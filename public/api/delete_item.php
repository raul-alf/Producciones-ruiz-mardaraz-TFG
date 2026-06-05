<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../core/Database.php';
$pdo = Database::connect();

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null; // 'reserva', 'event' o 'album'

if (!$id || !$type) {
    echo json_encode(["success" => false, "message" => "ID o Tipo no especificado"]);
    exit;
}

try {
    if ($type === 'reserva') {
        $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->execute([$id]);
    } else if ($type === 'event') {
        // Borrar imagen física antes que el registro (como hacía server.js)
        $stmtImg = $pdo->prepare("SELECT image FROM events WHERE id = ?");
        $stmtImg->execute([$id]);
        $res = $stmtImg->fetch();
        if ($res && file_exists("../" . $res['image'])) unlink("../" . $res['image']);
        
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);
    } else if ($type === 'album') {
        require_once __DIR__ . '/../../models/Album.php';
        $albumModel = new Album();
        $success = $albumModel->delete($id);

        echo json_encode(["success" => $success]);
        exit;
    }
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}