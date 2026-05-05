<?php
session_start();

// --- LÓGICA DE LOGIN ---
if (isset($_POST['password'])) {
    if ($_POST['password'] === "LUNA2026") {
        $_SESSION['admin_logged'] = true;
    } else {
        $error = "Contraseña incorrecta";
    }
}

// --- LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$is_logged = isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="style.css">
     <style>
    body { 
        background: #000; 
        color: white; 
        font-family: 'Segoe UI', Roboto, sans-serif; 
        margin: 0; 
        padding: 0;
    }

    .admin-panel { 
        padding: 20px; 
        max-width: 1000px; 
        margin: 0 auto; 
    }

    .login-screen { 
        height: 100vh; 
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
        align-items: center; 
        padding: 20px;
    }

    .admin-card { 
        background: #111; 
        padding: 20px; 
        border: 1px solid #333; 
        margin-bottom: 20px; 
        border-radius: 8px; 
        box-sizing: border-box;
        width: 100%;
    }

    input, select { 
        width: 100%; 
        padding: 14px; 
        margin: 10px 0; 
        background: #000; 
        color: white; 
        border: 1px solid #444; 
        box-sizing: border-box; 
        font-size: 16px;
    }

    button { 
        padding: 12px 15px; 
        font-weight: bold; 
        border: none; 
        cursor: pointer; 
        transition: 0.3s; 
        border-radius: 4px; 
        font-size: 14px;
        margin: 5px 0;
    }

    .item-list { 
        border-bottom: 1px solid #222; 
        padding: 15px 0; 
        display: flex; 
        flex-direction: row;
        justify-content: space-between; 
        align-items: center; 
        gap: 15px;
    }

    .item-list img { 
        width: 60px; 
        height: 60px; 
        object-fit: cover; 
        border-radius: 4px; 
    }

    .item-info { 
        flex-grow: 1; 
        color: white; 
    }

    .item-info strong { display: block; font-size: 1.1rem; }
    .item-info small { color: #888; display: block; font-size: 0.85rem; margin-top: 3px; }

    .btn-accept { background: #0088ff; color: white; width: auto; }
    .btn-pay { background: #28a745; color: white; width: auto; }
    .btn-delete { background: #ff3131; color: white; width: auto; }
    .btn-hide { background: #d4af37; color: #000; width: auto; }
    .btn-show { background: #00c853; color: #fff; width: auto; }

    .status-badge { 
        font-weight: bold; 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        margin-top: 6px; 
        display: inline-block; 
        padding: 2px 6px;
        background: rgba(255,255,255,0.05);
        border-radius: 3px;
    }

    .admin-menu {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .menu-btn {
        background: #111;
        color: white;
        border: 1px solid #333;
        padding: 12px 20px;
    }

    .menu-btn.active {
        background: white;
        color: black;
    }

    .admin-section {
        display: none;
    }

    .admin-section.active-section {
        display: block;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .stat-box {
        background: #000;
        border: 1px solid #333;
        padding: 20px;
        text-align: center;
        border-radius: 8px;
    }

    .stat-box span {
        display: block;
        color: #888;
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .stat-box strong {
        font-size: 2rem;
        color: #fff;
    }

    .stats-event-card {
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #222;
        padding: 15px 0;
        cursor: pointer;
    }

    .stats-event-card:hover {
        background: #181818;
    }

    .stats-event-card img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
    }

    @media (max-width: 600px) {
        .admin-panel { padding: 15px; }
        
        h1 { font-size: 1.5rem; text-align: center; margin-top: 50px; }

        a[href="?logout=1"] {
            float: none;
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        .item-list { 
            flex-direction: column; 
            align-items: flex-start; 
            text-align: left;
            position: relative;
        }

        .item-list img { width: 100%; height: 120px; }

        .item-info { width: 100%; }

        .item-list div[style*="display:flex"] {
            width: 100%;
            flex-direction: column;
            gap: 8px;
        }

        button { width: 100%; margin-left: 0; padding: 15px; }

        .btn-delete {
            margin-top: 10px;
        }

        .admin-menu {
            flex-direction: column;
        }

        .menu-btn {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stats-event-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-event-card img {
            width: 100%;
            height: 140px;
        }
    }
</style>
</head>
<body>

    <?php if (!$is_logged): ?>
    <div class="login-screen">
       <a href="index.php">
        <img src="img/logo/logo Luna.png" style="width: 150px; margin-bottom: 20px;" alt="Logo Luna">
         </a>
        <form method="POST" class="admin-card" style="width: 300px;">
            <h2 style="text-align: center; letter-spacing: 2px;">ACCESO ADMIN</h2>
            <?php if (isset($error)) echo "<p style='color: #ff3131; text-align:center;'>$error</p>"; ?>
            <input type="password" name="password" placeholder="Contraseña">
            <button type="submit" style="width: 100%; background: white; color: black;">ENTRAR</button>
        </form>
    </div>

    <?php else: ?>
    <div class="admin-panel">
        <a href="?logout=1" style="background:#ff3131; color:white; padding:5px 15px; float:right; text-decoration:none; border-radius:4px;">CERRAR SESIÓN</a>
        <h1 style="margin-bottom: 40px;">PANEL DE GESTIÓN</h1>

        <nav class="admin-menu">
            <button onclick="mostrarSeccion('albumes', this)" class="menu-btn active">Álbumes</button>
            <button onclick="mostrarSeccion('eventos', this)" class="menu-btn">Carteles</button>
            <button onclick="mostrarSeccion('reservas', this)" class="menu-btn">Reservas</button>
            <button onclick="mostrarSeccion('estadisticas', this)" class="menu-btn">Estadísticas eventos</button>
        </nav>

        <section id="albumes" class="admin-section active-section">
            <div class="admin-card">
                <h3>NUEVO ÁLBUM (VINCULAR CARPETA)</h3>
                <input type="text" id="albumTitle" placeholder="Nombre para mostrar (Ej: LUNA X LATINEO)">
                <input type="text" id="albumId" placeholder="Nombre de la carpeta (Ej: LUNA_LATINEO)">
                <input type="file" id="photoFiles" accept="image/*">
                <button onclick="uploadAlbum()">PUBLICAR ÁLBUM</button>
            </div>

            <div class="admin-card">
                <h3>ÁLBUMES ACTIVOS</h3>
                <div id="listaAlbumes">Cargando...</div>
            </div>
        </section>

        <section id="eventos" class="admin-section">
            <div class="admin-card">
                <h3>NUEVO CARTEL (PRÓXIMAS FECHAS)</h3>
                <input type="text" id="eventStatus" placeholder="Título del evento">
                <input type="date" id="eventDate">
                <input type="file" id="eventFlyer" accept="image/*">
                <button onclick="uploadEvent()">PUBLICAR CARTEL</button>
            </div>

            <div class="admin-card">
                <h3>CARTELES ACTIVOS</h3>
                <p style="color:#888;">Desde aquí puedes ocultar o mostrar eventos futuros sin borrarlos.</p>
                <div id="eventList">Cargando...</div>
            </div>
        </section>

        <section id="reservas" class="admin-section">
            <div class="admin-card">
                <h3>GESTIÓN DE RESERVAS</h3>
                <div id="listaReservas">Cargando...</div>
            </div>
        </section>

        <section id="estadisticas" class="admin-section">
            <div class="admin-card">
                <h3>ESTADÍSTICAS DE EVENTOS</h3>
                <p style="color:#888;">Pulsa sobre un cartel publicado para ver sus estadísticas.</p>
                <div id="statsEventList">Cargando eventos...</div>
            </div>

            <div class="admin-card" id="statsPanel" style="display:none;">
                <h3 id="statsTitle">Estadísticas</h3>

                <div class="stats-grid">
                    <div class="stat-box">
                        <span>Entradas vendidas</span>
                        <strong id="statVendidas">0</strong>
                    </div>

                    <div class="stat-box">
                        <span>Entradas restantes</span>
                        <strong id="statRestantes">0</strong>
                    </div>

                    <div class="stat-box">
                        <span>Dinero recaudado</span>
                        <strong id="statDinero">0 €</strong>
                    </div>

                    <div class="stat-box">
                        <span>Reservas VIP</span>
                        <strong id="statReservas">0</strong>
                    </div>
                </div>

                <div id="soldOutBox" style="display:none; margin-top:20px; padding:20px; background:#ff3131; color:white; text-align:center; font-weight:bold; border-radius:8px;">
                    SOLD OUT
                </div>
            </div>
        </section>
    </div>

    <script>
        function mostrarSeccion(id, boton) {
            document.querySelectorAll('.admin-section').forEach(section => {
                section.classList.remove('active-section');
            });

            document.querySelectorAll('.menu-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(id).classList.add('active-section');
            boton.classList.add('active');
        }

        function esEventoPasado(fecha) {
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            const fechaEvento = new Date(fecha);
            fechaEvento.setHours(0, 0, 0, 0);

            return fechaEvento < hoy;
        }

        function cargarEventosEstadisticas(events) {
            const contenedor = document.getElementById('statsEventList');

            if (!contenedor) return;

            if (!events || events.length === 0) {
                contenedor.innerHTML = "<p style='color:#888;'>No hay eventos publicados.</p>";
                return;
            }

            contenedor.innerHTML = events.map(e => {
                const titulo = e.title || e.status;
                const oculto = e.oculto == 1 || e.hidden == 1;
                const estadoTexto = oculto ? 'OCULTO' : 'VISIBLE';

                return `
                    <div class="stats-event-card" onclick="abrirEstadisticasEvento(${e.id}, '${titulo.replace(/'/g, "\\'")}')">
                        <img src="${e.image}">
                        <div class="item-info">
                            <strong>${titulo}</strong>
                            <small>${e.date}</small>
                            <span class="status-badge" style="color:${oculto ? '#d4af37' : '#00ff00'}">${estadoTexto}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function abrirEstadisticasEvento(eventId, titulo) {
            try {
                const res = await fetch(`api/get_event_stats.php?id=${eventId}&t=${Date.now()}`);
                const data = await res.json();

                if (!data.success) {
                    alert(data.message || "No se pudieron cargar las estadísticas.");
                    return;
                }

                document.getElementById('statsPanel').style.display = 'block';
                document.getElementById('statsTitle').innerText = 'Estadísticas - ' + titulo;

                document.getElementById('statVendidas').innerText = data.vendidas;
                document.getElementById('statRestantes').innerText = data.restantes;
                document.getElementById('statDinero').innerText = data.recaudado + ' €';
                document.getElementById('statReservas').innerText = data.reservas_vip;

                if (data.restantes <= 0) {
                    document.getElementById('soldOutBox').style.display = 'block';
                } else {
                    document.getElementById('soldOutBox').style.display = 'none';
                }

            } catch (error) {
                alert("Error cargando estadísticas del evento.");
                console.error(error);
            }
        }

        async function toggleEventoVisible(id, ocultar) {
            try {
                const accion = ocultar ? 1 : 0;
                const res = await fetch(`api/toggle_event_visibility.php?id=${id}&oculto=${accion}&t=${Date.now()}`);
                const data = await res.json();

                if (data.success) {
                    loadAllData();
                } else {
                    alert(data.message || "No se pudo cambiar la visibilidad.");
                }
            } catch (error) {
                alert("Error al cambiar la visibilidad del evento.");
                console.error(error);
            }
        }

        // --- CARGA DE DATOS ---
        async function loadAllData() {
            try {
                const res = await fetch('api/get_all.php?t=' + Date.now());
                const data = await res.json();
                
                const albums = data.albums || [];
                const events = data.events || [];
                const reservas = data.reservas || [];

                cargarEventosEstadisticas(events);

                document.getElementById('listaAlbumes').innerHTML = albums.length > 0 
                    ? albums.map(a => `
                        <div class="item-list">
                            <img src="${a.cover}">
                            <div class="item-info"><strong>${a.title}</strong><small>Carpeta: ${a.id}</small></div>
                            <button class="btn-delete" onclick="deleteItem('${a.id}', 'album')">BORRAR</button>
                        </div>
                    `).join('')
                    : "<p style='color:#888;'>No hay álbumes creados.</p>";

                document.getElementById('eventList').innerHTML = events.length > 0
                    ? events.map(e => {
                        const titulo = e.title || e.status;
                        const oculto = e.oculto == 1 || e.hidden == 1;
                        const pasado = esEventoPasado(e.date);

                        let botonVisibilidad = '';

                        if (!pasado) {
                            if (oculto) {
                                botonVisibilidad = `<button class="btn-show" onclick="toggleEventoVisible(${e.id}, false)">MOSTRAR</button>`;
                            } else {
                                botonVisibilidad = `<button class="btn-hide" onclick="toggleEventoVisible(${e.id}, true)">OCULTAR</button>`;
                            }
                        } else {
                            botonVisibilidad = `<span style="color:#888; font-size:0.8rem; font-weight:bold;">EVENTO PASADO</span>`;
                        }

                        return `
                            <div class="item-list">
                                <img src="${e.image}">
                                <div class="item-info">
                                    <strong>${titulo}</strong>
                                    <small>${e.date}</small>
                                    <span class="status-badge" style="color:${oculto ? '#d4af37' : '#00ff00'}">
                                        ${oculto ? 'OCULTO' : 'VISIBLE'}
                                    </span>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    ${botonVisibilidad}
                                    <button class="btn-delete" onclick="deleteItem(${e.id}, 'event')">BORRAR</button>
                                </div>
                            </div>
                        `;
                    }).join('')
                    : "<p style='color:#888;'>No hay carteles activos.</p>";

                document.getElementById('listaReservas').innerHTML = reservas.length > 0
                    ? reservas.map(r => {
                        let colorStatus = r.estado === 'Pendiente' ? 'gold' : (r.estado === 'Confirmada' ? '#0088ff' : '#00ff00');
                        let actionBtn = '';
                        
                        if(r.estado === 'Pendiente') actionBtn = `<button class="btn-accept" onclick="updateReserva(${r.id}, 'aceptar')">ACEPTAR</button>`;
                        if(r.estado === 'Confirmada') actionBtn = `<button class="btn-pay" onclick="updateReserva(${r.id}, 'pagar')">PAGADO</button>`;
                        if(r.estado === 'Pagado') actionBtn = `<span style="color:#00ff00; margin-right:10px; font-weight:bold;">✓ LISTO</span>`;

                        return `
                            <div class="item-list">
                                <div class="item-info">
                                    <strong>${r.nombre} (${r.personas} pers.)</strong>
                                    <small>${r.fecha} | ${r.telefono}</small>
                                    <span class="status-badge" style="color:${colorStatus}">${r.estado}</span>
                                </div>
                                <div style="display:flex; align-items:center;">
                                    ${actionBtn}
                                    <button class="btn-delete" onclick="deleteItem(${r.id}, 'reserva')">X</button>
                                </div>
                            </div>
                        `;
                    }).join('')
                    : "<p style='color:#888;'>No hay reservas registradas.</p>";

            } catch (e) { 
                console.error("Error en la carga de datos:", e); 
            }
        }

        async function updateReserva(id, action) {
            try {
                await fetch(`api/update_reserva.php?id=${id}&action=${action}`);
                loadAllData();
            } catch (error) { console.error(error); }
        }

        async function deleteItem(id, type) {
            if(confirm("¿Seguro de eliminar este " + type + "?")) {
                try {
                    await fetch(`api/delete_item.php?id=${id}&type=${type}`);
                    loadAllData();
                } catch (error) { console.error(error); }
            }
        }

        async function uploadAlbum() {
            const title = document.getElementById('albumTitle').value;
            const albumId = document.getElementById('albumId').value;
            const fileInput = document.getElementById('photoFiles');

            if (!title || !albumId || !fileInput.files[0]) return alert("Completa todos los campos.");

            const formData = new FormData();
            formData.append('title', title);
            formData.append('albumId', albumId);
            formData.append('cover', fileInput.files[0]);

            try {
                const res = await fetch('api/upload_album.php', { method: 'POST', body: formData });
                
                if (!res.ok) {
                    console.error("Estado del servidor:", res.status);
                    throw new Error("El archivo api/upload_album.php no existe o hay un error 500");
                }
                
                const result = await res.json();
                if (result.success) {
                    alert("Álbum creado.");
                    location.reload(); 
                } else {
                    alert("Error: " + (result.error || result.message));
                }
            } catch (error) {
                alert("Error: No se pudo contactar con la API.");
                console.error(error);
            }
        }

        async function uploadEvent() {
            const statusInput = document.getElementById('eventStatus');
            const dateInput = document.getElementById('eventDate');
            const fileInput = document.getElementById('eventFlyer');

            if (!fileInput.files[0] || !dateInput.value) return alert("Faltan datos.");

            const formData = new FormData();
            formData.append('status', statusInput.value);
            formData.append('date', dateInput.value);
            formData.append('flyer', fileInput.files[0]);

            try {
                const res = await fetch('api/upload_event.php', { method: 'POST', body: formData });
                const result = await res.json();
                if (result.success) {
                    alert("Cartel publicado.");
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                alert("Error al subir cartel. Revisa la consola.");
                console.error(error);
            }
        }

        loadAllData();
    </script>
    <?php endif; ?>
</body>
</html>