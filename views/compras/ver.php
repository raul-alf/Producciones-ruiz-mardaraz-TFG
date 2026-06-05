<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de compra</title>

    <style>
        body{
            background:#000;
            color:white;
            font-family:Arial,sans-serif;
            padding:40px;
        }

        .card{
            max-width:700px;
            margin:auto;
            background:#111;
            border:1px solid #333;
            padding:30px;
        }

        h1{
            color:#d4af37;
        }

        p{
            margin:15px 0;
        }
    </style>
</head>
<body>

<div class="card">

    <h1>Detalles de la compra</h1>

    <p>
        Producto:
        <?= htmlspecialchars($compra['producto']) ?>
    </p>

    <p>
        Precio:
        <?= htmlspecialchars($compra['precio']) ?> €
    </p>

    <p>
        Fecha:
        <?= htmlspecialchars($compra['fecha']) ?>
    </p>

    <p>
        Estado:
        Confirmado
    </p>

</div>

</body>
</html>