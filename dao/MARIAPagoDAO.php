<?php

class MARIAPagoDAO {
	public function insertAcuerdoPago(dto_acuerdo_pago $dtoAcuerdoPago){
		$idClienteCartera = $dtoAcuerdoPago->getIdClienteCartera();
		$idCuenta = $dtoAcuerdoPago->getIdCuenta();
		$UsuarioServicio = $dtoAcuerdoPago->getUsuarioCreacion();
        $numeroPagare = $dtoAcuerdoPago->getNumeroPagare();
        $numeroCuotas = $dtoAcuerdoPago->getNumeroCuotas();
        $fechaAcuerdo = $dtoAcuerdoPago->getFechaAcuerdo();
        $valorAcuerdo = $dtoAcuerdoPago->getValorAcuerdo();

        $arrayDetalle = json_decode($_POST['detalleAcuerdoPago'],true);
        $dataDetalle ="";

        

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sqlUpdateExistAcuerdoPago= "UPDATE ca_acuerdo_pago SET estado = 0 WHERE idcliente_cartera= ? AND idcuenta = ? AND estado = 1";
        $prUpdateExistAcuerdoPago=$connection->prepare($sqlUpdateExistAcuerdoPago);
        $prUpdateExistAcuerdoPago->bindParam(1,$idClienteCartera, PDO::PARAM_INT);
        $prUpdateExistAcuerdoPago->bindParam(2,$idCuenta, PDO::PARAM_INT);
        $prUpdateExistAcuerdoPago->execute();

        $sqlUpdateExistDetalleAcuerdoPago= "UPDATE ca_detalle_acuerdo_pago SET estado = 0 WHERE idcliente_cartera= ? AND idcuenta = ? AND estado = 1";
        $prUpdateExistDetalleAcuerdoPago=$connection->prepare($sqlUpdateExistDetalleAcuerdoPago);
        $prUpdateExistDetalleAcuerdoPago->bindParam(1,$idClienteCartera, PDO::PARAM_INT);
        $prUpdateExistDetalleAcuerdoPago->bindParam(2,$idCuenta, PDO::PARAM_INT);
        $prUpdateExistDetalleAcuerdoPago->execute();


        $sqlInsertAcuerdoPago = "INSERT INTO ca_acuerdo_pago(idcliente_cartera, idcuenta, estado, numero_pagare, numero_cuotas, fecha_acuerdo, valor_acuerdo, usuario_creacion, fecha_creacion) VALUES".
    							"(?,?,1,?,?,?,?,?, now() )";
		$prInsertAcuerdoPago = $connection->prepare($sqlInsertAcuerdoPago);
		$prInsertAcuerdoPago->bindParam(1,$idClienteCartera,PDO::PARAM_INT);
		$prInsertAcuerdoPago->bindParam(2,$idCuenta,PDO::PARAM_INT);
		$prInsertAcuerdoPago->bindParam(3,$numeroPagare,PDO::PARAM_INT);
		$prInsertAcuerdoPago->bindParam(4,$numeroCuotas,PDO::PARAM_INT);
		$prInsertAcuerdoPago->bindParam(5,$fechaAcuerdo,PDO::PARAM_STR);
		$prInsertAcuerdoPago->bindParam(6,$valorAcuerdo,PDO::PARAM_INT);
		$prInsertAcuerdoPago->bindParam(7,$UsuarioServicio,PDO::PARAM_INT);

		if($prInsertAcuerdoPago->execute()){
			$lastIdAcuerdoPago = $connection->lastInsertId();

	        for($i=0;$i<count($arrayDetalle);$i++){
	        	$dataDetalle.="(". "'".$lastIdAcuerdoPago."',". 
	        						"'".$idClienteCartera."',".
	        						"'".$idCuenta."',".
	        						"'".$numeroPagare."',".
	        						"'".$arrayDetalle[$i]['numero_cuota']."',".
	        						"'".$arrayDetalle[$i]['fecha_cuota']."',".
	        						"'".$arrayDetalle[$i]['valor_cuota']."',".
	        						"'".$UsuarioServicio."',".
	        						"now(),".
	        						"1"
	        					.")," ;
	        }
	        $dataDetalle= substr($dataDetalle, 0, -1);
			
			$sqlInsertDetalleAcuerdoPago = "INSERT INTO ca_detalle_acuerdo_pago(idacuerdo_pago, idcliente_cartera, idcuenta, numero_pagare, numero_cuota, fecha_cuota, valor_cuota, usuario_creacion, fecha_creacion, estado) VALUES".
											$dataDetalle;
			$prInsertDetalleAcuerdoPago= $connection->prepare($sqlInsertDetalleAcuerdoPago);
			if($prInsertDetalleAcuerdoPago->execute()){
				echo json_encode(array('rst'=>true,'msg'=>'Acuerdo de pago realizado'));
			}else{
				echo json_encode(array('rst'=>fasle,'msg'=>'Detalle del Acuerdo de pago no realizado'));
			}
		}else{
			echo json_encode(array('rst'=>fasle,'msg'=>'Acuerdo de pago no realizado'));
		}		


	}

