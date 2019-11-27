<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=gestion_diaria.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	$servicio = $_GET['servicio'];
	$cartera = $_GET['cartera'];
	
	date_default_timezone_set('America/Lima');
	
	$sql = " SELECT usu.idusuario,ususer.idusuario_servicio,UPPER(CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno)) AS OPERADOR,
		  IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'CLIENTES_SIN_GESTIONAR',
		  IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'CLIENTES_GESTIONADOS',
		  ( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'CLIENTE_ASIGNADOS'
		  FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
		  WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ";
		  
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$pr=$connection->prepare($sql);
	
	$pr->bindParam(1,$cartera);
	$pr->bindParam(2,$cartera);
	$pr->bindParam(3,$cartera);
	$pr->bindParam(4,$servicio);
	$pr->execute();
	
	echo '<table>';
		echo '<tr>';
			echo '<td align="center" style="background-color:blue;color:white;" >OPERADOR</td>';
			echo '<td align="center" style="background-color:blue;color:white;">CLIENTES ASIGNADOS</td>';
			echo '<td align="center" style="background-color:blue;color:white;">CLIENTES GESTIONADOS</td>';
			echo '<td align="center" style="background-color:blue;color:white;">CLIENTES SIN GESTIONAR</td>';
		echo '</tr>';
		
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		echo '<tr>';
			echo '<td align="center">'.$row['OPERADOR'].'</td>';
			echo '<td align="center">'.$row['CLIENTE_ASIGNADOS'].'</td>';
			echo '<td align="center">'.$row['CLIENTES_GESTIONADOS'].'</td>';
			echo '<td align="center">'.$row['CLIENTES_SIN_GESTIONAR'].'</td>';
		echo '</tr>';
	}
	echo '</table>';
				
	
?>