<?php

	class PGSQL_PDOTipoUsuarioDAO {
		public function queryNotAdmin ( ) {
			$sql=" SELECT idtipo_usuario AS id,nombre FROM ca_tipo_usuario WHERE estado=1 AND nombre IN ('OPERADOR','GESTOR DE CAMPO') ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function queryByIdName ( ) {
			$sql=" SELECT idtipo_usuario AS id,nombre FROM ca_tipo_usuario WHERE estado=1 ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function queryAdmin ( )	{
			$sql=" SELECT idtipo_usuario AS id,nombre FROM ca_tipo_usuario WHERE estado=1 ";
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
	}

?>