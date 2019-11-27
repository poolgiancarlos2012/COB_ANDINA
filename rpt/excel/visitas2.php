<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=GESTION_DE_VISITAS.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
    $idcartera = $_REQUEST['Cartera'];
    $servicio = $_REQUEST['Servicio'];
    $fechaInicio = $_REQUEST['FechaInicio'];
    $fechaFin = $_REQUEST['FechaFin'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

    $sql_llamada="SELECT car.nombre_cartera CLAVEVISITA
                FROM ca_cliente_cartera clicar
                INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
                INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta and cu.idcliente_cartera=vis.idcliente_cartera
                WHERE clicar.idcartera IN ($idcartera) AND car.idcartera IN ($idcartera) AND cu.idcartera IN ($idcartera) and vis.estado=1
                AND DATE(vis.fecha_visita) BETWEEN '$fechaInicio' AND '$fechaFin'";

    $pr_llamada=$connection->prepare($sql_llamada);
    $i=0;
    if($pr_llamada->execute()){
        while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
            if($i==0){
                foreach ($data_llamada as $key => $value) {
                        echo utf8_decode($key)."\t";
                }
                    echo "\n";
            }
            $i++;
            $cont=0;
            foreach ($data_llamada as $key => $value) {
                    echo utf8_decode($value)."\t";
            }
                    echo "\n";
        }        
    }    
?>