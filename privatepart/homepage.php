<script>
$(document).ready(function() {
	$("#link").click(function () {
		$("#list").slideToggle(500);
		return false;
	});
});
</script>
<?php
$aVisits = dataManager::Read('ts_compteur',array(array('sDate','=',date('Y-m-d'))));
$aConnexion = dataManager::Read('ts_session',array(array('sLastConnexion','=',date('Y-m-d'))));
$aInscription = dataManager::Read('ts_session',array(array('sDate','=',date('Y-m-d'))));
$aOrder = dataManager::Read('ts_commande',array(array('sDate','LIKE',date('Y-m-d').'%')));
?>


<h1>Informations du jour - <?php echo date('d/m/Y'); ?></h1>
<p>Nombre de visites: <?php echo count($aVisits); ?></p>
<p>Nombre de connexions: <?php echo count($aConnexion); ?></p>
<p>Nombre d'inscriptions: <?php echo count($aInscription); ?></p>
<p>Nombre de commandes: <?php echo count($aOrder); ?></p>

<?php $iCount = dataManager::Count('ts_commande','ID', array(array('sStatus','=','en_cours'),array('bPrint','=',1))); ?>
<div class="boxRed" style="margin:20px 0 0;">
	<p id="link" class="redBox">Attention, il reste <?php echo $iCount ?> commandes imprimées et non envoyées</p>
	<?php $aOrder = dataManager::Read('ts_commande',array(array('sStatus','=','en_cours'),array('bPrint','=',1))); ?>
	<ul id="list" class="redBox" style="display:none;">
		<?php foreach ($aOrder as $oOrder): ?>
			<li>
				<a href="index.php?page_admin=commande&id=<?php echo $oOrder->ID ?>"><?php echo $oOrder->sOrderCode ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>