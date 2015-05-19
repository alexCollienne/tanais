<?php

require('connect.php');

include('../include/function.php');
include('../include/class.php');

$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
                               
$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
'(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
                                // séparés par des caractères autorisés avant l'arobase
'@' .                           // Suivis d'un arobase
'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
                                // séparés par des points
$domain . '{2,63}$/i';

if($_POST['mail'] != 'Votre adresse mail' && preg_match($regex, $_POST['mail'])):
	$aClient = dataManager::Read('ts_rappel_stock',array(array('sMail','=',$_POST['mail']),array('iArticle','=',$_POST['iarticle'])));
	if(!empty($aClient)):
		echo 2;
	else:
		$bAdd = dataManager::Write('insert','ts_rappel_stock',array(array('sMail',$_POST['mail']),array('iArticle',$_POST['iarticle'])));
		echo ($bAdd) ? 1 : $bAdd;
	endif;
else:
	echo 3;
endif;

?>