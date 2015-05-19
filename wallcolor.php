<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>

<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Accueil</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content" class="floatLeft">
	<script>
		$(document).ready(function() {
			$('#wallColor li').click(function(){

				$.ajax({
					type:'POST',
					url:'article-color.php',
					data:'color=rouge',
					succes : function(msg){
						alert('rouge');
					}
				});
			});
		});
	</script>
	<div id="wallColor">
		<ul>
			<li class="rouge"><img src="img/site/wallcolor_red.jpg" /></li>
			<div id="rouge">test</div>
			<li><img src="img/site/wallcolor_pink.jpg" /></li>
			<li><img src="img/site/wallcolor_purple.jpg" /></li>
			<li><img src="img/site/wallcolor_blue.jpg" /></li>
			<li><img src="img/site/wallcolor_turquoise.jpg" /></li>
			<li><img src="img/site/wallcolor_green.jpg" /></li>
			<li><img src="img/site/wallcolor_yellow.jpg" /></li>
			<li><img src="img/site/wallcolor_orange.jpg" /></li>
			<li><img src="img/site/wallcolor_brown.jpg" /></li>
			<li><img src="img/site/wallcolor_beige.jpg" /></li>
			<li><img src="img/site/wallcolor_white.jpg" /></li>
			<li><img src="img/site/wallcolor_grey.jpg" /></li>
			<li><img src="img/site/wallcolor_black.jpg" /></li>
			<li><img src="img/site/wallcolor_silver.jpg" /></li>
			<li><img src="img/site/wallcolor_copper.jpg" /></li>
			<li><img src="img/site/wallcolor_bronze.jpg" /></li>
			<li><img src="img/site/wallcolor_gold.jpg" /></li>
		</ul>
	</div>
	<div class="clearer"></div>
</div>
<?php include('include/footer.php'); ?>