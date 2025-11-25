<?php
// app/views/dashboard/clientes.php
$pageTitle = 'Clientes - Vigitecol';
$currentSection = 'clientes';
$customScript = '/js/clientes.js';
ob_start();
?>

<style>
    /* Estilos mejorados para clientes */
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset {
        background: #e0e0e0;
        color: #555;
    }

    .btn-reset:hover {
        background: #d0d0d0;
    }

    /* Grid Container */
    .grid-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .grid-header {
        display: grid;
        grid-template-columns: 1fr 1fr 1.2fr 1fr 1fr 1fr 1fr;
        gap: 1rem;
        padding: 1rem;
        background: #e5e906;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        text-align: center;
        align-items: center;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1.2fr 1fr 1fr 1fr 1fr;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #eee;
        align-items: center;
        text-align: center;
        transition: background-color 0.3s;
    }

    .grid-row:hover {
        background: #f8f9fa;
    }

    .grid-row > div {
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        min-height: 50px;
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
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        background: #f8f9fa;
    }

    .btn-action.view:hover {
        color: #3498db;
    }

    .btn-action.edit:hover {
        color: #f39c12;
    }

    .btn-action.email:hover {
        color: #27ae60;
    }

    .btn-action.delete:hover {
        color: #e74c3c;
    }

    /* Estados y tipos */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 80px;
    }

    .status-activo {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .status-inactivo {
        background: #ffebee;
        color: #c62828;
    }

    .client-type {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 100px;
    }

    .type-natural {
        background: #e3f2fd;
        color: #1565c0;
    }

    .type-jurídico {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .type-gubernamental {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .nombre-completo {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .nombre-completo strong {
        margin-bottom: 2px;
    }

    .nombre-completo small {
        color: #666;
        font-size: 0.8rem;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        color: #666;
        grid-column: 1 / -1;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
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

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
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
        background: #95a5a6;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .grid-header, .grid-row {
            grid-template-columns: 1fr 1fr 1.2fr 1fr 1fr 1fr 1fr;
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
        
        .grid-row > div {
            justify-content: flex-start;
            text-align: left;
            min-height: auto;
            padding: 0.25rem 0;
        }
        
        .grid-row > div::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 0.5rem;
            color: #333;
            min-width: 100px;
        }
        
        .actions {
            justify-content: flex-start;
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
            grid-template-columns: 1fr;
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
        <h1>Gestión de Clientes</h1>
        <button class="btn-primary" id="openModal">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </button>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="filters-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Buscar clientes por nombre, documento, email...">
        </div>
        
        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label" for="tipoFilter">Tipo Cliente</label>
                <select class="filter-select" id="tipoFilter">
                    <option value="">Todos los tipos</option>
                    <option value="Natural">Natural</option>
                    <option value="Jurídico">Jurídico</option>
                    <option value="Gubernamental">Gubernamental</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="estadoFilter">Estado</label>
                <select class="filter-select" id="estadoFilter">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="ordenFilter">Ordenar por</label>
                <select class="filter-select" id="ordenFilter">
                    <option value="reciente">Más reciente</option>
                    <option value="antiguo">Más antiguo</option>
                    <option value="az">Nombre A-Z</option>
                    <option value="za">Nombre Z-A</option>
                    <option value="documento">Documento</option>
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

    <!-- Grid de Clientes -->
    <div class="grid-container">
        <div class="grid-header">
            <div>Documento</div>
            <div>Nombre Completo</div>
            <div>Email</div>
            <div>Celular</div>
            <div>Tipo</div>
            <div>Estado</div>
            <div>Acciones</div>
        </div>

        <div id="clientesContainer">
            <?php if (empty($clientes)): ?>
                <div class="no-results">
                    <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                    <p>No se encontraron clientes</p>
                    <p class="text-muted">Utilice el botón "Nuevo Cliente" para agregar el primer cliente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($clientes as $cliente): ?>
                    <div class="grid-row" data-id="<?php echo $cliente->id; ?>" data-nombre="<?php echo htmlspecialchars($cliente->nombre . ' ' . $cliente->apellidos); ?>" data-documento="<?php echo htmlspecialchars($cliente->documento); ?>" data-fecha="<?php echo $cliente->fecha_ingreso; ?>">
                        <div data-label="Documento">
                            <strong><?php echo htmlspecialchars($cliente->tipo_documento); ?></strong><br>
                            <small><?php echo htmlspecialchars($cliente->documento); ?></small>
                        </div>
                        <div data-label="Nombre" class="nombre-completo">
                            <strong><?php echo htmlspecialchars($cliente->nombre . ' ' . $cliente->apellidos); ?></strong>
                            <?php if (!empty($cliente->empresa)): ?>
                                <small><?php echo htmlspecialchars($cliente->empresa); ?></small>
                            <?php endif; ?>
                        </div>
                        <div data-label="Email"><?php echo htmlspecialchars($cliente->email); ?></div>
                        <div data-label="Celular"><?php echo htmlspecialchars($cliente->celular ?? 'N/A'); ?></div>
                        <div data-label="Tipo">
                            <span class="client-type type-<?php echo strtolower($cliente->tipo_cliente); ?>">
                                <?php echo $cliente->tipo_cliente; ?>
                            </span>
                        </div>
                        <div data-label="Estado">
                            <span class="status-badge status-<?php echo $cliente->estado; ?>">
                                <?php echo ucfirst($cliente->estado); ?>
                            </span>
                        </div>
                        <div data-label="Acciones" class="actions">
                            <button class="btn-action view" title="Ver Detalles" onclick="verCliente(<?php echo $cliente->id; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-action edit" title="Editar Cliente" onclick="editarCliente(<?php echo $cliente->id; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action email" title="Enviar Email" onclick="emailCliente(<?php echo $cliente->id; ?>)">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button class="btn-action delete" title="Eliminar Cliente" onclick="eliminarCliente(<?php echo $cliente->id; ?>)">
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

<!-- Modal para Agregar/Editar Cliente -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalTitle">Agregar Nuevo Cliente</h2>
            <button class="close-modal" id="closeModal">✕</button>
        </div>
        <div class="modal-body">
            <form id="clienteForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nombreCliente">Nombre *</label>
                        <input type="text" class="form-input" id="nombreCliente" name="nombre" placeholder="Ingrese el nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="apellidoCliente">Apellidos *</label>
                        <input type="text" class="form-input" id="apellidoCliente" name="apellido" placeholder="Ingrese los apellidos" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="tipoDocumento">Tipo de Documento *</label>
                        <select class="form-select" id="tipoDocumento" name="tipo_documento" required>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="NIT">NIT</option>
                            <option value="PASAPORTE">Pasaporte</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="documentoCliente">Número de Documento *</label>
                        <input type="text" class="form-input" id="documentoCliente" name="documento" placeholder="Ingrese el documento" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="celularCliente">Celular</label>
                        <input type="tel" class="form-input" id="celularCliente" name="celular" placeholder="Ingrese el celular">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="emailCliente">Email *</label>
                        <input type="email" class="form-input" id="emailCliente" name="email" placeholder="Ingrese el email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="direccionCliente">Dirección</label>
                    <textarea class="form-textarea" id="direccionCliente" name="direccion" placeholder="Ingrese la dirección completa"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="tipoCliente">Tipo de Cliente *</label>
                        <select class="form-select" id="tipoCliente" name="tipo_cliente" required>
                            <option value="Natural">Natural</option>
                            <option value="Jurídico">Jurídico</option>
                            <option value="Gubernamental">Gubernamental</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="empresaCliente">Empresa (opcional)</label>
                        <input type="text" class="form-input" id="empresaCliente" name="empresa" placeholder="Nombre de la empresa">
                    </div>
                </div>
                
                <div class="form-group" id="estadoField" style="display: none;">
                    <label class="form-label" for="estadoCliente">Estado</label>
                    <select class="form-select" id="estadoCliente" name="estado">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="notasCliente">Notas Adicionales</label>
                    <textarea class="form-textarea" id="notasCliente" name="notas_adicionales" placeholder="Agregue cualquier información adicional sobre el cliente"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="cancelModal">Cancelar</button>
            <button class="btn-success" id="saveClienteBtn" onclick="guardarCliente()">Guardar Cliente</button>
        </div>
    </div>
</div>

<script>
// Variables globales
let clienteActualId = null;
let isEditMode = false;

// Variables de paginación
let currentPage = 1;
let itemsPerPage = 10;
let allClientes = [];
let searchTimer = null;

// ==========================================
// SISTEMA DE FILTRADO Y ORDENAMIENTO EN TIEMPO REAL
// ==========================================

function inicializarFiltrosTiempoReal() {
    console.log('🔄 Inicializando filtros en tiempo real...');
    
    // Búsqueda en tiempo real con debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                aplicarFiltrosYOrdenamiento();
            }, 300);
        });
    }
    
    // Filtros en tiempo real
    document.getElementById('tipoFilter').addEventListener('change', aplicarFiltrosYOrdenamiento);
    document.getElementById('estadoFilter').addEventListener('change', aplicarFiltrosYOrdenamiento);
    document.getElementById('ordenFilter').addEventListener('change', aplicarFiltrosYOrdenamiento);
    
    // Items por página
    document.getElementById('itemsPerPage').addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        aplicarPaginacion();
        actualizarPaginadores();
    });
}

