<?php
require_once "start.php";

session_destroy();
setcookie('PHPSESSID', "", time() - 3600, "/");

header('Location: login.php');