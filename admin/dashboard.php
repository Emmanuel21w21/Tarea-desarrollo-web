<?php
/**
 * admin/dashboard.php
 * --------------------
 * Panel exclusivo para el rol "administrador". Muestra estadísticas
 * generales del catálogo y una gráfica (Chart.js) con las existencias
 * por videojuego, generada a partir del arreglo $productos.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('administrador');

// --- Cálculos para las tarjetas de estadísticas (en PHP) ---
$totalProductos   = count($productos);
$totalExistencias = 0;
$valorInventario  = 0;

foreach ($productos as $p) {
    $totalExistencias += $p['existencias'];
    $valorInventario  += $p['existencias'] * $p['precio'];
}

// --- Datos para la gráfica: existencias por juego ---
$etiquetasJuegos    = array_column($productos, 'nombre');
$existenciasPorJuego = array_column($productos, 'existencias');

$tituloPagina = 'Panel de administrador · GameVault';
require __DIR__ . '/../includes/header.php';
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="marca"><span class="punto"></span> GameVault</div>
        <a href="dashboard.php" class="activo">Panel</a>
        <a href="../cliente/catalogo.php">Ver catálogo</a>
        <a href="../logout.php">Cerrar sesión</a>
    </aside>

    <main class="admin-main">
        <h1>Panel de administrador</h1>
        <p class="subtitulo">Resumen general del catálogo de videojuegos</p>

        <div class="stat-grid">
            <div class="stat-card">
                <span class="valor"><?= $totalProductos ?></span>
                <span class="etiqueta">Videojuegos en catálogo</span>
            </div>
            <div class="stat-card">
                <span class="valor"><?= $totalExistencias ?></span>
                <span class="etiqueta">Unidades en existencia</span>
            </div>
            <div class="stat-card">
                <span class="valor">$<?= number_format($valorInventario, 2) ?></span>
                <span class="etiqueta">Valor total del inventario (MXN)</span>
            </div>
        </div>

        <div class="panel">
            <h2>Existencias por videojuego</h2>
            <canvas id="graficaExistencias" height="90"></canvas>
        </div>

        <div class="panel">
            <h2>Catálogo completo</h2>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Juego</th>
                        <th>Género</th>
                        <th>Precio</th>
                        <th>Existencias</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><span class="genero-tag"><?= htmlspecialchars($p['genero']) ?></span></td>
                            <td>$<?= number_format($p['precio'], 2) ?></td>
                            <td><?= $p['existencias'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const etiquetas = <?= json_encode($etiquetasJuegos) ?>;
    const existencias = <?= json_encode($existenciasPorJuego) ?>;

    const ctx = document.getElementById('graficaExistencias');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Existencias',
                data: existencias,
                backgroundColor: '#2EE6A8',
                borderRadius: 4,
                maxBarThickness: 46
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#8D93A6' },
                    grid: { color: '#2A2D3A' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#8D93A6' },
                    grid: { color: '#2A2D3A' }
                }
            }
        }
    });
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
