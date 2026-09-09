<?php
/**
 * cliente/carrito.php
 * --------------------
 * Procesa las acciones sobre el carrito de compra. No dibuja nada:
 * recibe un POST, modifica $_SESSION['carrito'] y redirige de vuelta.
 *
 * El carrito se guarda como: [ id_producto => cantidad, ... ]
 * completamente en sesión, sin base de datos.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('cliente');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catalogo.php');
    exit;
}

$accion = $_POST['accion'] ?? '';
$id     = (int) ($_POST['id'] ?? 0);

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

switch ($accion) {
    case 'agregar':
        if (isset($productos[$id])) {
            $cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));
            $maximo   = $productos[$id]['existencias'];

            $cantidadActual = $_SESSION['carrito'][$id] ?? 0;
            $nuevaCantidad  = min($cantidadActual + $cantidad, $maximo);

            $_SESSION['carrito'][$id] = $nuevaCantidad;
        }
        header('Location: catalogo.php');
        break;

    case 'actualizar':
        if (isset($_SESSION['carrito'][$id]) && isset($productos[$id])) {
            $cantidad = (int) ($_POST['cantidad'] ?? 1);
            $maximo   = $productos[$id]['existencias'];

            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$id]);
            } else {
                $_SESSION['carrito'][$id] = min($cantidad, $maximo);
            }
        }
        header('Location: resumen.php');
        break;

    case 'eliminar':
        unset($_SESSION['carrito'][$id]);
        header('Location: resumen.php');
        break;

    case 'vaciar':
        $_SESSION['carrito'] = [];
        header('Location: resumen.php');
        break;

    default:
        header('Location: catalogo.php');
        break;
}
exit;
