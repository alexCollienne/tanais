<?php

class dataRead{
	
	 public function __get($sProperty){
		if(strpos($sProperty,'i18n')){
			$sVar = str_replace('i18n','_'.$_SESSION['lng'],$sProperty);
			if(empty($this->$sVar) && $_SESSION['role'] != 'admin'){
				$sVar = str_replace('_'.$_SESSION['lng'],'_FR',$sVar);
			}
			return $this->$sVar;
		}
		
	}
}

class pageRead{
	function pagination($iCount, $iLimit){
		$sPagination = '<ul id="pagination">';
		$iQuantityPage = $iCount / $iLimit + 1;
		for($i=1;$i<=$iQuantityPage;$i++){
			if(strpos($_SERVER['REQUEST_URI'],'.php?')):
				if(isset($_GET['page'])):
					$sUrl = str_replace('page='.$_GET['page'], 'page='.$i, $_SERVER['REQUEST_URI']);
				else:
					$sUrl =  $_SERVER['REQUEST_URI'].'&page='.$i;
				endif;
			else:
				$sUrl =  $_SERVER['REQUEST_URI'].'?page='.$i;
			endif;
			$sClass = (isset($_GET['page']) && $i == $_GET['page']) ? ' class="current"' : '';
			$sPagination .= '<li'.$sClass.'><a href="'.$sUrl.'">'.$i.'</a></li>';
		}
		$sPagination .= '</ul>';
		$sPagination .= '<div class="clearer"></div>';
		return $sPagination;
	}
}

class dataManager{
	
