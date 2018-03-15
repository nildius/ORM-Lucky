<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 2:54
 */

namespace model\Views\Notificaciones;


abstract class TipoNotificacion
{
	protected $icon;
	protected $type;
	protected $texto;
	
	public function setTexto($texto)
	{
		$this->texto = $texto;
	}
	
	public function getIcon() {
		return $this->icon;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getTexto() {
		return $this->texto;
	}
}