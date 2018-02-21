<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 22:08
 */

namespace ORM;

use PDO;

class QueryBuilder
{
	private $listaCampos;
	private $query;
	
	private static $diccionarioTipos = array(
		"integer" => PDO::PARAM_INT,
		"string" => PDO::PARAM_STR,
		"NULL" => PDO::PARAM_NULL,
		"boolean" => PDO::PARAM_BOOL,
		"object" => PDO::PARAM_STR,
	);
	
	public function __construct($arreglo)
	{
		$this->listaCampos = [];
		foreach($arreglo as $campo => $valor)
		{
			echo "Asigno $campo a $valor <br>";
			$this->query .= "$campo = :$campo, ";
			
			// SETEO DE TIPOS
			$tipo = static::$diccionarioTipos[gettype($valor)];
			
			$this->listaCampos[] = array(
				"key" => $campo,
				"value" => $valor,
				"bind" => ":$campo",
				"type" => $tipo
			);
		}
		$this->query = rtrim($this->query, ", ");
	}
	
	public function getQuery() {
		return $this->query;
	}
	
	public function getListaCampos() {
		return $this->listaCampos;
	}
}