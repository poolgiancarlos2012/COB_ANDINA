<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=FACTURACION.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "select car.NOMBRE_CARTERA,CONCAT('=\"',CONTRATO,'\"') as CONTRATO,CONCAT('=\"',CODCENT,'\"') as CODCENT,OFICINA,NOMB_OF,TERRITORIO,NOMBRE,AGENCIA,SUBPROD,NOMB_PROD,TIPDOC,CONCAT('=\"',NRODOC,'\"') as NRODOC,TPERSONA,TRAMO,MARCA_PAGO,AGENCIA2,AGENCIA3,TCONTACTO3,TCON3,IMP_PAG3,fac.COMISION,HONORARIO,IGV,TOTAL_PAGO,OF_FACTURA from ca_factura fac
                    inner join ca_cuenta cu on cu.idcuenta=fac.idcuenta
                    inner join ca_cartera car on cu.idcartera=car.idcartera
                    where cu.idcartera in ($cartera)";


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