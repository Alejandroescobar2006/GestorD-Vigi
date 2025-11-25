<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = 'Gestor de Documentos - Vigitecol';
$currentSection = 'documentos';
$customScript = '/js/documentos.js';

// Obtener carpeta actual desde la URL
$carpetaActual = $_GET['carpeta'] ?? null;
$usuarioActualId = $_SESSION['user']['id'] ?? 0;

ob_start();
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f6fa;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .main-content {
        padding: 2rem;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        width: 100%;
    }

    .section-header h1 {
        color: #333;
        font-size: 2rem;
        text-align: left;
    }

    .btn-primary {
        background: #3498db;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        width: 100%;
    }

    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .filters-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        width: 100%;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #555;
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .filter-select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-apply {
        background: #3498db;
        color: white;
    }

    .btn-apply:hover {
        background: #2980b9;
    }

    .btn-reset {
        background: #e0e0e0;
        color: #555;
    }

    .btn-reset:hover {
        background: #d0d0d0;
    }

    /* Grid Container */
    .grid-header {
        display: grid;
        grid-template-columns: 1fr 1.5fr 0.8fr 1fr 0.8fr 1fr 1.2fr;
        gap: 1rem;
        padding: 1rem;
        background: #e5e906;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-align: center;
        align-items: center;
        width: 100%;
    }

    .grid-header>div {
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 1fr 1.5fr 0.8fr 1fr 0.8fr 1fr 1.2fr;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-bottom: 1px solid #eee;
        align-items: center;
        text-align: center;
        width: 100%;
    }

    .grid-row>div {
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        min-height: 50px;
    }

    .grid-row:hover {
        background: #f8f9fa;
    }

    .grid-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
    }

    .btn-action {
        padding: 0.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        background: transparent;
        color: #000;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.share:hover {
        color: #9b59b6;
    }

    /* Para el estado indeterminado de checkboxes */
    input[type="checkbox"]:indeterminate {
        background: #3498db;
        border-color: #3498db;
    }

    .btn-action.view:hover {
        color: #f39c12;
    }

    .btn-action.edit:hover {
        color: #3498db;
    }

    .btn-action.download:hover {
        color: #27ae60;
    }

    .btn-action.delete:hover {
        color: #e74c3c;
    }

    .btn-action:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }

    .btn-action:disabled:hover::after {
        display: none;
    }

    /* Tooltip styles */
    .btn-action::after {
        content: attr(title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
        z-index: 10;
    }

    .btn-action:hover::after {
        opacity: 1;
        visibility: visible;
    }

    .document-type {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 70px;
    }

    .type-pdf {
        background: #ffebee;
        color: #c62828;
    }

    .type-doc {
        background: #e3f2fd;
        color: #1565c0;
    }

    .type-xls {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .type-ppt {
        background: #fff3e0;
        color: #ef6c00;
    }

    .type-otro {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #333;
        font-size: 1.5rem;
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0.25rem;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .close-modal:hover {
        background: #f0f0f0;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #555;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .file-upload {
        border: 2px dashed #ddd;
        border-radius: 6px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }

    .file-upload:hover {
        border-color: #3498db;
        background: #f8f9fa;
    }

    .file-upload i {
        font-size: 2rem;
        color: #3498db;
        margin-bottom: 1rem;
    }

    .file-upload p {
        margin: 0;
        color: #666;
    }

    .file-input {
        display: none;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .checkbox-group:hover {
        background: #f8f9fa;
    }

    .checkbox-group input {
        width: auto;
    }

    .usuarios-grid {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 1rem;
        background: #f8f9fa;
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #555;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .btn-success {
        background: #27ae60;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background: #219653;
    }

    .btn-success:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    .no-results {
        text-align: center;
        padding: 2rem;
        color: #666;
        grid-column: 1 / -1;
    }

    .small-text {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.5rem;
    }

    .file-name {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #333;
    }

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .usuarios-compartir-container {
        margin-top: 1rem;
    }

    .usuarios-grid {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 1rem;
        background: #f8f9fa;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .checkbox-group:hover {
        background: #e9ecef;
    }

    .checkbox-group input {
        width: auto;
    }

    /* ===== ESTILOS PAGINADOR ===== */
    .pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 25px 0;
        gap: 15px;
        width: 100%;
    }

    .pagination-info {
        font-size: 14px;
        color: #666;
        font-weight: 500;
        text-align: center;
    }

    .pagination {
        display: flex;
        list-style: none;
        border-radius: 50px;
        background: white;
        padding: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e1e5eb;
        gap: 5px;
    }

    .pagination li {
        margin: 0;
    }

    .pagination a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        text-decoration: none;
        color: #4a6ee0;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: white;
    }

    .pagination a:hover {
        background-color: #4a6ee0;
        color: white;
        border-color: #4a6ee0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 110, 224, 0.3);
    }

    .pagination a.active {
        background-color: #4a6ee0;
        color: white;
        border-color: #4a6ee0;
        box-shadow: 0 4px 12px rgba(74, 110, 224, 0.4);
    }

    .pagination a.disabled {
        color: #b0b7c3;
        background: #f8f9fa;
        pointer-events: none;
        border-color: #e1e5eb;
    }

    .pagination .arrow {
        font-size: 16px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .arrow:hover {
        background-color: #4a6ee0;
        color: white;
    }

    .pagination .page-info {
        display: flex;
        align-items: center;
        padding: 0 15px;
        color: #666;
        font-size: 14px;
        font-weight: 500;
    }

    .items-per-page {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: #555;
    }

    .items-per-page select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .items-per-page select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    /* Estilos para el dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .dropdown-toggle:hover {
        background: #2980b9;
    }

    .dropdown-toggle::after {
        content: '▼';
        font-size: 0.8em;
        margin-left: 5px;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 5px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        min-width: 200px;
        z-index: 1000;
        display: none;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        color: #3498db;
    }

    .dropdown-item i {
        width: 16px;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .grid-header,
        .grid-row {
            grid-template-columns: 1fr 1.5fr 0.8fr 1fr 0.8fr 1fr 1.2fr;
            gap: 0.5rem;
            padding: 0.75rem;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1rem;
        }

        .grid-header {
            display: none;
        }

        .grid-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            padding: 1rem;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: left;
        }

        .grid-row>div {
            justify-content: flex-start;
            text-align: left;
            min-height: auto;
            padding: 0.25rem 0;
        }

        /* CORRECCIÓN: Excluir la columna de acciones del pseudo-elemento */
        .grid-row>div::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 0.5rem;
            color: #333;
        }

        .grid-row>div.actions::before {
            content: none !important;
        }

        .actions {
            justify-content: center;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid #eee;
        }

        .filters-container {
            flex-direction: column;
        }

        .filter-actions {
            justify-content: stretch;
        }

        .btn-filter {
            flex: 1;
        }

        .modal {
            width: 95%;
            margin: 1rem;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .pagination {
            padding: 6px;
            border-radius: 25px;
        }
        
        .pagination a {
            width: 38px;
            height: 38px;
            font-size: 13px;
        }
        
        .pagination-info {
            font-size: 13px;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
            border-radius: 12px;
            padding: 10px;
        }
        
        .pagination a {
            width: 36px;
            height: 36px;
            margin: 2px;
        }
    }
</style>

<div class="main-content">
    <div class="section-header">
        <h1>Gestión de Documentos</h1>
        <div class="header-actions">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" id="agregarDropdown">
                    <i class="fas fa-plus"></i> Agregar
                </button>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a class="dropdown-item" href="#" onclick="abrirModalDocumento()">
                        <i class="fas fa-file"></i> Nuevo Documento
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalCarpeta()">
                        <i class="fas fa-folder"></i> Nueva Carpeta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Buscar documentos...">
        </div>

        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label" for="tipoFilter">Tipo</label>
                <select class="filter-select" id="tipoFilter">
                    <option value="">Todos los tipos</option>
                    <option value="PDF">PDF</option>
                    <option value="DOC">Word</option>
                    <option value="XLS">Excel</option>
                    <option value="PPT">PowerPoint</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="areaFilter">Área</label>
                <select class="filter-select" id="areaFilter">
                    <option value="">Todas las áreas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo htmlspecialchars($area->nombre); ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="tipoAccesoFilter">Tipo de Acceso</label>
                <select class="filter-select" id="tipoAccesoFilter">
                    <option value="">Todos los documentos</option>
                    <option value="propios">Mis documentos</option>
                    <option value="compartidos">Compartidos conmigo</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="ordenFilter">Ordenar por</label>
                <select class="filter-select" id="ordenFilter">
                    <option value="reciente">Más reciente</option>
                    <option value="antiguo">Más antiguo</option>
                    <option value="az">Nombre A-Z</option>
                    <option value="za">Nombre Z-A</option>
                </select>
            </div>

            <div class="items-per-page">
                <span>Mostrar:</span>
                <select id="itemsPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="filter-actions">
                <button class="btn-filter btn-reset" id="resetFilters">
                    <i class="fas fa-redo"></i> Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Primer Paginador -->
    <div class="pagination-container" id="paginationTopContainer">
        <div class="pagination-info" id="pageInfoTop"></div>
        <ul class="pagination" id="paginationTop"></ul>
    </div>

    <!-- Contenedor de documentos -->
    <div class="grid-container">
        <div class="grid-header">
            <div>Fecha</div>
            <div>Nombre</div>
            <div>Tipo</div>
            <div>Área</div>
            <div>Versión</div>
            <div>Tamaño</div>
            <div>Acciones</div>
        </div>

        <div id="documentosContainer">
            <?php if (empty($documentos)): ?>
                <div class="no-results">
                    <p>No se encontraron documentos</p>
                </div>
            <?php else: ?>
                <?php foreach ($documentos as $documento): ?>
                    <div class="grid-row" data-documento-id="<?php echo $documento->id; ?>">
                        <div data-label="Fecha"><?php echo date('d/m/Y', strtotime($documento->ultima_actualizacion)); ?></div>
                        <div data-label="Nombre">
                            <?php echo htmlspecialchars($documento->nombre_doc); ?>
                            <?php if (isset($documento->tipo_documento) && $documento->tipo_documento === 'compartido'): ?>
                                <br><small style="color: #666; font-size: 0.8rem;">
                                    👤 Compartido por: <?php echo htmlspecialchars($documento->creador_nombre . ' ' . $documento->creador_apellidos); ?>
                                </small>
                            <?php endif; ?>
                            <?php if (isset($documento->tipo_documento)): ?>
                                <br><small class="tipo-acceso" style="color: <?php echo $documento->tipo_documento === 'propio' ? '#27ae60' : '#3498db'; ?>; font-size: 0.8rem;">
                                    <?php echo $documento->tipo_documento === 'propio' ? '🟢 Mi documento' : '🔵 Compartido'; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div data-label="Tipo">
                            <span class="document-type type-<?php echo strtolower($documento->tipo_doc ?? 'pdf'); ?>">
                                <?php echo $documento->tipo_doc ?? 'PDF'; ?>
                            </span>
                        </div>
                        <div data-label="Área"><?php echo htmlspecialchars($documento->area_nombre ?? 'N/A'); ?></div>
                        <div data-label="Versión"><?php echo htmlspecialchars($documento->version ?? 'v1.0'); ?></div>
                        <div data-label="Tamaño"><?php echo $documento->tamanio ? round($documento->tamanio / 1024 / 1024, 2) . ' MB' : 'N/A'; ?></div>
                        <div class="actions" data-label="Acciones">
                            <button class="btn-action view" title="Ver" onclick="verDocumento(<?php echo $documento->id; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <?php 
                            $puedeEditar = ($documento->fk_usuario_id == $_SESSION['user']['id']) || 
                                          ($documento->permisos_usuario === 'edicion');
                            ?>
                            
                            <?php if ($puedeEditar): ?>
                                <button class="btn-action edit" title="Editar" onclick="editarDocumento(<?php echo $documento->id; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn-action edit" title="Sin permisos de edición" disabled>
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn-action download" title="Descargar" onclick="descargarDocumento(<?php echo $documento->id; ?>)">
                                <i class="fas fa-download"></i>
                            </button>
                            
                            <?php if ($documento->fk_usuario_id == $_SESSION['user']['id']): ?>
                                <button class="btn-action share" title="Gestionar Permisos" onclick="gestionarPermisos(<?php echo $documento->id; ?>)">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($documento->fk_usuario_id == $_SESSION['user']['id']): ?>
                                <button class="btn-action delete" title="Eliminar" onclick="eliminarDocumento(<?php echo $documento->id; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn-action delete" title="Solo el propietario puede eliminar" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Segundo Paginador -->
    <div class="pagination-container" id="paginationBottomContainer">
        <div class="pagination-info" id="pageInfoBottom"></div>
        <ul class="pagination" id="paginationBottom"></ul>
    </div>
</div>

<!-- Modal Agregar Documento -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalTitle">Subir Nuevo Documento</h2>
            <button class="close-modal" id="closeModal">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label" for="nombreDocumento">Nombre del Documento *</label>
                <input type="text" class="form-input" id="nombreDocumento" placeholder="Ingrese el nombre del documento">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="tipoDocumento">Tipo de Documento *</label>
                    <select class="form-select" id="tipoDocumento">
                        <option value="">Seleccione el tipo</option>
                        <option value="PDF">PDF</option>
                        <option value="DOC">Documento Word</option>
                        <option value="XLS">Hoja de Cálculo</option>
                        <option value="PPT">Presentación</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="versionDocumento">Versión</label>
                    <input type="text" class="form-input" id="versionDocumento" placeholder="Ej: v1.0" value="v1.0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="areaDocumento">Área *</label>
                    <select class="form-select" id="areaDocumento">
                        <option value="">Seleccione el área</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area->id; ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="categoriaDocumento">Categoría</label>
                    <select class="form-select" id="categoriaDocumento">
                        <option value="">Seleccione la categoría</option>
                        <option value="Manual">Manual</option>
                        <option value="Procedimiento">Procedimiento</option>
                        <option value="Política">Política</option>
                        <option value="Reporte">Reporte</option>
                        <option value="Formato">Formato</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="descripcionDocumento">Descripción</label>
                <textarea class="form-textarea" id="descripcionDocumento" placeholder="Describa el contenido y propósito del documento"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Compartir con usuarios:</label>
                <div class="usuarios-compartir-container">
                    <div class="checkbox-group" style="margin-bottom: 0.5rem;">
                        <input type="checkbox" id="selectAllUsersDoc">
                        <label for="selectAllUsersDoc" style="font-weight: 600;">Seleccionar todos</label>
                    </div>
                    <div class="usuarios-grid" id="usuariosGridDoc">
                        <?php foreach ($usuarios as $usuario): ?>
                            <div class="checkbox-group">
                                <input type="checkbox" name="usuarios_compartir[]" value="<?php echo $usuario->id; ?>" id="user_doc_<?php echo $usuario->id; ?>">
                                <label for="user_doc_<?php echo $usuario->id; ?>">
                                    <?php echo htmlspecialchars($usuario->nombre . ' ' . $usuario->apellidos); ?>
                                    <small style="color: #666;">(<?php echo htmlspecialchars($usuario->email); ?>)</small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="tipoPermisosDoc">Tipo de Permisos:</label>
                <select class="form-select" id="tipoPermisosDoc">
                    <option value="lectura">Solo Lectura</option>
                    <option value="edicion">Edición</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subir Documento *</label>
                <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Haga clic para subir el documento o arrastre y suelte</p>
                    <p class="small-text">Formatos aceptados: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Máx. 25MB)</p>
                    <input type="file" class="file-input" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                </div>
                <div id="fileName" class="file-name"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelModal">Cancelar</button>
            <button class="btn-success" id="btnGuardarDocumento">Guardar Documento</button>
        </div>
    </div>
</div>

<!-- Modal Gestionar Permisos -->
<div class="modal-overlay" id="permisosModalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2>Gestionar Permisos del Documento</h2>
            <button class="close-modal" id="closePermisosModal">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Seleccionar usuarios para compartir:</label>
                <div class="usuarios-compartir-container">
                    <div class="checkbox-group" style="margin-bottom: 0.5rem;">
                        <input type="checkbox" id="selectAllUsersPermisos">
                        <label for="selectAllUsersPermisos" style="font-weight: 600;">Seleccionar todos</label>
                    </div>
                    <div class="usuarios-grid" id="usuariosGridPermisos">
                        <?php foreach ($usuarios as $usuario): ?>
                            <div class="checkbox-group">
                                <input type="checkbox" name="usuarios_permisos[]" value="<?php echo $usuario->id; ?>" id="user_perm_<?php echo $usuario->id; ?>">
                                <label for="user_perm_<?php echo $usuario->id; ?>">
                                    <?php echo htmlspecialchars($usuario->nombre . ' ' . $usuario->apellidos); ?>
                                    <small style="color: #666;">(<?php echo htmlspecialchars($usuario->email); ?>)</small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="tipoPermisos">Tipo de Permisos:</label>
                <select class="form-select" id="tipoPermisos">
                    <option value="lectura">Solo Lectura</option>
                    <option value="edicion">Edición</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelPermisosModal">Cancelar</button>
            <button class="btn-success" id="btnGuardarPermisos">Guardar Permisos</button>
        </div>
    </div>
</div>

<!-- Modal Nueva Carpeta -->
<div class="modal-overlay" id="carpetaModalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2>Crear Nueva Carpeta</h2>
            <button class="close-modal" id="closeCarpetaModal">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label" for="nombreCarpeta">Nombre de la Carpeta *</label>
                <input type="text" class="form-input" id="nombreCarpeta" placeholder="Ingrese el nombre de la carpeta">
            </div>
            <div class="form-group">
                <label class="form-label" for="areaCarpeta">Área (Opcional)</label>
                <select class="form-select" id="areaCarpeta">
                    <option value="">Sin área específica</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area->id; ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelCarpetaModal">Cancelar</button>
            <button class="btn-success" id="btnGuardarCarpeta">Crear Carpeta</button>
        </div>
    </div>
</div>

<!-- Modal Editar Documento -->
<div class="modal-overlay" id="editarModalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2>Editar Documento</h2>
            <button class="close-modal" id="closeEditarModal">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editarDocumentoId">
            <div class="form-group">
                <label class="form-label" for="editarNombreDocumento">Nombre del Documento *</label>
                <input type="text" class="form-input" id="editarNombreDocumento" placeholder="Ingrese el nombre del documento">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="editarTipoDocumento">Tipo de Documento *</label>
                    <select class="form-select" id="editarTipoDocumento">
                        <option value="">Seleccione el tipo</option>
                        <option value="PDF">PDF</option>
                        <option value="DOC">Documento Word</option>
                        <option value="XLS">Hoja de Cálculo</option>
                        <option value="PPT">Presentación</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="editarVersionDocumento">Versión</label>
                    <input type="text" class="form-input" id="editarVersionDocumento" placeholder="Ej: v1.0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="editarAreaDocumento">Área *</label>
                    <select class="form-select" id="editarAreaDocumento">
                        <option value="">Seleccione el área</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area->id; ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="editarCategoriaDocumento">Categoría</label>
                    <select class="form-select" id="editarCategoriaDocumento">
                        <option value="">Seleccione la categoría</option>
                        <option value="Manual">Manual</option>
                        <option value="Procedimiento">Procedimiento</option>
                        <option value="Política">Política</option>
                        <option value="Reporte">Reporte</option>
                        <option value="Formato">Formato</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="editarDescripcionDocumento">Descripción</label>
                <textarea class="form-textarea" id="editarDescripcionDocumento" placeholder="Describa el contenido y propósito del documento"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Actualizar Documento (Opcional)</label>
                <div class="file-upload" onclick="document.getElementById('editarFileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Haga clic para actualizar el documento</p>
                    <p class="small-text">Dejar en blanco para mantener el archivo actual</p>
                    <input type="file" class="file-input" id="editarFileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                </div>
                <div id="editarFileName" class="file-name"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelEditarModal">Cancelar</button>
            <button class="btn-success" id="btnActualizarDocumento">Actualizar Documento</button>
        </div>
    </div>
</div>

<script>
console.log('🚀 Gestor de documentos cargado');

// Variables globales
let carpetaActual = null;
let elementoAEliminar = null;
let tipoElementoAEliminar = null;
let currentPage = 1;
let itemsPerPage = 10;
let allDocumentos = [];
let searchTimer = null;
let documentoActualPermisos = null;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado - Inicializando gestor de documentos');
    
    inicializarDropdown();
    inicializarModales();
    inicializarEventos();
    inicializarPaginacion();
});

