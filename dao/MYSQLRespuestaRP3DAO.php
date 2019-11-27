<?php


	class MYSQLRespuestaRP3DAO {
	
		public function Gestion ( $servicio, $carteras, $fecha_inicio, $fecha_fin ) {
			
			$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
			$user = $confCobrast['user_db']['user_rpt'];
			$password = $confCobrast['user_db']['password_rpt'];

			$factoryConnection = FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection( $user,$password );

			$sql = " SELECT 
					cu.dato2 AS SECUENCIA,
					cu.numero_cuenta AS CUENTA,
					'T' AS TIPO,
					IFNULL(( SELECT CASE WHEN status = 'CORRECTA' THEN 1 WHEN status = 'INCORRECTA' THEN 2 ELSE 3   END FROM ca_ll_det_direccion_est WHERE idllamada = lla.idllamada AND status != 'NUEVO' LIMIT 1 ),3) AS CALIFICACION,
					( SELECT IFNULL( CASE WHEN idtipo_referencia IN ( 2 ) THEN 'DO' WHEN idtipo_referencia IN ( 1,4,5,3 ) THEN 'LA' WHEN idtipo_referencia IN ( 6,8,9 ) THEN 'RE' ELSE 'OT' END , 'OT' )  FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS ATRIBUTO,
					IFNULL(( SELECT codigo FROM ca_contacto WHERE idcontacto = lla.idcontacto ),3) AS GESTION_1,
					( SELECT codigo FROM ca_final_servicio WHERE idservicio = ? AND idfinal = lla.idfinal ) AS GESTION_2,
					IFNULL(( SELECT codigo FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago  ),7) AS MOTIVO,
					IFNULL(( SELECT codigo FROM ca_parentesco WHERE idparentesco = lla.idparentesco ),0) AS PARENTESCO,
					lla.observacion AS COMENTARIO,
					IF( ISNULL(lla.fecha_cp) = 0 AND DATE(lla.fecha_cp) != '0000-00-00','S','N' ) AS PROMESA_PAGO,
					IF( ISNULL(lla.fecha_cp) = 0 AND DATE(lla.fecha_cp) != '0000-00-00',DATE_FORMAT(lla.fecha_cp,'%Y%m%d'),'' ) AS FECHA_PROMESA,
					CONCAT( LPAD(HOUR(lla.fecha),2,0),':00 - ', LPAD(HOUR(lla.fecha)+1,2,0),':00 ' , DATE_FORMAT(lla.fecha,'%p') ) AS HORARIO,
					TIME(FROM_UNIXTIME( UNIX_TIMESTAMP(lla.fecha) - ( RAND()*180+1) ))  AS HORA_INI,
					TIME(lla.fecha) AS HORA_FIN,
					( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS GESTOR
					FROM ca_llamada lla INNER JOIN ca_cuenta cu 
					ON cu.idcuenta = lla.idcuenta 
					WHERE cu.idcartera IN ( ".$carteras." ) AND DATE(lla.fecha) BETWEEN ? AND ? 
					AND lla.estado = 1 AND lla.tipo = 'LL' ";

			$pr = $connection->prepare($sql);
			$pr->bindParam(1,$servicio,PDO::PARAM_INT);
			$pr->bindParam(2,$fecha_inicio,PDO::PARAM_STR);
			$pr->bindParam(3,$fecha_fin,PDO::PARAM_STR);
			$pr->execute();
			$dataG = $pr->fetchAll(PDO::FETCH_ASSOC);
			return $dataG ;
		}
		
		public function send( $tipo, $servicio, $carteras, $fecha_inicio, $fecha_fin ) {
			
			$dataG = $this->Gestion( $servicio, $carteras, $fecha_inicio, $fecha_fin );	
					
			$field_table = " respuesta ";
			$field_table_hst = " respuesta_historico ";

			if( $tipo == 'comercial' ) {
				$field_table = " respuesta ";
				$field_table_hst = " respuesta_historico ";
			}else if( $tipo == 'banco' ) {
				$field_table = " respuestaBanco ";
				$field_table_hst = " respuesta_historicoBanco ";
			}
			
			$factoryConnection = FactoryConnection::create('mysqli');
			$connectionRP3 = $factoryConnection->getConnection( "","", new configRP3 );
			
			$connectionRP3->autocommit(false);
			
			
			
			$sqlTr = " DELETE FROM ".$field_table."  ";
			
			$sqlH = " INSERT INTO ".$field_table_hst." ( SECUENCIA, CUENTA, TIPO, CALIFICACION, ATRIBUTO, GESTION_1, GESTION_2, MOTIVO, PARENTESCO, COMENTARIO, PROMESA_PAGO, FECHA_PROMESA, HORARIO, HORA_INI, HORA_FIN, GESTOR )
					SELECT SECUENCIA, CUENTA, TIPO, CALIFICACION, ATRIBUTO, GESTION_1, GESTION_2, MOTIVO, PARENTESCO, COMENTARIO, PROMESA_PAGO, FECHA_PROMESA, HORARIO, HORA_INI, HORA_FIN, GESTOR 
					FROM ".$field_table." ";
					
			$prRP3_H = $connectionRP3->prepare($sqlH);
			if( $prRP3_H->execute() ){
				
			}else{
				$connectionRP3->rollback();
				return array("rst"=>false);
				exit();
			}
			
			$prRP3_T = $connectionRP3->prepare($sqlTr);
			if( $prRP3_T->execute() ){
			}else{
				$connectionRP3->rollback();
				return array("rst"=>false);
				exit();
			}

			$count = 0;
			foreach( $dataG as $index => $row ) {
			
				$sqlRP3 = " INSERT INTO ".$field_table." ( SECUENCIA, CUENTA, TIPO, CALIFICACION, ATRIBUTO, GESTION_1, GESTION_2, MOTIVO, PARENTESCO, COMENTARIO, PROMESA_PAGO, FECHA_PROMESA, HORARIO, HORA_INI, HORA_FIN, GESTOR ) 
						VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ) ";
				
				$prRP3 = $connectionRP3->prepare($sqlRP3);
				$prRP3->bind_param(
					'ssssssssssssssss',
					$row['SECUENCIA'],
					$row['CUENTA'],
					$row['TIPO'],
					$row['CALIFICACION'],
					$row['ATRIBUTO'],
					$row['GESTION_1'],
					$row['GESTION_2'],
					$row['MOTIVO'],
					$row['PARENTESCO'],
					str_replace("'","",str_replace("\n"," ",str_replace("\"","",$row['COMENTARIO']))),
					$row['PROMESA_PAGO'],
					$row['FECHA_PROMESA'],
					$row['HORARIO'],
					$row['HORA_INI'],
					$row['HORA_FIN'],
					$row['GESTOR']
					
				);
				/*$prRP3->bindParam(1,$row['SECUENCIA']);
				$prRP3->bindParam(2,$row['CUENTA']);
				$prRP3->bindParam(3,$row['TIPO']);
				$prRP3->bindParam(4,$row['CALIFICACION']);
				$prRP3->bindParam(5,$row['ATRIBUTO']);
				$prRP3->bindParam(6,$row['GESTION_1']);
				$prRP3->bindParam(7,$row['GESTION_2']);
				$prRP3->bindParam(8,$row['MOTIVO']);
				$prRP3->bindParam(9,$row['PARENTESCO']);
				$prRP3->bindParam(10,$row['COMENTARIO']);
				$prRP3->bindParam(11,$row['PROMESA_PAGO']);
				$prRP3->bindParam(12,$row['FECHA_PROMESA']);
				$prRP3->bindParam(13,$row['HORARIO']);
				$prRP3->bindParam(14,$row['HORA_INI']);
				$prRP3->bindParam(15,$row['HORA_FIN']);
				$prRP3->bindParam(16,$row['GESTOR']);*/
				if( $prRP3->execute() ){
					
				}else{
					$connectionRP3->rollback();
					return array("rst"=>false);
					exit();
				}
				
				$count++;
			}
			
			$connectionRP3->commit();
			return array("rst"=>true,"cantidad"=>$count);
			
			
		}
		
	}
	
	
?>