<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>
	<link rel='stylesheet' media='screen' href='css/catalogue.css' />
	<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
	<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->	
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Patrons</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>
<?php include('include/breadcrumb.php'); ?>
<div id="content">
	<?php
	//Pour afficher la page d'un seul patron
	if(isset($_GET['id'])):
		$aPatron = dataManager::Read('ts_patron',array(array('ID','=',$_GET['id'])));
		$oPatron = $aPatron[0];
	?>	
	<h1 style='font-size:24px; color:#C7317F'><?php echo $oPatron->sNamei18n ?></h1>
	
	<div style='width:234px; height:200px; margin:auto; margin-bottom:20px;'>
		<img src='img/site/shadow-left.jpg' style='width:12px; height:200px;' />
		<a href="img/patron/image/<?php echo $oPatron->sImage ?>" class="pirobox" title="'.$row2[1].'">
			<img src='img/patron/image/<?php echo $oPatron->sImage ?>' style='border:none' width='200' height='200' alt='' />
		</a>
		<img src='img/site/shadow-right.jpg' style='width:12px; height:200px;' />
	</div>
	
	<a href='img/patron/pdf/<?php echo $oPatron->sPdfi18n ?>' title='pdf' target='_blank' style='text-align:center; display:block;' class='btn'>Voir le patron</a>
	
	<?php $aArticle = dataManager::Read('ts_patron_link',array(array('iPatron','=',$oPatron->ID)),null,null,array(array('INNER JOIN','ts_catalogue',array(array('ts_catalogue.ID','=','ts_patron_link.iArticle'))))); ?>
	<div id='patron-box'>
		<div><img src='img/site/patron-top.jpg'/></div>
		<h1 style='color:#C7317F; font-size:17px; padding-left:20px; margin:10px 0 0'>Mat&eacute;riel n&eacute;cessaire</h1>
		<table id='materiel'>
			<?php 
			$i = 0;
		    $iTotalKit = 0;
			foreach($aArticle as $oArticle):
				$i++;
		        $iPrice = $oArticle->iQuantity * $oArticle->iPrice;
		        $iTotalKit += $iPrice;
			?>
			<tr>
				<td>
					<p><?php echo $oArticle->iQuantity ?> pcs - <a href='catalogue.php?id_patron=<?php echo $_GET['id'] ?>&id=<?php echo $oArticle->ID ?>'><?php echo $oArticle->sNamei18n ?></a></p>
				</td>
		    	<td width='100' align='center'>
		        	<p><?php echo number_format($iPrice, 2, ",", "") ?>&euro;</p>
		        </td>
				<td>
					<?php if($oArticle->iQuantity > $oArticle->iStock && $oArticle->bStockSoldOut == 0): ?>
						<p>Hors-stock</p>
						<?php $sOnClick = ' onclick="return confirm(\'Certains articles composant ce patron sont hors-stock. Etes-vous sûr de vouloir passer votre commande?\');"'; ?>
					<?php else: ?>
						<form method='post' enctype='application/x-www-form-urlencoded' name='caddie' action='sys/sql-article.php?id=".$row[0]."&idPatron=<?php echo $_GET['id'] ?>&option=patronSimple'>
							<input type='hidden' size='2' name='quantite' value='".$row[22]."'><input type='submit' value='Ajouter au panier' class='btn submit'>
						</form>
						<?php $sOnClick = ''; ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<p style='margin:0 0 10px 22px'>Prix du kit: <?php echo number_format($iTotalKit, 2, ",", "") ?>&euro;</p>
		<a href="sys/sql-article.php?option=patron_multiple&ipatron=<?php echo $_GET['id'] ?>"<?php echo $sOnClick; ?> style='margin-bottom:20px; margin-left:22px;' class='btn submit'>Acheter le kit</a>
		<div><img src='img/site/patron-bottom.jpg'/></div>
	</div>
<?php else:
		$aPatron = dataManager::Read('ts_patron',null,array('sDate','DESC'));
		foreach($aPatron as $oPatron): ?>
			<div id='content-patron'>
				<img src='img/site/shadow-left.jpg' style='width:10px; height:129px;' />
				<a href='patron.php?id=<?php echo $oPatron->ID ?>' title="<?php echo addslashes($oPatron->sNamei18n) ?>"><img src='img/patron/image/<?php echo $oPatron->sImage ?>' width='129' height='129' alt="<?php echo $oPatron->sNAmei18n ?>" style='border:none' /></a>
				<img src='img/site/shadow-right.jpg' style='width:10px; height:129px;' />
			</div>
		<?php endforeach; ?>
	<?php endif;?>
</div>
<?php include('include/footer.php'); ?>