	function Read($sTable, $aOptions = '', $aOrder = '', $aLimit = '', $aJoin = '', $aTypeSelect = ''){
	
		$aArray = array();
		$bSecondOption = false;
		$bSecondJoinOption = false;
		if(!empty($aTypeSelect)){
			$sSql = 'SELECT '.$aTypeSelect[0].'('.selectTrad($aTypeSelect[1]).') FROM '.$sTable;
		}else{
			$sSql = 'SELECT * FROM '.$sTable;
		}
			
		if(!empty($aJoin)){
			foreach($aJoin as $aJoinOn){
				$bSecondJoinOption = false;
				$sSql .= ' '.$aJoinOn[0].' '.$aJoinOn[1].' ON';
				foreach($aJoinOn[2] as $aJoinOption){
					if($bSecondJoinOption):
						$sSql .= (isset($aValue[3])) ? $aValue[3] : ' AND';
					endif;
	
					$sSql .= ' '.selectTrad($aJoinOption[0]).' '.$aJoinOption[1].' '.selectTrad($aJoinOption[2]);
					$bSecondJoinOption = true;
				}
			}
		}
		
		if(!empty($aOptions)){
			$sSql .= ' WHERE';
			foreach($aOptions as $aValue){
				if($bSecondOption):
					$sSql .= (isset($aValue[3])) ? ' '.$aValue[3] : ' AND';
				endif;
				
				$sSql .= ' '.selectTrad($aValue[0]).' '.$aValue[1].' "'.$aValue[2].'"';
				$sSql .= (isset($aValue[4])) ? ' '.$aValue[4] : '';
				$bSecondOption = true;
			}
		}
		
		if(!empty($aOrder)){
			$sSql .= ($aOrder == 'random') ? ' ORDER BY RAND()' : ' ORDER BY '.selectTrad($aOrder[0]).' '.$aOrder[1];
		}
		
		if(!empty($aLimit)){
			$sSql .= ' LIMIT '.$aLimit[0].','.$aLimit[1];
		}
		
		$rSql = mysql_query($sSql) or die('Erreur : '.mysql_error());
		
		while($oSql = mysql_fetch_object($rSql, 'dataRead')){
			$aArray[] = $oSql;
		}
		
		return $sSql;
	}
	
	
	function ReadOne($sTable, $aOptions = '', $aOrder = '', $aLimit = '', $aJoin = '', $aTypeSelect = ''){
	
		$bSecondOption = false;
		$bSecondJoinOption = false;
		if(!empty($aTypeSelect)){
			if($aTypeSelect[0] == 'COUNT'){
				$sSql = 'SELECT '.$aTypeSelect[0].'('.selectTrad($aTypeSelect[1]).') AS count FROM '.$sTable;
			}else{
				$sSql = 'SELECT '.$aTypeSelect[0].'('.selectTrad($aTypeSelect[1]).') FROM '.$sTable;
			}
		}else{
			$sSql = 'SELECT * FROM '.$sTable;
		}
			
		if(!empty($aJoin)){
			foreach($aJoin as $aJoinOn){
				$sSql .= ' '.$aJoinOn[0].' '.$aJoinOn[1].' ON';
				foreach($aJoinOn[2] as $aJoinOption){
					if($bSecondJoinOption):
						$sSql .= (isset($aValue[3])) ? $aValue[3] : ' AND';
					endif;
	
					$sSql .= ' '.selectTrad($aJoinOption[0]).' '.$aJoinOption[1].' '.selectTrad($aJoinOption[2]);
					$bSecondJoinOption = true;
				}
			}
		}
		
		if(!empty($aOptions)){
			$sSql .= ' WHERE';
			foreach($aOptions as $aValue){
				if($bSecondOption):
					$sSql .= (isset($aValue[3])) ? ' '.$aValue[3] : ' AND';
				endif;
				
				$sSql .= ' '.selectTrad($aValue[0]).' '.$aValue[1].' "'.$aValue[2].'"';
				$bSecondOption = true;
			}
		}
		
		if(!empty($aOrder)){
			$sSql .= ($aOrder == 'random') ? ' ORDER BY RAND()' : ' ORDER BY '.selectTrad($aOrder[0]).' '.$aOrder[1];
		}
		
		if(!empty($aLimit)){
			$sSql .= ' LIMIT '.$aLimit[0].','.$aLimit[1];
		}
		
		$rSql = mysql_query($sSql) or die('Erreur : '.mysql_error());
		$oSql = mysql_fetch_object($rSql, 'dataRead');
		
		//Check the value of the object. If it's not an object, the function returns null
		if(!empty($oSql)):
			return $oSql;
		else:
			return null;
		endif;
	}
	
	
	function Write($sType, $sTable, $aValues, $aOptions = ''){
		switch($sType):
			case 'update':
				$sSql = 'UPDATE '.$sTable.' SET';
				foreach($aValues as $iKey => $aValue):
					if($iKey == 0):
						$sSql .= ' '.selectTrad($aValue[0]).' = "'.$aValue[1].'"';
					else:
						$sSql .= ', '.selectTrad($aValue[0]).' = "'.$aValue[1].'"';
					endif;
				endforeach;
				$sSql .= ' WHERE';
				foreach($aOptions as $iKey => $aOption):
					if($iKey == 0):
						$sSql .= ' '.selectTrad($aOption[0]).' '.$aOption[1].' "'.$aOption[2].'"';
					else:
 						$sSql .= (isset($aOption[3])) ? $aOption[3] : ' AND';
						$sSql .= ' '.selectTrad($aOption[0]).' '.$aOption[1].' "'.$aOption[2].'"';
					endif;
				endforeach;
			break;
			
			case 'insert':
				$sSql = 'INSERT INTO '.$sTable.'(';
				foreach($aValues as $iKey => $aValue):
					if($iKey == 0):
						$sSql .= selectTrad($aValue[0]);
					else:
						$sSql .= ', '.selectTrad($aValue[0]);
					endif;
				endforeach;
				$sSql .= ') VALUES(';
				foreach($aValues as $iKey => $aValue):
					if($iKey == 0):
						$sSql .= '"'.$aValue[1].'"';
					else:
						$sSql .= ', "'.$aValue[1].'"';
					endif;
				endforeach;
				$sSql .= ')';
			break;

			case 'delete':
				$sSql = 'DELETE FROM '.$sTable.' WHERE';
				foreach($aOptions as $iKey => $aOption):
					if($iKey == 0):
						$sSql .= ' '.selectTrad($aOption[0]).' '.$aOption[1].' "'.$aOption[2].'"';
					else:
 						$sSql .= (isset($aOption[3])) ? $aOption[3] : ' AND';
						$sSql .= ' '.selectTrad($aOption[0]).' '.$aOption[1].' "'.$aOption[2].'"';
					endif;
				endforeach;
			break;
			
		endswitch;
		
		return mysql_query($sSql) or die('Erreur : '.mysql_error());
	}
	
