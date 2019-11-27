<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=PRIORIDAD.xls");
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
		
	$time = date("Y_m_d_H_i_s");
	/*
	?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE PRIORIDAD</td>
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
*/
	$sql = " CREATE TEMPORARY TABLE rpt_cuenta_".$time." AS
		SELECT * 
		FROM
		(
		SELECT
		lla.idcuenta,
		IF( fin.idcarga_final IN ( 1,2 ) , 'INCORRECTO','CORRECTO' ) AS CALIFICACION_LLAMADA,
		fin.nombre AS LLAMADA,
		lla.observacion AS MOTIVO_NO_PAGO
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser
		ON finser.idfinal = fin.idfinal AND fin.idfinal = lla.idfinal AND  lla.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND finser.idservicio = ? AND DATE(lla.fecha) BETWEEN ? AND ? 
		AND lla.tipo = 'LL' AND lla.estado = 1 
		ORDER BY lla.idcuenta, finser.peso DESC
		) t1 GROUP BY t1.idcuenta DESC ";
	
	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
	$pr->execute();
	
	$sqlAT = " ALTER TABLE rpt_cuenta_".$time." ADD INDEX ( idcuenta ) ";
	$prAT = $connection->prepare($sqlAT);
	$prAT->execute();
	
	$sqlP = " SELECT 
			dato2 AS SEGMENTO,
			CASE 
			WHEN dato2 = '09' THEN 1
			WHEN dato2 = '10' THEN 2
			WHEN dato2 = '11' THEN 3
			WHEN dato2 = '12' THEN 4
			WHEN dato2 = '01' THEN 5
			WHEN dato2 = '05' THEN 6
			WHEN dato2 = '02' THEN 7
			WHEN dato2 = '06' THEN 8
			WHEN dato2 = '03' THEN 9
			WHEN dato2 = '07' THEN 10
			WHEN dato2 = '04' THEN 11
			WHEN dato2 = '08' THEN 12
			WHEN dato2 = '21' THEN 13
			WHEN dato2 = '22' THEN 14
			WHEN dato2 = '23' THEN 15
			WHEN dato2 = '24' THEN 16
			WHEN dato2 = '13' THEN 17
			WHEN dato2 = '17' THEN 18
			WHEN dato2 = '14' THEN 19
			WHEN dato2 = '18' THEN 20
			WHEN dato2 = '15' THEN 21
			WHEN dato2 = '19' THEN 22
			WHEN dato2 = '16' THEN 23 
			ELSE 24 
			END AS PRIORIDAD,
			numero_cuenta AS NUMERO_CUENTA,
			inscripcion AS PAN,
			CASE 
			WHEN dato2 IN ( '09','10','11','12','01','05','02','06','03','07','04','08' ) THEN 'PAGO MINIMO >=10'
			ELSE 'PAGO MINIMO <10' END AS TIPO_PAGO,
			CASE
			WHEN dato2 IN ( '09','10','11','12','21','22','23','24' ) THEN 'ES SU PRIMERA FACTURACION'
			ELSE 'TIENE FACTURACION ANTERIOR' END AS FACTURACION,
			( SELECT dias_mora FROM ca_pago WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta LIMIT 1 ) AS DIAS_MORA,
			CASE 
			WHEN dato2 IN ('10','02','06','22','14','18') THEN 'NB'
			WHEN dato2 IN ('11','03','07','23','15','19') THEN 'Riesgo Aceptable'
			WHEN dato2 IN ('09','01','05','21','13','17') THEN 'Riesgo Alto'
			ELSE 'Riesgo Bajo' END AS RIESGO,
			t2.CALIFICACION_LLAMADA,
			t2.LLAMADA,
			t2.MOTIVO_NO_PAGO 
			FROM ca_cuenta cu LEFT JOIN rpt_cuenta_".$time."  t2 ON t2.idcuenta = cu.idcuenta
			WHERE idcartera IN ( ".$idcartera." )  ";
	
	$prP = $connection->prepare($sqlP);
	$prP->execute();
	
	$i = 0;

	//echo '<table>';
		
	while ($row = $prP->fetch(PDO::FETCH_ASSOC)) {
		
		if( $i == 0 ) {
			//echo '<tr>';
			foreach( $row as $key => $value )
			{	
				//echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$key.'</td>';
				echo $key."\t";
			}
			echo "\n";
			//echo '</tr>';
		}
		
		//echo '<tr>';
		foreach( $row as $key => $value )
		{	
				if( $key == 'SEGMENTO' || $key == 'NUMERO_CUENTA' || $key == 'PAN' ) {
					//echo '<td style="border-color:white;" align="center">="'.strtoupper(utf8_decode($value)).'"</td>';
					echo '="'.strtoupper(utf8_decode($value)).'"'."\t";
				}else{
					//echo '<td style="border-color:white;" align="center">'.strtoupper(utf8_decode($value)).'</td>';
					echo strtoupper(utf8_decode($value))."\t";
				}
				
		}
		echo "\n";
		//echo '</tr>';

		$i++;

	}
	//echo '</table>';


?>