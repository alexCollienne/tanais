<?php

session_start();
header('location:'.$_SESSION['url']);

setcookie('tanais[lng]', $_GET['lng'], (time() + 50000), '/');
$_SESSION['lng'] = $_GET['lng'];

?>