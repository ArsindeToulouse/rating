<?php
//session_start();

header ("Content-Type: text/html; charset=utf-8");
date_default_timezone_set('Europe/Moscow');
error_reporting(E_ALL);
//require_once __DIR__.'\vendor\autoload.php';

define('HOST', 'localhost');
define('DB', 'global');
define('USER', 'mysql');
define('PASS', 'mysql');

/*define('DB', 'ushina');
define('USER', 'ushina');
define('PASS', 'neto0642');*/

function autoload($class_name){
	$path = $_SERVER['DOCUMENT_ROOT']."/".str_replace("\\","/", $class_name).".php";
	//$path = $_SERVER['DOCUMENT_ROOT']."\\".$class_name.".php";
	//echo "Path: {$path}<br>";
	
	if(file_exists($path)){
		//echo "Подключен файл: ".$path."<br>";
		include $path;
	}
}

spl_autoload_register('autoload');