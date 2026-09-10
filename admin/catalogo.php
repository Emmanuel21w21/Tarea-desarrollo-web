<?php
/**
 * admin/catalogo.php
 * ------------------
 * Vista del catálogo para el rol administrador, sin acciones de compra.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('administrador');

$totalProductos = count($productos);
$totalExistencias = 0;
$productosAgotados = 0;
$productosStockBajo = 0;

foreach ($productos as $producto) {
    $existencias = (int) $producto['existencias'];
    $totalExistencias += $existencias;

    if ($existencias === 0) {
        $productosAgotados++;
    } elseif ($existencias <= 10) {
        $productosStockBajo++;
    }
}

$tituloPagina = 'Catálogo administrativo · GameVault';
require __DIR__ . '/../includes/header.php';
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="marca"><span class="punto"></span> GameVault</div>
        <a href="dashboard.php">Panel</a>
        <a href="catalogo.php" class="activo">Ver catálogo</a>
    </aside>

    <main class="admin-main">
        <div class="catalogo-header admin-catalogo-header">
            <div>
                <h1>Catálogo administrativo</h1>
                <p class="subtitulo">Consulta el inventario publicado y su disponibilidad</p>
            </div>
            <a href="dashboard.php" class="btn secundario btn-catalogo-admin">Editar inventario</a>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <span class="valor"><?= $totalProductos ?></span>
                <span class="etiqueta">Videojuegos publicados</span>
            </div>
            <div class="stat-card">
                <span class="valor"><?= $totalExistencias ?></span>
                <span class="etiqueta">Unidades disponibles</span>
            </div>
            <div class="stat-card">
                <span class="valor stock-bajo-valor"><?= $productosStockBajo ?></span>
                <span class="etiqueta">Productos con stock bajo</span>
            </div>
            <div class="stat-card">
                <span class="valor agotado-valor"><?= $productosAgotados ?></span>
                <span class="etiqueta">Productos agotados</span>
            </div>
        </div>

        <div class="grid-productos admin-grid-productos">
            <?php foreach ($productos as $producto): ?>
                <?php
                $existencias = (int) $producto['existencias'];
                $estadoClase = $existencias === 0 ? 'agotado' : ($existencias <= 10 ? 'stock-bajo' : 'disponible');
                $estadoTexto = $existencias === 0 ? 'Agotado' : ($existencias <= 10 ? 'Stock bajo' : 'Disponible');
                ?>
                <article class="tarjeta-producto tarjeta-admin">
                    <img src="../<?= htmlspecialchars($producto['imagen']) ?>" alt="Portada de <?= htmlspecialchars($producto['nombre']) ?>" class="portada">
                    <div class="cuerpo">
                        <span class="genero-tag"><?= htmlspecialchars($producto['genero']) ?></span>
                        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                        <p class="descripcion-producto"><?= htmlspecialchars($producto['descripcion']) ?></p>
                        <span class="precio">$<?= number_format($producto['precio'], 2) ?> MXN</span>
                        <div class="estado-inventario <?= $estadoClase ?>">
                            <span><?= $estadoTexto ?></span>
                            <strong><?= $existencias ?> unidades</strong>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>