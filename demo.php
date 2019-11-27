<?php
	
	/*$cn = memcache_connect("localhost");
	memcache_set($cn,"kennedy",array('chagua','Encarnacion','123456'));
	$data = memcache_get($cn,"kennedy"); 
	echo $data[1];
	memcache_close($cn);*/
	date_default_timezone_set('America/Lima');
	echo date('l, d ').'de'.date(' F ').'de'.date(' Y h:i:s A');
	exit();
	require_once 'conexion/config.php';
    require_once 'conexion/MYSQLConnectionMYSQLI.php';
    require_once 'conexion/MYSQLConnectionPDO.php';

    require_once 'factory/DAOFactory.php';
    require_once 'factory/FactoryConnection.php';
	
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$sql = " SELECT aler.idalerta, aler.descripcion, aler.fecha_alerta, aler.fecha_creacion,
		clicar.idusuario_servicio, 
		( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'usuario',
		clicar.idcliente_cartera,
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'cliente',
		cli.numero_documento ,
		cli.tipo_documento ,
		aler.estado ,
		1 AS 'servicio'
		FROM ca_alerta aler INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli
		ON cli.codigo = clicar.codigo_cliente AND clicar.idcliente_cartera = aler.idcliente_cartera ";
	
	$pr = $connection->prepare( $sql );
	$pr->execute();
	
	$cnSqlite = new SQLiteDatabase("db/cobrast.sqlite");
	$cnSqlite->queryExec(" DELETE FROM ca_alerta ");
	while( $rowSQL = $pr->fetch(PDO::FETCH_ASSOC) ){
		
		$sqlite = " INSERT INTO ca_alerta( idalerta, descripcion, fecha_alerta, fecha_creacion, idusuario_servicio, 
			  nombre_usuario_servicio, idcliente_cartera, nombre_cliente, numero_documento_cliente, tipo_documento_cliente, estado , idservicio ) 
			  VALUES( ".$rowSQL['idalerta'].", '".$rowSQL['descripcion']."', '".$rowSQL['fecha_alerta']."', '".$rowSQL['fecha_creacion']."' , ".$rowSQL['idusuario_servicio'].",
			  '".$rowSQL['usuario']."', ".$rowSQL['idcliente_cartera'].", '".$rowSQL['cliente']."', '".$rowSQL['numero_documento']."', 
			  '".$rowSQL['tipo_documento']."' , ".$rowSQL['estado'].", ".$rowSQL['servicio']." ) ";
			  
		$cnSqlite->queryExec($sqlite);
		
	}
	
?>