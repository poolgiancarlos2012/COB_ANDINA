<?php

class MARIAOrigenDAO {

    public function queryByIdName() {
        $sql = " SELECT idorigen,nombre FROM ca_origen ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>