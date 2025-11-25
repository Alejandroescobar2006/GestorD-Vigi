<?php
return [
    // ==========================================
    // RUTAS PRINCIPALES
    // ==========================================
    '/' => [
        'controller' => 'DashboardController',
        'action' => 'index'
    ],
    '/dashboard' => [
        'controller' => 'DashboardController',
        'action' => 'index'
    ],

    // ==========================================
    // RUTAS DE DOCUMENTOS (MÁS ESPECÍFICAS PRIMERO)
    // ==========================================
    '/dashboard/crear-documento' => [
        'controller' => 'DocumentosController',
        'action' => 'crear'
    ],
    '/dashboard/editar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'editar'
    ],
    '/dashboard/eliminar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'eliminar'
    ],
    '/dashboard/obtener-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'obtener'
    ],
    '/dashboard/descargar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'descargar'
    ],
    '/dashboard/previsualizar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'previsualizar'
    ],
    '/documentos' => [
        'controller' => 'DocumentosController',
        'action' => 'index'
    ],

    // ==========================================
    // RUTAS DE FORMATOS
    // ==========================================
    '/formatos/crear' => [
        'controller' => 'FormatosController',
        'action' => 'crear'
    ],
    '/formatos/editar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'editar'
    ],
    '/formatos/eliminar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'eliminar'
    ],
    '/formatos/obtener/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'obtener'
    ],
    '/formatos/descargar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'descargar'
    ],
    '/formatos/previsualizar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'previsualizar'
    ],
    '/formatos' => [
        'controller' => 'FormatosController',
        'action' => 'index'
    ],
    // Rutas para Documentos - permisos
    '/dashboard/actualizar-permisos-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'actualizarPermisos'
    ],

    // Rutas para Formatos - permisos  
    '/formatos/actualizar-permisos/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'actualizarPermisos'
    ],

    // Rutas existentes de documentos
    '/dashboard/crear-documento' => [
        'controller' => 'DocumentosController',
        'action' => 'crear'
    ],
    '/dashboard/editar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'editar'
    ],
    '/dashboard/eliminar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'eliminar'
    ],
    '/dashboard/previsualizar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'previsualizar'
    ],
    '/dashboard/descargar-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'descargar'
    ],
    '/dashboard/obtener-documento/(\d+)' => [
        'controller' => 'DocumentosController',
        'action' => 'obtener'
    ],

    // Rutas existentes de formatos
    '/formatos/crear' => [
        'controller' => 'FormatosController',
        'action' => 'crear'
    ],
    '/formatos/editar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'editar'
    ],
    '/formatos/eliminar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'eliminar'
    ],
    '/formatos/previsualizar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'previsualizar'
    ],
    '/formatos/descargar/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'descargar'
    ],
    '/formatos/obtener/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'obtener'
    ],
    '/formatos' => [
        'controller' => 'FormatosController',
        'action' => 'index'
    ],

    // Rutas para notificaciones de formatos
    '/formatos/notificaciones' => [
        'controller' => 'FormatosController',
        'action' => 'getNotificaciones'
    ],
    '/formatos/marcar-notificacion-leida/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'marcarNotificacionLeida'
    ],
    // Rutas para notificaciones de formatos
    '/formatos/notificaciones' => [
        'controller' => 'FormatosController',
        'action' => 'getNotificaciones'
    ],
    // Ruta temporal para probar notificaciones - ELIMINAR DESPUÉS
    '/formatos/probar-notificacion' => [
        'controller' => 'FormatosController',
        'action' => 'probarNotificacion'
    ],

    '/formatos/marcar-notificacion-leida/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'marcarNotificacionLeida'
    ],
    // Rutas para notificaciones de formatos
    '/formatos/notificaciones' => [
        'controller' => 'FormatosController',
        'action' => 'getNotificaciones'
    ],
    '/formatos/marcar-notificacion-leida/(\d+)' => [
        'controller' => 'FormatosController',
        'action' => 'marcarNotificacionLeida'
    ],

    // ==========================================
    // RUTAS DE CLIENTES (ACTUALIZADAS)
    // ==========================================
    '/dashboard/crear-cliente' => [
        'controller' => 'ClientesController',
        'action' => 'crear'
    ],
    '/dashboard/editar-cliente/(\d+)' => [
        'controller' => 'ClientesController',
        'action' => 'editar'
    ],
    '/dashboard/eliminar-cliente/(\d+)' => [
        'controller' => 'ClientesController',
        'action' => 'eliminar'
    ],
    '/dashboard/obtener-cliente/(\d+)' => [
        'controller' => 'ClientesController',
        'action' => 'obtener'
    ],
    '/clientes' => [
        'controller' => 'ClientesController',
        'action' => 'index'
    ],

    // ==========================================
    // RUTAS DE PERFIL
    // ==========================================
    '/dashboard/perfil' => [
        'controller' => 'PerfilController',
        'action' => 'index'
    ],
    '/aprendiz/perfil' => [
        'controller' => 'PerfilController',
        'action' => 'index'
    ],
    '/dashboard/actualizar-perfil' => [
        'controller' => 'PerfilController',
        'action' => 'actualizar'
    ],
    '/aprendiz/actualizar-perfil' => [
        'controller' => 'PerfilController',
        'action' => 'actualizar'
    ],

    // ==========================================
    // RUTAS DEL DASHBOARD
    // ==========================================
    '/dashboard/buscar' => [
        'controller' => 'DashboardController',
        'action' => 'buscarFormatos'
    ],
    '/dashboard/notificaciones' => [
        'controller' => 'DashboardController',
        'action' => 'getNotificaciones'
    ],

    // ==========================================
    // RUTAS DE AUTENTICACIÓN
    // ==========================================
    '/login' => [
        'controller' => 'AuthController',
        'action' => 'login'
    ],
    '/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout'
    ],
    // Rutas para perfil
