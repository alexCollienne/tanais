<?php 

include('sys/session.php');	

if(empty($_SESSION['tmpCommande'])):
	header('Location:index.php');
elseif($_SESSION['bTotalNull']):
	$_SESSION['delivreryName'] = $_POST['name'];
	$_SESSION['delivreryStreet'] = $_POST['street'];
	$_SESSION['delivreryCity'] = $_POST['city'];
	$_SESSION['delivreryZip'] = $_POST['zip'];
	$_SESSION['delivreryCountry'] = $_POST['country'];
	$_SESSION['delivreryMail'] = $_POST['mail'];
	$_SESSION['delivreryPhone'] = $_POST['phone'];
	$_SESSION['comment'] = $_POST['comment'];
	header('Location:commande-validation.php');
else:
	include('include/header.php');
	$_SESSION['delivreryName'] = $_POST['name'];
	$_SESSION['delivreryStreet'] = $_POST['street'];
	$_SESSION['delivreryCity'] = $_POST['city'];
	$_SESSION['delivreryZip'] = $_POST['zip'];
	$_SESSION['delivreryCountry'] = $_POST['country'];
	$_SESSION['delivreryMail'] = $_POST['mail'];
	$_SESSION['delivreryPhone'] = $_POST['phone'];
	$_SESSION['comment'] = $_POST['comment'];
?>
<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Confirmation de la commande</title>
<script>
	function confirmPaiement(){
	
		var ogone1 = document.getElementById('ogone1').checked;
		var ogone2 = document.getElementById('ogone2').checked;
		var cb = document.getElementById('cb').checked;
		var paypal = document.getElementById('paypal').checked;
		var cheque = document.getElementById('cheque').checked;
		var virement = document.getElementById('virement').checked;

		if(!ogone1 && !ogone2 && !paypal && !cheque && !virement && !cb){
			document.forms.onsubmit = alert('Vous devez choisir un mode de paiement.');
			return false;
		}
	}
</script>	
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<h1 style="font-size:24px; color:#C7317F">Choissir un mode de paiement</h1>
	<ul id="etapes">
		<li id="first"><a href="caddie.php" class="pink">1<br />Caddie</a></li>
		<li><img src="img/site/line-arrowpink.jpg" /></li>
		<li><a href="commande-login.php" class="pink">2<br />Inscription</a></li>
		<li><img src="img/site/line-arrowpink.jpg" /></li>
		<li><a href="commande-adres.php" class="pink">3<br />Livraison</a></li>
		<li><img src="img/site/line-arrowpinkcurrent.jpg" /></li>
		<li class="current"><span>4<br />Paiement</span></li>
		<li><img src="img/site/line-arrowcurrent.jpg" /></li>
		<li><span class="grey">5<br />Validation</span></li>
		<li><img src="img/site/line-last.jpg" /></li>
	</ul>
	<div id="contentCommande">
		<form method="post" enctype="application/x-www-form-urlencoded" onsubmit="return confirmPaiement()" id="confirmForm" name="confirm" action="commande-validation.php">
			<table>
				<tr><td><input type="radio" name="paiement" id="ogone1" value="ogone" onclick="payOgone()" /></td><td><label for="ogone1"><img src="img/site/bancontact.jpg" /></label></td></tr>
				<tr><td><input type="radio" name="paiement" id="ogone2" value="ogone" onclick="payOgone()" /></td><td><label for="ogone2"><img src="img/site/visa.jpg" /><img src="img/site/aex.jpg" /></label></td></tr>
				<tr><td><input type="radio" name="paiement" id="paypal" value="paypal" onclick="payPaypal()" /></td><td><label for="paypal"><img src="img/site/paypal.jpg" /></label></td></tr>
				<tr><td><input type="radio" name="paiement" id="virement" value="virement" onclick="payVirement()"  /></td><td><label for="virement"><img src="img/site/virement.jpg" /></label></td></tr>
				<tr><td><input type="radio" name="paiement" id="cb" value="cb" onclick="payCb()" /></td><td><label for="cb"><img src="img/site/cb.jpg" /></label></td></tr>
				<tr><td><input type="radio" name="paiement"id="cheque" value="cheque" onclick="payCheque()" /></td><td><label for="cheque"><img src="img/site/cheque.jpg" style="margin-left: 8px;" /></label></td></tr>
			</table>
			<input type="submit" class='btn' value="Payer la commande" />
		</form>
	</div>
</div>
<?php include('include/footer.php'); ?>
<?php endif; ?>