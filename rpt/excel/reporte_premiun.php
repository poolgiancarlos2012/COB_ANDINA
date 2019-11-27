<?php

require_once('../../phpincludes/excel/Workbook.php');
require_once('../../phpincludes/excel/Worksheet.php');

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$workbook = new Workbook("-");
$workbook->setName('Reportes');

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$formatoCabeceras = & $workbook->add_format(array("color" => "blue", "bold" => "1", "bg_color" => "0x99999"));

$xls = & $workbook->add_worksheet('REPORTE PREMIUN');

$xls->write_string(1, 1, 'REPORTE PREMIUN');
$xls->write_string(2, 1, 'Reporte Generado el: ' . date("Y-m-d H:i:s"));

$servicio = $_GET['Servicio'];
$cartera = $_GET['Cartera'];

$sql = " SELECT cu.numero_cuenta AS 'NUMERO_CUENTA', clicar.codigo_cliente AS 'CODIGO_CLIENTE',
		cli.tipo_documento AS 'TIPO_DOCUMENTO',cli.numero_documento AS 'NUMERO_DOCUMENTO',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE',detcu.tramo AS 'TRAMO',
		cu.moneda AS 'MONEDA',cu.total_deuda AS 'DEUDA', cu.total_comision AS 'INTERES', cu.monto_pagado AS 'MONTO_PAGADO',
		( SELECT direccion FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3 LIMIT 1 ) AS 'DIRECCION',
		( SELECT departamento FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3 LIMIT 1 ) AS 'DEPARTAMENTO',
		( SELECT provincia FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3 LIMIT 1 ) AS 'PROVINCIA',
		( SELECT distrito FROM ca_direccion WHERE idcliente = clicar.idcliente AND idtipo_referencia = 3 LIMIT 1 ) AS 'DISTRITO',
		'HDEC' AS 'EMPRESA', cu.producto AS 'PRODUCTO', 
		( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado ) AS 'RESUMEN_GESTION_TELEFONICA',
		( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado ) AS 'RESUMEN_GESTION_DOMICILIARIA',
		( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga ) AS 'CARGA',
		cu.ml_fcpg AS 'FECHA_COMPROMISO',
		cu.ml_observacion AS 'OBSERVACION',
		IF( clicar.estado = 1, 'ACTIVO', 'INACTIVO' ) AS 'STATUS'
		FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_detalle_cuenta detcu
		ON detcu.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente
		WHERE  detcu.idcartera IN ($cartera) AND cu.idcartera IN ($cartera) AND clicar.idcartera IN ($cartera) AND cli.idservicio = ? 
		 ";

$pr = $connection->prepare($sql);
/*$pr->bindParam(1, $cartera, PDO::PARAM_INT);
$pr->bindParam(2, $cartera, PDO::PARAM_INT);
$pr->bindParam(3, $cartera, PDO::PARAM_INT);*/
$pr->bindParam(1, $servicio, PDO::PARAM_INT);
$pr->execute();
$count = 5;
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
    if ($count == 5) {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($count - 1, $j, $index, $formatoCabeceras);
            $j++;
        }
    }
    $j = 1;
    foreach ($row as $index => $value) {
        $xls->write_string($count, $j, $value);
        $j++;
    }
    $count++;
}

$workbook->close();
?>