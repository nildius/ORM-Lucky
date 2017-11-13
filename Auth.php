<?php

class Auth
{
	private static $instancia;
	private $usuario;
	
	public function __construct()
	{
		$this->usuario = isset($_SESSION['id']) ? Usuario::get($_SESSION['id']) : null;
	}
	
	public static function getAuth()
	{
		if(isset(self::$instancia))
			return self::$instancia;
		return new Auth;
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
	
	public function setUsuarioConectado($id)
	{
		$_SESSION['id'] = $id;
		$this->usuario = Usuario::get($id);
	}
	
	public function deleteUsuario()
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
		$sal = "poltec20175-qklr.9Xvfe34F2le";
		return(sha1($sal . $texto));
	}
}