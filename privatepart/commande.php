<?php
//DELETE
if(isset($_GET['delete'])):
	dataManager::Write('delete','ts_commande',null,array(array('ID','=',$_GET['iorder'])));
	dataManager::Write('delete','ts_commande_article',null,array(array('iOrder','=',$_GET['iorder'])));
endif;

//BON D'ACHAT
if(isset($_GET['send-article'])):
	$aOrderArticles = dataManager::Read('ts_commande_article',array(array('iOrder','=',$_GET['id']),array('iArticle','=',$_GET['iarticle'])));
	$oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$_GET['iarticle'])));
	
	$iTotal = $oArticle->iPrice * $_GET['quantity'];
	$iWeight = $oArticle->iWeight * $_GET['quantity'];
	
	foreach ($aOrderArticles as $oOrderArticle):
		if($oOrderArticle->sStatus == $_GET['send-article']):
			$iQuantity = $oOrderArticle->iQuanty + $_GET['quantity'];
		endif;
		$bPromo = ($oOrderArticle->bPromo == 0) ? 0 : 1;
	endforeach;
	
	if(!empty($sOrderArticle)):
		dataManager::Write('update','ts_commande_article',array(
			array('iOrder',$oOrder->ID),
			array('iArticle',$oArticle->ID),
			array('iQuantity',$iQuantity),
			array('iTotal',$iTotal),
			array('iWeight',$iWeight),
			array('sStatus',$_GET['send-article']),
			array('bPromo',$bPromo)
		), array(
			array('sStatus','=',$_GET['send-article']),
			array('')
		));
	else:
		dataManager::Write('insert','ts_commande_article',array(
			array('iOrder',$oOrder->ID),
			array('iArticle',$oArticle->ID),
			array('iQuantity',$_GET['quantity']),
			array('iTotal',$iTotal),
			array('iWeight',$iWeight),
			array('sStatus',$_GET['send-article']),
			array('bPromo',$bPromo)
		));
	endif;
	
	if($_GET['quantity'] == $oOrderArticle->iQuantity):
		//dataManager::Write('delete','ts_commande_article',null,array(array('ID','=',$oOrderArticle->ID)));
		echo 'true;';
	else:
		$iNewQuantity = $oOrderArticle->iQuantity - $_GET['quantity'];
		/*dataManager::Write('update','ts_commande_article',array(
			array('iQuantity',$iNewQuantity)),
			array(array('ID','=',$oOrderArticle->ID)
		));*/
		echo 'false;'.$iNewQuantity;
	endif;

		/*
	if($_GET['send-article'] == 'reliquat'):
		$oOrder = dataManager::ReadOne('ts_commande',array(array('ID','=',$_GET['id'])));
		$oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$oOrder->iClient)));	
		$oBon = dataManager::ReadOne('ts_reliquat',array(array('iClient','=',$oOrder->iClient)));
		
		$iNewVoucher = $oBon->iValue + $oArticle->iPrice * $_GET['quantity'];
		$iOrderVoucher = $oOrder->iVoucherCreated + $iNewVoucher;
		
		dataManager::Write('update','ts_reliquat',array(array('iValue',$iNewVoucher)),array(array('ID','=',$oBon->ID)));
		
		dataManager::Write('insert','ts_bon_achat_historique',array(
			array('iClient',$oClient->ID),
			array('iValue',$iNewVoucher),
			array('iOrder',$oOrder->ID),
			array('sType','commande'),
			array('sDate',date('Y-m-d'))
		));
		
		//Envoi du mail au client pour le prévenir du bon d'achat
		$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
		$entete .='MIME-Version: 1.0'."\n";
		$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
		$entete .='Content-Transfer-Encoding: 8bit'."\n"; 
		
		$sDear = ($oClient->genre == 'Mr.') ? 'cher mr.' : 'ch&egrave;re md.';
		
		$dest = $oClient->mail;	
		$sujet = 'Ajout d\'un bon d\'achat';
		 
		$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
		$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
		$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; min-height:250px;'>";
		$content .= "<p>Bonjour ".$sDear." ".$oClient->nom." ".$oClient->prenom.",</p>";
		$content .= "<p>Malheureusement l'article (<a href='http://www.tanais.be/catalogue.php?id=".$oArticle->ID."'>".$oArticle->sName."</a>) que vous avez demandé dans votre commande n° ".$oOrder->sOrderCode." est toujours hors stock chez le fournisseur.</p>";
		$content .= "<p>Aussi nous avons transformons votre reliquat en bon d'achat d'une valeur de ".$iNewVoucher."&euro; (à activer dans votre page perso) afin que vous puissiez remplacer par un autre article.</p>";
		$content .= "<p>Si votre nouvelle commande est égale ou inférieur au bon d'achat, vos frais de port seront gratuits.</p>";
		$content .= "<p>Nous vous souhaitons une bonne journée créative.</p>";
		$content .= "<p>Cordialement,</p>";
		$content .= "<p>Faniel Marie-France<br><a href='mailto:serviceclient@tanais.be'>L'équipe Tana&iuml;s</a></p>";
		$content .= "</div>";
		
		$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:20px; padding-top:5px; padding-bottom:5px; margin-top:50px;'><a href='http://www.tanais.be/index.php?page=erase' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
		
		mail($dest, $sujet, $content, $entete);
	endif;
	*/
