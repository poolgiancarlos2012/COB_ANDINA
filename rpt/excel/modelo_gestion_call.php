<?php 
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
//
    date_default_timezone_set('America/Lima');
//
//    header('Content-Type: text/html; charset=UTF-8');
//    header("Content-type:application/vnd.ms-excel;charset=latin");
//    header("Content-Disposition:atachment;filename=modelo_call.xls");
//    header("Pragma:no-cache");
//    header("Expires:0");
//  
    $cartera = $_REQUEST['Cartera'];
    $nombre_servicio = $_REQUEST['NombreServicio'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];
    $tipocambio=$_REQUEST['tipocambio'];
    $tipovac=$_REQUEST['tipovac'];
//
//  $factoryConnection= FactoryConnection::create('mysql'); 
//  $connection = $factoryConnection->getConnection();
    

//    $sql_llamada= "select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
//                    CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
//                    (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
//                    cu.dato9 AS 'Territorio',
//                    cu.dato11 as 'Nombre_oficina',
//                    cu.dato10 AS 'Codigo_oficina',
//                    CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
//                    DATE(lla.fecha) AS 'Fecha_gestion',
//                    detcu.grupo1_cuenta AS 'tramo',
//                    '' AS 'Hora_inicio',
//                    '' AS 'Hora_fin',
//                    lla.fecha_cp As 'Fecha_PDP',
//                    IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
//                    (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
//                    '' AS 'Duracion_llamada(TMO)',
//                    '' AS 'Hora',
//                    carfin.nombre AS 'Tipo_contacto',
//                    fin.nombre as 'Resultado_llamada',
//                    (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
//                    (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',
//                    replace(REPLACE(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') As 'Observaciones',
//                    (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion',
//                    cu.producto,
//                    cu.dato8 as 'marca',
//                    (select nombre_cartera FROM ca_cartera where idcartera=clicar.idcartera) as 'nombre_cartera'
//                    FROM ca_llamada lla
//                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
//                    inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
//                    inner join ca_usuario usu on usu.idusuario=ususer.idusuario
//                    inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
//                    inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
//                    inner join ca_final fin on fin.idfinal =lla.idfinal
//                    inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
//                    where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.tipo IN ('LL','SA') and cu.producto='COMERCIAL'";

