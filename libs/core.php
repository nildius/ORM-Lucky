<?php
error_reporting( E_ALL );
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
ini_set('log_errors','On');

require_once("autoloader.php");
require_once("config.php");

use Database\Database;

date_default_timezone_set('America/Argentina/Buenos_Aires');