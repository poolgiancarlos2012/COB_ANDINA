<?php

class PGSQL_PDOCargaCarteraDAO {

	public function executeQuery($sql) {
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		////$connection->beginTransaction();
		$pr = $connection->prepare($sql);
		if ($pr->execute()) {
			////$connection->commit();
			return true;
		} else {
			////$connection->rollBack();
			return false;
		}
	}

	public function executeQueryReturn($sql) {
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$pr = $connection->prepare($sql);
		$pr->execute();
		return $pr->fetchAll(PDO::FETCH_ASSOC);
	}

	public function uploadDocumentCartera($_post, $_files) {
		$idFile = $_post['idTmpFile'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files[$idFile]['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files[$idFile]['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files[$idFile]['name']));
				$_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
				$this->limpiarCartera3($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files[$idFile]['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files[$idFile]['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files[$idFile]['name']));		
					$_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
					$this->limpiarCartera3($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraNOC($_post, $_files) {
		if (@opendir('../documents/nocpredictivo/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraNOC']['tmp_name'], '../documents/nocpredictivo/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraNOC']['name'])) {
				$_post['file'] = $_files['uploadFileCarteraNOC']['name'];
				//$this->limpiarCarteraNOC($_post);
				$this->limpiarCartera4($_post,'nocpredictivo');
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {
			if (@mkdir('../documents/nocpredictivo/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraNOC']['tmp_name'], '../documents/nocpredictivo/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraNOC']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraNOC']['name'];
					//$this->limpiarCarteraNOC($_post);
					$this->limpiarCartera4($_post,'nocpredictivo');
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraCourier($_post, $_files) {
		if (@opendir('../documents/currier_visitas/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraCourier']['tmp_name'], '../documents/currier_visitas/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCourier']['name'])) {
				$_post['file'] = $_files['uploadFileCarteraCourier']['name'];

				$this->limpiarCartera4($_post,'currier_visitas');
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {
			if (@mkdir('../documents/currier_visitas/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraCourier']['tmp_name'], '../documents/currier_visitas/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCourier']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraCourier']['name'];

					$this->limpiarCartera4($_post,'currier_visitas');
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentFileDistribucionMecanico ( $_post, $_files ) {
		if( @opendir('../documents/carteras/'.$_post['NombreServicio']) ) {
			if(@move_uploaded_file($_files['fileDistribucionMecanica']['tmp_name'],'../documents/carteras/'.$_post['NombreServicio'].'/'.$_files['fileDistribucionMecanica']['name'])){
				$_post['file']=$_files['fileDistribucionMecanica']['name'];
				$this->limpiarCartera4($_post);
			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));		
			}

		}else{

			if( @mkdir('../documents/carteras/'.$_post['NombreServicio']) ){
				if(@move_uploaded_file($_files['fileDistribucionMecanica']['tmp_name'],'../documents/carteras/'.$_post['NombreServicio'].'/'.$_files['fileDistribucionMecanica']['name'])){
					$_post['file']=$_files['fileDistribucionMecanica']['name'];
					$this->limpiarCartera4($_post);
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));		
				}
			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al crear directorio'));
			}

		}	
	}

