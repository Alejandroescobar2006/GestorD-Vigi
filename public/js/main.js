// public/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    initializeMenu();
    initializeNotifications();
});

function initializeMenu() {
    const menuToggle = document.getElementById('menuToggle'); // ✅ usa el ID
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const closeMenu = document.querySelector('.close-menu');
    const mainContent = document.querySelector('.main-content');

    if (menuToggle && sidebar) {
        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // 🔹 Alternar apertura y cierre al hacer clic en el botón hamburguesa
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.classList.contains('active')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        // 🔹 Cerrar al hacer clic en el botón "X"
        if (closeMenu) {
            closeMenu.addEventListener('click', closeSidebar);
        }

        // 🔹 Cerrar al hacer clic en el overlay (modo móvil)
        sidebarOverlay.addEventListener('click', closeSidebar);

        // 🔹 Cerrar al hacer clic en un enlace del menú (solo móvil)
        const menuLinks = document.querySelectorAll('.menu-item');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    closeSidebar();
                }
            });
        });

        // 🔹 Cerrar sidebar al hacer clic fuera (modo escritorio)
        document.addEventListener('click', (e) => {
            if (
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !menuToggle.contains(e.target)
            ) {
                closeSidebar();
            }
        });
    }
}

function initializeNotifications() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function() {
            notificationDropdown.classList.remove('active');
        });
    }
}

// Función global para mostrar loading
function showLoading() {
    // Implementar spinner de carga
}

function hideLoading() {
    // Ocultar spinner de carga
}

function showMessage(message, type = 'success') {
    console.log(`${type}: ${message}`);
}
