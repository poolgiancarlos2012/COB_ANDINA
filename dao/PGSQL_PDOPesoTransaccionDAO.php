<?php

	class PGSQL_PDOPesoTransaccionDAO {
		
		public function queryPorIdEstadoTransaccion ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			$sql=" SELECT idpeso_transaccion,peso FROM ca_peso_transaccion WHERE idestado_transaccion = ? AND estado = 1 ";	
			
			$EstadoTransaccion=$dtoEstadoTransaccion->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$EstadoTransaccion);
			if( $pr->execute() ){
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array();
			}
		}
		
		public function insert ( dto_peso_transaccion $dtoPesoTransaccion ) {
			$sql=" INSERT INTO ca_peso_transaccion ( idestado_transaccion , peso , fecha_creacion , usuario_creacion ) 
			VALUES ( ?,?,NOW(),? ) ";
			
			$EstadoTransaccion=$dtoPesoTransaccion->getIdEstadoTransaccion();
			$peso=$dtoPesoTransaccion->getPeso();
			$UsuarioCreacion=$dtoPesoTransaccion->getUsuarioCreacion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$EstadoTransaccion);
			$pr->bindParam(2,$peso);
			$pr->bindParam(3,$UsuarioCreacion);
			if( $pr->execute() ){
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
		}
		
		public function update ( dto_peso_transaccion $dtoPesoTransaccion ) {
			$sql=" UPDATE ca_peso_transaccion SET peso = ? , fecha_modificacion = NOW() , usuario_modificacion = ? 
			WHERE idpeso_transaccion = ? ";
			
			$id=$dtoPesoTransaccion->getId();
			$EstadoTransaccion=$dtoPesoTransaccion->getIdEstadoTransaccion();
			$peso=$dtoPesoTransaccion->getPeso();
			$UsuarioModificacion=$dtoPesoTransaccion->getUsuarioModificacion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$peso);
			$pr->bindParam(2,$UsuarioModificacion);
			$pr->bindParam(3,$id);
			if( $pr->execute() ){
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
		}
		
		public function delete ( dto_peso_transaccion $dtoPesoTransaccion ) {
			$sql=" UPDATE ca_peso_transaccion SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idpeso_transaccion = ? ";
			
			$id=$dtoPesoTransaccion->getId();
			$UsuarioModificacion=$dtoPesoTransaccion->getUsuarioModificacion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$UsuarioModificacion);
			$pr->bindParam(2,$id);
			if( $pr->execute() ){
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
		}
		
		public function checkPeso ( dto_peso_transaccion $dtoPesoTransaccion  ) {
			$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_peso_transaccion WHERE idestado_transaccion = ? AND peso = ? AND estado = 1 ";
			
			$EstadoTransaccion=$dtoPesoTransaccion->getIdEstadoTransaccion();
			$peso=$dtoPesoTransaccion->getPeso();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$EstadoTransaccion);
			$pr->bindParam(2,$peso);
			if( $pr->execute() ){
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array(array('COUNT'=>1));
			}
		}
		
		public function queryById ( dto_peso_transaccion $dtoPesoTransaccion ) {
			
			$sql=" SELECT idpeso_transaccion,peso FROM ca_peso_transaccion WHERE idpeso_transaccion = ? ";
			
			$id=$dtoPesoTransaccion->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$id);
			if( $pr->execute() ){
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array();
			}
		}
			
	}

?>

