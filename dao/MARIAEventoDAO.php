<?php

class MARIAEventoDAO {

    public function insert(dto_evento $dtoEvento) {
        $sql = " INSERT INTO ca_evento( evento ,fecha ,hora, idusuario_servicio ,fecha_creacion, usuario_creacion ) 
				VALUES( ?,?,?,?,NOW(),? ) ";

        $evento = $dtoEvento->getEvento();
        $fecha = $dtoEvento->getFecha();
        $hora = $dtoEvento->getHora();
        $UsuarioServicio = $dtoEvento->getIdUsuarioServicio();
        $UsuarioCreacion = $dtoEvento->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $evento);
        $pr->bindParam(2, $fecha);
        $pr->bindParam(3, $hora);
        $pr->bindParam(4, $UsuarioServicio);
        $pr->bindParam(5, $UsuarioCreacion);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertRange(dto_evento $dtoEvento) {
        $sql = " INSERT INTO ca_evento( evento , fecha, fecha_fin, hora, idusuario_servicio ,fecha_creacion, usuario_creacion ) 
				VALUES( ?,?,?,?,?,NOW(),? ) ";

        $evento = $dtoEvento->getEvento();
        $fecha = $dtoEvento->getFecha();
        $fecha_fin = $dtoEvento->getFechaFin();
        $hora = $dtoEvento->getHora();
        $UsuarioServicio = $dtoEvento->getIdUsuarioServicio();
        $UsuarioCreacion = $dtoEvento->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $evento);
        $pr->bindParam(2, $fecha);
        $pr->bindParam(3, $fecha_fin);
        $pr->bindParam(4, $hora);
        $pr->bindParam(5, $UsuarioServicio);
        $pr->bindParam(6, $UsuarioCreacion);

        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryLastEvent($anio, $mes, dto_evento $dtoEvento) {
        $sql = " SELECT DAY(fecha) AS 'dia',fecha,CONCAT_WS('_','Calendar','subcontent','evento',YEAR(fecha),MONTH(fecha),DAY(fecha)) AS 'layer',
			( SELECT hora FROM ca_evento WHERE idusuario_servicio=event.idusuario_servicio AND fecha=event.fecha ORDER BY idevento DESC LIMIT 1  ) AS 'hora',
			( SELECT evento FROM ca_evento WHERE idusuario_servicio=event.idusuario_servicio AND fecha=event.fecha ORDER BY idevento DESC LIMIT 1  ) AS 'evento'
			 FROM ca_evento event
			WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND estado=1 
			GROUP BY fecha ";

        $UsuarioServicio = $dtoEvento->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryEventRange($anio, $mes, dto_evento $dtoEvento) {
        $sql = " SELECT idevento,fecha,fecha_fin,DAY(fecha) AS 'dia_inicio',DAY(fecha_fin) AS 'dia_fin',
				(DAY(fecha_fin)-DAY(fecha))+1 AS 'cantidad_dia',evento,hora,YEAR(fecha) AS 'anio',MONTH(fecha) AS 'mes'
				FROM ca_evento WHERE ISNULL(fecha_fin)=0 AND estado=1 AND idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes
				ORDER BY fecha_fin ";

        $UsuarioServicio = $dtoEvento->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryEventToDay(dto_evento $dtoEvento) {

        $sql = " SELECT idevento, evento, hora FROM ca_evento WHERE fecha=CURDATE() AND ISNULL(fecha_fin)=1
			AND idusuario_servicio = ? AND estado = 1 
			UNION 
			SELECT idevento, evento , hora FROM ca_evento WHERE fecha_fin >=CURDATE() AND fecha<=CURDATE() AND ISNULL(fecha_fin)=0
			AND idusuario_servicio = ? AND estado = 1 ORDER BY hora  ";

        $UsuarioServicio = $dtoEvento->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        $pr->bindParam(2, $UsuarioServicio);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

}

?>