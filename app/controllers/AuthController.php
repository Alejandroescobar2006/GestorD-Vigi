<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController {
    public function login() {
        // Si ya está autenticado, redirigir
        if (isset($_SESSION['user']) && $_SESSION['user']['authenticated']) {
            $this->redirect('/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $errors = [];
            
            if (empty($email)) {
                $errors[] = "El correo electrónico es requerido";
            }
            
            if (empty($password)) {
                $errors[] = "La contraseña es requerida";
            }

            if (empty($errors)) {
                $userModel = new UserModel();
                
                // DEBUG: Listar usuarios para verificar
                error_log("=== LISTANDO USUARIOS EN BD ===");
                $allUsers = $userModel->getAllUsers();
                foreach ($allUsers as $u) {
                    error_log("Usuario: " . $u->email . " - Pass: " . ($u->password ? $u->password : 'NULL'));
                }
                
                $user = $userModel->checkCredentials($email, $password);
                
                if ($user) {
                    // INICIAR SESIÓN
                    session_regenerate_id(true);
                    
                    $_SESSION['user'] = [
                        'id' => $user->id,
                        'email' => $user->email,
                        'nombre' => $user->nombre,
                        'apellido' => $user->apellidos,
                        'tipo_usuario' => $user->tipo_usuario,
                        'documento' => $user->documento,
                        'authenticated' => true
                    ];
                    
                    $_SESSION['last_activity'] = time();
                    
                    // Actualizar último login
                    $userModel->updateLastLogin($user->id);
                    
                    // 🔥 REDIRECCIÓN ESPECIAL PARA EL APRENDIZ (ID 28)
                    if ($user->id == 28) {
                        $this->redirect('/aprendiz/inicio');
                    } else {
                        // REDIRIGIR AL DASHBOARD NORMAL
                        $this->redirect('/dashboard');
                    }
                    exit;
                    
                } else {
                    $errors[] = "Credenciales incorrectas.";
                    error_log("❌ FALLO EN LOGIN - Email: $email");
                }
            }
            
            $this->view('auth/login', ['errors' => $errors, 'email' => $email]);
            
        } else {
            // Mostrar formulario
            $this->view('auth/login');
        }
    }

    public function logout() {
        // Limpiar sesión
        session_unset();
        session_destroy();
        
        // Redirigir al login
        header('Location: /login');
        exit;
    }
}
?>