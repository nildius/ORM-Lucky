<?php

use core\Render;
use model\Partida;
use model\Personaje;

class PartidaController
{
	public function renderPartidaPerfil($nombre)
	{
		$nombre_recortado = str_replace("-", " ", $nombre);
		
		$partida = Partida::getByName($nombre_recortado);
		$listaPersonajes = $partida->getPersonajes();
		echo Render::$twig->render("/partidas/partida_perfil.twig", array('partida' => $partida, 'listaPersonajes' => $listaPersonajes));
	}
	
	public function renderPartidaPerfilPersonaje($partidaNombre, $personajeNombre) {
		$partidaNombreRecortado = str_replace("-", " ", $partidaNombre);
		$personajeNombreRecortado = str_replace("-", " ", $personajeNombre);
		
		$partida = Partida::getByName($partidaNombreRecortado);
		$personaje = Personaje::getByName($personajeNombreRecortado);
		echo Render::$twig->render("/partidas/partida_perfil_personaje.twig", array('partida' => $partida, 'personaje' => $personaje));
	}
}