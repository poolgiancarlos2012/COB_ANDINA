<?php

	class servletCartera extends CommandController {
		
		public function doPost ( ) {
			$daoCartera = DAOFactory::getDAOCartera('maria');
			switch($_POST['action']){
				case 'update_meta_fecha':
					
					$usuario_modificacion = $_POST['usuario_modificacion'];
					$idcartera = $_POST['id'];
					$nombre_cartera = trim( $_POST['car_nombre_cartera'] );
					$fecha_inicio = ( trim( $_POST['car_fecha_inicio'] ) == '' ) ? NULL : trim( $_POST['car_fecha_inicio'] ) ;
					$fecha_fin = ( trim( $_POST['car_fecha_fin'] ) == '' ) ? NULL : trim( $_POST['car_fecha_fin'] ) ;
					$meta_cliente = $_POST['car_meta_cliente'];
					$meta_cuenta = $_POST['car_meta_cuenta'];
					$meta_monto = $_POST['car_meta_monto'];
					$operacion = $_POST['oper'];
					$flag_provincia=$_POST['flag_provincia'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					$dtoCartera->setNombreCartera($nombre_cartera);
					$dtoCartera->setFechaInicio($fecha_inicio);
					$dtoCartera->setFechaFin($fecha_fin);
					$dtoCartera->setMetaCliente($meta_cliente);
					$dtoCartera->setMetaCuenta($meta_cuenta);
					$dtoCartera->setMetaMonto($meta_monto);
					$dtoCartera->setUsuarioModificacion($usuario_modificacion);
					
					switch( $operacion ){
						case 'edit':
							
							echo ( $daoCartera->UpdateMetaFecha($dtoCartera,$flag_provincia))?json_encode(array('rst'=>true,'msg'=>'Metas y fechas actualizadas correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar metas'));
							
						break;
					}
					
				break;
				case 'update_meta':
				
					$usuario_modificacion = $_POST['usuario_modificacion'];
					$idcartera = $_POST['id'];
					$meta_cliente = $_POST['car_meta_cliente'];
					$meta_cuenta = $_POST['car_meta_cuenta'];
					$operacion = $_POST['oper'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					$dtoCartera->setMetaCliente($meta_cliente);
					$dtoCartera->setMetaCuenta($meta_cuenta);
					$dtoCartera->setUsuarioModificacion($usuario_modificacion);
					
					switch( $operacion ){
						case 'edit':
							
							echo ( $daoCartera->UpdateMeta($dtoCartera))?json_encode(array('rst'=>true,'msg'=>'Metas actualizadas correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar metas'));
							
						break;
					}
					
				break;
				case 'delete':
					
					$usuario_modificacion = $_POST['usuario_modificacion'];
					$idcartera = $_POST['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setUsuarioModificacion($usuario_modificacion);
					$dtoCartera->setId($idcartera);
					
					echo ( $daoCartera->delete($dtoCartera))?json_encode(array('rst'=>true,'msg'=>'Campañas eliminadas correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar campañas'));
					
				break;
				case 'active':
					
					$usuario_modificacion = $_POST['usuario_modificacion'];
					$idcartera = $_POST['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setUsuarioModificacion($usuario_modificacion);
					$dtoCartera->setId($idcartera);
					
					echo ( $daoCartera->active($dtoCartera))?json_encode(array('rst'=>true,'msg'=>'Campañas activadas correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al activar campañas'));
					
				break;
				case 'desactive':
					
					$usuario_modificacion = @$_POST['usuario_modificacion'];
					$idcartera = $_POST['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setUsuarioModificacion($usuario_modificacion);
					$dtoCartera->setId($idcartera);
					
					echo ( $daoCartera->desactive($dtoCartera))?json_encode(array('rst'=>true,'msg'=>'Campañas desactivadas correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al desactivar campañas'));
				
				break;
				default : 
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}
			
		}
		
		public function doGet ( ) {
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			$daoCartera = DAOFactory::getDAOCartera('maria');
			switch($_GET['action']){
				case 'ListarMetaData':
					
					$idcartera = $_GET['cartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode( $daoCartera->queryCarteraMetaData( $dtoCartera ) );
					
				break;
				case 'jqgrid_gestiones_servicio':
					
					if(!isset($_GET["idservicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( $_GET['idservicio']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					/********/
					$where = "";
					$param = array();
					$where = " AND cam.idservicio = :servicio ";
					$param[':servicio'] = trim($_GET['idservicio']);
					
					if( isset($_GET['cam_nombre']) ) {
						if( trim($_GET['cam_nombre'])!='' ) {
							$where.=" AND cam.nombre LIKE :campania ";
							$param[':campania'] = '%'.trim($_GET['cam_nombre']).'%';
						}
					}
					if( isset($_GET['car_nombre_cartera']) ) {
						if( trim($_GET['car_nombre_cartera'])!='' ) {
							$where.=" AND car.nombre_cartera LIKE :cartera ";
							$param[':cartera'] = '%'.trim($_GET['car_nombre_cartera']).'%';
						}
					}
					if( isset($_GET['car_fecha_carga']) ) {
						if( trim($_GET['car_fecha_carga'])!='' ) {
							$where.=" AND DATE( car.fecha_carga ) = :fecha_carga ";
							$param[':fecha_carga'] = trim($_GET['car_fecha_carga']);
						}
					}
					if( isset($_GET['car_fecha_inicio']) ) {
						if( trim($_GET['car_fecha_inicio'])!='' ) {
							$where.=" AND TRIM(car.fecha_inicio) = :fecha_inicio ";
							$param[':fecha_inicio'] = trim($_GET['car_fecha_inicio']);
						}
					}
					if( isset($_GET['car_fecha_fin']) ) {
						if( trim($_GET['car_fecha_fin'])!='' ) {
							$where.=" AND TRIM(car.fecha_fin) = :fecha_fin ";
							$param[':fecha_fin'] = trim($_GET['car_fecha_fin']);
						}
					}
					if( isset($_GET['car_cantidad']) ) {
						if( trim($_GET['car_cantidad'])!='' ) {
							$where.=" AND car.cantidad = :cantidad ";
							$param[':cantidad'] = trim($_GET['car_cantidad']);
						}
					}
					if( isset($_GET['car_status']) ) {
						if( trim($_GET['car_status'])!='' ) {
							$where.=" AND car.status LIKE :status ";
							$param[':status'] = '%'.trim($_GET['car_status']).'%';
						}
					}
					if( isset($_GET['flag_cartera']) ) {
						if( trim($_GET['flag_cartera'])!='' ) {
							$where.=" AND car.flag_provincia LIKE :status ";
							$param[':flag_provincia'] = '%'.trim($_GET['flag_provincia']).'%';
						}
					}					
					/********/
					
					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountGestionesPorServicio($where,$param);
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
						$limit=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsGestionesPorServicio($sidx, $sord, $start, $limit, $where, $param );
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						/*array_push($dataRow, array("id"=>$data[$i]['idcartera'],"cell"=>array(
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['campania'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['nombre_cartera'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['fecha_carga'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['fecha_inicio'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['fecha_fin'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['status'].'</pre>',
																							  $data[$i]['meta_cliente'],$data[$i]['meta_cuenta'],
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['registros'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['clientes'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['cuenta'].'</pre>',
																							  '<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['detalle'].'</pre>'
																							  )
													)
									);*/
									
						array_push($dataRow, array("id"=>$data[$i]['idcartera'],"cell"=>array(
																							  $data[$i]['campania'],
																							  $data[$i]['nombre_cartera'],
																							  $data[$i]['fecha_carga'],
																							  $data[$i]['fecha_inicio'],
																							  $data[$i]['fecha_fin'],
																							  $data[$i]['status'],
																							  $data[$i]['meta_cliente'],
																							  $data[$i]['meta_cuenta'],
																							  $data[$i]['meta_monto'],
																							  $data[$i]['registros'],
																							  $data[$i]['clientes'],
																							  $data[$i]['cuenta'],
																							  $data[$i]['detalle'],
																							  $data[$i]['flag_provincia'],
																							  )
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
					
				break;
				default : 
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}
			
		}
	
	}

?>