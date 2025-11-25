<?php
// public/index.php

// Iniciar sesión
session_start();

// Cargar archivos necesarios
require_once __DIR__ . '/../app/config/global.php';

// Controladores
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/FormatosController.php';
require_once __DIR__ . '/../app/controllers/DocumentosController.php';  
require_once __DIR__ . '/../app/controllers/ClientesController.php';
require_once __DIR__ . '/../app/controllers/PerfilController.php';
require_once __DIR__ . '/../app/controllers/AprendizController.php';

// Modelos
require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/DashboardModel.php';
require_once __DIR__ . '/../app/models/FormatosModel.php';
require_once __DIR__ . '/../app/models/DocumentosModel.php';
require_once __DIR__ . '/../app/models/ClientesModel.php';
require_once __DIR__ . '/../app/models/PerfilModel.php';
require_once __DIR__ . '/../app/models/AprendicesModel.php';
// Obtener ruta actual
$rutaActual = $_SERVER['REQUEST_URI'];
if (strpos($rutaActual, '?') !== false) {
    $rutaActual = substr($rutaActual, 0, strpos($rutaActual, '?'));
}

// Limpiar ruta base si está en subdirectorio
$basePath = '/public';
if (strpos($rutaActual, $basePath) === 0) {
    $rutaActual = substr($rutaActual, strlen($basePath));
}

// Asegurar que empiece con /
if (empty($rutaActual) || $rutaActual[0] !== '/') {
    $rutaActual = '/' . $rutaActual;
}

// Log para debug
error_log("========================================");
error_log("RUTA SOLICITADA: {$rutaActual}");
error_log("MÉTODO: {$_SERVER['REQUEST_METHOD']}");

// Rutas permitidas sin sesión
$rutasPermitidas = ['/login', '/logout', '/auth/login', '/css/', '/js/', '/images/'];

// Verificar sesión 
if (!isset($_SESSION['user'])) {
    $permitido = false;
    
    foreach ($rutasPermitidas as $ruta) {
        if (strpos($rutaActual, $ruta) === 0) {
            $permitido = true;
            break;
        }
    }
    
    if (!$permitido && $rutaActual !== '/login') {
        error_log("❌ Sin sesión, redirigiendo a login");
        header('Location: /login');
        exit;
    }
}

// Cargar rutas
$rutas = require __DIR__ . '/../app/config/routes.php';
$rutaEncontrada = false;

// Buscar ruta coincidente
foreach ($rutas as $patron => $config) {
    // Convertir patrón a regex
    // Ejemplo: /dashboard/obtener-documento/(\d+) -> /^\/dashboard\/obtener-documento\/(\d+)$/
    $patronRegex = str_replace('/', '\/', $patron);
    $patronRegex = '/^' . $patronRegex . '$/';
    
    error_log("Comparando con patrón: {$patron}");
    error_log("Regex: {$patronRegex}");
    
    // Intentar hacer match
    if (preg_match($patronRegex, $rutaActual, $matches)) {
        error_log("✅ MATCH ENCONTRADO!");
        error_log("Matches: " . print_r($matches, true));
        
        // Remover el primer elemento (match completo)
        array_shift($matches);
        
        $nombreControlador = "App\\Controllers\\" . $config['controller'];
        $accion = $config['action'];
        
        error_log("Controller: {$nombreControlador}");
        error_log("Action: {$accion}");
        error_log("Parámetros: " . print_r($matches, true));
        
        // Verificar si el controlador existe
        if (!class_exists($nombreControlador)) {
            error_log("❌ Controlador no encontrado: " . $nombreControlador);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Controlador no encontrado']);
            exit;
        }
        
        try {
            $controlador = new $nombreControlador();
            
            // Verificar si el método existe
            if (!method_exists($controlador, $accion)) {
                error_log("❌ Método no encontrado: {$accion}");
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Método no encontrado']);
                exit;
            }
            
            // Ejecutar con parámetros
            call_user_func_array([$controlador, $accion], $matches);
            $rutaEncontrada = true;
            break;
            
        } catch (\Exception $e) {
            error_log("❌ EXCEPCIÓN: " . $e->getMessage());
            error_log($e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            exit;
        }
    }
}

// Si no hay coincidencia
if (!$rutaEncontrada) {
    error_log("❌ RUTA NO ENCONTRADA: {$rutaActual}");
    
    // Si es petición AJAX, devolver JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ruta no encontrada']);
        exit;
    }
    
    // Si es navegador, redirigir
    if ($rutaActual !== '/login') {
        header('Location: /login');
        exit;
    }
}
?>