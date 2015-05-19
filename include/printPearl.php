<div class="pearl-box">
	<div class='catalogue carre-block'>
		<img src='img/site/shadow-left.jpg' class='image border' />
		<a href='catalogue.php?id=<?php echo $oArticle->ID?>'><img class='mini image' src="img/catalogue/<?php echo $oArticle->sImage; ?>" title="<?php echo $oArticle->sNamei18n; ?>"  alt="<?php echo $oArticle->sNamei18n; ?>" /></a>
		<img src='img/site/shadow-right.jpg' class='image border' />
	</div>
	<div class='form-catalogue'>
		<p class='nom-article'><?php echo $oArticle->sNamei18n; ?></p>
		
		<?php $aPromo = dataManager::Read('ts_promo', array(array('iArticle', '=', $oArticle->ID))); ?>
		<?php if(!empty($aPromo[0])): ?>
			<?php $oPromo = $aPromo[0]; ?>
			<?php $iResult = $oArticle->iPrice - ($oArticle->iPrice * $oPromo->iValue / 100); ?>
			<p style='margin-left:0px;'>Prix: <span style='text-decoration:line-through;'><?php echo number_format($oArticle->iPrice, 2, ",", "") ?> &euro; TTC</span><br /> -<?php echo $oPromo->iValue ?> %</p>
			<p><strong style='color:red'><?php echo number_format($iResult, 2, ",", "") ?> &euro; TTC</strong></p>
		<?php else: ?>
			<p style='margin-left:0px;'>Prix: <?php echo number_format($oArticle->iPrice, 2, ",", "") ?> &euro; TTC</p>
		<?php endif; ?>
			
		<form method='post' id='article' enctype='application/x-www-form-urlencoded' name='caddie' action="sys/sql-article.php?id=<?php echo $oArticle->ID; ?>">
			<input type='text' size='2' name='quantite' value='1' class='caddiePerle'>
			<input type='hidden' value="<?php echo $_SERVER["REQUEST_URI"] ?>" size='2' name='url'>
			<input type='submit' value='' class='ajout-article'>
		</form>
		<a href='catalogue.php?id=".$row[0]."' style='background:pink; padding:0 5px; color:#333; text-decoration:none'>Détail</a>
	</div>
</div>