function aplicarFiltrosYOrdenamiento() {
    console.log('🎯 Aplicando filtros y ordenamiento...');
    
    const busqueda = document.getElementById('searchInput').value.toLowerCase();
    const tipo = document.getElementById('tipoFilter').value;
    const estado = document.getElementById('estadoFilter').value;
    const orden = document.getElementById('ordenFilter').value;

    console.log('🔍 Filtros activos:', { busqueda, tipo, estado, orden });

    // Obtener todas las filas de clientes
    const filas = Array.from(document.querySelectorAll('.grid-row[data-id]')).filter(row => 
        !row.classList.contains('no-results')
    );

    // Aplicar filtros
    let filasFiltradas = filas.filter(fila => {
        const nombre = fila.querySelector('.nombre-completo strong').textContent.toLowerCase();
        const documento = fila.querySelector('div[data-label="Documento"] small').textContent.toLowerCase();
        const email = fila.querySelector('div[data-label="Email"]').textContent.toLowerCase();
        const tipoCliente = fila.querySelector('.client-type').textContent;
        const estadoCliente = fila.querySelector('.status-badge').textContent.toLowerCase();

        // Aplicar filtros
        const coincideBusqueda = !busqueda || 
                               nombre.includes(busqueda) || 
                               documento.includes(busqueda) || 
                               email.includes(busqueda);
        const coincideTipo = !tipo || tipoCliente === tipo;
        const coincideEstado = !estado || estadoCliente === estado.toLowerCase();

        return coincideBusqueda && coincideTipo && coincideEstado;
    });

    console.log(`📊 Filas después de filtros: ${filasFiltradas.length} de ${filas.length}`);

    // Aplicar ordenamiento
    filasFiltradas = aplicarOrdenamiento(filasFiltradas, orden);

    // Reorganizar el DOM con el nuevo orden
    reorganizarFilasEnDOM(filasFiltradas);

    // Actualizar paginación
    allClientes = filasFiltradas;
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
                
                case 'documento':
                    return compararDocumentos(a, b, 'asc');
                
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
    const fechaTexto = fila.getAttribute('data-fecha');
    
    if (!fechaTexto) return new Date(0);
    
    try {
        return new Date(fechaTexto);
    } catch (error) {
        console.error('❌ Error parseando fecha:', fechaTexto, error);
        return new Date(0);
    }
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
    return fila.getAttribute('data-nombre') || '';
}

