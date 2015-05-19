<p id="breadcrumb">
	<a href="index.php">Accueil</a>
	<?php if(isset($_GET['id'])): ?>
		<?php
			$aArticle =	dataManager::Read('ts_catalogue',array(array('ID','=',$_GET['id'])));
			$oArticle = $aArticle[0];
			$aCat = dataManager::Read('ts_categorie',array(array('ID','=',$oArticle->iCategory)));
			$oCat = $aCat[0];
		?>
		>
		<a href="catalogue.php?cat=<?php echo $oCat->ID?>"><?php echo $oCat->sCategoryi18n; ?></a>
		>
		<a href="catalogue.php?subcat=<?php echo $oCat->ID?>"><?php echo $oCat->sSubCategoryi18n; ?></a>
		>
		<?php echo $oArticle->sNamei18n ?>
	<?php endif; ?>
	
	<?php if(isset($_GET['cat'])): ?>
		<?php
			$aCat = dataManager::Read('ts_categorie',array(array('ID','=',$_GET['cat'])));
			$oCat = $aCat[0];
		?>
		>
		<?php echo $oCat->sCategoryi18n; ?>
	<?php endif; ?>
	
	<?php if(isset($_GET['subcat'])): ?>
		<?php
			$aCat = dataManager::Read('ts_categorie',array(array('ID','=',$_GET['subcat'])));
			$oCat = $aCat[0];
		?>
		>
		<a href="catalogue.php?cat=<?php echo $oCat->ID?>"><?php echo $oCat->sCategoryi18n; ?></a>
		>
		<?php echo $oCat->sSubCategoryi18n; ?>
	<?php endif; ?>
</p>