<?php
namespace model\Usuario;

use ORM\Formatter;
use ORM\Model;
use Personaje;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 20:39
 */
class Visitante
{
	public $id;
	public function __construct()
	{
		$id = -1;
	}
	
	public function displayName()
	{
		return "$this->nombre $this->apellido";
	}
	
	public function isAdmin()
	{
		return $this->rol == "false";
	}
}