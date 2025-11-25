<?php
// app/views/dashboard/index.php
$pageTitle = 'Main - Vigitecol';
$currentSection = 'Main';
$customScript = '/js/main.js';
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/dashboard.css">    <title><?php echo $pageTitle ?? 'Vigitecol'; ?></title>

    <style>
        /* --- 🔹 Sidebar que empuja el contenido --- */
        .app-container {
            display: flex;
            transition: margin-left 0.3s ease;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            height: 100vh;
            background-color: #1e1e2f;
            color: #fff;
            transition: left 0.3s ease;
            z-index: 2000;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .main-content {
            flex: 1;
            margin-left: 0;
            transition: margin-left 0.3s ease;
            width: 100%;
        }

        /* 🔹 Cuando el sidebar está activo, el contenido se corre */
        .sidebar.active ~ .main-content {
            margin-left: 250px;
        }

        .sidebar-overlay {
            display: none;
        }

        /* --- 🔹 Responsive --- */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1500;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .sidebar.active ~ .main-content {
                margin-left: 0;
            }
        }

        /* --- 🔹 Header y botón menú --- */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 3000;
            height: 60px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* 🔹 Ícono menú hamburguesa */
        .menu-toggle {
            background: none;
            border: none;
            color: #000; /* negro garantizado */
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 5px; /* corre un poco el logo */
        }

        .logo {
            height: 40px;
        }

        .app-name {
            font-weight: 600;
            color: #1e1e2f;
            font-size: 18px;
        }

        .main-content {
            margin-top: 60px; /* evita que el header tape el contenido */
            padding: 20px;
        }

        /* 🔹 Ícono del menú más visible en móviles también */
        @media (max-width: 768px) {
            .menu-toggle {
                color: #000;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <!-- 🔹 Botón menú hamburguesa -->
                <button class="menu-toggle" id="menuToggle" title="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- 🔹 Logo -->
                <div class="logo-container">
                    <img src="/images/logoVigitecol.png" alt="Vigitecol" class="logo">
                    <span class="app-name">Vigitecol Docs</span>
                </div>
            </div>
            
            <div class="header-right">
                <!-- Notificaciones -->
                <div class="notifications">
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notificaciones</h4>
                            <span class="notification-count" id="notificationCount">0 nuevas</span>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-item">
                                <p>No hay notificaciones</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Usuario -->
                <div class="user-menu">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <span class="user-name"><?php echo htmlspecialchars($user['nombre'] ?? 'Usuario'); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </header>

        <!-- Sidebar Menu -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Menú Principal</h3>
                <button class="close-menu" id="closeMenu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <ul class="menu-items">
                <li><a href="/dashboard" class="menu-item <?php echo ($currentSection ?? '') === 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
                <li><a href="/formatos" class="menu-item <?php echo ($currentSection ?? '') === 'formatos' ? 'active' : ''; ?>"><i class="fas fa-table"></i><span>Formatos</span></a></li>
                <li><a href="/documentos" class="menu-item <?php echo ($currentSection ?? '') === 'documentos' ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i><span>Documentos</span></a></li>
                <li><a href="/clientes" class="menu-item <?php echo ($currentSection ?? '') === 'clientes' ? 'active' : ''; ?>"><i class="fas fa-users"></i><span>Clientes</span></a></li>
                <li><a href="/dashboard/perfil" class="menu-item <?php echo ($currentSection ?? '') === 'perfil' ? 'active' : ''; ?>"><i class="fas fa-user"></i><span>Mi Perfil</span></a></li>
                <li class="menu-divider"></li>
                <li><a href="/logout" class="menu-item logout-btn"><i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span></a></li>
            </ul>
        </nav>

        <!-- Overlay para móvil -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="content-wrapper">
                <?php echo $content; ?>
            </div>
        </main>
    </div>

    <script src="/js/main.js"></script>

    <!-- 🔹 Script adicional para abrir/cerrar -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeMenu = document.getElementById('closeMenu');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        closeMenu.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // 🔹 Cerrar al hacer clic fuera (solo escritorio)
        document.addEventListener('click', (e) => {
            if (
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !menuToggle.contains(e.target)
            ) {
                closeSidebar();
            }
        });
    });
    </script>

    <?php if (isset($customScript)): ?>
        <script src="<?php echo $customScript; ?>"></script>
    <?php endif; ?>
</body>
</html>
