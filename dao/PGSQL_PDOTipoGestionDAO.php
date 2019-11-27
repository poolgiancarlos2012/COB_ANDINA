<?php

	class PGSQL_PDOTipoGestionDAO {
		public function queryIdName ( ) {
			$sql=" SELECT idtipo_gestion,nombre FROM ca_tipo_gestion ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function queryJQGRID ( $sidx,$sord,$start,$limit ) {
			$sql=" SELECT idtipo_gestion, IFNULL(nombre,'') AS 'nombre', IFNULL(descripcion,'') AS 'descripcion' 
				FROM ca_tipo_gestion ORDER BY $sidx $sord LIMIT $start , $limit ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function COUNT ( ) {
			$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_tipo_gestion ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);	
		}
	}

?>