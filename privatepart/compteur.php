<?php

$iDay = 3600*24;
$iTenDayBefore = time() - 11*$iDay;
$iYesterday = time() - $iDay;
$sFirstDate = date('Y-m-d',$iTenDayBefore);
$sLastDate = date('Y-m-d',$iYesterday);

$iCount = dataManager::Count('ts_compteur', 'DISTINCT IP_client',array(array('sDate','>',$sFirstDate),array('sDate','<',$sLastDate)));
$iMoyenne = round($iCount/10);

?>

<h1>Compteur de visites</h1>
<div class="blueBox">
	<p>Moyenne journali&egrave;re actuelle: <?php echo $iMoyenne; ?></p>
</div>
<h2 style="margin:0 0 10px;">D&eacute;tails des visites durant les 50 derniers jours</h2>
<table style="float:left; margin:0 20px 0 0;">
	<tbody>
		<thead>
			<tr>
				<th>Mois</th>
				<th>Nombre de visiteurs uniques</th>
			</tr>
		</thead>
<?php

//Mensuel
$flag = True;
$annee = date('Y');
$mois= date('m');

for($i=$mois; $i>=02 || $annee > 2009; $i--){
	
	$class = ($flag) ? 'even' : 'odd';
	if(($annee !== date('Y') || $i !== $mois) && $i < 10){
		$i = "0".$i;	
	}
	
	$iCount = dataManager::Count('ts_compteur', 'DISTINCT IP_client',array(array('sDate','LIKE',$annee.'-'.$i.'%')));
	echo '<tr class="'.$class.'"><td>'.$annee."-".$i.'</td><td>'.$iCount.'</td></tr>';
	
	if($i == 01){
		$annee --;
		$i = 13;
	}
	$flag = !$flag;
}

?>
	<tbody>
</table>

<table>
	<thead>
		<tr>
			<th>Date</th>
			<th>Nombre de visites</th>
		</tr>
	</thead>
	<tbody>
	<?php
	
	//Journalier	
	$flag = True;
	for($i=0;$i<=50;$i++):
		$class = ($flag) ? 'even' : 'odd';
		$iCalculateDay = time() - $i*$iDay;
		$sDate = date('Y-m-d',$iCalculateDay);
		$iCount = dataManager::Count('ts_compteur', 'DISTINCT IP_client', array(array('sDate','=',$sDate)));
	?>
		<tr class="<?php echo $class ?>">
			<td><?php echo $sDate ?></td>
			<td><?php echo $iCount ?></td>
		</tr>
		<?php $flag = !$flag; ?>
	<?php endfor; ?>
	<tbody>
</table>