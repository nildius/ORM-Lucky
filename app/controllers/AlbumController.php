<?php
use core\ORM\Carbon;
use core\Render;
use MiladRahimi\PHPRouter\Request;
use MiladRahimi\PHPRouter\Response;
use model\Album\Album;
use model\Album\Dibujo;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 22:38
 */
class AlbumController
{
	public function indexAlbumDibujos($id)
	{
		$album = Album::findOrFail($id);
		echo Render::$twig->render("galeria/dibujos_perfil.twig", array("album" => $album));
	}
	
	public function indexAlbumComics($id, Request $request)
	{
		$album = Album::findOrFail($id);
		echo Render::$twig->render("galeria/comics_perfil.twig", array("album" => $album));
	}
	
	public function crearAlbum(Request $request, Response $response)
	{
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		$album = new Album();
		$album->nombre = $request->post("nombre");
		$album->descripcion = $request->post("desc");
		$album->fecha_creacion = Carbon::now();
		$album->deleted = 0;
		$album->idUsuario = $usuario->id;
		$album->tipo = $request->post("tipo");
		$album->insert();
		
		$nombre_directorio = $usuario->getUrlName() . "-$album->id";
		$album->directorio = $nombre_directorio;
		$album->save();
		mkdir(DIRECTORY_ROOT . "/uploads/albumes/$nombre_directorio");
		
		$response->redirect("/galeria/editarAlbum/$album->id");
	}
	
	public function cargarFotos(Request $request, Response $response)
	{
		$auth = Auth::getAuth();
		$usuario = $auth->getUsuario();
		
		$album = Album::get($request->post('id'));
		$archivos = ReArrangeFiles($_FILES['dibujos']);
		$contador = 0;
		foreach($archivos as $archivo)
		{
			$foto = new \Factories\FotoFactory($archivo);
			$foto->setNombre(time() . "-$contador");
			$contador++;
			$foto->moveTo(DIRECTORY_ROOT . "/uploads/albumes/$album->directorio/");
			$foto->createThumbnail(DIRECTORY_ROOT . "/uploads/albumes/$album->directorio", 300, 300);
			
			$dibujo = new Dibujo();
			$dibujo->idAlbum = $album->id;
			$dibujo->archivo = $foto->getNombre();
			$dibujo->archivo_thumb = $foto->getNombreThumbnail();
			$dibujo->idUsuario = $usuario->id;
			$dibujo->fecha_creacion = Carbon::now();
			$dibujo->insert();
		}
		
		$auth->agregarExito("Se han cargado $contador dibujos exitosamente.");
		$response->redirect("/galeria/editarAlbum/$album->id");
	}
}