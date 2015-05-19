<table>
	<thead>
		<tr>
			<th>Nom</th>
			<th>Stock actuel</th>
			<th>Stock minimum d&eacute;fini</th>
			<th>Fournisseur</th>
		</tr>
	</thead>
	<tbody>
<?php

$flag = true;
$aArticle = dataManager::Read('ts_catalogue',array(array('iStock','<=',0),array('iStock','<=','iStockMin')),array('sName_FR','ASC'));
foreach($aArticle as $oArticle):

	$sClass = ($flag) ? 'even' : 'odd';
?>
	<tr class='<?php echo $sClass ?>'>
		<td><a href='index.php?page_admin=catalogue&id=<?php echo $oArticle->ID ?>'><?php echo $oArticle->sName_FR ?></a></td>
		<td><?php echo $oArticle->iStock ?></td>
		<td><?php echo $oArticle->iStockMin ?></td>
		<td><?php echo $oArticle->sProvider ?></td>
	</tr>
<?php 	
	$flag = !$flag;
endforeach;

?>
	</tbody>
</table>