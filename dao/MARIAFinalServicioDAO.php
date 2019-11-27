<?php

class MARIAFinalServicioDAo {

    public function UpdatePesoPrioridad(dto_final_servicios $dto) {

        $sql = " UPDATE ca_final_servicio 
                SET fecha_modificacion = NOW(), usuario_modificacion = ?, peso = ?, prioridad = ? , codigo = ? , flg_volver_llamar = ? , estado_observa = ? 
		WHERE idfinal_servicio = ? ";

        $idfinal_servicio = $dto->getId();
        $prioridad = $dto->getPrioridad();
        $peso = $dto->getPeso();
        $codigo = $dto->getCodigo();
        $flg_volver_llamar= $dto->getFlgVolverLlamar();
        $usuario_modificacion = $dto->getUsuarioModificacion();
        $estado_observa = $dto->getEstadoObserva();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $ps = $connection->prepare($sql);
        $ps->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $ps->bindParam(2, $peso, PDO::PARAM_INT);
        $ps->bindParam(3, $prioridad, PDO::PARAM_INT);
        $ps->bindParam(4, $codigo, PDO::PARAM_STR);
        $ps->bindParam(5, $flg_volver_llamar, PDO::PARAM_STR);
		$ps->bindParam(6, $estado_observa, PDO::PARAM_STR);
		$ps->bindParam(7, $idfinal_servicio, PDO::PARAM_INT);
        if ($ps->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insert(dto_final_servicios $dto) {

        $sql = "INSERT INTO ca_final_servicio (idservicio,idfinal,prioridad,usuario_creacion,fecha_creacion,peso,efecto) 
			VALUES (?,?,?,?,NOW(),?,?)";

        $final = $dto->getIdFinal();
        $servicio = $dto->getIdServicio();
        $usuario_creacion = $dto->getUsuarioCreacion();
        $prioridad = $dto->getPrioridad();
        $peso = $dto->getPeso();
        $efecto = $dto->getEfecto();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $ps = $connection->prepare($sql);
        $ps->bindParam(1, $servicio, PDO::PARAM_INT);
        $ps->bindParam(2, $final, PDO::PARAM_INT);
        $ps->bindParam(3, $prioridad, PDO::PARAM_INT);
        $ps->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
        /*         * ***** */
        $ps->bindParam(5, $peso, PDO::PARAM_INT);
        $ps->bindParam(6, $efecto, PDO::PARAM_STR);
        /*         * ***** */
        if ($ps->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function checkFinal(dto_final_servicios $dtoFinalServicio) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_final_servicio WHERE idfinal = ? AND idservicio = ? AND estado = 1 ";

        $final = $dtoFinalServicio->getIdFinal();
        $servicio = $dtoFinalServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $ps = $connection->prepare($sql);
        $ps->bindParam(1, $final, PDO::PARAM_INT);
        $ps->bindParam(2, $servicio, PDO::PARAM_INT);
        if ($ps->execute()) {
            //$connection->commit();
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 1));
        }
    }

    public function delete(dto_final_servicios $dtoFinalServicios) {
        $sql = " UPDATE ca_final_servicio SET estado = 0 , usuario_modificacion = ? , fecha_modificacion = NOW()
			WHERE idfinal_servicio = ? ";

        $id = $dtoFinalServicios->getId();
        $usuario_modificacion = $dtoFinalServicios->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $ps = $connection->prepare($sql);
        $ps->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $ps->bindParam(2, $id, PDO::PARAM_INT);
        if ($ps->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_final_servicios $dto) {
        $sql = "UPDATE ca_final_servicio SET idservicio=?,idfinal=?,estado=?,usuario_creacion=?,fecha_creacion=? WHERE idfinal_servicio=?";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $ps = $connection->prepare($sql);
        $ps->bindParam(1, $dto->getIdServicio(), PDO::PARAM_INT);
        $ps->bindParam(2, $dto->getIdFinal(), PDO::PARAM_INT);
        $ps->bindParam(3, $dto->getEstado(), PDO::PARAM_STR);
        $ps->bindParam(4, $dto->getUsuarioCreacion(), PDO::PARAM_INT);
        $ps->bindParam(5, $dto->getFechaCreacion(), PDO::PARAM_STR);
        $ps->bindParam(6, $dto->getId(), PDO::PARAM_INT);
        if ($ps->execute()) {
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function searchFinalenServicio($final, $servicio) {
        $sql = "SELECT COUNT(*) as 'count' FROM ca_final_servicio WHERE idfinal=? AND idservicio=?";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        //$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $final);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function queryJQGRID($sidx, $sord, $start, $limit, dto_final_servicios $dtoFinalServicios) {

        $sql = "SELECT fs.idfinal_servicio as id,DATE(fs.fecha_creacion) as 'fecha_registro',
					IFNULL(fs.prioridad,'') AS 'prioridad',
					UPPER(fn.nombre) as 'nombre_final',
                                        IFNULL( fs.codigo,'' ) as 'codigo',
					IFNULL( fs.peso , '' ) AS 'peso',
					IFNULL( fs.efecto, '' ) AS 'efecto',
					( SELECT clafin.nombre FROM ca_final fin INNER JOIN ca_clase_final clafin 
					ON clafin.idclase_final = fin.idclase_final WHERE fin.idfinal = fn.idfinal ) AS 'clase_final',
                    fs.flg_volver_llamar, fs.estado_observa 
					FROM ca_final fn INNER JOIN ca_final_servicio fs
					ON fn.idfinal=fs.idfinal WHERE fs.estado=1 AND fs.idservicio = ?
					ORDER BY $sidx $sord LIMIT $start , $limit ";

        $servicio = $dtoFinalServicios->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function COUNT(dto_final_servicios $dtoFinalServicios) {
        $sql = "SELECT COUNT(*) AS 'COUNT' FROM ca_final_servicio WHERE estado=1 AND idservicio = ? ";

        $servicio = $dtoFinalServicios->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array("COUNT" => 0));
        }
    }

    /*     * ************* */

    /*     * *************** */

    public function queryStateLlamadaByServicio(dto_servicio $dtoServicio) {

        $sql = "    SELECT carfin.nombre AS 'CARGA', carfin.idcarga_final,
    				GROUP_CONCAT( CONCAT(
                        fin.idfinal,'@#',
                        IFNULL( CONCAT(niv.nombre,' / '),''),
                        fin.nombre,'@#',
                        finser.peso,'@#',
                        IFNULL(finser.codigo,''),
                        '@#',IFNULL(finser.flg_volver_llamar,'0'),
    					'@#',IFNULL(finser.estado_observa,''),
                        '@#',IFNULL(finser.flag_compromiso_pago,''),
                        '@#',IFNULL(finser.dias_fecha_cp,'')
                        ) ORDER BY fin.nombre ASC SEPARATOR '|' ) AS 'data'
    				FROM ca_nivel niv
    				RIGHT JOIN ca_final fin ON fin.idnivel = niv.idnivel
    				INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin.idcarga_final 
    				INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal
    				WHERE 
                    finser.idservicio = ? AND 
                    fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='llamada' ) AND 
                    finser.estado = 1 AND
                    fin.idcarga_final NOT IN (3,7)
    				GROUP BY carfin.nombre
                    ORDER BY finser.prioridad ASC
                    ";

        // echo $sql;
        // exit();

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();
		$sqlGC = "SET SESSION group_concat_max_len=102400";
		$prGC = $connection->prepare($sqlGC);
		$prGC->execute();
//        $pr = $connection->prepare($sqlGC);
//		$pr->execute();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function queryStateCuentaByServicio(dto_servicio $dtoServicio) {

//			$sql = " SELECT fin.idfinal , fin.nombre
//				FROM ca_final fin INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal
//				WHERE finser.idservicio = ? AND fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='cuenta' ) ";
//			$sql = " SELECT fin.idfinal , fin.nombre , carfin.nombre AS 'CARGA'
//				FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
//				ON carfin.idcarga_final = fin.idcarga_final AND finser.idfinal = fin.idfinal
//				WHERE finser.idservicio = ? AND fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='cuenta' )
//				ORDER BY 3, 2 ";

        $sql = " SELECT carfin.nombre AS 'CARGA',
				GROUP_CONCAT( CONCAT(fin.idfinal,'@#',fin.nombre,'@#',finser.peso) SEPARATOR '|' ) AS 'data'
				FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_final_servicio finser 
				ON carfin.idcarga_final = fin.idcarga_final AND finser.idfinal = fin.idfinal
				WHERE finser.idservicio = ? AND fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='cuenta' )
				GROUP BY carfin.nombre
				ORDER BY 1 ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }
    
    public function queryStateCuotificacion( dto_servicio $dtoServicio ) {
        
        $sql = " SELECT fin.idfinal , 
                 fin.nombre 
                 FROM ca_final fin INNER JOIN ca_final_servicio finser 
                 ON finser.idfinal = fin.idfinal 
                 WHERE finser.idservicio = ? AND finser.estado = 1 AND fin.idclase_final = 8  ";
        
        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
         
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
         
            return array();
        }
        
    }

