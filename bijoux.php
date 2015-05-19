<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>

<link rel='stylesheet' media='screen' href='css/catalogue.css' />
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->	
<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Bijoux</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<?php $aJewellery = dataManager::Read('ts_categorie',array(array('sCategory_FR','=','bijoux'))); ?>
	<div>
		<?php foreach($aJewellery as $oJewellery): ?>
			<a href="bijoux.php?cat=<?php echo $oJewellery->ID ?>" class="option bijoux" ><?php echo $oJewellery->sSubCategoryi18n ?></a>
    	<?php endforeach; ?>
	</div>
	<div class="clearer"></div>
	<div id='page-bijoux'>
	    <?php if(isset($_GET['cat'])): ?>
			<?php $aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1),array('iCategory','=',$_GET['cat'])),array('sDate','DESC')); ?>
			<?php foreach($aArticle as $oArticle): ?>
				<div id='content-patron'>
					<img src='img/site/shadow-left.jpg' style='width:10px; height:129px;' /><a href='catalogue.php?id=<?php echo $oArticle->ID ?>' title='<?php echo $oArticle->sNamei18n ?>'><img class='mini image' src='img/catalogue/<?php echo $oArticle->sImage ?>' title='<?php echo $oArticle->sNamei18n ?>'  alt='<?php echo $oArticle->sNamei18n ?>' width='129' height='129' style='border:none' /></a><img src='img/site/shadow-right.jpg' style='width:10px; height:129px;' />
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php $aArticle = dataManager::Read('ts_categorie',array(array('bActive','=',1),array('sCategory_FR','=','bijoux')),array('sDate','DESC'),null,array(array('INNER JOIN','ts_catalogue',array(array('ts_categorie.ID','=','iCategory'))))); ?>
			<?php foreach($aArticle as $oArticle): ?>
				<div id='content-patron'>
					<img src='img/site/shadow-left.jpg' style='width:10px; height:129px;' /><a href='catalogue.php?id=<?php echo $oArticle->ID ?>' title='<?php echo $oArticle->sNamei18n ?>'><img class='mini image' src='img/catalogue/<?php echo $oArticle->sImage ?>' title='<?php echo $oArticle->sNamei18n ?>'  alt='<?php echo $oArticle->sNamei18n ?>' width='129' height='129' style='border:none' /></a><img src='img/site/shadow-right.jpg' style='width:10px; height:129px;' />
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
<?php include('include/footer.php'); ?>