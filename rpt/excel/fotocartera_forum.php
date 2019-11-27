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
	$nombre_servicio = $_REQUEST['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sqlDataCartera = " SELECT idcartera,tabla,archivo,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales,cabeceras
                        FROM ca_cartera WHERE idcartera IN ($cartera) ";

    $prData=$connection->prepare($sqlDataCartera);
    $prData->execute();
    $dataCartera=$prData->fetchAll(PDO::FETCH_ASSOC);

    $field = array();

    $cabeceras=$dataCartera[0]['cabeceras'];
    $arrayCabeceras=explode(",",$cabeceras);

    $dataCliente=str_replace("\\","", $dataCartera[0]['cliente']);
    $arrayCliente=json_decode($dataCliente,true);

    $dataCuenta=str_replace("\\","",$dataCartera[0]['cuenta']);
    $arrayCuenta=json_decode($dataCuenta,true);

    $dataDetalleCuenta=str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
    $arrayDetalleCuenta=json_decode($dataDetalleCuenta,true);

    $dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
    $arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);

    for($i=0;$i<count($arrayCliente);$i++){
        if($arrayCliente[$i]['campoT']=='codigo'){
            array_push($field," CONCAT('=\"',cli.".$arrayCliente[$i]['campoT'].",'\"') AS '".$arrayCliente[$i]['dato']."' " );
        }else if($arrayCliente[$i]['campoT']!='numero_documento'){
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
        if($arrayDetalleCuenta[$i]['campoT']!='codigo_operacion' && $arrayDetalleCuenta[$i]['campoT']!='saldo_capital' && $arrayDetalleCuenta[$i]['campoT']!='moneda' && $arrayDetalleCuenta[$i]['campoT']!='total_deuda' && $arrayDetalleCuenta[$i]['campoT']!='tramo'){
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

    $sql_llamada="SELECT $cabeceras,ML_CUENTA,NUEVO_FONO,NUEVA_DIRECCION,CORREO,FLAG_CLIENTE,FLAG_CUENTA,GESTOR_ASIGNADO,INGRESO_CLIENTE,TRAMO_DIA_HDEC
                FROM (SELECT ".implode(",", $field).",
                (SELECT codigo_postal FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'codpostal',
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'codestado',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'codprovincia',
                (SELECT distrito FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'coddistrito',
                (SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'direccion',
                (SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'direccion2',
                (SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'direccion1',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'provincia',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'provincia2',
                (SELECT distrito FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'distrito',
                (SELECT distrito FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'distrito2',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'telefono',
                (SELECT ubigeo FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'ubigeo',
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'departamento',
                (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'departamento2',
                (SELECT provincia FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'dpto',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'tipofono1',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'fono1',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'extension1',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'prefino1',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'tipofono2',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'fono2',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'extension2',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 limit 1) AS 'prefijo2',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'tipofono3',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'fono3',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'extension3',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 limit 1) AS 'prefijo3',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'tipofono4',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'fono4',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'extension4',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 limit 1) AS 'prefijo4',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=6 limit 1) AS 'tipofono5',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=6 limit 1) AS 'fono5',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=6 limit 1) AS 'estension5',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=6 limit 1) AS 'prefijo5',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=7 limit 1) AS 'tipo_telefono',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=7 limit 1) AS 'prefijo_de_telefono',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=7 limit 1) AS 'telefono1',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=7 limit 1) AS 'extension',
                (SELECT observacion FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=8 limit 1) AS 'tipo_telefono2',
                (SELECT referencia FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=8 limit 1) AS 'prefijo_de_telefono2',
                (SELECT numero FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=8 limit 1) AS 'telefono2',
                (SELECT anexo FROM ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=8 limit 1) AS 'extension02',
                (SELECT usu.codigo FROm ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'CODIGO_GESTOR',
                (SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROm ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'GESTOR_ASIGNADO',                
                (SELECT numero FROm ca_telefono where idcliente_cartera=clicar.idcliente_cartera and is_new=1 limit 1) As 'NUEVO_FONO',
                (SELECT direccion FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and is_new=1 limit 1) AS 'NUEVA_DIRECCION',
                (SELECT correo FROm ca_correo where idcliente=clicar.idcliente limit 1) AS 'CORREO',
                CASE 
                            WHEN CAST(detcu.dias_mora AS SIGNED) <= 30 THEN 'BLANDA'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 30 AND CAST(detcu.dias_mora AS SIGNED) <= 60 THEN 'TEMPRANA'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 60 AND CAST(detcu.dias_mora AS SIGNED) <= 90 THEN 'TARDIA'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 90 THEN 'MOROSA'
                            ELSE 'NO_TRAMO'
                END AS TRAMO_DIA_HDEC, IF(LEFT(TRIM(cu.dato8),2)='VI','VIP','NO VIP') AS MARCO_GRUPO,                
                DATE(cu.ml_fecha) AS 'ML_FECHA',
                ( SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE idtelefono = cu.ml_telefono ) AS ML_TELEFONO,
                ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = cu.ml_carga LIMIT 1 ) AS 'ML_CARGA',
                ( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ML_ESTADO', 
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
                IFNULL((select CONCAT(IFNULL(DATE(lla.fecha),' '),'@@',IFNULL(carfin.nombre,' '),'@@',IFNULL(fin.nombre,' '),'@@',IFNULL(lla.fecha_cp,' '),'@@',IFNULL(lla.monto_cp,' '),'@@',IFNULL(lla.nombre_contacto,''),'@@',IFNULL(replace(REPLACE(Replace(Replace(Replace(lla.observacion,'|',' '),char(10),' '),char(13),' '),CHAR(9),' '),char(8),' '),' '))
                from ca_llamada lla
                inner join ca_final fin on lla.idfinal=fin.idfinal
                inner join ca_final_servicio finser on finser.idfinal=fin.idfinal
                INNER JOIN ca_telefono tel on tel.idtelefono=lla.idtelefono
                inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                inner join ca_usuario_servicio ususer on ususer.idusuario_servicio=lla.idusuario_servicio
                inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                where lla.idcuenta=cu.idcuenta
                ORDER BY finser.peso DESC,lla.fecha DESC limit 1),'@@@@@@@@@@@@') AS 'ML_CUENTA'                 
                 FROM ca_cliente_cartera clicar
                 INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                 INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                 INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                 WHERE clicar.idcartera in (".$cartera."))A";

    $pr_llamada=$connection->prepare($sql_llamada);
    $i=0;
    if($pr_llamada->execute()){
        while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
            if($i==0){
                    echo utf8_decode("NUMERO CONTRATO")."\t";
                    echo utf8_decode("DNI CLIENTE")."\t";
                    echo utf8_decode("NOMBRE CLIENTE")."\t";
                    echo utf8_decode("MONEDA")."\t";
                    echo utf8_decode("SALDO CAPITAL")."\t";
                    echo utf8_decode("NRO CUOTA")."\t";
                    echo utf8_decode("VTO CUO HOY")."\t";
                    echo utf8_decode("DIAS DE ATRASO HOY")."\t";
                    echo utf8_decode("TOTAL HOY")."\t";
                    echo utf8_decode("CUOTA ANTIGUA")."\t";
                    echo utf8_decode("DIF")."\t";
                    echo utf8_decode("TELEFONO1")."\t";
                    echo utf8_decode("TELEFONO2")."\t";
                    echo utf8_decode("NUEVO FONO")."\t";
                    echo utf8_decode("DIRECCION")."\t";
                    echo utf8_decode("DISTRITO")."\t";
                    echo utf8_decode("PROVINCIA")."\t";
                    echo utf8_decode("DEPARTAMENTO")."\t";
                    echo utf8_decode("NOMBRE AVAL")."\t";
                    echo utf8_decode("FONO AVAL")."\t";
                    echo utf8_decode("MODELO")."\t";
                    echo utf8_decode("MARCA")."\t";
                    echo utf8_decode("PLACA")."\t";
                    echo utf8_decode("FECHA GESTION")."\t";
                    echo utf8_decode("TIPO GESTION")."\t";
                    echo utf8_decode("RESULTADO")."\t";
                    echo utf8_decode("FECHA PDP")."\t";
                    echo utf8_decode("CUOTAS PDP")."\t";
                    echo utf8_decode("CONTACTO")."\t";
                    echo utf8_decode("OBSERVACIONES")."\t";
                    echo utf8_decode("NUEVO TELF")."\t";                    
                    echo utf8_decode("NUEVA DIRECCION")."\t";
                    echo utf8_decode("CORREO")."\t";
                    echo utf8_decode("FLAG_CLIENTE")."\t";
                    echo utf8_decode("FLAG_CUENTA")."\t";
                    echo utf8_decode("GESTOR_ASIGNADO")."\t";
                    echo utf8_decode("INGRESO_CLIENTE")."\t";
                    echo utf8_decode("TRAMO")."\t";                    
                    echo "\n";
            }
            $i++;
            $cont=0;
            foreach ($data_llamada as $key => $value) {
                if($key=='ML_CUENTA'){
                    $arrayml_llamada_cliente=explode('@@', $value);
                    for($i=0;$i<count($arrayml_llamada_cliente);$i++){
                        echo utf8_decode($arrayml_llamada_cliente[$i])."\t";                    
                    }
                }else{
                        echo utf8_decode($value)."\t";    
                }
                    
            }
                    echo "\n";
        }        
    }    



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>