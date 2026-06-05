<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    // Obtener datos del POST
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['eventId']) || !isset($input['entradasTotales'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        exit;
    }

    $eventId = intval($input['eventId']);
    $entradasTotales = intval($input['entradasTotales']);

    if ($eventId <= 0 || $entradasTotales < 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos inválidos'
        ]);
        exit;
    }

    // Verificar que el evento existe
    $stmt = $pdo->prepare("SELECT id FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        echo json_encode([
            'success' => false,
            'message' => 'Evento no encontrado'
        ]);
        exit;
    }

    // Actualizar el número de entradas totales
    $stmt = $pdo->prepare("UPDATE events SET entradas_totales = ? WHERE id = ?");
    $stmt->execute([$entradasTotales, $eventId]);

    echo json_encode([
        'success' => true,
        'message' => 'Entradas totales actualizado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en servidor: ' . $e->getMessage()
    ]);
}

