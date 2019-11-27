<?php

    class servletLogin extends CommandController {
          public function doPost() {
              $dao=DAOFactory::getDAOUsuarioServicio('maria');
              switch ($_POST['action']):
                case 'check':
					
					if( !isset($_POST['Usuario']) ) {
						echo json_encode(array('rst'=>false,'msg'=>'User not found'));
						exit();
					}
					
					if( !isset($_POST['Password']) ) {
						echo json_encode(array('rst'=>false,'msg'=>'Password not found'));
						exit();
					}
					
					if( !isset($_POST['Servicio']) ) {
						echo json_encode(array('rst'=>false,'msg'=>'Service not found'));
						exit();
					}
					
					$usuario = $_POST['Usuario'];
					$password = $_POST['Password'];
					$servicio = $_POST['Servicio'];
					
                    $dtoUsuario=new dto_usuario ;
                    $dtoServicio=new dto_servicio ;
                    $dtoUsuario->setDni($usuario);
                    $dtoUsuario->setClave($password);
                    $dtoServicio->setId($servicio);

					if( $dtoServicio->getId()>0 ){
						$IsExists=$dao->IsExists($dtoUsuario,$dtoServicio);
						
						if( $IsExists[0]['COUNT']>0 ) {
						//if( $IsExists[0]['count']>0 ) {
							$dateCorrect=$dao->IsDateCorrect($dtoUsuario,$dtoServicio);
							if( $dateCorrect[0]['COUNT']>0 ){
							//if( $dateCorrect[0]['count']>0 ){
								
								$dataUsuario=$dao->queryLogin($dtoUsuario, $dtoServicio);
								$_SESSION['cobrast']=$dataUsuario[0];
								$_SESSION['cobrast']['activo']=1; 
								echo json_encode(array('rst'=>true,'msg'=>'Usuario logeado correctamente'));
								
							}else{
								echo json_encode(array('rst'=>false,'msg'=>'Usuario vencido'));
							}
						}else{
							echo json_encode(array('rst'=>false,'msg'=>'Usuario no se encuentra registrado'));
						}
					}else{
						echo json_encode(array('rst'=>false,'msg'=>'Linea Incorrecta'));
					}
					
					/*******/
                    
                break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
              endswitch;
          }
          public function doGet() {
         			 		    
          }
    }

?>
