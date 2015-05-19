<?php

function getCountry($sCode){
	$oCountry = dataManager::ReadOne('ts_pays',array(array('sCode','=',$sCode)));
	return $oCountry->sNamei18n;
}

function tagLng($sKey){
	$sLng = (isset($_COOKIE['tanais']['lng'])) ? $_COOKIE['tanais']['lng'] : 'fr';
	$xmlDoc = new DOMDocument();
	$xmlDoc->load('xml/translate.'.$sLng.'.xml');
	$aTrad = $xmlDoc->getElementsByTagName('translate');
	
	foreach($aTrad as $oValue){
		if($oValue->getAttribute('key') == $sKey){
			return $oValue->nodeValue;
		}
	}
}

function cutString($sString, $iInt, $sEndString = ''){
	$sNewString = chunk_split($sString, $iInt, $sEndString);
	$aNewString = explode('...', $sNewString);
	
	$iLenghtString = strlen($sString);
	$sEndString = ($iLenghtString > $iInt) ? $sEndString : '';
	return $aNewString[0].$sEndString;
}

function getCategory($iId){
	$aCategory = dataManager::Read('ts_categorie',array(array('ID','=',$iId)));
	return $aCategory[0];
}

function selectTrad($sVar){
	
	if(strpos($sVar,'i18n')){
		$sVar = explode('i18n',$sVar);
		if(isset($_SESSION['lng'])):
			$sName = $sVar[0].'_'.$_SESSION['lng'];
		else:
			$sName = $sVar[0].'_FR';
		endif;
	}else{
		$sName = $sVar;				
	}
	return $sName;
}

function getSelectLng($sUrl){
	if(isset($_GET['modify'])):
		if(isset($_GET['lng'])):
			$sUrl = str_replace('&lng='.$_GET['lng'], '', $sUrl);
		endif;
		$sSelect = '<select id="setLng">';
		if($_SESSION['lng'] == 'FR'){
			$sSelect .= '<option value="'.$sUrl.'&lng=fr" selected="selected">Français</option>';
			$sSelect .= '<option value="'.$sUrl.'&lng=en">Anglais</option>';
		}else{
			$sSelect .= '<option value="'.$sUrl.'&lng=fr">Français</option>';
			$sSelect .= '<option value="'.$sUrl.'&lng=en" selected="selected">Anglais</option>';		
		}
		$sSelect .= '</select>';
		$sSelect .= '<input type="hidden" value="'.$_SESSION['lng'].'" name="lngChoice">';
	else:
		$sSelect = '<select name="lngChoice">
						<option value="FR">Français</option>
						<option value="EN">Anglais</option>
					</select>';
	endif;

	return $sSelect;
}

function formatDate($sDate, $sType = ''){
	$aDateTemp = explode('-',$sDate);
	if(strrpos($aDateTemp[2], ':')){
		$aHourTemp = explode(':', $aDateTemp[2]);
		$aDayTemp = explode(' ',$aHourTemp[0]);
		$iTimestamp = mktime($aDayTemp[1], $aHourTemp[1], $aHourTemp[2], $aDateTemp[1], $aDayTemp[0], $aDateTemp[0]);
	}else{
		$iTimestamp = mktime(0, 0, 0, $aDateTemp[1], $aDateTemp[2], $aDateTemp[0]);
	}
	if($sType = ''){
		$sType = 'd/m/Y';
	}
	return date('d/m/Y', $iTimestamp);
}
?>