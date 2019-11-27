<?php

	class MARIACalendarDAO {
		
		public function queryLastEventWork ( $anio , $mes, dto_tarea $dtoTarea ) {
			$sql=" SELECT DAY(fecha) AS 'dia',fecha,CONCAT_WS('_','Calendar','subcontent','tarea',YEAR(fecha),MONTH(fecha),DAY(fecha)) as 'layer',
			( SELECT hora FROM ca_tarea WHERE idusuario_servicio=tar.idusuario_servicio AND fecha=tar.fecha ORDER BY idtarea DESC LIMIT 1  ) AS 'hora',
			( SELECT titulo FROM ca_tarea WHERE idusuario_servicio=tar.idusuario_servicio AND fecha=tar.fecha ORDER BY idtarea DESC LIMIT 1  ) AS 'titulo'
			 FROM ca_tarea tar
			WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND estado=1 
			GROUP BY fecha
			UNION 
			SELECT DAY(fecha) AS 'dia',fecha,CONCAT_WS('_','Calendar','subcontent','evento',YEAR(fecha),MONTH(fecha),DAY(fecha)) AS 'layer',
			( SELECT hora FROM ca_evento WHERE idusuario_servicio=event.idusuario_servicio AND fecha=event.fecha ORDER BY idevento DESC LIMIT 1  ) AS 'hora',
			( SELECT evento FROM ca_evento WHERE idusuario_servicio=event.idusuario_servicio AND fecha=event.fecha ORDER BY idevento DESC LIMIT 1  ) AS 'evento'
			 FROM ca_evento event
			WHERE idusuario_servicio = ? AND YEAR(fecha)=$anio AND MONTH(fecha)=$mes AND estado=1 AND ISNULL(fecha_fin)=1
			GROUP BY fecha ";
			
			$UsuarioServicio=$dtoTarea->getIdUsuarioServicio();
			
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			
			//$connection->beginTransaction();
			
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$UsuarioServicio);
			$pr->bindParam(2,$UsuarioServicio);
			if( $pr->execute() ){
				//$connection->commit();
				return $pr->fetchAll(PDO::FETCH_ASSOC);
			}else{
				//$connection->rollBack();
				return array();
			}
			
		}
			
	}

?>