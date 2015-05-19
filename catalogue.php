<?php 
	include('sys/session.php');
	include('include/header.php');
?>
<script type="text/javascript">
	$(document).ready(function() {			
		$("#favoriteButton").click( function() {
			<?php if(isset($_SESSION['id'])): ?>
				$.get("sys/sql-add-favoris.php",{
					id:"<?php echo $_GET['id'] ?>"}, 
					function(data){
						if(data == true){
							alert('Cet article a bien été ajouté à votre liste personnelle de favoris');
						}else{
							alert('Cet article n\'a pas été ajouté à votre liste personnelle de favoris');
						}
					}
				);
			<?php else: ?>
				alert('Il vous faut être connecté pour pouvoir ajouter cet article à votre liste personnelle de favoris');
			<?php endif; ?>
		});						

		// Inscription to the stock list
		$("#subscribe-stock").submit( function() {	// à la soumission du formulaire						 
			$.ajax({ // fonction permettant de faire de l'ajax
			   type: "POST", // methode de transmission des données au fichier php
			   url: "sys/subscribe-stock.php", // url du fichier php
			   data: "mail="+$("#mail").val()+"&iarticle="+$("#iArticle").val(), // données à transmettre
			   success: function(msg){ // si l'appel a bien fonctionné
				   alert(msg);
					if(msg==1) // si la connexion en php a fonctionnée
					{
						$("div#box-stock").html("<p id=\"confirmMsg\">Votre demande a bien &eacute;t&eacute enregistrée</p>");
						$("div#error").html("");
					}
					else if(msg==2) // si la connexion en php n'a pas fonctionnée
					{
						$("div#error").html("<span style='color:red'>Votre adresse mail a d&eacute;j&agrave; &eacute;t&eacute; ajout&eacute;e &agrave; notre liste d'attente</span>");
					}
					else if(msg==3) // si la connexion en php n'a pas fonctionnée
					{
						$("div#error").html("<span style='color:red'>Cette adresse mail n'est pas valide. Veuillez réessayer.</span>");
					}
					else // si la connexion en php n'a pas fonctionnée
					{
						$("div#error").html("<span style='color:red'>D&eacute;sol&eacute;, votre demande est &eacute;ronn&eacute;e, veuillez recommencer</span>");
					}
			   }
			});
			return false; // permet de rester sur la même page à la soumission du formulaire
		});
	});
</script>
<link rel='stylesheet' media='screen' href='css/catalogue.css' />
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->
<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Catalogue</title>
<?php

$title = '';
$description = '';
if(isset($_GET['id'])):
	$oArticle = dataManager::ReadOne('ts_catalogue', array(array('id', '=', $_GET['id'])));
	$oCategory = getCategory($oArticle->iCategory);
	$title = $oArticle->sNamei18n;
	$description = (!empty($oArticle->sdescriptioni18n)) ? $oArticle->sdescriptioni18n : $oArticle->sNamei18n;
	$sImage = "/img/catalogue/".$oArticle->sImage;
?>
<fb:share-button class="meta"> 
	<meta name="medium" content="website"/> 
    <meta name="title" content="<?php echo $title; ?>"/> 
    <meta name="description" content="<?php echo $description; ?>"/> 
	<link rel="image_src" href="<?php echo $sImage; ?>"/> 
</fb:share-button> 
<?php endif; ?>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
<?php
include('include/breadcrumb.php');
include('include/menu-catalogue.php');

//Catalogue
if(!isset($_GET['id']) || empty($_GET['id'])):
?>
	<div id='content-pearl'>
<?php
	$iLimit = 32;
	$iPage = (isset($_GET['page'])) ? ($_GET['page']-1)*$iLimit : 0;
	$iCategory = (isset($_GET['cat'])) ? $_GET['cat'] : '';
	$iSubCategory = (isset($_GET['subcat'])) ? $_GET['subcat'] : '';
	
	if(!empty($iSubCategory)): //SHOW SUBCATEGORIES
		$aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1),array('ICategory','=',$iSubCategory)));
	
	elseif(!empty($iCategory)): //SHOW CATEGORIES
		$aIdCat = array(array('bActive','=',1));
		$aCategoryName = dataManager::ReadOne('ts_categorie',array(array('ID','=',$iCategory)));
		$aCategory = dataManager::Read('ts_categorie',array(array('sCategoryi18n','=',$aCategoryName->sCategoryi18n)));
		foreach($aCategory as $iKey => $oCategory):
			if($iKey == 0):
				$aIdCat[1] = array('iCategory','=',$oCategory->ID, 'AND (');
			elseif($iKey == count($aCategory)-1):
				$aIdCat[] = array('iCategory','=',$oCategory->ID,'OR', ')');
			else:
				$aIdCat[] = array('iCategory','=',$oCategory->ID,'OR');
			endif;
		endforeach;
		echo dataManager::Read('ts_catalogue',$aIdCat,null,array($iFirstLimit,$iLimit));
	
	else: //SHOW CATALOGUE