function inicializarDropdown() {
    const dropdownToggle = document.getElementById('agregarDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
        
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
}

function inicializarModales() {
    // Modal documento
    const btnCerrarDoc = document.getElementById('closeModal');
    const btnCancelarDoc = document.getElementById('cancelModal');
    if (btnCerrarDoc) btnCerrarDoc.addEventListener('click', cerrarModalDocumento);
    if (btnCancelarDoc) btnCancelarDoc.addEventListener('click', cerrarModalDocumento);

    // Modal carpeta
    const btnCerrarCarpeta = document.getElementById('closeCarpetaModal');
    const btnCancelarCarpeta = document.getElementById('cancelCarpetaModal');
    if (btnCerrarCarpeta) btnCerrarCarpeta.addEventListener('click', cerrarModalCarpeta);
    if (btnCancelarCarpeta) btnCancelarCarpeta.addEventListener('click', cerrarModalCarpeta);

    // Modal permisos
    const btnCerrarPermisos = document.getElementById('closePermisosModal');
    const btnCancelarPermisos = document.getElementById('cancelPermisosModal');
    if (btnCerrarPermisos) btnCerrarPermisos.addEventListener('click', cerrarModalPermisos);
    if (btnCancelarPermisos) btnCancelarPermisos.addEventListener('click', cerrarModalPermisos);

    // Modal editar
    const btnCerrarEditar = document.getElementById('closeEditarModal');
    const btnCancelarEditar = document.getElementById('cancelEditarModal');
    if (btnCerrarEditar) btnCerrarEditar.addEventListener('click', cerrarModalEditar);
    if (btnCancelarEditar) btnCancelarEditar.addEventListener('click', cerrarModalEditar);

    // Botones guardar
    const btnGuardarDocumento = document.getElementById('btnGuardarDocumento');
    const btnGuardarCarpeta = document.getElementById('btnGuardarCarpeta');
    const btnGuardarPermisos = document.getElementById('btnGuardarPermisos');
    const btnActualizarDocumento = document.getElementById('btnActualizarDocumento');
    
    if (btnGuardarDocumento) btnGuardarDocumento.addEventListener('click', guardarDocumento);
    if (btnGuardarCarpeta) btnGuardarCarpeta.addEventListener('click', guardarCarpeta);
    if (btnGuardarPermisos) btnGuardarPermisos.addEventListener('click', guardarPermisos);
    if (btnActualizarDocumento) btnActualizarDocumento.addEventListener('click', actualizarDocumento);

    // File inputs
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = document.getElementById('fileName');
            if (this.files[0]) {
                const file = this.files[0];
                const mb = (file.size / 1024 / 1024).toFixed(2);
                fileName.textContent = `📎 ${file.name} (${mb} MB)`;
                fileName.style.color = '#27ae60';
            }
        });
    }

    const editarFileInput = document.getElementById('editarFileInput');
    if (editarFileInput) {
        editarFileInput.addEventListener('change', function() {
            const fileName = document.getElementById('editarFileName');
            if (this.files[0]) {
                const file = this.files[0];
                const mb = (file.size / 1024 / 1024).toFixed(2);
                fileName.textContent = `📎 ${file.name} (${mb} MB)`;
                fileName.style.color = '#27ae60';
            }
        });
    }

    // Click fuera de modales
    const overlays = ['modalOverlay', 'carpetaModalOverlay', 'permisosModalOverlay', 'editarModalOverlay'];
    overlays.forEach(overlayId => {
        const overlay = document.getElementById(overlayId);
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    switch(overlayId) {
                        case 'modalOverlay': cerrarModalDocumento(); break;
                        case 'carpetaModalOverlay': cerrarModalCarpeta(); break;
                        case 'permisosModalOverlay': cerrarModalPermisos(); break;
                        case 'editarModalOverlay': cerrarModalEditar(); break;
                    }
                }
            });
        }
    });

    // ESC para cerrar modales
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalDocumento();
            cerrarModalCarpeta();
            cerrarModalPermisos();
            cerrarModalEditar();
        }
    });
}

