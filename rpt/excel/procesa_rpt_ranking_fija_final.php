<?php
require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';
	
date_default_timezone_set('America/Lima');
	
$factoryConnection= FactoryConnection::create('mysql');	
$connection = $factoryConnection->getConnection();

$carteras=$_REQUEST['idcartera'];
$fecha_inicio=$_REQUEST['fecha_inicio'];
$fecha_fin=$_REQUEST['fecha_fin'];
$idservicio=$_REQUEST['idservicio'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=ranking_final.xls");
header("Pragma:no-cache");
header("Expires:0");

/*$sql_q_cont="SELECT DISTINCT carfin.nombre FROM ca_final  fin INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final 
WHERE fin.idfinal IN ( SELECT idfinal FROM ca_final_servicio WHERE estado=1 AND idservicio=".$idservicio.") ";
					
$pr_q_cont=$connection->prepare($sql_q_cont);
$pr_q_cont->execute();

$query_cont="";
while( $row = $pr_q_cont->fetch(PDO::FETCH_ASSOC) ) {
	foreach( $row as $index => $value ) {
		$query_cont=$query_cont." ( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal inner join ca_carga_final carfin 
on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion 
where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha)  between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='$value') as '$value',";	
	}
}*/

$sql = "select 
	CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) as teleoperador, 
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera  
	 where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."') as 'LLAMADAS',
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='NOC') as 'NOC', 
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE') as 'CNE', 
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF') as 'CEF', 
	
	IFNULL(ROUND(((( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE')+
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF'))/
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera 
		where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."')*100),2)
		,0)	AS '% LLAMADAS',

	ROUND(sum(if(retirado=0,cu.total_deuda,0)),2)  as 'DEUDA TOTAL', 
	ROUND(sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,cu.monto_pagado,0)),2)  as 'PAGO', 
	ROUND((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0 ,cu.monto_pagado,0)))/(sum(cu.total_deuda))*100,2) AS 'PORC %',	

	sum(if(retirado=0,1,0)) as 'CUENTAS. TOTALES',
	sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)) AS 'CUENTAS. RECUP.',
	ROUND(((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)))/(sum(if(retirado=0,1,0)))*100),2) as 'PORC. %',

	ROUND((IFNULL( ((( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE')+
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF'))/
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera 
		where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."')*100),0)
		+((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0 ,cu.monto_pagado,0)))/(sum(cu.total_deuda))*100)+
	((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)))/(sum(if(retirado=0,1,0)))*100))/3,2)	AS 'PROMEDIO %' 
	
	from ca_cuenta cu 
		inner join ca_cliente_cartera clicar on cu.codigo_cliente=clicar.codigo_cliente
		left join ca_usuario_servicio ususer on clicar.idusuario_servicio=ususer.idusuario_servicio
		left join ca_usuario usu on ususer.idusuario=usu.idusuario
where clicar.idcartera in (".$carteras.") AND cu.idcartera in (".$carteras.") and clicar.idusuario_servicio!=0 and retirado=0
group by clicar.idusuario_servicio order by 1 ASC";

$pr_sql=$connection->prepare($sql);
$pr_sql->execute();

$count=0;

?>
<style type='text/css'>
td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
td.narlight{border:0px solid #fcd5b4;border-collapse:collapse;color:#000000;background-color:#fcd5b4;}
</style>
<?php

echo ("<table cellspacing='0' cellpadding='0' border='0' bordercolor='#FFFFFF'>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='7' align='center'><b><h1>REPORTE RANKING FINAL</h1></b></td>
				<td colspan='5'></td>
			</tr>
			<tr height='30'><td></td></tr>
		");

while( $row = $pr_sql->fetch(PDO::FETCH_ASSOC) ) {
	if($count==0){
		echo '<tr><td></td>';
		foreach( $row as $index => $value ) {
			if(substr($index,0,3)=='sep'){
				echo('<td width="5"></td>');
			}else{
				echo('<td class="naranja">'.utf8_decode($index).'</td>');
			}
		}
		echo '</tr>';
	}
	echo '<tr><td></td>';
	foreach( $row as $index => $value ) {
		if(substr($index,0,3)=='sep'){
			echo('<td width="5"></td>');
		}else{		
			if( $count%2==0 ) {
					echo '<td align="center" class="narlight" >'.utf8_decode($value).'</td>';
				}else{
					echo '<td align="center">'.utf8_decode($value).'</td>';
			}
		}
	}
	echo '</tr>';
	
	$count++;
}
echo('</table>');
?>