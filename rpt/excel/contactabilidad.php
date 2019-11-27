<?php

require_once('../../phpincludes/excel/Workbook.php');
require_once('../../phpincludes/excel/Worksheet.php');

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');
$idcartera = $_GET['Cartera'];
$workbook = new Workbook("-");
$workbook->setName('Reportes');

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$xls = & $workbook->add_worksheet('Contactabilidad');
$formatoCabeceras = & $workbook->add_format(array("color" => "blue", "bold" => "1", "bg_color" => "0x41"));
$formatoCabeceras2 = & $workbook->add_format(array("color" => "red", "bold" => "1"));

$xls->write_string(1, 1, 'Reporte Generado el: ', $formatoCabeceras);
$xls->write_string(1, 2, date("Y-m-d H:i:s"), $formatoCabeceras2);
$xls->write_string(2, 1, 'Fecha: ', $formatoCabeceras);
$xls->write_string(2, 2, 'Del ' . $_GET['FechaInicio'] . ' al ' . $_GET['FechaFin'], $formatoCabeceras2);
$xls->write_string(3, 1, 'CONTACTABILIDAD CARGA', $formatoCabeceras);

$sqlCursor = 'SELECT DISTINCT carfin.idcarga_final, carfin.nombre
FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser
ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final
WHERE finser.idservicio = ?';
$pr = $connection->prepare($sqlCursor);
$pr->bindParam(1, $_GET['Servicio'], PDO::PARAM_INT);
$pr->execute();
$sqlConcat = '';
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
    $sqlConcat .= " SUM( IF( fin.idcarga_final = ".$row['idcarga_final']." ,1 ,0 ) ) AS '".$row['nombre']."',";
}
$sql = "SELECT
( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  ) AS 'TELEOPERADOR', 
".$sqlConcat."
COUNT(*) AS 'TOTAL'
 FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
 ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera 
 WHERE clicar.idcartera IN ($idcartera) AND DATE( tran.fecha_creacion ) BETWEEN ? AND ? 
 GROUP BY clicar.idusuario_servicio ORDER BY 1 ";
//$sql = " CALL contactabilidad ( ?,?,?,? ) ";

$pr = $connection->prepare($sql);
/*$pr->bindParam(1, $_GET['Servicio'], PDO::PARAM_INT);
$pr->bindParam(2, $_GET['Cartera'], PDO::PARAM_INT);
$pr->bindParam(3, $_GET['FechaInicio'], PDO::PARAM_STR);
$pr->bindParam(4, $_GET['FechaFin'], PDO::PARAM_STR);*/
$pr->bindParam(1, $_GET['FechaInicio'], PDO::PARAM_STR);
$pr->bindParam(2, $_GET['FechaFin'], PDO::PARAM_STR);
$pr->execute();
$count = 0;
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
    if ($count == 0) {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($count + 5, $j, $index, $formatoCabeceras);
            $xls->write_string($count + 6, $j, $value);
            $j++;
        }
    } else {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($count + 6, $j, $value);
            $j++;
        }
    }
    $count++;
}

$xls = & $workbook->add_worksheet('Contactabilidad por hora');

$xls->write_string(1, 1, 'Reporte Generado el: ', $formatoCabeceras);
$xls->write_string(1, 2, date("Y-m-d H:i:s"), $formatoCabeceras2);
$xls->write_string(2, 1, 'Fecha:', $formatoCabeceras);
$xls->write_string(2, 2, 'Del ' . $_GET['FechaInicio'] . ' al ' . $_GET['FechaFin'], $formatoCabeceras2);
$xls->write_string(3, 1, 'CONTACTABILIDAD POR HORA', $formatoCabeceras);