'/dashboard/perfil' => [
    'controller' => 'PerfilController',
    'action' => 'index'
],
'/dashboard/actualizar-perfil' => [
    'controller' => 'PerfilController',
    'action' => 'actualizar'
],
'/dashboard/cambiar-password' => [
    'controller' => 'PerfilController',
    'action' => 'cambiarPassword'
],
'/dashboard/obtener-datos-edicion' => [
    'controller' => 'PerfilController',
    'action' => 'obtenerDatosEdicion'
],
'/dashboard/exportar-datos' => [
    'controller' => 'PerfilController',
    'action' => 'exportarDatos'
],
'/aprendiz/perfil' => [
    'controller' => 'PerfilController',
    'action' => 'index'
],
'/aprendiz/actualizar-perfil' => [
    'controller' => 'PerfilController',
    'action' => 'actualizar'
],
'/aprendiz/cambiar-password' => [
    'controller' => 'PerfilController',
    'action' => 'cambiarPassword'
],
'/aprendiz/obtener-datos-edicion' => [
    'controller' => 'PerfilController',
    'action' => 'obtenerDatosEdicion'
],
'/aprendiz/exportar-datos' => [
    'controller' => 'PerfilController',
    'action' => 'exportarDatos'
],
// ==========================================
// RUTAS PARA APRENDIZ (USUARIO ID 28)
// ==========================================
'/aprendiz/inicio' => [
    'controller' => 'AprendizController',
    'action' => 'inicio'
],
'/aprendiz/documentos' => [
    'controller' => 'AprendizController',
    'action' => 'documentos'
],
'/aprendiz/formatos' => [
    'controller' => 'AprendizController',
    'action' => 'formatos'
],
'/aprendiz/aprendices' => [
    'controller' => 'AprendizController',
    'action' => 'aprendices'
],
'/aprendiz/cursos' => [
    'controller' => 'AprendizController',
    'action' => 'cursos'
],
    '/aprendiz/inicio' => [
        'controller' => 'AprendizController',
        'action' => 'inicio'
    ],
    '/aprendiz/documentos' => [
        'controller' => 'AprendizController', 
        'action' => 'documentos'
    ],
    '/aprendiz/formatos' => [
        'controller' => 'AprendizController',
        'action' => 'formatos'
    ],
    '/aprendiz/aprendices' => [
        'controller' => 'AprendizController',
        'action' => 'aprendices'
    ],
    '/aprendiz/cursos' => [
        'controller' => 'AprendizController',
        'action' => 'cursos'
    ],
    // En routes.php - agrega esta ruta
'/aprendiz/descargar-certificado/(\d+)' => [
    'controller' => 'AprendizController',
    'action' => 'descargarCertificado'
],
// En routes.php - agrega estas rutas
'/aprendiz/crear-aprendiz' => [
    'controller' => 'AprendizController',
    'action' => 'crearAprendiz'
],
'/aprendiz/obtener-aprendiz/(\d+)' => [
    'controller' => 'AprendizController', 
    'action' => 'obtenerAprendiz'
],
'/aprendiz/actualizar-aprendiz/(\d+)' => [
    'controller' => 'AprendizController',
    'action' => 'actualizarAprendiz'
],
'/aprendiz/eliminar-aprendiz/(\d+)' => [
    'controller' => 'AprendizController',
    'action' => 'eliminarAprendiz'
],
];