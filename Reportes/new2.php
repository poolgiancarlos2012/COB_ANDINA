<?php
require('fpdf.php');
include_once('config.php');


class PDF extends FPDF{


    
 function Header() {
             
        

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

$con = new DB;
$clientes = $con->conectar(); 
//,'09615941','42006962','07161726','42312584'
$f = $con->conectar(); 
$str = "SELECT * from ca_cuenta where codigo_cliente in (
'20128808711'
) and estado=1  and idcartera=2 GROUP BY codigo_cliente";
$f = mysql_query($str);
$h = mysql_num_rows($f);
$pdf = new PDF('P', 'mm', 'A4');





$a=1;
$b=1;
for($j=1;$j<=$h;$j++){



$fi = mysql_fetch_array($f);
    
$clientes = $con->conectar();  
$strConsulta ="SELECT c.codigo,CONCAT(c.nombre,'  ',c.paterno,'  ',c.materno) as nombre,d.direccion,d.distrito,c.tipo_persona,c.razon_social from ca_cliente c INNER JOIN ca_direccion d on c.codigo=d.codigo_cliente  where codigo='$fi[codigo_cliente]'";
$clientes = mysql_query($strConsulta);

$fila = mysql_fetch_array($clientes);

$aval= $con->conectar();
$str2 ="SELECT r.codigo_cliente,r.representante_legal,CONCAT(r.nombre,'  ',r.paterno,'  ',r.materno) as nombre,
d.direccion,COUNT(DISTINCT r.codigo_cliente) as avales from ca_representante_legal r inner join ca_direccion d
on r.codigo_cliente=d.codigo_cliente WHERE r.contrato='$fi[negocio]' and r.codigo_cliente<>'$fi[codigo_cliente]'";
$aval = mysql_query($str2);
$fil2 = mysql_fetch_array($aval);




if(($a % 2) <> 0) {
    $pdf->AddPage();
}
$pdf->SetMargins(10, 10);
$pdf->Image('img/footer-logo.jpg' , 10 ,10, 20 , 10,'JPG','');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,4,'',0,0);
$pdf->Cell(50,4,'FICHA DEL ABONADO',0,0);
$pdf->Cell(30,4,utf8_decode('Campaña:'),0,0);
$pdf->Cell(30,4,'Orden:  '.$a,0,0);
$pdf->Cell(10,4,'Nro:  '.$a,0,1);
$a++;

$t = date("d/m/Y");

$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(80,4,'CODIGO: '.$fi['negocio'],0,0);
$pdf->Cell(60,4,'DNI/RUC: '.$fi['codigo_cliente'],0,0);
$pdf->Cell(0,4,'FECHA DE IMP: '.$t,0,1);
if($fila['nombre']<>'    '){
        $pdf->Cell(140,4,'Nombre: '.$fila['nombre'],0,0);
    }

    if($fila['razon_social']<>''){
         $pdf->Cell(140,4,'Nombre: '.$fila['razon_social'],0,0);
    }
//$pdf->Cell(140,4,'NOMBRE: '.$fila['nombre'],0,0);
$pdf->Cell(0,4,'TP: '.$fila['tipo_persona'],0,1);
$pdf->Cell(0,4,'DIRECCION: '.$fila['direccion'],0,1);
$pdf->Cell(80,4,'DISTRITO: '.$fila['distrito'],0,0);
$pdf->Cell(60,4,'Q_AVAL: '.$fil2['avales'],0,0);
$pdf->Cell(0,4,'T Adjudicacion: '.$fi['inscripcion'],0,1);
$pdf->Cell(0,4,'AVAL1: '.$fil2['nombre'],0,1);
$pdf->Cell(0,4,'HIPOTECA: '.$fil2['direccion'],0,1);

$total= $con->conectar();  
$str ="SELECT  codigo_cliente,CONCAT('USD  ',ROUND(sum(cuota_mensual+otros+seguros),2)) as sum from ca_cuenta  where estado=1  and idcartera=2 and codigo_cliente='$fi[codigo_cliente]' and  negocio='$fi[negocio]'  GROUP BY codigo_cliente";
$total = mysql_query($str);
$fil = mysql_fetch_array($total);





$pdf->Ln(1);


$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,4,'Datos de Producto(s) en Gestion: ',0,0);
$pdf->Cell(60,4,'Deuda Total en $/.       '.$fil['sum'],0,0);
$pdf->Cell(0,4,'Semana:  '.$fi['subnegocio'],0,1);
/*
$rec3_width=10;
$rec4_height=55;

$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec3_width, $rec4_height, 190, 25, 2, 'DF');
*/

