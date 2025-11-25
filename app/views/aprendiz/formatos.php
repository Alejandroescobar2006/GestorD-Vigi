<?php
// app/views/dashboard/formatos.php
$pageTitle = 'Formatos - Vigitecol';
$currentSection = 'formatos';
$customScript = '/js/formatos.js';
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
    }

    .main-content {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h1 {
        color: #333;
        font-size: 2rem;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .btn-primary:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Filtros y Búsqueda */
    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-action.share:hover {
        color: #9b59b6;
    }

    /* Para el estado indeterminado de checkboxes */
    input[type="checkbox"]:indeterminate {
        background: #3498db;
        border-color: #3498db;
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

    .file-name {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #333;
    }

    .file-name small {
        color: #666;
        font-size: 0.8rem;
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

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Estilos para el file-upload en modo edición */
    .file-upload.edicion {
        border-color: #f39c12 !important;
        background: #fef9e7 !important;
    }

    .file-upload.edicion p {
        color: #f39c12 !important;
    }

    /* Estilos para la información del archivo */
    .file-info {
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: 6px;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .file-info.actual {
        background: #e8f4fd;
        border-left: 4px solid #3498db;
        color: #2c3e50;
    }

    .file-info.nuevo {
        background: #fef9e7;
        border-left: 4px solid #f39c12;
        color: #7d6608;
    }

    .file-info.creacion {
        background: #eafaf1;
        border-left: 4px solid #27ae60;
        color: #196f3d;
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
    }

    .pagination-info {
        font-size: 14px;
        color: #666;
        font-weight: 500;
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

    /* Estilos para notificaciones */
.notificacion-alerta {
    background: #fff3cd !important;
    border: 1px solid #ffeaa7 !important;
    border-radius: 8px !important;
    padding: 15px !important;
    margin-bottom: 10px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    animation: slideIn 0.3s ease-out !important;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

</style>

<div class="main-content">
    <div class="section-header">
        <h1>Gestión de Formatos</h1>
        <?php if ($esLina): ?>
            <button class="btn-primary" id="openModal">
                <i class="fas fa-plus"></i> Nuevo Formato
            </button>
        <?php else: ?>
            <button class="btn-primary" disabled title="Solo Lina puede crear formatos">
                <i class="fas fa-plus"></i> Nuevo Formato
            </button>
        <?php endif; ?>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Buscar formatos...">
        </div>

        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label" for="areaFilter">Área</label>
                <select class="filter-select" id="areaFilter">
                    <option value="">Todas las áreas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area->id; ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="versionFilter">Versión</label>
                <select class="filter-select" id="versionFilter">
                    <option value="">Todas las versiones</option>
                    <option value="v1.0">v1.0</option>
                    <option value="v1.1">v1.1</option>
                    <option value="v2.0">v2.0</option>
                    <option value="v2.1">v2.1</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="tipoAccesoFilter">Tipo de Acceso</label>
                <select class="filter-select" id="tipoAccesoFilter">
                    <option value="">Todos los formatos</option>
                    <option value="propios">Mis formatos</option>
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

            <div class="items-per-page filter-group">
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
                    <i class="fas fa-redo"></i> Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Primer Paginador -->
    <div class="pagination-container" id="paginationTopContainer">
        <div class="pagination-info" id="pageInfoTop"></div>
        <ul class="pagination" id="paginationTop"></ul>
    </div>

    <!-- Grid de Formatos -->
    <div class="grid-container">
        <div class="grid-header">
            <div>Fecha</div>
            <div>Nombre</div>
            <div>Versión</div>
            <div>Área</div>
            <div>Tipo Archivo</div>
            <div>Tamaño</div>
            <div>Acciones</div>
        </div>

        <div id="formatosContainer">
            <?php if (empty($formatos)): ?>
                <div class="no-results">
                    <p>No se encontraron formatos</p>
                </div>
            <?php else: ?>
                <?php foreach ($formatos as $formato): ?>
                    <div class="grid-row" data-fecha="<?php echo $formato->ultima_actualizacion; ?>" data-nombre="<?php echo htmlspecialchars($formato->nombre_formato); ?>">
                        <div data-label="Fecha"><?php echo date('d/m/Y', strtotime($formato->ultima_actualizacion)); ?></div>
                        <div data-label="Nombre">
                            <?php echo htmlspecialchars($formato->nombre_formato); ?>
                            <?php if (isset($formato->tipo_formato) && $formato->tipo_formato === 'compartido'): ?>
                                <br><small style="color: #666; font-size: 0.8rem;">
                                    👤 Compartido por: <?php echo htmlspecialchars($formato->creador_nombre . ' ' . $formato->creador_apellidos); ?>
                                </small>
                            <?php endif; ?>
                            <?php if (isset($formato->tipo_formato)): ?>
                                <br><small class="tipo-acceso" style="color: <?php echo $formato->tipo_formato === 'propio' ? '#27ae60' : '#3498db'; ?>; font-size: 0.8rem;">
                                    <?php echo $formato->tipo_formato === 'propio' ? '🟢 Mi formato' : '🔵 Compartido'; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div data-label="Versión"><?php echo htmlspecialchars($formato->version); ?></div>
                        <div data-label="Área"><?php echo htmlspecialchars($formato->area_nombre ?? 'N/A'); ?></div>
                        <div data-label="Tipo Archivo">
                            <?php if ($formato->archivo): ?>
                                <span class="document-type type-<?php echo strtolower(pathinfo($formato->archivo, PATHINFO_EXTENSION) ?? 'pdf'); ?>">
                                    <?php echo strtoupper(pathinfo($formato->archivo, PATHINFO_EXTENSION) ?? 'PDF'); ?>
                                </span>
                            <?php else: ?>
                                <span class="small-text">Sin archivo</span>
                            <?php endif; ?>
                        </div>
                        <div data-label="Tamaño"><?php echo $formato->tamanio ? round($formato->tamanio / 1024 / 1024, 2) . ' MB' : 'N/A'; ?></div>
                        <div class="actions" data-label="Acciones">
                            <button class="btn-action view" title="Ver" onclick="verFormato(<?php echo $formato->id; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <?php 
                            // Verificar permisos de edición
                            $puedeEditar = $esLina || 
                                          ($formato->fk_usuario_id == $_SESSION['user']['id']) || 
                                          ($formato->permisos_usuario === 'edicion');
                            ?>
                            
                            <?php if ($puedeEditar): ?>
                                <button class="btn-action edit" title="Editar" onclick="editarFormato(<?php echo $formato->id; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn-action edit" title="Sin permisos de edición" disabled>
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn-action download" title="Descargar" onclick="descargarFormato(<?php echo $formato->id; ?>)">
                                <i class="fas fa-download"></i>
                            </button>
                            
                            <!-- Botón Gestionar Permisos - Solo para propietarios -->
                            <?php if ($formato->fk_usuario_id == $_SESSION['user']['id']): ?>
                                <button class="btn-action share" title="Gestionar Permisos" onclick="gestionarPermisosFormato(<?php echo $formato->id; ?>)">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($esLina || $formato->fk_usuario_id == $_SESSION['user']['id']): ?>
                                <button class="btn-action delete" title="Eliminar" onclick="eliminarFormato(<?php echo $formato->id; ?>, '<?php echo htmlspecialchars($formato->nombre_formato); ?>')">
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

<!-- Modal Agregar/Editar Formato -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalTitle">Nuevo Formato</h2>
            <button class="close-modal" id="closeModal">✕</button>
        </div>
        <div class="modal-body">
            <form id="formatoForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label" for="nombreFormato">Nombre del Formato *</label>
                    <input type="text" class="form-input" id="nombreFormato" name="nombre_formato" placeholder="Ingrese el nombre del formato" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="versionFormato">Versión *</label>
                        <input type="text" class="form-input" id="versionFormato" name="version" placeholder="Ej: v1.0" value="v1.0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="areaFormato">Área *</label>
                        <select class="form-select" id="areaFormato" name="area_id" required>
                            <option value="">Seleccione el área</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area->id; ?>"><?php echo htmlspecialchars($area->nombre); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="descripcionFormato">Descripción</label>
                    <textarea class="form-textarea" id="descripcionFormato" name="descripcion" placeholder="Describa el propósito y uso del formato"></textarea>
                </div>

                <!-- ✅ CAMPO DE ARCHIVO - SIEMPRE VISIBLE TANTO EN CREACIÓN COMO EDICIÓN -->
                <div class="form-group">
                    <label class="form-label">Subir Archivo *</label>
                    <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Haga clic para subir el archivo o arrastre y suelte</p>
                        <p class="small-text">Formatos aceptados: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Máx. 25MB)</p>
                        <input type="file" class="file-input" id="fileInput" name="archivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                    </div>
                    <div id="fileName" class="file-name"></div>
                </div>

                <?php if ($esLina && !empty($usuarios)): ?>

                <div class="form-group">
                    <label class="form-label">Compartir con usuarios:</label>
                    <div class="usuarios-compartir-container">
                        <div class="checkbox-group" style="margin-bottom: 0.5rem;">
                            <input type="checkbox" id="selectAllUsers">
                            <label for="selectAllUsers" style="font-weight: 600;">Seleccionar todos</label>
                        </div>
                        <div class="usuarios-grid" id="usuariosGrid">
                            <?php foreach ($usuarios as $usuario): ?>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="usuarios_compartir[]" value="<?php echo $usuario->id; ?>" id="user_<?php echo $usuario->id; ?>">
                                    <label for="user_<?php echo $usuario->id; ?>">
                                        <?php echo htmlspecialchars($usuario->nombre . ' ' . $usuario->apellidos); ?>
                                        <small style="color: #666;">(<?php echo htmlspecialchars($usuario->email); ?>)</small>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tipoPermisosFormato">Tipo de Permisos:</label>
                    <select class="form-select" id="tipoPermisosFormato" name="permisos">
                        <option value="lectura">Solo Lectura</option>
                        <option value="edicion">Edición</option>
                    </select>
                </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelModal">Cancelar</button>
            <button class="btn-success" id="btnGuardarFormato">Guardar Formato</button>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Permisos de Formatos -->
<div class="modal-overlay" id="permisosModalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2>Gestionar Permisos del Formato</h2>
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
                    <div class="usuarios-grid" id="usuariosGridPermisos" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; padding: 1rem; background: #f8f9fa;">
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

<script>
console.log('🚀 formatos.js cargado - VERSIÓN CORREGIDA');

let formatoEditando = null;
let formatoGestionandoPermisos = null;
const esLina = <?php echo $esLina ? 'true' : 'false'; ?>;

// Variables de paginación
let currentPage = 1;
let itemsPerPage = 10;
let allFormatos = [];

// Timer para búsqueda en tiempo real
let searchTimer = null;

// Esperar a que cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Inicializando sistema de formatos');
    
    // Inicializar todos los componentes
    inicializarEventosBasicos();
    inicializarFiltrosTiempoReal();
    inicializarPaginacion();
    
    if (esLina) {
        inicializarSeleccionTodos();
        inicializarPermisos();
    }
});

// ==========================================
// INICIALIZACIÓN DE COMPONENTES
// ==========================================

function inicializarEventosBasicos() {
    // Botón nuevo formato
    const btnNuevo = document.getElementById('openModal');
    if (btnNuevo) {
        btnNuevo.onclick = function(e) {
            e.preventDefault();
            abrirModal(false);
        };
    }

    // Botones cerrar modal principal
    const btnCerrar = document.getElementById('closeModal');
    const btnCancelar = document.getElementById('cancelModal');
    if (btnCerrar) btnCerrar.onclick = cerrarModal;
    if (btnCancelar) btnCancelar.onclick = cerrarModal;

    // Click fuera del modal
    const overlay = document.getElementById('modalOverlay');
    if (overlay) {
        overlay.onclick = function(e) {
            if (e.target === overlay) cerrarModal();
        };
    }

    // Botón guardar
    const btnGuardar = document.getElementById('btnGuardarFormato');
    if (btnGuardar) {
        btnGuardar.onclick = guardarFormato;
    }

    // File input
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.onchange = function() {
            const fileName = document.getElementById('fileName');
            if (this.files[0]) {
                const file = this.files[0];
                const mb = (file.size / 1024 / 1024).toFixed(2);
                fileName.textContent = `📎 ${file.name} (${mb} MB)`;
                fileName.style.color = '#27ae60';
            }
        };
    }

    // ESC para cerrar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
            cerrarModalPermisos();
        }
    });
}

