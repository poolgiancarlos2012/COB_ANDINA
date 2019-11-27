<?php

class PGSQL_PDOCuentaDAO {

    public function queryHistorialByCliente(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.codigo_cliente AS 'CODIGO_CLIENTE', cu.numero_cuenta AS 'NUMERO_CUENTA', 
					DATE(det.fecha_creacion) AS 'FECHA', TRUNCATE( IFNULL(cu.total_deuda,''),2 ) AS 'DEUDA',
					TRUNCATE( IFNULL(cu.total_comision,''),2 ) AS 'COMISION',
					IFNULL(cu.moneda,'') AS 'MONEDA', IFNULL(det.tramo,'') AS 'TRAMO' , IFNULL(det.dias_mora,'') AS 'DIAS_MORA',
					IFNULL(det.numero_cuotas,'') AS 'NUMERO_CUOTAS', IFNULL(det.numero_cuotas_pagadas,'') AS 'NUMERO_CUOTAS_PAGADAS'
					FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_detalle_cuenta det 
					ON det.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera
					WHERE cu.idcartera IN ( SELECT idcartera FROM ca_cartera WHERE cartera_act = ?   )
					AND clicar.idcliente = ? AND cu.estado = 1 ";

        $cartera = $dtoClienteCartera->getIdCartera();
        $idcliente = $dtoClienteCartera->getIdCliente();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idcliente, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryByClient(dto_cuenta $dtoCuenta) {

        $sql = " SELECT if(retirado=1,'<font color=\"red\"><b><h3>RETIRADO</h3></b></font>','NO') as RETIRADO, t1.idcuenta, numero_cuenta, IFNULL(moneda,'') AS 'moneda', TRUNCATE( (total_deuda + IFNULL(total_comision,0) ),2 ) AS 'total_deuda', IFNULL(telefono,'') AS 'telefono', IFNULL(ultimo_fecha_cp,'') AS 'ultimo_fecha_cp' ,
			IFNULL( TRUNCATE( ultimo_monto_cp,2 ),'' ) AS 'ultimo_monto_cp',
			IFNULL( ultimo_idfinal,'' ) AS 'ultimo_idfinal',
			fact.is_send,
			corte_focalizado
			FROM ca_cuenta as t1 LEFT JOIN ca_factura_digital as fact ON fact.idcuenta = t1.idcuenta WHERE idcartera = ? AND codigo_cliente = ?  ";

        $cartera = $dtoCuenta->getIdCartera();
        $codigo_cliente = $dtoCuenta->getCodigoCliente();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $codigo_cliente, PDO::PARAM_STR);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

}

?>
