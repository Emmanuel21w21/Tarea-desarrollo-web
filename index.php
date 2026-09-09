<?php
/**
 * index.php
 * ---------
 * Punto de entrada del sistema. No dibuja nada por sí mismo:
 * decide a dónde mandar al visitante según si ya inició sesión
 * y con qué rol.
 */
require_once __DIR__ . '/includes/auth.php';

if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['rol'] === 'administrador') {
    header('Location: admin/dashboard.php');
} else {
    header('Location: cliente/catalogo.php');
}
exit;
