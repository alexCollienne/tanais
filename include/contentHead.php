<div id="header" class="container">
    <div id="logo" class="floatLeft">
		<a href="index.php"><img src="img/site/logo.jpg" alt="logo" /></a>
	</div>
	<div id="search-box" class="floatRight">
	    <form action="search.php" method="GET" enctype="multipart/form-data" name="search">
			<input type="submit" value="Chercher" class="floatRight btnHeader" />
			<input type="text" value="Rechercher un article" name="recherche" size="64" onClick="this.value=''" id="search" class="floatLeft" />
		</form>
		<div id="SearchResults"> </div>

		<div class="clearer"></div>
		
		<ul style="z-index:10">
			<li><a href="search.php?couleur=crystal" class="box-color" style="background-color:#FFF; color:#FFF" title="crystal">&nbsp;</a></li>
			<li><a href="search.php?couleur=cuivre" class="box-color" style="background-color:#663717; color:#663717;" title="cuivre">&nbsp;</a></li>
			<li><a href="search.php?couleur=bronze" class="box-color" style="background-color:#7B8F07; color:#7B8F07;" title="bronze">&nbsp;</a></li>
			<li><a href="search.php?couleur=argent" class="box-color" style="background-color:#CCC; color:#CCC;" title="argent">&nbsp;</a></li>
			<li><a href="search.php?couleur=doré" class="box-color" style="background-color:#EBCF0E; color:#EBCF0E;" title="doré">&nbsp;</a></li>
			<li><a href="search.php?couleur=rouge" class="box-color" style="background-color:#C60000; color:#C60000;" title="rouge">&nbsp;</a></li>
			<li><a href="search.php?couleur=fushia" class="box-color" style="background-color:#C09; color:#C09;" title="fushia">&nbsp;</a></li>
			<li><a href="search.php?couleur=rose" class="box-color" style="background-color:#F9C; color:#F9C;" title="rose">&nbsp;</a></li>
			<li><a href="search.php?couleur=mauve" class="box-color" style="background-color:#909; color:#909;" title="mauve">&nbsp;</a></li>
			<li><a href="search.php?couleur=bleu" class="box-color" style="background-color:#30C; color:#30C;" title="bleu">&nbsp;</a></li>
			<li><a href="search.php?couleur=turquoise" class="box-color" style="background-color:#0C9; color:#0C9;" title="turquoise">&nbsp;</a></li>
			<li><a href="search.php?couleur=vert" class="box-color" style="background-color:#3C3; color:#3C3;" title="vert">&nbsp;</a></li>
			<li><a href="search.php?couleur=jaune" class="box-color" style="background-color:#ECDA00; color:#ECDA00;" title="jaune">&nbsp;</a></li>
			<li><a href="search.php?couleur=orange" class="box-color" style="background-color:#DD6002; color:#DD6002;" title="orange">&nbsp;</a></li>
			<li><a href="search.php?couleur=brun" class="box-color" style="background-color:#885420; color:#885420;" title="brun">&nbsp;</a></li>
			<li><a href="search.php?couleur=beige" class="box-color" style="background-color:#F0D979; color:#F0D979;" title="beige">&nbsp;</a></li>
			<li><a href="search.php?couleur=blanc" class="box-color" style="background-color:#FFF; color:#FFF;" title="blanc">&nbsp;</a></li>
			<li><a href="search.php?couleur=gris" class="box-color" style="background-color:#999; color:#999;" title="gris">&nbsp;</a></li>
			<li><a href="search.php?couleur=noir" class="box-color" style="background-color:#000; color:#000; margin-right:0px;" title="noir">&nbsp;</a></li>
		</ul>
    </div>

    <div id="mailing-box">
		<script type="text/javascript">

		function mailing(){
				
				var email = document.forms['newsletter'].elements['mail'].value;;
				var posAt = email.indexOf("@",1);
				var periodPos = email.indexOf(".", posAt);
				var invalidChar = "/'\":;,?!";

				for(var k=0; k<invalidChar.length; k++){
					var badChar = invalidChar.charAt(k);
					if(email.indexOf(badChar) > -1){
						document.forms.onsubmit = alert('Des caractères invalides ont été trouvé dans votre adresse mail.');
						return false;
					}
				}
				
				if(posAt == -1 || email.indexOf("@",posAt+1) != -1 || periodPos == -1 || periodPos+3 > email.length || email == ""){
					document.forms.onsubmit = alert('L\'adresse mail n\'est pas correct.');
					return false;
				}
				
				return true;
		}

		</script>
		<form method="post" enctype="multipart/form-data" name="newsletter" onsubmit="return mailing()" action="sys/mailing.php">
		  <input type="submit" value="OK" class="floatRight btnHeader" />
		  <input type="text" value="Inscrivez-vous &agrave; la newsletter de Tana&iuml;s" name="mail" size="64" onClick="this.value=''" id="newsletter" class="floatLeft" />
		</form>
    </div>
</div>
<div class="clearer"></div>

<?php /*Appelle le menu*/include('include/menu.php'); ?>
	
