<?php
/**
 * config/datos.php
 * -----------------
 * Aquí viven TODOS los datos del sistema.
 *
 * $usuarios  -> credenciales válidas para iniciar sesión
 * $productos -> catálogo de videojuegos disponible para la vista de Cliente
 */

// -----------------------------------------------------------------
// Usuarios válidos del sistema
// -----------------------------------------------------------------
$usuarios = [
    'administrador' => [
        'password' => 'asd',
        'rol'      => 'administrador',
        'nombre'   => 'Administrador',
    ],
    'cliente' => [
        'password' => '123',
        'rol'      => 'cliente',
        'nombre'   => 'Cliente',
    ],
];

// -----------------------------------------------------------------
// Catálogo de productos (videojuegos)
// -----------------------------------------------------------------
$productos = [
    1 => [
        'id'          => 1,
        'nombre'      => 'Need for Speed Most Wanted (2005)',
        'genero'      => 'Carreras',
        'precio'      => 649.00,
        'existencias' => 18,
        'imagen'      => 'assets/img/nfsmw_2005.jpeg',
        'descripcion' => 'Carreras callejeras en un mundo abierto con persecuciones policiales intensas.',
    ],
    2 => [
        'id'          => 2,
        'nombre'      => 'Halo Campaign Evolved',
        'genero'      => 'Acción',
        'precio'      => 1899.00,
        'existencias' => 12,
        'imagen'      => 'assets/img/HaloCampaingEvolved.jpeg',
        'descripcion' => 'Combate frenetico en primera persona en un hambiente de guerra futurista.',
    ],
    3 => [
        'id'          => 3,
        'nombre'      => 'Paper Mario The Origami King',
        'genero'      => 'RPG',
        'precio'      => 749.00,
        'existencias' => 25,
        'imagen'      => 'assets/img/paper_mario.jpg',
        'descripcion' => 'Una colorida y divertida aventura RPG de papel con Mario y sus amigos.',
    ],
    4 => [
        'id'          => 4,
        'nombre'      => 'Outlast',
        'genero'      => 'Terror',
        'precio'      => 599.00,
        'existencias' => 8,
        'imagen'      => 'assets/img/outlast.jpg',
        'descripcion' => 'Sobrevive a al horror puro en primer persona.',
    ],
    5 => [
        'id'          => 5,
        'nombre'      => 'Donkey Kong Country: Tropical Freeze',
        'genero'      => 'Plataformas',
        'precio'      => 399.00,
        'existencias' => 30,
        'imagen'      => 'assets/img/donkey.jpg',
        'descripcion' => 'El rey de las plataformas en 2D regresa con su desafio definitivo.',
    ],
    6 => [
        'id'          => 6,
        'nombre'      => 'Pikmin 4',
        'genero'      => 'Estrategia',
        'precio'      => 549.00,
        'existencias' => 15,
        'imagen'      => 'assets/img/pikmin.jpg',
        'descripcion' => 'Estrategia, exploración y encanto en un planeta en miniatura.',
    ],
    7 => [
        'id'          => 7,
        'nombre'      => 'EA Sports FC 27',
        'genero'      => 'Deportes',
        'precio'      => 1899.00,
        'existencias' => 20,
        'imagen'      => 'assets/img/fc27.jpg',
        'descripcion' => 'Arma el equipo de tus sueños y compite contra los mejores',
    ],
    8 => [
        'id'          => 8,
        'nombre'      => 'The Legend of Zelda: Tears of the Kingdom',
        'genero'      => 'Aventura',
        'precio'      => 799.00,
        'existencias' => 10,
        'imagen'      => 'assets/img/Zelda.jpg',
        'descripcion' => 'La secuela de uno de los mejores videojuegos de la historia.',
    ],
    9=> [
        'id'          => 9,
        'nombre'      => 'Grand Theft Auto VI (Preventa)',
        'genero'      => 'Acción, Mundo abierto',
        'precio'      => 1899.00,
        'existencias' => 0,
        'imagen'      => 'assets/img/gta6.jpg',
        'descripcion' => 'El titulo más esperado de la saga.',
    ]
];

// Si el administrador ha guardado cambios, estos sustituyen los valores iniciales.
$archivoProductos = __DIR__ . '/productos.json';
if (is_file($archivoProductos)) {
    $productosGuardados = json_decode(file_get_contents($archivoProductos), true);

    if (is_array($productosGuardados) && $productosGuardados !== []) {
        $productos = $productosGuardados;
    }
}
