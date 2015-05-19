<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>

<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Login oublié</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<p>Veuillez inscrire votre adresse mail ci-dessous, vous recevrez alors un mail dans lequel sera indiqu&eacute; votre mot de passe.</p>
	<form enctype="application/x-www-form-urlencoded" method="post" action="sys/login-lose.php" name="loginLose" onsubmit="return validLoginLose()">
		<input type="text" name="mail" value="Votre adresse mail" onClick="this.value = ''" style="width:300px; text-align:center; margin-left:150px;" />
		<input type="submit" value="ok" style="background-color:#C7317F; color:white; margin-left:-5px; font-size:14px; width:26px; text-align:center;" />
	</form>
</div>
<?php include('include/footer.php'); ?>