<?php
// [file name]: cursos.php
// Ubicación: app/views/aprendiz/cursos.php

$pageTitle = 'Cursos - Aprendiz - Vigitecol';
$currentSection = 'cursos';
$customScript = '/js/aprendiz-cursos.js';
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
        text-align: center;
        margin-bottom: 3rem;
        width: 100%;
    }

    .section-header h1 {
        color: #333;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .section-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        width: 100%;
    }

    .course-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .course-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2980b9);
    }

    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #3498db;
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .course-header h3 {
        color: #333;
        font-size: 1.4rem;
        font-weight: 600;
        line-height: 1.3;
        flex: 1;
        margin-right: 1rem;
    }

    .course-status {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .course-status.completed {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .course-status.in-progress {
        background: #fff3e0;
        color: #ef6c00;
    }

    .course-status.pending {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .course-status.not-started {
        background: #e3f2fd;
        color: #1565c0;
    }

    .course-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .course-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #555;
        font-size: 0.9rem;
    }

    .meta-item i {
        color: #3498db;
        width: 16px;
    }

    .course-progress {
        margin-bottom: 1.5rem;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .progress-label {
        color: #555;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .progress-percentage {
        color: #3498db;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3498db, #2980b9);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .course-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: #3498db;
        color: white;
        flex: 1;
        justify-content: center;
    }

    .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: #3498db;
        border: 2px solid #3498db;
    }

    .btn-outline:hover {
        background: #3498db;
        color: white;
        transform: translateY(-2px);
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
        padding: 1rem;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal {
        transform: translateY(0);
    }

    .modal-header {
        padding: 2rem 2rem 1rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .modal-header-content {
        flex: 1;
    }

    .modal-header h2 {
        color: #333;
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .modal-status {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0.5rem;
        border-radius: 8px;
        transition: background-color 0.3s;
        margin-left: 1rem;
    }

    .close-modal:hover {
        background: #f0f0f0;
    }

    .modal-body {
        padding: 1.5rem 2rem 2rem;
    }

    .course-detail-section {
        margin-bottom: 2rem;
    }

    .course-detail-section h3 {
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-detail-section h3 i {
        color: #3498db;
    }

    .course-description-full {
        color: #666;
        line-height: 1.7;
        font-size: 1rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .detail-card {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #3498db;
    }

    .detail-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .detail-value {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .detail-value.price {
        color: #27ae60;
        font-size: 1.3rem;
    }

    .instructor-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .instructor-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #3498db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .instructor-details h4 {
        color: #333;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .instructor-details p {
        color: #666;
        font-size: 0.9rem;
    }

    .course-modules {
        margin-top: 1rem;
    }

    .module-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
    }

    .module-item:hover {
        background: #e9ecef;
    }

    .module-info h4 {
        color: #333;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .module-info p {
        color: #666;
        font-size: 0.85rem;
    }

    .module-status {
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .module-status.completed {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .module-status.in-progress {
        background: #fff3e0;
        color: #ef6c00;
    }

    .module-status.pending {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background: #219653;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding: 1rem;
        }

        .section-header h1 {
            font-size: 2rem;
        }

        .courses-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .course-card {
            padding: 1.5rem;
        }

        .course-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .course-header h3 {
            margin-right: 0;
        }

        .modal {
            width: 95%;
            margin: 1rem;
        }

        .modal-header {
            padding: 1.5rem 1.5rem 1rem;
        }

        .modal-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .course-actions {
            flex-direction: column;
        }

        .instructor-info {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .course-meta {
            flex-direction: column;
            gap: 0.75rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="main-content">
    <div class="section-header">
        <h1>Mis Cursos</h1>
    </div>

    <div class="courses-grid">
        <!-- Curso 1: Vigilante de Seguridad -->
        <div class="course-card" onclick="abrirModalCurso('vigilante')">
            <div class="course-header">
                <h3>Curso de Vigilante de Seguridad</h3>
            </div>
            <p class="course-description">Formación completa para vigilantes de seguridad privada con enfoque en normativa legal, técnicas de protección y manejo de situaciones de riesgo.</p>
            
            <div class="course-meta">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>180 Horas</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>3 Meses</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user-graduate"></i>
                    <span>Certificado</span>
                </div>
            </div>

            

            <div class="course-actions">
                <button class="btn btn-primary" onclick="event.stopPropagation(); abrirModalCurso('vigilante')">
                    <i class="fas fa-info-circle"></i> Ver Detalles
                </button>
            </div>
        </div>

        <!-- Curso 2: Escolta -->
        <div class="course-card" onclick="abrirModalCurso('escolta')">
            <div class="course-header">
                <h3>Curso de Escolta Privado</h3>
            </div>
            <p class="course-description">Especialización en protección personal y seguridad ejecutiva. Técnicas avanzadas de protección y seguridad corporativa.</p>
            
            <div class="course-meta">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>120 Horas</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>2 Meses</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Avanzado</span>
                </div>
            </div>


            <div class="course-actions">
                <button class="btn btn-primary" onclick="event.stopPropagation(); abrirModalCurso('escolta')">
                    <i class="fas fa-info-circle"></i> Ver Detalles
                </button>
            </div>
        </div>

        <!-- Curso 3: Control de Accesos -->
        <div class="course-card" onclick="abrirModalCurso('accesos')">
            <div class="course-header">
                <h3>Control de Accesos y CCTV</h3>
            </div>
            <p class="course-description">Manejo de sistemas de control de accesos, circuito cerrado de televisión y tecnologías de seguridad electrónica.</p>
            
            <div class="course-meta">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>80 Horas</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>1 Mes</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-camera"></i>
                    <span>Tecnológico</span>
                </div>
            </div>


            <div class="course-actions">
                <button class="btn btn-primary" onclick="event.stopPropagation(); abrirModalCurso('accesos')">
                    <i class="fas fa-info-circle"></i> Ver Detalles
                </button>
            </div>
        </div>

        <!-- Curso 4: Primeros Auxilios -->
        <div class="course-card" onclick="abrirModalCurso('primeros-auxilios')">
            <div class="course-header">
                <h3>Primeros Auxilios y Emergencias</h3>
            </div>
            <p class="course-description">Capacitación en atención prehospitalaria, RCP, manejo de emergencias médicas y protocolos de actuación en crisis.</p>
            
            <div class="course-meta">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>60 Horas</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>3 Semanas</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-first-aid"></i>
                    <span>Salud</span>
                </div>
            </div>


            <div class="course-actions">
                <button class="btn btn-primary" onclick="event.stopPropagation(); abrirModalCurso('primeros-auxilios')">
                    <i class="fas fa-info-circle"></i> Ver Detalles
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Curso de Vigilante -->
<div class="modal-overlay" id="modalVigilante">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-content">
                <h2>Curso de Vigilante de Seguridad</h2>
            </div>
            <button class="close-modal" onclick="cerrarModal('modalVigilante')">✕</button>
        </div>
        <div class="modal-body">
            <div class="course-detail-section">
                <h3><i class="fas fa-info-circle"></i> Descripción del Curso</h3>
                <p class="course-description-full">
                    Formación integral para profesionales de la seguridad privada. Este curso cubre todos los aspectos legales, técnicos y prácticos necesarios para ejercer como vigilante de seguridad certificado. Incluye formación en derechos y deberes, técnicas de intervención, manejo de equipos de seguridad y protocolos de actuación.
                </p>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-building"></i> Empresa Líder</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Empresa Certificadora</div>
                        <div class="detail-value">Seguridad Integral S.A.</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Años de Experiencia</div>
                        <div class="detail-value">15+ Años</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificación</div>
                        <div class="detail-value">Superintendencia de Vigilancia</div>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-tag"></i> Información del Curso</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Duración</div>
                        <div class="detail-value">180 Horas / 3 Meses</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Modalidad</div>
                        <div class="detail-value">Presencial/Virtual</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Precio</div>
                        <div class="detail-value price">$1.200.000 COP</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificado</div>
                        <div class="detail-value">Vigilante Nivel I</div>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-user-tie"></i> Instructor Principal</h3>
                <div class="instructor-info">
                    <div class="instructor-avatar">CM</div>
                    <div class="instructor-details">
                        <h4>Carlos Martínez</h4>
                        <p>Ex oficial de policía con 12 años de experiencia en seguridad privada y formación de personal.</p>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-list-ol"></i> Módulos del Curso</h3>
                <div class="course-modules">
                    <div class="module-item">
                        <div class="module-info">
                            <h4>Legislación y Normativa</h4>
                            <p>Marco legal de la seguridad privada</p>
                        </div>
                        <span class="module-status completed">Completado</span>
                    </div>
                    <div class="module-item">
                        <div class="module-info">
                            <h4>Técnicas de Intervención</h4>
                            <p>Protocolos de actuación y defensa personal</p>
                        </div>

                    </div>
                    <div class="module-item">
                        <div class="module-info">
                            <h4>Manejo de Equipos</h4>
                            <p>Equipos de seguridad y comunicación</p>
                        </div>
                        <span class="module-status pending">Pendiente</span>
                    </div>
                    <div class="module-item">
                        <div class="module-info">
                            <h4>Primeros Auxilios</h4>
                            <p>Atención prehospitalaria básica</p>
                        </div>
                        <span class="module-status pending">Pendiente</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="cerrarModal('modalVigilante')">Cerrar</button>
            <button class="btn btn-success">Continuar Curso</button>
        </div>
    </div>
</div>

<!-- Modal para Curso de Escolta -->
<div class="modal-overlay" id="modalEscolta">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-content">
                <h2>Curso de Escolta Privado</h2>
            </div>
            <button class="close-modal" onclick="cerrarModal('modalEscolta')">✕</button>
        </div>
        <div class="modal-body">
            <div class="course-detail-section">
                <h3><i class="fas fa-info-circle"></i> Descripción del Curso</h3>
                <p class="course-description-full">
                    Especialización avanzada en protección personal y seguridad ejecutiva. Diseñado para profesionales que buscan especializarse en la protección de personas de alto perfil, ejecutivos y personalidades. Incluye técnicas avanzadas de escolta, planificación de seguridad y manejo de situaciones de alto riesgo.
                </p>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-building"></i> Empresa Líder</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Empresa Certificadora</div>
                        <div class="detail-value">Protección Ejecutiva Ltda.</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Especialización</div>
                        <div class="detail-value">Seguridad Corporativa</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificación</div>
                        <div class="detail-value">Nivel Internacional</div>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-tag"></i> Información del Curso</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Duración</div>
                        <div class="detail-value">120 Horas / 2 Meses</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Modalidad</div>
                        <div class="detail-value">Intensivo Presencial</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Precio</div>
                        <div class="detail-value price">$2.500.000 COP</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificado</div>
                        <div class="detail-value">Escolta Profesional</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="cerrarModal('modalEscolta')">Cerrar</button>
            <button class="btn btn-success">Inscribirse</button>
        </div>
    </div>
</div>

<!-- Modal para Curso de Control de Accesos -->
<div class="modal-overlay" id="modalAccesos">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-content">
                <h2>Control de Accesos y CCTV</h2>
            </div>
            <button class="close-modal" onclick="cerrarModal('modalAccesos')">✕</button>
        </div>
        <div class="modal-body">
            <div class="course-detail-section">
                <h3><i class="fas fa-info-circle"></i> Descripción del Curso</h3>
                <p class="course-description-full">
                    Curso especializado en sistemas electrónicos de seguridad. Cubre el manejo de controles de acceso, sistemas de circuito cerrado de televisión (CCTV), alarmas y tecnologías modernas de vigilancia electrónica. Ideal para profesionales que trabajan en centros comerciales, empresas y edificios inteligentes.
                </p>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-building"></i> Empresa Líder</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Empresa Certificadora</div>
                        <div class="detail-value">TecnoSeguridad Colombia</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Especialización</div>
                        <div class="detail-value">Tecnología de Seguridad</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificación</div>
                        <div class="detail-value">Técnico en CCTV</div>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-tag"></i> Información del Curso</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Duración</div>
                        <div class="detail-value">80 Horas / 1 Mes</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Modalidad</div>
                        <div class="detail-value">Práctico Presencial</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Precio</div>
                        <div class="detail-value price">$800.000 COP</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificado</div>
                        <div class="detail-value">Técnico CCTV Nivel I</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="cerrarModal('modalAccesos')">Cerrar</button>
            <button class="btn btn-success">Descargar Certificado</button>
        </div>
    </div>
</div>

<!-- Modal para Curso de Primeros Auxilios -->
<div class="modal-overlay" id="modalPrimerosAuxilios">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-content">
                <h2>Primeros Auxilios y Emergencias</h2>
            </div>
            <button class="close-modal" onclick="cerrarModal('modalPrimerosAuxilios')">✕</button>
        </div>
        <div class="modal-body">
            <div class="course-detail-section">
                <h3><i class="fas fa-info-circle"></i> Descripción del Curso</h3>
                <p class="course-description-full">
                    Capacitación esencial en atención prehospitalaria y manejo de emergencias médicas. Este curso es fundamental para todo profesional de la seguridad, ya que proporciona las habilidades necesarias para responder efectivamente ante emergencias de salud hasta la llegada de personal médico especializado.
                </p>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-building"></i> Empresa Líder</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Empresa Certificadora</div>
                        <div class="detail-value">Cruz Roja Colombiana</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Años de Experiencia</div>
                        <div class="detail-value">100+ Años</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificación</div>
                        <div class="detail-value">Nacional e Internacional</div>
                    </div>
                </div>
            </div>

            <div class="course-detail-section">
                <h3><i class="fas fa-tag"></i> Información del Curso</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Duración</div>
                        <div class="detail-value">60 Horas / 3 Semanas</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Modalidad</div>
                        <div class="detail-value">Teórico-Práctico</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Precio</div>
                        <div class="detail-value price">$450.000 COP</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Certificado</div>
                        <div class="detail-value">Primeros Auxilios Avanzados</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="cerrarModal('modalPrimerosAuxilios')">Cerrar</button>
            <button class="btn btn-success">Continuar Curso</button>
        </div>
    </div>
</div>

<script>
// Función para abrir modales de cursos
function abrirModalCurso(tipoCurso) {
    const modales = {
        'vigilante': 'modalVigilante',
        'escolta': 'modalEscolta',
        'accesos': 'modalAccesos',
        'primeros-auxilios': 'modalPrimerosAuxilios'
    };

    const modalId = modales[tipoCurso];
    if (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
                console.log(`✅ Modal ${tipoCurso} abierto`);
            }, 10);
        }
    }
}

// Función para cerrar modales
function cerrarModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            console.log(`✅ Modal ${modalId} cerrado`);
        }, 300);
    }
}

// Cerrar modales al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Sistema de cursos cargado');
    
    const modales = document.querySelectorAll('.modal-overlay');
    modales.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                const modalId = modal.id;
                cerrarModal(modalId);
            }
        });
    });

    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modalesAbiertos = document.querySelectorAll('.modal-overlay.active');
            modalesAbiertos.forEach(modal => {
                cerrarModal(modal.id);
            });
        }
    });
});

// Hacer funciones globales
window.abrirModalCurso = abrirModalCurso;
window.cerrarModal = cerrarModal;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/aprendiz-main.php';
?>