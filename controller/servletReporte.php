<?php

	class servletReporte extends CommandController {
		public function doGet ( ) {
			$daoClienteCartera=DAOFactory::getDAOClienteCartera('maria');
			switch ($_GET['action']):
				case 'fotocartera':
					echo json_encode($daoClienteCartera->executeSelectString("call foto_cartera(".$_GET['Campania'].")"));	
				break;
			endswitch;
		}
		
		public function doPost ( ) {
			$daoClienteCartera=DAOFactory::getDAOClienteCartera('maria');
			switch ($_GET['action']):
				case 'fotocartera':
					echo json_encode($daoClienteCartera->executeSelectString("call foto_cartera(".$_GET['Campania'].")"));	
				break;
			endswitch;
		}
	}

?>