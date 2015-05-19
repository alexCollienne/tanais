<?php $aListCategory = array(); ?>
<div id="menu-catalogue">
	<ul class="niveau1" style="float:left; margin-bottom:60px">
		<li class='sousmenu cat'><a href='catalogue.pdf' class='categorie' style="text-align:center;" target="_blank"> - catalogue PDF - </a>	
		<?php $aMenuCategory = dataManager::Read('ts_categorie', null, array('sCategoryi18n', 'ASC')); ?>
		<?php foreach($aMenuCategory as $oMenuCategory): ?>
			<?php if(!in_array($oMenuCategory->sCategoryi18n, $aListCategory)): ?>
				<li class='sousmenu cat'><a href="catalogue.php?cat=<?php echo $oMenuCategory->ID ?>" class="categorie"><?php echo $oMenuCategory->sCategoryi18n ?></a>
					<ul class='niveau2'>
						<?php $aSubCategory = dataManager::Read('ts_categorie', array(array('sCategoryi18n', '=', $oMenuCategory->sCategoryi18n)), array('sSubCategoryi18n', 'ASC')); ?>
						<?php foreach($aSubCategory as $oSubCategory): ?>
							<?php $aArticle = dataManager::Read('ts_catalogue',array(array('iCategory','=',$oSubCategory->ID))); ?>
							<?php if(!empty($aArticle)): ?>
								<li class='sousmenu'><a href="catalogue.php?subcat=<?php echo $oSubCategory->ID ?>" class="sous_categorie"><?php echo $oSubCategory->sSubCategoryi18n ?></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</li>
				<?php $aListCategory[] = $oMenuCategory->sCategoryi18n; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>