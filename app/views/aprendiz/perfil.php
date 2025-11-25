<?php
// app/views/dashboard/perfil.php
$pageTitle = 'Mi Perfil - Vigitecol';
$currentSection = 'perfil';
$customScript = '/js/perfil.js';
ob_start();
?>

<div class="profile-container">
    <!-- Header del Perfil -->
    <div class="profile-header">
        <div class="profile-avatar-section">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
                <div class="avatar-overlay">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <div class="profile-basic-info">
                <h1 class="profile-name"><?php echo htmlspecialchars($user['nombre'] . ' ' . ($user['apellido'] ?? '')); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="profile-badges">
                    <span class="role-badge"><?php echo htmlspecialchars($userInfo->cargo_nombre ?? $user['tipo_usuario']); ?></span>
                    <span class="status-badge active">Activo</span>
                </div>
            </div>
        </div>
        <div class="profile-actions">
            <button class="btn-primary btn-edit" onclick="editarPerfil()">
                <i class="fas fa-edit"></i> Editar Perfil
            </button>
            <button class="btn-secondary" onclick="exportarDatos()">
                <i class="fas fa-download"></i> Exportar Datos
            </button>
        </div>
    </div>

    <div class="profile-content">
        <!-- Información Personal -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-user"></i>
                <h2>Información Personal</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-group">
                        <label class="info-label">Documento</label>
                        <div class="info-value">
                            <i class="fas fa-id-card"></i>
                            <span><?php echo htmlspecialchars($user['documento']); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Tipo de Documento</label>
                        <div class="info-value">
                            <i class="fas fa-file-alt"></i>
                            <span><?php echo htmlspecialchars($userInfo->tipo_documento ?? 'Cédula de Ciudadanía'); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Celular</label>
                        <div class="info-value">
                            <i class="fas fa-mobile-alt"></i>
                            <span><?php echo htmlspecialchars($userInfo->celular ?? 'No registrado'); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Dirección</label>
                        <div class="info-value">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars($userInfo->direccion ?? 'No registrada'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Laboral -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-briefcase"></i>
                <h2>Información Laboral</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-group">
                        <label class="info-label">Cargo</label>
                        <div class="info-value">
                            <i class="fas fa-user-tie"></i>
                            <span><?php echo htmlspecialchars($userInfo->cargo_nombre ?? 'No asignado'); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Área/Departamento</label>
                        <div class="info-value">
                            <i class="fas fa-building"></i>
                            <span><?php echo htmlspecialchars($userInfo->area_nombre ?? 'No asignada'); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Fecha de Ingreso</label>
                        <div class="info-value">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?php echo date('d/m/Y', strtotime($userInfo->fecha_registro ?? date('Y-m-d'))); ?></span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Estado</label>
                        <div class="info-value">
                            <i class="fas fa-circle"></i>
                            <span class="status-badge status-active"><?php echo htmlspecialchars($userInfo->estado ?? 'Activo'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i>
                <h2>Estadísticas de Actividad</h2>
            </div>
            <div class="card-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon documents">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $estadisticas['documentosSubidos'] ?? 0; ?></div>
                            <div class="stat-label">Documentos Subidos</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon formats">
                            <i class="fas fa-table"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $estadisticas['formatosCreados'] ?? 0; ?></div>
                            <div class="stat-label">Formatos Creados</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon clients">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $estadisticas['clientesRegistrados'] ?? 0; ?></div>
                            <div class="stat-label">Clientes Registrados</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon activity">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $estadisticas['actividadMensual'] ?? 0; ?></div>
                            <div class="stat-label">Actividad Este Mes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de Cuenta -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-cog"></i>
                <h2>Configuración de Cuenta</h2>
            </div>
            <div class="card-content">
                <div class="settings-grid">
                    <div class="setting-item">
                        <div class="setting-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="setting-content">
                            <h3>Cambiar Contraseña</h3>
                            <p>Actualiza tu contraseña regularmente para mantener la seguridad de tu cuenta</p>
                        </div>
                        <button class="btn-setting" onclick="cambiarPassword()">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="setting-content">
                            <h3>Notificaciones</h3>
                            <p>Configura cómo y cuándo recibir notificaciones del sistema</p>
                        </div>
                        <button class="btn-setting" onclick="configurarNotificaciones()">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="setting-content">
                            <h3>Privacidad y Seguridad</h3>
                            <p>Gestiona la privacidad de tus datos y configuraciones de seguridad</p>
                        </div>
                        <button class="btn-setting" onclick="gestionarPrivacidad()">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="setting-content">
                            <h3>Apariencia</h3>
                            <p>Personaliza el tema y la apariencia de la interfaz</p>
                        </div>
                        <button class="btn-setting" onclick="personalizarApariencia()">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos mejorados para el perfil */
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header del Perfil */
    .profile-header {
        background: #0095ebff;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .profile-avatar {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .profile-avatar i {
        font-size: 120px;
        color: rgba(255,255,255,0.9);
    }

    .avatar-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #3498db;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-overlay:hover {
        transform: scale(1.1);
        background: #2980b9;
    }

    .avatar-overlay i {
        font-size: 16px;
        color: white;
    }

    .profile-basic-info h1 {
        font-size: 2.2rem;
        margin: 0 0 8px 0;
        font-weight: 600;
    }

    .profile-email {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0 0 15px 0;
    }

    .profile-badges {
        display: flex;
        gap: 10px;
    }

    .role-badge, .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .role-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .status-badge.active {
        background: #27ae60;
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: white;
        color: #667eea;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-secondary {
        background: rgba(255,255,255,0.1);
        color: white;
        backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
        background: rgba(255,255,255,0.2);
    }

    /* Tarjetas de contenido */
    .profile-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .card-header {
        background: #f8f9fa;
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header i {
        color: #3498db;
        font-size: 1.2rem;
    }

    .card-header h2 {
        margin: 0;
        font-size: 1.3rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .card-content {
        padding: 25px;
    }

    /* Grid de información */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .info-value i {
        color: #3498db;
        width: 16px;
    }

    /* Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
    }

    .stat-icon.documents { background: #e74c3c; }
    .stat-icon.formats { background: #3498db; }
    .stat-icon.clients { background: #27ae60; }
    .stat-icon.activity { background: #f39c12; }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 4px;
    }

    /* Configuración */
    .settings-grid {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .setting-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .setting-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .setting-icon {
        width: 50px;
        height: 50px;
        background: #3498db;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .setting-content {
        flex: 1;
    }

    .setting-content h3 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .setting-content p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.4;
    }

    .btn-setting {
        background: none;
        border: none;
        color: #3498db;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-setting:hover {
        background: #3498db;
        color: white;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .profile-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            gap: 25px;
            text-align: center;
        }

        .profile-avatar-section {
            flex-direction: column;
            text-align: center;
        }

        .profile-actions {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .info-grid, .stats-grid {
            grid-template-columns: 1fr;
        }

        .profile-container {
            padding: 15px;
        }
    }
</style>

<script>
    console.log('🚀 perfil.js cargado');

// Esperar a que cargue el DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

function init() {
    console.log('✅ Inicializando sistema de perfil');
    
    // Inicializar modales
    inicializarModalEditarPerfil();
    inicializarModalCambiarPassword();
    inicializarModalExportarDatos();
}

// ==========================================
// MODAL EDITAR PERFIL
// ==========================================

function inicializarModalEditarPerfil() {
    // Crear modal de editar perfil si no existe
    if (!document.getElementById('modalEditarPerfil')) {
        const modalHTML = `
            <div class="modal-overlay" id="modalEditarPerfilOverlay" style="display: none;">
                <div class="modal" style="max-width: 600px;">
                    <div class="modal-header">
                        <h2>✏️ Editar Perfil</h2>
                        <button class="close-modal" onclick="cerrarModalEditarPerfil()">✕</button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarPerfil">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" class="form-input" id="editarNombre" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-input" id="editarApellidos" name="apellidos">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-input" id="editarEmail" name="email" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Tipo de Documento</label>
                                    <select class="form-select" id="editarTipoDocumento" name="tipo_documento">
                                        <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                                        <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                                        <option value="Pasaporte">Pasaporte</option>
                                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Celular</label>
                                    <input type="tel" class="form-input" id="editarCelular" name="celular" placeholder="+57 300 123 4567">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Dirección</label>
                                <textarea class="form-textarea" id="editarDireccion" name="direccion" placeholder="Ingresa tu dirección completa" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-secondary" onclick="cerrarModalEditarPerfil()">Cancelar</button>
                        <button class="btn-primary" onclick="guardarPerfil()">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Click fuera del modal para cerrar
        document.getElementById('modalEditarPerfilOverlay').onclick = function(e) {
            if (e.target === this) cerrarModalEditarPerfil();
        };
    }
}

function editarPerfil() {
    console.log('📝 Abriendo modal para editar perfil...');
    
    // Mostrar loading
    const btn = event.target;
    const htmlOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    btn.disabled = true;
    
    fetch('/dashboard/obtener-datos-edicion')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarDatosEnFormulario(data.userInfo);
                abrirModalEditarPerfil();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al cargar datos del perfil');
        })
        .finally(() => {
            btn.innerHTML = htmlOriginal;
            btn.disabled = false;
        });
}

function cargarDatosEnFormulario(userInfo) {
    document.getElementById('editarNombre').value = userInfo.nombre || '';
    document.getElementById('editarApellidos').value = userInfo.apellidos || '';
    document.getElementById('editarEmail').value = userInfo.email || '';
    document.getElementById('editarTipoDocumento').value = userInfo.tipo_documento || 'Cédula de Ciudadanía';
    document.getElementById('editarCelular').value = userInfo.celular || '';
    document.getElementById('editarDireccion').value = userInfo.direccion || '';
}

function abrirModalEditarPerfil() {
    const overlay = document.getElementById('modalEditarPerfilOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('active'), 10);
    }
}

function cerrarModalEditarPerfil() {
    const overlay = document.getElementById('modalEditarPerfilOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.style.display = 'none', 300);
    }
}

function guardarPerfil() {
    const form = document.getElementById('formEditarPerfil');
    const formData = new FormData(form);
    
    // Validaciones básicas
    const nombre = formData.get('nombre').trim();
    const email = formData.get('email').trim();
    
    if (!nombre || !email) {
        alert('❌ Nombre y email son obligatorios');
        return;
    }
    
    if (!isValidEmail(email)) {
        alert('❌ Por favor ingresa un email válido');
        return;
    }

    const btn = document.querySelector('#modalEditarPerfilOverlay .btn-primary');
    const textoOriginal = btn.textContent;
    btn.textContent = '⏳ Guardando...';
    btn.disabled = true;

    fetch('/dashboard/actualizar-perfil', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Perfil actualizado correctamente');
            cerrarModalEditarPerfil();
            location.reload(); // Recargar para ver cambios
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al actualizar el perfil');
    })
    .finally(() => {
        btn.textContent = textoOriginal;
        btn.disabled = false;
    });
}

// ==========================================
// MODAL CAMBIAR CONTRASEÑA
// ==========================================

function inicializarModalCambiarPassword() {
    if (!document.getElementById('modalCambiarPassword')) {
        const modalHTML = `
            <div class="modal-overlay" id="modalCambiarPasswordOverlay" style="display: none;">
                <div class="modal" style="max-width: 500px;">
                    <div class="modal-header">
                        <h2>🔐 Cambiar Contraseña</h2>
                        <button class="close-modal" onclick="cerrarModalCambiarPassword()">✕</button>
                    </div>
                    <div class="modal-body">
                        <form id="formCambiarPassword">
                            <div class="form-group">
                                <label class="form-label">Contraseña Actual *</label>
                                <input type="password" class="form-input" id="passwordActual" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nueva Contraseña *</label>
                                <input type="password" class="form-input" id="nuevaPassword" required>
                                <small class="form-help">Mínimo 6 caracteres</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirmar Nueva Contraseña *</label>
                                <input type="password" class="form-input" id="confirmarPassword" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-secondary" onclick="cerrarModalCambiarPassword()">Cancelar</button>
                        <button class="btn-primary" onclick="guardarNuevaPassword()">Cambiar Contraseña</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        document.getElementById('modalCambiarPasswordOverlay').onclick = function(e) {
            if (e.target === this) cerrarModalCambiarPassword();
        };
    }
}

function cambiarPassword() {
    // Limpiar formulario
    document.getElementById('formCambiarPassword').reset();
    abrirModalCambiarPassword();
}

function abrirModalCambiarPassword() {
    const overlay = document.getElementById('modalCambiarPasswordOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('active'), 10);
    }
}

function cerrarModalCambiarPassword() {
    const overlay = document.getElementById('modalCambiarPasswordOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.style.display = 'none', 300);
    }
}

function guardarNuevaPassword() {
    const passwordActual = document.getElementById('passwordActual').value;
    const nuevaPassword = document.getElementById('nuevaPassword').value;
    const confirmarPassword = document.getElementById('confirmarPassword').value;

    // Validaciones
    if (!passwordActual || !nuevaPassword || !confirmarPassword) {
        alert('❌ Todos los campos son obligatorios');
        return;
    }

    if (nuevaPassword.length < 6) {
        alert('❌ La nueva contraseña debe tener al menos 6 caracteres');
        return;
    }

    if (nuevaPassword !== confirmarPassword) {
        alert('❌ Las contraseñas nuevas no coinciden');
        return;
    }

    const btn = document.querySelector('#modalCambiarPasswordOverlay .btn-primary');
    const textoOriginal = btn.textContent;
    btn.textContent = '⏳ Cambiando...';
    btn.disabled = true;

    const datos = {
        password_actual: passwordActual,
        nueva_password: nuevaPassword,
        confirmar_password: confirmarPassword
    };

    fetch('/dashboard/cambiar-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Contraseña cambiada correctamente');
            cerrarModalCambiarPassword();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al cambiar la contraseña');
    })
    .finally(() => {
        btn.textContent = textoOriginal;
        btn.disabled = false;
    });
}

// ==========================================
// MODAL EXPORTAR DATOS
// ==========================================

function inicializarModalExportarDatos() {
    if (!document.getElementById('modalExportarDatos')) {
        const modalHTML = `
            <div class="modal-overlay" id="modalExportarDatosOverlay" style="display: none;">
                <div class="modal" style="max-width: 500px;">
                    <div class="modal-header">
                        <h2>📊 Exportar Mis Datos</h2>
                        <button class="close-modal" onclick="cerrarModalExportarDatos()">✕</button>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: center; padding: 20px;">
                            <i class="fas fa-file-export" style="font-size: 3rem; color: #3498db; margin-bottom: 20px;"></i>
                            <h3 style="margin-bottom: 10px;">Exportar Información Personal</h3>
                            <p style="color: #666; line-height: 1.5;">
                                Se generará un archivo JSON con toda tu información personal, 
                                datos laborales y estadísticas de actividad.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-secondary" onclick="cerrarModalExportarDatos()">Cancelar</button>
                        <button class="btn-primary" onclick="generarExportacion()">
                            <i class="fas fa-download"></i> Exportar Datos
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        document.getElementById('modalExportarDatosOverlay').onclick = function(e) {
            if (e.target === this) cerrarModalExportarDatos();
        };
    }
}

function exportarDatos() {
    abrirModalExportarDatos();
}

function abrirModalExportarDatos() {
    const overlay = document.getElementById('modalExportarDatosOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('active'), 10);
    }
}

function cerrarModalExportarDatos() {
    const overlay = document.getElementById('modalExportarDatosOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.style.display = 'none', 300);
    }
}

function generarExportacion() {
    const btn = document.querySelector('#modalExportarDatosOverlay .btn-primary');
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
    btn.disabled = true;

    fetch('/dashboard/exportar-datos')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                descargarJSON(data.datos, 'mis-datos-vigitecol.json');
                cerrarModalExportarDatos();
                alert('✅ Datos exportados correctamente');
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al exportar datos');
        })
        .finally(() => {
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        });
}

function descargarJSON(datos, filename) {
    const dataStr = JSON.stringify(datos, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// ==========================================
// FUNCIONES AUXILIARES
// ==========================================

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function configurarNotificaciones() {
    alert('🔔 Configuración de notificaciones en desarrollo');
}

function gestionarPrivacidad() {
    alert('🛡️ Gestión de privacidad en desarrollo');
}

function personalizarApariencia() {
    alert('🎨 Personalización de apariencia en desarrollo');
}

// ==========================================
// ESTILOS PARA MODALES
// ==========================================

const estilosModalesPerfil = `
    <style>
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-help {
            display: block;
            margin-top: 5px;
            font-size: 0.8rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
`;

// Agregar estilos al documento
document.head.insertAdjacentHTML('beforeend', estilosModalesPerfil);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>