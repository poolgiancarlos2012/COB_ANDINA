<?php

	class servletAlertaAdmin extends CommandController {
		
		public function doPost ( ) {
			
		}
		
		public function doGet ( ) {
			$daoAlerta=DAOFactory::getDAOAlerta('maria');
			switch($_GET['action']){
				case 'ListarAlertas':
					
					$idservicio = $_GET['idservicio'];
					$dtoAlerta = new dto_alerta ;
					$dtoAlerta->setIdServicio($idservicio);
					$dataHoy = $daoAlerta->alertasHoySQLITE($dtoAlerta);
					$dataAyer = $daoAlerta->alertasAyerSQLITE($dtoAlerta);
					$dataAntigua = $daoAlerta->alertasAntiguasSQLITE($dtoAlerta);
					
					echo json_encode(array('hoy'=>$dataHoy,'ayer'=>$dataAyer,'antigua'=>$dataAntigua)); 
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Action not found'));
				;
			}
			
		}
		
	}

?>