	public function listarPagoRef ( dto_pago $dtoPago ) {
		
		$idcli_car = $dtoPago->getIdClienteCartera();
		$idcar = $dtoPago->getIdCartera();
		
		$sql = " SELECT 
				idpago,
				fecha,
				TRUNCATE(monto_pagado,2) AS monto_pagado,
				IFNULL(moneda_pago,'') AS moneda_pago,
				IFNULL(observacion,'') AS observacion
				FROM ca_pago 
				WHERE estado = 1 AND estado_pago = 'REFINANCIAMIENTO' AND idcliente_cartera = ? AND idcartera = ?  ";
				
		$factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
		
		$pr = $connection->prepare( $sql );
		$pr->bindParam(1,$idcli_car,PDO::PARAM_INT);
		$pr->bindParam(2,$idcar,PDO::PARAM_INT);
		$pr->execute();
		
		return $pr->fetchAll(PDO::FETCH_ASSOC);		
		
	}
	
	public function grabarPagoRefinanciamiento( dto_pago $dtoPago ) {
		
		$idcli_car = $dtoPago->getIdClienteCartera();
		$idcar = $dtoPago->getIdCartera();
		$cod_cli = $dtoPago->getCodigoCliente();
		$monto = $dtoPago->getMontoPagado();
		$moneda = $dtoPago->getMoneda();
		$obs = $dtoPago->getObservacion();
		$usuario_creacion = $dtoPago->getUsuarioCreacion();
		$fecha = date("Y-m-d H:i:s");
		
		$factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $pago = '[{"campoT":"codigo_cliente","dato":"CodigoCliente","label":"CODIGO CLIENTE"},{"campoT":"monto_pagado","dato":"MontoPagado","label":"MONTO PAGADO"},{"campoT":"fecha","dato":"fecha_pago","label":"FECHA PAGO"}{"campoT":"observacion","dato":"observacion","label":"OBSERVACION"}]';
			
		$sqlCarPag = " INSERT INTO ca_cartera_pago ( cantidad, fecha_carga, usuario_creacion, fecha_creacion, idcartera, pago ) 
						VALUES ( 1,NOW(),?,NOW(),?,? ) ";
							
		$prCarPag = $connection->prepare( $sqlCarPag );
		$prCarPag->bindParam(1,$usuario_creacion,PDO::PARAM_INT);
		$prCarPag->bindParam(2,$idcar,PDO::PARAM_INT);
		$prCarPag->bindParam(3,$pago,PDO::PARAM_STR);
		if( $prCarPag->execute() ) {
			
			$idcar_pag = $connection->lastInsertId();
        
	        $sql = " INSERT INTO 
					ca_pago ( idcartera_pago,idcliente_cartera, idcartera, codigo_cliente, monto_pagado, moneda_pago, fecha, observacion, fecha_creacion, usuario_creacion, estado_pago ) 
					VALUES 
					( ?,?,?,?,?,?,?,?,NOW(),?,'REFINANCIAMIENTO' ) ";
						
			$pr = $connection->prepare( $sql );
			$pr->bindParam(1,$idcar_pag,PDO::PARAM_INT);
			$pr->bindParam(2,$idcli_car,PDO::PARAM_INT);
			$pr->bindParam(3,$idcar,PDO::PARAM_INT);
			$pr->bindParam(4,$cod_cli,PDO::PARAM_STR);
			$pr->bindParam(5,$monto);
			$pr->bindParam(6,$moneda,PDO::PARAM_STR);
			$pr->bindParam(7,$fecha,PDO::PARAM_STR);
			$pr->bindParam(8,$obs,PDO::PARAM_STR);
			$pr->bindParam(9,$usuario_creacion,PDO::PARAM_INT);
			if( $pr->execute() ) {
				
				$idpago = $connection->lastInsertId();
				$sqlUp = " UPDATE ca_cliente_cartera
						SET
						monto_pagado_ref = ( IFNULL(monto_pagado_ref,0) + ? ),
						ultima_fecha_pago_ref = ?,
						n_cuotas_pagadas_ref = ( IFNULL(n_cuotas_pagadas_ref,0) + 1 ),
						ultimo_monto_pagado_ref = ?
						WHERE idcartera = ? AND idcliente_cartera = ? ";
				
				$prUp = $connection->prepare( $sqlUp );
				$prUp->bindParam(1,$monto);
				$prUp->bindParam(2,$fecha,PDO::PARAM_STR);
				$prUp->bindParam(3,$monto);
				$prUp->bindParam(4,$idcar,PDO::PARAM_INT);
				$prUp->bindParam(5,$idcli_car,PDO::PARAM_INT);
				if( $prUp->execute() ) {
					
					return array("rst"=>true,"id"=>$idpago,"msg"=>"Pago grabado correctamente");
				}else{
					return array("rst"=>false,"msg"=>"Error al actualizar monto");
				}
			}else{
				return array("rst"=>false,"msg"=>"Error al grabar pago");
			}
			
		}else{
			return array("rst"=>false,"msg"=>"Error al grabar pago");
		}
		
	}

