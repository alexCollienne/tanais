<?php

	$sMessage = '';
	
	//DELETE THE VIDEO
	if(isset($_GET['delete'])):
		header('Location: index.php?page_admin=video');
		dataManager::Write('delete','ts_video',null,array(array('ID','=',$_GET['id'])));
	endif;

	//ADD OR EDIT
	if(isset($_POST['submitVideo'])):
		$aVideos = array(array('sDate',date('Y-m-d H:i:s')));

		if($_FILES['sImage']['tmp_name'] != ''):
			$file = $_FILES['sImage']['tmp_name'];
			move_uploaded_file($_FILES['sImage']['tmp_name'], '../img/video/"'.$_FILES['sImage']['name']);
			$aVideos[] = array('sImage',$_FILES['sImage']['name']); 
		endif;
		
		foreach($_POST as $sKey => $sValue):
			if($sKey != 'submitVideo' && $sKey != 'sImage' && $sKey != 'lngChoice'):
				$aVideos[] = array($sKey,$sValue);
			endif;
		endforeach;
		
		if(isset($_POST['id'])):
			$bAdd = dataManager::Write('update','ts_video',$aVideos,array(array('ID','=',$_GET['id'])));
		else:
			$bAdd = dataManager::Write('insert','ts_video',$aVideos);
		endif;

		if($bAdd):
			$sMessage = '<p class="greenBox">Votre vid&eacute;o a bien &eacute;t&eacute; enregistr&eacute;e.</p>';
		else:
			$sMessage = '<p class="redBox">Votre vid&eacute;o n\'a pas &eacute;t&eacute; enregistr&eacute;e.</p>';
		endif;
	endif;
?>

<script>
$(document).ready(function() {
	$('.show-box').click(function () {
		$(this).next('.hideBox').slideToggle(500);
		return false;
	});
});
</script>

<h1>Cours vid&eacute;os</h1>
<?php echo $sMessage; ?>
<?php if(!isset($_GET['type']) && !isset($_GET['id'])): ?>

	<?php $aVideo = dataManager::Read('ts_video'); ?>
	
	<ul class='list'>
		<li><a href="?page_admin=video&type=add">Ajouter une vid&eacute;o</a></li>
	</ul>
	
	<?php foreach ($aVideo as $oVideo): ?>
		
		<?php $sImg = (!empty($oVideo->sImage)) ? '<img src="../img/video/'.$oVideo->sImage.'" /><img src="../img/site/play.png" class="play" />' : '<img src="../img/site/play.png" />'; ?>
		
		<div class='imageVideo'>
			<a href='index.php?page_admin=video&modify=true&id=<?php echo $oVideo->ID ?>'>
				<?php echo $sImg ?>
				<span><?php echo stripslashes($oVideo->sName_FR) ?></span>
			</a>
		</div>
		
		<div class="clearer"></div>
	<?php endforeach; ?>
	
