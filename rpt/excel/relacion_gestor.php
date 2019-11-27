<?php

require_once('../../phpincludes/excel/Workbook.php');
require_once('../../phpincludes/excel/Worksheet.php');

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$cartera = $_GET['cartera'];
$servicio = $_GET['servicio'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=rpte_empresas.xls");
header("Pragma:no-cache");
header("Expires:0");

/* $workbook = new Workbook("-");
  $workbook->setName('Reportes'); */

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

/* $xls =& $workbook->add_worksheet('RELACION DE GESTORES');

  $xls->write_string(1,1,'REPORTE DE RELACION DE GESTORES');
  $xls->write_string(2,1,'Reporte Generado el: '.date("Y-m-d H:i:s")); */

$sqlCartera = " SELECT tabla, numero_cuenta, moneda_cuenta FROM ca_cartera WHERE idcartera = ? ";

$pr = $connection->prepare($sqlCartera);
$pr->bindParam(1, $cartera, PDO::PARAM_INT);
$pr->execute();
$dataCartera = $pr->fetchAll(PDO::FETCH_ASSOC);

$sql = " SELECT clicar.codigo_cliente, CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'cliente',
		IFNULL( cli.numero_documento,'')  AS 'numero_documento',
		IFNULL( cli.tipo_documento,'')  AS 'tipo_documento',
		( SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer On ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'GESTOR',
		cu.numero_cuenta, cu.moneda, 
		TRUNCATE( SUM( cu.total_deuda ),2 ) AS 'deuda',
		TRUNCATE( SUM( cu.total_comision ),2 ) AS 'interes',
		TRUNCATE( SUM( cu.monto_pagado ),2 ) AS 'monto_pagado',
		TRUNCATE( SUM( cu.total_deuda + cu.total_comision - cu.monto_pagado ),2 )  AS 'deuda_total',
		IF( clicar.estado = 1, 'ACTIVO', 'INACTIVO' ) AS 'status'
		FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
		ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente  
		WHERE cu.idcartera IN ($cartera) AND clicar.idcartera IN ($cartera) AND cli.idservicio = ? 
		GROUP BY cu.numero_cuenta, cu.moneda ";

if (trim($dataCartera[0]['moneda_cuenta']) != '') {
    $sql.=" , cu.moneda ";
}

/* $xls->write_string(5,1,'CODIGO CLIENTE');
  $xls->write_string(5,2,'CLIENTE');
  $xls->write_string(5,3,'NUMERO DOCUMENTO');
  $xls->write_string(5,4,'TIPO DOCUMENTO');
  $xls->write_string(5,5,'GESTOR');
  $xls->write_string(5,6,'NUMERO CUENTA');
  $xls->write_string(5,7,'MONEDA');
  $xls->write_string(5,8,'DEUDA');
  $xls->write_string(5,9,'INTERES');
  $xls->write_string(5,10,'MONTO_PAGADO');
  $xls->write_string(5,11,'DEUDA_TOTAL');
  $xls->write_string(5,12,'STATUS'); */

$pr2 = $connection->prepare($sql);
/*$pr2->bindParam(1, $cartera, PDO::PARAM_INT);
$pr2->bindParam(2, $cartera, PDO::PARAM_INT);*/
$pr2->bindParam(1, $servicio, PDO::PARAM_INT);
$pr2->execute();


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
				<td colspan='7' align='center'><b><h1>RELACION DE GESTORES</h1></b></td>
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



/* $countRow=6;
  while( $row = $pr2->fetch(PDO::FETCH_ASSOC) ) {
  $xls->write_string($countRow,1,$row['codigo_cliente']);
  $xls->write_string($countRow,2,$row['cliente']);
  $xls->write_string($countRow,3,$row['numero_documento']);
  $xls->write_string($countRow,4,$row['tipo_documento']);
  $xls->write_string($countRow,5,$row['GESTOR']);
  $xls->write_string($countRow,6,$row['numero_cuenta']);
  $xls->write_string($countRow,7,$row['moneda']);
  $xls->write_string($countRow,8,$row['deuda']);
  $xls->write_string($countRow,9,$row['interes']);
  $xls->write_string($countRow,10,$row['monto_pagado']);
  $xls->write_string($countRow,11,$row['deuda_total']);
  $xls->write_string($countRow,12,$row['status']);
  $countRow++;
  }

  $workbook->close(); */
?>