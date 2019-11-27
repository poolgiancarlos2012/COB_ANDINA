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
    $time = date("Ymd_Hms");


    $where_fecha="";
    if($fechaInicio != '' AND $fechafin != '' ){
        $where_fecha = " AND DATE(cor.fecha_creacion) BETWEEN '".$fechaInicio."' AND '".$fechafin."'";
    }

    $sql = "SELECT * FROM (
            SELECT cli.tipo_documento AS 'TIPO_DOCUMENTO', LPAD(cli.numero_documento,13,' ') AS 'DOCUMENTO', 
            LPAD(IFNULL(cor.correo,' '),100,' ') AS 'EMAIL'

            from ca_cliente_cartera clicar
                 INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                 INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                 INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                                    INNER JOIN ca_correo cor ON cor.idcliente = clicar.idcliente
            WHERE detcu.idcartera IN (".$cartera.") and cli.idservicio = ".$servicio."  ".$where_fecha.")A GROUP BY A.DOCUMENTO,A.EMAIL;";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/emails_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['TIPO_DOCUMENTO'].$arrayDATA[$i]['DOCUMENTO'].$arrayDATA[$i]['EMAIL'];
        
        fwrite($file_download,$data."\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'emails_'.$time));
?>
