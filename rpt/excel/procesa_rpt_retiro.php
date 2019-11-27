<?php
require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';
	
date_default_timezone_set('America/Lima');
	
$factoryConnection= FactoryConnection::create('mysql');	
$connection = $factoryConnection->getConnection();

$idcampania=$_REQUEST['campania'];
$carteras=$_REQUEST['cartera'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=rpte_retiros.xls");
header("Pragma:no-cache");
header("Expires:0");

$sql="select 'HDEC' as 'AGENCIA', cu.telefono as 'TELEFONO', cu.numero_cuenta as 'INSCRIPCION', (select zona from ca_direccion where codigo_cliente=cu.codigo_cliente and idcartera=cu.idcartera limit 1) as 'ZONAL', car.nombre_cartera as 'GESTION', car.fecha_inicio as 'FEC.INI', car.fecha_fin as 'FEC.FIN',  cu.motivo_retiro as 'MOTIVO_RETIRO'
from ca_cuenta cu inner join ca_cartera car on cu.idcartera=car.idcartera where cu.retirado=1 and cu.idcartera in (".$carteras.")";

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
			<tr height='30'>
				<td width='30'></td>
				<td colspan='7' align='center'><b><h1>REPORTE RETIROS</h1></b></td>
				<td colspan='5'></td>
			</tr>
			<tr height='15'><td></td></tr>
		");

while( $row = $pr_sql->fetch(PDO::FETCH_ASSOC) ) {
	if($count==0){
		echo '<tr><td></td>';
		foreach( $row as $index => $value ) {
			echo('<td class="naranja">'.utf8_decode($index).'</td>');
		}
		echo '</tr>';
	}
	echo '<tr><td></td>';
	foreach( $row as $index => $value ) {
		if( $count%2==0 ) {
				echo '<td align="center" class="narlight" >'.utf8_decode($value).'</td>';
			}else{
				echo '<td align="center">'.utf8_decode($value).'</td>';
		}
	}
	echo '</tr>';
	
	$count++;
}
echo('</table>');
?>