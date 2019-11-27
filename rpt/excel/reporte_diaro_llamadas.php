<?php

    date_default_timezone_set('America/Lima');
    
    $time=date("Ymd");


    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=reporte_diario_llamadas_".$time.".txt");
    
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';

    date_default_timezone_set('America/Lima');
    
    $time=date("Y_m_d_H_i_s");
    
    $factoryConnection= FactoryConnection::create('mysql'); 
    $connection = $factoryConnection->getConnection();

    $cartera = $_REQUEST['Cartera'];
    $servicio = $_REQUEST['Servicio'];
    $fecha_inicio = $_REQUEST['FechaInicio'];
    $fecha_fin = $_REQUEST['FechaFin'];
    // $fecha_unica = $_REQUEST['fecha_unica'];


    $sql = "    SELECT
                CONCAT(
                DATE_FORMAT(t1.fecha,'%d/%m/%Y %H:%i:%s'),'|',
                t1.contrato,'|',
                t1.rpta,
                IF(t1.telf IS NULL,'',CONCAT('|',t1.telf)),
                IF(t1.dir IS NULL,'',CONCAT('|',t1.dir))
                )
                FROM
                (
                SELECT
                lla.idcliente_cartera,
                (SELECT cu.negocio FROM ca_cuenta cu WHERE cu.idcuenta=lla.idcuenta) AS 'contrato',
                lla.fecha AS 'fecha',
                (SELECT nombre FROM ca_final WHERE idfinal=lla.idfinal ) AS 'rpta',
                (
                SELECT 
                GROUP_CONCAT(telf.numero SEPARATOR ',') 
                FROM 
                ca_telefono telf 
                INNER JOIN ca_cliente_cartera clicar ON telf.codigo_cliente=clicar.codigo_cliente 
                WHERE telf.codigo_cliente=clicar.codigo_cliente AND clicar.idcliente_cartera=lla.idcliente_cartera AND telf.is_new=1
                ) AS 'telf',
                (
                SELECT 
                GROUP_CONCAT(dir.direccion SEPARATOR '@')
                FROM 
                ca_direccion dir 
                INNER JOIN ca_cliente_cartera clicar ON dir.codigo_cliente=clicar.codigo_cliente 
                WHERE dir.codigo_cliente=clicar.codigo_cliente AND clicar.idcliente_cartera=lla.idcliente_cartera AND dir.is_new=1
                ) AS 'dir'
                FROM
                ca_llamada lla
                WHERE
                DATE(lla.fecha)BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                lla.estado=1 AND
                lla.tipo='LL'
                ORDER BY lla.idcliente_cartera DESC,lla.fecha DESC
                ) t1
                GROUP BY t1.idcliente_cartera,t1.contrato


    ";

    // echo $sql;
    // exit(); 


    $pr = $connection->prepare( $sql );
    $pr->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
    $pr->bindParam(2,$fecha_fin,PDO::PARAM_STR);
    $pr->bindParam(3,$fecha_inicio,PDO::PARAM_STR);
    $pr->bindParam(4,$fecha_fin,PDO::PARAM_STR);    
    $pr->execute();
    $contador=0;
    $x=0;
    while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
        $i=0;
        /*cuenta la cantidad de cabeceras que contiena la consulta*/
        if($x==0){
            foreach ($row as $index => $value) {
                echo 'FECHA|CONTRATO|RESPUESTA|TELF|DIR'."\r\n";
                $contador=$contador+1;
            }
            $x=$x+1;
        }
        foreach( $row as $index => $value ) {
            $i++;
            // echo $value."|";
            //if($i<$contador){
            //  echo $value."|";
            //}else{
             echo $value;
            //}
        }
        echo "\r\n";

    }



?>