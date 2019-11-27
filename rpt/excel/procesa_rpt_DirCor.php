<?php
require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';
	
date_default_timezone_set('America/Lima');
	
$factoryConnection= FactoryConnection::create('mysql');	
$connection = $factoryConnection->getConnection();

$carteras=$_REQUEST['cartera'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=rpte_empresas.xls");
header("Pragma:no-cache");
header("Expires:0");

$sql="select distinct * from
(select 
	date(dir.fecha_modificacion) as Fecha_Modif,
	'HDEC' as 'Agencia',
	cu.gestion,
	dir.codigo_cliente,
	cu.inscripcion as 'Cod. Cuenta',
	cu.telefono,
	(select nombre from ca_cliente where codigo=dir.codigo_cliente limit 1) as 'Nombre', 
	dir.direccion, dir.distrito, dir.Provincia, dir.departamento, dir.Referencia,
	'' as 'Fecha Ped Md Postal', '' as 'Observacion Area Distribucion'
from ca_direccion dir 
	left join ca_cuenta cu on cu.codigo_cliente=dir.codigo_cliente
where cu.retirado=0 and dir.is_new=0 and dir.fecha_modificacion is not null and cu.idcartera in (".$carteras.") order by 7) data";

$pr_sql=$connection->prepare($sql);
$pr_sql->execute();

//echo($sql);

$count=0;

?>
<style type='text/css'>
td.naranja{border:1px solid #f47b17;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
td.narlight{border-collapse:collapse;color:#000000;background-color:#fcd5b4;}

</style>
<?php

echo ("<table cellspacing='0' cellpadding='0' border='0' bordercolor='#FFFFFF'>
			<tr height='30'>
				<td width='30'></td>
				<td colspan='11' align='center'><b><h1>REPORTE DIRECCIONES INCORRECTAS</h1></b></td>
				<td colspan='5'></td>
			</tr>
			<tr height='15'><td></td></tr>
			<tr><td></td><td class='naranja' colspan='4' align='center'>Agencia</td><td class='naranja' colspan='4' align='center'>Cliente</td><td class='naranja' colspan='5' align='center'>Direccion Postal</td><td class='naranja' colspan='2' align='center'>Area Distribucion</td></tr>
		");

while( $row = $pr_sql->fetch(PDO::FETCH_ASSOC) ) {
	if($count==0){
		echo '<tr><td></td>';
		echo '<td align="center" class="naranja">N</td>';
		foreach( $row as $index => $value ) {
			echo('<td align="center" class="naranja">'.utf8_decode($index).'</td>');
		}
		echo '</tr>';
	}
	echo '<tr><td></td>';
	echo '<td align="center" class="naranja">'.($count+1).'</td>';
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