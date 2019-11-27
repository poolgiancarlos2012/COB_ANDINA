<?php

	class PGSQL_PDOEstadoTransaccionDAO {
		
		public function queryByService ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			$sql=" SELECT idestado_transaccion, nombre FROM ca_estado_transaccion WHERE estado = 1 AND idservicio = ? AND idtipo_transaccion = ? ";
			
			$servicio=$dtoEstadoTransaccion->getIdServicio();
			$tipo=$dtoEstadoTransaccion->getIdTipoTransaccion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			$pr->bindParam(2,$tipo);
			if( $pr->execute() ){
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array();
			}
			
		}
		
		public function insert ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			
			$sql=" INSERT INTO ca_estado_transaccion ( idservicio , idtipo_transaccion , nombre, peso, descripcion, fecha_creacion, usuario_creacion ) 
			VALUES ( ?,?,?,?,?,NOW(),? ) ";
			
			$servicio=$dtoEstadoTransaccion->getIdServicio();
			$tipo=$dtoEstadoTransaccion->getIdTipoTransaccion();
			$nombre=$dtoEstadoTransaccion->getNombre();
			$peso=$dtoEstadoTransaccion->getPeso();
			$descripcion=$dtoEstadoTransaccion->getDescripcion();
			$UsuarioCreacion=$dtoEstadoTransaccion->getUsuarioCreacion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			$pr->bindParam(2,$tipo);
			$pr->bindParam(3,$nombre);
			$pr->bindParam(4,$peso);
			$pr->bindParam(5,$descripcion);
			$pr->bindParam(6,$UsuarioCreacion);
			if( $pr->execute() ){ 
				//$connection->commit(); 
				return true;
			}else{ 
				//$connection->rollBack(); 
				return false;
			}
			
		}
		
		public function update ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			
			$sql=" UPDATE ca_estado_transaccion SET idtipo_transaccion = ?, nombre = ?, peso = ?, descripcion = ?, fecha_modificacion = NOW() , usuario_modificacion = ?
			WHERE idestado_transaccion = ? ";
			
			$id=$dtoEstadoTransaccion->getId();
			$tipo=$dtoEstadoTransaccion->getIdTipoTransaccion();
			$nombre=$dtoEstadoTransaccion->getNombre();
			$peso=$dtoEstadoTransaccion->getPeso();
			$descripcion=$dtoEstadoTransaccion->getDescripcion();
			$UsuarioModificacion=$dtoEstadoTransaccion->getUsuarioModificacion();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$tipo);
			$pr->bindParam(2,$nombre);
			$pr->bindParam(3,$peso);
			$pr->bindParam(4,$descripcion);
			$pr->bindParam(5,$UsuarioModificacion);
			$pr->bindParam(6,$id);
			if( $pr->execute() ){ 
				//$connection->commit(); 
				return true;
			}else{ 
				//$connection->rollBack(); 
				return false;
			}
		}
		
		public function delete ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			$sql=" UPDATE ca_estado_transaccion SET estado=0 , fecha_modificacion = NOW() , usuario_modificacion = ? WHERE idestado_transaccion = ? ";
			
			$id=$dtoEstadoTransaccion->getId();
			$UsuarioModificacion=$dtoEstadoTransaccion->getUsuarioModificacion();
			
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
		
		public function checkPeso ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			
			$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_estado_transaccion WHERE idservicio = ? AND idtipo_transaccion = ? AND peso = ? AND estado = 1 ";
			
			$servicio=$dtoEstadoTransaccion->getIdServicio();
			$tipo=$dtoEstadoTransaccion->getIdTipoTransaccion();
			$peso=$dtoEstadoTransaccion->getPeso();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			$pr->bindParam(2,$tipo);
			$pr->bindParam(3,$peso);
			if( $pr->execute() ){ 
				//$connection->commit(); 
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{ 
				//$connection->rollBack(); 
				return array(array('COUNT'=>1));
			}
		}
		
		public function queryById ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			
			$sql=" SELECT idestado_transaccion,nombre,peso,IFNULL(descripcion,'') AS 'descripcion',idtipo_transaccion 
			FROM ca_estado_transaccion WHERE idestado_transaccion = ? ";
			
			$id=$dtoEstadoTransaccion->getId();
			
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
				return array(array('COUNT'=>1));
			}
		}
		
		public function queryEstadosPrioridadPorServicio ( dto_estado_transaccion $dtoEstadoTransaccion ) {
			$sql=" SELECT idestado_transaccion,nombre,peso,IFNULL(descripcion,'') AS 'descripcion',DATE(fecha_creacion) AS 'fecha_registro',
				( SELECT GROUP_CONCAT(peso) FROM ca_peso_transaccion WHERE estado = 1 AND idestado_transaccion = es.idestado_transaccion  ) AS 'prioridad'
				FROm ca_estado_transaccion es WHERE idservicio = ? AND estado = 1 ";
			
			$servicio=$dtoEstadoTransaccion->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
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