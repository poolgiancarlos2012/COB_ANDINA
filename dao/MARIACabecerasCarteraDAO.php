<?php

	class MARIACabecerasCarteraDAO {
		
		public function queryPago ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " SELECT idcabeceras_cartera, nombre
			FROM ca_cabeceras_cartera WHERE idservicio = ? AND estado = 1 AND tipo = 'pago' ";
			
			$idservicio = $dtoCabecerasCartera->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
				
		}
		
		public function queryCartera ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " SELECT idcabeceras_cartera, nombre
			FROM ca_cabeceras_cartera WHERE idservicio = ? AND estado = 1 AND tipo = 'cartera' ";
			
			$idservicio = $dtoCabecerasCartera->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
				
		}
		
		public function queryByService ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " SELECT idcabeceras_cartera, nombre, tipo 
			FROM ca_cabeceras_cartera WHERE idservicio = ? AND estado = 1 ";
			
			$idservicio = $dtoCabecerasCartera->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
			
		}
		
		public function queryById ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " SELECT idcabeceras_cartera, cabeceras, nombre, tipo 
			FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";
			
			$idcabecera = $dtoCabecerasCartera->getId();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idcabecera,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
			
		}
		
		public function delete ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " UPDATE ca_cabeceras_cartera SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = ?
					WHERE idcabeceras_cartera = ? ";
			
			$idcabeceras_cartera = $dtoCabecerasCartera->getId();
			$usuario_modificacion = $dtoCabecerasCartera->getUsuarioModificacion();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$usuario_modificacion,PDO::PARAM_INT);
			$pr->bindParam(2,$idcabeceras_cartera,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				////$connection->rollBack();
				return false;
			}
			
		}
		
		public function update ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " UPDATE ca_cabeceras_cartera 
					SET cabeceras = ?, nombre = ? , tipo = ?, fecha_modificacion = NOW(), usuario_modificacion = ?  
					WHERE idcabeceras_cartera = ? " ;
					
			$cabeceras = $dtoCabecerasCartera->getCabeceras();
			$usuario_modificacion = $dtoCabecerasCartera->getUsuarioModificacion();
			$nombre = $dtoCabecerasCartera->getNombre();
			$tipo = $dtoCabecerasCartera->getTipo();
			$idcabeceras_cartera = $dtoCabecerasCartera->getId();
			
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$cabeceras,PDO::PARAM_STR);
			$pr->bindParam(2,$nombre,PDO::PARAM_STR);
			$pr->bindParam(3,$tipo,PDO::PARAM_STR);
			$pr->bindParam(4,$usuario_modificacion,PDO::PARAM_INT);
			$pr->bindParam(5,$idcabeceras_cartera,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				////$connection->rollBack();
				return false;
			}
			
		}
		
		public function insert ( dto_cabeceras_cartera $dtoCabecerasCartera ) {
			
			$sql = " INSERT INTO ca_cabeceras_cartera ( cabeceras,fecha_creacion,usuario_creacion,idservicio, nombre, tipo ) 
					VALUES( ?,NOW(),?,?,?,? ) " ;
					
			$cabeceras = $dtoCabecerasCartera->getCabeceras();
			$usuario_creacion = $dtoCabecerasCartera->getUsuarioCreacion();
			$idservicio = $dtoCabecerasCartera->getIdServicio();
			$nombre = $dtoCabecerasCartera->getNombre();
			$tipo = $dtoCabecerasCartera->getTipo();
					
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$cabeceras,PDO::PARAM_STR);
			$pr->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
			$pr->bindParam(3,$idservicio,PDO::PARAM_INT);
			$pr->bindParam(4,$nombre,PDO::PARAM_STR);
			$pr->bindParam(5,$tipo,PDO::PARAM_STR);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				//return false;
			}
				
		}
		
	}	

?>
