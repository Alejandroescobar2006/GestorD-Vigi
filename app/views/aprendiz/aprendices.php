<?php
// [file name]: aprendices.php
// Ubicación: app/views/aprendiz/aprendices.php

$pageTitle = 'Aprendices - Vigitecol';
$currentSection = 'aprendices';
$customScript = '/js/aprendiz-aprendices.js';
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1.5fr 1fr 1.2fr;
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
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1.5fr 1fr 1.2fr;
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

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 70px;
    }

    .status-badge.activo {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .status-badge.inactivo {
        background: #ffebee;
        color: #c62828;
    }

    .status-badge.graduado {
        background: #e3f2fd;
        color: #1565c0;
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
        max-width: 600px;
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

    .file-name {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #333;
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

    .loading {
        opacity: 0.6;
        pointer-events: none;
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
            grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1.5fr 1fr 1.2fr;
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
        <h1>Compañeros Aprendices</h1>
        <button class="btn-primary" onclick="abrirModalAprendiz()">
            <i class="fas fa-plus"></i> Nuevo Aprendiz
        </button>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Buscar aprendices..." 
                   value="<?php echo htmlspecialchars($filtros['busqueda'] ?? ''); ?>">
        </div>

        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label" for="estadoFilter">Estado</label>
                <select class="filter-select" id="estadoFilter">
                    <option value="">Todos los estados</option>
                    <option value="activo" <?php echo ($filtros['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($filtros['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    <option value="graduado" <?php echo ($filtros['estado'] ?? '') === 'graduado' ? 'selected' : ''; ?>>Graduado</option>
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
                <button class="btn-filter btn-apply" onclick="aplicarFiltros()">
                    <i class="fas fa-filter"></i> Aplicar Filtros
                </button>
                <button class="btn-filter btn-reset" onclick="limpiarFiltros()">
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

    <!-- Contenedor de aprendices -->
    <div class="grid-container">
        <div class="grid-header">
            <div>Nombre</div>
            <div>Apellidos</div>
            <div>Cédula</div>
            <div>Teléfono</div>
            <div>Correo</div>
            <div>Estado</div>
            <div>Acciones</div>
        </div>

        <div id="aprendicesContainer">
            <?php if (empty($aprendices)): ?>
                <div class="no-results">
                    <p>No se encontraron aprendices</p>
                </div>
            <?php else: ?>
                <?php foreach ($aprendices as $aprendiz): ?>
                    <div class="grid-row" data-aprendiz-id="<?php echo $aprendiz->id; ?>">
                        <div data-label="Nombre"><?php echo htmlspecialchars($aprendiz->nombre); ?></div>
                        <div data-label="Apellidos"><?php echo htmlspecialchars($aprendiz->apellidos); ?></div>
                        <div data-label="Cédula"><?php echo htmlspecialchars($aprendiz->cedula); ?></div>
                        <div data-label="Teléfono"><?php echo htmlspecialchars($aprendiz->telefono); ?></div>
                        <div data-label="Correo"><?php echo htmlspecialchars($aprendiz->correo); ?></div>
                        <div data-label="Estado">
                            <span class="status-badge <?php echo $aprendiz->estado; ?>">
                                <?php echo ucfirst($aprendiz->estado); ?>
                            </span>
                        </div>
                        <div class="actions" data-label="Acciones">
                            <button class="btn-action view" title="Ver detalles" onclick="verAprendiz(<?php echo $aprendiz->id; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-action edit" title="Editar" onclick="editarAprendiz(<?php echo $aprendiz->id; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($aprendiz->certificado): ?>
                                <button class="btn-action download" title="Descargar Certificado" onclick="descargarCertificado(<?php echo $aprendiz->id; ?>)">
                                    <i class="fas fa-download"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn-action download" title="Sin certificado" disabled>
                                    <i class="fas fa-download"></i>
                                </button>
                            <?php endif; ?>
                            <button class="btn-action delete" title="Eliminar" onclick="eliminarAprendiz(<?php echo $aprendiz->id; ?>, '<?php echo htmlspecialchars($aprendiz->nombre . ' ' . $aprendiz->apellidos); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
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

<!-- Modal para Crear/Editar Aprendiz -->
<div class="modal-overlay" id="modalAprendiz">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalAprendizTitle">Nuevo Aprendiz</h2>
            <button class="close-modal" id="closeModalAprendiz">✕</button>
        </div>
        <div class="modal-body">
            <form id="formAprendiz" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nombreAprendiz">Nombre *</label>
                        <input type="text" class="form-input" id="nombreAprendiz" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="apellidosAprendiz">Apellidos *</label>
                        <input type="text" class="form-input" id="apellidosAprendiz" name="apellidos" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="cedulaAprendiz">Cédula *</label>
                        <input type="text" class="form-input" id="cedulaAprendiz" name="cedula" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="telefonoAprendiz">Teléfono</label>
                        <input type="text" class="form-input" id="telefonoAprendiz" name="telefono">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="correoAprendiz">Correo Electrónico</label>
                    <input type="email" class="form-input" id="correoAprendiz" name="correo">
                </div>

                <div class="form-group">
                    <label class="form-label" for="estadoAprendiz">Estado</label>
                    <select class="form-select" id="estadoAprendiz" name="estado">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="graduado">Graduado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="notasAprendiz">Notas Adicionales</label>
                    <textarea class="form-textarea" id="notasAprendiz" name="notas" placeholder="Observaciones o comentarios..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Certificado (PDF)</label>
                    <div class="file-upload" onclick="document.getElementById('certificadoFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Haga clic para subir el certificado PDF</p>
                        <p class="small-text">Tamaño máximo: 10MB</p>
                        <input type="file" id="certificadoFile" name="certificado" accept=".pdf" class="file-input">
                    </div>
                    <div id="certificadoFileName" class="file-name"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelModalAprendiz">Cancelar</button>
            <button class="btn-success" id="btnGuardarAprendiz">Guardar Aprendiz</button>
        </div>
    </div>
</div>

<!-- Modal para Ver Aprendiz -->
<div class="modal-overlay" id="modalVerAprendiz">
    <div class="modal">
        <div class="modal-header">
            <h2>Detalles del Aprendiz</h2>
            <button class="close-modal" id="closeModalVerAprendiz">✕</button>
        </div>
        <div class="modal-body">
            <div id="detallesAprendiz">
                <!-- Los detalles se cargarán aquí -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelModalVerAprendiz">Cerrar</button>
        </div>
    </div>
</div>

<script>
let aprendizEditando = null;

// Variables de paginación
let currentPage = 1;
let itemsPerPage = 10;
let allAprendices = [];
let searchTimer = null;

// ==========================================
// INICIALIZACIÓN
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Vista de Aprendices cargada correctamente');
    
    inicializarModales();
    inicializarEventos();
    inicializarPaginacion();
});

function inicializarModales() {
    // Modal aprendiz
    const btnCerrar = document.getElementById('closeModalAprendiz');
    const btnCancelar = document.getElementById('cancelModalAprendiz');
    if (btnCerrar) btnCerrar.addEventListener('click', cerrarModalAprendiz);
    if (btnCancelar) btnCancelar.addEventListener('click', cerrarModalAprendiz);

    // Modal ver aprendiz
    const btnCerrarVer = document.getElementById('closeModalVerAprendiz');
    const btnCancelarVer = document.getElementById('cancelModalVerAprendiz');
    if (btnCerrarVer) btnCerrarVer.addEventListener('click', cerrarModalVerAprendiz);
    if (btnCancelarVer) btnCancelarVer.addEventListener('click', cerrarModalVerAprendiz);

    // Botón guardar
    const btnGuardar = document.getElementById('btnGuardarAprendiz');
    if (btnGuardar) btnGuardar.addEventListener('click', guardarAprendiz);

    // File input
    const certificadoFile = document.getElementById('certificadoFile');
    if (certificadoFile) {
        certificadoFile.addEventListener('change', function() {
            const fileName = document.getElementById('certificadoFileName');
            if (this.files[0]) {
                const file = this.files[0];
                const mb = (file.size / 1024 / 1024).toFixed(2);
                fileName.textContent = `📎 ${file.name} (${mb} MB)`;
                fileName.style.color = '#27ae60';
            }
        });
    }

    // Click fuera de modales
    const modalAprendiz = document.getElementById('modalAprendiz');
    const modalVerAprendiz = document.getElementById('modalVerAprendiz');
    
    if (modalAprendiz) {
        modalAprendiz.addEventListener('click', function(e) {
            if (e.target === modalAprendiz) {
                cerrarModalAprendiz();
            }
        });
    }
    
    if (modalVerAprendiz) {
        modalVerAprendiz.addEventListener('click', function(e) {
            if (e.target === modalVerAprendiz) {
                cerrarModalVerAprendiz();
            }
        });
    }

    // ESC para cerrar modales
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalAprendiz();
            cerrarModalVerAprendiz();
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
                aplicarFiltros();
            }, 300);
        });
    }
    
    // Filtros en tiempo real
    document.getElementById('estadoFilter').addEventListener('change', aplicarFiltros);
    
    // Items por página
    document.getElementById('itemsPerPage').addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        aplicarPaginacion();
        actualizarPaginadores();
    });
}

