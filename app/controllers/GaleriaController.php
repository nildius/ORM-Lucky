<?php
use core\Render;
use MiladRahimi\PHPRouter\Request;
use model\Album\Album;
use model\Usuario\Auth;

class GaleriaController {
	
	public function indexGaleriaDibujos()
	{
		$albumes = Album::getAlbumesDeDibujos();
		echo Render::$twig->render("galeria/dibujos.twig", array("albumes" => $albumes));
	}
	
	public function indexGaleriaComics(Request $request)
	{
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		if($request->get('param') == "misAlbumes")
			$albumes = Album::getAlbumesDeComicsDeUsuario($usuario->id);
		else
			$albumes = Album::getAlbumesDeComics();
		echo Render::$twig->render("galeria/comics.twig", array("albumes" => $albumes));
	}
	
	
	public function indexCrearAlbum()
	{
		$albumes = Album::getAll();
		echo Render::$twig->render("galeria/galeria_crear_album.twig", array("albumes" => $albumes));
	}
	
	public function indexEditarAlbum($id)
	{
		$album = Album::findOrFail($id);
		echo Render::$twig->render("galeria/galeria_editar_album.twig", array("album" => $album));
	}
}