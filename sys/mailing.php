<?php

include('connect.php');

if(ereg("^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]{2,}[.][a-zA-Z]{2,4}$", $_POST['mail'])) {
	
	$aMail = dataManager::Read('ts_mailing',array(array('sMail','=',$_POST['mail'])));
	
	if(empty($aMail)){
		$bAdd = dataManager::Write('insert','ts_mailing', array(
			array('sMail',$_POST['mail'])
		));
		
		$dest = $_POST['mail'];
		$sujet = "Inscription à la newsletter";
		$entete ='From: Votre magasin Tanaïs<info@tanais.be>'."\n";
		$entete .='MIME-Version: 1.0'."\n";
		$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
		$entete .='Content-Transfer-Encoding: 8bit'."\n";

		
		$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
		$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";
		$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><div style='padding-left:200px; padding-top:20px; text-align:right; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#999;'>";

		$content .= "<p>Bonjour</p>";
		$content .= "<p>Vous &ecirc;tes bien inscrit &agrave; la mailing liste du magasin Tana&iuml;s. Vous recevrez ainsi toutes les nouveautés nous concernant ainsi que les derrniers articles mis en ligne.</p>";
		$content .= "<p>Cordialement,</p>";
		$content .= "<p>L'&eacute;quipe Tana&Iuml;s</p></div>";
		$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:50px; padding-top:5px; padding-bottom:5px;'><a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";

		
		mail($dest, $sujet, $content, $entete);
		header("LOCATION: ../index.php?true=mail_correct");
	}else{
		header("LOCATION: ../index.php?false=mail_exist");
	}
}else{
	header("LOCATION: ../index.php?false=mail_error");	
}
?>