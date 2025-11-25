<?php
namespace App\Controllers;

use App\Models\DashboardModel;

class DashboardController extends BaseController
{
    private $dashboardModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'dashboard',
            'pageTitle' => 'Dashboard - Vigitecol',
            'estadisticas' => [
                'totalClientes' => $this->dashboardModel->getTotalClientes(),
                'documentosPendientes' => $this->dashboardModel->getDocumentosPendientes(),
                'formatosActivos' => $this->dashboardModel->getFormatosActivos()
            ],
            'formatos' => $this->dashboardModel->getFormatosRecientes(),
            'recordatorios' => $this->dashboardModel->getRecordatorios($_SESSION['user']['id']),
            'areas' => $this->dashboardModel->getAreas()
        ];

        $this->view('dashboard/index', $data);
    }

    private function getFiltros()
    {
        return [
            'busqueda' => $_GET['busqueda'] ?? '',
            'area' => $_GET['area'] ?? '',
            'version' => $_GET['version'] ?? '',
            'fecha' => $_GET['fecha'] ?? '',
            'tipo' => $_GET['tipo'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];
    }
}
?>