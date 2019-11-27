<?php

class PGSQL_PDOTransaccionDAO {

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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function insertDataCreationUpdateClienteCarteraLlamada(dto_transaccion $dto) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, fecha, observacion, fecha_creacion, usuario_creacion )  
				VALUES (?,?,?,?,?,NOW(),?) ";

        $TipoGestion = $dto->getIdTipoGestion();
        $ClienteCartera = $dto->getIdClienteCartera();
        $Final = $dto->getIdFinal();
        $Observacion = $dto->getObservacion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();
        $fecha = $dto->getFecha();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function insertDataLlamadaUpdateClienteCarteraLlamada(dto_transaccion $dtoTransaccion, dto_llamada $dtoLlamada, $cuenta) {
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, idusuario_servicio, fecha, fecha_creacion, usuario_creacion, is_llamada )  
				VALUES (?,?,?,?,?,NOW(),NOW(),?,1) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        //$final=$dtoTransaccion->getIdFinal();
        $estado = $dtoTransaccion->getIdEstado();
        $FechaLLamada = $dtoTransaccion->getFecha();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();
        $servicio = $dtoTransaccion->getIdServicio();
        //echo $servicio.'####';
        /*         * ******** */
        $usuario_servicio = $dtoTransaccion->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();



        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion, PDO::PARAM_INT);
        $pr->bindParam(2, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(3, $estado, PDO::PARAM_INT);
        $pr->bindParam(4, $observacion, PDO::PARAM_STR);
        //$pr->bindParam(5,$FechaLLamada);
        //$pr->bindParam(6,$pesoTransaccion);
        $pr->bindParam(5, $usuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioCreacion, PDO::PARAM_INT);
        /*         * *** */

        if ($pr->execute()) {
            //$sqlLlamada=" INSERT INTO ca_llamada ( fecha, idtransaccion, idtelefono, fecha_creacion, usuario_creacion ) 
//					VALUES (NOW(),?,?,NOW(),?) ";
//				$sqlLlamada=" INSERT INTO ca_llamada ( fecha, idtransaccion, idtelefono, fecha_creacion, usuario_creacion, inicio_tmo, fin_tmo, enviar_campo ) 
//					VALUES (NOW(),?,?,NOW(),?,?,?,? ) ";

            $sqlLlamada = " INSERT INTO ca_llamada ( fecha, idtransaccion, idtelefono, fecha_creacion, usuario_creacion, inicio_tmo, fin_tmo, enviar_campo, idcontacto, idmotivo_no_pago, nombre_contacto ) 
					VALUES (NOW(),?,?,NOW(),?,?,?,?,?,?,? ) ";

            $transaccion = $connection->lastInsertId();


            $telefono = $dtoLlamada->getIdTelefono();
            //$PesoLlamada=$dtoLlamada->getIdPesoLlamada();
            $UsuarioCreacionLlamada = $dtoLlamada->getUsuarioCreacion();
            /*             * ******* */
            $TMO_inicio = $dtoLlamada->getTmoInicio();
            $TMO_fin = $dtoLlamada->getTmoFin();
            $enviar_campo = $dtoLlamada->getEnviarCampo();
            $idcontacto = $dtoLlamada->getIdContacto();
            $nombre_contacto = $dtoLlamada->getNombreContacto();
            $idmotivo_no_pago = $dtoLlamada->getIdMotivoNoPago();
            /*             * ****** */

            $prll = $connection->prepare($sqlLlamada);
            //$prll->bindParam(1,$FechaLLamada);
            $prll->bindParam(1, $transaccion, PDO::PARAM_INT);
            $prll->bindParam(2, $telefono, PDO::PARAM_INT);
            //$prll->bindParam(3,$PesoLlamada);
            $prll->bindParam(3, $UsuarioCreacionLlamada, PDO::PARAM_INT);
            /*             * **** */
            $prll->bindParam(4, $TMO_inicio, PDO::PARAM_STR);
            $prll->bindParam(5, $TMO_fin, PDO::PARAM_STR);
            $prll->bindParam(6, $enviar_campo, PDO::PARAM_INT);
            $prll->bindParam(7, $idcontacto, PDO::PARAM_INT);
            $prll->bindParam(8, $idmotivo_no_pago, PDO::PARAM_INT);
            $prll->bindParam(9, $nombre_contacto, PDO::PARAM_STR);
            /*             * **** */
            if ($prll->execute()) {

                $llamada = $connection->lastInsertId();

                $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                $prCC = $connection->prepare($sqlCC);
                $prCC->bindParam(1, $transaccion);
                $prCC->bindParam(2, $UsuarioCreacionLlamada);
                $prCC->bindParam(3, $ClienteCartera);
                if ($prCC->execute()) {

                    for ($i = 0; $i < count($cuenta); $i++) {

                        $fecha_cp = (trim($cuenta[$i]['FechaCp']) == '') ? NULL : $cuenta[$i]['FechaCp'];
                        $monto_cp = (trim($cuenta[$i]['MontoCp']) == '') ? NULL : $cuenta[$i]['MontoCp'];
                        $estado_cuenta = (trim($cuenta[$i]['estado']) == '0' || trim($cuenta[$i]['estado']) == '') ? NULL : $cuenta[$i]['estado'];


                        //$sqlCuenta = " INSERT INTO ca_gestion_cuenta( idllamada, idcuenta, idestado, fecha_cp, monto_cp, usuario_creacion, fecha_creacion ) 
                        //VALUES ( ?,?,?,?,?,?,NOW() ) ";

                        $sqlCuenta = " INSERT INTO ca_gestion_cuenta( idllamada, idcuenta, idfinal, fecha_cp, monto_cp, usuario_creacion, fecha_creacion, numero_cuenta, moneda ) 
								VALUES ( ?,?,?,?,?,?,NOW(), ( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta = ? ),( SELECT moneda FROM ca_cuenta WHERE idcuenta = ? ) ) ";

                        $prCuenta = $connection->prepare($sqlCuenta);
                        $prCuenta->bindParam(1, $llamada, PDO::PARAM_INT);
                        $prCuenta->bindParam(2, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        $prCuenta->bindParam(3, $cestado_cuenta, PDO::PARAM_INT);
                        $prCuenta->bindParam(4, $fecha_cp);
                        $prCuenta->bindParam(5, $monto_cp);
                        $prCuenta->bindParam(6, $UsuarioCreacion);
                        /*                         * *** */
                        $prCuenta->bindParam(7, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        $prCuenta->bindParam(8, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        /*                         * *** */
                        if ($prCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        //$sqlUpdateCuenta = " UPDATE ca_cuenta SET ultimo_fecha_cp = ?, ultimo_monto_cp = ?, ultimo_idestado = ?, 
                        //fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idcuenta = ? ";
//								$sqlUpdateCuenta = " UPDATE ca_cuenta SET ultimo_fecha_cp = ?, ultimo_monto_cp = ?, ultimo_idfinal = ?, 
//								fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idcuenta = ? ";

                        $sqlUpdateCuenta = " UPDATE ca_cuenta SET ultimo_fecha_cp = ?, ultimo_monto_cp = ?, ultimo_idfinal = ?, 
								fecha_modificacion = NOW(), usuario_modificacion = ? , 
								ul_fecha = NOW(), ul_carga = ( SELECT idcarga_final FROM ca_final WHERE idfinal = ? ) , 
								ul_estado = ? , ul_fcpg = ? , ul_observacion = ? , ul_operador = ? , idcontacto = ?, idmotivo_no_pago = ? ,
								ul_telefono = ? 
								WHERE idcuenta = ? ";

                        $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                        $prUpdateCuenta->bindParam(1, $fecha_cp);
                        $prUpdateCuenta->bindParam(2, $monto_cp);
                        $prUpdateCuenta->bindParam(3, $estado, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(4, $UsuarioCreacion, PDO::PARAM_INT);
                        /*                         * ******* */
                        $prUpdateCuenta->bindParam(5, $estado, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(6, $estado, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(7, $fecha_cp, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(8, $observacion, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(9, $usuario_servicio, PDO::PARAM_INT);
                        /*                         * ******* */
                        $prUpdateCuenta->bindParam(10, $idcontacto, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(11, $idmotivo_no_pago, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(12, $telefono, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(13, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        /*                         * ****** */
                        if ($prUpdateCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        $sqlMLCuenta = " UPDATE ca_cuenta SET ml_fecha = NOW(), 
								ml_carga = ( SELECT idcarga_final FROM ca_final WHERE idfinal = ? ), 
								ml_estado = ?, ml_fcpg = ?, ml_observacion = ?, ml_operador = ?,  ml_telefono = ?,
								ml_peso_estado = ( SELECT peso FROM ca_final_servicio WHERE idfinal = ? AND idservicio = ? LIMIT 1 ) 
								WHERE ( SELECT peso FROM ca_final_servicio WHERE idfinal = ? AND idservicio = ? LIMIT 1 ) >= ml_peso_estado 
								AND idcuenta = ? ";
                        //echo $sqlMLCuenta;
                        $prMLCuenta = $connection->prepare($sqlMLCuenta);
                        $prMLCuenta->bindParam(1, $estado, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(2, $estado, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(3, $fecha_cp);
                        $prMLCuenta->bindParam(4, $observacion);
                        $prMLCuenta->bindParam(5, $usuario_servicio, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(6, $telefono, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(7, $estado, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(8, $servicio, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(9, $estado, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(10, $servicio, PDO::PARAM_INT);
                        $prMLCuenta->bindParam(11, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        if ($prMLCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }
                    }

                    //$connection->commit();
                    return true;
                } else {
                    //$connection->rollBack();
                    return false;
                    exit();
                }
                ////$connection->commit();
                //return true;
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function insertDataVisitaClienteCarteraVisitaCuentas($cuenta, dto_transaccion $dtoTransaccion, dto_visita $dtoVisita) {
        //$sql=" INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, fecha, fecha_creacion, usuario_creacion )  
        //VALUES (?,?,?,?,NOW(),NOW(),?) ";
        $sql = " INSERT INTO ca_transaccion ( idtipo_gestion, idcliente_cartera, idfinal, observacion, idusuario_servicio, fecha, fecha_creacion, usuario_creacion, is_visita )  
				VALUES (?,?,?,?,?,NOW(),NOW(),?,1) ";

        $TipoGestion = $dtoTransaccion->getIdTipoGestion();
        $ClienteCartera = $dtoTransaccion->getIdClienteCartera();
        $final = $dtoTransaccion->getIdFinal();
        $observacion = $dtoTransaccion->getObservacion();
        $UsuarioCreacion = $dtoTransaccion->getUsuarioCreacion();
        $pesoTransaccion = $dtoTransaccion->getIdPesoTransaccion();
        $servicio = $dtoTransaccion->getIdServicio();
        /*         * ******* */
        $usuario_servicio = $dtoTransaccion->getIdUsuarioServicio();
        /*         * ******* */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $TipoGestion);
        $pr->bindParam(2, $ClienteCartera);
        $pr->bindParam(3, $final);
        $pr->bindParam(4, $observacion);
        /*         * ** */
        //$pr->bindParam(5,$pesoTransaccion);
        $pr->bindParam(5, $usuario_servicio);
        /*         * ** */
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            $sqlVisita = " INSERT INTO ca_visita ( idtransaccion , iddireccion, fecha_creacion, usuario_creacion, fecha_visita, fecha_recepcion, idnotificador, descripcion_inmueble, idcontacto, nombre_contacto ) 
					VALUES( ?,?,NOW(),?,?,?,?,?,?,? ) ";

            $transaccion = $connection->lastInsertId();

            $direccion = $dtoVisita->getIdDireccion();
            $fecha_visita_v = $dtoVisita->getFechaVisita();
            $fecha_recepcion_v = $dtoVisita->getFechaRecepcion();
            $idnotificador = $dtoVisita->getIdNotificador();
            $idnotificador = ($idnotificador == 0) ? NULL : $idnotificador;
            $descripcion_inmueble = $dtoVisita->getDescripcionInmueble();
            $idcontacto = $dtoVisita->getIdContacto();
            $nombre_contacto = $dtoVisita->getNombreContacto();

            $prV = $connection->prepare($sqlVisita);
            $prV->bindParam(1, $transaccion, PDO::PARAM_INT);
            $prV->bindParam(2, $direccion, PDO::PARAM_INT);
            $prV->bindParam(3, $UsuarioCreacion, PDO::PARAM_INT);
            $prV->bindParam(4, $fecha_visita_v, PDO::PARAM_STR);
            $prV->bindParam(5, $fecha_recepcion_v, PDO::PARAM_STR);
            $prV->bindParam(6, $idnotificador, PDO::PARAM_INT);
            /*             * *** */
            $prV->bindParam(7, $descripcion_inmueble, PDO::PARAM_STR);
            $prV->bindParam(8, $idcontacto, PDO::PARAM_INT);
            $prV->bindParam(9, $nombre_contacto, PDO::PARAM_STR);
            /*             * ***** */
            if ($prV->execute()) {

                $visita = $connection->lastInsertId();

                $sqlCC = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";
                $prCC = $connection->prepare($sqlCC);
                $prCC->bindParam(1, $transaccion);
                $prCC->bindParam(2, $UsuarioCreacion);
                $prCC->bindParam(3, $ClienteCartera);
                if ($prCC->execute()) {

                    for ($i = 0; $i < count($cuenta); $i++) {

                        $fecha_cp = (trim($cuenta[$i]['FechaCp']) == '') ? NULL : $cuenta[$i]['FechaCp'];
                        $monto_cp = (trim($cuenta[$i]['MontoCp']) == '') ? NULL : $cuenta[$i]['MontoCp'];

                        $sqlCuenta = " INSERT INTO ca_gestion_cuenta( idvisita, idcuenta, fecha_cp, monto_cp, usuario_creacion, fecha_creacion, numero_cuenta, moneda ) 
								VALUES ( ?,?,?,?,?,NOW(), ( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta = ? ),( SELECT moneda FROM ca_cuenta WHERE idcuenta = ? ) ) ";

                        $prCuenta = $connection->prepare($sqlCuenta);
                        $prCuenta->bindParam(1, $visita, PDO::PARAM_INT);
                        $prCuenta->bindParam(2, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        //$prCuenta->bindParam(3,$cuenta[$i]['estado'],PDO::PARAM_INT);
                        $prCuenta->bindParam(3, $fecha_cp);
                        $prCuenta->bindParam(4, $monto_cp);
                        $prCuenta->bindParam(5, $UsuarioCreacion);
                        /*                         * *** */
                        $prCuenta->bindParam(6, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        $prCuenta->bindParam(7, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        /*                         * *** */
                        if ($prCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        //$sqlUpdateCuenta = " UPDATE ca_cuenta SET ultimo_fecha_cp = ?, ultimo_monto_cp = ?, ultimo_idfinal = ?, 
//								fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idcuenta = ? ";
//								
//								$prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
//								$prUpdateCuenta->bindParam(1,$fecha_cp);
//								$prUpdateCuenta->bindParam(2,$monto_cp);
//								$prUpdateCuenta->bindParam(3,$cuenta[$i]['estado'],PDO::PARAM_INT);
//								$prUpdateCuenta->bindParam(4,$UsuarioCreacion,PDO::PARAM_INT);
//								$prUpdateCuenta->bindParam(5,$cuenta[$i]['Cuenta'],PDO::PARAM_INT);
//								
//								if( $prUpdateCuenta->execute() ) {
//									
//								}else{
//									//$connection->rollBack();
//									return false;
//									exit();
//								}

                        $sqlUpdateCuenta = " UPDATE ca_cuenta SET uv_fecha = ?, 
								uv_carga = ( SELECT idcarga_final FROM ca_final WHERE idfinal = ? ), uv_estado = ?, 
								uv_fcpg = ?, uv_observacion = ?, uv_operador = ? , fecha_modificacion = NOW(), 
								usuario_modificacion = ? 
								WHERE idcuenta = ? ";

                        $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                        $prUpdateCuenta->bindParam(1, $fecha_visita_v, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(2, $final, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(3, $final, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(4, $fecha_cp, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(5, $observacion, PDO::PARAM_STR);
                        $prUpdateCuenta->bindParam(6, $usuario_servicio, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(7, $UsuarioCreacion, PDO::PARAM_INT);
                        $prUpdateCuenta->bindParam(8, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        if ($prUpdateCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }

                        $sqlMVCuenta = " UPDATE ca_cuenta SET mv_fecha = ?,
								mv_carga = ( SELECT idcarga_final FROM ca_final WHERE idfinal = ? ), mv_estado = ?, mv_fcpg = ?, mv_observacion = ?, mv_operador = ?, 
								mv_peso_estado = ( SELECT peso FROM ca_final_servicio WHERE idfinal = ? AND idservicio = ? LIMIT 1 )
								WHERE idcuenta = ? AND ( SELECT peso FROM ca_final_servicio WHERE idfinal = ? AND idservicio = ? LIMIT 1 ) >= mv_peso_estado ";

                        $prMVCuenta = $connection->prepare($sqlMVCuenta);
                        $prMVCuenta->bindParam(1, $fecha_visita_v, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(2, $final, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(3, $final, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(4, $fecha_cp, PDO::PARAM_STR);
                        $prMVCuenta->bindParam(5, $observacion, PDO::PARAM_STR);
                        $prMVCuenta->bindParam(6, $usuario_servicio, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(7, $final, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(8, $servicio, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(9, $cuenta[$i]['Cuenta'], PDO::PARAM_INT);
                        $prMVCuenta->bindParam(10, $final, PDO::PARAM_INT);
                        $prMVCuenta->bindParam(11, $servicio, PDO::PARAM_INT);
                        if ($prMVCuenta->execute()) {
                            
                        } else {
                            //$connection->rollBack();
                            return false;
                            exit();
                        }
                    }

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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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