//    $pr_llamada=$connection->prepare($sql_llamada);
//    $i=0;
//    $pr_llamada->execute();
//    if($pr_llamada->execute()){
//        while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
//            if($i==0){
//                foreach ($data_llamada as $key => $value) {
//                        echo utf8_decode($key)."\t";
//                }
//                    echo "\n";
//            }
//            $i++;
//            $cont=0;
//            foreach ($data_llamada as $key => $value) {
//                    echo utf8_decode($value)."\t";
//            }
//                    echo "\n";
//        }        
//    }    
    
    
            $objc=new ConexionBD();
            $cn=$objc->getConexionBD();
           
        /*$sql="select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                    CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                    (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                    cu.dato9 AS 'Territorio',
                    cu.dato11 as 'Nombre_oficina',
                    cu.dato10 AS 'Codigo_oficina',
                    CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                    DATE(lla.fecha) AS 'Fecha_gestion',
                    detcu.grupo1_cuenta AS 'tramo',
                        detcu.dias_mora AS 'Dias_Atraso',
                    TIME(lla.fecha) AS 'Hora_inicio',
                    '' AS 'Hora_fin',
                    lla.fecha_cp As 'Fecha_PDP',
                    IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                    (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
                    '' AS 'Duracion_llamada(TMO)',
                    '' AS 'Hora',
                    carfin.nombre AS 'Tipo_contacto',
                    fin.nombre as 'Resultado_llamada',
                    (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
                    (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',
                    replace(REPLACE(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') As 'Observaciones',
                    (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion',
                    cu.producto as producto,
                    cu.dato8 as 'marca',
                    (select nombre_cartera FROM ca_cartera where idcartera=clicar.idcartera) as 'nombre_cartera',
                    peso AS 'peso'
                    FROM ca_llamada lla
                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                    inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                    inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                    inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                    inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                    inner join ca_final fin on fin.idfinal =lla.idfinal
                    inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                    inner join ca_final_servicio finser on finser.idfinal =lla.idfinal
                    where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.tipo IN ('LL','SA') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') and cu.estado=1";
            */
                    $sql="select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                    CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                    (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                    cu.dato9 AS 'Territorio',
                    cu.dato11 as 'Nombre_oficina',
                    cu.dato10 AS 'Codigo_oficina',
                    CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                    DATE(lla.fecha) AS 'Fecha_gestion',
                    detcu.grupo1_cuenta AS 'tramo',
                        detcu.dias_mora AS 'Dias_Atraso',
                    TIME(lla.fecha) AS 'Hora_inicio',
                    '' AS 'Hora_fin',
                    lla.fecha_cp As 'Fecha_PDP',
                    IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                    (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
                    '' AS 'Duracion_llamada(TMO)',
                    '' AS 'Hora',
                    carfin.nombre AS 'Tipo_contacto',
                    fin.nombre as 'Resultado_llamada',
                    (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
                    (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',
                    replace(REPLACE(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') As 'Observaciones',
                    (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion',
                    cu.producto as producto,
                    cu.dato8 as 'marca',
                    (select nombre_cartera FROM ca_cartera where idcartera=clicar.idcartera) as 'nombre_cartera',
                    peso AS 'peso',
                    'LL' as 'tipo'
                    FROM ca_llamada lla
                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                    inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                    inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                    inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                    inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                    inner join ca_final fin on fin.idfinal =lla.idfinal
                    inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                    inner join ca_final_servicio finser on finser.idfinal =fin.idfinal
                    where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.tipo IN ('LL','SA') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES')

                        union all
                    select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                    CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                    (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                    cu.dato9 AS 'Territorio',
                    cu.dato11 as 'Nombre_oficina',
                    cu.dato10 AS 'Codigo_oficina',
                    CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                    DATE(vis.fecha_visita) AS 'Fecha_gestion',
                    detcu.grupo1_cuenta AS 'tramo',
                        detcu.dias_mora AS 'Dias_Atraso',
                    TIME(vis.hora_visita) AS 'Hora_inicio',
                    '' AS 'Hora_fin',
                    vis.fecha_cp As 'Fecha_PDP',
                    IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                    '' AS 'Telefono',
                    '' AS 'Duracion_llamada(TMO)',
                    '' AS 'Hora',
                    carfin.nombre AS 'Tipo_contacto',
                    fin.nombre as 'Resultado_llamada',
                    '' as 'Razon_no_pago',
                    '' AS 'Sustento_PDP',
                    replace(REPLACE(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),'') As 'Observaciones',
                    '' AS 'Alerta_gestion',
                    cu.producto as producto,
                    cu.dato8 as 'marca',
                    (select nombre_cartera FROM ca_cartera where idcartera=clicar.idcartera) as 'nombre_cartera',
                    peso AS 'peso',
                    'VIS' as 'tipo'
                    FROM ca_visita vis
                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =vis.idcliente_cartera
                    inner join ca_usuario_servicio ususer on vis.idnotificador=ususer.idusuario_servicio
                    inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                    inner join ca_cuenta cu on cu.idcuenta =vis.idcuenta
                    inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                    inner join ca_final fin on fin.idfinal =vis.idfinal
                    inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                    inner join ca_final_servicio finser on finser.idfinal =fin.idfinal
                    where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(vis.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND vis.tipo IN ('VIS') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES')";

            $rs=  mysql_query($sql,$cn);        
            $lista=  array(); 
            while ($fila=mysql_fetch_assoc($rs))
            {    $lista[]=$fila;               
            } 
            mysql_close();            
            
           //return  $lista;
            
            $objc=new ConexionBD();
            $cn=$objc->getConexionBD();
           
        /*$sql="SELECT * FROM (select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                            CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                            (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                            cu.dato9 AS 'Territorio',
                            cu.dato11 as 'Nombre_oficina',
                            cu.dato10 AS 'Codigo_oficina',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                            DATE(lla.fecha) AS 'Fecha_gestion',
                            detcu.grupo1_cuenta AS 'tramo',
                                detcu.dias_mora AS 'Dias_Atraso',
                            '' AS 'Hora_inicio',
                            '' AS 'Hora_fin',
                            lla.fecha_cp As 'Fecha_PDP',
                            IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                            (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
                            '' AS 'Duracion_llamada(TMO)',
                            '' AS 'Hora',
                            cu.numero_cuenta AS numero_cuenta,
                            idllamada,
                            max(peso) as peso,
                            carfin.nombre as tipo_contacto, 
                            fin.nombre as resultado_llamada,
                                (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
                                (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',    
                            observacion as observacionllamada,
                            (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion'
                            
                            FROM ca_llamada lla
                           inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                            inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                            inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                            inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                            inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                            inner join ca_final fin on fin.idfinal =lla.idfinal
                            inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                                                        inner join ca_final_servicio finser on lla.idfinal=finser.idfinal
                            where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.tipo IN ('LL','SA') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') and cu.estado=1 and carfin.nombre in('CNE','CEF')
                                                                                        GROUP BY cu.numero_cuenta) AS T 
                    LEFT JOIN (select   idvisita,max(peso) as peso_visita,cu.numero_cuenta AS numero_cuenta, carfin.nombre AS tipo_contacto_vi, fin.nombre AS resultado_visita, observacion as observacionvisita
                                        FROM ca_visita lla
                                       inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                                        inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                                        inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                                        inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                                        inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                                        inner join ca_final fin on fin.idfinal =lla.idfinal
                                        inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                                                                    inner join ca_final_servicio finser on lla.idfinal=finser.idfinal
                                        where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin'  and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') and carfin.nombre in('CNE','CEF')
                    GROUP BY cu.numero_cuenta) AS T2 
                    ON T.numero_cuenta=T2.numero_cuenta";
            */

            $sql="SELECT * FROM (
                SELECT * FROM (
                select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                            CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                            (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                            cu.dato9 AS 'Territorio',
                            cu.dato11 as 'Nombre_oficina',
                            cu.dato10 AS 'Codigo_oficina',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                            DATE(lla.fecha) AS 'Fecha_gestion',
                            detcu.grupo1_cuenta AS 'tramo',
                                detcu.dias_mora AS 'Dias_Atraso',
                            '' AS 'Hora_inicio',
                            '' AS 'Hora_fin',
                            lla.fecha_cp As 'Fecha_PDP',
                            IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                            (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
                            '' AS 'Duracion_llamada(TMO)',
                            '' AS 'Hora',
                            cu.numero_cuenta AS numero_cuenta,
                            idllamada,
                            finser.peso as peso,
                            carfin.nombre as tipo_contacto, 
                            fin.nombre as resultado_llamada,
                                (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
                                (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',    
                            observacion as observacionllamada,
                            (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion',
                                                        'LL' as tipo
                            FROM ca_llamada lla
                                                        inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                            inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                            inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                            inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                            inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                            inner join ca_final fin on fin.idfinal =lla.idfinal
                            inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                        inner join ca_final_servicio finser on fin.idfinal=finser.idfinal
                            where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.tipo IN ('LL','SA') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') and carfin.nombre in ('CEF','CNE')
                            

                            union all

                            select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                            CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                            (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                            cu.dato9 AS 'Territorio',
                            cu.dato11 as 'Nombre_oficina',
                            cu.dato10 AS 'Codigo_oficina',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                            DATE(vis.fecha_visita) AS 'Fecha_gestion',
                            detcu.grupo1_cuenta AS 'tramo',
                                detcu.dias_mora AS 'Dias_Atraso',
                            '' AS 'Hora_inicio',
                            '' AS 'Hora_fin',
                            vis.fecha_cp As 'Fecha_PDP',
                            IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                            '' AS 'Telefono',
                            '' AS 'Duracion_llamada(TMO)',
                            '' AS 'Hora',
                            cu.numero_cuenta AS numero_cuenta,
                            idvisita,
                            finser.peso as peso,
                            carfin.nombre as tipo_contacto, 
                            fin.nombre as resultado_llamada,
                            '' as 'Razon_no_pago',
                            '' AS 'Sustento_PDP',    
                            observacion as observacionllamada,
                            '' AS 'Alerta_gestion',
                                                        'VIS' as tipo
                            FROM ca_visita vis
                                                        inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =vis.idcliente_cartera
                            inner join ca_usuario_servicio ususer on vis.idnotificador=ususer.idusuario_servicio
                            inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                            inner join ca_cuenta cu on cu.idcuenta =vis.idcuenta
                            inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                            inner join ca_final fin on fin.idfinal =vis.idfinal
                            inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                        inner join ca_final_servicio finser on fin.idfinal=finser.idfinal
                            where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(vis.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND vis.tipo IN ('VIS') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') and carfin.nombre in ('CEF','CNE') 
                            )A
                            order by A.numero_cuenta,A.peso DESC                            
                            )TOTAL
                            GROUP BY TOTAL.numero_cuenta";

            $rs=  mysql_query($sql,$cn);      
            $lista2=  array();
            while ($fila=mysql_fetch_assoc($rs))
            {    $lista2[]=$fila;               
            } 
            mysql_close();       

            $objc3=new ConexionBD();
            $cn3=$objc3->getConexionBD();
            
            $sql3="SELECT * FROM (
SELECT *, SUM(Cuota_vencida) As 'TOTAL' FROM (
SELECT * FROM (
SELECT * FROM (
SELECT * FROM (
select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                            CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                            (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                            cu.dato9 AS 'Territorio',
                            cu.dato11 as 'Nombre_oficina',
                            cu.dato10 AS 'Codigo_oficina',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                            DATE(lla.fecha) AS 'Fecha_gestion',
                            detcu.grupo1_cuenta AS 'tramo',
                                detcu.dias_mora AS 'Dias_Atraso',
                            '' AS 'Hora_inicio',
                            '' AS 'Hora_fin',
                            lla.fecha_cp As 'Fecha_PDP',
                            IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                            (SElECT IFNULL(numero_act,numero) FROM ca_telefono where idtelefono=lla.idtelefono limit 1) AS 'Telefono',
                            '' AS 'Duracion_llamada(TMO)',
                            '' AS 'Hora',
                            cu.numero_cuenta AS numero_cuenta,
                            idllamada,
                            finser.peso as peso,
                            carfin.nombre as tipo_contacto, 
                            fin.nombre as resultado_llamada,
                                (SELECT nombre FROM ca_motivo_no_pago where idmotivo_no_pago=lla.idmotivo_no_pago) as 'Razon_no_pago',
                                (SELECT nombre FROM ca_sustento_pago where idsustento_pago=lla.idsustento_pago) AS 'Sustento_PDP',    
                            observacion as observacionllamada,
                            (SELECT nombre FROM ca_alerta_gestion where idalerta_gestion=lla.idalerta_gestion) AS 'Alerta_gestion',
                                                        'LL' as tipo
                            FROM ca_llamada lla
                                                        inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =lla.idcliente_cartera
                            inner join ca_usuario_servicio ususer on lla.idusuario_servicio=ususer.idusuario_servicio
                            inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                            inner join ca_cuenta cu on cu.idcuenta =lla.idcuenta
                            inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                            inner join ca_final fin on fin.idfinal =lla.idfinal
                            inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                        inner join ca_final_servicio finser on fin.idfinal=finser.idfinal
                            where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(lla.fecha) BETWEEN '2015-05-01' AND '2015-05-26' AND lla.tipo IN ('LL','SA') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') 
                            

union all

select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'Asesor',
                            CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CodCliente',
                            (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente limit 1) AS 'Nombre_Cliente',
                            cu.dato9 AS 'Territorio',
                            cu.dato11 as 'Nombre_oficina',
                            cu.dato10 AS 'Codigo_oficina',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'N_contrato',
                            DATE(vis.fecha_visita) AS 'Fecha_gestion',
                            detcu.grupo1_cuenta AS 'tramo',
                                detcu.dias_mora AS 'Dias_Atraso',
                            '' AS 'Hora_inicio',
                            '' AS 'Hora_fin',
                            vis.fecha_cp As 'Fecha_PDP',
                            IF(cu.moneda='USD',cu.total_deuda*$tipocambio,IF(cu.moneda='VAC',cu.total_deuda*$tipovac,cu.total_deuda)) As 'Cuota_vencida',
                            '' AS 'Telefono',
                            '' AS 'Duracion_llamada(TMO)',
                            '' AS 'Hora',
                            cu.numero_cuenta AS numero_cuenta,
                            idvisita,
                            finser.peso as peso,
                            carfin.nombre as tipo_contacto, 
                            fin.nombre as resultado_llamada,
                            '' as 'Razon_no_pago',
                            '' AS 'Sustento_PDP',    
                            observacion as observacionllamada,
                            '' AS 'Alerta_gestion',
                                                        'VIS' as tipo
                            FROM ca_visita vis
                                                        inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =vis.idcliente_cartera
                            inner join ca_usuario_servicio ususer on vis.idnotificador=ususer.idusuario_servicio
                            inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                            inner join ca_cuenta cu on cu.idcuenta =vis.idcuenta
                            inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                            inner join ca_final fin on fin.idfinal =vis.idfinal
                            inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                        inner join ca_final_servicio finser on fin.idfinal=finser.idfinal
                            where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and detcu.idcartera in ($cartera) and date(vis.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND vis.tipo IN ('VIS') and cu.producto IN('PR?STAMOS COMERCIALES','PRESTAMOS COMERCIALES','LEASING','CRED. INMOBILIARIOS','TARJETAS COMERCIALES') 
)A
order by A.numero_cuenta,A.peso DESC                            
)TOTAL
GROUP BY TOTAL.numero_cuenta
)T 
ORDER BY T.codCliente,T.peso DESC
)W
GROUP BY W.codcliente
)Z
ORDER BY Z.TOTAL DESC limit 30";
                $rs3=  mysql_query($sql3,$cn3);        
                $lista3=  array(); 
                while ($fila3=mysql_fetch_assoc($rs3))
                {    $lista3[]=$fila3;               
                } 
                mysql_close(); 
            
            
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').';../../phpincludes/phpexcel/Classes/');

/** PHPExcel */
include '../../phpincludes/phpexcel/Classes/PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include '../../phpincludes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
include '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';
// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();
$styleArray = array(
    'font' => array(
        'bold' => FALSE,
        'name' => 'Calibri',
        'size' => '11',
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            'color' => array('argb'=>'00000F')
        ),
        'left' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'right' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'bottom' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
            
        ),     
        'inside'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ), 
       
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_PATTERN_MEDIUMGRAY,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => '9BBB59',
        ),
        'endcolor' => array(
            'argb' => '9BBB59',
        ),
    ),
);
$styleArrayRos = array(
    'font' => array(
        'bold' => FALSE,
        'name' => 'Calibri',
        'size' => '11',
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            'color' => array('argb'=>'00000F')
        ),
        'left' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'right' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'bottom' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
            
        ),     
        'inside'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ), 
       
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_PATTERN_MEDIUMGRAY,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'DA9694',
        ),
        'endcolor' => array(
            'argb' => 'DA9694',
        ),
    ),
);

$styleArrayAma = array(
    'font' => array(
        'bold' => FALSE,
        'name' => 'Calibri',
        'size' => '11',
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            'color' => array('argb'=>'00000F')
        ),
        'left' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'right' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
        ),
        'bottom' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>'00000F')
            
        ),     
        'inside'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ), 
       
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_PATTERN_MEDIUMGRAY,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFFF00',
        ),
        'endcolor' => array(
            'argb' => 'FFFF00',
        ),
    ),
);
 
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArrayRos);
$objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('W1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('X1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Y1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Z1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('AA1')->applyFromArray($styleArrayAma);
   
//$objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('9BBB59');


 





// Set properties
//echo date('H:i:s') . " Set properties\n";
//$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
//$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
//$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
//$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
//$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

// Add some data
//echo date('H:i:s') . " Add some data\n";
$objPHPExcel->setActiveSheetIndex(0); 
//$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
//$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
//$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
//$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');
// Asesor   CodCliente  Nombre_Cliente  Territorio  Nombre_oficina


            $objPHPExcel->getActiveSheet()->setCellValue('A1','ASESOR');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CODIGO CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('C1','NOMBRE_CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'TERRITORIO');
            $objPHPExcel->getActiveSheet()->setCellValue('E1','NOMBRE_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CODIGO_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('G1','N_CONTRATO');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'FECHA_GESTION');
            $objPHPExcel->getActiveSheet()->setCellValue('I1','TRAMO');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'DIAS_ATRASO');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'HORA_INICIO');
            $objPHPExcel->getActiveSheet()->setCellValue('L1','HORA_FIN');
            $objPHPExcel->getActiveSheet()->setCellValue('M1', 'FECHA_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('N1','CUOTA_VENCIDA');
            $objPHPExcel->getActiveSheet()->setCellValue('O1', 'TELEFONO');
            $objPHPExcel->getActiveSheet()->setCellValue('P1','DURACION_LLAMADA(TMO)');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'HORA');
            $objPHPExcel->getActiveSheet()->setCellValue('R1', 'TIPO_CONTACTO');
            $objPHPExcel->getActiveSheet()->setCellValue('S1','RESULTADO_LLAMADA');
            $objPHPExcel->getActiveSheet()->setCellValue('T1', 'RAZON_NO_PAGO');
            $objPHPExcel->getActiveSheet()->setCellValue('U1','SUSTENTO_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('V1', 'OBSERVACIONES');
            $objPHPExcel->getActiveSheet()->setCellValue('W1', 'ALERTA_GESTION');
                $objPHPExcel->getActiveSheet()->setCellValue('X1', 'BANCA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'PESO');
                $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'NOMBRE_CARTERA');
                $objPHPExcel->getActiveSheet()->setCellValue('AA1', 'TIPO');
                
            
        /*  $objPHPExcel->getActiveSheet()->setCellValue('W1','PRODUCTO');
            $objPHPExcel->getActiveSheet()->setCellValue('X1', 'MARCA');
            $objPHPExcel->getActiveSheet()->setCellValue('Y1','NOMBRE_CARTERA');    */
            
$i=2;

//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(A)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(B)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(C)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(D)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(E)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(F)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(G)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(H)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(I)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(J)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(K)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(L)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(M)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(N)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(O)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(P)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Q)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(R)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(S)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(T)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(U)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(V)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(W)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(X)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Y)->setAutoSize(true);
            
foreach ($lista as $value) {
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, utf8_decode($value['Asesor']));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, utf8_decode($value['CodCliente']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, utf8_decode($value['Nombre_Cliente']));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, utf8_decode($value['Territorio']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, utf8_decode($value['Nombre_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, utf8_decode($value['Codigo_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, utf8_decode($value['N_contrato']));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, utf8_decode($value['Fecha_gestion']));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, utf8_decode($value['tramo']));
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, utf8_decode($value['Dias_Atraso']));
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, utf8_decode($value['Hora_inicio']));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, utf8_decode($value['Hora_fin']));
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, utf8_decode($value['Fecha_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, utf8_decode($value['Cuota_vencida']));
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, utf8_decode($value['Telefono']));
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, utf8_decode($value['Duracion_llamada(TMO)']));
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, utf8_decode($value['Hora']));
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, utf8_decode($value['Tipo_contacto']));
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, utf8_decode($value['Resultado_llamada']));
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, utf8_decode($value['Razon_no_pago']));
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, utf8_decode($value['Sustento_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, utf8_decode($value['Observaciones']));
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['Alerta_gestion']));
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'MINORISTA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['peso']));
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, utf8_decode($value['nombre_cartera']));
                $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, utf8_decode($value['tipo']));
            
            
         /*   $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['producto']));
            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, utf8_decode($value['marca']));
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['nombre_cartera']));  */
$i++;          
}

