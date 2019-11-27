<?php

class PGSQL_PDOProvinciaDAO {

    public function queryAllProvincias(dto_campanias $dtoCampania) {

        $sql = "select distinct provincia from ca_direccion 
			where idcartera in (select distinct idcartera from ca_cartera where idcampania=?) and length(provincia)<30 and provincia!='' order by provincia";

        $campania = $dtoCampania->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $campania);

        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
        echo($pr);
    }

}

?>