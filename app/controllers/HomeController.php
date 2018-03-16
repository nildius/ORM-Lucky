<?php
use model\Usuario\Usuario;
use Tightenco\Collect\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Lucky
 * Date: 16/03/2018
 * Time: 12:33
 */
class HomeController
{
	public function index()
	{
		$usuarios = Usuario::getAll();
		foreach($usuarios as $user)
		{
			echo "=> ".$user->displayName()." <br>";
		}
	}
}