function inicializarFiltrosTiempoReal() {
    console.log('🔄 Inicializando filtros en tiempo real');
    
    const searchInput = document.getElementById('searchInput');
    const areaFilter = document.getElementById('areaFilter');
    const versionFilter = document.getElementById('versionFilter');
    const tipoAccesoFilter = document.getElementById('tipoAccesoFilter');
    const ordenFilter = document.getElementById('ordenFilter');
    const btnLimpiar = document.getElementById('resetFilters');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    
    // Búsqueda en tiempo real con debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                aplicarFiltrosYOrdenamiento();
            }, 300);
        });
    }
    
    // Filtros en tiempo real
    if (areaFilter) areaFilter.addEventListener('change', aplicarFiltrosYOrdenamiento);
    if (versionFilter) versionFilter.addEventListener('change', aplicarFiltrosYOrdenamiento);
    if (tipoAccesoFilter) tipoAccesoFilter.addEventListener('change', aplicarFiltrosYOrdenamiento);
    if (ordenFilter) ordenFilter.addEventListener('change', aplicarFiltrosYOrdenamiento);
    
    // Items por página
    if (itemsPerPageSelect) {
        itemsPerPageSelect.addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            aplicarPaginacion();
            actualizarPaginadores();
        });
    }
    
    // Botón limpiar
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
}

