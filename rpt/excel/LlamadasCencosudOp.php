<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=REPORTE_LLAMADAS.xls");
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
			t1.GRUPO_DE_CIERRE,
			t1.ID_O_DNI,
			t1.NUMERO_CUENTA,
			t1.NOMBRE_CLIENTE,
			t1.TELEFONO,
			t1.FECHA_LLAMADA,
			t1.ESTADO_LLAMADA,
			replace(replace(t1.OBSERVACION_LLAMADA,'\t',' '),'\n',' ') as OBSERVACION_LLAMADA,
			t1.RANGO_HORARIO,
			t1.PRIORIDAD_GESTION
			FROM
			(
			SELECT 
			lla.idcuenta,
			cu.dato1 AS GRUPO_DE_CIERRE,
			cli.numero_documento AS ID_O_DNI,
			cu.numero_cuenta AS NUMERO_CUENTA,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			( SELECT CONCAT_WS('__',IF( referencia IS NULL ,'CLIENTE','REFERENCIA' ),IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			DATE(lla.fecha) AS FECHA_LLAMADA, 
			CONCAT_WS('__',fin.nombre,carfin.nombre, IF( carfin.nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ),IF( carfin.nombre IN ( 'NOC','CNE' ),'NO VALIDADO','VALIDADO' ) ) AS ESTADO_LLAMADA,
			replace(replace(lla.observacion,'\t',' '),'\n',' ') AS OBSERVACION_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			finser.peso AS PRIORIDAD_GESTION
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal 
			INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final 
			INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal 
			WHERE cli.idservicio = ? AND finser.idservicio = ".$servicio." AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ?  AND lla.estado = 1
			ORDER BY lla.idcuenta , finser.peso DESC 
			) t1 GROUP BY t1.idcuenta ";

	}else if( $tipo_llamada == 'ultimo' ){
	
	$sql = " 
			SELECT
			t1.GRUPO_DE_CIERRE,
			t1.ID_O_DNI,
			t1.NUMERO_CUENTA,
			t1.NOMBRE_CLIENTE,
			t1.TELEFONO,
			t1.FECHA_LLAMADA,
			t1.ESTADO_LLAMADA,
			replace(replace(t1.OBSERVACION_LLAMADA,'\t',' '),'\n',' ') as OBSERVACION_LLAMADA,
			t1.RANGO_HORARIO,
			t1.PRIORIDAD_GESTION
			FROM
			(
			SELECT 
			lla.idcuenta,
			cu.dato1 AS GRUPO_DE_CIERRE,
			cli.numero_documento AS ID_O_DNI,
			cu.numero_cuenta AS NUMERO_CUENTA,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			( SELECT CONCAT_WS('__',IF( referencia IS NULL ,'CLIENTE','REFERENCIA' ),IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			DATE(lla.fecha) AS FECHA_LLAMADA, 
			CONCAT_WS('__',fin.nombre,carfin.nombre, IF( carfin.nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ),IF( carfin.nombre IN ( 'NOC','CNE' ),'NO VALIDADO','VALIDADO' ) ) AS ESTADO_LLAMADA,
			replace(replace(lla.observacion,'\t',' '),'\n',' ') AS OBSERVACION_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			finser.peso AS PRIORIDAD_GESTION
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal 
			INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final 
			INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal 
			WHERE cli.idservicio = ? AND finser.idservicio = ".$servicio." AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ?  AND lla.estado = 1
			ORDER BY lla.fecha DESC 
			) t1 GROUP BY t1.idcuenta ";

	}else{

	$sql = " SELECT 
			cu.dato1 AS GRUPO_DE_CIERRE,
			cli.numero_documento AS ID_O_DNI,
			cu.numero_cuenta AS NUMERO_CUENTA,
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS NOMBRE_CLIENTE,
			( SELECT CONCAT_WS('__', IF( referencia IS NULL ,'CLIENTE','REFERENCIA' ) ,IF( SUBSTRING(numero,1,1)='9' , 'CELULAR','FIJO' ),numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS TELEFONO,
			DATE(lla.fecha) AS FECHA_LLAMADA, 
			( SELECT CONCAT_WS('__',fin.nombre,carfin.nombre, IF( carfin.nombre IN ( 'NOC','CNE' ),'NO CONTACTO','CONTACTO' ),IF( carfin.nombre IN ( 'NOC','CNE' ),'NO VALIDADO','VALIDADO' ) ) FROM ca_final fin INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final WHERE fin.idfinal = lla.idfinal LIMIT 1 ) AS ESTADO_LLAMADA,
			replace(replace(lla.observacion,'\t',' '),'\n',' ') AS OBSERVACION_LLAMADA,
			HOUR(lla.fecha) AS RANGO_HORARIO,
			( SELECT peso FROM ca_final_servicio WHERE idservicio = ".$servicio." AND idfinal = lla.idfinal LIMIT 1 ) AS PRIORIDAD_GESTION
			FROM ca_cliente cli
			INNER JOIN ca_cliente_cartera clicar On clicar.idcliente = cli.idcliente 
			INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta 
			WHERE cli.idservicio = ? AND clicar.idcartera IN ( ".$idcartera." ) AND cu.idcartera IN ( ".$idcartera." ) 
			AND lla.tipo = 'LL' AND DATE(lla.fecha) BETWEEN ? AND ?  AND lla.estado = 1
			GROUP BY lla.idllamada ";

	}

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
	$pr->execute();
	$i = 0;

	$header = array( "GRUPO DE CIERRE","ID O DNI", "CUENTA", "NOMBRE CLIENTE", "GESTION", "TIPO DE TELEFONO", 
					"NRO. TELEFONICO", "FECHA DE LLAMADA", "RESULTADO DE GESTION", "ESTADO", "SITUACION",
					"CALIF TELEFONICA", "OBSERVACION LLAMADA", "RANGO", "PRIORIDAD" );
	//echo '<table>';
		//echo '<tr>';
			for( $j=0;$j<count($header);$j++ ) {
				echo($header[$j]."\t");
			}
		echo("\n");
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

		//$style="";
		//( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		//echo '<tr>';
		foreach( $row as $key => $value )
		{	
			if( $key == 'ESTADO_LLAMADA' ){

				$est_ll = explode("__",$value);

				for( $k=0;$k<count($est_ll);$k++ ) {
					echo(strtoupper(utf8_decode($est_ll[$k]))."\t");
				}

			}else if( $key == 'TELEFONO' ){

				$est_ll = explode("__",$value);

				for( $k=0;$k<count($est_ll);$k++ ) {
					echo "=\"".strtoupper(utf8_decode($est_ll[$k]))."\"\t";
				}

			}else if( $key == 'NUMERO_CUENTA' || $key == 'ID_O_DNI' ){
				echo(strtoupper(utf8_decode($value))."\t");
			}else{
				echo(strtoupper(utf8_decode($value))."\t");
			}
		}
		echo("\n");

		$i++;

	}
	//echo '</table>';


?>