<?php

class EntradaController extends Controller
{
    public function compra()
    {
        $eventId = intval($_GET['id'] ?? 0);
        $fiesta = $_GET['fiesta'] ?? 'Evento Luna';
        $imagen = $_GET['img'] ?? 'img/placeholder.jpg';
        $fecha = $_GET['date'] ?? date('Y-m-d');
        $precio = 15.00;

        if ($eventId > 0) {
            require_once __DIR__ . '/../core/Database.php';
            $pdo = Database::connect();

            $stmt = $pdo->prepare("SELECT title, status, date, image, precio_entrada FROM events WHERE id = ?");
            $stmt->execute([$eventId]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($event) {
                $fiesta = $event['title'] ?: ($event['status'] ?: $fiesta);
                $imagen = $event['image'] ?: $imagen;
                $fecha = $event['date'] ?: $fecha;
                $precio = floatval($event['precio_entrada'] ?: $precio);
            }
        }

        $this->view('entradas/compra', [
            'fiesta' => $fiesta,
            'imagen' => $imagen,
            'fecha' => $fecha,
            'precio' => $precio
        ]);
    }

public function entrada()
{
    $fiesta = $_GET['fiesta'] ?? 'Evento Luna';
    $imagen = $_GET['img'] ?? 'img/placeholder.jpg';
    $orderID = $_GET['orderID'] ?? '';
    $nombre = $_GET['nombre'] ?? 'Cliente';

    $codigoQR = 'LUNA-' . strtoupper(substr(md5($orderID . time()), 0, 12));

    $this->view('entradas/entrada', [
        'fiesta' => $fiesta,
        'imagen' => $imagen,
        'orderID' => $orderID,
        'nombre' => $nombre,
        'codigoQR' => $codigoQR
    ]);
}
public function generarPdf()
{
    require_once '../vendor/autoload.php';

    $fiesta = $_GET['fiesta'] ?? 'Evento Luna';
    $imagen = $_GET['img'] ?? 'img/placeholder.jpg';
    $codigo = $_GET['codigo'] ?? 'LUNA-ERROR';
    $nombre = $_GET['nombre'] ?? 'Cliente';

    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . rtrim(BASE_URL, '/');

    if (!preg_match('#^https?://#i', $imagen)) {
        $imagen = $baseUrl . '/' . ltrim($imagen, '/');
    }

    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($codigo);

    $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada Cafe Pub La Luna</title>
    <style>
        body { margin: 0; background: #0a0a0a; color: #fff; font-family: Arial, sans-serif; }
        .ticket { max-width: 700px; margin: 0 auto; background: #111; border: 3px solid #d4af37; border-radius: 16px; overflow: hidden; }
        .ticket-header { padding: 30px; text-align: center; background: #111; }
        .ticket-header h1 { margin: 0; font-size: 30px; color: #d4af37; }
        .ticket-header p { margin: 8px 0 0; color: #ccc; }
        .ticket-image { width: 100%; display: block; }
        .ticket-body { padding: 30px; }
        .ticket-body h2 { margin: 0 0 12px; color: #fff; }
        .ticket-detail { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #333; }
        .ticket-detail:last-child { border-bottom: none; }
        .ticket-detail strong { color: #d4af37; }
        .ticket-qr { text-align: center; padding: 30px; }
        .ticket-qr img { width: 240px; height: 240px; border: 4px solid #d4af37; border-radius: 18px; background: #fff; }
        .ticket-footer { padding: 20px 30px 40px; text-align: center; color: #ccc; font-size: 14px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>CAFE PUB LA LUNA</h1>
            <p>Entrada confirmada</p>
        </div>
        <img class="ticket-image" src="{$imagen}" alt="Cartel del evento">
        <div class="ticket-body">
            <h2>{$fiesta}</h2>
            <div class="ticket-detail"><span><strong>Nombre</strong></span><span>{$nombre}</span></div>
            <div class="ticket-detail"><span><strong>Código</strong></span><span>{$codigo}</span></div>
        </div>
        <div class="ticket-qr">
            <img src="{$qrUrl}" alt="Código QR">
        </div>
        <div class="ticket-footer">
            Presenta esta entrada en la puerta del evento. Gracias por tu compra.
        </div>
    </div>
</body>
</html>
HTML;

    header('Content-Type: text/html; charset=UTF-8');
    header('Content-Disposition: attachment; filename="entrada-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $codigo) . '.html"');
    echo $html;
    exit;
}

public function ticket()
    {
    require_once '../core/Database.php';

    $order_id = $_GET['order_id'] ?? '';

    if (empty($order_id)) {
        die("Falta el order_id");
    }

    $db = Database::connect();

    $stmt = $db->prepare("SELECT * FROM compras WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("Compra no encontrada");
    }

    $codigoQR = 'LUNA-' . strtoupper(substr(md5($order_id . $compra['fecha']), 0, 12));

    $this->view('entradas/ticket', [
        'fiesta' => $compra['producto'],
        'imagen' => $compra['imagen'] ?? 'img/placeholder.jpg',
        'orderID' => $order_id,
        'nombre' => $compra['email'] ?? 'Cliente',
        'codigoQR' => $codigoQR
    ]);
}
}