<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 2:55
 */

namespace model\Views\Notificaciones;



class Error extends TipoNotificacion
{
	public function __construct()
	{
		$this->icon = "fa fa-remove";
		$this->type = "red";
	}
}