endif;

if(isset($_GET['send'])):

	dataManager::Write('update','ts_commande',array(
			array('sStatus',$_GET['send']),
			array('sDateSend',date('Y-m-d'))
		),array(
			array('ID','=',$_GET['id'])
		));

	//SEND EMAIL TO CLIENT WITH RESUME OF THE ORDER
	if($_GET['send'] == 'envoye'):
		$aArticle = dataManager::Read('ts_commande_article',array(array('iOrder','=',$_GET['id']),array('sStatus','=','en_cours')),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','iArticle')))));
		foreach ($aArticle as $oArticle):
			$iTotal = $oArticle->iPrice * $_GET['quantity'];
			$iWeight = $oArticle->iWeight * $_GET['quantity'];
			$bPromo = ($oArticle->bPromo = 0) ? 0 : 1;
			
			dataManager::Write('insert','ts_commande_article',array(
				array('iOrder',$oArticle->iOrder),
				array('iArticle',$oArticle->ID),
				array('iQuantity',$_GET['quantity']),
				array('iTotal',$iTotal),
				array('iWeight',$iWeight),
				array('sStatus',$_GET['send-article']),
				array('bPromo',$bPromo)
			));
			
			if($_GET['quantity'] == $oArticle->iQuantity):
				dataManager::Write('delete','ts_commande_Article',null,array(array('iArticle','=',$oArticle->ID),array('iOrder','=',$oArticle->iOrder)));
			else:
				$iNewQuantity = $oArticle->iQuantity - $_GET['quantity'];
				dataManager::Write('update','ts_commande_article',array(array('iQuantity',$iNewQuantity)),array(array('iArticle','=',$oArticle->ID),array('iOrder','=',$oArticle->iOrder)));
			endif;
		endforeach;
	endif;

endif;

if(isset($_GET['option'])):
	if($_GET['option'] == 'recall'):
		
		$oOrder = dataManager::ReadOne('ts_commande',array(array('ID',$_GET['id'])));
		$oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$oOrder->iClient)));
			
		$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
		$entete .='MIME-Version: 1.0'."\n";
		$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
		$entete .='Content-Transfer-Encoding: 8bit'."\n"; 

		$sDear = ($oClient->genre == "Mr.") ? 'Cher monsieur,' : 'Ch&egrave;re madame';

		$dest = $oClient->mail;	
		
		$sujet = "Rappel : commande n° ".$oCommande->date;
		
		$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
		$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
		$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; min-height:250px;'>";
		$content .= "<p>Bonjour ".$sDear." ".$oClient->nom.",</p>";
		$content .= "<p>Vous avez passé une commande chez nous et apparemment nous n'avons pas encore reçu votre paiement.</p>";
		$content .= "<p>Nous vous redonnons nos coordonnées bancaires au cas ou vous ne les auriez pas eues.</p>";
		$content .= "<p>Votre commande sera envoyée le jour de la réception de votre paiement.</p>";
		
		$content .= "<p style='text-decoration:underline'>- Virement &agrave; partir d'un compte bancaire Belge:</p>";
		$content .= "<p>750-6018036-23</p>";
		$content .= "<p>Faniel Marie-France</p>";
		$content .= "<p>Rue de Velaine, 95</p>";
		$content .= "<p>5300 Andenne</p>";
		$content .= "<p>Belgique</p>";
		$content .= "<p>Communication: <span style='color:red'>votre n&deg; de commande</span></p><br />";
		
		$content .= "<p style='text-decoration:underline'>- Virement &agrave; partir d'un compte bancaire non Belge:</p>";
		$content .= "<p>Compte IBAN: BE92750601803623</p>";
		$content .= "<p>SWIFT/BIC: AXABBE22</p>";
		$content .= "<p>Banque AXA</p>";
		$content .= "<p>Rue de la station, 41</p>";
		$content .= "<p>5300 Andenne</p>";
		$content .= "<p>Belgique</p>";
		$content .= "<p>Communication: <span style='color:red'>votre n&deg; de commande</span></p><br>";

		
		$content .= "<p>Vous pouvez aussi payer par carte en nous téléphonant au 00 32 (0)85/82.82.38</p>";
		$content .= "<p>Nous restons à votre entière disposition pour toutes informations complémentaires que vous pourriez désirer.</p>";
		$content .= "<p>Pour nous contacter, voir heures d’ouverture sur le site.</p>";
		$content .= "<p>Nous vous souhaitons une agréable journée.</p>";
		$content .= "<p>Cordialement,</p>";
		$content .= "<p>Service client&egrave;le</p>";
		$content .= "<p><a href='mailto:serviceclient@tanais.be'>serviceclient@tanais.be</a></p>";
		$content .= "</div>";
		
		$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:20px; padding-top:5px; padding-bottom:5px; margin-top:50px;'><a href='http://www.tanais.be/index.php?page=erase' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
		
		
		$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:20px; padding-top:5px; padding-bottom:5px; margin-top:50px;'><a href='http://www.tanais.be/index.php?page=erase' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
		
		if(mail($dest, $sujet, $content, $entete)):
			$sMessage = 'Le mail de rappel a bien &eacute;t&eacute; envoyé';
			$bClass = true;
		else:
			$sMessage = 'Le mail de rappel n\'a pas &eacute;t&eacute; envoyé';
			$bClass = false;
		endif;
				
	endif;
	
	if($_GET['option'] == 'mail'):
	
		$oOrder = dataManager::ReadOne('ts_commande',array(array('ID','=',$_GET['id'])));
		$oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$oOrder->iClient)));
		$sDear = ($oClient->sGender == "Mr.") ? 'Cher' : 'Chère';
	
		$dest = $oClient->sMail;
		$sujet = $_POST['sujet'];
		$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
		$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
		$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><p style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#999; min-height:250px;'>".$sDear." ".$oClient->sFirstname." ".$oClient->sName.",</p>";
		$content .= "<p>".stripslashes($_POST['mail'])."</p>";
		$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
	    $entete .= 'MIME-Version: 1.0'."\n";
	    $entete .= 'Content-Type:text/html;charset=iso-8859-1'."\n";
	    $entete .= 'Content-Transfer-Encoding: 8bit'."\n";
	    
		$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; padding-top:5px; padding-bottom:5px; margin-top:50px;'><a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";

		if(mail($dest, $sujet, $content, $entete)):
			dataManager::Write('update','ts_commande',array(array('bMail',1)),array(array('ID','=',$_GET['id'])));	
			dataManager::Write('insert','ts_commande_mail',array(
				array('iOrder',$_GET['id']),
				array('iClient',$oClient->ID),
				array('sSubject',$_POST['sujet']),
				array('sContent',$_POST['mail']),
				array('sDate',date('Y-m-d'))
			));
		endif;
		
	endif;
	
	if($_GET['option'] == 'comment'):
		dataManager::Write('update','ts_commande',array(array('sComment',$_POST['comment'])),array(array('ID','=',$_GET['id'])));	
	endif;
