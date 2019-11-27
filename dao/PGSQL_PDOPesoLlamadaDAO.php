<?php

	class PGSQL_PDOPesoLlamadaDAO {
		
		public function queryPorIdEstadoLlamada ( dto_estado_llamada $dtoEstadoLlamada ) {
			$sql=" SELECT idpeso_llamada,peso FROM ca_peso_llamada WHERE idestado_llamada = ? ";	
			
			$EstadoLlamada=$dtoEstadoLlamada->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$EstadoLlamada);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
			
	}

?>