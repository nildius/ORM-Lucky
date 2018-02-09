<?php
namespace ORM;

use Carbon\Carbon as Carbon;

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
		global $database;
		$tabla = static::$tabla;
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		if (!$consulta)
			throw new \Exception('QUERY ERROR: ' . $database->error);
		if($consulta->num_rows == 0)
			return null;
		$res = $consulta->fetch_assoc();
		
		return static::createObjectFromArray($res);
	}
	
	public static function whereMultiple(Formatter $where)
	{
		global $database;
		$tabla = static::$tabla;
		$lista = [];
		
		$query = "SELECT * FROM $tabla " . $where->format();
		
		$consulta = $database->query($query);
		//echo $query ."<br>";
		while($res = $consulta->fetch_assoc())
			array_push($lista, self::createObjectFromArray($res));
		
		return $lista;
	}
	
	public static function get($id)
	{
		$database = \Database::getInstance()->getConexion();
		if($id === null)
			return null;
		$tabla_format = static::$tabla;
		$identificador = static::$identificador;
		$pedido = "SELECT * FROM $tabla_format WHERE $identificador = ?";
		$query = $database->prepare($pedido);
		$query->bind_param("i", $id);
		$query->execute();
		$resultado = $query->get_result();
		if ($query->error)
			throw new \Exception('QUERY ERROR: ' . $query->error);
		
		if ($resultado->num_rows == 1)
		{
			$data = $resultado->fetch_assoc();
			return self::createObjectFromArray($data);
		}
		
		$query->free_result();
	}
	
	public static function getAll()
	{
		global $database;
		$lista = [];
		$tabla = static::$tabla;
		$consulta = $database->query("SELECT * FROM $tabla");
		if (!$consulta)
			throw new \Exception('QUERY ERROR: ' . $database->error);
		
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
			throw new \Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function save()
	{
		$database = \Database::getInstance()->getConexion();
		$queryBuilder = new QueryBuilder($this->campos);
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$consulta = "UPDATE $tabla SET ";
		$consulta .= $queryBuilder->getQuery();
		$consulta .= " WHERE $identi = " . $this->campos[lcfirst($identi)];
		$query = $database->prepare($consulta);
		
		$listaValoresPorReferencia = [];
		foreach($queryBuilder->getValores() as $valor) {
			$listaValoresPorReferencia[] = &$valor;
		}
		call_user_func_array(array($query, "bind_param"), array_merge(array($queryBuilder->getTipos(), $listaValoresPorReferencia)));
		$query->execute();
		if($query->error)
			throw new \Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function insert()
	{
		global $database;
		$tabla = static::$tabla;
		$identi = static::$identificador;
		$lista = array();
		$query = "INSERT INTO $tabla SET ";
		$query .= Model::queryBuilder($this->campos);
		$consulta = $database->query($query);
		$this->campos[lcfirst($identi)] = $database->insert_id; #Recuperamos el ID Autogenerado y lo guardamos en el objeto.
		
		if($consulta)
			return true;
		throw new \Exception('QUERY ERROR: ' . $database->error);
	}
	
	public function jsonSerialize()
	{
		return $this->campos;
	}
}