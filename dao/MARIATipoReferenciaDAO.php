<?php

class MARIATipoReferenciaDAO {

    public function queryByIdName() {
        $sql = " SELECT idtipo_referencia,nombre FROM ca_tipo_referencia ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>