    public function queryStateVisitaByServicio(dto_servicio $dtoServicio) {

        $sql = "    SELECT 
                    fin.idfinal , 
                    IFNULL( finser.codigo,'' ) AS codigo, 
                    IFNULL(finser.peso,0) AS peso,
                    fin.idcarga_final,
                    -- CONCAT( IFNULL(CONCAT( ( SELECT nombre FROM ca_nivel WHERE idnivel = fin.idnivel ),' ---> '),''),CONCAT(finser.codigo,' ',fin.nombre) ) AS 'nombre'
    				fin.nombre AS 'nombre'
                    FROM ca_final fin INNER JOIN ca_final_servicio finser 
    				ON finser.idfinal = fin.idfinal 
    				WHERE finser.idservicio = ? AND finser.estado = 1 AND fin.idclase_final = 2 
    				ORDER BY finser.codigo ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }
    
    public function queryStateLlamada2ByServicio(dto_servicio $dtoServicio) {

        $sql = " SELECT fin.idfinal , CONCAT( IFNULL(finser.codigo,''), ' ', IFNULL(CONCAT(( SELECT nombre FROM ca_nivel WHERE idnivel = fin.idnivel ),' --> '),''), fin.nombre ) AS 'nombre'
                                FROM ca_final fin INNER JOIN ca_final_servicio finser 
                                ON finser.idfinal = fin.idfinal 
                                WHERE finser.idservicio = ? AND finser.estado = 1 AND fin.idclase_final = 1 
                                ORDER BY finser.codigo ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryStateAgendaByServicio(dto_servicio $dtoServicio) {

        $sql = " SELECT fin.idfinal , fin.nombre
				FROM ca_final fin INNER JOIN ca_final_servicio finser ON finser.idfinal = fin.idfinal
				WHERE finser.idservicio = ? AND fin.idclase_final IN ( SELECT idclase_final FROM ca_clase_final WHERE LOWER(nombre)='agendar' ) ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    /*     * ************* */

    public function queryByServicio(dto_servicio $dtoServicio, dto_final $dtoFinal) {
        $sql = " SELECT idfinal,nombre FROM ca_final  WHERE idnivel=? AND  idtipo_final=? AND idcarga_final=? 
				AND idclase_final=? AND 
				idfinal IN ( SELECT idfinal FROM ca_final_servicio WHERE idservicio=? ) ";

        $servicio = $dtoServicio->getId();
        $TipoFinal = $dtoFinal->getIdTipoFinal();
        $CargaFinal = $dtoFinal->getIdCargaFinal();
        $ClaseFinal = $dtoFinal->getIdClaseFinal();
        $nivel = $dtoFinal->getIdNivel();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $nivel);
        $pr->bindParam(2, $TipoFinal);
        $pr->bindParam(3, $CargaFinal);
        $pr->bindParam(4, $ClaseFinal);
        $pr->bindParam(5, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryCargaByServicio(dto_servicio $dtoServicio) {
        $sql = " SELECT fin.idcarga_final, carfin.nombre
				FROM ca_final_servicio finser INNER JOIN ca_final fin  INNER JOIN ca_carga_final carfin
				ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = finser.idfinal 
				WHERE finser.idservicio = ? 
				GROUP BY fin.idcarga_final ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
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
