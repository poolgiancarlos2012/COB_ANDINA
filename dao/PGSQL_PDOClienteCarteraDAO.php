<?php

class PGSQL_PDOClienteCarteraDAO {

    public function CantidadClientesSinAsignarSinGestion(dto_cartera $dtoCartera) {

        $idcartera = $dtoCartera->getId();

        $sql = " SELECT COUNT(*) AS COUNT 
        FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 AND id_ultima_llamada = 0 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function queryListarClusterByServicio($servicio) {
        $sql = " SELECT idcluster,nombre FROM ca_cluster_usuario WHERE idservicio=" . $servicio . " and estado=1";
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generarDistribucionSinGestion(dto_cartera $dtoCartera, $operadores) {

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionMontosIguales($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $dataCodigoCliente = array();


        $sqlCodigoCliente = " SELECT cu.codigo_cliente, SUM( cu.total_deuda ) AS deuda 
        			FROM ca_cuenta cu 
					WHERE cu.idcartera = ? 
					AND cu.codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 AND id_ultima_llamada = 0 )
					GROUP BY cu.codigo_cliente ORDER BY 2 DESC ";

        $prCliente = $connection->prepare($sqlCodigoCliente);
        $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
        $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
        $prCliente->execute();
        $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);



        $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionMontosIguales", $dataCodigoCliente);

        for ($i = 0; $i < count($operadores); $i++) {
            $codigo_operador = $operadores[$i]['operador'];
            $clientes = array();
            for ($j = $i; $j < ceil(count($dataCodigoCliente) / 2); $j = $j + count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            for ($j = (count($dataCodigoCliente) - ($i + 1)); $j >= round(count($dataCodigoCliente) / 2); $j = $j - count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            if (count($clientes) > 0) {

                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $clientes) . " ) ";

                $prUpdate = $connection->prepare($sqlUpdateClienteCartera);
                $prUpdate->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prUpdate->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdate->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function RetirarTodoClienteAsignadosUsuario(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = 0, fecha_modificacion = CURRENT_TIMESTAMP 
        	WHERE idcartera = ? AND idusuario_servicio = ? ";

        $idcartera = $dtoClienteCartera->getIdCartera();
        $idusuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idusuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function CantidadClientesSinAsignarDConstante(dto_cartera $dtoCartera) {

        $idcartera = $dtoCartera->getId();

        $sql = " SELECT COUNT(*) AS COUNT 
        	FROM ca_cliente_cartera 
        	WHERE idcartera = ? AND idusuario_servicio = 0 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function DistribucionConstante(dto_cartera $dtoCartera, $idcartera_referencia, $operadores) {

        $idcartera = $dtoCartera->getId();

        function MapCodigoCliente($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

;

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        for ($i = 0; $i < count($operadores); $i++) {

            $sql = " SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = ? ";

            $pr = $connection->prepare($sql);
            $pr->bindParam(1, $idcartera_referencia, PDO::PARAM_INT);
            $pr->bindParam(2, $operadores[$i]['operador'], PDO::PARAM_INT);
            $pr->execute();
            $dataCodigoCliente = $pr->fetchAll();
            $map_codigo_cliente = array_map("MapCodigoCliente", $dataCodigoCliente);

            if (count($map_codigo_cliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?
						WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $map_codigo_cliente) . " ) ";

                $prU = $connection->prepare($sql);
                $prU->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prU->bindParam(2, $idcartera, PDO::PARAM_INT);
                if ($prU->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function CantidadClientesSinAsignarZona(dto_cartera $dtoCartera, $zona) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS COUNT
				FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente = dir.idcliente
				WHERE clicar.idcartera = ? 
				AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? ";

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        /* $pr->bindParam(2,$cartera,PDO::PARAM_INT); */
        $pr->bindParam(2, $zona, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function CantidadClientesSinAsignarCartera(dto_cartera $dtoCartera) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS COUNT
				FROM ca_cliente_cartera clicar 
				WHERE clicar.idcartera = ? 
				AND clicar.idusuario_servicio = 0 ";

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function CantidadCuentasPorCartera(dto_cartera $dtoCartera) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */

        $sql = " SELECT COUNT(idcuenta) as COUNT FROM ca_cuenta WHERE idcartera=? ";

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function consultaNextHorarioAtencion(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $h_i, $h_f, $item, $filtroEstado) {

        $sql = " SELECT clicar.idcliente_cartera,clicar.idcartera,cli.idcliente,cli.codigo,cli.idservicio,
        			clicar.estado, clicar.retiro, clicar.motivo_retiro, 
					TRIM(cli.nombre) || ' ' || TRIM(cli.paterno) || ' ' || TRIM(cli.materno) AS nombre,
					COALESCE(cli.numero_documento,'') AS numero_documento,
					COALESCE(cli.tipo_documento,'') AS tipo_documento,
					CASE WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR' ELSE ( SELECT usu.nombre || ' ' || usu.paterno || ' ' || usu.materno FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) ) END AS gestor
					FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente=cli.idcliente  
					WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ?  
					AND clicar.idcliente_cartera = ( 
					
						SELECT t2.idcliente_cartera 
						FROM
						(
						SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
						FROM (
						SELECT clicar2.idcliente_cartera
						FROM 
						( SELECT DISTINCT idcliente FROM ca_horario_atencion WHERE hora BETWEEN ? AND ? ) ha INNER JOIN 
						( SELECT idcliente, codigo  FROM ca_cliente WHERE idservicio = ?  ) cli2 INNER JOIN 
						( SELECT idcliente_cartera, codigo_cliente, idcliente  FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = ? $filtroEstado ) clicar2
						ON clicar2.idcliente = cli2.idcliente AND cli2.idcliente = ha.idcliente
						) t1, ( SELECT @rownum:=0 ) r
						) t2 WHERE t2.item = ? 
						
					) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $h_i, PDO::PARAM_STR);
        $pr->bindParam(5, $h_f, PDO::PARAM_STR);
        $pr->bindParam(6, $servicio, PDO::PARAM_INT);
        $pr->bindParam(7, $cartera, PDO::PARAM_INT);
        $pr->bindParam(8, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(9, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function save_cliente_especial(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = ? WHERE idcliente_cartera = ? ";

        $idcliente_cartera = $dtoClienteCartera->getId();
        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $idcliente_cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function generarDistribucionMontosIguales(dto_cartera $dtoCartera, $operadores, $zona) {

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionMontosIguales($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $dataCodigoCliente = array();

        if ($zona == '0') {

            $sqlCodigoCliente = " SELECT cu.codigo_cliente, SUM( cu.total_deuda ) AS deuda 
            		FROM ca_cuenta cu 
					WHERE cu.idcartera = ? 
					AND cu.codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 )
					GROUP BY cu.codigo_cliente ORDER BY 2 DESC ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);
        } else {

            /* 				$sqlCodigoCliente  = " SELECT cu.codigo_cliente, SUM(cu.total_deuda) AS 'deuda'
              FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
              ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = dir.idcliente
              WHERE clicar.idcartera = ? AND cu.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
              AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ?
              GROUP BY cu.codigo_cliente ORDER BY 2 DESC "; */

            $sqlCodigoCliente = " SELECT cu.codigo_cliente, SUM(cu.total_deuda) AS deuda
					FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
					ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = dir.idcliente
					WHERE clicar.idcartera = ? AND cu.idcartera = ? 
					AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ?
					GROUP BY cu.codigo_cliente 
					ORDER BY 2 DESC ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(3,$cartera,PDO::PARAM_INT); */
            $prCliente->bindParam(3, $zona, PDO::PARAM_STR);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);
        }

        $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionMontosIguales", $dataCodigoCliente);

        for ($i = 0; $i < count($operadores); $i++) {
            $codigo_operador = $operadores[$i]['operador'];
            $clientes = array();
            for ($j = $i; $j < ceil(count($dataCodigoCliente) / 2); $j = $j + count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            for ($j = (count($dataCodigoCliente) - ($i + 1)); $j >= round(count($dataCodigoCliente) / 2); $j = $j - count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            if (count($clientes) > 0) {

                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $clientes) . " ) ";

                $prUpdate = $connection->prepare($sqlUpdateClienteCartera);
                $prUpdate->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prUpdate->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdate->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorDepartamento(dto_direccion_ER2 $dtoDireccion, $operadores, $clientes_por_operador) {

        $cartera = $dtoDireccion->getIdCartera();
        $departamento = $dtoDireccion->getDepartamento();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionDepartamento($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $inicio = 0;

        for ($i = 0; $i < count($operadores); $i++) {

            $codigo_operador = $operadores[$i]['operador'];

            $dataCodigoCliente = array();

//				$sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
//					FROM ca_direccion WHERE idcartera = ? AND TRIM(departamento) = ? LIMIT ?, ? ";

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente
					FROM ca_direccion WHERE idcartera = ? AND TRIM(departamento) = ? 
					AND codigo_cliente IN 
					( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0  ) 
					LIMIT $clientes_por_operador OFFSET $inicio ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $departamento, PDO::PARAM_STR);
            /*             * ***** */
            $prCliente->bindParam(3, $cartera, PDO::PARAM_INT);
            /*             * ***** */
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionDepartamento", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $MapDataCodigoCliente) . " ) ";

                $prDisDepartamento = $connection->prepare($sql);
                $prDisDepartamento->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prDisDepartamento->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisDepartamento->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorTramoModoSeguimiento(dto_detalle_cuenta $dtoDetalleCuenta, $operadores) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $sqlCantidadClientes = " SELECT COUNT( DISTINCT codigo_cliente ) AS COUNT
				FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
				AND codigo_cliente ";

        $pr = $connection->prepare($sqlCantidadClientes);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $tramo, PDO::PARAM_STR);
        $pr->execute();
        $dataCantidadCliente = $pr->fetchAll(PDO::FETCH_ASSOC);
        $clientes_disponibles = (int) $dataCantidadCliente[0]['COUNT'];
        $clientes_por_operador = ceil($clientes_disponibles / count($operadores));

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
					FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
					AND codigo_cliente 
					LIMIT $clientes_por_operador OFFSET $inicio ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $tramo, PDO::PARAM_STR);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
            $inicio += $clientes_por_operador;
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorTramo(dto_detalle_cuenta $dtoDetalleCuenta, $operadores, $clientes_por_operador) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
					FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
					AND codigo_cliente IN 
					( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0  ) 
					LIMIT $clientes_por_operador OFFSET $inicio ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $tramo, PDO::PARAM_STR);
            $prCliente->bindParam(3, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }

    public function queryDistribucionAutomatica(dto_servicio $dtoServicio, dto_cartera $dtoCartera) {
//			$sql=" SELECT COALESCE(SUM(IF(idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar',
//				COALESCE(SUM(IF(idusuario_servicio<>0,1,0)),0) AS 'clientes_asignados',(
//				SELECT COUNT(*) FROM ca_usuario_servicio WHERE idservicio=? AND idtipo_usuario IN (1,2) ) AS 'cantidad_operadores'
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera 
//				WHERE car.idcampania=? ";

        $sql = " SELECT 
        		COALESCE( SUM( CASE WHEN idusuario_servicio=0 THEN 1 ELSE 0 END ),0 ) AS clientes_sin_asignar,
				COALESCE( SUM( CASE WHEN idusuario_servicio<>0 THEN 1 ELSE 0 END ),0 ) AS clientes_asignados,
				( SELECT COUNT(*) FROM ca_usuario_servicio WHERE idservicio=? AND idtipo_usuario IN (2,3) AND estado=1 ) AS cantidad_operadores 
				FROM ca_cliente_cartera WHERE idcartera=? AND estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $servicio = $dtoServicio->getId();
        $cartera = $dtoCartera->getid();

        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $cartera);
        /*         * */
        //$pr->bindParam(3,$servicio);
        /*         * * */
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryDataDistribucionPorOperador(dto_cliente_cartera $dtoClienteCartera) {
        $cartera = $dtoClienteCartera->getIdCartera();
        $sql = " SELECT 
        		COALESCE( COUNT(*),0 ) AS cliente_asignados,
				COALESCE( SUM( CASE WHEN id_ultima_llamada =0 THEN 1 ELSE 0 END ),0) AS clientes_sin_gestionar,
				COALESCE( SUM( CASE WHEN id_ultima_llamada <>0 THEN 1 ELSE 0 END ),0 ) AS clientes_gestionados
				FROM ca_cliente_cartera
				WHERE idusuario_servicio = ? AND idcartera IN (" . $cartera . ") ";

        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio, PDO::PARAM_INT);
        //$pr->bindParam(2,$cartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            //////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //////$connection->rollBack();
            return array();
        }
    }

    public function queryClientesByOperador(dto_cartera $dtoCartera, dto_servicio $dtoServicio) {
        //$sql=" SELECT usu.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS operador,
//				COALESCE( (SELECT SUM(IF(clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0,1,0)) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio ),0) AS 'clientes_sin_gestionar',
//				COALESCE( (SELECT SUM(IF(clicar.id_ultima_llamada<>0 OR clicar.id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio ),0) AS 'clientes_gestionados',
//				( SELECT COUNT(*) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'clientes_asignados'
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
//				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) ";
//			$sql=" SELECT usu.idusuario,ususer.idusuario_servicio,UPPER(CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno)) AS operador,
//				COALESCE( (SELECT SUM(IF(id_ultima_llamada=0 AND id_ultima_visita=0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
//				COALESCE( (SELECT SUM(IF(id_ultima_llamada<>0 OR id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
//				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
//				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ";

        $sql = " SELECT 
        		usu.idusuario,ususer.idusuario_servicio,UPPER(usu.paterno || ' ' || usu.materno || ' ' || usu.nombre) AS operador,
				COALESCE( (SELECT SUM( CASE WHEN id_ultima_llamada=0 THEN 1 ELSE 0 END ) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS clientes_sin_gestionar,
				COALESCE( (SELECT SUM( CASE WHEN id_ultima_llamada<>0 THEN 1 ELSE 0 END ) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS clientes_gestionados,
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS clientes_asignados
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ORDER BY 3 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $cartera = $dtoCartera->getId();
        $servicio = $dtoServicio->getId();

        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $cartera);
        $pr->bindParam(4, $servicio);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesByOperadorPorCluster(dto_cartera $dtoCartera, dto_servicio $dtoServicio, $idcluster) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoServicio->getId();

        if ($idcluster == 0) {
            $sqlfill = "WHERE ususer.idservicio=" . $servicio . " AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1  ORDER BY 3";
        } else {
            $sqlfill = " INNER JOIN ca_usuario_servicio_cluster ususerclu ON ususer.idusuario_servicio=ususerclu.idusuario_servicio
				WHERE ususer.idservicio=" . $servicio . " AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 AND ususerclu.idcluster=" . $idcluster . " and ususerclu.estado=1 ORDER BY 3";
        }

        $sql = " SELECT 
        		usu.idusuario,ususer.idusuario_servicio,
        		UPPER(usu.paterno || ' ' || usu.materno || ' ' || usu.nombre) AS operador,
				COALESCE( (SELECT SUM(CASE WHEN id_ultima_llamada=0 THEN 1 ELSE 0 END ) FROM ca_cliente_cartera  WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS clientes_sin_gestionar,
				COALESCE( (SELECT SUM(CASE WHEN id_ultima_llamada<>0 THEN 1 ELSE 0 END) FROM ca_cliente_cartera  WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS clientes_gestionados,
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS clientes_asignados 
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario " . $sqlfill;

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesSinAsignar(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        //$servicio=$dtoCampania->getIdServicio();
        //$sql=" SELECT COALESCE(SUM(IF(clicar.idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar' FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car 
//				ON car.idcartera=clicar.idcartera WHERE car.idcampania=:campania ";

        $sql = " SELECT COALESCE(SUM(CASE WHEN idusuario_servicio=0 THEN 1 ELSE 0 END),0) AS clientes_sin_asignar 
				FROM ca_cliente_cartera 
				WHERE idcartera=:cartera AND estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesSinPago(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " 
        	SELECT COUNT(DISTINTC data.idcliente_cartera ) AS clientes_sin_pago 
        	FROM
			(
			SELECT 
			t1.idcliente_cartera, t1.idcartera, TRUNCATE( t1.total_deuda,2) AS EXIGIBLE, 
			TRUNC( t1.monto_pagado,2) AS PAGO, 
			TRUNC( ( t1.total_deuda + COALESCE(t1.total_comision,0) - COALESCE(t1.monto_pagado,0) ),2 ) AS SALDO,
			CASE 
			WHEN COALESCE(t1.monto_pagado,0)=0 THEN 'SIN PAGO' 
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  <= t1.monto_pagado THEN 'ACANCELADO' 
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  != t1.monto_pagado THEN 'AMORTIZADO'
			ELSE ''
			END AS status 
			FROM ca_cuenta t1 
			WHERE idcartera=:cartera AND retirado=0
			ORDER BY 1 DESC, 6 DESC 
			) data
			WHERE data.status IN ('SIN PAGO') ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesAmortizado(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " 
        	SELECT
        	COUNT(DISTINCT data.idcliente_cartera ) AS clientes_amortizado 
        	FROM
			(
			SELECT	t1.idcliente_cartera, t1.idcartera, 
			TRUNC( t1.total_deuda,2) AS EXIGIBLE, 
			TRUNC( t1.monto_pagado,2) AS PAGO, 
			TRUNC( ( t1.total_deuda + COALESCE(t1.total_comision,0) - COALESCE(t1.monto_pagado,0) ),2 ) AS SALDO ,
			CASE
			WHEN COALESCE(t1.monto_pagado,0)=0 THEN 'SIN PAGO'
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  <= t1.monto_pagado THEN 'ACANCELADO' 
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  != t1.monto_pagado THEN 'AMORTIZADO' 
			ELSE '' 
			END AS status 
			FROM ca_cuenta t1 
			WHERE idcartera=:cartera AND retirado=0
			ORDER BY 1 DESC, 6 DESC 
			) data
			WHERE data.status IN ('AMORTIZADO') ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryNumeroCliCar(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " SELECT COUNT(*) AS clientes 
        FROM ca_cliente_cartera WHERE idcartera=:cartera ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function generarDistribucionAutomatica(dto_servicio $dtoServicio, dto_cartera $dtoCartera) {

        $factoryConnection = FactoryConnection::create('postgres_pdoi');
        $connection = $factoryConnection->getConnection();

        $dao = DAOFactory::getDAOUsuarioServicio();

        $count = 0;

        $data = $this->queryDistribucionAutomatica($dtoServicio, $dtoCartera);

        $clientesXOperador = ceil($data[0]['clientes_sin_asignar'] / $data[0]['cantidad_operadores']);
        $operadores = $dao->queryIdOperadorXServicio($dtoServicio);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        $connection->autocommit(false);

        for ($i = 0; $i < count($operadores); $i++):

            $arrayId = $this->queryReturnIdClienteCartera($dtoCartera, $count, $clientesXOperador);

            if (count($arrayId) > 0) :

                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $count+=$clientesXOperador;

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=? WHERE idcliente_cartera IN ( $ids ) ";

                $usuario_servicio = $operadores[$i]['idusuario_servicio'];

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario_servicio);
                $rst = $pr->execute();
                //$rst=$pr->execute(array(':usuario'=>$usuario));
                if (!$rst):
                    ////$connection->rollBack();
                    return false;
                    exit();
                endif;

            endif;

        endfor;

        ////$connection->commit();
        return true;
    }

    public function queryReturnIdClienteCartera(dto_cartera $dto, $inicio, $cantidad) {
        //$sql=" SELECT clicar.idcliente_cartera 
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car 
//				ON car.idcartera=clicar.idcartera 
//				WHERE car.idcampania=? AND clicar.idusuario_servicio=0 
//				ORDER BY clicar.idcliente_cartera 
//				LIMIT $inicio, $cantidad ";

        $sql = " SELECT idcliente_cartera FROM ca_cliente_cartera 
				WHERE idcartera=? AND idusuario_servicio=0 AND estado=1 
				ORDER BY idcliente_cartera LIMIT $cantidad OFFSET $inicio ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryReturnIdClienteCarteraSinPago(dto_cartera $dto, $inicio, $cantidad) {
        $sql = " 
        SELECT DISTINCT( data.idcliente_cartera ) 
        FROM
		(
		SELECT	t1.idcliente_cartera, t1.idcartera, 
		TRUNC( t1.total_deuda,2) AS EXIGIBLE, 
		TRUNC( t1.monto_pagado,2) AS PAGO, 
		TRUNC( ( t1.total_deuda + COALESCE(t1.total_comision,0) - COALESCE(t1.monto_pagado,0) ),2 ) AS SALDO ,
		CASE
		WHEN COALESCE(t1.monto_pagado,0)=0 THEN 'SIN PAGO'
		WHEN IF( COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  <= t1.monto_pagado THEN 'ACANCELADO' 
		WHEN IF( COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  != t1.monto_pagado THEN 'AMORTIZADO'
		ELSE '' 
		END AS status 
		FROM ca_cuenta t1 
		WHERE idcartera=? and retirado=0
		ORDER BY 1 DESC, 6 DESC 
		) data
		WHERE data.status IN ('SIN PAGO') LIMIT $cantidad OFFSET $inicio ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryReturnIdClienteCarteraAmortizado(dto_cartera $dto, $inicio, $cantidad) {
    	
        $sql = " 
        	SELECT DISTINCT(data.idcliente_cartera) 
        	FROM
			(
			SELECT t1.idcliente_cartera, t1.idcartera, 
			TRUNC( t1.total_deuda,2) AS EXIGIBLE, 
			TRUNC( t1.monto_pagado,2) AS PAGO, 
			TRUNC( ( t1.total_deuda + COALESCE(t1.total_comision,0) - COALESCE(t1.monto_pagado,0) ),2 ) AS SALDO ,
			CASE
			WHEN COALESCE(t1.monto_pagado,0)=0 THEN 'SIN PAGO'
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  <= t1.monto_pagado THEN 'ACANCELADO'
			WHEN COALESCE(t1.monto_pagado,0)!=0 AND ( COALESCE(t1.total_deuda,0) + COALESCE(t1.total_comision,0) )  != t1.monto_pagado THEN 'AMORTIZADO'
			ELSE '' 
			END AS 'status' 
			FROM ca_cuenta t1 
			WHERE idcartera=? AND retirado=0
			ORDER BY 1 DESC, 6 DESC 
			) data
			WHERE data.status IN ('AMORTIZADO') LIMIT $cantidad OFFSET $inicio ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAllClienteSinGestionarXUsuario(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=0 WHERE id_ultima_llamada=0 AND id_ultima_visita=0 
				AND idusuario_servicio=? AND idcartera = ? AND estado=1 ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $cartera = $dtoCartera->getId();
        $usuario_servicio = $dtoUsuarioServicio->getId();

        $pr->bindParam(1, $usuario_servicio);
        $pr->bindParam(2, $cartera);

        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function generarDistribucionManual(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('postgres_pdoi');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        $connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCartera($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                $inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionSinPago(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('postgres_pdoi');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        $connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCarteraSinPago($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?, fecha_modificacion = CURRENT_TIMESTAMP WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                $inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionAmortizado(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('postgres_pdoi');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        $connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCarteraAmortizado($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?, fecha_modificacion = CURRENT_TIMESTAMP WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                $inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarTraspasoCartera($idusuario_servicio_DE, $idusuario_servicio_PARA, $idcartera) {

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $idcliente_cartera = '';

        $sql_idclicar = "SELECT idcliente_cartera || ',' AS idcliente_cartera FROM ca_cliente_cartera WHERE idcartera=" . $idcartera . " AND idusuario_servicio=" . $idusuario_servicio_DE . " AND estado=1";
        $pr_idclicar = $connection->prepare($sql_idclicar);
        if ($pr_idclicar->execute()) {
            while ($row = $pr_idclicar->fetch(PDO::FETCH_ASSOC)) {
                foreach ($row as $index => $value) {
                    $idcliente_cartera.=$value;
                }
            }
            $idcliente_cartera = substr($idcliente_cartera, 0, (strlen($idcliente_cartera) - 1));

            ////$connection->beginTransaction();
            $sqlUpdClicar = "UPDATE ca_cliente_cartera SET idusuario_servicio=" . $idusuario_servicio_PARA . ", fecha_modificacion = CURRENT_TIMESTAMP WHERE idcliente_cartera IN (" . $idcliente_cartera . ")";
            $prUpdClicar = $connection->prepare($sqlUpdClicar);
            if ($prUpdClicar->execute()) {
                ////$connection->commit();
                return true;
            } else {
                ////$connection->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    public function deleteClientesIngresadosSinGestionar(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, $cantidad) {
        $sql = " SELECT clicar.idcliente_cartera 
				FROM ca_cliente_cartera clicar 
				WHERE clicar.idcartera=? AND clicar.idusuario_servicio=? AND clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0 AND clicar.estado=1 
				ORDER BY clicar.idcliente_cartera LIMIT $cantidad OFFSET 0 ";

        $usuario = $dtoUsuarioServicio->getId();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $usuario);
        $pr->execute();
        $dataIds = $pr->fetchAll(PDO::FETCH_ASSOC);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $dataIds));

        ////$connection->beginTransaction();

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=0 WHERE idcliente_cartera IN ( $ids ) ";

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function consultaNext(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $item, $filtro_estado) {

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				COALESCE(cli.numero_documento,'') AS 'numero_documento',
//				COALESCE(cli.tipo_documento,'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
//				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
//				WHERE idcartera=? AND idusuario_servicio=? AND estado=1 AND idcliente_cartera > ? 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.idcartera, cli.codigo,clicar.estado, clicar.retiro, clicar.motivo_retiro, 
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno)) AS nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT usu.nombre || ' ' || usu.paterno || ' ' || usu.materno FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=?  $filtro_estado 
				AND clicar.idcliente_cartera=( 
					
					SELECT t1.idcliente_cartera
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', clicar.idcliente_cartera
					FROM ca_cliente_cartera clicar,( SELECT @rownum:=0 ) r
					WHERE idcartera = ? AND idusuario_servicio = ? $filtro_estado 
					) t1
					WHERE t1.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * *** */
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $item, PDO::PARAM_INT);
        //$pr->bindParam(6,$ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $item, $filtro_estado) {

//			$sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				COALESCE(TRIM(cli.numero_documento),'') AS 'numero_documento',
//				COALESCE(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
//				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
//				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND clicar2.idcliente_cartera > ? AND detcun.tramo = ? 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,cli.codigo, 
        		clicar.idcartera, clicar.estado, clicar.retiro, clicar.motivo_retiro,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) as nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT usu.nombre || ' ' || usu.paterno || ' ' || usu.materno FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
					
					SELECT t2.idcliente_cartera 
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
					FROM
					(
					SELECT DISTINCT clicar.idcliente_cartera
					FROM ca_cliente_cartera clicar INNER JOIN ca_detalle_cuenta detcun 
					ON detcun.codigo_cliente = clicar.codigo_cliente AND detcun.idcartera = clicar.idcartera
					WHERE clicar.idcartera = ? AND clicar.idusuario_servicio = ?  
					AND clicar.estado = 1  AND detcun.tramo = ? $filtro_estado 
					) t1 , ( SELECT @rownum:=0 ) r
					) t2 WHERE t2.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        //$pr->bindParam(6,$ClienteCartera,PDO::PARAM_INT);
        $pr->bindParam(6, $tramo, PDO::PARAM_STR);
        $pr->bindParam(7, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $sord, $item, $filtro_estado) {

//			$sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				COALESCE(TRIM(cli.numero_documento),'') AS 'numero_documento',
//				COALESCE(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//					SELECT clic.idcliente_cartera
//					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
//					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? AND clic.idcliente_cartera > ?
//					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.idcartera, cli.codigo, 
        		clicar.estado, clicar.retiro, clicar.motivo_retiro, 
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno)) as nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=? $filtro_estado
				AND clicar.idcliente_cartera=( 
				
					SELECT t2.idcliente_cartera 
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
					FROM
					(
					SELECT DISTINCT clicar.idcliente_cartera 
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ?  $filtro_estado 
					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord 
					) t1 , ( SELECT @rownum:=0 ) r
					) t2 WHERE t2.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        //$pr->bindParam(7,$ClienteCartera,PDO::PARAM_INT);
        $pr->bindParam(7, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno)) as nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera=? AND estado=1 AND idcliente_cartera > ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND clicar2.idcliente_cartera > ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(5, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBack(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' ||  cli.materno) AS nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo
				WHERE cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MAX(idcliente_cartera) FROM ca_cliente_cartera WHERE idcartera=? 
				AND idusuario_servicio=? AND estado=1 AND idcliente_cartera < ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ***** */
        $pr->bindParam(1, $servicio);
        /*         * ***** */
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $UsuarioServicio);
        $pr->bindParam(4, $cartera);
        $pr->bindParam(5, $UsuarioServicio);
        $pr->bindParam(6, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND clicar2.idcliente_cartera < ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(7, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $sord) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
					SELECT clic.idcliente_cartera
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? AND clic.idcliente_cartera < ?
					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(7, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo
				WHERE cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MAX(idcliente_cartera) FROM ca_cliente_cartera WHERE idcartera=? AND estado=1 AND idcliente_cartera < ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND clicar2.idcliente_cartera < ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(5, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestion(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera=? AND idusuario_servicio=?  AND estado=1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$servicio);
        /*         * ***** */
        $pr->bindParam(1, $servicio);
        /*         * **** */
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $UsuarioServicio);
        $pr->bindParam(4, $cartera);
        $pr->bindParam(5, $UsuarioServicio);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND detcun.tramo = ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, $sord) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MAX(clic.idcliente_cartera)
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR' 
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END  AS gestor 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera= ? AND clicar.estado=1  
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera = ? AND estado=1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(cli.nombre || ' ' || cli.paterno || ' ' || cli.materno) AS nombre,
				COALESCE(TRIM(cli.numero_documento),'') AS numero_documento,
				COALESCE(TRIM(cli.tipo_documento),'') AS tipo_documento,
				CASE 
				WHEN clicar.idusuario_servicio = 0 THEN 'SIN GESTOR'
				ELSE ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) 
				END AS gestor
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND detcun.tramo = ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function SearchClientByDni(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
//			$sql=" SELECT clicar.idcliente_cartera,clicar.idcliente,cli.codigo,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',COALESCE(cli.numero_documento,'') AS 'numero_documento',
//				COALESCE(cli.tipo_documento,'') AS 'tipo_documento' FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.idcliente=clicar.idcliente 
//				WHERE TRIM(cli.numero_documento)=? AND clicar.idcartera=? AND clicar.idusuario_servicio=? AND cli.estado=1 AND clicar.estado=1 ";
//			$sql=" SELECT clicar.idcliente_cartera,clicar.idcliente,cli.codigo,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',COALESCE(cli.numero_documento,'') AS 'numero_documento',
//				COALESCE(cli.tipo_documento,'') AS 'tipo_documento' FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
//				ON cli.codigo=clicar.codigo_cliente 
//				WHERE cli.idservicio = ? AND TRIM(cli.numero_documento)=? AND clicar.idcartera=? AND clicar.idusuario_servicio=? AND cli.estado=1 AND clicar.estado=1 ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				cli.nombre || ' ' || cli.paterno || ' ' || cli.materno AS nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento 
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli 
				ON cli.codigo=clicar.codigo_cliente 
				WHERE cli.idservicio = ? AND TRIM(cli.numero_documento)=? AND clicar.idcartera=? AND cli.estado=1 AND clicar.estado=1 ";


        $NumeroDocumento = $dtoCliente->getNumeroDocumento();
        $cartera = $dtoCartera->getId();
        /*         * ****** */
        $servicio = $dtoCliente->getIdServicio();
        /*         * ****** */
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ******* */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * ******* */
        $pr->bindParam(2, $NumeroDocumento, PDO::PARAM_STR);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        /*         * *** */
        //$pr->bindParam(4,$UsuarioServicio,PDO::PARAM_INT);
        /*         * *** */
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function SearchClientByCode(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
//			$sql=" SELECT clicar.idcliente_cartera,clicar.idcliente,cli.codigo,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',COALESCE(cli.numero_documento,'') AS 'numero_documento',
//				COALESCE(cli.tipo_documento,'') AS 'tipo_documento' FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli
//				ON cli.idcliente=clicar.idcliente 
//				WHERE TRIM(cli.codigo) = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ? AND cli.estado=1 AND clicar.estado=1 "; 

        /* 			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
          CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',COALESCE(cli.numero_documento,'') AS 'numero_documento',
          COALESCE(cli.tipo_documento,'') AS 'tipo_documento' FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli
          ON cli.codigo=clicar.codigo_cliente
          WHERE cli.idservicio = ? AND TRIM(cli.codigo) = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ? AND cli.estado=1 AND clicar.estado=1 "; */

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				cli.nombre || ' ' || cli.paterno || ' ' || cli.materno AS nombre,
				COALESCE(cli.numero_documento,'') AS numero_documento,
				COALESCE(cli.tipo_documento,'') AS tipo_documento 
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli
				ON cli.codigo=clicar.codigo_cliente  
				WHERE cli.idservicio = ? AND TRIM(cli.codigo) = ? AND clicar.idcartera = ? AND cli.estado=1 AND clicar.estado=1 ";


        $codigo = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();
        /*         * ****** */
        $servicio = $dtoCliente->getIdServicio();
        /*         * ****** */
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ***** */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * ***** */
        $pr->bindParam(2, $codigo, PDO::PARAM_STR);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        /*         * ****** */
        //$pr->bindParam(4,$UsuarioServicio,PDO::PARAM_INT);
        /*         * ****** */
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function updateUltimaVisita(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?, usuario_modificacion = ?, fecha_modificacion = CURRENT_TIMESTAMP WHERE idcliente_cartera = ?  ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $UsuarioModificacion = $dtoClienteCartera->getUsuarioModificacion();
        $IdUltimaVisita = $dtoClienteCartera->getIdUltimaVisita();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $IdUltimaVisita);
        $pr->bindParam(2, $UsuarioModificacion);
        $pr->bindParam(3, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function updateUltimaLlamada(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = CURRENT_TIMESTAMP 
        	WHERE idcliente_cartera = ?  ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $UsuarioModificacion = $dtoClienteCartera->getUsuarioModificacion();
        $IdUltimaLlamada = $dtoClienteCartera->getIdUltimaLlamada();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $IdUltimaLlamada);
        $pr->bindParam(2, $UsuarioModificacion);
        $pr->bindParam(3, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function updateMultiId($ids, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? WHERE idcliente_cartera IN ( $ids ) ";

        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function executeSelectString($sql) {
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
