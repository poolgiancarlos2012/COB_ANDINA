<?php

	class servletCalendar extends CommandController {
		public function doPost ( ) {
			$daoEvento=DAOFactory::getDAOEvento('maria'); 
			$daoTarea=DAOFactory::getDAOTarea('maria'); 
			switch ($_POST['action']) :
				case 'GuardarEventoMasivo':
					
					$evento =  $_POST['Evento'];
					$fecha = $_POST['Fecha'];
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']));
					$usuario_creacion = $_POST['UsuarioCreacion'];
					$hora = $_POST['Hora'];
					$idusuario_servicio = $_POST['UsuarioServicio'];
					
					
				break;
				case 'GuardarTareaMasiva':
					
					$titulo = $_POST['Titulo'];
					$fecha = $_POST['Fecha'];
					$hora = $_POST['Hora'];
					$nota = $_POST['Nota'];
					$usuario_creacion = $_POST['UsuarioCreacion'];
					$operadores = json_decode(str_replace("\\","",$_POST['operadores']));
					$idusuario_servicio = $_POST['UsuarioServicio'];
					
					
				break;
				case 'GuardarEvento':
					$dtoEvento=new dto_evento ;
					$dtoEvento->setEvento($_POST['Evento']);
					$dtoEvento->setFecha($_POST['Fecha']);
					$dtoEvento->setHora($_POST['Hora']);
					$dtoEvento->setIdUsuarioServicio($_POST['UsuarioServicio']);
					$dtoEvento->setUsuarioCreacion($_POST['UsuarioCreacion']);
					echo ($daoEvento->insert($dtoEvento))?json_encode(array('rst'=>true,'msg'=>'Evento guardado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al guardar evento'));
				break;
				case 'GuardarRangoEvento':
					$dtoEvento=new dto_evento ;
					$dtoEvento->setEvento($_POST['Evento']);
					$dtoEvento->setFecha($_POST['FechaInicio']);
					$dtoEvento->setFechaFin($_POST['FechaFin']);
					$dtoEvento->setHora($_POST['Hora']);
					$dtoEvento->setIdUsuarioServicio($_POST['UsuarioServicio']);
					$dtoEvento->setUsuarioCreacion($_POST['UsuarioCreacion']);
					echo ($daoEvento->insertRange($dtoEvento))?json_encode(array('rst'=>true,'msg'=>'Evento guardado correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al guardar evento'));
				break;
				case 'GuardarTarea':
					$dtoTarea=new dto_tarea ;
					$dtoTarea->setTitulo($_POST['Titulo']);
					$dtoTarea->setFecha($_POST['Fecha']);
					$dtoTarea->setHora($_POST['Hora']);
					$dtoTarea->setNota($_POST['Nota']);
					$dtoTarea->setIdUsuarioServicio($_POST['UsuarioServicio']);
					$dtoTarea->setUsuarioCreacion($_POST['UsuarioCreacion']);
					echo ($daoTarea->insert($dtoTarea))?json_encode(array('rst'=>true,'msg'=>'Tarea guardada correctamente')):json_encode(array('rst'=>false,'msg'=>'Error al guardar tarea'));
				break;
			endswitch;
		
		}
		public function doGet ( ) {
			$daoEvento=DAOFactory::getDAOEvento('maria'); 
			$daoTarea=DAOFactory::getDAOTarea('maria'); 
			$daoCalendar=DAOFactory::getDAOCalendar('maria'); 
			$daoJqgrid=DAOFactory::getDAOJqgrid('maria');
			switch ($_GET['action']):
				case 'LastEvent':
					$dtoEvento=new dto_evento ;
					$dtoEvento->setIdUsuarioServicio($_GET['UsuarioServicio']);
					echo json_encode($daoEvento->queryLastEvent($_GET['Anio'],$_GET['Mes'],$dtoEvento));
				break;
				case 'LastWork':
					$dtoTarea=new dto_tarea ;
					$dtoTarea->setIdUsuarioServicio($_GET['UsuarioServicio']);
					echo json_encode($daoTarea->queryLastWork($_GET['Anio'],$_GET['Mes'],$dtoTarea));
				break;
				case 'LastEventWork':
					$dtoTarea=new dto_tarea ;
					$dtoTarea->setIdUsuarioServicio($_GET['UsuarioServicio']);
					echo json_encode($daoCalendar->queryLastEventWork($_GET['Anio'],$_GET['Mes'],$dtoTarea));
				break;
				case 'ListEventRange':
					$dtoEvento=new dto_evento ;
					$dtoEvento->setIdUsuarioServicio($_GET['UsuarioServicio']);
					echo json_encode($daoEvento->queryEventRange($_GET['Anio'],$_GET['Mes'],$dtoEvento));
				break;
				case 'jqgrid_tarea':
					if(!isset($_GET["Anio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['Anio']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if(!isset($_GET["Mes"])) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';	
						exit();
					}else if( $_GET['Mes']=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if(!isset($_GET["Dia"])) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';	
						exit();
					}else if( $_GET['Dia']=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];

					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_GET['UsuarioServicio']);

					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountTarea($_GET['Anio'],$_GET['Mes'],$_GET['Dia'],$dtoUsuarioServicio);
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
						$limit=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);

					$data=$daoJqgrid->JQGRIDRowsTarea($sidx, $sord, $start, $limit, $_GET['Anio'],$_GET['Mes'],$_GET['Dia'],$dtoUsuarioServicio);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idtarea'],"cell"=>array(
																							$data[$i]['titulo'],
																							$data[$i]['hora'],
																							$data[$i]['nota']
																							)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);					
					
				break;
				case 'jqgrid_evento':
					
					if(!isset($_GET["Anio"])){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if( $_GET['Anio']=='' ) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if(!isset($_GET["Mes"])) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';	
						exit();
					}else if( $_GET['Mes']=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}else if(!isset($_GET["Dia"])) {
						echo '{"page":0,"total":0,"records":"0","rows":[]}';	
						exit();
					}else if( $_GET['Dia']=='' ){
						echo '{"page":0,"total":0,"records":"0","rows":[]}';
						exit();
					}
					
					$page=$_GET["page"];
					$limit=$_GET["rows"];
					$sidx=$_GET["sidx"];
					$sord=$_GET["sord"];

					$dtoUsuarioServicio=new dto_usuario_servicio ;
					$dtoUsuarioServicio->setId($_GET['UsuarioServicio']);

					if(!$sidx)$sidx=1 ;
					
					$row=$daoJqgrid->JQGRIDCountEvento($_GET['Anio'],$_GET['Mes'],$_GET['Dia'],$dtoUsuarioServicio);
					$count=$row[0]['COUNT'];
					if($count>0) {
						$total_pages=ceil($count/$limit);
					}else {
						$total_pages=0;
						$limit=0;
					}
	
					if($page>$total_pages) $page=$total_pages;
	
					$start=$page*$limit-$limit;
	
					$response=array("page"=>$page,"total"=>$total_pages,"records"=>$count);

					$data=$daoJqgrid->JQGRIDRowsEvento($sidx, $sord, $start, $limit, $_GET['Anio'],$_GET['Mes'],$_GET['Dia'],$dtoUsuarioServicio);
					$dataRow=array();
					for($i=0;$i<count($data);$i++){
						array_push($dataRow, array("id"=>$data[$i]['idevento'],"cell"=>array(
																							$data[$i]['evento'],
																							$data[$i]['hora']
																							)
													)
									);
					}
					$response["rows"]=$dataRow;
					echo json_encode($response);
					
				break;
				default:
					echo json_encode(array('rst'=>false,'msg'=>'Accion no encontrada'));
				;
			endswitch;
		}
	}

?>