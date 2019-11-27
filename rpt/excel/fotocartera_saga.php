<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=fotocartera_saga.xls");
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
	
	$sqlDataCartera = " SELECT idcartera,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera = ? ";
	
	$sqlDataCarteraPago = " SELECT pago FROM ca_cartera_pago WHERE idcartera = ? ORDER BY idcartera_pago DESC LIMIT 1 ";
	
	$prData = $connection->prepare($sqlDataCartera);
	$prData->bindParam(1,$_GET['Cartera'],PDO::PARAM_INT);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	$prDataPago = $connection->prepare($sqlDataCarteraPago);
	$prDataPago->bindParam(1,$_GET['Cartera'],PDO::PARAM_INT);
	$prDataPago->execute();
	$dataCarteraPago = $prDataPago->fetchAll(PDO::FETCH_ASSOC);
	
        
	$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
	$arrayCuenta = json_decode($dataCuenta,true);
	
	$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
	$arrayDetalle = json_decode($dataDetalle,true);
	
	$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
	$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
/*	$dataPago = str_replace("\\","",$dataCarteraPago[0]['pago']);
	$arrayPago = json_decode($dataPago,true);*/
	
	$field = array();
	$fieldPago = array();
	
	for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cliente']);$i++ ) {
        if($arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']=='CARTERA'){
		array_push($field," clicar.".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']."' ");
        }
	}
        
	for( $i=0;$i<count($arrayCuenta);$i++ ) {
		if( $arrayCuenta[$i]['campoT'] == 'numero_cuenta' ) {
			array_push($field," CONCAT('=\"',cu.".$arrayCuenta[$i]['campoT'].",'\"') AS '".$arrayCuenta[$i]['label']."' ");
		}else{
			array_push($field," cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
		}
	}
	
	for( $i=0;$i<count($arrayDetalle);$i++ ) {
		if( $arrayDetalle[$i]['campoT'] == 'codigo_operacion' ) {
			array_push($field," CONCAT('=\"',det.".$arrayDetalle[$i]['campoT'].",'\"') AS '".$arrayDetalle[$i]['label']."' ");
		}else{
			array_push($field," det.".$arrayDetalle[$i]['campoT']." AS '".$arrayDetalle[$i]['label']."' ");
		}
	}
	/*
	for( $i=0;$i<count($arrayPago);$i++ ) {
		array_push($fieldPago," pag.".$arrayPago[$i]['campoT']." AS '".$arrayPago[$i]['label']."' ");
	}
		$str_implode_pago = "";
                $join_pago = "";
                $where_pago="";
                */

	/*$sqlCountPago = " SELECT COUNT(*) AS 'COUNT' FROM ca_pago WHERE estado = 1 AND idcartera = ".$_GET['Cartera']." ";
	$prCountPago = $connection->prepare($sqlCountPago);
	$prCountPago->execute();
	$dataCountPago = $prCountPago->fetchAll(PDO::FETCH_ASSOC);
	$countPago = (int)$dataCountPago[0]['COUNT'];
	
	$implode_pago = implode(",",$fieldPago);
	$str_implode_pago = "";
	$where_pago = "";
	$join_pago = "";
	
	if( count($arrayPago) > 0 && $countPago > 500 ) {
		$str_implode_pago = " , ".$implode_pago;
		$where_pago = " AND pag.idcartera = ".$_GET['Cartera']." AND pag.estado = 1 "; 
		$join_pago = " LEFT JOIN  ca_pago pag ON pag.iddetalle_cuenta = det.iddetalle_cuenta "; 
	}*/

	$sqlLV = " SELECT CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODIGO_CLIENTE', 
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE',
			CONCAT('=\"',cli.numero_documento,'\"') AS 'NUMERO_DOCUMENTO',
			cli.tipo_documento AS 'TIPO_DOCUMENTO',
			( SELECT direccion FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DIRECCION',
			( SELECT distrito FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DISTRITO',
			( SELECT provincia FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'PROVINCIA',
			( SELECT departamento FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DEPARTAMENTO',
            ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio) as 'ASIGNACION',
            ".implode(",",$field).", 
			TRUNCATE( det.total_deuda + IFNULL(det.comision_total_deuda,0) - IFNULL(det.monto_pagado,0) , 2 ) AS 'SALDO_ACTUAL',
			IF( clicar.estado = 1, 'ACTIVO', 'INACTIVO' ) AS '_STATUS_',
			(select numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idcuenta=cu.idcuenta and idorigen='1' and idtipo_referencia='2' limit 1) as telefono,
			(select numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idcuenta=cu.idcuenta and idorigen='1' and idtipo_referencia='3' limit 1) as telefono2,
			DATE(cu.ml_fecha) AS 'ML_FECHA',
			( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO', 
			( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel  WHERE fin.idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_PERFIL_CLIENTE',
			DATE( cu.ml_fcpg ) AS 'ML_FCPG', cu.ml_observacion AS 'ML_OBSERVACION' ,
			cu.ml_operador AS 'ML_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_OPERADOR',
			DATE(cu.ul_fecha) AS 'UL_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'UL_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'UL_ESTADO', ( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel  WHERE fin.idfinal = cu.ml_estado LIMIT 1 ) AS 'UL_PERFIL_CLIENTE',
			DATE( cu.ul_fcpg ) AS 'UL_FCPG', cu.ul_observacion AS 'UL_OBSERVACION',
			cu.ul_operador AS 'UL_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_OPERADOR' ,
			DATE(cu.mv_fecha) AS 'MV_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'VL_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'MV_ESTADO', DATE( cu.mv_fcpg ) AS 'MV_FCPG', cu.mv_observacion AS 'MV_OBSERVACION',
			cu.mv_operador AS 'MV_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_notificador ) AS 'MV_OPERADOR' ,
			DATE(cu.uv_fecha) AS 'UV_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'UV_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'UV_ESTADO', DATE( cu.uv_fcpg ) AS 'UV_FCPG', cu.uv_observacion AS 'UV_OBSERVACION' ,
			cu.uv_operador AS 'UV_CODOPE', ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_notificador ) AS 'UV_OPERADOR'
			FROM ca_cliente cli 
			INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente
			INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_detalle_cuenta det ON det.idcuenta = cu.idcuenta
			 
			WHERE cli.idservicio = ? AND clicar.idcartera = ? AND cu.idcartera = ? AND det.idcartera = ?  ";
		
	
	$pr5 = $connection->prepare($sqlLV);
	$pr5->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
	$pr5->bindParam(2,$_GET['Cartera'],PDO::PARAM_INT);
	$pr5->bindParam(3,$_GET['Cartera'],PDO::PARAM_INT);
	$pr5->bindParam(4,$_GET['Cartera'],PDO::PARAM_INT);
	$pr5->execute();
	$count2=0;
	?>
    	<table>
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
        </table>
	<?php
	echo '<table>';
	while( $row3 = $pr5->fetch(PDO::FETCH_ASSOC) ) {
		if( $count2 == 0 ) {
			echo '<tr>';
			foreach( $row3 as $index => $value ){
				echo '<td align="center" style="color:white;background-color:blue;">'.$index.'</td>';
			}
			echo '</tr>';
		}
		echo '<tr>';
		foreach( $row3 as $index => $value ){
			echo '<td align="center">'.$value.'</td>';
		}
		echo '</tr>';
		$count2++;
	}
	echo '</table>';
	
	
?>
