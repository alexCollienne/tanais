<?php 
include('sys/session.php');

if(empty($_SESSION['tmpCommande'])):
	header('Location:index.php');
else:
	include('include/header.php'); 

	$sContent = '';
	$iTotalBasket = 0;
	$iWeight = 25;
	$iWeightTotal = 0;
	$iDelivreryPrice = 0;
	$iBon = 0;
	$aOrder = dataManager::Read('ts_commande', null, array('ID','DESC'));
	$iOrder = $aOrder[0]->ID + 1;
	foreach($_SESSION['tmpCommandeIdArticle'] as $iKey=>$iArticle){
		$aPromo = dataManager::Read('ts_promo',array(array('iArticle','=',$iArticle)));
		$bPromo = (!empty($aPromo)) ? 1 : 0;

		$iTotal = $_SESSION['tmpCommandePrixTotal'][$iKey];
		$iWeight = $_SESSION['tmpCommandePoidsTotal'][$iKey];
		
		$iTotalBasket += $iTotal;
		$iWeightTotal += $_SESSION['tmpCommandePoidsTotal'][$iKey];
		$iQuantity = $_SESSION['tmpCommandeQuantite'][$iKey];
		$sContentArticle = '';
		
		dataManager::Write('insert','ts_commande_article',array(
			array('iOrder',$iOrder),
			array('iArticle',$iArticle),
			array('iQuantity',$iQuantity),
			array('iTotal',$iTotal),
			array('iWeight',$iWeight),
			array('bPromo',$bPromo)
		));
		
		$oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$iArticle)));
		$sTotal = ($bPromo == 1) ? '<span style="color:red">'.$iTotal.'</span>' : $iTotal;
		
		//Tableau d'affichage du résumé des commandes.
		$sContentArticle .= "<tr>";
		$sContentArticle .= "<td style='paddin:2px;'>".$oArticle->sNamei18n."</td>";
		$sContentArticle .= "<td style='paddin:2px;'>".$iQuantity." pc(s)</td>";
		$sContentArticle .= "<td style='paddin:2px;'>".str_replace('.', ',', $oArticle->iPrice)." &euro;/pc</td>";
		$sContentArticle .= "<td style='paddin:2px;'>".str_replace('.', ',', $sTotal)." &euro;</td>";
		$sContentArticle .= "</tr>";

	}
	
	//CALCUL POUR LES FRAIS DE PORT. SI LE TOTAL = 0, PAS DE FRAIS DE PORT
	if($iTotalBasket != 0):
		$aDelivreryContry = dataManager::Read('ts_delivrery_cost',null,null,null, null,array('distinct','sCountry'));
		if(in_array($_SESSION['delivreryCountry'],$aDelivreryContry)):
			$oDelivrery = dataManager::ReadOne('ts_delivrery_cost',array(array('sCountry','=',$_SESSION['delivreryCountry']),array('iWeightMin','<=',$iWeightTotal),array('iWeightMax','>',$iWeightTotal)));
		else:
			$oDelivrery = dataManager::ReadOne('ts_delivrery_cost',array(array('sCountry','=','ALL'),array('iWeightMin','<=',$iWeightTotal),array('iWeightMax','>',$iWeightTotal)));
		endif;
		$iDelivreryPrice = ($iTotalBasket >= 35 && $_SESSION['delivreryCountry'] == 'BE') ? 0 : $oDelivrery->iPrice;
		$iDelivreryPrice = ($iTotalBasket >= 50 && $_SESSION['delivreryCountry'] == 'ALL' && $iDelivreryPrice != 0) ? 0 : $oDelivrery->iPrice;
		
		$iTotalBasket += $iDelivreryPrice;
	endif;

	//CALCUL POUR LE BON D'ACHAT
	if($_SESSION['bon'] != ''){
		$oBon = dataManager::ReadOne('ts_reliquat',array(array('iClient','=',$_SESSION['id'])));
		if($iTotalBasket > $oBon->iValue){
			$iBon = $oBon->iValue;
			$iRestValue = 0;
			$iTotalBasket = $iTotalBasket - $aBon->iValue;
		}else{
			$iBon = $oBon->iValue - $iTotalBasket;
			$iRestValue = $iBon;
			$iTotalBasket = 0;
		}
		dataManager::Write('update','ts_reliquat',array(
			array('iValue',$iRestValue)
		),array(
			array('iClient','=',$_SESSION['id'])
		));
	}
	
	if(isset($_POST['paiement'])):
		switch($_POST['paiement']):
			
			case 'ogone':
				$mode_de_paiement = 'ogone';
				break;
				
			case 'paypal':
				$mode_de_paiement = 'paypal';
				break;
				
			case 'cb':
				$mode_de_paiement = 'Carte bleu';
				break;
				
			case 'virement':
				$mode_de_paiement = 'virement';
				break;
				
			case 'cheque':
				$mode_de_paiement = 'cheque';
				break;
			
			default:
				$mode_de_paiement = 'Bon d\'achat';
				break;
		endswitch;
	else:
		$mode_de_paiement = 'Bon d\'achat';
	endif;
		
	//CALCUL ORDER CODE
	$sOrderCode = 'TA'.$iOrder.'D'.date('dmY');
	
	$bWriteOrder = dataManager::Write('insert','ts_commande',array(
		array('sOrderCode',$sOrderCode),
		array('iClient',$_SESSION['id']),
		array('iTotal',$iTotalBasket),
		array('iWeight',$iWeightTotal),
		array('sDate',date('Y-m-d H:i:s')),
		array('sComment',$_SESSION['comment']),
		array('iVoucherUsed',$iBon),
		array('sDelivreryName',$_SESSION['delivreryName']),
		array('sDelivreryStreet',$_SESSION['delivreryStreet']),
		array('sDelivreryCity',$_SESSION['delivreryCity']),
		array('sDelivreryZip',$_SESSION['delivreryZip']),
		array('sDelivreryCountry',$_SESSION['delivreryCountry']),
		array('sDelivreryMail',$_SESSION['delivreryMail']),
		array('sPaymentMode',$mode_de_paiement),
		array('iDelivreryPrice',$iDelivreryPrice)
	));

	$sSubject = "Confirmation de la reception de la commande ".$sOrderCode;

    if($_SESSION['genre'] == "Mr."){
		$sDear = "Cher ".$_SESSION['genre']." ".$_SESSION['nom'];
	}elseif($_SESSION['genre'] == "Mlle." || $_SESSION['genre'] == "Mme."){
		$sDear = "Ch&egrave;re ".$_SESSION['genre']." ".$_SESSION['nom'];
	}else{
	  $sDear = "Cher client";
	}

	$sTo = $_SESSION['mail'];
	$sContent = "<div align='center' style='background-color:#C7317F; min-height:600px; margin-top:20px;'>";
	$sContent .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";

	$sContent .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:left; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; min-height:250px;'>";
	$sContent .= "<p>".$sDear.",</p>";
	$sContent .= "<p>Nous avons bien re&ccedil;u votre commande <strong>n&deg;".$sOrderCode."</strong> et vous en remercions. Nous nous appliquons &agrave; apporter le meilleur soin &agrave; l'ex&eacute;cution de celle-ci.</p><br /> <p>Votre commande sera envoy&eacute;e le jour de la r&eacute;ception de votre paiement.</p>";

	$sContent .= "<table width='420' border='1' style='font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; border-collapse:collapse;'>";
	$sContent .= $sContentArticle;	
	$sContent .= "<tr><td>Frais de port</td><td colspan='2'></td><td>".number_format($iDelivreryPrice, 2, ",", "")." &euro;</td></tr>";
	if(!empty($iBon)){
		$sContent .= "<tr><td>Bon d'achat</td><td colspan='2'></td><td>-".number_format($iBon, 2, ",", "")." &euro;</td></tr>";
	}
	$sContent .= "<tr><td style='border-bottom:1px solid black'></td><td style='border-bottom:1px solid black'></td><td style='border-bottom:1px solid black'></td><td style='border-bottom:1px solid black'></td></tr>";
	$sContent .= "<tr><td>TOTAL</td><td colspan='2'></td><td>".number_format($iTotalBasket, 2, ",", "")." &euro;</td></tr>";
	$sContent .= "</table>";

	$sContent .= "<p>Votre commentaire: ".$_SESSION['comment']."</p>";

	if($mode_de_paiement == "virement"){
		$sContent .= "<p>Ci-dessous nos coordonn&eacute;es bancaires.</p>";
		$sContent .= "<p style='text-decoration:underline'>- Virement &agrave; partir d'un compte bancaire Belge:</p>";
		$sContent .= "<p>750-6018036-23</p>";
		$sContent .= "<p>Faniel Marie-France</p>";
		$sContent .= "<p>Rue de Velaine, 95</p>";
		$sContent .= "<p>5300 Andenne</p>";
		$sContent .= "<p>Belgique</p>";
		$sContent .= "<p>Communication: <span style='color:red'>votre n&deg; de commande</span></p><br />";
		$sContent .= "<p style='text-decoration:underline'>- Virement &agrave; partir d'un compte bancaire non Belge:</p>";
		$sContent .= "<p>Compte IBAN: BE92750601803623</p>";
		$sContent .= "<p>SWIFT/BIC: AXABBE22</p>";
		$sContent .= "<p>Banque AXA</p>";
		$sContent .= "<p>Rue de la station, 41</p>";
		$sContent .= "<p>5300 Andenne</p>";
		$sContent .= "<p>Belgique</p>";
		$sContent .= "<p>Communication: <span style='color:red'>votre n&deg; de commande</span></p><br>";
	}
	if($mode_de_paiement == "cheque"){
		$sContent .= "<p>Voici nos coordonn&eacute;es pour que vous puissiez nous envoyez votre ch&egrave;que:</p> <p>Faniel Marie-France Rue de Velaine, 95 5300 Andenne Belgique.</p> <p>Votre commande partira d&egrave;s r&eacute;ception de votre paiement.</p>";
	}

	$sContent .= "<p>Nous restons &agrave; votre enti&egrave;re disposition pour toute information compl&eacute;mentaire que vous pourriez d&eacute;sirer.<br> Pour nous contacter, nous vous rappelons que notre magasin est ouvert tous les jours de la semaine sauf le lundi, de 10h30 &agrave; 13h30 et de 14h &agrave; 18h.<p><br />";
	$sContent .= "<p>Nous vous souhaitons une agr&eacute;able journ&eacute;e.</p><br />";
	$sContent .= "<p>Cordialement,</p><br />";
	$sContent .= "<p>Service client&egrave;le</p><br />";
	$sContent .= "<p><a href='mailto:serviceclient@tanais.be'>serviceclient@tanais.be</a></p><br />";
	$sContent .= "</div>";
	$sContent .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; padding-top:5px; padding-bottom:5px; margin-top:50px;'><a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";

	$sContentAdmin = '<p>Vous avez une nouvelle commande <a href="http://tanais.be/privatepart/index.php?page_admin=commande&id='.$iOrder.'">ici</a></p>';
	
	//Send to client
	$oMail = new Mail;
	$oMail->Content($sContent);
	$oMail->subject($sSubject);
	$oMail->To($sTo);
	$oMail->send();
	
	//Send to Admin
	$oMail = new Mail;
	$oMail->To('info@tanais.be');
	$oMail->Content($sContentAdmin);
	$oMail->subject('Vous avez une commande');
	$oMail->send();
	

