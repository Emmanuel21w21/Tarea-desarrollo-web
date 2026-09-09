<?php
/**
 * logout.php
 * ----------
 * Destruye la sesión actual (incluido el carrito) y regresa al login.
 */
require_once __DIR__ . '/includes/auth.php';

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
