<?php
namespace model;
use core\ORM\Formatter;
use core\ORM\Model;
use model\Partida\Sesion;
use model\Usuario\Usuario;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 23/02/2018
 * Time: 18:15
 */
class Partida extends Model
{
	protected static $tabla = "Partidas";
	protected static $identificador = "id";
	
	public static function getByName($nombre)
	{
		$format = new Formatter();
		$format->addWhere("LOWER(Nombre)", "LIKE", $nombre);
		return Partida::where($format);
	}
	
	public static function getByUrlName(string $nombre)
	{
		$nombre_recortado = str_replace("-", " ", $nombre);
		$format = new Formatter();
		$format->addWhere("LOWER(Nombre)", "LIKE", $nombre_recortado);
		return Partida::where($format);
	}

	public function getPersonajes()
	{
		$format = new Formatter();
		$format->addWhere("idPartida", "=", $this->id);
		$format->setOrder("Nombre", "ASC");
		return Personaje::whereMultiple($format);
	}
	
	public function getDirector()
	{
		return Usuario::get($this->idDirector);
	}
	
	public function getCantidadJugadores()
	{
		return count($this->getPersonajes());
	}
	
	public function getUrlName()
	{
		$texto = strtolower(str_replace(" ", "-", $this->nombre));
		return $texto;
	}
	
	public function getSesiones()
	{
		$format = new Formatter();
		$format->addWhere("idPartida", "=", $this->id);
		return Sesion::whereMultiple($format);
	}
}