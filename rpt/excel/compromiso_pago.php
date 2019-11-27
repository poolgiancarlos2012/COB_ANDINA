<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=COMPROMISO_PAGO.xls");
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
	$estados = $_REQUEST['Estados'];

?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">COMPROMISO DE PAGO</td>
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


	$sql = " 
		SELECT *
		FROM 
		(
		SELECT cu.idcuenta,car.nombre_cartera AS GESTION,
		car.fecha_inicio AS FECHA_INICIO,
		car.fecha_fin AS FECHA_FIN,
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_ABONADO,
		TRUNCATE( cu.total_deuda,2 ) AS MONTO_EXIGIBLE,
		TRUNCATE( cu.total_deuda - cu.monto_pagado , 2 ) AS SALDO,
		CASE WHEN ( cu.total_deuda - cu.monto_pagado ) = cu.total_deuda THEN 'SP' WHEN ( cu.total_deuda - cu.monto_pagado ) >0 AND ( cu.total_deuda - cu.monto_pagado ) < cu.total_deuda THEN 'A' ELSE 'C' END AS ESTADO_DEUDA,
		( SELECT niv.nombre FROM ca_final fin  INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal = lla.idfinal ) AS RESPUESTA_GESTION,
		( SELECT nombre FROM ca_final WHERE idfinal = lla.idfinal ) AS RESPUESTA_INCIDENCIA,
		cu.telefono AS TELEFONO_CARTERA,
		( SELECT numero FROM ca_telefono WHERE idtelefono = lla.idtelefono  ) AS TELEFONO_LLAMADA,
		DATE(lla.fecha) AS FECHA_LLAMADA,
		fecha_cp AS FECHA_PROMESA_PAGO,
                lla.monto_cp AS MONTO_PROMESA_PAGO,
		lla.observacion AS OBSERVACION,
		( SELECT usu.codigo FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  ) AS CODIGO_TELEOPERADOR,
		( SELECT CONCAT_WS(usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  ) AS TELEOPERADOR
		FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_llamada lla INNER JOIN ca_cliente cli
		ON cli.idcliente = clicar.idcliente AND lla.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera
		WHERE cli.idservicio = ? AND car.idcartera IN ( $idcartera ) AND clicar.idcartera IN ( $idcartera ) 
		AND cu.idcartera IN ( $idcartera ) AND lla.idfinal IN ( $estados )  
		ORDER BY cu.idcuenta , lla.fecha DESC 
		) t1 GROUP BY t1.idcuenta ";
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