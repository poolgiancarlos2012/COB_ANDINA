<?php
	header("Content-Type: text/html; charset=UTF-8");	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=pcampania.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	$confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
	$user = $confCobrast['user_db']['user_rpt'];
	$password = $confCobrast['user_db']['password_rpt'];

	date_default_timezone_set('America/Lima');

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	$time=date("Y_m_d_H_i_s");

	$idcartera = $_REQUEST['cartera'];
	$servicio = $_REQUEST['servicio'];
	$campania =$_REQUEST['campania'];
	$NombreServicio = $_REQUEST['nombre_servicio'];
	$file = $_REQUEST['file'];
	
	$path = "../../documents/pcampania/" . $NombreServicio . "/" . $file;
	if (!file_exists($path)) {
		echo 'La cartera subida no existe o fue removida, intente subir otra vez la cartera';
		exit();
	}

	$archivo = @fopen($path, "r+");
	$colum = explode("\t", fgets($archivo));
	
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

	$columHeader = array();
	$countHeaderFalse = 0;

	for ($i = 0; $i < count($colum); $i++) {
		if ($colum[$i] != "") {
			array_push($columHeader, " " . $colum[$i] . " VARCHAR(200) ");
		} else {
			$countHeaderFalse++;
		}
	}

	if ($countHeaderFalse > 0) {
		echo 'Existen cabeceras vacias';
		exit();
	}
	
	array_push($columHeader, " INDEX ( idcuenta ) ");
	
	$sqlCreateTable = " CREATE TEMPORARY TABLE p_campania_".$time." ( ".implode(",", $columHeader)." ) ";
	
	$pr = $connection->prepare($sqlCreateTable);
	if( $pr->execute() ) {
		
		$sqlLoadData = " LOAD DATA INFILE '" . $confCobrast['ruta_cobrast']['document_root_cobrast'] . "/" . $confCobrast['ruta_cobrast']['nombre_carpeta'] . "/documents/pcampania/" . $NombreServicio . "/" . $file . "'
					INTO TABLE p_campania_" . $time . " FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES ";
		
		$prLoad = $connection->prepare($sqlLoadData);
		if( $prLoad->execute() ) {
		
			/*$field = array();
			array_push($field," idcuenta INT ");
			for( $i=1;$i<11;$++ ) {
				array_push($field," FACTURA".$i." VARCHAR(50) ");
				array_push($field," FECHA_EMISION".$i." DATE ");
				array_push($field," FECHA_VENCIMIENTO".$i." DATE ");
				array_push($field," CARGO".$i." TEXT ");
				array_push($field," ESTADO".$i." VARCHAR(50) ");
			}
			array_push($field," INDEX( idcuenta ) ");
			
			$sqlCreateTableF = " CREATE TEMPORARY TABLE facturas_".$time." ( ".implode(",", $field)." ) ";
			$prF = $connection->prepare($sqlCreateTableF);
			if( $prF->execute() ) {*/
				
				/*$sqlFacturas = " 
					INSERT INTO facturas_".$time." 
					SELECT t2.idcuenta, 
					GROUP_CONCAT( IF( t2.flag=1,t2.codigo_operacion,NULL ) ) AS 'FACTURA1',
					GROUP_CONCAT( IF( t2.flag=1,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION1',
					GROUP_CONCAT( IF( t2.flag=1,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO1',
					GROUP_CONCAT( IF( t2.flag=1,t2.nombre_agencia,NULL ) ) AS 'CARGO1',
					GROUP_CONCAT( IF( t2.flag=1,t2.estado,NULL ) ) AS 'ESTADO1',
					GROUP_CONCAT( IF( t2.flag=2,t2.codigo_operacion,NULL ) ) AS 'FACTURA2',
					GROUP_CONCAT( IF( t2.flag=2,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION2',
					GROUP_CONCAT( IF( t2.flag=2,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO2',
					GROUP_CONCAT( IF( t2.flag=2,t2.nombre_agencia,NULL ) ) AS 'CARGO2',
					GROUP_CONCAT( IF( t2.flag=2,t2.estado,NULL ) ) AS 'ESTADO2',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA3',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION3',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO3',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO3',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO3',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA4',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION4',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO4',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO4',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO4',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA5',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION5',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO5',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO5',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO5',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA6',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION6',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO6',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO6',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO6',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA7',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION7',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO7',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO7',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO7',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA8',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION8',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO8',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO8',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO8',
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA9',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION9',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO9',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO9',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO9'
					GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA10',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION10',
					GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO10',
					GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO10',
					GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO10'
					FROM
					(
					SELECT *, IF( STRCMP(@rowcli, numero_cuenta) = 0, @rownum:=@rownum+1, @rownum:=1  ) AS 'flag', @rowcli:=numero_cuenta 
					FROM 
					(
					SELECT idcuenta, numero_cuenta, codigo_operacion, fecha_emision, fecha_vencimiento, nombre_agencia, 
					CASE WHEN ( total_deuda - monto_pagado ) = 0 THEN 'C' WHEN ( total_deuda - monto_pagado ) >0 AND ( total_deuda - monto_pagado ) < total_deuda  THEN 'A' ELSE 'SP' END AS estado
					FROM ca_detalle_cuenta 
					WHERE idcartera IN ( $idcartera )
					ORDER BY idcuenta, fecha_vencimiento ASC 
					) t1
					,  ( SELECT @rownum:=1, @rowcli:='012' ) r
					) t2
					GROUP BY t2.idcuenta ";
	
				$prInsert = $connection->prepare($sqlFacturas);
				if( $prInsert->execute() ) {*/
				
					$sql = " SELECT tmp.*, tf1.* 
						FROM p_campania_".$time." tmp 
						INNER JOIN 
						(  
						SELECT t2.idcuenta, 
						GROUP_CONCAT( IF( t2.flag=1,t2.codigo_operacion,NULL ) ) AS 'FACTURA1',
						GROUP_CONCAT( IF( t2.flag=1,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION1',
						GROUP_CONCAT( IF( t2.flag=1,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO1',
						GROUP_CONCAT( IF( t2.flag=1,t2.nombre_agencia,NULL ) ) AS 'CARGO1',
						GROUP_CONCAT( IF( t2.flag=1,t2.estado,NULL ) ) AS 'ESTADO1',
						GROUP_CONCAT( IF( t2.flag=2,t2.codigo_operacion,NULL ) ) AS 'FACTURA2',
						GROUP_CONCAT( IF( t2.flag=2,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION2',
						GROUP_CONCAT( IF( t2.flag=2,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO2',
						GROUP_CONCAT( IF( t2.flag=2,t2.nombre_agencia,NULL ) ) AS 'CARGO2',
						GROUP_CONCAT( IF( t2.flag=2,t2.estado,NULL ) ) AS 'ESTADO2',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA3',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION3',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO3',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO3',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO3',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA4',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION4',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO4',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO4',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO4',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA5',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION5',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO5',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO5',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO5',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA6',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION6',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO6',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO6',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO6',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA7',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION7',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO7',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO7',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO7',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA8',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION8',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO8',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO8',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO8',
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA9',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION9',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO9',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO9',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO9'
						GROUP_CONCAT( IF( t2.flag=3,t2.codigo_operacion,NULL ) ) AS 'FACTURA10',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_emision,NULL ) ) AS 'FECHA_EMISION10',
						GROUP_CONCAT( IF( t2.flag=3,t2.fecha_vencimiento,NULL ) ) AS 'FECHA_VENCIMIENTO10',
						GROUP_CONCAT( IF( t2.flag=3,t2.nombre_agencia,NULL ) ) AS 'CARGO10',
						GROUP_CONCAT( IF( t2.flag=3,t2.estado,NULL ) ) AS 'ESTADO10'
						FROM
						(
						SELECT *, IF( STRCMP(@rowcli, numero_cuenta) = 0, @rownum:=@rownum+1, @rownum:=1  ) AS 'flag', @rowcli:=numero_cuenta 
						FROM 
						(
						SELECT idcuenta, numero_cuenta, codigo_operacion, fecha_emision, fecha_vencimiento, nombre_agencia, 
						CASE WHEN ( total_deuda - monto_pagado ) = 0 THEN 'C' WHEN ( total_deuda - monto_pagado ) >0 AND ( total_deuda - monto_pagado ) < total_deuda  THEN 'A' ELSE 'SP' END AS estado
						FROM ca_detalle_cuenta 
						WHERE idcartera IN ( $idcartera )
						ORDER BY idcuenta, fecha_vencimiento ASC 
						) t1
						,  ( SELECT @rownum:=1, @rowcli:='012' ) r
						) t2
						GROUP BY t2.idcuenta
						) tf1 
						ON tf1.idcuenta = tmp.idcuenta  ";
					
					/*$sql = " SELECT tmp.*, tf1.* 
						FROM p_campania_".$time." tmp INNER JOIN facturas_".$time." tf1 ON tf1.idcuenta = tmp.idcuenta  ";
						*/
	
					$prMain = $connection->prepare($sql);	
					$prMain->execute();
					$i = 0;
	
					echo '<table>';
					while( $row = $prMain->fetch(PDO::FETCH_ASSOC) ) {
						if( $i == 0 ) {
							echo '<tr>';
							foreach( $row as $index => $value ) {
								echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
							}
							echo '</tr>';
						}
	
						$style="";
						( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
						echo '<tr>';
						foreach( $row as $key => $value )
						{
							echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
						}
						echo '</tr>';
						$i++;
					}
					echo '</table>';
					
				/*}else{
					echo "Error al insertar facturas";
				}*/
				
			/*}else{
				echo "Error al crear facturas";
			}*/
			
			
			
		}else{
			echo 'Error al cargar data a temporal';
		}
		
	}else{
		echo 'Error al crear tabla temporal';
	}
	
	

	


?>

