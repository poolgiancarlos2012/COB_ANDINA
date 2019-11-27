<?php

class PGSQL_PDONotificadorDAO {

    public function queryById(dto_notificador $dtoNotificador) {

        $sql = " SELECT idnotificador, CONCAT_WS(' ',nombre,paterno,materno) AS 'notificador' , IFNULL( telefono,'' ) AS 'telefono',
			IFNULL( nombre, '' ) AS 'nombre', IFNULL( paterno,'' ) AS 'paterno', IFNULL( materno,'' ) AS 'materno',
			IFNULL( direccion, '' ) AS 'direccion', IFNULL(correo,'') AS 'correo'
			FROM ca_notificador WHERE idnotificador = ? ";

        $idnotificador = $dtoNotificador->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idnotificador, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryByService(dto_notificador $dtoNotificador) {

        $sql = " SELECT idnotificador, CONCAT_WS(' ',nombre,paterno,materno) AS 'notificador' , UPPER(IFNULL( telefono,'' )) AS 'telefono',
			UPPER(IFNULL( nombre, '' )) AS 'nombre', UPPER(IFNULL( paterno,'' )) AS 'paterno', UPPER(IFNULL( materno,'' )) AS 'materno',
			UPPER(IFNULL( direccion, '' )) AS 'direccion', UPPER(IFNULL(correo,'')) AS 'correo'
			FROM ca_notificador WHERE idservicio = ? AND estado = 1 ";

        $idservicio = $dtoNotificador->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function delete(dto_notificador $dtoNotificador) {

        $sql = " UPDATE ca_notificador SET estado = 0, usuario_modificacion = ?, fecha_modificacion = NOW() 
				WHERE idnotificador = ? ";

        $idnotificador = $dtoNotificador->getId();
        $usuario_modificacion = $dtoNotificador->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $idnotificador, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insert(dto_notificador $dtoNotificador) {

        $sql = " INSERT INTO ca_notificador ( idservicio, nombre, paterno, materno, telefono, direccion, correo, usuario_creacion, fecha_creacion ) 
				VALUES( ?,?,?,?,?,?,?,?,NOW() ) ";

        $idservicio = $dtoNotificador->getIdServicio();
        $nombre = $dtoNotificador->getNombre();
        $paterno = $dtoNotificador->getPaterno();
        $materno = $dtoNotificador->getMaterno();
        $telefono = $dtoNotificador->getTelefono();
        $direccion = $dtoNotificador->getDireccion;
        $correo = $dtoNotificador->getCorreo();
        $usuario_creacion = $dtoNotificador->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
        $pr->bindParam(2, $nombre, PDO::PARAM_STR);
        $pr->bindParam(3, $paterno, PDO::PARAM_STR);
        $pr->bindParam(4, $materno, PDO::PARAM_STR);
        $pr->bindParam(5, $telefono, PDO::PARAM_STR);
        $pr->bindParam(6, $direccion, PDO::PARAM_STR);
        $pr->bindParam(7, $correo, PDO::PARAM_STR);
        $pr->bindParam(8, $usuario_creacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_notificador $dtoNotificador) {

        $sql = " UPDATE ca_notificador SET nombre = ? , paterno = ?, materno = ?, telefono = ?, direccion = ?, correo = ?, 
				usuario_modificacion = ?, fecha_modificacion = NOW()
				WHERE idnotificador = ? ";

        $idnotificador = $dtoNotificador->getId();
        $nombre = $dtoNotificador->getNombre();
        $paterno = $dtoNotificador->getPaterno();
        $materno = $dtoNotificador->getMaterno();
        $telefono = $dtoNotificador->getTelefono();
        $direccion = $dtoNotificador->getDireccion;
        $correo = $dtoNotificador->getCorreo();
        $usuario_modificacion = $dtoNotificador->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre, PDO::PARAM_STR);
        $pr->bindParam(2, $paterno, PDO::PARAM_STR);
        $pr->bindParam(3, $materno, PDO::PARAM_STR);
        $pr->bindParam(4, $telefono, PDO::PARAM_STR);
        $pr->bindParam(5, $direccion, PDO::PARAM_STR);
        $pr->bindParam(6, $correo, PDO::PARAM_STR);
        $pr->bindParam(7, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(8, $idnotificador, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

}

?>
