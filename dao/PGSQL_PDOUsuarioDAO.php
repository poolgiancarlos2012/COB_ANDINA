<?php

class PGSQL_PDOUsuarioDAO {

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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $sql = " INSERT INTO ca_usuario (nombre ,paterno ,materno ,dni ,email ,clave ,fecha_creacion  ,usuario_creacion ) 
			VALUES (?,?,?,?,?,MD5(?),CURRENT_TIMESTAMP,?) ";

        $nombre = $dto->getNombre();
        $paterno = $dto->getPaterno();
        $materno = $dto->getMaterno();
        $email = $dto->getEmail();
        $dni = $dto->getDni();
        $clave = $dto->getClave();
        $usuario_creacion = $dto->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        $pr->bindParam(6, $clave);
        $pr->bindParam(7, $usuario_creacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateDataModification(dto_usuario $dto) {
        $sql = " UPDATE ca_usuario SET nombre=? ,paterno=? ,materno=? ,dni=? ,email=? ,fecha_modificacion=CURRENT_TIMESTAMP ,usuario_modificacion=? 
		WHERE idusuario=? ";

        $id = $dto->getId();
        $nombre = $dto->getNombre();
        $paterno = $dto->getPaterno();
        $materno = $dto->getMaterno();
        $email = $dto->getEmail();
        $dni = $dto->getDni();
        //$clave=$dto->getClave();
        $usuario_modificacion = $dto->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        //$pr->bindParam(6,$clave);
        $pr->bindParam(6, $usuario_modificacion);
        $pr->bindParam(7, $id);
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
        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " UPDATE ca_usuario_servicio_cluster 
        		SET fecha_modificacion=CURRENT_TIMESTAMP,
        		usuario_modificacion=" . $usuario_modificacion . ",
        		estado=( CASE WHEN estado=1 THEN 0 ELSE 1 END ) 
        		WHERE idusuario_servicio_cluster=" . $idususerclu;
        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " UPDATE ca_usuario SET estado = 0, usuario_modificacion = ?, fecha_modificacion = CURRENT_TIMESTAMP where idusuario=? ";

        $id = $dto->getId();
        $usuario_modificacion = $dto->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return json_encode($pr->fetchAll(PDO::FETCH_ASSOC));
    }

    public function queryListarClusterByServicio($servicio) {
        $sql = " SELECT idcluster,nombre FROM ca_cluster_usuario WHERE idservicio=" . $servicio . " and estado=1";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkInsertClusterServicioOperador($idususer, $idcluster) {
        $sql = " SELECT COUNT(*) AS COUNT FROM ca_usuario_servicio_cluster WHERE idusuario_servicio=" . $idususer . " AND idcluster=" . $idcluster;

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function insertClusterServicioOperador($idususer, $idcluster, $usuarioCreacion) {

        $sql = " INSERT INTO ca_usuario_servicio_cluster (idusuario_servicio,idcluster,estado,fecha_creacion,usuario_creacion) VALUES (" . $idususer . "," . $idcluster . ",1,CURRENT_TIMESTAMP," . $usuarioCreacion . ") ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT idusuario,nombre,paterno,materno,email,dni, img_avatar FROM ca_usuario WHERE idusuario=? ";

        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT 
        		ususer.idusuario_servicio,usu.paterno || ' ' || usu.materno || ' ' || usu.nombre AS Teleoperador,
        		usu.dni as DNI
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = " . $idservicio . "  
				ORDER BY 2 ASC ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryByDetalleClusterOperador($idususer) {
        $sql = " SELECT 
        		ususerclu.idusuario_servicio_cluster,cluusu.nombre, 
        		CASE WHEN ususerclu.estado=1 THEN 'ACTIVO' ELSE 'INACTIVO' END AS estado
				FROM ca_usuario_servicio_cluster ususerclu
				INNER JOIN ca_cluster_usuario cluusu ON ususerclu.idcluster=cluusu.idcluster
				WHERE idusuario_servicio=" . $idususer . " ORDER BY 2 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT COUNT(*) AS COUNT FROM ca_usuario WHERE dni=? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT COUNT(*) AS COUNT FROM ca_usuario WHERE dni=? AND idusuario != ? ";

        $dni = $dto->getDni();
        $usuario = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT COUNT(*) AS countDNINotUser FROM ca_usuario WHERE dni=? AND idusuario<>? ";

        $dni = $dto->getDni();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>