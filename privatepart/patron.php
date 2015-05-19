<?php 
	$sMessage = '';
	
	if(isset($_POST['submitForm'])):
		if(is_numeric($_POST['iQuantity']) && $_POST['iQuantity'] > 0):
			$oArticle = dataManager::ReadOne('ts_patron_link',array(array('iArticle','=',$_GET['iarticle']),array('iPatron','=',$_GET['id'])));
			if($oArticle):
				$bAdd = dataManager::Write('update','ts_patron_link', array(array('iQuantity',$_POST['iQuantity'])),array(array('iArticle','=',$_GET['iarticle']),array('iPatron','=',$_GET['id'])));
				if($bAdd):
					$sMessage = '<p class="greenBox">L\'article a bien été  édité</p>';
				else:
					$sMessage = '<p class="redBox">L\'article n\'a pas pu être ajouté au patron. Veuillez réessayer.</p>';
				endif;
			else:
				$bAdd = dataManager::Write('insert','ts_patron_link', array(array('iArticle',$_GET['iarticle']),array('iPatron',$_GET['id']),array('iQuantity',$_POST['iQuantity'])));
				if($bAdd):
					$sMessage = '<p class="greenBox">L\'article a bien été ajouté au patron</p>';
				else:
					$sMessage = '<p class="redBox">L\'article n\'a pas pu être ajouté au patron. Veuillez réessayer.</p>';
				endif;
			endif;
		else:
			$sMessage = '<p class="redBox">Veuillez encoder une quantité valide.</p>';
		endif;
	endif;
	
	if(isset($_POST['submitPatron'])):
		$aValues = array(array('sNamei18n',$_POST['sNamei18n']),array('sDate',date('Y-m-d')));
		
		if($_FILES['sImage']['name'] != ''):
			$aValues[] = array('sImage',$_FILES['sImage']['name']);
			move_uploaded_file($_FILES['sImage']['tmp_name'], "../img/patron/image/".$_FILES['sImage']['name']);
		endif;
			
		if($_FILES['sPdfi18n']['name'] != ''):
			$aValues[] = array('sPdfi18n',$_FILES['sPdfi18n']['name']);
			move_uploaded_file($_FILES['sPdfi18n']['tmp_name'], "../img/patron/pdf/".$_FILES['sPdfi18n']['name']);
		endif;
		
		if(isset($_GET['id'])):
			$bAdd = dataManager::Write('update','ts_patron',$aValues,array(array('ID','=',$_GET	['id'])));
		else:
			$bAdd = dataManager::Write('insert','ts_patron',$aValues);
		endif;
		
		if($bAdd):
			$sMessage = '<p class="greenBox">Le patron a bien été ajouté</p>';
		else:
			$sMessage = '<p class="redBox">Une erreur est survenue, veuillez réessayer.</p>';
		endif;
	endif;
	
	if(isset($_GET['delete'])):
		$bDelete = dataManager::Write('delete','ts_patron_link',null,array(array('iArticle','=',$_GET['delete']),array('iPatron','=',$_GET['id'])));
		if($bDelete):
			$sMessage = '<p class="greenBox">L\'article a bien été supprimé.</p>';
		else:
			$sMessage = '<p class="redBox">L\'article n\'a pas pu être supprimé. Veuillez réessayer.</p>';
		endif;
	endif;
	
	if(isset($_GET['delete-patron'])):
		header('Location: index.php?page_admin=patron');
		dataManager::Write('delete','ts_patron',null,array(array('ID','=',$_GET['id'])));
	endif;

?>

<h1>Patron</h1>
<ul class="list">
	<li><a href="index.php?page_admin=patron&option=form">Ajouter du patron</a></li>
	<li><a href="index.php?page_admin=patron">Gestion des patrons</a></li>
