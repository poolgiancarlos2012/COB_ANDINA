﻿
<?php

class MARIACargaCarteraDAO {


    public function NormalizarTelefono2(){
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $servicio=$_POST['servicio'];
        $carteras=$_POST['cartera'];
        $randon = date("Y_m_d_H_i_s") . rand(100,1000);

        if($servicio!=10 && $servicio!=11  && $servicio!=13){
            echo json_encode(array('rst'=>false,'msg'=>'Este servicio no es aceptable'));
            exit();
        }

        if( $carteras==''){
            echo json_encode(array('rst'=>false,'msg'=>'Este servicio no es aceptable'));
            exit();
        }

        $sqlCreateTMPIdTelefono = "CREATE TEMPORARY TABLE tmp_tel".$randon." ( idtelefono int(11) )";
        $prCreateTMPIdTelefono = $connection->prepare($sqlCreateTMPIdTelefono);
        if( $prCreateTMPIdTelefono->execute() ){
            $sqlAlterTMPIdTelefono = "ALTER TABLE tmp_tel".$randon." add unique index (idtelefono)";
            $prAlterTMPIdTelefono = $connection->prepare($sqlAlterTMPIdTelefono);
            if( $prAlterTMPIdTelefono->execute() ){
                $sqlInsertTMPIdTelefono = "INSERT INTO tmp_tel".$randon."
                            SELECT idtelefono from ca_telefono 
                                        WHERE idcartera IN (". $carteras .")";
                $prInsertTMPIdTelefono=$connection->prepare($sqlInsertTMPIdTelefono);
                if( $prInsertTMPIdTelefono->execute() ){
                    /* ACTUALIZANDO EL NUMERO_ACT - el length puede ser  10-9-8-7 pa este query*/
                    // PA CORREGIR LA VALIDACION Q NO HABIA PA LOS TELEFONOS
                    $SQLUPDATETELEFONO = "UPDATE ca_telefono 
                                SET 
                                numero = REPLACE((REPLACE((REPLACE((REPLACE ( ( REPLACE ( ( REPLACE( ( REPLACE( (REPLACE( TRIM(numero) ,'-','') ),' ','' ) ),'/','' )),'*','' )),'Q','')),'(','')),')','')),'.','')
                                WHERE 
                                idcartera IN (". $carteras .") AND estado = 1";
                    $PRLUPDATETELEFONO = $connection->prepare($SQLUPDATETELEFONO);
                    $PRLUPDATETELEFONO->execute();

                    $sqlUpdateNroActualByInicioCero = "UPDATE ca_telefono 
                                SET 
                                numero_act = REPLACE ( ( REPLACE ( ( REPLACE( ( REPLACE( (REPLACE( (TRIM(LEADING '0' FROM ( TRIM(LEADING '1' FROM ( TRIM(LEADING '0' FROM ( TRIM(numero) ) ) ) ) ) ) ),'-','') ),' ','' ) ),'/','' )),'*','' )),'Q','') 
                                WHERE 
                        idcartera IN (". $carteras .") AND 
                        LENGTH(numero) IN(12,11,10,9,8,7) AND estado=1 AND numero_act IS NULL 
                        and REPLACE( ( REPLACE( (REPLACE( (TRIM(LEADING '0' FROM ( TRIM(LEADING '1' FROM ( TRIM(LEADING '0' FROM ( IFNULL(numero_act,numero) ) ) ) ) ) ) ),'-','') ),' ','' ) ),'/','' ) != ''
                        and ( IFNULL(numero_act,numero ) REGEXP '^0.')
                        and idtelefono 
                        IN (SELECT tel.idtelefono from tmp_tel".$randon." tel );";
                    $prUpdateNroActualByInicioCero=$connection->prepare($sqlUpdateNroActualByInicioCero);
                    if( $prUpdateNroActualByInicioCero->execute() ){
                        /*ACTUALIZAR LOS NUMERS ACTUALIZADOS NUMERO_ACT PARA QUE SE LE AGREGE EL PREFIJO POR QUE ES DE PROVINCIA , ESTO E SPARA LOS LENGTR = 6*/
                        $sqlUpdateNroActualByInicioCeroAndProvincia="UPDATE ca_telefono tele
                                                SET 
                                                tele.numero_act = CONCAT( (select pre.codigo from ca_prefijo pre 
                                                    inner join ca_direccion dir on dir.departamento = pre.departamento where dir.idcuenta=tele.idcuenta and dir.departamento is not null limit 1) ,REPLACE ( ( REPLACE ( ( REPLACE( ( REPLACE( (REPLACE( (TRIM(LEADING '0' FROM ( TRIM(LEADING '1' FROM ( TRIM(LEADING '0' FROM ( TRIM(numero) ) ) ) ) ) ) ),'-','') ),' ','' ) ),'/','' )),'*','' )),'Q','')  ) 
                                                WHERE 
                                tele.idcartera IN (". $carteras .") AND 
                                LENGTH(tele.numero_act) =6 AND tele.estado=1 AND tele.numero_act IS not NULL 
                                and tele.idtelefono 
                                IN (SELECT tel.idtelefono from tmp_tel".$randon." tel );";
                        $prUpdateNroActualByInicioCeroAndProvincia=$connection->prepare($sqlUpdateNroActualByInicioCeroAndProvincia);
                        if( $prUpdateNroActualByInicioCeroAndProvincia->execute() ){
                            /*ACTUALIZAR LOS NMUMERO DE 6 DIGITOS Q NO ESTEN ACTUALIZADOS PA AUMENTAR SU PREFIJO SIESQ TIENE SU DEPARTAMENTO*/
                            $sqlUpdateNroByInicioNotCeroAndProvincia = "UPDATE ca_telefono tele
                                                            SET 
                                                            tele.numero_act = CONCAT( (select pre.codigo from ca_prefijo pre inner join ca_direccion dir on dir.departamento = pre.departamento where dir.idcuenta=tele.idcuenta and dir.departamento is not null limit 1) , REPLACE(REPLACE(REPLACE(tele.numero,' ',''),'/',''),'-','') )
                                                            WHERE 
                                            idcartera IN (". $carteras .") AND 
                                            LENGTH(tele.numero) IN(6) AND tele.estado=1 AND tele.numero_act IS NULL 
                                            and !(IFNULL(tele.numero_act,tele.numero) NOT REGEXP '^0.' AND (IFNULL(tele.numero_act,tele.numero) REGEXP '^9........$' OR IFNULL(tele.numero_act,tele.numero) REGEXP '^[2-8].......$' OR IFNULL(tele.numero_act,tele.numero) REGEXP '^[2-8]......$' ))
                                            and tele.idtelefono 
                                            IN (SELECT tel.idtelefono from tmp_tel".$randon." tel );";
                            $prUpdateNroByInicioNotCeroAndProvincia = $connection->prepare($sqlUpdateNroByInicioNotCeroAndProvincia);
                            if(  $prUpdateNroByInicioNotCeroAndProvincia->execute() ){
                                $sqlUpdateNumberBy8DigitsAndBeginByOne ="UPDATE ca_telefono tele
                                                    SET 
                                                    tele.numero_act = substr(tele.numero,2)
                                                    WHERE 
                                    idcartera IN (". $carteras .") AND 
                                    LENGTH(tele.numero) IN(8) AND tele.estado=1 AND tele.numero_act IS NULL AND tele.numero REGEXP '^1.'
                                    and !(IFNULL(tele.numero_act,tele.numero) NOT REGEXP '^0.' AND (IFNULL(tele.numero_act,tele.numero) REGEXP '^9........$' OR IFNULL(tele.numero_act,tele.numero) REGEXP '^[2-8].......$' OR IFNULL(tele.numero_act,tele.numero) REGEXP '^[2-8]......$' ))
                                    and tele.idtelefono 
                                    IN (SELECT tel.idtelefono from tmp_tel".$randon." tel );";
                                $prUpdateNumberBy8DigitsAndBeginByOne=$connection->prepare($sqlUpdateNumberBy8DigitsAndBeginByOne);
                                if( $prUpdateNumberBy8DigitsAndBeginByOne->execute() ){
                                    /*UPDATE LOS NUMERO_ACT QUE NO CUMPLAN*/
                                    $sqlUpdateNroActualInvalido = "UPDATE ca_telefono 
                                                            SET numero_act = NULL
                                                            where idcartera IN
                                                            (". $carteras .") and
                                                             (LENGTH(numero_act)<=6 or numero=numero_act or 
                                                                (SUBSTR(numero_act,1,1)=9 and LENGTH(numero_act)<9))
                                                            ";
                                    $prUpdateNroActualInvalido=$connection->prepare($sqlUpdateNroActualInvalido);
                                    if( $prUpdateNroActualInvalido->execute() ){
                                        echo json_encode(array('rst'=>true,'msg'=>'Proceso de Normalizacion culminado'));
                                        exit();
                                    }
                                }            
                            }      
                        }
                    }
                }
            }
        }
    }

    public function verificarArchivoPlanoPagoSaga($_post, $_files){
        $file = $_POST['idTmpFile'];
        $usuario_creacion = $_POST['usuario_creacion'];
        $servicio = $_POST['servicio'];
        $cartera = $_POST['cartera'];

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $randon = date("Y_m_d_H_i_s") . rand(100,1000);

        if (@opendir('../documents/carteras/SAGA/preparacion_archivos_planos')) {
            if (@move_uploaded_file($_files[$file]['tmp_name'], '../documents/carteras/SAGA/preparacion_archivos_planos' . '/' . $_files[$file]['name'])) {
               $nameFile = $_files[$file]['name']; 

               $sqlCreateTemporary= "CREATE TEMPORARY TABLE tmp_carga_pago_saga_".$randon."
                                     (
                                        EQUIPO varchar(100),
                                        USUARIO_GESTOR varchar(100),
                                        NOMBRE_GESTOR varchar(100),
                                        NOMBRE_TITULAR varchar(100),
                                        DISTRITO_PARTICULAR varchar(100),
                                        DIRECCION_PARTICULAR varchar(100),
                                        CUENTA varchar(100),
                                        MONTO_ACELERADO varchar(100),
                                        MONTO_PAGO varchar(100),
                                        TIPO_PAGO varchar(100),
                                        FECHA_ASIGNA varchar(100),
                                        FECHA_PAGO_DET varchar(100),
                                        CODIGO_OPERACION varchar(100)
                                        ) ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                $prCreateTemporary= $connection->prepare($sqlCreateTemporary);
                if( $prCreateTemporary->execute() ){
                    $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/SAGA/preparacion_archivos_planos".
                                 "/" . $nameFile . 
                                "'INTO TABLE  tmp_carga_pago_saga_".$randon." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' IGNORE 1 LINES ";
                    $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                    if( $prLoadTemporary->execute() ){

                        $sqlUpdateCodigoOperacion = "UPDATE tmp_carga_pago_saga_".$randon." tmp inner join ca_detalle_cuenta detcu on detcu.numero_cuenta = tmp.CUENTA 
                                set tmp.CODIGO_OPERACION=detcu.codigo_operacion
                                WHERE detcu.IDCARTERA = ".$cartera.";";
                        $prUpdateCodigoOperacion = $connection->prepare($sqlUpdateCodigoOperacion);
                        if( $prUpdateCodigoOperacion->execute() ){

                            $sqlDeleteCodigoOperacionNoExistente = "DELETE FROM tmp_carga_pago_saga_".$randon." WHERE CODIGO_OPERACION IS NULL;";
                            $prDeleteCodigoOperacionNoExistente = $connection->prepare($sqlDeleteCodigoOperacionNoExistente);
                            if( $prDeleteCodigoOperacionNoExistente->execute() ){

                                $sqlDATA = "SELECT * FROM tmp_carga_pago_saga_".$randon." ;";
                                $prDATA = $connection->prepare($sqlDATA);
                                $prDATA->execute();
                                $arrayDATA = $prDATA->fetchAll(PDO::FETCH_ASSOC);
                                $file_download= fopen('../documents/carteras/SAGA/preparacion_archivos_planos/file_download.txt', 'w');
                                $array_cabecera = array('EQUIPO','USUARIO_GESTOR','NOMBRE_GESTOR','NOMBRE_TITULAR','DISTRITO_PARTICULAR','DIRECCION_PARTICULAR','CUENTA','CODIGO_OPERACION','MONTO_ACELERADO','MONTO_PAGO','TIPO_PAGO',
                                    'FECHA_ASIGNA','FECHA_PAGO_DET');
                                $cab = implode("\t", $array_cabecera);

                                fwrite($file_download,$cab."\n");
                                for($i=0;$i<count($arrayDATA);$i++){
                                    
                                    $data = $arrayDATA[$i]['EQUIPO']."\t".$arrayDATA[$i]['USUARIO_GESTOR']."\t".$arrayDATA[$i]['NOMBRE_GESTOR']."\t".$arrayDATA[$i]['NOMBRE_TITULAR']."\t".$arrayDATA[$i]['DISTRITO_PARTICULAR']."\t".$arrayDATA[$i]['DIRECCION_PARTICULAR']."\t".$arrayDATA[$i]['CUENTA'].
                                    "\t".$arrayDATA[$i]['CODIGO_OPERACION']."\t".$arrayDATA[$i]['MONTO_ACELERADO']."\t".$arrayDATA[$i]['MONTO_PAGO']."\t".$arrayDATA[$i]['TIPO_PAGO']."\t".$arrayDATA[$i]['FECHA_ASIGNA']."\t".$arrayDATA[$i]['FECHA_PAGO_DET'];
                                    
                                    fwrite($file_download,$data."\n");
                                }
                                fclose($file_download);

                                echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.', 'isfile'=>true ));
                                exit();
                            }
                        }
                    }
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor 2'. $_files[$file]['tmp_name']));
            }
        }else{
            echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor 1'));
        }
    }

    public function updateMontosPagado($_post){

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $servicio=$_POST['servicio'];
        $carteras=$_POST['cartera'];

        // solo esta permitido pal servicio 10 y 11
        if($servicio!=10 && $servicio!=11 ){
            echo json_encode(array('rst'=>false,'msg'=>'Este servicio no es aceptable'));
            exit();
        }

        if( $carteras==''){
            echo json_encode(array('rst'=>false,'msg'=>'Este servicio no es aceptable'));
            exit();
        }
            
        // como en el dato1 para servicio10 y servicio11 esta la fecha del proceso... fecha a la que esta la deuda
        if( $servicio==10 ){

            $sqlGetFechaProcesoByCartera = "select idcartera, CASE
                                                    WHEN LENGTH(TRIM(dato1)) = 8
                                                    THEN CONCAT(SUBSTRING(dato1,1,4),'-',SUBSTRING(dato1,5,2),'-',SUBSTRING(dato1,7,2))
                                                    WHEN LENGTH(TRIM(dato1)) = 10
                                                    THEN
                                                        CASE
                                                        WHEN INSTR(TRIM(dato1),'/') = 3
                                                        THEN CONCAT(SUBSTRING(dato1,7,4),'-',SUBSTRING(dato1,4,2),'-',SUBSTRING(dato1,1,2))
                                                        WHEN INSTR(TRIM(dato1),'/') = 5
                                                        THEN CONCAT(SUBSTRING(dato1,1,4),'-',SUBSTRING(dato1,6,2),'-',SUBSTRING(dato1,9,2))
                                                        WHEN INSTR(TRIM(dato1),'-') = 3
                                                        THEN CONCAT(SUBSTRING(dato1,7,4),'-',SUBSTRING(dato1,4,2),'-',SUBSTRING(dato1,1,2))
                                                        WHEN INSTR(TRIM(dato1),'.') = 3
                                                        THEN CONCAT(SUBSTRING(dato1,7,4),'-',SUBSTRING(dato1,4,2),'-',SUBSTRING(dato1,1,2))
                                                        WHEN INSTR(TRIM(dato1),'.') = 5
                                                        THEN CONCAT(SUBSTRING(dato1,1,4),'-',SUBSTRING(dato1,6,2),'-',SUBSTRING(dato1,9,2))
                                                        ELSE TRIM(dato1)
                                                        END
                                                    ELSE TRIM(dato1)
                                                    END as fecha_proceso from ca_cuenta where idcartera IN (".$carteras.") and estado = 1 and retirado=0 group by idcartera";
            $prGetFechaProcesoByCartera = $connection->prepare($sqlGetFechaProcesoByCartera);
            if( $prGetFechaProcesoByCartera->execute() ){
                $arrayIdCarteraAndFecha = $prGetFechaProcesoByCartera->fetchAll(PDO::FETCH_ASSOC);

                $sqlRestartMontoPagadoCuenta = "UPDATE ca_cuenta  SET  monto_pagado=0 WHERE  idcartera IN (".$carteras.") ";
                $prRestartMontoPagadoCuenta = $connection->prepare($sqlRestartMontoPagadoCuenta);
                if( $prRestartMontoPagadoCuenta->execute() ){
                    $sqlRestartMontoPagadoDetalleCuenta="UPDATE ca_detalle_cuenta  SET  monto_pagado=0 WHERE  idcartera IN (".$carteras.") ";
                    $prRestartMontoPagadoDetalleCuenta = $connection->prepare($sqlRestartMontoPagadoDetalleCuenta);
                    if( $prRestartMontoPagadoDetalleCuenta->execute() ){
                        // aca viene lo bueno
                        for($i=0;$i<count($arrayIdCarteraAndFecha);$i++){
                           
                            $sqlUpdateMontoPagadoCuenta = "UPDATE ca_detalle_cuenta detcu INNER JOIN
                                                                (
                                                                SELECT iddetalle_cuenta, SUM(monto_pagado) AS MONTO_PAGO 
                                                                from ca_pago where idcartera = ".$arrayIdCarteraAndFecha[$i]['idcartera']." and estado = 1 
                                                                and fecha > '".$arrayIdCarteraAndFecha[$i]['fecha_proceso']."' 
                                                                group by iddetalle_cuenta order by fecha desc
                                                                ) tmp
                                                                ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
                                                                SET 
                                                                detcu.monto_pagado = IFNULL( detcu.monto_pagado,0 ) + tmp.MONTO_PAGO;
                                                            ";
                            $prUpdateMontoPagadoCuenta = $connection->prepare($sqlUpdateMontoPagadoCuenta);
                            if( $prUpdateMontoPagadoCuenta->execute() ){
                                $sqlUpdateMontoPagadoDetalleCuenta = "UPDATE ca_cuenta cu INNER JOIN
                                                                (
                                                                SELECT idcuenta, SUM(monto_pagado) AS MONTO_PAGO  
                                                                from ca_pago where idcartera = ".$arrayIdCarteraAndFecha[$i]['idcartera']." and estado = 1 
                                                                and fecha > '".$arrayIdCarteraAndFecha[$i]['fecha_proceso']."' 
                                                                group by idcuenta order by fecha desc
                                                                ) tmp
                                                                ON tmp.idcuenta = cu.idcuenta
                                                                SET
                                                                cu.monto_pagado = IFNULL(cu.monto_pagado,0) + tmp.MONTO_PAGO ";
                                $prUpdateMontoPagadoDetalleCuenta =$connection->prepare($sqlUpdateMontoPagadoDetalleCuenta);
                                if( $prUpdateMontoPagadoDetalleCuenta->execute() ){

                                }
                            }
                        }

                        echo json_encode(array('rst'=>true,'msg'=>'Se actualiz&oacute; el monto pagado'));
                        // aca termina lo bueno
                    }
                } 
            }

        }else if( $servicio==11 ){
            $sqlGetFechaProcesoByCartera = "SELECT detcu.idcartera, CASE
                                                WHEN LENGTH(TRIM(detcu.dato1)) = 8
                                                THEN CONCAT(SUBSTRING(detcu.dato1,1,4),'-',SUBSTRING(detcu.dato1,5,2),'-',SUBSTRING(detcu.dato1,7,2))
                                                WHEN LENGTH(TRIM(detcu.dato1)) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM(detcu.dato1),'/') = 3
                                                    THEN CONCAT(SUBSTRING(detcu.dato1,7,4),'-',SUBSTRING(detcu.dato1,4,2),'-',SUBSTRING(detcu.dato1,1,2))
                                                    WHEN INSTR(TRIM(detcu.dato1),'/') = 5
                                                    THEN CONCAT(SUBSTRING(detcu.dato1,1,4),'-',SUBSTRING(detcu.dato1,6,2),'-',SUBSTRING(detcu.dato1,9,2))
                                                    WHEN INSTR(TRIM(detcu.dato1),'-') = 3
                                                    THEN CONCAT(SUBSTRING(detcu.dato1,7,4),'-',SUBSTRING(detcu.dato1,4,2),'-',SUBSTRING(detcu.dato1,1,2))
                                                    WHEN INSTR(TRIM(detcu.dato1),'.') = 3
                                                    THEN CONCAT(SUBSTRING(detcu.dato1,7,4),'-',SUBSTRING(detcu.dato1,4,2),'-',SUBSTRING(detcu.dato1,1,2))
                                                    WHEN INSTR(TRIM(detcu.dato1),'.') = 5
                                                    THEN CONCAT(SUBSTRING(detcu.dato1,1,4),'-',SUBSTRING(detcu.dato1,6,2),'-',SUBSTRING(detcu.dato1,9,2))
                                                    ELSE TRIM(detcu.dato1)
                                                    END
                                                ELSE TRIM(detcu.dato1)
                                                END as fecha_proceso from ca_detalle_cuenta detcu inner join ca_cuenta cu on cu.idcuenta=detcu.idcuenta where detcu.idcartera IN(".$carteras.") and cu.retirado=0 and cu.estado = 1 GROUP BY detcu.idcartera";
            $prGetFechaProcesoByCartera = $connection->prepare($sqlGetFechaProcesoByCartera);
            if( $prGetFechaProcesoByCartera->execute() ){
                $arrayIdCarteraAndFecha = $prGetFechaProcesoByCartera->fetchAll(PDO::FETCH_ASSOC);

                $sqlRestartMontoPagadoCuenta = "UPDATE ca_cuenta  SET  monto_pagado=0 WHERE  idcartera IN (".$carteras.") ";
                $prRestartMontoPagadoCuenta = $connection->prepare($sqlRestartMontoPagadoCuenta);
                if( $prRestartMontoPagadoCuenta->execute() ){
                    $sqlRestartMontoPagadoDetalleCuenta="UPDATE ca_detalle_cuenta  SET  monto_pagado=0 WHERE  idcartera IN (".$carteras.") ";
                    $prRestartMontoPagadoDetalleCuenta = $connection->prepare($sqlRestartMontoPagadoDetalleCuenta);
                    if( $prRestartMontoPagadoDetalleCuenta->execute() ){
                        // aca viene lo bueno
                        for($i=0;$i<count($arrayIdCarteraAndFecha);$i++){
                           
                            $sqlUpdateMontoPagadoCuenta = "UPDATE ca_detalle_cuenta detcu INNER JOIN
                                                                (
                                                                SELECT iddetalle_cuenta, SUM(monto_pagado) AS MONTO_PAGO 
                                                                from ca_pago where idcartera = ".$arrayIdCarteraAndFecha[$i]['idcartera']." and estado = 1 
                                                                and fecha > '".$arrayIdCarteraAndFecha[$i]['fecha_proceso']."' 
                                                                group by iddetalle_cuenta order by fecha desc
                                                                ) tmp
                                                                ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
                                                                SET 
                                                                detcu.monto_pagado = IFNULL( detcu.monto_pagado,0 ) + tmp.MONTO_PAGO;
                                                            ";
                            $prUpdateMontoPagadoCuenta = $connection->prepare($sqlUpdateMontoPagadoCuenta);
                            if( $prUpdateMontoPagadoCuenta->execute() ){
                                $sqlUpdateMontoPagadoDetalleCuenta = "UPDATE ca_cuenta cu INNER JOIN
                                                                (
                                                                SELECT idcuenta, SUM(monto_pagado) AS MONTO_PAGO  
                                                                from ca_pago where idcartera = ".$arrayIdCarteraAndFecha[$i]['idcartera']." and estado = 1 
                                                                and fecha > '".$arrayIdCarteraAndFecha[$i]['fecha_proceso']."' 
                                                                group by idcuenta order by fecha desc
                                                                ) tmp
                                                                ON tmp.idcuenta = cu.idcuenta
                                                                SET
                                                                cu.monto_pagado = IFNULL(cu.monto_pagado,0) + tmp.MONTO_PAGO ";
                                $prUpdateMontoPagadoDetalleCuenta =$connection->prepare($sqlUpdateMontoPagadoDetalleCuenta);
                                if( $prUpdateMontoPagadoDetalleCuenta->execute() ){

                                }
                            }
                        }

                        echo json_encode(array('rst'=>true,'msg'=>'Se actualiz&oacute; el monto pagado'));
                        // aca termina lo bueno
                    }
                } 
            }
        }
    }


    //Preparacion de archivos de covinoc (7 archivos para deuda)
    public function verificarArchivoPlanoCovinoc($_post, $_files){
        $file = $_POST['idTmpFile'];
        $usuario_creacion = $_POST['usuario_creacion'];
        $servicio = $_POST['servicio'];
        $fechaProceso = $_POST['fecha_proceso'];
        $nameFile = "";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $randon = date("Y_m_d_H_i_s") . rand(100,1000);
        $nameFile_separado = "";
        $cadena_proceso = array();
        $array_importar = array();
        //tmp_filDatDem_
        $campos_tmp_filDatDem = array('tip_doc varchar(50)','doc varchar(50)','contrato varchar(50)',
                                        'obligacion varchar(50)','nombre varchar(50)','tip_deudor varchar(50)');
        $pauta_length_filDatDem = array('0','1','13','9','30','80','20');
        $pauta_posicion_filDatDem = array('0','1','14','23','53','133');
        //tmp_filOblMor
        $campos_tmp_filOblMor = array('contrato varchar(50)','obligacion varchar(50)','producto varchar(50)',
                                        'saldo_actual varchar(50)','mora varchar(50)','fecha_corte varchar(50)');
        $pauta_length_filOblMor = array('0','9','30','20','13','13','10');
        $pauta_posicion_filOblMor = array('0','9','39','59','72','85');
        //tmp_filDatAdiObl
        $campos_tmp_filDatAdiObl = array('contrato varchar(50)','obligacion varchar(50)','portafolio varchar(50)',
                                        'originador varchar(50)','sub_prod varchar(50)','capital varchar(50)',
                                        'interes_corriente varchar(50)','interes_mora varchar(50)','otros varchar(50)');
        $pauta_length_filDatAdiObl = array('0','9','30','20','25','20','13','13','13','13');
        $pauta_posicion_filDatAdiObl = array('0','9','39','59','84','104','117','130','143');
        //tmp_filOtrDatAdiObl
        $campos_tmp_filOtrDatAdiObl = array('contrato varchar(50)','obligacion varchar(50)','producto varchar(50)',
                                        'fecha_inicio_mora varchar(50)');
        $pauta_length_filOtrDatAdiObl = array('0','9','30','20','10');
        $pauta_posicion_filOtrDatAdiObl = array('0','9','39','59');
        //tmp_filDatTel
        $campos_tmp_filDatTel = array('tip_doc varchar(50)','doc varchar(50)','telefono varchar(50)',
                                        'extension varchar(50)','tip_tel varchar(50)','ciudad varchar(50)'
                                        ,'departamento varchar(50)');
        $pauta_length_filDatTel = array('0','1','13','11','5','1','30','20');
        $pauta_posicion_filDatTel = array('0','1','14','25','30','31','61');
        //tmp_filDatDir
        $campos_tmp_filDatDir = array('tip_doc varchar(50)','doc varchar(50)','direccion varchar(50)',
                                        'ciudad varchar(50)','tip_direccion varchar(50)', 'departamento varchar(50)');
        $pauta_length_filDatDir = array('0','1','13','100','30','1','50');
        $pauta_posicion_filDatDir = array('0','1','14','114','144','145');
        //tmp_filDatEma
        $campos_tmp_filDatEma = array('tip_doc varchar(50)','doc varchar(50)','email varchar(50)');
        $pauta_length_filDatEma = array('0','1','13','100');
        $pauta_posicion_filDatEma = array('0','1','14');
        // vamos a verificar uno por uno .
        if (@opendir('../documents/carteras/COVINOC/preparacion_archivos_planos')) {
            if (@move_uploaded_file($_files[$file]['tmp_name'], '../documents/carteras/COVINOC/preparacion_archivos_planos' . '/' . $_files[$file]['name'])) {
                $nameFile = $_files[$file]['name'];
                $nameFile_separado = $randon.$_files[$file]['name'];
             //  echo json_encode(array('rst' => true, 'msg' => 'okas'));
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
            switch ($_POST['caso']) {
                case 'fileDatosDemograficos':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filDatDem);$i++){
                            if($i!=count($pauta_length_filDatDem)-1){
                                array_push($cadena_proceso, str_replace(array("\"","\\")," ",trim(substr($lineas[$j],$pauta_posicion_filDatDem[$i],$pauta_length_filDatDem[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                  //  echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filDatDem_".$randon." (".implode(',', $campos_tmp_filDatDem).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filDatDem_".$randon." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filDatDem_".$randon.
                                        " WHERE tip_doc = '' OR doc = '' ".
                                        "OR obligacion=''  OR tip_deudor='' ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            $sqlTruncateInProceso = "TRUNCATE  proc_prep_covinoc ";
                            $prTruncateInProceso = $connection->prepare($sqlTruncateInProceso);
                            $prTruncateInProceso->execute();
                            $sqlTruncateInConsolidado = "TRUNCATE  data_preparacion_covinoc ";
                            $prTruncateInConsolidado = $connection->prepare($sqlTruncateInConsolidado);
                            $prTruncateInConsolidado->execute();
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlInsertInProceso = "INSERT INTO  proc_prep_covinoc (estado, fecha_creacion, usuario_creacion, idservicio, isload_datos_demograficos ) VALUES(1, now(), ".$usuario_creacion.", ".$servicio.", 1 )";
                            $prInsertInProceso = $connection->prepare($sqlInsertInProceso);
                            $prInsertInProceso->execute();

                            $sqlUpdateDoc = "UPDATE tmp_filDatDem_".$randon." tmp SET tmp.doc = IF(CONVERT(tmp.doc, UNSIGNED INTEGER)=0,tmp.doc,CONVERT(tmp.doc, UNSIGNED INTEGER));  ";
                            $prUpdateDoc = $connection->prepare($sqlUpdateDoc);
                            if($prUpdateDoc->execute()){
                                /*
                                $sqlUpdateDocumento = "UPDATE tmp_filDatDem_".$randon." tmp SET tmp.doc = IF(LENGTH(tmp.doc)<8,LPAD(tmp.doc,8,0),tmp.doc)  ;";
                                $prUpdateDocumento = $connection->prepare($sqlUpdateDocumento);
                                $prUpdateDocumento->execute();
                                */
                                $sqlInsertInConsolidado = "INSERT INTO  data_preparacion_covinoc (CODCENT, TIPO_DOCUMENTO, DOCUMENTO, CONTRATO, OBLIGACION, NOMBRE, TIPO_DEUDOR ) ( SELECT doc, tip_doc, doc, contrato,obligacion, nombre, tip_deudor FROM tmp_filDatDem_".$randon." )";
                                $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                                $prInsertInConsolidado->execute();

                                $sqlUpdateMonedaByContrato = "UPDATE data_preparacion_covinoc dat SET dat.moneda = (select moneda from adicional_contrato_extrajudicial where dat.CONTRATO=contrato limit 1)";
                                $prUpdateMonedaByContrato = $connection->prepare($sqlUpdateMonedaByContrato);
                                $prUpdateMonedaByContrato->execute();


                                $sqlUpdateCodCentInConsolidado = "UPDATE data_preparacion_covinoc dat INNER JOIN ca_cliente cli ON IF(CONVERT(cli.numero_documento, UNSIGNED INTEGER)=0,cli.numero_documento,CONVERT(cli.numero_documento, UNSIGNED INTEGER))=dat.DOCUMENTO SET dat.CODCENT=cli.codigo WHERE cli.estado = 1 and cli.idservicio=10";
                                $prUpdateCodCentInConsolidado = $connection->prepare($sqlUpdateCodCentInConsolidado);
                                $prUpdateCodCentInConsolidado->execute();

                                $sqlUpdateFechaProcesoInConsolidado = "UPDATE  data_preparacion_covinoc dat SET dat.FPROCESO='".$fechaProceso."'";
                                $prUpdateFechaProcesoInConsolidado = $connection->prepare($sqlUpdateFechaProcesoInConsolidado);
                                $prUpdateFechaProcesoInConsolidado->execute();

                                $sqlUpdateNombreForUnicode = "UPDATE  data_preparacion_covinoc SET NOMBRE = REPLACE(REPLACE(nombre,'?','Ñ'),'#','Ñ') ";
                                $prUpdateNombreForUnicode = $connection->prepare($sqlUpdateNombreForUnicode);
                                $prUpdateNombreForUnicode->execute();

                                echo json_encode(array('rst' => true, 'msg' => 'Complete - Pasó la prueba.' ));
                                exit();


                            }

                            

                            
                        }

                    }
                    break;
                case 'fileObligacionesMora':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filOblMor);$i++){
                            if($i!=count($pauta_length_filOblMor)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filOblMor[$i],$pauta_length_filOblMor[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filOblMor_".$randon." (".implode(',', $campos_tmp_filOblMor).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filOblMor_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filOblMor_".$randon.
                                        " WHERE obligacion = '' OR producto= '' ".
                                        "OR saldo_actual=''  ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_obligaciones_en_mora=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();

                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filOblMor_".$randon." tmp on tmp.obligacion=dat.OBLIGACION SET dat.PRODUCTO=tmp.producto, dat.SALDO_ACTUAL=tmp.saldo_actual, dat.MORA=tmp.mora, dat.FECHA_CORTE=tmp.fecha_corte";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();
                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.' ));
                            exit();

                            
                        }

                    }

                    break;
                case 'fileDatosAdicObligaciones':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filDatAdiObl);$i++){
                            if($i!=count($pauta_length_filDatAdiObl)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filDatAdiObl[$i],$pauta_length_filDatAdiObl[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filDatAdiObl_".$randon." (".implode(',', $campos_tmp_filDatAdiObl).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filDatAdiObl_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filDatAdiObl_".$randon.
                                        " WHERE obligacion = '' ".
                                        "  ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_dat_adi_obligaciones=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();

                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filDatAdiObl_".$randon." tmp on tmp.obligacion=dat.OBLIGACION SET dat.PORTAFOLIO=tmp.portafolio, dat.ORIGINADOR=tmp.originador, dat.SUB_PRODUCTO=tmp.sub_prod, dat.CAPITAL=tmp.capital, dat.INTERES_CORRIENTE=tmp.interes_corriente, dat.INTERES_MORA=tmp.interes_mora, dat.OTROS=tmp.otros";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();

                            $sqlUpdatePortafolioByObligacion = "UPDATE data_preparacion_covinoc dat inner join tmp_portafolio_piro tmp on tmp.obligacion=dat.OBLIGACION SET dat.PORTAFOLIO = tmp.portafolio";
                            $prUpdatePortafolioByObligacion = $connection->prepare($sqlUpdatePortafolioByObligacion);
                            $prUpdatePortafolioByObligacion->execute();
                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.' ));
                            exit();

                            
                        }

                    }
                    break;
                case 'fileOtrosDatosAdicObligaciones':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filOtrDatAdiObl);$i++){
                            if($i!=count($pauta_length_filOtrDatAdiObl)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filOtrDatAdiObl[$i],$pauta_length_filOtrDatAdiObl[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filOtrDatAdiObl_".$randon." (".implode(',', $campos_tmp_filOtrDatAdiObl).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filOtrDatAdiObl_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filOtrDatAdiObl_".$randon.
                                        " WHERE obligacion = '' OR producto= '' ".
                                        "OR fecha_inicio_mora=''  ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_otr_dat_adi_obligaciones=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();

                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filOtrDatAdiObl_".$randon." tmp on tmp.obligacion=dat.OBLIGACION and tmp.producto=dat.PRODUCTO SET dat.FECHA_INICIO_MORA=tmp.fecha_inicio_mora";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();
                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.' ));
                            exit();

                            
                        }

                    }
                    break;
                case 'fileDatosTelefonos':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filDatTel);$i++){
                            if($i!=count($pauta_length_filDatTel)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filDatTel[$i],$pauta_length_filDatTel[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filDatTel_".$randon." (".implode(',', $campos_tmp_filDatTel).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filDatTel_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filDatTel_".$randon.
                                        " WHERE tip_doc = '' OR doc = '' OR telefono= '' ".
                                        "OR tip_tel=''   ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_datos_telefonos=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();

                            $sqlUpdateDoc = "UPDATE tmp_filDatTel_".$randon." tmp SET tmp.doc = IF(CONVERT(tmp.doc, UNSIGNED INTEGER)=0,tmp.doc,CONVERT(tmp.doc, UNSIGNED INTEGER));  ";
                            $prUpdateDoc = $connection->prepare($sqlUpdateDoc);
                            $prUpdateDoc->execute();
                            /*      
                            $sqlUpdateDocumento = "UPDATE tmp_filDatTel_".$randon." tmp SET tmp.doc = IF(LENGTH(tmp.doc)<8,LPAD(tmp.doc,8,0),tmp.doc)  ;";
                            $prUpdateDocumento = $connection->prepare($sqlUpdateDocumento);
                            $prUpdateDocumento->execute();
                            */
                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filDatTel_".$randon." tmp on tmp.doc=dat.DOCUMENTO  SET dat.TELEFONO=CONVERT(tmp.telefono, UNSIGNED INTEGER), dat.EXTENSION=tmp.extension, dat.TIPO_TELEFONO=tmp.tip_tel";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();
                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.' ));
                            exit();

                            
                        }

                    }
                    break;
                case 'fileDatosDirecciones':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filDatDir);$i++){
                            if($i!=count($pauta_length_filDatDir)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filDatDir[$i],$pauta_length_filDatDir[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filDatDir_".$randon." (".implode(',', $campos_tmp_filDatDir).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filDatDir_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filDatDir_".$randon.
                                        " WHERE tip_doc = '' OR doc = '' ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            

                            // correcto : actualizar el registro de procesos e insertar la data al consolidado
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_datos_direcciones=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();
                            
                            $sqlUpdateDoc = "UPDATE tmp_filDatDir_".$randon." tmp SET tmp.doc = IF(CONVERT(tmp.doc, UNSIGNED INTEGER)=0,tmp.doc,CONVERT(tmp.doc, UNSIGNED INTEGER));  ";
                            $prUpdateDoc = $connection->prepare($sqlUpdateDoc);
                            $prUpdateDoc->execute();
                            /*
                            $sqlUpdateDocumento = "UPDATE tmp_filDatDir_".$randon." tmp SET tmp.doc = IF(LENGTH(tmp.doc)<8,LPAD(tmp.doc,8,0),tmp.doc)  ;";
                            $prUpdateDocumento = $connection->prepare($sqlUpdateDocumento);
                            $prUpdateDocumento->execute();
                            */
                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filDatDir_".$randon." tmp on tmp.doc=dat.DOCUMENTO SET  dat.DEPARTAMENTO=tmp.departamento,dat.CIUDAD=tmp.ciudad,dat.DIRECCION=tmp.direccion, dat.TIPO_DIRECCION=tmp.tip_direccion";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();

                            $sqlUpdateNombreForUnicode = "UPDATE  data_preparacion_covinoc SET DIRECCION = REPLACE(REPLACE(DIRECCION,'?','Ñ'),'#','Ñ') ";
                            $prUpdateNombreForUnicode = $connection->prepare($sqlUpdateNombreForUnicode);
                            $prUpdateNombreForUnicode->execute();

                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.' ));
                            exit();

                            
                        }

                    }
                    break;
                case 'fileDatosEmails':
                    //procesando el archivo
                    $lineas = file('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile, FILE_SKIP_EMPTY_LINES );
                    $file_separado= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/'.$nameFile_separado, 'w');

                    for($j=0;$j<count($lineas);$j++){
                        for($i=0;$i<count($pauta_length_filDatEma);$i++){
                            if($i!=count($pauta_length_filDatEma)-1){
                                array_push($cadena_proceso, str_replace("\""," ",trim(substr($lineas[$j],$pauta_posicion_filDatEma[$i],$pauta_length_filDatEma[($i+1)]))));
                            }
                        }        
                        $cadena_final = implode("\t",$cadena_proceso);
                        array_push($array_importar, $cadena_final);
                        fwrite($file_separado,$array_importar[$j]."\n");
                        unset($cadena_proceso);
                        $cadena_proceso = array();
                    }
                    fclose($file_separado);
                    //echo json_encode(array('rst' => true, 'msg' => $array_importar));
                    $sqlCreateTemporary = "CREATE TEMPORARY TABLE tmp_filDatEma_".$randon." (".implode(',', $campos_tmp_filDatEma).") ENGINE = Aria DEFAULT CHARACTER SET = latin1";
                    $prCreateTemporary = $connection->prepare($sqlCreateTemporary);
                    if ( $prCreateTemporary->execute() ) {
                        $sqlLoadTemporary = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" .
                                $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/COVINOC/preparacion_archivos_planos".
                                 "/" . $nameFile_separado . 
                                "'INTO TABLE  tmp_filDatEma_".$randon.
                                " CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\n' ";
                        $prLoadTemporary = $connection->prepare($sqlLoadTemporary);
                        if ( $prLoadTemporary->execute() ){
                            //validaciones
                            $sqlData = "SELECT  count(*) AS COUNT from tmp_filDatEma_".$randon.
                                        " WHERE tip_doc = '' OR doc = '' ";
                            $prData = $connection->prepare($sqlData);
                            $prData->execute();
                            $data = $prData->fetchAll(PDO::FETCH_ASSOC);
                            if($data[0]['COUNT'] > 0){
                                echo json_encode(array('rst' => false, 'msg' => 'Verificar - El archivo contiene campos vacios.' ));
                                exit();
                            }
                            
                            // correcto : actualizar el registro de procesos e insertar la data al consolidado 
                            $sqlUpdateInProceso = "UPDATE proc_prep_covinoc SET fecha_modificacion = DATE(now()), usuario_modificacion= ".$usuario_creacion.", isload_datos_emails=1 WHERE usuario_creacion = ".$usuario_creacion." and fecha_creacion = DATE(now())";
                            $prUpdateInProceso = $connection->prepare($sqlUpdateInProceso);
                            $prUpdateInProceso->execute();

                            $sqlUpdateDoc = "UPDATE tmp_filDatEma_".$randon." tmp SET tmp.doc = IF(CONVERT(tmp.doc, UNSIGNED INTEGER)=0,tmp.doc,CONVERT(tmp.doc, UNSIGNED INTEGER));  ";
                            $prUpdateDoc = $connection->prepare($sqlUpdateDoc);
                            $prUpdateDoc->execute();
                            /*
                            $sqlUpdateDocumento = "UPDATE tmp_filDatEma_".$randon." tmp SET tmp.doc = IF(LENGTH(tmp.doc)<8,LPAD(tmp.doc,8,0),tmp.doc)  ;";
                            $prUpdateDocumento = $connection->prepare($sqlUpdateDocumento);
                            $prUpdateDocumento->execute();
                            */
                            $sqlInsertInConsolidado = "UPDATE  data_preparacion_covinoc dat INNER JOIN tmp_filDatEma_".$randon." tmp on tmp.doc=dat.DOCUMENTO SET dat.EMAIL=tmp.email";
                            $prInsertInConsolidado = $connection->prepare($sqlInsertInConsolidado);
                            $prInsertInConsolidado->execute();

                            $sqlUpdateDocImportant = "UPDATE data_preparacion_covinoc dat SET dat.DOCUMENTO=dat.CODCENT";
                            $prUpdateDocImportant = $connection->prepare($sqlUpdateDocImportant);
                            $prUpdateDocImportant->execute();

                            $sqlUpdateTotalDeudaAndInteres = "UPDATE data_preparacion_covinoc SET 
                                                                TOTAL_DEUDA = IFNULL(CAPITAL,0) + IFNULL(INTERES_MORA,0) + IFNULL(INTERES_CORRIENTE,0) + IFNULL(OTROS,0) ,
                                                                 INTERES = IFNULL(INTERES_CORRIENTE,0) + IFNULL(INTERES_MORA,0),
                                                                 SALDO_ACTUAL = CAST(IFNULL(SALDO_ACTUAL,0) AS DECIMAL(10,2)),
                                                                 MORA = CAST(IFNULL(MORA,0) AS DECIMAL(10,2)),
                                                                 CAPITAL =  CAST(IFNULL(CAPITAL,0) AS DECIMAL(10,2)),
                                                                 INTERES_CORRIENTE = CAST(IFNULL(INTERES_CORRIENTE,0) AS DECIMAL(10,2)),
                                                                 INTERES_MORA = CAST(IFNULL(INTERES_MORA,0) AS DECIMAL(10,2)),
                                                                 OTROS = CAST(IFNULL(OTROS,0) AS DECIMAL(10,2))";
                            $prUpdateTotalDeudaAndInteres = $connection->prepare($sqlUpdateTotalDeudaAndInteres);
                            $prUpdateTotalDeudaAndInteres->execute();


                            $sqlDATA = "SELECT * FROM data_preparacion_covinoc ;";
                            $prDATA = $connection->prepare($sqlDATA);
                            $prDATA->execute();
                            $arrayDATA = $prDATA->fetchAll(PDO::FETCH_ASSOC);
                            $file_download= fopen('../documents/carteras/COVINOC/preparacion_archivos_planos/file_download.txt', 'w');
                            $array_cabecera = array('TOTAL_DEUDA','INTERES','FPROCESO','CODCENT','MONEDA','TIPO_DOCUMENTO','DOCUMENTO','CONTRATO','OBLIGACION','NOMBRE','TIPO_DEUDOR',
                                'PRODUCTO','SALDO_ACTUAL','MORA','FECHA_CORTE','PORTAFOLIO','ORIGINADOR','SUB_PRODUCTO','CAPITAL','INTERES_CORRIENTE','INTERES_MORA','OTROS','FECHA_INICIO_MORA',
                                'TELEFONO','EXTENSION','TIPO_TELEFONO','CIUDAD','DEPARTAMENTO','DIRECCION','TIPO_DIRECCION','EMAIL');
                            $cab = implode("\t", $array_cabecera);

                            fwrite($file_download,$cab."\n");
                            for($i=0;$i<count($arrayDATA);$i++){
                                
                                $data = $arrayDATA[$i]['TOTAL_DEUDA']."\t".$arrayDATA[$i]['INTERES']."\t".$arrayDATA[$i]['FPROCESO']."\t".$arrayDATA[$i]['CODCENT']."\t".$arrayDATA[$i]['MONEDA']."\t".$arrayDATA[$i]['TIPO_DOCUMENTO']."\t".$arrayDATA[$i]['DOCUMENTO'].
                                "\t".$arrayDATA[$i]['CONTRATO']."\t".$arrayDATA[$i]['OBLIGACION']."\t".$arrayDATA[$i]['NOMBRE']."\t".$arrayDATA[$i]['TIPO_DEUDOR']."\t".
                                $arrayDATA[$i]['PRODUCTO']."\t".$arrayDATA[$i]['SALDO_ACTUAL']."\t".$arrayDATA[$i]['MORA']."\t".$arrayDATA[$i]['FECHA_CORTE']."\t".$arrayDATA[$i]['PORTAFOLIO']."\t".$arrayDATA[$i]['ORIGINADOR'].
                                "\t".$arrayDATA[$i]['SUB_PRODUCTO']."\t".$arrayDATA[$i]['CAPITAL']."\t".$arrayDATA[$i]['INTERES_CORRIENTE']."\t".$arrayDATA[$i]['INTERES_MORA']."\t".$arrayDATA[$i]['OTROS']."\t".$arrayDATA[$i]['FECHA_INICIO_MORA']."\t".$arrayDATA[$i]['TELEFONO']."\t".$arrayDATA[$i]['EXTENSION']."\t".
                                $arrayDATA[$i]['TIPO_TELEFONO']."\t".$arrayDATA[$i]['CIUDAD']."\t".$arrayDATA[$i]['DEPARTAMENTO']."\t".$arrayDATA[$i]['DIRECCION']."\t".$arrayDATA[$i]['TIPO_DIRECCION']."\t".$arrayDATA[$i]['EMAIL'];
                                
                                fwrite($file_download,$data."\n");
                            }
                            fclose($file_download);

                            echo json_encode(array('rst' => true, 'msg' => 'Complete - Paso la prueba.', 'isfile'=>true ));
                            exit();

                            
                        }

                    }
                    break;
                default:
                    break;
            }
        }
            
        
    }


    public function executeQuery($sql) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        ////$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function executeQueryReturn($sql) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function uploadDocumentCartera($_post, $_files) {
        $idFile = $_post['idTmpFile'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files[$idFile]['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files[$idFile]['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files[$idFile]['name']));
                $_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
                $this->limpiarCartera3($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files[$idFile]['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files[$idFile]['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files[$idFile]['name']));
                    $_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
                    $this->limpiarCartera3($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraNOC($_post, $_files) {
        if (@opendir('../documents/nocpredictivo/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraNOC']['tmp_name'], '../documents/nocpredictivo/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraNOC']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraNOC']['name'];
                //$this->limpiarCarteraNOC($_post);
                $this->limpiarCartera4($_post,'nocpredictivo');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/nocpredictivo/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraNOC']['tmp_name'], '../documents/nocpredictivo/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraNOC']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraNOC']['name'];
                    //$this->limpiarCarteraNOC($_post);
                    $this->limpiarCartera4($_post,'nocpredictivo');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraCourier($_post, $_files) {
        if (@opendir('../documents/currier_visitas/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraCourier']['tmp_name'], '../documents/currier_visitas/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCourier']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraCourier']['name'];

                $this->limpiarCartera4($_post,'currier_visitas');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/currier_visitas/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraCourier']['tmp_name'], '../documents/currier_visitas/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCourier']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraCourier']['name'];

                    $this->limpiarCartera4($_post,'currier_visitas');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }
    
    public function uploadDocumentCarteraEstadoCuenta($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraEstadoCuenta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraEstadoCuenta']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraEstadoCuenta']['name'];

                $this->limpiarCartera4($_post,'carteras');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraEstadoCuenta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraEstadoCuenta']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraEstadoCuenta']['name'];

                    $this->limpiarCartera4($_post,'carteras');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }
    
    public function uploadDocumentCarteraSaldoTotal($_post, $_files) {
        
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraSaldoTotal']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraSaldoTotal']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraSaldoTotal']['name'];

                $this->limpiarCartera4($_post,'carteras');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraSaldoTotal']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraSaldoTotal']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraSaldoTotal']['name'];

                    $this->limpiarCartera4($_post,'carteras');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
        
    }
    
    public function uploadDocumentCarteraDetalleM($_post, $_files) {




















        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraDetalleM']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalleM']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraDetalleM']['name'];

                $this->limpiarCartera4($_post,'carteras');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraDetalleM']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalleM']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraDetalleM']['name'];

                    $this->limpiarCartera4($_post,'carteras');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }

    }

    public function uploadDocumentFileDistribucionMecanico ( $_post, $_files ) {
        if( @opendir('../documents/carteras/'.$_post['NombreServicio']) ) {
            if(@move_uploaded_file($_files['fileDistribucionMecanica']['tmp_name'],'../documents/carteras/'.$_post['NombreServicio'].'/'.$_files['fileDistribucionMecanica']['name'])){
                $_post['file']=$_files['fileDistribucionMecanica']['name'];
                $this->limpiarCartera4($_post);
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));
            }

        }else{

            if( @mkdir('../documents/carteras/'.$_post['NombreServicio']) ){
                if(@move_uploaded_file($_files['fileDistribucionMecanica']['tmp_name'],'../documents/carteras/'.$_post['NombreServicio'].'/'.$_files['fileDistribucionMecanica']['name'])){
                    $_post['file']=$_files['fileDistribucionMecanica']['name'];
                    $this->limpiarCartera4($_post);
                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));
                }
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al crear directorio'));
            }

        }
    }

    public function uploadDocumentFileImportPCampania ( $_post, $_files ) {
        if( @opendir('../documents/pcampania/'.$_post['NombreServicio']) ) {
            if(@move_uploaded_file($_files['fileImportPCampania']['tmp_name'],'../documents/pcampania/'.$_post['NombreServicio'].'/'.$_files['fileImportPCampania']['name'])){
                $_post['file']=$_files['fileImportPCampania']['name'];
                $this->limpiarCartera4($_post,'pcampania');
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));
            }

        }else{

            if( @mkdir('../documents/pcampania/'.$_post['NombreServicio']) ){
                if(@move_uploaded_file($_files['fileImportPCampania']['tmp_name'],'../documents/pcampania/'.$_post['NombreServicio'].'/'.$_files['fileImportPCampania']['name'])){
                    $_post['file']=$_files['fileImportPCampania']['name'];
                    $this->limpiarCartera4($_post,'pcampania');
                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));
                }
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al crear directorio'));
            }

        }
    }

    public function loadHeaderDistribucionMecanico ( $_post ) {
        $cartera = @$_post['cartera'];
        if( @opendir('../documents/carteras/'.$_post['NombreServicio']) ) {
            if( @file_exists('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']) ) {
                $dataFile=@fopen('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file'],"r+");
                function MapArrayHeader ( $n ) {

                    $buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"',"?","Â¿","!","Â¡","[","]","-");
                    $cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");
                    return str_replace($buscar,$cambia,trim(utf8_encode($n)));
                }
                $dataHeader = array();
                if( $_post['separador'] == 'tab' ) {
                    $dataHeader=explode("\t",fgets($dataFile));
                }else{
                    $dataHeader=explode($_post['separador'],fgets($dataFile));
                }

                fclose($dataFile);

                if( count($dataHeader)==1 ){
                    echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto'));
                }else{
                    $dataHeaderMap=array_map("MapArrayHeader",$dataHeader);

                    echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
                }
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al leer archivo'));
            }
        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error en directorio'));
        }
    }

    public function uploadDocumentCarteraRetiro($_post, $_files) {
        if (@opendir('../documents/retiro/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraRetiro']['tmp_name'], '../documents/retiro/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRetiro']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraRetiro']['name'];
                //$this->limpiarCarteraRetiro($_post);
                $this->limpiarCartera4($_post,'retiro');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/retiro/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraRetiro']['tmp_name'], '../documents/retiro/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRetiro']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraRetiro']['name'];
                    //$this->limpiarCarteraRetiro($_post);
                    $this->limpiarCartera4($_post,'retiro');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCorteFocalizado($_post, $_files) {
        if (@opendir('../documents/corte_focalizado/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCorteFocalizado']['tmp_name'], '../documents/corte_focalizado/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCorteFocalizado']['name'])) {
                $_post['file'] = $_files['uploadFileCorteFocalizado']['name'];
                //$this->limpiarCarteraCorteFocalizado($_post);
                $this->limpiarCartera4($_post,'corte_focalizado');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/corte_focalizado/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCorteFocalizado']['tmp_name'], '../documents/corte_focalizado/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCorteFocalizado']['name'])) {
                    $_post['file'] = $_files['uploadFileCorteFocalizado']['name'];
                    //$this->limpiarCarteraCorteFocalizado($_post);
                    $this->limpiarCartera4($_post,'corte_focalizado');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentFacturacion($_post, $_files) {
        if (@opendir('../documents/facturacion_comision/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileFacturacion']['tmp_name'], '../documents/facturacion_comision/' . $_post['NombreServicio'] . '/' . $_files['uploadFileFacturacion']['name'])) {
                $_post['file'] = $_files['uploadFileFacturacion']['name'];
                $this->buildTableFacturacionComision(
                    $_files['uploadFileFacturacion']['name'],
                    $_post
                );
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            if (@mkdir('../documents/facturacion_comision/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileFacturacion']['tmp_name'], '../documents/facturacion_comision/' . $_post['NombreServicio'] . '/' . $_files['uploadFileFacturacion']['name'])) {
                    $_post['file'] = $_files['uploadFileFacturacion']['name'];
                    $this->buildTableFacturacionComision(
                        $_files['uploadFileFacturacion']['name'],
                        $_post
                    );
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    private function buildTableFacturacionComision($file,$_post)
    {
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $path = "../documents/facturacion_comision/" . $_post["NombreServicio"] . "/" . $file;
        $archivoParser = @fopen($path, "r+");
        $columMap = explode("\t", fgets($archivoParser));
        $columnasTabla = array();
        fclose($archivoParser);
        foreach ($columMap as $key => $value) {
            if($value == 'cnt_total' or $value == 'cnt_recuperados')
            {
                array_push($columnasTabla, '`'.$value.'` int');
            }
            else if(strcmp ($value, 'FECHA INICIO') == 0 or strcmp ($value, 'FECHA FIN') == 0)
            {
                array_push($columnasTabla, '`'.$value.'` date');
            }
            else if($value == 'mto_total' or $value == 'mto_recuperados')
            {
                array_push($columnasTabla, '`'.$value.'` decimal(10,2)');
            }else{
                array_push($columnasTabla, $value.' varchar(200)');
            }
        }
        /*array_push($columnasTabla, '`OBJETIVO_MONTO` int');
        array_push($columnasTabla, '`OBJETIVO_CLIENTE` int');
        array_push($columnasTabla, '`MONTO_RECUPERADO` decimal(10,2)');
        array_push($columnasTabla, '`CLIENTE_RECUPERADO` decimal(10,2)');
        array_push($columnasTabla, '`COMISION` decimal(10,1)');
        array_push($columnasTabla, '`FACTURACION` decimal(10,2)');
        array_push($columnasTabla, 'id int auto_increment primary key');*/
        $time = date("Y_m_d_H_i_s");
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $nameTable = "tmpfacturacion_" . session_id() . "_" . $time;
        $sqlDropTableTMPCartera = " DROP TABLE IF EXISTS $nameTable";
        $prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
        try {
            if ($prDropTableTMPCartera->execute()) {
                $sqlCreateTabelTMPCartera = " CREATE TABLE $nameTable ( ".implode(",", $columnasTabla)." ) ENGINE = InnoDB DEFAULT CHARACTER SET = latin1 ";
                //echo $sqlCreateTabelTMPCartera;
                $prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
                if ($prsqlCreateTabelTMPCartera->execute()) {
                    $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturacion_comision/" . $_post['NombreServicio'] . "/" . $file . "'
                                     INTO TABLE $nameTable FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                    $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
                    if ($prLoadDataInFileUC->execute()) {
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN OBJETIVO_MONTO int");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN OBJETIVO_CLIENTE int");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN MONTO_RECUPERADO decimal(10,2)");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN CLIENTE_RECUPERADO decimal(10,2)");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN COMISION decimal(10,1)");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN FACTURACION decimal(10,2)");
                        $connection->exec("ALTER TABLE $nameTable ADD COLUMN id int auto_increment primary key");
                        $this->updateObjetivosTablaTmpFacturacion($nameTable);
                        $this->calcClientMontosRecuperadosTablaTmpFacturacion($nameTable);
                        $this->calcularComisionesTablaTmpFacturacion($nameTable);
                        $this->calcularFacturacionTablaTmpFacturacion($nameTable);
                        echo json_encode(array('rst' => true, 'msg' => 'Archivo Procesado Correctamente','tabla' => $nameTable));
                    }
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    private function updateObjetivosTablaTmpFacturacion($nameTable)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $campo = 'objetivo_monto';
        $queryIncompleto = "UPDATE $nameTable
                INNER JOIN ca_clusters_maestros
                                ON $nameTable.cluster = ca_clusters_maestros.nombre
                INNER JOIN ca_tramos_maestros
                                ON $nameTable.tramo = ca_tramos_maestros.nombre
                INNER JOIN ca_objetivo
                        ON ca_objetivo.idtrama = ca_tramos_maestros.idtrama AND ca_objetivo.idcluma = ca_clusters_maestros.idcluma
                set ";
        $sql1 = $queryIncompleto.$campo." = porcentaje WHERE idtipoobj = 2";
        $campo = 'objetivo_cliente';
        $sql2 = $queryIncompleto.$campo." = porcentaje WHERE idtipoobj = 1";
        $sql3 = "UPDATE $nameTable SET objetivo_monto = objetivo_monto_modificado WHERE NOT objetivo_monto_modificado = ''";
        $sql4 = "UPDATE $nameTable SET objetivo_cliente = objetivo_cliente_modificado WHERE NOT objetivo_cliente_modificado = ''";
        $estado1 = $connection->exec($sql1);
        $estado2 = $connection->exec($sql2);
        $estado3 = $connection->exec($sql3);
        $estado4 = $connection->exec($sql4);
        if($estado1 === false or $estado2 === false or $estado3 === false or $estado4 === false)

        {
            throw new Exception('Ocurrio un error al actualizar los valores de los objetivos');
        }
    }
    private function calcularComisionesTablaTmpFacturacion($nameTable)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $queryComision = "SELECT porcentaje FROM ca_objetivo INNER JOIN ca_clusters_maestros ON ca_clusters_maestros.idcluma = ca_objetivo.idcluma INNER JOIN ca_tramos_maestros ON ca_tramos_maestros.idtrama.ca_objetivo.idtrama ";
        $sql1 = "UPDATE $nameTable
                INNER JOIN ca_clusters_maestros
                                ON $nameTable.cluster = ca_clusters_maestros.nombre
                INNER JOIN ca_tramos_maestros
                                ON $nameTable.tramo = ca_tramos_maestros.nombre
                INNER JOIN ca_comision_cluster_tramo
                        ON ca_comision_cluster_tramo.idtrama = ca_tramos_maestros.idtrama AND ca_comision_cluster_tramo.idcluma = ca_clusters_maestros.idcluma
                SET comision = porcentaje WHERE monto_recuperado between rangoObjMontoInicial AND rangoObjMontoFinal AND cliente_recuperado between rangoObjClieInicial and rangoObjClieFinal";
        $stm1 = $connection->prepare($sql1);
        if($stm1->execute())
        {
            $queryGetRegistrosConObjetivosCambiados = "SELECT id,objetivo_cliente,objetivo_monto,tramo,cluster FROM $nameTable WHERE NOT objetivo_cliente_modificado = '' OR NOT objetivo_monto_modificado = ''";
            $stm = $connection->prepare($queryGetRegistrosConObjetivosCambiados);
            $stm->execute();
            $arrObjetivosCambiados  = $stm->fetchAll();
            if(is_array($arrObjetivosCambiados) AND count($arrObjetivosCambiados) > 0)
            {
                foreach ($arrObjetivosCambiados as $key => $row) {
                    $objetivoY = $row['objetivo_cliente'];
                    $objetivoX = $row['objetivo_monto'];
                    $tramo = $row['tramo'];
                    $cluster = $row['cluster'];
                    $id = $row['id'];
                    $querySelectComisiones = "SELECT idcomiclutra,ca_comision_cluster_tramo.idtrama,ca_comision_cluster_tramo.idcluma,rangoObjClieInicial,rangoObjClieFinal,rangoObjMontoInicial,rangoObjMOntoFinal,porcentaje, 1 as idTmpFact FROM ca_comision_cluster_tramo
                        INNER JOIN ca_clusters_maestros ON ca_clusters_maestros.idcluma = ca_comision_cluster_tramo.idcluma
                        INNER JOIN ca_tramos_maestros ON ca_tramos_maestros.idtrama = ca_comision_cluster_tramo.idtrama
                        WHERE ca_clusters_maestros.nombre = '$cluster' AND ca_tramos_maestros.nombre = '$tramo'";
                    $stm = $connection->prepare($querySelectComisiones);
                    $stm->execute();
                    $arrRegComisiones = $stm->fetchAll(PDO::FETCH_ASSOC);
                    $arrRangosXY = array(
                        array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[0]['idcomiclutra']),
                        array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[1]['idcomiclutra']),
                        array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[2]['idcomiclutra']),
                        array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[3]['idcomiclutra']),
                        array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[4]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[5]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[6]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[7]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[8]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[9]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[10]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[11]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[12]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[13]['idcomiclutra']),
                        array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[14]['idcomiclutra'])
                    );
                    $queryDropTableTmpComision = "DROP TABLE IF EXISTS tmpComision";
                    $queryCreateTableTmp = "create table tmpComision($querySelectComisiones)";
                    $estadoDrop = $connection->exec($queryDropTableTmpComision);
                    $stm = $connection->prepare($queryCreateTableTmp);
                    //var_dump($stm->execute());
                    if($stm->execute())
                    {
                        foreach ($arrRangosXY as $key2 => $value) {
                            $rangoInicialY = $value['rangoInicialY'];
                            $rangoFinalY = $value['rangoFinalY'];
                            $rangoInicialX = $value['rangoInicialX'];
                            $rangoFinalX = $value['rangoFinalX'];
                            $idComiCluTra = $value['id'];
                            $queryUpdateTmpComisiones = "UPDATE tmpComision
                                SET rangoObjClieInicial = $rangoInicialY, rangoObjClieFinal = $rangoFinalY,rangoObjMontoInicial = $rangoInicialX,rangoObjMOntoFinal = $rangoFinalX
                            WHERE idcomiclutra = $idComiCluTra";
                            $estado = $connection->exec($queryUpdateTmpComisiones);
                        }
                        $queryUpdateComision = "UPDATE $nameTable
                            INNER JOIN ca_clusters_maestros
                                            ON $nameTable.cluster = ca_clusters_maestros.nombre
                            INNER JOIN ca_tramos_maestros
                                            ON $nameTable.tramo = ca_tramos_maestros.nombre
                            INNER JOIN tmpComision
                                    ON tmpComision.idtrama = ca_tramos_maestros.idtrama AND tmpComision.idcluma = ca_clusters_maestros.idcluma
                            SET comision = porcentaje WHERE monto_recuperado between rangoObjMontoInicial AND rangoObjMontoFinal AND cliente_recuperado between rangoObjClieInicial and rangoObjClieFinal AND id = $id";
                        $estado = $connection->exec($queryUpdateComision);
                    }
                }
            }
        }
    }
    private function calcularFacturacionTablaTmpFacturacion($nameTable)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sql = "UPDATE $nameTable SET facturacion = (comision * mto_recuperados)/100 WHERE NOT ISNULL(comision) AND NOT ISNULL(mto_recuperados)";
        $estado = $connection->exec($sql);
        if($estado === false)
        {
            throw new Exception('Ocurrio un error al calcular el monto de facturacion');
        }
    }
    private function calcClientMontosRecuperadosTablaTmpFacturacion($nameTable)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sql = "UPDATE $nameTable SET MONTO_RECUPERADO = (mto_recuperados/mto_total)*100,CLIENTE_RECUPERADO = (cnt_recuperados/cnt_total)*100";
        //$sql1 = "UPDATE $nameTable SET COMISION = '=SUMA(K1,L2)'";
        $estado = $connection->exec($sql);
        //$estado = $connection->exec($sql1);
        if($estado === false or $estado === 0){
            throw new Exception('No se pudo calcular los clientes y montos retirados correctamente');
        }
    }
    public function downloadFileFacturacion($nameTable)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=FACTURACION.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sql = "DESCRIBE $nameTable";
        $stm = $connection->prepare($sql);
        $tabla = '<table>';
        $indexCampoObjetivoClienteModificado = 0;
        $indexCampoObjetivoMontoModificado = 0;
        if($stm->execute())
        {
            $tabla .= '<tr>';
            foreach ($stm->fetchAll(PDO::FETCH_NUM) as $key => $row) {
                $porcentaje = '';
                if(strcasecmp($row[0], 'objetivo_cliente_modificado') == 0)$indexCampoObjetivoClienteModificado = $key;
                if(strcasecmp($row[0], 'objetivo_monto_modificado') == 0)$indexCampoObjetivoMontoModificado = $key;
                if(strcasecmp($row[0], 'MONTO_RECUPERADO') == 0 or strcasecmp($row[0], 'CLIENTE_RECUPERADO') == 0 or strcasecmp($row[0], 'OBJETIVO_CLIENTE') == 0 or strcasecmp($row[0], 'OBJETIVO_MONTO') == 0)
                {
                    $porcentaje = '%';
                }
                if(strcasecmp($row[0], 'objetivo_cliente_modificado') != 0 and strcasecmp($row[0], 'objetivo_monto_modificado') != 0)
                {
                    $tabla .= '<td>'.$row[0].' '.$porcentaje.'</td>';
                }
            }

            $tabla .= '</tr>';
            $sql = "SELECT * FROM $nameTable";
            $stm = $connection->prepare($sql);
            if($stm->execute())
            {
                foreach ($stm->fetchAll(PDO::FETCH_NUM) as $key => $row) {
                    $tabla .= '<tr>';
                    foreach ($row as $keyColumn => $value) {
                        if($indexCampoObjetivoClienteModificado != $keyColumn and $indexCampoObjetivoMontoModificado != $keyColumn)
                        {
                            $tabla .= '<td>'.$value.'</td>';
                        }
                    }
                    $tabla .= '</tr>';
                }
            }
            $sql = "DROP TABLE $nameTable";
            $connection->exec($sql);
            echo $tabla;

        }else{
            throw new Exception('No se pudo obtener los registros');
        }
    }
    public function actualizarCortesFocalizados($servicio, $files) {
        $rpt = array('msg' => '', 'resumen' => array(), 'rst' => false);
        $correcto = true;
        $files = explode(':', $files);
        //var_dump($files);
        $idsCuentas = '';
        for ($i = 0; $i < count($files); $i++) {
            if (!empty($files[$i])) {
                $path = "../documents/corte_focalizado/" . $servicio . "/" . $files[$i];
                if (!file_exists($path)) {
                    $error = array('msg' => 'Archivo ' . $files[$i] . ' no existe o fue removido, intente subir otra vez Archivos');
                    $correcto = false;
                    array_push($rpt['resumen'], $error);
                } else {
                    $archivo = @fopen($path, 'r+');
                    if ($archivo) {
                        $count = 0;
                        while (!feof($archivo)) {
                            $linea = fgets($archivo);
                            if ($count != 0) {
                                $arrLine = explode("|", $linea);
                                if (trim($arrLine[0]) != '') {
                                    $idsCuentas .= trim($arrLine[0]) . ',';
                                }
                            }
                            $count++;
                        }
                        fclose($archivo);
                    } else {
                        @fclose($archivo);
                        $error = array('msg' => 'Error al leer el archivo' . $files[$i]);
                        $correcto = false;
                        array_push($rpt['resumen'], $error);
                    }
                }
            }
        }
        $idsCuentas = substr($idsCuentas, 0, strlen($idsCuentas) - 1);
        $sql = "UPDATE ca_cuenta SET corte_focalizado = 1 WHERE idcuenta IN($idsCuentas)";
        //echo $sql;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $stm = $connection->prepare($sql);
        if ($stm->execute()) {
            $rpt['rst'] = true;
            //$connection->commit();
        }
        $rpt['msg'] = 'Se actualizaron ' . $stm->rowCount() . ' cuentas';
        echo json_encode($rpt);
    }

    public function uploadDocumentCarteraIVR($_post, $_files) {
        if (@opendir('../documents/ivr/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraIVR']['tmp_name'], '../documents/ivr/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraIVR']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraIVR']['name'];
                //echo json_encode(array('rst'=>true,'msg'=>'subir archivo al servidor'));
                //$this->limpiarCarteraIVR($_post);
                $this->limpiarCartera4($_post,'ivr');
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidorRRRRR'));
            }
        } else {
            if (@mkdir('../documents/ivr/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraIVR']['tmp_name'], '../documents/ivr/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraIVR']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraIVR']['name'];
                    //$this->limpiarCarteraIVR($_post);
                    $this->limpiarCartera4($_post,'ivr');
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor luego de crear carpeta'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentLimpiarCartera($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadLimpiarCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadLimpiarCartera']['name'])) {
                echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente', 'file' => $_files['uploadLimpiarCartera']['name']));
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadLimpiarCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadLimpiarCartera']['name'])) {
                    echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente', 'file' => $_files['uploadLimpiarCartera']['name']));
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraCentroPago($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraCentroPago']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCentroPago']['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
                $_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
                $this->limpiarCartera($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraCentroPago']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCentroPago']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
                    $_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
                    $this->limpiarCartera($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }
//~ Vic I
    public function uploadInsertarLlamadasManual($_post, $_files) {
        $nombre_archivo = date("Ymd_His")."_".$_files['uploadFileInsertarLlamada']['name'];
        if (@opendir('../documents/llamadas/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileInsertarLlamada']['tmp_name'], '../documents/llamadas/' . $_post['NombreServicio'] . '/'.$nombre_archivo)) {

                $_post['file'] = $nombre_archivo;
                $retornoLimpiar = $this->limpiarLlamadaManual($_post);
                if ($retornoLimpiar['rst']) 
                {
                    $nombre_archivo = $retornoLimpiar['file'];
                }
                else 
                {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al limpiar los datos del TXT'));
                    exit();
                }

                $factoryConnection = FactoryConnection::create('mysql');
                $connection = $factoryConnection->getConnection();
                $prUtf = $connection->prepare("TRUNCATE TABLE ca_llamada_manual");
                $prUtf->execute();

                $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
                $sqlLoadLlamada = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/llamadas/".$_post['NombreServicio']
                            ."/".$nombre_archivo."' INTO TABLE ca_llamada_manual CHARACTER SET utf8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES";
                $prLoadLlamada = $connection->prepare($sqlLoadLlamada);

                if ($prLoadLlamada->execute()) {
                    $prCountRegistro = $connection->prepare("SELECT COUNT(*) AS countNow FROM ca_llamada_manual");
                    $prCountRegistro->execute();
                    $rstCount = $prCountRegistro->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode(array('rst' => true, 'msg' => $rstCount[0]['countNow'].' Llamadas correctamente cargadas'));
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error de Datos, por favor verificar el TXT'));
                }

            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
        }
    }
    public function uploadCargaCuota($_post,$_files) {
        $create_hora = date("Ymd_His");
        $nombre_archivo = date("Ymd_His")."_".$_files['uploadFileCargaCuota']['name'];
        if (@opendir('../documents/cuota/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCargaCuota']['tmp_name'], '../documents/cuota/' . $_post['NombreServicio'] . '/'.$nombre_archivo)) {

                $factoryConnection = FactoryConnection::create('mysql');
                $connection = $factoryConnection->getConnection();

                $prClearCuotaTmp = $connection->prepare("CREATE TEMPORARY TABLE ca_cuotas_tmp_".$create_hora." LIKE ca_cuotas_tmp");
                $prClearCuotaTmp->execute();

                $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
/*              $sqlLoadCuota = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/cuota/".$_post['NombreServicio']
                            ."/".$nombre_archivo."' INTO TABLE ca_cuotas_tmp_".$create_hora." FIELDS TERMINATED BY '|' LINES  TERMINATED BY '\\r\\n' "
                            ."(num_contrato, @fecha_vencim, deuda_impagocap, deuda_impagoint, deuda_impago, deuda_impagocom, moneda) "
                            ."SET fecha_vencim = STR_TO_DATE(@fecha_vencim,'%d-%m-%Y'), idcartera=".$_post['idcartera'];*/
                $sqlLoadCuota = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/cuota/".$_post['NombreServicio']
                            ."/".$nombre_archivo."' INTO TABLE ca_cuotas_tmp_".$create_hora." FIELDS TERMINATED BY '|' LINES  TERMINATED BY '\\r\\n' "
                            ."(num_contrato, fecha_vencim, deuda_impagocap, deuda_impagoint, deuda_impago, deuda_impagocom, moneda) "
                            ."SET idcartera=".$_post['idcartera'];                            
                $prLoadCuota = $connection->prepare($sqlLoadCuota);

                if ($prLoadCuota->execute()) {

                    $sqlUpdateTmpCuota = "UPDATE ca_cuotas_tmp_".$create_hora." ct INNER JOIN ca_cuenta cu ON ct.num_contrato=cu.numero_cuenta AND ct.idcartera=cu.idcartera "
                                            ."SET ct.idcuenta=cu.idcuenta, ct.idcliente_cartera=cu.idcliente_cartera";
                    $prUpdateTmpCuota = $connection->prepare($sqlUpdateTmpCuota);
                    if ($prUpdateTmpCuota->execute()) {

                        $prUpdateCuota = $connection->prepare("UPDATE ca_cuotas SET estado=0 WHERE idcartera=".$_post['idcartera']);
                        if ($prUpdateCuota->execute()) {

                            $sqlInsertCuota = "INSERT INTO ca_cuotas (num_contrato, fecha_vencim, deuda_impagocap, deuda_impagoint, deuda_impago, deuda_impagocom, moneda, idcartera, idcuenta, idcliente_cartera) "
                                                ."SELECT num_contrato, fecha_vencim, deuda_impagocap, deuda_impagoint, deuda_impago, deuda_impagocom, moneda, idcartera, idcuenta, idcliente_cartera "
                                                ."FROM ca_cuotas_tmp_".$create_hora;
                            $prInsertCuota = $connection->prepare($sqlInsertCuota);
                            if ($prInsertCuota->execute()) {
                                echo json_encode(array('rst' => true, 'msg' => 'Cuotas Cargadas Correctamente'));
                            } else {
                                echo json_encode(array('rst' => false, 'msg' => 'Error en Insertar Cuotas'));
                            }

                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error en Actualizar el Estado de Cuotas'));
                        }

                    } else {
                        echo json_encode(array('rst' => false, 'msg' => 'Error en Actualizar Temporal'));
                    }

                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error en LOAD DATA INFILE'));
                }

            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
        }
    }
    public function newTelefonosManual($_post)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $registro = explode("^^", $_post['fonos']);

        foreach ($registro as $k => $v)
        {
            $row = explode("|", $v);
            $sqlNew = "INSERT INTO ca_telefono (idtelefono, idtipo_telefono, numero, numero_act, anexo, observacion, 
                            referencia, fecha_creacion, fecha_modificacion, usuario_creacion, usuario_modificacion, idorigen, 
                            idtipo_referencia, idcartera, idlinea_telefono, is_import, codigo_cliente, estado, is_new, is_active, 
                            idcliente_cartera, idcuenta, STATUS, u_peso, m_peso, idfinal, is_campo, is_carga)
                        VALUES (NULL, 2, '".$row[0]."', NULL, NULL, NULL, NULL, NOW(), NULL, 1, NULL, 1, 3, ".$row[1].", NULL, 0, '".$row[2]."', 
                            0, 0, 1, ".$row[3].", ".$row[4].", NULL, NULL, NULL, NULL, 0, 0)";
            $prInsert = $connection->prepare($sqlNew);
            if ($prInsert->execute())
            {
                $rst = true;
                $men = " Datos Guardados corrrectamente ";
            }
            else
            {
                $rst = false;
                $men = " Error de Insertar Telefonos ";
                break;
            }
        }
        echo json_encode(array('rst' => $rst, 'mensaje' => $men));
    }
    public function newInsertLlamadasManual($_post)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sqlInsertLlam = $this->sql_generar_llamadas($_post['cartera'], 'insertar',$_post['tipo_llamada']);
        $prInsertLlama = $connection->prepare($sqlInsertLlam);
        if ($prInsertLlama->execute())
        {
            $rstaUltima = $this->actualizarUltimaLlamadaManual($_post['cartera']);
            $rstaUltimaReal = $this->actualizarUltimaLlamadaManualReal($_post['cartera']);
            $rsmllamada=$this->actualizarMejorLlamada($_post['cartera'],$_post['Servicio']);
            $prullamada=$this->actualizarUltimaLlamada($_post['cartera']);
            if ( ($rstaUltima==true) AND ($rstaUltimaReal==true) AND ($rsmllamada==true) AND ($prullamada==true)) 
            {
                echo json_encode(array('rst' => true, 'mensaje' => "Se guardo correctamente las llamadas y se actualizaron las ultimas llamadas"));
            }
            else 
            {
                echo json_encode(array('rst' => false, 'mensaje' => "[ Error ] Actualizacion de la ultima llamada. Si se ingreso las llamadas"));
            }
        }
        else
        {
            echo json_encode(array('rst' => false, 'mensaje' => "[ Error ], vuelva a realizar el proceso nuevamente [ Error ]"));
        }
    }
    public function actualizarUltimaLlamadaManual($cartera)
    {
$sql = <<<EOT
        UPDATE ca_cliente_cartera clicar 
            INNER JOIN (
                SELECT * FROM
                    (
                        SELECT lla.idcliente_cartera, lla.fecha,lla.idllamada
                        FROM ca_cliente_cartera clicar 
                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera= clicar.idcliente_cartera
                        WHERE clicar.idcartera = {$cartera} 
                        ORDER BY lla.idcliente_cartera, lla.fecha DESC
                    ) t1 GROUP BY t1.idcliente_cartera
            ) tmp 
            ON tmp.idcliente_cartera = clicar.idcliente_cartera
        SET clicar.id_ultima_llamada_total = tmp.idllamada 
        WHERE clicar.idcartera = {$cartera} 
EOT;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $prUltimaLlamadaManual = $connection->prepare($sql);
        if ($prUltimaLlamadaManual->execute()) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    public function actualizarMejorLlamada($cartera,$servicio)
    {
$sql = <<<EOT
        update ca_cuenta cu inner join 
        (
        select * from
        (
        select lla.idcuenta, lla.fecha, fin.idcarga_final, lla.idfinal, lla.fecha_cp, lla.observacion, lla.idusuario_servicio, lla.idtelefono , finser.peso 
        from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser 
        on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera
        where clicar.idcartera = {$cartera} and finser.idservicio = {$servicio} AND lla.tipo<>'S'
        order by lla.idcuenta, finser.peso desc,lla.fecha DESC 
        ) t1 group by t1.idcuenta 
        ) tmp
        on tmp.idcuenta = cu.idcuenta 
        set
        cu.ml_fecha = tmp.fecha,
        cu.ml_carga = tmp.idcarga_final,
        cu.ml_estado = tmp.idfinal,
        cu.ml_fcpg = tmp.fecha_cp,
        cu.ml_observacion = tmp.observacion,
        cu.ml_operador = tmp.idusuario_servicio,
        cu.ml_telefono = tmp.idtelefono,
        cu.ml_peso_estado = tmp.peso
        where cu.idcartera = {$cartera}
EOT;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $prUltimaLlamadaManual = $connection->prepare($sql);
        if ($prUltimaLlamadaManual->execute()) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }    
    public function actualizarUltimaLlamada($cartera)
    {
$sql = <<<EOT
        update ca_cuenta cu inner join 
        (
        select * from
        (
        select lla.idcuenta, lla.fecha, fin.idcarga_final, lla.idfinal, lla.fecha_cp, lla.observacion, lla.idusuario_servicio, lla.idtelefono
        from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin on fin.idfinal = lla.idfinal and lla.idcliente_cartera = clicar.idcliente_cartera
        where clicar.idcartera = {$cartera} AND lla.tipo<>'S'
        order by lla.idcuenta, lla.fecha desc 
        ) t1 group by t1.idcuenta 
        ) tmp
        on tmp.idcuenta = cu.idcuenta 
        set
        cu.ul_fecha = tmp.fecha,
        cu.ul_carga = tmp.idcarga_final,
        cu.ul_estado = tmp.idfinal,
        cu.ul_fcpg = tmp.fecha_cp,
        cu.ul_observacion = tmp.observacion,
        cu.ul_operador = tmp.idusuario_servicio,
        cu.ul_telefono= tmp.idtelefono
        where cu.idcartera = {$cartera}
EOT;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $prUltimaLlamadaManual = $connection->prepare($sql);
        if ($prUltimaLlamadaManual->execute()) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }        
    public function actualizarUltimaLlamadaManualReal($cartera)
    {
$sql = <<<EOT
        UPDATE ca_cliente_cartera clicar 
            INNER JOIN (
                SELECT * FROM (
                    SELECT lla.idcliente_cartera, lla.fecha,lla.idllamada
                    FROM ca_cliente_cartera clicar 
                        INNER JOIN ca_llamada lla ON lla.idcliente_cartera= clicar.idcliente_cartera
                    WHERE clicar.idcartera = {$cartera} AND lla.idusuario_servicio<>'1' and lla.tipo<>'S'
                    ORDER BY lla.idcliente_cartera, lla.fecha DESC
                ) t1 GROUP BY t1.idcliente_cartera
            ) tmp ON tmp.idcliente_cartera = clicar.idcliente_cartera
        SET clicar.id_ultima_llamada = tmp.idllamada
        WHERE clicar.idcartera = {$cartera} 
EOT;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $prUltimaLlamadaManualReal = $connection->prepare($sql);
        if ($prUltimaLlamadaManualReal->execute()) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    public function cruceLlamada($_post)
    {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlExe = $this->sql_generar_llamadas($_post['cartera'], 'select',$_post['tipo_llamada']);
        $prLlama = $connection->prepare($sqlExe);
        if ($prLlama->execute())
        {
            $var = "";
            $telefono=array();
            $cuenta=array();
            $nroFono = "";
            $nroFonoInsertar = "";
            $nroCuenta = "";
            $rspta = $prLlama->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($rspta); $i++) {
                if ($rspta[$i]['idtelefono']==NULL)
                {
                    $telefono[$rspta[$i]['telefono']]=1;
                    $nroFono .= $rspta[$i]['telefono']."^^";
                    $nroFonoInsertar .= $rspta[$i]['telefono']."|".$_post['cartera']."|".$rspta[$i]['contrato']."|".$rspta[$i]['idcliente_cartera']."|".$rspta[$i]['idcuenta']."^^";
                }
                if ($rspta[$i]['idcuenta']==NULL)
                {
                    $cuenta[$rspta[$i]['contrato']]=1;
                    $nroCuenta .= $rspta[$i]['contrato'].", ";
                }
            }
            $countFono = array_sum($telefono);
            $countCuenta = array_sum($cuenta);
            if (($countFono==0) AND ($countCuenta==0)) 
            {
                $boton = "INSERTAR_LLAMA";
            }
            else 
            {
                $boton = "AGREGAR_FONO";
                $var .= "Telefonos que no existen:&nbsp;".array_sum($telefono)." ( ".str_replace("^^",", ",substr($nroFono, 0, -2))." )<br/>";
                $var .= "Cuentas que no existen:&nbsp;".array_sum($cuenta)." ( ".substr($nroCuenta, 0, -2)." )<br/>";
            }
            $var .= "Cantidad de Registros a Ingresar:&nbsp;".$prLlama->rowCount()."<br/>";
            $var .= "<label class='text-alert'>Nota: </label>Si no coinciden la cantidad de registro cargados, es por que el cliente no existe en la cartera";

            echo json_encode(array('rst' => true, 'mensaje' => $var, 'verBoton' => $boton, 'nroTelefonos' => substr($nroFonoInsertar, 0, -2)));
        }
        else 
        {
            $var = "Error en ejecutar la consulta";
            echo json_encode(array('rst' => false, 'mensaje' => "", 'verBoton' => "", 'nroTelefonos' => ""));
        }
    }
    public function listaTipificacionLlam($codServicio)
    {
        $sql = "SELECT fs.idfinal, f.nombre
                FROM ca_final_servicio fs 
                    INNER JOIN ca_final f ON fs.idfinal=f.idfinal
                WHERE fs.idservicio=? AND fs.estado=1 AND f.idclase_final=1
                ORDER BY f.nombre ASC ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $codServicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }
    public function sql_generar_llamadas($idServicio, $accion,$tipollamada)
    {
        if ($accion=='select')
        {
            $accionLlam = ', a.telefono, a.contrato ';
            $insertar = "";
        }
        else if($accion=='insertar')
        {
            $accionLlam = "";
            $insertar = "INSERT INTO ca_llamada (idllamada, idcliente_cartera, idfinal, idtipo_gestion, idcontacto, idmotivo_no_pago, idparentesco, idtelefono, "
                            ."idcuenta, fecha, inicio_tmo, fin_tmo, idusuario_servicio, enviar_campo, observacion, fecha_cp, monto_cp, moneda_cp, nombre_contacto, "
                            ."tipo, estado, fecha_creacion, fecha_modificacion, usuario_creacion, usuario_modificacion, status_cuenta, call_id) ";
        }
        else 
        {
            exit();
        }
/*
$sql = <<<EOT
        {$insertar}
        SELECT a.idllamada, a.idcliente_cartera, a.idfinal, a.idtipo_gestion, a.idcontacto, a.idmotivo_no_pago, a.idparentesco, IF(a.idtelefono IS NULL, 
            (SELECT t.idtelefono FROM ca_telefono t WHERE CAST(t.numero_act AS SIGNED)=CAST(a.telefono AS SIGNED) LIMIT 1), a.idtelefono ) AS idtelefono,
            a.idcuenta, a.fecha, a.inicio_tmo, a.fin_tmo, a.idusuario_servicio, a.enviar_campo, a.observacion, a.fecha_cp, a.monto_cp, a.moneda_cp,
            a.nombre_contacto, a.tipo, a.estado, a.fecha_creacion, a.fecha_modificacion, a.usuario_creacion, a.usuario_modificacion, a.status_cuenta,a.call_id
            {$accionLlam}
        FROM (
        SELECT NULL AS idllamada, cc.idcliente_cartera AS idcliente_cartera, {$idestado} AS idfinal, 2 AS idtipo_gestion, NULL AS idcontacto, NULL AS idmotivo_no_pago, 
            NULL AS idparentesco, 
            (SELECT t.idtelefono FROM ca_telefono t WHERE CAST(t.numero AS SIGNED)=CAST(i.telefono AS SIGNED) LIMIT 1) AS idtelefono,
            (SELECT u.idcuenta FROM ca_cuenta u WHERE u.numero_cuenta=i.contrato AND u.idcartera={$idServicio} AND u.retirado=0 LIMIT 1) AS idcuenta,
            CONCAT(i.fecha,' ',i.hora) AS fecha, NULL AS inicio_tmo, NULL AS fin_tmo, 1 AS idusuario_servicio,0 AS enviar_campo, 
            i.observa AS observacion, i.fechacp AS fecha_cp, i.montocp AS monto_cp, 
            (SELECT u.moneda FROM ca_cuenta u WHERE u.numero_cuenta=i.contrato AND u.idcartera={$idServicio} AND u.retirado=0 LIMIT 1) AS moneda_cp,
            i.contacto AS nombre_contacto, 'LL' AS tipo, 1 AS estado, CONCAT(i.fecha,' ',i.hora) AS fecha_creacion, 
            NULL AS fecha_modificacion, 1 AS usuario_creacion, NULL AS usuario_modificacion, NULL AS status_cuenta, NULL AS call_id,i.telefono,i.contrato
        FROM ca_llamada_manual i
            INNER JOIN ca_cliente c ON i.cliente=c.codigo
            INNER JOIN ca_cliente_cartera cc ON c.idcliente=cc.idcliente
        WHERE cc.idcartera={$idServicio} AND cc.estado=1
        ) a 
EOT;
*/


$sql = <<<EOT
        {$insertar}
        SELECT a.idllamada, a.idcliente_cartera, a.idfinal, a.idtipo_gestion, a.idcontacto, a.idmotivo_no_pago, a.idparentesco, 
            IF(a.idtelefono IS NULL, (SELECT t.idtelefono FROM ca_telefono t WHERE t.numero_act=a.telefono LIMIT 1),a.idtelefono ) AS idtelefono,
            a.idcuenta, a.fecha, a.inicio_tmo, a.fin_tmo, a.idusuario_servicio, a.enviar_campo, a.observacion, a.fecha_cp, a.monto_cp, a.moneda_cp,
            a.nombre_contacto, a.tipo, a.estado, a.fecha_creacion, a.fecha_modificacion, a.usuario_creacion, a.usuario_modificacion, a.status_cuenta,a.call_id 
            {$accionLlam}
        FROM (
        SELECT NULL AS idllamada, cc.idcliente_cartera AS idcliente_cartera, i.estado AS idfinal, 2 AS idtipo_gestion, NULL AS idcontacto, 
            NULL AS idmotivo_no_pago, NULL AS idparentesco, 
            (SELECT t.idtelefono FROM ca_telefono t WHERE t.numero=i.telefono LIMIT 1) AS idtelefono, cu.idcuenta AS idcuenta,
            CONCAT(i.fecha,' ',i.hora) AS fecha, NULL AS inicio_tmo, NULL AS fin_tmo, i.usuarios AS idusuario_servicio,0 AS enviar_campo, 
            i.observa AS observacion, IF(TRIM(i.fechacp)='', NULL, i.fechacp) AS fecha_cp, IF(TRIM(i.montocp)='', NULL, i.montocp) AS monto_cp, cu.moneda AS moneda_cp,
            i.contacto AS nombre_contacto, '$tipollamada' AS tipo, 1 AS estado, NOW() AS fecha_creacion, 
            NULL AS fecha_modificacion, i.usuarios AS usuario_creacion, NULL AS usuario_modificacion, NULL AS status_cuenta, NULL AS call_id,
            i.telefono,i.contrato
        FROM ca_llamada_manual i
            INNER JOIN ca_cliente_cartera cc ON i.cliente=cc.codigo_cliente AND cc.idcartera={$idServicio} 
            INNER JOIN ca_cuenta cu ON i.contrato=cu.numero_cuenta AND cc.idcartera=cu.idcartera and cc.idcliente_cartera=cu.idcliente_cartera and cu.idcartera={$idServicio}
        WHERE cc.idcartera={$idServicio} 
        ) a 
EOT;

        return $sql;
    }
//~ Vic F
    public function uploadDocumentCarteraPlanta($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraPlanta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPlanta']['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
                $_post['file'] = $_files['uploadFileCarteraPlanta']['name'];
                $this->limpiarCartera3($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraPlanta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPlanta']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
                    $_post['file'] = $_files['uploadFileCarteraPlanta']['name'];
                    $this->limpiarCartera3($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function loadHeaderCarteraPlanta($_post) {
        $path = '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {

                //$dataFile=file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);

                /*                 * *** */
                $archivo = file($path);

                //$tmpArchivo=fopen('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file'],'w');
//              fwrite($tmpArchivo,'');
//              fclose($tmpArchivo);
//
//              $countHeader=0;
//
//              $tmpArchivo=fopen($path,'a+');
//
//              for( $i=0;$i<count($archivo);$i++ ){
//                  if( $i==0 ) {
//                      $buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"');
//                      $cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'');
//                      $line=str_replace($buscar,$cambia,trim(utf8_encode($archivo[$i])));
//                      $explode_header=explode($_post['separator'],$line);
//                      //print_r($explode_header);
//                      for( $j=0;$j<count($explode_header);$j++ ) {
//                          if( $explode_header[$j]=='' ) {
//                              fclose($tmpArchivo);
//                              unlink($path);
//                              echo json_encode(array('rst'=>false,'msg'=>'Existen cabeceras vacias'));
//                              exit();
//                          }
//                      }
//                      $countHeader=count($explode_header);
//                      fwrite($tmpArchivo,$line);
//                  }else{
//                      $buscar=array('"',"'","#","&");
//                      $cambia=array('',"","","");
//                      //$line=str_replace("   ","|",$archivo[$i]);
//                      $line=str_replace($buscar,$cambia,trim(utf8_encode($archivo[$i])));
//                      $explode_line=explode($_post['separator'],$line);
//                      if( count($explode_line)!=$countHeader ) {
//                          fclose($tmpArchivo);
//                          unlink($path);
//                          echo json_encode(array('rst'=>false,'msg'=>'Linea '.($i+1).' no coincide con longitud de cabeceras'));
//                          exit();
//                      }
//                      fwrite($tmpArchivo,$line);
//                  }
//              }
//
//              fclose($tmpArchivo);
//              //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente'));
//
//              /******/
//              $archivo = file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataHeader = explode($_post['separator'], $archivo[0]);
                //$dataHeaderMap=array_map("MapArrayHeader",$dataHeader);
                echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeader));
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function uploadDocumentCarteraPago2($_post, $files) {

        if (opendir('../documents/carteras/TG_FIJA')) {

            if (move_uploaded_file($files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/TG_FIJA/' . $_post['uploadFileCarteraPagoMain']['name'])) {

            } else {

            }
        } else {

        }
    }

    public function uploadDocumentCarteraPago($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPagoMain']['name'])) {

                $_post['file'] = $_files['uploadFileCarteraPagoMain']['name'];
                if ($_post['idCabecera'] == '0') {
                    //$this->limpiarCarteraPago($_post);
                    $this->limpiarCartera4($_post);

                } else {
                    $this->limpiarCarteraPagoAddHeader($_post);
                }
            } else {
                //echo $_files['uploadFileCarteraPagoMain']['name'];
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor, (Abrir carpeta)'));
            }
        } else {
            if (mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (move_uploaded_file($_files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPagoMain']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraPagoMain']['name'];
                    if ($_post['idCabecera'] == '0') {
                        $this->limpiarCarteraPago($_post);
                    } else {
                        $this->limpiarCarteraPagoAddHeader($_post);
                    }
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor,( Crear carpeta )'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraTelefono($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraTelefono']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraTelefono']['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraPago']['name']));
                $_post['file'] = $_files['uploadFileCarteraTelefono']['name'];
                //$this->limpiarCartera($_post);
                $this->limpiarCartera4($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraTelefono']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraTelefono']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraPago']['name']));
                    $_post['file'] = $_files['uploadFileCarteraTelefono']['name'];
                    //$this->limpiarCartera($_post);
                    $this->limpiarCartera4($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraPrincipal($_post, $_files) {

        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            
            //echo $_files['uploadFileCartera']['tmp_name']."-".'../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCartera']['name'];


            // $message = 'Error uploading file';
            // switch( $_files['uploadFileCartera']['error'] ) {
            //     case UPLOAD_ERR_OK:
            //         $message = false;;
            //         break;
            //     case UPLOAD_ERR_INI_SIZE:
            //     case UPLOAD_ERR_FORM_SIZE:
            //         $message .= ' - file too large (limit of  bytes).';
            //         break;
            //     case UPLOAD_ERR_PARTIAL:
            //         $message .= ' - file upload was not completed.';
            //         break;
            //     case UPLOAD_ERR_NO_FILE:
            //         $message .= ' - zero-length file uploaded.';
            //         break;
            //     default:
            //         $message .= ' - internal error #'.$_files['uploadFileCartera']['error'];
            //         break;
            // }
            // if( !$message ) {
            //     if( !is_uploaded_file($_files['uploadFileCartera']['tmp_name']) ) {
            //         $message = 'Error uploading file - unknown error.';
            //     } else {
            //         // Let's see if we can move the file...
            //         $dest .= '/'.$this_file;
            //         if( !move_uploaded_file($_files['uploadFileCartera']['tmp_name'], $dest) ) { // No error supporession so we can see the underlying error.
            //             $message = 'Error uploading file - could not save upload (this will probably be a permissions problem in '.$dest.')';
            //         } else {
            //             $message = 'File uploaded okay.';
            //         }
            //     }
            // }

            // echo $message;

            if (@move_uploaded_file($_files['uploadFileCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCartera']['name'])) {
                
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                $_post['file'] = $_files['uploadFileCartera']['name'];
                //$this->limpiarCartera2($_post);
                if ($_post['ModoCarga'] == '0') {
                    /* if( $_post['idCabecera'] != '0' ) {
                      $this->limpiarCarteraAgregarCabecera($_post);
                      }else{ */
                    $this->limpiarCartera4($_post);
                    //}
                } else if ($_post['ModoCarga'] == 'agregar') {
                    //$this->limpiarCarteraSoloAgregarCabecera($_post);
                    if($_POST['Servicio']==1){
                        //$this->limpiarCarteraSoloAgregarCabecera($_post);
                        $this->limpiarCartera4($_post);
                    }
                } else if ($_post['ModoCarga'] == 'agregar_dividir') {
                    // $this->limpiarCarteraAgregarCabecera($_post);
                    if($_POST['Servicio']==1){
                        $this->limpiarCartera4($_post);
                    }
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor XX'));
            }
        } else {
            
            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCartera']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                    $_post['file'] = $_files['uploadFileCartera']['name'];
                    //$this->limpiarCartera2($_post);
                    //$this->limpiarCartera3($_post);
                    /* if( $_post['idCabecera'] != '0' ) {
                      $this->limpiarCarteraAgregarCabecera($_post);
                      }else{
                      $this->limpiarCartera3($_post);
                      } */
                    if ($_post['ModoCarga'] == '0') {
                        $this->limpiarCartera4($_post);
                    } else if ($_post['ModoCarga'] == 'agregar') {
                        $this->limpiarCarteraSoloAgregarCabecera($_post);
                    } else if ($_post['ModoCarga'] == 'agregar_dividir') {
                        $this->limpiarCarteraAgregarCabecera($_post);
                    }
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor YY'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraDetalle($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraDetalle']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalle']['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                $_post['file'] = $_files['uploadFileCarteraDetalle']['name'];
                //$this->limpiarCartera2($_post);
                $this->limpiarCartera3($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraDetalle']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalle']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                    $_post['file'] = $_files['uploadFileCarteraDetalle']['name'];
                    //$this->limpiarCartera2($_post);
                    $this->limpiarCartera3($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraReclamo($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraReclamo']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraReclamo']['name'])) {
                //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                $_post['file'] = $_files['uploadFileCarteraReclamo']['name'];
                //$this->limpiarCartera2($_post);
                $this->limpiarCartera4($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraReclamo']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraReclamo']['name'])) {
                    //echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
                    $_post['file'] = $_files['uploadFileCarteraReclamo']['name'];
                    //$this->limpiarCartera2($_post);
                    $this->limpiarCartera4($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function uploadDocumentCarteraRRLL($_post, $_files) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@move_uploaded_file($_files['uploadFileCarteraRRLL']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRRLL']['name'])) {
                $_post['file'] = $_files['uploadFileCarteraRRLL']['name'];
                $this->limpiarCartera3($_post);
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
            }
        } else {

            if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
                if (@move_uploaded_file($_files['uploadFileCarteraRRLL']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRRLL']['name'])) {
                    $_post['file'] = $_files['uploadFileCarteraRRLL']['name'];
                    $this->limpiarCartera3($_post);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
            }
        }
    }

    public function loadHeader($_post) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                $dataHeader = explode($_post['separator'], fgets($dataFile));
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }
    public function loadHeader2($_post) {
        //echo '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'];
        //exit();
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");
                //echo fgets($dataFile);
                function MapArrayHeader($n) {

                    $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                //$dataHeader=explode($_post['separator'],fgets($dataFile));
                $dataHeader = array();
                if ($_post['separator'] == 'tab') {
                    $dataHeader = explode("\t", fgets($dataFile));
                } else {

                    $dataHeader = explode($_post['separator'], fgets($dataFile));
                }
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto '.$_post['NombreServicio']));
                } else {
                    // print_r($dataHeader);
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * *************** */
                    $sql = "    SELECT 
                                idjson_parser, cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono,direccion,adicionales,codigo_cliente,numero_cuenta, codigo_operacion, separador
                                FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser DESC ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    
                    $data_ranking = array();
                    $data_c = array();
                    $data_p = array();
                    for( $i=0;$i<count($rs);$i++ ) {
                        $cabeceras =explode(",",$rs[$i]['cabeceras']);

                        $notHeader = array_values( array_diff( $dataHeaderMap, $cabeceras ) );

                        if( count($notHeader) == 0 ) {
                            echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$notHeader,'dataJsonParserBefore'=>$rs[$i]));
                            exit();
                        }else{
                            array_push($data_ranking,array("cantidad"=>count($notHeader),"posicion"=>$i,"notHeader"=>$notHeader));
                            array_push($data_c,count($notHeader));
                            array_push($data_p,$i);
                        }

                    }

                    array_multisort($data_c,SORT_ASC,$data_p,SORT_ASC,$data_ranking);

                    if( count($data_ranking) > 0 ) {

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$data_ranking[0]['notHeader'],'dataJsonParserBefore'=>$rs[$data_ranking[0]['posicion']]));
                    }else{

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$dataHeaderMap,'dataJsonParserBefore'=>array()));
                    }
                    /**********************/

                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function loadHeaderTelefonoloadHeader2($_post) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                //$dataHeader=explode($_post['separator'],fgets($dataFile));
                $dataHeader = array();
                if ($_post['separator'] == 'tab') {
                    $dataHeader = explode("\t", fgets($dataFile));
                } else {
                    $dataHeader = explode($_post['separator'], fgets($dataFile));
                }
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'.$_post['NombreServicio']));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * *************** */
                    $sql = " SELECT idjson_parser, cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono,
                        direccion,adicionales,codigo_cliente,numero_cuenta, codigo_operacion, separador
                        FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser DESC ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    
                    $data_ranking = array();
                    $data_c = array();
                    $data_p = array();
                    for( $i=0;$i<count($rs);$i++ ) {
                        $cabeceras =explode(",",$rs[$i]['cabeceras']);
                        $notHeader = array_values( array_diff( $dataHeaderMap, $cabeceras ) );

                        if( count($notHeader) == 0 ) {
                            echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$notHeader,'dataJsonParserBefore'=>$rs[$i]));
                            exit();
                        }else{
                            array_push($data_ranking,array("cantidad"=>count($notHeader),"posicion"=>$i,"notHeader"=>$notHeader));
                            array_push($data_c,count($notHeader));
                            array_push($data_p,$i);
                        }

                    }

                    array_multisort($data_c,SORT_ASC,$data_p,SORT_ASC,$data_ranking);

                    if( count($data_ranking) > 0 ) {

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$data_ranking[0]['notHeader'],'dataJsonParserBefore'=>$rs[$data_ranking[0]['posicion']]));
                    }else{

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$dataHeaderMap,'dataJsonParserBefore'=>array()));
                    }
                    /**********************/

                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    /*     * ************* */

    public function loadHeaderPago($_post) {
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                $dataHeader = array();
                if ($_post['separator'] == 'tab') {
                    $dataHeader = explode("\t", fgets($dataFile));
                } else {
                    $dataHeader = explode($_post['separator'], fgets($dataFile));
                }

                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * ********** */
                    $sql = " SELECT idjson_parser_pago,codigo_cliente,numero_cuenta,codigo_operacion,pago,cabeceras
                        FROM ca_json_parser_pago WHERE idservicio = ? ORDER BY idjson_parser_pago DESC ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    ////$connection->beginTransaction();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    /*$cabeceras = explode(",", ((count($rs)>0)?$rs[0]['cabeceras']:"") );
                    $notHeader = array();
                    for ($i = 0; $i < count($dataHeaderMap); $i++) {
                        if (!in_array($dataHeaderMap[$i], $cabeceras)) {

                            array_push($notHeader, $dataHeaderMap[$i]);
                        }
                    }

                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => ((count($rs)>0)?$rs[0]:array())));*/

                    /******************/
                    $data_ranking = array();
                    $data_c = array();
                    $data_p = array();
                    for( $i=0;$i<count($rs);$i++ ) {
                        $cabeceras =explode(",",$rs[$i]['cabeceras']);
                        $notHeader = array_values(array_diff( $dataHeaderMap, $cabeceras ));

                        if( count($notHeader) == 0 ) {

                            echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$notHeader,'dataJsonParserBefore'=>$rs[$i]));
                            exit();
                        }else{
                            array_push($data_ranking,array("cantidad"=>count($notHeader),"posicion"=>$i,"notHeader"=>$notHeader));
                            array_push($data_c,count($notHeader));
                            array_push($data_p,$i);
                        }

                    }

                    array_multisort($data_c,SORT_ASC,$data_p,SORT_ASC,$data_ranking);

                    if( count($data_ranking) > 0 ) {

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$data_ranking[0]['notHeader'],'dataJsonParserBefore'=>$rs[$data_ranking[0]['posicion']]));
                    }else{

                        echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap,'headerNot'=>$dataHeaderMap,'dataJsonParserBefore'=>array()));
                    }
                    /******************/

                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function loadHeaderDetalle($_post) {
        $cartera = $_post['cartera'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                $dataHeader = explode($_post['separator'], fgets($dataFile));
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * ********** */
                    //$sql = " SELECT car.idcartera, car.numero_cuenta, car.moneda_cuenta,car.detalle_cuenta, car.adicionales, car.cabeceras
//                      FROM ca_servicio ser INNER JOIN ca_campania cam INNER JOIN ca_cartera car
//                      ON car.idcampania = cam.idcampania AND cam.idservicio = ser.idservicio
//                      WHERE ser.idservicio = ? AND idcartera != ?
//                      ORDER BY car.idcartera DESC LIMIT 1 ";

                    $sql = " SELECT numero_cuenta_detalle, moneda_detalle, codigo_operacion_detalle, detalle_cuenta, adicionales, cabeceras_detalle FROM ca_json_parser WHERE idservicio = ? AND idjson_parser != ( SELECT MAX(idjson_parser) FROM ca_json_parser WHERE idservicio = ? ) ORDER BY idjson_parser DESC LIMIT 1 ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    ////$connection->beginTransaction();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->bindParam(2, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    //$pr->bindParam(2,$cartera,PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    $cabeceras = explode(",", $rs[0]['cabeceras_detalle']);
                    $notHeader = array();
                    for ($i = 0; $i < count($dataHeaderMap); $i++) {
                        if (!in_array($dataHeaderMap[$i], $cabeceras)) {
                            array_push($notHeader, $dataHeaderMap[$i]);
                        }
                    }
                    /*                     * ********** */
                    //echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function loadHeaderReclamo($_post) {
        $cartera = @$_post['cartera'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                $dataHeader = array();
                if ($_post['separator'] == 'tab') {
                    $dataHeader = explode("\t", fgets($dataFile));
                } else {
                    $dataHeader = explode($_post['separator'], fgets($dataFile));
                }
                //$dataHeader=explode($_post['separator'],fgets($dataFile));
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * ********** */
                    $sql = " SELECT carre.idcartera_reclamo ,carre.reclamo, carre.cabeceras
                        FROM ca_cartera_reclamo carre INNER JOIN ca_cartera car INNER JOIN ca_campania cam
                        ON cam.idcampania = car.idcampania AND car.idcartera = carre.idcartera
                        WHERE cam.idservicio = ? ORDER BY carre.idcartera_reclamo DESC LIMIT 1 ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    ////$connection->beginTransaction();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    $cabeceras = explode(",", ((count($rs)>0)?$rs[0]['cabeceras']:"") );
                    $notHeader = array();
                    for ($i = 0; $i < count($dataHeaderMap); $i++) {
                        if (!in_array($dataHeaderMap[$i], $cabeceras)) {
                            array_push($notHeader, $dataHeaderMap[$i]);
                        }
                    }
                    /*                     * ********** */
                    //echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => ( (count($rs)>0)?$rs[0]:array() ) ));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function loadHeaderRRLL($_post) {
        $cartera = $_post['cartera'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                $dataHeader = explode($_post['separator'], fgets($dataFile));
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * ********** */
                    $sql = " SELECT rrll.idcartera_rrll ,rrll.rrll, rrll.cabeceras
                        FROM ca_cartera_rrll rrll INNER JOIN ca_cartera car INNER JOIN ca_campania cam
                        ON cam.idcampania = car.idcampania AND car.idcartera = rrll.idcartera
                        WHERE cam.idservicio = ? ORDER BY rrll.idcartera_rrll DESC LIMIT 1 ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    ////$connection->beginTransaction();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);

                    $cabeceras = explode(",", $rs[0]['cabeceras']);
                    $notHeader = array();
                    for ($i = 0; $i < count($dataHeaderMap); $i++) {
                        if (!in_array($dataHeaderMap[$i], $cabeceras)) {
                            array_push($notHeader, $dataHeaderMap[$i]);
                        }
                    }

                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function loadHeaderTelefono($_post) {
        //$cartera = $_post['cartera'];
        if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
                //$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
                $dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

                function MapArrayHeader($n) {

                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    return str_replace($buscar, $cambia, trim(utf8_encode($n)));
                }

                //$dataHeader=explode($_post['separator'],$dataFile[0]);
                /*                 * ************ */
                $dataHeader = array();
                if ($_post['separator'] == 'tab') {
                    $dataHeader = explode("\t", fgets($dataFile));
                } else {
                    $dataHeader = explode($_post['separator'], fgets($dataFile));
                }
                /*                 * ************ */
                //$dataHeader=explode($_post['separator'],fgets($dataFile));
                fclose($dataFile);

                if (count($dataHeader) == 1) {
                    echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
                } else {
                    $dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
                    /*                     * ********** */
                    $sql = " SELECT carte.idcartera_telefono ,carte.telefono, carte.cabeceras
                        FROM ca_cartera_telefono carte INNER JOIN ca_cartera car INNER JOIN ca_campania cam
                        ON cam.idcampania = car.idcampania AND car.idcartera = carte.idcartera
                        WHERE cam.idservicio = ? ORDER BY carte.idcartera_telefono DESC LIMIT 1 ";

                    $factoryConnection = FactoryConnection::create('mysql');
                    $connection = $factoryConnection->getConnection();

                    ////$connection->beginTransaction();

                    $pr = $connection->prepare($sql);
                    $pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
                    $pr->execute();
                    $rs = $pr->fetchAll(PDO::FETCH_ASSOC);
                    if( count($rs)==0 ) {
                        echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $dataHeaderMap, 'dataJsonParserBefore' => array()));
                        exit();
                    }

                    $cabeceras = explode(",", $rs[0]['cabeceras']);
                    $notHeader = array();
                    for ($i = 0; $i < count($dataHeaderMap); $i++) {
                        if (!in_array($dataHeaderMap[$i], $cabeceras)) {
                            array_push($notHeader, $dataHeaderMap[$i]);
                        }
                    }
                    echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));

                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    /*     * ************* */

    public function limpiarCartera($_post) {

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = file($path);

        $tmpArchivo = fopen($path, 'w');
        fwrite($tmpArchivo, '');
        fclose($tmpArchivo);

        $countHeader = 0;

        $tmpArchivo = fopen($path, 'a+');

        for ($i = 0; $i < count($archivo); $i++) {

            if ($i == 0) {
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "");
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"',"?","Â¿","!","Â¡","[","]");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","");
                $line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
                $explode_header = explode("|", $line);

                for ($j = 0; $j < count($explode_header); $j++) {
                    if ($explode_header[$j] == '') {
                        fclose($tmpArchivo);
                        unlink($path);
                        echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias (LC)'));
                        exit();
                    }
                }
                $countHeader = count($explode_header);
                fwrite($tmpArchivo, $line . "\r\n");
            } else {
                $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", "Â¥");
                $cambia = array('', "", "|", "", "", "", "", "", "", "N");
                $line = str_replace("   ", "|", $archivo[$i]);
                $line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
                //$explode_line=explode("|",$line);
//              if( count($explode_line)!=$countHeader ) {
//                  fclose($tmpArchivo);
//                  //unlink($path);
//                  echo json_encode(array('rst'=>false,'msg'=>'Linea '.($i+1).' tiene demasiados datos'));
//                  exit();
//              }
                fwrite($tmpArchivo, $line . "\r\n");
            }
        }

        fclose($tmpArchivo);
        echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $_post['file']));
    }

    public function limpiarCartera2($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/TMP" . $_post["file"], 'a+');
        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
            exit();
        }

        $archivo = @fopen($path, "r+");

        $countHeader = 0;

        /* $struc=array(
          "NROINS"=>array(0,15),"CLIENTE"=>array(15,10),"CUENTA"=>array(25,10),
          "COD_ESTA_CD"=>array(35,2),"MOT_ESTA_CMR_CD"=>array(37,9),"COD_NAT_CTA_CD"=>array(46,9),
          "COD_SGM_CTA_CD"=>array(55,9),"COD_SBG_CTA_CD"=>array(64,9),"IND_STP_FTC_CD"=>array(73,1),
          "IND_CLI_ITB_IN"=>array(74,1),"NUM_INDE_UN"=>array(75,40),"COD_TIP_DOC_CD"=>array(115,2),
          "NUM_DOC_CD"=>array(117,30),"COD_EMP_EXT_CD"=>array(147,9),"AGR_COD_FTC_CD"=>array(156,9),
          "COD_UN_MED_CD"=>array(165,9),"COD_ESTA_IDE_CD"=>array(174,2),"MTO_TOT_IM"=>array(176,19),
          "MTO_PDO_IM"=>array(195,19),"MTO_PDU_IM"=>array(214,19),"MTO_EXI_IM"=>array(233,19),
          "MTO_FCN_IM"=>array(252,19),"MTO_ENV_PVS_SN"=>array(271,19),"MTO_ENV_PDA_SN"=>array(290,19),
          "FEC_EMI_DOC_FF"=>array(309,10),"FEC_VNC_DOC_FF"=>array(319,10),"IND_ESTA_DOC_IN"=>array(329,1),
          "FEC_CCL_DOC_FF"=>array(330,10),"COD_ESC_GES_CD"=>array(340,9),"FEC_ING_GES_FF"=>array(349,10),
          "NOM_FCH_ORI_MO"=>array(359,20),"DES_CLI_DS"=>array(379,140),"S_TIP_CAL_ATI_CD"=>array(519,2),
          "S_NOM_CAL_DS"=>array(521,40),"A_NUM_CAL_UN"=>array(561,6),"S_DSC_CMP_PRI_DS"=>array(567,40),
          "S_DSC_CMP_SEG_DS"=>array(607,40),"S_COD_POS_CD"=>array(647,10),"S_DIRE_CIU_CD"=>array(657,9),
          "S_PRC_TIP_PRO_CMR_CD"=>array(666,10),"S_PRC_SBT_PRO_CMR_CD"=>array(676,9),"CUT_TIP_DOC_CD"=>array(685,3),
          "CUT_NIM_DOC_CU_CD"=>array(688,20),"NOM_ARE_GEO_NO"=>array(708,20),"NOM_DEU_NFAC_NUM"=>array(728,19),
          "COD_EXT_FNX_CD"=>array(747,1)); */

        $struc = array(
            "CLIENTE" => array(0, 10),
            "CUENTA" => array(10, 10),
            "INSCRIPCION" => array(20, 10),
            "TELEFONO" => array(30, 15),
            "GESTION" => array(45, 30),
            "FECHA_INICIO_GESTION" => array(75, 10),
            "FECHA_FIN_GESTION" => array(85, 10),
            "DESCRIPCION_AGENCIA" => array(95, 20),
            "CODIGO_ESTADO_PC" => array(115, 4),
            "DESCRIPCION_ESTADO_PC" => array(119, 10),
            "ESTADO_RECLAMO" => array(129, 15),
            "ESTADO_RESULTADO_RECLAMO" => array(144, 20),
            "MONTO_FUNDADO_RECLAMO" => array(164, 12),
            "NOMBRE_EMPRESA" => array(176, 30),
            "CODIGO_AGRUPACION" => array(206, 4),
            "CODIGO_SEGMENTO_CTA" => array(210, 4),
            "DESCRIPCION_TIPO_DOCUMENTO" => array(214, 30),
            "DESCRIPCION_AGRUPACION" => array(244, 15),
            "DESCRIPCION_SEGMENTO_CTA" => array(259, 30),
            "NEGOCIO" => array(289, 4),
            "CODIGO_TIPO_DOCUMENTO" => array(293, 2),
            "NUMERO_DOCUMENTO" => array(295, 13),
            "FECHA_CICLO" => array(308, 10),
            "FECHA_EMISION" => array(318, 10),
            "FECHA_VENCIMIENTO" => array(328, 10),
            "MONTO_EXIGIBLE" => array(338, 12),
            "MONTO_PAGADO" => array(350, 12),
            "MONTO_TOTAL" => array(362, 12),
            "MONTO_AJUSTADO" => array(374, 12),
            "MONTO_FINANCIADO" => array(386, 12),
            "MONTO_DISPUTA" => array(398, 12),
            "FECHA_ALTA_PC" => array(410, 10),
            "FECHA_BAJA_PC" => array(420, 10),
            "NOMBRES" => array(430, 100),
            "CODIGO_TIPO_DOCUMENTO_DNI" => array(530, 3),
            "NUMERO_DOCUMENTO_DNI" => array(533, 17),
            "VIA" => array(550, 3),
            "CALLE" => array(553, 43),
            "NUMERO" => array(596, 6),
            "DIRECCION" => array(602, 100),
            "PROVINCIA" => array(702, 20),
            "AREA" => array(722, 10),
            "DESCRIPCION_ZONAL" => array(732, 3),
            "CODIGO_SUB_LOCALIDAD" => array(735, 10),
            "DESCRIPCION_SUB_LOCALIDAD" => array(745, 30),
            "CODIGO_POSTAL" => array(775, 10),
            "CDM" => array(785, 4),
            "CODIGO_SUB_TIPO_PC" => array(789, 4),
            "DESCRIPCION_SUB_TIPO_PC" => array(793, 50),
            "CODIGO_LOCALIDAD" => array(843, 10),
            "DESCRIPCION_LOCALIDAD" => array(853, 30),
            "TIPO_SPEEDY" => array(883, 50),
            "FECHA_SPEEDY" => array(933, 10),
            "PAQUETE" => array(943, 50),
            "INFOCORP" => array(993, 2),
            "PRODUCTO_COBRANZA" => array(995, 50),
            "SERVICIO_COBRANZA" => array(1045, 50),
            "CORTE" => array(1095, 10),
            "CAMPANIA" => array(1105, 50),
            "TIPO_ALTA" => array(1155, 30),
            "TELEFONO_REFERENCIA" => array(1185, 30),
            "APLICA_FINANCIAMIENTO" => array(1215, 2),
            "FINANCIAMIENTO_ACTUAL" => array(1217, 50),
            "RESERVADO_1" => array(1267, 50),
            "RESERVADO_2" => array(1317, 50),
            "RESERVADO_3" => array(1367, 50),
            "RESERVADO_4" => array(1417, 50),

            "RESERVADO_5" => array(1467, 50),
            "DESCRIPCION_GESTION" => array(1517, 40),

            "DESCRIPCION_EVENTO" => array(1557, 40),
            "DESCRIPCION_SEGMENTACION" => array(1597, 50)
        );

        $header = array();
        foreach ($struc as $index => $value) {
            array_push($header, $index);
        }

        fwrite($tmpArchivo, implode("|", $header) . "\r\n");

        if ($archivo) {
            while (!feof($archivo)) {
                $lineTMP = array();
                $linea = fgets($archivo);
                foreach ($struc as $index => $value) {
                    array_push($lineTMP, trim(substr($linea, $value[0], $value[1])));
                }
                fwrite($tmpArchivo, implode("|", $lineTMP) . "\r\n");
            }
            fclose($archivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => "TMP" . $_post['file']));
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
        }
    }

    public function limpiarCarteraNOC($_post) {
        $path = "../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        //$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
        $tmpArchivo = @fopen("../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "", "", "", "", "", "", "", "");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function limpiarCarteraRetiro($_post) {
        $path = "../documents/retiro/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/retiro/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);

            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function limpiarCarteraCorteFocalizado($_post) {
        $path = "../documents/corte_focalizado/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'El archivo subido no existe o fue removida, intente subir otra vez el archivo'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/corte_focalizado/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'No se encuentra cabecera'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Archivo limpiado correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo'));
        }
    }

    public function limpiarCarteraIVR($_post) {
        $path = "../documents/ivr/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/ivr/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function limpiarCarteraAgregarCabecera($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }


        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');
        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
            exit();
        }

        $archivo = @fopen($path, "r+");

        $countHeader = 0;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
        $pr->execute();
        $data = $pr->fetchAll(PDO::FETCH_ASSOC);

        $cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

        $header = array();
        foreach ($cabeceras as $index => $value) {
            array_push($header, $index);
        }

        if ($_post['CaracterSeparador'] == 'tab') {
            fwrite($tmpArchivo, implode("\t", $header) . "\r\n");
        } else {
            fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $header) . "\r\n");
        }
        //fwrite($tmpArchivo,implode("|",$header)."\r\n");
        //$longitud = 0;
        if ($archivo) {
            while (!feof($archivo)) {
                $lineTMP = array();
                $linea = fgets($archivo);
                $longitud = 0;
                foreach ($cabeceras as $index => $value) {
                    array_push($lineTMP, trim(substr($linea, $longitud, $value)));
                    $longitud = $longitud + (int) $value;
                }
                //fwrite($tmpArchivo,implode("|",$lineTMP)."\r\n");
                if ($_post['CaracterSeparador'] == 'tab') {
                    fwrite($tmpArchivo, implode("\t", $lineTMP) . "\r\n");
                } else {
                    fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $lineTMP) . "\r\n");
                }
            }
            fclose($archivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
        }
    }

    public function limpiarCarteraPagoAddHeader($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"] . ".txt";

        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
        $pr->execute();
        $data = $pr->fetchAll(PDO::FETCH_ASSOC);

        $cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

        $header = array();
        foreach ($cabeceras as $index => $value) {
            array_push($header, $index);
        }

        fwrite($tmpArchivo, implode("|", $header) . "\r\n");

        $carteraArchivo = @fopen($path, 'r+');

        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            //fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function limpiarCartera3($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        //$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function limpiarCarteraSoloAgregarCabecera($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');
        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
            exit();
        }

        $archivo = @fopen($path, "r+");

        $countHeader = 0;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
        $pr->execute();
        $data = $pr->fetchAll(PDO::FETCH_ASSOC);

        $cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

        $header = array();
        foreach ($cabeceras as $index => $value) {
            array_push($header, $index);
        }

        if ($_post['CaracterSeparador'] == 'tab') {
            fwrite($tmpArchivo, implode("\t", $header) . "\r\n");
        } else {
            fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $header) . "\r\n");
        }
        if ($archivo) {
            while (!feof($archivo)) {
                $linea = fgets($archivo);
                $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                $cambia = array('', "", "", "", "", "", "", "", "", "N");
                $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                fwrite($tmpArchivo, $line_c . "\r\n");
            }
            fclose($archivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
        }
    }

    public function limpiarCartera4($_post,$carpeta='carteras') {
        $path = "../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    // $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $line = str_replace($buscar, $cambia, trim($linea));
                    $cabeceras = array();
                    if ($_post['CaracterSeparador'] == 'tab') {
                        $cabeceras = explode("\t", $line);
                    }else{
                        $cabeceras = explode($_post['CaracterSeparador'], $line);
                    }
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    // $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    // $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", "", "Â¥");
                    // $cambia = array('', "", "", "", "", "", "", "", "", "N");
                    // $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    // fwrite($tmpArchivo, $line_c . "\r\n");

                    if(!empty($linea)){ // No considera a las lineas vacias
                        $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", "", "Â¥");
                        $cambia = array('' , "" , "" , "" , "" , "" , ""  , ""  , "", "N" );
                        // $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                        $line_c = str_replace($buscar, $cambia, trim($linea));
                        fwrite($tmpArchivo, $line_c . "\r\n");
                        //$count++;
                    }

                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }
    //~ Vic I
    public function limpiarLlamadaManual($_post,$carpeta='llamadas') {
        $path = "../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            return array('rst' => false, 'msg' => 'Problemas al crear archivo temporal');
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if (feof($carteraArchivo)) break;
                if ($count == 0) {
                    $buscar = array("ñ","Ñ","Á","É","Í","Ó","Ú","á","é","í","ó","ú","Ã", "Ã¡", "Ã", "Ã?", "Ã©", "Ã¨", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("n","N","A","E","I","O","U","a","e","i","o","u","a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = array();
                    $cabeceras = explode("\t", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            return array('rst' => false, 'msg' => 'Existen cabeceras vacias');
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            return array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file);
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            return array('rst' => false, 'msg' => 'Error al leer cartera');
        }
    }

//~ Vic
    public function limpiarTxtSinCabecera($_post,$carpeta='llamadas',$separa="\t") {
        $path = "../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        $tmpArchivo = @fopen("../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            return array('rst' => false, 'msg' => 'Problemas al crear archivo temporal');
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                $cambia = array('', "", "", "", "", "", "", "", "", "N");
                $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                fwrite($tmpArchivo, $line_c."\r\n");
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            return array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file);
        } else {
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            return array('rst' => false, 'msg' => 'Error al leer cartera');
        }
    }

    public function limpiarCarteraPago($_post) {
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

        //$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
        $tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

        if (!$tmpArchivo) {
            echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');

        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("|", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "\t", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
        } else {
            //fclose($carteraArchivo);
            fclose($tmpArchivo);
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
        }
    }

    public function uploadCarteraPago( $_post, $is_parser=0,$file, $is_update = 0 ) {
        //$file = $_post['file'];
        $separator = $_post['separator'];
        $servicio = $_post['Servicio'];
        $NombreServicio = $_post['NombreServicio'];
        //$campania=$_post['Campania'];
        $cartera=$_post['Cartera'];

        $UsuarioCreacion = $_post['usuario_creacion'];
        $jsonPago = json_decode(str_replace("\\", "", $_post['data_pago']), true);
        $id_cartera_pago = 0;

        $codigo_cliente = '';

        $numero_cuenta = '';
        $moneda_cuenta = '';
        $grupo1_cuenta = '';

        $codigo_operacion = '';
        $moneda_operacion = '';
        $grupo1_operacion = '';

        $gestion = '';
        $codigo_transaccion='';

        $call_center = '';
        $moneda = '';
        $monto = '';
        $total_deuda = '';
        $fecha_pago = '';

        for ($i = 0; $i < count($jsonPago); $i++) {
            if ($jsonPago[$i]['campoT'] == 'monto_pagado') {
                $monto = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'moneda') {
                $moneda = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'total_deuda') {
                $total_deuda = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'call_center') {
                $call_center = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'codigo_cliente') {
                $codigo_cliente = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'numero_cuenta') {
                $numero_cuenta = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'moneda_cuenta') {
                $moneda_cuenta = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'grupo1_cuenta') {
                $grupo1_cuenta = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'codigo_operacion') {
                $codigo_operacion = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'moneda_operacion') {
                $moneda_operacion = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'grupo1_operacion') {
                $grupo1_operacion = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'gestion') {
                $gestion = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'fecha') {
                $fecha_pago = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'codigo_transaccion'){
                $codigo_transaccion=$jsonPago[$i]['dato'];

            }
        }


        if (trim($codigo_operacion) == '') {
            return array('rst' => false, 'msg' => 'Seleccione operacion');
            exit();
        }

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $path = "../documents/carteras/" . $NombreServicio . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        //$archivo = file($path);
        $archivo = @fopen($path, "r+");

        //$colum = explode($separator, fgets($archivo));
        $colum = array();
        if ($_post['separator'] == 'tab') {
            $colum = explode("\t", fgets($archivo));
        } else {
            $colum = explode($_post['separator'], fgets($archivo));
        }

        if (count($colum) < 4) {
            return array('rst' => false, 'msg' => 'Caracter separador incorrecto');
            exit();
        }

        if( !function_exists('map_header_pago') ) {
            function map_header_pago($n) {
                $item = "";
                if (trim(utf8_encode($n)) != "") {
                    //$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                    $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                    //$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
                    $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                    //$item="`".$item."` VARCHAR(200) ";
                }

                return $item;
            };
        }

        $colum = array_map("map_header_pago", $colum);

        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                //array_push($columHeader,$colum[$i]);
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
            exit();
        }

        fclose($archivo);


        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTablePago = " DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ";
        $prDropTablePago = $connection->prepare($sqlDropTablePago);
        if ($prDropTablePago->execute()) {

            $createTablePago = " CREATE TABLE tmppago_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";

            $prCreateTablePago = $connection->prepare($createTablePago);
            if ($prCreateTablePago->execute()) {
                $sqlLoadPago = "";
                if( $separator == 'tab' ) {
                    $sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
                      INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }else{
                    $sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
                      INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }

                $prLoadPago = $connection->prepare($sqlLoadPago);
                if ($prLoadPago->execute()) {

                    $sqlUpdateTMPCarteraPago = " ALTER TABLE tmppago_" . session_id() . "_" . $time . " ADD idcartera INT, ADD iddetalle_cuenta INT , ADD idcartera_pago INT, ADD idcuenta INT , ADD estado_pag TINYINT DEFAULT 1, ADD INDEX(idcartera), ADD INDEX(iddetalle_cuenta)  ";

                    $prUpdateTMPCarteraPago = $connection->prepare($sqlUpdateTMPCarteraPago);
                    if ($prUpdateTMPCarteraPago->execute()) {

                        //$connection->beginTransaction();
                        if( trim($gestion)!='' ) {
                            $sqlUpdateIdCarteraTMPCarteraPago = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp
                                SET idcartera = ( SELECT idcartera FROM ca_cartera INNER JOIN ca_campania ON ca_cartera.idcampania = ca_campania.idcampania AND ca_campania.estado = 1 AND ca_cartera.estado = 1 AND idservicio = $servicio WHERE TRIM(nombre_cartera) = TRIM( tmp.$gestion ) ORDER BY idcartera DESC LIMIT 1 )
                                WHERE TRIM($gestion) != '' ";
                            $prUpdateIdCarteraTMPCarteraPago = $connection->prepare($sqlUpdateIdCarteraTMPCarteraPago);
                            if ($prUpdateIdCarteraTMPCarteraPago->execute()) {

                            }else{
                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                @$prDropTablePagoRollback->execute();
                                return array('rst' => false, 'msg' => 'Error al actualizar gestion');
                                exit();
                            }
                        }

                        /*$prUpdateIdCarteraTMPCarteraPago = $connection->prepare($sqlUpdateIdCarteraTMPCarteraPago);
                        if ($prUpdateIdCarteraTMPCarteraPago->execute()) {*/

                            //$sqlDeleteRowNotGestPago = " DELETE FROM tmppago_".session_id()."_".$time." WHERE ISNULL(idcartera) = 1 ";
                            //$prDeleteRowNotGestPago = $connection->prepare( $sqlDeleteRowNotGestPago );
                            //if( $prDeleteRowNotGestPago->execute() ) {

                            $cabeceras = implode(",", $colum);
                            $parserPago = str_replace("\\", "", $_post["data_pago"]);
                            if ($is_parser == 1) {
                                $InsertJsonParserPago = " INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion, moneda )
                                    VALUES ( ?,?,?,?,?,?,? ) ";

                                $prInsertJsonParserPago = $connection->prepare($InsertJsonParserPago);
                                $prInsertJsonParserPago->bindParam(1, $servicio);
                                $prInsertJsonParserPago->bindParam(2, $cabeceras);
                                $prInsertJsonParserPago->bindParam(3, $parserPago);
                                $prInsertJsonParserPago->bindParam(4, $codigo);
                                $prInsertJsonParserPago->bindParam(5, $numero_cuenta);
                                $prInsertJsonParserPago->bindParam(6, $operacion);
                                /*                                 * ****** */
                                $prInsertJsonParserPago->bindParam(7, $moneda);
                                /*                                 * ****** */
                                if ($prInsertJsonParserPago->execute()) {

                                } else {
                                    //$connection->rollBack();

                                    @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                    @$prDropTablePagoRollback->execute();

                                    return array('rst' => false, 'msg' => 'Error al guardar metadata');
                                    exit();
                                }
                            }


                            //validando para codigo_transaccion es decir nro_recibo de pago del cliente
                            if ($codigo_transaccion!=''){
                                if(trim($gestion)==''){
                                    $sql="delete tmp.* from tmppago_" . session_id() . "_" . $time ." as tmp inner join ca_pago pag on pag.codigo_transaccion=tmp.".$codigo_transaccion." where pag.idcartera=$cartera";
                                }else{
                                    $sql="delete tmp.* from tmppago_" . session_id() . "_" . $time ." as tmp inner join ca_pago pag on pag.codigo_transaccion=tmp.".$codigo_transaccion." and pag.idcartera=tmp.idcartera";
                                }
                                $prsql=$connection->prepare($sql);
                                if($prsql->execute()){

                                }else{
                                    
                                }                                
                            }
                            /**********/
                            

                            //$InsertCarteraPago=" INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion )
                            //VALUE( ".$_post['Cartera'].",'tmppago_".session_id()."_".$time."',".(count($archivo)-1).",NOW(),'".$file."',$UsuarioCreacion,NOW() )";
                            //$InsertCarteraPago=" INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras )
                            //VALUES( ".$_post['Cartera'].",'tmppago_".session_id()."_".$time."', ( SELECT COUNT(*) FROM tmppago_".session_id()."_".$time."  ) ,NOW(),'".$file."',$UsuarioCreacion,NOW(),'".$codigo."','".$numero_cuenta."','".$moneda."','".$operacion."','".$parserPago."','".$cabeceras."' )";
                            $InsertCarteraPago = "";
                            if( trim($gestion) == '' ) {
                                $InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras )
                                VALUES ( $cartera, 'tmppago_" . session_id() . "_" . $time . "', ( SELECT COUNT(*) FROM tmppago_" . session_id() . "_" . $time . " ), NOW(),'".$file."', $UsuarioCreacion, NOW(),'" . $codigo . "','" . $numero_cuenta . "','" . $moneda . "','" . $operacion . "','" . $parserPago . "','" . $cabeceras . "' ) ";
                            }else{
                                $InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras )
                                    SELECT idcartera ,'tmppago_" . session_id() . "_" . $time . "', COUNT(*),NOW(),'" . $file . "',$UsuarioCreacion,NOW(),'" . $codigo . "','" . $numero_cuenta . "','" . $moneda . "','" . $operacion . "','" . $parserPago . "','" . $cabeceras . "'
                                    FROM tmppago_" . session_id() . "_" . $time . "
                                    WHERE TRIM($gestion) != '' AND ISNULL(idcartera) = 0 GROUP BY idcartera ";
                            }


                            $prInsertCarteraPago = $connection->prepare($InsertCarteraPago);
                            if ($prInsertCarteraPago->execute())
                            {

                                $idCarteraPago = $connection->lastInsertId();

                                //$sqlUpdateTmpIdCarteraPago = " UPDATE tmppago_".session_id()."_".$time." SET idcartera_pago = (  ) ";
                                /*if( trim($gestion) == '' ) {
                                    $id_cartera_pago = $connection->lastInsertId();
                                }*/

                                $field_on = "";
                                $field_where = "";
                                $field_on_est_pago = "";
                                $field_where_est_pago = "";
                                if( trim($gestion) != ''  ) {
                                    $field_on .= " AND detcu.idcartera = tmp.idcartera ";
                                    $field_on_est_pago .= " AND pag.idcartera = tmp.idcartera ";
                                    $field_where .= " AND ISNULL(tmp.idcartera) = 0 ";
                                    $field_where_est_pago .= " AND ISNULL(tmp.idcartera) = 0 ";
                                }else{
                                    $field_where .= " AND detcu.idcartera = $cartera ";
                                    $field_where_est_pago .= " AND pag.idcartera = $cartera  ";
                                }

                                if( trim($moneda_cuenta) != ''  ) {
                                    $field_on .= " AND detcu.moneda = tmp.$moneda_operacion ";
                                    $field_where .= " AND ISNULL(tmp.$moneda_operacion) = 0 ";
                                }
                                if( trim($grupo1_cuenta) != ''  ) {
                                    $field_on .= " AND detcu.grupo1_operacion = tmp.$grupo1_operacion ";
                                    $field_where .= " AND ISNULL(tmp.$grupo1_operacion) = 0 ";
                                }

                                $sqlUpdateTmpIdDetalleCuenta = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu
                                    ON detcu.codigo_operacion = tmp.$codigo_operacion ".$field_on."
                                    SET tmp.iddetalle_cuenta = detcu.iddetalle_cuenta , tmp.idcuenta = detcu.idcuenta
                                    WHERE ISNULL(tmp.$codigo_operacion) = 0 ".$field_where." ";
                                
                                $prUpdateTmpIdDetalleCuenta = $connection->prepare($sqlUpdateTmpIdDetalleCuenta);
                                if( $prUpdateTmpIdDetalleCuenta->execute() )
                                {


                                    if( $is_update == 1 ) {

                                        if( $_SESSION['cobrast']['idservicio'] == 10){

                                            $sqlUpdateEstadoPago = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp INNER JOIN ca_pago pag
                                            ON pag.iddetalle_cuenta = tmp.iddetalle_cuenta ".$field_on_est_pago." and pag.codigo_transaccion=tmp.".$codigo_transaccion."
                                            SET pag.estado = 0 , pag.fecha_modificacion = NOW() , pag.usuario_modificacion = $UsuarioCreacion
                                            WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0 ".$field_where_est_pago." ";

                                            $prUpdateEstadoPago = $connection->prepare($sqlUpdateEstadoPago);
                                            if( $prUpdateEstadoPago->execute() ) {

                                            }else{
                                                @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
                                                @$prDropTablePagoRollback->execute();

                                                return array('rst'=>false,'msg'=>'Error al actualizar estado de pagos');
                                                exit();
                                            }

                                        }else{
                                            $sqlUpdateEstadoPago = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp INNER JOIN ca_pago pag
                                            ON pag.iddetalle_cuenta = tmp.iddetalle_cuenta ".$field_on_est_pago."
                                            SET pag.estado = 0 , pag.fecha_modificacion = NOW() , pag.usuario_modificacion = $UsuarioCreacion
                                            WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0 ".$field_where_est_pago." ";

                                            $prUpdateEstadoPago = $connection->prepare($sqlUpdateEstadoPago);
                                            if( $prUpdateEstadoPago->execute() ) {

                                            }else{
                                                @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
                                                @$prDropTablePagoRollback->execute();

                                                return array('rst'=>false,'msg'=>'Error al actualizar estado de pagos');
                                                exit();
                                            }
                                        }                                    

                                        

                                    }

                                    //$fieldPago=array_intersect_key($jsonPago,array('monto'=>'','moneda'=>'','fecha'=>'','observacion'=>''));
                                    $campoPagoTMP = array();
                                    $campoPago = array();

                                    $estado_cruce = "";
                                    $retiro_cliente = "";
                                    for ($i = 0; $i < count($jsonPago); $i++) {
                                        if ($jsonPago[$i]['campoT'] == "codigo_cliente") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                        } else if ($jsonPago[$i]['campoT'] == "numero_cuenta") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                        } else if ( $jsonPago[$i]['campoT'] == "estado_pago" ) {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " IFNULL(( SELECT descripcion FROM ca_estado_pago_cruce WHERE nombre = TRIM( " . $jsonPago[$i]['dato'] . " ) AND idservicio = ".$servicio." AND estado = 1 ), TRIM( " . $jsonPago[$i]['dato'] . " ) ) ");
                                            $estado_cruce = " IFNULL(( SELECT descripcion FROM ca_estado_pago_cruce WHERE nombre = TRIM( " . $jsonPago[$i]['dato'] . " ) AND idservicio = ".$servicio." AND estado = 1 ),TRIM( " . $jsonPago[$i]['dato'] . " )) ";
                                        } else if ($jsonPago[$i]['campoT'] == "codigo_operacion") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                        } else if ($jsonPago[$i]['campoT'] == "moneda") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                        } else if ($jsonPago[$i]['campoT'] == "call_center") {
                                            
                                        } else if ($jsonPago[$i]['campoT'] == "retiro_cliente") {
                                            $retiro_cliente = $jsonPago[$i]['dato'];
                                        } else if ($jsonPago[$i]['campoT'] == "fecha") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, "
                                                CASE
                                                WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 8
                                                THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",7,2))
                                                WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 3
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 5
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'-') = 3

                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 3
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 5
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
                                                    ELSE TRIM(".$jsonPago[$i]['dato'].")
                                                    END
                                                ELSE TRIM(".$jsonPago[$i]['dato'].")
                                                END
                                                 ");
                                        } else if ($jsonPago[$i]['campoT'] == "fecha_envio") {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, "
                                                CASE
                                                WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 8
                                                THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",7,2))
                                                WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 3
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 5
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'-') = 3
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 3
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
                                                    WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 5
                                                    THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
                                                    ELSE TRIM(".$jsonPago[$i]['dato'].")
                                                    END
                                                ELSE TRIM(".$jsonPago[$i]['dato'].")
                                                END
                                                 ");
                                        } else {
                                            array_push($campoPago, $jsonPago[$i]['campoT']);
                                            array_push($campoPagoTMP, " TRIM( ".$jsonPago[$i]['dato']." ) ");
                                        }
                                    }
                                    /*                                 * ********** */



                                    /*$sqlVerificarFecha = " SELECT COUNT(*) AS 'COUNT'
                                        FROM ca_pago pag INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp
                                        ON tmp.idcartera = pag.idcartera AND tmp.$fecha_pago = pag.fecha
                                        WHERE pag.estado = 1 ";*/



                                    $trace_VerificarFecha = "";
                                    $trace_where_VerificarFecha = "";
                                    if( trim($gestion) == '' ) {
                                        $trace_where_VerificarFecha = " WHERE pag.idcartera = $cartera ";
                                    }else{
                                        $trace_VerificarFecha = " tmp.idcartera = pag.idcartera AND ";
                                    }

                                    $field_estado_pg = "";
                                    $field_estado_pg_tmp = "";
                                    if( $fecha_pago == '' ) {

                                    } else{

                                        $sqlVerificarFecha = " UPDATE ca_pago pag INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp
                                            ON ".$trace_VerificarFecha." tmp.$codigo_operacion = pag.codigo_operacion AND tmp.$fecha_pago = pag.fecha
                                            SET tmp.estado_pag = 0 ".$trace_where_VerificarFecha." ";

                                        $prVerificarFecha = $connection->prepare($sqlVerificarFecha);

                                        if( $prVerificarFecha->execute() ){

                                        }else{

                                          @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
                                          @$prDropTablePagoRollback->execute();

                                          return array('rst'=>false,'msg'=>'Error al verificar pagos del cliente');
                                          exit();

                                        }

                                        $field_estado_pg = "AND estado_pag = 1";
                                        $field_estado_pg_tmp = "AND tmp.estado_pag = 1 ";
                                    }



                                    $sqlPago = "";

                                        //if( trim($call_center)=='' ) {

                                        if( trim($gestion) == '' ) {
                                            $sqlPago = " INSERT IGNORE INTO ca_pago (  idcartera_pago, is_act, idcartera, iddetalle_cuenta, idcuenta, usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " )
                                                        SELECT $idCarteraPago, $is_update, $cartera , iddetalle_cuenta, idcuenta, $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "
                                                        FROM tmppago_" . session_id() . "_" . $time . "
                                                        WHERE ISNULL( iddetalle_cuenta ) = 0 AND ISNULL( idcuenta ) = 0 ".$field_estado_pg." ";

                                        }else{
                                            $sqlPago = " INSERT IGNORE INTO ca_pago (  idcartera_pago, is_act, idcartera, iddetalle_cuenta, idcuenta, usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " )
                                                        SELECT $idCarteraPago, $is_update, idcartera , iddetalle_cuenta, idcuenta, $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "
                                                        FROM tmppago_" . session_id() . "_" . $time . "
                                                        WHERE ISNULL(idcartera) = 0
                                                        AND TRIM($gestion) != ''
                                                        AND ISNULL( iddetalle_cuenta ) = 0 AND ISNULL( idcuenta ) = 0  ".$field_estado_pg." ";
                                        }

                                        $prInsertPago = $connection->prepare($sqlPago);
                                        if ($prInsertPago->execute()) {

                                            if( $is_update == 1 ) {

                                                $sqlClearMontoDetalle = " UPDATE ca_cuenta cu INNER JOIN ca_detalle_cuenta detcu INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp
                                                ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta AND detcu.idcuenta = cu.idcuenta
                                                SET
                                                detcu.monto_pagado = 0,
                                                detcu.ul_fecha_pago = NULL,
                                                cu.monto_pagado = 0,
                                                cu.ul_fecha_pago = NULL
                                                WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0 ".$field_estado_pg_tmp." ";

                                                $prUDC = $connection->prepare($sqlClearMontoDetalle);
                                                if( $prUDC->execute() ) {

                                                }else{
                                                    @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
                                                    @$prDropTablePagoRollback->execute();

                                                    return array('rst'=>false,'msg'=>'Error al actualizar monto y fecha de pago de cuenta y detalle');
                                                    exit();
                                                }

                                            }

                                            $field_detcu = "";
                                            $field_cu = "";
                                            $field_max = "";
                                            if( $fecha_pago != '' ) {
                                                $field_detcu = " , detcu.ul_fecha_pago = tmp.FECHA_PAGO  ";
                                                $field_cu = " , cu.ul_fecha_pago = tmp.FECHA_PAGO ";
                                                $field_max = " ,MAX($fecha_pago) AS FECHA_PAGO  ";
                                            }

                                            /*$sqlUpdateMontoDetalleCuenta = " UPDATE ca_detalle_cuenta detcu INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp
                                            ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
                                            SET
                                            detcu.monto_pagado = IFNULL(detcu.monto_pagado,0) + tmp.$monto
                                            $field_detcu
                                                WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0  ".$field_estado_pg_tmp." ";*/

                                            $sqlUpdateMontoDetalleCuenta = " UPDATE ca_detalle_cuenta detcu INNER JOIN
                                                            (
                                                            SELECT iddetalle_cuenta, SUM($monto) AS MONTO_PAGO ".$field_max."
                                                            FROM tmppago_" . session_id() . "_" . $time . "
                                                            WHERE ISNULL(iddetalle_cuenta) = 0 AND ISNULL(idcuenta) = 0  ".$field_estado_pg."
                                                            GROUP BY iddetalle_cuenta
                                                            ) tmp
                                                            ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
                                                            SET
                                                            detcu.monto_pagado = IFNULL(detcu.monto_pagado,0) + tmp.MONTO_PAGO
                                                            ".$field_detcu."  ";

                                            $prUpdateMontoDetalleCuenta = $connection->prepare($sqlUpdateMontoDetalleCuenta);
                                            if( $prUpdateMontoDetalleCuenta->execute() )  {

                                                $field_tmp = "";
                                                $field_cuenta = "";
                                                if( $estado_cruce != '' ){
                                                    $field_tmp = ", ".$estado_cruce." AS ESTADO_CRUCE ";
                                                    $field_cuenta = " , cu.estado_pago = tmp.ESTADO_CRUCE ";
                                                }

                                                $sqlUpdateMontoCuenta = " UPDATE ca_cuenta cu INNER JOIN
                                                            (
                                                            SELECT idcuenta, SUM($monto) AS MONTO_PAGO ".$field_max." ".$field_tmp."
                                                            FROM tmppago_" . session_id() . "_" . $time . "
                                                            WHERE ISNULL(iddetalle_cuenta) = 0 AND ISNULL(idcuenta) = 0  ".$field_estado_pg."
                                                            GROUP BY idcuenta
                                                            ) tmp
                                                            ON tmp.idcuenta = cu.idcuenta
                                                            SET
                                                            cu.monto_pagado = IFNULL(cu.monto_pagado,0) + tmp.MONTO_PAGO
                                                            ".$field_cu." ".$field_cuenta." ";

                                                $prUpdateMontoCuenta = $connection->prepare( $sqlUpdateMontoCuenta );
                                                if( $prUpdateMontoCuenta->execute() ) {

                                                    if ($call_center == '') {
                                                        //$connection->commit();
                                                        return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
                                                    } else {

                                                        if( $retiro_cliente != '' ) {

                                                            $sqlRetiroCliente = " UPDATE ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp
                                                            ON tmp.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera
                                                            SET
                                                            clicar.estado = IF( tmp.$retiro_cliente ='0' OR tmp.$retiro_cliente =0,0,1 ),
                                                            clicar.retiro = IF( tmp.$retiro_cliente ='0' OR tmp.$retiro_cliente =0,1,0 ),
                                                            WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0
                                                            AND TRIM(tmp.$retiro_cliente)!=''  ".$field_estado_pg_tmp." ";

                                                            $prRCl = $connection->prepare($sqlRetiroCliente);
                                                            if( $prRCl->execute() ) {

                                                            }else{
                                                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                                                @$prDropTablePagoRollback->execute();

                                                                return array('rst' => false, 'msg' => 'Error al retirar clientes');
                                                                exit();
                                                            }

                                                        }

                                                        if (trim($monto) == "") {
                                                            //$connection->commit();
                                                            return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
                                                        } else {

                                                            $sqlRankinPago = " INSERT IGNORE INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion )
                                                                            SELECT " . $call_center . ", SUM( " . $monto . " ), $idCarteraPago, NOW(), $UsuarioCreacion
                                                                            FROM tmppago_" . session_id() . "_" . $time . "
                                                                            WHERE LENGTH( TRIM( " . $call_center . " ) ) > 0
                                                                            AND ISNULL(iddetalle_cuenta) = 0 AND ISNULL(idcuenta) = 0
                                                                            GROUP BY LOWER( TRIM( " . $call_center . " ) ) ";

                                                            /* $sqlRankinPago=" INSERT IGNORE INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion )
                                                              SELECT ".$call_center.", SUM( ".$monto." ), idcartera_pago, NOW(), $UsuarioCreacion
                                                              FROM tmppago_".session_id()."_".$time." WHERE LENGTH( TRIM( ".$call_center." ) ) > 0 GROUP BY LOWER( TRIM( ".$call_center." ) ) "; */

                                                            $prSqlRankinPago = $connection->prepare($sqlRankinPago);
                                                            if (@$prSqlRankinPago->execute()) {
                                                                //$connection->commit();
                                                                return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
                                                            } else {
                                                                //$connection->rollBack();

                                                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                                                @$prDropTablePagoRollback->execute();

                                                                return array('rst' => false, 'msg' => 'Error agregar datos de ranking de pago');
                                                            }
                                                        }


                                                        //}
                                                    }

                                                }else{
                                                    @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                                    @$prDropTablePagoRollback->execute();

                                                    return array('rst' => false, 'msg' => 'Error al actualizar montos de cuenta');
                                                }

                                            }else{
                                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                                @$prDropTablePagoRollback->execute();

                                                return array('rst' => false, 'msg' => 'Error al actualizar montos de detalle cuenta (facturas)');
                                            }

                                        } else {
                                            //$connection->rollBack();

                                            @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                            @$prDropTablePagoRollback->execute();

                                            return array('rst' => false, 'msg' => 'Error agregar datos de pago');
                                        }

                                }else{

                                    @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                    @$prDropTablePagoRollback->execute();

                                    return array('rst' => false, 'msg' => 'Error al actualizar id detalle cuenta ( factura ) ');
                                }

                            } else {
                                //$connection->rollBack();

                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                @$prDropTablePagoRollback->execute();

                                return array('rst' => false, 'msg' => 'Error insertar datos de temporal');
                            }
                            /* }else{
                              //$connection->rollBack();
                              @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
                              @$prDropTablePagoRollback->execute();

                              echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar las no gestiones '));
                              } */
                        /*} else {
                            //$connection->rollBack();
                            @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                            @$prDropTablePagoRollback->execute();

                            echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar gestion'));
                        }*/
                    } else {
                        @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                        @$prDropTablePagoRollback->execute();

                        return array('rst' => false, 'msg' => 'Error al agregar campo idcartera');
                    }
                } else {

                    @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                    @$prDropTablePagoRollback->execute();

                    return array('rst' => false, 'msg' => 'Error al cargar datos de pago');
                }
            } else {

                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                @$prDropTablePagoRollback->execute();

                return array('rst' => false, 'msg' => 'Error create temporary table');
            }
        } else {
            return array('rst' => false, 'msg' => 'Error al eliminar tabla');
        }
    }

    public function uploadCartera($_post, $is_parser=0, $file) {
        $idcampania=$_POST['Campania'];

        if($idcampania==1){
            // INICIO COBRANZAS
            $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
            $usuario_creacion = $_post["usuario_creacion"];

            $tipo_data = (int)$_post["TipoData"];
            $cabecera_departamento = $_post["CabeceraDepartamento"];

            $nombre_cartera = $_post["NombreCartera"];
            $codigo_cliente = "";
            $numero_cuenta = "";
            $codigo_operacion = "";
            $moneda_cuenta = "";
            $moneda_operacion = "";
            $grupo1_cuenta = "";
            $grupo1_operacion = "";
            $gestion = "";

            $parserCliente = str_replace("\\", "", $_post["data_cliente"]);
            $parserCartera = str_replace("\\", "", $_post["data_cartera"]);
            $parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
            $parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
            $parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
            $parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
            $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

            $jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
            $jsonCartera = json_decode(str_replace("\\", "", $_post["data_cartera"]), true);
            $jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
            $jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
            $jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
            $jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
            $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

            $sql_where_main = " ";
            $sql_where_main_tmp = " ";
            if( $tipo_data == 1 ) {

            }else if ( $tipo_data == 2 ){
                $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
                $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
            }else if ( $tipo_data == 3 ) {
                $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
                $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
            }else{
                echo json_encode(array('rst' => false, 'msg' => 'Tipo incorrecto de carga'));
                exit();
            }

            for( $i=0;$i<count($jsonCliente);$i++ ) {
                if( $jsonCliente[$i]['campoT'] == 'codigo' ) {
                    $codigo_cliente = $jsonCliente[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonCartera);$i++ ) {
                if( $jsonCartera[$i]['campoT'] == 'nombre_cartera' ) {
                    $gestion = $jsonCartera[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonCuenta);$i++ ) {
                if( $jsonCuenta[$i]['campoT'] == 'numero_cuenta' ) {
                    $numero_cuenta = $jsonCuenta[$i]['dato'];
                }else if( $jsonCuenta[$i]['campoT'] == 'moneda' ){
                    $moneda_cuenta = $jsonCuenta[$i]['dato'];
                }else if( $jsonCuenta[$i]['campoT'] == 'grupo1' ) {
                    $grupo1_cuenta = $jsonCuenta[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonOperacion);$i++ ) {
                if( $jsonOperacion[$i]['campoT'] == 'codigo_operacion' ) {
                    $codigo_operacion = $jsonOperacion[$i]['dato'];
                }else if( $jsonOperacion[$i]['campoT'] == 'moneda' ){
                    $moneda_operacion = $jsonOperacion[$i]['dato'];
                }else if( $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
                    $grupo1_operacion = $jsonOperacion[$i]['dato'];
                }
            }

            

            if (!isset($confCobrast['ruta_cobrast'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            }

            $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
            if (!file_exists($path)) {
                return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
                exit();
            }


            $time = date("Y_m_d_H_i_s");
            $archivoParser = @fopen($path, "r+");
            $columMap = array();
            if ($_post['separator'] == 'tab') {
                $columMap = explode("\t", fgets($archivoParser));
            } else {
                $columMap = explode($_post['separator'], fgets($archivoParser));
            }

            fclose($archivoParser);

            if (!function_exists('map_header')) {
                function map_header($n) {
                    $item = "";
                    if (trim(utf8_encode($n)) != "") {
                        $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                        $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "");
                        $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                    }
                    return $item;
                }
            }

            $colum = array_map("map_header", $columMap);
            $columHeader = array();
            $countHeaderFalse = 0;

            for ($i = 0; $i < count($colum); $i++) {
                if ($colum[$i] != "") {
                    array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
                } else {
                    $countHeaderFalse++;
                }
            }

            $parserHeader = implode(",", $colum);

            array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
            array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
            if ($codigo_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
            }
            if ($moneda_cuenta != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
            }
            if ($moneda_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
            }
            if ($grupo1_cuenta != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_cuenta` ( `$grupo1_cuenta` ASC ) ");
            }
            if ($grupo1_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_operacion` ( `$grupo1_operacion` ASC ) ");
            }
            if ($gestion != "") {
                array_push($columHeader, "INDEX `index_" . session_id() . "_gestion` ( `$gestion` ASC ) ");
            }
            if ($countHeaderFalse > 0) {
                return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
                exit();
            }

            $nombre_tabla="tmp_cobranzas_andina";

            $factoryConnection = FactoryConnection::create('mysql');
            $connection = $factoryConnection->getConnection();

            $truncate_tmp_cobranzas="TRUNCATE TABLE tmp_cobranzas_andina;";
            $prLoadData_tmp_cobranzas = $connection->prepare($truncate_tmp_cobranzas);
            $prLoadData_tmp_cobranzas->execute();

            if($_post["NombreServicio"]=='ANDINA' ){
                if ($_post['separator'] == 'tab') {
                    // $sqlLoadDataInFileUC = "    LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                    //                             INTO TABLE $nombre_tabla FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES SET llave=CONCAT(empresa,'@',cod_cliente,'@',td,'@',num_doc,'@',mon)";

                    $sqlLoadDataInFileUC = "    LOAD DATA LOCAL INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                                                INTO TABLE $nombre_tabla FIELDS TERMINATED BY '\t' LINES  TERMINATED BY '\r\n' IGNORE 1 LINES SET llave=CONCAT(empresa,'@',cod_cliente,'@',td,'@',num_doc,'@',mon)";
                                                
                } else {
                    $sqlLoadDataInFileUC = "    LOAD DATA LOCAL INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                                                INTO TABLE $nombre_tabla FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\r\n' IGNORE 1 LINES SET llave=CONCAT(empresa,'@',cod_cliente,'@',td,'@',num_doc,'@',mon)";
                }
            }

            //echo $sqlLoadDataInFileUC;
            //exit();



            $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
            $prLoadDataInFileUC->execute();

            $updatecomas='  UPDATE
                            tmp_cobranzas_andina
                            SET
                            importe_original=REPLACE(importe_original,",",""),
                            saldo=REPLACE(saldo,",",""),
                            soles=REPLACE(soles,",",""),
                            dolares=REPLACE(dolares,",","")';
            $prupdatecomas = $connection->prepare($updatecomas);
            $prupdatecomas->execute();


            $insertCartera = "";
            $id_cartera = 0;
            if(trim($gestion)==''){
                $insertCartera=" INSERT INTO ca_cartera( nombre_cartera,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, moneda_cuenta, moneda_operacion, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion,adicionales )
                                                VALUES ( '".$nombre_cartera."',".$_post['Campania'].",NOW(),( SELECT COUNT(*) FROM $nombre_tabla ),'$nombre_tabla','".utf8_encode($_post["file"])."',".$_post['usuario_creacion'].",NOW() ,'".$parserHeader."','".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$moneda_cuenta."','".$moneda_operacion."','".$parserCliente."', '".$parserCartera."' ,'".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."' ) ";
                $prInsertCartera = $connection->prepare($insertCartera);
                if ($prInsertCartera->execute()){
                    $id_cartera = 0;
                    if(trim($gestion)==''){
                        $id_cartera=$connection->lastInsertId();
                        $updateTMPCartera="UPDATE $nombre_tabla SET idcartera= $id_cartera;";
                        $prUpdateTMPCartera = $connection->prepare($updateTMPCartera);
                        if( $prUpdateTMPCartera->execute() ) {

                        }else{
                            return array('rst' => false, 'msg' => 'Error al actualizar idcartera en temporal');
                            exit();
                        }
                    }
                }else{
                    return array('rst' => false, 'msg' => 'Error al insertar cartera');
                    exit();
                }

                if ($is_parser==1){
                    $insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador, moneda_cuenta, moneda_operacion )
                                                            VALUES (".$_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','".$parserCartera."','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "','" . $moneda_cuenta . "','" . $moneda_operacion . "' ) ";
                    $prInsertJsonParser = $connection->prepare($insertJsonParser);
                    if ($prInsertJsonParser->execute()) {
                        
                        /*INSERT CA_CLIENTE*/
                        $insertCliente = " ";
                        $campoTableClienteTMP = array();
                        $campoTableCliente = array();

                        for ($i = 0; $i < count($jsonCliente); $i++) {
                            if ($jsonCliente[$i]['campoT'] == 'codigo') {
                                array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
                            } else {
                                array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                array_push($campoTableClienteTMP, "TRIM(".$jsonCliente[$i]['dato'].")");
                            }
                        }

                        $insertCliente = "  INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " )
                                            SELECT 
                                            " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . "
                                            FROM 
                                            $nombre_tabla
                                            WHERE 
                                            LENGTH( TRIM($codigo_cliente) )>0 ".$sql_where_main."
                                            GROUP BY TRIM($codigo_cliente) 
                                        ";
						//echo $insertCliente;
						//exit();

                        $prInsertCliente = $connection->prepare($insertCliente);
                        if ($prInsertCliente->execute()) {

                            $sqlTMPUpdateIdCliente = "  UPDATE 
                                                        $nombre_tabla tmp 
                                                        INNER JOIN ca_cliente cli ON TRIM(cli.codigo) = TRIM(tmp.$codigo_cliente)
                                                        SET 
                                                        tmp.idcliente = cli.idcliente
                                                        WHERE 
                                                        cli.idservicio = " . $_post['Servicio'] . " ".$sql_where_main_tmp."
                                                    ";
							//echo $sqlTMPUpdateIdCliente;
							//exit();
							
                            $prTMPUpdateIdCliente = $connection->prepare($sqlTMPUpdateIdCliente);
                            if ($prTMPUpdateIdCliente->execute()){
                                
                                $campoTableClienteCarteraTMP = array();
                                $campoTableClienteCartera = array();

                                for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cliente']);$i++) {
                                    array_push($campoTableClienteCartera, $jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']);
                                    array_push($campoTableClienteCarteraTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['dato']." ) ");
                                }

                                $implode_cc_t = "";
                                $implode_cc_tmp = "";

                                if( count($campoTableClienteCartera) >0 ){
                                    $implode_cc_t = ", ".implode(",",$campoTableClienteCartera);
                                    $implode_cc_tmp = ", ".implode(",",$campoTableClienteCarteraTMP);
                                }

                                $field_cartera = "";
                                $field_group = "";
                                ( trim($gestion) == '' )
                                ?
                                    $field_cartera = " ".$id_cartera." "
                                :
                                    $field_cartera = " idcartera ";
                                    $field_group = " idcartera , ";
                                ;

                                $InsertClienteCartera = "   INSERT IGNORE INTO ca_cliente_cartera ( idcliente, codigo_cliente,tipo_cliente,idcartera,usuario_creacion,fecha_creacion ".$implode_cc_t." )
                                                            SELECT 
                                                            idcliente, 
                                                            TRIM($codigo_cliente),
                                                            tipo_cliente,
                                                            ".$field_cartera." ,
                                                            ".$usuario_creacion.",
                                                            NOW()
                                                            ".$implode_cc_tmp."
                                                            FROM 
                                                            $nombre_tabla
                                                            WHERE 
                                                            LENGTH( TRIM($codigo_cliente) )>0 AND 
                                                            ISNULL(idcliente) = 0 
                                                            ".$sql_where_main."
                                                            GROUP BY ".$field_group." TRIM($codigo_cliente) ";


                                $prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
                                if ($prInsertClienteCartera->execute()){
                                    
                                    $field_on = "";
                                    $field_where = "";
                                    ( trim($gestion) == '' )?$field_on = " " : $field_on = " AND clicar.idcartera = tmp.idcartera ";
                                    ( trim($gestion) == '' )?$field_where = " WHERE ISNULL(tmp.idcliente) = 0 AND clicar.idcartera = ".$id_cartera." ".$sql_where_main_tmp." " : $field_where = " WHERE ISNULL(tmp.idcliente) = 0 ".$sql_where_main_tmp." ";
                                    
                                    $sqlTMPUpdateIdClienteCartera = "   UPDATE 
                                                                        $nombre_tabla tmp 
                                                                        INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = tmp.idcliente ".$field_on."
                                                                        SET 
                                                                        tmp.idcliente_cartera = clicar.idcliente_cartera ".$field_where;
                                    $prTMPUpdateIdClienteCartera = $connection->prepare($sqlTMPUpdateIdClienteCartera);
                                    if ($prTMPUpdateIdClienteCartera->execute()){
                                        
                                        $campoTableCuentaTMP = array();
                                        $campoTableCuenta = array();
                                        $field_moneda = 0;
                                        for ($i = 0; $i < count($jsonCuenta); $i++) {
                                            if ( $jsonCuenta[$i]['campoT'] == 'total_deuda' || $jsonCuenta[$i]['campoT'] == 'monto_mora' || $jsonCuenta[$i]['campoT'] == 'saldo_capital' ) {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                            } else if ($jsonCuenta[$i]['campoT'] == 'monto_pagado') {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                            } else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                            } else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                $field_moneda = 1;
                                            } else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                            } else {
                                                array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                array_push($campoTableCuentaTMP, $jsonCuenta[$i]['dato']);
                                            }
                                        }

                                        if( $field_moneda==0 ){
                                            array_push($campoTableCuenta, " moneda ");
                                            array_push($campoTableCuentaTMP, " '' ");
                                        }

                                        for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cuenta']);$i++) {
                                            array_push($campoTableCuenta, $jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']);
                                            array_push($campoTableCuentaTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['dato']." ) ");
                                        }

                                        $field_cartera = "";
                                        $field_group = "";
                                        $field_where = "";
                                        $field_on = "";
                                        $field_u_where = "";
                                        if( trim($gestion) == '' )  {
                                            $field_cartera = " ".$id_cartera." ";
                                            $field_u_where = " AND cu.idcartera = ".$id_cartera." ";

                                        }else{
                                            $field_cartera = " idcartera ";
                                            $field_group = " , idcartera ";
                                            $field_where = " AND ISNULL(tmp.idcartera)=0 ".$sql_where_main_tmp." ";
                                            $field_on = " AND tmp.idcartera = cu.idcartera ";
                                        }

                                        $insertCuenta = "";
                                        $sqlTMPUpdateIdCuenta = "";

                                        $field_on_c = $field_on;
                                        $group_by_c = $field_group;
                                        if($moneda_cuenta != '')
                                        {
                                            $field_on_c = $field_on." AND cu.moneda = TRIM( tmp.$moneda_cuenta ) ";
                                            $group_by_c = $field_group." , TRIM($moneda_cuenta) ";
                                        }

                                        if($grupo1_cuenta != '')
                                        {
                                            $field_on_c = $field_on." AND cu.grupo1_cuenta = TRIM( tmp.$grupo1_cuenta ) ";
                                            $group_by_c = $field_group." , TRIM($grupo1_cuenta) ";
                                        }

                                        $insertCuenta = "   INSERT IGNORE INTO ca_cuenta ( idcliente_cartera, codigo_cliente,dato1, idcartera, estado, fecha_creacion, usuario_creacion,dato2,dato3,  " . implode(",", $campoTableCuenta) . " )
                                                            SELECT 
                                                            idcliente_cartera, 
                                                            TRIM($codigo_cliente),
                                                            llave,
                                                            ".$field_cartera.", 
                                                            1,
                                                            NOW(), 
                                                            $usuario_creacion,
                                                            td,
                                                            empresa,
                                                            " . implode(",", $campoTableCuentaTMP) . "
                                                            FROM 
                                                            $nombre_tabla 
                                                            tmp
                                                            WHERE 
                                                            LENGTH( TRIM($codigo_cliente) )>0 AND 
                                                            LENGTH( TRIM( $numero_cuenta ) )>0 AND 
                                                            ISNULL( idcliente_cartera ) = 0 
                                                            ".$field_where."
                                                            GROUP BY empresa,cod_cliente,td,idcliente_cartera,TRIM($numero_cuenta)".$group_by_c." ";

                                        $prInsertCuenta = $connection->prepare($insertCuenta);
                                        if ($prInsertCuenta->execute()){
                                            $sqlTMPUpdateIdCuenta = "   UPDATE 
                                                                        $nombre_tabla tmp 
                                                                        INNER JOIN ca_cuenta cu ON tmp.llave = cu.dato1
                                                                        ".$field_on_c."
                                                                        SET 
                                                                        tmp.idcuenta=cu.idcuenta
                                                                        WHERE ISNULL(tmp.idcliente_cartera) = 0 ".$sql_where_main_tmp."  ".$field_u_where." ";

                                            $prTMPUpdateIdCuenta = $connection->prepare($sqlTMPUpdateIdCuenta);
                                            if($prTMPUpdateIdCuenta->execute()){
                                                
                                                if(count($jsonOperacion)>0){
                                                    $campoTableOperacionTMP = array();
                                                    $campoTableOperacion = array();
                                                    $fieldTramo = "";
                                                    for ($i = 0; $i < count($jsonOperacion); $i++) {
                                                        if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                        } else if ($jsonOperacion[$i]['campoT'] == 'moneda' || $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                        } else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                            $fieldTramo = $jsonOperacion[$i]['dato'];
                                                        } else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento' || $jsonOperacion[$i]['campoT'] == 'fecha_asignacion' || $jsonOperacion[$i]['campoT'] == 'fecha_baja' || $jsonOperacion[$i]['campoT'] == 'fecha_alta' || $jsonOperacion[$i]['campoT'] == 'fecha_ciclo' || $jsonOperacion[$i]['campoT'] == 'fecha_emision' ) {
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, "
                                                                CASE
                                                                WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 8
                                                                THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",7,2))
                                                                WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 10
                                                                THEN
                                                                    CASE
                                                                    WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 3
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                    WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 5
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                    WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'-') = 3
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                    WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 3
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                    WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 5
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                    ELSE TRIM(".$jsonOperacion[$i]['dato'].")
                                                                    END
                                                                ELSE TRIM(DATE(STR_TO_DATE(".$jsonOperacion[$i]['dato'].", '%d/%m/%Y')))
                                                                END  ");
                                                        } else if( $jsonOperacion[$i]['campoT'] == 'total_deuda' || $jsonOperacion[$i]['campoT'] == 'total_deuda_soles' || $jsonOperacion[$i]['campoT'] == 'total_deuda_dolares' || $jsonOperacion[$i]['campoT'] == 'monto_mora' || $jsonOperacion[$i]['campoT'] == 'monto_mora_soles' || $jsonOperacion[$i]['campoT'] == 'monto_mora_dolares' || $jsonOperacion[$i]['campoT'] == 'saldo_capital' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_soles' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_dolares' ){
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " SUM( ".$jsonOperacion[$i]['dato']." ) ");
                                                        } else {
                                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$jsonOperacion[$i]['dato']." ) ");
                                                        }
                                                    }


                                                    array_push($campoTableOperacion, " codigo_cliente " );
                                                    array_push($campoTableOperacionTMP, " TRIM( ".$codigo_cliente.")");

                                                    array_push($campoTableOperacion, " numero_cuenta " );
                                                    array_push($campoTableOperacionTMP, " TRIM( ".$numero_cuenta.")");

                                                    if( $moneda_cuenta != '' ) {
                                                        array_push($campoTableOperacion, " moneda_cuenta " );
                                                        array_push($campoTableOperacionTMP, " TRIM( ".$moneda_cuenta.")");
                                                    }

                                                    if( $grupo1_cuenta != '' ) {
                                                        array_push($campoTableOperacion, " grupo1_cuenta " );
                                                        array_push($campoTableOperacionTMP, " TRIM( ".$grupo1_cuenta.")");
                                                    }

                                                    for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_detalle_cuenta']);$i++) {
                                                        array_push($campoTableOperacion, $jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
                                                        array_push($campoTableOperacionTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['dato']." ) ");
                                                    }

                                                    $field_group_o = $field_group;
                                                    if( $moneda_operacion != '' ) {
                                                        $field_group_o .= " , ".$moneda_operacion;
                                                    }
                                                    if( $grupo1_operacion != '' ) {
                                                        $field_group_o .= " , ".$grupo1_operacion;
                                                    }

                                                    $insertOperacion = "    INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", idcartera, usuario_creacion,dato45,fecha_creacion, idcuenta )
                                                                            SELECT 
                                                                            ".implode(",", $campoTableOperacionTMP) . ", ".$field_cartera." , 
                                                                            $usuario_creacion , 
                                                                            llave,
                                                                            NOW() , 
                                                                            idcuenta
                                                                            FROM 
                                                                            $nombre_tabla tmp
                                                                            WHERE 
                                                                            LENGTH( TRIM( $codigo_cliente ) ) > 0 AND 
                                                                            LENGTH( TRIM( $numero_cuenta ) ) > 0 AND 
                                                                            LENGTH( TRIM( $codigo_operacion ) ) > 0 AND 
                                                                            ISNULL( idcuenta ) = 0 ".$field_where."
                                                                            GROUP BY 
                                                                            idcuenta,empresa,cod_cliente,td,TRIM( $codigo_operacion ) ".$field_group_o." ";

                                                    // echo $insertOperacion;
                                                    // exit();

                                                    $prInsertOperacion = $connection->prepare($insertOperacion);
                                                    if ($prInsertOperacion->execute()){
                                                        $sqlTMPUpdateIdDetalleCuenta = "    UPDATE 
                                                                                            $nombre_tabla tmp 
                                                                                            INNER JOIN ca_detalle_cuenta detcu ON tmp.llave=detcu.dato45 AND detcu.idcartera=$field_cartera
                                                                                            SET 
                                                                                            tmp.iddetalle_cuenta=detcu.iddetalle_cuenta";


                                                        $prTMPUpdateIdDetalleCuenta = $connection->prepare($sqlTMPUpdateIdDetalleCuenta);
                                                        if($prTMPUpdateIdDetalleCuenta->execute()){

                                                            

                                                            $insertmail="SELECT cod_cliente,correo,idcliente FROM $nombre_tabla WHERE correo<>'' GROUP BY cod_cliente,correo,idcliente;";
                                                            $prinsertmail=$connection->prepare($insertmail);
                                                            if($prinsertmail->execute()){

                                                                $ar_correos=$prinsertmail->fetchAll(PDO::FETCH_ASSOC);

                                                                for ($i=0; $i <=count($ar_correos)-1 ; $i++){
                                                                    $ar_mail=explode(',', $ar_correos[$i]['correo']);
                                                                    for ($j=0; $j <=count($ar_mail)-1 ; $j++) { 
                                                                        $insert_mail="INSERT IGNORE `cob_andina`.`ca_correo`(`codigo_cliente`,`correo`,`estado`,`usuario_creacion`,`fecha_creacion`,idcliente) VALUES ('".$ar_correos[$i]['cod_cliente']."','".$ar_mail[$j]."',1,1,NOW(),'".$ar_correos[$i]['idcliente']."')";
                                                                        $pr_insert_mail = $connection->prepare($insert_mail);
                                                                        $pr_insert_mail->execute();
                                                                    }
                                                                }

                                                                $insertfone="SELECT cod_cliente,telefono,idcartera,idcliente_cartera FROM $nombre_tabla WHERE telefono<>'' GROUP BY cod_cliente,telefono,idcartera,idcliente_cartera";
                                                                $prinsertfone=$connection->prepare($insertfone);
                                                                if($prinsertfone->execute()){
                                                                    $ar_fone=$prinsertfone->fetchAll(PDO::FETCH_ASSOC);

                                                                    for ($k=0; $k <=count($ar_fone)-1 ; $k++) {
                                                                        $ar_telf=explode(',', $ar_fone[$k]['telefono']);
                                                                        for ($n=0; $n <=count($ar_telf)-1 ; $n++) {
                                                                            $insert_fone="  INSERT IGNORE ca_telefono(idtipo_telefono,numero,usuario_creacion,fecha_creacion,idorigen,idtipo_referencia,idcartera,is_import,codigo_cliente,estado,is_new,is_active,idcliente_cartera,status) VALUES (8,'".$ar_telf[$n]."',$usuario_creacion,NOW(),1,1,'".$ar_fone[$k]['idcartera']."',0,'".$ar_fone[$k]['cod_cliente']."',1,1,1,'".$ar_fone[$k]['idcliente_cartera']."','CORRECTO')";
                                                                            $pr_insert_fone = $connection->prepare($insert_fone);
                                                                            $pr_insert_fone->execute();
                                                                        }

                                                                    }

                                                                    $fecha_file= str_replace(" ","-",str_replace(".txt","",substr($file, -12)));
                                                                    $arr=explode("-",$fecha_file);
                                                                    $arr_d=$arr[0];
                                                                    $arr_m=$arr[1];
                                                                    $arr_y=$arr[2];
                                                                    $fech_load = $arr_y."-".$arr_m."-".$arr_d;
                                                                    $date = new DateTime($fech_load);

                                                                    $id_servicio=$_POST['Servicio'];
                                                                    $id_campania=$_POST['Campania'];
                                                                    $min_load=date('h:i:s');
                                                                    $fecha_carga = $date->format('Y-m-d '.$min_load);
                                                                    $fecha_gestion = $date->format('Y-m-d');


                                                                    // $sql_his_cob_andina = "     INSERT INTO ca_historico_cobranza_andina (
                                                                    //                             cod_zon,
                                                                    //                             empresa,
                                                                    //                             zona,
                                                                    //                             tienda,
                                                                    //                             localidad,
                                                                    //                             externo,
                                                                    //                             vend_actual,
                                                                    //                             vend_rtc_actual,
                                                                    //                             supervisor,
                                                                    //                             tipo_cliente,
                                                                    //                             cod_cliente,
                                                                    //                             cliente,
                                                                    //                             td,
                                                                    //                             num_doc,
                                                                    //                             fecha_doc,
                                                                    //                             mes_emis,
                                                                    //                             ano_emis,
                                                                    //                             dias_plazo,
                                                                    //                             fecha_vcto,
                                                                    //                             m_vcto,
                                                                    //                             ano_vcto,
                                                                    //                             fecha_al,
                                                                    //                             dias_transc_vcto_of,
                                                                    //                             tipo_de_operacion,
                                                                    //                             rango_vcto,
                                                                    //                             linea_de_credito,
                                                                    //                             ind_vcto,
                                                                    //                             semaforo_de_vencimiento,
                                                                    //                             mon,
                                                                    //                             importe_original,
                                                                    //                             saldo,
                                                                    //                             tp,
                                                                    //                             soles,
                                                                    //                             dolares,
                                                                    //                             total_convertido_a_dolares,
                                                                    //                             total_convertido_a_soles,
                                                                    //                             glosa,
                                                                    //                             est_letr,
                                                                    //                             banco,
                                                                    //                             num_cobranza,
                                                                    //                             referencia,
                                                                    //                             llave,
                                                                    //                             idservicio,
                                                                    //                             idcampania,
                                                                    //                             idcartera,
                                                                    //                             idcliente,
                                                                    //                             idcliente_cartera,
                                                                    //                             idcuenta,
                                                                    //                             iddetalle_cuenta,
                                                                    //                             fecha_carga,
                                                                    //                             fecha_gestion,
                                                                    //                             retiro,
                                                                    //                             estado,
                                                                    //                             carga_valida
                                                                    //                             )
                                                                    //                             SELECT
                                                                    //                             cod_zon,
                                                                    //                             empresa,
                                                                    //                             zona,
                                                                    //                             tienda,
                                                                    //                             localidad,
                                                                    //                             externo,
                                                                    //                             vend_actual,
                                                                    //                             vend_rtc_actual,
                                                                    //                             supervisor,
                                                                    //                             tipo_cliente,
                                                                    //                             cod_cliente,
                                                                    //                             cliente,
                                                                    //                             td,
                                                                    //                             num_doc,
                                                                    //                             fecha_doc,
                                                                    //                             mes_emis,
                                                                    //                             ano_emis,
                                                                    //                             dias_plazo,
                                                                    //                             fecha_vcto,
                                                                    //                             m_vcto,
                                                                    //                             ano_vcto,
                                                                    //                             fecha_al,
                                                                    //                             dias_transc_vcto_of,
                                                                    //                             tipo_de_operacion,
                                                                    //                             rango_vcto,
                                                                    //                             linea_de_credito,
                                                                    //                             ind_vcto,
                                                                    //                             semaforo_de_vencimiento,
                                                                    //                             mon,
                                                                    //                             importe_original,
                                                                    //                             saldo,
                                                                    //                             tp,
                                                                    //                             soles,
                                                                    //                             dolares,
                                                                    //                             total_convertido_a_dolares,
                                                                    //                             total_convertido_a_soles,
                                                                    //                             glosa,
                                                                    //                             est_letr,
                                                                    //                             banco,
                                                                    //                             num_cobranza,
                                                                    //                             referencia,
                                                                    //                             llave,
                                                                    //                             $id_servicio,
                                                                    //                             $id_campania,
                                                                    //                             idcartera,
                                                                    //                             idcliente,
                                                                    //                             idcliente_cartera,
                                                                    //                             idcuenta,
                                                                    //                             iddetalle_cuenta,
                                                                    //                             '$fecha_carga',
                                                                    //                             '$fecha_gestion',
                                                                    //                             0,
                                                                    //                             1,
                                                                    //                             1
                                                                    //                             FROM
                                                                    //                             tmp_cobranzas_andina";
                                                                    // $pr_sql_his_cob_andina = $connection->prepare($sql_his_cob_andina);
                                                                    // if($pr_sql_his_cob_andina->execute()){
                                                                    //     return array('rst' => true, 'msg' => 'Se cargo Correctamente');
                                                                    // }else{
                                                                    //     return array('rst' => false, 'msg' => 'Error al insertar historico');
                                                                    //     exit();
                                                                    // }

                                                                    return array('rst' => true, 'msg' => 'Se cargo Correctamente');

                                                                }else{
                                                                    return array('rst' => false, 'msg' => 'Error al insertar telefonos');
                                                                    exit();
                                                                }

                                                            }else{
                                                                return array('rst' => false, 'msg' => 'Error al insertar correos');
                                                                exit();
                                                            }


                                                        }else{
                                                            return array('rst' => false, 'msg' => 'Error al Actualizar iddetalle_cuenta en temporal');
                                                            exit();
                                                        }
                                                    }else{
                                                        return array('rst' => false, 'msg' => 'Error al insertar ca_detalle_cuenta');
                                                        exit();
                                                    }
                                                }
                                            }else{
                                                return array('rst' => false, 'msg' => 'Error al Actualizar idcuenta en temporal');
                                                exit();
                                            }
                                        }else{
                                            return array('rst' => false, 'msg' => 'Error al insertar ca_cuenta');
                                            exit();
                                        }
                                    }else{
                                        return array('rst' => false, 'msg' => 'Error al Actualizar idcliente_cartera en temporal');
                                        exit();
                                    }
                                }else{
                                    return array('rst' => false, 'msg' => 'Error al insertar ca_cliente_cartera');
                                    exit();
                                }
                            }else{
                                return array('rst' => false, 'msg' => 'Error al actualizar idcliente en temporal');
                                exit();
                            }
                        }else{
                            return array('rst' => false, 'msg' => 'Error al insertar ca_cliente');
                            exit();
                        }
                    } else {
                        return array('rst' => false, 'msg' => 'Error al insertar metadata');
                        exit();
                    }
                }
            }

            // FIN COBRANZAS
        }else if($idcampania==2){
            // INICIO POST-VENTA
            
            // FIN POST-VENTA
        }

    }

    public function uploadUpdateCartera($_post, $is_parser=0,$file) {
        $idcampania=$_POST['Campania'];
        if($idcampania==1){
            // INICIO ACTUALIZACION COBRANZAS
            $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
            $usuario_creacion = $_post["usuario_creacion"];

            $tipo_data = (int)$_post["TipoData"];
            $cabecera_departamento = $_post["CabeceraDepartamento"];

            $nombre_cartera = $_post["NombreCartera"];
            $codigo_cliente = "";
            $numero_cuenta = "";
            $codigo_operacion = "";
            $moneda_cuenta = "";
            $moneda_operacion = "";
            $grupo1_cuenta = "";
            $grupo1_operacion = "";
            $gestion = "";

            $parserCliente = str_replace("\\", "", $_post["data_cliente"]);
            $parserCartera = str_replace("\\", "", $_post["data_cartera"]);
            $parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
            $parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
            $parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
            $parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
            $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

            $jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
            $jsonCartera = json_decode(str_replace("\\", "", $_post["data_cartera"]), true);
            $jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
            $jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
            $jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
            $jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
            $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

            $sql_where_main = " ";
            $sql_where_main_tmp = " ";
            if( $tipo_data == 1 ) {

            }else if ( $tipo_data == 2 ){
                $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
                $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
            }else if ( $tipo_data == 3 ) {
                $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
                $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
            }else{
                echo json_encode(array('rst' => false, 'msg' => 'Tipo incorrecto de carga'));
                exit();
            }

            for( $i=0;$i<count($jsonCliente);$i++ ) {
                if( $jsonCliente[$i]['campoT'] == 'codigo' ) {
                    $codigo_cliente = $jsonCliente[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonCartera);$i++ ) {
                if( $jsonCartera[$i]['campoT'] == 'nombre_cartera' ) {
                    $gestion = $jsonCartera[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonCuenta);$i++ ) {
                if( $jsonCuenta[$i]['campoT'] == 'numero_cuenta' ) {
                    $numero_cuenta = $jsonCuenta[$i]['dato'];
                }else if( $jsonCuenta[$i]['campoT'] == 'moneda' ){
                    $moneda_cuenta = $jsonCuenta[$i]['dato'];
                }else if( $jsonCuenta[$i]['campoT'] == 'grupo1' ) {
                    $grupo1_cuenta = $jsonCuenta[$i]['dato'];
                }
            }

            for( $i=0;$i<count($jsonOperacion);$i++ ) {
                if( $jsonOperacion[$i]['campoT'] == 'codigo_operacion' ) {
                    $codigo_operacion = $jsonOperacion[$i]['dato'];
                }else if( $jsonOperacion[$i]['campoT'] == 'moneda' ){
                    $moneda_operacion = $jsonOperacion[$i]['dato'];
                }else if( $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
                    $grupo1_operacion = $jsonOperacion[$i]['dato'];
                }
            }

            

            if (!isset($confCobrast['ruta_cobrast'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
                exit();
            }

            $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
            if (!file_exists($path)) {
                return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
                exit();
            }


            $time = date("Y_m_d_H_i_s");
            $archivoParser = @fopen($path, "r+");
            $columMap = array();
            if ($_post['separator'] == 'tab') {
                $columMap = explode("\t", fgets($archivoParser));
            } else {
                $columMap = explode($_post['separator'], fgets($archivoParser));
            }

            fclose($archivoParser);

            if (!function_exists('map_header')) {
                function map_header($n) {
                    $item = "";
                    if (trim(utf8_encode($n)) != "") {
                        $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                        $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "");
                        $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                    }
                    return $item;
                }
            }

            $colum = array_map("map_header", $columMap);
            $columHeader = array();
            $countHeaderFalse = 0;

            for ($i = 0; $i < count($colum); $i++) {
                if ($colum[$i] != "") {
                    array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
                } else {
                    $countHeaderFalse++;
                }
            }

            $parserHeader = implode(",", $colum);

            array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
            array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
            if ($codigo_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
            }
            if ($moneda_cuenta != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
            }
            if ($moneda_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
            }
            if ($grupo1_cuenta != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_cuenta` ( `$grupo1_cuenta` ASC ) ");
            }
            if ($grupo1_operacion != '') {
                array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_operacion` ( `$grupo1_operacion` ASC ) ");
            }
            if ($gestion != "") {
                array_push($columHeader, "INDEX `index_" . session_id() . "_gestion` ( `$gestion` ASC ) ");
            }
            if ($countHeaderFalse > 0) {
                return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
                exit();
            }

            $nombre_tabla="tmp_cobranzas_andina";

            $factoryConnection = FactoryConnection::create('mysql');
            $connection = $factoryConnection->getConnection();

            $truncate_tmp_cobranzas="TRUNCATE TABLE tmp_cobranzas_andina;";
            $prLoadData_tmp_cobranzas = $connection->prepare($truncate_tmp_cobranzas);
            $prLoadData_tmp_cobranzas->execute();

            if($_post["NombreServicio"]=='ANDINA' ){
                if ($_post['separator'] == 'tab') {
                    $sqlLoadDataInFileUC = "    LOAD DATA LOCAL INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                                                INTO TABLE $nombre_tabla FIELDS TERMINATED BY '\\\t' LINES  TERMINATED BY '\\\r\\\n' IGNORE 1 LINES SET llave=CONCAT(empresa,'@',cod_cliente,'@',td,'@',num_doc,'@',mon)";
                } else {
                    $sqlLoadDataInFileUC = "    LOAD DATA LOCAL INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                                                INTO TABLE $nombre_tabla FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\r\n' IGNORE 1 LINES SET llave=CONCAT(empresa,'@',cod_cliente,'@',td,'@',num_doc,'@',mon)";
                }
            }
            $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
            $prLoadDataInFileUC->execute();

            $updatecomas='  UPDATE
                            tmp_cobranzas_andina
                            SET
                            importe_original=REPLACE(importe_original,",",""),
                            saldo=REPLACE(saldo,",",""),
                            soles=REPLACE(soles,",",""),
                            dolares=REPLACE(dolares,",","")';
            $prupdatecomas = $connection->prepare($updatecomas);
            $prupdatecomas->execute();

            $id_cartera = $_post['Cartera'];

            $updateTMPCartera="UPDATE $nombre_tabla SET idcartera= $id_cartera;";
            $prUpdateTMPCartera = $connection->prepare($updateTMPCartera);
            if( $prUpdateTMPCartera->execute() ) {




                $sqlUpdateCartera=  "   UPDATE ca_cartera
                                        SET
                                        tabla = '$nombre_tabla',
                                        archivo = '$file',                            
                                        usuario_modificacion = $usuario_creacion,
                                        fecha_modificacion = NOW(),
                                        cabeceras = '$parserHeader',
                                        nombre_cartera = '$nombre_cartera',
                                        codigo_cliente = '$codigo_cliente',
                                        numero_cuenta = '$numero_cuenta',
                                        moneda_cuenta = '$moneda_cuenta',
                                        codigo_operacion = '$codigo_operacion',
                                        moneda_operacion = '$moneda_operacion',
                                        cliente = '$parserCliente',
                                        cartera = '$parserCartera',
                                        cuenta = '$parserCuenta',
                                        detalle_cuenta = '$parserOperacion',
                                        telefono = '$parserTelefono',
                                        direccion = '$parserDireccion',
                                        adicionales = '$parserAdicionales',
                                        cantidad=( SELECT COUNT(*) FROM $nombre_tabla )
                                        WHERE 
                                        idcartera IN ($id_cartera)
                                    ";
                $prUpdateCartera = $connection->prepare($sqlUpdateCartera) ;
                if($prUpdateCartera->execute()){
                    if ($is_parser == 1) {
                        $insertJsonParser = "   INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador, moneda_cuenta, moneda_operacion )
                                                VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','".$parserCartera."','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "','" . $moneda_cuenta . "','" . $moneda_operacion . "' ) ";

                        $prInsertJsonParser = $connection->prepare($insertJsonParser);
                        if ($prInsertJsonParser->execute()) {

                            $insertCliente = " ";
                            $campoTableClienteTMP = array();
                            $campoTableCliente = array();
                            $campoUpdateTableCliente = array();

                            for ($i = 0; $i < count($jsonCliente); $i++) {
                                if ($jsonCliente[$i]['campoT'] == 'codigo') {
                                    array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                    array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
                                } else {
                                    array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                    array_push($campoTableClienteTMP, "TRIM(".$jsonCliente[$i]['dato'].")");
                                    array_push($campoUpdateTableCliente," ".$jsonCliente[$i]['campoT']." = VALUES ( ".$jsonCliente[$i]['campoT']." ) ");
                                }
                            }
                            // SI EL RUC/DNI VUELVE A VENIR SE ACTUALIZA NUMERO DOCUMENTO Y RAZON SOCIAL
                            $insertCliente = "  INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " )
                                                SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . "
                                                FROM $nombre_tabla
                                                WHERE LENGTH( TRIM($codigo_cliente) )>0 ".$sql_where_main."
                                                GROUP BY TRIM($codigo_cliente) ON DUPLICATE KEY UPDATE ".implode(",",$campoUpdateTableCliente); //

                            $prInsertCliente = $connection->prepare($insertCliente);
                            if ($prInsertCliente->execute()){
                                $sqlTMPUpdateIdCliente = "  UPDATE $nombre_tabla tmp 
                                                            INNER JOIN ca_cliente cli ON RTRIM(LTRIM(cli.codigo)) = RTRIM(LTRIM(tmp.$codigo_cliente))
                                                            SET tmp.idcliente = cli.idcliente
                                                            WHERE cli.idservicio = " . $_post['Servicio'] . " ".$sql_where_main_tmp." ";                                    
							
                                $prTMPUpdateIdCliente = $connection->prepare($sqlTMPUpdateIdCliente);
                                if ($prTMPUpdateIdCliente->execute()){

                                    $campoTableClienteCarteraTMP = array();
                                    $campoTableClienteCartera = array();
                                    $campoUpdateTableClienteCartera = array();

                                    for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cliente']);$i++) {
                                        array_push($campoTableClienteCartera, $jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']);
                                        array_push($campoTableClienteCarteraTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['dato']." ) ");
                                        array_push($campoUpdateTableClienteCartera, " ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']." = VALUES( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']." ) ");
                                    }

                                    $implode_cc_t = "";
                                    $implode_cc_tmp = "";
                                    $implode_cc_u_t = "";

                                    if( count($campoTableClienteCartera) > 0 ){
                                        $implode_cc_t = ", ".implode(",",$campoTableClienteCartera);
                                        $implode_cc_tmp = ", ".implode(",",$campoTableClienteCarteraTMP);
                                    }
                                    if( count($campoUpdateTableClienteCartera)>0 ) {
                                        $implode_cc_u_t = " , ".implode(",",$campoUpdateTableClienteCartera);
                                    }

                                    $field_cartera = "";
                                    $field_group = "";
                                    ( trim($gestion) == '' )
                                    ?
                                        $field_cartera = " ".$id_cartera." "
                                    :
                                        $field_cartera = " idcartera ";
                                        $field_group = " idcartera , ";
                                    ;

                                    $EstadoClienteCartera = "UPDATE ca_cliente_cartera SET estado=0 where idcartera=".$id_cartera;
                                    $prEstadoClienteCartera=$connection->prepare($EstadoClienteCartera);
                                    if ($prEstadoClienteCartera->Execute()){
                                        
                                    }else{
                                        return array('rst' => false, 'msg' => 'Error al actualizar estado cliente');
                                    }

                                    $InsertClienteCartera = "   INSERT INTO ca_cliente_cartera (idcliente,tipo_cliente,codigo_cliente,idcartera,usuario_creacion,fecha_creacion ".$implode_cc_t." )
                                                                SELECT 
                                                                idcliente,
                                                                tipo_cliente,
                                                                TRIM($codigo_cliente),
                                                                ".$field_cartera." ,
                                                                " . $usuario_creacion . ",
                                                                NOW()  
                                                                ".$implode_cc_tmp."
                                                                FROM 
                                                                $nombre_tabla tmp
                                                                WHERE 
                                                                LENGTH( TRIM($codigo_cliente) )>0 AND 
                                                                ISNULL(idcliente) = 0 
                                                                ".$sql_where_main."
                                                                ON DUPLICATE KEY UPDATE 
                                                                estado = 1,
                                                                tipo_cliente=tmp.tipo_cliente
                                                                ".$implode_cc_u_t." ";
									
                                    
                                    $prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
                                    if ($prInsertClienteCartera->execute()){
                                        $field_on = "";
                                        $field_where = "";
                                        ( trim($gestion) == '' )?$field_on = " " : $field_on = " AND clicar.idcartera = tmp.idcartera ";
                                        ( trim($gestion) == '' )?$field_where = " WHERE clicar.idcartera = ".$id_cartera." ".$sql_where_main_tmp." " : $field_where = " WHERE ISNULL(tmp.idcliente) = 0 ".$sql_where_main_tmp." ";
                                        
                                        $sqlTMPUpdateIdClienteCartera = "   UPDATE 
                                                                            $nombre_tabla tmp 
                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = tmp.idcliente ".$field_on."
                                                                            SET 
                                                                            tmp.idcliente_cartera= clicar.idcliente_cartera ".$field_where;
                                        $prTMPUpdateIdClienteCartera = $connection->prepare($sqlTMPUpdateIdClienteCartera);
                                        if ($prTMPUpdateIdClienteCartera->execute()){

                                            $campoTableCuentaTMP = array();
                                            $campoTableCuenta = array();
                                            $campoUpdateTableCuenta = array();

                                            $field_moneda = 0;
                                            for ($i = 0; $i < count($jsonCuenta); $i++) {
                                                if ( $jsonCuenta[$i]['campoT'] == 'total_deuda' || $jsonCuenta[$i]['campoT'] == 'monto_mora' || $jsonCuenta[$i]['campoT'] == 'saldo_capital' ) {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    /********************/
                                                    array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = VALUES ( ".$jsonCuenta[$i]['campoT']." ) ");
                                                } else if ($jsonCuenta[$i]['campoT'] == 'monto_pagado') {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    /********************/
                                                    array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = VALUES ( ".$jsonCuenta[$i]['campoT']." ) ");
                                                } else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                } else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    $field_moneda = 1;
                                                } else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    /********************/
                                                    array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = VALUES ( ".$jsonCuenta[$i]['campoT']." ) ");
                                                } else {
                                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, $jsonCuenta[$i]['dato']);
                                                    /********************/
                                                    array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = VALUES ( ".$jsonCuenta[$i]['campoT']." ) ");
                                                }
                                            }

                                            if( $field_moneda==0 ){
                                                array_push($campoTableCuenta, " moneda ");
                                                array_push($campoTableCuentaTMP, " '' ");
                                            }

                                            for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cuenta']);$i++) {
                                                array_push($campoTableCuenta, $jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']);
                                                array_push($campoTableCuentaTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['dato']." ) ");
                                                array_push($campoUpdateTableCuenta," ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']." = VALUES ( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']." ) ");
                                            }

                                            $field_cartera = "";
                                            $field_group = "";
                                            $field_where = "";
                                            $field_on = "";
                                            $field_u_where = "";
                                            if( trim($gestion) == '' )  {
                                                $field_cartera = " ".$id_cartera." ";
                                                $field_u_where = " AND cu.idcartera = ".$id_cartera." ";
                                                $field_where = $sql_where_main_tmp ;
                                            }else{
                                                $field_cartera = " idcartera ";
                                                $field_group = " , idcartera ";
                                                $field_where = " AND ISNULL(tmp.idcartera)=0 ".$sql_where_main_tmp." ";
                                                $field_on = " AND tmp.idcartera = cu.idcartera ";
                                            }

                                            $insertCuenta = "";
                                            $sqlTMPUpdateIdCuenta = "";

                                            $field_on_c = $field_on;
                                            $group_by_c = $field_group;
                                            if($moneda_cuenta != '')
                                            {
                                                $field_on_c = $field_on." AND cu.moneda = TRIM( tmp.$moneda_cuenta ) ";
                                                $group_by_c = $field_group." , TRIM($moneda_cuenta) ";
                                            }

                                            if($grupo1_cuenta != '')
                                            {
                                                $field_on_c = $field_on." AND cu.grupo1_cuenta = TRIM( tmp.$grupo1_cuenta ) ";
                                                $group_by_c = $field_group." , TRIM($grupo1_cuenta) ";
                                            }

                                            $EstadoClienteCuenta="UPDATE ca_cuenta SET retirado=1,estado=0 where idcartera=".$id_cartera;
                                            $prEstadoClienteCuenta=$connection->prepare($EstadoClienteCuenta);
                                            if ($prEstadoClienteCuenta->execute()){
                                                //
                                            }else{
                                                return array('rst' => false, 'msg' => 'Error al actualizar Contratos');
                                            }

                                            $adicional_cuenta="";
                                            if(implode(",",$campoUpdateTableCuenta)!=''){$adicional_cuenta=",".implode(",",$campoUpdateTableCuenta);}

                                            $insertCuenta = "   INSERT INTO ca_cuenta ( idcliente_cartera, codigo_cliente,dato1, idcartera, estado, fecha_creacion, usuario_creacion,dato2,dato3, " . implode(",", $campoTableCuenta) . " )
                                                                SELECT 
                                                                idcliente_cartera, 
                                                                TRIM($codigo_cliente),
                                                                llave,
                                                                ".$field_cartera.", 
                                                                1, 
                                                                NOW(),                                                                
                                                                $usuario_creacion,
                                                                td,
                                                                empresa,
                                                                " . implode(",", $campoTableCuentaTMP) . "
                                                                FROM 
                                                                $nombre_tabla
                                                                WHERE 
                                                                LENGTH( TRIM($codigo_cliente) )>0 AND 
                                                                LENGTH( TRIM($numero_cuenta ) )>0 AND 
                                                                ISNULL( idcliente_cartera ) = 0 
                                                                ".$field_where."
                                                                GROUP BY empresa,cod_cliente,td,idcliente_cartera,TRIM($numero_cuenta)".$group_by_c."
                                                                ON DUPLICATE KEY UPDATE retirado=0,estado=1".$adicional_cuenta." ";

                                            $prInsertCuenta = $connection->prepare($insertCuenta);
                                            if ($prInsertCuenta->execute()){
                                                $sqlTMPUpdateIdCuenta = "   UPDATE 
                                                                            $nombre_tabla tmp 
                                                                            INNER JOIN ca_cuenta cu ON RTRIM(LTRIM(tmp.llave)) = RTRIM(LTRIM(cu.dato1))
                                                                            ".$field_on_c."
                                                                            SET 
                                                                            tmp.idcuenta=cu.idcuenta
                                                                            WHERE ISNULL(tmp.idcliente_cartera) = 0 ".$sql_where_main_tmp."  ".$field_u_where." ";


                                                $prTMPUpdateIdCuenta = $connection->prepare($sqlTMPUpdateIdCuenta);
                                                if ($prTMPUpdateIdCuenta->execute()) {
                                                    if (count($jsonOperacion) > 0) {
                                                        $campoTableOperacionTMP = array();
                                                        $campoTableOperacion = array();

                                                        $campoUpdateTableOperacion = array();
                                                        
                                                        $fieldTramo = "";                                                            

                                                        for ($i = 0; $i < count($jsonOperacion); $i++) {

                                                            if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                            } else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                            } else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                                /*******************/
                                                                array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = VALUES( ".$jsonOperacion[$i]['campoT']." ) ");
                                                                $fieldTramo = $jsonOperacion[$i]['dato'];
                                                            } else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento' || $jsonOperacion[$i]['campoT'] == 'fecha_asignacion' || $jsonOperacion[$i]['campoT'] == 'fecha_baja' || $jsonOperacion[$i]['campoT'] == 'fecha_alta' || $jsonOperacion[$i]['campoT'] == 'fecha_ciclo' || $jsonOperacion[$i]['campoT'] == 'fecha_emision') {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, "
                                                                    CASE
                                                                    WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 8
                                                                    THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",7,2))
                                                                    WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 10
                                                                    THEN
                                                                        CASE
                                                                        WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 3
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                        WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 5
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                        WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'-') = 3
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                        WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 3
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                        WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 5
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                        ELSE TRIM(".$jsonOperacion[$i]['dato'].")
                                                                        END
                                                                    ELSE TRIM(DATE(STR_TO_DATE(".$jsonOperacion[$i]['dato'].",'%d/%m/%Y')))
                                                                    END ");

                                                                
                                                                array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = VALUES( ".$jsonOperacion[$i]['campoT']." ) ");
                                                            } else if( $jsonOperacion[$i]['campoT'] == 'total_deuda' || $jsonOperacion[$i]['campoT'] == 'total_deuda_soles' || $jsonOperacion[$i]['campoT'] == 'total_deuda_dolares' || $jsonOperacion[$i]['campoT'] == 'monto_mora' || $jsonOperacion[$i]['campoT'] == 'monto_mora_soles' || $jsonOperacion[$i]['campoT'] == 'monto_mora_dolares' || $jsonOperacion[$i]['campoT'] == 'saldo_capital' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_soles' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_dolares' ) {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " SUM( ".$jsonOperacion[$i]['dato']." ) ");
                                                                
                                                                array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = VALUES( ".$jsonOperacion[$i]['campoT']." ) ");
                                                            } else {
                                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " TRIM( ".$jsonOperacion[$i]['dato']." ) ");
                                                                
                                                                array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = VALUES( ".$jsonOperacion[$i]['campoT']." ) ");
                                                            }
                                                        }


                                                        array_push($campoTableOperacion, " codigo_cliente " );
                                                        array_push($campoTableOperacionTMP, " TRIM( ".$codigo_cliente.")");

                                                        array_push($campoTableOperacion, " numero_cuenta " );
                                                        array_push($campoTableOperacionTMP, " TRIM( ".$numero_cuenta.")");

                                                        if( $moneda_cuenta != '' ) {
                                                            array_push($campoTableOperacion, " moneda_cuenta " );
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$moneda_cuenta.")");
                                                        }

                                                        if( $grupo1_cuenta != '' ) {
                                                            array_push($campoTableOperacion, " grupo1_cuenta " );
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$grupo1_cuenta.")");
                                                        }

                                                        for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_detalle_cuenta']);$i++) {
                                                            array_push($campoTableOperacion, $jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['dato']." ) ");
                                                            array_push($campoUpdateTableOperacion," ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']." = VALUES( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']." ) ");
                                                        }

                                                        $field_group_o = $field_group;
                                                        if( $moneda_operacion != '' ) {
                                                            $field_group_o .= " , ".$moneda_operacion;
                                                        }
                                                        if( $grupo1_operacion != '' ) {
                                                            $field_group_o .= " , ".$grupo1_operacion;
                                                        }

                                                        $EstadoClienteDetalleCuenta="UPDATE ca_detalle_cuenta SET estado=0 where idcartera=".$id_cartera;
                                                        $prEstadoClienteDetalleCuenta=$connection->prepare($EstadoClienteDetalleCuenta);
                                                        if ($prEstadoClienteDetalleCuenta->execute()){
                                                            
                                                        }else{
                                                            return array('rst' => false, 'msg' => 'Error al actualizar Detalle');
                                                        }   

                                                        $insertOperacion = "    INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", idcartera, usuario_creacion,dato45,fecha_creacion, idcuenta )
                                                                                SELECT 
                                                                                ".implode(",", $campoTableOperacionTMP) . ", ".$field_cartera." , 
                                                                                $usuario_creacion , 
                                                                                llave,
                                                                                NOW() , 
                                                                                idcuenta
                                                                                FROM 
                                                                                $nombre_tabla tmp
                                                                                WHERE 
                                                                                LENGTH( TRIM( $codigo_cliente ) ) > 0 AND 
                                                                                LENGTH( TRIM( $numero_cuenta ) ) > 0 AND 
                                                                                LENGTH( TRIM( $codigo_operacion ) ) > 0 AND 
                                                                                ISNULL( idcuenta ) = 0 ".$field_where."
                                                                                GROUP BY 
                                                                                idcuenta,empresa,cod_cliente,td,TRIM( $codigo_operacion ) ".$field_group_o."
                                                                                ON DUPLICATE KEY UPDATE estado=1,fecha_modificacion=NOW(),".implode(",",$campoUpdateTableOperacion)."";

                                                        // echo $insertOperacion;
                                                        // exit();

                                                        $prInsertOperacion = $connection->prepare($insertOperacion);
                                                        if ($prInsertOperacion->execute()){

                                                            $sqlTMPUpdateIdDetalleCuenta = "    UPDATE 
                                                                                                $nombre_tabla tmp 
                                                                                                INNER JOIN ca_detalle_cuenta detcu ON RTRIM(LTRIM(tmp.llave))=RTRIM(LTRIM(detcu.dato45)) 	 AND detcu.idcartera=$field_cartera
                                                                                                SET 
                                                                                                tmp.iddetalle_cuenta=detcu.iddetalle_cuenta";


                                                            $prTMPUpdateIdDetalleCuenta = $connection->prepare($sqlTMPUpdateIdDetalleCuenta);
                                                            if($prTMPUpdateIdDetalleCuenta->execute()){                                                              

                                                                $insertmail="SELECT cod_cliente,correo,idcliente FROM $nombre_tabla WHERE correo<>'' GROUP BY cod_cliente,correo,idcliente;";
                                                                $prinsertmail=$connection->prepare($insertmail);
                                                                if($prinsertmail->execute()){
                                                                    $ar_correos=$prinsertmail->fetchAll(PDO::FETCH_ASSOC);
                                                                    for ($i=0; $i <=count($ar_correos)-1 ; $i++){
                                                                        $ar_mail=explode(',', $ar_correos[$i]['correo']);
                                                                        for ($j=0; $j <=count($ar_mail)-1 ; $j++) { 
                                                                            $insert_mail="INSERT IGNORE `cob_andina`.`ca_correo`(`codigo_cliente`,`correo`,`estado`,`usuario_creacion`,`fecha_creacion`,idcliente) VALUES ('".$ar_correos[$i]['cod_cliente']."','".$ar_mail[$j]."',1,1,NOW(),'".$ar_correos[$i]['idcliente']."')";
                                                                            $pr_insert_mail = $connection->prepare($insert_mail);
                                                                            $pr_insert_mail->execute();
                                                                        }
                                                                    }

                                                                    $insertfone="SELECT cod_cliente,telefono,idcartera,idcliente_cartera FROM $nombre_tabla WHERE telefono<>'' GROUP BY cod_cliente,telefono,idcartera,idcliente_cartera";
                                                                    $prinsertfone=$connection->prepare($insertfone);
                                                                    if($prinsertfone->execute()){
                                                                        $ar_fone=$prinsertfone->fetchAll(PDO::FETCH_ASSOC);
                                                                        for ($k=0; $k <=count($ar_fone)-1 ; $k++) {                                                                         
                                                                            $ar_telf=explode(',', $ar_fone[$k]['telefono']);
                                                                            for ($n=0; $n <=count($ar_telf)-1 ; $n++) { 
                                                                                $insert_fone="  INSERT IGNORE ca_telefono(idtipo_telefono,numero,usuario_creacion,fecha_creacion,idorigen,idtipo_referencia,idcartera,is_import,codigo_cliente,estado,is_new,is_active,idcliente_cartera,status) VALUES (8,'".$ar_telf[$n]."',$usuario_creacion,NOW(),1,1,'".$ar_fone[$k]['idcartera']."',0,'".$ar_fone[$k]['cod_cliente']."',1,1,1,'".$ar_fone[$k]['idcliente_cartera']."','CORRECTO')";
                                                                                $pr_insert_fone = $connection->prepare($insert_fone);
                                                                                $pr_insert_fone->execute();
                                                                            }
                                                                        }

                                                                        $fecha_file= str_replace(" ","-",str_replace(".txt","",substr($file, -12)));
                                                                        $arr=explode("-",$fecha_file);
                                                                        $arr_d=$arr[0];
                                                                        $arr_m=$arr[1];
                                                                        $arr_y=$arr[2];
                                                                        $fech_load = $arr_y."-".$arr_m."-".$arr_d;
                                                                        $date = new DateTime($fech_load);

                                                                        $id_servicio=$_POST['Servicio'];
                                                                        $id_campania=$_POST['Campania'];
                                                                        $min_load=date('h:i:s');
                                                                        $fecha_carga = $date->format('Y-m-d '.$min_load);
                                                                        $fecha_gestion = $date->format('Y-m-d');;


                                                                        // $sql_his_cob_andina = "     INSERT INTO ca_historico_cobranza_andina (
                                                                        //                             cod_zon,
                                                                        //                             empresa,
                                                                        //                             zona,
                                                                        //                             tienda,
                                                                        //                             localidad,
                                                                        //                             externo,
                                                                        //                             vend_actual,
                                                                        //                             vend_rtc_actual,
                                                                        //                             supervisor,
                                                                        //                             tipo_cliente,
                                                                        //                             cod_cliente,
                                                                        //                             cliente,
                                                                        //                             td,
                                                                        //                             num_doc,
                                                                        //                             fecha_doc,
                                                                        //                             mes_emis,
                                                                        //                             ano_emis,
                                                                        //                             dias_plazo,
                                                                        //                             fecha_vcto,
                                                                        //                             m_vcto,
                                                                        //                             ano_vcto,
                                                                        //                             fecha_al,
                                                                        //                             dias_transc_vcto_of,
                                                                        //                             tipo_de_operacion,
                                                                        //                             rango_vcto,
                                                                        //                             linea_de_credito,
                                                                        //                             ind_vcto,
                                                                        //                             semaforo_de_vencimiento,
                                                                        //                             mon,
                                                                        //                             importe_original,
                                                                        //                             saldo,
                                                                        //                             tp,
                                                                        //                             soles,
                                                                        //                             dolares,
                                                                        //                             total_convertido_a_dolares,
                                                                        //                             total_convertido_a_soles,
                                                                        //                             glosa,
                                                                        //                             est_letr,
                                                                        //                             banco,
                                                                        //                             num_cobranza,
                                                                        //                             referencia,
                                                                        //                             llave,
                                                                        //                             idservicio,
                                                                        //                             idcampania,
                                                                        //                             idcartera,
                                                                        //                             idcliente,
                                                                        //                             idcliente_cartera,
                                                                        //                             idcuenta,
                                                                        //                             iddetalle_cuenta,
                                                                        //                             fecha_carga,
                                                                        //                             fecha_gestion,
                                                                        //                             retiro,
                                                                        //                             estado,
                                                                        //                             carga_valida
                                                                        //                             )
                                                                        //                             SELECT
                                                                        //                             cod_zon,
                                                                        //                             empresa,
                                                                        //                             zona,
                                                                        //                             tienda,
                                                                        //                             localidad,
                                                                        //                             externo,
                                                                        //                             vend_actual,
                                                                        //                             vend_rtc_actual,
                                                                        //                             supervisor,
                                                                        //                             tipo_cliente,
                                                                        //                             cod_cliente,
                                                                        //                             cliente,
                                                                        //                             td,
                                                                        //                             num_doc,
                                                                        //                             fecha_doc,
                                                                        //                             mes_emis,
                                                                        //                             ano_emis,
                                                                        //                             dias_plazo,
                                                                        //                             fecha_vcto,
                                                                        //                             m_vcto,
                                                                        //                             ano_vcto,
                                                                        //                             fecha_al,
                                                                        //                             dias_transc_vcto_of,
                                                                        //                             tipo_de_operacion,
                                                                        //                             rango_vcto,
                                                                        //                             linea_de_credito,
                                                                        //                             ind_vcto,
                                                                        //                             semaforo_de_vencimiento,
                                                                        //                             mon,
                                                                        //                             importe_original,
                                                                        //                             saldo,
                                                                        //                             tp,
                                                                        //                             soles,
                                                                        //                             dolares,
                                                                        //                             total_convertido_a_dolares,
                                                                        //                             total_convertido_a_soles,
                                                                        //                             glosa,
                                                                        //                             est_letr,
                                                                        //                             banco,
                                                                        //                             num_cobranza,
                                                                        //                             referencia,
                                                                        //                             llave,
                                                                        //                             $id_servicio,
                                                                        //                             $id_campania,
                                                                        //                             idcartera,
                                                                        //                             idcliente,
                                                                        //                             idcliente_cartera,
                                                                        //                             idcuenta,
                                                                        //                             iddetalle_cuenta,
                                                                        //                             '$fecha_carga',
                                                                        //                             '$fecha_gestion',
                                                                        //                             0,
                                                                        //                             1,
                                                                        //                             1
                                                                        //                             FROM
                                                                        //                             tmp_cobranzas_andina";
                                                                        // $pr_sql_his_cob_andina = $connection->prepare($sql_his_cob_andina);
                                                                        // if($pr_sql_his_cob_andina->execute()){
                                                                        //     return array('rst' => true, 'msg' => 'Se cargo Correctamente');
                                                                        // }else{
                                                                        //     return array('rst' => false, 'msg' => 'Error al insertar historico');
                                                                        //     exit();
                                                                        // }

                                                                        return array('rst' => true, 'msg' => 'Se cargo Correctamente');

                                                                    }else{
                                                                        return array('rst' => false, 'msg' => 'Error al insertar telefonos');
                                                                        exit();
                                                                    }
                                                                }else{
                                                                    return array('rst' => false, 'msg' => 'Error al insertar correos');
                                                                    exit();
                                                                }
                                                            }else{
                                                                return array('rst' => false, 'msg' => 'Error al Actualizar iddetalle_cuenta en temporal');
                                                                exit();
                                                            }
                                                        }else{
                                                            return array('rst' => false, 'msg' => 'Error al Actualizar ca_detalle_cuenta');
                                                            exit();
                                                        }
                                                    }
                                                }else{
                                                    return array('rst' => false, 'msg' => 'Error al Actualizar idcuenta en temporal');
                                                    exit();
                                                }
                                            }else{
                                                return array('rst' => false, 'msg' => 'Error al Actualizar ca_cuenta');
                                                exit(); 
                                            }
                                        }else{
                                            return array('rst' => false, 'msg' => 'Error al Actualizar idcliente_cartera en temporal');
                                            exit(); 
                                        }
                                    }else{
                                        return array('rst' => false, 'msg' => 'Error al Actualizar ca_cliente_cartera');
                                        exit(); 
                                    }
                                }else{
                                    return array('rst' => false, 'msg' => 'Error al Actualizar idcliente en temporal');
                                    exit();
                                }
                            }else{
                                return array('rst' => false, 'msg' => 'Error al Actualizar ca_cliente');
                                exit();
                            }
                        } else {
                            return array('rst' => false, 'msg' => 'Error al insertar metadata');
                            exit();
                        }
                    }
                }else{
                    return array('rst' => false, 'msg' => 'Error al actualizar ca_cartera');
                    exit();
                }
            }else{
                return array('rst' => false, 'msg' => 'Error al actualizar idcartera en temporal');
                exit();
            }

            // FIN ACTUALIZACION COBRANZAS
        }else if($idcampania==2){
            // INICIO ACTUALIZACION POST-VENTA

            // FIN ACTUALIZACION POST-VENTA
        }     
    }

    public function uploadAddCartera2 ( $_post, $is_parser = 0, $file  ) {

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $usuario_creacion = $_post["usuario_creacion"];

        $tipo_data = (int)$_post["TipoData"];
        $cabecera_departamento = $_post["CabeceraDepartamento"];

        //$id_cartera = $_post['Cartera'];

        $nombre_cartera = $_post["NombreCartera"];
        $codigo_cliente = "";
        $numero_cuenta = "";
        $codigo_operacion = "";
        $moneda_cuenta = "";
        $moneda_operacion = "";
        $grupo1_cuenta = "";
        $grupo1_operacion = "";

        $gestion = "";

        $parserCliente = str_replace("\\", "", $_post["data_cliente"]);
        $parserCartera = str_replace("\\", "", $_post["data_cartera"]);
        $parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
        $parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
        $parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
        $parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
        $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);



        $jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
        $jsonCartera = json_decode(str_replace("\\", "", $_post["data_cartera"]), true);
        $jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
        $jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
        $jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
        $jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
        $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

        $sql_where_main = " ";
        $sql_where_main_tmp = " ";
        if( $tipo_data == 1 ) {

        }else if ( $tipo_data == 2 ){
            $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
            $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
        }else if ( $tipo_data == 3 ) {
            $sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
            $sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
        }else{
            echo json_encode(array('rst' => false, 'msg' => 'Tipo incorrecto de carga'));
            exit();
        }

        for( $i=0;$i<count($jsonCliente);$i++ ) {
            if( $jsonCliente[$i]['campoT'] == 'codigo' ) {
                $codigo_cliente = $jsonCliente[$i]['dato'];
            }
        }

        for( $i=0;$i<count($jsonCartera);$i++ ) {
            if( $jsonCartera[$i]['campoT'] == 'nombre_cartera' ) {
                $gestion = $jsonCartera[$i]['dato'];
            }
        }

        for( $i=0;$i<count($jsonCuenta);$i++ ) {
            if( $jsonCuenta[$i]['campoT'] == 'numero_cuenta' ) {
                $numero_cuenta = $jsonCuenta[$i]['dato'];
            }else if( $jsonCuenta[$i]['campoT'] == 'moneda' ){
                $moneda_cuenta = $jsonCuenta[$i]['dato'];
            }else if( $jsonCuenta[$i]['campoT'] == 'grupo1' ) {
                $grupo1_cuenta = $jsonCuenta[$i]['dato'];
            }
        }

        for( $i=0;$i<count($jsonOperacion);$i++ ) {
            if( $jsonOperacion[$i]['campoT'] == 'codigo_operacion' ) {
                $codigo_operacion = $jsonOperacion[$i]['dato'];
            }else if( $jsonOperacion[$i]['campoT'] == 'moneda' ){
                $moneda_operacion = $jsonOperacion[$i]['dato'];
            }else if( $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
                $grupo1_operacion = $jsonOperacion[$i]['dato'];
            }
        }




        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
            //echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }


        $time = date("Y_m_d_H_i_s");
        //$archivoParser = file($path);
        $archivoParser = @fopen($path, "r+");
        //$columMap = explode($_post['separator'],$archivoParser[0]);
        $columMap = array();
        if ($_post['separator'] == 'tab') {
            $columMap = explode("\t", fgets($archivoParser));
        } else {
            $columMap = explode($_post['separator'], fgets($archivoParser));
        }
        /*         * ****** */
        fclose($archivoParser);
        /*         * ******* */
        if (!function_exists('map_header')) {

            function map_header($n) {
                $item = "";
                if (trim(utf8_encode($n)) != "") {
                    //$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                    $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "");
                    $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                    //$item="`".$item."` VARCHAR(200) ";
                }

                return $item;
            }

        }

        $colum = array_map("map_header", $columMap);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        $parserHeader = implode(",", $colum);

        /*         * ********* */
        /*array_push($columHeader, "`idcartera` INT ");
        array_push($columHeader, "`idcliente` INT ");
        array_push($columHeader, "`idcliente_cartera` INT ");
        array_push($columHeader, "`idcuenta` INT ");*/
        array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
        if ($codigo_operacion != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
        }
        if ($moneda_cuenta != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
        }
        if ($moneda_operacion != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
        }
        if ($grupo1_cuenta != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_cuenta` ( `$grupo1_cuenta` ASC ) ");
        }
        if ($grupo1_operacion != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_operacion` ( `$grupo1_operacion` ASC ) ");
        }
        if ($gestion != "") {
            array_push($columHeader, "INDEX `index_" . session_id() . "_gestion` ( `$gestion` ASC ) ");
        }

        /*array_push($columHeader, "INDEX `index_" . session_id() . "_idcartera` ( `idcartera` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente` ( `idcliente` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente_cartera` ( `idcliente_cartera` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");*/

        /*         * ********** */

        if ($countHeaderFalse > 0) {
            return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
            //echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
            exit();
        }


        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
        $prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
        if ($prDropTableTMPCartera->execute()) {

            $sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = Aria DEFAULT CHARACTER SET = latin1 ";

            $prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
            if ($prsqlCreateTabelTMPCartera->execute()) {

                /* $sqlLoadDataInFileUC=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$file."'
                  INTO TABLE tmpcartera_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES "; */
                $sqlLoadDataInFileUC = "";
                if ($_post['separator'] == 'tab') {
                    $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                } else {
                    $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }
                //echo $sqlLoadDataInFileUC;
                $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
                if ($prLoadDataInFileUC->execute()) {

                    $sqlAlterTableTMPCartera = " ALTER  TABLE tmpcartera_".session_id()."_".$time." ADD idcartera INT, ADD idcliente INT, ADD idcliente_cartera INT, ADD idcuenta INT, ADD INDEX(idcliente), ADD INDEX(idcliente_cartera), ADD INDEX(idcuenta) ";
                    $prAlterTableTMPCartera = $connection->prepare($sqlAlterTableTMPCartera);
                    if( $prAlterTableTMPCartera->execute() ) {

                    //$connection->beginTransaction();

                        $sql_idcartera_c = "";
                        $id_cartera = 0;
                        if( trim($gestion) == '' )  {
                            $id_cartera = $_post['Cartera'];
                            $sql_idcartera_c = $_post['Cartera'];
                        }else{

                            $sql_idcartera_c = " SELECT DISTINCT idcartera FROM tmpcartera_" . session_id() . "_" . $time . " ";

                            $updateTMPCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp
                                    SET idcartera = ( SELECT idcartera FROM ca_cartera WHERE idcampania = " . $_post['Campania'] . " AND estado = 1 AND TRIM(nombre_cartera) = TRIM(tmp.$gestion) ORDER BY idcartera DESC LIMIT 1 )
                                    WHERE TRIM(tmp.$gestion) != '' ".$sql_where_main_tmp." ";
                            $prUpdateTMPCartera = $connection->prepare($updateTMPCartera);
                            if( $prUpdateTMPCartera->execute() ) {

                            }else{
                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                return array('rst' => false, 'msg' => 'Error al actualizar temporal');
                                exit();
                            }
                        }

                        //$id_cartera=$connection->lastInsertId();

                        /*                         * ********* */
                        //$sqlUpdateCartera = " UPDATE ca_cartera SET cartera_act = $id_cartera WHERE idcartera = $id_cartera ";
                        //$prUpdateCartera = $connection->prepare($sqlUpdateCartera);
                        //if( $prUpdateCartera->execute() ) {
                        //if ($prUpdateTMPCartera->execute()) {

                            /*                             * ***** save parser ****** */
                            if ($is_parser == 1) {

                                //$insertJsonParser=" INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador )
                                //                                      VALUES ( ".$_post['Servicio'].",".$usuario_creacion.",NOW(),'".$parserHeader."','".$parserCliente."','".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."', '".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$_post['separator']."' ) ";

                                $insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador, moneda_cuenta, moneda_operacion )
                                            VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','".$parserCartera."','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "','" . $moneda_cuenta . "','" . $moneda_operacion . "' ) ";

                                $prInsertJsonParser = $connection->prepare($insertJsonParser);
                                if ($prInsertJsonParser->execute()) {

                                } else {
                                    //$connection->rollBack();
                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                    @$prsqlDropTableTMPCarteraRollBack->execute();
                                    return array('rst' => false, 'msg' => 'Error al insertar metadata');
                                    //echo json_encode(array('rst'=>false,'msg'=>'Error al insertar metadata'));
                                    //exit();
                                }
                            }
                            /************** */



                                $insertCliente = " ";

                                $campoTableClienteTMP = array();
                                $campoTableCliente = array();

                                for ($i = 0; $i < count($jsonCliente); $i++) {
                                    if ($jsonCliente[$i]['campoT'] == 'codigo') {
                                        array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                        array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
                                    } else {
                                        array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                        array_push($campoTableClienteTMP, "TRIM(".$jsonCliente[$i]['dato'].")");
                                    }
                                }

                                //$insertCliente=" INSERT IGNORE INTO ca_cliente ( idservicio,".implode(",",$campoTableCliente)." )
                                //SELECT ".$_post['Servicio'].",".implode(",",$campoTableClienteTMP)." FROM tmpcartera_".session_id()."_".$time." GROUP BY TRIM($codigo_cliente) ";

                                $insertCliente = " INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " )
                                            SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . "
                                            FROM tmpcartera_" . session_id() . "_" . $time . "
                                            WHERE LENGTH( TRIM($codigo_cliente) )>0 ".$sql_where_main."
                                            GROUP BY TRIM($codigo_cliente) ";

                                $prInsertCliente = $connection->prepare($insertCliente);
                                if ($prInsertCliente->execute()) {

                                    /*                                 * ***************** */
                                    $sqlTMPUpdateIdCliente = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cliente cli
                                                ON cli.codigo = tmp.$codigo_cliente
                                                SET tmp.idcliente = cli.idcliente
                                                WHERE cli.idservicio = " . $_post['Servicio'] . " ".$sql_where_main_tmp." ";
                                    /*                                 * ***************** */

                                    $prTMPUpdateIdCliente = $connection->prepare($sqlTMPUpdateIdCliente);
                                    if ($prTMPUpdateIdCliente->execute()) {

                                        $campoTableClienteCarteraTMP = array();
                                        $campoTableClienteCartera = array();

                                        for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cliente']);$i++) {
                                            array_push($campoTableClienteCartera, $jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']);
                                            array_push($campoTableClienteCarteraTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['dato']." ) ");

                                        }

                                        $implode_cc_t = "";
                                        $implode_cc_tmp = "";


                                        if( count($campoTableClienteCartera) > 0 ){
                                            $implode_cc_t = ", ".implode(",",$campoTableClienteCartera);
                                            $implode_cc_tmp = ", ".implode(",",$campoTableClienteCarteraTMP);
                                        }

                                        $field_cartera = "";
                                        $field_group = "";
                                        ( trim($gestion) == '' )
                                        ?
                                            $field_cartera = " ".$id_cartera." "
                                        :
                                            $field_cartera = " idcartera ";
                                            $field_group = " idcartera , ";
                                        ;

                                        $InsertClienteCartera = " INSERT IGNORE INTO ca_cliente_cartera ( idcliente, codigo_cliente,idcartera,usuario_creacion,fecha_creacion ".$implode_cc_t." )
                                                    SELECT idcliente, TRIM($codigo_cliente), ".$field_cartera." ," . $usuario_creacion . ",NOW()  ".$implode_cc_tmp."
                                                    FROM tmpcartera_" . session_id() . "_" . $time . "
                                                    WHERE LENGTH( TRIM($codigo_cliente) )>0 AND ISNULL(idcliente) = 0 ".$sql_where_main." ";

                                        //echo $InsertClienteCartera;
                                        $prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
                                        if ($prInsertClienteCartera->execute()) {
                                            $field_on = "";
                                            $field_where = "";
                                            ( trim($gestion) == '' )?$field_on = " " : $field_on = " AND clicar.idcartera = tmp.idcartera ";
                                            ( trim($gestion) == '' )?$field_where = " WHERE clicar.idcartera = ".$id_cartera." ".$sql_where_main_tmp." " : $field_where = " WHERE ISNULL(tmp.idcliente) = 0 ".$sql_where_main_tmp." ";
                                            /*                                         * ********* */
                                            $sqlTMPUpdateIdClienteCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cliente_cartera clicar
                                                        ON clicar.idcliente = tmp.idcliente ".$field_on."
                                                        SET tmp.idcliente_cartera = clicar.idcliente_cartera ".$field_where;
                                            /*                                         * ************ */
                                            //echo $sqlTMPUpdateIdClienteCartera;exit();
                                            $prTMPUpdateIdClienteCartera = $connection->prepare($sqlTMPUpdateIdClienteCartera);
                                            if ($prTMPUpdateIdClienteCartera->execute()) {

                                                $campoTableCuentaTMP = array();
                                                $campoTableCuenta = array();

                                                $field_moneda = 0;
                                                for ($i = 0; $i < count($jsonCuenta); $i++) {
                                                    if ( $jsonCuenta[$i]['campoT'] == 'total_deuda' || $jsonCuenta[$i]['campoT'] == 'monto_mora' || $jsonCuenta[$i]['campoT'] == 'saldo_capital' ) {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    } else if ($jsonCuenta[$i]['campoT'] == 'monto_pagado') {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    } else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    } else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                        $field_moneda = 1;
                                                    } else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                                    } else {
                                                        array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                                        array_push($campoTableCuentaTMP, $jsonCuenta[$i]['dato']);
                                                    }
                                                }

                                                if( $field_moneda==0 ){
                                                    array_push($campoTableCuenta, " moneda ");
                                                    array_push($campoTableCuentaTMP, " '' ");
                                                }

                                                for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cuenta']);$i++) {
                                                    array_push($campoTableCuenta, $jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']);
                                                    array_push($campoTableCuentaTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['dato']." ) ");
                                                }

                                                $field_cartera = "";
                                                $field_group = "";
                                                $field_where = "";
                                                $field_on = "";
                                                $field_u_where = "";
                                                if( trim($gestion) == '' )  {
                                                    $field_cartera = " ".$id_cartera." ";
                                                    $field_u_where = " AND cu.idcartera = ".$id_cartera." ";
                                                    $field_where = $sql_where_main_tmp ;
                                                }else{
                                                    $field_cartera = " idcartera ";
                                                    $field_group = " , idcartera ";
                                                    $field_where = " AND ISNULL(tmp.idcartera)=0 ".$sql_where_main_tmp." ";
                                                    $field_on = " AND tmp.idcartera = cu.idcartera ";
                                                }

                                                $insertCuenta = "";
                                                $sqlTMPUpdateIdCuenta = "";

                                                $field_on_c = $field_on;
                                                $group_by_c = $field_group;
                                                if($moneda_cuenta != '')
                                                {
                                                    $field_on_c = $field_on." AND cu.moneda = TRIM( tmp.$moneda_cuenta ) ";
                                                    $group_by_c = $field_group." , TRIM($moneda_cuenta) ";
                                                }

                                                if($grupo1_cuenta != '')
                                                {
                                                    $field_on_c = $field_on." AND cu.grupo1_cuenta = TRIM( tmp.$grupo1_cuenta ) ";
                                                    $group_by_c = $field_group." , TRIM($grupo1_cuenta) ";
                                                }

                                                $insertCuenta = " INSERT IGNORE INTO ca_cuenta ( idcliente_cartera, codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )
                                                        SELECT idcliente_cartera, TRIM($codigo_cliente), ".$field_cartera.", 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . "
                                                        FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                        WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0 AND ISNULL( idcliente_cartera ) = 0 ".$field_where."
                                                        GROUP BY idcliente_cartera, TRIM($numero_cuenta)".$group_by_c." ";

                                                $sqlTMPUpdateIdCuenta = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu
                                                        ON cu.idcliente_cartera = tmp.idcliente_cartera
                                                        AND cu.numero_cuenta = tmp.$numero_cuenta
                                                        ".$field_on_c."
                                                        SET tmp.idcuenta = cu.idcuenta
                                                        WHERE ISNULL(tmp.idcliente_cartera) = 0 ".$field_u_where." ".$sql_where_main_tmp." ";

                                                $prInsertCuenta = $connection->prepare($insertCuenta);
                                                if ($prInsertCuenta->execute()) {

                                                    $prTMPUpdateIdCuenta = $connection->prepare($sqlTMPUpdateIdCuenta);
                                                    if ($prTMPUpdateIdCuenta->execute()) {

                                                        if (count($jsonOperacion) > 0) {
                                                            $campoTableOperacionTMP = array();
                                                            $campoTableOperacion = array();

                                                            /*                                                         * *** */
                                                            $fieldTramo = "";
                                                            /*                                                         * ** */

                                                            for ($i = 0; $i < count($jsonOperacion); $i++) {

                                                                if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                                } else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                                } else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                                                    $fieldTramo = $jsonOperacion[$i]['dato'];
                                                                } else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento' || $jsonOperacion[$i]['campoT'] == 'fecha_asignacion' || $jsonOperacion[$i]['campoT'] == 'fecha_baja' || $jsonOperacion[$i]['campoT'] == 'fecha_alta' || $jsonOperacion[$i]['campoT'] == 'fecha_ciclo' || $jsonOperacion[$i]['campoT'] == 'fecha_emision') {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, "
                                                                        CASE
                                                                        WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 8
                                                                        THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",7,2))
                                                                        WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 10
                                                                        THEN
                                                                            CASE
                                                                            WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 3
                                                                            THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                            WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 5
                                                                            THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                            WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'-') = 3
                                                                            THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                            WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 3
                                                                            THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",1,2))
                                                                            WHEN INSTR(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 5
                                                                            THEN CONCAT(SUBSTRING(".$jsonOperacion[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonOperacion[$i]['dato'].",9,2))
                                                                            ELSE TRIM(".$jsonOperacion[$i]['dato'].")
                                                                            END
                                                                        ELSE TRIM(".$jsonOperacion[$i]['dato'].")
                                                                        END ");

                                                                } else if( $jsonOperacion[$i]['campoT'] == 'total_deuda' || $jsonOperacion[$i]['campoT'] == 'total_deuda_soles' || $jsonOperacion[$i]['campoT'] == 'total_deuda_dolares' || $jsonOperacion[$i]['campoT'] == 'monto_mora' || $jsonOperacion[$i]['campoT'] == 'monto_mora_soles' || $jsonOperacion[$i]['campoT'] == 'monto_mora_dolares' || $jsonOperacion[$i]['campoT'] == 'saldo_capital' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_soles' || $jsonOperacion[$i]['campoT'] == 'saldo_capital_dolares' ) {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, " SUM( ".$jsonOperacion[$i]['dato']." ) ");
                                                                } else {
                                                                    array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                                    array_push($campoTableOperacionTMP, " TRIM( ".$jsonOperacion[$i]['dato']." ) ");
                                                                }
                                                            }


                                                            array_push($campoTableOperacion, " codigo_cliente " );
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$codigo_cliente.")");

                                                            array_push($campoTableOperacion, " numero_cuenta " );
                                                            array_push($campoTableOperacionTMP, " TRIM( ".$numero_cuenta.")");

                                                            if( $moneda_cuenta != '' ) {
                                                                array_push($campoTableOperacion, " moneda_cuenta " );
                                                                array_push($campoTableOperacionTMP, " TRIM( ".$moneda_cuenta.")");
                                                            }

                                                            if( $grupo1_cuenta != '' ) {
                                                                array_push($campoTableOperacion, " grupo1_cuenta " );
                                                                array_push($campoTableOperacionTMP, " TRIM( ".$grupo1_cuenta.")");
                                                            }

                                                            for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_detalle_cuenta']);$i++) {
                                                                array_push($campoTableOperacion, $jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
                                                                array_push($campoTableOperacionTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['dato']." ) ");
                                                            }

                                                            $field_group_o = $field_group;
                                                            if( $moneda_operacion != '' ) {
                                                                $field_group_o .= " , ".$moneda_operacion;
                                                            }
                                                            if( $grupo1_operacion != '' ) {
                                                                $field_group_o .= " , ".$grupo1_operacion;
                                                            }

                                                            $insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", idcartera, usuario_creacion,fecha_creacion, idcuenta )
                                                                        SELECT " . implode(",", $campoTableOperacionTMP) . ", ".$field_cartera." , $usuario_creacion , NOW() , idcuenta
                                                                        FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                                        WHERE LENGTH( TRIM( $codigo_cliente ) ) > 0 AND LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 AND ISNULL( idcuenta ) = 0 ".$field_where."
                                                                        GROUP BY idcuenta, TRIM( $codigo_operacion ) ".$field_group_o." ";


                                                            $prInsertOperacion = $connection->prepare($insertOperacion);
                                                            if ($prInsertOperacion->execute()) {


                                                                    if (trim($fieldTramo) != "") {
                                                                        $InsertTramo = " INSERT IGNORE INTO ca_tramo ( tramo, fecha_creacion, usuario_creacion, idservicio, tipo )
                                                                                    SELECT DISTINCT( TRIM($fieldTramo) ),NOW(),$usuario_creacion , " . $_post['Servicio'] . " ,'TRAMO'
                                                                                    FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $fieldTramo ) ) > 0 ";
                                                                        $prInsertTramo = $connection->prepare($InsertTramo);
                                                                        if ($prInsertTramo->execute()) {

                                                                        } else {
                                                                            //$connection->rollBack();
                                                                            return array('rst' => false, 'msg' => 'Error al insertar tramos');
                                                                            //echo json_encode(array('rst'=>false,'msg'=>'Error al insertar tramos'));
                                                                            //exit();
                                                                        }
                                                                    }


                                                                    $referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5, 'telefono_familiar' => 6, 'telefono_personal'=> 7, 'telefono_tercero'=> 8, 'telefono_conyuge'=>9, 'telefono_aval' => 10 );

                                                                    foreach ($jsonTelefono as $index => $value) {
                                                                        $fieldTelefono = array();
                                                                        $fieldTelefonoTMP = array();
                                                                        $fieldReferenciaTelefono = "";
                                                                        if (count($value) > 0) {

                                                                            foreach ($value as $i => $v) {
                                                                                array_push($fieldTelefono, $i);
                                                                                array_push($fieldTelefonoTMP, " TRIM(" . $v." )");
                                                                                if ($i == "numero") {
                                                                                    $fieldReferenciaTelefono = $v;
                                                                                }
                                                                            }

                                                                            $insertTelefono = "";
                                                                            $field_where = "";
                                                                            (trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
                                                                            (trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";

                                                                            $insertTelefono = " INSERT IGNORE INTO ca_telefono ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                                                                SELECT idcliente_cartera, idcuenta, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                                                                FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                                                                WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 ".$field_where." ";
                                                                            $prInsertTelefono = $connection->prepare($insertTelefono);
                                                                            if ($prInsertTelefono->execute()) {

                                                                            } else {

                                                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                                                return array('rst' => false, 'msg' => 'Error al insertar telefono');

                                                                            }
                                                                        }
                                                                    }

                                                                    $referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5, 'direccion_familiar' => 6);
                                                                    //$insertDireccion="";
                                                                    foreach ($jsonDireccion as $index => $value) {
                                                                        $fieldDireccion = array();
                                                                        $fieldDireccionTMP = array();
                                                                        $fieldDireccionTMPIntersec = array();
                                                                        $fieldReferenciaDireccion = "";
                                                                        $fieldUbigeo = "";
                                                                        $FieldDepartamentoTMP = "";
                                                                        $FieldProvinciaTMP = "";
                                                                        $FieldDistritoTMP = "";
                                                                        if (count($value) > 0) {

                                                                            foreach ($value as $i => $v) {

                                                                                if ($i == "direccion") {
                                                                                    $fieldReferenciaDireccion = $v;
                                                                                    array_push($fieldDireccion, $i);
                                                                                    array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                                } else if ($i == "ubigeo") {
                                                                                    $fieldUbigeo = $v;
                                                                                    
                                                                                    $c_w_dept = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT departamento FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ),'-',1 ) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ) ,'/',-1 ) 
                                                                                        ELSE TRIM( ".$v." ) END
                                                                                        ) ";
    
                                                                                    $c_w_prov = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT provincia FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',2),'-',-1) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( SUBSTRING_INDEX( TRIM( " . $v . " ) ,'/',2),'/',-1) 
                                                                                        ELSE TRIM( ".$v." ) END
                                                                                        ) ";
    
                                                                                    $c_w_dist = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT distrito FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',-1 ) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ),'/',1 ) 
                                                                                        ELSE TRIM( ".$v." ) END 
                                                                                        ) ";
    
                                                                                    array_push($fieldDireccion, $i);
                                                                                    array_push($fieldDireccionTMP, $v );
                                                                                    array_push($fieldDireccion, "departamento");
                                                                                    array_push($fieldDireccionTMP, $c_w_dept);
                                                                                    array_push($fieldDireccion, "provincia");
                                                                                    array_push($fieldDireccionTMP, $c_w_prov);
                                                                                    array_push($fieldDireccion, "distrito");
                                                                                    array_push($fieldDireccionTMP, $c_w_dist);
                                                                                    
                                                                                    /*$FieldDepartamentoTMP = " IF( LOCATE('-',".$v.") = 0 , ( SELECT departamento FROM ca_ubigeo WHERE codigo = TRIM(".$v.") LIMIT 1 ) , SUBSTRING_INDEX( TRIM( " . $v . " ),'-',1 ) ) ";
                                                                                    $FieldDistritoTMP = " IF( LOCATE('-',".$v.") = 0 , ( SELECT distrito FROM ca_ubigeo WHERE codigo = TRIM(".$v.") LIMIT 1 )  , SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',-1 ) ) ";
                                                                                    $FieldProvinciaTMP = " IF( LOCATE('-',".$v.") = 0 , ( SELECT provincia FROM ca_ubigeo WHERE codigo = TRIM(".$v.") LIMIT 1 ) , SUBSTRING_INDEX( SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',2),'-',-1) ) ";
                                                                                    array_push($fieldDireccion, $i);
                                                                                    array_push($fieldDireccionTMP,  $v);
                                                                                    array_push($fieldDireccion, "departamento");
                                                                                    array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
                                                                                    array_push($fieldDireccion, "provincia");

                                                                                    array_push($fieldDireccionTMP, $FieldProvinciaTMP);
                                                                                    array_push($fieldDireccion, "distrito");
                                                                                    array_push($fieldDireccionTMP, $FieldDistritoTMP);*/
                                                                                    
                                                                                } else if ($i == "departamento") {
                                                                                    if (!array_search("departamento", $fieldDireccion)) {
                                                                                        array_push($fieldDireccion, $i);
                                                                                        array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                                    }
                                                                                } else if ($i == "provincia") {
                                                                                    if (!array_search("provincia", $fieldDireccion)) {
                                                                                        array_push($fieldDireccion, $i);
                                                                                        array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                                    }
                                                                                } else if ($i == "distrito") {
                                                                                    if (!array_search("distrito", $fieldDireccion)) {
                                                                                        array_push($fieldDireccion, $i);
                                                                                        array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                                    }
                                                                                } else {
                                                                                    array_push($fieldDireccion, $i);
                                                                                    array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                                }
                                                                            }

                                                                            $insertDireccion = "";
                                                                            $field_where = "";
                                                                            (trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
                                                                            (trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";

                                                                            $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcliente_cartera, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta )
                                                                                                SELECT TRIM( $codigo_cliente ), idcliente_cartera, ".$field_cartera.", 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . ", idcuenta
                                                                                                FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                                                                WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 ".$field_where." ";
                                                                            $prInsertDireccion = $connection->prepare($insertDireccion);
                                                                            if ($prInsertDireccion->execute()) {

                                                                            } else {
                                                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                                                return array('rst' => false, 'msg' => 'Error al insertar direccion');

                                                                            }
                                                                        }
                                                                    }


                                                                    //$connection->commit();
                                                                    return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
                                                                    //echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));

                                                            } else {
                                                                //$connection->rollBack();

                                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                                return array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion');
                                                                //echo json_encode(array('rst'=>false,'msg'=>'No selecciono cabeceras de operacion'));
                                                            }
                                                        } else {

                                                            $referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5, 'telefono_familiar' => 6, 'telefono_personal'=> 7, 'telefono_tercero'=> 8, 'telefono_conyuge'=>9, 'telefono_aval'=>10 );

                                                            foreach ($jsonTelefono as $index => $value) {
                                                                $fieldTelefono = array();
                                                                $fieldTelefonoTMP = array();
                                                                $fieldReferenciaTelefono = "";
                                                                    if (count($value) > 0) {

                                                                        foreach ($value as $i => $v) {
                                                                            array_push($fieldTelefono, $i);
                                                                            array_push($fieldTelefonoTMP, " TRIM(" . $v." )");
                                                                            if ($i == "numero") {
                                                                                    $fieldReferenciaTelefono = $v;
                                                                            }
                                                                        }

                                                                        $insertTelefono = "";
                                                                        $field_where = "";
                                                                        (trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
                                                                        (trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";

                                                                        $insertTelefono = " INSERT IGNORE INTO ca_telefono ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                                                                SELECT idcliente_cartera, idcuenta, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                                                                FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                                                                WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 ".$field_where." ";
                                                                        $prInsertTelefono = $connection->prepare($insertTelefono);
                                                                        if ($prInsertTelefono->execute()) {

                                                                        } else {

                                                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                                                return array('rst' => false, 'msg' => 'Error al insertar telefono');

                                                                        }
                                                                    }
                                                            }

                                                            $referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);

                                                            foreach ($jsonDireccion as $index => $value) {
                                                                $fieldDireccion = array();
                                                                $fieldDireccionTMP = array();
                                                                $fieldDireccionTMPIntersec = array();
                                                                $fieldReferenciaDireccion = "";
                                                                $fieldUbigeo = "";
                                                                $FieldDepartamentoTMP = "";
                                                                $FieldProvinciaTMP = "";
                                                                $FieldDistritoTMP = "";
                                                                if (count($value) > 0) {

                                                                    foreach ($value as $i => $v) {

                                                                        if ($i == "direccion") {
                                                                            $fieldReferenciaDireccion = $v;
                                                                            array_push($fieldDireccion, $i);
                                                                            array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                        } else if ($i == "ubigeo") {
                                                                            $fieldUbigeo = $v;

                                                                            $c_w_dept = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT departamento FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ),'-',1 ) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ) ,'/',-1 ) 
                                                                                        ELSE TRIM( ".$v." ) END
                                                                                        ) ";

                                                                            $c_w_prov = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT provincia FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',2),'-',-1) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( SUBSTRING_INDEX( TRIM( " . $v . " ) ,'/',2),'/',-1) 
                                                                                        ELSE TRIM( ".$v." ) END
                                                                                        ) ";

                                                                            $c_w_dist = " IF( LENGTH( TRIM(".$v.") )<=8, 
                                                                                        ( SELECT distrito FROM ca_ubigeo WHERE codigo = TRIM( ".$v." ) LIMIT 1  ) ,
                                                                                        CASE 
                                                                                        WHEN LOCATE( '-',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ) ,'-',-1 ) 
                                                                                        WHEN LOCATE( '/',".$v." ) > 0 THEN SUBSTRING_INDEX( TRIM( " . $v . " ),'/',1 ) 
                                                                                        ELSE TRIM( ".$v." ) END 
                                                                                        ) ";

                                                                            array_push($fieldDireccion, $i);
                                                                            array_push($fieldDireccionTMP, $v );
                                                                            array_push($fieldDireccion, "departamento");
                                                                            array_push($fieldDireccionTMP, $c_w_dept);
                                                                            array_push($fieldDireccion, "provincia");

                                                                            array_push($fieldDireccionTMP, $c_w_prov);
                                                                            array_push($fieldDireccion, "distrito");
                                                                            array_push($fieldDireccionTMP, $c_w_dist);

                                                                        } else if ($i == "departamento") {
                                                                            if (!array_search("departamento", $fieldDireccion)) {
                                                                                array_push($fieldDireccion, $i);
                                                                                array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                            }
                                                                        } else if ($i == "provincia") {
                                                                            if (!array_search("provincia", $fieldDireccion)) {
                                                                                        array_push($fieldDireccion, $i);
                                                                                        array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                            }
                                                                        } else if ($i == "distrito") {
                                                                            if (!array_search("distrito", $fieldDireccion)) {
                                                                                        array_push($fieldDireccion, $i);
                                                                                        array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                            }
                                                                        } else {
                                                                                    array_push($fieldDireccion, $i);
                                                                                    array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
                                                                        }
                                                                    }

                                                                    $insertDireccion = "";
                                                                    $field_where = "";
                                                                    (trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
                                                                    (trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";

                                                                    $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcliente_cartera, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta )
                                                                                                SELECT TRIM( $codigo_cliente ), idcliente_cartera, ".$field_cartera.", 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . ", idcuenta
                                                                                                FROM tmpcartera_" . session_id() . "_" . $time . " tmp
                                                                                                WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 ".$field_where." ";
                                                                    

                                                                    $prInsertDireccion = $connection->prepare($insertDireccion);

                                                                    if ($prInsertDireccion->execute()) {

                                                                    } else {
                                                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                                                return array('rst' => false, 'msg' => 'Error al insertar direccion');

                                                                    }
                                                                }
                                                            }

                                                            //$connection->commit();
                                                            return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
                                                            //echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));
                                                        }
                                                    } else {
                                                        //$connection->rollBack();

                                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                        @$prsqlDropTableTMPCarteraRollBack->execute();
                                                        return array('rst' => false, 'msg' => 'Error al agregar id cuenta a temporal');
                                                    }
                                                } else {
                                                    //$connection->rollBack();

                                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                    @$prsqlDropTableTMPCarteraRollBack->execute();
                                                    return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
                                                    //echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
                                                }
                                            } else {
                                                //$connection->rollBack();

                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                return array('rst' => false, 'msg' => 'Error al agregar id distribucion a temporal');
                                            }
                                        } else {
                                            //$connection->rollBack();

                                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                            @$prsqlDropTableTMPCarteraRollBack->execute();
                                            return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
                                            //echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
                                            //exit();
                                        }
                                    } else {
                                        //$connection->rollBack();

                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                        @$prsqlDropTableTMPCarteraRollBack->execute();
                                        return array('rst' => false, 'msg' => 'Error al insertar id cliente a temporal');
                                    }
                                } else {
                                    //$connection->rollBack();

                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                    @$prsqlDropTableTMPCarteraRollBack->execute();
                                    return array('rst' => false, 'msg' => 'Error al insertar cliente');
                                    //echo json_encode(array('rst'=>false,'msg'=>'Error al insertar cliente'));
                                }



                    /* }else{
                      $sqlDropTableTMPCarteraRollBack=" DROP TABLE IF EXISTS tmpcartera_".session_id()."_".$time." ";
                      @$prsqlDropTableTMPCarteraRollBack=$connection->prepare($sqlDropTableTMPCarteraRollBack);
                      @$prsqlDropTableTMPCarteraRollBack->execute();
                      return array('rst'=>false,'msg'=>'Error al agregar id de gestion');
                      //echo json_encode(array('rst'=>false,'msg'=>'Error al agregar id de gestion'));
                      } */

                    }else{
                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();
                        return array('rst' => false, 'msg' => 'Error campos adicionales');
                    }

                } else {
                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                    @$prsqlDropTableTMPCarteraRollBack->execute();
                    return array('rst' => false, 'msg' => 'Error load data infile');
                    //echo json_encode(array('rst'=>false,'msg'=>'Error load data infile'));
                }
            } else {
                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                @$prsqlDropTableTMPCarteraRollBack->execute();
                return array('rst' => false, 'msg' => 'Error create temporary table');
                //echo json_encode(array('rst'=>false,'msg'=>'Error create temporary table'));
            }
        } else {
            return array('rst' => false, 'msg' => 'Error al eliminar tabla');
            //echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar tabla'));
        }

    }


    public function uploadUpdateCarteraPago($_post, $is_parser=0) {
        $file = $_post['file'];
        $separator = $_post['separator'];
        $servicio = $_post['Servicio'];
        $NombreServicio = $_post['NombreServicio'];
        $cartera = $_post['Cartera'];
        $campania = $_post['Campania'];
        $UsuarioCreacion = $_post['usuario_creacion'];
        $jsonPago = json_decode(str_replace("\\", "", $_post['data_pago']), true);

        //$codigo=$jsonPago['codigo_cliente'];
        $codigo = '';
//      $call_center=$jsonPago['call_center'];
        //$numero_cuenta=$jsonPago['numero_cuenta'];
        $numero_cuenta = '';
        //$operacion=$jsonPago['codigo_operacion'];
        $operacion = '';
        /*         * ****** */
        //$moneda = $jsonPago['moneda'];
        $moneda = '';
        /*         * ***** */
//      $moneda=$jsonPago['moneda'];
//      $monto=$jsonPago['monto'];
//      $fecha=$jsonPago['fecha'];
//      $observacion=$jsonPago['Observacion'];
        $call_center = '';
        $moneda = '';
        $monto = '';
        $fecha = '';
        $observacion = '';

//      if( trim($codigo)=='' ) {
//          echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de codigo cliente'));
//          exit();
//      }
        //if( trim($numero_cuenta)=='' ){
//          echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de cuenta'));
//          exit();
//      }

        for ($i = 0; $i < count($jsonPago); $i++) {
            if ($jsonPago[$i]['campoT'] == 'codigo_cliente') {
                $codigo = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'numero_cuenta') {
                $numero_cuenta = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'moneda') {
                $moneda = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'codigo_operacion') {
                $operacion = $jsonPago[$i]['dato'];
            }
        }

        if (trim($operacion) == '') {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione campo de operacion-factura'));
            exit();
        }
        //if( !isset($jsonPago['codigo_operacion']) ) {
//          echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de operacion-factura'));
//          exit();
//      }
//      if( !isset($jsonPago['codigo_cliente']) ) {
//          $codigo='';
//      }
//      if( !isset($jsonPago['numero_cuenta']) ) {
//          $numero_cuenta='';
//      }
//      if( !isset($jsonPago['moneda']) ) {
//          $moneda='';
//      }
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $path = "../documents/carteras/" . $NombreServicio . "/" . $file;
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        //$archivo = file($path);
        $archivo = @fopen($path, "r+");
        //$colum = explode($separator,$archivo[0]);
        $colum = explode($separator, fgets($archivo));
        if (count($colum) < 5) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                //$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        /* foreach( $jsonPago as $i => $v ) {
          if( $i=='monto_pagado' ) {
          $monto=$v;
          }else if( $i=='moneda' ) {
          $moneda=$v;
          }else if( $i=='fecha' ) {
          $fecha=$v;
          }else if( $i=='call_center' ) {
          $call_center=$v;
          }else if( $i=='observacion' ) {
          $observacion=$v;
          }
          } */

        for ($i = 0; $i < count($jsonPago); $i++) {
            if ($jsonPago[$i]['campoT'] == 'monto_pagado') {
                $monto = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'moneda') {
                $moneda = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'fecha') {
                $fecha = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'call_center') {
                $call_center = $jsonPago[$i]['dato'];
            } else if ($jsonPago[$i]['campoT'] == 'observacion') {
                $observacion = $jsonPago[$i]['dato'];
            }
        }

        /*         * ****** */
        fclose($archivo);
        /*         * ****** */

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTablePago = " DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ";
        $prDropTablePago = $connection->prepare($sqlDropTablePago);
        if ($prDropTablePago->execute()) {

            $createTablePago = " CREATE TEMPORARY TABLE tmppago_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";

            $prCreateTablePago = $connection->prepare($createTablePago);
            if ($prCreateTablePago->execute()) {
                $sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
                  INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";

                $prLoadPago = $connection->prepare($sqlLoadPago);
                if ($prLoadPago->execute()) {

                    //$connection->beginTransaction();
                    //$selectCheckCodigoCliente=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($codigo)='' ";
//                  $prselectCheckCodigoCliente=$connection->prepare($selectCheckCodigoCliente);
//                  $resulCodigoClienteCheck=$prselectCheckCodigoCliente->fetchAll(PDO::FETCH_ASSOC);
//                  if( $resulCodigoClienteCheck[0]['COUNT']>0 ){
//                      //$connection->rollBack();
//
//                      @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//                      @$prDropTablePagoRollback->execute();
//
//                      echo json_encode(array('rst'=>false,'msg'=>'Codigo de cliente posee campos vacios'));
//                      exit();
//                  }
                    //$selectCheckCodigoCuenta=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($numero_cuenta)='' ";
//                  $prselectCheckCodigoCuenta=$connection->prepare($selectCheckCodigoCuenta);
//                  $resultCuentaCheck=$prselectCheckCodigoCuenta->fetchAll(PDO::FETCH_ASSOC);
//                  if( $resultCuentaCheck[0]['COUNT']>0 ) {
//                      //$connection->rollBack();
//
//                      @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//                      @$prDropTablePagoRollback->execute();
//
//                      echo json_encode(array('rst'=>false,'msg'=>'Numero de cuenta posee campos vacios'));
//                      exit();
//                  }
                    //$selectCheckCodigoOperacion=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($operacion)='' ";
//                  $prselectCheckCodigoOperacion=$connection->prepare($selectCheckCodigoOperacion);
//                  $resultOperacionCheck=$prselectCheckCodigoOperacion->fetchAll(PDO::FETCH_ASSOC);
//                  if( $resultOperacionCheck[0]['COUNT']>0 ) {
//                      //$connection->rollBack();
//
//                      @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//                      @$prDropTablePagoRollback->execute();
//
//                      echo json_encode(array('rst'=>false,'msg'=>'Codigo operacion posee campos vacios'));
//                      exit();
//                  }

                    /*                     * ***** save parser ***** */
                    $cabeceras = implode(",", $colum);
                    $parserPago = str_replace("\\", "", $_post["data_pago"]);

                    if ($is_parser == 1) {
//                      $InsertJsonParserPago=" INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion )
//                      VALUES ( ?,?,?,?,?,? ) ";

                        $InsertJsonParserPago = " INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion, moneda )
                        VALUES ( ?,?,?,?,?,?,? ) ";

                        $prInsertJsonParserPago = $connection->prepare($InsertJsonParserPago);
                        $prInsertJsonParserPago->bindParam(1, $servicio);
                        $prInsertJsonParserPago->bindParam(2, $cabeceras);
                        $prInsertJsonParserPago->bindParam(3, $parserPago);
                        $prInsertJsonParserPago->bindParam(4, $codigo);
                        $prInsertJsonParserPago->bindParam(5, $numero_cuenta);
                        $prInsertJsonParserPago->bindParam(6, $operacion);
                        /*                         * **** */
                        $prInsertJsonParserPago->bindParam(7, $moneda);
                        /*                         * *** */
                        if ($prInsertJsonParserPago->execute()) {

                        } else {
                            //$connection->rollBack();

                            @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                            @$prDropTablePagoRollback->execute();

                            echo json_encode(array('rst' => false, 'msg' => 'Error al guardar metadata'));
                            exit();
                        }
                    }

                    /*                     * ********** */

                    $InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras )
                    VALUES( " . $_post['Cartera'] . ",'tmppago_" . session_id() . "_" . $time . "'," . (count($archivo) - 1) . ",NOW(),'" . $file . "',$UsuarioCreacion, NOW(), '" . $codigo . "','" . $numero_cuenta . "','" . $moneda . "','" . $operacion . "','" . $parserPago . "','" . $cabeceras . "' )";

                    $prInsertCarteraPago = $connection->prepare($InsertCarteraPago);
                    if ($prInsertCarteraPago->execute()) {

                        $idCarteraPago = $connection->lastInsertId();

                        $SelectIdDetalleCuentaTMP = "";
                        if (trim($call_center) == '') {
                            $SelectIdDetalleCuentaTMP = " SELECT TRIM( $operacion ) AS 'operacion' FROM tmppago_" . session_id() . "_" . $time . " ";
                        } else {
                            $SelectIdDetalleCuentaTMP = " SELECT TRIM( $operacion ) AS 'operacion' FROM tmppago_" . session_id() . "_" . $time . " WHERE LOWER(TRIM($call_center))='hdec' ";
                        }

                        //function MapSelectIdDetalleCuentaTMP ( $n ) {
//                              return "'".$n['operacion']."'";
//                          }
//
//                          $prSelectIdDetalleCuentaTMP=$connection->prepare($SelectIdDetalleCuentaTMP);
//                          $prSelectIdDetalleCuentaTMP->execute();
//                          $ResultSelectIdDetalleCuentaTMP=$prSelectIdDetalleCuentaTMP->fetchAll(PDO::FETCH_ASSOC);
//                          $MapResultSelectIdDetalleCuentaTMP=array_map("MapSelectIdDetalleCuentaTMP",$ResultSelectIdDetalleCuentaTMP);

                        /*                         * ****** */

//                          $SelectIdCarteraPago=" SELECT idcartera_pago FROM ca_cartera_pago WHERE idcartera = $cartera ";
//                          $prSelectIdCarteraPago = $connection->prepare($SelectIdCarteraPago);
//                          $prSelectIdCarteraPago->execute();
//                          $ResultSelectIdCarteraPago=$prSelectIdCarteraPago->fetchAll(PDO::FETCH_ASSOC);
//
//                          function MapSelectIdCarteraPago ( $n ) {
//                              return $n['idcartera_pago'];
//                          }
//
//                          $MapResultSelectIdCarteraPago=array_map("MapSelectIdCarteraPago",$ResultSelectIdCarteraPago);
                        //$UpdatePago=" UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion
                        //WHERE estado=1 AND idcartera_pago IN ( ".implode(",",$MapResultSelectIdCarteraPago)." ) AND codigo_operacion IN ( ".implode(",",$MapResultSelectIdDetalleCuentaTMP)." ) ";
//                          $UpdatePago=" UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion
//                          WHERE estado=1 AND idcartera = $cartera AND codigo_operacion IN ( ".implode(",",$MapResultSelectIdDetalleCuentaTMP)." ) ";

                        $UpdatePago = " UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion
                            WHERE estado=1 AND idcartera = $cartera AND codigo_operacion IN ( $SelectIdDetalleCuentaTMP ) ";

                        $prUpdatePago = $connection->prepare($UpdatePago);
                        if ($prUpdatePago->execute()) {

                            //$fieldPago=array_intersect_key($jsonPago,array('monto'=>'','moneda'=>'','fecha'=>'','observacion'=>''));
                            $campoPagoTMP = array();
                            $campoPago = array();

                            /* foreach($jsonPago as $index => $value ) {
                              if( $index=="codigo_cliente" ) {
                              array_push($campoPago,$index);
                              array_push($campoPagoTMP," TRIM( ".$value." ) ");
                              }else if( $index=="numero_cuenta" ) {
                              array_push($campoPago,$index);
                              array_push($campoPagoTMP," TRIM( ".$value." ) ");
                              }else if( $index=="codigo_operacion" ) {
                              array_push($campoPago,$index);
                              array_push($campoPagoTMP," TRIM( ".$value." ) ");
                              }else if( $index=="moneda" ) {
                              array_push($campoPago,$index);
                              array_push($campoPagoTMP," TRIM( ".$value." ) ");
                              }else if( $index=="call_center" ){

                              }else{
                              array_push($campoPago,$index);
                              array_push($campoPagoTMP,$value);
                              }
                              } */

                            /*                             * *********************** */
                            for ($i = 0; $i < count($jsonPago); $i++) {
                                if ($jsonPago[$i]['campoT'] == "codigo_cliente") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                } else if ($jsonPago[$i]['campoT'] == "numero_cuenta") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                } else if ($jsonPago[$i]['campoT'] == "codigo_operacion") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                } else if ($jsonPago[$i]['campoT'] == "moneda") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
                                } else if ($jsonPago[$i]['campoT'] == "call_center") {

                                } else if ($jsonPago[$i]['campoT'] == "estado_cruce") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " ( SELECT descripcion FROM ca_estado_pago_cruce WHERE idservicio = $servicio AND nombre = TRIM( " . $jsonPago[$i]['dato'] . " ) ) ");
                                } else if ($jsonPago[$i]['campoT'] == "fecha") {
                                    //array_push($campoTableOperacionTMP," IF( LOCATE('/', ".$jsonOperacion[$i]['dato']." ) = 3,  CONCAT_WS('-',SUBSTRING( ".$jsonOperacion[$i]['dato']." ,7),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,4,2),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,1,2)) , IF( LOCATE('/', ".$jsonOperacion[$i]['dato']." ) = 5, CONCAT_WS('-',SUBSTRING( ".$jsonOperacion[$i]['dato']." ,1,4),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,6,2),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,9,2)) , ".$jsonOperacion[$i]['dato']." ) ) ");
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,7),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,9,2)) , " . $jsonPago[$i]['dato'] . " ) ) ");
                                } else if ($jsonPago[$i]['campoT'] == "fecha_envio") {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, " IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,7),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,9,2)) , " . $jsonPago[$i]['dato'] . " ) ) ");
                                } else {
                                    array_push($campoPago, $jsonPago[$i]['campoT']);
                                    array_push($campoPagoTMP, $jsonPago[$i]['dato']);
                                }
                            }
                            /*                             * *********************** */

                            $sqlInsertPago = "";

                            if (trim($call_center) == '') {

//                                  $sqlInsertPago=" INSERT IGNORE INTO ca_pago ( idcartera_pago, idcartera , usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." )
//                                      SELECT $idCarteraPago, $cartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."
//                                      FROM tmppago_".session_id()."_".$time." ";

                                $sqlInsertPago = " INSERT IGNORE INTO ca_pago ( idcartera_pago, is_act, idcartera , usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " )
                                        SELECT $idCarteraPago, 1, $cartera , $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "
                                        FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $operacion ) ) > 0 ";
                            } else {

//                                  $sqlInsertPago=" INSERT IGNORE INTO ca_pago ( idcartera_pago, idcartera , usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." )
//                                      SELECT $idCarteraPago, $cartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."
//                                      FROM tmppago_".session_id()."_".$time." WHERE LOWER(TRIM($call_center))='hdec' ";

                                $sqlInsertPago = " INSERT IGNORE INTO ca_pago ( idcartera_pago, is_act, idcartera , usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " )
                                        SELECT $idCarteraPago, 1, $cartera , $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "
                                        FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $operacion ) ) > 0 AND LOWER(TRIM($call_center))='hdec' ";
                            }

                            $prInsertPago = $connection->prepare($sqlInsertPago);
                            if ($prInsertPago->execute()) {

                                /* if( trim($monto)=='' ) {
                                  //$connection->rollBack();
                                  echo json_encode(array('rst'=>false,'msg'=>'Seleccione Monto Pagado para actualizar cuenta'));
                                  }else{ */

                                if (trim($call_center) == '') {
                                    //$connection->commit();
                                    echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
                                } else {

                                    if (trim($monto) == '') {
                                        //$connection->commit();
                                        echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
                                    } else {

                                        $sqlRankinPago = " INSERT INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion )
                                                    SELECT " . $call_center . ", SUM( " . $monto . " ), $idCarteraPago, NOW(), $UsuarioCreacion
                                                    FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $call_center ) ) > 0 GROUP BY LOWER( TRIM( " . $call_center . " ) ) ";

                                        $prSqlRankinPago = $connection->prepare($sqlRankinPago);
                                        if ($prSqlRankinPago->execute()) {
                                            //$connection->commit();
                                            echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
                                        } else {
                                            $c1onnection->rollBack();
                                            @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                            @$prDropTablePagoRollback->execute();
                                            echo json_encode(array('rst' => false, 'msg' => 'Error agregar datos de ranking de pago'));
                                        }
                                    }
                                }

                                //}
                            } else {
                                //$connection->rollBack();
                                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                                @$prDropTablePagoRollback->execute();
                                echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de pago'));
                            }
                        } else {
                            //$connection->rollBack();
                            @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                            @$prDropTablePagoRollback->execute();
                            echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos a historial'));
                        }
                    } else {
                        //$connection->rollBack();
                        @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                        @$prDropTablePagoRollback->execute();
                        echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de temporal'));
                        exit();
                    }
                } else {
                    @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                    @$prDropTablePagoRollback->execute();
                    echo json_encode(array('rst' => false, 'msg' => 'Error al cargar datos de pago'));
                }
            } else {
                @$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
                @$prDropTablePagoRollback->execute();
                echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
        }
    }

    public function loadHeaderCentroPago($_post) {
        if (@opendir('../documents/centro_pago/' . $_post['NombreServicio'])) {
            if (@file_exists('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file'])) {

                $dataFile = file('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file']);

                /*                 * *** */
                //$archivo = file($path);

                $tmpArchivo = fopen('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file'], 'w');
                fwrite($tmpArchivo, '');
                fclose($tmpArchivo);

                $countHeader = 0;

                $tmpArchivo = fopen($path, 'a+');

                for ($i = 0; $i < count($dataFile); $i++) {
                    if ($i == 0) {
                        //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"');
                        //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'');
                        $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                        $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                        $line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
                        $explode_header = explode($_post['separator'], $line);
                        for ($j = 0; $j < count($explode_header); $j++) {
                            if ($explode_header[$j] == '') {
                                fclose($tmpArchivo);
                                unlink($path);
                                echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                                exit();
                            }
                        }
                        $countHeader = count($explode_header);
                        fwrite($tmpArchivo, $line);
                    } else {
                        $buscar = array('"', "'", "#", "&");
                        $cambia = array('', "", "", "");
                        //$line=str_replace("   ","|",$archivo[$i]);
                        $line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
                        $explode_line = explode($_post['separator'], $line);
                        if (count($explode_line) != $countHeader) {
                            fclose($tmpArchivo);
                            unlink($path);
                            echo json_encode(array('rst' => false, 'msg' => 'Linea ' . ($i + 1) . ' no coincide con longitud de cabeceras'));
                            exit();
                        }
                        fwrite($tmpArchivo, $line);
                    }
                }

                fclose($tmpArchivo);
                //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente'));

                /*                 * *** */
                $archivo = file('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file']);
                $dataHeader = explode($_post['separator'], $archivo[0]);
                //$dataHeaderMap=array_map("MapArrayHeader",$dataHeader);
                echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeader));
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
        }
    }

    public function uploadCentroPago($_post) {

        $file = $_post['file'];
        $separator = $_post['separator'];
        $servicio = $_post['Servicio'];
        $NombreServicio = $_post['NombreServicio'];
        $UsuarioCreacion = $_post['usuario_creacion'];
        $nombre = $_post['Nombre'];
        $jsonCentroPago = json_decode(str_replace("\\", "", $_post['data_centro_pago']), true);

        if (trim($nombre) == '') {
            echo json_encode(array('rst' => false, 'msg' => 'Ingrese nombre de archivo de centro de pago'));
            exit();
        }
        if (count($jsonCentroPago) == 0) {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione campos a cargar'));
            exit();
        }

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $path = "../documents/carteras/" . $NombreServicio . "/" . $file;

        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = file($path);
        $colum = explode($separator, $archivo[0]);
        if (count($colum) < 5) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                //$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                $item = "`" . $item . "` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, $colum[$i]);
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTableCentroPago = " CREATE TABLE tmpcentro_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
        //echo $sqlCreateTableCentroPago;
        $prCreateTableCentroPago = $connection->prepare($sqlCreateTableCentroPago);
        if ($prCreateTableCentroPago->execute()) {

            $sqlLoadCentroPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
                  INTO TABLE tmpcentro_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";

            $prLoadCentroPago = $connection->prepare($sqlLoadCentroPago);
            if ($prLoadCentroPago->execute()) {

                //$connection->beginTransaction();

                $sqlInsertDataFileCentroPago = " INSERT INTO ca_file_centro_pago (nombre,idservicio,fecha_carga,fecha_creacion,usuario_creacion)
                VALUES('" . $nombre . "',$servicio,NOW(),NOW(),$UsuarioCreacion) ";

                $prInsertDataFileCentroPago = $connection->prepare($sqlInsertDataFileCentroPago);
                if ($prInsertDataFileCentroPago->execute()) {

                    $id_centro_pago = $connection->lastInsertId();

                    $fieldCentroPago = array();
                    $fieldCentroPagoTMP = array();

                    foreach ($jsonCentroPago as $index => $value) {
                        array_push($fieldCentroPago, $index);
                        array_push($fieldCentroPagoTMP, $value);
                    }

                    $sqlInsertDataCentroPago = " INSERT INTO ca_centro_pago (idfile_centro_pago," . implode(",", $fieldCentroPago) . ",fecha_creacion,usuario_creacion)
                    SELECT $id_centro_pago," . implode(",", $fieldCentroPagoTMP) . ",NOW(),$UsuarioCreacion FROM tmpcentro_" . session_id() . "_" . $time . " ";
                    $prInsertDataCentroPago = $connection->prepare($sqlInsertDataCentroPago);
                    if ($prInsertDataCentroPago->execute()) {
                        //$connection->commit();

                        $sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
                        @$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
                        @$prDropTableCentroPago->execute();

                        echo json_encode(array('rst' => true, 'msg' => 'Centros de pagos cargados correctamente'));
                    } else {

                        //$connection->rollBack();

                        $sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
                        @$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
                        @$prDropTableCentroPago->execute();

                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar centros de pago'));
                    }
                } else {
                    //$connection->rollBack();

                    $sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
                    @$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
                    @$prDropTableCentroPago->execute();

                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar informacion de archivo'));
                }
            } else {

                $sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
                @$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
                @$prDropTableCentroPago->execute();

                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a temporal'));
            }
        } else {
            $sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
            @$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
            @$prDropTableCentroPago->execute();

            echo json_encode(array('rst' => false, 'msg' => 'Error al crear temporal'));
        }
    }

    public function uploadCargaAutomatica($_post) {

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = file($path);
        $colum = explode($_post['separator'], $archivo[0]);

        function map_header_automatic($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header_automatic", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, $colum[$i]);
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlJsonParse = " SELECT idjson_parser, cabeceras, cliente, cuenta, detalle_cuenta, telefono,
        direccion,adicionales,codigo_cliente,numero_cuenta, codigo_operacion, separador
        FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser ";

        $prJsonParser = $connection->prepare($sqlJsonParse);
        $prJsonParser->bindParam(1, $_post['Servicio']);
        $prJsonParser->execute();
        $ResultJsonParse = $prJsonParser->fetchAll(PDO::FETCH_ASSOC);
        //print_r($colum);
        //exit();
        $index = -1;

        for ($i = 0; $i < count($ResultJsonParse); $i++) {
            $countCheck = 0;
            $cabeceras = explode(",", $ResultJsonParse[$i]['cabeceras']);
            for ($j = 0; $j < count($colum); $j++) {
                if (in_array($colum[$j], $cabeceras)) {
                    $countCheck++;
                }
            }

            if ($countCheck == count($colum)) {
                $index = $i;
                break;
            }
        }

        /*         * **** ULTIMO PARSEO **** */

        //for( $i=0;$i<count($ResultJsonParse);$i++ ) {
//          $countCheck=0;
//          $cabeceras=explode(",",$ResultJsonParse[$i]['cabeceras']);
//          for( $j=0;$j<count($colum);$j++ ) {
//              if( in_array($colum[$j],$cabeceras) ) {
//                  $countCheck++;
//              }
//          }
//
//          if( $countCheck==count($colum) ) {
//              $index=$i;
//              break;
//          }
//
//      }

        /*         * **************** */

        if ($index == -1) {
            echo json_encode(array('rst' => false, 'msg' => 'Cabeceras no coinciden con ninguna de las plantillas, realize carga manual'));
            exit();
        }

        $postPlantilla = $_post;
        $postPlantilla['data_cliente'] = $ResultJsonParse[$index]['cliente'];
        $postPlantilla['data_cuenta'] = $ResultJsonParse[$index]['cuenta'];
        $postPlantilla['data_operacion'] = $ResultJsonParse[$index]['detalle_cuenta'];
        $postPlantilla['data_telefono'] = $ResultJsonParse[$index]['telefono'];
        $postPlantilla['data_direccion'] = $ResultJsonParse[$index]['direccion'];
        $postPlantilla['data_adicionales'] = $ResultJsonParse[$index]['adicionales'];

        $postPlantilla['codigo_cliente'] = $ResultJsonParse[$index]['codigo_cliente'];
        $postPlantilla['numero_cuenta'] = $ResultJsonParse[$index]['numero_cuenta'];
        $postPlantilla['codigo_operacion'] = $ResultJsonParse[$index]['codigo_operacion'];
        //$postPlantilla['separator']=$ResultJsonParse[$index]['saparador'];

        if ($_post['Proceso'] == 'carga') {
            $this->uploadCartera($postPlantilla, 0);
        } else if ($_post['Proceso'] == 'actualizacion') {
            $this->uploadUpdateCartera($postPlantilla, 0);
        }
    }

    public function uploadCargaAutomaticaPago($_post) {

        $file = $_post['file'];
        $separator = $_post['separator'];
        $servicio = $_post['Servicio'];
        $NombreServicio = $_post['NombreServicio'];
        $campania = $_post['Campania'];
        $cartera = $_post['Cartera'];
        $UsuarioCreacion = $_post['usuario_creacion'];

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        $path = "../documents/carteras/" . $_post['NombreServicio'] . "/" . $_post['file'];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = file($path);
        $colum = explode($_post['separator'], $archivo[0]);
        if (count($colum) < 5) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header_automatic_pay($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header_automatic_pay", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, $colum[$i]);
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $sqlPagoParser = " SELECT idjson_parser_pago, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, pago
        FROM ca_json_parser_pago WHERE idservicio = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $prJsonParserPago = $connection->prepare($sqlPagoParser);
        $prJsonParserPago->bindParam(1, $_post['Servicio']);
        $prJsonParserPago->execute();
        $ResultJsonParserPago = $prJsonParserPago->fetchAll(PDO::FETCH_ASSOC);

        $index = -1;

        for ($i = 0; $i < count($ResultJsonParserPago); $i++) {
            $countCheck = 0;
            $cabeceras = explode(",", $ResultJsonParserPago[$i]['cabeceras']);
            for ($j = 0; $j < count($colum); $j++) {
                if (in_array($colum[$j], $cabeceras)) {
                    $countCheck++;
                }
            }

            if ($countCheck == count($colum)) {
                $index = $i;
                break;
            }
        }

        if ($index == -1) {
            echo json_encode(array('rst' => false, 'msg' => 'Cabeceras no coinciden con ninguna de las plantillas, realize carga manual'));
            exit();
        }

        $postPlantilla = $_post;
        $postPlantilla['data_pago'] = $ResultJsonParserPago[$index]['pago'];

        $postPlantilla['codigo_cliente'] = $ResultJsonParserPago[$index]['codigo_cliente'];
        $postPlantilla['numero_cuenta'] = $ResultJsonParserPago[$index]['numero_cuenta'];
        $postPlantilla['codigo_operacion'] = $ResultJsonParserPago[$index]['codigo_operacion'];

        if ($_post['Proceso'] == 'carga') {
            $this->uploadCarteraPago($postPlantilla, 0);
        } else if ($_post['Proceso'] == 'actualizacion') {
            $this->uploadUpdateCarteraPago($postPlantilla, 0);
        }
    }

    /*     * ************* */

    public function uploadCarteraPlanta($_post, $is_parser=0) {
        //print_r($_post);
        //exit();
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        $codigo = $_post["codigo"];
        $usuario_creacion = $_post["usuario_creacion"];
        $nombre_cartera = $_POST["NombreCartera"];
        $id_cartera = 0;


        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }


        $time = date("Y_m_d_H_i_s");
        $archivoParser = file($path);
        $columMap = explode($_post['separator'], $archivoParser[0]);

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header", $columMap);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        //array_push($columHeader,"`idcliente` INT ");
        //array_push($columHeader,"`idcuenta` INT ");
        //array_push($columHeader,"`iddetalle_cuenta` INT ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo` ASC ) ");

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $parserPlanta = str_replace("\\", "", $_post["data_planta"]);
        $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

        $parserHeader = implode(",", $colum);

        $jsonPlanta = json_decode(str_replace("\\", "", $_post["data_planta"]), true);
        $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
        $prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
        if ($prDropTableTMPCartera->execute()) {

            $sqlCreateTabelTMPCartera = " CREATE TABLE tmpplanta_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
            $prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
            if ($prsqlCreateTabelTMPCartera->execute()) {

                $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                 INTO TABLE tmpplanta_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
                if ($prLoadDataInFileUC->execute()) {

                    //$connection->beginTransaction();

                    $selectCodigoClienteCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpplanta_" . session_id() . "_" . $time . " WHERE TRIM($codigo)='' ";
                    $prselectCodigoClienteCheck = $connection->prepare($selectCodigoClienteCheck);
                    $resultselectCodigoClienteCheck = $prselectCodigoClienteCheck->fetchAll(PDO::FETCH_ASSOC);
                    if ($resultselectCodigoClienteCheck[0]['COUNT'] > 0) {
                        //$connection->rollBack();
                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();
                        echo json_encode(array('rst' => false, 'msg' => 'Codigo posee campos vacios'));
                        exit();
                    }

                    $insertCartera = " INSERT INTO ca_cartera_planta( nombre,idcampania, cantidad_registros,fecha_carga,archivo,tabla,usuario_creacion,fecha_creacion, cabeceras, codigo )
                                VALUES ( '" . $nombre_cartera . "'," . $_post['Campania'] . " ," . (count($archivoParser) - 1) . ",NOW(),'" . utf8_encode($_post["file"]) . "','tmpplanta_" . session_id() . "_" . $time . "'," . $_post['usuario_creacion'] . ",NOW() ,'" . $parserHeader . "','" . $codigo . "' ) ";
                    $prInsertCartera = $connection->prepare($insertCartera);
                    if ($prInsertCartera->execute()) {

                        $id_cartera = $connection->lastInsertId();

                        /*                         * *********** */
                        if ($is_parser == 1) {

                            $insertJsonParser = " INSERT INTO ca_json_parser_planta ( idservicio, usuario_creacion, fecha_creacion, cabeceras, planta, adicionales, codigo )
                                        VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserPlanta . "','" . $parserAdicionales . "', '" . $codigo . "' ) ";
                            $prInsertJsonParser = $connection->prepare($insertJsonParser);
                            if ($prInsertJsonParser->execute()) {

                            } else {
                                //$connection->rollBack();
                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar metadata'));
                                exit();
                            }
                        }
                        /*                         * ************ */
                        $insertPlanta = " ";

                        $campoTablePlantaTMP = array();
                        $campoTablePlanta = array();

                        for ($i = 0; $i < count($jsonPlanta); $i++) {
                            array_push($campoTablePlanta, $jsonPlanta[$i]['campoT']);
                            array_push($campoTablePlantaTMP, " TRIM(" . $jsonPlanta[$i]['dato'] . ")");
                        }

                        $insertPlanta = " INSERT IGNORE INTO ca_planta ( idcartera_planta, usuario_creacion, fecha_creacion, " . implode(",", $campoTablePlanta) . " )
                                    SELECT $id_cartera , $usuario_creacion, NOW() , " . implode(",", $campoTablePlantaTMP) . " FROM tmpplanta_" . session_id() . "_" . $time . " ";

                        $prInsertCliente = $connection->prepare($insertPlanta);
                        if ($prInsertCliente->execute()) {

                            $fieldCabecera = array();
                            $fieldCabeceraTMP = array();
                            $fieldValueTMP = array();

                            foreach ($jsonAdicionales as $index => $value) {

                                array_push($fieldCabecera, $index);
                                array_push($fieldValueTMP, "'" . $value . "'");
                                array_push($fieldCabeceraTMP, $value);
                            }

                            $insertCabeceras = " INSERT IGNORE INTO ca_cabeceras_adicionales ( idcartera, is_planta, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " )
                                              VALUES( $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
                            $prInsertCabeceras = $connection->prepare($insertCabeceras);
                            if ($prInsertCabeceras->execute()) {

                                $insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_planta ( idcartera_planta, codigo, usuario_creacion, fecha_creacion, " . implode(",", $fieldCabecera) . " )
                                                  SELECT $id_cartera, TRIM($codigo),$usuario_creacion, NOW(), " . implode(",", $fieldCabeceraTMP) . "
                                                  FROM tmpplanta_" . session_id() . "_" . $time . " ";

                                $prInsertAdicionales = $connection->prepare($insertAdicionales);
                                if ($prInsertAdicionales->execute()) {
                                    //$connection->commit();

                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                    echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
                                } else {
                                    //$connection->rollBack();

                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
                                    exit();
                                }
                            } else {
                                //$connection->rollBack();

                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();

                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
                                exit();
                            }
                        } else {
                            //$connection->rollBack();

                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                            @$prsqlDropTableTMPCarteraRollBack->execute();

                            echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
                        }
                    } else {
                        //$connection->rollBack();

                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();

                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cartera'));
                    }
                } else {
                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                    @$prsqlDropTableTMPCarteraRollBack->execute();
                    echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
                }
            } else {
                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                @$prsqlDropTableTMPCarteraRollBack->execute();
                echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
        }
    }

    /*     * ************* */

    /*     * *********** */

    public function uploadCartera2($_post, $is_parser=0) {

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        $codigo_cliente = $_post["codigo_cliente"];
        $numero_cuenta = $_post["numero_cuenta"];
        $codigo_operacion = $_post["codigo_operacion"];
        $usuario_creacion = $_post["usuario_creacion"];
        $nombre_cartera = $_POST["NombreCartera"];
        $id_cartera = 0;


        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }


        $time = date("Y_m_d_H_i_s");
        $archivoParser = file($path);
        $columMap = explode($_post['separator'], $archivoParser[0]);

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header", $columMap);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        //array_push($columHeader,"`idcliente` INT ");
        //array_push($columHeader,"`idcuenta` INT ");
        //array_push($columHeader,"`iddetalle_cuenta` INT ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $parserCliente = str_replace("\\", "", $_post["data_cliente"]);
        $parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
        $parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
        $parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
        $parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
        $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

        $parserHeader = implode(",", $colum);

        $jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
        $jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
        $jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
        $jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
        $jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
        $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
        $prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
        if ($prDropTableTMPCartera->execute()) {

            $sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
            $prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
            if ($prsqlCreateTabelTMPCartera->execute()) {

                $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
                if ($prLoadDataInFileUC->execute()) {

                    //$connection->beginTransaction();

                    $selectCodigoClienteCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($codigo_cliente)='' ";
                    $prselectCodigoClienteCheck = $connection->prepare($selectCodigoClienteCheck);
                    $resultselectCodigoClienteCheck = $prselectCodigoClienteCheck->fetchAll(PDO::FETCH_ASSOC);
                    if ($resultselectCodigoClienteCheck[0]['COUNT'] > 0) {
                        //$connection->rollBack();
                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();
                        echo json_encode(array('rst' => false, 'msg' => 'Codigo de cliente posee campos vacios'));
                        exit();
                    }

                    $selectNumeroCuentaCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($numero_cuenta)='' ";
                    $prselectNumeroCuentaCheck = $connection->prepare($selectNumeroCuentaCheck);
                    $resultselectNumeroCuentaCheck = $prselectNumeroCuentaCheck->fetchAll(PDO::FETCH_ASSOC);
                    if ($resultselectNumeroCuentaCheck[0]['COUNT'] > 0) {
                        //$connection->rollBack();
                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();
                        echo json_encode(array('rst' => false, 'msg' => 'Codigo cuenta posee campos vacios'));
                        exit();
                    }

                    $selectCodigoOperacionCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($codigo_operacion)='' ";
                    $prselectCodigoOperacionCheck = $connection->prepare($selectCodigoOperacionCheck);
                    $resultselectCodigoOperacionCheck = $prselectCodigoOperacionCheck->fetchAll(PDO::FETCH_ASSOC);
                    if ($resultselectCodigoOperacionCheck[0]['COUNT'] > 0) {
                        //$connection->rollBack();
                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();
                        echo json_encode(array('rst' => false, 'msg' => 'Codigo operacion posee campos vacios'));
                        exit();
                    }


                    $insertCartera = " INSERT INTO ca_cartera( nombre_cartera,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion )
                                VALUES ( '" . $nombre_cartera . "'," . $_post['Campania'] . ",NOW()," . (count($archivoParser) - 1) . ",'tmpcartera_" . session_id() . "_" . $time . "','" . utf8_encode($_post["file"]) . "'," . $_post['usuario_creacion'] . ",NOW() ,'" . $parserHeader . "','" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "' ) ";
                    $prInsertCartera = $connection->prepare($insertCartera);
                    if ($prInsertCartera->execute()) {

                        $id_cartera = $connection->lastInsertId();

                        /*                         * *********** */
                        if ($is_parser == 1) {

                            $insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador )
                                        VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "' ) ";
                            $prInsertJsonParser = $connection->prepare($insertJsonParser);
                            if ($prInsertJsonParser->execute()) {

                            } else {
                                //$connection->rollBack();
                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar metadata'));
                                exit();
                            }
                        }

                        echo $insertJsonParser;
                            exit();

                        /*                         * ************ */
                        $insertCliente = " ";

                        $campoTableClienteTMP = array();
                        $campoTableCliente = array();

                        for ($i = 0; $i < count($jsonCliente); $i++) {
                            if ($jsonCliente[$i]['campoT'] == 'codigo') {
                                array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
                            } else {
                                array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                                array_push($campoTableClienteTMP, $jsonCliente[$i]['dato']);
                            }
                        }

                        $insertCliente = " INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " )
                                    SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY TRIM($codigo_cliente) ";

                        $prInsertCliente = $connection->prepare($insertCliente);
                        if ($prInsertCliente->execute()) {

                            $InsertClienteCartera = " INSERT IGNORE INTO ca_cliente_cartera ( codigo_cliente,idcartera,usuario_creacion,fecha_creacion )
                                        SELECT TRIM($codigo_cliente)," . $id_cartera . "," . $usuario_creacion . ",NOW() FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY TRIM($codigo_cliente) ";

                            $prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
                            if ($prInsertClienteCartera->execute()) {

                                $campoTableCuentaTMP = array();
                                $campoTableCuenta = array();

                                foreach ($jsonCuenta as $index => $value) {
                                    if ($index == "total_deuda") {
                                        array_push($campoTableCuenta, $index);
                                        array_push($campoTableCuentaTMP, " SUM( " . $value . " ) ");
                                    } else if ($index == "numero_cuenta") {
                                        array_push($campoTableCuenta, $index);
                                        array_push($campoTableCuentaTMP, " TRIM( " . $value . " )");
                                    } else {
                                        array_push($campoTableCuenta, $index);
                                        array_push($campoTableCuentaTMP, $value);
                                    }
                                }

                                $insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )
                                            SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . "
                                            FROM tmpcartera_" . session_id() . "_" . $time . "
                                            GROUP BY TRIM($codigo_cliente),TRIM($numero_cuenta) ORDER BY TRIM($codigo_cliente) ";

                                $prInsertCuenta = $connection->prepare($insertCuenta);
                                if ($prInsertCuenta->execute()) {


                                    if (count($jsonOperacion) > 0) {
                                        $campoTableOperacionTMP = array();
                                        $campoTableOperacion = array();

                                        //$fieldTramo="";

                                        for ($i = 0; $i < count($jsonOperacion); $i++) {

                                            if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                            } else {
                                                array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                                array_push($campoTableOperacionTMP, $jsonOperacion[$i]['dato']);
                                            }
                                        }

                                        //$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$campoTableOperacion).", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion )
                                        //SELECT ".implode(",",$campoTableOperacionTMP).", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW()
                                        //FROM tmpcartera_".session_id()."_".$time." GROUP BY TRIM( $codigo_cliente ), TRIM( $numero_cuenta), TRIM( $codigo_operacion ) ";

                                        $insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion )
                                                    SELECT " . implode(",", $campoTableOperacionTMP) . ", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW()
                                                    FROM tmpcartera_" . session_id() . "_" . $time . " ";

                                        $prInsertOperacion = $connection->prepare($insertOperacion);
                                        if ($prInsertOperacion->execute()) {


                                            $tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
                                            //$idDatosAdicionales=array("ca_datos_adicionales_cliente"=>"idcliente","ca_datos_adicionales_cuenta"=>"idcuenta","ca_datos_adicionales_detalle_cuenta"=>"iddetalle_cuenta");
                                            $idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "numero_cuenta", "ca_datos_adicionales_detalle_cuenta" => "codigo_operacion");
                                            $idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");

                                            foreach ($jsonAdicionales as $index => $value) {
                                                $fieldCabecera = array();
                                                $fieldCabeceraTMP = array();
                                                $fieldValueTMP = array();

                                                if (count($value) > 0) {

                                                    foreach ($value as $i => $v) {
                                                        array_push($fieldCabecera, $i);
                                                        array_push($fieldValueTMP, "'" . $v . "'");
                                                        array_push($fieldCabeceraTMP, $v);
                                                    }

                                                    $insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " )
                                                                    VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
                                                    $prInsertCabeceras = $connection->prepare($insertCabeceras);
                                                    if ($prInsertCabeceras->execute()) {

                                                        $insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " )
                                                                        SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
                                                                        FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $idTMPDatosAdicionales[$index] . "";

                                                        $prInsertAdicionales = $connection->prepare($insertAdicionales);
                                                        if ($prInsertAdicionales->execute()) {

                                                        } else {
                                                            //$connection->rollBack();

                                                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                            @$prsqlDropTableTMPCarteraRollBack->execute();

                                                            echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
                                                            exit();
                                                        }
                                                    } else {
                                                        //$connection->rollBack();

                                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                        @$prsqlDropTableTMPCarteraRollBack->execute();

                                                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
                                                        exit();
                                                    }
                                                }
                                            }

                                            //$connection->commit();
                                            echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
                                        } else {
                                            //$connection->rollBack();

                                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                            @$prsqlDropTableTMPCarteraRollBack->execute();

                                            echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
                                        }
                                    } else {
                                        //$connection->rollBack();

                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                        @$prsqlDropTableTMPCarteraRollBack->execute();

                                        echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
                                    }
                                } else {
                                    //$connection->rollBack();

                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                    echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
                                }
                            } else {
                                //$connection->rollBack();

                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();

                                echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
                                exit();
                            }
                        } else {
                            //$connection->rollBack();

                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                            @$prsqlDropTableTMPCarteraRollBack->execute();

                            echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
                        }
                    } else {
                        //$connection->rollBack();

                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();

                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cartera'));
                    }
                } else {
                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                    @$prsqlDropTableTMPCarteraRollBack->execute();
                    echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
                }
            } else {
                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                @$prsqlDropTableTMPCarteraRollBack->execute();
                echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
        }
    }

    public function uploadCarteraTelefono($_post) {

        $cartera = $_post['Cartera'];
        //$campania = $_post['Campania'];
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $idorigen = ($_post['origen'] == '0') ? 1 : $_post['origen'];
        $idtipo = ($_post['tipo'] == '0') ? 2 : $_post['tipo'];

        $parserTelefono = str_replace("\\", "", $_post['data_telefono']);
        $jsonTelefono = json_decode($parserTelefono, true);
        //print_r($jsonTelefono);
        /*         * *********** */
        $codigo_cliente = @$jsonTelefono["codigo_cliente"];
        $numero_cuenta = @$jsonTelefono["numero_cuenta"];
        $codigo_operacion = @$jsonTelefono["codigo_operacion"];
        /*         * *********** */

        if (trim($jsonTelefono["codigo_cliente"]) == '' || trim($jsonTelefono["codigo_cliente"]) == '0') {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione codigo de cliente'));
            exit();
        }

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = @fopen($path, "r+");
        $colum = array();
        if ($separator == 'tab') {
            $colum = explode("\t", fgets($archivo));
        } else {
            $colum = explode($separator, fgets($archivo));
        }
        if (count($colum) < 2) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                $buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $parserHeader = implode(",", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        fclose($archivo);
        /*         * *************** */
        array_push($columHeader, "`idcartera` INT ");
        array_push($columHeader, "`tmp_codigo_cliente` VARCHAR(50) ");
        array_push($columHeader, "`idcliente_cartera` INT ");
        array_push($columHeader, "`idcuenta` INT ");
        /*         * *************** */
        if ($codigo_cliente != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_codigo_cliente` ( `" . $codigo_cliente . "` ASC ) ");
        }
        if ($numero_cuenta != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_numero_cuenta` ( `" . $numero_cuenta . "` ASC ) ");
        }
        if ($codigo_operacion != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_codigo_operacion` ( `" . $codigo_operacion . "` ASC ) ");
        }
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcartera` ( `idcartera` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente_cartera` ( `idcliente_cartera` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_tmp_codigo_cliente` ( `tmp_codigo_cliente` ASC ) ");
        /*         * ***************** */

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTable = " CREATE TEMPORARY TABLE tmptelefono_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
        //echo $sqlCreateTable;
        $prCreateTable = $connection->prepare($sqlCreateTable);
        if ($prCreateTable->execute()) {
            $sqlLoad = "";
            if ($separator == 'tab') {
                $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                  INTO TABLE tmptelefono_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            } else {
                $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                  INTO TABLE tmptelefono_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            }

            $prLoad = $connection->prepare($sqlLoad);
            if ($prLoad->execute()) {

                //$connection->beginTransaction();

                $sqlUpdateTMP = "";
                if ($numero_cuenta != '') {
                    $sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu
                    ON cu.numero_cuenta = tmp.$numero_cuenta AND cu.codigo_cliente = tmp.$codigo_cliente
                    SET tmp.idcartera = cu.idcartera ,
                    tmp.tmp_codigo_cliente = cu.codigo_cliente ,
                    tmp.idcuenta = cu.idcuenta ,
                    tmp.idcliente_cartera = cu.idcliente_cartera
                    WHERE cu.idcartera IN ( " . $cartera . " ) ";
                } else if( $codigo_cliente != '' ) {
                    $sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu
                    ON cu.codigo_cliente = tmp.$codigo_cliente
                    SET tmp.idcartera = cu.idcartera ,
                    tmp.tmp_codigo_cliente = cu.codigo_cliente ,
                    tmp.idcuenta = cu.idcuenta ,
                    tmp.idcliente_cartera = cu.idcliente_cartera
                    WHERE cu.idcartera IN ( " . $cartera . " ) ";

                }else if ($codigo_operacion != '') {
                    $sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu
                    ON detcu.codigo_operacion = tmp.$codigo_operacion
                    SET tmp.idcartera = detcu.idcartera ,
                    tmp.tmp_codigo_cliente = detcu.codigo_cliente ,
                    tmp.idcuenta = detcu.idcuenta ,
                    tmp.idcliente_cartera = ( SELECT idcliente_cartera FROM ca_cuenta WHERE idcuenta = detcu.idcuenta LIMIT 1 )
                    WHERE detcu.idcartera IN ( " . $cartera . " ) ";
                } else{
                    echo json_encode(array('rst' => false, 'msg' => 'Seleccione codigo cliente , numero cuenta o numero de operacion'));
                    exit();
                }

                $prUpdateTMP = $connection->prepare($sqlUpdateTMP);
                if ($prUpdateTMP->execute()) {


                        $referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);


                        /* $sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras )
                          VALUES( ?, NOW(), ?, NOW(), ?, ? ) "; */
                        $values = array();
                        $e_cartera = explode(",", $cartera);
                        for ($i = 0; $i < count($e_cartera); $i++) {
                            array_push($values, " ( " . $e_cartera[$i] . " , NOW(), " . $usuario_creacion . " , NOW(), '" . $parserTelefono . "', '" . $parserHeader . "' ) ");
                        }

                        $sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras )
                            VALUES " . implode(",", $values) . " ";

                        $prCartera = $connection->prepare($sqlCartera);
                        /* $prCartera->bindParam(1,$cartera,PDO::PARAM_INT);
                          $prCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
                          $prCartera->bindParam(3,$parserTelefono,PDO::PARAM_STR);
                          $prCartera->bindParam(4,$parserHeader,PDO::PARAM_STR); */
                        if ($prCartera->execute()) {

                            foreach ($jsonTelefono["dataTelefono"] as $index => $value) {
                                $fieldTelefonoTMP = array();
                                $fieldTelefono = array();
                                $fieldReferenciaTelefono = "";

                                if (count($value) > 0) {

                                    $tipo_telefono = $idtipo;

                                    for ($i = 0; $i < count($value); $i++) {
                                        if ($value[$i]['campoT'] == 'numero') {
                                            $fieldReferenciaTelefono = $value[$i]['dato'];

                                            $tipo_telefono = " IF( SUBSTRING( TRIM( " . $value[$i]['dato'] . " ), 1, 1 ) = 9 , 1, 2 ) ";
                                        }
                                        array_push($fieldTelefono, $value[$i]['campoT']);
                                        array_push($fieldTelefonoTMP, " TRIM( " . $value[$i]['dato'] . " ) ");

                                    }

                                    if( $idtipo != '0' ) {
                                        $tipo_telefono = $idtipo;
                                    }

                                    $insertTelefono = "";
                                    if (trim($fieldReferenciaTelefono) == '') {
                                        /* $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." )
                                          SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), ".$idorigen.", $cartera, ".$referenciaTelefono[$index].", ".$idtipo.", $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
                                          FROM tmptelefono_".session_id()."_".$time." "; */

                                        $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, is_new, idcliente_cartera, idcuenta, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                        SELECT TRIM( tmp_codigo_cliente ), 1, idcliente_cartera, idcuenta, " . $idorigen . ", idcartera, " . $referenciaTelefono[$index] . ", " . $tipo_telefono . ", $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                        FROM tmptelefono_" . session_id() . "_" . $time . "
                                        WHERE ISNULL(tmp_codigo_cliente) = 0 AND ISNULL( idcartera ) = 0 AND ISNULL( idcliente_cartera ) = 0 ";
                                    } else {
                                        /* $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." )
                                          SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), ".$idorigen.", $cartera, ".$referenciaTelefono[$index].", ".$idtipo.", $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
                                          FROM tmptelefono_".session_id()."_".$time."
                                          WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 "; */

                                        $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, is_new, idcliente_cartera, idcuenta, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                        SELECT TRIM( tmp_codigo_cliente ), 1, idcliente_cartera, idcuenta, " . $idorigen . ", idcartera, " . $referenciaTelefono[$index] . ", " . $tipo_telefono . ", $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                        FROM tmptelefono_" . session_id() . "_" . $time . "
                                        WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4
                                        AND ISNULL(tmp_codigo_cliente) = 0 AND ISNULL( idcartera ) = 0 AND ISNULL( idcliente_cartera ) = 0 ";
                                    }
                                    /*CONDICIONAL PARA AGREGAR TELEFONOS PARA LOS CDO(CONTROL DE OPERACIONES)*/        
                                //    if($idorigen=='14'){
                                //        $insertTelefono=str_replace("is_new","is_carga",$insertTelefono);
                                //    }                                    

                                    $prTelefono = $connection->prepare($insertTelefono);
                                    if ($prTelefono->execute()) {

                                    } else {
                                        //$connection->rollBack();
                                        echo json_encode(array('rst' => false, 'msg' => 'Error al grabar telefonos'));
                                        exit();
                                    }
                                }
                            }

                            //$connection->commit();
                            echo json_encode(array('rst' => true, 'msg' => 'Cartera de telefonos cargada correctamente'));
                        } else {
                            //$connection->rollBack();
                            echo json_encode(array('rst' => false, 'msg' => 'Error al grabar data de tabla'));
                        }

                } else {
                    //$connection->rollBack();
                    echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar tabla temporal'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al cargar los datos a tabla'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
        }
    }

//  public function uploadCarteraTelefono ( $_post ) {
//
//      $cartera = $_post['Cartera'];
//      $campania = $_post['Campania'];
//      $servicio = $_post['Servicio'];
//      $nombre_servicio = $_post['NombreServicio'];
//      $usuario_creacion = $_post['usuario_creacion'];
//      $separator = $_post['separator'];
//
//      $parserTelefono = str_replace("\\","",$_post['data_telefono']);
//      $jsonTelefono = json_decode($parserTelefono,true);
//
//      if( trim($jsonTelefono["codigo_cliente"])=='' || trim($jsonTelefono["codigo_cliente"])=='0' ){
//          echo json_encode(array('rst'=>false,'msg'=>'Seleccione codigo de cliente'));
//          exit();
//      }
//
//      $confCobrast=parse_ini_file('../conf/cobrast.ini',true);
//
//      if( !isset($confCobrast['ruta_cobrast']) ){
//          echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//          exit();
//      }else if( !isset($confCobrast['ruta_cobrast']['document_root_cobrast']) ) {
//          echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//          exit();
//      }else if( !isset($confCobrast['ruta_cobrast']['nombre_carpeta']) ) {
//          echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//          exit();
//      }
//
//      $path="../documents/carteras/".$_post["NombreServicio"]."/".$_post["file"];
//      if( !file_exists($path) ) {
//          echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
//          exit();
//      }
//
//      $time=date("Y_m_d_H_i_s");
//      $archivo = @fopen($path,"r+");
//      $colum = explode($separator,fgets($archivo));
//      if( count( $colum )<2 ) {
//          echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto'));
//          exit();
//      }
//
//      function map_header( $n ) {
//          $item="";
//          if( trim(utf8_encode($n))!="" ){
//              $buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"',"?","Â¿","!","Â¡","[","]","-");
//              $cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");
//
//              $item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
//          }
//
//          return $item;
//      }
//      $colum = array_map("map_header",$colum);
//      $parserHeader = implode(",",$colum);
//      $columHeader=array();
//      $countHeaderFalse=0;
//
//      for( $i=0;$i<count($colum);$i++ ) {
//          if( $colum[$i]!="" ) {
//              array_push($columHeader,"`".$colum[$i]."` VARCHAR(200) ");
//          }else{
//              $countHeaderFalse++;
//          }
//      }
//
//      if( $countHeaderFalse>0 ) {
//          echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
//          exit();
//      }
//
//      fclose($archivo);
//
//
//
//      array_push($columHeader,"INDEX `index_".session_id()."_cliente` ( `".$jsonTelefono["codigo_cliente"]."` ASC ) ");
//
//      /********************/
//
//      $factoryConnection= FactoryConnection::create('mysql');
//        $connection = $factoryConnection->getConnection();
//
//      $sqlCreateTable=" CREATE TEMPORARY TABLE tmptelefono_".session_id()."_".$time." ( ".implode(",",$columHeader)." ) COLLATE=utf8_spanish_ci ";
//      $prCreateTable = $connection->prepare($sqlCreateTable);
//      if( $prCreateTable->execute() ) {
//
//          $sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
//            INTO TABLE tmptelefono_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
//          $prLoad = $connection->prepare($sqlLoad);
//          if( $prLoad->execute() ) {
//              $referenciaTelefono=array('telefono_predeterminado'=>3,'telefono_domicilio'=>2,'telefono_oficina'=>1,'telefono_negocio'=>4,'telefono_laboral'=>5);
//
//              //$connection->beginTransaction();
//
//              $sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras )
//                  VALUES( ?, NOW(), ?, NOW(), ?, ? ) ";
//              $prCartera = $connection->prepare($sqlCartera);
//              $prCartera->bindParam(1,$cartera,PDO::PARAM_INT);
//              $prCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
//              $prCartera->bindParam(3,$parserTelefono,PDO::PARAM_STR);
//              $prCartera->bindParam(4,$parserHeader,PDO::PARAM_STR);
//              if( $prCartera->execute() ) {
//
//                  foreach( $jsonTelefono["dataTelefono"] as $index => $value ) {
//                      $fieldTelefonoTMP = array();
//                      $fieldTelefono = array();
//                      $fieldReferenciaTelefono = "";
//
//                      if( count($value)>0 ) {
//
//                          for( $i=0;$i<count($value);$i++){
//                              if( $value[$i]['campoT']=='numero' ) {
//                                  $fieldReferenciaTelefono = $value[$i]['dato'];
//                              }
//                              array_push($fieldTelefono,$value[$i]['campoT']);
//                              array_push($fieldTelefonoTMP," TRIM( ".$value[$i]['dato']." ) ");
//                          }
//
//                          $insertTelefono = "";
//                          if( trim($fieldReferenciaTelefono)=='' ) {
//                              $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." )
//                              SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), 1, $cartera, ".$referenciaTelefono[$index].", 2, $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
//                              FROM tmptelefono_".session_id()."_".$time." ";
//                          }else{
//                              $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." )
//                              SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), 1, $cartera, ".$referenciaTelefono[$index].", 2, $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
//                              FROM tmptelefono_".session_id()."_".$time."
//                              WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 ";
//                          }
//
//                          $prTelefono = $connection->prepare($insertTelefono);
//                          if( $prTelefono->execute() ) {
//
//                          }else{
//                              //$connection->rollBack();
//                              echo json_encode(array('rst'=>false,'msg'=>'Error al grabar telefonos'));
//                              exit();
//                          }
//
//                      }
//
//                  }
//
//                  //$connection->commit();
//                  echo json_encode(array('rst'=>true,'msg'=>'Datos grabados correctamente'));
//
//              }else{
//                  //$connection->rollBack();
//                  echo json_encode(array('rst'=>false,'msg'=>'Error al grabar data de tabla'));
//              }
//
//          }else{
//              echo json_encode(array('rst'=>false,'msg'=>'Error al cargar los datos a tabla'));
//          }
//
//      }else{
//          echo json_encode(array('rst'=>false,'msg'=>'Error al crear tabla temporal'));
//      }
//
//
//  }


    public function uploadCarteraDetalle($_post) {

        $cartera = $_post['Cartera'];
        $campania = $_post['Campania'];
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];

        $codigo_operacion = $_post['codigo_operacion'];
        $numero_cuenta = $_post['numero_cuenta'];
        $moneda_cuenta = $_post['moneda_cuenta'];

        $parserAdicional = str_replace("\\", "", $_post['data_adicional']);
        $parserOperacion = str_replace("\\", "", $_post['data_detalle']);
        $jsonOperacion = json_decode($parserOperacion, true);
        $jsonAdicional = json_decode($parserAdicional, true);


        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = @fopen($path, "r+");
        $colum = explode($separator, fgets($archivo));
        if (count($colum) < 2) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $parserHeader = implode(",", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        fclose($archivo);

        array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `" . $numero_cuenta . "` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `" . $codigo_operacion . "` ASC ) ");
        if ($moneda_cuenta != '-Seleccione-' && trim($moneda_cuenta) != '') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `" . $moneda_cuenta . "` ASC ) ");
        }
        /*         * ***************** */

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTable = " CREATE TEMPORARY TABLE tmpdetalle_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
        //echo $sqlCreateTable;
        $prCreateTable = $connection->prepare($sqlCreateTable);
        if ($prCreateTable->execute()) {


            $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
              INTO TABLE tmpdetalle_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            $prLoad = $connection->prepare($sqlLoad);
            if ($prLoad->execute()) {

                //$connection->beginTransaction();

                $selectCartera = " SELECT idcartera, adicionales FROM ca_cartera WHERE idcartera = ? ";
                $prSelectCartera = $connection->prepare($selectCartera);
                $prSelectCartera->bindParam(1, $cartera);

                $prSelectCartera->execute();
                $dataCartera = $prSelectCartera->fetchAll(PDO::FETCH_ASSOC);
                //print_r($dataCartera);
                $dataAdicionales = json_decode($dataCartera[0]['adicionales'], true);
                //print_r($dataAdicionales);
                $dataAdicionales['ca_datos_adicionales_detalle_cuenta'] = $jsonAdicional['ca_datos_adicionales_detalle_cuenta'];
                //print_r($dataAdicionales);
                $parserAdicionalCartera = str_replace("\\", "", json_encode($dataAdicionales));
                //echo $parserAdicionalCartera;
                $sqlUpdateCartera = " UPDATE ca_cartera SET codigo_operacion = ? ,detalle_cuenta = ? , adicionales = ? ,
                            usuario_modificacion = ? , fecha_modificacion = NOW()  WHERE idcartera = ? ";

                $prUpdateCartera = $connection->prepare($sqlUpdateCartera);
                $prUpdateCartera->bindParam(1, $codigo_operacion, PDO::PARAM_STR);
                $prUpdateCartera->bindParam(2, $parserOperacion, PDO::PARAM_STR);
                $prUpdateCartera->bindParam(3, $parserAdicionalCartera, PDO::PARAM_STR);
                $prUpdateCartera->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
                $prUpdateCartera->bindParam(5, $cartera, PDO::PARAM_INT);
                if ($prUpdateCartera->execute()) {

                    $selectParser = " SELECT adicionales FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser DESC LIMIT 1 ";
                    $prSelectParser = $connection->prepare($selectParser);
                    $prSelectParser->bindParam(1, $servicio);
                    $prSelectParser->execute();
                    $dataParser = $prSelectParser->fetchAll(PDO::FETCH_ASSOC);
                    $dataAdicionalesParser = json_decode($dataParser[0]['adicionales'], true);
                    $dataAdicionalesParser['ca_datos_adicionales_detalle_cuenta'] = $jsonAdicional['ca_datos_adicionales_detalle_cuenta'];
                    $parserAdicionalParser = str_replace("\\", "", json_encode($dataAdicionalesParser));

//                  $sqlUpdateJsonParser = " UPDATE ca_json_parser SET numero_cuenta_detalle = ?,
//                      moneda_detalle = ?, codigo_operacion_detalle = ? , detalle_cuenta = ?,
//                      adicionales = ? , cabeceras_detalle = ?, usuario_modificacion = ?, fecha_modificacion = NOW()
//                      WHERE idservicio = ? AND idjson_parser = ( SELECT MAX(idjson_parser) FROM ca_json_parser WHERE idservicio = ? ) ";

                    $sqlUpdateJsonParser = " UPDATE ca_json_parser SET numero_cuenta_detalle = ?,
                        moneda_detalle = ?, codigo_operacion_detalle = ? , detalle_cuenta = ?,
                        adicionales = ? , cabeceras_detalle = ?, usuario_modificacion = ?, fecha_modificacion = NOW()
                        WHERE idservicio = ? ORDER BY idjson_parser DESC LIMIT 1 ";

                    $prUpdateJsonParser = $connection->prepare($sqlUpdateJsonParser);
                    $prUpdateJsonParser->bindParam(1, $numero_cuenta, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(2, $moneda_cuenta, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(3, $codigo_operacion, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(4, $parserOperacion, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(5, $parserAdicionalParser, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(6, $parserHeader, PDO::PARAM_STR);
                    $prUpdateJsonParser->bindParam(7, $usuario_creacion, PDO::PARAM_INT);
                    $prUpdateJsonParser->bindParam(8, $servicio, PDO::PARAM_INT);
                    //$prUpdateJsonParser->bindParam(9,$servicio,PDO::PARAM_INT);
                    if ($prUpdateJsonParser->execute()) {

                        $sqlCartera = " INSERT INTO ca_cartera_detalle( idcartera, fecha_carga, usuario_creacion, fecha_creacion, cabeceras, codigo_operacion, numero_cuenta, moneda_cuenta, detalle_cuenta, adicionales )
                                VALUES( ?,NOW(),?,NOW(),?,?,?,?,?,? ) ";
                        $prCartera = $connection->prepare($sqlCartera);
                        $prCartera->bindParam(1, $cartera, PDO::PARAM_INT);
                        $prCartera->bindParam(2, $usuario_creacion, PDO::PARAM_INT);
                        $prCartera->bindParam(3, $parserHeader, PDO::PARAM_STR);
                        $prCartera->bindParam(4, $codigo_operacion, PDO::PARAM_STR);
                        $prCartera->bindParam(5, $numero_cuenta, PDO::PARAM_STR);
                        $prCartera->bindParam(6, $moneda_cuenta, PDO::PARAM_STR);
                        $prCartera->bindParam(7, $parserOperacion, PDO::PARAM_STR);
                        $prCartera->bindParam(8, $parserAdicional, PDO::PARAM_STR);
                        if ($prCartera->execute()) {
                            $fieldOperacion = array();
                            $fieldTMP = array();
                            for ($i = 0; $i < count($jsonOperacion); $i++) {
                                array_push($fieldOperacion, $jsonOperacion[$i]['campoT']);
                                array_push($fieldTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                            }
                            $insertOperacion = "";
                            if ($moneda_cuenta != '-Seleccione-' && trim($moneda_cuenta) != '') {

                                //                          $insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , idcartera, usuario_creacion, fecha_creacion )
                                //                              SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ) , $cartera, $usuario_creacion , NOW()
                                //                              FROM tmpdetalle_".session_id()."_".$time."
                                //                              WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0
                                //                              GROUP BY TRIM( $codigo_operacion ) ";
                                //                          $insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta, codigo_cliente , idcartera, usuario_creacion, fecha_creacion )
                                //                              SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND idcartera = $cartera LIMIT 1  ), $cartera, $usuario_creacion , NOW()
                                //                              FROM tmpdetalle_".session_id()."_".$time."
                                //                              WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0
                                //                              GROUP BY TRIM( $codigo_operacion ) ";

                                $insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $fieldOperacion) . ", numero_cuenta , moneda, codigo_cliente,  idcartera, usuario_creacion, fecha_creacion )
                                    SELECT " . implode(",", $fieldTMP) . ", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND moneda = TRIM( $moneda_cuenta ) AND idcartera = $cartera LIMIT 1  ) , $cartera, $usuario_creacion , NOW()
                                    FROM tmpdetalle_" . session_id() . "_" . $time . "
                                    WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0
                                    GROUP BY TRIM( $codigo_operacion ) ";
                            } else {

                                //                          $insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , moneda, idcartera, usuario_creacion, fecha_creacion )
                                //                              SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , $cartera, $usuario_creacion , NOW()
                                //                              FROM tmpdetalle_".session_id()."_".$time."
                                //                              WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0
                                //                              GROUP BY TRIM( $codigo_operacion ) ";

                                $insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $fieldOperacion) . ", numero_cuenta, codigo_cliente , idcartera, usuario_creacion, fecha_creacion, is_detalle )
                                    SELECT " . implode(",", $fieldTMP) . ", TRIM( $numero_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND idcartera = $cartera LIMIT 1  ), $cartera, $usuario_creacion , NOW() , 1
                                    FROM tmpdetalle_" . session_id() . "_" . $time . "
                                    WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 ";

                                //                          $insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , moneda, codigo_cliente,  idcartera, usuario_creacion, fecha_creacion )
                                //                              SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND moneda = TRIM( $moneda_cuenta ) AND idcartera = $cartera LIMIT 1  ) , $cartera, $usuario_creacion , NOW()
                                //                              FROM tmpdetalle_".session_id()."_".$time."
                                //                              WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 ";
                            }

                            $prInsertOperacion = $connection->prepare($insertOperacion);
                            if ($prInsertOperacion->execute()) {

                                $updateCuenta = " UPDATE ca_cuenta cu
                                    SET cu.total_deuda =
                                    (
                                    SELECT SUM(total_deuda)
                                    FROM ca_detalle_cuenta WHERE idcartera = $cartera AND numero_cuenta = cu.numero_cuenta
                                    GROUP BY numero_cuenta LIMIT 1
                                    )
                                    WHERE cu.idcartera = $cartera ";

                                $prUpdateCuenta = $connection->prepare($updateCuenta);
                                if ($prUpdateCuenta->execute()) {

                                    if (count($jsonAdicional['ca_datos_adicionales_detalle_cuenta']) > 0) {
                                        $fieldAdicional = array();
                                        $fieldAdicionalTMP = array();
                                        $cabecerasAdicional = array();
                                        //                                  foreach( $jsonAdicional['ca_datos_adicionales_detalle_cuenta'] as $index => $value ){
                                        //                                      array_push($fieldAdicional,$index);
                                        //                                      array_push($fieldAdicionalTMP,"TRIM( ".$value." )");
                                        //                                      array_push($cabecerasAdicional,"'".$value."'");
                                        //                                  }


                                        for ($i = 0; $i < count($jsonAdicional['ca_datos_adicionales_detalle_cuenta']); $i++) {
                                            array_push($fieldAdicional, $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
                                            array_push($fieldAdicionalTMP, "TRIM( " . $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['dato'] . " )");
                                            array_push($cabecerasAdicional, "'" . $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['label'] . "'");
                                        }

                                        $insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldAdicional) . " )
                                                VALUES( " . $servicio . " , 3, $cartera, NOW(), $usuario_creacion, " . implode(",", $cabecerasAdicional) . " ) ";
                                        $prInsertCabeceras = $connection->prepare($insertCabeceras);
                                        if ($prInsertCabeceras->execute()) {

                                            //                                  $insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, ".implode(",",$fieldAdicional)." )
                                            //                                      SELECT $cartera, TRIM( $codigo_operacion ), ".implode(",",$fieldAdicionalTMP)."
                                            //                                      FROM tmpdetalle_".session_id()."_".$time." GROUP BY TRIM( $codigo_operacion ) ";
                                            //                                  $insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, codigo_cliente, ".implode(",",$fieldAdicional)." )
                                            //                                      SELECT $cartera, TRIM( $codigo_operacion ), ( SELECT codigo_cliente FROM ca_detalle_cuenta WHERE codigo_operacion = TRIM( $codigo_operacion ) AND idcartera = $cartera LIMIT 1  ) , ".implode(",",$fieldAdicionalTMP)."
                                            //                                      FROM tmpdetalle_".session_id()."_".$time." ";
                                            $insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, codigo_cliente, " . implode(",", $fieldAdicional) . " )
                                                SELECT $cartera, TRIM( $codigo_operacion ), ( SELECT codigo_cliente FROM ca_detalle_cuenta WHERE codigo_operacion = TRIM( $codigo_operacion ) AND idcartera = $cartera LIMIT 1  ) , " . implode(",", $fieldAdicionalTMP) . "
                                                FROM tmpdetalle_" . session_id() . "_" . $time . " GROUP BY TRIM( $codigo_operacion ) ";
                                            //echo $insertAdicionales;
                                            $prInsertAdicionales = $connection->prepare($insertAdicionales);
                                            if ($prInsertAdicionales->execute()) {
                                                //$connection->commit();
                                                echo json_encode(array('rst' => true, 'msg' => 'Datos grabados correctamente'));
                                            } else {
                                                //$connection->rollBack();
                                                echo json_encode(array('rst' => false, 'msg' => 'Error al grabar datos adicionales'));
                                            }
                                        } else {
                                            //$connection->rollBack();
                                            echo json_encode(array('rst' => false, 'msg' => 'Error al grabar cabeceras'));
                                        }
                                    } else {
                                        //$connection->commit();
                                        echo json_encode(array('rst' => true, 'msg' => 'Datos grabados correctamente'));
                                    }
                                } else {
                                    //$connection->rollBack();
                                    echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar deuda total'));
                                }
                            } else {
                                //$connection->rollBack();
                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar detalle'));
                            }
                        } else {
                            //$connection->rollBack();
                            echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
                        }
                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar metadata'));
                    }
                } else {
                    //$connection->rollBack();
                    echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar de datos de cartera'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
        }
    }

    public function uploadCarteraReclamo($_post) {

        $cartera = $_post['Cartera'];
        $campania = @$_post['Campania'];
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];

        /*         * ****** */
        if ($cartera == '') {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione carteras'));
            exit;
        }
        /*         * ****** */
        $codigo_cliente = '';
        $codigo_operacion = '';
        /*         * ***** */
        $numero_cuenta = '';
        $moneda = '';
        $telefono = '';

        $parserReclamo = str_replace("\\", "", $_post['data_reclamo']);
        $jsonReclamo = json_decode($parserReclamo, true);

        $field_u_on = array();

        for ($i = 0; $i < count($jsonReclamo); $i++) {
            if ($jsonReclamo[$i]['campoT'] == 'numero_cuenta') {
                $numero_cuenta = $jsonReclamo[$i]['dato'];
                array_push($field_u_on," detcu.numero_cuenta = tmp.".$jsonReclamo[$i]['dato']." ");
            } else if ($jsonReclamo[$i]['campoT'] == 'moneda') {
                $moneda = $jsonReclamo[$i]['dato'];
                array_push($field_u_on," detcu.moneda = tmp.".$jsonReclamo[$i]['dato']." ");
            } else if ($jsonReclamo[$i]['campoT'] == 'telefono') {
                $telefono = $jsonReclamo[$i]['dato'];

            } else if ($jsonReclamo[$i]['campoT'] == 'codigo_cliente') {
                $codigo_cliente = $jsonReclamo[$i]['dato'];
                array_push($field_u_on," detcu.codigo_cliente = tmp.".$jsonReclamo[$i]['dato']." ");
            } else if ($jsonReclamo[$i]['campoT'] == 'codigo_operacion') {
                $codigo_operacion = $jsonReclamo[$i]['dato'];
                array_push($field_u_on," detcu.codigo_operacion = tmp.".$jsonReclamo[$i]['dato']." ");
            }
        }

        if (trim($numero_cuenta) == '') {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione numero de cuenta'));
            exit();
        }

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = @fopen($path, "r+");
        $colum = explode($separator, fgets($archivo));
        if (count($colum) < 2) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $parserHeader = implode(",", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        /*         * ************** */
        array_push($columHeader, "`iddetalle_cuenta` int ");
        array_push($columHeader, "`idcuenta` int ");
        array_push($columHeader, "`idcliente_cartera` int ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_iddetalle_cuenta` ( `iddetalle_cuenta` ASC ) ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_idcliente` ( `idcliente_cartera` ASC ) ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_numero_cuenta` ( `$numero_cuenta` ASC ) ");
        array_push($columHeader, " INDEX `index_" . session_id() . "_codigo_operacion` ( `$codigo_operacion` ASC ) ");
        /*         * ************** */
        fclose($archivo);

        /*         * ***************** */

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTable = " CREATE TABLE tmpreclamo_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";

        //echo $sqlCreateTable;
        $prCreateTable = $connection->prepare($sqlCreateTable);
        if ($prCreateTable->execute()) {
            $sqlLoad = "";
            if ($_post['separator'] == 'tab') {
                $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                  INTO TABLE tmpreclamo_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            } else {
                $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                  INTO TABLE tmpreclamo_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            }

            $prLoad = $connection->prepare($sqlLoad);
            if ($prLoad->execute()) {

                //$connection->beginTransaction();

                /*                 * ************* */
                $values = array();
                $e_carteras = explode(",", $cartera);
                for ($i = 0; $i < count($e_carteras); $i++) {
                    array_push($values, " ( " . $e_carteras[$i] . ", NOW() ," . $usuario_creacion . ", NOW(), '" . $parserReclamo . "', '" . $parserHeader . "' ) ");
                }
                $insertCarteraReclamo = " INSERT INTO ca_cartera_reclamo ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, reclamo, cabeceras )
                    VALUES " . implode(",", $values) . "  ";
                /*                 * ************* */

                /* $insertCarteraReclamo = " INSERT INTO ca_cartera_reclamo ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, reclamo, cabeceras )
                  VALUES ( ?,NOW(),?,NOW(),?,? ) ";

                  $prInsertCartera = $connection->prepare($insertCarteraReclamo);
                  $prInsertCartera->bindParam(1,$cartera,PDO::PARAM_INT);
                  $prInsertCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
                  $prInsertCartera->bindParam(3,$parserReclamo,PDO::PARAM_STR);
                  $prInsertCartera->bindParam(4,$parserHeader,PDO::PARAM_STR); */
                //echo $insertCarteraReclamo;
                $prInsertCartera = $connection->prepare($insertCarteraReclamo);
                if ($prInsertCartera->execute()) {

                    $sqlUpdateIdTMP = " UPDATE tmpreclamo_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu
                         ON ".implode(" AND ",$field_u_on)."
                         SET
                         tmp.iddetalle_cuenta = detcu.iddetalle_cuenta ,
                         tmp.idcuenta = detcu.idcuenta
                         WHERE detcu.idcartera IN ( " . $cartera . " ) ";

                    $prUpdateIdTMP = $connection->prepare($sqlUpdateIdTMP);
                    if ($prUpdateIdTMP->execute()) {

                        $sqlUpdateIdTMPClienteCartera = " UPDATE tmpreclamo_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu
                            ON cu.idcuenta = tmp.idcuenta
                            SET tmp.idcliente_cartera = cu.idcliente_cartera
                            WHERE cu.idcartera IN ( ".$cartera." ) AND ISNULL(tmp.idcuenta) = 0 ";

                        $prUpdateIdTMPClienteCartera = $connection->prepare($sqlUpdateIdTMPClienteCartera);
                        if ($prUpdateIdTMPClienteCartera->execute()) {

                            $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp
                            ON tmp.idcliente_cartera = clicar.idcliente_cartera
                            SET clicar.reclamo = 1
                            WHERE ISNULL(tmp.idcliente_cartera) = 0 AND clicar.idcartera IN ( ".$cartera." ) ";

                            $prUpdateClienteCartera = $connection->prepare($sqlUpdateClienteCartera);
                            if ($prUpdateClienteCartera->execute()) {

                                $sqlUpdateCuenta = " UPDATE ca_cuenta cu INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp
                                ON tmp.idcuenta = cu.idcuenta
                                SET cu.is_reclamo = 1
                                WHERE ISNULL(tmp.idcuenta) = 0 AND cu.idcartera IN ( ".$cartera." ) ";

                                $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                                if ($prUpdateCuenta->execute()) {

                                    $fieldSet = array();
                                    for ($i = 0; $i < count($jsonReclamo); $i++) {

                                        if ($jsonReclamo[$i]['campoT'] != 'numero_cuenta' && $jsonReclamo[$i]['campoT'] != 'moneda' && $jsonReclamo[$i]['campoT'] != 'telefono' && $jsonReclamo[$i]['campoT'] != 'codigo_cliente' && $jsonReclamo[$i]['campoT'] != 'codigo_operacion') {
                                            array_push($fieldSet, " detcu." . $jsonReclamo[$i]['campoT'] . " = tmp." . $jsonReclamo[$i]['dato'] . " ");
                                        }
                                    }

                                    $implode_field_set = "";

                                    if (count($fieldSet) > 0) {
                                        $implode_field_set = " , " . implode(",", $fieldSet);
                                    }

                                    $sqlUpdateDetalleCuenta = " UPDATE ca_detalle_cuenta detcu INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp
                                    ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
                                    SET detcu.is_reclamo = 1 " . $implode_field_set . "
                                    WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND detcu.idcartera IN ( ".$cartera." ) ";

                                    $prUpdateDetalleCuenta = $connection->prepare($sqlUpdateDetalleCuenta);
                                    if ($prUpdateDetalleCuenta->execute()) {
                                        //$connection->commit();
                                        echo json_encode(array('rst' => true, 'msg' => 'Cartera de reclamo cargada correctamente'));
                                    } else {
                                        //$connection->rollBack();
                                        echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar facturas'));
                                    }
                                } else {
                                    //$connection->rollBack();
                                    echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar cuentas'));
                                }
                            } else {
                                //$connection->rollBack();
                                echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar clientes'));
                            }

                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar temporal( cliente )'));
                            exit();
                        }



                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar temporal( detalle_cuenta, cuenta )'));
                    }

                    /* $sqlInsertReclamos = "";
                      if( $moneda == '' && $telefono == '' ) {
                      $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
                      WHERE idcartera = ? AND TRIM(numero_cuenta) IN ( SELECT TRIM($numero_cuenta) FROM tmpreclamo_".session_id()."_".$time." ) ";
                      }else if( $moneda == '' && $telefono != '' ) {
                      $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
                      WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(telefono)) IN
                      ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($telefono)) FROM tmpreclamo_".session_id()."_".$time." ) ";
                      }else if( $moneda != '' && $telefono == '' ) {
                      $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
                      WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(moneda)) IN
                      ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda)) FROM tmpreclamo_".session_id()."_".$time." ) ";
                      }else if( $moneda != '' && $telefono != '' ) {
                      $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
                      WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(moneda),TRIM(telefono)) IN
                      ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda),TRIM($telefono)) FROM tmpreclamo_".session_id()."_".$time." ) ";
                      }else{
                      //$connection->rollBack();
                      echo json_encode(array('rst'=>false,'msg'=>'Error al insertar reclamos'));
                      }
                      //echo $sqlInsertReclamos;
                      $prInsertReclamos = $connection->prepare($sqlInsertReclamos);
                      $prInsertReclamos->bindParam(1,$cartera,PDO::PARAM_INT);
                      if( $prInsertReclamos->execute() ) {

                      //$connection->commit();
                      echo json_encode(array('rst'=>true,'msg'=>'Datos de cartera insertados correctamente'));

                      }else{
                      //$connection->rollBack();
                      echo json_encode(array('rst'=>false,'msg'=>'Error al insertar reclamos'));
                      } */
                } else {
                    //$connection->rollBack();
                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
        }
    }

    public function uploadCarteraNOCpre_masivo($_post, $file) {

        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $formatoFechas = $_post['formatoFechas'];
        $estado_noc_pre = $_post['estado_noc_pre'];

        //COMPROBACIOON RUTA COBRAST SQLLITE
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }
        $path = "../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
            /* echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
              exit(); */
        }


        $time = date("Y_m_d_H_i_s");

        /*         * ***************** */
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();


        $sqlDropTableTmpNocPredictivo = " DROP TABLE IF EXISTS tmp_noc_predictivo_" . $time;
        $prDropTableTmpNocPredictivo = $connection->prepare($sqlDropTableTmpNocPredictivo);
        if ($prDropTableTmpNocPredictivo->execute()) {

            $sqlCreateTableTmpNocPredictivo = " CREATE TABLE tmp_noc_predictivo_" . $time . " (
            ID varchar(50),
            ESTADO varchar(50),
            Fecha_Hora varchar(50),
            Hora varchar(50),
            Telefono varchar(50),
            codigo_cliente  varchar(50),
            idcliente_cartera int,
            idcartera int,
            idcliente int,
            idcuenta int,
            inicio_tramo datetime,
            fin_tramo datetime,
            idtelefono varchar(30),
            fecha_creacion datetime
            ) ENGINE = InnoDB ";

            $prsqlCreateTableTmpNocPredictivo = $connection->prepare($sqlCreateTableTmpNocPredictivo);
            if ($prsqlCreateTableTmpNocPredictivo->execute()) {

                /***************/

                $sqlLoad = "";
                if( $separator == 'tab' ) {
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/nocpredictivo/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_noc_predictivo_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }else{
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/nocpredictivo/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_noc_predictivo_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }

                $prLoad = $connection->prepare($sqlLoad);

                if ($prLoad->execute()) {

                    /* CARGA A TEMPORAL */

                    //$connection->beginTransaction();

                    $sqlUpdate1tmp = " update tmp_noc_predictivo_" . $time . "
                        set Telefono = if(length(Telefono)=7,concat('1',Telefono),Telefono) ";
                    $prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
                    if ($prUpdate1tmp->execute()) {

                        $sqlUpdate2tmp = "update tmp_noc_predictivo_" . $time . " t set t.ID=lpad(t.ID,10,'0')";
                        $prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
                        if ($prUpdate2tmp->execute()) {

                            $sqlUpdate3tmp = " update tmp_noc_predictivo_" . $time . " t
                                set idcartera = (  select max(cu.idcartera) from ca_cuenta cu inner join ca_cartera car on car.idcartera = cu.idcartera where cu.numero_cuenta = trim(t.ID) and car.estado = 1 ) ";
                            $prUpdate3tmp = $connection->prepare($sqlUpdate3tmp);
                            if ($prUpdate3tmp->execute()) {

                                $sqlUpdate4tmp = "update tmp_noc_predictivo_" . $time . " t
                                    set codigo_cliente = (  select codigo_cliente from ca_cuenta where numero_cuenta = trim(t.ID) and idcartera = t.idcartera ) ";
                                $prUpdate4tmp = $connection->prepare($sqlUpdate4tmp);
                                if ($prUpdate4tmp->execute()) {

                                    $sqlUpdate5tmp = " update tmp_noc_predictivo_" . $time . " t inner join ca_cuenta cu
                                        on cu.idcartera = t.idcartera and cu.codigo_cliente = t.codigo_cliente and cu.numero_cuenta = trim(t.ID)
                                        set
                                        t.idcuenta = cu.idcuenta ,
                                        t.idcliente_cartera = cu.idcliente_cartera
                                        where isnull(t.idcartera) = 0 and isnull(t.codigo_cliente) = 0 ";
                                    $prUpdate5tmp = $connection->prepare($sqlUpdate5tmp);
                                    if ($prUpdate5tmp->execute()) {

                                        $sqlUpdate6tmp = "update tmp_noc_predictivo_" . $time . " t inner join ca_cliente_cartera clicar
                                            on clicar.idcartera = t.idcartera and clicar.idcliente_cartera = t.idcliente_cartera
                                            set t.idcliente = clicar.idcliente
                                            where isnull(t.idcartera) = 0 and isnull(t.idcliente_cartera)=0 ";
                                        $prUpdate6tmp = $connection->prepare($sqlUpdate6tmp);
                                        if ($prUpdate6tmp->execute()) {

                                                        $sqlUpdate10tmp = "update tmp_noc_predictivo_" . $time . " t
                                                        set idtelefono = ( select idtelefono from ca_telefono where numero = trim(t.Telefono) and idcuenta = t.idcuenta and idcartera = t.idcartera limit 1 )";
                                                        $prUpdate10tmp = $connection->prepare($sqlUpdate10tmp);
                                                        if ($prUpdate10tmp->execute()) {

                                                                /*                                                                 * *************************  DISTRIBUCION DE DATOS */

                                                                /*$sqlInsertTransaccion = "insert into ca_transaccion (
                                                                    idtipo_gestion,idcliente_cartera,idfinal,fecha_creacion,usuario_creacion,fecha,is_predictivo_noc,telefono,observacion
                                                                    )
                                                                    select 2,idcliente_cartera,306,fecha_creacion,1,concat_ws(' ',STR_TO_DATE(Fecha_Hora,'" . $formatoFechas . "'),substr(Fecha_Hora,12,8)) as Fecha_Hora,1,Telefono,'NO CONTESTA PREDICTIVO'
                                                                    from  tmp_noc_predictivo_" . $time . " where idtelefono is not null and idcartera is not null and Fecha_Hora is not null ";*/


                                                                $sqlInsertTransaccion = " INSERT INTO ca_llamada (
                                                                    idcliente_cartera, idfinal, idtipo_gestion, idtelefono, idcuenta, fecha, observacion, tipo, fecha_creacion, usuario_creacion
                                                                    )
                                                                    SELECT idcliente_cartera, $estado_noc_pre, 2, idtelefono, idcuenta, concat_ws(' ',STR_TO_DATE(Fecha_Hora,'" . $formatoFechas . "'),substr(Fecha_Hora,12,8)), 'NO CONTESTA PREDICTIVO', 'NOCPRE', NOW(), $usuario_creacion
                                                                    FROM tmp_noc_predictivo_" . $time . "
                                                                    WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(idtelefono) = 0 ";
                                                                $prInsertTransaccion = $connection->prepare($sqlInsertTransaccion);
                                                                if ($prInsertTransaccion->execute()) {

                                                                    $sqlUpdCliCar = "
                                                                            update ca_cliente_cartera clicar inner join tmp_noc_predictivo_" . $time . " t
                                                                            on t.idcliente_cartera=clicar.idcliente_cartera
                                                                            set is_noc_predictivo = 1
                                                                        ";
                                                                    $prUpdCliCar = $connection->prepare($sqlUpdCliCar);
                                                                    if ($prUpdCliCar->execute()) {

                                                                        return array('rst' => true, 'msg' => 'Datos NOC Predictivo insertados correctamente');

                                                                    } else {
                                                                        //$connection->rollBack();
                                                                        return array('rst' => false, 'msg' => 'Error al Actualizar Tabla Cliente Cartera');
                                                                        //echo json_encode(array('rst'=>false,'msg'=>'Error al Actualizar Tabla Cliente Cartera'));
                                                                    }
                                                                } else {
                                                                    //$connection->rollBack();
                                                                    return array('rst' => false, 'msg' => 'Error al insertar datos de Transaccion');
                                                                    //echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos de Transaccion'));
                                                                }

                                                                /*                                                                 * ************************ */

                                                        } else {
                                                            //$connection->rollBack();
                                                            return array('rst' => true, 'msg' => 'Error en carga de datos temporales10');
                                                            //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales10'));
                                                        }



                                        } else {
                                            //$connection->rollBack();
                                            return array('rst' => true, 'msg' => 'Error en carga de datos temporales6');
                                            //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales6'));
                                        }
                                    } else {
                                        //$connection->rollBack();
                                        return array('rst' => true, 'msg' => 'Error en carga de datos temporales5');
                                        //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales5'));
                                    }
                                } else {
                                    //$connection->rollBack();
                                    return array('rst' => true, 'msg' => 'Error en carga de datos temporales4');
                                    //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales4'));
                                }
                            } else {
                                //$connection->rollBack();
                                return array('rst' => true, 'msg' => 'Error en carga de datos temporales3');
                                //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales3'));
                            }
                        } else {
                            //$connection->rollBack();
                            return array('rst' => true, 'msg' => 'Error en carga de datos temporales2');
                            //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales2'));
                        }
                    } else {
                        //$connection->rollBack();
                        return array('rst' => true, 'msg' => 'Error en carga de datos temporales1');
                        //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales1'));
                    }



                    /* FIN CARGA A TEMPORAL */
                } else {
                    return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
                    //echo json_encode(array('rst'=>false,'msg'=>'Error al Cargar Datos a Temporal'));
                }

                /*                 * ********* */

                /* $sqlDropTableFinalTmpNocPredictivo=" DROP TABLE IF EXISTS tmp_noc_predictivo_".$time;
                  $prDropTableFinalTmpNocPredictivo=$connection->prepare($sqlDropTableFinalTmpNocPredictivo);
                  $prDropTableFinalTmpNocPredictivo->execute(); */
            } else {
                return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
                //echo json_encode(array('rst'=>false,'msg'=>'Error al Crear Tabla Temporal'));
            }
        } else {
            return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
            //echo json_encode(array('rst'=>false,'msg'=>'Error al Eliminar Tabla Temporal Anterior'));
        }
    }

    public function uploadCarteraIVR_masivo($_post, $file) {

        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $formatoFechas = $_post['formatoFechas'];
        $estado_contestado = $_post['estado_contestado'];
        $estado_no_contestado = $_post['estado_no_contestado'];
        //$cartera=$_post['Cartera'];
        //COMPROBACIOON RUTA COBRAST SQLLITE
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }
        $path = "../documents/ivr/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
            /* echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
              exit(); */
        }


        $time = date("Y_m_d_H_i_s");

        /*         * ***************** */
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();


        $sqlDropTableTmpIVR = " DROP TABLE IF EXISTS tmp_ivr_" . $time;
        $prDropTableTmpIVR = $connection->prepare($sqlDropTableTmpIVR);
        if ($prDropTableTmpIVR->execute()) {

            $sqlCreateTableTmpIVR = " create table tmp_ivr_" . $time . " (
                telefono varchar(50),
                campania varchar(50),
                estado varchar(50),
                fecha varchar(10),
                hora varchar(50),
                Fecha_Hora varchar(50),
                codigo_cliente  varchar(50),
                idcliente_cartera int,
                idcartera int,
                idcuenta int,
                idtelefono int
                )  ";

            $prsqlCreateTableTmpIVR = $connection->prepare($sqlCreateTableTmpIVR);
            if ($prsqlCreateTableTmpIVR->execute()) {

                /*                 * ************ */
                $sqlLoad = "";
                if( $separator == 'tab' ) {
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/ivr/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_ivr_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                } else{
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/ivr/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_ivr_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }


                $prLoad = $connection->prepare($sqlLoad);

                if ($prLoad->execute()) {

                    /* CARGA A TEMPORAL */

                    //$connection->beginTransaction();

                    $sqlUpdate1tmp = "update tmp_ivr_" . $time . "
                        set telefono = if(length(telefono)=7,concat('1',telefono),telefono)";
                    $prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
                    if ($prUpdate1tmp->execute()) {

                        $sqlUpdate2tmp = "update tmp_ivr_" . $time . "
                            set Fecha_hora = concat_ws(' ',fecha,hora) ";
                        $prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
                        if ($prUpdate2tmp->execute()) {

                            $sqlUpdate3tmp = "update tmp_ivr_" . $time . " i
                                set
                                i.idcartera=(select max(tel.idcartera) from ca_telefono tel inner join ca_cartera car on tel.idcartera=car.idcartera where numero=i.telefono and car.estado=1)";
                            /* $sqlUpdate3tmp="update tmp_ivr_".$time." i set i.idcartera=(select max(tel.idcartera) from ca_telefono tel inner join ca_cartera car on tel.idcartera=car.idcartera where numero=i.telefono and car.estado=1 and idcampania=".$idcampania.")"; */
                            $prUpdate3tmp = $connection->prepare($sqlUpdate3tmp);
                            if ($prUpdate3tmp->execute()) {

                                $sqlUpdate4tmp = "update ca_telefono tel inner join tmp_ivr_" . $time . " i
                                    on i.idcartera = tel.idcartera and i.telefono = tel.numero
                                    set
                                    i.codigo_cliente = tel.codigo_cliente ,
                                    i.idtelefono = tel.idtelefono,
                                    i.idcliente_cartera = tel.idcliente_cartera,
                                    i.idcuenta = tel.idcuenta
                                    where isnull(i.idcartera) = 0 ";
                                $prUpdate4tmp = $connection->prepare($sqlUpdate4tmp);
                                if ($prUpdate4tmp->execute()) {

                                    /*$sqlUpdate5tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i
                                        on i.codigo_cliente = clicar.codigo_cliente
                                        set i.idcliente_cartera = clicar.idcliente_cartera
                                        where clicar.idcartera =i.idcartera";*/

                                    /*$sqlUpdate5tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i
                                        on i.idcartera = clicar.idcartera and i.codigo_cliente = clicar.codigo_cliente
                                        set
                                        i.idcliente_cartera = clicar.idcliente_cartera ,
                                        i.idcliente = clicar.idcliente
                                        ";

                                    $prUpdate5tmp = $connection->prepare($sqlUpdate5tmp);
                                    if ($prUpdate5tmp->execute()) {*/

                                        /*$sqlUpdate6tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i
                                            on i.idcliente_cartera=clicar.idcliente_cartera
                                            set i.idcliente = clicar.idcliente";
                                        $prUpdate6tmp = $connection->prepare($sqlUpdate6tmp);
                                        if ($prUpdate6tmp->execute()) {*/

                                            /*$sqlUpdate7tmp = "update ca_cuenta cu inner join tmp_ivr_" . $time . " i
                                                on cu.codigo_cliente=i.codigo_cliente
                                                set i.numero_cuenta=cu.numero_cuenta
                                                where i.telefono = cu.telefono and  cu.idcliente_cartera=i.idcliente_cartera
                                                and cu.idcartera=i.idcartera";*/
                                            /*$sqlUpdate7tmp = "update ca_cuenta cu inner join tmp_ivr_" . $time . " i
                                                on i.idcartera = cu.idcartera and i.idcliente_cartera = cu.idcliente_cartera and i.telefono = cu.telefono
                                                set i.idcuenta = cu.idcuenta
                                                where isnull(i.idcartera)=0 and isnull(i.idcliente_cartera) = 0 and isnull(i.idtelefono) = 0
                                                ";
                                            $prUpdate7tmp = $connection->prepare($sqlUpdate7tmp);
                                            if ($prUpdate7tmp->execute()) {*/





                                                                                /******* DISTRIBUCION DE DATOS ********/

                                                                                /*$sqlInsertTransaccion = "insert into ca_transaccion (
                                                                                    idtipo_gestion,idcliente_cartera,idfinal,observacion,fecha_creacion,usuario_creacion,fecha,is_ivr,telefono)
                                                                                    select 2, idcliente_cartera, if(Estado='NO CONTESTADO',304,305),'RESULTADO IVR',fecha_creacion,1,Fecha_Hora,1,telefono
                                                                                    from tmp_ivr_" . $time . "
                                                                                    where idcliente_cartera is not null and codigo_cliente is not null";*/

                                                                                $sqlInsertIvr = " INSERT INTO ca_llamada
                                                                                        (
                                                                                        idcliente_cartera, idfinal, idtipo_gestion, idtelefono, idcuenta, fecha, observacion, tipo, fecha_creacion, usuario_creacion
                                                                                        )
                                                                                        SELECT idcliente_cartera, IF(Estado='NO CONTESTADO',$estado_no_contestado,$estado_contestado), 2, idtelefono, idcuenta, Fecha_Hora, 'RESULTADO IVR', 'IVR', NOW(), $usuario_creacion
                                                                                        FROM tmp_ivr_" . $time . "
                                                                                        WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(idtelefono) = 0 AND ISNULL(idcartera) = 0
                                                                                        ";
                                                                                $prInsertTransaccion = $connection->prepare($sqlInsertIvr);
                                                                                if ($prInsertTransaccion->execute()) {
                                                                                    return array('rst' => true, 'msg' => 'Datos IVR insertados correctamente');

                                                                                } else {
                                                                                    //$connection->rollBack();
                                                                                    return array('rst' => false, 'msg' => 'Error al insertar datos de Transaccion');
                                                                                    //echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos de Transaccion'));
                                                                                }


                                            /*} else {
                                                //$connection->rollBack();
                                                return array('rst' => true, 'msg' => 'Error en carga de datos temporales7');
                                                //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales7'));
                                            }*/
                                        /*} else {
                                            //$connection->rollBack();
                                            return array('rst' => true, 'msg' => 'Error en carga de datos temporales6');
                                            //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales6'));
                                        }*/
                                    /*} else {
                                        //$connection->rollBack();
                                        return array('rst' => true, 'msg' => 'Error en carga de datos temporales5');
                                        //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales5'));
                                    }*/
                                } else {
                                    //$connection->rollBack();
                                    return array('rst' => true, 'msg' => 'Error en carga de datos temporales4');
                                    //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales4'));
                                }
                            } else {
                                //$connection->rollBack();
                                return array('rst' => true, 'msg' => 'Error en carga de datos temporales3');
                                //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales3'));
                            }
                        } else {
                            //$connection->rollBack();
                            return array('rst' => true, 'msg' => 'Error en carga de datos temporales2');
                            //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales2'));
                        }
                    } else {
                        //$connection->rollBack();
                        return array('rst' => true, 'msg' => 'Error en carga de datos temporales1');
                        //echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales1'));
                    }



                    /* FIN CARGA A TEMPORAL */
                } else {
                    return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
                    //echo json_encode(array('rst'=>false,'msg'=>'Error al Cargar Datos a Temporal'));
                }

                /*                 * ********* */

                /* $sqlDropTableFinalTmpNocPredictivo=" DROP TABLE IF EXISTS tmp_noc_predictivo_".$time;
                  $prDropTableFinalTmpNocPredictivo=$connection->prepare($sqlDropTableFinalTmpNocPredictivo);
                  $prDropTableFinalTmpNocPredictivo->execute(); */
            } else {
                return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
                //echo json_encode(array('rst'=>false,'msg'=>'Error al Crear Tabla Temporal'));
            }
        } else {
            return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
            //echo json_encode(array('rst'=>false,'msg'=>'Error al Eliminar Tabla Temporal Anterior'));
        }
    }

    public function uploadCarteraRetiro_masivo($_post, $file) {

        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $formatoFechas = $_post['formatoFechas'];
        $carteras = $_post['Carteras'];

        //COMPROBACIOON RUTA COBRAST SQLLITE
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }
        $path = "../documents/retiro/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
        }


        $time = date("Y_m_d_H_i_s");

        /*         * ***************** */
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();


        $sqlDropTableTmpRetiro = "DROP TEMPORARY TABLE IF EXISTS tmp_retiro_" . $time;
        $prDropTableTmpRetiro = $connection->prepare($sqlDropTableTmpRetiro);
        if ($prDropTableTmpRetiro->execute()) {

            $sqlCreateTableTmpRetiro = "CREATE TEMPORARY TABLE tmp_retiro_" . $time . " (
                Inscripcion varchar(10),
                nombre_cartera varchar(50),
                Fecha_Ini_Ges    datetime,
                Fecha_Fin_ges datetime,
                Des_Agencia varchar(100),
                Fecha_Retiro datetime,
                Motivo_Retiro varchar(100),
                idcartera int,
                idcliente_cartera int ,
                idcuenta int
                ) ENGINE = InnoDB ";

            $prsqlCreateTableTmpRetiro = $connection->prepare($sqlCreateTableTmpRetiro);
            if ($prsqlCreateTableTmpRetiro->execute()) {

                /*                 * ************ */
                $sqlLoad = "";
                if( $separator == 'tab' ) {
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/retiro/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_retiro_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }else{
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/retiro/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_retiro_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }

                $prLoad = $connection->prepare($sqlLoad);

                if ($prLoad->execute()) {

                    /* CARGA A TEMPORAL */
                    
                    if( $carteras == '' ) {

                        $sqlUpdate1tmp = " UPDATE tmp_retiro_" . $time . " t
                                        SET
                                        idcartera = ( SELECT idcartera FROM ca_cartera WHERE estado = 1 AND nombre_cartera = t.nombre_cartera LIMIT 1 )";
                        $prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
                        if ($prUpdate1tmp->execute()) {
    
                            $sqlUpdate2tmp = " UPDATE tmp_retiro_" . $time . " t INNER JOIN ca_cuenta cu
                            ON cu.idcartera = t.idcartera AND cu.numero_cuenta = t.inscripcion
                            SET 
                            t.idcliente_cartera = cu.idcliente_cartera,
                            t.idcuenta = cu.idcuenta 
                            WHERE ISNULL(t.idcartera) = 0 ";
                            $prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
                            if ($prUpdate2tmp->execute()) {
    
                                /******************************DISTRIBUCION DE DATOS */
                                $sqlUpdtCliCar = " UPDATE ca_cliente_cartera clicar INNER JOIN tmp_retiro_" . $time . " t 
                                ON t.idcliente_cartera = clicar.idcliente_cartera 
                                SET clicar.retiro = 1
                                WHERE t.idcliente_cartera IS NOT NULL AND t.idcartera IS NOT NULL ";
                                $prUpdtCliCar = $connection->prepare($sqlUpdtCliCar);
                                if ($prUpdtCliCar->execute()) {
    
                                    $sqlUpdtCuenta = " UPDATE ca_cuenta cu INNER JOIN tmp_retiro_" . $time . " t 
                                    ON t.idcuenta = cu.idcuenta 
                                    SET
                                    cu.is_retiro = 1,
                                    cu.retirado = 1,
                                    cu.fecha_retiro = t.fecha_retiro,
                                    cu.motivo_retiro = t.motivo_retiro
                                    WHERE t.idcliente_cartera IS NOT NULL AND t.idcartera IS NOT NULL AND t.idcuenta IS NOT NULL ";
                                    $prUpdtCuenta = $connection->prepare($sqlUpdtCuenta);
                                    if ($prUpdtCuenta->execute()) {
                                        //$connection->commit();
                                        return array('rst' => true, 'msg' => ' CARGA DE RETIROS CORRECTA');
                                    } else {
                                        //$connection->rollBack();
                                        return array('rst' => false, 'msg' => 'Error al Actualizar tabla Cuenta');
                                    }
                                } else {
    
                                    return array('rst' => false, 'msg' => 'Error al Actualizar CliCar');
                                }
                                /*                             * **************************** */
                            } else {
    
                                return array('rst' => false, 'msg' => 'Error en carga de datos temporales2');
                            }
                        } else {
    
                            return array('rst' => false, 'msg' => 'Error en carga de datos temporales1');
                        }
                    
                    }else{
                        
                        $sqlUpdate2tmp = " UPDATE tmp_retiro_" . $time . " t INNER JOIN ca_cuenta cu
                            ON cu.numero_cuenta = t.inscripcion 
                            SET 
                            t.idcliente_cartera = cu.idcliente_cartera, 

                            t.idcuenta = cu.idcuenta 
                            WHERE cu.idcartera IN ( ".$carteras." ) ";
                            
                        
                        $prUpdate2tmp = $connection->prepare( $sqlUpdate2tmp );
                        if( $prUpdate2tmp->execute() ) {
                        
                            $sqlUpdtCliCar = " UPDATE ca_cliente_cartera clicar INNER JOIN tmp_retiro_" . $time . " t 
                                ON t.idcliente_cartera = clicar.idcliente_cartera 
                                SET clicar.retiro = 1
                                WHERE t.idcliente_cartera IS NOT NULL AND t.idcuenta IS NOT NULL ";
                            $prUpdtCliCar = $connection->prepare($sqlUpdtCliCar);
                            if ($prUpdtCliCar->execute()) {

                                    $sqlUpdtCuenta = " UPDATE ca_cuenta cu INNER JOIN tmp_retiro_" . $time . " t 
                                    ON t.idcuenta = cu.idcuenta 
                                    SET
                                    cu.is_retiro = 1,
                                    cu.retirado = 1,
                                    cu.fecha_retiro = t.fecha_retiro,
                                    cu.motivo_retiro = t.motivo_retiro
                                    WHERE t.idcliente_cartera IS NOT NULL AND t.idcuenta IS NOT NULL AND cu.idcartera IN ( ".$carteras." ) ";
                                    $prUpdtCuenta = $connection->prepare($sqlUpdtCuenta);
                                    if ($prUpdtCuenta->execute()) {
                                        
                                        return array('rst' => true, 'msg' => ' CARGA DE RETIROS CORRECTA');
                                    } else {
                                        
                                        return array('rst' => false, 'msg' => 'Error al Actualizar tabla Cuenta');
                                    }
                            } else {
                                return array('rst' => false, 'msg' => 'Error al Actualizar CliCar');
                            }
                        
                        }else{
                            return array('rst' => false, 'msg' => 'Error actualizar datos de temporales');
                        }
                        
                        
                    }

                    /* FIN CARGA A TEMPORAL */
                } else {
                    return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
                    
                }

            } else {
                return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
            }
        } else {
            return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
        }
    }

    public function uploadAddCartera($_post) {

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        $codigo_cliente = $_post["codigo_cliente"];
        $numero_cuenta = $_post["numero_cuenta"];
        $codigo_operacion = $_post["codigo_operacion"];
        $usuario_creacion = $_post["usuario_creacion"];
        $nombre_cartera = $_POST["NombreCartera"];
        $moneda_cuenta = "";
        $moneda_operacion = "";

        if ($_post["moneda_cuenta"] != '-Seleccione-') {
            $moneda_cuenta = $_POST["moneda_cuenta"];
        } else {
            $moneda_cuenta = NULL;
        }
        if ($_post["moneda_operacion"] != '-Seleccione-') {
            $moneda_operacion = $_POST["moneda_operacion"];
        } else {
            $moneda_operacion = NULL;
        }

        $id_cartera = $_post['Cartera'];


        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }


        $time = date("Y_m_d_H_i_s");
        //$archivoParser = file($path);
        $archivoParser = @fopen($path, "r+");
        //$columMap = explode($_post['separator'],$archivoParser[0]);
        $columMap = explode($_post['separator'], fgets($archivoParser));
        /*         * ****** */
        fclose($archivoParser);
        /*         * ******* */

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%");
                //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
                //$item="`".$item."` VARCHAR(200) ";
            }

            return $item;
        }

        $colum = array_map("map_header", $columMap);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
        array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
        if ($_POST["codigo_operacion"] != '-Seleccione-') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
        } else {
            $codigo_operacion = '';
        }
        if ($_POST["moneda_cuenta"] != '-Seleccione-') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
        } else {
            $moneda_cuenta = '';
        }
        if ($_POST["moneda_operacion"] != '-Seleccione-') {
            array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
        } else {
            $moneda_operacion = '';
        }
        /*         * ********** */

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        $parserCliente = str_replace("\\", "", $_post["data_cliente"]);
        $parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
        $parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
        $parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
        $parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
        $parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

        $parserHeader = implode(",", $colum);

        $jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
        $jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
        $jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
        $jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
        $jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
        $jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
        $prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
        if ($prDropTableTMPCartera->execute()) {

            $sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
            $prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
            if ($prsqlCreateTabelTMPCartera->execute()) {

                $sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
                 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                $prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
                if ($prLoadDataInFileUC->execute()) {

                    //$connection->beginTransaction();
                    //$id_cartera=$connection->lastInsertId();

                    $insertCliente = " ";

                    $campoTableClienteTMP = array();
                    $campoTableCliente = array();

                    for ($i = 0; $i < count($jsonCliente); $i++) {
                        if ($jsonCliente[$i]['campoT'] == 'codigo') {
                            array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                            array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
                        } else {
                            array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
                            array_push($campoTableClienteTMP, $jsonCliente[$i]['dato']);
                        }
                    }

                    $insertCliente = " INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " )
                        SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 GROUP BY TRIM($codigo_cliente) ";

                    $prInsertCliente = $connection->prepare($insertCliente);
                    if ($prInsertCliente->execute()) {

                        $InsertClienteCartera = " INSERT IGNORE INTO ca_cliente_cartera ( codigo_cliente,idcartera,usuario_creacion,fecha_creacion )
                            SELECT TRIM($codigo_cliente)," . $id_cartera . "," . $usuario_creacion . ",NOW() FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 GROUP BY TRIM($codigo_cliente) ";

                        $prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
                        if ($prInsertClienteCartera->execute()) {

                            $campoTableCuentaTMP = array();
                            $campoTableCuenta = array();


                            for ($i = 0; $i < count($jsonCuenta); $i++) {
                                if ($jsonCuenta[$i]['campoT'] == 'total_deuda') {
                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                    array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                } else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                    array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                } else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                    array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                } else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                    array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
                                } else {
                                    array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
                                    array_push($campoTableCuentaTMP, $jsonCuenta[$i]['dato']);
                                }
                            }

                            $insertCuenta = "";
                            if ($_post['moneda_cuenta'] != '-Seleccione-') {
                                $insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )
                                    SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . "
                                    FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0
                                    GROUP BY TRIM($numero_cuenta), TRIM($moneda_cuenta) ";
                            } else {
                                $insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )
                                    SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . "
                                    FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0
                                    GROUP BY TRIM($numero_cuenta) ";
                            }

                            $prInsertCuenta = $connection->prepare($insertCuenta);
                            if ($prInsertCuenta->execute()) {

                                if (count($jsonOperacion) > 0) {
                                    $campoTableOperacionTMP = array();
                                    $campoTableOperacion = array();

                                    /*                                     * *** */
                                    $fieldTramo = "";
                                    /*                                     * ** */

                                    for ($i = 0; $i < count($jsonOperacion); $i++) {

                                        if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                        } else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                        } else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
                                            $fieldTramo = $jsonOperacion[$i]['dato'];
                                        } else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento') {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
                                        } else if ($jsonOperacion[$i]['campoT'] == 'fecha_asignacion') {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
                                        } else {
                                            array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
                                            array_push($campoTableOperacionTMP, $jsonOperacion[$i]['dato']);
                                        }
                                    }

                                    $insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion )
                                        SELECT " . implode(",", $campoTableOperacionTMP) . ", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW()
                                        FROM tmpcartera_" . session_id() . "_" . $time . "
                                        WHERE LENGTH( TRIM( $codigo_cliente ) ) > 0 AND LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0
                                        GROUP BY TRIM( $codigo_operacion ) ";

                                    $prInsertOperacion = $connection->prepare($insertOperacion);
                                    if ($prInsertOperacion->execute()) {

                                        /*                                         * ********* */
                                        if (trim($fieldTramo) != "") {
                                            $InsertTramo = " INSERT IGNORE INTO ca_tramo ( tramo, fecha_creacion, usuario_creacion, idservicio, tipo )
                                                SELECT DISTINCT( TRIM($fieldTramo) ),NOW(),$usuario_creacion , " . $_post['Servicio'] . " ,'TRAMO'
                                                FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $fieldTramo ) ) > 0 ";
                                            $prInsertTramo = $connection->prepare($InsertTramo);
                                            if ($prInsertTramo->execute()) {

                                            } else {
                                                //$connection->rollBack();
                                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar tramos'));
                                                exit();
                                            }
                                        }
                                        /*                                         * ************ */

                                        $referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);

                                        foreach ($jsonTelefono as $index => $value) {
                                            $fieldTelefono = array();
                                            $fieldTelefonoTMP = array();
                                            $fieldReferenciaTelefono = "";
                                            if (count($value) > 0) {

                                                foreach ($value as $i => $v) {
                                                    array_push($fieldTelefono, $i);
                                                    array_push($fieldTelefonoTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    if ($i == "numero") {
                                                        $fieldReferenciaTelefono = $v;
                                                    }
                                                }

                                                $insertTelefono = "";

                                                if (trim($fieldReferenciaTelefono) == '') {

                                                    $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                            SELECT DISTINCT TRIM($codigo_cliente), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                            FROM tmpcartera_" . session_id() . "_" . $time . " ;";
                                                } else {

                                                    $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                            SELECT TRIM( $codigo_cliente ), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                            FROM tmpcartera_" . session_id() . "_" . $time . "
                                                            WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 GROUP BY TRIM($fieldReferenciaTelefono) ;";
                                                }
                                                $prInsertTelefono = $connection->prepare($insertTelefono);
                                                if ($prInsertTelefono->execute()) {

                                                } else {
                                                    //$connection->rollBack();
                                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                    @$prsqlDropTableTMPCarteraRollBack->execute();
                                                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar telefono'));
                                                    exit();
                                                }
                                            }
                                        }

                                        $referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);

                                        foreach ($jsonDireccion as $index => $value) {
                                            $fieldDireccion = array();
                                            $fieldDireccionTMP = array();
                                            $fieldDireccionTMPIntersec = array();
                                            $fieldReferenciaDireccion = "";
                                            $fieldUbigeo = "";
                                            $FieldDepartamentoTMP = "";
                                            $FieldProvinciaTMP = "";
                                            $FieldDistritoTMP = "";
                                            if (count($value) > 0) {

                                                foreach ($value as $i => $v) {

                                                    if ($i == "direccion") {
                                                        $fieldReferenciaDireccion = $v;
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    } else if ($i == "ubigeo") {
                                                        $fieldUbigeo = $v;
                                                        $FieldDepartamentoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',1 ) ";
                                                        $FieldDistritoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',-1 ) ";
                                                        $FieldProvinciaTMP = " SUBSTRING_INDEX( SUBSTRING_INDEX(tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',2),'-',-1) ";
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                        array_push($fieldDireccion, "departamento");
                                                        array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
                                                        array_push($fieldDireccion, "provincia");
                                                        array_push($fieldDireccionTMP, $FieldProvinciaTMP);
                                                        array_push($fieldDireccion, "distrito");
                                                        array_push($fieldDireccionTMP, $FieldDistritoTMP);
                                                    } else if ($i == "departamento") {
                                                        if (!array_search("departamento", $fieldDireccion)) {
                                                            array_push($fieldDireccion, $i);
                                                            array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                        }
                                                    } else if ($i == "provincia") {
                                                        if (!array_search("provincia", $fieldDireccion)) {
                                                            array_push($fieldDireccion, $i);
                                                            array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                        }
                                                    } else if ($i == "distrito") {
                                                        if (!array_search("distrito", $fieldDireccion)) {
                                                            array_push($fieldDireccion, $i);
                                                            array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                        }
                                                    } else {
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    }
                                                }

                                                $insertDireccion = "";

                                                if (trim($fieldReferenciaDireccion) == '') {

                                                    $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " )
                                                            SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
                                                            FROM tmpcartera_" . session_id() . "_" . $time . " ;";
                                                } else {

                                                    $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen ,idtipo_referencia , usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " )
                                                            SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1, " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
                                                            FROM tmpcartera_" . session_id() . "_" . $time . "
                                                            WHERE TRIM( $fieldReferenciaDireccion )!='' ;";
                                                }

                                                $prInsertDireccion = $connection->prepare($insertDireccion);
                                                if ($prInsertDireccion->execute()) {

                                                } else {
                                                    //$connection->rollBack();

                                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar direccion'));
                                                    exit();
                                                }
                                            }
                                        }

                                        $tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);

                                        $idDatosAdicionales = array();
                                        $idTMPDatosAdicionales = array();
                                        $groupTMPDatosAdicionales = array();
                                        if ($_post['moneda_cuenta'] != '-Seleccione-') {
                                            $idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta, moneda", "ca_datos_adicionales_detalle_cuenta" => "codigo_cliente, codigo_operacion");
                                            $idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
                                            $groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
                                        } else {
                                            $idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta ", "ca_datos_adicionales_detalle_cuenta" => " codigo_cliente, codigo_operacion ");
                                            $idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
                                            $groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
                                        }

                                        foreach ($jsonAdicionales as $index => $value) {
                                            $fieldCabecera = array();
                                            $fieldCabeceraTMP = array();
                                            $fieldValueTMP = array();

                                            if (count($value) > 0) {

                                                for ($i = 0; $i < count($value); $i++) {
                                                    array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
                                                    array_push($fieldCabecera, $value[$i]['campoT']);
                                                    array_push($fieldCabeceraTMP, $value[$i]['dato']);
                                                }

                                                $insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " )
                                                        VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
                                                $prInsertCabeceras = $connection->prepare($insertCabeceras);
                                                if ($prInsertCabeceras->execute()) {

                                                    $insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " )
                                                            SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
                                                            FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $groupTMPDatosAdicionales[$index] . "";

                                                    $prInsertAdicionales = $connection->prepare($insertAdicionales);
                                                    if ($prInsertAdicionales->execute()) {

                                                    } else {
                                                        //$connection->rollBack();

                                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                        @$prsqlDropTableTMPCarteraRollBack->execute();

                                                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
                                                        exit();
                                                    }
                                                } else {
                                                    //$connection->rollBack();

                                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
                                                    exit();
                                                }
                                            }
                                        }

                                        //$connection->commit();
                                        echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
                                    } else {
                                        //$connection->rollBack();

                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                        @$prsqlDropTableTMPCarteraRollBack->execute();

                                        echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
                                    }
                                } else {

                                    $referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);

                                    foreach ($jsonTelefono as $index => $value) {
                                        $fieldTelefono = array();
                                        $fieldTelefonoTMP = array();
                                        $fieldReferenciaTelefono = "";
                                        if (count($value) > 0) {

                                            foreach ($value as $i => $v) {
                                                array_push($fieldTelefono, $i);
                                                array_push($fieldTelefonoTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                if ($i == "numero") {
                                                    $fieldReferenciaTelefono = $v;
                                                }
                                            }

                                            $insertTelefono = "";

                                            if (trim($fieldReferenciaTelefono) == '') {


                                                $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                    SELECT DISTINCT TRIM($codigo_cliente), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                    FROM tmpcartera_" . session_id() . "_" . $time . " ;";
                                            } else {

                                                $insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " )
                                                    SELECT TRIM( $codigo_cliente ), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
                                                    FROM tmpcartera_" . session_id() . "_" . $time . "
                                                    WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 GROUP BY TRIM($fieldReferenciaTelefono) ;";
                                            }
                                            $prInsertTelefono = $connection->prepare($insertTelefono);
                                            if ($prInsertTelefono->execute()) {

                                            } else {
                                                //$connection->rollBack();
                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                @$prsqlDropTableTMPCarteraRollBack->execute();
                                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar telefono'));
                                                exit();
                                            }
                                        }
                                    }

                                    $referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);

                                    foreach ($jsonDireccion as $index => $value) {
                                        $fieldDireccion = array();
                                        $fieldDireccionTMP = array();
                                        $fieldDireccionTMPIntersec = array();
                                        $fieldReferenciaDireccion = "";
                                        $fieldUbigeo = "";
                                        $FieldDepartamentoTMP = "";
                                        $FieldProvinciaTMP = "";
                                        $FieldDistritoTMP = "";
                                        if (count($value) > 0) {

                                            foreach ($value as $i => $v) {

                                                if ($i == "direccion") {
                                                    $fieldReferenciaDireccion = $v;
                                                    array_push($fieldDireccion, $i);
                                                    array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                } else if ($i == "ubigeo") {
                                                    $fieldUbigeo = $v;
                                                    $FieldDepartamentoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',1 ) ";
                                                    $FieldDistritoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',-1 ) ";
                                                    $FieldProvinciaTMP = " SUBSTRING_INDEX( SUBSTRING_INDEX(tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',2),'-',-1) ";
                                                    array_push($fieldDireccion, $i);
                                                    array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    array_push($fieldDireccion, "departamento");
                                                    array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
                                                    array_push($fieldDireccion, "provincia");
                                                    array_push($fieldDireccionTMP, $FieldProvinciaTMP);
                                                    array_push($fieldDireccion, "distrito");
                                                    array_push($fieldDireccionTMP, $FieldDistritoTMP);
                                                } else if ($i == "departamento") {
                                                    if (!array_search("departamento", $fieldDireccion)) {
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    }
                                                } else if ($i == "provincia") {
                                                    if (!array_search("provincia", $fieldDireccion)) {
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    }
                                                } else if ($i == "distrito") {
                                                    if (!array_search("distrito", $fieldDireccion)) {
                                                        array_push($fieldDireccion, $i);
                                                        array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                    }
                                                } else {
                                                    array_push($fieldDireccion, $i);
                                                    array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
                                                }
                                            }

                                            $insertDireccion = "";

                                            if (trim($fieldReferenciaDireccion) == '') {

                                                $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " )
                                                    SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
                                                    FROM tmpcartera_" . session_id() . "_" . $time . " ;";
                                            } else {

                                                $insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen ,idtipo_referencia , usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " )
                                                    SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1, " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
                                                    FROM tmpcartera_" . session_id() . "_" . $time . "
                                                    WHERE TRIM( $fieldReferenciaDireccion )!='' ;";
                                            }

                                            $prInsertDireccion = $connection->prepare($insertDireccion);
                                            if ($prInsertDireccion->execute()) {

                                            } else {
                                                //$connection->rollBack();

                                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                @$prsqlDropTableTMPCarteraRollBack->execute();

                                                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar direccion'));
                                                exit();
                                            }
                                        }
                                    }

                                    $tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
                                    $idDatosAdicionales = array();
                                    $idTMPDatosAdicionales = array();
                                    $groupTMPDatosAdicionales = array();
                                    if ($_post['moneda_cuenta'] != '-Seleccione-') {
                                        $idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta, moneda", "ca_datos_adicionales_detalle_cuenta" => "codigo_cliente, codigo_operacion");
                                        $idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
                                        $groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
                                    } else {
                                        $idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta ", "ca_datos_adicionales_detalle_cuenta" => " codigo_cliente, codigo_operacion ");
                                        $idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
                                        $groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
                                    }
                                    /*                                     * ********** */

                                    foreach ($jsonAdicionales as $index => $value) {
                                        $fieldCabecera = array();
                                        $fieldCabeceraTMP = array();
                                        $fieldValueTMP = array();
                                        if ($index == 'ca_datos_adicionales_cliente' || $index == 'ca_datos_adicionales_cuenta') {
                                            if (count($value) > 0) {

                                                for ($i = 0; $i < count($value); $i++) {
                                                    array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
                                                    array_push($fieldCabecera, $value[$i]['campoT']);
                                                    array_push($fieldCabeceraTMP, $value[$i]['dato']);
                                                }

                                                $insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " )
                                                    VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
                                                $prInsertCabeceras = $connection->prepare($insertCabeceras);
                                                if ($prInsertCabeceras->execute()) {

                                                    $insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " )
                                                        SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
                                                        FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $groupTMPDatosAdicionales[$index] . "";

                                                    $prInsertAdicionales = $connection->prepare($insertAdicionales);
                                                    if ($prInsertAdicionales->execute()) {

                                                    } else {
                                                        //$connection->rollBack();

                                                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                        @$prsqlDropTableTMPCarteraRollBack->execute();

                                                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
                                                        exit();
                                                    }
                                                } else {
                                                    //$connection->rollBack();

                                                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                                    @$prsqlDropTableTMPCarteraRollBack->execute();

                                                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
                                                    exit();
                                                }
                                            }
                                        }
                                    }

                                    //$connection->commit();
                                    echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
                                }
                            } else {
                                //$connection->rollBack();

                                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                                @$prsqlDropTableTMPCarteraRollBack->execute();

                                echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
                            }
                        } else {
                            //$connection->rollBack();

                            $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                            @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                            @$prsqlDropTableTMPCarteraRollBack->execute();

                            echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
                            exit();
                        }
                    } else {
                        //$connection->rollBack();

                        $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                        @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                        @$prsqlDropTableTMPCarteraRollBack->execute();

                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
                    }
                } else {
                    $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                    @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                    @$prsqlDropTableTMPCarteraRollBack->execute();
                    echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
                }
            } else {
                $sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
                @$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
                @$prsqlDropTableTMPCarteraRollBack->execute();
                echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
        }
    }

    public function uploadCarteraRRLL($_post) {

        $cartera = $_post['Cartera'];
        $campania = $_post['Campania'];
        $servicio = $_post['Servicio'];

        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];

        $numero_cuenta = '';
        $moneda = '';
        $telefono = '';
        $contacto = '';

        $parserRRLL = str_replace("\\", "", $_post['data_rrll']);
        $jsonRRLL = json_decode($parserRRLL, true);

        for ($i = 0; $i < count($jsonRRLL); $i++) {
            if ($jsonRRLL[$i]['campoT'] == 'numero_cuenta') {
                $numero_cuenta = $jsonRRLL[$i]['dato'];
            } else if ($jsonRRLL[$i]['campoT'] == 'moneda') {
                $moneda = $jsonRRLL[$i]['dato'];
            } else if ($jsonRRLL[$i]['campoT'] == 'telefono') {
                $telefono = $jsonRRLL[$i]['dato'];
            } else if ($jsonRRLL[$i]['campoT'] == 'contacto') {
                $contacto = $jsonRRLL[$i]['dato'];
            }
        }

        if (trim($numero_cuenta) == '') {
            echo json_encode(array('rst' => false, 'msg' => 'Seleccione numero de cuenta'));
            exit();
        }

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
            exit();
        }

        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
        if (!file_exists($path)) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time = date("Y_m_d_H_i_s");
        $archivo = @fopen($path, "r+");
        $colum = explode($separator, fgets($archivo));
        if (count($colum) < 2) {
            echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
            exit();
        }

        function map_header($n) {
            $item = "";
            if (trim(utf8_encode($n)) != "") {
                $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%", "'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

                $item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
            }

            return $item;
        }

        $colum = array_map("map_header", $colum);
        $parserHeader = implode(",", $colum);
        $columHeader = array();
        $countHeaderFalse = 0;

        for ($i = 0; $i < count($colum); $i++) {
            if ($colum[$i] != "") {
                array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
            } else {
                $countHeaderFalse++;
            }
        }

        if ($countHeaderFalse > 0) {
            echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
            exit();
        }

        fclose($archivo);

        /*         * ***************** */

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTable = " CREATE TEMPORARY TABLE tmprrll_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
        //echo $sqlCreateTable;
        $prCreateTable = $connection->prepare($sqlCreateTable);
        if ($prCreateTable->execute()) {

            $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
              INTO TABLE tmprrll_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            $prLoad = $connection->prepare($sqlLoad);
            if ($prLoad->execute()) {

                //$connection->beginTransaction();

                $insertCarteraReclamo = " INSERT INTO ca_cartera_rrll ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, rrll, cabeceras )
                    VALUES ( ?,NOW(),?,NOW(),?,? ) ";
                $prInsertCartera = $connection->prepare($insertCarteraReclamo);
                $prInsertCartera->bindParam(1, $cartera, PDO::PARAM_INT);
                $prInsertCartera->bindParam(2, $usuario_creacion, PDO::PARAM_INT);
                $prInsertCartera->bindParam(3, $parserRRLL, PDO::PARAM_STR);
                $prInsertCartera->bindParam(4, $parserHeader, PDO::PARAM_STR);
                if ($prInsertCartera->execute()) {
                    $sqlInsertReclamos = "";
                    if ($moneda == '' && $telefono == '') {
                        $sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE TRIM($numero_cuenta) = cu.numero_cuenta LIMIT 1 )
                            WHERE idcartera = ? ";
                    } else if ($moneda == '' && $telefono != '') {
                        $sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($telefono)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.telefono)) LIMIT 1 )
                            WHERE idcartera = ? ";
                    } else if ($moneda != '' && $telefono == '') {
                        $sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.moneda)) LIMIT 1 )
                            WHERE idcartera = ? ";
                    } else if ($moneda != '' && $telefono != '') {
                        $sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda),TRIM($telefono)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.moneda),TRIM(cu.telefono)) LIMIT 1 )
                            WHERE idcartera = ? ";
                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar reclamos'));
                    }
                    //echo $sqlInsertReclamos;
                    $prInsertReclamos = $connection->prepare($sqlInsertReclamos);
                    $prInsertReclamos->bindParam(1, $cartera, PDO::PARAM_INT);
                    if ($prInsertReclamos->execute()) {

                        //$connection->commit();
                        echo json_encode(array('rst' => true, 'msg' => 'Datos de cartera insertados correctamente'));
                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => false, 'msg' => 'Error al insertar reclamos'));
                    }
                } else {
                    //$connection->rollBack();
                    echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
                }
            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
        }
    }

    public function uploadDistribucionMecanico ( $_post ) {

        $cartera = $_post['idcartera'];
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separador = $_post['separador'];
        $operadores = json_decode(str_replace("\\","",$_post['operadores']),true);
        $modo = $_post['modo'];

        $codigo_cliente = '';
        $codigo_usuario = '';

        $parserDis = str_replace("\\","",$_post['data_generate']);
        $jsonDis = json_decode($parserDis,true);

        for( $i=0;$i<count($jsonDis);$i++ ) {
            if( $jsonDis[$i]['campoT'] == 'codigo_cliente' ) {
                $codigo_cliente = $jsonDis[$i]['dato'];
            }else if( $jsonDis[$i]['campoT'] == 'codigo' ) {
                $codigo_usuario = $jsonDis[$i]['dato'];
            }
        }

        if( trim($codigo_cliente) == '' ) {
            echo json_encode(array('rst'=>false,'msg'=>'Seleccione codigo de cliente'));
            exit();
        }

        $confCobrast=parse_ini_file('../conf/cobrast.ini',true);

        if( !isset($confCobrast['ruta_cobrast']) ){
            echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
            exit();
        }else if( !isset($confCobrast['ruta_cobrast']['document_root_cobrast']) ) {
            echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
            exit();
        }else if( !isset($confCobrast['ruta_cobrast']['nombre_carpeta']) ) {
            echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
            exit();
        }

        $path="../documents/carteras/".$_post["NombreServicio"]."/".$_post["file"];
        if( !file_exists($path) ) {
            echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
            exit();
        }

        $time=date("Y_m_d_H_i_s");
        $archivo = @fopen($path,"r+");
        $colum = array();
        if( $separador == 'tab' ) {
            $colum = explode("\t",fgets($archivo));
        }else{
            $colum = explode($separador,fgets($archivo));
        }

        if( count( $colum )<2 ) {
            echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto'));
            exit();
        }

        function MapArrayCodigoClienteDis ( $n ) {
            return "'".$n['codigo_cliente']."'";
        };

        function map_header( $n ) {
            $item="";
            if( trim(utf8_encode($n))!="" ){
                $buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","'",'"',"?","Â¿","!","Â¡","[","]","-");
                $cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");

                $item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
            }

            return $item;
        }
        $colum = array_map("map_header",$colum);
        $parserHeader = implode(",",$colum);
        $columHeader=array();
        $countHeaderFalse=0;

        for( $i=0;$i<count($colum);$i++ ) {
            if( $colum[$i]!="" ) {
                array_push($columHeader,"`".$colum[$i]."` VARCHAR(200) ");
            }else{
                $countHeaderFalse++;
            }
        }

        /********/
        array_push($columHeader," idusuario_servicio INT ");
        /**********/
        array_push($columHeader," INDEX( ".$codigo_cliente." ASC ) ");

        if( $countHeaderFalse>0 ) {
            echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
            exit();
        }

        fclose($archivo);

        /********************/

        $factoryConnection= FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlCreateTable=" CREATE TEMPORARY TABLE tmpdis_".session_id()."_".$time." ( ".implode(",",$columHeader)." ) ";
        //echo $sqlCreateTable;
        $prCreateTable = $connection->prepare($sqlCreateTable);
        if( $prCreateTable->execute() ) {

            $sqlLoad="";
            if( $separador == 'tab' ) {
                $sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
                INTO TABLE tmpdis_".session_id()."_".$time." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            }else{
                $sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
                INTO TABLE tmpdis_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            }

            $prLoad = $connection->prepare($sqlLoad);
            if( $prLoad->execute() ) {

                $connection->beginTransaction();



                if( count($operadores) > 0 ) {

                    $sqlCountTmp = "  SELECT COUNT(*) AS 'COUNT' FROM tmpdis_".session_id()."_".$time." WHERE TRIM(".$codigo_cliente.") != '' ";
                    $prCountTmp = $connection->prepare($sqlCountTmp);
                    $prCountTmp->execute();
                    $dataCount = $prCountTmp->fetchAll(PDO::FETCH_ASSOC);
                    $countTMP = (int)$dataCount[0]['COUNT'];

                    $cantidadClientes = ceil( $countTMP / count($operadores) );

                    if( $modo == 'cartera' ) {


                        $inicio = 0;
                        for( $i=0;$i<count($operadores);$i++ ) {



                            $sqlLimitCliente = " SELECT ".$codigo_cliente." AS 'codigo_cliente' FROM tmpdis_".session_id()."_".$time." LIMIT ".$inicio.", ".$cantidadClientes."  ";
                            $prLimitCliente = $connection->prepare($sqlLimitCliente);
                            $prLimitCliente->execute();
                            $dataLimitCliente = $prLimitCliente->fetchAll(PDO::FETCH_ASSOC);

                            $clientesDis = array_map("MapArrayCodigoClienteDis",$dataLimitCliente);

                            if( count($clientesDis) > 0 ) {
                                $sqlDistribucion = " UPDATE ca_cliente_cartera
                                SET idusuario_servicio = ".$operadores[$i]['operador']."
                                WHERE idcartera = ".$cartera." AND idusuario_servicio = 0 AND codigo_cliente IN ( ".implode(",",$clientesDis)." ) ";

                                $prUpdateClienteCartera = $connection->prepare($sqlDistribucion);
                                if( $prUpdateClienteCartera->execute() ){

                                }else{
                                    $connection->rollBack();
                                    echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar distribucion'));
                                    exit();
                                }
                            }
                            $inicio = $inicio + $cantidadClientes ;
                        }

                    }else{

                        $sqlClearTmp = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = 0 WHERE idcartera = ".$cartera." ";
                        $prClearTmp = $connection->prepare($sqlClearTmp);
                        if( $prClearTmp->execute() ) {

                        }else{
                            $connection->rollBack();
                            echo json_encode(array('rst'=>false,'msg'=>'Error al limpiar temporal'));
                            exit;
                        }

                        $inicio = 0;
                        for( $i=0;$i<count($operadores);$i++ ) {

                            $sqlLimitCliente = " SELECT ".$codigo_cliente." AS 'codigo_cliente' FROM tmpdis_".session_id()."_".$time." LIMIT ".$inicio.", ".$cantidadClientes."  ";
                            $prLimitCliente = $connection->prepare($sqlLimitCliente);
                            $prLimitCliente->execute();

                            $dataLimitCliente = $prLimitCliente->fetchAll(PDO::FETCH_ASSOC);

                            $clientesDis = array_map("MapArrayCodigoClienteDis",$dataLimitCliente);

                            if( count($clientesDis) > 0 ) {
                                $sqlDistribucion = " UPDATE ca_cliente_cartera
                                SET idusuario_servicio_especial = ".$operadores[$i]['operador']."
                                WHERE idcartera = ".$cartera." AND codigo_cliente IN ( ".implode(",",$clientesDis)." ) ";

                                $prUpdateClienteCartera = $connection->prepare($sqlDistribucion);
                                if( $prUpdateClienteCartera->execute() ){

                                }else{
                                    $connection->rollBack();
                                    echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar distribucion'));
                                    exit();
                                }
                            }
                            $inicio = $inicio + $cantidadClientes ;
                        }

                    }

                }else{

                    $sqlUpdateTmpIdUsuarioServicio = " UPDATE tmpdis_".session_id()."_".$time." tmp
                    SET idusuario_servicio = ( SELECT ususer.idusuario_servicio FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idservicio = $servicio AND usu.codigo = TRIM(tmp.$codigo_usuario) AND ususer.estado = 1 AND usu.estado = 1 LIMIT 1 )
                    WHERE TRIM(tmp.$codigo_usuario) != '' ";

                    $prUpdateTmpIdUsuarioServicio = $connection->prepare($sqlUpdateTmpIdUsuarioServicio);
                    if( $prUpdateTmpIdUsuarioServicio->execute() ) {

                        $sqlDistribucion = "";
                        if( $modo == 'cartera' ) {

                            $sqlDistribucion = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpdis_".session_id()."_".$time." tmp
                            ON tmp.".$codigo_cliente." = clicar.codigo_cliente
                            SET clicar.idusuario_servicio = tmp.idusuario_servicio
                            WHERE clicar.idcartera = ".$cartera." ";

                            $prDistribucion = $connection->prepare($sqlDistribucion);
                            if( $prDistribucion->execute() ){

                            }else{
                                $connection->rollBack();
                                echo json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
                                exit();
                            }

                        }else{

                            $sqlClearTmp = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = 0 WHERE idcartera = ".$cartera." ";

                            $prClearTmp = $connection->prepare($sqlClearTmp);
                            if( $prClearTmp->execute() ) {

                            }else{
                                $connection->rollBack();
                                echo json_encode(array('rst'=>false,'msg'=>'Error al limpiar temporal'));
                                exit();
                            }

                            $sqlDistribucion = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpdis_".session_id()."_".$time." tmp
                            ON tmp.".$codigo_cliente." = clicar.codigo_cliente
                            SET clicar.idusuario_servicio_especial = tmp.idusuario_servicio
                            WHERE clicar.idcartera = ".$cartera."  ";

                            $prDistribucion = $connection->prepare($sqlDistribucion);
                            if( $prDistribucion->execute() ){

                            }else{
                                $connection->rollBack();
                                echo json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
                                exit();
                            }

                        }
                    }else{
                        $connection->rollBack();
                        echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar tabla temporal'));
                        exit();
                    }

                }

                $connection->commit();
                /*ACTUALIZANDO NUMEROS NULLOS COMO IDUSUARIO_SERVICIO CERO*/
                $sqlActualizarNullos="UPDATE ca_cliente_cartera
                                      SET idusuario_servicio=0
                                      WHERE idcartera= ".$cartera." AND idusuario_servicio IS NULL ";
                $practualizanullos=$connection->prepare($sqlActualizarNullos);
                if( $practualizanullos->execute() ){

                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar los NULLOS'));
                    exit();
                }                
                /************************************/
                echo json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente'));

            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos a tabla temporal'));
            }

        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error al crear tabla temporal'));
        }

    }

    public function uploadCarteraCourier_masivo ( $_post, $file ) {

        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $formatoFechas = $_post['formatoFechas'];
        $tipo = $_post['Tipo'];
        //$idcampania=$_post['idcampania'];

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        }
        $path = "../documents/currier_visitas/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');

        }

        $time = date("Y_m_d_H_i_s");

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();


        $sqlDropTableTmpIVR = " DROP TABLE IF EXISTS tmp_courier_" . $time;
        $prDropTableTmpIVR = $connection->prepare($sqlDropTableTmpIVR);
        if ($prDropTableTmpIVR->execute()) {

            $sqlCreateTableTmpIVR = " CREATE TABLE tmp_courier_" . $time . " (
                TIPO_VISITA VARCHAR(50),
                CONTRATO VARCHAR(50),
                NRO_CUOTA VARCHAR(50),
                CODIGO_GESTOR VARCHAR(30),
                CODIGO_ESTADO VARCHAR(20),
                FECHA_VISITA VARCHAR(20),
                FECHA_RECEPCION VARCHAR(20),
                FECHA_CP VARCHAR(20),
                MONTO_CP VARCHAR(15),
                MONEDA_CP VARCHAR(50),
                OBSERVACION TEXT,
                DESCRIPCION_INMUEBLE TEXT,
                DIRECCION VARCHAR(200),
                DEPARTAMENTO VARCHAR(80),
                PROVINCIA VARCHAR(80),
                DISTRITO VARCHAR(100),
                idcartera INT,
                idcliente_cartera  INT,
                idcuenta INT,
                idnotificador INT,
                idfinal INT,
                iddireccion INT
                )  ";

            $prsqlCreateTableTmpIVR = $connection->prepare($sqlCreateTableTmpIVR);
            if ($prsqlCreateTableTmpIVR->execute()) {

                $sqlLoad = "";
                if( $separator == 'tab' ) {
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/currier_visitas/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_courier_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                } else{
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/currier_visitas/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_courier_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }

                $prLoad = $connection->prepare($sqlLoad);
                if( $prLoad->execute() ) {

                    $sqlUpdateEstado = "    UPDATE 
                                            tmp_courier_".$time." tmp 
                                            INNER JOIN ca_final_servicio finser ON finser.idfinal = TRIM(tmp.CODIGO_ESTADO)
                                            SET 
                                            tmp.idfinal = finser.idfinal
                                            WHERE 
                                            finser.estado = 1 AND finser.idservicio = ".$servicio." ";

                    $prUpdateEstado = $connection->prepare( $sqlUpdateEstado );
                    if( $prUpdateEstado->execute() ) {

                        $sqlUpdateGestor = "    UPDATE 
                                                tmp_courier_".$time." tmp 
                                                INNER JOIN ca_usuario usu INNER JOIN ca_usuario_servicio ususer
                                                ON ususer.idusuario = usu.idusuario AND usu.codigo = TRIM(tmp.CODIGO_GESTOR)
                                                SET tmp.idnotificador = ususer.idusuario_servicio
                                                WHERE ususer.estado = 1 AND ususer.idservicio = ".$servicio." ";

                        $prUpdateGestor = $connection->prepare( $sqlUpdateGestor );
                        if( $prUpdateGestor->execute() ) {

                            $sqlUpdateCartera = " UPDATE tmp_courier_".$time." tmp
                            SET tmp.idcartera = ( SELECT MAX(cu.idcartera) FROM ca_cuenta cu inner join ca_cartera car on car.idcartera=cu.idcartera WHERE car.estado=1 and cu.negocio = TRIM(tmp.CONTRATO) )";

                            $prUpdateCartera = $connection->prepare( $sqlUpdateCartera );
                            if( $prUpdateCartera->execute() ) {

                                $sqlUpdateIdCuentaIdCC = "      UPDATE 
                                                                tmp_courier_".$time." tmp 
                                                                INNER JOIN ca_cuenta cu
                                                                ON cu.idcartera = tmp.idcartera AND cu.negocio = TRIM(tmp.CONTRATO) AND cu.tramo_cuenta=tmp.NRO_CUOTA
                                                                SET
                                                                tmp.idcliente_cartera = cu.idcliente_cartera ,
                                                                tmp.idcuenta = cu.idcuenta
                                                                WHERE ISNULL( tmp.idcartera ) = 0 ";

                                $prUpdateIdCuentaIdCC = $connection->prepare( $sqlUpdateIdCuentaIdCC );
                                if( $prUpdateIdCuentaIdCC->execute() ) {

                                    $sqlUpdateDireccion = " UPDATE 
                                                            tmp_courier_".$time." tmp 
                                                            -- INNER JOIN ca_direccion dir ON dir.idcartera = tmp.idcartera AND dir.idcuenta = tmp.idcuenta
                                                            SET tmp.iddireccion = (SELECT dir.iddireccion FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=dir.codigo_cliente WHERE clicar.idcliente_cartera=tmp.idcliente_cartera LIMIT 1)
                                                            WHERE ISNULL(tmp.idcartera) = 0 AND ISNULL( tmp.idcuenta ) = 0 ";

                                    $prUpdateDireccion = $connection->prepare( $sqlUpdateDireccion );
                                    if( $prUpdateDireccion->execute() ) {

                                        $fecha_visita = "
                                                CASE
                                                WHEN LENGTH(TRIM( FECHA_VISITA )) = 8
                                                THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,5,2),'-',SUBSTRING( FECHA_VISITA ,7,2))
                                                WHEN LENGTH(TRIM( FECHA_VISITA )) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM( FECHA_VISITA ),'/') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_VISITA ),'/') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,6,2),'-',SUBSTRING( FECHA_VISITA ,9,2))
                                                    WHEN INSTR(TRIM( FECHA_VISITA ),'-') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_VISITA ),'.') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_VISITA ),'.') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,6,2),'-',SUBSTRING( FECHA_VISITA ,9,2))
                                                    ELSE TRIM( FECHA_VISITA )
                                                    END
                                                ELSE TRIM( FECHA_VISITA )
                                                END
                                                 ";

                                        $fecha_recepcion = "
                                                CASE
                                                WHEN LENGTH(TRIM( FECHA_RECEPCION )) = 8
                                                THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,5,2),'-',SUBSTRING( FECHA_RECEPCION ,7,2))
                                                WHEN LENGTH(TRIM( FECHA_RECEPCION )) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM( FECHA_RECEPCION ),'/') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_RECEPCION ),'/') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,6,2),'-',SUBSTRING( FECHA_RECEPCION ,9,2))
                                                    WHEN INSTR(TRIM( FECHA_RECEPCION ),'-') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_RECEPCION ),'.') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_RECEPCION ),'.') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,6,2),'-',SUBSTRING( FECHA_RECEPCION ,9,2))
                                                    ELSE TRIM( FECHA_RECEPCION )
                                                    END
                                                ELSE TRIM( FECHA_RECEPCION )
                                                END
                                                 ";

                                        $fecha_cp = "
                                                CASE
                                                WHEN LENGTH(TRIM( FECHA_CP )) = 8
                                                THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,5,2),'-',SUBSTRING( FECHA_CP ,7,2))
                                                WHEN LENGTH(TRIM( FECHA_CP )) = 10
                                                THEN
                                                    CASE
                                                    WHEN INSTR(TRIM( FECHA_CP ),'/') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_CP ),'/') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,6,2),'-',SUBSTRING( FECHA_CP ,9,2))
                                                    WHEN INSTR(TRIM( FECHA_CP ),'-') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_CP ),'.') = 3
                                                    THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
                                                    WHEN INSTR(TRIM( FECHA_CP ),'.') = 5
                                                    THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,6,2),'-',SUBSTRING( FECHA_CP ,9,2))
                                                    ELSE TRIM( FECHA_CP )
                                                    END
                                                ELSE TRIM( FECHA_CP )
                                                END
                                                 ";

                                        $sqlInsert = " INSERT INTO ca_visita
                                        (
                                        idcliente_cartera, idtipo_gestion, idfinal, iddireccion, idnotificador, idcuenta, fecha_cp,
                                        monto_cp, moneda_cp, fecha_visita, fecha_recepcion, observacion, descripcion_inmueble, tipo,
                                        fecha_creacion, usuario_creacion, tipo_visita
                                        )
                                        SELECT
                                        idcliente_cartera, 1, idfinal, iddireccion, idnotificador, idcuenta , IF( TRIM(FECHA_CP)='',NULL,( $fecha_cp ) ),
                                        IF(TRIM(MONTO_CP)='',NULL,TRIM(MONTO_CP)), IF(TRIM(MONEDA_CP)='',NULL,TRIM(MONEDA_CP)), ( $fecha_visita ), ( $fecha_recepcion ), OBSERVACION, DESCRIPCION_INMUEBLE, '$tipo',
                                        NOW(), $usuario_creacion, TIPO_VISITA
                                        FROM tmp_courier_".$time."
                                        WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(iddireccion) = 0 AND ISNULL(idfinal) = 0";

                                        $prInsert = $connection->prepare($sqlInsert);
                                        if( $prInsert->execute() ) {

                                            return array('rst' => true, 'msg' => 'Data insertada correctamente');

                                        } else{
                                            return array('rst' => false, 'msg' => 'Error al insertar data');
                                        }

                                    } else{
                                        return array('rst' => false, 'msg' => 'Error al actualizar id direccion a temporal');
                                    }

                                } else{
                                    return array('rst' => false, 'msg' => 'Error al actualizar id cuenta a temporal');
                                }

                            } else{
                                return array('rst' => false, 'msg' => 'Error al actualizar id gestion a temporal');
                            }

                        } else{
                            return array('rst' => false, 'msg' => 'Error al actualizar id gestor a temporal');
                        }

                    } else{
                        return array('rst' => false, 'msg' => 'Error al actualizar id estado a temporal');
                    }

                } else{
                    return array('rst' => false, 'msg' => 'Error al cargar data a temporal');
                }

            } else{
                return array('rst' => false, 'msg' => 'Error al cargar crear temporal');
            }

        } else {
            return array('rst' => false, 'msg' => 'Error al eliminar tabla temporal');

        }

    }
    
    public function uploadCarteraEstadoCuenta_masivo ( $_post, $file  ) {
        
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $idcampania=$_post['campania'];
        
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        }
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');

        }

        $time = date("Y_m_d_H_i_s");

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $dataCrt = array();
        
        $sqlCrt = " SELECT idcartera FROM ca_cartera WHERE estado = 1 AND idcampania = ? ";
        
        $prCrt = $connection->prepare( $sqlCrt );
        $prCrt->bindParam(1,$idcampania,PDO::PARAM_INT);
        $prCrt->execute();
        while( $row = $prCrt->fetch(PDO::FETCH_ASSOC) ) {
            array_push( $dataCrt, $row['idcartera'] );
        }

        $sqlDropTableTmpEstCu = " DROP TEMPORARY TABLE IF EXISTS tmp_estado_cuenta_" . $time;
        $prDropTableTmpEstCu = $connection->prepare($sqlDropTableTmpEstCu);
        if ($prDropTableTmpEstCu->execute()) {

            $sqlCreateTableTmpEstCu = " CREATE TEMPORARY TABLE tmp_estado_cuenta_" . $time . " (
                CODIGO_CLIENTE VARCHAR(50),
                NUMERO_CUENTA VARCHAR(50),
                MONEDA VARCHAR(20),
                GRUPO1 VARCHAR(20),
                STATUS VARCHAR(20),
                ESTADO VARCHAR(100),
                idcartera INT,
                INDEX( CODIGO_CLIENTE ),
                INDEX( NUMERO_CUENTA ),
                INDEX( MONEDA )
                )  ";

            $prsqlCreateTableTmpEstCu = $connection->prepare($sqlCreateTableTmpEstCu);
            if ($prsqlCreateTableTmpEstCu->execute()) {
                    
                    $sqlLoad = "";
                    if( $separator == 'tab' ) {
                        $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                        INTO TABLE tmp_estado_cuenta_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                    } else{
                        $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                        INTO TABLE tmp_estado_cuenta_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                    }
    
                    $prLoad = $connection->prepare($sqlLoad);
                    if( $prLoad->execute() ) {
                
                        $sqlUpdateCuenta = " UPDATE ca_cuenta cu INNER JOIN tmp_estado_cuenta_".$time." tmp
                            ON tmp.CODIGO_CLIENTE = cu.codigo_cliente AND tmp.NUMERO_CUENTA = cu.numero_cuenta 
                            AND tmp.MONEDA = cu.moneda AND tmp.GRUPO1 = cu.grupo1 
                            SET 
                            cu.estado = IF( UPPER(tmp.STATUS) = 'ACTIVO',1,0 ), 
                            cu.estado_cuenta = tmp.ESTADO 
                            WHERE cu.idcartera IN ( ".implode(",",$dataCrt)." )  ";
    
                        $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta); 
                        if( $prUpdateCuenta->execute() ) {
    
                            return array('rst' => true, 'msg' => 'Cuentas actualizadas correctamente');
    
                        }else{
    
                            return array('rst' => false, 'msg' => 'Error al actualizar cuentas');
    
                        }
                        
                    }else{
                        return array('rst' => false, 'msg' => 'Error cargar data a temporal');
                    }
                    
                
            }else{
                return array('rst' => false, 'msg' => 'Error al crear tabla temporal');
            }
     
        }else{
            return array('rst' => false, 'msg' => 'Error al eliminar tabla temporal');
        }
        
        
        
    }

    public function uploadCarteraSaldoTotalCencosud_masivo ( $_post, $file  ) {

        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        if (!isset($confCobrast['ruta_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        }
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');

        }

        $time = date("Y_m_d_H_i_s");

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlDropTableTmpEstCu = " DROP TEMPORARY TABLE IF EXISTS tmp_estado_cuenta_" . $time;
        $prDropTableTmpEstCu = $connection->prepare($sqlDropTableTmpEstCu);
        if ($prDropTableTmpEstCu->execute()) {

            $sqlCreateTableTmpEstCu = " CREATE TEMPORARY TABLE tmp_saldo_total_" . $time . " (
                EMPRESA VARCHAR(50),
                NUMERO_CUENTA VARCHAR(50),
                NUMERO_DOCUMENTO VARCHAR(20),
                FECHA_NACIMIENTO VARCHAR(20),
                SALDO_TOTAL DECIMAL(12,2),
                COD_SUC VARCHAR(10),
                GRP_AFINIDAD VARCHAR(10),
                idcartera INT,
                INDEX( NUMERO_CUENTA ),
                INDEX( NUMERO_DOCUMENTO )
                )  ";

            $prsqlCreateTableTmpEstCu = $connection->prepare($sqlCreateTableTmpEstCu);
            if ($prsqlCreateTableTmpEstCu->execute()) {

                    $sqlLoad = "";
                    if( $separator == 'tab' ) {
                        $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                        INTO TABLE tmp_saldo_total_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                    } else{
                        $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                        INTO TABLE tmp_saldo_total_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                    }

                    $prLoad = $connection->prepare($sqlLoad);
                    if( $prLoad->execute() ) {
                    
                        $fecha_fec_nac = "
                                    CASE
                                    WHEN LENGTH(TRIM( FECHA_NACIMIENTO )) = 8
                                    THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,1,4),'-',SUBSTRING( FECHA_NACIMIENTO ,5,2),'-',SUBSTRING( FECHA_NACIMIENTO ,7,2))
                                    WHEN LENGTH(TRIM( FECHA_NACIMIENTO )) = 10
                                    THEN
                                        CASE
                                        WHEN INSTR(TRIM( FECHA_NACIMIENTO ),'/') = 3
                                        THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,7,4),'-',SUBSTRING( FECHA_NACIMIENTO ,4,2),'-',SUBSTRING( FECHA_NACIMIENTO ,1,2))
                                        WHEN INSTR(TRIM( FECHA_NACIMIENTO ),'/') = 5
                                        THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,1,4),'-',SUBSTRING( FECHA_NACIMIENTO ,6,2),'-',SUBSTRING( FECHA_NACIMIENTO ,9,2))
                                        WHEN INSTR(TRIM( FECHA_NACIMIENTO ),'-') = 3
                                        THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,7,4),'-',SUBSTRING( FECHA_NACIMIENTO ,4,2),'-',SUBSTRING( FECHA_NACIMIENTO ,1,2))
                                        WHEN INSTR(TRIM( FECHA_NACIMIENTO ),'.') = 3
                                        THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,7,4),'-',SUBSTRING( FECHA_NACIMIENTO ,4,2),'-',SUBSTRING( FECHA_NACIMIENTO ,1,2))
                                        WHEN INSTR(TRIM( FECHA_NACIMIENTO ),'.') = 5
                                        THEN CONCAT(SUBSTRING( FECHA_NACIMIENTO ,1,4),'-',SUBSTRING( FECHA_NACIMIENTO ,6,2),'-',SUBSTRING( FECHA_NACIMIENTO ,9,2))
                                        ELSE TRIM( FECHA_NACIMIENTO )
                                        END
                                    ELSE TRIM( FECHA_NACIMIENTO )
                                    END
                                    ";
                    
                        $sqlUpdateNumDoc = " UPDATE tmp_saldo_total_" . $time . " 
                                            SET 
                                            NUMERO_DOCUMENTO = LPAD(NUMERO_DOCUMENTO,9,'0') ,
                                            FECHA_NACIMIENTO = ( ".$fecha_fec_nac." )
                                            WHERE EMPRESA = 'H' ";
                                            
                        $prUpdateNumDoc = $connection->prepare( $sqlUpdateNumDoc );
                        if( $prUpdateNumDoc->execute() ) {
                    
                            $sqlUpdateIdCartera = " UPDATE tmp_saldo_total_" . $time . " tmp
                                    SET
                                    tmp.idcartera = ( SELECT MAX(cu.idcartera) FROM ca_cartera car INNER JOIN ca_cuenta cu ON cu.idcartera = car.idcartera WHERE cu.numero_cuenta = tmp.NUMERO_CUENTA AND car.estado = 1 ) 
                                    WHERE tmp.EMPRESA = 'H' ";
    
                            $prUpdateIdCartera = $connection->prepare( $sqlUpdateIdCartera );
                            if( $prUpdateIdCartera->execute() ) {
    
                                $sqlUpdateFecNac = " UPDATE ca_cliente cli INNER JOIN tmp_saldo_total_" . $time . " tmp 
                                        ON tmp.NUMERO_DOCUMENTO = cli.numero_documento
                                        set 
                                        cli.fecha_nacimiento = tmp.FECHA_NACIMIENTO 
                                        WHERE cli.idservicio = ".$servicio." AND tmp.EMPRESA = 'H' ";
    
                                $prUpdateFecNac = $connection->prepare( $sqlUpdateFecNac );
                                if( $prUpdateFecNac->execute() ) {
    
                                    $sqlUpdateSaldo = " UPDATE ca_cuenta cu INNER JOIN tmp_saldo_total_" . $time . " tmp ON tmp.idcartera = cu.idcartera AND cu.numero_cuenta = tmp.NUMERO_CUENTA
                                            SET 
                                            cu.saldo_capital = tmp.SALDO_TOTAL
                                            WHERE tmp.idcartera IS NOT NULL  ";
    
                                    $prUpdateSaldo = $connection->prepare( $sqlUpdateSaldo );
                                    if( $prUpdateSaldo->execute() ) {
    
                                        $sqlIdCartera = " SELECT DISTINCT idcartera 
                                            FROM tmp_saldo_total_" . $time . " WHERE idcartera IS NOT NULL  ";
    
                                        $prIdCartera = $connection->prepare( $sqlIdCartera ); 
                                        $prIdCartera->execute();
                                        $data_c = array();
                                        while( $row = $prIdCartera->fetch(PDO::FETCH_ASSOC) ){
                                            array_push( $data_c, $row['idcartera'] );
                                        }
    
                                        $sqlCartera = " SELECT idcartera, cuenta, detalle_cuenta 
                                                FROM ca_cartera 
                                                WHERE idcartera IN ( ".implode(",",$data_c)." ) "; 
    
                                        $prCartera = $connection->prepare($sqlCartera);
                                        $prCartera->execute();
                                        $cartera_det = $prCartera->fetchAll( PDO::FETCH_ASSOC );
                                        for( $i=0;$i<count($cartera_det);$i++ ) {
    
                                            $cuenta = json_decode( str_replace("\\","",$cartera_det[$i]['cuenta']), true );
                                            $detalle_cuenta = json_decode( str_replace("\\","",$cartera_det[$i]['detalle_cuenta']), true );
                                            $saldo_t_c = 0;
                                            for( $j=0;$j<count($cuenta);$j++ ) {
    
                                                if( $cuenta[$j]['campoT'] == 'saldo_capital' ) {
                                                    $saldo_t_c++;
                                                }
    
                                            }
                                            $saldo_t_dc = 0;
                                            for( $j=0;$j<count($detalle_cuenta);$j++ ) {
                                                if( $detalle_cuenta[$j]['campoT'] == 'saldo_capital' ) {
                                                    $saldo_t_dc++;
                                                }
                                            }
    
                                            if( $saldo_t_c == 0 ) {
    
                                                array_push( $cuenta, array( "campoT"=>"saldo_capital", "dato"=>"SALDO_TOTAL", "label"=>"SALDO TOTAL" ) );
                                            }
                                            
                                            if( $saldo_t_dc == 0 ) {
                                                array_push( $detalle_cuenta, array( "campoT"=>"saldo_capital", "dato"=>"SALDO_TOTAL", "label"=>"SALDO TOTAL" ) );
                                            }
                                            
                                            if( $saldo_t_c == 0 || $saldo_t_dc == 0 ) {
                                            
                                                $sqlUpdateCartera = " UPDATE ca_cartera 
                                                                SET 
                                                                cuenta = ? ,
                                                                detalle_cuenta = ? ,
                                                                fecha_modificacion = NOW() ,
                                                                usuario_modificacion = ? 
                                                                WHERE idcartera = ? ";
                                                                
                                                $prUpdateCartera = $connection->prepare( $sqlUpdateCartera ); 
                                                $prUpdateCartera->bindParam(1,json_encode($cuenta),PDO::PARAM_STR);
                                                $prUpdateCartera->bindParam(2,json_encode($detalle_cuenta),PDO::PARAM_STR);
                                                $prUpdateCartera->bindParam(3,$usuario_creacion,PDO::PARAM_INT);
                                                $prUpdateCartera->bindParam(4,$cartera_det[$i]['idcartera'],PDO::PARAM_INT);
                                                if( $prUpdateCartera->execute() ) {
                                                
                                                }else{
                                                    return array('rst' => false, 'msg' => 'Error actualizar cartera');
                                                    exit();
                                                }
                                                
                                                
                                            }
                                            
                                        }
                                        
                                        return array('rst' => true, 'msg' => 'Saldos actualizados correctamente');
    
                                    }else{
                                        return array('rst' => false, 'msg' => 'Error actualizar saldo');
                                    }
    
                                }else{  
                                    return array('rst' => false, 'msg' => 'Error actualizar fecha de nacimiento');
                                }    
    
                            }else{
                                return array('rst' => false, 'msg' => 'Error actualizar temporal');
                            }
                        
                        }else{
                            return array('rst' => false, 'msg' => 'Error actualizar numero de documento');
                        }

                    }else{
                        return array('rst' => false, 'msg' => 'Error cargar data a temporal');
                    }


            }else{
                return array('rst' => false, 'msg' => 'Error al crear tabla temporal');
            }

        }else{
            return array('rst' => false, 'msg' => 'Error al eliminar tabla temporal');
        }



    }
    
    public function uploadCarteraDetalleMovil ( $_post, $file ) {
        
        $servicio = $_post['Servicio'];
        $nombre_servicio = $_post['NombreServicio'];
        $usuario_creacion = $_post['usuario_creacion'];
        $separator = $_post['separator'];
        $idcartera = $_post['Cartera'];
        
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        
        if (!isset($confCobrast['ruta_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } 
        if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        } 
        if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
            return array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion');
            exit();
        }
        if( !isset($confCobrast['tipo_cambio']) ) {
            return array('rst' => false, 'msg' => 'Ingrese tipo de cambio en archivo de configuracion');
            exit();
        }
        
        $tipo_cambio = $confCobrast['tipo_cambio']['dolar'] ; 
        
        $path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');

        }

        $time = date("Y_m_d_H_i_s");

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $sqlDropTableTmpDet = " DROP TEMPORARY TABLE IF EXISTS tmp_detalle_" . $time;
        $prDropTableTmpDet = $connection->prepare($sqlDropTableTmpDet);
        if ($prDropTableTmpDet->execute()) {
            
            $sqlCreateTableDet = " CREATE TEMPORARY TABLE tmp_detalle_" . $time . " 
                                (
                                COD_GEST VARCHAR(10) ,
                                COD_EMP_COB VARCHAR(10) ,
                                COD VARCHAR(50), 
                                GESTOR VARCHAR(100),
                                ANEXO VARCHAR(20),
                                CCZONA VARCHAR(10),
                                ZONAL VARCHAR(50),
                                NOM_TIP_DOC VARCHAR(20), 
                                CCLDOC VARCHAR(50), 
                                NRO_DOC VARCHAR(20),
                                NRO_FACT_DOC VARCHAR(10), 
                                ANIO_FEC_VEN VARCHAR(10), 
                                MES_FEC_VEN VARCHAR(10), 
                                DIA_FEC_VEN VARCHAR(10), 
                                DEUDA DECIMAL(12,4), 
                                MONEDA VARCHAR(10), 
                                DESCRIPCION VARCHAR(100) ,
                                CODIGO_CLIENTE VARCHAR(20),
                                idcuenta INT,
                                idcliente_cartera INT,
                                INDEX( idcliente_cartera ),
                                INDEX( idcuenta ),
                                INDEX( ANEXO ),
                                INDEX( NRO_DOC ) 
                                )  ";
            
            $prCreateTableDet = $connection->prepare( $sqlCreateTableDet );
            
            if( $prCreateTableDet->execute() ) {
                
                $sqlLoad = "";
                if( $separator == 'tab' ) {
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_detalle_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                } else{
                    $sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'
                    INTO TABLE tmp_detalle_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
                }
                
                $prLoad = $connection->prepare( $sqlLoad );
                if( $prLoad->execute() ) {
                    
                    $sqlUpdateIdCuentaCC = " UPDATE tmp_detalle_" . $time . " tmp INNER JOIN ca_cuenta cu 
                                ON cu.numero_cuenta = tmp.ANEXO
                                SET
                                tmp.idcuenta = cu.idcuenta ,
                                tmp.idcliente_cartera = cu.idcliente_cartera ,
                                tmp.CODIGO_CLIENTE = cu.codigo_cliente 
                                WHERE cu.idcartera = ?  ";
                                
                    $prUpdateIdCuentaCC = $connection->prepare( $sqlUpdateIdCuentaCC );
                    $prUpdateIdCuentaCC->bindParam(1, $idcartera, PDO::PARAM_INT );
                    if( $prUpdateIdCuentaCC->execute() ) {
                        
                        $sqlInsertDet = " INSERT IGNORE INTO ca_detalle_cuenta 
                            ( 
                            idcuenta, idcartera, codigo_cliente, numero_cuenta, codigo_operacion, moneda, grupo1, total_deuda, descripcion_servicio, fecha_vencimiento, 
                            dato1, dato2, dato3, dato4, dato5, dato6, dato7, dato8 , fecha_creacion, usuario_creacion  
                            ) 
                            SELECT idcuenta, ".$idcartera.", CODIGO_CLIENTE, ANEXO, NRO_DOC, MONEDA, NRO_FACT_DOC, DEUDA, DESCRIPCION, CONCAT_WS('-',ANIO_FEC_VEN,MES_FEC_VEN,DIA_FEC_VEN),
                            NOM_TIP_DOC, COD_GEST, GESTOR, COD_EMP_COB, CCZONA, COD, ZONAL, CCLDOC , NOW(), ".$usuario_creacion." 
                            FROM tmp_detalle_" . $time . " 
                            WHERE idcuenta IS NOT NULL AND idcliente_cartera IS NOT NULL ";
                        
                        $prInsertDet = $connection->prepare( $sqlInsertDet );
                        if( $prInsertDet->execute() ) {
                            
                            $sqlUpdateCuenta = " UPDATE ca_cuenta cu
                                                SET 
                                                cu.is_retiro = 1 ,
                                                cu.retirado = 1 ,
                                                cu.fecha_retiro = CURDATE(),
                                                cu.motivo_retiro = 'DETALLE'
                                                WHERE cu.idcartera = ? 
                                                AND cu.idcuenta NOT IN ( SELECT idcuenta FROM tmp_detalle_" . $time . " WHERE idcuenta IS NOT NULL AND idcliente_cartera IS NOT NULL )
                                                ";  
                            
                            $prUpdateCuenta = $connection->prepare( $sqlUpdateCuenta );
                            $prUpdateCuenta->bindParam(1,$idcartera,PDO::PARAM_INT);
                            if( $prUpdateCuenta->execute() ) {
                                
                                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera clicar
                                                        SET 
                                                        clicar.retiro = 1 ,
                                                        clicar.estado = 0 
                                                        WHERE clicar.idcartera = ? 
                                                        AND clicar.idcliente_cartera NOT IN ( SELECT idcliente_cartera FROM tmp_detalle_" . $time . " WHERE idcuenta IS NOT NULL AND idcliente_cartera IS NOT NULL ) ";
                                
                                $prUpdateClienteCartera = $connection->prepare( $sqlUpdateClienteCartera );
                                $prUpdateClienteCartera->bindParam(1,$idcartera,PDO::PARAM_INT);
                                if( $prUpdateClienteCartera->execute() ) {
                                    
                                    $sqlUpdateDeuda = " UPDATE ca_cuenta cu 
                                                        SET
                                                        cu.total_deuda = ( SELECT SUM( IF( MONEDA = 'USD', DEUDA*".$tipo_cambio." , DEUDA ) ) FROM tmp_detalle_" . $time . " WHERE idcartera = cu.idcartera AND idcuenta = cu.idcuenta AND idcuenta IS NOT NULL AND idcliente_cartera IS NOT NULL )
                                                        WHERE cu.idcartera = ? AND cu.retirado = 0 
                                                        ";
                                    
                                    $prUpdateDeuda = $connection->prepare( $sqlUpdateDeuda );
                                    $prUpdateDeuda->bindParam(1,$idcartera,PDO::PARAM_INT);
                                    if( $prUpdateDeuda->execute() ) {
                                        
                                        $sqlC = " SELECT idcartera, cuenta, detalle_cuenta , adicionales 
                                            FROM ca_cartera 
                                            WHERE idcartera = ? ";
                                            
                                        $prC = $connection->prepare( $sqlC );
                                        $prC->bindParam( 1, $idcartera, PDO::PARAM_INT );
                                        $prC->execute();
                                        $dataC = $prC->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        $cuenta = json_decode( $dataC[0]['cuenta'], true );
                                        $detalle_cuenta = json_decode( $dataC[0]['detalle_cuenta'], true );
                                        $adicionales = json_decode( $dataC[0]['adicionales'], true );
                                        
                                        $c_total_deuda_c = 0 ;
                                        for( $i=0;$i<count($cuenta);$i++ ) {
                                            if( $cuenta[$i]['campoT'] == 'total_deuda' ) {
                                                $c_total_deuda_c++;
                                            }
                                        }
                                        
                                        if( $c_total_deuda_c == 0 ) {
                                            array_push( $cuenta, array( "campoT"=>"total_deuda", "dato"=>"TOTAL_DEUDA", "label"=>"TOTAL DEUDA" ) );
                                        }
                                        
                                        $c_numero_cuenta_det = 0;
                                        $c_codigo_operacion_det = 0;
                                        $c_grupo1_det = 0;
                                        $c_moneda_det = 0;
                                        $c_total_deuda_det = 0;
                                        $c_descripcion_servicio_det = 0;
                                        $c_fech_venc_det = 0;
                                        for( $i=0;$i<count($detalle_cuenta);$i++ ) {
                                        
                                            if( $detalle_cuenta[$i]['campoT'] == 'numero_cuenta' ) {
                                                $c_numero_cuenta_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'codigo_operacion' ) {
                                                $c_codigo_operacion_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'grupo1' ) {
                                                $c_grupo1_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'moneda' ) {
                                                $c_moneda_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'total_deuda' ) {
                                                $c_total_deuda_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'descripcion_servicio' ) {
                                                $c_descripcion_servicio_det++;
                                            }else if( $detalle_cuenta[$i]['campoT'] == 'fecha_vencimiento' ) {
                                                $c_fech_venc_det++;
                                            }
                                            
                                        }
                                        
                                        if( $c_numero_cuenta_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"numero_cuenta", "dato"=>"TCNFOL", "label"=>"ANEXO" ) );
                                        }
                                        if( $c_codigo_operacion_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"codigo_operacion", "dato"=>"CCNDOC", "label"=>"NRO FACTURA" ) );
                                        }
                                        if( $c_grupo1_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"grupo1", "dato"=>"CCNLCA", "label"=>"CUOTA" ) );
                                        }
                                        if( $c_moneda_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"moneda", "dato"=>"CCMOEL", "label"=>"MONEDA" ) );
                                        }
                                        if( $c_total_deuda_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"total_deuda", "dato"=>"CCSASG", "label"=>"DEUDA" ) );
                                        }
                                        if( $c_descripcion_servicio_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"descripcion_servicio", "dato"=>"CCMOD1", "label"=>"NOM FACTURA" ) );
                                        }
                                        if( $c_fech_venc_det == 0 ) {
                                            array_push( $detalle_cuenta, array( "campoT"=>"fecha_vencimiento", "dato"=>"FEC_VENC", "label"=>"FECHA VENCIMIENTO" ) );
                                        }
                                        
                                        $c_dato1_adc = 0;
                                        $c_dato2_adc = 0;
                                        $c_dato3_adc = 0;
                                        $c_dato4_adc = 0;
                                        $c_dato5_adc = 0;
                                        $c_dato6_adc = 0;
                                        $c_dato7_adc = 0;
                                        $c_dato8_adc = 0;
                                        for( $i=0;$i<count($adicionales['ca_datos_adicionales_detalle_cuenta']);$i++ ) {
                                            if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato1' ) {
                                                $c_dato1_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato2' ) {
                                                $c_dato2_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato3' ) {
                                                $c_dato3_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato4' ) {
                                                $c_dato4_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato5' ) {
                                                $c_dato5_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato6' ) {
                                                $c_dato6_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato7' ) {
                                                $c_dato7_adc++;
                                            }else if( $adicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT'] == 'dato8' ) {
                                                $c_dato8_adc++;
                                            }
                                        }
                                        
                                        if( $c_dato1_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato1", "dato"=>"CCTIPD", "label"=>"TIPO FACTURA" ) );
                                        }
                                        if( $c_dato2_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato2", "dato"=>"ASNUMA", "label"=>"NRO GESTION" ) );
                                        }
                                        if( $c_dato3_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato3", "dato"=>"GESTOR", "label"=>"EMPRESA" ) );
                                        }
                                        if( $c_dato4_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato4", "dato"=>"ASGEST", "label"=>"COD EMP" ) );
                                        }
                                        if( $c_dato5_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato5", "dato"=>"CCZONA", "label"=>"COD ZONA" ) );
                                        }
                                        if( $c_dato6_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato6", "dato"=>"COD", "label"=>"COD" ) );
                                        }
                                        if( $c_dato7_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato7", "dato"=>"ZONAL", "label"=>"ZONAL" ) );
                                        }
                                        if( $c_dato8_adc == 0 ) {
                                            array_push( $adicionales['ca_datos_adicionales_detalle_cuenta'], array( "campoT"=>"dato8", "dato"=>"CCLDOC", "label"=>"CCLDOC" ) );
                                        }
                                        
                                        $sqlUpdateCartera = " UPDATE ca_cartera
                                                            SET
                                                            cuenta = ? , 
                                                            detalle_cuenta = ? , 
                                                            adicionales = ? , 
                                                            usuario_modificacion = ? , 
                                                            fecha_modificacion = NOW() 
                                                            WHERE idcartera = ? ";
                                                            
                                        $prUpdateCartera = $connection->prepare( $sqlUpdateCartera );
                                        $prUpdateCartera->bindParam(1,json_encode( str_replace("\\","",$cuenta) ),PDO::PARAM_STR);
                                        $prUpdateCartera->bindParam(2,json_encode( str_replace("\\","",$detalle_cuenta) ),PDO::PARAM_STR);
                                        $prUpdateCartera->bindParam(3,json_encode( str_replace("\\","",$adicionales) ),PDO::PARAM_STR);
                                        $prUpdateCartera->bindParam(4,$usuario_creacion,PDO::PARAM_INT);
                                        $prUpdateCartera->bindParam(5,$idcartera,PDO::PARAM_INT);
                                        if( $prUpdateCartera->execute() ) {
                                        
                                            return array('rst' => true, 'msg' => 'Detalle cargado correctamente');
                                            
                                        }else{
                                            return array('rst' => false, 'msg' => 'Error al actualizar cartera');
                                        }
                                        
                                    }else{
                                        return array('rst' => false, 'msg' => 'Error al actualizar deuda');
                                    }
                                    
                                }else{
                                    return array('rst' => false, 'msg' => 'Error al retirar clientes');
                                }
                                    
                            }else{

                                return array('rst' => false, 'msg' => 'Error al retirar cuentas');
                            }
                            
                        }else{
                            return array('rst' => false, 'msg' => 'Error insertar detalle');
                        }
                        
                    }else{
                        return array('rst' => false, 'msg' => 'Error actualizar temporal');
                    }
                    
                }else{
                    return array('rst' => false, 'msg' => 'Error al cargar data a tabla temporal');
                }
                
            }else{
                return array('rst' => false, 'msg' => 'Error crear tabla temporal');
            }
            
        }else{
            return array('rst' => false, 'msg' => 'Error al eliminar tabla temporal');
        }
        
        
    }

//~ Vic I
public function historyCartera($tmpTable, $servicio=914)
{

    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();

    $sqlJson = "SELECT cabeceras FROM ca_json_parser WHERE idservicio=".$servicio." ORDER BY idjson_parser DESC LIMIT 1 ";
    $kryJson = $connection->prepare($sqlJson);
    $kryJson->execute();
    $rowJson = $kryJson->fetch(PDO::FETCH_ASSOC);
    $columnaJson = $rowJson['cabeceras'];

$sql = <<<EOT
INSERT INTO ca_historial (id, {$columnaJson} , idcartera, idcliente, idcliente_cartera, 
    idcuenta,tablaTemp)
SELECT NULL, {$columnaJson} , idcartera, idcliente, idcliente_cartera, idcuenta, '{$tmpTable}' 
FROM {$tmpTable} 
EOT;

//~ Total de Registro
    $sqlTotal = "SELECT COUNT(contrato) AS contratos FROM ".$tmpTable;
    $kryTotal = $connection->prepare($sqlTotal);
    $kryTotal->execute();
    $row = $kryTotal->fetch(PDO::FETCH_ASSOC);

    $kryHistory = $connection->prepare($sql);
    if ($kryHistory->execute())
    {
        return array('rstHis' => true, 'msgHis' => 'Historial_OK - '.$row['contratos'].' Registros');
    }
    else
    {
        return array('rstHis' => false, 'msgHis' => '[_Error_]_Historial');
    }
}

// piro 
public function historyCarteraSaga($tmpTable, $servicio)
{

    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();

    $sqlJson = "SELECT cabeceras FROM ca_json_parser WHERE idservicio=".$servicio." ORDER BY idjson_parser DESC LIMIT 1 ";
    $kryJson = $connection->prepare($sqlJson);
    $kryJson->execute();
    $rowJson = $kryJson->fetch(PDO::FETCH_ASSOC);
    $columnaJson = $rowJson['cabeceras'];

$sql = <<<EOT
INSERT INTO ca_historial_saga (idhistorial_saga, {$columnaJson} , idcartera, idcliente, idcliente_cartera, 
    idcuenta,tablaTemp)
SELECT NULL, {$columnaJson} , idcartera, idcliente, idcliente_cartera, idcuenta, '{$tmpTable}' 
FROM {$tmpTable} 
EOT;

//~ Total de Registro
    $sqlTotal = "SELECT COUNT(NROTARJETA) AS NROTARJETA FROM ".$tmpTable;
    $kryTotal = $connection->prepare($sqlTotal);
    $kryTotal->execute();
    $row = $kryTotal->fetch(PDO::FETCH_ASSOC);

    $kryHistory = $connection->prepare($sql);
    if ($kryHistory->execute())
    {
        return array('rstHis' => true, 'msgHis' => 'Historial_OK - '.$row['NROTARJETA'].' Registros');
    }
    else
    {
        return array('rstHis' => false, 'msgHis' => '[_Error_]_Historial');
    }
}

public function uploadJoinClientes($_post,$_files)
{
    $create_hora = date("Ymd_His");
    $nombre_archivo = $create_hora."_".$_files['uploadFileCargaClienteNew']['name'];
    if (@opendir('../documents/cartera_unir/' . $_post['NombreServicio'])) {
        if (@move_uploaded_file($_files['uploadFileCargaClienteNew']['tmp_name'], '../documents/cartera_unir/' . $_post['NombreServicio'] . '/'.$nombre_archivo)) {

            //~ Limpiar TXT
            $_post['file'] = $nombre_archivo;
            $retornoLimpiar = $this->limpiarTxtSinCabecera($_post, "cartera_unir", "|");
            if ($retornoLimpiar['rst']) {
                $nombre_archivo = $retornoLimpiar['file'];
            } else {
                echo json_encode(array('rst' => false, 'msg' => $retornoLimpiar['msg']));
                exit();
            }

            $factoryConnection = FactoryConnection::create('mysql');
            $connection = $factoryConnection->getConnection();

            $prClearCliente = $connection->prepare("CREATE TABLE ca_nuevo_cliente_".$create_hora." LIKE ca_nuevo_cliente");
            $prClearCliente->execute();

            $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
            $sqlLoadClienteJoin = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/cartera_unir/".$_post['NombreServicio']
                        ."/".$nombre_archivo."' INTO TABLE ca_nuevo_cliente_".$create_hora." FIELDS TERMINATED BY '|' LINES  TERMINATED BY '\\r\\n' ";
            $prLoadClienteJoin = $connection->prepare($sqlLoadClienteJoin);

            if ($prLoadClienteJoin->execute()) {
                echo json_encode(array('rst' => true, 'valor' => 1, 'msg' => 'Se guardo correctamente los datos del Archivo', 'tiempo' => $create_hora));
            } else {
                echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error al guardar los datos', 'tiempo' => $create_hora));
            }

        } else {
            echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error en subir el archivo', 'tiempo' => $create_hora));
        }
    } else {
        echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error en abrir la carpeta', 'tiempo' => $create_hora));
    }
}

public function uploadJoinContratos($_post,$_files)
{
    $create_hora = date("Ymd_His");
    $nombre_archivo = $create_hora."_".$_files['uploadFileCargaContratoNew']['name'];
    if (@opendir('../documents/cartera_unir/'.$_post['NombreServicio'])) {
        if (@move_uploaded_file($_files['uploadFileCargaContratoNew']['tmp_name'], '../documents/cartera_unir/' . $_post['NombreServicio'] . '/'.$nombre_archivo)) {

            //~ Limpiar TXT
            $_post['file'] = $nombre_archivo;
            $retornoLimpiar = $this->limpiarTxtSinCabecera($_post, "cartera_unir", "|");
            if ($retornoLimpiar['rst']) {
                $nombre_archivo = $retornoLimpiar['file'];
            } else {
                echo json_encode(array('rst' => false, 'msg' => $retornoLimpiar['msg']));
                exit();
            }

            $factoryConnection = FactoryConnection::create('mysql');
            $connection = $factoryConnection->getConnection();

            $prClearCliente = $connection->prepare("CREATE TABLE ca_nuevo_contrato_".$create_hora." LIKE ca_nuevo_contrato");
            $prClearCliente->execute();

            $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
            $sqlLoadClienteJoin = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/cartera_unir/".$_post['NombreServicio']
                        ."/".$nombre_archivo."' INTO TABLE ca_nuevo_contrato_".$create_hora." FIELDS TERMINATED BY '|' LINES  TERMINATED BY '\\r\\n' ";
            $prLoadClienteJoin = $connection->prepare($sqlLoadClienteJoin);

            if ($prLoadClienteJoin->execute()) {
                echo json_encode(array('rst' => true, 'valor' => 1, 'msg' => 'Se guardo correctamente los datos del Archivo', 'tiempo' => $create_hora));
            } else {
                echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error al guardar los datos', 'tiempo' => $create_hora));
            }

        } else {
            echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error en subir el archivo', 'tiempo' => $create_hora));
        }
    } else {
        echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error en abrir la carpeta', 'tiempo' => $create_hora));
    }
}

public function txtJoinCarteras($_post)
{
    $create_hora = date("Ymd_His");
    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();

    $timeCli = $_post['timeCli'];
    $timeCon = $_post['timeCon'];

$sqlJoin = <<<EOT
    SELECT cli.cod_central, cli.fecha_proceso, cli.tipodoc_identidad, cli.numdoc_identidad, cli.cod_territorio, cli.cod_oficina, 
        cli.cod_gestorbco, cli.nombre, cli.pri_apellido, cli.seg_apellido, cli.tipo_cliente, cli.nrocont_impago, cli.cod_oficinasignada, 
        cli.agencia_asignada, cli.fecha_asignacion, cli.ind_invirregular, cli.accion, cli.tel_particular, cli.tel_trabajo, cli.tel_movil, 
        cli.tel_4, cli.tel_5, cli.email1, cli.email2, cli.direccion1, cli.ciudad1, cli.codpostal1, cli.prov1, cli.direccion2, cli.ciudad2, 
        cli.codpostal2, cli.prov2, cli.direccion3, cli.ciudad3, cli.codpostal3, cli.prov3,
        con.num_contrato, con.fecha_proceso, con.fecha_vencimiento, con.fecha_entinvirregular, con.tipo_prod, con.tipo_subprod, 
        con.mon_contrato, con.mon_secundaria, con.ofic_gestora, con.nro_cuotasimp, con.deuda_total, con.deuda_totalcap, con.deuda_totint, 
        con.deuda_totalotr, con.deuda_totalintmor, con.deuda_parcialmonloc, con.deuda_parcialmonsec, con.deuda_impago, con.deuda_impagocap, 
        con.deuda_impagoint, con.deuda_impagootr, con.deuda_impagointmor, con.deuda_impagogastos, con.deuda_impagomonloc, con.deuda_impagomonsec, 
        con.tipodecambio, con.deuda_proxcuota, con.deuda_proxcuotasec, con.fecha_proxcouta, con.deuda_mora, con.cod_oficinasignada
    FROM ca_nuevo_cliente_{$timeCli} cli
        INNER JOIN ca_nuevo_contrato_{$timeCon} con ON cli.cod_central=con.cod_central
    WHERE TRIM(cli.cod_central)!='' AND TRIM(con.cod_central)!=''
EOT;

    $prJoin = $connection->prepare($sqlJoin);
    $prJoin->execute();

    $html = "cod_central|fecha_proceso|tipodoc_identidad|numdoc_identidad|cod_territorio|cod_oficina|"
            ."cod_gestorbco|nombre|pri_apellido|seg_apellido|tipo_cliente|nrocont_impago|cod_oficinasignada|"
            ."agencia_asignada|fecha_asignacion|ind_invirregular|accion|tel_particular|tel_trabajo|tel_movil|"
            ."tel_4|tel_5|email1|email2|direccion1|ciudad1|codpostal1|prov1|direccion2|ciudad2|"
            ."codpostal2|prov2|direccion3|ciudad3|codpostal3|prov3|"
            ."num_contrato|fecha_proceso|fecha_vencimiento|fecha_entinvirregular|tipo_prod|tipo_subprod|"
            ."mon_contrato|mon_secundaria|ofic_gestora|nro_cuotasimp|deuda_total|deuda_totalcap|deuda_totint|"
            ."deuda_totalotr|deuda_totalintmor|deuda_parcialmonloc|deuda_parcialmonsec|deuda_impago|deuda_impagocap|"
            ."deuda_impagoint|deuda_impagootr|deuda_impagointmor|deuda_impagogastos|deuda_impagomonloc|deuda_impagomonsec|"
            ."tipodecambio|deuda_proxcuota|deuda_proxcuotasec|fecha_proxcouta|deuda_mora|cod_oficinasignada\n";
    while( $row = $prJoin->fetch(PDO::FETCH_ASSOC) ) {
        $html .= $row["cod_central"]."|";
        $html .= $row["fecha_proceso"]."|";
        $html .= $row["tipodoc_identidad"]."|";
        $html .= $row["numdoc_identidad"]."|";
        $html .= $row["cod_territorio"]."|";
        $html .= $row["cod_oficina"]."|";
        $html .= $row["cod_gestorbco"]."|";
        $html .= $row["nombre"]."|";
        $html .= $row["pri_apellido"]."|";
        $html .= $row["seg_apellido"]."|";
        $html .= $row["tipo_cliente"]."|";
        $html .= $row["nrocont_impago"]."|";
        $html .= $row["cod_oficinasignada"]."|";
        $html .= $row["agencia_asignada"]."|";
        $html .= $row["fecha_asignacion"]."|";
        $html .= $row["ind_invirregular"]."|";
        $html .= $row["accion"]."|";
        $html .= $row["tel_particular"]."|";
        $html .= $row["tel_trabajo"]."|";
        $html .= $row["tel_movil"]."|";
        $html .= $row["tel_4"]."|";
        $html .= $row["tel_5"]."|";
        $html .= $row["email1"]."|";
        $html .= $row["email2"]."|";
        $html .= $row["direccion1"]."|";
        $html .= $row["ciudad1"]."|";
        $html .= $row["codpostal1"]."|";
        $html .= $row["prov1"]."|";
        $html .= $row["direccion2"]."|";
        $html .= $row["ciudad2"]."|";
        $html .= $row["codpostal2"]."|";
        $html .= $row["prov2"]."|";
        $html .= $row["direccion3"]."|";
        $html .= $row["ciudad3"]."|";
        $html .= $row["codpostal3"]."|";
        $html .= $row["prov3"]."|";

        $html .= $row["num_contrato"]."|";
        $html .= $row["fecha_proceso"]."|";
        $html .= $row["fecha_vencimiento"]."|";
        $html .= $row["fecha_entinvirregular"]."|";
        $html .= $row["tipo_prod"]."|";
        $html .= $row["tipo_subprod"]."|";
        $html .= $row["mon_contrato"]."|";
        $html .= $row["mon_secundaria"]."|";
        $html .= $row["ofic_gestora"]."|";
        $html .= $row["nro_cuotasimp"]."|";
        $html .= $row["deuda_total"]."|";
        $html .= $row["deuda_totalcap"]."|";
        $html .= $row["deuda_totint"]."|";
        $html .= $row["deuda_totalotr"]."|";
        $html .= $row["deuda_totalintmor"]."|";
        $html .= $row["deuda_parcialmonloc"]."|";
        $html .= $row["deuda_parcialmonsec"]."|";
        $html .= $row["deuda_impago"]."|";
        $html .= $row["deuda_impagocap"]."|";
        $html .= $row["deuda_impagoint"]."|";
        $html .= $row["deuda_impagootr"]."|";
        $html .= $row["deuda_impagointmor"]."|";
        $html .= $row["deuda_impagogastos"]."|";
        $html .= $row["deuda_impagomonloc"]."|";
        $html .= $row["deuda_impagomonsec"]."|";
        $html .= $row["tipodecambio"]."|";
        $html .= $row["deuda_proxcuota"]."|";
        $html .= $row["deuda_proxcuotasec"]."|";
        $html .= $row["fecha_proxcouta"]."|";
        $html .= $row["deuda_mora"]."|";
        $html .= $row["cod_oficinasignada"]."\n";
    }

    $cnf = parse_ini_file('../conf/cobrast.ini', true);
    $ruta = $cnf['ruta_cobrast']['document_root_cobrast'] . "/" . $cnf['ruta_cobrast']['nombre_carpeta'] . "/documents/cartera_unir/".$_post['NombreServicio']."/";
    $archivo = fopen($ruta."cartera_".$create_hora.".txt","w+");
    fputs($archivo,$html);
    fclose($archivo);

//~ Eliminar TMP
    $prDelteTmpCli = $connection->prepare("DROP TABLE ca_nuevo_cliente_".$timeCli);
    $prDelteTmpCli->execute();
    $prDelteTmpCon = $connection->prepare("DROP TABLE ca_nuevo_contrato_".$timeCon);
    $prDelteTmpCon->execute();

    $zip = new ZipArchive;
    if ($zip->open($ruta."cartera_".$create_hora.".zip",ZIPARCHIVE::CREATE) === TRUE) {
        $zip->addFile($ruta."cartera_".$create_hora.".txt","cartera_".$create_hora.".txt");
        $zip->close();
        echo json_encode(array('rst' => true, 'valor' => 1, 'msg' => 'Archivo para Exportar', 'tiempo' => $create_hora));
    } else {
        echo json_encode(array('rst' => false, 'valor' => 0, 'msg' => 'Error para Exportar', 'tiempo' => $create_hora));
    }

}
public function uploadCargaFacturacion($_post,$_files){
    $create_hora=date("Ymd_His");
    $nombre_archivo=$create_hora."_".$_files['uploadFileCarteraCargaFacturacion']['name'];
    if(@opendir('../documents/facturacion/'.$_post['NombreServicio'])){
        if(@move_uploaded_file($_files['uploadFileCarteraCargaFacturacion']['tmp_name'],'../documents/facturacion/'.$_post['NombreServicio'].'/'.$nombre_archivo)){
            $_post['file'] = $nombre_archivo;
            $retornoLimpiar = $this->limpiarTxtSinCabecera($_post, "facturacion", "|");
            if ($retornoLimpiar['rst']) {
                $nombre_archivo = $retornoLimpiar['file'];
                echo json_encode(array('rst' => true, 'msg' => $retornoLimpiar['msg'],'file'=>$nombre_archivo));  
                exit();              
            } else {
                echo json_encode(array('rst' => false, 'msg' => $retornoLimpiar['msg']));
                exit();
            }
            echo json_encode(array('rst' => true, 'msg' => 'Se Subio Satisfactoriamente'));
        }else{
            echo json_encode(array('rst' => true, 'msg' => 'Error al subir archivo'.'../documents/facturacion/'.$_post['NombreServicio'].'/'.$nombre_archivo));
        }
    }else{
        echo json_encode(array('rst' => false, 'msg' => 'Error al abrir Carpeta '));
    }
}
public function uploadCargaProvision($_post,$_files){
    $create_hora=date("Ymd_His");
    $nombre_archivo=$create_hora."_".$_files['uploadFileCarteraCargaProvision']['name'];
    if(@opendir('../documents/provision/'.$_post['NombreServicio'])){
        if(@move_uploaded_file($_files['uploadFileCarteraCargaProvision']['tmp_name'],'../documents/provision/'.$_post['NombreServicio'].'/'.$nombre_archivo)){
            $_post['file'] = $nombre_archivo;
            $retornoLimpiar = $this->limpiarTxtSinCabecera($_post, "provision", "|");
            if ($retornoLimpiar['rst']) {
                $nombre_archivo = $retornoLimpiar['file'];
                echo json_encode(array('rst' => true, 'msg' => $retornoLimpiar['msg'],'file'=>$nombre_archivo));  
                exit();              
            } else {
                echo json_encode(array('rst' => false, 'msg' => $retornoLimpiar['msg']));
                exit();
            }
            echo json_encode(array('rst' => true, 'msg' => 'Se Subio Satisfactoriamente'));
        }else{
            echo json_encode(array('rst' => true, 'msg' => 'Error al subir archivo'.'../documents/provision/'.$_post['NombreServicio'].'/'.$nombre_archivo));
        }
    }else{
        echo json_encode(array('rst' => false, 'msg' => 'Error al abrir Carpeta '));
    }
}
public function uploadCargaProvisionTotal($_post,$_files){ // airton
    $create_hora=date("Ymd_His");
    $nombre_archivo=$create_hora.'_'.rand(1, 1000)."_".'provisiontotal.txt';
    $destino= '../documents/provisionTotal/'.$_post['NombreServicio'].'/';
    $idCarteras = $_POST['idCarteras'];
    $usuarioCreacion = $_post['UsuarioCreacion'];
    $fechaprovisionTotal = $_POST['fechaProvision'];

    if(@opendir('../documents/provisionTotal/'.$_post['NombreServicio'])){
        if(@move_uploaded_file($_files['uploadFileProvisionTotal']['tmp_name'],'../documents/provisionTotal/'.$_post['NombreServicio'].'/'.$nombre_archivo)){
            
            $boolValido=$this->validarArchivoProvisionTotal($destino,$nombre_archivo);
           
            if($boolValido['rst']){
                $this->cargarProvisionTotal($boolValido['file'],$idCarteras, $usuarioCreacion, $fechaprovisionTotal);


            }
           


        }else{
            echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo'));
        }
    }else{
        echo json_encode(array('rst' => false, 'msg' => 'Error al abrir Carpeta '));
    }
}
public function cargarProvisionTotal ( $tmpFile, $xidCarteras, $xUsuarioCreacion, $xFechaProvisionTotal ) {//<piro>
    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    $time=date("Y_m_d_H_i_s");
    $idCarteras = $xidCarteras;
    $nombreTemporal = "tmp_carga_ProvisionTotal_" . $time ;
    $usuarioCreacion = $xUsuarioCreacion;
    $fechaProvisionTotal = $xFechaProvisionTotal;


    $sqlCreateTableTmpEstCu = " CREATE TABLE tmp_carga_ProvisionTotal_" . $time . " (
        AREA VARCHAR(45),
        BANCA VARCHAR(45),
        TERRITORIO VARCHAR(250),
        COD_OFI VARCHAR(250),
        OFICINA VARCHAR(250),
        COD_CEN VARCHAR(250),
        NOMBRE VARCHAR(250),
        CLASIF_ABR_15 VARCHAR(250),
        CLASIFC_MAY_15 VARCHAR(250),
        MOTIVO VARCHAR(45),
        INDICADOR VARCHAR(45),
        SEGMENTO_NEGOCIO VARCHAR(45),
        TIPO VARCHAR(45),
        DEUDA VARCHAR(45),
        GARANTIA VARCHAR(200),
        TOTAL_STOCK_PROVISION_DIC_2014 VARCHAR(200),
        TOTAL_STOCK_PROVISION_ABR_2015 VARCHAR(200),
        DIFERENCIA_STOCK_PROVISION VARCHAR(100),
        CORPORATIVO VARCHAR(50),
        GRANDES_EMPRESAS VARCHAR(50),
        MEDIANAS_EMPRESAS VARCHAR(50),
        PEQUENA_EMPRESA VARCHAR(50),
        MICRO_EMPRESA VARCHAR(50),
        CONSUMO_TOTAL varchar(50),
        CONSUMO_TARJETA varchar(50),
        CONSUMO_PRESTAMO varchar(50),
        CONSUMO_CONTIAUTO varchar(50),
        HIPOTECARIO varchar(200),
        VARIACION_ARRASTRE varchar(200),
        PRODUCTO_GENERA_ARRASTRE varchar(200),
        DIAS_IMPAGO_PRODUCTO_GENERA_ARRASTRE varchar(200),
        TIPO_ARRASTRE varchar(200),
        TOTAL_VARIACION_PROVISION varchar(200),
        DEUDA_VIGENTE__VIGENTE_CON_IMPAGO varchar(200),
        DEUDA_REESTRUCTURADA varchar(200),
        DEUDA_REFINANCIADA varchar(200),
        DEUDA_VENCIDA varchar(200),
        DEUDA_JUDICIAL varchar(200),
        estado TINYINT(4), 
        idcliente_cartera INT,
        idcartera int,
        INDEX( COD_CEN ),
        INDEX( HIPOTECARIO ),
        INDEX( TOTAL_VARIACION_PROVISION ),
        INDEX( idcliente_cartera ),
        INDEX( idcartera ),
        INDEX( estado ),
        INDEX( MOTIVO )    
        )  ";
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $prcreartemporal=$connection->prepare($sqlCreateTableTmpEstCu);
        if($prcreartemporal->execute()){

            $sqlLoadData = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/provisionTotal/BBVA/" .$tmpFile . "'
                             INTO TABLE tmp_carga_ProvisionTotal_" . $time." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
            
            $prLoadData=$connection->prepare($sqlLoadData);
            if($prLoadData->execute()){
                /* UPDATE ESTADO 0  */
                $sqlUpdateEstado="UPDATE tmp_carga_ProvisionTotal_" . $time." SET estado=1";

                $prUpdateEstado= $connection->prepare($sqlUpdateEstado);
                if($prUpdateEstado->execute()){
                     /* FILTRO PARA QUE EL MOTIVO QUE CONTENGA CI, CJ, EJ GUARDEN ESTADO 0  */
                     $sqlUpdateEstadoXMotivo= "UPDATE tmp_carga_ProvisionTotal_" . $time." SET estado=0 WHERE MOTIVO IN ('CI','CJ','EJ') ";

                     $prUpdateEstadoXMotivo= $connection->prepare($sqlUpdateEstadoXMotivo);
                     if($prUpdateEstadoXMotivo->execute()){
                        $sqlupdatecliente = "UPDATE tmp_carga_ProvisionTotal_" . $time." tmp
                                INNER JOIN ca_cliente_cartera clicar on clicar.codigo_cliente=tmp.COD_CEN
                                SET tmp.idcliente_cartera=clicar.idcliente_cartera,tmp.idcartera=clicar.idcartera
                                WHERE clicar.idcartera IN (".$idCarteras.")";

                        $prupdatecliente=$connection->prepare($sqlupdatecliente);
                        if($prupdatecliente->execute()){
                            /*EVALUAR SI LA FECHA DE LA PROVISION QUE VA A CARGAR YA EXISTE EN EL HISTORIAL DE TABLAS, ESTO ES PARA REEMPLAZAR LOS ACTUALES DATOS*/
                            $sqlExistProvisionHistorial="UPDATE ca_historial_tabla_provision_total SET estado = 0 WHERE fecha_provisionTotal = '".$fechaProvisionTotal."'";
                            $prExistProvisionHistorial=$connection->prepare($sqlExistProvisionHistorial);
                            $prExistProvisionHistorial->execute();

                            $sqlInsertHistorialTablaProvision= "INSERT INTO ca_historial_tabla_provision_total(nombre_tabla,fecha,estado,fecha_provisionTotal) VALUES ('".$nombreTemporal."',now(),1 ,'".$fechaProvisionTotal."') ";
                            $prInsertHistorialTablaProvision = $connection->prepare($sqlInsertHistorialTablaProvision);
                            if($prInsertHistorialTablaProvision->execute()){
                                    /*EVALUAR SI LA FECHA DE LA PROVISION QUE VA A CARGAR YA EXISTE EN EL HISTORICO DE PROVISIONES, ESTO ES PARA REEMPLAZAR LOS ACTUALES DATOS*/
                                    $sqlExistProvisionHistorico = "UPDATE ca_historico_provision_total SET estado = 0 WHERE date_provision ='".$fechaProvisionTotal."'";
                                    $prExistProvisionHistorico=$connection->prepare($sqlExistProvisionHistorico);
                                    $prExistProvisionHistorico->execute();

                                    $sqlInsertHistorialProvision = "INSERT INTO ca_historico_provision_total(codcen,provisionPositiva,provisitonPosiNega,hipotecarioPosiNega,hipotecarioPositivo,estado,idcliente_cartera,idcartera,date_provision,fecha_creacion,usuario_creacion,fecha_provision,fecha_provision_total,idhistorial_tabla_provision_total) 
                                                                    (SELECT 
                                                                        COD_CEN AS 'codcen', 
                                                                        ( SELECT SUM(tmp2.TOTAL_VARIACION_PROVISION) FROM ". $nombreTemporal." tmp2 WHERE tmp2.COD_CEN=tmp.COD_CEN AND tmp2.TOTAL_VARIACION_PROVISION>0  ) AS 'provisionPositiva',
                                                                        ( SELECT SUM(tmp4.TOTAL_VARIACION_PROVISION) FROM ". $nombreTemporal." tmp4 WHERE tmp4.COD_CEN=tmp.COD_CEN) AS 'provisitonPosiNega',
                                                                        ( SELECT SUM(tmp1.HIPOTECARIO) FROM ". $nombreTemporal." tmp1 WHERE tmp1.COD_CEN=tmp.COD_CEN) AS 'hipotecarioPosiNega',
                                                                        ( SELECT SUM(tmp3.HIPOTECARIO) FROM ". $nombreTemporal." tmp3 WHERE tmp3.COD_CEN=tmp.COD_CEN AND tmp3.HIPOTECARIO>0  ) AS 'hipotecarioPositivo',

                                                                        '1' AS 'estado',
                                                                        tmp.idcliente_cartera, 
                                                                        tmp.idcartera,
                                                                        '". $fechaProvisionTotal ."',
                                                                        now(),
                                                                        ". $usuarioCreacion . ",
                                                                        CASE
                                                                            WHEN tmp.TOTAL_VARIACION_PROVISION > 0 THEN CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(', DAYOFMONTH('". $fechaProvisionTotal ."'), ')' )
                                                                            ELSE NULL
                                                                            END AS  'fecha_provision',
                                                                        CASE
                                                                            WHEN tmp.TOTAL_VARIACION_PROVISION <= 0 THEN CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(', DAYOFMONTH('". $fechaProvisionTotal ."'), ')' )
                                                                            ELSE NULL
                                                                            END AS  'fecha_provision_total',

                                                                        (SELECT MAX(idhistorial_tabla_provision_total) FROM ca_historial_tabla_provision_total) AS 'idhistorial_tabla_provision_total'

                                                                    FROM ". $nombreTemporal. "  tmp
                                                                    WHERE tmp.idcartera is not null  and tmp.estado=1
                                                                    GROUP BY tmp.COD_CEN
                                                                    )";
                                    $prInsertHistorialProvision = $connection->prepare($sqlInsertHistorialProvision);
                                    if($prInsertHistorialProvision->execute()){
                                        /*verificar si es la primera vez que se sube archivo de provisionTotal*/
                                    $sqlCountTablaProvisionTotal = "SELECT count(*) AS 'COUNT' from ca_historial_tabla_provision_total";
                                    $prCountTablaProvisionTotal = $connection->prepare($sqlCountTablaProvisionTotal);
                                    $prCountTablaProvisionTotal->execute();
                                    $arrayCountTablaProvisionTotal = $prCountTablaProvisionTotal->fetchAll(PDO::FETCH_ASSOC);
                                    $countTablaProvisionTotal = $arrayCountTablaProvisionTotal[0]['COUNT'];
                                    if($countTablaProvisionTotal>1){
                                        /*<!>sacar el la penultima tabla de provision cargada<¡>*/
                                        $sqlGetPenultimaProvisionCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total where estado = 1  
                                                                            ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2";
                                        $prGetPenultimaProvisionCargada=$connection->prepare($sqlGetPenultimaProvisionCargada);
                                        $prGetPenultimaProvisionCargada->execute();
                                        $penultimaProvision=$prGetPenultimaProvisionCargada->fetchAll(PDO::FETCH_ASSOC);

                                        $namePenultimaProvision=$penultimaProvision[1]['nombre_tabla'];
                                        $diaPenultimaProvision=$penultimaProvision[1]['DIA'];
                                        $idHistorialTablaProvisionPenultima=$penultimaProvision[1]['idhistorial_tabla_provision_total'];

                                        /*sacar la utlima provision cargada*/
                                        $sqlGetUltimaProvisionCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total where estado = 1  
                                                                            ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2";

                                        $prGetUltimaProvisionCargada = $connection->prepare($sqlGetUltimaProvisionCargada);
                                        $prGetUltimaProvisionCargada->execute();
                                        $ultimaProvision = $prGetUltimaProvisionCargada->fetchAll(PDO::FETCH_ASSOC);

                                        $nameUltimaProvision = $ultimaProvision[0]['nombre_tabla'];
                                        $diaUltimaProvision = $ultimaProvision[0]['DIA'];
                                        $idHistorialTablaProvisionUltima = $ultimaProvision[0]['idhistorial_tabla_provision_total'];


                                        /*  LOS CLIENTES DE LA PENULTIMA PROVISION QUE DEJARON DE PROVISIONAR EN LA ULTIMA PROVISION */
                                        $sqlUpdateHistorialIsRetirado= " UPDATE ca_historico_provision_total AS  hisprotot 
                                                                         INNER JOIN (
                                                                                select DISTINCT C.DEJO_PROVISONAR ,'".$idHistorialTablaProvisionPenultima."' AS 'idhistorial_tabla_provision_total' FROM (   /*ESTO SE ACTUALIZARIA EN EL HSITORIAL ; SE APLICARIA POR EL PENULTIMO IDHISTORIAL_TABLA_PROVISION_TOTAL*/
                                                                                select 
                                                                                    CASE
                                                                                        WHEN  A.COD_CEN is null THEN B.COD_CEN
                                                                                        ELSE null
                                                                                    END AS 'DEJO_PROVISONAR' FROM (
                                                                                    select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN
                                                                                ) AS A -- ultimo
                                                                                RIGHT JOIN 
                                                                                (
                                                                                    select distinct COD_CEN 
                                                                                    from 
                                                                                    (
                                                                                       ".$namePenultimaProvision."
                                                                                    )
                                                                                     where idcartera is not null group by COD_CEN   -- penulitmo
                                                                                )AS B ON B.COD_CEN=A.COD_CEN
                                                                                ) AS C  WHERE C.DEJO_PROVISONAR IS NOT NULL
                                                                            ) AS b ON b.DEJO_PROVISONAR=hisprotot.codcen and b.idhistorial_tabla_provision_total=hisprotot.idhistorial_tabla_provision_total
                                                                        SET hisprotot.isRetirado = 1 ,hisprotot.idmotivo_retiro_provtot=2, hisprotot.fecha_retirado = CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(', ".$diaPenultimaProvision.", ')' )  
                                                                        WHERE hisprotot.estado=1 AND hisprotot.isRetirado is null AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionPenultima ;

                                        $prUpdateHistorialIsRetirado= $connection->prepare( $sqlUpdateHistorialIsRetirado );
                                        if($prUpdateHistorialIsRetirado->execute()){
                                            /* PA VER SI LA PROVISION ESTA RETIRADA O NO | SI APARECE COMO 0 NO ESTA ACTIVA ; SI 1 ESTA ACTIVADA */
                                            $sqlUpdateHistorialretirado2 = " UPDATE ca_historico_provision_total AS hisprotot 
                                                                             INNER JOIN 
                                                                            (
                                                                                SELECT A.COD_CEN, B.statusCLIENTE, '".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total'  from (  
                                                                                    select * from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN 
                                                                                )AS A
                                                                                INNER JOIN 
                                                                                ( 
                                                                                    SELECT
                                                                                            cli.codigo AS codcent,
                                                                                            cli_car.estado as statusCLIENTE
                                                                                            
                                                                                        FROM ca_cliente cli
                                                                                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                                        WHERE cuen.idcartera IN (".$idCarteras.") ORDER BY cli.codigo desc   
                                                                                )AS B ON A.COD_CEN=B.codcent group by A.COD_CEN 
                                                                            )AS C ON C.COD_CEN=hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total=C.idhistorial_tabla_provision_total 

                                                                            SET hisprotot.isRetirado = 1  , hisprotot.idmotivo_retiro_provtot=1, hisprotot.fecha_retirado = CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )  
                                                                            WHERE hisprotot.estado=1 AND hisprotot.isRetirado is null AND C.statusCliente = 0 AND hisprotot.idhistorial_tabla_provision_total=".$idHistorialTablaProvisionUltima ;  

                                            $prUpdateHistorialretirado2=$connection->prepare($sqlUpdateHistorialretirado2);
                                            if( $prUpdateHistorialretirado2->execute() ){
                                                $sqlUpdateNuevoIngreso = " UPDATE  ca_historico_provision_total AS hisprotot 
                                                                            INNER JOIN (
                                                                                select DISTINCT C.nuevo_ingreso ,'".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total' FROM (  
                                                                                    select 
                                                                                            CASE WHEN B.COD_CEN IS NULL THEN A.COD_CEN
                                                                                            ELSE null   
                                                                                            END AS 'nuevo_ingreso'            
                                                                                      FROM (
                                                                                                  select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN  -- 3347
                                                                                            ) AS A
                                                                                    LEFT JOIN
                                                                                    (
                                                                                            select distinct COD_CEN 
                                                                                            from
                                                                                            (
                                                                                                 ".$namePenultimaProvision."
                                                                                            )
                                                                                             where idcartera is not null group by COD_CEN -- penulitmo
                                                                                    ) AS B ON B.COD_CEN=A.COD_CEN
                                                                                ) AS C  WHERE C.nuevo_ingreso IS NOT NULL

                                                                            ) AS D ON D.nuevo_ingreso = hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total= D.idhistorial_tabla_provision_total

                                                                             SET hisprotot.status = 'nuevo_ingreso' 

                                                                             WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima ;

                                                $prUpdateNuevoIngreso = $connection->prepare($sqlUpdateNuevoIngreso);
                                                if($prUpdateNuevoIngreso->execute()){
                                                    $sqlUpdateClienContinuos = " UPDATE ca_historico_provision_total AS hisprotot
                                                                                SET hisprotot.status='continuo'
                                                                               WHERE hisprotot.estado = 1 AND hisprotot.status IS NULL AND hisprotot.idhistorial_tabla_provision_total= ". $idHistorialTablaProvisionUltima ;
                                                    $prUpdateClienContinuos = $connection->prepare($sqlUpdateClienContinuos);
                                                    if($prUpdateClienContinuos->execute()){
                                                        $sqlUpdateFechaNuevIngresoPosi="UPDATE  ca_historico_provision_total AS hisprotot 
                                                                                            INNER JOIN (
                                                                                                Select G.nuevo_ingreso,G.idhistorial_tabla_provision_total,tmp6.TOTAL_VARIACION_PROVISION from (
                                                                                                    select DISTINCT C.nuevo_ingreso ,'".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total' FROM (  
                                                                                                            select 
                                                                                                                            CASE WHEN B.COD_CEN IS NULL THEN A.COD_CEN
                                                                                                                            ELSE null  
                                                                                                                            END AS 'nuevo_ingreso'             
                                                                                                                FROM (
                                                                                                                            
                                                                                                                                        select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN  -- 3347
                                                                                                                            ) AS A
                                                                                                            LEFT JOIN
                                                                                                            (
                                                                                                                            select distinct COD_CEN 
                                                                                                                            from
                                                                                                                            (
                                                                                                                                     ".$namePenultimaProvision."
                                                                                                                            )
                                                                                                                             where idcartera is not null group by COD_CEN -- penulitmo
                                                                                                            ) AS B ON B.COD_CEN=A.COD_CEN
                                                                                                    ) AS C  WHERE C.nuevo_ingreso IS NOT NULL
                                                                                                )AS G 
                                                                                                INNER JOIN (select tmp.COD_CEN , SUM(tmp.TOTAL_VARIACION_PROVISION)AS 'TOTAL_VARIACION_PROVISION'  FROM ". $nameUltimaProvision." tmp  where COD_CEN is not null GROUP BY tmp.COD_CEN) tmp6 ON tmp6.COD_CEN=G.nuevo_ingreso WHERE tmp6.TOTAL_VARIACION_PROVISION>=0 GROUP BY G.nuevo_ingreso ORDER BY G.nuevo_ingreso ASC 

                                                                                            ) AS D ON D.nuevo_ingreso = hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total= D.idhistorial_tabla_provision_total

                                                                                             SET hisprotot.fecha_ingreso= CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )

                                                                                             WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima ;

                                                        $prUpdateFechaNuevIngresoPosi = $connection->prepare($sqlUpdateFechaNuevIngresoPosi);
                                                        if($prUpdateFechaNuevIngresoPosi->execute()){
                                                            $sqlUpdateFechaNuevIngresoNega = "UPDATE  ca_historico_provision_total AS hisprotot 
                                                                                        INNER JOIN (
                                                                                            Select G.nuevo_ingreso,G.idhistorial_tabla_provision_total,tmp6.TOTAL_VARIACION_PROVISION from (
                                                                                                select DISTINCT C.nuevo_ingreso ,'".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total' FROM (  
                                                                                                        select 
                                                                                                                        CASE WHEN B.COD_CEN IS NULL THEN A.COD_CEN
                                                                                                                        ELSE null  
                                                                                                                        END AS 'nuevo_ingreso'             
                                                                                                            FROM (
                                                                                                                        
                                                                                                                                    select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN  -- 3347
                                                                                                                        ) AS A
                                                                                                        LEFT JOIN
                                                                                                        (
                                                                                                                        select distinct COD_CEN 
                                                                                                                        from
                                                                                                                        (
                                                                                                                                 ".$namePenultimaProvision."
                                                                                                                        )
                                                                                                                         where idcartera is not null group by COD_CEN -- penulitmo
                                                                                                        ) AS B ON B.COD_CEN=A.COD_CEN
                                                                                                ) AS C  WHERE C.nuevo_ingreso IS NOT NULL
                                                                                            )AS G 
                                                                                            INNER JOIN (select tmp.COD_CEN , SUM(tmp.TOTAL_VARIACION_PROVISION)AS 'TOTAL_VARIACION_PROVISION'  FROM ". $nameUltimaProvision." tmp  where COD_CEN is not null GROUP BY tmp.COD_CEN) tmp6 ON tmp6.COD_CEN=G.nuevo_ingreso WHERE tmp6.TOTAL_VARIACION_PROVISION<0 GROUP BY G.nuevo_ingreso ORDER BY G.nuevo_ingreso ASC 

                                                                                        ) AS D ON D.nuevo_ingreso = hisprotot.codcen  AND hisprotot.idhistorial_tabla_provision_total= D.idhistorial_tabla_provision_total

                                                                                         SET hisprotot.fecha_ingreso_total = CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )

                                                                                         WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima ;

                                                            $prUpdateFechaNuevIngresoNega = $connection->prepare($sqlUpdateFechaNuevIngresoNega);
                                                            if($prUpdateFechaNuevIngresoNega->execute()){
                                                                 echo json_encode(array('rst' => true, 'msg' => 'cargado a BD'));
                                                            }else{
                                                                 echo json_encode(array('rst' => false, 'msg' => 'ultimo proceso no finalizado'));
                                                            }
                                                        }else{
                                                             echo json_encode(array('rst' => false, 'msg' => 'no se actualizo la fecha de de ingresos positivos'));
                                                        }   
                                                    }else{
                                                         echo json_encode(array('rst' => false, 'msg' => 'no se actualizo fecha de ingreso posi'));
                                                    }   
                                                 }else{
                                                     echo json_encode(array('rst' => true, 'msg' => 'No se actualizaron los  ingresos continuos'));
                                                 }
                                            }else{
                                                echo json_encode(array('rst' => false, 'msg' => 'no se hizo el segundo update para retirar provision'));
                                            }
                                        }else{
                                            echo json_encode(array('rst' => false, 'msg' => 'no se hizo el primer update para retirar provision'));                                    
                                        }  
                                    }else{//condicion <=0
                                        $sqlGetUltimaProvisionCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 ";
                                        $prGetUltimaProvisionCargada = $connection->prepare($sqlGetUltimaProvisionCargada);
                                        $prGetUltimaProvisionCargada->execute();
                                        $ultimaProvision = $prGetUltimaProvisionCargada->fetchAll(PDO::FETCH_ASSOC);

                                        $nameUltimaProvision = $ultimaProvision[0]['nombre_tabla'];
                                        $diaUltimaProvision = $ultimaProvision[0]['DIA'];
                                        $idHistorialTablaProvisionUltima = $ultimaProvision[0]['idhistorial_tabla_provision_total'];

                                        $sqlUpdateHistorialretirado2 = " UPDATE ca_historico_provision_total AS hisprotot 
                                                                         INNER JOIN 
                                                                        (
                                                                            SELECT A.COD_CEN, B.statusCLIENTE, '".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total'  from (  
                                                                                select * from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN 
                                                                            )AS A
                                                                            INNER JOIN 
                                                                            ( 
                                                                                SELECT
                                                                                        cli.codigo AS codcent,
                                                                                        cli_car.estado as statusCLIENTE
                                                                                        
                                                                                    FROM ca_cliente cli
                                                                                        INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                                        INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                                        INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                                    WHERE cuen.idcartera IN (".$idCarteras.") ORDER BY cli.codigo desc   
                                                                            )AS B ON A.COD_CEN=B.codcent group by A.COD_CEN 
                                                                        )AS C ON C.COD_CEN=hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total=C.idhistorial_tabla_provision_total 

                                                                        SET hisprotot.isRetirado = 1  , hisprotot.idmotivo_retiro_provtot=1, hisprotot.fecha_retirado = CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )  
                                                                        WHERE hisprotot.estado=1 AND hisprotot.isRetirado is null AND C.statusCliente = 0 AND hisprotot.idhistorial_tabla_provision_total=".$idHistorialTablaProvisionUltima ;  

                                        $prUpdateHistorialretirado2=$connection->prepare($sqlUpdateHistorialretirado2);
                                        if( $prUpdateHistorialretirado2->execute() ){
                                            $sqlUpdateNuevoIngreso = " UPDATE  ca_historico_provision_total AS hisprotot 
                                                                        
                                                                         SET hisprotot.status = 'nuevo_ingreso' 

                                                                         WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima ;

                                            $prUpdateNuevoIngreso = $connection->prepare($sqlUpdateNuevoIngreso);
                                            if($prUpdateNuevoIngreso->execute()){
                                                $sqlUpdateFechaNuevIngresoPosi="UPDATE  ca_historico_provision_total AS hisprotot 
                                                                                        INNER JOIN (
                                                                                                Select G.nuevo_ingreso,G.idhistorial_tabla_provision_total,SUM(tmp6.TOTAL_VARIACION_PROVISION) AS 'TOTAL_VARIACION_PROVISION' from (
                                                                                                    select DISTINCT C.nuevo_ingreso ,'".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total' FROM (  
                                                                                                        select 
                                                                                                             A.COD_CEN AS 'nuevo_ingreso'             
                                                                                                            FROM (                                                       
                                                                                                                 select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN  -- 3347
                                                                                                            ) AS A
                                                                                                         ) AS C  WHERE C.nuevo_ingreso IS NOT NULL
                                                                                                )AS G 
                                                                                                INNER JOIN (select tmp.COD_CEN , SUM(tmp.TOTAL_VARIACION_PROVISION)AS 'TOTAL_VARIACION_PROVISION'  FROM ". $nameUltimaProvision." tmp  where COD_CEN is not null GROUP BY tmp.COD_CEN) tmp6 ON tmp6.COD_CEN=G.nuevo_ingreso WHERE tmp6.TOTAL_VARIACION_PROVISION>=0 GROUP BY G.nuevo_ingreso ORDER BY G.nuevo_ingreso ASC

                                                                                        ) AS D ON D.nuevo_ingreso = hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total= D.idhistorial_tabla_provision_total

                                                                                         SET hisprotot.fecha_ingreso= CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )

                                                                                         WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima;

                                                $prUpdateFechaNuevIngresoPosi = $connection->prepare($sqlUpdateFechaNuevIngresoPosi);
                                                if($prUpdateFechaNuevIngresoPosi->execute()){
                                                    $sqlUpdateFechaNuevIngresoNega = "UPDATE  ca_historico_provision_total AS hisprotot 
                                                                                    INNER JOIN (
                                                                                                Select G.nuevo_ingreso,G.idhistorial_tabla_provision_total,SUM(tmp6.TOTAL_VARIACION_PROVISION) AS 'TOTAL_VARIACION_PROVISION' from (
                                                                                                    select DISTINCT C.nuevo_ingreso ,'".$idHistorialTablaProvisionUltima."' AS 'idhistorial_tabla_provision_total' FROM (  
                                                                                                        select 
                                                                                                             A.COD_CEN AS 'nuevo_ingreso'             
                                                                                                            FROM (                                                       
                                                                                                                 select distinct COD_CEN from ". $nameUltimaProvision." where idcartera is not null group by COD_CEN  -- 3347
                                                                                                            ) AS A
                                                                                                         ) AS C  WHERE C.nuevo_ingreso IS NOT NULL
                                                                                                )AS G 
                                                                                                INNER JOIN (select tmp.COD_CEN , SUM(tmp.TOTAL_VARIACION_PROVISION)AS 'TOTAL_VARIACION_PROVISION'  FROM ". $nameUltimaProvision." tmp  where COD_CEN is not null GROUP BY tmp.COD_CEN) tmp6 ON tmp6.COD_CEN=G.nuevo_ingreso WHERE tmp6.TOTAL_VARIACION_PROVISION<0 GROUP BY G.nuevo_ingreso ORDER BY G.nuevo_ingreso ASC

                                                                                             ) AS D ON D.nuevo_ingreso = hisprotot.codcen AND hisprotot.idhistorial_tabla_provision_total= D.idhistorial_tabla_provision_total

                                                                                     SET hisprotot.fecha_ingreso_total= CONCAT( 'FOTO(' ,DAYOFMONTH(now()), ') - LIST(',DAYOFMONTH('". $fechaProvisionTotal ."'),')' )

                                                                                     WHERE hisprotot.estado=1 AND hisprotot.idhistorial_tabla_provision_total=". $idHistorialTablaProvisionUltima;

                                                    $prUpdateFechaNuevIngresoNega = $connection->prepare($sqlUpdateFechaNuevIngresoNega);
                                                    if($prUpdateFechaNuevIngresoNega->execute()){
                                                        echo json_encode(array('rst' => true, 'msg' => 'cargado a BD'));
                                                    }else{
                                                        echo json_encode(array('rst' => false, 'msg' => 'ultimo proceso no finalizado'));
                                                    }
                                                }else{
                                                     echo json_encode(array('rst' => false, 'msg' => 'no se actualizo la fecha de de ingresos positivos'));
                                                }
                                            }else{
                                                echo json_encode(array('rst'=>false, 'msg'=>'No se realizo el proceso prUpdateNuevoIngreso'));
                                            }
                                        }else{
                                            echo json_encode(array('rst'=>false, 'msg'=> 'No se realizo proceso prUpdateHistorialretirado2' ));
                                        }
                                    }//fin condicion <=0     
                            }else{
                                echo json_encode(array('rst'=> false , 'msg'=> 'No se inserto en el historial de provision total'));
                            }
                        }else{
                            echo json_encode(array('rst'=>false, 'msg'=>'No se inserto en el historial Tabla de provision total'));
                            }
                    }else{
                        echo json_encode(array('rst'=> false, 'msg' => 'No actualizo el idcliente y idcliente_cartera'));
                    }
                 }else{
                    echo json_encode(array('rst'=> false , 'msg'=> 'No se actualizo el estado por motivo'));
                 }
            }else{
                echo json_encode(array('rst'=> false , 'msg'=>'No se actualizo el estado a 1'));
            } 
        }else{
            echo json_encode(array('rst' => true, 'msg' => 'NO cargado a BD'));
        }
        }
}
public function validarArchivoProvisionTotal($dest,$nombre_archivo){
   $path = $dest.$nombre_archivo;
        if (!file_exists($path)) {
            return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
            exit();
        }

        $tmp_file = "TMP" . session_id() . rand(1, 1000) . $nombre_archivo;

        $tmpArchivo = @fopen($dest . $tmp_file, 'w');

        if (!$tmpArchivo) {
            return array('rst' => false, 'msg' => 'Problemas al crear archivo temporal');
            exit();
        }

        $carteraArchivo = @fopen($path, 'r+');
        if ($carteraArchivo) {
            $count = 0;
            while (!feof($carteraArchivo)) {
                $linea = fgets($carteraArchivo);
                if ($count == 0) {
                    $buscar = array("Ã ", "Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã", "Ã", "Ã­", "Ã¬", "Ã?", "Ã", "Ã³", "Ã²", "Ã", "Ã", "Ãº", "Ã¹", "Ã", "Ã", ".", "#", " ", "/", "Ã±", "Ã", "@", "(", ")", "$", "&", "%","'", '"', "?", "Â¿", "!", "Â¡", "[", "]", "-", "Â¥");
                    $cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_","", '', "", "", "", "", "", "", "", "N");
                    //$buscar=array("Ã ","Ã¡","Ã","Ã?","Ã©","Ãš","Ã","Ã","Ã­","Ã¬","Ã?","Ã","Ã³","Ã²","Ã","Ã","Ãº","Ã¹","Ã","Ã",".","#"," ","/","Ã±","Ã","@","(",")","$","&","%","\t","'",'"',"?","Â¿","!","Â¡","[","]");
                    //$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
                    $line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    $cabeceras = explode("\t", $line);
                    for ($i = 0; $i < count($cabeceras); $i++) {
                        if (trim($cabeceras[$i]) == '') {
                            fclose($tmpArchivo);
                            fclose($carteraArchivo);
                            echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
                            exit();
                        }
                    }
                    fwrite($tmpArchivo, $line . "\r\n");
                } else {
                    $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", ",", "Â¥");
                    $cambia = array('', "", "", "", "", "", "", "", "", "N");
                    $line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
                    fwrite($tmpArchivo, $line_c . "\r\n");
                }
                $count++;
            }
        }
            fclose($carteraArchivo);
            fclose($tmpArchivo);
            //echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
            return array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file);
}
public function generateCargaFacturacion($_post){
    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    $time=date("Y_m_d_H_i_s");
    
    $sqlCreateTableTmpEstCu = " CREATE TEMPORARY TABLE tmp_carga_facturacion_" . $time . " (
        CONTRATO VARCHAR(45),
        CODCENT VARCHAR(45),
        OFICINA VARCHAR(250),
        NOMB_OF VARCHAR(250),
        TERRITORIO VARCHAR(250),
        NOMBRE VARCHAR(250),
        AGENCIA VARCHAR(250),
        SUBPROD VARCHAR(250),
        NOMB_PROD VARCHAR(250),
        TIPDOC VARCHAR(45),
        NRODOC VARCHAR(45),
        TPERSONA VARCHAR(45),
        TRAMO VARCHAR(45),
        MARCA_PAGO VARCHAR(45),
        AGENCIA2 VARCHAR(200),
        AGENCIA3 VARCHAR(200),
        TCONTACTO3 VARCHAR(200),
        TCON3 VARCHAR(100),
        IMP_PAG3 VARCHAR(50),
        COMISION VARCHAR(50),
        HONORARIO VARCHAR(50),
        IGV VARCHAR(50),
        TOTAL_PAGO VARCHAR(50),
        OF_FACTURA varchar(50),
        idcliente_cartera INT,
        idcartera int,
        idcuenta int,
        INDEX(CONTRATO),
        INDEX(CODCENT),
        INDEX( idcliente_cartera ),
        INDEX( idcartera ),
        INDEX( idcuenta )
        )  ";
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $prcreartemporal=$connection->prepare($sqlCreateTableTmpEstCu);
        if($prcreartemporal->execute()){
        $sqlLoadData = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturacion/" . $_post['NombreServicio'] . "/" . $_post['archivo'] . "'
                         INTO TABLE tmp_carga_facturacion_" . $time." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
        $prLoadData=$connection->prepare($sqlLoadData);
        if($prLoadData->execute()){
            $sqlcuentacliente="UPDATE tmp_carga_facturacion_" . $time." tmp
                               INNER JOIN ca_cuenta cu ON cu.codigo_cliente=tmp.CODCENT and cu.numero_cuenta=tmp.contrato
                               SET tmp.idcuenta=cu.idcuenta,tmp.idcliente_cartera=cu.idcliente_cartera,tmp.idcartera=cu.idcartera
                               WHERE cu.idcartera in (".$_post['idcartera'].")";

            $prcuentacliente=$connection->prepare($sqlcuentacliente);
            if($prcuentacliente->execute()){
                $sqlclientesincuenta="UPDATE tmp_carga_facturacion_" . $time." tmp
                                    INNER JOIN ca_cliente_cartera clicar ON tmp.CODCENT=clicar.codigo_cliente
                                    SET tmp.idcliente_cartera=clicar.idcliente_cartera,tmp.idcartera=clicar.idcartera
                                    WHERE clicar.idcartera IN (".$_post['idcartera'].")";
                $prclientesincuenta=$connection->prepare($sqlclientesincuenta);

                if($prclientesincuenta->execute()){
                    $sqlinsertfactura="INSERT INTO ca_factura(CONTRATO,CODCENT,OFICINA,NOMB_OF,TERRITORIO,NOMBRE,AGENCIA,SUBPROD,NOMB_PROD,TIPDOC,NRODOC,TPERSONA,TRAMO,MARCA_PAGO,AGENCIA2,AGENCIA3,TCONTACTO3,TCON3,IMP_PAG3,COMISION,HONORARIO,IGV,TOTAL_PAGO,OF_FACTURA,idcliente_cartera,idcartera,idcuenta,fecha_creacion,usuario_creacion) 
                                        (SELECT *,NOW(),".$_post['UsuarioCreacion']." FROM tmp_carga_facturacion_" . $time." WHERE CODCENT IS NOT NULL)";
                    
                    $prinsertfactura=$connection->prepare($sqlinsertfactura);
                    if($prinsertfactura->execute()){

                        echo json_encode(array('rst'=>true,'msg'=>'Se cargo Satisfactoriamente'));   

                    }else{
                        echo json_encode(array('rst'=>false,'msg'=>'Error en carga'));                
                    }
                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'Error en clientes sin cuenta'));                
                }
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error en actualizar clientes con cuentas'));
            }

        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error en Load Data'));            
        }

        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error en crear temporal'));
        }
}
public function generateCargaProvision($_post){
    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    $time=date("Y_m_d_H_i_s");   
    $sqlCreateTableTmpEstCu = " CREATE TABLE tmp_carga_provision_" . $time . " (
            CODCENT VARCHAR(45),
            PROVISION VARCHAR(20),
            CLASIFICACION VARCHAR(255),
            idcliente_cartera int,
            idcartera int,
            index(CODCENT),
            index(idcliente_cartera),
            index(idcartera))";
    $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
    $prcreartemporal=$connection->prepare($sqlCreateTableTmpEstCu);
    if($prcreartemporal->execute()){     
        $sqlLoadData = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/provision/" . $_post['NombreServicio'] . "/" . $_post['archivo'] . "'
                         INTO TABLE tmp_carga_provision_" . $time." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
        $prLoadData=$connection->prepare($sqlLoadData);
        if($prLoadData->execute()){
            $sqlupdatecliente="UPDATE tmp_carga_provision_" . $time." tmp
                                INNER JOIN ca_cliente_cartera clicar on clicar.codigo_cliente=tmp.CODCENT
                                SET tmp.idcliente_cartera=clicar.idcliente_cartera,tmp.idcartera=clicar.idcartera
                                WHERE clicar.idcartera IN (".$_post['idcartera'].")";
            $prupdatecliente=$connection->prepare($sqlupdatecliente);
            if($prupdatecliente->execute()){
                $sqlinserthistorico="INSERT INTO ca_historico_provision(codcent,provision,clasificacion,idcliente_cartera,idcartera,fecha_creacion,usuario_creacion)
                                    (SELECT CODCENT,PROVISION,CLASIFICACION,idcliente_cartera,idcartera,now(),".$_post['UsuarioCreacion']." FROM tmp_carga_provision_" . $time." WHERE idcliente_cartera IS NOT NULL)";
                $prinserthistorico=$connection->prepare($sqlinserthistorico);
                if($prinserthistorico->execute()){
                    $sqlborradeuda="UPDATE ca_cliente_cartera 
                                    SET deuda=0
                                    WHERE idcartera in (".$_post['idcartera'].")";
                    $prborrardeuda=$connection->prepare($sqlborradeuda);
                    if($prborrardeuda->execute()){
                        $sqlupdateprovision="UPDATE (SELECT idcliente_cartera,SUM(provision) as provision FROM tmp_carga_provision_".$time." WHERE idcliente_cartera is not null GROUP BY idcliente_cartera) tmp
                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=tmp.idcliente_cartera
                                            SET clicar.deuda=tmp.provision
                                            WHERE clicar.idcartera IN (".$_post['idcartera'].") AND tmp.idcliente_cartera is NOT NULL";
                        $prupdateprovision=$connection->prepare($sqlupdateprovision);
                        if($prupdateprovision->execute()){
                            echo json_encode(array('rst'=>true,'msg'=>'Se cargo Satisfactoriamente'));  
                        }else{
                            echo json_encode(array('rst'=>false,'msg'=>'Erroe en provision'));                              
                        }                    
                    }else{
                        echo json_encode(array('rst'=>false,'msg'=>'error en actualizar provision en cliente_cartera'));  
                    }
                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'error al insertar historico'));  
                }
            }else{
                echo json_encode(array('rst'=>false,'msg'=>'Error actualziar cliente'));                  
            }
        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error en Load Data'));
        }        
    }else{
        echo json_encode(array('rst'=>false,'msg'=>'Error en crear temporal'));
    }       
}
public function uploadFiadores($_post,$_files) {
    $create_hora = date("Ymd_His");
    $nombre_archivo = date("Ymd_His")."_".$_files['uploadFileFiadores']['name'];
    if (@opendir('../documents/fiadores/' . $_post['NombreServicio'])) {
        if (@move_uploaded_file($_files['uploadFileFiadores']['tmp_name'], '../documents/fiadores/' . $_post['NombreServicio'] . '/'.$nombre_archivo)) {

            //~ Limpiar TXT
            $_post['file'] = $nombre_archivo;
            $retornoLimpiar = $this->limpiarTxtSinCabecera($_post, "fiadores", "|");
            if ($retornoLimpiar['rst']) {
                $nombre_archivo = $retornoLimpiar['file'];
            } else {
                echo json_encode(array('rst' => false, 'msg' => $retornoLimpiar['msg']));
                exit();
            }

            $factoryConnection = FactoryConnection::create('mysql');
            $connection = $factoryConnection->getConnection();

            $prFiadorTmp = $connection->prepare("CREATE TEMPORARY TABLE ca_fiadores_tmp_".$create_hora." LIKE ca_fiadores_tmp");
            $prFiadorTmp->execute();

            $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
            $sqlLoadCuota = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/fiadores/".$_post['NombreServicio']
                        ."/".$nombre_archivo."' INTO TABLE ca_fiadores_tmp_".$create_hora." CHARACTER SET utf8 FIELDS TERMINATED BY '|' LINES  TERMINATED BY '\\r\\n' "
                        ."(num_contrato, num_contratogar, tipo_gar, subtipo_gar, mon_gar, imp_gar, sit_gar, @fecha_sit, direcc_inmueblehip, placa_vehiculoprend, cod_centralfiador, nombre_fiador, "
                            ."direcc_fiador, ciudad, cod_postal, provincia, tel_particular, tel_trabajo, tel_movil, tel_4, tel_5) "
                        ."SET fecha_sit = STR_TO_DATE(@fecha_sit,'%d-%m-%Y'), idcartera=".$_post['idcartera'];
            $prLoadCuota = $connection->prepare($sqlLoadCuota);

            if ($prLoadCuota->execute()) {

                $sqlUpdateTmpCuota = "UPDATE ca_fiadores_tmp_".$create_hora." ft INNER JOIN ca_cuenta cu ON ft.num_contrato=cu.numero_cuenta AND ft.idcartera=cu.idcartera "
                                        ."SET ft.idcuenta=cu.idcuenta, ft.idcliente_cartera=cu.idcliente_cartera";
                $prUpdateTmpCuota = $connection->prepare($sqlUpdateTmpCuota);
                if ($prUpdateTmpCuota->execute()) {

                    $prUpdateCuota = $connection->prepare("UPDATE ca_fiadores SET estado=0 WHERE idcartera=".$_post['idcartera']);
                    if ($prUpdateCuota->execute()) {

                        $sqlInsertCuota = "INSERT INTO ca_fiadores (num_contrato, num_contratogar, tipo_gar, subtipo_gar, mon_gar, imp_gar, sit_gar, fecha_sit, direcc_inmueblehip, placa_vehiculoprend, "
                                            ."cod_centralfiador, nombre_fiador, direcc_fiador, ciudad, cod_postal, provincia, tel_particular, tel_trabajo, tel_movil, tel_4, tel_5, idcartera, "
                                            ."idcuenta, idcliente_cartera) "
                                        ."SELECT num_contrato, num_contratogar, tipo_gar, subtipo_gar, mon_gar, imp_gar, sit_gar, fecha_sit, direcc_inmueblehip, placa_vehiculoprend, cod_centralfiador, "
                                            ."nombre_fiador, direcc_fiador, ciudad, cod_postal, provincia, tel_particular, tel_trabajo, tel_movil, tel_4, tel_5, idcartera, idcuenta, idcliente_cartera "
                                        ."FROM ca_fiadores_tmp_".$create_hora;
                        $prInsertCuota = $connection->prepare($sqlInsertCuota);

                        if ($prInsertCuota->execute()) {
                            echo json_encode(array('rst' => true, 'msg' => 'Fiadores cargados Correctamente'));
                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error en Insertar Fiadores'));
                        }

                    } else {
                        echo json_encode(array('rst' => false, 'msg' => 'Error en Actualizar el Estado de Fiadores'));
                    }

                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error en Actualizar Temporal'));
                }

            } else {
                echo json_encode(array('rst' => false, 'msg' => 'Error en LOAD DATA INFILE'));
            }

        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
        }
    } else {
        echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
    }
}
//~ Vic F

// poolpg

    public function subirCargaLlamada($_post,$_files){
        $creacion_hora=date('Y_m_d_H_i_s');
        $nombre_archivo='tmp_llamada_'.$creacion_hora;
        if(@opendir('../documents/llamadas/')){
            if(@move_uploaded_file($_files['btnCargaLlamadas']['tmp_name'], '../documents/llamadas/'.$nombre_archivo.'.txt'))
            echo json_encode(array('rst'=>true,'msg'=>'Archivo Subido Correctamente','archivo'=>$nombre_archivo));
        }else{
            echo json_encode(array('rst'=>false,'msg'=>'Error al abrir carpeta'));
        }
    }

    public function procesarCargaLlamada($usuario,$archivo,$cartera){
        $creacion_hora=date('Y_m_d_H_i_s');
        $nombre_archivo='tmp_carga_llamada_'.$creacion_hora;        
        $sqlcreartabla="CREATE TABLE ".$nombre_archivo." 
                    (
                        empresa VARCHAR(10),
                        ncodigo VARCHAR(255),
                        zona VARCHAR(255),
                        reponsable_zona VARCHAR(255),
                        supervisor VARCHAR(255),
                        codigo_cliente VARCHAR(100),
                        cliente VARCHAR(255),
                        documento VARCHAR(255),
                        td VARCHAR(10),
                        doc VARCHAR(45),
                        ncuenta VARCHAR(255),
                        fecha_doc VARCHAR(255),
                        fecha_vcto VARCHAR(255),
                        mon VARCHAR(255),
                        importe VARCHAR(255),
                        saldo VARCHAR(255),
                        fecha_llamada VARCHAR(10),
                        resultado_contacto VARCHAR(200),
                        tipo_contacto VARCHAR(255),
                        rpta1 VARCHAR(255),
                        rpta2 VARCHAR(255),
                        fecha_cp VARCHAR(10),
                        obs VARCHAR(255),
                        telefono VARCHAR(255),
                        idcontacto INT,
                        idtelefono INT,
                        idfinal INT,
                        idusuario_servicio INT,
                        idcliente_cartera INT,
                        idcartera INT,
                        idcuenta INT,
                        iddetalle_cuenta int,
                        INDEX xtd (td ASC),
                        INDEX xdoc (doc ASC),
                        INDEX xidcontacto (idcontacto ASC),
                        INDEX xidtelefono (idtelefono ASC),
                        INDEX xidfinal (idfinal ASC),
                        INDEX xidusuario_servicio (idusuario_servicio ASC),
                        INDEX xidcliente_cartera (idcliente_cartera ASC),
                        INDEX xidcartera (idcartera ASC),
                        INDEX xidcuenta (idcuenta ASC)
                    )";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $prcreartabla=$connection->prepare($sqlcreartabla);
        if($prcreartabla->execute()){
                $confCobrast=parse_ini_file('../conf/cobrast.ini',true);
                $sqlCargar = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/documents/llamadas/".$archivo.".txt' INTO TABLE ".$nombre_archivo." FIELDS TERMINATED BY '\t' LINES  TERMINATED BY '\r\n' IGNORE 1 LINES SET idcartera=$cartera";           
                // echo($sqlCargar);
                // exit();

                $prLoadData=$connection->prepare($sqlCargar);   
                if($prLoadData->execute()){

                    // UPDATE CLIENTE

                    $sqlupdatecli=" UPDATE 
                                    $nombre_archivo,ca_cliente_cartera,ca_cartera
                                    SET 
                                    $nombre_archivo.idcliente_cartera=ca_cliente_cartera.idcliente_cartera,
                                    $nombre_archivo.idcartera=ca_cartera.idcartera
                                    WHERE 
                                    $nombre_archivo.codigo_cliente=ca_cliente_cartera.codigo_cliente AND 
                                    ca_cartera.estado=1 AND
                                    ca_cartera.idcartera=$cartera AND
                                    ca_cliente_cartera.idcartera=ca_cartera.idcartera";
                    $prupdatecliente=$connection->prepare($sqlupdatecli);
                    if($prupdatecliente->execute()){
                        // UPDATE TELEFONO
                            $sqlupdatetelf="  UPDATE $nombre_archivo,ca_telefono
                                              SET 
                                              $nombre_archivo.idtelefono=IF(ca_telefono.numero='',NULL,ca_telefono.idtelefono)
                                              WHERE 
                                              $nombre_archivo.telefono=ca_telefono.numero AND 
                                              $nombre_archivo.idcartera=ca_telefono.idcartera";

                            $prupdatetelefono=$connection->prepare($sqlupdatetelf);
                            if($prupdatetelefono->execute()){

                                // USUARIO
                                $sqlupdateusu="   UPDATE $nombre_archivo SET $nombre_archivo.idusuario_servicio=94";
                                $prupdateusuario=$connection->prepare($sqlupdateusu);
                                if($prupdateusuario->execute()){
                                    

                                    $sqlcontacto =" UPDATE
                                                    $nombre_archivo,ca_contacto
                                                    SET
                                                    $nombre_archivo.idcontacto=ca_contacto.idcontacto
                                                    WHERE
                                                    ca_contacto.nombre=$nombre_archivo.resultado_contacto";
                                    $prupdatecontacto=$connection->prepare($sqlcontacto);
                                    if($prupdatecontacto->execute()){
                                        $sqlstate ="    UPDATE
                                                        $nombre_archivo,
                                                        (
                                                        SELECT
                                                        fin.idfinal,
                                                        carfin.nombre AS 'CARGA',
                                                        fin.nombre AS 'ESTADO'
                                                        FROM ca_nivel niv
                                                        RIGHT JOIN ca_final fin ON fin.idnivel = niv.idnivel
                                                        INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final 
                                                        INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal
                                                        WHERE 
                                                        finser.idservicio = 1 AND 
                                                        fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='llamada' ) AND 
                                                        finser.estado = 1 AND
                                                        fin.idcarga_final NOT IN (3,7)
                                                        ORDER BY finser.prioridad ASC
                                                        ) AS t1
                                                        SET
                                                        $nombre_archivo.idfinal=t1.idfinal
                                                        WHERE
                                                        $nombre_archivo.tipo_contacto=t1.CARGA AND 
                                                        $nombre_archivo.rpta1=t1.ESTADO";
                                        $prupdatestate=$connection->prepare($sqlstate);
                                        if($prupdatestate->execute()){                                                
                                            $sqlcuenta="    UPDATE $nombre_archivo tmp,ca_cuenta cu
                                                            SET
                                                            tmp.idcuenta=cu.idcuenta
                                                            WHERE
                                                            tmp.empresa=cu.dato3 AND
                                                            tmp.td=cu.dato2 AND
                                                            tmp.doc=cu.numero_cuenta AND
                                                            cu.idcartera=$cartera";
                                            $prupdatcuenta=$connection->prepare($sqlcuenta);
                                            if($prupdatcuenta->execute()){
                                                

                                                $sqliddetallecuenta="   UPDATE $nombre_archivo tmp, ca_detalle_cuenta detcu
                                                                        SET
                                                                        tmp.iddetalle_cuenta=detcu.iddetalle_cuenta
                                                                        WHERE
                                                                        tmp.empresa=detcu.dato2 AND 
                                                                        tmp.td=detcu.dato8 AND
                                                                        tmp.doc=detcu.codigo_operacion AND
                                                                        detcu.idcartera=$cartera";
                                                $prupdatedetcu=$connection->prepare($sqliddetallecuenta);
                                                if($prupdatedetcu->execute()){
                                                    // echo json_encode(array('rst'=>true,'msg'=>'Datos Registrados Correctamente'));

                                                    $sqlinsertcall="    INSERT INTO
                                                                        ca_llamada(
                                                                        fecha,
                                                                        observacion,
                                                                        fecha_cp,
                                                                        idtipo_gestion,
                                                                        idcontacto,
                                                                        idtelefono,
                                                                        idfinal,
                                                                        idusuario_servicio,
                                                                        idcliente_cartera,
                                                                        tipo,
                                                                        idcuenta,
                                                                        estado,
                                                                        fecha_creacion
                                                                        )
                                                                        SELECT
                                                                        fecha_llamada,
                                                                        obs,
                                                                        fecha_cp,
                                                                        2,
                                                                        idcontacto,
                                                                        idtelefono,
                                                                        idfinal,
                                                                        idusuario_servicio,
                                                                        idcliente_cartera,
                                                                        'LL',
                                                                        idcuenta,
                                                                        1,
                                                                        NOW()
                                                                        FROM
                                                                        $nombre_archivo 
                                                                        WHERE 
                                                                        idcliente_cartera IS NOT NULL AND 
                                                                        iddetalle_cuenta IS NOT NULL AND 
                                                                        idcuenta IS NOT NULL AND 
                                                                        idcliente_cartera>0";
                                                    $prinsertcall=$connection->prepare($sqlinsertcall);
                                                    if($prinsertcall->execute()){
                                                        $updateultimallamada="  UPDATE ca_cliente_cartera clicar INNER JOIN 
                                                                                (
                                                                                    SELECT * FROM
                                                                                    (
                                                                                        SELECT 
                                                                                        lla.idcliente_cartera, lla.fecha,lla.idllamada
                                                                                        FROM ca_cliente_cartera clicar 
                                                                                        INNER JOIN ca_llamada lla ON lla.idcliente_cartera= clicar.idcliente_cartera
                                                                                        WHERE 
                                                                                        clicar.idcartera IN ($cartera) AND 
                                                                                        lla.tipo<>'IVR'
                                                                                        ORDER BY lla.idcliente_cartera, lla.fecha DESC 
                                                                                    ) t1 
                                                                                    GROUP BY t1.idcliente_cartera
                                                                                ) tmp ON tmp.idcliente_cartera = clicar.idcliente_cartera
                                                                                SET
                                                                                clicar.id_ultima_llamada = tmp.idllamada
                                                                                WHERE clicar.idcartera IN ($cartera)";
                                                        $prupdateultimallamada=$connection->prepare($updateultimallamada);
                                                        if($prupdateultimallamada->execute()){
                                                            echo json_encode(array('rst'=>true,'msg'=>'Datos Registrados Correctamente'));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }                                
                            }

                        // // LLAMADA
                        //     $sqlupdatella="";
                        // echo json_encode(array('rst'=>true,'msg'=>'Datos Registrados Correctamente'));
                    }


                    
                }else{
                    echo json_encode(array('rst'=>false,'msg'=>'Error al Transferir datos del .txt al tmp de la BD...'));
                }   
        }else{
            echo json_encode(array('rst'=>false,'msg'=>'No creo tabla'));
        }
    }

// poolpg

    public function Listar_Cartera(){
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sqlcartera="SELECT idcartera,nombre_cartera FROM ca_cartera ORDER BY fecha_carga DESC";
        $prsqlcartera=$connection->prepare($sqlcartera);
        if($prsqlcartera->execute()){
            $ar_cartera=$prsqlcartera->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'msg'=>'Consulta Exitosamente','data'=>$ar_cartera));   
        }
    }

}

?>
