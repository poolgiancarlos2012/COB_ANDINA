<?php

class PGSQL_PDODetalleCuentaDAO {

    public function countClientesDisponiblesPorTramo(dto_detalle_cuenta $dtoDetalleCuenta) {

        $sql = " SELECT COUNT( DISTINCT codigo_cliente ) AS 'COUNT'
				FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
				AND codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 ) ";

        $idcartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $tramo, PDO::PARAM_STR);
        $pr->bindParam(3, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array('COUNT' => 0);
        }
    }

    public function queryTotalByCuenta(dto_cliente_cartera $dtoClienteCartera, dto_cliente $dtoCliente) {
//			$sql=" SELECT  IFNULL(SUM(detcuen.total_deuda),'') AS 'total_deuda',IFNULL(SUM(detcuen.total_deuda_soles),'') AS 'total_deuda_soles',
//				IFNULL(SUM(detcuen.total_deuda_dolares),'') AS 'total_deuda_dolares',IFNULL(SUM(detcuen.monto_mora),'') AS 'monto_mora',
//				IFNULL(SUM(detcuen.monto_mora_soles),'') AS 'monto_mora_soles',IFNULL(SUM(detcuen.monto_mora_dolares),'') AS 'monto_mora_dolares',
//				IFNULL(SUM(detcuen.saldo_capital),'') AS 'saldo_capital',IFNULL(SUM(detcuen.saldo_capital_soles),'') AS 'saldo_capital_soles',
//				IFNULL(SUM(detcuen.saldo_capital_dolares),'') AS 'saldo_capital_dolares'
//				FROM ca_cuenta cuen INNER JOIN ca_detalle_cuenta detcuen 
//				ON detcuen.idcuenta=cuen.idcuenta WHERE cuen.idcliente_cartera=? ";
        //$sql=" SELECT  
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda),2),'') AS 'total_deuda',
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda_soles),2),'') AS 'total_deuda_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda_dolares),2),'') AS 'total_deuda_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora),2),'') AS 'monto_mora',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora_soles),2),'') AS 'monto_mora_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora_dolares),2),'') AS 'monto_mora_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital),2),'') AS 'saldo_capital',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital_soles),2),'') AS 'saldo_capital_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital_dolares),2),'') AS 'saldo_capital_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision),2),'') AS 'comision_general',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda),2),'') AS 'comision_total_deuda',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_soles),2),'') AS 'comision_total_deuda_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_dolares),2),'') AS 'comision_total_deuda_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora),2),'') AS 'comision_monto_mora',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_soles),2),'') AS 'comision_monto_mora_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_dolares),2),'') AS 'comision_monto_mora_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital),2),'') AS 'comision_saldo_capital',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_soles),2),'') AS 'comision_saldo_capital_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_dolares),2),'') AS 'comision_saldo_capital_dolares'
//				FROM ca_cuenta cuen INNER JOIN ca_detalle_cuenta detcuen 
//				ON detcuen.idcuenta=cuen.idcuenta WHERE cuen.idcliente_cartera=? ";
//			$sql=" SELECT  
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda),2),'') AS 'total_deuda',
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda_soles),2),'') AS 'total_deuda_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.total_deuda_dolares),2),'') AS 'total_deuda_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora),2),'') AS 'monto_mora',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora_soles),2),'') AS 'monto_mora_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.monto_mora_dolares),2),'') AS 'monto_mora_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital),2),'') AS 'saldo_capital',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital_soles),2),'') AS 'saldo_capital_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.saldo_capital_dolares),2),'') AS 'saldo_capital_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision),2),'') AS 'comision_general',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda),2),'') AS 'comision_total_deuda',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_soles),2),'') AS 'comision_total_deuda_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_dolares),2),'') AS 'comision_total_deuda_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora),2),'') AS 'comision_monto_mora',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_soles),2),'') AS 'comision_monto_mora_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_dolares),2),'') AS 'comision_monto_mora_dolares',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital),2),'') AS 'comision_saldo_capital',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_soles),2),'') AS 'comision_saldo_capital_soles',
//				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_dolares),2),'') AS 'comision_saldo_capital_dolares'
//				FROM ca_cuenta cuen INNER JOIN ca_detalle_cuenta detcuen 
//				ON detcuen.codigo_cliente=cuen.codigo_cliente AND detcuen.numero_cuenta=cuen.numero_cuenta 
//				WHERE cuen.codigo_cliente = ? AND detcuen.idcartera = ? AND cuen.idcartera = ? ";

        $sql = " SELECT cu.numero_cuenta,IFNULL(cu.moneda,'-') AS 'moneda',
				TRUNCATE(cu.total_deuda,2) AS 'total_deuda',
				IFNULL( ( SELECT TRUNCATE( SUM( comision ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision',
				IFNULL( ( SELECT TRUNCATE( SUM( total_deuda_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'total_deuda_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( total_deuda_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'total_deuda_dolares',
				IFNULL( ( SELECT TRUNCATE( SUM( monto_mora ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'monto_mora',
				IFNULL( ( SELECT TRUNCATE( SUM( monto_mora_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'monto_mora_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( monto_mora_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'monto_mora_dolares',
				IFNULL( ( SELECT TRUNCATE( SUM( saldo_capital ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'saldo_capital',
				IFNULL( ( SELECT TRUNCATE( SUM( saldo_capital_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'saldo_capital_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( saldo_capital_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'saldo_capital_dolares',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_total_deuda ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_total_deuda',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_total_deuda_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_total_deuda_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_total_deuda_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_total_deuda_dolares',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_monto_mora ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_monto_mora',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_monto_mora_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_monto_mora_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_monto_mora_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_monto_mora_dolares',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_saldo_capital ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_saldo_capital',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_saldo_capital_soles ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_saldo_capital_soles',
				IFNULL( ( SELECT TRUNCATE( SUM( comision_saldo_capital_dolares ),2 ) FROM ca_detalle_cuenta WHERE idcartera = cu.idcartera AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda ),'' ) AS 'comision_saldo_capital_dolares'
				FROM ca_cuenta cu 
				WHERE cu.idcartera = ? AND cu.codigo_cliente = ? AND estado = 1 ";

        $codigo_cliente = $dtoCliente->getCodigo();
        $cartera = $dtoClienteCartera->getIdCartera();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        /* $pr->bindParam(1,$codigo_cliente);
          $pr->bindParam(2,$cartera);
          $pr->bindParam(3,$cartera); */
        /*         * **** */
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $codigo_cliente, PDO::PARAM_STR);
        /*         * **** */
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryTotalComision(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT  
				IFNULL(TRUNCATE(SUM(detcuen.comision),2),'') AS 'comision_general',
				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda),2),'') AS 'comision_total_deuda',
				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_soles),2),'') AS 'comision_total_deuda_soles',
				IFNULL(TRUNCATE(SUM(detcuen.comision_total_deuda_dolares),2),'') AS 'comision_total_deuda_dolares',
				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora),2),'') AS 'comision_monto_mora',
				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_soles),2),'') AS 'comision_monto_mora_soles',
				IFNULL(TRUNCATE(SUM(detcuen.comision_monto_mora_dolares),2),'') AS 'comision_monto_mora_dolares',
				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital),2),'') AS 'comision_saldo_capital',
				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_soles),2),'') AS 'comision_saldo_capital_soles',
				IFNULL(TRUNCATE(SUM(detcuen.comision_saldo_capital_dolares),2),'') AS 'comision_saldo_capital_dolares'
				FROM ca_cuenta cuen INNER JOIN ca_detalle_cuenta detcuen 
				ON detcuen.idcuenta=cuen.idcuenta WHERE cuen.idcliente_cartera=? ";

        $cliente_cartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cliente_cartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryTramo(dto_cartera $dtoCartera) {
        $cartera = $dtoCartera->getId();
        $sql = " SELECT TRIM(tramo) AS tramo,porcentaje_comision FROM ca_detalle_cuenta 
			WHERE idcartera IN (" . $cartera . ") AND ISNULL(tramo)=0 AND TRIM(tramo)!='' GROUP BY TRIM(tramo) ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$cartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function asignar_comision($data, dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdoi');
        $connection = $factoryConnection->getConnection();

        $connection->autocommit(false);

        for ($i = 0; $i < count($data); $i++) {

            $sql = " UPDATE ca_detalle_cuenta SET porcentaje_comision = " . $data[$i]['porcentaje'] . ", 
				comision_total_deuda = (total_deuda * " . $data[$i]['porcentaje'] . ")/100,
				comision_total_deuda_soles = (total_deuda_soles * " . $data[$i]['porcentaje'] . " )/100 ,
				comision_total_deuda_dolares = (total_deuda_dolares * " . $data[$i]['porcentaje'] . " )/100, 
				comision_monto_mora = (monto_mora * " . $data[$i]['porcentaje'] . " )/100,
				comision_monto_mora_soles = (monto_mora_soles * " . $data[$i]['porcentaje'] . " )/100,
				comision_monto_mora_dolares = ( monto_mora_dolares * " . $data[$i]['porcentaje'] . " )/100,
				comision_saldo_capital = ( saldo_capital * " . $data[$i]['porcentaje'] . " )/100,
				comision_saldo_capital_soles = ( saldo_capital_soles * " . $data[$i]['porcentaje'] . " )/100,
				comision_saldo_capital_dolares = ( saldo_capital_dolares * " . $data[$i]['porcentaje'] . " )/100 
				WHERE idcartera = $cartera AND TRIM(tramo) = '" . $data[$i]['tramo'] . "' ";


            if ($connection->query($sql)) {
                
            } else {
                //$connection->rollBack();
                return false;
                exit();
            }
        }

        //$connection->commit();
        return true;
    }

    public function asignar_comision_tramo_servicio($data, dto_cartera $dtoCartera, dto_tramo $dtoTramo) {

        $cartera = $dtoCartera->getId();

        $idservicio = $dtoTramo->getIdServicio();
        $usuario_modificacion = $dtoTramo->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        for ($i = 0; $i < count($data); $i++) {

            $sql = " UPDATE ca_tramo SET porcentaje_comision = ?, fecha_modificacion = NOW(), usuario_modificacion = ? 
				WHERE idservicio = ? AND tipo = 'TRAMO' AND tramo = ?  ; ";

            $pr = $connection->prepare($sql);
            $pr->bindParam(1, $data[$i]['porcentaje'], PDO::PARAM_INT);
            $pr->bindParam(2, $usuario_modificacion, PDO::PARAM_INT);
            $pr->bindParam(3, $idservicio, PDO::PARAM_INT);
            $pr->bindParam(4, $data[$i]['tramo'], PDO::PARAM_STR);
            if ($pr->execute()) {
                
            } else {
                //$connection->rollBack();
                return false;
                exit();
            }
        }

        for ($i = 0; $i < count($data); $i++) {

            $sqlUpdateOperacion = " UPDATE ca_detalle_cuenta SET tipo_comision = 'TRAMO',porcentaje_comision = " . $data[$i]['porcentaje'] . " ,
				comision_total_deuda = ( total_deuda * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_total_deuda_soles = ( total_deuda_soles * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_total_deuda_dolares = ( total_deuda_dolares * " . $data[$i]['porcentaje'] . " )/100 ,
				comision_monto_mora = ( monto_mora * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_monto_mora_soles = ( monto_mora_soles * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_monto_mora_dolares = ( monto_mora_dolares * " . $data[$i]['porcentaje'] . " )/100 ,
				comision_saldo_capital = ( saldo_capital * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_saldo_capital_soles = ( saldo_capital_soles * " . $data[$i]['porcentaje'] . " )/100 , 
				comision_saldo_capital_dolares = ( saldo_capital_dolares * " . $data[$i]['porcentaje'] . " )/100
				WHERE idcartera = ? AND tramo = ? ";

            $prUpdateOperacion = $connection->prepare($sqlUpdateOperacion);
            $prUpdateOperacion->bindParam(1, $cartera, PDO::PARAM_INT);
            $prUpdateOperacion->bindParam(2, $data[$i]['tramo'], PDO::PARAM_STR);
            if ($prUpdateOperacion->execute()) {
                
            } else {
                //$connection->rollBack();
                return false;
                exit();
            }
        }

        $sqlUpdateCuenta = " UPDATE ca_cuenta cu 
			SET total_comision = ( SELECT SUM(comision_total_deuda) FROM ca_detalle_cuenta WHERE idcartera = ? AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda GROUP BY numero_cuenta )
			WHERE idcartera = ? ";

        $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
        $prUpdateCuenta->bindParam(1, $cartera, PDO::PARAM_INT);
        $prUpdateCuenta->bindParam(2, $cartera, PDO::PARAM_INT);
        if ($prUpdateCuenta->execute()) {
            
        } else {
            //$connection->rollBack();
            return false;
            exit();
        }

        //$connection->commit();
        return true;
    }

    public function asignar_comision_generico_servicio(dto_tramo $dtoTramo, dto_cartera $dtoCartera) {

        $porcentaje = $dtoTramo->getPorcentaje();
        $cartera = $dtoCartera->getId();
        $servicio = $dtoTramo->getIdServicio();
        $usuario = $dtoTramo->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $sqlCheckTramo = " SELECT COUNT(*) AS 'COUNT' FROM ca_tramo WHERE idservicio = ? AND tipo = 'GENERICO' AND tramo = 'GENERICO' ";

        $prCheckTramo = $connection->prepare($sqlCheckTramo);
        $prCheckTramo->bindParam(1, $servicio, PDO::PARAM_INT);
        $prCheckTramo->execute();
        $countCheckTramo = $prCheckTramo->fetchAll(PDO::FETCH_ASSOC);

        if ($countCheckTramo[0]['COUNT'] == 0) {
            $sqlTramo = " INSERT INTO ca_tramo ( tramo, tipo, fecha_creacion, usuario_creacion, porcentaje_comision, idservicio ) VALUES ( 'GENERICO','GENERICO',NOW(),?,?,? ) ";
        } else {
            $sqlTramo = " UPDATE ca_tramo SET fecha_modificacion = NOW(), usuario_modificacion = ?, porcentaje_comision = ? 
				WHERE idservicio = ? AND tipo = 'GENERICO' AND tramo = 'GENERICO' ";
        }


        $prTramo = $connection->prepare($sqlTramo);
        $prTramo->bindParam(1, $usuario, PDO::PARAM_INT);
        $prTramo->bindParam(2, $porcentaje, PDO::PARAM_INT);
        $prTramo->bindParam(3, $servicio, PDO::PARAM_INT);
        if ($prTramo->execute()) {

            $sqlUpdateOperacion = " UPDATE ca_detalle_cuenta SET tipo_comision = 'GENERICO',porcentaje_comision = " . $porcentaje . ", 
				comision_total_deuda = ( total_deuda * " . $porcentaje . ")/100,
				comision_total_deuda_soles = ( total_deuda_soles * " . $porcentaje . " )/100 ,
				comision_total_deuda_dolares = ( total_deuda_dolares * " . $porcentaje . " )/100, 
				comision_monto_mora = ( monto_mora * " . $porcentaje . " )/100,
				comision_monto_mora_soles = ( monto_mora_soles * " . $porcentaje . " )/100,
				comision_monto_mora_dolares = ( monto_mora_dolares * " . $porcentaje . " )/100,
				comision_saldo_capital = ( saldo_capital * " . $porcentaje . " )/100,
				comision_saldo_capital_soles = ( saldo_capital_soles * " . $porcentaje . " )/100,
				comision_saldo_capital_dolares = ( saldo_capital_dolares * " . $porcentaje . " )/100 
				WHERE idcartera = ? ";

            $prUpdateOperacion = $connection->prepare($sqlUpdateOperacion);
            $prUpdateOperacion->bindParam(1, $cartera, PDO::PARAM_INT);
            if ($prUpdateOperacion->execute()) {

                $sqlUpdateCuenta = " UPDATE ca_cuenta cu 
					SET total_comision = ( SELECT SUM(comision_total_deuda) FROM ca_detalle_cuenta WHERE idcartera = ? AND numero_cuenta = cu.numero_cuenta AND moneda = cu.moneda GROUP BY numero_cuenta )
					WHERE idcartera = ? ";

                $prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
                $prUpdateCuenta->bindParam(1, $cartera, PDO::PARAM_INT);
                $prUpdateCuenta->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdateCuenta->execute()) {

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
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function asignar_comision_generico($porcentaje, dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();

        $sql = " UPDATE ca_detalle_cuenta SET porcentaje_comision = " . $porcentaje . ", 
				comision_total_deuda = (total_deuda * " . $porcentaje . ")/100,
				comision_total_deuda_soles = (total_deuda_soles * " . $porcentaje . " )/100 ,
				comision_total_deuda_dolares = (total_deuda_dolares * " . $porcentaje . " )/100, 
				comision_monto_mora = (monto_mora * " . $porcentaje . " )/100,
				comision_monto_mora_soles = (monto_mora_soles * " . $porcentaje . " )/100,
				comision_monto_mora_dolares = ( monto_mora_dolares * " . $porcentaje . " )/100,
				comision_saldo_capital = ( saldo_capital * " . $porcentaje . " )/100,
				comision_saldo_capital_soles = ( saldo_capital_soles * " . $porcentaje . " )/100,
				comision_saldo_capital_dolares = ( saldo_capital_dolares * " . $porcentaje . " )/100 
				WHERE idcartera = $cartera ";

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {

            $sqlUpdatePorcentaje = " UPDATE ca_detalle_cuenta SET porcentaje_comision = 0 WHERE idcartera = $cartera ";
            $prUpdatePorcentaje = $connection->prepare($sqlUpdatePorcentaje);
            if ($prUpdatePorcentaje->execute()) {
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

}

?>
