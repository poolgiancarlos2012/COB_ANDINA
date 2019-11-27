<?php
require('fpdf.php');
require('../config.php');
//include 'config.php';
require 'numletras.php';
   
        

class PDF extends FPDF{

    
    function Header() {
        
        $this->Image('../estilos/images/edificio.jpg' , 10 ,15, 25 , 35,'JPG','');
   
        //$this->SetFont('Arial','B',12);  
        $this->SetXY(35,25);
        $this->AddFont('rounted');
        $this->SetFont('rounted','', 13);
        $this->Write(8,'ASOC. PROPIETARIOS ALTO MIRADOR 2');
        
        $this->SetFont('Arial','',12);          
        $this->SetXY(55,30);
        $this->Write(8,utf8_decode('Av. Brasil 1387 - Jesus María'));
        
        $this->SetFillColor(255,255,255);//fondo blanco   
        $this->SetLineWidth(0.5);//grosor linea
        $this->RoundedRect(134, 20, 65, 25, 2, 'DF');
        
        //$this->SetXY(136, 20);
        $this->SetXY(134, 24);
        $this->SetTextColor(0, 0, 0);//letra negra
        //$this->SetFont('Arial','B',10); 
        $this->AddFont('eurocaps');
        $this->SetFont('eurocaps','', 15);
        //$this->CellFitSpace(0,15, utf8_decode("RECIBO DE MANTENIMIENTO"),0, 0 , 'C');
        $this->Write(8,'RECIBO DE MANTENIMIENTO');
        
        //$this->SetXY(136, 25);
        $this->SetXY(145, 29);
        $this->SetTextColor(0, 0, 0);//letra negra
        //$this->SetFont('Arial','B',10); 
        $this->AddFont('eurocaps');
        $this->SetFont('eurocaps','', 15);
        //$this->CellFitSpace(0,15, utf8_decode("DE AREAS COMUNES"),0, 0 , 'C');
        $this->Write(8,utf8_decode('DE AREAS COMUNES'));
        
        
        
        //$this->SetXY(136, 30);
        $this->SetXY(157, 35);
        $this->SetTextColor(255, 0, 0);//letra ROJO 
        //$this->SetFont('Arial','B',10); 
        $this->AddFont('eurocaps');
        $this->SetFont('eurocaps','', 15);
        //$this->CellFitSpace(0,15, utf8_decode("N° 000136"),0, 0 , 'C');
        //foreach($cabecera as $row){
        $this->Write(8,utf8_decode('N°'));
        //}
        
        $this->SetFillColor(255,255,255);//fondo blanco  
        $this->SetLineWidth(0.3);//grosor linea
        $this->RoundedRect(10, 60, 124, 20, 2, 'DF');
        
        $this->SetXY(12, 50);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Departamento: ");
        

        
        $this->SetXY(12, 55);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Propietario: ");
        
        
        
        $this->SetXY(12, 60);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Inquilino: ");
        
        
        
        $this->SetFillColor(255,255,255);//fondo blanco 
        $this->SetLineWidth(0.3);//grosor linea
        $this->RoundedRect(136, 60, 63, 20, 2, 'DF');
        
        $this->SetXY(142, 50);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Fecha: ");
        
        
        
        $this->SetXY(142, 55);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Periodo Pago: ");
        
        
        
        $this->SetXY(142, 60);
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Vencimiento: ");
        
        
        
         
        
        /*Adorno Linea Punteada*/
        $punteada = array('width' => 1, 'cap' => 'round', 'join' => 'round', 'dash' => '0,5', 'color' => array(0, 0, 0));
        $this->Linestyle(10, 55, 200, 55, $punteada);
        
        //$linea = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0));
        //$this->Linestyle(5, 10, 5, 30, $linea);
       }
       

    
function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);
 
        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;
 
        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
            }
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            //Override user alignment (since text will fill up cell)
            $align='';
        }
 
        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
 
        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }
 
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
 
    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }


    
    
