<?php
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location:' . BASE_URL . '/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
       <style>
body {
    background: #000;
    color: white;
    font-family: 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
}

.admin-panel {
    max-width: 1600px;
    margin: 0 auto;
    padding: 45px 30px 80px;
    box-sizing: border-box;
}

.logout-btn {
    float: right;
    background: #ff3131;
    color: white;
    padding: 8px 20px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
}

.admin-panel h1 {
    font-size: 2rem;
    margin-bottom: 45px;
    letter-spacing: 1px;
}

.admin-menu {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.menu-btn {
    background: #111;
    color: white;
    border: 1px solid #333;
    padding: 14px 22px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
}

.menu-btn.active {
    background: white;
    color: black;
}

.admin-card {
    background: #111;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
}

.admin-card h3 {
    margin-top: 0;
    margin-bottom: 22px;
    font-size: 1.2rem;
}

input,
select {
    width: 100%;
    padding: 15px;
    margin-bottom: 18px;
    background: #000;
    color: white;
    border: 1px solid #333;
    box-sizing: border-box;
    font-size: 1rem;
}

button {
    padding: 12px 18px;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
}

.item-list {
    border-bottom: 1px solid #222;
    padding: 16px 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

    .item-list img,
    .stats-event-card img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .stats-event-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #222;
        cursor: pointer;
    }

    .stats-event-card .item-info {
        flex: 1;
    }

    .stats-event-card strong {
        display: block;
        margin-bottom: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        letter-spacing: 0.02em;
        background: rgba(255, 255, 255, 0.08);
    font-size: 1.05rem;
}

.item-info small {
    color: #888;
}

.btn-delete {
    background: #ff3131;
    color: white;
}

.btn-hide {
    background: #d4af37;
    color: black;
}

.btn-show {
    background: #00c853;
    color: white;
}

.btn-accept {
    background: #0088ff;
    color: white;
}

.btn-pay {
    background: #28a745;
    color: white;
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

#statsPanel {
    width: 100%;
}

.chart-card {
    background: #08101a;
    border: 1px solid #22303f;
    border-radius: 14px;
    padding: 25px;
    margin: 0 0 30px 0;
    width: 100%;
    box-sizing: border-box;
}

#main {
    width: 100%;
    height: 760px;
    min-height: 760px;
}

.chart-card h4 {
    margin: 0 0 20px;
    color: #fff;
    font-size: 1.3rem;
    text-align: center;
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
}

@media (max-width: 700px) {
    .logout-btn {
        float: none;
        display: block;
        text-align: center;
        margin-bottom: 25px;
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

    #main {
        height: 520px;
        min-height: 520px;
    }

    .chart-card {
        padding: 16px;
    }

    .item-list {
        flex-direction: column;
        align-items: flex-start;
    }

    .item-list img {
        width: 100%;
        height: 140px;
    }
}
</style>

</head>

<body>

