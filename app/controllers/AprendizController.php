<?php
// [file name]: AprendizController.php
namespace App\Controllers;

use App\Models\AprendicesModel;
use App\Models\DocumentosModel;
use App\Models\FormatosModel;

class AprendizController extends BaseController
{
    private $aprendicesModel;
    private $documentosModel;
    private $formatosModel;

    public function __construct()
    {
        $this->checkAuth();
        if ($_SESSION['user']['id'] != 28) {
            $this->redirect('/dashboard');
        }
        
        // Inicializar todos los modelos
        $this->aprendicesModel = new AprendicesModel();
        $this->documentosModel = new DocumentosModel();
        $this->formatosModel = new FormatosModel();
    }

    public function inicio()
    {
        error_log("=== ACCEDIENDO A APRENDIZ/INICIO ===");
        error_log("Usuario ID: " . ($_SESSION['user']['id'] ?? 'N/A'));
        
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'inicio',
            'pageTitle' => 'Inicio - Aprendiz - Vigitecol'
        ];
        
        $this->view('aprendiz/inicio', $data);
    }

    public function documentos()
    {
        // Verificar que el modelo esté inicializado
        if (!$this->documentosModel) {
            $this->documentosModel = new DocumentosModel();
        }
        
        $filtros = $this->getFiltros();
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'documentos',
            'pageTitle' => 'Documentos - Aprendiz - Vigitecol',
            'customScript' => '/js/documentos.js',
            'documentos' => $this->documentosModel->getDocumentos($filtros),
            'areas' => $this->documentosModel->getAreas(),
            'filtros' => $filtros,
            'usuarios' => $this->documentosModel->getUsuariosParaCompartir()
        ];
        $this->view('aprendiz/documentos', $data);
    }

    public function formatos()
    {
        // Verificar que el modelo esté inicializado
        if (!$this->formatosModel) {
            $this->formatosModel = new FormatosModel();
        }
        
        $filtros = $this->getFiltros();
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'formatos',
            'pageTitle' => 'Formatos - Aprendiz - Vigitecol',
            'customScript' => '/js/formatos.js',
            'formatos' => $this->formatosModel->getFormatosPorUsuario($_SESSION['user']['id'], $filtros),
            'areas' => $this->formatosModel->getAreas(),
            'filtros' => $filtros,
            'esLina' => false, // El aprendiz no es Lina
            'usuarios' => []
        ];
        $this->view('aprendiz/formatos', $data);
    }

    public function aprendices()
    {
        // Verificar que el modelo esté inicializado
        if (!$this->aprendicesModel) {
            $this->aprendicesModel = new AprendicesModel();
        }
        
        $filtros = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'aprendices',
            'pageTitle' => 'Aprendices - Vigitecol',
            'customScript' => '/js/aprendiz-aprendices.js',
            'aprendices' => $this->aprendicesModel->getAprendices($filtros),
            'filtros' => $filtros
        ];
        
        $this->view('aprendiz/aprendices', $data);
    }

    public function cursos()
    {
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'cursos',
            'pageTitle' => 'Cursos - Aprendiz - Vigitecol'
        ];
        $this->view('aprendiz/cursos', $data);
    }

    public function descargarCertificado($id = null)
    {
        // Verificar que el modelo esté inicializado
        if (!$this->aprendicesModel) {
            $this->aprendicesModel = new AprendicesModel();
        }
        
        // Si el ID viene por parámetro
        if ($id === null) {
            // Obtener el ID de la URL
            $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $id = end($urlParts);
        }

        if (!is_numeric($id)) {
            http_response_code(400);
            die("ID inválido");
        }

        $certificado = $this->aprendicesModel->obtenerCertificado($id);
        
        if (!$certificado || !$certificado->certificado_binario) {
            http_response_code(404);
            die("Certificado no encontrado");
        }

        header('Content-Type: ' . $certificado->mime_type);
        header('Content-Disposition: attachment; filename="' . $certificado->certificado . '"');
        header('Content-Length: ' . strlen($certificado->certificado_binario));
        header('Cache-Control: no-cache');
        
        echo $certificado->certificado_binario;
        exit;
    }

    private function getFiltros()
    {
        return [
            'busqueda' => $_GET['busqueda'] ?? '',
            'area' => $_GET['area'] ?? '',
            'tipo' => $_GET['tipo'] ?? ''
        ];
    }
