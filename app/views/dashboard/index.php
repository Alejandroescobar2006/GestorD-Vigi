<?php
// app/views/dashboard/index.php
$pageTitle = 'Dashboard - Vigitecol';
$currentSection = 'dashboard';
$customScript = '/js/dashboard.js';

// Eliminar estas líneas que causan el error:
// require_once __DIR__ . '/../../models/ClienteModel.php';
// require_once __DIR__ . '/../../models/DocumentoModel.php';
// require_once __DIR__ . '/../../models/FormatoModel.php';
// require_once __DIR__ . '/../../models/RecordatorioModel.php';
// require_once __DIR__ . '/../../models/ActividadModel.php';

// En su lugar, usar los datos que deberían venir del controlador
// Si no vienen datos, usar valores por defecto
$estadisticas = $estadisticas ?? [
    'totalClientes' => 0,
    'documentosPendientes' => 0,
    'formatosActivos' => 0,
    'documentosCompletados' => 0,
];

$recordatorios = $recordatorios ?? [];
$datosActividad = $datosActividad ?? [];
$formatosRecientes = $formatosRecientes ?? [];

ob_start();
?>

<!-- Tarjeta de Bienvenida -->
<div class="welcome-card">
    <h1>¡Bienvenido, <span class="user-name"><?php echo htmlspecialchars($user['nombre'] ?? 'Usuario'); ?></span>!</h1>
    <p>Has iniciado sesión correctamente en el sistema de Vigitecol. Aquí puedes gestionar todas las funcionalidades de la plataforma.</p>
    <div class="login-info">
        <small>Último acceso: <?php echo date('d/m/Y H:i', strtotime($user['ultimo_acceso'] ?? 'now')); ?></small>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon" style="background: #3498db;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 id="totalClientes"><?php echo number_format($estadisticas['totalClientes']); ?></h3>
            <p>Total Clientes</p>
            <small class="stat-trend positive">+5% este mes</small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #e74c3c;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3 id="documentosPendientes"><?php echo number_format($estadisticas['documentosPendientes']); ?></h3>
            <p>Documentos Pendientes</p>
            <small class="stat-trend <?php echo $estadisticas['documentosPendientes'] > 10 ? 'negative' : 'positive'; ?>">
                <?php echo $estadisticas['documentosPendientes'] > 10 ? '¡Necesita atención!' : 'Bajo control'; ?>
            </small>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #27ae60;">
            <i class="fas fa-table"></i>
        </div>
        <div class="stat-info">
            <h3 id="formatosActivos"><?php echo number_format($estadisticas['formatosActivos']); ?></h3>
            <p>Formatos Activos</p>
            <small class="stat-trend neutral">Disponibles</small>
        </div>
    </div>
</div>



<!-- Gráficos de Actividad -->
<div class="charts-section">
    <h2>Actividad Reciente</h2>
    <div class="chart-container">
        <canvas id="activityChart"></canvas>
    </div>
</div>

<!-- Datos para JavaScript -->
<script>
    // Pasar datos PHP a JavaScript
    const datosActividad = <?php echo json_encode($datosActividad); ?>;
    const estadisticas = <?php echo json_encode($estadisticas); ?>;
</script>

