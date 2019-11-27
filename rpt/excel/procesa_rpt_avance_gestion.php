<?php

include_once("../../libreria/funciones.php");

$carteras=$_REQUEST['carteras'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=avance_gestion.xls");
header("Pragma:no-cache");
header("Expires:0");

$sql="select car.nombre_cartera, car.fecha_inicio, car.fecha_fin, count(*) as 'cantidad', sum( if(clicar.id_ultima_llamada=0,1,0) ) AS 'sin_gestion',
sum( if(clicar.id_ultima_llamada!=0,1,0) ) AS 'gestionados'
from ca_cliente_cartera clicar inner join ca_cartera car on car.idcartera = clicar.idcartera
where clicar.idcartera in ( ".$carteras." ) and clicar.retiro=0
group by clicar.idcartera";
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
				<td colspan='5' align='center'><b><h1>REPORTE AVANCE GESTIONES</h1></b></td>
				<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
	<td width='40'></td><td class='naranja'>Nombre Cartera</td><td class='naranja'>Fecha Inicio</td><td class='naranja'>Fecha Fin</td><td class='naranja'>Cantidad</td><td class='naranja'>Sin Gestion</td><td class='naranja'>Gestionados</td><td></td></tr>		
	");

for($i=0;$i<count($data);$i++){
	if($i%2==0){
		echo("<tr><td>");
		echo("<td class='narlight'>".$data[$i][0]."</td>");
		echo("<td class='narlight'>".$data[$i][1]."</td>");
		echo("<td class='narlight'>".$data[$i][2]."</td>");
		echo("<td class='narlight'>".$data[$i][3]."</td>");
		echo("<td class='narlight'>".$data[$i][4]."</td>");
		echo("<td class='narlight'>".$data[$i][5]."</td>");
		echo("</tr>");	
	}else{
		echo("<tr><td>");
		echo("<td class='narlight2'>".$data[$i][0]."</td>");
		echo("<td class='narlight2'>".$data[$i][1]."</td>");
		echo("<td class='narlight2'>".$data[$i][2]."</td>");
		echo("<td class='narlight2'>".$data[$i][3]."</td>");
		echo("<td class='narlight2'>".$data[$i][4]."</td>");
		echo("<td class='narlight2'>".$data[$i][5]."</td>");
		echo("</tr>");	
	}
}
echo("</table>");
?>