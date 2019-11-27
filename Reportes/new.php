<?php

require('fpdf.php');
include_once('config.php');
class PDF extends FPDF
{
function Header() {
             
        
        $this->Image('img/footer-logo.jpg' , 30 ,20, 30 , 20,'JPG','');

        $this->SetXY(75,30);
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
}

     $con = new DB;
     $clientes = $con->conectar(); 
     $f = $con->conectar(); 

     $idcartera=2;

    $str = "SELECT 
            codigo_cliente,
            inscripcion
            FROM 
            ca_cuenta 
            WHERE 
            codigo_cliente IN (
            '20128808711'
            ) AND estado=1 AND idcartera=$idcartera GROUP BY codigo_cliente";
     $f = mysql_query($str); 
     $u = mysql_num_rows($f);

     $pdf=new PDF('P','mm','A4');
     $inc =1;
    
    for($j=1;$j<=$u;$j++){

        $fi = mysql_fetch_array($f);
        
        $clientes = $con->conectar();  
        $strConsulta ="SELECT codigo,CONCAT(nombre,'  ',paterno,'  ',materno) as nombre,razon_social from ca_cliente where codigo='".$fi['codigo_cliente']."'";
        // echo $strConsulta;
        $clientes = mysql_query($strConsulta);        
        $fila = mysql_fetch_array($clientes);       
        
        $pdf->Open();
        $pdf->AddPage();

        // echo $asc;
        // $asc++;

        $pdf->SetMargins(20,20);

        $pdf->Ln(20);
        $pdf->SetFont('Arial','B',10);

        if($fila['nombre']<>'    '){
            // $pdf->Cell(0,6,'Asociado:',0,1);
            $pdf->SetXY(20,40);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Write(30,'Asociado:');

            $pdf->SetXY(45,40);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Write(30, $fila['nombre']);
        }

        if($fila['razon_social']<>''){
            //  $pdf->Cell(0,6,'Asociado: '.$fila['razon_social'],0,1);
            //  $pdf->SetXY(20,40);
            // $pdf->SetFont('Arial','B',10);
            // $pdf->SetTextColor(0, 0, 0);
            // $pdf->Write(30,'Asociado:');
            $pdf->SetXY(20,40);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Write(30,'Asociado:');

            $pdf->SetXY(45,40);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Write(30, $fila['razon_social']);
        }
       
        
        // $pdf->Cell(0,6,'Documento: '.$fila['codigo'],0,1);

        $pdf->SetXY(20,45);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30,'Documento:');

