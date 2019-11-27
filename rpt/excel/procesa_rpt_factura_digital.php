<?php

include_once("../../libreria/funciones.php");

//$campania=$_REQUEST['campania'];
/*$cant_carteras=explode(',',$_GET['cartera'];
$carteras="";
for($i=0;$i<$cant_carteras;$i++){
	if(!empty($_REQUEST['cart'.($i+1)])){
		$carteras.=$_REQUEST['cart'.($i+1)].",";	
	}
}
$long=strlen($carteras);*/
$carteras=$_GET['cartera'];

//echo $carteras;

/*$fecha_inicio=$_REQUEST['fechainicio'];
$fecha_fin=$_REQUEST['fechafin'];*/
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=factura_digital.xls");
header("Pragma:no-cache");
header("Expires:0");
$sql = "SELECT 
	now() as 'fecha', 
	carte.nombre_cartera as 'cartera',
	fact.fecha_creacion as 'fecha_emision',
	cue.inscripcion as 'inscripcion',
	cue.telefono as 'telefono',
	cli.nombre as 'cliente',
	fact.solicita,
	concat(usu.paterno,' ',usu.materno,' ',usu.nombre) as 'remitente',
	fact.correo as 'correo'
from ca_factura_digital as fact INNER JOIN ca_cliente_cartera as clicar ON fact.idcliente_cartera = clicar.idcliente_cartera 
INNER JOIN ca_cliente AS cli ON cli.idcliente = clicar.idcliente 
INNER JOIN ca_cuenta as cue ON cue.idcuenta = fact.idcuenta
INNER JOIN ca_usuario_servicio as ususerv ON ususerv.idusuario_servicio = fact.usuario_creacion
INNER JOIN ca_usuario as usu ON usu.idusuario = ususerv.idusuario
INNER JOIN ca_cartera as carte ON carte.idcartera = clicar.idcartera WHERE fact.estado = 1 AND fact.is_send = 1 AND carte.idcartera IN($carteras)";

$data=lee($sql);
echo("<style type='text/css'>
td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
td.narlight{border:0px solid #fcd5b4;border-collapse:collapse;color:#000000;background-color:#fcd5b4;}
td.narlight2{border:0px solid #fde9d9;border-collapse:collapse;color:#000000;background-color:#fde9d9;}
</style>");

echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='5' align='center'><b><h1>REPORTE FACTURA DIGITAL</h1></b></td>
				<!--<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>-->
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
	<td width='40'></td><td class='naranja'>Fecha</td><td class='naranja'>Cartera</td><td class='naranja'>Emision</td><td class='naranja'>Inscripcion</td><td class='naranja'>Telefono</td><td class='naranja'>Cliente</td><td class='naranja'>Persona que solicia</td><td class='naranja'>Persona que emite duplicado</td><td class='naranja' >Correo</td></tr>		
	");
if(is_array($data))
{
	for($i=0;$i<count($data);$i++){
		if($i%2==0){
			echo("<tr><td>");
			echo("<td class='narlight'>".$data[$i][0]."</td>");
			echo("<td class='narlight'>".$data[$i][1]."</td>");
			echo("<td class='narlight'>".$data[$i][2]."</td>");
			echo("<td class='narlight'>".$data[$i][3]."</td>");
			echo("<td class='narlight'>".$data[$i][4]."</td>");
			echo("<td class='narlight'>".$data[$i][5]."</td>");
			echo("<td class='narlight'>".$data[$i][6]."</td>");
			echo("<td class='narlight'>".$data[$i][7]."</td>");
			echo("<td class='narlight'>".$data[$i][8]."</td>");
			echo("</tr>");	
		}else{
			echo("<tr><td>");
			echo("<td class='narlight2'>".$data[$i][0]."</td>");
			echo("<td class='narlight2'>".$data[$i][1]."</td>");
			echo("<td class='narlight2'>".$data[$i][2]."</td>");
			echo("<td class='narlight2'>".$data[$i][3]."</td>");
			echo("<td class='narlight2'>".$data[$i][4]."</td>");
			echo("<td class='narlight2'>".$data[$i][5]."</td>");
			echo("<td class='narlight2'>".$data[$i][6]."</td>");
			echo("<td class='narlight2'>".$data[$i][7]."</td>");
			echo("<td class='narlight2'>".$data[$i][8]."</td>");
			echo("</tr>");	
		}
	}
}
echo("</table>");
?>
