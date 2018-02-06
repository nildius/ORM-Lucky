<?php

define("SALT", "salsalsal-qklr.9Xvfe34F2le");

class Auth
{
	private static $instancia;
	private $usuario;
	
	private function __construct()
	{
		$this->usuario = isset($_SESSION['id']) ? Usuario::get($_SESSION['id']) : null;
	}
	
	public static function getAuth()
	{
		if(isset(self::$instancia))
			self::$instancia = new Auth();
		return self::$instancia;
	}
	
	public function getUsuario()
	{
		return $this->usuario;
	}
	
	public function RedireccionarOffline()
	{
		if(!$this->getUsuario()) {
			header('location: ../index.php');
			die();
		}
	}
	
	public function login($id)
	{
		$_SESSION['id'] = $id;
		$this->usuario = Usuario::get($id);
	}
	
	public function logout()
	{
		unset($_SESSION['id']);
		$this->usuario = NULL;
	}
	
	public function addError($error)
	{
		if(!isset($_SESSION['error']))
			$_SESSION['error'] = [];
		$_SESSION['error'][] = $error;
	}
	
	public function getErrores()
	{
		return isset($_SESSION['error']) ? $_SESSION['error'] : [];
	}
	
	public function deleteErrores()
	{
		unset($_SESSION['error']);
	}
	
	public function isUserLogged()
	{
		return $this->usuario == null;
	}
	
	public function kickIfNotAdmin()
	{
		if(!$this->isUserLogged())
			header("Location: error.php");
		$usuario = $this->getUsuarioConectado();
		if(!$usuario->isAdmin())
			header("Location: error.php");
	}
	
	public static function crypt($texto)
	{
		return(sha1(SALT . $texto));
	}
}