<?php

class MARIAServicioDAO {
        public function update ( dto_servicio $dto ) {
		$sql=" UPDATE ca_servicio SET nombre=? ,descripcion=? ,estado=? ,fecha_ceacion=? ,fecha_modificacion=? ,usuario_creacion=? ,usuario_modificacion=?
			WHERE idservicio=? ";
			
		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
		$pr=$connection->prepare($sql);
		$pr->bindParam(1,$dto->getNombre());
		$pr->bindParam(2,$dto->getDescripcion());
		$pr->bindParam(3,$dto->getEstado());
		$pr->bindParam(4,$dto->getFechaCreacion());
		$pr->bindParam(5,$dto->getFechaModificacion());
		$pr->bindParam(6,$dto->getUsuarioCreacion());
		$pr->bindParam(7,$dto->getUsuarioModificacion());
		$pr->bindParam(8,$dto->getId());
		return $pr->execute();
	}
	
	public function updateNameDescriptionModification ( dto_servicio $dto ) {
		$sql=" UPDATE ca_servicio SET nombre=? ,descripcion=? ,fecha_modificacion=NOW() ,usuario_modificacion=?
			WHERE idservicio=? ";
		
		$nombre=$dto->getNombre();
		$descripcion=$dto->getDescripcion();
		$UsuarioModificacion=$dto->getUsuarioModificacion();
		$id=$dto->getId();
			
		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		$pr->bindParam(1,$nombre);
		$pr->bindParam(2,$descripcion);
		$pr->bindParam(3,$UsuarioModificacion);
		$pr->bindParam(4,$id);
		$rs=$pr->execute();
		if( $rs ){
			//$connection->commit();
			return true;	
		}else{
			//$connection->rollBack();
			return false;
		}
	}
	
	public function delete ( dto_servicio $dto ) {
		$sql=" UPDATE ca_servicio SET estado=0 , usuario_modificacion=? , fecha_modificacion=NOW() WHERE idservicio=? ";
		
		$id=$dto->getId();
		$UsuarioModificacion=$dto->getUsuarioModificacion();
		
		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
        $pr->bindParam(1,$UsuarioModificacion);
		$pr->bindParam(2,$id);
		$rs=$pr->execute();
		if( $rs ){
			//$connection->commit();
			return true;
		}else{
			//$connection->rollBack();
			return false;
		}
	}
	
	public function insert ( dto_servicio $dto ) { 
		$sql=" INSERT INTO ca_servicio (nombre, descripcion, estado, fecha_creacion, fecha_modificacion, usuario_creacion, usuario_modificacion) 
			VALUES (?,?,?,?,?,?,?) ";

		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
		$pr=$connection->prepare($sql);
		$pr->bindParam(1,$dto->getNombre());
		$pr->bindParam(2,$dto->getDescripcion());
		$pr->binbParam(3,$dto->getEstado());
		$pr->bindParam(4,$dto->getFechaCreacion());
		$pr->bindParam(5,$dto->getFechaModificacion());
		$pr->bindParam(6,$dto->getUsuarioCreacion());
		$pr->bindParam(7,$dto->getUsuarioModificacion());
		return $pr->execute();
	}
	
	public function insertNameDescriptionCreation ( dto_servicio $dto ) {
		$sql=" INSERT INTO ca_servicio (nombre, descripcion, estado, fecha_creacion, usuario_creacion)
			VALUES (:nombre,:descripcion,1,NOW(),:usuario_creacion) ";
			
		$nombre=$dto->getNombre();
		$descripcion=$dto->getDescripcion();
		$fecha_creacion=$dto->getFechaCreacion();
		$usuario_creacion=$dto->getUsuarioCreacion();
		
		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
                
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		$pr->bindParam(':nombre',$nombre,PDO::PARAM_STR);
		$pr->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
		$pr->bindParam(':usuario_creacion',$usuario_creacion,PDO::PARAM_INT);
		
		if($pr->execute()){
			////$connection->commit();
			
			$idservicio = $connection->lastInsertId(); 
						
			$sqlUS = " INSERT INTO ca_usuario_servicio ( idusuario, idservicio, idprivilegio, idtipo_usuario, fecha_inicio, fecha_fin, fecha_creacion, usuario_creacion ) 
			VALUES( 1,?,3,4,'2010-01-01','2020-12-31',NOW(),? )";
			
			$prUS = $connection->prepare($sqlUS);
			$prUS->bindParam(1,$idservicio,PDO::PARAM_INT);
			$prUS->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
			if( $prUS->execute() ) {
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
			//return true;
		}else{
			//$connection->rollBack();
			return false;
		}

	}
	
	public function queryAll ( ) {
		$sql=" SELECT idservicio, nombre, descripcion, estado, fecha_creacion, fecha_modificacion, usuario_creacion, 
			usuario_modificacion FROM ca_servicio ";
		
		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();	
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		if( $pr->execute() ){
			return json_encode($pr->fetchAll(PDO::FETCH_ASSOC));
		}else{
			return array();
		}
		
	}

	public function queryIdName ( ) {
		$sql=" SELECT idservicio AS id , nombre FROM ca_servicio WHERE estado=1 AND TRIM(nombre) NOT IN ('cobrast') ";
		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		if( $pr->execute() ){
			//$connection->commit();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			//$connection->rollBack();
			return array();
		}
	}
	
	public function queryIdNameAll ( ) {
		$sql=" SELECT idservicio AS id , nombre FROM ca_servicio WHERE estado=1 ";
		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		if( $pr->execute() ){
			//$connection->commit();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			//$connection->rollBack();
			return array();
		}
	}
	
	public function queryById ( dto_servicio $dto ) {
		$sql=" SELECT idservicio,nombre,descripcion,usuario_creacion,(SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario=ca_servicio.usuario_creacion) as nombre_usuario_creacion FROM ca_servicio WHERE estado=1 AND idservicio=? ";
		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		
		$id=$dto->getId();
		
		$pr->bindParam(1,$id);
		if( $pr->execute() ){
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return array();
		}
	}
	
	public function COUNT ( ) {
		$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_servicio WHERE estado=1 ";
		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();	
		
		//$connection->beginTransaction();
		
		$pr=$connection->prepare($sql);
		if( $pr->execute() ){
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return array(array('COUNT'=>0));
		}
	}
	
	public function executeString ( $sql ) {
		$factoryConnection= FactoryConnection::create('mysql');	
		$connection = $factoryConnection->getConnection();
		$pr=$connection->prepare($sql);
		$pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
	}

}

?>