// Rename sheet
//echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('MODELO_GESTION_CALL');

// Siguiente pestaña
$positionInExcel=1; //Posicion 1 
$objPHPExcel->createSheet($positionInExcel); //Loque mencionaste
$objPHPExcel->setActiveSheetIndex(1); //Seleccionar la pestaña deseada

//            $objPHPExcel->getActiveSheet()->getColumnDimension(A)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(B)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(C)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(D)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(E)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(F)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(G)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(H)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(I)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(J)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(K)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(L)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(M)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(N)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(O)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(P)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Q)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(R)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(S)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(T)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(U)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(V)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(W)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(X)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Y)->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArrayRos);
$objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('W1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('X1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Y1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Z1')->applyFromArray($styleArrayAma);

            $objPHPExcel->getActiveSheet()->setCellValue('A1','ASESOR');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CODIGO CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('C1','NOMBRE_CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'TERRITORIO');
            $objPHPExcel->getActiveSheet()->setCellValue('E1','NOMBRE_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CODIGO_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('G1','N_CONTRATO');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'FECHA_GESTION');
            $objPHPExcel->getActiveSheet()->setCellValue('I1','TRAMO');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','DIAS_ATRASO');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'HORA_INICIO');
            $objPHPExcel->getActiveSheet()->setCellValue('L1','HORA_FIN');
            $objPHPExcel->getActiveSheet()->setCellValue('M1', 'FECHA_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('N1','CUOTA_VENCIDA');
            $objPHPExcel->getActiveSheet()->setCellValue('O1', 'TELEFONO');
            $objPHPExcel->getActiveSheet()->setCellValue('P1','DURACION_LLAMADA(TMO)');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'HORA');
        /*  $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ID_LLAMADA');
            $objPHPExcel->getActiveSheet()->setCellValue('R1','PESO');   */
            $objPHPExcel->getActiveSheet()->setCellValue('R1', 'TIPO_CONTACTO');
            $objPHPExcel->getActiveSheet()->setCellValue('S1','RESULTADO_LLAMADA');
                $objPHPExcel->getActiveSheet()->setCellValue('T1','RAZON_NO_PAGO');
                $objPHPExcel->getActiveSheet()->setCellValue('U1','SUSTENTO_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('V1', 'OBSERVACION_LLAMADA');
                $objPHPExcel->getActiveSheet()->setCellValue('W1', 'ALERTA_GESTION');
                $objPHPExcel->getActiveSheet()->setCellValue('X1', 'BANCA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'PESO');
                $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'TIPO');
            
       /*     $objPHPExcel->getActiveSheet()->setCellValue('V1', 'ID_VISITA');
            $objPHPExcel->getActiveSheet()->setCellValue('W1', 'PESO_VISITA');
            $objPHPExcel->getActiveSheet()->setCellValue('X1','TIPO_CONTACTO_VI');
            $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'RESULTADO_VISITA');
            $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'OBSERVACION_VISITA');   */
            
$i=2;

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(75);
//$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(75);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

foreach ($lista2 as $value) {
        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, utf8_decode($value['Asesor']));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, utf8_decode($value['CodCliente']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, utf8_decode($value['Nombre_Cliente']));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, utf8_decode($value['Territorio']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, utf8_decode($value['Nombre_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, utf8_decode($value['Codigo_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, utf8_decode($value['N_contrato']));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, utf8_decode($value['Fecha_gestion']));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, utf8_decode($value['tramo']));
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, utf8_decode($value['Dias_Atraso']));
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, utf8_decode($value['Hora_inicio']));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, utf8_decode($value['Hora_fin']));
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, utf8_decode($value['Fecha_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, utf8_decode($value['Cuota_vencida']));
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, utf8_decode($value['Telefono']));
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, utf8_decode($value['Duracion_llamada(TMO)']));
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, utf8_decode($value['Hora']));
         /*   $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, utf8_decode($value['idllamada']));
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, utf8_decode($value['peso']));              */
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, utf8_decode($value['tipo_contacto']));
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, utf8_decode($value['resultado_llamada']));
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, utf8_decode($value['Razon_no_pago']));
                $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, utf8_decode($value['Sustento_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, utf8_encode($value['observacionllamada']));
                $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['Alerta_gestion']));
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'MINORISTA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['peso']));
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, utf8_decode($value['tipo']));
         /*   $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, utf8_decode($value['idvisita']));
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['peso_visita']));
            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, utf8_decode($value['tipo_contacto_vi']));
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['resultado_visita']));
            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, utf8_encode($value['observacionvisita']));  */

