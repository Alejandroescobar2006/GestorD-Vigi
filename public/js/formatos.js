// public/js/formatos.js

// Funcionalidades específicas de formatos
document.addEventListener('DOMContentLoaded', function() {
    initializeFormatos();
});

function initializeFormatos() {
    // Modal functionality
    const openModalBtn = document.getElementById('openModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelModalBtn = document.getElementById('cancelModal');

    if (openModalBtn && modalOverlay) {
        openModalBtn.addEventListener('click', () => {
            modalOverlay.classList.add('active');
        });

        function closeModal() {
            modalOverlay.classList.remove('active');
        }

        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
        
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.grid-row');
            
            rows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                if (textContent.includes(searchTerm)) {
                    row.style.display = 'grid';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Filtros
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    
    if (applyFilters) {
        applyFilters.addEventListener('click', aplicarFiltros);
    }
    
    if (resetFilters) {
        resetFilters.addEventListener('click', limpiarFiltros);
    }
}

function aplicarFiltros() {
    // Lógica para aplicar filtros
    console.log('Aplicando filtros...');
}

function limpiarFiltros() {
    // Lógica para limpiar filtros
    const searchInput = document.getElementById('searchInput');
    const areaFilter = document.getElementById('areaFilter');
    const versionFilter = document.getElementById('versionFilter');
    
    if (searchInput) searchInput.value = '';
    if (areaFilter) areaFilter.value = '';
    if (versionFilter) versionFilter.value = '';
    
    const rows = document.querySelectorAll('.grid-row');
    rows.forEach(row => {
        row.style.display = 'grid';
    });
}