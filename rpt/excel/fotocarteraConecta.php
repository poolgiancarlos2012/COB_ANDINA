<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=FOTOCARTERA.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['Servicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sqlDataCartera = " SELECT idcartera,tabla,archivo,cliente,cartera,cuenta,detalle_cuenta,telefono,direccion,adicionales,cabeceras
                        FROM ca_cartera WHERE idcartera IN ($cartera) ";

    $prData=$connection->prepare($sqlDataCartera);
    $prData->execute();
    $dataCartera=$prData->fetchAll(PDO::FETCH_ASSOC);

    $field = array();

    $cabeceras=$dataCartera[0]['cabeceras'];
    $arrayCabeceras=explode(",",$cabeceras);

    $dataCarteras=str_replace("\\","", $dataCartera[0]['cartera']);
    $arrayCarteras=json_decode($dataCarteras,true);

    $dataCliente=str_replace("\\","", $dataCartera[0]['cliente']);
    $arrayCliente=json_decode($dataCliente,true);

    $dataCuenta=str_replace("\\","",$dataCartera[0]['cuenta']);
    $arrayCuenta=json_decode($dataCuenta,true);

    $dataDetalleCuenta=str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
    $arrayDetalleCuenta=json_decode($dataDetalleCuenta,true);

    $dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
    $arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);


    for($i=0;$i<count($arrayCarteras);$i++){
        
        array_push($field, " car.".$arrayCarteras[$i]['campoT']." AS '".$arrayCarteras[$i]['dato']."' ");            
        
    }


    for($i=0;$i<count($arrayCliente);$i++){
        if($arrayCliente[$i]['campoT']=='codigo'){
            array_push($field," CONCAT('=\"',cli.".$arrayCliente[$i]['campoT'].",'\"') AS '".$arrayCliente[$i]['dato']."' " );
        }else if($arrayCliente[$i]['campoT']=='nombre'){
            array_push($field," replace(REPLACE(Replace(Replace(Replace(cli.".$arrayCliente[$i]['campoT'].",'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') AS '".$arrayCliente[$i]['dato']."' " );
        }else if($arrayCliente[$i]['campoT']=='numero_documento'){
            
        }else{
            array_push($field," cli.".$arrayCliente[$i]['campoT']." AS '".$arrayCliente[$i]['dato']."' " );            
        }
    }

    for($i=0;$i<count($arrayCuenta);$i++){
        if($arrayCuenta[$i]['campoT']=='numero_cuenta'){
            array_push($field, " CONCAT('=\"',cu.".$arrayCuenta[$i]['campoT'].",'\"') AS '".$arrayCuenta[$i]['dato']."' ");
        }else{
            array_push($field, " cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['dato']."' ");            
        }
    }

    for($i=0;$i<count($arrayDetalleCuenta);$i++){
        if($arrayDetalleCuenta[$i]['campoT']!='moneda' && $arrayDetalleCuenta[$i]['campoT']!='total_deuda' && $arrayDetalleCuenta[$i]['campoT']!='tramo'){
            array_push($field, " detcu.".$arrayDetalleCuenta[$i]['campoT']." AS '".$arrayDetalleCuenta[$i]['dato']."' ");
        }
    }

    for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cliente']);$i++ ) {
        array_push($field," clicar.".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['dato']."' ");
    }
    
    for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cuenta']);$i++ ) {
        if($arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['label']=='NUMCUENTA'){
            array_push($field," CONCAT('=\"',cu.".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['campoT'].",'\"') AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['dato']."' ");
        }else{
            array_push($field," cu.".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['dato']."' ");            
        }
    }

    for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_detalle_cuenta']);$i++ ) {
            array_push($field," detcu.".$arrayAdicionalesCuenta['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_detalle_cuenta'][$i]['dato']."' ");
        
    }    

    
        $sql_llamada="SELECT $cabeceras,CODIGO_GESTOR,GESTOR_ASIGNADO,TRAMO_DIA_HDEC,MARCO_GRUPO,ML_FECHA,ML_TELEFONO,ML_CARGA,ML_ESTADO,COD_GESTION_LLAMADA,ML_FCPG,ML_OBSERVACION,ML_CODOPE,ML_OPERADOR,ML_CODIGO_OPERADOR,UL_FECHA,
                UL_TELEFONO,UL_CARGA,UL_ESTADO,UL_OBSERVACION,UL_CODOPE,UL_OPERADOR,UL_CODIGO_OPERADOR,MV_FECHA,VL_CARGA,MV_ESTADO,COD_GESTION_VISITA,MV_FCPG,MV_OBSERVACION,
                MV_CODOPE,MV_OPERADOR,UV_FECHA,UV_CARGA,UV_ESTADO,UV_FCPG,UV_OBSERVACION,UV_CODOPE,UV_OPERADOR,FLAG_CLIENTE,FLAG_CUENTA,INGRESO_CLIENTE,CORREO_NUEVO,MONTO_PROVISION,FECHA_PROVISION,TRAMO_COMERCIAL,INGRESO_CUENTA
                FROM (SELECT ".implode(",", $field).",
                (SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'Direccion_Legal',--
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'Departamento_Legal',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'Provincia_Legal',
                                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'Distrito_Legal',
(SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=4 limit 1) AS 'Direccion_Negocio',--
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=4 limit 1) AS 'Departamento_Negocio',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=4 limit 1) AS 'Provincia_Negocio',
                                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=4 limit 1) AS 'Distrito_Negocio',
(SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'Direccion_Oficina',--
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'Departamento_Oficina',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'Provincia_Oficina',
                                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'Distrito_Oficina',
(SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'Direccion_Aval',--
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'Departamento_Aval',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'Provincia_Aval',
                                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'Distrito_Aval',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idorigen=1 and idtipo_referencia=2 and numero is not null limit 1) AS 'Tel_Dom', 
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idorigen=1 and idtipo_referencia=10 and numero is not null limit 1) AS 'Tel_Aval', 
                (SELECT usu.codigo FROm ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'CODIGO_GESTOR',
                (SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROm ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'GESTOR_ASIGNADO',                
                CASE 
                            WHEN CAST(detcu.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 30 AND CAST(detcu.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                            ELSE 'NO_TRAMO'
                END AS TRAMO_DIA_HDEC, IF(LEFT(TRIM(cu.dato8),2)='VI','VIP','NO VIP') AS MARCO_GRUPO,                
                DATE(cu.ml_fecha) AS 'ML_FECHA',
                ( SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE idtelefono = cu.ml_telefono ) AS ML_TELEFONO,
                ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
                ( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO', 
                (select  finser.codigo from ca_final_servicio finser INNER JOIN ca_final fin On fin.idfinal=finser.idfinal where finser.estado = 1 and finser.idservicio=6 and fin.idfinal = cu.ml_estado ) AS 'Cod_Gestion_Llamada',
                DATE( cu.ml_fcpg ) AS 'ML_FCPG', 
                replace(REPLACE(Replace(Replace(Replace(cu.ml_observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') AS 'ML_OBSERVACION' ,
                cu.ml_operador AS 'ML_CODOPE', 
                ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_OPERADOR',
                ( SELECT usu.codigo FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'ML_CODIGO_OPERADOR',                
                DATE(cu.ul_fecha) AS 'UL_FECHA',
                ( SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE idtelefono = cu.ul_telefono ) AS UL_TELEFONO,
                ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ul_carga LIMIT 1 ) AS 'UL_CARGA',
                ( SELECT nombre FROM ca_final WHERE idfinal = cu.ul_estado LIMIT 1 ) AS 'UL_ESTADO', 
                DATE( cu.ul_fcpg ) AS 'UL_FCPG', 
                replace(REPLACE(Replace(Replace(Replace(cu.ul_observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') AS 'UL_OBSERVACION',
                cu.ul_operador AS 'UL_CODOPE', 
                ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_OPERADOR' ,
                ( SELECT usu.codigo FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ul_operador ) AS 'UL_CODIGO_OPERADOR' ,                
                DATE(cu.mv_fecha) AS 'MV_FECHA',
                ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.mv_carga LIMIT 1 ) AS 'VL_CARGA',
                ( SELECT nombre FROM ca_final WHERE idfinal = cu.mv_estado LIMIT 1 ) AS 'MV_ESTADO', 
                (select  finser.codigo from ca_final_servicio finser INNER JOIN ca_final fin On fin.idfinal=finser.idfinal where finser.estado = 1 and finser.idservicio=6 and fin.idfinal = cu.mv_estado ) AS 'Cod_Gestion_Visita',
                DATE( cu.mv_fcpg ) AS 'MV_FCPG', 
                replace(REPLACE(Replace(Replace(Replace(cu.mv_observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') AS 'MV_OBSERVACION',
                cu.mv_operador AS 'MV_CODOPE', 
                ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.mv_operador ) AS 'MV_OPERADOR' ,
                DATE(cu.uv_fecha) AS 'UV_FECHA',
                ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.uv_carga LIMIT 1 ) AS 'UV_CARGA',
                ( SELECT nombre FROM ca_final WHERE idfinal = cu.uv_estado LIMIT 1 ) AS 'UV_ESTADO', 
                DATE( cu.uv_fcpg ) AS 'UV_FCPG', 
                replace(REPLACE(Replace(Replace(Replace(cu.uv_observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') AS 'UV_OBSERVACION' ,
                cu.uv_operador AS 'UV_CODOPE', 
                ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.uv_operador ) AS 'UV_OPERADOR',
                 clicar.estado AS 'FLAG_CLIENTE',cu.estado AS 'FLAG_CUENTA',date(clicar.fecha_creacion) AS 'INGRESO_CLIENTE',
                 (select correo from ca_correo where idcliente=clicar.idcliente limit 1) As 'CORREO_NUEVO',
                 IFNULL(clicar.deuda,0) AS 'MONTO_PROVISION',
                 (SELECT DATE(his.fecha_creacion) FROM ca_historico_provision his WHERE his.idcliente_cartera=clicar.idcliente_cartera ORDER BY his.fecha_creacion DESC limit 1 ) AS 'FECHA_PROVISION',
                 detcu.grupo1_cuenta AS 'TRAMO_COMERCIAL',
                 DATE(cu.fecha_creacion) AS 'INGRESO_CUENTA'

                 FROM ca_cliente_cartera clicar
                 INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                 INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                 INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                 INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
                 WHERE clicar.idcartera in (".$cartera."))A";


        $pr_llamada=$connection->prepare($sql_llamada);
        $i=0;
        if($pr_llamada->execute()){
            while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
                if($i==0){
                    foreach ($data_llamada as $key => $value) {
                            echo utf8_decode($key)."\t";
                    }
                        echo "\n";
                }
                $i++;
                $cont=0;
                foreach ($data_llamada as $key => $value) {
                        echo utf8_decode($value)."\t";
                }
                        echo "\n";
            }        
        }  
   
    

      



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>