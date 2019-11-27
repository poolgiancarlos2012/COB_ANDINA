<?php

class MARIAMotivoNoPagoDAO {

    public function queryByService(dto_motivo_no_pago $dtoMotivoNoPago) {

        $sql = " SELECT idmotivo_no_pago,nombre,
                IFNULL(codigo,'') AS codigo, IFNULL(descripcion,'') AS descripcion,tipo_producto 
		FROM ca_motivo_no_pago WHERE idservicio = ? AND estado = 1 ORDER BY 1 DESC";

        $idservicio = $dtoMotivoNoPago->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
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
    public function ListarSustentoPago(dto_motivo_no_pago $dtoMotivoNoPago) {//jmore18112014

        $sql = " SELECT idsustento_pago,nombre,tipo_producto 
        FROM ca_sustento_pago WHERE idservicio = ? AND estado = 1 ";

        $idservicio = $dtoMotivoNoPago->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
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
    public function ListarAlertaGestion(dto_motivo_no_pago $dtoMotivoNoPago) {//jmore18112014

        $sql = " SELECT idalerta_gestion,nombre,tipo_producto 
        FROM ca_alerta_gestion WHERE idservicio = ? AND estado = 1 ";

        $idservicio = $dtoMotivoNoPago->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
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