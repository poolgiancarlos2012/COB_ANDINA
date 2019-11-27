<?php

class MARIATipoFinalDAO {

    public function queryAllByIdName() {
        $sql = " SELECT idtipo_final, nombre FROM ca_tipo_final ";
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

    public function queryJQGRID($sidx, $sord, $start, $limit) {
        $sql = " SELECT idtipo_final, IFNULL(nombre,'') AS 'nombre', IFNULL(descripcion,'') AS 'descripcion' 
				FROM ca_tipo_final ORDER BY $sidx $sord LIMIT $start , $limit ";
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

    public function COUNT() {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_tipo_final ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

}

?>