<?php
namespace core;

use model\Usuario\Auth;
use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 24/02/2018
 * Time: 1:56
 */

class Render
{
	public static $twig;
	
	public static function inicializarTwig() {
		$loader = new Twig_Loader_Filesystem('views/');
		Render::$twig = new Twig_Environment($loader, array('strict_variables' => true));
		
		$functionRoot = new Twig_Function('root', function() {
			return $_SERVER['REQUEST_URI'];
		});
		$functionUrl = new Twig_Function('url', function($ruta) {
			return DOMAIN . $ruta;
		});
		$functionLink = new Twig_Function('link', function($ruta) {
			return $ruta;
		});
		$functionAsset = new Twig_Function('asset', function($ruta) {
			return DOMAIN . "/dist/" . $ruta;
		});
		$functionUploads = new Twig_Function('uploads', function($ruta) {
			return DOMAIN . "/uploads/" . $ruta;
		});
		$functionSpaceToGuion = new Twig_Function('convertSpace', function($texto) {
			return strtolower(str_replace(" ", "-", $texto));
		});
		
		Render::$twig->addFunction($functionRoot);
		Render::$twig->addFunction($functionUrl);
		Render::$twig->addFunction($functionAsset);
		Render::$twig->addFunction($functionSpaceToGuion);
		Render::$twig->addFunction($functionLink);
		Render::$twig->addFunction($functionUploads);
		
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		
		Render::$twig->addGlobal("usuario", $usuario);
		Render::$twig->addGlobal("notificaciones", $auth->getNotificaciones());
		
		$auth->deleteNotificaciones();
	}
	
}