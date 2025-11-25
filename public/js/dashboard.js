// dashboard.js
// public/js/dashboard.js

// Funcionalidades específicas del dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar estadísticas
    loadStatistics();
    
    // Inicializar recordatorios
    loadReminders();
    
    // Inicializar gráfico si existe
    initializeChart();
});

function loadStatistics() {
    // Los datos ya vienen del PHP, esta función es para futuras actualizaciones en tiempo real
    console.log('Estadísticas cargadas');
}

function loadReminders() {
    // Los recordatorios ya vienen del PHP, esta función es para futuras actualizaciones
    console.log('Recordatorios cargados');
}

function initializeChart() {
    const ctx = document.getElementById('activityChart');
    if (ctx) {
        // Aquí iría la inicialización de Chart.js
        const context = ctx.getContext('2d');
        context.fillStyle = '#f0f0f0';
        context.fillRect(0, 0, ctx.width, ctx.height);
        context.fillStyle = '#666';
        context.textAlign = 'center';
        context.fillText('Gráfico de actividad - Integrar Chart.js', ctx.width/2, ctx.height/2);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const closeMenu = document.querySelector('.close-menu');
    const searchInput = document.getElementById('searchInput');
    const areaFilter = document.getElementById('areaFilter');
    const versionFilter = document.getElementById('versionFilter');
    const dateFilter = document.getElementById('dateFilter');
    const resetFilters = document.getElementById('resetFilters');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    // Funcionalidad del menú hamburguesa
    menuToggle.addEventListener('click', () => {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
    });

    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    }

    closeMenu.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Notificaciones
    notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationDropdown.classList.toggle('active');
    });

    // Cerrar notificaciones al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
        }
    });

    // Búsqueda en tiempo real
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            buscarFormatos();
        }, 500);
    });

    // Filtros en tiempo real
    [areaFilter, versionFilter, dateFilter].forEach(filter => {
        filter.addEventListener('change', buscarFormatos);
    });

    // Restablecer filtros
    resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        areaFilter.value = '';
        versionFilter.value = '';
        dateFilter.value = '';
        buscarFormatos();
    });

    // Función para búsqueda en tiempo real
    function buscarFormatos() {
        const formData = new FormData();
        formData.append('busqueda', searchInput.value);
        formData.append('area', areaFilter.value);
        formData.append('version', versionFilter.value);
        formData.append('fecha', dateFilter.value);

        fetch('/dashboard/buscar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            actualizarListaFormatos(data.formatos);
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
        });
    }

    // Actualizar lista de formatos
    function actualizarListaFormatos(formatos) {
        const container = document.getElementById('formatosContainer');
        
        if (formatos.length === 0) {
            container.innerHTML = '<div class="no-results"><p>No se encontraron formatos</p></div>';
            return;
        }

        let html = '';
        formatos.forEach(formato => {
            html += `
                <div class="grid-row">
                    <div>${formato.fecha_creacion}</div>
                    <div>${escapeHtml(formato.nombre_formato)}</div>
                    <div>${escapeHtml(formato.area_nombre || 'N/A')}</div>
                    <div>${escapeHtml(formato.pesantante_id || 'N/A')}</div>
                    <div>${escapeHtml(formato.cargo_responsable || 'N/A')}</div>
                    <div>${escapeHtml(formato.version)}</div>
                    <div class="actions">
                        <button class="btn-action view" title="Ver" onclick="verFormato(${formato.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action edit" title="Editar" onclick="editarFormato(${formato.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action download" title="Descargar" onclick="descargarFormato(${formato.id})">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-action delete" title="Eliminar" onclick="eliminarFormato(${formato.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Funciones para acciones de formatos
    window.verFormato = function(id) {
        alert(`Ver formato ${id}`);
        // Implementar lógica para ver formato
    };

    window.editarFormato = function(id) {
        alert(`Editar formato ${id}`);
        // Implementar lógica para editar formato
    };

    window.descargarFormato = function(id) {
        alert(`Descargar formato ${id}`);
        // Implementar lógica para descargar formato
    };

    window.eliminarFormato = function(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este formato?')) {
            alert(`Eliminar formato ${id}`);
            // Implementar lógica para eliminar formato
        }
    };

    // Función auxiliar para escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Actualizar notificaciones periódicamente
    setInterval(actualizarNotificaciones, 30000); // Cada 30 segundos

    function actualizarNotificaciones() {
        fetch('/dashboard/notificaciones')
            .then(response => response.json())
            .then(data => {
                // Actualizar badge y lista de notificaciones
                const badge = document.getElementById('notificationBadge');
                const count = document.getElementById('notificationCount');
                const list = document.getElementById('notificationList');

                badge.textContent = data.notificaciones.length;
                count.textContent = data.notificaciones.length + ' nuevas';

                if (data.notificaciones.length === 0) {
                    list.innerHTML = '<div class="notification-item"><p>No hay notificaciones</p></div>';
                } else {
                    let html = '';
                    data.notificaciones.forEach(notif => {
                        html += `
                            <div class="notification-item ${notif.leido ? '' : 'unread'}">
                                <p><strong>${escapeHtml(notif.titulo)}</strong></p>
                                <p>${escapeHtml(notif.mensaje)}</p>
                                <span class="time">${notif.fecha}</span>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                }
            })
            .catch(error => console.error('Error al actualizar notificaciones:', error));
    }
});