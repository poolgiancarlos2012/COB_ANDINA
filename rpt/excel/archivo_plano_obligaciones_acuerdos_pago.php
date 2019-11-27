<?php
  
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';

    date_default_timezone_set('America/Lima');
    
    $factoryConnection= FactoryConnection::create('mysql');	
    $connection = $factoryConnection->getConnection();
    
    
    
    $servicio = $_REQUEST['Servicio'];
    $cartera = $_REQUEST['Cartera'];
    $fechaInicio = $_REQUEST['fechaInicio'];
    $fechafin = $_REQUEST['fechaFin'];
    $time = date("Ymd");

    $where_fecha="";
    if($fechaInicio != '' AND $fechafin != '' ){
        $where_fecha = " AND DATE(acu.fecha_creacion) BETWEEN '".$fechaInicio."' AND '".$fechafin."'";
    }
   
    $sql = "SELECT 
            RPAD(IFNULL(cli.tipo_documento,''),1,' ') AS 'TIPO_DOCUMENTO',
             RPAD(cli.numero_documento,13,' ') AS 'DOCUMENTO' ,
             RPAD(acu.numero_pagare,13,' ') AS 'NUMERO_PAGARE',
             RPAD(detcu.dato2,9,' ') AS 'CONTRATO',
             RPAD(detcu.codigo_operacion,30,' ') AS  'OBLIGACION'
            from ca_cliente_cartera clicar
            inner join ca_cuenta cu on cu.idcliente_cartera = clicar.idcliente_Cartera
            inner join ca_detalle_cuenta detcu on detcu.idcuenta = cu.idcuenta
            inner join ca_cliente cli on cli.idcliente=clicar.idcliente 
            inner join ca_acuerdo_pago acu on acu.idcliente_cartera= clicar.idcliente_Cartera
            where acu.estado = 1 AND clicar.idcartera IN (".$cartera.") ". $where_fecha;

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/obligaciones_acuerdos_de_pago_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['TIPO_DOCUMENTO'].$arrayDATA[$i]['DOCUMENTO'].
        $arrayDATA[$i]['NUMERO_PAGARE'].$arrayDATA[$i]['CONTRATO'].
        $arrayDATA[$i]['OBLIGACION'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'obligaciones_acuerdos_de_pago_'.$time));
?>
