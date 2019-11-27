<?php

class PGSQL_PDOProcedureDAO {

    public function executeQueryReturn($sql) {

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //////$connection->rollBack();
            return array();
        }
    }

    public function executeQuery($sql) {

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            //////$connection->rollBack();
            return false;
        }
    }

    public function ranking_usuario_servicio(dto_cliente_cartera $dto_cliente_cartera, dto_servicio $dto_servicio) {

        $sql = " CALL p_ranking_operador ( ?,?,? ) ";

        $usuario_servicio = $dto_cliente_cartera->getIdUsuarioServicio();
        $cartera = $dto_cliente_cartera->getIdCartera();
        $servicio = $dto_servicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $usuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function ranking_cartera($servicio, $cartera, $fecha, $fechafin) {

        $sql = " CALL p_ranking_cartera ( " . $servicio . ",'" . $cartera . "','" . $fecha . "','" . $fechafin . "' ) ";
        //echo $sql;
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$pr=$connection->prepare($sql);
        $pr = $connection->query($sql);
        if ($pr) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>