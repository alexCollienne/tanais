<?php

//ADD ARTICLE
if(isset($_POST['sendForm'])):
	$aInsert = array();
	$_SESSION['lng'] = $_POST['lngChoice'];
	
	foreach($_POST as $sKey => $aValue):
		if($sKey != 'lngChoice' && $sKey != 'id' && $sKey != 'MAX_FILES_SIZE' && $sKey != 'sendForm'):
			$aInsert[] = array($sKey,$aValue);
		endif;
	endforeach;
	
	if(!empty($_FILES['sImage']['name'])):
		$aInsert[] = array('sImage',$_FILES['sImage']['name']);
	endif;
	
	if(!empty($_POST['id'])):
	
		if(isset($_GET['id']) && !empty($_GET['id'])):
			$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$_GET['id'])));
			$oArticle = $aArticle[0];
		endif;
		
		$bAdd = dataManager::Write('update','ts_catalogue',$aInsert,array(array('ID','=',$_POST['id'])));
		
		if($bAdd):
			$sMessage = '<p class="greenBox">L\'article a bien été mis à jour.</p>';
			if(!empty($_FILES['sImage']['tmp_name'])):
				if(file_exists("../../img/catalogue/".$_FILES['sImage']['name']) && $_FILES['sImage']['name'] == $oArticle->sImage):
					unlink("../../img/catalogue/".$_FILES['sImage']['name']);
				endif;
				move_uploaded_file($_FILES['sImage']['tmp_name'], "../img/catalogue/".$_FILES['sImage']['name']);
			endif;
			if(!empty($_FILES['sImageZoom']['tmp_name'])):
				if(file_exists("../../img/zoom/".$_FILES['sImageZoom']['name']) && $_FILES['sImageZoom']['name'] == $oArticle->sImage):
					unlink("../../img/zoom/".$_FILES['sImageZoom']['name']);
				endif;
				move_uploaded_file($_FILES['sImageZoom']['tmp_name'], "../img/zoom/".$_FILES['sImageZoom']['name']);
			endif;
		else:
			$sMessage = '<p class="redBox">L\'article n\'a pas été mis à jour.</p>';
		endif;
		
		if($_POST['iStock'] > $_POST['iStockMin']):
			$aMails = dataManager::Read('ts_rappel_stock',array(array('iArticle','=',$_POST['id'])));
			if(!empty($aMails)):					
				$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
				$entete .='MIME-Version: 1.0'."\n";
				$entete .= "Bcc: \n";
				$entete .='Content-Type:text/html;charset=UTF 8'."\n";
				$entete .='Content-Transfer-Encoding: 8bit'."\n"; 
				$sujet = "Réassort de l'article: ".$_POST['sNamei18n'];
				$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
				$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
				$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; min-height:250px;'>";
				$content .= "<p>Comme demand&eacute; nous vous envoyons ce mail pour vous pr&eacute;venir que l'article <a href='http://www.tanais.be/index.php?page=catalogue&id=".$_POST['id']."' style='color:#C7317F'>".$_POST['sNamei18n']."</a> est de nouveau en stock dans notre magasin ainsi que sur le site <a href='http://www.tanais.be' style='color:#C7317F'>http://www.tanais.be</a></p>";
				$content .= "<p>Cordialement,</p><p>Tana&iuml;s</p></div></div>";
		
				foreach($aMails as $oMail):
					$sTo = $oMail->sMail;					
					if(mail($sTo, $sujet, $content, $entete)):
						$sMessage .= '<p class="greenBox">Le mail de rappel des stocks a bien été envoyé à '.$sTo.'</p>';
						dataManager::Write('delete','ts_rappel_stock',null,array(array('ID','=',$oMail->ID)));
					else:
						$sMessage .= '<p class="redBox">Le mail de rappel des stocks n\'a pas été envoyé à '.$sTo.'</p>';
					endif;
				endforeach;
			endif;
		endif;
	else:
		$aInsert[] = array('sDate',date('Y-m-d H:i:s'));

		$bAdd = dataManager::Write('insert','ts_catalogue',$aInsert);

		if($bAdd):
			$sMessage = '<p class="greenBox">L\'article a bien été ajouté au catalogue.</p>';
			if(!empty($_FILES['sImage']['tmp_name'])):
				move_uploaded_file($_FILES['sImage']['tmp_name'], "../img/catalogue/".$_FILES['sImage']['name']);
			endif;
			if(!empty($_FILES['sImageZoom']['tmp_name'])):
				move_uploaded_file($_FILES['sImageZoom']['tmp_name'], "../img/zoom/".$_FILES['sImageZoom']['name']);
			endif;
		else:
			$sMessage = '<p class="redBox">Une erreur s\'est déclarée lors de l\'ajout de l\'article .</p>';
		endif;
	endif;
