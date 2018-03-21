<?php
use Usuario\Auth;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 27/02/2018
 * Time: 1:10
 */
class PanelController
{
	public function renderPanel()
	{
		$auth = Auth::getAuth();
		if(!$auth->isUserLogged()) {
			die("Usuario no conectado");
		}
		echo Render::$twig->render("/panel/panel.twig");
	}
	
	public function renderEdicionPersonaje($nombrePersonaje)
	{
		$auth = Auth::getAuth();
		$nombreCorto = str_replace("-", " ", $nombrePersonaje);
		$personaje = Personaje::getByName($nombreCorto);
		echo Render::$twig->render("/panel/panel_edicion_personaje.twig", array('personaje' => $personaje));
	}
}