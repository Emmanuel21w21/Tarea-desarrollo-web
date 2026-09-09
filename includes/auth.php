<?php
/**
 * includes/auth.php
 * ------------------
 * Funciones para manejar la sesión, validar credenciales y proteger
 * rutas según el rol del usuario (administrador / cliente).
 *
 * Toda la validación se hace en PHP puro con arreglos en memoria,
 * definidos en config/datos.php. No se usa base de datos.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica usuario y contraseña contra el arreglo $usuarios.
 * Devuelve el arreglo del usuario (con su rol) si es válido, o null.
 */
function validar_credenciales(string $usuario, string $password, array $usuarios): ?array
{
    if (!isset($usuarios[$usuario])) {
        return null;
    }

    if ($usuarios[$usuario]['password'] !== $password) {
        return null;
    }

    return [
        'usuario' => $usuario,
        'rol'     => $usuarios[$usuario]['rol'],
        'nombre'  => $usuarios[$usuario]['nombre'],
    ];
}

/**
 * Guarda los datos de sesión de un login exitoso.
 */
function iniciar_sesion_usuario(array $datosUsuario): void
{
    $_SESSION['usuario'] = $datosUsuario['usuario'];
    $_SESSION['rol']     = $datosUsuario['rol'];
    $_SESSION['nombre']  = $datosUsuario['nombre'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
}

/**
 * Corta la ejecución y redirige a login.php si no hay sesión activa.
 */
function requerir_login(): void
{
    if (empty($_SESSION['usuario'])) {
        header('Location: ' . ruta_base() . 'login.php');
        exit;
    }
}

/**
 * Corta la ejecución si el rol de la sesión no coincide con el requerido.
 * Debe llamarse después de requerir_login().
 */
function requerir_rol(string $rolRequerido): void
{
    requerir_login();

    if ($_SESSION['rol'] !== $rolRequerido) {
        // Si tiene sesión pero no el rol correcto, lo mandamos a SU panel,
        // no a la página de error (el error es solo para login inválido).
        if ($_SESSION['rol'] === 'administrador') {
            header('Location: ' . ruta_base() . 'admin/dashboard.php');
        } else {
            header('Location: ' . ruta_base() . 'cliente/catalogo.php');
        }
        exit;
    }
}

/**
 * Calcula la ruta relativa hacia la raíz del proyecto según la
 * profundidad de la carpeta actual (para que los redirects funcionen
 * igual desde /admin/ o /cliente/ que desde la raíz).
 */
function ruta_base(): string
{
    // Si el script que se está ejecutando vive dentro de /admin/ o /cliente/
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    if (preg_match('#/(admin|cliente)/#', $script)) {
        return '../';
    }
    return '';
}
