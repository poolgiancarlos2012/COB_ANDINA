<?php

//	require_once('../../phpincludes/excel/Workbook.php');
//	require_once('../../phpincludes/excel/Worksheet.php');	
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$cartera = $_GET['cartera'];
	
//	$workbook = new Workbook("-");
//	$workbook->setName('Reportes');

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
//	$xls =& $workbook->add_worksheet('GESTION - FOTOCARTERA');
//	
//	$xls->write_string(1,1,'REPORTE DE GESTION - FOTOCARTERA');
//	$xls->write_string(2,1,'Reporte Generado el: '.date("Y-m-d H:i:s"));
//	$xls->write_string(3,1,'Fecha: '.date("Y-m-d H:i:s"));
	
	$sqlCartera = " SELECT tabla, numero_cuenta, IFNULL(moneda_cuenta,'') AS 'moneda_cuenta' 
	FROM ca_cartera WHERE idcartera = ? ";
	
	$pr = $connection->prepare( $sqlCartera );
	$pr->bindParam(1,$cartera,PDO::PARAM_INT);
	$pr->execute();
	$dataCartera = $pr->fetchAll(PDO::FETCH_ASSOC);
	
	$sql = " SELECT tmp.*, 'NO' AS 'Benchmarking',
	DATE(cu.ml_fecha) AS 'ml_fecha',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ml_carga',
	( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ml_estado', DATE( cu.ml_fcpg ) AS 'ml_fcpg', cu.ml_observacion ,
	cu.ml_operador AS 'ml_operador', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ml_operador',
	DATE(cu.ul_fecha) AS 'ul_fecha',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'ul_carga',
	( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'ul_estado', DATE( cu.ul_fcpg ) AS 'ul_fcpg', cu.ul_observacion ,
	cu.ul_operador AS 'ul_codope', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'ul_operador' ,
	DATE(cu.mv_fecha) AS 'mv_fecha',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'vl_carga',
	( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'mv_estado', DATE( cu.mv_fcpg ) AS 'mv_fcpg', cu.mv_observacion ,
	cu.mv_operador AS 'mv_codope', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_operador ) AS 'mv_operador' ,
	DATE(cu.uv_fecha) AS 'uv_fecha',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'uv_carga',
	( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'uv_estado', DATE( cu.uv_fcpg ) AS 'uv_fcpg', cu.uv_observacion ,
	cu.uv_operador AS 'uv_codope', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_operador ) AS 'uv_operador' 
	FROM ".$dataCartera[0]['tabla']." tmp
	LEFT JOIN ( SELECT * FROM ca_cuenta WHERE idcartera = $cartera ) cu ON cu.numero_cuenta = tmp.".$dataCartera[0]['numero_cuenta']." ";
	echo $sql;
	//if( trim($dataCartera[0]['moneda_cuenta']) != '' ) {
//		$sql .= " AND cu.moneda = tmp.".$dataCartera[0]['moneda_cuenta']." ";
//	}
//	
//	$pr = $connection->prepare($sql);
//	$pr->execute();
//	$countRow=8;
//	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
//		if( $countRow == 8 ) {
//			$j=1;
//			foreach( $row as $index => $value ) {
//				//$field = explode("____",$index);
//				$xls->write_string($countRow-1,$j,$index);
//				$xls->write_string($countRow,$j,$value);
//				$j++;
//			}
//		}else{
//			$j=1;
//			foreach( $row as $index => $value ) {
//				$xls->write_string($countRow,$j,$value);
//				$j++;
//			}	
//		}
//		$countRow++;
//	}
//	
//	$workbook->close();	

?>