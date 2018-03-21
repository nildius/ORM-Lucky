<?php
/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 23:03
 */

namespace model\Usuario;


use core\Database\Database;
use PDO;

class GestorPrivilegios
{
	private $id;
	
	public function __construct($idUsuario)
	{
		$this->id = $idUsuario;
	}
	
	public function agregarPrivilegio($texto)
	{
		$database = Database::connect();
		$consulta = $database->query("INSERT INTO Usuarios_Privilegios SET idUsuario = $this->id, Privilegio = '$texto'");
	}
	
	public function tienePrivilegio($texto)
	{
		$database = Database::connect();
		$consulta = $database->query("SELECT * FROM Usuarios_Privilegios WHERE idUsuario = $this->id AND Privilegio = '$texto'");
		$res = $consulta->fetch(PDO::FETCH_ASSOC);
		if($res) return true;
		return false;
	}
	
	public function borrarPrivilegio($texto)
	{
		$database = Database::connect();
		$database->query("DELETE FROM Usuarios_Privilegios WHERE idUsuario = $this->id, Privilegio = '$texto'");
	}
}