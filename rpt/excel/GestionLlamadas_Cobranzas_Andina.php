<?php

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=gestion_llamadas.xls");
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
    
    $countRow = 5;
    
    $servicio = $_REQUEST['Servicio'];
    $cartera = $_REQUEST['Cartera'];
    $fecha_inicio = $_REQUEST['FechaInicio'];
    $fecha_fin = $_REQUEST['FechaFin'];

    $sql = "    SELECT 
                car.nombre_cartera AS 'CARTERA_MES',
                car.fecha_inicio AS 'INICIO_GESTION',
                car.fecha_fin AS 'FIN_GESTION',
                detcu.dato1 AS 'cod_zon',
                detcu.dato2 AS 'empresa',
                detcu.dato3 AS 'zona',
                detcu.dato4 AS 'localidad',
                detcu.dato5 AS 'vend_actual',
                detcu.dato6 AS 'vend_rtc_actual',
                detcu.dato7 AS 'supervisor',
                clicar.tipo_cliente AS 'tipo_cliente',
                CONCAT('=\"',detcu.codigo_cliente,'\"') AS 'cod_cliente',
                IF (CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) <> '',CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)),cli.razon_social) AS 'cliente',
                detcu.dato8 AS 'td',
                CONCAT('=\"',detcu.codigo_operacion,'\"') AS 'num_doc',
                detcu.fecha_emision AS 'fecha_doc',
                detcu.dato9 AS 'mes_emis',
                detcu.dato10 AS 'ano_emis',
                detcu.dato11 AS 'dias_plazo',
                detcu.fecha_vencimiento AS 'fecha_vcto',
                detcu.dato12 AS 'm_vcto',
                detcu.dato13 AS 'ano_vcto',
                detcu.dias_mora AS 'dias_transc_vcto_of',
                detcu.dato14 AS 'tipo_de_operacion',
                detcu.dato15 AS 'rango_vcto',
                detcu.dato16 AS 'linea_de_credito',
                detcu.dato17 AS 'ind_vcto',
                detcu.dato18 AS 'semaforo_de_vencimiento',
                detcu.moneda AS 'mon',
                detcu.total_deuda AS 'importe_original',
                detcu.saldo_capital AS 'saldo',
                detcu.saldo_capital_soles AS 'soles',
                detcu.saldo_capital_dolares AS 'dolares',
                detcu.dato19 AS 'total_convertido_a_dolares',
                detcu.dato20 AS 'total_convertido_a_soles',
                detcu.dato21 AS 'glosa',
                detcu.dato22 AS 'est_letr',
                detcu.dato23 AS 'banco',
                detcu.dato24 AS 'num_cobranza',
                detcu.dato25 AS 'referencia',
                DATE(lla.fecha) AS ' FECHA_LLAMADA',
                TIME(lla.fecha) AS 'HORA_LLAMADA',
                ( SELECT numero FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS 'TELEFONO',
                ( SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal ) AS 'TIPO_CONTACTO',
                ( SELECT nombre FROM ca_nivel WHERE idnivel = fin.idnivel ) AS 'RESPUESTA_CONTACTO',
                fin.nombre AS 'ESTADO_LLAMADA',
                replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                cu.moneda AS 'DIVISA',
                DATE(lla.fecha_cp) AS 'FECHA_CP',
                TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'TELEOPERADOR',
                ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  LIMIT 1 ) AS 'ASIGNADO',
                CASE WHEN lla.tipo = 'LL' THEN 'CALL' ELSE lla.tipo END AS 'ORIGEN_LLAMADA',
                (SELECT DATE(fecha) FROM ca_llamada WHERE idllamada=clicar.id_ultima_llamada) AS 'FECH_ULT',
                (SELECT TIME(fecha) FROM ca_llamada WHERE idllamada=clicar.id_ultima_llamada) AS 'HORA_ULT',
                (SELECT fin.nombre FROM ca_llamada lla INNER JOIN ca_final fin ON fin.idfinal=lla.idfinal  WHERE lla.idllamada=clicar.id_ultima_llamada) AS 'ESTADO_ULT',
                ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS CONTACTO,
                lla.nombre_contacto AS NOMBRE_CONTACTO,
                ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) AS PARENTESCO ,
                ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) AS MOTIVO_NO_PAGO,
                ( SELECT nombre FROM ca_situacion_laboral WHERE idsituacion_laboral = lla.idsituacion_laboral ) AS SITUACION_LABORAL,
                ( SELECT nombre FROM ca_disposicion_refinanciar WHERE iddisposicion_refinanciar = lla.iddisposicion_refinanciar ) AS DISPOSICION_REFINACIAR,
                ( SELECT nombre FROM ca_estado_cliente WHERE idestado_cliente = lla.idestado_cliente ) AS ESTADO_CLIENTE
                FROM 
                ca_cartera car 
                INNER JOIN ca_cliente cli 
                INNER JOIN ca_cliente_cartera clicar 
                INNER JOIN ca_llamada lla 
                INNER JOIN ca_cuenta cu 
                INNER JOIN ca_final fin 
                INNER JOIN ca_detalle_cuenta detcu
                ON 
                fin.idfinal = lla.idfinal AND 
                cu.idcuenta = lla.idcuenta AND 
                lla.idcliente_cartera = clicar.idcliente_cartera AND 
                clicar.idcliente = cli.idcliente AND 
                clicar.idcartera=car.idcartera AND 
                detcu.idcuenta=cu.idcuenta
                WHERE 
                clicar.idcartera IN (" . $cartera . ") AND 
                cu.idcartera IN ( ".$cartera." ) AND 
                cli.idservicio = $servicio AND 
                cu.estado=1 AND 
                DATE(lla.fecha) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' AND 
                car.idcartera IN (" . $cartera . ")
                ORDER BY lla.idcliente_cartera DESC
                ";
        // echo $sql;
        // exit();
    $prData = $connection->prepare($sql);
    $prData->execute();
    $i = 0;
    //echo '<table>';
    while( $row = $prData->fetch(PDO::FETCH_ASSOC) ) {
      if( $i == 0 ) {
          //echo '<tr>';
          foreach( $row as $index => $value ) {
           // echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
           echo $index."\t";
          }
          echo "\n";
         // echo '</tr>';
      }
    
        $style="";
        ( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
        //echo '<tr>';
        foreach( $row as $key => $value )
        {
          //echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
          if( $key == 'NUMERO_CUENTA' || $key == 'CODIGO_CLIENTE' || $key == 'PAN' ) {
            echo '="'.$value.'"'."\t";
          }else if( $key == 'OBSERVACION' || $key=='CLIENTE' ){
            echo str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value)))))."\t";
          }else{
            echo utf8_decode($value)."\t";
          }
        }
        echo "\n";
        //echo '</tr>';
    
      $i++;
    }
    //echo '</table>';

?>
