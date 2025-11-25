<?php
namespace App\Models;

class CarpetasModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'carpetas';
    }

    public function crearCarpeta($datos)
    {
        try {
            $this->db->beginTransaction();

            // Generar ruta basada en la carpeta padre
            $ruta = $this->generarRuta($datos['carpeta_padre_id'] ?? null, $datos['nombre']);

            $sql = "INSERT INTO carpetas (
                nombre, ruta, fk_area_id, fk_usuario_id, carpeta_padre_id
            ) VALUES (?, ?, ?, ?, ?)";
            
            $params = [
                $datos['nombre'],
                $ruta,
                $datos['area_id'] ?? null,
                $datos['usuario_id'],
                $datos['carpeta_padre_id'] ?? null
            ];

            $stmt = $this->query($sql, $params);
            
            if (!$stmt) {
                throw new \Exception('Error al crear carpeta');
            }
            
            $carpetaId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $carpetaId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERROR CREAR CARPETA: " . $e->getMessage());
            return false;
        }
    }


    private function generarRuta($carpetaPadreId, $nombreCarpeta)
    {
        if (!$carpetaPadreId) {
            return '/' . $this->sanitizarNombreCarpeta($nombreCarpeta) . '/';
        }
        
        // Obtener ruta de la carpeta padre
        $sql = "SELECT ruta FROM carpetas WHERE id = ?";
        $stmt = $this->query($sql, [$carpetaPadreId]);
        $carpetaPadre = $stmt ? $stmt->fetch() : null;
        
        if ($carpetaPadre) {
            return $carpetaPadre->ruta . $this->sanitizarNombreCarpeta($nombreCarpeta) . '/';
        }
        
        return '/' . $this->sanitizarNombreCarpeta($nombreCarpeta) . '/';
    }

    public function obtenerCarpeta($id)
    {
        try {
            $sql = "SELECT c.*, a.nombre as area_nombre, 
                    u.nombre as usuario_nombre, u.apellidos as usuario_apellidos,
                    cp.nombre as carpeta_padre_nombre
                    FROM carpetas c 
                    LEFT JOIN areas a ON c.fk_area_id = a.id 
                    LEFT JOIN usuario u ON c.fk_usuario_id = u.id
                    LEFT JOIN carpetas cp ON c.carpeta_padre_id = cp.id
                    WHERE c.id = ?";
            $stmt = $this->query($sql, [$id]);
            return $stmt ? $stmt->fetch() : null;
        } catch (\Exception $e) {
            error_log('Error obtenerCarpeta: ' . $e->getMessage());
            return null;
        }
    }

    public function getCarpetasPorUsuario($usuarioId, $carpetaPadreId = null, $areaId = null)
{
    try {
        $sql = "SELECT DISTINCT 
                    c.*, 
                    a.nombre as area_nombre,
                    CASE 
                        WHEN c.fk_usuario_id = ? THEN 'propio'
                        WHEN cp.permisos IS NOT NULL THEN 'compartido'
                        ELSE 'sin_acceso'
                    END as tipo_carpeta,
                    cp.permisos as permisos_usuario
                FROM carpetas c 
                LEFT JOIN areas a ON c.fk_area_id = a.id 
                LEFT JOIN carpeta_permisos cp ON c.id = cp.carpeta_id AND cp.usuario_id = ?
                WHERE (c.fk_usuario_id = ? OR cp.usuario_id = ? OR cp.permisos IS NOT NULL)
                AND c.estado = 'activa'";
        
        $params = [$usuarioId, $usuarioId, $usuarioId, $usuarioId];
        
        if ($carpetaPadreId === null) {
            $sql .= " AND c.carpeta_padre_id IS NULL";
        } else {
            $sql .= " AND c.carpeta_padre_id = ?";
            $params[] = $carpetaPadreId;
        }
        
        if ($areaId) {
            $sql .= " AND c.fk_area_id = ?";
            $params[] = $areaId;
        }
        
        $sql .= " ORDER BY c.nombre";
        
        error_log("🔍 SQL Carpetas: " . $sql);
        error_log("🔍 Parámetros Carpetas: " . print_r($params, true));
        
        $stmt = $this->query($sql, $params);
        $result = $stmt ? $stmt->fetchAll() : [];
        
        error_log("📁 Resultado carpetas: " . count($result));
        return $result;
        
    } catch (\Exception $e) {
        error_log("❌ Error en getCarpetasPorUsuario: " . $e->getMessage());
        return [];
    }
}

