<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 23/02/2018
 * Time: 19:07
 */

namespace model;


use core\Database\Database;
use core\ORM\Formatter;
use core\ORM\Model;
use model\Experiencia\PaqueteExperiencia;
use model\Usuario\Usuario;
use PDO;

class Personaje extends Model
{
	protected static $tabla = "Personajes";
	protected static $identificador = "id";
	public static $progreso_experiencia = array(
		1 => 100,
		2 => 400,
		3 => 1000,
		4 => 2000,
		5 => 3500,
		6 => 5600,
		7 => 8400,
		8 => 12000,
		9 => 16500,
		10 => 22000,
		11 => 28600,
		12 => 36400,
		13 => 45000,
		14 => 56000,
		15 => 68000,
		16 => 81600,
		17 => 96900,
		18 => 114000,
		19 => 133000,
		20 => 190000,
		21 => 210000,
		22 => 231000,
		23 => 253000,
		24 => 276000,
		25 => 300000,
		26 => 325000,
		27 => 351000,
		28 => 378000,
		29 => 406000,
		30 => 435000
	);
	public static $colorDefault = "#5976ad";
	
	public static function getByName($nombre)
	{
		$format = new Formatter();
		$format->addWhere("LOWER(Nombre)", "LIKE", $nombre);
		return Personaje::where($format);
	}
	
	public function getJugador()
	{
		return Usuario::get($this->idUsuario);
	}
	
	public function getPartida()
	{
		return Partida::get($this->idPartida);
	}
	
	public function getClases()
	{
		return json_decode($this->claseNivel);
	}
	
	public function getXpActual()
	{
		if($this->getNivel() == 1) return $this->getXpTotal();
		return $this->getXpTotal() - self::$progreso_experiencia[$this->getNivel() - 1];
	}
	
	public function getXpTotal()
	{
		$database = Database::connect();
		$consulta = $database->query("SELECT SUM(Cantidad) FROM " . PaqueteExperiencia::$tabla . " WHERE idPersonaje = $this->id");
		$res = $consulta->fetch(PDO::FETCH_NUM)[0];
		if($res == null) return 0;
		return $res;
	}
	
	public function getXpNecesariaNivel($nivel)
	{
		if($nivel == 1) return self::$progreso_experiencia[$nivel];
		return self::$progreso_experiencia[$nivel] - self::$progreso_experiencia[$nivel - 1];
	}
	
	public function getNivel()
	{
		$actual = $this->getXpTotal();
		$ant = 1;
		foreach(self::$progreso_experiencia as $key => $value) {
			$ant = $key;
			if ($actual < $value) {
				return $ant;
			}
		}
		return $ant;
	}
	
	public function getPaquetesExperiencia()
	{
		$format = new Formatter();
		$format->addWhere("idPersonaje", "=", $this->id);
		$format->setOrder("id", "DESC");
		return PaqueteExperiencia::whereMultiple($format);
	}
	
	public function getClasesInString()
	{
		$clases = "";
		foreach($this->getClases() as $clase => $nivel)
		{
			$clases .= "$clase, ";
		}
		$clases = rtrim($clases, ", ");
		return $clases;
	}
	
	public function getNivelTotal()
	{
		$count = 0;
		foreach($this->getClases() as $key => $value)
		{
			$count += $value;
		}
		
		return $count;
	}
	
	public function getAvatar()
	{
		return $this->avatar;
	}
	
	public function getJournalsEscritos()
	{
		$formatter = new \ORM\Formatter();
		$formatter->addWhere("idPersonaje", "=", $this->id);
		return Journal_Participacion::whereMultiple($formatter);
	}
	
	public function addExperiencia($cantidad, $motivo, $entregador_id, $sesion)
	{
		$paquete = new Paquete_Experiencia;
		$paquete->idPersonaje = $this->id;
		$paquete->cantidad = $cantidad;
		$paquete->motivo = $motivo;
		$paquete->idEntregador = $entregador_id;
		$paquete->fecha = Carbon::now();
		$paquete->idJournal = $sesion;
		
		$paquete->insert();
	}
	
	public function tieneParticipacion($journal)
	{
		global $database;
		$consulta = $database->query("SELECT * FROM Journals_Participaciones WHERE idJournal = $journal->id AND idPersonaje = $this->id");
		
		return $consulta->num_rows > 0;
	}
	
	public function getSesionesAsistidas()
	{
		global $database;
		$lista = [];
		$consulta = $database->query("SELECT * FROM Journals INNER JOIN Journals_Asistencias ON Journals.Id = Journals_Asistencias.idJournal WHERE Journals_Asistencias.idPersonaje = $this->id");
		while($res = $consulta->fetch_assoc())
			$lista[] = Journal::createObjectFromArray($res);
		
		return $lista;
	}
	
	public function getColor()
	{
		return $this->color ? $this->color : Personaje::$colorDefault;
	}
	
	public function getColorFont()
	{
		$r = hexdec(substr($this->getColor(), 0, 2));
		$g = hexdec(substr($this->getColor(), 2, 2));
		$b = hexdec(substr($this->getColor(), 4, 2));
		$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
		return ($yiq >= 128) ? 'black' : 'white';
	}
	
	public function getUrlName()
	{
		$texto = strtolower(str_replace(" ", "-", $this->nombre));
		return $texto;
	}
}