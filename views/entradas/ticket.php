<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Entrada - Luna Night Club</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .ticket-container {
            max-width: 600px;
            margin: 80px auto 40px;
            padding: 20px;
        }

        .ticket-card {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border: 2px solid #d4af37;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.3);
        }

        .ticket-header {
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .ticket-header h1 {
            color: #d4af37;
            font-size: 2em;
            margin: 0 0 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .ticket-header p {
            color: #bbb;
            margin: 5px 0;
        }

        .ticket-body {
            padding: 20px 0;
        }

        .event-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
            max-height: 300px;
        }

        .ticket-details {
            background: rgba(212, 175, 55, 0.1);
            border-left: 4px solid #d4af37;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 4px;
        }

        .ticket-details p {
            margin: 10px 0;
            font-size: 1em;
        }

        .ticket-details strong {
            color: #d4af37;
        }

        .ticket-footer {
            padding-top: 20px;
            border-top: 2px solid #d4af37;
            margin-top: 20px;
        }

        .ticket-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-download {
            background: #d4af37;
            color: #000;
        }

        .btn-download:hover {
            background: #e5c158;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .btn-home {
            background: transparent;
            color: #d4af37;
            border: 2px solid #d4af37;
        }

        .btn-home:hover {
            background: #d4af37;
            color: #000;
            transform: translateY(-2px);
        }

        .ticket-code {
            background: #111;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-weight: bold;
            color: #d4af37;
            word-break: break-all;
        }

        .success-icon {
            font-size: 3em;
            margin: 10px 0;
            color: #d4af37;
        }

        @media (max-width: 600px) {
            .ticket-card {
                padding: 20px;
            }

            .ticket-header h1 {
                font-size: 1.5em;
            }

            .ticket-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>img/logo/Luna_fondo_negro.png" alt="Luna Logo">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>">INICIO</a></li>
            <li><a href="<?= BASE_URL ?>fechas">PRÓXIMAS FECHAS</a></li>
            <li><a href="<?= BASE_URL ?>galeria">GALERÍA</a></li>
        </ul>
    </div>
</nav>

<div class="ticket-container">
    <div class="ticket-card">
        <div class="ticket-header">
            <div class="success-icon">✓</div>
            <h1>¡Compra Confirmada!</h1>
            <p>Tu entrada está lista para descargar</p>
        </div>

        <div class="ticket-body">
            <?php if (!empty($imagen)): ?>
                <img src="<?= BASE_URL . ltrim($imagen, '/') ?>" alt="<?= htmlspecialchars($fiesta) ?>" class="event-image">
            <?php endif; ?>

            <div class="ticket-details">
                <p><strong>Evento:</strong> <?= htmlspecialchars($fiesta) ?></p>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
                <p><strong>Referencia:</strong></p>
                <div class="ticket-code"><?= htmlspecialchars($orderID) ?></div>
                <p><strong>Código QR:</strong> <?= htmlspecialchars($codigoQR) ?></p>
            </div>

            <p style="color: #bbb; font-size: 0.9em; margin-top: 20px;">
                Presenta esta entrada en la puerta del evento. Puedes mostrar el PDF en tu teléfono o imprimirlo.
            </p>
        </div>

        <div class="ticket-footer">
            <div class="ticket-actions">
                <a href="<?= BASE_URL ?>generar-pdf?fiesta=<?= urlencode($fiesta) ?>&img=<?= urlencode($imagen) ?>&codigo=<?= urlencode($codigoQR) ?>&nombre=<?= urlencode($nombre) ?>" 
                   class="btn btn-download" download>
                    Descargar Entrada
                </a>
                <a href="<?= BASE_URL ?>" class="btn btn-home">
                     Volver al inicio
                </a>
            </div>
            <p style="color: #888; font-size: 0.85em; margin-top: 15px;">
                También hemos enviado tu entrada por correo electrónico.
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</body>
</html>
