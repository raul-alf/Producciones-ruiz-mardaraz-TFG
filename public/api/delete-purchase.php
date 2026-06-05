<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    // Obtener datos del POST o JSON
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $input = null;
    }

    if (!$input) {
        $input = $_POST;
    }

    if (!isset($input['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        exit;
    }

    $id = intval($input['id']);

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID inválido'
        ]);
        exit;
    }

    // Verificar que la compra existe
    $stmt = $pdo->prepare("SELECT id FROM compras WHERE id = ?");
    $stmt->execute([$id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        echo json_encode([
            'success' => false,
            'message' => 'Compra no encontrada'
        ]);
        exit;
    }

    // Eliminar la compra
    $stmt = $pdo->prepare("DELETE FROM compras WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode([
        'success' => true,
        'message' => 'Compra eliminada correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en servidor: ' . $e->getMessage()
    ]);
}
