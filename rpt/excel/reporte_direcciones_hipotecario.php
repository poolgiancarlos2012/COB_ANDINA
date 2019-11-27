<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=GESTION_DE_LLAMADAS.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT CONCAT('=\"',dir.codigo_cliente,'\"') as codigo_cliente,tipref.nombre as tipo,dir.direccion from ca_direccion dir
                    inner join ca_cliente_cartera clicar on dir.codigo_cliente=clicar.codigo_cliente
                    inner join ca_tipo_referencia tipref on tipref.idtipo_referencia=dir.idtipo_referencia
                    where clicar.idcartera in ($cartera) and dir.idtipo_referencia in (11,12)";


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



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>