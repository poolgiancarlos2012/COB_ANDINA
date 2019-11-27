<?php

class PGSQL_PDOTelefonoDAO {

    public function inactive(dto_telefono_ER2 $dtoTelefono) {

        $idTelefono = $dtoTelefono->getId();

        $sql = " UPDATE ca_telefono SET estado = 0 WHERE idtelefono = ? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idTelefono, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insert(dto_telefono_ER2 $dtoTelefono, dto_cliente $dtoCliente) {
        //$sql=" INSERT INTO ca_telefono ( idcliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, idlinea_telefono, numero, anexo, observacion, usuario_creacion, fecha_creacion ) 
        //VALUES( ?,?,?,?,?,?,?,?,?,?,NOW() ) ";
//			$sql=" INSERT INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, idlinea_telefono, numero, anexo, observacion, usuario_creacion, fecha_creacion ) 
//			VALUES( ?,?,?,?,?,?,?,?,?,?,NOW() ) ";

        $sql = " INSERT INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, idlinea_telefono, numero, anexo, observacion, usuario_creacion, fecha_creacion, is_new, idcliente ) 
			VALUES( ?,?,?,?,?,?,?,?,?,?,NOW(),1,? ) ";

        /*         * *** */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * ** */
        $cliente = $dtoTelefono->getIdCliente();
        $origen = $dtoTelefono->getIdOrigen();
        $cartera = $dtoTelefono->getIdCartera();
        $TipoReferencia = $dtoTelefono->getIdTipoReferencia();
        $LineaTelefono = $dtoTelefono->getIdLineaTelefono();
        $TipoTelefono = $dtoTelefono->getIdTipoTelefono();
        $numero = $dtoTelefono->getNumero();
        $anexo = $dtoTelefono->getAnexo();
        $observacion = $dtoTelefono->getObservacion();
        $UsuarioCreacion = $dtoTelefono->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * ** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ** */
        $pr->bindParam(2, $origen);
        $pr->bindParam(3, $cartera);
        $pr->bindParam(4, $TipoReferencia);
        $pr->bindParam(5, $TipoTelefono);
        $pr->bindParam(6, $LineaTelefono);
        $pr->bindParam(7, $numero);
        $pr->bindParam(8, $anexo);
        $pr->bindParam(9, $observacion);
        $pr->bindParam(10, $UsuarioCreacion);
        $pr->bindParam(11, $cliente);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function UpdateNumero(dto_telefono_ER2 $dtoTelefono) {
        $sql = " UPDATE ca_telefono SET numero = ? WHERE idtelefono = ? ";

        $id = $dtoTelefono->getId();
        $numero = $dtoTelefono->getNumero();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $numero);
        $pr->bindParam(2, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_telefono_ER2 $dtoTelefono) {

        $id = $dtoTelefono->getId();
        $TipoReferencia = $dtoTelefono->getIdTipoReferencia();
        $TipoTelefono = $dtoTelefono->getIdTipoTelefono();
        $LineaTelefono = $dtoTelefono->getIdLineaTelefono();
        $numero = $dtoTelefono->getNumero();
        $anexo = $dtoTelefono->getAnexo();
        $observacion = $dtoTelefono->getObservacion();
        $UsuarioModificacion = $dtoTelefono->getUsuarioModificacion();

        $sql = " UPDATE ca_telefono SET numero = ?, anexo = ?, idtipo_telefono = ? , idtipo_referencia = ? ,
			idlinea_telefono = ? , observacion = ? , fecha_modificacion = NOW() , usuario_modificacion = ? 
			WHERE idtelefono = ? ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $numero);
        $pr->bindParam(2, $anexo);
        $pr->bindParam(3, $TipoTelefono);
        $pr->bindParam(4, $TipoReferencia);
        $pr->bindParam(5, $LineaTelefono);
        $pr->bindParam(6, $observacion);
        $pr->bindParam(7, $UsuarioModificacion);
        $pr->bindParam(8, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryById(dto_telefono_ER2 $dtoTelefono) {

        $sql = " SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', IFNULL(idtipo_telefono,0) AS 'idtipo_telefono', 
			IFNULL(idorigen,'') AS 'idorigen', IFNULL(idtipo_referencia,0) AS 'idtipo_referencia', 
			IFNULL(idlinea_telefono,0) AS 'idlinea_telefono',IFNULL(observacion,'') AS 'observacion' 
			FROM ca_telefono WHERE idtelefono = ? ";

        $id = $dtoTelefono->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function queryTelefonosPorNombreCliente(dto_cliente $dtoCliente) {

        $nombre = $dtoCliente->getNombre();

//			$sql=" SELECT DISTINCT lla.idtelefono,cli.codigo,TRIM(CONCAT_WS('',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				( SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
//				tel.numero,tel.anexo,
//				( SELECT nombre FROM ca_origen where idorigen=tel.idorigen LIMIT 1 )  AS 'origen',
//				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
//				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
//				IFNULL(( SELECT nombre FROM ca_linea_telefono WHERE idlinea_telefono=tel.idlinea_telefono LIMIT 1 ),'') AS 'linea_telefono'
//				FROM ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel INNER JOIN ca_cliente cli 
//				ON cli.idcliente=tel.idcliente AND tel.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion 
//				WHERE CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) LIKE '%$nombre%' ";
//			$sql=" SELECT DISTINCT(tel.numero) AS 'numero',tel.idtelefono,tel.anexo,
//				( SELECT nombre FROM ca_origen WHERE idorigen=tel.idorigen LIMIT 1 ) AS 'origen',
//				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
//				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
//				cli.idcliente,cli.codigo,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				(SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
//				IFNULL(( SELECT carfin.nombre FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel2 
//				ON tel2.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion AND tran.idfinal=fin.idfinal AND fin.idcarga_final=carfin.idcarga_final 
//				WHERE TRIM(tel2.numero)=TRIM(tel.numero) ORDER BY tran.idtransaccion DESC LIMIT 1  ),'') AS 'carga_final'
//				FROM ca_cliente cli INNER JOIN ca_telefono tel ON tel.idcliente=cli.idcliente
//				WHERE CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) LIKE '%$nombre%'  GROUP BY TRIM(tel.numero); ";

        $sql = " SELECT DISTINCT(tel.numero) AS 'numero',tel.idtelefono,tel.anexo,
				( SELECT nombre FROM ca_origen WHERE idorigen=tel.idorigen LIMIT 1 ) AS 'origen',
				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
				cli.idcliente,cli.codigo,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				(SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
				IFNULL(( SELECT carfin.nombre FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel2 
				ON tel2.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion AND tran.idfinal=fin.idfinal AND fin.idcarga_final=carfin.idcarga_final 
				WHERE TRIM(tel2.numero)=TRIM(tel.numero) ORDER BY tran.idtransaccion DESC LIMIT 1  ),'') AS 'carga_final'
				FROM ca_cliente cli INNER JOIN ca_telefono tel ON tel.idcliente=cli.idcliente 
				WHERE CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) LIKE '%$nombre%' GROUP BY TRIM(tel.numero); ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function importTelefonos($ids, dto_telefono_ER2 $dtoTelefono) {

        $UsuarioCreacion = $dtoTelefono->getUsuarioCreacion();
        $cliente = $dtoTelefono->getIdCliente();
        $cartera = $dtoTelefono->getIdCartera();

//			$sql=" INSERT INTO ca_telefono( numero,is_import,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,idcartera,idcliente,fecha_creacion,usuario_creacion )
//			SELECT numero,1,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,$cartera,$cliente,NOW(),$UsuarioCreacion 
//			FROM ca_telefono WHERE idtelefono IN ( $ids ) ";

        $sql = " INSERT INTO ca_telefono( numero,is_import,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,idcartera,codigo_cliente,fecha_creacion,usuario_creacion )
			SELECT numero,1,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,$cartera,
			( SELECT codigo FROM ca_cliente WHERE idcliente = ? LIMIT 1 ),
			NOW(),$UsuarioCreacion 
			FROM ca_telefono WHERE idtelefono IN ( $ids ) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cliente);
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