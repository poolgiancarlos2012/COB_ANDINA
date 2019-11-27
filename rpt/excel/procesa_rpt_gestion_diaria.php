<?php

include_once("../../libreria/funciones.php");

//$campania=$_REQUEST['campania'];
$carteras=$_REQUEST['cartera'];

//echo $carteras;

/*$fecha_inicio=$_REQUEST['fechainicio'];
$fecha_fin=$_REQUEST['fechafin'];*/
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=gestion_diaria.xls");
header("Pragma:no-cache");
header("Expires:0");

$sql="select cli.codigo as 'CODIGO_CLIENTE',
cu.inscripcion as 'CODIGO_CUENTA',
cu.numero_cuenta AS 'INSCRIPCION',
concat_ws(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE ABONADO',
cli.numero_documento AS 'DNI',
( select concat(direccion, ', ', numero, ', ', calle ) from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'DIRECCION',
( select distrito from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'DISTRITO',
( select zona from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'ZONAL',
cu.telefono AS 'TELEFONO',detcu.fecha_alta AS 'FECHA ALTA',detcu.fecha_baja AS 'FECHA BAJA',
car.nombre_cartera AS 'NOMBRE GESTION', car.fecha_inicio AS 'FECHA INICIO GESTION', car.fecha_fin AS 'FECHA FIN GESTION'
, IF( car.idcampania = 1, 'T1', 'T2' ) AS 'EVENTO',
detcu.total_deuda AS 'EXIGIBLE', detcu.total_deuda_soles AS 'TOTAL', detcu.monto_mora AS 'AJUSTADO', 
detcu.monto_pagado AS 'PAGO', ( detcu.total_deuda - detcu.monto_pagado ) AS 'SALDO',
if(  detcu.total_deuda <= detcu.monto_pagado  ,'C', if(  detcu.total_deuda > detcu.monto_pagado AND detcu.monto_pagado>0  , 'A', 'SP'  ) ) AS 'ESTADO PAGO',
(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'USUARIO' ,
IF( clicar.is_noc_predictivo = 1 , 'NOC',( SELECT nombre from ca_carga_final where idcarga_final = cu.ul_carga  )) AS 'CONTACTABILIDAD',
IF( clicar.is_noc_predictivo = 1 , 'NO CONTESTAN/BAJO PUERTA', ( SELECT niv.nombre from ca_final fin inner join ca_nivel niv on niv.idnivel = fin.idnivel  where fin.idfinal = cu.ul_estado  )) AS 'RESPUESTA GESTION',
IF( clicar.is_noc_predictivo = 1, 'SIN INCIDENCIA', ( SELECT nombre from ca_final where idfinal = cu.ul_estado  )) AS 'RESPUESTA INCIDENCIA',
GROUP_CONCAT(detcu.codigo_operacion SEPARATOR '|' ) AS 'NRO_FACTURAS',
IF( clicar.is_noc_predictivo = 1, 'NO CONTESTA PREDICTIVO',replace(cu.ul_observacion,'\n',' ')) AS 'OBSERVACION',
if(cu.ml_carga is null,(IF( clicar.is_noc_predictivo = 1 , 'NOC','')),( SELECT nombre from ca_carga_final where idcarga_final = cu.ml_carga  )) AS 'ML CONTACTABILIDAD',
IF(cu.ml_estado is null,(IF(clicar.is_noc_predictivo = 1 , 'NO CONTESTAN/BAJO PUERTA','')),(SELECT niv.nombre from ca_final fin inner join ca_nivel niv on niv.idnivel = fin.idnivel  where fin.idfinal = cu.ml_estado)) AS 'ML RESPUESTA GESTION',
IF(cu.ml_estado is null,(IF(clicar.is_noc_predictivo = 1 , 'SIN INCIDENCIA','')),(SELECT nombre from ca_final where idfinal = cu.ml_estado )) AS 'ML RESPUESTA INCIDENCIA',
IF(cu.ml_observacion is null,(IF( clicar.is_noc_predictivo = 1, 'NO CONTESTA PREDICTIVO','')),(replace(cu.ml_observacion,'\n',' '))) AS 'ML_OBSERVACION'
from ca_cliente cli inner join ca_cartera car inner join ca_cliente_cartera clicar inner join ca_cuenta cu inner join ca_detalle_cuenta detcu
on detcu.idcuenta = cu.idcuenta and cu.idcliente_cartera = clicar.idcliente_cartera and clicar.idcartera = car.idcartera and clicar.idcliente = cli.idcliente
where clicar.idcartera in (".$carteras.") and cu.idcartera in (".$carteras.") and detcu.idcartera in (".$carteras.") and cu.retirado=0 
GROUP BY cu.numero_cuenta
";
$data=lee($sql);

echo("<style type='text/css'>
td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
td.narlight{border:0px solid #1f497d;border-collapse:collapse;color:#000000;background-color:#fde4d0;}
td.bdazul{border:1px solid #1f497d;border-collapse:collapse;color:#000000;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");

echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='4' align='center'><b><h1>REPORTE GESTION DIARIA</h1></b></td>
				
			</tr>
			<tr height='10'><td></td></tr>
		</table>");


echo("<table border='1' bordercolor='#FFFFFF'<tr height='20'><tr>
	<td width='40'></td><td class='naranja'>CODIGO CLIENTE</td><td class='naranja'>CODIGO CUENTA</td><td class='naranja'>INSCRIPCION</td><td class='naranja'>NOMBRE ABONADO</td>
	<td class='naranja'>DNI</td><td class='naranja'>DIRECCION</td><td class='naranja'>DISTRITO</td><td class='naranja'>ZONAL</td>
	<td class='naranja'>TELEFONO</td><td class='naranja'>FECHA ALTA</td><td class='naranja'>FECHA BAJA</td><td class='naranja'>NOMBRE CARTERA</td><td class='naranja'>FECHA INICIO</td>
	<td class='naranja'>FECHA FIN</td></td><td class='naranja'>EVENTO</td><td class='naranja'>DEUDA EXIGIBLE</td><td class='naranja'>DEUDA TOTAL</td>
	<td class='naranja'>DEUDA AJUSTADA</td><td class='naranja'>PAGO</td><td class='naranja'>SALDO</td><td class='naranja'>ESTADO DE PAGO</td>
	<td class='naranja'>USUARIO</td><td class='naranja'>CONTACTABILIDAD</td><td class='naranja'>RESPUESTA GESTION</td><td class='naranja'>RESPUESTA INCIDENCIA</td><td class='naranja'>OBSERVACION</td></tr>		
	");


if($data!=''){

	for($i=0;$i<count($data);$i++){
		if($i%2==0){
			echo("<tr><td></td>");
			echo("<td class='narlight'>".$data[$i][0]."</td>");
			echo("<td class='narlight'>".$data[$i][1]."</td>");
			echo("<td class='narlight'>".$data[$i][2]."</td>");
			echo("<td class='narlight'>".$data[$i][3]."</td>");
			echo("<td class='narlight'>".$data[$i][4]."</td>");
			echo("<td class='narlight' width='380'>".$data[$i][5]."</td>");
			echo("<td class='narlight'>".$data[$i][6]."</td>");
			echo("<td class='narlight'>".$data[$i][7]."</td>");
			echo("<td class='narlight'>".$data[$i][8]."</td>");
			echo("<td class='narlight'>".$data[$i][9]."</td>");
			echo("<td class='narlight'>".$data[$i][10]."</td>");
			echo("<td class='narlight'>".$data[$i][11]."</td>");
			echo("<td class='narlight'>".$data[$i][12]."</td>");
			echo("<td class='narlight'>".$data[$i][13]."</td>");
			echo("<td class='narlight'>".$data[$i][14]."</td>");
			echo("<td class='narlight'>".$data[$i][15]."</td>");
			echo("<td class='narlight'>".$data[$i][16]."</td>");
			echo("<td class='narlight'>".$data[$i][17]."</td>");
			echo("<td class='narlight'>".$data[$i][18]."</td>");
			echo("<td class='narlight'>".$data[$i][19]."</td>");
			echo("<td class='narlight'>".$data[$i][20]."</td>");
			echo("<td class='narlight' width='380'>".$data[$i][21]."</td>");
			echo("<td class='narlight'>".$data[$i][22]."</td>");
			echo("<td class='narlight'>".$data[$i][23]."</td>");
			echo("<td class='narlight'>".$data[$i][24]."</td>");
			echo("<td class='narlight'>".$data[$i][25]."</td>");
			echo("</tr>");	
		}else{
			echo("<tr><td></td>");
			echo("<td>".$data[$i][0]."</td>");
			echo("<td>".$data[$i][1]."</td>");
			echo("<td>".$data[$i][2]."</td>");
			echo("<td>".$data[$i][3]."</td>");
			echo("<td>".$data[$i][4]."</td>");
			echo("<td width='380'>".$data[$i][5]."</td>");		
			echo("<td>".$data[$i][6]."</td>");
			echo("<td>".$data[$i][7]."</td>");
			echo("<td>".$data[$i][8]."</td>");
			echo("<td>".$data[$i][9]."</td>");
			echo("<td>".$data[$i][10]."</td>");
			echo("<td>".$data[$i][11]."</td>");
			echo("<td>".$data[$i][12]."</td>");
			echo("<td>".$data[$i][13]."</td>");
			echo("<td>".$data[$i][14]."</td>");
			echo("<td>".$data[$i][15]."</td>");
			echo("<td>".$data[$i][16]."</td>");
			echo("<td>".$data[$i][17]."</td>");
			echo("<td>".$data[$i][18]."</td>");
			echo("<td>".$data[$i][19]."</td>");
			echo("<td>".$data[$i][20]."</td>");
			echo("<td width='380'>".$data[$i][21]."</td>");
			echo("<td>".$data[$i][22]."</td>");
			echo("<td>".$data[$i][23]."</td>");
			echo("<td>".$data[$i][24]."</td>");
			echo("<td>".$data[$i][25]."</td>");
			echo("</tr>");	
		}
	}

}
echo("</table>");
?>
