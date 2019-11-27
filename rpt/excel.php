<?php

	require_once('../phpincludes/excel/Workbook.php');
	require_once('../phpincludes/excel/Worksheet.php');	
	
	require_once '../conexion/config.php';
    require_once '../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../conexion/MYSQLConnectionPDO.php';

    require_once '../factory/DAOFactory.php';
    require_once '../factory/FactoryConnection.php';
	
	require_once '../dao/MYSQLClienteCarteraDAO.php';

	$sql=" SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) as 'nombre',cli.dni,cli.ruc
						FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
						ON cli.idcliente=clicar.idcliente AND clicar.idcartera=car.idcartera
						WHERE car.idcampania=1 AND clicar.idusuario=10 ";
	$daoClienteCartera=DAOFactory::getDAOClienteCartera();	
		
	$data=$daoClienteCartera->executeSelectString($sql);
	
	$workbook = new Workbook("-");
	$workbook->setName('Reporte de Clientes');
	
		$xls =& $workbook->add_worksheet('Clientes Asignados');
	
		$xls->write_string(1,1,'IDCLIENTE CARTERA');
		$xls->write_string(1,2,'CODIGO');
		$xls->write_string(1,3,'NOMBRE');
		$xls->write_string(1,4,'DNI');
		$xls->write_string(1,5,'RUC');
	
	for($i=0;$i<count($data);$i++){
		$xls->write_string($i+2,1,$data[$i]['idcliente_cartera']);
		$xls->write_string($i+2,2,$data[$i]['codigo']);
		$xls->write_string($i+2,3,$data[$i]['nombre']);
		$xls->write_string($i+2,4,$data[$i]['dni']);
		$xls->write_string($i+2,5,$data[$i]['ruc']);
	}
	
	$workbook->close();	

?>
