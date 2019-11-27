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

$workbook = new Workbook("-");
$workbook->setName('Reportes');

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$formatoCabeceras = & $workbook->add_format(array("color" => "blue", "bold" => "1", "bg_color" => "0x99999"));

$xls = & $workbook->add_worksheet('REPORTE DE NOTIFICACION');

$xls->write_string(1, 1, 'REPORTE DE NOTIFICACION');
$xls->write_string(2, 1, 'Reporte Generado el: ' . date("Y-m-d H:i:s"));

$sql = " SELECT CONCAT_WS('-',cu.gestion,cu.numero_cuenta) AS 'ID_ABONADO', cu.codigo_cliente 
				,CONCAT_WS('-',cu.gestion,cu.codigo_cliente) AS 'ID_CLIENTE'
				,( SELECT CONCAT_WS(' ',paterno,materno,nombre) FROM ca_cliente WHERE idservicio = $servicio AND codigo = cu.codigo_cliente LIMIT 1 ) AS 'NOMBRE'
				,cu.gestion AS 'GESTION',cu.telefono AS 'CELULAR',cu.numero_cuenta AS 'NROINS', cu.codigo_cliente AS 'CODIGO'
				,dir.direccion AS 'DIRECCION',dir.direccion AS 'DIRECCION_REFERENCIA',dir.distrito AS 'DISTRITO'
				, cu.total_deuda AS 'DEUDA', cu.monto_pagado AS 'MONTO', ( cu.total_deuda - cu.monto_pagado ) AS 'SALDO'
				,cu.numero_cuenta AS 'ANEXO'
				,( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado ) AS 'ESTADO_LLAM_RESUMEN'
				,( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado ) AS 'ESTADO_VISITA_RESUMEN'
				,( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado ) AS 'ESTADO_LLAM_ULTIMO'
				,( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado ) AS 'ESTADO_VISITA_ULTIMO'
				, '' AS 'ESTADO_VISI_1', '' AS 'FECH_VISI_1', '' AS 'CPG_VISI_1', '' AS 'OBS_VISI_1'
				, '' AS 'ESTADO_VISI_2', '' AS 'FECH_VISI_2', '' AS 'CPG_VISI_2', '' AS 'OBS_VISI_2'
				, '' AS 'ESTADO_VISI_3', '' AS 'FECH_VISI_3', '' AS 'CPG_VISI_3', '' AS 'OBS_VISI_3'
				, '' AS 'ESTADO_VISI_4', '' AS 'FECH_VISI_4', '' AS 'CPG_VISI_4', '' AS 'OBS_VISI_4'
				, '' AS 'ESTADO_VISI_5', '' AS 'FECH_VISI_5', '' AS 'CPG_VISI_5', '' AS 'OBS_VISI_5'
				, '' AS 'ESTADO_VISI_6', '' AS 'FECH_VISI_6', '' AS 'CPG_VISI_6', '' AS 'OBS_VISI_6'
				, '' AS 'ESTADO_VISI_7', '' AS 'FECH_VISI_7', '' AS 'CPG_VISI_7', '' AS 'OBS_VISI_7'
				, '' AS 'ESTADO_VISI_8', '' AS 'FECH_VISI_8', '' AS 'CPG_VISI_8', '' AS 'OBS_VISI_8'
				, '' AS 'ESTADO_VISI_9', '' AS 'FECH_VISI_9', '' AS 'CPG_VISI_9', '' AS 'OBS_VISI_9'
				, '' AS 'ESTADO_VISI_10', '' AS 'FECH_VISI_10', '' AS 'CPG_VISI_10', '' AS 'OBS_VISI_10',
				dir.zona AS 'ZONAL'
				FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu 
				ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = dir.idcliente
				WHERE cu.idcartera IN ($cartera) AND clicar.idcartera IN ($cartera) AND dir.idcartera IN ($cartera) AND dir.idtipo_referencia =3 ";

$pr = $connection->prepare($sql);
$pr->execute();
$countRow = 8;
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

    if ($countRow == 8) {
        $j = 1;
        foreach ($row as $index => $value) {

            $xls->write_string($countRow - 1, $j, $index, $formatoCabeceras);
            $xls->write_string($countRow, $j, $value);

            $j++;
        }
    } else {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($countRow, $j, $value);
            $j++;
        }
    }
    $countRow++;
}

$workbook->close();
?>