function inicializarEventos() {
    // Búsqueda en tiempo real con debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                filtrarDocumentos();
            }, 300);
        });
    }
    
    // Filtros en tiempo real
    document.getElementById('tipoFilter').addEventListener('change', filtrarDocumentos);
    document.getElementById('areaFilter').addEventListener('change', filtrarDocumentos);
    document.getElementById('tipoAccesoFilter').addEventListener('change', filtrarDocumentos);
    document.getElementById('ordenFilter').addEventListener('change', filtrarDocumentos);
    
    // Items por página
    document.getElementById('itemsPerPage').addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        aplicarPaginacion();
        actualizarPaginadores();
    });
    
    // Botón limpiar
    const btnLimpiar = document.getElementById('resetFilters');
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
}

// ==========================================
// SISTEMA DE PAGINACIÓN
// ==========================================

function inicializarPaginacion() {
    console.log('🔄 Inicializando sistema de paginación...');
    
    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => {
        return !row.classList.contains('no-results') && row.style.display !== 'none';
    });
    
    allDocumentos = ordenarDocumentos(filas, 'reciente');
    
    console.log(`📊 Total de documentos encontrados: ${allDocumentos.length}`);
    
    aplicarPaginacion();
    actualizarPaginadores();
}

function aplicarPaginacion() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const totalDocumentos = allDocumentos.length;
    
    console.log(`📄 Mostrando página ${currentPage}, items ${startIndex + 1}-${Math.min(endIndex, totalDocumentos)} de ${totalDocumentos}`);
    
    allDocumentos.forEach((documento, index) => {
        documento.style.display = (index >= startIndex && index < endIndex) ? 'grid' : 'none';
    });
    
    actualizarInfoPagina();
}

