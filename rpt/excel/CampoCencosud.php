<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=CAMPO.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	$idCartera = $_GET['Cartera'];
	$nombre_servicio = $_GET['NombreServicio'];
	
	if ( $gd = @opendir('../../documents/campo/' . $nombre_servicio)) {
		@closedir( $gd );
	} else {
		@mkdir('../../documents/campo/' . $nombre_servicio);
	}
	

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	$sqlDataCartera = " SELECT idcartera,tabla,archivo,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales 
						FROM ca_cartera WHERE idcartera IN ($idCartera) ";

	$prData = $connection->prepare($sqlDataCartera);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);

	$dataTabla = $dataCartera[0]['tabla'];
	$dataArchivo = $dataCartera[0]['archivo'];
	
	$path = "../../documents/campo/" . $nombre_servicio . "/" . $dataArchivo;
	
	if( $dataTabla == '' || $dataTabla == 'temporal eliminada' ) {
	
		$ArchivoCampo = @fopen($path, 'r+');
		
		if ( $ArchivoCampo ) {
			
			while ( !feof($ArchivoCampo)) {
				
				$linea = fgets($ArchivoCampo);
				
				echo implode("\t",explode("|",$linea))."\n";
				
			}
			
			@fclose( $ArchivoCampo );
			
		}else{
			echo "Error al abrir archivo";
		}
		
	
	}else{
	
		$tmpArchivo = @fopen( $path , 'w');
		
		$sqlDataCarteraPago = " SELECT pago FROM ca_cartera_pago WHERE idcartera IN ( $idCartera ) ORDER BY idcartera_pago DESC LIMIT 1 ";

		$prDataPago = $connection->prepare($sqlDataCarteraPago);
		$prDataPago->execute();
		$dataCarteraPago = $prDataPago->fetchAll(PDO::FETCH_ASSOC);

		$dataCliente = str_replace("\\","",$dataCartera[0]['cliente']);
		$arrayCliente = json_decode($dataCliente,true);
	
		$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
		$arrayCuenta = json_decode($dataCuenta,true);
	
		$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
		$arrayDetalle = json_decode($dataDetalle,true);
	
		$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
		$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
		$dataPago = str_replace("\\","",$dataCarteraPago[0]['pago']);
		$arrayPago = json_decode($dataPago,true);
	
		$fieldPago = array();
		$fieldP = array();
	
		/*for( $i=0;$i<count($arrayPago);$i++ ) {
			if( $arrayPago[$i]['campoT']!='numero_cuenta' && $arrayPago[$i]['campoT']!='codigo_operacion' ) {
				if( $arrayPago[$i]['campoT']=='monto_pagado' ) {
					array_push($fieldPago," TRUNCATE( pag.".$arrayPago[$i]['campoT'].", 2 ) AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, " SUM( ".$arrayPago[$i]['campoT']." ) AS '".$arrayPago[$i]['campoT']."'"  );
				}else if( $arrayPago[$i]['campoT']=='fecha' || $arrayPago[$i]['campoT']=='fecha_envio' ){
					array_push($fieldPago," IF( DATE( pag.".$arrayPago[$i]['campoT']." ) = '0000-00-00','',DATE( pag.".$arrayPago[$i]['campoT']." ) ) AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, $arrayPago[$i]['campoT'] );
				}else{
					array_push($fieldPago," pag.".$arrayPago[$i]['campoT']." AS '".$arrayPago[$i]['label']."' ");
					array_push($fieldP, $arrayPago[$i]['campoT'] );
				}
			}
	
		}*/
	
		$str_implode_pago = "";
		$implode_pago = implode(",",$fieldPago);
		$where_pago = "";
		$join_pago = "";
		if( count($arrayPago) >0 ) {
			//$str_implode_pago = " ".$implode_pago." , ";
			$str_implode_pago = " pag.dias_mora AS D_MORA, pag.dato1 AS PMIN1, pag.dato2 AS EFECTIVIDAD, pag.dato3 AS PARA_GESTION, pag.dato4 AS TIPO_PAGO, pag.monto_pagado AS TOT_PAGOS , ";
			$str_implode_pago .= " 
							CASE 
							WHEN CAST( pag.dias_mora AS SIGNED ) < 9 THEN 'NORMAL'
							WHEN CAST( pag.dias_mora AS SIGNED ) BETWEEN 9 AND 30 THEN 'CPP'
							WHEN CAST( pag.dias_mora AS SIGNED ) BETWEEN 31 AND 60 THEN 'DEFICIENTE'
							WHEN CAST( pag.dias_mora AS SIGNED ) BETWEEN 61 AND 120  THEN 'DUDOSO'
							ELSE 'PERDIDA' 
							END AS TRAMO,
							 ";
			$where_pago = " AND pag.idcartera IN ( $idCartera ) ";
			$join_pago = " LEFT JOIN ca_pago pag ON pag.iddetalle_cuenta = detcu.iddetalle_cuenta AND pag.estado = 1 ";
		}
	
		$field = array();
	
		$sqlLV = " SELECT 
				tmp.GRP_FACT ,
				tmp.FEC_FACT , 
				tmp.NUM_CTA ,
				".$str_implode_pago." 
				CASE 
				WHEN cu.dato2 = '09' THEN 1
				WHEN cu.dato2 = '10' THEN 2
				WHEN cu.dato2 = '11' THEN 3
				WHEN cu.dato2 = '12' THEN 4
				WHEN cu.dato2 = '01' THEN 5
				WHEN cu.dato2 = '05' THEN 6
				WHEN cu.dato2 = '02' THEN 7
				WHEN cu.dato2 = '06' THEN 8
				WHEN cu.dato2 = '03' THEN 9
				WHEN cu.dato2 = '07' THEN 10
				WHEN cu.dato2 = '04' THEN 11
				WHEN cu.dato2 = '08' THEN 12
				WHEN cu.dato2 = '21' THEN 13
				WHEN cu.dato2 = '22' THEN 14
				WHEN cu.dato2 = '23' THEN 15
				WHEN cu.dato2 = '24' THEN 16
				WHEN cu.dato2 = '13' THEN 17
				WHEN cu.dato2 = '17' THEN 18
				WHEN cu.dato2 = '14' THEN 19
				WHEN cu.dato2 = '18' THEN 20
				WHEN cu.dato2 = '15' THEN 21
				WHEN cu.dato2 = '19' THEN 22
				WHEN cu.dato2 = '16' THEN 23 
				ELSE 24 
				END AS PRIORIDAD,
				tmp.SEGMENTO ,
				CASE 
				WHEN cu.dato2 IN ('10','02','06','22','14','18') THEN 'NB'
				WHEN cu.dato2 IN ('11','03','07','23','15','19') THEN 'RIESGO ACEPTABLE'
				WHEN cu.dato2 IN ('09','01','05','21','13','17') THEN 'RIESGO ALTO'
				ELSE 'RIESGO BAJO'
				END AS RIESGO,
				( SELECT nombre FROM ca_final WHERE idfinal = cu.ml_estado LIMIT 1 ) AS 'ESTADO_LLAMADA', 
				DATE(cu.ml_fecha) AS 'FECHA_LLAMADA',
				TIME(cu.ml_fecha) AS 'HORA_LLAMADA',
				DATE( cu.ml_fcpg ) AS 'FECHA_CPG', 
				cu.ml_observacion AS 'OBSERVACION' ,
				( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario = ususer.idusuario WHERE ususer.idusuario_servicio = cu.ml_operador ) AS 'OPERADOR',
				( SELECT numero FROM ca_telefono WHERE idtelefono = cu.ml_telefono ) AS 'TELEFONO_MG',
				tmp.PAN ,
				CASE 
				WHEN SUBSTRING( cu.inscripcion,1,6 ) = '905050' THEN 'Metro Cerrada' 
				WHEN SUBSTRING( cu.inscripcion,1,6 ) = '477151' THEN 'Metro Visa Clasica' 
				WHEN SUBSTRING( cu.inscripcion,1,6 ) = '477357' THEN 'Wong Visa Clasica' 
				WHEN SUBSTRING( cu.inscripcion,1,6 ) = '477358' THEN 'Wong Visa Gold' 
				WHEN SUBSTRING( cu.inscripcion,1,6 ) = '477359' THEN 'Wong Visa Platinum' 
				ELSE '' 
				END AS BIN,
				SUBSTRING( cu.inscripcion,1,6 ) AS CAMP,
				
				
				tmp.NOM_COMPLETO,
				tmp.PMIN2 AS PMIN2_,
				tmp.SAL_FACT1,
				tmp.DIR_DOMICILIO AS DIRECCION,
				tmp.DISTRITO_DOMICILIO AS DISTRITO,
				( SELECT referencia FROM ca_direccion WHERE idcartera = cu.idcartera AND codigo_cliente = cu.codigo_cliente AND idtipo_referencia = 2 LIMIT 1 ) AS REFERENCIA,
				tmp.DISTRITO_SIS,
				tmp.TEL1,
				tmp.TEL2,
				tmp.TEL3,
				tmp.TEL4,
				tmp.TEL5,
				tmp.TEL6,
				tmp.TEL7,
				tmp.TEL8,
				
				TRUNCATE( ( SUM(detcu.monto_mora) -  IFNULL(cu.monto_pagado,0) ),2 ) AS PMIN2,
				( SELECT zona FROM ca_ubigeo WHERE distrito = TRIM( tmp.DISTRITO_DOMICILIO ) LIMIT 1 ) AS ZONA,
				detcu.fecha_vencimiento AS F_VENCIMIENTO , 
				tmp.FEC_FACT AS F_EMISION
				FROM ca_cuenta cu 
				INNER JOIN $dataTabla tmp ON tmp.idcuenta = cu.idcuenta 
				INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = tmp.idcuenta 
				".$join_pago."
				WHERE cu.idcartera IN ($idCartera) AND detcu.idcartera IN ($idCartera) ".$where_pago." 
				GROUP BY cu.idcuenta ";
	
	
		$pr5 = $connection->prepare($sqlLV);
		$pr5->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
		$pr5->execute();
		$count2=0;
	
	
		while( $row3 = $pr5->fetch(PDO::FETCH_ASSOC) ) {
			if( $count2 == 0 ) {
	
				$header = array();
				foreach( $row3 as $index => $value ){
					if( $index!='idcartera' && $index!='idcliente' && $index!='idcliente_cartera' && $index!='idcuenta' ) {
						echo $index."\t";
						array_push( $header, $index );
					}
				}
				echo "\n";
	
				@fwrite( $tmpArchivo, implode("|",$header)."\r\n" );
	
			}
			$data = array();
			foreach( $row3 as $index => $value ){
				if( $index!='idcartera' && $index!='idcliente' && $index!='idcliente_cartera' && $index!='idcuenta' ) {
					echo '="'.str_replace("\n","",str_replace("\t","",$value)).'"'."\t";
					array_push( $data, str_replace("\n"," ",str_replace("|"," ",utf8_encode($value))) );
				}
			}
			echo "\n";
	
			@fwrite( $tmpArchivo, implode("|",$data)."\r\n" );
	
			$count2++;
		}
	
		@fclose( $tmpArchivo );
	
	}

?>
