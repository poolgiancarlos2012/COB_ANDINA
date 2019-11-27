<?php

    class servletUsuario extends CommandController {
        public function doPost ( ) {
            $daoUsuario=DAOFactory::getDAOUsuario('maria');
			$daoUsuarioServicio=DAOFactory::getDAOUsuarioServicio('maria');
			$daoNotificador = DAOFactory::getDAONotificador('maria');
            switch ($_POST['action']):
				case 'update_cluster_servicio':
					$idcluster=$_POST['Id'];
					$idservicio=$_POST['idServicio'];
					$nombre=$_POST['nombre'];
					$descripcion=$_POST['descripcion'];
					$estado=$_POST['estado'];
					$usumodif=$_POST['usuarioModificacion'];
					$checkServicio=$daoUsuarioServicio->checkClusterServicio($nombre,$idservicio,$idcluster);
					if( $checkServicio[0]['COUNT']==0 ) {
						echo ($daoUsuarioServicio->updateDataClusterServicio($idcluster,$nombre,$descripcion,$estado,$usumodif))?json_encode(array('rst'=>true,'msg'=>'Resgistro Actualizado')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe en este Servicio un Cluster con este nombre')); 
					}
				break;
				case 'insert_cluster_servicio':
					$idservicio=$_POST['idServicio'];
					$nombre=$_POST['nombre'];
					$descripcion=$_POST['descripcion'];
					$usucreate=$_POST['usuarioCreacion'];
					$checkServicio=$daoUsuarioServicio->checkClusterServicio2($nombre,$idservicio);
					if( $checkServicio[0]['COUNT']==0 ) {
						echo ($daoUsuarioServicio->insertDataClusterServicio($idservicio,$nombre,$descripcion,$usucreate))?json_encode(array('rst'=>true,'msg'=>'Resgistro Ingresado')):json_encode(array('rst'=>false,'msg'=>'Error al Ingresar Registro'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Ya existe en este Servicio un Cluster con este nombre')); 
					}
				break;
				case 'insert_cluster_servicio_operador':
					$idususer=$_POST['idususer'];
					$idcluster=$_POST['idcluster'];
					$usuarioCreacion=$_POST['usuarioCreacion'];
					$checkServicio=$daoUsuario->checkInsertClusterServicioOperador($idususer,$idcluster);
					if( $checkServicio[0]['COUNT']==0 ) {
						echo ($daoUsuario->insertClusterServicioOperador($idususer,$idcluster,$usuarioCreacion))?json_encode(array('rst'=>true,'msg'=>'Cluster Ingresado')):json_encode(array('rst'=>false,'msg'=>'Error al Ingresar Registro'));
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'El Operador ya tiene este Cluster')); 
					}
				break;
				case 'update_notificador':
					$idnotificador = $_POST['idnotificador'];
					$nombre = (trim($_POST['nombre'])=='')?NULL:trim($_POST['nombre']);
					$paterno = (trim($_POST['paterno'])=='')?NULL:trim($_POST['paterno']);
					$materno = (trim($_POST['materno'])=='')?NULL:trim($_POST['materno']);
					$telefono = (trim($_POST['telefono'])=='')?NULL:trim($_POST['telefono']);
					$direccion = (trim($_POST['direccion'])=='')?NULL:trim($_POST['direccion']);
					$correo = (trim($_POST['correo'])=='')?NULL:trim($_POST['correo']);
					$usuario_modificacion = (trim($_POST['usuario_modificacion'])=='')?NULL:trim($_POST['usuario_modificacion']);
					
					$dtoNotificador = new dto_notificador;
					$dtoNotificador->setId($idnotificador);
					$dtoNotificador->setNombre($nombre);
					$dtoNotificador->setPaterno($paterno);
					$dtoNotificador->setMaterno($materno);
					$dtoNotificador->setTelefono($telefono);
					$dtoNotificador->setDireccion($direccion);
					$dtoNotificador->setCorreo($correo);
					$dtoNotificador->setUsuarioModificacion($usuario_modificacion);
					
					echo ($daoNotificador->update($dtoNotificador))?json_encode(array('rst'=>true,'msg'=>'Notificador actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar notificador'));
					
				break;
				case 'change_state_ususerclu':
					$idususerclu = $_POST['idususerclu'];
					$usuario_modificacion = $_POST['usuario_modificacion'];
					
					echo ($daoUsuario->changeStateUsuSerClu($idususerclu,$usuario_modificacion))?json_encode(array('rst'=>true,'msg'=>'Estado Cambiado')):json_encode(array('rst'=>false,'msg'=>'Error al Cambiar Estado'));
				break;
				case 'delete_notificador':
					
					$idnotificador = (trim($_POST['idnotificador'])=='')?NULL:trim($_POST['idnotificador']);
					$usuario_modificacion = (trim($_POST['usuario_modificacion'])=='')?NULL:trim($_POST['usuario_modificacion']);
					
					$dtoNotificador = new dto_notificador;
					$dtoNotificador->setId($idnotificador);
					$dtoNotificador->setUsuarioModificacion($usuario_modificacion);
					
					echo ($daoNotificador->delete($dtoNotificador))?json_encode(array('rst'=>true,'msg'=>'Notificador elimado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar notificador'));
					
				break;
				case 'insert_notificador':
					
					$idservicio = $_POST['idservicio'];
					$nombre = (trim($_POST['nombre'])=='')?NULL:trim($_POST['nombre']);
					$paterno = (trim($_POST['paterno'])=='')?NULL:trim($_POST['paterno']);
					$materno = (trim($_POST['materno'])=='')?NULL:trim($_POST['materno']);
					$telefono = (trim($_POST['telefono'])=='')?NULL:trim($_POST['telefono']);
					$direccion = (trim($_POST['direccion'])=='')?NULL:trim($_POST['direccion']);
					$correo = (trim($_POST['correo'])=='')?NULL:trim($_POST['correo']);
					$usuario_creacion = (trim($_POST['usuario_cracion'])=='')?NULL:trim($_POST['usuario_creacion']);
					
					$dtoNotificador = new dto_notificador;
					$dtoNotificador->setIdServicio($idservicio);
					$dtoNotificador->setNombre($nombre);
					$dtoNotificador->setPaterno($paterno);
					$dtoNotificador->setMaterno($materno);
					$dtoNotificador->setTelefono($telefono);
					$dtoNotificador->setDireccion($direccion);
					$dtoNotificador->setCorreo($correo);
					$dtoNotificador->setUsuarioCreacion($usuario_creacion);
					
					echo ($daoNotificador->insert($dtoNotificador))?json_encode(array('rst'=>true,'msg'=>'Notificador grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar notificador'));
					
				break;
				case 'update_avatar':
					$idusuario = $_POST['idusuario'];
					$nombre = (trim($_POST['nombre'])=='')?NULL:trim($_POST['nombre']);
					$paterno = (trim($_POST['paterno'])=='')?NULL:trim($_POST['paterno']);
					$materno = (trim($_POST['materno'])=='')?NULL:trim($_POST['materno']);
					$email = (trim($_POST['email'])=='')?NULL:trim($_POST['email']);
					$dni = (trim($_POST['dni'])=='')?NULL:trim($_POST['dni']);
					$img_avatar = $_POST['img_avatar'];
					$clave = (trim($_POST['clave'])=='')?NULL:trim($_POST['clave']);
					
					if( $idusuario == '' ) {
						echo json_encode(array('rst'=>false,'msg'=>'Error en usuario'));
						exit();
					}
					
					$dtoUsuario = new dto_usuario ;
					$dtoUsuario->setId($idusuario);
					$dtoUsuario->setNombre($nombre);
                    $dtoUsuario->setPaterno($paterno);
                    $dtoUsuario->setMaterno($materno);
                    $dtoUsuario->setDni($dni);
                    $dtoUsuario->setEmail($email);
                    $dtoUsuario->setClave($clave);
                    $dtoUsuario->setUsuarioCreacion($idusuario);
					$dtoUsuario->setImgAvatar($img_avatar);
					
					$checkDNI=$daoUsuario->checkDNIexistsNotUser($dtoUsuario);
                    if($checkDNI[0]['countDNINotUser']>0){
                        echo json_encode(array('rst'=>false,'msg'=>'DNI ingresado ya existe'));
                    }else{
                        echo ($daoUsuario->updateAvatar($dtoUsuario))?json_encode(array('rst'=>true,'msg'=>'Usuario actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar usuario'));
                    }
					
				break;
                case 'save_usuario':
                    $dtoUsuario=new dto_usuario();
					$dtoUsuarioServicio=new dto_usuario_servicio();
					
                    $dtoUsuario->setNombre($_POST['Nombre']);
                    $dtoUsuario->setPaterno($_POST['Paterno']);
                    $dtoUsuario->setMaterno($_POST['Materno']);
                    $dtoUsuario->setDni($_POST['Dni']);
                    $dtoUsuario->setEmail($_POST['Email']);
                    $dtoUsuario->setClave($_POST['Clave']);
                    $dtoUsuario->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$dtoUsuarioServicio->setIdServicio($_POST['Servicio']);
					$dtoUsuarioServicio->setIdTipoUsuario($_POST['TipoUsuario']);
					$dtoUsuarioServicio->setIdPrivilegio($_POST['Privilegio']);
					$dtoUsuarioServicio->setFechaInicio($_POST['FechaInicio']);
					$dtoUsuarioServicio->setFechaFin($_POST['FechaFin']);
					
                    $checkDNI=$daoUsuario->checkDNIexists($dtoUsuario);
                    if($checkDNI[0]['COUNT']>0){
                        echo json_encode(array('rst'=>false,'msg'=>'DNI ingresado ya existe'));
                    }else{
                        echo ($daoUsuarioServicio->insertUsuarioServicio($dtoUsuarioServicio,$dtoUsuario))?json_encode(array('rst'=>true,'msg'=>'Usuario creado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al  crear usuario'));
                    }
                    
                break;
                case 'update_usuario':
                    $dtoUsuario=new dto_usuario ;
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					
					$dtoUsuario->setId($_POST['Id']);
                    $dtoUsuario->setNombre($_POST['Nombre']);
                    $dtoUsuario->setPaterno($_POST['Paterno']);
                    $dtoUsuario->setMaterno($_POST['Materno']);
                    $dtoUsuario->setDni($_POST['Dni']);
                    $dtoUsuario->setEmail($_POST['Email']);
                    $dtoUsuario->setClave($_POST['Clave']);
                    $dtoUsuario->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					$dtoUsuarioServicio->setIdServicio($_POST['Servicio']);
					$dtoUsuarioServicio->setId($_POST['UsuarioServicio']);
					$dtoUsuarioServicio->setIdTipoUsuario($_POST['TipoUsuario']);
					$dtoUsuarioServicio->setIdPrivilegio($_POST['Privilegio']);
					$dtoUsuarioServicio->setFechaInicio($_POST['FechaInicio']);
					$dtoUsuarioServicio->setFechaFin($_POST['FechaFin']);
					$dtoUsuarioServicio->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
                    $checkDNI=$daoUsuario->checkDNIexistsNotUser($dtoUsuario);
                    if($checkDNI[0]['countDNINotUser']>0){
                        echo json_encode(array('rst'=>false,'msg'=>'DNI ingresado ya existe'));
                    }else{
                        echo ($daoUsuarioServicio->updateUsuarioServicio($dtoUsuario,$dtoUsuarioServicio))?json_encode(array('rst'=>true,'msg'=>'Usuario actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar usuario'));
                    }
                    
                break;
                case 'delete_usuario':
				
                    $dto=new dto_usuario_servicio ;
					
                    $dto->setId($_POST['UsuarioServicio']);
                    $dto->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
                    echo ($daoUsuarioServicio->deleteUsuarioServicio($dto))?json_encode(array('rst'=>true,'msg'=>'Se retiro usuario del servicio ')):json_encode(array('rst'=>false,'msg'=>'Error al retirar usuario'));
                break;
            endswitch;
        }

        public function doGet ( ) {
			$daoServicio=DAOFactory::getDAOServicio('maria');
			$daoTipoUsuario=DAOFactory::getDAOTipoUsuario('maria');
			$daoPrivilegio=DAOFactory::getDAOPrivilegio('maria');
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			$daoUsuarioServicio=DAOFactory::getDAOUsuarioServicio('maria');
			$daoUsuario = DAOFactory::getDAOUsuario('maria');
			$daoNotificador = DAOFactory::getDAONotificador('maria');
            switch ($_GET['action']) :
				case 'load_data_cluster_servicio':
					$servicio=$_SESSION['cobrast']['idservicio']; 
					echo json_encode($daoUsuario->queryListarClusterByServicio($servicio));
				break;
				case 'load_mantenimiento_cluster':
					$idCluster=$_GET['Id'];
					echo json_encode($daoUsuarioServicio->queryClusterById($idCluster));
				break;
				case 'jqgrid_mantenimiento_cluster':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					//$dtoUsuario=new dto_usuario ;
					//$dtoUsuario->setId($_SESSION['cobrast']['idusuario_servicio']); 
					$servicio=$_SESSION['cobrast']['idservicio']; 
					
					if(!$sidx)$sidx=1 ;
					$row=$daoJqgrid->JQGRIDCountClusterOfServicio( $servicio );
					

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=1;
					}
	
					if($page>$total_pages) $page=$total_pages;
					$start=($page*$limit)-$limit;
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsClusterOfServicio($sidx,$sord,$start,$limit,$servicio);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcluster'],"cell"=>array($data[$i]['nombre'],$data[$i]['descripcion'],$data[$i]['estado'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'ListarNotificadorPorId':
					$idnotificador = $_GET['idnotificador'];
					$dtoNotificador = new dto_notificador ;
					$dtoNotificador->setId($idnotificador);
					
					echo json_encode($daoNotificador->queryById($dtoNotificador));
					
				break;
				case 'ListarNotificadores':
					$idservicio = $_GET['idservicio'];
					$dtoNotificador = new dto_notificador ;
					$dtoNotificador->setIdServicio($idservicio);
					
					echo json_encode($daoNotificador->queryByService($dtoNotificador));
					
				break;
				case 'ListarOperadorCluster':
					$idservicio = $_GET['idservicio'];
					echo json_encode($daoUsuario->queryByOperadorService($idservicio));
				break;
				case 'ListarOperadorClusterDetalle':
					$idusuario_servicio = $_GET['idusuario_servicio'];
					echo json_encode($daoUsuario->queryByDetalleClusterOperador($idusuario_servicio));
				break;
                case 'ListarServicio':
                	echo json_encode($daoServicio->queryIdName());
                break;
				case 'ListarPrivilegios':
					echo json_encode($daoPrivilegio->queryNotAdmin());
				break;
				case 'ListarTipoUsuario':
					echo json_encode($daoTipoUsuario->queryNotAdmin());
				break;
				case 'queryByUser':
					$idusuario = $_GET['idusuario'];
					if( $idusuario == '' ) {
						echo json_encode(array());
						exit();
					}
					$dtoUsuario = new dto_usuario ;
					$dtoUsuario->setId($idusuario);
					echo json_encode($daoUsuario->queryById($dtoUsuario));
				break;
				case 'DataById':
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_GET['UsuarioServicio']);
					echo json_encode($daoUsuarioServicio->queryUserById($dtoUsuarioServicio));
				break;
				case 'jqgrid_usuario_teleoperador_gestor_campo':
					
					if(!isset($_GET['Servicio'])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['Servicio']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$searchString = @$_GET['searchString'];
					$searchField = @$_GET['searchField'];
					$querySearch = "";
					
					if( $_GET['_search'] ) {
						
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
					$param[':servicio']=$_GET['Servicio'];

					if( isset($_GET['usuario']) ) {
						if( trim($_GET['usuario'])!='' ) {
							$search.=" AND CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) LIKE '%".$_GET['usuario']."%' ";
							//$param[':usuario']=$_GET['usuario'];
						}
					}
					if( isset($_GET['usu_dni']) ) {
						if( trim($_GET['usu_dni'])!='' ) {
							$search.=" AND TRIM(usu.dni) = :dni ";
							$param[':dni']=$_GET['usu_dni'];
						}
					}
					if( isset($_GET['usu_email']) ) {
						if( trim($_GET['usu_email'])!='' ) {
							$search.=" AND TRIM(usu.email) = :email ";
							$param[':email']=$_GET['usu_email'];
						}
					}
					if( isset($_GET['tipo_usuario']) ) {
						if( trim($_GET['tipo_usuario'])!='' ) {
							$search.=" AND tipo_usuario = :tipo_usuario ";
							$param[':tipo_usuario']=$_GET['tipo_usuario'];
						}
					}
					if( isset($_GET['privilegio']) ) {
						if( trim($_GET['privilegio'])!='' ) {
							$search.=" AND privilegio = :privilegio ";
							$param[':privilegio']=$_GET['privilegio'];
						}
					}
					if( isset($_GET['ususer_fecha_inicio']) ) {
						if( trim($_GET['ususer_fecha_inicio'])!='' ) {
							$search.=" AND ususer.fecha_inicio = :fecha_inicio ";
							$param[':fecha_inicio']=$_GET['ususer_fecha_inicio'];
						}
					}
					if( isset($_GET['ususer_fecha_fin']) ) {
						if( trim($_GET['ususer_fecha_fin'])!='' ) {
							$search.=" AND ususer.fecha_fin = :fecha_fin ";
							$param[':fecha_fin']=$_GET['ususer_fecha_fin'];
						}
					}
					if( isset($_GET['fecha_registro']) ) {
						if( trim($_GET['fecha_registro'])!='' ) {
							$search.=" AND DATE(usu.fecha_creacion) = :fecha_registro ";
							$param[':fecha_registro']=$_GET['fecha_registro'];
						}
					}
					
					//$dtoUsuarioServicio=new dto_usuario_servicio ;
					//$dtoUsuarioServicio->setIdServicio($_GET['Servicio']);
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
	
					if(!$sidx)$sidx=1 ;
					//$row=$daoJqgrid->JQGRIDCountUsuarioOperadoresActivosPorServicio($dtoUsuarioServicio);
					$row=$daoJqgrid->JQGRIDCountUsuarioOperadoresActivosPorServicio($search,$param,$querySearch);

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
					
					//$data=$daoJqgrid->JQGRIDRowsUsuarioOperadoresActivosPorServicio($sidx,$sord,$start,$limit,$dtoUsuarioServicio);
					$data=$daoJqgrid->JQGRIDRowsUsuarioOperadoresActivosPorServicio($sidx,$sord,$start,$limit,$search,$param,$querySearch);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario_servicio'],"cell"=>array(
																										$data[$i]['usuario'],
																										$data[$i]['dni'],
																										$data[$i]['email'],
																										$data[$i]['tipo_usuario'],
																										$data[$i]['privilegio'],
																										$data[$i]['fecha_inicio'],
																										$data[$i]['fecha_fin'],
																										$data[$i]['fecha_registro']
																										)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'jqgrid_usuario_administrador':
				
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
	
					if(!$sidx)$sidx=1 ;
					$row=$daoJqgrid->JQGRIDCountUsuarioAdminOperadoresActivosPorServicio( );

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsUsuarioAdminOperadoresActivosPorServicio($sidx,$sord,$start,$limit);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario'],"cell"=>array($data[$i]['usuario'],$data[$i]['email'],$data[$i]['servicio'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
            endswitch;
        }
    }

?>
