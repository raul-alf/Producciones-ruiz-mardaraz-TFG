<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cafe Pub La Luna - Galería</title>

    <link rel="stylesheet" href="css/style.css">

    <style>
        .album-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .album-card {
            position: relative;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border: 1px solid #222;
            cursor: pointer;
            display: block;
        }

        .album-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .album-card:hover img {
            transform: scale(1.1);
        }

        .album-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            padding: 20px;
            text-align: center;
        }

        .day-label {
            display: block;
            color: #fff;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .date-label {
            color: #d4af37;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }

        header {
            text-align: center;
            padding-top: 120px;
        }

        .tagline {
            letter-spacing: 5px;
            color: #666;
            font-size: 0.9rem;
            margin-top: 10px;
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

<header>

    <div class="logo-container">
        <img src="<?= BASE_URL ?>img/logo/logo Luna.png"
             alt="Luna Logo"
             class="main-logo"
             style="max-width:200px;">
    </div>

    <p class="tagline">
        REVIVE TUS MEJORES MOMENTOS
    </p>

</header>

<div class="album-container" id="grid-albumes">

    <p style="color:white;text-align:center;grid-column:1/-1;">
        Cargando recuerdos...
    </p>

</div>

<script>

async function cargarAlbumes()
{
    const grid = document.getElementById('grid-albumes');

    try {

        const res = await fetch('<?= BASE_URL ?>api/get-all?t=' + Date.now());

        const data = await res.json();

        const albums = data.albums || [];

        if(albums.length === 0)
        {
            grid.innerHTML =
            `
            <p style="color:#666;text-align:center;grid-column:1/-1;">
                Próximamente fotos de nuevos eventos...
            </p>
            `;
            return;
        }

        grid.innerHTML = albums.map(album => `
            <a href="<?= BASE_URL ?>ver-album?id=${album.id}" class="album-card">

                <img src="${album.cover}" alt="${album.title}">

                <div class="album-overlay">

                    <span class="day-label">
                        ${album.title}
                    </span>

                    <span class="date-label">
                        CLICK PARA VER FOTOS
                    </span>

                </div>

            </a>
        `).join('');

    }
    catch(error)
    {
        console.error(error);
        grid.innerHTML =
        `
        <p style="color:red;text-align:center;grid-column:1/-1;">
            Error al conectar con la galería.
        </p>
        `;
    }
}

cargarAlbumes();

</script>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</body>
</html>