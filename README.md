# GameVault — Tienda de videojuegos (proyecto escolar)

Sistema en **PHP puro**, sin base de datos. Usuarios, contraseñas y
catálogo de productos están definidos directamente en el código
(`config/datos.php`).

## Estructura del proyecto

```
tienda-videojuegos/
├── index.php              Redirige a login o al panel según la sesión
├── login.php              Formulario de acceso + validación en PHP
├── error.php              Página de error para credenciales inválidas
├── logout.php             Cierra sesión
├── config/
│   └── datos.php          Usuarios y catálogo (hardcodeados)
├── includes/
│   ├── auth.php           Login, sesión y protección de rutas por rol
│   ├── header.php         HTML compartido (head, barra superior)
│   └── footer.php         Cierre de HTML compartido
├── admin/
│   └── dashboard.php      Panel del administrador + gráfica Chart.js
├── cliente/
│   ├── catalogo.php       Catálogo de videojuegos
│   ├── carrito.php        Maneja agregar/actualizar/eliminar del carrito
│   └── resumen.php        Resumen de compra: subtotales y total (PHP)
└── assets/
    ├── css/estilo.css     Estilos del sistema
    └── img/*.svg          Portadas de cada videojuego
```

## Credenciales de prueba

| Usuario         | Contraseña | Rol            |
|-----------------|------------|----------------|
| administrador   | asd        | Administrador  |
| cliente         | 123        | Cliente        |

## Cómo correrlo en local

Necesitas PHP instalado (7.4 o superior; recomendado 8.x). Desde la
carpeta raíz del proyecto:

```bash
php -S localhost:8000
```

Abre `http://localhost:8000` en tu navegador.

## Cómo publicarlo temporalmente con Ngrok

1. Deja corriendo el servidor de PHP del paso anterior.
2. En otra terminal, ejecuta:
   ```bash
   ngrok http 8000
   ```
3. Ngrok te dará una URL pública como `https://xxxx.ngrok-free.app`.
4. Entra a esa URL desde el navegador (no desde localhost) y ahí toma
   las capturas de pantalla para el PDF de evidencias.

## Notas técnicas

- El carrito de compra vive en `$_SESSION['carrito']` como un arreglo
  `id_producto => cantidad`. No se guarda en ningún archivo ni base de
  datos: al cerrar sesión o reiniciar el servidor se pierde.
- Las imágenes del catálogo son archivos `.svg` originales generados
  para este proyecto (no se usan imágenes de terceros ni con derechos
  reservados).
- La gráfica del panel de administrador usa **Chart.js** (CDN) y se
  alimenta con datos reales de existencias, generados con
  `json_encode()` desde PHP.
