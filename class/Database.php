<?php

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 20:04
 */
class Database
{
	private static $credenciales = ['localhost', 'root', '', 'pruebas_orm'];
	private static $instance;
	private $conexion;
	
	private function __construct()
	{
		
	}
	
	public static function getInstance() : Database
	{
		if(!isset(static::$instance))
			static::$instance = new Database;
		return static::$instance;
	}
	
	public function getConexion() : mysqli
	{
		if(!isset($this->conexion))
			throw new ConexionNoEstablecidaException();
		return $this->conexion;
	}
	
	public function connect() {
		$credenciales = static::$credenciales;
		$this->conexion = mysqli_connect($credenciales[0], $credenciales[1], $credenciales[2], $credenciales[3]);
		
		if (mysqli_connect_errno()) {
			printf("Database Error: %s\n", mysqli_connect_error());
			exit();
		}
		mysqli_set_charset($this->conexion, "utf8");
	}
}