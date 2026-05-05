<?php
// api/get_events.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite peticiones desde el front

require_once '../config/db.php';

try {
    // Consultamos todos los eventos
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}