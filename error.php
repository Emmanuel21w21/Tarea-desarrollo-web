<?php
/**
 * error.php
 * ---------
 * Página dedicada que se muestra cuando el login falla, tal como
 * lo pide el requisito de manejo de errores de autenticación.
 */
require_once __DIR__ . '/includes/auth.php';

$tituloPagina  = 'Credenciales inválidas · GameVault';
$mostrarTopbar = false;
require __DIR__ . '/includes/header.php';
?>

<div class="error-wrap">
    <div class="error-card">
        <div class="icono">&#9888;</div>
        <h1>Usuario o contraseña incorrectos</h1>
        <p>No encontramos una cuenta que coincida con esos datos. Verifica tu usuario y contraseña e intenta de nuevo.</p>
        <a href="login.php" class="btn secundario" style="display:inline-flex;width:auto;padding-left:2rem;padding-right:2rem;">Volver a intentar</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
