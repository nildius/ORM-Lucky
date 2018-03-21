<?php

require_once("core.php");

function ReArrangeFiles( $arr ){
	foreach( $arr as $key => $all ){
		foreach( $all as $i => $val ){
			$new[$i][$key] = $val;
		}
	}
	return $new;
}