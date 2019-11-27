<?php
	
	class MARIAConsultaDAO {
		
		public function insertConsulta ( dto_consultas $dtoConsulta ) { 
			$sql=" INSERT INTO ca_consultas ( supervisor, asunto, consulta, fecha_consulta, idcliente_cartera, fecha_creacion, usuario_creacion ) 
				VALUES ( ?,?,?,NOW(),?,NOW(),? ) ";
				
			$supervisor=$dtoConsulta->getSupervisor();
			$asunto=$dtoConsulta->getAsunto();
			$consulta=$dtoConsulta->getConsulta(); 
			$cliente_cartera=$dtoConsulta->getIdClienteCartera();
			$usuario_creacion=$dtoConsulta->getUsuarioCreacion();
				
			$factoryConnection= FactoryConnection::create('mysql');
	        $connection = $factoryConnection->getConnection();
			
    	    //$connection->beginTransaction();
			
        	$pr=$connection->prepare($sql);
			$pr->bindParam(1,$supervisor);
			$pr->bindParam(2,$asunto);
			$pr->bindParam(3,$consulta);
			$pr->bindParam(4,$cliente_cartera);
			$pr->bindParam(5,$usuario_creacion);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;	
			}
		}
		
		public function insertRespuesta ( dto_consultas $dtoConsulta ) {
			$sql=" INSERT INTO ca_consultas ( idconsulta, respuesta, fecha_modificacion, usuario_modificacion ) 
				VALUES ( ?,?,NOW(),? ) ";
				
			$id=$dtoConsulta->getId();
			$respuesta=$dtoConsulta->getRespuesta();
			$usuario_modificacion=$dtoConsulta->getUsuarioModificacion();
				
			$factoryConnection= FactoryConnection::create('mysql'); 
	        $connection = $factoryConnection->getConnection(); 
			
    	    //$connection->beginTransaction();
			
        	$pr=$connection->prepare($sql);
			$pr->bindParam(1,$id);
			$pr->bindParam(2,$respuesta);
			$pr->bindParam(5,$usuario_modificacion);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;	
			}
		}
		
		public function queryRespondidos ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql=" SELECT cons.idconsultas,cons.asunto FROM ca_consultas cons INNER JOIN ca_cliente_cartera clicar
				ON clicar.idcliente_cartera=cons.idcliente_cartera 
				WHERE clicar.idcartera = ? AND cons.estado=1 AND cons.respondido=1 ";
				
			$cartera=$dtoClienteCartera->getIdCartera();
				
			$factoryConnection= FactoryConnection::create('mysql'); 
	        $connection = $factoryConnection->getConnection(); 
			
    	    //$connection->beginTransaction();
			
        	$pr=$connection->prepare($sql);
			$pr->bindParam(1,$cartera);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;	
			}
		}

		//~ Vic I
		public function krySaldoInicial ( dto_cliente_cartera $dtoClienteCartera ) {
			//~ Filtro es Codigo Contrato, Codigo Cliente y Codigo de Cartera ordenado por Fecha de Proceso STR_TO_DATE(h.fproceso,'%d-%b-%Y')
			$sql = " SELECT DISTINCT h.divisa, CAST(h.saldohoy AS DECIMAL(12,2)) AS saldoHoy, h.diavenc "
						.",h.fincumpli AS cumpli, h.fproceso, h.producto, h.nom_subprod AS subProducto "
					."FROM ca_cliente_cartera cc "
						."INNER JOIN ca_historial h ON cc.idcliente_cartera=h.idcliente_cartera "
					."WHERE cc.idcliente_cartera = ? AND h.contrato = ? ORDER BY h.datesys DESC ";

			$var = explode("^^",$dtoClienteCartera->getId());
			$id_cliente_cartera = $var[1];
			$id_nro_contrato = $var[0];

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			$pr->bindParam(2, $id_nro_contrato, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}

		public function krySaldoInicialNroContrato ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql = " SELECT DISTINCT CONCAT_WS('^^',contrato,idcliente_cartera) AS valor, contrato FROM ca_historial WHERE idcliente_cartera = ? ";

			$id_cliente_cartera = $dtoClienteCartera->getId();

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}

		public function kryCuotasNroContrato ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql = " SELECT DISTINCT CONCAT_WS('^^',idcuenta,idcliente_cartera) AS valor, num_contrato AS contrato FROM ca_cuotas WHERE idcliente_cartera= ? ";

			$id_cliente_cartera = $dtoClienteCartera->getId();

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}

		public function kryCuotasPendientes ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql = " SELECT DISTINCT DATE_FORMAT(fecha_vencim,'%d-%m-%Y') AS fecha_vencims, deuda_impagocap, deuda_impagoint, deuda_impago, deuda_impagocom, moneda "
					.", (deuda_impagocap + deuda_impagoint + deuda_impago) AS total "
					."FROM ca_cuotas WHERE idcliente_cartera=? AND idcuenta=? AND estado=1 ORDER BY fecha_vencim DESC ";

			$var = explode("^^",$dtoClienteCartera->getId());
			$id_cuenta = $var[0];
			$id_cliente_cartera = $var[1];

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			$pr->bindParam(2, $id_cuenta, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}

		public function kryFiadoresNroContrato ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql = " SELECT DISTINCT CONCAT_WS('^^',idcuenta,idcliente_cartera) AS valor, num_contrato AS contrato FROM ca_fiadores WHERE idcliente_cartera= ? ";

			$id_cliente_cartera = $dtoClienteCartera->getId();

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}

		public function kryFiadoresPendientes ( dto_cliente_cartera $dtoClienteCartera ) {
			$sql = " SELECT DISTINCT num_contratogar, tipo_gar, subtipo_gar, mon_gar, imp_gar, sit_gar, DATE_FORMAT(fecha_sit,'%d-%m-%Y') AS fecha_sit, direcc_inmueblehip, "
						."placa_vehiculoprend, cod_centralfiador, nombre_fiador, direcc_fiador, ciudad, cod_postal, provincia, tel_particular, tel_trabajo, tel_movil, tel_4, tel_5 "
					."FROM ca_fiadores "
					."WHERE idcliente_cartera=? AND idcuenta=? AND estado=1 ORDER BY fecha_sit ASC";

			$var = explode("^^",$dtoClienteCartera->getId());
			$id_cuenta = $var[0];
			$id_cliente_cartera = $var[1];

			$factoryConnection = FactoryConnection::create('mysql'); 
			$connection = $factoryConnection->getConnection(); 

			$pr = $connection->prepare($sql);
			$pr->bindParam(1, $id_cliente_cartera, PDO::PARAM_INT);
			$pr->bindParam(2, $id_cuenta, PDO::PARAM_INT);
			if ($pr->execute()) {
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return array();
			}
		}
		//~ Vic F

	}

?>
