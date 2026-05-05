<?php
require 'vendor/autoload.php';
require 'vendor/setasign/fpdf/fpdf.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

$fiesta = $_GET['fiesta'] ?? 'Evento Luna';
$imagen = $_GET['img'] ?? 'img/placeholder.jpg';
$codigo = $_GET['codigo'] ?? 'LUNA-ERROR';
$nombre = $_GET['nombre'] ?? 'Cliente';

$qrPath = 'temp_qr.png';

$result = Builder::create()
    ->writer(new PngWriter())
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

if (file_exists($imagen)) {
    $pdf->Image($imagen, 35, 35, 140);
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

$pdf->Output('D', 'entrada-la-luna.pdf');