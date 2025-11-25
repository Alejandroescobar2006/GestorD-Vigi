<?php
namespace App\Controllers;

class BaseController {
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "<h1>Error: Vista no encontrada</h1>";
            echo "<p><strong>Ruta buscada:</strong> $viewPath</p>";
            exit;
        }
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function checkAuth() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['authenticated']) {
            $this->redirect('/login');
        }
        
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > INACTIVE_TIME * 60)) {
            $this->logout();
        }
        
        $_SESSION['last_activity'] = time();
    }

    protected function logout() {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        $this->redirect('/login');
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>