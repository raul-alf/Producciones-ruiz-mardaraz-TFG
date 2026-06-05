<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    // Contar las reservas por estado
    $stmt = $pdo->query("SELECT estado, COUNT(*) AS total FROM reservas GROUP BY estado");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'data' => []
    ]);
}