function actualizarInfoPagina() {
    const totalDocumentos = allDocumentos.length;
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, totalDocumentos);
    
    const infoText = totalDocumentos > 0 
        ? `Mostrando ${startIndex}-${endIndex} de ${totalDocumentos} documentos` 
        : 'No se encontraron documentos';
    
    document.getElementById('pageInfoTop').textContent = infoText;
    document.getElementById('pageInfoBottom').textContent = infoText;
}

function actualizarPaginadores() {
    const totalDocumentos = allDocumentos.length;
    const totalPages = Math.ceil(totalDocumentos / itemsPerPage);
    
    console.log(`🔢 Actualizando paginadores: ${totalPages} páginas totales`);
    
    actualizarPaginador('paginationTop', totalPages);
    actualizarPaginador('paginationBottom', totalPages);
}

function actualizarPaginador(paginadorId, totalPages) {
    const paginador = document.getElementById(paginadorId);
    if (!paginador) return;
    
    paginador.innerHTML = '';
    
    // Botón Anterior
    const prevLi = crearBotonPaginacion('Anterior', currentPage > 1, () => cambiarPagina(currentPage - 1));
    paginador.appendChild(prevLi);
    
    // Páginas
    const paginas = generarNumerosPaginas(currentPage, totalPages);
    paginas.forEach(pagina => {
        if (pagina === '...') {
            const li = document.createElement('li');
            li.innerHTML = '<span class="pagination-ellipsis">...</span>';
            paginador.appendChild(li);
        } else {
            const li = crearBotonPaginacion(pagina, true, () => cambiarPagina(pagina), pagina === currentPage);
            paginador.appendChild(li);
        }
    });
    
    // Botón Siguiente
    const nextLi = crearBotonPaginacion('Siguiente', currentPage < totalPages, () => cambiarPagina(currentPage + 1));
    paginador.appendChild(nextLi);
}

