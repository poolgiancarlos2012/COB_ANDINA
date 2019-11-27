<?php

class PGSQL_PDOCampaniaDAO {

    public function insert($dto) {
        $sql = " INSERT INTO ca_campania ( idservicio ,nombre ,fecha_inicio ,fecha_fin ,descripcion ,estado ,fecha_creacion ,fecha_modificacion ,usuario_creacion ,usuario_modificacion )
			VALUES (?,?,?,?,?,?,?,?,?,?) ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdServicio());
        $pr->bindParam(2, $dto->getNombre());
        $pr->bindParam(3, $dto->getFechaInicio());
        $pr->bindParam(4, $dto->getFechaFin());
        $pr->bindParam(5, $dto->getDescripcion());
        $pr->bindParam(6, $dto->getEstado());
        $pr->bindParam(7, $dto->getFechaCreacion());
        $pr->bindParam(8, $dto->getFechaModificacion());
        $pr->bindParam(9, $dto->getUsuarioCreacion());
        $pr->bindParam(10, $dto->getUsuarioModificacion());
        return $pr->execute();
    }

    public function insertDataCreation(dto_campanias $dto) {
        $sql = " INSERT INTO ca_campania ( idservicio ,nombre ,fecha_inicio ,fecha_fin ,descripcion ,estado ,fecha_creacion ,usuario_creacion )
			VALUES (?,?,?,?,?,1,NOW(),?) ";

        $servicio = $dto->getIdServicio();
        $nombre = $dto->getNombre();
        $FechaInicio = $dto->getFechaInicio();
        $FechaFin = $dto->getFechaFin();
        $descripcion = $dto->getDescripcion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        //////$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $nombre);
        $pr->bindParam(3, $FechaInicio);
        $pr->bindParam(4, $FechaFin);
        $pr->bindParam(5, $descripcion);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            //////$connection->commit();
            return true;
        } else {
            //////$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET estado=0 , usuario_modificacion=? , fecha_modificacion=NOW() where idcampania=? ";

        $UsuarioModificacion = $dto->getUsuarioModificacion();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioModificacion);
        $pr->bindParam(2, $id);

        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function update(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET idusuario_servicio=? ,nombre=? ,fecha_inicio=? ,fecha_fin=? ,descripcion=? ,estado=? ,fecha_creacion=? ,fecha_modificacion=? ,usuario_creacion=? ,usuario_modificacion=? WHERE idcampania=? ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdUsuarioServicio());
        $pr->bindParam(2, $dto->getNombre());
        $pr->bindParam(3, $dto->getFechaInicio());
        $pr->bindParam(4, $dto->getFechaFin());
        $pr->bindParam(5, $dto->getDescripcion());
        $pr->bindParam(6, $dto->getEstado());
        $pr->bindParam(7, $dto->getFechaCreacion());
        $pr->bindParam(8, $dto->getFechaModificacion());
        $pr->bindParam(9, $dto->getUsuarioCreacion());
        $pr->bindParam(10, $dto->getUsuarioModificacion());
        $pr->bindParam(11, $dto->getId());
        return $pr->execute();
    }

    public function updateDataModification(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET nombre=? ,fecha_inicio=? ,fecha_fin=? ,descripcion=? ,fecha_modificacion=NOW() ,usuario_modificacion=? WHERE idcampania=? ";

        $nombre = $dto->getNombre();
        $FechaInicio = $dto->getFechaInicio();
        $FechaFin = $dto->getFechaFin();
        $descripcion = $dto->getDescripcion();
        $UsuarioModificacion = $dto->getUsuarioModificacion();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $FechaInicio);
        $pr->bindParam(3, $FechaFin);
        $pr->bindParam(4, $descripcion);
        $pr->bindParam(5, $UsuarioModificacion);
        $pr->bindParam(6, $id);

        if ($pr->execute()) {
            //////$connection->commit();
            return true;
        } else {
            //////$connection->rollBack();
            return false;
        }
    }

    public function queryByIdName(dto_servicio $dto) {
        $sql = " SELECT idcampania,nombre FROM ca_campania WHERE idservicio=? AND estado=1 AND fecha_inicio<=CURDATE() AND fecha_fin>=CURDATE() ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryAllByIdName(dto_servicio $dto) {
        $sql = " SELECT idcampania,nombre FROM ca_campania WHERE idservicio=? AND estado=1 ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryAll() {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM  ca_campania WHERE estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryAllByService(dto_campanias $dtoCampania) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM  ca_campania WHERE estado=1 AND idservicio=? ";

        $servicio = $dtoCampania->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryById(dto_campanias $dto) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin, usuario_creacion, (SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario=ca_campania.usuario_creacion) AS 'nombre_usuario_creacion' FROM ca_campania WHERE idcampania=? ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $id = $dto->getId();

        $pr->bindParam(1, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryByUserService(dto_campanias $dto) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM ca_campania WHERE idusuario_servicio=? ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdServicio());
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function COUNTByServicio(dto_campanias $dto) {
		/******* MYSQL *****/
        //$sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_campania WHERE estado=1 AND idservicio=? ";
		/****** POSTGRES ******/
		$sql = " SELECT COUNT(*) AS COUNT FROM ca_campania WHERE estado=1 AND idservicio=? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $servicio = $dto->getIdServicio();

        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function executeSelectString($sql) {
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>