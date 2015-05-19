<script>
	$(document).ready(function(){
		$('#sendType').change(function(){
			if($('#sendType').val() != 'test'){
				$('#testMail').attr('disabled','disabled');
				$('#testMail').addClass('disabled');
				$('#testMail').removeAttr('value');
			}else{
				$('#testMail').removeAttr('disabled');
				$('#testMail').removeClass('disabled');
			}
		});
	});
</script>
<?php

$aDate = dataManager::Read('ts_last_newsletter');
$aCount = dataManager::Read('ts_catalogue',array(array('sDate','>',$aDate[0]->sDate)),null,null,null,array('COUNT','ID'));
$sMessage = '';

if(isset($_POST['sendMail'])):
	
	$sContent = $_POST['text'];
	$bMail = false;
	$nMail = 0;
	$i = 0;
	
	$sSubject = $_POST['sujet'];
	$sContent  = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
	$sContent  .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";

	if($_POST['text'] != ""):
		$sContent  .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000; min-height:250px;'>";
		$sContent  .= "<p>".stripslashes($_POST['text'])."</p>";
	endif;
	
	$sContent  .= "</div>";
	
	$entete = 'From: Votre perlerie Tanais<info@tanais.be>'."\n";
	$entete .='MIME-Version: 1.0'."\n";
	$entete .='Content-Type:text/html;charset=UTF 8'."\n";
	$entete .='Content-Transfer-Encoding: 8bit'."\n";

	$sContent  .= "<table align='left' style='margin:0;'><tr>";
	
	//Boucle choissie si on a préféré d'afficher les nouveautés depuis la dernière newsletter
	if($_POST['check'] == "last"):
		$aArticle = dataManager::Read('ts_catalogue',array(array('sDate','>',$aDate[0]->sDate)));
	endif;

	//Boucle choissie si on a préféré de choissir nous-même le nombre désiré de nouveautés.
	if($_POST['check'] == "nbr"):
		$aArticle = dataManager::Read('ts_catalogue',null,array('sDate','DESC'),array(0,$_POST['nbr']));
	endif;
	
	if(isset($aArticle)):
		foreach($aArticle as $oArticle):
			//Afficher les perles dans le catalogue.
			$sContent .= "<td width='250' height='170' align='center' valign='top'>";
			$sContent .= "<img style='width:10px; height:90px;' src='http://www.tanais.be/img/site/shadow-left.jpg' class='image border' /><a href='http://www.tanais.be/index.php?page=catalogue&id=".$oArticle->ID."'><img style='height:90px; width:90px; border:none' src='http://www.tanais.be/img/catalogue/".$oArticle->sImage."' title='".$oArticle->sName_FR."' alt='".$oArticle->sName_FR."' /></a><img style='height:90px; width:10px;' src='http://www.tanais.be/img/site/shadow-right.jpg' class='image border' />";
			$sContent .= "<p style='margin:0;'><a href='http://www.tanais.be/index.php?page=catalogue&id=".$oArticle->ID."' style='color:#C7317F'>".$oArticle->sName_FR."</a></p>";
			$sContent .= "<p style='margin:0;'>".$oArticle->iPrice."&euro;</p>";
			$sContent .= "</td>";
			$i++;
			if($i == 3):
			  $sContent  .= "</tr><tr>";
			  $i=0;
			endif;
		endforeach;
	endif;

	$sContent  .= "</tr></table>";

	if($_POST['sendType'] == 'test'):
		$sContent .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin:0; padding:0; font-size:10px;'><a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
		$oMail = new Mail;
		$oMail->Content($sContent);
		$oMail->subject($sSubject);
		$oMail->To($_POST['testMail']);
		if($oMail->send()):
			$bMail = true;
		endif;
	else:
		$aMail = dataManager::Read('ts_mailing');
		foreach($aMail as $oMail):
			$sContent .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin:0; padding:0; font-size:10px;'><a href='http://www.tanais.be/erase-mail.php?id=".$oMail->ID."' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
			$oMail = new Mail;
			$oMail->Content($sContent);
			$oMail->subject($sSubject);
			$oMail->To($oMail->sMail);
			if($oMail->send()):
				$bMail = true;
				$nMail++;
			endif;
		endforeach;
	endif;
	
	if($bMail):
		$sMessage = '<p class="greenBox">La newsletter a bien &eacute;t&eacute; envoy&eacute;e';
		if($nMail != 0):
			$sMessage .= ' : '.$nMail.' ont &eacute;t&eacute; envoy&eacute;s';
			dataManager::Write('update','ts_last_newsletter',array(array('sDate',date('Y-m-d h:i:s'))));
		endif;
		$sMessage .= '</p>';
	else:
		$sMessage = '<p class="redBox">La newsletter n\'a pas &eacute;t&eacute; envoy&eacute;e :'.mysql_error().'</p>';
	endif;	
	
