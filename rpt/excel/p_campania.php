<?php
	header("Content-Type: text/html; charset=UTF-8");	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=pcampania.xls");
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
	
	$time=date("Y_m_d_H_i_s");
	
	$idcartera = $_REQUEST['cartera'];
	$servicio = $_REQUEST['servicio'];
	$provincia =$_REQUEST['provincia'];
	$campania =$_REQUEST['campania'];
	$monto_menor = $_REQUEST['monto_menor'];
	$monto_mayor = $_REQUEST['monto_mayor'];

	$sqlDataCartera = " SELECT idcartera,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera IN ( ".$idcartera." ) LIMIT 1 ";
	
	$prData = $connection->prepare($sqlDataCartera);
	//$prData->bindParam(1,$_GET['Cartera'],PDO::PARAM_INT);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
	$arrayCuenta = json_decode($dataCuenta,true);
	
	$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
	$arrayDetalle = json_decode($dataDetalle,true);
	
	$field = array();
	
	for( $i=0;$i<count($arrayCuenta);$i++ ) {
		if( $arrayCuenta[$i]['campoT'] != 'fecha_inicio' && $arrayCuenta[$i]['campoT'] != 'fecha_fin' && $arrayCuenta[$i]['campoT'] != 'total_deuda' && $arrayCuenta[$i]['campoT'] != 'monto_pagado' ) {
			array_push($field," cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
		}
	}
	
	for( $i=0;$i<count($arrayDetalle);$i++ ) {
		if( $arrayDetalle[$i]['campoT'] == 'total_deuda_soles' || $arrayDetalle[$i]['campoT'] == 'total_deuda_dolares' || $arrayDetalle[$i]['campoT'] == 'monto_mora' || $arrayDetalle[$i]['campoT'] == 'monto_mora_soles' || $arrayDetalle[$i]['campoT'] == 'monto_mora_dolares' || $arrayDetalle[$i]['campoT'] == 'saldo_capital' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_soles' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_dolares' ) {
			array_push($field," SUM( detcu.".$arrayDetalle[$i]['campoT']." ) AS '".$arrayDetalle[$i]['label']."' ");
		}else if( $arrayDetalle[$i]['campoT'] == 'fecha_vencimiento' || $arrayDetalle[$i]['campoT'] == 'fecha_emision'){
			//$fecha_vencimiento = str_replace(" ","_",$arrayDetalle[$i]['label']);
		}else if( $arrayDetalle[$i]['campoT'] == 'fecha_asignacion' ){
			$fecha_asignacion = str_replace(" ","_",$arrayDetalle[$i]['label']);
		}else if( $arrayDetalle[$i]['campoT'] == 'codigo_operacion' ){
			$codigo_operacion = str_replace(" ","_",$arrayDetalle[$i]['label']);
		}else if( $arrayDetalle[$i]['campoT'] == 'nombre_agencia' ){
			$nombre_agencia = str_replace(" ","_",$arrayDetalle[$i]['label']);
		}else if( $arrayDetalle[$i]['campoT'] == 'total_deuda' ){
			$total_deuda = str_replace(" ","_",$arrayDetalle[$i]['label']);
			array_push($field," SUM( detcu.".$arrayDetalle[$i]['campoT']." ) AS '".$arrayDetalle[$i]['label']."' ");
		}else if( $arrayDetalle[$i]['campoT'] == 'monto_pagado' ){
			$monto_pagado = str_replace(" ","_",$arrayDetalle[$i]['label']);
			array_push($field," SUM( detcu.".$arrayDetalle[$i]['campoT']." ) AS '".$arrayDetalle[$i]['label']."' ");
		}else{
			array_push($field," detcu.".$arrayDetalle[$i]['campoT']." AS '".$arrayDetalle[$i]['label']."' ");
		}
	}
	
	$sqlFacturas = " SELECT t2.idcuenta, 
		GROUP_CONCAT( IF( t2.flag=1,t2.codigo_operacion,NULL ) ) AS 'FACTURA1',
		GROUP_CONCAT( IF( t2.flag=1,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION1',
		GROUP_CONCAT( IF( t2.flag=1,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO1',
		GROUP_CONCAT( IF( t2.flag=1,t2.nombre_agencia,NULL ) ) AS 'CARGO1',
		GROUP_CONCAT( IF( t2.flag=1,t2.estado,NULL ) ) AS 'ESTADO1',
		GROUP_CONCAT( IF( t2.flag=2,t2.codigo_operacion,NULL ) ) AS 'FACTURA2',
		GROUP_CONCAT( IF( t2.flag=2,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION2',
		GROUP_CONCAT( IF( t2.flag=2,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO2',
		GROUP_CONCAT( IF( t2.flag=2,t2.nombre_agencia,NULL ) ) AS 'CARGO2',
		GROUP_CONCAT( IF( t2.flag=2,t2.estado,NULL ) ) AS 'ESTADO2',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA3',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION3',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO3',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO3',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO3',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA4',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION4',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO4',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO4',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO4',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA5',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION5',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO5',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO5',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO5',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA6',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION6',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO6',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO6',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO6',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA7',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION7',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO7',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO7',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO7',
		GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA8',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION8',
		GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO8',
		GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO8',
		GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO8'
		FROM
		(
		SELECT *, IF( STRCMP(@rowcli, numero_cuenta) = 0, @rownum:=@rownum+1, @rownum:=1  ) AS 'flag', @rowcli:=numero_cuenta 
		FROM 
		(
		SELECT idcuenta, numero_cuenta, codigo_operacion, fecha_emision, fecha_vencimiento, nombre_agencia, 
		CASE WHEN ( total_deuda - monto_pagado ) = 0 THEN 'C' WHEN ( total_deuda - monto_pagado ) >0 AND ( total_deuda - monto_pagado ) < total_deuda  THEN 'A' ELSE 'SP' END AS estado
		FROM ca_detalle_cuenta 
		WHERE idcartera IN ( $idcartera )
		ORDER BY idcuenta, fecha_vencimiento ASC 
		) t1
		,  ( SELECT @rownum:=1, @rowcli:='012' COLLATE utf8_spanish_ci  ) r
		) t2
		GROUP BY t2.idcuenta ";
	
	$sqlEstadosVisita = " SELECT tt2.idcuenta, 
		GROUP_CONCAT( tt2.flag = 1, tt2.estado,NULL ) AS 'ESTADO_VISITA1',
		GROUP_CONCAT( tt2.flag = 2, tt2.estado,NULL ) AS 'ESTADO_VISITA2',
		GROUP_CONCAT( tt2.flag = 3, tt2.estado,NULL ) AS 'ESTADO_VISITA3',
		GROUP_CONCAT( tt2.flag = 4, tt2.estado,NULL ) AS 'ESTADO_VISITA4',
		GROUP_CONCAT( tt2.flag = 5, tt2.estado,NULL ) AS 'ESTADO_VISITA5'
		FROM
		(
		SELECT * , IF( STRCMP(@rowcu, idcuenta) = 0, @rownum:=@rownum+1, @rownum:=1  ) AS 'flag', @rowcu:=idcuenta
		FROM
		(
		SELECT vis.idcuenta , CONCAT(fs.codigo,'  ', f.nombre,' --> ', ( SELECT nombre FROM ca_nivel WHERE idnivel = f.idnivel ) ) AS 'estado'
		FROM ca_cliente_cartera cc INNER JOIN ca_visita vis INNER JOIN ca_final f INNER JOIN ca_final_servicio fs 
		ON fs.idfinal = f.idfinal AND f.idfinal = vis.idfinal AND vis.idcliente_cartera = cc.idcliente_cartera
		WHERE fs.idservicio = 1 AND vis.estado = 1 AND cc.idcartera IN ( $idcartera )
		ORDER BY vis.idcuenta
		) tt1, ( SELECT @rownum:=1, @rowcu:='012' ) r
		) tt2 GROUP BY tt2.idcuenta ";
	
	$sql = " SELECT cu.idcuenta,
		( SELECT COUNT(*) FROM ca_visita WHERE idcuenta = cu.idcuenta AND estado = 1 ) AS 'NUMERO_VISITAS',
		cu.fecha_retiro AS 'FECHA_RETIRO',
		cu.motivo_retiro AS 'MOTIVO_RETIRO',
		car.evento AS 'EVENTO',
		car.cluster AS 'CLUSTER',
		car.segmento AS 'SEGMENTO',
		car.negocio AS 'NEGOCIO',
		car.fecha_inicio AS 'FECHA_INICIO',
		car.fecha_fin AS 'FECHA_FIN',
		clicar.codigo_cliente AS 'CODIGO_CLIENTE',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
		cli.numero_documento AS 'NUMERO_DOCUMENTO',
		cli.tipo_documento AS 'TIPO_DOCUMENTO',
		( SELECT direccion FROM ca_direccion WHERE idcuenta = cu.idcuenta AND is_new = 0 LIMIT 1 ) AS 'DIRECCION',
		( SELECT zona FROM ca_direccion WHERE idcuenta = cu.idcuenta AND is_new = 0 LIMIT 1 ) AS 'ZONA',
		CASE WHEN ( SUM(detcu.total_deuda) - SUM(detcu.monto_pagado) ) > 0 AND ( SUM(detcu.total_deuda) - SUM(detcu.monto_pagado) ) < SUM(detcu.total_deuda) THEN 'A' WHEN ( SUM(detcu.total_deuda) - SUM(detcu.monto_pagado) ) = SUM(detcu.total_deuda) THEN 'SP' ELSE 'C'  END AS 'ESTADO_FACTURA',
		".implode(",",$field)." , 
		tv1.ESTADO_VISITA1, 
		tv1.ESTADO_VISITA2, 
		tv1.ESTADO_VISITA3, 
		tv1.ESTADO_VISITA4, 
		tv1.ESTADO_VISITA5, 
		detcu.fecha_reclamo AS 'FECHA_RECLAMO',
		detcu.estado_reclamo AS 'ESTADO_RECLAMO',
		detcu.observacion_reclamo AS 'OBSERVACION_RECLAMO',
		DATE(cu.ml_fecha) AS 'FECHA_MEJOR_LLAMADA',
		DATE(cu.ml_fcpg) AS 'FECHA_CPG_MEJOR_LLAMADA',
		cu.ml_observacion AS 'OBSERVACION_MEJOR_LLAMADA',
		DATE(cu.mv_fecha) AS 'FECHA_MEJOR_VISITA',
		cu.mv_observacion AS 'OBSERVACION_MEJOR_VISITA'
		FROM ca_cartera car 
		INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
		INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
		INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera
		INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta
		LEFT JOIN ( $sqlEstadosVisita ) tv1 ON tv1.idcuenta = cu.idcuenta
		WHERE car.idcartera IN ( $idcartera ) AND clicar.idcartera IN ( $idcartera ) 
		AND cli.idservicio = $servicio AND cu.idcartera IN ( $idcartera ) AND detcu.idcartera IN ( $idcartera )  
		AND cu.total_deuda BETWEEN $monto_menor AND $monto_mayor 
		GROUP BY cu.idcuenta ";
		
	$prMain = $connection->prepare($sql);
	$prMain->execute();
	$i = 0;
	
	echo '<table>';
	while( $row = $prMain->fetch(PDO::FETCH_ASSOC) ) {
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

