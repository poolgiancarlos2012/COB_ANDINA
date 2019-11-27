<?php

	class MARIAZonaDAO {
		
		public function insertDepartamentos ( $idservicio, $usuario_creacion, $idcartera ) {
			
			$sql = " INSERT IGNORE INTO ca_zona ( departamento, idservicio, usuario_creacion, fecha_creacion ) 
			SELECT TRIM(departamento), ".$idservicio.", ".$usuario_creacion.", NOW() 
			FROM ca_direccion WHERE idcartera = ? AND TRIM(departamento)!='' 
			AND ISNULL(departamento)=0 GROUP BY TRIM(departamento) ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
			if( $pr->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
			
		}
		
		public function updateByService ( $idcartera, $data ) {
		
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			for( $i=0;$i<count($data);$i++ ) {
				
				$sql = " UPDATE ca_zona SET grupo = ? WHERE idzona = ? ";
				$pr = $connection->prepare($sql);
				$pr->bindParam(1,$data[$i]['zona'],PDO::PARAM_STR);
				$pr->bindParam(2,$data[$i]['idzona'],PDO::PARAM_INT);
				if( $pr->execute() ) {

				}else{
					//$connection->rollBack();
					return false;
					exit();
				}
				
				$sqlUpdateDireccion = " UPDATE ca_direccion SET grupo = ? 
					WHERE idcartera = ? 
					AND TRIM(departamento) = ( SELECT departamento FROM ca_zona WHERE idzona = ? ) ";
				$prUD = $connection->prepare($sqlUpdateDireccion);
				$prUD->bindParam(1,$data[$i]['zona'],PDO::PARAM_STR);
				$prUD->bindParam(2,$idcartera,PDO::PARAM_INT);
				$prUD->bindParam(3,$data[$i]['idzona'],PDO::PARAM_INT);
				if( $prUD->execute() ) {

				}else{
					//$connection->rollBack();
					return false;
					exit();
				}
				
			}
			
			//$connection->commit();
			return true;
			
		}
		
		public function queryByService ( $idservicio ) {
			
			$sql = " SELECT idzona, IFNULL(departamento,'') AS 'departamento', IFNULL(zona,'') AS 'zona' 
			FROM ca_zona WHERE idservicio = ? AND estado = 1 ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
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