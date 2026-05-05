<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luna Night Club - Ver Álbum</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; }
        /* Cuadrícula de fotos */
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
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .photo-item:hover img { transform: scale(1.05); }

        /* Cabecera */
        .gallery-header { text-align: center; padding: 100px 20px 20px; }
        .gallery-header h1 { text-transform: uppercase; letter-spacing: 3px; margin-top: 15px; }
        .back-btn { color: #d4af37; text-decoration: none; text-transform: uppercase; font-size: 0.9rem; }

        /* Responsive */
        @media (max-width: 1024px) { .photos-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .photos-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo"><a href="index.php"><img src="img/logo/Luna_fondo_negro.png"></a></div>
        <ul class="nav-links">
            <li><a href="index.php">INICIO</a></li>
            <li><a href="galeria.php" class="active">GALERÍA</a></li>
            <li><a href="fechas.php">PRÓXIMAS FECHAS</a></li>
            <li><a href="reservas.php">RESERVAS</a></li>
        </ul>
    </div>
</nav>

<div class="gallery-header">
    <a href="galeria.php" class="back-btn">← Volver a Galería</a>
    <h1 id="album-title">Cargando Álbum...</h1>
</div>

<div class="photos-grid" id="photos-grid">
    </div>

<script>
    async function cargarFotos() {
        // Obtenemos el ID del álbum de la URL (ej: ver-album.php?id=LUNA_LATINEO)
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');

        if (!albumId) {
            window.location.href = 'galeria.php';
            return;
        }

        try {
            // Llamamos a la API unificada que ya tienes
            const res = await fetch(`api/get_all.php?t=${Date.now()}`);
            const data = await res.json();
            
            // Buscamos el álbum específico dentro de la lista
            const album = data.albums.find(a => a.id === albumId);

            if (!album) {
                document.getElementById('album-title').innerText = "Álbum no encontrado";
                return;
            }

            document.getElementById('album-title').innerText = album.title;
            
            const grid = document.getElementById('photos-grid');
            
            // IMPORTANTE: Aquí album.photos debe ser un array con las rutas de las fotos
            if (album.photos && album.photos.length > 0) {
                grid.innerHTML = album.photos.map(fotoPath => `
                    <div class="photo-item">
                        <img src="${fotoPath}" alt="Foto del evento" loading="lazy">
                    </div>
                `).join('');
            } else {
                grid.innerHTML = "<p style='grid-column: 1/-1; text-align: center; color: #666;'>No hay fotos en este álbum aún.</p>";
            }

        } catch (e) {
            console.error("Error cargando fotos:", e);
            document.getElementById('album-title').innerText = "Error de conexión";
        }
    }

    cargarFotos();
</script>

</body>
</html>