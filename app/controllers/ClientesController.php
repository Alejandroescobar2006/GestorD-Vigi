<?php
namespace App\Controllers;

use App\Models\ClientesModel;

class ClientesController extends BaseController
{
    private $clientesModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->clientesModel = new ClientesModel();
    }

    public function index()
    {
        $filtros = $this->getFiltros();
        $data = [
            'user' => $_SESSION['user'],
            'currentSection' => 'clientes',
            'pageTitle' => 'Clientes - Vigitecol',
            'customScript' => '/js/clientes.js',
            'clientes' => $this->clientesModel->getClientes($filtros),
            'filtros' => $filtros
        ];
        $this->view('dashboard/clientes', $data);
    }

    public function crear()
    {
        try {
            // Obtener datos del POST
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                echo json_encode(['success' => false, 'message' => 'Datos no recibidos']);
                return;
            }

            // Validaciones básicas
            if (empty($input['nombre']) || empty($input['documento']) || empty($input['email'])) {
                echo json_encode(['success' => false, 'message' => 'Nombre, documento y email son obligatorios']);
                return;
            }

            // Preparar datos para el modelo
            $datosCliente = [
                'nombre' => trim($input['nombre']),
                'apellido' => trim($input['apellido'] ?? ''),
                'tipo_documento' => trim($input['tipo_documento'] ?? 'CC'),
                'documento' => trim($input['documento']),
                'celular' => trim($input['celular'] ?? ''),
                'email' => trim($input['email']),
                'direccion' => trim($input['direccion'] ?? ''),
                'tipo_cliente' => trim($input['tipo_cliente'] ?? 'Natural'),
                'empresa' => trim($input['empresa'] ?? '')
            ];

            // Crear cliente
            $resultado = $this->clientesModel->crearCliente($datosCliente);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Cliente creado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el cliente']);
            }

        } catch (\Exception $e) {
            error_log('Error en crear cliente: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function editar($id)
    {
        try {
            // Obtener datos del POST
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                echo json_encode(['success' => false, 'message' => 'Datos no recibidos']);
                return;
            }

            // Validaciones básicas
            if (empty($input['nombre']) || empty($input['documento']) || empty($input['email'])) {
                echo json_encode(['success' => false, 'message' => 'Nombre, documento y email son obligatorios']);
                return;
            }

            // Preparar datos para el modelo
            $datosCliente = [
                'nombre' => trim($input['nombre']),
                'apellido' => trim($input['apellido'] ?? ''),
                'tipo_documento' => trim($input['tipo_documento'] ?? 'CC'),
                'documento' => trim($input['documento']),
                'celular' => trim($input['celular'] ?? ''),
                'email' => trim($input['email']),
                'direccion' => trim($input['direccion'] ?? ''),
                'tipo_cliente' => trim($input['tipo_cliente'] ?? 'Natural'),
                'empresa' => trim($input['empresa'] ?? ''),
                'estado' => trim($input['estado'] ?? 'activo')
            ];

            // Actualizar cliente
            $resultado = $this->clientesModel->editarCliente($id, $datosCliente);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Cliente actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el cliente']);
            }

        } catch (\Exception $e) {
            error_log('Error en editar cliente: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function eliminar($id)
    {
        try {
            $resultado = $this->clientesModel->eliminarCliente($id);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Cliente eliminado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el cliente']);
            }

        } catch (\Exception $e) {
            error_log('Error en eliminar cliente: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function obtener($id)
    {
        try {
            $cliente = $this->clientesModel->obtenerCliente($id);

            if ($cliente) {
                echo json_encode(['success' => true, 'cliente' => $cliente]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
            }

        } catch (\Exception $e) {
            error_log('Error en obtener cliente: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    private function getFiltros()
    {
        return [
            'busqueda' => $_GET['busqueda'] ?? '',
            'tipo' => $_GET['tipo'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];
    }
}
?>