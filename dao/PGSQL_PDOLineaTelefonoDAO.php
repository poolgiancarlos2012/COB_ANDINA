<?php

class PGSQL_PDOLineaTelefonoDAO {

    public function queryIdName() {

        $sql = " SELECT idlinea_telefono, nombre FROM ca_linea_telefono ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>