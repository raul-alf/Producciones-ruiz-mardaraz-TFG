<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próximas Fechas - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .flyers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 900px) {
            .flyers-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .flyers-grid {
                grid-template-columns: 1fr;
            }
        }

        .flyer-card {
            position: relative;
            background: #0a0a0a;
            border: 1px solid #222;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .flyer-card:hover {
            border-color: #fff;
            transform: translateY(-5px);
        }

        .flyer-card img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
            transition: 0.5s;
        }

        .flyer-card:hover img {
            filter: brightness(0.7);
        }

        .flyer-header-text {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-bottom: 1px solid #222;
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

        .btn-ticket {
            display: inline-block;
            background: #fff;
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 0.8rem;
            margin: 10px auto;
            transition: 0.3s;
            text-align: center;
        }

        .btn-ticket:hover {
            background: #d4af37;
            color: #fff;
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
            <li><a href="<?= BASE_URL ?>index" class="active">INICIO</a></li>
            <li><a href="<?= BASE_URL ?>galeria">GALERÍA</a></li>
            <li><a href="<?= BASE_URL ?>fechas">PRÓXIMAS FECHAS</a></li>
            <li><a href="<?= BASE_URL ?>reservas">RESERVAS</a></li>
        </ul>
    </div>
</nav>

<div class="flyers-grid" id="flyersGrid" style="padding-top: 120px;">
    <p style="color: white; text-align: center; grid-column: 1/-1;">
        Cargando próximos eventos...
    </p>
</div>

<div id="modalZoom" class="modal-zoom" onclick="closeModal()">
    <span class="close-zoom">&times;</span>
    <div id="caption"></div>
    <img class="modal-content-zoom" id="imgAmpliada">
</div>

<script>
    async function loadAllEvents() {
        try {
            const response = await fetch('<?= BASE_URL ?>api/events?t=' + Date.now());  
            console.log("Respuesta del servidor:", await response);
            const events = await response.json();
            console.log("Eventos recibidos:", events);
            const grid = document.getElementById('flyersGrid');

            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            const eventosFuturos = events.filter(event => {
                const fechaEvento = new Date(event.date);
                fechaEvento.setHours(0, 0, 0, 0);

                const oculto = event.oculto == 1 || event.hidden == 1;

                return fechaEvento >= hoy && !oculto;
            });

            if (eventosFuturos.length === 0) {
                grid.innerHTML = "<p style='color: #666; text-align: center; grid-column: 1/-1;'>No hay eventos programados próximamente.</p>";
                return;
            }

            eventosFuturos.sort((a, b) => new Date(a.date) - new Date(b.date));

            grid.innerHTML = '';

            eventosFuturos.forEach(event => {
                const titulo = event.title || event.status || 'Evento Luna';

                const fechaFormateada = new Date(event.date).toLocaleDateString('es-ES', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long'
                });

                const urlCompra = '<?= BASE_URL ?>compra?fiesta=' + encodeURIComponent(titulo) + '&img=' + encodeURIComponent(event.image) + '&date=' + encodeURIComponent(event.date) + '&id=' + encodeURIComponent(event.id);

                grid.innerHTML += `
                    <div class="flyer-card">
                        <div class="flyer-header-text">${titulo}</div>

                        <div class="img-wrapper" onclick="openModal('${event.image}', '${titulo.replace(/'/g, "\\'")}')">
                            <img src="${event.image}" alt="${titulo}">
                        </div>

                        <div style="padding: 15px; text-align: center; color: #d4af37; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                            ${fechaFormateada}
                        </div>

                        <div style="padding: 0 10px 20px 10px; text-align: center;">
                            <a href="${urlCompra}" class="btn-ticket">
                                COMPRAR ENTRADA
                            </a>

                            <a href="<?= BASE_URL ?>reservas?evento=${encodeURIComponent(titulo)}"
                               style="display:block; color:#888; text-decoration:none; font-size:0.7rem; margin-top:10px;">
                               RESERVAR MESA VIP
                            </a>
                        </div>
                    </div>
                `;
            });

        } catch (error) {
            console.error("Error cargando fechas:", error.message);

            document.getElementById('flyersGrid').innerHTML =
                "<p style='color: red; text-align:center; grid-column:1/-1;'>Error al conectar con el servidor.</p>";
        }
    }

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

    loadAllEvents();
</script>
<script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</body>
</html>