public function getRutaCompleta($carpetaId)
{
    try {
        $ruta = [];
        $currentId = $carpetaId;
        
        while ($currentId) {
            $sql = "SELECT id, nombre, carpeta_padre_id FROM carpetas WHERE id = ?";
            $stmt = $this->query($sql, [$currentId]);
            $carpeta = $stmt ? $stmt->fetch() : null;
            
            if ($carpeta) {
                array_unshift($ruta, $carpeta);
                $currentId = $carpeta->carpeta_padre_id;
            } else {
                break;
            }
        }
        
        error_log("🛣️ Ruta encontrada para carpeta $carpetaId: " . count($ruta) . " elementos");
        return $ruta;
        
    } catch (\Exception $e) {
        error_log("❌ Error en getRutaCompleta: " . $e->getMessage());
        return [];
    }
}

    private function guardarPermisosCarpeta($carpetaId, $usuariosCompartir, $tipoPermisos = 'lectura')
    {
        try {
            $sql = "INSERT INTO carpeta_permisos (carpeta_id, usuario_id, permisos) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($usuariosCompartir as $usuarioId) {
                $stmt->execute([$carpetaId, $usuarioId, $tipoPermisos]);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("ERROR GUARDAR PERMISOS CARPETA: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCarpeta($id)
    {
        try {
            $this->db->beginTransaction();
            
            // Verificar si la carpeta tiene contenido
            $contenido = $this->tieneContenido($id);
            if ($contenido) {
                throw new \Exception('No se puede eliminar la carpeta porque contiene archivos o subcarpetas');
            }
            
            // Eliminar permisos
            $sqlPermisos = "DELETE FROM carpeta_permisos WHERE carpeta_id = ?";
            $this->query($sqlPermisos, [$id]);
            
            // Eliminar carpeta
            $sql = "DELETE FROM carpetas WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if ($stmt !== false) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Error eliminarCarpeta: ' . $e->getMessage());
            return false;
        }
    }

    private function tieneContenido($carpetaId)
    {
        // Verificar subcarpetas
        $sqlCarpetas = "SELECT COUNT(*) as total FROM carpetas WHERE carpeta_padre_id = ? AND estado = 'activa'";
        $stmtCarpetas = $this->query($sqlCarpetas, [$carpetaId]);
        $carpetas = $stmtCarpetas ? $stmtCarpetas->fetch() : null;
        
        // Verificar documentos
        $sqlDocumentos = "SELECT COUNT(*) as total FROM documentos WHERE carpeta_id = ?";
        $stmtDocumentos = $this->query($sqlDocumentos, [$carpetaId]);
        $documentos = $stmtDocumentos ? $stmtDocumentos->fetch() : null;
        
        // Verificar formatos
        $sqlFormatos = "SELECT COUNT(*) as total FROM formatos WHERE carpeta_id = ?";
        $stmtFormatos = $this->query($sqlFormatos, [$carpetaId]);
        $formatos = $stmtFormatos ? $stmtFormatos->fetch() : null;
        
        return ($carpetas && $carpetas->total > 0) || 
               ($documentos && $documentos->total > 0) || 
               ($formatos && $formatos->total > 0);
    }

    private function sanitizarNombreCarpeta($nombre)
    {
        $sanitizado = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\-]/', '_', $nombre);
        return substr($sanitizado, 0, 100);
    }
}
?>