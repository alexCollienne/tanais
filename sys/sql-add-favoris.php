<?php
include('connect.php');
include('session.php');
include('../include/function.php');
include('../include/class.php');

return dataManager::Write('insert','ts_favoris',array(array('iClient',$_SESSION['id']),array('iArticle',$_GET['id'])));

?>