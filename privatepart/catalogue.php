<?php
$sMessage = '';

//ACTIVE OR UNACTIVE THE ARTICLE
if(isset($_GET['active'])):
	$iActive = ($_GET['active'] =='true') ? 1 : 0;
	$sActive = ($_GET['active'] =='true') ? 'activé' : 'désactivé';
	$bUp = dataManager::Write('update','ts_catalogue',array(array('bActive',$iActive)),array(array('ID','=',$_GET['id'])));
	if($bUp):
		$sMessage = '<p class="greenBox">L\'article a bien été '.$sActive.'</p>';
	else:
		$sMessage = '<p class="redBox">Un problème a été rencontré. L\'article n\'a pas été '.$sActive.'</p>';
	endif;
endif;

//DELETE
if(isset($_GET['delete'])):
	header('Location:index.php?page_admin=catalogue');
	dataManager::Write('delete','ts_catalogue',null,array(array('ID','=',$_GET['id'])));
endif;

//SOLD OUT OF STOCK
if(isset($_GET['stock'])):
	$iActive = ($_GET['stock'] =='true') ? 1 : 0;
	$bUp = dataManager::Write('update','ts_catalogue',array(array('bStockSoldOut',$iActive)),array(array('ID','=',$_GET['id'])));
	if($bUp):
		$sMessage = '<p class="greenBox">La vente hors stock de cet article a bien été activée</p>';
	else:
		$sMessage = '<p class="redBox">Un problème a été rencontré. Veuillez recommencer.</p>';
	endif;
endif;

//SET PROMOTION
if(isset($_GET['promo'])):
	$oPromo = dataManager::ReadOne('ts_promo',array(array('iArticle','=',$_GET['id'])));
	if(empty($oPromo)):
		$bAdd = dataManager::Write('insert', 'ts_promo',array(
				array('iArticle',$_GET['id']),
				array('iValue',$_POST['ivalue']),
				array('sDate',date('Y-m-d')),
				array('sType',$_POST['type'])
			));
	else:
		$bAdd = dataManager::Write('update', 'ts_promo',array(
				array('iArticle',$_GET['id']),
				array('iValue',$_POST['ivalue']),
				array('sDate',date('Y-m-d')),
				array('sType',$_POST['type'])
			),array(array('iArticle',$_GET['id'])));
	endif;
	
	if($bAdd):
		$sMessage = '<p class="greenBox">La promotion a bien été ajoutée</p>';
	else:
		$sMessage = '<p class="redBox">Un problème a été rencontré. La promotion n\'a pas été ajoutée</p>';
	endif;
endif;

//PUT IN TOP OF THE LIST
if(isset($_GET['new'])):
	$bMaj = dataManager::Write('update','ts_catalogue',array(array('sDate',date('Y-m-d H:i:s'))),array(array('ID','=',$_GET['id'])));
	if($bMaj):
		$sMessage = '<p class="greenBox">L\'article a bien été mis à jour</p>';
	else:
		$sMessage = '<p class="redBox">Un problème a été rencontré. Veuillez réessayer.</p>';
	endif;
endif;

?>
<?php echo $sMessage; ?>
<?php $iCategory = (isset($_GET['cat'])) ? $_GET['cat'] : ''; ?>
<?php $iSubCategory = (isset($_GET['subcat'])) ? $_GET['subcat'] : ''; ?>
<h1>Catalogue</h1>
<?php if(isset($_SESSION['iPatron'])): ?>
	<?php $oPatron = dataManager::ReadOne('ts_patron',array(array('ID','=',$_SESSION['iPatron']))); ?>
	<p class="blueBox">Vous pouvez attacher des articles au patron "<a href="index.php?page_admin=patron&id=<?php echo $oPatron->ID ?>"><?php echo $oPatron->sName_FR ?></a>" | <a href="<?php echo $_SESSION['url'] ?>&stop-attach=true">Arrêter d'attacher des perles à ce patron</a></p>
<?php endif; ?>
<ul class="list">
	<li><a href="index.php?page_admin=champs">D&eacute;tecter les champs vides</a></li>
	<li><a href="index.php?page_admin=verif_stock">Retrouver les perles hors-stock</a></li>
	<li><a href="?page_admin=stock">Listes d'attentes des stocks</a></li>
	<li><a href="?page_admin=category">Gestion des catégories</a></li>
	<li><a href="pdf.php" target="_blank">Générer le catalogue PDF</a></li>
</ul>
<div class="clear"></div>
<script>
	$(document).ready(function() {
		$('#iCategory').change(function(){
			parent.location.href = $('#iCategory').val();
		});

		$('.showBox').click(function(){
			$(this).next('.hideBox').slideToggle();
		});
	});