?>
		<h1 style='border-top:1px solid #C7317F; border-bottom:1px solid #C7317F; width:597px; margin:0 0 15px 13px; text-align:center; padding:2px; font-size:16px;'>Notre catalogue</h1>
<?php
		$aArticle = dataManager::Read('ts_catalogue',null,array('sDate','ASC'));
	endif;
	for($i = $iPage; $i<$iPage+$iLimit; $i++):
		if(isset($aArticle[$i])):
			$oArticle = $aArticle[$i];
			include 'include/printPearl.php';
		endif;
	endfor;
	
	$iCounter = sizeof($aArticle); //FUNCTION COUNT LENGHT OF THE ARRAY
	echo pageRead::pagination($iCounter, $iLimit);
?>
		</div>
	</div>
<?php 
else:

/////////////////////////////////////////////
/////////////////Article/////////////////////
/////////////////////////////////////////////
	
	//Ajout dans la base de données de la page pour les pages visitées actuellement
	dataManager::Write('insert', 'ts_page_visite', array(array('sDate',date('Y-m-d H:i:s')),array('iPage',$_GET['id']))); ?>	
	<?php if(isset($_SESSION['id'])): ?>
		<?php $aFavorite = dataManager::Read('ts_favoris', array(array('iClient','=',$_SESSION['id']), array('iArticle','=',$_GET['id']))); ?>	
	<?php else: ?>
		<?php $aFavorite = ''; ?>
	<?php endif; ?>
	<div id="pearl-detail">
		<h1><?php echo $oArticle->sNamei18n ?></h1>
		<div id='form-prix'>

			<?php $oPromo = dataManager::ReadOne('ts_promo', array(array('iArticle', '=', $oArticle->id))); ?>
			<?php if(!empty($oPromo)): ?>
				<?php $iResult = $oArticle->iPrice - ($oArticle->iPrice * $oPromo->iValue / 100); ?>
				<p style='margin-left:0px;'>Prix: <span style='text-decoration:line-through;'><?php echo number_format($oArticle->iPrice, 2, ",", "") ?> &euro; TTC</span> -<?php echo $oPromo->iValue ?> %</p>
				<p style='margin-top:-5px; margin-left:0px;'><span style='color:red'>".number_format($iResult, 2, ",", "")." &euro; TTC</span> <span style='font-weight:900; color:purple'>PROMOTION</span></p>
			<?php else: ?>
				<p style='margin-left:0px;'>Prix: <?php echo number_format($oArticle->iPrice, 2, ",", "") ?> &euro; TTC</p>
			<?php endif; ?>
			
			<form method='post' id='article' enctype='application/x-www-form-urlencoded' name='caddie' action='sys/sql-article.php'>
				<div id='plus-moins'>
					<a href='javascript:javascript' onClick='caddie_plus()'><img src='img/site/plus.jpg' style='border:none' title='plus' alt='plus' /></a>
					<a href='javascript:javascript' onClick='caddie_moins()'><img src='img/site/moins.jpg' style='border:none; margin-top:1px' title='moins' alt='moins' /></a>
				</div>
				<input type='text' size='2' name='quantite' value='1' id='caddiePerle'>
				<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
				<input type='submit' value='Ajouter au caddie' style='margin:-26px 0 0 15px; /margin:-28px 0 0 15px; float:right; background-color:#C7317F; color:white; width:140px;' class='ajout-article'>
			</form>

			<?php if($oArticle->iStock < 1 && $oArticle->bStockSoldOut == 0): ?>
				<?php if(isset($_SESSION['mail'])): ?>
					<?php $sMail = $_SESSION['mail']; ?>
				<?php else: ?>
					<?php $sMail = "Votre adresse mail"; ?>
				<?php endif; ?>
				
				<div id="box-stock">
					<p>Merci de me prévenir quand cet article sera de nouveau en stock</p>
					<div id="error"> </div>
					<form method="POST" name="box-form" id="subscribe-stock">
						<input type="text" id="mail" name="mail" value="<?php echo $sMail; ?>" onclick="this.value=''" />
						<input type="hidden" id="iArticle" name="iArticle" value="<?php echo $_GET['id']; ?>" />
						<input type="submit" class="bouton-classic" value="envoyer" />
					</form>
				</div>
	
			<?php endif; ?>
			
			<ul>
				<?php if(empty($aFavorite)): ?>
					<li><a href='' id='favoriteButton'></a></li>
				<?php endif; ?>
				<li><a name="fb_share" type="button" href="http://www.facebook.com/sharer.php">Partager</a></li>
			</ul>
			
			<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
		</div>
		<div class="catalogue-carre-image page perle">
			<img src="img/site/shadow-left.jpg" style="height: 159px;" />
			<a href="img/zoom/<?php echo $oArticle->sImage ?>" class="pirobox" title="<?php echo $oArticle->sNamei18n ?>">
				<img src="img/catalogue/<?php echo $oArticle->sImage ?>" title="<?php echo $oArticle->sNamei18n ?>" style="height:160px; width:160px; border:none" alt="<?php echo $oArticle->sNamei18n ?>" />
				<img src="img/site/zoom.png" title="<?php echo $oArticle->sNamei18n ?>" alt="<?php echo $oArticle->sNamei18n ?>" style="border:none; margin:0 0 0 -43px" />
			</a>
			<img src="img/site/shadow-right.jpg" style="height: 159px;" />
		</div>

	<div id='description'>
		<p style='margin-top:20px;'>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Catégorie</span>: <?php echo $oCategory->sCategoryi18n; ?>
		</p>
		<p>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Sous-catégorie</span>: <?php echo $oCategory->sSubCategoryi18n ?>
		</p>
		<p>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Poids à l'unité</span>: <?php echo $oArticle->iWeight ?> gr.
		</p>
		<p>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Coloris</span>: <?php echo $oArticle->sColori18n ?>
		</p>
		<p>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Mati&egrave;re</span>: <?php echo $oArticle->sMateriali18n ?>
		</p>
		<p>
			<img src='img/site/quadri-detail.gif' class='picto description' />
			<span style='text-decoration:underline'>Forme</span>: <?php echo $oArticle->sFormi18n ?>
		</p>
		
		<?php if($oArticle->bStockSoldOut == 0): ?>
			<?php if($oArticle->iStock < 1): ?>
			  	<p style='color:red'>
			  		<img src='img/site/quadri-detail.gif' class='picto description' />
			  		<span style='text-decoration:underline;'>Stock</span>: &eacute;puis&eacute;
			  	</p>	  
			<?php else: ?>
				<p style='color:red'>
					<img src='img/site/quadri-detail.gif' class='picto description' />
					<span style='text-decoration:underline;'>Stock</span>: <?php echo $oArticle->iStock ?>
				</p>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if(!empty($oArticle->sdescriptioni18n)): ?>
			<p>
				<img src='img/site/quadri-detail.gif' class='picto description' />
				<span style='text-decoration:underline'>Commentaire</span>: <?php echo stripslashes($oArticle->sdescriptioni18n) ?>
			</p>
		<?php endif; ?>
	</div>

	<?php $aPatron = dataManager::Read('ts_patron_link', array(array('iArticle','=',$oArticle->ID)), null, null, array(array('INNER JOIN', 'ts_patron',array(array('iPatron','=','ts_patron.ID'))))); ?>
	<?php echo (!empty($aPatron)) ?  '<h1 style="margin-top:20px">Patrons utilisant cet article</h1>' : ''; ?>
	<?php foreach($aPatron as $oPatron): ?>
		<div class='patron-box'>
			<a href='patron.php?id=<?php echo $oPatron->ID ?>'>
				<img src='img/patron/image/<?php echo $oPatron->sImage; ?>' width=90' height='90' style='border:none'/>
			</a>
		</div>
	<?php endforeach;?>
	
	<?php $aOrder = dataManager::Read('ts_commande_article',array(array('iArticle','=',$_GET['id'])),'random'); ?>
	<?php if(!empty($aOrder)): ?>
		<?php $aListArticle = array(); ?>
		<?php $aListId = array(); ?>
		<?php $i = 0; ?>
		<?php foreach($aOrder as $oOrder): ?>
			<?php $aNewList = dataManager::Read('ts_commande_article',array(array('iOrder','=',$oOrder->iOrder),array('iArticle','<>',$_GET['id']))); ?>
			<?php $aListArticle = array_merge($aListArticle,$aNewList); ?>
		<?php endforeach; ?>
		
		<div class="clearer"></div>
		
		<?php if(!empty($aListArticle)): ?>
			<div id='link'>
				<h1>Les clients qui ont achet&eacute;s cette pi&egrave;ce ont également achet&eacute;:</h1>
				<?php shuffle($aListArticle); ?>
				<?php foreach($aListArticle as $oListArticle): ?>
					<?php 
						if($i == 4){
							break;
						}
					?>
					<?php $oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$oListArticle->iArticle))); ?>
					<div class='patron-box'>
						<a href='catalogue.php?id=<?php echo $oArticle->ID ?>'>
							<img class='mini image' src='img/catalogue/<?php echo $oArticle->sImage ?>' width=90' height='90' title='<?php echo addslashes($oArticle->sNamei18n) ?>'  alt='<?php echo addslashes($oArticle->sNamei18n) ?>' />
						</a>
					</div>
					<?php $i++; ?>
				<?php endforeach;?>
					<div class="clearer"></div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div>
<?php include('include/footer.php'); ?>