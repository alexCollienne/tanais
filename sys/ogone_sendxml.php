<?
include('connect.php');

/*$orderID=0;
if ( isset($_GET['orderID'] ) ) {
	$orderID=$_GET['orderID'];
}

//on se connecte a la bd mysql pour aller chercher le total a payer
if ( $orderID == 0 ) { echo "Numéro inconnu"; exit(); }

//on va chercher l id de la commande
$sql_commande="SELECT id_commande FROM commande WHERE num_commande='$orderID'";
$result_commande=mysql_query($sql_commande);
$liste_commande=mysql_fetch_array($result_commande);
$id_commande=$liste_commande[0];

$amount=0;
$sql_commandedetail = "SELECT `puarticle_detail`,`qarticle_detail` FROM `commande_detail` WHERE `id_commande`='$id_commande'";
$result_commandedetail = mysql_query($sql_commandedetail);
while ($ligne_commandedetail = mysql_fetch_array($result_commandedetail)) {
	$prix=$ligne_commandedetail[0];
	$quantite=$ligne_commandedetail[1];
	$temp=$prix*$quantite;
	$amount+=$temp;
}

//UPDATE

$sql = "SELECT * FROM `bon_achat_permanent_valid` where `id_commande`='$orderID'";
	$result = mysql_query($sql);
$resultat=mysql_fetch_array($result);	
$valeurbon=$resultat[2];	
$amount=$amount-$valeurbon;

$sql_p = "SELECT * FROM `bon_achat_commande` where `id_commande`='$orderID'";
	$result = mysql_query($sql_p) or die ("erreur de requete".mysql_error());
	while ($ligne_p = mysql_fetch_array($result)) {
		$amount-=$ligne_p[3];
		}

$amount=$amount*100;

if ( $amount < 0 ) { echo "PAS POSSIBLE"; exit(); }*/

$sql = "SELECT prix_total FROM ts_commande WHERE date = '".$_GET['orderID']."'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_row($result);

$total = $row[0]*100;

echo '<orderid="'.$orderID.'"  amount="'.$total.'"  currency="EUR">';

//on écrit dans le fichier ...
/*
$date_msg=date("d/m/Y");
$heure_msg=date("H:i");
$fp = fopen("ogone_log.txt", "a");
fputs($fp, "\n\n$date_msg à $heure_msg Commande $orderID => $amount");
fclose($fp);*/
?>