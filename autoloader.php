<?php
function __autoload($nombre_clase)
{
	$directorys = array(
		'class/',
		'class/ORM/',
		'class/Exceptions/'
	);
	
	foreach($directorys as $directory)
	{
		$todo = $directory.$nombre_clase . '.php';
		if(file_exists($directory.$nombre_clase . '.php'))
		{
			require_once("".$directory.$nombre_clase . '.php');
			return;
		}
	}
}