<div class="clearer"></div>

<div class="container">
	
	<div id="rightSide" class="floatRight">
		
		<div id="caddie-box" class="rightBox">
			<div class="bottomRightBox">
				<h3>Caddie</h3>
				<?php

					$quantiteTotal = 0;
					$prixTotal = 0;
					
					if(isset($_SESSION['tmpCommande'])){
						foreach($_SESSION['tmpCommandePrixTotal'] as $prix){
							$prixTotal += $prix;
						}
						foreach($_SESSION['tmpCommandeQuantite'] as $quantite){
							$quantiteTotal += $quantite;
						}
					}
					
					if(isset($_SESSION['tmpCommande'])){
						echo "<p class='floatLeft'>".$prixTotal."<br />Euro</p>";
						echo "<p class='floatRight'>".$quantiteTotal."<br />articles</p><div class='clearer'></div>";
						echo "<p><a href='caddie.php' class='boutonBoxRight'>Voir le caddie</a></p>";
					}else{
						echo "<p class='floatLeft'>0<br />Euro</p>";
						echo "<p class='floatRight'>0<br />article</p>";
					}
				?>
				<div class="clearer"></div>
			</div>
		</div>
		
		<div id="boxLanguage">
			<a href="sys/translate.php?lng=FR">Fr</a>
			<a href="sys/translate.php?lng=EN">En</a>
		</div>
		
		<div id="loginBox" class="rightBox">
			<div class="bottomRightBox">	
				<?php

				if(isset($_SESSION['nom'])){ echo "<h3>Connect&eacute;</h3>";}else{echo "<h3>Connexion</h3>";}
				//Boucle permettant d'afficher la box personnelle d'un visiteur si celui-ci est enregistré ou d'afficher le formulaire de log in si ce n'est pas le cas.
				if(isset($_SESSION['genre'])):
					echo "<p>Bienvenue, ".$_SESSION['genre']." ".$_SESSION['nom']."</p>";
					echo "<p><a href='page-perso.php?option=historique' class='boutonBoxRight' style='margin:0 0 0 3px'>Page perso</a></p>";
					echo "<p><a href='sys/logout.php' class='boutonBoxRight' style='margin:0 0 0 3px'>D&eacute;connexion</a></p>";
				else:
					?>
					<form action="sys/login.php" enctype="multipart/form-data" method="post" id="login-box">
						<label class="lose-password">Mail</label>
							<input type="text" name="mail" id="loginMail" value="" onclick="this.value=''" />
						<label class="lose-password">Password</label>
							<input type="password" name="password" id="loginPassword" value="" onclick="this.value=''" />
							<!--<input type="checkbox" name="logCookie" id="logCookie" class="floatRight" />
						<label for="logCookie">Rester connecté</label>-->
							<input type="submit" value="Connexion" class='boutonBoxRight' />
					</form>
					<p><a href="login-lose.php" class='loginLose'>Mot de passe oublié?</a></p>
					<p><a href="inscription.php" class='boutonBoxRight'>Inscription</a></p>
				<?php endif; ?>
			</div>
		</div>
		
		<div id="adresse">
			<p>
			<?php $aWords = dataManager::Read('ts_word_search', array(array('sWordi18n', '!=', '')), 'random', array(0,15)); ?>
			<?php foreach($aWords as $oWord): ?>
				<?php $sSize = $oWord->iQuantity/6	+8; ?>
				<?php $sSize = ($sSize > 25) ? 25 : $sSize; ?>
				<a href='search.php?recherche=<?php echo $oWord->sWordi18n ?>' style='color:#c7317f; vertical-align:middle; font-weight:500; text-decoration:none; font-size:<?php echo $sSize ?>px'><?php echo $oWord->sWordi18n ?></a>
			<?php endforeach; ?>
			</p>
			<br />
			<p>Tana&iuml;s</p>
			<p>Rue de Velaine, 95</p>
			<p>5300 Landenne (Andenne)</p>
			<p>Belgique</p>
			<p>0032(0)85/82.82.38</p>
			<p>BE0657.716.814</p>
			<br />
			<p style="color:#c7317f; text-decoration:underline">Possibilité de venir faire vos achats sur place le:</p><p style="color:#c7317f">mardi de 10h30à17h00</p><p style="color:#c7317f">mercredi de 13h30 à 17h00</p><p style="color:#c7317f">vendredi de 10h30 à 13h</p><p style="color:#c7317f">samedi sur rdv</p></div>
			<table cellspacing="5">
				<tr>
					<td colspan="2" style="text-align:center;"><img src="img/site/paypal.jpg" /></td>
				</tr>
				<tr>
					<td><img src="img/site/visa.jpg" /></td>
					<td><img src="img/site/bancontact.jpg" /></td>
				</tr>
				<tr>
					<td><img src="img/site/cb.jpg" /></td>
					<td><img src="img/site/aex.jpg" /></td>
				</tr>
				<tr>
					<td><img src="img/site/virement.jpg" /></td>
					<td><img src="img/site/cheque.jpg" /></td>
				</tr>
			</table>
		</div>