<h1>Clients</h1>

<form name="recherche_client" class='form-admin' enctype="multipart/form-data" action="" method="post">
	<p>Ce champs de recherche vous permettra de retrouver rapidement et facilement n'importe lequel de vos clients &agrave; l'aide de son nom, son pr&eacute;nom ou son adresse mail.</p>
	<input type="text" value="Chercher un client" name="searchClient" onclick='this.value = ""' size="60" />
	<input type="submit" value="Chercher" style='margin-left:10px' />
</form>

<?php

if(isset($_POST['searchClient'])){
	
	$sKeyword = $_POST['searchClient'];
	$aClient = dataManager::Read('ts_session',array(
		array('sName','LIKE','%'.$sKeyword.'%'),
		array('sFirstname','LIKE','%'.$sKeyword.'%','OR'),
		array('sMail','LIKE','%'.$sKeyword.'%','OR'),
		array('sStreet','LIKE','%'.$sKeyword.'%','OR'),
		array('sCity','LIKE','%'.$sKeyword.'%','OR')
		),
		array('sName','ASC'));
	foreach ($aClient as $oClient):
		echo "<p><a href='index.php?page_admin=detail_client&id=".$oClient->ID."'>".$oClient->sName." ".$oClient->sFirstname."</a></p>";
	endforeach;
}

?>