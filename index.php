<?php
require_once("config.php");

echo "Hacemos las pruebiñas<br><br>";
$user = Usuario::get(3);

echo "YO SOY $user->id Y ME LLAMO $user->nombre <br><br>";
$user->id = 3;
$user->nombre = "BESPUCIO";

$user->save();