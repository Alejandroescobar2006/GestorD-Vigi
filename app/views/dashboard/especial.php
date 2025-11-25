<?php
// app/views/dashboard/especial.php

// DEBUG
error_log("🎨 Iniciando vista especial.php");

$pageTitle = 'Dashboard Especial - Vigitecol';
$currentSection = 'dashboard';
$customScript = '/js/dashboard.js';

ob_start();
?>

<!-- Dashboard Especial para Cargo 28 -->
<div class="special-dashboard">
    <!-- Header Especial -->
    <div class="special-header">
        <h1>¡Bienvenido, <span class="user-name"><?php echo htmlspecialchars($user['nombre'] ?? 'Usuario'); ?></span>!</h1>
        <p>Panel de Control Especial - Sistema de Gestión Documental</p>
        <div class="special-badge">Usuario Especial</div>
        <?php if (isset($cargoInfo) && $cargoInfo): ?>
        <div class="cargo-info">
            <strong>Cargo:</strong> <?php echo htmlspecialchars($cargoInfo->nombre); ?>
            <?php if (!empty($cargoInfo->descripcion)): ?>
                <br><small><?php echo htmlspecialchars($cargoInfo->descripcion); ?></small>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Estadísticas Principales -->
    <div class="main-stats">
        <h2>Resumen General del Sistema</h2>
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticasEspeciales['usuariosActivos'] ?? 0; ?></h3>
                    <p>Usuarios Activos</p>
                    <small>En el sistema</small>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticasEspeciales['documentosRevision'] ?? 0; ?></h3>
                    <p>Documentos Totales</p>
                    <small>En el sistema</small>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-table"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticasEspeciales['formatosVencidos'] ?? 0; ?></h3>
                    <p>Formatos Activos</p>
                    <small>Disponibles</small>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticasEspeciales['auditoriasPendientes'] ?? 0; ?></h3>
                    <p>Auditorías Pendientes</p>
                    <small>Por revisar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Acciones Rápidas -->
    <div class="quick-actions-section">
        <h2>Acciones Rápidas</h2>
        <div class="actions-grid">
            <a href="/documentos" class="action-card">
                <div class="action-icon doc">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="action-content">
                    <h4>Gestionar Documentos</h4>
                    <p>Ver, crear y editar documentos del sistema</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <a href="/formatos" class="action-card">
                <div class="action-icon format">
                    <i class="fas fa-table"></i>
                </div>
                <div class="action-content">
                    <h4>Administrar Formatos</h4>
                    <p>Gestionar formatos y plantillas del sistema</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <a href="/aprendices" class="action-card">
                <div class="action-icon learn">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="action-content">
                    <h4>Gestión de Aprendices</h4>
                    <p>Administrar información de aprendices</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <div class="action-card" onclick="generarReporte()">
                <div class="action-icon report">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-content">
                    <h4>Reportes del Sistema</h4>
                    <p>Generar reportes y estadísticas</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas Especiales -->
    <?php if (!empty($alertasEspeciales)): ?>
    <div class="special-alerts">
        <h2>Alertas del Sistema</h2>
        <div class="alerts-container">
            <?php foreach ($alertasEspeciales as $alerta): ?>
            <div class="alert-item <?php echo $alerta->prioridad; ?>">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h4><?php echo htmlspecialchars($alerta->titulo); ?></h4>
                    <p><?php echo htmlspecialchars($alerta->descripcion); ?></p>
                    <small><?php echo date('d/m/Y H:i', strtotime($alerta->fecha_creacion)); ?></small>
                </div>
                <div class="alert-actions">
                    <button class="btn-action resolve" data-alerta-id="<?php echo $alerta->id; ?>">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Reportes Especiales -->
    <div class="special-reports">
        <h2>Reportes Recientes</h2>
        <div class="reports-grid">
            <?php if (!empty($reportesEspeciales)): ?>
                <?php foreach ($reportesEspeciales as $reporte): ?>
                <div class="report-card">
                    <div class="report-header">
                        <h4><?php echo htmlspecialchars($reporte->titulo); ?></h4>
                        <span class="report-badge <?php echo $reporte->tipo; ?>">
                            <?php echo ucfirst($reporte->tipo); ?>
                        </span>
                    </div>
                    <p><?php echo htmlspecialchars($reporte->descripcion); ?></p>
                    <div class="report-footer">
                        <small>Generado: <?php echo date('d/m/Y', strtotime($reporte->fecha_creacion)); ?></small>
                        <button class="btn-view-report" data-reporte-id="<?php echo $reporte->id; ?>">
                            Ver Detalles
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reports">
                    <p>No hay reportes especiales disponibles</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.special-dashboard {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.special-header {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    padding: 2.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.special-header h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.special-header p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 1rem;
}

.user-name {
    color: #f39c12;
    font-weight: 700;
}

.special-badge {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: #e74c3c;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: bold;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.cargo-info {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    display: inline-block;
    backdrop-filter: blur(10px);
}

.main-stats {
    margin-bottom: 3rem;
}

.main-stats h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 6px solid transparent;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-card.primary { border-left-color: #3498db; }
.stat-card.success { border-left-color: #27ae60; }
.stat-card.warning { border-left-color: #f39c12; }
.stat-card.danger { border-left-color: #e74c3c; }

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
}

.stat-card.primary .stat-icon { background: #3498db; }
.stat-card.success .stat-icon { background: #27ae60; }
.stat-card.warning .stat-icon { background: #f39c12; }
.stat-card.danger .stat-icon { background: #e74c3c; }

.stat-info h3 {
    font-size: 2.5rem;
    margin-bottom: 0.25rem;
    color: #2c3e50;
    font-weight: 700;
}

.stat-info p {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.stat-info small {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.quick-actions-section {
    margin-bottom: 3rem;
}

.quick-actions-section h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border: 2px solid transparent;
    cursor: pointer;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #3498db;
}

.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
}

.action-icon.doc { background: #e74c3c; }
.action-icon.format { background: #3498db; }
.action-icon.learn { background: #9b59b6; }
.action-icon.report { background: #27ae60; }

.action-content {
    flex: 1;
}

.action-content h4 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 600;
}

.action-content p {
    color: #7f8c8d;
    margin: 0;
    line-height: 1.5;
}

.action-arrow {
    color: #bdc3c7;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.action-card:hover .action-arrow {
    transform: translateX(5px);
    color: #3498db;
}

.special-alerts {
    margin-bottom: 3rem;
}

.special-alerts h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.alerts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.alert-item {
    display: flex;
    align-items: center;
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #e74c3c;
}

.alert-item.high { border-left-color: #e74c3c; }
.alert-item.medium { border-left-color: #f39c12; }
.alert-item.low { border-left-color: #3498db; }

.alert-icon {
    margin-right: 1rem;
    color: #e74c3c;
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content h4 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
}

.alert-content p {
    margin: 0;
    color: #666;
}

.special-reports {
    margin-bottom: 2rem;
}

.special-reports h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.report-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e1e8ed;
}

.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.report-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: bold;
}

.report-badge.auditoria { background: #ffeaa7; color: #e17055; }
.report-badge.revision { background: #a29bfe; color: white; }
.report-badge.sistema { background: #fd79a8; color: white; }

.no-reports {
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 8px;
    color: #666;
}

@media (max-width: 768px) {
    .special-dashboard {
        padding: 15px;
    }

    .special-header {
        padding: 1.5rem;
    }

    .special-header h1 {
        font-size: 2rem;
    }

    .stats-grid,
    .actions-grid,
    .alerts-container,
    .reports-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 1.5rem;
    }

    .action-card {
        padding: 1.5rem;
    }

    .special-badge {
        position: relative;
        top: auto;
        right: auto;
        margin-top: 1rem;
        display: inline-block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard especial cargado correctamente');
    
    // Configurar event listeners
    document.querySelectorAll('.btn-action.resolve').forEach(btn => {
        btn.addEventListener('click', function() {
            const alertaId = this.getAttribute('data-alerta-id');
            resolverAlerta(alertaId);
        });
    });

    document.querySelectorAll('.btn-view-report').forEach(btn => {
        btn.addEventListener('click', function() {
            const reporteId = this.getAttribute('data-reporte-id');
            verReporteDetallado(reporteId);
        });
    });
});

function generarReporte() {
    alert('Generando reporte completo del sistema...');
}

function resolverAlerta(alertaId) {
    if (confirm('¿Marcar esta alerta como resuelta?')) {
        console.log('Resolviendo alerta:', alertaId);
        alert('Alerta marcada como resuelta');
    }
}

function verReporteDetallado(reporteId) {
    console.log('Viendo reporte:', reporteId);
    alert('Abriendo reporte detallado ID: ' + reporteId);
}
</script>

<?php
$content = ob_get_clean();

// INCLUIR LAYOUT ESPECIAL
$layoutPath = __DIR__ . '/../layouts/special_layout.php';
if (file_exists($layoutPath)) {
    error_log("✅ Incluyendo layout especial");
    include $layoutPath;
} else {
    error_log("❌ Layout especial no encontrado, usando main.php");
    include __DIR__ . '/../layouts/main.php';
}
?>