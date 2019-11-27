<?php

class servletAsteriskAGI extends CommandController {

    public function doPost() {
        
    }

    public function doGet() {
        switch ($_GET['action']):
            case 'originate':

                $phone = trim($_GET['Phone']);
                /*********/
                $asterisk_server = trim($_GET['CallCenterIp']);
                $prefijo = trim($_GET['Prefijo']);
                $usuario_asterisk = trim($_GET['UserCallCenter']);
                $password_asterisk = trim($_GET['PasswordCallCenter']);
                $servicio = str_replace(" ", "_", substr(trim($_GET['NombreServicio']), 0, 5));
                $anexo = trim($_GET['Anexo']);
                $idclientecartera=trim(@$_GET['idcliente_cartera']);
                $codigo_cliente=trim(@$_GET['codigo_cliente']);
                /*********/
                $errno = 0;
                $errstr = 0;


                if ($phone == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Ingrese numero telefonico', 'fecha_llamada' => ''));
                    exit();
                }

                $idusuario_servicio = trim($_SESSION['cobrast']['idusuario_servicio']);

                /*if ($idusuario_servicio == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Logeese de nuevo por favor', 'fecha_llamada' => ''));
                    exit();
                }*/

                /*$memcached = new ConnectionMemcached();
                $anexo = trim($memcached->getValue($idusuario_servicio));*/

                /*if ($anexo == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Actualize su anexo', 'fecha_llamada' => ''));
                    exit();
                }*/

                /*$asterisk_server = trim($_SESSION['cobrast']['call_center_ip']);
                $prefijo = trim($_SESSION['cobrast']['prefijo']);
                $usuario_asterisk = trim($_SESSION['cobrast']['user_call_center']);
                $password_asterisk = trim($_SESSION['cobrast']['password_call_center']);
                $servicio = str_replace(" ", "_", substr(trim($_SESSION['cobrast']['servicio']), 0, 5));*/

                if ($asterisk_server == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Logeese de nuevo por favor', 'fecha_llamada' => ''));
                    exit();
                }

                /* if( $prefijo == '' ) { 
                  echo json_encode(array('rst'=>false,'msg'=>'Logeese de nuevo por favor','fecha_llamada'=>''));
                  exit();
                  } */


                $dataSocket = array();
                $oSocket = fsockopen($asterisk_server, 5038, $errno, $errstr, 20);

                if ($oSocket) {

                    fputs($oSocket, "Action: login\r\n");
                    fputs($oSocket, "Events: off\r\n");
                    /* fputs($oSocket, "Username: usercall\r\n");
                      fputs($oSocket, "Secret: userca11\r\n\r\n"); */
                    fputs($oSocket, "Username: " . $usuario_asterisk . "\r\n");
                    fputs($oSocket, "Secret: " . $password_asterisk . "\r\n\r\n");

                    while ($result = strtolower(trim(fgets($oSocket)))) {
                        array_push($dataSocket, $result);
                    }

                    fputs($oSocket, "Action: originate\r\n");
                    fputs($oSocket, "Channel: SIP/" . $anexo . "\r\n");
                    fputs($oSocket, "WaitTime: 15\r\n");
//                    fputs($oSocket, "CallerId:  " . $servicio.'-'.$anexo . "<" . $prefijo . $phone . ">\r\n");

                    //$caller_id = $idclientecartera."_".$phone."_".$anexo."_".date("YmdHisu");
                    $phonenuevo="";
                    $cantidaddigito=strlen($phone);
                                        
                    if($cantidaddigito=="7"){
                        $phone="01".$phone;
                    }

                    $inicio=substr($phone,0,1);

                    if($inicio=="9"){
                      $phonenuevo="C".$phone;
                    }else{
                      $phonenuevo="F".$phone;
                    }
                    $caller_id="26".$codigo_cliente.date("ymdHi").$phonenuevo;
                    
                    fputs($oSocket, "CallerId:  " .$caller_id."\r\n");                    
                    fputs($oSocket, "Exten: " . $prefijo . $phone . "\r\n");
                    fputs($oSocket, "Context: continental\r\n");
                    fputs($oSocket, "Priority: 1\r\n\r\n");



                    while ($result = strtolower(trim(fgets($oSocket)))) {
                        array_push($dataSocket, $result);
                    }

                    fputs($oSocket, "ACTION: ExtensionState\r\n");
                    //fputs($oSocket, "Context: from-internal\r\n");
                    fputs($oSocket, "Context: continental\r\n");
                    fputs($oSocket, "Exten: " . $anexo . "\r\n");
                    fputs($oSocket, "ActionID: 1\r\n\r\n");

                    $fecha_llamada = date("Y-m-d H:i:s");

                    while ($result = strtolower(trim(fgets($oSocket)))) {
                        array_push($dataSocket, $result);

                    }

                    $message = '';
                    //$rst = false;
                    $rst = true;

                    $dataStatus = $dataSocket[count($dataSocket) - 1];
                    $dataStatus = explode(":", $dataStatus);

                    

                    /*if (trim($dataStatus[1]) == -1) {
                        $message = 'Error en realizar llamada, anexo no existe';
                        $rst = false;
                    } else {
                        $message = 'Llamada realizada correctamente';
                        $rst = true;
                    }*/

                    fputs($oSocket, "Action: Logoff\r\n\r\n");

                    echo json_encode(array('rst' => $rst, 'msg' => $message.' status '.($dataStatus[1]), 'fecha_llamada' => $fecha_llamada,'callerid'=>$caller_id));
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al realizar llamada', 'fecha_llamada' => $fecha_llamada));
                }

                fclose($oSocket);


                break;
            case 'Hungup':

                $idusuario_servicio = trim($_SESSION['cobrast']['idusuario_servicio']);

                /**************/
                $asterisk_server = trim($_GET['CallCenterIp']);
                $prefijo = trim(@$_GET['Prefijo']);
                $usuario_asterisk = trim($_GET['UserCallCenter']);
                $password_asterisk = trim($_GET['PasswordCallCenter']);
                $servicio = str_replace(" ", "_", substr(trim($_GET['NombreServicio']), 0, 5));
                $anexo = trim($_GET['Anexo']);
                /**************/

                if ($idusuario_servicio == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Logeese de nuevo por favor', 'fecha_llamada' => ''));
                    exit();
                }

                /*$memcached = new ConnectionMemcached();
                $anexo = trim($memcached->getValue($idusuario_servicio));*/

                if ($anexo == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Actualize su anexo', 'fecha_llamada' => ''));
                    exit();
                }

                /*$asterisk_server = trim($_SESSION['cobrast']['call_center_ip']);
                $prefijo = trim($_SESSION['cobrast']['prefijo']);
                $usuario_asterisk = trim($_SESSION['cobrast']['user_call_center']);
                $password_asterisk = trim($_SESSION['cobrast']['password_call_center']);*/

                if ($asterisk_server == '') {
                    echo json_encode(array('rst' => false, 'msg' => 'Logeese de nuevo por favor', 'fecha_llamada' => ''));
                    exit();
                }

                /* if( $prefijo == '' ) { 
                  echo json_encode(array('rst'=>false,'msg'=>'Logeese de nuevo por favor','fecha_llamada'=>''));
                  exit();
                  } */


                $errno = 0;
                $errstr = 0;

                $oSocket = fsockopen($asterisk_server, 5038, $errno, $errstr, 20);

                if ($oSocket) {

                    $dataSocket = array();

                    fputs($oSocket, "Action: login\r\n");
                    fputs($oSocket, "Events: off\r\n");
                    fputs($oSocket, "Username: " . $usuario_asterisk . "\r\n");
                    fputs($oSocket, "Secret: " . $password_asterisk . "\r\n\r\n");

                    while ($result = strtolower(trim(fgets($oSocket)))) {
                        array_push($dataSocket, $result);
                    }

                    fputs($oSocket, "Action: ExtensionState\r\n");
                    fputs($oSocket, "exten: " . $anexo . "\r\n");
                    fputs($oSocket, "context: nivel-2\r\n");
                    fputs($oSocket, "actionid: 1\r\n\r\n");

                    while ($result = strtolower(trim(fgets($oSocket)))) {
                        array_push($dataSocket, $result);
                    }

                    $dataChannel = array();

                    $dataStatus = $dataSocket[count($dataSocket) - 1];
                    $dataStatus = explode(":", $dataStatus);
                    //echo " status ".$dataStatus[1];
                    //if (trim($dataStatus[1]) == 1 || trim($dataStatus[1]) == 2 || trim($dataStatus[1]) == 4) {

                        fputs($oSocket, "Action: command\r\n");
                        fputs($oSocket, "command: show channels\r\n\r\n");

                        while ($result = strtolower(trim(fgets($oSocket)))) {
                            array_push($dataChannel, $result);
                        }

                        $dataHangup = array();

                        for ($i = 3; $i < (count($dataChannel) - 3); $i++) {
                            $data = explode(" ", trim($dataChannel[$i]));
                            $channel = explode("-", trim($data[0]));
                            if ($channel[0] == 'sip/' . $anexo) {
                                fputs($oSocket, "Action: Hangup\r\n");
                                fputs($oSocket, "channel: " . trim($data[0]) . "\r\n\r\n");
                                while ($result = strtolower(trim(fgets($oSocket)))) {
                                    array_push($dataHangup, $result);
                                }

                                break;
                            }
                        }

                        if (strstr($dataHangup[0], 'success')) {
                            echo json_encode(array('rst' => true, 'msg' => 'Llamada colgada correctamente', 'fecha_llamada' => date("Y-m-d H:i:s")));
                        } else {
                            echo json_encode(array('rst' => false, 'msg' => 'Error al colgar llamada', 'fecha_llamada' => date("Y-m-d H:i:s")));
                        }
                    /*} else {
                        echo json_encode(array('rst' => false, 'msg' => 'Llamada not found', 'fecha_llamada' => date("Y-m-d H:i:s")));
                    }*/

                    fputs($oSocket, "Action: Logoff\r\n\r\n");
                } else {
                    echo json_encode(array('rst' => false, 'msg' => 'Error al realizar llamada', 'fecha_llamada' => date("Y-m-d H:i:s")));
                }

                fclose($oSocket);


                break;
            default:
                echo json_encode(array('rst' => false, 'msg' => 'Acction not found'));
                ;
        endswitch;
    }

}
?>

