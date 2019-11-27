<?php

	class servletUbigeo extends CommandController { 
		
		public function doPost ( ) {
			
			switch ($_POST['action']){
				
				case '':
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
				
			}
			
		}
		
		public function doGet ( ) {
			$daoUbigeo = DAOFactory::getUbigeoDAO('maria'); 
			switch ($_GET['action']){
				case 'ListarDepartamento':
					
					echo json_encode( $daoUbigeo->queryDepartamento() );
					
				break;
				case 'ListarProvincia':
					
					echo json_encode( $daoUbigeo->queryProvincia( $_GET['departamento'] ) );
					
				break;
				case 'ListarDistrito':
					
					echo json_encode( $daoUbigeo->queryDistrito( $_GET['departamento'], $_GET['provincia'] )  );
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;

			}
			
		}
		
	}

?>