function compararDocumentos(filaA, filaB, orden = 'asc') {
    const docA = filaA.getAttribute('data-documento') || '';
    const docB = filaB.getAttribute('data-documento') || '';
    
    if (orden === 'asc') {
        return docA.localeCompare(docB);
    } else {
        return docB.localeCompare(docA);
    }
}

function reorganizarFilasEnDOM(filasOrdenadas) {
    const container = document.getElementById('clientesContainer');
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
    document.getElementById('tipoFilter').value = '';
    document.getElementById('estadoFilter').value = '';
    document.getElementById('ordenFilter').value = 'reciente';
    
    // Obtener todas las filas originales
    const filas = Array.from(document.querySelectorAll('.grid-row[data-id]')).filter(row => 
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
    allClientes = filasOrdenadas;
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
    
    // Obtener todos los clientes del DOM
    allClientes = Array.from(document.querySelectorAll('.grid-row[data-id]')).filter(row => {
        return !row.classList.contains('no-results');
    });
    
    console.log(`📊 Total de clientes encontrados: ${allClientes.length}`);
    
    // Aplicar paginación inicial
    aplicarPaginacion();
    actualizarPaginadores();
}

function aplicarPaginacion() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const totalClientes = allClientes.length;
    
    console.log(`📄 Página ${currentPage}: mostrando items ${startIndex + 1}-${Math.min(endIndex, totalClientes)} de ${totalClientes}`);
    
    // Ocultar todos los clientes primero
    allClientes.forEach((cliente, index) => {
        cliente.style.display = (index >= startIndex && index < endIndex) ? 'grid' : 'none';
    });
    
    // Actualizar información de página
    actualizarInfoPagina();
}

