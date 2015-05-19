<?php
header("LOCATION: ../index.php");
include('connect.php');
dataManager::Write('delete', 'ts_mailing', array(array('sMail','=',$_GET['id'])));
?>