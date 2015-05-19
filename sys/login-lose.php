<?php
include('connect.php');
include('../include/function.php');
include('../include/class.php');
header('LOCATION: ../index.php');

$oClient = dataManager::ReadOne('ts_session',array(array('sMail','=',$_POST['mail'])));

$dest = $_POST['mail'];	
$sujet = "Rappel: mot de passe";
$content = "<div align='center' style='background-color:#C7317F; margin-top:20px;'>";
$content .= "<div style='background-color:#FFF; width:650px;'><a href='http://www.tanais.be' style='width:366px; height:145px; margin:auto; display:block;'><img src='http://www.tanais.be/img/site/header-logo.jpg' style='border:none' /></a>";

if(!empry($oClient->sPassword)){
	$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><p style='padding-left:200px; padding-top:20px; text-align:left; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000;'>Votre mot de passe est le suivant: ".$oClient->sPassword."</p>";
}else{
	$content .= "<div style='float:left'><img src='http://www.tanais.be/img/site/carre-left.jpg' /></div><p style='padding-left:200px; padding-top:20px; text-align:left; padding-right:20px; font-size:12px; font-family:Verdana, Geneva, sans-serif; color:#000;'>Votre adresse email n'est pas ou n'est plus dans notre base de donnée. Veuillez vous inscrire à notre site pour pouvoir y faire des achats.</p>";
}

$entete = 'From: Votre magasin Tanais<info@tanais.be>'."\n";
$entete .='MIME-Version: 1.0'."\n";
$entete .= "Bcc: \n";
$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
$entete .='Content-Transfer-Encoding: 8bit'."\n"; 

$content .= "<p style='background-color:#CCC;width:650px;text-align:center; clear:both; margin-top:50px; padding-top:5px; padding-bottom:5px;'><a href='http://www.tanais.be/index.php?page=erase' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Vous d&eacute;sinscrire &agrave; la newsletter</a> - <a href='http://www.tanais.be' style='color:#000;font-family:Verdana, Geneva, sans-serif;font-size:10px;'>Acc&eacute;s au site Tana&iuml;s</a></p></div></div>";
	
mail($dest, $sujet, $content, $entete, "-finfo@tanais.be");


?>