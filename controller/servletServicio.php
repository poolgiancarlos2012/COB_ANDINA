<?php

class servletServicio extends CommandController {
    public function doPost ( ) {
        $dao=DAOFactory::getDAOServicio('maria');
        switch ($_POST['action']) :
            case 'save_servicio':
                $dto=new dto_servicio();
                $dto->setNombre($_POST['Nombre']);
                $dto->setDescripcion($_POST['Descripcion']);
                $dto->setUsuarioCreacion($_POST['UsuarioCreacion']);
                echo ($dao->insertNameDescriptionCreation($dto))?json_encode(array('rst'=>true,'msg'=>'Servicio grabado correctamente')):json_encode(array('rst'>false,'msg'=>'Error al grabar servicio'));

                break;
            case 'update_servicio':
                $dto=new dto_servicio();
                $dto->setId($_POST['Id']);
                $dto->setNombre($_POST['Nombre']);
                $dto->setDescripcion($_POST['Descripcion']);
                $dto->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($dao->updateNameDescriptionModification($dto))?json_encode(array('rst'=>true,'msg' =>'Servicio actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar servicio'));
                break;
            case 'delete_servicio':
                $dto=new dto_servicio ;
                $dto->setId($_POST['Id']);
                $dto->setUsuarioModificacion($_POST['UsuarioModificacion']);
                echo ($dao->delete($dto))?json_encode(array('rst'=>true,'msg'=>'Servicio eliminado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar servicio'));
			break;
		
		endswitch;


    }
    public function doGet ( ) {
        $dao=DAOFactory::getDAOServicio('maria');
		$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
        switch ($_GET['action']):
            case 'listar_servicio':
                echo json_encode( $dao->queryIdName() );
            break;
			case 'DataById':
				$dto=new dto_servicio ;
				$dto->setId($_GET['Id']);
				echo json_encode($dao->queryById($dto));
			break;
            case 'jqgrid_servicio':
               
                $page=$_GET["page"];
                $limit=$_GET["rows"];
                $sidx=$_GET["sidx"];
                $sord=$_GET["sord"];

                if(!$sidx)$sidx=1 ;
                $row=$dao->COUNT();
                $count=$row[0]['COUNT'];
                if($count>0) {
                    $total_pages=ceil($count/$limit);
                }else {
                    $total_pages=0;
                }

                if($page>$total_pages) $page=$total_pages;

                $start=$page*$limit-$limit;

                $stmt=" SELECT idservicio, nombre, descripcion
                FROM ca_servicio WHERE estado=1 ORDER BY $sidx $sord LIMIT $start , $limit ";
                
                $response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
                
                $data=$dao->executeString($stmt);
                $dataRow=array();
                for($i=0;$i<count($data);$i++){
                    array_push($dataRow, array("id"=>$data[$i]['idservicio'],"cell"=>array($data[$i]['nombre'],$data[$i]['descripcion'])));
                }
                $response["rows"]=$dataRow;
                echo json_encode($response);
               
                break;
				case 'jqgrid_usuarioAdmin_servicio':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
	
					if(!$sidx)$sidx=1 ;
					$row=$daoJqgrid->JQGRIDCountServicioUsuarioAdmin();
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsServicioUsuarioAdmin($sidx,$sord,$start,$limit);
					
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario'],"cell"=>array($data[$i]['usuario'],$data[$i]['dni'],$data[$i]['email'],$data[$i]['fecha_creacion'],$data[$i]['servicios'])));
					}
					$response["rows"]=$dataRow;
                	echo json_encode($response);
				break;
				case 'jqgrid_usuarioOpera_servicio':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];
	
					if(!$sidx)$sidx=1 ;
					$row=$daoJqgrid->JQGRIDCountServicioUsuarioOpera();

					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoJqgrid->JQGRIDRowsServicioUsuarioOpera($sidx,$sord,$start,$limit);

					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idusuario'],"cell"=>array($data[$i]['usuario'],$data[$i]['dni'],$data[$i]['email'],$data[$i]['fecha_creacion'],$data[$i]['servicios'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
            endswitch;
    }
}

?>