	function Count($sTable, $sColumn, $aOptions = ''){
		$bSecondOption = false;
		$sSql = 'SELECT COUNT('.$sColumn.') FROM '.$sTable;
		
		if(!empty($aOptions)){
			$sSql .= ' WHERE';
			foreach($aOptions as $aValue){
				if($bSecondOption):
					$sSql .= (isset($aValue[3])) ? ' '.$aValue[3] : ' AND';
				endif;
				$sSql .= ' '.selectTrad($aValue[0]).' '.$aValue[1].' "'.$aValue[2].'"';
				$bSecondOption = true;
			}
		}
		
		$aCount = mysql_fetch_array(mysql_query($sSql));
		return $aCount[0];
	}

	function Sum($sTable, $sColumn, $aOptions = ''){
		$bSecondOption = false;
		$sSql = 'SELECT SUM('.$sColumn.') FROM '.$sTable;
		
		if(!empty($aOptions)){
			$sSql .= ' WHERE';
			foreach($aOptions as $aValue){
				if($bSecondOption):
					$sSql .= (isset($aValue[3])) ? ' '.$aValue[3] : ' AND';
				endif;
				$sSql .= ' '.selectTrad($aValue[0]).' '.$aValue[1].' "'.$aValue[2].'"';
				$bSecondOption = true;
			}
		}
		
		$aCount = mysql_fetch_array(mysql_query($sSql));
		return $aCount[0]+0 ;
	}
	
}

class Mail{
	public $sFrom = '';
	public $sCc = '';
	public $sBcc = '';
	public $sBody = '';
	public $sContent = '';
	public $sSubject = '';
	public $sHeader = '';
	
	public function construct(){
		if($this->sFrom != ''):
			$this->sHeader = $this->sFrom;
		else:
			$this->sHeader = 'From: Plan9<info@plan9.be>"\n"';
		endif;
		$this->sHeader .= 'MIME-Version: 1.0"\n"';
		$this->sHeader .= 'Content-Type:text/html;charset=iso-8859-1"\n"';
		$this->sHeader .= 'Content-Transfer-Encoding: 8bit"\n"'; 
		$this->sHeader .= 'Cc: '.$this->sCc.'"\n"'; 
		$this->sHeader .= 'Bcc: '.$this->sBcc.'"\n"'; 
		$this->sBody .= $this->sContent;
		$this->sBody .= '</div>';
	}
	
	public function From($sMail, $sName){ 
		$this->sFrom = 'From: '.$sName.'<'.$sMail.'>"\n"';
	}
	
	public function To($sMail){
		$this->sTo = $sMail;
	}
	
	public function subject($sSubject){
		$this->sSubject = $sSubject;
	}
	
	public function Bcc($aMail){
		if(is_array($aMail)){
			foreach($aMail as $sMail){
				$this->sBcc .= ', '.$sMail;
			}
		}else{
			$this->sBcc .= ', '.$aMail;
		}
	}
	
	public function Cc($sMail){
		$this->sCc = $sMail;
	}
	
	public function Content($sText){
		$this->sContent = $sText;
	}
	
	public function send(){
		$this->construct();
		mail($this->sTo, $this->sSubject, $this->sBody, $this->sHeader);
	}
}

?>