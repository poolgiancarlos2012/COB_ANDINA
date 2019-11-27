<?php

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=visita.xls");
    header("Pragma:no-cache");
    header("Expires:0");

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
    $connection = $factoryConnection->getConnection();
    
    $idcartera = $_REQUEST['Cartera'];
    $servicio = $_REQUEST['Servicio'];
    $fechaInicio = $_REQUEST['FechaInicio'];
    $fechaFin = $_REQUEST['FechaFin'];


   /* $sql = "SELECT 
    vis.idvisita AS 'IDVISITA',
    car.nombre_cartera AS 'GESTION',
    car.fecha_inicio AS 'FECHA_INICIO',
    car.fecha_fin AS 'FECHA_FIN',
    clicar.codigo_cliente AS 'CODIGO_CLIENTE' ,
    cu.numero_cuenta AS 'NUMERO_CUENTA',
    clicar.dato1 AS 'CARTERA',
	CASE
	WHEN vis.tipo = 'VIS' THEN 'CAMPO'
	WHEN vis.tipo = 'COUR' THEN 'COURIER'
	ELSE '' END AS 'TIPO',
    CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
    case cu.moneda when 'USD' then 2.8*cu.total_deuda when 'VAC' then 7*cu.total_deuda else cu.total_deuda end AS 'DEUDA',
    DATE(vis.fecha_creacion) AS 'FECHA_DIGITACION',
    DATE(vis.fecha_visita) AS 'FECHA_VISITA',
    CASE WHEN DAYOFWEEK(DATE(vis.fecha_visita)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(vis.fecha_visita)) - 1) WHEN DAYOFWEEK(DATE(vis.fecha_visita)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(vis.fecha_visita)) + 1)  ELSE DATE(vis.fecha_visita) END AS 'FECHA_SIG_VISITA',
    vis.fecha_cp AS 'FECHA_CPG',
	TRUNCATE(vis.monto_cp,2) AS MONTO_CP,
    DATE(vis.fecha_recepcion) AS 'FECHA_RECEPCION',
    vis.hora_visita AS HORA_CONTACTACTABILIDAD, 
    vis.hora_salida AS HORA_SALIDA, 
    ( SELECT nombre FROM ca_parentesco WHERE idparentesco = vis.idparentesco ) AS PARENTESCO,
    ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS CONTACTO,
    vis.nombre_contacto AS NOMBRE_CONTACTO,
    ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = vis.idmotivo_no_pago ) AS MOTIVO_NO_PAGO,
    ( SELECT direccion FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DIRECCION',
    ( SELECT distrito FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DISTRITO',
    ( SELECT provincia FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'PROVINCIA',
    ( SELECT departamento FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DEPARTAMENTO',    
    ( SELECT zona FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'ZONA',
    ( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal = vis.idfinal ) AS 'RESPUESTA_GESTION',
    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal) as 'CARGA',
	( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO_VISITA',
    ( SELECT finser.prioridad FROM ca_final fin INNER JOIN ca_final_servicio finser ON fin.idfinal=finser.idfinal WHERE finser.idservicio=$servicio and fin.idfinal=vis.idfinal and finser.estado=1) AS 'PRIORIDAD',
    ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador  LIMIT 1 ) AS 'GESTOR_CAMPO',
	vis.observacion AS 'OBSERVACION',
    vis.descripcion_inmueble AS 'INMUEBLE',
	( SELECT CONCAT_WS('|',IFNULL(dir.direccion,''),IFNULL(dir.distrito,''),IFNULL(dir.provincia,''),IFNULL(dir.departamento,'')) FROM ca_direccion dir INNER JOIN ca_vis_det_direccion_est detvis ON detvis.iddireccion = dir.iddireccion WHERE detvis.idvisita = vis.idvisita LIMIT 1 ) AS DET_DIRECCION_NUEVO,
	( SELECT numero FROM ca_telefono WHERE is_new = 1 AND codigo_cliente = clicar.codigo_cliente AND DATE(fecha_creacion) = DATE(vis.fecha_creacion) AND usuario_creacion = vis.usuario_creacion LIMIT 1 ) AS TELEFONO_NUEVO
	FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_visita vis INNER JOIN ca_cuenta cu 
	ON cu.idcuenta = vis.idcuenta AND vis.idcliente_cartera = clicar.idcliente_cartera AND cli.idcliente = clicar.idcliente AND clicar.idcartera = car.idcartera
	WHERE clicar.idcartera IN ($idcartera) AND car.idcartera IN ($idcartera) AND cu.idcartera IN ($idcartera) and vis.estado=1
    AND DATE(vis.fecha_visita) BETWEEN ? AND ? AND cli.idservicio = ? ";

    */

    $sql="SELECT
    vis.tipo_visita AS 'TIPO_VISITA', 
    vis.idvisita AS 'CODVISITA',
    car.nombre_cartera AS 'GESTION',
    car.fecha_inicio AS 'FECHA_INICIO',
    car.fecha_fin AS 'FECHA_FIN',
    CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODIGO_CLIENTE' ,
    CONCAT('=\"',cu.numero_cuenta,'\"') AS 'NUMERO_CUENTA',
    clicar.dato1 AS 'CARTERA',
    CASE
    WHEN vis.tipo = 'VIS' THEN 'CAMPO'
    WHEN vis.tipo = 'COUR' THEN 'COURIER'
    ELSE '' END AS 'TIPO',
    (SELECT CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) FROM ca_cliente cli where cli.idcliente=clicar.idcliente AND cli.idservicio = $servicio) AS 'NOMBRE_CLIENTE',
    case cu.moneda when 'USD' then 2.8*cu.total_deuda when 'VAC' then 7*cu.total_deuda else cu.total_deuda end AS 'DEUDA',
    DATE(vis.fecha_creacion) AS 'FECHA_DIGITACION',
    DATE(vis.fecha_visita) AS 'FECHA_VISITA',
    CASE WHEN DAYOFWEEK(DATE(vis.fecha_visita)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(vis.fecha_visita)) - 1) WHEN DAYOFWEEK(DATE(vis.fecha_visita)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(vis.fecha_visita)) + 1)  ELSE DATE(vis.fecha_visita) END AS 'FECHA_SIG_VISITA',
    vis.fecha_cp AS 'FECHA_CPG',
    TRUNCATE(vis.monto_cp,2) AS MONTO_CP,
    DATE(vis.fecha_recepcion) AS 'FECHA_RECEPCION',
    vis.hora_visita AS HORA_CONTACTACTABILIDAD, 
    vis.hora_salida AS HORA_SALIDA, 
    ( SELECT nombre FROM ca_parentesco WHERE idparentesco = vis.idparentesco ) AS PARENTESCO,
    ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS CONTACTO,
    vis.nombre_contacto AS NOMBRE_CONTACTO,
    ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = vis.idmotivo_no_pago ) AS MOTIVO_NO_PAGO,
    ( SELECT direccion FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DIRECCION',
    ( SELECT distrito FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DISTRITO',
    ( SELECT provincia FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'PROVINCIA',
    ( SELECT departamento FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DEPARTAMENTO',    
    ( SELECT zona FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'ZONA',
    ( SELECT niv.nombre FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal = vis.idfinal ) AS 'RESPUESTA_GESTION',
    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal) as 'CARGA',
    ( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO_VISITA',
    ( SELECT finser.prioridad FROM ca_final fin INNER JOIN ca_final_servicio finser ON fin.idfinal=finser.idfinal WHERE finser.idservicio=6 and fin.idfinal=vis.idfinal and finser.estado=1) AS 'PRIORIDAD',
    ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador  LIMIT 1 ) AS 'GESTOR_CAMPO',
    replace(replace(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION',
    replace(replace(Replace(Replace(Replace(vis.descripcion_inmueble,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'INMUEBLE',
 ( SELECT CONCAT_WS('|',IFNULL(dir.direccion,''),IFNULL(dir.distrito,''),IFNULL(dir.provincia,''),IFNULL(dir.departamento,'')) FROM ca_direccion dir INNER JOIN ca_vis_det_direccion_est detvis ON detvis.iddireccion = dir.iddireccion WHERE detvis.idvisita = vis.idvisita LIMIT 1 ) AS DET_DIRECCION_NUEVO,
 ( SELECT numero FROM ca_telefono WHERE is_new = 1 AND idcliente_cartera=clicar.idcliente_cartera AND codigo_cliente = clicar.codigo_cliente AND DATE(fecha_creacion) = DATE(vis.fecha_creacion) AND usuario_creacion = vis.usuario_creacion LIMIT 1 ) AS TELEFONO_NUEVO ,
    ( SELECT SUM(cuen.total_deuda) FROM ca_cuenta cuen WHERE cuen.estado = 1 AND cuen.idcuenta = cu.idcuenta ) AS 'TOTAL_DEUDA',
    detcu.dias_mora AS 'DIAS_MORA'
    FROM ca_cartera car 
    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
    INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
    INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
    INNER JOIN ca_detalle_cuenta detcu On detcu.idcuenta = cu.idcuenta
    WHERE clicar.idcartera IN ($idcartera) AND car.idcartera IN ($idcartera) AND cu.idcartera IN ($idcartera) and vis.estado=1
    AND DATE(vis.fecha_visita) BETWEEN '$fechaInicio' AND '$fechaFin' ";

    $pr = $connection->prepare($sql);
//    $pr->bindParam(1, $fechaInicio, PDO::PARAM_STR);
//    $pr->bindParam(2, $fechaFin, PDO::PARAM_STR);
//    $pr->bindParam(3, $servicio, PDO::PARAM_INT);
    $pr->execute();
    $i = 0;
    while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
        if( $i == 0 ) {
            foreach( $row as $index => $value ) {
				if( $index == 'DET_DIRECCION_NUEVO' ) {
					echo 'DIRECCION_NUEVO'."\t";
					echo 'DISTRITO_NUEVO'."\t";
					echo 'PROVINCIA_NUEVO'."\t";
					echo 'DEPARTAMENTO_NUEVO'."\t";
				}else{
					echo $index."\t";
				}
            }
            echo "\n";
        }

        foreach( $row as $key => $value )
        {
            if($key=='NUMERO_CUENTA' || $key=='CODIGO_CLIENTE'){
                echo utf8_decode($value)."\t";                
            }else if($key == 'DET_DIRECCION_NUEVO'){
				$dat_dir = explode("|",$value);
				echo utf8_decode(@$dat_dir[0])."\t";
				echo utf8_decode(@$dat_dir[1])."\t";
				echo utf8_decode(@$dat_dir[2])."\t";
				echo utf8_decode(@$dat_dir[3])."\t";
			}else{
                echo utf8_decode($value)."\t";
            }
        }
        echo "\n";
        
        $i++;
    }



?>