$sqlCPorHora = " SELECT 
		T1.nombre AS 'CARGA', IFNULL(T2.7,0) AS '7' , IFNULL(T2.8,0) AS '8', IFNULL(T2.9,0) AS '9', IFNULL(T2.10,0) AS '10', IFNULL(T2.11,0) AS '11', 
		IFNULL(T2.12,0) AS '12', IFNULL(T2.13,0) AS '13', IFNULL(T2.14,0) AS '14', IFNULL(T2.15,0) AS '15', IFNULL(T2.16,0) AS '16', 
		IFNULL(T2.17,0) AS '17', IFNULL(T2.18,0) AS '18', IFNULL(T2.19,0) AS '19', IFNULL(T2.20,0) AS '20',IFNULL(T2.TOTAL,0) AS 'TOTAL'
		FROM (
		SELECT DISTINCT fin.idcarga_final, carfin.nombre
		FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
		ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final
		where idservicio = ?
		) AS T1 LEFT JOIN 
		(
		SELECT carfin.idcarga_final,carfin.nombre AS 'CARGA', 
				SUM( IF( HOUR(lla.fecha)=7,1,0 ) ) AS '7',
				SUM( IF( HOUR(lla.fecha)=8,1,0 ) ) AS '8',
				SUM( IF( HOUR(lla.fecha)=9,1,0 ) ) AS '9',
				SUM( IF( HOUR(lla.fecha)=10,1,0 ) ) AS '10',
				SUM( IF( HOUR(lla.fecha)=11,1,0 ) ) AS '11',
				SUM( IF( HOUR(lla.fecha)=12,1,0 ) ) AS '12',
				SUM( IF( HOUR(lla.fecha)=13,1,0 ) ) AS '13',
				SUM( IF( HOUR(lla.fecha)=14,1,0 ) ) AS '14',
				SUM( IF( HOUR(lla.fecha)=15,1,0 ) ) AS '15',
				SUM( IF( HOUR(lla.fecha)=16,1,0 ) ) AS '16',
				SUM( IF( HOUR(lla.fecha)=17,1,0 ) ) AS '17',
				SUM( IF( HOUR(lla.fecha)=18,1,0 ) ) AS '18',
				SUM( IF( HOUR(lla.fecha)=19,1,0 ) ) AS '19',
				SUM( IF( HOUR(lla.fecha)=20,1,0 ) ) AS '20',
				COUNT( * ) AS 'TOTAL'
				FROM ca_cliente_cartera clicar INNER JOIN ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idfinal = fin.idfinal  AND carfin.idcarga_final = fin.idcarga_final AND tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera IN ($idcartera) AND DATE(lla.fecha) BETWEEN ? AND ?
				GROUP BY carfin.idcarga_final 
		) AS T2 ON T2.idcarga_final = T1.idcarga_final
		UNION
		SELECT 'TOTAL',
		SUM( IF( HOUR(lla.fecha)=7,1,0 ) ) AS '7',
		SUM( IF( HOUR(lla.fecha)=8,1,0 ) ) AS '8',
		SUM( IF( HOUR(lla.fecha)=9,1,0 ) ) AS '9',
		SUM( IF( HOUR(lla.fecha)=10,1,0 ) ) AS '10',
		SUM( IF( HOUR(lla.fecha)=11,1,0 ) ) AS '11',
		SUM( IF( HOUR(lla.fecha)=12,1,0 ) ) AS '12',
		SUM( IF( HOUR(lla.fecha)=13,1,0 ) ) AS '13',
		SUM( IF( HOUR(lla.fecha)=14,1,0 ) ) AS '14',
		SUM( IF( HOUR(lla.fecha)=15,1,0 ) ) AS '15',
		SUM( IF( HOUR(lla.fecha)=16,1,0 ) ) AS '16',
		SUM( IF( HOUR(lla.fecha)=17,1,0 ) ) AS '17',
		SUM( IF( HOUR(lla.fecha)=18,1,0 ) ) AS '18',
		SUM( IF( HOUR(lla.fecha)=19,1,0 ) ) AS '19',
		SUM( IF( HOUR(lla.fecha)=20,1,0 ) ) AS '20',
		COUNT( * ) AS 'TOTAL'
		FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
		ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ($idcartera) AND DATE(lla.fecha) BETWEEN ? AND ? ";

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$prCPorHora = $connection->prepare($sqlCPorHora);
$prCPorHora->bindParam(1, $_GET['Servicio'], PDO::PARAM_INT);
//$prCPorHora->bindParam(2, $_GET['Cartera'], PDO::PARAM_INT);
$prCPorHora->bindParam(2, $_GET['FechaInicio'], PDO::PARAM_STR);
$prCPorHora->bindParam(3, $_GET['FechaFin'], PDO::PARAM_STR);
//$prCPorHora->bindParam(5, $_GET['Cartera'], PDO::PARAM_INT);
$prCPorHora->bindParam(4, $_GET['FechaInicio'], PDO::PARAM_STR);
$prCPorHora->bindParam(5, $_GET['FechaFin'], PDO::PARAM_STR);
$prCPorHora->execute();

$count = 5;

