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

    
   
    $sql = "SELECT 
            RPAD(fin.idfinal,8,' ') AS 'idfinal', 
            RPAD(CONCAT(fin.nombre,' ',carfin.nombre,' '),100,' ') AS 'descripcion' 
            FROM  ca_final_servicio finser
            INNER JOIN ca_final fin ON fin.idfinal =finser.idfinal
            INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final
            WHERE finser.idservicio = ".$servicio." AND finser.estado=1 AND fin.idclase_final IN (1,2) ;";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/codigo_gestion_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['idfinal'].$arrayDATA[$i]['descripcion'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'codigo_gestion_'.$time));
?>
