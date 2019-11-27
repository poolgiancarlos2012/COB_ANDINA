<?php

	class servletSpeech extends CommandController {
	
		public function doPost ( ) {
			$daoAyudaGestion=DAOFactory::getDAOAyudaGestion('maria');
			switch($_POST['action']):
				case 'upload':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setIdServicio($_POST['Servicio']);
					$dtoAyudaGestion->setNombre($_POST['Nombre']);
					$dtoAyudaGestion->setIdTipoAyudaGestion($_POST['TipoAyudaGestion']);
					$dtoAyudaGestion->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					$daoAyudaGestion->insertDataCreation($dtoAyudaGestion,$_POST,$_FILES);
					
				break;
				case 'GuardarSpeechModoTexto':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setIdServicio($_POST['Servicio']);
					$dtoAyudaGestion->setNombre($_POST['Nombre']);
					$dtoAyudaGestion->setTexto($_POST['Texto']);
					$dtoAyudaGestion->setIsText(1);
					$dtoAyudaGestion->setIdTipoAyudaGestion($_POST['TipoAyudaGestion']);
					$dtoAyudaGestion->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					echo ($daoAyudaGestion->insertModoTexto($dtoAyudaGestion))?json_encode(array('rst'=>true,'msg'=>'Speech  grabado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al grabar speech'));
				break;
				case 'UpdateSpeechModoTexto':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setId($_POST['Id']);
					$dtoAyudaGestion->setNombre($_POST['Nombre']);
					$dtoAyudaGestion->setTexto($_POST['Texto']);
					$dtoAyudaGestion->setIdTipoAyudaGestion($_POST['TipoAyudaGestion']);
					$dtoAyudaGestion->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					echo ($daoAyudaGestion->updateModoTexto($dtoAyudaGestion))?json_encode(array('rst'=>true,'msg'=>'Speech actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar speech'));
				break;
				case 'ReadFile':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setId($_POST['Id']);
					
					echo json_encode($daoAyudaGestion->ReadFile($dtoAyudaGestion));
					
				break;
				case 'ReadText':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setId($_POST['Id']);

					echo json_encode($daoAyudaGestion->ReadText($dtoAyudaGestion));
				break;
				case 'DataText':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setId($_POST['Id']);
					echo json_encode($daoAyudaGestion->queryAllIsTextById($dtoAyudaGestion));
				break;
				default:
					echo json_encode();
				;
			endswitch;
		}

		public function doGet ( ) {
			$daoTipoAyudaGestion=DAOFactory::getDAOTipoAyudaGestion('maria');
			$daoAyudaGestion=DAOFactory::getDAOAyudaGestion('maria');
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			switch($_GET['action']):
				case 'LoadTipoAyudaGestion':
					//sleep(15);
					echo json_encode($daoTipoAyudaGestion->queryAllByIdNombre());
				break;
				case 'ListarSpeech':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setIdServicio($_GET['Servicio']);
					echo json_encode($daoAyudaGestion->queryAllByService($dtoAyudaGestion));
				break;
				case 'ListarSpeechIsText':
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setIdServicio($_GET['Servicio']);
					echo json_encode($daoAyudaGestion->queryAllByServicieIsText($dtoAyudaGestion));
				break;
				case 'jqgrid_ListarSpeech':
					if(!isset($_GET["Servicio"])){
						echo '{}';
						exit();
					}else if( $_GET['Servicio']=='' ) {
						echo '{}';
						exit();
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
					
					$dtoAyudaGestion=new dto_ayuda_gestion ;
					$dtoAyudaGestion->setIdServicio($_GET['Servicio']);

					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountSpeechListar($dtoAyudaGestion);
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsSpeechListar($sidx, $sord, $start, $limit,$dtoAyudaGestion);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idayuda_gestion'],"cell"=>array($data[$i]['fecha_creacion'],$data[$i]['ruta'],$data[$i]['tipo_ayuda_gestion'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
			endswitch;
		}
	}

?>