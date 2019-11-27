<?php

/**
 * Description of servletCargaCatera
 *
 */
class servletCargaCatera extends CommandController {

    public function doPost() {
        $daoCargaCartera = DAOFactory::getDAOCargaCartera('maria');
        $daoDetalleCuenta = DAOFactory::getDAODetalleCuenta('maria');
        $daoCabecerasCartera = DAOFactory::getCabecerasCarteraDAO('maria');
        $daoTelefono = DAOFactory::getDAOTelefono('maria');
        switch ($_POST["action"]) {
            case 'NormalizarTelefono2':
                $daoCargaCartera->NormalizarTelefono2($_POST);
            break;
            case 'updateMontosPagado':
                $daoCargaCartera->updateMontosPagado($_POST);
            break;
            case 'verificarArchivoPlanoCovinoc' :
                $daoCargaCartera->verificarArchivoPlanoCovinoc($_POST, $_FILES);
            break;
            case 'verificarArchivoPlanoPagoSaga' : 
                $daoCargaCartera->verificarArchivoPlanoPagoSaga($_POST, $_FILES);
            break;
            case 'CruceTelefonos':
                
                $servicio = $_POST['servicio'];
                $usuario_creacion = $_POST['usuario_creacion'];
                $cartera = $_POST['cartera'];
                $carteras_fl = $_POST['carteras_fl'];
                $tipo = $_POST['tipo'];
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                
                echo ( $daoTelefono->CruceTelefono( $tipo, $cartera, $fecha_inicio, $fecha_fin, $carteras_fl, $usuario_creacion ) ) ? json_encode(array('rst' => true, 'msg' => 'Telefonos cruzados correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al cruzar telefonos'));
                
            break;
            case 'update_fec_venc':
                
                echo json_encode( $daoDetalleCuenta->updateFecVenc( $_POST['idcartera'], $_POST['fecha_vencimiento'], $_POST['usuario_modificacion'] ) );

            break;
            case 'eliminar_cabeceras':

                $usuario_modificacion = $_POST['usuario_modificacion'];
                $idcabeceras_cartera = $_POST['idcabecera'];

                $dtoCabececerasCartera = new dto_cabeceras_cartera;
                $dtoCabececerasCartera->setId($idcabeceras_cartera);
                $dtoCabececerasCartera->setUsuarioModificacion($usuario_modificacion);

                echo ( $daoCabecerasCartera->delete($dtoCabececerasCartera) ) ? json_encode(array('rst' => true, 'msg' => 'Cabeceras eliminadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al eliminar cabeceras'));

                break;
            case 'actualizarCorteFocalizado':
                $daoCargaCartera->actualizarCortesFocalizados($_POST['servicio'], $_POST['files']);
                break;
            case 'actualizar_cabeceras':

                $idcabeceras_cartera = $_POST['idcabecera'];
                $nombre = $_POST['nombre'];
                $tipo = $_POST['tipo'];
                $cabeceras = $_POST['cabeceras'];
                $usuario_modificacion = str_replace("\\", "", $_POST['usuario_modificacion']);

                $dtoCabececerasCartera = new dto_cabeceras_cartera;
                $dtoCabececerasCartera->setId($idcabeceras_cartera);
                $dtoCabececerasCartera->setNombre($nombre);
                $dtoCabececerasCartera->setTipo($tipo);
                $dtoCabececerasCartera->setUsuarioModificacion($usuario_modificacion);
                $dtoCabececerasCartera->setCabeceras($cabeceras);

                echo ( $daoCabecerasCartera->update($dtoCabececerasCartera) ) ? json_encode(array('rst' => true, 'msg' => 'Cabeceras actualizadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al actualizar cabeceras'));

                break;
            case 'guardar_cabeceras':

                $nombre = $_POST['nombre'];
                $tipo = $_POST['tipo'];
                $cabeceras = $_POST['cabeceras'];
                $idservicio = $_POST['idservicio'];
                $usuario_creacion = str_replace("\\", "", $_POST['usuario_creacion']);

                $dtoCabececerasCartera = new dto_cabeceras_cartera;
                $dtoCabececerasCartera->setNombre($nombre);
                $dtoCabececerasCartera->setTipo($tipo);
                $dtoCabececerasCartera->setIdServicio($idservicio);
                $dtoCabececerasCartera->setUsuarioCreacion($usuario_creacion);
                $dtoCabececerasCartera->setCabeceras($cabeceras);

                echo ( $daoCabecerasCartera->insert($dtoCabececerasCartera) ) ? json_encode(array('rst' => true, 'msg' => 'Cabeceras grabadas correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar cabeceras'));

                break;
            case 'upload_file_import_pcampania':
                $daoCargaCartera->uploadDocumentFileImportPCampania($_POST,$_FILES);
            break;
            case 'upload_file_distribuion_mecanica'	:
                $daoCargaCartera->uploadDocumentFileDistribucionMecanico($_POST,$_FILES);
                break;
            case 'loadHeaderFileDistribucionMecanico':
                $daoCargaCartera->loadHeaderDistribucionMecanico($_POST);
                break;
            case 'save_distribucion_manual':
                $daoCargaCartera->uploadDistribucionMecanico($_POST);
                break;
            case 'upload':
                //$daoCargaCartera->uploadDocumentCartera($_POST,$_FILES);
                $daoCargaCartera->uploadDocumentCarteraPrincipal($_POST, $_FILES);
                break;
            case 'uploadNocPre':
                //$daoCargaCartera->uploadDocumentCartera($_POST,$_FILES);
                $daoCargaCartera->uploadDocumentCarteraNOC($_POST, $_FILES);
                break;
            case 'uploadCourier':
                $daoCargaCartera->uploadDocumentCarteraCourier($_POST, $_FILES);
                break;
            case 'uploadEstadoCuenta':
                $daoCargaCartera->uploadDocumentCarteraEstadoCuenta( $_POST, $_FILES );
            break;
            case 'uploadSaldoTotal':
                $daoCargaCartera->uploadDocumentCarteraSaldoTotal( $_POST, $_FILES );
            break;
            case 'uploadDetalleM':
                $daoCargaCartera->uploadDocumentCarteraDetalleM( $_POST, $_FILES );
            break;
            case 'uploadRetiro':
                $daoCargaCartera->uploadDocumentCarteraRetiro($_POST, $_FILES);
                break;
            case 'uploadCorteFocalizado':
                $daoCargaCartera->uploadDocumentCorteFocalizado($_POST, $_FILES);
                break;
            case 'uploadFacturacion' :
                $daoCargaCartera->uploadDocumentFacturacion($_POST, $_FILES);
                break;
            case 'uploadProvision' :
                $daoCargaCartera->uploadDocumentProvision($_POST, $_FILES);
                break;                
            case 'uploadIVR':
                $daoCargaCartera->uploadDocumentCarteraIVR($_POST, $_FILES);
                break;
            case 'uploadPago':
                $daoCargaCartera->uploadDocumentCarteraPago($_POST, $_FILES);
                break;
            case 'uploadCarteraPlanta':
                $daoCargaCartera->uploadDocumentCarteraPlanta($_POST, $_FILES);
                break;
            case 'uploadTelefono':
                $daoCargaCartera->uploadDocumentCarteraTelefono($_POST, $_FILES);
                break;
            case 'uploadDetalle':
                $daoCargaCartera->uploadDocumentCarteraDetalle($_POST, $_FILES);
                break;
            case 'uploadReclamo':
                $daoCargaCartera->uploadDocumentCarteraReclamo($_POST, $_FILES);
                break;
            case 'uploadRRLL':
                $daoCargaCartera->uploadDocumentCarteraRRLL($_POST, $_FILES);
                break;
            case 'loadHeader':
                $daoCargaCartera->loadHeader2($_POST);
                break;
            case 'loadHeaderPago':
                //$daoCargaCartera->loadHeader($_POST);
                $daoCargaCartera->loadHeaderPago($_POST);
                break;
            case 'loadHeaderCentroPago':
                $daoCargaCartera->loadHeader($_POST);
                break;
            case 'loadHeaderCarteraPlanta':
                $daoCargaCartera->loadHeaderCarteraPlanta($_POST);
                break;
            case 'loadHeaderTelefono':
                $daoCargaCartera->loadHeaderTelefono($_POST);
                break;
            case 'loadHeaderDetalle':
                $daoCargaCartera->loadHeaderDetalle($_POST);
                break;
            case 'loadHeaderReclamo':
                $daoCargaCartera->loadHeaderReclamo($_POST);
                break;
            case 'loadHeaderRRLL':
                $daoCargaCartera->loadHeaderRRLL($_POST);
                break;
            case 'uploadLimpiarCartera':
                //$daoCargaCartera->uploadDocumentCartera($_POST,$_FILES);
                $daoCargaCartera->uploadDocumentLimpiarCartera($_POST, $_FILES);
                break;
            case 'subirCargaLlamada':
                $daoCargaCartera->subirCargaLlamada($_POST,$_FILES);
            break;
            case 'procesarCargaLlamada':
                $usuario=$_POST['usuario_creacion'];
                $archivo=$_POST['archivo'];
                $cartera=$_POST['cartera'];
                $daoCargaCartera->procesarCargaLlamada($usuario,$archivo,$cartera);
            break;
            case 'uploadCentroPago':
                $daoCargaCartera->uploadDocumentCarteraCentroPago($_POST, $_FILES);
                break;
			//~ Vic I
			case 'uploadInsertarLlamada':
				$daoCargaCartera->uploadInsertarLlamadasManual($_POST, $_FILES);
				break;
            case 'uploadCargaCuota':
                $daoCargaCartera->uploadCargaCuota($_POST, $_FILES);
            break;

			case 'uploadFiadores':
				$daoCargaCartera->uploadFiadores($_POST, $_FILES);
			break;
            case 'uploadCargaFacturacion':
                $daoCargaCartera->uploadCargaFacturacion($_POST,$_FILES);
            break;
            case 'uploadCargaProvision': 
                $daoCargaCartera->uploadCargaProvision($_POST,$_FILES);
            break; 
            case  'cargaProvisionTotal' : // airton
                $daoCargaCartera->uploadCargaProvisionTotal($_POST,$_FILES);
            break;           
            case 'generateCargaFacturacion':
                $daoCargaCartera->generateCargaFacturacion($_POST);
            break;
            case 'generateCargaProvision':
                $daoCargaCartera->generateCargaProvision($_POST);
            break;            
			case 'uploadJoinClientes':
				$daoCargaCartera->uploadJoinClientes($_POST, $_FILES);
			break;
			case 'uploadJoinContratos':
				$daoCargaCartera->uploadJoinContratos($_POST, $_FILES);
			break;
			case 'txtJoinCarteras':
				$daoCargaCartera->txtJoinCarteras($_POST);
			break;

			case 'cruceLlamada':
				$daoCargaCartera->cruceLlamada($_POST);
				break;
			case 'newTelefonosManual':
				$daoCargaCartera->newTelefonosManual($_POST);
				break;
			case 'newInsertLlamadasManual':
				$daoCargaCartera->newInsertLlamadasManual($_POST);
				break;
			case 'listaTipificacionLlam':
				echo json_encode($daoCargaCartera->listaTipificacionLlam($_POST['idservicio']));
				break;
			//~ Vic F
            case 'LimpiarCartera':
                $daoCargaCartera->limpiarCartera($_POST);
                break;
            case 'carga_pago':
                if (strtolower($_POST['Proceso']) == 'carga') {

                    $arrFile = explode(':', $_POST["file"]);
                    $processAll = true;
                    $resumen = array();
                    foreach ($arrFile as $key => $value) {
                        $rpt = $daoCargaCartera->uploadCarteraPago($_POST, 1, $value);

                        if (!$rpt['rst']) {
                            $processAll = false;
                        }
                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                    }
                    $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Proceso culminado correctamente')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al procesar los archivos'));
                    echo $rpt;


                    //$daoCargaCartera->uploadCarteraPago($_POST, 1);
                } else if (strtolower($_POST['Proceso']) == 'actualizacion') {
                    //$daoCargaCartera->uploadUpdateCarteraPago($_POST, 1);
                    $arrFile = explode(':', $_POST["file"]);
                    $processAll = true;
                    $resumen = array();
                    foreach ($arrFile as $key => $value) {
                        $rpt = $daoCargaCartera->uploadCarteraPago($_POST, 1, $value,1);

                        if (!$rpt['rst']) {
                            $processAll = false;
                        }
                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                    }
                    $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Proceso culminado correctamente')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al procesar los archivos'));
                    echo $rpt;
                    //$daoCargaCartera->uploadCarteraPago($_POST, 1, $value, 1);
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Proceso de carga no reconocido'));
                }

                break;
            case 'carga_nocpre_masivo':
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraNOCpre_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => '  Carga de NOC predictivo(s) Correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al Cargar archivo(s)'));
                echo $rpt;
                break;
            case 'carga_estado_cuenta_masivo':
                
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraEstadoCuenta_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Carga de estados de cuenta  Correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al cargar estados de cuenta'));
                echo $rpt;
                
                
            break;
            case 'carga_saldo_total_masivo':
                
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraSaldoTotalCencosud_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Carga de saldos totales correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al cargar saldos totales'));
                echo $rpt;
                
            break;
            case 'carga_detalle_m_masivo':
                
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraDetalleMovil($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Carga de detalles correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al cargar detalles'));
                echo $rpt; 
                
            break;
            case 'carga_courier_masivo':

                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraCourier_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => '  Carga de visitas correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al Cargar archivo(s)'));
                echo $rpt;

                break;
            case 'carga_ivr_masivo':
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraIVR_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => '  Carga de IVR Correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al Cargar archivo(s)'));
                echo $rpt;
                break;
            case 'carga_retiro_masivo':
                $arrFiles = explode(':', $_POST["file"]);
                $processAll = true;
                $resumen = array();

                foreach ($arrFiles as $key => $value) {
                    $rpt = $daoCargaCartera->uploadCarteraRetiro_masivo($_POST, $value);
                    if (!$rpt['rst']) {
                        $processAll = false;
                    }
                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                }
                $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => '  Carga de Retiro(s) Correcta')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al Cargar archivo(s)'));
                echo $rpt;
                break;
            case 'carga-cartera':
                if (strtolower($_POST['Proceso']) == 'carga') {
                    $arrFile = explode(':', $_POST["file"]);
                    $processAll = true;
                    $resumen = array();
                    foreach ($arrFile as $key => $value) {
                        $rpt = $daoCargaCartera->uploadCartera($_POST, 1, $value);

                        if($_POST['Servicio']=='6' || $_POST['Servicio']=='7'){
						//~ Vic I
    						if (!$rpt['rst'])
    						{
    							$processAll = false;
    							array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error File'));
    						}
    						else 
    						{
    							if (array_key_exists('tmpBbva', $rpt))
    							{
    								$rptHis = $daoCargaCartera->historyCartera($rpt['tmpBbva'], $_POST['Servicio']);
    								if ($rptHis['rstHis'])
    								{
    									array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
    								}
    								else 
    								{
    									array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
    								}
    							}
    							else 
    							{
    								array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error'));
    							}
    						}
						//~ Vic F
                        }
                        // piro
                        if($_POST['Servicio']=='11'){
                            //~ Vic I
                            if (!$rpt['rst'])
                            {
                                $processAll = false;
                                array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error File'));
                            }
                            else 
                            {
                                if (array_key_exists('tmpBbva', $rpt))
                                {
                                    $rptHis = $daoCargaCartera->historyCarteraSaga($rpt['tmpBbva'], $_POST['Servicio']);
                                    if ($rptHis['rstHis'])
                                    {
                                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
                                    }
                                    else 
                                    {
                                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
                                    }
                                }
                                else 
                                {
                                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error'));
                                }
                            }  
                        }

                    }
                    $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Proceso culminado correctamente')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al procesar los archivos'));
                    echo $rpt;
                } else if (strtolower($_POST['Proceso']) == 'actualizacion') {

                    $arrFile = explode(':', $_POST["file"]);
                    $processAll = true;
                    $resumen = array();
                    foreach ($arrFile as $key => $value) {
                        $rpt = $daoCargaCartera->uploadUpdateCartera($_POST, 1, $value);

                        if($_POST['Servicio']=='6' || $_POST['Servicio']=='7'){
    						//~ Vic I
    						if (!$rpt['rst'])
    						{
    							$processAll = false;
    							array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error File'));
    						}
    						else 
    						{
    							if (array_key_exists('tmpBbva', $rpt))
    							{
    								$rptHis = $daoCargaCartera->historyCartera($rpt['tmpBbva'], $_POST['Servicio']);
    								if ($rptHis['rstHis'])
    								{
    									array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
    								}
    								else 
    								{
    									array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
    								}
    							}
    							else 
    							{
    								array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error'));
    							}
    						}
    						//~ Vic F
                        }

                        // piro
                        if($_POST['Servicio']=='11'){
                            //~ Vic I
                            if (!$rpt['rst'])
                            {
                                $processAll = false;
                                array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error File'));
                            }
                            else 
                            {
                                if (array_key_exists('tmpBbva', $rpt))
                                {
                                    $rptHis = $daoCargaCartera->historyCarteraSaga($rpt['tmpBbva'], $_POST['Servicio']);
                                    if ($rptHis['rstHis'])
                                    {
                                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
                                    }
                                    else 
                                    {
                                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => $rptHis['msgHis']));
                                    }
                                }
                                else 
                                {
                                    array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg'], 'history' => 'Error'));
                                }
                            }
                            
                        }
                    }
                    $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Proceso culminado correctamente')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al procesar los archivos'));
                    echo $rpt;

                    //$daoCargaCartera->uploadUpdateCartera($_POST, 1);
                } else if (strtolower($_POST['Proceso']) == 'agregar') {

                    $arrFile = explode(':', $_POST["file"]);
                    $processAll = true;
                    $resumen = array();
                    foreach ($arrFile as $key => $value) {
                        $rpt = $daoCargaCartera->uploadAddCartera2($_POST, 1, $value);

                        if (!$rpt['rst']) {
                            $processAll = false;
                        }
                        array_push($resumen, array('archivo' => $value, 'msg' => $rpt['msg']));
                    }
                    $rpt = $processAll ? json_encode(array('rst' => true, 'resumen' => $resumen, 'msg' => 'Proceso culminado correctamente')) : json_encode(array('rst' => false, 'resumen' => $resumen, 'msg' => 'Ocurrio un error al procesar los archivos'));
                    echo $rpt;

                }else {
                    echo json_encode(array('rst' => false, 'msg' => 'Proceso de carga no reconocido'));
                }
                break;
            case 'carga_telefono':
                //$data = json_decode(str_replace("\\","",$_POST['data_telefono']),true);
                //print_r($data);
                $daoCargaCartera->uploadCarteraTelefono($_POST);
                break;
            case 'carga_detalle':
                $daoCargaCartera->uploadCarteraDetalle($_POST);
                break;
            case 'carga_reclamo':
                $daoCargaCartera->uploadCarteraReclamo($_POST);
                break;
            case 'carga_rrll':
                $daoCargaCartera->uploadCarteraRRLL($_POST);
                break;
            case 'carga_cartera_automatica':
                $daoCargaCartera->uploadCargaAutomatica($_POST);
                break;
            case 'carga_centro_pago':
                $daoCargaCartera->uploadCentroPago($_POST);
                break;
            case 'carga_cartera_planta':
                $daoCargaCartera->uploadCarteraPlanta($_POST);
                break;
            case 'carga_pago_automatica':
                $daoCargaCartera->uploadCargaAutomaticaPago($_POST);
                break;
            case 'asignar_comision':
                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($_POST['Cartera']);
                $data = json_decode(str_replace("\\", "", $_POST['data']), true);

                echo ($daoDetalleCuenta->asignar_comision($data, $dtoCartera)) ? json_encode(array('rst' => true, 'msg' => 'Comision grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar comision'));

                break;
            case 'asignar_comision_tramo_servicio':
                $dtoTramo = new dto_tramo;
                $dtoCartera = new dto_cartera;

                $data = json_decode(str_replace("\\", "", $_POST['data']), true);

                $dtoCartera->setId($_POST['Cartera']);
                $dtoTramo->setIdServicio($_POST['IdServicio']);
                $dtoTramo->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($daoDetalleCuenta->asignar_comision_tramo_servicio($data, $dtoCartera, $dtoTramo)) ? json_encode(array('rst' => true, 'msg' => 'Comision grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar comision'));
                break;
            case 'asignar_comision_generico':
                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($_POST['Cartera']);

                echo ($daoDetalleCuenta->asignar_comision_generico($_POST['Porcentaje'], $dtoCartera)) ? json_encode(array('rst' => true, 'msg' => 'Comision grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar comision'));

                break;
            case 'asignar_comision_generico_servicio':
                $dtoTramo = new dto_tramo;
                $dtoCartera = new dto_cartera;

                $dtoCartera->setId($_POST['Cartera']);
                $dtoTramo->setIdServicio($_POST['IdServicio']);
                $dtoTramo->setUsuarioModificacion($_POST['Usuario']);
                $dtoTramo->setPorcentaje($_POST['Porcentaje']);

                echo ($daoDetalleCuenta->asignar_comision_generico_servicio($dtoTramo, $dtoCartera)) ? json_encode(array('rst' => true, 'msg' => 'Comision grabada correctamente')) : json_encode(array('rst' => false, 'msg' => 'Error al grabar comision'));
                break;
            case 'Listar_Cartera':
                $daoCargaCartera->Listar_Cartera();
                break;
            default:
                echo json_encode(array('rst' => false, 'msg' => 'Accion no encontrada'));
                ;
        }
    }

    public function doGet() {
        $daoCampania = DAOFactory::getDAOCampania('maria');
        $daoCartera = DAOFactory::getDAOCartera('maria');
        $daoProvincia = DAOFactory::getDAOProvincia('maria');
        $daoTramo = DAOFactory::getDAOTramo('maria');
        $daoDetalleCuenta = DAOFactory::getDAODetalleCuenta('maria');
        $daoCabecerasCartera = DAOFactory::getCabecerasCarteraDAO('maria');
        $daoJqgrid = DAOFactory::getDAOJqgrid('maria');
        $daoCargaCartera = DAOFactory::getDAOCargaCartera('maria');
        switch ($_GET["action"]) {
            case 'ListTodasCartera':
                
                $dtoUsuarioServicio = new dto_usuario_servicio ;
                $dtoUsuarioServicio->setIdServicio($_GET['servicio']);
                
                echo json_encode( $daoCartera->queryAllByService( $dtoUsuarioServicio ) );
                
            break;
            case 'downloadFileFacturacion':
                $nameTable = $_GET['tabla'];
                $daoCargaCartera->downloadFileFacturacion($nameTable);
                break;
            case 'ListarModuloCabecerasPorId':

                $idcabecera = $_GET['idcabecera'];

                $dtoCabecerasCartera = new dto_cabeceras_cartera;
                $dtoCabecerasCartera->setId($idcabecera);

                echo json_encode($daoCabecerasCartera->queryById($dtoCabecerasCartera));

                break;
            case 'ListarModuloCabecerasPorServicioPago':

                $idservicio = $_GET['idservicio'];

                $dtoCabecerasCartera = new dto_cabeceras_cartera;
                $dtoCabecerasCartera->setIdServicio($idservicio);

                echo json_encode($daoCabecerasCartera->queryPago($dtoCabecerasCartera));

                break;
            case 'ListarModuloCabecerasPorServicioCartera':

                $idservicio = $_GET['idservicio'];

                $dtoCabecerasCartera = new dto_cabeceras_cartera;
                $dtoCabecerasCartera->setIdServicio($idservicio);

                echo json_encode($daoCabecerasCartera->queryCartera($dtoCabecerasCartera));

                break;
            case 'ListarModuloCabecerasPorServicio':

                $idservicio = $_GET['idservicio'];

                $dtoCabecerasCartera = new dto_cabeceras_cartera;
                $dtoCabecerasCartera->setIdServicio($idservicio);

                echo json_encode($daoCabecerasCartera->queryByService($dtoCabecerasCartera));


                break;
            case 'DataTemplate':

                $idcartera = $_GET['idcartera'];

                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($idcartera);

                echo json_encode($daoCartera->queryCarteraMetaData($dtoCartera));

                break;
            case 'ListCampania':
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['Servicio']);
                echo json_encode($daoCampania->queryAllByIdName($dtoServicio));
                break;
                /*jmore*/
            case 'procesarNormalizacionTelefono':
                $dtoCartera=new dto_cartera;
                $dtoCartera->setId($_GET['cartera']);
                echo json_encode($daoCartera->updateNormalizacionTelefono($dtoCartera));
                break;/*jmore*/            
            case 'ListCartera':
                $dtoCampania = new dto_campanias;
                $dtoCampania->setId($_GET['Campania']);
                echo json_encode($daoCartera->queryIdNombreActivos($dtoCampania));
                break;
            case 'ListCarteraRpteRank':
                $dtoCampania = new dto_campanias;
                $dtoCampania->setId($_GET['Campania']);
                $estado = $_GET['Estado'];
                echo json_encode($daoCartera->queryIdNombreActivosRpteRank($dtoCampania, $estado));
                break;
            case 'ListCarteraOperador':
                $dtoCampania = new dto_campanias;
                $dtoCampania->setId($_GET['Campania']);
                $usuario = $_GET['idusuario_servicio'];
                //echo json_encode($daoCartera->queryIdNombreActivosOperador($dtoCampania,$usuario));
                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];
                $where = "";
                if( $_GET['Evento']!='0' ) {
                        $where .= " AND car.evento = ".$_GET['Evento'];
                }
                if( $_GET['Cluster']!='0' ) {
                        $where .= " AND car.cluster = ".$_GET['Cluster'];
                }
                if( $_GET['Segmento']!='0' ) {
                        $where .= " AND car.segmento = ".$_GET['Segmento'];
                }
                if( $_GET['Modo']=='cartera'){
                        $where .= " AND clicar.idusuario_servicio = ?";
                }
                if( $_GET['Modo']=='seguimiento'){
                        $where .= " AND clicar.idusuario_servicio_especial = ?";
                }                
                $param = array($dtoCampania->getId(), $usuario);
                if (!$sidx)
                    $sidx = 1;

                $row = $daoJqgrid->JQGRIDCountCarterasXoperador($where, $param);

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

                $data = $daoJqgrid->JQGRIDRowsCarterasXoperador($sidx, $sord, $start, $limit, $where, $param);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['vencido'] != 1) {
                        array_push($dataRow, array("id" => $data[$i]['idcartera'], "cell" => array(
                                $data[$i]['idcartera'],
                                $data[$i]['nombre_cartera'],
                                $data[$i]['fecha_inicio'],
                                $data[$i]['fecha_fin']
                                )));
                    }
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);
                break;
            case 'ListProvincia':
                $dtoCampania = new dto_campanias;
                $dtoCampania->setId($_GET['Campania']);
                //print_r($dtoProvincia);
                echo json_encode($daoProvincia->queryAllProvincias($dtoCampania));
                break;
            case 'ListarTramo':
                $dtoCartera = new dto_cartera;
                $dtoCartera->setId($_GET['Id']);
                echo json_encode($daoDetalleCuenta->queryTramo($dtoCartera));
                break;
            case 'ListarTramoServicio':
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['IdServicio']);
                echo json_encode($daoTramo->queryTramo($dtoServicio));
                break;
            case 'ListarTramoGenerico':
                $dtoServicio = new dto_servicio;
                $dtoServicio->setId($_GET['IdServicio']);
                echo json_encode($daoTramo->queryGenerico($dtoServicio));
                break;
            default:
                echo json_encode(array('rst' => false, 'msg' => 'Accion no encontrada'));
                ;
        }
    }

}

?>
