<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 21/02/2018
 * Time: 0:00
 */

use core\Database\Database;
use Whoops\Run;

error_reporting( E_ALL );
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
ini_set('log_errors','On');

require_once("app/config.php");
require_once("functions.php");

date_default_timezone_set('America/Argentina/Buenos_Aires');

$whoops = new Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

Database::connect();