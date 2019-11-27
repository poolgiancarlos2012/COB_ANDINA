<?php

class PGSQL_PDONotaDAO {

    public function insert(dto_nota $dtoNota) {
        $sql = " INSERT INTO ca_nota ( idcliente_cartera, fecha , descripcion, fecha_creacion, usuario_creacion ) 
				VALUES( ?,?,?,NOW(),? ) ";

        $ClienteCartera = $dtoNota->getIdClienteCartera();
        $fecha = $dtoNota->getFecha();
        $descripcion = $dtoNota->getDescripcion();
        $UsuarioCreacion = $dtoNota->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $ClienteCartera);
        $pr->bindParam(2, $fecha);
        $pr->bindParam(3, $descripcion);
        $pr->bindParam(4, $UsuarioCreacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryById(dto_nota $dtoNota) {

        $sql = " SELECT nota.idnota, DATE_FORMAT( nota.fecha_creacion , '%d de %M del %Y' ) AS 'fecha_creacion', cli.codigo, nota.descripcion , TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
			( SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario = nota.usuario_creacion LIMIT 1 ) AS 'usuario_creacion'
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_nota nota 
			ON nota.idcliente_cartera=clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo 
			WHERE nota.idnota = ? ";

        $id = $dtoNota->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryAllToDay(dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio) {
//			$sql=" SELECT DISTINCT nota.idnota, nota.descripcion, cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente'
//			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_nota nota 
//			ON nota.idcliente_cartera=clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente  
//			WHERE nota.fecha=CURDATE() AND nota.estado=1 AND cli.estado=1 AND clicar.idcartera = ? AND clicar.estado=1 
//			AND clicar.idusuario_servicio = ? ORDER BY nota.fecha DESC ";
        $cartera = $dtoClienteCartera->getIdCartera();
        $sql = " SELECT DISTINCT nota.idnota, nota.reading, nota.important , IF(LENGTH(nota.descripcion)<25,nota.descripcion,CONCAT(SUBSTRING(nota.descripcion,1,25),'...')) AS 'descripcion', 
			cli.codigo,nota.descripcion AS 'descripcion_total', TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente'
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_nota nota 
			ON nota.idcliente_cartera=clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo 
			WHERE nota.fecha=CURDATE() AND nota.estado=1 AND cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera IN (" . $cartera . ") AND clicar.estado=1 
			AND clicar.idusuario_servicio = ? ORDER BY nota.fecha DESC LIMIT 20 ";

        $UsuarioServicio = $dtoClienteCartera->getIdUsuarioServicio();
        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ** */
        $pr->bindParam(1, $servicio);
        /*         * ** */
        //$pr->bindParam(2,$cartera);
        $pr->bindParam(2, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function deleteAll(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_nota SET estado = 0 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idcliente_cartera IN ( SELECT idcliente_cartera FROM ca_cliente_cartera WHERE idusuario_servicio = ? AND idcartera = ? ) ";

        $usuario_modificacion = $dtoClienteCartera->getUsuarioModificacion();
        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();
        $cartera = $dtoClienteCartera->getIdCartera();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $usuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function deleteById(dto_nota $dtoNota, $ids) {
        $sql = " UPDATE ca_nota SET estado = 0 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idnota IN ( $ids ) ";

        $usuario_modificacion = $dtoNota->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function MarcarNoLeida(dto_nota $dtoNota, $ids) {
        $sql = " UPDATE ca_nota SET reading = 0 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idnota IN ( $ids ) ";

        $usuario_modificacion = $dtoNota->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function MarcarLeido(dto_nota $dtoNota) {
        $sql = " UPDATE ca_nota SET reading = 1 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idnota = ? ";

        $usuario_modificacion = $dtoNota->getUsuarioModificacion();
        $id = $dtoNota->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $id, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function MarcarImportante(dto_nota $dtoNota, $ids) {
        $sql = " UPDATE ca_nota SET important = 1 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idnota IN ( $ids ) ";

        $usuario_modificacion = $dtoNota->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function DesmarcarImportante(dto_nota $dtoNota) {

        $sql = " UPDATE ca_nota SET important = 0 , fecha_modificacion = NOW(), usuario_modificacion = ?  
			WHERE idnota = ? ";

        $usuario_modificacion = $dtoNota->getUsuarioModificacion();
        $id = $dtoNota->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $id, PDO::PARAM_INT);
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
