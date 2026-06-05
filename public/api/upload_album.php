<?php
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../models/Album.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $title = $_POST['title'] ?? '';
    $albumId = $_POST['albumId'] ?? '';
    $cover = $_FILES['cover'] ?? null;

    if (empty($title) || empty($albumId) || !$cover) {
        throw new Exception('Faltan datos para crear el álbum.');
    }

    if ($cover['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen de portada.');
    }

    $albumModel = new Album();
    $album = $albumModel->create($albumId, $title, $cover);

    echo json_encode([
        'success' => true,
        'message' => 'Álbum creado correctamente.',
        'album' => $album
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
