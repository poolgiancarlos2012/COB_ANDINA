<?php

	class MARIAClaseFinalDAO {
		public function queryAllByIdName ( ) {
			$sql=" SELECT idclase_final,nombre FROM ca_clase_final ";
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function queryJQGRID ( $sidx,$sord,$start,$limit ) {
			$sql=" SELECT idclase_final,IFNULL(nombre,'') AS 'nombre',IFNULL(descripcion,'') AS 'descripcion' 
				FROM ca_clase_final ORDER BY $sidx $sord LIMIT $start , $limit ";
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function COUNT ( ) {
			$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_clase_final ";
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
	}

?>