<?php
namespace core\ORM;

class Formatter {
	private $listaCampos;
	private $order;
	
	public function __construct() {
		$this->query = [];
		$this->order = [];
		$this->listaCampos = [];
		$this->limit = "";
		$this->offset = "";
	}
	
	public function addWhere($campo, $operador, $valor) {
		$data = [$campo, $operador, $valor];
		
		array_push($this->listaCampos, $data);
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
		if(!empty($this->listaCampos))
			$consulta = "WHERE ";
		foreach($this->listaCampos as $campo) {
			$key = $campo[0];
			$operador = $campo[1];
			$valor = $campo[2];
			if($valor === NULL)
				$consulta .= "$key $operador NULL AND ";
			else
				$consulta .= "$key $operador '$valor' AND ";
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