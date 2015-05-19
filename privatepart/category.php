<?php 
	if(isset($_POST['sendForm'])):
		if(isset($_POST['iCat'])):
			$sCat = (isset($_POST['catText'])) ? $_POST['catText'] : $_POST['catSelect'];
			$bAdd = dataManager::Write('update', 'ts_categorie', array(
				array('sCategoryi18n',$sCat),
				array('sSubCategoryi18n',$_POST['subcat'])
			),
			array(
				array('ID','=',$_POST['iCat'])
			));
		else:
			$bAdd = dataManager::Write('insert', 'ts_categorie', array(
				array('sCategoryi18n',$_POST['catText']),
				array('sSubCategoryi18n',$_POST['subcat'])
			));
		endif;
		if($bAdd):
			$sMessage = '<p class="greenBox">La catégorie a bien été ajoutée/modifiée</p>';
		else:
			$sMessage = '<p class="redBox">Une erreur s\'est déclarée.</p>';
		endif;
	endif;
	
	if(isset($_GET['option']) && $_GET['option'] == 'delete'):
		$bDelete = dataManager::Write('delete','ts_categorie',null,array(array('ID','=',$_GET['id'])));
		if($bDelete):
			$sMessage = '<p class="greenBox">La catégorie a bien été supprimée</p>';
		else:
			$sMessage = '<p class="redBox">Une erreur s\'est déclarée.</p>';
		endif;
	endif;
?>

<h1>Catalogue - Gestion des catégories</h1>

<?php echo (isset($sMessage)) ? $sMessage : ''; ?>
<h2>Ajouter/modifier une catégorie</h2>
<?php if(isset($_GET['modify'])): ?>
	<?php $oCategory = dataManager::ReadOne('ts_categorie',array(array('ID','=',$_GET['id']))); ?>
<?php endif; ?>
<form class="form-admin" action="" method="POST">
	<?php echo getSelectLng($_SERVER['REQUEST_URI']) ?>
	<div class="field">
		<label>Catégorie</label>
		<input type="text" name="catText" value="<?php echo (isset($oCategory)) ? $oCategory->sCategoryi18n : '' ?>" />
		<?php if(!isset($_GET['modify'])): ?>
			<strong>OU</strong>
			<?php $aSelectCategory = dataManager::Read('ts_categorie', null, array('sCategoryi18n', 'ASC'), null, null, array('DISTINCT','sCategoryi18n')); ?>
			<select name="catSelect">
				<?php foreach($aSelectCategory as $oSelectCategory): ?>
					<option value="<?php echo $oSelectCategory->sCategoryi18n ?>"><?php echo $oSelectCategory->sCategoryi18n ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	</div>
	<div class="field">
		<label>Sous-catégorie</label>
		<input type="text" name="subcat" value="<?php echo (isset($oCategory)) ? $oCategory->sSubCategoryi18n : '' ?>" />
	</div>
	<div class="field">
		<?php echo (isset($_GET['id'])) ? '<input type="hidden" name="iCat" value="'.$_GET['id'].'" />' : ''?>
		<input type="submit" value="Envoyer" name="sendForm" />
	</div>
</form>

<h2>Liste des catégories</h2>
<?php $aMenuCategory = dataManager::Read('ts_categorie', null, array('sCategory_FR', 'ASC')); ?>
<table>
	<thead>
		<tr>
			<th>Catégorie</th>
			<th>Sous-catégorie</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($aMenuCategory as $oMenuCategory): ?>
			<tr>
				<td><?php echo $oMenuCategory->sCategory_FR ?></td>
				<td><?php echo $oMenuCategory->sSubCategory_FR ?></td>
				<td>
					<a href="index.php?page_admin=category&option=delete&id=<?php echo $oMenuCategory->ID ?>">Supprimer</a>
					<a href="index.php?page_admin=category&modify=true&id=<?php echo $oMenuCategory->ID ?>">Editer</a>
				</td>
		<?php endforeach; ?>
	</tbody>
</table>