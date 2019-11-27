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
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];
    $tipocambio=$_REQUEST['tipocambio'];
    $tipovac=$_REQUEST['tipovac'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT cu.dato9 as 'Territorio',car.nombre_cartera as 'CARTERA',CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODIGO_CLIENTE',
                (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente) AS 'CLIENTE',
                CONCAT('=\"',(SELECT numero_documento FROM ca_cliente where idcliente=clicar.idcliente),'\"') AS 'NUMERO_DOCUMENTO',
                DATE(fecha) AS 'FECHA_LLAMADA',TIME(fecha) AS 'HORA_LLAMADA',
                CONCAT('=\"',(SELECT numero FROM ca_telefono where idtelefono=lla.idtelefono),'\"') AS 'TELEFONO',
                carfin.nombre as 'TIPO_CONTACTO',fin.nombre AS 'ESTADO_LLAMADA',
                (SELECT prioridad FROM ca_final_servicio where idfinal=lla.idfinal and estado=1) AS 'Prioridad',
                lla.fecha_cp AS 'FECHA_CP',IF(cu.moneda='USD',$tipocambio*lla.monto_cp,IF(cu.moneda='VAC',$tipovac*lla.monto_cp,lla.monto_cp)) AS 'MONTO_CP',replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION',
                (SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'ASIGNADO',
                IF(lla.idusuario_servicio=1,'OP8',(SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM  ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE lla.idusuario_servicio=ususer.idusuario_servicio)) AS 'GESTIONADO_POR_OPERADOR',
                motpag.nombre AS 'MOTIVO_NO_PAGO',IF(lla.idusuario_servicio=1,'OP8','LL') AS 'MARCA'
                FROM ca_llamada lla
                INNER JOIN ca_cliente_cartera clicar ON lla.idcliente_cartera = clicar.idcliente_cartera
                INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera AND lla.idcuenta = cu.idcuenta
                INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
                INNER JOIN ca_final fin ON fin.idfinal=lla.idfinal
                INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final
                LEFT OUTER JOIN ca_motivo_no_pago motpag ON motpag.idmotivo_no_pago=lla.idmotivo_no_pago
                WHERE clicar.idcartera in ($cartera) AND cu.idcartera in ($cartera) AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion";


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