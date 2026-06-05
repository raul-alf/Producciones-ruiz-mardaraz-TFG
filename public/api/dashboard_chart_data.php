<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    $stmt = $pdo->query("
        SELECT 
            id,
            order_id,
            producto,
            precio,
            fecha,
            email,
            imagen,
            created_at,
            event_date
        FROM compras
        ORDER BY created_at DESC
    ");

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $compras
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage(),
        'data' => []
    ]);
}