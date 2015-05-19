<?php 
	include('sys/session.php');
	include('include/header.php'); 
?>
	<title>Magasin Tana&iuml;s - Perlerie | Site de vente en ligne<?php echo " | ".$_GET['page'] ?></title>
</head>

<body>
<?php include('include/contentHead.php'); ?>
<div id="content">
<?php
/*Boucle d'envoi d'un commentaire et affichage du message de confirmation
if(isset($_POST['submitForm'])):
	$iClient = (isset($_SESSION['id'])) ? $_SESSION['id'] : '';
	$aValues = array(array('iVideo',$_GET['id']),array('iClient',$iClient),array('sDate',date('Y-m-d H:i:s')));
	foreach($_POST as $sKey => $sValue):
		if($sKey != 'submitForm')
		$aValues[] = array($sKey,$sValue);
	endforeach;
	dataManager::Write('insert','ts_video_comment',$aValues);
endif;
*/
?>

<?php if(isset($_GET['id'])): ?>
	<div id="lastVideo">
		<p style='text-align:center; padding:5px 0; margin:0 0 10px 0; font-size:16px; background:#C7317F; color:#FFF'>Derni&egrave;res vid&eacute;os</p>
		<?php $aLastVideo = dataManager::Read('ts_video',null,array('sDate','DESC'),array(0,5)) ?>
		<?php foreach($aLastVideo as $oLastVideo): ?>
			<div class='image-video'>
		   		<a href='cours.php?id=<?php echo $oLastVideo->ID ?>'>
					<img src='img/video/<?php echo $oLastVideo->sImage ?>' alt='<?php echo $oLastVideo->sNamei8n ?>' title='<?php echo $oLastVideo->sNamei8n ?>'  width='160' height='90' />
					<img src='img/site/play.png' class="play" />
				</a>
			</div>
		<?php endforeach; ?>
	</div>

	<?php $aVideo = dataManager::Read('ts_video',array(array('ID','=',$_GET['id']))); ?>
	<?php $oVideo = $aVideo[0] ?>
    <h1 style='font-size:20px; font-weight:bold; margin:0 0 20px 0;'><?php echo $oVideo->sNamei18n ?></h1>
	<p style='margin:15px 0;'><?php echo $oVideo->sDescriptioni18n ?></p>
    <?php echo $oVideo->sHtml ?>
	
	<?php $aComment = dataManager::Read('ts_video_comment',array(array('iVideo','=',$_GET['id']))) ?>
	<?php foreach($aComment as $oComment): ?>
		<p class="video comment title">
	    <?php 
		if($oComment->sMail != "" && $oComment->sName != ""):
			echo "<a href='mailto:".$oComment->sMail."'>".$oComment->sName."</a>, le ".formatDate($oComment->sDate);
		elseif($oComment->sName != ''):
			echo "<span>".$oComment->sName."</span>, le ".formatDate($oComment->sDate);
		else:
			echo "<span>Inconnu</span>, le ".formatDate($oComment->sDate);
		endif; 
		?>
		</p>
		<p class="video comment"><?php echo $oComment->sComment; ?></p>
	<?php endforeach; ?>
    <h2 style="margin: 20px 0 10px 0;">Laissez un commentaire</h2>
	<form action="" method="post" name="video_comment" enctype="multipart/form-data">
	    <table id="comment">
            <tr>
            	<td><p>Nom</p></td>
            	<td><input type="text" name="sName" size="51" value="<?php echo (isset($_SESSION['nom'])) ? $_SESSION['nom']." ".$_SESSION['prenom'] : '' ?>" /></td>
            </tr>
            <tr>
            	<td><p>Mail</p></td>
            	<td><input type="text" name="sMail" size="51" value="<?php echo (isset($_SESSION['mail'])) ? $_SESSION['mail'] : '' ?>" /></td>
            </tr>
            <tr>
            	<td valign="top"><p>Commentaire</p></td>
            	<td><textarea name="sComment" cols="50" rows="10"></textarea></td>
            </tr>
            <tr>
            	<td></td>
            	<td><input type="submit" name="submitForm" value="Envoyer" class="btn" /></td>
            </tr>
	    </table>
	</form>
<?php else: ?>
	<?php $aVideo = dataManager::Read('ts_video',null,array('sDate','DESC'))?>
	<?php foreach($aVideo as $oVideo): ?>	
		<div class='image-video'>
			<a href='cours.php?id=<?php echo $oVideo->ID ?>'>
				<img src='img/video/<?php echo $oVideo->sImage; ?>' width='160' height='90' />
				<img src='img/site/play.png' class="play" />
			</a>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
</div>
<?php include('include/footer.php'); ?>