<?php

	class MARIARefinanciamientoDAO {
	
		public function insert ( dto_refinanciamiento $dtoRefinanciamiento, $cuenta ) {
		
			$idcliente_cartera = $dtoRefinanciamiento->getIdClienteCartera();
			$idusuario_servicio = $dtoRefinanciamiento->getIdUsuarioServicio();
			$idtelefono = $dtoRefinanciamiento->getIdTelefono();
			$idfinal = $dtoRefinanciamiento->getIdFinal();
			$objecion = $dtoRefinanciamiento->getObjecion();
			$observacion = $dtoRefinanciamiento->getObservacion();
			$total_deuda = $dtoRefinanciamiento->getTotalDeuda();
			$numero_cuota = $dtoRefinanciamiento->getNumeroCuota();
			$tipo_cuota = $dtoRefinanciamiento->getTipoCuota();
			$monto_cuota = $dtoRefinanciamiento->getMontoCuota();
			$usuario_creacion = $dtoRefinanciamiento->getUsuarioCreacion();
			$fecha = date("Y-m-d H:i:s");
			
			$factoryConnection = FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			for ($i = 0; $i < count($cuenta); $i++) {
				
				$sql = " INSERT INTO ca_refinanciamiento ( idcliente_cartera, idcuenta, idtelefono, fecha, idfinal, objecion, total_deuda, numero_cuota, monto_cuota, idusuario_servicio, observacion, usuario_creacion, tipo_cuota, fecha_creacion ) 
						VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,NOW() ) ";
						
				$pr = $connection->prepare($sql);
				$pr->bindParam(1,$idcliente_cartera);
				$pr->bindParam(2,$cuenta[$i]['Cuenta']);
				$pr->bindParam(3,$idtelefono);
				$pr->bindParam(4,$fecha);
				$pr->bindParam(5,$idfinal);
				$pr->bindParam(6,$objecion);
				$pr->bindParam(7,$total_deuda);
				$pr->bindParam(8,$numero_cuota);
				$pr->bindParam(9,$monto_cuota);
				$pr->bindParam(10,$idusuario_servicio);
				$pr->bindParam(11,$observacion);
				$pr->bindParam(12,$usuario_creacion);
				$pr->bindParam(13,$tipo_cuota);
				
				if( $pr->execute() ) {
				
				}else{
					return false;
					exit();
				}
				
				
			}
			
			return true;
			
		}/*jmore050712*/
		public function create ( dto_refinanciamiento $dtoRefinanc ) {

			$deuda = $dtoRefinanc->getTotalDeuda();
			$descuento = $dtoRefinanc->getDescuento();
			$n_cuotas = $dtoRefinanc->getNumeroCuota();
			$tipo_monto = $dtoRefinanc->getTipoCuota();
			$monto_pago = $dtoRefinanc->getMontoCuota();
			$observacion = $dtoRefinanc->getObservacion();
			$numero_cuenta = $dtoRefinanc->getNumeroCuenta();
			$moneda = $dtoRefinanc->getMoneda();
			$idcliente_cartera = $dtoRefinanc->getIdClienteCartera();
			$idcliente = $dtoRefinanc->getIdCliente();
			$usuario_creacion = $dtoRefinanc->getUsuarioCreacion();
			$idusuario_servicio = $dtoRefinanc->getIdUsuarioServicio();
			
			$sql = " INSERT INTO ca_refinanciamiento 
				( fecha, total_deuda, descuento, numero_cuota, tipo_cuota, monto_cuota, observacion, numero_cuenta, moneda, idcliente_cartera, idcliente, idusuario_servicio, usuario_creacion, fecha_creacion ) 
				VALUES ( NOW(),?,?,?,?,?,?,?,?,?,?,?,?,NOW() )";

			$factoryConnection= FactoryConnection::create('mysql');
        	$connection = $factoryConnection->getConnection();

        	$connection->beginTransaction();
        	$pr = $connection->prepare( $sql );
        	$pr->bindParam(1,$deuda);
        	$pr->bindParam(2,$descuento);
        	$pr->bindParam(3,$n_cuotas);
        	$pr->bindParam(4,$tipo_monto);
        	$pr->bindParam(5,$monto_pago);
        	$pr->bindParam(6,$observacion);
        	$pr->bindParam(7,$numero_cuenta);
        	$pr->bindParam(8,$moneda);
        	$pr->bindParam(9,$idcliente_cartera);
        	$pr->bindParam(10,$idcliente);
        	$pr->bindParam(11,$idusuario_servicio);
        	$pr->bindParam(12,$usuario_creacion);
        	if( $pr->execute() ) {
        		$connection->commit();
        		return true;
        	}else{
        		$connection->rollBack();
        		return false;
        	}

		}  /*jmore050712*/ 
                
	
	}

?>