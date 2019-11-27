<?php

class PGSQL_PDORankingDAO {

    public function ranking_pago(dto_cartera $dtoCartera) {
        $idcartera = $dtoCartera->getId();
        $sql = " SELECT 
				(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
				ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
				TRUNCATE( SUM( cu.total_deuda ),2 ) AS 'DEUDA_TOTAL',
				TRUNCATE( SUM( cu.monto_Pagado ),2 ) AS 'PAGO',
				ROUND(((TRUNCATE( SUM( cu.monto_Pagado ),2 ))/(TRUNCATE( SUM( cu.total_deuda ),2 ))*100),2) as PORCEN
				FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
				ON cu.codigo_cliente = clicar.codigo_cliente 
				WHERE clicar.idcartera in (" . $idcartera . ") AND cu.idcartera in (" . $idcartera . ")
				AND clicar.idusuario_servicio != 0 
				GROUP BY clicar.idusuario_servicio ORDER BY 3 DESC ";
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
            return array();
        }
    }

}

?>