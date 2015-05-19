<?php

//Appel du fichier de connexion Ã  la base de donnÃ©e.
require('sys/connect.php');
	
include('class.php');
include('function.php');
include 'wideimage/WideImage.php';

$_SESSION['role'] = 'user';

//Pose la langue fr par défaut
if(!isset($_COOKIE['tanais']['lng']) && !isset($_SESSION['lng'])):
	setcookie('tanais[lng]', 'FR', (time() + 50000), '/');
	$_SESSION['lng'] = 'FR';
elseif(!isset($_SESSION['lng'])):
	$_SESSION['lng'] = $_COOKIE['tanais']['lng'];
elseif(!isset($_COOKIE['tanais']['lng'])):
	$_COOKIE['tanais']['lng'] = $_SESSION['lng'];
endif;

//Enregistre l'url en SESSION
$_SESSION['url'] = (isset($_SESSION['url'])) ? $_SERVER['REQUEST_URI'] : '../index.php';
$_SESSION['pays'] = (isset($_SESSION['pays'])) ? $_SESSION['pays'] : 'ALL';

//ADD IP IN THE DB
$aIP = dataManager::Read('ts_compteur',array(array('IP_client','=',$_SERVER['REMOTE_ADDR']),array('sDate','=',date('Y-m-d'))));
if(empty($aIP)){
	dataManager::Write('insert','ts_compteur',array(array('IP_client',$_SERVER['REMOTE_ADDR']),array('sDate',date('Y-m-d'))));
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="robots" content="All" />
	<meta name="google-site-verification" content="V9f-aWzh9Yq-Tft9kLC_N32piCGe1vZF6yA56KfyG9o" />
	<meta name="description" content="perles Swarovski,perle de Bohème,kit..., pâte polymère: Fimo, Cernit, Sculpey...vous y trouverez les dernières tendance ainsi que des tuto en ligne" />
	<meta name="keywords" content="Vente en ligne de perle,vente ligne perle, vente ligne perles,perles,bohèmes,bohème,millefiori,moulin à perles,moulins à perles,kit bague perle,kit de bijoux,kit bagues perles,kit bagues en perles,bagues en kit,kits bijoux,bague en kit,kits bagues,kit bague perles,Pearle,bagues en perles,bijoux fantaisie,bague en perle,bijoux en perles,perles,perles de rocaille,perles de rocailles,bagues perles,les perles,bague perle,bague en perles,miyuli,miyuki,myuki,miuki,gauge,wire, wire and wire, bobine fil, métalla perle,bague,bagues,vente ,rles,perles rocailles,bague homme,perle du bac,modeles bagues, ,modèles bagues,modèles de bagues,forum histoire de perles,
	grossiste perles,schémas bagues,bracelets en perles,rocaille,bracelet perles,bagues perle,schema bague perle,bague cristal,bijoux de perles,bague de mariage,perles bac,perles paris,acheter ,rles,ma planete perle,schemas perles,schemas de bagues,modèles perles,bague fiancailles,perles à repasser,perles schema,fantaisie,kit bague,bague cartier,polymère,polimaire,polymaire polymer, pâte, pâtes, fimo, cernit, sculpey, perle, perles, bague en perle de cristal,bague bouton,fabriquer des bagues,bijoux bagues,bagues homme,collants fantaisie,animaux perle,pendentif en perles,pearle vision,1001 perles,schemas bague,perles tahiti,schéma perle,bague ,ançaille,grossiste en perles,perles modele,
	noel en perles,perles schéma,bagues anciennes,perles fr,creation de bagues,tissage de perles,perles swaro,fabrication bague,faire une bague en perle,perle bijoux,patrons de bagues,faire des animaux en perles,schémas gratuits bagues,perles rares,modèles de perles,modele bagues perles,livres perles,perles a didine,fontaine aux perles,jardin rocaille,la fontaine aux perles,perle d asie,schemas bijoux perles,perle ,arowski,perles par milliers,boule en perles,perle en folie,livre perle,faire une bague,perle verre,grossiste de perles,jardin de rocaille,shémas bagues,kit perles,acheter bague,
	schema de bijoux en perles,perles pour bagues,bagues cartier,image fantaisie,bracelets de perles,bijoux pandora,pandorasbijoux perles cristal,perles de cultures,boite à perles,bague marguerite,kit de bague,kits de bagues en perles,kits bagues en perles,kit bague , ,rles,kits de bagues,perles en kit,kit bijou,kits perles kit de bagues,aiguille à perler,perle fantaisie,facette,facettes,fimo,murano,perles de venise,kit bijoux, kits bijoux,perles en verre,perles swarovski,swarovski,perles de rocaille,rocailles,perles en bois,perle en bois,perles en os,perle en os,daim,daims,patron bagues,patrons bagues,patron bijoux,patrons bijoux,perle aimantée,perles aimantées,perles en corne,perle en corne,
	rocaille,perles en métal,fermoirs,lacets,cuir,colliers,boucles d'oreille,bracelets,loisirs créatifs,apprêts,Swarovski,SWAROVSKI,swarovski,perles fantaisies,e-commerce,e-commerce perlerie,vente perles,vente FIMO,vente swarovski,vente outils,création bijoux,cours vidéos,cours création bijoux,perles,Perles,liege,LIEGE,PERLES,perlerie,la perlerie,Coeur,Cube,Facettes,Fleurs,cabane à perles,charlotte,Gouttes,crystal,cristal,Grains de riz,Ronde,strass,Strass &agrave; coller, Strass pointe diamant,Swarovski,Verre de Boh&egrave;me,Verre de Boh&egrave;me artisanal,Verre indien,rocaille,perles de rocaille,bijoux,colliers,bagues,boucles d'oreille,loisirs cr&eacute;atifs,loisirs creatifs,pendentifs,cr&eacute;ation,creation, " />	
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
	<script type="text/javascript" src="javascript/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="javascript/pngFix.js"></script>
	<script type="text/javascript" src="javascript/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="javascript/pirobox.1_2.js"></script>
	<script type="text/javascript" language="javascript" src="javascript/javascript.js"></script>
	<script type="text/javascript"> 

		$(document).ready(function() {
			
			//PNG Fixe
			$(document).pngFix();
			
			$().piroBox({
				my_speed: 300, //animation speed
				bg_alpha: 0.5, //background opacity
				radius: 4, //caption rounded co	rner
				scrollImage : false, // true == image follows the page _|_ false == image remains in the same open position
								   // in some cases of very large images or long description could be useful.
				slideShow : 'true', // true == slideshow on, false == slideshow off
				slideSpeed : 3, //slideshow 
				pirobox_next : 'piro_next', // Nav buttons -> piro_next == inside piroBox , piro_next_out == outside piroBox
				pirobox_prev : 'piro_prev', // Nav buttons -> piro_prev == inside piroBox , piro_prev_out == outside piroBox
				close_all : '.piro_close' // add class .piro_overlay(with comma)if you want overlay click close piroBox

			});

			$('#login-box').submit(function(){
				if($("#loginMail").val() == '' || $("#loginPassword").val() == ''){
					alert("Veuillez remplire tous les champs");
					return false;
				}
			});
		});

	</script>
	<link rel="icon" type="image/x-icon" href="img/site/mini-logo.jpg"/>	