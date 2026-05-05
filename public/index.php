<?php
/**
 * Index principal para Cafe Pub La Luna
 * Integra la estructura HTML con lógica PHP para componentes dinámicos.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Pub La Luna | Inicio</title>
    <link rel="icon" type="image/png" href="img/logo/logo Luna.png">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos para el Modal de Zoom */
        .modal-zoom {
            display: none; 
            position: fixed; 
            z-index: 10000; 
            left: 0; top: 0;
            width: 100%; height: 100%; 
            background-color: rgba(0,0,0,0.95);
            cursor: zoom-out;
            animation: fadeIn 0.3s;
        }
        .modal-content-zoom {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 85vh;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #333;
        }
        .close-zoom {
            position: absolute;
            top: 20px; right: 35px;
            color: #fff; font-size: 50px;
            font-weight: bold; cursor: pointer;
        }
        @keyframes fadeIn { from {opacity: 0;} to {opacity: 1;} }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <php 
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
            ?> 
            <a href="admin.php"><img src="img/logo/Luna_fondo_negro.png" alt="Luna Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">INICIO</a></li>
            <li><a href="galeria.php">GALERÍA</a></li>
            <li><a href="fechas.php">PRÓXIMAS FECHAS</a></li>
            <li><a href="reservas.php">RESERVAS</a></li>
        </ul>
    </div>
</nav>

<section class="hero">
    <div class="hero-content">
        <img src="img/logo/logo Luna.png" alt="Luna Logo" class="hero-logo">
        <h2 class="hero-title">EXPERIENCIA EXCLUSIVA</h2>
        <p class="hero-subtitle">Música, luces y tus mejores recuerdos en un solo lugar.</p>
        <div class="hero-btns">
            <a href="fechas.php" class="btn-main">PRÓXIMOS EVENTOS</a>
            <a href="reservas.php" class="btn-outline">RESERVAR VIP</a>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<section class="next-event-highlight" id="featuredEvent" style="display: none; padding: 60px 20px; text-align: center; background: #000;">
    <h2 class="section-title">ESTE FIN DE SEMANA</h2>
    <div id="eventContainer" style="max-width: 500px; margin: 0 auto;"></div>
</section>

<section class="experience">
    <div class="container">
        <h2 class="section-title">LA EXPERIENCIA LUNA</h2>
        <div class="features-grid">
            <div class="feature-item">
                <h3>VIP TABLES</h3>
                <p>Servicio exclusivo en las mejores ubicaciones del club.</p>
            </div>
            <div class="feature-item">
                <h3>SOUND SYSTEM</h3>
                <p>Siente cada beat con nuestra acústica profesional.</p>
            </div>
            <div class="feature-item">
                <h3>DJ SETS</h3>
                <p>Sesiones en directo con los referentes del género.</p>
            </div>
        </div>
    </div>
</section>

<section class="residents">
    <div class="container">
        <h2 class="section-title">DJ RESIDENTS</h2>
        <div class="dj-grid">
            <?php
            // Listado de DJs (esto podría venir de la DB más adelante)
            $djs = [
                ['name' => 'DJ ADONYS', 'role' => 'URBAN DJ', 'img' => 'img/DSC00354.jpg'],
                ['name' => 'DJ RAUL MALO', 'role' => 'URBAN DJ', 'img' => 'img/IMG_2071.jpg'],
                ['name' => 'DJ ALBERTO AGUIRRE', 'role' => 'TECHNO / GROOVE', 'img' => 'img/DSC02281.jpg'],
                ['name' => 'DJ NCNH', 'role' => 'TECHNO / GROOVE', 'img' => 'img/DSC02243.jpg']
            ];
            foreach ($djs as $dj): ?>
                <div class="dj-card">
                    <div class="dj-img"><img src="<?= $dj['img'] ?>" alt="<?= $dj['name'] ?>"></div>
                    <div class="dj-info"><h3><?= $dj['name'] ?></h3><p><?= $dj['role'] ?></p></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="horario-destacado" style="background: #050505; border-top: 1px solid #111;">
    <div class="container" style="text-align: center; padding: 80px 20px;">
        <h2 class="section-title">NUESTRO HORARIO</h2>
        <div class="horario-wrapper">
            <p style="color: #888; letter-spacing: 2px; margin-bottom: 30px;">Haz clic en el calendario para ampliar los detalles.</p>
            <img src="img/horario.png" alt="Horario Luna" id="horarioImg" 
                 style="max-width: 400px; width: 100%; border: 2px solid #222; border-radius: 8px; cursor: pointer; transition: 0.4s;" 
                 onclick="openModal(this.src)">
        </div>
    </div>
