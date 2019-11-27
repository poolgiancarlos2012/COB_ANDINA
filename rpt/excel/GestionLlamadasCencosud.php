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
	$fecha_inicio = $_REQUEST['FechaInicio'];
	$fecha_fin = $_REQUEST['FechaFin'];
	$tipo_llamada = $_REQUEST['TipoLlamada'];
	
	$sql = "";
	if( $tipo_llamada == 'mejor' ) {
		
		$sql = " 
			SELECT
			t1.ID_GESTION,
			t1.GRUPO_DE_CIERRE,
			t1.SEGMENTO,
			t1.NUMERO_CUENTA,
			t1.CODIGO_CLIENTE,
			t1.NOMBRE_CLIENTE,
			t1.NUMERO_DOCUMENTO,
			t1.FECHA_LLAMADA,
			t1.HORA_LLAMADA,
			t1.RANGO_HORARIO,
			t1.ESTADO_LLAMADA,
			t1.TELEFONO,
			t1.FECHA_CP,
			t1.MONTO_CP,
			replace(replace(t1.OBSERVACION,'\t',' '),'\n','') as OBSERVACION,
			t1.PRIORIDAD_GESTION,
			t1.EECC,
			t1.TELEOPERADOR,
			t1.ESTADO_DIRECCION,
			t1.DIRECCION_NUEVO
			FROM
			(
			SELECT 
			lla.idcuenta ,
			lla.idllamada AS ID_GESTION,
			cu.dato1 AS GRUPO_DE_CIERRE,
			cu.dato2 AS SEGMENTO,
			cu.numero_cuenta AS NUMERO_CUENTA,
			clicar.codigo_cliente AS CODIGO_CLIENTE,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			cli.numero_documento AS NUMERO_DOCUMENTO,
			DATE(lla.fecha) AS FECHA_LLAMADA, TIME( lla.fecha ) AS HORA_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			( SELECT CONCAT_WS('__',nombre,fin.nombre, IF( nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ) ) FROM ca_carga_final WHERE idcarga_final = fin.idcarga_final ) AS ESTADO_LLAMADA,
			( SELECT CONCAT_WS('__',IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			fecha_cp AS FECHA_CP,
			monto_cp AS MONTO_CP,
			replace(replace(lla.observacion,'\t',''),'\n','') AS OBSERVACION,
			finser.peso AS PRIORIDAD_GESTION,
			lla.status_cuenta AS EECC,
			( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1  ) AS TELEOPERADOR,
			IFNULL( ( SELECT CONCAT_WS('_____',IFNULL(lldet.status,''), IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status != 'NUEVO' LIMIT 1  ),'') AS ESTADO_DIRECCION ,
			IFNULL( ( SELECT CONCAT_WS('_____', IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status = 'NUEVO' LIMIT 1  ),'') AS DIRECCION_NUEVO 
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal 
			INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal 
			WHERE cli.idservicio = ? AND finser.idservicio = ".$servicio." AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ? 
			ORDER BY lla.idcuenta , finser.peso DESC 
			) t1 GROUP BY t1.idcuenta ";
		
	}else if( $tipo_llamada == 'ultimo' ){
		
	$sql = " 
			SELECT
			t1.ID_GESTION,
			t1.GRUPO_DE_CIERRE,
			t1.SEGMENTO,
			t1.NUMERO_CUENTA,
			t1.CODIGO_CLIENTE,
			t1.NOMBRE_CLIENTE,
			t1.NUMERO_DOCUMENTO,
			t1.FECHA_LLAMADA,
			t1.HORA_LLAMADA,
			t1.RANGO_HORARIO,
			t1.ESTADO_LLAMADA,
			t1.TELEFONO,
			t1.FECHA_CP,
			t1.MONTO_CP,
			replace(replace(t1.OBSERVACION,'\t',' '),'\n','') as OBSERVACION,
			t1.PRIORIDAD_GESTION,
			t1.EECC,
			t1.TELEOPERADOR,
			t1.ESTADO_DIRECCION,
			t1.DIRECCION_NUEVO
			FROM
			(
			SELECT 
			lla.idcuenta ,
			lla.idllamada AS ID_GESTION,
			cu.dato1 AS GRUPO_DE_CIERRE,
			cu.segmento AS SEGMENTO,
			cu.numero_cuenta AS NUMERO_CUENTA,
			clicar.codigo_cliente AS CODIGO_CLIENTE,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			cli.numero_documento AS NUMERO_DOCUMENTO,
			DATE(lla.fecha) AS FECHA_LLAMADA, TIME( lla.fecha ) AS HORA_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			( SELECT CONCAT_WS('__',nombre,fin.nombre, IF( nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ) ) FROM ca_carga_final WHERE idcarga_final = fin.idcarga_final ) AS ESTADO_LLAMADA,
			( SELECT CONCAT_WS('__',IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			fecha_cp AS FECHA_CP,
			monto_cp AS MONTO_CP,
			replace(replace(lla.observacion,'\t',''),'\n','') AS OBSERVACION,
			finser.peso AS PRIORIDAD_GESTION,
			lla.status_cuenta AS EECC,
			( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1  ) AS TELEOPERADOR,
			IFNULL( ( SELECT CONCAT_WS('_____',IFNULL(lldet.status,''), IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status != 'NUEVO' LIMIT 1  ),'') AS ESTADO_DIRECCION ,
			IFNULL( ( SELECT CONCAT_WS('_____', IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status = 'NUEVO' LIMIT 1  ),'') AS DIRECCION_NUEVO 
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal 
			INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal 
			WHERE cli.idservicio = ? AND finser.idservicio = ".$servicio." AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ? 
			ORDER BY lla.fecha DESC 
			) t1 GROUP BY t1.idcuenta ";
		
	}else{
			
	$sql = " SELECT 
			lla.idllamada AS ID_GESTION,
			cu.dato1 AS GRUPO_DE_CIERRE,
			cu.dato2 AS SEGMENTO,
			cu.numero_cuenta AS NUMERO_CUENTA,
			clicar.codigo_cliente AS CODIGO_CLIENTE,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			cli.numero_documento AS NUMERO_DOCUMENTO,
			DATE(lla.fecha) AS FECHA_LLAMADA, TIME( lla.fecha ) AS HORA_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			( SELECT CONCAT_WS('__',carfin.nombre,fin.nombre, IF( carfin.nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ) ) FROM ca_final fin INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final WHERE fin.idfinal = lla.idfinal LIMIT 1 ) AS ESTADO_LLAMADA,
			( SELECT CONCAT_WS('__',IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			fecha_cp AS FECHA_CP,
			monto_cp AS MONTO_CP,
			replace(replace(lla.observacion,'\t',''),'\n','') AS OBSERVACION,
			( SELECT peso FROM ca_final_servicio WHERE idservicio = ".$servicio." AND idfinal = lla.idfinal LIMIT 1 ) AS PRIORIDAD_GESTION,
			lla.status_cuenta AS EECC,
			( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1  ) AS TELEOPERADOR,
			IFNULL( ( SELECT CONCAT_WS('_____',IFNULL(lldet.status,''), IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status != 'NUEVO' LIMIT 1  ),'') AS ESTADO_DIRECCION ,
			IFNULL( ( SELECT CONCAT_WS('_____', IFNULL( dir.direccion,'' ),IFNULL(dir.distrito,''), IFNULL(dir.provincia,''),IFNULL(dir.departamento,'') ) FROM ca_ll_det_direccion_est lldet INNER JOIN ca_direccion dir ON dir.iddireccion = lldet.iddireccion  
			WHERE lldet.idllamada = lla.idllamada AND lldet.status = 'NUEVO' LIMIT 1  ),'') AS DIRECCION_NUEVO 
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			WHERE cli.idservicio = ? AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ? 
			GROUP BY lla.idllamada ";
	
	}
	
	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
	$pr->execute();
	$i = 0;
					 
	$header = array( "ID_GESTION","GRUPO_CIERRE","SEGMENTO","NUMERO_CUENTA", "CODIGO_CLIENTE","NOMBRE_CLIENTE","NUMERO_DOCUMENTO",
					"FECHA_LLAMADA", "HORA_LLAMADA","RANGO_HORARIO","ESTADO","RESULTADO GESTION","SITUACION", 
					"TIPO_TELEFONO", "TELEFONO", "FECHA_CP", "MONTO_CP", "OBSERVACION", "PRIORIDAD_GESTION","EECC",
					"TELEOPERADOR", "ESTADO_DIRECCION", 
					"DIRECCION_SIS", "DISTRITO_SIS", "PROVINCIA_SIS", "DEPARTAMENTO_SIS",
					"DIRECCION_NUEVO", "DISTRITO_NUEVO", "PROVINCIA_NUEVO", "DEPARTAMENTO_NUEVO" );
	//echo '<table>';
	//	echo '<tr>';
			for( $j=0;$j<count($header);$j++ ) {
				echo($header[$j]."\t");
			}
		echo("\n");
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
		
		//$style="";
		//( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		//echo '<tr>';
		foreach( $row as $key => $value )
		{	if( $key == 'ESTADO_DIRECCION' ) {
				
				if( $value == '' ) {
					echo("\t");
					echo("\t");
					echo("\t");
					echo("\t");
					echo("\t");
				}else{
					$dir_d = explode("_____",$value);
					if( count($dir_d) == 0 ){ $dir_d = array("","","","",""); }
					for( $k=0;$k<count($dir_d);$k++ ) {
						echo(strtoupper(utf8_decode($dir_d[$k]))."\t");
					}
				}
				
			}else if( $key == 'DIRECCION_NUEVO' ){
				
				if( $value == '' ) {
					echo("\t");
					echo("\t");
					echo("\t");
					echo("\t");
				}else{
					$dir_d = explode("_____",$value);
					if( count($dir_d) == 0 ){ $dir_d = array("","","",""); }
					for( $k=0;$k<count($dir_d);$k++ ) {
						echo(strtoupper(utf8_decode($dir_d[$k]))."\t");
					}
				}
				
			}else if( $key == 'ESTADO_LLAMADA' ){
				
				$est_ll = explode("__",$value);
				
				for( $k=0;$k<count($est_ll);$k++ ) {
					echo(strtoupper(utf8_decode($est_ll[$k]))."\t");
				}
				
			}else if( $key == 'TELEFONO' ){
				
				$est_ll = explode("__",$value);

				for( $k=0;$k<count($est_ll);$k++ ) {
					echo("=\"".strtoupper(utf8_decode($est_ll[$k]))."\"\t");
				}
				
			}else if( $key == 'NUMERO_CUENTA' || $key == 'CODIGO_CLIENTE' || $key == 'NUMERO_DOCUMENTO' || $key == 'SEGMENTO' ){
				echo("=\"".strtoupper(utf8_decode($value))."\"\t");
			}else{
				echo(strtoupper(utf8_decode($value))."\t");
			}
		}
		echo("\n");

		$i++;
		
	}
	//echo '</table>';
	

?>