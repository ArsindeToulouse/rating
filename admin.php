<?php
session_start();
require_once "start.php";
use Core\Rate\Rate;

$rate = new Rate($pdo);
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

$filter = array('rateFilter'=>'', 'readFilter'=>'');

if (!empty($_SESSION && $_SESSION['sess'] === $_COOKIE['PHPSESSID'])) {
	// Изменение значения прочитано(1)/непрочитано(0)
	if(!empty($_POST['readed'])){
		$rate->changeReadStatus($_POST['readed'], 1);
		header('Location: '.$_SERVER['PHP_SELF']);
        die();
	}
	if(!empty($_POST['unreaded'])){
		$rate->changeReadStatus($_POST['unreaded'], 0);
		header('Location: '.$_SERVER['PHP_SELF']);
        die();
	}
	// Удаляем комментарий
	if(!empty($_POST['delete'])){
		$rate->deleteComment($_POST['delete']);
		header('Location: '.$_SERVER['PHP_SELF']);
        die();
	}
	// получаем данные для таблицы вывода комментариев
	if(empty($_POST)){
		$rows = $rate->getCommentRows();
	}
	// Сортировка
	if(!empty($_POST['sorting'])){
		if ($_POST['sorting'] === 'asc') {
			$rows = $rate->sortAcs();
		}
		if ($_POST['sorting'] === 'desc') {
			$rows = $rate->sortDecs();
		}
	}
	if(!empty($_POST['filtering'])){
		if ($_POST['filtering'] === 'setRateFilter') {
			if ($_POST['filter_set']) {
				if (!empty($_POST['filter_read'])) $filter['readFilter'] = $_POST['filter_read'];
				$filter['rateFilter'] = $_POST['filter_set'];
				$rows = $rate->filterComment($filter);
			}else{
				if (!empty($_POST['filter_read'])) $filter['readFilter'] = $_POST['filter_read'];
				$rows = $rate->filterComment($filter);
			}
		}
		if ($_POST['filtering'] === 'setReadFilter' || $_POST['filtering'] === 'setUnreadFilter') {
			if (!empty($_POST['filter_set'])) $filter['rateFilter'] = $_POST['filter_set'];
			$filter['readFilter'] = $_POST['filtering'];
			$rows = $rate->filterComment($filter);
		}
	}
	if(!empty($_POST['delete_filter'])){
		header('Location: '.$_SERVER['PHP_SELF']);
	    die();
	}
	
}else{
	header('Location: login.php');
}
?>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Мысли вслух или ...</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/admin.css">
</head>
<body>
	<!--<div id="wrapper">-->
	<div class="wrapper">
		<!-- верхний колонтитул -->
		<header class="row header">
			<div class="logo">Мысли вслух или ...</div>
		</header>
		<section id="content" class="content">
			<h4>Панель администратора</h4>
			<a class="button orange" href="logout.php">выйти</a>
			<div>
				<form enctype="multipart/form-data" action="admin.php" method="post">
					<div>
						<p class="title">
							Сбросить все фильтры
						</p>
						<button type="submit" name="delete_filter" value="delete" class="btn24">
							<img src="img/Filter-Delete-24.png" alt="Сбросить фильтры">
						</button>
					</div>
					<table>
						<tr>
							<td></td>
							<td>комментарий</td>
							<td>
								<button type="submit" name="sorting" value="asc" class="btn16">
									<img src="img/Sort-Asc-16.png" alt="Непрочитано">
								</button>Оценка
								<button type="submit" name="sorting" value="desc" class="btn16">
									<img src="img/Sort-Desc-16.png" alt="Непрочитано">
								</button>
							</td>
							<td>Время</td>
							<td>Прочитано</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>
								<input type="text" name="filter_set" value="<?=$filter['rateFilter'];?>" class="filter-text">
								<button type="submit" name="filtering" value="setRateFilter" class="btn16">
									<img src="img/Filter-Settings-16.png" alt="Фильтровать">
								</button>
							</td>
							<td></td>
							<td>
								<input type="hidden" name="filter_read" value="<?=$filter['readFilter'];?>">
								<button type="submit" name="filtering" value="setReadFilter" class="btn24">
									<img src="img/Read-24.png" alt="Прочитано">
								</button>
								<button type="submit" name="filtering" value="setUnreadFilter" class="btn24">
									<img src="img/Unread-24.png" alt="Непрочитано">
								</button>
							</td>
							<td></td>
						</tr>
			<?php
				foreach ($rows as $key => $value) {
			?>
						<tr>
							<td><?=$value['id'];?></td>
							<td><?=$value['comment'];?></td>
							<td><?=$value['rate'];?></td>
							<td><?=date("d.m.Y, G:i:s", $value['rating_date']);?></td>
							<td>
			<?php
					if ($value['readed'] !== '0') {
			?>
								<button class="btn24" type="submit" name="unreaded" value="<?=$value['id'];?>">
									<img src="img/Close-24.png" alt="Непрочитано">
								</button>
			<?
					}else{
			?>
								<button class="btn24" type="submit" name="readed" value="<?=$value['id'];?>">
									<img src="img/Submit-24.png" alt="Прочитано">
								</button>
			<?
					}
			?>					
							</td>
							<td>
								<button class="btn24" type="submit" name="delete" value="<?=$value['id'];?>">
									<img src="img/Data-Delete-24.png" alt="Удалить">
								</button>
							</td>
						</tr>
			<?
				}		
			?>
					</table>
				</form>
			</div>
		</section>
		<!-- нижний колонтитул -->
		<footer class="footer">
			<div class="copyright">"Гениальные мысли" @ 2016</div>
		</footer>
	</div>
</body>
</html>