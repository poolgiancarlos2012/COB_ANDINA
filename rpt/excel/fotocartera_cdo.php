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
	

    $sql_fotocartera= "SELECT his.Fproceso,his.agencia,his.territorio,his.producto,his.nom_subprod,CONCAT('=\"',his.contrato,'\"') AS contrato,
                CONCAT('=\"',his.codcent,'\"') AS 'codcent',his.Nombre,his.divisa,his.saldohoy,his.diavenc,his.provincia,his.distrito,
                his.tramo_dia,his.marca,his.oficina2,his.oficina,'1' AS 'statusCLIENTE','1' AS 'statusCUENTA',
                (Select tipusu.nombre FROM ca_usuario_servicio ususer INNER JOIN ca_tipo_usuario tipusu ON tipusu.idtipo_usuario=ususer.idtipo_usuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'idGestor',
                (SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'asignado'
                FROM ca_historial his
                INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=his.idcliente_cartera
                WHERE clicar.idcartera in ($cartera) and his.Fproceso=$fecha_proceso";

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