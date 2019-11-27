<?php

class MARIARankingDAO {

    public function ranking_carga_fecha ( $idcartera, $anio, $mes, $diai, $diaf, $tipo ) {
        
        $trace_sql = " ";
        if( $tipo == 'gestion' ) {
            $trace_sql = " lla.idusuario_servicio ";
        } else {
            $trace_sql = " clicar.idusuario_servicio ";
        }

        $field = array();

        for( $i=$diai;$i<=$diaf;$i++ ) {
            array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
        }

        $sql = " SELECT
                ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = ".$trace_sql." ) AS TELEOPERADOR ,
                carfin.nombre AS CARGA,
                ".implode(",",$field)." , 
                COUNT( * ) AS TOTAL_GENERAL 
                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
                ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
                WHERE clicar.idcartera IN ( ".$idcartera." ) AND lla.tipo = 'LL' AND lla.estado = 1 
                AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
                GROUP BY ".$trace_sql." , fin.idcarga_final 
                WITH ROLLUP ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

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
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>