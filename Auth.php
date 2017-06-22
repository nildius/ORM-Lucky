<?php

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 07/06/2017
 * Time: 20:08
 */
abstract class Auth
{
	public static function RedireccionarOffline()
	{
		if(!isset($_SESSION['id'])) {
			header('location: ./index.php');
		}
	}
	
	public static function getUsuarioConectado()
	{
		if(isset($_SESSION['id'])) {
			return Usuario::get($_SESSION['id']);
		}
		return null;
	}
	
	public static function isUserLogged()
	{
		return isset($_SESSION['id']) && $_SESSION['id'] > 0;
	}
	
	public static function kickIfNotAdmin()
	{
		if(!self::isUserLogged())
			header("Location: error.php");
		$usuario = self::getUsuarioConectado();
		if(!$usuario->isAdmin())
			header("Location: error.php");
	}
}