<?php
header("Content-type: text/plain; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
//header("Cache-Control: no-cache");
header("Pragma: no-cache");

require_once "start.php";
use Core\Rate\Rate;
use Core\Rate\Response;

$rate = new Rate($pdo);
$response = new Response();

if (!empty($_GET)) {
	$result = $rate->checkInfo($_GET['hash']);
	if ($result) {
		//$str = mb_convert_encoding("Вы уже голосовали!!!", "UTF-8");
		$response->pushItem('msg', "Вы уже голосовали!!!");
		$response->pushItem('id', $result['id']);
		$response->pushItem('comment', $result['comment']);
		$response->pushItem('rate', $result['rate']);
		$response->pushItem('ls', $result['rating_date']);
		$response->pushItem('result', true);
	}else{
		$response->pushItem('result', $result);
	}
	echo $response->getJSON();
}