function actualizarInfoPagina() {
    const totalClientes = allClientes.length;
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, totalClientes);
    
    const infoText = totalClientes > 0 
        ? `Mostrando ${startIndex}-${endIndex} de ${totalClientes} clientes` 
        : 'No se encontraron clientes';
    
    document.getElementById('pageInfoTop').textContent = infoText;
    document.getElementById('pageInfoBottom').textContent = infoText;
}

function actualizarPaginadores() {
    const totalClientes = allClientes.length;
    const totalPages = Math.ceil(totalClientes / itemsPerPage);
    
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
    
    // Scroll suave hacia la parte superior de la tabla
    const tablaContainer = document.querySelector('.grid-container');
    if (tablaContainer) {
        tablaContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

// Funciones del Modal
function openModal() {
    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
    resetForm();
}

function resetForm() {
    document.getElementById('clienteForm').reset();
    document.getElementById('modalTitle').textContent = 'Agregar Nuevo Cliente';
    document.getElementById('saveClienteBtn').textContent = 'Guardar Cliente';
    document.getElementById('saveClienteBtn').onclick = guardarCliente;
    document.getElementById('estadoField').style.display = 'none';
    clienteActualId = null;
    isEditMode = false;
    document.getElementById('saveClienteBtn').disabled = false;
}

// FUNCIONES CRUD MEJORADAS
function verCliente(id) {
    console.log('Ver cliente:', id);
    
    // Obtener datos directamente de la fila de la tabla (más rápido y confiable)
    const row = document.querySelector(`.grid-row[data-id="${id}"]`);
    if (!row) {
        alert('❌ No se pudo encontrar la información del cliente');
        return;
    }

    const nombre = row.querySelector('.nombre-completo strong').textContent;
    const documento = row.querySelector('div[data-label="Documento"] small').textContent;
    const tipoDocumento = row.querySelector('div[data-label="Documento"] strong').textContent;
    const email = row.querySelector('div[data-label="Email"]').textContent;
    const celular = row.querySelector('div[data-label="Celular"]').textContent;
    const tipoCliente = row.querySelector('.client-type').textContent;
    const estado = row.querySelector('.status-badge').textContent;

    const info = `📋 **INFORMACIÓN DEL CLIENTE**

👤 **Nombre Completo:** ${nombre}
📄 **Documento:** ${tipoDocumento} - ${documento}
📧 **Email:** ${email}
📱 **Celular:** ${celular}
🏢 **Tipo Cliente:** ${tipoCliente}
✅ **Estado:** ${estado}`;
    
    alert(info);
}

function editarCliente(id) {
    console.log('Editar cliente:', id);
    
    // Obtener datos directamente de la fila
    const row = document.querySelector(`.grid-row[data-id="${id}"]`);
    if (!row) {
        alert('❌ No se pudo encontrar la información del cliente');
        return;
    }

    const nombreCompleto = row.querySelector('.nombre-completo strong').textContent;
    const [nombre, apellidos] = nombreCompleto.split(' ');
    const tipoDocumento = row.querySelector('div[data-label="Documento"] strong').textContent;
    const documento = row.querySelector('div[data-label="Documento"] small').textContent;
    const email = row.querySelector('div[data-label="Email"]').textContent;
    const celular = row.querySelector('div[data-label="Celular"]').textContent;
    const tipoCliente = row.querySelector('.client-type').textContent;
    const estado = row.querySelector('.status-badge').textContent.toLowerCase();

    // Llenar formulario con datos básicos
    document.getElementById('nombreCliente').value = nombre || '';
    document.getElementById('apellidoCliente').value = apellidos || '';
    document.getElementById('tipoDocumento').value = tipoDocumento || 'CC';
    document.getElementById('documentoCliente').value = documento || '';
    document.getElementById('celularCliente').value = celular !== 'N/A' ? celular : '';
    document.getElementById('emailCliente').value = email || '';
    document.getElementById('tipoCliente').value = tipoCliente || 'Natural';
    document.getElementById('estadoCliente').value = estado || 'activo';

    // Mostrar campo de estado
    document.getElementById('estadoField').style.display = 'block';
    
    // Configurar modal para edición
    document.getElementById('modalTitle').textContent = 'Editar Cliente';
    const btnGuardar = document.getElementById('saveClienteBtn');
    btnGuardar.textContent = 'Actualizar Cliente';
    btnGuardar.onclick = function() { actualizarCliente(id); };
    
    clienteActualId = id;
    
    // Abrir modal
    openModal();
}

function eliminarCliente(id) {
    console.log('Eliminar cliente:', id);
    
    if (!confirm('⚠️ ¿Está seguro de que desea eliminar este cliente?\n\nEsta acción no se puede deshacer.')) {
        return;
    }
    
    fetch(`/dashboard/eliminar-cliente/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al eliminar el cliente');
    });
}

function emailCliente(id) {
    console.log('Enviar email a cliente:', id);
    
    // Obtener email directamente de la tabla
    const row = document.querySelector(`.grid-row[data-id="${id}"]`);
    if (row) {
        const email = row.querySelector('div[data-label="Email"]').textContent;
        const nombre = row.querySelector('.nombre-completo strong').textContent.split(' ')[0];
        
        if (email && email !== 'N/A') {
            const asunto = encodeURIComponent('Información importante - Vigitecol');
            const cuerpo = encodeURIComponent(`Estimado/a ${nombre},\n\nEsperamos que se encuentre bien.\n\nSaludos cordiales,\nEquipo Vigitecol`);
            
            window.open(`mailto:${email}?subject=${asunto}&body=${cuerpo}`, '_blank');
        } else {
            alert('❌ El cliente no tiene email registrado');
        }
    } else {
        alert('❌ No se pudo encontrar la información del cliente');
    }
}

// Función para actualizar cliente
function actualizarCliente(id) {
    const datos = {
        nombre: document.getElementById('nombreCliente').value.trim(),
        apellido: document.getElementById('apellidoCliente').value.trim(),
        tipo_documento: document.getElementById('tipoDocumento').value,
        documento: document.getElementById('documentoCliente').value.trim(),
        celular: document.getElementById('celularCliente').value.trim(),
        email: document.getElementById('emailCliente').value.trim(),
        direccion: document.getElementById('direccionCliente').value.trim(),
        tipo_cliente: document.getElementById('tipoCliente').value,
        empresa: document.getElementById('empresaCliente').value.trim(),
        estado: document.getElementById('estadoCliente').value
    };

    // Validaciones
    if (!datos.nombre || !datos.documento || !datos.email) {
        alert('❌ Por favor complete los campos obligatorios: Nombre, Documento y Email');
        return;
    }

    const btnGuardar = document.getElementById('saveClienteBtn');
    const originalText = btnGuardar.textContent;
    btnGuardar.textContent = 'Actualizando...';
    btnGuardar.disabled = true;

    fetch(`/dashboard/editar-cliente/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            closeModal();
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al actualizar cliente');
    })
    .finally(() => {
        btnGuardar.textContent = originalText;
        btnGuardar.disabled = false;
    });
}

// Función para guardar nuevo cliente
function guardarCliente() {
    const datos = {
        nombre: document.getElementById('nombreCliente').value.trim(),
        apellido: document.getElementById('apellidoCliente').value.trim(),
        tipo_documento: document.getElementById('tipoDocumento').value,
        documento: document.getElementById('documentoCliente').value.trim(),
        celular: document.getElementById('celularCliente').value.trim(),
        email: document.getElementById('emailCliente').value.trim(),
        direccion: document.getElementById('direccionCliente').value.trim(),
        tipo_cliente: document.getElementById('tipoCliente').value,
        empresa: document.getElementById('empresaCliente').value.trim()
    };

    if (!datos.nombre || !datos.documento || !datos.email) {
        alert('❌ Por favor complete los campos obligatorios: Nombre, Documento y Email');
        return;
    }

    const btnGuardar = document.getElementById('saveClienteBtn');
    btnGuardar.textContent = 'Guardando...';
    btnGuardar.disabled = true;

    fetch('/dashboard/crear-cliente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            closeModal();
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al guardar el cliente');
    })
    .finally(() => {
        btnGuardar.textContent = 'Guardar Cliente';
        btnGuardar.disabled = false;
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Vista de Clientes cargada correctamente');
    
    document.getElementById('openModal').addEventListener('click', function() {
        resetForm();
        openModal();
    });

    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('cancelModal').addEventListener('click', closeModal);

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Inicializar filtros en tiempo real
    inicializarFiltrosTiempoReal();

    // Botón limpiar filtros
    document.getElementById('resetFilters').addEventListener('click', limpiarFiltros);

    // Inicializar paginación
    setTimeout(inicializarPaginacion, 500);
});

// Hacer funciones globales
window.recargarPaginacion = inicializarPaginacion;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>