<?php

class PGSQL_PDONivelDAO {

    public function queryAll() {
        $sql = " SELECT idnivel,nombre FROM ca_nivel ";
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

    public function queryJQGRID($sidx, $sord, $start, $limit) {
        $sql = " SELECT idnivel,IFNULL(nombre,'') AS 'nombre',IFNULL(descripcion,'') AS 'descripcion' 
				FROM ca_nivel ORDER BY $sidx $sord LIMIT $start , $limit ";
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

    public function COUNT() {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_nivel ";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
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