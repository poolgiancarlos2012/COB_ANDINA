<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=PRODUCTIVIDAD.xls");
	header("Pragma:no-cache");
	header("Expires:0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	$confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
	$user = $confCobrast['user_db']['user_rpt'];
	$password = $confCobrast['user_db']['password_rpt'];

	date_default_timezone_set('America/Lima');

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection($user,$password);

	$idcartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	$fecha_inicio = $_REQUEST['FechaInicio'];
	$fecha_fin = $_REQUEST['FechaFin'];
	
	?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">ESTADISTICO PRODUCTIVIDAD</td>
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
	
	$time = date("Y_m_d_H_i_s");
	
	$sqlTmp = " CREATE TEMPORARY TABLE tmp_prod_".$time." AS 
			SELECT 
			t1.idcuenta, 
			t1.idcliente_cartera,
			t1.idusuario_servicio,
			t1.idcarga_final,
			COUNT(*) AS llamadas 
			FROM
			(
			SELECT lla.idcuenta, lla.idcliente_cartera, lla.idusuario_servicio, fin.idcarga_final 
			FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser
			ON finser.idfinal = fin.idfinal AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera  
			WHERE lla.tipo = 'LL' AND lla.estado = 1 AND clicar.idcartera IN ( ".$idcartera." ) 
			AND DATE(lla.fecha) BETWEEN ? AND ? 
			ORDER BY lla.idcuenta, finser.peso DESC 
			) t1 GROUP BY t1.idcuenta ";
			
	$prTmp = $connection->prepare( $sqlTmp );
	$prTmp->bindParam(1, $fecha_inicio, PDO::PARAM_STR);
	$prTmp->bindParam(2, $fecha_fin, PDO::PARAM_STR);
	$prTmp->execute();

	/*$sql = " SELECT
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio ) AS TELEOPERADOR,
			COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) AS CEF,
			COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) AS CNE,
			( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) ) AS TOTAL,
			COUNT( DISTINCT IF( fin.idcarga_final = 1 ,lla.idcuenta,NULL ) ) AS NOC,
			( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 1 ,lla.idcuenta,NULL ) ) ) AS TOTAL_UNICOS,
			COUNT( * ) AS TOTAL_LLAMADA,
			TRUNCATE( ( ( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) ) / ( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 1 ,lla.idcuenta,NULL ) ) ) )*100,1) AS TOTAL_PRODUCTIVIDAD,
			TRUNCATE( ( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,1 ) AS TOTAL_CEF,
			TRUNCATE( ( COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,1 ) AS TOTAL_CNE,
			TRUNCATE( ( COUNT( DISTINCT IF( fin.idcarga_final = 1 ,lla.idcuenta,NULL ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,1 ) AS TOTAL_NOC,
			TRUNCATE( ( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ) + COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ) ),1 ) AS TOTAL_PRODUCTIVIDAD_M,
			TRUNCATE( COUNT( DISTINCT IF( fin.idcarga_final = 3 ,lla.idcuenta,NULL ) ),1 ) AS TOTAL_CEF_M,
			TRUNCATE( COUNT( DISTINCT IF( fin.idcarga_final = 2 ,lla.idcuenta,NULL ) ),1 ) AS TOTAL_CNE_M,
			TRUNCATE( COUNT( DISTINCT IF( fin.idcarga_final = 1 ,lla.idcuenta,NULL ) ),1 ) AS TOTAL_NOC_M
			FROM ca_cliente_cartera clicar 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal 
			WHERE clicar.idcartera IN ( ".$idcartera." ) AND lla.estado = 1 AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ? 
			GROUP BY lla.idusuario_servicio 
			ORDER BY 1 ";*/
			
	$sql = " SELECT
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = tmp.idusuario_servicio ) AS TELEOPERADOR,
			SUM( IF( tmp.idcarga_final = 3 ,1,0 ) ) AS CEF,
			SUM( IF( tmp.idcarga_final = 2 ,1,0 ) ) AS CNE,
			SUM( IF( tmp.idcarga_final IN ( 3,2 ) ,1,0 ) ) AS TOTAL,
			SUM( IF( tmp.idcarga_final = 1 ,1,0 ) ) AS NOC,
			SUM( IF( tmp.idcarga_final IN ( 3,2,1 ) ,1,0 ) ) AS TOTAL_UNICOS,
			SUM( tmp.llamadas ) AS TOTAL_LLAMADA,
			
			TRUNCATE( ( SUM( IF( tmp.idcarga_final IN ( 3,2 ) ,1,0 ) )/SUM( IF( tmp.idcarga_final IN ( 3,2,1 ) ,1,0 ) ) )*100 ,1) AS TOTAL_PRODUCTIVIDAD,
			
			TRUNCATE( ( SUM( IF( tmp.idcarga_final = 3 ,1,0 ) )/SUM( IF( tmp.idcarga_final IN ( 3,2,1 ) ,1,0 ) ) )*100,1 ) AS TOTAL_CEF,
			TRUNCATE( ( SUM( IF( tmp.idcarga_final = 2 ,1,0 ) )/SUM( IF( tmp.idcarga_final IN ( 3,2,1 ) ,1,0 ) ) )*100,1 ) AS TOTAL_CNE,
			TRUNCATE( ( SUM( IF( tmp.idcarga_final = 1 ,1,0 ) )/SUM( IF( tmp.idcarga_final IN ( 3,2,1 ) ,1,0 ) ) )*100,1 ) AS TOTAL_NOC,
			
			TRUNCATE( SUM( IF( tmp.idcarga_final IN ( 3,2 ) ,1,0 ) ),1 ) AS TOTAL_PRODUCTIVIDAD_M,
			TRUNCATE( SUM( IF( tmp.idcarga_final = 3 ,1,0 ) ),1 ) AS TOTAL_CEF_M,
			TRUNCATE( SUM( IF( tmp.idcarga_final = 2 ,1,0 ) ),1 ) AS TOTAL_CNE_M,
			TRUNCATE( SUM( IF( tmp.idcarga_final = 1 ,1,0 ) ),1 ) AS TOTAL_NOC_M
			FROM tmp_prod_".$time." tmp  
			GROUP BY idusuario_servicio 
			ORDER BY 1 ";

	$pr = $connection->prepare($sql);
	$pr->execute();
	$i = 0;

	$header = array( 
					""=>array("N"),
					"    "=>array("FECHA"),
					"  "=>array("AGENTE"),
					"CLIENTES UNICOS"=>array("CEF","CNE","TOTAL","NOC"),
					"   "=>array("TOTAL UNICOS","TOTAL LLAMADAS"),
					"PORCENTAJE"=>array("TOTAL productividad (CEF+CNE)","CEF %","CNE %","NOC %"),
					"PORCENTAJE EN BASE META"=>array("TOTAL productividad (CEF+CNE)","CEF %","CNE %","NOC %","META CLIENTE UNICO")
					);
	echo '<table>';
		echo '<tr>';
			foreach( $header as $index => $value ) {
				echo '<td colspan="'.count($value).'" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
			}
		echo '</tr>';
		echo '<tr>';
			foreach( $header as $index => $value ) {
				foreach( $value as $key => $v ) {
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$v.'</td>';
				}
			}
		echo '</tr>';
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

		$style="";
		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
			echo '<td style="'.$style.'" align="center">'.($i+1).'</td>';
			echo '<td style="'.$style.'" align="center">'.$fecha_fin.'</td>';
		foreach( $row as $key => $value )
		{	
			if( $key == 'TOTAL_PRODUCTIVIDAD' || $key == 'TOTAL_CEF' || $key == 'TOTAL_CNE' || $key == 'TOTAL_NOC' || $key == 'TOTAL_PRODUCTIVIDAD_M' || $key == 'TOTAL_CEF_M' || $key == 'TOTAL_CNE_M' || $key == 'TOTAL_NOC_M' ) {
				echo '<td style="'.$style.'" align="center">'.strtoupper(utf8_decode($value)).'%</td>';
			}else{
				echo '<td style="'.$style.'" align="center">'.strtoupper(utf8_decode($value)).'</td>';
			}
			
		}
			echo '<td style="'.$style.'" align="center">100</td>';
		echo '</tr>';

		$i++;

	}
	echo '</table>';


?>