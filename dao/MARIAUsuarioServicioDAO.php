<?php

class MARIAUsuarioServicioDAO {

    public function querySupervisor(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT ususer.idusuario_servicio, CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario 
			WHERE ususer.idservicio = ? AND usu.estado = 1 AND ususer.estado = 1 AND ususer.idtipo_usuario = 1 ";
        $idservicio = $dtoUsuarioServicio->getIdServicio();
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($pr->fetchAll(PDO::FETCH_ASSOC));
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function buscarAnexo(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario_servicio WHERE idservicio = ? AND anexo = ? AND idusuario_servicio != ?  ";

        $anexo = $dtoUsuarioServicio->getAnexo();
        $idservicio = $dtoUsuarioServicio->getIdServicio();
        $idusuario_servicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
        $pr->bindParam(2, $anexo, PDO::PARAM_STR);
        $pr->bindParam(3, $idusuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 1));
        }
    }

    public function updateAnexo(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " UPDATE ca_usuario_servicio SET anexo = ? WHERE idusuario_servicio = ? ";

        $anexo = $dtoUsuarioServicio->getAnexo();
        $idusuario_servicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $anexo, PDO::PARAM_STR);
        $pr->bindParam(2, $idusuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataCreation(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " INSERT INTO ca_usuario_servicio ( idusuario,idservicio,idprivilegio,idtipo_usuario,fecha_inicio,fecha_fin,fecha_creacion,usuario_creacion ) 
			VALUES( ?,?,?,?,?,?,NOW(),? ) ";

        $usuario = $dtoUsuarioServicio->getIdUsuario();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $privilegio = $dtoUsuarioServicio->getIdPrivilegio();
        $TipoUsuario = $dtoUsuarioServicio->getIdTipoUsuario();
        $FechaInicio = $dtoUsuarioServicio->getFechaInicio();
        $FechaFin = $dtoUsuarioServicio->getFechaFin();
        $UsuarioCreacion = $dtoUsuarioServicio->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        $pr->bindParam(2, $servicio);
        $pr->bindParam(3, $privilegio);
        $pr->bindParam(4, $TipoUsuario);
        $pr->bindParam(5, $FechaInicio);
        $pr->bindParam(6, $FechaFin);
        $pr->bindParam(7, $UsuarioCreacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateDataModification(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " UPDATE ca_usuario_servicio SET idservicio = ?, idtipo_usuario = ?, idprivilegio = ?, fecha_inicio = ?, 
			fecha_fin = ?, fecha_modificacion=NOW(), usuario_modificacion = ? WHERE idusuario_servicio = ? ";

        $id = $dtoUsuarioServicio->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $privilegio = $dtoUsuarioServicio->getIdPrivilegio();
        $TipoUsuario = $dtoUsuarioServicio->getIdTipoUsuario();
        $FechaInicio = $dtoUsuarioServicio->getFechaInicio();
        $FechaFin = $dtoUsuarioServicio->getFechaFin();
        $UsuarioModificacion = $dtoUsuarioServicio->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $TipoUsuario);
        $pr->bindParam(3, $privilegio);
        $pr->bindParam(4, $FechaInicio);
        $pr->bindParam(5, $FechaFin);
        $pr->bindParam(6, $UsuarioModificacion);
        $pr->bindParam(7, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateDataClusterServicio($idcluster, $nombre, $descripcion, $estado, $usumodif) {

        $sql = " update ca_cluster_usuario set nombre='" . $nombre . "', descripcion='" . $descripcion . "', estado=" . $estado . ", fecha_modificacion=now(),usuario_modificacion=" . $usumodif . " where idcluster=" . $idcluster;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertDataClusterServicio($idservicio, $nombre, $descripcion, $usucreate) {

        $sql = " insert into ca_cluster_usuario (idservicio,nombre,descripcion,estado,fecha_creacion,usuario_creacion) values ('" . $idservicio . "','" . $nombre . "','" . $descripcion . "',1,now()," . $usucreate . ") ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " UPDATE ca_usuario_servicio SET estado = 0 ,fecha_modificacion=NOW(), usuario_modificacion = ? WHERE idusuario_servicio = ? ";

        $id = $dtoUsuarioServicio->getId();
        $UsuarioModificacion = $dtoUsuarioServicio->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioModificacion);
        $pr->bindParam(2, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function checkUsuarioServicio(dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario_servicio WHERE idusuario = ? AND idservicio = ? AND estado = 1 ";

        $usuario = $dtoUsuarioServicio->getIdUsuario();
        $servicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function checkUsuarioServicio2(dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario_servicio WHERE idusuario = ? AND idservicio = ? AND idusuario_servicio != ? AND estado = 1 ";

        $id = $dtoUsuarioServicio->getId();
        $usuario = $dtoUsuarioServicio->getIdUsuario();
        $servicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        $pr->bindParam(2, $servicio);
        $pr->bindParam(3, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function checkClusterServicio($nombre, $idservicio, $idcluster) {
        $sql = " select count(*) as 'COUNT' from ca_cluster_usuario where nombre='" . $nombre . "' and idservicio=" . $idservicio . "  and idcluster!=" . $idcluster;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function checkClusterServicio2($nombre, $idservicio) {
        $sql = " select count(*) as 'COUNT' from ca_cluster_usuario where nombre='" . $nombre . "' and idservicio=" . $idservicio;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function insertUsuarioServicio(dto_usuario_servicio $dtoUsuarioServicio, dto_usuario $dtoUsuario) {
        $sqlUsuario = " INSERT INTO ca_usuario(nombre,paterno,materno,dni,email,clave,estado,fecha_creacion,usuario_creacion) 
						VALUES ( ?,?,?,?,?,MD5(?),1,NOW(),? ) ";

        $sqlUsuarioServicio = " INSERT INTO ca_usuario_servicio (idusuario,idservicio,idprivilegio,idtipo_usuario,fecha_inicio,fecha_fin,estado,fecha_creacion,usuario_creacion ) 
								VALUES ( ?,?,?,?,?,?,1,NOW(),? ) ";

        $nombre = $dtoUsuario->getNombre();
        $paterno = $dtoUsuario->getPaterno();
        $materno = $dtoUsuario->getMaterno();
        $dni = $dtoUsuario->getDni();
        $email = $dtoUsuario->getEmail();
        $clave = $dtoUsuario->getClave();
        $UsuarioCreacion = $dtoUsuario->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sqlUsuario);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        $pr->bindParam(6, $clave);
        $pr->bindParam(7, $UsuarioCreacion);

        if ($pr->execute()) {

            $usuario = $connection->lastInsertId();

            $servicio = $dtoUsuarioServicio->getIdServicio();
            $privilegio = $dtoUsuarioServicio->getIdPrivilegio();
            $TipoUsuario = $dtoUsuarioServicio->getIdTipoUsuario();
            $FechaInicio = $dtoUsuarioServicio->getFechaInicio();
            $FechaFin = $dtoUsuarioServicio->getFechaFin();

            $prUS = $connection->prepare($sqlUsuarioServicio);
            $prUS->bindParam(1, $usuario);
            $prUS->bindParam(2, $servicio);
            $prUS->bindParam(3, $privilegio);
            $prUS->bindParam(4, $TipoUsuario);
            $prUS->bindParam(5, $FechaInicio);
            $prUS->bindParam(6, $FechaFin);
            $prUS->bindParam(7, $UsuarioCreacion);

            if ($prUS->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateUsuarioServicio(dto_usuario $dtoUsuario, dto_usuario_servicio $dtoUsuarioServicio) {

        $sqlU = " UPDATE ca_usuario SET nombre=? , paterno=? , materno=? , dni= ? , email=? , usuario_modificacion=? , fecha_modificacion=NOW()
				WHERE idusuario=? ";

        $nombre = $dtoUsuario->getNombre();
        $paterno = $dtoUsuario->getPaterno();
        $materno = $dtoUsuario->getMaterno();
        $dni = $dtoUsuario->getDni();
        $email = $dtoUsuario->getEmail();
        $UsuarioModificacion = $dtoUsuario->getUsuarioModificacion();
        //$clave=$dtoUsuario->getClave();
        $usuario = $dtoUsuario->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sqlU);
        $pr->bindParam(1, $nombre);
        $pr->bindParam(2, $paterno);
        $pr->bindParam(3, $materno);
        $pr->bindParam(4, $dni);
        $pr->bindParam(5, $email);
        //$pr->bindParam(6,$clave);
        $pr->bindParam(6, $UsuarioModificacion);
        $pr->bindParam(7, $usuario);

        if ($pr->execute()) {

            $sqlUS = " UPDATE ca_usuario_servicio SET idprivilegio=? , idtipo_usuario=? , fecha_inicio=? , fecha_fin=? , usuario_modificacion=? , fecha_modificacion=NOW()
				WHERE idusuario_servicio=? ";

            $privilegio = $dtoUsuarioServicio->getIdPrivilegio();
            $TipoUsuario = $dtoUsuarioServicio->getIdTipoUsuario();
            $FechaInicio = $dtoUsuarioServicio->getFechaInicio();
            $FechaFin = $dtoUsuarioServicio->getFechaFin();
            $servicio = $dtoUsuarioServicio->getIdServicio();
            $UsuarioServicio = $dtoUsuarioServicio->getId();
            $UsuarioModificacionUS = $dtoUsuarioServicio->getUsuarioModificacion();


            $prUS = $connection->prepare($sqlUS);
            $prUS->bindParam(1, $privilegio);
            $prUS->bindParam(2, $TipoUsuario);
            $prUS->bindParam(3, $FechaInicio);
            $prUS->bindParam(4, $FechaFin);
            $prUS->bindParam(5, $UsuarioModificacionUS);
            $prUS->bindParam(6, $UsuarioServicio);

            if ($prUS->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function deleteUsuarioServicio(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " UPDATE ca_usuario_servicio SET estado=0 , fecha_modificacion=NOW() , usuario_modificacion=? 
				WHERE idusuario_servicio = ?  ";

        $id = $dtoUsuarioServicio->getId();
        $UsuarioModificacion = $dtoUsuarioServicio->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioModificacion);
        $pr->bindParam(2, $id);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryOperador(dto_servicio $dto) {
        $sql = " SELECT us.idusuario_servicio,u.idusuario,CONCAT_WS(' ',u.nombre,u.paterno,u.materno) AS 'operador' 
				FROM ca_usuario_servicio us INNER JOIN ca_usuario u ON u.idusuario=us.idusuario 
				WHERE us.estado=1 AND idservicio=? AND idtipo_usuario=2 ";

        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryGestorCampo(dto_servicio $dto) {
        $sql = " SELECT us.idusuario_servicio,u.idusuario,CONCAT_WS(' ',u.nombre,u.paterno,u.materno) AS 'operador' 
				FROM ca_usuario_servicio us INNER JOIN ca_usuario u ON u.idusuario=us.idusuario 
				WHERE us.estado=1 AND idservicio=? AND idtipo_usuario=3 ";
        $idservicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function check(dto_usuario $dtoUsuario, dto_servicio $dtoServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
            FROM ca_servicio s INNER JOIN  ca_usuario_servicio us INNER JOIN ca_usuario u ON u.idusuario=us.idusuario AND us.idservicio=s.idservicio
            WHERE u.dni=? AND u.clave=MD5(?) AND s.idservicio=? AND us.estado=1 AND u.estado=1 AND s.estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();
        $idServicio = $dtoServicio->getId();

        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        $pr->bindParam(3, $idServicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryLogin(dto_usuario $dtoUsuario, dto_servicio $dtoServicio) {
        $sql = " SELECT u.idusuario,
            CONCAT_WS(' ',u.nombre,u.paterno,u.materno) AS 'usuario',
            u.img_avatar AS 'avatar',u.dni,us.idusuario_servicio,
			IFNULL(s.idservicio,0) AS 'idservicio',	
            s.nombre AS 'servicio', s.call_center_ip, 
            IFNULL(s.prefijo,'') AS prefijo, 
            IFNULL(s.prefijo2,'') AS prefijo2, 
            IFNULL(s.prefijo_claro,'') AS prefijo_claro, 
            IFNULL(s.prefijo_claro2,'') AS prefijo_claro2, 
            IFNULL(s.prefijo_movistar,'') AS prefijo_movistar, 
            IFNULL(s.prefijo_movistar2,'') AS prefijo_movistar2, 
            IFNULL(s.prefijo_nextel,'') AS prefijo_nextel, 
            IFNULL(s.prefijo_nextel2,'') AS prefijo_nextel2, 
            IFNULL(s.prefijo_fijo,'') AS prefijo_fijo,
            IFNULL(s.prefijo_fijo2,'') AS prefijo_fijo2,
            IFNULL(s.prefijo_default,'') AS prefijo_default,                        
            s.interes, 
            s.descuento,
			( SELECT LOWER(TRIM(nombre)) FROM ca_privilegio WHERE idprivilegio=us.idprivilegio LIMIT 1 ) AS 'privilegio',
			s.is_interes_descuento, 
            s.is_monto_cobrar, 
            s.is_monto_vencido_por_vencer , 
            s.user_call_center, 
            s.password_call_center ,
            us.anexo 
            FROM ca_servicio s INNER JOIN ca_usuario_servicio us INNER JOIN ca_usuario u ON u.idusuario=us.idusuario AND us.idservicio=s.idservicio
            WHERE u.dni=? AND u.clave=md5(?) AND s.idservicio=? AND us.estado=1 AND u.estado=1 AND s.estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();
        $idServicio = $dtoServicio->getId();

        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        $pr->bindParam(3, $idServicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateInitSession($id) {
        $sql = " UPDATE ca_usuario_servicio SET sesion_activo = 1 WHERE idusuario_servicio = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateDeleteSession($id) {
        $sql = " UPDATE ca_usuario_servicio SET sesion_activo = 0 WHERE idusuario_servicio = ? ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryServiciosUsuario(dto_usuario $dto) {
        $sql = " SELECT ususer.idservicio,ser.nombre FROM ca_usuario_servicio ususer INNER JOIN ca_servicio ser 
				ON ser.idservicio=ususer.idservicio WHERE ususer.idusuario=? AND ususer.estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $id = $dto->getId();

        $pr->bindParam(1, $id);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryIdOperadorXServicio(dto_servicio $dto) {
        $sql = " SELECT idusuario_servicio FROM ca_usuario_servicio WHERE idservicio=? AND idtipo_usuario IN (2,3) AND estado=1 ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $id = $dto->getId();

        $pr->bindParam(1, $id);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryOperadorXServicio(dto_servicio $dto) {
        $sql = " SELECT ususer.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS'nombre' 
				FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario
				WHERE ususer.estado=1 AND ususer.idtipo_usuario IN (2,3) AND ususer.idservicio=? ORDER BY 3 ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $id = $dto->getId();

        $pr->bindParam(1, $id);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryUserById(dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT ususer.idusuario,ususer.idusuario_servicio,usu.nombre, 
				usu.paterno,usu.materno,usu.email,usu.dni,
				ususer.idtipo_usuario,ususer.idprivilegio,ususer.fecha_inicio,ususer.fecha_fin,ususer.usuario_creacion,
				IFNULL((SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario WHERE idusuario=ususer.usuario_creacion LIMIT 1),'') AS 'nombre_usuario_creacion'
				FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario
				WHERE ususer.idusuario_servicio=? ";

        $id = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryAllByService(dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT DISTINCT  ususer.idusuario_servicio,
                usu.codigo,
                CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS 'usuario',
                usu.dni,
                usu.email,
                pri.nombre AS 'privilegio',
                tipusu.nombre AS 'tipo_usuario',
                ususer.fecha_inicio,
                ususer.fecha_fin,
                usu.fecha_nacimiento,
                usu.genero,
                usu.estado_civil,
                usu.tipo_trabajo,
                usu.is_planilla
		FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_privilegio pri INNER JOIN ca_tipo_usuario  tipusu
		ON tipusu.idtipo_usuario=ususer.idtipo_usuario AND pri.idprivilegio=ususer.idprivilegio AND ususer.idusuario=usu.idusuario
		WHERE ususer.estado=1 AND usu.estado=1 AND tipusu.idtipo_usuario IN (2,3) AND ususer.idservicio=? ";

        $servicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function IsExists(dto_usuario $dtoUsuario, dto_servicio $dtoServicio) {
        $sql = "    SELECT 
                    COUNT(*) AS 'COUNT' 
        			FROM 
                    ca_usuario usu 
                    INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
                    WHERE 
                    usu.estado=1 AND 
                    ususer.estado=1 AND 
                    usu.dni = ? AND 
                    usu.clave=MD5(?) AND 
                    ususer.idservicio = ?
                    ";

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();
        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        $pr->bindParam(3, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function IsExistsAdmin(dto_usuario $dtoUsuario) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
			ON ususer.idusuario=usu.idusuario WHERE usu.estado=1 AND ususer.estado=1 AND usu.dni = ? 
			AND usu.clave=MD5(?) AND ususer.idtipo_usuario IN ( 4 ) AND ususer.idprivilegio IN ( 3 ) AND usu.estado = 1 AND ususer.estado = 1 ";

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function IsDateCorrect(dto_usuario $dtoUsuario, dto_servicio $dtoServicio) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
			ON ususer.idusuario=usu.idusuario WHERE usu.estado=1 AND ususer.estado=1 AND usu.dni = ? 
			AND usu.clave=MD5(?) AND ususer.idservicio = ? 
			AND usu.estado = 1 AND ususer.estado = 1 AND ususer.fecha_inicio <=CURDATE() AND ususer.fecha_fin >=CURDATE() ";

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();
        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        $pr->bindParam(3, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function IsDateCorrectAdmin(dto_usuario $dtoUsuario) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
			ON ususer.idusuario=usu.idusuario WHERE usu.estado=1 AND ususer.estado=1 AND usu.dni = ? 
			AND usu.clave=MD5(?) AND ususer.idtipo_usuario IN ( 4 ) AND ususer.idprivilegio IN ( 3 ) 
			AND usu.estado = 1 AND ususer.estado = 1 AND ususer.fecha_inicio <=CURDATE() AND ususer.fecha_fin >=CURDATE() ";

        $dni = $dtoUsuario->getDni();
        $clave = $dtoUsuario->getClave();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dni);
        $pr->bindParam(2, $clave);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryById(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT idusuario_servicio,idtipo_usuario,idprivilegio,fecha_inicio,fecha_fin,idservicio 
			FROM ca_usuario_servicio WHERE idusuario_servicio = ? ";

        $id = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryClusterById($idcluster) {

        $sql = " select idcluster,nombre, descripcion, estado from ca_cluster_usuario where idcluster=" . $idcluster;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
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
