<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=SIN_GESTION_POR_CARTERA.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODCENT',cli.nombre AS 'CLIENTE',
                    (SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio)  AS 'OPERADOR ASIGNADO'
                    FROM ca_cliente_cartera clicar
                    INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                    WHERE clicar.id_ultima_llamada=0 AND idcartera IN ($cartera) AND clicar.estado=1";


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