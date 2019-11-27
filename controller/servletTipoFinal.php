<?php
	
	class servletTipoFinal extends CommandController {
		public function doGet ( ) {
			$daoTipoFinal=DAOFactory::getDAOTipoFinal('maria');
			switch ($_GET['action']):
				case 'jqgrid_tipo_final':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];

					if(!$sidx)$sidx=1 ;
					
					$row=$daoTipoFinal->COUNT();
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoTipoFinal->queryJQGRID($sidx,$sord,$start,$limit);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idtipo_final'],"cell"=>array($data[$i]['nombre'],$data[$i]['descripcion'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
			endswitch;
		}
	}
	
?>