<?php
class servletNeotel extends CommandController {
    public function doPost ( ) {
        $daoNeotel=DAOFactory::getDAONeotel();
        $daoClienteCartera = DAOFactory::getDAOClienteCartera('maria');
		switch ($_POST['action']):
			case 'Logout':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->Logout($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error desloguar de NEOTEL'));
				}
				break;
			case 'setCloseContact':
				if($daoNeotel->CloseContact($_POST['base'],$_POST['idcontacto'])){
					echo json_encode(array('rst'=>true,'msg'=>'Contacto Cerrado en NEOTEL'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'No se pudo realizar CloseContact'));
				}
				break;
			case 'AddScheduleCall':
				if($daoNeotel->AddScheduleCall($_POST['usu_neotel'],$_POST['base'],$_POST['idcontacto'],$_POST['data'],$_POST['telefono'],$_POST['fecha_agenda'])){
					echo json_encode(array('rst'=>true,'msg'=>'Agendado en NEOTEL'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'No se pudo agendar en NEOTEL'));
				}
				break;
			case 'getIdTelefonoCliente':
				echo json_encode( $daoClienteCartera->getIdTelefonoCliente( $_POST ) );
				break;
			case 'setShowingContact':
				if($daoNeotel->CRM_ShowingContact($_POST['usu_neotel'],$_POST['base'],$_POST['idcontacto'],$_POST['data'])){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al poner ShowingContact'));
				}
				break;
			case 'setCrmAvailable':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->CRM_Available($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok available'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al cambiar a AVAILABLE'));
				}
				break;
			case 'setCrmUnAvailable':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->CRM_Unavailable($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok unavailable'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al cambiar a UNAVAILABLE'));
				}
				break;
			case 'setUnPause':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->Unpause($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al Quitar Pausa'));
				}
				break;
			case 'setPause':
				$usu_neotel=$_POST['usu_neotel'];
				$idsubtipo_descanso=$_POST['idsubtipo_descanso'];
				if($daoNeotel->Pause($usu_neotel,$idsubtipo_descanso)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al poner Pausa'));
				}
				break;
			case 'setLogoutCampania':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->Logout_Campaign($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al Desloguear CAMPAÑA'));
				}
				break;
			case 'setCampania':
				$usu_neotel=$_POST['usu_neotel'];
				$idcampania=$_POST['idcampania'];
				if($daoNeotel->Login_Campaign2($usu_neotel,$idcampania)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al loguearse en Campaña'));
				}
				break;
			case 'setDial':
				$usu_neotel=$_POST['usu_neotel'];
				$numero=$_POST['numero'];
				if($daoNeotel->Inicia_LLamada($usu_neotel,$numero)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al Inicial Llamada'));
				}			
				break;	
			case 'setHungup':
				$usu_neotel=$_POST['usu_neotel'];
				if($daoNeotel->Parar_LLamada($usu_neotel)){
					echo json_encode(array('rst'=>true,'msg'=>'ok'));
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al Inicial Llamada'));
				}			
				break;								
			case 'getPosition':
				$usu_neotel=$_POST['usu_neotel'];
				echo json_encode($daoNeotel->getPosition($usu_neotel));
				break;
			case 'getstatus':
				$usu_neotel=$_POST['usu_neotel'];
				echo json_encode($daoNeotel->getStatus($usu_neotel));
				break;
			case 'setShowDataCliente':
				echo json_encode($daoClienteCartera->setShowDataCliente($_POST['data'], $_POST['idservicio']));
				break;
			default:
				echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
			;
		endswitch;
    }

    public function doGet ( ) {
		$daoNeotel=DAOFactory::getDAONeotel();
		switch ($_GET['action']) :
			case 'load_data_cluster_servicio':
				$servicio=$_SESSION['cobrast']['idservicio']; 
				echo json_encode($daoUsuario->queryListarClusterByServicio($servicio));
				break;
			default:
				echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
			;
		endswitch;
    }
}
?>
