<?php

use Carbon\Carbon as Carbon;

abstract class ORM implements \jsonSerializable {
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
			if(isValidDateTime($value))
				$value = Carbon::parse($value);
			$obj->campos[$key_minus] = $value;
		}
		
		return $obj;
	}
	
	// FIN METODOS MAGICOS
	
	public static function where(Where $where)
	{
		global $database;
		$tabla = static::$tabla;
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		if (!$consulta)
			throw new Exception('QUERY ERROR: ' . $database->error);
		if($consulta->num_rows == 0)
			return null;
		$res = $consulta->fetch_assoc();
		
		return static::createObjectFromArray($res);
	}
	
	public static function whereMultiple(Where $where)
	{
		global $database;
		$tabla = static::$tabla;
		$lista = [];
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		while($res = $consulta->fetch_assoc())
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function get($id)
	{
		global $database;
		$tabla_format = static::$tabla;
		$identificador = static::$identificador;
		$consulta = $database->query("SELECT * FROM $tabla_format WHERE $identificador = $id");
		if (!$consulta)
			throw new Exception('QUERY ERROR: ' . $database->error);
		
		if ($consulta && $consulta->num_rows == 1)
		{
			$res = $consulta->fetch_assoc();
			return self::createObjectFromArray($res);
		}
	}
	
	public static function getAll()
	{
		global $database;
		$lista = [];
		$tabla = static::$tabla;
		$consulta = $database->query("SELECT * FROM $tabla");
		if (!$consulta)
			throw new Exception('QUERY ERROR: ' . $database->error);
		
		while($res = $consulta->fetch_assoc())
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public function delete()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$consulta = $database->query("DELETE FROM $tabla WHERE $identi = " . $this->campos[lcfirst($identi)]);
		if (!$consulta)
			throw new Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function save()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$query = "UPDATE $tabla SET ";
		$query .= ORM::queryBuilder($this->campos);
		$query .= " WHERE $identi = " . $this->campos[lcfirst($identi)];
		$consulta = $database->query($query);
		if($consulta)
			return true;
		throw new Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function insert()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$lista = array();
		$query = "INSERT INTO $tabla SET ";
		$query .= ORM::queryBuilder($this->campos);
		$consulta = $database->query($query);
		$this->campos[lcfirst($identi)] = $database->insert_id; #Recuperamos el ID Autogenerado y lo guardamos en el objeto.
		
		if($consulta)
			return true;
		throw new Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function jsonSerialize()
	{
		return $this->campos;
	}
	
	private static function queryBuilder($array)
	{
		$query = "";
		foreach($array as $campo => $valor)
		{
			if($valor === null)
				$query .= "$campo = null, ";
			else if(is_numeric($valor))
				$query .= "$campo = $valor, ";
			else
				$query .= "$campo = '$valor', ";
		}
		$query = rtrim($query, ", ");
		
		return $query;
	}
}

class Where {
	private $query;
	private $order;
	
	public function __construct() {
		$this->query = [];
		$this->order = "";
	}
	
	public function addWhere($campo, $operador, $valor) {
		$data = [$campo, $operador, $valor];
		
		array_push($this->query, $data);
	}
	
	public function setOrder($campo, $tipo) {
		$this->order = "ORDER BY $campo $tipo";
	}
	
	public function format() {
		$consulta = "";
		if(!empty($this->query))
			$consulta = "WHERE ";
		foreach($this->query as $parametro) {
			$campo = $parametro[0];
			$operador = $parametro[1];
			$valor = $parametro[2];
			if($valor === NULL)
				$consulta .= "$campo $operador NULL AND ";
			else
				$consulta .= "$campo $operador '$valor' AND ";
		}
		$consulta = rtrim($consulta, " AND ");
		if($this->order)
			$consulta .= " ".$this->order;
		return $consulta;
	}
}