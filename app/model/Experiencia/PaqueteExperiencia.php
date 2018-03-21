<?php
namespace model\Experiencia;


use core\ORM\Model;
use model\Personaje;

class PaqueteExperiencia extends Model
{
	public static $tabla = "Personajes_Experiencia";
	public static $identificador = "Id";
	
	public function getPersonaje()
	{
		return Personaje::get($this->idPersonaje);
	}
	
	public function getEntregador()
	{
		return Usuario::get($this->idEntregador);
	}
	
	public function getJournal()
	{
		return Journal::get($this->idJournal);
	}
}