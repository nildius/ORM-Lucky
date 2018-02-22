<?php
namespace ORM;

use Carbon\Carbon as Carbon;
use Database\Database;
use PDO;

abstract class Model implements \jsonSerializable {
	protected $campos;
	protected static $tabla;
	protected static $identificador;
	// METODOS MAGICOS
	
	public function __get($key)
	{
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
	
	public static function createObjectFromArray($arreglo)
	{
		$obj = new static;
		foreach($arreglo as $key => $value)
		{
			$key_minus = lcfirst($key);
			if(Validator::isValidDateTime($value))
				$value = Carbon::parse($value);
			$obj->campos[$key_minus] = $value;
		}
		
		return $obj;
	}
	
	// FIN METODOS MAGICOS
	
	public static function where(Formatter $where)
	{
		$database = Database::getInstance()->connect();
		$tabla = static::$tabla;
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
		if(count($resultado) == 0)
			return null;
		$res = $consulta->fetch_assoc();
		
		return static::createObjectFromArray($res);
	}
	
	public static function whereMultiple(Formatter $where)
	{
		$database = Database::getInstance()->connect();
		$tabla = static::$tabla;
		$lista = [];
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		while($res = $consulta->fetchObject())
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function whereRaw(String $string)
	{
		$database = Database::getInstance()->connect();
		$tabla = static::$tabla;
		$lista = [];
		
		$query = "SELECT * FROM $tabla WHERE " . $string;
		
		$consulta = $database->query($query);
		$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultados as $res)
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function get($id)
	{
		if($id === null)
			return null;
		$database = Database::getInstance()->connect();
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
	
	public static function getAll()
	{
		$database = Database::getInstance()->connect();
		$lista = [];
		$tabla = static::$tabla;
		$consulta = $database->query("SELECT * FROM $tabla");
		
		$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultados as $res)
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public function delete()
	{
		$database = Database::getInstance()->connect();
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$database->query("DELETE FROM $tabla WHERE $identi = " . $this->campos[lcfirst($identi)]);
	}
	
	public function save()
	{
		$database = Database::getInstance()->connect();
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
		$database = Database::getInstance()->connect();
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