// ==========================================
// SISTEMA DE FILTRADO Y ORDENAMIENTO - COMPLETAMENTE CORREGIDO
// ==========================================

function aplicarFiltrosYOrdenamiento() {
    console.log('🎯 Aplicando filtros y ordenamiento...');
    
    const busqueda = document.getElementById('searchInput').value.toLowerCase();
    const area = document.getElementById('areaFilter').value;
    const version = document.getElementById('versionFilter').value;
    const tipoAcceso = document.getElementById('tipoAccesoFilter').value;
    const orden = document.getElementById('ordenFilter').value;

    console.log('🔍 Filtros activos:', { busqueda, area, version, tipoAcceso, orden });

    // Obtener todas las filas de formatos
    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );

    // Aplicar filtros
    let filasFiltradas = filas.filter(fila => {
        const nombre = fila.children[1]?.textContent.toLowerCase() || '';
        const versionDoc = fila.children[2]?.textContent.trim() || '';
        const areaDoc = fila.children[3]?.textContent.trim() || '';
        const tipoAccesoDoc = fila.querySelector('.tipo-acceso')?.textContent.trim() || '';

        // Aplicar filtros
        const coincideBusqueda = !busqueda || nombre.includes(busqueda);
        const coincideArea = !area || areaDoc === area;
        const coincideVersion = !version || versionDoc === version;
        const coincideAcceso = !tipoAcceso || 
            (tipoAcceso === 'propios' && tipoAccesoDoc.includes('Mi formato')) ||
            (tipoAcceso === 'compartidos' && tipoAccesoDoc.includes('Compartido'));

        return coincideBusqueda && coincideArea && coincideVersion && coincideAcceso;
    });

    console.log(`📊 Filas después de filtros: ${filasFiltradas.length} de ${filas.length}`);

    // Aplicar ordenamiento
    filasFiltradas = aplicarOrdenamiento(filasFiltradas, orden);

    // Reorganizar el DOM con el nuevo orden
    reorganizarFilasEnDOM(filasFiltradas);

    // Actualizar paginación
    allFormatos = filasFiltradas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
}

