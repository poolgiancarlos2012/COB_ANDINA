<?php

class MARIADireccionDAO {

    public function UpdateGRD ( dto_direccion_ER2 $dtoDireccion ) {
        
        $usuario_modificacion=$dtoDireccion->getUsuarioModificacion();
        $referencia = $dtoDireccion->getReferencia();
        $iddireccion = $dtoDireccion->getId();
        
        $sql = " UPDATE ca_direccion 
                SET 
                referencia = ? , 
                usuario_modificacion = ? , 
                fecha_modificacion = NOW() 
                WHERE iddireccion = ? ";
        
        $factoryConnection= FactoryConnection::create('mysql'); 
        $connection = $factoryConnection->getConnection();
        
        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$referencia,PDO::PARAM_STR);
        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
        $pr->bindParam(3,$iddireccion,PDO::PARAM_INT);
        if( $pr->execute() ) {
            return true;
        }else{
            return false;
        }
        
        
    }

    public function UpdateStatus ( dto_direccion_ER2 $dtoDireccion ) {

                        $usuario_modificacion=$dtoDireccion->getUsuarioModificacion();
                        $status = $dtoDireccion->getStatus();
                        $observacion = $dtoDireccion->getObservacion();
                        $iddireccion = $dtoDireccion->getId();

                        $sql = " UPDATE ca_direccion 
                                SET status = ?, 
                                usuario_modificacion = ?, 
                                fecha_modificacion = NOW() 
                                WHERE iddireccion = ? ";

                        $factoryConnection= FactoryConnection::create('mysql');	
                        $connection = $factoryConnection->getConnection();

                        $pr = $connection->prepare( $sql );
                        $pr->bindParam(1,$status,PDO::PARAM_INT);
                        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
                        $pr->bindParam(3,$iddireccion,PDO::PARAM_INT);
                        if( $pr->execute() ) {
                                return true;
                        }else{
                                return false;
                        }

    }    
        
    public function UpdateObservacion ( dto_direccion_ER2 $dtoDireccion ) {

                        $usuario_modificacion=$dtoDireccion->getUsuarioModificacion();
                        $observacion = $dtoDireccion->getObservacion();
                        $iddireccion = $dtoDireccion->getId();

                        $sql = " UPDATE ca_direccion 
                                SET observacion = ?, usuario_modificacion = ?, fecha_modificacion = NOW() 
                                WHERE iddireccion = ? ";

                        $factoryConnection= FactoryConnection::create('mysql');	
                        $connection = $factoryConnection->getConnection();

                        $pr = $connection->prepare( $sql );
                        $pr->bindParam(1,$observacion,PDO::PARAM_INT);
                        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
                        $pr->bindParam(3,$iddireccion,PDO::PARAM_INT);
                        if( $pr->execute() ) {
                                return true;
                        }else{
                                return false;
                        }

    }

    public function ListarZonas(dto_direccion_ER2 $dtoDireccion) {

        $cartera = $dtoDireccion->getIdCartera();

        $sql = " SELECT TRIM(zona) AS 'zona' FROM ca_direccion WHERE idcartera = ? AND ISNULL(zona)=0 GROUP BY TRIM(zona) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function countClientesPorDepartamento(dto_direccion_ER2 $dtoDireccion) {

        $cartera = $dtoDireccion->getIdCartera();
        $departamento = $dtoDireccion->getDepartamento();

        $sql = " SELECT COUNT(DISTINCT codigo_cliente) AS 'COUNT'
				FROM ca_direccion WHERE TRIM(departamento) = ? AND idcartera = ?
				AND codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0  ) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $departamento, PDO::PARAM_STR);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array('COUNT' => 0);
        }
    }

    public function queryDepartamentos(dto_direccion_ER2 $dtoDireccion) {
        $cartera = $dtoDireccion->getIdCartera();
        $sql = " SELECT DISTINCT TRIM(departamento) AS 'departamento' 
			FROM ca_direccion 
			WHERE idcartera IN (" . $cartera . ") AND TRIM(departamento)!='' AND LOWER(TRIM(departamento))!='null' AND LENGTH(TRIM(departamento))<=20
			GROUP BY TRIM(departamento) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }
    public function queryProvincias(dto_direccion_ER2 $dtoDireccion,$departamento) {
        $cartera = $dtoDireccion->getIdCartera();
        $sql = " SELECT TRIM(provincia) AS 'provincia' 
            FROM ca_direccion 
            WHERE idcartera IN (" . $cartera . ") AND TRIM(provincia)!='' AND LOWER(TRIM(provincia))!='null' AND LENGTH(TRIM(provincia))<=20 and TRIM(departamento)='$departamento'
            GROUP BY TRIM(provincia) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }    
    

    public function insert(dto_direccion_ER2 $dtoDireccion, dto_cliente $dtoCliente, $cuentas ) {
        
        $codigo_cliente = $dtoCliente->getCodigo();
        
        $idcliente_cartera = $dtoDireccion->getIdClienteCartera();
        $cliente = $dtoDireccion->getIdCliente();
        $origen = $dtoDireccion->getIdOrigen();
        $cartera = $dtoDireccion->getIdCartera();
        $TipoReferencia = $dtoDireccion->getIdTipoReferencia();
        $direccion = $dtoDireccion->getDireccion();
        $referencia = $dtoDireccion->getReferencia();
        $ubigeo = $dtoDireccion->getUbigeo();
        $departamento = $dtoDireccion->getDepartamento();
        $provincia = $dtoDireccion->getProvincia();
        $distrito = $dtoDireccion->getDistrito();
        $observacion = $dtoDireccion->getObservacion();
        $UsuarioCreacion = $dtoDireccion->getUsuarioCreacion();
        $is_campo = $dtoDireccion->getIsCampo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        for( $i=0;$i<count($cuentas);$i++ ) {

                $sqlconsulta="select coddepartamento,codprovincia,coddistrito from ca_ubigeo_bbva where departamento=REPLACE('$departamento','Ã','Ñ') and provincia=REPLACE('$provincia','Ã','Ñ') and distrito=REPLACE('$distrito','Ã','Ñ') ";

                $pr = $connection->prepare($sqlconsulta);
                $pr->bindParam(1, $idDireccion);
                $pr->execute();
                $data=$pr->fetchAll(PDO::FETCH_ASSOC);

                $coddepartamento=$data[0]['coddepartamento'];
                $codprovincia=$data[0]['codprovincia'];
                $coddistrito=$data[0]['coddistrito'];

                $sql = " INSERT INTO ca_direccion ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, direccion, referencia, ubigeo, departamento, provincia, distrito, observacion, usuario_creacion, fecha_creacion, is_new, is_campo ) 
                VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),1,? ) ";
                
                $pr = $connection->prepare($sql);
                
                $pr->bindParam(1, $idcliente_cartera);
                $pr->bindParam(2, $cuentas[$i]['cuenta']);
                $pr->bindParam(3, $codigo_cliente);
                $pr->bindParam(4, $origen);
                $pr->bindParam(5, $cartera);
                $pr->bindParam(6, $TipoReferencia);
                $pr->bindParam(7, $direccion);
                $pr->bindParam(8, $referencia);
                $pr->bindParam(9, $ubigeo);
                //$pr->bindParam(10, $departamento);
                $pr->bindParam(10, $coddepartamento);                
                //$pr->bindParam(11, $provincia);
                $pr->bindParam(11, $codprovincia);
                //$pr->bindParam(12, $distrito);
                $pr->bindParam(12, $coddistrito);
                $pr->bindParam(13, $observacion);
                $pr->bindParam(14, $UsuarioCreacion);
                $pr->bindParam(15, $is_campo);
                
                if ( $pr->execute() ) {
                        
                    //return true;
                   
                } else {

                    //return false;
                    return array('rst'=>false,'msg'=>'Error al grabar direccion');
                    exit();
                }
        }
        $id = $connection->lastInsertId();
        return array('rst'=>true,'msg'=>'Direccion grabada correctamente','id'=>$id, 'cuenta'=>$cuentas[0]['cuenta']);
        
    }

    public function update(dto_direccion_ER2 $dtoDireccion) {
        $sql = " UPDATE ca_direccion SET direccion = ?, ubigeo = ?, departamento = ?,provincia = ?,distrito = ?,
			referencia = ?,observacion = ?,idorigen = ?,idtipo_referencia = ?,fecha_modificacion = NOW(), usuario_modificacion = ? WHERE iddireccion = ? ";

        $id = $dtoDireccion->getId();
        $origen = $dtoDireccion->getIdOrigen();
        $TipoReferencia = $dtoDireccion->getIdTipoReferencia();
        $direccion = $dtoDireccion->getDireccion();
        $referencia = $dtoDireccion->getReferencia();
        $ubigeo = $dtoDireccion->getUbigeo();
        $departamento = $dtoDireccion->getDepartamento();
        $provincia = $dtoDireccion->getProvincia();
        $distrito = $dtoDireccion->getDistrito();
        $observacion = $dtoDireccion->getObservacion();
        $UsuarioModificacion = $dtoDireccion->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $direccion);
        $pr->bindParam(2, $ubigeo);
        $pr->bindParam(3, $departamento);
        $pr->bindParam(4, $provincia);
        $pr->bindParam(5, $distrito);
        $pr->bindParam(6, $referencia);
        $pr->bindParam(7, $observacion);
        $pr->bindParam(8, $origen);
        $pr->bindParam(9, $TipoReferencia);
        $pr->bindParam(10, $UsuarioModificacion);
        $pr->bindParam(11, $id);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryDataById(dto_direccion_ER2 $dtoDireccion) {
        $sql = " SELECT iddireccion,TRIM(direccion) AS 'direccion',IFNULL(TRIM(ubigeo),'') AS 'ubigeo',
			IFNULL(TRIM(departamento),'') AS 'departamento',IFNULL(TRIM(provincia),'') AS 'provincia',IFNULL(TRIM(distrito),'') AS 'distrito',
			IFNULL(referencia,'') AS referencia,
                        TRIM(observacion) AS 'observacion',IFNULL(idorigen,'0') AS 'idorigen',
			IFNULL(idtipo_referencia ,'0') AS 'idtipo_referencia' 
			FROM ca_direccion WHERE iddireccion = ? ";

        $idDireccion = $dtoDireccion->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idDireccion);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryDataByCodeClient(dto_direccion_ER2 $dtoDireccion) {

        $sql = "    SELECT 
                    DISTINCT iddireccion,
                    codigo_cliente,
                    (select nombre from ca_tipo_referencia where ca_tipo_referencia.idtipo_referencia=ca_direccion.idtipo_referencia) AS Origen, direccion,
                    IFNULL((select departamento from ca_ubigeo_bbva where codigo=concat(ca_direccion.departamento,ca_direccion.provincia,ca_direccion.distrito)),IFNULL(ca_direccion.departamento,'')) AS 'departamento', 
                    IFNULL((select provincia from ca_ubigeo_bbva where codigo=concat(ca_direccion.departamento,ca_direccion.provincia,ca_direccion.distrito)),IFNULL(ca_direccion.provincia,'')) AS 'provincia', 
                    IFNULL((select distrito from ca_ubigeo_bbva where codigo=concat(ca_direccion.departamento,ca_direccion.provincia,ca_direccion.distrito)),IFNULL(ca_direccion.distrito,'')) AS 'distrito'
				    FROM 
                    ca_direccion 
                    WHERE 
                    -- idcartera IN (  ? ) AND 
                    codigo_cliente = ? AND 
                    idtipo_referencia not in (4) AND 
                    estado=1
                    GROUP BY idtipo_referencia, direccion, departamento, provincia, distrito 
                    ORDER BY iddireccion DESC LIMIT 4 ";

        // echo $sql;
        // exit();

        $idcartera = $dtoDireccion->getIdCartera();
        $codigo_cliente = $dtoDireccion->getCodigoCliente();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $codigo_cliente, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rolBack();
            return array();
        }
    }

    public function queryDataByCodeClientVisita(dto_direccion_ER2 $dtoDireccion) {

        $sql = "    SELECT 
                    DISTINCT iddireccion,
                    (select nombre from ca_tipo_referencia where ca_tipo_referencia.idtipo_referencia=ca_direccion.idtipo_referencia) AS Origen, 
                    CONCAT(IFNULL((select nombre from ca_tipo_referencia where ca_tipo_referencia.idtipo_referencia=ca_direccion.idtipo_referencia),''),'-',direccion) as direccion, 
                    IFNULL(departamento,'') AS 'departamento', 
                    IFNULL(provincia,'') AS 'provincia', 
                    IFNULL(distrito,'') AS 'distrito' 
                    FROM ca_direccion 
                    WHERE 
                    -- idcartera IN (  SELECT idcartera FROM ca_cartera WHERE cartera_act = ? OR idcartera = ? ) AND 
                    codigo_cliente = ? -- AND 
                    -- idtipo_referencia not in (4) 
                    GROUP BY idtipo_referencia, direccion, departamento, provincia, distrito ORDER BY iddireccion DESC LIMIT 4 ";

        $idcartera = $dtoDireccion->getIdCartera();
        $codigo_cliente = $dtoDireccion->getCodigoCliente();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        //$pr->bindParam(2, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(1, $codigo_cliente, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rolBack();
            return array();
        }
    }    

}

?>
