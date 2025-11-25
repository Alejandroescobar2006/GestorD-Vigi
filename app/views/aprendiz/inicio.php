<?php
// [file name]: inicio.php
// Ubicación: app/views/aprendiz/inicio.php

$pageTitle = 'Inicio - Aprendiz - Vigitecol';
$currentSection = 'inicio';
ob_start();
?>


<style>
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    margin: 0;
    font-size: 2.5rem;
}

.dashboard-header p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-card:nth-child(1) .stat-icon { background: #e3f2fd; color: #1976d2; }
.stat-card:nth-child(2) .stat-icon { background: #f3e5f5; color: #7b1fa2; }
.stat-card:nth-child(3) .stat-icon { background: #e8f5e8; color: #388e3c; }
.stat-card:nth-child(4) .stat-icon { background: #fff3e0; color: #f57c00; }

.stat-info h3 {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    font-weight: 600;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.content-section {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.content-section h2 {
    margin-top: 0;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.activity-list {
    margin-top: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.activity-info h4 {
    margin: 0;
    color: #333;
}

.activity-info p {
    margin: 0.25rem 0 0 0;
    color: #666;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="dashboard-header">
    <h1>¡Bienvenida!</h1>
    <p>Panel de control para la gestion deaprendices en seguridad</p>
</div>

<div class="stats-grid">
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3>Documentos Revisados</h3>
            <span class="stat-number">12</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-info">
            <h3>Tareas Pendientes</h3>
            <span class="stat-number">3</span>
        </div>
    </div>
    
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>