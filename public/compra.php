<?php
// Recogemos los datos enviados desde fechas.php
$fiesta = isset($_GET['fiesta']) ? $_GET['fiesta'] : 'Evento Luna';
$imagen = isset($_GET['img']) ? $_GET['img'] : 'img/placeholder.jpg';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://www.paypal.com/sdk/js?client-id=AW_tLnU6E9ADrQDyesp4gEYpgMSnMFzfKVdDNHPvhcNZaMzf1iOBeAh-8bmA-bJsewkNa4E0d0smdRC-&currency=EUR"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="style.css">
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

        /* AJUSTE PARA QUE EL CARTEL NO SE CORTE */
        .event-preview-container {
            width: 100%;
            background: #111; /* Fondo oscuro para los bordes si la imagen es estrecha */
            margin-bottom: 15px;
            border: 1px solid #333;
            line-height: 0;
        }

        .event-preview {
            width: 100%;
            height: auto; /* Altura automática para mantener la proporción real */
            max-height: 450px; /* Límite para que no ocupe toda la pantalla */
            object-fit: contain; /* Muestra la imagen completa sin recortes */
            display: block;
        }

        .event-info {
            background: #111;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #d4af37;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: #000;
            border: 1px solid #333;
            color: #fff;
            box-sizing: border-box;
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
        .btn-confirm:hover { background: #d4af37; color: #fff; }

        #loader-container { display: none; margin-top: 20px; }
        .progress-bar { width: 100%; height: 4px; background: #222; margin-top: 15px; overflow: hidden; }
        .progress-fill { width: 0%; height: 100%; background: #d4af37; transition: width 2.5s linear; }
    </style>
</head>
<body>

<div class="logo-nav">
    <a href="index.php"><img src="img/logo/logo Luna.png" alt="Luna Logo"></a>
</div>

<div class="checkout-box">
    <div class="timer-box">LA RESERVA EXPIRA EN: <span id="timer">07:00</span></div>

    <h2 style="letter-spacing: 4px; margin-top: 0;">FINALIZAR COMPRA</h2>
    
    <div class="event-preview-container">
        <img id="event-img" class="event-preview" src="<?php echo htmlspecialchars($imagen); ?>" alt="Cartel del Evento">
    </div>

    <div class="event-info">
        <p style="color: #888; font-size: 0.7rem; margin: 0;">EVENTO SELECCIONADO:</p>
        <h3 id="fest-name" style="margin: 5px 0 0 0; text-transform: uppercase;">
            <?php echo htmlspecialchars($fiesta); ?>
        </h3>
        <p style="margin-top: 10px; font-weight: bold; color: #d4af37;">PRECIO: 15.00€</p>
    </div>
        <div id="paypal-button-container"></div>
</form>
    <script>
        paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            // ...
            purchase_units: [{
                amount: {
                    value: 15.00 // Precio del evento, se puede ajustar dinámicamente según el evento seleccionado
                }
            }],
        });
    },
onApprove: function(data, actions) {
  return fetch(`/api/capture-order`, {
    method: "post",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ orderID: data.orderID })
  })
  .then(res => res.json())
  .then(result => {
    // result.order_id viene del backend
    window.location.href = `/ticket.php?order_id=${result.order_id}`;
  });
}

}).render('#paypal-button-container');

    </script>
</div>

<script>
    // Temporizador de 7 minutos
    let time = 420;
    const timerElement = document.getElementById('timer');
    const countdown = setInterval(() => {
        let min = Math.floor(time / 60);
        let sec = time % 60;
        timerElement.innerHTML = `${min}:${sec < 10 ? '0' + sec : sec}`;
        if (time-- <= 0) { clearInterval(countdown); location.href = "fechas.php"; }
    }, 1000);
</script>
</body>
</html>