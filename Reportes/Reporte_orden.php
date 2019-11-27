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
        $this->SetXY(142, 26);
        $this->SetTextColor(0, 0, 0);//letra negra
        //$this->SetFont('Arial','B',10); 
        $this->AddFont('eurocaps');
        $this->SetFont('eurocaps','', 15);
        //$this->CellFitSpace(0,15, utf8_decode("RECIBO DE MANTENIMIENTO"),0, 0 , 'C');
        $this->Write(8,'ORDEN DE COMPRA');
        

        
        
        
        //$this->SetXY(136, 30);
        $this->SetXY(154, 33);
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
        $this->RoundedRect(10, 60, 70, 20, 2, 'DF');
        
        $this->SetXY(12, 49);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Proveedor : ");
        
		$this->SetXY(12, 53);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "RUC : ");
        
        $this->SetXY(12, 57);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Contacto : ");
        
        
        $this->SetXY(12, 61);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Telefono : ");
        
		$this->SetFillColor(255,255,255);//fondo blanco  
        $this->SetLineWidth(0.3);//grosor linea
        $this->RoundedRect(81, 60, 77, 20, 2, 'DF');
        
		$this->SetXY(82, 49);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Direccion : ");
        
		$this->SetXY(82, 53);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Tipo Pago : ");
        
        
        
        $this->SetXY(82, 57);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Moneda : ");
		
		$this->SetXY(82, 61);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Emision : ");
		
        $this->SetFillColor(255,255,255);//fondo blanco 
        $this->SetLineWidth(0.3);//grosor linea
        $this->RoundedRect(159	, 60, 40, 20, 2, 'DF');
        
        $this->SetXY(159, 49);
        $this->SetFont('Arial','B',7.5);
        $this->SetTextColor(0, 0, 0);
        $this->Write(30, "Fecha Entrega: ");
        
        
        
        
             
        
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

$orden=$_GET['idorden'];
$ordencabe=db_query("SELECT p.crazproveedor AS nomprov,pc.cnomprov_contacto AS nom,p.ntelfproveedor AS telf,p.crucproveedor AS ruc,p.cdirproveedor AS dir,
						CONCAT(UPPER(LEFT(per.cnompersonal,1)),LOWER(SUBSTR(per.cnompersonal,2)),', ',
						UPPER(LEFT(per.capepatpersonal,1)),LOWER(SUBSTR(per.capepatpersonal,2)),' ',
						UPPER(LEFT(per.capematpersonal,1)),LOWER(SUBSTR(per.capematpersonal,2))) AS nombre,
						per.ccodedificio AS edificio,
						d.dentregadoc AS entrega,
						d.ncodmedio_pago AS pago,
						d.cnrodoc AS nro,
						mp.cdescmedio_pago AS medio,
						m.csymmodeda AS moneda,
						d.ntotdoc AS total
						FROM doc d
						LEFT JOIN proveedor p ON d.ncodproveedor=p.ncodproveedor
						LEFT JOIN prov_contacto pc ON p.ncodproveedor=pc.ncodproveedor
						LEFT JOIN  prov_edificio pe ON pe.ncodproveedor=p.ncodproveedor
						LEFT JOIN personal per ON per.ncodpersonal=pe.ncodpersonal
						LEFT JOIN medio_pago mp ON mp.ncodmedio_pago=d.ncodmedio_pago
						LEFT JOIN moneda m ON m.ccodmoneda=d.ccodmoneda
						WHERE d.ccoddoc=".$orden."
                                ");

$dataheader = db_fetch_array($ordencabe);
        
$pdf = new PDF();
$pdf->Open();
$pdf->AddPage();
$pdf->SetMargins(10, 10);
//nro del recibo
$pdf->SetXY(161	,33);
$pdf->SetTextColor(255, 0, 0);//letra ROJO 
//$pdf->SetFont('Arial','B',10); 
$pdf->AddFont('eurocaps');
$pdf->SetFont('eurocaps','', 15);
$pdf->Write(8,$dataheader['nro']);

$pdf->SetXY(29, 49);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['nomprov']);

$pdf->SetXY(29, 53);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['ruc']);

$pdf->SetXY(29, 57);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['nom']);

$pdf->SetXY(29, 61);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['telf']);

$pdf->SetXY(98, 49);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, utf8_decode($dataheader['dir']));


$pdf->SetXY(98, 53);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['medio']);

$pdf->SetXY(98, 57);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['moneda']);

$pdf->SetXY(98, 61);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, date("d-m-Y"));

$pdf->SetXY(180, 49);
$pdf->SetFont('Arial','',7.5);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30, $dataheader['entrega']);



$pdf->Ln(10);
$h=85;
$pdf->SetXY(10,$h);
$pdf->SetWidths(array(10, 74, 27,17,17,17,27));
$pdf->SetAligns(array('C', 'C', 'C','C', 'C', 'C','C'));//ALINEA LA CABECERA DE LA TABLA
//$pdf->SetFont('Arial','B',10);
$pdf->AddFont('boris');
$pdf->SetFont('boris','', 9);
$pdf->SetDrawColor(225,225,225);
$pdf->SetFillColor(227,227,227);
$pdf->SetTextColor(0);

