<?php
header("Content-Type: application/json");
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id || !$action) {
    echo json_encode(["success" => false, "message" => "Faltan parámetros"]);
    exit;
}

try {
    // Determinamos el nuevo estado según la acción del botón en el admin
    $nuevoEstado = '';
    if ($action === 'aceptar') {
        $nuevoEstado = 'Confirmada'; // Equivale a app.put('/api/reservas/:id/aceptar')
    } else if ($action === 'pagar') {
        $nuevoEstado = 'Pagado';     // Equivale a app.put('/api/reservas/:id/pagar')
    }

    if ($nuevoEstado !== '') {
        $stmt = $pdo->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevoEstado, $id]);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Acción no reconocida"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}