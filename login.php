<?php
/**
 * login.php
 * ---------
 * Pantalla de inicio de sesión. Pide usuario y contraseña y valida
 * las credenciales en PHP contra el arreglo $usuarios definido en
 * config/datos.php (no hay base de datos).
 *
 * Si las credenciales son incorrectas, redirige a error.php.
 * Si son correctas, crea la sesión y redirige al panel según el rol.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/datos.php';

// Si ya hay una sesión activa, no tiene sentido ver el login de nuevo.
if (!empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioIngresado  = trim($_POST['usuario'] ?? '');
    $passwordIngresada = (string) ($_POST['password'] ?? '');

    $datosValidos = validar_credenciales($usuarioIngresado, $passwordIngresada, $usuarios);

    if ($datosValidos === null) {
        // Credenciales incorrectas -> página de error dedicada.
        header('Location: error.php');
        exit;
    }

    // Credenciales correctas -> se crea la sesión y se redirige por rol.
    iniciar_sesion_usuario($datosValidos);

    if ($datosValidos['rol'] === 'administrador') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: cliente/catalogo.php');
    }
    exit;
}

$tituloPagina  = 'Iniciar sesión · GameVault';
$mostrarTopbar = false;
require __DIR__ . '/includes/header.php';
?>

<div class="login-wrap">
    <div class="login-card">
        <h1>GameVault</h1>
        <p class="subtitulo">Inicia sesión para continuar</p>

        <form method="post" action="login.php">
            <div class="campo">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" autocomplete="username" required autofocus>
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="credenciales-ayuda">
            Cuentas de prueba<br>
            Administrador: <code>administrador</code> / <code>asd</code><br>
            Cliente: <code>cliente</code> / <code>123</code>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
