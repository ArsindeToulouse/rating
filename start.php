<?php
//require_once __DIR__.'\vendor\autoload.php';
require_once "Config/autoload.php";

use Core\DB\ConnectDB;

//$loader = new Twig_Loader_Filesystem('templates');
//$twig = new Twig_Environment($loader, array('cache' => 'tmp/cache', 'auto_reload' => true));

//$twig->getExtension('Twig_Extension_Core')->setTimezone('Europe/Moscow');

$db = ConnectDB::getInstance(HOST, DB, USER, PASS);
$pdo = $db->getPDO();