<?php
header('Content-Type: application/json');

require_once '../db.php';

$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($eventId <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Evento no válido"
    ]);
    exit;
}

/*
    IMPORTANTE:
    Este código supone estas tablas:

    eventos:
    - id
    - title/status
    - entradas_totales
    - precio_entrada

    entradas:
    - id
    - event_id
    - estado
    - precio

    reservas:
    - id
    - evento_id
    - tipo
    - estado
*/

try {
    // Datos del evento
    $stmt = $conn->prepare("SELECT entradas_totales, precio_entrada FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        echo json_encode([
            "success" => false,
            "message" => "Evento no encontrado"
        ]);
        exit;
    }

    $entradasTotales = intval($evento['entradas_totales']);

    // Entradas vendidas
    $stmt = $conn->prepare("SELECT COUNT(*) FROM compras WHERE product_id = ? OR evento_id = ?");
    $stmt->execute([$eventId, $eventId]);
    $vendidas = intval($stmt->fetchColumn());

    // Dinero recaudado
    $stmt = $conn->prepare("SELECT SUM(precio) FROM compras WHERE product_id = ? OR evento_id = ?");
    $stmt->execute([$eventId, $eventId]);
    $recaudado = floatval($stmt->fetchColumn() ?? 0);

    // Reservas VIP
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reservas WHERE evento_id = ? AND tipo = 'VIP'");
    $stmt->execute([$eventId]);
    $reservasVip = intval($stmt->fetchColumn());

    $restantes = $entradasTotales - $vendidas;

    echo json_encode([
        "success" => true,
        "vendidas" => $vendidas,
        "restantes" => $restantes,
        "recaudado" => number_format($recaudado, 2, ',', '.'),
        "reservas_vip" => $reservasVip,
        "precio_entrada" => floatval($evento['precio_entrada'])
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en servidor",
        "error" => $e->getMessage()
    ]);
}