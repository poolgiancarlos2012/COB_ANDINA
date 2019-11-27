<?php
include_once('fpdf.php');


class PDF extends FPDF{

    
    function Header() {
             
        
    	$this->Image('img/footer-logo.jpg' , 30 ,0, 30 , 20,'JPG','');

        $this->SetXY(65,10);
        $this->AddFont('rounted');
        $this->SetFont('rounted','', 14);	
        $this->Write(8,'VERIFICACION DOMICILIARIA');

  
        
        /*Adorno Linea Punteada*/
        // $punteada = array('width' => 1, 'cap' => 'round', 'join' => 'round', 'dash' => '0,5', 'color' => array(0, 0, 0));
        // $this->Linestyle(10, 55, 200, 55, $punteada);

    }




    
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true){
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
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
    function MBGetStringLength($s){ //Patch to also work with CJK double-byte text
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
    function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234'){
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
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }   
    function SetLineStyle($style) { /*estilos de la linea*/
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
	function Linestyle($x1, $y1, $x2, $y2, $style = null) {
		if ($style)
			$this->SetLineStyle($style);
		parent::Line($x1, $y1, $x2, $y2);
	}
        
        
    var $widths;
    var $aligns;
    /*funciones adicionales para tablas*/
    function SetWidths($w){
    	//Set the array of column widths
    	$this->widths=$w;
    }
    function SetAligns($a){
    	//Set the array of column alignments
    	$this->aligns=$a;
    }
	function Row($data){
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
    function CheckPageBreak($h){
    	//If the height h would cause an overflow, add a new page immediately
    	if($this->GetY()+$h>$this->PageBreakTrigger)
    		$this->AddPage($this->CurOrientation);
    }
    function NbLines($w,$txt){
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

// P o Portrait (normal)
// L o Landscape (apaisado)

// A3 
// A4 
// A5 
// Letter 
// Legal 

// pt: punto
// mm: milimetro
// cm: centimetro
// in: pulgada

// $pdf = new PDF();
$pdf = new PDF('P', 'mm', 'A4');
$pdf->Open();
$pdf->AddPage();
$pdf->SetMargins(10, 10);

$rec1_width=20;
$rec2_height=30;


$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.4); //grosor linea

// LABEL 1


$pdf->SetXY($rec1_width,$rec2_height+1);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Asociacion:'));

$pdf->SetXY($rec1_width,$rec2_height+6);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Contrato:'));


$pdf->SetXY($rec1_width+30,$rec2_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('J Y B SEPARADORA INDUSTRIAL S.A.C.'));

$pdf->SetXY($rec1_width+30,$rec2_height+6);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('8107.017.02'));





//LABEL 2
/*
$rec3_width=20;
$rec4_height=50;
$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec3_width, $rec4_height, 150, 70, 2, 'DF');


$pdf->SetXY($rec3_width+20,$rec4_height+1);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Contrato'));

$pdf->SetXY($rec3_width+80,$rec4_height+1);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Deuda'));
*/

  
 
    //En una variable guardamos el array que regresa el método
    

function cabecera($cabecera){
        $this->SetXY(60,105);
        $this->SetFont('Arial','B',12);
        foreach($cabecera as $columna)
        {
            $this->Cell(60,20,$columna,1, 2 , 'L' ) ;
        }
    }
 
    function datos($datos){
 
        $this->SetXY(90,105);
        $this->SetFont('Arial','',10);
        foreach ($datos as $columna)
        {
            $this->Cell(70,20,utf8_decode($columna['negocio']),'TRB',2,'L' );
            $this->Cell(70,80,utf8_decode($columna['suma']),'TRB',2,'L' );
           
        }

    //El método tabla integra a los métodos cabecera y datos
    function tabla($cabecera,$datos){
        $this->cabecera ($cabecera) ;
        $this->datos($datos);
    }




//label 3

$rec5_width=20;
$rec6_height=120;


$pdf->SetXY($rec5_width,$rec6_height+1);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Importe Vencido:'));
$pdf->SetXY($rec5_width+40,$rec6_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('783'));


$pdf->SetXY($rec5_width,$rec6_height+5);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Importe por vencer:'));
$pdf->SetXY($rec5_width+40,$rec6_height+5);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('USD'));

$pdf->SetXY($rec5_width,$rec6_height+9);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Nro:'));
$pdf->SetXY($rec5_width+10,$rec6_height+9);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('280'));

$pdf->SetXY($rec5_width,$rec6_height+16);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar a un acuerdo sobre la deuda que'));

$pdf->SetXY($rec5_width,$rec6_height+20);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('manteniene con nosotros ya que los ultimos 3 meses no vienen honrandola a pesar de nuestros constantes requerimientos.'));

