<?php

	class MARIATramoDAO {
		
		public function queryTramo ( dto_servicio $dtoServicio ) {
			
//			$sql=" SELECT idtramo,tramo,IFNULL(porcentaje_comision,'') AS 'porcentaje_comision' FROM ca_tramo WHERE idcartera = ? ";
			
			$sql=" SELECT idtramo,tramo,IFNULL(porcentaje_comision,'') AS 'porcentaje_comision' FROM ca_tramo WHERE idservicio = ? AND tipo = 'TRAMO' ";
			
			//$cartera=$dtoCartera->getId();
			$servicio = $dtoServicio->getId();
			
			$factoryConnection= FactoryConnection::create('mysql');
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
		
		public function queryGenerico ( dto_servicio $dtoServicio ) {
			
			$sql=" SELECT idtramo,tramo,IFNULL(porcentaje_comision,'') AS 'porcentaje_comision' 
			FROM ca_tramo WHERE idservicio = ? AND tipo = 'GENERICO' AND tramo = 'GENERICO' ";
			
			$servicio = $dtoServicio->getId();
			
			$factoryConnection= FactoryConnection::create('mysql');
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