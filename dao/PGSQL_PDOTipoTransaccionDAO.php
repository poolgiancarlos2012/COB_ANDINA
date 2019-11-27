<?php

class PGSQL_PDOTipoTransaccionDAO {

    public function query() {
        $sql = " SELECT idtipo_transaccion AS 'id', nombre FROM ca_tipo_transaccion ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
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