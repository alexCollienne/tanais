<?php 
	include('sys/session.php');
	include('include/header.php'); 
	
	if(isset($_GET['action']) && $_GET['action'] == "createFolder"){
		mkdir ("img/creation-client/".$_SESSION['id'], 0777);
	}
	if(isset($_GET['delete'])):
		$bDelete = dataManager::Write('delete', 'ts_favoris', null, array(array('iArticle','=',$_GET['id']),array('iClient','=',$_SESSION['id'])));
	endif;
?>
<link rel='stylesheet' media='screen' href='css/catalogue.css' />
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->
<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Page personnelle</title>

  <script>
	$(document).ready(function() {
		$("#linkArticle").click(function(){
			$("#accordion").css("display", "none");
			$("#articlePerso").css("display", "block");
			$(this).css("border-bottom", "1px solid #eeeeee");
			$("#linkCommande").css("border-bottom", "1px solid #000000");
		});
		
		$("#linkCommande").click(function(){
			$("#accordion").css("display", "block");
			$("#articlePerso").css("display", "none");
			$(this).css("border-bottom", "1px solid #eeeeee");
			$("#linkArticle").css("border-bottom", "1px solid #000000");
		});
	
		$(".slide").click(function(){
			$(this).next('.show').slideToggle(0);
		});
	});
	
  </script>

</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
<?php

//Affichage du bon d'achat
$aBon = dataManager::Read('ts_reliquat',array(array('iClient','=',$_SESSION['id'])));
if($aBon[0]):
  echo "<div id='box-reliquat'><p style='margin-top:5px'>Bon d'achat</p><p>".$aBon[0]->iValue."&euro;</p></div>";
else:
  echo "<div id='box-reliquat'><p style='margin-top:5px'>Bon d'achat</p><p>0 &euro;</p></div>";
endif;
?>
<ul id="text-accueil">
	<img src="img/site/patron-top.jpg" width="430" height="8" style="margin-bottom:20px;" />
	<li>Par un simple "clic", vous pouvez modifier facilement vos donn&eacute;es personnelles</li>
	<li>Vous avez la possibilit&eacute; d\'ajouter des articles favoris en cliquant sur l\'&eacute;toile que vous trouverez en affichant le d&eacute;tail de l\'article</li>
	<li>Vous savez facilement suivre vos commandes en temps r&eacute;el, article par article</li>
	<li>Tr&egrave;s prochainement, vous pourrez partager vos cr&eacute;ations personnelles avec la possibilit&eacute; de les vendre. </li>
	<img src="img/site/patron-bottom.jpg" width="430" height="8" style="margin-top:20px;" />
</ul>

<?php
//Affichage des données personnelles.
echo "<div id='donnee-perso'><p>".$_SESSION['genre']." ".$_SESSION['prenom']." ".$_SESSION['nom']."</p>";
echo "<p>Mail: ".$_SESSION['mail']."</p>";
echo "<p>".$_SESSION['adresse']." ".$_SESSION['numero']." ".$_SESSION['boite']." </p><p>".$_SESSION['cp']." ".$_SESSION['ville']."</p> <p>".getCountry($_SESSION['pays'])."</p>";
echo "<p>Tel: ".$_SESSION['tel']."</p> <p><a href='inscription.php?id=".$_SESSION['id']."' style='color:#c7317f; font-size:12px;'>Modification de vos donn&eacute;es personnelles</a></p></div>";

//Menu de la page personelle
?>

<div id="menu-perso">
  <a href="page-perso.php?option=historique" id="menu-perso_historique" class="btn">Vos commandes</a>
  <a href='page-perso.php?option=favoris' id="menu-perso_favoris" class="btn">Vos favoris</a>
  <!-- <a href='page-perso.php?option=creation' id="menu-perso_creation" class="btn">Vos cr&eacute;ations</a> -->
</div>

<div class="clearer"></div>

