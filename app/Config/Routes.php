<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Rutas: LIBROS
$routes->get('/libros', 'LibroController::index');
$routes->get('/libros/crear', 'LibroController::crear'); //Renderiza el FORM
$routes->get('/libros/editar/(:num)', 'LibroController::editar/$1');
$routes->get('/libros/buscar', 'LibroController::buscar');
$routes->post('/public/api/buscarlibro', 'LibroController::buscarLibro');

$routes->post('/libros/guardar', 'LibroController::guardar'); //<form method="POST">
$routes->post('/libros/actualizar', 'LibroController::actualizar'); //<form method="POST">

$routes->get('/libros/borrar/(:num)', 'LibroController::borrar/$1');

$routes->get('/editoriales', 'EditorialController::index');
$routes->get('/editoriales/crear', 'EditorialController::crear');
$routes->get('/editoriales/editar', 'EditorialController::editar');


//Ruta: Personas
$routes->get('/personas', 'PersonaController::index');

$routes->get('/personas/crear', 'PersonaController::crear');
$routes->post('/personas/guardar', 'PersonaController::guardar');

$routes->get('/personas/editar/(:num)', 'PersonaController::editar/$1');
$routes->post('/personas/actualizar/(:num)', 'PersonaController::actualizar/$1');

$routes->get('/personas/eliminar/(:num)', 'PersonaController::eliminar/$1');
$routes->get('/personas/buscar', 'PersonaController::buscar');

//API
$routes->get('api/personas/buscardni/(:num)', 'PersonaController::searchByDNI/$1');
$routes->get('api/ubigeo/provincias/(:num)', 'ProvinciaController::getProvinciasByDepartamento/$1');
$routes->get('api/ubigeo/distritos/(:num)', 'ProvinciaController::getDistritosByProvincia/$1');