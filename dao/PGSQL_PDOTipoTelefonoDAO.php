<?php

class PGSQL_PDOTipoTelefonoDAO {

    public function queryByIdName() {
        $sql = " SELECT idtipo_telefono,nombre FROM ca_tipo_telefono ";
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