function crearBotonPaginacion(texto, habilitado, onClick, activo = false) {
    const li = document.createElement('li');
    const a = document.createElement('a');
    a.href = '#';
    
    if (texto === 'Anterior' || texto === 'Siguiente') {
        a.classList.add('arrow');
        a.innerHTML = texto === 'Anterior' ? '&laquo;' : '&raquo;';
        a.title = texto;
    } else {
        a.textContent = texto;
    }
    
    if (activo) {
        a.classList.add('active');
    }
    
    if (!habilitado) {
        a.classList.add('disabled');
    } else {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            onClick();
        });
    }
    
    li.appendChild(a);
    return li;
}

function generarNumerosPaginas(paginaActual, totalPaginas) {
    const paginas = [];
    const paginasVisibles = 5;
    
    if (totalPaginas <= paginasVisibles) {
        for (let i = 1; i <= totalPaginas; i++) {
            paginas.push(i);
        }
    } else {
        if (paginaActual <= 3) {
            for (let i = 1; i <= 4; i++) {
                paginas.push(i);
            }
            paginas.push('...');
            paginas.push(totalPaginas);
        } else if (paginaActual >= totalPaginas - 2) {
            paginas.push(1);
            paginas.push('...');
            for (let i = totalPaginas - 3; i <= totalPaginas; i++) {
                paginas.push(i);
            }
        } else {
            paginas.push(1);
            paginas.push('...');
            for (let i = paginaActual - 1; i <= paginaActual + 1; i++) {
                paginas.push(i);
            }
            paginas.push('...');
            paginas.push(totalPaginas);
        }
    }
    
    return paginas;
}

