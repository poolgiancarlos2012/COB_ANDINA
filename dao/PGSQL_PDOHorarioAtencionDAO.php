<?php

class PGSQL_PDOHorarioAtencionDAO {

    public function insert(dto_horario_atencion $dtoHorarioAtencion) {

        $idcliente = $dtoHorarioAtencion->getIdCliente();
        $hora = $dtoHorarioAtencion->getHora();
        $observacion = $dtoHorarioAtencion->getObservacion();
        $usuario_creacion = $dtoHorarioAtencion->getUsuarioCreacion();

        $sql = " INSERT INTO ca_horario_atencion ( idcliente, hora, observacion, fecha_creacion, usuario_creacion ) 
				VALUES ( ?,?,?,NOW(),? ) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcliente, PDO::PARAM_INT);
        $pr->bindParam(2, $hora, PDO::PARAM_STR);
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

    public function delete(dto_horario_atencion $dtoHorarioAtencion) {

        $idhorario_atencion = $dtoHorarioAtencion->getId();
        $usuario_modificacion = $dtoHorarioAtencion->getUsuarioModificacion();

        $sql = " UPDATE ca_horario_atencion SET estado = 0, usuario_modificacion = ? , fecha_modificacion = NOW() 
				WHERE idhorario_atencion = ? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $idhorario_atencion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_horario_atencion $dtoHorarioAtencion) {

        $hora = $dtoHorarioAtencion->getHora();
        $observacion = $dtoHorarioAtencion->getObservacion();
        $idhorario_atencion = $dtoHorarioAtencion->getId();
        $usuario_modificacion = $dtoHorarioAtencion->getUsuarioModificacion();

        $sql = " UPDATE ca_horario_atencion SET hora = ?, observacion = ?, usuario_modificacion = ? , fecha_modificacion = NOW() 
				WHERE idhorario_atencion = ? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $hora, PDO::PARAM_INT);
        $pr->bindParam(2, $observacion, PDO::PARAM_STR);
        $pr->bindParam(3, $usuario_modificacion, PDO::PARAM_STR);
        $pr->bindParam(4, $idhorario_atencion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function query(dto_horario_atencion $dtoHorarioAtencion) {

        $idcliente = $dtoHorarioAtencion->getIdCliente();

        $sql = " SELECT idhorario_atencion, hora, IFNULL( observacion,'' ) AS 'observacion' 
			FROM ca_horario_atencion WHERE idcliente = ? AND estado = 1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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