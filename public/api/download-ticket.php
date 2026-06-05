<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

try {
    require_once __DIR__ . '/../../core/Database.php';

    $pdo = Database::connect();

    // Obtener datos del POST
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['orderId'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Order ID no proporcionado'
        ]);
        exit;
    }

    $orderId = trim($input['orderId']);

    // Buscar la compra
    $stmt = $pdo->prepare("SELECT * FROM compras WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        echo json_encode([
            'success' => false,
            'message' => 'Compra no encontrada'
        ]);
        exit;
    }

    // Generar código QR usando servicio online (más confiable)
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($orderId);

    // Generar contenido HTML para la entrada
    $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Entrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #000;
        }
        .ticket {
            background: white;
            border: 3px solid #d4af37;
            border-radius: 10px;
            padding: 40px;
            max-width: 700px;
            margin: 20px auto;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #d4af37;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #000;
            font-size: 32px;
        }
        .header p {
            margin: 10px 0 0 0;
            color: #666;
            font-size: 16px;
        }
        .event-image {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #d4af37;
        }
        .content {
            margin: 30px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }
        .row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #333;
            flex: 1;
        }
        .value {
            text-align: right;
            color: #666;
            flex: 1;
        }
        .qr-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px 0;
            border-top: 3px solid #d4af37;
            border-bottom: 3px solid #d4af37;
        }
        .order-id {
            text-align: center;
            font-family: monospace;
            font-size: 20px;
            color: #d4af37;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
        .important {
            background: #fff3cd;
            border: 1px solid #d4af37;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        @media print {
            body {
                background: white;
            }
            .ticket {
                box-shadow: none;
                border-width: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>🎫 ENTRADA CONFIRMADA</h1>
            <p>Cafe Pub La Luna</p>
        </div>
        {IMAGEN_AQUI}
        <div class="content">
            <div class="row">
                <span class="label">Evento:</span>
                <span class="value" style="font-weight: bold;">{PRODUCTO}</span>
            </div>
            <div class="row">
                <span class="label">Email:</span>
                <span class="value">{EMAIL}</span>
            </div>
            <div class="row">
                <span class="label">Fecha del evento:</span>
                <span class="value">{FECHA_EVENTO}</span>
            </div>
            <div class="row">
                <span class="label">Precio:</span>
                <span class="value" style="color: #00c853; font-weight: bold; font-size: 18px;">{PRECIO} €</span>
            </div>
            <div class="row">
                <span class="label">Fecha de compra:</span>
                <span class="value">{FECHA_COMPRA}</span>
            </div>
        </div>
        <div class="important">
            ⚠️ PRESENTA ESTA ENTRADA EN LA PUERTA
        </div>
        <div class="qr-section">
            <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Código de referencia</p>
            <div class="order-id">{ORDER_ID}</div>
            <div style="text-align: center; margin: 20px 0;">
                <img src="{QR_URL}" alt="Código QR" style="width: 200px; height: 200px; border: 2px solid #d4af37; padding: 10px; background: white;">
            </div>
        </div>
        <div class="footer">
            <p>✓ Esta entrada es válida solo con el código de referencia mostrado arriba.</p>
            <p>✓ No se permiten copias digitales. Descarga nuevamente si necesitas otra copia.</p>
            <p>✓ Contacta con la administración si tienes dudas.</p>
            <p style="margin-top: 20px; font-size: 10px;">ID de compra: {ID_COMPRA} | Generado: {GENERADO}</p>
        </div>
    </div>
</body>
</html>
HTML;

    // Reemplazar placeholders con valores reales
    $html = str_replace('{PRODUCTO}', htmlspecialchars($compra['producto']), $html);
    $html = str_replace('{EMAIL}', htmlspecialchars($compra['email']), $html);
    $html = str_replace('{FECHA_EVENTO}', htmlspecialchars($compra['event_date']), $html);
    $html = str_replace('{PRECIO}', htmlspecialchars($compra['precio']), $html);
    $html = str_replace('{FECHA_COMPRA}', htmlspecialchars($compra['fecha']), $html);
    $html = str_replace('{ORDER_ID}', htmlspecialchars($orderId), $html);
    $html = str_replace('{QR_URL}', htmlspecialchars($qrUrl), $html);
    $html = str_replace('{ID_COMPRA}', htmlspecialchars($compra['id']), $html);
    $html = str_replace('{GENERADO}', htmlspecialchars($compra['created_at']), $html);
    
    // Reemplazar imagen si existe
    if (!empty($compra['imagen'])) {
        $imagen = '<img src="' . htmlspecialchars($compra['imagen']) . '" alt="Cartel del evento" class="event-image">';
        $html = str_replace('{IMAGEN_AQUI}', $imagen, $html);
    } else {
        $html = str_replace('{IMAGEN_AQUI}', '', $html);
    }

    echo json_encode([
        'success' => true,
        'html' => $html,
        'fileName' => 'entrada_' . $orderId . '.html'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

