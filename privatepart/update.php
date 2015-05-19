<?php
	include('../sys/connect.php');
	include('../include/function.php');
	include('../include/class.php');
	
	//DELETE => ts_commande_tmp_article
	
	/*
	$sql = 'SELECT id, new_id FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_favoris SET id_article = "'.$rowId[1].'" WHERE id_article = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_video';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_video_comment SET id_video = "'.$rowId[1].'" WHERE id_video = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	
	//NEXT => Edit all the names of colum. EX.: id_article -> iarticle / utilisation -> sdescription_fr
	
	
	$sql = 'SELECT id, poids FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_catalogue SET new_poids = "'.$rowId[1].'" WHERE id = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT * FROM ts_categorie';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_catalogue SET iCategory = "'.$rowId[0].'" WHERE categorie = "'.$rowId[1].'" AND sous_categorie = "'.$rowId[2].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//NEXT => Delete the columns 'categorie' and 'sous_categorie'
	
	//UPDATE => id_commande
	
	$sql = 'SELECT id, new_id FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_commande_article SET id_commande = "'.$rowId[1].'" WHERE id_commande = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_bon_achat_historique SET id_commande = "'.$rowId[1].'" WHERE id_commande = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_mail SET id_commande = "'.$rowId[1].'" WHERE id_commande = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//END UPDATE
	
	//NEXT => DELETE ID in ts_commande et EDIT new_id -> id
	
	//UPDATE ID SESSION
	
	$sql = 'SELECT id, new_id FROM ts_session';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_bon_achat_historique SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_session';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_commande SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//DELETE => id_client in ts_commande_article_confirm
	
	$sql = 'SELECT id, new_id FROM ts_session';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_creation SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_session';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_favoris SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, new_id FROM ts_session';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_mail SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//REFRESH UNTIL THE END OF EDIT
	
	$sql = 'SELECT a.id_client, b.new_id FROM ts_reliquat a, ts_session b WHERE a.id_client = b.id';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		if(!is_int($rowId[0])):
			$up = 'UPDATE ts_reliquat SET id_client = "'.$rowId[1].'" WHERE id_client = "'.$rowId[0].'"';
			mysql_query($up) or die(mysql_error());
		endif;
	}
	
	
	$sql = 'SELECT id, new_id FROM ts_patron';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_patron_link SET id_patron = "'.$rowId[1].'" WHERE id_patron = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT sDate, ID, date_save FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$sDate = $rowId[0];
		if($sDate == '0000-00-00 00:00:00'):
			$sDateSave = $rowId[2];
			$sDateSave = substr_replace($sDateSave, ' ', 10, 1);
			$sDateSave = substr_replace($sDateSave, ':', 13, 0);
			$sDateSave = substr_replace($sDateSave, ':', 16, 0);
		else:
			$sDateSave = $sDate;
		endif;
		$up = 'UPDATE ts_commande SET sDate_var = "'.$sDateSave.'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());

	}

	$sql = 'SELECT date, ID FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_commande SET date_save = "'.$rowId[0].'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//WARNING => DO LIKE THAT FOT EVERY FUTUR BOOLEAN COLUMN
	
	$sql = 'SELECT mail, ID FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$bMail = ($rowId[0] == 'send') ? 1 : 0;

		$up = 'UPDATE ts_commande SET mail = "'.$bMail.'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT print, ID FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$bPrint = ($rowId[0] == 'print') ? 1 : 0;

		$up = 'UPDATE ts_commande SET print = "'.$bPrint.'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT pays, ID FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){

		$up = 'UPDATE ts_commande SET sdelivrery_country = "'.$rowId[0].'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//DELETE => 'pays'
	//DELETE => 'prix_unitaire'
	
	
	$sql = 'SELECT promo, id FROM ts_commande_article';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$bPromo = ($rowId[0] == 'true') ? 1 : 0;

		$up = 'UPDATE ts_commande_article SET promo = "'.$bPromo.'" WHERE id = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//UPGRADE THE BACTIVE ROW
	$sql = 'SELECT id, bactive FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$bActive = ($rowId[1] == 1) ? 0 : 1;

		$up = 'UPDATE ts_catalogue SET bactive = "'.$bActive.'" WHERE id = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	//UPGRADE THE IARTICLE IN TS_PATRON_LINK
	$sql = 'SELECT b.ID, b.sReference FROM ts_patron_link a, ts_catalogue b WHERE b.sReference = a.sReference';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_patron_link SET iArticle = "'.$rowId[0].'" WHERE sReference = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}

	
	$sql = 'SELECT id, new_id FROM ts_catalogue_save';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_catalogue SET old_id = "'.$rowId[0].'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	
	
	$sql = 'SELECT id, new_id FROM ts_catalogue_save';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_catalogue SET old_id = "'.$rowId[0].'" WHERE ID = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	
	$sql = 'SELECT id, old_id FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_commande_article SET id_article = "'.$rowId[0].'" WHERE id_article = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	 
	//SET NEW NUMBER CODE FOR THE ORDER
	$sql = 'SELECT ID, sDate FROM ts_commande';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$aDate = explode('-',$rowId[1]);
		$aDay = explode(' ',$aDate[2]);
		
		$sOrderCode = 'TA'.$rowId[0].'D'.$aDay[0].$aDate[1].$aDate[0];
		$up = 'UPDATE ts_commande SET sOrderCode = "'.$sOrderCode.'" WHERE ID = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
	
	$sql = 'SELECT id, old_id FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_promo SET id_perle = "'.$rowId[0].'" WHERE id_perle = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}

	
	$sql = 'SELECT id, old_id FROM ts_catalogue';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$up = 'UPDATE ts_rappel_stock SET id_article = "'.$rowId[0].'" WHERE id_article = "'.$rowId[1].'"';
		mysql_query($up) or die(mysql_error());
	}
	$sql = 'SELECT ID, sDate FROM ts_video';
	$result = mysql_query($sql) or die(mysql_error());
	while($rowId = mysql_fetch_row($result)){
		$sDateSave = $rowId[1];
		$sDateSave = substr_replace($sDateSave, ' ', 10, 1);
		$sDateSave = substr_replace($sDateSave, ':', 13, 1);
		$sDateSave = substr_replace($sDateSave, ':', 16, 1);
		$up = 'UPDATE ts_video SET sDate = "'.$sDateSave.'" WHERE ID = "'.$rowId[0].'"';
		mysql_query($up) or die(mysql_error());
	}
*/
	
	//DELETE ALL ID
	//EDIT ALL NEW_ID to ID
	//ADD EVERYWHERE AND ID AUTOINCREMENT IF IT DOESN'T EXIST
	//EDIT EVERY COLUMN'S NAME IN ENGLISH AND WITH THE LETTER TO DEFINE THE TYPE OF THE VARIABLE
	//DELETE SDATE FROM ts_commande_article
	//RENAME ts_commande_article TO TS_COMMANDE_ARTICLE
	//ADD SCODEORDER IN TS_COMMANDE
