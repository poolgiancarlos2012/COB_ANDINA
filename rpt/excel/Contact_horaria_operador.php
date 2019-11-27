<?php

	header("Content-Type: text/html; charset=UTF-8");	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=contactabilidad_horaria_por_operador.xls");
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
	
	$sql = " SELECT carfin.idcarga_final, carfin.nombre 
		FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
		ON finser.idfinal = fin.idfinal AND fin.idcarga_final = carfin.idcarga_final 
		WHERE finser.idservicio = ? AND finser.estado = 1 ";
		
	$pr = $connection->prepare($sql);
	$pr->bindParam(1,$servicio,PDO::PARAM_INT);
	$pr->execute();
	$dataCarga = $pr->fetchAll(PDO::FETCH_ASSOC);
	
	$sqlCartera = " SELECT idcartera, nombre_cartera AS cartera ,
		fecha_inicio, fecha_fin, evento, cluster, segmento 
		FROM ca_cartera WHERE idcartera IN ( $carteras ) ";
	
	$prC = $connection->prepare($sql);
	$prC->bindParam(1,$servicio,PDO::PARAM_INT);
	$prC->execute();
	$dataCartera = $prC->fetchAll(PDO::FETCH_ASSOC);
	
	?>
		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">CONTACTABILIDAD HORARIA POR OPERADOR</td>
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
	
	$field = array();
	$field2 = array();
	$field3 = array();
	for( $i=0;$i<count($dataCarga);$i++ ) {
		array_push($field," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['nombre']." ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."_LLM' ");
		array_push($field," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['nombre']." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."_CPG' ");
	}	 
	
	for( $j=7;$j<16;$j++ ) {
		for( $i=0;$i<count($dataCarga);$i++ ) {
			array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['nombre']." AND HOUR(lla.fecha) = ".$j." ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."_".$dataCarga[$i]['nombre']."_LLM' ");
			array_push($field2," SUM( IF( fin.idcarga_final = ".$dataCarga[$i]['nombre']." AND HOUR(lla.fecha) = ".$j." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."_".$dataCarga[$i]['nombre']."_CPG' ");
		}	 
		array_push($field2," SUM( IF( HOUR(lla.fecha) = ".$j." ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."_TOTAL__LLM' ");
		array_push($field2," SUM( IF( HOUR(lla.fecha) = ".$j." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT)."_TOTAL___CPG' ");
	}
	
	for( $j=0;$j<count($dataCartera);$j++ ) {
		for( $i=0;$i<count($dataCarga);$i++ ) {
			array_push($field3," SUM( IF( clicar.idcartera = ".$dataCartera[$i]['idcartera']." AND fin.idcarga_final = ".$dataCarga[$i]['nombre']." ,1,0 ) ) AS '".$dataCartera[$j]['nombre']."_".$dataCarga[$i]['nombre']."_LLM' ");
			array_push($field3," SUM( IF( clicar.idcartera = ".$dataCartera[$i]['idcartera']." AND fin.idcarga_final = ".$dataCarga[$i]['nombre']." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".$dataCartera[$j]['nombre']."_".$dataCarga[$i]['nombre']."_CPG' ");
		}	 
		array_push($field3," SUM( IF( clicar.idcartera = ".$dataCartera[$i]['idcartera']." ,1,0 ) ) AS '".$dataCartera[$j]['nombre']."_TOTAL_LLM' ");
		array_push($field3," SUM( IF( clicar.idcartera = ".$dataCartera[$i]['idcartera']." AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS '".$dataCartera[$j]['nombre']."_TOTAL_CPG' ");
	}
	
	$sqlResumen = " SELECT 
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'TELEOPERADOR',
		".implode(",",$field).", 
		COUNT(*) AS 'TOTAL__LLM',
		SUM( IF( fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS 'TOTAL__CPG'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( $carteras )  AND lla.estado = 1
		GROUP BY clicar.idusuario_servicio WITH ROLLUP ";
		
	$sqlHorario = " SELECT 
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'TELEOPERADOR',
		".implode(",",$field2).",
		COUNT(*) AS 'TOTAL__LLM',
		SUM( IF( fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS 'TOTAL__CPG'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( $carteras ) AND lla.estado = 1
		GROUP BY clicar.idusuario_servicio WITH ROLLUP ";
		
	$sqlCartera = " SELECT 
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'TELEOPERADOR',
		".implode(",",$field3).",
		SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 15 ,1,0 ) ) AS 'TOTAL__LLM',
		SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 15 AND fin.idfinal IN ( ".$estados." ) ,1,0 ) ) AS 'TOTAL__CPG'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( $carteras ) AND lla.estado = 1
		GROUP BY clicar.idusuario_servicio WITH ROLLUP ";
		
	$prR = $connection->prepare( $sqlResumen );
	$prR->execute();
	$data = $prR->fetchAll(PDO::FETCH_ASSOC);
	?>
		<table>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="font-weight:bold;font-size:12px;color:blue;">RESUMEN</td>
			</tr>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
		</table>
	<?php
	echo '<table>';
	for( $i=0;$i<count($data);$i++ ) {
		if( $i == 0 ) {
			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			for( $j=0;$j<count($dataCarga);$j++ ) {
				echo '<td colspan="2" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$dataCarga[$j]['nombre'].'</td>';
			}
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			echo '<tr>';
			echo '<tr>';
			foreach( $data[$i] as $index => $value ){
				$header = explode("_",$index);
				if( count($header) = 1 ) {
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$header[1].'</td>';
				}
				
			}
			echo '<tr>';
		}
		
		if( $i = ( count($data) - 1 ) ) {
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				if( $key == 'TELEOPERADOR' ){
					echo '<td></td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$value.'</td>';
				}
			}
			echo '</tr>';
		}else{
			$style="";
			( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
			}
			echo '</tr>';
		}
		
	}
	echo '</table>';
	
	$prH = $connection->prepare($sqlHorario);
	$prH->execute();
	$data = $prH->fetchAll(PDO::FETCH_ASSOC);
	?>
		<table>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="font-weight:bold;font-size:12px;color:blue;">HORARIO</td>
			</tr>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
		</table>
	<?php
	echo '<table>';
	for( $i=0;$i<count($data);$i++ ) {
		if( $i == 0 ) {
			$header_carga = '';
			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			for( $j=7;$j<16;$j++ ) {
				for( $k=0;$k<count($dataCarga);$k++ ) {
					$header_carga.='<td colspan="2" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$dataCarga[$k]['nombre'].'</td>';
				}	 
				$header_carga.='<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				$header_carga.='<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td colspan="'.(count($dataCarga)*2 + 2 ).'" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.str_pad($j,2,'0',STR_PAD_LEFT)."-".str_pad(($j+1),2,'0',STR_PAD_LEFT).'</td>';
			}
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo $header_carga;
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			echo '</tr>';
			
			echo '<tr>';
			foreach( $data[$i] as $index => $value ){
				$header = explode("_",$index);
				if( count($header) = 1 ) {
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
				}else if( count($header) = 2 ){
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$header[1].'</td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$header[2].'</td>';
				}

			}
			echo '<tr>';
		}

		if( $i = ( count($data) - 1 ) ) {
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				if( $key == 'TELEOPERADOR' ){
					echo '<td></td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$value.'</td>';
				}
			}
			echo '</tr>';
		}else{
			$style="";
			( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
			}
			echo '</tr>';
		}

	}
	echo '</table>';
	
	
	$prCar = $connection->prepare($sqlCartera);
	$prCar->execute();
	$data = $prCar->fetchAll(PDO::FETCH_ASSOC);
	?>
		<table>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
			<tr>
				<td></td>
				<td style="font-weight:bold;font-size:12px;color:blue;">CARTERA</td>
			</tr>
			<tr>
				<td></td>
				<td style="height:40px;"></td>
			</tr>
		</table>
	<?php
	echo '<table>';
	for( $i=0;$i<count($data);$i++ ) {
		if( $i == 0 ) {
			$header_carga = '';
			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			for( $j=0;$j<count($dataCartera);$j++ ) {
				for( $k=0;$k<count($dataCarga);$k++ ) {
					$header_carga.='<td colspan="2" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$dataCarga[$k]['nombre'].'</td>';
				}	 
				$header_carga.='<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				$header_carga.='<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td colspan="'.( count($dataCarga)*2 + 2 ).'" style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$dataCartera[$j]['nombre'].'</td>';
			}
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo $header_carga;
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" ></td>';
			echo '</tr>';

			echo '<tr>';
			foreach( $data[$i] as $index => $value ){
				$header = explode("_",$index);
				if( count($header) = 1 ) {
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
				}else if( count($header) = 2 ){
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$header[1].'</td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$header[2].'</td>';
				}

			}
			echo '<tr>';
		}
		
		if( $i = ( count($data) - 1 ) ) {
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				if( $key == 'TELEOPERADOR' ){
					echo '<td></td>';
				}else{
					echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$value.'</td>';
				}
			}
			echo '</tr>';
		}else{
			$style="";
			( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
			echo '<tr>';
			foreach( $data[$i] as $key => $value )
			{
				echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
			}
			echo '</tr>';
		}

	}
	echo '</table>';
	
	
?>