<?php
namespace model\Album;
use core\ORM\Formatter;
use core\ORM\Model;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 1:26
 */
class Album extends Model
{
	protected static $tabla = "Albumes";
	protected static $identificador = "id";
	
	public static function getAlbumesDeDibujos()
	{
		$format = new Formatter();
		$format->addWhere("Tipo", "=", "dibujos");
		return Album::whereMultiple($format);
	}
	
	public static function getAlbumesDeComics()
	{
		$format = new Formatter();
		$format->addWhere("Tipo", "=", "comics");
		return Album::whereMultiple($format);
	}
	
	public static function getAlbumesDeComicsDeUsuario($id)
	{
		$format = new Formatter();
		$format->addWhere("Tipo", "=", "comics");
		$format->addWhere("idUsuario", "=", "$id");
		return Album::whereMultiple($format);
	}
	
	public function getDibujos()
	{
		$format = new Formatter();
		$format->addWhere("idAlbum", "=", $this->id);
		$format->setOrder("Orden", "ASC");
		return Dibujo::whereMultiple($format);
	}
	
	public function cantidadDeDibujos()
	{
		return count($this->getDibujos());
	}
	
	public function getFotoRepresentativa()
	{
		$dibujos = $this->getDibujos();
		if(count($dibujos) == 0)
			return null;
		$dibujo = $dibujos[0];
		return $dibujo;
	}
}