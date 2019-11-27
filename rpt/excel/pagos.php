<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=pagos.xls");
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

	$cartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	$FechaInicio = $_REQUEST['FechaInicio'];
	$FechaFin = $_REQUEST['FechaFin'];

?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE FACTURAS</td>
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

	$sql = " SELECT
		car.nombre_cartera AS 'GESTION',
		car.fecha_inicio AS 'FECHA_INICIO',
		car.fecha_fin AS 'FECHA_FIN',
		car.evento AS 'EVENTO',
		car.cluster AS 'CLUSTER',
		car.segmento AS 'SEGMENTO',
		pg.codigo_operacion AS 'NUMERO_FACTURA',
		pg.monto_pagado AS 'MONTO_PAGO',
		pg.fecha AS 'FECHA_PAGO',
		pg.observacion AS 'OBSERVACION'
		FROM ca_pago pg INNER JOIN ca_cartera car ON car.idcartera = pg.idcartera
		WHERE car.idcartera IN ( $cartera ) AND pg.idcartera IN ( $cartera ) 
		AND DATE(pg.fecha) BETWEEN ? AND ? 
		AND pg.estado = 1 ";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $FechaInicio, PDO::PARAM_STR);
	$pr->bindParam(2, $FechaFin, PDO::PARAM_STR);
	$pr->execute();
	$i = 0;
	echo '<table>';
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
		if( $i == 0 ) {
			echo '<tr>';
			foreach( $row as $index => $value ) {
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
			}
			echo '</tr>';
		}

		$style="";
		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
		foreach( $row as $key => $value )
		{
			echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
		}
		echo '</tr>';

		$i++;
	}
	echo '</table>';

?>