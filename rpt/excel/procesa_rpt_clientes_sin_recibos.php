<?php

include_once("../../libreria/funciones.php");

//$campania=$_REQUEST['campania'];
$todos = $_GET['todos'];
$carteras="";
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=clientes_sin_recibos_fisicos.xls");
header("Pragma:no-cache");
header("Expires:0");
$sql = "SELECT 
	DISTINCT cuen.idcuenta, 
	cuen.telefono,
	dtlcuen.fecha_ciclo,
	cli.nombre,
	dir.direccion,
	dir.codigo_postal,
	fin.nombre,
	now() as 'fecha',
	dtlcuen.codigo_operacion,
        dataad.dato29,
        cuen.total_deuda
FROM ca_transaccion as tran 
INNER JOIN ca_cuenta AS cuen ON tran.idcliente_cartera = cuen.idcliente_cartera AND cuen.is_retiro = 0 AND tran.idfinal = 259
INNER JOIN ca_datos_adicionales_cuenta as dataad ON dataad.idcuenta = cuen.idcuenta
INNER JOIN ca_final as fin ON fin.idfinal = tran.idfinal
INNER JOIN ca_detalle_cuenta as dtlcuen ON dtlcuen.idcuenta = cuen.idcuenta
INNER JOIN ca_cliente_cartera as clicar ON cuen.idcliente_cartera = clicar.idcliente_cartera AND clicar.retiro = 0
INNER JOIN ca_cliente as cli ON cli.idcliente = clicar.idcliente
LEFT JOIN  ca_direccion as dir ON dir.numero_cuenta = cuen.numero_cuenta";
if($todos == 'false')
{
	$carteras=$_GET['cartera'];
	$data=lee($sql." WHERE cuen.idcartera IN ($carteras)");
}else{
	$data=lee($sql);
}

//echo $carteras;

/*$fecha_inicio=$_REQUEST['fechainicio'];
$fecha_fin=$_REQUEST['fechafin'];*/


echo("<style type='text/css'>
td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
td.narlight{border:0px solid #fcd5b4;border-collapse:collapse;color:#000000;background-color:#fcd5b4;}
td.narlight2{border:0px solid #fde9d9;border-collapse:collapse;color:#000000;background-color:#fde9d9;}
</style>");

echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='5' align='center'><b><h1>REPORTE DE CLIENTES SIN RECIBO FISICO</h1></b></td>
				<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'>
        <tr>
	<td width='40'></td>
        <td class='naranja'>TELEFONO</td>
        <td class='naranja'>CICLO</td>
        <td class='naranja'>NOMBRE DEL CLIENTE</td>
        <td class='naranja'>DIRECCION</td>
        <td class='naranja'>CODIGO POSTAL</td>
        <td class='naranja'>OBSERVACION</td>
        <td class='naranja'>FECHA</td>
        <td class='naranja'>FACTURA</td>
        <td class='naranja'>TRAMO</td>
        <td class='naranja'>MONTO EXIGIBLE</td>
        <td></td>
        </tr>		
	");
if(is_array($data))
{
	for($i=0;$i<count($data);$i++){
		if($i%2==0){
			echo("<tr><td>");
			echo("<td class='narlight'>".$data[$i][1]."</td>");
			echo("<td class='narlight'>".$data[$i][2]."</td>");
			echo("<td class='narlight'>".$data[$i][3]."</td>");
			echo("<td class='narlight'>".$data[$i][4]."</td>");
			echo("<td class='narlight'>".$data[$i][5]."</td>");
			echo("<td class='narlight'>".$data[$i][6]."</td>");
			echo("<td class='narlight'>".$data[$i][7]."</td>");
			echo("<td class='narlight'>".$data[$i][8]."</td>");
			echo("<td class='narlight'>".$data[$i][9]."</td>");
			echo("<td class='narlight'>".$data[$i][10]."</td>");
			echo("</tr>");	
		}else{
			echo("<tr><td>");
			echo("<td class='narlight2'>".$data[$i][1]."</td>");
			echo("<td class='narlight2'>".$data[$i][2]."</td>");
			echo("<td class='narlight2'>".$data[$i][3]."</td>");
			echo("<td class='narlight2'>".$data[$i][4]."</td>");
			echo("<td class='narlight2'>".$data[$i][5]."</td>");
			echo("<td class='narlight2'>".$data[$i][6]."</td>");
			echo("<td class='narlight2'>".$data[$i][7]."</td>");
			echo("<td class='narlight2'>".$data[$i][8]."</td>");
			echo("<td class='narlight2'>".$data[$i][9]."</td>");
			echo("<td class='narlight2'>".$data[$i][10]."</td>");
			echo("</tr>");	
		}
	}
}
echo("</table>");
?>
