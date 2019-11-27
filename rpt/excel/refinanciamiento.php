<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=REFINANCIAMIENTO.xls");
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
				<td colspan="2" style="font-weight:bold;font-size:24px;">REFINANCIAMIENTOS</td>
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
	
	$header = array( 
					"GESTION","CODIGO CLIENTE", "NOMBRE CLIENTE", "NUMERO DOCUMENTO", "NUMERO CUENTA", 
					"TELEFONO", "ESTADO", "FECHA", "TOTAL DEUDA", "NUMERO CUOTAS", "MONTO CUOTA", 
					"OBSERVACION", "OBJECION" 
					);

	$trace_sql = "";
	if( $idcartera != '' ) {
		$trace_sql = " AND clicar.idcartera IN ( ".$idcartera." ) ";
	}

	$sql = " SELECT 
	car.nombre_cartera AS GESTION,
	clicar.codigo_cliente AS CODIGO_CLIENTE,
	CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
	cli.numero_documento AS NUMERO_DOCUMENTO,
	( SELECT numero_cuenta FROM ca_cuenta WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera ) AS NUMERO_CUENTA,
	( SELECT numero FROM ca_telefono WHERE idtelefono = ref.idtelefono ) AS TELEFONO,
	( SELECT nombre FROM ca_final WHERE idfinal = ref.idfinal LIMIT 1 ) AS ESTADO,
	DATE(ref.fecha) AS FECHA,
	TRUNCATE(IFNULL(ref.total_deuda,0),2) AS TOTAL_DEUDA,
	ref.numero_cuota AS NUMERO_CUOTAS,
	TRUNCATE(IFNULL(ref.monto_cuota,0),2) AS MONTO_CUOTA,
	ref.observacion AS OBSERVACION,
	IFNULL(ref.objecion,'') AS OBJECION
	FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_refinanciamiento ref 
	ON ref.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND car.idcartera = clicar.idcartera
	WHERE cli.idservicio = ? AND car.estado = 1 ".$trace_sql."
	AND DATE(ref.fecha) BETWEEN ? AND ? 
	";
	
	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
	$pr->execute();
	$i = 0;
	
	echo '<table>';
	
	echo '<tr>';
	foreach( $header as $key => $value ) 
	{
	
		echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center">'.$value.'</td>';

	}
	echo '</tr>';
		
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

		/*if( $i == 0 ) {
			echo '<tr>';
			foreach( $row as $key => $value )
			{	
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center">'.$key.'</td>';
	
			}
			echo '</tr>';
		}*/

		$style="";
		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
		foreach( $row as $key => $value )
		{	if( $key == 'CODIGO_CLIENTE' || $key == 'NUMERO_DOCUMENTO' || $key == 'NUMERO_CUENTA' || $key == 'TELEFONO' ) {
				echo '<td style="'.$style.'" align="center">="'.strtoupper(utf8_decode($value)).'"</td>';
			}else{
				echo '<td style="'.$style.'" align="center">'.strtoupper(utf8_decode($value)).'</td>';
			}
			
		}
		echo '</tr>';

		$i++;

	}
	echo '</table>';


?>