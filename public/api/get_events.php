<?php
// api/get_events.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite peticiones desde el front

require_once __DIR__ . '/../../core/Database.php';
$pdo = Database::connect();

try {
    // Consultamos solo eventos visibles (no ocultos)
    $stmt = $pdo->query("SELECT * FROM events WHERE oculto = 0 ORDER BY date ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}