<ul id="menu">
    <?php

    	//Affichage du GIF new
            	
    $aPage = array("catalogue", "promo", "bijoux", "patron");
	$new = array('','','','');
        
    foreach($aPage as $iKey => $sPage):
    	$iDay = date("d")-4;
        $sNewsDate = date("Y-m-d", mktime(0, 0, 0, date('m'), $iDay ,date("Y")));
              
		if($sPage == 'bijoux'):
			$aNewArticle = dataManager::Read('ts_catalogue',array(array('sDate','>=',$sNewsDate),array('sCategory_FR','=','bijoux')),null,null,array(array('INNER JOIN','ts_categorie',array(array('ts_catalogue.iCategory','=','ts_categorie.ID')))));
	
        	if(!empty($aNewArticle)):
               	$aNew[2] = "<img src='img/site/new.gif' style='border:none; margin:0; position:absolute;width:25px' />";
            else:
               	$aNew[2] = '';
            endif;
		else:
			$aNewArticle = dataManager::Read('ts_'.$sPage,array(array('sDate','>=',$sNewsDate)));
			
			if(!empty($aNewArticle)):
				$aNew[$iKey] = "<img src='img/site/new.gif' style='border:none; margin:0; position:absolute;width:25px' />";
			else:
				$aNew[$iKey] = '';
			endif;
		endif;
	endforeach;
?>
  
	<li id='first-child'><a href='index.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'index.php') !== false) ? "class='accueil-select'" : "Class='accueil'" ?>></a></li>
	<li id='second-child'><a href='catalogue.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'catalogue.php') !== false) ? 'class="select"' : ''; ?>>Catalogue<?php echo $aNew[0] ?></a></li>
	<li id='second-child'><a href='nouveautes.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'nouveautes.php') !== false) ? 'class="select"' : ''; ?>>Nouveautés<?php echo $aNew[1] ?></a></li>
	<li id='second-child'><a href='promo.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'promo.php') !== false) ? 'class="select"' : ''; ?>>Promotion<?php echo $aNew[2] ?></a></li>
	<li id='second-child'><a href='bijoux.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'bijoux.php') !== false) ? 'class="select"' : ''; ?>>Bijoux<?php echo $aNew[3] ?></a></li>
	<li id='second-child'><a href='patron.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'patron.php') !== false) ? 'class="select"' : ''; ?>>Patrons</a></li>
	<li id='second-child'><a href='cours.php' <?php echo (strpos($_SERVER["REQUEST_URI"], 'cours.php') !== false) ? 'class="select"' : ''; ?>>Cours vidéos</a></li>
	<li id='second-child'><a href='contact.php' style='border-right:1px solid #FFFFFF; 	padding:0 37px;' <?php echo (strpos($_SERVER["REQUEST_URI"], 'contact.php') !== false) ? 'class="select"' : ''; ?>>Contact</a></li>
</ul>