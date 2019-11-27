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

    $sqlCarterasCovinoc = "SELECT car.idcartera AS 'idcartera'  from ca_campania cam
            inner join ca_cartera car on car.idcampania = cam.idcampania where idservicio = ".$servicio." and car.estado=1 and cam.estado=1";
    $prCarterasCovinoc = $connection->prepare($sqlCarterasCovinoc);
    $prCarterasCovinoc->execute();
    $arrayCarterasCovinoc = $prCarterasCovinoc->fetchAll(PDO::FETCH_ASSOC);
    $carterasCovinoc=array();

    for($i=0;$i<count($arrayCarterasCovinoc);$i++){
        array_push($carterasCovinoc, $arrayCarterasCovinoc[$i]['idcartera']);
    }   

   
    $sql = "SELECT RPAD(lla.idfinal,8,' ') AS 'idfinal',RPAD((SELECT CONCAT(fin.nombre,' ',carfin.nombre,' ') 
            FROM  
            ca_final fin  
            INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final  WHERE fin.idfinal =lla.idfinal),100,' ') AS 'descripcion' 
from ca_llamada lla 
inner join ca_cliente_cartera clicar 
on clicar.idcliente_cartera = lla.idcliente_cartera 
where clicar.idcartera 
IN 
(
    ".implode(',',$carterasCovinoc )."
) GROUP BY idfinal
UNION
SELECT 
RPAD(fin.idfinal,8,' ') AS 'idfinal', 
RPAD(CONCAT(fin.nombre,' ',carfin.nombre,' '),100,' ') AS 'descripcion'from  ca_final_servicio finser
inner join ca_final fin on fin.idfinal =finser.idfinal
inner join ca_carga_final carfin on carfin.idcarga_final = fin.idcarga_final
where finser.idservicio = ".$servicio." and finser.estado=1
UNION
SELECT  RPAD(vis.idfinal,8,' '),RPAD((SELECT CONCAT(fin.nombre,' ',carfin.nombre,' ') 
            FROM  
            ca_final fin  
            INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final  WHERE fin.idfinal =vis.idfinal),100,' ') AS 'descripcion' FROM ca_visita vis INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = vis.idcliente_cartera WHERE clicar.idcartera IN (
".implode(',',$carterasCovinoc )."
) ORDER BY idfinal ASC";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/codigo_gestion_historial'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['idfinal'].$arrayDATA[$i]['descripcion'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'codigo_gestion_historial'.$time));
?>