endif;

$sUrl = '';
if(isset($_GET['id']) && !empty($_GET['id'])):
	$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$_GET['id'])));
	$oArticle = $aArticle[0];
endif;

?>

<h1>Ajouter un objet</h1>
<?php echo (isset($sMessage)) ? $sMessage : ''; ?>
<form class="form-admin" action="" method="post" enctype="multipart/form-data">
	<?php if(isset($_GET['id']) && isset($_GET['modify'])): ?>
		<input type="hidden" value="<?php echo $_GET['id']; ?>" name="id" />
    <?php endif; ?>
	<?php echo getSelectLng($_SERVER['REQUEST_URI']) ?>
    <div>
    	<label>Nom</label>
      	<input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sNamei18n : '' ?>" name="sNamei18n" size="30" />
    </div>
    <div>
        <label>Cat&eacute;gorie</label>
		<?php $aMenuCategory = dataManager::Read('ts_categorie', null, array('sCategoryi18n', 'ASC'), null, null,array('DISTINCT','sCategoryi18n')); ?>
        <select name='iCategory'>
			<?php foreach($aMenuCategory as $oMenuCategory): ?>
    			<optgroup label="<?php echo $oMenuCategory->sCategoryi18n ?>"></optgroup>
				<?php $aSubCategory = dataManager::Read('ts_categorie', array(array('sCategoryi18n', '=', $oMenuCategory->sCategoryi18n)), array('sSubCategoryi18n', 'ASC')); ?>
				<?php foreach($aSubCategory as $oSubCategory): ?>
					<option value="<?php echo $oSubCategory->ID ?>"<?php echo (isset($oArticle) && $oArticle->iCategory == $oSubCategory->ID) ? ' selected="selected"' : '' ?>><?php echo $oMenuCategory->sCategoryi18n ?> - <?php echo $oSubCategory->sSubCategoryi18n ?></option>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</select>        
    </div>
    <div>
    	<label>R&eacute;f&eacute;rence</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sReference : '' ?>" name="sReference" size="30" />
    </div>
    <div>
    	<label>Code du magasin</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sShopCode : '' ?>" name="sShopCode" size="30" />
    </div>
    <div>
    	<label>Couleur</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sColori18n : '' ?>" name="sColori18n" size="30" />
    </div>
    <div>
    	<label>Couleur recherche</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sColorSearch : '' ?>" name="sColorSearch" size="30" />
    </div>
    <div>
    	<label>Forme</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sFormi18n : '' ?>" name="sFormi18n" size="30" />
    </div>
    <div>
    	<label>Stock actuel</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->iStock : '' ?>" name="iStock" size="30" />
    </div>
    <div>
    	<label>Stock minimum</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->iStockMin : '' ?>" name="iStockMin" size="30" />
    </div>
    <div>
    	<label>Poids</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->iWeight : '' ?>" name="iWeight" size="30" />
    </div>
    <div>
    	<label>Prix</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->iPrice : '' ?>" name="iPrice" size="30" />
    </div>
    <div>
    	<label>Fournisseur</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sProvider : '' ?>" name="sProvider" size="30" />
    </div>
	<div>
    	<label>Mati&egrave;re</label>
        <input type="text" value="<?php echo (isset($oArticle)) ? $oArticle->sMateriali18n : '' ?>" name="sMateriali18n" size="30" />
    </div>

    <div>
    	<label>Commentaire</label>
        <textarea name="sDescriptioni18n" rows="6" cols="28" class="tinymce"><?php echo (isset($oArticle)) ? $oArticle->sDescriptioni18n : '' ?></textarea>
    </div>
    <div>
    	<label>Tags</label>
        <textarea name="sTagi18n" rows="6" cols="28"><?php echo (isset($oArticle)) ? $oArticle->sTagi18n : '' ?></textarea>
    </div>
    <?php if(isset($_GET['modify'])): ?>
	    <div>
	    	<img src="../img/catalogue/<?php echo $oArticle->sImage ?>" width="50" class="imgForm" />
	    </div>
    <?php endif; ?>
    <div>
    	<label>Image</label>
        <input type="hidden" name="MAX_FILES_SIZE" value="700000" />
        <input type="file" size="30" name="sImage" />
    </div>
    <div>
    	<label><strong>Zoom</strong></label>
        <input type="hidden" name="MAX_FILES_SIZE" value="700000" />
        <input type="file" size="30" name="sImageZoom" />
    </div>
    <input type="submit" name="sendForm" value="Enregistrer" />
</form>