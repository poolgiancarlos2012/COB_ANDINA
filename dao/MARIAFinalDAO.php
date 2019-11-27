<?php

class MARIAFinalDAO {
    /*     * **** davis ***** */

    public function insert(dto_final $dtoFinal) {

        $sql = "INSERT INTO ca_final ( idtipo_final,idcarga_final,idclase_final,idnivel,nombre,descripcion,fecha_creacion,usuario_creacion ) 
			VALUES ( ?,?,?,?,?,?,NOW(),? )";

        $nombre = $dtoFinal->getNombre();
        $descripcion = $dtoFinal->getDescripcion();
        $carga = $dtoFinal->getIdCargaFinal();
        $tipo = $dtoFinal->getIdTipoFinal();
        $clase = $dtoFinal->getIdClaseFinal();
        $nivel = $dtoFinal->getIdNivel();
        $usuario_creacion = $dtoFinal->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $tipo, PDO::PARAM_INT);
        $pr->bindParam(2, $carga, PDO::PARAM_INT);
        $pr->bindParam(3, $clase, PDO::PARAM_INT);
        $pr->bindParam(4, $nivel, PDO::PARAM_INT);
        $pr->bindParam(5, $nombre, PDO::PARAM_STR);
        $pr->bindParam(6, $descripcion, PDO::PARAM_STR);
        $pr->bindParam(7, $usuario_creacion, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_final $dtoFinal) {

        $sql = " UPDATE ca_final SET idtipo_final=? , idcarga_final=? , idclase_final=? , idnivel=? , nombre=? , descripcion=?,
			fecha_modificacion=NOW() , usuario_modificacion=? WHERE idfinal=? ";

        $id = $dtoFinal->getId();
        $nombre = $dtoFinal->getNombre();
        $descripcion = $dtoFinal->getDescripcion();
        $carga = $dtoFinal->getIdCargaFinal();
        $tipo = $dtoFinal->getIdTipoFinal();
        $clase = $dtoFinal->getIdClaseFinal();
        $nivel = $dtoFinal->getIdNivel();
        $usuario_modificacion = $dtoFinal->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $tipo, PDO::PARAM_INT);
        $pr->bindParam(2, $carga, PDO::PARAM_INT);
        $pr->bindParam(3, $clase, PDO::PARAM_INT);
        $pr->bindParam(4, $nivel, PDO::PARAM_INT);
        $pr->bindParam(5, $nombre, PDO::PARAM_STR);
        $pr->bindParam(6, $descripcion, PDO::PARAM_STR);
        $pr->bindParam(7, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(8, $id, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryById(dto_final $dtoFinal) {

        $sql = " SELECT idfinal,nombre,IFNULL(descripcion,'') AS 'descripcion',idtipo_final,idcarga_final,idclase_final,idnivel 
			FROM ca_final WHERE idfinal = ? ";

        $id = $dtoFinal->getId();

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

    public function delete(dto_final $dto) {
        $sql = "UPDATE ca_final SET estado=0,usuario_modificacion=?,fecha_modificacion=NOW() WHERE idfinal=?";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $dto->getUsuarioModificacion(), PDO::PARAM_STR);
        $pr->bindParam(2, $dto->getId(), PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryJQGRID($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT fin.idfinal, fin.nombre, IFNULL(tipfin.nombre,'') AS 'tipo_final', clafin.nombre AS 'clase_final',
				carfin.nombre AS 'carga_final',IFNULL(nv.nombre,'') AS 'nivel'
				FROM ca_final fin LEFT JOIN ca_tipo_final tipfin ON tipfin.idtipo_final = fin.idtipo_final 
				LEFT JOIN ca_clase_final clafin ON clafin.idclase_final = fin.idclase_final LEFT JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final
				LEFT JOIN ca_nivel nv ON nv.idnivel = fin.idnivel
				WHERE fin.estado = 1 $where ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function COUNT($where, $param) {
        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_final fin LEFT JOIN ca_tipo_final tipfin ON tipfin.idtipo_final = fin.idtipo_final 
				LEFT JOIN ca_clase_final clafin ON clafin.idclase_final = fin.idclase_final LEFT JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final
				LEFT JOIN ca_nivel nv ON nv.idnivel = fin.idnivel
				WHERE fin.estado = 1 $where ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function buscarFinal($id) {
        $sql = "SELECT
					  f.idfinal as id,
					  UPPER(f.nombre) nombre,
					  UPPER(f.descripcion) as descrip,
					  tf.nombre as idtipo,
					  clf.nombre as idclase,
					  cf.nombre as  idcarga,
					  n.nombre as idnivel
				  FROM
					ca_final f
				  INNER JOIN
					ca_tipo_final tf
				  INNER JOIN
					ca_clase_final clf
				  INNER JOIN
					ca_carga_final cf
				  INNER JOIN
					ca_nivel n
				  ON
					f.idtipo_final=tf.idtipo_final
				  AND
					f.idcarga_final=cf.idcarga_final
				  AND
					f.idclase_final=clf.idclase_final
				  AND
					f.idnivel = n.idnivel WHERE f.idfinal=? AND f.estado=1 ";
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

    /*     * ********* */

    public function queryIdName() {
        $sql = " SELECT idfinal,nombre FROM ca_final WHERE estado=1 ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryByCargaClaseTipoNivel(dto_final $dto) {
        $sql = " SELECT idfinal,nombre FROM ca_final WHERE idtipo_final=? AND idcarga_final=? 
				AND idclase_final=? AND idnivel=? AND estado=1 ";

        $tipoFinal = $dto->getIdTipoFinal();
        $cargaFinal = $dto->getIdCargaFinal();
        $claseFinal = $dto->getIdClaseFinal();
        $nivel = $dto->getIdNivel();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $tipoFinal);
        $pr->bindParam(2, $cargaFinal);
        $pr->bindParam(3, $claseFinal);
        $pr->bindParam(4, $nivel);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>