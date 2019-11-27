<?php
	
	class PGSQL_PDOConsultaDAO {
		
		public function insertConsulta ( dto_consultas $dtoConsulta ) { 
			$sql=" INSERT INTO ca_consultas ( supervisor, asunto, consulta, fecha_consulta, idcliente_cartera, fecha_creacion, usuario_creacion ) 
				VALUES ( ?,?,?,NOW(),?,NOW(),? ) ";
				
			$supervisor=$dtoConsulta->getSupervisor();
			$asunto=$dtoConsulta->getAsunto();
			$consulta=$dtoConsulta->getConsulta(); 
			$cliente_cartera=$dtoConsulta->getIdClienteCartera();
			$usuario_creacion=$dtoConsulta->getUsuarioCreacion();
				
			$factoryConnection= FactoryConnection::create('postgres_pdo');
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
				
			$factoryConnection= FactoryConnection::create('postgres_pdo'); 
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
				
			$factoryConnection= FactoryConnection::create('postgres_pdo'); 
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
		
	}

?>