<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>
<link rel='stylesheet' media='screen' href='css/catalogue.css' />		
<!--[if IE]><link rel='stylesheet' media='screen' href='css/catalogue-ie.css' /><![endif]-->
<!--[if IE 6]><link rel='stylesheet' media='screen' href='css/catalogue-ie6.css' /><![endif]-->
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Nouveautés</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
<?php
		$aArticle = dataManager::Read('ts_catalogue',array(array('bActive','=',1)),array('sDate','DESC'),array(0,50));
		foreach($aArticle as $oArticle):
			include 'include/printPearl.php';
		endforeach;
?>
</div>
<?php include('include/footer.php'); ?>