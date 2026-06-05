<?php

class EntradaController extends Controller
{
    public function compra()
    {
        $fiesta = $_GET['fiesta'] ?? 'Evento Luna';
        $imagen = $_GET['img'] ?? 'img/placeholder.jpg';
        $fecha = $_GET['date'] ?? date('Y-m-d');
        $precio = 15.00;

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
    require_once '../vendor/setasign/fpdf/fpdf.php';

    $fiesta = $_GET['fiesta'] ?? 'Evento Luna';
    $imagen = $_GET['img'] ?? 'img/placeholder.jpg';
    $codigo = $_GET['codigo'] ?? 'LUNA-ERROR';
    $nombre = $_GET['nombre'] ?? 'Cliente';

    $qrPath = '../public/temp_qr.png';

    $result = \Endroid\QrCode\Builder\Builder::create()
        ->writer(new \Endroid\QrCode\Writer\PngWriter())
        ->data($codigo)
        ->size(300)
        ->margin(10)
        ->build();

    $result->saveToFile($qrPath);

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFillColor(0, 0, 0);
    $pdf->Rect(0, 0, 210, 297, 'F');

    $pdf->SetTextColor(212, 175, 55);
    $pdf->SetFont('Arial', 'B', 22);
    $pdf->Cell(0, 15, 'CAFE PUB LA LUNA', 0, 1, 'C');

    $pdf->Ln(5);

    $rutaImagen = '../public/' . ltrim($imagen, '/');

    if (file_exists($rutaImagen)) {
        $pdf->Image($rutaImagen, 35, 35, 140);
    }

    $pdf->Ln(145);

    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, utf8_decode($fiesta), 0, 1, 'C');

    $pdf->SetFont('Arial', '', 13);
    $pdf->Cell(0, 10, utf8_decode('Nombre: ' . $nombre), 0, 1, 'C');

    $pdf->SetTextColor(212, 175, 55);
    $pdf->Cell(0, 10, 'Codigo: ' . $codigo, 0, 1, 'C');

    $pdf->Image($qrPath, 75, 220, 60, 60);

    if (file_exists($qrPath)) {
        unlink($qrPath);
    }

    $pdf->Output('D', 'entrada-la-luna.pdf');
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