// ==========================================
// SISTEMA DE PAGINACIÓN
// ==========================================

function inicializarPaginacion() {
    console.log('🔄 Inicializando sistema de paginación...');
    
    // Obtener todos los aprendices del DOM
    allAprendices = Array.from(document.querySelectorAll('.grid-row')).filter(row => {
        return !row.classList.contains('no-results') && row.style.display !== 'none';
    });
    
    console.log(`📊 Total de aprendices encontrados: ${allAprendices.length}`);
    
    aplicarPaginacion();
    actualizarPaginadores();
}

function aplicarPaginacion() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const totalAprendices = allAprendices.length;
    
    console.log(`📄 Mostrando página ${currentPage}, items ${startIndex + 1}-${Math.min(endIndex, totalAprendices)} de ${totalAprendices}`);
    
    allAprendices.forEach((aprendiz, index) => {
        aprendiz.style.display = (index >= startIndex && index < endIndex) ? 'grid' : 'none';
    });
    
    actualizarInfoPagina();
}

function actualizarInfoPagina() {
    const totalAprendices = allAprendices.length;
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, totalAprendices);
    
    const infoText = totalAprendices > 0 
        ? `Mostrando ${startIndex}-${endIndex} de ${totalAprendices} aprendices` 
        : 'No se encontraron aprendices';
    
    document.getElementById('pageInfoTop').textContent = infoText;
    document.getElementById('pageInfoBottom').textContent = infoText;
}

