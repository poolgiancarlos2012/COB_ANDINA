<?php

//require_once('../../phpincludes/excel/Workbook.php');
//require_once('../../phpincludes/excel/Worksheet.php');	

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$cartera = $_GET['Cartera'];
$servicio = $_GET['Servicio'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=rpte_empresas.xls");
header("Pragma:no-cache");
header("Expires:0");


/* $workbook = new Workbook("-");
  $workbook->setName('Reportes'); */

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

/* $xls =& $workbook->add_worksheet('REPORTE CLIENTES');

  $xls->write_string(1,1,'REPORTE CLIENTES');
  $xls->write_string(2,1,'Reporte Generado el: '.date("Y-m-d H:i:s"));

  $xls->write_string(5,1,"CODIGO CLIENTE");
  $xls->write_string(5,2,"NOMBRE");
  $xls->write_string(5,3,"NUMERO DOCUMENTO");
  $xls->write_string(5,4,"TIPO DOCUMENTO");
  $xls->write_string(5,5,"DIRECCION");
  $xls->write_string(5,6,"DEPARTAMENTO");
  $xls->write_string(5,7,"PROVINCIA");
  $xls->write_string(5,8,"DISTRITO");
  $xls->write_string(5,9,"NUMERO CUENTA");
  $xls->write_string(5,10,"MONEDA");
  $xls->write_string(5,11,"TOTAL DEUDA");
  $xls->write_string(5,12,"COMISION");
  $xls->write_string(5,13,"MONTO PAGADO");
  $xls->write_string(5,14,"STATUS"); */

//	$sql = " SELECT cu.codigo_cliente, CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'nombre', cli.numero_documento, 
//			cli.tipo_documento, dir.direccion, dir.departamento, dir.provincia, dir.distrito,
//			cu.numero_cuenta, cu.moneda , cu.total_deuda, cu.total_comision, cu.monto_pagado, IF( clicar.estado = 1, 'ACTIVO','INACTIVO' ) AS 'status'
//			FROM ca_direccion dir INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
//			ON cu.idcliente_cartera  = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND cli.idcliente = dir.idcliente
//			WHERE cu.idcartera = ? AND clicar.idcartera = ? AND cli.idservicio = ? AND dir.idcartera = ? AND dir.idtipo_referencia = 3  ";

$sql = " SELECT cu.codigo_cliente, CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'nombre', cli.numero_documento, 
			cli.tipo_documento, 
			( SELECT direccion FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3  LIMIT 1 ) AS 'direccion',
			( SELECT departamento FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3  LIMIT 1 ) AS 'departamento',
			( SELECT provincia FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3  LIMIT 1 ) AS 'provincia',
			( SELECT distrito FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3  LIMIT 1 ) AS 'distrito',
			cu.numero_cuenta, cu.moneda , cu.total_deuda, cu.total_comision, cu.monto_pagado, 
			IF( clicar.estado = 1, 'ACTIVO','INACTIVO' ) AS 'status'
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
			ON cu.idcliente_cartera  = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente 
			WHERE cu.idcartera IN ($cartera) AND clicar.idcartera IN ($cartera) AND cli.idservicio = ?  ";

$pr2 = $connection->prepare($sql);
/*$pr2->bindParam(1, $cartera, PDO::PARAM_INT);
$pr2->bindParam(2, $cartera, PDO::PARAM_INT);*/
$pr2->bindParam(1, $servicio, PDO::PARAM_INT);
//$pr2->bindParam(4,$cartera,PDO::PARAM_INT);
$pr2->execute();
//$countRow = 6;
$count = 0;
?>
<style type='text/css'>
    td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
    td.narlight{border:0px solid #fcd5b4;border-collapse:collapse;color:#000000;background-color:#fcd5b4;}
</style>
<?php

echo ("<table cellspacing='0' cellpadding='0' border='0' bordercolor='#FFFFFF'>
			<tr height='30'>
				<td width='30'></td>
				<td colspan='7' align='center'><b><h1>REPORTE CLIENTES</h1></b></td>
				<td colspan='5'></td>
			</tr>
			<tr height='15'><td></td></tr>
		");

while ($row = $pr2->fetch(PDO::FETCH_ASSOC)) {
    if ($count == 0) {
        echo '<tr><td></td>';
        foreach ($row as $index => $value) {
            echo('<td align="center" class="naranja">' . utf8_decode($index) . '</td>');
        }
        echo '</tr>';
    }
    echo '<tr><td></td>';
    foreach ($row as $index => $value) {
        if ($count % 2 == 0) {
            echo '<td align="center" class="narlight" >' . utf8_decode($value) . '</td>';
        } else {
            echo '<td align="center">' . utf8_decode($value) . '</td>';
        }
    }
    echo '</tr>';

    $count++;
}
echo('</table>');


/* while( $row = $pr2->fetch(PDO::FETCH_ASSOC) ) {
  $xls->write_string($countRow,1,$row['codigo_cliente']);
  $xls->write_string($countRow,2,$row['nombre']);
  $xls->write_string($countRow,3,$row['numero_documento']);
  $xls->write_string($countRow,4,$row['tipo_documento']);
  $xls->write_string($countRow,5,$row['direccion']);
  $xls->write_string($countRow,6,$row['departamento']);
  $xls->write_string($countRow,7,$row['provincia']);
  $xls->write_string($countRow,8,$row['distrito']);
  $xls->write_string($countRow,9,$row['numero_cuenta']);
  $xls->write_string($countRow,10,$row['moneda']);
  $xls->write_string($countRow,11,$row['total_deuda']);
  $xls->write_string($countRow,12,$row['total_comision']);
  $xls->write_string($countRow,13,$row['monto_pagado']);
  $xls->write_string($countRow,14,$row['status']);
  $countRow++;
  } */
//$workbook->close();
?>