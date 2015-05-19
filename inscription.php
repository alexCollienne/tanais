<?php 
include('sys/session.php');
include('include/header.php');


if(isset($_POST['sendProfile'])):
	$aMailing = dataManager::Read('ts_mailing',array(array('sMail','=',$_POST['mail_inscription'])));
	if(isset($_POST['newsletter']) && empty($aMailing)):
		$bAdd = dataManager::Write('insert','ts_mailing',array(array('sMail',$_POST['mail_inscription'])));
	endif;

	if(isset($_POST['id'])):
		$aTestClient = dataManager::Read('ts_session',array(array('sMail','=',$_POST['mail_inscription'])));
		if(!empty($aTestClient)):
			$sMessage = '<p class="errorMessage">Un compte est déjà lié à cette adresse mail. Veuillez-vous inscrire avec une autre adresse.</p>';
		else:
	
			dataManager::Write('update','ts_session',array(
				array('sFirstname',$_POST['prenom']),
				array('sName',$_POST['nom']),
				array('sMail',$_POST['mail_inscription']),
				array('sPassword',$_POST['mdp_inscription']),
				array('sStreet',$_POST['adresse']),
				array('sNumber',$_POST['numero']),
				array('sBox',$_POST['boite']),
				array('sZip',$_POST['cp']),
				array('sCity',$_POST['ville']),
				array('sCountry',$_POST['pays']),
				array('sPhone',$_POST['tel']),
				array('sGender',$_POST['genre'])
			),array(
				array('ID','=',$_POST['id'])
			));
		
			$_SESSION['mail'] = $_POST['mail_inscription'];
			$_SESSION['password'] = $_POST['mdp_inscription'];
			$_SESSION['prenom'] =  $_POST['prenom'];
			$_SESSION['nom'] = $_POST['nom'];
			$_SESSION['adresse'] =  $_POST['adresse'];
			$_SESSION['numero'] =  $_POST['numero'];
			$_SESSION['boite'] =  $_POST['boite'];
			$_SESSION['cp'] = $_POST['cp'];
			$_SESSION['ville'] = $_POST['ville'];
			$_SESSION['pays'] = $_POST['pays'];
			$_SESSION['tel'] = $_POST['tel'];
			$_SESSION['genre'] = $_POST['genre'];
		
			$sMessage = '<p class="confirmMessage">Votre profile a bien été mis à jour.</p>';
		endif;
	else:
		$aTestClient = dataManager::Read('ts_session',array(array('sMail','=',$_POST['mail_inscription'])));
		if(!empty($aTestClient)):
			$sMessage = '<p class="errorMessage">Un compte est déjà lié à cette adresse mail. Veuillez-vous inscrire avec une autre adresse.</p>';
		else:
			dataManager::Write('insert','ts_session',array(
				array('sFirstname',$_POST['prenom']),
				array('sName',$_POST['nom']),
				array('sMail',$_POST['mail_inscription']),
				array('sPassword',$_POST['mdp_inscription']),
				array('sStreet',$_POST['adresse']),
				array('sNumber',$_POST['numero']),
				array('sBox',$_POST['boite']),
				array('sZip',$_POST['cp']),
				array('sCity',$_POST['ville']),
				array('sCountry',$_POST['pays']),
				array('sPhone',$_POST['tel']),
				array('sGender',$_POST['genre']),
				array('sDate',date('Y-m-d')),
				array('sLastConnexion',date('Y-m-d'))
			));
			
			$aClient = dataManager::Read('ts_session',array(array('sMail','=',$_POST['mail_inscription'])));
			$oClient = $aClient[0];
			
			$_SESSION['id'] = $oClient->ID;
			$_SESSION['mail'] = $_POST['mail_inscription'];
			$_SESSION['password'] = $_POST['mdp_inscription'];
			$_SESSION['prenom'] =  $_POST['prenom'];
			$_SESSION['nom'] = $_POST['nom'];
			$_SESSION['adresse'] =  $_POST['adresse'];
			$_SESSION['numero'] =  $_POST['numero'];
			$_SESSION['boite'] =  $_POST['boite'];
			$_SESSION['cp'] = $_POST['cp'];
			$_SESSION['ville'] = $_POST['ville'];
			$_SESSION['pays'] = $_POST['pays'];
			$_SESSION['tel'] = $_POST['tel'];
			$_SESSION['genre'] = $_POST['genre'];
			
			//Reliquat
			$bAdd = dataManager::Write('insert','ts_reliquat',array(array('iClient',$oClient->ID)));
			if($bAdd):
				$sMessage = '<p class="confirmMessage">Nous avons bien reçu votre inscription. Vous pouvez dès maintenant vous connecter sur Tanaïs avec votre adresse email et votre mot de passe.</p>';
			else:
				$sMessage = '<p class="errorMessage">Une erreur est survenue lors de votre inscription. Veuillez réessayer.</p>';
			endif;
			
			$dest = $_POST['mail_inscription'];	
			$sujet = "Inscription";
			$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
			$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
			$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#999;'><p>Nous vous remercions de votre int&eacute;r&ecirc;t pour notre magasin en ligne et sommes heureux de vous compter parmi nos clients.</p> <p>Voici votre mot de passe: ".$_POST['mdp_inscription']."</p> <p>Nous vous souhaitons d'agr&eacute;ables visites.</p>";
			$content .= "<p>Cordialement,</p>";
			$content .= "<p>Service Client&egrave;le</p>";
			$content .= "<p><a href='mailto:serviceclient@tanais.be'>serviceclient@tanais.be</a></p></div>";
			
			$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
			$entete .='MIME-Version: 1.0'."\n";
			$entete .= "Bcc: \n";
			$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
			$entete .='Content-Transfer-Encoding: 8bit'."\n"; 
			
			$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:50px; padding-top:5px; padding-bottom:5px;'><a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
			mail($dest, $sujet, $content, $entete, "-finfo@tanais.be");
			
		endif;
	endif;
