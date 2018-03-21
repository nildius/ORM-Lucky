<?php
use core\Render;
use model\Usuario\Usuario;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 24/02/2018
 * Time: 2:41
 */
class JugadorController
{
	public function renderJugadores()
	{
		$listaJugadores = Usuario::getAll();
		echo Render::$twig->render("/jugadores/jugadores_lista.twig", array('listaJugadores' => $listaJugadores));
	}
	
	public function renderJugadorPerfil($personajeNombre)
	{
		$nombre = str_replace("-", " ", $personajeNombre);
		$jugador = Usuario::getByName($nombre);
		if(!$jugador)
			die("Nope");
		echo Render::$twig->render("/jugadores/jugadores_perfil.twig", array('jugador' => $jugador));
	}
}