<?php
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../core/Config.php';
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Solicitud inválida: JSON no recibido.');
    }

    $orderID = trim($input['orderID'] ?? '');
    $producto = trim($input['fiesta'] ?? 'Evento Luna');
    $imagen = trim($input['imagen'] ?? '');
    $fecha_evento = trim($input['fecha'] ?? date('Y-m-d'));
    $precio = floatval($input['precio'] ?? 0);
    $email = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);

    if (empty($orderID) || $precio <= 0 || !$email) {
        throw new Exception('Faltan datos obligatorios o el correo no es válido.');
    }

    $db = Database::connect();

    $db->exec(
        "CREATE TABLE IF NOT EXISTS compras (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id VARCHAR(100) NOT NULL UNIQUE,
            producto VARCHAR(255) NOT NULL,
            precio DECIMAL(10,2) NOT NULL,
            fecha DATETIME NOT NULL,
            event_date DATE NOT NULL,
            email VARCHAR(255) NOT NULL,
            imagen VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Agregar columna event_date si no existe
    try {
        $existingColumn = $db->query("SHOW COLUMNS FROM compras LIKE 'event_date'");
        if ($existingColumn && $existingColumn->rowCount() === 0) {
            $db->exec("ALTER TABLE compras ADD COLUMN event_date DATE NOT NULL DEFAULT '1970-01-01'");
        }
    } catch (Exception $e) {
        // La columna ya existe o la versión de MySQL no soporta DEFAULT CURRENT_DATE.
        // Ignoramos el error para no interrumpir la compra.
    }

    $stmt = $db->prepare(
        "INSERT INTO compras (order_id, producto, precio, fecha, event_date, email, imagen)
         VALUES (:order_id, :producto, :precio, :fecha, :event_date, :email, :imagen)
         ON DUPLICATE KEY UPDATE producto = VALUES(producto), precio = VALUES(precio), email = VALUES(email), imagen = VALUES(imagen), event_date = VALUES(event_date)"
    );

    $stmt->execute([
        ':order_id' => $orderID,
        ':producto' => $producto,
        ':precio' => $precio,
        ':fecha' => date('Y-m-d H:i:s'),
        ':event_date' => $fecha_evento,
        ':email' => $email,
        ':imagen' => $imagen
    ]);

    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . BASE_URL;

    $ticketUrl = $baseUrl . 'ticket?order_id=' . urlencode($orderID);
    $imageUrl = $imagen ? $baseUrl . ltrim($imagen, '/') : '';
    $qrTempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'luna_ticket_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $orderID) . '.png';

    $qrResult = \Endroid\QrCode\Builder\Builder::create()
        ->writer(new \Endroid\QrCode\Writer\PngWriter())
        ->data($ticketUrl)
        ->size(300)
        ->margin(10)
        ->build();

    $qrResult->saveToFile($qrTempPath);

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.es';
    $mail->SMTPAuth = true;
    $mail->Username = 'mailer@rauljc.es';
    $mail->Password = 'RaUl23112006.';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('mailer@rauljc.es', 'Cafe Pub La Luna');
    $mail->addAddress($email);
    $mail->addReplyTo('mailer@rauljc.es', 'Cafe Pub La Luna');
    $mail->addEmbeddedImage($qrTempPath, 'qr_ticket', 'qr_ticket.png');

    $mail->isHTML(true);
    $mail->Subject = 'Tu entrada para Cafe Pub La Luna';
    $mail->Body = "<div style=\"font-family:Arial,sans-serif;color:#111;background:#fafafa;padding:20px;\">"
        . "<div style=\"max-width:600px;margin:0 auto;background:#111;color:#fff;padding:30px;border-radius:12px;\">"
        . "<h1 style=\"margin:0 0 20px;color:#d4af37;font-size:28px;text-align:center;\">Tu entrada está lista</h1>"
        . ($imageUrl ? "<div style=\"text-align:center;margin-bottom:20px;\"><img src=\"" . htmlspecialchars($imageUrl) . "\" alt=\"Ticket del evento\" style=\"max-width:100%;border-radius:10px;\"></div>" : "")
        . "<p style=\"font-size:16px;line-height:1.6;color:#ddd;\">Hola,</p>"
        . "<p style=\"font-size:16px;line-height:1.6;color:#ddd;\">Gracias por tu compra. Aquí tienes los datos de tu entrada para <strong>" . htmlspecialchars($producto) . "</strong>.</p>"
        . "<table style=\"width:100%;margin:20px 0 30px 0;border-collapse:collapse;\">"
        . "<tr><td style=\"padding:10px 0;color:#d4af37;width:160px;\">Referencia de pedido:</td><td style=\"padding:10px 0;color:#fff;\">" . htmlspecialchars($orderID) . "</td></tr>"
        . "<tr><td style=\"padding:10px 0;color:#d4af37;\">Evento:</td><td style=\"padding:10px 0;color:#fff;\">" . htmlspecialchars($producto) . "</td></tr>"
        . "<tr><td style=\"padding:10px 0;color:#d4af37;\">Precio:</td><td style=\"padding:10px 0;color:#fff;\">" . number_format($precio, 2) . " €</td></tr>"
        . "</table>"
        . "<div style=\"text-align:center;margin-bottom:30px;\">"
        . "<a href=\"" . htmlspecialchars($ticketUrl) . "\" style=\"display:inline-block;padding:15px 30px;background:#d4af37;color:#111;font-weight:bold;text-decoration:none;border-radius:8px;\">Ver tu ticket</a>"
        . "</div>"
        . "<div style=\"text-align:center;margin-bottom:30px;\">"
        . "<p style=\"margin:0 0 10px 0;color:#fff;font-size:16px;\">Escanea este código en acceso para presentar tu entrada:</p>"
        . "<img src=\"cid:qr_ticket\" alt=\"QR Ticket\" style=\"max-width:200px;border-radius:12px;background:#fff;padding:10px;\">"
        . "</div>"
        . "<p style=\"color:#bbb;font-size:14px;line-height:1.6;\">Presenta este correo en la entrada del evento o descarga tu ticket desde el enlace. Si tienes cualquier duda, responde a este email y te ayudamos.</p>"
        . "<p style=\"color:#bbb;font-size:14px;line-height:1.6;margin-top:30px;\">¡Nos vemos pronto en Cafe Pub La Luna!</p>"
        . "</div>"
        . "</div>";
    $mail->AltBody = "Hola,\n\nGracias por tu compra de " . $producto . ".\n\nReferencia de pedido: " . $orderID
        . "\nPrecio: " . number_format($precio, 2) . " €\n\nPuedes ver tu ticket en: " . $ticketUrl
        . "\n\nPresenta este correo en la entrada del evento.\n\n¡Nos vemos pronto en Cafe Pub La Luna!";

    $mailSent = false;
    $mailError = '';

    try {
        $mail->send();
        $mailSent = true;
    } catch (Exception $mailEx) {
        $mailError = $mailEx->getMessage();
    }

    if (file_exists($qrTempPath)) {
        unlink($qrTempPath);
    }

    $responseMessage = 'Compra registrada correctamente.';
    if ($mailSent) {
        $responseMessage = 'Compra registrada y correo enviado.';
    } else {
        $responseMessage .= ' No se pudo enviar el correo: ' . $mailError;
    }

    echo json_encode([
        'success' => true,
        'order_id' => $orderID,
        'ticket_url' => $ticketUrl,
        'message' => $responseMessage,
        'email_sent' => $mailSent
    ]);
} catch (Exception $e) {
    if (!empty($qrTempPath) && file_exists($qrTempPath)) {
        unlink($qrTempPath);
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar el pedido: ' . $e->getMessage()
    ]);
}
