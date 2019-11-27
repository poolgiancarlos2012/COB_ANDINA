<?php
	
	class servletDistribucion extends CommandController {
		public function doPost ( ) {
			$daoClienteCartera=DAOFactory::getDAOClienteCartera('maria');
			$daoDetalleCuenta = DAOFactory::getDAODetalleCuenta('maria');
			$daoDireccion = DAOFactory::getDAODireccion('maria');
			$daoZona = DAOFactory::getDAOZona('maria');
			switch ($_POST['action']) :
				case 'save_distribucion_pagos':
					
					$idcartera = $_POST['idcartera'];
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					$dataPago = ( $_POST['dataPagos'] == '' )? array() :explode(",",$_POST['dataPagos']);
					$modo = $_POST['modo'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);

					echo ($daoClienteCartera->generarDistribucionPagos($dtoCartera,$operadores,$dataPago,$modo))?json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
					
				break;
				case 'DistribucionSinGestion':
				
					$idcartera = $_POST['idcartera'];
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo ($daoClienteCartera->generarDistribucionSinGestion($dtoCartera,$operadores))?json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
					
				break;
				case 'RetirarTodocliente':
					
					$idusuario_servicio = $_POST['idusuario_servicio'];
					$idcartera = $_POST['idcartera'];
					
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setIdUsuarioServicio($idusuario_servicio);
					$dtoClienteCartera->setIdCartera($idcartera);
					
					echo ($daoClienteCartera->RetirarTodoClienteAsignadosUsuario($dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Clientes retirados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al retirar clientes'));
										
				break;
				case 'DistribucionConstante':
					
					$idcartera = $_POST['idcartera'];
					$idcartera_referencia = $_POST['idcartera_referencia'];
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo ($daoClienteCartera->DistribucionConstante($dtoCartera,$idcartera_referencia,$operadores))?json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
					
				break;
				case 'grabar_zonas':
					
					$idcartera = $_POST['idcartera'];
					$data = json_decode(str_replace("\\","",$_POST['data']),true);
					
					echo ($daoZona->updateByService( $idcartera, $data ))?json_encode(array('rst'=>true,'msg'=>'Datos grabados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar datos'));
					
				break;
				case 'grabar_departamentos_zonas':
					
					$idservicio = $_POST['idservicio'];
					$idcartera = $_POST['idcartera'];
					$usuario_creacion = $_POST['usuario_creacion'];
					
					echo ($daoZona->insertDepartamentos( $idservicio, $usuario_creacion, $idcartera ))?json_encode(array('rst'=>true,'msg'=>'Datos grabados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar datos'));
					
				break;
				case 'distribucion_montos_iguales':
					
					$idcartera = $_POST['cartera'];
					//$zona = (trim($_POST['zona'])=='0')?NULL:trim($_POST['zona']);
					$zona = trim($_POST['zona']);
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo ($daoClienteCartera->generarDistribucionMontosIguales($dtoCartera,$operadores,$zona))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion'));
					
				break;
				case 'save_cliente_especial':
					
					$idcliente_cartera = $_POST['idcliente_cartera'];
					$usuario_servicio = $_POST['usuario_servicio'];
					
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setId( $idcliente_cartera );
					$dtoClienteCartera->setIdUsuarioServicio($usuario_servicio);
					
					echo ($daoClienteCartera->save_cliente_especial($dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Cliente Asignado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al asignar cliente'));
					
				break;
				case 'delete_cliente_especial':
					
					$idcliente_cartera = $_GET['idcliente_cartera'];
					$usuario_servicio = 0;
					
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setId( $idcliente_cartera );
					$dtoClienteCartera->setIdUsuarioServicio($usuario_servicio);
					
					echo ($daoClienteCartera->save_cliente_especial($dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Cliente eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar cliente'));
					
				break;
				case 'save_distribucion_por_departamento':
					
					$dto = new dto_direccion_ER2 ;
					$dto->setIdCartera($_POST['Cartera']);
					$dto->setDepartamento($_POST['Departamento']);
					
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					$clientes_disponibles = $daoDireccion->countClientesPorDepartamento($dto);
					$clientes_disponibles = $clientes_disponibles[0]['COUNT'];
					$cantidad_operadores = count($operadores);
					$cantidad_clientes_por_operador = ceil( ( ( (int)$clientes_disponibles) ) / ((int)$cantidad_operadores) );
					
					echo ($daoClienteCartera->generarDistribucionPorDepartamento($dto,$operadores,$cantidad_clientes_por_operador))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion por departamento'));
					
				break;
				case 'save_distribucion_por_tramo':
					
					$modo = $_POST['Modo'];
					
					$dto = new dto_detalle_cuenta ;
					$dto->setIdCartera($_POST['Cartera']);
					$dto->setTramo($_POST['Tramo']);
					
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					if( $modo == 'cartera' ) {
						//$clientes_disponibles = $daoDetalleCuenta->countClientesDisponiblesPorTramo($dto);
						$clientes_disponibles = $daoDetalleCuenta->countClientesDisponiblesPorTramoEspecial($dto);
						$clientes_disponibles = $clientes_disponibles[0]['COUNT'];
						$cantidad_operadores = count($operadores);
						$cantidad_clientes_por_operador = ceil( ((int)$clientes_disponibles) / $cantidad_operadores );
	
						echo ($daoClienteCartera->generarDistribucionPorTramoEspecial($dto,$operadores,$cantidad_clientes_por_operador))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion por tramo'));
					}else{
						$clientes_disponibles = $daoDetalleCuenta->countClientesDisponiblesPorTramoEspecial($dto);
						$clientes_disponibles = $clientes_disponibles[0]['COUNT'];
						$cantidad_operadores = count($operadores);
						$cantidad_clientes_por_operador = ceil( ((int)$clientes_disponibles) / $cantidad_operadores );						
						echo ($daoClienteCartera->generarDistribucionPorTramoModoSeguimientoEspecial($dto,$operadores,$cantidad_clientes_por_operador))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion por tramo'));
					}
					
					/*$clientes_disponibles = $daoDetalleCuenta->countClientesDisponiblesPorTramo($dto);
					$clientes_disponibles = $clientes_disponibles[0]['COUNT'];
					$cantidad_operadores = count($operadores);
					$cantidad_clientes_por_operador = ceil( ((int)$clientes_disponibles) / $cantidad_operadores );

					echo ($daoClienteCartera->generarDistribucionPorTramo($dto,$operadores,$cantidad_clientes_por_operador))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion por tramo'));*/
				
				break;
				case 'generar_distribucion_automatica':
					$dtoCartera=new dto_cartera ;
					$dtoServicio=new dto_servicio ;
					$dtoCartera->setId($_POST['Cartera']);
					//$dtoCampania->setIdServicio($_POST['Servicio']);
					$dtoServicio->setId($_POST['Servicio']);
					
					echo ($daoClienteCartera->generarDistribucionAutomatica($dtoServicio,$dtoCartera))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion automatica'));
					
				break;
				case 'RetirarTodoclienteSinGestionar':
					$dtoCartera=new dto_cartera ;
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoCartera->setId($_POST['Cartera']);
					$dtoUsuarioServicio->setId($_POST['UsuarioServicio']);
					
					echo ($daoClienteCartera->deleteAllClienteSinGestionarXUsuario($dtoCartera,$dtoUsuarioServicio))?json_encode(array('rst'=>true,'msg'=>'Clientes retirados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al retirar clientes'));
					
				break;
				case 'generar_distribucion_manual':
					$arrayData=json_decode(str_replace("\\","",$_POST['DataManual']),true);
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_POST['Cartera']);
					//$dtoCampania->setIdServicio($_POST['Servicio']);
					echo ($daoClienteCartera->generarDistribucionManual($dtoCartera,$arrayData))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion manual'));
				break;
				case 'generar_distribucion_sinpago':
					$arrayData=json_decode(str_replace("\\","",$_POST['DataManual']),true);
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_POST['Cartera']);
					echo ($daoClienteCartera->generarDistribucionSinPago($dtoCartera,$arrayData))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion sin pagos'));
				break;
				case 'generar_distribucion_amortizado':
					$arrayData=json_decode(str_replace("\\","",$_POST['DataManual']),true);
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_POST['Cartera']);
					echo ($daoClienteCartera->generarDistribucionAmortizado($dtoCartera,$arrayData))?json_encode(array('rst'=>true,'msg'=>'Distribucion Generada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al generar distribucion Amortizados'));
				break;
				case 'generar_traspaso_cartera':
					$idusuario_servicio_DE=$_POST['idusuario_servicio_DE'];
					$idusuario_servicio_PARA=$_POST['idusuario_servicio_PARA'];
					$idcart=$_POST['idcartera'];
					$filtros = explode(",",$_POST['filtros']);
					echo ($daoClienteCartera->generarTraspasoCartera($idusuario_servicio_DE,$idusuario_servicio_PARA,$idcart,$filtros))?json_encode(array('rst'=>true,'msg'=>'Traspaso de Carteras Correcto')):json_encode(array('rst'=>false,'msg'=>'Error al Traspasar Carteras entre Operadores'));
				break;
				case 'RetirarIngresadosClientesSinGestionar':
					$dtoCartera=new dto_cartera ;
					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoCartera->setId($_POST['Cartera']);
					$dtoUsuarioServicio->setId($_POST['UsuarioServicio']);
					$cantidad=$_POST['Cantidad'];
					echo ($daoClienteCartera->deleteClientesIngresadosSinGestionar($dtoCartera,$dtoUsuarioServicio,$cantidad))?json_encode(array('rst'=>true,'msg'=>'Clientes retirados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al retirar clientes'));
				break;
				case 'distribucion_por_operador':
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setIdUsuarioServicio($_POST['UsuarioServicio']);
					
					echo ($daoClienteCartera->updateMultiId($_POST['Ids'],$dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al asignar operadores'));
					
				break;
				case 'save_distribucion_por_campo':
					
					$tabla = $_POST['tabla'];
					$campo = $_POST['campo'];
					$dato = $_POST['dato'];
					$cartera = $_POST['cartera'];
					$clientes = explode(",",str_replace("\\","",$_POST['clientes']));
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']),true);
					
					if( count($clientes)==0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'No hay clientes a distribuir'));
						exit();
					}
					if( count($operadores)==0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'No hay operadores a distribuir'));
						exit();
					}
					
					$cantidad_cliente_por_operador = ceil( count($clientes)/count($operadores) );
					
					if( $cantidad_cliente_por_operador<=0 ) {
						echo json_encode(array('rst'=>false,'msg'=>'Todos los clientes ya fueron distribuidos'));
						exit();
					}
					
					$factoryConnection= FactoryConnection::create('mysql');	
					$connection = $factoryConnection->getConnection();
					
					//$connection->beginTransaction();
					
					for( $i=0;$i<count($operadores);$i++ ) {
						$data_clientes = array();
						for( $j=($i*$cantidad_cliente_por_operador);$j<( $i*$cantidad_cliente_por_operador  + $cantidad_cliente_por_operador  );$j++ ) {
							if( isset($clientes[$j]) ) {
								array_push($data_clientes,$clientes[$j]);
							}
						}
						if( count($data_clientes)>0 ) {
							
							$sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ".$operadores[$i]['operador']." 
							WHERE idcartera = $cartera AND codigo_cliente IN ( ".implode(",",$data_clientes)." ) ";
							
							$pr = $connection->prepare($sql);
							if( $pr->execute() ) {
								
							}else{
								//$connection->rollBack();
								echo json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
								exit();
							}
						}
						
					}
					
					//$connection->commit();
					echo json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente'));
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			endswitch;
		}
		public function doGet ( ) {
			$daoCampania=DAOFactory::getDAOCampania('maria');
			$daoClienteCartera=DAOFactory::getDAOClienteCartera('maria');
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			$daoDireccion = DAOFactory::getDAODireccion('maria');
			$daoDetalleCuenta = DAOFactory::getDAODetalleCuenta('maria');
			$daoCartera = DAOFactory::getDAOCartera('maria');
			$daoProcedure=DAOFactory::getDAOProcedure('maria');
			$daoZona = DAOFactory::getDAOZona('maria');
			switch ($_GET['action']):
				case 'load_data_cluster_servicio':
					$servicio=$_SESSION['cobrast']['idservicio']; 
					echo json_encode($daoClienteCartera->queryListarClusterByServicio($servicio));
				break;
				case 'CantidadClientesSinAsignarDistrPagos':
					
					$idcartera = $_GET['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					$dataPagos = ( $_GET['dataPagos'] == '' )? array() : explode(",",$_GET['dataPagos']);
					
					echo json_encode($daoClienteCartera->CantidadClientesSinAsignarDistrPagos($dtoCartera,$dataPagos));	
					
				break;
				case 'CantidadClientesSinAsignarSinGestion':
				
					$idcartera = $_GET['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode($daoClienteCartera->CantidadClientesSinAsignarSinGestion($dtoCartera));	
					
				break;
				case 'CantidadClientesSinAsignarConstante':
				
					$idcartera = $_GET['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode($daoClienteCartera->CantidadClientesSinAsignarDConstante($dtoCartera));
					
				break;
				case 'ListarCarterasServicio':
				
					$idservicio = $_GET['idservicio'];
					
					$dtoUsuarioServicio = new dto_usuario_servicio ;
					$dtoUsuarioServicio->setIdServicio($idservicio);
					
					echo json_encode($daoCartera->queryAllByService($dtoUsuarioServicio));
					
				break;
				case 'CantidadClientesSinAsignarZonas':
				
					$idcartera = $_GET['idcartera'];
					$zona = ($_GET['zona']=='0')?NULL:$_GET['zona'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode($daoClienteCartera->CantidadClientesSinAsignarZona($dtoCartera,$zona));
					
				break;
				case 'CantidadClientesSinAsignarCartera':
				
					$idcartera = $_GET['idcartera'];
					//$zona = ($_GET['zona']=='0')?NULL:$_GET['zona'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode($daoClienteCartera->CantidadClientesSinAsignarCartera($dtoCartera));
					
				break;
				case 'CantidadCuentasPorCartera':
				
					$idcartera = $_GET['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					echo json_encode($daoClienteCartera->CantidadCuentasPorCartera($dtoCartera));
					
				break;
				
				case 'listar_zonas':
					
					$idcartera = $_GET['idcartera'];
					
					$dtoDireccion = new dto_direccion_ER2 ;
					$dtoDireccion->setIdCartera($idcartera);
					
					echo json_encode($daoDireccion->ListarZonas($dtoDireccion));
					
				break;
				case 'listar_departamento_zonas':
					
					$idservicio = $_GET['idservicio'];
					
					echo json_encode($daoZona->queryByService($idservicio));
					
				break;
				case 'ListarCabecerasCartera':
					
					$idcartera = $_GET['idcartera'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					$metadata = $daoCartera->queryCarteraMetaData($dtoCartera);
					$tmp = $metadata[0]['tabla'];
					
					$sqlField = " SELECT * FROM ".$tmp." LIMIT 1 ";
					
					$dataTMP = $daoProcedure->executeQueryReturn($sqlField); 
					
					echo json_encode($dataTMP);
					
				break;
				case 'MostrarCantidadClienteSinGestionarPorUsuario':
					
					$servicio = $_GET['servicio'];
					$tabla = $_GET['tabla'];
					$campo = $_GET['campo'];
					$dato = $_GET['dato'];
					$cartera = is_array($_GET['cartera']) ? implode(',',$_GET['cartera']) : $_GET['cartera'];
					$referencia = $_GET['referencia'];
					$usuario_servicio = $_GET['usuario_servicio'];
					
					function MapArray ( $n ) {
						return "'".$n['codigo_cliente']."'";
					};

					$sqllimpiarfiltro="delete from ca_filtro where idusuario_servicio=".$usuario_servicio." and idcartera=".$cartera." and session='".session_id()."'";
					$daoProcedure->executeQuery($sqllimpiarfiltro);/*jmore05072013*/					
					
					$sqlClientes = "";
					if( $tabla == 'ca_cliente' ) {
						$sqlClientes = " SELECT COUNT(*) AS 'COUNT' FROM 
						( SELECT idcliente_cartera, codigo_cliente, idusuario_servicio FROM ca_cliente_cartera WHERE idcartera IN (".$cartera.") ) clicar INNER JOIN
						( SELECT idcliente, codigo, nombre, paterno, materno, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = ".$servicio." ) cli
						ON cli.codigo = clicar.codigo_cliente WHERE TRIM( cli.".$campo." ) = '".$dato."' AND  clicar.idusuario_servicio = ".$usuario_servicio." ";
					}else if( $tabla == 'ca_direccion' ){
						
						$referenciaDireccion=array('direccion_predeterminado'=>3,'direccion_domicilio'=>2,'direccion_oficina'=>1,'direccion_negocio'=>4,'direccion_laboral'=>5);
						
						$sql = " SELECT codigo_cliente FROM ".$tabla." 
						WHERE idcartera IN (".$cartera.") AND TRIM( ".$campo." ) = '".$dato."' AND idtipo_referencia = ".$referenciaDireccion[$referencia]." ";

						$clientes = $daoProcedure->executeQueryReturn($sql); 
						$map_cliente = array_map("MapArray",$clientes);
						if( count($map_cliente)<=0 ) {
							echo json_encode(array(array('COUNT'=>0)));
							exit();
						}
						$sqlClientes = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera WHERE idcartera IN (".$cartera.") 
						AND idusuario_servicio = ".$usuario_servicio." AND codigo_cliente IN ( ".implode(",",$map_cliente)." ) ";
						
					}else{
						
						$sql = " SELECT codigo_cliente FROM ".$tabla." 
						WHERE idcartera IN (".$cartera.") AND TRIM( ".$campo." ) = '".$dato."' ";
						
						$clientes = $daoProcedure->executeQueryReturn($sql); 
						$map_cliente = array_map("MapArray",$clientes);
						if( count($map_cliente)<=0 ) {
							echo json_encode(array(array('COUNT'=>0)));
							exit();
						}

						/*jmore05072013*/
						$sqlinsertarfiltro="INSERT INTO ca_filtro(idcliente_cartera,idcartera,idusuario_servicio,session)
											(SELECT idcliente_cartera,idcartera,idusuario_servicio,'".session_id()."' FROM ca_cliente_cartera 
											WHERE idcartera IN (".$cartera.") AND codigo_cliente IN (".implode(",",$map_cliente)." ) 
											and idusuario_servicio=".$usuario_servicio." and estado=1)";
						$daoProcedure->executeQuery($sqlinsertarfiltro);/**/
						
						$sqlClientes = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera WHERE idcartera IN (".$cartera.") 
						AND idusuario_servicio = ".$usuario_servicio." AND estado=1 AND codigo_cliente IN ( ".implode(",",$map_cliente)." ) ";
						
					}
					
					echo json_encode($daoProcedure->executeQueryReturn($sqlClientes));
					
				break;
				case 'MostrarCantidadClienteSinGestionar':
					
					$servicio = $_GET['servicio'];
					$tabla = $_GET['tabla'];
					$campo = $_GET['campo'];
					$dato = $_GET['dato'];
					$cartera = $_GET['cartera'];
					
					function MapArray ( $n ) {
						return "'".$n['codigo_cliente']."'";
					};
					
					$sqlClientes = "";
					if( $tabla == 'ca_cliente' ) {
						$sqlClientes = " SELECT clicar.codigo_cliente FROM 
						( SELECT idcliente_cartera, codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ".$cartera." ) clicar INNER JOIN
						( SELECT idcliente, codigo, nombre, paterno, materno, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = ".$servicio." ) cli
						ON cli.codigo = clicar.codigo_cliente WHERE TRIM( cli.".$campo." ) = '".$dato."'  ";
					}else{
						$sqlClientes = " SELECT codigo_cliente FROM ".$tabla." 
						WHERE idcartera = ".$cartera." AND TRIM( ".$campo." ) = '".$dato."'  ";
					}
						
					$dataCliente = $daoProcedure->executeQueryReturn($sqlClientes); 
					
					$map_cliente = array_map("MapArray",$dataCliente);
					if( count($map_cliente)>0 ) {
						$sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera WHERE 
						idcartera = ".$cartera." AND idusuario_servicio = 0 AND estado=1
						AND codigo_cliente IN ( ".implode(",",$map_cliente)." ) ";
						
						$data = $daoProcedure->executeQueryReturn($sql);
						
						$sqlClientesDisponibles = " SELECT codigo_cliente FROM ca_cliente_cartera WHERE 
						idcartera = ".$cartera." AND idusuario_servicio = 0 AND estado=1
						AND codigo_cliente IN ( ".implode(",",$map_cliente)." ) ";
						
						$dataClienteDisponibles = $daoProcedure->executeQueryReturn($sqlClientesDisponibles);
						$clientes_disponibles = array_map("MapArray",$dataClienteDisponibles);
						
						echo json_encode(array('data'=>$data,'clientes'=>implode(",",$clientes_disponibles)));
						
					}else{
						echo json_encode(array('data'=>array(array('COUNT'=>0)),'clientes'=>""));
					}
					
					//$sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera WHERE 
//					idcartera = ".$cartera." AND id_ultima_llamada = 0 AND codigo_cliente IN (
//						
//					) ";
//					
//					$data = $daoProcedure->executeQueryReturn($sql); 
//					
//					echo json_encode(array('data'=>$data));
					
				break;
				case 'ListarDataCampo':
					
					$tabla = $_GET['tabla'];
					$campo = $_GET['campo'];
					$cartera = is_array($_GET['cartera']) ? implode(',',$_GET['cartera']) : $_GET['cartera'];
					$servicio = $_GET['servicio'];
					
					$sql = "";
					if( $tabla == "ca_cliente" ) {
						$sql = " SELECT TRIM( cli.".$campo." ) AS '".$campo."' FROM 
						( SELECT idcliente_cartera, codigo_cliente FROM ca_cliente_cartera WHERE idcartera IN (".$cartera.") ) clicar INNER JOIN
						( SELECT idcliente, codigo, nombre, paterno, materno, numero_documento, tipo_documento FROM ca_cliente WHERE idservicio = ".$servicio." ) cli
						ON cli.codigo = clicar.codigo_cliente WHERE TRIM( cli.".$campo." )!='' GROUP BY TRIM( cli.".$campo." ) ";
					}else{
						$sql = " SELECT TRIM( ".$campo." ) AS '".$campo."' 
						FROM ".$tabla." WHERE idcartera IN (".$cartera.") AND TRIM( ".$campo." )!='' GROUP BY TRIM( ".$campo." ) "; 
					}

					$data = $daoProcedure->executeQueryReturn($sql); 
					
					echo json_encode(array('data'=>$data));
					
				break;
				case 'ListarCampos':
				
					$idcartera = is_array($_GET['idcartera']) ? implode(',',$_GET['idcartera']) : $_GET['idcartera'] ;
					$campo = $_GET['campo'];
					$referencia = $_GET['referencia'];
					
					$dtoCartera = new dto_cartera ;
					$dtoCartera->setId($idcartera);
					
					$metadata = $daoCartera->queryCarteraMetaData($dtoCartera);
					$listaCampos = array();
					
					if( $campo == 'adicionales' ) {
						$data = json_decode($metadata[0][$campo],true); 
						for( $i=0;$i<count($data[$referencia]);$i++ ) { 
							array_push($listaCampos,array("campoT"=>$data[$referencia][$i]['campoT'],"campoTMP"=>$data[$referencia][$i]['dato']));
						}
					}else if( $campo == 'direccion' ){
						$data = json_decode($metadata[0][$campo],true); 
						foreach( $data[$referencia] as $index => $value ) { 
							array_push($listaCampos,array("campoT"=>$index,"campoTMP"=>$value));
						}
					}else{
						$data = json_decode($metadata[0][$campo],true); 
						for( $i=0;$i<count($data);$i++ ) {
							array_push($listaCampos,array("campoT"=>$data[$i]['campoT'],"campoTMP"=>$data[$i]['dato']));
						}
					}
					
					echo json_encode($listaCampos); 
					
				break;
				case 'CantidadClientesPorDepartamento':
					$dto = new dto_direccion_ER2 ;
					$dto->setIdCartera($_GET['Cartera']);
					$dto->setDepartamento($_GET['Departamento']);
					
					echo json_encode($daoDireccion->countClientesPorDepartamento($dto));
				break;
				case 'CantidadClientesPorTramo':
					$dto = new dto_detalle_cuenta ;
					$dto->setIdCartera($_GET['Cartera']);
					$dto->setTramo($_GET['Tramo']);
					
					echo json_encode($daoDetalleCuenta->countClientesDisponiblesPorTramo($dto));
				break;
				case 'CantidadClientesPorTramoEspecial':
					$dto = new dto_detalle_cuenta ;
					$dto->setIdCartera($_GET['Cartera']);
					$dto->setTramo($_GET['Tramo']);
					
					echo json_encode($daoDetalleCuenta->countClientesDisponiblesPorTramoEspecial($dto));
				break;				
				case 'ListarDepartamentosPorCartera':
					$carteras = is_array($_GET['idcartera']) ? implode(',',$_GET['idcartera']): $_GET['idcartera'];
					$dto = new dto_direccion_ER2 ;
					$dto->setIdCartera($carteras);
					
					echo json_encode($daoDireccion->queryDepartamentos($dto)); 
					
				break;
				case 'ListarProvinciasPorCartera':
					$carteras = is_array($_GET['idcartera']) ? implode(',',$_GET['idcartera']): $_GET['idcartera'];
					$dto = new dto_direccion_ER2 ;
					$dto->setIdCartera($carteras);
					$departamento=$_GET['departamento'];
					echo json_encode($daoDireccion->queryProvincias($dto,$departamento)); 
					
				break;								
				case 'ListCampania':
					$dto=new dto_servicio ;
					$dto->setId($_GET['Servicio']);
					echo json_encode($daoCampania->queryByIdName($dto));
				break;
				case 'ListarGestionOperador':
					$dtoCartera=new dto_cartera ;
					$dtoServicio=new dto_servicio ;
					$dtoCartera->setId($_GET['Cartera']);
					$dtoServicio->setId($_GET['Servicio']);
					echo json_encode($daoClienteCartera->queryClientesByOperador($dtoCartera,$dtoServicio));
				break;
				case 'ListarGestionOperadorPorCluster':
					$dtoCartera=new dto_cartera ;
					$dtoServicio=new dto_servicio ;
					$idcluster=$_GET['idcluster'];
					$dtoCartera->setId($_GET['Cartera']);
					$dtoServicio->setId($_GET['Servicio']);
					echo json_encode($daoClienteCartera->queryClientesByOperadorPorCluster($dtoCartera,$dtoServicio,$idcluster));
				break;
				case 'DataDistribucionAutomatica':
					$dtoServicio=new dto_servicio ;
					$dtoCartera=new dto_cartera ;
					$dtoServicio->setId($_GET['Servicio']);
					$dtoCartera->setId($_GET['Cartera']);
					echo json_encode($daoClienteCartera->queryDistribucionAutomatica($dtoServicio,$dtoCartera));
				break;
				case 'clientes_sin_gestionar':
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_GET['Cartera']);
					//$dtoCampania->setIdServicio($_GET['Servicio']);
					echo json_encode($daoClienteCartera->queryClientesSinAsignar($dtoCartera));
				break;
				case 'clientes_sin_pago':
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_GET['Cartera']);
					echo json_encode($daoClienteCartera->queryClientesSinPago($dtoCartera));
				break;
				case 'clientes_amortizado':
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_GET['Cartera']);
					echo json_encode($daoClienteCartera->queryClientesAmortizado($dtoCartera));
				break;
				case 'numero_clientes_cartera':
					$dtoCartera=new dto_cartera ;
					$dtoCartera->setId($_GET['Cartera']);
					echo json_encode($daoClienteCartera->queryNumeroCliCar($dtoCartera));
				break;
				case 'jqgrid_clientes_gestionados':
					if(!isset($_GET['Cartera'])){
						echo '{}';
						exit();
					}else if( $_GET['Cartera']=='' ) {
						echo '{}';
						exit();
					}
					
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
					$param[':cartera']=$_GET['Cartera'];
					$param[':servicio']=$_GET['Servicio'];

					if( isset($_GET['cli_codigo']) ) {
						if( trim($_GET['cli_codigo'])!='' ) {
							$search.=" AND TRIM(cli.codigo) = :codigo ";
							$param[':codigo']=$_GET['cli_codigo'];
							
						}
					}
					if( isset($_GET['cli_nombre']) ) {
						if( trim($_GET['cli_nombre'])!='' ) {
							$search.=" AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%".$_GET['cli_nombre']."%' ";
						}
					}
					if( isset($_GET['cli_numero_documento']) ) {
						if( trim($_GET['cli_numero_documento'])!='' ) {
							$search.=" AND TRIM(cli.numero_documento) = :numero_documento ";
							$param[':numero_documento']=$_GET['cli_numero_documento'];
						}
					}
					if( isset($_GET['usuario_gestion']) ) {
						if( trim($_GET['usuario_gestion'])!='' ) {
							$search.=" AND usuario_gestion LIKE '%".$_GET['usuario_gestion']."%' ";
						}
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					/*$dtoCartera=new dto_cartera ;
					$dtoServicio=new dto_servicio ;
					$dtoCartera->setId($_GET['Cartera']);
					$dtoServicio->setId($_GET['Servicio']);*/
				
					if(!$sidx)$sidx=1 ;
					
					//$row=$daoJqgrid->JQGRIDCountClientesGestionados($dtoCartera,$dtoServicio);
					$row=$daoJqgrid->JQGRIDCountClientesGestionados($search,$param,$querySearch);
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$limit=0;
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					//$data=$daoJqgrid->JQGRIDRowsClientesGestionados($sidx, $sord, $start, $limit, $dtoCartera, $dtoServicio);
					$data=$daoJqgrid->JQGRIDRowsClientesGestionados($sidx, $sord, $start, $limit, $search, $param,$querySearch );
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcliente_cartera'],"cell"=>array($data[$i]['codigo'],$data[$i]['cliente'],$data[$i]['numero_documento'],$data[$i]['usuario_gestion'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'jqgrid_clientes_sin_gestionar':
					if(!isset($_GET['Cartera'])){
						echo '{}';
						exit();
					}else if( $_GET['Cartera']=='' ) {
						echo '{}';
						exit();
					}
					
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
					$param[':cartera']=$_GET['Cartera'];
					$param[':servicio']=$_GET['Servicio'];

					if( isset($_GET['cli_codigo']) ) {
						if( trim($_GET['cli_codigo'])!='' ) {
							$search.=" AND TRIM(cli.codigo) = :codigo ";
							$param[':codigo']=$_GET['cli_codigo'];
							
						}
					}
					if( isset($_GET['cli_nombre']) ) {
						if( trim($_GET['cli_nombre'])!='' ) {
							$search.=" AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%".$_GET['cli_nombre']."%' ";
							//$param[':cliente']="'%".$_GET['cli_nombre']."%'";
						}
					}
					if( isset($_GET['cli_numero_documento']) ) {
						if( trim($_GET['cli_numero_documento'])!='' ) {
							$search.=" AND TRIM(cli.numero_documento) = :numero_documento ";
							$param[':numero_documento']=$_GET['cli_numero_documento'];
						}
					}
					if( isset($_GET['usuario_gestion']) ) {
						if( trim($_GET['usuario_gestion'])!='' ) {
							$search.=" AND usuario_gestion LIKE '%".$_GET['usuario_gestion']."%' ";
							//$param[':usuario_gestion']="'%".$_GET['usuario_gestion']."%'";
						}
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					/*$dtoCartera=new dto_cartera ;
					$dtoServicio=new dto_servicio ;
					$dtoCartera->setId($_GET['Cartera']);
					$dtoServicio->setId($_GET['Servicio']); */
	
					if(!$sidx)$sidx=1 ;
					
					//$row=$daoJqgrid->JQGRIDCountClientesSinGestionados($dtoCartera,$dtoServicio);
					$row=$daoJqgrid->JQGRIDCountClientesSinGestionados($search, $param, $querySearch);
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
					
					//$data=$daoJqgrid->JQGRIDRowsClientesSinGestionados($sidx, $sord, $start, $limit, $dtoCartera, $dtoServicio);
					$data=$daoJqgrid->JQGRIDRowsClientesSinGestionados($sidx, $sord, $start, $limit, $search, $param, $querySearch );
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcliente_cartera'],"cell"=>array($data[$i]['codigo'],$data[$i]['cliente'],$data[$i]['numero_documento'],$data[$i]['usuario_gestion'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
				case 'jqgrid_clientes_por_cartera':
					
					if(!isset($_GET["Cartera"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Cartera'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if(!isset($_GET["Servicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Servicio'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$search="";
					
					$param=array();
					$param[':cartera']=$_GET['Cartera'];
					$param[':servicio']=$_GET['Servicio'];

					if( isset($_GET['cli_codigo']) ) {
						if( trim($_GET['cli_codigo'])!='' ) {
							$search.=" AND TRIM(cli.codigo) = :codigo ";
							$param[':codigo']=$_GET['cli_codigo'];
							
						}
					}
					if( isset($_GET['cliente']) ) {
						if( trim($_GET['cliente'])!='' ) {
							$search.=" AND TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) LIKE '".$_GET['cliente']."%' ";
							//$search.=" AND TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) = :cliente ";
							//$param[':cliente']=$_GET['cliente'];
						}
					}
					if( isset($_GET['cli_numero_documento']) ) {
						if( trim($_GET['cli_numero_documento'])!='' ) {
							$search.=" AND TRIM(cli.numero_documento) = :numero_documento ";
							$param[':numero_documento']=$_GET['cli_numero_documento'];
						}
					}
					if( isset($_GET['cli_tipo_documento']) ) {
						if( trim($_GET['cli_tipo_documento'])!='' ) {
							$search.=" AND TRIM(cli.tipo_documento) = :tipo_documento ";
							$param[':tipo_documento']=$_GET['cli_tipo_documento'];
						}
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountDistribucionPorOperador($param,$search);
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
			
					$data=$daoJqgrid->JQGRIDRowsDistribucionPorOperador($sidx, $sord, $start, $limit, $param, $search);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcliente_cartera'],"cell"=>array(
																										$data[$i]['codigo'],
																										$data[$i]['cliente'],
																										$data[$i]['numero_documento'],
																										$data[$i]['tipo_documento']
																										)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
					
				break;
				case 'jqgrid_clientes':
					
					if(!isset($_GET["Cartera"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Cartera'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if(!isset($_GET["Servicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Servicio'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$search="";
					
					$param=array();
					$param[':cartera']=$_GET['Cartera'];
					$param[':servicio']=$_GET['Servicio'];
					
					$search .= " AND clicar.idusuario_servicio_especial = 0  ";
					if( isset($_GET['clicar_codigo_cliente']) ) {
						if( trim($_GET['clicar_codigo_cliente'])!='' ) {
							$search.=" AND TRIM(clicar.codigo_cliente) = :codigo ";
							$param[':codigo']=$_GET['clicar_codigo_cliente'];
							
						}
					}
					if( isset($_GET['nombre']) ) {
						if( trim($_GET['nombre'])!='' ) {
							$search.=" AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%".trim($_GET['nombre'])."%' ";
							//$search.=" AND TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) = :cliente ";
							//$param[':cliente']="'%".$_GET['nombre']."%'"; 
						}
					}
					if( isset($_GET['cli_numero_documento']) ) {
						if( trim($_GET['cli_numero_documento'])!='' ) {
							$search.=" AND TRIM(cli.numero_documento) = :numero_documento ";
							$param[':numero_documento']=$_GET['cli_numero_documento'];
						}
					}
					if( isset($_GET['cli_tipo_documento']) ) {
						if( trim($_GET['cli_tipo_documento'])!='' ) {
							$search.=" AND TRIM(cli.tipo_documento) = :tipo_documento ";
							$param[':tipo_documento']=$_GET['cli_tipo_documento'];
						}
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountClientesCartera($param,$search);
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
			
					$data=$daoJqgrid->JQGRIDRowsClientesCartera($sidx, $sord, $start, $limit, $param, $search);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcliente_cartera'],"cell"=>array(
																										$data[$i]['codigo_cliente'],
																										$data[$i]['nombre'],
																										$data[$i]['numero_documento'],
																										$data[$i]['tipo_documento']
																										)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
					
				break;
				case 'jqgrid_clientes_especiales_asignados':
					
					if(!isset($_GET["Cartera"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Cartera'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if(!isset($_GET["Servicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['Servicio'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if(!isset($_GET["UsuarioServicio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					if( trim($_GET['UsuarioServicio'])=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$search="";
					
					$param=array();
					$param[':cartera']=$_GET['Cartera'];
					$param[':servicio']=$_GET['Servicio'];
					$param[':usuario_servicio']=$_GET['UsuarioServicio'];

					if( isset($_GET['clicar_codigo_cliente']) ) {
						if( trim($_GET['clicar_codigo_cliente'])!='' ) {
							$search.=" AND TRIM(clicar.codigo_cliente) = :codigo ";
							$param[':codigo']=$_GET['clicar_codigo_cliente'];
							
						}
					}
					if( isset($_GET['nombre']) ) {
						if( trim($_GET['nombre'])!='' ) {
							$search.=" AND TRIM(CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre)) LIKE '%".trim($_GET['nombre'])."%' ";
							//$search.=" AND TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) = :cliente ";
							//$param[':cliente']="'%".$_GET['nombre']."%'"; 
						}
					}
					if( isset($_GET['cli_numero_documento']) ) {
						if( trim($_GET['cli_numero_documento'])!='' ) {
							$search.=" AND TRIM(cli.numero_documento) = :numero_documento ";
							$param[':numero_documento']=$_GET['cli_numero_documento'];
						}
					}
					if( isset($_GET['cli_tipo_documento']) ) {
						if( trim($_GET['cli_tipo_documento'])!='' ) {
							$search.=" AND TRIM(cli.tipo_documento) = :tipo_documento ";
							$param[':tipo_documento']=$_GET['cli_tipo_documento'];
						}
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountClientesEspecialesAsignadosTeleoperador($param,$search);
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
			
					$data=$daoJqgrid->JQGRIDRowsClientesEspecialesAsignadosTeleoperador($sidx, $sord, $start, $limit, $param, $search);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcliente_cartera'],"cell"=>array(
																										$data[$i]['codigo_cliente'],
																										$data[$i]['nombre'],
																										$data[$i]['numero_documento'],
																										$data[$i]['tipo_documento']
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
			endswitch;
		}
	}
	
?>
