<?php 
include('sys/session.php');

if(empty($_SESSION['tmpCommande'])){
	header('Location:index.php');
}else{
	include('include/header.php'); 
?>
<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Caddie - Adresse</title>
	
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
<h1 style="font-size:24px; color:#C7317F">Adresse de livraison</h1>

<form method="post" enctype="application/x-www-form-urlencoded" id="confirmForm" name="confirm" action="commande-paiement.php">

<?php

$valueName = "";
$valueAdresse = "";
$valueCity = "";
$valueTel = "";

if(!empty($_SESSION['nom'])){
	$valueName = $_SESSION['nom']." ".$_SESSION['prenom'];
	$valueAdresse = $_SESSION['adresse']." ".$_SESSION['numero']." ".$_SESSION['boite'];
	$valueZip = $_SESSION['cp'];
	$valueCity = $_SESSION['ville'];
	$valueTel = $_SESSION['tel'];
	$valueMail = $_SESSION['mail'];
}
?>
	<ul id="etapes">
		<li id="first"><a href="caddie.php" class="pink">1<br />Caddie</a></li>
		<li><img src="img/site/line-arrowpink.jpg" /></li>
		<li><a href="commande-login.php" class="pink">2<br />Inscription</a></li>
		<li><img src="img/site/line-arrowpinkcurrent.jpg" /></li>
		<li class="current"><span>3<br />Livraison</span></li>
		<li><img src="img/site/line-arrowcurrent.jpg" /></li>
		<li><span class="grey">4<br />Paiement</span></li>
		<li><img src="img/site/line-arrowgrey.jpg" /></li>
		<li><span class="grey">5<br />Validation</span></li>
		<li><img src="img/site/line-last.jpg" /></li>
	</ul>
	<div id="contentCommande">
		<label>Nom et prénom: </label><input type="text" name="name" size="55" value="<?php echo $valueName; ?>" /><br />
		<label>Rue et numéro: </label><input type="text" name="street" size="55" value="<?php echo $valueAdresse; ?>" /><br />
		<label>Code postal: </label><input type="text" name="zip" size="15" value="<?php echo $valueZip; ?>" /><br />
		<label>Ville: </label><input type="text" name="city" size="55" value="<?php echo $valueCity; ?>" /><br />
		<label>Téléphone: </label><input type="text" name="phone" size="55" value="<?php echo $valueTel; ?>" /><br />
		<label>Adresse mail: </label><input type="text" name="mail" size="55" value="<?php echo $valueMail; ?>" /><br />
		<label>Pays: </label>
		<select name="country">
		<?php $aCountry = dataManager::Read('ts_pays',null,array('sNamei18n','ASC')); ?>
		<?php foreach($aCountry as $oCountry): ?>
			<option <?php echo ($_SESSION['pays'] == $oCountry->sCode) ? 'selected="selected"' : ''; ?> value="<?php echo $oCountry->sCode ?>"><?php echo $oCountry->sNamei18n ?></option>
		<?php endforeach; ?>
		</select>
		<p>Commentaire</p>
		<textarea name="comment" cols="50" rows="10"></textarea>
		<p style="font-size:10px;">Les frais de ports seront naturellement calcul&eacute;s en fonction du pays de livraison et non pas en fonction de celui de votre inscription.</p>

		<input type="submit" class='btn' value="Confirmer l'adresse de livraison" />
	</div>
</form>
</div>
<?php include('include/footer.php'); } ?>