endif;
	
?>
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Inscription</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<?php 
	if(isset($sMessage)):
		echo $sMessage;	
	endif;
	if(isset($_SESSION['id'])):
		$aSession = dataManager::Read('ts_session',array(array('ID','=',$_SESSION['id'])));
		$oSession = $aSession[0];
		
		$aMail = dataManager::Read('ts_mailing',array(array('sMail','=',$oSession->sMail)));
	?>	
		<h1 style='font-size:24px; color:#C7317F'>Modification de vos donn&eacute;es personnelles</h1>
	<?php else: ?>
		<?php $sUrl = ''; ?>
		<h1 style='font-size:24px; color:#C7317F'>Inscription</h1>
	<?php endif; ?>
	<form method="POST" enctype="multipart/form-data" name="formInscription" onsubmit="return valid()">
		<table width="600">
		    <tr height='40'>
		    	<td><p>Titre</p></td>
		        <td>
		        	<select name="genre" style="margin-right:20px;">
		        		<option value='Mme.'<?php echo (isset($oSession) && $oSession->sGender == "Mme.") ? ' selected="selected"' : '' ?>>Mme.</option>
						<option value='Mlle.'<?php echo (isset($oSession) && $oSession->sGender == "Mlle.") ? ' selected="selected"' : '' ?>>Mlle.</option>
						<option value='Mr.'<?php echo (isset($oSession) && $oSession->sGender == "Mr.") ? ' selected="selected"' : '' ?>>Mr.</option>
					</select>
				</td>
		    </tr>
		    <tr height='40'>
		        <td width="211"><p>Pr&eacute;nom</p></td>
		        <td width="377"><input type="text" name="prenom" value="<?php  echo isset($oSession->sFirstname) ? $oSession->sFirstname : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Nom</p></td>
		        <td><input type="text" name="nom" value="<?php  echo isset($oSession->sName) ? $oSession->sName : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td style='margin-top:3px'><p>Adresse Email</p></td>
		        <td><input type="text" name="mail_inscription" value="<?php echo isset($oSession->sMail) ? $oSession->sMail : '' ?>" id="mail_inscription" style="float:left;" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td style='margin-top:3px'><p>Confirmez l'adresse Email</p></td>
		        <td><input type="text" name="confirm_mail_inscription" value="<?php echo isset($oSession->sMail) ? $oSession->sMail : '' ?>" id="confirm_mail_inscription" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Mot de passe</p></td>
		        <td><input type="password" name="mdp_inscription" value="<?php echo isset($oSession->sPassword) ? $oSession->sPassword : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Confirmez le mot de passe</p></td>
		        <td><input type="password" name="confirm_mdp_inscription" value="<?php echo isset($oSession->sPassword) ? $oSession->sPassword : '' ?>" size="40"/></td>
		    </tr>
		
		    <tr height='40'>
		        <td><p style="display:inline;">Adresse</p></td>
		        <td valign="middle"><input type="text" name="adresse" value="<?php echo isset($oSession->sStreet) ? $oSession->sStreet : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Numéro - Boite</p></td>
		        <td><input type="text" name="numero" value="<?php echo isset($oSession->sNumber) ? $oSession->sNumber : '' ?>" size="10" /> <input type="text" name="boite" value="<?php echo isset($oSession->sBox) ? $oSession->sBox : '' ?>" size="10" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Code postale - Ville</p></td>
		        <td><input type="text" name="cp" maxlength="6" value="<?php echo isset($oSession->sZip) ? $oSession->sZip : '' ?>" size="5" /> <input type="text" name="ville" value="<?php echo isset($oSession->sCity) ? $oSession->sCity : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>T&eacute;l&eacute;phone</p></td>
		        <td><input type="text" name="tel" value="<?php echo isset($oSession->sPhone) ? $oSession->sPhone : '' ?>" size="40" /></td>
		    </tr>
		    <tr height='40'>
		        <td><p>Pays</p></td>
		        <td>
		        	<select name="pays" lang="fr" style="width:165px;">
		                <?php
		                $aCountry = dataManager::Read('ts_pays',null,array('sNamei18n','ASC'));
		                foreach($aCountry as $oCountry):
		                ?>
							<option <?php echo (isset($oSession) && $oSession->sCountry == $oCountry->sCode) ? 'selected="selected"' : ''; ?> value="<?php echo $oCountry->sCode ?>"><?php echo $oCountry->sNamei18n ?></option>
						<?php endforeach; ?>
		            </select>
		    	</td>
		    </tr>
		    <tr height='40'>
		    	<td></td>
		    	<td>
		    		<?php if(empty($aMailing)): ?>
						<input type='checkbox' id="checkMailing" name='newsletter' value='check' style='border:none;' />
						<label for="checkMailing">S'inscrire &agrave; la newsletter</label>
					<?php endif; ?>
				</td>
		    </tr>
		    <tr height='60'>
		    	<td></td>
		    	<td>
					<?php if(isset($_SESSION['id'])): ?>
						<input type="hidden" name="id" value="<?php echo $_SESSION['id'] ?>" />
						<input type='submit' value='Modification' class='btn' name='sendProfile' style='margin-left:0px;' />
					<?php else: ?>
						<input type='submit' value='Inscription' class='btn' name='sendProfile' style='margin-left:0px;' />
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php include('include/footer.php'); ?>