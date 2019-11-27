<?php

class MARIANoticeDAO {

    public function insert(dto_notice $dtoNotice) {

        $sql = " INSERT INTO ca_noticia ( titulo, descripcion, fecha_creacion, usuario_creacion, idusuario_servicio ) 
				VALUES ( ?,?,NOW(),?,? ) ";

        $titulo = $dtoNotice->getTitulo();
        $descripcion = $dtoNotice->getDescripcion();
        $usuario_creacion = $dtoNotice->getUsuarioCreacion();
        $idusuario_servicio = $dtoNotice->getIdUsuarioServicio();
        $idservicio = $dtoNotice->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $titulo, PDO::PARAM_STR);
        $pr->bindParam(2, $descripcion, PDO::PARAM_STR);
        $pr->bindParam(3, $usuario_creacion, PDO::PARAM_INT);
        $pr->bindParam(4, $idusuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {

            $idnoticia = $connection->lastInsertId();

            $sqlUsuarioServicio = " SELECT ususer.idusuario_servicio , CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario'
						FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = ? ";
            $prUsu = $connection->prepare($sqlUsuarioServicio);
            $prUsu->bindParam(1, $idusuario_servicio, PDO::PARAM_INT);
            $prUsu->execute();
            $dataUsu = $prUsu->fetchAll(PDO::FETCH_ASSOC);

            if ($cnSqlite = new SQLiteDatabase(ROOT_COBRAST_SQLITE)) {

                @$cnSqlite->queryExec(" CREATE TABLE ca_noticia (
											idnoticia INT PRIMARY KEY, 
											titulo TEXT,
											descripcion TEXT, 
											fecha_creacion DATETIME , 
											usuario_creacion INT,
											idusuario_servicio INT, 
											nombre_usuario_servicio VARCHAR(100), 
											estado INT DEFAULT 1,
											idservicio INT ,
											dar_baja INT DEFAULT 0,
											demardar INT DEFAULT 0,
											razon_demanda TEXT
											) ");

                $sqlite = " INSERT INTO ca_noticia ( idnoticia, titulo, descripcion, fecha_creacion, usuario_creacion, idusuario_servicio, nombre_usuario_servicio, estado, idservicio ) 
							VALUES( " . $idnoticia . ", '" . $titulo . "', '" . $descripcion . "', '" . date("Y-m-d H:i:s") . "', " . $usuario_creacion . ", " . $idusuario_servicio . ", '" . $dataUsu[0]['usuario'] . "',1, " . $idservicio . " ) ";

                if ($cnSqlite->queryExec($sqlite)) {
                    //$connection->beginTransaction();
                    return true;
                } else {
                    //$connection->rollBack();
                    return false;
                }
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryByServiceNoticeToDaySQLITE(dto_notice $dtoNotice) {

        $idservicio = $dtoNotice->getIdServicio();

        $sqlite = " SELECT idnoticia, titulo, descripcion, fecha_creacion, usuario_creacion, 
				idusuario_servicio, nombre_usuario_servicio, estado, idservicio 
				FROM ca_noticia WHERE idservicio = " . $idservicio . " AND DATE(fecha_creacion) = DATE('NOW') AND estado = 1 ORDER BY fecha_creacion DESC ";

        if ($cnSqlite = new SQLiteDatabase(ROOT_COBRAST_SQLITE)) {

            $data = array();
            $sqliteResult = $cnSqlite->query($sqlite, SQLITE_ASSOC);
            while ($row = $sqliteResult->fetch()) {
                array_push($data, $row);
            }

            return $data;
        } else {
            return array();
        }
    }

}

?>