    public function listarEstadoPago(dto_cartera $dtoCartera) {
        $idcartera = $dtoCartera->getId();
        $sql = " SELECT TRIM(estado_pago) AS  'estado_pago' FROM ca_pago 
				WHERE idcartera IN (" . $idcartera . ") AND estado = 1 AND ISNULL(estado_pago)=0 AND TRIM(estado_pago)!=''
				GROUP BY TRIM(estado_pago) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }/*jmore300612*/
		public function insert ( dto_pago $dtoPago ) {
			
			$idcartera = $dtoPago->getIdCartera();
                        $iddetalle_cuenta=$dtoPago->getIdDetalleCuenta();
			$numero_cuenta = $dtoPago->getNumeroCuenta();
			$moneda = $dtoPago->getMoneda();
			$codigo_operacion = $dtoPago->getCodigoOperacion();
			$monto_pagado = $dtoPago->getMontoPagado();
			$fecha = $dtoPago->getFecha();
			$estado_pago = $dtoPago->getEstadoPago();
			$observacion = $dtoPago->getObservacion();
			$agencia = $dtoPago->getAgencia();
			$usuario_creacion = $dtoPago->getUsuarioCreacion();
			
			$pago = '[{"campoT":"numero_cuenta","dato":"NUM_CUENTA_PMCP","label":"NUMERO CUENTA"},{"campoT":"codigo_operacion","dato":"NUM_CUENTA_PMCP","label":"CODIGO OPERACION"},{"campoT":"monto_pagado","dato":"MontoPagado","label":"MONTO PAGADO"},{"campoT":"fecha","dato":"fecha_pago","label":"FECHA PAGO"},{"campoT":"agencia","dato":"Agencia","label":"AGENCIA"},{"campoT":"observacion","dato":"observacion","label":"OBSERVACION"}]';
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
				
			$connection->beginTransaction();
			
			$sqlCarteraPago = " INSERT INTO ca_cartera_pago ( cantidad, fecha_carga, usuario_creacion, fecha_creacion, idcartera, pago ) 
			VALUES ( 1,NOW(),?,NOW(),?,? ) ";
			
			$prCarteraPago = $connection->prepare($sqlCarteraPago);
			$prCarteraPago->bindParam(1,$usuario_creacion,PDO::PARAM_INT);
			$prCarteraPago->bindParam(2,$idcartera,PDO::PARAM_INT);
			$prCarteraPago->bindParam(3,$pago,PDO::PARAM_STR);
			if( $prCarteraPago->execute() ) {
				
				$idcartera_pago = $connection->lastInsertId();
				
				$sql = " INSERT INTO ca_pago ( numero_cuenta, moneda_cuenta, codigo_operacion, monto_pagado, fecha, observacion, agencia, fecha_creacion, usuario_creacion, idcartera, idcartera_pago, estado_pago ,iddetalle_cuenta) 
				VALUES ( ?,?,?,?,?,?,?,NOW(),?,?,?,?,? ) ";
				
				$pr=$connection->prepare($sql);
				$pr->bindParam(1,$numero_cuenta);
				$pr->bindParam(2,$moneda);
				$pr->bindParam(3,$codigo_operacion);
				$pr->bindParam(4,$monto_pagado);
				$pr->bindParam(5,$fecha);
				$pr->bindParam(6,$observacion);
				$pr->bindParam(7,$agencia);
				$pr->bindParam(8,$usuario_creacion);
				$pr->bindParam(9,$idcartera);
				$pr->bindParam(10,$idcartera_pago);
				$pr->bindParam(11,$estado_pago);
				$pr->bindParam(12,$iddetalle_cuenta);     
                               

				if( $pr->execute() ) {
                                            $sqlcuenta="update ca_cuenta set monto_pagado=IFNULL(monto_pagado,0)+IFNULL($monto_pagado,0),estado_pago='$estado_pago',ul_fecha_pago='$fecha'
                                                        where numero_cuenta=( SELECT numero_cuenta FROM  ca_detalle_cuenta WHERE codigo_operacion = '$codigo_operacion' AND idcartera = $idcartera LIMIT 1 ) and idcartera=$idcartera";

                                        $prcuenta=$connection->prepare($sqlcuenta);
                                        if($prcuenta->execute()){
                                            $sqlDetalleCuenta="update ca_detalle_cuenta set monto_pagado=IFNULL(monto_pagado,0)+IFNULL($monto_pagado,0),ul_fecha_pago='$fecha'
                                                                where codigo_operacion='$codigo_operacion' and idcartera=$idcartera";
                                            $prDetalleCuenta=$connection->prepare($sqlDetalleCuenta);
                                            if($prDetalleCuenta->execute()){
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
				
			}else{
				$connection->rollBack();
				return false;
			}
			
		}
		    /*jmore300612*/

}

?>