endif;

if(isset($_POST['submitFpReel'])):
	$bPos = strpos($_POST['fpReel'], ',');
	$fpReel = ($bPos !== false) ? str_replace(array(','), '.', $_POST['fpReel']) : $_POST['fpReel'];
	$bUp = dataManager::Write('update','ts_commande',array(array('iRealDelivreryPrice',$fpReel)),array(array('ID','=',$_GET['id'])));

	if($bUp):
		$sMessage = 'Les frais de ports r&eacute;els ont bien &eacute;t&eacute; bien mis &agrave; jour';
		$bClass = true;
	else:
		$sMessage = 'Les frais de ports r&eacute;els n\'ont pas &eacute;t&eacute; bien mis &agrave; jour : '.mysql_error();
		$bClass = false;
	endif;
endif;

$sStatus = (isset($_GET['statut'])) ? $_GET['statut'] : 'en_cours';
?>

<script>

$(document).ready(function() {
	$('.change-quantity').change(function(){
		iQuantity = $(this).val();
		$(this).parent('td').parent('tr').find('.action a').each(function(){
			aHref = $(this).attr('href').split('&');
			sHref = 'index.php?page_admin=commande&' + aHref[1] + '&' + 'quantity=' + iQuantity + '&' + aHref[3] + '&' + aHref[4];
			$(this).attr('href',sHref);
		});
	});
});
</script>

