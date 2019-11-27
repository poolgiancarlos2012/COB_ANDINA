<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=RESUMEN_GESTIONES.xls");
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

?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">RESUMEN DE GESTIONES</td>
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
		car.nombre_cartera AS NOMBRE_GESTION,
		car.fecha_inicio AS FECHA_INICIO,
		car.fecha_fin AS FECHA_FIN,
		SUM(cu.total_deuda) AS DEUDA_EXIGIBLE,
		car.meta_monto AS OBJETIVO_MONTO,
		( ( SUM(cu.monto_pagado) / SUM(cu.total_deuda) ) * 100 ) AS EFECTIVIDAD_MONTO,
		((SUM(cu.total_deuda)*car.meta_monto)/100) AS MONTO_OBJETIVO,
		SUM(cu.monto_pagado) AS MONTO_PAGADO,
		( ((SUM(cu.total_deuda)*car.meta_monto)/100) -  SUM(cu.monto_pagado) ) AS MONTO_FALTANTE,
		COUNT(DISTINCT clicar.idcliente_cartera) AS TOTAL_CLIENTES,
		car.meta_cliente AS OBJETIVO_CLIENTE,
		( COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) / COUNT(DISTINCT clicar.idcliente_cartera) )*100 AS EFECTIVIDAD_CLIENTE,
		((COUNT(DISTINCT clicar.idcliente_cartera)*car.meta_cliente)/100) AS CLIENTES_OBJETIVO,
		COUNT( DISTINCT IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) AS CLIENTES_RECUPERADOS,
		( ((COUNT(DISTINCT clicar.idcliente_cartera)*car.meta_cliente)/100) - COUNT( DISTINCT IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) ) AS CLIENTES_FALTANTES,
		COUNT( DISTINCT IF( clicar.id_ultima_llamada = 0, NULL, clicar.idcliente_cartera ) ) AS CLIENTES_GESTIONADOS,
		COUNT( DISTINCT IF( clicar.id_ultima_llamada = 0, clicar.idcliente_cartera, NULL ) ) AS CLIENTES_SIN_GESTIONAR,
		COUNT( DISTINCT IF( clicar.idusuario_servicio = 0, clicar.idcliente_cartera, NULL ) ) AS NO_ASIGNADOS,
		COUNT(*) AS TOTAL_CUENTAS,
		car.meta_cuenta AS OBJETIVO_CUENTA,
		( COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) / COUNT(*) )*100 AS EFECTIVIDAD_CUENTAS,
		((COUNT(*)*car.meta_cuenta )/100) AS CUENTAS_OBJETIVO,
		COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) AS CUENTAS_RECUPERADOS,
		( ((COUNT(*)*car.meta_cuenta )/100) - COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) ) AS CUENTAS_FALTANTES
		FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu 
		ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera
		WHERE car.idcartera IN ( $idcartera ) AND clicar.idcartera IN ( $idcartera ) AND cu.idcartera IN ( $idcartera )
		GROUP BY clicar.idcartera ";

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
	
	$sql = " SELECT 
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS TELEOPERADOR,
		car.nombre_cartera AS NOMBRE_GESTION,
		car.fecha_inicio AS FECHA_INICIO,
		car.fecha_fin AS FECHA_FIN,
		SUM(cu.total_deuda) AS DEUDA_EXIGIBLE,
		car.meta_monto AS OBJETIVO_MONTO,
		( ( SUM(cu.monto_pagado) / SUM(cu.total_deuda) ) * 100 ) AS EFECTIVIDAD_MONTO,
		((SUM(cu.total_deuda)*car.meta_monto)/100) AS MONTO_OBJETIVO,
		SUM(cu.monto_pagado) AS MONTO_PAGADO,
		( ((SUM(cu.total_deuda)*car.meta_monto)/100) -  SUM(cu.monto_pagado) ) AS MONTO_FALTANTE,
		COUNT(DISTINCT clicar.idcliente_cartera) AS TOTAL_CLIENTES,
		car.meta_cliente AS OBJETIVO_CLIENTE,
		( COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) / COUNT(DISTINCT clicar.idcliente_cartera) )*100 AS EFECTIVIDAD_CLIENTE,
		((COUNT(DISTINCT clicar.idcliente_cartera)*car.meta_cliente)/100) AS CLIENTES_OBJETIVO,
		COUNT( DISTINCT IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) AS CLIENTES_RECUPERADOS,
		( ((COUNT(DISTINCT clicar.idcliente_cartera)*car.meta_cliente)/100) - COUNT( DISTINCT IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcliente_cartera, NULL ) ) ) AS CLIENTES_FALTANTES,
		COUNT( DISTINCT IF( clicar.id_ultima_llamada = 0, NULL, clicar.idcliente_cartera ) ) AS CLIENTES_GESTIONADOS,
		COUNT( DISTINCT IF( clicar.id_ultima_llamada = 0, clicar.idcliente_cartera, NULL ) ) AS CLIENTES_SIN_GESTIONAR,
		COUNT( DISTINCT IF( clicar.idusuario_servicio = 0, clicar.idcliente_cartera, NULL ) ) AS NO_ASIGNADOS,
		COUNT(*) AS TOTAL_CUENTAS,
		car.meta_cuenta AS OBJETIVO_CUENTA,
		( COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) / COUNT(*) )*100 AS EFECTIVIDAD_CUENTAS,
		((COUNT(*)*car.meta_cuenta )/100) AS CUENTAS_OBJETIVO,
		COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) AS CUENTAS_RECUPERADOS,
		( ((COUNT(*)*car.meta_cuenta )/100) - COUNT( IF( cu.monto_pagado != 0 AND ISNULL(cu.monto_pagado) = 0  , cu.idcuenta, NULL ) ) ) AS CUENTAS_FALTANTES
		FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu 
		ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera
		WHERE car.idcartera IN ( $idcartera ) AND clicar.idcartera IN ( $idcartera ) AND cu.idcartera IN ( $idcartera )
		GROUP BY clicar.idusuario_servicio, clicar.idcartera ";
	
	echo '<table>';	
		echo '<tr><td style="height:40px;"></td></tr>';
		echo '<tr><td>Resumen por operador</td></tr>';
		echo '<tr><td style="height:40px;"></td></tr>';
	echo '</table>';	
		
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

