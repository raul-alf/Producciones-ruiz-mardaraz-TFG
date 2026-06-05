<?php // reservas.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cafe Pub La Luna - Reservas</title>
<link rel="stylesheet" href="css/style.css">

<style>
body {
    background-color: #000;
    color: #fff;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.logo-externo {
    text-align: center;
    margin-top: 140px;
    margin-bottom: 20px;
    width: 100%;
}

.logo-externo img {
    max-width: 220px;
    height: auto;
}

.eventos-reserva {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
}

.evento-card {
    background: #111;
    border: 1px solid #333;
    cursor: pointer;
    transition: 0.3s;
    overflow: hidden;
    border-radius: 10px;
}

.evento-card:hover {
    border-color: #d4af37;
    transform: translateY(-5px);
}

.evento-card img {
    width: 100%;
    height: auto;
    max-height: 700px;
    object-fit: contain;
    display: block;
    background: #000;
}

.evento-card-info {
    padding: 15px;
    text-align: center;
}

.evento-card-info h3 {
    color: white;
    margin: 0 0 10px 0;
    text-transform: uppercase;
}

.evento-card-info p {
    color: #d4af37;
    margin: 0;
    font-size: 0.85rem;
    text-transform: uppercase;
}

.btn-sin-evento {
    display: block;
    margin: 40px auto 80px auto;
    padding: 15px 30px;
    background: white;
    color: black;
    border: none;
    font-weight: bold;
    cursor: pointer;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.reserva-container {
    display: none;
    max-width: 760px;
    margin: 0 auto 100px auto;
    padding: 40px;
    background: #0a0a0a;
    border: 1px solid #222;
    border-radius: 14px;
    box-shadow: 0 14px 60px rgba(0,0,0,0.85);
}

.info-vip {
    text-align: center;
    margin-bottom: 30px;
}

.info-vip h1 {
    font-size: 1.8rem;
    letter-spacing: 3px;
    margin-bottom: 10px;
}

.info-vip p {
    color: #888;
    font-size: 0.9rem;
}

#imgEventoSeleccionado {
    width: 100%;
    max-height: 520px;
    object-fit: contain;
    margin-top: 20px;
    display: none;
    border-radius: 8px;
    border: 1px solid #333;
    background: #000;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    color: #888;
    margin-bottom: 8px;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

input, select {
    width: 100%;
    padding: 12px;
    background: #000;
    border: 1px solid #333;
    color: #fff;
    border-radius: 4px;
    font-size: 1rem;
    box-sizing: border-box;
}

input:focus, select:focus {
    border-color: #fff;
    outline: none;
}

.btn-reserva {
    width: 100%;
    padding: 15px;
    background: #fff;
    color: #000;
    border: none;
    font-weight: bold;
    letter-spacing: 2px;
    cursor: pointer;
    transition: 0.3s;
    text-transform: uppercase;
    margin-top: 10px;
}

.btn-reserva:hover {
    background: #ddd;
    transform: scale(1.01);
}

.btn-volver {
    width: 100%;
    padding: 12px;
    background: transparent;
    color: #888;
    border: 1px solid #333;
    margin-top: 15px;
    cursor: pointer;
}

.btn-volver:hover {
    color: #fff;
    border-color: #fff;
}

@media (max-width: 900px) {
    .eventos-reserva {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .logo-externo {
        margin-top: 100px;
    }

    .logo-externo img {
        max-width: 160px;
    }

    .reserva-container {
        margin: 0 15px 50px 15px;
        padding: 30px 20px;
    }

    .eventos-reserva {
        grid-template-columns: 1fr;
    }

    .evento-card img {
        max-height: none;
    }
}
</style>
</head>

<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="<?= BASE_URL ?>index">
                <img src="<?= BASE_URL ?>img/logo/Luna_fondo_negro.png" alt="Luna Logo">
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>index">INICIO</a></li>
            <li><a href="<?= BASE_URL ?>galeria">GALERÍA</a></li>
            <li><a href="<?= BASE_URL ?>fechas">PRÓXIMAS FECHAS</a></li>
            <li><a href="<?= BASE_URL ?>reservas" class="active">RESERVAS</a></li>
        </ul>
    </div>
</nav>

<div class="logo-externo">
    <img src="<?= BASE_URL ?>img/logo/logo Luna.png" alt="Logo La Luna">
</div>

<div class="eventos-reserva" id="eventosReserva">
    <p style="grid-column:1/-1; text-align:center; color:#888;">
        Cargando eventos disponibles...
    </p>
</div>

<button class="btn-sin-evento" onclick="reservarSinEvento()">
    RESERVAR EN FECHA SIN EVENTO
</button>

<div class="reserva-container" id="formContainer">
    <div class="info-vip">
        <h1>RESERVA TU MESA</h1>
        <p id="tituloEvento">Selecciona evento</p>
        <img id="imgEventoSeleccionado" alt="Cartel del evento seleccionado">
    </div>

    <form id="formReserva">
        <div class="form-group">
            <label>NOMBRE COMPLETO</label>
            <input type="text" id="resNombre" required placeholder="Ej: Juan Pérez">
        </div>

        <div class="form-group">
            <label>FECHA DEL EVENTO</label>
            <input type="date" id="resFecha" required>
        </div>

        <div class="form-group">
            <label>Nº DE PERSONAS</label>
            <select id="resPersonas" required>
                <option value="2-4">2 a 4 personas</option>
                <option value="5-8">5 a 8 personas</option>
                <option value="10+">Más de 10 personas</option>
            </select>
        </div>

        <div class="form-group">
            <label>TELÉFONO DE CONTACTO</label>
            <input type="tel" id="resTel" required placeholder="+34 600 000 000">
        </div>

        <button type="submit" class="btn-reserva">
            SOLICITAR RESERVA
        </button>
    </form>

    <button type="button" class="btn-volver" onclick="volverEventos()">
        VOLVER A EVENTOS
    </button>

    <div id="msgExito" style="display:none; text-align:center; margin-top:20px;">
        <p style="color:#00ff00; font-weight:bold;">
            ¡Reserva enviada con éxito!
        </p>

        <p style="color:#888; font-size:0.9rem;">
            Nos pondremos en contacto contigo por teléfono para confirmar.
        </p>

        <a href="<?= BASE_URL ?>index" style="color:#fff; text-decoration:underline; font-size:0.8rem;">
            Volver al inicio
        </a>
    </div>
</div>

<script>
async function cargarEventos() {
    try {
        const res = await fetch('<?= BASE_URL ?>api/events?t=' + Date.now());
        const events = await res.json();

        const contenedor = document.getElementById('eventosReserva');

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        const eventosFiltrados = events.filter(e => {
            const fechaEvento = new Date(e.date);
            fechaEvento.setHours(0, 0, 0, 0);

            const oculto = Number(e.oculto) === 1 || Number(e.hidden) === 1;

            return fechaEvento >= hoy && !oculto;
        });

        if (eventosFiltrados.length === 0) {
            contenedor.innerHTML = `
                <p style="grid-column:1/-1; text-align:center; color:#888;">
                    No hay eventos disponibles para reservar.
                </p>
            `;
            return;
        }

        eventosFiltrados.sort((a, b) => new Date(a.date) - new Date(b.date));

        contenedor.innerHTML = eventosFiltrados.map(e => {
            const titulo = e.title || e.status || 'Evento Luna';

            const fechaFormateada = new Date(e.date).toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });

            const tituloSeguro = titulo.replace(/'/g, "\\'");
            const imagenSeguro = e.image ? e.image.replace(/'/g, "\\'") : '';

            return `
                <div class="evento-card" onclick="seleccionarEvento('${e.date}', '${tituloSeguro}', '${imagenSeguro}')">
                    <img src="${e.image}" alt="${titulo}">
                    <div class="evento-card-info">
                        <h3>${titulo}</h3>
                        <p>${fechaFormateada}</p>
                    </div>
                </div>
            `;
        }).join('');

    } catch (error) {
        document.getElementById('eventosReserva').innerHTML = `
            <p style="grid-column:1/-1; text-align:center; color:red;">
                Error al cargar eventos.
            </p>
        `;
        console.error(error);
    }
}

function seleccionarEvento(fecha, titulo, img) {
    document.getElementById('eventosReserva').style.display = 'none';
    document.querySelector('.btn-sin-evento').style.display = 'none';
    document.getElementById('formContainer').style.display = 'block';

    document.getElementById('resFecha').value = fecha;
    document.getElementById('tituloEvento').innerText = titulo;

    const imagen = document.getElementById('imgEventoSeleccionado');

    if (img) {
        imagen.src = img;
        imagen.style.display = 'block';
    } else {
        imagen.src = '';
        imagen.style.display = 'none';
    }

    document.getElementById('formReserva').style.display = 'block';
    document.getElementById('msgExito').style.display = 'none';

    window.scrollTo({
        top: document.getElementById('formContainer').offsetTop - 100,
        behavior: 'smooth'
    });
}

function reservarSinEvento() {
    document.getElementById('eventosReserva').style.display = 'none';
    document.querySelector('.btn-sin-evento').style.display = 'none';
    document.getElementById('formContainer').style.display = 'block';

    document.getElementById('tituloEvento').innerText = 'Reserva sin evento';
    document.getElementById('resFecha').value = '';

    const imagen = document.getElementById('imgEventoSeleccionado');
    imagen.src = '';
    imagen.style.display = 'none';

    document.getElementById('formReserva').style.display = 'block';
    document.getElementById('msgExito').style.display = 'none';

    window.scrollTo({
        top: document.getElementById('formContainer').offsetTop - 100,
        behavior: 'smooth'
    });
}

function volverEventos() {
    document.getElementById('eventosReserva').style.display = 'grid';
    document.querySelector('.btn-sin-evento').style.display = 'block';
    document.getElementById('formContainer').style.display = 'none';

    document.getElementById('formReserva').reset();
    document.getElementById('formReserva').style.display = 'block';
    document.getElementById('msgExito').style.display = 'none';

    window.scrollTo({
        top: document.getElementById('eventosReserva').offsetTop - 100,
        behavior: 'smooth'
    });
}

document.getElementById('formReserva').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData();

    formData.append('nombre', document.getElementById('resNombre').value);
    formData.append('fecha', document.getElementById('resFecha').value);
    formData.append('personas', document.getElementById('resPersonas').value);
    formData.append('telefono', document.getElementById('resTel').value);
    formData.append('evento', document.getElementById('tituloEvento').innerText);

    try {
        const response = await fetch('<?= BASE_URL ?>api/crear-reserva', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('formReserva').style.display = 'none';
            document.getElementById('msgExito').style.display = 'block';
        } else {
            alert('Error: ' + result.message);
        }

    } catch (error) {
        alert('No se pudo conectar con el servidor.');
    }
});

cargarEventos();
</script>

</body>
</html>