<?php if(isset($_GET['option']) && $_GET['option'] == "favoris"): ?>

	<h1 id='title_option_perso'>Favoris</h1>
	<div class="clearer"></div>
	<div id="articleFavoris">
		<?php $aArticle = dataManager::Read('ts_favoris',array(array('ts_favoris.iClient','=',$_SESSION['id'])),null,null,array(array('INNER JOIN','ts_catalogue',array(array('ts_favoris.iArticle','=','ts_catalogue.ID'))))); ?>
		<?php foreach($aArticle as $oArticle): ?>
		<div class="article">
			<a href="catalogue.php?id=<?php echo $oArticle->ID ?>"><img src="img/catalogue/<?php echo $oArticle->sImage ?>" alt="<?php echo $oArticle->sNAmei18n ?>" width="80" height="80" /></a><br />
			<a href="img/zoom/<?php echo $oArticle->sImage ?>" class="pirobox">zoom</a>
			<a href='page-perso.php?option=favoris&delete=true&id=<?php echo $oArticle->ID ?>' onclick="return confirm('Etes-vous s&ucirc;r de vouloir supprimer cet article de vos favoris?')" class='delete'>Supprimer</a>
		</div>
		<?php endforeach; ?>
	</div>

<?php elseif(isset($_GET['option']) && $_GET['option'] == "creation"): ?>

	<h1 id='title_option_perso'>Cr&eacute;ation personnelle</h1>
	<p>Bienvenu dans la partie cr&eacute;tion. C'est une partie qui est toujours en cours de d&eacute;veloppement. Vous pouvez malgr&eacute; tout mettre en ligne vos r&eacute;alisations. A long terme, votre site <strong style='color:#C7317F'>Tana&iuml;s</strong> vous permettra d'afficher ces r&eacute;lisations aux yeux des autres clients et de peut-&ecirc;tre les vendre.</p>
	<p>Nous vous souhaitons une bonne visite.</p>
	<div class="clearer"></div>
	
	<?php if(!is_dir("img/creation-client/".$_SESSION['id'])): ?>
		<a href='page-perso.php?option=creation&action=createFolder'>Cr&eacute;er une galerie pour vos bijoux</a>";
	<?php else: ?>
		<form method='post' action='sys/add-crea.php' enctype='multipart/form-data'>
			<input type='file' name='frm_file' />
			<input type='submit' value='Envoyer' class='btn creation' />
		</form>
		<div id='creation'>
			<?php $aCreation = dataManager::Read('ts_creation',array(array('iClient','=',$_SESSION['id']))); ?>
			<?php foreach($aCreation as $oCreation): ?>
				<div class='catalogue carre-block' style='float:left'>
					<img src='img/site/shadow-left.jpg' class='image border' />
					<a href='img/creation-client/<?php echo $_SESSION['id'] ?>/<?php echo $oCreation->sImage ?>' rel='lightbox' title='<?php echo $oCreation->sNamei18n ?>'>
						<img src='img/creation-client/<?php echo $_SESSION['id'] ?>/<?php echo $oCreation->sImage ?>' alt='<?php echo $oCreation->sNamei18n ?>' style='border:none; width:70px; height:70px;' />
					</a>
					<img src='img/site/shadow-right.jpg' class='image border' />
					<a href='sys/sql-delete-creation.php?id=".$row[0]."' style='clear:both; text-align:center; font-size:12px; display:block; color:#c7317f;'>Supprimer</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php else: ?>
	<h1 id='title_option_perso'>Historique</h1>
	<ul id="persoSubMenu">
		<li id="linkCommande">Historique de vos commandes</li>
		<li id="linkArticle">Historique de vos article command&eacute;s</li>
	</ul>
	<div class="clearer"></div>
	<?php $aOrder = dataManager::Read('ts_commande',array(array('iClient','=',$_SESSION['id'])),array('sDate','DESC')); ?>
	<?php if(empty($aOrder)): ?>
		<p>Vous n'avez pas encore pass&eacute; de commande dans notre magasin en ligne.</p>
	<?php else: ?>
        <div id='accordion'>
        	<p class="tiny">
        		<img src="img/site/histo_current.jpg" /> <span>Commande en cours</span>
        		<img src="img/site/histo_complete.jpg" /> <span>Commande complète</span>
        		<img src="img/site/histo_half.jpg" /> <span>Commande incomplète</span>
        	</p>
        	
        	<div class="clearer"></div>
			
			<?php  $aListArticle = array(); ?>
			<?php foreach($aOrder as $oOrder):
			
				if($oOrder->sStatus == 'en_cours'):
					$statut = "histoCurrent";
				elseif($oOrder->sStatus == "envoye"):
					$statut = "histoComplete";
				elseif($oOrder->sStatus == "incomplete"):
					$statut = "histoHalf";
				endif;
			
				$total_caddie = 0;
				$flag = true;
			?>				
			<p class="slide <?php echo $statut ?>">Commande num&eacute;ro <?php echo $oOrder->sOrderCode ?> - Datant du <?php echo formatDate($oOrder->sDate) ?></p>
			
			<table class='show' cellspacing="0" border="1" cellpadding="0" width="800" width="800">
				<thead>
					<tr>
						<th width="40"></th>
						<th>Nom</th>
						<th>Quantit&eacute;</th>
						<th>Prix unitaire</th>
						<th>Prix total</th>
					</tr>
				</thead>
				<tbody>
					<?php $aArticle = dataManager::Read('ts_commande_article',array(array('iOrder','=',$oOrder->ID)),null,null,array(array('INNER JOIN','ts_catalogue',array(array('ts_commande_article.iArticle','=','ts_catalogue.ID'))))); ?>
					<?php foreach($aArticle as $oArticle): ?>
						<?php $class = ($flag == true) ? 'even' : 'odd'; ?>
						<tr class='<?php echo $class; ?>'>
							<td><a href='img/zoom/<?php echo $oArticle->sImage; ?>' class='pirobox'><img src='img/catalogue/<?php echo $oArticle->sImage ?>' width='20' /></a></td>
							<td><a href='catalogue.php?id=<?php echo $oArticle->ID ?>'><?php echo $oArticle->sNamei18n ?></a></td>
							<td><?php echo $oArticle->iQuantity ?></td>
							<td align='right'><?php echo number_format($oArticle->iPrice, 2, ",", ""); ?> &euro; </td>
							<td align='right'><?php echo number_format($oArticle->iTotal, 2, ",", ""); ?> &euro; </td>
						</tr>
						<?php if(!in_array($oArticle->ID, $aListArticle)): ?>
							<?php $aListArticle[] =  $oArticle->ID;?>
						<?php endif; ?>
						<?php $flag = !$flag; ?>
					<?php endforeach; ?>
					<tr>
						<td colspan='4'>
							<p>Frais de port</p>
						</td>
						<td align='right'>
							<p><?php echo number_format($oOrder->iVoucher, 2, ",", "") ?> &euro;</p>
						</td>
					</tr>
					<?php if($oOrder->iVoucherUsed > 0): ?>
					<tr>
						<td colspan='4'>
							<p>Bon d'achat</p>
						</td>
						<td align='right'>
							<p><?php echo number_format($oOrder->iVoucherUsed, 2, ",", "") ?> &euro;</p>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td colspan='4'>
							<p>TOTAL</p>
						</td>
						<td align='right'>
							<p><?php echo number_format($oOrder->iTotal, 2, ",", "") ?> &euro;</p>
						</td>
					</tr>
				</table>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div id='articlePerso' style='display:none;'>
		<?php foreach($aListArticle as $iId): ?>
			<?php $aArticle = dataManager::Read('ts_catalogue',array(array('ID','=',$iId))); ?>
			<?php $oListArticle = $aArticle[0]; ?>
			<div class="article">
				<a href="catalogue.php?id=<?php echo $oListArticle->ID ?>"><img src="img/catalogue/<?php echo $oListArticle->sImage ?>" alt="<?php echo $oListArticle->sNamei18n ?>" width="80" height="80" /></a><br />
				<a href="img/zoom/<?php echo $oListArticle->sImage ?>" class="pirobox">zoom</a>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

</div>
<?php include('include/footer.php'); ?>