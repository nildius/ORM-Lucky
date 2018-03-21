<?php
namespace model\Usuario;
use core\Database\Database;
use core\ORM\Formatter;
use core\ORM\Model;
use model\Partida;
use model\Personaje;
use PDO;

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
	
	private $managerPrivilegios;
	
	public function postLoad()
	{
		$this->managerPrivilegios = new GestorPrivilegios($this->id);
	}
	
	public function getMedallas()
	{
		$database = Database::connect();
		$lista = [];
		$consulta = $database->query("SELECT Usuarios_Medallas.* FROM Usuarios_Medallas INNER JOIN Usuarios_X_Medallas ON Usuarios_X_Medallas.idMedalla = Usuarios_Medallas.id WHERE Usuarios_X_Medallas.idUsuario = $this->id");
		while($res = $consulta->fetchObject())
			$lista[] = self::createObjectFromArray($res);
		
		return $lista;
	}
	
	public static function getUsuarioByCorreoAndPassword($correo, $password) {
		$formatter = new Formatter();
		$formatter->addWhere("Correo", "LIKE", $correo);
		$formatter->addWhere("Password", "LIKE", $password);
		return Usuario::where($formatter);
	}
	
	public static function getByName($nombre)
	{
		$format = new Formatter();
		$format->addWhere("LOWER(Nickname)", "LIKE", $nombre);
		return Usuario::where($format);
	}
	
	public function displayName()
	{
		return "$this->nombre $this->apellido";
	}
	
	public function isAdmin()
	{
		return $this->rol == "admin";
	}
	
	public function getPersonajes()
	{
		$where = new Formatter;
		$where->addWhere("idUsuario", "LIKE", $this->id);
		return Personaje::whereMultiple($where);
	}
	
	public function getPartidas()
	{
		$where = new Formatter;
		$where->addWhere("idDirector", "LIKE", $this->id);
		return Partida::whereMultiple($where);
	}
	
	public function addExperiencia($monto)
	{
		$nextlevel = $this->getCantidadExperienciaNextLevel();
		$resta = $nextlevel - $this->experiencia;
		if($this->experiencia + $monto > $nextlevel)
		{
			$this->experiencia = 0;
			$this->nivel++;
			$this->addExperiencia($monto - $resta);
			$this->addNotificacion("¡Tu cuenta ha subido a nivel $this->nivel!", "heart", "#cf4242", "perfil.php?id=$this->id");
		}
		$this->experiencia += $monto;
		$this->save();
	}
	
	public function getTitulo()
	{
		$titulos = ["Forastero", "Campesino", "Trabajador", "Noble", "Sabio", "Consejero del rey", "Principe", "Rey"];
		if($this->nivel <= 2) return "Forastero";
		if($this->nivel <= 3) return "Campesino";
		if($this->nivel <= 4) return "Trabajador";
		if($this->nivel <= 6) return "Noble";
		if($this->nivel <= 7) return "Sabio";
		if($this->nivel <= 9) return "Consejero del rey";
		if($this->nivel <= 11) return "Principe";
		return "Rey";
	}
	
	public function getCantidadExperienciaNextLevel()
	{
		return 100 + $this->nivel * 40;
	}
	
	public function getUrlName()
	{
		$texto = strtolower(str_replace(" ", "-", $this->nickname));
		return $texto;
	}
	
	public function esArtista()
	{
		$check = $this->managerPrivilegios->tienePrivilegio("ES_ARTISTA");
		return $check;
	}
}