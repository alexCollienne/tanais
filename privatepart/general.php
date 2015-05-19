<?php
	if(isset($_POST['sendForm'])):
		if(isset($_POST['iCountry'])):
			$bAdd = dataManager::Write('update','ts_delivrery_cost',array(
				array('sCountry',$_POST['country']),
				array('iWeightMin',$_POST['weightMin']),
				array('iWeightMax',$_POST['weightMax']),
				array('iPrice',$_POST['price'])
			),array(
				array('ID','=',$_POST['iCountry'])
			));
		else:
			$bAdd = dataManager::Write('insert','ts_delivrery_cost',array(
				array('sCountry',$_POST['country']),
				array('iWeightMin',$_POST['weightMin']),
				array('iWeightMax',$_POST['weightMax']),
				array('iPrice',$_POST['price'])
			));
		endif;
		
		if($bAdd):
			$sMessage = '<p class="greenBox">La catégorie a bien été ajoutée/modifiée</p>';
		else:
			$sMessage = '<p class="redBox">Une erreur s\'est déclarée.</p>';
		endif;
	endif;
?>

<?php echo (isset($sMessage)) ? $sMessage : ''; ?>
<h1>Catalogue - Gestion générale</h1>

<h2>Ajouter/Modifier des frais de ports</h2>
<?php $oDelivrery = (isset($_GET['id'])) ? dataManager::ReadOne('ts_delivrery_cost',array(array('ID','=',$_GET['id']))) : ''?>
<form class="form-admin" action="" method="POST">
	<div class="field">
		<label>Pays</label>
		<select name="country">
			<option value="ALL">Tous</option>
			<?php $aCountry = dataManager::Read('ts_pays',null,array('sName_FR','ASC')); ?>
			<?php foreach($aCountry as $oCountry): ?>
				<option value="<?php echo $oCountry->sCode ?>"><?php echo $oCountry->sName_FR ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="field">
		<label>Poids minimum</label>
		<input type="text" name="weightMin"<?php echo (!empty($oDelivrery)) ? ' value="'.$oDelivrery->iWeightMin.'"' : '' ?> />
	</div>
	<div class="field">
		<label>Poids maximum</label>
		<input type="text" name="weightMax"<?php echo (!empty($oDelivrery)) ? ' value="'.$oDelivrery->iWeightMax.'"' : '' ?> />
	</div>
	<div class="field">
		<label>Prix</label>
		<input type="text" name="price"<?php echo (!empty($oDelivrery)) ? ' value="'.$oDelivrery->iPrice.'"' : '' ?> />
	</div>
	<div class="field">
		<?php echo (isset($_GET['id'])) ? '<input type="hidden" name="iCountry" value="'.$_GET['id'].'" />' : '' ?>
		<input type="submit" name="sendForm" value="Envoyer" />
	</div>
</form>

<h2>Liste des frais de ports</h2>
<?php $aDelivreryCost = dataManager::Read('ts_delivrery_cost', null, array('sCountry','DESC')); ?>
<table>
	<thead>
		<tr>
			<th>Pays</th>
			<th>Poids minimum</th>
			<th>Poids maximum</th>
			<th>Prix</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($aDelivreryCost as $iKey => $oDelivrery): ?>
			<tr<?php echo ($iKey % 2 == 0) ? ' class="even"' : ' class="odd"'; ?>>
				<td width="90" valign="top"><?php echo ($oDelivrery->sCountry == 'ALL') ? 'Tous' : getCountry($oDelivrery->sCountry);?></td>
				<td width="150"><?php echo $oDelivrery->iWeightMin; ?> gr.</td>
				<td width="150"><?php echo $oDelivrery->iWeightMax; ?> gr.</td>
				<td width="100"><?php echo $oDelivrery->iPrice; ?>&euro;</td>
				<td><a href="index.php?page_admin=general&action=edit&id=<?php echo $oDelivrery->ID ?>">Editer</a></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>