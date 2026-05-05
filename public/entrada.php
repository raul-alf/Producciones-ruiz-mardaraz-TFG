<?php
$fiesta = $_GET['fiesta'] ?? 'Evento Luna';
$imagen = $_GET['img'] ?? 'img/placeholder.jpg';
$orderID = $_GET['orderID'] ?? '';
$nombre = $_GET['nombre'] ?? 'Cliente';

$codigoQR = 'LUNA-' . strtoupper(substr(md5($orderID . time()), 0, 12));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrada generada</title>
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
        }

        .ticket {
            max-width: 500px;
            margin: auto;
            background: #111;
            padding: 30px;
            border: 1px solid #d4af37;
        }

        img.cartel {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            background: #d4af37;
            color: #000;
            padding: 15px 25px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="ticket">
    <h1>Compra completada</h1>

    <img class="cartel" src="<?php echo htmlspecialchars($imagen); ?>">

    <h2><?php echo htmlspecialchars($fiesta); ?></h2>
    <p>Nombre: <?php echo htmlspecialchars($nombre); ?></p>
    <p>Código entrada: <strong><?php echo $codigoQR; ?></strong></p>

    <a class="btn" href="generar_pdf.php?fiesta=<?php echo urlencode($fiesta); ?>&img=<?php echo urlencode($imagen); ?>&codigo=<?php echo urlencode($codigoQR); ?>&nombre=<?php echo urlencode($nombre); ?>">
        Descargar entrada PDF
    </a>
</div>

</body>
</html>