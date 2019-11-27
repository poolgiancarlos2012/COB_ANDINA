<?php
	
	class MARIAAyudaGestionUsuarioDAO {
		
		public function queryUsuarioAyudar ( dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {
			
			$servicio=$dtoServicio->getId();
			$cartera=$dtoClienteCartera->getIdCartera();
			$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio(); 
			
			$sql=" SELECT usu.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS operador,
				IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 AND id_ultima_visita=0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
				IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 OR id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND ususer.idusuario_servicio IN 
				( SELECT idusuario_servicio_ayuda_gestion FROM ca_ayuda_gestion_usuario WHERE idcartera = ? AND idusuario_servicio = ? AND estado = 1 ) ORDER BY 3 "; 
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			////$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$cartera);
			$pr->bindParam(2,$cartera);
			$pr->bindParam(3,$cartera);
			$pr->bindParam(4,$servicio);
			$pr->bindParam(5,$cartera);
			$pr->bindParam(6,$UsuarioServicio);
			//print $sql;
			if( $pr->execute() ){ 
				////$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
		}
		
		public function queryUsuarioAsignar ( dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {
			
			$servicio=$dtoServicio->getId();
			$cartera=$dtoClienteCartera->getIdCartera();
			$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio(); 
			
			$sql=" SELECT * FROM (
				SELECT usu.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS operador,
				IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 AND id_ultima_visita=0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
				IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 OR id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND ususer.idusuario_servicio NOT IN 
				( SELECT idusuario_servicio_ayuda_gestion FROM ca_ayuda_gestion_usuario WHERE idcartera = ? AND idusuario_servicio = ? AND estado = 1 ) 
				)TMP WHERE TMP.clientes_asignados>0 ORDER BY 3 ";
		  	
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			////$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$cartera);
			$pr->bindParam(2,$cartera);
			$pr->bindParam(3,$cartera);
			$pr->bindParam(4,$servicio);
			$pr->bindParam(5,$cartera);
			$pr->bindParam(6,$UsuarioServicio);
			if( $pr->execute() ){ 
				////$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
		}
		
		public function queryUsuarioAsignarConDistr ( dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {

			$servicio=$dtoServicio->getId();
			$cartera=$dtoClienteCartera->getIdCartera();
			$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio(); 

			$sql=" SELECT 
				usu.idusuario,
				ususer.idusuario_servicio,
				CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS operador,
				IFNULL( SUM(IF(clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0,1,0)) ,0) AS 'clientes_sin_gestionar',
				IFNULL( SUM(IF(clicar.id_ultima_llamada<>0 OR clicar.id_ultima_visita<>0,1,0)) ,0) AS 'clientes_gestionados',
				COUNT(*) AS 'clientes_asignados'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idusuario_servicio = ususer.idusuario_servicio AND ususer.idusuario=usu.idusuario
				WHERE clicar.idcartera = ? AND ususer.idservicio=? AND ususer.estado=1 and clicar.estado=1
				GROUP BY clicar.idusuario_servicio 
				ORDER BY 3 ";

			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();

			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$cartera);
			$pr->bindParam(2,$servicio);
			if( $pr->execute() ){ 
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				return array();
			}
		}
		
		public function insertMasivo ( $ids , dto_cliente_cartera $dtoClienteCartera ) {
			
			$cartera=$dtoClienteCartera->getIdCartera();
			$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio();
			$UsuarioCreacion=$dtoClienteCartera->getUsuarioCreacion();

			$sql=" INSERT INTO ca_ayuda_gestion_usuario ( idusuario_servicio , idusuario_servicio_ayuda_gestion , idcartera , fecha_creacion , usuario_creacion  ) 
			SELECT $UsuarioServicio,idusuario_servicio,$cartera,NOW(),$UsuarioCreacion FROM ca_usuario_servicio WHERE idusuario_servicio IN ( $ids ) ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			if( $pr->execute() ){ 
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
		}
		
		public function deleteMasivo ( $ids , dto_cliente_cartera $dtoClienteCartera ) {
			
			$cartera=$dtoClienteCartera->getIdCartera();
			$UsuarioModificacion=$dtoClienteCartera->getUsuarioModificacion();
			
			$sql=" UPDATE ca_ayuda_gestion_usuario SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = ? 
			WHERE idcartera = ? AND idusuario_servicio_ayuda_gestion IN ( $ids )  ";
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$UsuarioModificacion);
			$pr->bindParam(2,$cartera);
			if( $pr->execute() ){ 
				//$connection->commit();
				return true;
			}else{
				//$connection->rollBack();
				return false;
			}
		}
		
		public function queryListarUsuariosAsignados ( dto_cliente_cartera $dtoClienteCartera,$idusuario_servicio ) {
			$cartera=$dtoClienteCartera->getIdCartera();
			$sql=" SELECT idusuario_servicio_ayuda_gestion AS 'idusuario_servicio', 
			( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
			ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio = idusuario_servicio_ayuda_gestion LIMIT 1 ) AS 'nombre' 
			FROM ca_ayuda_gestion_usuario WHERE idcartera IN (".$cartera.") 
			AND estado = 1 AND idusuario_servicio=".$idusuario_servicio."
			ORDER BY 2 ";
			$cartera=$dtoClienteCartera->getIdCartera();
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			////$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			//$pr->bindParam(1,$cartera);
			if( $pr->execute() ) {
				////$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				////$connection->rollBack();
				return array();
			}
		}
		
	}

?>