</script>
<h3 class="showBox">Filtre</h3>
<div id="filtreBox" class="hideBox">
	<form class="form-admin">       
		<label>Les catégories</label>
		<?php $aListCategory = array(); ?>
		<?php $aMenuCategory = dataManager::Read('ts_categorie', null, array('sCategoryi18n', 'ASC')); ?>
		<select name="iCategory" id="iCategory">
			<?php foreach($aMenuCategory as $oMenuCategory): ?>
				<?php if(!in_array($oMenuCategory->sCategoryi18n, $aListCategory)): ?>
					<option value="index.php?page_admin=catalogue&cat=<?php echo $oMenuCategory->ID ?>"> - <?php echo $oMenuCategory->sCategory_FR ?> - </option>
					<?php $aSubCategory = dataManager::Read('ts_categorie', array(array('sCategory_FR', '=', $oMenuCategory->sCategory_FR)), array('sSubCategory_FR', 'ASC')); ?>
					<?php foreach($aSubCategory as $oSubCategory): ?>
						<option value="index.php?page_admin=catalogue&subcat=<?php echo $oSubCategory->ID ?>"<?php echo (isset($oArticle) && $oArticle->iCategory == $oSubCategory->ID) ? ' selected="selected"' : '' ?>><?php echo $oMenuCategory->sCategory_FR ?> - <?php echo $oSubCategory->sSubCategory_FR ?></option>
					<?php endforeach; ?>
					<?php $aListCategory[] = $oMenuCategory->sCategoryi18n; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>  
	</form>
</div>

<form method="POST" action="index.php?page_admin=catalogue" class='form-admin' enctype="multipart/form-data" name="search">
	<input type="text" value="Rechercher un article" name="recherche" size="50" onClick="this.value=''" />
	<input type="submit" value="OK" style="margin:0" />
</form>

<?php 
if(isset($_GET['id'])):
	$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$_GET['id'])));
	$oArticle = $aArticle[0];
?>
	<div id='pearl-box'>
		<div class='catalogue-image' style='margin-right:20px;'>
			<img src='../img/catalogue/<?php echo $oArticle->sImage ?>' style='border: 1px solid ; height: 180px; width: 180px; background-color: white;' alt='<?php echo $oArticle->sName_FR ?>' />
			<h3>Option:</h3>
			<ul class='adminList'>
				<li><a href='index.php?page_admin=add-object&id=<?php echo $oArticle->ID ?>&modify=true' title='Modifier'>Modifier</a></li>
				<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&new=true' title='Modifier'>Mettre en nouveaut&eacute;</a></li>
				<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&delete=true' onclick='return(confirm("Etes-vous sur de vouloir supprimer cet objet?"))'>Supprimer</a></li>	
				<?php if($oArticle->bActive == 0): ?>
					<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&active=true'>Activer</a></li>
				<?php else: ?>
					<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&active=false'>D&eacute;sactiver</a></li>
				<?php endif; ?>		
				<?php if($oArticle->bStockSoldOut == 0): ?>
					<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&stock=true' title='vente hors stock'>Vendre hors stock</a></li>
				<?php else: ?>
					<li><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&stock=false' title='vente à la limite des stocks'>Ne pas vendre hors stock</a></li>
				<?php endif; ?>
				<?php if(isset($_SESSION['iPatron'])): ?>
					<li><a href="index.php?page_admin=patron&id=<?php echo $_SESSION['iPatron']; ?>&iarticle=<?php echo $oArticle->ID ?>">Attacher cet article au patron</a></li>
				<?php endif; ?>
				<li><a href='index.php?page_admin=add-object&id=<?php echo $oArticle->ID ?>' title='Dupliquer'>Dupliquer</a></li>
			</ul>
			<div class="clear"></div>
			<?php $aPromo = dataManager::Read('ts_promo',array(array('iArticle','=',$oArticle->ID))); ?>
			<?php if(!empty($aPromo)): ?>
				<?php $oPromo = $aPromo[0]; ?>
				<?php $sPromo = ($oPromo->type == 'promo') ? ' selected="selected"' : ''; ?>
				<?php $sSolde = ($oPromo->type == 'solde') ? ' selected="selected"' : ''; ?>
				<form method='post' class='form-admin' action='sys/add-promo.php?id=<?php echo $oArticle->ID ?>' enctype='application/x-www-form-urlencoded'>
					<p style='text-align:center; margin:0 0 15px'>Promotions/Soldes</p>
					<input type='text' name='valeur' value='<?php echo $oPromo->iValue ?>' /> % <br />
					<select name='type' style='width:100px'>
						<option value='promo'<?php echo $sPromo ?>>Promotion</option>
						<option value='solde'<?php echo $sSolde ?>>Solde</option>
					</select>
					<input type='submit' value='Encoder' />
				</form>
			<?php else: ?>
				<form method='post' class='form-admin' action='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>&promo=true' enctype='application/x-www-form-urlencoded'>
					<p style='text-align:center; margin:0 0 15px'>Promotions/Soldes</p>
					<input type='text' name='valeur' size='3' /> %<br />
					<select name='type' style='width:100px'>
						<option value='promo'>Promotion</option>
						<option value='solde'>Solde</option>
					</select>
					<input type='submit' value='Encoder' />
				</form>
			<?php endif; ?>
		</div>
		<?php $oCategory = getCategory($oArticle->iCategory); ?>
		<table width="850">
			<tr class="odd">
				<td>Nom</td> 
				<td><?php echo $oArticle->sName_FR ?></td>
			</tr>
			<tr class="even">
				<td>Cat&eacute;gorie</td> 
				<td><?php echo $oCategory->sCategory_FR ?></td>
			</tr>
			<tr class="odd">
				<td>Sous-cat&eacute;gorie</td> 
				<td><?php echo $oCategory->sSubCategory_FR ?></td>
			</tr>
			<tr class="even">
				<td>Description</td> 
				<td><?php echo stripslashes($oArticle->sDescription_FR) ?></td>
			</tr>
			<tr class="odd">
				<td>Couleur</td> 
				<td><?php echo $oArticle->sColor_FR ?></td>
			</tr>
			<tr class="even">
				<td>Prix</td> 
				<td><?php echo $oArticle->iPrice ?> &euro;</td>
			</tr>
			<tr class="odd">
				<td>Poids</td> 
				<td><?php echo $oArticle->iWeight ?> gr.</td>
			</tr>
			<tr class="even">
				<td>R&eacute;f&eacute;rence</td> 
				<td><?php echo $oArticle->sReference ?></td>
			</tr>
			<tr class="odd">
				<td>Quantit&eacute;</td> 
				<td><?php echo $oArticle->iStock ?> pièces</td>
			</tr>
			<tr class="even">
				<td>Stock minimum</td> 
				<td><?php echo $oArticle->iStockMin ?> pièces</td>
			</tr>
			<tr class="odd">
				<td>Forme</td> 
				<td><?php echo $oArticle->sForm_FR ?></td>
			</tr>
			<tr class="even">
				<td>Tag</td> 
				<td><?php echo stripslashes($oArticle->sTag_FR) ?> </td>
			</tr>
			<tr class="odd">
				<td>Fournisseur</td> 
				<td><?php echo $oArticle->sProvider ?></td>
			</tr>
			<tr class="even">
				<td>Mati&egrave;re</td> 
				<td><?php echo $oArticle->sMaterial_FR ?></td>
			</tr>
			<tr class="odd">
				<td>Code du magasin</td> 
				<td><?php echo $oArticle->sShopCode ?></td>
			</tr>
		</table>
	</div>