function actualizarPaginadores() {
    const totalAprendices = allAprendices.length;
    const totalPages = Math.ceil(totalAprendices / itemsPerPage);
    
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
    
    const gridContainer = document.querySelector('.grid-container');
    if (gridContainer) {
        gridContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

// ==========================================
// SISTEMA DE FILTRADO
// ==========================================

function aplicarFiltros() {
    const busqueda = document.getElementById('searchInput').value.toLowerCase();
    const estado = document.getElementById('estadoFilter').value;

    console.log('🔍 Aplicando filtros:', { busqueda, estado });

    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );

    let filasFiltradas = filas.filter(fila => {
        const nombre = fila.children[0]?.textContent.toLowerCase() || '';
        const apellidos = fila.children[1]?.textContent.toLowerCase() || '';
        const cedula = fila.children[2]?.textContent.toLowerCase() || '';
        const telefono = fila.children[3]?.textContent.toLowerCase() || '';
        const correo = fila.children[4]?.textContent.toLowerCase() || '';
        const estadoAprendiz = fila.children[5]?.textContent.toLowerCase() || '';

        const coincideBusqueda = !busqueda || 
            nombre.includes(busqueda) || 
            apellidos.includes(busqueda) || 
            cedula.includes(busqueda) ||
            telefono.includes(busqueda) ||
            correo.includes(busqueda);
        
        const coincideEstado = !estado || estadoAprendiz.includes(estado.toLowerCase());

        return coincideBusqueda && coincideEstado;
    });

    const container = document.getElementById('aprendicesContainer');
    filas.forEach(fila => fila.remove());
    filasFiltradas.forEach(fila => container.appendChild(fila));

    allAprendices = filasFiltradas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
}

function limpiarFiltros() {
    console.log('🧹 Limpiando filtros...');
    
    document.getElementById('searchInput').value = '';
    document.getElementById('estadoFilter').value = '';
    
    const filas = Array.from(document.querySelectorAll('.grid-row')).filter(row => 
        !row.classList.contains('no-results')
    );
    
    const container = document.getElementById('aprendicesContainer');
    filas.forEach(fila => fila.remove());
    filas.forEach(fila => container.appendChild(fila));
    
    allAprendices = filas;
    currentPage = 1;
    aplicarPaginacion();
    actualizarPaginadores();
}

// ==========================================
// FUNCIONES DE MODALES
// ==========================================

function abrirModalAprendiz(aprendiz = null) {
    console.log('👤 Abriendo modal aprendiz');
    
    aprendizEditando = aprendiz;
    const modal = document.getElementById('modalAprendiz');
    const title = document.getElementById('modalAprendizTitle');
    
    if (aprendiz) {
        title.textContent = 'Editar Aprendiz';
        document.getElementById('nombreAprendiz').value = aprendiz.nombre || '';
        document.getElementById('apellidosAprendiz').value = aprendiz.apellidos || '';
        document.getElementById('cedulaAprendiz').value = aprendiz.cedula || '';
        document.getElementById('telefonoAprendiz').value = aprendiz.telefono || '';
        document.getElementById('correoAprendiz').value = aprendiz.correo || '';
        document.getElementById('estadoAprendiz').value = aprendiz.estado || 'activo';
        document.getElementById('notasAprendiz').value = aprendiz.notas || '';
        
        if (aprendiz.certificado) {
            document.getElementById('certificadoFileName').textContent = `📎 Certificado actual: ${aprendiz.certificado}`;
        } else {
            document.getElementById('certificadoFileName').textContent = '';
        }
    } else {
        title.textContent = 'Nuevo Aprendiz';
        document.getElementById('formAprendiz').reset();
        document.getElementById('certificadoFileName').textContent = '';
    }
    
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('active');
        console.log('✅ Modal aprendiz abierto');
    }, 10);
}

