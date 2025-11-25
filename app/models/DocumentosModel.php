<?php
namespace App\Models;

class DocumentosModel extends BaseModel {    
    public function __construct() {
        parent::__construct();
        $this->table = 'documentos';
    }

    public function getDocumentosPendientes() {
        $query = "SELECT COUNT(*) as total FROM documentos WHERE estado = 'pendiente'";
        $result = $this->db->query($query);
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    public function getDocumentosCompletados() {
        $query = "SELECT COUNT(*) as total FROM documentos 
                 WHERE estado = 'completado' 
                 AND MONTH(fecha_creacion) = MONTH(CURRENT_DATE())";
        $result = $this->db->query($query);
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function crearDocumento($datos, $mimeType = null, $tamanio = null) {
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

            $sql = "INSERT INTO documentos (
                nombre_doc, 
                tipo_doc, 
                version, 
                fk_area_id, 
                descripcion, 
                fk_usuario_id, 
                archivo,
                ruta_archivo,
                mime_type,
                tamanio,
                es_compartido
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $datos['nombre_doc'],
                $datos['tipo_doc'],
                $datos['version'] ?? 'v1.0',
                $datos['area_id'] ?? 1,
                $datos['descripcion'] ?? '',
                $datos['usuario_id'] ?? 1,
                $datos['nombre_archivo'],
                $rutaArchivo,
                $mimeType,
                $tamanio,
                $esCompartido
            ];
            
            error_log("INSERTANDO DOCUMENTO CON RUTA: " . $rutaArchivo);
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                // Si falla la inserción, eliminar el archivo guardado
                $this->eliminarArchivoDelDisco($rutaArchivo);
                throw new \Exception('Error al insertar documento');
            }
            
            $documentoId = $this->db->lastInsertId();
            
            // Guardar permisos si se compartió con usuarios
            if (isset($datos['usuarios_compartir']) && !empty($datos['usuarios_compartir'])) {
                $this->guardarPermisosDocumento($documentoId, $datos['usuarios_compartir'], $datos['permisos'] ?? 'lectura');
            }
            
            $this->db->commit();
            return $documentoId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR CREAR DOCUMENTO: " . $e->getMessage());
            return false;
        }
    }

