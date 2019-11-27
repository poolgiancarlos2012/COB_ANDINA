<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=fotocartera_andina_cobranzas.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	$Servicio = $_GET['Servicio'];
	$idCartera = $_GET['Cartera'];

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	$sql="	SELECT
			IFNULL((SELECT IF (SUBSTR(DATE(NOW())+0 FROM 1 FOR 6) = SUBSTR(DATE(fecha)+0 FROM 1 FOR 6),'CON GESTION','SIN GESTION') FROM ca_llamada WHERE idllamada = clicar.id_ultima_llamada),'SIN GESTION') AS 'ESTADO_GESTION_DEL_MES',
			IF(cu.retirado = 1, 'INACTIVO', 'ACTIVO' ) AS 'STATUS',
			detcu.dato1 AS 'cod_zon',
			detcu.dato2 AS 'empresa',
			detcu.dato3 AS 'zona',
			detcu.dato4 AS 'localidad',
			detcu.dato5 AS 'vend_actual',
			detcu.dato6 AS 'vend_rtc_actual',
			detcu.dato7 AS 'supervisor',
			clicar.tipo_cliente AS 'tipo_cliente',
			CONCAT('=\"',detcu.codigo_cliente,'\"') AS 'cod_cliente',
			IF (CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) <> '',CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)),cli.razon_social) AS 'cliente',
			detcu.dato8 AS 'td',
			detcu.codigo_operacion AS 'num_doc',
			detcu.fecha_emision AS 'fecha_doc',
			detcu.dato9 AS 'mes_emis',
			detcu.dato10 AS 'ano_emis',
			detcu.dato11 AS 'dias_plazo',
			detcu.fecha_vencimiento AS 'fecha_vcto',
			detcu.dato12 AS 'm_vcto',
			detcu.dato13 AS 'ano_vcto',
			detcu.dias_mora AS 'dias_transc_vcto_of',
			detcu.dato14 AS 'tipo_de_operacion',
			detcu.dato15 AS 'rango_vcto',
			detcu.dato16 AS 'linea_de_credito',
			detcu.dato17 AS 'ind_vcto',
			detcu.dato18 AS 'semaforo_de_vencimiento',
			detcu.moneda AS 'mon',
			detcu.total_deuda AS 'importe_original',
			detcu.saldo_capital AS 'saldo',
			detcu.saldo_capital_soles AS 'soles',
			detcu.saldo_capital_dolares AS 'dolares',
			detcu.dato19 AS 'total_convertido_a_dolares',
			detcu.dato20 AS 'total_convertido_a_soles',
			detcu.dato21 AS 'glosa',
			detcu.dato22 AS 'est_letr',
			detcu.dato23 AS 'banco',
			detcu.dato24 AS 'num_cobranza',
			detcu.dato25 AS 'referencia',			
			(SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS asignado FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=clicar.idcliente_cartera) AS 'ASIGNADO',				
			(SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=clicar.idusuario_servicio) AS 'CODIGO_USUARIO',
			DATE(cu.ml_fecha) AS 'ML_FECHA',
			( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO',
			( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel  WHERE fin.idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_PERFIL_CLIENTE',
			DATE( cu.ml_fcpg ) AS 'ML_FCPG', 
			cu.ml_observacion AS 'ML_OBSERVACION' ,
			cu.ml_operador AS 'ML_CODOPE', 
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_OPERADOR',
			DATE(cu.ul_fecha) AS 'UL_FECHA',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'UL_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'UL_ESTADO', 
			( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel  WHERE fin.idfinal = cu.ml_estado LIMIT 1 ) AS 'UL_PERFIL_CLIENTE',
			DATE( cu.ul_fcpg ) AS 'UL_FCPG', 
			cu.ul_observacion AS 'UL_OBSERVACION',
			cu.ul_operador AS 'UL_CODOPE', 
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_OPERADOR' ,
			DATE(cu.mv_fecha) AS 'MV_FECHA',
			( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'VL_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'MV_ESTADO', 
			DATE( cu.mv_fcpg ) AS 'MV_FCPG', 
			cu.mv_observacion AS 'MV_OBSERVACION',
			cu.mv_operador AS 'MV_CODOPE', 
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_notificador ) AS 'MV_OPERADOR' ,
			DATE(cu.uv_fecha) AS 'UV_FECHA',
			( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'UV_CARGA',
			( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'UV_ESTADO', 
			DATE( cu.uv_fcpg ) AS 'UV_FCPG', 
			cu.uv_observacion AS 'UV_OBSERVACION' ,
			cu.uv_operador AS 'UV_CODOPE', 
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_notificador ) AS 'UV_OPERADOR'
			FROM
			ca_detalle_cuenta detcu
			INNER JOIN ca_cuenta cu ON cu.idcuenta=detcu.idcuenta
			INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = cu.idcliente_cartera
			INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
			INNER JOIN ca_cartera car ON car.idcartera = clicar.idcartera
			WHERE
			cu.estado = 1 AND 
			cu.idcartera = $idCartera
			ORDER BY cli.codigo
	";

	// echo $sql;
	// exit();

	$prData = $connection->prepare($sql);
    $prData->execute();
    $i = 0;

	// echo '<table border=1>';
	// while( $row = $prData->fetch(PDO::FETCH_ASSOC) ) {
	// if( $i == 0 ) {
	//   echo '<tr>';
	//   foreach( $row as $index => $value ) {

	// 	if($index=='DIRECCION' OR $index=='TITULAR'){			  	
	// 		echo '<td width=600 style="background-color:#000080;color:white;border-color:white;" align="center" >'.$index.'</td>';
	// 	}else{
	// 		echo '<td style="background-color:#000080;color:white;border-color:white;" align="center" >'.$index.'</td>';
	// 	}

	//   }
	//  echo '</tr>';
	// }

	// $style="";
	// ( $i%2 == 0 )?$style="background-color:#A8D9D8;border-color:white;":$style="background-color:#D5ECCA;border-color:white;";
	// echo '<tr>';
	// foreach( $row as $key => $value ){				

	// 	if($key == 'TITULAR' OR $key == 'ULT_ESTADO_LLAMADA_CLIENTE' OR $key == 'ULT_ESTADO_LLAMADA_CONTRATO' OR $key == 'ML_ESTADO_LLAMADA_CONTRATO' OR $key == 'GESTOR_COBRANZA' OR $key == 'DIRECCION' OR $key == 'IDGESTOR'){
	// 		echo '<td style="'.$style.'" align="left">'.str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value))))).'</td>';
	// 	}else{
	// 		echo '<td style="'.$style.'" align="center">'.str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value))))).'</td>';
	// 	}

	// }
	// echo '</tr>';

	// $i++;
	// }
	// echo '</table>';


	while( $row = $prData->fetch(PDO::FETCH_ASSOC) ) {
	if( $i == 0 ) {
	  foreach( $row as $index => $value ) {

		if($index=='DIRECCION' OR $index=='TITULAR'){			  	
			echo $index."\t";
		}else{
			echo $index."\t";
		}

	  }
		echo "\n";
	}

	$style="";
	( $i%2 == 0 )?$style="background-color:#A8D9D8;border-color:white;":$style="background-color:#D5ECCA;border-color:white;";
	foreach( $row as $key => $value ){				

		if($key == 'TITULAR' OR $key == 'ULT_ESTADO_LLAMADA_CLIENTE' OR $key == 'ULT_ESTADO_LLAMADA_CONTRATO' OR $key == 'ML_ESTADO_LLAMADA_CONTRATO' OR $key == 'GESTOR_COBRANZA' OR $key == 'DIRECCION' OR $key == 'IDGESTOR'){
			echo str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value)))))."\t";
		}else{
			echo str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value)))))."\t";
		}

	}
	echo "\n";

	$i++;
	}


?>
