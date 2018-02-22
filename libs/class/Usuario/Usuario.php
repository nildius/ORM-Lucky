<?php
namespace Usuario;
use ORM\Model;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 08/02/2018
 * Time: 20:39
 */
class Usuario extends Model
{
	protected static $tabla = "Usuarios";
	protected static $identificador = "id";
}