private function guardarArchivoEnDisco($archivoTemporal, $nombreArchivo, $areaId, $usuarioId) {
    try {
        // Obtener nombres de área y usuario para la estructura de carpetas
        $areaNombre = $this->getNombreArea($areaId);
        $usuarioNombre = $this->getNombreUsuario($usuarioId);
        
        // Sanitizar nombres para usar en rutas
        $areaFolder = $this->sanitizarNombreCarpeta($areaNombre ?: 'sin-area');
        $usuarioFolder = $this->sanitizarNombreCarpeta($usuarioNombre ?: 'sin-usuario');
        
        // CORREGIDO: Crear estructura de directorios con /uploads/documentos/
        $directorioBase = $_SERVER['DOCUMENT_ROOT'] . '/uploads/documentos/';
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
            // CORREGIDO: Retornar ruta relativa correcta
            return '/uploads/documentos/' . $areaFolder . '/' . $usuarioFolder . '/' . $nombreUnico;
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

   
private function eliminarArchivoDelDisco($rutaArchivo) {
    try {
        if (empty($rutaArchivo)) return true;
        
        // CORREGIDO: La ruta ya incluye /uploads/documentos/
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
    
public function obtenerArchivoBinario($id) {
    try {
        $sql = "SELECT archivo, ruta_archivo, mime_type FROM documentos WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        
        if (!$stmt) return null;
        
        $documento = $stmt->fetch();
        if (!$documento || empty($documento->ruta_archivo)) {
            error_log("Documento sin ruta_archivo: " . $id);
            return null;
        }
        
        // CORREGIDO: La ruta ya incluye /uploads/documentos/
        $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $documento->ruta_archivo;
        
        if (!file_exists($rutaCompleta)) {
            error_log("Archivo no encontrado: " . $rutaCompleta);
            return null;
        }
        
        $documento->archivo_binario = file_get_contents($rutaCompleta);
        return $documento;
        
    } catch (\Exception $e) {
        error_log('Error obtenerArchivoBinario: ' . $e->getMessage());
        return null;
    }
}
    public function eliminarDocumento($id) {
        try {
            // Primero obtener la ruta del archivo
            $sqlSelect = "SELECT ruta_archivo FROM documentos WHERE id = ?";
            $stmtSelect = $this->query($sqlSelect, [$id]);
            $documento = $stmtSelect ? $stmtSelect->fetch() : null;
            
            $this->db->beginTransaction();
            
            // Eliminar permisos
            $sqlPermisos = "DELETE FROM documento_permisos WHERE documento_id = ?";
            $this->query($sqlPermisos, [$id]);
            
            // Eliminar modificaciones
            $sqlModificaciones = "DELETE FROM documento_modificaciones WHERE documento_id = ?";
            $this->query($sqlModificaciones, [$id]);
            
            // Eliminar documento
            $sql = "DELETE FROM documentos WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if ($stmt !== false) {
                // Eliminar archivo físico si existe
                if ($documento && $documento->ruta_archivo) {
                    $this->eliminarArchivoDelDisco($documento->ruta_archivo);
                }
                
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Error eliminarDocumento: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza documento (incluyendo archivo si se proporciona)
     */
    public function editarDocumento($id, $datos, $archivoTemporal = null, $nombreArchivo = null, $mimeType = null, $tamanio = null) {
        try {
            $this->db->beginTransaction();
            
            // Si hay nuevo archivo
            if ($archivoTemporal && $nombreArchivo) {
                // Obtener datos actuales del documento para la estructura de carpetas
                $sqlActual = "SELECT fk_area_id, fk_usuario_id, ruta_archivo FROM documentos WHERE id = ?";
                $stmtActual = $this->query($sqlActual, [$id]);
                $documentoActual = $stmtActual ? $stmtActual->fetch() : null;
                
                if (!$documentoActual) {
                    throw new \Exception('Documento no encontrado');
                }
                
                // Obtener archivo anterior para eliminarlo
                $rutaArchivoAnterior = $documentoActual->ruta_archivo;
                
                // Guardar nuevo archivo con la misma estructura
                $rutaArchivo = $this->guardarArchivoEnDisco(
                    $archivoTemporal, 
                    $nombreArchivo,
                    $documentoActual->fk_area_id,
                    $documentoActual->fk_usuario_id
                );
                
                if (!$rutaArchivo) {
                    throw new \Exception('Error al guardar el nuevo archivo');
                }
                
                $sql = "UPDATE documentos SET 
                        nombre_doc = ?, 
                        tipo_doc = ?, 
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
                    $datos['nombre_doc'],
                    $datos['tipo_doc'],
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
                $sql = "UPDATE documentos SET 
                        nombre_doc = ?, 
                        tipo_doc = ?, 
                        version = ?, 
                        fk_area_id = ?, 
                        descripcion = ?, 
                        ultima_actualizacion = NOW() 
                        WHERE id = ?";
                
                $params = [
                    $datos['nombre_doc'],
                    $datos['tipo_doc'],
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
            error_log('Error editarDocumento: ' . $e->getMessage());
            return false;
        }
    }

    private function guardarPermisosDocumento($documentoId, $usuariosCompartir, $tipoPermisos = 'lectura') {
        try {
            // Primero, eliminar permisos existentes para evitar duplicados
            $sqlDelete = "DELETE FROM documento_permisos WHERE documento_id = ?";
            $this->query($sqlDelete, [$documentoId]);
            
            // Insertar nuevos permisos
            $sql = "INSERT INTO documento_permisos (documento_id, usuario_id, permisos) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($usuariosCompartir as $usuarioId) {
                $stmt->execute([$documentoId, $usuarioId, $tipoPermisos]);
                error_log("Permiso guardado - Documento: $documentoId, Usuario: $usuarioId, Permisos: $tipoPermisos");
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("ERROR GUARDAR PERMISOS DOCUMENTO: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDocumento($id) {
        try {
            $sql = "SELECT d.*, a.nombre as area_nombre, 
                    u_creador.nombre as creador_nombre,
                    u_creador.apellidos as creador_apellidos
                    FROM documentos d 
                    LEFT JOIN areas a ON d.fk_area_id = a.id 
                    LEFT JOIN usuario u_creador ON d.fk_usuario_id = u_creador.id
                    WHERE d.id = ?";
            $stmt = $this->query($sql, [$id]);
            return $stmt ? $stmt->fetch() : null;
        } catch (\Exception $e) {
            error_log('Error obtenerDocumento: ' . $e->getMessage());
            return null;
        }
    }

    public function getDocumentos($filtros = [], $usuarioId = null) {
        if ($usuarioId === null) {
            $usuarioId = $_SESSION['user']['id'] ?? 0;
        }
        
        $sql = "SELECT DISTINCT 
                    d.*, 
                    a.nombre as area_nombre,
                    u_creador.nombre as creador_nombre,
                    u_creador.apellidos as creador_apellidos,
                    CASE 
                        WHEN d.fk_usuario_id = ? THEN 'propio'
                        WHEN dp.permisos IS NOT NULL THEN 'compartido'
                        ELSE 'sin_acceso'
                    END as tipo_documento,
                    dp.permisos as permisos_usuario
                FROM documentos d 
                LEFT JOIN areas a ON d.fk_area_id = a.id 
                LEFT JOIN usuario u_creador ON d.fk_usuario_id = u_creador.id
                LEFT JOIN documento_permisos dp ON d.id = dp.documento_id AND dp.usuario_id = ?
                WHERE (d.fk_usuario_id = ? OR dp.usuario_id = ? OR dp.permisos IS NOT NULL)";
        
        $params = [$usuarioId, $usuarioId, $usuarioId, $usuarioId];
        
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (d.nombre_doc LIKE ? OR d.descripcion LIKE ?)";
            $params[] = '%' . $filtros['busqueda'] . '%';
            $params[] = '%' . $filtros['busqueda'] . '%';
        }
        
        if (!empty($filtros['area'])) {
            $sql .= " AND d.fk_area_id = ?";
            $params[] = $filtros['area'];
        }
        
        if (!empty($filtros['tipo'])) {
            $sql .= " AND d.tipo_doc = ?";
            $params[] = $filtros['tipo'];
        }
        
        // Filtro para mostrar solo documentos compartidos
        if (isset($filtros['solo_compartidos']) && $filtros['solo_compartidos']) {
            $sql .= " AND dp.usuario_id = ? AND d.fk_usuario_id != ?";
            $params[] = $usuarioId;
            $params[] = $usuarioId;
        }
        
        // Filtro para mostrar solo documentos propios
        if (isset($filtros['solo_propios']) && $filtros['solo_propios']) {
            $sql .= " AND d.fk_usuario_id = ?";
            $params[] = $usuarioId;
        }
        
        $sql .= " ORDER BY d.ultima_actualizacion DESC";
        
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function tienePermisoDocumento($documentoId, $usuarioId, $permisoRequerido = 'lectura') {
        try {
            // El propietario siempre tiene todos los permisos
            $sqlPropietario = "SELECT 1 FROM documentos WHERE id = ? AND fk_usuario_id = ? LIMIT 1";
            $stmtPropietario = $this->query($sqlPropietario, [$documentoId, $usuarioId]);
            if ($stmtPropietario && $stmtPropietario->fetch()) {
                return true;
            }
            
            // Verificar permisos específicos
            $sql = "SELECT permisos FROM documento_permisos 
                    WHERE documento_id = ? AND usuario_id = ? 
                    LIMIT 1";
            $stmt = $this->query($sql, [$documentoId, $usuarioId]);
            
            if ($stmt && $permisos = $stmt->fetch()) {
                $permisosUsuario = $permisos->permisos;
                
                // Jerarquía de permisos
                $jerarquia = ['lectura' => 1, 'edicion' => 2, 'propietario' => 3];
                $nivelRequerido = $jerarquia[$permisoRequerido] ?? 1;
                $nivelUsuario = $jerarquia[$permisosUsuario] ?? 0;
                
                return $nivelUsuario >= $nivelRequerido;
            }
            
            return false;
        } catch (\Exception $e) {
            error_log('Error tienePermisoDocumento: ' . $e->getMessage());
            return false;
        }
    }

    public function actualizarPermisosDocumento($documentoId, $usuariosCompartir, $tipoPermisos = 'lectura') {
        try {
            $this->db->beginTransaction();
            
            // Eliminar permisos existentes
            $sqlDelete = "DELETE FROM documento_permisos WHERE documento_id = ?";
            $this->query($sqlDelete, [$documentoId]);
            
            // Si no hay usuarios para compartir, actualizar es_compartido a 0
            if (empty($usuariosCompartir)) {
                $sqlUpdate = "UPDATE documentos SET es_compartido = 0 WHERE id = ?";
                $this->query($sqlUpdate, [$documentoId]);
            } else {
                // Insertar nuevos permisos
                $sqlInsert = "INSERT INTO documento_permisos (documento_id, usuario_id, permisos) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sqlInsert);
                
                foreach ($usuariosCompartir as $usuarioId) {
                    $stmt->execute([$documentoId, $usuarioId, $tipoPermisos]);
                }
                
                // Actualizar es_compartido a 1
                $sqlUpdate = "UPDATE documentos SET es_compartido = 1 WHERE id = ?";
                $this->query($sqlUpdate, [$documentoId]);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR ACTUALIZAR PERMISOS DOCUMENTO: " . $e->getMessage());
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

    public function getUsuariosCompartidosDocumento($documentoId) {
        $sql = "SELECT u.id, u.nombre, u.apellidos, u.email, dp.permisos 
                FROM documento_permisos dp 
                INNER JOIN usuario u ON dp.usuario_id = u.id 
                WHERE dp.documento_id = ?";
        $stmt = $this->query($sql, [$documentoId]);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getAreas() {
        $sql = "SELECT id, nombre FROM areas ORDER BY nombre";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }
    
}
?>