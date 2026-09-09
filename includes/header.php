<?php
/**
 * includes/header.php
 * --------------------
 * Se incluye al inicio de cada página. Espera que la variable
 * $tituloPagina ya esté definida antes de incluirse.
 *
 * $mostrarTopbar (bool) controla si se dibuja la barra superior con
 * usuario/carrito/cerrar sesión (login.php y error.php no la usan).
 */

$base = ruta_base();
$mostrarTopbar = $mostrarTopbar ?? true;

$totalCarrito = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $cant) {
        $totalCarrito += $cant;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'GameVault') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>assets/css/estilo.css">
</head>
<body>
<?php if ($mostrarTopbar && !empty($_SESSION['usuario'])): ?>
    <div class="topbar">
        <div class="marca"><span class="punto"></span> GameVault</div>
        <div class="topbar-derecha">
            <?php if ($_SESSION['rol'] === 'cliente'): ?>
                <a href="<?= $base ?>cliente/resumen.php" class="pill carrito">
                    Carrito (<?= (int) $totalCarrito ?>)
                </a>
            <?php endif; ?>
            <span class="usuario"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
            <a href="<?= $base ?>logout.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </div>
<?php endif; ?>
