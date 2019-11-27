<?php

	class servletFiles extends CommandController {
		
		public function doPost ( ) {

			switch ($_POST["action"]) {
				case 'upload_file':
					
					$nombre_servicio = $_POST['nombre_servicio'];
					$router = $_POST['router'];
					$file_name = $_POST['filename'];

					$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
					$router = $confCobrast['ruta_cobrast']['document_root_cobrast'].'/'.$confCobrast['ruta_cobrast']['nombre_carpeta'].'/documents/my_files/'.$nombre_servicio.$router;

					if( $rs = @opendir( $router ) ) {
						
						if( @move_uploaded_file( $_FILES[$file_name]['tmp_name'], $router.$_FILES[$file_name]['name'] ) ){
							echo json_encode(array('rst'=>true,'msg'=>'Archivo subido correctamente'));
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));
						}

						@closedir($rs);
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Problemas al intentar abrir archivo'));
					}

				break;
				case 'create_directory':
					
					$nombre_directorio = $_POST['data']['nombre'];
					$nombre_servicio = $_POST['data']['nombre_servicio'];
					$router = $_POST['data']['router'];

					$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
					$router = $confCobrast['ruta_cobrast']['document_root_cobrast'].'/'.$confCobrast['ruta_cobrast']['nombre_carpeta'].'/documents/my_files/'.$nombre_servicio.$router.$nombre_directorio;

					if( $rs = @opendir($router) ) {
						echo json_encode(array('rst'=>false,'msg'=>'Directorio ya existe'));
						@closedir($rs);
					}else{
						if( mkdir($router) ){
							echo json_encode(array('rst'=>true,'msg'=>'Directorio creado correctamente'));
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Error al crear directorio'));
						}
					}

				break;
				case 'delete_file':
					
					$nombre_servicio = $_POST['data']['nombre_servicio'];
					$router = $_POST['data']['router'];
					$file = $_POST['data']['file'];

					$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
					$router = $confCobrast['ruta_cobrast']['document_root_cobrast'].'/'.$confCobrast['ruta_cobrast']['nombre_carpeta'].'/documents/my_files/'.$nombre_servicio.$router.$file;

					if( file_exists($router) ) {
						
						if( unlink($router) ) {
							echo json_encode(array('rst'=>true,'msg'=>'Archivo eliminado correctamente'));
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar archivo'));
						}

					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Archivo no existe'));
					}

				break;
				case 'delete_directory':
					
					$nombre_servicio = $_POST['data']['nombre_servicio'];
					$router = $_POST['data']['router'];
					$directorio = $_POST['data']['directorio'];

					$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
					$router = $confCobrast['ruta_cobrast']['document_root_cobrast'].'/'.$confCobrast['ruta_cobrast']['nombre_carpeta'].'/documents/my_files/'.$nombre_servicio.$router.$directorio;

					function trash_three ( $router ) {
						if( $rs = opendir($router) ) { 
							while ( ( $archivo = readdir($rs) ) !== false ) {
								if( $archivo!='.' && $archivo!='..' ) {
									$tipo = filetype($router.'/'.$archivo);
									if( $tipo == 'dir' ) {
										trash_three( $router.'/'.$archivo );
										if( @rmdir($router.'/'.$archivo) ) {
											
										}else{
											echo json_encode(array('rst'=>false,'msg'=>'Error al tratar de eliminar directorio'));
											exit();
										}
									}else{
										if( @unlink($router.'/'.$archivo) ) {
											
										}else{
											echo json_encode(array('rst'=>false,'msg'=>'Error al tratar de eliminar archivos del directorio'));
											exit();
										}
									}
								}
							}
							closedir($rs);
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Directorio no existe'));
						}

					}

					if( is_dir($router) ) {
						
						trash_three($router);
						if( rmdir($router) ) {
							echo json_encode(array('rst'=>true,'msg'=>'Directorio eliminado correctamente'));
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar directorio'));
						}
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Archivo a eliminar no es un directorio'));
					}

				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}

		}
		public function doGet ( ) {

			switch ($_GET["action"]) {
				case 'read_directory':
					
					$nombre_servicio = $_GET['data']['nombre_servicio'];
					$router = $_GET['data']['router'];
					$directorio = $_GET['data']['directorio'];
					
					$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
					$router = $confCobrast['ruta_cobrast']['document_root_cobrast'].'/'.$confCobrast['ruta_cobrast']['nombre_carpeta'].'/documents/my_files/'.$nombre_servicio.$router.$directorio;

					$data = array();
                                        
					if( is_dir($router) ) {
						
						if( $gd = opendir($router) ) {

							while( ($archivo = readdir($gd) ) !== false ) {
								if( $archivo!='.' && $archivo!='..' ) {
									$tipo = filetype($router.'/'.$archivo);
									if( $tipo == 'dir' ) {
										array_push($data,array("nombre"=>$archivo,"tipo"=>'dir'));
									}else{
										$pathinfo = pathinfo($router.'/'.$archivo);
										array_push($data,array("nombre"=>$archivo,"tipo"=>$pathinfo['extension']));
									}
								}
								
							}
							closedir($gd);
						}

					}
					
					echo json_encode(array("rst"=>true,"data"=>$data));

				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}

		}

	}

?>