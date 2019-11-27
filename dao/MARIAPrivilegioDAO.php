<?php

	class MARIAPrivilegioDAO {
		public function queryNotAdmin ( ) {
			$sql=" SELECT idprivilegio AS id,nombre FROM ca_privilegio 
			WHERE TRIM(nombre) IN ('operador','gestor de campo') ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		public function queryByIdName ( ) {
			$sql=" SELECT idprivilegio AS id,nombre FROM ca_privilegio WHERE estado=1 ";
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		public function queryAdmin ( ) {
			$sql=" SELECT idprivilegio AS id,nombre FROM ca_privilegio ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
	}

?>