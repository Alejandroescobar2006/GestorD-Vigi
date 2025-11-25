<?php
namespace App\Models;

class AprendicesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'aprendices';
    }

    public function getAprendices($filtros = [])
    {
        $sql = "SELECT * FROM aprendices WHERE 1=1";
        $params = [];

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (nombre LIKE ? OR apellidos LIKE ? OR cedula LIKE ? OR correo LIKE ?)";
            $searchTerm = '%' . $filtros['busqueda'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filtros['estado'];
        }

        $sql .= " ORDER BY nombre, apellidos";

        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function obtenerAprendiz($id)
    {
        $sql = "SELECT * FROM aprendices WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function crearAprendiz($datos, $mimeType = null, $tamanio = null)
    {
        try {
            $this->db->beginTransaction();

            // Guardar certificado en sistema de archivos si existe
            $rutaCertificado = null;
            if (isset($datos['certificado_temporal']) && isset($datos['nombre_certificado'])) {
                $rutaCertificado = $this->guardarCertificadoEnDisco(
                    $datos['certificado_temporal'], 
                    $datos['nombre_certificado'],
                    $datos['nombre'],
                    $datos['apellidos']
                );
                
                if (!$rutaCertificado) {
                    throw new \Exception('Error al guardar el certificado en el servidor');
                }
            }

            $sql = "INSERT INTO aprendices (
                nombre, apellidos, cedula, telefono, correo, 
                certificado, ruta_certificado, mime_type, tamanio, notas, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params = [
                $datos['nombre'],
                $datos['apellidos'],
                $datos['cedula'],
                $datos['telefono'] ?? '',
                $datos['correo'] ?? '',
                $datos['nombre_certificado'] ?? null,
                $rutaCertificado,
                $mimeType,
                $tamanio,
                $datos['notas'] ?? '',
                $datos['estado'] ?? 'activo'
            ];

            $stmt = $this->query($sql, $params);
            
            if (!$stmt) {
                // Si falla la inserción, eliminar el archivo guardado
                if ($rutaCertificado) {
                    $this->eliminarCertificadoDelDisco($rutaCertificado);
                }
                throw new \Exception('Error al insertar aprendiz');
            }
            
            $aprendizId = $this->db->lastInsertId();
            $this->db->commit();
            return $aprendizId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR CREAR APRENDIZ: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarAprendiz($id, $datos, $certificadoTemporal = null, $nombreCertificado = null, $mimeType = null, $tamanio = null)
    {
        try {
            $this->db->beginTransaction();

            // Si hay nuevo certificado
            if ($certificadoTemporal && $nombreCertificado) {
                // Obtener certificado anterior para eliminarlo
                $sqlAnterior = "SELECT ruta_certificado FROM aprendices WHERE id = ?";
                $stmtAnterior = $this->query($sqlAnterior, [$id]);
                $aprendizAnterior = $stmtAnterior ? $stmtAnterior->fetch() : null;
                
                // Guardar nuevo certificado
                $rutaCertificado = $this->guardarCertificadoEnDisco(
                    $certificadoTemporal, 
                    $nombreCertificado,
                    $datos['nombre'],
                    $datos['apellidos']
                );
                
                if (!$rutaCertificado) {
                    throw new \Exception('Error al guardar el nuevo certificado');
                }
                
                $sql = "UPDATE aprendices SET 
                    nombre = ?, apellidos = ?, cedula = ?, telefono = ?, correo = ?,
                    certificado = ?, ruta_certificado = ?, mime_type = ?, tamanio = ?, notas = ?, estado = ?
                    WHERE id = ?";
                
                $params = [
                    $datos['nombre'],
                    $datos['apellidos'],
                    $datos['cedula'],
                    $datos['telefono'] ?? '',
                    $datos['correo'] ?? '',
                    $nombreCertificado,
                    $rutaCertificado,
                    $mimeType,
                    $tamanio,
                    $datos['notas'] ?? '',
                    $datos['estado'] ?? 'activo',
                    $id
                ];
                
                // Eliminar certificado anterior
                if ($aprendizAnterior && $aprendizAnterior->ruta_certificado) {
                    $this->eliminarCertificadoDelDisco($aprendizAnterior->ruta_certificado);
                }
                
            } else {
                // Solo actualizar datos sin certificado
                $sql = "UPDATE aprendices SET 
                    nombre = ?, apellidos = ?, cedula = ?, telefono = ?, correo = ?,
                    notas = ?, estado = ?
                    WHERE id = ?";
                
                $params = [
                    $datos['nombre'],
                    $datos['apellidos'],
                    $datos['cedula'],
                    $datos['telefono'] ?? '',
                    $datos['correo'] ?? '',
                    $datos['notas'] ?? '',
                    $datos['estado'] ?? 'activo',
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
            error_log("ERROR ACTUALIZAR APRENDIZ: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarAprendiz($id)
    {
        try {
            $this->db->beginTransaction();
            
            // Primero obtener la ruta del certificado
            $sqlSelect = "SELECT ruta_certificado FROM aprendices WHERE id = ?";
            $stmtSelect = $this->query($sqlSelect, [$id]);
            $aprendiz = $stmtSelect ? $stmtSelect->fetch() : null;
            
            // Eliminar aprendiz
            $sql = "DELETE FROM aprendices WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if ($stmt !== false) {
                // Eliminar certificado físico si existe
                if ($aprendiz && $aprendiz->ruta_certificado) {
                    $this->eliminarCertificadoDelDisco($aprendiz->ruta_certificado);
                }
                
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR ELIMINAR APRENDIZ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Guarda el certificado en el sistema de archivos con nombre del aprendiz
     */
    private function guardarCertificadoEnDisco($certificadoTemporal, $nombreCertificado, $nombreAprendiz, $apellidosAprendiz) {
        try {
            // Crear nombre del archivo usando nombre y apellidos del aprendiz
            $nombreArchivo = $this->sanitizarNombreArchivo($nombreAprendiz . '_' . $apellidosAprendiz);
            
            // Crear estructura de directorios
            $directorioBase = $_SERVER['DOCUMENT_ROOT'] . '/uploads/certificados/';
            
            if (!is_dir($directorioBase)) {
                mkdir($directorioBase, 0755, true);
            }
            
            // Generar nombre único manteniendo el nombre del aprendiz
            $extension = pathinfo($nombreCertificado, PATHINFO_EXTENSION);
            $nombreUnico = $nombreArchivo . '_' . uniqid() . '.' . $extension;
            $rutaCompleta = $directorioBase . $nombreUnico;
            
            // Mover archivo
            if (move_uploaded_file($certificadoTemporal, $rutaCompleta)) {
                // Retornar ruta relativa para guardar en BD
                return '/uploads/certificados/' . $nombreUnico;
            } else {
                error_log("Error al mover certificado: " . $certificadoTemporal . " -> " . $rutaCompleta);
                return false;
            }
            
        } catch (\Exception $e) {
            error_log("ERROR GUARDAR CERTIFICADO DISCO: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina certificado del sistema de archivos
     */
    private function eliminarCertificadoDelDisco($rutaCertificado) {
        try {
            if (empty($rutaCertificado)) return true;
            
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $rutaCertificado;
            if (file_exists($rutaCompleta)) {
                return unlink($rutaCompleta);
            }
            return true;
        } catch (\Exception $e) {
            error_log("ERROR ELIMINAR CERTIFICADO DISCO: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el contenido del certificado desde el sistema de archivos
     */
    public function obtenerCertificado($id) {
        try {
            $sql = "SELECT certificado, ruta_certificado, mime_type FROM aprendices WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if (!$stmt) return null;
            
            $aprendiz = $stmt->fetch();
            if (!$aprendiz || empty($aprendiz->ruta_certificado)) {
                error_log("Aprendiz sin ruta_certificado: " . $id);
                return null;
            }
            
            // Leer certificado desde sistema de archivos
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $aprendiz->ruta_certificado;
            
            if (!file_exists($rutaCompleta)) {
                error_log("Certificado no encontrado: " . $rutaCompleta);
                return null;
            }
            
            $aprendiz->certificado_binario = file_get_contents($rutaCompleta);
            return $aprendiz;
            
        } catch (\Exception $e) {
            error_log('Error obtenerCertificado: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sanitiza nombres para archivos
     */
    private function sanitizarNombreArchivo($nombre) {
        // Reemplazar espacios y caracteres especiales, mantener puntos para extensiones
        $sanitizado = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\-.]/', '_', $nombre);
        // Reemplazar múltiples espacios/guiones bajos por uno solo
        $sanitizado = preg_replace('/_{2,}/', '_', $sanitizado);
        // Eliminar guiones bajos al inicio y final
        $sanitizado = trim($sanitizado, '_');
        // Limitar longitud
        return substr($sanitizado, 0, 100);
    }

    /**
     * Sanitiza nombres para carpetas (elimina caracteres especiales)
     */
    private function sanitizarNombreCarpeta($nombre) {
        // Reemplazar espacios y caracteres especiales
        $sanitizado = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\-]/', '_', $nombre);
        // Reemplazar múltiples guiones bajos por uno solo
        $sanitizado = preg_replace('/_{2,}/', '_', $sanitizado);
        // Eliminar guiones bajos al inicio y final
        $sanitizado = trim($sanitizado, '_');
        // Limitar longitud
        return substr($sanitizado, 0, 50);
    }
}
?>