<?php
namespace App\Controllers;

use App\Models\DocumentosModel;

class DocumentosController extends BaseController
{
    private $documentosModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->documentosModel = new DocumentosModel();
    }

    public function index()
    {
        $filtros = $this->getFiltros();
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'documentos',
            'pageTitle' => 'Documentos - Vigitecol',
            'customScript' => '/js/documentos.js',
            'documentos' => $this->documentosModel->getDocumentos($filtros),
            'areas' => $this->documentosModel->getAreas(),
            'filtros' => $filtros,
            'usuarios' => $this->documentosModel->getUsuariosParaCompartir()
        ];
        $this->view('dashboard/documentos', $data);
    }

    public function crear()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        try {
            error_log("=== INICIO CREAR DOCUMENTO ===");
            error_log("POST: " . print_r($_POST, true));
            error_log("FILES: " . print_r($_FILES, true));

            // Validar archivo
            if (!isset($_FILES['archivo'])) {
                echo json_encode(['success' => false, 'message' => 'No se recibió archivo']);
                exit;
            }

            if ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                $errores = [
                    UPLOAD_ERR_INI_SIZE => 'Archivo demasiado grande (php.ini)',
                    UPLOAD_ERR_FORM_SIZE => 'Archivo demasiado grande (formulario)',
                    UPLOAD_ERR_PARTIAL => 'Archivo subido parcialmente',
                    UPLOAD_ERR_NO_FILE => 'No se seleccionó archivo',
                    UPLOAD_ERR_NO_TMP_DIR => 'No hay carpeta temporal',
                    UPLOAD_ERR_CANT_WRITE => 'Error al escribir en disco',
                    UPLOAD_ERR_EXTENSION => 'Extensión bloqueada'
                ];
                $mensaje = $errores[$_FILES['archivo']['error']] ?? 'Error desconocido';
                echo json_encode(['success' => false, 'message' => $mensaje]);
                exit;
            }

            // Validar campos
            $nombre_doc = trim($_POST['nombre_doc'] ?? '');
            $tipo_doc = trim($_POST['tipo_doc'] ?? '');
            $area_id = intval($_POST['area_id'] ?? 0);

            if (empty($nombre_doc)) {
                echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
                exit;
            }

            if (empty($tipo_doc)) {
                echo json_encode(['success' => false, 'message' => 'El tipo es obligatorio']);
                exit;
            }

            if ($area_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Seleccione un área válida']);
                exit;
            }

            $archivoInfo = $_FILES['archivo'];
            
            // Validar tamaño
            if ($archivoInfo['size'] > 25 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'Archivo muy grande (máx 25MB)']);
                exit;
            }

            // Validar extensión
            $extension = strtolower(pathinfo($archivoInfo['name'], PATHINFO_EXTENSION));
            $permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip'];
            
            if (!in_array($extension, $permitidos)) {
                echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
                exit;
            }

            $mimeType = $archivoInfo['type'];
            $tamanio = $archivoInfo['size'];
            $nombreArchivo = basename($archivoInfo['name']);

            error_log("Archivo recibido. Tamaño: $tamanio bytes, Nombre: $nombreArchivo");

            // Obtener usuarios para compartir
            $usuariosCompartir = [];
            if (isset($_POST['usuarios_compartir']) && is_array($_POST['usuarios_compartir'])) {
                $usuariosCompartir = array_map('intval', $_POST['usuarios_compartir']);
            }

            // Obtener tipo de permisos
            $permisos = $_POST['permisos'] ?? 'lectura';

            // Datos para el modelo
            $datos = [
                'nombre_doc' => $nombre_doc,
                'tipo_doc' => $tipo_doc,
                'version' => trim($_POST['version'] ?? 'v1.0'),
                'area_id' => $area_id,
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'usuario_id' => $_SESSION['user']['id'] ?? 1,
                'nombre_archivo' => $nombreArchivo,
                'archivo_temporal' => $archivoInfo['tmp_name'],
                'usuarios_compartir' => $usuariosCompartir,
                'permisos' => $permisos
            ];

            // Guardar (el modelo ahora maneja el archivo en disco)
            $resultado = $this->documentosModel->crearDocumento($datos, $mimeType, $tamanio);
            
            if ($resultado) {
                error_log("✅ Documento creado con ID: $resultado");
                echo json_encode([
                    'success' => true, 
                    'message' => 'Documento creado correctamente',
                    'id' => $resultado
                ]);
            } else {
                error_log("❌ Error al insertar en BD");
                echo json_encode(['success' => false, 'message' => 'Error al guardar en base de datos']);
            }
            
        } catch (\Exception $e) {
            error_log("❌ EXCEPCIÓN: " . $e->getMessage());
            error_log($e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function previsualizar($id = null)
    {
        try {
            if ($id === null) {
                $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                $id = end($urlParts);
            }

            if (!is_numeric($id)) {
                http_response_code(400);
                die("ID inválido");
            }

            error_log("Previsualizando documento ID: $id");

            $documento = $this->documentosModel->obtenerArchivoBinario($id);
            
            if (!$documento) {
                error_log("❌ Documento no encontrado");
                http_response_code(404);
                die("Documento no encontrado");
            }

            if (!$documento->archivo_binario) {
                error_log("❌ Sin archivo binario");
                http_response_code(404);
                die("Archivo no disponible");
            }

            error_log("✅ Enviando archivo. Tipo: " . $documento->mime_type);

            header('Content-Type: ' . $documento->mime_type);
            header('Content-Disposition: inline; filename="' . $documento->archivo . '"');
            header('Content-Length: ' . strlen($documento->archivo_binario));
            header('Cache-Control: public, max-age=3600');
            
            echo $documento->archivo_binario;
            exit;
            
        } catch (\Exception $e) {
            error_log('Error previsualizar: ' . $e->getMessage());
            http_response_code(500);
            die("Error interno");
        }
    }

    public function descargar($id = null)
    {
        try {
            if ($id === null) {
                $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                $id = end($urlParts);
            }

            if (!is_numeric($id)) {
                http_response_code(400);
                die("ID inválido");
            }

            error_log("Descargando documento ID: $id");

            $documento = $this->documentosModel->obtenerArchivoBinario($id);
            
            if (!$documento || !$documento->archivo_binario) {
                http_response_code(404);
                die("Documento no encontrado");
            }

            error_log("✅ Descargando archivo: " . $documento->archivo);

            header('Content-Type: ' . $documento->mime_type);
            header('Content-Disposition: attachment; filename="' . $documento->archivo . '"');
            header('Content-Length: ' . strlen($documento->archivo_binario));
            header('Cache-Control: no-cache');
            
            echo $documento->archivo_binario;
            exit;
            
        } catch (\Exception $e) {
            error_log('Error descargar: ' . $e->getMessage());
            http_response_code(500);
            die("Error interno");
        }
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

            error_log("Eliminando documento ID: $id");

            $documento = $this->documentosModel->obtenerDocumento($id);
            
            if (!$documento) {
                echo json_encode(['success' => false, 'message' => 'Documento no encontrado']);
                exit;
            }

            // Verificar permisos: solo el propietario puede eliminar
            if ($documento->fk_usuario_id != $_SESSION['user']['id']) {
                echo json_encode(['success' => false, 'message' => 'Solo el propietario puede eliminar el documento']);
                exit;
            }

            $resultado = $this->documentosModel->eliminarDocumento($id);
            
            if ($resultado) {
                error_log("✅ Documento eliminado");
                echo json_encode(['success' => true, 'message' => 'Documento eliminado']);
            } else {
                error_log("❌ Error al eliminar");
                echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
            }
            
        } catch (\Exception $e) {
            error_log('Error eliminar: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
        exit;
    }

    public function editar($id = null)
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

            // Verificar si es FormData (con archivo) o JSON (solo metadatos)
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            
            if (strpos($contentType, 'multipart/form-data') !== false) {
                // Es FormData (con archivo)
                $datos = $_POST;
                $archivoInfo = $_FILES['archivo'] ?? null;
                
                if ($archivoInfo && $archivoInfo['error'] === UPLOAD_ERR_OK) {
                    // Validar archivo
                    if ($archivoInfo['size'] > 25 * 1024 * 1024) {
                        echo json_encode(['success' => false, 'message' => 'Archivo muy grande (máx 25MB)']);
                        exit;
                    }
                    
                    $extension = strtolower(pathinfo($archivoInfo['name'], PATHINFO_EXTENSION));
                    $permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip'];
                    
                    if (!in_array($extension, $permitidos)) {
                        echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
                        exit;
                    }
                }
            } else {
                // Es JSON (solo metadatos)
                $input = file_get_contents('php://input');
                $datos = json_decode($input, true);
                $archivoInfo = null;
            }
            
            if (!$datos || !is_numeric($id)) {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                exit;
            }

            // Verificar permisos
            $documento = $this->documentosModel->obtenerDocumento($id);
            if (!$documento) {
                echo json_encode(['success' => false, 'message' => 'Documento no encontrado']);
                exit;
            }

            $puedeEditar = ($documento->fk_usuario_id == $_SESSION['user']['id']) ||
                          $this->documentosModel->tienePermisoDocumento($id, $_SESSION['user']['id'], 'edicion');
            
            if (!$puedeEditar) {
                echo json_encode(['success' => false, 'message' => 'No tiene permisos para editar este documento']);
                exit;
            }

            if (empty($datos['nombre_doc']) || empty($datos['tipo_doc']) || empty($datos['area_id'])) {
                echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes']);
                exit;
            }

            error_log("Editando documento ID: $id");

            $datosSanitizados = [
                'nombre_doc' => trim($datos['nombre_doc']),
                'tipo_doc' => trim($datos['tipo_doc']),
                'version' => trim($datos['version'] ?? 'v1.0'),
                'area_id' => intval($datos['area_id']),
                'descripcion' => trim($datos['descripcion'] ?? '')
            ];

            // Preparar datos del archivo si existe
            $archivoTemporal = $archivoInfo ? $archivoInfo['tmp_name'] : null;
            $nombreArchivo = $archivoInfo ? basename($archivoInfo['name']) : null;
            $mimeType = $archivoInfo ? $archivoInfo['type'] : null;
            $tamanio = $archivoInfo ? $archivoInfo['size'] : null;

            $resultado = $this->documentosModel->editarDocumento(
                $id, 
                $datosSanitizados, 
                $archivoTemporal, 
                $nombreArchivo, 
                $mimeType, 
                $tamanio
            );
            
            if ($resultado) {
                error_log("✅ Documento actualizado");
                echo json_encode(['success' => true, 'message' => 'Documento actualizado']);
            } else {
                error_log("❌ Error al actualizar");
                echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
            }
        } catch (\Exception $e) {
            error_log('Error editar: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
        exit;
    }

    public function obtener($id = null)
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

            error_log("Obteniendo documento ID: $id");

            $documento = $this->documentosModel->obtenerDocumento($id);
            
            if ($documento) {
                // Obtener usuarios con los que se compartió
                $documento->usuarios_compartidos = $this->documentosModel->getUsuariosCompartidosDocumento($id);
                
                error_log("✅ Documento obtenido");
                echo json_encode(['success' => true, 'documento' => $documento]);
            } else {
                error_log("❌ Documento no encontrado");
                echo json_encode(['success' => false, 'message' => 'Documento no encontrado']);
            }
        } catch (\Exception $e) {
            error_log('Error obtener: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
        exit;
    }

    public function actualizarPermisos($id = null)
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

            $input = file_get_contents('php://input');
            $datos = json_decode($input, true);
            
            if (!$datos || !is_numeric($id)) {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                exit;
            }

            // Verificar que el usuario es el propietario del documento
            $documento = $this->documentosModel->obtenerDocumento($id);
            if (!$documento || $documento->fk_usuario_id != $_SESSION['user']['id']) {
                echo json_encode(['success' => false, 'message' => 'No tiene permisos para gestionar este documento']);
                exit;
            }

            $usuariosCompartir = $datos['usuarios_compartir'] ?? [];
            $tipoPermisos = $datos['permisos'] ?? 'lectura';

            $resultado = $this->documentosModel->actualizarPermisosDocumento($id, $usuariosCompartir, $tipoPermisos);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Permisos actualizados correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar permisos']);
            }
        } catch (\Exception $e) {
            error_log('Error actualizar permisos: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
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
}
?>