$pdf->SetXY($rec5_width,$rec6_height+27);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Por favor preguntar por Oscar Villaba al telefono 712-3500 anexo(102201)'));

$pdf->SetXY($rec5_width,$rec6_height+32);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Atte.'));


//lbael4

$pdf->Image('img/footer-logo.jpg' , 30 ,170, 30 , 20,'JPG', ' ');
$rec7_width=20;
$rec8_height=170;

$pdf->SetXY($rec7_width+50,$rec8_height+10);
$pdf->SetFont('Arial','B', 14);
$pdf->Write(8,utf8_decode('VERIFICACION DOMICILIARIA'));


$pdf->SetXY($rec7_width,$rec8_height+30);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Garante: __________________________________________________'));

$pdf->SetXY($rec7_width,$rec8_height+35);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Asociado: __________________________________________________'));


$pdf->SetXY($rec7_width,$rec8_height+40);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Contrato: __________________________'));

$pdf->SetXY($rec7_width,$rec8_height+45);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Importe:USD_________________'));


$pdf->SetXY($rec7_width,$rec8_height+50);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Importe por Vencer:USD_________________'));


$pdf->SetXY($rec7_width,$rec8_height+58);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar a un acuerdo sobre la deuda que'));

$pdf->SetXY($rec7_width,$rec8_height+62);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('manteniene con nosotros en su calidad de Garante Solidario ya que los ultimos ___ meses el asociado en mencion no '));

$pdf->SetXY($rec7_width,$rec8_height+66);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('cumple en honrarla.'));

$pdf->SetXY($rec7_width,$rec8_height+72);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Por favor preguntar por el Sr. ________________ a los telefonos 712-3500 anexo(102201).'));


//LABEL 5
/*
$rec7_width=10;
$rec8_height=140;


$pdf->SetXY($rec7_width,$rec8_height+1);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('Asignado :'));
$pdf->SetXY($rec7_width+20,$rec8_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('ANGEL ABEL ALVA GONZALEZ'));


//LABEL 6
$rec9_width=10;
$rec10_height=150;
$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec9_width, $rec10_height, 190, 25, 2, 'DF');


$pdf->SetXY($rec9_width,$rec10_height+1);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Mejor Gestion:'));

$pdf->SetXY($rec9_width+60,$rec10_height+1);
$pdf->SetFont('Arial','', 10);
$pdf->Write(8,utf8_decode('call:'));

$pdf->SetXY($rec9_width+70,$rec10_height+1);
$pdf->SetFont('Arial','', 10);
$pdf->Write(8,utf8_decode('No contesta/Ocupada:'));

$pdf->SetXY($rec9_width,$rec10_height+6);
$pdf->SetFont('Arial','', 10);
$pdf->Write(8,utf8_decode('Telefono:'));

$pdf->SetXY($rec9_width,$rec10_height+10);
$pdf->SetFont('Arial','', 10);
$pdf->Write(8,utf8_decode('Fecha: Ult gestion'));

$pdf->SetXY($rec9_width,$rec10_height+16);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('Validacion de Telefonos: '));

$pdf->SetXY($rec9_width+60,$rec10_height+16);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('(  )'));

$pdf->SetXY($rec9_width+80,$rec10_height+16);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('(  )'));

$pdf->SetXY($rec9_width+100,$rec10_height+16);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('(  )'));

$pdf->SetXY($rec9_width+120,$rec10_height+16);
$pdf->SetFont('Arial','B', 10);
$pdf->Write(8,utf8_decode('(  )'));
// RPTA 1
/*

$pdf->SetXY($rec3_width+30,$rec4_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('J Y B SEPARADORA INDUSTRIAL S.A.C.'));

$pdf->SetXY($rec3_width+30,$rec4_height+6);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('AV. SEPARADORA INDUSTRIAL 734 mz k lote 21'));

$pdf->SetXY($rec3_width+30,$rec4_height+11);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('ATE'));

$pdf->SetXY($rec3_width+30,$rec4_height+16);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('BARRIENTOS ZAVALA KATHERINE GRACE'));

$pdf->SetXY($rec3_width+30,$rec4_height+21);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('AV LAS DUPRAS N 163 RESIDENCIAL MONTERRICO'));
*/
}
?>