<?php

class PGSQL_PDODireccionDAO {

    public function ListarZonas(dto_direccion_ER2 $dtoDireccion) {

        $cartera = $dtoDireccion->getIdCartera();

        $sql = " SELECT TRIM(zona) AS 'zona' FROM ca_direccion WHERE idcartera = ? AND ISNULL(zona)=0 GROUP BY TRIM(zona) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
        $sql = " SELECT TRIM(departamento) AS 'departamento' 
			FROM ca_direccion 
			WHERE idcartera IN (" . $cartera . ") AND TRIM(departamento)!='' AND LOWER(TRIM(departamento))!='null' AND LENGTH(TRIM(departamento))<=20
			GROUP BY TRIM(departamento) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function insert(dto_direccion_ER2 $dtoDireccion, dto_cliente $dtoCliente) {
        //$sql=" INSERT INTO ca_direccion ( idcliente, idorigen, idcartera, idtipo_referencia, direccion, referencia, ubigeo, departamento, provincia, distrito, observacion, usuario_creacion, fecha_creacion ) 
        //VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,NOW() ) ";

        $sql = " INSERT INTO ca_direccion ( codigo_cliente, idorigen, idcartera, idtipo_referencia, direccion, referencia, ubigeo, departamento, provincia, distrito, observacion, usuario_creacion, fecha_creacion, is_new, idcliente ) 
			VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,NOW(),1,? ) ";

        /*         * * */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * */

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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * * */
        $pr->bindParam(1, $codigo_cliente);
        /*         * * */
        $pr->bindParam(2, $origen);
        $pr->bindParam(3, $cartera);
        $pr->bindParam(4, $TipoReferencia);
        $pr->bindParam(5, $direccion);
        $pr->bindParam(6, $referencia);
        $pr->bindParam(7, $ubigeo);
        $pr->bindParam(8, $departamento);
        $pr->bindParam(9, $provincia);
        $pr->bindParam(10, $distrito);
        $pr->bindParam(11, $observacion);
        $pr->bindParam(12, $UsuarioCreacion);
        $pr->bindParam(13, $cliente);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
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

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
			referencia,TRIM(observacion) AS 'observacion',IFNULL(idorigen,'0') AS 'idorigen',
			IFNULL(idtipo_referencia ,'0') AS 'idtipo_referencia' 
			FROM ca_direccion WHERE iddireccion = ? ";

        $idDireccion = $dtoDireccion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

        $sql = " SELECT iddireccion, direccion, IFNULL(departamento,'') AS 'departamento', IFNULL(provincia,'') AS 'provincia', 
				IFNULL(distrito,'') AS 'distrito' 
				FROM ca_direccion WHERE idcartera IN (  SELECT idcartera FROM ca_cartera WHERE cartera_act = ? OR idcartera = ? ) AND codigo_cliente = ? ORDER BY iddireccion DESC LIMIT 3 ";

        $idcartera = $dtoDireccion->getIdCartera();
        $codigo_cliente = $dtoDireccion->getCodigoCliente();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(3, $codigo_cliente, PDO::PARAM_STR);
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
