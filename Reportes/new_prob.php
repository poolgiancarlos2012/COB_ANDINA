<?php

require('fpdf.php');
include_once('config.php');
class PDF extends FPDF
{
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

    //$codigo= $_POST['codigo'];


     $con = new DB;
     $clientes = $con->conectar(); 
     
/*
*/
     $f = $con->conectar(); 
     $str = "SELECT * from ca_cuenta where idcartera=2 and codigo_cliente in (
'20474691986',
'20537292823',
'29412884',
'09701913',
'07756913',
'20536293851',
'43372299',
'10007197',
'20511168105',
'20519774322',
'07841966',
'20509665347',
'45091001',
'09390738',
'20519254248',
'07481317',
'40164103',
'18166424',
'06186647',
'40768070',
'42610370',
'09599844',
'20552787766',
'06056847',
'10471857',
'00700218',
'42964264',
'09888222',
'40593523',
'09259350',
'29618458',
'20551851665',
'20600254490',
'09142204',
'08435559',
'43427579',
'20550018633',
'44336246',
'07257651',
'43185096',
'10710137',
'10819573',
'20548141860',
'10318073',
'09805400',
'07414979',
'09633933',
'06658006',
'09750758',
'70674316',
'40306110',
'71751950',
'20511763160',
'10308004',
'40288973',
'09881428',
'40397214',
'41331251',
'20392606620',
'45680294',
'44167165',
'46351996',
'02863706',
'20521855569',
'10614263',
'43413538',
'43296095',
'09299222',
'20502612809',
'29236865',
'09885746',
'08082333',
'09463909',
'25791898',
'41536202',
'44732815'


                                            ) and estado=1 GROUP BY codigo_cliente";
     $f = mysql_query($str); 
     $u = mysql_num_rows($f);
     
     $pdf=new PDF('P','mm','A4');
     $inc =1;
    
    for($j=1;$j<=$u;$j++){
    $fi = mysql_fetch_array($f);
    
    $clientes = $con->conectar();  
    $strConsulta ="SELECT codigo,CONCAT(nombre,'  ',paterno,'  ',materno) as nombre,razon_social from ca_cliente where codigo='$fi[codigo_cliente]'";
    $clientes = mysql_query($strConsulta);
    
    $fila = mysql_fetch_array($clientes);
   
    
    $pdf->Open();
    $pdf->AddPage();

    $pdf->SetMargins(20,20);

    $pdf->Ln(20);
    $pdf->SetFont('Arial','',10);



    // 
    //if(trim(isset($fila['nombre'],""))){
    if($fila['nombre']<>'    '){
        $pdf->Cell(0,6,'Nombre: '.$fila['nombre'],0,1);
    }

    if($fila['razon_social']<>''){
         $pdf->Cell(0,6,'Nombre: '.$fila['razon_social'],0,1);
    }
   
    
    $pdf->Cell(0,6,'Documento: '.$fila['codigo'],0,1);

    
    $pdf->Ln(7);
    
    $pdf->SetWidths(array(40,40));
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(1);

        for($i=0;$i<1;$i++)
            {
                 $pdf->Row(array('Contrato', 'Deuda'));
            }
    
    $historial = $con->conectar();  
    $strConsulta = "SELECT  negocio,CONCAT('USD  ',ROUND(sum(cuota_mensual+otros+seguros),2)) as suma from ca_cuenta  where estado=1 and idcartera=2 and codigo_cliente='$fi[codigo_cliente]' GROUP BY negocio";
    
    $historial = mysql_query($strConsulta);
    $numfilas = mysql_num_rows($historial);
    
    for ($i=0; $i<$numfilas; $i++)
        {
            $fila = mysql_fetch_array($historial);
            $pdf->SetFont('Arial','',10);
            
            
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0);
                $pdf->Row(array($fila['negocio'], $fila['suma']));
         
        }

    $total= $con->conectar();  
    $str ="SELECT  codigo_cliente,CONCAT('USD  ',ROUND(sum(cuota_mensual+otros+seguros),2)) as sum from ca_cuenta  where estado=1 and idcartera=2 and codigo_cliente='$fi[codigo_cliente]'    GROUP BY codigo_cliente";
    $total = mysql_query($str);
    $fil = mysql_fetch_array($total);


    $pdf->Ln(25);

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,6,'Total deuda: '.$fil['sum'],0,1);
    $pdf->Cell(0,6,'Nro: '.$inc,0,1);

   $inc++;

   $rec5_width=20;
$rec6_height=120;

$pdf->SetXY($rec5_width,$rec6_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar a un acuerdo sobre la deuda'));

$pdf->SetXY($rec5_width,$rec6_height+5);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('que manteniene con nosotros ya que no viene honrandola a pesar de nuestros constantes requerimientos.'));



$pdf->SetXY($rec5_width,$rec6_height+10);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Por favor preguntar por Oscar Villaba al telefono 712-3500 anexo(102201)'));

$pdf->SetXY($rec5_width,$rec6_height+21);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Atte.'));

   
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
$pdf->Write(8,utf8_decode('Requerimos de su presencia en nuestras oficinas con caracter de urgencia a fin de llegar a un acuerdo sobre la deuda'));

$pdf->SetXY($rec7_width,$rec8_height+62);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('que manteniene con nosotros en su calidad de Garante Solidario ya que los ultimos ___ meses el asociado en'));

$pdf->SetXY($rec7_width,$rec8_height+66);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('mencion no cumple en honrarla.'));

$pdf->SetXY($rec7_width,$rec8_height+72);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('Por favor preguntar por el Sr. ________________ a los telefonos 712-3500 anexo(102201).'));


if($fi['inscripcion']<>'NO ADJUDICADO'){
    $pdf->Image('img/pre_judicial.jpg' , 140 ,55, 50 , 20,'JPG', ' ');

}



}
  
$pdf->Output();



?>