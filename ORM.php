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
	}
	
	public static function createObjectFromArray($arreglo)
	{
		$obj = new static;
		foreach($arreglo as $key => $value)
		{
			$key_minus = lcfirst($key);
			if(isValidDateTime($value)) {
				$fecha = Carbon::parse($value);
				$obj->campos[$key_minus] = $fecha;
				continue;
			}
			$obj->campos[$key_minus] = $value;
		}
		
		return $obj;
	}
	
	// FIN METODOS MAGICOS
	
	public static function where(Where $where)
	{
		global $database;
		$tabla = static::$tabla;
		
		$query = "SELECT * FROM $tabla WHERE " . $where->format();
		
		$consulta = $database->query($query);
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
		
		$query = "SELECT * FROM $tabla WHERE " . $where->format();
		
		$consulta = $database->query($query);
		while($res = $consulta->fetch_assoc())
		{
			array_push($lista, self::createObjectFromArray($res));
		}
		
		return $lista;
	}
	
	public static function get($id)
	{
		global $database;
		$tabla_format = static::$tabla;
		$identificador = static::$identificador;
		$consulta = $database->query("SELECT * FROM $tabla_format WHERE $identificador = $id");
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
		while($res = $consulta->fetch_assoc())
		{
			array_push($lista, self::createObjectFromArray($res));
		}
		
		return $lista;
	}
	
	public function delete()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$consulta = $database->query("DELETE FROM $tabla WHERE $identi = " . $this->campos[lcfirst($identi)]);
	}
	
	public function save()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$lista = array();
		$query = "UPDATE $tabla SET ";
		foreach($this->campos as $key => $value) {
			if($value != "") {
				if(is_numeric($value))
					$query .= "$key = $value, ";
				else
					$query .= "$key = '$value', ";
			}
		}
		$query = rtrim($query, ", ");
		$query .= " WHERE $identi = " . $this->campos[lcfirst($identi)];
		
		$consulta = $database->query($query);
	}
	
	public function insert()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$lista = array();
		$query = "INSERT INTO $tabla SET ";
		foreach($this->campos as $key => $value) {
			if($value != "") {
				if(is_numeric($value))
					$query .= "$key = $value, ";
				else
					$query .= "$key = '$value', ";
			}
		}
		$query = rtrim($query, ", ");
		$consulta = $database->query($query);
		
		// Recuperamos la ID
		$consulta2 = $database->query("SELECT * FROM $tabla ORDER BY $identi DESC");
		$res2 = $consulta2->fetch_assoc();
		
		$this->campos[lcfirst($identi)] = $res2[$identi];
	}
	
	public function jsonSerialize()
	{
		return $this->campos;
	}
}

class Where {
	private $query;
	
	public function __construct() {
		$this->query = [];
	}
	
	public function addWhere($campo, $operador, $valor) {
		$data = [$campo, $operador, $valor];
		
		array_push($this->query, $data);
	}
	
	public function format() {
		$consulta = "";
		foreach($this->query as $parametro) {
			$campo = $parametro[0];
			$operador = $parametro[1];
			$valor = $parametro[2];
			
			$consulta .= "$campo $operador '$valor' AND ";
		}
		$consulta = rtrim($consulta, " AND ");
		return $consulta;
	}
}