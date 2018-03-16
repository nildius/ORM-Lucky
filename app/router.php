<?php
use MiladRahimi\PHPRouter\Router;

$router = new Router();
/*
 * DEFINIR AQUÍ RUTAS PERSONALIZADAS
 */
$router->get("/", "HomeController@index");

//$router->get("/404", "ErrorController@index404");

$router->dispatch();
