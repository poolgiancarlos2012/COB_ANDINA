<?php

include_once("../../libreria/funciones.php");

//$campania=$_REQUEST['campania'];
$todos = $_GET['todos'];
$carteras="";
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=tmo_detalle.xls");
header("Pragma:no-cache");
header("Expires:0");
$sql = "SELECT 
cli.codigo,
cuen.numero_cuenta,
cuen.inscripcion, 
cli.nombre, 
cli.numero_documento,
dir.direccion,
dir.distrito,
dir.zona,
cuen.telefono,
cuen.gestion,
cart.fecha_inicio,
cart.fecha_fin,
SEC_TO_TIME(avg(TIME_TO_SEC(TIMEDIFF(llama.fin_tmo,llama.inicio_tmo)))) as tmo,
SEC_TO_TIME(max(TIME_TO_SEC(TIMEDIFF(llama.fin_tmo,llama.inicio_tmo)))) as 'mayor duracion',
datosadd.dato29 as evento,
datosadd.dato28 as cluster,
dtlcuen.tramo AS segmento,
carfin.nombre as 'contactabilidad',
niv.nombre AS 'respuesta gestion',
fin.nombre as 'respuesta incidencia'
FROM ca_cuenta as cuen
INNER JOIN ca_detalle_cuenta AS dtlcuen ON dtlcuen.idcuenta = cuen.idcuenta
INNER JOIN ca_datos_adicionales_cuenta as datosadd ON datosadd.idcuenta = cuen.idcuenta AND cuen.retirado = 0
INNER JOIN ca_final as fin ON fin.idfinal = cuen.ultimo_idfinal
INNER JOIN ca_carga_final as carfin ON carfin.idcarga_final = fin.idcarga_final
LEFT JOIN ca_nivel AS niv ON niv.idnivel = fin.idnivel
INNER JOIN ca_cliente_cartera as clicar ON clicar.idcliente_cartera = cuen.idcliente_cartera AND clicar.retiro = 0
INNER JOIN ca_cliente as cli ON cli.idcliente = clicar.idcliente
INNER JOIN ca_cartera as cart ON cart.idcartera = clicar.idcartera
INNER JOIN ca_transaccion as trans ON trans.idcliente_cartera = clicar.idcliente_cartera
INNER JOIN ca_llamada as llama ON llama.idtransaccion = trans.idtransaccion
LEFT JOIN ca_direccion AS dir ON dir.numero_cuenta = cuen.numero_cuenta";
if($todos == 'false')
{
	$carteras=$_GET['cartera'];
	$data=lee($sql." WHERE clicar.idcartera IN ($carteras) GROUP BY cuen.numero_cuenta, cli.codigo");
}else{
	$data=lee($sql." GROUP BY cuen.numero_cuenta, cli.codigo");
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
				<td colspan='5' align='center'><b><h1>REPORTE TMO DETALLE</h1></b></td>
				<td><img src='http://200.31.116.162:5800/web/COBRAST/img/hdec.png' /></td>
			</tr>
			<tr height='10'><td></td></tr>
		</table>");



echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
		<td class='naranja'>CODIGO CLIENTE</td>
		<td class='naranja'>CODIGO CUENTA</td>
		<td class='naranja'>INSCRIPCION</td>
		<td class='naranja'>NOMBRE DEL ABONADO</td>
		<td class='naranja'>DNI</td>
		<td class='naranja'>TELEFONO</td>
		<td class='naranja'>NOMBRE GESTION</td>
		<td class='naranja'>INICIO GESTION</td>
		<td class='naranja'>FIN GESTION</td>
		<td class='naranja'>TMO</td>
		<td class='naranja'>MAYOR DURACION</td>
		<td class='naranja'>EVENTO</td>
		<td class='naranja'>CLUSTER</td>
		<td class='naranja'>SEGMENTO</td>
		<td class='naranja'>CONTACTABILIDAD</td>
		<td class='naranja'>RESPUESTA GESTION</td>
		<td class='naranja'>RESPUESTA INCIDENCIA</td>
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
			echo("<td >".$data[$i][8]."</td>");
			echo("<td >".$data[$i][9]."</td>");
			echo("<td >".$data[$i][10]."</td>");
			echo("<td >".$data[$i][11]."</td>");
			echo("<td >".$data[$i][12]."</td>");
			echo("<td >".$data[$i][13]."</td>");
			echo("<td >".$data[$i][14]."</td>");
			echo("<td >".$data[$i][15]."</td>");
			echo("<td >".$data[$i][16]."</td>");
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
