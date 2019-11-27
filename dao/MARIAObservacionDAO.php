<?php

	class MARIAObservacionDAO {
	 	
		public function insert ( dto_observacion $dtoObservacion ) {
			
			$sql=" INSERT INTO ca_observacion ( observacion, fecha_creacion, usuario_creacion, idcliente ) 
				VALUES ( ?,NOW(),?,? ) ";
				
			$observacion = $dtoObservacion->getObservacion();
			$idcliente = $dtoObservacion->getIdCliente();
			$usuario_creacion = $dtoObservacion->getUsuarioCreacion(); 
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$observacion,PDO::PARAM_STR);
			$pr->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
			$pr->bindParam(3,$idcliente,PDO::PARAM_INT);
			if( $pr->execute() ){
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
			
		}
		
		public function query ( dto_observacion $dtoObservacion ) {
			
			$sql = " SELECT idobservacion, observacion, ( SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario = usuario_creacion ) AS 'usuario' 
				FROM ca_observacion WHERE idcliente = ? AND estado = 1 ";
			
			$idcliente = $dtoObservacion->getIdCliente();
				
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$idcliente,PDO::PARAM_STR);
			if( $pr->execute() ) {
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array();
			}
			
		}
		
	}

?>