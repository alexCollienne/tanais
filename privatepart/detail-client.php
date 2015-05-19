<?php

if(isset($_POST['sendBonAchat'])):
	$oBon = dataManager::ReadOne('ts_reliquat',array(array('iClient','=',$_POST['idClient'])));
	$iNewValue = $oBon->iValue +  $_POST['valueBonAchat'];
	$bUp = dataManager::Write('update','ts_reliquat',array(array('iValue',$iNewValue)),array(array('iClient','=',$_POST['idClient'])));
	$bAdd = dataManager::Write('insert', 'ts_bon_achat_historique', array(
		array('iClient',$_POST['idClient']),
		array('iValue',$_POST['valueBonAchat']),
		array('sType','admin'),
		array('sDate',date('Y-m-d G:i:s'))));

	if($bAdd && $bUp):
		$sMessage = '<p class="greenBox">Le bon d\'achat a bien été mis à jour</p>';
	else:
		$sMessage = '<p class="redBox">Le bon d\'achat n\'a pas été mis à jour</p>';
	endif;
endif;
?>

<?php $oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$_GET['id']))); ?>

<h1><?php echo $oClient->sName ?> <?php echo $oClient->sFirstname ?></h1>
<?php echo (isset($sMessage)) ? $sMessage : ''; ?>

<p>Vous trouverez ci-dessous toutes les d&eacute;tails relatifs au clients choisis: ses coordon&eacute;es, la valeur de son bon d'achat et son historique de commandes.</p>

<h2>Donnée du client</h2>
<table>
	<tr class="odd">
		<td>Nom et pr&eacute;nom</td>
		<td><?php echo $oClient->sName ?> <?php echo $oClient->sFirstname ?></td>
	</tr>
	<tr class="even">
		<td valign='top'>Adresse</td>
		<td><?php echo $oClient->sStreet.' '.$oClient->sNumber." ".$oClient->sBox." ".$oClient->sZip." ".$oClient->sCity." ".getCountry($oClient->sCountry) ?></td>
	</tr>
	<tr class="odd">
		<td>Adresse mail</td>
		<td><a href='mailto:<?php echo $oClient->sMail ?>'><?php echo $oClient->sMail ?></a></td>
	</tr>
	<tr class="even">
		<td>Num&eacute;ro de t&eacute;l&eacute;phone</td>
		<td><?php echo $oClient->sPhone ?></td>
	</tr>
</table>
	
<?php $oBon = dataManager::ReadOne('ts_reliquat',array(array('iClient','=',$_GET['id']))); ?>
<?php if(!empty($oBon)): ?>
	<h2>Bon d'achat</h2>
	<h3>Editer le bon d'achat</h3>
	<p class="blueBox">Bon d'achat actuel : <?php echo number_format($oBon->iValue, 2, ",", "") ?> &euro;</p>
	<div class="blueBox">	
		<p>Veuillez encoder la valeur que vous voulez ajouter au bon d'achat</p>
		<br />
		<form method="post">
			<input type="text" name="valueBonAchat" />
			<input type="hidden" value="<?php echo $_GET['id'] ?>" name="idClient" />
			<input type="submit" value="Ajouter au bon d'achat" name="sendBonAchat" />
		</form>
	</div>
	
	<h3>Historique du bon d'achat</h3>
	<table class="tableReliquat">
		<thead>
			<tr>
				<td>Valeur</td>
				<td>Type</td>
				<td>Commande</td>
				<td>Date</td>
			</tr>
		</thead>
		<tbody>
	<?php
		$bClassTr = true;
		$aBonHisto = dataManager::Read('ts_bon_achat_historique',array(array('iClient','=',$_GET['id'])),array('sDate','DESC'));
		foreach($aBonHisto as $oHisto):
			$sClassTr = ($bClassTr) ? 'odd' : 'even';
			$bClassTr = !$bClassTr;
	?>	
			<tr class="<?php echo $sClassTr; ?>">
				<td><?php echo $oHisto->iValue; ?> &euro;</td>
				<td><?php echo $oHisto->sType; ?></td>
				<td><?php echo (!empty($oHisto->iOrder)) ? '<a href="index.php?page_admin=commande&id='.$oHisto->iOrder.'">Voir la commande</a>' : ''; ?></td>
				<td><?php echo $oHisto->sDate; ?></td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
	
<h2>Liste des reliquats du client</h2>
<table cellspacing="1" cellpadding="0" border="0" id="tableCommande" class="tableReliquat">
	<thead>
		<tr>
			<th class="headImage"></th>
			<th>Article en reliquat</th>
			<th>Commande contenant le reliquat</th>
		</tr>
	</thead>
	<tbody>
	<?php	
	$bClass = true;
	$aArticle = dataManager::Read('ts_commande',array(array('iClient','=',$_GET['id']),array('ts_commande_article.sStatus','=','en_cours')),null,null,array(array('inner join','ts_commande_article',array(array('iOrder','=','ts_commande.ID'))),array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','iArticle')))));
	foreach($aArticle as $oArticle):
		$sClass = ($bClass) ? 'odd' : 'even';
		$bClass = !$bClass;
	?>
		<tr class="<?php echo $sClass ?>">
			<td class="headImage">
				<a title="<?php echo $oArticle->sName_FR ?>" class="pirobox" href="../img/zoom/<?php echo $oArticle->sImage ?>">
					<img width="30" src="../img/catalogue/<?php echo $oArticle->sImage ?>">
				</a>
			</td>
			<td><a href="index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>"><?php echo $oArticle->sName_FR ?></a></td>
			<td><a href="index.php?page_admin=commande&id=<?php echo $oArticle->ID ?>">commande n° <?php echo $oArticle->sOrderCode ?></a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php $aOrder = dataManager::Read('ts_commande',array(array('iClient','=',$_GET['id'])),array('sDate','DESC')); ?>
<h2>Commandes pass&eacute;es</h2>
<?php if(!empty($aOrder)): ?>
	<?php foreach ($aOrder as $oOrder): ?>
		<p style='width:490px; padding:5px; margin-top:5px; padding-top:3px;'>
			<a href='index.php?page_admin=commande&id=<?php echo $oOrder->ID ?>' style='text-align:center;'>Commande num&eacute;ro <?php $oOrder->sCodeOrder ?> - Datant du <?php echo formatDate($oOrder->sDate); ?></a>
		</p>
	<?php endforeach; ?>
<?php else: ?>	
	<p>Ce client n'a pas encore pass&eacute; de commande dans notre magasin en ligne.</p>
<?php endif; ?>