$valor = $con->conectar();  
$str8 = "SELECT  d.descripcion_fogapi,c.dato2 from ca_cuenta c INNER JOIN ca_detalle_cuenta d  on
c.idcuenta=d.idcuenta  where c.estado=1   and c.idcartera=2 and c.negocio='$fi[negocio]'";
$valor = mysql_query($str8);
$fil4 = mysql_fetch_array($valor);


$pdf->Ln(1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(50,4,'Sit. Entrega: '.$fil4['dato2'],0,0);
$pdf->Cell(50,4,'Valor Certificado: '.$fil4['descripcion_fogapi'],0,0);
$pdf->Cell(0,4,'Gestor: '.$fi['gestor_cobranza'],0,1);
$pdf->Ln(1);
$pdf->Cell(5);
$pdf->SetWidths(array(8,25,25,15,15));
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(230,230,255);
$pdf->SetDrawColor(255,255,225);
$pdf->SetTextColor(1);


$str3 = "SELECT  d.fecha_vencimiento,c.cuota_mensual,c.otros,c.seguros from ca_cuenta c INNER JOIN ca_detalle_cuenta d  on
c.idcuenta=d.idcuenta  where c.estado=1  and c.idcartera=2 and c.negocio='$fi[negocio]' and ( c.cuota_mensual or c.otros<>0 or c.seguros<>0)  ORDER BY d.fecha_vencimiento desc";

$historial = mysql_query($str3);

$r = mysql_num_rows($historial);

for($i=0;$i<1;$i++)
        {
             $pdf->Row(array('Nro','F.Venc', 'Cuota Mensual','Seguro','Otros'));
        }

$count=1;
for ($i=0; $i<$r; $i++)
    {
        $pdf->Cell(5);
        $fila = mysql_fetch_array($historial);
        $pdf->SetFont('Arial','',8);
        
        
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
            $pdf->Row(array($count,$fila['fecha_vencimiento'],$fila['cuota_mensual'], $fila['seguros'],$fila['otros']));
            $count++;
    
    }

if($r==1){
    $pdf->Ln(12);
}
if($r==2){
    $pdf->Ln(7);
}
if($r==3){
    $pdf->Ln(2);
}

//$pdf->Ln(9);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Asignado_a: ',0,0);
/*
$rec5_width=10;
$rec6_height=85;

$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec5_width, $rec6_height, 190, 20, 2, 'DF');
*/
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,4,'Mejor Gestion: ',0,0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,4,'Call: ',0,1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,4,'Telefono: ',0,1);
$pdf->Cell(0,6,'Fecha: ',0,1);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,6,'Validacion de telefonos: ',0,0);
$pdf->Cell(30,6,'(  )',0,0);
$pdf->Cell(30,6,'(  )',0,0);
$pdf->Cell(20,6,'(  )',0,0);
$pdf->Cell(0,6,'(  ) Hora de contacto:___________',0,0);
/*
$rec5_width=70;
$rec6_height=85;

$pdf->SetFillColor(255,255,255); //fondo blanco 
$pdf->SetLineWidth(0.2); //grosor linea
$pdf->Rect($rec5_width, $rec6_height, 125, 14, 2, 'DF');
*/
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Gestion Campo: ',0,0);

$pdf->Ln(5);
$pdf->SetFont('Arial','',8);
$pdf->Cell(50,6,'FECHA VISITA:______________',0,0);
$pdf->Cell(50,6,'HORA VISITA:______________',0,0);
$pdf->Cell(30,6,'COD.RPTA ',0,0);
$pdf->Cell(0,6,'Descripcion del inmueble: ',0,1);
$pdf->Cell(130,6,'Observaciones: ',0,0);
$pdf->Cell(0,6,'____________________________________',0,1);
$pdf->Cell(0,6,'_______________________________________________________________________________________________________________________',0,1);
$pdf->Cell(0,6,'_______________________________________________________________________________________________________________________',0,1);

$pdf->Ln(4);



$rec1_width=10;
$rec2_height=25;

 //fondo blanco 
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec1_width, $rec2_height, 190, 25, 2, 'D');


$rec3_width=10;
$rec4_height=55;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec3_width, $rec4_height, 190, 28, 2, 'D');

$rec5_width=10;
$rec6_height=88;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec5_width, $rec6_height, 190, 20, 2, 'D');