for($i=0;$i<1;$i++)
			{
				$pdf->Row(array('Nro', 'Concepto', 'Articulo','U.med','Precio','Cant','Importe (S/.)'));
			}
//$detallerecibo=$con->conectar();
$sqldetalle=db_query("
                                SELECT 
								a.cdescarticulo AS articulo,
								a.ncodarticulo AS acodigo,
								dt.npredoc_det AS precio,
								dt.ncantdoc_det AS cantidad,
								dt.ntotdoc_det AS subtotal,
								u.csym_umedida AS medida,
								u.ncod_umedida AS ucodigo,
								c.cnomconcepto AS concepto,
								c.ccodconcepto AS ccodigo
								FROM doc_det dt 
								LEFT JOIN concepto c ON dt.ccodconcepto=c.ccodconcepto
								LEFT JOIN articulo a ON a.ncodarticulo=dt.ncodarticulo
								LEFT JOIN unid_medida u ON u.ncod_umedida=dt.ncod_umedida
								WHERE dt.ccoddoc=".$orden."
                                ");                        

$numfilas=db_num_rows($sqldetalle);
$j=0;
$w=0;
for ($i=0; $i<$numfilas; $i++)
		{       
            $pdf->SetAligns(array('C', 'L', 'L','C', 'R', 'C','R'));//ALINEA EL CONTENIDO DE LA TABLA
			$fila = db_fetch_array($sqldetalle);
			$pdf->SetFont('Arial','',8);
			
			if($i%2 == 1)
			{
                        
			$pdf->SetFillColor(225,225,225);
                        
    			$pdf->SetTextColor(0);
                        $j++;
						$w+=5;
				$pdf->Row(array($j, $fila['concepto'], $fila['articulo'],$fila['medida'],$fila['precio'],$fila['cantidad'],$fila['subtotal']));
			}
			else
			{
			$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
                        $j++;
						$w+=5;
				$pdf->Row(array($j, $fila['concepto'], $fila['articulo'],$fila['medida'],$fila['precio'],$fila['cantidad'],$fila['subtotal']));
			}
		}


$pdf->SetWidths(array(139, 50));
$pdf->SetAligns(array('R', 'R'));
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(232,232,232);
$pdf->SetTextColor(0);
$pdf->Row(array("TOTAL", $dataheader['total']));

/*fin del detalle*/

/*recibo parte inferior*/
$altrec=15;
$altura=$w+$h+$altrec;
$linea = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$pdf->Linestyle(0, 0, 0, 0, $linea);
$pdf->SetFillColor(255,255,255);//fondo blanco  
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(10, $altura, 124, 13, 2, 'DF');

$numletras= new EnLetras();
$num=$dataheader['total'];
$pdf->SetXY(12, $altura-12+4);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"El importe Total es: ".utf8_decode($numletras->ValorEnLetras($num, "Soles")));

$pdf->SetFillColor(255,255,255);//fondo blanco 
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(136, $altura, 63, 13, 2, 'DF');

$pdf->SetXY(137, $altura-12);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"Nota: El 100% de la mercaderia debe ser entregada en");

$pdf->SetXY(137, $altura-12+4);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"nuestros almacenes, de los contrario no se ");

$pdf->SetXY(137, $altura-12+8);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"cancelara");

$pdf->SetFillColor(255,255,255);//fondo blanco  
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(10, $altura+14, 124, 30, 2, 'DF');

$pdf->SetXY(12, $altura+2);
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"Importante: ");
$pdf->Ln();

$pdf->SetXY(12, $altura+6);
//$pdf->SetFont('Arial','B',7);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"1.- Si los precios pactados en esta orden han variado, no atender sin previa consulta ");

$pdf->SetXY(12, $altura+10);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("2.- Sirvase a entregar la mercaderia con GUIA en triplicado e indicar el Nro de "));

$pdf->SetXY(12, $altura+14);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("      esta copia."));

$pdf->SetXY(12, $altura+18);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("3.- Presentar su Factura en triplicado desdoblando el importe General a las Ventas "));

$pdf->SetXY(12, $altura+22);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,utf8_decode("      DL. 190, indicando Nro de esta orden de compra, adjuntando copia por ambos lados "));

$pdf->SetFillColor(255,255,255);//fondo blanco 
$pdf->SetLineWidth(0.3);//grosor linea
$pdf->RoundedRect(136, $altura+14, 63, 30, 2, 'DF');

$pdf->SetXY(145, $altura+22);
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
$pdf->SetXY(155, $altura+22);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,$personal);


/*$altura=$w+$h;
$pdf->SetXY(155, $altura);
$pdf->AddFont('rounted');
$pdf->SetFont('rounted','', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Write(30,"hola ".$altura);*/


/* inicio recibo parte inferior*/
$pdf->Output();
?>
