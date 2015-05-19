	</div>
	<div class="clearer"></div>
	<div id="footer" class="container">
		<p><a href="index.php">Home</a> - <a href="catalogue.php">Catalogue</a> - <a href="nouveautes.php">Nouveautés</a> - <a href="promo.php">Promotions</a> - <a href="bijoux.php">Bijoux</a> - <a href="patron.php">Patron</a> - <a href="cours.php">Cours vidéo</a> - <a href="/blog">News</a> - <a href="contact.php">Contact</a></p>
		<p>2011 Tana&iuml;s Copyright &copy; <a href="mailto:info@tanais.be">Alexandre Collienne</a> - <a href="condition.php">Condition g&eacute;n&eacute;rale</a> - <a href="frais-de-port.php">Frais de port</a> - <a href="#">Plan du site</a></p>
	</div>
</div>

<?php

//Suite d'alerte javascript. En fin de script pour afficher la page avant l'alerte

if(@$_GET['true'] == 'mail_correct'){
	echo "<script type='text/javascript' language='javascript'>alert('Votre adresse mail a bien été enregistrée');</script>";
}

if(@$_GET['false'] == 'mail_error'){
	echo "<script type='text/javascript' language='javascript'>alert('Votre adresse mail n\'est pas correct');</script>";
}

if(@$_GET['nombre'] == 'false'){
	echo "<script type='text/javascript' language='javascript'>alert('La quantite que vous demandez est éronnée');</script>";
}

if(@$_GET['login'] == 'false'){
	echo "<script type='text/javascript' language='javascript'>alert('Vous adresse mail ou votre password n\'est pas correct.');</script>";
}

if(@$_GET['favoris'] == 'false'){
	echo "<script type='text/javascript' language='javascript'>alert('Vous devez vous inscrire et vous connecter pour enregistrer vos articles favoris.');</script>";
}

if(@$_GET['quantite'] == 'false'){
	echo "<script type='text/javascript' language='javascript'>alert('La quantite commandée est supérieur au stock.');</script>";
}
?>

</body>
</html>