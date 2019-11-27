<?php

class PGSQL_PDOjqgridDAO {
    /*     * * Ventana de Atencion y Digitacion Busqueda Base ************* */

    public function JQGRIDRowsSearchBaseByNumberAccount($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cuenta $dtoCuenta) {

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $cuenta = $dtoCuenta->getNumeroCuenta();

        //$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,clicar.estado,clicar.retiro,clicar.motivo_retiro,
//			cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//			IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
//			ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente 
//			WHERE cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ?
//			AND cli.codigo IN ( SELECT codigo_cliente FROM ca_cuenta WHERE idcartera= ? AND numero_cuenta LIKE '$cuenta%'  ) 
//			ORDER BY $sidx $sord LIMIT $start , $limit  ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
			cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
			IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_cartera car INNER JOIN ca_campania cam
			ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente
			WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cu.numero_cuenta LIKE '%$cuenta%' 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        //$pr->bindParam(3,$cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByNumberAccount(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cuenta $dtoCuenta) {

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $cuenta = $dtoCuenta->getNumeroCuenta();

//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//			ON clicar.codigo_cliente=cli.codigo 
//			WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1  
//			AND cli.codigo IN ( SELECT codigo_cliente FROM ca_cuenta WHERE idcartera= ? AND numero_cuenta LIKE '$cuenta%'  ) ";

        $sql = " SELECT COUNT(DISTINCT clicar.idcliente_cartera) AS 'COUNT' 
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_cartera car INNER JOIN ca_campania cam
			ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente
			WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cu.numero_cuenta LIKE '%$cuenta%' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        //$pr->bindParam(3,$cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsSearchBaseByPhone($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_telefono $dtoTelefono) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $numero = $dtoTelefono->getNumero();

//			$sql=" SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.estado, clicar.retiro, clicar.motivo_retiro, 
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_telefono tel INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo AND cli.codigo=tel.codigo_cliente  
//				WHERE clicar.idcartera = ?  AND cli.idservicio = ? AND cli.estado=1 
//				AND tel.idcartera = ? AND tel.numero LIKE '$numero%' 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_telefono tel INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente AND cli.idcliente = tel.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND tel.numero = '$numero' 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        //$pr->bindParam(3,$cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByPhone(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_telefono $dtoTelefono) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $numero = $dtoTelefono->getNumero();

//			$sql=" SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT' 
//				FROM ca_telefono tel INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo AND cli.codigo=tel.codigo_cliente  
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND tel.idcartera = ? AND tel.numero LIKE '$numero%' ";

        $sql = " SELECT COUNT(DISTINCT clicar.idcliente_cartera) AS 'COUNT' 
				FROM ca_telefono tel INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente AND cli.idcliente = tel.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND tel.numero = '$numero' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        //$pr->bindParam(3,$cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsSearchBaseByName($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $nombre = $dtoCliente->getNombre();

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.estado, clicar.retiro, clicar.motivo_retiro,
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1  
//				AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%$nombre%' 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%$nombre%'
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByName(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $nombre = $dtoCliente->getNombre();

        //$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%$nombre%' ";

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%$nombre%' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsSearchBaseByCodigo($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $codigo = $dtoCliente->getCodigo();

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,clicar.estado, clicar.retiro, clicar.motivo_retiro,
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.codigo LIKE '$codigo%' ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.codigo LIKE '$codigo%' 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByCodigo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $codigo = $dtoCliente->getCodigo();

//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo  
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.codigo LIKE '$codigo%' ";

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.codigo LIKE '$codigo%' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        /* $pr->bindParam(3,$UsuarioServicio); */
        //$pr->bindParam(4,$codigo);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsSearchBaseByDni($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $NumeroDocumento = $dtoCliente->getNumeroDocumento();

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,clicar.estado,cli.idservicio, clicar.retiro, clicar.motivo_retiro,
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.numero_documento LIKE '$NumeroDocumento%' ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.numero_documento LIKE '$NumeroDocumento%' 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByDni(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $NumeroDocumento = $dtoCliente->getNumeroDocumento();

//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.numero_documento LIKE '$NumeroDocumento%' ";

        $sql = " SELECT COUNT(DISTINCT clicar.idcliente_cartera) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.numero_documento LIKE '$NumeroDocumento%' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsSearchBaseByRuc($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $TipoDocumento = $dtoCliente->getTipoDocumento();

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,clicar.estado, clicar.retiro, clicar.motivo_retiro,
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.tipo_documento LIKE '$TipoDocumento%' 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT DISTINCT clicar.idcliente_cartera,cli.idcliente,car.nombre_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.tipo_documento LIKE '$TipoDocumento%'  
				ORDER BY $sidx $sord LIMIT $start , $limit ";


        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountSearchBaseByRuc(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $TipoDocumento = $dtoCliente->getTipoDocumento();

//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 
//				AND cli.tipo_documento LIKE '$TipoDocumento%' ";

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.tipo_documento LIKE '$TipoDocumento%' ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********* Servicio******** */

    public function JQGRIDRowsServicioUsuarioAdmin($sidx, $sord, $start, $limit) {
        $sql = " SELECT usu.idusuario,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',
				usu.dni,usu.email,usu.fecha_creacion,GROUP_CONCAT(ser.nombre) as 'servicios'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (1,4) AND ususer.estado=1 GROUP BY usu.idusuario 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

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

    public function JQGRIDCountServicioUsuarioAdmin() {
        $sql = " SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (1,4) AND ususer.estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsServicioUsuarioOpera($sidx, $sord, $start, $limit) {
        $sql = " SELECT usu.idusuario,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',
				usu.dni,usu.email,usu.fecha_creacion,GROUP_CONCAT(ser.nombre) as 'servicios'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 GROUP BY usu.idusuario 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

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

    public function JQGRIDCountServicioUsuarioOpera() {
        $sql = " SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * *****Usuario******* */

//		public function JQGRIDRowsUsuarioOperadoresActivosPorServicio ( $sidx, $sord, $start, $limit, dto_usuario_servicio $dtoUsuarioServicio )  {
    public function JQGRIDRowsUsuarioOperadoresActivosPorServicio($sidx, $sord, $start, $limit, $search, $param, $searchQuery) {
//			$sql=" SELECT DISTINCT usu.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',usu.email,
//				( SELECT nombre FROM ca_tipo_usuario WHERE idtipo_usuario=ususer.idtipo_usuario LIMIT 1) AS 'tipo_usuario'
//				FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario
//				WHERE idtipo_usuario IN (2,3) AND usu.estado = 1 AND ususer.estado = 1 AND ususer.idservicio=? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',usu.email,usu.dni,
//				( SELECT nombre FROM ca_tipo_usuario WHERE idtipo_usuario=ususer.idtipo_usuario LIMIT 1) AS 'tipo_usuario',
//				( SELECT nombre FROM ca_privilegio WHERE idprivilegio=ususer.idprivilegio LIMIT 1 ) AS 'privilegio',
//				ususer.fecha_inicio,ususer.fecha_fin,DATE(usu.fecha_creacion) AS 'fecha_registro' 
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
//				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',usu.email,usu.dni,
				( SELECT nombre FROM ca_tipo_usuario WHERE idtipo_usuario=ususer.idtipo_usuario LIMIT 1) AS 'tipo_usuario',
				( SELECT nombre FROM ca_privilegio WHERE idprivilegio=ususer.idprivilegio LIMIT 1 ) AS 'privilegio',
				ususer.fecha_inicio,ususer.fecha_fin,DATE(usu.fecha_creacion) AS 'fecha_registro' 
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = :servicio $search $searchQuery
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$servicio=$dtoUsuarioServicio->getIdServicio();
        //echo $sql;
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountUsuarioOperadoresActivosPorServicio ( dto_usuario_servicio $dtoUsuarioServicio ) {
    public function JQGRIDCountUsuarioOperadoresActivosPorServicio($search, $param, $searchQuery) {
//			$sql=" SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT'
//				FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario
//				WHERE idtipo_usuario IN (2,3) AND usu.estado = 1 AND ususer.estado = 1 AND idservicio=? ";
//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
//				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND usu.estado=1 AND ususer.idservicio = :servicio $search $searchQuery ";


        //$servicio=$dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Usuario Admin ******* */

    public function JQGRIDRowsUsuarioAdminOperadoresActivosPorServicio($sidx, $sord, $start, $limit) {
        $sql = " SELECT DISTINCT usu.idusuario,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario',usu.email,
				( SELECT GROUP_CONCAT( nombre ) FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer ON ususer.idservicio=ser.idservicio 
				WHERE ususer.idusuario=usu.idusuario AND ususer.estado=1 LIMIT 1 ) AS 'servicio'
				FROM ca_usuario usu WHERE usu.estado = 1
				ORDER BY $sidx $sord LIMIT $start , $limit ";

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

    public function JQGRIDCountUsuarioAdminOperadoresActivosPorServicio() {
        $sql = " SELECT COUNT( usu.idusuario ) AS 'COUNT'
				FROM ca_usuario usu
				WHERE usu.estado = 1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******** Atencion Cliente Telefonos *********** */

    public function JQGRIDRowsAtencionClienteTelefono($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

//			$sql=" SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
//				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL(tel.observacion,'') AS 'observacion'
//				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
//				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
//				WHERE tel.codigo_cliente = ? AND tel.idcartera = ? AND tel.estado = 1 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT t1.idtelefono, t1.numero, t1.anexo, t1.tipo_telefono, t1.origen, t1.tipo_referencia, t1.observacion
				FROM
				(
				SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL(tel.observacion,'') AS 'observacion'
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.idcartera = ? AND tel.estado = 1 AND tel.is_new = 0 
				UNION 
				SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL(tel.observacion,'') AS 'observacion'
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.estado = 1 AND tel.is_new = 1
				) t1 GROUP BY t1.numero ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ** */
        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteTelefono(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
//				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
//				WHERE tel.codigo_cliente=? AND tel.idcartera=? AND tel.estado = 1 ";

        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM
				(
				SELECT tel.idtelefono 
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.idcartera = ? AND tel.estado = 1 AND tel.is_new = 0
				UNION 
				SELECT tel.idtelefono
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.estado = 1 AND tel.is_new = 1
				) t1 ";

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * *** */
        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Solo Numero Telefonos *********** */

    public function JQGRIDRowsAtencionClienteNumeroTelefono($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

//			$sql=" SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo'
//				FROM ca_telefono WHERE idcartera = ?  AND codigo_cliente = ? AND estado = 1 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT t1.idtelefono, t1.numero, t1.anexo, t1.is_new
				FROM
				(
				SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', is_new
				FROM ca_telefono WHERE idcartera = ?  AND codigo_cliente = ? AND estado = 1 AND is_new = 0 
				UNION
				SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', is_new
				FROM ca_telefono WHERE codigo_cliente = ? AND estado = 1 AND is_new = 1
				) t1 GROUP BY t1.numero ORDER BY $sidx $sord LIMIT $start , $limit ";

        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $codigo_cliente);
        $pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteNumeroTelefono(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_telefono WHERE idcartera = ?  AND codigo_cliente = ? AND estado = 1 ";

        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM
				(
				SELECT idtelefono, numero
				FROM ca_telefono WHERE idcartera = ?  AND codigo_cliente = ? AND estado = 1 AND is_new = 0
				UNION
				SELECT idtelefono, numero
				FROM ca_telefono WHERE codigo_cliente = ? AND estado = 1 AND is_new = 1
				) t1 GROUP BY t1.numero ";

        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $codigo_cliente);
        $pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    /*     * ********* Atencion Cliente Direcciones ************ */

    public function JQGRIDRowsAtencionClienteDireccion($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT refcli.idreferencia_cliente,TRIM(dir.direccion) AS 'direccion',TRIM(dir.referencia) AS 'referencia' ,
//				dir.ubigeo AS 'ubigeo',TRIM(dir.distrito) AS 'distrito' ,TRIM(dir.provincia) AS 'provincia',TRIM(dir.departamento) AS 'departamento',
//				tipref.nombre AS 'tipo_referencia',org.nombre AS 'origen',refcli.observacion
//				FROM ca_referencia_cliente refcli INNER JOIN ca_direccion dir INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND  dir.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=2 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT dir.iddireccion , IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo', 
//				IFNULL(dir.departamento,'') AS 'departamento', IFNULL(dir.provincia,'') AS 'provincia', IFNULL(dir.distrito,'') AS 'distrito', 
//				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia', IFNULL(dir.observacion,'') AS 'observacion' 
//				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
//				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
//				WHERE dir.idcliente = ? AND dir.idcartera = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT dir.iddireccion , IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo', 
				IFNULL(dir.departamento,'') AS 'departamento', IFNULL(dir.provincia,'') AS 'provincia', IFNULL(dir.distrito,'') AS 'distrito', 
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia', IFNULL(dir.observacion,'') AS 'observacion', IFNULL(dir.codigo_postal,'')  AS 'codigo_postal'
				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
				WHERE dir.codigo_cliente = ? AND dir.idcartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * * */
        $pr->bindParam(1, $codigo_cliente);
        /*         * * */
        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteDireccion(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_referencia_cliente refcli INNER JOIN ca_direccion dir INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND  dir.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=2 ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref  
				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
				WHERE dir.codigo_cliente = ? AND dir.idcartera = ? ";

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * * */
        $pr->bindParam(1, $codigo_cliente);
        /*         * * */
        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Llamada ************* */

    public function JQGRIDRowsAtencionClienteLlamada($sidx, $sord, $start, $limit, dto_cliente_cartera $dtoClienteCartera) {
        //$sql=" SELECT DISTINCT trans.idtransaccion,cli.idcliente,DATE(trans.fecha),trans.observacion,
//				(SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1) AS 'final',
//				IFNULL( (SELECT TRIM(CONCAT_WS(' ',nombre,paterno,materno)) FROM ca_usuario WHERE idusuario=trans.usuario_creacion LIMIT 1 ),'') AS 'operador'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion trans ON
//				trans.idcliente_cartera=clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente 
//				WHERE clicar.idcliente_cartera=? AND trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=1 )
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT gescu.idgestion_cuenta,
				DATE(tran.fecha_creacion) AS 'fecha_creacion',
				( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=gescu.idcuenta  ) AS 'numero_cuenta',
				( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono  ) AS 'telefono',
				( SELECT nombre FROM ca_final WHERE idfinal=tran.idfinal ) AS 'estado',
				DATE(lla.fecha) AS 'fecha_llamada',
				TIME(lla.fecha) AS 'hora_llamada',
				( SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = tran.idusuario_servicio ) AS 'teleoperador',
				IFNULL(DATE(gescu.fecha_cp),'') AS 'fecha_cp',
				IFNULL(gescu.monto_cp,'') AS 'monto_cp',
				IFNULL(tran.observacion,'') AS 'observacion'
				FROM ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu
				ON gescu.idllamada=lla.idllamada AND lla.idtransaccion=tran.idtransaccion
				WHERE tran.idcliente_cartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $ClienteCartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteLlamada(dto_cliente_cartera $dtoClienteCartera) {
//			$sql=" SELECT COUNT( DISTINCT trans.idtransaccion ) AS 'COUNT'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion trans ON
//				trans.idcliente_cartera=clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente 
//				WHERE clicar.idcliente_cartera=? AND trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=1 ) ";
        //$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel 
//				ON tel.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion
//				WHERE tran.idcliente_cartera = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu
			ON gescu.idllamada=lla.idllamada AND lla.idtransaccion=tran.idtransaccion
			WHERE tran.idcliente_cartera = ? ";

        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $ClienteCartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Cuenta  **************** */

    public function JQGRIDRowsAtencionClienteCuenta($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT idcuenta,numero_cuenta,TRUNCATE( total_deuda , 2 )  AS 'total_deuda'
//				FROM ca_cuenta WHERE idcliente_cartera = ? AND idcartera = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT idcuenta,numero_cuenta,TRUNCATE( total_deuda , 2 ) AS 'total_deuda', telefono 
//				FROM ca_cuenta WHERE codigo_cliente = ? AND idcartera = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT idcuenta,numero_cuenta,moneda,TRUNCATE( total_deuda , 2 ) AS 'total_deuda', 
				telefono , TRUNCATE( monto_pagado,2 ) AS 'monto_pagado',
				IFNULL( TRUNCATE( ( ( total_deuda + total_comision ) - monto_pagado ),2),'') AS 'saldo',
				TRUNCATE(total_comision,2) AS 'total_comision' 
				FROM ca_cuenta WHERE codigo_cliente = ? AND idcartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$ClienteCartera=$dtoClienteCartera->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ** */
        //$pr->bindParam(1,$ClienteCartera);
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteCuenta(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_cuenta WHERE idcliente_cartera = ? AND idcartera = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM ca_cuenta WHERE codigo_cliente = ? AND idcartera = ? ";

        //$ClienteCartera=$dtoClienteCartera->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ** */
        //$pr->bindParam(1,$ClienteCartera);
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******** Atencion Cliente Detalle Cuenta ( Operacion ) ******** */

    public function JQGRIDRowsAtencionClienteDetalleCuenta($sidx, $sord, $start, $limit, dto_cuenta $dtoCuenta, dto_cartera $dtoCartera) {
        //$sql=" SELECT iddetalle_cuenta,
//				codigo_operacion,moneda,refinanciamiento,numero_cuotas,numero_cuotas_pagadas,dias_mora,
//				TRUNCATE(total_deuda,2) AS 'total_deuda',
//				TRUNCATE(total_deuda_soles,2) AS 'total_deuda_soles',
//				TRUNCATE(total_deuda_dolares,2) AS 'total_deuda_dolares',
//				TRUNCATE(monto_mora,2) AS 'monto_mora',
//				TRUNCATE(monto_mora_soles,2) AS 'monto_mora_soles',
//				TRUNCATE(monto_mora_dolares,2) AS 'monto_mora_dolares',
//				TRUNCATE(saldo_capital,2) AS 'saldo_capital',
//				TRUNCATE(saldo_capital_soles,2) AS 'saldo_capital_soles',
//				TRUNCATE(saldo_capital_dolares,2) AS 'saldo_capital_dolares',
//				DATE(fecha_asignacion) AS 'fecha_asignacion',
//				tramo FROM ca_detalle_cuenta WHERE idcuenta=? ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT iddetalle_cuenta,
//				codigo_operacion,moneda,refinanciamiento,numero_cuotas,numero_cuotas_pagadas,dias_mora,
//				TRUNCATE(total_deuda,2) AS 'total_deuda',
//				TRUNCATE(total_deuda_soles,2) AS 'total_deuda_soles',
//				TRUNCATE(total_deuda_dolares,2) AS 'total_deuda_dolares',
//				TRUNCATE(monto_mora,2) AS 'monto_mora',
//				TRUNCATE(monto_mora_soles,2) AS 'monto_mora_soles',
//				TRUNCATE(monto_mora_dolares,2) AS 'monto_mora_dolares',
//				TRUNCATE(saldo_capital,2) AS 'saldo_capital',
//				TRUNCATE(saldo_capital_soles,2) AS 'saldo_capital_soles',
//				TRUNCATE(saldo_capital_dolares,2) AS 'saldo_capital_dolares',
//				DATE(fecha_asignacion) AS 'fecha_asignacion',
//				tramo FROM ca_detalle_cuenta WHERE numero_cuenta = ? AND idcartera = ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT iddetalle_cuenta,
				codigo_operacion,moneda,refinanciamiento,numero_cuotas,numero_cuotas_pagadas,dias_mora,
				TRUNCATE(total_deuda,2) AS 'total_deuda',
				TRUNCATE(total_deuda_soles,2) AS 'total_deuda_soles',
				TRUNCATE(total_deuda_dolares,2) AS 'total_deuda_dolares',
				TRUNCATE(monto_mora,2) AS 'monto_mora',
				TRUNCATE(monto_mora_soles,2) AS 'monto_mora_soles',
				TRUNCATE(monto_mora_dolares,2) AS 'monto_mora_dolares',
				TRUNCATE(saldo_capital,2) AS 'saldo_capital',
				TRUNCATE(saldo_capital_soles,2) AS 'saldo_capital_soles',
				TRUNCATE(saldo_capital_dolares,2) AS 'saldo_capital_dolares',
				DATE(fecha_asignacion) AS 'fecha_asignacion',
				TRUNCATE(comision,2) AS 'comision',
				tramo 
				FROM ca_detalle_cuenta WHERE numero_cuenta = ? AND idcartera = ? AND moneda = ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cuenta=$dtoCuenta->getId();
        $numero_cuenta = $dtoCuenta->getNumeroCuenta();
        /*         * ** */
        $cartera = $dtoCartera->getId();
        /*         * ** */
        $moneda = $dtoCuenta->getMoneda();
        /*         * *** */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $numero_cuenta);
        $pr->bindParam(2, $cartera);
        /*         * **** */
        $pr->bindParam(3, $moneda);
        /*         * *** */
        //$pr->bindParam(1,$cuenta);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteDetalleCuenta(dto_cuenta $dtoCuenta, dto_cartera $dtoCartera) {
        //$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_detalle_cuenta WHERE idcuenta=? ";
//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_detalle_cuenta WHERE numero_cuenta = ? AND idcartera = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM ca_detalle_cuenta WHERE numero_cuenta = ? AND idcartera = ? AND moneda = ? ";

        //$cuenta=$dtoCuenta->getId();
        $numero_cuenta = $dtoCuenta->getNumeroCuenta();
        $cartera = $dtoCartera->getId();
        /*         * ****** */
        $moneda = $dtoCuenta->getMoneda();
        /*         * ****** */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * * */
        $pr->bindParam(1, $numero_cuenta);
        $pr->bindParam(2, $cartera);
        /*         * * */
        $pr->bindParam(3, $moneda);
        /*         * * */

        //$pr->bindParam(1,$cuenta);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******* Atencion Cliente Pago ************ */

    public function JQGRIDRowsAtencionClientePago($sidx, $sord, $start, $limit, dto_detalle_cuenta $dtoDetalleCuenta, dto_cliente_cartera $dtoClienteCartera) {
        //$sql=" SELECT idpago,monto,moneda,fecha
//				FROM ca_pago WHERE iddetalle_cuenta=? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";		
//			$sql=" SELECT idpago,TRUNCATE(monto,2) AS 'monto',moneda,fecha
//				FROM ca_pago WHERE iddetalle_cuenta=? AND estado = 1 AND idcartera_pago IN ( SELECT idcartera_pago FROM ca_cartera_pago WHERE idcartera=? )
//				ORDER BY $sidx $sord LIMIT $start , $limit ";		
//			$sql=" SELECT idpago,TRUNCATE(monto,2) AS 'monto',IFNULL(moneda,'') AS 'moneda',IFNULL(fecha,'') AS 'fecha',
//				TRUNCATE(total_deuda,2) AS 'total_deuda',TRUNCATE(monto_mora,2) AS 'monto_mora',TRUNCATE(saldo_capital,2) AS 'saldo_capital',IFNULL(dias_mora,'') AS 'dias_mora' 
//				FROM ca_pago WHERE codigo_operacion = ? AND estado = 1 AND idcartera = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";		

        $sql = " SELECT idpago,TRUNCATE(monto_pagado,2) AS 'monto_pagado',IFNULL(moneda,'') AS 'moneda',IFNULL(fecha,'') AS 'fecha_pago',
				IFNULL(fecha_envio,'') AS 'fecha_envio',IFNULL( TRUNCATE( total_deuda,2 ),'' ) AS 'total_deuda', IFNULL( TRUNCATE( monto_mora,2 ),'' ) AS 'monto_mora',
				IFNULL( TRUNCATE( saldo_capital,2 ),'' ) AS 'saldo_capital', IFNULL( dias_mora,'' ) AS 'dias_mora', IFNULL( TRIM(agencia),'' ) AS 'agencia'
				FROM ca_pago WHERE codigo_operacion = ? AND estado = 1 AND idcartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$DetalleCuenta=$dtoDetalleCuenta->getId();
        $codigo_operacion = $dtoDetalleCuenta->getCodigoOperacion();
        /*         * * */
        $cartera = $dtoClienteCartera->getIdCartera();
        /*         * * */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $codigo_operacion);
        //$pr->bindParam(1,$DetalleCuenta);
        /*         * ** */
        $pr->bindParam(2, $cartera);
        /*         * * */
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClientePago(dto_detalle_cuenta $dtoDetalleCuenta, dto_cliente_cartera $dtoClienteCartera) {
        //$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_pago WHERE iddetalle_cuenta=? ";
//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_pago WHERE iddetalle_cuenta=? AND estado = 1 
//				AND idcartera_pago = ( SELECT idcartera_pago FROM ca_cartera_pago WHERE idcartera=? ) ";		

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_pago WHERE codigo_operacion = ? AND estado = 1 
				AND idcartera = ? ";

        //$DetalleCuenta=$dtoDetalleCuenta->getId();
        $codigo_operacion = $dtoDetalleCuenta->getCodigoOperacion();
        /*         * * */
        $cartera = $dtoClienteCartera->getIdCartera();
        /*         * * */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $codigo_operacion);
        //$pr->bindParam(1,$DetalleCuenta);
        /*         * * */
        $pr->bindParam(2, $cartera);
        /*         * * */
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ************** Digitacion Telefonos ****************** */

    public function JQGRIDRowsDigitacionTelefonos($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT refcli.idreferencia_cliente,tel.idtelefono,tel.numero,tel.anexo,
//				(SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
//				tipref.nombre as 'tipo_referencia',org.nombre as 'origen',refcli.observacion
//				FROM ca_referencia_cliente refcli INNER JOIN ca_telefono tel INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND tel.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=1 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
//				org.nombre AS 'origen', tipref.nombre AS 'referencia',IFNULL(tel.observacion,'') AS 'observacion'
//				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
//				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
//				WHERE tel.idcliente = ? AND tel.idcartera = ?  
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
				org.nombre AS 'origen', tipref.nombre AS 'referencia',IFNULL(tel.observacion,'') AS 'observacion'
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.idcartera = ?  
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cliente=$dtoCliente->getId();
        /*         * ******* */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * ******* */
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * ***** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ***** */
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountDigitacionTelefonos(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_referencia_cliente refcli INNER JOIN ca_telefono tel INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND tel.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=1 ";
        //$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
//				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
//				WHERE tel.idcliente = ? AND tel.idcartera = ?  ";

        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.idcartera = ?  ";

        //$cliente=$dtoCliente->getId();
        /*         * ******* */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * ******* */
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * ****** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ****** */
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * *********** Digitacion Direcciones *************** */

    public function JQGRIDRowsDigitacionDirecciones($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
        //$sql=" SELECT refcli.idreferencia_cliente,TRIM(dir.direccion) AS 'direccion',TRIM(dir.referencia) AS 'referencia' ,
//				dir.ubigeo AS 'ubigeo',TRIM(dir.distrito) AS 'distrito' ,TRIM(dir.provincia) AS 'provincia',TRIM(dir.departamento) AS 'departamento',
//				tipref.nombre AS 'tipo_referencia',org.nombre AS 'origen',refcli.observacion
//				FROM ca_referencia_cliente refcli INNER JOIN ca_direccion dir INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND  dir.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=2 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT dir.iddireccion , IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo',
//				IFNULL(dir.departamento,'') AS 'departamento', IFNULL(dir.provincia,'') AS 'provincia', IFNULL(dir.distrito,'') AS 'distrito',
//				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL( dir.observacion,'' ) AS 'observacion'
//				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
//				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
//				WHERE dir.idcliente = ? AND dir.idcartera = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT dir.iddireccion , IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo',
				IFNULL(dir.departamento,'') AS 'departamento', IFNULL(dir.provincia,'') AS 'provincia', IFNULL(dir.distrito,'') AS 'distrito',
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL( dir.observacion,'' ) AS 'observacion'
				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
				WHERE dir.codigo_cliente = ? AND dir.idcartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cliente=$dtoCliente->getId();
        /*         * ****** */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * ****** */
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * ******** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ******** */
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountDigitacionDirecciones(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {
//			$sql=" SELECT COUNT(*) AS 'COUNT'
//				FROM ca_referencia_cliente refcli INNER JOIN ca_direccion dir INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_origen org
//				ON org.idorigen=refcli.idorigen AND tipref.idtipo_referencia=refcli.idtipo_referencia 
//				AND  dir.idreferencia_cliente=refcli.idreferencia_cliente 
//				WHERE refcli.idcliente=? AND refcli.estado=1 AND refcli.idclase=2 ";
//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
//				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
//				WHERE dir.idcliente = ? AND dir.idcartera = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
				WHERE dir.codigo_cliente = ? AND dir.idcartera = ? ";

        //$cliente=$dtoCliente->getId();
        /*         * ****** */
        $codigo_cliente = $dtoCliente->getCodigo();
        /*         * ****** */
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cliente);
        /*         * ****** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * ****** */
        $pr->bindParam(2, $cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Atencion Cliente Matriz de Busqueda ********* */

    public function JQGRIDRowsAtencionClienteMatrizBusqueda($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
//			$sql=" SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) as 'nombre',cli.dni,cli.ruc
//				FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcartera=car.idcartera
//				WHERE car.idcampania=? AND clicar.idusuario_servicio=? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,
//				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre',
//				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.idcliente=cli.idcliente 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND clicar.estado = 1 AND cli.estado=1 AND clicar.idusuario_servicio = ? 
//				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,clicar.estado,clicar.retiro,clicar.motivo_retiro,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 AND clicar.idusuario_servicio = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $servicio);
        $pr->bindParam(3, $UsuarioServicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteMatrizBusqueda(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
//			$sql=" SELECT COUNT(*) AS 'COUNT' 
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.idcliente=cli.idcliente 
//				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND clicar.estado = 1 AND cli.estado=1 AND clicar.idusuario_servicio = ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 AND clicar.idusuario_servicio = ? ";

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $servicio);
        $pr->bindParam(3, $UsuarioServicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Atencion Cliente Busqueda Estado ********* */

    public function JQGRIDRowsAtencionClienteBusquedaEstado($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_transaccion $dtoTransaccion) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,clicar.estado,clicar.retiro,clicar.motivo_retiro,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre',
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento', 
				COUNT(*) AS 'llamadas' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla
				ON clicar.codigo_cliente=cli.codigo AND lla.idtransaccion = tran.idtransaccion AND tran.idcliente_cartera = clicar.idcliente_cartera 
				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 AND clicar.idusuario_servicio = ?  
				AND clicar.idcliente_cartera IN ( SELECT idcliente_cartera FROM ca_transaccion WHERE idfinal = ? ) 
				GROUP BY clicar.codigo_cliente 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $idfinal = $dtoTransaccion->getIdFinal();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $servicio, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $idfinal, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteBusquedaEstado(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_transaccion $dtoTransaccion) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera = ? AND cli.idservicio = ? AND cli.estado=1 AND clicar.idusuario_servicio = ? 
				AND clicar.idcliente_cartera IN ( SELECT idcliente_cartera FROM ca_transaccion WHERE idfinal = ? ) ";

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $idfinal = $dtoTransaccion->getIdFinal();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $servicio, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $idfinal, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Busqueda Gestionados ************** */

    //public function JQGRIDRowsAtencionClienteBusquedaGestionados ( $sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio ) {
    public function JQGRIDRowsAtencionClienteBusquedaGestionados($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.estado,clicar.retiro,clicar.motivo_retiro,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre', 
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				COUNT(*) AS 'llamadas' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla 
				ON clicar.codigo_cliente=cli.codigo AND lla.idtransaccion = tran.idtransaccion AND tran.idcliente_cartera = clicar.idcliente_cartera
				WHERE clicar.idcartera IN(" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio AND clicar.estado = 1 
				AND cli.estado=1 AND clicar.idusuario_servicio = :operador  AND clicar.id_ultima_llamada != 0 $where 
				GROUP BY clicar.codigo_cliente 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /* $pr->bindParam(1,$cartera,PDO::PARAM_INT);
          $pr->bindParam(2,$servicio,PDO::PARAM_INT);
          $pr->bindParam(3,$UsuarioServicio,PDO::PARAM_INT); */
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountAtencionClienteBusquedaGestionados ( dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio ) {
    public function JQGRIDCountAtencionClienteBusquedaGestionados($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera IN (" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio AND cli.estado=1 
				AND clicar.idusuario_servicio = :operador AND clicar.id_ultima_llamada != 0  $where ";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /* $pr->bindParam(1,$cartera,PDO::PARAM_INT);
          $pr->bindParam(2,$servicio,PDO::PARAM_INT);
          $pr->bindParam(3,$UsuarioServicio,PDO::PARAM_INT); */
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Busqueda Sin Gestion ************* */

    //public function JQGRIDRowsAtencionClienteBusquedaSinGestion ( $sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio ) {
    public function JQGRIDRowsAtencionClienteBusquedaSinGestion($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.estado, clicar.retiro, clicar.motivo_retiro,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre', 
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera IN (" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio 
				AND cli.estado=1 AND clicar.idusuario_servicio = :operador  AND clicar.id_ultima_llamada = 0 $where 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /* $pr->bindParam(1,$cartera,PDO::PARAM_INT);
          $pr->bindParam(2,$servicio,PDO::PARAM_INT);
          $pr->bindParam(3,$UsuarioServicio,PDO::PARAM_INT); */
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountAtencionClienteBusquedaSinGestion ( dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio ) {
    public function JQGRIDCountAtencionClienteBusquedaSinGestion($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera IN (" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio AND cli.estado=1 
				AND clicar.idusuario_servicio = :operador AND clicar.id_ultima_llamada = 0 $where	";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /* $pr->bindParam(1,$cartera,PDO::PARAM_INT);
          $pr->bindParam(2,$servicio,PDO::PARAM_INT);
          $pr->bindParam(3,$UsuarioServicio,PDO::PARAM_INT); */
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Atencion Cliente Agendados *********** */

    public function JQGRIDRowsAtencionClienteAgendados($sidx, $sord, $start, $limit, $fecha_inicio, $fecha_fin, dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera, dto_cliente $dtoCliente) {
//			$sql=" SELECT DISTINCT trans.idtransaccion,clicar.idcliente_cartera,cli.idcliente,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',
//				IFNULL(cli.numero_documento,'') AS 'numero_documento',IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
//				IFNULL((SELECT fecha_cp FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
//				IFNULL((SELECT monto_cp FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
//				IFNULL((SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=trans.idtipo_gestion LIMIT 1),'') AS 'tipo_gestion',
//				IFNULL((SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1 ),'') AS 'final'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=3 ) AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=?
//				AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ORDER BY $sidx $sord LIMIT $start , $limit";

        $sql = " SELECT DISTINCT trans.idtransaccion,clicar.idcliente_cartera,cli.idcliente,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IFNULL((SELECT DATE(fecha_cp) FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
				IFNULL((SELECT TRUNCATE(monto_cp,2) FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
				IFNULL((SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=trans.idtipo_gestion LIMIT 1),'') AS 'tipo_gestion',
				IFNULL((SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1 ),'') AS 'final'
				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
				ON cli.codigo=clicar.codigo_cliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=3 ) 
				AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=?
				AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ORDER BY $sidx $sord LIMIT $start , $limit";

        $cartera = $dtoCartera->getId();
        /*         * ***** */
        $servicio = $dtoCliente->getIdServicio();
        /*         * ***** */
        $UsuarioServicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * **** */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * **** */
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $fecha_inicio, PDO::PARAM_STR);
        $pr->bindParam(5, $fecha_fin, PDO::PARAM_STR);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteAgendados($fecha_inicio, $fecha_fin, dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera, dto_cliente $dtoCliente) {
//			$sql=" SELECT COUNT( DISTINCT trans.idtransaccion ) AS 'COUNT' 
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=3 ) AND clicar.idcartera=? AND clicar.estado=1 
//				AND clicar.idusuario_servicio=?	AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ";

        $sql = " SELECT COUNT( DISTINCT trans.idtransaccion ) AS 'COUNT' 
				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
				ON cli.codigo=clicar.codigo_cliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=3 ) 
				AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idusuario_servicio=?	AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ";

        $cartera = $dtoCartera->getId();
        /*         * ***** */
        $servicio = $dtoCliente->getIdServicio();
        /*         * ***** */
        $UsuarioServicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ***** */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * ***** */
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $fecha_inicio, PDO::PARAM_STR);
        $pr->bindParam(5, $fecha_fin, PDO::PARAM_STR);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Digitacion Agendados ********* */

    //public function JQGRIDRowsDigitacionVisita ( $sidx, $sord, $start, $limit, $fecha_inicio, $fecha_fin, dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, dto_cliente_cartera $dtoClienteCartera) {
        //$sql=" SELECT DISTINCT trans.idtransaccion,clicar.idcliente_cartera,cli.idcliente,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',
//				cli.dni,IFNULL(cli.ruc,'') as 'ruc',
//				IFNULL((SELECT fecha_cp FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
//				IFNULL((SELECT monto_cp FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
//				IFNULL((SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=trans.idtipo_gestion LIMIT 1),'') AS 'tipo_gestion',
//				IFNULL((SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1 ),'') AS 'final'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_cartera car 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcliente_cartera=trans.idcliente_cartera
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=2 ) AND car.idcampania=? AND clicar.idusuario_servicio=?
//				AND DATE(trans.fecha) BETWEEN ? AND ? ORDER BY $sidx $sord LIMIT $start , $limit";
//			$sql=" SELECT DISTINCT trans.idtransaccion,clicar.idcliente_cartera,cli.idcliente,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',
//				IFNULL(cli.numero_documento,'') AS 'numero_documento',IFNULL(cli.tipo_documento,'') as 'tipo_documento',
//				IFNULL((SELECT DATE(fecha_cp) FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
//				IFNULL((SELECT monto_cp FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
//				IFNULL((SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=trans.idtipo_gestion LIMIT 1),'') AS 'tipo_gestion',
//				IFNULL((SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1 ),'') AS 'final'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=2 ) AND clicar.idcartera=? AND clicar.estado=1 
//				AND clicar.idusuario_servicio=?	AND cli.estado=1 
//				AND DATE(trans.fecha) BETWEEN ? AND ? ORDER BY $sidx $sord LIMIT $start , $limit ";
//			$sql=" SELECT DISTINCT trans.idtransaccion,clicar.idcliente_cartera,cli.idcliente,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',
//				IFNULL(cli.numero_documento,'') AS 'numero_documento',IFNULL(cli.tipo_documento,'') as 'tipo_documento',
//				IFNULL((SELECT DATE(fecha_cp) FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'fecha_cp',
//				IFNULL((SELECT TRUNCATE(monto_cp,2) FROM ca_compromiso_pago WHERE idtransaccion=trans.idtransaccion LIMIT 1 ),'') AS 'monto_cp',
//				IFNULL((SELECT nombre FROM ca_tipo_gestion WHERE idtipo_gestion=trans.idtipo_gestion LIMIT 1),'') AS 'tipo_gestion',
//				IFNULL((SELECT nombre FROM ca_final WHERE idfinal=trans.idfinal LIMIT 1 ),'') AS 'final'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.codigo=clicar.codigo_cliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=2 ) AND clicar.idcartera=? AND clicar.estado=1 
//				AND clicar.idusuario_servicio=?	AND cli.estado=1 AND cli.idservicio = ? 
//				AND DATE(trans.fecha) BETWEEN ? AND ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT gescu.idgestion_cuenta,gescu.numero_cuenta,DATE(gescu.fecha_creacion) AS 'fecha_registro',
				(SELECT nombre FROM ca_final WHERE idfinal = tran.idfinal ) AS 'estado',
				IFNULL(DATE(gescu.fecha_cp),'') AS 'fecha_cp',IFNULL(TRUNCATE(gescu.monto_cp,2),'') AS 'monto_cp',
				tran.observacion, DATE(vis.fecha_visita) AS 'fecha_visita', DATE(vis.fecha_recepcion) AS 'fecha_recepcion'
				FROM ca_transaccion tran INNER JOIN ca_visita vis INNER JOIN ca_gestion_cuenta gescu
				ON gescu.idvisita = vis.idvisita AND vis.idtransaccion = tran.idtransaccion 
				WHERE tran.idcliente_cartera = ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        /*         * ****** */
        //$servicio=$dtoServicio->getId();
        /*         * ****** */
        //$cartera=$dtoCartera->getId();
        //$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio();

        $cliente_cartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera,PDO::PARAM_INT);
        //$pr->bindParam(2,$UsuarioServicio,PDO::PARAM_INT);
        /*         * ******* */
        //$pr->bindParam(3,$servicio,PDO::PARAM_INT);
        /*         * ******* */
        //$pr->bindParam(4,$fecha_inicio,PDO::PARAM_STR);
        //$pr->bindParam(5,$fecha_fin,PDO::PARAM_STR);
        /*         * *** */
        $pr->bindParam(1, $cliente_cartera, PDO::PARAM_INT);
        /*         * *** */
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountDigitacionVisita ( $fecha_inicio, $fecha_fin, dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDCountDigitacionVisita(dto_cliente_cartera $dtoClienteCartera) {
//			$sql=" SELECT COUNT( DISTINCT trans.idtransaccion ) AS 'COUNT'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=2 ) AND clicar.idcartera=? AND clicar.estado=1 
//				AND clicar.idusuario_servicio=?	AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ";
//			$sql=" SELECT COUNT( DISTINCT trans.idtransaccion ) AS 'COUNT'
//				FROM ca_transaccion trans INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.codigo=clicar.codigo_cliente AND clicar.idcliente_cartera=trans.idcliente_cartera 
//				WHERE trans.idfinal IN ( SELECT idfinal FROM ca_final WHERE idclase_final=2 ) 
//				AND clicar.idcartera=? AND clicar.estado=1 AND cli.idservicio = ?
//				AND clicar.idusuario_servicio=?	AND cli.estado=1 AND DATE(trans.fecha) BETWEEN ? AND ? ";

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_transaccion tran INNER JOIN ca_visita vis INNER JOIN ca_gestion_cuenta gescu
				ON gescu.idvisita = vis.idvisita AND vis.idtransaccion = tran.idtransaccion 
				WHERE tran.idcliente_cartera = ? ";

        /*         * ******** */
        //$servicio=$dtoServicio->getId();
        /*         * ******** */
        //$cartera=$dtoCartera->getId();
        //$UsuarioServicio=$dtoClienteCartera->getIdUsuarioServicio();

        $cliente_cartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera,PDO::PARAM_INT);
        /*         * ******** */
        //$pr->bindParam(2,$servicio,PDO::PARAM_INT);
        /*         * ******** */
        //$pr->bindParam(3,$UsuarioServicio,PDO::PARAM_INT);
        //$pr->bindParam(4,$fecha_inicio,PDO::PARAM_STR);
        //$pr->bindParam(5,$fecha_fin,PDO::PARAM_STR);
        /*         * ***** */
        $pr->bindParam(1, $cliente_cartera, PDO::PARAM_INT);
        /*         * ***** */
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******* Atencion Cliente Historico *********** */

    public function JQGRIDRowsAtencionClienteHistorico($sidx, $sord, $start, $limit, dto_cliente $dtoCliente) {

        $sql = " SELECT gescu.idgestion_cuenta,
				DATE(tran.fecha_creacion) AS 'fecha_creacion',
				( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=gescu.idcuenta  ) AS 'numero_cuenta',
				( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono  ) AS 'telefono',
				( SELECT CONCAT_WS(' - ',niv.nombre,fin.nombre) FROM ca_final fin INNER JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal=tran.idfinal ) AS 'estado',
				DATE(lla.fecha) AS 'fecha_llamada',
				TIME(lla.fecha) AS 'hora_llamada',
				IFNULL(DATE(gescu.fecha_cp),'') AS 'fecha_cp',
				IFNULL(gescu.monto_cp,'') AS 'monto_cp',
				IFNULL(tran.observacion,'') AS 'observacion',
				( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = tran.idusuario_servicio ) AS 'teleoperador'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu
				ON gescu.idllamada=lla.idllamada AND lla.idtransaccion=tran.idtransaccion AND tran.idcliente_cartera=clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
				WHERE cli.idcliente = ?  
				ORDER BY $sidx $sord LIMIT $start , $limit  ";


        $idcliente = $dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ******* */
        //$pr->bindParam(1,$codigo_cliente); 
        /*         * ******* */
        $pr->bindParam(1, $idcliente);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteHistorico(dto_cliente $dtoCliente) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_gestion_cuenta gescu
				ON gescu.idllamada=lla.idllamada AND lla.idtransaccion=tran.idtransaccion AND tran.idcliente_cartera=clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
				WHERE cli.idcliente = ?   ";

        $idcliente = $dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        //$pr->bindParam(1,$codigo_cliente);
        /*         * *** */
        $pr->bindParam(1, $idcliente);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ************* Speech Listar ************ */

    public function JQGRIDRowsSpeechListar($sidx, $sord, $start, $limit, dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " SELECT ag.idayuda_gestion,ag.fecha_creacion,ag.ruta,tag.nombre AS 'tipo_ayuda_gestion'
				FROM ca_ayuda_gestion ag INNER JOIN ca_tipo_ayuda_gestion tag 
				ON tag.idtipo_ayuda_gestion=ag.idtipo_ayuda_gestion  
				WHERE ag.idservicio=? ORDER BY $sidx $sord LIMIT $start , $limit ";

        $servicio = $dtoAyudaGestion->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountSpeechListar(dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM ca_ayuda_gestion ag INNER JOIN ca_tipo_ayuda_gestion tag 
				ON tag.idtipo_ayuda_gestion=ag.idtipo_ayuda_gestion  
				WHERE ag.idservicio=? ";

        $servicio = $dtoAyudaGestion->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Distribucion ******** */

    /*     * *** Clientes Gestionados **** */

    //public function JQGRIDRowsClientesGestionados ( $sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDRowsClientesGestionados($sidx, $sord, $start, $limit, $search, $param, $querySearch) {
//			$sql=" SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',IFNULL(cli.numero_documento,'') AS 'numero_documento',
//				(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
//				ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio LIMIT 1)   as 'usuario_gestion'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
//				ON clicar.idcliente=cli.idcliente
//				WHERE clicar.idcartera = ? AND ( clicar.id_ultima_llamada!=0 OR clicar.id_ultima_visita!=0 ) 
//				AND clicar.estado=1 ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',IFNULL(cli.numero_documento,'') AS 'numero_documento',
				(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
				ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio LIMIT 1)   as 'usuario_gestion'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente=cli.idcliente
				WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.id_ultima_llamada!=0  
				AND clicar.estado=1 $search $querySearch 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cartera=$dtoCartera->getId();
        //$servicio=$dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera);
        //$pr->bindParam(2,$campania);
        //$pr->bindParam(2,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountClientesGestionados ( dto_cartera $dtoCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDCountClientesGestionados($search, $param, $querySearch) {
        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente = cli.idcliente
				WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.id_ultima_llamada!=0  $search $querySearch 
				AND clicar.estado=1 ";

        //$cartera=$dtoCartera->getId();
        //$servicio=$dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera);
        //$pr->bindParam(2,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * **** Clientes Sin Gestionar ***** */

    //public function JQGRIDRowsClientesSinGestionados ( $sidx, $sord, $start, $limit, dto_cartera $dtoCartera , dto_servicio $dtoServicio ) {
    public function JQGRIDRowsClientesSinGestionados($sidx, $sord, $start, $limit, $search, $param, $querySearch) {
//			$sql=" SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',IFNULL(cli.numero_documento,'') AS 'numero_documento',
//				(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
//				ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio LIMIT 1)   as 'usuario_gestion'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
//				ON clicar.idcliente=cli.idcliente
//				WHERE clicar.idcartera = ? AND clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0  
//				AND clicar.estado=1 ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT clicar.idcliente_cartera,cli.codigo,CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'cliente',IFNULL(cli.numero_documento,'') AS 'numero_documento',
				(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
				ON ususer.idusuario=usu.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio LIMIT 1)   as 'usuario_gestion'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente=cli.idcliente 
				WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.id_ultima_llamada=0 
				AND clicar.estado=1 $search $querySearch
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cartera=$dtoCartera->getId();
        //$servicio=$dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera);
        //$pr->bindParam(2,$campania);
        //$pr->bindParam(2,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountClientesSinGestionados ( dto_cartera $dtoCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDCountClientesSinGestionados($search, $param, $querySearch) {
        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente=cli.idcliente 
				WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.id_ultima_llamada=0 
				AND clicar.estado=1 $search $querySearch ";

        //$cartera=$dtoCartera->getId();
        //$servicio=$dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera);
        //$pr->bindParam(2,$servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******** Centro de Pago ********** */

    public function JQGRIDCountCentroPago($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_centro_pago
			WHERE estado=1 AND idfile_centro_pago = ( SELECT MAX(idfile_centro_pago) FROM ca_file_centro_pago WHERE estado=1 AND idservicio = :servicio ) " . $where;

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsCentroPago($sidx, $sord, $start, $limit, $param, $where) {

        $sql = " SELECT idcentro_pago,IFNULL(agencia,'') AS 'agencia',IFNULL(tipo_canal,'') AS 'tipo_canal',IFNULL(direccion,'') AS 'direccion',
			IFNULL(zona,'') AS 'zona',IFNULL(horario,'') AS 'horario',IFNULL(departamento,'') AS 'departamento',IFNULL(provincia,'') AS 'provincia',
			IFNULL(distrito,'') AS 'distrito' FROM ca_centro_pago
			WHERE estado=1 AND idfile_centro_pago = ( SELECT MAX(idfile_centro_pago) FROM ca_file_centro_pago WHERE estado=1 AND idservicio = :servicio ) " . $where . " 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ** Estado de Transaccion ** */

    public function JQGRIDRowsEstadoTransaccion($sidx, $sord, $start, $limit, dto_servicio $dtoServicio) {

        $sql = " SELECT idestado_transaccion,nombre,peso,
			( SELECT nombre FROM ca_tipo_transaccion WHERE idtipo_transaccion=idtipo_transaccion LIMIT 1 ) AS 'tipo',
			IFNULL(descripcion,'') AS 'descripcion', DATE(fecha_creacion) AS 'fecha_registro'
			FROM ca_estado_transaccion WHERE idservicio = ? AND estado = 1 ORDER BY $sidx $sord LIMIT $start , $limit ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountEstadoTransaccion(dto_servicio $dtoServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
			FROM ca_estado_transaccion WHERE idservicio = ? AND estado = 1 ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsPrioridadTransaccion($sidx, $sord, $start, $limit, dto_estado_transaccion $dtoEstadoTransaccion) {
        $sql = " SELECT idpeso_transaccion , peso , DATE(fecha_creacion) AS 'fecha_registro' 
				FROM ca_peso_transaccion WHERE idestado_transaccion = ? AND estado = 1 ORDER BY $sidx $sord LIMIT $start , $limit ";

        $EstadoTransaccion = $dtoEstadoTransaccion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $EstadoTransaccion);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountPrioridadTransaccion(dto_estado_transaccion $dtoEstadoTransaccion) {
        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_peso_transaccion WHERE idestado_transaccion = ? AND estado = 1 ";

        $EstadoTransaccion = $dtoEstadoTransaccion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $EstadoTransaccion);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * *** Prioridad Transaccion  ** */

    /*     * *  Tareas ***** */

    public function JQGRIDRowsTarea($sidx, $sord, $start, $limit, $anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT idtarea,titulo,hora,nota FROM ca_tarea 
			WHERE idusuario_servicio = ? AND estado = 1 AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountTarea($anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_tarea 
			WHERE idusuario_servicio = ? AND estado = 1 AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ***** Evento ****** */

    public function JQGRIDRowsEvento($sidx, $sord, $start, $limit, $anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT idevento,evento,hora  
			FROM ca_evento where idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountEvento($anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
			FROM ca_evento WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia  ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Todos los usuarios ******* */

    public function JQGRIDRowsUsuarioAll($sidx, $sord, $start, $limit, $search, $param, $querySearch) {
        $sql = " SELECT idusuario,CONCAT_WS(' ',nombre,paterno,materno) AS 'nombre',
			IFNULL(email,'') AS 'email',dni,DATE(fecha_creacion) AS 'fecha_registro'
			FROM ca_usuario WHERE estado=1 $search $querySearch
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountUsuarioAll($search, $param, $querySearch) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario WHERE estado=1 $search $querySearch	";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** Servicios de usuario  ********** */

    public function JQGRIDRowsServicesOfUser($sidx, $sord, $start, $limit, dto_usuario $dtoUsuario) {
        $sql = " SELECT idusuario_servicio ,
			( SELECT nombre FROM ca_servicio WHERE idservicio=ususer.idservicio LIMIT 1 ) AS 'servicio',
			( SELECT nombre FROM ca_tipo_usuario WHERE idtipo_usuario=ususer.idtipo_usuario LIMIT 1 ) AS 'tipo_usuario',
			( SELECT nombre FROM ca_privilegio WHERE idprivilegio=ususer.idprivilegio LIMIT 1 ) AS 'privilegio',
			fecha_inicio,fecha_fin,DATE(fecha_creacion) AS 'fecha_registro'
			FROM ca_usuario_servicio ususer WHERE estado=1 AND idusuario = ? ";

        $usuario = $dtoUsuario->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDRowsClusterOfServicio($sidx, $sord, $start, $limit, $serv) {
        $sql = "select idcluster,nombre, descripcion, if(estado=1,'ACTIVO','INACTIVO') AS 'estado' from ca_cluster_usuario where idservicio=" . $serv . " ORDER BY $sidx $sord LIMIT $start , $limit";

        //echo($sql);
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountServicesOfUser(dto_usuario $dtoUsuario) {
        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_usuario_servicio ususer WHERE estado=1 AND idusuario = ? ";

        $usuario = $dtoUsuario->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDCountClusterOfServicio($serv) {
        $sql = " select count(*) as 'COUNT' from ca_cluster_usuario where idservicio=" . $serv . " ";
        //echo($sql);

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ******* Usuarios por servicio ********** */

    public function JQGRIDRowsUserByService($sidx, $sord, $start, $limit, dto_servicio $dtoServicio) {
        $sql = " SELECT DISTINCT usu.idusuario,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'nombre',
			IFNULL(usu.email,'') AS 'email',usu.dni,DATE(usu.fecha_creacion) AS 'fecha_registro' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
			WHERE usu.estado=1 AND ususer.idservicio = ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountUserByService(dto_servicio $dtoServicio) {
        $sql = " SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT' 
			FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario 
			WHERE usu.estado=1 AND ususer.idservicio = ? ORDER BY $sidx $sord LIMIT $start , $limit ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * **** Distribucion Por Operador ******** */

    public function JQGRIDRowsDistribucionPorOperador($sidx, $sord, $start, $limit, $param, $where) {

//			$sql=" SELECT clicar.idcliente_cartera,cli.codigo,TRIM(concat_ws(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//			IFNULL(cli.numero_documento,'') as 'numero_documento',IFNULL(cli.tipo_documento,'') as 'tipo_documento'
//			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente=cli.idcliente
//			WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio=0 AND clicar.estado=1 ".$where." 
//			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $sql = " SELECT clicar.idcliente_cartera,cli.codigo,TRIM(concat_ws(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
			IFNULL(cli.numero_documento,'') as 'numero_documento',IFNULL(cli.tipo_documento,'') as 'tipo_documento'
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo
			WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio=0 AND cli.idservicio = :servicio AND clicar.estado=1 " . $where . " 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountDistribucionPorOperador($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
			WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio=0 AND cli.idservicio = :servicio AND clicar.estado=1 " . $where . " ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * *********** Clientes De Cartera ************** */

    public function JQGRIDRowsClientesCartera($sidx, $sord, $start, $limit, $param, $where) {

        $sql = " SELECT clicar.idcliente_cartera, clicar.codigo_cliente,  
				CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'nombre',
				cli.numero_documento,
				cli.tipo_documento
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit  ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountClientesCartera($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera " . $where . " ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ************ Clientes Globales ****************** */

    public function JQGRIDRowsClientesBusquedaGlobal($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT clicar.idcliente_cartera, cli.codigo,CONCAT_WS(' ',TRIM(cli.paterno),TRIM(cli.materno),TRIM(cli.nombre)) AS 'cliente',
				IFNULL( cli.numero_documento,'') AS 'numero_documento',IFNULL(  cli.tipo_documento,'' ) AS 'tipo_documento', 
				ser.nombre AS 'servicio',car.nombre_cartera AS 'cartera', DATE(clicar.fecha_modificacion) AS 'ultima_llamada'
				FROM ca_cartera car 
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
				INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente 
				INNER JOIN ca_servicio ser ON ser.idservicio = cli.idservicio $where 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountClientesBusquedaGlobal($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cartera car 
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
				INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente 
				INNER JOIN ca_servicio ser ON ser.idservicio = cli.idservicio $where ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * *********** Clientes especiales asignados a teleoperador ******************* */

    public function JQGRIDRowsClientesEspecialesAsignadosTeleoperador($sidx, $sord, $start, $limit, $param, $where) {

        $sql = " SELECT clicar.idcliente_cartera, clicar.codigo_cliente,  
				CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'nombre',
				cli.numero_documento,
				cli.tipo_documento
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio_especial = :usuario_servicio " . $where . " 
				ORDER BY $sidx $sord LIMIT $start , $limit  ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountClientesEspecialesAsignadosTeleoperador($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio_especial = :usuario_servicio " . $where . " ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ***********  Clientes de Servicio ******************** */

    public function JQGRIDRowsAtencionClienteByService($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT clicar.idcliente_cartera,
				cli.codigo, TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre', 
				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND cli.estado=1 $where 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountAtencionClienteByService($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado = 1 AND clicar.idcartera = :cartera AND cli.idservicio = :servicio $where ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    //**************** FACTURAS DIGITALES *********************
    public function JQGRIDRowsFacturasDigitales($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT idfactura_digital,correo,ruta_absoluta,solicita,is_send as enviado, paterno, materno, nombre, fecha_vencimiento FROM ca_factura_digital INNER JOIN ca_usuario_servicio ON ca_usuario_servicio.idusuario_servicio = ca_factura_digital.usuario_creacion INNER JOIN ca_usuario ON ca_usuario_servicio.idusuario = ca_usuario.idusuario  AND  ca_factura_digital.idusuario_servicio = ? AND ca_factura_digital.is_send = 0 $where 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountFacturasDigitales($where, $param) {

        $sql = " SELECT COUNT(*) as 'COUNT' FROM ca_factura_digital INNER JOIN ca_usuario_servicio ON ca_usuario_servicio.idusuario_servicio = ca_factura_digital.usuario_creacion INNER JOIN ca_usuario ON ca_usuario_servicio.idusuario = ca_usuario.idusuario  AND  ca_factura_digital.idusuario_servicio = ? AND ca_factura_digital.is_send = 0 $where ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********** CARTERAS A GESTIONAR POR OPERADOR ************** */

    public function JQGRIDRowsCarterasXoperador($sidx, $sord, $start, $limit, $where, $param) {

        $sql = "SELECT distinct car.idcartera,
				if( date(car.fecha_fin)<date(now()), concat('<font color=red>',car.nombre_cartera,'</font>') , car.nombre_cartera) as 'nombre_cartera', 
				IFNULL(car.fecha_inicio,'') AS 'fecha_inicio', 
				IFNULL(car.fecha_fin,'') AS 'fecha_fin',
				if( date(car.fecha_fin)<date(now()), 1 , 0) as 'vencido'
			FROM ca_cartera car
			left join ca_cliente_cartera clicar on clicar.idcartera=car.idcartera
			WHERE car.estado=1 AND car.idcampania=?  and clicar.idusuario_servicio=? $where ORDER BY $sidx $sord LIMIT $start, $limit";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountCarterasXoperador($where, $param) {

        $sql = "SELECT COUNT(*) AS 'COUNT' FROM ca_cartera car
			left join ca_cliente_cartera clicar on clicar.idcartera=car.idcartera
			WHERE car.estado=1 AND car.idcampania=?  and clicar.idusuario_servicio=? $where";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********************  Control Gestion ( Gestiones por Servicio ) ********************** */

    public function JQGRIDRowsGestionesPorServicio($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT car.idcartera, cam.nombre AS 'campania', car.nombre_cartera AS 'nombre_cartera',
				DATE(car.fecha_carga) AS 'fecha_carga',
				car.fecha_inicio  AS 'fecha_inicio', car.fecha_fin AS 'fecha_fin', car.status , car.cantidad AS 'registros',
				IFNULL(car.meta_cliente,0) AS 'meta_cliente', IFNULL(car.meta_cuenta,0) AS 'meta_cuenta',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera = car.idcartera ) AS 'clientes',
				( SELECT COUNT(*) FROM ca_cuenta WHERE idcartera = car.idcartera ) AS 'cuenta',
				( SELECT COUNT(*) FROM ca_detalle_cuenta WHERE idcartera = car.idcartera ) AS 'detalle'
				FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania 
				WHERE cam.estado = 1 AND car.estado = 1  $where ORDER BY $sidx $sord LIMIT $start, $limit";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

    public function JQGRIDCountGestionesPorServicio($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania
			WHERE cam.estado = 1 AND car.estado = 1 $where ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

}

?>
