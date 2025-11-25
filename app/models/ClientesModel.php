<?php
namespace App\Models;

class ClientesModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'clientes';
    }

     public function getTotalClientes() {
        $query = "SELECT COUNT(*) as total FROM clientes WHERE estado = 'activo'";
        $result = $this->db->query($query);
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function crearCliente($datos) {
        try {
            error_log("Creando cliente con datos: " . print_r($datos, true));
            
            $sql = "INSERT INTO clientes (
                nombre, 
                apellidos, 
                tipo_documento, 
                documento, 
                celular, 
                email, 
                direccion, 
                tipo_cliente, 
                empresa,
                fecha_ingreso,
                estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'activo')";
            
            $params = [
                $datos['nombre'],
                $datos['apellido'],
                $datos['tipo_documento'],
                $datos['documento'],
                $datos['celular'],
                $datos['email'],
                $datos['direccion'],
                $datos['tipo_cliente'],
                $datos['empresa']
            ];
            
            error_log("SQL: $sql");
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->query($sql, $params);
            $result = $stmt !== false;
            
            error_log("Resultado creación: " . ($result ? 'Éxito' : 'Falló'));
            return $result;
            
        } catch (\Exception $e) {
            error_log('Error en crearCliente: ' . $e->getMessage());
            return false;
        }
    }

    public function editarCliente($id, $datos) {
        try {
            error_log("Editando cliente ID: $id");
            $sql = "UPDATE clientes SET 
                nombre = ?,
                apellidos = ?,
                tipo_documento = ?,
                documento = ?,
                celular = ?,
                email = ?,
                direccion = ?,
                tipo_cliente = ?,
                empresa = ?,
                estado = ?
            WHERE id = ?";
            
            $params = [
                $datos['nombre'],
                $datos['apellido'],
                $datos['tipo_documento'],
                $datos['documento'],
                $datos['celular'],
                $datos['email'],
                $datos['direccion'],
                $datos['tipo_cliente'],
                $datos['empresa'],
                $datos['estado'],
                $id
            ];
            
            error_log("SQL: $sql");
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->query($sql, $params);
            $result = $stmt !== false;
            
            error_log("Resultado edición: " . ($result ? 'Éxito' : 'Falló'));
            return $result;
            
        } catch (\Exception $e) {
            error_log('Error en editarCliente: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminarCliente($id) {
        try {
            error_log("Eliminando cliente ID: $id");
            $sql = "DELETE FROM clientes WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            $result = $stmt !== false;
            
            error_log("Resultado eliminación: " . ($result ? 'Éxito' : 'Falló'));
            return $result;
            
        } catch (\Exception $e) {
            error_log('Error en eliminarCliente: ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerCliente($id) {
        try {
            error_log("Buscando cliente con ID: " . $id);
            $sql = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $this->query($sql, [$id]);
            
            if ($stmt) {
                $cliente = $stmt->fetch(\PDO::FETCH_OBJ);
                error_log("Cliente encontrado: " . ($cliente ? 'Sí' : 'No'));
                return $cliente;
            } else {
                error_log("Error en la consulta del cliente");
                return null;
            }
        } catch (\Exception $e) {
            error_log('Error en obtenerCliente: ' . $e->getMessage());
            return null;
        }
    }

    public function getClientes($filtros = []) {
        try {
            $sql = "SELECT * FROM clientes WHERE 1=1";
            
            $params = [];
            
            if (!empty($filtros['busqueda'])) {
                $sql .= " AND (nombre LIKE ? OR apellidos LIKE ? OR email LIKE ? OR documento LIKE ?)";
                $params[] = '%' . $filtros['busqueda'] . '%';
                $params[] = '%' . $filtros['busqueda'] . '%';
                $params[] = '%' . $filtros['busqueda'] . '%';
                $params[] = '%' . $filtros['busqueda'] . '%';
            }
            
            if (!empty($filtros['tipo'])) {
                $sql .= " AND tipo_cliente = ?";
                $params[] = $filtros['tipo'];
            }
            
            if (!empty($filtros['estado'])) {
                $sql .= " AND estado = ?";
                $params[] = $filtros['estado'];
            }
            
            $sql .= " ORDER BY fecha_ingreso DESC";
            
            error_log("SQL getClientes: $sql");
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->query($sql, $params);
            return $stmt ? $stmt->fetchAll() : [];
            
        } catch (\Exception $e) {
            error_log('Error en getClientes: ' . $e->getMessage());
            return [];
        }
    }
    
}
?>