<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 04/04/2018
 * Time: 1:12
 */

namespace core;


// SINGLETON
use model\Usuario\Auth;

class App
{
	private static $instancia;
	
	public static function getInstance() {
		if(static::$instancia == null)
			static::$instancia = new App;
		return static::$instancia;
	}
	
	private function __construct() {}
	
	public function user()
	{
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		return $usuario;
	}
	
	public function isGuest()
	{
		$auth = Auth::getAuth();
		return !$auth->isUserLogged();
	}
	
	public function auth()
	{
		
	}
}