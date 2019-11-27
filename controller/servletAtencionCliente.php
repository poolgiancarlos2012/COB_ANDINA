<?php

class servletAtencionCliente extends CommandController {

    public function doPost() {
        $daoAlerta = DAOFactory::getDAOAlerta('maria');
        $daoTransaccion = DAOFactory::getDAOTransaccion('maria');
        $daoReferenciaCliente = DAOFactory::getDAOReferenciaCliente('maria');
        $daoDireccion = DAOFactory::getDAODireccion('maria');
        $daoTelefono = DAOFactory::getDAOTelefono('maria');
        $daoNota = DAOFactory::getDAONota('maria');
        $daoConsulta = DAOFactory::getDAOConsulta('maria');
        $daoDetalleConsulta = DAOFactory::getDAODetalleConsulta('maria');
        $daoAyudaGestion = DAOFactory::getDAOAyudaGestion('maria');
        $daoEtiqueta = DAOFactory::getDAOEtiqueta('maria');
        $daoUsuarioServicio = DAOFactory::getDAOUsuarioServicio('maria');
        $daoObservacion = DAOFactory::getDAOObservacion('maria');
        $daoCorreo = DAOFactory::getDAOCorreo('maria');
        $daoHorarioAtencion = DAOFactory::getDAOHorarioAtencion('maria');
        $objFacturaDigitalDao = DAOFactory::getFacturaDigitalDAO('maria');
        $daoRefinanciamiento = DAOFactory::getRefinanciamientoDAO('maria');
        $daoClienteCartera = DAOFactory::getDAOClienteCartera('maria');
        $daoPago = DAOFactory::getDAOPago('maria');
        $daoJqgrid = DAOFactory::getDAOJqgrid('maria');
        $daoCuenta = DAOFactory::getDAOCuenta('maria');
        
        switch ($_POST['action']):
                case 'saveAcuerdoPago':
                    $dtoAcuerdoPago = new dto_acuerdo_pago();
                    $dtoAcuerdoPago->setUsuarioCreacion($_POST['usuarioServicio']);
                    $dtoAcuerdoPago->setIdClienteCartera($_POST['idClienteCartera']);
                    $dtoAcuerdoPago->setIdCuenta($_POST['idCuenta']);
                    $dtoAcuerdoPago->setNumeroPagare($_POST['numeroPagare']);
                    $dtoAcuerdoPago->setNumeroCuotas($_POST['numeroCuotas']);
                    $dtoAcuerdoPago->setFechaAcuerdo($_POST['fechaAcuerdo']);
                    $dtoAcuerdoPago->setValorAcuerdo($_POST['valorAcuerdo']);

                    $daoPago->insertAcuerdoPago($dtoAcuerdoPago, $_POST);

                break;
        		case 'ListarPagoRef':
        			
        			$dtoPago = new dto_pago ;	
        			$dtoPago->setIdClienteCartera($_POST['idcliente_cartera']);
        			$dtoPago->setIdCartera($_POST['idcartera']);
        			
        			echo json_encode( $daoPago->listarPagoRef( $dtoPago ) );
        		
        		break;
        		case 'GuardarPagoRef':
        			
        			$dtoPago = new dto_pago ;	
        			$dtoPago->setIdClienteCartera($_POST['idcliente_cartera']);
        			$dtoPago->setIdCartera($_POST['idcartera']);
        			$dtoPago->setCodigoCliente($_POST['codigo_cliente']);
        			$dtoPago->setMontoPagado($_POST['monto']);
        			$dtoPago->setMoneda($_POST['moneda']);
        			$dtoPago->setObservacion($_POST['observacion']);
        			$dtoPago->setUsuarioCreacion($_POST['usuario_creacion']);
        			
        			echo json_encode( $daoPago->grabarPagoRefinanciamiento( $dtoPago ) );
        			
        		
        		break;
        		case 'GuardarRef':
        			
        			$dtoCliCar = new dto_cliente_cartera ;
        			
        			$dtoCliCar->setId($_POST['idcliente_cartera']);
        			$dtoCliCar->setIdCartera($_POST['idcartera']);
        			$dtoCliCar->setCodigoCliente($_POST['codigo_cliente']);
        			$dtoCliCar->setDeuda($_POST['deuda']);
        			$dtoCliCar->setDescuentoRef( (double)$_POST['descuento'] );
        			$dtoCliCar->setInteresRef( (double)$_POST['interes'] );
        			$dtoCliCar->setComisionRef( (double)$_POST['comision'] );
        			$dtoCliCar->setMoraRef( (double)$_POST['mora'] );
        			$dtoCliCar->setGastosCobranzaRef( (double)$_POST['gastos_cobranza'] );
        			$dtoCliCar->setNumeroCuotasRef((int)$_POST['n_cuotas']);
        			$dtoCliCar->setTipoPagoRef($_POST['tipo_pago']);
        			$dtoCliCar->setFechaPrimerPagoRef($_POST['fecha_primer_pago']);
        			$dtoCliCar->setObservacionRef($_POST['observacion']);
        			$dtoCliCar->setUsuarioCreacion($_POST['usuario_creacion']);
					
        			echo ($daoClienteCartera->guardarRefinanciamiento($dtoCliCar))?json_encode(array('rst'=>true,'msg'=>'Refinanciamiento grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar refinanciamiento'));
        			
        		break;
                case 'ActualizarGRDDireccion':
                    
                        $dtoDireccion=new dto_direccion_ER2 ;

                        $dtoDireccion->setId($_POST['iddireccion']);
                        $dtoDireccion->setReferencia($_POST['dir_referencia']);
                        $dtoDireccion->setUsuarioModificacion($_POST['usuario_modificacion']);

                        echo ($daoDireccion->UpdateGRD($dtoDireccion))?json_encode(array('rst'=>true,'msg'=>'Direccion actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar direccion'));
                    
                break;
                case 'ActualizarLineaTelefono':
                        
                        //print_r($_POST);

                        $numero = $_POST['numero'];
                        $usuario_modificacion = $_POST['usuario_modificacion'];
                        $idlinea_telefono = $_POST['t1_linea'];
                        
                        echo ($daoTelefono->updateLineaTelefono( $idlinea_telefono, $numero, $usuario_modificacion ))?json_encode(array('rst'=>true,'msg'=>'Telefono actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar telefono'));

                break;
/*jmore050712*/
               case 'GrabarRefinanciamiento':

                        $dtoRefinanc = new dto_refinanciamiento ;
                        $dtoRefinanc->setIdCliente($_POST['idcliente']);
                        $dtoRefinanc->setIdClienteCartera($_POST['idcliente_cartera']);
                        $dtoRefinanc->setNumeroCuenta($_POST['numero_cuenta']);
                        $dtoRefinanc->setMoneda($_POST['moneda']);
                        $dtoRefinanc->setTotalDeuda($_POST['deuda']);
                        $dtoRefinanc->setDescuento($_POST['descuento']);
                        $dtoRefinanc->setNumeroCuota($_POST['n_cuotas']);
                        $dtoRefinanc->setTipoCuota($_POST['tipo_monto']);
                        $dtoRefinanc->setMontoCuota($_POST['monto_pago']);
                        $dtoRefinanc->setObservacion($_POST['observacion']);
                        $dtoRefinanc->setUsuarioCreacion($_POST['usuario_creacion']);
                        $dtoRefinanc->setIdUsuarioServicio($_POST['idusuario_servicio']);

                        echo ($daoRefinanciamiento->create($dtoRefinanc))?json_encode(array('rst'=>true,'msg'=>'Refinanciamiento grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar refinanciamiento'));	

                break;/*jmore050712*/               
                case 'ActualizarVisitaGrd':
                		
                		$fecha_visita = ( trim($_POST['FECHA_VISITA']) == '' )? NULL : trim($_POST['FECHA_VISITA']);
                		// $fecha_recepcion = ( trim($_POST['vis_fecha_recepcion']) == '' )? NULL : trim($_POST['vis_fecha_recepcion']);
                		$monto_cp = ( trim($_POST['MONTO_CP']) == '' )? NULL : trim($_POST['MONTO_CP']);
                		$fecha_cp = ( trim($_POST['FECHA_CP']) == '' )? NULL : trim($_POST['FECHA_CP']);
                		
                		
                        $dtoVisita = new dto_visita ;
                        $dtoVisita->setIdcuenta($_POST['IDCUENTA']);
                        $dtoVisita->setIdfinal($_POST['ESTADO']);
                        $dtoVisita->setId($_POST['id']);
                        // $dtoVisita->setIdNotificador($_POST['notificador']);
                        $dtoVisita->setUsuarioModificacion($_POST['usuario_modificacion']);
                        $dtoVisita->setFechaVisita($fecha_visita);
                        // $dtoVisita->setFechaRecepcion($fecha_recepcion);
                        $dtoVisita->setObservacion($_POST['OBS']);
                        $dtoVisita->setMontoCp($monto_cp);
                        $dtoVisita->setFechaCp($fecha_cp);
                        
                        echo ($daoTransaccion->UpdateVisitaN( $dtoVisita ))?json_encode(array('rst'=>true,'msg'=>'Notificador actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar notificador'));
                        
                break;
                case 'save_visita_comercial': // piro 10-12-2014
                
                $dtoVisitaComercial= new dto_visita_comercial;
               
                $dtoVisitaComercial->setIdClienteCartera(trim($_POST['idClienteCartera']));
                $dtoVisitaComercial->setIdDireccion(trim($_POST['idDireccion']));
                $dtoVisitaComercial->setIdNotificador(trim($_POST['idNotificador']));
                $dtoVisitaComercial->setIdCuenta(trim($_POST['idCuenta']));
                $dtoVisitaComercial->setIdUsuarioServicio(trim($_POST['idUsuarioServicio']));
                $dtoVisitaComercial->setFechaCP(trim($_POST['fechaCompromisoPago']));
                $dtoVisitaComercial->setFechaVisita(trim($_POST['fechaVisita']));
                $dtoVisitaComercial->setHoraVisita($_POST['horaVisita']);
                $dtoVisitaComercial->setIdGiroNegocio(trim($_POST['idGiroNegocio']));
                $dtoVisitaComercial->setDetalleGiroExtraNegocio(trim($_POST['detalleGiroExtraNegocio']));
                $dtoVisitaComercial->setIdAfrontarPagoNegocio(trim($_POST['idAfrontarPago']));
                $dtoVisitaComercial->setDetalleAfrontarPagoNegocio(trim($_POST['detalleAfronPago']));
                $dtoVisitaComercial->setIdmotivoAtrasoNegocio(trim($_POST['idMotivoAtraso']));
                $dtoVisitaComercial->setDetalleMotivoAtrasoNegocio(trim($_POST['detalleMotAtr']));
                $dtoVisitaComercial->setIdCuestionacobranzaNegocio(trim($_POST['idCuestionaCobranza']));            
                $dtoVisitaComercial->setIdObservacionEspecialistaNegocio($_POST['idObservacionEspecialista']);
                $dtoVisitaComercial->setCaracteristicaNegocioEnActividad($_POST['caracteristicaNegocioEnActividad']);
                $dtoVisitaComercial->setCaracteristicaNegocioTieneExistencias($_POST['caracteristicaNegocioTieneExistencias']);
                $dtoVisitaComercial->setCaracteristicaNegocioLaborArtesanal($_POST['caracteristicaNegocioLaborArtesanal']);
                $dtoVisitaComercial->setCaracteristicaNegocioLocalPropio($_POST['caracteristicaNegocioLocalPropio']);
                $dtoVisitaComercial->setCaracteristicaNegocioOficinaAdministrativa($_POST['caracteristicaNegocioOficinaAdministrativa']);
                $dtoVisitaComercial->setMenorigual10personas($_POST['menorigual10personas']);
                $dtoVisitaComercial->setMayor10personas($_POST['mayor10personas']);
                $dtoVisitaComercial->setCaracteristicaNegocioPlantaIndustrial($_POST['caracteristicaNegocioPlantaIndustrial']);
                $dtoVisitaComercial->setCaracteristicaNegocioCasaNegocio($_POST['caracteristicaNegocioCasaNegocio']);
                $dtoVisitaComercial->setCaracteristicaNegocioPuertaCalle($_POST['caracteristicaNegocioPuertaCalle']);
                $dtoVisitaComercial->setCaracteristicaNegocioActividadAdicional($_POST['caracteristicaNegocioActividadAdicional']);
                $dtoVisitaComercial->setTipoVisita($_POST['tipoVisita']);
                $dtoVisitaComercial->setNumerovisita($_POST['numeroVisita']);
                $dtoVisitaComercial->setNuevaDireccion($_POST['nuevaDireccion']);
                $dtoVisitaComercial->setNuevoTelefono($_POST['nuevoTelefono']);
                $dtoVisitaComercial->setDireccionVisita2($_POST['direccionVisita2']);
                
               
                echo ($daoTransaccion->insertDataVisitaComercial($dtoVisitaComercial)) ? json_encode(array('rst' => true, 'msg' => 'Visita grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar visita'));

                break;
                case 'ActualizarEstado':
                		
                        $dtoLlamada = new dto_llamada ;                        
                              $dtoLlamada->setId($_POST['id']);
                              $dtoLlamada->setIdFinal($_POST['ESTADO']);
                              $dtoLlamada->setUsuarioModificacion($_POST['usuario_modificacion']);
                              $dtoLlamada->setFechaCp($_POST['FECHA_CP']);
                              $dtoLlamada->setMontoCp($_POST['MONTO_CP']);
                              $dtoLlamada->setObservaciones($_POST['OBS']);

                        // $idllamada=$_POST['id'];
                        // $idfinal=$_POST['estado'];
                        // $usuario_modificacion=$_POST['usuario_modificacion'];
                        // $fechacp=$_POST['fecha_cp'];
                        // $montocp=$_POST['monto_cp'];
                        // $observaciones=$_POST['observacion'];

                        // echo $idllamada."----".$idfinal."----".$usuario_modificacion."----".$fechacp."----".$montocp."----".$observaciones;

                        $cartera=$_POST['cartera'];
                        $cliente_cartera=$_POST['cliente_cartera'];
                        
                        echo ($daoTransaccion->UpdateState( $dtoLlamada,$cartera,$cliente_cartera ))?json_encode(array('rst'=>true,'msg'=>'Estado actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar estado'));
                        // echo ($daoTransaccion->UpdateState( $idllamada,$idfinal,$usuario_modificacion,$fechacp,$montocp,$observaciones,$cartera,$cliente_cartera ))?json_encode(array('rst'=>true,'msg'=>'Estado actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar estado'));
                        
                break;
                case 'GrabarCuotificacion':
                        
                        $dtoRefinanciamiento = new dto_refinanciamiento ;
                        
                        $dtoRefinanciamiento->setIdClienteCartera($_POST['idcliente_cartera']);
                        $dtoRefinanciamiento->setIdUsuarioServicio($_POST['idusuario_servicio']);
                        $dtoRefinanciamiento->setIdTelefono($_POST['idtelefono']);
                        $dtoRefinanciamiento->setIdFinal($_POST['estado']);
                        $dtoRefinanciamiento->setObjecion($_POST['objecion']);
                        $dtoRefinanciamiento->setObservacion($_POST['observacion']);
                        $dtoRefinanciamiento->setTotalDeuda($_POST['deuda']);
                        $dtoRefinanciamiento->setNumeroCuota($_POST['numero_cuotas']);
                        $dtoRefinanciamiento->setTipoCuota($_POST['tipo']);
                        $dtoRefinanciamiento->setMontoCuota($_POST['monto_cuota']);
                        $dtoRefinanciamiento->setUsuarioCreacion($_POST['usuario_creacion']);
                        
                        $cuentas = json_decode( $_POST['cuentas'],true );
                        
                        echo ($daoRefinanciamiento->insert($dtoRefinanciamiento,$cuentas))?json_encode(array('rst'=>true,'msg'=>'Refinanciamiento grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar refinanciamiento'));                        
                break;
                case 'ActualizarStatusTelefono':
                
                        $dtoTelefono = new dto_telefono_ER2 ;
                        
                        $dtoTelefono->setStatus($_POST['status']);
                        $dtoTelefono->setUsuarioModificacion($_POST['usuario_modificacion']);
                        $dtoTelefono->setCodigoCliente($_POST['codigo_cliente']);
                        $dtoTelefono->setNumero($_POST['numero']);
                        
                        echo ($daoTelefono->UpdateStatus($dtoTelefono))?json_encode(array('rst'=>true,'msg'=>'Status actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar status'));
                        
                break;
                case 'ActualizarStatusDireccion':

                        $dtoDireccion=new dto_direccion_ER2 ;

                        $dtoDireccion->setId($_POST['id']);
                        $dtoDireccion->setStatus($_POST['dir_status']);
                        $dtoDireccion->setUsuarioModificacion($_POST['usuario_modificacion']);

                        echo ($daoDireccion->UpdateStatus($dtoDireccion))?json_encode(array('rst'=>true,'msg'=>'Status actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar status'));

                break;
		case 'GuardarObservacion':

                $idcliente = $_POST['idcliente'];
                $observacion = $_POST['observacion'];
                $usuario_creacion = $_POST['usuario_creacion'];

                $dtoObservacion = new dto_observacion();
                $dtoObservacion->setObservacion($observacion);
                $dtoObservacion->setIdCliente($idcliente);
                $dtoObservacion->setUsuarioCreacion($usuario_creacion);

                echo ($daoObservacion->insert($dtoObservacion)) ? json_encode(array('rst' => true, 'msg' => 'Observacion grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar observacion'));

                break;
            case 'sendCorreoFacturaDigital' :
                $clientes = json_decode(htmlspecialchars_decode(stripcslashes($_POST['clientes'])));
                $enviadosAll = true;
                foreach ($clientes as $key => $obj) {
                    if ($obj->enviado == '0') {
                        $fechaVencimiento = explode('-', $obj->fechaVencimiento);
                        $fechaVencimiento = $fechaVencimiento[2] . '/' . $fechaVencimiento[1] . '/' . $fechaVencimiento[0];
                        $objDtoFacturaDigital = new dto_factura_digital();
                        $objDtoFacturaDigital->setCorreo($obj->correo);
                        $objDtoFacturaDigital->setRutaAbsoluta($obj->rutaAbsoluta);
                        $objDtoFacturaDigital->setSolicita($obj->solicita);
                        $objDtoFacturaDigital->setFechaVencimiento($fechaVencimiento);
                        if (!$objFacturaDigitalDao->sendEmail($objDtoFacturaDigital)) {
                            $enviadosAll = false;
                        } else {
                            $objFacturaDigitalDao->emailEnviado($obj);
                        }
                    }
                }
                if ($enviadosAll) {
                    echo json_encode(array('rst' => true, 'msg' => 'Emails enviados correctamente'));
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Nose llegaron a enviar todos los emails'));
                }
                break;
            case 'uploadFactura' :
                $objDtoFacturaDigital = new dto_factura_digital();
                $objDtoFacturaDigital->setObservacion($_POST['observacion']);
                $objDtoFacturaDigital->setSolicita($_POST['solicito']);
                $objDtoFacturaDigital->setIdUsuarioServicio($_POST['idUsuarioServicio']);
                $objDtoFacturaDigital->setUsuarioCreacion($_SESSION['cobrast']['idusuario_servicio']);
                $objDtoFacturaDigital->setCorreo($_POST['correo']);
                $objDtoFacturaDigital->setFechaVencimiento($_POST['fechaVencimiento']);
                $objDtoFacturaDigital->setIdClienteCartera($_POST['idClienteCartera']);
                $objDtoFacturaDigital->setIdcuenta($_POST['idCuenta']);
                echo $objFacturaDigitalDao->uploadFacturaDigital($objDtoFacturaDigital, $_FILES, $_SESSION['cobrast']['servicio'], 'fileFacturaDigital');
                break;
            case 'save_correo':

                $idcliente = $_POST['idcliente'];
                $correo = $_POST['correo'];
                $observacion = $_POST['observacion'];
                $usuario_creacion = $_POST['usuario_creacion'];

                $dtoCorreo = new dto_correo;
                $dtoCorreo->setCorreo($correo);
                $dtoCorreo->setObservacion($observacion);
                $dtoCorreo->setIdCliente($idcliente);
                $dtoCorreo->setUsuarioCreacion($usuario_creacion);

                echo ($daoCorreo->insert($dtoCorreo)) ? json_encode(array('rst' => true, 'msg' => 'Correo grabado correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar correo'));

                break;
            case 'save_horario_atencion':

                $idcliente = $_POST['idcliente'];
                $horario_atencion = $_POST['horario_atencion'];
                $observacion = $_POST['observacion'];
                $usuario_creacion = $_POST['usuario_creacion'];

                $dtoHorarioAtencion = new dto_horario_atencion;
                $dtoHorarioAtencion->setHora($horario_atencion);
                $dtoHorarioAtencion->setObservacion($observacion);
                $dtoHorarioAtencion->setIdCliente($idcliente);
                $dtoHorarioAtencion->setUsuarioCreacion($usuario_creacion);

                echo ($daoHorarioAtencion->insert($dtoHorarioAtencion)) ? json_encode(array('rst' => true, 'msg' => 'Horario de atencion grabado correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar horario de atencion'));

                break;
            case 'DeleteTelefono':

                $idTelefono = $_POST['idTelefono'];

                $dtoTelefono = new dto_telefono_ER2;
                $dtoTelefono->setId($idTelefono);

                echo ($daoTelefono->inactive($dtoTelefono)) ? json_encode(array('rst' => true, 'msg' => 'Telefono eliminado correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al eliminar telefono'));

                break;
            case 'update_anexo':

                $idservicio = ( trim($_POST['Servicio']) == '' ) ? NULL : trim($_POST['Servicio']);
                $idusuario_servicio = ( trim($_POST['UsuarioServicio']) == '' ) ? NULL : trim($_POST['UsuarioServicio']);
                $anexo = ( trim($_POST['Anexo']) == '' ) ? NULL : trim($_POST['Anexo']);

                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoUsuarioServicio->setId($idusuario_servicio);
                $dtoUsuarioServicio->setIdServicio($idservicio);
                $dtoUsuarioServicio->setAnexo($anexo);

                $data = $daoUsuarioServicio->buscarAnexo($dtoUsuarioServicio);

                //if( $data[0]['COUNT']==0 ) {

                if ($daoUsuarioServicio->updateAnexo($dtoUsuarioServicio)) {
                    /*$memcached = new ConnectionMemcached();
                    if ($memcached->getValue($_SESSION['cobrast']['idusuario_servicio'])) {
                        if ($memcached->replace($_SESSION['cobrast']['idusuario_servicio'], $anexo)) {
                            echo json_encode(array('rst' => true, 'msg' => 'Anexo grabado correctamente'));
                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error al grabar anexo'));
                        }
                    } else {
                        if ($memcached->setValue($_SESSION['cobrast']['idusuario_servicio'], $anexo)) {
                            echo json_encode(array('rst' => true, 'msg' => 'Anexo grabado correctamente'));
                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error al grabar anexo'));
                        }
                    }
                    $memcached->close();*/
                    $_SESSION['cobrast']['anexo'] = $anexo;
                    echo json_encode(array('rst' => true, 'msg' => 'Anexo grabado correctamente'));
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al grabar anexo'));
                }


                //}else{
                //echo json_encode(array('rst'=>false,'msg'=>'Anexo ingresado ya existe'));
                //}

                break;
            case 'GuardarEtiqueta':
                $dtoEtiqueta = new dto_etiqueta;
                $dtoEtiqueta->setNombre($_POST['Nombre']);
                $dtoEtiqueta->setDescripcion($_POST['Descripcion']);
                $dtoEtiqueta->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $dtoEtiqueta->setIdUsuarioServicio($_POST['UsuarioServicio']);
                echo ($daoEtiqueta->save($dtoEtiqueta)) ? json_encode(array('rst' => true, 'msg' => 'Etiqueta creada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al crear etiqueta'));
                break;
            case 'DesmarcarNotasComoImportante':
                $dtoNota = new dto_nota;
                $dtoNota->setUsuarioModificacion($_POST['UsuarioModificacion']);
                $dtoNota->setId($_POST['Id']);
                echo ($daoNota->DesmarcarImportante($dtoNota, $_POST['notas'])) ? json_encode(array('rst' => true, 'msg' => 'Nota desmarcada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al desmarcar nota'));
                break;
            case 'MarcarNotasComoImportante':
                $dtoNota = new dto_nota;
                $dtoNota->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($daoNota->MarcarImportante($dtoNota, $_POST['notas'])) ? json_encode(array('rst' => true, 'msg' => 'Nota marcada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al marcar nota'));
                break;
            case 'MarcarLeidoNotas':
                $dtoNota = new dto_nota;
                $dtoNota->setId($_POST['Id']);
                $dtoNota->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($daoNota->MarcarLeido($dtoNota)) ? json_encode(array('rst' => true, 'msg' => 'Nota marcada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al marcar nota'));
                break;
            case 'DeleteAllNota':

                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setUsuarioModificacion($_POST['UsuarioModificacion']);
                $dtoClienteCartera->setIdUsuarioServicio($_POST['UsuarioServicio']);
                $dtoClienteCartera->setIdCartera($_POST['Cartera']);

                echo ($daoNota->deleteAll($dtoClienteCartera)) ? json_encode(array('rst' => true, 'msg' => 'Notas eliminadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al eliminar notas'));
                break;
            case 'MarcarNoLeidoNotas':

                $dtoNota = new dto_nota;
                $dtoNota->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($daoNota->MarcarNoLeida($dtoNota, $_POST['notas'])) ? json_encode(array('rst' => true, 'msg' => 'Notas marcadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al marcar notas'));
                break;
            case 'DeleteNota':

                $dtoNota = new dto_nota;
                $dtoNota->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($daoNota->deleteById($dtoNota, $_POST['notas'])) ? json_encode(array('rst' => true, 'msg' => 'Notas eliminadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al eliminar notas'));
                break;
            case 'GuardarAlerta':
                $dtoAlerta = new dto_alerta;
                $dtoAlerta->setFechaAlerta($_POST['FechaAlerta']);
                $dtoAlerta->setDescripcion($_POST['Descripcion']);
                $dtoAlerta->setIdClienteCartera($_POST['ClienteCartera']);
                $dtoAlerta->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $dtoAlerta->setIdUsuarioServicio($_POST['UsuarioServicio']);
                $dtoAlerta->setIdServicio($_POST['Servicio']);

                echo ($daoAlerta->insertDataCreation($dtoAlerta)) ? json_encode(array('rst' => true, 'msg' => 'Alerta grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar alerta'));
                break;
			//~ Vic I
			case 'SaldoInicialNroContrato':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->krySaldoInicialNroContrato($dtoClienteCartera)));
				break;
            case 'SaldoInicialListar':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->krySaldoInicial($dtoClienteCartera)));
				break;
			case 'CuotasNroContrato':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->kryCuotasNroContrato($dtoClienteCartera)));
				break;
			case 'ListarCuotasPendientes':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->kryCuotasPendientes($dtoClienteCartera)));
				break;
			case 'FiadioresNroContrato':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->kryFiadoresNroContrato($dtoClienteCartera)));
				break;
			case 'ListarFiadorPendientes':
				$dtoClienteCartera = new dto_cliente_cartera;
				$dtoClienteCartera->setId($_POST['ClienteCartera']);
				echo json_encode(array('data' => $daoConsulta->kryFiadoresPendientes($dtoClienteCartera)));
				break;
			//~ Vic F
            case 'DeleteAlerta':
                $dtoAlerta = new dto_alerta;
                $dtoAlerta->setId($_POST['Alerta']);
                $dtoAlerta->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($daoAlerta->delete($dtoAlerta)) ? json_encode(array('rst' => true, 'msg' => 'Alerta eliminada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al eliminar alerta'));
                break;
            case 'GuardarAgendado':
                $dtoTransaccion = new dto_transaccion;
                //$dtoTransaccion->setIdTipoGestion($_POST['TipoGestion']);
                $dtoTransaccion->setIdTipoGestion(2);
                $dtoTransaccion->setIdFinal($_POST['Final']);
                $dtoTransaccion->setIdClienteCartera($_POST['ClienteCartera']);
                $dtoTransaccion->setObservacion($_POST['Observacion']);
                $dtoTransaccion->setFecha($_POST['FechaAgendar']);
                $dtoTransaccion->setUsuarioCreacion($_POST['UsuarioCreacion']);

                $dtoCP = new dto_compromiso_pago;
                $dtoCP->setFechaCp($_POST['FechaCP']);
                $dtoCP->setMontoCp($_POST['MontoCP']);
                $dtoCP->setObservacion($_POST['Observacion']);
                $dtoCP->setUsuarioCreacion($_POST['UsuarioCreacion']);

                if ($_POST['FechaCP'] == '' && $_POST['MontoCP'] == '') {
                    echo ($daoTransaccion->insertDataCreation($dtoTransaccion)) ? json_encode(array('rst' => true, 'msg' => 'Agenda grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al agendar'));
                } else {
                    echo ($daoTransaccion->insertDataCP($dtoTransaccion, $dtoCP)) ? json_encode(array('rst' => true, 'msg' => 'Agenda grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar Agenda'));
                }

                break;
            case 'save_visita':

                /*                 * ******* */
                $cuentas = json_decode(str_replace("\\", "", $_POST['Cuentas']), true);
                /*                 * ******* */
                $idcontacto = (trim($_POST['Contacto']) == '0' || trim($_POST['Contacto']) == '') ? NULL : trim($_POST['Contacto']);
                $nombre_contacto = (trim($_POST['NombreContacto']) == '') ? NULL : trim($_POST['NombreContacto']);
                $motivo_no_pago = ( trim($_POST['MotivoNoPago']) == '0' ) ? NULL : trim($_POST['MotivoNoPago']);
                $parentesco = ( trim($_POST['Parentesco']) == '0' ) ? NULL : trim($_POST['Parentesco']);
                /*                 * ******** */
                $descripcion_inmueble = ( trim($_POST['DescripcionInmueble']) == '' ) ? NULL : trim($_POST['DescripcionInmueble']);
                $hora_ubicacion = ( trim($_POST['HoraUbicacion']) == '' ) ? NULL : trim($_POST['HoraUbicacion']).':00';
                $hora_salida = ( trim($_POST['HoraSalida']) == '' ) ? NULL : trim($_POST['HoraSalida']).':00';
                
                $direcciones_est = ( isset($_POST['DireccionEst']) )?$_POST['DireccionEst']:array();

                $dtoTransaccion = new dto_transaccion;
                //$dtoTransaccion->setIdTipoGestion($_POST['TipoGestion']);
                $dtoTransaccion->setIdTipoGestion(2);
                $dtoTransaccion->setIdFinal($_POST['Final']);
                $dtoTransaccion->setIdClienteCartera($_POST['ClienteCartera']);
                $dtoTransaccion->setObservacion($_POST['Observacion']);
                $dtoTransaccion->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $dtoTransaccion->setFecha($_POST['FechaVisita']);
                $dtoTransaccion->setIdPesoTransaccion(@$_POST['Prioridad']);
                /*                 * ****** */
                $dtoTransaccion->setIdUsuarioServicio($_POST['UsuarioServicio']);
                $dtoTransaccion->setIdServicio($_POST['Servicio']);
                /*                 * ****** */

                $dtoVisita = new dto_visita;
                $dtoVisita->setIdDireccion($_POST['Direccion']);
                $dtoVisita->setFechaVisita($_POST['FechaVisita']);
                // $dtoVisita->setFechaRecepcion($_POST['FechaRecepcion']);
                // $dtoVisita->setIdNotificador($_POST['Notificador']);
                /*                 * ******* */

                $idestado_cliente=$_POST['idestado_cliente'];

                $dtoVisita->setIdContacto($idcontacto);
                $dtoVisita->setNombreContacto($nombre_contacto);
                $dtoVisita->setIdMotivoNoPago($motivo_no_pago);
                $dtoVisita->setIdParentesco($parentesco);
                $dtoVisita->setHoraUbicacion($hora_ubicacion);
                $dtoVisita->setHoraSalida($hora_salida);
                $dtoVisita->setDescripcionInmueble($descripcion_inmueble);
                /*                 * ******* */
                
                if( $_POST['HoraUbicacion'] != '' ){
                
                        $dtoHorarioAtencion = new dto_horario_atencion;
                        $dtoHorarioAtencion->setHora($hora_ubicacion);
                        $dtoHorarioAtencion->setIdCliente($_POST['IdCliente']);
                        $dtoHorarioAtencion->setUsuarioCreacion($_POST['UsuarioCreacion']);
        
                        @$daoHorarioAtencion->insert($dtoHorarioAtencion);
                
                }
                
                /******************/

                echo ($daoTransaccion->insertDataVisitaClienteCarteraVisitaCuentas($cuentas,$idestado_cliente, $dtoTransaccion, $dtoVisita, $_POST['Peso'], $_POST['IdCarga'], $direcciones_est)) ? json_encode(array('rst' => true, 'msg' => 'Visita grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar visita'));
                
                

                break;
            case 'update_visita':
                $dtoTransaccion = new dto_transaccion;
                $dtoTransaccion->setId($_POST['IdTransaccion']);
                $dtoTransaccion->setIdTipoGestion(2);
                $dtoTransaccion->setIdFinal($_POST['Final']);
                $dtoTransaccion->setObservacion($_POST['Observacion']);
                $dtoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
                $dtoTransaccion->setFecha($_POST['FechaVisita']);
                $dtoTransaccion->setIdPesoTransaccion($_POST['Prioridad']);

                $dtoVisita = new dto_visita;
                $dtoVisita->setId($_POST['IdVisita']);
                $dtoVisita->setIdDireccion($_POST['Direccion']);

                $dtoCP = new dto_compromiso_pago;
                $dtoCP->setId($_POST['IdCpg']);
                $dtoCP->setFechaCp($_POST['FechaCP']);
                $dtoCP->setMontoCp($_POST['MontoCP']);
                $dtoCP->setObservacion($_POST['Observacion']);
                $dtoCP->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($daoTransaccion->updateVisitaCp($dtoTransaccion, $dtoVisita, $dtoCP)) ? json_encode(array('rst' => true, 'msg' => 'Visita actualizada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar visita'));

                break;
            case 'save_direccion':

                $idorigen = (trim($_POST['Origen']) == 0) ? NULL : trim($_POST['Origen']);
                $idtipo_referencia = (trim($_POST['TipoReferencia']) == 0) ? NULL : trim($_POST['TipoReferencia']);
                $cuentas = json_decode($_POST['IdCuenta'],true);

                $dtoDireccion = new dto_direccion_ER2;
                /*                 * ********** */
                $dtoCliente = new dto_cliente;

                $dtoCliente->setCodigo($_POST['CodigoCliente']);
                /*                 * ********** */
                $dtoDireccion->setIdCliente($_POST['Cliente']);
                $dtoDireccion->setIdClienteCartera($_POST['IdClienteCartera']);
                $dtoDireccion->setIdCartera($_POST['Cartera']);
                $dtoDireccion->setIdOrigen($idorigen);
                $dtoDireccion->setIdTipoReferencia($idtipo_referencia);
                $dtoDireccion->setDireccion($_POST['Direccion']);
                $dtoDireccion->setReferencia($_POST['Referencia']);
                $dtoDireccion->setUbigeo($_POST['Ubigeo']);
                $dtoDireccion->setDepartamento($_POST['Departamento']);
                $dtoDireccion->setProvincia($_POST['Provincia']);
                $dtoDireccion->setDistrito($_POST['Distrito']);
                $dtoDireccion->setObservacion($_POST['Observacion']);
                $dtoDireccion->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $dtoDireccion->setIsCampo(@$_POST['IsCampo']);

               //echo ($daoDireccion->insert($dtoDireccion, $dtoCliente, $cuentas)) ? json_encode(array('rst' => true, 'msg' => 'Direccion grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar direccion'));
                echo json_encode( $daoDireccion->insert($dtoDireccion, $dtoCliente, $cuentas) );
                
                break;
            case 'update_direccion':

                $idorigen = (trim($_POST['Origen']) == 0) ? NULL : trim($_POST['Origen']);
                $idtipo_referencia = (trim($_POST['TipoReferencia']) == 0) ? NULL : trim($_POST['TipoReferencia']);

                $dtoDireccion = new dto_direccion_ER2;
                $dtoDireccion->setId($_POST['Id']);
                $dtoDireccion->setIdOrigen($idorigen);
                $dtoDireccion->setIdTipoReferencia($idtipo_referencia);
                $dtoDireccion->setDireccion($_POST['Direccion']);
                $dtoDireccion->setReferencia($_POST['Referencia']);
                $dtoDireccion->setUbigeo($_POST['Ubigeo']);
                $dtoDireccion->setDepartamento($_POST['Departamento']);
                $dtoDireccion->setProvincia($_POST['Provincia']);
                $dtoDireccion->setDistrito($_POST['Distrito']);
                $dtoDireccion->setObservacion($_POST['Observacion']);
                $dtoDireccion->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($daoDireccion->update($dtoDireccion)) ? json_encode(array('rst' => true, 'msg' => 'Direccion actualizada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar direccion'));
                break;
            case 'deshabilitarTelefono':
                    
                    return false;
                    

                    $dtoTelefono= new dto_telefono_ER2;

                    $numero=$_POST['numero'];
                    $codigo_cliente=$_POST['codigo_cliente'];

                    echo ($daoTelefono->DeshabilitarTelefono($numero,$codigo_cliente));
                break;
            case 'save_telefono':

                $idorigen = (trim($_POST['Origen']) == 0) ? NULL : trim($_POST['Origen']);
                $idtipo_referencia = (trim($_POST['TipoReferencia']) == 0) ? NULL : trim($_POST['TipoReferencia']);
                $idtipo_telefono = (trim($_POST['TipoTelefono']) == 0) ? NULL : trim($_POST['TipoTelefono']);
                
                $cuentas = json_decode($_POST['IdCuenta'],true);

                $dtoTelefono = new dto_telefono_ER2;
                /*                 * ********** */
                $dtoCliente = new dto_cliente;

                $dtoCliente->setCodigo($_POST['CodigoCliente']);
                /*                 * ********** */
                $dtoTelefono->setIdCliente($_POST['Cliente']);
                $dtoTelefono->setIdClienteCartera($_POST['IdClienteCartera']);
                $dtoTelefono->setIdCartera($_POST['Cartera']);
                $dtoTelefono->setIdOrigen($idorigen);
                $dtoTelefono->setIdTipoReferencia($idtipo_referencia);
                $dtoTelefono->setIdTipoTelefono($idtipo_telefono);
                $dtoTelefono->setNumero($_POST['Numero']);
                $dtoTelefono->setAnexo($_POST['Anexo']);
                $dtoTelefono->setObservacion($_POST['Observacion']);
                $dtoTelefono->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $line = $_POST['LineaTelefono'];
                if ($line == 0) {
                    $line = null;
                }
                $dtoTelefono->setIdLineaTelefono($line);
                $dtoTelefono->setIsCampo(@$_POST['IsCampo']);


                //echo ($daoTelefono->insert($dtoTelefono, $dtoCliente, $cuentas)) ? json_encode(array('rst' => true, 'msg' => 'Telefono grabado correctamente')) : json_encode(array('rst' => false, 'Error al grabar telefono'));
                echo json_encode( $daoTelefono->insert($dtoTelefono, $dtoCliente, $cuentas) );
                break;
            case 'update_telefono':

                $idorigen = (trim($_POST['Origen']) == 0) ? NULL : trim($_POST['Origen']);
                $idtipo_referencia = (trim($_POST['TipoReferencia']) == 0) ? NULL : trim($_POST['TipoReferencia']);
                $idtipo_telefono = (trim($_POST['TipoTelefono']) == 0) ? NULL : trim($_POST['TipoTelefono']);

                $dtoTelefono = new dto_telefono_ER2;
                $dtoTelefono->setId($_POST['Id']);
                //$dtoTelefono->setIdCartera($_POST['Cartera']);
                $dtoTelefono->setIdOrigen($idorigen);
                $dtoTelefono->setIdTipoReferencia($idtipo_referencia);
                $dtoTelefono->setIdTipoTelefono($idtipo_telefono);
                $dtoTelefono->setNumero($_POST['Numero']);
                $dtoTelefono->setAnexo($_POST['Anexo']);
                $dtoTelefono->setObservacion($_POST['Observacion']);
                $dtoTelefono->setUsuarioModificacion($_POST['UsuarioModificacion']);
                $line = $_POST['LineaTelefono'];
                if ($line == 0) {
                    $line = null;
                }
                $dtoTelefono->setIdLineaTelefono($line);


                echo ($daoTelefono->update($dtoTelefono)) ? json_encode(array('rst' => true, 'msg' => 'Telefono actualizado correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar telefono'));

                break;
            case 'GuardarNota':
                $dtoNota = new dto_nota;
                $dtoNota->setIdClienteCartera($_POST['ClienteCartera']);
                $dtoNota->setFecha($_POST['FechaNota']);
                $dtoNota->setDescripcion($_POST['Nota']);
                $dtoNota->setUsuarioCreacion($_POST['UsuarioCreacion']);
                echo ($daoNota->insert($dtoNota)) ? json_encode(array('rst' => true, 'msg' => 'Nota grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar nota'));
                break;
            case 'GuardarLlamada':
                //var_dump($_POST);
                $cuentas = json_decode(str_replace("\\", "", $_POST['Cuentas']), true);
                $enviar_campo = $_POST['EnviarCampo'];

                $tmo_inicio = ( trim($_POST['TMO_inicio']) == '' ) ? NULL : trim($_POST['TMO_inicio']);
                $tmo_fin = ( trim($_POST['TMO_fin']) == '' ) ? date("Y-m-d H:i:s") : trim($_POST['TMO_fin']);

                $contacto = ( trim($_POST['Contacto']) == '0' ) ? NULL : trim($_POST['Contacto']);
                $nombre_contacto = ( trim($_POST['NombreContacto']) == '' ) ? NULL : trim($_POST['NombreContacto']);
                $motivo_no_pago = ( trim($_POST['motivo_no_pago']) == '0' ) ? NULL : trim($_POST['motivo_no_pago']);
                //$sustento_pago = ( trim(@$_POST['sustento_pago']) == '0' ) ? NULL : trim(@$_POST['sustento_pago']);
                $sustento_pago = trim(@$_POST['sustento_pago']);
                //$alerta_gestion = ( trim(@$_POST['alerta_gestion']) == '0' ) ? NULL : trim(@$_POST['alerta_gestion']);
                $alerta_gestion = trim(@$_POST['alerta_gestion']);
                $parentesco = ( trim($_POST['parentesco']) == '0' ) ? NULL : trim($_POST['parentesco']);

                //$idsituacion_laboral = ( trim($_POST['idsituacion_laboral']) == '0' ) ? NULL : trim($_POST['idsituacion_laboral']);
                $idsituacion_laboral = trim($_POST['idsituacion_laboral']);

                //$iddisposicion_refinanciar = ( trim($_POST['iddisposicion_refinanciar']) == '0' ) ? NULL : trim($_POST['iddisposicion_refinanciar']);
                $iddisposicion_refinanciar = trim($_POST['iddisposicion_refinanciar']);

                $idestado_cliente = ( trim($_POST['idestado_cliente']) == '0' ) ? NULL : trim($_POST['idestado_cliente']);
                
                $direcciones = ( @$_POST['DireccionEst'] ) ? $_POST['DireccionEst'] : array() ;
                $telefonos_check = ( @$_POST['TelefonosEst'] ) ? $_POST['TelefonosEst'] : array() ;
                
                $dtoTransaccion = new dto_transaccion;
                $dtoTransaccion->setIdTipoGestion(2);
                //$dtoTransaccion->setIdFinal($_POST['Final']);
                $dtoTransaccion->setIdEstado($_POST['Estado']);
                $dtoTransaccion->setIdClienteCartera($_POST['ClienteCartera']);
                //$dtoTransaccion->setFecha($_POST['FechaLlamada']);
                $dtoTransaccion->setObservacion($_POST['Observacion']);
                $dtoTransaccion->setUsuarioCreacion($_POST['UsuarioCreacion']);
                //$dtoTransaccion->setIdPesoTransaccion($_POST['PesoLlamada']);
                $dtoTransaccion->setIdUsuarioServicio($_POST['UsuarioServicio']);
                /*                 * ****** */
                $dtoTransaccion->setIdServicio($_POST['Servicio']);
                /*                 * ****** */

                $dtoLlamada = new dto_llamada;
                $dtoLlamada->setIdTelefono($_POST['Telefono']);
                $dtoLlamada->setUsuarioCreacion($_POST['UsuarioCreacion']);
                /*                 * ********* */
                $dtoLlamada->setTmoInicio($tmo_inicio);
                $dtoLlamada->setTmoFin($tmo_fin);
                $dtoLlamada->setEnviarCampo($enviar_campo);
                $dtoLlamada->setIdContacto($contacto);
                $dtoLlamada->setNombreContacto($nombre_contacto);
                $dtoLlamada->setIdMotivoNoPago($motivo_no_pago);
                $dtoLlamada->setIdParentesco($parentesco);
                $dtoLlamada->setIdCargaFinal($_POST['CargaFinal']);
                $dtoLlamada->setCallerId($_POST['CallerId']);
                /*                 * ********* */

                $dtoCP = new dto_compromiso_pago;
                //$dtoCP->setFechaCp($_POST['FechaCP']);
                //$dtoCP->setMontoCp($_POST['MontoCP']);
                $dtoCP->setObservacion($_POST['Observacion']);
                $dtoCP->setUsuarioCreacion($_POST['UsuarioCreacion']);

                $call_id=($_POST['call_id']);

                echo ($daoTransaccion->insertDataLlamadaUpdateClienteCarteraLlamada($dtoTransaccion, $dtoLlamada, $cuentas, $_POST['PesoEstado'], $_POST['NumeroTelefono'], $direcciones, $telefonos_check, $call_id,$_POST['CodigoCliente'],$_POST['recibio_eecc'],$sustento_pago,$alerta_gestion,$idsituacion_laboral,$iddisposicion_refinanciar,$idestado_cliente )) ? json_encode(array('rst' => true, 'msg' => 'Llamada grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar llamada'));

                //for( $i=0;$i<count($cuentas);$i++ ) {
