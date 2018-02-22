<?php

use MiladRahimi\PHPRouter\Router;

require_once("controllers/UserController.php");

$router = new Router();
$router->get("/usuarios/{id}", "UserController@createUser");

$router->get("/*", function() {
    return "La pagina no está definida";
});

$router->dispatch();
