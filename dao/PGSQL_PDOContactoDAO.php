<?php

class PGSQL_PDOContactoDAO {

    public function queryByService(dto_contacto $dtoContacto) {

        $sql = " SELECT idcontacto, nombre,descripcion FROM ca_contacto WHERE idservicio = ? AND estado = 1 ";

        $idservicio = $dtoContacto->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
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