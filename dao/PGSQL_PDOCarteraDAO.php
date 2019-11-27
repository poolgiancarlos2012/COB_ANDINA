<?php

class PGSQL_PDOCarteraDAO {

    public function UpdateMeta(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $meta_cliente = $dtoCartera->getMetaCliente();
        $meta_cuenta = $dtoCartera->getMetaCuenta();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW(), meta_cliente = ? , meta_cuenta = ?  
				WHERE idcartera = ? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $meta_cliente, PDO::PARAM_INT);
        $pr->bindParam(3, $meta_cuenta, PDO::PARAM_INT);
        $pr->bindParam(4, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function active(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW(), status = 'ACTIVO' 
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function desactive(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW() ,  status = 'DESACTIVO'
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET estado = 0, usuario_modificacion = ? , fecha_modificacion = NOW() 
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryAllCarteraByCamp(dto_cartera $dtoCartera) {

        $sql = " SELECT idcartera,codigo_cliente, tabla, numero_cuenta, moneda_cuenta, moneda_operacion , codigo_operacion, 
			cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, cabeceras 
			FROM ca_cartera 
			WHERE idcampania = ? AND estado = 1 ";

        $idcampania = $dtoCartera->getIdCampania();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcampania, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryAllByService(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT car.idcartera , car.nombre_cartera 
				FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania 
				WHERE cam.idservicio = ? AND car.estado = 1 ORDER BY idcartera ASC ";

        $idservicio = $dtoUsuarioServicio->getIdServicio();

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

    public function queryCarteraMetaData(dto_cartera $dtoCartera) {
        $idcartera = $dtoCartera->getId();
        $sql = " SELECT idcartera,codigo_cliente, tabla, numero_cuenta, moneda_cuenta, moneda_operacion , codigo_operacion, 
			cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, cabeceras 
			FROM ca_cartera 
			WHERE idcartera IN (" . $idcartera . ") ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function queryIdNombreActivos(dto_campanias $dtoCampania) {

        $sql = "SELECT idcartera,
				CASE WHEN fecha_fin < CURRENT_DATE THEN ('<font color=red>' || nombre_cartera || '</font>') ELSE nombre_cartera END AS nombre_cartera, 
				fecha_inicio, 
				fecha_fin,
				CASE WHEN fecha_fin < CURRENT_DATE THEN 1 ELSE 0 END AS vencido 
				FROM ca_cartera WHERE estado=1 AND idcampania=? ";

        $campania = $dtoCampania->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $campania);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryIdNombreActivosRpteRank(dto_campanias $dtoCampania, $estado) {
        $campania = $dtoCampania->getId();

        $sql = "SELECT idcartera,
	if( date(fecha_fin)<date(now()), concat('<font color=red>',nombre_cartera,'</font>') , nombre_cartera) as nombre_cartera, 
	IFNULL(fecha_inicio,'') AS 'fecha_inicio', 
	IFNULL(fecha_fin,'') AS 'fecha_fin',
	if( date(fecha_fin)<date(now()), 1 , 0) as 'vencido'
FROM ca_cartera WHERE estado=1 AND idcampania=" . $campania . " and (if( date(fecha_fin)<date(now()), 1 , 0)) in (" . $estado . ") ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        //$pr->bindParam(1,$campania);
        //$pr->bindParam(2,$estado,PDO::PARAM_STR);

        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryIdNombreActivosOperador(dto_campanias $dtoCampania, $usuario) {

        $sql = "SELECT distinct car.idcartera,
				if( date(car.fecha_fin)<date(now()), concat('<font color=red>',car.nombre_cartera,'</font>') , car.nombre_cartera) as 'nombre_cartera', 
				IFNULL(car.fecha_inicio,'') AS 'fecha_inicio', 
				IFNULL(car.fecha_fin,'') AS 'fecha_fin',
				if( date(car.fecha_fin)<date(now()), 1 , 0) as 'vencido'
			FROM ca_cartera car
			left join ca_cliente_cartera clicar on clicar.idcartera=car.idcartera
			WHERE car.estado=1 AND car.idcampania=?  and clicar.idusuario_servicio=?";

        $campania = $dtoCampania->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $campania);
        $pr->bindParam(2, $usuario);

        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

}

?>
