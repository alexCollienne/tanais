<?php 
	include('sys/session.php');
	include('include/header.php');
	
	$oNews = dataManager::ReadOne('ts_news',array(array('sNewsi18n','!=','')), array('sDate', 'DESC'), array(0,1));
	if(!empty($oNews)):
		$bCheckNews = true;
	endif;
	
?>

<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Accueil</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content" class="floatLeft">
	<div id="flash">
		<img src="img/site/patron-top.jpg" width="800" height="15"/>
		<?php if($bCheckNews): ?>
			<div id="boxNews">
				<p><?php echo $oNews->sNewsi18n; ?></p>
			</div>
		<?php else: ?>
			<div style="height:114px; margin:0 0 0 -6px; /margin:-5px 0 0 -6px">
				<a href="promo.php">
					<img style="border:none" src="img/site/banner-hivers.jpg" />
				</a>
			</div>
		<?php endif; ?>
		<img src="img/site/patron-bottom.jpg" width="800" height="15"/>
	</div>

	<div id="succes">
		<h2 style="width:250px">Aper&ccedil;u du catalogue</h2>
		<?php
			$tableau = array("crystal", "cuivre", "bronze", "argent", "doré", "rouge", "fushia", "rose", "mauve", "bleu", "turquoise", "vert", "jaune", "orange", "brun", "beige", "blanc" , "gris", "noir"); 
			$result = array_rand($tableau); 
			$sColor = $tableau[$result]; 
		?>
		<?php $aArticle = dataManager::Read('ts_catalogue', array(array('bActive','=',1), array('sColorSearch','LIKE','%'.$sColor.'%')), 'random', array(0,6)) ?>
		<?php foreach($aArticle as $oArticle): ?>
			<?php //WideImage::load('img/catalogue/'.$oArticle->sImage)->crop(0,0,95, 95)->saveToFile($oArticle->sImage); ?>
			<div style='float:left; background:url(img/site/bckg-nouveaute.png) no-repeat; margin:0 10px 10px 0px; padding:6px 0 0 6px; width:107px; height:106px'>
				<a href="catalogue.php?id=<?php echo $oArticle->ID ?>">
					<img src='img/catalogue/<?php echo $oArticle->sImage; ?>' width='95' height='95' alt="<?php echo $oArticle->sNamei18n ?>" title="<?php echo $oArticle->sNamei18n ?>" style='border:none' />
				</a>
			</div>
		<?php endforeach; ?>
		
	</div>
	
	<div id="nouveaute">
		<h2 style="width:150px">Nouveaut&eacute;s</h2>
		<?php $aNewArticle = dataManager::Read('ts_catalogue', array(array('bActive','=',1)), array('sDate', 'DESC'), array(0,6)) ?>
		<?php foreach($aNewArticle as $oNewArticle): ?>
			<div style='float:left; background:url(img/site/bckg-nouveaute.png) no-repeat; margin:0 10px 10px 0px; padding:6px 0 0 6px; width:107px; height:106px'>
				<a href="catalogue.php?id=<?php echo $oNewArticle->ID ?>"> 
					<img src='img/catalogue/<?php echo $oNewArticle->sImage ?>' width='95' height='95' alt="<?php echo $oNewArticle->sNamei18n ?>" title="<?php echo $oNewArticle->sNamei18n ?>" style='border:none' />
				</a>
			</div>
		<?php endforeach; ?>
		
	</div>

	<div class="clearer"></div>

	<ul id="news">
		<li><a href="blog/?p=125" target="_blank"><img src="img/site/carre-atelier.png" /></a></li>
		<li><a href="catalogue.pdf" target="_blank"><img src="img/site/carre-catalogue.png" /></a></li>
		<li><a href="http://www.facebook.com/#!/profile.php?id=100000523597559" target="_blank"><img src="img/site/carre-facebook.png" /></a></li>
		<li class="last"><a href="cours.php"><img src="img/site/carre-video.png" /></a></li>
	</ul>

	<div class="clearer"></div>
	
	<div id="vente1">
		<h2 style="width:340px">Articles visit&eacute;s actuellement</h2>
		<?php $aPage = dataManager::Read('ts_page_visite', null, array('sDate', 'DESC'), array(0,5), null, array('DISTINCT', 'iPage')); ?>
		<?php foreach($aPage as $oPage): ?>
			<?php $oArticle = dataManager::ReadOne('ts_catalogue', array(array('ID', '=', $oPage->iPage))); ?>
			<?php //WideImage::load('img/catalogue/'.$oArticle->sImage)->crop(0,0, 129, 129)->saveToFile($oArticle->sImage); ?>
			<div class="pearl-box">
				<div class="catalogue carre-block">
					<img src='img/site/shadow-left.jpg' class='image border' />
					<a href="catalogue.php?id=<?php echo $oArticle->ID ?>"><img class="mini image" src="img/catalogue/<?php echo $oArticle->sImage ?>" title="<?php echo $oArticle->sNamei18n ?>"  alt="<?php echo $oArticle->sNamei18n ?>" /></a>
					<img src='img/site/shadow-right.jpg' class='image border' />
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<div class="clearer"></div>
	
	<div id="rea">
		<h2 style="width:200px">Nos bijoux</h2>
		<?php $aArticle = dataManager::Read('ts_categorie', array(array('sCategory_FR', '=', 'bijoux')), 'random', array(0,5), array(array('INNER JOIN', 'ts_catalogue', array(array('ts_categorie.ID', '=', 'ts_catalogue.iCategory'))))); ?>
		<?php foreach($aArticle as $oArticle): ?>
			<?php //WideImage::load('img/catalogue/'.$oArticle->sImage)->crop('center','center',129, 129)->saveToFile($oArticle->sImage); ?>
			<div class="pearl-box">
				<div class="catalogue carre-block">
					<img src='img/site/shadow-left.jpg' class='image border' />
					<a href="catalogue.php?id=<?php echo $oArticle->ID ?>">
						<img class="mini image" src="img/catalogue/<?php echo $oArticle->sImage ?>" title="<?php echo $oArticle->sNamei18n ?>"  alt="<?php echo $oArticle->sNamei18n ?>" /></a>
					<img src='img/site/shadow-right.jpg' class='image border' />
				</div>
			</div>
		<?php endforeach; ?>
	</div>	
</div>
<?php include('include/footer.php'); ?>