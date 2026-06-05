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

    <img class="cartel" src="/<?= htmlspecialchars($imagen) ?>" alt="Cartel del evento">

    <h2><?= htmlspecialchars($fiesta) ?></h2>

    <p>
        Nombre: <?= htmlspecialchars($nombre) ?>
    </p>

    <p>
        Código entrada: 
        <strong><?= htmlspecialchars($codigoQR) ?></strong>
    </p>

    <a class="btn" 
       href="/generar-pdf?fiesta=<?= urlencode($fiesta) ?>&img=<?= urlencode($imagen) ?>&codigo=<?= urlencode($codigoQR) ?>&nombre=<?= urlencode($nombre) ?>">
        Descargar entrada PDF
    </a>
</div>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</body>
</html>