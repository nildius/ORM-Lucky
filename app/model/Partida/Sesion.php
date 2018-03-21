<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 09/03/2018
 * Time: 0:53
 */

namespace model\Partida;

use core\Database\Database;
use core\ORM\Formatter;
use core\ORM\Model;
use model\Experiencia\PaqueteExperiencia;
use model\Partida;
use model\Personaje;
use model\Usuario\Usuario;
use PDO;

class Sesion extends Model
{
	protected static $tabla = "Sesiones";
	protected static $identificador = "id";
	
	public function getPartida()
	{
		return Partida::get($this->idPartida);
	}
	
	public function getJugador()
	{
		return Usuario::get($this->idCreador);
	}
	
	public function deleteAllAsistencias()
	{
		global $database;
		return $database->query("DELETE FROM Journals_Asistencias WHERE idJournal = $this->id");
	}
	
	public function insertAsistencia($lista_personajes)
	{
		global $database;
		foreach($lista_personajes as $id)
			$database->query("INSERT INTO Journals_Asistencias SET idJournal = $this->id, idPersonaje = $id");
	}
	
	public function getAsistencias()
	{
		$lista = [];
		global $database;
		$consulta =	$database->query("SELECT * FROM Journals_Asistencias WHERE idJournal = $this->id");
		while($res = $consulta->fetch_assoc())
			array_push($lista, $res['idPersonaje']);
		return $lista;
	}
	
	public function getPosts()
	{
		$where = new Formatter;
		$where->addWhere("idSesion", "=", $this->id);
		return Post::whereMultiple($where);
	}
	
	public function getExperiencias()
	{
		$where = new Formatter;
		$where->addWhere("idJournal", "=", $this->id);
		return PaqueteExperiencia::whereMultiple($where);
	}
	
	public function esUltimo() : bool
	{
		$database = Database::connect();
		$consulta = $database->query("SELECT * FROM Sesiones WHERE idPartida = $this->idPartida ORDER BY Id DESC");
		$res = $consulta->fetch(PDO::FETCH_ASSOC);
		
		return $this->id == $res['Id'];
	}
	
	public function getPersonajeSemana() : ?Personaje
	{
		return Personaje::get($this->idPersonajeSemana);
	}
	
	public function getResumen()
	{
		$largo = 200;
		$texto = strip_tags($this->descripcion);
		if(strlen($texto) < $largo)
			return $texto;
		
		$texto = substr($texto, 0, $largo);
		return $texto . "...";
	}
}