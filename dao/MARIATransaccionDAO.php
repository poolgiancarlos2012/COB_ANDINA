<?php

class MARIATransaccionDAO {

    public function UpdateVisitaN ( dto_visita $dtoVisita ) {
        
        $idvisita = $dtoVisita->getId();
        // $idnotificador = $dtoVisita->getIdNotificador();
        $usuario_modificacion = $dtoVisita->getUsuarioModificacion(); 
        $fecha_visita = $dtoVisita->getFechaVisita(); 
        // $fecha_recepcion = $dtoVisita->getFechaRecepcion(); 
        $observacion = $dtoVisita->getObservacion(); 
        $fecha_cp = $dtoVisita->getFechaCp(); 
        $monto_cp = $dtoVisita->getMontoCp(); 
        $idcuenta=$dtoVisita->getIdcuenta();/*jmore201208*/
        $idfinal=$dtoVisita->getIdfinal();/*jmore201208*/

        $sql = " UPDATE ca_visita 
                SET 
                fecha_visita = ?,
                observacion = ?,
                fecha_cp = ?,
                monto_cp = ?,
                usuario_modificacion = ? , 
                idfinal = ? , 
                fecha_modificacion = NOW() 
                WHERE idvisita = ? "; 
        

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $connection->beginTransaction();

        $pr = $connection->prepare( $sql );
        // $pr->bindParam(1,$idnotificador,PDO::PARAM_INT);
        $pr->bindParam(1,$fecha_visita,PDO::PARAM_STR);
        // $pr->bindParam(3,$fecha_recepcion,PDO::PARAM_STR);
        $pr->bindParam(2,$observacion,PDO::PARAM_STR);
        $pr->bindParam(3,$fecha_cp,PDO::PARAM_STR);
        $pr->bindParam(4,$monto_cp);
        $pr->bindParam(5,$usuario_modificacion,PDO::PARAM_INT);
        $pr->bindParam(6,$idfinal,PDO::PARAM_INT);
        $pr->bindParam(7,$idvisita,PDO::PARAM_INT);
        if( $pr->execute() ) {
            $sqlMV="UPDATE ca_cuenta cu inner join 
                    (
                    select * from
                    (
                    select vis.idcuenta, vis.fecha_visita, fin.idcarga_final, vis.idfinal, vis.fecha_cp, vis.observacion, vis.idusuario_servicio, vis.iddireccion, vis.idnotificador , finser.peso 
                    from ca_cliente_cartera clicar inner join ca_visita vis inner join ca_final fin inner join ca_final_servicio finser 
                    on finser.idfinal = fin.idfinal and fin.idfinal = vis.idfinal and vis.idcliente_cartera = clicar.idcliente_cartera
                    where vis.idcuenta=?
                    order by vis.idcuenta, finser.peso desc 
                    ) t1 group by t1.idcuenta 
                    ) tmp
                    on tmp.idcuenta = cu.idcuenta 
                    set
                    cu.mv_fecha = tmp.fecha_visita,
                    cu.mv_carga = tmp.idcarga_final,
                    cu.mv_estado = tmp.idfinal,
                    cu.mv_fcpg = tmp.fecha_cp,
                    cu.mv_observacion = tmp.observacion,
                    cu.mv_operador = tmp.idusuario_servicio,
                    cu.mv_direccion = tmp.iddireccion,
                    cu.mv_notificador = tmp.idnotificador,
                    cu.mv_peso_estado = tmp.peso
                    where cu.idcuenta=?"; 
            $prMV=$connection->prepare($sqlMV);
            $prMV->bindParam(1,$idcuenta,PDO::PARAM_INT);
            $prMV->bindParam(2,$idcuenta,PDO::PARAM_INT);                       
            if($prMV->execute()){
                $sqlUV="UPDATE ca_cuenta cu inner join 
                        (
                        select * from
                        (
                        select vis.idcuenta, vis.fecha_visita, fin.idcarga_final, vis.idfinal, vis.fecha_cp, vis.observacion, vis.idusuario_servicio, vis.idnotificador, vis.iddireccion
                        from ca_cliente_cartera clicar inner join ca_visita vis inner join ca_final fin on fin.idfinal = vis.idfinal and vis.idcliente_cartera = clicar.idcliente_cartera
                        where vis.idcuenta=?
                        order by vis.idcuenta, vis.fecha_visita desc 
                        ) t1 group by t1.idcuenta 
                        ) tmp
                        on tmp.idcuenta = cu.idcuenta 
                        set
                        cu.uv_fecha = tmp.fecha_visita,
                        cu.uv_carga = tmp.idcarga_final,
                        cu.uv_estado = tmp.idfinal,
                        cu.uv_fcpg = tmp.fecha_cp,
                        cu.uv_observacion = tmp.observacion,
                        cu.uv_operador = tmp.idusuario_servicio,
                        cu.uv_direccion = tmp.iddireccion,
                        cu.uv_notificador = tmp.idnotificador
                        where cu.idcuenta=?" ;   
                $prUV=$connection->prepare($sqlUV);        
                $prUV->bindParam(1,$idcuenta,PDO::PARAM_INT);
                $prUV->bindParam(2,$idcuenta,PDO::PARAM_INT);                                      
                if($prUV->execute()){
                    $connection->commit();
                    return true;
                } else{
                    $connection->rollBack();
                    return false;    
                }
            }else{
                $connection->rollBack();
                return false;
            }
        }else{
            $connection->rollBack();
            return false;
        }
        
    }
    
    
    public function UpdateState ( dto_llamada $dtoLLamada,$cartera,$cliente_cartera ) {
    // public function UpdateState ( $idllamada,$idfinal,$usuario_modificacion,$fechacp,$montocp,$observaciones,$cartera,$cliente_cartera ) {
     // $idllamada,$idfinal,$usuario_modificacion,$fechacp,$montocp,$observaciones
        $idllamada = $dtoLLamada->getId();
        $idfinal = $dtoLLamada->getIdFinal();
        $usuario_modificacion = $dtoLLamada->getUsuarioModificacion();
        $fechacp=$dtoLLamada->getFechaCp();
        $montocp=$dtoLLamada->getMontoCp();
        $observaciones=$dtoLLamada->getObservaciones();
        
        if ($fechacp==''){
            $fechacp='NULL';
        }else{
            $fechacp="'".$fechacp."'";
        }

        $sql = " UPDATE ca_llamada 
                SET 
                idfinal = ?,
                usuario_modificacion = ?,
                fecha_cp=".$fechacp.",
                monto_cp='".$montocp."',
                observacion='".$observaciones."',
                fecha_modificacion = NOW() 
                WHERE idllamada = ? ";

        // $sql = " UPDATE ca_llamada 
        //         SET 
        //         idfinal = $idfinal,
        //         usuario_modificacion = $usuario_modificacion,
        //         fecha_cp=".$fechacp.",
        //         monto_cp='".$montocp."',
        //         observacion='".$observaciones."',
        //         fecha_modificacion = NOW() 
        //         WHERE idllamada = $idllamada ";

        //         echo $sql;
        //         exit;
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $connection->beginTransaction();
                
        $pr = $connection->prepare($sql);
        $pr->bindParam(1,$idfinal,PDO::PARAM_INT);
        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
        $pr->bindParam(3,$idllamada,PDO::PARAM_INT);
        if( $pr->execute() ) {
            $sqlUL="UPDATE ca_cuenta cu inner join 
                    (
                        select * from
                        (
                        select lla.idcuenta, lla.fecha, fin.idcarga_final, lla.idfinal, lla.fecha_cp, lla.observacion, lla.idusuario_servicio, lla.idtelefono
                        from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin on fin.idfinal = lla.idfinal and lla.idcliente_cartera = clicar.idcliente_cartera
                        where clicar.idcartera = $cartera and clicar.idcliente_cartera = $cliente_cartera
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
                    where cu.idcartera = $cartera";
            $prUL=$connection->prepare($sqlUL);
            if ($prUL->execute()){
                $sqlML="UPDATE ca_cuenta cu inner join 
                        (
                            select * from
                            (
                            select lla.idcuenta, lla.fecha, fin.idcarga_final, lla.idfinal, lla.fecha_cp, lla.observacion, lla.idusuario_servicio, lla.idtelefono , finser.peso 
                            from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser 
                            on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera
                            where clicar.idcartera = $cartera and clicar.idcliente_cartera=$cliente_cartera
                            order by lla.idcuenta, finser.peso desc 
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
                        where cu.idcartera = $cartera";
                $prML=$connection->prepare($sqlML);
                if($prML->execute()){
                    $connection->commit();
                    return true;    
                }else{
                    $connection->rollBack();
                    return false;
                }
            }else{
                $connection->rollBack();
                return false;
            }

        }else{
            $connection->rollBack();
            return false;
        }
        
    }
    

    public function insertDataCreation(dto_transaccion $dto) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, fecha, observacion, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        //$daoClienteCartera=DAOFactory::getDAOClienteCartera(); 

        $TipoGestion = $dto->getIdTipoGestion();
        $ClienteCartera = $dto->getIdClienteCartera();
        $Final = $dto->getIdFinal();
        $Observacion = $dto->getObservacion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();
        $fecha = $dto->getFecha();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $Final);
        $pr->bindParam(4, $fecha);
        $pr->bindParam(5, $Observacion);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataCP(dto_transaccion $dtoTransaccion, dto_compromiso_pago $dtoCP) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $fecha = $dtoTransaccion->getFecha();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $fecha);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlCP = " INSERT INTO ca_compromiso_pago( idtransaccion, fecha_cp, monto_cp, estado, observacion, fecha_creacion, usuario_creacion ) 
					VALUES (?,?,?,1,?,NOW(),?) ";

            $transaccion = $connection->lastInsertId();
            $fechaCP = $dtoCP->getFechaCp();
            $montoCP = $dtoCP->getMontoCp();
            $observacionCP = $dtoCP->getObservacion();
            $UsuarioCreacionCP = $dtoCP->getUsuarioCreacion();

            $prCP = $connection->prepare($sqlCP);
            $prCP->bindParam(1, $transaccion);
            $prCP->bindParam(2, $fechaCP);
            $prCP->bindParam(3, $montoCP);
            $prCP->bindParam(4, $observacion);
            $prCP->bindParam(5, $UsuarioCreacionCP);

            if ($prCP->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataCreationUpdateClienteCarteraVisita(dto_transaccion $dto) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, fecha, observacion, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dto->getIdTipoGestion();
        $ClienteCartera = $dto->getIdClienteCartera();
        $Final = $dto->getIdFinal();
        $Observacion = $dto->getObservacion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();
        $fecha = $dto->getFecha();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $Final);
        $pr->bindParam(4, $fecha);
        $pr->bindParam(5, $Observacion);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {
            ////$connection->commit();
            $idTransaction = $connection->lastInsertId();
            $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?, usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
            $prCC = $connection->prepare($sqlCC);
            $prCC->bindParam(1, $idTransaction);
            $prCC->bindParam(2, $UsuarioCreacion);
            $prCC->bindParam(3, $ClienteCartera);
            if ($prCC->execute()) {
                //$connection->commit();
                return true;
            } else {
                return false;
            }
            //return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }
    
    public function  insertDataVisitaComercial(dto_visita_comercial $dtoVisitaComercial){ //piro
        
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
       
        $idClienteCartera                           =$dtoVisitaComercial->getIdClienteCartera();
        $idDireccion                                =$dtoVisitaComercial->getIdDireccion();
        $idNotificador                              =$dtoVisitaComercial->getIdNotificador();
        $idCuenta                                   =$dtoVisitaComercial->getIdCuenta();
        $idUsuarioServicio                          =$dtoVisitaComercial->getIdUsuarioServicio();
        $fechaCP                                    =$dtoVisitaComercial->getFechaCP();
        $fechaVisita                                =$dtoVisitaComercial->getFechaVisita();
        $horaVisita                                 =$dtoVisitaComercial->getHoraVisita();
        $idGiroNegocio                              =$dtoVisitaComercial->getIdGiroNegocio();
        $detalleGiroExtraNegocio                    =$dtoVisitaComercial->getDetalleGiroExtraNegocio();
        $idMotivoAtrasoNegocio                      =$dtoVisitaComercial->getIdmotivoAtrasoNegocio();
        $detalleMotivoAtrasoNegocio                 =$dtoVisitaComercial->getDetalleMotivoAtrasoNegocio();
        $idAfrontarPagoNegocio                      =$dtoVisitaComercial->getIdAfrontarPagoNegocio();
        $detalleAfrontarPagoNegocio                 =$dtoVisitaComercial->getDetalleAfrontarPagoNegocio();
        $idCuestionaCobranzaNegocio                 =$dtoVisitaComercial->getIdCuestionacobranzaNegocio();
        $idObservacionEspecialistaNegocio           =$dtoVisitaComercial->getIdObservacionEspecialistaNegocio();
        $caracteristicaNegocioEnActividad           =$dtoVisitaComercial->getCaracteristicaNegocioEnActividad();
        $caracteristicaNegocioTieneExistencias      =$dtoVisitaComercial->getCaracteristicaNegocioTieneExistencias();
        $caracteristicaNegocioLaborArtesanal        =$dtoVisitaComercial->getCaracteristicaNegocioLaborArtesanal();
        $caracteristicaNegocioLocalPropio           =$dtoVisitaComercial->getCaracteristicaNegocioLocalPropio();
        $caracteristicaNegocioOficinaAdministrativa =$dtoVisitaComercial->getCaracteristicaNegocioOficinaAdministrativa();
        $menorigual10personas                       =$dtoVisitaComercial->getMenorigual10personas();
        $mayor10personas                            =$dtoVisitaComercial->getMayor10personas();
        $caracteristicaNegocioPlantaIndustrial      =$dtoVisitaComercial->getCaracteristicaNegocioPlantaIndustrial();
        $caracteristicaNegocioCasaNegocio           =$dtoVisitaComercial->getCaracteristicaNegocioCasaNegocio();
        $caracteristicaNegocioPuertaCalle           =$dtoVisitaComercial->getCaracteristicaNegocioPuertaCalle();
        $caracteristicaNegocioActividadAdicional    =$dtoVisitaComercial->getCaracteristicaNegocioActividadAdicional();
        $tipoVisita                                 =$dtoVisitaComercial->getTipoVisita();
        $numeroVisita                               =$dtoVisitaComercial->getNumerovisita();
        $nuevaDireccion                             =$dtoVisitaComercial->getNuevaDireccion();
        $nuevoTelefono                              =$dtoVisitaComercial->getNuevoTelefono();
        $direecionVisita2                           =$dtoVisitaComercial->getDireccionVisita2();
        
        
        $sql = "INSERT INTO 
                ca_visita(idcliente_cartera, idtipo_gestion, iddireccion, idnotificador, idcuenta,
                        idusuario_servicio, fecha_cp, fecha_visita, hora_visita, tipo,
                        estado, fecha_creacion, idgiro_negocio, detalle_giro_extra_negocio,
                        idmotivo_atraso_negocio,detalle_motivo_atraso_negocio, idafrontar_pago_negocio, detalle_afrontar_pago_negocio, 
                        idcuestiona_cobranza_negocio,idobservacion_especialista_negocio, caracteristica_negocio_enactividad, caracteristica_negocio_tieneexistencias, caracteristica_negocio_laborartesanal
                        , caracteristica_negocio_localpropio, caracteristica_negocio_ofiadministra, caracteristica_negocio_menorigualdiezpersonas, caracteristica_negocio_mayordiezpersonas, caracteristica_negocio_plantaindustrial
                        , caracteristica_negocio_casanegocio, caracteristica_negocio_puertaacalle, caracteristica_negocio_actividad_adicional, idcarga_final, numero_visita, nueva_direccion, nuevo_telefono, direccion_visita_2)
                VALUES($idClienteCartera,'2',$idDireccion,$idNotificador,$idCuenta,"
                . "$idUsuarioServicio,'$fechaCP','$fechaVisita','$horaVisita','VISCOM',"
                . "'1',NOW(),$idGiroNegocio,'$detalleGiroExtraNegocio',"
                . "$idMotivoAtrasoNegocio,'$detalleMotivoAtrasoNegocio',$idAfrontarPagoNegocio,'$detalleAfrontarPagoNegocio',"
                . "$idCuestionaCobranzaNegocio,$idObservacionEspecialistaNegocio,$caracteristicaNegocioEnActividad,$caracteristicaNegocioTieneExistencias, $caracteristicaNegocioLaborArtesanal, "
                . "$caracteristicaNegocioLocalPropio, $caracteristicaNegocioOficinaAdministrativa, $menorigual10personas,$mayor10personas, $caracteristicaNegocioPlantaIndustrial,"
                . " $caracteristicaNegocioCasaNegocio,$caracteristicaNegocioPuertaCalle,'$caracteristicaNegocioActividadAdicional', $tipoVisita, $numeroVisita, '$nuevaDireccion', '$nuevoTelefono', '$direecionVisita2')";
        
             
        
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            
            //$connection->commit();
            return true;
        } else {
            
            //$connection->rollBack();
            return false;
        }
       
        
        
    }

    public function insertDataCreationUpdateClienteCarteraLlamada(dto_transaccion $dto) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, fecha, observacion, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dto->getIdTipoGestion();
        $ClienteCartera = $dto->getIdClienteCartera();
        $Final = $dto->getIdFinal();
        $Observacion = $dto->getObservacion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();
        $fecha = $dto->getFecha();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $Final);
        $pr->bindParam(4, $fecha);
        $pr->bindParam(5, $Observacion);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {
            ////$connection->commit();
            $idTransaction = $connection->lastInsertId();
            $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
            $prCC = $connection->prepare($sqlCC);
            $prCC->bindParam(1, $idTransaction);
            $prCC->bindParam(2, $UsuarioCreacion);
            $prCC->bindParam(3, $ClienteCartera);
            if ($prCC->execute()) {
                //$connection->commit();
                return true;
            } else {
                return false;
            }
            //return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function insertDataCPUpdateClienteCarteraVisita(dto_transaccion $dtoTransaccion, dto_compromiso_pago $dtoCP) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $fecha = $dtoTransaccion->getFecha();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $fecha);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlCP = " INSERT INTO ca_compromiso_pago( idtransaccion, fecha_cp, monto_cp, estado, observacion, fecha_creacion, usuario_creacion ) 
					VALUES (?,?,?,1,?,NOW(),?) ";

            $transaccion = $connection->lastInsertId();
            $fechaCP = $dtoCP->getFechaCp();
            $montoCP = $dtoCP->getMontoCp();
            $observacionCP = $dtoCP->getObservacion();
            $UsuarioCreacionCP = $dtoCP->getUsuarioCreacion();

            $prCP = $connection->prepare($sqlCP);
            $prCP->bindParam(1, $transaccion);
            $prCP->bindParam(2, $fechaCP);
            $prCP->bindParam(3, $montoCP);
            $prCP->bindParam(4, $observacion);
            $prCP->bindParam(5, $UsuarioCreacionCP);

            if ($prCP->execute()) {

                $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?, usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                $prCC = $connection->prepare($sqlCC);
                $prCC->bindParam(1, $transaccion);
                $prCC->bindParam(2, $UsuarioCreacion);
                $prCC->bindParam(3, $ClienteCartera);
                if ($prCC->execute()) {
                    //$connection->commit();
                    return true;
                } else {
                    return false;
                }
                ////$connection->commit();
                //return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataCPUpdateClienteCarteraLlamada(dto_transaccion $dtoTransaccion, dto_compromiso_pago $dtoCP) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $fecha = $dtoTransaccion->getFecha();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $fecha);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlCP = " INSERT INTO ca_compromiso_pago( idtransaccion, fecha_cp, monto_cp, estado, observacion, fecha_creacion, usuario_creacion ) 
					VALUES (?,?,?,1,?,NOW(),?) ";

            $transaccion = $connection->lastInsertId();
            $fechaCP = $dtoCP->getFechaCp();
            $montoCP = $dtoCP->getMontoCp();
            $observacionCP = $dtoCP->getObservacion();
            $UsuarioCreacionCP = $dtoCP->getUsuarioCreacion();

            $prCP = $connection->prepare($sqlCP);
            $prCP->bindParam(1, $transaccion);
            $prCP->bindParam(2, $fechaCP);
            $prCP->bindParam(3, $montoCP);
            $prCP->bindParam(4, $observacion);
            $prCP->bindParam(5, $UsuarioCreacionCP);

            if ($prCP->execute()) {

                $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                $prCC = $connection->prepare($sqlCC);
                $prCC->bindParam(1, $transaccion);
                $prCC->bindParam(2, $UsuarioCreacion);
                $prCC->bindParam(3, $ClienteCartera);
                if ($prCC->execute()) {
                    //$connection->commit();
                    return true;
                } else {
                    //$connection->rollBack();
                    return false;
                }
                ////$connection->commit();
                //return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataLlamadaUpdateClienteCarteraLlamada(dto_transaccion $dtoTransaccion, dto_llamada $dtoLlamada, $cuenta, $peso, $numero_telefono, $direcciones, $telefonos_check, $call_id,$codigo_cliente ,$recibio_eecc,$sustento_pago,$alerta_gestion,$idsituacion_laboral,$iddisposicion_refinanciar,$idestado_cliente) {
        		
        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        //$final=$dtoTransaccion->getIdFinal();
        $final=$dtoTransaccion->getIdEstado();
        $FechaLLamada = $dtoTransaccion->getFecha();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        $servicio = $dtoTransaccion->getIdServicio();
        $usuario_servicio = $dtoTransaccion->getIdUsuarioServicio();

        $telefono = $dtoLlamada->getIdTelefono();

        $TMO_inicio = $dtoLlamada->getTmoInicio();
        $TMO_fin = $dtoLlamada->getTmoFin();

        $caller_id = $dtoLlamada->getCallerId();

        $enviar_campo = $dtoLlamada->getEnviarCampo();
        $idcontacto = $dtoLlamada->getIdContacto();
        $nombre_contacto = $dtoLlamada->getNombreContacto();
        $idmotivo_no_pago = $dtoLlamada->getIdMotivoNoPago();
        $idparentesco = $dtoLlamada->getIdParentesco();
        $idcarga_final = $dtoLlamada->getIdCargaFinal();
        
        $fecha_llamada = date("Y-m-d H:i:s");

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

                                    for ($i = 0; $i < count($cuenta); $i++) {

                        $fecha_cp = (trim($cuenta[$i]['FechaCp']) == '') ? NULL : $cuenta[$i]['FechaCp'];
                        $monto_cp = (trim($cuenta[$i]['MontoCp']) == '') ? NULL : $cuenta[$i]['MontoCp'];
                        $moneda_cp = (trim($cuenta[$i]['MonedaCp']) == '') ? NULL : $cuenta[$i]['MonedaCp'];
                        $estado_cuenta = (trim($cuenta[$i]['estado']) == '0' || trim($cuenta[$i]['estado']) == '') ? NULL : $cuenta[$i]['estado'];

                        // echo $cuenta[$i]['MontoCp'];
                        //$sqlCuenta = " INSERT INTO ca_gestion_cuenta( idllamada, idcuenta, idestado, fecha_cp, monto_cp, usuario_creacion, fecha_creacion ) 
                        //VALUES ( ?,?,?,?,?,?,NOW() ) ";

                        $sqlCuenta = " INSERT INTO ca_llamada ( idcliente_cartera, idfinal ,idtipo_gestion ,idcontacto ,nombre_contacto ,idmotivo_no_pago ,idtelefono ,idcuenta ,fecha ,inicio_tmo ,fin_tmo ,idusuario_servicio ,enviar_campo ,observacion ,fecha_cp ,monto_cp ,moneda_cp ,tipo ,usuario_creacion ,fecha_creacion, status_cuenta, idparentesco, call_id ,idsustento_pago,idalerta_gestion,callerid,idsituacion_laboral,iddisposicion_refinanciar,idestado_cliente) 
                                                                        VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'LL',?,NOW(),?,?,'$call_id','$sustento_pago','$alerta_gestion',?,'$idsituacion_laboral','$iddisposicion_refinanciar','$idestado_cliente' ) ";

                        // echo $sqlCuenta;
                        

                        $prCuenta = $connection->prepare($sqlCuenta);

                                                $prCuenta->bindParam(1, $ClienteCartera, PDO::PARAM_INT);
                                                $prCuenta->bindParam(2, $final, PDO::PARAM_INT);
                                                $prCuenta->bindParam(3, $TipoGestion, PDO::PARAM_INT);
                                                $prCuenta->bindParam(4, $idcontacto, PDO::PARAM_STR);
                                                $prCuenta->bindParam(5, $nombre_contacto, PDO::PARAM_INT);
                                                $prCuenta->bindParam(6, $idmotivo_no_pago, PDO::PARAM_INT);
                                                $prCuenta->bindParam(7, $telefono, PDO::PARAM_INT);
                                                $prCuenta->bindParam(8, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                                                
                                                $prCuenta->bindParam(9, $fecha_llamada, PDO::PARAM_STR);
                                                
                                                $prCuenta->bindParam(10, $TMO_inicio, PDO::PARAM_INT);
                                                $prCuenta->bindParam(11, $TMO_fin, PDO::PARAM_INT);
                                                $prCuenta->bindParam(12, $usuario_servicio, PDO::PARAM_INT);
                                                $prCuenta->bindParam(13, $enviar_campo, PDO::PARAM_INT);
                                                $prCuenta->bindParam(14, $observacion, PDO::PARAM_INT);
                                                $prCuenta->bindParam(15, $fecha_cp, PDO::PARAM_INT);
                                                $prCuenta->bindParam(16, $monto_cp, PDO::PARAM_INT);
                                                $prCuenta->bindParam(17, $moneda_cp, PDO::PARAM_INT);
                                                $prCuenta->bindParam(18, $UsuarioCreacion, PDO::PARAM_INT);
                                                $prCuenta->bindParam(19, $cuenta[$i]['EstadoCuenta'], PDO::PARAM_STR);
                                                $prCuenta->bindParam(20, $idparentesco, PDO::PARAM_INT);
                                                $prCuenta->bindParam(21, $caller_id, PDO::PARAM_STR);
                        
                        $llamada = NULL;
                        if ($prCuenta->execute()) {
                            $llamada = $connection->lastInsertId();

                                                        $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW(),id_ultima_llamada_total=? WHERE idcliente_cartera = ?  ";
                                                        $prCC = $connection->prepare($sqlCC);
                                                        $prCC->bindParam(1, $llamada);
                                                        $prCC->bindParam(2, $UsuarioCreacionLlamada);
                                                        $prCC->bindParam(3, $llamada);                                                        
                                                        $prCC->bindParam(4, $ClienteCartera);
                                                        if ($prCC->execute()) {
                                                        }else{
                                                                return false;
                                                                exit();
                                                        }

                                                        /*ACTUALIZANDO RECIBIO ESTADO DE CUENTA DEL CLIENTE POR CARTERA*/
                                                        if($recibio_eecc>0){
                                                            $sqleecc="UPDATE ca_cliente_cartera SET recibio_eexx=$recibio_eecc WHERE idcliente_cartera=$ClienteCartera";
                                                            $preecc=$connection->prepare($sqleecc);
                                                            if($preecc->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }
                                                        /*ACUTALIZANDO A NIVEL CLIENTE EL MOTIVO NO PAGO*/
                                                        if($idmotivo_no_pago>0){
                                                            $sqlmotivonopago="UPDATE ca_cliente_cartera SET ul_motivo_no_pago=$idmotivo_no_pago WHERE idcliente_cartera=$ClienteCartera";
                                                            $prmotivonopago=$connection->prepare($sqlmotivonopago);
                                                            if($prmotivonopago->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }   

                                                        if($idsituacion_laboral>0){
                                                            $sqlSituacionLaboral="UPDATE ca_cliente_cartera SET idsituacion_laboral=$idsituacion_laboral WHERE idcliente_cartera=$ClienteCartera";
                                                            $prSituacionLaboral=$connection->prepare($sqlSituacionLaboral);
                                                            if($prSituacionLaboral->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }

                                                        if($iddisposicion_refinanciar>0){
                                                            $sqlDisposicionRefinanciar="UPDATE ca_cliente_cartera SET iddisposicion_refinanciar=$iddisposicion_refinanciar WHERE idcliente_cartera=$ClienteCartera";
                                                            $prDisposicionRefinanciar=$connection->prepare($sqlDisposicionRefinanciar);
                                                            if($prDisposicionRefinanciar->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }

                                                        if($idestado_cliente>0){
                                                            $sqlEstadoCliente="UPDATE ca_cliente_cartera SET idestado_cliente=$idestado_cliente WHERE idcliente_cartera=$ClienteCartera";
                                                            $prEstadoCliente=$connection->prepare($sqlEstadoCliente);
                                                            if($prEstadoCliente->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }

                                                        /**insertando ultimo*/    
                                                        if($idcontacto>0){
                                                            $sqlcontacto="INSERT INTO ca_ultimo_contacto(codigo_cliente,idcontacto) 
                                                                            VALUES('$codigo_cliente',$idcontacto)
                                                                            ON DUPLICATE KEY UPDATE estado=1,idcontacto=$idcontacto";
                                                            $prcontacto=$connection->prepare($sqlcontacto);
                                                            if($prcontacto->execute()){

                                                            }else{
                                                                return false;
                                                                exit();
                                                            }
                                                        }                                                                                                         

                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }
                        
                        foreach( $direcciones as $index => $value ){
                                $sqlED = "";
                                if( $cuenta[$i]['Cuenta'] == $value['cuenta'] && $value['est'] != '' ) {
                                        $sqlED = " INSERT INTO ca_ll_det_direccion_est ( idllamada, iddireccion, status, fecha_creacion, usuario_creacion ) 
                                                VALUES (  ?,?,?,NOW(),? ) ";
                                                
                                        $prED = $connection->prepare( $sqlED );
                                        $prED->bindParam(1,$llamada,PDO::PARAM_INT);
                                        $prED->bindParam(2,$index,PDO::PARAM_INT);
                                        $prED->bindParam(3,$value['est'],PDO::PARAM_STR);
                                        $prED->bindParam(4,$UsuarioCreacion,PDO::PARAM_INT);
                                        if( $prED->execute() ) {
                                                
                                        }else{
                                                return false;
                                                exit();
                                        }
                                }
                                
                        }
                        
                        foreach( $telefonos_check as $k => $v ) {
                                
                                $sqlET = "";
                                
                                if( $cuenta[$i]['Cuenta'] == $v['cuenta'] && $v['est'] != '' ) {
                                        
                                        $sqlET = " INSERT INTO ca_ll_det_telefono_est ( idllamada, idtelefono, status, fecha_creacion, usuario_creacion ) 
                                                VALUES (  ?,?,?,NOW(),? ) ";
                                        
                                        $prET = $connection->prepare( $sqlET );
                                        $prET->bindParam(1,$llamada,PDO::PARAM_INT);
                                        $prET->bindParam(2,$k,PDO::PARAM_INT);
                                        $prET->bindParam(3,$v['est'],PDO::PARAM_STR);
                                        $prET->bindParam(4,$UsuarioCreacion,PDO::PARAM_INT);
                                        if( $prET->execute() ) {

                                        }else{
                                                return false;
                                                exit();
                                        }
                                        
                                }
                                
                        }

                        $sqlUpdateCuenta = " UPDATE ca_cuenta SET 
                                                                fecha_modificacion = NOW(), usuario_modificacion = ? , 
                                                                ul_fecha = NOW(), ul_carga = ? , 
                                                                ul_estado = ? , ul_fcpg = ? , ul_observacion = ? , ul_operador = ? , idcontacto = ?, idmotivo_no_pago = ? ,
                                                                ul_telefono = ? 
                                                                WHERE idcuenta = ? ";

                        $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                        $prUpdateCuenta->bindParam(1, $UsuarioCreacion, PDO::PARAM_INT);
                        /*                         * ******* */
                        $prUpdateCuenta->bindParam(2, $idcarga_final, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(3, $final, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(4, $fecha_cp, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(5, $observacion, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(6, $usuario_servicio, PDO::PARAM_INT);
                        /*                         * ******* */
                        $prUpdateCuenta->bindParam(7, $idcontacto, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(8, $idmotivo_no_pago, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(9, $telefono, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(10, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        /*                         * ****** */
                        if ($prUpdateCuenta->execute()) {

                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        $sqlMLCuenta = " UPDATE ca_cuenta SET ml_fecha = NOW(), 
                                                                ml_carga = ? , 
                                                                ml_estado = ?, ml_fcpg = ?, ml_observacion = ?, ml_operador = ?,  ml_telefono = ?,
                                                                ml_peso_estado = ? 
                                                                WHERE  ml_peso_estado <= ? 
                                                                AND idcuenta = ? ";
                        //echo $sqlMLCuenta;
                        $prMLCuenta = $connection->prepare($sqlMLCuenta);
                        $prMLCuenta->bindParam(1, $idcarga_final, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(2, $final, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(3, $fecha_cp);
                        $prMLCuenta->bindParam(4, $observacion);
                        $prMLCuenta->bindParam(5, $usuario_servicio, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(6, $telefono, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(7, $peso, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(8, $peso, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(9, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        if ($prMLCuenta->execute()) {

                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        $sqlUpdateTelefono = " UPDATE ca_telefono 
                                            SET
                                            idfinal = IF( IFNULL(m_peso,0)>=?, IFNULL(idfinal,0) , ? ),
                                            u_peso = ?,
                                            m_peso = IF( IFNULL(m_peso,0)>=?, m_peso , ? ),
                                            fecha_modificacion = NOW(),
                                            usuario_modificacion = ? 
                                            WHERE codigo_cliente = ? AND (numero = ? OR numero_act=?)";

                        $prUpdateTelefono = $connection->prepare($sqlUpdateTelefono);
                        $prUpdateTelefono->bindParam(1,$peso,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(2,$final,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(3,$peso,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(4,$peso,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(5,$peso,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(6,$UsuarioCreacion,PDO::PARAM_INT);
                        $prUpdateTelefono->bindParam(7,$codigo_cliente,PDO::PARAM_STR);
                        $prUpdateTelefono->bindParam(8,$numero_telefono,PDO::PARAM_STR);
                        $prUpdateTelefono->bindParam(9,$numero_telefono,PDO::PARAM_STR);                        
                        if( @$prUpdateTelefono->execute() ) {

                        }else{
                            return false;
                            exit;
                        }                        
                    }

                    //$connection->commit();
                    return true;
                   
    }

    public function insertDataCPGLlamadaUpdateClienteCarteraLlamada(dto_transaccion $dtoTransaccion, dto_llamada $dtoLlamada, dto_compromiso_pago $dtoCompromisoPago, $cuenta) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,NOW(),NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $FechaLLamada = $dtoTransaccion->getFecha();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        for ($i = 0; $i < count($cuenta); $i++) {


            $pr = $connection->prepare($sql);
            $pr->bindParam(1, $TipoGestion);
            $pr->bindParam(2, $ClienteCartera);
            $pr->bindParam(3, $final);
            $pr->bindParam(4, $observacion);
            //$pr->bindParam(5,$FechaLLamada);
            //$pr->bindParam(6,$pesoTransaccion);
            $pr->bindParam(5, $UsuarioCreacion);

            if ($pr->execute()) {
                $sqlLlamada = " INSERT INTO ca_llamada ( fecha, idtransaccion, idtelefono, idcuenta, fecha_creacion, usuario_creacion ) 
					VALUES (NOW(),?,?,NOW(),?) ";

                $transaccion = $connection->lastInsertId();


                $telefono = $dtoLlamada->getIdTelefono();
                //$PesoLlamada=$dtoLlamada->getIdPesoLlamada();
                $UsuarioCreacionLlamada = $dtoLlamada->getUsuarioCreacion();

                $prll = $connection->prepare($sqlLlamada);
                //$prll->bindParam(1,$FechaLLamada);
                $prll->bindParam(1, $transaccion);
                $prll->bindParam(2, $telefono);
                /*                 * ****** */
                $prll->bindParam(3, $cuenta[$i]);
                /*                 * ****** */
                //$prll->bindParam(3,$PesoLlamada);
                $prll->bindParam(4, $UsuarioCreacionLlamada);

                if ($prll->execute()) {

                    $sqlCPG = " INSERT INTO ca_compromiso_pago ( fecha_cp, monto_cp, estado, idtransaccion, observacion, fecha_creacion, usuario_creacion ) 
						VALUES ( ?,?,1,?,?,NOW(),? ) ";

                    $fechaCPG = $dtoCompromisoPago->getFechaCp();
                    $montoCPG = $dtoCompromisoPago->getMontoCp();

                    $prCPG = $connection->prepare($sqlCPG);
                    $prCPG->bindParam(1, $fechaCPG);
                    $prCPG->bindParam(2, $montoCPG);
                    $prCPG->bindParam(3, $transaccion);
                    $prCPG->bindParam(4, $observacion);
                    $prCPG->bindParam(5, $UsuarioCreacion);
                    if ($prCPG->execute()) {
                        $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                        $prCC = $connection->prepare($sqlCC);
                        $prCC->bindParam(1, $transaccion);
                        $prCC->bindParam(2, $UsuarioCreacionLlamada);
                        $prCC->bindParam(3, $ClienteCartera);
                        if ($prCC->execute()) {
//							//$connection->commit();
//							return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }
                    } else {
                        $connection_ > rollBack();
                        return false;
                        exit();
                    }
                } else {
                    //$connection->rollBack();
                    return false;
                    exit();
                }
            } else {
                //$connection->rollBack();
                return false;
                exit();
            }
        }

        //$connection->commit();
        return true;
    }

    public function insertDataVisitaUpdateClienteCarteraVisita(dto_transaccion $dtoTransaccion, dto_visita $dtoVisita) {
        //$sql=" INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, idpeso_transaccion, fecha_creacion, usuario_creacion )  
        //VALUES (?,?,?,?,NOW(),?,NOW(),?) ";

        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,NOW(),NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        //$pesoTransaccion=$dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        //$pr->bindParam(5,$pesoTransaccion);
        $pr->bindParam(5, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlVisita = " INSERT INTO ca_visita ( idtransaccion , iddireccion, fecha_creacion, usuario_creacion ) 
					VALUES( ?,?,NOW(),? ) ";

            $transaccion = $connection->lastInsertId();

            $direccion = $dtoVisita->getIdDireccion();

            $prV = $connection->prepare($sqlVisita);
            $prV->bindParam(1, $transaccion);
            $prV->bindParam(2, $direccion);
            $prV->bindParam(3, $UsuarioCreacion);

            if ($prV->execute()) {

                $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                $prCC = $connection->prepare($sqlCC);
                $prCC->bindParam(1, $transaccion);
                $prCC->bindParam(2, $UsuarioCreacion);
                $prCC->bindParam(3, $ClienteCartera);
                if ($prCC->execute()) {
                    //$connection->commit();
                    return true;
                } else {
                    //$connection->rollBack();
                    return false;
                }
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataCPGVisitaUpdateClienteCarteraVisita(dto_transaccion $dtoTransaccion, dto_visita $dtoVisita, dto_compromiso_pago $dtoCompromisoPago) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, idpeso_transaccion, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,NOW(),?,NOW(),?) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $pesoTransaccion);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlVisita = " INSERT INTO ca_visita ( idtransaccion , iddireccion, fecha_creacion, usuario_creacion ) 
					VALUES( ?,?,NOW(),? ) ";

            $transaccion = $connection->lastInsertId();

            $direccion = $dtoVisita->getIdDireccion();

            $prV = $connection->prepare($sqlVisita);
            $prV->bindParam(1, $transaccion);
            $prV->bindParam(2, $direccion);
            $prV->bindParam(3, $UsuarioCreacion);

            if ($prV->execute()) {

                $sqlCPG = " INSERT INTO ca_compromiso_pago ( fecha_cp, monto_cp, estado, idtransaccion, observacion, fecha_creacion, usuario_creacion ) 
						VALUES ( ?,?,1,?,?,NOW(),? ) ";

                $fechaCPG = $dtoCompromisoPago->getFechaCp();
                $montoCPG = $dtoCompromisoPago->getMontoCp();

                $prCPG = $connection->prepare($sqlCPG);
                $prCPG->bindParam(1, $fechaCPG);
                $prCPG->bindParam(2, $montoCPG);
                $prCPG->bindParam(3, $transaccion);
                $prCPG->bindParam(4, $observacion);
                $prCPG->bindParam(5, $UsuarioCreacion);

                if ($prCPG->execute()) {

                    $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                    $prCC = $connection->prepare($sqlCC);
                    $prCC->bindParam(1, $transaccion);
                    $prCC->bindParam(2, $UsuarioCreacion);
                    $prCC->bindParam(3, $ClienteCartera);
                    if ($prCC->execute()) {
                        //$connection->commit();
                        return true;
                    } else {
                        //$connection->rollBack();
                        return false;
                    }
                } else {
                    //$connection->rollBack();
                    return false;
                }
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataVisitaClienteCarteraVisitaCuentas($cuenta,$idestado_cliente, dto_transaccion $dtoTransaccion, dto_visita $dtoVisita, $peso, $carga, $direcciones_est ) {
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

            $direccion = $dtoVisita->getIdDireccion();
            $fecha_visita_v = $dtoVisita->getFechaVisita();
            // $fecha_recepcion_v = $dtoVisita->getFechaRecepcion();
            $hora_ubicacion = $dtoVisita->getHoraUbicacion();
            $hora_salida = $dtoVisita->getHoraSalida();
            // $idnotificador = $dtoVisita->getIdNotificador();
            // $idnotificador = ($idnotificador == 0) ? NULL : $idnotificador ;
            $descripcion_inmueble = $dtoVisita->getDescripcionInmueble();
            $idcontacto = $dtoVisita->getIdContacto();
            $nombre_contacto = $dtoVisita->getNombreContacto();
            $idmotivo_no_pago = $dtoVisita->getIdMotivoNoPago();
            $idparentesco = $dtoVisita->getIdParentesco();

            $TipoGestion = $dtoTransaccion->getIdTipoGestion();
            $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
            $final = $dtoTransaccion->getIdFinal();
            $observacion = $dtoTransaccion->getObservacion();
            $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
            $servicio = $dtoTransaccion->getIdServicio();
            $usuario_servicio = $dtoTransaccion->getIdUsuarioServicio();



            for ($i = 0; $i <= count($cuenta)-1; $i++) {

                $fecha_cp = (trim($cuenta[$i]['FechaCp']) == '') ? NULL : $cuenta[$i]['FechaCp'];
                $monto_cp = (trim($cuenta[$i]['MontoCp']) == '') ? NULL : $cuenta[$i]['MontoCp'];
                                        $moneda_cp = (trim($cuenta[$i]['MonedaCp']) == '') ? NULL : $cuenta[$i]['MonedaCp'];

                $sqlVisita = " INSERT INTO ca_visita ( idcliente_cartera ,idtipo_gestion ,idfinal ,iddireccion ,idcontacto ,nombre_contacto ,idmotivo_no_pago ,idcuenta ,idusuario_servicio ,fecha_cp ,monto_cp ,moneda_cp ,fecha_visita , hora_visita, hora_salida,observacion ,descripcion_inmueble ,tipo ,fecha_creacion ,usuario_creacion, idparentesco,idestado_cliente,caracteristica_negocio_enactividad,caracteristica_negocio_tieneexistencias,caracteristica_negocio_laborartesanal,caracteristica_negocio_localpropio,caracteristica_negocio_ofiadministra,caracteristica_negocio_menorigualdiezpersonas,caracteristica_negocio_mayordiezpersonas,caracteristica_negocio_plantaindustrial,caracteristica_negocio_casanegocio,caracteristica_negocio_puertaacalle,numero_visita) 
                                                                VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'VIS',NOW(),?,?,$idestado_cliente,0,0,0,0,0,0,0,0,0,0,0 ) ";

                $prV = $connection->prepare($sqlVisita);
                $prV->bindParam(1, $ClienteCartera, PDO::PARAM_INT);
                $prV->bindParam(2, $TipoGestion, PDO::PARAM_INT);
                $prV->bindParam(3, $final, PDO::PARAM_INT);
                $prV->bindParam(4, $direccion, PDO::PARAM_INT);
                // $prV->bindParam(5, NULL);
                $prV->bindParam(5, $idcontacto, PDO::PARAM_INT);
                $prV->bindParam(6, $nombre_contacto, PDO::PARAM_STR);
                $prV->bindParam(7, $idmotivo_no_pago, PDO::PARAM_INT);
                $prV->bindParam(8, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                $prV->bindParam(9, $usuario_servicio, PDO::PARAM_INT);
                $prV->bindParam(10, $fecha_cp, PDO::PARAM_STR);
                $prV->bindParam(11, $monto_cp);
                $prV->bindParam(12, $moneda_cp, PDO::PARAM_STR);
                $prV->bindParam(13, $fecha_visita_v, PDO::PARAM_STR);
                // $prV->bindParam(15, NULL);
                $prV->bindParam(14, $hora_ubicacion, PDO::PARAM_STR);
                $prV->bindParam(15, $hora_salida, PDO::PARAM_STR);                                        
                $prV->bindParam(16, $observacion, PDO::PARAM_STR);
                $prV->bindParam(17, $descripcion_inmueble, PDO::PARAM_STR);
                $prV->bindParam(18, $UsuarioCreacion, PDO::PARAM_INT);
                $prV->bindParam(19, $idparentesco, PDO::PARAM_INT);
                $visita = NULL ;
                if ($prV->execute()) {

                    $visita = $connection->lastInsertId();

                    $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                    $prCC = $connection->prepare($sqlCC);
                    $prCC->bindParam(1, $visita);
                    $prCC->bindParam(2, $UsuarioCreacion);
                    $prCC->bindParam(3, $ClienteCartera);
                    if ($prCC->execute()) {

                    }else{
                        return false;
                        exit();
                    }

                } else {
                    //$connection->rollBack();
                    return false;
                    exit();
                }
                
                foreach( $direcciones_est as $index => $value ){
                        $sqlED = "";
                        if( $cuenta[$i]['Cuenta'] == $value['cuenta'] && $value['est'] != '' ) {
                                $sqlED = " INSERT INTO ca_vis_det_direccion_est ( idvisita, iddireccion, status, fecha_creacion, usuario_creacion ) 
                                        VALUES (  ?,?,?,NOW(),? ) ";

                                $prED = $connection->prepare( $sqlED );
                                $prED->bindParam(1,$visita,PDO::PARAM_INT);
                                $prED->bindParam(2,$index,PDO::PARAM_INT);
                                $prED->bindParam(3,$value['est'],PDO::PARAM_STR);
                                $prED->bindParam(4,$UsuarioCreacion,PDO::PARAM_INT);
                                if( $prED->execute() ) {

                                }else{
                                        return false;
                                        exit();
                                }
                        }

                }

                $sqlUpdateCuenta = " UPDATE ca_cuenta SET uv_fecha = ?, 
                                     uv_carga = ? , uv_estado = ?, 
                                     uv_fcpg = ?, uv_observacion = ?, uv_operador = ? , fecha_modificacion = NOW(), 
                                     uv_direccion = ?, uv_notificador = ? , usuario_modificacion = ? 
                                     WHERE idcuenta = ? ";

                $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                $prUpdateCuenta->bindParam(1, $fecha_visita_v, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(2, $carga, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(3, $final, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(4, $fecha_cp, PDO::PARAM_STR);
                $prUpdateCuenta->bindParam(5, $observacion, PDO::PARAM_STR);
                $prUpdateCuenta->bindParam(6, $usuario_servicio, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(7, $direccion, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(8, $idnotificador, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(9, $UsuarioCreacion, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(10, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                if ($prUpdateCuenta->execute()) {

                } else {
                    //$connection->rollBack();
                    return false;
                    exit();
                }

                $sqlMVCuenta = " UPDATE ca_cuenta 
                                 SET mv_fecha = ?,
                                 mv_carga = ?, 
                                 mv_estado = ?, mv_fcpg = ?, mv_observacion = ?, mv_operador = ?, 
                                 mv_peso_estado = ? ,
                                 mv_direccion = ?, mv_notificador = ? 
                                 WHERE idcuenta = ? AND mv_peso_estado<= ?  ";

                $prMVCuenta = $connection->prepare($sqlMVCuenta);
                $prMVCuenta->bindParam(1, $fecha_visita_v, PDO::PARAM_INT);
                $prMVCuenta->bindParam(2, $carga, PDO::PARAM_INT);
                $prMVCuenta->bindParam(3, $final, PDO::PARAM_INT);
                $prMVCuenta->bindParam(4, $fecha_cp, PDO::PARAM_STR);
                $prMVCuenta->bindParam(5, $observacion, PDO::PARAM_STR);
                $prMVCuenta->bindParam(6, $usuario_servicio, PDO::PARAM_INT);
                $prMVCuenta->bindParam(7, $peso, PDO::PARAM_INT);
                $prMVCuenta->bindParam(8, $direccion, PDO::PARAM_INT);
                $prMVCuenta->bindParam(9, $idnotificador, PDO::PARAM_INT);
                $prMVCuenta->bindParam(10, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                $prMVCuenta->bindParam(11, $peso, PDO::PARAM_INT);
                if ($prMVCuenta->execute()) {

                } else {
                    //$connection->rollBack();
                    return false;
                    exit();
                }
            }

            //$connection->commit();
            return true;
		
    }
    public function queryEnviarCargo($idcuenta,$idcliente_cartera,$usuario_creacion){
        /*$sql="UPDATE ca_cuenta
               set enviar_cargo=1
               WHERE idcuenta=$idcuenta";*/

        $sqlcuenta="SELECT dato1 from ca_cuenta where idcuenta=$idcuenta";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection(); 
        $prcuenta = $connection->prepare($sqlcuenta);    
        $prcuenta->execute();

        $data=$prcuenta->fetchAll(PDO::FETCH_ASSOC);

        $fproceso=$data[0]['dato1'];
        
        $sql="INSERT ca_enviar_cargo(idcuenta,idcliente_cartera,fecha_creacion,usuario_creacion,fproceso)
                VALUES($idcuenta,$idcliente_cartera,now(),$usuario_creacion,'$fproceso')";
        $pr = $connection->prepare($sql); 
                        if ($pr->execute()) {
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }                 
    }
    public function queryAllByServiceUser(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT DISTINCT tran.idtransaccion,tran.observacion,tran.fecha AS 'fecha_agendar',
				(SELECT nombre FROM ca_final WHERE idfinal=tran.idfinal LIMIT 1 ) AS 'final',
				(SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=tran.idtipo_gestion LIMIT 1) AS 'tipo_gestion',
				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',
				cam.nombre AS'campania'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam INNER JOIN ca_transaccion tran 
				ON tran.idcliente_cartera=clicar.idcliente_cartera AND cam.idcampania=car.idcampania AND car.idcartera=clicar.idcartera AND clicar.codigo_cliente=cli.codigo  
				WHERE cam.idservicio=? AND clicar.idusuario_servicio=?  
				AND tran.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=3 ) ORDER BY tran.fecha DESC ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $UsuarioServicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryDataLlamadaById(dto_transaccion $dtoTransaccion) {
        $sql = " SELECT tran.idtransaccion,tran.idtipo_gestion,tran.idfinal,tran.observacion,
			( SELECT idestado_transaccion FROM ca_peso_transaccion WHERE idpeso_transaccion = tran.idpeso_transaccion LIMIT 1 ) AS 'idestado_transaccion',
			( SELECT idcarga_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'carga_final',
			( SELECT idtipo_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'tipo_final',
			( SELECT idclase_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'clase_final',
			( SELECT idnivel FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'nivel',
			tran.idpeso_transaccion,lla.idllamada,lla.idtelefono,
			IFNULL(( SELECT idcompromiso_pago FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'idcompromiso_pago',
			IFNULL(( SELECT fecha_cp FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
			IFNULL(( SELECT TRUNCATE(monto_cp,2) FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
			( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono LIMIT 1 ) AS 'numero'
			FROM ca_transaccion tran INNER JOIN  ca_llamada lla
			ON lla.idtransaccion=tran.idtransaccion WHERE tran.idtransaccion = ? ";

        $id = $dtoTransaccion->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryListarLlamadas($idcartera,$fecha){
        $sql="SELECT count(*) AS 'COUNT' FROM ca_llamada lla
            INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=lla.idcliente_cartera
            WHERE clicar.idcartera = $idcartera and DATE(lla.fecha)='$fecha'";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }


    }
    public function queryDataVisitaById(dto_transaccion $dtoTransaccion) {
        $sql = " SELECT tran.idtransaccion,tran.idtipo_gestion,tran.fecha,tran.idfinal,tran.observacion,
			( SELECT idestado_transaccion FROM ca_peso_transaccion WHERE idpeso_transaccion = tran.idpeso_transaccion LIMIT 1 ) as 'idestado_transaccion',
			( SELECT idcarga_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'carga_final',
			( SELECT idtipo_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'tipo_final',
			( SELECT idclase_final FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'clase_final',
			( SELECT idnivel FROM ca_final WHERE idfinal = tran.idfinal LIMIT 1 ) AS 'nivel',
			tran.idpeso_transaccion,vis.idvisita,
			IFNULL(( SELECT idcompromiso_pago FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'idcompromiso_pago',
			IFNULL(( SELECT fecha_cp FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
			IFNULL(( SELECT TRUNCATE(monto_cp,2) FROM ca_compromiso_pago WHERE idtransaccion=tran.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
			( SELECT TRIM(direccion) FROM ca_direccion WHERE iddireccion=vis.iddireccion LIMIT 1 ) AS 'direccion'
			FROM ca_transaccion tran INNER JOIN ca_visita vis 
			ON vis.idtransaccion=tran.idtransaccion WHERE tran.idtransaccion = ? ";

        $id = $dtoTransaccion->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function updateLlamada(dto_transaccion $dtoTransaccion, dto_llamada $dtoLlamada) {

        $sql = " UPDATE ca_transaccion SET idtipo_gestion = ?, idfinal = ?, idpeso_transaccion = ?, observacion = ?, fecha_modificacion = NOW(), 
			usuario_modificacion = ? WHERE idtransaccion = ? ";

        $idTransaccion = $dtoTransaccion = $dtoTransaccion->getId();
        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioModificacion = $dtoTransaccion->getUsuarioModificacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $final);
        $pr->bindParam(3, $pesoTransaccion);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $UsuarioModificacion);
        $pr->bindParam(6, $idTransaccion);
        if ($pr->execute()) {
            $sqlLlamada = " UPDATE ca_llamada SET fecha = ?, fecha_modificacion = NOW (), usuario_modificacion = ? WHERE idtransaccion = ? AND idllamada = ? ";

            $idTelefono = $dtoLlamada->getIdTelefono();
            $fecha = $dtoLlamada->getLlamada();

            $prLlamada = $connection->prepare($sqlLlamada);
            $prLlamada->bindParam(1, $fecha);
            $prLlamada->bindParam(2, $UsuarioModificacion);
            $prLlamada->bindParam(3, $idTransaccion);
            $prLlamada->bindParam(4, $idTelefono);
            if ($prLlamada->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateLlamadaCp(dto_transaccion $dtoTransaccion, dto_llamada $dtoLlamada, dto_compromiso_pago $dtoCompromisoPago) {

        $sql = " UPDATE ca_transaccion SET idtipo_gestion = ?, idfinal = ?, idpeso_transaccion = ?, observacion = ?, fecha = ?, fecha_modificacion = NOW(), 
			usuario_modificacion = ? WHERE idtransaccion = ? ";

        $idTransaccion = $dtoTransaccion->getId();
        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $FechaLlamada = $dtoTransaccion->getFecha();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioModificacion = $dtoTransaccion->getUsuarioModificacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $final);
        $pr->bindParam(3, $pesoTransaccion);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $FechaLlamada);
        $pr->bindParam(6, $UsuarioModificacion);
        $pr->bindParam(7, $idTransaccion);
        if ($pr->execute()) {
            $sqlLlamada = " UPDATE ca_llamada SET fecha = ?, fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idtransaccion = ? AND idllamada = ? ";
            $idTelefono = $dtoLlamada->getIdTelefono();
            //$fecha=$dtoLlamada->getFecha();

            $prLlamada = $connection->prepare($sqlLlamada);
            $prLlamada->bindParam(1, $FechaLlamada);
            $prLlamada->bindParam(2, $UsuarioModificacion);
            $prLlamada->bindParam(3, $idTransaccion);
            $prLlamada->bindParam(4, $idTelefono);
            if ($prLlamada->execute()) {

                $idCp = $dtoCompromisoPago->getId();
                $fechaCPG = $dtoCompromisoPago->getFechaCp();
                $montoCPG = $dtoCompromisoPago->getMontoCp();

                if ($idCp == '') {
                    if ($fechaCPG != '' && $montoCPG != '') {
                        $sqlCp = " INSERT INTO ca_compromiso_pago ( idtransaccion ,fecha_cp, monto_cp, observacion, fecha_creacion, usuario_creacion ) 
							VALUES ( ?,?,?,?,NOW(),? ) ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $idTransaccion);
                        $prCp->bindParam(2, $fechaCPG);
                        $prCp->bindParam(3, $montoCPG);
                        $prCp->bindParam(4, $observacion);
                        $prCp->bindParam(5, $UsuarioModificacion);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else {
                        //$connection->commit();
                        return true;
                    }
                } else {
                    if ($fechaCPG == '' && $montoCPG == '') {
                        $sqlCp = " DELETE FROM ca_compromiso_pago WHERE idcompromiso_pago = ? ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $idCp);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else if ($fechaCPG != '' && $montoCPG != '') {
                        $sqlCp = " UPDATE ca_compromiso_pago SET fecha_cp = ?, monto_cp = ?, observacion = ?, fecha_modificacion = NOW(), usuario_modificacion = ? 
							WHERE idtransaccion = ? AND idcompromiso_pago = ? ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $fechaCPG);
                        $prCp->bindParam(2, $montoCPG);
                        $prCp->bindParam(3, $observacion);
                        $prCp->bindParam(4, $UsuarioModificacion);
                        $prCp->bindParam(5, $idTransaccion);
                        $prCp->bindParam(6, $idCp);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else {
                        //$connection->commit();
                        return true;
                    }
                }
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateVisita(dto_transaccion $dtoTransaccion, dto_visita $dtoVisita) {
        $sql = " UPDATE ca_transaccion SET idtipo_gestion = ?, idfinal = ?, idpeso_transaccion = ?, observacion = ?, fecha_modificacion = NOW(), 
			usuario_modificacion = ? WHERE idtransaccion = ? ";

        $idTransaccion = $dtoTransaccion = $dtoTransaccion->getId();
        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioModificacion = $dtoTransaccion->getUsuarioModificacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $final);
        $pr->bindParam(3, $pesoTransaccion);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $UsuarioModificacion);
        $pr->bindParam(6, $idTransaccion);
        if ($pr->execute()) {

            $sqlVisita = " UPDATE ca_visita SET fecha_modificacion = NOW(), usuario_modificacion = ?  WHERE idtransaccion = ? AND idvisita = ? ";

            $idDireccion = $dtoVisita->getIdDireccion();

            $prVisita = $connection->prepare($sqlVisita);
            $prVisita->bindParam(1, $UsuarioModificacion);
            $prVisita->bindParam(2, $idTransaccion);
            $prVisita->bindParam(3, $idDireccion);
            if ($prVisita->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateVisitaCp(dto_transaccion $dtoTransaccion, dto_visita $dtoVisita, dto_compromiso_pago $dtoCompromisoPago) {
        $sql = " UPDATE ca_transaccion SET idtipo_gestion = ?, idfinal = ?, idpeso_transaccion = ?, observacion = ?, fecha = ?, fecha_modificacion = NOW(), 
			usuario_modificacion = ? WHERE idtransaccion = ? ";

        $idTransaccion = $dtoTransaccion->getId();
        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioModificacion = $dtoTransaccion->getUsuarioModificacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();
        $fecha = $dtoTransaccion->getFecha();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $final);
        $pr->bindParam(3, $pesoTransaccion);
        $pr->bindParam(4, $observacion);
        $pr->bindParam(5, $fecha);
        $pr->bindParam(6, $UsuarioModificacion);
        $pr->bindParam(7, $idTransaccion);
        if ($pr->execute()) {

            $sqlVisita = " UPDATE ca_visita SET fecha_modificacion = NOW(), usuario_modificacion = ?  WHERE idtransaccion = ? AND idvisita = ? ";

            $idDireccion = $dtoVisita->getIdDireccion();

            $prVisita = $connection->prepare($sqlVisita);
            $prVisita->bindParam(1, $UsuarioModificacion);
            $prVisita->bindParam(2, $idTransaccion);
            $prVisita->bindParam(3, $idDireccion);
            if ($prVisita->execute()) {

                $idCp = $dtoCompromisoPago->getId();
                $fechaCPG = $dtoCompromisoPago->getFechaCp();
                $montoCPG = $dtoCompromisoPago->getMontoCp();

                if ($idCp == '') {
                    if ($fechaCPG != '' && $montoCPG != '') {
                        $sqlCp = " INSERT INTO ca_compromiso_pago ( idtransaccion ,fecha_cp, monto_cp, observacion, fecha_creacion, usuario_creacion ) 
							VALUES ( ?,?,?,?,NOW(),? ) ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $idTransaccion);
                        $prCp->bindParam(2, $fechaCPG);
                        $prCp->bindParam(3, $montoCPG);
                        $prCp->bindParam(4, $observacion);
                        $prCp->bindParam(5, $UsuarioModificacion);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else {
                        //$connection->commit();
                        return true;
                    }
                } else {
                    if ($fechaCPG == '' && $montoCPG == '') {
                        $sqlCp = " DELETE FROM ca_compromiso_pago WHERE idcompromiso_pago = ? ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $idCp);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else if ($fechaCPG != '' && $montoCPG != '') {
                        $sqlCp = " UPDATE ca_compromiso_pago SET fecha_cp = ?, monto_cp = ?, observacion = ?, fecha_modificacion = NOW(), usuario_modificacion = ? 
							WHERE idtransaccion = ? AND idcompromiso_pago = ? ";
                        $prCp = $connection->prepare($sqlCp);
                        $prCp->bindParam(1, $fechaCPG);
                        $prCp->bindParam(2, $montoCPG);
                        $prCp->bindParam(3, $observacion);
                        $prCp->bindParam(4, $UsuarioModificacion);
                        $prCp->bindParam(5, $idTransaccion);
                        $prCp->bindParam(6, $idCp);
                        if ($prCp->execute()) {
                            //$connection->commit();
                            return true;
                        } else {
                            //$connection->rollBack();
                            return false;
                        }
                    } else {
                        //$connection->commit();
                        return true;
                    }
                }
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

}

?>