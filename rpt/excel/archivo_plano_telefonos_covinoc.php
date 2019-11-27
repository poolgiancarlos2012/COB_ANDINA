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
        $where_fecha = " AND DATE(tmp.fecha_creacion) BETWEEN '".$fechaInicio."' AND '".$fechafin."'";
    }


    $sqlTMP = "CREATE TEMPORARY TABLE telefonos_tmp_".$time." (SELECT t1.idtelefono,t1.codigo_cliente, IF(LENGTH(t1.numero)=8 AND SUBSTRING(t1.numero,1,1)!=9, CONCAT('0',t1.numero), t1.numero) AS numero, t1.anexo, t1.is_new, t1.is_campo,t1.is_carga, t1.referencia , t1.estado , t1.prefijos, t1.peso, t1.origen, t1.fecha_creacion, t1.tipo_telefono
                FROM
                (
                                        SELECT idtelefono, IFNULL(numero_act,numero) as numero,codigo_cliente, IF(IFNULL(anexo,'') REGEXP '^-?[0-9]+$',IFNULL(anexo,''),'') AS 'anexo', IFNULL(m_peso,0) AS peso ,
                                        is_new, is_campo,is_carga ,IFNULL(referencia,'') AS referencia ,
                                        IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tmp.idfinal ),'') AS estado ,
                                        IFNULL(( SELECT CONCAT_WS(':',nombre,CONCAT_WS('-',lb_prefijo,lb_prefijo2,lb_prefijo3)) FROM ca_linea_telefono WHERE idlinea_telefono = tmp.idlinea_telefono ),':') AS prefijos,
                                        org.nombre AS 'origen',
                                        tmp.fecha_creacion AS 'fecha_creacion',
                                        IF(tmp.idtipo_telefono=1,'C',IF(tmp.idtipo_telefono=2,'T','') ) AS 'tipo_telefono'
         
                                        FROM ca_telefono tmp 
                                        INNER JOIN ca_origen org ON org.idorigen = tmp.idorigen
                                        WHERE idcartera IN (".$cartera.") AND estado = 1  AND is_active = 1  AND CAST(IFNULL(numero_act,numero) AS SIGNED)!=0  AND is_new = 1 ".$where_fecha."
                                        ORDER BY peso DESC
                                        ) t1 
                        WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^[2-8].......$' OR t1.numero REGEXP '^[2-8]......$' )) 
                        GROUP BY t1.numero,t1.codigo_cliente ORDER BY peso
                                );
                ";
    $prTMP = $connection->prepare($sqlTMP);
    $prTMP->execute();

    $sqlAddIndex = "ALTER TABLE telefonos_tmp_".$time." ADD UNIQUE INDEX(codigo_cliente, numero);";
    $prAddIndex =$connection->prepare($sqlAddIndex);
    $prAddIndex->execute();
    $sqlAddIndex2 = "ALTER TABLE telefonos_tmp_".$time." ADD INDEX(idtelefono);";
    $prAddIndex2 =$connection->prepare($sqlAddIndex2);
    $prAddIndex2->execute();
    
   
    $sql = "SELECT * FROM ( SELECT LPAD(IFNULL(cli.tipo_documento,' '),1,'') AS 'TIPO_DOCUMENTO', LPAD(IFNULL(cli.numero_documento,''),13,' ') AS 'DOCUMENTO', 
            LPAD(IFNULL(tmp.numero,''),11,'0')AS 'TELEFONO',
            LPAD(IFNULL(tmp.anexo,''),5,'0')AS 'EXTENSION',
            LPAD(IFNULL(tmp.tipo_telefono,''),1,' ') AS 'TIPO_TELEFONO',
            LPAD(IFNULL((select provincia from ca_direccion where idcliente_cartera=clicar.idcliente_cartera and estado = 1 LIMIT 1),''),30,' ')AS 'CIUDAD',
            LPAD(IFNULL((select departamento from ca_direccion where idcliente_cartera=clicar.idcliente_cartera and estado = 1 LIMIT 1),''),20,' ')AS 'DEPARTAMENTO'
            from ca_cliente_cartera clicar
                             INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                             INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                             INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                             LEFT JOIN telefonos_tmp_".$time." tmp ON tmp.codigo_cliente= clicar.codigo_cliente

            where detcu.idcartera in(".$cartera.")  and cli.idservicio = ".$servicio." ".$where_fecha." )a  WHERE a.TELEFONO != ''  AND a.TELEFONO != '00000000000' AND a.TELEFONO not like '%-%' GROUP BY a.DOCUMENTO,a.TELEFONO";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/telefonos_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['TIPO_DOCUMENTO'].$arrayDATA[$i]['DOCUMENTO'].$arrayDATA[$i]['TELEFONO'].$arrayDATA[$i]['EXTENSION'].$arrayDATA[$i]['TIPO_TELEFONO'].
        $arrayDATA[$i]['CIUDAD'].$arrayDATA[$i]['DEPARTAMENTO'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'telefonos_'.$time));
?>
