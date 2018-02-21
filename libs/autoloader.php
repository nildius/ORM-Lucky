<?php
function __autoload($nombre_clase)
{
	$directories = array(
		'system/',
		'system/Database/',
		'system/ORM/',
		'system/Exceptions/',
		'class/',
		'class/Usuario/'
	);
	
	foreach($directories as $directory)
	{
		$parts = explode('\\', $nombre_clase);
		$nombre_recortado = end($parts);
		$ruta = DIRECTORY_LIBS . $directory.$nombre_recortado . '.php';
		if(file_exists($ruta))
		{
			require_once($directory.$nombre_recortado . '.php');
			return;
		}
	}
}