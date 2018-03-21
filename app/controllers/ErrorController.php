<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 2:08
 */
class ErrorController
{
	public function index404() {
		echo Render::$twig->render("errors/404.twig");
	}
}