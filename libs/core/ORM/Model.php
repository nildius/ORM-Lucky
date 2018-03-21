<?php
namespace core\ORM;

use core\Database\Database;
use core\Exceptions\CampoNoExisteEnModeloError;
use PDO;
use Render;
use Tightenco\Collect\Support\Collection;

/**
 * Class Model
 * @package core\ORM
 * @author Lucas E. Lois <lucaslois95@gmail.com> - https://github.com/nildius
 * @last-update 16/03/2018
 * Esta clase actúa como ORM dentro del sistema. La clase cargará tantos atributos como campos haya en la base de datos de una tabla.
 * Para crear un Modelo se debe heredar esta clase y debe definirse el atributo estático protegido <tabla> e <identificador>
 * Tabla: Representa el nombre de la tabla que se quiere representar con el modelo
 * Identificador: Representa el campo que posee la clave primaria de la tabla
 */
abstract class Model implements \jsonSerializable {
	protected $campos;
	protected static $tabla;
	protected static $identificador;
	// METODOS MAGICOS
	
	public function __isset($key) : bool {
		return isset($this->campos[$key]);
	}
	
	public function __get($key)
	{
		if(!isset($this->campos[$key]))
			throw new CampoNoExisteEnModeloError("No se ha encontrado el campo '$key' en el modelo '". get_class($this) ."'.");
		return $this->campos[$key];
	}
	
	public function __set($campo, $valor)
	{
		$this->campos[$campo] = $valor;
	}
	
	public function __construct()
	{
		$this->campos = array();
		$this->campos[lcfirst(static::$identificador)] = 0;
	}
	
	
	/**
	 * @param $arreglo
	 * @return Model
	 */
	public static function createObjectFromArray($arreglo) : self
	{
		$obj = new static;
		foreach($arreglo as $key => $value)
		{
			$key_minus = lcfirst($key);
			if(Validator::isValidDateTime($value))
				$value = Carbon::parse($value);
			$obj->campos[$key_minus] = $value;
		}
		
		$obj->postLoad();
		return $obj;
	}
	
	public function postLoad()
	{
		return;
	}
	
	// FIN METODOS MAGICOS
	
	public static function where(Formatter $where) : ?self
	{
		$database = Database::connect();
		$tabla = static::$tabla;
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		$resultado = $consulta->fetchObject();
		if(!$resultado)
			return null;
		
		return static::createObjectFromArray($resultado);
	}
	
	public static function whereMultiple(Formatter $where) : Collection
	{
		$database = Database::connect();
		$tabla = static::$tabla;
		$lista = new Collection();
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		while($res = $consulta->fetchObject())
			$lista->push(self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function whereRaw(String $string) : Collection
	{
		$database = Database::connect();
		$tabla = static::$tabla;
		$lista = new Collection();
		
		$query = "SELECT * FROM $tabla WHERE " . $string;
		
		$consulta = $database->query($query);
		$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultados as $res)
			$lista->push(self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function get($id) : self
	{
		if($id === null)
			return null;
		$database = Database::connect();
		$tabla = static::$tabla;
		$identificador = static::$identificador;
		$query = $database->prepare("SELECT * FROM $tabla WHERE $identificador = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if(count($result) == 1)
			return self::createObjectFromArray($result[0]);
		return null;
	}
	
	public static function findOrFail($id) : self
	{
		$tmp = static::get($id);
		if($tmp === null)
		{
			header("Location: " . DOMAIN . "/404");
			die();
		}
		return $tmp;
	}
	
	public static function getAll() : Collection
	{
		$database = Database::connect();
		$lista = new Collection();
		$tabla = static::$tabla;
		$consulta = $database->query("SELECT * FROM $tabla");
		
		$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultados as $res)
			$lista->push(self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public function delete()
	{
		$database = Database::connect();
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$database->query("DELETE FROM $tabla WHERE $identi = " . $this->campos[lcfirst($identi)]);
	}
	
	public function save()
	{
		$database = Database::connect();
		$queryBuilder = new QueryBuilder($this->campos);
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$consulta = "UPDATE $tabla SET ";
		$consulta .= $queryBuilder->getQuery();
		$consulta .= " WHERE $identi = " . $this->campos[$identi];
		
		$query = $database->prepare($consulta);
		foreach($queryBuilder->getListaCampos() as $pack) {
			$query->bindParam($pack['bind'], $pack['value'], $pack['type']);
		}
		$query->execute();
	}
	
	public function insert()
	{
		$database = Database::connect();
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$queryBuilder = new QueryBuilder($this->campos);
		$consulta = "INSERT INTO $tabla SET ";
		$consulta .= $queryBuilder->getQuery();
		
		$query = $database->prepare($consulta);
		foreach($queryBuilder->getListaCampos() as $pack) {
			$query->bindParam($pack['bind'], $pack['value'], $pack['type']);
		}
		$query->execute();
		
		$this->campos[lcfirst($identi)] = $database->lastInsertId(); #Recuperamos el ID Autogenerado y lo guardamos en el objeto.
		if($consulta)
			return true;
		return false;
	}
	
	public function jsonSerialize()
	{
		return $this->campos;
	}
}