public function crearAprendiz()
{
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    try {
        // Validar campos obligatorios
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $cedula = trim($_POST['cedula'] ?? '');

        if (empty($nombre) || empty($apellidos) || empty($cedula)) {
            echo json_encode(['success' => false, 'message' => 'Nombre, apellidos y cédula son obligatorios']);
            exit;
        }

        // Procesar archivo si existe
        $mimeType = null;
        $tamanio = null;
        $nombreCertificado = null;
        $certificadoTemporal = null;

        if (isset($_FILES['certificado_file']) && $_FILES['certificado_file']['error'] === UPLOAD_ERR_OK) {
            $archivoInfo = $_FILES['certificado_file'];
            
            // Validar que sea PDF
            $extension = strtolower(pathinfo($archivoInfo['name'], PATHINFO_EXTENSION));
            if ($extension !== 'pdf') {
                echo json_encode(['success' => false, 'message' => 'Solo se permiten archivos PDF']);
                exit;
            }

            // Validar tamaño (10MB máximo)
            if ($archivoInfo['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande (máx. 10MB)']);
                exit;
            }

            $mimeType = $archivoInfo['type'];
            $tamanio = $archivoInfo['size'];
            $nombreCertificado = basename($archivoInfo['name']); // Usar nombre original
            $certificadoTemporal = $archivoInfo['tmp_name'];
        }

        $datos = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
            'notas' => trim($_POST['notas'] ?? ''),
            'nombre_certificado' => $nombreCertificado,
            'certificado_temporal' => $certificadoTemporal
        ];

        // Guardar (sin pasar archivo binario)
        $resultado = $this->aprendicesModel->crearAprendiz($datos, $mimeType, $tamanio);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Aprendiz creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el aprendiz']);
        }

    } catch (\Exception $e) {
        error_log('Error crear aprendiz: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    }
    exit;
}

public function obtenerAprendiz($id = null)
{
    header('Content-Type: application/json');
    
    try {
        if ($id === null) {
            $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $id = end($urlParts);
        }

        if (!is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        $aprendiz = $this->aprendicesModel->obtenerAprendiz($id);
        
        if ($aprendiz) {
            // No enviar el binario del certificado
            unset($aprendiz->certificado_binario);
            echo json_encode(['success' => true, 'aprendiz' => $aprendiz]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aprendiz no encontrado']);
        }
    } catch (\Exception $e) {
        error_log('Error obtener aprendiz: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno']);
    }
    exit;
}

public function actualizarAprendiz($id = null)
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

        // Validar campos obligatorios
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $cedula = trim($_POST['cedula'] ?? '');

        if (empty($nombre) || empty($apellidos) || empty($cedula)) {
            echo json_encode(['success' => false, 'message' => 'Nombre, apellidos y cédula son obligatorios']);
            exit;
        }

        // Procesar archivo si existe
        $certificadoTemporal = null;
        $nombreCertificado = null;
        $mimeType = null;
        $tamanio = null;

        if (isset($_FILES['certificado_file']) && $_FILES['certificado_file']['error'] === UPLOAD_ERR_OK) {
            $archivoInfo = $_FILES['certificado_file'];
            
            // Validar que sea PDF
            $extension = strtolower(pathinfo($archivoInfo['name'], PATHINFO_EXTENSION));
            if ($extension !== 'pdf') {
                echo json_encode(['success' => false, 'message' => 'Solo se permiten archivos PDF']);
                exit;
            }

            // Validar tamaño (10MB máximo)
            if ($archivoInfo['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande (máx. 10MB)']);
                exit;
            }

            $certificadoTemporal = $archivoInfo['tmp_name'];
            $nombreCertificado = basename($archivoInfo['name']);
            $mimeType = $archivoInfo['type'];
            $tamanio = $archivoInfo['size'];
        }

        $datos = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
            'notas' => trim($_POST['notas'] ?? '')
        ];

        $resultado = $this->aprendicesModel->actualizarAprendiz(
            $id, 
            $datos, 
            $certificadoTemporal, 
            $nombreCertificado, 
            $mimeType, 
            $tamanio
        );
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Aprendiz actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el aprendiz']);
        }

    } catch (\Exception $e) {
        error_log('Error actualizar aprendiz: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    }
    exit;
}

public function eliminarAprendiz($id = null)
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

        $resultado = $this->aprendicesModel->eliminarAprendiz($id);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Aprendiz eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el aprendiz']);
        }

    } catch (\Exception $e) {
        error_log('Error eliminar aprendiz: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    }
    exit;
}
}
// <!-- Hola padre mio como estas? espero que muy bien señor primeramente agrdeerte por este nuevo dia por permitir 
// tener a nustras familias completas por cada dia pomer la comida en l mesa señor por tenerlos con vida padre 
// mio te pedimos perdon si en algun momento te hemos faltado al respeto padretepedimos perdon por cada uno de 
// nuestros pecados no somos personas perfectas pero estamos arrepentidos por ellos perdonasno tennos en tu misericordia 
// te pedimos que no ayudes a mejorar cada dia no permitas que caigamos e la tentacion cuidanos muestranos el camino de la luz señor
// guiano siempre cuida a nuestras familias sanalas protegelas de todo mal padre mio, ayudanos a cumplir nuestros sueños
// te lo pedimos en el nombre de tu hijo jesus amen. -->
?>