<h1>Commandes</h1>

<form name="Choix" class='form-admin'>
<select name="Liste" onChange="Lien()">
	<option> - Choisissez un mois - </option>
	<?php
	
	//Création du menu
	$annee = date('Y');
	$mois= date('m');
	
	for($i=$mois; $i>=02 || $annee > 2009; $i--){
	
		if(($annee !== date('Y') || $i !== $mois) && $i < 10){
			$i = "0".$i;	
		}
		
	    $iCommande = dataManager::Count('ts_commande', 'ID', array(array('sDate','LIKE',$annee.'-'.$i.'%')));
		
		echo '<option value="index.php?page_admin=commande&date='.$annee.'-'.$i.'">'.$i.'-'.$annee.' ('.$iCommande.')</option>';
		if($i == 01){
			$annee --;
			$i = 13;
		}
	}
	if(isset($_GET['date'])):
		$_SESSION['date'] = $_GET['date'];	
	else:
		if(!isset($_SESSION['date'])):
			$_SESSION['date'] = date('Y-m');
		endif;
	endif;
	
	?>
	</select>
</form>

<ul class='list'>
	<li><a href='index.php?page_admin=commande&statut=en_cours'>Commandes en attente</a></li>
    <li><a href='index.php?page_admin=commande&statut=incomplete'>Commandes incompl&egrave;te</a></li>
    <li><a href='index.php?page_admin=commande&statut=envoye'>Commandes Termin&eacute;e</a></li>
    <li><a href='index.php?page_admin=commande&option=reliquat'>Voir la liste de reliquats</a></li>
</ul>
<div class="clear"></div>
<?php $iTotal = dataManager::Sum('ts_commande','iTotal',array(array('sDate','LIKE',$_SESSION['date'].'%'),array('sStatus','=','envoye'),array('sDate','LIKE',$_SESSION['date'].'%','OR'),array('sStatus','=','incomplete'))); ?>
<?php $iTotalDelivreryPrice = dataManager::Sum('ts_commande','iDelivreryPrice',array(array('sDate','LIKE',$_SESSION['date'].'%'),array('sStatus','=','envoye'),array('sDate','LIKE',$_SESSION['date'].'%','OR'),array('sStatus','=','incomplete'))); ?>
<?php $iRealTotalDelivreryPrice = dataManager::Sum('ts_commande','iRealDelivreryPrice',array(array('sDate','LIKE',$_SESSION['date'].'%'),array('sStatus','=','envoye'),array('sDate','LIKE',$_SESSION['date'].'%','OR'),array('sStatus','=','incomplete'))); ?>

<ul class="admin-list">
	<li>Total des ventes du <?php echo $_SESSION['date']; ?> : <?php print_r($iTotal); ?> &euro;</li>
	<li>Total des frais de port du <?php echo $_SESSION['date']; ?> : <?php echo $iTotalDelivreryPrice; ?> &euro;</li>
	<li>Total des frais de port r&eacute;els du <?php echo $_SESSION['date']; ?> : <?php echo $iRealTotalDelivreryPrice; ?> &euro;</li>
</ul>

<?php $sClass = (isset($bClass) && $bClass) ? 'greenBox' : 'redBox'; ?>
<?php echo (isset($sMessage)) ? '<p class="'.$sClass.'">'.$sMessage.'</p>' : ''; ?>

