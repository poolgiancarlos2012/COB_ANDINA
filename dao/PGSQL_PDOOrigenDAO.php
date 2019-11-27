<?php

class PGSQL_PDOOrigenDAO {

    public function queryByIdName() {
        $sql = " SELECT idorigen,nombre FROM ca_origen ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>