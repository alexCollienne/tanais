<?php 

	$sMessage = '';
	if(isset($_GET['delete'])):
		$bDelete = dataManager::Write('delete','ts_promo',null,array(array('ID','=',$_GET['id'])));
		if($bDelete):
			$sMessage = '<p class="greenBox">La promotion a bien été effacée</p>';
		else:
			$sMessage = '<p class="redBox">Un problème est survenu lors de la suppression de la promotion. Veuillez réessayer.</p>';
		endif;
	endif;

?>

<?php echo $sMessage; ?>
<h1>Promotions</h1>
<?php $aListValue = array(); ?>
<?php $aPromo = dataManager::Read('ts_promo',array(array('sType','=','promo')),array('iValue','ASC')); ?>
<?php foreach ($aPromo as $oPromo): ?>
	<?php if(!in_array($oPromo->iValue,$aListValue)): ?>
		<h2 class="clear"><?php echo $oPromo->iValue ?> %</h2>
	<?php endif; ?>
	<?php $aListValue[] = $oPromo->iValue; ?>
	<?php $oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$oPromo->iArticle))); ?>
	<div class='catalogue-image'>
		<a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>' title='<?php echo $oArticle->sName_FR ?>'>
			<img src='../img/catalogue/<?php echo $oArticle->sImage ?>' alt='<?php echo $oArticle->sName_FR ?>' />
		</a>
		<a href='index.php?page_admin=promo&delete=true&id=<?php echo $oPromo->ID ?>'>Supprimer</a>
	</div>
<?php endforeach; ?>
<div class="clear"></div>

<h1>Soldes</h1>
<?php $aListValue = array(); ?>
<?php $aPromo = dataManager::Read('ts_promo',array(array('sType','=','solde')),array('iValue','ASC')); ?>
<?php foreach ($aPromo as $oPromo): ?>
	<?php if(!in_array($oPromo->iValue,$aListValue)): ?>
		<h2 class="clear"><?php echo $oPromo->iValue ?> %</h2>
	<?php endif; ?>
	<?php $aListValue[] = $oPromo->iValue; ?>
	<?php $oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$oPromo->iArticle))); ?>
	<div class='catalogue-image'>
		<a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>' title='<?php echo $oArticle->sName_FR ?>'>
			<img src='../img/catalogue/<?php echo $oArticle->sImage ?>' alt='<?php echo $oArticle->sName_FR ?>' />
		</a>
		<a href='index.php?page_admin=promo&delete=true&id=<?php echo $oPromo->ID ?>'>Supprimer</a>
	</div>
<?php endforeach; ?>
<div class="clear"></div>