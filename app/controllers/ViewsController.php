<?php
use core\Render;
use model\Noticia;
use model\Partida;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 23/02/2018
 * Time: 2:37
 */
class ViewsController
{
	public function renderHome()
	{
		$noticias = Noticia::getAll();
		echo Render::$twig->render("home.twig", array("noticias" => $noticias));
	}
	
	public function renderPartidas()
	{
		$partidas = Partida::getAll();
		echo Render::$twig->render("/partidas/partidas_lista.twig", array('listaPartidas' => $partidas));
	}

}