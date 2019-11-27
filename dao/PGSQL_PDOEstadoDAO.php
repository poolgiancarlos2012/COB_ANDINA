<?php

class PGSQL_PDOEstadoDAO {

    public function queryStateCall(dto_servicio $dtoServicio) {

        $sql = " SELECT idestado,descripcion FROM ca_estado WHERE estado = 1 AND idtipo_estado = 1 AND idservicio = ? ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryStateNumberAccount(dto_servicio $dtoServicio) {

        $sql = " SELECT idestado,descripcion FROM ca_estado WHERE estado = 1 AND idtipo_estado = 2 AND idservicio = ? ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

}

?>