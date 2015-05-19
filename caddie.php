<?php 

include('sys/session.php');

if(isset($_GET['deleteArticle'])){
	if(sizeof($_SESSION['tmpCommandeIdArticle']) == 1){
		unset($_SESSION['tmpCommande']);
	}else{
		$key = array_search($_GET['id'], $_SESSION['tmpCommandeIdArticle']);
		unset($_SESSION['tmpCommandeIdArticle'][$key], 
			$_SESSION['tmpCommandeQuantite'][$key], 
			$_SESSION['tmpCommandePrixUnitaire'][$key], 
			$_SESSION['tmpCommandePrixTotal'][$key],
			$_SESSION['tmpCommandePoidsTotal'][$key]);
	}
}

if(isset($_GET['deleteCaddie'])){
	unset($_SESSION['tmpCommandeIdArticle'], 
		$_SESSION['tmpCommandeQuantite'], 
		$_SESSION['tmpCommandePrixUnitaire'], 
		$_SESSION['tmpCommandePrixTotal'],
		$_SESSION['tmpCommandePoidsTotal'],
		$_SESSION['tmpCommande']);
}

	if(empty($_SESSION['tmpCommande'])){
		header('Location:index.php');
	}else{
	include('include/header.php'); 
	echo '<link rel="stylesheet" type="text/css" media="print" href="css/print.css" />';
?>
	<script>
		$(document).ready(function() {
			$("#fraisDePort").mousemove(function(e){
				oLeft = e.pageX;
			  	oTop = e.pageY;
				$("#hideFraisDePort").css("display", "block");
				$("#hideFraisDePort").css("left", oLeft+10);
				$("#hideFraisDePort").css("top", oTop+10);
		   });
   
			$("#fraisDePort").mouseout(function(e){
	  			oLeft = e.pageX;
	  			oTop = e.pageY;
	  			$("#hideFraisDePort").css("display", "none");
   			});

		});
		
	</script>
	
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Caddie</title>
</head>

<body>
<?php include('include/contentHead.php'); ?>
<div id="content">
<h1 style="font-size:24px; color:#C7317F">Votre panier</h1>

<ul id="etapes">
	<li id="first" class="current"><span>1<br />Caddie</span></li>
	<li><img src="img/site/line-arrowcurrent.jpg" /></li>
	<li><span class="grey">2<br />Inscription</span></li>
	<li><img src="img/site/line-arrowgrey.jpg" /></li>
	<li><span class="grey">3<br />Livraison</span></li>
	<li><img src="img/site/line-arrowgrey.jpg" /></li>
	<li><span class="grey">4<br />Paiement</span></li>
	<li><img src="img/site/line-arrowgrey.jpg" /></li>
	<li><span class="grey">5<br />Validation</span></li>
	<li><img src="img/site/line-last.jpg" /></li>
</ul>

<table cellspacing="0" border="1" cellpadding="0" id="caddie" width="800">
	<thead>
		<tr>
			<th width="40"></th>
			<th>Nom</th>
			<th width="50">Quantit&eacute;</th>
			<th width="90">Prix unitaire</th>
			<th width="80">Prix total</th>
			<th width="70">D&eacute;lais*</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php 

	$poids = 25;
	$iTotal = 0;
	$bFlag = true;
	$frais_port = 0;
	foreach($_SESSION['tmpCommandeIdArticle'] as $key=>$idArticle): 
		$oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$idArticle)));
		$sClass = ($bFlag == true) ? $sClass = 'odd' : 'even';
