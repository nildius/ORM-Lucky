<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 17/03/2018
 * Time: 0:10
 */

namespace model\Usuario;


use core\Database\Database;
use core\ORM\Formatter;
use core\ORM\Model;

class Sesion extends Model
{
	protected static $tabla = "Usuarios_Sesiones";
	protected static $identificador = "id";
	
	public function getUsuario() : ?Usuario {
		return Usuario::get($this->idUsuario);
	}
	
	public static function buscar(string $token) : ?Sesion {
		$format = new Formatter();
		$format->addWhere("Token", "=", $token);
		return Sesion::where($format);
	}
	
	public static function destruirSesionesDelUsuario(Usuario $usuario) {
		$database = Database::connect();
		$database->query("DELETE FROM Usuarios_Sesiones WHERE idUsuario = $usuario->id");
	}
}