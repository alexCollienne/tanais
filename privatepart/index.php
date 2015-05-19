<?php
	require('../sys/connect.php');
	session_start();
	include('../include/class.php');
	include('../include/function.php');

	$_SESSION['role'] = 'admin';
	
	$_SESSION['url'] = (isset($_SESSION['url'])) ? $_SERVER['REQUEST_URI'] : '../index.php';
	
	if(isset($_POST['lngChoice'])):
		$_SESSION['lng'] = strtoupper($_POST['lngChoice']);
	elseif(isset($_GET['lng'])):
		$_SESSION['lng'] = strtoupper($_GET['lng']);
	else:
		$_SESSION['lng'] = 'FR';
	endif;
	
	if(isset($_GET['ipatron'])):
		$_SESSION['iPatron'] = $_GET['ipatron'];
	endif;
	
	if(isset($_GET['stop-attach'])):
		unset($_SESSION['iPatron']);
	endif;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" media="screen" href="css/style.css" />
<link rel="icon" type="image/x-icon" href=""/>
<title>Perlerie Tana&iuml;s - Partie administrateur</title>
<script type="text/javascript" language="javascript" src="javascript/javascript.js"></script>
<script type="text/javascript" language="javascript" src="javascript/jquery-1.4.2.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/pirobox.1_2.js"></script>
<!-- Load TinyMCE -->
<script type="text/javascript" src="javascript/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('textarea.tinymce').tinymce({

			language : 'fr',
			
			// Location of TinyMCE script
			script_url : 'javascript/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});

		$('#setLng').change(function(){
			parent.location.href = $('#setLng').val();
		});
	});
</script>
<script>
$(document).ready(function(){
	$().piroBox({
		my_speed: 300, //animation speed
		bg_alpha: 0.5, //background opacity
		radius: 4, //caption rounded co	rner
		scrollImage : false, // true == image follows the page _|_ false == image remains in the same open position
						   // in some cases of very large images or long description could be useful.
		slideShow : 'true', // true == slideshow on, false == slideshow off
		slideSpeed : 3, //slideshow 
		pirobox_next : 'piro_next', // Nav buttons -> piro_next == inside piroBox , piro_next_out == outside piroBox
		pirobox_prev : 'piro_prev', // Nav buttons -> piro_prev == inside piroBox , piro_prev_out == outside piroBox
		close_all : '.piro_close' // add class .piro_overlay(with comma)if you want overlay click close piroBox
	});	
})
</script>

</head>

<body>
<div id="header">
	<div id="logo">
		<a href="index.php"><img src="img/header-logo.jpg" height="23" /></a>
	</div>
	<div id="box-menu">
		<?php /*Appelle le menu*/include('php-print/menu.php'); ?>
	</div>
</div>

<div class="clear"> </div>
<!--
<div id="search-box">
	<?php include ('php-print/search-box.php'); ?>
</div>-->


<div id="content">
	<?php

	//Identification du circuit à  exécuter par le distributeur
	$page_admin = isset($_GET['page_admin']) ? $_GET['page_admin'] : 'home';

	//Le distributeur
	switch($page_admin){

		case 'add-object':
			include('add-article.php');
			break;

		case 'catalogue':
			include('catalogue.php');
			break;

		case 'stock':
			include('rappel-stock.php');
			break;	

		case 'category':
			include('category.php');
			break;	
			
		case 'patron':
			include('patron.php');
			break;

		case 'mail':
			include('mail.php');
			break;

		case 'commande':
			include('commande.php');
			break;

		case 'newsletter':
			include('form-newsletter.php');
			break;

		case 'promo':
			include('promo.php');
			break;

		case 'champs':
			include('champs.php');
			break;

		case 'news':
			include('news.php');
			break;

		case 'compteur':
			include('compteur.php');
			break;

		//Affiche le détail des clients
		case 'detail_client':
			include('detail-client.php');
			break;

		//Affiche la liste des clients
		case 'client':
			include('client.php');
			break;

		//Affiche la liste des clients
		case 'bon_achat':
			include('bon-achat.php');
			break;

		//Affiche la liste des clients
		case 'verif_stock':
			include('verif_stock.php');
			break;
		
		//Affiche la liste des vidéo
		case 'video':
			include('video.php');
			break;
			
		//Affiche les option générales
		case 'general':
			include('general.php');
			break;
		
		default:
			include('homepage.php');
			break;
	}

	?>
</div>

<div id="footer">
	<p>Copyright Tana&iuml;s 2010</p>
</div>

<?php

if(@$_GET['reliquat'] == 'true'){
	echo "<script type='text/javascript' language='javascript'>alert('Le bon d\'achat a bien Ã©tÃ© envoyÃ©');</script>";
}

if(@$_GET['reliquat'] == 'false'){
	echo "<script type='text/javascript' language='javascript'>alert('L\'adresse mail n\'existe pas');</script>";
}

?>
</body>
</html>
