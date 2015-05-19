<?php 
include('sys/session.php');
include('include/header.php'); 

if(isset($_POST['sendMessage'])):
	$entete ='From: '.$_POST["nom"].'<'.$_POST["mail"].'>'."\n";
	$entete .='MIME-Version: 1.0'."\n";
	$entete .='Content-Type:text/html;charset=iso-8859-1'."\n";
	$entete .='Content-Transfer-Encoding: 8bit'."\n";
	
	$sujet = 'Message d\'un client: '.$_POST['sSubject'];
	
	mail('serviceclient@tanais.be', $sujet, stripslashes($_POST['sContent']), $entete, "-finfo@tanais.be");
	if(mail('info@tanais.be', $sujet, stripslashes($_POST['textarea']), $entete, "-fserviceclient@tanais.be")):
		$sMessage = '<p class="confirmMessage">Votre mail nous est bien parvenu. Nous vous répondrons dans les plus brefs délais.</p>';
	else:
		$sMessage = '<p class="errorMessage">Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer</p>';
	endif;
endif;

?>

<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Contact</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
<?php

if($_SERVER['REQUEST_URI'] == "contact.php"){
	echo "Votre message a bien été envoyé";
}

?>
<h1>Contact</h1>

<h2>Nos donn&eacute;es de contact</h2>
<p><strong>Tana&iuml;s</strong></p>
<p>Rue de Velaine, 95</p>
<p>5300 Landenne (Andenne)</p>
<p>Belgique</p>
<p>0032(0)85/82.82.38</p>
<p>Email : <a href="mailto:info@tanais.be">info@tanais.be</a></p>
<p>Numéro de TVA : BE0657.716.814</p><br /><br />
<p>Si vous voulez nous envoyez des recommandations accompagn&eacute;es d'images, n'h&eacute;sitez pas &agrave; nous contacter via l'adresse suivante: <a href="mailto:serviceclient@tanais.be" style="color:#c7317f">serviceclient@tanais.be</a></p>

<h2>Nous contacter par mail</h2>
<form action="" method="post">
	<table style="margin:20px 0">
	    <tr>
	      <td width="100"><p>Nom</p></td>
	      <td><input name="nom" size="64" value="<?php echo (isset($_SESSION['id'])) ? $_SESSION['prenom'].' '.$_SESSION['nom'] : '' ?>" /></td>
	    </tr>
	    <tr>
	      <td><p>Adresse mail</p></td>
	      <td><input name="mail" size="64" value="<?php echo (isset($_SESSION['id'])) ? $_SESSION['mail'] : '' ?>" /></td>
	    </tr>
	    <tr>
	      <td><p>Sujet</p></td>
	      <td><input name="sSubject" size="64" /></td>
	    </tr>
	    <tr>
	    	<td valign="top"><p>Texte</p></td>
	        <td colspan="2"><textarea name="sContent" rows="10" cols="64"></textarea></td>
	    </tr>
	    <tr>
	    	<td></td>
	    	<td><input type="submit" value="Envoyer" class="btn submit" name="sendMessage" style="margin-left:0px;" /></td>
	    </tr>
	</table>
</form>

<h2>Notre plan</h2>
<iframe width="790" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.fr/maps?f=q&amp;source=s_q&amp;hl=fr&amp;geocode=&amp;q=Rue+de+Velaine,+95++Andenne,+R%C3%A9gion+Wallonne,+Belgique&amp;sll=50.517905,5.075152&amp;sspn=0.016945,0.045447&amp;ie=UTF8&amp;hq=&amp;hnear=Rue+de+Velaine+95,+Landenne+5300+Andenne,+Namur,+R%C3%A9gion+Wallonne,+Belgique&amp;z=14&amp;iwloc=r0&amp;ll=50.526142,5.082293&amp;output=embed"></iframe>
</div>
<?php include('include/footer.php'); ?>