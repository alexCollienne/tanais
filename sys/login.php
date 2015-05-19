<?php 

include('session.php');
include('connect.php');
include('../include/function.php');
include('../include/class.php');

if(isset($_GET['last_page']) && $_GET['last_page'] == "modification"){
	
	$oSession = dataManager::ReadOne('ts_session',array(array('sMail','=',$_GET['mail']),array('sPassword','=',$_GET['password'])));
	if(!empty($oSession)){
		$_SESSION['id'] = $oArticle->ID;
		$_SESSION['mail'] = $oSession->sMail;
		$_SESSION['password'] = $oSession->sPassword;
		$_SESSION['prenom'] =  $oSession->sFirstname;
		$_SESSION['nom'] = $oSession->sName;
		$_SESSION['adresse'] =  $oSession->sStreet;
		$_SESSION['numero'] =  $oSession->sNumber;
		$_SESSION['boite'] =  $oSession->sBox;
		$_SESSION['cp'] = $oSession->sZip;
		$_SESSION['ville'] = $oSession->sCity;
		$_SESSION['pays'] = $oSession->sCountry;
		$_SESSION['tel'] = $oSession->sPhone;
		$_SESSION['genre'] = $oSession->sGender;
	}
	header ("LOCATION:../page-perso.php");
}else{
	$oSession = dataManager::ReadOne('ts_session',array(array('sMail','=',$_POST['mail']),array('sPassword','=',$_POST['password'])));
	if(!empty($oSession)){
		$_SESSION['id'] = $oSession->ID;
		$_SESSION['mail'] = $oSession->sMail;
		$_SESSION['password'] = $oSession->sPassword;
		$_SESSION['prenom'] =  $oSession->sFirstname;
		$_SESSION['nom'] = $oSession->sName;
		$_SESSION['adresse'] =  $oSession->sStreet;
		$_SESSION['numero'] =  $oSession->sNumber;
		$_SESSION['boite'] =  $oSession->sBox;
		$_SESSION['cp'] = $oSession->sZip;
		$_SESSION['ville'] = $oSession->sCity;
		$_SESSION['pays'] = $oSession->sCountry;
		$_SESSION['tel'] = $oSession->sPhone;
		$_SESSION['genre'] = $oSession->sGender;

		$bUp = dataManager::Write('update','ts_session',array(
			array('sLastConnexion',date('Y-m-d'))
		),array(
			array('sPassword','=',$_POST['password']),
			array('sMail','=',$_POST['mail']))
		);
		header ("LOCATION:".$_SESSION['url']);
	}else{
		if(strpos($_SESSION['url'],"login=false")){
			$url = $_SESSION['url'];
		}else{
			if(strpos($_SESSION['url'],"?")){
				$url = $_SESSION['url']."&login=false";
			}else{
				$url = $_SESSION['url']."?login=false";
			}
		}
		header("LOCATION:".$url);
	}
}
?>