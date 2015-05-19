<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>

<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne | Accueil</title>
</head>

<body>

<?php include('include/contentHead.php'); ?>

<div id="content">
	<h2>Frais de port</h2>
	<p>Les frais de port sont gratuits pour la Belgique &agrave; partir de 35&euro; et de 50&euro; pour l'&eacute;tranger.</p>
	<p>Pour les montants inférieurs, cela dépend du poids total de votre commande.</p>
	<table id="fraisDePort">
		<thead>
			<tr>
				<th>Poids</th>
				<th>Prix en Belgique</th>
				<th>Prix à l'étranger</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td> &lt; 100g. </td>
				<td> 1,68 &euro;</td>
				<td> 3,50 &euro;</td>
			</tr>
			<tr>
				<td> 100g. &lt;&gt; 350 g. </td>
				<td> 2.27 &euro;</td>
				<td> 5,90 &euro;</td>
			</tr>
			<tr>
				<td> 350g. &lt;&gt; 1 kg. </td>
				<td> 3,45 &euro;</td>
				<td> 9,50 &euro;</td>
			</tr>
			<tr>
				<td> 1kg. &lt;&gt; 2kg. </td>
				<td> 4,63 &euro;</td>
				<td> 19 &euro;</td>
			</tr>
		</tbody>
	</table>
</div>	
<?php include('include/footer.php'); ?>