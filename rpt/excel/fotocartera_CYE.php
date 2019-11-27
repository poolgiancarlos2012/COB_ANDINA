<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=fotocartera.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	$idCartera = $_GET['Cartera'];
	$nombre_servicio = $_GET['NombreServicio'];
	
	if ( $gd = @opendir('../../documents/fotocartera/' . $nombre_servicio)) {
		@closedir( $gd );
	} else {
		@mkdir('../../documents/fotocartera/' . $nombre_servicio);
	}
	
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$sqlDataCartera = " SELECT idcartera,tabla,archivo,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales 
						FROM ca_cartera WHERE idcartera IN ($idCartera) ";
	
	$prData = $connection->prepare($sqlDataCartera);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	
	
	$dataTabla = $dataCartera[0]['tabla'];
	$dataArchivo = $dataCartera[0]['archivo'];

	$path = "../../documents/fotocartera/" . $nombre_servicio . "/" . $dataArchivo;
	
	if( $dataTabla == '' || $dataTabla == 'temporal eliminada' ) {
	
		$ArchivoFoto = @fopen($path, 'r+');

		if ( $ArchivoFoto ) {

			while ( !feof($ArchivoFoto)) {

				$linea = fgets($ArchivoFoto);

				echo implode("\t",explode("|",$linea))."\n";

			}

			@fclose( $ArchivoFoto );

		}else{
			echo "Error al abrir archivo";
		}
	
	}else{
		
		$tmpArchivo = @fopen( $path , 'w');
		
		$sqlDataCarteraPago = " SELECT pago FROM ca_cartera_pago WHERE idcartera IN ( $idCartera ) ORDER BY idcartera_pago DESC LIMIT 1 ";

		$prDataPago = $connection->prepare($sqlDataCarteraPago);
		$prDataPago->execute();
		$dataCarteraPago = $prDataPago->fetchAll(PDO::FETCH_ASSOC);
		
		
		$dataCliente = str_replace("\\","",$dataCartera[0]['cliente']);
		$arrayCliente = json_decode($dataCliente,true);
	
		$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
		$arrayCuenta = json_decode($dataCuenta,true);
	
		$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
		$arrayDetalle = json_decode($dataDetalle,true);
	
		$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
		$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
		$dataPago = str_replace("\\","",@$dataCarteraPago[0]['pago']);
		$arrayPago = json_decode($dataPago,true);
	
		$fieldPago = array();
		$fieldP = array();
	
		for( $i=0;$i<count($arrayPago);$i++ ) {
			if( $arrayPago[$i]['campoT']!='numero_cuenta' && $arrayPago[$i]['campoT']!='codigo_operacion' ) {
				if( $arrayPago[$i]['campoT']=='monto_pagado' ) {
					array_push($fieldPago," TRUNCATE( pag.".$arrayPago[$i]['campoT'].", 2 ) AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, " SUM( ".$arrayPago[$i]['campoT']." ) AS '".$arrayPago[$i]['campoT']."'"  );
				}else if( $arrayPago[$i]['campoT']=='fecha' || $arrayPago[$i]['campoT']=='fecha_envio' ){
					array_push($fieldPago," IF( DATE( pag.".$arrayPago[$i]['campoT']." ) = '0000-00-00','',DATE( pag.".$arrayPago[$i]['campoT']." ) ) AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, $arrayPago[$i]['campoT'] );
				}else{
					array_push($fieldPago," pag.".$arrayPago[$i]['campoT']." AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, $arrayPago[$i]['campoT'] );
				}
			}
	
		}
	
		$str_implode_pago = "";
		$implode_pago = implode(",",$fieldPago);
		$where_pago = "";
		$join_pago = "";
		if( count($arrayPago) >0 ) {
			$str_implode_pago = " ".$implode_pago." , ";
			//$where_pago = " AND pag.idcartera IN ( $idCartera ) ";
			$where_pago = " ";
			$join_pago = " LEFT JOIN ca_pago pag ON pag.iddetalle_cuenta = detcu.iddetalle_cuenta AND pag.estado = 1 AND pag.idcartera IN ( $idCartera ) ";
		}
	
		$field = array();
	
		/*for( $i=0;$i<count($arrayCliente);$i++ ){
			array_push($field," cli.".$arrayCliente[$i]['campoT']." AS '".$arrayCliente[$i]['label']."' ");
		}
	
		for( $i=0;$i<count($arrayCuenta);$i++ ) {
			if( $arrayCuenta[$i]['campoT'] != 'fecha_inicio' && $arrayCuenta[$i]['campoT'] != 'total_deuda' && $arrayCuenta[$i]['campoT'] != 'fecha_fin' ) {
				if( $arrayCuenta[$i]['campoT'] == 'total_deuda' || $arrayCuenta[$i]['campoT'] == 'monto_mora' || $arrayCuenta[$i]['campoT'] == 'saldo_capital' || $arrayCuenta[$i]['campoT'] == 'monto_pagado' ) {
					array_push($field," TRUNCATE( cu.".$arrayCuenta[$i]['campoT'].", 2 ) AS '".$arrayCuenta[$i]['label']."' ");
				}else{
					array_push($field," cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
				}
			}
		}
	
		for( $i=0;$i<count($arrayDetalle);$i++ ) {
			if( $arrayDetalle[$i]['campoT'] != 'codigo_operacion' ) {
				if( $arrayDetalle[$i]['campoT'] == 'total_deuda' || $arrayDetalle[$i]['campoT'] == 'total_deuda_soles' || $arrayDetalle[$i]['campoT'] == 'total_deuda_dolares' || $arrayDetalle[$i]['campoT'] == 'saldo_capital' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_soles' || $arrayDetalle[$i]['campoT'] == 'saldo_capital_dolares' || $arrayDetalle[$i]['campoT'] == 'monto_mora' || $arrayDetalle[$i]['campoT'] == 'monto_mora_soles' || $arrayDetalle[$i]['campoT'] == 'monto_mora_dolares' || $arrayDetalle[$i]['campoT'] == 'monto_pagado' ) {
					array_push($field," TRUNCATE( det.".$arrayDetalle[$i]['campoT']." , 2 ) AS '".$arrayDetalle[$i]['label']."' ");
				}else{
					array_push($field," det.".$arrayDetalle[$i]['campoT']." AS '".$arrayDetalle[$i]['label']."' ");
				}
			}
		}
	
		for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cliente']);$i++ ) {
			array_push($field," clicar.".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']."' ");
		}
	
		for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cuenta']);$i++ ) {
			array_push($field," cu.".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['label']."' ");
		}*/
	
		$sqlLV = " SELECT clicar.dato8 as 'ITEM',clicar.dato1 as 'CARTERA',cli.tipo_documento as 'TIPO_DOC_IDENT',
				cli.numero_documento as 'NUM_DOC_IDENT',cu.numero_cuenta as 'NUM_CUENTA_ORIGEN',cli.nombre as 'CLIENTE', ".$str_implode_pago." 
				TRUNCATE(cu.monto_pagado,2) AS MONTO_PAGADO,
				IF( DATE(cu.ul_fecha_pago)='0000-00-00','',DATE(cu.ul_fecha_pago)) AS ULTIMA_FECHA_PAGO,
				CASE
				WHEN IFNULL(cu.monto_pagado,0)<=0 THEN 'SP'
				WHEN ( IFNULL(cu.total_deuda,0) - IFNULL(cu.monto_pagado,0) )<=0 THEN 'C' 
				ELSE 'A' 
				END AS ESTADO_PAGO,
				IF( cu.retirado = 1 ,'SI','NO' ) AS RETIRADO,
				( SELECT usu.codigo FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_cliente_cartera clicar ON clicar.idusuario_servicio = ususer.idusuario_servicio AND ususer.idusuario = usu.idusuario WHERE clicar.idcartera = cu.idcartera AND clicar.idcliente_cartera = cu.idcliente_cartera ) AS CODIGO_OPERADOR,
				( SELECT concat_ws(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_cliente_cartera clicar ON clicar.idusuario_servicio = ususer.idusuario_servicio AND ususer.idusuario = usu.idusuario WHERE clicar.idcartera = cu.idcartera AND clicar.idcliente_cartera = cu.idcliente_cartera ) AS ASIGNACION,                
				DATE(cu.ml_fecha) AS 'ML_FECHA',
				( SELECT numero FROM ca_telefono WHERE idtelefono = cu.ml_telefono ) AS ML_TELEFONO,
				( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO', 
				DATE( cu.ml_fcpg ) AS 'ML_FCPG', 
				cu.ml_observacion AS 'ML_OBSERVACION' ,
				cu.ml_operador AS 'ML_CODOPE', 
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_OPERADOR',
				DATE(cu.ul_fecha) AS 'UL_FECHA',
				( SELECT numero FROM ca_telefono WHERE idtelefono = cu.ul_telefono ) AS UL_TELEFONO,
				( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'UL_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'UL_ESTADO', 
				DATE( cu.ul_fcpg ) AS 'UL_FCPG', cu.ul_observacion AS 'UL_OBSERVACION',
				cu.ul_operador AS 'UL_CODOPE', 
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_OPERADOR' ,
				DATE(cu.mv_fecha) AS 'MV_FECHA',
				( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'MV_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'MV_ESTADO', 
				DATE( cu.mv_fcpg ) AS 'MV_FCPG', 
				cu.mv_observacion AS 'MV_OBSERVACION',
				cu.mv_operador AS 'MV_CODOPE', 
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_notificador ) AS 'MV_GESTOR' ,
				DATE(cu.uv_fecha) AS 'UV_FECHA',
				( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'UV_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'UV_ESTADO', 
				DATE( cu.uv_fcpg ) AS 'UV_FCPG', 
				cu.uv_observacion AS 'UV_OBSERVACION' ,
				cu.uv_operador AS 'UV_CODOPE', 
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_notificador ) AS 'UV_GESTOR'
				FROM ca_cuenta cu 
				INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta 
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=cu.idcliente_cartera
				INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
				".$join_pago."
				WHERE cu.idcartera IN ($idCartera) AND detcu.idcartera IN ($idCartera) AND clicar.idcartera IN ($idCartera) ".$where_pago." 
				GROUP BY detcu.iddetalle_cuenta ";
	
		/*$sqlLV = " SELECT ".implode(",",$field).",
				TRUNCATE(det.monto_pagado,2) AS MONTO_PAGADO,
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS TELEOPERADOR,
				CASE 
				WHEN IFNULL(det.monto_pagado,0)<=0 THEN 'SP'
				WHEN ( IFNULL(det.total_deuda,0) - IFNULL(det.monto_pagado,0) )<=0 THEN 'C' 
				ELSE 'A' 
				END AS ESTADO_PAGO,
				IF( clicar.estado = 1, 'ACTIVO', 'INACTIVO' ) AS 'STATUS', 
				DATE(cu.ml_fecha) AS 'ML_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO', DATE( cu.ml_fcpg ) AS 'ML_FCPG', cu.ml_observacion AS 'ML_OBSERVACION' ,
				cu.ml_operador AS 'ML_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_OPERADOR',
				DATE(cu.ul_fecha) AS 'UL_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'UL_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'UL_ESTADO', DATE( cu.ul_fcpg ) AS 'UL_FCPG', cu.ul_observacion AS 'UL_OBSERVACION',
				cu.ul_operador AS 'UL_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_OPERADOR' ,
				DATE(cu.mv_fecha) AS 'MV_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'VL_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'MV_ESTADO', DATE( cu.mv_fcpg ) AS 'MV_FCPG', cu.mv_observacion AS 'MV_OBSERVACION',
				cu.mv_operador AS 'MV_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_operador ) AS 'MV_OPERADOR' ,
				DATE(cu.uv_fecha) AS 'UV_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'UV_CARGA',
				( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'UV_ESTADO', DATE( cu.uv_fcpg ) AS 'UV_FCPG', cu.uv_observacion AS 'UV_OBSERVACION' ,
				cu.uv_operador AS 'UV_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_operador ) AS 'UV_OPERADOR'
				FROM ca_detalle_cuenta det INNER JOIN ca_cuenta cu INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
				ON cli.idcliente = clicar.idcliente AND clicar.idcliente_cartera = cu.idcliente_cartera 
				AND cu.idcuenta = det.idcuenta
				WHERE cli.idservicio = ? AND clicar.idcartera IN ($idCartera) AND cu.idcartera IN ($idCartera) AND det.idcartera IN ($idCartera)  ";*/
	
//echo $sqlLV;
//exit();
                $sqlLV=str_replace("pag.dato2 AS 'CARTERA'  ,", "", $sqlLV);
		$pr5 = $connection->prepare($sqlLV);
		$pr5->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
		$pr5->execute();
		$count2=0;
	
		/*	<table>
				<tr>
					<td colspan="2" style="font-weight:bold;font-size:24px;">FOTOCARTERA</td>
				</tr>
				<tr>
					<td>Reporte generado:</td>
	
					<td><?php echo date("Y-m-d"); ?></td>
				</tr>
				<tr>
					<td style="height:40px;"></td>
				</tr>
			</table>*/
	
		while( $row3 = $pr5->fetch(PDO::FETCH_ASSOC) ) {
			if( $count2 == 0 ) {
				$header = array();
				foreach( $row3 as $index => $value ){
					if( $index!='idcartera' && $index!='idcliente' && $index!='idcliente_cartera' && $index!='idcuenta' ) {
						echo $index."\t";
						array_push( $header, $index );
					}
				}
				echo "\n";
				
				@fwrite( $tmpArchivo, implode("|",$header)."\r\n" );
			}
			
			$data = array();
			foreach( $row3 as $index => $value ){
				if( $index!='idcartera' && $index!='idcliente' && $index!='idcliente_cartera' && $index!='idcuenta' ) {
					
					echo '="'.str_replace("\n","",str_replace("\t","",$value)).'"'."\t";
					array_push( $data, str_replace("\n"," ",str_replace("|"," ",utf8_encode($value))) );
				}
			}
			echo "\n";
			
			@fwrite( $tmpArchivo, implode("|",$data)."\r\n" );
			
			$count2++;
		}
		
		@fclose( $tmpArchivo );
	
	
	}
	
?>
