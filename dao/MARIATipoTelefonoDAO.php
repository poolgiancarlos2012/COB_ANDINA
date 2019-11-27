<?php

class MARIATipoTelefonoDAO {

    public function queryByIdName() {
        $sql = " SELECT idtipo_telefono,nombre FROM ca_tipo_telefono ";
        $factoryConnection = FactoryConnection::create('mysql');
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