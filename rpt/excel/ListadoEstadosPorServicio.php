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
	
    $servicio=$_REQUEST['Servicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT 
                    fin.idfinal as CODIGO,
                    carfin.nombre as CARGA,
                    fin.nombre as ESTADO,
                    'LLAMADA' AS LLAMADA,
                    finser.peso as PESO,
                    finser.prioridad as PRIORIDAD,
                    CONCAT('=\"',finser.codigo,'\"') as RESPUESTA
                    from ca_final fin 
                    inner join ca_final_servicio finser on finser.idfinal=fin.idfinal
                    inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                    where finser.idservicio=$servicio and fin.idclase_final=1 and finser.estado=1";


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