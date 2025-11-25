<?php
namespace App\Models;

class DashboardModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'dashboard'; // Ajusta según tu estructura
    }

    // Métodos para el dashboard
    public function getTotalClientes() {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE estado = 'activo'";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    public function getDocumentosPendientes() {
        $sql = "SELECT COUNT(*) as total FROM documentos WHERE estado = 'Pendiente'";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    public function getFormatosActivos() {
        $sql = "SELECT COUNT(*) as total FROM formatos WHERE estado = 'Activo'";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetch()->total : 0;
    }

    public function getFormatosRecientes() {
        $sql = "SELECT f.*, a.nombre as area_nombre 
                FROM formatos f 
                LEFT JOIN areas a ON f.fk_area_id = a.id 
                ORDER BY f.ultima_actualizacion DESC 
                LIMIT 5";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getRecordatorios($usuarioId) {
        $sql = "SELECT * FROM recordatorios 
                WHERE usuario_id = ? AND completado = 0 
                ORDER BY fecha_vencimiento ASC 
                LIMIT 5";
        $stmt = $this->query($sql, [$usuarioId]);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getNotificaciones($usuarioId) {
        $sql = "SELECT * FROM notificaciones 
                WHERE usuario_id = ? OR usuario_id IS NULL 
                ORDER BY created_at DESC 
                LIMIT 10";
        $stmt = $this->query($sql, [$usuarioId]);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getAreas() {
        $sql = "SELECT id, nombre FROM areas ORDER BY nombre";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }
}
?>