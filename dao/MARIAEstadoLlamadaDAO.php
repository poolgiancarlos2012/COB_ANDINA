<?php

	class MARIAEstadoLlamadaDAO {
		
		public function queryByService ( dto_estado_llamada $dtoEstadoLlamada ) {
			$sql=" SELECT idestado_llamada, nombre FROM ca_estado_llamada WHERE idservicio = ? ";
			
			$servicio=$dtoEstadoLlamada->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('mysql');
	        $connection = $factoryConnection->getConnection();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
			
		}
			
	}

?>