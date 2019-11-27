<?php

	class servletCargaFinal extends CommandController {
		public function doGet ( ) {
			$daoCargaFinal=DAOFactory::getDAOCargaFinal('maria');
			switch ($_GET['action']):
				case 'jqgrid_carga_final':
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];

					if(!$sidx)$sidx=1 ;
					
					$row=$daoCargaFinal->COUNT();
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
					
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);
					
					$data=$daoCargaFinal->queryJQGRID($sidx,$sord,$start,$limit);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idcarga_final'],"cell"=>array($data[$i]['nombre'],$data[$i]['descripcion'])));
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
				break;
			endswitch;
		}
	}

?>