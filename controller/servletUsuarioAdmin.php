<?php

	class servletUsuarioAdmin extends CommandController {
		
		public function doGet ( ) {
			$daoTipoUsuario=DAOFactory::getDAOTipoUsuario('maria');
			$daoPrivilegio=DAOFactory::getDAOPrivilegio('maria');
			$daoServicio=DAOFactory::getDAOServicio('maria');
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			$daoUsuario=DAOFactory::getDAOUsuario('maria');
			$daoUsuarioServicio=DAOFactory::getDAOUsuarioServicio('maria');
			switch($_GET['action']){
				case 'load_servicio_usuario':
					$dtoUsuarioServicio = new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_GET['Id']);
					echo json_encode($daoUsuarioServicio->queryById($dtoUsuarioServicio));
				break;
				case 'ListarPrivilegios':
					echo json_encode($daoPrivilegio->queryAdmin());
				break;
				case 'ListarTipoUsuario':
					echo json_encode($daoTipoUsuario->queryAdmin());
				break;
				case 'ListarServicio':
					echo json_encode($daoServicio->queryIdNameAll());
				break;
				case 'load_data_usuario':
					$dtoUsuario=new dto_usuario ;
					$dtoUsuario->setId($_GET['Usuario']);
					echo json_encode($daoUsuario->queryById($dtoUsuario));
				break;
				case 'jqgrid_usuarios':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					$searchString = @$_GET['searchString'];
					$searchField = @$_GET['searchField'];
					$querySearch = "";
					$is_search = settype($_GET['_search'],'bool');
					
					if( $is_search ) {
						
						if( @$_GET['searchOper'] == 'eq' ) { // =
							$querySearch = " AND ".$searchField." = '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'ne' ) { // !=
							$querySearch = " AND ".$searchField." != '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'lt' ) { // menor que
							$querySearch = " AND ".$searchField." < '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'le' ) { // menor o igual
							$querySearch = " AND ".$searchField." <= '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'gt' ) { // mayor
							$querySearch = " AND ".$searchField." > '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'ge' ) { // mayor o igual
							$querySearch = " AND ".$searchField." >= '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'bw' ) { // empiece por
							$querySearch = " AND ".$searchField." LIKE '".$searchString."%' ";
						}else if( @$_GET['searchOper'] == 'bn' ) { // no empiece por
							$querySearch = " AND ".$searchField." NOT LIKE '".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'in' ) { // esta en 
							$querySearch = " AND ".$searchField." IN ('".$searchString."') ";
						}else if( @$_GET['searchOper'] == 'ni' ) { // no esta en
							$querySearch = " AND ".$searchField." NOT IN ('".$searchString."') ";
						}else if( @$_GET['searchOper'] == 'ew' ) { // termina por
							$querySearch = " AND ".$searchField." LIKE '%".$searchString."'";
						}else if( @$_GET['searchOper'] == 'en' ) { // no termina por
							$querySearch = " AND ".$searchField." NOT LIKE '%".$searchString."' ";
						}else if( @$_GET['searchOper'] == 'cn' ) { // contiene
							$querySearch = " AND ".$searchField." LIKE '%".$searchString."%' ";
						}else if( @$_GET['searchOper'] == 'nc' ) { // no contiene
							$querySearch = " AND ".$searchField." NOT LIKE '%".$searchString."%' ";
						}
						
					}
					
					$search="";
					
					$param=array();
					//$param[':cartera']=@$_GET['Cartera'];
					//$param[':servicio']=@$_GET['Servicio'];

					if( isset($_GET['nombre']) ) {
						if( trim($_GET['nombre'])!='' ) {
							$search.=" AND CONCAT_WS(' ',nombre,paterno,materno) LIKE '%".$_GET['nombre']."%' ";
						}
					}
					if( isset($_GET['dni']) ) {
						if( trim($_GET['dni'])!='' ) {
							$search.=" AND TRIM(dni) = :dni ";
							$param[':dni']=trim($_GET['dni']);
						}
					}
					if( isset($_GET['email']) ) {
						if( trim($_GET['email'])!='' ) {
							$search.=" AND TRIM(email) = :email ";
							$param[':email']=trim($_GET['email']);
						}
					}
					if( isset($_GET['fecha_registro']) ) {
						if( trim($_GET['fecha_registro'])!='' ) {
							$search.=" AND DATE(fecha_creacion) = :fecha_registro ";
							$param[':fecha_registro']=trim($_GET['fecha_registro']);
						}
					}
	
					if(!$sidx)$sidx=1 ;
					//$row=$daoJqgrid->JQGRIDCountUsuarioAll( );
					$row=$daoJqgrid->JQGRIDCountUsuarioAll( $search, $param, $querySearch );

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
						$limit = 0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					//$data=$daoJqgrid->JQGRIDRowsUsuarioAll($sidx,$sord,$start,$limit);
					$data=$daoJqgrid->JQGRIDRowsUsuarioAll($sidx,$sord,$start,$limit,$search,$param,$querySearch);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario'],"cell"=>array($data[$i]['codigo'],$data[$i]['nombre'],$data[$i]['dni'],$data[$i]['email'],$data[$i]['fecha_registro'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'jqgrid_servicios_usuario':
					if(!isset($_GET["Usuario"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['Usuario']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					$dtoUsuario=new dto_usuario ;
					$dtoUsuario->setId($_GET['Usuario']); 
	
					if(!$sidx)$sidx=1 ;
					$row=$daoJqgrid->JQGRIDCountServicesOfUser( $dtoUsuario );

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsServicesOfUser($sidx,$sord,$start,$limit,$dtoUsuario);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario_servicio'],"cell"=>array($data[$i]['servicio'],$data[$i]['tipo_usuario'],$data[$i]['privilegio'],$data[$i]['fecha_inicio'],$data[$i]['fecha_fin'],$data[$i]['fecha_registro'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			}
			
		}
		
		public function doPost ( ) {
			$daoUsuario=DAOFactory::getDAOUsuario('maria');
			$daoUsuarioServicio=DAOFactory::getDAOUsuarioServicio('maria');
			switch($_POST['action']){
				case 'save_usuario':
					$dtoUsuario = new dto_usuario ;
					$dtoUsuario->setNombre($_POST['Nombre']);
					$dtoUsuario->setPaterno($_POST['Paterno']);
					$dtoUsuario->setMaterno($_POST['Materno']);
					$dtoUsuario->setEmail($_POST['Email']);
					$dtoUsuario->setDni($_POST['Dni']);
					$dtoUsuario->setCodigo($_POST['Codigo']);
					$dtoUsuario->setCelular($_POST['Celular']);
					$dtoUsuario->setTelefono($_POST['Telefono']);
					$dtoUsuario->setTelefono2($_POST['Telefono2']);
					$dtoUsuario->setDireccion($_POST['Direccion']);
					$dtoUsuario->setFechaNacimiento($_POST['FechaNacimiento']);
					$dtoUsuario->setTipoTrabajo($_POST['TipoTrabajo']);
					$dtoUsuario->setEstadoCivil($_POST['EstadoCivil']);
					$dtoUsuario->setGenero($_POST['Genero']);
					$dtoUsuario->setIsPlanilla($_POST['Planilla']);
					$dtoUsuario->setClave($_POST['Clave']);
					$dtoUsuario->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$checkDNI=$daoUsuario->checkDNIexists($dtoUsuario);
					if( $checkDNI[0]['COUNT']==0 ) {
						echo ($daoUsuario->insertDataCreation($dtoUsuario))?json_encode(array('rst'=>true,'msg'=>'Usuario grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar usuario'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Dni ingresado ya existe'));
					}
					
				break;
				case 'update_usuario':
				
					$dtoUsuario = new dto_usuario ;
					$dtoUsuario->setId($_POST['Id']);
					$dtoUsuario->setNombre($_POST['Nombre']);
					$dtoUsuario->setPaterno($_POST['Paterno']);
					$dtoUsuario->setMaterno($_POST['Materno']);
					$dtoUsuario->setEmail($_POST['Email']);
					$dtoUsuario->setDni($_POST['Dni']);
					$dtoUsuario->setCodigo($_POST['Codigo']);
					$dtoUsuario->setCelular($_POST['Celular']);
					$dtoUsuario->setTelefono($_POST['Telefono']);
					$dtoUsuario->setTelefono2($_POST['Telefono2']);
					$dtoUsuario->setDireccion($_POST['Direccion']);
					$dtoUsuario->setFechaNacimiento($_POST['FechaNacimiento']);
					$dtoUsuario->setTipoTrabajo($_POST['TipoTrabajo']);
					$dtoUsuario->setEstadoCivil($_POST['EstadoCivil']);
					$dtoUsuario->setGenero($_POST['Genero']);
					$dtoUsuario->setIsPlanilla($_POST['Planilla']);
					$dtoUsuario->setClave($_POST['Clave']);
					$dtoUsuario->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					$checkDNI=$daoUsuario->checkDNIexistsUserExists($dtoUsuario);
					if( $checkDNI[0]['COUNT']==0 ) {
						echo ($daoUsuario->updateDataModification($dtoUsuario))?json_encode(array('rst'=>true,'msg'=>'Usuario actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar usuario'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Dni ingresado ya existe'));
					}
					
				break;
				case 'delete_usuario':
					
					$dtoUsuario = new dto_usuario ;
					$dtoUsuario->setId($_POST['Id']);
					$dtoUsuario->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					echo ($daoUsuario->delete($dtoUsuario))?json_encode(array('rst'=>true,'msg'=>'Usuario eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar usuario'));
					
				break;
				case 'insert_usuario_servicio':
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setIdUsuario($_POST['Usuario']);
					$dtoUsuarioServicio->setIdServicio($_POST['Servicio']);
					$dtoUsuarioServicio->setIdPrivilegio($_POST['Privilegio']);
					$dtoUsuarioServicio->setIdTipoUsuario($_POST['TipoUsuario']);
					$dtoUsuarioServicio->setFechaInicio($_POST['FechaInicio']);
					$dtoUsuarioServicio->setFechaFin($_POST['FechaFin']);
					$dtoUsuarioServicio->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$checkServicio=$daoUsuarioServicio->checkUsuarioServicio($dtoUsuarioServicio);
					if( $checkServicio[0]['COUNT']==0 ) {
						echo ($daoUsuarioServicio->insertDataCreation($dtoUsuarioServicio))?json_encode(array('rst'=>true,'msg'=>'Servicio agregado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al agregar servicio'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Servicio ya fue agregado al usuario seleccionado')); 
					}
					
				break;
				case 'update_usuario_servicio':
				
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_POST['Id']);
					$dtoUsuarioServicio->setIdUsuario($_POST['Usuario']);
					$dtoUsuarioServicio->setIdServicio($_POST['Servicio']);
					$dtoUsuarioServicio->setIdPrivilegio($_POST['Privilegio']);
					$dtoUsuarioServicio->setIdTipoUsuario($_POST['TipoUsuario']);
					$dtoUsuarioServicio->setFechaInicio($_POST['FechaInicio']);
					$dtoUsuarioServicio->setFechaFin($_POST['FechaFin']);
					$dtoUsuarioServicio->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					$checkServicio=$daoUsuarioServicio->checkUsuarioServicio2($dtoUsuarioServicio);
					if( $checkServicio[0]['COUNT']==0 ) {
						echo ($daoUsuarioServicio->updateDataModification($dtoUsuarioServicio))?json_encode(array('rst'=>true,'msg'=>'Servicio actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar servicio'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Servicio ya fue agregado al usuario seleccionado')); 
					}
				break;
				case 'delete_usuario_servicio':
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_POST['Id']);
					$dtoUsuarioServicio->setUsuarioModificacion($_POST['UsuarioModificacion']);
					echo ($daoUsuarioServicio->delete($dtoUsuarioServicio))?json_encode(array('rst'=>true,'msg'=>'Servicio eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar servicio'));
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			}
			
		}
		
	}

?>