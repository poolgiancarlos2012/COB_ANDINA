<?php

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=gestion_llamadas__call_sa.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
    
    $confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
    $user = $confCobrast['user_db']['user_rpt'];
    $password = $confCobrast['user_db']['password_rpt'];
    
    date_default_timezone_set('America/Lima');
    
    $factoryConnection= FactoryConnection::create('mysql');	
    $connection = $factoryConnection->getConnection($user,$password);
    
    $countRow = 5;
    
    $servicio = $_REQUEST['Servicio'];
    $cartera = $_REQUEST['Cartera'];
    $fecha_inicio = $_REQUEST['FechaInicio'];
    $fecha_fin = $_REQUEST['FechaFin'];
    /*
    ?>
    
        <table>
          <tr>
            <td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE LLAMADAS</td>
          </tr>
          <tr>
            <td>Reporte generado:</td>
            <td><?php echo date("Y-m-d"); ?></td>
          </tr>
          <tr>
            <td style="height:40px;"></td>
          </tr>
        </table>
      <?php
    */
    $sql = "SELECT 
        lla.idllamada as 'IDLLAMADA',
        car.nombre_cartera AS 'NOMBRE_GESTION',
        car.fecha_inicio AS 'INICIO_GESTION',
        car.fecha_fin AS 'FIN_GESTION',
        cu.numero_cuenta AS 'NUMERO_CUENTA',
        clicar.dato1 AS 'CARTERA',
        clicar.idcliente_cartera AS 'DATA',
        clicar.codigo_cliente AS 'CODIGO_CLIENTE',
        CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) AS 'CLIENTE',
        cli.numero_documento AS 'NUMERO_DOCUMENTO',
        car.nombre_cartera as 'NOMBRE_GESTION',
        car.fecha_inicio as 'FECHA_INICIO',
        car.fecha_fin as 'FECHA_FIN',
        DATE(lla.fecha) AS ' FECHA_LLAMADA',
        CASE WHEN DAYOFWEEK(DATE(lla.fecha)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) - 1) WHEN DAYOFWEEK(DATE(lla.fecha)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) + 1)  ELSE DATE(lla.fecha) END AS 'FECHA_SIG_LLAMADA',
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
        ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS CONTACTO,
        lla.nombre_contacto AS NOMBRE_CONTACTO,
        ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) AS PARENTESCO ,
        ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) AS MOTIVO_NO_PAGO,
        DATE(lla.fecha_modificacion) AS 'FECHA_MODIFICACION',TIME(lla.fecha_modificacion) AS 'HORA_MODIFICACION',
        (select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) as nombre from ca_usuario usu where usu.idusuario=lla.usuario_modificacion) AS 'USUARIO_MODIFICO',
        lla.call_id,
        IF(lla.enviar_campo=1,'SI','') AS enviar_campo,
        (select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=$servicio) as prioridad,
        (select tipificacion from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=$servicio) as 'TIPO_CONTAC',
        ( SELECT SUM(cuen.total_deuda) FROM ca_cuenta cuen WHERE cuen.estado = 1 AND cuen.idcuenta = cu.idcuenta ) AS 'TOTAL_DEUDA',
        detcu.dias_mora AS 'DIAS_MORA'
        FROM ca_cartera car INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_cuenta cu INNER JOIN ca_final fin INNER JOIN ca_detalle_cuenta detcu
        ON fin.idfinal = lla.idfinal AND cu.idcuenta = lla.idcuenta AND lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND clicar.idcartera=car.idcartera AND detcu.idcuenta=cu.idcuenta
        WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN ( ".$cartera." ) AND lla.tipo IN ('LL','SA')
        AND cli.idservicio = $servicio 
        AND DATE(lla.fecha) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' 
        AND car.idcartera IN (" . $cartera . ") 	GROUP BY lla.idcliente_cartera,lla.fecha";

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
