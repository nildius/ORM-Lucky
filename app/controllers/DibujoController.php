<?php
use Album\Album;
use Album\Dibujo;
use MiladRahimi\PHPRouter\Request;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 01/03/2018
 * Time: 22:05
 */

class DibujoController {
	public function actualizarOrden($id, Request $request)
	{
		$album = Album::get($id);
		$lista = json_decode($request->post('tabla'));
		foreach($lista as $elem) {
			$dibujo = Dibujo::get($elem->id);
			$dibujo->orden = $elem->orden;
			$dibujo->save();
		}
	}
}