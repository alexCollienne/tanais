<?php
require('../fpdf16/fpdf.php');
require('../sys/connect.php');
require('../include/function.php');
require('../include/class.php');
session_start();

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;


//Pied de page
function Footer()
{
  if($this->PageNo() != 1){
    $this->SetLeftMargin(10);
    //Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    //Police Arial italique 8
    $this->SetFont('Arial','I',8);
    //Numéro de page
    $this->Cell(0,10,'- Page '.$this->PageNo().' -',0,0,'C');
  }
}


function PDF($orientation='P',$unit='mm',$format='A4')
{

    //Appel au constructeur parent
    $this->FPDF($orientation,$unit,$format);
    //Initialisation
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}

function WriteHTML($html)
{
    //Parseur HTML
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Texte
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Balise
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extraction des attributs
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Balise ouvrante
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Balise fermante
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modifie le style et sélectionne la police correspondante
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }

    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Place un hyperlien
    $this->SetTextColor(153,153,153);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
}

}


$pdf=new PDF();

$pdf->AddPage();
$pdf->Image('../img/site/cover-catalogue.jpg',0,0,'210','297');

$categorie = "";

$aCategory = dataManager::Read('ts_categorie',null,array('sCategoryi18n','ASC'),null,null,array('distinct','sCategoryi18n'));
$i=0;
$j=0;
foreach($aCategory as $oCategory):
	$aIdCat = array(array('bActive','=',1));
	$aCategory = dataManager::Read('ts_categorie',array(array('sCategoryi18n','=',$oCategory->sCategoryi18n)));
	$pdf->SetLeftMargin(10);
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(3,3,3);
    $pdf->Cell('','6',$oCategory->sCategoryi18n,1,'0', 'C');
    $pdf->ln();
    $bFlag = false;
    $j=0;

    foreach($aCategory as $iKey => $oCategory):
		if($iKey == 0):
			$aIdCat[1] = array('iCategory','=',$oCategory->ID);
		else:
			$aIdCat[] = array('iCategory','=',$oCategory->ID,'OR');
		endif;
	endforeach;
	$aArticle = dataManager::Read('ts_catalogue',$aIdCat);
    foreach ($aArticle as $oArticle):
		if($j==9):
		    $pdf->AddPage();
		    $j=0;
		else:
		    $j++;
		endif;
	
		$categorie = $oArticle->sCategoryi18n;
	
	  	if($bFlag){
		    $gety = $pdf->GetY()-28;
		    $getx = $pdf->GetX();
		    $pdf->SetLeftMargin(10);
		}else{
		    $gety = $pdf->GetY()+20;
		    $getx = 10;
		    $pdf->SetLeftMargin($pdf->GetX());
		}
	    $bFlag != $bFlag;
		
		$pdf->Image('../img/catalogue/'.$oArticle->sImage,$getx,$gety, '30', '30', '','http://www.tanais.be/catalogue.php?id='.$oArticle->ID);
		
		$html = '<a href="http://www.tanais.be/catalogue.php?id='.$oArticle->ID.'">'.html_entity_decode($oArticle->sNamei18n).'</a><br>';
		$prix = 'Prix: '.$oArticle->iPrice.' €<br>';
		$html .= '<p>Forme: '.html_entity_decode($oArticle->sFormei18n).'</p>';
		$html .= '<p>Couleur: '.html_entity_decode($oArticle->sColori18n).'</p>';
		$html .= '<p>Matière: '.html_entity_decode($oArticle->sMateriali18n).'</p>';
		$html .= '<p>Rfr: '.$oArticle->sReference.'<br>';
		$pdf->SetLeftMargin($getx+31);
		$pdf->SetY($gety-2);
		$pdf->SetFont('Arial','',8);
		$content = $pdf ->WriteHTML($html);
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(199,49,127);
		$content .= $pdf->WriteHTML($prix);
		$pdf->Cell(72,0,$content);
		$pdf->SetFont('Arial','',8);
	endforeach;
endforeach;

$pdf->Output();

?>