endif;
?>
<h1>Newsletter</h1>

<?php echo $sMessage; ?>

<div class='redBox'>
	<p>Date de la derni&egrave;re newsletter: <?php echo $aDate[0]->sDate ?><br />
	<?php echo $aCount[0]->count ?> articles ont &eacute;t&eacute; encod&eacute;s depuis la derni&egrave;re newsletter.</p>
</div>

<p>Remplisser le formulaire pour pouvoir envoyer une newsletter &agrave; tous les abonn&eacute;s &agrave; de la newsletter. Sujet et texte sont les options classiques. Juste en-dessous vous pouvez choisir entre 3 options qui concernent l'affichage des nouveaut&eacute;s dans la newsletter.</p>
<p>Le premier choix vous permettra d'afficher toutes les nouveaut&eacute;s encod&eacute;es depuis la derni&egrave;re newsletter envoy&eacute;e. Si vous choisissez cette options, faites bien attention &agrave; la date de la derni&egrave;re newsletter et du nombre d'article encod&eacute; depuis afin de ne pas
avoir un trop grand nombre d'article dans la newsletter. Vous pouvez voir le nombre d'article encod&eacute; depuis le dernier envoi ci-dessus.</p>
<p>Le deuxi&egrave;me choix vous permettra d'envoyer les X derni&egrave;res nouveaut&eacute;s encod&eacute;es, X &eacute;tant le nombre que vous encodez dans le champs texte.</p>
<p>Enfin, le dernier choix vous permettra de ne pas envoyer de nouveaut&eacute;s dans la newsletter.</p>

<form action="" class="form-admin" enctype="multipart/form-data" method="post">
	<div>
		<label>Type d'envoi</label>
		<select name="sendType" id="sendType">
			<option value="test">Envoi de la newsletter en test</option>
			<option value="newsletter">Envoi de la newsletter à tous les inscrits</option>
		</select>
	</div>
	<div>
		<label>Mail test</label>
		<input type="text" name="testMail" id="testMail" />
	</div>
	<div>
    	<label>Sujet</label>
        <input type="text" name="sujet" size="53" />
	</div>
	<div>
    	<label>Texte</label>
        <textarea name="text" rows="14" cols="50" class="tinymce"><?php echo (isset($sContent)) ? $sContent : '' ?></textarea> 
	</div>
	<div>       
    	<input type="radio" class="checkBox" name="check" value="last" /><span>Afficher toutes nouveaut&eacute;s depuis la derni&egrave;re newsletter</span>
	</div>
	<div>
    	<input type="radio" class="checkBox" name="check" value="nbr" /><span>Afficher </span><input type="text" name="nbr" id="inputNbr" /><span> nouveaut&eacute;s dans la newsletter</span>
	</div>
	<div>
    	<input type="radio" class="checkBox" name="check" value="none" checked="checked" /><span>N'afficher aucune nouveaut&eacute; dans la newsletter.</span>
	</div>
	<div>
        <input type="submit" value="Envoyer la newsletter" name="sendMail" />
	</div>    
</form>