<?php
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../core/Database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$oculto = isset($_GET['oculto']) ? intval($_GET['oculto']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID de evento no válido"
    ]);
    exit;
}

try {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("UPDATE events SET oculto = :oculto WHERE id = :id");
    $stmt->execute([
        ':oculto' => $oculto,
        ':id' => $id
    ]);

    echo json_encode([
        "success" => true,
        "message" => $oculto ? "Evento ocultado" : "Evento mostrado"
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al cambiar visibilidad",
        "error" => $e->getMessage()
    ]);
}
