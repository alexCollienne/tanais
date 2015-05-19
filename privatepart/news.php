<?php

	if(isset($_POST['submitNews'])):
		$_SESSION['lng'] = $_POST['lngChoice'];
		
		if(isset($_POST['id'])):
			$bAdd = dataManager::Write('update','ts_news', array(array('sNewsi18n',$_POST['sNewsi18n']),array('sDate',date('Y-m-d G:i:s'))),array(array('ID','=',$_POST['id'])));
		else:
			$bAdd = dataManager::Write('insert', 'ts_news', array(
				array('sNewsi18n',$_POST['sNewsi18n']),
				array('sDate',date('Y-m-d G:i:s'))));
		endif;
		if($bAdd):
			$sMessage = 'La news a bien &eacute;t&eacute; ajout&eacute;e';
			$sClass = 'greenBox';
		else:
			$sMessage = 'La news n\'a pas &eacute;t&eacute; ajout&eacute;e :'.mysql_error();
			$sClass = 'redBox';
		endif;
	endif;
		
	if(isset($_GET['option'])):
		switch($_GET['option']):
			
			case 'upnews':
				$bUp = dataManager::Write('update','ts_news', array(array('sDate',date('Y-m-d G:i:s'))),array(array('ID','=',$_GET['id'])));
				if($bUp):
					$sMessage = 'La news a bien &eacute;t&eacute; mise en premi&egrave;re place';
					$sClass = 'greenBox';
				else:
					$sMessage = 'Un probl&egrave;me est apparu avec la mise &agrave; jour :'.mysql_error();
					$sClass = 'redBox';
				endif;
			break;
			
			case 'delete':
				$bDelete = dataManager::Write('delete','ts_news',null,array(array('ID','=',$_GET['id'])));
				if($bDelete):
					$sMessage = 'La news a bien &eacute;t&eacute; supprim&eacute;e';
					$sClass = 'greenBox';
				else:
					$sMessage = 'Un probl&egrave;me est apparu lors la suppression :'.mysql_error();
					$sClass = 'redBox';
				endif;
			break;
		endswitch;
	endif;
?>

<?php if(isset($sMessage)): ?>
	<p class="<?php echo $sClass; ?>"><?php echo $sMessage; ?></p>
<?php endif; ?>
<?php if(isset($_GET['modify'])): ?>
	<?php $oNews = dataManager::ReadOne('ts_news',array(array('ID','=',$_GET['id']))); ?>
<?php endif; ?>
<h1>Ajouter une news</h1>
<div id="addNews">
	<form method="POST" action="index.php?page_admin=news" class="form-admin">
		<?php if(isset($oNews)): ?>
			<input type="hidden" value="<?php echo $oNews->ID ?>" name="id" />
		<?php endif; ?>
		<div class="field-form">
			<label>Langue</label>
			<?php echo getSelectLng($_SERVER['REQUEST_URI']) ?>
		</div>
		<div class="clear"></div>
		<div class="field-form">
			<label>News</label>
			<textarea name="sNewsi18n" class="tinymce"><?php echo (isset($oNews)) ? $oNews->sNewsi18n : '' ?></textarea>
		</div>
		<div class="clear"></div>
		<input type="submit" value="Ajouter la news" name="submitNews" />
	</form>
</div>

<h1>Liste des news</h1>

<?php $aNews = dataManager::Read('ts_news',null,array('sDate','DESC')); ?>
<?php foreach($aNews as $iKey => $oNews): ?>
	<div class="boxNews">
		<p class="news-place"><?php echo $iKey+1 ?>.</p>
		<div class="news-text">
			<p><?php echo $oNews->sNewsi18n ?></p>
		</div>
		<div class="clear"></div>
		<p class="button"><a href="?page_admin=news&option=upnews&id=<?php echo $oNews->ID ?>">Mettre cette news en première place</a>
		<p class="button"><a href="?page_admin=news&option=delete&id=<?php echo $oNews->ID ?>">Supprimer cette news</a>
		
		<?php if(!empty($oNews->sNews_FR)): ?>
			<p class="button"><a href="?page_admin=news&modify=true&lng=fr&id=<?php echo $oNews->ID ?>">Editer cette news (FR)</a>
		<?php else: ?>
			<p class="button"><a href="?page_admin=news&modify=true&lng=fr&id=<?php echo $oNews->ID ?>">Traduire cette news (FR)</a>
		<?php endif; ?>
		
		<?php if(!empty($oNews->sNews_EN)): ?>
			<p class="button"><a href="?page_admin=news&modify=true&lng=en&id=<?php echo $oNews->ID ?>">Editer cette news (EN)</a>
		<?php else: ?>
			<p class="button"><a href="?page_admin=news&modify=true&lng=en&id=<?php echo $oNews->ID ?>">Traduire cette news (EN)</a>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
