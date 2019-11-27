<?php

class MARIAUsuarioDAO {

    public function updateAvatar(dto_usuario $dto) {

        $id = $dto->getId();
        $nombre = $dto->getNombre();
        $paterno = $dto->getPaterno();
        $materno = $dto->getMaterno();
        $dni = $dto->getDni();
        $email = $dto->getEmail();
        $clave = $dto->getClave();
        $img_avatar = $dto->getImgAvatar();

        $sql = "";
        $data = array();
        if ($clave == '') {

            $sql = " UPDATE ca_usuario 
			SET nombre = :nombre ,paterno = :paterno ,materno = :materno ,dni = :dni ,email = :email , img_avatar = :img_avatar ,fecha_modificacion = NOW() ,usuario_modificacion = :u_m 
			WHERE idusuario = :id ";
            $data = array(':nombre' => $nombre, ':paterno' => $paterno, ':materno' => $materno, ':dni' => $dni, ':email' => $email, ':img_avatar' => $img_avatar, ':u_m' => $id, ':id' => $id);
        } else {

            $sql = " UPDATE ca_usuario 
			SET nombre = :nombre ,paterno = :paterno ,materno = :materno ,dni = :dni ,email = :email ,clave = MD5(:clave) , img_avatar = :img_avatar, fecha_modificacion = NOW() , usuario_modificacion = :u_m 
			WHERE idusuario = :id ";
            $data = array(':nombre' => $nombre, ':paterno' => $paterno, ':materno' => $materno, ':dni' => $dni, ':email' => $email, ':clave' => $clave, ':img_avatar' => $img_avatar, ':u_m' => $id, ':id' => $id);
        }

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($data)) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insert() {
        $sql = " INSERT INTO ca_usuario (nombre ,paterno ,materno ,dni ,email ,clave ,estado ,fecha_creacion ,fecha_modificacion ,usuario_creacion ,usuario_modificacion) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?) ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getNombre());
        $pr->bindParam(2, $dto->getPaterno());
        $pr->bindParam(3, $dto->getMaterno());
        $pr->bindParam(4, $dto->getDni());
        $pr->bindParam(5, $dto->getEmail());
        $pr->bindParam(6, $dto->getClave());
        $pr->bindParam(7, $dto->getEstado());
        $pr->bindParam(8, $dto->getFechaCreacion());
        $pr->bindParam(9, $dto->getFechaModificacion());
        $pr->bindParam(10, $dto->getUsuarioCreacion());
        $pr->bindParam(11, $dto->getUsuarioModificacion());
        return $pr->execute();
    }