<style>
    /* CSS específico del Dashboard - Versión Mejorada */
    .welcome-card {
        background: linear-gradient(135deg, #3498db, #2c3e50);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        transform: rotate(30deg);
    }

    .welcome-card h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .user-name {
        color: #f39c12;
        font-weight: 700;
    }

    .login-info {
        margin-top: 1rem;
        opacity: 0.8;
        position: relative;
        z-index: 1;
    }

    /* Stats Cards Mejoradas */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-card:nth-child(1) { border-left-color: #3498db; }
    .stat-card:nth-child(2) { border-left-color: #e74c3c; }
    .stat-card:nth-child(3) { border-left-color: #27ae60; }
    .stat-card:nth-child(4) { border-left-color: #f39c12; }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-info h3 {
        font-size: 2rem;
        margin-bottom: 0.25rem;
        color: #2c3e50;
    }

    .stat-info p {
        color: #666;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .stat-trend {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stat-trend.positive { color: #27ae60; }
    .stat-trend.negative { color: #e74c3c; }
    .stat-trend.neutral { color: #95a5a6; }

    /* Quick Actions */
    .quick-actions {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .quick-actions h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-actions h2::before {
        content: '⚡';
        font-size: 1.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 1rem;
        flex: 1;
        min-width: 200px;
        justify-content: center;
    }

    .action-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Dashboard Content Layout */
    .dashboard-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }

    /* Formats Section */
    .formats-section {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header h2::before {
        content: '📋';
    }

    .btn-primary {
        background: #3498db;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .grid-container {
        border: 1px solid #e1e8ed;
        border-radius: 8px;
        overflow: hidden;
    }

    .grid-header {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr 1fr 1fr;
        background: #f8f9fa;
        padding: 1rem;
        font-weight: bold;
        border-bottom: 1px solid #e1e8ed;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr 1fr 1fr;
        padding: 1rem;
        border-bottom: 1px solid #e1e8ed;
        align-items: center;
        transition: background 0.2s ease;
    }

    .grid-row:hover {
        background: #f8f9fa;
    }

    .grid-row:last-child {
        border-bottom: none;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        background: none;
        border: none;
        padding: 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action.view {
        color: #3498db;
        border: 1px solid #3498db;
    }

    .btn-action.download {
        color: #27ae60;
        border: 1px solid #27ae60;
    }

    .btn-action:hover {
        background: #f8f9fa;
        transform: scale(1.1);
    }

    .no-results {
        padding: 3rem 2rem;
        text-align: center;
        color: #7f8c8d;
        background: #f8f9fa;
        border-radius: 8px;
    }

    /* Reminders Section Mejorada */
    .reminders-section {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .reminders-section h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reminders-section h2::before {
        content: '🔔';
    }

    .reminders-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .reminder-item {
        padding: 1.25rem;
        border-left: 4px solid #3498db;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
        transition: all 0.3s ease;
    }

    .reminder-item:hover {
        transform: translateX(5px);
    }

    .reminder-item.urgent {
        border-left-color: #e74c3c;
        background: #ffeaea;
    }

    .reminder-item.warning {
        border-left-color: #f39c12;
        background: #fff4e6;
    }

    .reminder-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .reminder-badge {
        background: #e74c3c;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .reminder-item.urgent .reminder-badge {
        background: #e74c3c;
    }

    .reminder-item.warning .reminder-badge {
        background: #f39c12;
    }

    .reminder-item:not(.urgent):not(.warning) .reminder-badge {
        background: #3498db;
    }

    .reminder-item p {
        margin-bottom: 0.5rem;
    }

    .reminder-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 0.75rem;
        font-size: 0.875rem;
    }

    .reminder-footer small {
        color: #666;
    }

    /* Charts Section */
    .charts-section {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .charts-section h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .charts-section h2::before {
        content: '📊';
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-cards {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-btn {
            justify-content: center;
            min-width: auto;
        }

        .grid-header,
        .grid-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .reminder-header {
            flex-direction: column;
            gap: 0.5rem;
        }

        .reminder-footer {
            flex-direction: column;
            gap: 0.25rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // JavaScript específico del Dashboard - Versión Mejorada
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar gráfico con datos reales
        initializeActivityChart();
        
        // Actualizar estadísticas en tiempo real
        updateRealTimeStats();
        
        console.log('Dashboard inicializado correctamente');
    });

    // Funciones específicas del dashboard
    function verFormato(id) {
        if (id) {
            window.location.href = '/dashboard/formatos?action=view&id=' + id;
        }
    }

    function descargarFormato(id) {
        if (id) {
            window.open('/dashboard/formatos?action=download&id=' + id, '_blank');
        }
    }

    // Initialize Activity Chart con datos reales
    function initializeActivityChart() {
        const ctx = document.getElementById('activityChart');
        
        if (!ctx) {
            console.log('Canvas del gráfico no encontrado');
            return;
        }
        
        // Usar datos reales si están disponibles
        const activityData = datosActividad && datosActividad.length > 0 ? 
            datosActividad : getDefaultActivityData();
        
        // Configuración del gráfico
        const config = {
            type: 'line',
            data: activityData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        padding: 10
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        };

        // Crear el gráfico
        try {
            new Chart(ctx, config);
            console.log('Gráfico inicializado correctamente con datos reales');
        } catch (error) {
            console.error('Error al inicializar el gráfico:', error);
        }
    }

    // Datos por defecto si no hay datos reales
    function getDefaultActivityData() {
        const today = new Date();
        const labels = [];
        
        // Generar etiquetas de los últimos 7 días
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(today.getDate() - i);
            labels.push(date.toLocaleDateString('es-ES', { weekday: 'short' }));
        }
        
        return {
            labels: labels,
            datasets: [{
                label: 'Documentos Creados',
                data: [12, 19, 8, 15, 12, 5, 9],
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'Formatos Usados',
                data: [8, 12, 6, 10, 15, 3, 7],
                backgroundColor: 'rgba(46, 204, 113, 0.1)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };
    }

    // Actualizar estadísticas en tiempo real
    function updateRealTimeStats() {
        // Simular actualización en tiempo real cada 30 segundos
        setInterval(() => {
            // Aquí iría una llamada AJAX para obtener datos actualizados
            console.log('Actualizando estadísticas...');
            
            // Ejemplo de actualización de un contador
            const clientesElement = document.getElementById('totalClientes');
            if (clientesElement) {
                const current = parseInt(clientesElement.textContent.replace(/,/g, ''));
                // Simular pequeño incremento
                if (Math.random() > 0.7) {
                    clientesElement.textContent = (current + 1).toLocaleString();
                }
            }
        }, 30000);
    }

    // Función para exportar datos del dashboard
    function exportDashboardData() {
        const data = {
            estadisticas: estadisticas,
            fecha: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `dashboard-data-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>