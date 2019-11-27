<?php

class servletCampania extends CommandController {

    public function doPost ( ) {
        $dao=DAOFactory::getDAOCampania('maria');
        switch ( $_POST['action'] ):
            case 'ActEstadoCampania':
                
                $dto=new dto_campanias();
                $dto->setId($_POST['id']);
                $dto->setStatus($_POST['status']);
                $dto->setUsuarioModificacion($_POST['usuario_modificacion']);
                
                echo ($dao->updateStatusCampania($dto))?json_encode(array('rst'=>true,'msg'=>'Estado actualizado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al actualizar estado'));
                
            break;
            case 'save_campania':
                $dto=new dto_campanias();
                $dto->setIdServicio($_POST['Servicio']);
                $dto->setNombre($_POST['Nombre']);
                $dto->setFechaInicio($_POST['FechaInicio']);
                $dto->setFechaFin($_POST['FechaFin']);
                $dto->setDescripcion($_POST['Descripcion']);
                $dto->setUsuarioCreacion($_POST['UsuarioCreacion']);

                echo ($dao->insertDataCreation($dto))?json_encode(array('rst'=>true,'msg'=>'Campaña creada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al crear campaña'));

                break;
            case 'update_campania':
                $dto=new dto_campanias();
                $dto->setId($_POST['Id']);
                $dto->setNombre($_POST['Nombre']);
                $dto->setFechaInicio($_POST['FechaInicio']);
                $dto->setFechaFin($_POST['FechaFin']);
                $dto->setDescripcion($_POST['Descripcion']);
                $dto->setUsuarioModificacion($_POST['UsuarioModificacion']);

                echo ($dao->updateDataModification($dto))?json_encode(array('rst'=>true,'msg'=>'Campaña actualizada correctamente')):json_encode(array('rst'=>false,'Error al actualizar campaña'));

                break;
            case 'delete_campania':
                $dto=new dto_campanias();
                $dto->setId($_POST['Id']);
                $dto->setUsuarioModificacion($_POST['UsuarioModificacion']);
				
                echo ($dao->delete($dto))?json_encode(array('rst'=>true,'msg'=>'Campaña eliminada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al eliminar campaña'));

            break;
       	endswitch;
    }

    public function doGet ( ) {
        $daoCampania=DAOFactory::getDAOCampania('maria');
        $daoServicio=DAOFactory::getDAOServicio('maria');
        switch ($_GET['action']):
            case 'ListarServicio':
                echo json_encode($daoServicio->queryIdName());
                break;
            case 'DataById':
                $dto=new dto_campanias;
                $dto->setId($_GET['Id']);
                echo json_encode($daoCampania->queryById($dto));
                break;
            case 'listarCampaniaServicio':
                $dto = new dto_campanias();
                $dto->setIdServicio($_GET["idServicio"]);                
                echo $daoCampania->queryByUserService($dto);
                break;
            case 'jqgrid_campania':
                $page=$_GET["page"];
                $limit=$_GET["rows"];
                $sidx=$_GET["sidx"];
                $sord=$_GET["sord"];
                $servicio=$_GET['Servicio'];

                $dto=new dto_campanias;
                $dto->setIdServicio($servicio);

                if(!$sidx)$sidx=1 ;
                $row=$daoCampania->COUNTByServicio($dto);
                $count=$row[0]['COUNT'];
	        //$count=$row[0]['count'];
                if($count>0) {
                    $total_pages=ceil($count/$limit);
                }else {
                    $total_pages=0;
					$limit=0;
                }

                if($page>$total_pages) $page=$total_pages;

                $start=$page*$limit-$limit;

				/******* MYSQL ******/	
                /*$stmt=" SELECT idcampania,nombre,fecha_inicio,fecha_fin,descripcion
					FROM ca_campania WHERE estado=1 AND idservicio=$servicio ORDER BY $sidx $sord LIMIT $start , $limit ";*/
				/****** POSTGRES ******/
				$stmt=" SELECT idcampania,nombre,status,fecha_inicio,fecha_fin,descripcion
					FROM ca_campania WHERE estado=1 AND idservicio=$servicio ORDER BY $sidx $sord LIMIT $limit OFFSET $start ";	

                $response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);

                $data=$daoCampania->executeSelectString($stmt);
                $dataRow=array();
                for($i=0;$i<count($data);$i++) {
                    array_push($dataRow, array("id"=>$data[$i]['idcampania'],"cell"=>array($data[$i]['nombre'],$data[$i]['status'],$data[$i]['fecha_inicio'],$data[$i]['fecha_fin'],$data[$i]['descripcion'])));
                }
                $response["rows"]=$dataRow;
                echo json_encode($response);
                break;
            endswitch;
    }

}

?>