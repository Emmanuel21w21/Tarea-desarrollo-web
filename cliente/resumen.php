<?php
/**
 * cliente/resumen.php
 * --------------------
 * Resumen de compra: lista los productos del carrito (sesión),
 * calcula el subtotal de cada línea y el total general, todo en PHP.
 *
 * Al "Finalizar compra" se vacía el carrito y se muestra una
 * confirmación (no hay base de datos, así que no se guarda un
 * historial de pedidos: se simula la compra tal como pide el proyecto).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('cliente');

$compraFinalizada = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'finalizar') {
    $_SESSION['carrito'] = [];
    $compraFinalizada    = true;
}

// --- Construir las líneas del carrito y calcular totales (PHP) ---
$lineas = [];
$total  = 0.0;

foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
    if (!isset($productos[$idProducto])) {
        continue;
    }

    $producto = $productos[$idProducto];
    $subtotal = $producto['precio'] * $cantidad;
    $total   += $subtotal;

    $lineas[] = [
        'producto'  => $producto,
        'cantidad'  => $cantidad,
        'subtotal'  => $subtotal,
    ];
}

$tituloPagina = 'Resumen de compra · GameVault';
require __DIR__ . '/../includes/header.php';
?>

<div class="contenedor">
    <h1>Resumen de compra</h1>
    <p class="subtitulo" style="margin-bottom:1.6rem;">Revisa tu selección antes de confirmar</p>

    <?php if ($compraFinalizada): ?>
        <div class="confirmacion-banner">
            ¡Compra simulada con éxito! Gracias por tu pedido. Tu carrito ha sido vaciado.
        </div>
    <?php endif; ?>

    <?php if (empty($lineas)): ?>
        <div class="carrito-vacio">
            <p>Tu carrito está vacío.</p>
            <br>
            <a href="catalogo.php" class="btn secundario" style="display:inline-flex;width:auto;padding-left:2rem;padding-right:2rem;">Ir al catálogo</a>
        </div>
    <?php else: ?>
        <table class="resumen-tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineas as $linea): ?>
                    <?php $p = $linea['producto']; ?>
                    <tr>
                        <td>
                            <div class="nombre-producto">
                                <img src="../<?= htmlspecialchars($p['imagen']) ?>" alt="" class="miniatura">
                                <?= htmlspecialchars($p['nombre']) ?>
                            </div>
                        </td>
                        <td>$<?= number_format($p['precio'], 2) ?></td>
                        <td>
                            <form class="cantidad-form" method="post" action="carrito.php">
                                <input type="hidden" name="accion" value="actualizar">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="number" name="cantidad" value="<?= $linea['cantidad'] ?>" min="1" max="<?= $p['existencias'] ?>">
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td class="subtotal">$<?= number_format($linea['subtotal'], 2) ?></td>
                        <td>
                            <form method="post" action="carrito.php">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-quitar">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-final">
            <span class="etiqueta">Total a pagar</span>
            <span class="monto">$<?= number_format($total, 2) ?></span>
        </div>

        <div class="acciones-resumen">
            <a href="catalogo.php" class="btn secundario" style="width:auto;padding-left:1.6rem;padding-right:1.6rem;">Seguir comprando</a>
            <form method="post" action="resumen.php">
                <input type="hidden" name="accion" value="finalizar">
                <button type="submit" class="btn" style="width:auto;padding-left:2rem;padding-right:2rem;">Finalizar compra</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
