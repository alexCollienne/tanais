<h1>Catalogue - Liste en attente des stocks</h1>
<table>
	<thead class="odd">
		<tr>
			<th>Article</th>
			<th colspan="2">Adresses mail</th>
		</tr>
	<thead>
	<tbody>
<?php
$flag = True;

$aListArticle = dataManager::Read('ts_rappel_stock',null,null,null,array(array('inner join','ts_catalogue',array(array('iArticle','=','ts_catalogue.ID')))),array('DISTINCT','iArticle'));
foreach($aListArticle as $oIdArticle):
	$sClass = ($flag) ? 'even' : 'odd';
	$oArticle = dataManager::ReadOne('ts_catalogue',array(array('ID','=',$oIdArticle->iArticle)));
	echo '<tr class="'.$sClass.'">';
	echo '<td>';
	$aMail = dataManager::Read('ts_rappel_stock',array(array('iArticle','=',$oIdArticle->iArticle)));
	foreach($aMail as $iKey => $oMail):
		echo ($iKey != 0) ? ', ' : '';
		echo $oMail->sMail;
	endforeach;
	echo '</td>';
	$iCount = dataManager::Count('ts_rappel_stock', 'DISTINCT sMail', array(array('iarticle','=',$oIdArticle->iArticle)));
	echo '<td><a href="?page_admin=catalogue&id='.$oArticle->iArticle.'">'.$oArticle->sName_FR.'</a></td><td>'.$iCount.'</td>';
	echo '</tr>';
	$flag = !$flag;
endforeach;
?>
	</tbody>
</table>