$i++;          
}

$objPHPExcel->getActiveSheet()->setTitle('MEJOR_LLAMADA-MEJOR_VISITA'); //Establecer nombre para la pestaña



// Siguiente pestaña
$positionInExcel=2; //Posicion 1 
$objPHPExcel->createSheet($positionInExcel); //Loque mencionaste
$objPHPExcel->setActiveSheetIndex(2); 
//
// $objPHPExcel->getActiveSheet()->getColumnDimension(A)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(B)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(C)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(D)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(E)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(F)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(G)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(H)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(I)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(J)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(K)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(L)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(M)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(N)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(O)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(P)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Q)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(R)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(S)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(T)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(U)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(V)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(W)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(X)->setAutoSize(true);
//            $objPHPExcel->getActiveSheet()->getColumnDimension(Y)->setAutoSize(true);


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArrayRos);
$objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('W1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('X1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Y1')->applyFromArray($styleArrayAma);
$objPHPExcel->getActiveSheet()->getStyle('Z1')->applyFromArray($styleArrayAma);

////Seleccionar la pestaña deseada
//            $objPHPExcel->getActiveSheet()->setCellValue('A1','ASESOR');
//            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CODIGO CLIENTE');
//           // $objPHPExcel->getActiveSheet()->setCellValue('C1','CLIENTE_CARTERA');
//            $objPHPExcel->getActiveSheet()->setCellValue('D1','NOMBRE_CLIENTE');
//            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'TERRITORIO');
//            $objPHPExcel->getActiveSheet()->setCellValue('F1','NOMBRE_OFICINA');
//            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CODIGO_OFICINA');
//            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'N_CONTRATO');
//            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'FECHA_GESTION');
//            $objPHPExcel->getActiveSheet()->setCellValue('I1','TRAMO');
//                $objPHPExcel->getActiveSheet()->setCellValue('I1','DIAS_ATRASO');
//            $objPHPExcel->getActiveSheet()->setCellValue('J1', 'HORA_INICIO');
//            $objPHPExcel->getActiveSheet()->setCellValue('K1','HORA_FIN');
//            $objPHPExcel->getActiveSheet()->setCellValue('L1', 'FECHA_PDP');
//            $objPHPExcel->getActiveSheet()->setCellValue('M1','TOTAL CUOTA_VENCIDA');
//            $objPHPExcel->getActiveSheet()->setCellValue('N1', 'TELEFONO');
//            $objPHPExcel->getActiveSheet()->setCellValue('O1','DURACION_LLAMADA(TMO)');
//            $objPHPExcel->getActiveSheet()->setCellValue('P1', 'HORA');
//        /*    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ID_LLAMADA');
//            $objPHPExcel->getActiveSheet()->setCellValue('R1','PESO');  */
//            $objPHPExcel->getActiveSheet()->setCellValue('S1', 'TIPO_CONTACTO');
//            $objPHPExcel->getActiveSheet()->setCellValue('T1','RESULTADO_LLAMADA');
//            $objPHPExcel->getActiveSheet()->setCellValue('U1', 'OBSERVACION_LLAMADA');
//            $objPHPExcel->getActiveSheet()->setCellValue('V1', 'ID_VISITA');
//            $objPHPExcel->getActiveSheet()->setCellValue('W1', 'PESO_VISITA');
//            $objPHPExcel->getActiveSheet()->setCellValue('X1','TIPO_CONTACTO_VI');
//            $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'RESULTADO_VISITA');
//            $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'OBSERVACION_VISITA');
            $objPHPExcel->getActiveSheet()->setCellValue('A1','ASESOR');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CODIGO CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('C1','NOMBRE_CLIENTE');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'TERRITORIO');
            $objPHPExcel->getActiveSheet()->setCellValue('E1','NOMBRE_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CODIGO_OFICINA');
            $objPHPExcel->getActiveSheet()->setCellValue('G1','N_CONTRATO');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'FECHA_GESTION');
            $objPHPExcel->getActiveSheet()->setCellValue('I1','TRAMO');
                $objPHPExcel->getActiveSheet()->setCellValue('J1','DIAS_ATRASO');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'HORA_INICIO');
            $objPHPExcel->getActiveSheet()->setCellValue('L1','HORA_FIN');
            $objPHPExcel->getActiveSheet()->setCellValue('M1', 'FECHA_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('N1','CUOTA_VENCIDA');
            $objPHPExcel->getActiveSheet()->setCellValue('O1', 'TELEFONO');
            $objPHPExcel->getActiveSheet()->setCellValue('P1','DURACION_LLAMADA(TMO)');
            $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'HORA');
        /*  $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ID_LLAMADA');
            $objPHPExcel->getActiveSheet()->setCellValue('R1','PESO');   */
            $objPHPExcel->getActiveSheet()->setCellValue('R1', 'TIPO_CONTACTO');
            $objPHPExcel->getActiveSheet()->setCellValue('S1','RESULTADO_LLAMADA');
                $objPHPExcel->getActiveSheet()->setCellValue('T1','RAZON_NO_PAGO');
                $objPHPExcel->getActiveSheet()->setCellValue('U1','SUSTENTO_PDP');
            $objPHPExcel->getActiveSheet()->setCellValue('V1', 'OBSERVACION_LLAMADA');
                $objPHPExcel->getActiveSheet()->setCellValue('W1', 'ALERTA_GESTION');
                $objPHPExcel->getActiveSheet()->setCellValue('X1', 'BANCA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'PESO');
                $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'TIPO');
            
