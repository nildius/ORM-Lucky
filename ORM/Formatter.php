<?php
namespace ORM;

class Formatter {
	private $query;
	private $order;
	
	public function __construct() {
		$this->query = [];
		$this->order = [];
		$this->limit = "";
		$this->offset = "";
	}
	
	public function addWhere($campo, $operador, $valor) {
		$data = [$campo, $operador, $valor];
		
		array_push($this->query, $data);
	}
	
	public function setOrder($campo, $tipo) {
			$this->order[] = "$campo $tipo";
	}
	
	public function setLimit($num) {
		$this->limit = $num;
	}
	
	public function setOffset($num) {
		$this->offset = $num;
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
		if(count($this->order) > 0) {
			$consulta .= "ORDER BY ";
			foreach ($this->order as $orden) {
				$consulta .= " $orden,";
			}
		}
		$consulta = rtrim($consulta, ",");
		if($this->limit)
			$consulta .= " LIMIT $this->limit";
		if($this->offset)
			$consulta .= " OFFSET $this->limit";
		return $consulta;
	}
}