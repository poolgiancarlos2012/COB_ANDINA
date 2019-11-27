<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=gestion_diaria_saga.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
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
    $fechaInicio = $_REQUEST['FechaInicio'];
    $fechafin = $_REQUEST['FechaFin'];
    $time = date("Ymd");

    
   
    $sql = "SELECT 
               DATE(lla.fecha) AS ' FECHA_GESTION',-- FECHA LLAMADA
         cu.numero_cuenta AS 'NUMERO_CUENTA',
                '3' AS 'SITUACION_CUENTA',
                'CALL' AS 'TIPO_GESTION',
                (select cod.idcodigo from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_codigo cod on cod.idcodigo=fincov.idcodigo where fin.idfinal=lla.idfinal  and cod.estado =1 and fincov.estado = 1 ) AS 'RESULTADO_GESTION',
                (select cod.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_codigo cod on cod.idcodigo=fincov.idcodigo where fin.idfinal=lla.idfinal  and cod.estado =1 and fincov.estado = 1 ) AS 'DATO_RESULTADO_GESTION',
                detcu.dato12 AS 'CODIGO_ESTUDIO',
       ( SELECT CONCAT(SUBSTR(usu.nombre,1,1),usu.paterno,SUBSTR(usu.materno,1,1)) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'NOMBRE_GESTOR',
                ( SELECT dir.distrito FROM ca_direccion dir WHERE dir.idcartera = ".$cartera." and dir.codigo_cliente = clicar.codigo_cliente  limit 1) AS 'DISTRITO_CLIENTE' ,
                 (select con.idtipo_contacto from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_contacto con on con.idtipo_contacto=fincov.idtipo_contacto where fin.idfinal=lla.idfinal  and con.estado =1 and fincov.estado = 1 )  AS 'CONTACTABILIDAD',
                (select con.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_contacto con on con.idtipo_contacto=fincov.idtipo_contacto where fin.idfinal=lla.idfinal  and con.estado =1 and fincov.estado = 1 ) AS 'DATO_CONTACTABILIDAD',
         lla.fecha AS 'HORA_GESTION',-- HORA LLAMADA,
                 TRUNCATE(lla.monto_cp,2)  AS 'MONTO_COMPROMISO',
                 DATE(lla.fecha_cp) AS 'FECHA_COMPROMISO',
                 (select tip.idtipo_pago from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_pago tip on tip.idtipo_pago=fincov.idtipo_pago where fin.idfinal=lla.idfinal  and tip.estado =1 and fincov.estado = 1 ) AS 'TIPO_PAGO',
                (select tip.nombre from ca_final fin INNER JOIN ca_final_covinoc fincov on fincov.idfinal_covinoc=fin.idfinal_covinoc inner join ca_tipo_pago tip on tip.idtipo_pago=fincov.idtipo_pago where fin.idfinal=lla.idfinal  and tip.estado =1 and fincov.estado = 1 ) AS 'DATO_TIPO_PAGO'
        FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_cuenta cu INNER JOIN ca_final fin INNER JOIN ca_detalle_cuenta detcu
        ON fin.idfinal = lla.idfinal AND cu.idcuenta = lla.idcuenta AND lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND clicar.idcartera=car.idcartera AND detcu.idcuenta=cu.idcuenta
        WHERE clicar.idcartera IN (".$cartera.") AND cu.idcartera IN ( ".$cartera." ) 
        AND cli.idservicio = ".$servicio."
        AND DATE(lla.fecha) BETWEEN '" . $fechaInicio . "' AND '" . $fechafin . "' 
        AND car.idcartera IN (".$cartera.")    GROUP BY lla.idcliente_cartera,lla.fecha;";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $html="<table>";
    $html .=   "<tr style='width:auto'>".
                        "<td>FECHA_GESTION</td>".
                        "<td>NUMERO_CUENTA</td>".
                        "<td>SITUACION_CUENTA</td>".
                        "<td>TIPO_GESTION</td>".
                        "<td>RESULTADO_GESTION</td>".  
                        "<td>DATO_RESULTADO_GESTION</td>".  
                        "<td>CODIGO_ESTUDIO</td>". 
                        "<td>NOMBRE_GESTOR</td>". 
                        "<td>DISTRITO_CLIENTE</td>".
                        "<td>CONTACTABILIDAD</td>".
                        "<td>DATO_CONTACTABILIDAD</td>".
                        "<td>HORA_GESTION</td>".
                        "<td>MONTO_COMPROMISO</td>".
                        "<td>FECHA_COMPROMISO</td>".
                        "<td>TIPO_PAGO</td>".
                        "<td>DATO_TIPO_PAGO</td>".
                    "</tr>";
    for($i=0;$i<count($arrayDATA);$i++){
        $html .=   "<tr>".
                        "<td>".$arrayDATA[$i]['FECHA_GESTION']."</td>".
                        "<td>".$arrayDATA[$i]['NUMERO_CUENTA']."</td>".
                        "<td>".$arrayDATA[$i]['SITUACION_CUENTA']."</td>".
                        "<td>".$arrayDATA[$i]['TIPO_GESTION']."</td>".
                        "<td>".$arrayDATA[$i]['RESULTADO_GESTION']."</td>".  
                        "<td>".$arrayDATA[$i]['DATO_RESULTADO_GESTION']."</td>".  
                        "<td>".$arrayDATA[$i]['CODIGO_ESTUDIO']."</td>". 
                        "<td>".$arrayDATA[$i]['NOMBRE_GESTOR']."</td>". 
                        "<td>".$arrayDATA[$i]['DISTRITO_CLIENTE']."</td>".
                        "<td>".$arrayDATA[$i]['CONTACTABILIDAD']."</td>".
                        "<td>".$arrayDATA[$i]['DATO_CONTACTABILIDAD']."</td>".
                        "<td>".$arrayDATA[$i]['HORA_GESTION']."</td>".
                        "<td>".$arrayDATA[$i]['MONTO_COMPROMISO']."</td>".
                        "<td>".$arrayDATA[$i]['FECHA_COMPROMISO']."</td>".
                        "<td>".$arrayDATA[$i]['TIPO_PAGO']."</td>".
                        "<td>".$arrayDATA[$i]['DATO_TIPO_PAGO']."</td>".
                    "</tr>";
    }
    $html.="</table>";

    echo $html;

?>
