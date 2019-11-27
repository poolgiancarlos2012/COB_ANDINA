<?php

	class PGSQL_PDOTramoDAO {
		
		public function queryTramo ( dto_servicio $dtoServicio ) {
			
			$sql=" SELECT idtramo,tramo,COALESCE(porcentaje_comision,0) AS porcentaje_comision FROM ca_tramo WHERE idservicio = ? AND tipo = 'TRAMO' ";
			
			$servicio = $dtoServicio->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
    	    $pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			if( $pr->execute() ) {
				
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				
				return array();
			}
			
		}
		
		public function queryGenerico ( dto_servicio $dtoServicio ) {
			
			/****** postgres_pdo *****/
			/*$sql=" SELECT idtramo,tramo,IFNULL(porcentaje_comision,'') AS 'porcentaje_comision' 
			FROM ca_tramo WHERE idservicio = ? AND tipo = 'GENERICO' AND tramo = 'GENERICO' ";*/
			/********* POSTGRES *********/
			$sql=" SELECT idtramo,tramo,COALESCE(porcentaje_comision,0) AS porcentaje_comision
			FROM ca_tramo WHERE idservicio = ? AND tipo = 'GENERICO' AND tramo = 'GENERICO' ";
			
			$servicio = $dtoServicio->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');
	        $connection = $factoryConnection->getConnection();
			
    	    //$connection->beginTransaction();
			
        	$pr=$connection->prepare($sql);
			//$pr->bindParam(1,$cartera);
			$pr->bindParam(1,$servicio);
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