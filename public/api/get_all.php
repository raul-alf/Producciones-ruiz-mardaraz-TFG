<?php
// api/get_all.php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1); // Esto te mostrará el error real en pantalla

try {
    require_once __DIR__ . '/../../core/Database.php';
    require_once __DIR__ . '/../../models/Album.php';
    $pdo = Database::connect();

    $res = [];
    
    // Consultar eventos (asegúrate de que la tabla existe)
    $stmt = $pdo->query("SELECT * FROM events");
    $res['events'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consultar reservas
    $stmt2 = $pdo->query("SELECT * FROM reservas");
    $res['reservas'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $albumModel = new Album();
    $res['albums'] = $albumModel->getAll();

    echo json_encode($res);

} catch (Exception $e) {
    // Si falla la DB, enviamos el error como JSON para que no de SyntaxError
    echo json_encode([
        "error" => true,
        "message" => "Error de base de datos: " . $e->getMessage(),
        "events" => [],
        "reservas" => [],
        "albums" => []
    ]);
}