<?php

abstract class CommandController {

    public function process ( ) {
        switch ($_SERVER['REQUEST_METHOD']):
            case 'POST':
                if(method_exists($this,'doPost')) {
                    $this->doPost();
                }
            break;
            case 'GET':
                if(method_exists($this,'doGet')) {
                    $this->doGet();
                }
           	break;
       	endswitch;
    }

    public function doPost ( ) {

    }
    public function doGet ( ) {

    }
   
    public static function getCommand ( ) {
        $classController= NULL;
        switch ($_REQUEST['command'] ) :
            case 'neotel':
                $classController = new servletNeotel ;
            break;
            case 'servicio':
                $classController = new servletServicio ;
            break;
            case 'campania':
                $classController = new servletCampania ;
            break;
            case 'usuario':
                $classController = new servletUsuario ;
            break;
            case 'permisos':
                $classController = new servletPermisosDetalle ;
            break;
            case 'menus':
                $classController = new servletMenu ;
            break;
            case 'login':
                $classController = new servletLogin ;
            break;
            case 'distribucion':
                $classController = new servletDistribucion ;
            break;
            case 'atencion_cliente':
                $classController = new servletAtencionCliente ;
            break;
            case 'carga-cartera':
                $classController = new servletCargaCatera();
            break;
			case 'tipo_final':
				$classController = new servletTipoFinal();
			break;
			case 'carga_final':
				$classController = new servletCargaFinal();
			break;
			case 'clase_final':
				$classController = new servletClaseFinal();
			break;
			case 'nivel':
				$classController = new servletNivel();
			break;
			case 'tipo_gestion':
				$classController = new servletTipoGestion();
			break;
			case 'speech':
				$classController = new servletSpeech();
			break;
			case 'asteriskAGI':
				$classController = new servletAsteriskAGI();
			break;
			case 'reporte':
				$classController = new servletReporte();
			break;
			case 'calendar':
				$classController = new servletCalendar();
			break;
			case 'ayuda_gestion_usuario':
				$classController = new servletAyudaGestionUsuario();
			break;
			case 'estado_prioridad':
				$classController = new servletEstados();
			break;
			case 'usuario_admin':
				$classController = new servletUsuarioAdmin();
			break;
			case 'finales':
				$classController = new servletFinales();
			break;
			case 'finalesxservicio':
				$classController = new servletFinalesServicio();
			break;
			case 'ranking_operador':
				$classController = new servletRanking();
			break;
			case 'alerta_admin':
				$classController = new servletAlertaAdmin();
			break;
			case 'notice':
				$classController = new servletNotice();
			break;
			case 'cartera':
				$classController = new servletCartera();
			break;
			case 'files':
				$classController = new servletFiles();
			break;
			case 'RP3':
				$classController = new servletRP3();
			break;
			case 'ubigeo':
				$classController = new servletUbigeo();
			break;
			default:	
				echo json_encode(array('rst'=>false,'msg'=>'Controllador no encontrado'));
				exit();
			;
        endswitch;
        return $classController;
    }

}


?>
