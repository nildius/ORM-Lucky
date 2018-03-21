<?php
namespace model\Factories;

use Zebra_Image;

class FotoFactory
{
	private static $listaFormatosValidos = ["JPG", "PNG", "BMP", "JPEG"];
	private $nombre;
	private $nombre_thumb;
	private $nombre_interno;
	private $nombre_watermark;
	private $tmp_name;
	public $extension;
	private $ruta;
	
	public function __construct($archivo)
	{
		$this->nombre_interno = $archivo['name'];
		$pack = explode(".", $this->nombre_interno);
		$this->extension = end($pack);
		$this->tmp_name = $archivo['tmp_name'];
	}
	
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
		$this->nombre_thumb = "{$this->nombre}_thumb";
		$this->nombre_watermark = "{$this->nombre}_watermark";
	}
	
	public function getNombre()
	{
		return $this->nombre . "." . $this->extension;
	}
	
	public function getNombreThumbnail()
	{
		return $this->nombre_thumb . "." . $this->extension;
	}
	
	public function getNombreWater()
	{
		return $this->nombre_watermark . "." . $this->extension;
	}
	
	public function isValidFormat()
	{
		$extension_mayus = strtoupper($this->extension);
		if(!in_array($extension_mayus, static::$listaFormatosValidos)) {
			return false;
		}
		return true;
	}
	
	public function moveTo($fichero)
	{
		$total = $fichero . $this->nombre . "." . $this->extension;
		move_uploaded_file($this->tmp_name, $total);
	}
	
	public function createThumbnail($ruta, $width, $height)
	{
		$image = new Zebra_Image();
		$image->source_path = "$ruta/{$this->getNombre()}";
		$image->target_path = "$ruta/{$this->getNombreThumbnail()}";
		$image->resize($width, $height, ZEBRA_IMAGE_CROP_CENTER);
	}
}