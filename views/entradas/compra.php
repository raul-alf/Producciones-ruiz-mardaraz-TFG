<!DOCTYPE html>
<html lang="es">
<head> 
    <script src="https://www.paypal.com/sdk/js?client-id=AW_tLnU6E9ADrQDyesp4gEYpgMSnMFzfKVdDNHPvhcNZaMzf1iOBeAh-8bmA-bJsewkNa4E0d0smdRC-&currency=EUR"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">

    <style>
        body { background-color: #000; color: #fff; font-family: sans-serif; margin: 0; }
        .logo-nav { text-align: center; padding: 30px 0 10px 0; }
        .logo-nav img { width: 120px; transition: 0.3s; }

        .checkout-box {
            max-width: 450px;
            margin: 20px auto 80px auto;
            padding: 40px;
            background: #0a0a0a;
            border: 1px solid #222;
            text-align: center;
        }

        .timer-box {
            background: #d4af37;
            color: #000;
            padding: 8px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .event-preview-container {
            width: 100%;
            background: #111;
            margin-bottom: 15px;
            border: 1px solid #333;
            line-height: 0;
        }

        .event-preview {
            width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: contain;
            display: block;
        }

        .event-info {
            background: #111;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #d4af37;
            text-align: left;
        }

        .btn-confirm {
            width: 100%;
            padding: 15px;
            background: #fff;
            color: #000;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            background: #d4af37;
            color: #fff;
        }

        .email-input {
            width: 100%;
            padding: 14px 16px;
            margin: 20px 0 0 0;
            border: 1px solid #333;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .email-label {
            display: block;
            text-align: left;
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

<div class="logo-nav">
    <a href="<?= BASE_URL ?>">
        <img src="<?= BASE_URL ?>img/logo/logo Luna.png" alt="Luna Logo">
    </a>
</div>

<div class="checkout-box">
    <div class="timer-box">
        LA RESERVA EXPIRA EN: <span id="timer">07:00</span>
    </div>

    <h2 style="letter-spacing: 4px; margin-top: 0;">
        FINALIZAR COMPRA
    </h2>
    
    <div class="event-preview-container">
        <img id="event-img" 
             class="event-preview" 
             src="<?= BASE_URL ?><?= htmlspecialchars($imagen) ?>" 
             alt="Cartel del Evento">
    </div>

    <div class="event-info">
        <p style="color: #888; font-size: 0.7rem; margin: 0;">
            EVENTO SELECCIONADO:
        </p>

        <h3 id="fest-name" style="margin: 5px 0 0 0; text-transform: uppercase;">
            <?= htmlspecialchars($fiesta) ?>
        </h3>

        <p style="margin-top: 10px; font-weight: bold; color: #d4af37;">
            PRECIO: <?= number_format($precio, 2) ?>€
        </p>

        <label for="buyerEmail" class="email-label">Correo electrónico</label>
        <input id="buyerEmail" type="email" class="email-input" placeholder="tucorreo@ejemplo.com" required>
    </div>

    <div id="paypal-button-container"></div>
</div>

<script>
    paypal.Buttons({
        onClick: function(data, actions) {
            const email = document.getElementById('buyerEmail').value.trim();
            const emailPattern = /^\S+@\S+\.\S+$/;

            if (!emailPattern.test(email)) {
                alert('Introduce un correo válido para recibir tu entrada.');
                return actions.reject();
            }

            return actions.resolve();
        },

        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: "<?= number_format($precio, 2, '.', '') ?>"
                    }
                }]
            });
        },

        onApprove: function(data, actions) {
            const email = document.getElementById('buyerEmail').value.trim();

            return fetch('<?= BASE_URL ?>api/capture-order.php', {
                method: "post",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    fiesta: "<?= htmlspecialchars($fiesta) ?>",
                    imagen: "<?= htmlspecialchars($imagen) ?>",
                    fecha: "<?= htmlspecialchars($fecha) ?>",
                    precio: "<?= number_format($precio, 2, '.', '') ?>",
                    email: email
                })
            })
            .then(res => res.json())
            .then(result => {
                if (!result.success) {
                    throw new Error(result.message || 'Error al procesar el pedido');
                }

                window.location.href = result.ticket_url || ('<?= BASE_URL ?>ticket?order_id=' + result.order_id);
            })
            .catch(error => {
                alert('Error al procesar el pedido: ' + error.message);
            });
        }
    }).render('#paypal-button-container');
</script>

<script>
    let time = 420;
    const timerElement = document.getElementById('timer');

    const countdown = setInterval(() => {
        let min = Math.floor(time / 60);
        let sec = time % 60;

        timerElement.innerHTML = `${min}:${sec < 10 ? '0' + sec : sec}`;

        if (time-- <= 0) {
            clearInterval(countdown);
            location.href = '<?= BASE_URL ?>fechas' + '?expired=true';
        }
    }, 1000);
</script>

</body>
</html>