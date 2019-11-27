<?php
	header('Content-Type: text/html; charset=UTF-8');
	header("Content-Disposition:atachment;filename=sig.txt");
	header("Content-Type: application/force-download");
	header("Content-Transfer-Encoding: binary");
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
	
	$campania=$_REQUEST['campania'];
	$carteras=$_REQUEST['carteras'];
	$idcarteras="'".$carteras."'";
	$fecha_inicio=$_REQUEST['fecha_inicio'];
	$fecha_fin=$_REQUEST['fecha_fin'];

	$sql = " SELECT RPAD(car.nombre_cartera,50,' ') AS 'GESTION',
		'  ' AS 'CODIGO_EVENTO',
		car.fecha_inicio AS 'FECHA_INICIO',
		car.fecha_fin AS 'FECHA_FIN',
		RPAD(car.negocio,4,' ') AS 'NEGOCIO',
		clicar.codigo_cliente AS 'ABONADO',
		RPAD('',10,' ') AS 'SERVICIO',
		RPAD(cu.numero_cuenta,10,' ') AS 'INSCRIPCION',
		RPAD(cu.inscripcion,10,' ') AS 'CUENTA',
		RPAD(cu.telefono,15,' ') AS 'TELEFONO',
		RPAD('HdeC',50,' ') AS 'AGENCIA',
		RPAD(dir.zona,3,' ') AS 'ZONA',
		DATE(lla.fecha) AS 'FECHA_GESTION',
		CONCAT(LPAD(HOUR(lla.fecha),2,0),LPAD(MINUTE(lla.fecha),2,0)) AS 'HORA_GESTION',
		CASE WHEN lla.tipo = 'LL' OR lla.tipo = 'IVR' THEN 'LL' ELSE '  ' END AS 'TIPO_GESTION',
		( SELECT codigo FROM ca_final_servicio WHERE idfinal = lla.idfinal AND idservicio = 1 ) AS 'CODIGO_RESPUESTA',
		( SELECT LPAD(usu.dni,8,0) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio ) AS 'DNI_GESTOR'
		FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_direccion dir INNER JOIN ca_llamada lla 
		ON lla.idcliente_cartera = clicar.idcliente_cartera AND dir.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera
		WHERE car.idcartera IN ( $carteras ) AND cu.idcartera IN ( $carteras ) AND dir.idcartera IN ( $carteras ) ";
	
	$pr = $connection->prepare($sql);
	$pr->execute()
	while( $data = $pr->fetch(PDO::FETCH_ASSOC) ){
		echo implode("|",$data);
			/*echo (rpad_php($data[$i][0],50," ","")."|");
			echo (rpad_php($data[$i][1],2," ","")."|");
			echo (rpad_php($data[$i][2],10," ","")."|");
			echo (rpad_php($data[$i][3],10," ","")."|");
			echo (rpad_php($data[$i][4],4," ","")."|");
			echo (rpad_php($data[$i][5],10," ","")."|");
			echo (rpad_php($data[$i][6],10," ","")."|");
			echo (rpad_php($data[$i][7],10," ","")."|");
			echo (rpad_php($data[$i][8],10," ","")."|");
			echo (rpad_php($data[$i][9],15," ","")."|");
			echo (rpad_php($data[$i][10],50," ","")."|");
			echo (rpad_php($data[$i][11],3," ","")."|");
			echo (rpad_php($data[$i][12],10," ","")."|");
			echo (rpad_php($data[$i][13],4," ","")."|");
			echo (rpad_php($data[$i][14],2," ","")."|");
			echo (rpad_php($data[$i][15],3," ","")."|");
			echo (rpad_php($data[$i][16],8," ","")."|");*/
			echo "\r\n";
	}
	
	$sql = " SELECT RPAD(car.nombre_cartera,50,' ') AS 'GESTION',
		'  ' AS 'CODIGO_EVENTO',
		car.fecha_inicio AS 'FECHA_INICIO',
		car.fecha_fin AS 'FECHA_FIN',
		RPAD(car.negocio,4,' ') AS 'NEGOCIO',
		clicar.codigo_cliente AS 'ABONADO',
		RPAD('',10,' ') AS 'SERVICIO',
		RPAD(cu.numero_cuenta,10,' ') AS 'INSCRIPCION',
		RPAD(cu.inscripcion,10,' ') AS 'CUENTA',
		RPAD(cu.telefono,15,' ') AS 'TELEFONO',
		RPAD('HdeC',50,' ') AS 'AGENCIA',
		RPAD(dir.zona,3,' ') AS 'ZONA',
		DATE(vis.fecha_visita) AS 'FECHA_GESTION',
		CONCAT(LPAD(HOUR(vis.fecha_visita),2,0),LPAD(MINUTE(vis.fecha_visita),2,0)) AS 'HORA_GESTION',
		CASE WHEN vis.tipo = 'VIS' THEN 'VT' ELSE '  ' END AS 'TIPO_GESTION',
		( SELECT codigo FROM ca_final_servicio WHERE idfinal = vis.idfinal AND idservicio = 1 ) AS 'CODIGO_RESPUESTA',
		( SELECT LPAD(usu.dni,8,0) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador ) AS 'DNI_GESTOR'
		FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_direccion dir INNER JOIN ca_visita vis
		ON vis.idcliente_cartera = clicar.idcliente_cartera AND dir.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera
		WHERE car.idcartera IN ( $carteras ) AND cu.idcartera IN ( $carteras ) AND dir.idcartera IN ( $carteras ) ";

	$pr = $connection->prepare($sql);
	$pr->execute()
	while( $data = $pr->fetch(PDO::FETCH_ASSOC) ){
		echo implode("|",$data)."\r\n";
	}
	
?>
