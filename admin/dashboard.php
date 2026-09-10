<?php
/**
 * admin/dashboard.php
 * --------------------
 * Panel exclusivo para el rol "administrador". Muestra estadísticas
 * generales del catálogo y una gráfica (Chart.js) con las existencias
 * por videojuego, generada a partir del arreglo $productos.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/datos.php';

requerir_rol('administrador');

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'agregar_producto') {
    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $genero = trim((string) ($_POST['genero'] ?? ''));
    $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
    $precio = $_POST['precio'] ?? null;
    $existencias = $_POST['existencias'] ?? null;
    $archivoImagen = $_FILES['imagen'] ?? null;
    $errores = [];

    if ($nombre === '') {
        $errores[] = 'El nombre del juego es obligatorio.';
    }
    if ($genero === '') {
        $errores[] = 'El género es obligatorio.';
    }
    if ($descripcion === '') {
        $errores[] = 'La descripción es obligatoria.';
    }
    if (!is_numeric($precio) || (float) $precio < 0) {
        $errores[] = 'El precio debe ser un número mayor o igual a cero.';
    }
    if (filter_var($existencias, FILTER_VALIDATE_INT) === false || (int) $existencias < 0) {
        $errores[] = 'Las existencias deben ser un entero mayor o igual a cero.';
    }

    $tiposImagen = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $mimeImagen = null;

    if (!is_array($archivoImagen) || ($archivoImagen['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $errores[] = 'Debes seleccionar una imagen JPG, PNG o WebP.';
    } elseif (($archivoImagen['size'] ?? 0) > 5 * 1024 * 1024) {
        $errores[] = 'La imagen no puede superar los 5 MB.';
    } else {
        $informacionImagen = @getimagesize($archivoImagen['tmp_name']);
        $mimeImagen = $informacionImagen['mime'] ?? null;

        if ($mimeImagen === null || !isset($tiposImagen[$mimeImagen])) {
            $errores[] = 'La imagen debe ser un archivo JPG, PNG o WebP válido.';
        }
    }

    $rutaImagenGuardada = null;
    if ($errores === []) {
        $nombreArchivo = 'producto_' . bin2hex(random_bytes(8)) . '.' . $tiposImagen[$mimeImagen];
        $directorioImagenes = __DIR__ . '/../assets/img';
        $rutaImagenGuardada = $directorioImagenes . '/' . $nombreArchivo;

        if (!move_uploaded_file($archivoImagen['tmp_name'], $rutaImagenGuardada)) {
            $errores[] = 'No se pudo guardar la imagen. Verifica los permisos de la carpeta assets/img.';
        } else {
            $nuevoId = empty($productos) ? 1 : max(array_map('intval', array_keys($productos))) + 1;
            $productos[$nuevoId] = [
                'id' => $nuevoId,
                'nombre' => $nombre,
                'genero' => $genero,
                'precio' => round((float) $precio, 2),
                'existencias' => (int) $existencias,
                'imagen' => 'assets/img/' . $nombreArchivo,
                'descripcion' => $descripcion,
            ];

            if (file_put_contents($archivoProductos, json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
                unlink($rutaImagenGuardada);
                $errores[] = 'No se pudo guardar el producto. Verifica los permisos de la carpeta config.';
            }
        }
    }

    if ($errores !== []) {
        $mensaje = implode(' ', $errores);
        $tipoMensaje = 'error';
    } else {
        $mensaje = 'Juego agregado al catálogo correctamente.';
        $tipoMensaje = 'exito';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_catalogo') {
    $productosEditados = $_POST['productos'] ?? [];
    $errores = [];

    if (!is_array($productosEditados)) {
        $errores[] = 'Los datos recibidos no son válidos.';
    } else {
        foreach ($productos as $id => $producto) {
            $datosEditados = $productosEditados[$id] ?? [];
            $datosEditados = is_array($datosEditados) ? $datosEditados : [];
            $nombre = trim((string) ($datosEditados['nombre'] ?? ''));
            $precio = $datosEditados['precio'] ?? null;
            $existencias = $datosEditados['existencias'] ?? null;

            if ($nombre === '') {
                $errores[] = "El nombre del producto {$id} no puede estar vacío.";
            }
            if (!is_numeric($precio) || (float) $precio < 0) {
                $errores[] = "El precio del producto {$id} debe ser un número mayor o igual a cero.";
            }
            if (filter_var($existencias, FILTER_VALIDATE_INT) === false || (int) $existencias < 0) {
                $errores[] = "Las existencias del producto {$id} deben ser un entero mayor o igual a cero.";
            }

            $productos[$id]['nombre'] = $nombre;
            $productos[$id]['precio'] = round((float) $precio, 2);
            $productos[$id]['existencias'] = (int) $existencias;
        }
    }

    if ($errores !== []) {
        $mensaje = implode(' ', $errores);
        $tipoMensaje = 'error';
    } elseif (file_put_contents($archivoProductos, json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
        $mensaje = 'No se pudieron guardar los cambios. Verifica los permisos de la carpeta config.';
        $tipoMensaje = 'error';
    } else {
        $mensaje = 'Catálogo actualizado correctamente.';
        $tipoMensaje = 'exito';
    }
}

// --- Cálculos para las tarjetas de estadísticas (en PHP) ---
$totalProductos   = count($productos);
$totalExistencias = 0;
$valorInventario  = 0;

foreach ($productos as $p) {
    $totalExistencias += $p['existencias'];
    $valorInventario  += $p['existencias'] * $p['precio'];
}

// --- Datos para la gráfica: existencias por juego ---
$etiquetasJuegos    = array_column($productos, 'nombre');
$existenciasPorJuego = array_column($productos, 'existencias');

$tituloPagina = 'Panel de administrador · GameVault';
require __DIR__ . '/../includes/header.php';
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="marca"><span class="punto"></span> GameVault</div>
        <a href="dashboard.php" class="activo">Panel</a>
        <a href="catalogo.php">Ver catálogo</a>
    </aside>

    <main class="admin-main">
        <h1>Panel de administrador</h1>
        <p class="subtitulo">Resumen general del catálogo de videojuegos</p>

        <?php if ($mensaje !== ''): ?>
            <div class="mensaje <?= $tipoMensaje === 'exito' ? 'mensaje-exito' : 'mensaje-error' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <span class="valor"><?= $totalProductos ?></span>
                <span class="etiqueta">Videojuegos en catálogo</span>
            </div>
            <div class="stat-card">
                <span class="valor"><?= $totalExistencias ?></span>
                <span class="etiqueta">Unidades en existencia</span>
            </div>
            <div class="stat-card">
                <span class="valor">$<?= number_format($valorInventario, 2) ?></span>
                <span class="etiqueta">Valor total del inventario (MXN)</span>
            </div>
        </div>

        <div class="panel">
            <h2>Existencias por videojuego</h2>
            <canvas id="graficaExistencias" height="90"></canvas>
        </div>

        <div class="panel">
            <h2>Agregar juego nuevo</h2>
            <form method="post" enctype="multipart/form-data" class="form-producto-nuevo">
                <input type="hidden" name="accion" value="agregar_producto">
                <div class="form-producto-grid">
                    <div class="campo">
                        <label for="nombre">Nombre del juego</label>
                        <input type="text" id="nombre" name="nombre" maxlength="150" required>
                    </div>
                    <div class="campo">
                        <label for="genero">Género</label>
                        <input type="text" id="genero" name="genero" maxlength="80" required>
                    </div>
                    <div class="campo">
                        <label for="precio">Precio (MXN)</label>
                        <input type="number" id="precio" name="precio" min="0" step="0.01" required>
                    </div>
                    <div class="campo">
                        <label for="existencias">Existencias</label>
                        <input type="number" id="existencias" name="existencias" min="0" step="1" required>
                    </div>
                    <div class="campo campo-ancho">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" maxlength="500" rows="3" required></textarea>
                    </div>
                    <div class="campo campo-ancho">
                        <label for="imagen">Portada (JPG, PNG o WebP, máximo 5 MB)</label>
                        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-guardar">Agregar juego</button>
            </form>
        </div>

        <div class="panel">
            <h2>Catálogo completo</h2>
            <form method="post">
                <input type="hidden" name="accion" value="guardar_catalogo">
                <div class="tabla-scroll">
                    <table class="tabla tabla-edicion">
                        <thead>
                            <tr>
                                <th>Juego</th>
                                <th>Género</th>
                                <th>Precio (MXN)</th>
                                <th>Existencias</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $id => $p): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="productos[<?= $id ?>][nombre]" value="<?= htmlspecialchars($p['nombre']) ?>" required>
                                    </td>
                                    <td><span class="genero-tag"><?= htmlspecialchars($p['genero']) ?></span></td>
                                    <td>
                                        <input type="number" name="productos[<?= $id ?>][precio]" value="<?= htmlspecialchars((string) $p['precio']) ?>" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" name="productos[<?= $id ?>][existencias]" value="<?= (int) $p['existencias'] ?>" min="0" step="1" required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-guardar">Guardar cambios</button>
            </form>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const etiquetas = <?= json_encode($etiquetasJuegos) ?>;
    const existencias = <?= json_encode($existenciasPorJuego) ?>;

    const ctx = document.getElementById('graficaExistencias');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Existencias',
                data: existencias,
                backgroundColor: '#2EE6A8',
                borderRadius: 4,
                maxBarThickness: 46
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#8D93A6' },
                    grid: { color: '#2A2D3A' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#8D93A6' },
                    grid: { color: '#2A2D3A' }
                }
            }
        }
    });
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
