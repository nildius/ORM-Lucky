<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 21/02/2018
 * Time: 0:00
 */

session_start();

define("DATABASE_HOST", "localhost");
define("DATABASE_DBNAME", "genubi_reborn_db");
define("DATABASE_USER", "root");
define("DATABASE_PASSWORD", "");

define("DIRECTORY_ROOT", $_SERVER['DOCUMENT_ROOT'] . "");
define("DIRECTORY_LIBS", DIRECTORY_ROOT . "/libs");
define("DIRECTORY_CONTROLLERS", DIRECTORY_ROOT . "/controllers");
define("DOMAIN", "http://genubisouls.local");

define("ERROR_PAGE_404", "/404");