        $pdf->SetXY(45,45);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30, $fila['codigo']);

        
       //  //$pdf->Ln(30);
        
        $pdf->SetWidths(array(40,40));
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(1);

        for($i=0;$i<1;$i++){
            // $pdf->Row(array('Contrato', 'Deuda'));
            // $pdf->SetAligns(array('C', 'L', 'L','C', 'R', 'C','R'));
            // $pdf->SetFont('Arial','',8);
            // $pdf->SetFillColor(225,225,225);
            // $pdf->SetTextColor(0);
            // $pdf->Row(array($j, $fila['concepto'], $fila['articulo'],$fila['medida'],$fila['precio'],$fila['cantidad'],$fila['subtotal']));
        
            $pdf->Ln(10);
            $h=70;
            $pdf->SetXY(20,$h);
            $pdf->SetWidths(array(10, 27,47, 27));
            $pdf->SetAligns(array('C', 'C', 'C','C'));//ALINEA LA CABECERA DE LA TABLA
            //$pdf->SetFont('Arial','B',10);
            $pdf->AddFont('boris');
            $pdf->SetFont('boris','', 9);
            $pdf->SetDrawColor(225,225,225);
            $pdf->SetFillColor(227,227,227);
            $pdf->SetTextColor(0);

            for($i=0;$i<1;$i++)
            {
                $pdf->Row(array('#', 'Contrato', 'Tip.Adjudicacion', 'Deuda'));
            }

        }
        
        $historial = $con->conectar();  
        $strConsulta = "SELECT  negocio,inscripcion,CONCAT('USD  ',ROUND(sum(cuota_mensual+otros+seguros),2)) as suma from ca_cuenta  where estado=1 AND idcartera=$idcartera and codigo_cliente='".$fi['codigo_cliente']."' GROUP BY negocio,inscripcion";
        
        $historial = mysql_query($strConsulta);
        $numfilas = mysql_num_rows($historial);

       //  //for ($i=0; $i<$numfilas; $i++){
       //      // $fila = mysql_fetch_array($historial);
       //      // $pdf->SetFont('Arial','',10);
       //      // $pdf->SetFillColor(255,255,255);
       //      // $pdf->SetTextColor(0);
       //      // $pdf->Row(array($fila['negocio'], $fila['suma']));             
       //  //}


        $z=0;
        $p=0;
        for ($i=0; $i<=$numfilas-1; $i++){


                $pdf->SetAligns(array('C', 'C','C','C'));//ALINEA EL CONTENIDO DE LA TABLA
                $fila = mysql_fetch_array($historial);
                $pdf->SetFont('Arial','B',9);
            
            if($i%2 == 1)
            {
                        
            $pdf->SetFillColor(225,225,225);                        
            $pdf->SetTextColor(0);
                        $z++;
                        $p+=5;
                $pdf->Row(array($z, $fila['negocio'],$fila['inscripcion'],$fila['suma']));
            }
            else
            {
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
                        $z++;
                        $p+=5;
            $pdf->Row(array($z, $fila['negocio'],$fila['inscripcion'],$fila['suma']));
            }
        }

        $total= $con->conectar();  
        $str ="SELECT  codigo_cliente,CONCAT('USD  ',ROUND(sum(cuota_mensual+otros+seguros),2)) as sum from ca_cuenta  where estado=1 AND idcartera=$idcartera and codigo_cliente='".$fi['codigo_cliente']."'    GROUP BY codigo_cliente";
        $total = mysql_query($str);
        $fil = mysql_fetch_array($total);


        $pdf->Ln(25);

        // $pdf->SetFont('Arial','B',10);
        // $pdf->Cell(0,6,'Total deuda: '.$fil['sum'],0,1);
        // $pdf->Cell(0,6,'Nro: '.$inc,0,1);

        $pdf->SetXY(20,70+$p);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30,'Total deuda:');

        $pdf->SetXY(45,70+$p);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30, $fil['sum']);

        $pdf->SetXY(20,75+$p);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30,'Nro:');

        $pdf->SetXY(45,75+$p);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(30,$inc);

       $inc++;

       $rec5_width=20;
        $rec6_height=120;

        $pdf->SetXY($rec5_width,$rec6_height+1);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar a un'));

        $pdf->SetXY($rec5_width,$rec6_height+5);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('acuerdo sobre la deuda que manteniene con nosotros ya que no viene  '));



        $pdf->SetXY($rec5_width,$rec6_height+9);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('honrandola a pesar de nuestros constantes requerimientos.'));

        $pdf->SetXY($rec5_width,$rec6_height+17);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Por favor preguntar por Oscar Villaba al telefono 712-3500 anexo(102201)'));

        $pdf->SetXY($rec5_width,$rec6_height+25);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Atte.'));

           
        $pdf->Image('img/footer-logo.jpg' , 30 ,170, 30 , 20,'JPG', ' ');
        $rec7_width=20;
        $rec8_height=170;

        $pdf->SetXY($rec7_width+50,$rec8_height+10);
        $pdf->SetFont('Arial','B', 14);
        $pdf->Write(8,utf8_decode('VERIFICACION DOMICILIARIA'));


        $pdf->SetXY($rec7_width,$rec8_height+30);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Write(8,utf8_decode('Garante: __________________________________________________'));

        $pdf->SetXY($rec7_width,$rec8_height+35);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Write(8,utf8_decode('Asociado: __________________________________________________'));


        $pdf->SetXY($rec7_width,$rec8_height+40);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Write(8,utf8_decode('Contrato: __________________________'));

        $pdf->SetXY($rec7_width,$rec8_height+45);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Write(8,utf8_decode('Importe:USD_________________'));


        $pdf->SetXY($rec7_width,$rec8_height+50);
        $pdf->SetFont('Arial','B', 10);
        $pdf->Write(8,utf8_decode('Importe por Vencer:USD_________________'));


        $pdf->SetXY($rec7_width,$rec8_height+66);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar '));

        $pdf->SetXY($rec7_width,$rec8_height+70);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('a un acuerdo sobre la deuda que manteniene con nosotros en su calidad de Garante'));

        $pdf->SetXY($rec7_width,$rec8_height+74);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Solidario ya que los ultimos ___ meses el asociado en mencion no cumple en honrarla.'));

        $pdf->SetXY($rec7_width,$rec8_height+86);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Write(8,utf8_decode('Por favor preguntar por el Sr. ________________ a los telefonos 712-3500 anexo(102201).'));


        if($fi['inscripcion']<>'NO ADJUDICADO'){
            $pdf->Image('img/pre_judicial.jpg' , 145 ,70, 50 , 15,'JPG', ' ');

        }
    }
  
$pdf->Output();



?>