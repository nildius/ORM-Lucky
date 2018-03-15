<?php
use MiladRahimi\PHPRouter\Router;

$router = new Router();
/*
 * DEFINIR AQUÍ RUTAS PERSONALIZADAS
 */
$router->get("/", "ViewsController@renderHome");

// * LOGIN *
$router->get("/iniciar-sesion", "LoginController@index");
$router->post("/iniciar-sesion/login", "LoginController@login");
$router->get("/iniciar-sesion/logout", "LoginController@logout");
$router->get("/registrarse", "RegisterController@index");

// * GALERIA *
$router->get("/galeria/crearAlbum", "GaleriaController@indexCrearAlbum");
$router->get("/galeria/editarAlbum/{id}", "GaleriaController@indexEditarAlbum");
$router->post("/AJAX/galeria/actualizarOrdenAlbum/{id}", "DibujoController@actualizarOrden");

$router->post("/galeria/crearAlbum", "AlbumController@crearAlbum");
$router->post("/galeria/cargarFotos", "AlbumController@cargarFotos");
$router->get("/galeria/dibujos", "GaleriaController@indexGaleriaDibujos");
$router->get("/galeria/comics", "GaleriaController@indexGaleriaComics");
$router->get("/galeria/dibujos/{id}", "AlbumController@indexAlbumDibujos");
$router->get("/galeria/comics/{id}", "AlbumController@indexAlbumComics");

$router->get("/galeria/comics{id}", "AlbumController@indexAlbumComics");

$router->get("/partidas", "ViewsController@renderPartidas");
$router->get("/partidas/{nombre}", "PartidaController@renderPartidaPerfil");
$router->get("/partidas/{partidaNombre}/{personajeNombre}", "PartidaController@renderPartidaPerfilPersonaje");

$router->get("/personajes", "PersonajeController@renderPersonajes");
$router->get("/personajes/{personajeNombre}", "PersonajeController@renderPersonajePerfil");

$router->get("/jugadores", "JugadorController@renderJugadores");
$router->get("/jugadores/{personajeNombre}", "JugadorController@renderJugadorPerfil");

$router->get("/panel", "PanelController@renderPanel");
$router->get("/panel/personaje/{nombrePersonaje}", "PanelController@renderEdicionPersonaje");

$router->get("/404", "ErrorController@index404");

$router->dispatch();
