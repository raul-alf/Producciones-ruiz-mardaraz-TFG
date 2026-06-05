<?php
// api/crear_reserva.php
header("Content-Type: application/json");
require_once __DIR__ . '/../../core/Database.php';
$pdo = Database::connect();

$nombre = $_POST['nombre'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$personas = $_POST['personas'] ?? null;
$telefono = $_POST['telefono'] ?? null;

if (!$nombre || !$fecha || !$telefono) {
    echo json_encode(["success" => false, "message" => "Por favor, rellena todos los campos."]);
    exit;
}

try {
    // Insertamos la reserva con estado 'Pendiente' por defecto
    $stmt = $pdo->prepare("INSERT INTO reservas (nombre, fecha, personas, telefono, estado) VALUES (?, ?, ?, ?, 'Pendiente')");
    $stmt->execute([$nombre, $fecha, $personas, $telefono]);
    
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}