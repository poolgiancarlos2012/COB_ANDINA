<?php

class MARIACorreoDAO {

    public function insert(dto_correo $dtoCorreo) {

        $sql = " INSERT INTO ca_correo ( idcliente, correo, observacion, fecha_creacion, usuario_creacion ) 
				VALUES ( ?,?,?,NOW(),? ) ";

        $idcliente = $dtoCorreo->getIdCliente();
        $correo = $dtoCorreo->getCorreo();
        $observacion = $dtoCorreo->getObservacion();
        $usuario_creacion = $dtoCorreo->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcliente, PDO::PARAM_INT);
        $pr->bindParam(2, $correo, PDO::PARAM_STR);
        $pr->bindParam(3, $observacion, PDO::PARAM_STR);
        $pr->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_correo $dtoCorreo) {

        $sql = " UPDATE ca_correo SET estado = 0, usuario_modificacion = ? , fecha_modificacion = NOW() 
				WHERE idcorreo = ? ";

        $idcorreo = $dtoCorreo->getId();
        $usuario_modificacion = $dtoCorreo->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcorreo, PDO::PARAM_INT);
        $pr->bindParam(2, $usuario_modificacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_correo $dtoCorreo) {

        $sql = " UPDATE ca_correo SET corre = ?, observacion = ?, usuario_modificacion = ? , fecha_modificacion = NOW() 
				WHERE idcorreo = ? ";

        $correo = $dtoCorreo->getCorreo();
        $observacion = $dtoCorreo->getObservacion();
        $idcorreo = $dtoCorreo->getId();
        $usuario_modificacion = $dtoCorreo->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $correo, PDO::PARAM_STR);
        $pr->bindParam(2, $observacion, PDO::PARAM_STR);
        $pr->bindParam(3, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(4, $idcorreo, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function query(dto_correo $dtoCorreo) {

        $idcliente = $dtoCorreo->getIdCliente();

        $sql = " SELECT idcorreo, correo, IFNULL(observacion,'') AS 'observacion' 
			FROM ca_correo WHERE idcliente = ? AND estado = 1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcliente, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

}

?>