<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=facturas.xls");
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
		clicar.codigo_cliente AS 'CODIGO_CLIENTE',
		cu.numero_cuenta AS 'INSCRIPCION',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
		cu.telefono AS 'TELEFONO',
		detcu.codigo_operacion AS 'NUMERO_FACTURA',
		detcu.fecha_emision AS 'FECHA_EMISION',
		detcu.fecha_vencimiento AS 'FECHA_VENCIMIENTO',
		TRUNCATE(detcu.total_deuda,2) AS 'EXIGIBLE',
		TRUNCATE(detcu.total_deuda_soles,2) AS 'MONTO_TOTAL',
		TRUNCATE(detcu.monto_pagado,2) AS 'PAGO_FACTURA',
		TRUNCATE( detcu.total_deuda - detcu.monto_pagado,2 ) AS 'SALDO',
		CASE WHEN ( detcu.total_deuda - detcu.monto_pagado ) = detcu.total_deuda THEN 'SP' WHEN ( detcu.total_deuda - detcu.monto_pagado ) >0 AND ( detcu.total_deuda - detcu.monto_pagado ) < detcu.total_deuda THEN 'A' ELSE 'C' END AS 'ESTADO_PAGO',
		DATE(detcu.ul_fecha_pago) AS 'ULTIMA_FECHA_PAGO'
		FROM
		ca_cartera car 
		INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
		INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
		INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera
		INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta
		WHERE car.idcartera IN ( $cartera ) AND clicar.idcartera IN ( $cartera ) AND cli.idservicio = ? 
		AND cu.idcartera IN ( $cartera ) AND detcu.idcartera IN ( $cartera ) ";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
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