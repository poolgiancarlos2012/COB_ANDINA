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
              LPAD( DATE_FORMAT(DATE(lla.fecha),'%Y%m%d') ,8,' '  ) AS ' FECHA_GESTION',-- FECHA LLAMADA
        LPAD( cu.numero_cuenta,10,' ') AS 'NUMERO_CUENTA',
                '3' AS 'SITUACION_CUENTA',
                LPAD('CALL',5,' ') AS 'TIPO_GESTION',
                LPAD( (select cod.idcodigo from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_codigo cod on cod.idcodigo=fincov.idcodigo where fin.idfinal=lla.idfinal  and cod.estado =1 and fincov.estado = 1 ),2,'0' ) AS 'RESULTADO_GESTION',
                -- (select cod.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_codigo cod on cod.idcodigo=fincov.idcodigo where fin.idfinal=lla.idfinal  and cod.estado =1 and fincov.estado = 1 ) AS 'RESULTADO_GESTION',
                LPAD( detcu.dato12,8,' ') AS 'CODIGO_ESTUDIO',
        LPAD( ( SELECT CONCAT(SUBSTR(usu.nombre,1,1),usu.paterno,SUBSTR(usu.materno,1,1)) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) ,12,' ') AS 'NOMBRE_GESTOR',
                LPAD( IFNULL(( SELECT dir.distrito FROM ca_direccion dir WHERE dir.idcartera = 25281 and dir.codigo_cliente = clicar.codigo_cliente  limit 1),''),30,' ') AS 'DISTRITO_CLIENTE' ,
                LPAD( (select con.idtipo_contacto from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_contacto con on con.idtipo_contacto=fincov.idtipo_contacto where fin.idfinal=lla.idfinal  and con.estado =1 and fincov.estado = 1 ) ,2,'0') AS 'CONTACTABILIDAD',
                -- (select con.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_contacto con on con.idtipo_contacto=fincov.idtipo_contacto where fin.idfinal=lla.idfinal  and con.estado =1 and fincov.estado = 1 ) AS 'CONTACTABILIDAD',
        LPAD( DATE_FORMAT(lla.fecha,'%H%i%s'),6,' ') AS 'HORA_GESTION',-- HORA LLAMADA,
                LPAD( IFNULL(TRUNCATE(lla.monto_cp,2),' '),10,'0')  AS 'MONTO_COMPROMISO',
                LPAD( IFNULL(DATE_FORMAT(DATE(lla.fecha_cp),'%Y%m%d'),' '),8,' ') AS 'FECHA_COMPROMISO',
                IF( (select tip.idtipo_pago from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_pago tip on tip.idtipo_pago=fincov.idtipo_pago where fin.idfinal=lla.idfinal  and tip.estado =1 and fincov.estado = 1 ) IS NULL,'  ',LPAD( (select tip.idtipo_pago from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_pago tip on tip.idtipo_pago=fincov.idtipo_pago where fin.idfinal=lla.idfinal  and tip.estado =1 and fincov.estado = 1 ),2,'0')) AS 'TIPO_PAGO'
                -- (select tip.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_pago tip on tip.idtipo_pago=fincov.idtipo_pago where fin.idfinal=lla.idfinal  and tip.estado =1 and fincov.estado = 1 ) AS 'TIPO_PAGO'
        FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_cuenta cu INNER JOIN ca_final fin INNER JOIN ca_detalle_cuenta detcu
        ON fin.idfinal = lla.idfinal AND cu.idcuenta = lla.idcuenta AND lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND clicar.idcartera=car.idcartera AND detcu.idcuenta=cu.idcuenta
        WHERE clicar.idcartera IN (".$cartera.") AND cu.idcartera IN ( ".$cartera." ) 
        AND cli.idservicio = ".$servicio."
        AND DATE(lla.fecha) BETWEEN '" . $fechaInicio . "' AND '" . $fechafin . "' 
        AND car.idcartera IN (".$cartera.")    GROUP BY lla.idcliente_cartera,lla.fecha;";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/'.$time.'-'.trim($arrayDATA[0]['CODIGO_ESTUDIO']).'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['FECHA_GESTION'].$arrayDATA[$i]['NUMERO_CUENTA'].$arrayDATA[$i]['SITUACION_CUENTA'].$arrayDATA[$i]['TIPO_GESTION'].$arrayDATA[$i]['RESULTADO_GESTION'].
        $arrayDATA[$i]['CODIGO_ESTUDIO'].$arrayDATA[$i]['NOMBRE_GESTOR'].$arrayDATA[$i]['DISTRITO_CLIENTE'].
        $arrayDATA[$i]['CONTACTABILIDAD'].$arrayDATA[$i]['HORA_GESTION'].$arrayDATA[$i]['MONTO_COMPROMISO'].$arrayDATA[$i]['FECHA_COMPROMISO'].$arrayDATA[$i]['TIPO_PAGO'];
        
        fwrite($file_download,$data."\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>$time.'-'.trim($arrayDATA[0]['CODIGO_ESTUDIO'])));
?>
