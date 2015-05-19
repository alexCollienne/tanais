<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>
<link rel='stylesheet' media='screen' href='css/catalogue.css' />
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Recherche</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<?php if(isset($_POST['recherche']) || isset($_GET['recherche'])): ?>
		<?php 
		if(isset($_POST['recherche'])):
			$sWord = htmlentities($_POST['recherche']);	
		endif;
		
		if(isset($_GET['recherche'])):
			$sWord = $_GET['recherche'];	
		endif;
		?>
		<?php $aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1),array('sNamei18n','LIKE','%'.$sWord.'%'),array('sTagi18n','LIKE','%'.$sWord.'%','OR'),array('sDescriptioni18n','LIKE','%'.$sWord.'%','OR'))); ?>
		<?php if(empty($aArticle) || empty($sWord)): ?>
			<p id='search-text'>Aucune recherche trouv&eacute;e pour le mot <span style='font-weight:900'><?php echo $sWord ?></span></p>
		<?php else: ?>
			<p id='search-text'>Recherche(s) trouv&eacute;e(s) pour le mot <span style='font-weight:900'><?php echo $sWord ?></span></p>
			<div id='search-result'>
				<?php foreach ($aArticle as $oArticle): ?>
					<?php include 'include/printPearl.php'; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div id='search-result'>
			<?php $aArticle = dataManager::Read('ts_catalogue',array(array('sColorSearch','LIKE','%'.$_GET['couleur'].'%'))); ?>
			<?php foreach ($aArticle as $oArticle): ?>
				<?php include 'include/printPearl.php'; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<?php include('include/footer.php'); ?>