$xls->write_string($count, 1, 'CARGA', $formatoCabeceras);
$xls->write_string($count, 2, '7', $formatoCabeceras);
$xls->write_string($count, 3, '8', $formatoCabeceras);
$xls->write_string($count, 4, '9', $formatoCabeceras);
$xls->write_string($count, 5, '10', $formatoCabeceras);
$xls->write_string($count, 6, '11', $formatoCabeceras);
$xls->write_string($count, 7, '12', $formatoCabeceras);
$xls->write_string($count, 8, '13', $formatoCabeceras);
$xls->write_string($count, 9, '14', $formatoCabeceras);
$xls->write_string($count, 10, '15', $formatoCabeceras);
$xls->write_string($count, 11, '16', $formatoCabeceras);
$xls->write_string($count, 12, '17', $formatoCabeceras);
$xls->write_string($count, 13, '18', $formatoCabeceras);
$xls->write_string($count, 14, '19', $formatoCabeceras);
$xls->write_string($count, 15, '20', $formatoCabeceras);

while ($rowCPorHora = $prCPorHora->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    $xls->write_string($count, 1, $rowCPorHora['CARGA']);
    $xls->write_string($count, 2, $rowCPorHora['7']);
    $xls->write_string($count, 3, $rowCPorHora['8']);
    $xls->write_string($count, 4, $rowCPorHora['9']);
    $xls->write_string($count, 5, $rowCPorHora['10']);
    $xls->write_string($count, 6, $rowCPorHora['11']);
    $xls->write_string($count, 7, $rowCPorHora['12']);
    $xls->write_string($count, 8, $rowCPorHora['13']);
    $xls->write_string($count, 9, $rowCPorHora['14']);
    $xls->write_string($count, 10, $rowCPorHora['15']);
    $xls->write_string($count, 11, $rowCPorHora['16']);
    $xls->write_string($count, 12, $rowCPorHora['17']);
    $xls->write_string($count, 13, $rowCPorHora['18']);
    $xls->write_string($count, 14, $rowCPorHora['19']);
    $xls->write_string($count, 15, $rowCPorHora['20']);
}