?>

<link rel='stylesheet' media='screen' href='css/catalogue.css' />
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Paiement</title>
	
	<script type="text/javascript">
	  var gaJsHost = (("https:" == document.location.protocol ) ? "https://ssl." : "http://www.");
	  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>

	<script>
		$(document).ready(function() {

		  var pageTracker = _gat._getTracker("UA-7518604-1");
		  pageTracker._trackPageview();
		  pageTracker._addTrans(
			  "<?php echo $_GET['id'] ?>",					// order ID - required
			  "Tanais",  									// affiliation or store name
			  "<?php echo $iTotalBasket ?>",					// total - required
			  "<?php echo $iTotalBasket*21/100 ?>", 			// tax
			  "<?php echo $iDelivreryPrice ?>",					// shipping
			  "",											// city
			  "",											// state or province
			  "<?php echo getCountry($_SESSION['pays']) ?>"	// country
			);

	<?php

		foreach($_SESSION['tmpCommandeIdArticle'] as $iKey => $iArticle):
			$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$iArticle)));
			$oArticle = $aArticle[0];
			
			$prixUnitaire = $_SESSION['tmpCommandePrixUnitaire'][$iKey];		
			$iQuantity = $_SESSION['tmpCommandeQuantite'][$iKey];
			
		   // add item might be called for every item in the shopping cart
		   // where your ecommerce engine loops through each item in the cart and
		   // prints out _addItem for each 
		?>
			pageTracker._addItem(
				"<?php echo $_GET['id'] ?>",			// order ID - necessary to associate item with transaction
				"<?php echo $oArticle->reference ?>",	// SKU/code - required
				"<?php echo $oArticle->nom ?>",			// product name
				"<?php echo $oArticle->categorie ?>",	// category or variation
				"<?php echo $prixUnitaire ?>",			// unit price - required
				"<?php echo $iQuantity ?>"				// quantity - required
			);
	<?php
		endforeach;
		
		//DELETE THE BASKET
		if($bWriteOrder):
			unset($_SESSION['tmpCommandeIdArticle'], 
				$_SESSION['tmpCommandeQuantite'], 
				$_SESSION['tmpCommandePrixUnitaire'], 
				$_SESSION['tmpCommandePrixTotal'],
				$_SESSION['tmpCommandePoidsTotal'],
				$_SESSION['tmpCommande']);
		endif;
	?>
		   pageTracker._trackTrans(); //submits transaction to the Analytics servers
		} catch(err) {}
	});
	</script>

