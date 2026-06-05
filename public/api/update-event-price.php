<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['eventId']) || !isset($input['precio'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        exit;
    }

    $eventId = intval($input['eventId']);
    $precio = floatval($input['precio']);

    if ($eventId <= 0 || $precio <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos inválidos'
        ]);
        exit;
    }

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

    $stmt = $pdo->prepare("UPDATE events SET precio_entrada = ? WHERE id = ?");
    $stmt->execute([$precio, $eventId]);

    echo json_encode([
        'success' => true,
        'message' => 'Precio del evento actualizado correctamente'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en servidor: ' . $e->getMessage()
    ]);
}
