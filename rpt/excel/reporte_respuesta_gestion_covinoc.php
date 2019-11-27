<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/plain; charset=UTF-8');
    header("Content-Disposition:atachment;filename=RESPUESTA_GESTION.txt");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
    $servicio = $_REQUEST['Servicio'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT 
                    RPAD('HDC',20,' ') AS empresa,
                    RPAD(DATE_FORMAT(lla.fecha,'%d/%m/%Y %T'),21,' ') as fecha,
                    RPAD(IF(cu.moneda='PEN',contrato_soles,contrato_dolares),9,' ') as contrato,
                    RPAD(cu.numero_cuenta,30,' ') as obligacion,
                    (select RPAD(IFNULL(tipo_documento,''),1,' ') from ca_cliente where idcliente=clicar.idcliente) as tipo_doc,
                    (select RPAD(IFNULL(numero_documento,''),13,' ') from ca_cliente where idcliente=clicar.idcliente) as numero_documento,
                    (select RPAD(IFNULL(codigo,''),8,' ') from ca_final_servicio where idfinal=fin.idfinal and estado=1 and idservicio=$servicio) as codigo_gestion,
                    (select RPAD(IFNULL(direccion,''),100,' ') from ca_direccion where idcliente_cartera =clicar.idcliente_cartera limit 1) as ubicacion,
                    RPAD(replace(replace(replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),'Ñ','N'),'ñ','n'),'°',''),500,' ') as detalle,
                    RPAD('',10,' ') as fecha_pago,
                    RPAD('',15,' ') as valor_pago,
                    RPAD(IFNULL(lla.fecha_cp,''),10,' ') as fecha_compromiso,
                    RPAD(IFNULL(lla.monto_cp,''),15,' ') as monto_compromiso,
                    RPAD('',15,' ') as modelo_correo,
                    RPAD('',15,' ') as razon_pago,
                    RPAD(usu.dni,13,' ') as documento_usuario
                    from ca_llamada lla
                    inner join ca_cliente_cartera clicar on lla.idcliente_cartera=clicar.idcliente_cartera
                    inner join ca_cuenta cu on cu.idcuenta=lla.idcuenta
                    inner join ca_final fin on fin.idfinal=lla.idfinal
                    inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                    inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                    inner join ca_cartera car on car.idcartera=clicar.idcartera
                    where clicar.idcartera in ($cartera) and date(lla.fecha) between '$fecha_inicio' and '$fecha_fin'";


    $pr_llamada=$connection->prepare($sql_llamada);
    $i=0;
    if($pr_llamada->execute()){
        while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){

            $i++;
            $cont=0;
            foreach ($data_llamada as $key => $value) {
                    echo utf8_decode($value);
            }
                    echo "\n";
        }        
    }    



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>