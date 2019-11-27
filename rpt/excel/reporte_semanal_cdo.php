<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=REPORTE_FOTOCARTERA.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];
    $fecha_proceso=$_REQUEST['fproceso'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_fotocartera= "select car.nombre_cartera as 'cluster',car.evento as 'gestion',car.segmento as 'zonal','HDEC' as 'agencia',car.fecha_inicio as 'inicio',car.fecha_fin as 'fin',cu.numero_cuenta as 'cuenta',cu.total_deuda as 'monto',cu.monto_pagado as 'pago', 
                        '' as 'cliente_pago',carfin.nombre as 'carga',tipfin.nombre as 'grupo',niv.nombre as 'respuesta',fin.nombre as 'respuesta_incidencia'
                        from ca_cuenta cu
                        inner join ca_cartera car on car.idcartera=cu.idcartera
                        left join ca_final fin on fin.idfinal=cu.ml_estado
                        left join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                        LEFT JOIN ca_tipo_final tipfin on tipfin.idtipo_final = fin.idtipo_final
                        LEFT JOIN ca_nivel niv on niv.idnivel=fin.idnivel
                        where car.estado=1 and car.fecha_fin>=CURDATE()";

    $pr_fotocartera=$connection->prepare($sql_fotocartera);
    $i=0;
    if($pr_fotocartera->execute()){
        while($data_fotocartera=$pr_fotocartera->fetch(PDO::FETCH_ASSOC)){
            if($i==0){
                foreach ($data_fotocartera as $key => $value) {
                        echo utf8_decode($key)."\t";
                }
                    echo "\n";
            }
            $i++;
            $cont=0;
            foreach ($data_fotocartera as $key => $value) {
                    echo utf8_decode($value)."\t";
            }
                    echo "\n";
        }        
    }    



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>