function cerrarModalAprendiz() {
    console.log('👤 Cerrando modal aprendiz');
    const modal = document.getElementById('modalAprendiz');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            console.log('✅ Modal aprendiz cerrado');
        }, 300);
    }
    aprendizEditando = null;
}

function abrirModalVerAprendiz(aprendiz) {
    console.log('👁️ Abriendo modal ver aprendiz');
    const modal = document.getElementById('modalVerAprendiz');
    const detalles = document.getElementById('detallesAprendiz');
    
    detalles.innerHTML = `
        <div style="line-height: 1.6;">
            <p><strong>Nombre:</strong> ${aprendiz.nombre} ${aprendiz.apellidos}</p>
            <p><strong>Cédula:</strong> ${aprendiz.cedula}</p>
            <p><strong>Teléfono:</strong> ${aprendiz.telefono || 'No especificado'}</p>
            <p><strong>Correo:</strong> ${aprendiz.correo || 'No especificado'}</p>
            <p><strong>Estado:</strong> <span class="status-badge ${aprendiz.estado}">${aprendiz.estado.charAt(0).toUpperCase() + aprendiz.estado.slice(1)}</span></p>
            <p><strong>Fecha de Registro:</strong> ${new Date(aprendiz.fecha_registro).toLocaleDateString()}</p>
            ${aprendiz.notas ? `<p><strong>Notas:</strong> ${aprendiz.notas}</p>` : ''}
            ${aprendiz.certificado ? `<p><strong>Certificado:</strong> ${aprendiz.certificado}</p>` : ''}
        </div>
    `;
    
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('active');
        console.log('✅ Modal ver aprendiz abierto');
    }, 10);
}