function aplicarOrdenamiento(filas, criterio) {
    console.log(`🔄 Ordenando por: ${criterio}`);
    
    return [...filas].sort((a, b) => {
        try {
            switch (criterio) {
                case 'reciente':
                    return compararFechas(a, b, 'desc');
                
                case 'antiguo':
                    return compararFechas(a, b, 'asc');
                
                case 'az':
                    return compararNombres(a, b, 'asc');
                
                case 'za':
                    return compararNombres(a, b, 'desc');
                
                default:
                    return 0;
            }
        } catch (error) {
            console.error('❌ Error en ordenamiento:', error);
            return 0;
        }
    });
}

function compararFechas(filaA, filaB, orden = 'desc') {
    const fechaA = extraerFechaFila(filaA);
    const fechaB = extraerFechaFila(filaB);
    
    if (orden === 'desc') {
        return fechaB - fechaA; // Más reciente primero
    } else {
        return fechaA - fechaB; // Más antiguo primero
    }
}

function extraerFechaFila(fila) {
    const textoFecha = fila.children[0]?.textContent.trim() || '';
    
    if (!textoFecha) return new Date(0);
    
    try {
        // Convertir "dd/mm/yyyy" a Date
        const partes = textoFecha.split('/');
        if (partes.length === 3) {
            const dia = parseInt(partes[0], 10);
            const mes = parseInt(partes[1], 10) - 1;
            const año = parseInt(partes[2], 10);
            return new Date(año, mes, dia);
        }
    } catch (error) {
        console.error('❌ Error parseando fecha:', textoFecha, error);
    }
    
    return new Date(0);
}

