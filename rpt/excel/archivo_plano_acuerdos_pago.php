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
             LPAD(acu.numero_cuotas,3,'0') AS 'NUMERO_CUOTAS',
             RPAD(DATE_FORMAT(acu.fecha_creacion,'%d/%m/%Y'),10,' ') AS 'FECHA_ACUERDO',
             RPAD(acu.valor_acuerdo,15,' ') AS  'VALOR_ACUERDO',
             RPAD((select usu.dni from ca_usuario usu inner join ca_usuario_servicio ususer on ususer.idusuario=usu.idusuario WHERE acu.usuario_creacion=ususer.idusuario_servicio LIMIT 1 ),13,' ')  AS 'ASESOR' 
            from ca_cliente_cartera clicar 
            inner join ca_cliente cli on cli.idcliente=clicar.idcliente 
            inner join ca_acuerdo_pago acu on acu.idcliente_cartera= clicar.idcliente_Cartera
            where acu.estado = 1 AND clicar.idcartera IN (".$cartera.") ". $where_fecha;

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/acuerdos_de_pago_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['TIPO_DOCUMENTO'].$arrayDATA[$i]['DOCUMENTO'].
        $arrayDATA[$i]['NUMERO_PAGARE'].$arrayDATA[$i]['NUMERO_CUOTAS'].
        $arrayDATA[$i]['FECHA_ACUERDO'].$arrayDATA[$i]['VALOR_ACUERDO'].$arrayDATA[$i]['ASESOR'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'acuerdos_de_pago_'.$time));
?>
