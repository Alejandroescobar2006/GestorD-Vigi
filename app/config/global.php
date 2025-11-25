<?php
// app/config/global.php

// Configuración de la aplicación
define("INACTIVE_TIME", 30); // 30 minutos de inactividad
define("DRIVER", 'mysql');
define("HOST", 'localhost');
define("USERNAME_DB", 'root');
define("PASSWORD_DB", '');
define("DATABASE", 'vigitecoldocs');
define("CHARSET", 'utf8mb4');
define("COLLATION", 'utf8mb4_unicode_ci');

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar zona horaria
date_default_timezone_set('America/Bogota');

// Función para debug
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// Función para log
function log_message($message) {
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message);
}
?>