<?php
namespace App\Controllers;

use App\Models\PerfilModel;

class PerfilController extends BaseController
{
    private $perfilModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->perfilModel = new PerfilModel();
    }

    public function index()
    {
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'perfil',
            'pageTitle' => 'Mi Perfil - Vigitecol',
            'userInfo' => $this->perfilModel->getUserInfo($_SESSION['user']['id']),
            'estadisticas' => $this->perfilModel->getEstadisticasUsuario($_SESSION['user']['id'])
        ];
        $this->view('dashboard/perfil', $data);
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = [
                    'nombre' => $this->sanitizeInput($_POST['nombre'] ?? ''),
                    'apellidos' => $this->sanitizeInput($_POST['apellidos'] ?? ''),
                    'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                    'celular' => $this->sanitizeInput($_POST['celular'] ?? '')
                ];

                $resultado = $this->perfilModel->actualizarPerfil($_SESSION['user']['id'], $datos);
                
                if ($resultado) {
                    $this->jsonResponse(['success' => true, 'message' => 'Perfil actualizado correctamente']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Error al actualizar perfil'], 500);
                }
            } catch (\Exception $e) {
                error_log('Error en actualizar perfil: ' . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error interno del servidor'], 500);
            }
        }
    }
}
?>