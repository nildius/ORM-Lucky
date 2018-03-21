<?php
use core\Render;
use model\Personaje;

class PersonajeController
{
	
	public function renderPersonajes()
	{
		$listaPersonajes = Personaje::getAll();
		echo Render::$twig->render("/personajes/personajes_lista.twig", array('listaPersonajes' => $listaPersonajes));
	}
	
	public function renderPersonajePerfil($personajeNombre)
	{
		$nombreCorto = str_replace("-", " ", $personajeNombre);
		$personaje = Personaje::getByName($nombreCorto);
		$partida = $personaje->getPartida();
		echo Render::$twig->render("/personajes/personaje_perfil.twig", array('personaje' => $personaje, 'partida' => $partida));
	}
}