	public function uploadDocumentFileImportPCampania ( $_post, $_files ) {
		if( @opendir('../documents/pcampania/'.$_post['NombreServicio']) ) {
			if(@move_uploaded_file($_files['fileImportPCampania']['tmp_name'],'../documents/pcampania/'.$_post['NombreServicio'].'/'.$_files['fileImportPCampania']['name'])){
				$_post['file']=$_files['fileImportPCampania']['name'];
				$this->limpiarCartera4($_post,'pcampania');
			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));		
			}

		}else{

			if( @mkdir('../documents/pcampania/'.$_post['NombreServicio']) ){
				if(@move_uploaded_file($_files['fileImportPCampania']['tmp_name'],'../documents/pcampania/'.$_post['NombreServicio'].'/'.$_files['fileImportPCampania']['name'])){
					$_post['file']=$_files['fileImportPCampania']['name'];
					$this->limpiarCartera4($_post,'pcampania');
				}else{
					echo json_encode(array('rst'=>false,'msg'=>'Error al subir archivo al servidor'));		
				}
			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al crear directorio'));
			}

		}	
	}

	public function loadHeaderDistribucionMecanico ( $_post ) {
		$cartera = @$_post['cartera'];
		if( @opendir('../documents/carteras/'.$_post['NombreServicio']) ) {
			if( @file_exists('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']) ) {
				$dataFile=@fopen('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file'],"r+");
				function MapArrayHeader ( $n ) {

					$buscar=array("à","á","À","�?","é","è","É","È","í","ì","�?","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"',"?","¿","!","¡","[","]","-");
					$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");
					return str_replace($buscar,$cambia,trim(utf8_encode($n)));
				}
				$dataHeader = array();
				if( $_post['separador'] == 'tab' ) {
					$dataHeader=explode("\t",fgets($dataFile));
				}else{
					$dataHeader=explode($_post['separador'],fgets($dataFile));
				}

				fclose($dataFile);

				if( count($dataHeader)==1 ){
					echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto')); 
				}else{
					$dataHeaderMap=array_map("MapArrayHeader",$dataHeader);

					echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
				}
			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al leer archivo'));	
			}
		}else{
			echo json_encode(array('rst'=>false,'msg'=>'Error en directorio'));	
		}
	}

	public function uploadDocumentCarteraRetiro($_post, $_files) {
		if (@opendir('../documents/retiro/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraRetiro']['tmp_name'], '../documents/retiro/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRetiro']['name'])) {
				$_post['file'] = $_files['uploadFileCarteraRetiro']['name'];
				//$this->limpiarCarteraRetiro($_post);
				$this->limpiarCartera4($_post,'retiro');
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {
			if (@mkdir('../documents/retiro/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraRetiro']['tmp_name'], '../documents/retiro/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRetiro']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraRetiro']['name'];
					//$this->limpiarCarteraRetiro($_post);
					$this->limpiarCartera4($_post,'retiro');
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCorteFocalizado($_post, $_files) {
		if (@opendir('../documents/corte_focalizado/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCorteFocalizado']['tmp_name'], '../documents/corte_focalizado/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCorteFocalizado']['name'])) {
				$_post['file'] = $_files['uploadFileCorteFocalizado']['name'];
				//$this->limpiarCarteraCorteFocalizado($_post);
				$this->limpiarCartera4($_post,'corte_focalizado');
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {
			if (@mkdir('../documents/corte_focalizado/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCorteFocalizado']['tmp_name'], '../documents/corte_focalizado/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCorteFocalizado']['name'])) {
					$_post['file'] = $_files['uploadFileCorteFocalizado']['name'];
					//$this->limpiarCarteraCorteFocalizado($_post);
					$this->limpiarCartera4($_post,'corte_focalizado');
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentFacturacion($_post, $_files) {
		if (@opendir('../documents/facturacion_comision/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileFacturacion']['tmp_name'], '../documents/facturacion_comision/' . $_post['NombreServicio'] . '/' . $_files['uploadFileFacturacion']['name'])) {
				$_post['file'] = $_files['uploadFileFacturacion']['name'];
				$this->buildTableFacturacionComision(
					$_files['uploadFileFacturacion']['name'],
					$_post    
				);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {
			if (@mkdir('../documents/facturacion_comision/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileFacturacion']['tmp_name'], '../documents/facturacion_comision/' . $_post['NombreServicio'] . '/' . $_files['uploadFileFacturacion']['name'])) {
					$_post['file'] = $_files['uploadFileFacturacion']['name'];
					$this->buildTableFacturacionComision(
						$_files['uploadFileFacturacion']['name'],
						$_post    
					);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	private function buildTableFacturacionComision($file,$_post)
	{
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$path = "../documents/facturacion_comision/" . $_post["NombreServicio"] . "/" . $file;
		$archivoParser = @fopen($path, "r+");
		$columMap = explode("\t", fgets($archivoParser));
		$columnasTabla = array();
		fclose($archivoParser);
		foreach ($columMap as $key => $value) {
			if($value == 'cnt_total' or $value == 'cnt_recuperados')
			{
				array_push($columnasTabla, '`'.$value.'` int');
			}
			else if(strcmp ($value, 'FECHA INICIO') == 0 or strcmp ($value, 'FECHA FIN') == 0)
			{
				array_push($columnasTabla, '`'.$value.'` date');
			}
			else if($value == 'mto_total' or $value == 'mto_recuperados')
			{
				array_push($columnasTabla, '`'.$value.'` decimal(10,2)');
			}else{
				array_push($columnasTabla, $value.' varchar(200)');
			}
		}
		/*array_push($columnasTabla, '`OBJETIVO_MONTO` int');
		array_push($columnasTabla, '`OBJETIVO_CLIENTE` int');
		array_push($columnasTabla, '`MONTO_RECUPERADO` decimal(10,2)');
		array_push($columnasTabla, '`CLIENTE_RECUPERADO` decimal(10,2)');
		array_push($columnasTabla, '`COMISION` decimal(10,1)');
		array_push($columnasTabla, '`FACTURACION` decimal(10,2)');
		array_push($columnasTabla, 'id int auto_increment primary key');*/
		$time = date("Y_m_d_H_i_s");
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$nameTable = "tmpfacturacion_" . session_id() . "_" . $time;        
		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS $nameTable";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		try {
			if ($prDropTableTMPCartera->execute()) {
				$sqlCreateTabelTMPCartera = " CREATE TABLE $nameTable ( ".implode(",", $columnasTabla)." ) ENGINE = InnoDB DEFAULT CHARACTER SET = latin1 ";
				//echo $sqlCreateTabelTMPCartera;
				$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
				if ($prsqlCreateTabelTMPCartera->execute()) {
					$sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/facturacion_comision/" . $_post['NombreServicio'] . "/" . $file . "'
									 INTO TABLE $nameTable FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
					$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
					if ($prLoadDataInFileUC->execute()) {
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN OBJETIVO_MONTO int");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN OBJETIVO_CLIENTE int");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN MONTO_RECUPERADO decimal(10,2)");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN CLIENTE_RECUPERADO decimal(10,2)");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN COMISION decimal(10,1)");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN FACTURACION decimal(10,2)");
						$connection->exec("ALTER TABLE $nameTable ADD COLUMN id int auto_increment primary key");
						$this->updateObjetivosTablaTmpFacturacion($nameTable);
						$this->calcClientMontosRecuperadosTablaTmpFacturacion($nameTable);
						$this->calcularComisionesTablaTmpFacturacion($nameTable);
						$this->calcularFacturacionTablaTmpFacturacion($nameTable);
						echo json_encode(array('rst' => true, 'msg' => 'Archivo Procesado Correctamente','tabla' => $nameTable));
					}
				}
			}
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}
	private function updateObjetivosTablaTmpFacturacion($nameTable)
	{
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$campo = 'objetivo_monto';
		$queryIncompleto = "UPDATE $nameTable
				INNER JOIN ca_clusters_maestros 
								ON $nameTable.cluster = ca_clusters_maestros.nombre
				INNER JOIN ca_tramos_maestros 
								ON $nameTable.tramo = ca_tramos_maestros.nombre
				INNER JOIN ca_objetivo 
						ON ca_objetivo.idtrama = ca_tramos_maestros.idtrama AND ca_objetivo.idcluma = ca_clusters_maestros.idcluma  
				set ";
		$sql1 = $queryIncompleto.$campo." = porcentaje WHERE idtipoobj = 2";
		$campo = 'objetivo_cliente';
		$sql2 = $queryIncompleto.$campo." = porcentaje WHERE idtipoobj = 1";
		$sql3 = "UPDATE $nameTable SET objetivo_monto = objetivo_monto_modificado WHERE NOT objetivo_monto_modificado = ''";
		$sql4 = "UPDATE $nameTable SET objetivo_cliente = objetivo_cliente_modificado WHERE NOT objetivo_cliente_modificado = ''";
		$estado1 = $connection->exec($sql1);
		$estado2 = $connection->exec($sql2);
		$estado3 = $connection->exec($sql3);
		$estado4 = $connection->exec($sql4);
		if($estado1 === false or $estado2 === false or $estado3 === false or $estado4 === false)
		{
			throw new Exception('Ocurrio un error al actualizar los valores de los objetivos');
		}
	}
	private function calcularComisionesTablaTmpFacturacion($nameTable)
	{
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$queryComision = "SELECT porcentaje FROM ca_objetivo INNER JOIN ca_clusters_maestros ON ca_clusters_maestros.idcluma = ca_objetivo.idcluma INNER JOIN ca_tramos_maestros ON ca_tramos_maestros.idtrama.ca_objetivo.idtrama ";
		$sql1 = "UPDATE $nameTable
				INNER JOIN ca_clusters_maestros 
								ON $nameTable.cluster = ca_clusters_maestros.nombre
				INNER JOIN ca_tramos_maestros 
								ON $nameTable.tramo = ca_tramos_maestros.nombre
				INNER JOIN ca_comision_cluster_tramo 
						ON ca_comision_cluster_tramo.idtrama = ca_tramos_maestros.idtrama AND ca_comision_cluster_tramo.idcluma = ca_clusters_maestros.idcluma 
				SET comision = porcentaje WHERE monto_recuperado between rangoObjMontoInicial AND rangoObjMontoFinal AND cliente_recuperado between rangoObjClieInicial and rangoObjClieFinal";
		$stm1 = $connection->prepare($sql1);
		if($stm1->execute())
		{
			$queryGetRegistrosConObjetivosCambiados = "SELECT id,objetivo_cliente,objetivo_monto,tramo,cluster FROM $nameTable WHERE NOT objetivo_cliente_modificado = '' OR NOT objetivo_monto_modificado = ''";
			$stm = $connection->prepare($queryGetRegistrosConObjetivosCambiados);
			$stm->execute();
			$arrObjetivosCambiados  = $stm->fetchAll();
			if(is_array($arrObjetivosCambiados) AND count($arrObjetivosCambiados) > 0)
			{
				foreach ($arrObjetivosCambiados as $key => $row) {
					$objetivoY = $row['objetivo_cliente'];
					$objetivoX = $row['objetivo_monto'];
					$tramo = $row['tramo'];
					$cluster = $row['cluster'];
					$id = $row['id'];
					$querySelectComisiones = "SELECT idcomiclutra,ca_comision_cluster_tramo.idtrama,ca_comision_cluster_tramo.idcluma,rangoObjClieInicial,rangoObjClieFinal,rangoObjMontoInicial,rangoObjMOntoFinal,porcentaje, 1 as idTmpFact FROM ca_comision_cluster_tramo
						INNER JOIN ca_clusters_maestros ON ca_clusters_maestros.idcluma = ca_comision_cluster_tramo.idcluma
						INNER JOIN ca_tramos_maestros ON ca_tramos_maestros.idtrama = ca_comision_cluster_tramo.idtrama
						WHERE ca_clusters_maestros.nombre = '$cluster' AND ca_tramos_maestros.nombre = '$tramo'";
					$stm = $connection->prepare($querySelectComisiones);
					$stm->execute();
					$arrRegComisiones = $stm->fetchAll(PDO::FETCH_ASSOC);
					$arrRangosXY = array(
						array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[0]['idcomiclutra']),
						array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[1]['idcomiclutra']),
						array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[2]['idcomiclutra']),
						array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[3]['idcomiclutra']),
						array('rangoInicialY' => (0), 'rangoFinalY' => ($objetivoY - 0.01), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[4]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[5]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[6]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[7]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[8]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY), 'rangoFinalY' => ($objetivoY + 4.99), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[9]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => (0), 'rangoFinalX' => ($objetivoX - 2.01), 'id' => $arrRegComisiones[10]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX - 2), 'rangoFinalX' => ($objetivoX - 0.01), 'id' => $arrRegComisiones[11]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX), 'rangoFinalX' => ($objetivoX + 1.99), 'id' => $arrRegComisiones[12]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX + 2), 'rangoFinalX' => ($objetivoX + 3.99), 'id' => $arrRegComisiones[13]['idcomiclutra']),
						array('rangoInicialY' => ($objetivoY + 5), 'rangoFinalY' => (100), 'rangoInicialX' => ($objetivoX + 4), 'rangoFinalX' => (100), 'id' => $arrRegComisiones[14]['idcomiclutra'])
					);
					$queryDropTableTmpComision = "DROP TABLE IF EXISTS tmpComision";
					$queryCreateTableTmp = "create table tmpComision($querySelectComisiones)";
					$estadoDrop = $connection->exec($queryDropTableTmpComision);
					$stm = $connection->prepare($queryCreateTableTmp);
					//var_dump($stm->execute());
					if($stm->execute())
					{
						foreach ($arrRangosXY as $key2 => $value) {
							$rangoInicialY = $value['rangoInicialY'];
							$rangoFinalY = $value['rangoFinalY'];
							$rangoInicialX = $value['rangoInicialX'];
							$rangoFinalX = $value['rangoFinalX'];
							$idComiCluTra = $value['id'];
							$queryUpdateTmpComisiones = "UPDATE tmpComision 
								SET rangoObjClieInicial = $rangoInicialY, rangoObjClieFinal = $rangoFinalY,rangoObjMontoInicial = $rangoInicialX,rangoObjMOntoFinal = $rangoFinalX
							WHERE idcomiclutra = $idComiCluTra";
							$estado = $connection->exec($queryUpdateTmpComisiones);
						}
						$queryUpdateComision = "UPDATE $nameTable
							INNER JOIN ca_clusters_maestros 
											ON $nameTable.cluster = ca_clusters_maestros.nombre
							INNER JOIN ca_tramos_maestros 
											ON $nameTable.tramo = ca_tramos_maestros.nombre
							INNER JOIN tmpComision 
									ON tmpComision.idtrama = ca_tramos_maestros.idtrama AND tmpComision.idcluma = ca_clusters_maestros.idcluma 
							SET comision = porcentaje WHERE monto_recuperado between rangoObjMontoInicial AND rangoObjMontoFinal AND cliente_recuperado between rangoObjClieInicial and rangoObjClieFinal AND id = $id";       
						$estado = $connection->exec($queryUpdateComision);
					}
				}
			}
		}
	}
	private function calcularFacturacionTablaTmpFacturacion($nameTable)
	{
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$sql = "UPDATE $nameTable SET facturacion = (comision * mto_recuperados)/100 WHERE NOT ISNULL(comision) AND NOT ISNULL(mto_recuperados)";
		$estado = $connection->exec($sql);
		if($estado === false)
		{
			throw new Exception('Ocurrio un error al calcular el monto de facturacion');
		}
	}
	private function calcClientMontosRecuperadosTablaTmpFacturacion($nameTable)
	{
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$sql = "UPDATE $nameTable SET MONTO_RECUPERADO = (mto_recuperados/mto_total)*100,CLIENTE_RECUPERADO = (cnt_recuperados/cnt_total)*100";
		//$sql1 = "UPDATE $nameTable SET COMISION = '=SUMA(K1,L2)'";
		$estado = $connection->exec($sql);
		//$estado = $connection->exec($sql1);
		if($estado === false or $estado === 0){
			throw new Exception('No se pudo calcular los clientes y montos retirados correctamente');
		}
	}
	public function downloadFileFacturacion($nameTable)
	{
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=FACTURACION.xls");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		$sql = "DESCRIBE $nameTable";
		$stm = $connection->prepare($sql);
		$tabla = '<table>';
		$indexCampoObjetivoClienteModificado = 0;
		$indexCampoObjetivoMontoModificado = 0;
		if($stm->execute())
		{
			$tabla .= '<tr>';
			foreach ($stm->fetchAll(PDO::FETCH_NUM) as $key => $row) {
				$porcentaje = '';
				if(strcasecmp($row[0], 'objetivo_cliente_modificado') == 0)$indexCampoObjetivoClienteModificado = $key;
				if(strcasecmp($row[0], 'objetivo_monto_modificado') == 0)$indexCampoObjetivoMontoModificado = $key;
				if(strcasecmp($row[0], 'MONTO_RECUPERADO') == 0 or strcasecmp($row[0], 'CLIENTE_RECUPERADO') == 0 or strcasecmp($row[0], 'OBJETIVO_CLIENTE') == 0 or strcasecmp($row[0], 'OBJETIVO_MONTO') == 0)
				{
					$porcentaje = '%';
				}
				if(strcasecmp($row[0], 'objetivo_cliente_modificado') != 0 and strcasecmp($row[0], 'objetivo_monto_modificado') != 0)
				{
					$tabla .= '<td>'.$row[0].' '.$porcentaje.'</td>';
				}
			}

			$tabla .= '</tr>';
			$sql = "SELECT * FROM $nameTable";
			$stm = $connection->prepare($sql);
			if($stm->execute())
			{
				foreach ($stm->fetchAll(PDO::FETCH_NUM) as $key => $row) {
					$tabla .= '<tr>';
					foreach ($row as $keyColumn => $value) {
						if($indexCampoObjetivoClienteModificado != $keyColumn and $indexCampoObjetivoMontoModificado != $keyColumn)
						{
							$tabla .= '<td>'.$value.'</td>';
						}
					}
					$tabla .= '</tr>';
				}
			}
			$sql = "DROP TABLE $nameTable";
			$connection->exec($sql);
			echo $tabla;

		}else{
			throw new Exception('No se pudo obtener los registros');
		}
	}
	public function actualizarCortesFocalizados($servicio, $files) {
		$rpt = array('msg' => '', 'resumen' => array(), 'rst' => false);
		$correcto = true;
		$files = explode(':', $files);
		//var_dump($files);
		$idsCuentas = '';
		for ($i = 0; $i < count($files); $i++) {
			if (!empty($files[$i])) {
				$path = "../documents/corte_focalizado/" . $servicio . "/" . $files[$i];
				if (!file_exists($path)) {
					$error = array('msg' => 'Archivo ' . $files[$i] . ' no existe o fue removido, intente subir otra vez Archivos');
					$correcto = false;
					array_push($rpt['resumen'], $error);
				} else {
					$archivo = @fopen($path, 'r+');
					if ($archivo) {
						$count = 0;
						while (!feof($archivo)) {
							$linea = fgets($archivo);
							if ($count != 0) {
								$arrLine = explode("|", $linea);
								if (trim($arrLine[0]) != '') {
									$idsCuentas .= trim($arrLine[0]) . ',';
								}
							}
							$count++;
						}
						fclose($archivo);
					} else {
						@fclose($archivo);
						$error = array('msg' => 'Error al leer el archivo' . $files[$i]);
						$correcto = false;
						array_push($rpt['resumen'], $error);
					}
				}
			}
		}
		$idsCuentas = substr($idsCuentas, 0, strlen($idsCuentas) - 1);
		$sql = "UPDATE ca_cuenta SET corte_focalizado = 1 WHERE idcuenta IN($idsCuentas)";
		//echo $sql;
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();
		//$connection->beginTransaction();
		$stm = $connection->prepare($sql);
		if ($stm->execute()) {
			$rpt['rst'] = true;
			//$connection->commit();
		}
		$rpt['msg'] = 'Se actualizaron ' . $stm->rowCount() . ' cuentas';
		echo json_encode($rpt);
	}

	public function uploadDocumentCarteraIVR($_post, $_files) {
		if (@opendir('../documents/ivr/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraIVR']['tmp_name'], '../documents/ivr/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraIVR']['name'])) {
				$_post['file'] = $_files['uploadFileCarteraIVR']['name'];
				//echo json_encode(array('rst'=>true,'msg'=>'subir archivo al servidor'));		
				//$this->limpiarCarteraIVR($_post);
				$this->limpiarCartera4($_post,'ivr');
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidorRRRRR'));
			}
		} else {
			if (@mkdir('../documents/ivr/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraIVR']['tmp_name'], '../documents/ivr/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraIVR']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraIVR']['name'];
					//$this->limpiarCarteraIVR($_post);
					$this->limpiarCartera4($_post,'ivr');
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor luego de crear carpeta'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentLimpiarCartera($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadLimpiarCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadLimpiarCartera']['name'])) {
				echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente', 'file' => $_files['uploadLimpiarCartera']['name']));
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadLimpiarCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadLimpiarCartera']['name'])) {
					echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente', 'file' => $_files['uploadLimpiarCartera']['name']));
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraCentroPago($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraCentroPago']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCentroPago']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
				$_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
				$this->limpiarCartera($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraCentroPago']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraCentroPago']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));		
					$_post['file'] = $_files['uploadFileCarteraCentroPago']['name'];
					$this->limpiarCartera($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraPlanta($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraPlanta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPlanta']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));
				$_post['file'] = $_files['uploadFileCarteraPlanta']['name'];
				$this->limpiarCartera3($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraPlanta']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPlanta']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraCentroPago']['name']));		
					$_post['file'] = $_files['uploadFileCarteraPlanta']['name'];
					$this->limpiarCartera3($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function loadHeaderCarteraPlanta($_post) {
		$path = '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {

				//$dataFile=file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);

				/*                 * *** */
				$archivo = file($path);

				//$tmpArchivo=fopen('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file'],'w');
//				fwrite($tmpArchivo,'');
//				fclose($tmpArchivo);
//				
//				$countHeader=0;
//				
//				$tmpArchivo=fopen($path,'a+');
//				
//				for( $i=0;$i<count($archivo);$i++ ){
//					if( $i==0 ) {
//						$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"');
//						$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'');
//						$line=str_replace($buscar,$cambia,trim(utf8_encode($archivo[$i])));
//						$explode_header=explode($_post['separator'],$line);
//						//print_r($explode_header);
//						for( $j=0;$j<count($explode_header);$j++ ) {
//							if( $explode_header[$j]=='' ) {
//								fclose($tmpArchivo);
//								unlink($path);
//								echo json_encode(array('rst'=>false,'msg'=>'Existen cabeceras vacias'));
//								exit();
//							}
//						}
//						$countHeader=count($explode_header);
//						fwrite($tmpArchivo,$line);
//					}else{
//						$buscar=array('"',"'","#","&");
//						$cambia=array('',"","","");
//						//$line=str_replace("	","|",$archivo[$i]);
//						$line=str_replace($buscar,$cambia,trim(utf8_encode($archivo[$i])));
//						$explode_line=explode($_post['separator'],$line);
//						if( count($explode_line)!=$countHeader ) {
//							fclose($tmpArchivo);
//							unlink($path);
//							echo json_encode(array('rst'=>false,'msg'=>'Linea '.($i+1).' no coincide con longitud de cabeceras'));
//							exit();
//						}
//						fwrite($tmpArchivo,$line);
//					}
//				}
//				
//				fclose($tmpArchivo);
//				//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente'));
//				
//				/******/
//				$archivo = file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);	
				$dataHeader = explode($_post['separator'], $archivo[0]);
				//$dataHeaderMap=array_map("MapArrayHeader",$dataHeader);
				echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeader));
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function uploadDocumentCarteraPago2($_post, $files) {

		if (opendir('../documents/carteras/TG_FIJA')) {

			if (move_uploaded_file($files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/TG_FIJA/' . $_post['uploadFileCarteraPagoMain']['name'])) {

			} else {

			}
		} else {

		}
	}

	public function uploadDocumentCarteraPago($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPagoMain']['name'])) {

				$_post['file'] = $_files['uploadFileCarteraPagoMain']['name'];
				if ($_post['idCabecera'] == '0') {
					$this->limpiarCarteraPago($_post);
				} else {
					$this->limpiarCarteraPagoAddHeader($_post);
				}
			} else {
				//echo $_files['uploadFileCarteraPagoMain']['name'];
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor, (Abrir carpeta)'));
			}
		} else {
			if (mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (move_uploaded_file($_files['uploadFileCarteraPagoMain']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraPagoMain']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraPagoMain']['name'];
					if ($_post['idCabecera'] == '0') {
						$this->limpiarCarteraPago($_post);
					} else {
						$this->limpiarCarteraPagoAddHeader($_post);
					}
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor,( Crear carpeta )'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraTelefono($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraTelefono']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraTelefono']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraPago']['name']));
				$_post['file'] = $_files['uploadFileCarteraTelefono']['name'];
				//$this->limpiarCartera($_post);
				$this->limpiarCartera4($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraTelefono']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraTelefono']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCarteraPago']['name']));		
					$_post['file'] = $_files['uploadFileCarteraTelefono']['name'];
					//$this->limpiarCartera($_post);
					$this->limpiarCartera4($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraPrincipal($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCartera']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
				$_post['file'] = $_files['uploadFileCartera']['name'];
				//$this->limpiarCartera2($_post);
				if ($_post['ModoCarga'] == '0') {
					/* if( $_post['idCabecera'] != '0' ) { 
					  $this->limpiarCarteraAgregarCabecera($_post);
					  }else{ */
					$this->limpiarCartera4($_post);
					//}
				} else if ($_post['ModoCarga'] == 'agregar') {
					$this->limpiarCarteraSoloAgregarCabecera($_post);
				} else if ($_post['ModoCarga'] == 'agregar_dividir') {
					$this->limpiarCarteraAgregarCabecera($_post);
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCartera']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCartera']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));		
					$_post['file'] = $_files['uploadFileCartera']['name'];
					//$this->limpiarCartera2($_post);
					//$this->limpiarCartera3($_post);
					/* if( $_post['idCabecera'] != '0' ) {
					  $this->limpiarCarteraAgregarCabecera($_post);
					  }else{
					  $this->limpiarCartera3($_post);
					  } */
					if ($_post['ModoCarga'] == '0') {
						$this->limpiarCartera4($_post);
					} else if ($_post['ModoCarga'] == 'agregar') {
						$this->limpiarCarteraSoloAgregarCabecera($_post);
					} else if ($_post['ModoCarga'] == 'agregar_dividir') {
						$this->limpiarCarteraAgregarCabecera($_post);
					}
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraDetalle($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraDetalle']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalle']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
				$_post['file'] = $_files['uploadFileCarteraDetalle']['name'];
				//$this->limpiarCartera2($_post);
				$this->limpiarCartera3($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraDetalle']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraDetalle']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));		
					$_post['file'] = $_files['uploadFileCarteraDetalle']['name'];
					//$this->limpiarCartera2($_post);
					$this->limpiarCartera3($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraReclamo($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraReclamo']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraReclamo']['name'])) {
				//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));
				$_post['file'] = $_files['uploadFileCarteraReclamo']['name'];
				//$this->limpiarCartera2($_post);
				$this->limpiarCartera4($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraReclamo']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraReclamo']['name'])) {
					//echo json_encode(array('rst'=>true,'msg'=>'Archivo guardado correctamente','file'=>$_files['uploadFileCartera']['name']));		
					$_post['file'] = $_files['uploadFileCarteraReclamo']['name'];
					//$this->limpiarCartera2($_post);
					$this->limpiarCartera4($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function uploadDocumentCarteraRRLL($_post, $_files) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@move_uploaded_file($_files['uploadFileCarteraRRLL']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRRLL']['name'])) {
				$_post['file'] = $_files['uploadFileCarteraRRLL']['name'];
				$this->limpiarCartera3($_post);
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
			}
		} else {

			if (@mkdir('../documents/carteras/' . $_post['NombreServicio'])) {
				if (@move_uploaded_file($_files['uploadFileCarteraRRLL']['tmp_name'], '../documents/carteras/' . $_post['NombreServicio'] . '/' . $_files['uploadFileCarteraRRLL']['name'])) {
					$_post['file'] = $_files['uploadFileCarteraRRLL']['name'];
					$this->limpiarCartera3($_post);
				} else {
					echo json_encode(array('rst' => false, 'msg' => 'Error al subir archivo al servidor'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
			}
		}
	}

	public function loadHeader($_post) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				$dataHeader = explode($_post['separator'], fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function loadHeader2($_post) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ".", "#", " ", "/", "�", "�", "@", "(", ")", "$", "&", "%", "'", '"', "?", "�", "!", "�", "[", "]", "-", "�");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",".","#"," ","/","�","�","@","(",")","$","&","%","\t","'",'"',"?","�","!","�","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				//$dataHeader=explode($_post['separator'],fgets($dataFile));
				$dataHeader = array();
				if ($_post['separator'] == 'tab') {
					$dataHeader = explode("\t", fgets($dataFile));
				} else {
					$dataHeader = explode($_post['separator'], fgets($dataFile));
				}
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * *************** */
					$sql = " SELECT idjson_parser, cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, 
						direccion,adicionales,codigo_cliente,numero_cuenta, codigo_operacion, separador  
						FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);

					$cabeceras = array();
					$dataJsonParserBefore = array();
					if(count($rs)==0) 
					{
						$cabeceras = array();
					}
					else
					{
						$cabeceras = explode(",",$rs[0]['cabeceras']);
						$dataJsonParserBefore = $rs[0];
					}
					$notHeader = array();
					//$yesHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {

						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							//array_push($yesHeader,$dataHeaderMap[$i]);
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}

					/*                     * *************** */
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $dataJsonParserBefore));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	/*     * ************* */

	public function loadHeaderPago($_post) {
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				$dataHeader = explode($_post['separator'], fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * ********** */
					$sql = " SELECT idjson_parser_pago,codigo_cliente,numero_cuenta,codigo_operacion,pago,cabeceras
						FROM ca_json_parser_pago WHERE idservicio = ? ORDER BY idjson_parser_pago DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);

					$cabeceras = explode(",", ((count($rs)>0)?$rs[0]['cabeceras']:"") );
					$notHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {
						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}
					/*                     * ********** */
					//echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => ((count($rs)>0)?$rs[0]:array())));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function loadHeaderDetalle($_post) {
		$cartera = $_post['cartera'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				$dataHeader = explode($_post['separator'], fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * ********** */
					//$sql = " SELECT car.idcartera, car.numero_cuenta, car.moneda_cuenta,car.detalle_cuenta, car.adicionales, car.cabeceras
//						FROM ca_servicio ser INNER JOIN ca_campania cam INNER JOIN ca_cartera car
//						ON car.idcampania = cam.idcampania AND cam.idservicio = ser.idservicio
//						WHERE ser.idservicio = ? AND idcartera != ?
//						ORDER BY car.idcartera DESC LIMIT 1 ";

					$sql = " SELECT numero_cuenta_detalle, moneda_detalle, codigo_operacion_detalle, detalle_cuenta, adicionales, cabeceras_detalle FROM ca_json_parser WHERE idservicio = ? AND idjson_parser != ( SELECT MAX(idjson_parser) FROM ca_json_parser WHERE idservicio = ? ) ORDER BY idjson_parser DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->bindParam(2, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					//$pr->bindParam(2,$cartera,PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);

					$cabeceras = explode(",", $rs[0]['cabeceras_detalle']);
					$notHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {
						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}
					/*                     * ********** */
					//echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function loadHeaderReclamo($_post) {
		$cartera = @$_post['cartera'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				$dataHeader = array();
				if ($_post['separator'] == 'tab') {
					$dataHeader = explode("\t", fgets($dataFile));
				} else {
					$dataHeader = explode($_post['separator'], fgets($dataFile));
				}
				//$dataHeader=explode($_post['separator'],fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * ********** */
					$sql = " SELECT carre.idcartera_reclamo ,carre.reclamo, carre.cabeceras 
						FROM ca_cartera_reclamo carre INNER JOIN ca_cartera car INNER JOIN ca_campania cam 
						ON cam.idcampania = car.idcampania AND car.idcartera = carre.idcartera 
						WHERE cam.idservicio = ? ORDER BY carre.idcartera_reclamo DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);

					$cabeceras = explode(",", ((count($rs)>0)?$rs[0]['cabeceras']:"") );
					$notHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {
						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}
					/*                     * ********** */
					//echo json_encode(array('rst'=>true,'msg'=>'Cabeceras cargadas correctamente','header'=>$dataHeaderMap));
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => ( (count($rs)>0)?$rs[0]:array() ) ));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function loadHeaderRRLL($_post) {
		$cartera = $_post['cartera'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				$dataHeader = explode($_post['separator'], fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * ********** */
					$sql = " SELECT rrll.idcartera_rrll ,rrll.rrll, rrll.cabeceras 
						FROM ca_cartera_rrll rrll INNER JOIN ca_cartera car INNER JOIN ca_campania cam 
						ON cam.idcampania = car.idcampania AND car.idcartera = rrll.idcartera 
						WHERE cam.idservicio = ? ORDER BY rrll.idcartera_rrll DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);

					$cabeceras = explode(",", $rs[0]['cabeceras']);
					$notHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {
						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}

					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function loadHeaderTelefono($_post) {
		//$cartera = $_post['cartera'];
		if (@opendir('../documents/carteras/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'])) {
				//$dataFile=@file('../documents/carteras/'.$_post['NombreServicio'].'/'.$_post['file']);
				$dataFile = @fopen('../documents/carteras/' . $_post['NombreServicio'] . '/' . $_post['file'], "r+");

				function MapArrayHeader($n) {

					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					return str_replace($buscar, $cambia, trim(utf8_encode($n)));
				}

				//$dataHeader=explode($_post['separator'],$dataFile[0]);
				/*                 * ************ */
				$dataHeader = array();
				if ($_post['separator'] == 'tab') {
					$dataHeader = explode("\t", fgets($dataFile));
				} else {
					$dataHeader = explode($_post['separator'], fgets($dataFile));
				}
				/*                 * ************ */
				//$dataHeader=explode($_post['separator'],fgets($dataFile));
				fclose($dataFile);

				if (count($dataHeader) == 1) {
					echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
				} else {
					$dataHeaderMap = array_map("MapArrayHeader", $dataHeader);
					/*                     * ********** */
					$sql = " SELECT carte.idcartera_telefono ,carte.telefono, carte.cabeceras 
						FROM ca_cartera_telefono carte INNER JOIN ca_cartera car INNER JOIN ca_campania cam 
						ON cam.idcampania = car.idcampania AND car.idcartera = carte.idcartera 
						WHERE cam.idservicio = ? ORDER BY carte.idcartera_telefono DESC LIMIT 1 ";

					$factoryConnection = FactoryConnection::create('mysql');
					$connection = $factoryConnection->getConnection();

					////$connection->beginTransaction();

					$pr = $connection->prepare($sql);
					$pr->bindParam(1, $_SESSION['cobrast']['idservicio'], PDO::PARAM_INT);
					$pr->execute();
					$rs = $pr->fetchAll(PDO::FETCH_ASSOC);
					if( count($rs)==0 ) {
						echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $dataHeaderMap, 'dataJsonParserBefore' => array()));    
						exit();
					}

					$cabeceras = explode(",", $rs[0]['cabeceras']);
					$notHeader = array();
					for ($i = 0; $i < count($dataHeaderMap); $i++) {
						if (!in_array($dataHeaderMap[$i], $cabeceras)) {
							array_push($notHeader, $dataHeaderMap[$i]);
						}
					}
					echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeaderMap, 'headerNot' => $notHeader, 'dataJsonParserBefore' => $rs[0]));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	/*     * ************* */

	public function limpiarCartera($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = file($path);

		$tmpArchivo = fopen($path, 'w');
		fwrite($tmpArchivo, '');
		fclose($tmpArchivo);

		$countHeader = 0;

		$tmpArchivo = fopen($path, 'a+');

		for ($i = 0; $i < count($archivo); $i++) {
			if ($i == 0) {
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "");
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"',"?","¿","!","¡","[","]");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","");
				$line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
				$explode_header = explode("|", $line);

				for ($j = 0; $j < count($explode_header); $j++) {
					if ($explode_header[$j] == '') {
						fclose($tmpArchivo);
						unlink($path);
						echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias (LC)'));
						exit();
					}
				}
				$countHeader = count($explode_header);
				fwrite($tmpArchivo, $line . "\r\n");
			} else {
				$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", "¥");
				$cambia = array('', "", "|", "", "", "", "", "", "", "N");
				$line = str_replace("	", "|", $archivo[$i]);
				$line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
				//$explode_line=explode("|",$line);
//				if( count($explode_line)!=$countHeader ) {
//					fclose($tmpArchivo);
//					//unlink($path);
//					echo json_encode(array('rst'=>false,'msg'=>'Linea '.($i+1).' tiene demasiados datos'));
//					exit();
//				}
				fwrite($tmpArchivo, $line . "\r\n");
			}
		}

		fclose($tmpArchivo);
		echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $_post['file']));
	}

	public function limpiarCartera2($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/TMP" . $_post["file"], 'a+');
		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
			exit();
		}

		$archivo = @fopen($path, "r+");

		$countHeader = 0;

		/* $struc=array(
		  "NROINS"=>array(0,15),"CLIENTE"=>array(15,10),"CUENTA"=>array(25,10),
		  "COD_ESTA_CD"=>array(35,2),"MOT_ESTA_CMR_CD"=>array(37,9),"COD_NAT_CTA_CD"=>array(46,9),
		  "COD_SGM_CTA_CD"=>array(55,9),"COD_SBG_CTA_CD"=>array(64,9),"IND_STP_FTC_CD"=>array(73,1),
		  "IND_CLI_ITB_IN"=>array(74,1),"NUM_INDE_UN"=>array(75,40),"COD_TIP_DOC_CD"=>array(115,2),
		  "NUM_DOC_CD"=>array(117,30),"COD_EMP_EXT_CD"=>array(147,9),"AGR_COD_FTC_CD"=>array(156,9),
		  "COD_UN_MED_CD"=>array(165,9),"COD_ESTA_IDE_CD"=>array(174,2),"MTO_TOT_IM"=>array(176,19),
		  "MTO_PDO_IM"=>array(195,19),"MTO_PDU_IM"=>array(214,19),"MTO_EXI_IM"=>array(233,19),
		  "MTO_FCN_IM"=>array(252,19),"MTO_ENV_PVS_SN"=>array(271,19),"MTO_ENV_PDA_SN"=>array(290,19),
		  "FEC_EMI_DOC_FF"=>array(309,10),"FEC_VNC_DOC_FF"=>array(319,10),"IND_ESTA_DOC_IN"=>array(329,1),
		  "FEC_CCL_DOC_FF"=>array(330,10),"COD_ESC_GES_CD"=>array(340,9),"FEC_ING_GES_FF"=>array(349,10),
		  "NOM_FCH_ORI_MO"=>array(359,20),"DES_CLI_DS"=>array(379,140),"S_TIP_CAL_ATI_CD"=>array(519,2),
		  "S_NOM_CAL_DS"=>array(521,40),"A_NUM_CAL_UN"=>array(561,6),"S_DSC_CMP_PRI_DS"=>array(567,40),
		  "S_DSC_CMP_SEG_DS"=>array(607,40),"S_COD_POS_CD"=>array(647,10),"S_DIRE_CIU_CD"=>array(657,9),
		  "S_PRC_TIP_PRO_CMR_CD"=>array(666,10),"S_PRC_SBT_PRO_CMR_CD"=>array(676,9),"CUT_TIP_DOC_CD"=>array(685,3),
		  "CUT_NIM_DOC_CU_CD"=>array(688,20),"NOM_ARE_GEO_NO"=>array(708,20),"NOM_DEU_NFAC_NUM"=>array(728,19),
		  "COD_EXT_FNX_CD"=>array(747,1)); */

		$struc = array(
			"CLIENTE" => array(0, 10),
			"CUENTA" => array(10, 10),
			"INSCRIPCION" => array(20, 10),
			"TELEFONO" => array(30, 15),
			"GESTION" => array(45, 30),
			"FECHA_INICIO_GESTION" => array(75, 10),
			"FECHA_FIN_GESTION" => array(85, 10),
			"DESCRIPCION_AGENCIA" => array(95, 20),
			"CODIGO_ESTADO_PC" => array(115, 4),
			"DESCRIPCION_ESTADO_PC" => array(119, 10),
			"ESTADO_RECLAMO" => array(129, 15),
			"ESTADO_RESULTADO_RECLAMO" => array(144, 20),
			"MONTO_FUNDADO_RECLAMO" => array(164, 12),
			"NOMBRE_EMPRESA" => array(176, 30),
			"CODIGO_AGRUPACION" => array(206, 4),
			"CODIGO_SEGMENTO_CTA" => array(210, 4),
			"DESCRIPCION_TIPO_DOCUMENTO" => array(214, 30),
			"DESCRIPCION_AGRUPACION" => array(244, 15),
			"DESCRIPCION_SEGMENTO_CTA" => array(259, 30),
			"NEGOCIO" => array(289, 4),
			"CODIGO_TIPO_DOCUMENTO" => array(293, 2),
			"NUMERO_DOCUMENTO" => array(295, 13),
			"FECHA_CICLO" => array(308, 10),
			"FECHA_EMISION" => array(318, 10),
			"FECHA_VENCIMIENTO" => array(328, 10),
			"MONTO_EXIGIBLE" => array(338, 12),
			"MONTO_PAGADO" => array(350, 12),
			"MONTO_TOTAL" => array(362, 12),
			"MONTO_AJUSTADO" => array(374, 12),
			"MONTO_FINANCIADO" => array(386, 12),
			"MONTO_DISPUTA" => array(398, 12),
			"FECHA_ALTA_PC" => array(410, 10),
			"FECHA_BAJA_PC" => array(420, 10),
			"NOMBRES" => array(430, 100),
			"CODIGO_TIPO_DOCUMENTO_DNI" => array(530, 3),
			"NUMERO_DOCUMENTO_DNI" => array(533, 17),
			"VIA" => array(550, 3),
			"CALLE" => array(553, 43),
			"NUMERO" => array(596, 6),
			"DIRECCION" => array(602, 100),
			"PROVINCIA" => array(702, 20),
			"AREA" => array(722, 10),
			"DESCRIPCION_ZONAL" => array(732, 3),
			"CODIGO_SUB_LOCALIDAD" => array(735, 10),
			"DESCRIPCION_SUB_LOCALIDAD" => array(745, 30),
			"CODIGO_POSTAL" => array(775, 10),
			"CDM" => array(785, 4),
			"CODIGO_SUB_TIPO_PC" => array(789, 4),
			"DESCRIPCION_SUB_TIPO_PC" => array(793, 50),
			"CODIGO_LOCALIDAD" => array(843, 10),
			"DESCRIPCION_LOCALIDAD" => array(853, 30),
			"TIPO_SPEEDY" => array(883, 50),
			"FECHA_SPEEDY" => array(933, 10),
			"PAQUETE" => array(943, 50),
			"INFOCORP" => array(993, 2),
			"PRODUCTO_COBRANZA" => array(995, 50),
			"SERVICIO_COBRANZA" => array(1045, 50),
			"CORTE" => array(1095, 10),
			"CAMPANIA" => array(1105, 50),
			"TIPO_ALTA" => array(1155, 30),
			"TELEFONO_REFERENCIA" => array(1185, 30),
			"APLICA_FINANCIAMIENTO" => array(1215, 2),
			"FINANCIAMIENTO_ACTUAL" => array(1217, 50),
			"RESERVADO_1" => array(1267, 50),
			"RESERVADO_2" => array(1317, 50),
			"RESERVADO_3" => array(1367, 50),
			"RESERVADO_4" => array(1417, 50),
			"RESERVADO_5" => array(1467, 50),
			"DESCRIPCION_GESTION" => array(1517, 40),
			"DESCRIPCION_EVENTO" => array(1557, 40),
			"DESCRIPCION_SEGMENTACION" => array(1597, 50)
		);

		$header = array();
		foreach ($struc as $index => $value) {
			array_push($header, $index);
		}

		fwrite($tmpArchivo, implode("|", $header) . "\r\n");

		if ($archivo) {
			while (!feof($archivo)) {
				$lineTMP = array();
				$linea = fgets($archivo);
				foreach ($struc as $index => $value) {
					array_push($lineTMP, trim(substr($linea, $value[0], $value[1])));
				}
				fwrite($tmpArchivo, implode("|", $lineTMP) . "\r\n");
			}
			fclose($archivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => "TMP" . $_post['file']));
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
		}
	}

	public function limpiarCarteraNOC($_post) {
		$path = "../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		//$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
		$tmpArchivo = @fopen("../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "", "", "", "", "", "", "", "");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCarteraRetiro($_post) {
		$path = "../documents/retiro/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/retiro/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCarteraCorteFocalizado($_post) {
		$path = "../documents/corte_focalizado/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'El archivo subido no existe o fue removida, intente subir otra vez el archivo'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/corte_focalizado/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'No se encuentra cabecera'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Archivo limpiado correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo'));
		}
	}

	public function limpiarCarteraIVR($_post) {
		$path = "../documents/ivr/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/ivr/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCarteraAgregarCabecera($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');
		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
			exit();
		}

		$archivo = @fopen($path, "r+");

		$countHeader = 0;

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		////$connection->beginTransaction();

		$sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

		$pr = $connection->prepare($sql);
		$pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
		$pr->execute();
		$data = $pr->fetchAll(PDO::FETCH_ASSOC);

		$cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

		$header = array();
		foreach ($cabeceras as $index => $value) {
			array_push($header, $index);
		}

		if ($_post['CaracterSeparador'] == 'tab') {
			fwrite($tmpArchivo, implode("\t", $header) . "\r\n");
		} else {
			fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $header) . "\r\n");
		}
		//fwrite($tmpArchivo,implode("|",$header)."\r\n");
		//$longitud = 0;
		if ($archivo) {
			while (!feof($archivo)) {
				$lineTMP = array();
				$linea = fgets($archivo);
				$longitud = 0;
				foreach ($cabeceras as $index => $value) {
					array_push($lineTMP, trim(substr($linea, $longitud, $value)));
					$longitud = $longitud + (int) $value;
				}
				//fwrite($tmpArchivo,implode("|",$lineTMP)."\r\n");
				if ($_post['CaracterSeparador'] == 'tab') {
					fwrite($tmpArchivo, implode("\t", $lineTMP) . "\r\n");
				} else {
					fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $lineTMP) . "\r\n");
				}
			}
			fclose($archivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
		}
	}

	public function limpiarCarteraPagoAddHeader($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"] . ".txt";

		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

		$pr = $connection->prepare($sql);
		$pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
		$pr->execute();
		$data = $pr->fetchAll(PDO::FETCH_ASSOC);

		$cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

		$header = array();
		foreach ($cabeceras as $index => $value) {
			array_push($header, $index);
		}

		fwrite($tmpArchivo, implode("|", $header) . "\r\n");

		$carteraArchivo = @fopen($path, 'r+');

		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			//fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCartera3($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		//$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCarteraSoloAgregarCabecera($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');
		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear archivo temporal'));
			exit();
		}

		$archivo = @fopen($path, "r+");

		$countHeader = 0;

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sql = " SELECT cabeceras FROM ca_cabeceras_cartera WHERE idcabeceras_cartera = ? ";

		$pr = $connection->prepare($sql);
		$pr->bindParam(1, $_post['idCabecera'], PDO::PARAM_INT);
		$pr->execute();
		$data = $pr->fetchAll(PDO::FETCH_ASSOC);

		$cabeceras = json_decode(str_replace("\\", "", $data[0]['cabeceras']), true);

		$header = array();
		foreach ($cabeceras as $index => $value) {
			array_push($header, $index);
		}

		if ($_post['CaracterSeparador'] == 'tab') {
			fwrite($tmpArchivo, implode("\t", $header) . "\r\n");
		} else {
			fwrite($tmpArchivo, implode($_post['CaracterSeparador'], $header) . "\r\n");
		}
		if ($archivo) {
			while (!feof($archivo)) {
				$linea = fgets($archivo);
				$buscar = array('"', "'", "#", "&", "?", "¿", "!", "¡", ",", "¥");
				$cambia = array('', "", "", "", "", "", "", "", "", "N");
				$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
				fwrite($tmpArchivo, $line_c . "\r\n");
			}
			fclose($archivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al abrir cartera'));
		}
	}

	public function limpiarCartera4($_post,$carpeta='carteras') {
		$path = "../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		$tmpArchivo = @fopen("../documents/".$carpeta."/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');
		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = array();
					if ($_post['CaracterSeparador'] == 'tab') {
						$cabeceras = explode("\t", $line);
					}else{
						$cabeceras = explode($_post['CaracterSeparador'], $line);
					}
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function limpiarCarteraPago($_post) {
		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$tmp_file = "TMP" . session_id() . rand(1, 1000) . $_post["file"];

		//$tmpArchivo=@fopen("../documents/carteras/".$_post["NombreServicio"]."/TMP".session_id().rand(1,10).$_post["file"],'a+');
		$tmpArchivo = @fopen("../documents/carteras/" . $_post["NombreServicio"] . "/" . $tmp_file, 'a+');

		if (!$tmpArchivo) {
			echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
			exit();
		}

		$carteraArchivo = @fopen($path, 'r+');

		if ($carteraArchivo) {
			$count = 0;
			while (!feof($carteraArchivo)) {
				$linea = fgets($carteraArchivo);
				if ($count == 0) {
					$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "\t", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "|", "", '', "", "", "", "", "", "", "", "N");
					//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","\t","'",'"',"?","¿","!","¡","[","]");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","|","",'',"","","","","","");
					$line = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					$cabeceras = explode("|", $line);
					for ($i = 0; $i < count($cabeceras); $i++) {
						if (trim($cabeceras[$i]) == '') {
							fclose($tmpArchivo);
							fclose($carteraArchivo);
							echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
							exit();
						}
					}
					fwrite($tmpArchivo, $line . "\r\n");
				} else {
					$buscar = array('"', "'", "\t", "#", "&", "?", "¿", "!", "¡", ",", "¥");
					$cambia = array('', "", "|", "", "", "", "", "", "", "", "N");
					$line_c = str_replace($buscar, $cambia, trim(utf8_encode($linea)));
					fwrite($tmpArchivo, $line_c . "\r\n");
				}
				$count++;
			}
			fclose($carteraArchivo);
			fclose($tmpArchivo);
			//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente','file'=>"TMP".session_id().rand(1,10).$_post['file']));
			echo json_encode(array('rst' => true, 'msg' => 'Cartera limpiada correctamente', 'file' => $tmp_file));
		} else {
			//fclose($carteraArchivo);
			fclose($tmpArchivo);
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer cartera'));
		}
	}

	public function uploadCarteraPago( $_post, $is_parser=0,$file ) {
		//$file = $_post['file'];
		$separator = $_post['separator'];
		$servicio = $_post['Servicio'];
		$NombreServicio = $_post['NombreServicio'];
		//$campania=$_post['Campania'];
		//$cartera=$_post['Cartera'];
		$UsuarioCreacion = $_post['usuario_creacion'];
		$jsonPago = json_decode(str_replace("\\", "", $_post['data_pago']), true);
		$id_cartera_pago = 0;

		$codigo_cliente = '';

		$numero_cuenta = '';
		$moneda_cuenta = '';
		$grupo1_cuenta = '';

		$codigo_operacion = '';
		$moneda_operacion = '';
		$grupo1_operacion = '';

		$gestion = '';

		$call_center = '';
		$moneda = '';
		$monto = '';
		$total_deuda = '';
		$fecha_pago = '';

		for ($i = 0; $i < count($jsonPago); $i++) {
			if ($jsonPago[$i]['campoT'] == 'monto_pagado') {
				$monto = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'moneda') {
				$moneda = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'total_deuda') {
				$total_deuda = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'call_center') {
				$call_center = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'codigo_cliente') {
				$codigo_cliente = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'numero_cuenta') {
				$numero_cuenta = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'moneda_cuenta') {
				$moneda_cuenta = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'grupo1_cuenta') {
				$grupo1_cuenta = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'codigo_operacion') {
				$codigo_operacion = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'moneda_operacion') {
				$moneda_operacion = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'grupo1_operacion') {
				$grupo1_operacion = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'gestion') {
				$gestion = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'fecha') {
				$fecha_pago = $jsonPago[$i]['dato'];
			}
		}


		if (trim($codigo_operacion) == '') {
			return array('rst' => false, 'msg' => 'Seleccione operacion');
			exit();
		}

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$path = "../documents/carteras/" . $NombreServicio . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		//$archivo = file($path);
		$archivo = @fopen($path, "r+");

		//$colum = explode($separator,$archivo[0]);
		$colum = explode($separator, fgets($archivo));

		if (count($colum) < 4) {
			return array('rst' => false, 'msg' => 'Caracter separador incorrecto');
			exit();
		}

		if( !function_exists('map_header_pago') ) {
			function map_header_pago($n) {
				$item = "";
				if (trim(utf8_encode($n)) != "") {
					//$buscar=array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",".","#"," ","/","�","�","@","(",")","$","&","%");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
					$buscar = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ".", "#", " ", "/", "�", "�", "@", "(", ")", "$", "&", "%", "'", '"', "?", "�", "!", "�", "[", "]", "-", "�");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
					//$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
					$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
					//$item="`".$item."` VARCHAR(200) ";
				}

				return $item;
			};
		}

		$colum = array_map("map_header_pago", $colum);

		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				//array_push($columHeader,$colum[$i]);
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
			exit();
		}

		fclose($archivo);

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTablePago = " DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ";
		$prDropTablePago = $connection->prepare($sqlDropTablePago);
		if ($prDropTablePago->execute()) {

			$createTablePago = " CREATE TABLE tmppago_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";

			$prCreateTablePago = $connection->prepare($createTablePago);
			if ($prCreateTablePago->execute()) {
				$sqlLoadPago = "";
				if( $separator == 'tab' ) {
					$sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
					  INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}else{
					$sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
					  INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}

				$prLoadPago = $connection->prepare($sqlLoadPago);
				if ($prLoadPago->execute()) {

					$sqlUpdateTMPCarteraPago = " ALTER TABLE tmppago_" . session_id() . "_" . $time . " ADD idcartera INT, ADD iddetalle_cuenta INT , ADD idcartera_pago INT, ADD idcuenta INT , ADD estado_pag TINYINT DEFAULT 1, ADD INDEX(idcartera), ADD INDEX(iddetalle_cuenta)  ";

					$prUpdateTMPCarteraPago = $connection->prepare($sqlUpdateTMPCarteraPago);
					if ($prUpdateTMPCarteraPago->execute()) {

						//$connection->beginTransaction();
						if( trim($gestion)!='' ) {
							$sqlUpdateIdCarteraTMPCarteraPago = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp 
								SET idcartera = ( SELECT idcartera FROM ca_cartera INNER JOIN ca_campania ON ca_cartera.idcampania = ca_campania.idcampania AND ca_campania.estado = 1 AND ca_cartera.estado = 1 AND idservicio = $servicio WHERE TRIM(nombre_cartera) = TRIM( tmp.$gestion ) ORDER BY idcartera DESC LIMIT 1 ) 
								WHERE TRIM($gestion) != '' ";
							$prUpdateIdCarteraTMPCarteraPago = $connection->prepare($sqlUpdateIdCarteraTMPCarteraPago);
							if ($prUpdateIdCarteraTMPCarteraPago->execute()) {

							}else{
								@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
								@$prDropTablePagoRollback->execute();
								return array('rst' => false, 'msg' => 'Error al actualizar gestion');
								exit();
							}
						}

						/*$prUpdateIdCarteraTMPCarteraPago = $connection->prepare($sqlUpdateIdCarteraTMPCarteraPago);
						if ($prUpdateIdCarteraTMPCarteraPago->execute()) {*/

							//$sqlDeleteRowNotGestPago = " DELETE FROM tmppago_".session_id()."_".$time." WHERE ISNULL(idcartera) = 1 ";
							//$prDeleteRowNotGestPago = $connection->prepare( $sqlDeleteRowNotGestPago );
							//if( $prDeleteRowNotGestPago->execute() ) {

							$cabeceras = implode(",", $colum);
							$parserPago = str_replace("\\", "", $_post["data_pago"]);
							if ($is_parser == 1) {
								$InsertJsonParserPago = " INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion, moneda ) 
									VALUES ( ?,?,?,?,?,?,? ) ";

								$prInsertJsonParserPago = $connection->prepare($InsertJsonParserPago);
								$prInsertJsonParserPago->bindParam(1, $servicio);
								$prInsertJsonParserPago->bindParam(2, $cabeceras);
								$prInsertJsonParserPago->bindParam(3, $parserPago);
								$prInsertJsonParserPago->bindParam(4, $codigo);
								$prInsertJsonParserPago->bindParam(5, $numero_cuenta);
								$prInsertJsonParserPago->bindParam(6, $operacion);
								/*                                 * ****** */
								$prInsertJsonParserPago->bindParam(7, $moneda);
								/*                                 * ****** */
								if ($prInsertJsonParserPago->execute()) {

								} else {
									//$connection->rollBack();

									@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
									@$prDropTablePagoRollback->execute();

									return array('rst' => false, 'msg' => 'Error al guardar metadata');
									exit();
								}
							}


							//$InsertCarteraPago=" INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion ) 
							//VALUE( ".$_post['Cartera'].",'tmppago_".session_id()."_".$time."',".(count($archivo)-1).",NOW(),'".$file."',$UsuarioCreacion,NOW() )";
							//$InsertCarteraPago=" INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras ) 
							//VALUES( ".$_post['Cartera'].",'tmppago_".session_id()."_".$time."', ( SELECT COUNT(*) FROM tmppago_".session_id()."_".$time."  ) ,NOW(),'".$file."',$UsuarioCreacion,NOW(),'".$codigo."','".$numero_cuenta."','".$moneda."','".$operacion."','".$parserPago."','".$cabeceras."' )";
							$InsertCarteraPago = "";
							if( trim($gestion) == '' ) {
								$InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras ) 
								VALUES ( ) ";
							}else{
								$InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras ) 
									SELECT idcartera ,'tmppago_" . session_id() . "_" . $time . "', COUNT(*),NOW(),'" . $file . "',$UsuarioCreacion,NOW(),'" . $codigo . "','" . $numero_cuenta . "','" . $moneda . "','" . $operacion . "','" . $parserPago . "','" . $cabeceras . "' 
									FROM tmppago_" . session_id() . "_" . $time . " 
									WHERE TRIM($gestion) != '' AND ISNULL(idcartera) = 0 GROUP BY idcartera ";
							}


							$prInsertCarteraPago = $connection->prepare($InsertCarteraPago);
							if ($prInsertCarteraPago->execute()) 
							{

								$idCarteraPago = $connection->lastInsertId();

								//$sqlUpdateTmpIdCarteraPago = " UPDATE tmppago_".session_id()."_".$time." SET idcartera_pago = (  ) ";
								/*if( trim($gestion) == '' ) {
									$id_cartera_pago = $connection->lastInsertId();
								}*/

								$field_on = "";
								$field_where = "";
								if( trim($gestion) != ''  ) {
									$field_on .= " AND detcu.idcartera = tmp.idcartera ";
									$field_where .= " AND ISNULL(tmp.idcartera) = 0 ";
								}else{
									$field_where .= " AND detcu.idcartera =  ";
								}

								if( trim($moneda_cuenta) != ''  ) {
									$field_on .= " AND detcu.moneda = tmp.$moneda_operacion ";
									$field_where .= " AND ISNULL(tmp.$moneda_operacion) = 0 ";
								}
								if( trim($grupo1_cuenta) != ''  ) {
									$field_on .= " AND detcu.grupo1_operacion = tmp.$grupo1_operacion ";
									$field_where .= " AND ISNULL(tmp.$grupo1_operacion) = 0 ";
								}

								$sqlUpdateTmpIdDetalleCuenta = " UPDATE tmppago_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu 
									ON detcu.codigo_operacion = tmp.$codigo_operacion ".$field_on." 
									SET tmp.iddetalle_cuenta = detcu.iddetalle_cuenta , tmp.idcuenta = detcu.idcuenta 
									WHERE ISNULL(tmp.$codigo_operacion) = 0 ".$field_where." ";

								$prUpdateTmpIdDetalleCuenta = $connection->prepare($sqlUpdateTmpIdDetalleCuenta);
								if( $prUpdateTmpIdDetalleCuenta->execute() ) 
								{

									//$fieldPago=array_intersect_key($jsonPago,array('monto'=>'','moneda'=>'','fecha'=>'','observacion'=>''));
									$campoPagoTMP = array();
									$campoPago = array();

									for ($i = 0; $i < count($jsonPago); $i++) {
										if ($jsonPago[$i]['campoT'] == "codigo_cliente") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
										} else if ($jsonPago[$i]['campoT'] == "numero_cuenta") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
										} else if ($jsonPago[$i]['campoT'] == "codigo_operacion") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
										} else if ($jsonPago[$i]['campoT'] == "moneda") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
										} else if ($jsonPago[$i]['campoT'] == "call_center") {

										} else if ($jsonPago[$i]['campoT'] == "estado_cruce") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " ( SELECT descripcion FROM ca_estado_pago_cruce WHERE idservicio = $servicio AND nombre = TRIM( " . $jsonPago[$i]['dato'] . " ) ) ");
										} else if ($jsonPago[$i]['campoT'] == "fecha") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " 
												CASE
												WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 8
												THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",7,2))
												WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 10
												THEN 
													CASE
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 5
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'-') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 5
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
													ELSE TRIM(".$jsonPago[$i]['dato'].")
													END
												ELSE TRIM(".$jsonPago[$i]['dato'].")
												END 
												 ");
										} else if ($jsonPago[$i]['campoT'] == "fecha_envio") {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " 
												CASE
												WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 8
												THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",5,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",7,2))
												WHEN LENGTH(TRIM(".$jsonPago[$i]['dato'].")) = 10
												THEN 
													CASE
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'/') = 5
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'-') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 3
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",7,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",4,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",1,2))
													WHEN INSTR(TRIM(".$jsonPago[$i]['dato']."),'.') = 5
													THEN CONCAT(SUBSTRING(".$jsonPago[$i]['dato'].",1,4),'-',SUBSTRING(".$jsonPago[$i]['dato'].",6,2),'-',SUBSTRING(".$jsonPago[$i]['dato'].",9,2))
													ELSE TRIM(".$jsonPago[$i]['dato'].")
													END
												ELSE TRIM(".$jsonPago[$i]['dato'].")
												END 
												 ");
										} else {
											array_push($campoPago, $jsonPago[$i]['campoT']);
											array_push($campoPagoTMP, " TRIM( ".$jsonPago[$i]['dato']." ) ");
										}
									}
									/*                                 * ********** */



									/*$sqlVerificarFecha = " SELECT COUNT(*) AS 'COUNT' 
										FROM ca_pago pag INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp 
										ON tmp.idcartera = pag.idcartera AND tmp.$fecha_pago = pag.fecha  
										WHERE pag.estado = 1 ";*/

									$sqlVerificarFecha = " UPDATE ca_pago pag INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp 
										ON tmp.idcartera = pag.idcartera AND tmp.$codigo_operacion = pag.codigo_operacion AND tmp.$fecha_pago = pag.fecha
										SET tmp.estado_pag = 0 ";

									$prVerificarFecha = $connection->prepare($sqlVerificarFecha);
									//$prVerificarFecha->execute();
									//$dataVerificarFecha = $prVerificarFecha->fetchAll(PDO::FETCH_ASSOC);

									//$countVerificarFecha = (int) $dataVerificarFecha[0]['COUNT'];

									/*                                 * *********** */

									//if( $countVerificarFecha > 0 ){
									if( $prVerificarFecha->execute() ){



										$sqlPago = "";

										//if( trim($call_center)=='' ) {

										if( trim($gestion) == '' ) {
											$sqlPago = " INSERT IGNORE INTO ca_pago (  idcartera_pago, idcartera, iddetalle_cuenta, usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " ) 
														SELECT $idCarteraPago,  , iddetalle_cuenta, $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "  
														FROM tmppago_" . session_id() . "_" . $time . " 
														WHERE TRIM($gestion) != '' AND ISNULL( iddetalle_cuenta ) = 0 
														AND ISNULL( idcuenta ) = 0 AND estado_pag = 1 ";

										}else{
											$sqlPago = " INSERT IGNORE INTO ca_pago (  idcartera_pago, idcartera, iddetalle_cuenta, usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " ) 
														SELECT $idCarteraPago, idcartera , iddetalle_cuenta, $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "  
														FROM tmppago_" . session_id() . "_" . $time . " 
														WHERE ISNULL(idcartera) = 0 
														AND TRIM($gestion) != '' 
														AND ISNULL( iddetalle_cuenta ) = 0 AND ISNULL( idcuenta ) = 0 AND estado_pag = 1 ";
										}


										/* $sqlPago=" INSERT IGNORE INTO ca_pago (  idcartera_pago, idcartera, usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." ) 
										  SELECT idcartera_pago , idcartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."
										  FROM tmppago_".session_id()."_".$time." WHERE LENGTH( TRIM( $operacion ) ) > 0 "; */

										/* }else{

										  $sqlPago=" INSERT IGNORE INTO ca_pago ( idcartera_pago, idcartera , usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." )
										  SELECT $idCarteraPago, idcartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."
										  FROM tmppago_".session_id()."_".$time." WHERE ISNULL(idcartera) = 0 AND LENGTH( TRIM( $operacion ) ) > 0 AND LOWER( TRIM($call_center) )='hdec' ";

										  } */
										//echo $sqlPago;
										$prInsertPago = $connection->prepare($sqlPago);
										if ($prInsertPago->execute()) {

											$sqlUpdateMontoDetalleCuenta = " UPDATE ca_detalle_cuenta detcu INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp 
											ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta
											SET 
											detcu.monto_pagado = IFNULL(detcu.monto_pagado,0) + tmp.$monto,
											detcu.ul_fecha_pago = tmp.$fecha_pago 
											WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND ISNULL(tmp.idcuenta) = 0 AND tmp.estado_pag = 1 ";

											$prUpdateMontoDetalleCuenta = $connection->prepare($sqlUpdateMontoDetalleCuenta);
											if( $prUpdateMontoDetalleCuenta->execute() )  {

												/*$sqlUpdateMontoCuenta = " UPDATE ca_cuenta cu INNER JOIN tmppago_" . session_id() . "_" . $time . " tmp 
															ON cu.idcuenta = tmp.idcuenta 
															SET 
															cu.monto_pagado = IFNULL(cu.monto_pagado,0) + SUM(tmp.$monto),
															cu.ul_fecha_pago = MAX(tmp.$fecha_pago)
															WHERE ISNULL(tmp.idcuenta) = 0 AND ISNULL(tmp.iddetalle_cuenta) = 0 ";*/

												$sqlUpdateMontoCuenta = " UPDATE ca_cuenta cu INNER JOIN 
															(
															SELECT idcuenta, SUM($monto) AS MONTO_PAGO, MAX($fecha_pago) AS FECHA_PAGO
															FROM tmppago_" . session_id() . "_" . $time . "
															WHERE ISNULL(iddetalle_cuenta) = 0 AND ISNULL(idcuenta) = 0 AND estado_pag = 1
															GROUP BY idcuenta 
															) tmp
															ON tmp.idcuenta = cu.idcuenta
															SET 
															cu.monto_pagado = IFNULL(cu.monto_pagado,0) + tmp.MONTO_PAGO,
															cu.ul_fecha_pago = tmp.FECHA_PAGO ";

												$prUpdateMontoCuenta = $connection->prepare( $sqlUpdateMontoCuenta );
												if( $prUpdateMontoCuenta->execute() ) {

													if ($call_center == '') {
														//$connection->commit();
														return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
													} else {

														/* if( trim($monto)=='' ) {
														  //$connection->rollBack();
														  echo json_encode(array('rst'=>false,'msg'=>'Seleccione monto pagado para actualizar cuenta'));
														  }else { */


														if (trim($monto) == "") {
															//$connection->commit();
															return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
														} else {

															$sqlRankinPago = " INSERT IGNORE INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion ) 
																			SELECT " . $call_center . ", SUM( " . $monto . " ), $idCarteraPago, NOW(), $UsuarioCreacion 
																			FROM tmppago_" . session_id() . "_" . $time . " 
																			WHERE LENGTH( TRIM( " . $call_center . " ) ) > 0  
																			AND NOT ISNULL(idcartera) 
																			GROUP BY LOWER( TRIM( " . $call_center . " ) ) ";

															/* $sqlRankinPago=" INSERT IGNORE INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion ) 
															  SELECT ".$call_center.", SUM( ".$monto." ), idcartera_pago, NOW(), $UsuarioCreacion
															  FROM tmppago_".session_id()."_".$time." WHERE LENGTH( TRIM( ".$call_center." ) ) > 0 GROUP BY LOWER( TRIM( ".$call_center." ) ) "; */

															$prSqlRankinPago = $connection->prepare($sqlRankinPago);
															if (@$prSqlRankinPago->execute()) {
																//$connection->commit();
																return array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente');
															} else {
																//$connection->rollBack();

																@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
																@$prDropTablePagoRollback->execute();

																return array('rst' => false, 'msg' => 'Error agregar datos de ranking de pago');
															}
														}


														//}
													}

												}else{
													@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
													@$prDropTablePagoRollback->execute();

													return array('rst' => false, 'msg' => 'Error al actualizar montos de cuenta');
												}

											}else{
												@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
												@$prDropTablePagoRollback->execute();

												return array('rst' => false, 'msg' => 'Error al actualizar montos de detalle cuenta (facturas)');
											}

										} else {
											//$connection->rollBack();

											@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
											@$prDropTablePagoRollback->execute();

											return array('rst' => false, 'msg' => 'Error agregar datos de pago');
										}

									}else{

									  @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
									  @$prDropTablePagoRollback->execute();

									  //echo json_encode(array('rst'=>false,'msg'=>'Archivo de pagos ya fue cargado'));
									  return array('rst'=>false,'msg'=>'Error al verificar pagos del cliente');

									}

								}else{

									@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
									@$prDropTablePagoRollback->execute();

									return array('rst' => false, 'msg' => 'Error al actualizar id detalle cuenta ( factura ) ');
								}

							} else {
								//$connection->rollBack();

								@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
								@$prDropTablePagoRollback->execute();

								return array('rst' => false, 'msg' => 'Error insertar datos de temporal');
							}
							/* }else{
							  //$connection->rollBack();
							  @$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
							  @$prDropTablePagoRollback->execute();

							  echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar las no gestiones '));
							  } */
						/*} else {
							//$connection->rollBack();
							@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
							@$prDropTablePagoRollback->execute();

							echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar gestion'));
						}*/
					} else {
						@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
						@$prDropTablePagoRollback->execute();

						return array('rst' => false, 'msg' => 'Error al agregar campo idcartera');
					}
				} else {

					@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
					@$prDropTablePagoRollback->execute();

					return array('rst' => false, 'msg' => 'Error al cargar datos de pago');
				}
			} else {

				@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
				@$prDropTablePagoRollback->execute();

				return array('rst' => false, 'msg' => 'Error create temporary table');
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al eliminar tabla');
		}
	}

	public function uploadCartera($_post, $is_parser=0, $file) {
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$usuario_creacion = $_post["usuario_creacion"];

		$tipo_data = (int)$_post["TipoData"];
		$cabecera_departamento = $_post["CabeceraDepartamento"];

		$nombre_cartera = $_post["NombreCartera"];
		$codigo_cliente = $_post["codigo_cliente"];
		$numero_cuenta = $_post["numero_cuenta"];
		$codigo_operacion = $_post["codigo_operacion"];
		$moneda_cuenta = "";
		$moneda_operacion = "";
		$grupo1_cuenta = "";
		$grupo1_operacion = "";

		$gestion = "";

		$parserCliente = str_replace("\\", "", $_post["data_cliente"]);
		$parserCartera = str_replace("\\", "", $_post["data_cartera"]);
		$parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
		$parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
		$parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
		$parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
		$parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);



		$jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
		$jsonCartera = json_decode(str_replace("\\", "", $_post["data_cartera"]), true);
		$jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
		$jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
		$jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
		$jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
		$jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

		$sql_where_main = " ";
		$sql_where_main_tmp = " ";
		if( $tipo_data == 1 ) {

		}else if ( $tipo_data == 2 ){
			$sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
			$sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
		}else if ( $tipo_data == 3 ) {
			$sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
			$sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
		}else{
			echo json_encode(array('rst' => false, 'msg' => 'Tipo incorrecto de carga'));
			exit();
		}

		for( $i=0;$i<count($jsonCliente);$i++ ) {
			if( $jsonCliente[$i]['campoT'] == 'codigo' ) {
				$codigo_cliente = $jsonCliente[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonCartera);$i++ ) {
			if( $jsonCartera[$i]['campoT'] == 'nombre_cartera' ) {
				$gestion = $jsonCartera[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonCuenta);$i++ ) {
			if( $jsonCuenta[$i]['campoT'] == 'numero_cuenta' ) {
				$numero_cuenta = $jsonCuenta[$i]['dato'];
			}else if( $jsonCuenta[$i]['campoT'] == 'moneda' ){
				$moneda_cuenta = $jsonCuenta[$i]['dato'];
			}else if( $jsonCuenta[$i]['campoT'] == 'grupo1' ) {
				$grupo1_cuenta = $jsonCuenta[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonOperacion);$i++ ) {
			if( $jsonOperacion[$i]['campoT'] == 'codigo_operacion' ) {
				$codigo_operacion = $jsonOperacion[$i]['dato'];
			}else if( $jsonOperacion[$i]['campoT'] == 'moneda' ){
				$moneda_operacion = $jsonOperacion[$i]['dato'];
			}else if( $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
				$grupo1_operacion = $jsonOperacion[$i]['dato'];
			}
		}

		$id_cartera = 0;


		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
			//echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();	
		}


		$time = date("Y_m_d_H_i_s");
		//$archivoParser = file($path);
		$archivoParser = @fopen($path, "r+");
		//$columMap = explode($_post['separator'],$archivoParser[0]);
		$columMap = array();
		if ($_post['separator'] == 'tab') {
			$columMap = explode("\t", fgets($archivoParser));
		} else {
			$columMap = explode($_post['separator'], fgets($archivoParser));
		}
		/*         * ****** */
		fclose($archivoParser);
		/*         * ******* */
		if (!function_exists('map_header')) {

			function map_header($n) {
				$item = "";
				if (trim(utf8_encode($n)) != "") {
					//$buscar=array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",".","#"," ","/","�","�","@","(",")","$","&","%");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
					$buscar = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ".", "#", " ", "/", "�", "�", "@", "(", ")", "$", "&", "%", "'", '"', "?", "�", "!", "�", "[", "]", "-", "�");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "");
					$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
					//$item="`".$item."` VARCHAR(200) ";
				}

				return $item;
			}

		}

		$colum = array_map("map_header", $columMap);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		$parserHeader = implode(",", $colum);

		/*         * ********* */
		/*array_push($columHeader, "`idcartera` INT ");
		array_push($columHeader, "`idcliente` INT ");
		array_push($columHeader, "`idcliente_cartera` INT ");
		array_push($columHeader, "`idcuenta` INT ");*/
		array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
		if ($codigo_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
		} 
		if ($moneda_cuenta != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
		} 
		if ($moneda_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
		} 
		if ($grupo1_cuenta != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_cuenta` ( `$grupo1_cuenta` ASC ) ");
		} 
		if ($grupo1_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_operacion` ( `$grupo1_operacion` ASC ) ");
		} 
		if ($gestion != "") {
			array_push($columHeader, "INDEX `index_" . session_id() . "_gestion` ( `$gestion` ASC ) ");
		}

		/*array_push($columHeader, "INDEX `index_" . session_id() . "_idcartera` ( `idcartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente` ( `idcliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente_cartera` ( `idcliente_cartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");*/

		/*         * ********** */

		if ($countHeaderFalse > 0) {
			return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
			//echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
			exit();
		}


		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		if ($prDropTableTMPCartera->execute()) {

			$sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = Aria DEFAULT CHARACTER SET = latin1 ";

			$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
			if ($prsqlCreateTabelTMPCartera->execute()) {

				/* $sqlLoadDataInFileUC=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$file."'
				  INTO TABLE tmpcartera_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES "; */
				$sqlLoadDataInFileUC = "";
				if ($_post['separator'] == 'tab') {
					$sqlLoadDataInFileUC = " COPY tmpcartera_" . session_id() . "_" . $time . " FROM  '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "' ";
				} else {
					$sqlLoadDataInFileUC = " COPY tmpcartera_" . session_id() . "_" . $time . " FROM '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "' 
					USING DELIMITERS '".$_post['separator']."' ";
				}
				//echo $sqlLoadDataInFileUC;
				$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
				if ($prLoadDataInFileUC->execute()) {

					$sqlAlterTableTMPCartera = " ALTER  TABLE tmpcartera_".session_id()."_".$time." ADD idcartera INT, ADD idcliente INT, ADD idcliente_cartera INT, ADD idcuenta INT, ADD INDEX(idcliente), ADD INDEX(idcliente_cartera), ADD INDEX(idcuenta) ";
					$prAlterTableTMPCartera = $connection->prepare($sqlAlterTableTMPCartera);
					if( $prAlterTableTMPCartera->execute() ) { 
					
						$deleteFirstLineCartera = " DELETE FROM tmpcartera_".session_id()."_".$time." WHERE TRIM( $codigo_cliente ) = '$codigo_cliente' ";
						
						$prDeleteFirstLineCartera = $connection->prepare( $deleteFirstLineCartera );
						if( $prDeleteFirstLineCartera->execute() ) {
	
						//$connection->beginTransaction();
						//$insertCartera=" INSERT INTO ca_cartera( nombre_cartera,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, moneda_cuenta, moneda_operacion, cliente, cuenta, detalle_cuenta, telefono, direccion,adicionales ) 
						//VALUES ( '".$nombre_cartera."',".$_post['Campania'].",NOW(),( SELECT COUNT(*) FROM tmpcartera_".session_id()."_".$time." ),'tmpcartera_".session_id()."_".$time."','".utf8_encode($_post["file"])."',".$_post['usuario_creacion'].",NOW() ,'".$parserHeader."','".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$moneda_cuenta."','".$moneda_operacion."','".$parserCliente."','".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."' ) ";
						$insertCartera = "";
						if( trim($gestion) == '' ) {
							$insertCartera=" INSERT INTO ca_cartera( nombre_cartera,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, moneda_cuenta, moneda_operacion, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion,adicionales ) 
										VALUES ( '".$nombre_cartera."',".$_post['Campania'].",CURRENT_TIMESTAMP,( SELECT COUNT(*) FROM tmpcartera_".session_id()."_".$time." ),'tmpcartera_".session_id()."_".$time."','".utf8_encode($_post["file"])."',".$_post['usuario_creacion'].",CURRENT_TIMESTAMP ,'".$parserHeader."','".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$moneda_cuenta."','".$moneda_operacion."','".$parserCliente."', '".$parserCartera."' ,'".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."' ) ";
						}else{
							$campoTableCartera = array();
							$campoTableCarteraTMP = array();
							for ($i = 0; $i < count($jsonCartera); $i++) {
								if ($jsonCartera[$i]['campoT'] == 'fecha_inicio') {
									array_push($campoTableCartera, $jsonCartera[$i]['campoT']);
									array_push($campoTableCarteraTMP, " MAX(
												CASE
												WHEN LENGTH(TRIM(".$jsonCartera[$i]['dato'].")) = 8
												THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 5 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 7 FOR 2))
												WHEN LENGTH(TRIM(".$jsonCartera[$i]['dato'].")) = 10
												THEN 
													CASE
													WHEN STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'/') = 3 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'-') = 3 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'.') = 3 
													THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 7 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 4 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 2))
													WHEN STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'/') = 5 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'.') = 5
													THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 6 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 9 FOR 2))
													ELSE TRIM(".$jsonCartera[$i]['dato'].")
													END
												ELSE TRIM(".$jsonCartera[$i]['dato'].")
												END 
												 ) ");
								} else if ($jsonCartera[$i]['campoT'] == 'fecha_fin') {
									array_push($campoTableCartera, $jsonCartera[$i]['campoT']);
									array_push($campoTableCarteraTMP, " MAX(
												CASE
												WHEN LENGTH(TRIM(".$jsonCartera[$i]['dato'].")) = 8
												THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 5 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 7 FOR 2))
												WHEN LENGTH(TRIM(".$jsonCartera[$i]['dato'].")) = 10
												THEN 
													CASE
													WHEN STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'/') = 3 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'-') = 3 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'.') = 3 
													THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 7 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 4 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 2))
													WHEN STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'/') = 5 OR STRPOS(TRIM(".$jsonCartera[$i]['dato']."),'.') = 5
													THEN (SUBSTRING(".$jsonCartera[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 6 FOR 2) || '-' || SUBSTRING(".$jsonCartera[$i]['dato']." FROM 9 FOR 2))
													ELSE TRIM(".$jsonCartera[$i]['dato'].")
													END
												ELSE TRIM(".$jsonCartera[$i]['dato'].")
												END 
												 ) ");
								}else{
									array_push($campoTableCartera, $jsonCartera[$i]['campoT']);
									array_push($campoTableCarteraTMP, " MAX( TRIM( ".$jsonCartera[$i]['dato']." ) ) ");
								}
							}
	
							$insertCartera = " INSERT INTO ca_cartera( ".implode(",",$campoTableCartera)." ,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, moneda_cuenta, moneda_operacion, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion,adicionales ) 
									SELECT ".implode(",",$campoTableCarteraTMP).", " . $_post['Campania'] . " , CURRENT_TIMESTAMP , COUNT(*) ,'tmpcartera_" . session_id() . "_" . $time . "','" . utf8_encode($file) . "'," . $_post['usuario_creacion'] . ",CURRENT_TIMESTAMP ,'" . $parserHeader . "','" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $moneda_cuenta . "','" . $moneda_operacion . "','" . $parserCliente . "', '".$parserCartera."' ,'" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "'
									FROM tmpcartera_" . session_id() . "_" . $time . " 
									WHERE TRIM($gestion) != '' ".$sql_where_main." 
									GROUP BY TRIM($gestion) ";
						}
	
						$prInsertCartera = $connection->prepare($insertCartera);
						if ($prInsertCartera->execute()) {
							$id_cartera = 0;
							if( trim($gestion) == '' )  {
								$id_cartera=$connection->lastInsertId();
							}else{
								$updateTMPCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
										SET idcartera = ( SELECT idcartera FROM ca_cartera WHERE idcampania = " . $_post['Campania'] . " AND estado = 1 AND TRIM(nombre_cartera) = TRIM(tmp.$gestion) ORDER BY idcartera DESC LIMIT 1 ) 
										WHERE TRIM($gestion) != '' ".$sql_where_main."  ";
								$prUpdateTMPCartera = $connection->prepare($updateTMPCartera);
								if( $prUpdateTMPCartera->execute() ) {
	
								}else{
									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();
									return array('rst' => false, 'msg' => 'Error al actualizar temporal');
									exit();
								}
							}
	
	
							//$id_cartera=$connection->lastInsertId();
	
							/**
							$sqlUpdateCartera = "";
							if( trim($gestion) == '' )  {
								$sqlUpdateCartera = " UPDATE ca_cartera SET cartera_act = $id_cartera WHERE idcartera = $id_cartera ";
							}else{
								$sqlUpdateCartera = " UPDATE ca_cartera car INNER JOIN tmpcartera_" . session_id() . "_" . $time . " tmp
								ON tmp.idcartera = car.idcartera 
								SET car.cartera_act = tmp.idcartera ";
							}
	
							**/
							//$sqlUpdateCartera = " UPDATE ca_cartera SET cartera_act = $id_cartera WHERE idcartera = $id_cartera ";
							//$prUpdateCartera = $connection->prepare($sqlUpdateCartera);
							//if( $prUpdateCartera->execute() ) {
							//if ($prUpdateTMPCartera->execute()) {
	
								/*                             * ***** save parser ****** */
								if ($is_parser == 1) {
	
									//$insertJsonParser=" INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador ) 
									//										VALUES ( ".$_post['Servicio'].",".$usuario_creacion.",NOW(),'".$parserHeader."','".$parserCliente."','".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."', '".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$_post['separator']."' ) ";
	
									$insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador, moneda_cuenta, moneda_operacion ) 
												VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','".$parserCartera."','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "','" . $moneda_cuenta . "','" . $moneda_operacion . "' ) ";
	
									$prInsertJsonParser = $connection->prepare($insertJsonParser);
									if ($prInsertJsonParser->execute()) {
	
									} else {
										//$connection->rollBack(); 
										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();
										return array('rst' => false, 'msg' => 'Error al insertar metadata');
										//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar metadata'));
										//exit();
									}
								}
								/*                             * ************ */
								$insertCliente = " ";
	
								$campoTableClienteTMP = array();
								$campoTableCliente = array();
	
								for ($i = 0; $i < count($jsonCliente); $i++) {
									if ($jsonCliente[$i]['campoT'] == 'codigo') {
										array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
										array_push($campoTableClienteTMP, " MAX( TRIM( " . $jsonCliente[$i]['dato'] . " ) ) ");
									} else {
										array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
										array_push($campoTableClienteTMP, " MAX( TRIM(".$jsonCliente[$i]['dato']." ) )");
									}
								}
	
								//$insertCliente=" INSERT IGNORE INTO ca_cliente ( idservicio,".implode(",",$campoTableCliente)." )  
								//SELECT ".$_post['Servicio'].",".implode(",",$campoTableClienteTMP)." FROM tmpcartera_".session_id()."_".$time." GROUP BY TRIM($codigo_cliente) ";
	
								$insertCliente = " INSERT INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " ) 
											SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " 
											FROM tmpcartera_" . session_id() . "_" . $time . " 
											WHERE LENGTH( TRIM($codigo_cliente) )>0 ".$sql_where_main." 
											GROUP BY TRIM($codigo_cliente) ";
	
								$prInsertCliente = $connection->prepare($insertCliente);
								if ($prInsertCliente->execute()) {
	
									/********************/
									$sqlTMPUpdateIdCliente = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
												SET tmp.idcliente = cli.idcliente 
												FROM ca_cliente cli 
												WHERE cli.idservicio = " . $_post['Servicio'] . " AND cli.codigo = tmp.$codigo_cliente ".$sql_where_main_tmp." ";
									/********************/
	
									$prTMPUpdateIdCliente = $connection->prepare($sqlTMPUpdateIdCliente);
									if ($prTMPUpdateIdCliente->execute()) {
										
										$sqlCreateRuleClienteCartera = "
											CREATE OR REPLACE RULE insert_ignore_ca_cliente_cartera 
											AS ON INSERT TO ca_cliente_cartera 
											WHERE EXISTS( SELECT * FROM ca_cliente_cartera WHERE idcartera = NEW.idcartera AND codigo_cliente = NEW.codigo_cliente )
											DO INSTEAD NOTHING
										";
										
										$prCreateRuleClienteCartera = $connection->prepare( $sqlCreateRuleClienteCartera );
										if( $prCreateRuleClienteCartera->prepare($prCreateRuleClienteCartera) ) {
	
											$campoTableClienteCarteraTMP = array();
											$campoTableClienteCartera = array();
		
											for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cliente']);$i++) {
												array_push($campoTableClienteCartera, $jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']);
												array_push($campoTableClienteCarteraTMP, " MAX( TRIM( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['dato']." ) ) ");
											}
		
											$implode_cc_t = "";
											$implode_cc_tmp = "";
		
											if( count($campoTableClienteCartera) >0 ){
												$implode_cc_t = ", ".implode(",",$campoTableClienteCartera);
												$implode_cc_tmp = ", ".implode(",",$campoTableClienteCarteraTMP);
											}
		
											$field_cartera = "";
											$field_group = "";
											( trim($gestion) == '' )
											?
												$field_cartera = " ".$id_cartera." "
											:
												$field_cartera = " MAX( idcartera ) ";
												$field_group = " idcartera , ";
											;
		
											$InsertClienteCartera = " INSERT INTO ca_cliente_cartera ( idcliente, codigo_cliente,idcartera,usuario_creacion,fecha_creacion ".$implode_cc_t." ) 
														SELECT MAX(idcliente), MAX( TRIM($codigo_cliente) ), ".$field_cartera." ," . $usuario_creacion . ",CURRENT_TIMESTAMP  ".$implode_cc_tmp."
														FROM tmpcartera_" . session_id() . "_" . $time . " 
														WHERE LENGTH( TRIM($codigo_cliente) )>0 AND idcliente IS NOT NULL ".$sql_where_main."
														GROUP BY ".$field_group." TRIM($codigo_cliente) ";
											//echo $InsertClienteCartera;
											$prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
											if ($prInsertClienteCartera->execute()) {
												$field_on = "";
												$field_where = "";
												( trim($gestion) == '' )?$field_on = " " : $field_on = " AND clicar.idcartera = tmp.idcartera ";
												( trim($gestion) == '' )?$field_where = "  tmp.idcliente IS NOT NULL AND clicar.idcartera = ".$id_cartera." ".$sql_where_main_tmp." " : $field_where = " tmp.idcliente IS NOT NULL ".$sql_where_main_tmp." ";
												/************/
												$sqlTMPUpdateIdClienteCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
															SET tmp.idcliente_cartera = clicar.idcliente_cartera 
															FROM ca_cliente_cartera clicar  
															WHERE clicar.idcliente = tmp.idcliente ".$field_on." ".$field_where;
															
												/***************/
												//echo $sqlTMPUpdateIdClienteCartera;exit();
												$prTMPUpdateIdClienteCartera = $connection->prepare($sqlTMPUpdateIdClienteCartera);
												if ($prTMPUpdateIdClienteCartera->execute()) {
		
													$campoTableCuentaTMP = array();
													$campoTableCuenta = array();
													$field_moneda = 0;
													for ($i = 0; $i < count($jsonCuenta); $i++) {
														if ( $jsonCuenta[$i]['campoT'] == 'total_deuda' || $jsonCuenta[$i]['campoT'] == 'monto_mora' || $jsonCuenta[$i]['campoT'] == 'saldo_capital' ) {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
														} else if ($jsonCuenta[$i]['campoT'] == 'monto_pagado') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
														} else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " MAX( TRIM( " . $jsonCuenta[$i]['dato'] . " ) ) ");
														} else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " MAX( TRIM( " . $jsonCuenta[$i]['dato'] . " ) ) ");
															$field_moneda = 1;
														} else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
														} else {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " MAX( TRIM( ".$jsonCuenta[$i]['dato']." ) ) ");
														}
													}
		
													if( $field_moneda==0 ){
														array_push($campoTableCuenta, " moneda ");
														array_push($campoTableCuentaTMP, " '' ");
													}
		
													for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cuenta']);$i++) {
														array_push($campoTableCuenta, $jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']);
														array_push($campoTableCuentaTMP, " MAX( TRIM( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['dato']." ) ) ");
													}
		
													$field_cartera = "";
													$field_group = "";
													$field_where = "";
													$field_on = "";
													$field_u_where = "";
													if( trim($gestion) == '' )  {
														$field_cartera = " ".$id_cartera." ";
														$field_u_where = " AND cu.idcartera = ".$id_cartera." ";
		
													}else{
														$field_cartera = " MAX( idcartera ) ";
														$field_group = " , idcartera ";
														$field_where = " AND tmp.idcartera IS NOT NULL ".$sql_where_main_tmp." ";
														$field_on = " AND tmp.idcartera = cu.idcartera ";
													}
		
													$insertCuenta = "";
													$sqlTMPUpdateIdCuenta = "";
		
													$field_on_c = $field_on;
													$group_by_c = $field_group;
													if($moneda_cuenta != '')
													{
														$field_on_c = $field_on." AND cu.moneda = TRIM( tmp.$moneda_cuenta ) ";
														$group_by_c = $field_group." , TRIM($moneda_cuenta) ";
													}
		
													if($grupo1_cuenta != '')
													{
														$field_on_c = $field_on." AND cu.grupo1_cuenta = TRIM( tmp.$grupo1_cuenta ) ";
														$group_by_c = $field_group." , TRIM($grupo1_cuenta) ";
													}
													
													$sqlCreateRuleCuenta = " 
															CREATE OR REPLACE RULE insert_ignore_ca_cuenta
															AS ON INSERT TO ca_cuenta
															WHERE EXISTS ( SELECT * FROM ca_cuenta WHERE idcliente_cartera = NEW.idcliente_cartera AND numero_cuenta = NEW.numero_cuenta AND moneda = NEW.moneda AND grupo1 = NEW.grupo1 ) 
															DO INSTEAD NOTHING
														";
		
													$insertCuenta = " INSERT INTO ca_cuenta ( idcliente_cartera, codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )  
															SELECT MAX( idcliente_cartera ), MAX( TRIM($codigo_cliente) ), ".$field_cartera.", 1, CURRENT_TIMESTAMP, $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . " 
															FROM tmpcartera_" . session_id() . "_" . $time . " tmp
															WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0 AND idcliente_cartera IS NOT NULL ".$field_where."
															GROUP BY idcliente_cartera, TRIM($numero_cuenta)".$group_by_c." ";
		
													$sqlTMPUpdateIdCuenta = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
															SET tmp.idcuenta = cu.idcuenta 
															FROM ca_cuenta cu 
															WHERE cu.idcliente_cartera = tmp.idcliente_cartera AND cu.numero_cuenta = tmp.$numero_cuenta ".$field_on_c." 
															tmp.idcliente_cartera IS NOT NULL ".$sql_where_main_tmp."  ".$field_u_where." ";
															
													$prCreateRuleCuenta = $connection->prepare($sqlCreateRuleCuenta);
													if( $prCreateRuleCuenta->execute() ){
		
														$prInsertCuenta = $connection->prepare($insertCuenta);
														if ($prInsertCuenta->execute()) {
																	
															$prTMPUpdateIdCuenta = $connection->prepare($sqlTMPUpdateIdCuenta);
															if ($prTMPUpdateIdCuenta->execute()) {
			
																if (count($jsonOperacion) > 0) {
																	$campoTableOperacionTMP = array();
																	$campoTableOperacion = array();
			
																	/*                                                         * *** */
																	$fieldTramo = "";
																	/*                                                         * ** */
			
																	for ($i = 0; $i < count($jsonOperacion); $i++) {
			
																		if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( " . $jsonOperacion[$i]['dato'] . " ) ) ");
																			$fieldTramo = $jsonOperacion[$i]['dato'];
																		} else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'fecha_asignacion') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'fecha_baja') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'fecha_alta') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'fecha_ciclo') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
																		} else {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( ".$jsonOperacion[$i]['dato']." ) ) ");
																		}
																	}
			
			
																	array_push($campoTableOperacion, " codigo_cliente " );
																	array_push($campoTableOperacionTMP, " MAX( TRIM( ".$codigo_cliente.") )");
			
																	array_push($campoTableOperacion, " numero_cuenta " );
																	array_push($campoTableOperacionTMP, " MAX( TRIM( ".$numero_cuenta.") )");
			
																	if( $moneda_cuenta != '' ) {
																		array_push($campoTableOperacion, " moneda_cuenta " );
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$moneda_cuenta.") )");
																	}
			
																	if( $grupo1_cuenta != '' ) {
																		array_push($campoTableOperacion, " grupo1_cuenta " );
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$grupo1_cuenta.") )");
																	}
			
																	for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_detalle_cuenta']);$i++) {
																		array_push($campoTableOperacion, $jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['dato']." ) ) ");
																	}
			
																	$field_group_o = $field_group;
																	if( $moneda_operacion != '' ) {
																		$field_group_o .= " , ".$moneda_operacion;
																	}
																	if( $grupo1_operacion != '' ) {
																		$field_group_o .= " , ".$grupo1_operacion;
																	}
																	
																	$sqlCreateRuleOperacion = "
																		CREATE OR REPLACE RULE insert_ignore_ca_detalle_cuenta
																		AS ON INSERT TO ca_detalle_cuenta
																		WHERE EXISTS( SELECT * FROM ca_detalle_cuenta WHERE idcuenta = NEW.idcuenta AND codigo_operacion = NEW.codigo_operacion AND moneda = NEW.moneda AND grupo1 = NEW.grupo1 )
																		DO INSTEAD NOTHING
																	";
			
																	$insertOperacion = " INSERT INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", idcartera, usuario_creacion,fecha_creacion, idcuenta ) 
																				SELECT " . implode(",", $campoTableOperacionTMP) . ", ".$field_cartera." , $usuario_creacion , CURRENT_TIMESTAMP , MAX(idcuenta)
																				FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																				WHERE LENGTH( TRIM( $codigo_cliente ) ) > 0 AND LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 AND idcuenta IS NOT NULL ".$field_where."
																				GROUP BY idcuenta, TRIM( $codigo_operacion ) ".$field_group_o." ";
																	
																	$prCreateRuleOperacion = $connection->prepare($sqlCreateRuleOperacion);
																	if( $prCreateRuleOperacion->execute() ) {
			
																		$prInsertOperacion = $connection->prepare($insertOperacion);
																		if ($prInsertOperacion->execute()) {
				
				
																				if (trim($fieldTramo) != "") {
																					$InsertTramo = " INSERT INTO ca_tramo ( tramo, fecha_creacion, usuario_creacion, idservicio, tipo ) 
																								SELECT DISTINCT( TRIM($fieldTramo) ),CURRENT_TIMESTAMP,$usuario_creacion , " . $_post['Servicio'] . " ,'TRAMO'
																								FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $fieldTramo ) ) > 0 ";
																					$prInsertTramo = $connection->prepare($InsertTramo);
																					if ($prInsertTramo->execute()) {
				
																					} else {
																						//$connection->rollBack();
																						return array('rst' => false, 'msg' => 'Error al insertar tramos');
																						//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar tramos'));
																						//exit();
																					}
																				}
				
				
																				$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);
				
																				foreach ($jsonTelefono as $index => $value) {
																					$fieldTelefono = array();
																					$fieldTelefonoTMP = array();
																					$fieldReferenciaTelefono = "";
																					if (count($value) > 0) {
				
																						foreach ($value as $i => $v) {
																							array_push($fieldTelefono, $i);
																							array_push($fieldTelefonoTMP, " TRIM(" . $v." )");
																							if ($i == "numero") {
																								$fieldReferenciaTelefono = $v;
																							}
																						}
				
																						$insertTelefono = "";
																						$field_where = "";
																						(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																						(trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";
				
																						$insertTelefono = " INSERT INTO ca_telefono ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
																											SELECT idcliente_cartera, idcuenta, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldTelefonoTMP) . "
																											FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																											WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																						$prInsertTelefono = $connection->prepare($insertTelefono);
																						if ($prInsertTelefono->execute()) {
				
																						} else {
				
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar telefono');
				
																						}
																					}
																				}
				
																				$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);
																				//$insertDireccion="";
																				foreach ($jsonDireccion as $index => $value) {
																					$fieldDireccion = array();
																					$fieldDireccionTMP = array();
																					$fieldDireccionTMPIntersec = array();
																					$fieldReferenciaDireccion = "";
																					$fieldUbigeo = "";
																					$FieldDepartamentoTMP = "";
																					$FieldProvinciaTMP = "";
																					$FieldDistritoTMP = "";
																					if (count($value) > 0) {
				
																						foreach ($value as $i => $v) {
				
																							if ($i == "direccion") {
																								$fieldReferenciaDireccion = $v;
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																							} else if ($i == "ubigeo") {
																								$fieldUbigeo = $v;
																								$FieldDepartamentoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[1] ) ";
																								$FieldDistritoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[2] ) ";
																								$FieldProvinciaTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[3] ) ";
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP,  $v);
																								array_push($fieldDireccion, "departamento");
																								array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
																								array_push($fieldDireccion, "provincia");
																								array_push($fieldDireccionTMP, $FieldProvinciaTMP);
																								array_push($fieldDireccion, "distrito");
																								array_push($fieldDireccionTMP, $FieldDistritoTMP);
																							} else if ($i == "departamento") {
																								if (!array_search("departamento", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else if ($i == "provincia") {
																								if (!array_search("provincia", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else if ($i == "distrito") {
																								if (!array_search("distrito", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else {
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																							}
																						}
				
																						$insertDireccion = "";
																						$field_where = "";
																						(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																						(trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";
				
																						$insertDireccion = " INSERT INTO ca_direccion ( codigo_cliente, idcliente_cartera, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta ) 
																											SELECT TRIM( $codigo_cliente ), idcliente_cartera, ".$field_cartera.", 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldDireccionTMP) . ", idcuenta 
																											FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																											WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																						$prInsertDireccion = $connection->prepare($insertDireccion);
																						if ($prInsertDireccion->execute()) {
				
																						} else {
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar direccion');
				
																						}
																					}
																				}
				
																				$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
				
																				foreach ($jsonAdicionales as $index => $value) {
				
																					$fieldValueTMP = array();
																					$fieldCabecera = array();
				
																					if (count($value) > 0) {
				
																						for ($i = 0; $i < count($value); $i++) {
																							array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
																							array_push($fieldCabecera, $value[$i]['campoT']);
																						}
																						$insertCabeceras = "";
																						if( trim($gestion) == '' ) {
																							$insertCabeceras=" INSERT INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, ".implode(",",$fieldCabecera)." ) 
																										VALUES( ".$_post['Servicio']." , ".$tipoDatosAdicionales[$index].", $id_cartera, CURRENT_TIMESTAMP, $usuario_creacion, ".implode(",",$fieldValueTMP)." ) ";
																						}else{
																							$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
																											SELECT " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", MAX( idcartera ), CURRENT_TIMESTAMP , $usuario_creacion, " . implode(",", $fieldValueTMP) . " 
																											FROM tmpcartera_" . session_id() . "_" . $time . " 
																											WHERE idcartera IS NOT NULL 
																											GROUP BY idcartera ";
																						}
				
																						$prInsertCabeceras = $connection->prepare($insertCabeceras);
																						if ($prInsertCabeceras->execute()) {
				
																						} else {
				
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar cabeceras');
				
																						}
																					}
																				}
				
																				//$connection->commit();
																				return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
																				//echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));
				
																		} else {
																			//$connection->rollBack();
				
																			$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																			@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																			@$prsqlDropTableTMPCarteraRollBack->execute();
																			return array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion');
																			//echo json_encode(array('rst'=>false,'msg'=>'No selecciono cabeceras de operacion'));
																		}
																	
																	}else{
																		
																		//$connection->rollBack();
																		$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																		@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																		@$prsqlDropTableTMPCarteraRollBack->execute();
																		return array('rst' => false, 'msg' => 'Error al crear regla para detalle de cuenta');
																		
																	}
																	
																} else {
			
																	$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);
			
																	foreach ($jsonTelefono as $index => $value) {
																		$fieldTelefono = array();
																		$fieldTelefonoTMP = array();
																		$fieldReferenciaTelefono = "";
																		if (count($value) > 0) {
			
																			foreach ($value as $i => $v) {
																				array_push($fieldTelefono, $i);
																				array_push($fieldTelefonoTMP, " TRIM( " . $v." ) ");
																				if ($i == "numero") {
																					$fieldReferenciaTelefono = $v;
																				}
																			}
			
																			$field_where = "";
																			(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																			(trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";
			
																			$insertTelefono = " INSERT INTO ca_telefono ( idcliente, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
																					SELECT idcliente, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldTelefonoTMP) . "
																					FROM tmpcartera_" . session_id() . "_" . $time . " 
																					WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																			$prInsertTelefono = $connection->prepare($insertTelefono);
																			if ($prInsertTelefono->execute()) {
			
																			} else {
																				//$connection->rollBack();
																				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																				@$prsqlDropTableTMPCarteraRollBack->execute();
																				return array('rst' => false, 'msg' => 'Error al insertar telefono');
																				//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar telefono'));
																				//exit();
																			}
																		}
																	}
			
																	$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);
			
																	foreach ($jsonDireccion as $index => $value) {
																		$fieldDireccion = array();
																		$fieldDireccionTMP = array();
																		$fieldDireccionTMPIntersec = array();
																		$fieldReferenciaDireccion = "";
																		$fieldUbigeo = "";
																		$FieldDepartamentoTMP = "";
																		$FieldProvinciaTMP = "";
																		$FieldDistritoTMP = "";
																		if (count($value) > 0) {
			
																			foreach ($value as $i => $v) {
			
																				if ($i == "direccion") {
																					$fieldReferenciaDireccion = $v;
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																				} else if ($i == "ubigeo") {
																					$fieldUbigeo = $v;
																					$FieldDepartamentoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[1] ) ";
																					$FieldDistritoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[2] ) ";
																					$FieldProvinciaTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[3] ) ";
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
																					array_push($fieldDireccion, "departamento");
																					array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
																					array_push($fieldDireccion, "provincia");
																					array_push($fieldDireccionTMP, $FieldProvinciaTMP);
																					array_push($fieldDireccion, "distrito");
																					array_push($fieldDireccionTMP, $FieldDistritoTMP);
																				} else if ($i == "departamento") {
																					if (!array_search("departamento", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else if ($i == "provincia") {
																					if (!array_search("provincia", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else if ($i == "distrito") {
																					if (!array_search("distrito", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else {
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																				}
																			}
			
																			$field_where = "";
																			(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																			(trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";
			
																			$insertDireccion = " INSERT INTO ca_direccion ( codigo_cliente, idcliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta ) 
																							SELECT TRIM( $codigo_cliente ), idcliente, idcartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldDireccionTMP) . ", idcuenta 
																							FROM tmpcartera_" . session_id() . "_" . $time . " 
																							WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
			
																			$prInsertDireccion = $connection->prepare($insertDireccion);
																			if ($prInsertDireccion->execute()) {
			
																			} else {
																				//$connection->rollBack();
			
																				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																				@$prsqlDropTableTMPCarteraRollBack->execute();
																				return array('rst' => false, 'msg' => 'Error al insertar direccion');
																				//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar direccion'));
																				//exit();
																			}
																		}
																	}
			
																	$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
			
																	foreach ($jsonAdicionales as $index => $value) {
			
																		$fieldValueTMP = array();
			
																		if ($index == 'ca_datos_adicionales_cliente' || $index == 'ca_datos_adicionales_cuenta') {
																			if (count($value) > 0) {
			
																				for ($i = 0; $i < count($value); $i++) {
																					array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
			
																				}
			
																				$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
																							VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, CURRENT_TIMESTAMP, $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
																				$prInsertCabeceras = $connection->prepare($insertCabeceras);
																				if ($prInsertCabeceras->execute()) {
			
																				} else {
																					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																					@$prsqlDropTableTMPCarteraRollBack->execute();
																					return array('rst' => false, 'msg' => 'Error al insertar cabeceras');
			
																				}
																			}
																		}
																	}
			
																	//$connection->commit();
																	return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
																	//echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));
																}
															} else {
																//$connection->rollBack();
			
																$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																@$prsqlDropTableTMPCarteraRollBack->execute();
																return array('rst' => false, 'msg' => 'Error al agregar id cuenta a temporal');
															}
														} else {
															//$connection->rollBack();
			
															$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
															@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
															@$prsqlDropTableTMPCarteraRollBack->execute();
															return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
															//echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
														}
													
													}else{
														$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
														@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
														@$prsqlDropTableTMPCarteraRollBack->execute();
														return array('rst' => false, 'msg' => 'Error al crear regla para cuenta');
													}
													
												} else {
													//$connection->rollBack();
		
													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();
													return array('rst' => false, 'msg' => 'Error al agregar id distribucion a temporal');
												}
											} else {
												//$connection->rollBack();
		
												$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
												@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
												@$prsqlDropTableTMPCarteraRollBack->execute();
												return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
												//echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
												//exit();	
											}
										
										}else{
											$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
											@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
											@$prsqlDropTableTMPCarteraRollBack->execute();
											return array('rst' => false, 'msg' => 'Error al crear regla para cliente cartera');
										}
										
									} else {
										//$connection->rollBack();
	
										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();
										return array('rst' => false, 'msg' => 'Error al insertar id cliente a temporal');
									}
									
								} else {
									//$connection->rollBack();
	
									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();
									return array('rst' => false, 'msg' => 'Error al insertar cliente');
									//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar cliente'));
								}
							/*} else {
	
								//$connection->rollBack();
	
								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();
	
								//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar data adicional a cartera'));
								return array('rst' => false, 'msg' => 'Error al actualizar temporal');
								//echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar temporal'));
							}*/
	
							/*                         * ********* */
						} else {
							//$connection->rollBack();
	
							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();
							return array('rst' => false, 'msg' => 'Error al insertar cartera');
							//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar cartera'));
						}
	
						/* }else{
						  $sqlDropTableTMPCarteraRollBack=" DROP TABLE IF EXISTS tmpcartera_".session_id()."_".$time." ";
						  @$prsqlDropTableTMPCarteraRollBack=$connection->prepare($sqlDropTableTMPCarteraRollBack);
						  @$prsqlDropTableTMPCarteraRollBack->execute();
						  return array('rst'=>false,'msg'=>'Error al agregar id de gestion');
						  //echo json_encode(array('rst'=>false,'msg'=>'Error al agregar id de gestion'));
						  } */
	
						}else{
							
							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();
							return array('rst' => false, 'msg' => 'Error al eliminar registro de cabeceras');
							
						}

					}else{
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						return array('rst' => false, 'msg' => 'Error campos adicionales');
					}

				} else {
					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
					@$prsqlDropTableTMPCarteraRollBack->execute();
					return array('rst' => false, 'msg' => 'Error load data infile');
					//echo json_encode(array('rst'=>false,'msg'=>'Error load data infile'));
				}
			} else {
				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
				@$prsqlDropTableTMPCarteraRollBack->execute();
				return array('rst' => false, 'msg' => 'Error create temporary table');
				//echo json_encode(array('rst'=>false,'msg'=>'Error create temporary table'));
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al eliminar tabla');
			//echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar tabla'));
		}
	}

	public function uploadUpdateCartera($_post, $is_parser=0,$file) {


		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$usuario_creacion = $_post["usuario_creacion"];

		$tipo_data = (int)$_post["TipoData"];
		$cabecera_departamento = $_post["CabeceraDepartamento"];

		//$id_cartera = $_post['Cartera'];

		$nombre_cartera = $_post["NombreCartera"];
		$codigo_cliente = $_post["codigo_cliente"];
		$numero_cuenta = $_post["numero_cuenta"];
		$codigo_operacion = $_post["codigo_operacion"];
		$moneda_cuenta = "";
		$moneda_operacion = "";
		$grupo1_cuenta = "";
		$grupo1_operacion = "";

		$gestion = "";

		$parserCliente = str_replace("\\", "", $_post["data_cliente"]);
		$parserCartera = str_replace("\\", "", $_post["data_cartera"]);
		$parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
		$parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
		$parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
		$parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
		$parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);



		$jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
		$jsonCartera = json_decode(str_replace("\\", "", $_post["data_cartera"]), true);
		$jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
		$jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
		$jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
		$jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
		$jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

		$sql_where_main = " ";
		$sql_where_main_tmp = " ";
		if( $tipo_data == 1 ) {

		}else if ( $tipo_data == 2 ){
			$sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
			$sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) IN ( 'LIMA','CALLAO' ) ";
		}else if ( $tipo_data == 3 ) {
			$sql_where_main = " AND UPPER( TRIM( ".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
			$sql_where_main_tmp = " AND UPPER( TRIM( tmp.".$cabecera_departamento." ) ) NOT IN ( 'LIMA','CALLAO' ) ";
		}else{
			echo json_encode(array('rst' => false, 'msg' => 'Tipo incorrecto de carga'));
			exit();
		}

		for( $i=0;$i<count($jsonCliente);$i++ ) {
			if( $jsonCliente[$i]['campoT'] == 'codigo' ) {
				$codigo_cliente = $jsonCliente[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonCartera);$i++ ) {
			if( $jsonCartera[$i]['campoT'] == 'nombre_cartera' ) {
				$gestion = $jsonCartera[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonCuenta);$i++ ) {
			if( $jsonCuenta[$i]['campoT'] == 'numero_cuenta' ) {
				$numero_cuenta = $jsonCuenta[$i]['dato'];
			}else if( $jsonCuenta[$i]['campoT'] == 'moneda' ){
				$moneda_cuenta = $jsonCuenta[$i]['dato'];
			}else if( $jsonCuenta[$i]['campoT'] == 'grupo1' ) {
				$grupo1_cuenta = $jsonCuenta[$i]['dato'];
			}
		}

		for( $i=0;$i<count($jsonOperacion);$i++ ) {
			if( $jsonOperacion[$i]['campoT'] == 'codigo_operacion' ) {
				$codigo_operacion = $jsonOperacion[$i]['dato'];
			}else if( $jsonOperacion[$i]['campoT'] == 'moneda' ){
				$moneda_operacion = $jsonOperacion[$i]['dato'];
			}else if( $jsonOperacion[$i]['campoT'] == 'grupo1' ) {
				$grupo1_operacion = $jsonOperacion[$i]['dato'];
			}
		}




		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera');
			//echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();	
		}


		$time = date("Y_m_d_H_i_s");
		//$archivoParser = file($path);
		$archivoParser = @fopen($path, "r+");
		//$columMap = explode($_post['separator'],$archivoParser[0]);
		$columMap = array();
		if ($_post['separator'] == 'tab') {
			$columMap = explode("\t", fgets($archivoParser));
		} else {
			$columMap = explode($_post['separator'], fgets($archivoParser));
		}
		/*         * ****** */
		fclose($archivoParser);
		/*         * ******* */
		if (!function_exists('map_header')) {

			function map_header($n) {
				$item = "";
				if (trim(utf8_encode($n)) != "") {
					//$buscar=array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",".","#"," ","/","�","�","@","(",")","$","&","%");
					//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
					$buscar = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ".", "#", " ", "/", "�", "�", "@", "(", ")", "$", "&", "%", "'", '"', "?", "�", "!", "�", "[", "]", "-", "�");
					$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "");
					$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
					//$item="`".$item."` VARCHAR(200) ";
				}

				return $item;
			}

		}

		$colum = array_map("map_header", $columMap);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		$parserHeader = implode(",", $colum);

		/*         * ********* */
		/*array_push($columHeader, "`idcartera` INT ");
		array_push($columHeader, "`idcliente` INT ");
		array_push($columHeader, "`idcliente_cartera` INT ");
		array_push($columHeader, "`idcuenta` INT ");*/
		array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
		if ($codigo_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
		} 
		if ($moneda_cuenta != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
		} 
		if ($moneda_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
		} 
		if ($grupo1_cuenta != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_cuenta` ( `$grupo1_cuenta` ASC ) ");
		} 
		if ($grupo1_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_grupo1_operacion` ( `$grupo1_operacion` ASC ) ");
		} 
		if ($gestion != "") {
			array_push($columHeader, "INDEX `index_" . session_id() . "_gestion` ( `$gestion` ASC ) ");
		}

		/*array_push($columHeader, "INDEX `index_" . session_id() . "_idcartera` ( `idcartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente` ( `idcliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente_cartera` ( `idcliente_cartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");*/

		/*         * ********** */

		if ($countHeaderFalse > 0) {
			return array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias ');
			//echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
			exit();
		}


		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		if ($prDropTableTMPCartera->execute()) {

			$sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = Aria DEFAULT CHARACTER SET = latin1 ";

			$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
			if ($prsqlCreateTabelTMPCartera->execute()) {

				/* $sqlLoadDataInFileUC=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$file."'
				  INTO TABLE tmpcartera_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES "; */
				$sqlLoadDataInFileUC = "";
				if ($_post['separator'] == 'tab') {
					$sqlLoadDataInFileUC = " COPY tmpcartera_" . session_id() . "_" . $time . " FROM  '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "'  ";
				} else {
					$sqlLoadDataInFileUC = " COPY tmpcartera_" . session_id() . "_" . $time . " FROM '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $file . "' 
					USING DELIMITERS '".$_post['separator']."' ";
				}
				//echo $sqlLoadDataInFileUC;
				$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
				if ($prLoadDataInFileUC->execute()) {

					$sqlAlterTableTMPCartera = " ALTER  TABLE tmpcartera_".session_id()."_".$time." ADD idcartera INT, ADD idcliente INT, ADD idcliente_cartera INT, ADD idcuenta INT, ADD INDEX(idcliente), ADD INDEX(idcliente_cartera), ADD INDEX(idcuenta) ";
					$prAlterTableTMPCartera = $connection->prepare($sqlAlterTableTMPCartera);
					if( $prAlterTableTMPCartera->execute() ) { 

					//$connection->beginTransaction();
						$deleteFirstLineCartera = " DELETE FROM tmpcartera_".session_id()."_".$time." WHERE TRIM( $codigo_cliente ) = '$codigo_cliente' ";
						$prDeleteFirstLineCartera = $connection->prepare($deleteFirstLineCartera);
						if( $prDeleteFirstLineCartera->execute() ) {
						
							$id_cartera = 0;
							if( trim($gestion) == '' )  {
								$id_cartera = $_post['Cartera'];
							}else{
								$updateTMPCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
										SET idcartera = ( SELECT idcartera FROM ca_cartera WHERE idcampania = " . $_post['Campania'] . " AND estado = 1 AND TRIM(nombre_cartera) = TRIM(tmp.$gestion) ORDER BY idcartera DESC LIMIT 1 ) 
										WHERE TRIM(tmp.$gestion) != '' ".$sql_where_main_tmp." ";
								$prUpdateTMPCartera = $connection->prepare($updateTMPCartera);
								if( $prUpdateTMPCartera->execute() ) {
	
								}else{
									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();
									return array('rst' => false, 'msg' => 'Error al actualizar temporal');
									exit();
								}
							}
	
	
							//$id_cartera=$connection->lastInsertId();
	
							/*                         * ********* */
							//$sqlUpdateCartera = " UPDATE ca_cartera SET cartera_act = $id_cartera WHERE idcartera = $id_cartera ";
							//$prUpdateCartera = $connection->prepare($sqlUpdateCartera);
							//if( $prUpdateCartera->execute() ) {
							//if ($prUpdateTMPCartera->execute()) {
	
								/*                             * ***** save parser ****** */
								if ($is_parser == 1) {
	
									//$insertJsonParser=" INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador ) 
									//										VALUES ( ".$_post['Servicio'].",".$usuario_creacion.",NOW(),'".$parserHeader."','".$parserCliente."','".$parserCuenta."','".$parserOperacion."','".$parserTelefono."','".$parserDireccion."','".$parserAdicionales."', '".$codigo_cliente."','".$numero_cuenta."','".$codigo_operacion."','".$_post['separator']."' ) ";
	
									$insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador, moneda_cuenta, moneda_operacion ) 
												VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",CURRENT_TIMESTAMP,'" . $parserHeader . "','" . $parserCliente . "','".$parserCartera."','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "','" . $moneda_cuenta . "','" . $moneda_operacion . "' ) ";
	
									$prInsertJsonParser = $connection->prepare($insertJsonParser);
									if ($prInsertJsonParser->execute()) {
	
									} else {
										//$connection->rollBack(); 
										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();
										return array('rst' => false, 'msg' => 'Error al insertar metadata');
										//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar metadata'));
										//exit();
									}
								}
								/*                             * ************ */
								$insertCliente = " ";
	
								$campoTableClienteTMP = array();
								$campoTableCliente = array();
	
								for ($i = 0; $i < count($jsonCliente); $i++) {
									if ($jsonCliente[$i]['campoT'] == 'codigo') {
										array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
										array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
									} else {
										array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
										array_push($campoTableClienteTMP, " MAX( TRIM(".$jsonCliente[$i]['dato'].") ) ");
									}
								}
	
								//$insertCliente=" INSERT IGNORE INTO ca_cliente ( idservicio,".implode(",",$campoTableCliente)." )  
								//SELECT ".$_post['Servicio'].",".implode(",",$campoTableClienteTMP)." FROM tmpcartera_".session_id()."_".$time." GROUP BY TRIM($codigo_cliente) ";
	
								$insertCliente = " INSERT INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " ) 
											SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " 
											FROM tmpcartera_" . session_id() . "_" . $time . " 
											WHERE LENGTH( TRIM($codigo_cliente) )>0 ".$sql_where_main."
											GROUP BY TRIM($codigo_cliente) ";
	
								$prInsertCliente = $connection->prepare($insertCliente);
								if ($prInsertCliente->execute()) {
	
									/******************* */
									$sqlTMPUpdateIdCliente = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
												SET tmp.idcliente = cli.idcliente 
												FROM ca_cliente cli 
												WHERE cli.codigo = tmp.$codigo_cliente AND cli.idservicio = " . $_post['Servicio'] . " ".$sql_where_main_tmp." ";
												
									/********************/
	
									$prTMPUpdateIdCliente = $connection->prepare($sqlTMPUpdateIdCliente);
									if ($prTMPUpdateIdCliente->execute()) {
	
										$campoTableClienteCarteraTMP = array();
										$campoTableClienteCartera = array();
	
										$campoUpdateTableClienteCartera = array();
	
										for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cliente']);$i++) {
											array_push($campoTableClienteCartera, $jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']);
											array_push($campoTableClienteCarteraTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['dato']." ) ");
	
											array_push($campoUpdateTableClienteCartera, " ".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']." =  NEW.".$jsonAdicionales['ca_datos_adicionales_cliente'][$i]['campoT']." ");
	
										}
	
										$implode_cc_t = "";
										$implode_cc_tmp = "";
										$implode_cc_u_t = "";
	
										if( count($campoTableClienteCartera) > 0 ){
											$implode_cc_t = ", ".implode(",",$campoTableClienteCartera);
											$implode_cc_tmp = ", ".implode(",",$campoTableClienteCarteraTMP);
										}
										if( count($campoUpdateTableClienteCartera)>0 ) {
											$implode_cc_u_t = " , ".implode(",",$campoUpdateTableClienteCartera);
										}
	
										$field_cartera = "";
										$field_group = "";
										( trim($gestion) == '' )
										?
											$field_cartera = " ".$id_cartera." "
										:
											$field_cartera = " idcartera ";
											$field_group = " idcartera , ";
										;
										
										$sqlCreateRuleClienteCartera = " 
											CREATE OR REPLACE RULE duplicate_key_update_ca_cliente_cartera  
											AS ON INSERT TO ca_cliente_cartera
											WHERE EXISTS( SELECT * FROM ca_cliente_cartera WHERE idcartera = NEW.idcartera AND codigo_cliente = NEW.codigo_cliente )
											DO INSTEAD
											UPDATE ca_cliente_cartera 
											SET estado = 0 $implode_cc_u_t 
											WHERE idcartera = NEW.idcartera AND codigo_cliente = NEW.codigo_cliente 
										 ";
										 
										$prCreateRuleClienteCartera = $connection->prepare( $sqlCreateRuleClienteCartera );
										if( $prCreateRuleClienteCartera->execute() ) {
	
											$InsertClienteCartera = " INSERT INTO ca_cliente_cartera ( idcliente, codigo_cliente,idcartera,usuario_creacion,fecha_creacion ".$implode_cc_t." ) 
														SELECT idcliente, TRIM($codigo_cliente), ".$field_cartera." ," . $usuario_creacion . ", CURRENT_TIMESTAMP  ".$implode_cc_tmp." 
														FROM tmpcartera_" . session_id() . "_" . $time . " 
														WHERE LENGTH( TRIM($codigo_cliente) )>0 AND idcliente IS NOT NULL ".$sql_where_main." ";
		
											//echo $InsertClienteCartera;
											$prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
											if ($prInsertClienteCartera->execute()) {
												$field_on = "";
												$field_where = "";
												( trim($gestion) == '' )?$field_on = " " : $field_on = " AND clicar.idcartera = tmp.idcartera ";
												( trim($gestion) == '' )?$field_where = " AND clicar.idcartera = ".$id_cartera." ".$sql_where_main_tmp." " : $field_where = " AND tmp.idcliente IS NOT NULL ".$sql_where_main_tmp." ";
												/************/
												$sqlTMPUpdateIdClienteCartera = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
															SET tmp.idcliente_cartera = clicar.idcliente_cartera 
															FROM ca_cliente_cartera clicar  
															WHERE clicar.idcliente = tmp.idcliente ".$field_on." ".$field_where." "; 
												/***************/
												//echo $sqlTMPUpdateIdClienteCartera;exit();
												$prTMPUpdateIdClienteCartera = $connection->prepare($sqlTMPUpdateIdClienteCartera);
												if ($prTMPUpdateIdClienteCartera->execute()) {
		
													$campoTableCuentaTMP = array();
													$campoTableCuenta = array();
		
													$campoUpdateTableCuenta = array();
		
													for ($i = 0; $i < count($jsonCuenta); $i++) {
														if ( $jsonCuenta[$i]['campoT'] == 'total_deuda' || $jsonCuenta[$i]['campoT'] == 'monto_mora' || $jsonCuenta[$i]['campoT'] == 'saldo_capital' ) {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
															/********************/
															array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = NEW.".$jsonCuenta[$i]['campoT']." ");
														} else if ($jsonCuenta[$i]['campoT'] == 'monto_pagado') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
															/********************/
															array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = NEW.".$jsonCuenta[$i]['campoT']." ");
														} else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
														} else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
														} else if($jsonCuenta[$i]['campoT'] == 'grupo1'){
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
														}else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
															/********************/
															array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = NEW.".$jsonCuenta[$i]['campoT']." ");
														} else {
															array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
															array_push($campoTableCuentaTMP, " TRIM( ".$jsonCuenta[$i]['dato']." ) ");
															/********************/
															array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = NEW.".$jsonCuenta[$i]['campoT']." ");
														}
													}
		
													for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_cuenta']);$i++) {
														array_push($campoTableCuenta, $jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['campoT']);
														array_push($campoTableCuentaTMP, " TRIM( ".$jsonAdicionales['ca_datos_adicionales_cuenta'][$i]['dato']." ) ");
														
														array_push($campoUpdateTableCuenta," ".$jsonCuenta[$i]['campoT']." = NEW.".$jsonCuenta[$i]['campoT']." ");
													}
		
													$field_cartera = "";
													$field_group = "";
													$field_where = "";
													$field_on = "";
													$field_u_where = "";
													if( trim($gestion) == '' )  {
														$field_cartera = " ".$id_cartera." ";
														$field_u_where = " AND cu.idcartera = ".$id_cartera." ";
														$field_where = $sql_where_main_tmp ;
													}else{
														$field_cartera = " idcartera ";
														$field_group = " , idcartera ";
														$field_where = " AND tmp.idcartera IS NOT NULL ".$sql_where_main_tmp." ";
														$field_on = " AND tmp.idcartera = cu.idcartera ";
													}
		
													$insertCuenta = "";
													$sqlTMPUpdateIdCuenta = "";
		
													$field_on_c = $field_on;
													$group_by_c = $field_group;
													if($moneda_cuenta != '')
													{
														$field_on_c = $field_on." AND cu.moneda = TRIM( tmp.$moneda_cuenta ) ";
														$group_by_c = $field_group." , TRIM($moneda_cuenta) ";
													}
		
													if($grupo1_cuenta != '')
													{
														$field_on_c = $field_on." AND cu.grupo1_cuenta = TRIM( tmp.$grupo1_cuenta ) ";
														$group_by_c = $field_group." , TRIM($grupo1_cuenta) ";
													}
													
													$sqlCreateRuleCuenta = " 
														CREATE OR REPLACE duplicate_key_update_ca_cuenta 
														AS ON INSERT TO ca_cuenta
														WHERE EXISTS( SELECT * FROM ca_cuenta WHERE idcliente_cartera = NEW.idcliente_cartera AND numero_cuenta = NEW.numero_cuenta AND moneda = NEW.moneda AND grupo1 = NEW.grupo1 )
														DO INSTEAD
														UPDATE ca_cuenta
														SET 
														".implode(",",$campoUpdateTableCuenta)." 
														WHERE idcliente_cartera = NEW.idcliente_cartera AND numero_cuenta = NEW.numero_cuenta AND moneda = NEW.moneda AND grupo1 = NEW.grupo1
													 ";
		
													$insertCuenta = " INSERT INTO ca_cuenta ( idcliente_cartera, codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )  
															SELECT idcliente_cartera, TRIM($codigo_cliente), ".$field_cartera.", 1, CURRENT_TIMESTAMP, $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . " 
															FROM tmpcartera_" . session_id() . "_" . $time . " tmp
															WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0 AND idcliente_cartera IS NOT NULL ".$field_where."
															GROUP BY idcliente_cartera, TRIM($numero_cuenta)".$group_by_c." ";
		
													$sqlTMPUpdateIdCuenta = " UPDATE tmpcartera_" . session_id() . "_" . $time . " tmp 
															SET tmp.idcuenta = cu.idcuenta 
															FROM ca_cuenta cu 
															WHERE cu.idcliente_cartera = tmp.idcliente_cartera 
															AND cu.numero_cuenta = tmp.$numero_cuenta 
															".$field_on_c." 
															AND tmp.idcliente_cartera IS NOT NULL ".$field_u_where." ".$sql_where_main_tmp." ";
															
													$prCreateRuleCuenta = $connection->prepare($sqlCreateRuleCuenta);
													if( $prCreateRuleCuenta ) {
													
														$prInsertCuenta = $connection->prepare($insertCuenta);
														if ($prInsertCuenta->execute()) {
			
															$prTMPUpdateIdCuenta = $connection->prepare($sqlTMPUpdateIdCuenta);
															if ($prTMPUpdateIdCuenta->execute()) {
			
																if (count($jsonOperacion) > 0) {
																	$campoTableOperacionTMP = array();
																	$campoTableOperacion = array();
			
																	$campoUpdateTableOperacion = array();
			
																	/*                                                         * *** */
																	$fieldTramo = "";
																	/*                                                         * ** */
			
																	for ($i = 0; $i < count($jsonOperacion); $i++) {
			
																		if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
																		}else if ($jsonOperacion[$i]['campoT'] == 'grupo1') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
																		}else if ( in_array($jsonOperacion[$i]['campoT'] ,array('total_deuda','total_deuda_soles','total_deuda_dolares','monto_mora','monto_mora_soles','monto_mora_dolares','saldo_capital','saldo_capital_soles','saldo_capital_dolares') ) ) {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " SUM( TRIM( " . $jsonOperacion[$i]['dato'] . " ) ) ");
																			/********************/
																			array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = NEW.".$jsonOperacion[$i]['campoT']." ) ");
																		} else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( " . $jsonOperacion[$i]['dato'] . " ) ) ");
																			/*******************/
																			array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = NEW.".$jsonOperacion[$i]['campoT']." ) ");
																			$fieldTramo = $jsonOperacion[$i]['dato'];
																		} else if ( in_array($jsonOperacion[$i]['campoT'], array('fecha_vencimiento','fecha_asignacion','fecha_baja','fecha_alta','fecha_ciclo') ) ) {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX(
																					CASE
																					WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 8
																					THEN (SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 5 FOR 2) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 7 FOR 2))
																					WHEN LENGTH(TRIM(".$jsonOperacion[$i]['dato'].")) = 10
																					THEN 
																						CASE
																						WHEN STRPOS(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 3 OR STRPOS(TRIM(".$jsonOperacion[$i]['dato']."),'-') = 3 OR STRPOS(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 3 
																						THEN (SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 7 FOR 4) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 4 FOR 2) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 1 FOR 2))
																						WHEN STRPOS(TRIM(".$jsonOperacion[$i]['dato']."),'/') = 5 OR STRPOS(TRIM(".$jsonOperacion[$i]['dato']."),'.') = 5
																						THEN (SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 1 FOR 4) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 6 FOR 2) || '-' || SUBSTRING(".$jsonOperacion[$i]['dato']." FROM 9 FOR 2))
																						ELSE TRIM(".$jsonOperacion[$i]['dato'].")
																						END
																					ELSE TRIM(".$jsonOperacion[$i]['dato'].")
																					END 
																					 ) " );
																			/*******************/
																			array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = NEW.".$jsonOperacion[$i]['campoT']." ) ");
																		} else {
																			array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
																			array_push($campoTableOperacionTMP, " MAX( TRIM( ".$jsonOperacion[$i]['dato']." ) ) ");
																			/*******************/
																			array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = NEW.".$jsonOperacion[$i]['campoT']." ) ");
																		}
																	}
			
			
																	array_push($campoTableOperacion, " codigo_cliente " );
																	array_push($campoTableOperacionTMP, " MAX( TRIM( ".$codigo_cliente.") )");
			
																	array_push($campoTableOperacion, " numero_cuenta " );
																	array_push($campoTableOperacionTMP, " MAX( TRIM( ".$numero_cuenta.") )");
			
																	if( $moneda_cuenta != '' ) {
																		array_push($campoTableOperacion, " moneda_cuenta " );
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$moneda_cuenta.") )");
																	}
			
																	if( $grupo1_cuenta != '' ) {
																		array_push($campoTableOperacion, " grupo1_cuenta " );
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$grupo1_cuenta.") )");
																	}
			
																	for ($i=0;$i<count($jsonAdicionales['ca_datos_adicionales_detalle_cuenta']);$i++) {
																		array_push($campoTableOperacion, $jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
																		array_push($campoTableOperacionTMP, " MAX( TRIM( ".$jsonAdicionales['ca_datos_adicionales_detalle_cuenta'][$i]['dato']." ) ) ");
																		/*******************/
																		array_push($campoUpdateTableOperacion," ".$jsonOperacion[$i]['campoT']." = NEW.".$jsonOperacion[$i]['campoT']." ) ");
																	}
			
																	//$field_group_o = $field_group;
																	$field_group_o = "";
																	if( $moneda_operacion != '' ) {
																		$field_group_o .= " , ".$moneda_operacion;
																	}
																	if( $grupo1_operacion != '' ) {
																		$field_group_o .= " , ".$grupo1_operacion;
																	}
																	
																	$sqlCreateRuleOperacion = " 
																		CREATE OR REPLACE RULE duplicate_key_update_ca_detalle_cuenta 
																		AS ON INSERT TO ca_detalle_cuenta 
																		WHERE EXISTS( SELECT * FROM ca_detalle_cuenta WHERE idcuenta = NEW.idcuenta AND codigo_operacion = NEW.codigo_operacion AND moneda = NEW.moneda AND grupo1 = NEW.grupo1 )
																		DO INSTEAD
																		UPDATE ca_detalle_cuenta
																		SET
																		".implode(",",$campoUpdateTableOperacion)." 
																		WHERE idcuenta = NEW.idcuenta AND codigo_operacion = NEW.codigo_operacion AND moneda = NEW.moneda AND grupo1 = NEW.grupo1
																	 ";
			
																	$insertOperacion = " INSERT INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", idcartera, usuario_creacion,fecha_creacion, idcuenta ) 
																				SELECT " . implode(",", $campoTableOperacionTMP) . ", ".$field_cartera." , $usuario_creacion , CURRENT_TIMESTAMP , idcuenta
																				FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																				WHERE LENGTH( TRIM( $codigo_cliente ) ) > 0 AND LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 AND idcuenta IS NOT NULL ".$field_where."
																				GROUP BY idcuenta, TRIM( $codigo_operacion ) ".$field_group_o." ";
																				
																	$prCreateRuleOperacion = $connection->prepare($sqlCreateRuleOperacion);
																	if( $prCreateRuleOperacion->execute() ) {
																		
																		$prInsertOperacion = $connection->prepare($insertOperacion);
																		if ($prInsertOperacion->execute()) {
				
				
																				if (trim($fieldTramo) != "") {
																					$InsertTramo = " INSERT INTO ca_tramo ( tramo, fecha_creacion, usuario_creacion, idservicio, tipo ) 
																								SELECT DISTINCT( TRIM($fieldTramo) ),CURRENT_TIMESTAMP,$usuario_creacion , " . $_post['Servicio'] . " ,'TRAMO'
																								FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $fieldTramo ) ) > 0 ";
																					$prInsertTramo = $connection->prepare($InsertTramo);
																					if ($prInsertTramo->execute()) {
				
																					} else {
																						//$connection->rollBack();
																						return array('rst' => false, 'msg' => 'Error al insertar tramos');
																						//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar tramos'));
																						//exit();
																					}
																				}
				
				
																				$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);
				
																				foreach ($jsonTelefono as $index => $value) {
																					$fieldTelefono = array();
																					$fieldTelefonoTMP = array();
																					$fieldReferenciaTelefono = "";
																					if (count($value) > 0) {
				
																						foreach ($value as $i => $v) {
																							array_push($fieldTelefono, $i);
																							array_push($fieldTelefonoTMP, " TRIM(" . $v." )");
																							if ($i == "numero") {
																								$fieldReferenciaTelefono = $v;
																							}
																						}
				
																						$insertTelefono = "";
																						$field_where = "";
																						(trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
																						(trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";
				
																						$insertTelefono = " INSERT INTO ca_telefono ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
																											SELECT idcliente_cartera, idcuenta, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldTelefonoTMP) . "
																											FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																											WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																						$prInsertTelefono = $connection->prepare($insertTelefono);
																						if ($prInsertTelefono->execute()) {
				
																						} else {
				
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar telefono');
				
																						}
																					}
																				}
				
																				$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);
																				//$insertDireccion="";
																				foreach ($jsonDireccion as $index => $value) {
																					$fieldDireccion = array();
																					$fieldDireccionTMP = array();
																					$fieldDireccionTMPIntersec = array();
																					$fieldReferenciaDireccion = "";
																					$fieldUbigeo = "";
																					$FieldDepartamentoTMP = "";
																					$FieldProvinciaTMP = "";
																					$FieldDistritoTMP = "";
																					if (count($value) > 0) {
				
																						foreach ($value as $i => $v) {
				
																							if ($i == "direccion") {
																								$fieldReferenciaDireccion = $v;
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																							} else if ($i == "ubigeo") {
																								$fieldUbigeo = $v;
																								$FieldDepartamentoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[1] ) ";
																								$FieldDistritoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[2] ) ";
																								$FieldProvinciaTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[3] ) ";
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP,  $v);
																								array_push($fieldDireccion, "departamento");
																								array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
																								array_push($fieldDireccion, "provincia");
																								array_push($fieldDireccionTMP, $FieldProvinciaTMP);
																								array_push($fieldDireccion, "distrito");
																								array_push($fieldDireccionTMP, $FieldDistritoTMP);
																							} else if ($i == "departamento") {
																								if (!array_search("departamento", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else if ($i == "provincia") {
																								if (!array_search("provincia", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else if ($i == "distrito") {
																								if (!array_search("distrito", $fieldDireccion)) {
																									array_push($fieldDireccion, $i);
																									array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																								}
																							} else {
																								array_push($fieldDireccion, $i);
																								array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																							}
																						}
				
																						$insertDireccion = "";
																						$field_where = "";
																						(trim($gestion) =='')?:$field_where .= " AND ISNULL(idcartera) = 0 ";
																						(trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";
				
																						$insertDireccion = " INSERT INTO ca_direccion ( codigo_cliente, idcliente_cartera, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta ) 
																											SELECT TRIM( $codigo_cliente ), idcliente_cartera, ".$field_cartera.", 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldDireccionTMP) . ", idcuenta 
																											FROM tmpcartera_" . session_id() . "_" . $time . " tmp
																											WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																						$prInsertDireccion = $connection->prepare($insertDireccion);
																						if ($prInsertDireccion->execute()) {
				
																						} else {
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar direccion');
				
																						}
																					}
																				}
				
																				$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
				
																				foreach ($jsonAdicionales as $index => $value) {
				
																					$fieldValueTMP = array();
																					$fieldCabecera = array();
				
																					$fieldUpdateCabecera = array();
				
																					if (count($value) > 0) {
				
																						for ($i = 0; $i < count($value); $i++) {
																							array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
																							array_push($fieldCabecera, $value[$i]['campoT']);
																							/****************/
																							array_push($fieldUpdateCabecera, " ".$value[$i]['campoT']." =  '".$value[$i]['label']."' ");
																						}
																						
																						$insertCabeceras = "";
																						if( trim($gestion) == '' ) {
																										
																							$insertCabeceras = "
																								UPDATE ca_cabeceras
																								SET
																								fecha_modificacion = CURRENT_TIMESTAMP, 
																								usuario_modificacion = $usuario_creacion , 
																								".implode(",",$fieldUpdateCabecera)."
																								WHERE idcartera = ".$id_cartera." 
																								AND idservicio = ".$_post['Servicio']." AND idtipo_datos_adicionales = ".$tipoDatosAdicionales[$index]."
																							";
																						}else{
																							
																							$insertCabeceras = "
																								UPDATE ca_cabeceras
																								SET
																								fecha_modificacion = CURRENT_TIMESTAMP,
																								usuario_modificacion = $usuario_creacion ,
																								".implode(",",$fieldUpdateCabecera)." 
																								WHERE idcartera IN ( SELECT DISTINCT idcartera FROM tmpcartera_" . session_id() . "_" . $time . "  )
																								AND idservicio = ".$_post['Servicio']." AND idtipo_datos_adicionales = ".$tipoDatosAdicionales[$index]."
																							";
																							
																						}
				
																						$prInsertCabeceras = $connection->prepare($insertCabeceras);
																						if ($prInsertCabeceras->execute()) {
				
																						} else {
				
																							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																							@$prsqlDropTableTMPCarteraRollBack->execute();
																							return array('rst' => false, 'msg' => 'Error al insertar cabeceras');
				
																						}
																					}
																				}
				
																				//$connection->commit();
																				return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
																				//echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));
				
																		} else {
																			//$connection->rollBack();
				
																			$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																			@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																			@$prsqlDropTableTMPCarteraRollBack->execute();
																			return array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion');
																			//echo json_encode(array('rst'=>false,'msg'=>'No selecciono cabeceras de operacion'));
																		}
																	
																	}else{
																		$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																		@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																		@$prsqlDropTableTMPCarteraRollBack->execute();
																		return array('rst' => false, 'msg' => 'Error al crear regla para detalle cuenta');
																	}
																	
																} else {
			
																	$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);
			
																	foreach ($jsonTelefono as $index => $value) {
																		$fieldTelefono = array();
																		$fieldTelefonoTMP = array();
																		$fieldReferenciaTelefono = "";
																		if (count($value) > 0) {
			
																			foreach ($value as $i => $v) {
																				array_push($fieldTelefono, $i);
																				array_push($fieldTelefonoTMP, " TRIM( " . $v." ) ");
																				if ($i == "numero") {
																					$fieldReferenciaTelefono = $v;
																				}
																			}
			
																			$field_where = "";
																			(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																			(trim($fieldReferenciaTelefono) == '') ?:$field_where .= " AND TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 ";
			
																			$insertTelefono = " INSERT INTO ca_telefono ( idcliente, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
																					SELECT idcliente, TRIM($codigo_cliente), 1, ".$field_cartera.", " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldTelefonoTMP) . "
																					FROM tmpcartera_" . session_id() . "_" . $time . " 
																					WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
																			$prInsertTelefono = $connection->prepare($insertTelefono);
																			if ($prInsertTelefono->execute()) {
			
																			} else {
																				//$connection->rollBack();
																				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																				@$prsqlDropTableTMPCarteraRollBack->execute();
																				return array('rst' => false, 'msg' => 'Error al insertar telefono');
																				//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar telefono'));
																				//exit();
																			}
																		}
																	}
			
																	$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);
			
																	foreach ($jsonDireccion as $index => $value) {
																		$fieldDireccion = array();
																		$fieldDireccionTMP = array();
																		$fieldDireccionTMPIntersec = array();
																		$fieldReferenciaDireccion = "";
																		$fieldUbigeo = "";
																		$FieldDepartamentoTMP = "";
																		$FieldProvinciaTMP = "";
																		$FieldDistritoTMP = "";
																		if (count($value) > 0) {
			
																			foreach ($value as $i => $v) {
			
																				if ($i == "direccion") {
																					$fieldReferenciaDireccion = $v;
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																				} else if ($i == "ubigeo") {
																					$fieldUbigeo = $v;
																					$FieldDepartamentoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[1] ) ";
																					$FieldDistritoTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[2] ) ";
																					$FieldProvinciaTMP = " TRIM( (REGEXP_SPLIT_TO_ARRAY ( TRIM(" . $v . "),E'\-+'))[3] ) ";
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
																					array_push($fieldDireccion, "departamento");
																					array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
																					array_push($fieldDireccion, "provincia");
																					array_push($fieldDireccionTMP, $FieldProvinciaTMP);
																					array_push($fieldDireccion, "distrito");
																					array_push($fieldDireccionTMP, $FieldDistritoTMP);
																				} else if ($i == "departamento") {
																					if (!array_search("departamento", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else if ($i == "provincia") {
																					if (!array_search("provincia", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else if ($i == "distrito") {
																					if (!array_search("distrito", $fieldDireccion)) {
																						array_push($fieldDireccion, $i);
																						array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																					}
																				} else {
																					array_push($fieldDireccion, $i);
																					array_push($fieldDireccionTMP, " TRIM( " . $v." ) ");
																				}
																			}
			
																			$field_where = "";
																			(trim($gestion) =='')?:$field_where .= " AND idcartera IS NOT NULL ";
																			(trim($fieldReferenciaDireccion) == '')?$field_where = "":$field_where .= " AND TRIM( $fieldReferenciaDireccion )!='' ";
			
																			$insertDireccion = " INSERT INTO ca_direccion ( codigo_cliente, idcliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . ", idcuenta ) 
																							SELECT TRIM( $codigo_cliente ), idcliente, idcartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, CURRENT_TIMESTAMP, " . implode(",", $fieldDireccionTMP) . ", idcuenta 
																							FROM tmpcartera_" . session_id() . "_" . $time . " 
																							WHERE idcliente_cartera IS NOT NULL AND idcuenta IS NOT NULL ".$field_where." ";
			
																			$prInsertDireccion = $connection->prepare($insertDireccion);
																			if ($prInsertDireccion->execute()) {
			
																			} else {
																				//$connection->rollBack();
			
																				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																				@$prsqlDropTableTMPCarteraRollBack->execute();
																				return array('rst' => false, 'msg' => 'Error al insertar direccion');
																				//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar direccion'));
																				//exit();
																			}
																		}
																	}
			
																	$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
			
																	foreach ($jsonAdicionales as $index => $value) {
			
																		$fieldValueTMP = array();
																		$fieldCabecera = array();
			
																		$fieldUpdateCabecera = array();
			
																		if ($index == 'ca_datos_adicionales_cliente' || $index == 'ca_datos_adicionales_cuenta') {
																			if (count($value) > 0) {
			
																				for ($i = 0; $i < count($value); $i++) {
																					array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
																					array_push($fieldCabecera, $value[$i]['campoT']);
																					/****************/
																					array_push($fieldUpdateCabecera, " ".$value[$i]['campoT']." =  '".$value[$i]['label']."' ");
																				}
																						
																				$insertCabeceras = "";
																				if( trim($gestion) == '' ) {
																										
																					$insertCabeceras = "
																						UPDATE ca_cabeceras
																						SET
																						fecha_modificacion = CURRENT_TIMESTAMP, 
																						usuario_modificacion = $usuario_creacion , 
																						".implode(",",$fieldUpdateCabecera)."
																						WHERE idcartera = ".$id_cartera." 
																						AND idservicio = ".$_post['Servicio']." AND idtipo_datos_adicionales = ".$tipoDatosAdicionales[$index]."
																					";
																				}else{
																							
																					$insertCabeceras = "
																						UPDATE ca_cabeceras
																						SET
																						fecha_modificacion = CURRENT_TIMESTAMP,
																						usuario_modificacion = $usuario_creacion ,
																						".implode(",",$fieldUpdateCabecera)." 
																						WHERE idcartera IN ( SELECT DISTINCT idcartera FROM tmpcartera_" . session_id() . "_" . $time . "  )
																						AND idservicio = ".$_post['Servicio']." AND idtipo_datos_adicionales = ".$tipoDatosAdicionales[$index]."
																					";
																							
																				}
				
																				$prInsertCabeceras = $connection->prepare($insertCabeceras);
																				if ($prInsertCabeceras->execute()) {
				
																				} else {
				
																					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																					@$prsqlDropTableTMPCarteraRollBack->execute();
																					return array('rst' => false, 'msg' => 'Error al insertar cabeceras');
				
																				}
																			}
																		}
																	}
			
																	//$connection->commit();
																	return array('rst' => true, 'msg' => 'Cartera cargada correctamente');
																	//echo json_encode(array('rst'=>true,'msg'=>'Cartera cargada correctamente'));
																}
															} else {
																//$connection->rollBack();
			
																$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
																@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
																@$prsqlDropTableTMPCarteraRollBack->execute();
																return array('rst' => false, 'msg' => 'Error al agregar id cuenta a temporal');
															}
														} else {
															//$connection->rollBack();
			
															$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
															@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
															@$prsqlDropTableTMPCarteraRollBack->execute();
															return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
															//echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
														}
													
														
													
													}else{
														$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
														@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
														@$prsqlDropTableTMPCarteraRollBack->execute();
														return array('rst' => false, 'msg' => 'Error al crear regla para cuenta');
													}
													
												} else {
													//$connection->rollBack();
		
													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();
													return array('rst' => false, 'msg' => 'Error al agregar id distribucion a temporal');
												}
												
											} else {
												//$connection->rollBack();
		
												$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
												@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
												@$prsqlDropTableTMPCarteraRollBack->execute();
												return array('rst' => false, 'msg' => 'Error insertar datos de distribucion');
												//echo json_encode(array('rst'=>false,'msg'=>'Error insertar datos de distribucion'));
												//exit();	
											}
										
										}else{
											
											$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
											@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
											@$prsqlDropTableTMPCarteraRollBack->execute();
											return array('rst' => false, 'msg' => 'Error al crear regla para cliente cartera');
											
										}
										
									} else {
										//$connection->rollBack();
	
										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();
										return array('rst' => false, 'msg' => 'Error al insertar id cliente a temporal');
									}
								} else {
									//$connection->rollBack();
	
									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();
									return array('rst' => false, 'msg' => 'Error al insertar cliente');
									//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar cliente'));
								}
							/*} else {
	
								//$connection->rollBack();
	
								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();
	
								//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar data adicional a cartera'));
								return array('rst' => false, 'msg' => 'Error al actualizar temporal');
								//echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar temporal'));
							}*/
	
							/*                         * ********* */
	
	
						/* }else{
						  $sqlDropTableTMPCarteraRollBack=" DROP TABLE IF EXISTS tmpcartera_".session_id()."_".$time." ";
						  @$prsqlDropTableTMPCarteraRollBack=$connection->prepare($sqlDropTableTMPCarteraRollBack);
						  @$prsqlDropTableTMPCarteraRollBack->execute();
						  return array('rst'=>false,'msg'=>'Error al agregar id de gestion');
						  //echo json_encode(array('rst'=>false,'msg'=>'Error al agregar id de gestion'));
						  } */

						}else{
							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();
							return array('rst' => false, 'msg' => 'Error al eliminar registro de cabeceras de temporal');
						}
					
					}else{
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						return array('rst' => false, 'msg' => 'Error al agregar campos adicionales a temporal');
					}

				} else {
					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
					@$prsqlDropTableTMPCarteraRollBack->execute();
					return array('rst' => false, 'msg' => 'Error load data infile');
					//echo json_encode(array('rst'=>false,'msg'=>'Error load data infile'));
				}
			} else {
				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
				@$prsqlDropTableTMPCarteraRollBack->execute();
				return array('rst' => false, 'msg' => 'Error create temporary table');
				//echo json_encode(array('rst'=>false,'msg'=>'Error create temporary table'));
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al eliminar tabla');
			//echo json_encode(array('rst'=>false,'msg'=>'Error al eliminar tabla'));
		}



	}

	public function uploadUpdateCarteraPago($_post, $is_parser=0) {
		$file = $_post['file'];
		$separator = $_post['separator'];
		$servicio = $_post['Servicio'];
		$NombreServicio = $_post['NombreServicio'];
		$cartera = $_post['Cartera'];
		$campania = $_post['Campania'];
		$UsuarioCreacion = $_post['usuario_creacion'];
		$jsonPago = json_decode(str_replace("\\", "", $_post['data_pago']), true);

		//$codigo=$jsonPago['codigo_cliente'];
		$codigo = '';
//		$call_center=$jsonPago['call_center'];
		//$numero_cuenta=$jsonPago['numero_cuenta'];
		$numero_cuenta = '';
		//$operacion=$jsonPago['codigo_operacion'];
		$operacion = '';
		/*         * ****** */
		//$moneda = $jsonPago['moneda'];
		$moneda = '';
		/*         * ***** */
//		$moneda=$jsonPago['moneda'];
//		$monto=$jsonPago['monto'];
//		$fecha=$jsonPago['fecha'];
//		$observacion=$jsonPago['Observacion'];
		$call_center = '';
		$moneda = '';
		$monto = '';
		$fecha = '';
		$observacion = '';

//		if( trim($codigo)=='' ) {
//			echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de codigo cliente'));
//			exit();
//		}
		//if( trim($numero_cuenta)=='' ){
//			echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de cuenta'));
//			exit();
//		}

		for ($i = 0; $i < count($jsonPago); $i++) {
			if ($jsonPago[$i]['campoT'] == 'codigo_cliente') {
				$codigo = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'numero_cuenta') {
				$numero_cuenta = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'moneda') {
				$moneda = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'codigo_operacion') {
				$operacion = $jsonPago[$i]['dato'];
			}
		}

		if (trim($operacion) == '') {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione campo de operacion-factura'));
			exit();
		}
		//if( !isset($jsonPago['codigo_operacion']) ) {
//			echo json_encode(array('rst'=>false,'msg'=>'Seleccione campo de operacion-factura'));
//			exit();
//		}
//		if( !isset($jsonPago['codigo_cliente']) ) {
//			$codigo='';
//		}
//		if( !isset($jsonPago['numero_cuenta']) ) {
//			$numero_cuenta='';
//		}
//		if( !isset($jsonPago['moneda']) ) {
//			$moneda='';
//		}
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$path = "../documents/carteras/" . $NombreServicio . "/" . $file;
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		//$archivo = file($path);
		$archivo = @fopen($path, "r+");
		//$colum = explode($separator,$archivo[0]);
		$colum = explode($separator, fgets($archivo));
		if (count($colum) < 5) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				//$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		/* foreach( $jsonPago as $i => $v ) {
		  if( $i=='monto_pagado' ) {
		  $monto=$v;
		  }else if( $i=='moneda' ) {
		  $moneda=$v;
		  }else if( $i=='fecha' ) {
		  $fecha=$v;
		  }else if( $i=='call_center' ) {
		  $call_center=$v;
		  }else if( $i=='observacion' ) {
		  $observacion=$v;
		  }
		  } */

		for ($i = 0; $i < count($jsonPago); $i++) {
			if ($jsonPago[$i]['campoT'] == 'monto_pagado') {
				$monto = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'moneda') {
				$moneda = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'fecha') {
				$fecha = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'call_center') {
				$call_center = $jsonPago[$i]['dato'];
			} else if ($jsonPago[$i]['campoT'] == 'observacion') {
				$observacion = $jsonPago[$i]['dato'];
			}
		}

		/*         * ****** */
		fclose($archivo);
		/*         * ****** */

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTablePago = " DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ";
		$prDropTablePago = $connection->prepare($sqlDropTablePago);
		if ($prDropTablePago->execute()) {

			$createTablePago = " CREATE TEMPORARY TABLE tmppago_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";

			$prCreateTablePago = $connection->prepare($createTablePago);
			if ($prCreateTablePago->execute()) {
				$sqlLoadPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
				  INTO TABLE tmppago_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";

				$prLoadPago = $connection->prepare($sqlLoadPago);
				if ($prLoadPago->execute()) {

					//$connection->beginTransaction();
					//$selectCheckCodigoCliente=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($codigo)='' ";
//					$prselectCheckCodigoCliente=$connection->prepare($selectCheckCodigoCliente);
//					$resulCodigoClienteCheck=$prselectCheckCodigoCliente->fetchAll(PDO::FETCH_ASSOC);
//					if( $resulCodigoClienteCheck[0]['COUNT']>0 ){
//						//$connection->rollBack();
//						
//						@$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//						@$prDropTablePagoRollback->execute();
//						
//						echo json_encode(array('rst'=>false,'msg'=>'Codigo de cliente posee campos vacios'));
//						exit();
//					}
					//$selectCheckCodigoCuenta=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($numero_cuenta)='' ";
//					$prselectCheckCodigoCuenta=$connection->prepare($selectCheckCodigoCuenta);
//					$resultCuentaCheck=$prselectCheckCodigoCuenta->fetchAll(PDO::FETCH_ASSOC);
//					if( $resultCuentaCheck[0]['COUNT']>0 ) {
//						//$connection->rollBack();
//						
//						@$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//						@$prDropTablePagoRollback->execute();
//						
//						echo json_encode(array('rst'=>false,'msg'=>'Numero de cuenta posee campos vacios'));
//						exit();
//					}
					//$selectCheckCodigoOperacion=" SELECT COUNT(*) AS 'COUNT' FROM tmppago_".session_id()."_".$time." WHERE TRIM($operacion)='' ";
//					$prselectCheckCodigoOperacion=$connection->prepare($selectCheckCodigoOperacion);
//					$resultOperacionCheck=$prselectCheckCodigoOperacion->fetchAll(PDO::FETCH_ASSOC);
//					if( $resultOperacionCheck[0]['COUNT']>0 ) {
//						//$connection->rollBack();
//						
//						@$prDropTablePagoRollback=$connection->prepare(" DROP TABLE IF EXISTS tmppago_".session_id()."_".$time." ");
//						@$prDropTablePagoRollback->execute();
//						
//						echo json_encode(array('rst'=>false,'msg'=>'Codigo operacion posee campos vacios'));
//						exit();
//					}

					/*                     * ***** save parser ***** */
					$cabeceras = implode(",", $colum);
					$parserPago = str_replace("\\", "", $_post["data_pago"]);

					if ($is_parser == 1) {
//						$InsertJsonParserPago=" INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion ) 
//						VALUES ( ?,?,?,?,?,? ) ";

						$InsertJsonParserPago = " INSERT INTO ca_json_parser_pago ( idservicio, cabeceras, pago, codigo_cliente, numero_cuenta, codigo_operacion, moneda ) 
						VALUES ( ?,?,?,?,?,?,? ) ";

						$prInsertJsonParserPago = $connection->prepare($InsertJsonParserPago);
						$prInsertJsonParserPago->bindParam(1, $servicio);
						$prInsertJsonParserPago->bindParam(2, $cabeceras);
						$prInsertJsonParserPago->bindParam(3, $parserPago);
						$prInsertJsonParserPago->bindParam(4, $codigo);
						$prInsertJsonParserPago->bindParam(5, $numero_cuenta);
						$prInsertJsonParserPago->bindParam(6, $operacion);
						/*                         * **** */
						$prInsertJsonParserPago->bindParam(7, $moneda);
						/*                         * *** */
						if ($prInsertJsonParserPago->execute()) {

						} else {
							//$connection->rollBack();

							@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
							@$prDropTablePagoRollback->execute();

							echo json_encode(array('rst' => false, 'msg' => 'Error al guardar metadata'));
							exit();
						}
					}

					/*                     * ********** */

					$InsertCarteraPago = " INSERT INTO ca_cartera_pago( idcartera, tabla, cantidad, fecha_carga, archivo, usuario_creacion, fecha_creacion, codigo_cliente, numero_cuenta, moneda, codigo_operacion, pago, cabeceras ) 
					VALUES( " . $_post['Cartera'] . ",'tmppago_" . session_id() . "_" . $time . "'," . (count($archivo) - 1) . ",NOW(),'" . $file . "',$UsuarioCreacion, NOW(), '" . $codigo . "','" . $numero_cuenta . "','" . $moneda . "','" . $operacion . "','" . $parserPago . "','" . $cabeceras . "' )";

					$prInsertCarteraPago = $connection->prepare($InsertCarteraPago);
					if ($prInsertCarteraPago->execute()) {

						$idCarteraPago = $connection->lastInsertId();

						$SelectIdDetalleCuentaTMP = "";
						if (trim($call_center) == '') {
							$SelectIdDetalleCuentaTMP = " SELECT TRIM( $operacion ) AS 'operacion' FROM tmppago_" . session_id() . "_" . $time . " ";
						} else {
							$SelectIdDetalleCuentaTMP = " SELECT TRIM( $operacion ) AS 'operacion' FROM tmppago_" . session_id() . "_" . $time . " WHERE LOWER(TRIM($call_center))='hdec' ";
						}

						//function MapSelectIdDetalleCuentaTMP ( $n ) {
//								return "'".$n['operacion']."'";
//							}
//							
//							$prSelectIdDetalleCuentaTMP=$connection->prepare($SelectIdDetalleCuentaTMP);
//							$prSelectIdDetalleCuentaTMP->execute();
//							$ResultSelectIdDetalleCuentaTMP=$prSelectIdDetalleCuentaTMP->fetchAll(PDO::FETCH_ASSOC);
//							$MapResultSelectIdDetalleCuentaTMP=array_map("MapSelectIdDetalleCuentaTMP",$ResultSelectIdDetalleCuentaTMP);

						/*                         * ****** */

//							$SelectIdCarteraPago=" SELECT idcartera_pago FROM ca_cartera_pago WHERE idcartera = $cartera ";
//							$prSelectIdCarteraPago = $connection->prepare($SelectIdCarteraPago);
//							$prSelectIdCarteraPago->execute();
//							$ResultSelectIdCarteraPago=$prSelectIdCarteraPago->fetchAll(PDO::FETCH_ASSOC);
//							
//							function MapSelectIdCarteraPago ( $n ) {
//								return $n['idcartera_pago'];
//							}
//							
//							$MapResultSelectIdCarteraPago=array_map("MapSelectIdCarteraPago",$ResultSelectIdCarteraPago);
						//$UpdatePago=" UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion  
						//WHERE estado=1 AND idcartera_pago IN ( ".implode(",",$MapResultSelectIdCarteraPago)." ) AND codigo_operacion IN ( ".implode(",",$MapResultSelectIdDetalleCuentaTMP)." ) ";
//							$UpdatePago=" UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion  
//							WHERE estado=1 AND idcartera = $cartera AND codigo_operacion IN ( ".implode(",",$MapResultSelectIdDetalleCuentaTMP)." ) ";

						$UpdatePago = " UPDATE ca_pago SET estado = 0, fecha_modificacion = NOW(), usuario_modificacion = $UsuarioCreacion  
							WHERE estado=1 AND idcartera = $cartera AND codigo_operacion IN ( $SelectIdDetalleCuentaTMP ) ";

						$prUpdatePago = $connection->prepare($UpdatePago);
						if ($prUpdatePago->execute()) {

							//$fieldPago=array_intersect_key($jsonPago,array('monto'=>'','moneda'=>'','fecha'=>'','observacion'=>''));
							$campoPagoTMP = array();
							$campoPago = array();

							/* foreach($jsonPago as $index => $value ) {
							  if( $index=="codigo_cliente" ) {
							  array_push($campoPago,$index);
							  array_push($campoPagoTMP," TRIM( ".$value." ) ");
							  }else if( $index=="numero_cuenta" ) {
							  array_push($campoPago,$index);
							  array_push($campoPagoTMP," TRIM( ".$value." ) ");
							  }else if( $index=="codigo_operacion" ) {
							  array_push($campoPago,$index);
							  array_push($campoPagoTMP," TRIM( ".$value." ) ");
							  }else if( $index=="moneda" ) {
							  array_push($campoPago,$index);
							  array_push($campoPagoTMP," TRIM( ".$value." ) ");
							  }else if( $index=="call_center" ){

							  }else{
							  array_push($campoPago,$index);
							  array_push($campoPagoTMP,$value);
							  }
							  } */

							/*                             * *********************** */
							for ($i = 0; $i < count($jsonPago); $i++) {
								if ($jsonPago[$i]['campoT'] == "codigo_cliente") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
								} else if ($jsonPago[$i]['campoT'] == "numero_cuenta") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
								} else if ($jsonPago[$i]['campoT'] == "codigo_operacion") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
								} else if ($jsonPago[$i]['campoT'] == "moneda") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " TRIM( " . $jsonPago[$i]['dato'] . " ) ");
								} else if ($jsonPago[$i]['campoT'] == "call_center") {

								} else if ($jsonPago[$i]['campoT'] == "estado_cruce") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " ( SELECT descripcion FROM ca_estado_pago_cruce WHERE idservicio = $servicio AND nombre = TRIM( " . $jsonPago[$i]['dato'] . " ) ) ");
								} else if ($jsonPago[$i]['campoT'] == "fecha") {
									//array_push($campoTableOperacionTMP," IF( LOCATE('/', ".$jsonOperacion[$i]['dato']." ) = 3,  CONCAT_WS('-',SUBSTRING( ".$jsonOperacion[$i]['dato']." ,7),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,4,2),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,1,2)) , IF( LOCATE('/', ".$jsonOperacion[$i]['dato']." ) = 5, CONCAT_WS('-',SUBSTRING( ".$jsonOperacion[$i]['dato']." ,1,4),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,6,2),SUBSTRING( ".$jsonOperacion[$i]['dato']." ,9,2)) , ".$jsonOperacion[$i]['dato']." ) ) ");
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,7),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,9,2)) , " . $jsonPago[$i]['dato'] . " ) ) ");
								} else if ($jsonPago[$i]['campoT'] == "fecha_envio") {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, " IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,7),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonPago[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonPago[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonPago[$i]['dato'] . " ,9,2)) , " . $jsonPago[$i]['dato'] . " ) ) ");
								} else {
									array_push($campoPago, $jsonPago[$i]['campoT']);
									array_push($campoPagoTMP, $jsonPago[$i]['dato']);
								}
							}
							/*                             * *********************** */

							$sqlInsertPago = "";

							if (trim($call_center) == '') {

//									$sqlInsertPago=" INSERT IGNORE INTO ca_pago ( idcartera_pago, idcartera , usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." ) 
//										SELECT $idCarteraPago, $cartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."  
//										FROM tmppago_".session_id()."_".$time." ";

								$sqlInsertPago = " INSERT IGNORE INTO ca_pago ( idcartera_pago, is_act, idcartera , usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " ) 
										SELECT $idCarteraPago, 1, $cartera , $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "  
										FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $operacion ) ) > 0 ";
							} else {

//									$sqlInsertPago=" INSERT IGNORE INTO ca_pago ( idcartera_pago, idcartera , usuario_creacion, fecha_creacion, ".implode(",",$campoPago)." ) 
//										SELECT $idCarteraPago, $cartera , $UsuarioCreacion, NOW(), ".implode(",",$campoPagoTMP)."  
//										FROM tmppago_".session_id()."_".$time." WHERE LOWER(TRIM($call_center))='hdec' ";

								$sqlInsertPago = " INSERT IGNORE INTO ca_pago ( idcartera_pago, is_act, idcartera , usuario_creacion, fecha_creacion, " . implode(",", $campoPago) . " ) 
										SELECT $idCarteraPago, 1, $cartera , $UsuarioCreacion, NOW(), " . implode(",", $campoPagoTMP) . "  
										FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $operacion ) ) > 0 AND LOWER(TRIM($call_center))='hdec' ";
							}

							$prInsertPago = $connection->prepare($sqlInsertPago);
							if ($prInsertPago->execute()) {

								/* if( trim($monto)=='' ) {
								  //$connection->rollBack();
								  echo json_encode(array('rst'=>false,'msg'=>'Seleccione Monto Pagado para actualizar cuenta'));
								  }else{ */

								if (trim($call_center) == '') {
									//$connection->commit();
									echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
								} else {

									if (trim($monto) == '') {
										//$connection->commit();
										echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
									} else {

										$sqlRankinPago = " INSERT INTO ca_ranking_pago ( call_center, monto, idcartera_pago, fecha_creacion, usuario_creacion ) 
													SELECT " . $call_center . ", SUM( " . $monto . " ), $idCarteraPago, NOW(), $UsuarioCreacion 
													FROM tmppago_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $call_center ) ) > 0 GROUP BY LOWER( TRIM( " . $call_center . " ) ) ";

										$prSqlRankinPago = $connection->prepare($sqlRankinPago);
										if ($prSqlRankinPago->execute()) {
											//$connection->commit();
											echo json_encode(array('rst' => true, 'msg' => 'Cartera de pago cargadas correctamente'));
										} else {
											$c1onnection->rollBack();
											@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
											@$prDropTablePagoRollback->execute();
											echo json_encode(array('rst' => false, 'msg' => 'Error agregar datos de ranking de pago'));
										}
									}
								}

								//}
							} else {
								//$connection->rollBack();
								@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
								@$prDropTablePagoRollback->execute();
								echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de pago'));
							}
						} else {
							//$connection->rollBack();
							@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
							@$prDropTablePagoRollback->execute();
							echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos a historial'));
						}
					} else {
						//$connection->rollBack();
						@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
						@$prDropTablePagoRollback->execute();
						echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de temporal'));
						exit();
					}
				} else {
					@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
					@$prDropTablePagoRollback->execute();
					echo json_encode(array('rst' => false, 'msg' => 'Error al cargar datos de pago'));
				}
			} else {
				@$prDropTablePagoRollback = $connection->prepare(" DROP TABLE IF EXISTS tmppago_" . session_id() . "_" . $time . " ");
				@$prDropTablePagoRollback->execute();
				echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
		}
	}

	public function loadHeaderCentroPago($_post) {
		if (@opendir('../documents/centro_pago/' . $_post['NombreServicio'])) {
			if (@file_exists('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file'])) {

				$dataFile = file('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file']);

				/*                 * *** */
				//$archivo = file($path);	

				$tmpArchivo = fopen('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file'], 'w');
				fwrite($tmpArchivo, '');
				fclose($tmpArchivo);

				$countHeader = 0;

				$tmpArchivo = fopen($path, 'a+');

				for ($i = 0; $i < count($dataFile); $i++) {
					if ($i == 0) {
						//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"');
						//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'');
						$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
						$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
						$line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
						$explode_header = explode($_post['separator'], $line);
						for ($j = 0; $j < count($explode_header); $j++) {
							if ($explode_header[$j] == '') {
								fclose($tmpArchivo);
								unlink($path);
								echo json_encode(array('rst' => false, 'msg' => 'Existen cabeceras vacias'));
								exit();
							}
						}
						$countHeader = count($explode_header);
						fwrite($tmpArchivo, $line);
					} else {
						$buscar = array('"', "'", "#", "&");
						$cambia = array('', "", "", "");
						//$line=str_replace("	","|",$archivo[$i]);
						$line = str_replace($buscar, $cambia, trim(utf8_encode($archivo[$i])));
						$explode_line = explode($_post['separator'], $line);
						if (count($explode_line) != $countHeader) {
							fclose($tmpArchivo);
							unlink($path);
							echo json_encode(array('rst' => false, 'msg' => 'Linea ' . ($i + 1) . ' no coincide con longitud de cabeceras'));
							exit();
						}
						fwrite($tmpArchivo, $line);
					}
				}

				fclose($tmpArchivo);
				//echo json_encode(array('rst'=>true,'msg'=>'Cartera limpiada correctamente'));

				/*                 * *** */
				$archivo = file('../documents/centro_pago/' . $_post['NombreServicio'] . '/' . $_post['file']);
				$dataHeader = explode($_post['separator'], $archivo[0]);
				//$dataHeaderMap=array_map("MapArrayHeader",$dataHeader);
				echo json_encode(array('rst' => true, 'msg' => 'Cabeceras cargadas correctamente', 'header' => $dataHeader));
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al leer archivo'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error en directorio'));
		}
	}

	public function uploadCentroPago($_post) {

		$file = $_post['file'];
		$separator = $_post['separator'];
		$servicio = $_post['Servicio'];
		$NombreServicio = $_post['NombreServicio'];
		$UsuarioCreacion = $_post['usuario_creacion'];
		$nombre = $_post['Nombre'];
		$jsonCentroPago = json_decode(str_replace("\\", "", $_post['data_centro_pago']), true);

		if (trim($nombre) == '') {
			echo json_encode(array('rst' => false, 'msg' => 'Ingrese nombre de archivo de centro de pago'));
			exit();
		}
		if (count($jsonCentroPago) == 0) {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione campos a cargar'));
			exit();
		}

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
		$path = "../documents/carteras/" . $NombreServicio . "/" . $file;

		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = file($path);
		$colum = explode($separator, $archivo[0]);
		if (count($colum) < 5) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				//$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				$item = "`" . $item . "` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, $colum[$i]);
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTableCentroPago = " CREATE TABLE tmpcentro_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
		//echo $sqlCreateTableCentroPago;
		$prCreateTableCentroPago = $connection->prepare($sqlCreateTableCentroPago);
		if ($prCreateTableCentroPago->execute()) {

			$sqlLoadCentroPago = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $NombreServicio . "/" . $file . "'
				  INTO TABLE tmpcentro_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";

			$prLoadCentroPago = $connection->prepare($sqlLoadCentroPago);
			if ($prLoadCentroPago->execute()) {

				//$connection->beginTransaction();

				$sqlInsertDataFileCentroPago = " INSERT INTO ca_file_centro_pago (nombre,idservicio,fecha_carga,fecha_creacion,usuario_creacion) 
				VALUES('" . $nombre . "',$servicio,NOW(),NOW(),$UsuarioCreacion) ";
				$prInsertDataFileCentroPago = $connection->prepare($sqlInsertDataFileCentroPago);
				if ($prInsertDataFileCentroPago->execute()) {

					$id_centro_pago = $connection->lastInsertId();

					$fieldCentroPago = array();
					$fieldCentroPagoTMP = array();

					foreach ($jsonCentroPago as $index => $value) {
						array_push($fieldCentroPago, $index);
						array_push($fieldCentroPagoTMP, $value);
					}

					$sqlInsertDataCentroPago = " INSERT INTO ca_centro_pago (idfile_centro_pago," . implode(",", $fieldCentroPago) . ",fecha_creacion,usuario_creacion) 
					SELECT $id_centro_pago," . implode(",", $fieldCentroPagoTMP) . ",NOW(),$UsuarioCreacion FROM tmpcentro_" . session_id() . "_" . $time . " ";
					$prInsertDataCentroPago = $connection->prepare($sqlInsertDataCentroPago);
					if ($prInsertDataCentroPago->execute()) {
						//$connection->commit();

						$sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
						@$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
						@$prDropTableCentroPago->execute();

						echo json_encode(array('rst' => true, 'msg' => 'Centros de pagos cargados correctamente'));
					} else {

						//$connection->rollBack();

						$sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
						@$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
						@$prDropTableCentroPago->execute();

						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar centros de pago'));
					}
				} else {
					//$connection->rollBack();

					$sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
					@$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
					@$prDropTableCentroPago->execute();

					echo json_encode(array('rst' => false, 'msg' => 'Error al insertar informacion de archivo'));
				}
			} else {
				$sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
				@$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
				@$prDropTableCentroPago->execute();

				echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a temporal'));
			}
		} else {
			$sqlDropTableCentroPago = " DROP TABLE IF EXISTS tmpcentro_" . session_id() . "_" . $time . " ";
			@$prDropTableCentroPago = $connection->prepare($sqlDropTableCentroPago);
			@$prDropTableCentroPago->execute();

			echo json_encode(array('rst' => false, 'msg' => 'Error al crear temporal'));
		}
	}

	public function uploadCargaAutomatica($_post) {

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = file($path);
		$colum = explode($_post['separator'], $archivo[0]);

		function map_header_automatic($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header_automatic", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, $colum[$i]);
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlJsonParse = " SELECT idjson_parser, cabeceras, cliente, cuenta, detalle_cuenta, telefono, 
		direccion,adicionales,codigo_cliente,numero_cuenta, codigo_operacion, separador  
		FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser ";

		$prJsonParser = $connection->prepare($sqlJsonParse);
		$prJsonParser->bindParam(1, $_post['Servicio']);
		$prJsonParser->execute();
		$ResultJsonParse = $prJsonParser->fetchAll(PDO::FETCH_ASSOC);
		//print_r($colum);
		//exit();
		$index = -1;

		for ($i = 0; $i < count($ResultJsonParse); $i++) {
			$countCheck = 0;
			$cabeceras = explode(",", $ResultJsonParse[$i]['cabeceras']);
			for ($j = 0; $j < count($colum); $j++) {
				if (in_array($colum[$j], $cabeceras)) {
					$countCheck++;
				}
			}

			if ($countCheck == count($colum)) {
				$index = $i;
				break;
			}
		}

		/*         * **** ULTIMO PARSEO **** */

		//for( $i=0;$i<count($ResultJsonParse);$i++ ) {
//			$countCheck=0;
//			$cabeceras=explode(",",$ResultJsonParse[$i]['cabeceras']);
//			for( $j=0;$j<count($colum);$j++ ) {
//				if( in_array($colum[$j],$cabeceras) ) {
//					$countCheck++;
//				}
//			}
//			
//			if( $countCheck==count($colum) ) {
//				$index=$i;
//				break;
//			}
//			
//		}

		/*         * **************** */

		if ($index == -1) {
			echo json_encode(array('rst' => false, 'msg' => 'Cabeceras no coinciden con ninguna de las plantillas, realize carga manual'));
			exit();
		}

		$postPlantilla = $_post;
		$postPlantilla['data_cliente'] = $ResultJsonParse[$index]['cliente'];
		$postPlantilla['data_cuenta'] = $ResultJsonParse[$index]['cuenta'];
		$postPlantilla['data_operacion'] = $ResultJsonParse[$index]['detalle_cuenta'];
		$postPlantilla['data_telefono'] = $ResultJsonParse[$index]['telefono'];
		$postPlantilla['data_direccion'] = $ResultJsonParse[$index]['direccion'];
		$postPlantilla['data_adicionales'] = $ResultJsonParse[$index]['adicionales'];

		$postPlantilla['codigo_cliente'] = $ResultJsonParse[$index]['codigo_cliente'];
		$postPlantilla['numero_cuenta'] = $ResultJsonParse[$index]['numero_cuenta'];
		$postPlantilla['codigo_operacion'] = $ResultJsonParse[$index]['codigo_operacion'];
		//$postPlantilla['separator']=$ResultJsonParse[$index]['saparador']; 

		if ($_post['Proceso'] == 'carga') {
			$this->uploadCartera($postPlantilla, 0);
		} else if ($_post['Proceso'] == 'actualizacion') {
			$this->uploadUpdateCartera($postPlantilla, 0);
		}
	}

	public function uploadCargaAutomaticaPago($_post) {

		$file = $_post['file'];
		$separator = $_post['separator'];
		$servicio = $_post['Servicio'];
		$NombreServicio = $_post['NombreServicio'];
		$campania = $_post['Campania'];
		$cartera = $_post['Cartera'];
		$UsuarioCreacion = $_post['usuario_creacion'];

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		$path = "../documents/carteras/" . $_post['NombreServicio'] . "/" . $_post['file'];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = file($path);
		$colum = explode($_post['separator'], $archivo[0]);
		if (count($colum) < 5) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header_automatic_pay($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header_automatic_pay", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, $colum[$i]);
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$sqlPagoParser = " SELECT idjson_parser_pago, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion, pago 
		FROM ca_json_parser_pago WHERE idservicio = ? ";

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$prJsonParserPago = $connection->prepare($sqlPagoParser);
		$prJsonParserPago->bindParam(1, $_post['Servicio']);
		$prJsonParserPago->execute();
		$ResultJsonParserPago = $prJsonParserPago->fetchAll(PDO::FETCH_ASSOC);

		$index = -1;

		for ($i = 0; $i < count($ResultJsonParserPago); $i++) {
			$countCheck = 0;
			$cabeceras = explode(",", $ResultJsonParserPago[$i]['cabeceras']);
			for ($j = 0; $j < count($colum); $j++) {
				if (in_array($colum[$j], $cabeceras)) {
					$countCheck++;
				}
			}

			if ($countCheck == count($colum)) {
				$index = $i;
				break;
			}
		}

		if ($index == -1) {
			echo json_encode(array('rst' => false, 'msg' => 'Cabeceras no coinciden con ninguna de las plantillas, realize carga manual'));
			exit();
		}

		$postPlantilla = $_post;
		$postPlantilla['data_pago'] = $ResultJsonParserPago[$index]['pago'];

		$postPlantilla['codigo_cliente'] = $ResultJsonParserPago[$index]['codigo_cliente'];
		$postPlantilla['numero_cuenta'] = $ResultJsonParserPago[$index]['numero_cuenta'];
		$postPlantilla['codigo_operacion'] = $ResultJsonParserPago[$index]['codigo_operacion'];

		if ($_post['Proceso'] == 'carga') {
			$this->uploadCarteraPago($postPlantilla, 0);
		} else if ($_post['Proceso'] == 'actualizacion') {
			$this->uploadUpdateCarteraPago($postPlantilla, 0);
		}
	}

	/*     * ************* */

	public function uploadCarteraPlanta($_post, $is_parser=0) {
		//print_r($_post);
		//exit();
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		$codigo = $_post["codigo"];
		$usuario_creacion = $_post["usuario_creacion"];
		$nombre_cartera = $_POST["NombreCartera"];
		$id_cartera = 0;


		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}


		$time = date("Y_m_d_H_i_s");
		$archivoParser = file($path);
		$columMap = explode($_post['separator'], $archivoParser[0]);

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header", $columMap);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		//array_push($columHeader,"`idcliente` INT ");
		//array_push($columHeader,"`idcuenta` INT ");
		//array_push($columHeader,"`iddetalle_cuenta` INT ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo` ASC ) ");

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$parserPlanta = str_replace("\\", "", $_post["data_planta"]);
		$parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

		$parserHeader = implode(",", $colum);

		$jsonPlanta = json_decode(str_replace("\\", "", $_post["data_planta"]), true);
		$jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		if ($prDropTableTMPCartera->execute()) {

			$sqlCreateTabelTMPCartera = " CREATE TABLE tmpplanta_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
			$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
			if ($prsqlCreateTabelTMPCartera->execute()) {

				$sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				 INTO TABLE tmpplanta_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
				if ($prLoadDataInFileUC->execute()) {

					//$connection->beginTransaction();

					$selectCodigoClienteCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpplanta_" . session_id() . "_" . $time . " WHERE TRIM($codigo)='' ";
					$prselectCodigoClienteCheck = $connection->prepare($selectCodigoClienteCheck);
					$resultselectCodigoClienteCheck = $prselectCodigoClienteCheck->fetchAll(PDO::FETCH_ASSOC);
					if ($resultselectCodigoClienteCheck[0]['COUNT'] > 0) {
						//$connection->rollBack();
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						echo json_encode(array('rst' => false, 'msg' => 'Codigo posee campos vacios'));
						exit();
					}

					$insertCartera = " INSERT INTO ca_cartera_planta( nombre,idcampania, cantidad_registros,fecha_carga,archivo,tabla,usuario_creacion,fecha_creacion, cabeceras, codigo ) 
								VALUES ( '" . $nombre_cartera . "'," . $_post['Campania'] . " ," . (count($archivoParser) - 1) . ",NOW(),'" . utf8_encode($_post["file"]) . "','tmpplanta_" . session_id() . "_" . $time . "'," . $_post['usuario_creacion'] . ",NOW() ,'" . $parserHeader . "','" . $codigo . "' ) ";
					$prInsertCartera = $connection->prepare($insertCartera);
					if ($prInsertCartera->execute()) {

						$id_cartera = $connection->lastInsertId();

						/*                         * *********** */
						if ($is_parser == 1) {

							$insertJsonParser = " INSERT INTO ca_json_parser_planta ( idservicio, usuario_creacion, fecha_creacion, cabeceras, planta, adicionales, codigo ) 
										VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserPlanta . "','" . $parserAdicionales . "', '" . $codigo . "' ) ";
							$prInsertJsonParser = $connection->prepare($insertJsonParser);
							if ($prInsertJsonParser->execute()) {

							} else {
								//$connection->rollBack(); 
								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();
								echo json_encode(array('rst' => false, 'msg' => 'Error al insertar metadata'));
								exit();
							}
						}
						/*                         * ************ */
						$insertPlanta = " ";

						$campoTablePlantaTMP = array();
						$campoTablePlanta = array();

						for ($i = 0; $i < count($jsonPlanta); $i++) {
							array_push($campoTablePlanta, $jsonPlanta[$i]['campoT']);
							array_push($campoTablePlantaTMP, " TRIM(" . $jsonPlanta[$i]['dato'] . ")");
						}

						$insertPlanta = " INSERT IGNORE INTO ca_planta ( idcartera_planta, usuario_creacion, fecha_creacion, " . implode(",", $campoTablePlanta) . " ) 
									SELECT $id_cartera , $usuario_creacion, NOW() , " . implode(",", $campoTablePlantaTMP) . " FROM tmpplanta_" . session_id() . "_" . $time . " ";

						$prInsertCliente = $connection->prepare($insertPlanta);
						if ($prInsertCliente->execute()) {

							$fieldCabecera = array();
							$fieldCabeceraTMP = array();
							$fieldValueTMP = array();

							foreach ($jsonAdicionales as $index => $value) {

								array_push($fieldCabecera, $index);
								array_push($fieldValueTMP, "'" . $value . "'");
								array_push($fieldCabeceraTMP, $value);
							}

							$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras_adicionales ( idcartera, is_planta, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
											  VALUES( $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
							$prInsertCabeceras = $connection->prepare($insertCabeceras);
							if ($prInsertCabeceras->execute()) {

								$insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_planta ( idcartera_planta, codigo, usuario_creacion, fecha_creacion, " . implode(",", $fieldCabecera) . " ) 
												  SELECT $id_cartera, TRIM($codigo),$usuario_creacion, NOW(), " . implode(",", $fieldCabeceraTMP) . "
												  FROM tmpplanta_" . session_id() . "_" . $time . " ";

								$prInsertAdicionales = $connection->prepare($insertAdicionales);
								if ($prInsertAdicionales->execute()) {
									//$connection->commit();

									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();

									echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
								} else {
									//$connection->rollBack();

									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();

									echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
									exit();
								}
							} else {
								//$connection->rollBack();

								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();

								echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
								exit();
							}
						} else {
							//$connection->rollBack();

							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();

							echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
						}
					} else {
						//$connection->rollBack();

						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();

						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cartera'));
					}
				} else {
					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
					@$prsqlDropTableTMPCarteraRollBack->execute();
					echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
				}
			} else {
				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpplanta_" . session_id() . "_" . $time . " ";
				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
				@$prsqlDropTableTMPCarteraRollBack->execute();
				echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
		}
	}

	/*     * ************* */

	/*     * *********** */

	public function uploadCartera2($_post, $is_parser=0) {

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		$codigo_cliente = $_post["codigo_cliente"];
		$numero_cuenta = $_post["numero_cuenta"];
		$codigo_operacion = $_post["codigo_operacion"];
		$usuario_creacion = $_post["usuario_creacion"];
		$nombre_cartera = $_POST["NombreCartera"];
		$id_cartera = 0;


		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}


		$time = date("Y_m_d_H_i_s");
		$archivoParser = file($path);
		$columMap = explode($_post['separator'], $archivoParser[0]);

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header", $columMap);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		//array_push($columHeader,"`idcliente` INT ");
		//array_push($columHeader,"`idcuenta` INT ");
		//array_push($columHeader,"`iddetalle_cuenta` INT ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$parserCliente = str_replace("\\", "", $_post["data_cliente"]);
		$parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
		$parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
		$parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
		$parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
		$parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

		$parserHeader = implode(",", $colum);

		$jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
		$jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
		$jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
		$jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
		$jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
		$jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		if ($prDropTableTMPCartera->execute()) {

			$sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
			$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
			if ($prsqlCreateTabelTMPCartera->execute()) {

				$sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
				if ($prLoadDataInFileUC->execute()) {

					//$connection->beginTransaction();

					$selectCodigoClienteCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($codigo_cliente)='' ";
					$prselectCodigoClienteCheck = $connection->prepare($selectCodigoClienteCheck);
					$resultselectCodigoClienteCheck = $prselectCodigoClienteCheck->fetchAll(PDO::FETCH_ASSOC);
					if ($resultselectCodigoClienteCheck[0]['COUNT'] > 0) {
						//$connection->rollBack();
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						echo json_encode(array('rst' => false, 'msg' => 'Codigo de cliente posee campos vacios'));
						exit();
					}

					$selectNumeroCuentaCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($numero_cuenta)='' ";
					$prselectNumeroCuentaCheck = $connection->prepare($selectNumeroCuentaCheck);
					$resultselectNumeroCuentaCheck = $prselectNumeroCuentaCheck->fetchAll(PDO::FETCH_ASSOC);
					if ($resultselectNumeroCuentaCheck[0]['COUNT'] > 0) {
						//$connection->rollBack();
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						echo json_encode(array('rst' => false, 'msg' => 'Codigo cuenta posee campos vacios'));
						exit();
					}

					$selectCodigoOperacionCheck = " SELECT COUNT(*) AS 'COUNT' FROM tmpcartera_" . session_id() . "_" . $time . " WHERE TRIM($codigo_operacion)='' ";
					$prselectCodigoOperacionCheck = $connection->prepare($selectCodigoOperacionCheck);
					$resultselectCodigoOperacionCheck = $prselectCodigoOperacionCheck->fetchAll(PDO::FETCH_ASSOC);
					if ($resultselectCodigoOperacionCheck[0]['COUNT'] > 0) {
						//$connection->rollBack();
						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();
						echo json_encode(array('rst' => false, 'msg' => 'Codigo operacion posee campos vacios'));
						exit();
					}


					$insertCartera = " INSERT INTO ca_cartera( nombre_cartera,idcampania,fecha_carga,cantidad,tabla,archivo,usuario_creacion,fecha_creacion, cabeceras, codigo_cliente, numero_cuenta, codigo_operacion ) 
								VALUES ( '" . $nombre_cartera . "'," . $_post['Campania'] . ",NOW()," . (count($archivoParser) - 1) . ",'tmpcartera_" . session_id() . "_" . $time . "','" . utf8_encode($_post["file"]) . "'," . $_post['usuario_creacion'] . ",NOW() ,'" . $parserHeader . "','" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "' ) ";
					$prInsertCartera = $connection->prepare($insertCartera);
					if ($prInsertCartera->execute()) {

						$id_cartera = $connection->lastInsertId();

						/*                         * *********** */
						if ($is_parser == 1) {

							$insertJsonParser = " INSERT INTO ca_json_parser ( idservicio, usuario_creacion, fecha_creacion,cabeceras, cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, codigo_cliente, numero_cuenta, codigo_operacion, separador ) 
										VALUES ( " . $_post['Servicio'] . "," . $usuario_creacion . ",NOW(),'" . $parserHeader . "','" . $parserCliente . "','" . $parserCuenta . "','" . $parserOperacion . "','" . $parserTelefono . "','" . $parserDireccion . "','" . $parserAdicionales . "', '" . $codigo_cliente . "','" . $numero_cuenta . "','" . $codigo_operacion . "','" . $_post['separator'] . "' ) ";
							$prInsertJsonParser = $connection->prepare($insertJsonParser);
							if ($prInsertJsonParser->execute()) {

							} else {
								//$connection->rollBack(); 
								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();
								echo json_encode(array('rst' => false, 'msg' => 'Error al insertar metadata'));
								exit();
							}
						}
						/*                         * ************ */
						$insertCliente = " ";

						$campoTableClienteTMP = array();
						$campoTableCliente = array();

						for ($i = 0; $i < count($jsonCliente); $i++) {
							if ($jsonCliente[$i]['campoT'] == 'codigo') {
								array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
								array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
							} else {
								array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
								array_push($campoTableClienteTMP, $jsonCliente[$i]['dato']);
							}
						}

						$insertCliente = " INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " ) 
									SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY TRIM($codigo_cliente) ";

						$prInsertCliente = $connection->prepare($insertCliente);
						if ($prInsertCliente->execute()) {

							$InsertClienteCartera = " INSERT IGNORE INTO ca_cliente_cartera ( codigo_cliente,idcartera,usuario_creacion,fecha_creacion ) 
										SELECT TRIM($codigo_cliente)," . $id_cartera . "," . $usuario_creacion . ",NOW() FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY TRIM($codigo_cliente) ";

							$prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
							if ($prInsertClienteCartera->execute()) {

								$campoTableCuentaTMP = array();
								$campoTableCuenta = array();

								foreach ($jsonCuenta as $index => $value) {
									if ($index == "total_deuda") {
										array_push($campoTableCuenta, $index);
										array_push($campoTableCuentaTMP, " SUM( " . $value . " ) ");
									} else if ($index == "numero_cuenta") {
										array_push($campoTableCuenta, $index);
										array_push($campoTableCuentaTMP, " TRIM( " . $value . " )");
									} else {
										array_push($campoTableCuenta, $index);
										array_push($campoTableCuentaTMP, $value);
									}
								}

								$insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )  
											SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . " 
											FROM tmpcartera_" . session_id() . "_" . $time . " 
											GROUP BY TRIM($codigo_cliente),TRIM($numero_cuenta) ORDER BY TRIM($codigo_cliente) ";

								$prInsertCuenta = $connection->prepare($insertCuenta);
								if ($prInsertCuenta->execute()) {

									if (count($jsonOperacion) > 0) {
										$campoTableOperacionTMP = array();
										$campoTableOperacion = array();

										//$fieldTramo="";

										for ($i = 0; $i < count($jsonOperacion); $i++) {

											if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
												array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
												array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
											} else {
												array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
												array_push($campoTableOperacionTMP, $jsonOperacion[$i]['dato']);
											}
										}

										//$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$campoTableOperacion).", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion ) 
										//SELECT ".implode(",",$campoTableOperacionTMP).", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW()  
										//FROM tmpcartera_".session_id()."_".$time." GROUP BY TRIM( $codigo_cliente ), TRIM( $numero_cuenta), TRIM( $codigo_operacion ) ";	

										$insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion ) 
													SELECT " . implode(",", $campoTableOperacionTMP) . ", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW()  
													FROM tmpcartera_" . session_id() . "_" . $time . " ";

										$prInsertOperacion = $connection->prepare($insertOperacion);
										if ($prInsertOperacion->execute()) {


											$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
											//$idDatosAdicionales=array("ca_datos_adicionales_cliente"=>"idcliente","ca_datos_adicionales_cuenta"=>"idcuenta","ca_datos_adicionales_detalle_cuenta"=>"iddetalle_cuenta");
											$idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "numero_cuenta", "ca_datos_adicionales_detalle_cuenta" => "codigo_operacion");
											$idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");

											foreach ($jsonAdicionales as $index => $value) {
												$fieldCabecera = array();
												$fieldCabeceraTMP = array();
												$fieldValueTMP = array();

												if (count($value) > 0) {

													foreach ($value as $i => $v) {
														array_push($fieldCabecera, $i);
														array_push($fieldValueTMP, "'" . $v . "'");
														array_push($fieldCabeceraTMP, $v);
													}

													$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
																	VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
													$prInsertCabeceras = $connection->prepare($insertCabeceras);
													if ($prInsertCabeceras->execute()) {

														$insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " ) 
																		SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
																		FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $idTMPDatosAdicionales[$index] . "";

														$prInsertAdicionales = $connection->prepare($insertAdicionales);
														if ($prInsertAdicionales->execute()) {

														} else {
															//$connection->rollBack();

															$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
															@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
															@$prsqlDropTableTMPCarteraRollBack->execute();

															echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
															exit();
														}
													} else {
														//$connection->rollBack();

														$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
														@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
														@$prsqlDropTableTMPCarteraRollBack->execute();

														echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
														exit();
													}
												}
											}

											//$connection->commit();
											echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
										} else {
											//$connection->rollBack();

											$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
											@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
											@$prsqlDropTableTMPCarteraRollBack->execute();

											echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
										}
									} else {
										//$connection->rollBack();

										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();

										echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
									}
								} else {
									//$connection->rollBack();

									$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
									@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
									@$prsqlDropTableTMPCarteraRollBack->execute();

									echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
								}
							} else {
								//$connection->rollBack();

								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();

								echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
								exit();
							}
						} else {
							//$connection->rollBack();

							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();

							echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
						}
					} else {
						//$connection->rollBack();

						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();

						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cartera'));
					}
				} else {
					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
					@$prsqlDropTableTMPCarteraRollBack->execute();
					echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
				}
			} else {
				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
				@$prsqlDropTableTMPCarteraRollBack->execute();
				echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
		}
	}

	public function uploadCarteraTelefono($_post) {

		$cartera = $_post['Cartera'];
		//$campania = $_post['Campania'];
		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];
		$idorigen = ($_post['origen'] == '0') ? 1 : $_post['origen'];
		$idtipo = ($_post['tipo'] == '0') ? 2 : $_post['tipo'];

		$parserTelefono = str_replace("\\", "", $_post['data_telefono']);
		$jsonTelefono = json_decode($parserTelefono, true);
		//print_r($jsonTelefono);
		/*         * *********** */
		$codigo_cliente = @$jsonTelefono["codigo_cliente"];
		$numero_cuenta = @$jsonTelefono["numero_cuenta"];
		$codigo_operacion = @$jsonTelefono["codigo_operacion"];
		/*         * *********** */

		if (trim($jsonTelefono["codigo_cliente"]) == '' || trim($jsonTelefono["codigo_cliente"]) == '0') {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione codigo de cliente'));
			exit();
		}

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = @fopen($path, "r+");
		$colum = array();
		if ($separator == 'tab') {
			$colum = explode("\t", fgets($archivo));
		} else {
			$colum = explode($separator, fgets($archivo));
		}
		if (count($colum) < 2) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				$buscar = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", ".", "#", " ", "/", "�", "�", "@", "(", ")", "$", "&", "%", "'", '"', "?", "�", "!", "�", "[", "]", "-", "�");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$parserHeader = implode(",", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		fclose($archivo);
		/*         * *************** */
		array_push($columHeader, "`idcartera` INT ");
		array_push($columHeader, "`tmp_codigo_cliente` INT ");
		array_push($columHeader, "`idcliente_cartera` INT ");
		array_push($columHeader, "`idcuenta` INT ");
		/*         * *************** */
		if ($codigo_cliente != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_codigo_cliente` ( `" . $codigo_cliente . "` ASC ) ");
		}
		if ($numero_cuenta != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_numero_cuenta` ( `" . $numero_cuenta . "` ASC ) ");
		}
		if ($codigo_operacion != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_codigo_operacion` ( `" . $codigo_operacion . "` ASC ) ");
		}
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcartera` ( `idcartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcliente_cartera` ( `idcliente_cartera` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_tmp_codigo_cliente` ( `tmp_codigo_cliente` ASC ) ");
		/*         * ***************** */

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTable = " CREATE TEMPORARY TABLE tmptelefono_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
		//echo $sqlCreateTable;
		$prCreateTable = $connection->prepare($sqlCreateTable);
		if ($prCreateTable->execute()) {
			$sqlLoad = "";
			if ($separator == 'tab') {
				$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				  INTO TABLE tmptelefono_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			} else {
				$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				  INTO TABLE tmptelefono_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			}

			$prLoad = $connection->prepare($sqlLoad);
			if ($prLoad->execute()) {

				//$connection->beginTransaction();

				$sqlUpdateTMP = "";
				if ($numero_cuenta != '') {
					$sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu 
					ON cu.numero_cuenta = tmp.$numero_cuenta AND cu.codigo_cliente = tmp.$codigo_cliente
					SET tmp.idcartera = cu.idcartera , 
					tmp.tmp_codigo_cliente = cu.codigo_cliente ,
					tmp.idcuenta = cu.idcuenta ,
					tmp.idcliente_cartera = cu.idcliente_cartera 
					WHERE cu.idcartera IN ( " . $cartera . " ) ";
				} else if( $codigo_cliente != '' ) {
					$sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu 
					ON cu.codigo_cliente = tmp.$codigo_cliente 
					SET tmp.idcartera = cu.idcartera , 
					tmp.tmp_codigo_cliente = cu.codigo_cliente ,
					tmp.idcuenta = cu.idcuenta ,
					tmp.idcliente_cartera = cu.idcliente_cartera 
					WHERE cu.idcartera IN ( " . $cartera . " ) ";

				}else if ($codigo_operacion != '') {
					$sqlUpdateTMP = " UPDATE tmptelefono_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu 
					ON detcu.codigo_operacion = tmp.$codigo_operacion
					SET tmp.idcartera = detcu.idcartera , 
					tmp.tmp_codigo_cliente = detcu.codigo_cliente ,
					tmp.idcuenta = detcu.idcuenta ,
					tmp.idcliente_cartera = ( SELECT idcliente_cartera FROM ca_cuenta WHERE idcuenta = detcu.idcuenta LIMIT 1 )
					WHERE detcu.idcartera IN ( " . $cartera . " ) ";
				} else{
					echo json_encode(array('rst' => false, 'msg' => 'Seleccione codigo cliente , numero cuenta o numero de operacion'));
					exit();
				}

				$prUpdateTMP = $connection->prepare($sqlUpdateTMP);
				if ($prUpdateTMP->execute()) {


						$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);


						/* $sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras ) 
						  VALUES( ?, NOW(), ?, NOW(), ?, ? ) "; */
						$values = array();
						$e_cartera = explode(",", $cartera);
						for ($i = 0; $i < count($e_cartera); $i++) {
							array_push($values, " ( " . $e_cartera[$i] . " , NOW(), " . $usuario_creacion . " , NOW(), '" . $parserTelefono . "', '" . $parserHeader . "' ) ");
						}

						$sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras )
							VALUES " . implode(",", $values) . " ";

						$prCartera = $connection->prepare($sqlCartera);
						/* $prCartera->bindParam(1,$cartera,PDO::PARAM_INT);
						  $prCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
						  $prCartera->bindParam(3,$parserTelefono,PDO::PARAM_STR);
						  $prCartera->bindParam(4,$parserHeader,PDO::PARAM_STR); */
						if ($prCartera->execute()) {

							foreach ($jsonTelefono["dataTelefono"] as $index => $value) {
								$fieldTelefonoTMP = array();
								$fieldTelefono = array();
								$fieldReferenciaTelefono = "";

								if (count($value) > 0) {

									$tipo_telefono = $idtipo;

									for ($i = 0; $i < count($value); $i++) {
										if ($value[$i]['campoT'] == 'numero') {
											$fieldReferenciaTelefono = $value[$i]['dato'];

											$tipo_telefono = " IF( SUBSTRING( TRIM( " . $value[$i]['dato'] . " ), 1, 1 ) = 9 , 1, 2 ) ";
										}
										array_push($fieldTelefono, $value[$i]['campoT']);
										array_push($fieldTelefonoTMP, " TRIM( " . $value[$i]['dato'] . " ) ");

									}

									if( $idtipo != '0' ) {
										$tipo_telefono = $idtipo;
									}

									$insertTelefono = "";
									if (trim($fieldReferenciaTelefono) == '') {
										/* $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." ) 
										  SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), ".$idorigen.", $cartera, ".$referenciaTelefono[$index].", ".$idtipo.", $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
										  FROM tmptelefono_".session_id()."_".$time." "; */

										$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, is_new, idcliente_cartera, idcuenta, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
										SELECT TRIM( tmp_codigo_cliente ), 1, idcliente_cartera, idcuenta, " . $idorigen . ", idcartera, " . $referenciaTelefono[$index] . ", " . $tipo_telefono . ", $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
										FROM tmptelefono_" . session_id() . "_" . $time . " 
										WHERE ISNULL(tmp_codigo_cliente) = 0 AND ISNULL( idcartera ) = 0 AND ISNULL( idcliente_cartera ) = 0 ";
									} else {
										/* $insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." ) 
										  SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), ".$idorigen.", $cartera, ".$referenciaTelefono[$index].", ".$idtipo.", $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
										  FROM tmptelefono_".session_id()."_".$time."
										  WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 "; */

										$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, is_new, idcliente_cartera, idcuenta, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
										SELECT TRIM( tmp_codigo_cliente ), 1, idcliente_cartera, idcuenta, " . $idorigen . ", idcartera, " . $referenciaTelefono[$index] . ", " . $tipo_telefono . ", $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
										FROM tmptelefono_" . session_id() . "_" . $time . " 
										WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>4 
										AND ISNULL(tmp_codigo_cliente) = 0 AND ISNULL( idcartera ) = 0 AND ISNULL( idcliente_cartera ) = 0 ";
									}

									$prTelefono = $connection->prepare($insertTelefono);
									if ($prTelefono->execute()) {

									} else {
										//$connection->rollBack();
										echo json_encode(array('rst' => false, 'msg' => 'Error al grabar telefonos'));
										exit();
									}
								}
							}

							//$connection->commit();
							echo json_encode(array('rst' => true, 'msg' => 'Cartera de telefonos cargada correctamente'));
						} else {
							//$connection->rollBack();
							echo json_encode(array('rst' => false, 'msg' => 'Error al grabar data de tabla'));
						}

				} else {
					//$connection->rollBack();
					echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar tabla temporal'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al cargar los datos a tabla'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
		}
	}

//	public function uploadCarteraTelefono ( $_post ) {
//		
//		$cartera = $_post['Cartera'];
//		$campania = $_post['Campania'];
//		$servicio = $_post['Servicio'];
//		$nombre_servicio = $_post['NombreServicio'];
//		$usuario_creacion = $_post['usuario_creacion'];
//		$separator = $_post['separator'];
//		
//		$parserTelefono = str_replace("\\","",$_post['data_telefono']);
//		$jsonTelefono = json_decode($parserTelefono,true);
//		
//		if( trim($jsonTelefono["codigo_cliente"])=='' || trim($jsonTelefono["codigo_cliente"])=='0' ){
//			echo json_encode(array('rst'=>false,'msg'=>'Seleccione codigo de cliente'));
//			exit();
//		}
//		
//		$confCobrast=parse_ini_file('../conf/cobrast.ini',true);
//		
//		if( !isset($confCobrast['ruta_cobrast']) ){
//			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//			exit();	
//		}else if( !isset($confCobrast['ruta_cobrast']['document_root_cobrast']) ) {
//			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//			exit();	
//		}else if( !isset($confCobrast['ruta_cobrast']['nombre_carpeta']) ) {
//			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
//			exit();	
//		}
//				
//		$path="../documents/carteras/".$_post["NombreServicio"]."/".$_post["file"];
//		if( !file_exists($path) ) {
//			echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
//			exit();	
//		}
//		
//		$time=date("Y_m_d_H_i_s");
//		$archivo = @fopen($path,"r+");
//		$colum = explode($separator,fgets($archivo));
//		if( count( $colum )<2 ) {
//			echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto'));
//			exit();
//		}
//	
//		function map_header( $n ) {
//			$item="";
//			if( trim(utf8_encode($n))!="" ){
//				$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"',"?","¿","!","¡","[","]","-");
//				$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");
//
//				$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
//			}
//			
//			return $item;
//		}
//		$colum = array_map("map_header",$colum);
//		$parserHeader = implode(",",$colum);
//		$columHeader=array();
//		$countHeaderFalse=0;
//		
//		for( $i=0;$i<count($colum);$i++ ) {
//			if( $colum[$i]!="" ) {
//				array_push($columHeader,"`".$colum[$i]."` VARCHAR(200) ");
//			}else{
//				$countHeaderFalse++;	
//			}
//		}
//		
//		if( $countHeaderFalse>0 ) {
//			echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
//			exit();
//		}
//		
//		fclose($archivo);
//		
//		
//		
//		array_push($columHeader,"INDEX `index_".session_id()."_cliente` ( `".$jsonTelefono["codigo_cliente"]."` ASC ) ");
//		
//		/********************/
//		
//		$factoryConnection= FactoryConnection::create('mysql');
//        $connection = $factoryConnection->getConnection();
//		
//		$sqlCreateTable=" CREATE TEMPORARY TABLE tmptelefono_".session_id()."_".$time." ( ".implode(",",$columHeader)." ) COLLATE=utf8_spanish_ci ";
//		$prCreateTable = $connection->prepare($sqlCreateTable);
//		if( $prCreateTable->execute() ) {
//			
//			$sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
//			  INTO TABLE tmptelefono_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
//			$prLoad = $connection->prepare($sqlLoad);
//			if( $prLoad->execute() ) {
//				$referenciaTelefono=array('telefono_predeterminado'=>3,'telefono_domicilio'=>2,'telefono_oficina'=>1,'telefono_negocio'=>4,'telefono_laboral'=>5);
//				
//				//$connection->beginTransaction();
//				
//				$sqlCartera = " INSERT INTO ca_cartera_telefono ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, telefono, cabeceras ) 
//					VALUES( ?, NOW(), ?, NOW(), ?, ? ) ";
//				$prCartera = $connection->prepare($sqlCartera);
//				$prCartera->bindParam(1,$cartera,PDO::PARAM_INT);
//				$prCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
//				$prCartera->bindParam(3,$parserTelefono,PDO::PARAM_STR);
//				$prCartera->bindParam(4,$parserHeader,PDO::PARAM_STR);
//				if( $prCartera->execute() ) {
//				
//					foreach( $jsonTelefono["dataTelefono"] as $index => $value ) {
//						$fieldTelefonoTMP = array();
//						$fieldTelefono = array();
//						$fieldReferenciaTelefono = "";
//						
//						if( count($value)>0 ) {
//						
//							for( $i=0;$i<count($value);$i++){
//								if( $value[$i]['campoT']=='numero' ) {
//									$fieldReferenciaTelefono = $value[$i]['dato'];
//								}
//								array_push($fieldTelefono,$value[$i]['campoT']);
//								array_push($fieldTelefonoTMP," TRIM( ".$value[$i]['dato']." ) ");
//							}
//							
//							$insertTelefono = "";
//							if( trim($fieldReferenciaTelefono)=='' ) {
//								$insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." ) 
//								SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), 1, $cartera, ".$referenciaTelefono[$index].", 2, $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
//								FROM tmptelefono_".session_id()."_".$time." ";
//							}else{
//								$insertTelefono=" INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, ".implode(",",$fieldTelefono)." ) 
//								SELECT DISTINCT TRIM( ".$jsonTelefono["codigo_cliente"]." ), 1, $cartera, ".$referenciaTelefono[$index].", 2, $usuario_creacion, NOW(), ".implode(",",$fieldTelefonoTMP)."
//								FROM tmptelefono_".session_id()."_".$time." 
//								WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 ";
//							}
//							
//							$prTelefono = $connection->prepare($insertTelefono);
//							if( $prTelefono->execute() ) {
//								
//							}else{
//								//$connection->rollBack();
//								echo json_encode(array('rst'=>false,'msg'=>'Error al grabar telefonos'));
//								exit();
//							}
//							
//						}
//						
//					}
//					
//					//$connection->commit();
//					echo json_encode(array('rst'=>true,'msg'=>'Datos grabados correctamente'));
//				
//				}else{
//					//$connection->rollBack();
//					echo json_encode(array('rst'=>false,'msg'=>'Error al grabar data de tabla'));
//				}
//				
//			}else{
//				echo json_encode(array('rst'=>false,'msg'=>'Error al cargar los datos a tabla'));
//			}
//			
//		}else{
//			echo json_encode(array('rst'=>false,'msg'=>'Error al crear tabla temporal'));
//		}
//		
//		
//	}


	public function uploadCarteraDetalle($_post) {

		$cartera = $_post['Cartera'];
		$campania = $_post['Campania'];
		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];

		$codigo_operacion = $_post['codigo_operacion'];
		$numero_cuenta = $_post['numero_cuenta'];
		$moneda_cuenta = $_post['moneda_cuenta'];

		$parserAdicional = str_replace("\\", "", $_post['data_adicional']);
		$parserOperacion = str_replace("\\", "", $_post['data_detalle']);
		$jsonOperacion = json_decode($parserOperacion, true);
		$jsonAdicional = json_decode($parserAdicional, true);


		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = @fopen($path, "r+");
		$colum = explode($separator, fgets($archivo));
		if (count($colum) < 2) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$parserHeader = implode(",", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		fclose($archivo);

		array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `" . $numero_cuenta . "` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `" . $codigo_operacion . "` ASC ) ");
		if ($moneda_cuenta != '-Seleccione-' && trim($moneda_cuenta) != '') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `" . $moneda_cuenta . "` ASC ) ");
		}
		/*         * ***************** */

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTable = " CREATE TEMPORARY TABLE tmpdetalle_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
		//echo $sqlCreateTable;
		$prCreateTable = $connection->prepare($sqlCreateTable);
		if ($prCreateTable->execute()) {

			$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
			  INTO TABLE tmpdetalle_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			$prLoad = $connection->prepare($sqlLoad);
			if ($prLoad->execute()) {

				//$connection->beginTransaction();

				$selectCartera = " SELECT idcartera, adicionales FROM ca_cartera WHERE idcartera = ? ";
				$prSelectCartera = $connection->prepare($selectCartera);
				$prSelectCartera->bindParam(1, $cartera);
				$prSelectCartera->execute();
				$dataCartera = $prSelectCartera->fetchAll(PDO::FETCH_ASSOC);
				//print_r($dataCartera);
				$dataAdicionales = json_decode($dataCartera[0]['adicionales'], true);
				//print_r($dataAdicionales);
				$dataAdicionales['ca_datos_adicionales_detalle_cuenta'] = $jsonAdicional['ca_datos_adicionales_detalle_cuenta'];
				//print_r($dataAdicionales);
				$parserAdicionalCartera = str_replace("\\", "", json_encode($dataAdicionales));
				//echo $parserAdicionalCartera;
				$sqlUpdateCartera = " UPDATE ca_cartera SET codigo_operacion = ? ,detalle_cuenta = ? , adicionales = ? , 
							usuario_modificacion = ? , fecha_modificacion = NOW()  WHERE idcartera = ? ";

				$prUpdateCartera = $connection->prepare($sqlUpdateCartera);
				$prUpdateCartera->bindParam(1, $codigo_operacion, PDO::PARAM_STR);
				$prUpdateCartera->bindParam(2, $parserOperacion, PDO::PARAM_STR);
				$prUpdateCartera->bindParam(3, $parserAdicionalCartera, PDO::PARAM_STR);
				$prUpdateCartera->bindParam(4, $usuario_creacion, PDO::PARAM_INT);
				$prUpdateCartera->bindParam(5, $cartera, PDO::PARAM_INT);
				if ($prUpdateCartera->execute()) {

					$selectParser = " SELECT adicionales FROM ca_json_parser WHERE idservicio = ? ORDER BY idjson_parser DESC LIMIT 1 ";
					$prSelectParser = $connection->prepare($selectParser);
					$prSelectParser->bindParam(1, $servicio);
					$prSelectParser->execute();
					$dataParser = $prSelectParser->fetchAll(PDO::FETCH_ASSOC);
					$dataAdicionalesParser = json_decode($dataParser[0]['adicionales'], true);
					$dataAdicionalesParser['ca_datos_adicionales_detalle_cuenta'] = $jsonAdicional['ca_datos_adicionales_detalle_cuenta'];
					$parserAdicionalParser = str_replace("\\", "", json_encode($dataAdicionalesParser));

//					$sqlUpdateJsonParser = " UPDATE ca_json_parser SET numero_cuenta_detalle = ?,
//						moneda_detalle = ?, codigo_operacion_detalle = ? , detalle_cuenta = ?, 
//						adicionales = ? , cabeceras_detalle = ?, usuario_modificacion = ?, fecha_modificacion = NOW() 
//						WHERE idservicio = ? AND idjson_parser = ( SELECT MAX(idjson_parser) FROM ca_json_parser WHERE idservicio = ? ) ";

					$sqlUpdateJsonParser = " UPDATE ca_json_parser SET numero_cuenta_detalle = ?,
						moneda_detalle = ?, codigo_operacion_detalle = ? , detalle_cuenta = ?, 
						adicionales = ? , cabeceras_detalle = ?, usuario_modificacion = ?, fecha_modificacion = NOW() 
						WHERE idservicio = ? ORDER BY idjson_parser DESC LIMIT 1 ";

					$prUpdateJsonParser = $connection->prepare($sqlUpdateJsonParser);
					$prUpdateJsonParser->bindParam(1, $numero_cuenta, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(2, $moneda_cuenta, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(3, $codigo_operacion, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(4, $parserOperacion, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(5, $parserAdicionalParser, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(6, $parserHeader, PDO::PARAM_STR);
					$prUpdateJsonParser->bindParam(7, $usuario_creacion, PDO::PARAM_INT);
					$prUpdateJsonParser->bindParam(8, $servicio, PDO::PARAM_INT);
					//$prUpdateJsonParser->bindParam(9,$servicio,PDO::PARAM_INT);
					if ($prUpdateJsonParser->execute()) {

						$sqlCartera = " INSERT INTO ca_cartera_detalle( idcartera, fecha_carga, usuario_creacion, fecha_creacion, cabeceras, codigo_operacion, numero_cuenta, moneda_cuenta, detalle_cuenta, adicionales ) 
								VALUES( ?,NOW(),?,NOW(),?,?,?,?,?,? ) ";
						$prCartera = $connection->prepare($sqlCartera);
						$prCartera->bindParam(1, $cartera, PDO::PARAM_INT);
						$prCartera->bindParam(2, $usuario_creacion, PDO::PARAM_INT);
						$prCartera->bindParam(3, $parserHeader, PDO::PARAM_STR);
						$prCartera->bindParam(4, $codigo_operacion, PDO::PARAM_STR);
						$prCartera->bindParam(5, $numero_cuenta, PDO::PARAM_STR);
						$prCartera->bindParam(6, $moneda_cuenta, PDO::PARAM_STR);
						$prCartera->bindParam(7, $parserOperacion, PDO::PARAM_STR);
						$prCartera->bindParam(8, $parserAdicional, PDO::PARAM_STR);
						if ($prCartera->execute()) {
							$fieldOperacion = array();
							$fieldTMP = array();
							for ($i = 0; $i < count($jsonOperacion); $i++) {
								array_push($fieldOperacion, $jsonOperacion[$i]['campoT']);
								array_push($fieldTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
							}
							$insertOperacion = "";
							if ($moneda_cuenta != '-Seleccione-' && trim($moneda_cuenta) != '') {

								//							$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , idcartera, usuario_creacion, fecha_creacion ) 
								//								SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ) , $cartera, $usuario_creacion , NOW()  
								//								FROM tmpdetalle_".session_id()."_".$time." 
								//								WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 
								//								GROUP BY TRIM( $codigo_operacion ) ";
								//							$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta, codigo_cliente , idcartera, usuario_creacion, fecha_creacion ) 
								//								SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND idcartera = $cartera LIMIT 1  ), $cartera, $usuario_creacion , NOW()  
								//								FROM tmpdetalle_".session_id()."_".$time." 
								//								WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 
								//								GROUP BY TRIM( $codigo_operacion ) ";

								$insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $fieldOperacion) . ", numero_cuenta , moneda, codigo_cliente,  idcartera, usuario_creacion, fecha_creacion ) 
									SELECT " . implode(",", $fieldTMP) . ", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND moneda = TRIM( $moneda_cuenta ) AND idcartera = $cartera LIMIT 1  ) , $cartera, $usuario_creacion , NOW()  
									FROM tmpdetalle_" . session_id() . "_" . $time . " 
									WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 
									GROUP BY TRIM( $codigo_operacion ) ";
							} else {

								//							$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , moneda, idcartera, usuario_creacion, fecha_creacion ) 
								//								SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , $cartera, $usuario_creacion , NOW()  
								//								FROM tmpdetalle_".session_id()."_".$time." 
								//								WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 
								//								GROUP BY TRIM( $codigo_operacion ) ";

								$insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $fieldOperacion) . ", numero_cuenta, codigo_cliente , idcartera, usuario_creacion, fecha_creacion, is_detalle ) 
									SELECT " . implode(",", $fieldTMP) . ", TRIM( $numero_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND idcartera = $cartera LIMIT 1  ), $cartera, $usuario_creacion , NOW() , 1  
									FROM tmpdetalle_" . session_id() . "_" . $time . " 
									WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 ";

								//							$insertOperacion=" INSERT IGNORE INTO ca_detalle_cuenta( ".implode(",",$fieldOperacion).", numero_cuenta , moneda, codigo_cliente,  idcartera, usuario_creacion, fecha_creacion ) 
								//								SELECT ".implode(",",$fieldTMP).", TRIM( $numero_cuenta ), TRIM( $moneda_cuenta ) , ( SELECT codigo_cliente FROM ca_cuenta WHERE numero_cuenta = TRIM( $numero_cuenta ) AND moneda = TRIM( $moneda_cuenta ) AND idcartera = $cartera LIMIT 1  ) , $cartera, $usuario_creacion , NOW()  
								//								FROM tmpdetalle_".session_id()."_".$time." 
								//								WHERE LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 ";
							}

							$prInsertOperacion = $connection->prepare($insertOperacion);
							if ($prInsertOperacion->execute()) {

								$updateCuenta = " UPDATE ca_cuenta cu
									SET cu.total_deuda =  
									( 
									SELECT SUM(total_deuda) 
									FROM ca_detalle_cuenta WHERE idcartera = $cartera AND numero_cuenta = cu.numero_cuenta
									GROUP BY numero_cuenta LIMIT 1
									) 
									WHERE cu.idcartera = $cartera ";

								$prUpdateCuenta = $connection->prepare($updateCuenta);
								if ($prUpdateCuenta->execute()) {

									if (count($jsonAdicional['ca_datos_adicionales_detalle_cuenta']) > 0) {
										$fieldAdicional = array();
										$fieldAdicionalTMP = array();
										$cabecerasAdicional = array();
										//									foreach( $jsonAdicional['ca_datos_adicionales_detalle_cuenta'] as $index => $value ){
										//										array_push($fieldAdicional,$index);
										//										array_push($fieldAdicionalTMP,"TRIM( ".$value." )");
										//										array_push($cabecerasAdicional,"'".$value."'");
										//									}


										for ($i = 0; $i < count($jsonAdicional['ca_datos_adicionales_detalle_cuenta']); $i++) {
											array_push($fieldAdicional, $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['campoT']);
											array_push($fieldAdicionalTMP, "TRIM( " . $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['dato'] . " )");
											array_push($cabecerasAdicional, "'" . $jsonAdicional['ca_datos_adicionales_detalle_cuenta'][$i]['label'] . "'");
										}

										$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldAdicional) . " ) 
												VALUES( " . $servicio . " , 3, $cartera, NOW(), $usuario_creacion, " . implode(",", $cabecerasAdicional) . " ) ";
										$prInsertCabeceras = $connection->prepare($insertCabeceras);
										if ($prInsertCabeceras->execute()) {

											//									$insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, ".implode(",",$fieldAdicional)." ) 
											//										SELECT $cartera, TRIM( $codigo_operacion ), ".implode(",",$fieldAdicionalTMP)."
											//										FROM tmpdetalle_".session_id()."_".$time." GROUP BY TRIM( $codigo_operacion ) ";
											//									$insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, codigo_cliente, ".implode(",",$fieldAdicional)." ) 
											//										SELECT $cartera, TRIM( $codigo_operacion ), ( SELECT codigo_cliente FROM ca_detalle_cuenta WHERE codigo_operacion = TRIM( $codigo_operacion ) AND idcartera = $cartera LIMIT 1  ) , ".implode(",",$fieldAdicionalTMP)."
											//										FROM tmpdetalle_".session_id()."_".$time." ";
											$insertAdicionales = " INSERT IGNORE INTO ca_datos_adicionales_detalle_cuenta ( idcartera, codigo_operacion, codigo_cliente, " . implode(",", $fieldAdicional) . " ) 
												SELECT $cartera, TRIM( $codigo_operacion ), ( SELECT codigo_cliente FROM ca_detalle_cuenta WHERE codigo_operacion = TRIM( $codigo_operacion ) AND idcartera = $cartera LIMIT 1  ) , " . implode(",", $fieldAdicionalTMP) . "
												FROM tmpdetalle_" . session_id() . "_" . $time . " GROUP BY TRIM( $codigo_operacion ) ";
											//echo $insertAdicionales;
											$prInsertAdicionales = $connection->prepare($insertAdicionales);
											if ($prInsertAdicionales->execute()) {
												//$connection->commit();
												echo json_encode(array('rst' => true, 'msg' => 'Datos grabados correctamente'));
											} else {
												//$connection->rollBack();
												echo json_encode(array('rst' => false, 'msg' => 'Error al grabar datos adicionales'));
											}
										} else {
											//$connection->rollBack();
											echo json_encode(array('rst' => false, 'msg' => 'Error al grabar cabeceras'));
										}
									} else {
										//$connection->commit();
										echo json_encode(array('rst' => true, 'msg' => 'Datos grabados correctamente'));
									}
								} else {
									//$connection->rollBack();
									echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar deuda total'));
								}
							} else {
								//$connection->rollBack();
								echo json_encode(array('rst' => false, 'msg' => 'Error al insertar detalle'));
							}
						} else {
							//$connection->rollBack();
							echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
						}
					} else {
						//$connection->rollBack();
						echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar metadata'));
					}
				} else {
					//$connection->rollBack();
					echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar de datos de cartera'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
		}
	}

	public function uploadCarteraReclamo($_post) {

		$cartera = $_post['Cartera'];
		$campania = @$_post['Campania'];
		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];

		/*         * ****** */
		if ($cartera == '') {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione carteras'));
			exit;
		}
		/*         * ****** */
		$codigo_cliente = '';
		$codigo_operacion = '';
		/*         * ***** */
		$numero_cuenta = '';
		$moneda = '';
		$telefono = '';

		$parserReclamo = str_replace("\\", "", $_post['data_reclamo']);
		$jsonReclamo = json_decode($parserReclamo, true);

		$field_u_on = array();

		for ($i = 0; $i < count($jsonReclamo); $i++) {
			if ($jsonReclamo[$i]['campoT'] == 'numero_cuenta') {
				$numero_cuenta = $jsonReclamo[$i]['dato'];
				array_push($field_u_on," detcu.numero_cuenta = tmp.".$jsonReclamo[$i]['dato']." ");
			} else if ($jsonReclamo[$i]['campoT'] == 'moneda') {
				$moneda = $jsonReclamo[$i]['dato'];
				array_push($field_u_on," detcu.moneda = tmp.".$jsonReclamo[$i]['dato']." ");
			} else if ($jsonReclamo[$i]['campoT'] == 'telefono') {
				$telefono = $jsonReclamo[$i]['dato'];

			} else if ($jsonReclamo[$i]['campoT'] == 'codigo_cliente') {
				$codigo_cliente = $jsonReclamo[$i]['dato'];
				array_push($field_u_on," detcu.codigo_cliente = tmp.".$jsonReclamo[$i]['dato']." ");
			} else if ($jsonReclamo[$i]['campoT'] == 'codigo_operacion') {
				$codigo_operacion = $jsonReclamo[$i]['dato'];
				array_push($field_u_on," detcu.codigo_operacion = tmp.".$jsonReclamo[$i]['dato']." ");
			}
		}

		if (trim($numero_cuenta) == '') {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione numero de cuenta'));
			exit();
		}

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = @fopen($path, "r+");
		$colum = explode($separator, fgets($archivo));
		if (count($colum) < 2) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$parserHeader = implode(",", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		/*         * ************** */
		array_push($columHeader, "`iddetalle_cuenta` int ");
		array_push($columHeader, "`idcuenta` int ");
		array_push($columHeader, "`idcliente_cartera` int ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_iddetalle_cuenta` ( `iddetalle_cuenta` ASC ) ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_idcuenta` ( `idcuenta` ASC ) ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_idcliente` ( `idcliente_cartera` ASC ) ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_numero_cuenta` ( `$numero_cuenta` ASC ) ");
		array_push($columHeader, " INDEX `index_" . session_id() . "_codigo_operacion` ( `$codigo_operacion` ASC ) ");
		/*         * ************** */
		fclose($archivo);

		/*         * ***************** */

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTable = " CREATE TABLE tmpreclamo_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
		//echo $sqlCreateTable;
		$prCreateTable = $connection->prepare($sqlCreateTable);
		if ($prCreateTable->execute()) {
			$sqlLoad = "";
			if ($_post['separator'] == 'tab') {
				$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				  INTO TABLE tmpreclamo_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			} else {
				$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				  INTO TABLE tmpreclamo_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			}

			$prLoad = $connection->prepare($sqlLoad);
			if ($prLoad->execute()) {

				//$connection->beginTransaction();

				/*                 * ************* */
				$values = array();
				$e_carteras = explode(",", $cartera);
				for ($i = 0; $i < count($e_carteras); $i++) {
					array_push($values, " ( " . $e_carteras[$i] . ", NOW() ," . $usuario_creacion . ", NOW(), '" . $parserReclamo . "', '" . $parserHeader . "' ) ");
				}
				$insertCarteraReclamo = " INSERT INTO ca_cartera_reclamo ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, reclamo, cabeceras ) 
					VALUES " . implode(",", $values) . "  ";
				/*                 * ************* */

				/* $insertCarteraReclamo = " INSERT INTO ca_cartera_reclamo ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, reclamo, cabeceras ) 
				  VALUES ( ?,NOW(),?,NOW(),?,? ) ";

				  $prInsertCartera = $connection->prepare($insertCarteraReclamo);
				  $prInsertCartera->bindParam(1,$cartera,PDO::PARAM_INT);
				  $prInsertCartera->bindParam(2,$usuario_creacion,PDO::PARAM_INT);
				  $prInsertCartera->bindParam(3,$parserReclamo,PDO::PARAM_STR);
				  $prInsertCartera->bindParam(4,$parserHeader,PDO::PARAM_STR); */
				//echo $insertCarteraReclamo;
				$prInsertCartera = $connection->prepare($insertCarteraReclamo);
				if ($prInsertCartera->execute()) {

					$sqlUpdateIdTMP = " UPDATE tmpreclamo_" . session_id() . "_" . $time . " tmp INNER JOIN ca_detalle_cuenta detcu 
						 ON ".implode(" AND ",$field_u_on)."
						 SET 
						 tmp.iddetalle_cuenta = detcu.iddetalle_cuenta , 
						 tmp.idcuenta = detcu.idcuenta
						 WHERE detcu.idcartera IN ( " . $cartera . " ) ";

					$prUpdateIdTMP = $connection->prepare($sqlUpdateIdTMP);
					if ($prUpdateIdTMP->execute()) {

						$sqlUpdateIdTMPClienteCartera = " UPDATE tmpreclamo_" . session_id() . "_" . $time . " tmp INNER JOIN ca_cuenta cu 
							ON cu.idcuenta = tmp.idcuenta 
							SET tmp.idcliente_cartera = cu.idcliente_cartera
							WHERE cu.idcartera IN ( ".$cartera." ) AND ISNULL(tmp.idcuenta) = 0 ";

						$prUpdateIdTMPClienteCartera = $connection->prepare($sqlUpdateIdTMPClienteCartera);
						if ($prUpdateIdTMPClienteCartera->execute()) {

							$sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp 
							ON tmp.idcliente_cartera = clicar.idcliente_cartera  
							SET clicar.reclamo = 1 
							WHERE ISNULL(tmp.idcliente_cartera) = 0 AND clicar.idcartera IN ( ".$cartera." ) ";

							$prUpdateClienteCartera = $connection->prepare($sqlUpdateClienteCartera);
							if ($prUpdateClienteCartera->execute()) {

								$sqlUpdateCuenta = " UPDATE ca_cuenta cu INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp
								ON tmp.idcuenta = cu.idcuenta 
								SET cu.is_reclamo = 1 
								WHERE ISNULL(tmp.idcuenta) = 0 AND cu.idcartera IN ( ".$cartera." ) ";

								$prUpdateCuenta = $connection->prepare($sqlUpdateCuenta);
								if ($prUpdateCuenta->execute()) {

									$fieldSet = array();
									for ($i = 0; $i < count($jsonReclamo); $i++) {

										if ($jsonReclamo[$i]['campoT'] != 'numero_cuenta' && $jsonReclamo[$i]['campoT'] != 'moneda' && $jsonReclamo[$i]['campoT'] != 'telefono' && $jsonReclamo[$i]['campoT'] != 'codigo_cliente' && $jsonReclamo[$i]['campoT'] != 'codigo_operacion') {
											array_push($fieldSet, " detcu." . $jsonReclamo[$i]['campoT'] . " = tmp." . $jsonReclamo[$i]['dato'] . " ");
										}
									}

									$implode_field_set = "";

									if (count($fieldSet) > 0) {
										$implode_field_set = " , " . implode(",", $fieldSet);
									}

									$sqlUpdateDetalleCuenta = " UPDATE ca_detalle_cuenta detcu INNER JOIN tmpreclamo_" . session_id() . "_" . $time . " tmp
									ON tmp.iddetalle_cuenta = detcu.iddetalle_cuenta 
									SET detcu.is_reclamo = 1 " . $implode_field_set . " 
									WHERE ISNULL(tmp.iddetalle_cuenta) = 0 AND detcu.idcartera IN ( ".$cartera." ) ";

									$prUpdateDetalleCuenta = $connection->prepare($sqlUpdateDetalleCuenta);
									if ($prUpdateDetalleCuenta->execute()) {
										//$connection->commit();
										echo json_encode(array('rst' => true, 'msg' => 'Cartera de reclamo cargada correctamente'));
									} else {
										//$connection->rollBack();
										echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar facturas'));
									}
								} else {
									//$connection->rollBack();
									echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar cuentas'));
								}
							} else {
								//$connection->rollBack();
								echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar clientes'));
							}

						} else {
							echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar temporal( cliente )'));
							exit();
						}



					} else {
						//$connection->rollBack();
						echo json_encode(array('rst' => false, 'msg' => 'Error al actualizar temporal( detalle_cuenta, cuenta )'));
					}

					/* $sqlInsertReclamos = "";
					  if( $moneda == '' && $telefono == '' ) {
					  $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
					  WHERE idcartera = ? AND TRIM(numero_cuenta) IN ( SELECT TRIM($numero_cuenta) FROM tmpreclamo_".session_id()."_".$time." ) ";
					  }else if( $moneda == '' && $telefono != '' ) {
					  $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
					  WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(telefono)) IN
					  ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($telefono)) FROM tmpreclamo_".session_id()."_".$time." ) ";
					  }else if( $moneda != '' && $telefono == '' ) {
					  $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
					  WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(moneda)) IN
					  ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda)) FROM tmpreclamo_".session_id()."_".$time." ) ";
					  }else if( $moneda != '' && $telefono != '' ) {
					  $sqlInsertReclamos = " UPDATE ca_cuenta SET is_reclamo = 1
					  WHERE idcartera = ? AND CONCAT_WS('-',TRIM(numero_cuenta),TRIM(moneda),TRIM(telefono)) IN
					  ( SELECT CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda),TRIM($telefono)) FROM tmpreclamo_".session_id()."_".$time." ) ";
					  }else{
					  //$connection->rollBack();
					  echo json_encode(array('rst'=>false,'msg'=>'Error al insertar reclamos'));
					  }
					  //echo $sqlInsertReclamos;
					  $prInsertReclamos = $connection->prepare($sqlInsertReclamos);
					  $prInsertReclamos->bindParam(1,$cartera,PDO::PARAM_INT);
					  if( $prInsertReclamos->execute() ) {

					  //$connection->commit();
					  echo json_encode(array('rst'=>true,'msg'=>'Datos de cartera insertados correctamente'));

					  }else{
					  //$connection->rollBack();
					  echo json_encode(array('rst'=>false,'msg'=>'Error al insertar reclamos'));
					  } */
				} else {
					//$connection->rollBack();
					echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
		}
	}

	public function uploadCarteraNOCpre_masivo($_post, $file) {

		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];
		$formatoFechas = $_post['formatoFechas'];
		$estado_noc_pre = $_post['estado_noc_pre'];

		//COMPROBACIOON RUTA COBRAST SQLLITE
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}
		$path = "../documents/nocpredictivo/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
			/* echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			  exit(); */
		}


		$time = date("Y_m_d_H_i_s");

		/*         * ***************** */
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();


		$sqlDropTableTmpNocPredictivo = " DROP TABLE IF EXISTS tmp_noc_predictivo_" . $time;
		$prDropTableTmpNocPredictivo = $connection->prepare($sqlDropTableTmpNocPredictivo);
		if ($prDropTableTmpNocPredictivo->execute()) {

			$sqlCreateTableTmpNocPredictivo = " CREATE TABLE tmp_noc_predictivo_" . $time . " ( 
			ID varchar(50),
			ESTADO varchar(50),
			Fecha_Hora varchar(50),
			Hora varchar(50),
			Telefono varchar(50),
			codigo_cliente  varchar(50),
			idcliente_cartera int,
			idcartera int,
			idcliente int,
			idcuenta int,
			inicio_tramo datetime,
			fin_tramo datetime,
			idtelefono varchar(30),
			fecha_creacion datetime 
			) ENGINE = InnoDB ";

			$prsqlCreateTableTmpNocPredictivo = $connection->prepare($sqlCreateTableTmpNocPredictivo);
			if ($prsqlCreateTableTmpNocPredictivo->execute()) {

				/***************/

				$sqlLoad = "";
				if( $separator == 'tab' ) {
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/nocpredictivo/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_noc_predictivo_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}else{
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/nocpredictivo/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_noc_predictivo_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}

				$prLoad = $connection->prepare($sqlLoad);

				if ($prLoad->execute()) {

					/* CARGA A TEMPORAL */

					//$connection->beginTransaction();		

					$sqlUpdate1tmp = " update tmp_noc_predictivo_" . $time . " 
						set Telefono = if(length(Telefono)=7,concat('1',Telefono),Telefono) ";
					$prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
					if ($prUpdate1tmp->execute()) {

						$sqlUpdate2tmp = "update tmp_noc_predictivo_" . $time . " t set t.ID=lpad(t.ID,10,'0')";
						$prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
						if ($prUpdate2tmp->execute()) {

							$sqlUpdate3tmp = " update tmp_noc_predictivo_" . $time . " t 
								set idcartera = (  select max(cu.idcartera) from ca_cuenta cu inner join ca_cartera car on car.idcartera = cu.idcartera where cu.numero_cuenta = trim(t.ID) and car.estado = 1 ) ";
							$prUpdate3tmp = $connection->prepare($sqlUpdate3tmp);
							if ($prUpdate3tmp->execute()) {

								$sqlUpdate4tmp = "update tmp_noc_predictivo_" . $time . " t 
									set codigo_cliente = (  select codigo_cliente from ca_cuenta where numero_cuenta = trim(t.ID) and idcartera = t.idcartera ) ";
								$prUpdate4tmp = $connection->prepare($sqlUpdate4tmp);
								if ($prUpdate4tmp->execute()) {

									$sqlUpdate5tmp = " update tmp_noc_predictivo_" . $time . " t inner join ca_cuenta cu
										on cu.idcartera = t.idcartera and cu.codigo_cliente = t.codigo_cliente and cu.numero_cuenta = trim(t.ID)
										set 
										t.idcuenta = cu.idcuenta ,
										t.idcliente_cartera = cu.idcliente_cartera 
										where isnull(t.idcartera) = 0 and isnull(t.codigo_cliente) = 0 ";
									$prUpdate5tmp = $connection->prepare($sqlUpdate5tmp);
									if ($prUpdate5tmp->execute()) {

										$sqlUpdate6tmp = "update tmp_noc_predictivo_" . $time . " t inner join ca_cliente_cartera clicar
											on clicar.idcartera = t.idcartera and clicar.idcliente_cartera = t.idcliente_cartera
											set t.idcliente = clicar.idcliente
											where isnull(t.idcartera) = 0 and isnull(t.idcliente_cartera)=0 ";
										$prUpdate6tmp = $connection->prepare($sqlUpdate6tmp);
										if ($prUpdate6tmp->execute()) {

														$sqlUpdate10tmp = "update tmp_noc_predictivo_" . $time . " t 
														set idtelefono = ( select idtelefono from ca_telefono where numero = trim(t.Telefono) and idcuenta = t.idcuenta and idcartera = t.idcartera limit 1 )";
														$prUpdate10tmp = $connection->prepare($sqlUpdate10tmp);
														if ($prUpdate10tmp->execute()) {

																/*                                                                 * *************************	DISTRIBUCION DE DATOS */

																/*$sqlInsertTransaccion = "insert into ca_transaccion ( 
																	idtipo_gestion,idcliente_cartera,idfinal,fecha_creacion,usuario_creacion,fecha,is_predictivo_noc,telefono,observacion
																	)
																	select 2,idcliente_cartera,306,fecha_creacion,1,concat_ws(' ',STR_TO_DATE(Fecha_Hora,'" . $formatoFechas . "'),substr(Fecha_Hora,12,8)) as Fecha_Hora,1,Telefono,'NO CONTESTA PREDICTIVO'
																	from  tmp_noc_predictivo_" . $time . " where idtelefono is not null and idcartera is not null and Fecha_Hora is not null ";*/


																$sqlInsertTransaccion = " INSERT INTO ca_llamada ( 
																	idcliente_cartera, idfinal, idtipo_gestion, idtelefono, idcuenta, fecha, observacion, tipo, fecha_creacion, usuario_creacion
																	) 
																	SELECT idcliente_cartera, $estado_noc_pre, 2, idtelefono, idcuenta, concat_ws(' ',STR_TO_DATE(Fecha_Hora,'" . $formatoFechas . "'),substr(Fecha_Hora,12,8)), 'NO CONTESTA PREDICTIVO', 'NOCPRE', NOW(), $usuario_creacion 
																	FROM tmp_noc_predictivo_" . $time . "
																	WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(idtelefono) = 0 ";
																$prInsertTransaccion = $connection->prepare($sqlInsertTransaccion);
																if ($prInsertTransaccion->execute()) {

																	$sqlUpdCliCar = "
																			update ca_cliente_cartera clicar inner join tmp_noc_predictivo_" . $time . " t 
																			on t.idcliente_cartera=clicar.idcliente_cartera 
																			set is_noc_predictivo = 1
																		";
																	$prUpdCliCar = $connection->prepare($sqlUpdCliCar);
																	if ($prUpdCliCar->execute()) {

																		return array('rst' => true, 'msg' => 'Datos NOC Predictivo insertados correctamente');

																	} else {
																		//$connection->rollBack(); 
																		return array('rst' => false, 'msg' => 'Error al Actualizar Tabla Cliente Cartera');
																		//echo json_encode(array('rst'=>false,'msg'=>'Error al Actualizar Tabla Cliente Cartera'));
																	}
																} else {
																	//$connection->rollBack();
																	return array('rst' => false, 'msg' => 'Error al insertar datos de Transaccion');
																	//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos de Transaccion'));	
																}

																/*                                                                 * ************************ */

														} else {
															//$connection->rollBack();
															return array('rst' => true, 'msg' => 'Error en carga de datos temporales10');
															//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales10'));				
														}



										} else {
											//$connection->rollBack();
											return array('rst' => true, 'msg' => 'Error en carga de datos temporales6');
											//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales6'));				
										}
									} else {
										//$connection->rollBack();
										return array('rst' => true, 'msg' => 'Error en carga de datos temporales5');
										//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales5'));				
									}
								} else {
									//$connection->rollBack();
									return array('rst' => true, 'msg' => 'Error en carga de datos temporales4');
									//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales4'));				
								}
							} else {
								//$connection->rollBack();
								return array('rst' => true, 'msg' => 'Error en carga de datos temporales3');
								//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales3'));				
							}
						} else {
							//$connection->rollBack();
							return array('rst' => true, 'msg' => 'Error en carga de datos temporales2');
							//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales2'));				
						}
					} else {
						//$connection->rollBack();
						return array('rst' => true, 'msg' => 'Error en carga de datos temporales1');
						//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales1'));				
					}



					/* FIN CARGA A TEMPORAL */
				} else {
					return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
					//echo json_encode(array('rst'=>false,'msg'=>'Error al Cargar Datos a Temporal'));	
				}

				/*                 * ********* */

				/* $sqlDropTableFinalTmpNocPredictivo=" DROP TABLE IF EXISTS tmp_noc_predictivo_".$time;
				  $prDropTableFinalTmpNocPredictivo=$connection->prepare($sqlDropTableFinalTmpNocPredictivo);
				  $prDropTableFinalTmpNocPredictivo->execute(); */
			} else {
				return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
				//echo json_encode(array('rst'=>false,'msg'=>'Error al Crear Tabla Temporal'));	
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
			//echo json_encode(array('rst'=>false,'msg'=>'Error al Eliminar Tabla Temporal Anterior'));	
		}
	}

	public function uploadCarteraIVR_masivo($_post, $file) {

		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];
		$formatoFechas = $_post['formatoFechas'];
		$estado_contestado = $_post['estado_contestado'];
		$estado_no_contestado = $_post['estado_no_contestado'];
		//$idcampania=$_post['idcampania'];
		//COMPROBACIOON RUTA COBRAST SQLLITE
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}
		$path = "../documents/ivr/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
			/* echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			  exit(); */
		}


		$time = date("Y_m_d_H_i_s");

		/*         * ***************** */
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();


		$sqlDropTableTmpIVR = " DROP TABLE IF EXISTS tmp_ivr_" . $time;
		$prDropTableTmpIVR = $connection->prepare($sqlDropTableTmpIVR);
		if ($prDropTableTmpIVR->execute()) {

			$sqlCreateTableTmpIVR = " create table tmp_ivr_" . $time . " (  
				telefono varchar(50),
				campania varchar(50),
				estado varchar(50),
				fecha varchar(10),
				hora varchar(50),
				Fecha_Hora varchar(50),
				codigo_cliente  varchar(50),
				idcliente_cartera int,
				idcartera int,
				idcuenta int,
				idtelefono int
				)  ";

			$prsqlCreateTableTmpIVR = $connection->prepare($sqlCreateTableTmpIVR);
			if ($prsqlCreateTableTmpIVR->execute()) {

				/*                 * ************ */
				$sqlLoad = "";
				if( $separator == 'tab' ) {
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/ivr/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_ivr_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				} else{
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/ivr/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_ivr_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}


				$prLoad = $connection->prepare($sqlLoad);

				if ($prLoad->execute()) {

					/* CARGA A TEMPORAL */

					//$connection->beginTransaction();		

					$sqlUpdate1tmp = "update tmp_ivr_" . $time . " 
						set telefono = if(length(telefono)=7,concat('1',telefono),telefono)";
					$prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
					if ($prUpdate1tmp->execute()) {

						$sqlUpdate2tmp = "update tmp_ivr_" . $time . " 
							set Fecha_hora = concat_ws(' ',fecha,hora) ";
						$prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
						if ($prUpdate2tmp->execute()) {

							$sqlUpdate3tmp = "update tmp_ivr_" . $time . " i 
								set 
								i.idcartera=(select max(tel.idcartera) from ca_telefono tel inner join ca_cartera car on tel.idcartera=car.idcartera where numero=i.telefono and car.estado=1)";
							/* $sqlUpdate3tmp="update tmp_ivr_".$time." i set i.idcartera=(select max(tel.idcartera) from ca_telefono tel inner join ca_cartera car on tel.idcartera=car.idcartera where numero=i.telefono and car.estado=1 and idcampania=".$idcampania.")"; */
							$prUpdate3tmp = $connection->prepare($sqlUpdate3tmp);
							if ($prUpdate3tmp->execute()) {

								$sqlUpdate4tmp = "update ca_telefono tel inner join tmp_ivr_" . $time . " i 
									on i.idcartera = tel.idcartera and i.telefono = tel.numero 
									set 
									i.codigo_cliente = tel.codigo_cliente ,
									i.idtelefono = tel.idtelefono,
									i.idcliente_cartera = tel.idcliente_cartera,
									i.idcuenta = tel.idcuenta
									where isnull(i.idcartera) = 0 ";
								$prUpdate4tmp = $connection->prepare($sqlUpdate4tmp);
								if ($prUpdate4tmp->execute()) {

									/*$sqlUpdate5tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i 
										on i.codigo_cliente = clicar.codigo_cliente 
										set i.idcliente_cartera = clicar.idcliente_cartera 
										where clicar.idcartera =i.idcartera";*/

									/*$sqlUpdate5tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i 
										on i.idcartera = clicar.idcartera and i.codigo_cliente = clicar.codigo_cliente 
										set 
										i.idcliente_cartera = clicar.idcliente_cartera ,
										i.idcliente = clicar.idcliente 
										";

									$prUpdate5tmp = $connection->prepare($sqlUpdate5tmp);
									if ($prUpdate5tmp->execute()) {*/

										/*$sqlUpdate6tmp = "update ca_cliente_cartera clicar inner join tmp_ivr_" . $time . " i 
											on i.idcliente_cartera=clicar.idcliente_cartera 
											set i.idcliente = clicar.idcliente";
										$prUpdate6tmp = $connection->prepare($sqlUpdate6tmp);
										if ($prUpdate6tmp->execute()) {*/

											/*$sqlUpdate7tmp = "update ca_cuenta cu inner join tmp_ivr_" . $time . " i 
												on cu.codigo_cliente=i.codigo_cliente 
												set i.numero_cuenta=cu.numero_cuenta 
												where i.telefono = cu.telefono and  cu.idcliente_cartera=i.idcliente_cartera 
												and cu.idcartera=i.idcartera";*/
											/*$sqlUpdate7tmp = "update ca_cuenta cu inner join tmp_ivr_" . $time . " i 
												on i.idcartera = cu.idcartera and i.idcliente_cartera = cu.idcliente_cartera and i.telefono = cu.telefono
												set i.idcuenta = cu.idcuenta 
												where isnull(i.idcartera)=0 and isnull(i.idcliente_cartera) = 0 and isnull(i.idtelefono) = 0
												";
											$prUpdate7tmp = $connection->prepare($sqlUpdate7tmp);
											if ($prUpdate7tmp->execute()) {*/





																				/******* DISTRIBUCION DE DATOS ********/

																				/*$sqlInsertTransaccion = "insert into ca_transaccion (
																					idtipo_gestion,idcliente_cartera,idfinal,observacion,fecha_creacion,usuario_creacion,fecha,is_ivr,telefono)
																					select 2, idcliente_cartera, if(Estado='NO CONTESTADO',304,305),'RESULTADO IVR',fecha_creacion,1,Fecha_Hora,1,telefono 
																					from tmp_ivr_" . $time . " 
																					where idcliente_cartera is not null and codigo_cliente is not null";*/

																				$sqlInsertIvr = " INSERT INTO ca_llamada 
																						(
																						idcliente_cartera, idfinal, idtipo_gestion, idtelefono, idcuenta, fecha, observacion, tipo, fecha_creacion, usuario_creacion
																						)
																						SELECT idcliente_cartera, IF(Estado='NO CONTESTADO',$estado_no_contestado,$estado_contestado), 2, idtelefono, idcuenta, Fecha_Hora, 'RESULTADO IVR', 'IVR', NOW(), $usuario_creacion 
																						FROM tmp_ivr_" . $time . "
																						WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(idtelefono) = 0 AND ISNULL(idcartera) = 0 
																						";
																				$prInsertTransaccion = $connection->prepare($sqlInsertIvr);
																				if ($prInsertTransaccion->execute()) {
																					return array('rst' => true, 'msg' => 'Datos IVR insertados correctamente');

																				} else {
																					//$connection->rollBack();
																					return array('rst' => false, 'msg' => 'Error al insertar datos de Transaccion');
																					//echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos de Transaccion'));	
																				}


											/*} else {
												//$connection->rollBack();
												return array('rst' => true, 'msg' => 'Error en carga de datos temporales7');
												//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales7'));				
											}*/
										/*} else {
											//$connection->rollBack();
											return array('rst' => true, 'msg' => 'Error en carga de datos temporales6');
											//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales6'));				
										}*/
									/*} else {
										//$connection->rollBack();
										return array('rst' => true, 'msg' => 'Error en carga de datos temporales5');
										//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales5'));				
									}*/
								} else {
									//$connection->rollBack();
									return array('rst' => true, 'msg' => 'Error en carga de datos temporales4');
									//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales4'));				
								}
							} else {
								//$connection->rollBack();
								return array('rst' => true, 'msg' => 'Error en carga de datos temporales3');
								//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales3'));				
							}
						} else {
							//$connection->rollBack();
							return array('rst' => true, 'msg' => 'Error en carga de datos temporales2');
							//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales2'));				
						}
					} else {
						//$connection->rollBack();
						return array('rst' => true, 'msg' => 'Error en carga de datos temporales1');
						//echo json_encode(array('rst'=>true,'msg'=>'Error en carga de datos temporales1'));				
					}



					/* FIN CARGA A TEMPORAL */
				} else {
					return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
					//echo json_encode(array('rst'=>false,'msg'=>'Error al Cargar Datos a Temporal'));	
				}

				/*                 * ********* */

				/* $sqlDropTableFinalTmpNocPredictivo=" DROP TABLE IF EXISTS tmp_noc_predictivo_".$time;
				  $prDropTableFinalTmpNocPredictivo=$connection->prepare($sqlDropTableFinalTmpNocPredictivo);
				  $prDropTableFinalTmpNocPredictivo->execute(); */
			} else {
				return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
				//echo json_encode(array('rst'=>false,'msg'=>'Error al Crear Tabla Temporal'));	
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
			//echo json_encode(array('rst'=>false,'msg'=>'Error al Eliminar Tabla Temporal Anterior'));	
		}
	}

	public function uploadCarteraRetiro_masivo($_post, $file) {

		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];
		$formatoFechas = $_post['formatoFechas'];

		//COMPROBACIOON RUTA COBRAST SQLLITE
		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}
		$path = "../documents/retiro/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');
		}


		$time = date("Y_m_d_H_i_s");

		/*         * ***************** */
		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();


		$sqlDropTableTmpRetiro = "DROP TABLE IF EXISTS tmp_retiro_" . $time;
		$prDropTableTmpRetiro = $connection->prepare($sqlDropTableTmpRetiro);
		if ($prDropTableTmpRetiro->execute()) {

			$sqlCreateTableTmpRetiro = "CREATE TABLE tmp_retiro_" . $time . " ( 
				Inscripcion varchar(10),
				nombre_cartera varchar(50),
				Fecha_Ini_Ges	 datetime,
				Fecha_Fin_ges datetime,
				Des_Agencia varchar(100),
				Fecha_Retiro datetime,
				Motivo_Retiro varchar(100),
				idcartera int,
				idcliente_cartera int ,
				idcuenta int
				) ENGINE = InnoDB ";

			$prsqlCreateTableTmpRetiro = $connection->prepare($sqlCreateTableTmpRetiro);
			if ($prsqlCreateTableTmpRetiro->execute()) {

				/*                 * ************ */
				$sqlLoad = "";
				if( $separator == 'tab' ) {
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/retiro/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_retiro_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}else{
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/retiro/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_retiro_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}

				$prLoad = $connection->prepare($sqlLoad);

				if ($prLoad->execute()) {

					/* CARGA A TEMPORAL */

					//$connection->beginTransaction();		

					$sqlUpdate1tmp = "update tmp_retiro_" . $time . " t 
					set 
					idcartera = ( select idcartera from ca_cartera where estado = 1 and nombre_cartera = t.nombre_cartera limit 1 )";
					$prUpdate1tmp = $connection->prepare($sqlUpdate1tmp);
					if ($prUpdate1tmp->execute()) {

						$sqlUpdate2tmp = "update tmp_retiro_" . $time . " t inner join ca_cuenta cu 
						on cu.idcartera = t.idcartera and cu.numero_cuenta = t.inscripcion 
						set 
						t.idcliente_cartera = cu.idcliente_cartera,
						t.idcuenta = cu.idcuenta 
						where isnull(t.idcartera) = 0 ";
						$prUpdate2tmp = $connection->prepare($sqlUpdate2tmp);
						if ($prUpdate2tmp->execute()) {

							/*                             * ****************************DISTRIBUCION DE DATOS */
							$sqlUpdtCliCar = "update ca_cliente_cartera clicar inner join tmp_retiro_" . $time . " t on t.idcliente_cartera = clicar.idcliente_cartera 
							set clicar.retiro = 1 
							where t.idcliente_cartera is not null and t.idcartera is not null ";
							$prUpdtCliCar = $connection->prepare($sqlUpdtCliCar);
							if ($prUpdtCliCar->execute()) {

								$sqlUpdtCuenta = "update ca_cuenta cu inner join tmp_retiro_" . $time . " t on t.idcuenta = cu.idcuenta 
								set 
								cu.is_retiro = 1,
								cu.retirado = 1, 
								cu.fecha_retiro = t.fecha_retiro, 
								cu.motivo_retiro = t.motivo_retiro 
								where t.idcliente_cartera is not null and t.idcartera is not null and t.idcuenta is not null ";
								$prUpdtCuenta = $connection->prepare($sqlUpdtCuenta);
								if ($prUpdtCuenta->execute()) {
									//$connection->commit();
									return array('rst' => true, 'msg' => ' CARGA DE RETIROS CORRECTA');
								} else {
									//$connection->rollBack();
									return array('rst' => false, 'msg' => 'Error al Actualizar tabla Cuenta');
								}
							} else {
								//$connection->rollBack();
								return array('rst' => false, 'msg' => 'Error al Actualizar CliCar');
							}
							/*                             * **************************** */
						} else {
							//$connection->rollBack();
							return array('rst' => false, 'msg' => 'Error en carga de datos temporales2');
						}
					} else {
						//$connection->rollBack();
						return array('rst' => false, 'msg' => 'Error en carga de datos temporales1');
					}

					/* FIN CARGA A TEMPORAL */
				} else {
					return array('rst' => false, 'msg' => 'Error al Cargar Datos a Temporal');
					//echo json_encode(array('rst'=>false,'msg'=>'Error al Cargar Datos a Temporal'));	
				}

				/*                 * ********* */

				/* $sqlDropTableFinalTmpRetiro=" DROP TABLE IF EXISTS tmp_retiro_".$time;
				  $prDropTableFinalTmpRetiro=$connection->prepare($sqlDropTableFinalTmpRetiro);
				  $prDropTableFinalTmpRetiro->execute(); */
			} else {
				return array('rst' => false, 'msg' => 'Error al Crear Tabla Temporal');
			}
		} else {
			return array('rst' => false, 'msg' => 'Error al Eliminar Tabla Temporal Anterior');
		}
	}

	public function uploadAddCartera($_post) {

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		$codigo_cliente = $_post["codigo_cliente"];
		$numero_cuenta = $_post["numero_cuenta"];
		$codigo_operacion = $_post["codigo_operacion"];
		$usuario_creacion = $_post["usuario_creacion"];
		$nombre_cartera = $_POST["NombreCartera"];
		$moneda_cuenta = "";
		$moneda_operacion = "";

		if ($_post["moneda_cuenta"] != '-Seleccione-') {
			$moneda_cuenta = $_POST["moneda_cuenta"];
		} else {
			$moneda_cuenta = NULL;
		}
		if ($_post["moneda_operacion"] != '-Seleccione-') {
			$moneda_operacion = $_POST["moneda_operacion"];
		} else {
			$moneda_operacion = NULL;
		}

		$id_cartera = $_post['Cartera'];


		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}


		$time = date("Y_m_d_H_i_s");
		//$archivoParser = file($path);
		$archivoParser = @fopen($path, "r+");
		//$columMap = explode($_post['separator'],$archivoParser[0]);
		$columMap = explode($_post['separator'], fgets($archivoParser));
		/*         * ****** */
		fclose($archivoParser);
		/*         * ******* */

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				//$buscar=array("à","á","À","Á","é","è","É","È","í","ì","Í","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%");
				//$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_");
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");
				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
				//$item="`".$item."` VARCHAR(200) ";
			}

			return $item;
		}

		$colum = array_map("map_header", $columMap);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		array_push($columHeader, "INDEX `index_" . session_id() . "_cliente` ( `$codigo_cliente` ASC ) ");
		array_push($columHeader, "INDEX `index_" . session_id() . "_cuenta` ( `$numero_cuenta` ASC ) ");
		if ($_POST["codigo_operacion"] != '-Seleccione-') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_operacion` ( `$codigo_operacion` ASC ) ");
		} else {
			$codigo_operacion = '';
		}
		if ($_POST["moneda_cuenta"] != '-Seleccione-') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_cuenta` ( `$moneda_cuenta` ASC ) ");
		} else {
			$moneda_cuenta = '';
		}
		if ($_POST["moneda_operacion"] != '-Seleccione-') {
			array_push($columHeader, "INDEX `index_" . session_id() . "_moneda_operacion` ( `$moneda_operacion` ASC ) ");
		} else {
			$moneda_operacion = '';
		}
		/*         * ********** */

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		$parserCliente = str_replace("\\", "", $_post["data_cliente"]);
		$parserCuenta = str_replace("\\", "", $_post["data_cuenta"]);
		$parserOperacion = str_replace("\\", "", $_post["data_operacion"]);
		$parserTelefono = str_replace("\\", "", $_post["data_telefono"]);
		$parserDireccion = str_replace("\\", "", $_post["data_direccion"]);
		$parserAdicionales = str_replace("\\", "", $_post["data_adicionales"]);

		$parserHeader = implode(",", $colum);

		$jsonCliente = json_decode(str_replace("\\", "", $_post["data_cliente"]), true);
		$jsonCuenta = json_decode(str_replace("\\", "", $_post["data_cuenta"]), true);
		$jsonOperacion = json_decode(str_replace("\\", "", $_post["data_operacion"]), true);
		$jsonTelefono = json_decode(str_replace("\\", "", $_post["data_telefono"]), true);
		$jsonDireccion = json_decode(str_replace("\\", "", $_post["data_direccion"]), true);
		$jsonAdicionales = json_decode(str_replace("\\", "", $_post["data_adicionales"]), true);

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlDropTableTMPCartera = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
		$prDropTableTMPCartera = $connection->prepare($sqlDropTableTMPCartera);
		if ($prDropTableTMPCartera->execute()) {

			$sqlCreateTabelTMPCartera = " CREATE TABLE tmpcartera_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE=utf8_spanish_ci ";
			$prsqlCreateTabelTMPCartera = $connection->prepare($sqlCreateTabelTMPCartera);
			if ($prsqlCreateTabelTMPCartera->execute()) {

				$sqlLoadDataInFileUC = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
				 INTO TABLE tmpcartera_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				$prLoadDataInFileUC = $connection->prepare($sqlLoadDataInFileUC);
				if ($prLoadDataInFileUC->execute()) {

					//$connection->beginTransaction();
					//$id_cartera=$connection->lastInsertId();

					$insertCliente = " ";

					$campoTableClienteTMP = array();
					$campoTableCliente = array();

					for ($i = 0; $i < count($jsonCliente); $i++) {
						if ($jsonCliente[$i]['campoT'] == 'codigo') {
							array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
							array_push($campoTableClienteTMP, " TRIM( " . $jsonCliente[$i]['dato'] . " ) ");
						} else {
							array_push($campoTableCliente, $jsonCliente[$i]['campoT']);
							array_push($campoTableClienteTMP, $jsonCliente[$i]['dato']);
						}
					}

					$insertCliente = " INSERT IGNORE INTO ca_cliente ( idservicio," . implode(",", $campoTableCliente) . " ) 
						SELECT " . $_post['Servicio'] . "," . implode(",", $campoTableClienteTMP) . " FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 GROUP BY TRIM($codigo_cliente) ";

					$prInsertCliente = $connection->prepare($insertCliente);
					if ($prInsertCliente->execute()) {

						$InsertClienteCartera = " INSERT IGNORE INTO ca_cliente_cartera ( codigo_cliente,idcartera,usuario_creacion,fecha_creacion ) 
							SELECT TRIM($codigo_cliente)," . $id_cartera . "," . $usuario_creacion . ",NOW() FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 GROUP BY TRIM($codigo_cliente) ";

						$prInsertClienteCartera = $connection->prepare($InsertClienteCartera);
						if ($prInsertClienteCartera->execute()) {

							$campoTableCuentaTMP = array();
							$campoTableCuenta = array();


							for ($i = 0; $i < count($jsonCuenta); $i++) {
								if ($jsonCuenta[$i]['campoT'] == 'total_deuda') {
									array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
									array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
								} else if ($jsonCuenta[$i]['campoT'] == 'numero_cuenta') {
									array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
									array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
								} else if ($jsonCuenta[$i]['campoT'] == 'moneda') {
									array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
									array_push($campoTableCuentaTMP, " TRIM( " . $jsonCuenta[$i]['dato'] . " ) ");
								} else if ($jsonCuenta[$i]['campoT'] == 'total_comision') {
									array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
									array_push($campoTableCuentaTMP, " SUM( " . $jsonCuenta[$i]['dato'] . " ) ");
								} else {
									array_push($campoTableCuenta, $jsonCuenta[$i]['campoT']);
									array_push($campoTableCuentaTMP, $jsonCuenta[$i]['dato']);
								}
							}

							$insertCuenta = "";
							if ($_post['moneda_cuenta'] != '-Seleccione-') {
								$insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )  
									SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . " 
									FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0 
									GROUP BY TRIM($numero_cuenta), TRIM($moneda_cuenta) ";
							} else {
								$insertCuenta = " INSERT IGNORE INTO ca_cuenta ( codigo_cliente, idcartera, estado, fecha_creacion, usuario_creacion, " . implode(",", $campoTableCuenta) . " )  
									SELECT TRIM($codigo_cliente), $id_cartera, 1, NOW(), $usuario_creacion, " . implode(",", $campoTableCuentaTMP) . " 
									FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM($codigo_cliente) )>0 AND LENGTH( TRIM( $numero_cuenta ) )>0 
									GROUP BY TRIM($numero_cuenta) ";
							}

							$prInsertCuenta = $connection->prepare($insertCuenta);
							if ($prInsertCuenta->execute()) {

								if (count($jsonOperacion) > 0) {
									$campoTableOperacionTMP = array();
									$campoTableOperacion = array();

									/*                                     * *** */
									$fieldTramo = "";
									/*                                     * ** */

									for ($i = 0; $i < count($jsonOperacion); $i++) {

										if ($jsonOperacion[$i]['campoT'] == 'codigo_operacion') {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
										} else if ($jsonOperacion[$i]['campoT'] == 'moneda') {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
										} else if ($jsonOperacion[$i]['campoT'] == 'tramo') {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, " TRIM( " . $jsonOperacion[$i]['dato'] . " ) ");
											$fieldTramo = $jsonOperacion[$i]['dato'];
										} else if ($jsonOperacion[$i]['campoT'] == 'fecha_vencimiento') {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
										} else if ($jsonOperacion[$i]['campoT'] == 'fecha_asignacion') {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, " IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 3,  CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,7),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,4,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,2)) , IF( LOCATE('/', " . $jsonOperacion[$i]['dato'] . " ) = 5, CONCAT_WS('-',SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,1,4),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,6,2),SUBSTRING( " . $jsonOperacion[$i]['dato'] . " ,9,2)) , " . $jsonOperacion[$i]['dato'] . " ) ) ");
										} else {
											array_push($campoTableOperacion, $jsonOperacion[$i]['campoT']);
											array_push($campoTableOperacionTMP, $jsonOperacion[$i]['dato']);
										}
									}

									$insertOperacion = " INSERT IGNORE INTO ca_detalle_cuenta( " . implode(",", $campoTableOperacion) . ", numero_cuenta,codigo_cliente, idcartera, usuario_creacion,fecha_creacion ) 
										SELECT " . implode(",", $campoTableOperacionTMP) . ", TRIM($numero_cuenta), TRIM($codigo_cliente) , $id_cartera , $usuario_creacion , NOW() 
										FROM tmpcartera_" . session_id() . "_" . $time . " 
										WHERE LENGTH( TRIM( $codigo_cliente ) ) > 0 AND LENGTH( TRIM( $numero_cuenta ) ) > 0 AND LENGTH( TRIM( $codigo_operacion ) ) > 0 
										GROUP BY TRIM( $codigo_operacion ) ";

									$prInsertOperacion = $connection->prepare($insertOperacion);
									if ($prInsertOperacion->execute()) {

										/*                                         * ********* */
										if (trim($fieldTramo) != "") {
											$InsertTramo = " INSERT IGNORE INTO ca_tramo ( tramo, fecha_creacion, usuario_creacion, idservicio, tipo ) 
												SELECT DISTINCT( TRIM($fieldTramo) ),NOW(),$usuario_creacion , " . $_post['Servicio'] . " ,'TRAMO'
												FROM tmpcartera_" . session_id() . "_" . $time . " WHERE LENGTH( TRIM( $fieldTramo ) ) > 0 ";
											$prInsertTramo = $connection->prepare($InsertTramo);
											if ($prInsertTramo->execute()) {

											} else {
												//$connection->rollBack();
												echo json_encode(array('rst' => false, 'msg' => 'Error al insertar tramos'));
												exit();
											}
										}
										/*                                         * ************ */

										$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);

										foreach ($jsonTelefono as $index => $value) {
											$fieldTelefono = array();
											$fieldTelefonoTMP = array();
											$fieldReferenciaTelefono = "";
											if (count($value) > 0) {

												foreach ($value as $i => $v) {
													array_push($fieldTelefono, $i);
													array_push($fieldTelefonoTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													if ($i == "numero") {
														$fieldReferenciaTelefono = $v;
													}
												}

												$insertTelefono = "";

												if (trim($fieldReferenciaTelefono) == '') {

													$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
															SELECT DISTINCT TRIM($codigo_cliente), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
															FROM tmpcartera_" . session_id() . "_" . $time . " ;";
												} else {

													$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
															SELECT TRIM( $codigo_cliente ), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
															FROM tmpcartera_" . session_id() . "_" . $time . " 
															WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 GROUP BY TRIM($fieldReferenciaTelefono) ;";
												}
												$prInsertTelefono = $connection->prepare($insertTelefono);
												if ($prInsertTelefono->execute()) {

												} else {
													//$connection->rollBack();
													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();
													echo json_encode(array('rst' => false, 'msg' => 'Error al insertar telefono'));
													exit();
												}
											}
										}

										$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);

										foreach ($jsonDireccion as $index => $value) {
											$fieldDireccion = array();
											$fieldDireccionTMP = array();
											$fieldDireccionTMPIntersec = array();
											$fieldReferenciaDireccion = "";
											$fieldUbigeo = "";
											$FieldDepartamentoTMP = "";
											$FieldProvinciaTMP = "";
											$FieldDistritoTMP = "";
											if (count($value) > 0) {

												foreach ($value as $i => $v) {

													if ($i == "direccion") {
														$fieldReferenciaDireccion = $v;
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													} else if ($i == "ubigeo") {
														$fieldUbigeo = $v;
														$FieldDepartamentoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',1 ) ";
														$FieldDistritoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',-1 ) ";
														$FieldProvinciaTMP = " SUBSTRING_INDEX( SUBSTRING_INDEX(tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',2),'-',-1) ";
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
														array_push($fieldDireccion, "departamento");
														array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
														array_push($fieldDireccion, "provincia");
														array_push($fieldDireccionTMP, $FieldProvinciaTMP);
														array_push($fieldDireccion, "distrito");
														array_push($fieldDireccionTMP, $FieldDistritoTMP);
													} else if ($i == "departamento") {
														if (!array_search("departamento", $fieldDireccion)) {
															array_push($fieldDireccion, $i);
															array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
														}
													} else if ($i == "provincia") {
														if (!array_search("provincia", $fieldDireccion)) {
															array_push($fieldDireccion, $i);
															array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
														}
													} else if ($i == "distrito") {
														if (!array_search("distrito", $fieldDireccion)) {
															array_push($fieldDireccion, $i);
															array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
														}
													} else {
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													}
												}

												$insertDireccion = "";

												if (trim($fieldReferenciaDireccion) == '') {

													$insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " ) 
															SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . " 
															FROM tmpcartera_" . session_id() . "_" . $time . " ;";
												} else {

													$insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen ,idtipo_referencia , usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " ) 
															SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1, " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
															FROM tmpcartera_" . session_id() . "_" . $time . " 
															WHERE TRIM( $fieldReferenciaDireccion )!='' ;";
												}

												$prInsertDireccion = $connection->prepare($insertDireccion);
												if ($prInsertDireccion->execute()) {

												} else {
													//$connection->rollBack();

													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();

													echo json_encode(array('rst' => false, 'msg' => 'Error al insertar direccion'));
													exit();
												}
											}
										}

										$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);

										$idDatosAdicionales = array();
										$idTMPDatosAdicionales = array();
										$groupTMPDatosAdicionales = array();
										if ($_post['moneda_cuenta'] != '-Seleccione-') {
											$idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta, moneda", "ca_datos_adicionales_detalle_cuenta" => "codigo_cliente, codigo_operacion");
											$idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
											$groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
										} else {
											$idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta ", "ca_datos_adicionales_detalle_cuenta" => " codigo_cliente, codigo_operacion ");
											$idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
											$groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
										}

										foreach ($jsonAdicionales as $index => $value) {
											$fieldCabecera = array();
											$fieldCabeceraTMP = array();
											$fieldValueTMP = array();

											if (count($value) > 0) {

												for ($i = 0; $i < count($value); $i++) {
													array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
													array_push($fieldCabecera, $value[$i]['campoT']);
													array_push($fieldCabeceraTMP, $value[$i]['dato']);
												}

												$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
														VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
												$prInsertCabeceras = $connection->prepare($insertCabeceras);
												if ($prInsertCabeceras->execute()) {

													$insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " ) 
															SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
															FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $groupTMPDatosAdicionales[$index] . "";

													$prInsertAdicionales = $connection->prepare($insertAdicionales);
													if ($prInsertAdicionales->execute()) {

													} else {
														//$connection->rollBack();

														$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
														@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
														@$prsqlDropTableTMPCarteraRollBack->execute();

														echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
														exit();
													}
												} else {
													//$connection->rollBack();

													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();

													echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
													exit();
												}
											}
										}

										//$connection->commit();
										echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
									} else {
										//$connection->rollBack();

										$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
										@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
										@$prsqlDropTableTMPCarteraRollBack->execute();

										echo json_encode(array('rst' => false, 'msg' => 'No selecciono cabeceras de operacion'));
									}
								} else {

									$referenciaTelefono = array('telefono_predeterminado' => 3, 'telefono_domicilio' => 2, 'telefono_oficina' => 1, 'telefono_negocio' => 4, 'telefono_laboral' => 5);

									foreach ($jsonTelefono as $index => $value) {
										$fieldTelefono = array();
										$fieldTelefonoTMP = array();
										$fieldReferenciaTelefono = "";
										if (count($value) > 0) {

											foreach ($value as $i => $v) {
												array_push($fieldTelefono, $i);
												array_push($fieldTelefonoTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
												if ($i == "numero") {
													$fieldReferenciaTelefono = $v;
												}
											}

											$insertTelefono = "";

											if (trim($fieldReferenciaTelefono) == '') {


												$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
													SELECT DISTINCT TRIM($codigo_cliente), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
													FROM tmpcartera_" . session_id() . "_" . $time . " ;";
											} else {

												$insertTelefono = " INSERT IGNORE INTO ca_telefono ( codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, usuario_creacion, fecha_creacion, " . implode(",", $fieldTelefono) . " ) 
													SELECT TRIM( $codigo_cliente ), 1, $id_cartera, " . $referenciaTelefono[$index] . ", 2, $usuario_creacion, NOW(), " . implode(",", $fieldTelefonoTMP) . "
													FROM tmpcartera_" . session_id() . "_" . $time . " 
													WHERE TRIM( $fieldReferenciaTelefono )!='' AND LENGTH( TRIM( $fieldReferenciaTelefono) )>6 GROUP BY TRIM($fieldReferenciaTelefono) ;";
											}
											$prInsertTelefono = $connection->prepare($insertTelefono);
											if ($prInsertTelefono->execute()) {

											} else {
												//$connection->rollBack();
												$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
												@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
												@$prsqlDropTableTMPCarteraRollBack->execute();
												echo json_encode(array('rst' => false, 'msg' => 'Error al insertar telefono'));
												exit();
											}
										}
									}

									$referenciaDireccion = array('direccion_predeterminado' => 3, 'direccion_domicilio' => 2, 'direccion_oficina' => 1, 'direccion_negocio' => 4, 'direccion_laboral' => 5);

									foreach ($jsonDireccion as $index => $value) {
										$fieldDireccion = array();
										$fieldDireccionTMP = array();
										$fieldDireccionTMPIntersec = array();
										$fieldReferenciaDireccion = "";
										$fieldUbigeo = "";
										$FieldDepartamentoTMP = "";
										$FieldProvinciaTMP = "";
										$FieldDistritoTMP = "";
										if (count($value) > 0) {

											foreach ($value as $i => $v) {

												if ($i == "direccion") {
													$fieldReferenciaDireccion = $v;
													array_push($fieldDireccion, $i);
													array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
												} else if ($i == "ubigeo") {
													$fieldUbigeo = $v;
													$FieldDepartamentoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',1 ) ";
													$FieldDistritoTMP = " SUBSTRING_INDEX( tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',-1 ) ";
													$FieldProvinciaTMP = " SUBSTRING_INDEX( SUBSTRING_INDEX(tmpcartera_" . session_id() . "_" . $time . "." . $v . ",'-',2),'-',-1) ";
													array_push($fieldDireccion, $i);
													array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													array_push($fieldDireccion, "departamento");
													array_push($fieldDireccionTMP, $FieldDepartamentoTMP);
													array_push($fieldDireccion, "provincia");
													array_push($fieldDireccionTMP, $FieldProvinciaTMP);
													array_push($fieldDireccion, "distrito");
													array_push($fieldDireccionTMP, $FieldDistritoTMP);
												} else if ($i == "departamento") {
													if (!array_search("departamento", $fieldDireccion)) {
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													}
												} else if ($i == "provincia") {
													if (!array_search("provincia", $fieldDireccion)) {
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													}
												} else if ($i == "distrito") {
													if (!array_search("distrito", $fieldDireccion)) {
														array_push($fieldDireccion, $i);
														array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
													}
												} else {
													array_push($fieldDireccion, $i);
													array_push($fieldDireccionTMP, "tmpcartera_" . session_id() . "_" . $time . "." . $v);
												}
											}

											$insertDireccion = "";

											if (trim($fieldReferenciaDireccion) == '') {

												$insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen, idtipo_referencia, usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " ) 
													SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1 , " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . " 
													FROM tmpcartera_" . session_id() . "_" . $time . " ;";
											} else {

												$insertDireccion = " INSERT IGNORE INTO ca_direccion ( codigo_cliente, idcartera, idorigen ,idtipo_referencia , usuario_creacion, fecha_creacion, " . implode(",", $fieldDireccion) . " ) 
													SELECT DISTINCT TRIM( $codigo_cliente ), $id_cartera, 1, " . $referenciaDireccion[$index] . ", $usuario_creacion, NOW(), " . implode(",", $fieldDireccionTMP) . "
													FROM tmpcartera_" . session_id() . "_" . $time . " 
													WHERE TRIM( $fieldReferenciaDireccion )!='' ;";
											}

											$prInsertDireccion = $connection->prepare($insertDireccion);
											if ($prInsertDireccion->execute()) {

											} else {
												//$connection->rollBack();

												$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
												@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
												@$prsqlDropTableTMPCarteraRollBack->execute();

												echo json_encode(array('rst' => false, 'msg' => 'Error al insertar direccion'));
												exit();
											}
										}
									}

									$tipoDatosAdicionales = array("ca_datos_adicionales_cliente" => 1, "ca_datos_adicionales_cuenta" => 2, "ca_datos_adicionales_detalle_cuenta" => 3);
									$idDatosAdicionales = array();
									$idTMPDatosAdicionales = array();
									$groupTMPDatosAdicionales = array();
									if ($_post['moneda_cuenta'] != '-Seleccione-') {
										$idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta, moneda", "ca_datos_adicionales_detalle_cuenta" => "codigo_cliente, codigo_operacion");
										$idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
										$groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ), TRIM( " . $moneda_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
									} else {
										$idDatosAdicionales = array("ca_datos_adicionales_cliente" => "codigo_cliente", "ca_datos_adicionales_cuenta" => "codigo_cliente, numero_cuenta ", "ca_datos_adicionales_detalle_cuenta" => " codigo_cliente, codigo_operacion ");
										$idTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_cliente . " ), TRIM( " . $codigo_operacion . " ) ");
										$groupTMPDatosAdicionales = array("ca_datos_adicionales_cliente" => "TRIM( " . $codigo_cliente . " )", "ca_datos_adicionales_cuenta" => " TRIM( " . $numero_cuenta . " ) ", "ca_datos_adicionales_detalle_cuenta" => " TRIM( " . $codigo_operacion . " ) ");
									}
									/*                                     * ********** */

									foreach ($jsonAdicionales as $index => $value) {
										$fieldCabecera = array();
										$fieldCabeceraTMP = array();
										$fieldValueTMP = array();
										if ($index == 'ca_datos_adicionales_cliente' || $index == 'ca_datos_adicionales_cuenta') {
											if (count($value) > 0) {

												for ($i = 0; $i < count($value); $i++) {
													array_push($fieldValueTMP, "'" . $value[$i]['label'] . "'");
													array_push($fieldCabecera, $value[$i]['campoT']);
													array_push($fieldCabeceraTMP, $value[$i]['dato']);
												}

												$insertCabeceras = " INSERT IGNORE INTO ca_cabeceras ( idservicio, idtipo_datos_adicionales, idcartera, fecha_creacion, usuario_creacion, " . implode(",", $fieldCabecera) . " ) 
													VALUES( " . $_post['Servicio'] . " , " . $tipoDatosAdicionales[$index] . ", $id_cartera, NOW(), $usuario_creacion, " . implode(",", $fieldValueTMP) . " ) ";
												$prInsertCabeceras = $connection->prepare($insertCabeceras);
												if ($prInsertCabeceras->execute()) {

													$insertAdicionales = " INSERT IGNORE INTO " . $index . " ( idcartera, " . $idDatosAdicionales[$index] . " , " . implode(",", $fieldCabecera) . " ) 
														SELECT $id_cartera, " . $idTMPDatosAdicionales[$index] . " , " . implode(",", $fieldCabeceraTMP) . "
														FROM tmpcartera_" . session_id() . "_" . $time . " GROUP BY " . $groupTMPDatosAdicionales[$index] . "";

													$prInsertAdicionales = $connection->prepare($insertAdicionales);
													if ($prInsertAdicionales->execute()) {

													} else {
														//$connection->rollBack();

														$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
														@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
														@$prsqlDropTableTMPCarteraRollBack->execute();

														echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos adicionales'));
														exit();
													}
												} else {
													//$connection->rollBack();

													$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
													@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
													@$prsqlDropTableTMPCarteraRollBack->execute();

													echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cabeceras'));
													exit();
												}
											}
										}
									}

									//$connection->commit();
									echo json_encode(array('rst' => true, 'msg' => 'Cartera cargada correctamente'));
								}
							} else {
								//$connection->rollBack();

								$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
								@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
								@$prsqlDropTableTMPCarteraRollBack->execute();

								echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
							}
						} else {
							//$connection->rollBack();

							$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
							@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
							@$prsqlDropTableTMPCarteraRollBack->execute();

							echo json_encode(array('rst' => false, 'msg' => 'Error insertar datos de distribucion'));
							exit();
						}
					} else {
						//$connection->rollBack();

						$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
						@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
						@$prsqlDropTableTMPCarteraRollBack->execute();

						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar cliente'));
					}
				} else {
					$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
					@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
					@$prsqlDropTableTMPCarteraRollBack->execute();
					echo json_encode(array('rst' => false, 'msg' => 'Error load data infile'));
				}
			} else {
				$sqlDropTableTMPCarteraRollBack = " DROP TABLE IF EXISTS tmpcartera_" . session_id() . "_" . $time . " ";
				@$prsqlDropTableTMPCarteraRollBack = $connection->prepare($sqlDropTableTMPCarteraRollBack);
				@$prsqlDropTableTMPCarteraRollBack->execute();
				echo json_encode(array('rst' => false, 'msg' => 'Error create temporary table'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al eliminar tabla'));
		}
	}

	public function uploadCarteraRRLL($_post) {

		$cartera = $_post['Cartera'];
		$campania = $_post['Campania'];
		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];

		$numero_cuenta = '';
		$moneda = '';
		$telefono = '';
		$contacto = '';

		$parserRRLL = str_replace("\\", "", $_post['data_rrll']);
		$jsonRRLL = json_decode($parserRRLL, true);

		for ($i = 0; $i < count($jsonRRLL); $i++) {
			if ($jsonRRLL[$i]['campoT'] == 'numero_cuenta') {
				$numero_cuenta = $jsonRRLL[$i]['dato'];
			} else if ($jsonRRLL[$i]['campoT'] == 'moneda') {
				$moneda = $jsonRRLL[$i]['dato'];
			} else if ($jsonRRLL[$i]['campoT'] == 'telefono') {
				$telefono = $jsonRRLL[$i]['dato'];
			} else if ($jsonRRLL[$i]['campoT'] == 'contacto') {
				$contacto = $jsonRRLL[$i]['dato'];
			}
		}

		if (trim($numero_cuenta) == '') {
			echo json_encode(array('rst' => false, 'msg' => 'Seleccione numero de cuenta'));
			exit();
		}

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}

		$path = "../documents/carteras/" . $_post["NombreServicio"] . "/" . $_post["file"];
		if (!file_exists($path)) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();
		}

		$time = date("Y_m_d_H_i_s");
		$archivo = @fopen($path, "r+");
		$colum = explode($separator, fgets($archivo));
		if (count($colum) < 2) {
			echo json_encode(array('rst' => false, 'msg' => 'Caracter separador incorrecto'));
			exit();
		}

		function map_header($n) {
			$item = "";
			if (trim(utf8_encode($n)) != "") {
				$buscar = array("à", "á", "À", "Á", "é", "è", "É", "È", "í", "ì", "Í", "Ì", "ó", "ò", "Ó", "Ò", "ú", "ù", "Ú", "Ù", ".", "#", " ", "/", "ñ", "Ñ", "@", "(", ")", "$", "&", "%", "'", '"', "?", "¿", "!", "¡", "[", "]", "-", "¥");
				$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", "_", "_", "_", "_", "n", "N", "_", "_", "_", "_", "&", "_", "", '', "", "", "", "", "", "", "", "N");

				$item = str_replace($buscar, $cambia, trim(utf8_encode($n)));
			}

			return $item;
		}

		$colum = array_map("map_header", $colum);
		$parserHeader = implode(",", $colum);
		$columHeader = array();
		$countHeaderFalse = 0;

		for ($i = 0; $i < count($colum); $i++) {
			if ($colum[$i] != "") {
				array_push($columHeader, "`" . $colum[$i] . "` VARCHAR(200) ");
			} else {
				$countHeaderFalse++;
			}
		}

		if ($countHeaderFalse > 0) {
			echo json_encode(array('rst' => false, 'msg' => 'La cartera tiene ' . $countHeaderFalse . ' cabeceras vacias '));
			exit();
		}

		fclose($archivo);

		/*         * ***************** */

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTable = " CREATE TEMPORARY TABLE tmprrll_" . session_id() . "_" . $time . " ( " . implode(",", $columHeader) . " ) COLLATE=utf8_spanish_ci ";
		//echo $sqlCreateTable;
		$prCreateTable = $connection->prepare($sqlCreateTable);
		if ($prCreateTable->execute()) {

			$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/carteras/" . $_post['NombreServicio'] . "/" . $_post["file"] . "'
			  INTO TABLE tmprrll_" . session_id() . "_" . $time . " FIELDS TERMINATED BY '" . $_post['separator'] . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			$prLoad = $connection->prepare($sqlLoad);
			if ($prLoad->execute()) {

				//$connection->beginTransaction();

				$insertCarteraReclamo = " INSERT INTO ca_cartera_rrll ( idcartera, fecha_carga, usuario_creacion, fecha_creacion, rrll, cabeceras ) 
					VALUES ( ?,NOW(),?,NOW(),?,? ) ";
				$prInsertCartera = $connection->prepare($insertCarteraReclamo);
				$prInsertCartera->bindParam(1, $cartera, PDO::PARAM_INT);
				$prInsertCartera->bindParam(2, $usuario_creacion, PDO::PARAM_INT);
				$prInsertCartera->bindParam(3, $parserRRLL, PDO::PARAM_STR);
				$prInsertCartera->bindParam(4, $parserHeader, PDO::PARAM_STR);
				if ($prInsertCartera->execute()) {
					$sqlInsertReclamos = "";
					if ($moneda == '' && $telefono == '') {
						$sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE TRIM($numero_cuenta) = cu.numero_cuenta LIMIT 1 )
							WHERE idcartera = ? ";
					} else if ($moneda == '' && $telefono != '') {
						$sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($telefono)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.telefono)) LIMIT 1 )  
							WHERE idcartera = ? ";
					} else if ($moneda != '' && $telefono == '') {
						$sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.moneda)) LIMIT 1 )  
							WHERE idcartera = ? ";
					} else if ($moneda != '' && $telefono != '') {
						$sqlInsertReclamos = " UPDATE ca_cuenta cu SET contacto = ( SELECT TRIM($contacto) FROM tmprrll_" . session_id() . "_" . $time . " WHERE CONCAT_WS('-',TRIM($numero_cuenta),TRIM($moneda),TRIM($telefono)) = CONCAT_WS('-',TRIM(cu.numero_cuenta),TRIM(cu.moneda),TRIM(cu.telefono)) LIMIT 1 ) 
							WHERE idcartera = ? ";
					} else {
						//$connection->rollBack();
						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar reclamos'));
					}
					//echo $sqlInsertReclamos;
					$prInsertReclamos = $connection->prepare($sqlInsertReclamos);
					$prInsertReclamos->bindParam(1, $cartera, PDO::PARAM_INT);
					if ($prInsertReclamos->execute()) {

						//$connection->commit();
						echo json_encode(array('rst' => true, 'msg' => 'Datos de cartera insertados correctamente'));
					} else {
						//$connection->rollBack();
						echo json_encode(array('rst' => false, 'msg' => 'Error al insertar reclamos'));
					}
				} else {
					//$connection->rollBack();
					echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos de cartera'));
				}
			} else {
				echo json_encode(array('rst' => false, 'msg' => 'Error al insertar datos a tabla temporal'));
			}
		} else {
			echo json_encode(array('rst' => false, 'msg' => 'Error al crear tabla temporal'));
		}
	}

	public function uploadDistribucionMecanico ( $_post ) {

		$cartera = $_post['idcartera'];
		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separador = $_post['separador'];
		$operadores = json_decode(str_replace("\\","",$_post['operadores']),true);
		$modo = $_post['modo'];

		$codigo_cliente = '';
		$codigo_usuario = '';

		$parserDis = str_replace("\\","",$_post['data_generate']);
		$jsonDis = json_decode($parserDis,true);

		for( $i=0;$i<count($jsonDis);$i++ ) {
			if( $jsonDis[$i]['campoT'] == 'codigo_cliente' ) {
				$codigo_cliente = $jsonDis[$i]['dato'];
			}else if( $jsonDis[$i]['campoT'] == 'codigo' ) {
				$codigo_usuario = $jsonDis[$i]['dato'];
			}
		}

		if( trim($codigo_cliente) == '' ) {
			echo json_encode(array('rst'=>false,'msg'=>'Seleccione codigo de cliente'));
			exit();
		}

		$confCobrast=parse_ini_file('../conf/cobrast.ini',true);

		if( !isset($confCobrast['ruta_cobrast']) ){
			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
			exit();	
		}else if( !isset($confCobrast['ruta_cobrast']['document_root_cobrast']) ) {
			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
			exit();	
		}else if( !isset($confCobrast['ruta_cobrast']['nombre_carpeta']) ) {
			echo json_encode(array('rst'=>false,'msg'=>'Error al leer el archivo de configuracion'));
			exit();	
		}

		$path="../documents/carteras/".$_post["NombreServicio"]."/".$_post["file"];
		if( !file_exists($path) ) {
			echo json_encode(array('rst'=>false,'msg'=>'La cartera subida no existe o fue removida, intente subir otra vez la cartera'));
			exit();	
		}

		$time=date("Y_m_d_H_i_s");
		$archivo = @fopen($path,"r+");
		$colum = array();
		if( $separador == 'tab' ) {
			$colum = explode("\t",fgets($archivo));
		}else{
			$colum = explode($separador,fgets($archivo));
		}

		if( count( $colum )<2 ) {
			echo json_encode(array('rst'=>false,'msg'=>'Caracter separador incorrecto'));
			exit();
		}

		function MapArrayCodigoClienteDis ( $n ) {
			return "'".$n['codigo_cliente']."'";
		};

		function map_header( $n ) {
			$item="";
			if( trim(utf8_encode($n))!="" ){
				$buscar=array("à","á","À","�?","é","è","É","È","í","ì","�?","Ì","ó","ò","Ó","Ò","ú","ù","Ú","Ù",".","#"," ","/","ñ","Ñ","@","(",")","$","&","%","'",'"',"?","¿","!","¡","[","]","-");
				$cambia=array("a","a","A","A","e","e","E","E","i","i","I","I","o","o","O","O","u","u","U","U","_","_","_","_","n","N","_","_","_","_","&","_","",'',"","","","","","","");

				$item=str_replace($buscar,$cambia,trim(utf8_encode($n)));
			}

			return $item;
		}
		$colum = array_map("map_header",$colum);
		$parserHeader = implode(",",$colum);
		$columHeader=array();
		$countHeaderFalse=0;

		for( $i=0;$i<count($colum);$i++ ) {
			if( $colum[$i]!="" ) {
				array_push($columHeader,"`".$colum[$i]."` VARCHAR(200) ");
			}else{
				$countHeaderFalse++;	
			}
		}

		/********/
		array_push($columHeader," idusuario_servicio INT ");
		/**********/
		array_push($columHeader," INDEX( ".$codigo_cliente." ASC ) ");

		if( $countHeaderFalse>0 ) {
			echo json_encode(array('rst'=>false,'msg'=>'La cartera tiene '.$countHeaderFalse.' cabeceras vacias '));
			exit();
		}

		fclose($archivo);

		/********************/

		$factoryConnection= FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$sqlCreateTable=" CREATE TEMPORARY TABLE tmpdis_".session_id()."_".$time." ( ".implode(",",$columHeader)." ) ";
		//echo $sqlCreateTable;
		$prCreateTable = $connection->prepare($sqlCreateTable);
		if( $prCreateTable->execute() ) {

			$sqlLoad="";
			if( $separador == 'tab' ) {
				$sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
				INTO TABLE tmpdis_".session_id()."_".$time." FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			}else{
				$sqlLoad=" LOAD DATA INFILE '".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/documents/carteras/".$_post['NombreServicio']."/".$_post["file"]."'
				INTO TABLE tmpdis_".session_id()."_".$time." FIELDS TERMINATED BY '".$_post['separator']."' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
			}

			$prLoad = $connection->prepare($sqlLoad);
			if( $prLoad->execute() ) {

				$connection->beginTransaction();



				if( count($operadores) > 0 ) {

					$sqlCountTmp = "  SELECT COUNT(*) AS 'COUNT' FROM tmpdis_".session_id()."_".$time." WHERE TRIM(".$codigo_cliente.") != '' ";
					$prCountTmp = $connection->prepare($sqlCountTmp);
					$prCountTmp->execute();
					$dataCount = $prCountTmp->fetchAll(PDO::FETCH_ASSOC);
					$countTMP = (int)$dataCount[0]['COUNT'];

					$cantidadClientes = ceil( $countTMP / count($operadores) );

					if( $modo == 'cartera' ) {


						$inicio = 0;
						for( $i=0;$i<count($operadores);$i++ ) {

							$sqlLimitCliente = " SELECT ".$codigo_cliente." AS 'codigo_cliente' FROM tmpdis_".session_id()."_".$time." LIMIT ".$inicio.", ".$cantidadClientes."  ";
							$prLimitCliente = $connection->prepare($sqlLimitCliente);
							$prLimitCliente->execute();
							$dataLimitCliente = $prLimitCliente->fetchAll(PDO::FETCH_ASSOC);

							$clientesDis = array_map("MapArrayCodigoClienteDis",$dataLimitCliente);

							if( count($clientesDis) > 0 ) {
								$sqlDistribucion = " UPDATE ca_cliente_cartera 
								SET idusuario_servicio = ".$operadores[$i]['operador']."
								WHERE idcartera = ".$cartera." AND idusuario_servicio = 0 AND codigo_cliente IN ( ".implode(",",$clientesDis)." ) ";

								$prUpdateClienteCartera = $connection->prepare($sqlDistribucion);
								if( $prUpdateClienteCartera->execute() ){

								}else{
									$connection->rollBack();
									echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar distribucion'));
									exit();
								}
							}
							$inicio = $inicio + $cantidadClientes ;
						}

					}else{

						$sqlClearTmp = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = 0 WHERE idcartera = ".$cartera." ";
						$prClearTmp = $connection->prepare($sqlClearTmp);
						if( $prClearTmp->execute() ) {

						}else{
							$connection->rollBack();
							echo json_encode(array('rst'=>false,'msg'=>'Error al limpiar temporal'));
							exit;
						}

						$inicio = 0;
						for( $i=0;$i<count($operadores);$i++ ) {

							$sqlLimitCliente = " SELECT ".$codigo_cliente." AS 'codigo_cliente' FROM tmpdis_".session_id()."_".$time." LIMIT ".$inicio.", ".$cantidadClientes."  ";
							$prLimitCliente = $connection->prepare($sqlLimitCliente);
							$prLimitCliente->execute();
							$dataLimitCliente = $prLimitCliente->fetchAll(PDO::FETCH_ASSOC);

							$clientesDis = array_map("MapArrayCodigoClienteDis",$dataLimitCliente);

							if( count($clientesDis) > 0 ) {
								$sqlDistribucion = " UPDATE ca_cliente_cartera  								
								SET idusuario_servicio_especial = ".$operadores[$i]['operador']."
								WHERE idcartera = ".$cartera." AND codigo_cliente IN ( ".implode(",",$clientesDis)." ) ";

								$prUpdateClienteCartera = $connection->prepare($sqlDistribucion);
								if( $prUpdateClienteCartera->execute() ){

								}else{
									$connection->rollBack();
									echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar distribucion'));
									exit();
								}
							}
							$inicio = $inicio + $cantidadClientes ;
						}

					}

				}else{

					$sqlUpdateTmpIdUsuarioServicio = " UPDATE tmpdis_".session_id()."_".$time." tmp
					SET idusuario_servicio = ( SELECT ususer.idusuario_servicio FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idservicio = $servicio AND usu.codigo = tmp.$codigo_usuario AND ususer.estado = 1 AND usu.estado = 1 LIMIT 1 ) 
					WHERE TRIM(tmp.$codigo_usuario) != '' ";

					$prUpdateTmpIdUsuarioServicio = $connection->prepare($sqlUpdateTmpIdUsuarioServicio);
					if( $prUpdateTmpIdUsuarioServicio->execute() ) {

						$sqlDistribucion = "";
						if( $modo == 'cartera' ) {

							$sqlDistribucion = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpdis_".session_id()."_".$time." tmp
							ON tmp.".$codigo_cliente." = clicar.codigo_cliente
							SET clicar.idusuario_servicio = tmp.idusuario_servicio
							WHERE clicar.idcartera = ".$cartera." AND clicar.idusuario_servicio = 0 AND ISNULL(tmp.idusuario_servicio) = 0 ";

							$prDistribucion = $connection->prepare($sqlDistribucion);
							if( $prDistribucion->execute() ){

							}else{
								$connection->rollBack();
								echo json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
								exit();
							}

						}else{

							$sqlClearTmp = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = 0 WHERE idcartera = ".$cartera." ";

							$prClearTmp = $connection->prepare($sqlClearTmp);
							if( $prClearTmp->execute() ) {

							}else{
								$connection->rollBack();
								echo json_encode(array('rst'=>false,'msg'=>'Error al limpiar temporal'));
								exit();
							}

							$sqlDistribucion = " UPDATE ca_cliente_cartera clicar INNER JOIN tmpdis_".session_id()."_".$time." tmp
							ON tmp.".$codigo_cliente." = clicar.codigo_cliente
							SET clicar.idusuario_servicio_especial = tmp.idusuario_servicio   
							WHERE clicar.idcartera = ".$cartera." AND ISNULL(tmp.idusuario_servicio) = 0 "; 

							$prDistribucion = $connection->prepare($sqlDistribucion);
							if( $prDistribucion->execute() ){

							}else{
								$connection->rollBack();
								echo json_encode(array('rst'=>false,'msg'=>'Error al realizar distribucion'));
								exit();
							}

						}
					}else{
						$connection->rollBack();
						echo json_encode(array('rst'=>false,'msg'=>'Error al actualizar tabla temporal'));
						exit();
					}

				}

				$connection->commit();
				echo json_encode(array('rst'=>true,'msg'=>'Distribucion realizada correctamente'));

			}else{
				echo json_encode(array('rst'=>false,'msg'=>'Error al insertar datos a tabla temporal'));
			}

		}else{
			echo json_encode(array('rst'=>false,'msg'=>'Error al crear tabla temporal'));
		}

	}

	public function uploadCarteraCourier_masivo ( $_post, $file ) {

		$servicio = $_post['Servicio'];
		$nombre_servicio = $_post['NombreServicio'];
		$usuario_creacion = $_post['usuario_creacion'];
		$separator = $_post['separator'];
		$formatoFechas = $_post['formatoFechas'];
		$tipo = $_post['Tipo'];
		//$idcampania=$_post['idcampania'];

		$confCobrast = parse_ini_file('../conf/cobrast.ini', true);

		if (!isset($confCobrast['ruta_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['document_root_cobrast'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		} else if (!isset($confCobrast['ruta_cobrast']['nombre_carpeta'])) {
			echo json_encode(array('rst' => false, 'msg' => 'Error al leer el archivo de configuracion'));
			exit();
		}
		$path = "../documents/currier_visitas/" . $_post["NombreServicio"] . "/" . $file;
		if (!file_exists($path)) {
			return array('rst' => false, 'msg' => 'Archivo subido no existe o fue removido, intente subir otra vez Archivos');

		}

		$time = date("Y_m_d_H_i_s");

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();


		$sqlDropTableTmpIVR = " DROP TABLE IF EXISTS tmp_courier_" . $time;
		$prDropTableTmpIVR = $connection->prepare($sqlDropTableTmpIVR);
		if ($prDropTableTmpIVR->execute()) {

			$sqlCreateTableTmpIVR = " CREATE TABLE tmp_courier_" . $time . " (  
				INSCRIPCION VARCHAR(50),
				CODIGO_GESTOR VARCHAR(30),
				CODIGO_ESTADO VARCHAR(20),
				FECHA_VISITA VARCHAR(20),
				FECHA_RECEPCION VARCHAR(20),
				FECHA_CP VARCHAR(20),
				MONTO_CP VARCHAR(15),
				MONEDA_CP VARCHAR(50),
				OBSERVACION TEXT,
				DESCRIPCION_INMUEBLE TEXT,
				idcartera INT,
				idcliente_cartera  INT,
				idcuenta INT,
				idnotificador INT,
				idfinal INT,
				iddireccion INT 
				)  ";

			$prsqlCreateTableTmpIVR = $connection->prepare($sqlCreateTableTmpIVR);
			if ($prsqlCreateTableTmpIVR->execute()) {

				$sqlLoad = "";
				if( $separator == 'tab' ) {
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/currier_visitas/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_courier_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				} else{
					$sqlLoad = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/currier_visitas/" . $_post['NombreServicio'] . "/" . $file . "'   
					INTO TABLE tmp_courier_" . $time . " FIELDS TERMINATED BY '" . $separator . "' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
				}

				$prLoad = $connection->prepare($sqlLoad);
				if( $prLoad->execute() ) {

					$sqlUpdateEstado = " UPDATE tmp_courier_".$time." tmp INNER JOIN ca_final_servicio finser 
					ON finser.codigo = TRIM(tmp.CODIGO_ESTADO) 
					SET tmp.idfinal = finser.idfinal
					WHERE finser.estado = 1 AND finser.idservicio = ".$servicio." ";

					$prUpdateEstado = $connection->prepare( $sqlUpdateEstado );
					if( $prUpdateEstado->execute() ) {

						$sqlUpdateGestor = " UPDATE tmp_courier_".$time." tmp INNER JOIN ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario AND usu.codigo = TRIM(tmp.CODIGO_GESTOR) 
						SET tmp.idnotificador = ususer.idusuario_servicio
						WHERE ususer.estado = 1 AND ususer.idservicio = ".$servicio." ";

						$prUpdateGestor = $connection->prepare( $sqlUpdateGestor );
						if( $prUpdateGestor->execute() ) {

							$sqlUpdateCartera = " UPDATE tmp_courier_".$time." tmp 
							SET tmp.idcartera = ( SELECT MAX(idcartera) FROM ca_cuenta WHERE numero_cuenta = TRIM(tmp.INSCRIPCION) ) ";

							$prUpdateCartera = $connection->prepare( $sqlUpdateCartera );
							if( $prUpdateCartera->execute() ) {

								$sqlUpdateIdCuentaIdCC = " UPDATE tmp_courier_".$time." tmp INNER JOIN ca_cuenta cu 
								ON cu.idcartera = tmp.idcartera AND cu.numero_cuenta = TRIM(tmp.INSCRIPCION) 
								SET 
								tmp.idcliente_cartera = cu.idcliente_cartera , 
								tmp.idcuenta = cu.idcuenta 
								WHERE ISNULL( tmp.idcartera ) = 0 ";

								$prUpdateIdCuentaIdCC = $connection->prepare( $sqlUpdateIdCuentaIdCC );
								if( $prUpdateIdCuentaIdCC->execute() ) {

									$sqlUpdateDireccion = " UPDATE tmp_courier_".$time." tmp INNER JOIN ca_direccion dir 
									ON dir.idcartera = tmp.idcartera AND dir.idcuenta = tmp.idcuenta
									SET tmp.iddireccion = dir.iddireccion 
									WHERE ISNULL(tmp.idcartera) = 0 AND ISNULL( tmp.idcuenta ) = 0 ";

									$prUpdateDireccion = $connection->prepare( $sqlUpdateDireccion );
									if( $prUpdateDireccion->execute() ) {

										$fecha_visita = " 
												CASE
												WHEN LENGTH(TRIM( FECHA_VISITA )) = 8
												THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,5,2),'-',SUBSTRING( FECHA_VISITA ,7,2))
												WHEN LENGTH(TRIM( FECHA_VISITA )) = 10
												THEN 
													CASE
													WHEN INSTR(TRIM( FECHA_VISITA ),'/') = 3
													THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
													WHEN INSTR(TRIM( FECHA_VISITA ),'/') = 5
													THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,6,2),'-',SUBSTRING( FECHA_VISITA ,9,2))
													WHEN INSTR(TRIM( FECHA_VISITA ),'-') = 3
													THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
													WHEN INSTR(TRIM( FECHA_VISITA ),'.') = 3
													THEN CONCAT(SUBSTRING( FECHA_VISITA ,7,4),'-',SUBSTRING( FECHA_VISITA ,4,2),'-',SUBSTRING( FECHA_VISITA ,1,2))
													WHEN INSTR(TRIM( FECHA_VISITA ),'.') = 5
													THEN CONCAT(SUBSTRING( FECHA_VISITA ,1,4),'-',SUBSTRING( FECHA_VISITA ,6,2),'-',SUBSTRING( FECHA_VISITA ,9,2))
													ELSE TRIM( FECHA_VISITA )
													END
												ELSE TRIM( FECHA_VISITA )
												END 
												 ";

										$fecha_recepcion = " 
												CASE
												WHEN LENGTH(TRIM( FECHA_RECEPCION )) = 8
												THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,5,2),'-',SUBSTRING( FECHA_RECEPCION ,7,2))
												WHEN LENGTH(TRIM( FECHA_RECEPCION )) = 10
												THEN 
													CASE
													WHEN INSTR(TRIM( FECHA_RECEPCION ),'/') = 3
													THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
													WHEN INSTR(TRIM( FECHA_RECEPCION ),'/') = 5
													THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,6,2),'-',SUBSTRING( FECHA_RECEPCION ,9,2))
													WHEN INSTR(TRIM( FECHA_RECEPCION ),'-') = 3
													THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
													WHEN INSTR(TRIM( FECHA_RECEPCION ),'.') = 3
													THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,7,4),'-',SUBSTRING( FECHA_RECEPCION ,4,2),'-',SUBSTRING( FECHA_RECEPCION ,1,2))
													WHEN INSTR(TRIM( FECHA_RECEPCION ),'.') = 5
													THEN CONCAT(SUBSTRING( FECHA_RECEPCION ,1,4),'-',SUBSTRING( FECHA_RECEPCION ,6,2),'-',SUBSTRING( FECHA_RECEPCION ,9,2))
													ELSE TRIM( FECHA_RECEPCION )
													END
												ELSE TRIM( FECHA_RECEPCION )
												END 
												 ";

										$fecha_cp = " 
												CASE
												WHEN LENGTH(TRIM( FECHA_CP )) = 8 
												THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,5,2),'-',SUBSTRING( FECHA_CP ,7,2))
												WHEN LENGTH(TRIM( FECHA_CP )) = 10 
												THEN 
													CASE
													WHEN INSTR(TRIM( FECHA_CP ),'/') = 3
													THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
													WHEN INSTR(TRIM( FECHA_CP ),'/') = 5
													THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,6,2),'-',SUBSTRING( FECHA_CP ,9,2))
													WHEN INSTR(TRIM( FECHA_CP ),'-') = 3
													THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
													WHEN INSTR(TRIM( FECHA_CP ),'.') = 3
													THEN CONCAT(SUBSTRING( FECHA_CP ,7,4),'-',SUBSTRING( FECHA_CP ,4,2),'-',SUBSTRING( FECHA_CP ,1,2))
													WHEN INSTR(TRIM( FECHA_CP ),'.') = 5
													THEN CONCAT(SUBSTRING( FECHA_CP ,1,4),'-',SUBSTRING( FECHA_CP ,6,2),'-',SUBSTRING( FECHA_CP ,9,2))
													ELSE TRIM( FECHA_CP )
													END
												ELSE TRIM( FECHA_CP )
												END 
												 ";

										$sqlInsert = " INSERT INTO ca_visita 
										( 
										idcliente_cartera, idtipo_gestion, idfinal, iddireccion, idnotificador, idcuenta, fecha_cp, 
										monto_cp, moneda_cp, fecha_visita, fecha_recepcion, observacion, descripcion_inmueble, tipo, 
										fecha_creacion, usuario_creacion  
										) 
										SELECT 
										idcliente_cartera, 1, idfinal, iddireccion, idnotificador, idcuenta , ( $fecha_cp ),
										MONTO_CP, MONEDA_CP, ( $fecha_visita ), ( $fecha_recepcion ), OBSERVACION, DESCRIPCION_INMUEBLE, '$tipo',
										NOW(), $usuario_creacion 
										FROM tmp_courier_".$time." 
										WHERE ISNULL(idcliente_cartera) = 0 AND ISNULL(idcuenta) = 0 AND ISNULL(iddireccion) = 0 ";

										$prInsert = $connection->prepare($sqlInsert);
										if( $prInsert->execute() ) {

											return array('rst' => true, 'msg' => 'Data insertada correctamente');

										} else{
											return array('rst' => false, 'msg' => 'Error al insertar data');
										}

									} else{
										return array('rst' => false, 'msg' => 'Error al actualizar id direccion a temporal');
									}

								} else{
									return array('rst' => false, 'msg' => 'Error al actualizar id cuenta a temporal');
								}

							} else{
								return array('rst' => false, 'msg' => 'Error al actualizar id gestion a temporal');
							}

						} else{
							return array('rst' => false, 'msg' => 'Error al actualizar id gestor a temporal');
						}

					} else{
						return array('rst' => false, 'msg' => 'Error al actualizar id estado a temporal');
					}

				} else{
					return array('rst' => false, 'msg' => 'Error al cargar data a temporal');
				}

			} else{
				return array('rst' => false, 'msg' => 'Error al cargar crear temporal');
			}

		} else {
			return array('rst' => false, 'msg' => 'Error al eliminar tabla temporal');

		}

	}



}

?>