function cambiarPagina(nuevaPagina) {
    if (nuevaPagina === currentPage) return;
    
    currentPage = nuevaPagina;
    console.log(`🔄 Cambiando a página ${currentPage}`);
    
    aplicarPaginacion();
    actualizarPaginadores();
    
    const tablaContainer = document.querySelector('.grid-container');
    if (tablaContainer) {
        tablaContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

// ==========================================
// SISTEMA DE FILTRADO
// ==========================================

function filtrarDocumentos() {
    const busqueda = document.getElementById('searchInput').value.toLowerCase();
    const tipo = document.getElementById('tipoFilter').value;
    const area = document.getElementById('areaFilter').value;
    const tipoAcceso = document.getElementById('tipoAccesoFilter').value;
    const orden = document.getElementById('ordenFilter').value;

    console.log('🔍 Aplicando filtros:', { busqueda, tipo, area, tipoAcceso, orden });

    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );

    let filasFiltradas = filas.filter(fila => {
        const nombre = fila.children[1]?.textContent.toLowerCase() || '';
        const tipoDoc = fila.children[2]?.textContent.trim() || '';
        const areaDoc = fila.children[3]?.textContent.trim() || '';
        const tipoAccesoDoc = fila.querySelector('.tipo-acceso')?.textContent.trim() || '';

        const coincideBusqueda = !busqueda || nombre.includes(busqueda);
        const coincideTipo = !tipo || tipoDoc === tipo;
        const coincideArea = !area || areaDoc === area;
        const coincideAcceso = !tipoAcceso || 
            (tipoAcceso === 'propios' && tipoAccesoDoc.includes('Mi documento')) ||
            (tipoAcceso === 'compartidos' && tipoAccesoDoc.includes('Compartido'));

        return coincideBusqueda && coincideTipo && coincideArea && coincideAcceso;
    });

    filasFiltradas = ordenarDocumentos(filasFiltradas, orden);

    const container = document.getElementById('documentosContainer');
    filas.forEach(fila => fila.remove());
    filasFiltradas.forEach(fila => container.appendChild(fila));

    allDocumentos = filasFiltradas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
}

function ordenarDocumentos(filas, orden) {
    return filas.sort((a, b) => {
        const nombreA = a.children[1]?.querySelector('small') ? 
                       a.children[1].textContent.split('\n')[0].toLowerCase().trim() : 
                       a.children[1]?.textContent.toLowerCase().trim() || '';
        
        const nombreB = b.children[1]?.querySelector('small') ? 
                       b.children[1].textContent.split('\n')[0].toLowerCase().trim() : 
                       b.children[1]?.textContent.toLowerCase().trim() || '';

        const fechaA = new Date(a.children[0]?.textContent.split('/').reverse().join('-') || 0);
        const fechaB = new Date(b.children[0]?.textContent.split('/').reverse().join('-') || 0);

        switch (orden) {
            case 'reciente':
                return fechaB - fechaA;
            case 'antiguo':
                return fechaA - fechaB;
            case 'az':
                return nombreA.localeCompare(nombreB);
            case 'za':
                return nombreB.localeCompare(nombreA);
            default:
                return 0;
        }
    });
}

function limpiarFiltros() {
    console.log('🧹 Limpiando filtros...');
    
    document.getElementById('searchInput').value = '';
    document.getElementById('tipoFilter').value = '';
    document.getElementById('areaFilter').value = '';
    document.getElementById('tipoAccesoFilter').value = '';
    document.getElementById('ordenFilter').value = 'reciente';
    
    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );
    
    const filasOrdenadas = ordenarDocumentos(filas, 'reciente');
    const container = document.getElementById('documentosContainer');
    filasOrdenadas.forEach(fila => {
        container.appendChild(fila);
    });
    
    allDocumentos = filasOrdenadas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
}

// ==========================================
// FUNCIONES DE MODALES
// ==========================================

function abrirModalDocumento() {
    console.log('📄 Abriendo modal documento');
    const dropdownMenu = document.getElementById('dropdownMenu');
    if (dropdownMenu) dropdownMenu.classList.remove('show');
    
    const overlay = document.getElementById('modalOverlay');
    if (!overlay) {
        console.error('❌ No se encontró modalOverlay');
        return;
    }
    
    document.getElementById('nombreDocumento').value = '';
    document.getElementById('tipoDocumento').value = '';
    document.getElementById('versionDocumento').value = 'v1.0';
    document.getElementById('areaDocumento').value = '';
    document.getElementById('descripcionDocumento').value = '';
    document.getElementById('fileInput').value = '';
    document.getElementById('fileName').textContent = '';
    
    overlay.style.display = 'flex';
    setTimeout(() => {
        overlay.classList.add('active');
        console.log('✅ Modal documento abierto');
    }, 10);
}

