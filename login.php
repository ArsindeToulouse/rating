<?php
session_start();

require_once "start.php";
use Core\User\User;

if (!empty($_POST['login']) && !empty($_POST['password'])) {
	$user = new User($pdo);
	$user_id = $user->getUser($_POST['login'], $_POST['password']);

	$_SESSION['id'] = $user_id;
	$_SESSION['sess'] = $_COOKIE['PHPSESSID'];

	($user_id) ? header('Location: admin.php') : header('Location: login.php');

}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Мысли вслух или ...</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<!--<div id="wrapper">-->
	<div class="wrapper">
		<!-- верхний колонтитул -->
		<header class="row header">
			<div class="logo">Мысли вслух или ...</div>
		</header>
		<section id="content" class="content">
			<h1>Войти администратором</h1>
			<div>
				<form enctype="multipart/form-data" action="login.php" method="post" name="loginForm">
					<div>
						<label for="login">login:</label>
						<input type="text" pattern="[a-zA-Z0-9]*" name="login">
					</div>
					<div>
						<label for="password">password:</label>
						<input type="password" pattern="[a-zA-Z0-9@#%^]*" name="password">
					</div>
					<div>
						<button>войти</button>
					</div>
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