<?php

require_once '../models/Eventos.php';
require_once '../models/Reserva.php';
require_once '../models/Album.php';

class AdminController extends Controller
{
    public function dashboard()
{
    if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    $this->view('admin/dashboard');
}

    public function getAll()
    {
        header('Content-Type: application/json');

        try {
            $eventoModel = new Evento();
            $reservaModel = new Reserva();
            $albumModel = new Album();

            echo json_encode([
                'events' => $eventoModel->getAll(),
                'reservas' => $reservaModel->getAll(),
                'albums' => $albumModel->getAll()
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'error' => true,
                'message' => $e->getMessage(),
                'events' => [],
                'reservas' => [],
                'albums' => []
            ]);
        }
    }

    public function getEventStats()
    {
        header('Content-Type: application/json');

        try {
            $eventId = $_GET['id'] ?? null;

            if (!$eventId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Event ID no proporcionado'
                ]);
                return;
            }

            $pdo = \Database::connect();

            // Asegurar que la columna event_date existe
            try {
                $pdo->exec("ALTER TABLE compras ADD COLUMN event_date DATE NULL");
            } catch (Exception $e) {
                // La columna ya existe, ignorar
            }

            // Obtener datos del evento (nombre del evento y fecha)
            $stmtEvent = $pdo->prepare("SELECT title, status, date, entradas_totales, precio_entrada FROM events WHERE id = ?");
            $stmtEvent->execute([$eventId]);
            $evento = $stmtEvent->fetch(\PDO::FETCH_ASSOC);

            if (!$evento) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Evento no encontrado'
                ]);
                return;
            }

            $nombreEvento = $evento['title'] ?? $evento['status'] ?? 'Evento Luna';
            $fechaEvento = substr($evento['date'] ?? date('Y-m-d'), 0, 10); // Asegurar formato YYYY-MM-DD
            $entradasTotales = intval($evento['entradas_totales'] ?? 0);

            // Contar compras para este evento en esta fecha
            $stmtVendidas = $pdo->prepare("
                SELECT COUNT(DISTINCT order_id) FROM compras 
                WHERE producto = ? AND (DATE(event_date) = ? OR event_date IS NULL)
            ");
            $stmtVendidas->execute([$nombreEvento, $fechaEvento]);
            $vendidas = intval($stmtVendidas->fetchColumn());

            // Contar reservas aceptadas para este evento en esta fecha
            $stmtReservasAceptadas = $pdo->prepare("
                SELECT COALESCE(SUM(CAST(personas AS UNSIGNED)), 0) FROM reservas
                WHERE evento = ? AND DATE(fecha) = ? AND estado = 'Aceptada'
            ");
            $stmtReservasAceptadas->execute([$nombreEvento, $fechaEvento]);
            $reservasAceptadas = intval($stmtReservasAceptadas->fetchColumn() ?? 0);

            // Total de entradas contabilizadas (compras + reservas aceptadas)
            $totalContabilizado = $vendidas + $reservasAceptadas;

            // Dinero recaudado (solo de compras, las reservas no generan pago)
            $stmtRecaudado = $pdo->prepare("
                SELECT SUM(CAST(precio AS DECIMAL(10,2))) FROM compras 
                WHERE producto = ? AND (DATE(event_date) = ? OR event_date IS NULL)
            ");
            $stmtRecaudado->execute([$nombreEvento, $fechaEvento]);
            $recaudado = floatval($stmtRecaudado->fetchColumn() ?? 0);

            $restantes = $entradasTotales - $totalContabilizado;

            // Obtener listado de compradores
            $stmtCompradores = $pdo->prepare("
                SELECT order_id, email, producto, precio, fecha, 'compra' as tipo
                FROM compras
                WHERE producto = ? AND (DATE(event_date) = ? OR event_date IS NULL)
                ORDER BY fecha DESC
                LIMIT 50
            ");
            $stmtCompradores->execute([$nombreEvento, $fechaEvento]);
            $compradores = $stmtCompradores->fetchAll(\PDO::FETCH_ASSOC);

            // Obtener listado de reservas aceptadas
            $stmtReservas = $pdo->prepare("
                SELECT id, nombre, telefono, personas, fecha, 'reserva' as tipo
                FROM reservas
                WHERE evento = ? AND DATE(fecha) = ? AND estado = 'Aceptada'
                ORDER BY fecha DESC
                LIMIT 50
            ");
            $stmtReservas->execute([$nombreEvento, $fechaEvento]);
            $reservasLista = $stmtReservas->fetchAll(\PDO::FETCH_ASSOC);

            // Combinar compradores y reservas
            $compradores = array_merge($compradores, $reservasLista);

            echo json_encode([
                'success' => true,
                'vendidas' => $totalContabilizado,
                'restantes' => max(0, $restantes),
                'recaudado' => number_format($recaudado, 2, ',', '.'),
                'reservas_vip' => 0,
                'compradores' => $compradores
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteItem()
    {
        header('Content-Type: application/json');

        try {
            $id = $_GET['id'] ?? null;
            $type = $_GET['type'] ?? null;

            if (!$id || !$type) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID o Tipo no especificado'
                ]);
                return;
            }

            if ($type === 'reserva') {
                $reservaModel = new Reserva();
                $resultado = $reservaModel->eliminar($id);

                echo json_encode([
                    'success' => $resultado
                ]);
                return;
            }

            if ($type === 'event') {
                $eventoModel = new Evento();

                $evento = $eventoModel->getById($id);

                if ($evento && !empty($evento['image'])) {
                    $rutaImagen = '../public/' . ltrim($evento['image'], '/');

                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }

                $resultado = $eventoModel->eliminar($id);

                echo json_encode([
                    'success' => $resultado
                ]);
                return;
            }

            if ($type === 'album') {
                $albumModel = new Album();
                $resultado = $albumModel->delete($id);

                echo json_encode([
                    'success' => $resultado
                ]);
                return;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Tipo no reconocido'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}