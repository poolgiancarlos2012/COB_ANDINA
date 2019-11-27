<?php

	class servletNotice extends CommandController {
		
		public function doPost ( ) {
			$daoNotice = DAOFactory::getDAONotice('maria');
			switch($_POST['action']){
				case 'save_notice':
				
					$idservicio = $_POST['idservicio'];
					$idusuario_servicio = $_POST['idusuario_servicio'];
					$titulo = $_POST['titulo'];
					$descripcion = $_POST['descripcion'];
					$usuario_creacion = $_POST['usuario_creacion'];
					
					$dtoNotice = new dto_notice ;
					$dtoNotice->setIdServicio($idservicio);
					$dtoNotice->setIdUsuarioServicio($idusuario_servicio);
					$dtoNotice->setTitulo($titulo);
					$dtoNotice->setDescripcion($descripcion);
					$dtoNotice->setUsuarioCreacion($usuario_creacion);
					
					echo ($daoNotice->insert($dtoNotice))?json_encode(array('rst'=>true,'msg'=>'Noticia Guardada Correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al crear noticia'));
				
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}
			
		}
		
		public function doGet ( ) {
			$daoNotice = DAOFactory::getDAONotice('maria');
			switch($_GET['action']){
				case 'ListarNoticeHoyRealTime':
					
					$idservicio = $_GET['idservicio'];
					
					$dtoNotice = new dto_notice ;
					$dtoNotice->setIdServicio($idservicio);
					
					$data = $daoNotice->queryByServiceNoticeToDaySQLITE($dtoNotice);
					$xml='';
					/*$xml.='<?xml version="1.0" encoding="ISO-8859-1"?>';*/
					/*$xml.='<rss version="2.0">';
						$xml.='<channel>';
							$xml.='<title>COBRAST</title>';
							$xml.='<description>Sistema de Cobranza Web </description>';
							$xml.='<language>es-es</language>';*/
					$rss = array();
					for( $i=0;$i<count($data);$i++ ) {
						array_push($rss,$data[$i]);
						/*$xml.='<item>';
							$xml.='<title>'.$data[$i]['titulo'].'</title>';
							$xml.='<descripcion>'.$data[$i]['descripcion'].'</description>';
							$xml.='<pubdate>'.$data[$i]['fecha_creacion'].'</pubdate>';
						$xml.='</item>';*/
					}
					/*	$xml.='</channel>';
					$xml.='</rss>';*/
					
					echo json_encode($rss);
					//echo json_encode($daoNotice->queryByServiceNoticeToDaySQLITE($dtoNotice));
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}
			
		}
		
	}

?>