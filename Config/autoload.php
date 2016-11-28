<?php
header ("Content-Type: text/html; charset=utf-8");
date_default_timezone_set('Europe/Moscow');
error_reporting(E_ALL);

define('HOST', 'localhost');
define('DB', '');
define('USER', '');
define('PASS', '');

function autoload($class_name){
	$path = $_SERVER['DOCUMENT_ROOT']."/".str_replace("\\","/", $class_name).".php";
	
	if(file_exists($path)){
		include $path;
	}
}

spl_autoload_register('autoload');