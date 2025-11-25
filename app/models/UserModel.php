<?php
namespace App\Models;

class UserModel extends BaseModel {
    protected $table = 'usuario'; // Cambiado de 'usuarios' a 'usuario'

    public function checkCredentials($email, $password) {
        error_log("=== INICIANDO VALIDACIÓN ===");
        error_log("Email recibido: " . $email);
        error_log("Password recibido: " . $password);
        
        // Buscar usuario por email - CORREGIDO: estado = 'activo' (minúscula)
        $sql = "SELECT * FROM usuario WHERE email = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->query($sql, [$email]);
        
        if (!$stmt) {
            error_log("❌ Error en la consulta SQL");
            return false;
        }
        
        $user = $stmt->fetch();
        
        if (!$user) {
            error_log("❌ Usuario no encontrado en BD o inactivo");
            error_log("SQL ejecutada: " . $sql . " con email: " . $email);
            return false;
        }
        
        error_log("✅ Usuario encontrado en BD:");
        error_log("ID: " . $user->id);
        error_log("Nombre: " . $user->nombre);
        error_log("Email: " . $user->email);
        error_log("Tipo usuario: " . $user->tipo_usuario);
        error_log("Estado: " . $user->estado);
        error_log("Password en BD: " . ($user->password ? 'EXISTE' : 'NULL/VACÍO'));
        
        // DEBUG: Mostrar el password real de la BD (solo para desarrollo)
        error_log("Password BD (crudo): " . $user->password);
        
        // Verificar si el usuario no tiene password en BD
        if (empty($user->password) || $user->password === '' || $user->password === 'password') {
            error_log("🔓 Usuario tiene password por defecto o vacío");
            
            // Si el password en BD es 'password' o está vacío, permitir acceso con 'password'
            if ($password === 'password') {
                error_log("🎯 Acceso concedido (password por defecto 'password')");
                return $user;
            }
            
            // También probar con '123456' por si acaso
            if ($password === '123456') {
                error_log("🎯 Acceso concedido (password por defecto '123456')");
                return $user;
            }
            
            error_log("❌ Password por defecto incorrecto. Se esperaba 'password' o '123456'");
            return false;
        }
        
        // Si tiene password, verificar con password_verify
        if (password_verify($password, $user->password)) {
            error_log("🎯 Acceso concedido (password hash correcto)");
            return $user;
        } else {
            error_log("❌ Password hash incorrecto");
            error_log("Password ingresado: " . $password);
            error_log("Hash en BD: " . $user->password);
            
            // Intentar con comparación directa por si el password no está hasheado
            if ($password === $user->password) {
                error_log("🎯 Acceso concedido (password directo - sin hash)");
                return $user;
            }
            
            return false;
        }
    }

    public function updateLastLogin($userId) {
        // Primero verificamos si la columna last_login existe
        $checkSql = "SHOW COLUMNS FROM usuario LIKE 'last_login'";
        $checkStmt = $this->query($checkSql);
        
        if ($checkStmt && $checkStmt->fetch()) {
            $sql = "UPDATE usuario SET last_login = NOW() WHERE id = ?";
            return $this->query($sql, [$userId]);
        }
        
        // Si no existe la columna, no hacemos nada
        error_log("⚠️ Columna last_login no existe en la tabla usuario");
        return true;
    }

    // Método para establecer password a usuarios
    public function setDefaultPassword($userId, $password = 'password') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET password = ? WHERE id = ?";
        return $this->query($sql, [$hashedPassword, $userId]);
    }

    // Método adicional para debug: listar todos los usuarios
    public function getAllUsers() {
        $sql = "SELECT id, nombre, email, tipo_usuario, estado, password FROM usuario";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }
}
?>