<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" media="screen" href="css/print.css" />
	<link rel="stylesheet" media="print" href="css/media-print.css" />
	<script type="text/javascript" src="javascript/jquery-1.4.2.js"></script>
	<script>
		$(document).ready(function(){
			$('.editLine').click(function(){
				$(this).parent('tr').toggleClass('printNone');
				if($(this).hasClass('deleteLine')){
					$(this).addClass('addLine').removeClass('deleteLine');
				}else{
					$(this).addClass('deleteLine').removeClass('addLine');
				}
				return false;
			});
		});
	</script>
	<title>Impression de la commande</title>
</head>
<?php include('../sys/connect.php'); ?>
<?php include('../include/function.php'); ?>
<?php include('../include/class.php'); ?>
<?php $_SESSION['lng'] = 'FR'; ?>
<?php dataManager::Write('update','ts_commande',array(array('bPrint',1)),array(array('ID','=',$_GET['id_commande']))); ?>
<body>
<div id="logoPrint">
	<img src='../img/site/header-logo.jpg' />
</div>
<?php $oOrder = dataManager::ReadOne('ts_commande',array(array('ID','=',$_GET['id_commande']))); ?>

<div id="adresseTanais"><h4>Tana&iuml;s</h4> Rue de Velaine, 95<br/> 5300 Andenne<br/> Belgique</div>
<div id="adresseClient"><h4>Adresse de livraison</h4><?php echo $oOrder->sDelivreryName.'<br />'.$oOrder->sDelivreryStreet.', '.$oOrder->sDelivreryNumber.' '.$oOrder->sDelivreryBox.'<br />'.$oOrder->sDelivreryZip.' '.$oOrder->sDelivreryCity.'<br />'.getCountry($oOrder->sDelivreryCountry); ?></div>
<div class="clear"></div>

<table border='0' cellspacing='0' cellpadding='2'>
	<tbody>
		<tr>
			<td class="borderNone"></td>
			<td colspan="7" bgcolor="#2A88CB" style="text-align:center; color:#000000;">Rapport de la commande n&deg; <?php echo $oOrder->sOrderCode ?></td>
		</tr>
		<tr class="head" bgcolor="#2A88CB">
			<td class="borderNone"></td>
			<td class="borderLeft">D&eacute;signation</td>
			<td width="5">Qt&eacute; cmd&eacute;e</td>
			<td width="10">Qt&eacute; lvr&eacute;e</td>
			<td width="10">Qt&eacute; rest</td>
			<td width="50">prix unitaire</td>
			<td width="50">prix total</td>
			<td class="borderRight" width="60">Statut</td>
		</tr>

	<?php
		$flag = True;
		$article = array();
		$aArticle = dataManager::Read('ts_commande_article',array(array('iOrder','=',$oOrder->ID)),array('sName_FR','ASC'),null,array(array('inner join','ts_catalogue',array(array('iArticle','=','ts_catalogue.ID')))));
		foreach($aArticle as $oArticle){
			$sClass = ($flag) ? 'even' : 'odd';
			$oDouble = dataManager::ReadOne('ts_commande_article',array(array('iOrder','=',$_GET['id_commande']),array('iArticle','=',$oArticle->ID),array('sStatus','<>',$oArticle->sStatus)));
			if(!empty($oDouble)):
				$nombreAsk = $oArticle->iQuantity + $oDouble->iQuantity;
				$nombreRest = $nombreAsk - $oArticle->iQuantity;
				$statut = 'Partiel';
			else:
				$nombreRest = 0;
				$nombreAsk = $oArticle->iQuantity;
				$statut = $oArticle->sStatus;
			endif;
			$nombreDoublon = (isset($oDouble->iQuantity)) ? $oDouble->iQuantity : 0;
			
			switch($statut) {
				case "en_cours":
					$statutPrint = "En cours";
					$nombre = $nombreDoublon;
					break;
				case "envoye":
					$statutPrint = "Envoy&eacute;";
					$nombre = $row['nombre'];
					break;
				case "reliquat":
					$statutPrint = "Bon d'achat";
					$nombre = 0;
					break;
				case "partiel":
					$statutPrint = "Partiel";
					$nombre = $row['nombre'];
					break;
			}
			
			if(!in_array($oArticle->ID, $article)):
	?>
		<tr class='<?php echo $sClass ?>'>
			<td class="borderNone editLine deleteLine"><a href="#">Test</a></td>
			<td class="name"><?php echo $oArticle->sName_FR; ?></td>
			<td class="quantite"><?php echo $nombreAsk; ?></td>
			<td class="quantite"><?php echo $nombre; ?></td>
			<td class="quantite"><?php echo $nombreRest; ?></td>
			<td><?php echo number_format($oArticle->iPrice, 2, ",", ""); ?> &euro;</td>
			<td><?php echo number_format($oArticle->iTotal, 2, ",", ""); ?> &euro;</td>
			<td class="last"><?php echo $statutPrint  ?></td>
		</tr>
	<?php
			endif;
			$article[] = $oArticle->ID;
			$flag = !$flag;
		}	
	?>
		<tr>
			<td class="borderNone"></td>
			<td colspan="7"></td>
		</tr>
		<tr>
			<td class="borderNone"></td>
			<td colspan="5">Frais de port</td>
			<td><?php echo number_format($oOrder->iDelivreryPrice, 2, ",", ""); ?> &euro;</td>
			<td></td>
		</tr>
		<tr>
			<td class="borderNone"></td>
			<td colspan="5">TOTAL</td>
			<td><?php echo number_format($oOrder->iTotal, 2, ",", ""); ?> &euro;</td>
			<td></td>
		</tr>
	</tbody>
</table>
<div class="redBox"><p>Poids total: <?php echo $oOrder->iWeight; ?> gr.</p></div>
<div class="redBox">
<?php
		if($oOrder->iVoucherUsed > 0){echo "<p>Pour cette commande, le client a utilis&eacute; un bon d'achat d'une valeur de ".number_format($oOrder->iVoucherUsed, 2, ",", "")." &euro;</p>";}
		if($oOrder->iVoucherCreated > 0){echo "<p style='color:red'>Un bon d'achat d'une valeur de ".number_format($oOrder->iVoucherCreated, 2, ",", "")." &euro; a &eacute;t&eacute; cr&eacute;&eacute; dans cette commande via les reliquats</p>";}
		if($oOrder->sClientComment){ echo "<p>Commentaire laiss&eacute; par le client: ".stripslashes(htmlentities(html_entity_decode($oOrder->sClientComment)))."</p>";}
?>
</div>
</body>
</html>