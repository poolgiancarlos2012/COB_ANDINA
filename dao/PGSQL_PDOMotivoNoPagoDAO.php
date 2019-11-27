<?php

class PGSQL_PDOMotivoNoPagoDAO {

    public function queryByService(dto_motivo_no_pago $dtoMotivoNoPago) {

        $sql = " SELECT idmotivo_no_pago,nombre,descripcion 
				FROM ca_motivo_no_pago WHERE idservicio = ? AND estado = 1 ";

        $idservicio = $dtoMotivoNoPago->getIdServicio();

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