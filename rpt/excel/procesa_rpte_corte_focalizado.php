<?php

include_once("../../libreria/funciones.php");
$todos = $_GET['todos'];
$carteras="";
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=corte_focalizado.xls");
header("Pragma:no-cache");
header("Expires:0");
$sql = "SELECT 
	DISTINCT cuen.idcuenta, 
	cuen.numero_cuenta as inscripcion,
	cuen.telefono,
	cli.codigo,
	cuen.inscripcion as cuenta,
	cuen.gestion,
	cart.fecha_inicio,
	cart.fecha_fin,
	cuen.total_deuda,
	cuen.ul_observacion,
	cuen.ultimo_fecha_cp,
	cuen.monto_pagado
FROM ca_transaccion as tran 
INNER JOIN ca_cuenta AS cuen ON tran.idcliente_cartera = cuen.idcliente_cartera AND cuen.is_retiro = 0 AND  not tran.idfinal = 277
INNER JOIN ca_cliente_cartera as clicar ON cuen.idcliente_cartera = clicar.idcliente_cartera
INNER JOIN ca_cliente as cli ON cli.idcliente = clicar.idcliente 
INNER JOIN ca_cartera AS cart ON cart.idcartera = cuen.idcartera 
WHERE cuen.idcuenta in(select idcuenta from ca_detalle_cuenta where NOT codigo_operacion IN(select codigo_operacion from ca_pago where estado = 1))
 AND cuen.is_reclamo = 0 AND  cuen.ultimo_fecha_cp > now() AND cuen.monto_pagado = 0 AND clicar.retiro = 0";
if($todos == 'false')
{
	$carteras=$_GET['cartera'];
	$data=lee($sql." AND cuen.idcartera IN($carteras)");
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
				<td colspan='5' align='center'><b><h1>REPORTE DE CORTE FOCALIZADO</h1></b></td>
				<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
	<td class='naranja'>IDCUENTA</td>
	<td class='naranja'>INSCRIPCION</td>
	<td class='naranja'>TELEFONO</td>
	<td class='naranja'>CLIENTE</td>
	<td class='naranja'>CUENTA</td>
	<td class='naranja'>GESTION</td>
	<td class='naranja'>INICIO GESTION</td>
	<td class='naranja'>FIN GESTION</td>
	<td class='naranja'>MONTO EXIGIBLE</td>
	<td class='naranja'>MOTIVO DEL CORTE FOCALIZADO</td>
	<td></td>
	</tr>		
	");
if(is_array($data))
{
    for($i=0;$i<count($data);$i++){
        /*if($i%2==0){*/
                echo("<tr>");
                echo("<td >".$data[$i][0]."</td>");
                echo("<td >".$data[$i][1]."</td>");
                echo("<td >".$data[$i][2]."</td>");
                echo("<td >".$data[$i][3]."</td>");
                echo("<td >".$data[$i][4]."</td>");
                echo("<td >".$data[$i][5]."</td>");
                echo("<td >".$data[$i][6]."</td>");
                echo("<td >".$data[$i][7]."</td>");
                echo("<td >".$data[$i][8]."</td>");
                echo("<td >".$data[$i][9]."</td>");
                echo("</tr>");	
        /*}else{
                echo("<tr>");
                echo("<td class='narlight2'>".$data[$i][1]."</td>");
                echo("<td class='narlight2'>".$data[$i][1]."</td>");
                echo("<td class='narlight2'>".$data[$i][2]."</td>");
                echo("<td class='narlight2'>".$data[$i][3]."</td>");
                echo("<td class='narlight2'>".$data[$i][4]."</td>");
                echo("<td class='narlight2'>".$data[$i][5]."</td>");
                echo("<td class='narlight2'>".$data[$i][6]."</td>");
                echo("<td class='narlight2'>".$data[$i][7]."</td>");
                echo("<td class='narlight2'>".$data[$i][8]."</td>");
                echo("<td class='narlight2'>".$data[$i][9]."</td>");
                echo("</tr>");	
        }*/
    }
}
echo("</table>");
?>