</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<div id="contentCommande">
		<h1 style="font-size:24px; color:#C7317F">Fin de la commande</h1>
		<ul id="etapes">
			<li id="first"><span class="pink">1<br />Caddie</span></li>
			<li><img src="img/site/line-arrowpink.jpg" /></li>
			<li><span class="pink">2<br />Inscription</span></li>
			<li><img src="img/site/line-arrowpink.jpg" /></li>
			<li><span class="pink">3<br />Livraison</span></li>
			<li><img src="img/site/line-arrowpink.jpg" /></li>
			<li><span class="pink">4<br />Paiement</span></li>
			<li><img src="img/site/line-arrowpinkcurrent.jpg" /></li>
			<li class="current"><span class="pink">5<br />Validation</span></li>
			<li><img src="img/site/line-last-current.jpg" /></li>
		</ul>
		<div class="clearer"></div>
		
		<?php if($_SESSION['bTotalNull']): ?>
			<p>Nous vous remercions pour votre commande.</p>
			<p>L'&eacute;quipe Tana&iuml;s</p>
			<p><a href="index.php" style="color:#c7317f">Cliquez ici pour revenir &agrave; l'accueil</a></p>
		<?php else: ?>
			<?php if($mode_de_paiement == "ogone"): ?>
				<?php $iTotalOgone = $iTotalBasket*100; ?>
				<p style='text-align:center; margin: 0 0 10px'>Veuillez cliquer sur le bouton ci-dessous pour &ecirc;tre redirig&eacute; vers un site s&eacute;curis&eacute; afin de pouvoir effectuer le paiement.</p>
				<form action='https://secure.ogone.com/ncol/prod/orderstandard.asp' id='form1' name='form1'>
					<input type='hidden' NAME='PSPID' value='FANIEL' />
					<input type='hidden' NAME='orderID' VALUE='<?php echo $sOrderCode ?>' />
					<input type='hidden' NAME='amount' VALUE='<?php echo $iTotalOgone ?>' />
					<input type='hidden' NAME='currency' VALUE='EUR' />
					<input type='hidden' NAME='language' VALUE='fr_FR' />
					<!-- lay out information -->
					<input type='hidden' NAME='TITLE' VALUE='Tanais - Paiement securise' />
					<input type='hidden' NAME='BGCOLOR' VALUE='#DEDEE6' />
					<input type='hidden' NAME='TXTCOLOR' VALUE='#000000' />
					<input type='hidden' NAME='TBLBGCOLOR' VALUE='#7B91AC' />
					<input type='hidden' NAME='TBLTXTCOLOR' VALUE='#000000' />
					<input type='hidden' NAME='BUTTONBGCOLOR' VALUE='#DEDEE6>' />
					<input type='hidden' NAME='BUTTONTXTCOLOR' VALUE='#000000' />
					<input type='hidden' NAME='LOGO' VALUE='header-logo.jpg' />
					<input type='hidden' NAME='FONTTYPE' VALUE='arial' />
					<!-- miscellanous -->
					<input type='hidden' NAME='COM' VALUE='Voir d&eacute;tail commande'>
					<INPUT type='hidden' NAME='CN' VALUE='<?php echo $_SESSION['nom'].' '.$_SESSION['prenom']; ?>' />
					<INPUT type='hidden' name='EMAIL' value='<?php echo $_SESSION['mail'] ?>' />
					<input type='hidden' NAME='PM' VALUE='' />
					<input type='hidden' NAME='BRAND' VALUE='' />
					<input type='hidden' NAME='ownerZIP' VALUE='' />
					<input type='hidden' NAME='owneraddress' VALUE='' />
					<input type='submit' value='Acc&eacute;der au paiement en ligne' class='btn paiement' id='submit2' name='submit2' />
				</form>
			<?php endif; ?>
	
			<?php if($mode_de_paiement == "paypal"): ?>
				<p style='text-align:center; margin: 0 0 10px'>Veuillez cliquer sur le bouton ci-dessous pour &ecirc;tre redirig&eacute; vers un site s&eacute;curis&eacute; afin de pouvoir effectuer le paiement.</p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="business" value="info@tanais.be">
					<input type="hidden" name="item_name" value="Tana&iuml;s">
					<input type="hidden" name="amount" value='<?php echo $iTotalBasket; ?>'>
					<input type="submit" value="Acc&eacute;der au paiement via Paypal" class="btn paiement" style="text-align:center; margin:auto">
				</form>
			<?php endif; ?>
	
			<?php if($mode_de_paiement == "Carte bleu"): ?>
				<p style='text-align:center; margin: 0 0 10px'>Veuillez cliquer sur le bouton ci-dessous pour &ecirc;tre redirig&eacute; vers un site s&eacute;curis&eacute; afin de pouvoir effectuer le paiement.</p>
				
			<?php
				echo '<a target="_blank" class="btn paiement" href="https://www.eurowebpayment.org/cb/?EWP_KEY=UN3308FR&EWP_AMOUNT='.$iTotalBasket.'">Paiement par carte bleu</a>';
			endif;
	
			if($mode_de_paiement == "virement" || $mode_de_paiement == "cheque"):
			?>
				<p>Nous vous remercions pour votre commande. Vous allez recevoir  un mail de confirmation ainsi que les coordon&eacute;es bancaires adapt&eacute;es au mode de paiement que vous avez choisi.</p>
				<p>L'&eacute;quipe Tana&iuml;s</p>
				<p><a href="index.php" style="color:#c7317f">Cliquez ici pour revenir &agrave; l'accueil</a></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
<?php 
	include('include/footer.php'); 
endif;
?>