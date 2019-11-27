<?php

require_once '../includes/class.phpmailer.php';

class MARIAFacturaDigitalDAO {

    public function uploadFacturaDigital(dto_factura_digital $dtoFacturaDigital, $_files, $nombre_servicio, $file) {

        $sql = " INSERT INTO ca_factura_digital ( correo ,idusuario_servicio ,solicita ,fecha_creacion ,usuario_creacion ,ruta_absoluta ,observacion ,idcliente_cartera ,ruta_cobrast,fecha_vencimiento, idcuenta ) 
			VALUES ( ?,?,?,NOW(),?,?,?,?,?,?,? ) ";

        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);

        $ruta_absoluta = $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'];
        $ruta_cobrast = "documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'];
        $ruta_absoluta = str_replace('//', '/', $ruta_absoluta);
        $correo = $dtoFacturaDigital->getCorreo();
        $idusuario_servicio = $dtoFacturaDigital->getIdUsuarioServicio();
        $solicita = $dtoFacturaDigital->getSolicita();
        $usuario_creacion = $dtoFacturaDigital->getUsuarioCreacion();
        $observacion = $dtoFacturaDigital->getObservacion();
        $idcliente_cartera = $dtoFacturaDigital->getIdClienteCartera();
        $fecha_vencimiento = $dtoFacturaDigital->getFechaVencimiento();
        $idcuenta = $dtoFacturaDigital->getIdcuenta();
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $correo, PDO::PARAM_STR);
        $pr->bindParam(2, $idusuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(3, $solicita, PDO::PARAM_INT);
        $pr->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
        $pr->bindParam(5, $ruta_absoluta, PDO::PARAM_STR);
        $pr->bindParam(6, $observacion, PDO::PARAM_STR);
        $pr->bindParam(7, $idcliente_cartera, PDO::PARAM_INT);
        $pr->bindParam(8, $ruta_cobrast, PDO::PARAM_STR);
        $pr->bindParam(9, $fecha_vencimiento, PDO::PARAM_STR);
        $pr->bindParam(10, $idcuenta, PDO::PARAM_INT);
        if ($pr->execute()) {

            if (@opendir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio)) {

                if (@opendir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d"))) {
                    if (@move_uploaded_file($_files[$file]['tmp_name'], $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'])) {
                        //$connection->commit();
                        return json_encode(array('rst' => true, 'msg' => 'Factura grabada correctamente'));
                    } else {
                        //$connection->rollBack();
                        return json_encode(array('rst' => false, 'msg' => 'Error al grabar factura'));
                    }
                } else {
                    if (@mkdir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d"))) {
                        if (@move_uploaded_file($_files[$file]['tmp_name'], $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'])) {
                            //$connection->commit();
                            return json_encode(array('rst' => true, 'msg' => 'Factura grabada correctamente'));
                        } else {
                            //$connection->rollBack();
                            return json_encode(array('rst' => false, 'msg' => 'Error al subir factura al servidor'));
                        }
                    } else {
                        //$connection->rollBack();
                        return json_encode(array('rst' => false, 'msg' => 'Error al abrir carpeta de factura'));
                    }
                }
            } else {
                if (@mkdir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio)) {
                    if (@opendir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d"))) {
                        if (@move_uploaded_file($_files[$file]['tmp_name'], $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'])) {
                            //$connection->commit();
                            return json_encode(array('rst' => true, 'msg' => 'Factura grabada correctamente'));
                        } else {
                            //$connection->rollBack();
                            return json_encode(array('rst' => false, 'msg' => 'Error al subir factura al servidor'));
                        }
                    } else {
                        if (@mkdir($confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d"))) {
                            if (@move_uploaded_file($_files[$file]['tmp_name'], $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturas/" . $nombre_servicio . "/" . date("Y_m_d") . "/" . $_files[$file]['name'])) {
                                //$connection->commit();
                                return json_encode(array('rst' => true, 'msg' => 'Factura grabada correctamente'));
                            } else {
                                //$connection->rollBack();
                                return json_encode(array('rst' => false, 'msg' => 'Error al subir factura al servidor'));
                            }
                        } else {
                            //$connection->rollBack();
                            return json_encode(array('rst' => false, 'msg' => 'Error al crear carpeta ' . date("Y_m_d")));
                        }
                    }
                } else {
                    //$connection->rollBack();
                    return json_encode(array('rst' => false, 'msg' => 'Error al crear carpeta ' . $nombre_servicio));
                }
            }
        } else {
            //$connection->rollBack();
            //return false;
            return json_encode(array('rst' => false, 'msg' => 'Error al grabar factura'));
        }
    }

    public function sendEmail($dtoFacturaDigital) {
        $confCobrast = parse_ini_file('../conf/cobrast.ini', true);
        $body = 'Estimado(a) Sr(a). ' . $dtoFacturaDigital->getSolicita() . ' de acuerdo a su pedido adjuntamos su Factura Digital con <br/>
				Fecha de vencimiento ' . $dtoFacturaDigital->getFechaVencimiento() . '. <br/>
				Asimismo, lo invitamos a afiliarse sin costo al servicio de Factura Digital, <br/> 
				Ingresando a la p�gina Web <br/> 
				<a href="http://www.telefonica.com.pe">http://www.telefonica.com.pe</a>';
        $objMailer = new PHPMailer();
        $objMailer->SMTPAuth = true;
        //$objMailer->SMTPDebug= true;
        $objMailer->WordWrap = -1;
        $objMailer->PluginDir = $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . '/includes/';
        $objMailer->Mailer = 'smtp';
        $objMailer->Host = 'mail.hdec.pe';
        $objMailer->SMTPSecure = "tls";
        //$objMailer->Priority = 1;
        //$objMailer->Timeout = 200;
        $objMailer->IsHTML(true);
        $objMailer->Port = '25';
        $objMailer->Username = 'desarrollo';
        $objMailer->Password = 'Desarrollo2009$';
        $objMailer->From = 'fija4@hdec.pe';
        $objMailer->FromName = 'HDEC';
        $objMailer->Subject = 'Factura Digital';
        $objMailer->AddAddress($dtoFacturaDigital->getCorreo());
        $objMailer->AddCC('fija3@hdec.pe');
        $objMailer->AddAttachment($dtoFacturaDigital->getRutaAbsoluta());
        $objMailer->Body = $body;

        return $objMailer->Send();
    }

    public function emailEnviado($obj) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sql = 'UPDATE ca_factura_digital set is_send = 1 WHERE idfactura_digital = ?';
        $stm = $connection->prepare($sql);
        $stm->execute(array($obj->idfactura_digital));
    }

    public function getLinasFacturaDigital($idClienteCartera) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $sql = 'SELECT cuenta.idcuenta, CONCAT( cuenta.numero_cuenta, IFNULL(cuenta.telefono,"") ) AS telefono
                FROM ca_cuenta AS cuenta LEFT JOIN ca_factura_digital AS fact 
                ON  cuenta.idcuenta = fact.idcuenta 
                WHERE cuenta.idcliente_cartera = ? AND (fact.estado = 1 OR ISNULL(fact.estado)) 
                AND (fact.is_send = 0 OR ISNULL(fact.is_send))';
        $stm = $connection->prepare($sql);
        if ($stm->execute(array($idClienteCartera))) {
            return array('rst' => true, 'data' => $stm->fetchAll(PDO::FETCH_ASSOC));
        } else {
            return array('rst' => false, 'msg' => 'Error al traer los datos');
        }
    }

}
?>

