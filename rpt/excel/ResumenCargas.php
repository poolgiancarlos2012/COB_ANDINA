<?php
/* require_once('../../phpincludes/excel/Workbook.php');
  require_once('../../phpincludes/excel/Worksheet.php'); */

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ResumenCargas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$campania =  $_GET['Campania'];
$cartera =  $_GET['Cartera'];

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$sql = " SELECT
		car.nombre_cartera AS 'NOMBRE',
		car.fecha_inicio AS 'FECHA_INICIO',
		car.fecha_fin AS 'FECHA_FIN',
        car.evento AS 'EVENTO',
        car.cluster AS 'CLUSTER',
        car.segmento AS 'SEGMENTO',
        DATE(car.fecha_creacion) AS 'FECHA_PROCESO',
		( SELECT COUNT( DISTINCT codigo_cliente ) FROM ca_cuenta WHERE idcartera = car.idcartera ) AS 'CLIENTES',
		( SELECT COUNT(*) FROM ca_cuenta WHERE idcartera = car.idcartera ) AS 'CUENTAS',
        ( SELECT SUM(total_deuda) FROM ca_cuenta WHERE idcartera = car.idcartera ) AS 'EXIGIBLE'
        FROM ca_cartera car 
        WHERE car.idcampania = ? AND car.idcartera IN ( ".$cartera." ) AND car.estado = 1 ";
?>
<table>
    <tr>
        <td colspan="2" style="font-weight:bold;font-size:24px;">RESUMEN DE CARGAS</td>
    </tr>
    <tr>
        <td>Reporte generado:</td>
        <td><?php echo date("Y-m-d"); ?></td>
    </tr>
    <tr>
        <td style="height:40px;"></td>
    </tr>
</table>
<?php
$pr = $connection->prepare($sql);
$pr->bindParam(1, $campania, PDO::PARAM_INT);
$pr->execute();
$fila = 6;
echo '<table>';
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
    if ($fila == 6) {
        echo '<tr>';
        foreach ($row as $index => $value) {
            echo '<td align="center" style="background-color:blue;color:white;">' . $index . '</td>';
        }
        echo '</tr>';
    }
    echo '<tr>';
    foreach ($row as $index => $value) {
        echo '<td align="center" >' . $value . '</td>';
    }
    echo '</tr>';
    
    $fila++;
}
echo '</table>';

?>