<?php else: ?>
	<?php if(isset($_GET['id'])): ?>
		<?php $oVideo = dataManager::ReadOne('ts_video',array(array('ID','=',$_GET['id']))); ?>
	<?php endif; ?>
	<ul class='list'>
		<li><a href="?page_admin=video&type=add">Ajouter une vid&eacute;o</a></li>
		<li><a href="index.php?page_admin=video">Retour aux vidéos</a></li>
	</ul>
	
	<p>Ici, vous pouvez ajouter vos cours vid&eacute;os pour que les clients puissent apprendre &agrave; utiliser les articles vendus sur le site.</p>
	<p>N'h&eacute;sitez donc pas &agrave; sp&eacute;cifier les articles utilis&eacute;s pendant la vid&eacute;o.</p>
	<?php if(isset($_GET['id'])): ?>
		<p class="button"><a href="index.php?page_admin=video&delete=true&id=<?php echo $_GET['id'] ?>">Supprimer cette vidéo</a></p>
	<?php endif; ?>
	<p class="show-box">Lire le tutorial</p>
	
	<ul id="listVideo" class="hideBox">
		<li>Le premier champ est simple, il s'agit juste du nom de votre vid&eacute;o. Celui-ci apparaitra en titre de la page contenant la vid&eacute;o.</li>
		<li>Ensuite, vous devez ajouter une image, le plus simple serait une image montrant un moment de la vid&eacute;o. Pour ce faire, il vous faut un logiciel de manipulation d'image, par exemple, photoshop. Dans un premier temps, vous allez lire la vid&eacute;o et appuyer sur STOP quand vous tombez sur l'image qui vous semble parfaite, vous appuyerez ensuite sur "PRINT SCREEN" sur votre clavier. Allez ensuite dans votre programme de manipulation d'image. Cliquer sur Fichier &gt; Nouveau et s&eacute;lectionner OK. Votre zone de travail apparaitra. Il vous suffira alors de presser "CTRL+V". Voici donc l'image de votre &eacute;cran dans votre zone de travail. S&eacute;lectionnez ensuite l'outil recadrage (C) et tapez, dans les options, 160px de largeur et 90px de hauteur. Utilisez ensuite l'outil recadrage pour s&eacute;lectionner la zone que vous voulez qui sera l'image de la vid&eacute;o apparraissant sur votre site et appuyez sur "ENTER". Pour finir, Sauvegardez votre image en JPG et s&eacute;lectionnez la &agrave; l'aide du formulaire ci-dessous.</li>
		<li>Le code HTML doit &ecirc;tre pris dans le site de vid&eacute;o en ligne o&ugrave; vous avez trouv&eacute; la votre. Pour les dimensions de votre vid&eacute;o, choisissez ce qui approche le plus de 400X500. N'oubliez pas de choisir un cadre rose.</li>
		<li>Les commentaires sont l&agrave; pour expliquer la vid&eacute;o et surtout donner les liens aux clients pour les diff&eacute;rents articles utilis&eacute;s. Pour mettre un lien vers un article, reprenez le squelette ci-dessous.</li>
		<li class="redBox">Articles utilis&eacute;s dans la vid&eacute;o:<br />&lt;p&gt; &lt;a href="index.php?page=catalogue&id=..."&gt;Nom de l'article&lt;/a&gt; &lt;/p&gt;</li>
		<li><strong>A la place des trois petits points, allez sur la page de l'article en question et faites un copier/coller de l'id pr&eacute;sent dans la barre d'URL. L'id, c'est la suite de chiffres et de lettres.</strong></li>
		<li>Les tags sont des mots cl&eacute;s qui permettront aux clients de retrouver cette vid&eacute;o &agrave; l'aide d'un moteur de recherche. Veuillez s&eacute;parer chaque mot cl&eacute; d'un	espace.</li>
	</ul>
	
	<form name="add-video" enctype="multipart/form-data" method="post" class='form-admin'>
		<?php echo getSelectLng($_SERVER['REQUEST_URI']) ?>
		<?php if(isset($_GET['id'])): ?>
			<div>
				<input type="hidden" name="id" value="<?php echo $oVideo->ID; ?>" size="52" />
			</div>
		<?php endif; ?>
		<div>
			<label>Nom</label>
			<input type="text" name="sNamei18n" value="<?php echo (isset($_GET['id'])) ? $oVideo->sNamei18n : ''; ?>" size="52" />
		</div>
		<div>
			<label>Image</label>
			<input type="file" name="sImage" size="45" />
		</div>
		<?php if(isset($_GET['id'])): ?>
			<div>
				<img src="../img/video/<?php echo (isset($_GET['id'])) ? $oVideo->sImage : ''; ?>" width="80" height="45" />
			</div>
			<div>
				<label>La vidéo</label>
				<?php echo $oVideo->sHtml ?>
			</div>
		<?php endif; ?>
		<div>
			<label>Code HTML</label>
			<Textarea name="sHtml" ><?php echo (isset($_GET['id'])) ? $oVideo->sHtml : ''; ?></textarea>
		</div>
		<div>
			<label>Commentaire</label>
			<Textarea name="sDescriptioni18n" class="tinymce"><?php echo (isset($_GET['id'])) ? $oVideo->sDescriptioni18n : ''; ?></textarea>
		</div>
		<div>
			<label>Tags</label>
			<Textarea name="sTagi18n"><?php echo (isset($_GET['id'])) ? $oVideo->sTagi18n : ''; ?></textarea>
		</div>
		<div>
			<input type="submit" value="Enregistrer" name="submitVideo" />
		</div>
	</form>
<?php endif; ?>
<div class="clear"> </div>