function translate( $text, $destLang = 'en', $srcLang = 'fr' ) {
 
	$text = urlencode( $text );
	$destLang = urlencode( $destLang );
	$srcLang = urlencode( $srcLang );
	 
	$trans = @file_get_contents( "ttps://www.googleapis.com/language/translate/v2?key=AIzaSyAt_lq4OLZ95AU_uw5tUeVPjeqGVDr47tA&q={$text}&source={$srcLang}&target={$destLang}" );
	$json = json_decode( $trans, true );
	 
	if( $json['responseStatus'] != '200' ) return false; else return $json['responseData']['translatedText'];
 
}
	echo '<h2>Trad : '.translate('Fil aluminium or 2mm/ 3M').'</h2>';
	$sql = 'SELECT ID, sName_FR, sName_EN FROM ts_catalogue WHERE sName_EN = "" ORDER BY RAND() LIMIT 0,3';
	$result = mysql_query($sql) or die(mysql_error());
	while($aRow = mysql_fetch_row($result)):
		//$sTrad = translate($aRow[1]);
		echo $aRow[1].' - '.translate($aRow[1]).'<br />';
		//$up = 'UPDATE ts_video SET sName_EN = "'.$sTrad.'" WHERE ID = "'.$aRow[0].'"';
		//mysql_query($up) or die(mysql_error());
	endwhile;
?>