function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));
 
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
 
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
 
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
 
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$y)*$k ));
            $this->_out(sprintf('%.2f %.2f l', ($x+$r)*$k, ($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
  
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    
    /*estilos de la linea*/
    
    function SetLineStyle($style) {
		extract($style);
		if (isset($width)) {
			$width_prev = $this->LineWidth;
			$this->SetLineWidth($width);
			$this->LineWidth = $width_prev;
		}
		if (isset($cap)) {
			$ca = array('butt' => 0, 'round'=> 1, 'square' => 2);
			if (isset($ca[$cap]))
				$this->_out($ca[$cap] . ' J');
		}
		if (isset($join)) {
			$ja = array('miter' => 0, 'round' => 1, 'bevel' => 2);
			if (isset($ja[$join]))
				$this->_out($ja[$join] . ' j');
		}
		if (isset($dash)) {
			$dash_string = '';
			if ($dash) {
				$tab = explode(',', $dash);
				$dash_string = '';
				foreach ($tab as $i => $v) {
					if ($i > 0)
						$dash_string .= ' ';
					$dash_string .= sprintf('%.2F', $v);
				}
			}
			if (!isset($phase) || !$dash)
				$phase = 0;
			$this->_out(sprintf('[%s] %.2F d', $dash_string, $phase));
		}
		if (isset($color)) {
			list($r, $g, $b) = $color;
			$this->SetDrawColor($r, $g, $b);
		}
	}

	// Draws a line
	// Parameters:
	// - x1, y1: Start point
	// - x2, y2: End point
	// - style: Line style. Array like for SetLineStyle
	function Linestyle($x1, $y1, $x2, $y2, $style = null) {
		if ($style)
			$this->SetLineStyle($style);
		parent::Line($x1, $y1, $x2, $y2);
	}
        
        
        var $widths;
var $aligns;
    /*funciones adicionales para tablas*/
function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}



function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		//$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                $a=$this->aligns[$i];
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		
		$this->Rect($x,$y,$w,$h);
                //$this->SetLineStyle(0);
                //$this->SetLineWidth($dash);
		$this->MultiCell($w,5,$data[$i],0,$a,'true');
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
/*Fin de funciones adicionales*/
        
    
}

/*inicio del detalle*/
//$con = new DB;
//$recibocabe = $con->conectar();
$recibo=$_GET['idrecibo'];
$recibocabe=db_query("SELECT r.nrorecibo AS nro,r.ncoddpto AS dpto,
                                    CONCAT(UPPER(LEFT(s.cnomsocio,1)),LOWER(SUBSTR(s.cnomsocio,2)),', ',
                                    UPPER(LEFT(s.capepatsocio,1)),LOWER(SUBSTR(s.capepatsocio,2)),' ',
                                    UPPER(LEFT(s.capematsocio,1)),LOWER(SUBSTR(s.capematsocio,2))) AS socio,
                                    m.cnommes AS mes,a.nanio AS anio,
                                    r.dvencimirecibo AS venci,
                                    r.ntotaldeudarecibo as total
                                    FROM recibo r
                                    LEFT JOIN socio_edificio se ON r.ncodsocio_edificio=se.ncodsocio_edificio
                                    LEFT JOIN socio s ON s.ncodsocio=se.ncodsocio
                                    LEFT JOIN mes m ON m.ncodmes=r.ncodmes
                                    LEFT JOIN anio a ON a.ncodanio=r.ncodanio
                                    WHERE  r.ncodrecibo=".$recibo." 
                                ");

$dataheader = db_fetch_array($recibocabe);
        
$pdf = new PDF();
$pdf->Open();
$pdf->AddPage();
$pdf->SetMargins(10, 10);
//nro del recibo
$pdf->SetXY(163,35);
$pdf->SetTextColor(255, 0, 0);//letra ROJO 
//$pdf->SetFont('Arial','B',10); 
$pdf->AddFont('eurocaps');
$pdf->SetFont('eurocaps','', 15);
$pdf->Write(8,$dataheader['nro']);
//departamento
$pdf->SetXY(39, 50);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['dpto']);
//socio
$pdf->SetXY(39, 55);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['socio']);

$pdf->SetXY(39, 60);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['socio']);

$pdf->SetXY(168, 50);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, date("d-m-Y"));

$pdf->SetXY(168, 55);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['mes']."-".$dataheader['anio']);

$pdf->SetXY(168, 60);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['venci']);

$pdf->Ln(10);

$pdf->SetXY(10,85);
$pdf->SetWidths(array(20, 119, 50));
$pdf->SetAligns(array('C', 'C', 'C'));//ALINEA LA CABECERA DE LA TABLA
//$pdf->SetFont('Arial','B',10);
$pdf->AddFont('boris');
$pdf->SetFont('boris','', 9);
$pdf->SetDrawColor(225,225,225);
$pdf->SetFillColor(227,227,227);
$pdf->SetTextColor(0);

