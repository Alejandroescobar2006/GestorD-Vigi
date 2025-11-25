<?php
namespace App\Models;

class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->connect();
    }

    protected function connect() {
        try {
            $dsn = DRIVER . ":host=" . HOST . ";dbname=" . DATABASE . ";charset=" . CHARSET;
            $this->db = new \PDO($dsn, USERNAME_DB, PASSWORD_DB);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    protected function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage());
            return false;
        }
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : false;
    }

    public function where($conditions = [], $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "$field = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }
        
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }
    public function getAreas() {
        $sql = "SELECT id, nombre FROM areas ORDER BY nombre";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }
}
?>