<?php

	class PGSQL_PDOCarteraPagoDAO {
		
		public function queryCarteraMetaData ( dto_cartera $dtoCartera ) {
			
			$sql=" SELECT idcartera_pago,tabla,codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago
			FROM ca_cartera_pago 
			WHERE idcartera = ? ORDER BY idcartera_pago DESC LIMIT 1 ";
			
			$idcartera = $dtoCartera->getId(); 
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
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