for($i=0;$i<1;$i++)
			{
				$pdf->Row(array('CODIGO', 'CONCEPTO', 'IMPORTE (S/.)'));
			}
//$detallerecibo=$con->conectar();
$sqldetalle=db_query("
                                SELECT 
                                ncodrecibo_detalle AS codigo,
                                c.cnomconcepto AS concepto,
                                rd.nimporterecibo_detalle AS importe
                                FROM recibo_detalle rd
                                LEFT JOIN concepto_edificio ce ON ce.ncodconcepto_edificio=rd.ncodconcepto_edificio
                                LEFT JOIN concepto c ON c.ccodconcepto=ce.ccodconcepto
                                WHERE  ncodrecibo=".$recibo." 
                                ORDER BY ncodrecibo_detalle    
                                ");                        

$numfilas=db_num_rows($sqldetalle);
$j=0;
for ($i=0; $i<$numfilas; $i++)
		{       
                        $pdf->SetAligns(array('C', 'L', 'C'));//ALINEA EL CONTENIDO DE LA TABLA
			$fila = db_fetch_array($sqldetalle);
			$pdf->SetFont('Arial','',10);
			
			if($i%2 == 1)
			{
                        
			$pdf->SetFillColor(225,225,225);
                        
    			$pdf->SetTextColor(0);
                        $j++;
				$pdf->Row(array($j, $fila['concepto'], $fila['importe']));
			}
			else
			{
			$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
                        $j++;
				$pdf->Row(array($j, $fila['concepto'], $fila['importe']));
			}
		}


$pdf->SetWidths(array(139, 50));
$pdf->SetAligns(array('R', 'C'));
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(232,232,232);
$pdf->SetTextColor(0);
$pdf->Row(array("TOTAL", $dataheader['total']));

/*fin del detalle*/

/*recibo parte inferior*/
$linea = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$pdf->Linestyle(0, 0, 0, 0, $linea);
$pdf->SetFillColor(255,255,255);//fondo blanco  
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(10, 125, 124, 12, 2, 'DF');

$numletras= new EnLetras();
$num=$dataheader['total'];
$pdf->SetXY(12, 115);
$pdf->SetFont('Arial','B',11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"El importe Total es: ".$numletras->ValorEnLetras($num, "Soles"));

$pdf->SetFillColor(255,255,255);//fondo blanco 
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(136, 125, 63, 12, 2, 'DF');

$pdf->SetXY(138, 115);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"El pago de este recibo no lo libera");

$pdf->SetXY(138, 119);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"de los adeudos anteriores");

$pdf->SetFillColor(255,255,255);//fondo blanco  
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(10, 139, 124, 30, 2, 'DF');

$pdf->SetXY(12, 129);
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"Formas de Pago: ");
$pdf->Ln();

$pdf->SetXY(12, 133);
//$pdf->SetFont('Arial','B',7);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"* En el condominio UNICAMENTE con cheque a nombre del Condominio ASOC.  ");

$pdf->SetXY(12, 137);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("   PROPIETARIOS ALTO MIRADOR 2 indicando que es NO NEGOCIABLE si paga "));

$pdf->SetXY(12, 141);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("   en EFECTIVO deposite en la cuenta N° 12992 Suc. 4116 de BANAMEX; envie copia "));

$pdf->SetXY(12, 145);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("   al 5247-3407. Deposite por INTERNET en la cuenta del condominio y envie la "));

$pdf->SetXY(12, 149);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("   la copia por e-mail."));

$pdf->SetFillColor(255,255,255);//fondo blanco 
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(136, 139, 63, 30, 2, 'DF');

$pdf->SetXY(145, 150);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("ADM."));

$idpersonal=$_GET['personal'];
$admin=db_query("SELECT 
				CONCAT(UPPER(LEFT(cnompersonal,1)),LOWER(SUBSTR(cnompersonal,2)),', ',
				UPPER(LEFT(capepatpersonal,1)),LOWER(SUBSTR(capepatpersonal,2)),' ',
				UPPER(LEFT(capematpersonal,1)),LOWER(SUBSTR(capematpersonal,2))) AS nombre
				FROM personal WHERE ncodpersonal=".$idpersonal."
				");
list($personal)=db_fetch_array($admin);
$pdf->SetXY(155, 150);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,$personal);
/* inicio recibo parte inferior*/
$pdf->Output();
?>
