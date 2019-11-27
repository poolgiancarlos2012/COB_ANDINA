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

    $where_fecha="";
    if($fechaInicio != '' AND $fechafin != '' ){
        $where_fecha = " AND DATE(tmp.fecha_creacion) BETWEEN '".$fechaInicio."' AND '".$fechafin."'";
    }
    $time = date("Ymd_Hms");

    $sqlTMP = "CREATE TEMPORARY TABLE tmp_dir_".$time." (SELECT * FROM (
            SELECT iddireccion, direccion, ubigeo, departamento, provincia, distrito, referencia, status, idcartera, codigo_cliente, fecha_creacion, if(idtipo_referencia=2,'R',if(idtipo_referencia=5,'O',''))AS tipo_direccion   from ca_direccion where estado = 1 and is_new = 1 and idcartera IN 
            (".$cartera.")
            )a GROUP BY a.direccion,a.codigo_cliente)";
    $prTMP = $connection->prepare($sqlTMP);
    $prTMP->execute();

    $sqlAddIndex = "ALTER TABLE tmp_dir_".$time." ADD INDEX(iddireccion)";
    $prAddIndex = $connection->prepare($sqlAddIndex);
    $prAddIndex->execute();

    $sqlAddIndex2 = "ALTER TABLE tmp_dir_".$time." ADD UNIQUE INDEX(direccion,codigo_cliente)";
    $prAddIndex2 = $connection->prepare($sqlAddIndex2);
    $prAddIndex2->execute();


    $sql = "SELECT * FROM ( 
SELECT  cli.tipo_documento AS 'TIPO_DOCUMENTO', LPAD(cli.numero_documento,13,' ') AS 'DOCUMENTO',
                LPAD(IFNULL(tmp.direccion,''),100,' ') AS 'DIRECCION', 
                LPAD(IFNULL(IF(tmp.provincia='01','LIMA',tmp.provincia),''),30,' ') AS 'CIUDAD',
                LPAD(IFNULL(tmp.tipo_direccion,' '),1,' ') AS 'TIPO_DIRECCION'
            from ca_cliente_cartera clicar
                 INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                 INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                 INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                                    left join tmp_dir_".$time." tmp ON tmp.codigo_cliente = detcu.codigo_cliente
            where detcu.idcartera IN (".$cartera.") and cli.idservicio = ".$servicio." ".$where_fecha."   )A WHERE A.direccion != LPAD('',100,' ') GROUP BY A.DOCUMENTO,A.DIRECCION;";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/direcciones_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['TIPO_DOCUMENTO'].$arrayDATA[$i]['DOCUMENTO'].$arrayDATA[$i]['DIRECCION'].$arrayDATA[$i]['CIUDAD'].$arrayDATA[$i]['TIPO_DIRECCION'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'direcciones_'.$time));
?>
