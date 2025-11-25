<?php
namespace App\Models;

class PerfilModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'usuario';
    }

    public function getUserInfo($userId) {
        $sql = "SELECT u.*, a.nombre as area_nombre, c.nombre as cargo_nombre
                FROM usuario u 
                LEFT JOIN areas a ON u.fk_id_area = a.id 
                LEFT JOIN cargos c ON u.cargo_id = c.id 
                WHERE u.id = ?";
        $stmt = $this->query($sql, [$userId]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function getEstadisticasUsuario($userId) {
        return [
            'documentosSubidos' => $this->countDocumentosUsuario($userId),
            'formatosCreados' => $this->countFormatosUsuario($userId),
            'clientesRegistrados' => $this->countClientesUsuario($userId),
            'actividadMensual' => $this->countActividadMensual($userId)
        ];
    }

    private function countDocumentosUsuario($userId) {
        $sql = "SELECT COUNT(*) as total FROM documentos WHERE fk_usuario_id = ?";
        $stmt = $this->query($sql, [$userId]);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    private function countFormatosUsuario($userId) {
        $sql = "SELECT COUNT(*) as total FROM formatos WHERE fk_usuario_id = ?";
        $stmt = $this->query($sql, [$userId]);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    private function countClientesUsuario($userId) {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE usuario_creador = ?";
        $stmt = $this->query($sql, [$userId]);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    private function countActividadMensual($userId) {
        $sql = "SELECT COUNT(*) as total FROM log_actividad 
                WHERE usuario_id = ? AND MONTH(fecha) = MONTH(NOW())";
        $stmt = $this->query($sql, [$userId]);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    public function actualizarPerfil($userId, $datos) {
        try {
            $sql = "UPDATE usuario SET 
                    nombre = ?,
                    apellidos = ?,
                    email = ?,
                    celular = ?,
                    updated_at = NOW()
                    WHERE id = ?";
            
            $params = [
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['celular'],
                $userId
            ];
            
            $stmt = $this->query($sql, $params);
            return $stmt !== false;
            
        } catch (\Exception $e) {
            error_log('Error en actualizarPerfil: ' . $e->getMessage());
            return false;
        }
    }
}
?>