function cerrarModalDocumento() {
    console.log('📄 Cerrando modal documento');
    const overlay = document.getElementById('modalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            console.log('✅ Modal documento cerrado');
        }, 300);
    }
}

function abrirModalCarpeta() {
    console.log('📁 Abriendo modal carpeta');
    
    const dropdownMenu = document.getElementById('dropdownMenu');
    if (dropdownMenu) {
        dropdownMenu.classList.remove('show');
    }
    
    const overlay = document.getElementById('carpetaModalOverlay');
    if (!overlay) {
        console.error('❌ No se encontró carpetaModalOverlay');
        return;
    }
    
    const nombreInput = document.getElementById('nombreCarpeta');
    const areaInput = document.getElementById('areaCarpeta');
    
    if (nombreInput) nombreInput.value = '';
    if (areaInput) areaInput.value = '';
    
    overlay.style.display = 'flex';
    setTimeout(() => {
        overlay.classList.add('active');
        console.log('✅ Modal carpeta abierto correctamente');
    }, 10);
}

function cerrarModalCarpeta() {
    console.log('📁 Cerrando modal carpeta');
    const overlay = document.getElementById('carpetaModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            console.log('✅ Modal carpeta cerrado');
        }, 300);
    }
}

function abrirModalPermisos() {
    console.log('👥 Abriendo modal permisos');
    const overlay = document.getElementById('permisosModalOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.add('active');
            console.log('✅ Modal permisos abierto');
        }, 10);
    }
}

function cerrarModalPermisos() {
    console.log('👥 Cerrando modal permisos');
    const overlay = document.getElementById('permisosModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            console.log('✅ Modal permisos cerrado');
        }, 300);
    }
}

function abrirModalEditar() {
    console.log('✏️ Abriendo modal editar');
    const overlay = document.getElementById('editarModalOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.classList.add('active');
            console.log('✅ Modal editar abierto');
        }, 10);
    }
}

function cerrarModalEditar() {
    console.log('✏️ Cerrando modal editar');
    const overlay = document.getElementById('editarModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            console.log('✅ Modal editar cerrado');
        }, 300);
    }
}

// ==========================================
// FUNCIONES CRUD - EDITAR Y COMPARTIR
// ==========================================

function editarDocumento(id) {
    console.log('✏️ Editando documento:', id);
    
    // Mostrar loading
    const btnEditar = event.target.closest('.btn-action');
    const originalHTML = btnEditar.innerHTML;
    btnEditar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`/dashboard/obtener-documento/${id}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener documento');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const doc = data.documento;
            
            // Llenar el formulario de edición
            document.getElementById('editarDocumentoId').value = doc.id;
            document.getElementById('editarNombreDocumento').value = doc.nombre_doc || '';
            document.getElementById('editarTipoDocumento').value = doc.tipo_doc || '';
            document.getElementById('editarVersionDocumento').value = doc.version || 'v1.0';
            document.getElementById('editarAreaDocumento').value = doc.area_id || '';
            document.getElementById('editarCategoriaDocumento').value = doc.categoria || '';
            document.getElementById('editarDescripcionDocumento').value = doc.descripcion || '';
            document.getElementById('editarFileName').textContent = doc.archivo_nombre ? `📎 ${doc.archivo_nombre}` : '';
            
            abrirModalEditar();
        } else {
            mostrarError(data.message || 'Error al cargar documento');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnEditar.innerHTML = originalHTML;
    });
}

function actualizarDocumento() {
    const id = document.getElementById('editarDocumentoId').value;
    const nombre = document.getElementById('editarNombreDocumento').value.trim();
    const tipo = document.getElementById('editarTipoDocumento').value;
    const area = document.getElementById('editarAreaDocumento').value;
    
    if (!nombre) {
        mostrarError('El nombre del documento es obligatorio');
        return;
    }
    
    if (!tipo) {
        mostrarError('El tipo de documento es obligatorio');
        return;
    }
    
    if (!area) {
        mostrarError('El área es obligatoria');
        return;
    }

    const formData = new FormData();
    formData.append('nombre_doc', nombre);
    formData.append('tipo_doc', tipo);
    formData.append('version', document.getElementById('editarVersionDocumento').value);
    formData.append('area_id', area);
    formData.append('categoria', document.getElementById('editarCategoriaDocumento').value);
    formData.append('descripcion', document.getElementById('editarDescripcionDocumento').value);
    
    const archivoInput = document.getElementById('editarFileInput');
    if (archivoInput.files[0]) {
        formData.append('archivo', archivoInput.files[0]);
    }

    const btnActualizar = document.getElementById('btnActualizarDocumento');
    const originalText = btnActualizar.innerHTML;
    btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    btnActualizar.disabled = true;

    fetch(`/dashboard/actualizar-documento/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('✅ Documento actualizado correctamente');
            cerrarModalEditar();
            location.reload();
        } else {
            mostrarError(data.message || 'Error al actualizar documento');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnActualizar.innerHTML = originalText;
        btnActualizar.disabled = false;
    });
}