?>
		<tr class='<?php echo $sClass ?>'>
			<td align="center" class="imgArticle">
				<a href="img/zoom/<?php echo $oArticle->sImage; ?>" class="pirobox" title="<?php echo $oArticle->sNamei18n ?>">
					<img src='img/catalogue/<?php echo $oArticle->sImage; ?>' width='30' />
				</a>
			</td>
			<td>
				<a href='catalogue.php?id=<?php echo $idArticle; ?>' title='<?php echo $oArticle->sNamei18n; ?>'><?php echo $oArticle->sNamei18n; ?></a>
			</td>
			<td align="center">
				<form method='post' enctype='application/x-www-form-urlencoded' name='caddie' action='sys/sql-article.php?option=caddieMaj'>
					<input class='caddie-quantite' maxlength='4' type='text' size='3' name='<?php echo $idArticle; ?>' value='<?php echo $_SESSION['tmpCommandeQuantite'][$key]; ?>' />
			</td>
			<td align="right"><?php echo number_format($_SESSION['tmpCommandePrixUnitaire'][$key], 2, ",", ""); ?> &euro;</td>
			<td align="right"><?php echo number_format($_SESSION['tmpCommandePrixTotal'][$key], 2, ",", ""); ?> &euro;</td>
				<?php if($oArticle->iStock < $_SESSION['tmpCommandeQuantite'][$key]): ?>
					<?php if($oArticle->bStockSoldOut == 0): ?>
						<td class='table-delai'><p style='color:red; font-size:10px;'>Livraison: 6 semaines</p></td>
					<?php else: ?>
						<td class='table-delai'><p style='color:orange; font-size:10px;'>Livraison: +/- 10 jours</p></td>
					<?php endif; ?>
				<?php else: ?>
					<td class='table-delai'><p style='color:green; font-size:10px;'>Livraison: imm&eacute;diate</p></td>
				<?php endif; ?>
			<td width="40" align="center">
				<a href='?deleteArticle=true&id=<?php echo $idArticle; ?>' style='color:red' title="supprimer l'article">
					<img src='img/site/delete.png' onclick="return confirm('Etes-vous s&ucirc;r de vouloir supprimer cet objet de votre liste?')" style='border:none;' class='delete' />
				</a>
			</td>
		</tr>
<?php 
	$iTotal += $_SESSION['tmpCommandePrixTotal'][$key];
	$poids += $_SESSION['tmpCommandePoidsTotal'][$key];
	$bFlag = !$bFlag;
endforeach; 	
?>

		<tr>
			<td colspan='2'></td>
			<td><input type='submit' style="color:#ffffff;" value='Recalculer' /></form></td>
			<td colspan='4'></td>
		</tr>
	
<?php
	$aDelivreryContry = dataManager::Read('ts_delivrery_cost',null,null,null, null,array('distinct','sCountry'));
	if(in_array($_SESSION['pays'],$aDelivreryContry)):
		$oDelivrery = dataManager::ReadOne('ts_delivrery_cost',array(array('sCountry','=',$_SESSION['pays']),array('iWeightMin','<=',$poids),array('iWeightMax','>',$poids)));
	else:
		$oDelivrery = dataManager::ReadOne('ts_delivrery_cost',array(array('sCountry','=','ALL'),array('iWeightMin','<=',$poids),array('iWeightMax','>',$poids)));
	endif;
	$iDelivreryPrice = ($iTotal >= 35 && $_SESSION['pays'] == 'BE') ? 0 : $oDelivrery->iPrice;
	$iDelivreryPrice = ($iTotal >= 50 && $_SESSION['pays'] == 'ALL') ? 0 : $oDelivrery->iPrice;
	
	$iTotal += $iDelivreryPrice;
