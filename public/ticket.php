<?php
require_once 'tcpdf/tcpdf.php';
require_once 'phpqrcode/qrlib.php';
require_once 'db.php'; // tu conexión a la base de datos

$order_id = $_GET['order_id'];

// 1. Recuperar datos de la compra
$stmt = $db->prepare("SELECT * FROM compras WHERE order_id = ?");
$stmt->execute([$order_id]);
$compra = $stmt->fetch();

if (!$compra) {
    die("Compra no encontrada");
}

// 2. Generar QR temporal
$qrTemp = "qr_$order_id.png";
$qrData = "https://tuweb.com/ver_compra.php?id=$order_id";
QRcode::png($qrData, $qrTemp, QR_ECLEVEL_L, 4);

// 3. Crear PDF
$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(0, 10, "Ticket de compra", 0, 1, 'C');

$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, "Producto: " . $compra['producto'], 0, 1);
$pdf->Cell(0, 10, "Precio: " . $compra['precio'] . " €", 0, 1);
$pdf->Cell(0, 10, "Fecha: " . $compra['fecha'], 0, 1);
$pdf->Ln(10);

// 4. Insertar QR
$pdf->Image($qrTemp, 15, 80, 50, 50);

// 5. Descargar PDF
$pdf->Output("ticket_$order_id.pdf", "I");

// 6. Borrar QR temporal
unlink($qrTemp);
?>
