<?php
namespace App\Models;

class FormatosModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'formatos';
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            return $stmt;
        } catch (\Exception $e) {
            error_log("❌ ERROR en query: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            return false;
        }
    }

    public function crearFormato($datos, $mimeType = null, $tamanio = null) {
        try {
            $this->db->beginTransaction();

            $esCompartido = !empty($datos['usuarios_compartir']) ? 1 : 0;

            // Guardar archivo en sistema de archivos con la nueva estructura
            $rutaArchivo = $this->guardarArchivoEnDisco(
                $datos['archivo_temporal'], 
                $datos['nombre_archivo'],
                $datos['area_id'] ?? 1,
                $datos['usuario_id'] ?? 1
            );
            
            if (!$rutaArchivo) {
                throw new \Exception('Error al guardar el archivo en el servidor');
            }

            // SQL CORREGIDO: Sin archivo_binario
            $sql = "INSERT INTO formatos (
                nombre_formato, 
                version, 
                fk_area_id, 
                descripcion, 
                fk_usuario_id,
                usuario_creador_id,
                archivo,
                ruta_archivo,
                mime_type,
                tamanio,
                ultima_actualizacion,
                es_compartido
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
            
            $params = [
                $datos['nombre_formato'],
                $datos['version'] ?? 'v1.0',
                $datos['area_id'] ?? 1,
                $datos['descripcion'] ?? '',
                $datos['usuario_id'] ?? 1,
                $datos['usuario_id'] ?? 1,
                $datos['nombre_archivo'],
                $rutaArchivo,
                $mimeType,
                $tamanio,
                $esCompartido
            ];
            
            error_log("INSERTANDO FORMATO CON RUTA: " . $rutaArchivo);
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                // Si falla la inserción, eliminar el archivo guardado
                $this->eliminarArchivoDelDisco($rutaArchivo);
                throw new \Exception('Error al insertar formato');
            }
            
            $formatoId = $this->db->lastInsertId();
            
            // Guardar permisos si se compartió con usuarios
            if (isset($datos['usuarios_compartir']) && !empty($datos['usuarios_compartir'])) {
                $this->guardarPermisosFormato($formatoId, $datos['usuarios_compartir'], $datos['permisos'] ?? 'lectura');
            }
            
            $this->db->commit();
            return $formatoId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR CREAR FORMATO: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Guarda el archivo en el sistema de archivos del servidor organizado por área y usuario
     */
    private function guardarArchivoEnDisco($archivoTemporal, $nombreArchivo, $areaId, $usuarioId) {
        try {
            // Obtener nombres de área y usuario para la estructura de carpetas
            $areaNombre = $this->getNombreArea($areaId);
            $usuarioNombre = $this->getNombreUsuario($usuarioId);
            
            // Sanitizar nombres para usar en rutas
            $areaFolder = $this->sanitizarNombreCarpeta($areaNombre ?: 'sin-area');
            $usuarioFolder = $this->sanitizarNombreCarpeta($usuarioNombre ?: 'sin-usuario');
            
            // Crear estructura de directorios
            $directorioBase = $_SERVER['DOCUMENT_ROOT'] . '/uploads/formatos/';
            $directorioCompleto = $directorioBase . $areaFolder . '/' . $usuarioFolder . '/';
            
            if (!is_dir($directorioCompleto)) {
                mkdir($directorioCompleto, 0755, true);
            }
            
            // Generar nombre único para el archivo manteniendo el nombre original
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreBase = pathinfo($nombreArchivo, PATHINFO_FILENAME);
            $nombreUnico = $this->sanitizarNombreArchivo($nombreBase) . '_' . uniqid() . '.' . $extension;
            $rutaCompleta = $directorioCompleto . $nombreUnico;
            
            // Mover archivo
            if (move_uploaded_file($archivoTemporal, $rutaCompleta)) {
                // Retornar ruta relativa para guardar en BD
                return '/uploads/formatos/' . $areaFolder . '/' . $usuarioFolder . '/' . $nombreUnico;
            } else {
                error_log("Error al mover archivo: " . $archivoTemporal . " -> " . $rutaCompleta);
                return false;
            }
            
        } catch (\Exception $e) {
            error_log("ERROR GUARDAR ARCHIVO DISCO: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el nombre del área
     */
    private function getNombreArea($areaId) {
        try {
            $sql = "SELECT nombre FROM areas WHERE id = ?";
            $stmt = $this->query($sql, [$areaId]);
            $area = $stmt ? $stmt->fetch() : null;
            return $area ? $area->nombre : null;
        } catch (\Exception $e) {
            error_log("Error obtener nombre área: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene el nombre del usuario
     */
    private function getNombreUsuario($usuarioId) {
        try {
            $sql = "SELECT nombre, apellidos FROM usuario WHERE id = ?";
            $stmt = $this->query($sql, [$usuarioId]);
            $usuario = $stmt ? $stmt->fetch() : null;
            if ($usuario) {
                return $usuario->nombre . '_' . $usuario->apellidos;
            }
            return null;
        } catch (\Exception $e) {
            error_log("Error obtener nombre usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Sanitiza nombres para carpetas (elimina caracteres especiales)
     */
    private function sanitizarNombreCarpeta($nombre) {
        // Reemplazar espacios y caracteres especiales
        $sanitizado = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\-]/', '_', $nombre);
        // Limitar longitud
        return substr($sanitizado, 0, 50);
    }

    /**
     * Sanitiza nombres para archivos
     */
    private function sanitizarNombreArchivo($nombre) {
        // Reemplazar espacios y caracteres especiales, mantener puntos para extensiones
        $sanitizado = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\-.]/', '_', $nombre);
        // Limitar longitud
        return substr($sanitizado, 0, 100);
    }

    /**
     * Elimina archivo del sistema de archivos
     */
    private function eliminarArchivoDelDisco($rutaArchivo) {
        try {
            if (empty($rutaArchivo)) return true;
            
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $rutaArchivo;
            if (file_exists($rutaCompleta)) {
                return unlink($rutaCompleta);
            }
            return true;
        } catch (\Exception $e) {
            error_log("ERROR ELIMINAR ARCHIVO DISCO: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el contenido del archivo desde el sistema de archivos
     */
    public function obtenerArchivoBinario($id) {
        try {
            $sql = "SELECT archivo, ruta_archivo, mime_type, nombre_formato FROM formatos WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if (!$stmt) return null;
            
            $formato = $stmt->fetch();
            if (!$formato || empty($formato->ruta_archivo)) {
                error_log("Formato sin ruta_archivo: " . $id);
                return null;
            }
            
            // Leer archivo desde sistema de archivos
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $formato->ruta_archivo;
            
            if (!file_exists($rutaCompleta)) {
                error_log("Archivo no encontrado: " . $rutaCompleta);
                return null;
            }
            
            $formato->archivo_binario = file_get_contents($rutaCompleta);
            return $formato;
            
        } catch (\Exception $e) {
            error_log('Error obtenerArchivoBinario: ' . $e->getMessage());
            return null;
        }
    }

    private function guardarPermisosFormato($formatoId, $usuariosCompartir, $tipoPermisos = 'lectura') {
        try {
            // Primero, eliminar permisos existentes
            $sqlDelete = "DELETE FROM formato_permisos WHERE formato_id = ?";
            $this->query($sqlDelete, [$formatoId]);
            
            $sql = "INSERT INTO formato_permisos (formato_id, usuario_id, permisos) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($usuariosCompartir as $usuarioId) {
                $stmt->execute([$formatoId, $usuarioId, $tipoPermisos]);
                error_log("✅ Permiso guardado - Formato: $formatoId, Usuario: $usuarioId, Permisos: $tipoPermisos");
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("❌ ERROR GUARDAR PERMISOS FORMATO: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerFormato($id) {
        try {
            $sql = "SELECT f.*, a.nombre as area_nombre, 
                    u_creador.nombre as creador_nombre,
                    u_creador.apellidos as creador_apellidos
                    FROM formatos f 
                    LEFT JOIN areas a ON f.fk_area_id = a.id 
                    LEFT JOIN usuario u_creador ON f.fk_usuario_id = u_creador.id
                    WHERE f.id = ?";
            $stmt = $this->query($sql, [$id]);
            return $stmt ? $stmt->fetch() : null;
        } catch (\Exception $e) {
            error_log('Error obtenerFormato: ' . $e->getMessage());
            return null;
        }
    }

    public function eliminarFormato($id) {
        try {
            // Primero obtener la ruta del archivo
            $sqlSelect = "SELECT ruta_archivo FROM formatos WHERE id = ?";
            $stmtSelect = $this->query($sqlSelect, [$id]);
            $formato = $stmtSelect ? $stmtSelect->fetch() : null;
            
            $this->db->beginTransaction();
            
            // Eliminar permisos
            $sqlPermisos = "DELETE FROM formato_permisos WHERE formato_id = ?";
            $this->query($sqlPermisos, [$id]);
            
            // Eliminar modificaciones
            $sqlModificaciones = "DELETE FROM formato_modificaciones WHERE formato_id = ?";
            $this->query($sqlModificaciones, [$id]);
            
            // Eliminar notificaciones
            $sqlNotificaciones = "DELETE FROM notificaciones_formatos WHERE formato_id = ?";
            $this->query($sqlNotificaciones, [$id]);
            
            // Eliminar formato
            $sql = "DELETE FROM formatos WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if ($stmt !== false) {
                // Eliminar archivo físico si existe
                if ($formato && $formato->ruta_archivo) {
                    $this->eliminarArchivoDelDisco($formato->ruta_archivo);
                }
                
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Error eliminarFormato: ' . $e->getMessage());
            return false;
        }
    }

    public function editarFormato($id, $datos) {
        try {
            $sql = "UPDATE formatos SET 
                    nombre_formato = ?, 
                    version = ?, 
                    fk_area_id = ?, 
                    descripcion = ?, 
                    ultima_actualizacion = NOW() 
                    WHERE id = ?";
            
            $params = [
                $datos['nombre_formato'],
                $datos['version'],
                $datos['area_id'],
                $datos['descripcion'],
                $id
            ];
            
            $stmt = $this->query($sql, $params);
            return $stmt !== false;
        } catch (\Exception $e) {
            error_log('Error editarFormato: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza formato con archivo nuevo
     */
    public function editarFormatoConArchivo($id, $datos, $archivoTemporal = null, $nombreArchivo = null, $mimeType = null, $tamanio = null) {
        try {
            $this->db->beginTransaction();
            
            // Si hay nuevo archivo
            if ($archivoTemporal && $nombreArchivo) {
                // Obtener datos actuales del formato para la estructura de carpetas
                $sqlActual = "SELECT fk_area_id, fk_usuario_id, ruta_archivo FROM formatos WHERE id = ?";
                $stmtActual = $this->query($sqlActual, [$id]);
                $formatoActual = $stmtActual ? $stmtActual->fetch() : null;
                
                if (!$formatoActual) {
                    throw new \Exception('Formato no encontrado');
                }
                
                // Obtener archivo anterior para eliminarlo
                $rutaArchivoAnterior = $formatoActual->ruta_archivo;
                
                // Guardar nuevo archivo con la misma estructura
                $rutaArchivo = $this->guardarArchivoEnDisco(
                    $archivoTemporal, 
                    $nombreArchivo,
                    $formatoActual->fk_area_id,
                    $formatoActual->fk_usuario_id
                );
                
                if (!$rutaArchivo) {
                    throw new \Exception('Error al guardar el nuevo archivo');
                }
                
                $sql = "UPDATE formatos SET 
                        nombre_formato = ?, 
                        version = ?, 
                        fk_area_id = ?, 
                        descripcion = ?, 
                        archivo = ?,
                        ruta_archivo = ?,
                        mime_type = ?,
                        tamanio = ?,
                        ultima_actualizacion = NOW() 
                        WHERE id = ?";
                
                $params = [
                    $datos['nombre_formato'],
                    $datos['version'],
                    $datos['area_id'],
                    $datos['descripcion'],
                    $nombreArchivo,
                    $rutaArchivo,
                    $mimeType,
                    $tamanio,
                    $id
                ];
                
                // Eliminar archivo anterior
                if ($rutaArchivoAnterior) {
                    $this->eliminarArchivoDelDisco($rutaArchivoAnterior);
                }
                
            } else {
                // Solo actualizar metadatos
                $sql = "UPDATE formatos SET 
                        nombre_formato = ?, 
                        version = ?, 
                        fk_area_id = ?, 
                        descripcion = ?, 
                        ultima_actualizacion = NOW() 
                        WHERE id = ?";
                
                $params = [
                    $datos['nombre_formato'],
                    $datos['version'],
                    $datos['area_id'],
                    $datos['descripcion'],
                    $id
                ];
            }
            
            $stmt = $this->query($sql, $params);
            $resultado = $stmt !== false;
            
            if ($resultado) {
                $this->db->commit();
            } else {
                $this->db->rollBack();
            }
            
            return $resultado;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Error editarFormatoConArchivo: ' . $e->getMessage());
            return false;
        }
    }

    public function tienePermisoFormato($formatoId, $usuarioId, $permisoRequerido = 'lectura') {
        try {
            // El propietario siempre tiene todos los permisos
            $sqlPropietario = "SELECT 1 FROM formatos WHERE id = ? AND fk_usuario_id = ? LIMIT 1";
            $stmtPropietario = $this->query($sqlPropietario, [$formatoId, $usuarioId]);
            if ($stmtPropietario && $stmtPropietario->fetch()) {
                return true;
            }
            
            // Verificar permisos específicos
            $sql = "SELECT permisos FROM formato_permisos 
                    WHERE formato_id = ? AND usuario_id = ? 
                    LIMIT 1";
            $stmt = $this->query($sql, [$formatoId, $usuarioId]);
            
            if ($stmt && $permisos = $stmt->fetch()) {
                $permisosUsuario = $permisos->permisos;
                
                // Jerarquía de permisos
                $jerarquia = ['lectura' => 1, 'edicion' => 2];
                $nivelRequerido = $jerarquia[$permisoRequerido] ?? 1;
                $nivelUsuario = $jerarquia[$permisosUsuario] ?? 0;
                
                return $nivelUsuario >= $nivelRequerido;
            }
            
            return false;
        } catch (\Exception $e) {
            error_log('Error tienePermisoFormato: ' . $e->getMessage());
            return false;
        }
    }

    public function getFormatosPorUsuario($usuarioId, $filtros = []) {
        $sql = "SELECT DISTINCT 
                    f.*, 
                    a.nombre as area_nombre,
                    u_creador.nombre as creador_nombre,
                    u_creador.apellidos as creador_apellidos,
                    CASE 
                        WHEN f.fk_usuario_id = ? THEN 'propio'
                        WHEN fp.permisos IS NOT NULL THEN 'compartido'
                        ELSE 'sin_acceso'
                    END as tipo_formato,
                    fp.permisos as permisos_usuario
                FROM formatos f 
                LEFT JOIN areas a ON f.fk_area_id = a.id 
                LEFT JOIN usuario u_creador ON f.fk_usuario_id = u_creador.id
                LEFT JOIN formato_permisos fp ON f.id = fp.formato_id AND fp.usuario_id = ?
                WHERE (f.fk_usuario_id = ? OR fp.usuario_id = ? OR fp.permisos IS NOT NULL)";
        
        $params = [$usuarioId, $usuarioId, $usuarioId, $usuarioId];
        
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (f.nombre_formato LIKE ? OR f.descripcion LIKE ?)";
            $params[] = '%' . $filtros['busqueda'] . '%';
            $params[] = '%' . $filtros['busqueda'] . '%';
        }
        
        if (!empty($filtros['area'])) {
            $sql .= " AND f.fk_area_id = ?";
            $params[] = $filtros['area'];
        }
        
        if (!empty($filtros['version'])) {
            $sql .= " AND f.version = ?";
            $params[] = $filtros['version'];
        }
        
        // Filtro para mostrar solo formatos compartidos
        if (isset($filtros['solo_compartidos']) && $filtros['solo_compartidos']) {
            $sql .= " AND fp.usuario_id = ? AND f.fk_usuario_id != ?";
            $params[] = $usuarioId;
            $params[] = $usuarioId;
        }
        
        // Filtro para mostrar solo formatos propios
        if (isset($filtros['solo_propios']) && $filtros['solo_propios']) {
            $sql .= " AND f.fk_usuario_id = ?";
            $params[] = $usuarioId;
        }
        
        $sql .= " ORDER BY f.ultima_actualizacion DESC";
        
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function actualizarPermisosFormato($formatoId, $usuariosCompartir, $tipoPermisos = 'lectura') {
        try {
            $this->db->beginTransaction();
            
            // Eliminar permisos existentes
            $sqlDelete = "DELETE FROM formato_permisos WHERE formato_id = ?";
            $this->query($sqlDelete, [$formatoId]);
            
            // Si no hay usuarios para compartir, actualizar es_compartido a 0
            if (empty($usuariosCompartir)) {
                $sqlUpdate = "UPDATE formatos SET es_compartido = 0 WHERE id = ?";
                $this->query($sqlUpdate, [$formatoId]);
            } else {
                // Insertar nuevos permisos
                $sqlInsert = "INSERT INTO formato_permisos (formato_id, usuario_id, permisos) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sqlInsert);
                
                foreach ($usuariosCompartir as $usuarioId) {
                    $stmt->execute([$formatoId, $usuarioId, $tipoPermisos]);
                }
                
                // Actualizar es_compartido a 1
                $sqlUpdate = "UPDATE formatos SET es_compartido = 1 WHERE id = ?";
                $this->query($sqlUpdate, [$formatoId]);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR ACTUALIZAR PERMISOS FORMATO: " . $e->getMessage());
            return false;
        }
    }

    public function getUsuariosParaCompartir() {
        $sql = "SELECT id, nombre, apellidos, email 
                FROM usuario 
                WHERE estado = 'activo' AND id != ?
                ORDER BY nombre, apellidos";
        $stmt = $this->query($sql, [$_SESSION['user']['id'] ?? 0]);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getUsuariosCompartidosFormato($formatoId) {
        $sql = "SELECT u.id, u.nombre, u.apellidos, u.email, fp.permisos 
                FROM formato_permisos fp 
                INNER JOIN usuario u ON fp.usuario_id = u.id 
                WHERE fp.formato_id = ?";
        $stmt = $this->query($sql, [$formatoId]);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getAreas() {
        $sql = "SELECT id, nombre FROM areas ORDER BY nombre";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }
}
?>