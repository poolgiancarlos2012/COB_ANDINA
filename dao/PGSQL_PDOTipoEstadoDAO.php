<?php

class PGSQL_PDOTipoEstadoDAO {

    public function queryByServiceTransacc(dto_tipo_estado $dtoTipoEstado) {

        $sql = " SELECT idtipo_estado,nombre FROM ca_tipo_estado WHERE idservicio = ? AND idtipo_transaccion = ? AND estado = 1 ";

        $servicio = $dtoTipoEstado->getIdServicio();
        $tipo_transaccion = $dtoTipoEstado->getIdTipoTransaccion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $tipo_transaccion, PDO::PARAM_INT);
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