<?
//on regarde les variables qu'on a recu et on le met dans les logs

$message="";
$message=$message.verifier(orderID);
$message=$message.verifier(STATUS );
$message=$message.verifier(Currency);

$message=$message.verifier(Amount);

$message=$message.verifier(PAYID);
$message=$message.verifier(ACCEPTANCE);
$message=$message.verifier(NCERROR);
$message=$message.verifier(PM);
$message=$message.verifier(BRAND);
$message=$message.verifier(CARDNO);
$message=$message.verifier(CCCTY);
$message=$message.verifier(IPCTY);
$message=$message.verifier(ECI);
$message=$message.verifier(CVCCheck);
$message=$message.verifier(AAVCheck);
$message=$message.verifier(VC);
$message=$message.verifier(IP);


function verifier($var) {
	if ( isset($_GET['$var']) ) {
		$msg="\n$var en get : $_GET[$var]";
	}
	else {
		$msg="\n$var PAS DE VARIABLE RECUE en get";
	}
	return $msg;
}




//on lui envoie les informations en xml
$amount="12345";
$orderID=$_GET['orderID'];

echo '<orderid="'.$orderID.'"  amount="'.$amount.'"  currency="EUR">';





//on écrit dans le fichier ...
$date_msg=date("d/m/Y");
$heure_msg=date("H:i");
$fp = fopen("ogone_log.txt", "a");
fputs($fp, "\n\n$date_msg à $heure_msg par la page $page\n$message");
fclose($fp);





?>