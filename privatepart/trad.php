<?php

	include('../sys/connect.php');
	include('../include/function.php');
	include('../include/class.php');

	return dataManager::Write('update','ts_catalogue',array(array('sName_EN',$_POST['name'])),array(array('ID','=',$_POST['id'])));
?>