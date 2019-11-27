<?php

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=GESTION_MEJOR_LLAMADA.xls");
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
        $sqladicionales="select * from ca_cartera where idcartera=$cartera limit 1";
    $pradicionales=$connection->prepare($sqladicionales);
    $pradicionales->execute();
    $row=$pradicionales->fetch(PDO::FETCH_ASSOC);
    $metadato=json_decode($row['adicionales']);
    foreach ($metadato as $index =>$value){
        if ($index=='ca_datos_adicionales_cliente'){
            $valores=array();
            $valores=$value;
            //var_dump($valores);
            for($i=0;$i<count($valores);$i++){
                foreach ($valores[$i] as $key =>$value){
                    //echo $key;
                    //echo $value;
                    if ($key=='campoT'){
                        $campo=$value;
                    }
                    if($key=='dato'){
                        if($value=='CARTERA'){
                            $dato="clicar.".$campo." as CARTERA";                            
                        }

                    }
                }
            }
        }
        
    }


            
    $sql = "SELECT CONCAT('=\"',t1.CODIGO,'\"') AS 'CODIGO',t1.CARTERA,t1.CLIENTE,t1.TIPO_DOCUMENTO,
			CONCAT('=\"',t1.NUMERO_DOCUMENTO,'\"') AS 'NUMERO_DOCUMENTO',t1.DIRECCION,
			t1.DISTRITO,t1.PROVINCIA,t1.DEPARTAMENTO,t1.FECHA_LLAMADA,t1.HORA_LLAMADA,
			t1.RELACION, t1.NOMBRE_CONTACTO,t1.MOTIVO_NO_PAGO,t1.TELEFONO,
			t1.TIPO_TELEFONO,t1.CODIGO_E,t1.CODF,t1.TIPO_CONTACTO,t1.ESTADO_LLAMADA,
			CONCAT('=\"',t1.NUMERO_CUENTA,'\"') AS 'NUMERO_CUENTA',t1.TRAMO,t1.NUEVO_TRAMO,
			t1.FECHA_CP,t1.MONTO_CP,t1.TELEOPERADOR,t1.OBSERVACION
			FROM 
			(
				SELECT clicar.idcliente_cartera,clicar.dato1 AS 'CARTERA',
				cli.codigo AS 'CODIGO',
				CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno))  AS 'CLIENTE',
				cli.tipo_documento AS 'TIPO_DOCUMENTO',
				cli.numero_documento AS 'NUMERO_DOCUMENTO',
				( SELECT direccion FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera  LIMIT 1 ) AS 'DIRECCION',
				( SELECT distrito FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DISTRITO',
				( SELECT provincia FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'PROVINCIA',
				( SELECT departamento FROM ca_direccion WHERE idcliente_cartera = clicar.idcliente_cartera AND idcartera = clicar.idcartera LIMIT 1 ) AS 'DEPARTAMENTO',
				DATE(lla.fecha) AS ' FECHA_LLAMADA',
				TIME(lla.fecha) AS 'HORA_LLAMADA',
				( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'RELACION',
				lla.nombre_contacto AS 'NOMBRE_CONTACTO',
				( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) AS 'MOTIVO_NO_PAGO',
				( SELECT numero FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS 'TELEFONO',
				( SELECT IF( SUBSTRING(TRIM(numero),1,1)='9','CELULAR','TELEFONO') FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS 'TIPO_TELEFONO',
                                finser.codigo as 'CODIGO_E',
				finser.codigo as 'CODF',
				( SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal ) AS 'TIPO_CONTACTO',
				fin.nombre AS 'ESTADO_LLAMADA',
				lla.idcuenta ,
				( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta = lla.idcuenta ) AS 'NUMERO_CUENTA',
				( SELECT tramo_cuenta FROM ca_cuenta WHERE idcuenta = lla.idcuenta ) AS 'TRAMO',
				( SELECT nuevo_tramo FROM ca_cuenta WHERE idcuenta = lla.idcuenta ) AS 'NUEVO_TRAMO',
				DATE(lla.fecha_cp) AS 'FECHA_CP',
				TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
				( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'TELEOPERADOR',
				REPLACE( REPLACE(lla.observacion,'\t',' '),'\n',' ') AS 'OBSERVACION'
				FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente
				INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
				INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal
				INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal
				WHERE finser.idservicio =  $servicio AND clicar.idcartera = $cartera AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
				ORDER BY clicar.idcliente_cartera, finser.peso DESC , lla.fecha DESC 
			) t1 GROUP BY t1.idcliente_cartera, t1.idcuenta ";

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
            if( $key == 'OBSERVACION' ){
            echo str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",$value))))."\t";
          }else{
            echo $value."\t";
          }
        }
        echo "\n";
        //echo '</tr>';
    
      $i++;
    }
    //echo '</table>';

?>
