<?php

use core\Render;

require_once("vendor/autoload.php");
require_once("libs/core.php");

setlocale(LC_TIME, 'Spanish');

LoginController::checkFirstLogin();
Render::inicializarTwig();

require_once("app/router.php");

