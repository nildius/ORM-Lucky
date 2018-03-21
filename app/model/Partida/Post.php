<?php
namespace model\Partida;

use core\ORM\Model;
use model\Personaje;

class Post extends Model
{
	protected static $tabla = "Sesiones_Posts";
	protected static $identificador = "Id";
	
	public function getJournal()
	{
		return Sesion::get($this->idJournal);
	}
	
	public function getPersonaje()
	{
		return Personaje::get($this->idPersonaje);
	}
}