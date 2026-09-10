# GameVault — Tienda de videojuegos (proyecto escolar)

Sistema en **PHP puro**, sin base de datos. Usuarios, contraseñas y
catálogo de productos están definidos directamente en el código
(`config/datos.php`). El catálogo inicia allí y los cambios realizados
desde el dashboard se guardan en `config/productos.json`.

## Estructura del proyecto

```
tienda-videojuegos/
├── index.php              Redirige a login o al panel según la sesión
├── login.php              Formulario de acceso + validación en PHP
├── error.php              Página de error para credenciales inválidas
├── logout.php             Cierra sesión
├── config/
│   ├── datos.php          Usuarios y catálogo inicial
│   └── productos.json     Cambios del catálogo guardados por el administrador
├── includes/
│   ├── auth.php           Login, sesión y protección de rutas por rol
│   ├── header.php         HTML compartido (head, barra superior)
│   └── footer.php         Cierre de HTML compartido
├── admin/
│   ├── dashboard.php      Panel, edición y alta de productos
│   └── catalogo.php       Catálogo exclusivo del administrador
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

