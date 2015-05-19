<?php

if(!is_file("img/creation-client/".$_SESSION['id']."/".$_FILES['frm_file']['name'])){
	if($_FILES['frm_file']['name']){
		move_uploaded_file($_FILES['frm_file']['tmp_name'], "img/creation-client/".$_SESSION['id']."/".$_FILES['frm_file']['name']);
			if(!is_uploaded_file($_FILES['frm_file']['tmp_name'])) {
				echo "<p>Le fichier a &eacute;t&eacute; upload&eacute;. Merci. <a style='color:#C7317F' href='page-perso.php?option=creation'>Retour</a></p>";
				$sql = "INSERT INTO ts_creation (id, id_client, image) VALUES ('".uniqid()."', '".$_SESSION['id']."', '".$_FILES['frm_file']['name']."')";
				$result = mysql_query($sql) or die ("Erreur: ".mysql_error());
			} else {
				echo "<p>D&eacute;sol&eacute;, votre fichier n'a pas &eacute;t&eacute; transf&eacute;r&eacute;. Veuillez recommencer. Merci. <a style='color:#C7317F' href='page-perso.php?option=creation'>Retour</a></p>";
				echo "img/creation-client/".$_SESSION['id']."/".$_FILES['frm_file']['name'];
				}
	}else{
		echo "<p>Veuillez choissir un fichier. Merci. <a style='color:#C7317F' href='page-perso.php?option=creation'>Retour</a></p>";}
}else{
	echo "<p>Un fichier porte d&eacute;j&agrave; ce nom, veuillez renommer votre photo. Merci. <a style='color:#C7317F' href='page-perso.php?option=creation'>Retour</a></p>";}

?>