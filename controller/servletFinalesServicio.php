<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of servletFinalesServicio
 *
 * @author Administrador
 */
class servletFinalesServicio extends CommandController {

    public function doPost() {
        $dao = DAOFactory::getDAOFinalServicio('maria');
        switch ($_POST["action"]) {
			case 'update_peso_prioridad':
			
				$usuario_modificacion = $_POST['usuario_modificacion'];
				$peso = @$_POST['peso'];
				$prioridad = @$_POST['prioridad'];
				$codigo = @$_POST['codigo'];
				$idfinal_servicio = $_POST['id'];
				$flg_volver_llamar = $_POST['flg_volver_llamar'];
				$estado_observa = $_POST['estado_observa'];
				
				$dtoFinalServicio=new dto_final_servicios ;
				$dtoFinalServicio->setId($idfinal_servicio);
				$dtoFinalServicio->setPeso($peso);
				$dtoFinalServicio->setPrioridad($prioridad);
				$dtoFinalServicio->setCodigo($codigo);
				$dtoFinalServicio->setUsuarioModificacion($usuario_modificacion);
				$dtoFinalServicio->setFlgVolverLlamar($flg_volver_llamar);
				$dtoFinalServicio->setEstadoObserva($estado_observa);
				
				echo ($dao->UpdatePesoPrioridad($dtoFinalServicio))?json_encode(array('rst'=>true,'msg'=>'Peso y prioridad actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error actualizar peso y prioridad'));	
				
			break;
            case 'insert':
				
				$prioridad = (trim($_POST['Prioridad'])=='')?NULL:trim($_POST['Prioridad']);
				$peso = (trim($_POST['Peso'])=='')?NULL:trim($_POST['Peso']);
				$efecto = (trim($_POST['Efecto'])=='')?NULL:trim($_POST['Efecto']);
			
				$dtoFinalServicio=new dto_final_servicios ;
				$dtoFinalServicio->setIdFinal($_POST['Final']);
				$dtoFinalServicio->setIdServicio($_POST['Servicio']);
				$dtoFinalServicio->setPrioridad($prioridad);
				$dtoFinalServicio->setPeso($peso);
				$dtoFinalServicio->setEfecto($efecto);
				$dtoFinalServicio->setUsuarioCreacion($_POST['UsuarioCreacion']);
				
				$checkFinal=$dao->checkFinal($dtoFinalServicio);
				if( $checkFinal[0]['COUNT']==0 ){
					echo ($dao->insert($dtoFinalServicio))?json_encode(array('rst'=>true,'msg'=>'Final agregado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al agregar final'));	
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Final ya fue agregado'));
				}
               
            break;
			case 'delete':
				$dtoFinalServicio=new dto_final_servicios ;
				$dtoFinalServicio->setId($_POST['FinalServicio']);
				$dtoFinalServicio->setUsuarioModificacion($_POST['UsuarioModificacion']);
				
				echo ($dao->delete($dtoFinalServicio))?json_encode(array('rst'=>true,'msg'=>'Final eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar final'));	
			break;
            default:
				echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
            ;
        }
    }

    public function doGet() {
        $dao = DAOFactory::getDAOFinalServicio('maria');
        switch ($_GET["action"]) {
            case 'jqgrid_serviciosfinales':
				
				if(!isset($_GET["Servicio"])){
					echo '{"page":0,"total":0,"records":"0","rows":[]}';
					exit();
				}else if( $_GET['Servicio']=='' ) {
					echo '{"page":0,"total":0,"records":"0","rows":[]}';
					exit();
				}
				
				$dtoFinalServicio=new dto_final_servicios ;
				$dtoFinalServicio->setIdServicio($_GET['Servicio']);
								
                $page = $_GET["page"];
                $limit = $_GET["rows"];
                $sidx = $_GET["sidx"];
                $sord = $_GET["sord"];

                !$sidx ? $sidx = 1 : '';

                $row = $dao->COUNT($dtoFinalServicio);
                $count = $row[0]['COUNT'];
                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 0;
					$limit = 0;
                }

                if ($page > $total_pages)
                    $page = $total_pages;

                $start = $page * $limit - $limit;

                $response = array("page" => $page, "total" => $total_pages, "records" => $count);

                $data = $dao->queryJQGRID($sidx, $sord, $start, $limit,$dtoFinalServicio);
                $dataRow = array();
                for ($i = 0; $i < count($data); $i++) {
                    array_push($dataRow, array("id" => $data[$i]['id'], "cell" => array(
																						$data[$i]['id'], 
																						'<pre style="white-space:normal;word-wrap: break-word;">'.$data[$i]['nombre_final'].'</pre>',
																						$data[$i]['codigo'],
																						$data[$i]['prioridad'],
																						$data[$i]['peso'], 
																						$data[$i]['efecto'], 
																						$data[$i]['clase_final'], 
																						$data[$i]['fecha_registro'],
																						$data[$i]['flg_volver_llamar'],
																						$data[$i]['estado_observa']
																						)
												)
								);
                }
                $response["rows"] = $dataRow;
                echo json_encode($response);

                break;


            default:
				echo json_encode(array('rst' => false, 'msg' => 'Accion no encontrada' ));
            ;
        }
    }

}
?>
