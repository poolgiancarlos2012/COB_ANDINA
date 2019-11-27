<?php

require_once('../../phpincludes/excel/Workbook.php');
require_once('../../phpincludes/excel/Worksheet.php');

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$cartera = $_GET['Cartera'];
$servicio = $_GET['Servicio'];
$fecha_inicio = $_GET['FechaInicio'];
$fecha_fin = $_GET['FechaFin'];
$tabla2="";
/*$workbook = new Workbook("-");
$workbook->setName('Reportes');*/

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();
$tabla="<table border=0><tr><td COLSPAN=2 style='font-weight:bold;font-size:22'>REPORTE ENVIO CALL A CAMPO</td></tr>
        <tr><td colspan=2>Fecha :".  date('Y-m-d H:i:s')."</td></tr>
        </table>";
$tabla.= "<table border='1' bordercolor='#FFFFFF'>
    <tr>
    </tr>
        <tr>
            <td style='color:#ffffff;background:#1F497D'>CODIGO CLIENTE</td>
            <td style='color:#ffffff;background:#1F497D'>NOMBRE</td>
            <td style='color:#ffffff;background:#1F497D'>NUMERO DOCUMENTO</td>
            <td style='color:#ffffff;background:#1F497D'>TIPO DOCUMENTO</td>
            <td style='color:#ffffff;background:#1F497D'>DIRECCION</td>
            <td style='color:#ffffff;background:#1F497D'>DEPARTAMENTO</td>
            <td style='color:#ffffff;background:#1F497D'>PROVINCIA</td>
            <td style='color:#ffffff;background:#1F497D'>DISTRITO</td>
            <td style='color:#ffffff;background:#1F497D'>NUMERO CUENTA</td>
            <td style='color:#ffffff;background:#1F497D'>MONEDA</td>
            <td style='color:#ffffff;background:#1F497D'>TOTAL DEUDA</td>
            <td style='color:#ffffff;background:#1F497D'>MONTO PAGADO</td>
        </tr>";

$sql = "SELECT distinct clicar.codigo_cliente,cli.nombre,cli.numero_documento,cli.tipo_documento,dir.direccion,
dir.departamento,dir.provincia,dir.distrito,cu.numero_cuenta ,cu.moneda,cu.total_deuda,cu.monto_pagado
FROM ca_direccion dir
INNER JOIN ca_cliente_cartera clicar
ON dir.idcliente_cartera=clicar.idcliente_cartera
INNER JOIN ca_cliente cli 
ON cli.codigo=clicar.codigo_cliente
INNER JOIN ca_cuenta cu 
ON cu.idcliente_cartera=clicar.idcliente_cartera
WHERE clicar.codigo_cliente IN(
		SELECT distinct clicar.codigo_cliente FROM ca_cliente_cartera clicar
		INNER JOIN ca_llamada lla
		ON clicar.idcliente_cartera=lla.idcliente_cartera
		WHERE clicar.idcartera IN ($cartera) AND lla.enviar_campo=1 AND lla.fecha BETWEEN ? AND ?
) and dir.idcartera in ($cartera) and clicar.idcartera IN ($cartera) AND  cu.idcartera IN ($cartera) and dir.idtipo_referencia=2 and cli.idservicio=? ";

$pr = $connection->prepare($sql);
/*$pr->bindParam(1, $cartera, PDO::PARAM_INT);
$pr->bindParam(2, $cartera, PDO::PARAM_INT);*/
$pr->bindParam(1, $fecha_inicio, PDO::PARAM_INT);
/*$pr->bindParam(4, $cartera, PDO::PARAM_INT);
$pr->bindParam(5, $cartera, PDO::PARAM_INT);*/
$pr->bindParam(2, $fecha_fin, PDO::PARAM_STR);
$pr->bindParam(3, $servicio, PDO::PARAM_STR);
$pr->execute();
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
    $tabla.='<tr>';
    foreach($row as $index=>$value){
        if($index=='codigo_cliente' || $index=='numero_cuenta' || $index=='numero_documento'){
            $tabla.='<td style="background:#DCE6F1">="'.$value.'"</td>';
        }else{
            if($index=='direccion'){
                $tabla.='<td style="background:#DCE6F1">'.str_replace("\t", "", utf8_decode($value)).'</td>';                            
            }else{
                $tabla.='<td style="background:#DCE6F1">'.$value.'</td>';            
            }
        }
    }
    $tabla.='</tr>';
    

}
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=ENVIO CALL A CAMPO.xls");
header("Pragma:no-cache");
header("Expires:0");
echo $tabla;
?>