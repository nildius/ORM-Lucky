<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 2:55
 */

namespace model\Views\Notificaciones;



class Exito extends TipoNotificacion
{
	public function __construct()
	{
		$this->icon = "fa fa-check";
		$this->type = "green";
	}
}