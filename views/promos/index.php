<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">

    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .promo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .promo-card {
            position: relative;
            background: #0a0a0a;
            border: 1px solid #222;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .promo-card:hover {
            border-color: #fff;
            transform: translateY(-5px);
        }

        .promo-card img {
            width: 100%;
            display: block;
            transition: 0.5s;
            height: 350px;
            object-fit: cover;
        }

        .promo-card:hover img {
            filter: brightness(0.7);
        }

        .promo-header-text {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-bottom: 1px solid #222;
        }

        .promo-description {
            padding: 15px;
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            line-height: 1.4;
            min-height: 60px;
        }

        .btn-promo {
            display: block;
            background: #fff;
            color: #000;
            text-decoration: none;
            padding: 12px;
            font-weight: bold;
            font-size: 0.8rem;
            margin: 10px;
            transition: 0.3s;
            text-align: center;
            letter-spacing: 1px;
        }

        .btn-promo:hover {
            background: #d4af37;
            color: #fff;
        }

        .modal-zoom {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            cursor: zoom-out;
            text-align: center;
        }

        .modal-content-zoom {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80vh;
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid #333;
        }

        #caption {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.5rem;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .close-zoom {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
        }

        .no-promos {
            text-align: center;
            grid-column: 1 / -1;
            padding: 100px 20px;
            color: #555;
            letter-spacing: 2px;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="nav-container">

        <div class="nav-logo">
            <a href="<?php BASE_URL ?>">
                <img src="<?= BASE_URL ?>img/logo/Luna_fondo_negro.png" alt="Luna Logo">
            </a>
        </div>

       <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>index" class="active">INICIO</a></li>
            <li><a href="<?= BASE_URL ?>galeria">GALERÍA</a></li>
            <li><a href="<?= BASE_URL ?>fechas">PRÓXIMAS FECHAS</a></li>
            <li><a href="<?= BASE_URL ?>reservas">RESERVAS</a></li>
        </ul>

    </div>
</nav>

<div class="promo-grid" style="padding-top: 120px;">

    <?php if (!empty($promos)): ?>

        <?php foreach ($promos as $promo): ?>
            <?php
                $titulo = htmlspecialchars($promo['titulo']);
                $imagen = "/img/" . htmlspecialchars($promo['imagen']);
                $desc = htmlspecialchars($promo['descripcion']);
            ?>

            <article class="promo-card">
                <div class="promo-header-text">
                    <?= $titulo ?>
                </div>

                <div class="img-wrapper" onclick="openModal('<?= $imagen ?>', '<?= $titulo ?>')">
                    <img src="<?= $imagen ?>" alt="<?= $titulo ?>">
                </div>

                <div class="promo-description">
                    <?= $desc ?>
                </div>

                <div style="padding: 0 10px 20px 10px;">
                    <a href="/reservas?promo=<?= urlencode($titulo) ?>" class="btn-promo">
                        APROVECHAR PROMO
                    </a>
                </div>
            </article>
        <?php endforeach; ?>

    <?php else: ?>

        <div class="no-promos">
            <h3>PRÓXIMAMENTE NUEVAS PROMOCIONES</h3>
            <p>Sigue nuestras redes para no perderte nada.</p>
        </div>

    <?php endif; ?>

</div>

<div id="modalZoom" class="modal-zoom" onclick="closeModal()">
    <span class="close-zoom">&times;</span>
    <div id="caption"></div>
    <img class="modal-content-zoom" id="imgAmpliada">
</div>

<script>
    function openModal(src, title) {
        document.getElementById("modalZoom").style.display = "block";
        document.getElementById("imgAmpliada").src = src;
        document.getElementById("caption").innerHTML = title;
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        document.getElementById("modalZoom").style.display = "none";
        document.body.style.overflow = "auto";
    }
</script>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>

</body>
</html>