//						
//						if($_POST['FechaCP']=='' || $_POST['MontoCP']=='' ){
//							echo ($daoTransaccion->insertDataLlamadaUpdateClienteCarteraLlamada($dtoTransaccion,$dtoLlamada,$cuentas[$i]))?json_encode(array('rst'=>true,'msg'=>'Llamada grabada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar llamada'));
//						}else{
//							echo ($daoTransaccion->insertDataCPGLlamadaUpdateClienteCarteraLlamada($dtoTransaccion,$dtoLlamada,$dtoCP,$cuentas[$i]))?json_encode(array('rst'=>true,'msg'=>'Llamada grabada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar llamada'));
//						}
//						
//					}
//					if($_POST['FechaCP']=='' || $_POST['MontoCP']=='' ){
//						echo ($daoTransaccion->insertDataLlamadaUpdateClienteCarteraLlamada($dtoTransaccion,$dtoLlamada,$cuentas))?json_encode(array('rst'=>true,'msg'=>'Llamada grabada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar llamada'));
//					}else{
//						echo ($daoTransaccion->insertDataCPGLlamadaUpdateClienteCarteraLlamada($dtoTransaccion,$dtoLlamada,$dtoCP,$cuentas))?json_encode(array('rst'=>true,'msg'=>'Llamada grabada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar llamada'));
//					}

                break;
            case 'UpdateLlamada':
                $dtoTransaccion = new dto_transaccion;
                $dtoTransaccion->setId($_POST['IdTransaccion']);
                $dtoTransaccion->setIdTipoGestion(2);
                $dtoTransaccion->setIdFinal($_POST['Final']);
                $dtoTransaccion->setFecha($_POST['FechaLlamada']);
                $dtoTransaccion->setObservacion($_POST['Observacion']);
                $dtoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
                $dtoTransaccion->setIdPesoTransaccion($_POST['PesoLlamada']);

                $dtoLlamada = new dto_llamada;
                $dtoLlamada->setId($_POST['IdLlamada']);
                $dtoLlamada->setUsuarioModificacion($_POST['UsuarioModificacion']);

                $dtoCP = new dto_compromiso_pago;
                $dtoCP->setId($_POST['IdCpg']);
                $dtoCP->setFechaCp($_POST['FechaCP']);
                $dtoCP->setMontoCp($_POST['MontoCP']);
                $dtoCP->setObservacion($_POST['Observacion']);
                $dtoCP->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($daoTransaccion->updateLlamadaCp($dtoTransaccion, $dtoLlamada, $dtoCP)) ? json_encode(array('rst' => true, 'msg' => 'Llamada actualizada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar llamada'));

                break;
            case 'UpdateNumeroTelefono':
                $dtoTelefono = new dto_telefono_ER2;
                $dtoTelefono->setId($_POST['Id']);
                $dtoTelefono->setNumero($_POST['Numero']);
                echo ($daoTelefono->UpdateNumero($dtoTelefono)) ? json_encode(array('rst' => true, 'msg' => 'Numero actualizado correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar numero'));
                break;
            case 'ImportarTelefonosGestion':
                $dtoTelefono = new dto_telefono_ER2;
                $dtoTelefono->setUsuarioCreacion($_POST['UsuarioCreacion']);
                $dtoTelefono->setIdCliente($_POST['Cliente']);
                $dtoTelefono->setIdCartera($_POST['Cartera']);
                echo ($daoTelefono->importTelefonos($_POST['IdsTelefono'], $dtoTelefono)) ? json_encode(array('rst' => true, 'msg' => 'Telefonos importados correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar numero'));
                break;
            case 'GuardarConsulta':
                $dtoConsulta = new dto_consulta;
                $dtoConsulta->setIdClienteCartera($_POST['ClienteCartera']);
                $dtoConsulta->setSupervisor($_POST['Supervisor']);
                $dtoConsulta->setAsunto($_POST['Asunto']);
                $dtoConsulta->setConsulta($_POST['Consulta']);
                $dtoConsulta->setUsuarioCreacion($_POST['UsuarioCreacion']);

                echo ($daoConsulta->insertConsulta($dtoConsulta)) ? json_encode(array('rst' => true, 'msg' => 'Consulta guardada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error grabar consulta'));
                break;
            case 'GuardarDetalleConsulta':
                $dtoDetalleConsulta = new dto_detalle_consulta;
                $dtoDetalleConsulta->setIdConsulta($_POST['IdConsulta']);
                $dtoDetalleConsulta->setConsulta($_POST['Consulta']);
                $dtoDetalleConsulta->setUsuarioCreacion($_POST['UsuarioCreacion']);

                echo ($daoDetalleConsulta->insertConsulta($dtoDetalleConsulta)) ? json_encode(array('rst' => true, 'msg' => 'Consulta guardada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error grabar consulta'));
                break;
            case 'LeerAyudaGestion':
                $dtoAyudaGestion = new dto_ayuda_gestion;
                $dtoAyudaGestion->setId($_POST['Id']);

                if ($_POST['IsText'] == 1) {
                    echo json_encode($daoAyudaGestion->ReadText($dtoAyudaGestion));
                } else {
                    echo json_encode($daoAyudaGestion->ReadFile($dtoAyudaGestion));
                }
                break;
            case 'Listar_Telefono_Aval':
                    $daoJqgrid->Listar_Telefono_Aval($_POST['codigo_cliente']);
                break;
            case 'Listar_Direccion_Aval':
                    $daoJqgrid->Listar_Direccion_Aval($_POST['codigo_cliente']);
                break;
            //MANTTELF
            case 'Listtipotelf':
                $data=$daoJqgrid->Listtipotelf();  
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['idtipo_telefono'], "cell" => array($data[$i]['idtipo_telefono'],utf8_encode($data[$i]['nombre']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'idtipo_telefono Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                }
            break;
            case 'Listreferenciatelf':
                $data=$daoJqgrid->Listreferenciatelf();  
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['idtipo_referencia'], "cell" => array($data[$i]['idtipo_referencia'],utf8_encode($data[$i]['nombre']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'idtipo_referencia Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                }
            break;
            case 'Listlineatelf':
                $data=$daoJqgrid->Listlineatelf();  
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['idlinea_telefono'], "cell" => array($data[$i]['idlinea_telefono'],utf8_encode($data[$i]['nombre']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'idlinea_telefono Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                }
            break;
            case 'Listorigentelf':
                $data=$daoJqgrid->Listorigentelf();  
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['idorigen'], "cell" => array($data[$i]['idorigen'],utf8_encode($data[$i]['nombre']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'idorigen Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                }
            break;
            //MANTTELF
            // CAMBIO 20-06-2016
            case 'List_Departamento':
                    
                $data=$daoJqgrid->List_Departamento();
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['departamento'], "cell" => array($data[$i]['departamento'],utf8_encode($data[$i]['departamento']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'Departamento Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Departamento en el combobox'));
                }
                break;
            case 'List_Distrito':
                $prov=isset($_POST['prov'])?$_POST['prov']:'';
                // $xmod=isset($_POST['xmod'])?$_POST['xmod']:'';
                $data=$daoJqgrid->List_Distrito($prov);
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['distrito'], "cell" => array($data[$i]['distrito'],utf8_encode($data[$i]['distrito']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'Distrito Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Distrito en el combobox'));
                }
                break;
            case 'List_Provincia':
                $dpto=isset($_POST['dpto'])?$_POST['dpto']:'';
                // $xmod=isset($_POST['xmod'])?$_POST['xmod']:'';
                // $data=$daoJqgrid->List_Provincia($dpto,$xmod);
                $data=$daoJqgrid->List_Provincia($dpto);
                $dataRow = array();
                for ($i=0; $i<count($data); $i++){
                    array_push($dataRow, array("id"=>$data[$i]['provincia'], "cell" => array($data[$i]['provincia'],utf8_encode($data[$i]['provincia']))));
                }
                $response["fila"] = $dataRow;
                if(count($response["fila"])!=""){
                    echo json_encode(array('rst' => true, 'msg' => 'Provincia Listados correctamente en el Combobox','datos'=>$response));
                }else{
                    echo json_encode(array('rst' => false, 'msg' => 'Error al listar Provincia en el combobox'));
                }
                break;
            case 'insertar_nueva_direccion_andina':
                
                break;
            // CAMBIO 20-06-2016
            case 'resumen_deuda':
                $codigo_cliente=$_POST['codigo_cliente'];
                $idcartera=$_POST['idcartera'];
                $empresa=$_POST['empresa'];
                $td=$_POST['td'];
                $doc=$_POST['doc'];
                $contado=$_POST['contado'];

                $daoCuenta->resumen_deuda($codigo_cliente,$idcartera,$empresa,$td,$doc,$contado);
                break;
            case 'cbo_listar_origen':
                    // $daoJqgrid->cbo_listar_origen();
                    $data=$daoJqgrid->cbo_listar_origen();  
                    $dataRow = array();
                    for ($i=0; $i<count($data); $i++){
                        array_push($dataRow, array("id"=>$data[$i]['idorigen'], "cell" => array($data[$i]['idorigen'],utf8_encode($data[$i]['nombre']))));
                    }
                    $response["fila"] = $dataRow;
                    if(count($response["fila"])!=""){
                        echo json_encode(array('rst' => true, 'msg' => 'Listados correctamente en el Combobox','datos'=>$response));
                    }else{
                        echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                    }
                break;
            case 'cbo_tipo_telefono':
                    // $daoJqgrid->cbo_listar_origen();
                    $data=$daoJqgrid->cbo_tipo_telefono();  
                    $dataRow = array();
                    for ($i=0; $i<count($data); $i++){
                        array_push($dataRow, array("id"=>$data[$i]['idtipo_telefono'], "cell" => array($data[$i]['idtipo_telefono'],utf8_encode($data[$i]['nombre']))));
                    }
                    $response["fila"] = $dataRow;
                    if(count($response["fila"])!=""){
                        echo json_encode(array('rst' => true, 'msg' => 'Listados correctamente en el Combobox','datos'=>$response));
                    }else{
                        echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                    }
                break;
            case 'cbo_linea_telefono':
                    // $daoJqgrid->cbo_listar_origen();
                    $data=$daoJqgrid->cbo_linea_telefono();  
                    $dataRow = array();
                    for ($i=0; $i<count($data); $i++){
                        array_push($dataRow, array("id"=>$data[$i]['idlinea_telefono'], "cell" => array($data[$i]['idlinea_telefono'],utf8_encode($data[$i]['nombre']))));
                    }
                    $response["fila"] = $dataRow;
                    if(count($response["fila"])!=""){
                        echo json_encode(array('rst' => true, 'msg' => 'Listados correctamente en el Combobox','datos'=>$response));
                    }else{
                        echo json_encode(array('rst' => false, 'msg' => 'Error al listar Clientes Responsables en el combobox'));
                    }
                break;
            case 'insertar_contacto_tefl':
                $daoJqgrid->insertar_contacto_tefl($_POST['idpersona'],$_POST['nro_telf'],$_POST['ori_telf'],$_POST['tip_telf'],$_POST['lin_telf']);
                break;
            case 'modificar_contacto_tefl':
                $daoJqgrid->modificar_contacto_tefl($_POST['idtelefono_pers'],$_POST['nro_telf'],$_POST['ori_telf'],$_POST['tip_telf'],$_POST['lin_telf']);
                break;
            case 'borrar_contacto_tefl':
                $daoJqgrid->borrar_contacto_tefl($_POST['idtelefono_pers']);
                break;
            case 'insertar_contacto_mail':
                $daoJqgrid->insertar_contacto_mail($_POST['idpersona'],$_POST['email']);
                break;
            case 'modificar_contacto_mail':
                $daoJqgrid->modificar_contacto_mail($_POST['idemail_pers'],$_POST['email']);
                break;
            case 'borrar_contacto_mail':
                $daoJqgrid->borrar_contacto_mail($_POST['idemail_pers']);
                break;
            default:
                echo json_encode(array('rst' => true, 'msg' => 'Accion no encontrada'));
                ;
        endswitch;
    }

    public function doGet() {
        $daoCampania = DAOFactory::getDAOCampania('maria');
        $daoUsuarioServicio = DAOFactory::getDAOUsuarioServicio('maria');
        $daoClienteCartera = DAOFactory::getDAOClienteCartera('maria');
        $daoDatosAdicionalesCliente = DAOFactory::getDAODatosAdicionalesCliente('maria');
        $daoDatosAdicionalesCuenta = DAOFactory::getDAODatosAdicionalesCuenta('maria');
        $daoDatosAdicionalesDetalleCuenta = DAOFactory::getDAODatosAdicionalesDetalleCuenta('maria');
        $daoDetalleCuenta = DAOFactory::getDAODetalleCuenta('maria');
        $daoTipoGestion = DAOFactory::getDAOTipoGestion('maria');
        $daoCargaFinal = DAOFactory::getDAOCargaFinal('maria');
        $daoClaseFinal = DAOFactory::getDAOClaseFinal('maria');
        $daoTipoFinal = DAOFactory::getDAOTipoFinal('maria');
        $daoFinalServicio = DAOFactory::getDAOFinalServicio('maria');
        $daoAlerta = DAOFactory::getDAOAlerta('maria');
        $daoOrigen = DAOFactory::getDAOOrigen('maria');
        $daoTipoTelefono = DAOFactory::getDAOTipoTelefono('maria');
        $daoTipoReferencia = DAOFactory::getDAOTipoReferencia('maria');
        $daoJqgrid = DAOFactory::getDAOJqgrid('maria');
        $daoCliente = DAOFactory::getDAOCliente('maria');
        $daoNivel = DAOFactory::getDAONivel('maria');
        $daoFiltros = DAOFactory::getDAOFiltros('maria');
        $daoNota = DAOFactory::getDAONota('maria');
        $daoEstadoTransaccion = DAOFactory::getDAOEstadoTransaccion('maria');
        $daoPesoTransaccion = DAOFactory::getDAOPesoTransaccion('maria');
        $daoLineaTelefono = DAOFactory::getDAOLineaTelefono('maria');
        $daoEvento = DAOFactory::getDAOEvento('maria');
        $daoTarea = DAOFactory::getDAOTarea('maria');
        $daoTelefono = DAOFactory::getDAOTelefono('maria');
        $daoDireccion = DAOFactory::getDAODireccion('maria');
        $daoTransaccion = DAOFactory::getDAOTransaccion('maria');
        $daoAyudaGestionUsuario = DAOFactory::getDAOAyudaGestionUsuario('maria');
        $daoAyudaGestion = DAOFactory::getDAOAyudaGestion('maria');
        $daoTipoEstado = DAOFactory::getDAOTipoEstado('maria');
        $daoEstado = DAOFactory::getDAOEstado('maria');
        $daoCuenta = DAOFactory::getDAOCuenta('maria');
        $daoProcedure = DAOFactory::getDAOProcedure('maria');
        $daoNotificador = DAOFactory::getDAONotificador('maria');
        $daoCartera = DAOFactory::getDAOCartera('maria');
        $daoCarteraPago = DAOFactory::getDAOCarteraPago('maria');
        $daoCorreo = DAOFactory::getDAOCorreo('maria');
        $daoHorarioAtencion = DAOFactory::getDAOHorarioAtencion('maria');
        $daoMotivoNoPago = DAOFactory::getDAOMotivoNoPago('maria');
        $daoContacto = DAOFactory::getDAOContacto('maria');
        $daoPago = DAOFactory::getDAOPago('maria');
        $objFacturaDigitalDao = DAOFactory::getFacturaDigitalDAO('maria');
        $daoParentesco = DAOFactory::getParentescoDAO('maria');
        $daoGestionComercial = DAOFactory::getDAOGestionComercial('maria'); //Piro 30-12-2014
        
        switch ($_GET['action']):
                        case 'ListarParentesco':
                                $dtoServicio = new dto_servicio ;
                                $dtoServicio->setId($_GET['idservicio']);

                                echo json_encode($daoParentesco->queryByService($dtoServicio));
                        break;
                        case 'ListarEstadoLlamadaG':
                                
                                $dtoServicio = new dto_servicio ;
                                $dtoServicio->setId($_GET['idservicio']);

                                echo json_encode($daoFinalServicio->queryStateLlamada2ByServicio($dtoServicio));
                                
                        break;
			case 'ListarCarteraEvento':
				
				$dtoServicio = new dto_servicio ;
				$dtoServicio->setId($_GET['idservicio']);
				
				echo json_encode($daoCartera->queryUniqueEvent($dtoServicio));
				
			break;
			case 'ListarCarteraSegmento':
				
				$dtoServicio = new dto_servicio ;
				$dtoServicio->setId($_GET['idservicio']);
				
				echo json_encode($daoCartera->queryUniqueSegment($dtoServicio));
				
			break;
			case 'ListarCarteraCluster':
				
				$dtoServicio = new dto_servicio ;
				$dtoServicio->setId($_GET['idservicio']);
				
				echo json_encode($daoCartera->queryUniqueCluster($dtoServicio));
				
			break;
            case 'ListarGestorCampo':

                $idservicio = $_GET['idservicio'];
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($idservicio);

                echo json_encode($daoUsuarioServicio->queryGestorCampo($dtoServicio));

                break;
            case 'GetLineasFacturaDigitalXcliente':
                $rpt = $objFacturaDigitalDao->getLinasFacturaDigital($_GET['idClienteCartera']);
                echo json_encode($rpt);
                break;
            case 'ListarSupervisores':
                $dtoUsuarioServicio = new dto_usuario_servicio();
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                echo json_encode($daoUsuarioServicio->querySupervisor($dtoUsuarioServicio));
                break;
            case 'ListarEstadoPago':
                $idcartera = is_array($_GET['idcartera']) ? implode(',', $_GET['idcartera']) : $_GET['idcartera'];
                $dtoCartera = new dto_cartera ();
                $dtoCartera->setId($idcartera);

                echo json_encode($daoPago->listarEstadoPago($dtoCartera));

                break;
            case 'ListarHistoricoCuenta':

                $idcliente = $_GET['idcliente'];
                $cartera = $_GET['cartera'];

                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setIdCartera($cartera);
                $dtoClienteCartera->setIdCliente($idcliente);

                echo json_encode($daoCuenta->queryHistorialByCliente($dtoClienteCartera));

                break;
            case 'ListarDistritoCartera':
                $cartera = $_GET['idcartera'];
                
                echo json_encode($daoCartera->queryListarDistritoCartera($cartera));
            break;
            case 'listarfproceso':
                $cartera = $_GET['idcartera'];
                echo json_encode($daoCartera->queryListarFproceso($cartera));
            break;
            case 'listarfprocesomultiple':
                $cartera = $_GET['idcartera'];
                echo json_encode($daoCartera->queryListarFprocesomultiple($cartera));
            break;            
            case 'listarterritorio':
                $cartera = $_GET['idcartera'];
                $fproceso=$_GET['fproceso'];

                echo json_encode($daoCartera->queryListarTerritorio($cartera,$fproceso));
            break;
            case 'ListLlamada':
                $cartera=$_GET['cartera'];
                $fecha=$_GET['fecha'];
                echo json_encode($daoTransaccion->queryListarLlamadas($cartera,$fecha));
            break;
            case 'listarAlertaTelefono':
                $cartera=$_GET['idcartera'];
                $codigo_cliente=$_GET['codigo_cliente'];
                echo json_encode($daoJqgrid->queryListarNumeroTelefonico($cartera,$codigo_cliente));
            break;
            case 'EnviarCargo':
                $idcuenta=$_GET['idcuenta'];
                $idcliente_cartera=@$_GET['idcliente_cartera'];
                $usuario_creacion=@$_GET['usuario_creacion'];
                echo json_encode($daoTransaccion->queryEnviarCargo($idcuenta,$idcliente_cartera,$usuario_creacion));
            break;            
            case 'cantidadDiasMora':
                $idcartera=is_array($_GET['idcartera']) ? implode(',', $_GET['idcartera']) : $_GET['idcartera'];
                $idusuario_servicio=$_GET['idusuario_servicio'];
                $modo=$_GET['modo'];
                if ($modo == 'cartera') {
                        $filtroUsuario = " AND clicar.idusuario_servicio = " . $idusuario_servicio . " ";
                } else {
                        $filtroUsuario = " AND clicar.idusuario_servicio_especial = " . $idusuario_servicio . " ";
                }                    
                echo json_encode($daoCartera->queryCantidadDiasMora($idcartera,$idusuario_servicio,$filtroUsuario));
            break;     
            case 'cantidadTerritorio':/*jmore200813*/
                $idcartera=is_array($_GET['idcartera']) ? implode(',', $_GET['idcartera']) : $_GET['idcartera'];
                $idusuario_servicio=$_GET['idusuario_servicio'];
                $modo=$_GET['modo'];
                if ($modo == 'cartera') {
                        $filtroUsuario = " AND clicar.idusuario_servicio = " . $idusuario_servicio . " ";
                } else {
                        $filtroUsuario = " AND clicar.idusuario_servicio_especial = " . $idusuario_servicio . " ";
                }                    
                echo json_encode($daoCartera->queryCantidadTerritorio($idcartera,$idusuario_servicio,$filtroUsuario));
            break;                                   
            case 'CantidadClientesAsignadosFiltros':

                function MapArray($n) {
                    return "'" . $n['idcliente_cartera'] . "'";
                }

                ;

                $cartera = is_array($_GET['cartera']) ? implode(',', $_GET['cartera']) : $_GET['cartera'];
                $servicio = $_GET['idservicio'];
                $usuario_servicio = $_GET['usuario_servicio'];
                $monto = $_GET['monto'];
                $tramo = $_GET['tramo'];
                $departamento = $_GET['departamento'];
                $provincia = $_GET['provincia'];
                $otros = $_GET['otros'];
                $idfinal = @$_GET['idfinal'];
                $matriz_usuario = $_GET['matriz_usuario'];
                $modo = $_GET['modo'];
                $estado_pago = $_GET['estado_pago'];
                /*                 * ******** */
                $tabla = trim($_GET['tabla']);
                $referencia = $_GET['referencia'];
                $campo = $_GET['campo'];
                $dato = $_GET['dato'];
                $tipo_f_estado = $_GET['tipo_f_estado'];
                $semana_opcion = $_GET['semana_opcion'];
                /*                 * ********** */

                $filtro_con_sin_ges=$_GET['filtro_con_sin_gestion'];

                $depto = $_GET['depto'][0];
                $provin = isset($_GET['provin'][0])?$_GET['provin'][0]:'';
                $distri = isset($_GET['distri'][0])?$_GET['distri'][0]:'';

                $rango_vcto = isset($_GET['rango_vcto'])?$_GET['rango_vcto']:'';
                $tipo_cliente = isset($_GET['tipo_cliente'])?$_GET['tipo_cliente']:'';

                $filtroUsuario = "";
                $filtroModo = "";
                if ($modo == 'cartera') {
                    if ($matriz_usuario == '0') {
                        $filtroUsuario = " clicar.estado=1 AND clicar.idusuario_servicio = " . $usuario_servicio . " ";
                    } else {
                        $filtroUsuario = " clicar.estado=1 AND clicar.idusuario_servicio = " . $matriz_usuario . " ";
                    }
                    $filtroModo = " clicar.filtro ";
                } else {
                    if ($matriz_usuario == '0') {
                        $filtroUsuario = " clicar.estado=1 AND clicar.idusuario_servicio_especial = " . $usuario_servicio . " ";
                    } else {
                        $filtroUsuario = " clicar.estado=1 AND clicar.idusuario_servicio_especial = " . $matriz_usuario . " ";
                    }
                    $filtroModo = " clicar.filtro_especial ";
                }

                //$sqlUpdateFiltro = " UPDATE ca_cliente_cartera clicar SET $filtroModo = 0
                //		WHERE idcartera = ".$cartera." AND $filtroUsuario  ";

                $sqlUpdateFiltro = " DELETE FROM ca_filtro WHERE idusuario_servicio = " . $usuario_servicio . "  AND idcartera IN (" . $cartera . ") AND session = '" . session_id() . "' ";

                $daoProcedure->executeQuery($sqlUpdateFiltro);

                $sql = "";

                $dataCodigoClienteGeneral = array();

                $sqlClientesUsuario = " SELECT idcliente_cartera FROM ca_cliente_cartera clicar WHERE idcartera IN (" . $cartera . ") AND $filtroUsuario ";
                // echo $sqlClientesUsuario;
                $dataCodigoClienteMapGeneral = $daoProcedure->executeQueryReturn($sqlClientesUsuario);
                $dataCodigoClienteGeneral = array_map("MapArray", $dataCodigoClienteMapGeneral);

                $dataGeneralCodigoCliente = array();

                $dataGeneralCodigoCliente = $dataCodigoClienteGeneral;

                $dataCodigoClienteEstado = array();

                if ($idfinal != '') {
                    
                    $sqlEstado = "";

                    if( $tipo_f_estado == 'telefono' ) {
                        
                        $sqlEstado = " SELECT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
                                ON tel.idcliente_cartera = clicar.idcliente_cartera 
                                WHERE tel.idcartera IN ( ".$cartera." ) AND clicar.idcartera IN ( ".$cartera." ) AND tel.idfinal IN ( ".$idfinal." ) AND tel.estado = 1 AND $filtroUsuario ";
                                
                    }else{

                       /* $sqlEstado = " SELECT clicar.codigo_cliente 
        			FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
        			ON lla.idllamada = clicar.id_ultima_llamada 
        			WHERE clicar.idcartera IN (" . $cartera . ") AND lla.idfinal IN ( " . $idfinal . " ) AND $filtroUsuario ";*/
                    // $sqlEstado="    SELECT DISTINCT idcliente_cartera FROM (
                    //                     SELECT * FROM
                    //                     (
                    //                         SELECT
                    //                         lla.idcuenta,
                    //                         lla.fecha,
                    //                         lla.idfinal, 
                    //                         finser.peso,
                    //                         clicar.codigo_cliente,
                    //                         clicar.idcliente_cartera
                    //                         FROM
                    //                         ca_cliente_cartera clicar
                    //                         INNER JOIN ca_llamada lla
                    //                         INNER JOIN ca_final fin
                    //                         INNER JOIN ca_final_servicio finser
                    //                         INNER JOIN ca_cuenta cu
                    //                         ON finser.idfinal = fin.idfinal AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera= clicar.idcliente_cartera AND cu.idcuenta=lla.idcuenta
                    //                         WHERE
                    //                         cu.estado=1 AND
                    //                         clicar.idcartera IN ($cartera) AND 
                    //                         lla.tipo<>'S' AND 
                    //                         $filtroUsuario
                    //                         ORDER BY lla.idcuenta,lla.fecha DESC
                    //                     ) t1 GROUP BY t1.idcuenta ORDER BY t1.fecha DESC
                    //                 )tmp WHERE tmp.idfinal IN (".$idfinal.")
                    //             ";

                    $sqlEstado="    SELECT
                                    DISTINCT clicar.idcliente_cartera
                                    FROM ca_cliente_cartera clicar
                                    INNER JOIN  ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
                                    WHERE                                    
                                    $filtroUsuario AND
                                    lla.idfinal IN (".$idfinal.")

                                ";
                    }

                    // echo $sqlEstado;
                    // exit();

                    $dataIdCliente = $daoProcedure->executeQueryReturn($sqlEstado);
                    $dataCodigoClienteEstado = array_map("MapArray", $dataIdCliente);

                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataCodigoClienteEstado);
                }

                $dataCodigoClienteEstadoPago = array();
                if ($estado_pago != '0') {
                    $estado_pago_sql = " SELECT codigo_cliente FROM ca_cuenta WHERE idcartera IN (" . $cartera . ") AND TRIM(estado_pago) = '" . $estado_pago . "' ";
                    $dataClienteEstadoPago = $daoProcedure->executeQueryReturn($estado_pago_sql);
                    $dataCodigoClienteEstadoPago = array_map("MapArray", $dataClienteEstadoPago);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataCodigoClienteEstadoPago);
                }

                $dataCodigoClienteTramo = array();
                if ($tramo != 0) {
                    $tramosql = " SELECT codigo_cliente FROM ca_detalle_cuenta 
						WHERE idcartera IN (" . $cartera . ") AND TRIM(tramo) = '" . trim($tramo) . "' ";
                    $dataClienteTramo = $daoProcedure->executeQueryReturn($tramosql);
                    $dataCodigoClienteTramo = array_map("MapArray", $dataClienteTramo);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataCodigoClienteTramo);
                }

                $dataCodigoClienteDepartamento = array();
                if ($departamento != '0') {
                    if($provincia!='0'){
                    $departamentosql = " SELECT codigo_cliente FROM ca_direccion WHERE idcartera IN (" . $cartera . ") AND TRIM(departamento) = '" . trim($departamento) . "' AND TRIM(provincia) = '" . trim($provincia) . "' ";
                    }else{
                    $departamentosql = " SELECT codigo_cliente FROM ca_direccion WHERE idcartera IN (" . $cartera . ") AND TRIM(departamento) = '" . trim($departamento) . "' ";
                    }
                    $dataClienteDepartamento = $daoProcedure->executeQueryReturn($departamentosql);
                    $dataCodigoClienteDepartamento = array_map("MapArray", $dataClienteDepartamento);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataCodigoClienteDepartamento);
                }

                $dataSemanaOpcion = array();
                if ( $semana_opcion != '0')  {
                    $sql_semanaopcion = "SELECT DISTINCT clicar.idcliente_cartera FROM ca_cliente_cartera clicar WHERE clicar.semana='$semana_opcion' AND $filtroUsuario;";
                    // echo $sql_semanaopcion;
                    $data_semana_opcion = $daoProcedure->executeQueryReturn($sql_semanaopcion);
                    $dataSemanaOpcion = array_map("MapArray", $data_semana_opcion);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataSemanaOpcion);
                }

                $datafiltro_con_sin_ges=array();
                if($filtro_con_sin_ges=="SINGESTION"){
                    $filtro_con_sin_ges_sql="   SELECT
                                                DISTINCT clicar.idcliente_cartera
                                                FROM ca_cliente_cartera clicar
                                                WHERE 
                                                clicar.idcartera IN ($cartera) AND 
                                                -- clicar.id_ultima_llamada=0 AND
                                                -- (SELECT IFNULL(CONCAT(YEAR(fecha), MONTH(fecha)),'0') FROM ca_llamada WHERE idllamada=clicar.id_ultima_llamada)<CONCAT(YEAR(NOW()), MONTH(NOW())) AND
                                                IFNULL((SELECT SUBSTR(DATE(fecha)+0,1,6) FROM ca_llamada WHERE idllamada=clicar.id_ultima_llamada),0)<SUBSTR(DATE(NOW())+0,1,6)  AND
                                                $filtroUsuario
                                            ";

                    // echo $filtro_con_sin_ges_sql;
                    // exit();

                    $filtro_con_sin_gest=$daoProcedure->executeQueryReturn($filtro_con_sin_ges_sql);
                    $datafiltro_con_sin_ges=array_map("MapArray",$filtro_con_sin_gest);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $datafiltro_con_sin_ges);
                }else if($filtro_con_sin_ges=="CONGESTION"){
                    $filtro_con_sin_ges_sql="   SELECT
                                                DISTINCT clicar.idcliente_cartera
                                                FROM ca_cliente_cartera clicar
                                                WHERE
                                                clicar.idcartera IN ($cartera) AND 
                                                -- clicar.id_ultima_llamada<>0 AND
                                                (SELECT SUBSTR(DATE(fecha)+0,1,6) FROM ca_llamada WHERE idllamada=clicar.id_ultima_llamada)=SUBSTR(DATE(NOW())+0,1,6)  AND
                                                $filtroUsuario
                                            ";

                    // echo $filtro_con_sin_ges_sql;
                    // exit();
                    $filtro_con_sin_gest=$daoProcedure->executeQueryReturn($filtro_con_sin_ges_sql);
                    $datafiltro_con_sin_ges=array_map("MapArray",$filtro_con_sin_gest);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $datafiltro_con_sin_ges);
                }

                $datadeptoOpcion = array();
                if ($depto != ''){

                    $ubigeo="";
                    if($provin!=''){
                        $ubigeo.=" provincia='$provin' AND";
                    }
                    if($distri!=''){
                        $ubigeo.=" distrito='$distri' AND";
                    }

                    $sql_deptoopcion= "   SELECT 
                                        DISTINCT cu.idcliente_cartera
                                        FROM 
                                        ca_direccion dir
                                        INNER JOIN ca_cuenta cu ON dir.codigo_cliente=cu.codigo_cliente
                                        INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=cu.idcliente_cartera
                                        WHERE 
                                        cu.estado=1 AND 
                                        dir.departamento='$depto' AND
                                        $ubigeo
                                        $filtroUsuario;";

                    $data_depto_opcion = $daoProcedure->executeQueryReturn($sql_deptoopcion);
                    $datadeptoOpcion = array_map("MapArray", $data_depto_opcion);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $datadeptoOpcion);
                }

                $data_ordermonto = array();
                if ( $monto != '0'){
                    if($monto=="DESC"){
                        $sql_ordermonto = " SELECT  
                                            clicar.idcliente_cartera
                                            FROM ca_cliente_cartera clicar
                                            INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                                            INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta 
                                            WHERE  
                                            clicar.estado=1 AND 
                                            $filtroUsuario
                                            GROUP BY clicar.idcliente_cartera
                                            ORDER BY SUM(detcu.dato20) DESC";
                    }else if($monto=="ASC"){
                        $sql_ordermonto = " SELECT  
                                            clicar.idcliente_cartera
                                            FROM ca_cliente_cartera clicar
                                            INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                                            INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta 
                                            WHERE  
                                            clicar.estado=1 AND 
                                            $filtroUsuario
                                            GROUP BY clicar.idcliente_cartera
                                            ORDER BY SUM(detcu.dato20) ASC";
                    }                    
                    
                    $data_order_monto_opcion = $daoProcedure->executeQueryReturn($sql_ordermonto);
                    $data_ordermonto = array_map("MapArray", $data_order_monto_opcion);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $data_ordermonto);
                }

                $data_rango_vcto = array();
                if ($rango_vcto!=''){
                    $sql_rango_vcto = " SELECT 
                                        clicar.idcliente_cartera 
                                        FROM 
                                        ( 
                                            SELECT 
                                            X.codigo_cliente 
                                            FROM 
                                            (
                                                SELECT 
                                                A.codigo_cliente,
                                                A.dias_mora,
                                                A.rango_vcto
                                                FROM 
                                                (
                                                    SELECT 
                                                    codigo_cliente,
                                                    numero_cuenta,
                                                    CAST(dias_mora as SIGNED) as dias_mora,
                                                    dato15 AS 'rango_vcto'
                                                    FROM 
                                                    ca_detalle_cuenta detcu 
                                                    WHERE 
                                                    detcu.idcartera IN ($cartera) AND
                                                    detcu.estado=1
                                                    ORDER BY detcu.codigo_cliente,CAST(dias_mora as SIGNED) DESC
                                                ) A
                                                GROUP BY A.codigo_cliente
                                            ) X
                                            WHERE X.rango_vcto='$rango_vcto'
                                        ) Z
                                        INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=Z.codigo_cliente 
                                        WHERE clicar.idcartera=$cartera AND clicar.estado=1";
                    
                    // echo $sql_rango_vcto;
                    // exit();

                    $data_pr_rango_vcto = $daoProcedure->executeQueryReturn($sql_rango_vcto);
                    $data_rango_vcto = array_map("MapArray", $data_pr_rango_vcto);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $data_rango_vcto);
                }

                $data_tipo_cliente = array();
                if ($tipo_cliente!=''){
                    $sql_tipo_cliente = "   SELECT  
                                            clicar.idcliente_cartera
                                            FROM 
                                            ca_cliente_cartera clicar
                                            WHERE  
                                            clicar.estado=1 AND 
                                            clicar.tipo_cliente='$tipo_cliente' AND
                                            $filtroUsuario";
                    
                    // echo $sql_rango_vcto;
                    // exit();

                    $data_pr_tipo_cliente = $daoProcedure->executeQueryReturn($sql_tipo_cliente);
                    $data_tipo_cliente = array_map("MapArray", $data_pr_tipo_cliente);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $data_tipo_cliente);
                }

                $dataCodigoClienteOtros = array();
                $arrayOtros=explode("_", $otros);                
                if ($otros != '0') {
                    $otrosSql = "";
                    if ($otros == 'pago') {
                        $otrosSql = " SELECT codigo_cliente
									FROM ca_detalle_cuenta WHERE idcartera IN (" . $cartera . ") AND
									codigo_operacion IN ( SELECT codigo_operacion FROM ca_pago WHERE estado = 1 AND idcartera IN (" . $cartera . ")  ) ";
                    }else if ($otros == 'tramo_1') {
                        $otrosSql = "SELECT clicar.codigo_cliente FROM 
                                    ( 
                                        SELECT X.codigo_cliente FROM 
                                        (
                                            select A.codigo_cliente,A.dias_mora FROM 
                                            (
                                                select codigo_cliente,numero_cuenta,CAST(dias_mora as SIGNED) as dias_mora
                                                from ca_detalle_cuenta detcu 
                                                where detcu.idcartera in (".$cartera.") 
                                                order by detcu.codigo_cliente,CAST(dias_mora as SIGNED) DESC
                                            )A
                                            GROUP BY A.codigo_cliente
                                        )X
                                        WHERE X.dias_mora BETWEEN 1 and 30
                                    )Z
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=Z.codigo_cliente 
                                    WHERE clicar.idcartera=".$cartera." AND clicar.estado=1";
                    }else if ($otros == 'tramo_2') {
                        $otrosSql = "SELECT clicar.codigo_cliente FROM ( 
                                     SELECT X.codigo_cliente FROM (
                                    select A.codigo_cliente,A.dias_mora FROM (
                                    select codigo_cliente,numero_cuenta,CAST(dias_mora as SIGNED) as dias_mora
                                    from ca_detalle_cuenta detcu 
                                    where detcu.idcartera in (".$cartera.") 
                                    order by detcu.codigo_cliente,CAST(dias_mora as SIGNED) DESC)A
                                    GROUP BY A.codigo_cliente)X
                                    WHERE X.dias_mora BETWEEN 31 and 60)Z
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=Z.codigo_cliente 
                                    WHERE clicar.idcartera=".$cartera." AND clicar.estado=1";
                    }else if ($otros == 'tramo_3') {
                        $otrosSql = "SELECT clicar.codigo_cliente FROM ( 
                                    SELECT X.codigo_cliente FROM (
                                    select A.codigo_cliente,A.dias_mora FROM (
                                    select codigo_cliente,numero_cuenta,CAST(dias_mora as SIGNED) as dias_mora
                                    from ca_detalle_cuenta detcu 
                                    where detcu.idcartera in (".$cartera.") 
                                    order by detcu.codigo_cliente,CAST(dias_mora as SIGNED) DESC)A
                                    GROUP BY A.codigo_cliente)X
                                    WHERE X.dias_mora BETWEEN 61 and 9999999)Z
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=Z.codigo_cliente 
                                    WHERE clicar.idcartera=".$cartera." AND clicar.estado=1";
                    } else if ($otros == 'visita') {
                        $otrosSql = " SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera IN (" . $cartera . ") AND id_ultima_visita != 0 ";
                    } else if ($otros == 'inactivos') {
                        $otrosSql = " SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera IN (" . $cartera . ") AND estado = 0 ";
                    } else if ($otros == 'sin_pago') {
                        $otrosSql = " SELECT codigo_cliente FROM ca_cuenta WHERE idcartera IN (" . $cartera . ") AND total_deuda > monto_pagado AND retirado = 0 ";
                    } else if ($otros == "sin_gestion_no_retirados") {
                        $otrosSql = " SELECT codigo_cliente FROM ca_cuenta WHERE idcartera IN (" . $cartera . ") AND retirado = 0 AND ISNULL(ul_fecha) = 1 ";
                    } else if ($otros == 'sin_gestion') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND (cu.estado_pago != 'NO LLAMAR' OR cu.estado_pago IS NULL)
                                AND clicar.id_ultima_llamada = 0 AND cu.retirado=0 AND $filtroUsuario ";
                    } else if ($otros == 'sin_gestion_total') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND (cu.estado_pago != 'NO LLAMAR' OR cu.estado_pago IS NULL)
                                AND clicar.id_ultima_llamada_total = 0 AND cu.retirado=0 AND $filtroUsuario ";
                    } else if ($otros == 'gestionados') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND (cu.estado_pago != 'NO LLAMAR' OR cu.estado_pago IS NULL)
                                AND clicar.id_ultima_llamada != 0 AND $filtroUsuario ";
                    } else if ($otros == "celulares_todo") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera  
							WHERE clicar.idcartera IN (" . $cartera . ") AND tel.idcartera IN (" . $cartera . ") AND clicar.retiro = 0 AND tel.numero LIKE '9%' AND $filtroUsuario ";
                    } else if ($otros == "celulares_sin_gestion") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera  
							WHERE clicar.idcartera IN (" . $cartera . ") AND clicar.retiro = 0 AND tel.idcartera IN (" . $cartera . ") AND tel.numero LIKE '9%' AND clicar.id_ultima_llamada = 0 AND $filtroUsuario ";
                    } else if ($otros == "celulares_con_gestion") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera  
							WHERE clicar.idcartera IN (" . $cartera . ") AND clicar.retiro = 0 AND tel.idcartera IN (" . $cartera . ") AND tel.numero LIKE '9%' AND clicar.id_ultima_llamada != 0 AND $filtroUsuario ";
                    } else if ($otros == "celulares_sin_pago") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente_cartera = cu.idcliente_cartera
							WHERE clicar.idcartera IN (" . $cartera . ") AND cu.retirado = 0 AND tel.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND tel.numero LIKE '9%' AND cu.monto_pagado<=0 AND $filtroUsuario ";
                    } else if ($otros == "celulares_amortizados") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente_cartera = cu.idcliente_cartera
							WHERE clicar.idcartera IN (" . $cartera . ") AND cu.retirado = 0 AND tel.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND tel.numero LIKE '9%' AND cu.monto_pagado < cu.total_deuda AND $filtroUsuario ";
                    } else if ($otros == "celulares_cancelados") {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_telefono tel 
							ON tel.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente_cartera = cu.idcliente_cartera
							WHERE clicar.idcartera IN (" . $cartera . ") AND cu.retirado = 0 AND tel.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND tel.numero LIKE '9%' AND cu.monto_pagado >= cu.total_deuda AND $filtroUsuario ";
                    } else if ($otros == 'factura_digital') {
                        $otrosSql = "SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_factura_digital fact 
                                ON fact.idcliente_cartera = clicar.idcliente_cartera  
                                WHERE clicar.idcartera IN (" . $cartera . ") 
                                AND clicar.estado = 1  AND fact.is_send = 1 AND $filtroUsuario  ";
                    } else if ($otros == 'corte_focalizado') {
                        $otrosSql = "SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu 
                                ON cu.idcliente_cartera = clicar.idcliente_cartera 
                                WHERE cu.corte_focalizado = 1 AND clicar.idcartera IN ( ".$cartera." ) 
                                AND $filtroUsuario ";
                    } else if ($otros == 'de_0_2_dias') {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                                ON lla.idllamada = clicar.id_ultima_llamada
                                WHERE clicar.idcartera IN ( ".$cartera." ) AND lla.idusuario_servicio<>'1' and clicar.estado=1
                                AND TIMESTAMPDIFF(DAY , DATE(lla.fecha), CURDATE() ) BETWEEN 0 AND 2 AND $filtroUsuario "; 
                    } else if ($otros == 'de_3_4_dias') {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                                ON lla.idllamada = clicar.id_ultima_llamada
                                WHERE clicar.idcartera IN ( ".$cartera." ) AND lla.idusuario_servicio<>'1' and clicar.estado=1
                                AND TIMESTAMPDIFF(DAY , DATE(lla.fecha), CURDATE() ) BETWEEN 3 AND 4 AND $filtroUsuario "; 
                    } else if ($otros == 'de_5_6_dias') {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                                ON lla.idllamada = clicar.id_ultima_llamada
                                WHERE clicar.idcartera IN ( ".$cartera." ) AND lla.idusuario_servicio<>'1' and clicar.estado=1
                                AND TIMESTAMPDIFF(DAY , DATE(lla.fecha), CURDATE() ) BETWEEN 5 AND 6 AND $filtroUsuario "; 
                    } else if ($otros == 'de_7_8_dias') {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                                ON lla.idllamada = clicar.id_ultima_llamada
                                WHERE clicar.idcartera IN ( ".$cartera." ) AND lla.idusuario_servicio<>'1' and clicar.estado=1
                                AND TIMESTAMPDIFF(DAY , DATE(lla.fecha), CURDATE() ) BETWEEN 7 AND 8 AND $filtroUsuario "; 
                    } else if ($otros == 'de_9_mas_dias') {
                        $otrosSql = " SELECT DISTINCT clicar.codigo_cliente 
                                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                                ON lla.idllamada = clicar.id_ultima_llamada
                                WHERE clicar.idcartera IN ( ".$cartera." ) AND lla.idusuario_servicio<>'1' and clicar.estado=1
                                AND TIMESTAMPDIFF(DAY , DATE(lla.fecha), CURDATE() ) >= 9 AND $filtroUsuario "; 
                    } else if ($otros == 'llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") 
                                AND cu.estado_pago = 'LLAMAR' AND $filtroUsuario ";
                    } else if ($otros == 'no_llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") 
                                AND cu.estado_pago = 'NO LLAMAR' AND $filtroUsuario ";
                    } else if ($otros == 'gestionados_llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND cu.estado_pago = 'LLAMAR'
                                AND clicar.id_ultima_llamada != 0 AND $filtroUsuario ";
                    } else if ($otros == 'gestionados_no_llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND cu.estado_pago = 'NO LLAMAR'
                                AND clicar.id_ultima_llamada != 0 AND $filtroUsuario ";
                    } else if ($otros == 'provision') {
                        $otrosSql="SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                                WHERE clicar.idcartera IN ($cartera) and $filtroUsuario";
                    } else if ($otros == 'sin_gestion_llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND cu.estado_pago = 'LLAMAR'
                                AND clicar.id_ultima_llamada = 0 AND $filtroUsuario ";
                    } else if ($otros == 'sin_gestion_no_llamar') {
                        $otrosSql = " SELECT clicar.codigo_cliente FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
                                ON cu.idcliente_cartera = clicar.idcliente_cartera
                                WHERE clicar.idcartera IN (" . $cartera . ") AND cu.idcartera IN (" . $cartera . ") AND cu.estado_pago = 'NO LLAMAR'
                                AND clicar.id_ultima_llamada = 0 AND $filtroUsuario ";
                    } else{/*sino cumple lo anterior trabajremos con el cbfiltrodiasmora*/
                        if($arrayOtros[0]=='DiasMora'){
                            $otrosSql="SELECT DISTINCT clicar.codigo_cliente FROM ca_cliente_cartera clicar
                                    INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                                    INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta 
                                    WHERE clicar.idcartera IN (".$cartera.") AND cu.retirado=0 AND detcu.dias_mora='".$arrayOtros[1]."' AND $filtroUsuario";                            
                        } else if($arrayOtros[0]=='Territorio'){
                            $otrosSql="SELECT DISTINCT clicar.codigo_cliente FROM ca_cliente_cartera clicar
                                    INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                                    WHERE clicar.idcartera IN (".$cartera.") AND cu.retirado=0 AND cu.dato9='".$arrayOtros[1]."' AND $filtroUsuario";                                                        
                        } else if($arrayOtros[0]=='filtro-llamada') {
                            $otrosSql="SELECT idcliente_cartera FROM (
                                                SELECT * FROM (
                                                        select clicar.idcliente_cartera,lla.fecha from ca_cliente_cartera clicar
                                                        inner join ca_llamada lla on lla.idcliente_cartera=clicar.idcliente_cartera
                                                        where clicar.idcartera IN (".$cartera.") and lla.idusuario_servicio<>'1' and clicar.estado=1 and lla.tipo IN ('LL','SA') AND $filtroUsuario
                                                        ORDER BY clicar.idcliente_cartera,lla.fecha DESC
                                                )A GROUP BY A.idcliente_cartera
                                        )B WHERE DATE(fecha) BETWEEN '".$arrayOtros[1]."' AND '".$arrayOtros[2]."'";
                            // echo $otrosSql;
							
						}
                    }
                    
                    $dataClienteOtros = $daoProcedure->executeQueryReturn($otrosSql);
                    $dataCodigoClienteOtros = array_map("MapArray", $dataClienteOtros);
                    $dataGeneralCodigoCliente = array_intersect($dataGeneralCodigoCliente, $dataCodigoClienteOtros);
                }

                $filtroCodigoCliente = "";
                if (count($dataGeneralCodigoCliente) > 0) {
                    $filtroCodigoCliente = " AND clicar.idcliente_Cartera IN ( " . implode(",", $dataGeneralCodigoCliente) . " ) ";
                } else {
                    $filtroCodigoCliente = " AND clicar.idcliente_Cartera = '' ";
                }

                // $sql_monto_roder="";
                // if($monto=='ASC'){
                //     $sql_monto_roder="ORDER BY (SELECT SUM(cu.cuota_mensual+cu.seguros+cu.otros) FROM ca_cuenta cu WHERE cu.estado=1 AND cu.idcartera=1 AND cu.idcliente_cartera=clicar.idcliente_cartera GROUP BY cu.idcliente_cartera ) ASC";
                // }else if($monto=='DESC'){
                //     $sql_monto_roder="ORDER BY (SELECT SUM(cu.cuota_mensual+cu.seguros+cu.otros) FROM ca_cuenta cu WHERE cu.estado=1 AND cu.idcartera=1 AND cu.idcliente_cartera=clicar.idcliente_cartera GROUP BY cu.idcliente_cartera ) DESC";
                // }

                $sql = "    INSERT INTO ca_filtro ( idcliente_cartera, idcartera, idusuario_servicio, session ) 
        					SELECT 
                            DISTINCT idcliente_cartera , 
                            idcartera  , 
                            $usuario_servicio , 
                            '" . session_id() . "'
        					FROM 
                            ca_cliente_cartera clicar
        					WHERE 
                            clicar.idcartera IN (" . $cartera . ") 
                            $filtroCodigoCliente
                            ";

                // echo $sql;
                // exit();

                if ($daoProcedure->executeQuery($sql)) {

                    //$sqlCount = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera clicar 
                    //	WHERE clicar.idcartera = ".$cartera." AND $filtroModo = 1 AND $filtroUsuario ";

                    $sqlCount = " SELECT COUNT(*) AS 'COUNT' FROM ca_filtro 
							WHERE idcartera IN (" . $cartera . ") AND session = '" . session_id() . "' AND idusuario_servicio = " . $usuario_servicio . " ";

                    $data = $daoProcedure->executeQueryReturn($sqlCount);
                    echo json_encode($data);
                } else {
                    echo json_encode(array(array('COUNT' => 0)));
                }

                break;
            case 'ListarContacto':

                $idservicio = $_GET['idservicio'];
                $dtoContacto = new dto_contacto;
                $dtoContacto->setIdServicio($idservicio);

                echo json_encode($daoContacto->queryByService($dtoContacto));

                break;
            case 'ListarMotivoNoPago':

                $idservicio = $_GET['idservicio'];
                $dtoMotivoNoPago = new dto_motivo_no_pago;
                $dtoMotivoNoPago->setIdServicio($idservicio);

                echo json_encode($daoMotivoNoPago->queryByService($dtoMotivoNoPago));

                break;
            case 'ListarSustentoPago'://jmore18112014

                $idservicio = $_GET['idservicio'];
                $dtoMotivoNoPago = new dto_motivo_no_pago;
                $dtoMotivoNoPago->setIdServicio($idservicio);

                echo json_encode($daoMotivoNoPago->ListarSustentoPago($dtoMotivoNoPago));

                break;  
            case 'ListarAlertaGestion'://jmore18112014

                $idservicio = $_GET['idservicio'];
                $dtoMotivoNoPago = new dto_motivo_no_pago;
                $dtoMotivoNoPago->setIdServicio($idservicio);

                echo json_encode($daoMotivoNoPago->ListarAlertaGestion($dtoMotivoNoPago));

                break;                  
            case 'LoadDataGlobal':

                $idcliente_cartera = $_GET['ClienteCartera'];

                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setId($idcliente_cartera);

                echo json_encode($daoCliente->queryGlobal($dtoClienteCartera));
                break;
            case 'ListarCorreosCliente':

                $idcliente = $_GET['idcliente'];
                $dtoCorreo = new dto_correo;
                $dtoCorreo->setIdCliente($idcliente);

                echo json_encode($daoCorreo->query($dtoCorreo));

                break;
            case 'ListarHorariosAtencionCliente':
                $idcliente = $_GET['idcliente'];
                $dtoHorarioAtencion = new dto_horario_atencion;
                $dtoHorarioAtencion->setIdCliente($idcliente);

                echo json_encode($daoHorarioAtencion->query($dtoHorarioAtencion));
                break;
            case 'data_distribucion_usuario':
                $usuario_servicio = $_GET['usuario_servicio'];
                $cartera = is_array($_GET['cartera']) ? implode(',', $_GET['cartera']) : $_GET['cartera'];
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setIdCartera($cartera);
                $dtoClienteCartera->setIdUsuarioServicio($usuario_servicio);

                echo json_encode($daoClienteCartera->queryDataDistribucionPorOperador($dtoClienteCartera));

                break;
            case 'listarSituacionLaboral': 
                echo json_encode($daoClienteCartera->listarSituacionLaboral($_GET['idServicio']));
            break;
            case 'listarDisposicionRefinanciamiento': 
                echo json_encode($daoClienteCartera->listarDisposicionRefinanciamiento($_GET['idServicio']));
            break;
            case 'listarEstadoCliente': 
                echo json_encode($daoClienteCartera->listarEstadoCliente($_GET['idServicio']));
            break;
            /*jmore300612*/
            case 'ListarDataDetalleCuenta':

                    $idcuenta = $_GET['idcuenta'];
                    $idcartera = $_GET['idcartera'];

                    $dtoCartera = new dto_cartera ;
                    $dtoCartera->setId($idcartera);

                    $metadata = $daoCartera->queryCarteraMetaData($dtoCartera);

                    $metadataOperacion = json_decode($metadata[0]['detalle_cuenta'],true);

                    $fieldDetalleCuenta = array();

                    $codigo_operacion = '';
                    for( $i=0; $i<count($metadataOperacion); $i++ ) {
                            if( $metadataOperacion[$i]['campoT'] == 'total_deuda' || $metadataOperacion[$i]['campoT'] == 'codigo_operacion' ) {
                                    if( $metadataOperacion[$i]['campoT'] == 'total_deuda' ) {
                                            array_push($fieldDetalleCuenta," IFNULL( TRUNCATE( ".$metadataOperacion[$i]['campoT'].", 2 ),'') AS '".$metadataOperacion[$i]['label']."' "); 
                                    }else if( $metadataOperacion[$i]['campoT'] == 'codigo_operacion' ){
                                            $codigo_operacion = $metadataOperacion[$i]['label'];
                                            array_push($fieldDetalleCuenta," IFNULL( TRIM( ".$metadataOperacion[$i]['campoT']." ),'') AS '".$metadataOperacion[$i]['label']."' "); 
                                    }else{
                                            array_push($fieldDetalleCuenta," IFNULL( TRIM( ".$metadataOperacion[$i]['campoT']." ),'') AS '".$metadataOperacion[$i]['label']."' "); 
                                    }
                            }
                    }

                    $sql = " SELECT t1.iddetalle_cuenta, t1.codigo_operacion, t1.numero_cuenta, IFNULL(t1.moneda,'') AS 'moneda' ,  ".implode(",",$fieldDetalleCuenta)." FROM ca_detalle_cuenta t1 WHERE idcuenta = ".$idcuenta." ";

                    $dataDetalleCuenta = $daoProcedure->executeQueryReturn($sql);

                    echo json_encode(array('Ini'=>$dataDetalleCuenta));

            break;    
            case 'GuardarPago':

                    $iddetalle_cuenta = $_GET['iddetalle_cuenta'];
                    $idcartera = $_GET['idcartera'];
                    $numero_cuenta = $_GET['numero_cuenta'];
                    $moneda = (trim($_GET['moneda'])=='')?NULL:trim($_GET['moneda']);
                    $codigo_operacion = $_GET['codigo_operacion'];
                    $monto_pagado = $_GET['monto_pagado'];
                    $fecha_pago = $_GET['fecha'];
                    $estado_pago = (trim($_GET['estado_pago'])=='' || trim($_GET['estado_pago'])=='0')?NULL:trim($_GET['estado_pago']);
                    $observacion = $_GET['observacion'];
                    $agencia = $_GET['agencia'];
                    $usuario_creacion = $_GET['usuario_creacion'];

                    $dtoPago = new dto_pago ;
                    $dtoPago->setIdCartera($idcartera);
                    $dtoPago->setNumeroCuenta($numero_cuenta);
                    $dtoPago->setIdDetalleCuenta($iddetalle_cuenta);
                    $dtoPago->setMoneda($moneda);
                    $dtoPago->setCodigoOperacion($codigo_operacion);
                    $dtoPago->setMontoPagado($monto_pagado);
                    $dtoPago->setFecha($fecha_pago);
                    $dtoPago->setEstadoPago($estado_pago);
                    $dtoPago->setAgencia($agencia);
                    $dtoPago->setObservacion($observacion);
                    $dtoPago->setUsuarioCreacion($usuario_creacion);

                    echo ($daoPago->insert($dtoPago))?json_encode(array('rst'=>true,'msg'=>'Pago grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar pago'));

            break;            
            /*jmore300612*/            
            case 'NextBack':

                $cartera = is_array($_GET['cartera']) ? implode(',', $_GET['cartera']) : $_GET['cartera'];
                $servicio = $_GET['servicio'];
                $usuario_servicio = $_GET['idusuario_servicio'];
                $item = $_GET['xitem'];
                $sordmonto = $_GET['sordmonto'];
                $tramo = $_GET['tramo'];
                $tabla = trim($_GET['tabla']);
                $referencia = $_GET['referencia'];
                $campo = $_GET['campo'];
                $dato = $_GET['dato'];
                $is_ha = $_GET['is_ha'];
                $hora_inicio = $_GET['hora_inicio'];
                $hora_fin = $_GET['hora_fin'];
                $otros = $_GET['otros'];
                $departamento = $_GET['departamento'];
                $idfinal = $_GET['idfinal'];
                $usuario_matriz = $_GET['usuario_matriz'];
                $modo = $_GET['modo'];

                $dtoCartera = new dto_cartera;
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;

                $dtoCartera->setId($cartera);
                $dtoCartera->setTramo($tramo);
                $dtoUsuarioServicio->setId($usuario_servicio);
                $dtoUsuarioServicio->setIdServicio($servicio);

                function MapArray($n) {
                    return "'" . $n['codigo_cliente'] . "'";
                }

                ;

                function MapArrayIdCliente($n) {
                    return $n['idcliente'];
                }

                ;

                $filtroEstado = "";
                $filtroUsuario = "";
                $filtroModo = "";
                if ($modo == 'cartera') {
                    if ($usuario_matriz == '0') {
                        $filtroUsuario = " clicar.idusuario_servicio = " . $usuario_servicio . " ";
                    } else {
                        $filtroUsuario = " clicar.idusuario_servicio = " . $usuario_matriz . " ";
                    }
                    $filtroModo = " clicar.filtro = 1 ";
                } else {
                    if ($usuario_matriz == '0') {
                        $filtroUsuario = " clicar.idusuario_servicio_especial = " . $usuario_servicio . " ";
                    } else {
                        $filtroUsuario = " clicar.idusuario_servicio_especial = " . $usuario_matriz . " ";
                    }
                    $filtroModo = " clicar.filtro_especial = 1 ";
                }

                $sql = " ";
                if ($sordmonto == '0') {
                    $sql = "    SELECT t1.idcliente_cartera
    							FROM (
    							SELECT @rownum:=@rownum+1 AS 'item', fil.idcliente_cartera
    							FROM ca_filtro fil ,  ( SELECT @rownum:=0 ) r 
    							WHERE fil.idcartera IN (" . $cartera . ") AND fil.session = '" . session_id() . "' AND fil.idusuario_servicio = " . $usuario_servicio . "
    							) t1 WHERE t1.item = " . $item . " LIMIT 1  ";
                } else {                   


                    // if($otros=='provision'){
                    //     $ordendefault="clicar.deuda";
                    // }else{
                    //     $ordendefault="SUM(cu.total_deuda)";
                    // }



                    $ordendefault="SUM(detcu.dato20)";


                    $sql = "    SELECT t1.idcliente_cartera 
    							FROM
    							(
        							SELECT 
                                    @rownum:=@rownum+1 AS 'item', 
                                    t2.idcliente_cartera
        							FROM 
        							(
        							SELECT 
                                    clicar.idcliente_cartera, SUM(detcu.dato20)
        							FROM 
                                    ca_filtro fil 
                                    INNER JOIN ca_cliente_cartera clicar 
                                    INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente_cartera = fil.idcliente_cartera
        							INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                                    WHERE fil.idcartera IN (" . $cartera . ") AND fil.session = '" . session_id() . "' AND clicar.idcartera IN (" . $cartera . ")
        							AND cu.idcartera IN (" . $cartera . ") AND fil.idusuario_servicio = " . $usuario_servicio . " 
        							GROUP BY clicar.idcliente_cartera ORDER BY $ordendefault $sordmonto
        							) t2 , ( SELECT @rownum:=0 ) r 
    							) t1 WHERE t1.item = " . $item . " LIMIT 1 ";
                    // echo $sql;
                }


                $sqlGestion = " SELECT clicar2.estado_cliente,clicar2.idcliente_cartera,cli.idcliente,cli.idservicio, clicar2.idcartera, cli.codigo,clicar2.estado, clicar2.retiro, clicar2.reclamo, clicar2.motivo_retiro, 
    							TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
    							IFNULL(cli.numero_documento,'') AS 'numero_documento',
    							IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
    							IF( clicar2.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar2.idusuario_servicio ) )  AS 'gestor',
                                IFNULL((SELECT nombre FROM ca_motivo_no_pago mot WHERE mot.idmotivo_no_pago=clicar2.ul_motivo_no_pago),'')AS 'ul_motivo_no_pago',IFNULL(clicar2.deuda,'0') as provision
    							FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente=cli.idcliente 
    							WHERE cli.estado=1 AND cli.idservicio = " . $servicio . " AND clicar2.idcartera IN (" . $cartera . ")
    							AND clicar2.idcliente_cartera=( 
    								$sql
    							) ";

                $dataGestion = $daoProcedure->executeQueryReturn($sqlGestion);
                echo json_encode($dataGestion);

                break;
            case 'ListarPago':

                $idcartera = $_GET['idcartera'];
                $codigo_operacion = $_GET['codigo_operacion'];

                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($idcartera);

                $metadata = $daoCarteraPago->queryCarteraMetaData($dtoCartera);
                if (count($metadata) > 0) {
                    $metadataPago = json_decode($metadata[0]['pago'], true);

                    $fieldPago = array();
                    /* foreach( $metadataPago as $index => $value ) {
                      if( $index == 'monto_pagado' ) {
                      array_push($fieldPago," TRUNCATE( ".$index.", 2 ) AS '".$value."' ");
                      }else{
                      array_push($fieldPago," TRIM( IFNULL( ".$index.",'' ) ) AS '".$value."' ");
                      }
                      } */

                    for ($i = 0; $i < count($metadataPago); $i++) {
                        if ($metadataPago[$i]['campoT'] == 'monto_pagado') {
                            array_push($fieldPago, " TRUNCATE( " . $metadataPago[$i]['campoT'] . ", 2 ) AS '" . $metadataPago[$i]['label'] . "' ");
                        } else if ($metadataPago[$i]['campoT'] == 'call_center') {
                            
                        } else {
                            array_push($fieldPago, " TRIM( IFNULL( " . $metadataPago[$i]['campoT'] . ",'' ) ) AS '" . $metadataPago[$i]['label'] . "' ");
                        }
                    }

                    $sqlPago = " SELECT idpago, " . implode(",", $fieldPago) . " , 
                        DATE(fecha_creacion) AS 'FECHA CARGA', TIME(fecha_creacion) AS 'HORA CARGA' 
                        FROM ca_pago 
                        WHERE idcartera = $idcartera AND codigo_operacion = '$codigo_operacion' AND estado = 1 ";

                    //$sqlPago = " SELECT idpago, ".implode(",",$fieldPago)." FROM ca_pago WHERE idcartera = $idcartera AND estado = 1 AND codigo_operacion IN ( SELECT codigo_operacion FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND numero_cuenta IN  ) ) ";

                    $dataPago = $daoProcedure->executeQueryReturn($sqlPago);

                    echo json_encode(array('dataPago' => $dataPago));
                } else {
                    echo json_encode(array('dataPago' => array()));
                }
                break;
            case 'ListarOperacion':

                $idcartera = $_GET['idcartera'];
                $idcuenta = $_GET['idcuenta'];
				$codigo_cliente = $_GET['codigo_cliente'];

                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($idcartera);

                $metadata = $daoCartera->queryCarteraMetaData($dtoCartera);

                $metadataOperacion = json_decode($metadata[0]['detalle_cuenta'], true);

                $metadataAdicionalesDetalleCuenta = json_decode($metadata[0]['adicionales'], true);
                $adicionales_detalle_cuenta = $metadataAdicionalesDetalleCuenta['ca_datos_adicionales_detalle_cuenta'];

                $fieldDetalleCuenta = array();

                $codigo_operacion = '';
                $countMontoPagado = 0;
                for ($i = 0; $i < count($metadataOperacion); $i++) {
                    if ($metadataOperacion[$i]['campoT'] == 'total_deuda' || $metadataOperacion[$i]['campoT'] == 'total_deuda_dolares' || $metadataOperacion[$i]['campoT'] == 'monto_mora' || $metadataOperacion[$i]['campoT'] == 'monto_mora_soles' || $metadataOperacion[$i]['campoT'] == 'monto_mora_dolares' || $metadataOperacion[$i]['campoT'] == 'saldo_capital' || $metadataOperacion[$i]['campoT'] == 'saldo_capital_soles' || $metadataOperacion[$i]['campoT'] == 'saldo_capital_dolares') {
                        array_push($fieldDetalleCuenta, " IFNULL( TRUNCATE( t1." . $metadataOperacion[$i]['campoT'] . ", 2 ),'') AS '" . $metadataOperacion[$i]['label'] . "' ");
                    } else if ($metadataOperacion[$i]['campoT'] == 'codigo_operacion') {
                        $codigo_operacion = $metadataOperacion[$i]['label'];
                        array_push($fieldDetalleCuenta, " IFNULL( TRIM( t1." . $metadataOperacion[$i]['campoT'] . " ),'') AS '" . $metadataOperacion[$i]['label'] . "' ");
                    } else if ($metadataOperacion[$i]['campoT'] == 'monto_pagado') {
                        array_push($fieldDetalleCuenta, " TRUNCATE( IFNULL( t1." . $metadataOperacion[$i]['campoT'] . " ,0),2 ) AS '" . $metadataOperacion[$i]['label'] . "' ");
                        $countMontoPagado++;
                    } else if($metadataOperacion[$i]['campoT'] == 'total_deuda_soles') {
                        
                    } else {
                        array_push($fieldDetalleCuenta, " IFNULL( TRIM( t1." . $metadataOperacion[$i]['campoT'] . " ),'') AS '" . $metadataOperacion[$i]['label'] . "' ");
                    }
                }

                if ($countMontoPagado == 0) {
                    array_push($fieldDetalleCuenta, " IFNULL( TRUNCATE(t1.monto_pagado,2) , '') AS 'MONTO PAGADO' ");
                }

                array_push($fieldDetalleCuenta, " IFNULL( DATE(t1.ul_fecha_pago) , '') AS 'ULT. F. PAGO' ");

                for ($i = 0; $i < count($adicionales_detalle_cuenta); $i++) {
                    array_push($fieldDetalleCuenta, " IFNULL( t1." . $adicionales_detalle_cuenta[$i]['campoT'] . " , '') AS '" . $adicionales_detalle_cuenta[$i]['label'] . "'");
                }


				$sqlListarDetalleCuenta = " SELECT 
                                            t1.iddetalle_cuenta AS 'ID'," . implode(",", $fieldDetalleCuenta) . " 
                                            FROM 
                                            ca_detalle_cuenta t1 
                                            WHERE 
                                            t1.idcartera = $idcartera AND 
                                            t1.codigo_cliente='$codigo_cliente'
                                            ORDER BY t1.fecha_vencimiento DESC
                                            ";
                // echo $sqlListarDetalleCuenta;
                $dataDetalleCuenta = $daoProcedure->executeQueryReturn($sqlListarDetalleCuenta);

                echo json_encode(array('dataDetalleCuenta' => $dataDetalleCuenta, 'codigo_operacion' => $codigo_operacion));

                break;
            case 'ListarCuenta':

                $idcartera = $_GET['idcartera'];
                $idservicio = $_GET['idservicio'];
                $idcliente_cartera = $_GET['idcliente_cartera'];
                $p_interes = @$_GET['PorInteres']; 
                $p_descuento = @$_GET['PorDescuento']; 
                $is_interes_descuento = @$_GET['IsInteresDescuento']; 
                $is_monto_cobrar = @$_GET['IsMontoCobrar']; 
                $is_monto_vencido_por_vencer = @$_GET['IsMontoVencidoPorVencer'];

                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($idcartera);

                $metadata = $daoCartera->queryCarteraMetaData($dtoCartera);

                $metadataCuenta = json_decode($metadata[0]['cuenta'], true);

                $metadataAdicionalesCuenta = json_decode($metadata[0]['adicionales'], true);
                $adicionales_cuenta = $metadataAdicionalesCuenta['ca_datos_adicionales_cuenta'];

                $fieldCuenta = array();
                
                $sd = simplexml_load_file( '../xml/struct.xml' );
                $rs = $sd->xpath('servicio[@id="'.$idservicio.'_"]');
                $std = $rs[0]->xpath('campo[@table="cuenta" and @field="inscripcion"]');
                //$str = array();
                for( $i=0;$i<count($std);$i++ ) {
                        
                        $attr = $std[$i]->attributes();
                        if( $attr['issubstr'] ) {
                                
                                $df = $std[$i]->xpath('substring');
                                for( $j=0;$j<count($df);$j++ ){
                                        $df_attr = $df[$j]->attributes();
                                        if( $df_attr['isequal'] ) {
                                                
                                                $eq = $df[$j]->xpath('equal');
                                                $field = array();
                                                for( $k=0;$k<count($eq);$k++ ) {
                                                        $eq_attr = $eq[$k]->attributes();
                                                        array_push( $field, " WHEN SUBSTRING( inscripcion, ".$df_attr['init'].", ".$df_attr['length']." ) = '".$eq_attr['value']."' THEN '".$eq[$k][0]."' " );
                                                }
                                                
                                                array_push( $fieldCuenta, " CASE ".implode(" ",$field)."  ELSE '' END AS '".$df_attr['label']."_:_".$df_attr['label']."' " );
                                                
                                        }else{
                                                array_push( $fieldCuenta, " SUBSTRING( inscripcion, ".$df_attr['init'].", ".$df_attr['length']." ) AS '".$df_attr['label']."_:_".$df_attr['label']."' " );
                                        }
                                }                                
                        }                       
                }
                

                
                
                $moneda = '';
                $numero_cuenta = '';
                $countTotalDeuda = 0;
                $countTotalInteres = 0;
                $countMontoPagado = 0;
                $countMoneda = 0;
                for ($i = 0; $i < count($metadataCuenta); $i++) {
                    if ($metadataCuenta[$i]['campoT'] == 'total_deuda' || $metadataCuenta[$i]['campoT'] == 'saldo_capital') {
                        array_push($fieldCuenta, " TRUNCATE( IFNULL(t1." . $metadataCuenta[$i]['campoT'] . ",0),2) AS '" . $metadataCuenta[$i]['label']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                        $countTotalDeuda++;
                    } else if ($metadataCuenta[$i]['campoT'] == 'monto_pagado') {
                        array_push($fieldCuenta, " TRUNCATE( t1." . $metadataCuenta[$i]['campoT'] . ",2) AS '" . $metadataCuenta[$i]['label']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                        $countMontoPagado++;
                    } else if ($metadataCuenta[$i]['campoT'] == 'total_comision') {
                        array_push($fieldCuenta, " TRUNCATE( t1." . $metadataCuenta[$i]['campoT'] . ",2) AS '" . $metadataCuenta[$i]['label']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                        $countTotalInteres++;
                    } else if ($metadataCuenta[$i]['campoT'] == 'moneda') {
                        $moneda = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['label']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                        $countMoneda++;
                    } else if ($metadataCuenta[$i]['campoT'] == 'cuota_mensual') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    } else if ($metadataCuenta[$i]['campoT'] == 'seguros') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    } else if ($metadataCuenta[$i]['campoT'] == 'otros') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    } else if ($metadataCuenta[$i]['campoT'] == 'numero_cuenta') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    }else if ($metadataCuenta[$i]['campoT'] == 'producto') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    }else if ($metadataCuenta[$i]['campoT'] == 'inscripcion') {
                        $numero_cuenta = $metadataCuenta[$i]['label'];
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['dato']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    } else {
                        array_push($fieldCuenta, " IFNULL( t1." . $metadataCuenta[$i]['campoT'] . " , '') AS '" . $metadataCuenta[$i]['label']."_:_".$metadataCuenta[$i]['campoT'] . "'");
                    }
                }


                if( $countMoneda == 0 ) {
                    array_push($fieldCuenta, " IFNULL(t1.moneda,'') AS 'MONEDA_:_moneda' ");
                }
                if ($countTotalDeuda == 0) {
                    array_push($fieldCuenta, " TRUNCATE( IFNULL(t1.total_deuda,0),2 ) AS 'DEUDA_:_total_deuda' ");
                }
                if ($countTotalInteres == 0) {
                    
                }
                if ($countMontoPagado == 0) {                    
                    array_push($fieldCuenta, " '0' AS 'MONTO PAGADO_:_monto_pagado' ");
                }
                if ($is_monto_cobrar == '1') {
                    array_push($fieldCuenta, " TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) ),2 ) AS 'MONTO_COBRAR_:_MONTO_COBRAR' ");
                }

                
                array_push($fieldCuenta, " IFNULL( t1.estado_pago,'' ) AS 'ESTADO_CUENTA_:_estado_pago' ");              
                array_push($fieldCuenta, " TRUNCATE( ( IFNULL( t1.total_deuda,0 ) + IFNULL(t1.total_comision,0) - IFNULL(t1.monto_pagado,0) ),2 ) AS 'SALDO_:_SALDO' ");
                array_push($fieldCuenta, " IF( ISNULL(t1.estado_pago)=0 AND TRIM(t1.estado_pago) != '' , TRIM(t1.estado_pago),  IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  <= t1.monto_pagado , 'CANCELADO', IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  != t1.monto_pagado , 'AMORTIZADO', '' )  )   ) AS 'status_:_status' ");

                if ($is_interes_descuento) {
                    array_push($fieldCuenta, " TRUNCATE( ( ( t1.total_deuda + IFNULL(t1.total_comision,0) - t1.monto_pagado ) * " . $p_interes . " ) ,2 ) AS 'TOTAL INTERES_:_TOTAL_INTERES' ");
                    array_push($fieldCuenta, " TRUNCATE( ( t1.total_deuda - t1.monto_pagado + ( IFNULL(t1.total_comision,0) * " . $p_interes . " ) ) ,2 ) AS 'TOTAL DESCUENTO_:_TOTAL_DESCUENTO' ");
                }                

                if ($is_monto_vencido_por_vencer == '1') {
                    if (trim($moneda) == '') {
                        array_push($fieldCuenta, " ( TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - t1.monto_pagado ),2 ) - IFNULL( ( SELECT TRUNCATE( SUM(total_deuda - monto_pagado),2 )  FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND numero_cuenta = t1.numero_cuenta AND CONVERT(fecha_vencimiento,DATE)>CURDATE() LIMIT 1 ),0 ) ) AS 'MONTO VENCIDO_:_MONTO_VENCIDO' ");
                        array_push($fieldCuenta, " IFNULL( ( SELECT TRUNCATE( SUM(total_deuda - monto_pagado),2 )  FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND numero_cuenta = t1.numero_cuenta AND CONVERT(fecha_vencimiento,DATE)>CURDATE() LIMIT 1 ), 0) AS 'MONTO POR VENCER_:_MONTO_POR_VENCER' ");
                    } else {
                        array_push($fieldCuenta, " ( TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - t1.monto_pagado ),2 ) - IFNULL( ( SELECT TRUNCATE( SUM(total_deuda - monto_pagado),2 )  FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND numero_cuenta = t1.numero_cuenta AND moneda = t1.moneda AND CONVERT(fecha_vencimiento,DATE)>CURDATE() LIMIT 1 ), 0 ) ) AS 'MONTO VENCIDO_:_MONTO_VENCIDO' ");
                        array_push($fieldCuenta, " IFNULL( ( SELECT TRUNCATE( SUM(total_deuda - monto_pagado),2 )  FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND numero_cuenta = t1.numero_cuenta AND moneda = t1.moneda AND CONVERT(fecha_vencimiento,DATE)>CURDATE() LIMIT 1 ), 0) AS 'MONTO POR VENCER_:_MONTO_POR_VENCER' ");
                    }
                }                
				
				$sqlListarCuenta = "    SELECT 
					                    cu.idcuenta AS 'idcuenta_:_idcuenta',
                                        IF( cu.retirado=1, CONCAT_WS(' ','<font color=\"red\"><b>RETIRADO</b></font>', cu.fecha_retiro, cu.motivo_retiro),IF(ISNULL(cu.estado_cuenta),'NO',cu.estado_cuenta) ) AS 'RETIRADO_:_RETIRADO',
                                        detcu.dato2 AS 'empresa_:_empresa',
                                        detcu.dato8 AS 'td_:_td',
                                        detcu.moneda AS 'mon_:_mon',
                                        detcu.codigo_operacion AS 'num_doc_:_num_doc',
                                        detcu.fecha_emision AS 'fecha_doc_:_fecha_doc',
                                        detcu.fecha_vencimiento AS 'fecha_vcto_:_fecha_vcto',
                                        detcu.dias_mora AS 'dias_transc_vcto_of_:_dias_transc_vcto_of',
                                        detcu.dato15 AS 'rango_vcto_:_rango_vcto',
                                        detcu.marca_cat AS 'marca_cat_:_marca_cat',
                                        IFNULL(detcu.dato22,'') AS 'est_letr_:_est_letr',
                                        IFNULL(detcu.dato23,'') AS 'banco_:_banco',
                                        IFNULL(detcu.dato24,'') AS 'num_cobranza_:_num_cobranza',
                                        -- detcu.dato18 AS 'semaforo_de_vencimiento_:_semaforo_de_vencimiento',
                                        detcu.total_deuda AS 'importe_original_:_importe_original',
                                        detcu.saldo_capital_dolares AS 'total_convertido_a_dolares_:_total_convertido_a_dolares',
                                        detcu.saldo_capital_soles AS 'total_convertido_a_soles_:_total_convertido_a_soles'

                                        FROM ca_cuenta cu
                                        INNER JOIN ca_detalle_cuenta detcu ON cu.idcuenta=detcu.idcuenta
                                        WHERE 
                                        cu.idcartera=$idcartera  AND 
                                        cu.estado=1 AND
                                        cu.idcliente_cartera = $idcliente_cartera
                                        ";
				
                // echo $sqlListarCuenta;
                $dataCuenta = $daoProcedure->executeQueryReturn($sqlListarCuenta);
                $data = array();
                for( $i=0;$i<count($dataCuenta);$i++ ) {
                        $dataC = array();
                        foreach( $dataCuenta[$i] as $index => $value ) {
                                $label = explode("_:_",$index);
                                array_push( $dataC, array( "dato"=>$value,"label"=>@$label[0],"campoT"=>@$label[1] ) );
                        }
                        array_push( $data, $dataC );
                }

                echo json_encode(array('dataCuenta' => $data, 'numero_cuenta' => $numero_cuenta, 'moneda_cuenta' => $moneda));

                break;
            case 'ListarCliente':

                $idcartera = $_GET['idcartera'];
                $servicio = $_GET['servicio'];
                $codigo_cliente = $_GET['codigo_cliente'];
                $idcliente_cartera = $_GET['idcliente_cartera'];

                $dtoCartera = new dto_cartera ;
                $dtoCartera->setId($idcartera);

                $metadata = $daoCartera->queryCarteraMetaData($dtoCartera);

                $metadataAdicionalesCliente = json_decode($metadata[0]['adicionales'],true);
                $adicionales_cliente= $metadataAdicionalesCliente['ca_datos_adicionales_cliente'];
                $fieldCliente = array();

                for( $i=0;$i<count($adicionales_cliente);$i++ ) {
                    array_push($fieldCliente," IFNULL( clicar.".$adicionales_cliente[$i]['campoT']." , '') AS '".$adicionales_cliente[$i]['label']."'");
                } 

                
                if(count($fieldCliente)>0){
                $sqlCliente = "     SELECT 
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE', 
                                    CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre))  AS 'CLIENTE', 
                                    IFNULL(cli.tipo_documento,'') AS 'TIPO_DOCUMENTO', 
                                    IFNULL(cli.numero_documento,'') AS 'NUMERO_DOCUMENTO',
                                    IFNULL(cli.fecha_nacimiento,'') AS 'FECHA_NACIMIENTO', 
                                    car.nombre_cartera AS NOMBRE_CARTERA, 
                                    ".implode(",",$fieldCliente)."
                                    FROM ca_cliente cli 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente 
                                    INNER JOIN ca_cartera car ON clicar.idcartera=car.idcartera 
                                    WHERE 
                                    cli.idservicio = $servicio AND 
                                    clicar.idcartera = $idcartera AND 
                                    clicar.codigo_cliente = '$codigo_cliente' ";
                                    
                }else{
                $sqlCliente = "     SELECT 
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE', 
                                   --  CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) AS 'CLIENTE', 
                                    IF(TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) IS NULL OR TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))='',cli.razon_social,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))) AS 'CLIENTE', 
                                    IFNULL(cli.tipo_documento,'') AS 'TIPO_DOCUMENTO', 
                                    IFNULL(cli.numero_documento,'') AS 'NUMERO_DOCUMENTO',
                                    IFNULL(cli.fecha_nacimiento,'') AS 'FECHA_NACIMIENTO', 
                                    IFNULL(clicar.tipo_cliente,'') AS 'TIPO_CLIENTE', 
                                    car.nombre_cartera AS NOMBRE_CARTERA,
                                    -- cli.tipo_adjudicacion AS 'TIPO_ADJUDICACION'
                                    (SELECT DISTINCT dato16 FROM ca_detalle_cuenta WHERE idcartera=$idcartera AND estado=1 AND codigo_cliente='$codigo_cliente') AS 'LINEA_CREDITO',
                                    (SELECT DISTINCT dato28 FROM ca_detalle_cuenta WHERE idcartera=$idcartera AND estado=1 AND codigo_cliente='$codigo_cliente') AS 'FECHA_AL'
                                    FROM ca_cliente cli 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente 
                                    INNER JOIN ca_cartera car ON clicar.idcartera=car.idcartera 
                                    WHERE 
                                    cli.idservicio = $servicio AND 
                                    clicar.idcartera = $idcartera AND 
                                    clicar.codigo_cliente = '$codigo_cliente' AND 
                                    clicar.idcliente_cartera=$idcliente_cartera
                                    ";  
                // echo $sqlCliente;

                }

                $sqlrepresentante=" SELECT 
                                    rep.idrepresentante_legal,
                                    rep.contrato,
                                    rep.codigo_cliente AS 'doi',
                                    IF(rep.representante_legal NOT IN (''),rep.representante_legal,CONCAT(rep.nombre,' ',rep.paterno,' ',rep.materno)) AS 'datos',
                                    rep.tipo_persona AS 'tipo_persona'
                                    FROM ca_representante_legal rep
                                    WHERE 
                                    rep.estado=1 AND 
                                    -- rep.contrato IN (SELECT DISTINCT contrato FROM ca_cliente WHERE codigo='$codigo_cliente')   
                                    rep.contrato IN (SELECT DISTINCT negocio FROM ca_cuenta WHERE codigo_cliente='$codigo_cliente')
                                    ORDER BY rep.contrato ASC
                                    ";

                $datarepresentante=$daoProcedure->executeQueryReturn($sqlrepresentante);


                $sqlGestion = " SELECT COUNT(*) AS 'COUNT' FROM ca_detalle_cuenta WHERE idcartera = $idcartera AND codigo_cliente = '$codigo_cliente' ";



                $sqlEstado = " SELECT IFNULL( estado_final,'' ) AS 'estado_final' FROM ca_cuenta WHERE idcartera = $idcartera AND codigo_cliente = '$codigo_cliente' LIMIT 1 ";

                $dataCliente = $daoProcedure->executeQueryReturn($sqlCliente);

                $dataClienteG = array();
                if($servicio==1 && $idcartera==0){
                foreach( $dataCliente as $index => $value ) {
                    $data = array();
                    $data['CODIGO_CLIENTE'] = $value['CODIGO_CLIENTE'];
                    $data['CLIENTE'] = utf8_encode( $value['CLIENTE'] );
                    $data['TIPO DOC'] = $value['TIPO_DOCUMENTO'];
                    $data['NUMERO DOC'] = $value['NUMERO_DOCUMENTO'];
                    $data['FECHA NAC'] = $value['FECHA_NACIMIENTO'];
                    $data['CARTERA']=$value['CARTERA'];
                    $data['CUENTA RECAUDADORA 1']=$value['CUENTA_RECAUDADORA_1'];
                    $data['CUENTA RECAUDADORA 2']=$value['CUENTA_RECAUDADORA2'];
                    $data['CODIGO INTERBANCARIO']=$value['CODIGO_INTERBANCARIO'];                        
                    array_push( $dataClienteG , $data );
                }
                }else{
                foreach( $dataCliente as $index => $value ) {
                    $data = array();
                    $data['CODIGO_CLIENTE'] = $value['CODIGO_CLIENTE'];
                    $data['CLIENTE'] = utf8_encode( $value['CLIENTE'] );
                    $data['TIPO_CLIENTE'] = $value['TIPO_CLIENTE'];
                    $data['NUMERO DOC'] = $value['NUMERO_DOCUMENTO'];
                    //$data['TIPO_ADJUDICACION'] = $value['TIPO_ADJUDICACION'];
                    $data['LINEA_CREDITO'] = $value['LINEA_CREDITO'];
                    $data['FECHA_AL'] = $value['FECHA_AL'];
                    $data['CARTERA'] = $value['NOMBRE_CARTERA'];
                    array_push( $dataClienteG , $data );
                }                    
                }
                $dataGestion = $daoProcedure->executeQueryReturn($sqlGestion);

                echo json_encode(array('dataCliente' => $dataClienteG, 'isGestion' => $dataGestion, 'estado_final' => array() ,'datarepresentante'=>$datarepresentante));

                break;
            case 'ListarNotificador':

                $idservicio = $_GET['Servicio'];

                $dto_notificador = new dto_notificador;
                $dto_notificador->setIdServicio($idservicio);

                echo json_encode($daoNotificador->queryByService($dto_notificador));

                break;
            case 'Listar_Representantes':

                $codigo_cliente = $_GET['codigo_cliente'];

                $daoJqgrid->Listar_Representante($codigo_cliente);

                break;
            case 'ListarDireccion':

                $idcartera = $_GET['idcartera'];
                $codigo_cliente = $_GET['codigo_cliente'];

                $dtoDireccion = new dto_direccion_ER2;
                $dtoDireccion->setIdCartera($idcartera);
                $dtoDireccion->setCodigoCliente($codigo_cliente);

                echo json_encode($daoDireccion->queryDataByCodeClient($dtoDireccion));

                break;
            case 'ListarDireccionVisita':

                $idcartera = $_GET['idcartera'];
                $codigo_cliente = $_GET['codigo_cliente'];

                $dtoDireccion = new dto_direccion_ER2;
                //$dtoDireccion->setIdCartera($idcartera);
                $dtoDireccion->setCodigoCliente($codigo_cliente);

                echo json_encode($daoDireccion->queryDataByCodeClientVisita($dtoDireccion));

                break;                
            case 'MostrarNotaPorId':
                $dtoNota = new dto_nota;
                $dtoNota->setId($_GET['Id']);

                echo json_encode($daoNota->queryById($dtoNota));

                break;
            case 'ranking_usuario_servicio':
                $carteras = implode(',', $_GET['Cartera']);
                $dtoServicio = new dto_servicio;
                $dtoClienteCartera = new dto_cliente_cartera;

                $dtoServicio->setId($_GET['Servicio']);
                $dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
                $dtoClienteCartera->setIdCartera($carteras);

                echo json_encode($daoProcedure->ranking_usuario_servicio($dtoClienteCartera, $dtoServicio));

                break;
            case 'ListCuenta':

                // $dtoCuenta = new dto_cuenta;
                // $dtoCuenta->setIdCartera($_GET['Cartera']);
                // $dtoCuenta->setIdClienteCartera($_GET['IdClienteCartera']);

                $idcartera=$_GET['Cartera'];
                $idcliente_cartera=$_GET['IdClienteCartera'];
                $empresa=$_GET['empresa'];
                $td=$_GET['td'];
                $doc=$_GET['doc'];
                $contado=$_GET['contado'];
                // echo json_encode($daoCuenta->queryByClient($dtoCuenta),$empresa);
                echo json_encode($daoCuenta->queryByClient($idcartera,$idcliente_cartera,$empresa,$td,$doc,$contado));

                break;
            case 'ListTramo':
                $carteras = is_array($_GET['Cartera']) ? implode(',', $_GET['Cartera']) : $_GET['Cartera'];
                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($carteras);

                echo json_encode($daoDetalleCuenta->queryTramo($dtoCartera));

                break;
            case 'ListState':

                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['Servicio']);

                $e_llamada = $daoFinalServicio->queryStateLlamadaByServicio($dtoServicio);
                $e_cuenta = array();
                $e_cuotificacion = $daoFinalServicio->queryStateCuotificacion($dtoServicio);
                $e_visita = $daoFinalServicio->queryStateVisitaByServicio($dtoServicio);

                echo json_encode( array( "llamada" => $e_llamada, "cuenta" => $e_cuenta, "visita" => $e_visita, "cuotificacion" => $e_cuotificacion ));

                break;

            case 'ListarSpeechArgumentario':
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['Servicio']);

                echo json_encode($daoAyudaGestion->queryPorServicioTextoNoTexto($dtoServicio));

                break;
            case 'DatosComisionTotalCuenta':
                $dto = new dto_cliente_cartera;
                $dto->setId($_GET['ClienteCartera']);
                echo json_encode($daoDetalleCuenta->queryTotalComision($dto));
                break;
            case 'ListarOperadoresAyudar':
                $carteras = is_array($_GET['Cartera']) ? implode(',', $_GET['Cartera']) : $_GET['Cartera'];
                //$carteras = implode(',',$_GET['Cartera']);
                $idusuario_servicio = $_GET['idusuario_servicio'];
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setIdCartera($carteras);
                echo json_encode($daoAyudaGestionUsuario->queryListarUsuariosAsignados($dtoClienteCartera, $idusuario_servicio));
                break;
            case 'SearchTelefonosCliente':
                $dtoCliente = new dto_cliente;
                $dtoCliente->setNombre($_GET['Cliente']);
                echo json_encode($daoTelefono->queryTelefonosPorNombreCliente($dtoCliente));
                break;
            case 'DataLlamadaPorId':
                $dtoTransaccion = new dto_transaccion;
                $dtoTransaccion->setId($_GET['Id']);
                echo json_encode($daoTransaccion->queryDataLlamadaById($dtoTransaccion));
                break;
            case 'DataVisitaPorId':
                $dtoTransaccion = new dto_transaccion;
                $dtoTransaccion->setId($_GET['Id']);
                echo json_encode($daoTransaccion->queryDataVisitaById($dtoTransaccion));
                break;
            case 'DataTelefonoPorId':
                $dtoTelefono = new dto_telefono_ER2;
                $dtoTelefono->setId($_GET['Id']);
                echo json_encode($daoTelefono->queryById($dtoTelefono));
                break;
            case 'DataDireccionPorId':
                $dtoDireccion = new dto_direccion_ER2;
                $dtoDireccion->setId($_GET['Id']);
                echo json_encode($daoDireccion->queryDataById($dtoDireccion));
                break;
            case 'ListarTareasHoy':
                $dtoTarea = new dto_tarea;
                $dtoTarea->setIdUsuarioServicio($_GET['UsuarioServicio']);

                echo json_encode($daoTarea->queryWorkToDay($dtoTarea));
                break;
            case 'ListarEventosHoy':
                $dtoEvento = new dto_evento;
                $dtoEvento->setIdUsuarioServicio($_GET['UsuarioServicio']);
                echo json_encode($daoEvento->queryEventToDay($dtoEvento));
                break;
            case 'ListarLineaTelefono':
                echo json_encode($daoLineaTelefono->queryIdName());
                break;
            case 'ListarFiltrosTablaAtencionCliente':
                $dtoFiltro = new dto_filtros;
                $dtoFiltro->setIdServicio($_GET['Servicio']);
                $dtoFiltro->setIdTipoFiltro(1);

                echo json_encode($daoFiltros->queryTablaByService($dtoFiltro));
                break;
            case 'ListarNotasHoy':
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setIdCampania($_GET['Campania']);
                $dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
                echo json_encode($daoNota->queryAllToDay($dtoClienteCartera));
                break;
            case 'ListarFiltrosCampoAtencionCliente':
                $dtoFiltro = new dto_filtros;
                $dtoFiltro->setIdServicio($_GET['Servicio']);
                $dtoFiltro->setIdTipoFiltro(1);
                $dtoFiltro->setTabla($_GET['Tabla']);

                echo json_encode($daoFiltros->queryCampoByTabla($dtoFiltro));
                break;
            case 'ListarCampaniasActivas':
                $dto = new dto_servicio;
                $dto->setId($_GET['Servicio']);
                echo json_encode($daoCampania->queryByIdNameStatusActive($dto));
            break;
			//~ Vic I
			case 'ListarCarteraHistory':
                $dtoCartera=new dto_cartera;
                $dtoCartera->setId($_GET['idcartera']);
				echo json_encode($daoCampania->ListarCarteraHistory($dtoCartera));
				break;
			//~ Vic F
            case 'ListarCampanias':
                $dto = new dto_servicio;
                $dto->setId($_GET['Servicio']);
                echo json_encode($daoCampania->queryByIdName($dto));
                break;
            case 'ListarCampaniasAll':
                $dto = new dto_servicio;
                $dto->setId($_GET['Servicio']);
                echo json_encode($daoCampania->queryAllByIdName($dto));
                break;
            case 'ListarServicio':
                $dto = new dto_usuario;
                $dto->setId($_GET['Usuario']);
                echo json_encode($daoUsuarioServicio->queryServiciosUsuario($dto));
                break;
            case 'DatosTotalCuenta':
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoCliente = new dto_cliente;
                //$dto->setId($_GET['ClienteCartera']);
                $dtoClienteCartera->setIdCartera($_GET['Cartera']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                //echo json_encode($daoDetalleCuenta->queryTotalByCuenta($dtoClienteCartera,$dtoCliente));
                /*                 * ***** */
                $data = $daoDetalleCuenta->queryTotalByCuenta($dtoClienteCartera, $dtoCliente);
                $dataCuenta = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataCuenta, array(
                        'NumeroCuenta' => $data[$i]['numero_cuenta'],
                        'Moneda' => $data[$i]['moneda'],
                        'Comision' => $data[$i]['comision'],
                        'DataCuenta' => array(
                            array("Total Deuda" => $data[$i]['total_deuda'], "Total Deuda Soles" => $data[$i]['total_deuda_soles'], "Total Deuda Dolares" => $data[$i]['total_deuda_dolares']),
                            array("Comision Total Deuda" => $data[$i]['comision_total_deuda'], "Comision Total Deuda Soles" => $data[$i]['comision_total_deuda_soles'], "Comision Total Deuda Dolares" => $data[$i]['comision_total_deuda_dolares']),
                            array("Monto Mora" => $data[$i]['monto_mora'], "Monto Mora Soles" => $data[$i]['monto_mora_soles'], "Monto Mora Dolares" => $data[$i]['monto_mora_dolares']),
                            array("Comision Monto Mora" => $data[$i]['comision_monto_mora'], "Comision Monto Mora Soles" => $data[$i]['comision_monto_mora_soles'], "Comision Monto Mora Dolares" => $data[$i]['comision_monto_mora_dolares']),
                            array("Saldo Capital" => $data[$i]['saldo_capital'], "Saldo Capital Soles" => $data[$i]['saldo_capital_soles'], "Saldo Capital Dolares" => $data[$i]['saldo_capital_dolares']),
                            array("Comision Saldo Capital" => $data[$i]['comision_saldo_capital'], "Comision Saldo Capital Soles" => $data[$i]['comision_saldo_capital_soles'], "Comision Saldo Capital Dolares" => $data[$i]['comision_saldo_capital_dolares'])
                        )
                    ));
                }
                echo json_encode($dataCuenta);
                /*                 * ***** */
                break;
            case 'ListarOrigen':
                echo json_encode($daoOrigen->queryByIdName());
                break;
            case 'ListarTipoReferencia':
                echo json_encode($daoTipoReferencia->queryByIdName());
                break;
            case 'ListarTipoTelefono':
                echo json_encode($daoTipoTelefono->queryByIdName());
                break;
            case 'ListarOperadores':
                $dto = new dto_servicio;
                $dto->setId($_GET['Servicio']);
                echo json_encode($daoUsuarioServicio->queryOperadorXServicio($dto));
                break;
            case 'ListarNotas':
                $carteras = is_array($_GET['Cartera']) ? implode(',', $_GET['Cartera']) : $_GET['Cartera'];
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoServicio = new dto_servicio;
                $dtoClienteCartera->setIdCartera($carteras);
                $dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
                $dtoServicio->setId($_GET['Servicio']);
                echo json_encode($daoNota->queryAllToDay($dtoClienteCartera, $dtoServicio));
                break;
            case 'DefaultNext':
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoClienteCartera = new dto_cliente_cartera;

                $dtoCartera->setId($_GET['Cartera']);
                $dtoCartera->setTramo($_GET['Tramo']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                $dtoClienteCartera->setId($_GET['ClienteCartera']);

                if ($_SESSION['cobrast']['privilegio'] == 'administrador' || $_SESSION['cobrast']['privilegio'] == 'supervisor') {

                    if ($_GET['Tramo'] == '0') {
                        echo json_encode($daoClienteCartera->consultaNextGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    } else {
                        echo json_encode($daoClienteCartera->consultaNextTramoGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    }
                } else {

                    if ($_GET['Tramo'] == '0' && $_GET['Monto'] == '0') {
                        echo json_encode($daoClienteCartera->consultaNext($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    } else if ($_GET['Monto'] == 'DESC' || $_GET['Monto'] == 'ASC') {
                        echo json_encode($daoClienteCartera->consultaNextMonto($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera, $_GET['Monto']));
                    } else {
                        echo json_encode($daoClienteCartera->consultaNextTramo($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    }
                }

                break;
            case 'DefaultBack':
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoClienteCartera = new dto_cliente_cartera;

                $dtoCartera->setId($_GET['Cartera']);
                $dtoCartera->setTramo($_GET['Tramo']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                $dtoClienteCartera->setId($_GET['ClienteCartera']);



                if ($_SESSION['cobrast']['privilegio'] == 'administrador' || $_SESSION['cobrast']['privilegio'] == 'supervisor') {

                    if ($_GET['Tramo'] == '0') {
                        echo json_encode($daoClienteCartera->consultaBackGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    } else {
                        echo json_encode($daoClienteCartera->consultaBackTramoGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    }
                } else {

                    if ($_GET['Tramo'] == '0' && $_GET['Monto'] == '0') {
                        echo json_encode($daoClienteCartera->consultaBack($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    } else if ($_GET['Monto'] == 'DESC' || $_GET['Monto'] == 'ASC') {
                        echo json_encode($daoClienteCartera->consultaBackMonto($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera, $_GET['Monto']));
                    } else {
                        echo json_encode($daoClienteCartera->consultaBackTramo($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    }
                }

                break;
            case 'InitDefaultGestion':
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;

                $dtoCartera->setId($_GET['Cartera']);
                $dtoCartera->setTramo($_GET['Tramo']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);

                if ($_SESSION['cobrast']['privilegio'] == 'administrador' || $_SESSION['cobrast']['privilegio'] == 'supervisor') {

                    if ($_GET['Tramo'] == '0') {
                        echo json_encode($daoClienteCartera->InitDefaultGestionGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    } else {
                        echo json_encode($daoClienteCartera->InitDefaultGestionTramoGlobalCartera($dtoCartera, $dtoUsuarioServicio, $dtoClienteCartera));
                    }
                } else {

                    if ($_GET['Tramo'] == '0' && $_GET['Monto'] == '0') {
                        echo json_encode($daoClienteCartera->InitDefaultGestion($dtoCartera, $dtoUsuarioServicio));
                    } else if ($_GET['Monto'] == 'DESC' || $_GET['Monto'] == 'ASC') {
                        echo json_encode($daoClienteCartera->InitDefaultGestionMonto($dtoCartera, $dtoUsuarioServicio, $_GET['Monto']));
                    } else {
                        echo json_encode($daoClienteCartera->InitDefaultGestionTramo($dtoCartera, $dtoUsuarioServicio));
                    }
                }
                break;
            case 'DatosAdicionalesOperacion':
                $dtoServicio = new dto_servicio;
                $dtoDetalleCuenta = new dto_detalle_cuenta;
                $dtoCartera = new dto_cartera;
                $dtoServicio->setId($_GET['Servicio']);
                //$dtoDetalleCuenta->setId($_GET['DetalleCuenta']);
                $dtoDetalleCuenta->setCodigoOperacion($_GET['CodigoOperacion']);
                $dtoCartera->setId($_GET['Cartera']);
                echo json_encode($daoDatosAdicionalesDetalleCuenta->queryByDetalleCuenta($dtoServicio, $dtoDetalleCuenta, $dtoCartera));
                break;
            case 'DatosAdicionalesCuenta':
                $dtoServicio = new dto_servicio;
                $dtoCuenta = new dto_cuenta;
                $dtoCartera = new dto_cartera;
                $dtoServicio->setId($_GET['Servicio']);
				$dtoCuenta->setId($_GET['IdCuenta']);
                //$dtoCuenta->setId($_GET['Cuenta']);
                //$dtoCuenta->setNumeroCuenta($_GET['NumeroCuenta']);
                /*                 * *** */
                //$dtoCuenta->setMoneda($_GET['Moneda']);
                /*                 * *** */
                $dtoCartera->setId($_GET['Cartera']);
                echo json_encode($daoDatosAdicionalesCuenta->queryByCuenta2($dtoServicio, $dtoCuenta, $dtoCartera));


                /* $idcartera = $_GET['Cartera'];
                  $moneda = $_GET['Moneda'];
                  $numero_cuenta = $_GET['NumeroCuenta'];

                  $dtoCartera = new dto_cartera ;
                  $dtoCartera->setId($idcartera);

                  $metadata = $daoCartera->queryCarteraMetaData($dtoCartera);

                  $metadataAdicionalesCuenta = json_decode($metadata[0]['adicionales'],true);
                  $adicionales_cuenta = $metadataAdicionalesCuenta['ca_datos_adicionales_cuenta'];

                  $fieldCuenta = array();

                  for( $i=0;$i<count($adicionales_cuenta);$i++ ) {
                  array_push($fieldCuenta," IFNULL( t2.".$adicionales_cuenta[$i]['campoT']." , '') AS '".$adicionales_cuenta[$i]['label']."'");
                  }
                  $sqlListarCuenta = '';
                  if( $moneda == 'undefined' ) {
                  $sqlListarCuenta = " SELECT ".implode(",",$fieldCuenta)." FROM ca_datos_adicionales_cuenta t2 WHERE idcartera = $idcartera AND numero_cuenta = '$numero_cuenta' ";
                  }else{
                  $sqlListarCuenta = " SELECT ".implode(",",$fieldCuenta)." FROM ca_datos_adicionales_cuenta t2 WHERE idcartera = $idcartera AND numero_cuenta = '$numero_cuenta' AND moneda = '$moneda' ";
                  }
                  $dataCuenta=array();
                  if( count($fieldCuenta)>0 ) {
                  $dataCuenta = $daoProcedure->executeQueryReturn($sqlListarCuenta);
                  }else{
                  $dataCuenta=array();
                  }
                  echo json_encode($dataCuenta); */

                break;
            case 'DatosAdicionalesCliente':
                $dtoServicio = new dto_servicio;
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoServicio->setId($_GET['Servicio']);
                //$dtoCliente->setId($_GET['Cliente']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                $dtoCartera->setId($_GET['Cartera']);
                echo json_encode($daoDatosAdicionalesCliente->queryByCliente($dtoCliente, $dtoCartera, $dtoServicio));
                break;
            case 'SearchClientByCode':
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente->setCodigo($_GET['Codigo']);
                
                $dtoCliente->setIdServicio($_GET['Servicio']);
                
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                echo json_encode($daoClienteCartera->SearchClientByCode($dtoCliente, $dtoCartera, $dtoUsuarioServicio));
                break;
            case 'SearchClientByDni':
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente->setNumeroDocumento($_GET['NumeroDocumento']);
                
                $dtoCliente->setIdServicio($_GET['Servicio']);
                
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                echo json_encode($daoClienteCartera->SearchClientByDni($dtoCliente, $dtoCartera, $dtoUsuarioServicio));
                break;
            case 'SearchClientByNumeroCuenta':
                
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCuenta = new dto_cuenta ;
                
                $dtoCuenta->setNumeroCuenta($_GET['NumeroCuenta']);
                $dtoCliente->setIdServicio($_GET['Servicio']);
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                
                echo json_encode($daoClienteCartera->SearchClientByAccountNumber($dtoCliente, $dtoCartera, $dtoCuenta));
                
                break;
            case 'SearchClientByTelefono':
                
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoTelefono = new dto_telefono ;

                $dtoTelefono->setNumero($_GET['Telefono']);
                $dtoCliente->setIdServicio($_GET['Servicio']);
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                
                echo json_encode($daoClienteCartera->SearchClientByPhone( $dtoCliente, $dtoCartera, $dtoTelefono ));
                
                break;
            case 'ListCampania':/*piro*/
                
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['Servicio']);
                echo json_encode($daoCampania->queryAllByIdName($dtoServicio));
                break;

            case 'searchClienteCartera':/*piro*/
                $dtoCartera = new dto_cartera();
                $dtoClienteCartera = new dto_cliente_cartera();
                $dtoCartera->setId($_GET['idcartera']);
                $dtoClienteCartera->setId($_GET['codigoCliente']);
                echo json_encode($daoClienteCartera->searchClienteCartera($dtoCartera, $dtoClienteCartera));
                break;
            
            case 'SearchClientByCode2': //piro
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente->setCodigo($_GET['Codigo']);
                          
                $dtoCartera->setId($_GET['Cartera']);
                

                echo json_encode($daoClienteCartera->SearchClientByCode2($dtoCliente, $dtoCartera));
                break;
            
            case 'FillIdCuentaByCode':/*piro*/
                $dtoCartera = new dto_cartera();
                $dtoClienteCartera = new dto_cliente_cartera();
                $dtoCartera->setId($_GET['idcartera']);
                $dtoClienteCartera->setId($_GET['codigoCliente']);
                echo json_encode($daoClienteCartera->FillIdCuentaByCode($dtoCartera, $dtoClienteCartera));
                break;

            case 'ListCartera':/*piro*/
                $dtoCampania = new dto_campanias;
                $dtoCampania->setId($_GET['Campania']);
                echo json_encode($daoCartera->queryIdNombreActivos($dtoCampania));
                break;

            case 'ListDomicilio':/*piro*/
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setCodigoCliente($_GET['CodigoCliente']);
                echo json_encode($daoClienteCartera->ListDomicilio($dtoClienteCartera));
                break;
            case 'LoadDataCliente':
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setId($_GET['ClienteCartera']);
                /*                 * ******** */
                $dtoCliente = new dto_cliente;
                $dtoCliente->setIdServicio($_GET['Servicio']);
                /*                 * ******** */
                //echo json_encode($daoCliente->queryByIdClienteCartera($dtoClienteCartera));
                echo json_encode($daoCliente->queryByIdClienteCartera($dtoClienteCartera, $dtoCliente));
                break;
            case 'buscarUsuario':
                echo json_encode($daoCliente->buscarUsuario($_GET['usuario'],$_GET['idservicio']));
            break;                
            case 'ListarTipoGestion':
                echo json_encode($daoTipoGestion->queryIdName());
                break;
            case 'ListarCargaFinalAll':
                echo json_encode($daoCargaFinal->queryAllByIdName());
                break;
            case 'ListarCargaFinal':
                $idservicio = $_GET['idservicio'];

                //echo json_encode($daoCargaFinal->queryAllByIdName());

                $sql = " SELECT fin.idcarga_final , ( SELECT nombre FROM ca_carga_final WHERE idcarga_final = fin.idcarga_final  ) AS 'nombre'
						FROM ca_final fin INNER JOIN ca_final_servicio finser 
						ON finser.idfinal = fin.idfinal 
						WHERE finser.idservicio = " . $idservicio . " AND ISNULL(fin.idcarga_final) = 0
						AND fin.idclase_final = 1 GROUP BY fin.idcarga_final ";

                $data = $daoProcedure->executeQueryReturn($sql);

                echo json_encode($data);
                break;
            case 'ListarClaseFinal':
                echo json_encode($daoClaseFinal->queryAllByIdName());
                break;
            case 'ListarTipoFinalAll':
                echo json_encode($daoTipoFinal->queryAllByIdName());
                break;
            case 'ListarTipoFinal':
                //echo json_encode($daoTipoFinal->queryAllByIdName());

                $idservicio = $_GET['idservicio'];
                $idcarga_final = $_GET['idcarga_final'];

                $sql = " SELECT fin.idtipo_final , ( SELECT nombre FROM ca_tipo_final WHERE idtipo_final = fin.idtipo_final  ) AS 'nombre'
						FROM ca_final fin INNER JOIN ca_final_servicio finser 
						ON finser.idfinal = fin.idfinal 
						WHERE finser.idservicio = " . $idservicio . " AND ISNULL(fin.idtipo_final) = 0
						AND fin.idcarga_final = " . $idcarga_final . " AND fin.idclase_final = 1 GROUP BY fin.idtipo_final ";

                $data = $daoProcedure->executeQueryReturn($sql);

                echo json_encode($data);
                break;
            case 'ListarNivelAll':
                echo json_encode($daoNivel->queryAll());
                break;
            case 'ListarNivel':
                //echo json_encode($daoNivel->queryAll());

                $idservicio = $_GET['idservicio'];
                $idcarga_final = $_GET['idcarga_final'];
                $idtipo_final = $_GET['idtipo_final'];

                $sql = " SELECT fin.idnivel , ( SELECT nombre FROM ca_nivel WHERE idnivel = fin.idnivel  ) AS 'nombre'
						FROM ca_final fin INNER JOIN ca_final_servicio finser 
						ON finser.idfinal = fin.idfinal 
						WHERE finser.idservicio = " . $idservicio . " AND ISNULL(fin.idnivel) = 0
						AND fin.idcarga_final = " . $idcarga_final . " AND fin.idtipo_final = " . $idtipo_final . "  
						AND fin.idclase_final = 1 GROUP BY fin.idnivel ";

                $data = $daoProcedure->executeQueryReturn($sql);

                echo json_encode($data);
                break;
            case 'ListarFinalServicioDetalle':

                $idservicio = $_GET['idservicio'];
                $idcarga_final = $_GET['idcarga_final'];
                $idtipo_final = $_GET['idtipo_final'];
                $idnivel = $_GET['idnivel'];

                $sql = " SELECT fin.idfinal , fin.nombre AS 'nombre'
						FROM ca_final fin INNER JOIN ca_final_servicio finser 
						ON finser.idfinal = fin.idfinal 
						WHERE finser.idservicio = " . $idservicio . " 
						AND fin.idcarga_final = " . $idcarga_final . " 
						AND fin.idtipo_final = " . $idtipo_final . "  
						AND fin.idnivel = " . $idnivel . " 
						AND fin.idclase_final = 1 GROUP BY fin.idfinal ";

                $data = $daoProcedure->executeQueryReturn($sql);

                echo json_encode($data);

                break;
            case 'ListarFinalServicioAgendar':

                $dtoServicio = new dto_servicio;
                //$dtoFinal=new dto_final ;

                $dtoServicio->setId($_GET['Servicio']);
                //$dtoFinal->setIdTipoFinal($_GET['Tipo']);
                //$dtoFinal->setIdCargaFinal($_GET['Carga']);
                //$dtoFinal->setIdClaseFinal(3);
                //$dtoFinal->setIdNivel($_GET['Nivel']);
                //echo json_encode($daoFinalServicio->queryByServicio($dtoServicio,$dtoFinal));

                echo json_encode($daoFinalServicio->queryStateAgendaByServicio($dtoServicio));

                break;
            case 'ListarFinalServicioLlamada':
                $dtoServicio = new dto_servicio;
                $dtoFinal = new dto_final;

                $dtoServicio->setId($_GET['Servicio']);
                $dtoFinal->setIdTipoFinal($_GET['Tipo']);
                $dtoFinal->setIdCargaFinal($_GET['Carga']);
                $dtoFinal->setIdClaseFinal(1);
                $dtoFinal->setIdNivel($_GET['Nivel']);

                echo json_encode($daoFinalServicio->queryByServicio($dtoServicio, $dtoFinal));
                break;
            case 'ListarFinalServicioVisita':
                $dtoServicio = new dto_servicio;
                //$dtoFinal=new dto_final ;

                $dtoServicio->setId($_GET['Servicio']);
                //$dtoFinal->setIdTipoFinal($_GET['Tipo']);
//					$dtoFinal->setIdCargaFinal($_GET['Carga']);
//					$dtoFinal->setIdClaseFinal(2);
//					$dtoFinal->setIdNivel($_GET['Nivel']);
                //echo json_encode($daoFinalServicio->queryByServicio($dtoServicio,$dtoFinal));
                echo json_encode($daoFinalServicio->queryStateVisitaByServicio($dtoServicio));
                break;
            case 'ListarEstadoTransaccion':
                $dtoEstadoTransaccion = new dto_estado_transaccion();
                $dtoEstadoTransaccion->setIdServicio($_GET['Servicio']);
                $dtoEstadoTransaccion->setIdTipoTransaccion(1);
                $RowLlamada = $daoEstadoTransaccion->queryByService($dtoEstadoTransaccion);
                $dtoEstadoTransaccion->setIdTipoTransaccion(2);
                $RowVisita = $daoEstadoTransaccion->queryByService($dtoEstadoTransaccion);

                echo json_encode(array('llamada' => $RowLlamada, 'visita' => $RowVisita));
                break;
            case 'ListarPesoTransaccion':
                $dtoEstadoTransaccion = new dto_estado_Transaccion;
                $dtoEstadoTransaccion->setId($_GET['EstadoTransaccion']);
                echo json_encode($daoPesoTransaccion->queryPorIdEstadoTransaccion($dtoEstadoTransaccion));
                break;
            case 'LoadAlertasRecientes':

                $idservicio = $_GET['Servicio'];
                $idusuario_servicio = $_GET['UsuarioServicio'];
                $idusuario = $_GET['Usuario'];
                $fecha = $_GET['Fecha'];
                $hora = $_GET['Hora'];

                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente = new dto_cliente;
                $dtoUsuarioServicio->setId($idusuario_servicio);
                $dtoCliente->setIdServicio($idservicio);

                //$init = $daoAlerta->queryByUsuarioServicioTodayLastCampaign($dtoUsuarioServicio,$dtoCliente,$fecha,$hora);
                $init = $daoAlerta->queryAlertasRecientes($idservicio, $idusuario, $fecha, $hora);

                echo json_encode(array('init' => $init));

                break;
            case 'LoadInitAlertas':

                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
                $dtoCliente->setIdServicio($_GET['Servicio']);
                //$dtoCartera->setId($_GET['Cartera']);

                $fecha = $_GET['Fecha'];
                $hora = $_GET['Hora'];
                //echo json_encode($daoAlerta->queryByUsuarioServicioTodayLastCampaign($dtoUsuarioServicio,$dtoCartera,$dtoCliente));
                //$init = $daoAlerta->queryByUsuarioServicioTodayLastCampaign($dtoUsuarioServicio,$dtoCartera,$dtoCliente);
                $hoy = $daoAlerta->alertasHoy($dtoUsuarioServicio, $dtoCartera, $dtoCliente, $fecha, $hora);
                $ayer = $daoAlerta->alertasAyer($dtoUsuarioServicio, $dtoCartera, $dtoCliente, $fecha);
                $antigua = $daoAlerta->alertasAntiguas($dtoUsuarioServicio, $dtoCartera, $dtoCliente, $fecha);

                //echo json_encode(array('init'=>$init,'hoy'=>$hoy,'ayer'=>$ayer,'antigua'=>$antigua));
                echo json_encode(array('hoy' => $hoy, 'ayer' => $ayer, 'antigua' => $antigua));

                break;
            case 'actualizarRepresentanteLegal':
                $codigo_cliente=$_GET['codigo_cliente'];
                $asesor_comercial=$_GET['asesor_comercial'];
                $representante_legal=$_GET['representante_legal'];
                $responsable_pago=$_GET['responsable_pago'];
                $observacion=$_GET['observacion'];
                $idrepresentante_legal=$_GET['idrepresentante_legal'];
                $cartera=$_GET['cartera'];

                $sql="UPDATE ca_representante_legal
                    SET asesor_comercial='$asesor_comercial',
                        representante_legal='$representante_legal',
                        responsable_pago='$responsable_pago',
                        observacion='$observacion'
                    WHERE idrepresentante_legal=$idrepresentante_legal";

                $daoProcedure->executeQuery($sql);
                echo json_encode(array('msg'=>'Se actualizo correctamente','rpt'=>true));                
            break;  
            case 'nuevoRepresentanteLegal':
                $codigo_cliente=$_GET['codigo_cliente'];
                $asesor_comercial=$_GET['asesor_comercial'];
                $representante_legal=$_GET['representante_legal'];
                $responsable_pago=$_GET['responsable_pago'];
                $observacion=$_GET['observacion'];
                $cartera=$_GET['cartera'];


                $sql="INSERT ca_representante_legal(asesor_comercial,representante_legal,responsable_pago,observacion,codigo_cliente)
                        VALUES('$asesor_comercial','$representante_legal','$responsable_pago','$observacion','$codigo_cliente')";

                $daoProcedure->executeQuery($sql);

                echo json_encode(array('msg'=>'Se Guardo correctamente','rpt'=>true));
            break;  
            case 'deleteRepresentanteLegal':
                $idrepresentante_legal=$_GET['idrepresentante_legal'];
                $sql="UPDATE ca_representante_legal
                        SET estado=0
                        WHERE idrepresentante_legal=$idrepresentante_legal";
                $daoProcedure->executeQuery($sql);
                echo json_encode(array('msg'=>'Se actualizo correctamente','rpt'=>true));
            break;                         
            case 'jqgrid_facturas_digitales':
                /* if(!isset($_GET['Cartera'],$_GET['Servicio'])){
                  echo '{"page":0,"total":0,"records":"0","rows":[]}';
                  exit();
                  }
                  if( $_GET['Cartera']=='' || $_GET['Servicio']=='') {
                  echo '{"page":0,"total":0,"records":"0","rows":[]}';
                  exit();
                  } */

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $where = "";
                $param = array($_SESSION['cobrast']['idusuario_servicio']);
                //$param[':cartera'] = trim($_GET['Cartera']);
                //$param[':servicio'] = trim($_GET['Servicio']);

                /* if( isset($_GET['cli_codigo']) ) {
                  if( trim($_GET['cli_codigo'])!='' ) {
                  $where.=" AND cli.codigo = :codigo ";
                  $param[':codigo'] = trim($_GET['cli_codigo']);
                  }
                  }
                  if( isset($_GET['cli_nombre']) ) {
                  if( trim($_GET['cli_nombre'])!='' ) {
                  $where.=" AND CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) LIKE :nombre ";
                  $param[':nombre'] = '%'.trim($_GET['cli_nombre']).'%';
                  }
                  }
                  if( isset($_GET['cli_numero_documento']) ) {
                  if( trim($_GET['cli_numero_documento'])!='' ) {
                  $where.=" AND cli.numero_documento LIKE :numero_documento ";
                  $param[':numero_documento'] = '%'.trim($_GET['cli_numero_documento']).'%';
                  }
                  }
                  if( isset($_GET['cli_tipo_documento']) ) {
                  if( trim($_GET['cli_tipo_documento'])!='' ) {
                  $where.=" AND cli.tipo_documento = :tipo_documento ";
                  $param[':tipo_documento'] = trim($_GET['cli_tipo_documento']);
                  }
                  } */

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountFacturasDigitales($where, $param);

                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsFacturasDigitales($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idfactura_digital'], "cell" => array(
                            $data[$i]['idfactura_digital'],
                            $data[$i]['solicita'],
                            $data[$i]['fecha_vencimiento'],
                            $data[$i]['correo'],
                            $data[$i]['paterno'] . ' ' . $data[$i]['materno'] . ' ' . $data[$i]['nombre'],
                            $data[$i]['enviado'],
                            $data[$i]['ruta_absoluta']
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_busquedaManual':
                if (!isset($_GET['Cartera'], $_GET['UsuarioServicio'], $_GET['Metadata'], $_GET['Servicio'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['UsuarioServicio'] == '' || $_GET['Servicio'] == '' || count(json_decode($_GET['Metadata'], true)) == 0) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];
                $cartera = $_GET["Cartera"];
                $UsuarioServicio = $_GET["UsuarioServicio"];
                $servicio = $_GET['Servicio'];

                $countDireccion = 0;
                $countTelefono = 0;
                $where = "";
                $whereDireccion = "";
                $whereTelefono = "";

                $stmpSQL = "";
                $stmpCOUNT = "";

                $metadata = json_decode($_GET['Metadata'], true);

                for ($i = 0; $i < count($metadata); $i++) {
                    $data = explode("@", $metadata[$i]['metadata']);
                    if ($data[0] == 'ca_direccion') {
                        $whereDireccion = $whereDireccion . " AND " . $data[0] . "." . $data[1] . "='" . $data[2] . "' ";
                        $countDireccion++;
                    } else if ($data[0] == 'ca_telefono') {
                        $whereTelefono.=" AND " . $data[0] . "." . $data[1] . "='" . $data[2] . "' ";
                        $countTelefono++;
                    }
                    $where.=" AND " . $data[0] . "." . $data[1] . "='" . $data[2] . "' ";
                }

                if ($countDireccion > 0 && $countTelefono == 0) {
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente_cartera.idcliente,ca_cliente.codigo,
//							TRIM( CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) ) AS 'cliente',
//							IFNULL(ca_cliente.dni,'') AS 'dni',IFNULL(ca_cliente.ruc,'') AS 'ruc'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente INNER JOIN ca_referencia_cliente INNER JOIN ca_direccion
//							ON ca_direccion.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_direccion.idcliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_direccion.idcartera = $cartera
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_direccion.codigo_cliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_direccion.idcartera = $cartera AND ca_cliente.idservicio = $servicio 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpSQL = " SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_direccion.codigo_cliente 
							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
							AND ca_direccion.idcartera = $cartera AND ca_cliente.idservicio = $servicio ";

//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente INNER JOIN ca_referencia_cliente INNER JOIN ca_direccion
//							ON ca_direccion.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
//							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_direccion.idcliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado=1 
//							AND ca_direccion.idcartera = $cartera
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
//							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_direccion.codigo_cliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado=1 
//							AND ca_direccion.idcartera = $cartera AND ca_cliente.idservicio = $servicio 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpCOUNT = " SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
							FROM ca_direccion INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_direccion.codigo_cliente 
							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado=1 
							AND ca_direccion.idcartera = $cartera AND ca_cliente.idservicio = $servicio ";

                    $stmpSQL = $stmpSQL . $whereDireccion;
                    $stmpCOUNT = $stmpCOUNT . $whereDireccion;
                } else if ($countTelefono > 0 && $countDireccion == 0) {
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente_cartera.idcliente,ca_cliente.codigo,
//							TRIM( CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) ) AS 'cliente',
//							IFNULL(ca_cliente.dni,'') AS 'dni',IFNULL(ca_cliente.ruc,'') AS 'ruc'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente INNER JOIN ca_referencia_cliente INNER JOIN ca_telefono
//							ON ca_telefono.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_telefono.idcliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_telefono.idcartera = $cartera
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_telefono.idcartera = $cartera AND ca_cliente.idservicio = $servicio 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpSQL = " SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente 
							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
							AND ca_telefono.idcartera = $cartera AND ca_cliente.idservicio = $servicio  ";

//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente INNER JOIN ca_referencia_cliente INNER JOIN ca_telefono
//							ON ca_telefono.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
//							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_telefono.idcliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_telefono.idcartera = $cartera 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
//							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente 
//							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
//							AND ca_telefono.idcartera = $cartera AND ca_cliente.idservicio = $servicio
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpCOUNT = " SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT' 
							FROM ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente 
							WHERE ca_cliente_cartera.idcartera=$cartera AND ca_cliente_cartera.estado = 1 
							AND ca_telefono.idcartera = $cartera AND ca_cliente.idservicio = $servicio ";

                    $stmpSQL = $stmpSQL . $whereTelefono;
                    $stmpCOUNT = $stmpCOUNT . $whereTelefono;
                } else if ($countDireccion > 0 && $countTelefono > 0) {
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente_cartera.idcliente,ca_cliente.codigo,
//							TRIM( CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) ) AS 'cliente',
//							IFNULL(ca_cliente.dni,'') AS 'dni',IFNULL(ca_cliente.ruc,'') AS 'ruc'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente 
//							INNER JOIN ca_referencia_cliente INNER JOIN ca_direccion INNER JOIN ca_telefono
//							ON ca_telefono.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_direccion.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_telefono.idcliente AND ca_direccion.idcliente=ca_cliente.idcliente 
//							WHERE ca_cliente_cartera.idcampania=$cartera AND ca_cliente_cartera.estado=1 
//							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpSQL=" SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
//							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
//							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
//							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente AND ca_direccion.codigo_cliente=ca_cliente.codigo 
//							WHERE ca_cliente_cartera.idcampania=$cartera AND ca_cliente_cartera.estado=1 AND ca_cliente.idservicio = $servicio 
//							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpSQL = " SELECT DISTINCT ca_cliente_cartera.idcliente_cartera,ca_cliente.idcliente,ca_cliente.codigo,
							CONCAT_WS(' ',ca_cliente.nombre,ca_cliente.paterno,ca_cliente.materno) AS 'cliente',
							IFNULL(ca_cliente.numero_documento,'') AS 'numero_documento',IFNULL(ca_cliente.tipo_documento,'') AS 'tipo_documento'
							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente AND ca_direccion.codigo_cliente=ca_cliente.codigo 
							WHERE ca_cliente_cartera.idcampania=$cartera AND ca_cliente_cartera.estado=1 AND ca_cliente.idservicio = $servicio 
							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera  ";

//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
//							FROM ca_cartera INNER JOIN ca_cliente_cartera INNER JOIN ca_cliente 
//							INNER JOIN ca_referencia_cliente INNER JOIN ca_direccion INNER JOIN ca_telefono
//							ON ca_telefono.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_direccion.idreferencia_cliente=ca_referencia_cliente.idreferencia_cliente AND 
//							ca_cliente.idcliente=ca_cliente_cartera.idcliente AND ca_cliente_cartera.idcartera=ca_cartera.idcartera
//							WHERE ca_cartera.idcampania=$campania AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
//							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.idcliente=ca_cliente.idcliente AND ca_cliente.idcliente=ca_telefono.idcliente AND ca_direccion.idcliente=ca_cliente.idcliente 
//							WHERE ca_cliente_cartera.idcampania=$cartera 
//							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";
//						$stmpCOUNT=" SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
//							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
//							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente AND ca_direccion.codigo_cliente=ca_cliente.codigo 
//							WHERE ca_cliente_cartera.idcampania=$cartera AND ca_cliente.idservicio = $servicio 
//							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera 
//							AND ca_cliente_cartera.idusuario_servicio=$UsuarioServicio ";

                    $stmpCOUNT = " SELECT COUNT( DISTINCT ca_cliente_cartera.idcliente_cartera ) AS 'COUNT'
							FROM ca_direccion INNER JOIN ca_telefono INNER JOIN ca_cliente INNER JOIN ca_cliente_cartera 
							ON ca_cliente_cartera.codigo_cliente=ca_cliente.codigo AND ca_cliente.codigo=ca_telefono.codigo_cliente AND ca_direccion.codigo_cliente=ca_cliente.codigo 
							WHERE ca_cliente_cartera.idcampania=$cartera AND ca_cliente.idservicio = $servicio 
							AND ca_telefono.idcartera = $cartera AND ca_direccion.idcartera = $cartera  ";

                    $stmpSQL = $stmpSQL . $where;
                    $stmpCOUNT = $stmpCOUNT . $where;
                }

                if (!$sidx)
                    $sidx = 1;

                $row = $daoClienteCartera->executeSelectString($stmpCOUNT);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $order = " ORDER BY $sidx $sord LIMIT $start, $limit ";

                $stmpSQL = $stmpSQL . $order;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoClienteCartera->executeSelectString($stmpSQL);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['cliente'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>'
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_busquedaBase':

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCliente = new dto_cliente;
                $dtoTelefono = new dto_telefono;
                $dtoCuenta = new dto_cuenta;
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['UsuarioServicio']);

                //$row = array();

                if (isset($_GET['Telefono'])) {
                    $dtoTelefono->setNumero($_GET['Telefono']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByPhone($dtoCartera, $dtoUsuarioServicio, $dtoTelefono);
                } else if (isset($_GET['Nombre'])) {
                    $dtoCliente->setNombre($_GET['Nombre']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByName($dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['Codigo'])) {
                    $dtoCliente->setCodigo($_GET['Codigo']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByCodigo($dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['NumeroDocumento'])) {
                    $dtoCliente->setNumeroDocumento($_GET['NumeroDocumento']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByDni($dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['TipoDocumento'])) {
                    $dtoCliente->setTipoDocumento($_GET['TipoDocumento']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByRuc($dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['NumeroCuenta'])) {
                    $dtoCuenta->setNumeroCuenta($_GET['NumeroCuenta']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByNumberAccount($dtoCartera, $dtoUsuarioServicio, $dtoCuenta);
                } else if (isset($_GET['Idcliente_cartera'])) {/*jmore221113*/
                    $dtoCuenta->setIdClienteCartera($_GET['Idcliente_cartera']);
                    //$row = $daoJqgrid->JQGRIDCountSearchBaseByNumberAccount($dtoCartera, $dtoUsuarioServicio, $dtoCuenta);
                } else {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                
                $row = array( array('COUNT'=>30) );

                if (!$sidx)
                    $sidx = 1;


                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = array();
                if (isset($_GET['Telefono'])) {
                    $dtoTelefono->setNumero($_GET['Telefono']);
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByPhone($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoTelefono);
                } else if (isset($_GET['Nombre'])) {
                    $dtoCliente->setNombre($_GET['Nombre']);
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByName($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['Codigo'])) {
                    $dtoCliente->setCodigo($_GET['Codigo']);
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByCodigo($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['NumeroDocumento'])) {
                    $dtoCliente->setNumeroDocumento($_GET['NumeroDocumento']);
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByDni($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['TipoDocumento'])) {
                    $dtoCliente->setTipoDocumento($_GET['TipoDocumento']);
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByRuc($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCliente);
                } else if (isset($_GET['NumeroCuenta'])) {
                    $dtoCuenta->setNumeroCuenta(trim($_GET['NumeroCuenta']));
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByNumberAccount($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCuenta);
                } else if (isset($_GET['Idcliente_cartera'])) {
                    $dtoCuenta->setIdClienteCartera(trim($_GET['Idcliente_cartera']));
                    $data = $daoJqgrid->JQGRIDRowsSearchBaseByIdClienteCartera($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoCuenta);
                }  else {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }


                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                             $data[$i]['nombre_cartera'] ,
                             $data[$i]['contrato'] ,
                             $data[$i]['codigo'] ,
                             utf8_encode( $data[$i]['cliente'] ) ,
                             $data[$i]['numero_documento'] ,
                             $data[$i]['tipo_documento'] 
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_matrizBusqueda':
                if (!isset($_GET['Cartera'], $_GET['Servicio'], $_GET['Operador'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['Operador'] == '' || $_GET['Servicio'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['Operador']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteMatrizBusqueda($dtoCartera, $dtoUsuarioServicio);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteMatrizBusqueda($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['nombre'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>'
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_busquedaEstado':

                if (!isset($_GET['Cartera'], $_GET['Servicio'], $_GET['Operador'], $_GET['IdFinal'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['Operador'] == '' || $_GET['Servicio'] == '' || $_GET['IdFinal'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCartera = new dto_cartera;
                $dtoUsuarioServicio = new dto_usuario_servicio;
                $dtoTransaccion = new dto_transaccion;
                $dtoCartera->setId($_GET['Cartera']);
                $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                $dtoUsuarioServicio->setId($_GET['Operador']);
                $dtoTransaccion->setIdFinal($_GET['IdFinal']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteBusquedaEstado($dtoCartera, $dtoUsuarioServicio, $dtoTransaccion);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteBusquedaEstado($sidx, $sord, $start, $limit, $dtoCartera, $dtoUsuarioServicio, $dtoTransaccion);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['nombre'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>',
                            $data[$i]['llamadas']
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_busquedaGestionados':

                if (!isset($_GET['Cartera'], $_GET['Servicio'], $_GET['Operador'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['Operador'] == '' || $_GET['Servicio'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                /* $dtoCartera=new dto_cartera ;
                  $dtoUsuarioServicio=new dto_usuario_servicio ;
                  $dtoCartera->setId($_GET['Cartera']);
                  $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                  $dtoUsuarioServicio->setId($_GET['Operador']); */

                $where = "";
                $param = array();
                //$param[':cartera'] = trim($_GET['Cartera']);
                $param[':servicio'] = trim($_GET['Servicio']);
                $param[':operador'] = trim($_GET['Operador']);

                if (isset($_GET['cli_codigo'])) {
                    if (trim($_GET['cli_codigo']) != '') {
                        $where.=" AND cli.codigo = :codigo ";
                        $param[':codigo'] = trim($_GET['cli_codigo']);
                    }
                }
                if (isset($_GET['cli_nombre'])) {
                    if (trim($_GET['cli_nombre']) != '') {
                        $where.=" AND CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) LIKE :nombre ";
                        $param[':nombre'] = '%' . trim($_GET['cli_nombre']) . '%';
                    }
                }
                if (isset($_GET['cli_numero_documento'])) {
                    if (trim($_GET['cli_numero_documento']) != '') {
                        $where.=" AND cli.numero_documento LIKE :numero_documento ";
                        $param[':numero_documento'] = '%' . trim($_GET['cli_numero_documento']) . '%';
                    }
                }
                if (isset($_GET['cli_tipo_documento'])) {
                    if (trim($_GET['cli_tipo_documento']) != '') {
                        $where.=" AND cli.tipo_documento = :tipo_documento ";
                        $param[':tipo_documento'] = trim($_GET['cli_tipo_documento']);
                    }
                }

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteBusquedaGestionados($where, $param);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteBusquedaGestionados($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['nombre'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>',
                            $data[$i]['llamadas']
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_cliente_servicio':

                if (!isset($_GET['Cartera'], $_GET['Servicio'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['Servicio'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $where = "";
                $param = array();
                $param[':cartera'] = trim($_GET['Cartera']);
                $param[':servicio'] = trim($_GET['Servicio']);

                if (isset($_GET['cli_codigo'])) {
                    if (trim($_GET['cli_codigo']) != '') {
                        $where.=" AND cli.codigo = :codigo ";
                        $param[':codigo'] = trim($_GET['cli_codigo']);
                    }
                }
                if (isset($_GET['cli_nombre'])) {
                    if (trim($_GET['cli_nombre']) != '') {
                        $where.=" AND CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) LIKE :nombre ";
                        $param[':nombre'] = '%' . trim($_GET['cli_nombre']) . '%';
                    }
                }
                if (isset($_GET['cli_numero_documento'])) {
                    if (trim($_GET['cli_numero_documento']) != '') {
                        $where.=" AND cli.numero_documento LIKE :numero_documento ";
                        $param[':numero_documento'] = '%' . trim($_GET['cli_numero_documento']) . '%';
                    }
                }
                if (isset($_GET['cli_tipo_documento'])) {
                    if (trim($_GET['cli_tipo_documento']) != '') {
                        $where.=" AND cli.tipo_documento = :tipo_documento ";
                        $param[':tipo_documento'] = trim($_GET['cli_tipo_documento']);
                    }
                }

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteByService($where, $param);

                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteByService($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['nombre'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>'
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_busquedaSinGestion':

                if (!isset($_GET['Cartera'], $_GET['Servicio'], $_GET['Operador'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['Operador'] == '' || $_GET['Servicio'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $where = "";
                $param = array();
                //$param[':cartera'] = trim($_GET['Cartera']);
                $param[':servicio'] = trim($_GET['Servicio']);
                $param[':operador'] = trim($_GET['Operador']);

                if (isset($_GET['cli_codigo'])) {
                    if (trim($_GET['cli_codigo']) != '') {
                        $where.=" AND cli.codigo = :codigo ";
                        $param[':codigo'] = trim($_GET['cli_codigo']);
                    }
                }
                if (isset($_GET['cli_nombre'])) {
                    if (trim($_GET['cli_nombre']) != '') {
                        $where.=" AND CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) LIKE :nombre ";
                        $param[':nombre'] = '%' . trim($_GET['cli_nombre']) . '%';
                    }
                }
                if (isset($_GET['cli_numero_documento'])) {
                    if (trim($_GET['cli_numero_documento']) != '') {
                        $where.=" AND cli.numero_documento LIKE :numero_documento ";
                        $param[':numero_documento'] = '%' . trim($_GET['cli_numero_documento']) . '%';
                    }
                }
                if (isset($_GET['cli_tipo_documento'])) {
                    if (trim($_GET['cli_tipo_documento']) != '') {
                        $where.=" AND cli.tipo_documento = :tipo_documento ";
                        $param[':tipo_documento'] = trim($_GET['cli_tipo_documento']);
                    }
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                /* $dtoCartera=new dto_cartera ;
                  $dtoUsuarioServicio=new dto_usuario_servicio ;
                  $dtoCartera->setId($_GET['Cartera']);
                  $dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
                  $dtoUsuarioServicio->setId($_GET['Operador']); */

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteBusquedaSinGestion($where, $param);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteBusquedaSinGestion($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['codigo'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['nombre'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['numero_documento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_documento'] . '</pre>'
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_busquedaGlobal':

                //echo '{"page":0,"total":0,"records":"0","rows":[]}';
                $where = "";
                $param = array();
                $count = 0;
                if (isset($_GET['cli_nombre'])) {
                    if (trim($_GET['cli_nombre']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) LIKE :nombre ";
                        $param[':nombre'] = '%' . trim($_GET['cli_nombre']) . '%';
                    }
                }
                if (isset($_GET['cli_codigo'])) {
                    if (trim($_GET['cli_codigo']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" cli.codigo = :codigo ";
                        $param[':codigo'] = trim($_GET['cli_codigo']);
                    }
                }
                if (isset($_GET['cli_numero_documento'])) {
                    if (trim($_GET['cli_numero_documento']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" cli.numero_documento = :numero_documento ";
                        $param[':numero_documento'] = trim($_GET['cli_numero_documento']);
                    }
                }
                if (isset($_GET['cli_tipo_documento'])) {
                    if (trim($_GET['cli_tipo_documento']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" cli.tipo_documento LIKE :tipo_documento ";
                        $param[':tipo_documento'] = '%' . trim($_GET['cli_tipo_documento']) . '%';
                    }
                }
                if (isset($_GET['ser_nombre'])) {
                    if (trim($_GET['ser_nombre']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" ser.nombre = :servicio ";
                        $param[':servicio'] = trim($_GET['ser_nombre']);
                    }
                }
                if (isset($_GET['car_nombre_cartera'])) {
                    if (trim($_GET['car_nombre_cartera']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" car.nombre_cartera LIKE :cartera ";
                        $param[':cartera'] = '%' . trim($_GET['car_nombre_cartera']) . '%';
                    }
                }
                if (isset($_GET['tran_fecha'])) {
                    if (trim($_GET['tran_fecha']) != '') {
                        if ($count == 0) {
                            $where.=" WHERE ";
                        } else {
                            $where.=" AND ";
                        }
                        $count++;
                        $where.=" DATE(clicar.fecha_modificacion) = :fecha ";
                        $param[':fecha'] = trim($_GET['tran_fecha']);
                    }
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountClientesBusquedaGlobal($where, $param);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsClientesBusquedaGlobal($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            $data[$i]['codigo'],
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['cliente'] . '</pre>',
                            $data[$i]['numero_documento'],
                            $data[$i]['tipo_documento'],
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['servicio'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['cartera'] . '</pre>',
                            $data[$i]['ultima_llamada']
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_llamada':
                // if (!isset($_GET["ClienteCartera"])) {
                //     echo '{"page":0,"total":0,"records":"0","rows":[]}';
                //     exit();
                // } else if ($_GET['ClienteCartera'] == '') {
                //     echo '{"page":0,"total":0,"records":"0","rows":[]}';
                //     exit();
                // }

                // $page = $_GET["page"];
                // $limit = $_GET["rows"];
                // $sidx = $_GET["sidx"];
                // $sord = $_GET["sord"];

                // $dtoClienteCartera = new dto_cliente_cartera;
                // $dtoClienteCartera->setId($_GET['ClienteCartera']);

                // if (!$sidx)
                //     $sidx = 1;

                // $row = $daoJqgrid->JQGRIDCountAtencionClienteLlamada($dtoClienteCartera);

                // $count = $row[0]['COUNT'];
                // if ($count > 0) {
                //     $total_pages = ceil($count / $limit);
                // } else {
                //     $total_pages = 0;
                //     $limit = 0;
                // }

                // if ($page > $total_pages)
                //     $page = $total_pages;

                // $start = $page * $limit - $limit;

                // $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                // $data = $daoJqgrid->JQGRIDRowsAtencionClienteLlamada($sidx, $sord, $start, $limit, $dtoClienteCartera);

                // $dataRow = array();
                // for ($i = 0; $i < count($data); $i++) {
                //     array_push($dataRow, array("id" => $data[$i]['idgestion_cuenta'], "cell" => array(
                //             $data[$i]['numero_cuenta'],
                //             $data[$i]['telefono'],
                //             $data[$i]['fecha_llamada'],
                //             $data[$i]['hora_llamada'],
                //             //$data[$i]['eecc'],
                //             //$data[$i]['status_dir'],
                //             $data[$i]['estado'],
                //             $data[$i]['fecha_cp'],
                //             $data[$i]['monto_cp'],
                //             '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['observacion'] . '</pre>',
                //             '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['teleoperador'] . '</pre>'
                //         )
                //             )
                //     );
                // }
                // $response["rows"] = $dataRow;
                // echo json_encode($response);
                break;
            case 'jqgrid_historico':
                if (!isset($_GET["idcliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['idcliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCliente->setId($_GET['idcliente']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteHistorico($dtoCliente);

                $count = $row[0]['COUNT'];
                
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteHistorico($sidx, $sord, $start, $limit, $dtoCliente);

                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['IDLLAMADA'], "cell" => array(
                            // $data[$i]['cartera'],
                            // $data[$i]['numero_cuenta'],
                            // $data[$i]['telefono'],
                            // $data[$i]['fecha_llamada'],
                            // $data[$i]['hora_llamada'],
                            // $data[$i]['eecc'],
                            // $data[$i]['status_dir'],
                            // $data[$i]['estado'],
                            // $data[$i]['contacto'],
                            // $data[$i]['nombre_contacto'],
                            // $data[$i]['parentesco'],
                            // $data[$i]['motivo_no_pago'],
                            // $data[$i]['fecha_cp'],
                            // $data[$i]['monto_cp'],
                            // utf8_encode('<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['observacion'].'</pre>'),
                            // $data[$i]['teleoperador']

                            $data[$i]['IDLLAMADA'],
                            $data[$i]['CARTERA'],
                            $data[$i]['TD'],
                            $data[$i]['DOCUMENTO'],
                            $data[$i]['TELEFONO'],
                            $data[$i]['FECHA_LLAMADA'],
                            $data[$i]['HORA_LLAMADA'],
                            $data[$i]['ESTADO'],
                            $data[$i]['FECHA_CP'],
                            $data[$i]['MONEDA_CP'],
                            $data[$i]['MONTO_CP'],
                            $data[$i]['CONTACTO'],
                            $data[$i]['NOMBRE_CONTACTO'],
                            $data[$i]['PARENTESCO'],
                            $data[$i]['MOTIVO_NO_PAGO'],
                            $data[$i]['ESTADO_CLIENTE'],
                            $data[$i]['OBS'],
                            $data[$i]['USUARIO']


                        ))
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_direcciones':
//					if(!isset($_GET["Cliente"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cliente']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                //$dtoCliente->setId($_GET['Cliente']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteDireccion($dtoCliente, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $limit = 0;
                    $total_pages = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteDireccion($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['iddireccion'], "cell" => array(
                            $data[$i]['status'],
                            $data[$i]['idcuenta'],
                            $data[$i]['direccion'],
                            $data[$i]['referencia'],
                            $data[$i]['tipo_referencia'],
                            $data[$i]['origen'],
                            $data[$i]['ubigeo'],
                            $data[$i]['distrito'],
                            $data[$i]['provincia'],
                            $data[$i]['departamento'],
                            $data[$i]['codigo_postal'],
                            $data[$i]['observacion']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_cuenta':
                //if(!isset($_GET["ClienteCartera"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['ClienteCartera']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $p_interes = (float) $_SESSION['cobrast']['interes'];
                $p_descuento = (float) $_SESSION['cobrast']['descuento'];

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                //$dtoClienteCartera=new dto_cliente_cartera ;
                $dtoCartera = new dto_cartera;
                $dtoCliente = new dto_cliente;
                //$dtoClienteCartera->setId($_GET['ClienteCartera']);
                $dtoCartera->setId($_GET['Cartera']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);

                if (!$sidx)
                    $sidx = 1;

                //$row=$daoJqgrid->JQGRIDCountAtencionClienteCuenta($dtoClienteCartera,$dtoCartera);
                $row = $daoJqgrid->JQGRIDCountAtencionClienteCuenta($dtoCliente, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteCuenta($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    $total_interes = ( ( ( (float) $data[$i]['total_deuda'] ) + ( (int) $data[$i]['total_comision'] ) ) * $p_interes );
                    $total_descuento = ( (float) $data[$i]['total_deuda'] + ( ( (int) $data[$i]['total_comision'] ) * $p_descuento ) );
                    array_push($dataRow, array("id" => $data[$i]['idcuenta'], "cell" => array(
                            $data[$i]['numero_cuenta'],
                            $data[$i]['moneda'],
                            $data[$i]['total_deuda'],
                            $data[$i]['monto_pagado'],
                            $data[$i]['total_comision'],
                            $data[$i]['saldo'],
                            $total_interes,
                            $total_descuento,
                            $data[$i]['telefono']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_operaciones':
                //if(!isset($_GET["Cuenta"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cuenta']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["NumeroCuenta"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['NumeroCuenta'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                /*                 * ******** */
                if (!isset($_GET['Moneda'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Moneda'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                /*                 * ******** */
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCuenta = new dto_cuenta;
                $dtoCartera = new dto_cartera;
                //$dtoCuenta->setId($_GET['Cuenta']);
                /*                 * ****** */
                $dtoCuenta->setMoneda($_GET['Moneda']);
                /*                 * ****** */
                $dtoCuenta->setNumeroCuenta($_GET['NumeroCuenta']);
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteDetalleCuenta($dtoCuenta, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $limit = 0;
                    $total_pages = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteDetalleCuenta($sidx, $sord, $start, $limit, $dtoCuenta, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['iddetalle_cuenta'], "cell" => array(
                            $data[$i]['codigo_operacion'],
                            $data[$i]['moneda'],
                            $data[$i]['refinanciamiento'],
                            $data[$i]['numero_cuotas'],
                            $data[$i]['numero_cuotas_pagadas'],
                            $data[$i]['dias_mora'],
                            $data[$i]['tramo'],
                            $data[$i]['comision'],
                            $data[$i]['total_deuda'],
                            $data[$i]['total_deuda_soles'],
                            $data[$i]['total_deuda_dolares'],
                            $data[$i]['monto_mora'],
                            $data[$i]['monto_mora_soles'],
                            $data[$i]['monto_mora_dolares'],
                            $data[$i]['saldo_capital'],
                            $data[$i]['saldo_capital_soles'],
                            $data[$i]['saldo_capital_dolares'],
                            $data[$i]['fecha_asignacion']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_pagos':
                //if(!isset($_GET["DetalleCuenta"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['DetalleCuenta']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoOperacion"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoOperacion'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoDetalleCuenta = new dto_detalle_cuenta;
                $dtoClienteCartera = new dto_cliente_cartera;
                //$dtoDetalleCuenta->setId($_GET['DetalleCuenta']);
                $dtoDetalleCuenta->setCodigoOperacion($_GET['CodigoOperacion']);
                $dtoClienteCartera->setIdCartera($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClientePago($dtoDetalleCuenta, $dtoClienteCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $limit = 0;
                    $total_pages = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClientePago($sidx, $sord, $start, $limit, $dtoDetalleCuenta, $dtoClienteCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idpago'], "cell" => array(
                            $data[$i]['monto_pagado'],
                            $data[$i]['total_deuda'],
                            $data[$i]['monto_mora'],
                            $data[$i]['saldo_capital'],
                            $data[$i]['dias_mora'],
                            $data[$i]['moneda'],
                            $data[$i]['fecha_pago'],
                            $data[$i]['fecha_envio'],
                            $data[$i]['agencia']
                            )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_telefonos':
//					if(!isset($_GET["Cliente"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cliente']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                //$dtoCliente->setId($_GET['Cliente']);
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteTelefono($dtoCliente, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $limit = 0;
                    $total_pages = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteTelefono($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idtelefono'], "cell" => array(
                            $data[$i]['status'],
                            $data[$i]['numero'],
                            $data[$i]['codigo_cliente'],
                            $data[$i]['idcuenta'],
                            $data[$i]['idcliente_cartera'],
                            $data[$i]['anexo'],
                            $data[$i]['tipo_telefono'],
                            $data[$i]['tipo_referencia'],
                            $data[$i]['origen'],
                            $data[$i]['observacion']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_numero_telefono':
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                //$row = $daoJqgrid->JQGRIDCountAtencionClienteNumeroTelefono($dtoCliente, $dtoCartera);
                //$count = $row[0]['COUNT'];
                $count = 30;
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $limit = 0;
                    $total_pages = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteNumeroTelefono($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                /*COLORES*/
                for ($i = 0; $i < count($data); $i++) {
                    $numero = "";
                    if ($data[$i]['is_active'] == "1") {
                        $numero = '<label style="color:green;font-weight:bold;">' . $data[$i]['numero'] . '</label>';
                    } else {
                                $numero = '<label >' . $data[$i]['numero'] . '</label>';
                    }
                    $pref = explode(":",$data[$i]['prefijos']);
                    array_push($dataRow, array("id" => $data[$i]['idtelefono'], "cell" => array(
                            $numero,
                            $data[$i]['anexo'],
                            $pref[0],
                            $data[$i]['estado'],
                            $data[$i]['referencia'] ,
                            $pref[1],
                            $data[$i]['peso'],
                            $data[$i]['origen']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_campo_telefonos':
                //if(!isset($_GET["Cliente"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cliente']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                //$dtoCliente->setId($_GET['Cliente']);
                /*                 * ****** */
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                /*                 * ****** */
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountDigitacionTelefonos($dtoCliente, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsDigitacionTelefonos($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idtelefono'], "cell" => array(
                            $data[$i]['status'],
                            $data[$i]['numero'],
                            $data[$i]['anexo'],
                            $data[$i]['tipo_telefono'],
                            $data[$i]['referencia'],
                            $data[$i]['origen'],
                            $data[$i]['observacion']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_campo_direcciones':
                //if(!isset($_GET["Cliente"])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cliente']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
                if (!isset($_GET["CodigoCliente"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['CodigoCliente'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET["Cartera"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCliente = new dto_cliente;
                $dtoCartera = new dto_cartera;
                //$dtoCliente->setId($_GET['Cliente']);
                /*                 * ****** */
                $dtoCliente->setCodigo($_GET['CodigoCliente']);
                /*                 * ****** */
                $dtoCartera->setId($_GET['Cartera']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountDigitacionDirecciones($dtoCliente, $dtoCartera);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsDigitacionDirecciones($sidx, $sord, $start, $limit, $dtoCliente, $dtoCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['iddireccion'], "cell" => array(
                            $data[$i]['status'],
                            $data[$i]['idcuenta'],
                            $data[$i]['direccion'],
                            $data[$i]['referencia'],
                            $data[$i]['tipo_referencia'],
                            $data[$i]['origen'],
                            $data[$i]['ubigeo'],
                            $data[$i]['distrito'],
                            $data[$i]['provincia'],
                            $data[$i]['departamento'],
                            $data[$i]['observacion']
                            //'<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['observacion'] . '</pre>'
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_centro_pago':
                if (!isset($_GET["Servicio"])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (trim($_GET['Servicio']) == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $search = "";

                $param = array();
                $param[':servicio'] = $_GET['Servicio'];

                if (isset($_GET['agencia'])) {
                    if (trim($_GET['agencia']) != '') {
                        $search.=" AND agencia LIKE :agencia ";
                        $param[':agencia'] = '%' . trim($_GET['agencia']) . '%';
                    }
                }
                if (isset($_GET['tipo_canal'])) {
                    if (trim($_GET['tipo_canal']) != '') {
                        $search.=" AND tipo_canal LIKE :tipo_canal ";
                        $param[':tipo_canal'] = '%' . trim($_GET['tipo_canal']) . '%';
                    }
                }
                if (isset($_GET['direccion'])) {
                    if (trim($_GET['direccion']) != '') {
                        $search.=" AND direccion LIEK :direccion ";
                        $param[':direccion'] = '%' . trim($_GET['direccion']) . '%';
                    }
                }
                if (isset($_GET['zona'])) {
                    if (trim($_GET['zona']) != '') {
                        $search.=" AND zona LIKE :zona ";
                        $param[':zona'] = '%' . trim($_GET['zona']) . '%';
                    }
                }
                if (isset($_GET['horario'])) {
                    if (trim($_GET['horario']) != '') {
                        $search.=" AND horario LIKE :horario ";
                        $param[':horario'] = trim($_GET['horario']) . '%';
                    }
                }
                if (isset($_GET['departamento'])) {
                    if (trim($_GET['departamento']) != '') {
                        $search.=" AND departamento LIKE :departamento ";
                        $param['departamento'] = '%' . trim($_GET['departamento']) . '%';
                    }
                }
                if (isset($_GET['provincia'])) {
                    if (trim($_GET['provincia']) != '') {
                        $search.=" AND provincia LIKE :provincia ";
                        $param[':provincia'] = '%' . trim($_GET['provincia']) . '%';
                    }
                }
                if (isset($_GET['distrito'])) {
                    if (trim($_GET['distrito']) != '') {
                        $search.=" AND distrito LIKE :distrito ";
                        $param[':distrito'] = '%' . trim($_GET['distrito']) . '%';
                    }
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountCentroPago($param, $search);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsCentroPago($sidx, $sord, $start, $limit, $param, $search);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idcentro_pago'], "cell" => array(
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['agencia'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['tipo_canal'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['direccion'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['zona'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['horario'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['departamento'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['provincia'] . '</pre>',
                            '<pre style="white-space:normal;word-wrap: break-word;">' . $data[$i]['distrito'] . '</pre>'
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'jqgrid_agendados':
                if (!isset($_GET['Cartera'], $_GET['UsuarioServicio'], $_GET['FechaInicio'], $_GET['FechaFin'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if ($_GET['Cartera'] == '' || $_GET['UsuarioServicio'] == '' || $_GET['FechaInicio'] == '' || $_GET['FechaFin'] == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (!isset($_GET['Servicio'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (trim($_GET['Servicio']) == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $dtoCartera = new dto_cartera;
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoCliente = new dto_cliente;
                $dtoCartera->setId($_GET['Cartera']);
                $dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
                $dtoCliente->setIdServicio($_GET['Servicio']);

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountAtencionClienteAgendados($_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera, $dtoCliente);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDRowsAtencionClienteAgendados($sidx, $sord, $start, $limit, $_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera, $dtoCliente);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idclientecartera'], "cell" => array(
                            $data[$i]['cliente'],
                            $data[$i]['numero_documento'],
                            $data[$i]['tipo_documento'],
                            $data[$i]['tipo_gestion'],
                            $data[$i]['final'],
                            $data[$i]['fecha_cp'],
                            $data[$i]['monto_cp']
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            
            case 'jqgrid_gestion_comercial'://Piro 30-12-2014
                $page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];

					if(!$sidx)$sidx=1 ;
					
					$row=$daoGestionComercial->COUNT();

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoGestionComercial->queryGestionComercial($sidx,$sord,$start,$limit);
					$dataRow=array();
                                        
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idvisita'],"cell"=>array($data[$i]['codigocliente'],$data[$i]['numerocuenta'],$data[$i]['nombre'],$data[$i]['moneda'],$data[$i]['totaldeuda']
                                                        ,$data[$i]['territorio'],$data[$i]['oficina'],$data[$i]['ruc'],$data[$i]['direccion']
                                                        ,$data[$i]['fechavisita'],$data[$i]['horavisita'],$data[$i]['gironegocio'],$data[$i]['detallegironegocio']
                                                        ,$data[$i]['motivoatrasonegocio'],$data[$i]['detallemotivoatrasonegocio'],$data[$i]['afrotnarpagonegocio'],$data[$i]['detalleafrontarpagonegocio']
                                                        ,$data[$i]['cuestionacobranza'],$data[$i]['observacionespecialistanegocio'],$data[$i]['tieneexistencias'],$data[$i]['laborartesanal']
                                                        ,$data[$i]['localpropio'],$data[$i]['oficinaadministrativa'],$data[$i]['menorigualdiezpersonas'],$data[$i]['mayordiezpersonas']
                                                        ,$data[$i]['plantaindustrial'],$data[$i]['casanegocio'],$data[$i]['puertaacalle'],$data[$i]['actividadadiconal'],$data[$i]['nuevadireccion'],$data[$i]['numerovisita']
                                                        ,$data[$i]['nuevotelefono'],$data[$i]['tipocontacto'],$data[$i]['direccionvisita2'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
                
                break;
            case 'jqgrid_campo_visita':


                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                //$dtoCartera=new dto_cartera ;
                $dtoClienteCartera = new dto_cliente_cartera;
                $dtoClienteCartera->setId(trim($_GET['ClienteCartera']));

                $ini=isset($_GET['FechaInicio']) ? $_GET['FechaInicio'] : '';
                $fin=isset($_GET['FechaFin']) ? $_GET['FechaFin'] : '';
                $estado=isset($_GET['estado']) ? $_GET['estado'] : '';

                if (!$sidx)
                    $sidx = 1;

                //$row=$daoJqgrid->JQGRIDCountDigitacionVisita($_GET['FechaInicio'],$_GET['FechaFin'],$dtoCartera,$dtoClienteCartera);
                //$row=$daoJqgrid->JQGRIDCountDigitacionVisita($_GET['FechaInicio'],$_GET['FechaFin'],$dtoCartera,$dtoClienteCartera,$dtoServicio);
                $row = $daoJqgrid->JQGRIDCountDigitacionVisita($ini,$fin,$estado,$dtoClienteCartera);
                $count = $row[0]['COUNT'];
                
                // $count = 10;
                
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                //$data=$daoJqgrid->JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera);
                //$data=$daoJqgrid->JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera,$dtoServicio);
                $data = $daoJqgrid->JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $ini,$fin,$estado,$dtoClienteCartera);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    // $data_dir = explode("|", $data[$i]['direccion'] );
                        array_push($dataRow, array("id" => $data[$i]['IDVISITA'], "cell" => array(
                            $data[$i]['IDVISITA'],
                            $data[$i]['CARTERA'],
                            $data[$i]['TD'],
                            $data[$i]['DOCUMENTO'],
                            $data[$i]['DIRECCION'],
                            $data[$i]['FECHA_VISITA'],
                            $data[$i]['HORA_LLEGADA'],
                            $data[$i]['HORA_SALIDA'],
                            $data[$i]['DESCRIP_INMUEBLE'],
                            $data[$i]['ESTADO'],
                            $data[$i]['FECHA_CP'],
                            $data[$i]['MONEDA_CP'],
                            $data[$i]['MONTO_CP'],
                            $data[$i]['CONTACTO'],
                            $data[$i]['NOMBRE_CONTACTO'],
                            $data[$i]['PARENTESCO'],
                            $data[$i]['MOTIVO_NO_PAGO'],
                            $data[$i]['ESTADO_CLIENTE'],
                            $data[$i]['OBS'],
                            $data[$i]['USUARIO'],
                            $data[$i]['IDCUENTA']
                        )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'jqgrid_campo_visita3':
                //if(!isset($_GET['Cartera'],$_GET['UsuarioServicio'],$_GET['Servicio'],$_GET['FechaInicio'],$_GET['FechaFin'])){
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}
//					if( $_GET['Cartera']=='' || $_GET['UsuarioServicio']=='' || $_GET['Servicio']=='' || $_GET['FechaInicio']=='' || $_GET['FechaFin']=='' ) {
//						echo '{"page":0,"total":0,"records":"0","rows":[]}';
//						exit();
//					}

                if (!isset($_GET['codigoCliente'])) {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }
                if (trim($_GET['codigoCliente']) == '') {
                    echo '{"page":0,"total":0,"records":"0","rows":[]}';
                    exit();
                }

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                //$dtoCartera=new dto_cartera ;
                $dtoCliente = new dto_cliente;
                /*                 * ***** */
                //$dtoServicio=new dto_servicio ;
                /*                 * ***** */
                //$dtoCartera->setId($_GET['Cartera']);
                //$dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
                /*                 * ***** */
                //$dtoServicio->setId($_GET['Servicio']);
                /*                 * ***** */

                /*                 * ** */
                $dtoCliente->setCodigo(trim($_GET['codigoCliente']));
               
                /*                 * ** */

                if (!$sidx)
                    $sidx = 1;

                //$row=$daoJqgrid->JQGRIDCountDigitacionVisita($_GET['FechaInicio'],$_GET['FechaFin'],$dtoCartera,$dtoClienteCartera);
                //$row=$daoJqgrid->JQGRIDCountDigitacionVisita($_GET['FechaInicio'],$_GET['FechaFin'],$dtoCartera,$dtoClienteCartera,$dtoServicio);
                //$row = $daoJqgrid->JQGRIDCountDigitacionVisita($dtoClienteCartera);
                //$count = $row[0]['COUNT'];
                
                $count = 10;
                
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                //$data=$daoJqgrid->JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera);
                //$data=$daoJqgrid->JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $_GET['FechaInicio'], $_GET['FechaFin'], $dtoCartera, $dtoClienteCartera,$dtoServicio);
                $data = $daoJqgrid->JQGRIDRowsDigitacionVisita3($sidx, $sord, $start, $limit, $dtoCliente);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    //array_push($dataRow, array("id"=>$data[$i]['idtransaccion'],"cell"=>array(
//																									$data[$i]['cliente'],
//																									$data[$i]['numero_documento'],
//																									$data[$i]['tipo_documento'],
//																									$data[$i]['tipo_gestion'],
//																									$data[$i]['final'],
//																									$data[$i]['fecha_cp'],
//																									$data[$i]['monto_cp']
//																									)
//													)
//													
//									);
					$data_dir = explode("|", $data[$i]['direccion'] );
                    array_push($dataRow, array("id" => $data[$i]['idvisita'], "cell" => array(
                            $data[$i]['numero_cuenta'],
                            $data[$i]['fecha_visita'],
                            $data[$i]['fecha_recepcion'],
                            $data[$i]['estado'],
                            @$data_dir[0],
                            @$data_dir[1],
                            @$data_dir[2],
                            $data[$i]['notificador'],
                            $data[$i]['fecha_cp'],
                            $data[$i]['monto_cp'],
                            $data[$i]['contacto'],
                            $data[$i]['nombre_contacto'],
                            $data[$i]['hora_visita'],
                            $data[$i]['hora_salida'],
                            $data[$i]['observacion'],
                            $data[$i]['idcuenta']/*jmore201208*/
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'ListarSemana_opcion':
                $semana=$_GET['idcartera'];
                $daoJqgrid->ListarSemana_opcion($semana);
                break;
            //MANTTELF
            case 'gestion_telefono':

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $codigo_cliente = $_GET["codigo_cliente"]; 

                $where = "";
                $param = array();

                if (isset($_GET['numero'])) {
                    if (trim($_GET['numero']) != '') {
                        $where.=" AND numero LIKE  :numero ";
                        $param[':numero'] = '%'.trim($_GET['numero']).'%';
                    }
                }

                if (isset($_GET['anexo'])) {
                    if (trim($_GET['anexo']) != '') {
                        $where.=" AND anexo LIKE  :anexo ";
                        $param[':anexo'] = '%'.trim($_GET['anexo']).'%';
                    }
                }

                if (isset($_GET['tipo'])) {
                    if (trim($_GET['tipo']) != '') {
                        $where.=" AND tel.idtipo_telefono LIKE  :tipo ";
                        $param[':tipo'] = '%'.trim($_GET['tipo']).'%';
                    }
                }

                if (isset($_GET['referencia'])) {
                    if (trim($_GET['referencia']) != '') {
                        $where.=" AND referencia LIKE  :referencia ";
                        $param[':referencia'] = '%'.trim($_GET['referencia']).'%';
                    }
                }

                if (isset($_GET['estado'])) {
                    if (trim($_GET['estado']) != '') {
                        $where.=" AND IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') LIKE  :estado ";
                        $param[':estado'] = '%'.trim($_GET['estado']).'%';
                    }
                }

                if (isset($_GET['prefijos'])) {
                    if (trim($_GET['prefijos']) != '') {
                        $where.=" AND IFNULL(( SELECT CONCAT_WS(':',nombre,CONCAT_WS('-',lb_prefijo,lb_prefijo2,lb_prefijo3)) FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),':') LIKE  :prefijos ";
                        $param[':prefijos'] = '%'.trim($_GET['prefijos']).'%';
                    }
                }

                if (isset($_GET['origen'])) {
                    if (trim($_GET['origen']) != '') {
                        $where.=" AND org.nombre LIKE  :origen ";
                        $param[':origen'] = '%'.trim($_GET['origen']).'%';
                    }
                }

                if (isset($_GET['observacion'])) {
                    if (trim($_GET['observacion']) != '') {
                        $where.=" AND observacion LIKE  :observacion ";
                        $param[':observacion'] = '%'.trim($_GET['observacion']).'%';
                    }
                }

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCOUNTGestionTelefonos($codigo_cliente,$where, $param);
                $count = $row[0]['COUNT'];
                //$count = 4;
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDGestionTelefonos($sidx, $sord, $start, $limit,$codigo_cliente,$where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['idtelefono'], "cell" => array(
                            $data[$i]['idtelefono'],
                            $data[$i]['numero'],
                            $data[$i]['anexo'],
                            $data[$i]['tipo'],
                            $data[$i]['is_new'],
                            $data[$i]['is_campo'],
                            $data[$i]['is_carga'],
                            $data[$i]['referencia'],
                            $data[$i]['estado'],
                            $data[$i]['prefijos'],
                            $data[$i]['peso'],
                            $data[$i]['origen'],                            
                            preg_replace('[\n|\r|\n\r]', '', $data[$i]['observacion'])
                        )
                            )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;

            case 'Mant_Telf':
                    if($_GET['oper']=='add'){

                        $idcliente_cartera=$_GET['idcliente_cartera'];
                        $codigo_cliente=$_GET['codigo_cliente'];
                        $cartera=$_GET['cartera'];
                        $numero=$_GET['numero'];
                        $anexo=$_GET['anexo'];
                        $tipo=$_GET['tipo'];
                        $referencia=$_GET['referencia'];
                        $prefijos=$_GET['prefijos'];
                        $origen=$_GET['origen'];
                        $observacion=$_GET['observacion'];
                        $idusuario_servicio=$_GET['idusuario_servicio'];

                        $daoJqgrid->INSERT_Telf($idcliente_cartera,$codigo_cliente,$cartera,$numero,$anexo,$tipo[0],$referencia[0],$prefijos[0],$origen[0],$observacion,$idusuario_servicio);


                    }else if($_GET['oper']=='edit'){

                        $id=$_GET['id'];
                        $numero=$_GET['numero'];
                        $anexo=$_GET['anexo'];
                        $tipo=$_GET['tipo'];
                        $referencia=$_GET['referencia'];
                        $prefijos=$_GET['prefijos'];
                        $origen=$_GET['origen'];
                        $observacion=$_GET['observacion'];

                        $daoJqgrid->UPDATE_Telf($id,$numero,$anexo,$tipo[0],$referencia[0],$prefijos[0],$origen[0],$observacion);

                    }else if($_GET['oper']=='del'){

                        $id=$_GET['id'];

                        $daoJqgrid->DELETE_Telf($id);

                    }
                break;
            case 'List_number_exist':
                    $numero=$_GET['numero'];
                    $daoJqgrid->List_number_exist($numero);
                break;
            //MANTTELF

            // CAMBIO 20-06-2016
            case 'gestion_direccion_opcion':

                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $codigo_cliente = $_GET["codigo_cliente"]; 

                $where = "";
                $param = array();

                

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCOUNTGestionDireccion_opcion($codigo_cliente,$where, $param);
                $count = $row[0]['COUNT'];
                //$count = 4;
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRIDGestionDireccion_opcion($sidx, $sord, $start, $limit,$codigo_cliente,$where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['iddireccion'], "cell" => array(
                                $data[$i]['iddireccion'],
                                $data[$i]['ubigeo'],
                                $data[$i]['departamento'],
                                $data[$i]['provincia'],
                                $data[$i]['distrito'],
                                $data[$i]['direccion'],
                                $data[$i]['idorigen'],
                                $data[$i]['origen_dir'],
                                $data[$i]['idtipo_referencia'],
                                $data[$i]['referencia_dir']
                            )
                        )
                    );
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;
            case 'Mant_Direct_Opcion':
                    if($_GET['oper']=='add'){


                        $idcliente_cartera=$_GET['idcliente_cartera'];
                        $codigo_cliente=$_GET['codigo_cliente'];
                        $cartera=$_GET['cartera'];
                        $idusuario_servicio=$_GET['idusuario_servicio'];
                        $referencia=$_GET['referencia_dir'];
                        $origen=$_GET['origen_dir'];
                        $departamento=$_GET['departamento'];
                        $direccion=$_GET['direccion'];
                        $distrito=$_GET['distrito'];
                        $provincia=$_GET['provincia'];

                        $daoJqgrid->INSERT_Direct_Opcion($idcliente_cartera,$codigo_cliente,$cartera,$referencia[0],$origen[0],$idusuario_servicio,$departamento,$direccion,$distrito,$provincia);


                    }else if($_GET['oper']=='edit'){

                        $id=$_GET['id'];
                        $idcliente_cartera=$_GET['idcliente_cartera'];
                        $codigo_cliente=$_GET['codigo_cliente'];
                        $cartera=$_GET['cartera'];
                        $idusuario_servicio=$_GET['idusuario_servicio'];
                        $referencia=$_GET['referencia_dir'];
                        $origen=$_GET['origen_dir'];
                        $departamento=$_GET['departamento'];
                        $direccion=$_GET['direccion'];
                        $distrito=$_GET['distrito'];
                        $provincia=$_GET['provincia'];

                        $daoJqgrid->UPDATE_Direct_Opcion($id,$idcliente_cartera,$codigo_cliente,$cartera,$referencia[0],$origen[0],$idusuario_servicio,$departamento,$direccion,$distrito,$provincia);

                    }else if($_GET['oper']=='del'){

                        $id=$_GET['id'];

                        $daoJqgrid->DELETE_Direct_Opcion($id);

                    }
                break;
            // CAMBIO 20-06-2016
            case 'Listar_Cartera_Opcion':
                $daoJqgrid->Listar_Cartera_Opcion();
                break;
            case 'List_Telf_cobranzas_andina':
                    $page = $_GET["page"];
                    $limit = $_GET["rows"];
                    $sidx = $_GET["sidx"];
                    $sord = $_GET["sord"];

                    $codigo_cliente = $_GET["codigo_cliente"]; 

                    $where = "";
                    $param = array();

                    

                    if (!$sidx)
                        $sidx = 1;

                    $row = $daoJqgrid->JQGRIDCOUNTList_Telf_cobranzas_andina($codigo_cliente);
                    $count = $row[0]['COUNT'];
                    //$count = 4;
                    if ($count > 0) {
                        $total_pages = ceil($count / $limit);
                    } else {
                        $total_pages = 0;
                        $limit = 0;
                    }

                    if ($page > $total_pages)
                        $page = $total_pages;

                    $start = $page * $limit - $limit;

                    $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                    $data = $daoJqgrid->JQGRIDList_Telf_cobranzas_andina($sidx, $sord, $start, $limit,$codigo_cliente);
                    $dataRow = array();
                    for ($i = 0; $i < count($data); $i++) {
                        array_push($dataRow, array("id" => $data[$i]['idtelefono'], "cell" => array(
                                    $data[$i]['idtelefono'],
                                    $data[$i]['numero'],
                                    $data[$i]['anexo'],
                                    $data[$i]['peso'],
                                    $data[$i]['tipo'],
                                    $data[$i]['is_new'],
                                    $data[$i]['is_campo'],
                                    $data[$i]['is_carga'],
                                    $data[$i]['referencia'],
                                    $data[$i]['estado'],
                                    $data[$i]['prefijos'],
                                    $data[$i]['origen'],
                                    $data[$i]['condicion'],
                                    $data[$i]['state'],
                                    $data[$i]['status'],
                                    $data[$i]['observacion']
                                )
                            )
                        );
                    }
                    $response["rows"] = $dataRow;
                    echo json_encode($response);
                break;
            case 'si_elnumero_telf_existe':
                $numero=$_GET['numero'];
                $daoJqgrid->si_elnumero_telf_existe($numero);

                break;
            case 'save_telf_cobranza_andina':

                $numero=$_GET['numero'];
                $anexo=$_GET['anexo'];
                $tipo=$_GET['tipo'];
                $referencia=$_GET['referencia'];
                $linea=$_GET['linea'];
                $origen=$_GET['origen'];
                $condi=$_GET['condi'];
                $obs=$_GET['obs'];
                $idcliente_cartera=$_GET['idcliente_cartera'];
                $codigo_cliente=$_GET['codigo_cliente'];
                $idcartera=$_GET['idcartera'];
                $usuario_creacion=$_GET['usuario_creacion'];

                $daoJqgrid->save_telf_cobranza_andina($numero,$anexo,$tipo,$referencia,$linea,$origen,$condi,$obs,$idcliente_cartera,$codigo_cliente,$idcartera,$usuario_creacion);
                break;
            case 'List_Update_Telf_Andina':
                $idtelefono=$_GET['idtelefono'];
                $daoJqgrid->List_Update_Telf_Andina($idtelefono);
                break;
            case 'update_telf_andina':
                
                $idtelefono=$_GET['idtelefono'];
                $numero=$_GET['numero'];
                $anexo=$_GET['anexo'];
                $tipo=$_GET['tipo'];
                $referencia=$_GET['referencia'];
                $linea=$_GET['linea'];
                $origen=$_GET['origen'];
                $obs=$_GET['obs'];
                $state=$_GET['state'];
                $status=$_GET['status'];
                $condi=$_GET['condi'];

                $daoJqgrid->update_telf_andina($idtelefono,$numero,$anexo,$tipo,$referencia,$linea,$origen,$obs,$state,$status,$condi);

                break;

            case 'eliminar_telf_andina':
                $idtelefono=$_GET['idTelefono'];
                $daoJqgrid->eliminar_telf_andina($idtelefono);
                break;
            case 'lista_direccion_cobranzas':
                    $page = $_GET["page"];
                    $limit = $_GET["rows"];
                    $sidx = $_GET["sidx"];
                    $sord = $_GET["sord"];

                    $codigo_cliente = $_GET["codigo_cliente"]; 

                    $where = "";
                    $param = array();

                    

                    if (!$sidx)
                        $sidx = 1;

                    $row = $daoJqgrid->JQGRIDCOUNTlista_direccion_cobranzas($codigo_cliente);
                    $count = $row[0]['COUNT'];
                    //$count = 4;
                    if ($count > 0) {
                        $total_pages = ceil($count / $limit);
                    } else {
                        $total_pages = 0;
                        $limit = 0;
                    }

                    if ($page > $total_pages)
                        $page = $total_pages;

                    $start = $page * $limit - $limit;

                    $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                    $data = $daoJqgrid->JQGRIDlista_direccion_cobranzas($sidx, $sord, $start, $limit,$codigo_cliente);
                    $dataRow = array();
                    for ($i = 0; $i < count($data); $i++) {
                        array_push($dataRow, array("id" => $data[$i]['iddireccion'], "cell" => array(
                                    $data[$i]['iddireccion'],
                                    $data[$i]['direccion'],
                                    $data[$i]['ubigeo'],
                                    $data[$i]['departamento'],
                                    $data[$i]['provincia'],
                                    $data[$i]['distrito'],
                                    $data[$i]['region'],
                                    $data[$i]['zona'],
                                    $data[$i]['codigo_postal'],
                                    $data[$i]['numero'],
                                    $data[$i]['calle'],
                                    $data[$i]['referencia'],
                                    $data[$i]['observacion'],
                                    $data[$i]['origen'],
                                    $data[$i]['tipo_referencia'],
                                    $data[$i]['fecha_creacion'],
                                    $data[$i]['usuario_creacion'],
                                    $data[$i]['idcartera'],
                                    $data[$i]['codigo_cliente'],
                                    $data[$i]['is_new'],
                                    $data[$i]['estado'],
                                    $data[$i]['idcliente_cartera'],
                                    $data[$i]['condicion']
                                )
                            )
                        );
                    }
                    $response["rows"] = $dataRow;
                    echo json_encode($response);
                break;
            case 'insertar_nueva_direccion_andina':
                    $direccion=$_GET['direccion'];
                    $departamento=$_GET['departamento'];
                    $provincia=$_GET['provincia'];
                    $distrito=$_GET['distrito'];
                    $region=$_GET['region'];
                    $zona=$_GET['zona'];
                    $codigo_postal=$_GET['codigo_postal'];
                    $numero=$_GET['numero'];
                    $calle=$_GET['calle'];
                    $txtref=$_GET['txtref'];
                    $referencia=$_GET['referencia'];
                    $origen=$_GET['origen'];
                    $condicion=$_GET['condicion'];
                    $observacion=$_GET['observacion'];
                    $idcliente_cartera=$_GET['idcliente_cartera'];
                    $codigo_cliente=$_GET['codigo_cliente'];
                    $cartera=$_GET['idcartera'];
                    $idusuario_servicio=$_GET['idusuario_servicio'];

                    $daoJqgrid->insertar_nueva_direccion_andina($direccion,$departamento,$provincia,$distrito,$region,$zona,$codigo_postal,$numero,$calle,$txtref,$referencia,$origen,$condicion,$observacion,$idcliente_cartera,$codigo_cliente,$cartera,$idusuario_servicio);
                break;
            case 'modificar_direccion_andina':

                $iddireccion=$_GET['iddireccion'];
                $direccion=$_GET['direccion'];
                $departamento=$_GET['departamento'];
                $provincia=$_GET['provincia'];
                $distrito=$_GET['distrito'];
                $region=$_GET['region'];
                $zona=$_GET['zona'];
                $codigo_postal=$_GET['codigo_postal'];
                $numero=$_GET['numero'];
                $calle=$_GET['calle'];
                $referencia=$_GET['referencia'];
                $tipo_referencia=$_GET['tipo_referencia'];
                $origen=$_GET['origen'];
                $condicion=$_GET['condicion'];
                $estado=$_GET['estado'];
                $observacion=$_GET['observacion'];

                $daoJqgrid->modificar_direccion_andina($iddireccion,$direccion,$departamento,$provincia,$distrito,$region,$zona,$codigo_postal,$numero,$calle,$referencia,$tipo_referencia,$origen,$condicion,$estado,$observacion);
                break;
            case 'eliminar_direccion_andina':
                $iddireccion=$_GET['iddireccion'];
                $daoJqgrid->eliminar_direccion_andina($iddireccion);
                break;
            case 'List_Correo_cobranzas_andina':
                    $page = $_GET["page"];
                    $limit = $_GET["rows"];
                    $sidx = $_GET["sidx"];
                    $sord = $_GET["sord"];

                    $idcliente = $_GET["idcliente"]; 

                    $where = "";
                    $param = array();

                    

                    if (!$sidx)
                        $sidx = 1;

                    $row = $daoJqgrid->JQGRIDCOUNTList_Direccion_cobranzas_andina($idcliente);
                    $count = $row[0]['COUNT'];
                    //$count = 4;
                    if ($count > 0) {
                        $total_pages = ceil($count / $limit);
                    } else {
                        $total_pages = 0;
                        $limit = 0;
                    }

                    if ($page > $total_pages)
                        $page = $total_pages;

                    $start = $page * $limit - $limit;

                    $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                    $data = $daoJqgrid->JQGRIDList_Direccion_cobranzas_andina($sidx, $sord, $start, $limit,$idcliente);
                    $dataRow = array();
                    for ($i = 0; $i < count($data); $i++) {
                        array_push($dataRow, array("id" => $data[$i]['idcorreo'], "cell" => array(
                                    $data[$i]['idcorreo'],
                                    $data[$i]['correo'],
                                    $data[$i]['observacion'],
                                    $data[$i]['estado'],
                                    $data[$i]['usuario_creacion'],
                                    $data[$i]['fecha_creacion'],
                                    $data[$i]['idcliente']
                                )
                            )
                        );
                    }
                    $response["rows"] = $dataRow;
                    echo json_encode($response);
                break;
            case 'save_mail_andina':
                $mail=$_GET['mail'];
                $obs=$_GET['obs'];
                $idusuario_servicio=$_GET['idusuario_servicio'];
                $idcliente=$_GET['idcliente'];
                $daoJqgrid->save_mail_andina($mail,$obs,$idusuario_servicio,$idcliente);
                break;
            case 'UPDATE_Correo':
                $idcorreo=$_GET['idcorreo'];
                $correo=$_GET['correo'];
                $observacion=$_GET['observacion'];
                $usuario_creacion = $_GET['usuario_creacion'];
                $estado = $_GET['estado'];
                $daoJqgrid->UPDATE_Correo($correo,$observacion,$idcorreo,$usuario_creacion,$estado);
                break;
            case 'eliminar_mail_andina':
                $idcorreo=$_GET['idcorreo'];
                $daoJqgrid->eliminar_mail_andina($idcorreo);
                break;
            case 'Buscar_Cliente':
                    $page = $_GET["page"];
                    $limit = $_GET["rows"];
                    $sidx = $_GET["sidx"];
                    $sord = $_GET["sord"];

                    $idservicio=$_GET["idservicio"];
                    $codigo_cliente=$_GET["codigo_cliente"];
                    $cliente=$_GET["cliente"];
                    $td=$_GET["td"];
                    $documento=$_GET["doc"];

                    if (!$sidx)
                        $sidx = 1;

                    $row = $daoJqgrid->JQGRIDCOUNT_Buscar_Cliente($idservicio,$codigo_cliente,$cliente,$td,$documento);
                    $count = $row[0]['COUNT'];
                    //$count = 4;
                    if ($count > 0) {
                        $total_pages = ceil($count / $limit);
                    } else {
                        $total_pages = 0;
                        $limit = 0;
                    }

                    if ($page > $total_pages)
                        $page = $total_pages;

                    $start = $page * $limit - $limit;

                    $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                    $data = $daoJqgrid->JQGRID_Buscar_Cliente($sidx,$sord,$start,$limit,$idservicio,$codigo_cliente,$cliente,$td,$documento);
                    $dataRow = array();
                    for($i = 0; $i < count($data); $i++){
                        array_push($dataRow, array("id" => $data[$i]['idcliente_cartera'], "cell" => array(
                            $data[$i]['idcliente_cartera'],
                            $data[$i]['idcliente'],
                            $data[$i]['idcartera'],
                            $data[$i]['nombre_cartera'],
                            $data[$i]['codigo'],
                            $data[$i]['cliente'],
                            $data[$i]['numero_documento'],
                            $data[$i]['tipo_documento']
                        )));
                    }
                    $response["rows"] = $dataRow;
                    echo json_encode($response);
                break;
            case 'consultar_datos_cliente':
                    $idservicio=$_GET['idservicio'];
                    $idcartera=$_GET['idcartera'];
                    $codigo_cliente=$_GET['codigo_cliente'];
                    $idcliente_cartera=$_GET['idcliente_cartera'];
                    $daoJqgrid->consultar_datos_cliente($idservicio,$idcartera,$codigo_cliente,$idcliente_cartera);
                break;
            case 'Listar_Contactos':
                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                $idcliente=$_GET["idcliente"];

                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCOUNT_Listar_Contactos($idcliente);
                $count = $row[0]['COUNT'];
                //$count = 4;
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
                    $limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $daoJqgrid->JQGRID_Listar_Contactos($sidx,$sord,$start,$limit,$idcliente);
                $dataRow = array();
                for($i = 0; $i < count($data); $i++){
                    array_push($dataRow, array("id" => $data[$i]['idpersona'], "cell" => array(
                        $data[$i]['idpersona'],
                        $data[$i]['razon_social'],
                        $data[$i]['nombre'],
                        $data[$i]['paterno'],
                        $data[$i]['materno'],
                        $data[$i]['tipo_documento'],
                        $data[$i]['numero_documento'],
                        $data[$i]['estado'],
                        $data[$i]['idcliente']
                    )));
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'Mant_contactos':
                    if($_GET['oper']=='add'){
                        $daoJqgrid->Mant_ADDContactos($_GET['razon_social'],$_GET['nombre'],$_GET['paterno'],$_GET['materno'],$_GET['tipo_documento'],$_GET['numero_documento'],$_GET['idcliente']);
                    }else if($_GET['oper']=='edit'){
                        $daoJqgrid->Mant_EDITContactos($_GET['id'],$_GET['razon_social'],$_GET['nombre'],$_GET['paterno'],$_GET['materno'],$_GET['tipo_documento'],$_GET['numero_documento']);
                    }else if($_GET['oper']=='del'){
                        $daoJqgrid->Mant_DELETEContactos($_GET['id']);
                    }
                break;
            case 'Listar_Contactos_telf':
                $daoJqgrid->Listar_Contactos_telf($_GET['idpersona']);
                break;
            case 'Listar_Contactos_mail':
                $daoJqgrid->Listar_Contactos_mail($_GET['idpersona']);
                break;
            default:
                echo json_encode(array('rst' => true, 'msg' => 'Accion no encontrada'));
                
        endswitch;
    }

}

?>