</section>

<section class="sponsors">
    <div class="container">
        <p class="sponsors-label">PARTNERS & SPONSORS</p>
        <div class="sponsors-list">
            <img src="img/patrocinadores/98.jpg.png" alt="Sponsor 1">
            <img src="img/patrocinadores/ESTRELLA-GALICIA-1.png" alt="Sponsor 2">
            <img src="img/patrocinadores/images-3.png" alt="Sponsor 3">
            <img src="img/patrocinadores/schweppes.svg" alt="Sponsor 4">
        </div>
    </div>
</section>

<footer class="footer-info">
    <div class="container footer-grid" style="display: flex; flex-wrap: wrap; justify-content: space-around; padding: 60px 0; border-top: 1px solid #111;">
        <div class="info-box">
            <h4>UBICACIÓN</h4>
            <p>C. Alfolíes, 3, 26540 Alfaro, La Rioja</p>
            <a href="https://maps.google.com" target="_blank" style="color: #fff; font-size: 0.8rem; text-decoration: underline;">CÓMO LLEGAR</a>
        </div>
        <div class="info-box">
            <h4>CONTACTO</h4>
            <p>Teléfono: +34 666 867 816</p>
            <p>Email: info@cafepublaluna.com</p>
        </div>
    </div>
</footer>

<div id="modalZoom" class="modal-zoom" onclick="closeModal()">
    <span class="close-zoom">×</span>
    <img class="modal-content-zoom" id="imgAmpliada">
</div>

<script>
    // Configuración de la API local
    async function loadFeaturedEvent() {
        try {
            // Llamada a tu script PHP que devuelve el JSON de la base de datos
            const response = await fetch('api/get_events.php?t=' + Date.now());
            const events = await response.json();
            
            if (events && events.length > 0) {
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                const proximaSemana = new Date();
                proximaSemana.setDate(hoy.getDate() + 7);

                const eventosSemana = events.filter(e => {
                    const fechaEvento = new Date(e.date);
                    return fechaEvento >= hoy && fechaEvento <= proximaSemana;
                });

                if (eventosSemana.length > 0) {
                    document.getElementById('featuredEvent').style.display = 'block';
                    const container = document.getElementById('eventContainer');
                    container.innerHTML = ''; 

                    eventosSemana.sort((a, b) => new Date(a.date) - new Date(b.date));

                    eventosSemana.forEach(ev => {
                        container.innerHTML += `
                            <div style="margin-bottom: 40px;">
                                <img src="${ev.image}" 
                                     alt="Evento" 
                                     style="width: 100%; border: 1px solid #222; cursor: pointer;"
                                     onclick="openModal(this.src)">
                                <p style="margin-top: 15px; color: #fff; letter-spacing: 2px; font-weight: bold;">
                                    ${ev.status}
                                </p>
                                <p style="color: #d4af37; font-size: 0.8rem;">
                                    ${new Date(ev.date).toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' })}
                                </p>
                            </div>
                        `;
                    });
                }
            }
        } catch (e) { 
            console.error("Error cargando eventos desde la API PHP:", e); 
        }
    }

    loadFeaturedEvent();

    function openModal(src) {
        document.getElementById("modalZoom").style.display = "block";
        document.getElementById("imgAmpliada").src = src;
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        document.getElementById("modalZoom").style.display = "none";
        document.body.style.overflow = "auto";
    }
</script>

</body>
</html>