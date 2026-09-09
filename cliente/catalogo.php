<?php
/**
 * cliente/catalogo.php
 * ---------------------
 * Catálogo de productos para el rol "cliente". Cada juego muestra
 * nombre, precio, existencias e imagen, con un formulario para
 * agregarlo al carrito (guardado en $_SESSION['carrito']).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('cliente');

$tituloPagina = 'Catálogo · GameVault';
require __DIR__ . '/../includes/header.php';
?>

<div class="contenedor">
    <div class="catalogo-header">
        <div>
            <h1>Catálogo de videojuegos</h1>
            <p class="subtitulo">Elige tus títulos y agrégalos al carrito</p>
        </div>
    </div>

    <div class="grid-productos">
        <?php foreach ($productos as $p): ?>
            <div class="tarjeta-producto">
                <img src="../<?= htmlspecialchars($p['imagen']) ?>" alt="Portada de <?= htmlspecialchars($p['nombre']) ?>" class="portada">
                <div class="cuerpo">
                    <span class="genero-tag"><?= htmlspecialchars($p['genero']) ?></span>
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <span class="precio">$<?= number_format($p['precio'], 2) ?></span>
                    <span class="existencias <?= $p['existencias'] <= 10 ? 'bajo' : '' ?>">
                        <?= $p['existencias'] ?> disponibles
                    </span>

                    <form class="form-agregar" method="post" action="carrito.php">
                        <input type="hidden" name="accion" value="agregar">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?= $p['existencias'] ?>">
                        <button type="submit">Agregar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