<div class="admin-panel">
        <a href="<?= BASE_URL ?>logout" style="background:#ff3131; color:white; padding:5px 15px; float:right; text-decoration:none; border-radius:4px;">CERRAR SESIÓN</a>
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
                
                <div class="chart-card">
                    <h4>Entradas vendidas del total</h4>
                    <div id="chartEntradas" style="width:100%; height:400px;"></div>
                </div>

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

                <div id="editEntradasPanel" style="margin-top:20px; padding:20px; background:#222; border:1px solid #333; border-radius:8px;">
                    <h4 style="color:#d4af37; margin-top:0; margin-bottom:15px;">Modificar detalles del evento</h4>
                    <div style="display:grid; grid-template-columns:1fr auto; gap:10px; align-items:flex-end;">
                        <div style="display:grid; gap:10px;">
                            <div>
                                <label style="display:block; color:#888; font-size:0.85em; margin-bottom:8px;">Total de entradas para este evento</label>
                                <input type="number" id="inputEntradasTotales" placeholder="Número de entradas" style="width:100%; padding:10px; background:#000; border:1px solid #444; color:white; border-radius:4px;">
                            </div>
                            <div>
                                <label style="display:block; color:#888; font-size:0.85em; margin-bottom:8px;">Precio por entrada (€)</label>
                                <input type="number" step="0.01" id="inputPrecioEvento" placeholder="Precio del evento" style="width:100%; padding:10px; background:#000; border:1px solid #444; color:white; border-radius:4px;">
                            </div>
                        </div>
                        <div style="display:grid; gap:10px;">
                            <button onclick="guardarEntradasTotales()" style="padding:10px 20px; background:#d4af37; color:#000; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Guardar Entradas</button>
                            <button onclick="guardarPrecioEvento()" style="padding:10px 20px; background:#0088ff; color:#fff; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Guardar Precio</button>
                        </div>
                    </div>
                    <small style="color:#666; display:block; margin-top:10px;">Nota: El precio se usa en las ventas y se aplica al evento seleccionado.</small>
                </div>

                <div id="compradoresPanel" style="margin-top:30px;">
                    <h4 style="color:#d4af37; margin-bottom:15px; font-size:1.1em;">Listado de Compradores</h4>
                    <div id="compradoresTable" style="overflow-x: auto;">
                        <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                            <thead>
                                <tr style="background:#222; border-bottom:2px solid #d4af37;">
                                    <th style="padding:10px; text-align:left; color:#d4af37;">Referencia</th>
                                    <th style="padding:10px; text-align:left; color:#d4af37;">Email</th>
                                    <th style="padding:10px; text-align:right; color:#d4af37;">Precio</th>
                                    <th style="padding:10px; text-align:left; color:#d4af37;">Fecha</th>
                                    <th style="padding:10px; text-align:center; color:#d4af37;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="compradoresList">
                                <tr><td colspan="5" style="padding:20px; text-align:center; color:#888;">Cargando compradores...</td></tr>
                            </tbody>
                        </table>
                    </div>
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

            if (id === 'estadisticas' && dashboardChart) {
                setTimeout(() => dashboardChart.resize(), 200);
            }
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

        let dashboardChart;
        let entradasChart;
        let currentEventId = null;

        function crearGraficoEntradas(vendidas, restantes) {
            const chartDom = document.getElementById('chartEntradas');
            if (!chartDom) return;

            if (!entradasChart) {
                entradasChart = echarts.init(chartDom);
            }

            const totalEntradas = vendidas + restantes;
            const porcentajeVendidas = totalEntradas > 0 ? (vendidas / totalEntradas * 100).toFixed(1) : 0;
            const porcentajeRestantes = totalEntradas > 0 ? (restantes / totalEntradas * 100).toFixed(1) : 0;

            const option = {
                backgroundColor: '#000',
                tooltip: {
                    trigger: 'item',
                    formatter: (params) => {
                        if (params.data.name === 'Vendidas') {
                            return `${params.data.name}: ${params.data.value} entradas (${porcentajeVendidas}%)`;
                        } else {
                            return `${params.data.name}: ${params.data.value} entradas (${porcentajeRestantes}%)`;
                        }
                    }
                },
                legend: {
                    bottom: '8%',
                    left: 'center',
                    textStyle: {
                        color: '#ddd',
                        fontSize: 12
                    },
                    data: ['Vendidas', 'Disponibles']
                },
                series: [
                    {
                        name: 'Entradas',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        center: ['50%', '50%'],
                        itemStyle: {
                            borderRadius: 8,
                            borderColor: '#000',
                            borderWidth: 2
                        },
                        label: {
                            color: '#fff',
                            fontSize: 14,
                            formatter: (params) => {
                                if (params.data.name === 'Vendidas') {
                                    return `${params.data.name}\n${params.data.value}\n(${porcentajeVendidas}%)`;
                                } else {
                                    return `${params.data.name}\n${params.data.value}\n(${porcentajeRestantes}%)`;
                                }
                            }
                        },
                        labelLine: {
                            lineStyle: {
                                color: '#666'
                            }
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 20,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        data: [
                            {
                                value: vendidas,
                                name: 'Vendidas',
                                itemStyle: {
                                    color: '#00ff00'
                                }
                            },
                            {
                                value: restantes,
                                name: 'Disponibles',
                                itemStyle: {
                                    color: '#ff3131'
                                }
                            }
                        ]
                    }
                ]
            };

            entradasChart.setOption(option);
            setTimeout(() => {
                entradasChart.resize();
            }, 200);
        }

        async function cargarGraficoDashboard() {
            try {
                const res = await fetch(`<?= BASE_URL ?>api/dashboard_chart_data.php?t=${Date.now()}`);
                const data = await res.json();

                if (!data.success) {
                    console.error('Error al cargar datos del gráfico:', data.message);
                    return;
                }

                const chartData = (data.data || []).map(item => ({
                    value: parseInt(item.total, 10) || 0,
                    name: item.estado || 'Sin estado'
                }));

                if (!chartData.length) {
                    chartData.push({ value: 1, name: 'Sin datos' });
                }

                const chartDom = document.getElementById('main');
                if (!chartDom) return;

                if (!dashboardChart) {
                    dashboardChart = echarts.init(chartDom);
                }

                const option = {
                    backgroundColor: '#000',
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}: {c} ({d}%)'
                    },
                    legend: {
                        top: '8%',
                        left: 'center',
                        textStyle: {
                            color: '#ddd'
                        }
                    },
                    series: [
                        {
                            name: 'Reservas',
                            type: 'pie',
                            radius: ['55%', '90%'],
                            center: ['50%', '55%'],
                            startAngle: 180,
                            endAngle: 360,
                            roseType: 'area',
                            itemStyle: {
                                borderRadius: 8,
                                borderColor: '#111',
                                borderWidth: 2
                            },
                            label: {
                                color: '#fff',
                                fontSize: 15,
                                formatter: '{b}\n{c} ({d}%)'
                            },
                            labelLine: {
                                lineStyle: {
                                    color: '#666'
                                }
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 20,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            },
                            data: chartData
                        }
                    ]
                };

                dashboardChart.setOption(option);

                setTimeout(() => {
                    dashboardChart.resize();
                }, 200);
            } catch (error) {
                console.error('Error cargando datos del gráfico:', error);
            }
        }

        async function abrirEstadisticasEvento(eventId, titulo) {
            try {
                currentEventId = eventId;
                
                const res = await fetch(`<?= BASE_URL ?>api/event-stats?id=${eventId}&t=${Date.now()}`);
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
                document.getElementById('statReservas').innerText = data.reservas_vip || 0;

                // Cargar el número total de entradas en el input editable
                const totalEntradas = data.vendidas + data.restantes;
                document.getElementById('inputEntradasTotales').value = totalEntradas;
                document.getElementById('inputPrecioEvento').value = data.precio_entrada || '';

                // Crear gráfico de entradas vendidas
                crearGraficoEntradas(data.vendidas, data.restantes);

                if (data.restantes <= 0) {
                    document.getElementById('soldOutBox').style.display = 'block';
                } else {
                    document.getElementById('soldOutBox').style.display = 'none';
                }

                const compradoresList = document.getElementById('compradoresList');
                if (data.compradores && data.compradores.length > 0) {
                    compradoresList.innerHTML = data.compradores.map(c => {
                        if (c.tipo === 'compra') {
                            return `
                                <tr style="border-bottom:1px solid #333;">
                                    <td style="padding:10px; color:#d4af37; font-family:monospace; font-size:0.85em;" title="Compra">${c.order_id.substring(0, 12)}...</td>
                                    <td style="padding:10px; color:#ccc;">${c.email || 'N/A'}</td>
                                    <td style="padding:10px; text-align:right; color:#0f0;">${c.precio} €</td>
                                    <td style="padding:10px; color:#888; font-size:0.85em;">${c.fecha}</td>
                                    <td style="padding:10px; text-align:center;">
                                        <button onclick="descargarEntrada('${c.order_id}', '${c.email}')" style="padding:6px 12px; background:#0088ff; color:white; border:none; border-radius:4px; cursor:pointer; margin-right:5px; font-size:0.85em;">Descargar</button>
                                        <button onclick="eliminarCompra(${c.id}, '${c.order_id}')" style="padding:6px 12px; background:#ff3131; color:white; border:none; border-radius:4px; cursor:pointer; font-size:0.85em;">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                        }

                        return `
                            <tr style="border-bottom:1px solid #333;">
                                <td style="padding:10px; color:#ffb700; font-family:monospace; font-size:0.85em;" title="Reserva">RES-${c.id}</td>
                                <td style="padding:10px; color:#ccc;">${c.nombre || 'N/A'}</td>
                                <td style="padding:10px; text-align:right; color:#0f0;">${c.personas} personas</td>
                                <td style="padding:10px; color:#888; font-size:0.85em;">${c.telefono || 'N/A'}</td>
                                <td style="padding:10px; text-align:center;">
                                    <button onclick="eliminarReserva(${c.id})" style="padding:6px 12px; background:#ff3131; color:white; border:none; border-radius:4px; cursor:pointer; font-size:0.85em;">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    compradoresList.innerHTML = '<tr><td colspan="5" style="padding:20px; text-align:center; color:#888;">No hay compradores ni reservas aún.</td></tr>';
                }
            } catch (error) {
                alert("Error cargando estadísticas del evento.");
                console.error(error);
            }
        }

        async function guardarEntradasTotales() {
            if (!currentEventId) {
                alert("No hay evento seleccionado.");
                return;
            }

            const entradasTotales = document.getElementById('inputEntradasTotales').value;

            if (!entradasTotales || entradasTotales <= 0) {
                alert("Por favor ingresa un número válido de entradas.");
                return;
            }

            try {
                const res = await fetch(`<?= BASE_URL ?>api/update-entradas-totales.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        eventId: currentEventId,
                        entradasTotales: parseInt(entradasTotales)
                    })
                });

                if (!res.ok) {
                    console.error('HTTP Error:', res.status, res.statusText);
                    alert(`Error HTTP: ${res.status}`);
                    return;
                }

                const data = await res.json();
                console.log('Response:', data);

                if (!data.success) {
                    alert(data.message || "Error al guardar los cambios.");
                    return;
                }

                alert("Entradas totales actualizado correctamente.");
                // Recargar las estadísticas del evento
                abrirEstadisticasEvento(currentEventId, document.getElementById('statsTitle').innerText.replace('Estadísticas - ', ''));
            } catch (error) {
                console.error('Fetch error:', error);
                alert("Error al guardar los cambios: " + error.message);
            }
        }

        async function guardarPrecioEvento() {
            if (!currentEventId) {
                alert("No hay evento seleccionado.");
                return;
            }

            const precio = document.getElementById('inputPrecioEvento').value;

            if (!precio || precio <= 0) {
                alert("Por favor ingresa un precio válido.");
                return;
            }

            try {
                const res = await fetch(`<?= BASE_URL ?>api/update-event-price.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        eventId: currentEventId,
                        precio: parseFloat(precio)
                    })
                });

                if (!res.ok) {
                    console.error('HTTP Error:', res.status, res.statusText);
                    alert(`Error HTTP: ${res.status}`);
                    return;
                }

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || "Error al guardar el precio.");
                    return;
                }

                alert("Precio del evento actualizado correctamente.");
                abrirEstadisticasEvento(currentEventId, document.getElementById('statsTitle').innerText.replace('Estadísticas - ', ''));
            } catch (error) {
                console.error('Fetch error:', error);
                alert("Error al guardar el precio: " + error.message);
            }
        }

        async function toggleEventoVisible(id, ocultar) {
            try {
                const accion = ocultar ? 1 : 0;
                const res = await fetch(`<?= BASE_URL ?>api/toggle-event-visibility?id=${id}&oculto=${accion}&t=${Date.now()}`);
                const data = await res.json();

                if (!res.ok || !data.success) {
                    alert(data.message || "No se pudo cambiar la visibilidad.");
                    console.error('Toggle response error:', data);
                    return;
                }

                window.addEventListener('resize', () => {
            if (dashboardChart) {
                dashboardChart.resize();
            }
        });

        loadAllData();
            } catch (error) {
                alert("Error al cambiar la visibilidad del evento.");
                console.error(error);
            }
        }

        async function descargarEntrada(orderId, email) {
            try {
                const res = await fetch(`<?= BASE_URL ?>api/download-ticket.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        orderId: orderId,
                        email: email
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || 'Error al descargar la entrada');
                    return;
                }

                // Crear un blob con el HTML
                const htmlContent = data.html;
                const blob = new Blob([htmlContent], { type: 'text/html' });
                const url = window.URL.createObjectURL(blob);
                
                // Crear link de descarga
                const a = document.createElement('a');
                a.href = url;
                a.download = data.fileName;
                document.body.appendChild(a);
                a.click();
                
                // Limpiar
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                alert('Entrada descargada correctamente');
            } catch (error) {
                console.error('Error:', error);
                alert('Error al descargar la entrada: ' + error.message);
            }
        }

        async function eliminarCompra(id, orderId) {
            if (!confirm(`¿Estás seguro de que deseas eliminar la compra ${orderId}?`)) {
                return;
            }

            try {
                const res = await fetch(`<?= BASE_URL ?>api/delete-purchase.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || 'Error al eliminar la compra');
                    return;
                }

                alert('Compra eliminada correctamente');
                // Recargar estadísticas
                abrirEstadisticasEvento(currentEventId, document.getElementById('statsTitle').innerText.replace('Estadísticas - ', ''));
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la compra: ' + error.message);
            }
        }

        async function eliminarReserva(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                return;
            }

            try {
                const res = await fetch(`<?= BASE_URL ?>api/delete-reservation.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || 'Error al eliminar la reserva');
                    return;
                }

                alert('Reserva eliminada correctamente');
                // Recargar estadísticas
                abrirEstadisticasEvento(currentEventId, document.getElementById('statsTitle').innerText.replace('Estadísticas - ', ''));
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la reserva: ' + error.message);
            }
        }

        // --- CARGA DE DATOS ---
        async function loadAllData() {
            try {
                const res = await fetch('<?= BASE_URL ?>api/get-all?t=' + Date.now());
                const data = await res.json();
                
                const albums = data.albums || [];
                const events = data.events || [];
                const reservas = data.reservas || [];

                cargarEventosEstadisticas(events);
                cargarGraficoDashboard();

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
                await fetch(`<?= BASE_URL ?>api/update_reserva.php?id=${id}&action=${action}`);
                window.addEventListener('resize', () => {
            if (dashboardChart) {
                dashboardChart.resize();
            }
        });

        loadAllData();
            } catch (error) { console.error(error); }
        }

        async function deleteItem(id, type) {
            if(confirm("¿Seguro de eliminar este " + type + "?")) {
                try {
                    await fetch(`<?= BASE_URL ?>api/delete_item.php?id=${id}&type=${type}`);
                    window.addEventListener('resize', () => {
            if (dashboardChart) {
                dashboardChart.resize();
            }
        });

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
                const res = await fetch('<?= BASE_URL ?>api/upload_album.php', { method: 'POST', body: formData });
                
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
                const res = await fetch('<?= BASE_URL ?>api/upload_event.php', { method: 'POST', body: formData });
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

        window.addEventListener('resize', () => {
            if (dashboardChart) {
                dashboardChart.resize();
            }
        });

        loadAllData();
    </script>
    <script src="https://cdn.userway.org/widget.js" data-account="demo"></script>
</div>
</body>
</html>