function cerrarModalVerAprendiz() {
    console.log('👁️ Cerrando modal ver aprendiz');
    const modal = document.getElementById('modalVerAprendiz');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            console.log('✅ Modal ver aprendiz cerrado');
        }, 300);
    }
}

// ==========================================
// FUNCIONES CRUD
// ==========================================

function guardarAprendiz() {
    const nombre = document.getElementById('nombreAprendiz').value.trim();
    const apellidos = document.getElementById('apellidosAprendiz').value.trim();
    const cedula = document.getElementById('cedulaAprendiz').value.trim();
    
    if (!nombre) {
        mostrarError('El nombre es obligatorio');
        return;
    }
    
    if (!apellidos) {
        mostrarError('Los apellidos son obligatorios');
        return;
    }
    
    if (!cedula) {
        mostrarError('La cédula es obligatoria');
        return;
    }

    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('apellidos', apellidos);
    formData.append('cedula', cedula);
    formData.append('telefono', document.getElementById('telefonoAprendiz').value.trim());
    formData.append('correo', document.getElementById('correoAprendiz').value.trim());
    formData.append('estado', document.getElementById('estadoAprendiz').value);
    formData.append('notas', document.getElementById('notasAprendiz').value.trim());
    
    const fileInput = document.getElementById('certificadoFile');
    if (fileInput.files[0]) {
        formData.append('certificado_file', fileInput.files[0]);
    }

    const btnGuardar = document.getElementById('btnGuardarAprendiz');
    const originalText = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    const url = aprendizEditando ? `/aprendiz/actualizar-aprendiz/${aprendizEditando.id}` : '/aprendiz/crear-aprendiz';
    
    fetch(url, {
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
        if (data.success) {
            mostrarExito('✅ Aprendiz guardado correctamente');
            cerrarModalAprendiz();
            location.reload();
        } else {
            mostrarError(data.message || 'Error al guardar aprendiz');
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

function verAprendiz(id) {
    console.log('👁️ Viendo aprendiz:', id);
    
    // Mostrar loading
    const btnVer = event.target.closest('.btn-action');
    const originalHTML = btnVer.innerHTML;
    btnVer.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`/aprendiz/obtener-aprendiz/${id}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener aprendiz');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            abrirModalVerAprendiz(data.aprendiz);
        } else {
            mostrarError(data.message || 'Error al cargar aprendiz');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    })
    .finally(() => {
        btnVer.innerHTML = originalHTML;
    });
}

function editarAprendiz(id) {
    console.log('✏️ Editando aprendiz:', id);
    
    // Mostrar loading
    const btnEditar = event.target.closest('.btn-action');
    const originalHTML = btnEditar.innerHTML;
    btnEditar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`/aprendiz/obtener-aprendiz/${id}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener aprendiz');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            abrirModalAprendiz(data.aprendiz);
        } else {
            mostrarError(data.message || 'Error al cargar aprendiz');
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

function eliminarAprendiz(id, nombre) {
    if (!confirm(`¿Está seguro de eliminar al aprendiz "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        return;
    }

    console.log('🗑️ Eliminando aprendiz:', id);
    
    fetch(`/aprendiz/eliminar-aprendiz/${id}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('✅ Aprendiz eliminado correctamente');
            location.reload();
        } else {
            mostrarError(data.message || 'Error al eliminar aprendiz');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        mostrarError('Error de conexión: ' + error.message);
    });
}

function descargarCertificado(id) {
    console.log('📥 Descargando certificado para aprendiz:', id);
    window.open(`/aprendiz/descargar-certificado/${id}`, '_blank');
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
window.abrirModalAprendiz = abrirModalAprendiz;
window.verAprendiz = verAprendiz;
window.editarAprendiz = editarAprendiz;
window.eliminarAprendiz = eliminarAprendiz;
window.descargarCertificado = descargarCertificado;
window.aplicarFiltros = aplicarFiltros;
window.limpiarFiltros = limpiarFiltros;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>