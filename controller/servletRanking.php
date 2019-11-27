<?php

	class servletRanking extends CommandController {
		
		public function doPost ( ) {
			$daoProcedure=DAOFactory::getDAOProcedure('maria'); 
			switch($_POST['action']){
				case 'RankingCartera':
					/*$dataProc=$daoProcedure->ranking_cartera($_POST['Servicio'],$_POST['cartera'],$_POST['Fecha'],$_POST['fechaf']);
					echo json_encode(array('cabeceras'=>array_keys($dataProc[0]),'data'=>$dataProc)); */
					
					$sql = " SELECT fin.idcarga_final, carfin.nombre
					FROM ca_final_servicio finser INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
					ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = finser.idfinal
					WHERE finser.idservicio = ".$_POST['Servicio']." AND finser.estado = 1 ";

					$data = $daoProcedure->executeQueryReturn($sql);
					$fields = array();
					for( $i=0;$i<count($data);$i++ ) {
						array_push($fields," SUM( IF( fin.idcarga_final = ".$data[$i]['idcarga_final']." ,1,0) ) AS ".$data[$i]['nombre']." ");
					}

					$sql = " SELECT clicar.idusuario_servicio,
						( SELECT CONCAT_WS(' ',paterno,materno,nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'operador',
						COUNT( DISTINCT clicar.idcliente_cartera ) AS 'abonados',
						COUNT(*) AS 'llamadas' ".((count($fields)>0)?" , ".implode(",",$fields):" ")." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal  AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$_POST['cartera']." )  
						AND DATE(lla.fecha) BETWEEN '".$_POST['Fecha']."' AND '".$_POST['fechaf']."' 
						AND clicar.idusuario_servicio != 0 AND ISNULL( clicar.idusuario_servicio ) = 0 
						AND lla.estado = 1 AND lla.tipo = 'LL'
						GROUP BY clicar.idusuario_servicio ";

					$dataR = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode(array('cabeceras'=>((count($dataR)>0)?array_keys($dataR[0]):array()),'data'=>$dataR));
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			}
			
		}
		
		public function doGet ( ) {
			$daoProcedure=DAOFactory::getDAOProcedure('maria');
			$daoRanking=DAOFactory::getDAORanking('maria'); 
			$daoFinalServicio=DAOFactory::getDAOFinalServicio('maria');
			switch($_GET['action']){
				case 'MetaClienteCuentaUsuarioServicio':
					
					$idcartera = $_GET['idcartera'];
					$idusuario_servicio = $_GET['idusuario_servicio'];
					
					$sqlMeta = " SELECT 
							car.nombre_cartera AS 'CARTERA',
							IFNULL(car.meta_cliente,0) AS 'META CLIENTE',
							COUNT( DISTINCT clicar.idcliente_cartera ) AS 'CLIENTES ASIGNADOS',
							COUNT( DISTINCT IF( cu.monto_pagado > 0  , clicar.idcliente_cartera, NULL ) ) AS 'CLIENTES RECUPERADOS',
							( COUNT( DISTINCT IF( cu.monto_pagado > 0  , clicar.idcliente_cartera, NULL ) ) / COUNT( DISTINCT clicar.idcliente_cartera ) ) * 100 AS 'META CLIENTE ACTUAL',
							IFNULL(car.meta_cuenta,0) AS 'META CUENTA',
							COUNT( DISTINCT cu.idcuenta  ) AS 'CUENTAS ASIGNADAS',
							COUNT( DISTINCT IF( cu.monto_pagado > 0  , cu.idcuenta, NULL )  ) AS 'CUENTAS RECUPERADAS',
							( COUNT( DISTINCT IF( cu.monto_pagado > 0  , cu.idcuenta, NULL )  ) / COUNT( DISTINCT cu.idcuenta  ) ) * 100  AS 'META CUENTA ACTUAL'
							FROM  ca_cartera car 
							INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera 
							INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera 
							WHERE clicar.idcartera IN ( ".$idcartera." ) AND clicar.idusuario_servicio  = ".$idusuario_servicio."  
							GROUP BY clicar.idcartera  ";
					
					$data = $daoProcedure->executeQueryReturn($sqlMeta);
					
					echo json_encode( array('MetaIni'=>$data) );
					
				break;
				case 'RankingServicioTotalUsuarioPorDia':
					
					$idservicio = $_GET['idservicio'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					$por = $_GET['por'];

					$trace_sql = "";
					if( $por == 'gestion' ){
						$trace_sql = " lla.idusuario_servicio ";
					}else{
						$trace_sql = " clicar.idusuario_servicio ";
					}
					
					$field = array();
					
					for( $i=6;$i<=20;$i++ ) {
						array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i.":00' ");
					}
					
					$sql = " 	SELECT 
								( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = ".$trace_sql." ) AS 'TELEOPERADOR',
								".implode(",",$field)." , SUM( IF( HOUR(lla.fecha) BETWEEN 6 AND 20 ,1,0 ) ) AS 'TOTAL'
								FROM ca_campania cam INNER JOIN ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
								ON lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera AND car.idcampania = cam.idcampania
								WHERE cam.estado = 1 AND car.estado = 1 AND lla.tipo = 'LL' AND lla.estado = 1
								AND DATE( lla.fecha ) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
								AND cam.idservicio = ".$idservicio." 
								AND clicar.idusuario_servicio >0
								GROUP BY ".$trace_sql." 
								ORDER BY 1
								";
					// echo $sql;

					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode( array('Ranking'=>$data) );
					
				break;
				case 'RankingTotalUsuarioPorDia':
					
					$idusuario_servicio = $_GET['idusuario_servicio'];
					$idservicio = $_GET['idservicio'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					$por = $_GET['por'];
					
					$trace_sql = "";
					if( $por == 'gestion' ){
						$trace_sql = " lla.idusuario_servicio ";
					}else{
						$trace_sql = " clicar.idusuario_servicio ";
					}
					
					$field = array();
					
					for( $i=6;$i<=20;$i++ ) {
						//array_push($field," SUM( IF( HOUR(tran.fecha) = ".$i." ,1,0 ) ) AS '".$i.":00' ");
						array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i.":00' ");
					}
					
					$sql = " SELECT 
						( SELECT nombre FROM ca_carga_final WHERE idcarga_final = fin.idcarga_final ) AS 'CONTACTABILIDAD',
						".implode(",",$field)." , SUM( IF( HOUR(lla.fecha) BETWEEN 6 AND 20 ,1,0 ) ) AS TOTAL
						FROM ca_campania cam INNER JOIN ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcartera = car.idcartera AND car.idcampania = cam.idcampania
						WHERE cam.estado = 1 AND car.estado = 1 AND lla.tipo = 'LL' AND lla.estado = 1
						AND ".$trace_sql." = ".$idusuario_servicio." AND DATE( lla.fecha ) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
						AND cam.idservicio = ".$idservicio." 
						GROUP BY fin.idcarga_final WITH ROLLUP ";
					

					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode( array('RankingUsuario'=>$data) );
					
				break;
				case 'ListCargaServicio':
					
					$idservicio = $_GET['idservicio'];
					$dtoServicio = new dto_servicio ;
					$dtoServicio->setId($idservicio);
					
					echo json_encode($daoFinalServicio->queryCargaByServicio($dtoServicio));
					
				break;
				case 'ranking_pago':
				
					$idcartera = $_GET['idcartera'];
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					echo json_encode($daoRanking->ranking_pago($dtoCartera));
				break;
				case 'ranking_estado':
					
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					
					$field = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0  ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
					
					$sql = " SELECT clicar.idusuario_servicio AS 'CODIGO_TELEOPERADOR',
						(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
						fin.nombre AS 'ESTADO',
						".implode(",",$field)." ,
						SUM( IF( DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."',1,0 ) ) AS 'TOTAL_LLAMADAS'
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera in (".$idcartera.") AND lla.tipo = 'LL' AND lla.estado = 1 
						GROUP BY lla.idusuario_servicio , lla.idfinal WITH ROLLUP ";
						
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode( $data );
					
				break;
				case 'ranking_abonado_llamada':
				
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					
					$field = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," COUNT( DISTINCT IF( DATE(lla.fecha)='".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', lla.idcliente_cartera,NULL ) ) AS 'ABONADO_".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0  ) ) AS 'LLAMADA_".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
					
					$sql = " SELECT 
						(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
						".implode(",",$field)."
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
						ON lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera in (".$idcartera.") AND lla.tipo = 'LL' AND lla.estado = 1
						GROUP BY lla.idusuario_servicio WITH ROLLUP ";
					
					$data = $daoProcedure->executeQueryReturn($sql);

					echo json_encode( $data );
					
				break;
				case 'ranking_llamada_hora':
					
					$idservicio = $_GET['idservicio'];
					$idcartera = $_GET['idcartera'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					
					$field = array();
					
					$sql = " SELECT DISTINCT carfin.idcarga_final, carfin.nombre
							FROM ca_final_servicio finser INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = finser.idfinal
							WHERE finser.idservicio = $idservicio ";
							
					$dataCarga = $daoProcedure->executeQueryReturn($sql);
					
					for( $j=6; $j<19;$j++ ) {
						for( $i=0; $i<count($dataCarga);$i++ ) {
							array_push($field," SUM( IF( HOUR(lla.fecha) = ".$j." AND fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."_".str_pad($j,2,'0',STR_PAD_LEFT).":00 ".str_pad(($j+1),2,'0',STR_PAD_LEFT).":00' ");
						}
					}
					
					$sql = " SELECT
						(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
						".(( count($field) >0 )?implode(",",$field).",":"")."  COUNT(*) AS 'TOTAL_LLAMADAS'
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera in (".$idcartera.") 
						AND DATE( lla.fecha ) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
						AND lla.tipo = 'LL' AND lla.estado = 1
						GROUP BY lla.idusuario_servicio WITH ROLLUP ";
					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode(array('carga'=>$dataCarga,'data'=>$data));
					
				break;
				case 'ranking_llamada_hora_detalle':
					
					$idservicio = $_GET['idservicio'];
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
										
					$field = array();
					
					$sql = " SELECT DISTINCT carfin.idcarga_final, carfin.nombre
							FROM ca_final_servicio finser INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = finser.idfinal
							WHERE finser.idservicio = $idservicio ";
							
					$dataCarga = $daoProcedure->executeQueryReturn($sql);
					
					for( $j=$diai; $j<=$diaf;$j++ ) {
						for( $i=0; $i<count($dataCarga);$i++ ) {
							array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($j,2,'0',STR_PAD_LEFT)."' AND fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."_".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($j,2,'0',STR_PAD_LEFT)."' ");
						}
					}
					
					$sql = " SELECT 
						(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
						CONCAT( LPAD(HOUR (lla.fecha),2,'0'),':00 ', LPAD(HOUR (lla.fecha)+1,2,'0'),':00'  ) AS 'HORA',
						".((count($field)>0)?implode(",",$field).",":"")." COUNT(*) AS 'TOTAL_LLAMADAS' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera in (".$idcartera.") 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 
						GROUP BY lla.idusuario_servicio, HOUR (lla.fecha) WITH ROLLUP ";
						
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode(array('carga'=>$dataCarga,'data'=>$data));
					
				break;
				case 'ranking_visita':
					
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
										
					$field = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(vis.fecha_visita) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");					
					}
					
					$sql = " SELECT 
						(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador LIMIT 1 ) AS 'NOTIFICADOR', 
						".implode(",",$field).", COUNT(*) AS 'VISITAS' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_visita vis 
						ON vis.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera in (".$idcartera.") 
						AND DATE(vis.fecha_visita) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
						AND vis.tipo = 'VIS' AND vis.estado = 1
						GROUP BY vis.idnotificador WITH ROLLUP ";
					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode($data);
					
				break;
				case 'ranking_semaforo':
					
					$idcartera = $_GET['idcartera'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					
					$field = array();
					
					for( $i=6;$i<=20;$i++ ) {
						array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i."' ");
					}
					
					$sql = " SELECT 
						(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
						".implode(",",$field).", COUNT(*) AS 'TOTAL_LLAMADAS' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera in (".$idcartera.") 
						AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
						AND lla.tipo = 'LL' AND lla.estado = 1 
						GROUP BY lla.idusuario_servicio  WITH ROLLUP ";
						
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode($data);
					
				break;
				case 'ranking_compromisos_pago':
				
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					
					$field = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(lla.fecha_cp) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
				
					$sql = " SELECT 
						(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
						".implode(",",$field).", COUNT(*) AS 'TOTAL_CP'
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera in (".$idcartera.") 
						AND DATE(lla.fecha_cp) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
						GROUP BY lla.idusuario_servicio WITH ROLLUP ";
					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					echo json_encode($data);
					
				break;
				case 'ranking_carga_fecha':
					
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					$tipo = (int)$_GET["tipo"];
					
					echo json_encode( $daoRanking->ranking_carga_fecha( $idcartera, $anio, $mes, $diai, $diaf, $tipo ) );
					
				break;
				case 'ranking_fija_contactabilidad_diario':
					
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					
					$field = array();
					
					$field1 = array();
					$field2 = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						
						array_push($field1," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$i."' ");
						array_push($field2," TRUNCATE( IFNULL( ( ( t1.".$i." / t2.".$i." ) * 100),0 ), 2 ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
					
					$sqlIni = " SELECT carfin.nombre AS 'VALOR', ".implode(",",$field).", COUNT(*) AS 'TOTAL' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
						ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 
						GROUP BY fin.idcarga_final WITH ROLLUP ";
						
					$sqlPor = " SELECT t1.VALOR, ".implode(",",$field2)." 
							FROM
							(
							SELECT carfin.nombre AS 'VALOR', ".implode(",",$field1)."
							FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
							WHERE clicar.idcartera IN ( ".$idcartera." ) 
							AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
							AND lla.tipo = 'LL' AND lla.estado = 1 
							GROUP BY fin.idcarga_final
							) AS t1 ,(
							SELECT 'FECHAS' AS 'VALOR', ".implode(",",$field1)." 
							FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
							WHERE clicar.idcartera IN ( ".$idcartera." ) 
							AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
							AND lla.tipo = 'LL' AND lla.estado = 1 
							) AS t2  ";
										
					$dataIni = $daoProcedure->executeQueryReturn($sqlIni);
					$dataPor = $daoProcedure->executeQueryReturn($sqlPor);
					
					echo json_encode(array("callIni"=>$dataIni,"callPor"=>$dataPor));
					
				break;
				case 'ranking_fija_rpt_gestion':
				
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					
					$field = array();
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
					
					$sql = " SELECT carfin.nombre AS 'ESTADO', niv.nombre AS 'RESPUESTA_GESTION', fin.nombre AS 'RESPUESTA_INCIDENCIA', 
						".implode(",",$field).", COUNT(*) AS 'TOTAL_GENERAL' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_nivel niv 
						ON niv.idnivel = fin.idnivel AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1
						GROUP BY fin.idcarga_final, fin.idnivel, fin.idfinal ORDER BY 1,2,3 "; 
						
					$sqlSum = " SELECT '' AS '1_','' AS '2_','' AS '3_',".implode(",",$field).", COUNT(*) AS 'TOTAL_GENERAL' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_nivel niv 
						ON niv.idnivel = fin.idnivel AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 "; 
						
					$data = $daoProcedure->executeQueryReturn($sql);
					echo json_encode($data);
					
				break;
				case 'ranking_contactabilidad_hora':
					
					$idcartera = $_GET['idcartera'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					
					$field = array();
					
					$field2 = array();
					
					for( $i=7;$i<=19;$i++ ) {
						array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i."' ");
						
						array_push($field2," TRUNCATE( IFNULL( (( t1.".$i." / t2.".$i." )*100),0 ), 2 ) AS '".$i."' ");
					}
					
					$sqlIni = " SELECT carfin.nombre AS 'ESTADO', ".implode(",",$field)." ,
						SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 19,1,0 ) ) AS 'TOTAL' 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
						ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
						AND lla.tipo = 'LL' AND lla.estado = 1
						GROUP BY fin.idcarga_final WITH ROLLUP ";
						
					$sqlPor = " SELECT t1.ESTADO AS 'RESULTADO', ".implode(",",$field2)." 
						FROM (
							SELECT carfin.nombre AS 'ESTADO', ".implode(",",$field)." 
							FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin 
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
							WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
							AND lla.tipo = 'LL' AND lla.estado = 1 
							GROUP BY fin.idcarga_final
						) AS t1, (
							SELECT 'VALORES' AS 'ESTADO', ".implode(",",$field)." 
							FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin
							ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
							WHERE clicar.idcartera IN ( ".$idcartera." ) 
							AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
							AND lla.tipo = 'LL' AND lla.estado = 1 
						) AS t2 ";
						

					$dataIni = $daoProcedure->executeQueryReturn($sqlIni);
					$dataPor = $daoProcedure->executeQueryReturn($sqlPor);
					
					echo json_encode(array("Ini"=>$dataIni,"Por"=>$dataPor));
					
				break;
				
				case 'ranking_fija_final':
					
					$carteras = $_GET['idcartera'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					$idservicio = $_GET['idservicio'];
					
					
					/*$sql_q_cont="SELECT DISTINCT carfin.nombre FROM ca_final  fin INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final 
WHERE fin.idfinal IN ( SELECT idfinal FROM ca_final_servicio WHERE estado=1 AND idservicio=".$idservicio.") ";
					$q_cont = $daoProcedure->executeQueryReturn($sql_q_cont);
					$query_cont="";
					foreach($q_cont as $value1){
						foreach($value1 as $value){
						$query_cont=$query_cont." ( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal inner join ca_carga_final carfin 
	on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion 
	where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha)  between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='$value') as '$value',";
						}
					}*/
					
					$sqlIni = "select 
	CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) as teleoperador, 
	"/*( select count(distinct tran.idcliente_cartera) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera  
	where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."') as 'abonados', */."
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera  
	 where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."') as 'LLAMADAS',
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='NOC') as 'NOC', 
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE') as 'CNE', 
	( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF') as 'CEF', 
	
	IFNULL(
	ROUND(((( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE')+
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF'))/
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera 
		where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."')*100),2)
		,0)	AS '% LLAMADAS',

	ROUND(sum(if(retirado=0,cu.total_deuda,0)),2)  as 'DEUDA TOTAL', 
	ROUND(sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,cu.monto_pagado,0)),2)  as 'PAGO', 
	ROUND((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0 ,cu.monto_pagado,0)))/(sum(cu.total_deuda))*100,2) AS 'PORC %',	

	sum(if(retirado=0,1,0)) as 'CUENTAS. TOTALES',
	sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)) AS 'CUENTAS. RECUP.',
	ROUND(((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)))/(sum(if(retirado=0,1,0)))*100),2) as 'PORC. %',

	ROUND((IFNULL( ((( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CNE')+
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera inner join ca_final fin on fin.idfinal=tran.idfinal 
		inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion where clicar.idcartera in (".$carteras.") 
		and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."' and carfin.nombre='CEF'))/
		( select count(*) from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera 
		where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio=ususer.idusuario_servicio and date(lla.fecha) between '".$fecha_inicio."' and '".$fecha_fin."')*100),0)+
	((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0 ,cu.monto_pagado,0)))/(sum(cu.total_deuda))*100)+
	((sum(if(date(cu.ul_fecha_pago) between '".$fecha_inicio."' and '".$fecha_fin."' and retirado=0,1,0)))/(sum(if(retirado=0,1,0)))*100))/3,2)	AS 'PROMEDIO %' 
	from ca_cuenta cu 
		inner join ca_cliente_cartera clicar on cu.codigo_cliente=clicar.codigo_cliente
		left join ca_usuario_servicio ususer on clicar.idusuario_servicio=ususer.idusuario_servicio
		left join ca_usuario usu on ususer.idusuario=usu.idusuario
where clicar.idcartera in (".$carteras.") AND cu.idcartera in (".$carteras.") and clicar.idusuario_servicio!=0 and retirado=0
group by clicar.idusuario_servicio order by 1 ASC";
					
					//echo($sqlIni);
					
					$dataIni = $daoProcedure->executeQueryReturn($sqlIni);
					
					
					echo json_encode(array("Ini"=>$dataIni));
					
				break;
				case 'ranking_semaforo_fija':
					
					$idcartera = $_GET['idcartera'];
					$fecha_inicio = $_GET['fecha_inicio'];
					$fecha_fin = $_GET['fecha_fin'];
					$idcarga = $_GET['idcarga'];
					$meta = (int)$_GET['meta'];
					
					$field = array();
					$field2 = array();
					$field3 = array();
					$field4 = array();
					$field5 = array();
					
					for( $i=7;$i<=19;$i++ ) {
						array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i."' ");
						
						array_push($field2," TRUNCATE( COUNT( DISTINCT IF( HOUR(lla.fecha) = ".$i." , clicar.idusuario_servicio, NULL ) ), 0 ) AS '".$i."' ");
						array_push($field3," SUM( IF( HOUR(lla.fecha) = ".$i." , 1, 0 ) ) AS '".$i."' ");
						array_push($field4," TRUNCATE( IFNULL( ( t1.".$i." / t2.".$i." ),0 ), 2 ) AS '".$i."' ");
						array_push($field5," TRUNCATE( ".$meta.", 0 ) AS '".$i."' ");
					}
					
					$sql = " SELECT 
						( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'TELEOPERADOR',
						".implode(",",$field).", SUM( IF( HOUR(lla.fecha) BETWEEN 7 AND 19,1,0 ) ) AS 'TOTAL_LLAMADAS'
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
						AND fin.idcarga_final IN ( ".$idcarga." )
						AND lla.tipo = 'LL' AND lla.estado = 1
						GROUP BY clicar.idusuario_servicio WITH ROLLUP ";
						
					$sqlNA = " SELECT 'NRO. Asesores por Hora' AS 'VALOR',".implode(",",$field2)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
						AND fin.idcarga_final IN ( ".$idcarga." )  
						AND lla.tipo = 'LL' AND lla.estado = 1 ";
					
					$sqlSum = " SELECT ".implode(",",$field3)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
						AND fin.idcarga_final IN ( ".$idcarga." ) 
						AND lla.tipo = 'LL' AND lla.estado = 1 ";
						
					$sqlPr = " SELECT 'Promedio de LLamadas' AS 'VALOR',".implode(",",$field4)." FROM ( $sqlSum ) t1 , ( $sqlNA ) t2 ";
					
					$sqlMeta = " SELECT 'Meta' AS 'VALOR',".implode(",",$field5)." ";
					
						
					$data = $daoProcedure->executeQueryReturn($sql);
					$Mdata = $daoProcedure->executeQueryReturn($sqlNA." UNION ".$sqlPr." UNION ".$sqlMeta);
					//echo json_encode($data);
					echo json_encode(array("Ini"=>$data,"Meta"=>$Mdata));
					
				break;
				case 'ranking_fija_cp_dia':
					
					$idcartera = $_GET['idcartera'];
					$anio = (int)$_GET["anio"];
					$mes = (int)$_GET["mes"];
					$diai = (int)$_GET["diai"];
					$diaf = (int)$_GET["diaf"];
					$idcarga = $_GET['idcarga'];
					
					$field = array();
					$field2 = array();
					$field3 = array();
					$field4 = array();
					$field5 = array(); 
					$field6 = array(); 
					$field7 = array(); 
					
					for( $i=$diai;$i<=$diaf;$i++ ) {
						array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."',1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field2," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field3," COUNT( DISTINCT IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' , clicar.idusuario_servicio , NULL ) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field4," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1 , 0) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field5," TRUNCATE( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), 0 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field6," ( ( SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) ) / 2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
						array_push($field7," TRUNCATE( ( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), '0') * 100 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
					}
				
					$sql = " SELECT 
						(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
						".implode(",",$field).", SUM( IF( DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' , 1,0 ) ) AS 'TOTAL_CP'
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND lla.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
						AND lla.tipo = 'LL' AND lla.estado = 1 
						GROUP BY clicar.idusuario_servicio WITH ROLLUP ";	
						
					$sqlCarga = " SELECT 'CEF' AS 'VALOR_EFECTIVO', ".implode(",",$field2)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) AND fin.idcarga_final IN ( ".$idcarga." ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 ";
						
					$sqlACP = " SELECT 'Asesores con Compromisos por Día' AS 'VALOR_EFECTIVO' ,".implode(",",$field3)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
						ON lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND lla.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) )
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 ";
						
					$sqlSum = " SELECT ".implode(",",$field4)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla
						ON lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) 
						AND lla.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) )
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' ";
						
					$sqlPro = " SELECT 'Promedio de Compromisos Por Asesor' AS 'VALOR_EFECTIVO' ,".implode(",",$field5)." FROM ( $sqlSum ) t1 , ( $sqlACP ) t2 ";
					
					$sqlMeta = " SELECT 'Meta (50% de CEF)' AS 'VALOR_EFECTIVO', ".implode(",",$field6)." 
						FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
						ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
						WHERE clicar.idcartera IN ( ".$idcartera." ) AND fin.idcarga_final IN ( ".$idcarga." ) 
						AND DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
						AND lla.tipo = 'LL' AND lla.estado = 1 ";
						
					$sqlCompromisos = " SELECT '% de compromisos' AS 'VALOR_EFECTIVO', ".implode(",",$field7)." FROM ( $sqlSum ) t1, ( $sqlMeta ) t2 ";
					
					
					$data = $daoProcedure->executeQueryReturn($sql);
					
					$Mdata = $daoProcedure->executeQueryReturn($sqlCarga." UNION ".$sqlACP." UNION ".$sqlPro." UNION ".$sqlMeta." UNION ".$sqlCompromisos );
					
					echo json_encode(array('Ini'=>$data,'MData'=>$Mdata));
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			}
		}
		
	}


?>