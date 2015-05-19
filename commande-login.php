<?php 

include('sys/session.php');
$_SESSION['bon'] = (isset($_POST['reliquat'])) ? $_POST['reliquat'] : '';
$_SESSION['bTotalNull'] = $_POST['bTotalNull'];
	if(empty($_SESSION['tmpCommande'])){
		header('Location:index.php');
	}elseif(isset($_SESSION['id'])){
		header('Location:commande-adresse.php');
	}else{
	
	include('include/header.php'); 
	
?>	
	<script>
		$("#displayInscription").click(function(){
			$("#blockInscription").css("display","block");
		});
	</script>
	
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Caddie - Enregistrement</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<h1 style="font-size:24px; color:#C7317F">Votre caddie - Enregistrement</h1>

	<ul id="etapes">
		<li id="first"><a href="caddie.php" class="pink">1<br />Caddie</a></li>
		<li><img src="img/site/line-arrowpinkcurrent.jpg" /></li>
		<li class="current"><span>2<br />Connexion</span></li>
		<li><img src="img/site/line-arrowcurrent.jpg" /></li>
		<li><span class="grey">3<br />Inscription</span></li>
		<li><img src="img/site/line-arrowgrey.jpg" /></li>
		<li><span class="grey">4<br />Paiement</span></li>
		<li><img src="img/site/line-arrowgrey.jpg" /></li>
		<li><span class="grey">5<br />Validation</span></li>
		<li><img src="img/site/line-last.jpg" /></li>
	</ul>
	<div id="contentCommande">	
		<div id="login2" class="floatRight stepCommande">
			<h2>Pas encore inscrit?</h2>
			<form action="sys/sql-bdd-inscription.php" method="post" enctype="multipart/form-data" name="formInscription" class="commandeInscription" onsubmit="return valid()">
				<div id="blockInscription">
					<div class="input">
						<label>Titre</label>
						<select name="genre">
							<option value='Mme.'>Mme.</option>
							<option value='Mlle.'>Mlle.</option>
							<option value='Mr.'>Mr.</option>
						</select>
					</div>
					<div class="clearer"></div>
					<div class="input">
						<label>Pr&eacute;nom</label>
						<input type="text" name="prenom" />
					</div>
					<div class="input">
						<label>Nom</label>
						<input type="text" name="nom" />
					</div>
					<div class="input">
						<label>Email</label>
						<input type="text" name="mail_inscription" id="mail_inscription" />
					</div>
					<div class="input">
						<label>Confirmer Email</label>
						<input type="text" name="confirm_mail_inscription" id="confirm_mail_inscription" />
					</div>
					<div class="input">
						<label>Mot de passe</label>
						<input type="password" name="mdp_inscription" />
					</div>
					<div class="input">
						<label>Confirmer le mot de passe</label>
						<input type="password" name="confirm_mdp_inscription"/>
					</div>
					<div class="input">
						<label>T&eacute;l&eacute;phone</label>
						<input type="text" name="tel" value="" />
					</div>
					<div class="input">
						<label>Rue</label>
						<input type="text" name="adresse" value="" />
					</div>
					<div class="input">
						<label>Num&eacute;ro - Boite</label>
						<input type="text" name="numero" value="" class="tinyInput" />
						<input type="text" name="boite" value="" class="tinyInput floatRight" />
					</div>
					<div class="input">
						<label>Code postal</label>
						<input type="text" name="cp" value="" />
					</div>
					<div class="input">
						<label>Ville</label>
						<input type="text" name="ville" value="" />
					</div>
					<div class="input">
						<label>Pays</label>
						<select name="pays" lang="fr" style="width:170px;">
			                <?php
			                $aCountry = dataManager::Read('ts_pays',null,array('sNamei18n','ASC'));
			                foreach($aCountry as $oCountry):
			                ?>
								<option <?php echo (isset($oSession) && $oSession->sCountry == $oCountry->sCode) ? 'selected="selected"' : ''; ?> value="<?php echo $oCountry->sCode ?>"><?php echo $oCountry->sNamei18n ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clearer"></div>
				</div>
				<div>
					<input type='submit' value='Envoyer' class='btn' />
				</div>
			</form>
		</div>
		<div id="login1" class="floatLeft stepCommande">
			<h2>Déjà inscrit?</h2>
			<form action="sys/login.php" method="post" enctype="application/x-www-form-urlencoded" >
				<div class="input">
					<label class="lose-password">Mail</label>
					<input type="text" name="mail" />
				</div>
				<div class="input">
					<label class="lose-password">Password</label>
					<input type="password" name="password" />
				</div>
				<div class="clearer"></div>	
				<div>
					<input type='submit' value='Connexion' class='btn' />
				</div>
			</form>	
		</div>
	</div>
</div>
<?php include('include/footer.php'); } ?>