function compararNombres(filaA, filaB, orden = 'asc') {
    const nombreA = extraerNombreFila(filaA).toLowerCase();
    const nombreB = extraerNombreFila(filaB).toLowerCase();
    
    if (orden === 'asc') {
        return nombreA.localeCompare(nombreB); // A-Z
    } else {
        return nombreB.localeCompare(nombreA); // Z-A
    }
}

function extraerNombreFila(fila) {
    const contenido = fila.children[1]?.textContent || '';
    // Tomar solo la primera línea (el nombre principal)
    const primeraLinea = contenido.split('\n')[0].trim();
    return primeraLinea;
}

function reorganizarFilasEnDOM(filasOrdenadas) {
    const container = document.getElementById('formatosContainer');
    if (!container) return;
    
    // Limpiar container (excepto no-results)
    const noResults = container.querySelector('.no-results');
    container.innerHTML = '';
    if (noResults) {
        container.appendChild(noResults);
    }
    
    // Agregar filas en nuevo orden
    filasOrdenadas.forEach(fila => {
        container.appendChild(fila);
    });
    
    console.log('✅ Filas reorganizadas en DOM');
}

function limpiarFiltros() {
    console.log('🧹 Limpiando todos los filtros...');
    
    // Resetear valores
    document.getElementById('searchInput').value = '';
    document.getElementById('areaFilter').value = '';
    document.getElementById('versionFilter').value = '';
    document.getElementById('tipoAccesoFilter').value = '';
    document.getElementById('ordenFilter').value = 'reciente';
    
    // Obtener todas las filas originales
    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );
    
    // Reorganizar en orden original (por fecha más reciente)
    const filasOrdenadas = aplicarOrdenamiento(filas, 'reciente');
    reorganizarFilasEnDOM(filasOrdenadas);
    
    // Mostrar todas las filas
    filasOrdenadas.forEach(fila => {
        fila.style.display = 'grid';
    });
    
    // Recargar paginación
    allFormatos = filasOrdenadas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
    
    console.log('✅ Filtros limpiados correctamente');
}

// ==========================================
// SISTEMA DE PAGINACIÓN
// ==========================================

function inicializarPaginacion() {
    console.log('🔄 Inicializando sistema de paginación...');
    
    // Obtener todos los formatos del DOM
    allFormatos = Array.from(document.querySelectorAll('.grid-row')).filter(row => {
        return !row.classList.contains('no-results');
    });
    
    console.log(`📊 Total de formatos encontrados: ${allFormatos.length}`);
    
    // Aplicar paginación inicial
    aplicarPaginacion();
    actualizarPaginadores();
}

function aplicarPaginacion() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const totalFormatos = allFormatos.length;
    
    console.log(`📄 Página ${currentPage}: mostrando items ${startIndex + 1}-${Math.min(endIndex, totalFormatos)} de ${totalFormatos}`);
    
    // Ocultar todos los formatos primero
    allFormatos.forEach((formato, index) => {
        if (index >= startIndex && index < endIndex) {
            formato.style.display = 'grid';
        } else {
            formato.style.display = 'none';
        }
    });
    
    // Actualizar información de página
    actualizarInfoPagina();
}

function actualizarInfoPagina() {
    const totalFormatos = allFormatos.length;
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, totalFormatos);
    
    const infoText = totalFormatos > 0 
        ? `Mostrando ${startIndex}-${endIndex} de ${totalFormatos} formatos` 
        : 'No se encontraron formatos';
    
    const pageInfoTop = document.getElementById('pageInfoTop');
    const pageInfoBottom = document.getElementById('pageInfoBottom');
    
    if (pageInfoTop) pageInfoTop.textContent = infoText;
    if (pageInfoBottom) pageInfoBottom.textContent = infoText;
}

