<?php
namespace model;

use core\ORM\Carbon;
use core\ORM\Formatter;
use core\ORM\Model;
use model\Usuario\Usuario;
use Tightenco\Collect\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 07/06/2017
 * Time: 20:23
 */
class Noticia extends Model
{
	public static $tabla = "Noticias";
	public static $identificador = "Id";
	
	public static function getAll() : Collection
	{
		$format = new Formatter();
		$format->setOrder("Fecha_creacion", "DESC");
		return Noticia::whereMultiple($format);
	}
	
	public function getUsuario()
	{
		if($this->idUsuario)
			return Usuario::get($this->idUsuario);
		return null;
	}
	
	public function tiempoPasado()
	{
		//return $this->fecha_creacion->diffForHumans(Carbon::now());
	}
}