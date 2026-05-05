<?php
header('Content-Type: application/json');

require_once '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$oculto = isset($_GET['oculto']) ? intval($_GET['oculto']) : 0;

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "ID de evento no válido"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE eventos SET oculto = :oculto WHERE id = :id");
    $stmt->execute([
        ':oculto' => $oculto,
        ':id' => $id
    ]);

    echo json_encode([
        "success" => true,
        "message" => $oculto ? "Evento ocultado" : "Evento mostrado"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al cambiar visibilidad",
        "error" => $e->getMessage()
    ]);
}