$sqlCPorHoraC = " SELECT T1.nombre AS 'CARGA',
		CONCAT(IFNULL( T2.7,0 ) ,'%') AS '7',CONCAT(IFNULL( T2.8,0 ) ,'%') AS '8',CONCAT(IFNULL( T2.9,0 ) ,'%') AS '9',CONCAT(IFNULL( T2.10,0 ) ,'%') AS '10',
		CONCAT(IFNULL( T2.11,0 ) ,'%') AS '11',CONCAT(IFNULL( T2.12,0 ) ,'%') AS '12',CONCAT(IFNULL( T2.13,0 ) ,'%') AS '13',CONCAT(IFNULL( T2.14,0 ) ,'%') AS '14',
		CONCAT(IFNULL( T2.15,0 ) ,'%') AS '15',CONCAT(IFNULL( T2.16,0 ) ,'%') AS '16',CONCAT(IFNULL( T2.17,0 ) ,'%') AS '17',CONCAT(IFNULL( T2.18,0 ) ,'%') AS '18',
		CONCAT(IFNULL( T2.19,0 ) ,'%') AS '19',CONCAT(IFNULL( T2.20,0 ) ,'%') AS '20'
		FROM
		(
		SELECT DISTINCT fin.idcarga_final, carfin.nombre
		FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
		ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final
		where idservicio = ?
		) AS T1 LEFT JOIN
		(
		SELECT carfin.idcarga_final,carfin.nombre AS 'CARGA', 
				IFNULL( TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=7,1,0 ) ) / ( 
				SELECT SUM( IF( HOUR(lla.fecha)=7,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera 
				  ) )*100,2),0)  AS '7' ,
				IFNULL( TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=8,1,0 ) ) / ( 
				SELECT SUM( IF( HOUR(lla.fecha)=8,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				) ) * 100, 2),0) AS '8',
				IFNULL( TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=9,1,0 ) ) / ( 
				SELECT SUM( IF( HOUR(lla.fecha)=9,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera    
				) ) * 100,2 ),0) AS '9',
				IFNULL( TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=10,1,0 ) ) / ( 
				SELECT SUM( IF( HOUR(lla.fecha)=10,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				) ) *100 ,2),0) AS '10',
				IFNULL( TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=11,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=11,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				) ) *100,2),0) AS '11',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=12,1,0 ) ) / (
				SELECT SUM( IF( HOUR(lla.fecha)=12,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				) )*100,2) AS '12',
				TRUNCATE( (SUM( IF( HOUR(lla.fecha)=13,1,0 ) ) / (
				SELECT SUM( IF( HOUR(lla.fecha)=13,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				) ) *100,2) AS '13',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=14,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=14,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '14',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=15,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=15,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '15',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=16,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=16,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '16',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=17,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=17,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '17',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=18,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=18,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '18',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=19,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=19,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '19',
				TRUNCATE( ( SUM( IF( HOUR(lla.fecha)=20,1,0 ) )/(
				SELECT SUM( IF( HOUR(lla.fecha)=20,1,0 ) ) 
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera = clicar.idcartera   
				))*100,2) AS '20'
				FROM ca_cliente_cartera clicar INNER JOIN ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idfinal = fin.idfinal  AND carfin.idcarga_final = fin.idcarga_final AND tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera IN ($idcartera) AND DATE(lla.fecha) BETWEEN ? AND ?
				GROUP BY carfin.idcarga_final
		) AS T2 ON T2.idcarga_final = T1.idcarga_final ";



$prCPorHoraC = $connection->prepare($sqlCPorHoraC);
$prCPorHoraC->bindParam(1, $_GET['Servicio'], PDO::PARAM_INT);
//$prCPorHoraC->bindParam(2, $_GET['Cartera'], PDO::PARAM_INT);
$prCPorHoraC->bindParam(2, $_GET['FechaInicio'], PDO::PARAM_STR);
$prCPorHoraC->bindParam(3, $_GET['FechaFin'], PDO::PARAM_STR);
$prCPorHoraC->execute();

$count = $count + 2;

$xls->write_string($count, 1, 'CARGA', $formatoCabeceras);
$xls->write_string($count, 2, '7', $formatoCabeceras);
$xls->write_string($count, 3, '8', $formatoCabeceras);
$xls->write_string($count, 4, '9', $formatoCabeceras);
$xls->write_string($count, 5, '10', $formatoCabeceras);
$xls->write_string($count, 6, '11', $formatoCabeceras);
$xls->write_string($count, 7, '12', $formatoCabeceras);
$xls->write_string($count, 8, '13', $formatoCabeceras);
$xls->write_string($count, 9, '14', $formatoCabeceras);
$xls->write_string($count, 10, '15', $formatoCabeceras);
$xls->write_string($count, 11, '16', $formatoCabeceras);
$xls->write_string($count, 12, '17', $formatoCabeceras);
$xls->write_string($count, 13, '18', $formatoCabeceras);
$xls->write_string($count, 14, '19', $formatoCabeceras);
$xls->write_string($count, 15, '20', $formatoCabeceras);

while ($rowCPorHoraC = $prCPorHoraC->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    $xls->write_string($count, 1, $rowCPorHoraC['CARGA']);
    $xls->write_string($count, 2, $rowCPorHoraC['7']);
    $xls->write_string($count, 3, $rowCPorHoraC['8']);
    $xls->write_string($count, 4, $rowCPorHoraC['9']);
    $xls->write_string($count, 5, $rowCPorHoraC['10']);
    $xls->write_string($count, 6, $rowCPorHoraC['11']);
    $xls->write_string($count, 7, $rowCPorHoraC['12']);
    $xls->write_string($count, 8, $rowCPorHoraC['13']);
    $xls->write_string($count, 9, $rowCPorHoraC['14']);
    $xls->write_string($count, 10, $rowCPorHoraC['15']);
    $xls->write_string($count, 11, $rowCPorHoraC['16']);
    $xls->write_string($count, 12, $rowCPorHoraC['17']);
    $xls->write_string($count, 13, $rowCPorHoraC['18']);
    $xls->write_string($count, 14, $rowCPorHoraC['19']);
    $xls->write_string($count, 15, $rowCPorHoraC['20']);
}

/* * *********** contactabilidad cierre de cartera ************ */

$sqlCierre = " SELECT T1.CARGA, T1.NOMBRE AS 'ESTADO' ,
		IFNULL(T2.TOTAL,0) AS 'TOTAL',
		IFNULL( T2.PORCIENTO, CONCAT(0,'%') ) AS 'PORCIENTO'
		FROM
		(
		SELECT DISTINCT finser.idfinal, carfin.nombre AS 'CARGA', fin.idcarga_final, fin.nombre
		FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
		ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final
		WHERE finser.idservicio = ? AND fin.idclase_final = 1
		) AS T1 LEFT JOIN 
		(
		SELECT    fin.idfinal,carfin.idcarga_final,carfin.nombre AS 'CARGA', 
				fin.nombre AS 'ESTADO',
				COUNT( * ) AS 'TOTAL',
				CONCAT(TRUNCATE( ( COUNT( * ) / (  
				SELECT COUNT( * ) AS 'TOTAL'
				FROM ca_cliente_cartera clicar INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idcliente_cartera = clicar.idcliente_cartera 
				WHERE clicar.idcartera IN ($idcartera)
				) ) * 100,2),'%') AS 'PORCIENTO'
				FROM ca_cliente_cartera clicar INNER JOIN ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN  ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu 
				ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion AND  tran.idfinal = fin.idfinal  AND carfin.idcarga_final = fin.idcarga_final AND tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera IN ($idcartera) AND DATE(lla.fecha) BETWEEN ? AND ? 
				GROUP BY fin.idfinal
		) AS T2 ON T2.idfinal = T1.idfinal ";

$prCierre = $connection->prepare($sqlCierre);
$prCierre->bindParam(1, $_GET['Servicio'], PDO::PARAM_INT);
//$prCierre->bindParam(2, $_GET['Cartera'], PDO::PARAM_INT);
//$prCierre->bindParam(3, $_GET['Cartera'], PDO::PARAM_INT);
$prCierre->bindParam(2, $_GET['FechaInicio'], PDO::PARAM_STR);
$prCierre->bindParam(3, $_GET['FechaFin'], PDO::PARAM_STR);
$prCierre->execute();

$xls = & $workbook->add_worksheet('Contactabilidad Cierre');

$xls->write_string(1, 1, 'Reporte Generado el: ', $formatoCabeceras);
$xls->write_string(1, 2, date("Y-m-d H:i:s"), $formatoCabeceras2);
$xls->write_string(2, 1, 'Fecha:', $formatoCabeceras);
$xls->write_string(2, 2, 'Del ' . $_GET['FechaInicio'] . ' al ' . $_GET['FechaFin'], $formatoCabeceras2);
$xls->write_string(3, 1, 'CONTACTABILIDAD CIERRE DE CARTERA', $formatoCabeceras);

$count = 5;

$xls->write_string($count, 1, 'CARGA', $formatoCabeceras);
$xls->write_string($count, 2, 'ESTADO', $formatoCabeceras);
$xls->write_string($count, 3, 'TOTAL', $formatoCabeceras);
$xls->write_string($count, 4, '%', $formatoCabeceras);

while ($rowCierre = $prCierre->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    $xls->write_string($count, 1, $rowCierre['CARGA']);
    $xls->write_string($count, 2, $rowCierre['ESTADO']);
    $xls->write_string($count, 3, $rowCierre['TOTAL']);
    $xls->write_string($count, 4, $rowCierre['PORCIENTO']);
}


$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$xls = & $workbook->add_worksheet('Contactabilidad Fecha');

$xls->write_string(1, 1, 'Reporte Generado el: ', $formatoCabeceras);
$xls->write_string(1, 2, date("Y-m-d H:i:s"), $formatoCabeceras2);
$xls->write_string(2, 1, 'Fecha: ', $formatoCabeceras);
$xls->write_string(2, 2, 'Del ' . $_GET['FechaInicio'] . ' al ' . $_GET['FechaFin'], $formatoCabeceras2);
$xls->write_string(3, 1, 'CONTACTABILIDAD POR FECHA', $formatoCabeceras);

$sqlPorFecha = " CALL contactabilidad_historico_diario ( ?,?,? ) ";

$prPorFecha = $connection->prepare($sqlPorFecha);
$prPorFecha->bindParam(1, $_GET['Cartera'], PDO::PARAM_INT);
$prPorFecha->bindParam(2, $_GET['FechaInicio'], PDO::PARAM_STR);
$prPorFecha->bindParam(3, $_GET['FechaFin'], PDO::PARAM_STR);
$prPorFecha->execute();
$count = 0;
while ($row = $prPorFecha->fetch(PDO::FETCH_ASSOC)) {
    if ($count == 0) {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($count + 5, $j, $index, $formatoCabeceras);
            $xls->write_string($count + 6, $j, $value);
            $j++;
        }
    } else {
        $j = 1;
        foreach ($row as $index => $value) {
            $xls->write_string($count + 6, $j, $value);
            $j++;
        }
    }
    $count++;
}

$workbook->close();
?>