<?php

	class servletAyudaGestionUsuario extends CommandController { 
		
		public function doPost ( ) {
			$daoAyudaGestionUsuario=DAOFactory::getDAOAyudaGestionUsuario('maria');
			switch($_POST['action']){
				case 'SaveUsuarioAyudar':
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setIdCartera($_POST['Cartera']);
					$dtoClienteCartera->setIdUsuarioServicio($_POST['UsuarioServicio']);
					$dtoClienteCartera->setUsuarioCreacion($_POST['UsuarioCreacion']);
					
					echo ($daoAyudaGestionUsuario->insertMasivo($_POST['IdsUsuarioServicio'],$dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Usuarios asignados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al asignar usuarios')); 
				break;
				case 'DeleteUsuarioAyudar':
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoClienteCartera->setIdCartera($_POST['Cartera']);
					$dtoClienteCartera->setUsuarioModificacion($_POST['UsuarioModificacion']);
					
					echo ($daoAyudaGestionUsuario->deleteMasivo($_POST['IdsUsuarioServicio'],$dtoClienteCartera))?json_encode(array('rst'=>true,'msg'=>'Usuarios eliminados correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar usuarios')); 
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Acccion no encontrada'));
				;
			}
		}
		
		public function doGet ( ) {
			$daoAyudaGestionUsuario=DAOFactory::getDAOAyudaGestionUsuario('maria');
			switch($_GET['action']){
				case 'ListarUsuariosAyudar':
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoServicio = new dto_servicio ;
					$dtoClienteCartera->setIdCartera($_GET['Cartera']);
					$dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
					$dtoServicio->setId($_GET['Servicio']);
					
					echo json_encode($daoAyudaGestionUsuario->queryUsuarioAyudar($dtoClienteCartera,$dtoServicio));
				break;
				case 'ListarUsuariosAsignarConDist':
					
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoServicio = new dto_servicio ;
					$dtoClienteCartera->setIdCartera($_GET['Cartera']);
					$dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
					$dtoServicio->setId($_GET['Servicio']);

					echo json_encode($daoAyudaGestionUsuario->queryUsuarioAsignarConDistr($dtoClienteCartera,$dtoServicio));
					
				break;
				case 'ListarUsuariosAsignar':
					$dtoClienteCartera = new dto_cliente_cartera ;
					$dtoServicio = new dto_servicio ;
					$dtoClienteCartera->setIdCartera($_GET['Cartera']);
					$dtoClienteCartera->setIdUsuarioServicio($_GET['UsuarioServicio']);
					$dtoServicio->setId($_GET['Servicio']);
					
					echo json_encode($daoAyudaGestionUsuario->queryUsuarioAsignar($dtoClienteCartera,$dtoServicio));
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Acccion no encontrada'));
				;
			}
		}
		
	}

?>