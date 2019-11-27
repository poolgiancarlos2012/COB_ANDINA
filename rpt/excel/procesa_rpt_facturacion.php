<?php

include_once("../../libreria/funciones.php");

//$campania=$_REQUEST['campania'];
$todos = $_GET['todos'];
$idServicio = $_GET['servicio'];
$carteras="";
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=reporte_facturacion.xls");
header("Pragma:no-cache");
header("Expires:0");
$sql = "select 
nombre_cartera, 
cart.fecha_creacion,
(select count(ca_cuenta.idcuenta) from ca_cuenta where ca_cuenta.idcartera = cart.idcartera) as 'total_base',
(select count(distinct ca_transaccion.idcliente_cartera) from ca_transaccion 
inner join ca_cliente_cartera on ca_cliente_cartera.idcliente_cartera = ca_transaccion.idcliente_cartera 
where idfinal in(select idfinal from ca_final where  idfinal in(select idfinal from ca_final_servicio where idservicio = $idServicio) and idcarga_final = 3) and idcartera = cuen.idcartera) as 'contacto_efectivo',
(select min(llamadas) from (select count(ca_llamada.idllamada)as llamadas,ca_cuenta.idcartera as idcartera from ca_cuenta  INNER JOIN ca_transaccion  ON ca_cuenta.idcliente_cartera = ca_transaccion.idcliente_cartera 
LEFT JOIN ca_llamada  ON ca_llamada.idtransaccion = ca_transaccion.idtransaccion GROUP BY ca_cuenta.idcuenta) as countLlamadas where countLlamadas.idcartera = cuen.idcartera) as 'vueltas',
(((select count(distinct ca_transaccion.idcliente_cartera) from ca_transaccion 
inner join ca_cliente_cartera on ca_cliente_cartera.idcliente_cartera = ca_transaccion.idcliente_cartera 
where idfinal in(select idfinal from ca_final where  idfinal in(select idfinal from ca_final_servicio where idservicio = $idServicio) and idcarga_final = 3) and idcartera = cuen.idcartera)/(select count(ca_cuenta.idcuenta) from ca_cuenta where ca_cuenta.idcartera = cart.idcartera))*100) as '%',
sec_to_time(avg(time_to_sec(timediff(fin_tmo,inicio_tmo)))) as 'tmo_promedio',
sum(cuen.monto_pagado)  
from ca_cartera as cart 
inner join ca_cuenta as cuen on cuen.idcartera = cart.idcartera 
inner join ca_cliente_cartera as clicar on clicar.idcliente_cartera = cuen.idcliente_cartera
left join ca_transaccion as trans on trans.idcliente_cartera = clicar.idcliente_cartera
left join ca_llamada as llama on llama.idtransaccion = trans.idtransaccion 
";
if($todos == 'false')
{
	$carteras=$_GET['cartera'];
	$data=lee($sql." WHERE cuen.idcartera IN ($carteras) GROUP BY cuen.idcartera");
}else{
	$data=lee($sql." GROUP BY cuen.idcartera");
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
				<td colspan='5' align='center'><b><h1>REPORTE FACTURACION</h1></b></td>
				<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
		<td class='naranja'>DETALLE DE PRODUCTO</td>
		<td class='naranja'>FECHA RECEPCION</td>
		<td class='naranja'>TOTAL DE BASE</td>
		<td class='naranja'>CONTACTO EFECTIVO</td>
		<td class='naranja'>VUELTAS</td>
		<td class='naranja'>CONTACTABILIDAD(%)</td>
		<td class='naranja'>TMO PROMEDIO</td>
		<td class='naranja'>TOTAL EN S/.</td>
		<td></td>
	</tr>		
	");
if(is_array($data))
{
	for($i=0;$i<count($data);$i++){
		//if($i%2==0){
			echo("<tr>");
			echo("<td >".$data[$i][0]."</td>");
			echo("<td >".$data[$i][1]."</td>");
			echo("<td >".$data[$i][2]."</td>");
			echo("<td >".$data[$i][3]."</td>");
			echo("<td >".$data[$i][4]."</td>");
			echo("<td >".$data[$i][5]."</td>");
			echo("<td >".$data[$i][6]."</td>");
			echo("<td >".$data[$i][7]."</td>");
			echo("</tr>");	
		/*}else{
			echo("<tr>");
			echo("<td class='narlight2'>".$data[$i][1]."</td>");
			echo("<td class='narlight2'>".$data[$i][2]."</td>");
			echo("<td class='narlight2'>".$data[$i][3]."</td>");
			echo("<td class='narlight2'>".$data[$i][4]."</td>");
			echo("<td class='narlight2'>".$data[$i][5]."</td>");
			echo("<td class='narlight2'>".$data[$i][6]."</td>");
			echo("</tr>");	
		}*/
	}
}
echo("</table>");
?>
