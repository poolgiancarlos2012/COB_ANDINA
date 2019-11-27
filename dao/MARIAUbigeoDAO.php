<?php

	class MARIAUbigeoDAO {
	
		public function queryDepartamento ( ) {
			
			$sql = " SELECT DISTINCT departamento FROM ca_ubigeo_bbva WHERE departamento IS NOT NULL ORDER BY 1 ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			$pr = $connection->prepare( $sql );
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
			
		}
		
		public function queryProvincia ( $depart  ) {
			
			$sql = " SELECT DISTINCT provincia FROM ca_ubigeo_bbva WHERE departamento = ? AND provincia IS NOT NULL ORDER BY 1 ";

			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			$pr = $connection->prepare( $sql );
			$pr->bindParam( 1, $depart, PDO::PARAM_STR );
			$pr->execute();
			$data = array();
			while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
				array_push( $data, array( "provincia"=>utf8_encode( $row['provincia'] ) ) );
			}
			return $data;
			
		}
		
		public function queryDistrito ( $depart, $prov ) {
			
			$sql = " SELECT DISTINCT distrito FROM ca_ubigeo_bbva WHERE departamento = ? AND provincia = ? AND distrito IS NOT NULL ORDER BY 1 ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();

			$pr = $connection->prepare( $sql );
			$pr->bindParam( 1, $depart, PDO::PARAM_STR );
			$pr->bindParam( 2, utf8_decode($prov) , PDO::PARAM_STR );
			$pr->execute();
			$data = array();
			while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
				array_push( $data, array( "distrito"=>utf8_encode( $row['distrito'] ) ) );
			}
			return $data;
			
		}
	
	}

?>