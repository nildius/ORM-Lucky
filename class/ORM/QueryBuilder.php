<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 22:08
 */

namespace ORM;

class QueryBuilder
{
	private $listaCampos;
	private $query;
	private $tipos;
	private $listaValores;
	
	public function __construct($arreglo)
	{
		$this->tipos = "";
		$this->listaValores = [];
		$this->listaCampos = $arreglo;
		foreach($arreglo as $campo => $valor)
		{
			$this->query .= "$campo = ?, ";
			
			// SETEO DE TIPOS
			$tipo = "s";
			if(is_int($valor))
				$tipo = "i";
			$this->tipos .= $tipo;
			
			$this->listaValores[] = $valor;
		}
		$this->query = rtrim($this->query, ", ");
	}
	
	public function getQuery() {
		return $this->query;
	}
	
	public function getTipos() {
		return $this->tipos;
	}
	
	public function getValores() {
		return $this->listaValores;
	}
}