$i=2;

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(75);
//$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(75);
//            $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('B')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('B')->setAutoSize(true);

foreach ($lista3 as $value) {        
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, utf8_decode($value['Asesor']));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, utf8_decode($value['CodCliente']));
           // $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, utf8_decode($value['cliente_cartera2']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, utf8_decode($value['Nombre_Cliente']));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, utf8_decode($value['Territorio']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, utf8_decode($value['Nombre_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, utf8_decode($value['Codigo_oficina']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, utf8_decode($value['N_contrato']));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, utf8_decode($value['Fecha_gestion']));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, utf8_decode($value['tramo']));
                $objPHPExcel->getActiveSheet()->setCellValue('j'.$i, utf8_decode($value['Dias_Atraso']));
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, utf8_decode($value['Hora_inicio']));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, utf8_decode($value['Hora_fin']));
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, utf8_decode($value['Fecha_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, utf8_decode($value['TOTAL']));
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, utf8_decode($value['Telefono']));
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, utf8_decode($value['Duracion_llamada(TMO)']));
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, utf8_decode($value['Hora']));
//            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, utf8_decode($value['idllamada']));
//            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, utf8_decode($value['peso']));
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, utf8_decode($value['tipo_contacto']));
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, utf8_decode($value['resultado_llamada']));
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, utf8_decode($value['Razon_no_pago']));
                $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, utf8_decode($value['Sustento_PDP']));
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, utf8_encode($value['observacionllamada']));
//            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, utf8_decode($value['idvisita']));
//            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['peso_visita']));
//            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, utf8_decode($value['tipo_contacto_vi']));
//            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['resultado_visita']));
//            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, utf8_encode($value['observacionvisita']));
                $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, utf8_decode($value['Alerta_gestion']));
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'MINORISTA');
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, utf8_decode($value['peso']));
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, utf8_decode($value['tipo']));
$i++;          
}

$objPHPExcel->getActiveSheet()->setTitle('MEJOR_LLAMADA_VISITA-30CLI.TOPS'); //Establecer nombre para la pestaña

$objPHPExcel->setActiveSheetIndex(0); //Seleccionar la pestaña deseada
// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));


// Echo done
//echo date('H:i:s') . " Done writing file.\r\n";

header('Location: modelo_gestion_call_descargar.php');