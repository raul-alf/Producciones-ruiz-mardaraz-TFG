<?php
require_once 'db.php';

$order_id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM compras WHERE order_id = ?");
$stmt->execute([$order_id]);
$compra = $stmt->fetch();

if (!$compra) {
    die("Compra no encontrada");
}
?>

<h1>Detalles de la compra</h1>
<p>Producto: <?= $compra['producto'] ?></p>
<p>Precio: <?= $compra['precio'] ?> €</p>
<p>Fecha: <?= $compra['fecha'] ?></p>
<p>Estado: Confirmado</p>
