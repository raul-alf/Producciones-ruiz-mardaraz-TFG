<?php

require_once '../models/Reserva.php';

class ReservaController extends Controller
{
    private $reservaModel;

    public function __construct()
    {
        $this->reservaModel = new Reserva();
    }

    public function index()
    {
        $this->view('reservas/index');
    }
public function crearReserva()
{
    header('Content-Type: application/json');

    try {

        $nombre = $_POST['nombre'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $personas = $_POST['personas'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        $evento = $_POST['evento'] ?? '';

        if (!$nombre || !$fecha || !$telefono) {

            echo json_encode([
                'success' => false,
                'message' => 'Por favor, rellena todos los campos.'
            ]);

            return;
        }

        $reservaModel = new Reserva();

        $resultado = $reservaModel->crear(
            $nombre,
            $fecha,
            $personas,
            $telefono,
            $evento
        );

        echo json_encode([
            'success' => $resultado
        ]);

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ]);

    }
}

    public function getAll()
    {
        header('Content-Type: application/json');

        echo json_encode(
            $this->reservaModel->getAll()
        );
    }

    public function eliminar()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? 0;

        $resultado = $this->reservaModel->eliminar($id);

        echo json_encode([
            'success' => $resultado
        ]);
    }

    public function actualizarEstado()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';

        $resultado = $this->reservaModel->actualizarEstado(
            $id,
            $estado
        );

        echo json_encode([
            'success' => $resultado
        ]);
    }
    public function updateReserva()
{
    header('Content-Type: application/json');

    try {
        $id = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;

        if (!$id || !$action) {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan parámetros'
            ]);
            return;
        }

        $nuevoEstado = '';

        if ($action === 'aceptar') {
            $nuevoEstado = 'Confirmada';
        } elseif ($action === 'pagar') {
            $nuevoEstado = 'Pagado';
        }

        if ($nuevoEstado === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Acción no reconocida'
            ]);
            return;
        }

        require_once '../models/Reserva.php';

        $reservaModel = new Reserva();
        $resultado = $reservaModel->actualizarEstado($id, $nuevoEstado);

        echo json_encode([
            'success' => $resultado
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

}