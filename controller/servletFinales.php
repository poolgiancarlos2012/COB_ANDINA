<?php

	class servletFinales extends CommandController {
	
		public function doPost() {
			$daoFinal = DAOFactory::getDAOFinal('maria');
			switch ($_POST["action"]) {
				case 'insert':
					
					$carga = (trim($_POST["Carga"])=='')?NULL:trim($_POST["Carga"]);
					$clase = (trim($_POST["Clase"])=='')?NULL:trim($_POST["Clase"]);
					$nivel = (trim($_POST["Nivel"])==0)?NULL:trim($_POST["Nivel"]);
					$tipo = (trim($_POST["Tipo"])==0)?NULL:trim($_POST["Tipo"]);
					$descripcion = (trim($_POST["Descripcion"])=='')?NULL:trim($_POST["Descripcion"]);
					$nombre = (trim($_POST["Nombre"])=='')?NULL:trim($_POST["Nombre"]);
					$usuario_creacion = (trim($_POST["UsuarioCreacion"])=='')?NULL:trim($_POST["UsuarioCreacion"]);

					$dto = new dto_final();
					$dto->setDescripcion($descripcion);
					$dto->setIdCargaFinal($carga);
					$dto->setIdClaseFinal($clase);
					$dto->setIdNivel($nivel);
					$dto->setIdTipoFinal($tipo);
					$dto->setNombre($nombre);
					$dto->setUsuarioCreacion($usuario_creacion);
					
					echo ($daoFinal->insert($dto))?json_encode(array('rst'=>true,'msg'=>'Final grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar final'));	
	
				break;
				case 'update':
				
					$carga = (trim($_POST["Carga"])=='')?NULL:trim($_POST["Carga"]);
					$clase = (trim($_POST["Clase"])=='')?NULL:trim($_POST["Clase"]);
					$nivel = (trim($_POST["Nivel"])==0)?NULL:trim($_POST["Nivel"]);
					$tipo = (trim($_POST["Tipo"])==0)?NULL:trim($_POST["Tipo"]);
					$descripcion = (trim($_POST["Descripcion"])=='')?NULL:trim($_POST["Descripcion"]);
					$nombre = (trim($_POST["Nombre"])=='')?NULL:trim($_POST["Nombre"]);
					$usuario_modificacion = (trim($_POST["UsuarioModificacion"])=='')?NULL:trim($_POST["UsuarioModificacion"]);
					$id = (trim($_POST["Id"])=='')?NULL:trim($_POST["Id"]);
				
					$dto = new dto_final();
					$dto->setDescripcion($descripcion);
					$dto->setIdCargaFinal($carga);
					$dto->setIdClaseFinal($clase);
					$dto->setIdNivel($nivel);
					$dto->setIdTipoFinal($tipo);
					$dto->setNombre($nombre);
					$dto->setUsuarioModificacion($usuario_modificacion);
					$dto->setId($id);
					
					echo ($daoFinal->update($dto))?json_encode(array('rst'=>true,'msg'=>'Final actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar final'));
					
				break;
				default:
					echo json_encode(array('rst'=>false,'Action'=>'Accion no encontrada'));
				;
			}
		}
	
		public function doGet() {
			$dao = DAOFactory::getDAOFinal('maria');
			switch ($_GET["action"]) {
				case 'jqgrid_final':
					$page = $_GET["page"];
					$limit = $_GET["rows"];
					$sidx = $_GET["sidx"];
					$sord = $_GET["sord"];
	
					!$sidx ? $sidx = 1 : '';
					
					$search = "";
					$param = array();
					if( isset($_GET['fin_idfinal']) ) {
						if( trim($_GET['fin_idfinal'])!='' ) {
							$search.=" AND fin.idfinal LIKE :idfinal ";  
							$param[":idfinal"]=trim($_GET['fin_idfinal']).'%';  
						}
					}
					if( isset($_GET['fin_nombre']) ) {
						if( trim($_GET['fin_nombre'])!='' ) {
							$search.=" AND fin.nombre LIKE :nombre ";  
							$param[":nombre"]=trim($_GET['fin_nombre']).'%';  
						}
					}
					if( isset($_GET['tipfin_nombre']) ) {
						if( trim($_GET['tipfin_nombre'])!='' ) {
							$search.=" AND tipfin.nombre LIKE :tipo_final ";  
							$param[":tipo_final"]=trim($_GET['tipfin_nombre']).'%';
						}
					}
					if( isset($_GET['clafin_nombre']) ) {
						if( trim($_GET['clafin_nombre'])!='' ) {
							$search.=" AND clafin.nombre LIKE :clase_final ";
							$param[":clase_final"]=trim($_GET['clafin_nombre']).'%';  
						}
					}
					if( isset($_GET['carfin_nombre']) ) {
						if( trim($_GET['carfin_nombre'])!='' ) {
							$search.=" AND carfin.nombre LIKE :carga_final ";
							$param[":carga_final"]=trim($_GET['carfin_nombre']).'%';  
						}
					}
					if( isset($_GET['nv_nombre']) ) {
						if( trim($_GET['nv_nombre'])!='' ) {
							$search.=" AND nv.nombre LIKE :nivel ";  
							$param[":nivel"]=trim($_GET['nv_nombre']).'%';  
						}
					}
					
					$row = $dao->COUNT( $search, $param );
					$count = $row[0]['COUNT'];
					if ($count > 0) {
						$total_pages = ceil($count / $limit);
					} else {
						$total_pages = 0;
						$limit = 0;
					}
	
					if ($page > $total_pages)
						$page = $total_pages;
	
					$start = $page * $limit - $limit;
	
					$response = array("page" => $page, "total" => $total_pages, "records" => $count);
	
					$data = $dao->queryJQGRID($sidx, $sord, $start, $limit, $search, $param);
					$dataRow = array();
					for ($i = 0; $i < count($data); $i++) {
						array_push($dataRow, array("id" => $data[$i]['idfinal'], "cell" => array(
																							$data[$i]['idfinal'], 
																							'<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['nombre'].'</pre>', 
																							$data[$i]['tipo_final'], 
																							$data[$i]['clase_final'], 
																							$data[$i]['carga_final'], 
																							$data[$i]['nivel']
																							)));
					}
					$response["rows"] = $dataRow;
					echo json_encode($response);
	
					break;
				case 'loadFinal':
					echo json_encode($dao->queryIdName());
				break;
				case 'buscar':
					echo json_encode($dao->buscarFinal($_GET["id"]));
				break;
				case 'getFinalById':
					$dtoFinal = new dto_final ;
					$dtoFinal->setId($_GET['Id']);
					
					echo json_encode($dao->queryById($dtoFinal));
				
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			}
		}
	
	}
?>