</ul>
<?php echo $sMessage; ?>
<?php if(isset($_GET['option']) && $_GET['option'] == 'form'): ?>
	<?php if(isset($_GET['id'])): ?>
		<?php $oPatron = dataManager::ReadOne('ts_patron',array(array('ID','=',$_GET['id']))); ?>
	<?php endif; ?>
	<form enctype="multipart/form-data" method="post" class="form-admin" action="">
		<?php echo getSelectLng($_SERVER['REQUEST_URI']) ?>
		<div>
		    <p>Nom du patron</p>
		    <input type="text" name="sNamei18n" value="<?php echo (!empty($oPatron)) ? $oPatron->sNamei18n : '' ?>" />
	    </div>
	    <?php if(!empty($aPatron)): ?>
	    	<div>
	    		<img src="../img/patron/image/<?php echo $oPatron->sImage ?> alt="<?php echo $oPatron->sNamei18n ?>" />
	    	</div>
	    <?php endif; ?>
	    <div>
		    <p>Image</p>
		    <input type="file" name="sImage" />
	    </div>
	    <?php if(!empty($aPatron)): ?>
	    	<div>
	    		<a href="../img/patron/pdf/<?php echo $oPatron->sPdfi18n ?>" target="_blank" title="<?php echo $oPatron->sPdfi18n ?>">Lien vers le pdf</a>
	    	</div>
	    <?php endif; ?>
	    <div>
		    <p>PDF</p>
		    <input type="file" name="sPdfi18n" />
	    </div>
	    <div>
	    	<input type="submit" name="submitPatron" value="Envoyer" />
	    </div>
	</form>
<?php else: ?>
	<?php if(isset($_GET['id'])): ?>
		<?php $oPatron = dataManager::ReadOne('ts_patron',array(array('ID','=',$_GET['id']))); ?>
		<h2><?php echo $oPatron->sName_FR?></h2>
		<img src="../img/patron/image/<?php echo $oPatron->sImage ?>" class="picture-article picture-patron" />
		<ul class='adminList'>
			<li><a href='index.php?page_admin=patron&delete-patron=true&id=<?php echo $_GET['id'] ?>' onclick='return(confirm(\"Etes-vous sûr de vouloir supprimer cet objet?\"))'>Supprimer le patron</a></li>
			<li><a href='index.php?page_admin=patron&option=form&modify=true&id=<?php echo $_GET['id'] ?>'>Modifier le patrons</a></li>
			<?php if(isset($_SESSION['iPatron'])): ?>
				<li><a href='index.php?page_admin=patron&id=<?php echo $_GET['id'] ?>&stop-attach=true'>Arrêter de lier les perles</a></li>
			<?php else: ?>
				<li><a href='index.php?page_admin=catalogue&ipatron=<?php echo $_GET['id'] ?>'>Lier les perles</a></li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
		<?php if(isset($_GET['iarticle'])): ?>
			<?php $oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$_GET['iarticle']))); ?>
			<h3>Attacher cet article au patron : <?php echo $oArticle->sName_FR ?></h3>
			<img src="../img/catalogue/<?php echo $oArticle->sImage ?>" class="picture-article" />
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
				<label>Quantité nécessaire pour le patron:</label>
				<input type="text" name="iQuantity" value="0" />
				<input type="submit" value="Valider" name="submitForm" />
			</form>
		<?php endif; ?>
		<h3>Liste des articles</h3>
		<ul id="listArticlePatron">
			<?php $aArticle = dataManager::Read('ts_patron_link',array(array('iPatron','=',$_GET['id'])),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','ts_patron_link.iArticle')))))?>	<?php $aArticle = dataManager::Read('ts_patron_link',array(array('iPatron','=',$_GET['id'])),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','ts_patron_link.iArticle')))))?>		<?php $aArticle = dataManager::Read('ts_patron_link',array(array('iPatron','=',$_GET['id'])),null,null,array(array('inner join','ts_catalogue',array(array('ts_catalogue.ID','=','ts_patron_link.iArticle')))))?>
			<?php foreach($aArticle as $oArticle): ?>
				<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>'><?php echo $oArticle->iQuantity.' '.$oArticle->sName_FR ?></a> - <a href="index.php?page_admin=patron&id=<?php echo $_GET['id'] ?>&delete=<?php echo $oArticle->ID ?>">X</a></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<?php $aPatron = dataManager::Read('ts_patron'); ?>	
		<?php foreach($aPatron as $oPatron): ?>
			<div style='float:left; margin:0 3px 0 0;'>
				<a href='index.php?page_admin=patron&id=<?php echo $oPatron->ID ?>'>
					<img src='../img/patron/image/<?php echo $oPatron->sImage ?>' class="picture-article" width=100' height='100' alt='' />
				</a>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
<div class="clear"> </div>
<?php endif; ?>