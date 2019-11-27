<?php

class PGSQL_PDODetalleConsultaDAO {

    public function insertConsulta(dto_detalle_consulta $dtoDetalleConsulta) {

        $sql = " INSERT INTO ca_reenvio ( idconsultas, consulta , fecha_creacion, usuario_creacion ) 
				VALUES ( ?,?,NOW(),? ) ";

        $idconsulta = $dtoReenvio->getIdConsulta();
        $consulta = $dtoReenvio->getConsulta();
        $usuario_creacion = $dtoReenvio->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idconsulta);
        $pr->bindParam(2, $consulta);
        $pr->bindParam(3, $usuario_creacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertRespuesta(dto_detalle_consulta $dtoDetalleConsulta) {

        $sql = " INSERT INTO ca_reenvio ( idreenvio, respuesta , fecha_modificacion, usuario_modificacion ) 
				VALUES ( ?,?,NOW(),? ) ";

        $id = $dtoReenvio->getId();
        $respuesta = $dtoReenvio->getRespuesta();
        $usuario_modificacion = $dtoReenvio->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        $pr->bindParam(2, $respuesta);
        $pr->bindParam(3, $usuario_modificacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

}

?>