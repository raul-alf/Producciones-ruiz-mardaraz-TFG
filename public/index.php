<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once '../core/Config.php';
require_once '../core/Database.php';
require_once '../core/Controller.php';
require_once '../core/Router.php';

$router = new Router();

/* PÁGINAS */
$router->get(BASE_URL, 'HomeController@index');
$router->get(BASE_URL . 'index', 'HomeController@index');

$router->get(BASE_URL . 'admin', 'AdminController@dashboard');
$router->get(BASE_URL . 'login', 'AuthController@login');
$router->post(BASE_URL . 'login', 'AuthController@comprobar');
$router->get(BASE_URL . 'logout', 'AuthController@logout');

$router->get(BASE_URL . 'fechas', 'EventoController@fechas');
$router->get(BASE_URL . 'galeria', 'GaleriaController@index');
$router->get(BASE_URL . 'ver-album', 'GaleriaController@verAlbum');
$router->get(BASE_URL . 'reservas', 'ReservaController@index');
$router->get(BASE_URL . 'promos', 'PromoController@index');

$router->get(BASE_URL . 'compra', 'EntradaController@compra');
$router->get(BASE_URL . 'entrada', 'EntradaController@entrada');
$router->get(BASE_URL . 'ticket', 'EntradaController@ticket');
$router->get(BASE_URL . 'generar-pdf', 'EntradaController@generarPdf');
$router->get(BASE_URL . 'ver-compra', 'CompraController@ver');

/* API ADMIN */
$router->get(BASE_URL . 'api/get-all', 'AdminController@getAll');
$router->get(BASE_URL . 'api/event-stats', 'AdminController@getEventStats');
$router->get(BASE_URL . 'api/delete-item', 'AdminController@deleteItem');

/* API EVENTOS */
$router->get(BASE_URL . 'api/events', 'EventoController@getEvents');
$router->post(BASE_URL . 'api/upload-event', 'EventoController@uploadEvent');
$router->get(BASE_URL . 'api/delete-event', 'EventoController@deleteEvent');
$router->get(BASE_URL . 'api/toggle-event-visibility', 'EventoController@toggleVisibility');

/* API RESERVAS */
$router->post(BASE_URL . 'api/crear-reserva', 'ReservaController@crearReserva');
$router->get(BASE_URL . 'api/update-reserva', 'ReservaController@updateReserva');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);