    public function insertDataCreation(dto_usuario $dto) {
    
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
    
        $sql = " SELECT ( MAX(idusuario)+ 1 ) AS 'MAX' FROM ca_usuario ";
        
        $prU = $connection->prepare($sql);
        $prU->execute();
        $dataU = $prU->fetchAll(PDO::FETCH_ASSOC);
        
        $sql = " INSERT INTO ca_usuario (nombre ,paterno ,materno ,dni ,email ,clave ,fecha_creacion  ,usuario_creacion, codigo, celular, telefono, telefono2, direccion, fecha_nacimiento, genero, estado_civil, tipo_trabajo, is_planilla ) 
			VALUES (?,?,?,?,?,MD5(?),NOW(),?,CONCAT('UA',LPAD('".$dataU[0]['MAX']."',8,'0')),?,?,?,?,?,?,?,?,?) ";
        
        $nombre = $dto->getNombre();
        $paterno = $dto->getPaterno();
        $materno = $dto->getMaterno();
        $email = $dto->getEmail();
        $dni = $dto->getDni();
        $clave = $dto->getClave();
	$codigo = $dto->getCodigo();
	$celular = $dto->getCelular();
	$telefono = $dto->getTelefono();
	$telefono2 = $dto->getTelefono2();
	$direccion = $dto->getDireccion();
        $fecha_nacimiento = $dto->getFechaNacimiento();
        $genero = $dto->getGenero();
        $estado_civil = $dto->getEstadoCivil();
        $tipo_trabajo = $dto->getTipoTrabajo();
        $is_planilla = $dto->getIsPlanilla();
        $usuario_creacion = $dto->getUsuarioCreacion();
        
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        $pr->bindParam(6, $clave);
        $pr->bindParam(7, $usuario_creacion);
		//$pr->bindParam(8, $codigo);
		$pr->bindParam(8, $celular);
		$pr->bindParam(9, $telefono);
		$pr->bindParam(10, $telefono2);
		$pr->bindParam(11, $direccion);
                $pr->bindParam(12, $fecha_nacimiento);
                $pr->bindParam(13, $genero);
                $pr->bindParam(14, $estado_civil);
                $pr->bindParam(15, $tipo_trabajo);
                $pr->bindParam(16, $is_planilla);
        if ($pr->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDataModification(dto_usuario $dto) {
	
        $id = $dto->getId();
        $nombre = $dto->getNombre();
        $paterno = $dto->getPaterno();
        $materno = $dto->getMaterno();
        $email = $dto->getEmail();
        $dni = $dto->getDni();
        $clave=$dto->getClave();
	$codigo = $dto->getCodigo();
	$celular = $dto->getCelular();
	$telefono = $dto->getTelefono();
	$telefono2 = $dto->getTelefono2();
	$direccion = $dto->getDireccion();
        $fecha_nacimiento = $dto->getFechaNacimiento();
        $genero = $dto->getGenero();
        $estado_civil = $dto->getEstadoCivil();
        $tipo_trabajo = $dto->getTipoTrabajo();
        $is_planilla = $dto->getIsPlanilla();
        $usuario_modificacion = $dto->getUsuarioModificacion();
		
		$piece_sql = "";
		($clave=='')?$piece_sql="":$piece_sql=" , clave = MD5(?) ";
		
		$sql = " UPDATE ca_usuario 
		SET nombre=? ,
		paterno=? ,
		materno=? ,
		dni=? ,
		email=? ,
		fecha_modificacion=NOW() ,
		usuario_modificacion=? ,
		codigo = ?,
		celular = ?,
		telefono = ?,
		telefono2 = ?,
		direccion = ?,
                fecha_nacimiento = ?,
                genero = ?,
                estado_civil = ?,
                tipo_trabajo = ?,
                is_planilla = ?
		$piece_sql 
		WHERE idusuario = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        $pr->bindParam(6, $usuario_modificacion);
	$pr->bindParam(7, $codigo);
	$pr->bindParam(8, $celular);
	$pr->bindParam(9, $telefono);
	$pr->bindParam(10, $telefono2);
	$pr->bindParam(11, $direccion);
        $pr->bindParam(12, $fecha_nacimiento);
        $pr->bindParam(13, $genero);
        $pr->bindParam(14, $estado_civil);
        $pr->bindParam(15, $tipo_trabajo);
        $pr->bindParam(16, $is_planilla);
	if( $clave == '' ) {
		$pr->bindParam(17, $id);
	}else{
		$pr->bindParam(17,$clave);
		$pr->bindParam(18, $id, PDO::PARAM_INT);
	}
        
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update($dto) {
        $sql = " UPDATE ca_usuario SET nombre=? ,paterno=? ,materno=? ,dni=? ,email=? ,clave=? ,estado=? ,fecha_creacion=? ,fecha_modificacion=? ,usuario_creacion=? ,usuario_modificacion=? WHERE idusuario=? ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getNombre());
        $pr->bindParam(2, $dto->getPaterno());
        $pr->bindParam(3, $dto->getMaterno());
        $pr->bindParam(4, $dto->getDni());
        $pr->bindParam(5, $dto->getEmail());
        $pr->bindParam(6, $dto->getClave());
        $pr->bindParam(7, $dto->getEstado());
        $pr->bindParam(8, $dto->getFechaCreacion());
        $pr->bindParam(9, $dto->getFechaModificacion());
        $pr->bindParam(10, $dto->getUsuarioCreacion());
        $pr->bindParam(11, $dto->getUsuarioModificacion());
        $pr->bindParam(12, $dto->getId());
        return $pr->execute();
    }

    public function changeStateUsuSerClu($idususerclu, $usuario_modificacion) {
        $sql = " update ca_usuario_servicio_cluster set fecha_modificacion=now(),usuario_modificacion=" . $usuario_modificacion . ",estado=if(estado=1,0,1) where idusuario_servicio_cluster=" . $idususerclu;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_usuario $dto) {
        $sql = " UPDATE ca_usuario SET estado = 0, usuario_modificacion = ?, fecha_modificacion = NOW() where idusuario=? ";

        $id = $dto->getId();
        $usuario_modificacion = $dto->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion);
        $pr->bindParam(2, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryAll() {
        $sql = " SELECT idusuario,nombre,paterno,materno,email FROM ca_usuario ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return json_encode($pr->fetchAll(PDO::FETCH_ASSOC));
    }

    public function queryListarClusterByServicio($servicio) {
        $sql = " select idcluster,nombre from ca_cluster_usuario where idservicio=" . $servicio . " and estado=1";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkInsertClusterServicioOperador($idususer, $idcluster) {
        $sql = " select COUNT(*) AS 'COUNT' from ca_usuario_servicio_cluster where idusuario_servicio=" . $idususer . " and idcluster=" . $idcluster;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function insertClusterServicioOperador($idususer, $idcluster, $usuarioCreacion) {

        $sql = " insert into ca_usuario_servicio_cluster (idusuario_servicio,idcluster,estado,fecha_creacion,usuario_creacion) values (" . $idususer . "," . $idcluster . ",1,now()," . $usuarioCreacion . ") ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryById(dto_usuario $dto) {
	
        $sql = " 
				SELECT idusuario,
				nombre,
				IFNULL(paterno,'') AS paterno,
				IFNULL(materno,'') AS materno,
				IFNULL(email,'') AS email,
				IFNULL(celular,'') AS celular,
				IFNULL(telefono,'') AS telefono,
				IFNULL(telefono2,'') AS telefono2,
				IFNULL(direccion,'') AS direccion,
				IFNULL(codigo,'') AS codigo,
                                IFNULL(estado_civil,'') AS estado_civil,
                                IFNULL(genero,'') AS genero,
                                IFNULL(fecha_nacimiento,'') AS fecha_nacimiento,
                                IFNULL(is_planilla,'') AS planilla,
                                IFNULL(tipo_trabajo,'') AS tipo_trabajo,
				dni, 
				img_avatar 
				FROM ca_usuario WHERE idusuario=? ";

        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function queryByOperadorService($idservicio) {
        $sql = " SELECT ususer.idusuario_servicio,CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) AS 'Teleoperador',usu.dni as 'DNI'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = " . $idservicio . "  
				ORDER BY 2 asc ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryByDetalleClusterOperador($idususer) {
        $sql = " select ususerclu.idusuario_servicio_cluster,cluusu.nombre, if(ususerclu.estado=1,'ACTIVO','INACTIVO') AS 'estado'
from ca_usuario_servicio_cluster ususerclu
	inner join ca_cluster_usuario cluusu on ususerclu.idcluster=cluusu.idcluster
where idusuario_servicio=" . $idususer . " order by 2 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //var_dump($pr->fetchAll(PDO::FETCH_ASSOC));
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function checkDNIexists(dto_usuario $dto) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario WHERE dni=? AND estado = 1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $dni = $dto->getDni();
        $pr->bindParam(1, $dni);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function checkDNIexistsUserExists(dto_usuario $dto) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario WHERE dni=? AND idusuario != ? AND estado = 1 ";

        $dni = $dto->getDni();
        $usuario = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $usuario);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function checkDNIexistsNotUser(dto_usuario $dto) {
        $sql = " SELECT COUNT(*) AS 'countDNINotUser' FROM ca_usuario WHERE dni=? AND idusuario<>? ";

        $dni = $dto->getDni();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>