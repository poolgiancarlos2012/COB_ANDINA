<?php

class MARIATipoAyudaGestionDAO {

    public function queryAllByIdNombre() {
        $sql = " SELECT idtipo_ayuda_gestion,nombre FROM ca_tipo_ayuda_gestion ";

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