<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=DIARIO.xls");
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
				<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DIARIO</td>
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
	$sql = " SELECT 
			cu.numero_cuenta AS NUMERO_CUENTA,
			cu.inscripcion AS PAN,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE,
			detcu.monto_mora AS PAGO_1_VTO,
			'' PAGO_2_VTO,
			pag.dato6 AS ESTADO_SEGUNDO_VCTO,
			pag.dato5 AS ESTADO_PRIMER_VCTO,
			pag.dato7 AS ESTADO,
			pag.dato8 AS COOR_GEST,
			( SELECT numero FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			DATE(lla.fecha) AS FECHA_LLAMADA,
			TIME(lla.fecha) AS HORA_LLAMADA,
			( SELECT LPAD(codigo,3,'0') FROM ca_final_servicio WHERE idservicio = 1 AND idfinal = lla.idfinal ) AS CODESTADO,
			( SELECT nombre FROM ca_final WHERE idfinal = lla.idfinal ) AS ESTADO_LLAMADA,
			DATE(lla.fecha_cp) AS FECHA_CPG,
			lla.observacion AS OBSERVACION,
			( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) AS NO_PAGO
			FROM ca_cliente cli 
			INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
			INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta
			LEFT JOIN ca_pago pag ON pag.iddetalle_cuenta = detcu.iddetalle_cuenta AND pag.estado = 1
			WHERE cli.idservicio = ? AND clicar.idcartera IN (".$idcartera.") AND cu.idcartera IN (".$idcartera.") 
			AND detcu.idcartera IN (".$idcartera.") AND pag.idcartera IN ( ".$idcartera." )
			AND DATE(lla.fecha) BETWEEN ? AND ?  
			GROUP BY lla.idllamada ";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
	$pr->execute();

	$i = 0;

	//echo '<table>';

	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

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
				if( $key == 'NUMERO_CUENTA' || $key == 'TELEFONO' || $key == 'PAN' ) {
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