function gestionarPermisos(id) {
    console.log('👥 Gestionando permisos para documento:', id);
    documentoActualPermisos = id;
    
    // Mostrar loading
    const btnCompartir = event.target.closest('.btn-action');
    const originalHTML = btnCompartir.innerHTML;
    btnCompartir.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`/dashboard/obtener-permisos-documento/${id}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener permisos');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Limpiar checkboxes
            const checkboxes = document.querySelectorAll('#usuariosGridPermisos input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Marcar usuarios que ya tienen permisos
            if (data.permisos && data.permisos.length > 0) {
                data.permisos.forEach(permiso => {
                    const checkbox = document.querySelector(`#user_perm_${permiso.usuario_id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                
                // Establecer tipo de permisos
                if (data.permisos[0].tipo_permiso) {
                    document.getElementById('tipoPermisos').value = data.permisos[0].tipo_permiso;
                }
            }
            
            abrirModalPermisos();
        } else {
            mostrarError(data.message || 'Error al cargar permisos');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnCompartir.innerHTML = originalHTML;
    });
}

function guardarPermisos() {
    if (!documentoActualPermisos) {
        mostrarError('No se ha seleccionado ningún documento');
        return;
    }

    const usuariosSeleccionados = [];
    const checkboxes = document.querySelectorAll('#usuariosGridPermisos input[type="checkbox"]:checked');
    checkboxes.forEach(checkbox => {
        usuariosSeleccionados.push(checkbox.value);
    });

    const tipoPermisos = document.getElementById('tipoPermisos').value;

    const datos = {
        documento_id: documentoActualPermisos,
        usuarios: usuariosSeleccionados,
        tipo_permiso: tipoPermisos
    };

    const btnGuardar = document.getElementById('btnGuardarPermisos');
    const originalText = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    fetch('/dashboard/guardar-permisos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('✅ Permisos guardados correctamente');
            cerrarModalPermisos();
        } else {
            mostrarError(data.message || 'Error al guardar permisos');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnGuardar.innerHTML = originalText;
        btnGuardar.disabled = false;
    });
}

// ==========================================
// FUNCIONES EXISTENTES
// ==========================================

function guardarCarpeta() {
    console.log('💾 Guardando carpeta...');
    const nombre = document.getElementById('nombreCarpeta').value.trim();
    
    if (!nombre) {
        mostrarError('El nombre de la carpeta es obligatorio');
        return;
    }
    
    const btnGuardar = document.getElementById('btnGuardarCarpeta');
    const originalText = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
    btnGuardar.disabled = true;

    const datos = {
        nombre: nombre,
        area_id: document.getElementById('areaCarpeta').value || null,
        carpeta_padre_id: carpetaActual
    };

    console.log('📤 Enviando datos de carpeta:', datos);
    
    fetch('/dashboard/crear-carpeta', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => {
        console.log('Respuesta HTTP:', response.status, response.statusText);
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('📥 Respuesta:', data);
        if (data.success) {
            mostrarExito('✅ Carpeta "' + nombre + '" creada correctamente');
            cerrarModalCarpeta();
            location.reload();
        } else {
            mostrarError(data.message || 'Error al crear la carpeta');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error: ' + error.message);
    })
    .finally(() => {
        btnGuardar.innerHTML = originalText;
        btnGuardar.disabled = false;
    });
}

function guardarDocumento() {
    console.log('💾 Guardando documento...');
    const formData = new FormData();
    const fileInput = document.getElementById('fileInput');
    
    const nombre = document.getElementById('nombreDocumento').value.trim();
    const tipo = document.getElementById('tipoDocumento').value;
    const area = document.getElementById('areaDocumento').value;
    
    if (!nombre) {
        mostrarError('El nombre del documento es obligatorio');
        return;
    }
    
    if (!tipo) {
        mostrarError('El tipo de documento es obligatorio');
        return;
    }
    
    if (!area) {
        mostrarError('El área es obligatoria');
        return;
    }
    
    if (!fileInput.files[0]) {
        mostrarError('Debe seleccionar un archivo');
        return;
    }

    formData.append('nombre_doc', nombre);
    formData.append('tipo_doc', tipo);
    formData.append('version', document.getElementById('versionDocumento').value);
    formData.append('area_id', area);
    formData.append('descripcion', document.getElementById('descripcionDocumento').value);
    formData.append('archivo', fileInput.files[0]);
    if (carpetaActual) {
        formData.append('carpeta_id', carpetaActual);
    }

    const btnGuardar = document.getElementById('btnGuardarDocumento');
    const originalText = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    fetch('/dashboard/crear-documento', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('📥 Respuesta crear documento:', data);
        if (data.success) {
            mostrarExito('✅ Documento creado correctamente');
            cerrarModalDocumento();
            location.reload();
        } else {
            mostrarError(data.message || 'Error al crear documento');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnGuardar.innerHTML = originalText;
        btnGuardar.disabled = false;
    });
}

function verDocumento(id) {
    console.log('👁️ Ver documento:', id);
    window.open(`/dashboard/previsualizar-documento/${id}`, '_blank');
}

function descargarDocumento(id) {
    console.log('📥 Descargar documento:', id);
    window.location.href = `/dashboard/descargar-documento/${id}`;
}

function eliminarDocumento(id) {
    if (!confirm('¿Está seguro de que desea eliminar este documento? Esta acción no se puede deshacer.')) {
        return;
    }
    
    console.log('🗑️ Eliminando documento:', id);
    
    fetch(`/dashboard/eliminar-documento/${id}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('✅ Documento eliminado correctamente');
            location.reload();
        } else {
            mostrarError(data.message || 'Error al eliminar documento');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    });
}

function mostrarError(mensaje) {
    console.error('❌ Error:', mensaje);
    alert('❌ ' + mensaje);
}

function mostrarExito(mensaje) {
    console.log('✅ Éxito:', mensaje);
    alert(mensaje);
}

// Hacer funciones globales
window.abrirModalDocumento = abrirModalDocumento;
window.abrirModalCarpeta = abrirModalCarpeta;
window.verDocumento = verDocumento;
window.descargarDocumento = descargarDocumento;
window.editarDocumento = editarDocumento;
window.gestionarPermisos = gestionarPermisos;
window.eliminarDocumento = eliminarDocumento;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>