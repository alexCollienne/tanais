<p class="button"><a href='index.php?page_admin=commande&id=<?php echo $_GET['id'] ?>'>Retour &agrave; la commande</a></p>
<?php $aMail = dataManager::Read('ts_commande_mail',array(array('iOrder','=',$_GET['id'])))?>
<?php foreach ($aMail as $oMail): ?>
	<p>Date: <?php echo formatDate($oMail->sDate); ?></p>
	<p>Sujet: <?php echo $oMail->sSubject ?></p>
	<p>Contenu: <?php echo $oMail->sContent; ?></p>
	<p style='border-bottom:2px dotted black; margin:10px 0; width:100%;'></p>
<?php endforeach; ?>