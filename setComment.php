<?php
header("Content-type: text/plain; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once "start.php";
use Core\Rate\Rate;
use Core\Rate\Response;

$rate = new Rate($pdo);
$response = new Response();

if (!empty($_POST)) {
	if (!empty($_POST['id'])) {
		$rate->updateRate($_POST);
		$response->pushItem('msg', "Ваше мнение изменилось!");
	}else{
		$id = $rate->addNewRate($_POST);
		$response->pushItem('id', $id);
		$response->pushItem('msg', "Спасибо, что высказали свое мнение!");
	}

	echo $response->getJSON();
}