<?php else: ?>
	<?php if(isset($_POST['recherche'])): ?>
		<?php $sWord = $_POST['recherche']; ?>
		<?php $aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1),array('sNamei18n','LIKE','%'.$sWord.'%'),array('sTagi18n','LIKE','%'.$sWord.'%','OR'),array('sDescriptioni18n','LIKE','%'.$sWord.'%','OR')),null,array(0,40)); ?>
		<?php if(empty($aArticle) || empty($sWord)): ?>
			<p id='search-text'>Aucune recherche trouv&eacute;e pour le mot <span style='font-weight:900'><?php echo $sWord ?></span></p>
		<?php else: ?>
			<p id='search-text'>Recherche(s) trouv&eacute;e(s) pour le mot <span style='font-weight:900'><?php echo $sWord ?></span></p>
		<?php endif; ?>
	<?php elseif(!empty($iSubCategory)):
		$aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1),array('ICategory','=',$iSubCategory)));
	elseif(!empty($iCategory)):
		$aIdCat = array(array('bActive','=',1));
		$aCategoryName = dataManager::Read('ts_categorie',array(array('ID','=',$iCategory)));
		$aCategory = dataManager::Read('ts_categorie',array(array('sCategoryi18n','=',$aCategoryName[0]->sCategoryi18n)));
		foreach($aCategory as $iKey => $oCategory):
			if($iKey == 0):
				$aIdCat[1] = array('iCategory','=',$oCategory->ID);
			else:
				$aIdCat[] = array('iCategory','=',$oCategory->ID,'OR');
			endif;
		endforeach;
		$aArticle = dataManager::Read('ts_catalogue',$aIdCat);
 	else: ?>
		<?php $aArticle = dataManager::Read('ts_catalogue',null,array('sDate','DESC'),array(0,72)); ?>
	<?php endif; ?>
	
	<?php foreach ($aArticle as $oArticle): ?>
		<div class='catalogue-image'>
			<a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>' title="<?php echo $oArticle->sName_FR ?>">
				<img src='../img/catalogue/<?php echo $oArticle->sImage ?>' />
			</a>
			<?php if(isset($_SESSION['iPatron'])): ?>
				<a href="index.php?page_admin=patron&id=<?php echo $_SESSION['iPatron']; ?>&iarticle=<?php echo $oArticle->ID ?>" class="attachPatron">Attacher au patron</a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<div class="clear"> </div>