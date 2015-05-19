<?php

header('LOCATION: ../page-perso.php?option=creation');

include('connect.php');
include('session.php');

$aCreation = dataManager::Read('ts_creation',array(array('ID','=',$_GET['id'])));
$oCreation = $aCreation[0];
$bDelete = dataManager::Write('delete','ts_creation',null,array(array('ID','=',$_GET['id'])));
unlink("../img/creation-client/".$_SESSION['id']."/".$oCreation->sImage);

?>