?>
			<tr>
				<td colspan='4'>
					<p id='fraisDePort'>Frais de port<span style='font-size:10px;'>**</span></p>
				</td>
				<td align='right'>
					<p id="delivreryCost"><?php echo number_format($iDelivreryPrice, 2, ",", "") ?> &euro;</p>
				</td>
				<td colspan='2'></td>
			</tr>
			<tr>
				<td colspan='4'>
					<p>TOTAL</p>
				</td>
				<td align='right'>
					<p id="totalCaddie"><?php echo number_format($iTotal, 2, ",", "") ?> &euro;</p>
				</td>
				<td colspan='2'></td>
			</tr>
		</tbody>
	</table>
	<div id="hideFraisDePort" style="display:none; position:absolute;">
		<h3>Frais de port</h3>
		<table>
			<thead>
				<tr>
					<th>Poids</th>
					<th>Prix en Belgique</th>
					<th>Prix à l'étranger</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td> &lt; 100g. </td>
					<td> 1,68 &euro;</td>
					<td> 3,50 &euro;</td>
				</tr>
				<tr>
					<td> 100g. &lt;&gt; 350 g. </td>
					<td> 2.27 &euro;</td>
					<td> 5,90 &euro;</td>
				</tr>
				<tr>
					<td> 350g. &lt;&gt; 1 kg. </td>
					<td> 3,45 &euro;</td>
					<td> 9,50 &euro;</td>
				</tr>
				<tr>
					<td> 1kg. &lt;&gt; 2kg. </td>
					<td> 4,63 &euro;</td>
					<td> 19 &euro;</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
	<form action="commande-login.php" method="post" enctype="application/x-www-form-urlencoded" onsubmit="return valid()">
		<?php if(isset($_SESSION['id'])): ?>
			<?php $oBon = dataManager::ReadOne('ts_reliquat',array(array('iClient','=',$_SESSION['id']))); ?>
			 		
			<script type="text/javascript">
				$(document).ready(function(){
					if($('#reliquat')){
						$('#reliquat').click(function(){
							if($(this).attr('checked')){
								<?php if($oBon->iValue > $iTotal - $iDelivreryPrice): ?>
									$('#delivreryCost').text('0,00 €');
									$('#totalCaddie').text('0,00 €');
									$('#bTotalNull').val('true');
								<?php else: ?>
									$('#totalCaddie').text('<?php echo number_format($iTotal - $oBon->iValue, 2, ',',''); ?> €');
									$('#bTotalNull').val('false');
								<?php endif; ?>
							}else{
								$('#delivreryCost').text('<?php echo number_format($iDelivreryPrice, 2, ',','') ?> €');
								$('#totalCaddie').text('<?php echo number_format($iTotal, 2, ',',''); ?> €');
								$('#bTotalNull').val('false');
							}
						});
					}
				});
				
			</script>
			<?php if($oBon->iValue != 0): ?>
				<div>
					<?php $iValue = $iTotal - $iDelivreryPrice; ?>
					<input type='checkbox' name='reliquat' id='reliquat' />
					<input type="hidden" name="bTotalNull" id="bTotalNull" value="false" />
					<label for='reliquat' class='labelCaddie'>Activer votre bon d'achat d'une valeur de <span style='color:#C7317F;'><?php echo $oBon->iValue ?>&euro;</span></label>
				</div> 
			<?php endif; ?>
		<?php endif; ?>
		<div><input type="checkbox" name="conditon" value="condition" id="condition" /> <label for="condition" class="labelCaddie"><span id="activeCondition">J'ai lu et compris </span><a href="condition.php" target="_blank">les conditions g&eacute;n&eacute;rales</a></label></div>
		<script>
			function valid(){
				var check = $("input#condition").attr("checked");
				if(!check){
					alert("Vous devez accepter les conditions générales");
					return false;
				}
			}
		</script>
		<div id="deleteCaddie">
			<p class="deleteCaddieBouton"><a href="?deleteCaddie=true" onclick="return(confirm('Etes-vous s&ucirc;r de vouloir vider votre caddie?'))">Vider le caddie</a></p>
		</div>
		<div id="confirmCaddie">
			<input type="submit" class="confirmCaddieBouton" value="Confirmer votre commande"/>
		</div>
	</form>
	<p style="font-size:10px;">* Sauf circonstances ind&eacute;pendantes de notre volont&eacute;</p>
	<p style='font-size:10px'>** Les frais de port sont calcul&eacute;s pour une livraison en Belgique.</p>
</div>
<?php include('include/footer.php'); } ?>