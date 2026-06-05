<?php

class EventoController extends Controller
{
    public function fechas()
    {
        $this->view('eventos/fechas');
    }

    public function getEvents()
    {
         header('Content-Type: application/json');

        try {
            require_once '../models/Eventos.php';

            $eventoModel = new Evento();
            $events = $eventoModel->getVisible();

            echo json_encode($events);

        } catch (Exception $e) {
            echo json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getStats($eventId)
{
    $stmt = $this->db->prepare("
        SELECT entradas_totales, precio_entrada 
        FROM events 
        WHERE id = ?
    ");
    $stmt->execute([$eventId]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        return null;
    }

    $entradasTotales = intval($evento['entradas_totales']);

    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM entradas 
        WHERE event_id = ? 
        AND estado = 'pagada'
    ");
    $stmt->execute([$eventId]);
    $vendidas = intval($stmt->fetchColumn());

    $stmt = $this->db->prepare("
        SELECT SUM(precio) 
        FROM entradas 
        WHERE event_id = ? 
        AND estado = 'pagada'
    ");
    $stmt->execute([$eventId]);
    $recaudado = floatval($stmt->fetchColumn());

    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM reservas 
        WHERE evento_id = ? 
        AND tipo = 'VIP'
    ");
    $stmt->execute([$eventId]);
    $reservasVip = intval($stmt->fetchColumn());

    return [
        'vendidas' => $vendidas,
        'restantes' => $entradasTotales - $vendidas,
        'recaudado' => number_format($recaudado, 2, ',', '.'),
        'reservas_vip' => $reservasVip
    ];
}
public function toggleVisibility()
{
    header('Content-Type: application/json');

    try {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $oculto = isset($_GET['oculto']) ? intval($_GET['oculto']) : 0;

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de evento no válido'
            ]);
            return;
        }

        require_once '../models/Eventos.php';

        $eventoModel = new Evento();
        $resultado = $eventoModel->cambiarVisibilidad($id, $oculto);

        echo json_encode([
            'success' => $resultado,
            'message' => $oculto ? 'Evento ocultado' : 'Evento mostrado'
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al cambiar visibilidad',
            'error' => $e->getMessage()
        ]);
    }
}
public function uploadEvent()
{
    header('Content-Type: application/json');

    try {
        if (!isset($_FILES['flyer'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No se ha enviado ningún cartel'
            ]);
            return;
        }

        $status = $_POST['status'] ?? '';
        $date = $_POST['date'] ?? '';

        if (empty($status) || empty($date)) {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan datos del evento'
            ]);
            return;
        }

        $name = time() . '_' . basename($_FILES['flyer']['name']);

        $folder = '../public/img/eventos/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $pathFisico = $folder . $name;

        if (!move_uploaded_file($_FILES['flyer']['tmp_name'], $pathFisico)) {
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo mover el archivo. Revisa permisos de public/img/eventos/'
            ]);
            return;
        }

        $pathDb = '/img/eventos/' . $name;

        require_once '../models/Eventos.php';

        $eventoModel = new Evento();
        $resultado = $eventoModel->crear($status, $date, $pathDb);

        echo json_encode([
            'success' => $resultado
        ]);

    } catch (Exception $e) {
        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
public function deleteEvent()
{
    header('Content-Type: application/json');

    try {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'Falta el ID del evento'
            ]);
            return;
        }

        $eventoModel = new Evento();
        $resultado = $eventoModel->eliminar($id);

        echo json_encode([
            'success' => $resultado
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

}
