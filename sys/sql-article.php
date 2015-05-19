<?php

include('session.php');
include('connect.php');
include('../include/class.php');
include('../include/function.php');

if(strpos($_SESSION['url'], '&quantite=false')):
	$sUrl = str_replace('&quantite=false', '', $_SESSION['url']);
else:
	$sUrl = $_SESSION['url'];
endif;
header('LOCATION:'.$sUrl);

if(isset($_GET['option']) && $_GET['option'] == "patron_multiple"){

	$aArticle = dataManager::Read('ts_patron_link',array(array('iPatron','=',$_GET['ipatron'])),null,null,array(array('INNER JOIN','ts_catalogue',array(array('ts_catalogue.ID','=','iarticle')))));
	
	foreach($aArticle as $oArticle){
		$iQuantity = $oArticle->iQuantity;
		if($iQuantity < $oArticle->iStock || $oArticle->bStockSoldOut == 1){
			if(!isset($_SESSION['tmpCommande'])){
				$_SESSION['tmpCommandeIdArticle'] = array();
				$_SESSION['tmpCommandeQuantite'] = array();
				$_SESSION['tmpCommandePrixUnitaire'] = array();
				$_SESSION['tmpCommandePrixTotal'] = array();
				$_SESSION['tmpCommandePoidsTotal'] = array();
				$_SESSION['tmpCommande'] = array($_SESSION['tmpCommandeIdArticle'], $_SESSION['tmpCommandePrixUnitaire'], $_SESSION['tmpCommandePrixTotal'], $_SESSION['tmpCommandePoidsTotal']);
			}
		
			$iTotalWeight = $iQuantity * $oArticle->iWeight;
			
			$aPromo = dataManager::Read('ts_promo',array(array('iArticle','=',$oArticle->ID)));
			if(!empty($aPromo)){
				$iUnitPrice = $oArticle->iPrice - ($oArticle->iPrice * $aPromo[0]->iValue / 100);
				$iUnitPrice = round($iUnitPrice, 2);
				$iTotal = $iQuantity * $iUnitPrice;
			}else{
				$iUnitPrice = $oArticle->iPrice;
				$iTotal = $iQuantity * $oArticle->iPrice;
			}
			
			if(in_array($oArticle->ID, $_SESSION['tmpCommandeIdArticle'])){
				$key = array_search($oArticle->ID, $_SESSION['tmpCommandeIdArticle']);
				$_SESSION['tmpCommandeQuantite'][$key] += $iQuantity;
				$_SESSION['tmpCommandePrixUnitaire'][$key] = $iUnitPrice;
				$_SESSION['tmpCommandePrixTotal'][$key] += $iTotal;
				$_SESSION['tmpCommandePoidsTotal'][$key] += $iTotalWeight;		
			}else{
				$_SESSION['tmpCommandeIdArticle'][] = $oArticle->ID;
				$_SESSION['tmpCommandeQuantite'][] = $iQuantity;
				$_SESSION['tmpCommandePrixUnitaire'][] = $iUnitPrice;
				$_SESSION['tmpCommandePrixTotal'][] = $iTotal;
				$_SESSION['tmpCommandePoidsTotal'][] = $iTotalWeight;
			}
		}
	}
}elseif(isset($_GET['option']) && $_GET['option'] == 'caddieMaj'){

	foreach($_POST as $iKey => $iQuantity){
		$key = array_search($iKey, $_SESSION['tmpCommandeIdArticle']);
		if($_SESSION['tmpCommandeQuantite'][$key] != $iQuantity){
			
			$iQuantity = round(abs($iQuantity));
			
			$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$iKey)));
			$oArticle = $aArticle[0];
		
			if($iQuantity > $oArticle->iStock && $oArticle->bStockSoldOut == 0){
				header("LOCATION: ../caddie.php?quantite=false");
				die();
			}
			
			$iTotalWeight = $iQuantity * $oArticle->iWeight;
			
			$aPromo = dataManager::Read('ts_promo',array(array('iArticle','=',$oArticle->ID)));
			if(!empty($aPromo)){
				$iUnitPrice = $oArticle->iPrice - ($oArticle->iPrice * $aPromo[0]->iValue / 100);
				$iUnitPrice = round($iUnitPrice, 2);
				$iTotal = $iQuantity * $iUnitPrice;
			}else{
				$iUnitPrice = $oArticle->iPrice;
				$iTotal = $iQuantity * $oArticle->iPrice;
			}
			
			$_SESSION['tmpCommandeQuantite'][$key] = $iQuantity;
			$_SESSION['tmpCommandePrixUnitaire'][$key] = $iUnitPrice;
			$_SESSION['tmpCommandePrixTotal'][$key] = $iTotal;
			$_SESSION['tmpCommandePoidsTotal'][$key] = $iTotalWeight;

		}
	}

}else{
	if(is_numeric($_POST['quantite']) && $_POST['quantite'] > 0){
		
		$iId = (isset($_POST['id'])) ? $_POST['id'] : $_GET['id'];
		$iQuantity = round(abs($_POST['quantite']));
		$aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$iId)));
		$oArticle = $aArticle[0];

		if($iQuantity > $oArticle->iStock && $oArticle->bStockSoldOut == 0){
			header("LOCATION: ../catalogue.php?id=".$iId."&quantite=false");
			die();
		}
		
		if(!isset($_SESSION['tmpCommande'])){
			$_SESSION['tmpCommandeIdArticle'] = array();
			$_SESSION['tmpCommandeQuantite'] = array();
			$_SESSION['tmpCommandePrixUnitaire'] = array();
			$_SESSION['tmpCommandePrixTotal'] = array();
			$_SESSION['tmpCommandePoidsTotal'] = array();
			$_SESSION['tmpCommande'] = array($_SESSION['tmpCommandeIdArticle'], $_SESSION['tmpCommandePrixUnitaire'], $_SESSION['tmpCommandePrixTotal'], $_SESSION['tmpCommandePoidsTotal']);
		}

		$iTotalWeight = $iQuantity * $oArticle->iWeight;
		
		$aPromo = dataManager::Read('ts_promo',array(array('iArticle','=',$oArticle->ID)));
		if(!empty($aPromo)){
			$iUnitPrice = $oArticle->iPrice - ($oArticle->iPrice * $aPromo[0]->iValue / 100);
			$iUnitPrice = round($iUnitPrice, 2);
			$iTotal = $iQuantity * $iUnitPrice;
		}else{
			$iUnitPrice = $oArticle->iPrice;
			$iTotal = $iQuantity * $oArticle->iPrice;
		}
		
		if(in_array($oArticle->ID, $_SESSION['tmpCommandeIdArticle'])){
			$key = array_search($oArticle->ID, $_SESSION['tmpCommandeIdArticle']);
			$_SESSION['tmpCommandeQuantite'][$key] += $iQuantity;
			$_SESSION['tmpCommandePrixUnitaire'][$key] = $iUnitPrice;
			$_SESSION['tmpCommandePrixTotal'][$key] += $iTotal;
			$_SESSION['tmpCommandePoidsTotal'][$key] += $iTotalWeight;		
		}else{
			$_SESSION['tmpCommandeIdArticle'][] = $oArticle->ID;
			$_SESSION['tmpCommandeQuantite'][] = $iQuantity;
			$_SESSION['tmpCommandePrixUnitaire'][] = $iUnitPrice;
			$_SESSION['tmpCommandePrixTotal'][] = $iTotal;
			$_SESSION['tmpCommandePoidsTotal'][] = $iTotalWeight;
		}
	}
}

?>