function actualizarPaginadores() {
    const totalFormatos = allFormatos.length;
    const totalPages = Math.ceil(totalFormatos / itemsPerPage);
    
    console.log(`🔢 Actualizando paginadores: ${totalPages} páginas totales`);
    
    // Actualizar ambos paginadores
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
    
    // Scroll suave a la tabla
    const tablaContainer = document.querySelector('.grid-container');
    if (tablaContainer) {
        tablaContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

// ==========================================
// FUNCIONES DE GESTIÓN DE FORMATOS (MANTENIDAS)
// ==========================================

function inicializarSeleccionTodos() {
    const selectAll = document.getElementById('selectAllUsers');
    const usuariosGrid = document.getElementById('usuariosGrid');
    
    if (!selectAll || !usuariosGrid) return;

    selectAll.addEventListener('change', function() {
        const checkboxes = usuariosGrid.querySelectorAll('input[name="usuarios_compartir[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    const checkboxes = usuariosGrid.querySelectorAll('input[name="usuarios_compartir[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', actualizarSeleccionTodos);
    });
}

function inicializarPermisos() {
    const btnCerrarPermisos = document.getElementById('closePermisosModal');
    const btnCancelarPermisos = document.getElementById('cancelPermisosModal');
    if (btnCerrarPermisos) btnCerrarPermisos.onclick = cerrarModalPermisos;
    if (btnCancelarPermisos) btnCancelarPermisos.onclick = cerrarModalPermisos;
    
    const btnGuardarPermisos = document.getElementById('btnGuardarPermisos');
    if (btnGuardarPermisos) btnGuardarPermisos.onclick = guardarPermisosFormato;
    
    const selectAllPermisos = document.getElementById('selectAllUsersPermisos');
    if (selectAllPermisos) {
        selectAllPermisos.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('#usuariosGridPermisos input[name="usuarios_permisos[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    const overlayPermisos = document.getElementById('permisosModalOverlay');
    if (overlayPermisos) {
        overlayPermisos.onclick = function(e) {
            if (e.target === overlayPermisos) cerrarModalPermisos();
        };
    }
}

function actualizarSeleccionTodos() {
    const selectAll = document.getElementById('selectAllUsers');
    if (!selectAll) return;
    
    const checkboxes = document.querySelectorAll('#usuariosGrid input[name="usuarios_compartir[]"]');
    const todosMarcados = Array.from(checkboxes).every(cb => cb.checked);
    const algunoMarcado = Array.from(checkboxes).some(cb => cb.checked);
    
    selectAll.checked = todosMarcados;
    selectAll.indeterminate = algunoMarcado && !todosMarcados;
}

function actualizarSeleccionTodosPermisos() {
    const selectAll = document.getElementById('selectAllUsersPermisos');
    if (!selectAll) return;
    
    const checkboxes = document.querySelectorAll('#usuariosGridPermisos input[name="usuarios_permisos[]"]');
    const todosMarcados = Array.from(checkboxes).every(cb => cb.checked);
    const algunoMarcado = Array.from(checkboxes).some(cb => cb.checked);
    
    selectAll.checked = todosMarcados;
    selectAll.indeterminate = algunoMarcado && !todosMarcados;
}

function abrirModal(editar = false, formato = null) {
    console.log('Abriendo modal. Editar:', editar);
    
    const overlay = document.getElementById('modalOverlay');
    if (!overlay) return;

    formatoEditando = editar ? formato : null;

    if (editar && formato) {
        document.getElementById('modalTitle').textContent = 'Editar Formato';
        document.getElementById('btnGuardarFormato').textContent = 'Actualizar Formato';
        
        document.getElementById('nombreFormato').value = formato.nombre_formato || '';
        document.getElementById('versionFormato').value = formato.version || 'v1.0';
        document.getElementById('areaFormato').value = formato.fk_area_id || '';
        document.getElementById('descripcionFormato').value = formato.descripcion || '';
        
        const fileName = document.getElementById('fileName');
        if (formato.archivo && fileName) {
            const nombreOriginal = formato.archivo.includes('_') 
                ? formato.archivo.split('_').slice(1).join('_') 
                : formato.archivo;
            fileName.textContent = `📎 Archivo actual: ${nombreOriginal}`;
            fileName.style.color = '#666';
        }
        
        if (formato.usuarios_compartidos && esLina) {
            const checkboxes = document.querySelectorAll('#usuariosGrid input[name="usuarios_compartir[]"]');
            checkboxes.forEach(checkbox => {
                const usuarioCompartido = formato.usuarios_compartidos.find(
                    uc => uc.id == checkbox.value
                );
                checkbox.checked = !!usuarioCompartido;
            });
            actualizarSeleccionTodos();
        }
        
    } else {
        document.getElementById('modalTitle').textContent = 'Nuevo Formato';
        document.getElementById('btnGuardarFormato').textContent = 'Guardar Formato';
        limpiarFormulario();
    }

    overlay.style.display = 'flex';
    setTimeout(() => overlay.classList.add('active'), 10);
}

function cerrarModal() {
    const overlay = document.getElementById('modalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            formatoEditando = null;
            limpiarFormulario();
        }, 300);
    }
}

function limpiarFormulario() {
    document.getElementById('nombreFormato').value = '';
    document.getElementById('versionFormato').value = 'v1.0';
    document.getElementById('areaFormato').value = '';
    document.getElementById('descripcionFormato').value = '';
    document.getElementById('fileInput').value = '';
    
    const fileName = document.getElementById('fileName');
    if (fileName) fileName.textContent = '';
    
    if (esLina) {
        const checkboxes = document.querySelectorAll('#usuariosGrid input[name="usuarios_compartir[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        actualizarSeleccionTodos();
    }
}

function guardarFormato() {
    if (formatoEditando) {
        actualizarFormato();
    } else {
        crearFormato();
    }
}

function crearFormato() {
    const nombre = document.getElementById('nombreFormato').value.trim();
    const area = document.getElementById('areaFormato').value;
    const file = document.getElementById('fileInput').files[0];

    if (!nombre) return alert('❌ Ingrese nombre del formato');
    if (!area) return alert('❌ Seleccione área');
    if (!file) return alert('❌ Seleccione un archivo');
    if (file.size > 25 * 1024 * 1024) return alert('❌ Archivo muy grande (máx 25MB)');

    const formData = new FormData();
    formData.append('nombre_formato', nombre);
    formData.append('area_id', area);
    formData.append('version', document.getElementById('versionFormato').value || 'v1.0');
    formData.append('descripcion', document.getElementById('descripcionFormato').value || '');
    formData.append('archivo', file);

    if (esLina) {
        const usuariosCompartir = [];
        document.querySelectorAll('#usuariosGrid input[name="usuarios_compartir[]"]:checked').forEach(checkbox => {
            usuariosCompartir.push(checkbox.value);
        });
        
        usuariosCompartir.forEach(usuarioId => {
            formData.append('usuarios_compartir[]', usuarioId);
        });

        const tipoPermisos = document.getElementById('tipoPermisosFormato').value;
        formData.append('permisos', tipoPermisos);
    }

    const btn = document.getElementById('btnGuardarFormato');
    const txtOriginal = btn.textContent;
    btn.textContent = '⏳ Guardando...';
    btn.disabled = true;

    fetch('/formatos/crear', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                alert('✅ Formato creado exitosamente');
                cerrarModal();
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (e) {
            alert('❌ Error en respuesta del servidor');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión');
    })
    .finally(() => {
        btn.textContent = txtOriginal;
        btn.disabled = false;
    });
}

function actualizarFormato() {
    const nombre = document.getElementById('nombreFormato').value.trim();
    const area = document.getElementById('areaFormato').value;
    const file = document.getElementById('fileInput').files[0];

    if (!nombre || !area) {
        alert('❌ Complete los campos obligatorios: Nombre y Área');
        return;
    }

    const formData = new FormData();
    formData.append('nombre_formato', nombre);
    formData.append('version', document.getElementById('versionFormato').value.trim());
    formData.append('area_id', area);
    formData.append('descripcion', document.getElementById('descripcionFormato').value.trim());
    
    if (file) {
        if (file.size > 25 * 1024 * 1024) {
            alert('❌ Archivo muy grande (máx 25MB)');
            return;
        }
        formData.append('archivo', file);
    }

    const btn = document.getElementById('btnGuardarFormato');
    const txtOriginal = btn.textContent;
    btn.textContent = '⏳ Actualizando...';
    btn.disabled = true;

    fetch(`/formatos/editar/${formatoEditando.id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                throw new Error('El servidor no devolvió JSON');
            });
        }
    })
    .then(data => {
        if (data.success) {
            const mensaje = data.actualizado_por ? 
                `✅ Formato actualizado por ${data.actualizado_por}` : 
                '✅ Formato actualizado correctamente';
            alert(mensaje);
            cerrarModal();
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('❌ Error en fetch:', error);
        alert('❌ Error: ' + error.message);
    })
    .finally(() => {
        btn.textContent = txtOriginal;
        btn.disabled = false;
    });
}

function gestionarPermisosFormato(formatoId) {
    formatoGestionandoPermisos = formatoId;
    
    fetch(`/formatos/obtener/${formatoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const formato = data.formato;
                
                document.querySelectorAll('#usuariosGridPermisos input[name="usuarios_permisos[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                if (formato.usuarios_compartidos && Array.isArray(formato.usuarios_compartidos)) {
                    formato.usuarios_compartidos.forEach(usuario => {
                        const checkbox = document.getElementById(`user_perm_${usuario.id}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }
                
                actualizarSeleccionTodosPermisos();
                
                const overlay = document.getElementById('permisosModalOverlay');
                overlay.style.display = 'flex';
                setTimeout(() => overlay.classList.add('active'), 10);
            } else {
                alert('Error al cargar los permisos del formato');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar permisos del formato');
        });
}

function guardarPermisosFormato() {
    if (!formatoGestionandoPermisos) return;
    
    const usuariosSeleccionados = [];
    document.querySelectorAll('#usuariosGridPermisos input[name="usuarios_permisos[]"]:checked').forEach(checkbox => {
        usuariosSeleccionados.push(checkbox.value);
    });
    
    const tipoPermisos = document.getElementById('tipoPermisos').value;
    
    const datos = {
        usuarios_compartir: usuariosSeleccionados,
        permisos: tipoPermisos
    };
    
    const btn = document.getElementById('btnGuardarPermisos');
    const textoOriginal = btn.textContent;
    btn.textContent = '⏳ Guardando...';
    btn.disabled = true;
    
    fetch(`/formatos/actualizar-permisos/${formatoGestionandoPermisos}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Permisos actualizados correctamente');
            cerrarModalPermisos();
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar permisos');
    })
    .finally(() => {
        btn.textContent = textoOriginal;
        btn.disabled = false;
    });
}

function cerrarModalPermisos() {
    const overlay = document.getElementById('permisosModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
        formatoGestionandoPermisos = null;
    }
}

// ==========================================
// FUNCIONES DE ACCIONES BÁSICAS
// ==========================================

function verFormato(id) {
    window.open(`/formatos/previsualizar/${id}`, '_blank');
}

function descargarFormato(id) {
    window.location.href = `/formatos/descargar/${id}`;
}

function editarFormato(id) {
    const btn = event.target.closest('.btn-action');
    const html = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/formatos/obtener/${id}`)
        .then(r => {
            if (!r.ok) throw new Error('Error en la respuesta del servidor');
            return r.json();
        })
        .then(data => {
            if (data.success) {
                abrirModal(true, data.formato);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Error al cargar formato: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = html;
            btn.disabled = false;
        });
}

function eliminarFormato(id, nombre) {
    if (!confirm(`⚠️ ¿ELIMINAR el formato "${nombre}"?\n\nNo se puede deshacer.`)) return;

    const btn = event.target.closest('.btn-action');
    const html = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/formatos/eliminar/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✅ Formato eliminado');
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Error al eliminar');
    })
    .finally(() => {
        btn.innerHTML = html;
        btn.disabled = false;
    });
}

// ==========================================
// FUNCIONES GLOBALES
// ==========================================

window.verFormato = verFormato;
window.descargarFormato = descargarFormato;
window.editarFormato = editarFormato;
window.eliminarFormato = eliminarFormato;
window.gestionarPermisosFormato = gestionarPermisosFormato;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>