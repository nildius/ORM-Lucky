<?php
namespace Database;
use PDO;
use PDOException;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 20:04
 * Singleton
 * Esta clase guarda el objeto de conexion de la base de datos
 *
 * TODO: MEJORAR
 */
class Database
{
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
	
	public function getConexion() : PDO
	{
		if(!isset($this->conexion))
			throw new ConexionNoEstablecidaException();
		return $this->conexion;
	}
	
	public static function connect() : PDO {
		$opciones  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
		try {
			$database = new PDO(
				"mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_DBNAME,
				DATABASE_USER,
				DATABASE_PASSWORD,
				$opciones
			);
		} catch (PDOException $e) {
			print "Database Connection Error: " . $e->getMessage() . "<br/>";
			die();
		}
		
		$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		
		return $database;
	}
}