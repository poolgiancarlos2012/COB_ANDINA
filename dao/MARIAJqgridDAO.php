<?php

class MARIAjqgridDAO {
    /*     * * Ventana de Atencion y Digitacion Busqueda Base ************* */

    public function JQGRIDRowsSearchBaseByNumberAccount($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cuenta $dtoCuenta) {

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $cuenta = $dtoCuenta->getNumeroCuenta();

        $sql = "    SELECT DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,
                    car.nombre_cartera,
                    cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
                    IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
                    FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cuenta cu 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
                    ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente
                    WHERE 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    cu.numero_cuenta = '$cuenta' 
                    ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('mysql');
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
			WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cu.numero_cuenta = '$cuenta' ";

        $factoryConnection = FactoryConnection::create('mysql');
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
    public function JQGRIDRowsSearchBaseByIdClienteCartera($sidx, $sord, $start, $limit, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cuenta $dtoCuenta) {

        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $idcliente_cartera = $dtoCuenta->getIdClienteCartera();


        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,
                    car.nombre_cartera,
                    cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
                    IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
                    FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cuenta cu 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
                    ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente
                    WHERE 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    cu.idcliente_cartera = '$idcliente_cartera' 
                    ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,car.nombre_cartera,
    				cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
    				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
    				FROM 
                    ca_telefono tel 
                    INNER JOIN ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
    				ON 
                    cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente AND clicar.idcliente_cartera = tel.idcliente_cartera 
    				WHERE 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    (tel.numero_act = '$numero' OR tel.numero = '$numero') 
    				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('mysql');
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
				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente AND clicar.idcliente_cartera = tel.idcliente_cartera
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND tel.numero = '$numero' ";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,car.nombre_cartera,
    				cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.razon_social)) AS 'cliente',
    				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
    				FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
    				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
    				WHERE 
                    car.status = 'ACTIVO' AND 
                    cam.status = 'ACTIVO' AND 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado =1 AND 
                    cam.idservicio = ? AND 
                    TRIM(CONCAT_WS(' ',cli.razon_social)) LIKE '%$nombre%' 
    				ORDER BY $sidx $sord LIMIT $start , $limit ";

