<?php

	header("Content-Type: text/html; charset=UTF-8");	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=Marcaciones.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

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

	$carteras = $_GET['Cartera'];
	$servicio = $_GET['Servicio'];
	$estados = $_GET['Estados'];
	$fecha_inicio = $_GET['FechaInicio'];
	$fecha_fin = $_GET['FechaFin'];

	$sql = " SELECT DISTINCT carfin.idcarga_final, carfin.nombre 
		FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
		ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final 
		WHERE finser.idservicio = ? AND finser.estado = 1 ";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1,$servicio,PDO::PARAM_INT);
	$pr->execute();
	$dataCarga = $pr->fetchAll(PDO::FETCH_ASSOC);

	?>
		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">MARCACIONES</td>
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
	

	$field2 = array();
	
	for( $j=7;$j<21;$j++ ) {
		array_push($field2," SUM( IF( HOUR(lla.fecha) = ".$j." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."WCPG' ");
		for( $i=0;$i<count($dataCarga);$i++ ) {
			array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." AND HOUR(lla.fecha) = ".$j." ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."W".$dataCarga[$i]['nombre']."' ");
			//array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." AND HOUR(lla.fecha) = ".$j." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."_CPG__".$dataCarga[$i]['nombre']."' ");
		}	 
		array_push($field2," SUM( IF( HOUR(lla.fecha) = ".$j." ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."WMAR' ");
		//array_push($field2," SUM( IF( HOUR(lla.fecha) = ".$j." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."WTOTAL_CPG' ");
	}
	
	array_push($field2," SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 20 AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS 'TOTAL_CPG' ");
	$contac = array();
	$cont_id = "0";
	for( $i=0;$i<count($dataCarga);$i++ ) {
		array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." AND HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."' ");
		//array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." AND HOUR(lla.fecha) BETWEEN 7 AND 20 AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS 'CPG__".$dataCarga[$i]['nombre']."' ");
		if( $dataCarga[$i]['nombre'] == 'CEF' || $dataCarga[$i]['nombre'] == 'CNE'  ) {
			array_push( $contac, $dataCarga[$i]['idcarga_final'] );
		}
		if( $dataCarga[$i]['nombre'] == 'CEF' ) {
			$cont_id = $dataCarga[$i]['idcarga_final'] ;
		}
	}
	array_push($field2," SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) AS 'TOTAL_MAR' ");
	
	array_push($field2," TRUNCATE( ( SUM( IF( fin.idcarga_final IN ( ".implode(",",$contac)." ) AND HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) / SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) )*100,2) AS 'GENERAL' ");
	array_push($field2," TRUNCATE( ( SUM( IF( fin.idcarga_final = ".$cont_id." AND HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) / SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) )*100,2) AS 'SOBRE_MARC' ");
	array_push($field2," TRUNCATE ( ( SUM( IF( fin.idcarga_final = ".$cont_id." AND HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) / SUM( IF( fin.idcarga_final IN ( ".implode(",",$contac)." ) AND HOUR(lla.fecha) BETWEEN 7 AND 20 ,1,0 ) ) )*100 ,2 ) AS 'CONTACTOS' ");
	
	
	$sql = " SELECT 
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio ) AS 'TELEOPERADOR',
		".implode(",",$field2)." 
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( $carteras ) AND lla.estado = 1 
		AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ? 
		GROUP BY lla.idusuario_servicio ";
		
	$pr = $connection->prepare($sql);
	$pr->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
	$pr->bindParam(2,$fecha_fin,PDO::PARAM_STR);
	$pr->execute();
	$data = $pr->fetchAll(PDO::FETCH_ASSOC);

	echo '<table>';
	for( $i=0;$i<count($data);$i++ ) {
		if( $i == 0 ) {
			
			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			for( $j=7;$j<21;$j++ ) {
				echo '<td colspan="'.(count($dataCarga) + 2 ).'" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >="'.str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT).'"</td>';
			}
				echo '<td colspan="'.(count($dataCarga) + 5 ).'" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >RESUMEN TOTALES</td>';
			echo '</tr>';

			echo '<tr>';
			foreach( $data[$i] as $index => $value ){
				$header = explode("W",$index);
				if( count($header) == 1 ) {
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.str_replace("_"," ",$index).'</td>';
				}else if( count($header) == 2 ){
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.str_replace("_"," ",$header[1]).'</td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.str_replace("_"," ",$index).'</td>';
				}

			}
			echo '</tr>';
		}
		
		$style="";
		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
		foreach( $data[$i] as $key => $value )
		{
			echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
		}
		echo '</tr>';
		
		
	}
	echo '</table>';
	
	

?>