<?php

	class servletRP3 extends CommandController {
		
		public function doGet ( ) {
			$daoCarteraRP3 = DAOFactory::getCarteraRP3DAO('mysql');
			$daoPagoRP3 = DAOFactory::getPagoRP3DAO('mysql');
			switch ($_GET["action"]) {
            	case 'BuscarPorFechaEnvio':
            	
            		$carteraC = $daoCarteraRP3->queryByDateCommerce($_GET['fecha_inicio'],$_GET['fecha_fin']);
            		$carteraB = $daoCarteraRP3->queryByDateBank($_GET['fecha_inicio'],$_GET['fecha_fin']);
            		
            		$pagosC = $daoPagoRP3->queryByDateCommerce($_GET['fecha_inicio'],$_GET['fecha_fin']);
            		$pagosB = $daoPagoRP3->queryByDateBank($_GET['fecha_inicio'],$_GET['fecha_fin']);
            	
            		echo json_encode(array( "CarteraC"=>$carteraC, "CarteraB"=>$carteraB, "PagosC" => $pagosC, "PagosB" => $pagosB ));
            	
            	break;
            	default:
            		echo json_encode(array('rst' => false, 'msg' => 'Acction not found'));
            	;
            }
			
		}
		
		public function doPost ( ) {
			$daoCarteraRP3 = DAOFactory::getCarteraRP3DAO('mysql');
			$daoPagoRP3 = DAOFactory::getPagoRP3DAO('mysql');
            $daoRespuestaRP3 = DAOFactory::getRespuestaRP3DAO('mysql');
			switch ($_POST["action"]) {
                case 'EnviarRespuesta':
                    
                    echo json_encode( $daoRespuestaRP3->send($_POST['tipo'],$_POST['servicio'],$_POST['carteras'],$_POST['fecha_inicio'],$_POST['fecha_fin']) );
                    
                break;
            	case 'CargarDataCartera':
            	
            		$data = json_decode( str_replace("\\","",$_POST['data']) ,true);
            	
            		echo json_encode( $daoCarteraRP3->loadFileCartera( $_POST['tipo'], $data , $_POST['NombreServicio'] ) );
            		
            	break;
            	case 'CargarDataPagos':
            	
            		$data = json_decode( str_replace("\\","",$_POST['data']) ,true);
            	
            		echo json_encode( $daoPagoRP3->loadFilePagos( $_POST['tipo'], $data, $_POST['NombreServicio'] ) );	
            	
            	break;
            	default:
            		echo json_encode(array('rst' => false, 'msg' => 'Acction not found'));
            	;
            }
			
		}
		
		
	}


?>