$rec5_width=70;
$rec6_height=88;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.2); //grosor linea
$pdf->Rect($rec5_width, $rec6_height, 125, 14, 2, 'D');


$rec7_width=10;
$rec8_height=113;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec7_width, $rec8_height, 190, 25, 2, 'D');





if($b % 2 ==0) {
    //segundo

$pdf->Image('img/footer-logo.jpg' , 10 ,139, 20 , 10,'JPG','');
$rec1_width=10;
$rec2_height=156;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec1_width, $rec2_height, 190, 25, 2, 'D');

$rec3_width=10;
$rec4_height=186;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec3_width, $rec4_height, 190, 28, 2, 'D');

$rec5_width=10;
$rec6_height=219;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec5_width, $rec6_height, 190, 20, 2, 'D');


$rec5_width=70;
$rec6_height=219;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.2); //grosor linea
$pdf->Rect($rec5_width, $rec6_height, 125, 14, 2, 'D');


$rec7_width=10;
$rec8_height=244;

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.4); //grosor linea
$pdf->RoundedRect($rec7_width, $rec8_height, 190, 25, 2, 'D');

}
$b++;


/*
// LABEL 1

$pdf->SetXY($rec1_width+5,$rec2_height+1);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('CODIGO:'));

$pdf->SetXY($rec1_width+5,$rec2_height+6);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('NOMBRE:')).$fila['nombre'];

$pdf->SetXY($rec1_width+5,$rec2_height+11);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('DIRECCION:'));

$pdf->SetXY($rec1_width+5,$rec2_height+16);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('DISTRITO:'));

$pdf->SetXY($rec1_width+5,$rec2_height+21);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('AVAL:'));

$pdf->SetXY($rec1_width+5,$rec2_height+26);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('HIPOTECA:'));

// RPTA 1

$pdf->SetXY($rec1_width+30,$rec2_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('8057.194.02'));

$pdf->SetXY($rec1_width+30,$rec2_height+6);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('J Y B SEPARADORA INDUSTRIAL S.A.C.'));

$pdf->SetXY($rec1_width+30,$rec2_height+11);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('AV. SEPARADORA INDUSTRIAL 734 mz k lote 21'));

$pdf->SetXY($rec1_width+30,$rec2_height+16);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('ATE'));

$pdf->SetXY($rec1_width+30,$rec2_height+21);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('BARRIENTOS ZAVALA KATHERINE GRACE'));

$pdf->SetXY($rec1_width+30,$rec2_height+26);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('AV LAS DUPRAS N 163 RESIDENCIAL MONTERRICO'));

// LABEL 2

$pdf->SetXY($rec1_width+120,$rec2_height+1);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('FECHA IMP:'));

$pdf->SetXY($rec1_width+120,$rec2_height+6);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('DNI/RUC:'));

$pdf->SetXY($rec1_width+120,$rec2_height+11);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('T.P.:'));

$pdf->SetXY($rec1_width+120,$rec2_height+16);
$pdf->SetFont('Arial','B', 9);
$pdf->Write(8,utf8_decode('TIPO ADJUDICACION:'));

// $pdf->SetXY($rec1_width+120,$rec2_height+21);
// $pdf->SetFont('Arial','', 9);
// $pdf->Write(8,utf8_decode('AVAL:'));

// $pdf->SetXY($rec1_width+120,$rec2_height+26);
// $pdf->SetFont('Arial','', 9);
// $pdf->Write(8,utf8_decode('HIPOTECA:'));

// RPTA 2

$pdf->SetXY($rec1_width+160,$rec2_height+1);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('27/06/2016'));

$pdf->SetXY($rec1_width+160,$rec2_height+6);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('20101724892'));

$pdf->SetXY($rec1_width+160,$rec2_height+11);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('TITULAR'));

$pdf->SetXY($rec1_width+160,$rec2_height+16);
$pdf->SetFont('Arial','', 9);
$pdf->Write(8,utf8_decode('REMATE'));

// $pdf->SetXY($rec1_width+120,$rec2_height+21);
// $pdf->SetFont('Arial','', 9);
// $pdf->Write(8,utf8_decode('AVAL:'));

// $pdf->SetXY($rec1_width+120,$rec2_height+26);
// $pdf->SetFont('Arial','', 9);
// $pdf->Write(8,utf8_decode('HIPOTECA:'));

*/


}

$pdf->Output();



?>