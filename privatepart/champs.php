<?php

if(isset($_POST['column']) && $_POST['column'] != 'all'):
	$aArticle = dataManager::Read('ts_catalogue',array(array($_POST['column'],'=','')));
else:
	$aArticle = dataManager::Read('ts_catalogue',array(
		array('sName_FR','=',''),
		array('sName_EN','=','','OR'),
		array('sDescription_FR','=','','OR'),
		array('sDescription_EN','=','','OR'),
		array('sImage','=','','OR'),
		array('sColorSearch','=','','OR'),
		array('sColor_FR','=','','OR'),
		array('sColor_FR','=','','OR'),
		array('iPrice','=','','OR'),
		array('iWeight','=','','OR'),
		array('sReference','=','','OR'),
		array('iStock','=','','OR'),
		array('iStockMin','=','','OR'),
		array('sForm_FR','=','','OR'),
		array('sForm_EN','=','','OR'),
		array('sMaterial_FR','=','','OR'),
		array('sMaterial_EN','=','','OR'),
		array('sTag_FR','=','','OR'),
		array('sTag_EN','=','','OR'),
		array('sShopCode','=','','OR'),
		array('sProvider','=','','OR')
	));
endif;
?>
<h1>Article ayant des champs vides (<?php echo 2;?>)</h1>
<?php 
$aColumn = array('sName_FR'=>'Nom Fran�ais',
		'sName_EN'=>'Nom anglais',
		'sDescription_FR'=>'Description fran�aise',
		'sDescription_EN'=>'Description anglaise',
		'sImage'=>'image',
		'sColorSearch'=>'couleur de recherche',
		'sColor_FR'=>'couleur fran�aise',
		'sColor_FR'=>'couleur anglaise',
		'iPrice'=>'prix',
		'iWeight'=>'poids',
		'sReference'=>'r�f�rence',
		'iStock'=>'stock',
		'iStockMin'=>'stock minimum',
		'sForm_FR'=>'forme fran�aise',
		'sForm_EN'=>'forme anglaise',
		'sMaterial_FR'=>'mati�re fran�aise',
		'sMaterial_EN'=>'mati�re anglaise',
		'sTag_FR'=>'tag fran�ais',
		'sTag_EN'=>'tag anglais',
		'sShopCode'=>'code magasin',
		'sProvider'=>'fournisseur');
	?>
<form method="post" action="index.php?page_admin=champs" enctype="application/x-www-form-urlencoded">
    <select name="column">
    	<option value="all">Tous</option>
    	<?php foreach($aColumn as $sKey => $sColumn): ?>
    		<option value="<?php echo $sKey ?>"><?php echo $sColumn ?></option>
    	<?php endforeach; ?>
    </select>
    <input type="submit" value="rechercher">
</form>
<ul id="listReliquat">
<?php foreach($aArticle as $oArticle): ?>
	<li><?php echo $oArticle->sName_FR ?> - <a href='index.php?page_admin=add-object&id=<?php echo $oArticle->ID ?>' title='Modifier'>Modifier</a></li>
<?php endforeach; ?>
</ul>