<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luna Night Club - Ver Álbum</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">

    <style>
        body {
            background: #000;
            color: #fff;
            font-family: sans-serif;
        }

        .photos-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .photo-item {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border: 1px solid #222;
            background-color: #111;
            cursor: pointer;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .photo-item:hover img {
            transform: scale(1.05);
        }

        .gallery-header {
            text-align: center;
            padding: 100px 20px 20px;
        }

        .gallery-header h1 {
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 15px;
        }

        .back-btn {
            color: #d4af37;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .lightbox-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
        }

        .lightbox-overlay.visible {
            display: flex;
        }

        .lightbox-content {
            position: relative;
            max-width: 95%;
            max-height: 95%;
            width: 100%;
            text-align: center;
        }

        .lightbox-content img {
            max-width: 95vw;
            max-height: 85vh;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);
        }

        .lightbox-close {
            position: absolute;
            top: -12px;
            right: -12px;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.95);
            color: #000;
            font-size: 1.4rem;
            cursor: pointer;
            line-height: 1;
        }

        .lightbox-actions {
            margin-top: 10px;
        }

        .lightbox-actions a {
            display: inline-block;
            padding: 10px 20px;
            background: #d4af37;
            color: #000;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 999px;
        }

        @media (max-width: 1024px) {
            .photos-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .photos-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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

<div class="gallery-header">
    <a href="<?= BASE_URL ?>galeria" class="back-btn">
        ← Volver a Galería
    </a>

    <h1 id="album-title">
        Cargando Álbum...
    </h1>
</div>

<div class="photos-grid" id="photos-grid"></div>

<div class="lightbox-overlay" id="lightboxOverlay" aria-hidden="true">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">×</button>
        <img id="lightboxImage" src="" alt="Foto ampliada">
        <div class="lightbox-actions">
            <a id="downloadLink" href="#" download="foto-album.jpg">Descargar</a>
        </div>
    </div>
</div>

<script>
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImage = document.getElementById('lightboxImage');
    const downloadLink = document.getElementById('downloadLink');
    const lightboxClose = document.getElementById('lightboxClose');

    function abrirLightbox(src) {
        lightboxImage.src = src;
        lightboxImage.alt = 'Foto ampliada';
        downloadLink.href = src;
        const filename = src.split('/').pop().split('?')[0] || 'foto-album.jpg';
        downloadLink.download = filename;
        lightboxOverlay.classList.add('visible');
        lightboxOverlay.setAttribute('aria-hidden', 'false');
    }

    function cerrarLightbox() {
        lightboxOverlay.classList.remove('visible');
        lightboxOverlay.setAttribute('aria-hidden', 'true');
        lightboxImage.src = '';
    }

    lightboxClose.addEventListener('click', cerrarLightbox);
    lightboxOverlay.addEventListener('click', (event) => {
        if (event.target === lightboxOverlay) {
            cerrarLightbox();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && lightboxOverlay.classList.contains('visible')) {
            cerrarLightbox();
        }
    });

    async function cargarFotos() {
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');

        if (!albumId) {
            window.location.href = '<?= BASE_URL ?>galeria';
            return;
        }

        try {
            const res = await fetch('<?= BASE_URL ?>api/get-all?t=' + Date.now());
            const data = await res.json();

            const albums = data.albums || [];
            const album = albums.find(a => a.id === albumId);

            if (!album) {
                document.getElementById('album-title').innerText = "Álbum no encontrado";
                return;
            }

            document.getElementById('album-title').innerText = album.title;

            const grid = document.getElementById('photos-grid');

            if (album.photos && album.photos.length > 0) {
                grid.innerHTML = album.photos.map(fotoPath => `
                    <div class="photo-item">
                        <img src="${fotoPath}" alt="Foto del evento" loading="lazy" data-src="${fotoPath}">
                    </div>
                `).join('');

                document.querySelectorAll('.photo-item img').forEach(img => {
                    img.addEventListener('click', () => abrirLightbox(img.dataset.src));
                });
            } else {
                grid.innerHTML = `
                    <p style="grid-column:1/-1; text-align:center; color:#666;">
                        No hay fotos en este álbum aún.
                    </p>
                `;
            }

        } catch (e) {
            console.error("Error cargando fotos:", e);
            document.getElementById('album-title').innerText = "Error de conexión";
        }
    }

    cargarFotos();
</script>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</body>
</html>