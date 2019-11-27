<?php

class PGSQL_PDOEtiquetaDAO {

    public function save(dto_etiqueta $dtoEtiqueta) {

        $sql = " INSERT INTO ca_etiqueta ( nombre, descripcion, idusuario_servicio, fecha_creacion, usuario_creacion ) 
			VALUES( ?,?,?,NOW(),? ) ";

        $nombre = $dtoEtiqueta->getNombre();
        $descripcion = $dtoEtiqueta->getDescripcion();
        $usuario_servicio = $dtoEtiqueta->getIdUsuarioServicio();
        $usuario_creacion = $dtoEtiqueta->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre, PDO::PARAM_STR);
        $pr->bindParam(2, $descripcion, PDO::PARAM_STR);
        $pr->bindParam(3, $usuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryAll(dto_etiqueta $dtoEtiqueta) {

        $sql = " SELECT idetiqueta, nombre , descripcion FROM ca_etiqueta WHERE idusuario_servicio = ? AND estado = 1 ";

        $usuario_servicio = $dtoEtiqueta->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio, PDO::PARAM_INT);
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