<?php

class PGSQL_PDOTareaDAO {

    public function insert(dto_tarea $dtoTarea) {
        $sql = " INSERT INTO ca_tarea( titulo, fecha, hora, nota, idusuario_servicio, fecha_creacion, usuario_creacion ) 
				VALUES( ?,?,?,?,?,NOW(),? ) ";

        $titulo = $dtoTarea->getTitulo();
        $fecha = $dtoTarea->getFecha();
        $hora = $dtoTarea->getHora();
        $nota = $dtoTarea->getNota();
        $UsuarioServicio = $dtoTarea->getIdUsuarioServicio();
        $UsuarioCreacion = $dtoTarea->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $titulo);
        $pr->bindParam(2, $fecha);
        $pr->bindParam(3, $hora);
        $pr->bindParam(4, $nota);
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

    public function queryLastWork($anio, $mes, dto_tarea $dtoTarea) {
        $sql = " SELECT DAY(fecha) AS 'dia',fecha,CONCAT_WS('_','Calendar','subcontent','tarea',YEAR(fecha),MONTH(fecha),DAY(fecha)) as 'layer',
			( SELECT hora FROM ca_tarea WHERE idusuario_servicio=tar.idusuario_servicio AND fecha=tar.fecha ORDER BY idtarea DESC LIMIT 1  ) AS 'hora',
			( SELECT titulo FROM ca_tarea WHERE idusuario_servicio=tar.idusuario_servicio AND fecha=tar.fecha ORDER BY idtarea DESC LIMIT 1  ) AS 'titulo'
			 FROM ca_tarea tar
			WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND estado=1 
			GROUP BY fecha ";

        $UsuarioServicio = $dtoTarea->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryWorkToDay(dto_tarea $dtoTarea) {
        $sql = " SELECT idtarea, titulo , hora FROM ca_tarea WHERE estado = 1 
			AND fecha=CURDATE() AND idusuario_servicio = ? ";

        $UsuarioServicio = $dtoTarea->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioServicio);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}

?>