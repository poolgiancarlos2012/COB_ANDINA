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
	
	date_default_timezone_set('America/Lima');
	
	$time=date("Y_m_d_H_i_s");
	
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$idcartera =$_REQUEST['cartera'];
	$provincia =$_REQUEST['provincia'];
	$campania =$_REQUEST['campania'];
	$monto_menor = $_REQUEST['monto_menor'];
	$monto_mayor = $_REQUEST['monto_mayor'];
	
	$fecha_asignacion = "FECHA_ASIGNACION";
	$fecha_vencimiento = "FECHA_VENCIMIENTO";
	$total_deuda = "TOTAL_DEUDA";
	$monto_pagado = "MONTO_PAGADO";
	$nombre_agencia = "NOMBRE_AGENCIA";
	$codigo_operacion = "CODIGO_OPERACION";
	
	$sqlDataCartera = " SELECT idcartera,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera IN ( ".$idcartera." ) LIMIT 1 ";
	
	$prData = $connection->prepare($sqlDataCartera);
	//$prData->bindParam(1,$_GET['Cartera'],PDO::PARAM_INT);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
	$arrayCuenta = json_decode($dataCuenta,true);
	
	$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
	$arrayDetalle = json_decode($dataDetalle,true);
	
	$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
	$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
	$field = array();
	
	for( $i=0;$i<count($arrayCuenta);$i++ ) {
		if( $arrayCuenta[$i]['campoT'] != 'fecha_inicio' && $arrayCuenta[$i]['campoT'] != 'fecha_fin' && $arrayCuenta[$i]['campoT'] != 'total_deuda' && $arrayCuenta[$i]['campoT'] != 'monto_pagado' ) {
			array_push($field," cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
		}
	}
	
	for( $i=0;$i<count($arrayDetalle);$i++ ) {
		if( $arrayDetalle[$i]['campoT'] == 'total_deuda_soles' || $arrayDetalle[$i]['campoT'] == 'total_deuda_dolares' || $arrayDetalle[$i]['campoT'] == 'monto_mora' || $arrayDetalle[$i]['campoT'] == 'monto_mora_soles' || $arrayDetalle[$i]['campoT'] == 'monto_mora_dolares' || $arrayDetalle[$i]['campoT'] == 'saldo_capital' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_soles' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_dolares' ) {
			array_push($field," SUM( detcu.".$arrayDetalle[$i]['campoT']." ) AS '".$arrayDetalle[$i]['label']."' ");
		}else if( $arrayDetalle[$i]['campoT'] == 'fecha_vencimiento'){
			$fecha_vencimiento = str_replace(" ","_",$arrayDetalle[$i]['label']);
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
	
	/*$sql = " SELECT clicar.codigo_cliente AS 'CODIGO_CLIENTE', car.nombre_cartera AS 'NOMBRE_GESTION', 
		car.fecha_inicio AS 'FECHA_INICIO_GESTION',car.fecha_fin AS 'FECHA_FIN_GESTION', 
		DATE(car.fecha_creacion) AS 'FECHA_DISTRIBUCION_GESTION',
		datcu.dato29 AS 'EVENTO_GESTION',
		datcu.dato28 AS 'CLUSTER_GESTION', '' AS 'ESTADO_SECTORISTA',datcu.dato3 AS 'ESTADO_PC', datcu.dato13 AS 'SUB_TIPO_PC',
		datcu.dato9 AS 'SEGMENTO_CUENTA',
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.mv_estado ) AS 'ESTADO_VISITA1',
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.uv_estado ) AS 'ESTADO_VISITA2',
		'' AS 'ESTADO_VISITA3', '' AS 'ESTADO_VISITA4', '' AS 'ESTADO_VISITA5',
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'OPERADOR',
		DATE(cu.ml_fecha ) AS 'FECHA_MEJOR_LLAMADA', 
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.ml_estado ) AS 'ESTADO_MEJOR_LLAMADA',
		DATE(cu.ml_fcpg) AS 'FECHA_CPG_LLAMADA', REPLACE(cu.ml_observacion,'\n','') AS 'OBSERVACION_MEJOR_LLAMADA',
		( SELECT COUNT(*) FROM ca_transaccion WHERE idcliente_cartera = clicar.idcliente_cartera AND is_visita = 1 ) AS 'CANTIDAD_VISITA',
		DATE(cu.mv_fecha ) AS 'FECHA_MEJOR_VISITA', 
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.mv_estado ) AS 'ESTADO_MEJOR_VISITA',
		DATE(cu.mv_fcpg) AS 'FECHA_CPG_VISITA', REPLACE(cu.mv_observacion,'\n','') AS 'OBSERVACION_MEJOR_VISITA',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE', IFNULL(cli.tipo_documento,'') AS 'TIPO_DOCUMENTO',
		IFNULL(cli.numero_documento,'') AS 'NUMERO_DOCUMENTO', 
		( SELECT CONCAT(direccion,' , ', calle,' , ',numero) FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DIRECCION',
		( SELECT departamento FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DEPARTAMENTO',
		( SELECT provincia FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'PROVINCIA',
		( SELECT distrito FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DISTRITO',
		".implode(",",$field)." 
		FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_datos_adicionales_cuenta datcu INNER JOIN ca_detalle_cuenta detcu 
		ON detcu.idcuenta = cu.idcuenta AND datcu.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND clicar.idcartera = car.idcartera 
		WHERE cu.retirado = 0 AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) AND detcu.idcartera IN ( ".$idcartera." ) 
		AND cu.total_deuda BETWEEN ".$monto_menor." AND ".$monto_mayor." 
		GROUP BY clicar.codigo_cliente ";*/
	
	$sql = " SELECT cu.idcuenta AS 'IDCUENTA',clicar.codigo_cliente AS 'CODIGO_CLIENTE', car.nombre_cartera AS 'NOMBRE_GESTION', 
		car.fecha_inicio AS 'FECHA_INICIO_GESTION',car.fecha_fin AS 'FECHA_FIN_GESTION', 
		DATE(car.fecha_creacion) AS 'FECHA_DISTRIBUCION_GESTION',
		datcu.dato29 AS 'EVENTO_GESTION',
		datcu.dato28 AS 'CLUSTER_GESTION', '' AS 'ESTADO_SECTORISTA',datcu.dato3 AS 'ESTADO_PC', datcu.dato13 AS 'SUB_TIPO_PC',
		datcu.dato9 AS 'SEGMENTO_CUENTA',
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.mv_estado ) AS 'ESTADO_VISITA1',
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.uv_estado ) AS 'ESTADO_VISITA2',
		'' AS 'ESTADO_VISITA3', '' AS 'ESTADO_VISITA4', '' AS 'ESTADO_VISITA5',
		( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'OPERADOR',
		DATE(cu.ml_fecha ) AS 'FECHA_MEJOR_LLAMADA', 
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.ml_estado ) AS 'ESTADO_MEJOR_LLAMADA',
		DATE(cu.ml_fcpg) AS 'FECHA_CPG_LLAMADA', REPLACE(cu.ml_observacion,'\n','') AS 'OBSERVACION_MEJOR_LLAMADA',
		( SELECT COUNT(*) FROM ca_transaccion WHERE idcliente_cartera = clicar.idcliente_cartera AND is_visita = 1 ) AS 'CANTIDAD_VISITA',
		DATE(cu.mv_fecha ) AS 'FECHA_MEJOR_VISITA', 
		( SELECT CONCAT(niv.nombre,'/',fin.nombre) FROM ca_nivel niv LEFT JOIN ca_final fin ON fin.idnivel = niv.idnivel WHERE fin.idfinal = cu.mv_estado ) AS 'ESTADO_MEJOR_VISITA',
		DATE(cu.mv_fcpg) AS 'FECHA_CPG_VISITA', REPLACE(cu.mv_observacion,'\n','') AS 'OBSERVACION_MEJOR_VISITA',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE', IFNULL(cli.tipo_documento,'') AS 'TIPO_DOCUMENTO',
		IFNULL(cli.numero_documento,'') AS 'NUMERO_DOCUMENTO', 
		( SELECT CONCAT(direccion,' , ', calle,' , ',numero) FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DIRECCION',
		( SELECT departamento FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DEPARTAMENTO',
		( SELECT provincia FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'PROVINCIA',
		( SELECT distrito FROM ca_direccion WHERE idcliente = clicar.idcliente AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DISTRITO',
		".implode(",",$field)." 
		FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_datos_adicionales_cuenta datcu INNER JOIN ca_detalle_cuenta detcu 
		ON detcu.idcuenta = cu.idcuenta AND datcu.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND clicar.idcartera = car.idcartera 
		WHERE cu.retirado = 0 AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) AND detcu.idcartera IN ( ".$idcartera." ) 
		AND cu.total_deuda BETWEEN ".$monto_menor." AND ".$monto_mayor." 
		GROUP BY cu.idcuenta ";
		
	/*$sqlMaxF = " SELECT MAX(t2.flag) AS 'COUNT' FROM (
		SELECT t1.codigo_cliente, t1.codigo_operacion, t1.total_deuda, if( strcmp(@rowcli, t1.codigo_cliente) = 0, @rownum:=@rownum+1, @rownum:=1  ) as 'flag', @rowcli:=codigo_cliente
		FROM (
		SELECT codigo_cliente, codigo_operacion, total_deuda
		FROM ca_detalle_cuenta WHERE idcartera IN ( ".$idcartera." ) AND total_deuda BETWEEN ".$monto_menor." AND ".$monto_mayor." 
		GROUP BY codigo_cliente, codigo_operacion
		) t1, ( SELECT @rownum:=1, @rowcli:='012' COLLATE utf8_spanish_ci  ) r  
		) t2 ";*/
		
	$sqlMaxF = " SELECT IFNULL(MAX(t2.flag),0) AS 'COUNT' FROM (
		SELECT t1.codigo_cliente, t1.codigo_operacion, t1.total_deuda, if( strcmp(@rowcli, t1.codigo_cliente) = 0, @rownum:=@rownum+1, @rownum:=1  ) as 'flag', @rowcli:=codigo_cliente
		FROM (
		SELECT codigo_cliente, codigo_operacion, total_deuda
		FROM ca_detalle_cuenta WHERE idcartera IN ( ".$idcartera." ) AND total_deuda BETWEEN ".$monto_menor." AND ".$monto_mayor." 
		GROUP BY idcuenta
		) t1, ( SELECT @rownum:=1, @rowcli:='012' COLLATE utf8_spanish_ci  ) r  
		) t2 ";
	
	$prMaxF = $connection->prepare($sqlMaxF);
	$prMaxF->execute();
	$DataCountMaxF = $prMaxF->fetchAll(PDO::FETCH_ASSOC);
	$CountMaxF = $DataCountMaxF[0]['COUNT'];
	
	$field2 = array();
	$field3 = array();
	
	for( $i=0;$i<$CountMaxF;$i++ ) {
		array_push($field2," GROUP_CONCAT( IF( t2.flag = ".($i+1)." , t2.codigo_operacion, NULL ) ) AS '".$codigo_operacion."_".($i+1)."' ");
		array_push($field2," SUM( IF( t2.flag = ".($i+1)." , t2.total_deuda, 0 ) ) AS '".$total_deuda."_".($i+1)."' ");
		array_push($field2," SUM( IF( t2.flag = ".($i+1)." , t2.monto_pagado, 0 ) ) AS '".$monto_pagado."_".($i+1)."' ");
		array_push($field2," GROUP_CONCAT( IF( t2.flag = ".($i+1)." , t2.fecha_vencimiento, NULL ) ) AS '".$fecha_vencimiento."_".($i+1)."' ");
		array_push($field2," GROUP_CONCAT( IF( t2.flag = ".($i+1)." , t2.fecha_asignacion, NULL ) ) AS '".$fecha_asignacion."_".($i+1)."' ");
		array_push($field2," GROUP_CONCAT( IF( t2.flag = ".($i+1)." , t2.nombre_agencia, NULL ) ) AS '".$nombre_agencia."_".($i+1)."' ");
		
		array_push($field3," T9.".$codigo_operacion."_".($i+1)." ");
		array_push($field3," T9.".$total_deuda."_".($i+1)." ");
		array_push($field3," T9.".$monto_pagado."_".($i+1)." ");
		array_push($field3," T9.".$fecha_vencimiento."_".($i+1)." ");
		array_push($field3," T9.".$fecha_asignacion."_".($i+1)." ");
		array_push($field3," T9.".$nombre_agencia."_".($i+1)." ");
	}
		
	/*$sqlFac = " SELECT  
		t2.codigo_cliente, ".implode(",",$field2)." 
		FROM
		(
		SELECT t1.idcuenta, t1.codigo_cliente, t1.codigo_operacion, t1.total_deuda, t1.monto_pagado, t1.fecha_vencimiento, t1.fecha_asignacion, t1.nombre_agencia, if( strcmp(@rowcli, t1.codigo_cliente) = 0, @rownum:=@rownum+1, @rownum:=1  ) as 'flag', @rowcli:=codigo_cliente
		FROM (
		SELECT idcuenta, iddetalle_cuenta, codigo_cliente, codigo_operacion, total_deuda, monto_pagado, fecha_vencimiento, nombre_agencia, fecha_asignacion
		FROM ca_detalle_cuenta WHERE idcartera IN ( ".$idcartera." ) 
		GROUP BY codigo_cliente, codigo_operacion 
		) t1, ( SELECT @rownum:=1, @rowcli:='012' COLLATE utf8_spanish_ci  ) r 
		) t2 GROUP BY t2.codigo_cliente ";*/
		
	$implode_field_3 = "";
	if( count($field3) > 0 ) {
		$implode_field_3 = " , ".implode(",",$field3) ;
	}
	
	$implode_field_2 = "";
	if( count($field2) > 0 ) {
		$implode_field_2 = " , ".implode(",",$field2) ;
	}
		
	$sqlFac = " SELECT  
		t2.idcuenta, t2.codigo_cliente ".$implode_field_2." 
		FROM
		(
		SELECT t1.idcuenta, t1.codigo_cliente, t1.codigo_operacion, t1.total_deuda, t1.monto_pagado, t1.fecha_vencimiento, t1.fecha_asignacion, t1.nombre_agencia, if( strcmp(@rowcli, t1.codigo_cliente) = 0, @rownum:=@rownum+1, @rownum:=1  ) as 'flag', @rowcli:=codigo_cliente
		FROM (
		SELECT idcuenta, iddetalle_cuenta, codigo_cliente, codigo_operacion, total_deuda, monto_pagado, fecha_vencimiento, nombre_agencia, fecha_asignacion
		FROM ca_detalle_cuenta WHERE idcartera IN ( ".$idcartera." ) 
		GROUP BY idcuenta 
		) t1, ( SELECT @rownum:=1, @rowcli:='012' COLLATE utf8_spanish_ci  ) r 
		) t2 GROUP BY t2.idcuenta ";
		
	/*******************/
	$create_table_tmp_sql = " CREATE TEMPORARY TABLE tmp_data_".$time." AS $sql ";
	$prCreateTableSQL = $connection->prepare($create_table_tmp_sql);	
	$prCreateTableSQL->execute();
	
	$sqlIndexTMPSQL = " ALTER TABLE tmp_data_".$time." ADD INDEX( IDCUENTA ASC ) ";
	$prIndexTMPSQL = $connection->prepare($sqlIndexTMPSQL);
	$prIndexTMPSQL->execute();
			
	$create_table_tmp_fac = " CREATE TEMPORARY TABLE tmp_facturas_".$time." AS $sqlFac ";
	$prCreateTableFac = $connection->prepare($create_table_tmp_fac);
	$prCreateTableFac->execute();
	
	$sqlIndexTMPFac = " ALTER TABLE tmp_facturas_".$time." ADD INDEX( idcuenta ASC ) ";
	$prIndexTMPFac = $connection->prepare($sqlIndexTMPFac);
	$prIndexTMPFac->execute();
	/*******************/
		
	//$sqlMain = " SELECT T10.*, ".implode(",",$field3)." FROM (select * from ( $sql ) TX where PROVINCIA='".$provincia."') T10 INNER JOIN ( $sqlFac ) T9 ON T10.CODIGO_CLIENTE = T9.codigo_cliente ";
	$sqlMain = " SELECT T10.* ".$implode_field_3." FROM tmp_data_".$time." T10 INNER JOIN tmp_facturas_".$time." T9 ON T10.IDCUENTA = T9.idcuenta WHERE TRIM( T10.PROVINCIA ) = '".$provincia."' ";
	//echo($provincia);
	//echo($sqlMain);
	$prMain = $connection->prepare($sqlMain);
	$prMain->execute();
	$count = 0;
	//echo '<table bordercolor="#FFFFFF">';
	while( $row = $prMain->fetch(PDO::FETCH_ASSOC) ) {
		if( $count == 0 ) {
			//echo '<tr>';
			foreach( $row as $index => $value ) {
				echo (utf8_decode($index)."\t");
			}
			echo ("\r\n");
		}
		//echo '<tr>';
		foreach( $row as $index => $value ) {
			echo (utf8_decode($value)."\t");
		}
		echo ("\r\n");
		$count++;
	}
	//echo '</table>';
?>