        // echo $sql;

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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


        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,
                    car.nombre_cartera,
    				cli.codigo,
                    cli.contrato,
                    -- TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
                    IF(TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) IS NULL OR TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))='',cli.razon_social,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))) AS 'cliente',
    				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
    				FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
    				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.codigo_cliente=cli.codigo
    				WHERE 
                    car.status='ACTIVO' AND 
                    cam.status='ACTIVO' AND 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    cli.codigo = '$codigo' 
    				ORDER BY $sidx $sord LIMIT $start , $limit ";

        // echo $sql;
        // exit();


        $factoryConnection = FactoryConnection::create('mysql');
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
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.codigo = '$codigo' ";

        $factoryConnection = FactoryConnection::create('mysql');
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


        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,car.nombre_cartera,
    				cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.razon_social)) AS 'cliente',
    				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
    				FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
    				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
    				WHERE 
                    car.status='ACTIVO' AND 
                    cam.status='ACTIVO' AND 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    CONVERT(cli.numero_documento,SIGNED) = CONVERT('$NumeroDocumento',SIGNED) 
    				ORDER BY $sidx $sord LIMIT $start , $limit ";

        $factoryConnection = FactoryConnection::create('mysql');
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
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.numero_documento = '$NumeroDocumento' ";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = "    SELECT 
                    DISTINCT clicar.idcliente_cartera,
                    cli.idcliente,car.nombre_cartera,
    				cli.codigo, 
                    '' AS 'contrato',
                    TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
    				IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
    				FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
    				ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
    				WHERE 
                    car.status='ACTIVO' AND 
                    cam.status='ACTIVO' AND 
                    car.estado = 1 AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    cam.idservicio = ? AND 
                    cli.tipo_documento = '$TipoDocumento' 
    				ORDER BY $sidx $sord LIMIT $start , $limit ";


        $factoryConnection = FactoryConnection::create('mysql');
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
				WHERE car.estado = 1 AND cli.idservicio = ? AND cli.estado=1 AND cam.idservicio = ? AND cli.tipo_documento = '$TipoDocumento' ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountServicioUsuarioAdmin() {
        $sql = " SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (1,4) AND ususer.estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountServicioUsuarioOpera() {
        $sql = " SELECT COUNT( DISTINCT usu.idusuario ) AS 'COUNT'
				FROM ca_servicio ser INNER JOIN ca_usuario_servicio ususer INNER JOIN ca_usuario usu 
				ON usu.idusuario=ususer.idusuario AND ususer.idservicio=ser.idservicio
				WHERE ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
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
        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountUsuarioAdminOperadoresActivosPorServicio() {
        $sql = " SELECT COUNT( usu.idusuario ) AS 'COUNT'
				FROM ca_usuario usu
				WHERE usu.estado = 1 ";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = " SELECT t1.idtelefono, t1.status, t1.numero, t1.codigo_cliente, t1.idcuenta, t1.idcliente_cartera, 
                                t1.anexo, t1.tipo_telefono, t1.origen, t1.tipo_referencia, t1.observacion
				FROM
				(
				SELECT tel.idtelefono, IFNULL(tel.numero,'') AS 'numero', tel.codigo_cliente, tel.idcuenta, 
                                tel.idcliente_cartera, IFNULL(tel.status,'') AS 'status', 
                                IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia',IFNULL(tel.observacion,'') AS 'observacion'
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.estado = 1  AND tel.idcartera = ? 
				) t1 ORDER BY $sidx $sord LIMIT $start , $limit ";
                                
        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $codigo_cliente);

        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        //$pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteTelefono(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

			$sql=" SELECT COUNT(*) AS 'COUNT' 
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente=? AND tel.idcartera=? AND tel.estado = 1 ";

        /*$sql = " SELECT COUNT(*) AS 'COUNT'
				FROM
				(
				SELECT tel.idtelefono 
				FROM ca_telefono tel INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref INNER JOIN ca_tipo_telefono tiptel
				ON tiptel.idtipo_telefono=tel.idtipo_telefono AND tipref.idtipo_referencia=tel.idtipo_referencia AND org.idorigen=tel.idorigen
				WHERE tel.codigo_cliente = ? AND tel.estado = 1 
                                GROUP BY tel.numero 
				) t1 ";*/

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $codigo_cliente);
        /*         * *** */
        //$pr->bindParam(1,$cliente);
        $pr->bindParam(2, $cartera);
        //$pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }
    public function queryListarNumeroTelefonico($cartera,$codigo_cliente){
        $sql="SELECT  t1.numero 
                FROM
                (
                SELECT idtelefono, IFNULL(numero_act,numero) as numero 
                FROM ca_telefono tel 
                WHERE codigo_cliente = '$codigo_cliente' AND estado = 1  AND is_active = 1 AND IF(idtipo_referencia=3,IF(idorigen=1,0,1),1)=1 AND CAST(numero AS SIGNED)!=0 
                ORDER BY idtelefono DESC
                ) t1 
                WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^........$' OR t1.numero REGEXP '^.......$' )) 
                GROUP BY t1.numero";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }        

    }
    /*     * ********** Atencion Cliente Solo Numero Telefonos *********** */

    public function JQGRIDRowsAtencionClienteNumeroTelefono($sidx, $sord, $start, $limit, dto_cliente $dtoCliente, dto_cartera $dtoCartera) {

        /*$sql = " SELECT t1.idtelefono, t1.numero, t1.anexo, t1.is_new, t1.referencia 
				FROM
				(
				SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', is_new, IFNULL(referencia,'') AS referencia 
				FROM ca_telefono 
				WHERE codigo_cliente = ? AND estado = 1  AND is_active = 1 
                GROUP BY numero
				) t1 GROUP BY t1.numero ORDER BY $sidx $sord LIMIT $start , $limit ";*/
        
        $sql = " SELECT t1.idtelefono, IF(LENGTH(t1.numero)=8 AND SUBSTRING(t1.numero,1,1)!=9, CONCAT('0',t1.numero), t1.numero) AS numero, t1.anexo, t1.is_new, t1.is_campo,t1.is_carga, t1.referencia , t1.estado , t1.prefijos, t1.peso, t1.origen,t1.is_active
                FROM
                (
                SELECT idtelefono, IFNULL(numero_act,numero) as numero, IFNULL(anexo,'') AS 'anexo', IFNULL(m_peso,0) AS peso ,
                is_new, is_campo,is_carga,is_active ,IFNULL(referencia,'') AS referencia ,
                IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') AS estado ,
                IFNULL(( SELECT CONCAT_WS(':',nombre,CONCAT_WS('-',lb_prefijo,lb_prefijo2,lb_prefijo3)) FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),':') AS prefijos,
                org.nombre AS 'origen' 
 
                FROM ca_telefono tel 
                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                WHERE codigo_cliente = ? AND estado = 1  -- AND is_active = 1 
                -- AND IF(idtipo_referencia=3,IF(tel.idorigen=1,0,1),1)=1 AND CAST(numero AS SIGNED)!=0 
                ORDER BY peso DESC
                ) t1 
				-- WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^[2-8].......$' OR t1.numero REGEXP '^[2-8]......$' )) 
				GROUP BY t1.numero ORDER BY peso DESC LIMIT $start , $limit ";

        // echo $sql;
        // exit();

        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1, $cartera);
        $pr->bindParam(1, $codigo_cliente);
        //$pr->bindParam(3, $codigo_cliente);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteNumeroTelefono(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {


       /* $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM
				(
				SELECT idtelefono, numero
				FROM ca_telefono WHERE idcartera = ?  AND codigo_cliente = ? AND estado = 1 AND is_new = 0
				UNION
				SELECT idtelefono, numero
				FROM ca_telefono WHERE codigo_cliente = ? AND estado = 1 AND is_new = 1
				) t1 GROUP BY t1.numero ";*/
		
		$sql = " SELECT COUNT(*) AS 'COUNT' FROM 
			(
			SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', is_new
			FROM ca_telefono 
			WHERE codigo_cliente = ? AND estado = 1  AND is_active = 1 
			GROUP BY numero
			) AS t1
			";

        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1, $cartera);
        $pr->bindParam(1, $codigo_cliente);
        //$pr->bindParam(3, $codigo_cliente);
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

        $sql = " SELECT dir.iddireccion , IFNULL(dir.status,'') AS 'status', dir.idcuenta,
                                IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo', 
				IFNULL(dir.departamento,'') AS 'departamento', IFNULL(dir.provincia,'') AS 'provincia', IFNULL(dir.distrito,'') AS 'distrito', 
				org.nombre AS 'origen', tipref.nombre AS 'tipo_referencia', IFNULL(dir.observacion,'') AS 'observacion', IFNULL(dir.codigo_postal,'')  AS 'codigo_postal'
				FROM  ca_direccion dir INNER JOIN ca_origen org INNER JOIN ca_tipo_referencia tipref 
				ON tipref.idtipo_referencia=dir.idtipo_referencia AND org.idorigen=dir.idorigen 
				WHERE dir.codigo_cliente = ? AND dir.idcartera = ? 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        //$cliente=$dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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
        
        $sql = " SELECT gescu.idgestion_cuenta,
				DATE(tran.fecha_creacion) AS 'fecha_creacion',
				( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=gescu.idcuenta  ) AS 'numero_cuenta',
				( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono  ) AS 'telefono',
				( SELECT nombre FROM ca_final WHERE idfinal=tran.idfinal ) AS 'estado',
                                IFNULL(lla.status_cuenta,'') AS eecc,
                                ( SELECT status FROM ca_ll_det_direccion_est WHERE idllamada = lla.idllamada ) AS status_dir,
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = " SELECT tel.idtelefono, IFNULL(tel.status,'') AS 'status', IFNULL(tel.numero,'') AS 'numero', IFNULL(tel.anexo,'') AS 'anexo', tiptel.nombre AS 'tipo_telefono',
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = " SELECT dir.iddireccion , IFNULL(dir.status,'') AS 'status', dir.idcuenta, 
                                IFNULL(dir.direccion,'') AS 'direccion', IFNULL(dir.referencia,'') AS 'referencia',IFNULL(dir.ubigeo,'') AS 'ubigeo',
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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
        
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $idfinal = $dtoTransaccion->getIdFinal();

        $sql = "    SELECT 
                    clicar.idcliente_cartera,
                    cli.idcliente,
                    cli.idservicio,
                    clicar.estado,
                    clicar.retiro,
                    clicar.motivo_retiro,
				    cli.codigo, 
                    TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'nombre',
				    IFNULL( cli.numero_documento,'' ) AS 'numero_documento', IFNULL(cli.tipo_documento,'') AS 'tipo_documento', 
				    COUNT(*) AS 'llamadas' 
				    FROM ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_llamada lla
				    ON lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente 
				    WHERE 
                    clicar.idcartera IN ( $cartera ) AND 
                    cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    -- clicar.idusuario_servicio = ?  AND 
                    lla.idfinal = ? 
				    GROUP BY clicar.idcliente_cartera  
				    ORDER BY $sidx $sord LIMIT $start , $limit ";

        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(3, $idfinal, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function JQGRIDCountAtencionClienteBusquedaEstado(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_transaccion $dtoTransaccion) {
    
        $cartera = $dtoCartera->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $idfinal = $dtoTransaccion->getIdFinal();

        $sql = "    SELECT 
                    COUNT( DISTINCT clicar.idcliente_cartera) AS 'COUNT' 
    				FROM 
                    ca_cliente cli 
                    INNER JOIN ca_cliente_cartera clicar 
                    INNER JOIN ca_llamada lla 
    				ON lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente 
    				WHERE 
                    clicar.idcartera IN ($cartera) 
                    AND cli.idservicio = ? AND 
                    cli.estado=1 AND 
                    -- clicar.idusuario_servicio = ? AND 
                    lla.idfinal = ? 
                ";

        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(3, $idfinal, PDO::PARAM_INT);
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
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
				ON lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente 
				WHERE clicar.idcartera IN(" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio AND clicar.estado = 1 
				AND cli.estado=1 AND clicar.idusuario_servicio = :operador  AND clicar.id_ultima_llamada != 0 $where 
				GROUP BY clicar.idcliente_cartera 
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('mysql');
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
				ON clicar.idcliente=cli.idcliente 
				WHERE clicar.idcartera IN (" . $_GET['Cartera'] . ") AND cli.idservicio = :servicio AND cli.estado=1 
				AND clicar.idusuario_servicio = :operador AND clicar.id_ultima_llamada != 0  $where ";

        /* $cartera=$dtoCartera->getId();
          $servicio=$dtoUsuarioServicio->getIdServicio();
          $UsuarioServicio=$dtoUsuarioServicio->getId(); */

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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
    public function JQGRIDRowsDigitacionVisita($sidx, $sord, $start, $limit, $ini,$fin,$estado,dto_cliente_cartera $dtoClienteCartera) {
        
        
        $where="";
        if($ini!='' && $fin!=''){
            $where="  fecha_visita BETWEEN '$ini' AND '$fin' AND ";
        }
        // if($estado!=''){
        //     $where=" AND vis.idfinal=$estado ";
        // }

        // $sql = " SELECT vis.idcuenta,vis.idvisita,
        //         ( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta = vis.idcuenta ) AS 'numero_cuenta',
        //         DATE(vis.fecha_creacion) AS 'fecha_registro',
        //         (SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'estado',
        //         (SELECT DISTINCT IFNULL(cli.tipo_persona,IFNULL(rep.tipo_persona,'')) FROM ca_direccion dir LEFT JOIN ca_cliente cli ON dir.codigo_cliente=cli.codigo LEFT JOIN ca_representante_legal rep ON rep.codigo_cliente=dir.codigo_cliente WHERE dir.iddireccion=vis.iddireccion) AS 'tipo_persona',
        //         (SELECT DISTINCT IFNULL(TRIM(CONCAT(cli.nombre,' ',cli.paterno,' ',cli.materno,' ',cli.razon_social)),IFNULL(TRIM(CONCAT(rep.nombre,' ',rep.paterno,' ',rep.materno,' ',representante_legal)),'')) FROM ca_direccion dir LEFT JOIN ca_cliente cli ON dir.codigo_cliente=cli.codigo LEFT JOIN ca_representante_legal rep ON rep.codigo_cliente=dir.codigo_cliente WHERE dir.iddireccion=vis.iddireccion) AS 'persona_direccion',
        //         IFNULL((SELECT CONCAT_WS('|',IFNULL(direccion,''),IFNULL(distrito,''),IFNULL(provincia,'')) from ca_direccion where iddireccion=vis.iddireccion),'') AS direccion,
        //         ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador LIMIT 1 ) AS notificador,
        //         IFNULL(DATE(vis.fecha_cp),'') AS 'fecha_cp',
        //         IFNULL(TRUNCATE(vis.monto_cp,2),'') AS 'monto_cp',
        //         ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS 'contacto',
        //         IFNULL(vis.nombre_contacto,'') AS 'nombre_contacto',
        //         IFNULL(vis.hora_visita,'') AS 'hora_visita',
        //         IFNULL(vis.hora_salida,'') AS 'hora_salida',
        //         vis.observacion, 
        //         DATE(vis.fecha_visita) AS 'fecha_visita', 
        //         DATE(vis.fecha_recepcion) AS 'fecha_recepcion'
        //         FROM ca_visita vis 
        //         WHERE vis.idcliente_cartera = ? AND vis.estado = 1
        //         ORDER BY vis.fecha_cp DESC,vis.hora_visita DESC
        //         LIMIT $start , $limit ";

        $sql = "    SELECT
                    vis.idvisita AS 'IDVISITA',
                    (SELECT nombre_cartera FROM ca_cartera WHERE idcartera=clicar.idcartera) AS 'CARTERA',
                    (SELECT dato2 FROM ca_cuenta WHERE idcuenta=vis.idcuenta) AS 'TD',
                    (SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=vis.idcuenta) AS 'DOCUMENTO',
                    (SELECT CONCAT(IFNULL(departamento,''),' - ',IFNULL(provincia,''),' - ',IFNULL(provincia,''),' , ' ,direccion) FROM ca_direccion WHERE iddireccion=vis.iddireccion) AS 'DIRECCION',
                    DATE(vis.fecha_visita) AS 'FECHA_VISITA',
                    IFNULL(vis.hora_visita,'') AS 'HORA_LLEGADA',
                    IFNULL(vis.hora_salida,'') AS 'HORA_SALIDA',
                    IFNULL(vis.descripcion_inmueble,'') AS 'DESCRIP_INMUEBLE',
                    (SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO',
                    IFNULL(DATE(vis.fecha_cp),'') AS 'FECHA_CP',
                    IFNULL(vis.moneda_cp,'') AS 'MONEDA_CP',
                    IFNULL(TRUNCATE(vis.monto_cp,2),'') AS 'MONTO_CP',
                    ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS 'CONTACTO',
                    IFNULL(vis.nombre_contacto,'') AS 'NOMBRE_CONTACTO',
                    (SELECT nombre FROM ca_parentesco WHERE idparentesco=vis.idparentesco) AS 'PARENTESCO',
                    (SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago=vis.idmotivo_no_pago ) AS 'MOTIVO_NO_PAGO',
                    (SELECT nombre FROM ca_estado_cliente WHERE idestado_cliente=vis.idestado_cliente) AS 'ESTADO_CLIENTE',
                    vis.observacion AS 'OBS',
                    (SELECT CONCAT(usu.nombre,', ',usu.paterno,' ',usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio userv ON usu.idusuario=userv.idusuario WHERE userv.idservicio=1 AND userv.idusuario_servicio=vis.idusuario_servicio) AS 'USUARIO',
                    vis.idcuenta AS 'IDCUENTA'
                    FROM 
                    ca_visita vis
                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=vis.idcliente_cartera
                    WHERE 
		   1=1 AND
		   $where
                    vis.idcliente_cartera = ? AND 
                    vis.estado = 1
                    ORDER BY vis.fecha_cp DESC,vis.hora_visita DESC
                    LIMIT $start , $limit 
                ";

//	echo $sql;
//	exit();
		
        $cliente_cartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cliente_cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }
    public function JQGRIDRowsDigitacionVisita3($sidx, $sord, $start, $limit, dto_cliente $dtoCliente) {
        /*piro 13-08-2015*/
        $sql = " SELECT vis.idcuenta,vis.idvisita,
                ( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta = vis.idcuenta ) AS 'numero_cuenta',
                DATE(vis.fecha_creacion) AS 'fecha_registro',
                (SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'estado',
                IFNULL((SELECT CONCAT_WS('|',IFNULL(direccion,''),IFNULL(distrito,''),IFNULL(provincia,'')) from ca_direccion where iddireccion=vis.iddireccion),'') AS direccion,
                ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador LIMIT 1 ) AS notificador,
                IFNULL(DATE(vis.fecha_cp),'') AS 'fecha_cp',
                IFNULL(TRUNCATE(vis.monto_cp,2),'') AS 'monto_cp',
                ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS 'contacto',
                IFNULL(vis.nombre_contacto,'') AS 'nombre_contacto',
                IFNULL(vis.hora_visita,'') AS 'hora_visita',
                IFNULL(vis.hora_salida,'') AS 'hora_salida',
                vis.observacion, 
                DATE(vis.fecha_visita) AS 'fecha_visita', 
                DATE(vis.fecha_recepcion) AS 'fecha_recepcion'
                FROM ca_visita vis 
                INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                WHERE clicar.codigo_cliente = ?  AND vis.estado = 1 AND DATE(vis.fecha_visita)>DATE(DATE_SUB(now(),INTERVAL 2 MONTH))
				ORDER BY $sidx $sord LIMIT $start , $limit ";

        echo $sql;
        exit();  
        
        $codigo_cliente = $dtoCliente->getCodigo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $codigo_cliente, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    //public function JQGRIDCountDigitacionVisita ( $fecha_inicio, $fecha_fin, dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera, dto_servicio $dtoServicio ) {
    public function JQGRIDCountDigitacionVisita($ini,$fin,$estado,dto_cliente_cartera $dtoClienteCartera) {

        $where="";
        if($ini!='' && $fin!=''){
            $where=" fecha_visita BETWEEN '$ini' AND '$fin' AND ";
        }
        // if($estado!=''){
        //     $where=" AND idfinal=$estado ";
        // }

        $sql = " SELECT 
                    COUNT(*) AS 'COUNT' 
                    FROM 
                    ca_visita vis
                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = vis.idcliente_cartera
                    WHERE 
		   1=1 AND
		   $where
                    clicar.idcliente_cartera = ? AND 
                    vis.estado = 1 
                    
                    ";
        // echo $sql;
        // exit();  
		
        $cliente_cartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $cliente_cartera, PDO::PARAM_INT);
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


        // $sql = "    SELECT 
        //             lla.idllamada,
        //             ( SELECT nombre_cartera FROM ca_cartera WHERE idcartera = clicar.idcartera ) AS cartera,
        //             DATE(lla.fecha_creacion) AS 'fecha_creacion',
        //             ( SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=lla.idcuenta  ) AS 'numero_cuenta',
        //             ( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono  ) AS 'telefono',
        //             ( SELECT CONCAT_WS(' - ',niv.nombre,fin.nombre) FROM ca_final fin LEFT JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal=lla.idfinal ) AS 'estado',
        //             DATE(lla.fecha) AS 'fecha_llamada',
        //             TIME(lla.fecha) AS 'hora_llamada',
        //             IFNULL( lla.status_cuenta,'' ) AS eecc,
        //             IFNULL( lla.nombre_contacto,'' ) AS nombre_contacto,
        //             IFNULL( ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto  ),'') AS contacto,
        //             IFNULL( ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) ,'') AS parentesco,
        //             IFNULL( ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) ,'') AS motivo_no_pago,
        //             ( SELECT status FROM ca_ll_det_direccion_est WHERE idllamada = lla.idllamada LIMIT 1 ) AS status_dir,
        //             IFNULL(DATE(lla.fecha_cp),'') AS 'fecha_cp',
        //             TRUNCATE(IFNULL(lla.monto_cp,0),2) AS 'monto_cp',
        //             IFNULL(lla.observacion,'') AS 'observacion',
        //             ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio ) AS 'teleoperador'
        //             FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
        //             ON lla.idcliente_cartera = clicar.idcliente_cartera 
        //             WHERE 
        //             clicar.idcliente = ? AND 
        //             lla.estado = 1 AND 
        //             lla.tipo<>'S'
        //             ORDER BY $sidx $sord LIMIT $start , $limit  
        //         ";
        $sql = "    SELECT 
                    lla.idllamada AS 'IDLLAMADA',
                    ( SELECT nombre_cartera FROM ca_cartera WHERE idcartera = clicar.idcartera ) AS 'CARTERA',
                    (SELECT dato2 FROM ca_cuenta WHERE idcuenta=lla.idcuenta) AS 'TD',
                    (SELECT numero_cuenta FROM ca_cuenta WHERE idcuenta=lla.idcuenta) AS 'DOCUMENTO',
                    ( SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono  ) AS 'TELEFONO',
                    DATE(lla.fecha) AS 'FECHA_LLAMADA',
                    TIME(lla.fecha) AS 'HORA_LLAMADA',
                    ( SELECT CONCAT_WS(' - ',niv.nombre,fin.nombre) FROM ca_final fin LEFT JOIN ca_nivel niv ON niv.idnivel = fin.idnivel WHERE fin.idfinal=lla.idfinal ) AS 'ESTADO',
                    IFNULL(DATE(lla.fecha_cp),'') AS 'FECHA_CP',
                    IFNULL(lla.moneda_cp,'') AS 'MONEDA_CP',
                    TRUNCATE(IFNULL(lla.monto_cp,0),2) AS 'MONTO_CP',
                    IFNULL( ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto  ),'') AS 'CONTACTO',
                    IFNULL( lla.nombre_contacto,'' ) AS 'NOMBRE_CONTACTO',
                    IFNULL( ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) ,'') AS 'PARENTESCO',
                    IFNULL( ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago ) ,'') AS 'MOTIVO_NO_PAGO',
                    (SELECT nombre FROM ca_estado_cliente WHERE idestado_cliente=lla.idestado_cliente) AS 'ESTADO_CLIENTE',
                    IFNULL(lla.observacion,'') AS 'OBS',
                    ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio ) AS 'USUARIO'
                    FROM ca_cliente_cartera clicar 
                    INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera 
    				WHERE 
                    clicar.idcliente = ? AND 
                    lla.estado = 1 AND 
                    lla.tipo<>'S'
    				ORDER BY $sidx $sord LIMIT $start , $limit  
                ";

        //echo $sql;
        //exit();

        $idcliente = $dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = "    SELECT COUNT(*) AS 'COUNT' 
        			FROM ca_cliente_cartera clicar 
                    INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
        			WHERE clicar.idcliente = ? AND lla.estado = 1 AND lla.tipo<>'S'";

        $idcliente = $dtoCliente->getId();
        $codigo_cliente = $dtoCliente->getCodigo();

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountSpeechListar(dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
				FROM ca_ayuda_gestion ag INNER JOIN ca_tipo_ayuda_gestion tag 
				ON tag.idtipo_ayuda_gestion=ag.idtipo_ayuda_gestion  
				WHERE ag.idservicio=? ";

        $servicio = $dtoAyudaGestion->getIdServicio();

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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountEstadoTransaccion(dto_servicio $dtoServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
			FROM ca_estado_transaccion WHERE idservicio = ? AND estado = 1 ";

        $servicio = $dtoServicio->getId();

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
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDRowsPrioridadTransaccion($sidx, $sord, $start, $limit, dto_estado_transaccion $dtoEstadoTransaccion) {
        $sql = " SELECT idpeso_transaccion , peso , DATE(fecha_creacion) AS 'fecha_registro' 
				FROM ca_peso_transaccion WHERE idestado_transaccion = ? AND estado = 1 ORDER BY $sidx $sord LIMIT $start , $limit ";

        $EstadoTransaccion = $dtoEstadoTransaccion->getId();

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountTarea($anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_tarea 
			WHERE idusuario_servicio = ? AND estado = 1 AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    /*     * ***** Evento ****** */

    public function JQGRIDRowsEvento($sidx, $sord, $start, $limit, $anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT idevento,evento,hora  
			FROM ca_evento where idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountEvento($anio, $mes, $dia, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " SELECT COUNT(*) AS 'COUNT'
			FROM ca_evento WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND DAY(fecha)=$dia  ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    /*     * ****** Todos los usuarios ******* */

    public function JQGRIDRowsUsuarioAll($sidx, $sord, $start, $limit, $search, $param, $querySearch) {
        $sql = " SELECT idusuario,IFNULL(codigo,'') AS 'codigo',CONCAT_WS(' ',nombre,paterno,materno) AS 'nombre',
			IFNULL(email,'') AS 'email',dni,DATE(fecha_creacion) AS 'fecha_registro'
			FROM ca_usuario WHERE estado=1 $search $querySearch
			ORDER BY $sidx $sord LIMIT $start , $limit ";

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

    public function JQGRIDCountUsuarioAll($search, $param, $querySearch) {
        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_usuario WHERE estado=1 $search $querySearch	";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        if ($pr->execute()) {
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
        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario);
        if ($pr->execute()) {
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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

        $factoryConnection = FactoryConnection::create('mysql');
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
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente=cli.idcliente
			WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.estado=1 " . $where . " 
			ORDER BY $sidx $sord LIMIT $start , $limit ";

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

    public function JQGRIDCountDistribucionPorOperador($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
			WHERE clicar.idcartera = :cartera AND cli.idservicio = :servicio AND clicar.estado=1 " . $where . " ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountClientesCartera($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera " . $where . " ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountClientesBusquedaGlobal($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cartera car 
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
				INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente 
				INNER JOIN ca_servicio ser ON ser.idservicio = cli.idservicio $where ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountClientesEspecialesAsignadosTeleoperador($param, $where) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente_cartera clicar INNER JOIN 
				( SELECT codigo, paterno, materno, nombre, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = :servicio  ) cli
				ON cli.codigo = clicar.codigo_cliente
				WHERE clicar.idcartera = :cartera AND clicar.idusuario_servicio_especial = :usuario_servicio " . $where . " ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountAtencionClienteByService($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado = 1 AND clicar.idcartera = :cartera AND cli.idservicio = :servicio $where ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function JQGRIDCountFacturasDigitales($where, $param) {

        $sql = " SELECT COUNT(*) as 'COUNT' FROM ca_factura_digital INNER JOIN ca_usuario_servicio ON ca_usuario_servicio.idusuario_servicio = ca_factura_digital.usuario_creacion INNER JOIN ca_usuario ON ca_usuario_servicio.idusuario = ca_usuario.idusuario  AND  ca_factura_digital.idusuario_servicio = ? AND ca_factura_digital.is_send = 0 $where ";

        $factoryConnection = FactoryConnection::create('mysql');
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

        $sql = " SELECT DISTINCT car.idcartera,
                IF( DATE(car.fecha_fin)<CURDATE(), CONCAT('<font color=red>',car.nombre_cartera,'</font>') , car.nombre_cartera) AS 'nombre_cartera', 
                IFNULL(car.fecha_inicio,'') AS 'fecha_inicio', 
                IFNULL(car.fecha_fin,'') AS 'fecha_fin', 
                IF( DATE(car.fecha_fin)<CURDATE(), 1 , 0) AS 'vencido' 
                FROM ca_cartera car
                INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                WHERE car.estado = 1 AND car.idcampania = ?  AND car.status = 'ACTIVO' 
                $where ORDER BY $sidx $sord LIMIT $start, $limit";
        
        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function JQGRIDCountCarterasXoperador($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_cartera car
		INNER JOIN ca_cliente_cartera clicar on clicar.idcartera=car.idcartera
		WHERE car.estado=1 AND car.idcampania = ? AND car.status = 'ACTIVO'  $where";

        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    /*     * ********************  Control Gestion ( Gestiones por Servicio ) ********************** */

    public function JQGRIDRowsGestionesPorServicio($sidx, $sord, $start, $limit, $where, $param) {

        $sql = " SELECT car.idcartera, cam.nombre AS 'campania', car.nombre_cartera AS 'nombre_cartera',
				DATE(car.fecha_carga) AS 'fecha_carga',
				car.fecha_inicio  AS 'fecha_inicio', car.fecha_fin AS 'fecha_fin', car.status , 
                                car.cantidad AS 'registros',
				IFNULL(car.meta_cliente,0) AS 'meta_cliente', 
                                IFNULL(car.meta_cuenta,0) AS 'meta_cuenta',
                                IFNULL(car.meta_monto,0) AS 'meta_monto',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera = car.idcartera ) AS 'clientes',
				( SELECT COUNT(*) FROM ca_cuenta WHERE idcartera = car.idcartera ) AS 'cuenta',
				( SELECT COUNT(*) FROM ca_detalle_cuenta WHERE idcartera = car.idcartera ) AS 'detalle',
                car.flag_provincia
				FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania 
				WHERE cam.estado = 1 AND car.estado = 1  $where ORDER BY $sidx $sord LIMIT $start, $limit";

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

    public function JQGRIDCountGestionesPorServicio($where, $param) {

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania
			WHERE cam.estado = 1 AND car.estado = 1 $where ";

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function Listar_Telefono_Aval($codigo_cliente){
        $sql="SELECT idtelefono,numero FROM ca_telefono WHERE codigo_cliente='$codigo_cliente' AND estado=1";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //return $pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'telefono'=>$pr->fetchAll(PDO::FETCH_ASSOC)));
        } else {
            return array(array('COUNT' => 0));
        }

    }
    public function Listar_Direccion_Aval($codigo_cliente){
        $sql="SELECT iddireccion,codigo_cliente,direccion,referencia,departamento,provincia,distrito FROM ca_direccion WHERE codigo_cliente='$codigo_cliente' AND estado=1";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //return $pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'direccion'=>$pr->fetchAll(PDO::FETCH_ASSOC)));
        } else {
            return array(array('COUNT' => 0));
        }

    }

    public function ListarSemana_opcion($semana){
        $sql="SELECT DISTINCT semana FROM ca_cliente_cartera WHERE idcartera=$semana ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {            
            echo json_encode(array('rst'=>true,'semana'=>$pr->fetchAll(PDO::FETCH_ASSOC)));
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function Listar_Representante($codigo_cliente){

        $sqlrepresentante=" SELECT 
                                    rep.idrepresentante_legal AS 'idrepresentante_legal',
                                    rep.contrato,
                                    rep.codigo_cliente AS 'doi',
                                    IF(rep.representante_legal NOT IN (''),rep.representante_legal,CONCAT(rep.nombre,' ',rep.paterno,' ',rep.materno)) AS 'datos',
                                    rep.tipo_persona AS 'tipo_persona'
                                    FROM ca_representante_legal rep
                                    WHERE 
                                    rep.estado=1 AND                                    
                                    rep.contrato IN (SELECT DISTINCT negocio FROM ca_cuenta WHERE codigo_cliente='$codigo_cliente')";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sqlrepresentante);
        if ($pr->execute()) {            
            echo json_encode(array('rst'=>true,'representante'=>$pr->fetchAll(PDO::FETCH_ASSOC)));
        } else {
            return array(array('COUNT' => 0));
        }  
    }

    //MANTTELF
    public function JQGRIDCOUNTGestionTelefonos($codigo_cliente,$where, $param){
        $sql="  SELECT 
                        COUNT(*) AS 'COUNT'
                FROM
                        (
                                SELECT 
                                idtelefono, 
                                IFNULL(numero_act,numero) as numero, 
                                IFNULL(anexo,'') AS 'anexo', 
                                IFNULL(m_peso,0) AS peso ,
                                is_new, 
                                is_campo,
                                is_carga ,
                                IFNULL(referencia,'') AS referencia ,
                                IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') AS estado ,
                                IFNULL(( SELECT CONCAT_WS(':',nombre,CONCAT_WS('-',lb_prefijo,lb_prefijo2,lb_prefijo3)) FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),':') AS prefijos,org.nombre AS 'origen'  
                                FROM ca_telefono tel 
                                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                                WHERE 
                                codigo_cliente = '$codigo_cliente' AND 
                                estado = 1  AND 
                                is_active = 1 -- AND 
                                -- IF(idtipo_referencia=3,IF(tel.idorigen=1,0,1),1)=1 AND CAST(numero AS SIGNED)!=0
                                $where
                                ORDER BY peso DESC
                        ) t1                
            ";
        //echo($sql);

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDGestionTelefonos($sidx, $sord, $start, $limit,$codigo_cliente,$where, $param){
        $sql="  SELECT 
                        t1.idtelefono AS 'idtelefono',
                        t1.numero AS 'numero',
                        t1.anexo AS 'anexo', 
                        t1.tipo AS 'tipo',
                        t1.is_new AS 'is_new', 
                        t1.is_campo AS 'is_campo',
                        t1.is_carga AS 'is_carga', 
                        t1.referencia AS 'referencia', 
                        t1.estado AS 'estado', 
                        t1.prefijos AS 'prefijos', 
                        t1.peso AS 'peso', 
                        t1.origen  AS 'origen',
                        t1.observacion
                FROM
                        (
                                SELECT 
                                idtelefono, 
                                IFNULL(numero_act,numero) as numero, 
                                IFNULL(anexo,'') AS 'anexo', 
                                IFNULL(m_peso,0) AS peso ,
                                tip.nombre AS 'tipo',
                                is_new, 
                                is_campo,
                                is_carga ,
                                IFNULL((SELECT nombre FROM  ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia),'') AS 'referencia' ,
                                IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') AS estado ,
                                IFNULL(( SELECT nombre FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),'') AS prefijos,
                                org.nombre AS 'origen',
                                observacion
                                FROM ca_telefono tel 
                                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                                LEFT JOIN ca_tipo_telefono tip ON tip.idtipo_telefono=tel.idtipo_telefono
                                WHERE 
                                codigo_cliente = '$codigo_cliente' AND 
                                estado = 1  AND 
                                is_active = 1 -- AND 
                                -- IF(idtipo_referencia=3,IF(tel.idorigen=1,0,1),1)=1 AND CAST(numero AS SIGNED)!=0 
                                $where
                                ORDER BY $sidx $sord LIMIT $start, $limit
                        ) t1
                
            ";

        //echo $sql;        
        //exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute($param)) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }

    }

    public function Listtipotelf(){

        $sql="SELECT idtipo_telefono,nombre FROM ca_tipo_telefono;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function Listreferenciatelf(){
        
        $sql="SELECT idtipo_referencia,nombre FROM ca_tipo_referencia;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function Listlineatelf(){
        
        $sql="SELECT idlinea_telefono,nombre FROM ca_linea_telefono;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function Listorigentelf(){
        
        $sql="SELECT idorigen,nombre FROM ca_origen;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function INSERT_Telf($idcliente_cartera,$codigo_cliente,$idcartera,$numero,$anexo,$tipo,$referencia,$prefijos,$origen,$observacion,$idusuario_servicio){

            $sql = "    INSERT INTO ca_telefono (
                            idcliente_cartera,
                            codigo_cliente, 
                            idcartera, 
                            numero, 
                            anexo, 
                            idtipo_telefono, 
                            idtipo_referencia, 
                            idlinea_telefono, 
                            idorigen, 
                            observacion, 
                            usuario_creacion, 
                            fecha_creacion, 
                            is_new,
                            is_active
                            ) 
                            VALUES( 
                            $idcliente_cartera,
                            '$codigo_cliente',
                            $idcartera,
                            '$numero',
                            '$anexo',
                            $tipo,
                            $referencia,
                            $prefijos,
                            $origen,
                            '$observacion',
                            $idusuario_servicio,
                            NOW(),
                            1,
                            1
                        )
                    ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"El numero se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar numero"));
            exit();
        }

    }

    public function UPDATE_Telf($id,$numero,$anexo,$tipo,$referencia,$prefijos,$origen,$observacion){

        $sql = "    UPDATE
                    ca_telefono
                    SET
                    numero='$numero',
                    anexo='$anexo',
                    idtipo_telefono=$tipo,
                    idtipo_referencia=$referencia,
                    idlinea_telefono=$prefijos,
                    idorigen=$origen,
                    observacion='$observacion'
                    WHERE
                    idtelefono=$id
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return array("rst"=>true,"msg"=>"El numero se modifico exitosamente");
        } else {
            return array("rst"=>false,"msg"=>"Error al modifico numero");
            exit();
        }

    }

    public function DELETE_Telf($id){
        $sql = " UPDATE ca_telefono SET is_active=0 WHERE idtelefono=$id";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return array("rst"=>true,"msg"=>"El numero se elimino exitosamente");
        } else {
            return array("rst"=>false,"msg"=>"Error al elimino numero");
            exit();
        }
    }

    public function List_number_exist($numero){
        $verifi_numero="SELECT COUNT(*) AS 'COUNT' FROM ca_telefono WHERE numero='$numero' AND estado=1 AND is_active=1";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr_verifi_numero = $connection->prepare($verifi_numero);
        if($pr_verifi_numero->execute()) {
            $cant_number=$pr_verifi_numero->fetchAll(PDO::FETCH_ASSOC);

            $confirm="";
            if($cant_number[0]['COUNT']==0){
                $confirm="NO";
            }else{
                $confirm="SI";
            }

            echo json_encode(array("rst"=>true,"msg"=>"se listo exitosamente numero telf","cant"=>$cant_number[0]['COUNT'],"exist"=>$confirm));

        }
    }
    //MANTTELF

    // CAMBIO 20-06-2016
    public function JQGRIDCOUNTGestionDireccion_opcion($codigo_cliente,$where, $param){
        $sql="  SELECT
                COUNT(*) AS 'COUNT'
                FROM
                ca_direccion dir
                LEFT JOIN ca_ubigeo ub ON ub.idubigeo=dir.ubigeo
                INNER JOIN ca_origen orig ON orig.idorigen=dir.idorigen
                INNER JOIN ca_tipo_referencia ref ON ref.idtipo_referencia=dir.idtipo_referencia
                WHERE
                dir.codigo_cliente='$codigo_cliente'
                
            ";
        //echo($sql);

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDGestionDireccion_opcion($sidx, $sord, $start, $limit,$codigo_cliente,$where, $param){
        $sql="  SELECT
                dir.iddireccion AS iddireccion,
                ub.idubigeo AS 'ubigeo',
                ub.departamento,
                ub.provincia,
                ub.distrito,
                ub.zona,
                -- dir.departamento AS departamento,
                -- dir.provincia AS provincia,
                -- dir.distrito AS distrito,
                dir.direccion AS direccion,
                dir.idorigen AS idorigen,
                orig.nombre AS origen_dir,
                dir.idtipo_referencia AS idtipo_referencia,
                ref.nombre AS referencia_dir
                FROM
                ca_direccion dir
                LEFT JOIN ca_ubigeo ub ON ub.idubigeo=dir.ubigeo
                INNER JOIN ca_origen orig ON orig.idorigen=dir.idorigen
                INNER JOIN ca_tipo_referencia ref ON ref.idtipo_referencia=dir.idtipo_referencia
                WHERE
                dir.codigo_cliente='$codigo_cliente'
                ORDER BY $sidx $sord LIMIT $start, $limit
            ";

        // echo $sql;        
        //exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }

    }

    public function List_Departamento(){
        $sql="SELECT DISTINCT departamento FROM ca_ubigeo;";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function List_Provincia($departamento){
        // if($xmod=='I'){
        //     $depto=isset($departamento[0])?$departamento[0]:'';
        // }else{
        //     $depto=isset($departamento)?$departamento:'';
        // }

        $depto=isset($departamento)?$departamento:'';
        
        $sql="SELECT DISTINCT provincia FROM ca_ubigeo WHERE departamento='$depto';";
        // echo($sql);
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function List_Distrito($dist){
        

        // if($xmod=='I'){
        //     $distrito=isset($dist[0])?$dist[0]:'';
        // }else{
        //     $distrito=isset($dist)?$dist:'';
        // }

        $distrito=isset($dist)?$dist:'';

        $sql="SELECT DISTINCT distrito FROM ca_ubigeo WHERE provincia='$distrito';";
        //echo($sql);
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function INSERT_Direct_Opcion($idcliente_cartera,$codigo_cliente,$cartera,$referencia,$origen,$idusuario_servicio,$departamento,$direccion,$distrito,$provincia){
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql_idubigeo=" SELECT idubigeo FROM ca_ubigeo WHERE departamento='$departamento' AND provincia='$provincia' AND distrito='$distrito' ";
        //echo $sql_idubigeo;
        //exit();
        $pr_idubigeo = $connection->prepare($sql_idubigeo);
        $pr_idubigeo->execute();
        $arr_idubigeo=$pr_idubigeo->fetchAll(PDO::FETCH_ASSOC);
        $arr_idubigeo=$arr_idubigeo[0]['idubigeo'];

        $sql="  INSERT INTO ca_direccion(
                direccion,
                ubigeo,
                departamento,
                provincia,
                distrito,
                fecha_creacion,
                usuario_creacion,
                idorigen,
                idtipo_referencia,
                idcartera,
                codigo_cliente,
                is_new,
                estado,
                idcliente_cartera
                ) VALUES(
                '$direccion',
                $arr_idubigeo,
                '$departamento',
                '$provincia',
                '$distrito',
                NOW(),
                $idusuario_servicio,
                $origen,
                $referencia,
                $cartera,
                $codigo_cliente,
                1,
                1,
                $idcliente_cartera
                )";

        //echo $sql;

        $pr = $connection->prepare($sql);
        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"El numero se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar numero"));
            exit();
        }

    }

    public function UPDATE_Direct_Opcion($id,$idcliente_cartera,$codigo_cliente,$cartera,$referencia,$origen,$idusuario_servicio,$departamento,$direccion,$distrito,$provincia){
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql_idubigeo=" SELECT idubigeo FROM ca_ubigeo WHERE departamento='$departamento' AND provincia='$provincia' AND distrito='$distrito' ";
        $pr_idubigeo = $connection->prepare($sql_idubigeo);
        $pr_idubigeo->execute();
        $arr_idubigeo=$pr_idubigeo->fetchAll(PDO::FETCH_ASSOC);
        $arr_idubigeo=$arr_idubigeo[0]['idubigeo'];

        $sql="  UPDATE ca_direccion
                SET
                direccion='$direccion',
                ubigeo=$arr_idubigeo,
                departamento='$departamento',
                provincia='$provincia',
                distrito='$distrito',
                fecha_modificacion=NOW(),
                usuario_creacion=$idusuario_servicio,
                idorigen=$origen,
                idtipo_referencia=$referencia,
                idcartera=$cartera,
                codigo_cliente=$codigo_cliente,
                is_new=1,
                estado=1,
                idcliente_cartera=$idcliente_cartera
                WHERE iddireccion=$id";   

        //echo $sql;

        $pr = $connection->prepare($sql);
        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"El numero se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar numero"));
            exit();
        }
    }

    public function DELETE_Direct_Opcion($id){
        $sql = " UPDATE ca_direccion SET estado=0 WHERE iddireccion=$id";
        //echo $sql;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return array("rst"=>true,"msg"=>"El numero se elimino exitosamente");
        } else {
            return array("rst"=>false,"msg"=>"Error al elimino numero");
            exit();
        }
    }

    // CAMBIO 20-06-2016

    public function Listar_Cartera_Opcion(){
        $sql="SELECT idcartera,idcampania,nombre_cartera FROM ca_cartera WHERE estado=1 AND idcartera NOT IN (1);";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            $cartera_opcion=$pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array("rst"=>true,"msg"=>"Ejecucion Correcta",'carteras'=>$cartera_opcion));
        } else {
            echo json_encode(array("rst"=>false,"msg"=>"Error al Ejecutarse"));
            exit();
        }

    }

    public function JQGRIDCOUNTList_Telf_cobranzas_andina($codigo_cliente){
        $sql="  SELECT 
                COUNT(*) AS 'COUNT'
                FROM ca_telefono tel 
                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                LEFT JOIN ca_tipo_telefono tip ON tip.idtipo_telefono=tel.idtipo_telefono
                WHERE 
                codigo_cliente = '$codigo_cliente'";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDList_Telf_cobranzas_andina($sidx, $sord, $start, $limit,$codigo_cliente){
        $sql="  SELECT 
                idtelefono, 
                IFNULL(numero_act,numero) as numero, 
                IFNULL(anexo,'') AS 'anexo', 
                IFNULL(m_peso,0) AS peso ,
                tip.nombre AS 'tipo',
                is_new, 
                is_campo,
                is_carga ,
                IFNULL((SELECT nombre FROM  ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia),'') AS 'referencia' ,
                IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') AS estado ,
                IFNULL(( SELECT nombre FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),'') AS prefijos,
                org.nombre AS 'origen',
                tel.status as 'condicion',
                IF(is_active=0,'NO ACTIVO','ACTIVO') AS 'state',
                IF(estado=0,'BAJA','ALTA') AS 'status',
                observacion
                FROM ca_telefono tel 
                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                LEFT JOIN ca_tipo_telefono tip ON tip.idtipo_telefono=tel.idtipo_telefono
                WHERE 
                codigo_cliente = '$codigo_cliente'
                ORDER BY $sidx $sord 
                LIMIT $start, $limit";

        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function si_elnumero_telf_existe($numero){
        $verifi_numero="SELECT GROUP_CONCAT(codigo_cliente SEPARATOR ',') AS 'codigo_cliente' FROM ca_telefono WHERE numero='$numero'";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr_verifi_numero = $connection->prepare($verifi_numero);
        if($pr_verifi_numero->execute()) {
            $cant_number=$pr_verifi_numero->fetchAll(PDO::FETCH_ASSOC);

            // echo count($cant_number)."<br>";
            // print_r($cant_number);

            $confirm="";
            if($cant_number[0]['codigo_cliente']==NULL){
                $confirm="NO";
            }else if(count($cant_number)>0){
                $confirm="SI";
            }

            $codigo_cliente=$cant_number[0]['codigo_cliente'];



            // print_r($cant_number);

            echo json_encode(array("rst"=>true,"msg"=>"se listo exitosamente numero telf","codigo_cliente"=>$codigo_cliente,"exist"=>$confirm));

        }
    }

    public function save_telf_cobranza_andina($numero,$anexo,$tipo,$referencia,$linea,$origen,$condi,$obs,$idcliente_cartera,$codigo_cliente,$idcartera,$usuario_creacion){

        $tipo=empty($tipo) ?  3 : $tipo;
        $referencia=empty($referencia) ?  1 : $referencia;
        $linea=empty($linea) ?  1 : $linea;
        $origen=empty($origen) ?  2 : $origen;

        $sql = "    INSERT INTO ca_telefono (
                            idcliente_cartera,
                            codigo_cliente, 
                            idcartera, 
                            numero, 
                            anexo, 
                            idtipo_telefono, 
                            idtipo_referencia, 
                            idlinea_telefono, 
                            idorigen, 
                            observacion, 
                            usuario_creacion, 
                            fecha_creacion, 
                            is_new,
                            is_active,
                            status
                            ) 
                            VALUES( 
                            $idcliente_cartera,
                            '$codigo_cliente',
                            $idcartera,
                            '$numero',
                            '$anexo',
                            '$tipo',
                            '$referencia',
                            '$linea',
                            '$origen',
                            '$obs',
                            $usuario_creacion,
                            NOW(),
                            1,
                            1,
                            '$condi'
                        )
                    ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array("rst"=>true,"rpta"=>"Se Inserto telf"));
        } else {
            echo json_encode(array("rst"=>false,"rpta"=>"No Inserto telf"));
        }
    }

    public function List_Update_Telf_Andina($idtelefono){
        $sql="  SELECT 
                idtelefono, 
                IFNULL(numero_act,numero) as numero, 
                IFNULL(anexo,'') AS 'anexo', 
                IFNULL(m_peso,0) AS peso ,
                tip.idtipo_telefono AS 'tipo', --
                is_new, 
                is_campo,
                is_carga ,
                tel.idtipo_referencia AS 'referencia' , --
                tel.idfinal AS estado ,
                tel.idlinea_telefono AS prefijos, --
                org.idorigen AS 'origen', -- 
                is_active AS 'state', --
                estado AS 'status', --
                tel.status as 'condicion',
                observacion
                FROM ca_telefono tel 
                INNER JOIN ca_origen org ON org.idorigen = tel.idorigen
                LEFT JOIN ca_tipo_telefono tip ON tip.idtipo_telefono=tel.idtipo_telefono
                WHERE 
                idtelefono = $idtelefono";

        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            $arr=$pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array("rst"=>true,"rpta"=>$arr));
        } else {
            echo json_encode(array("rst"=>false,"rpta"=>"No Se listo correctamente"));
        }
    }

    public function update_telf_andina($idtelefono,$numero,$anexo,$tipo,$referencia,$linea,$origen,$obs,$state,$status,$condi){

        $tipo=empty($tipo) ?  3 : $tipo;
        $referencia=empty($referencia) ?  1 : $referencia;
        $linea=empty($linea) ?  1 : $linea;
        $origen=empty($origen) ?  2 : $origen;

        $sql = "    UPDATE
                    ca_telefono
                    SET
                    numero='$numero',
                    anexo='$anexo',
                    idtipo_telefono=$tipo,
                    idtipo_referencia=$referencia,
                    idlinea_telefono=$linea,
                    idorigen=$origen,
                    observacion='$obs',
                    is_active=$state,
                    estado=$status,
                    status='$condi'
                    WHERE
                    idtelefono=$idtelefono
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            echo json_encode(array("rst"=>true,"msg"=>"El numero se modifico exitosamente"));
        } else {
            echo json_encode(array("rst"=>false,"msg"=>"Error al modifico numero"));
            exit();
        }
    }

    public function eliminar_telf_andina($idtelefono){

        $sql = "    UPDATE
                    ca_telefono
                    SET                    
                    estado=0
                    WHERE
                    idtelefono=$idtelefono
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            echo json_encode(array("rst"=>true,"msg"=>"El telefono fue dado de baja"));
        } else {
            echo json_encode(array("rst"=>false,"msg"=>"Error al dar de baja al telefono"));
            exit();
        }
    }

    public function JQGRIDCOUNTlista_direccion_cobranzas($codigo_cliente){
        $sql="  SELECT
                COUNT(*) 'COUNT'
                FROM
                ca_direccion dir
                -- INNER JOIN ca_ubigeo ub ON ub.idubigeo=dir.ubigeo
                WHERE
                dir.codigo_cliente='$codigo_cliente'";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDlista_direccion_cobranzas($sidx, $sord, $start, $limit,$codigo_cliente){
        $sql="  SELECT
                dir.iddireccion,
                dir.direccion,
                dir.ubigeo,
                dir.departamento,
                dir.provincia,
                dir.distrito,
                dir.region,
                dir.zona,
                dir.codigo_postal,
                dir.numero,
                dir.calle,
                dir.referencia,
                dir.observacion,
                (SELECT nombre FROM ca_origen WHERE idorigen=dir.idorigen) AS 'origen',
                (SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=dir.idtipo_referencia) AS 'tipo_referencia',
                dir.fecha_creacion,
                dir.usuario_creacion,
                dir.idcartera,
                dir.codigo_cliente,
                dir.is_new,
                IF(dir.estado=1,'ALTA','BAJA') AS 'estado',
                dir.idcliente_cartera,
                dir.status AS 'condicion'
                FROM
                ca_direccion dir
                -- INNER JOIN ca_ubigeo ub ON ub.idubigeo=dir.ubigeo
                WHERE
                dir.codigo_cliente='$codigo_cliente'
                ORDER BY $sidx $sord 
                LIMIT $start, $limit";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }


    public function insertar_nueva_direccion_andina($direccion,$departamento,$provincia,$distrito,$region,$zona,$codigo_postal,$numero,$calle,$txtref,$referencia,$origen,$condicion,$observacion,$idcliente_cartera,$codigo_cliente,$cartera,$idusuario_servicio){
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql_idubigeo=" SELECT idubigeo FROM ca_ubigeo WHERE departamento='$departamento' AND provincia='$provincia' AND distrito='$distrito' ";

        $pr_idubigeo = $connection->prepare($sql_idubigeo);
        $pr_idubigeo->execute();
        $arr_idubigeo=$pr_idubigeo->fetchAll(PDO::FETCH_ASSOC);
        $arr_idubigeo=$arr_idubigeo[0]['idubigeo'];

        $origen=empty($origen) ?  2 : $origen;
        $referencia=empty($referencia) ?  1 : $referencia;

        $sql="  INSERT INTO ca_direccion(
                direccion,
                ubigeo,
                departamento,
                provincia,
                distrito,
                region,
                zona,
                codigo_postal,
                numero,
                calle,
                observacion,
                idorigen,
                referencia,
                idtipo_referencia,
                fecha_creacion,
                usuario_creacion,
                idcartera,
                codigo_cliente,
                is_new,
                estado,
                idcliente_cartera,
                status
                ) VALUES(
                '$direccion',
                $arr_idubigeo,
                '$departamento',
                '$provincia',
                '$distrito',
                '$region',
                '$zona',
                '$codigo_postal',
                '$numero',
                '$calle',
                '$observacion',
                $origen,
                '$txtref',
                $referencia,                
                NOW(),
                $idusuario_servicio,
                $cartera,
                '$codigo_cliente',
                1,
                1,
                $idcliente_cartera,
                '$condicion'
                )";

        //echo $sql;

        $pr = $connection->prepare($sql);
        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"La Dirección del Cliente se se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar Dirección del Cliente"));
            exit();
        }

    }

    public function modificar_direccion_andina($iddireccion,$direccion,$departamento,$provincia,$distrito,$region,$zona,$codigo_postal,$numero,$calle,$referencia,$tipo_referencia,$origen,$condicion,$estado,$observacion){
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql_idubigeo=" SELECT idubigeo FROM ca_ubigeo WHERE departamento='$departamento' AND provincia='$provincia' AND distrito='$distrito' ";
        $pr_idubigeo = $connection->prepare($sql_idubigeo);
        $pr_idubigeo->execute();
        $arr_idubigeo=$pr_idubigeo->fetchAll(PDO::FETCH_ASSOC);
        $arr_idubigeo=$arr_idubigeo[0]['idubigeo'];

        $sql="  UPDATE ca_direccion
                SET
                direccion='$direccion',
                ubigeo=$arr_idubigeo,
                departamento='$departamento',
                provincia='$provincia',
                distrito='$distrito',
                idorigen=$origen,
                idtipo_referencia=$tipo_referencia,
                region='$region',
                zona='$zona',
                codigo_postal='$codigo_postal',
                numero='$numero',
                calle='$calle',
                referencia='$referencia',
                observacion='$observacion',
                estado=$estado,
                fecha_modificacion=NOW(),
                status='$condicion'
                WHERE iddireccion=$iddireccion";   

        // echo $sql;
        // eixt();

        $pr = $connection->prepare($sql);
        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"El numero se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar numero"));
            exit();
        }
    }

    public function eliminar_direccion_andina($iddireccion){
        $sql = "    UPDATE
                    ca_direccion
                    SET                    
                    estado=0
                    WHERE
                    iddireccion=$iddireccion
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            echo json_encode(array("rst"=>true,"msg"=>"El direccion fue dado de baja"));
        } else {
            echo json_encode(array("rst"=>false,"msg"=>"Error al dar de baja al direccion"));
            exit();
        }
    }

    public function JQGRIDCOUNTList_Direccion_cobranzas_andina($idcliente){

        $sql="  SELECT
                COUNT(*) AS 'COUNT'
                FROM
                ca_correo
                WHERE
                idcliente='$idcliente';
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }
    public function JQGRIDList_Direccion_cobranzas_andina($sidx, $sord, $start, $limit,$idcliente){

        $sql="  SELECT
                idcorreo,
                correo,
                observacion,
                IF(estado=1,'ALTA','BAJA') AS 'estado',
                usuario_creacion,
                fecha_creacion,
                idcliente
                FROM
                ca_correo
                WHERE
                idcliente='$idcliente';
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function save_mail_andina($mail,$obs,$idusuario_servicio,$idcliente){
        $sql = "    INSERT INTO ca_correo (
                    correo,
                    observacion,
                    usuario_creacion,
                    fecha_creacion,
                    idcliente,
                    estado
                    ) 
                    VALUES( 
                    '$mail',
                    '$obs',
                    '$idusuario_servicio',
                    NOW(),
                    '$idcliente',
                    1
                )
            ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return json_encode(array("rst"=>true,"msg"=>"El correo se grabo exitosamente"));
        } else {
            return json_encode(array("rst"=>false,"msg"=>"Error al grabar correo"));
            exit();
        }
    }

    public function UPDATE_Correo($correo,$observacion,$idcorreo,$usuario_creacion,$estado){

        $sql = "    UPDATE
                    ca_correo
                    SET
                    correo='$correo',
                    observacion='$observacion',
                    fecha_modificacion=NOW(),
                    usuario_modificacion='$usuario_creacion',
                    estado=$estado
                    WHERE
                    idcorreo=$idcorreo
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return array("rst"=>true,"msg"=>"El correo se modifico exitosamente");
        } else {
            return array("rst"=>false,"msg"=>"Error al modifico correo");
            exit();
        }

    }

    public function eliminar_mail_andina($idcorreo){
        $sql = "    UPDATE
                    ca_correo
                    SET
                    estado=0
                    WHERE
                    idcorreo=$idcorreo
                ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        if($pr->execute()) {
            return array("rst"=>true,"msg"=>"El correo se dio de baja exitosamente");
        } else {
            return array("rst"=>false,"msg"=>"Error al dar de baja al correo");
            exit();
        }
    }

    public function JQGRIDCOUNT_Buscar_Cliente($idservicio,$codigo_cliente,$cliente,$td,$documento){
        $where="";
        if($codigo_cliente!=""){
            $where.="AND  cli.codigo = '$codigo_cliente' ";
        }
        if($cliente!=""){
            $where.="AND  TRIM(CONCAT_WS(' ',cli.razon_social)) LIKE '%$cliente%' ";   
        }
        if($td!="" AND $documento!=""){
            $where.=" AND cli.codigo IN (SELECT DISTINCT codigo_cliente FROM ca_detalle_cuenta WHERE estado=1 AND dato8='$td' AND codigo_operacion ='$documento')";
        }

        $sql="  SELECT 
                COUNT(*) AS 'COUNT'
                FROM 
                ca_cliente cli 
                INNER JOIN ca_cliente_cartera clicar 
                INNER JOIN ca_cartera car 
                INNER JOIN ca_campania cam
                ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
                WHERE 
                car.status = 'ACTIVO' AND 
                cam.status = 'ACTIVO' AND 
                car.estado = 1 AND 
                cli.idservicio = $idservicio AND 
                cli.estado = 1 AND 
                cam.idservicio = $idservicio
                $where
                ";
        // echo $sql;
        // exit();
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }

    }

    public function JQGRID_Buscar_Cliente($sidx,$sord,$start,$limit,$idservicio,$codigo_cliente,$cliente,$td,$documento){
        $where="";
        if($codigo_cliente!=""){
            $where.=" AND  cli.codigo = '$codigo_cliente' ";
        }
        if($cliente!=""){
            $where.=" AND TRIM(CONCAT_WS(' ',cli.razon_social)) LIKE '%$cliente%' ";   
        }
        if($td!="" AND $documento!=""){
            $where.=" AND cli.codigo IN (SELECT DISTINCT codigo_cliente FROM ca_detalle_cuenta WHERE estado=1 AND dato8='$td' AND codigo_operacion ='$documento')";
        }

        $sql="  SELECT 
                DISTINCT clicar.idcliente_cartera,
                cli.idcliente,
                car.idcartera,
                car.nombre_cartera,
                cli.codigo, 
                '' AS 'contrato',
                TRIM(CONCAT_WS(' ',cli.razon_social)) AS 'cliente',
                IFNULL( cli.numero_documento,'' ) AS 'numero_documento', 
                IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
                FROM
                ca_cliente cli 
                INNER JOIN ca_cliente_cartera clicar 
                INNER JOIN ca_cartera car 
                INNER JOIN ca_campania cam
                ON cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.idcliente=cli.idcliente
                WHERE 
                car.status = 'ACTIVO' AND 
                cam.status = 'ACTIVO' AND 
                car.estado = 1 AND 
                cli.idservicio = $idservicio AND 
                cli.estado =1 AND 
                cam.idservicio = $idservicio 
                $where
                ORDER BY $sidx $sord LIMIT $start , $limit;
                ";
        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function consultar_datos_cliente($idservicio,$idcartera,$codigo_cliente,$idcliente_cartera){
        $sql="  SELECT 
                clicar.codigo_cliente AS 'CODIGO_CLIENTE', 
                IF(TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) IS NULL OR TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))='',cli.razon_social,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))) AS 'CLIENTE', 
                IFNULL(cli.tipo_documento,'') AS 'TIPO_DOCUMENTO', 
                IFNULL(cli.numero_documento,'') AS 'NUMERO_DOCUMENTO',
                IFNULL(cli.fecha_nacimiento,'') AS 'FECHA_NACIMIENTO', 
                IFNULL(clicar.tipo_cliente,'') AS 'TIPO_CLIENTE', 
                car.nombre_cartera AS NOMBRE_CARTERA,
                (SELECT DISTINCT dato16 FROM ca_detalle_cuenta WHERE idcartera=$idcartera AND estado=1 AND codigo_cliente='$codigo_cliente') AS 'LINEA_CREDITO'
                FROM ca_cliente cli 
                INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente 
                INNER JOIN ca_cartera car ON clicar.idcartera=car.idcartera 
                WHERE 
                cli.idservicio = $idservicio AND 
                clicar.idcartera = $idcartera AND 
                clicar.codigo_cliente = '$codigo_cliente' AND 
                clicar.idcliente_cartera=$idcliente_cartera";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        // echo $sql;
        // exit();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            // return $pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'data'=>$pr->fetchAll(PDO::FETCH_ASSOC)));
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRIDCOUNT_Listar_Contactos($idcliente){
        $sql="  SELECT
                COUNT(*) AS 'COUNT'
                FROM
                ca_persona
                WHERE
                idcliente=$idcliente AND
                estado=1;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function JQGRID_Listar_Contactos($sidx,$sord,$start,$limit,$idcliente){
        $sql="  SELECT
                idpersona,
                razon_social,
                nombre,
                paterno,
                materno,
                tipo_documento,
                numero_documento,
                estado,
                idcliente
                FROM 
                ca_persona
                WHERE
                idcliente=$idcliente AND
                estado=1;";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array(array('COUNT' => 0));
        }
    }

    public function Mant_ADDContactos($razon_social,$nombre,$paterno,$materno,$tipo_documento,$numero_documento,$idcliente){
        $sql="   INSERT INTO 
                    `cob_andina`.`ca_persona` (
                    `razon_social`, 
                    `nombre`, 
                    `paterno`, 
                    `materno`, 
                    `tipo_documento`, 
                    `numero_documento`, 
                    `estado`, 
                    `idcliente`
                    ) 
                    VALUES (
                    '$razon_social',
                    '$nombre',
                    '$paterno',
                    '$materno',
                    '$tipo_documento',
                    '$numero_documento',
                    1,
                    $idcliente
                    );
                    ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){                        
            return array('rst'=>true,'msg'=>'Se inserto informacion...!!!');//TRUE
        } else {
            return array('rst'=>false,'msg'=>'Error al grabar');//FALSE
            exit();
        }
    }

    public function Mant_EDITContactos($idpersona,$razon_social,$nombre,$paterno,$materno,$tipo_documento,$numero_documento){
        $sql="  UPDATE 
                ca_persona 
                SET 
                razon_social='$razon_social',
                nombre='$nombre',
                paterno='$paterno',
                materno='$materno',
                tipo_documento='$tipo_documento',
                numero_documento='$numero_documento' 
                WHERE 
                idpersona=$idpersona";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){                        
            return array('rst'=>true,'msg'=>'Se actualizo informacion...!!!');//TRUE
        } else {
            return array('rst'=>false,'msg'=>'Error al grabar');//FALSE
            exit();
        }
    }

    public function Mant_DELETEContactos($idpersona){
        $sql="  UPDATE 
                ca_persona 
                SET 
                estado=0 
                WHERE 
                idpersona=$idpersona";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){                        
            return array('rst'=>true,'msg'=>'Se actualizo informacion...!!!');//TRUE
        } else {
            return array('rst'=>false,'msg'=>'Error al grabar');//FALSE
            exit();
        }
    }

    public function Listar_Contactos_telf($idpersona){
        $sql="  SELECT 
                tlfp.idtelefono_pers,
                tlfp.numero,
                tlfp.idorigen,
                (SELECT nombre FROM ca_origen WHERE idorigen=tlfp.idorigen) AS origen,
                tlfp.idtipo_telefono,
                (SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tlfp.idtipo_telefono) AS tipo_telefono,
                tlfp.idlinea_telefono,
                (SELECT nombre FROM ca_linea_telefono WHERE idlinea_telefono=tlfp.idlinea_telefono) AS linea_telefono,
                tlfp.estado,
                tlfp.idpersona 
                FROM 
                ca_telefono_pers tlfp
                WHERE 
                tlfp.idpersona=$idpersona AND
                tlfp.estado=1";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            $datos=$pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'datos'=>$datos,'msg'=>'Se Listo Correctamente'));//TRUE
        } else {
            echo json_encode(array('rst'=>false,'datos'=>$datos,'msg'=>'Error al Listar'));//FALSE
            exit();
        }
    }

    public function cbo_listar_origen(){
        $sql="SELECT idorigen,nombre,descripcion FROM ca_origen";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }

    public function cbo_tipo_telefono(){
        $sql="SELECT idtipo_telefono,nombre,descripcion FROM ca_tipo_telefono";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }

    public function cbo_linea_telefono(){
        $sql="SELECT idlinea_telefono,nombre FROM ca_linea_telefono";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }

    public function insertar_contacto_tefl($idpersona,$nro_telf,$ori_telf,$tip_telf,$lin_telf){
        // echo $nro_telf."--".$ori_telf."--".$tip_telf."--".$lin_telf;

        $sql="INSERT INTO `cob_andina`.`ca_telefono_pers` ( `numero`, `idorigen`, `idtipo_telefono`, `idlinea_telefono`, `estado`, `idpersona`) VALUES ( '$nro_telf', '$ori_telf', '$tip_telf', '$lin_telf', '1', '$idpersona');";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }

    }
    public function modificar_contacto_tefl($idtelefono_pers,$nro_telf,$ori_telf,$tip_telf,$lin_telf){
        // echo $nro_telf."--".$ori_telf."--".$tip_telf."--".$lin_telf;

        $sql="UPDATE `cob_andina`.`ca_telefono_pers` SET  `numero`='$nro_telf', `idorigen`='$ori_telf', `idtipo_telefono`='$tip_telf', `idlinea_telefono`='$lin_telf' WHERE `idtelefono_pers`='$idtelefono_pers';";
        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }

    }
    public function borrar_contacto_tefl($idtelefono_pers){
        $sql="UPDATE `cob_andina`.`ca_telefono_pers` SET  `estado`='0' WHERE `idtelefono_pers`='$idtelefono_pers';";
        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }
    }

    public function Listar_Contactos_mail($idpersona){
        $sql="  SELECT 
                idemail_pers,
                email,
                estado,
                idcliente,
                idpersona
                FROM 
                ca_email_pers
                WHERE 
                idpersona=$idpersona AND
                estado=1";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if($pr->execute()){
            $datos=$pr->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('rst'=>true,'datos'=>$datos,'msg'=>'Se Listo Correctamente'));//TRUE
        } else {
            echo json_encode(array('rst'=>false,'datos'=>$datos,'msg'=>'Error al Listar'));//FALSE
            exit();
        }
    }
    public function insertar_contacto_mail($idpersona,$email){
        $sql="INSERT INTO `cob_andina`.`ca_email_pers` ( `email`, `estado`, `idcliente`, `idpersona`) VALUES ( '$email', '1', NULL, $idpersona);";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }

    }

    public function modificar_contacto_mail($idemail_pers,$email){
        $sql="UPDATE ca_email_pers SET email ='$email' WHERE idemail_pers=$idemail_pers";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }
    }

    public function borrar_contacto_mail($idemail_pers){
        $sql="UPDATE `cob_andina`.`ca_email_pers` SET  `estado`='0' WHERE `idemail_pers`='$idemail_pers';";
        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            echo json_encode(array('rst'=>true));
        }else{
            return array();
        }
    }

}

?>
