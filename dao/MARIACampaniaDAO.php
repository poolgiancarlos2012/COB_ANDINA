<?php

class MARIACampaniaDAO {

    public function updateStatusCampania ( dto_campanias $dto ) {
    
        $idcampania = $dto->getId();
        $usuario_modificacion = $dto->getUsuarioModificacion();
        $status = $dto->getStatus();
    
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $sql = " UPDATE ca_campania
                SET
                status = ? , 
                usuario_modificacion = ? , 
                fecha_modificacion = NOW()
                WHERE idcampania = ? ";
        
        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$status,PDO::PARAM_STR);
        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
        $pr->bindParam(3,$idcampania,PDO::PARAM_INT);
        if( $pr->execute() ) {
            return true ; 
        }else{
            return false ; 
        }
        
    }

    public function insert($dto) {
        $sql = " INSERT INTO ca_campania ( idservicio ,nombre ,fecha_inicio ,fecha_fin ,descripcion ,estado ,fecha_creacion ,fecha_modificacion ,usuario_creacion ,usuario_modificacion )
			VALUES (?,?,?,?,?,?,?,?,?,?) ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdServicio());
        $pr->bindParam(2, $dto->getNombre());
        $pr->bindParam(3, $dto->getFechaInicio());
        $pr->bindParam(4, $dto->getFechaFin());
        $pr->bindParam(5, $dto->getDescripcion());
        $pr->bindParam(6, $dto->getEstado());
        $pr->bindParam(7, $dto->getFechaCreacion());
        $pr->bindParam(8, $dto->getFechaModificacion());
        $pr->bindParam(9, $dto->getUsuarioCreacion());
        $pr->bindParam(10, $dto->getUsuarioModificacion());
        return $pr->execute();
    }

    public function insertDataCreation(dto_campanias $dto) {
        $sql = " INSERT INTO ca_campania ( idservicio ,nombre ,fecha_inicio ,fecha_fin ,descripcion ,estado ,fecha_creacion ,usuario_creacion )
			VALUES (?,?,?,?,?,1,NOW(),?) ";

        $servicio = $dto->getIdServicio();
        $nombre = $dto->getNombre();
        $FechaInicio = $dto->getFechaInicio();
        $FechaFin = $dto->getFechaFin();
        $descripcion = $dto->getDescripcion();
        $UsuarioCreacion = $dto->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //////$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $nombre);
        $pr->bindParam(3, $FechaInicio);
        $pr->bindParam(4, $FechaFin);
        $pr->bindParam(5, $descripcion);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            //////$connection->commit();
            return true;
        } else {
            //////$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET estado=0 , usuario_modificacion=? , fecha_modificacion=NOW() where idcampania=? ";

        $UsuarioModificacion = $dto->getUsuarioModificacion();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioModificacion);
        $pr->bindParam(2, $id);

        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function update(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET idusuario_servicio=? ,nombre=? ,fecha_inicio=? ,fecha_fin=? ,descripcion=? ,estado=? ,fecha_creacion=? ,fecha_modificacion=? ,usuario_creacion=? ,usuario_modificacion=? WHERE idcampania=? ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdUsuarioServicio());
        $pr->bindParam(2, $dto->getNombre());
        $pr->bindParam(3, $dto->getFechaInicio());
        $pr->bindParam(4, $dto->getFechaFin());
        $pr->bindParam(5, $dto->getDescripcion());
        $pr->bindParam(6, $dto->getEstado());
        $pr->bindParam(7, $dto->getFechaCreacion());
        $pr->bindParam(8, $dto->getFechaModificacion());
        $pr->bindParam(9, $dto->getUsuarioCreacion());
        $pr->bindParam(10, $dto->getUsuarioModificacion());
        $pr->bindParam(11, $dto->getId());
        return $pr->execute();
    }

    public function updateDataModification(dto_campanias $dto) {
        $sql = " UPDATE ca_campania SET nombre=? ,fecha_inicio=? ,fecha_fin=? ,descripcion=? ,fecha_modificacion=NOW() ,usuario_modificacion=? WHERE idcampania=? ";

        $nombre = $dto->getNombre();
        $FechaInicio = $dto->getFechaInicio();
        $FechaFin = $dto->getFechaFin();
        $descripcion = $dto->getDescripcion();
        $UsuarioModificacion = $dto->getUsuarioModificacion();
        $id = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $FechaInicio);
        $pr->bindParam(3, $FechaFin);
        $pr->bindParam(4, $descripcion);
        $pr->bindParam(5, $UsuarioModificacion);
        $pr->bindParam(6, $id);

        if ($pr->execute()) {
            //////$connection->commit();
            return true;
        } else {
            //////$connection->rollBack();
            return false;
        }
    }

    public function queryByIdNameStatusActive(dto_servicio $dto) {
        $sql = " SELECT idcampania,nombre 
                FROM ca_campania 
                WHERE idservicio=? AND estado=1 AND fecha_inicio<=CURDATE() AND fecha_fin>=CURDATE() AND status = 'ACTIVO' ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryByIdName(dto_servicio $dto) {
        $sql = " SELECT idcampania,nombre FROM ca_campania WHERE idservicio=? AND estado=1 AND fecha_inicio<=CURDATE() AND fecha_fin>=CURDATE() and status='ACTIVO' ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryAllByIdName(dto_servicio $dto) {
        $sql = " SELECT idcampania,nombre FROM ca_campania WHERE idservicio=? AND estado=1 and status='ACTIVO' order by idcampania desc ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryAll() {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM  ca_campania WHERE estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryAllByService(dto_campanias $dtoCampania) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM  ca_campania WHERE estado=1 AND idservicio=? ";

        $servicio = $dtoCampania->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryById(dto_campanias $dto) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin, usuario_creacion, (SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario=ca_campania.usuario_creacion) AS 'nombre_usuario_creacion' FROM ca_campania WHERE idcampania=? ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $id = $dto->getId();

        $pr->bindParam(1, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryByUserService(dto_campanias $dto) {
        $sql = " SELECT idcampania, nombre, descripcion, fecha_inicio, fecha_fin FROM ca_campania WHERE idusuario_servicio=? ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getIdServicio());
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function COUNTByServicio(dto_campanias $dto) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_campania WHERE estado=1 AND idservicio=? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $servicio = $dto->getIdServicio();

        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function executeSelectString($sql) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

	//~ Vic I
	public function ListarCarteraHistory(dto_cartera $dto)
	{
        $cartera=$dto->getId();
		$sql = "SELECT DISTINCT h.Fproceso FROM ca_historial h 
                INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=h.idcliente_cartera
                WHERE h.Fproceso!='' and clicar.idcartera in ($cartera) GROUP BY h.Fproceso ORDER BY h.dateSys DESC";
		$sqlA = "SELECT DISTINCT h.agencia FROM ca_historial h 
                INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=h.idcliente_cartera
                WHERE h.Fproceso!='' and clicar.idcartera in ($cartera) AND h.agencia IS NOT NULL GROUP BY h.agencia";
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$arrayData = array();
		$pr = $connection->prepare($sql);
		if ($pr->execute()) {
			$arrayData['fecha_proceso'] = $pr->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$arrayData['fecha_proceso'] = "";
		}
		$prA = $connection->prepare($sqlA);
		if ($prA->execute()) {
			$arrayData['agencias'] = $prA->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$arrayData['agencias'] = "";
		}
		return $arrayData;
	}
	//~ Vic F

}

?>
