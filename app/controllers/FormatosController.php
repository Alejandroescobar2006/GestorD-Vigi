<?php
namespace App\Controllers;

use App\Models\FormatosModel;

class FormatosController extends BaseController
{
    private $formatosModel;

    public function __construct()
{
    $this->checkAuth();
    $this->formatosModel = new FormatosModel();
}

    public function index()
    {
        $esLina = ($_SESSION['user']['id'] == 12);
        $filtros = $this->getFiltros();

        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'formatos',
            'pageTitle' => 'Formatos - Vigitecol',
            'customScript' => '/js/formatos.js',
            'formatos' => $this->formatosModel->getFormatosPorUsuario($_SESSION['user']['id'], $filtros),
            'areas' => $this->formatosModel->getAreas(),
            'filtros' => $filtros,
            'esLina' => $esLina,
            'usuarios' => $esLina ? $this->formatosModel->getUsuariosParaCompartir() : []
        ];

        $this->view('dashboard/formatos', $data);
    }

    public function crear()
{
    header('Content-Type: application/json');
    
    if ($_SESSION['user']['id'] != 12) {
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para crear formatos']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    try {
        error_log("=== INICIO CREAR FORMATO ===");
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
        $nombre_formato = trim($_POST['nombre_formato'] ?? '');
        $area_id = intval($_POST['area_id'] ?? 0);

        if (empty($nombre_formato)) {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
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
        $nombreArchivo = basename($archivoInfo['name']); // Usar nombre original

        error_log("Archivo recibido. Tamaño: $tamanio bytes, Nombre: $nombreArchivo");

        // Obtener usuarios para compartir
        $usuariosCompartir = [];
        if (isset($_POST['usuarios_compartir']) && is_array($_POST['usuarios_compartir'])) {
            $usuariosCompartir = array_map('intval', $_POST['usuarios_compartir']);
            error_log("Usuarios para compartir: " . implode(', ', $usuariosCompartir));
        }

        // Obtener tipo de permisos
        $permisos = $_POST['permisos'] ?? 'lectura';

        // Datos CORREGIDOS
        $datos = [
            'nombre_formato' => $nombre_formato,
            'version' => trim($_POST['version'] ?? 'v1.0'),
            'area_id' => $area_id,
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'usuario_id' => $_SESSION['user']['id'],
            'nombre_archivo' => $nombreArchivo, // Cambiado de 'archivo' a 'nombre_archivo'
            'archivo_temporal' => $archivoInfo['tmp_name'], // Nuevo campo
            'usuarios_compartir' => $usuariosCompartir,
            'permisos' => $permisos
        ];

        // Guardar (sin pasar archivo binario)
        $resultado = $this->formatosModel->crearFormato($datos, $mimeType, $tamanio);
        
        if ($resultado) {
            error_log("✅ Formato creado con ID: $resultado");
            echo json_encode([
                'success' => true, 
                'message' => 'Formato creado correctamente',
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

            error_log("Previsualizando formato ID: $id");

            $formato = $this->formatosModel->obtenerArchivoBinario($id);
            
            if (!$formato) {
                error_log("❌ Formato no encontrado");
                http_response_code(404);
                die("Formato no encontrado");
            }

            if (!$formato->archivo_binario) {
                error_log("❌ Sin archivo binario");
                http_response_code(404);
                die("Archivo no disponible");
            }

            error_log("✅ Enviando archivo. Tipo: " . $formato->mime_type);

            header('Content-Type: ' . $formato->mime_type);
            header('Content-Disposition: inline; filename="' . $formato->archivo . '"');
            header('Content-Length: ' . strlen($formato->archivo_binario));
            header('Cache-Control: public, max-age=3600');
            
            echo $formato->archivo_binario;
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

            error_log("Descargando formato ID: $id");

            $formato = $this->formatosModel->obtenerArchivoBinario($id);
            
            if (!$formato || !$formato->archivo_binario) {
                http_response_code(404);
                die("Formato no encontrado");
            }

            error_log("✅ Descargando archivo: " . $formato->archivo);

            header('Content-Type: ' . $formato->mime_type);
            header('Content-Disposition: attachment; filename="' . $formato->archivo . '"');
            header('Content-Length: ' . strlen($formato->archivo_binario));
            header('Cache-Control: no-cache');
            
            echo $formato->archivo_binario;
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
        
        if ($_SESSION['user']['id'] != 12) {
            echo json_encode(['success' => false, 'message' => 'No tiene permisos para eliminar formatos']);
            exit;
        }

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

            error_log("Eliminando formato ID: $id");

            $formato = $this->formatosModel->obtenerFormato($id);
            
            if (!$formato) {
                echo json_encode(['success' => false, 'message' => 'Formato no encontrado']);
                exit;
            }

            $resultado = $this->formatosModel->eliminarFormato($id);
            
            if ($resultado) {
                error_log("✅ Formato eliminado");
                echo json_encode(['success' => true, 'message' => 'Formato eliminado']);
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
    // Configurar header JSON inmediatamente
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

        // Verificar permisos primero
        $formato = $this->formatosModel->obtenerFormato($id);
        if (!$formato) {
            echo json_encode(['success' => false, 'message' => 'Formato no encontrado']);
            exit;
        }
        
        $puedeEditar = ($_SESSION['user']['id'] == 12) || 
                       ($formato->fk_usuario_id == $_SESSION['user']['id']) ||
                       $this->formatosModel->tienePermisoFormato($id, $_SESSION['user']['id'], 'edicion');
        
        if (!$puedeEditar) {
            echo json_encode(['success' => false, 'message' => 'No tiene permisos para editar este formato']);
            exit;
        }

        // SIMPLIFICAR: Solo manejar FormData para evitar problemas de JSON
        $resultado = false;
        
        // Siempre usar FormData (tanto con archivo como sin archivo)
        $nombre_formato = trim($_POST['nombre_formato'] ?? '');
        $version = trim($_POST['version'] ?? 'v1.0');
        $area_id = intval($_POST['area_id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Validar campos obligatorios
        if (empty($nombre_formato) || empty($area_id)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y área son obligatorios']);
            exit;
        }

        // Si hay archivo, procesar con archivo
       if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
             $archivoInfo = $_FILES['archivo'];
            
            // Validaciones del archivo
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

            // Leer archivo
            $archivoBinario = file_get_contents($archivoInfo['tmp_name']);
            if ($archivoBinario === false) {
                echo json_encode(['success' => false, 'message' => 'Error al leer archivo']);
                exit;
            }

            $mimeType = $archivoInfo['type'];
            $tamanio = $archivoInfo['size'];
            $nombreArchivo = uniqid() . '_' . basename($archivoInfo['name']);

            $datos = [
                'nombre_formato' => $nombre_formato,
                'version' => $version,
                'area_id' => $area_id,
                'descripcion' => $descripcion,
                'archivo' => $nombreArchivo,
                'archivo_binario' => $archivoBinario,
                'mime_type' => $mimeType,
                'tamanio' => $tamanio
            ];

            $resultado = $this->formatosModel->editarFormatoConArchivo(
        $id, 
        [
            'nombre_formato' => $nombre_formato,
            'version' => $version,
            'area_id' => $area_id,
            'descripcion' => $descripcion
        ],
        $archivoInfo['tmp_name'],
        basename($archivoInfo['name']),
        $archivoInfo['type'],
        $archivoInfo['size']
    );
} else {
    // Actualización solo de metadatos
    $datosSanitizados = [
        'nombre_formato' => $nombre_formato,
        'version' => $version,
        'area_id' => $area_id,
        'descripcion' => $descripcion
    ];

    $resultado = $this->formatosModel->editarFormato($id, $datosSanitizados);
}

        // RESPUESTA
        if ($resultado) {
            // Intentar registrar modificación (pero no fallar si hay error)
            try {
                $this->registrarModificacionFormato($id, $formato->fk_usuario_id);
            } catch (\Exception $e) {
                // Ignorar errores de registro
            }
            
            echo json_encode([
    'success' => true, 
    'message' => 'Formato actualizado correctamente',
    'actualizado_por' => $_SESSION['user']['nombre'] . ' ' . ($_SESSION['user']['apellidos'] ?? $_SESSION['user']['apellido'] ?? '')
]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el formato']);
        }
        
    } catch (\Exception $e) {
        error_log('❌ ERROR editar formato: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
    }
    exit;
}



private function registrarModificacionFormato($formatoId, $propietarioId) {
    try {
        $editorId = $_SESSION['user']['id'];
        
        // Solo registrar si no es el propietario quien edita
        if ($editorId != $propietarioId) {
            $sql = "INSERT INTO formato_modificaciones (formato_id, usuario_editor_id, propietario_id, fecha_modificacion) 
                    VALUES (?, ?, ?, NOW())";
            
            // Usar el método query del modelo en lugar de acceder a db directamente
            $this->formatosModel->query($sql, [$formatoId, $editorId, $propietarioId]);
            
            error_log("📝 Modificación registrada - Formato: $formatoId, Editor: " . $_SESSION['user']['nombre'] . ' ' . ($_SESSION['user']['apellidos'] ?? $_SESSION['user']['apellido'] ?? ''));
        }
    } catch (\Exception $e) {
        // No fallar si hay error en el registro
        error_log("⚠️ No se pudo registrar modificación: " . $e->getMessage());
    }
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

        error_log("Obteniendo formato ID: $id");

        $formato = $this->formatosModel->obtenerFormato($id);
        
        if ($formato) {
            unset($formato->archivo_binario);
            
            // Obtener usuarios con los que se compartió
            $formato->usuarios_compartidos = $this->formatosModel->getUsuariosCompartidosFormato($id);
            
            error_log("✅ Formato obtenido");
            echo json_encode(['success' => true, 'formato' => $formato]);
        } else {
            error_log("❌ Formato no encontrado");
            echo json_encode(['success' => false, 'message' => 'Formato no encontrado']);
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

            // Verificar que el usuario es el propietario del formato
            $formato = $this->formatosModel->obtenerFormato($id);
            if (!$formato || $formato->fk_usuario_id != $_SESSION['user']['id']) {
                echo json_encode(['success' => false, 'message' => 'No tiene permisos para gestionar este formato']);
                exit;
            }

            $usuariosCompartir = $datos['usuarios_compartir'] ?? [];
            $tipoPermisos = $datos['permisos'] ?? 'lectura';

            $resultado = $this->formatosModel->actualizarPermisosFormato($id, $usuariosCompartir, $tipoPermisos);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Permisos actualizados correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar permisos']);
            }
        } catch (\Exception $e) {
            error_log('Error actualizar permisos formato: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
        exit;
    }

    private function getFiltros()
    {
        return [
            'busqueda' => $_GET['busqueda'] ?? '',
            'area' => $_GET['area'] ?? '',
            'version' => $_GET['version'] ?? ''
        ];
    }
}
?>