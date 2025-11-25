<?php
namespace App\Controllers;

use App\Models\CarpetasModel;
use App\Models\DocumentosModel;

class CarpetasController extends BaseController
{
    private $carpetasModel;
    private $documentosModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->carpetasModel = new CarpetasModel();
        $this->documentosModel = new DocumentosModel();
    }

    public function crear()
{
    header('Content-Type: application/json');
    
    // **TEMPORAL: Mostrar todos los errores**
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    try {
        error_log("=== INICIO CREAR CARPETA ===");
        
        // Log de la sesión
        error_log("Usuario en sesión: " . ($_SESSION['user']['id'] ?? 'NO HAY SESIÓN'));
        
        $input = file_get_contents('php://input');
        error_log("Input recibido: " . $input);
        
        $datos = json_decode($input, true);
        error_log("Datos decodificados: " . print_r($datos, true));
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error decodificando JSON: ' . json_last_error_msg());
        }

        $nombre = trim($datos['nombre'] ?? '');
        $areaId = $datos['area_id'] ?? null;
        $carpetaPadreId = $datos['carpeta_padre_id'] ?? null;
        $usuarioId = $_SESSION['user']['id'] ?? null;

        if (!$usuarioId) {
            throw new \Exception('Usuario no autenticado');
        }

        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre de la carpeta es obligatorio']);
            exit;
        }

        $datosCarpeta = [
            'nombre' => $nombre,
            'area_id' => $areaId,
            'carpeta_padre_id' => $carpetaPadreId,
            'usuario_id' => $usuarioId
        ];

        error_log("Datos para modelo: " . print_r($datosCarpeta, true));

        $resultado = $this->carpetasModel->crearCarpeta($datosCarpeta);
        
        if ($resultado) {
            error_log("✅ Carpeta creada con ID: " . $resultado);
            echo json_encode(['success' => true, 'message' => 'Carpeta creada correctamente', 'id' => $resultado]);
        } else {
            error_log("❌ Error en modelo al crear carpeta");
            echo json_encode(['success' => false, 'message' => 'Error al crear carpeta en el modelo']);
        }
        
    } catch (\Exception $e) {
        error_log('❌ EXCEPCIÓN crear carpeta: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
    }
    exit;
}

public function contenido($carpetaId = null)
{
    header('Content-Type: application/json');
    
    try {
        if ($carpetaId === 'null' || $carpetaId === '') {
            $carpetaId = null;
        }

        $usuarioId = $_SESSION['user']['id'] ?? null;
        
        // Obtener carpetas
        $carpetas = $this->carpetasModel->getCarpetasPorUsuario($usuarioId, $carpetaId);
        
        // Obtener documentos
        $filtros = ['carpeta_id' => $carpetaId];
        $documentos = $this->documentosModel->getDocumentos($filtros, $usuarioId);
        
        // Obtener ruta
        $ruta = [];
        if ($carpetaId) {
            $ruta = $this->carpetasModel->getRutaCompleta($carpetaId);
        }
        
        echo json_encode([
            'success' => true,
            'carpetas' => $carpetas,
            'documentos' => $documentos,
            'ruta' => $ruta
        ]);
        
    } catch (\Exception $e) {
        error_log('Error contenido carpetas: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    }
    exit;
}

    public function eliminar($id = null)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        try {
            if ($id === null) {
                $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                $id = end($urlParts);
            }

            if (!is_numeric($id)) {
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                exit;
            }

            // Verificar que el usuario es el propietario de la carpeta
            $carpeta = $this->carpetasModel->obtenerCarpeta($id);
            if (!$carpeta || $carpeta->fk_usuario_id != $_SESSION['user']['id']) {
                echo json_encode(['success' => false, 'message' => 'No tiene permisos para eliminar esta carpeta']);
                exit;
            }

            $resultado = $this->carpetasModel->eliminarCarpeta($id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Carpeta eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar carpeta. Asegúrese de que esté vacía.']);
            }
        } catch (\Exception $e) {
            error_log('Error eliminar carpeta: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
        exit;
    }
}
?>