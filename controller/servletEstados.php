<?php

	class servletEstados extends CommandController {
		
		public function doPost ( ) {
			$daoEstadoTransaccion=DAOFactory::getDAOEstadoTransaccion('maria');
			$daoPesoTransaccion=DAOFactory::getDAOPesoTransaccion('maria');
			switch($_POST['action']){
				case 'insert_estado':
					$dtoEstadoTransaccion = new dto_estado_transaccion ;
					$dtoEstadoTransaccion->setIdServicio($_POST['Servicio']);
					$dtoEstadoTransaccion->setIdTipoTransaccion($_POST['TipoTransaccion']);
					$dtoEstadoTransaccion->setNombre($_POST['Nombre']);
					$dtoEstadoTransaccion->setPeso($_POST['Peso']);
					$dtoEstadoTransaccion->setDescripcion($_POST['Descripcion']);
					$dtoEstadoTransaccion->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$checkPeso=$daoEstadoTransaccion->checkPeso($dtoEstadoTransaccion);
					if( $checkPeso[0]['COUNT']>0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe un estado con peso '.$_POST['Peso']));
					}else{
						echo ($daoEstadoTransaccion->insert($dtoEstadoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Estado grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar estado'));
					}
					
				break;
				case 'update_estado':
					$dtoEstadoTransaccion = new dto_estado_transaccion ;
					$dtoEstadoTransaccion->setId($_POST['Id']);
					$dtoEstadoTransaccion->setIdTipoTransaccion($_POST['TipoTransaccion']);
					$dtoEstadoTransaccion->setNombre($_POST['Nombre']);
					$dtoEstadoTransaccion->setPeso($_POST['Peso']);
					$dtoEstadoTransaccion->setDescripcion($_POST['Descripcion']);
					$dtoEstadoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					$checkPeso=$daoEstadoTransaccion->checkPeso($dtoEstadoTransaccion);
					if( $checkPeso[0]['COUNT']>0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe un estado con peso '.$_POST['Peso']));
					}else{
						echo ($daoEstadoTransaccion->update($dtoEstadoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Estado actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar estado'));
					}
					
				break;
				case 'delete_estado':
					$dtoEstadoTransaccion = new dto_estado_transaccion ;
					$dtoEstadoTransaccion->setId($_POST['Id']);
					$dtoEstadoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					echo ($daoEstadoTransaccion->delete($dtoEstadoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Estado eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar estado'));
				break;
				case 'insert_prioridad':
					$dtoPesoTransaccion = new dto_peso_transaccion ;
					$dtoPesoTransaccion->setIdEstadoTransaccion($_POST['EstadoTransaccion']);
					$dtoPesoTransaccion->setPeso($_POST['Peso']);
					$dtoPesoTransaccion->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$checkPeso=$daoPesoTransaccion->checkPeso($dtoPesoTransaccion);
					if( $checkPeso[0]['COUNT']>0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe prioridad '.$_POST['Peso']));
					}else{
						echo ($daoPesoTransaccion->insert($dtoPesoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Prioridad grabada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar prioridad'));
					}
					
				break;
				case 'update_prioridad':
					$dtoPesoTransaccion = new dto_peso_transaccion ;
					$dtoPesoTransaccion->setId($_POST['Id']);
					$dtoPesoTransaccion->setIdEstadoTransaccion($_POST['EstadoTransaccion']);
					$dtoPesoTransaccion->setPeso($_POST['Peso']);
					$dtoPesoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					$checkPeso=$daoPesoTransaccion->checkPeso($dtoPesoTransaccion);
					if( $checkPeso[0]['COUNT']>0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe prioridad '.$_POST['Peso']));
					}else{
						echo ($daoPesoTransaccion->update($dtoPesoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Prioridad actualizar correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar prioridad'));
					}
					
				break;
				case 'delete_prioridad':
					$dtoPesoTransaccion = new dto_peso_transaccion ;
					$dtoPesoTransaccion->setId($_POST['Id']);
					$dtoPesoTransaccion->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					echo ($daoPesoTransaccion->delete($dtoPesoTransaccion))?json_encode(array('rst'=>true,'msg'=>'Prioridad eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar prioridad'));
				break;
				default: 
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));  
				;
			}
			
		}
		
		public function doGet ( ) {
			$daoTipoTransaccion=DAOFactory::getDAOTipoTransaccion('maria');
			$daoEstadoTransaccion=DAOFactory::getDAOEstadoTransaccion('maria');
			$daoPesoTransaccion=DAOFactory::getDAOPesoTransaccion('maria');
			$daoJqgrid=DAOFactory::getDAOJqgrid();
			switch($_GET['action']){
				case 'getParamPrioridad':
					$dtoPesoTransaccion = new dto_peso_transaccion ;
					$dtoPesoTransaccion->setId($_GET['Id']);
					
					echo json_encode($daoPesoTransaccion->queryById($dtoPesoTransaccion)); 
					
				break;
				case 'ListarTipoTransaccion':
					echo json_encode($daoTipoTransaccion->query());
				break;
				case 'getParamEstado':
					$dtoEstadoTransaccion = new dto_estado_transaccion ;
					$dtoEstadoTransaccion->setId($_GET['Id']);
					echo json_encode($daoEstadoTransaccion->queryById($dtoEstadoTransaccion));
				break;
				case 'jqgrid_estado':
					if(!isset($_GET["Servicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['Servicio']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					$dtoServicio=new dto_servicio ;
					$dtoServicio->setId($_GET['Servicio']);

					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountEstadoTransaccion($dtoServicio);

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

					$data=$daoJqgrid->JQGRIDRowsEstadoTransaccion($sidx, $sord, $start, $limit, $dtoServicio);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idestado_transaccion'],"cell"=>array(
																							$data[$i]['nombre'],
																							$data[$i]['peso'],
																							$data[$i]['tipo'],
																							$data[$i]['fecha_registro'],
																							$data[$i]['descripcion']
																							)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'jqgrid_prioridad':
					if(!isset($_GET["EstadoTransaccion"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['EstadoTransaccion']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}

					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					$dtoEstadoTransaccion=new dto_estado_transaccion ;
					$dtoEstadoTransaccion->setId($_GET['EstadoTransaccion']);

					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountPrioridadTransaccion($dtoEstadoTransaccion);

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

					$data=$daoJqgrid->JQGRIDRowsPrioridadTransaccion($sidx, $sord, $start, $limit, $dtoEstadoTransaccion);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idpeso_transaccion'],"cell"=>array(
																							$data[$i]['peso'],
																							$data[$i]['fecha_registro']
																							)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				default: 
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));  
				;
			}
		}
		
		
		
	} 

?>