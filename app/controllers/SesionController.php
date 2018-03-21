<?php
use core\Render;
use model\Partida;
use Tightenco\Collect\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 17/03/2018
 * Time: 0:25
 */
class SesionController
{
	public function index($nombre, $id)
	{
		$partida = Partida::getByUrlName($nombre);
		$sesion = Partida\Sesion::get($id);
		
		$experiencias = $sesion->getExperiencias();
		$experiencias_personajes = [];
		foreach($experiencias as $exp)
		{
			$pj = $exp->getPersonaje();
			if(!isset($experiencias_personajes[$pj->id]))
				$experiencias_personajes[$pj->id] = array("pj" => $pj, "cantidad" => 0);
			
			$experiencias_personajes[$pj->id]["cantidad"] += $exp->cantidad;
		}
		
		echo Render::$twig->render("sesiones/sesion_perfil.twig", array(
			"partida" => $partida,
			"sesion" => $sesion,
			"listaExperiencias" => $experiencias_personajes,
			"participaciones" => $sesion->getPosts()
		));
	}
}