<?php
if(isset($_GET['id'])):
	$flag = False;
	$oCommande = dataManager::ReadOne('ts_commande',array(array('ID','=',$_GET['id'])));
	$oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$oCommande->iClient)));
?>
	<h2>Commande n&deg; <?php echo $oCommande->sOrderCode ?></h2>
	<p class="redBox">Statut : <?php echo $oCommande->sStatus ?>
		<?php if($oCommande->sStatus == 'envoye' || $oCommande->sStatus == 'incomplete'): ?>
			<?php echo ' - '.formatDate($oCommande->sDateSend) ?>
		<?php endif; ?>
	</p>
	<p class="blueBox">Commandée le <?php echo formatDate($oCommande->sDate); ?></p>
	
	<h3>Détails de la commande</h3>
	<table border='0' cellpadding='0' cellspacing='0' id='tableCommande'>
		<tbody>
			<thead>
				<tr>
					<th class="headImage"></th>
					<th>Nom</th>
					<th width="50">Nombre</th>
					<th width="120">prix</th>
					<th width="80">Total</th>
					<th width="80">Stock</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td bgcolor="#FFB600" style='color:#fff' colspan="5">Article en attente</td>
				</tr>
				<?php $aOrderArticles = dataManager::Read('ts_commande_article',array(array('iOrder','=',$_GET['id']),array('sStatus','=','en_cours'))); ?>
				<?php foreach($aOrderArticles as $oOrderArticle): ?>
					<?php $oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$oOrderArticle->iArticle))); ?>
					<?php $class = ($flag) ? 'even' : 'odd'; ?>
					<tr class='<?php echo $class; ?>'>
						<td align="center" class="headImage">
							<a href="../img/zoom/<?php echo $oArticle->sImage; ?>" class="pirobox" title="<?php echo $oArticle->sName_FR; ?>">
								<img src="../img/catalogue/<?php echo $oArticle->sImage; ?>" width='30' />
							</a>
						</td>
						<td><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID; ?>'><?php echo $oArticle->sName_FR; ?></a></td>
						<td align="right"><?php echo $oOrderArticle->iQuantity; ?></td>
						<td align="right"><?php echo $oArticle->iPrice; ?> &euro;/pc</td>
						<td align="right"><?php echo $oOrderArticle->iTotal; ?> &euro;</td>
						<td align="right"><?php echo $oOrderArticle->iStock; ?> pi&egrave;ces</td>
						<td class="action">
							<select name='<?php echo $oArticle->ID ?>' class="change-quantity">
								<?php for($i = 1; $i <= $oOrderArticle->iQuantity; $i++): ?>
									<option value="<?php echo $i ?>"<?php echo ($i ==  $oOrderArticle->iQuantity) ? ' selected="selected"' : ''; ?>><?php echo $i ?></option>
								<?php endfor; ?>
							</select>
							<a href="index.php?page_admin=commande&send-article=reliquat&quantity=<?php echo $oOrderArticle->iQuantity ?>&id=<?php echo $_GET['id'] ?>&iarticle=<?php echo $oArticle->ID ?>">
								<img src="img/reliquat.gif" width='25' style='vertical-align:middle;' />
							</a>		
							<a href="index.php?page_admin=commande&send-article=envoye&quantity=<?php echo $oOrderArticle->iQuantity ?>&id=<?php echo $_GET['id'] ?>&iarticle=<?php echo $oArticle->ID ?>">
								<img src="img/envoi.gif" width='25' style='vertical-align:middle;' />
							</a>
						</td>
					</tr>
					<?php $flag = !$flag; ?>
				<?php endforeach; ?>
		
				<tr>
					<td></td>
					<td bgcolor="#FFB600" style='color:#fff' colspan="5">Article envoy&eacute;</td>
				</tr>
				<?php $aArticle = dataManager::Read('ts_commande_article',array(array('iOrder','=',$_GET['id']),array('sStatus','=','envoye')),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','iArticle'))))); ?>
				<?php foreach($aArticle as $oArticle): ?>
					<?php $class = ($flag) ? 'even' : 'odd'; ?>
					<tr class='<?php echo $class; ?>'>
						<td align="center" class="headImage">
							<a href="../img/zoom/<?php echo $oArticle->sImage; ?>" class="pirobox" title="<?php echo $oArticle->sName_FR; ?>">
								<img src="../img/catalogue/<?php echo $oArticle->sImage; ?>" width='30' />
							</a>
						</td>
						<td><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID; ?>'><?php echo $oArticle->sName_FR; ?></a></td>
						<td align="center"><?php echo $oArticle->iQuantity; ?></td>
						<td><?php echo $oArticle->iPrice; ?> &euro;/pc</td>
						<td><?php echo $oArticle->iTotal; ?> &euro;</td>
						<td><?php echo $oArticle->iStock; ?> pi&egrave;ces</td>
					<tr>
					<?php $flag = !$flag; ?>
				<?php endforeach; ?>
				
				<tr>
					<td></td>
					<td bgcolor="#FFB600" style='color:#fff' colspan="5">Bon d'achats</td>
				</tr>
				<?php $aArticle = dataManager::Read('ts_commande_article',array(array('iOrder','=',$_GET['id']),array('sStatus','=','reliquat')),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','iArticle'))))); ?>
				<?php foreach($aArticle as $oArticle): ?>
					<?php $class = ($flag) ? 'even' : 'odd'; ?>
					<tr class='<?php echo $class; ?>'>
						<td align="center" class="headImage">
							<a href="../img/zoom/<?php echo $oArticle->sImage; ?>" class="pirobox" title="<?php echo $oArticle->sName_FR; ?>">
								<img src="../img/catalogue/<?php echo $oArticle->sImage; ?>" width='30' />
							</a>
						</td>
						<td><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID; ?>'><?php echo $oArticle->sName_FR; ?></a></td>
						<td align="center"><?php echo $oArticle->iQuantity; ?></td>
						<td align="right"><?php echo $oArticle->iPrice; ?> &euro;/pc</td>
						<td><?php echo $oArticle->iTotal; ?> &euro;</td>
						<td><?php echo $oArticle->iStock; ?> pi&egrave;ces</td>
					</tr>
					<?php $flag = !$flag; ?>
				<?php endforeach; ?>
				<tr>
					<td></td>
					<td>Frais de port</td>
					<td colspan='2'></td>
					<td align='right'><?php echo $oCommande->iDelivreryPrice ?> &euro;</td>
				</tr>
				<tr>
					<td></td>
					<td colspan='5'></td>
				</tr>
				<tr>
					<td></td>
					<td>Total</td>
					<td colspan='2'></td>
					<td align='right'><?php echo $oCommande->iTotal; ?> &euro;</td>
				</tr>
			</tbody>
		</table>
	
	<p class='blueBox'>Poids total: <span style='background:yellow'><?php echo $oCommande->iWeight ?> gr.</span></p>
	<?php echo ($oCommande->iVoucherUsed != 0) ? '<p class="blueBox">Bon d\'achat d\'une valeur de <span style="background:yellow">'.$oCommande->iVoucherUsed.' &euro;</span></p>' : ''; ?>
	<?php echo ($oCommande->iVoucherCreated != 0) ? '<p class="blueBox">Total des bons d\'achats:  <span style="background:yellow">'.$oCommande->iVoucherCreated.' &euro;</span></p>' : ''; ?>
	<?php echo ($oCommande->sComment != '') ? '<p class="blueBox">Commentaire laiss&eacute; par le client: '.$oCommande->sCommentClient.'</p>' : ''; ?>
	<p class="blueBox">Mode de paiement choisit par le client:  <span style='background:yellow'><?php echo $oCommande->sPaymentMode; ?></span></p>
		
	<div id="boxFpReel" class='redBox'>
		<form method="POST">
			<label>Frais de port r&eacute;el:</label>
			<input type="text" value="<?php echo $oCommande->iRealDelivreryPrice ?>" id="fpReel" name="fpReel" />
			<input type="hidden" value="<?php echo $oCommande->ID ?>" name="idCommande" />
			<input type="submit" value="Sauvegarder" name="submitFpReel" />
		</form>
	</div>	
	
	<a href='print.php?id_commande=<?php echo $_GET['id'] ?>' target='_blank' style='display:inline;'>
		<img src='../img/site/print.jpg' title='Imprimer' />
	</a>
	
	<a href='index.php?page_admin=commande&send=envoye&id=<?php echo $_GET['id'] ?>'>
		<img src='img/envoi.gif' width='30' title='Envoyer la commande' onclick='return confirm(\"Etes-vous s&ucirc;r de vouloir envoyer cette commande?\")' />
	</a>
	
	<?php if($oCommande->sStatus != 'incomplete'): ?>
		<a href='index.php?page_admin=commande&send=incomplete&id=<?php echo $_GET['id'] ?>'>
			<img src='img/incompleteCommande.gif' width='30' title='Envoi incomplet' onclick='return confirm(\"Etes-vous s&ucirc;r de vouloir envoyer cette commande de maniÃ¨re incomplÃ¨te?\")' />
		</a>
	<?php endif; ?>

	<p class="button">
		<a href="index.php?page_admin=commande&option=recall&id=<?php echo $_GET['id'] ?>">Envoyer un rappel de paiement</a>
	</p>
	<h3>Lieu de destination de la commande:</h3>
	<p>
		<?php echo $oCommande->sDelivreryStreet.' '.$oCommande->sDelivreryNumber ?><?php echo ' '.$oCommande->sDelivreryBox ?>
		<br />
		<?php echo $oCommande->sDelivreryZip ?> <?php echo $oCommande->sDelivreryCity ?>
		<br />
		<?php echo getCountry($oCommande->sDelivreryCountry) ?>
		<?php echo (!empty($oCommande->sDelivreryPhone)) ? '<br />Tel. : '.$oCommande->sDelivreryPhone : '' ?>
		<br />
		<a href="mailto:<?php echo $oCommande->sDelivreryMail ?>"><?php echo $oCommande->sDelivreryMail ?></a>
	</p>
	<p><a href="index.php?page_admin=detail_client&id=<?php echo $oCommande->iClient?>"><strong>Fiche du client</strong></a></p>
	
	<h3>Commentaire</h3>
	<form action='index.php?page_admin=commande&option=comment&id=<?php echo $_GET['id'] ?>' class='form-admin' method='post' enctype='multipart/form-data'>
    	<textarea name='comment'><?php echo $oCommande->sComment ?></textarea>
    	<br />
    	<input type='submit' value='Commenter' />
    </form>

	<h3>Envoyer un mail au client</h3>
    <p><a href='index.php?page_admin=mail&id=<?php echo $_GET['id'] ?>'>Mail envoy&eacute;</a></p>
	<form action='index.php?page_admin=commande&option=mail&id=<?php echo $_GET['id'] ?>' class='form-admin' method='post' enctype='multipart/form-data'>
    	<input type='text' name='sujet' value='Sujet' size='62' />
    	<br />
    	<textarea name='mail' cols='60' rows='10'></textarea>
    	<br />
    	<input type='submit' value='Commenter' />
    </form>
<?php elseif(isset($_GET['option'])): ?>

	<h2>Liste des reliquats</h2>
	<ul id="listReliquat">
	<?php
		$aListArticle = array();
		$aArticle = dataManager::Read('ts_commande',array(array('ts_commande.sStatus','=','incomplete'),array('ts_commande_article.sStatus','=','en_cours')),null,null,array(
			array('inner join','ts_commande_article',array(array('ts_commande.ID','=','iOrder'))),
			array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','iArticle')))));
		foreach($aArticle as $oArticle):
			if(!in_array($oArticle->ID, $aListArticle)):
				$num = explode('-', $oArticle->sDate);
				echo '<li><a href="index.php?page_admin=catalogue&id='.$oArticle->ID.'">'.$oArticle->sName_FR.'</a> -  <a href="index.php?page_admin=commande&id='.$oArticle->iOrder.'">commande '.$oArticle->sOrderCode.'</a></li>';
				$aListArticle[] = $oArticle->ID;
			endif;
		endforeach;
	?>
	</ul>
	
<?php else: ?>
	<p>Vous &ecirc;tes sur la page des commande. Vous avez ci-dessus la liste de toutes les commandes en cours du mois en cours. Si vous souhaitez voir les commandes d'un mois pr&eacute;c&eacute;dent, vous pouvez le choisir via la liste d&eacute;roulante ci-dessus. Pour voir les commandes envoy&eacute;es ou inompl&egrave;tes du mois s&eacute;lectionn&eacute;, vous pouvez cliquer sur un des boutons ci-dessus.</p>
<?php
	$flag = True;

	if(isset($_GET['statut'])):
		$aCommande = dataManager::Read('ts_commande',array(array('sDate','LIKE',$_SESSION['date'].'%'),array('sStatus','=',$_GET['statut'])));
		$sStatus = $_GET['statut'];
	else:
		$aCommande = dataManager::Read('ts_commande',array(array('sDate','LIKE',$_SESSION['date'].'%'),array('sStatus','=','en_cours')));
		$sStatus = "en_cours";
	endif;
?>
<table cellpadding="0" border="0" cellspacing="0">
	<thead>
		<tr>
			<th>Date</th>
			<th>Num&eacute;ro de commande</th>
			<?php if($sStatus == 'envoye' || $sStatus == 'incomplete'){ echo '<th>Date d\'envoi</th>'; } ?>
			<th>Pr&eacute;nom - nom</th>
			<th>Total</th>
			<th>Mail</th>
			<th>Imprimer</th>
			<th>Commentaire</th>
			<?php echo ($sStatus != "envoye") ? '<th>Action</th>' : ''; ?>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($aCommande as $oCommande):
		
		$sClass = ($flag) ? 'even' : 'odd';
		$oClient = dataManager::ReadOne('ts_session',array(array('ID','=',$oCommande->iClient)));
		if(empty($oClient)):
			$oClient->sFirstname = 'visiteur';
			$oClient->sName = '';
			$oClient->ID = '';
		endif;
?>
		<tr class="<?php echo $sClass ?>">
			<td><?php echo formatDate($oCommande->sDate); ?></td>
			<td><a href='index.php?page_admin=commande&id=<?php echo $oCommande->ID ?>'><?php echo $oCommande->sOrderCode ?></a></td>
			<?php if($oCommande->sStatus == 'envoye' || $oCommande->sStatus == 'incomplete'): ?>
				<td><?php echo formatDate($oCommande->sDateSend) ?></td>
			<?php endif; ?>
			<td><a href='index.php?page_admin=detail_client&id=<?php echo $oClient->ID ?>'><?php echo $oClient->sFirstname.' '.$oClient->sName ?></a></td>
			<td><?php echo $oCommande->iTotal ?> &euro;</td>
			<td align='center'>
				<?php echo ($oCommande->bMail == 1) ? '<img src="img/ok.gif" width="15" />' : ''; ?>
			</td>
			<td align='center'>
				<?php echo ($oCommande->bPrint == 1) ? '<img src="img/ok.gif" width="15" />' : ''; ?>
			</td>
			<td width="170">
				<?php echo (!empty($oCommande->sComment)) ? cutString($oCommande->sComment, 50, '...') : ''; ?>
			</td>
			
			<?php if($sStatus != "envoye"): ?>
				<td bgcolor="#fff">
					<a href="print.php?id_commande=<?php echo $oCommande->ID ?>" target="_blank" title="Imprimer la commande" onclick='return confirm("Etes-vous s&ucirc;r de vouloir imprimer cette commande?")'>
						<img src='../img/site/print.jpg' title='Imprimer' style='border:none' />
					</a>
					
					<a href='sys/commande.php?page_admin=commande&delete=true&iorder=<?php echo $oCommande->ID ?>' onclick='return confirm(\"ATTENTION: Voulez-vous vraiment supprimer cette commande?\")'>
						<img src='img/eraseCommande.gif' width='30' title='Supprimer la commande' />
					</a>
					
					<?php if($sStatus == "en_cours" || $sStatus == "incomplete"): ?>
						<a href='index.php?page_admin=commande&send=envoye&id=<?php echo $oCommande->ID ?>'>
							<img src='img/envoi.gif' width='30' title='Envoyer la commande' onclick='return confirm(\"Etes-vous s&ucirc;r de vouloir envoyer cette commande?\")' />
						</a>
					<?php endif; ?>
					
					<?php if($sStatus == "en_cours"): ?>
						<a href='index.php?page_admin=commande&id=<?php echo $oCommande->ID ?>&send=incomplete'>
							<img src='img/incompleteCommande.gif' width='30' title='Envoi incomplet' onclick='return confirm(\"Etes-vous s&ucirc;r de vouloir envoyer cette commande de maniÃ¨re incomplÃ¨te?\")' />
						</a>
					<?php endif; ?>
				</td>
			<?php endif; ?>
			
		</tr>
		<?php $flag = !$flag; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>