<?php

class PGSQL_PDOPagoDAO {

    public function listarEstadoPago(dto_cartera $dtoCartera) {
        $idcartera = $dtoCartera->getId();
        $sql = " SELECT TRIM(estado_pago) AS  'estado_pago' FROM ca_pago 
				WHERE idcartera IN (" . $idcartera . ") AND estado = 1 AND ISNULL(estado_pago)=0 AND TRIM(estado_pago)!=''
				GROUP BY TRIM(estado_pago) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
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
