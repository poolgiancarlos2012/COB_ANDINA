<?php



	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php'; 

		
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';   

	$idcartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];

	date_default_timezone_set('America/Lima');


	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	$sqlInformeDiario= '  SELECT clicar.idcliente_cartera,
			( SELECT CONCAT_WS(" ",usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio limit 1) AS TELEOPERADOR, 
		-- ( SELECT usu.grupo FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS GRUPO, 
			TRUNCATE( SUM(IFNULL(cu.total_deuda,0)), 2 ) AS MONTO_ASIGNADO , 
		-- TRUNCATE( SUM( IFNULL(cu.monto_pagado,0) ), 2 ) AS MONTO_RECUPERADO,
			CONCAT(TRUNCATE( ( SUM(IFNULL(cu.monto_pagado,0)) / SUM(IFNULL(cu.total_deuda,0)) )*100,2 ),"%") AS RECUPERO
			FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu 
			ON cu.idcliente_cartera = clicar.idcliente_cartera 
			WHERE clicar.idcartera IN ( 4671 ) AND cu.idcartera IN ( 4671 )
			GROUP BY clicar.idusuario_servicio ORDER BY RECUPERO DESC ';
	$prInformeDiario = $connection->prepare($sqlInformeDiario);
	$prInformeDiario->execute();

	$arrayInformeDiario=$prInformeDiario->fetchAll(PDO::FETCH_ASSOC);


	$objPHPExcel= new PHPExcel();
	$objPHPExcel->setACtiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTittle('Ranking Teleoperador');

	$hojaActual= $objPHPExcel->getActiveSheet();

	$hojaActual->setCellValue('B2','RANKING TELEOPERADORES');
	$hojaActual->mergeCells('B2:E2');

	$hojaActual->setCellValue('B4','PUESTO ACTUAL');
	$hojaActual->setCellValue('C4','TELEOPERADOR');
	$hojaActual->setCellValue('D4','MONTO ASIGNADO');
	$hojaActual->setCellValue('E4','MONTO RECUPERO %');
	$c=5
	for($i=0;$i<count($arrayInformeDiario);$i++){
		$hojaActual->setCellValue('B'.$c,$i);
		$hojaActual->setCellValue('C'.$c,$arrayInformeDiario[$i]['TELEOPERADOR']);
		$hojaActual->setCellValue('D'.$c,$arrayInformeDiario[$i]['MONTO_ASIGNADO']);
		$hojaActual->setCellValue('B'.$c,$arrayInformeDiario[$i]['RECUPERO']);
	}



	//segunda hoja

	$sqlCartera = " SELECT 
					cu.idcartera,
					( SELECT nombre_cartera FROM ca_cartera WHERE idcartera = cu.idcartera ) AS CARTERA,
					COUNT(*) AS TOTAL_CUENTAS
					FROM ca_cuenta cu WHERE cu.idcartera IN ( ".$idcartera." ) 
					GROUP BY cu.idcartera ";

	$prCartera = $connection->prepare( $sqlCartera );
	$prCartera->execute();
	$dataCartera = $prCartera->fetchAll(PDO::FETCH_ASSOC);

	$objPHPExcel->setACtiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setTittle('Ranking x Distrito');
		
	

//	echo '<table border="0">';
//		echo '<tr>';
	for( $i=0;$i<count($dataCartera);$i++ ) {

		$sql = "SELECT
				DISTRITO,
				COUNT( * ) AS CUENTAS,
				TRUNCATE( ( COUNT( * ) / ".$dataCartera[$i]['TOTAL_CUENTAS']." )*100, 2) AS P_CUENTAS,
				TRUNCATE( SUM(t1.total_deuda),2 ) AS MONTO,
				TRUNCATE( SUM(IFNULL(t1.monto_pagado,0)),2 ) AS PAGO,
				TRUNCATE( ( SUM(IFNULL(t1.monto_pagado,0)) / SUM(t1.total_deuda) )*100 ,2 ) AS RECUPERO 
				FROM
				( 
				SELECT
				IFNULL( ( SELECT TRIM(zona) FROM ca_direccion WHERE idcartera = cu.idcartera AND idcuenta = cu.idcuenta LIMIT 1 ),'') AS ZONA,
				IFNULL( ( SELECT TRIM(distrito) FROM ca_direccion WHERE idcartera = cu.idcartera AND idcuenta = cu.idcuenta LIMIT 1 ),'') AS DISTRITO,
				cu.total_deuda ,
				cu.monto_pagado
				FROM ca_cuenta cu
				WHERE cu.idcartera = ?
				) t1
				WHERE t1.ZONA IN ('LIMA','LIM') 
				GROUP BY t1.DISTRITO 
				WITH ROLLUP  ";

		$pr = $connection->prepare($sql);
		$pr->bindParam(1,$dataCartera[$i]['idcartera'],PDO::PARAM_INT);
		$pr->execute();
		$data = $pr->fetchAll(PDO::FETCH_ASSOC);

		$inicio=2;
		$objPHPExcel->setCellValue('B'.$inicio,$dataCartera[$i]['CARTERA']);
		$objPHPExcel->mergeCells('B'.$inicio,':G'.$inicio);

		/*
		echo '<td>';
		
		echo '<table border="1" >';
			echo '<tr>';
				echo '<td style="background-color:#B8CCE4;color:black;font-weight:bold;" colspan="6" align="center" >'.$dataCartera[$i]['CARTERA'].'</td>';
			echo '</tr>';*/
		for( $j=0;$j<count($data);$j++ ) {
			
			if( $j == 0 ) {
				//echo '<tr>';
				foreach( $data[$j] as $index => $value ) {
					if($index=='DISTRITO'){
						$objPHPExcel->setCellValue('B'.($inicio+1),$index);
					}else if($index=='CUENTAS'){
						$objPHPExcel->setCellValue('C'.($inicio+1),$index);
					}else if($index=='P_CUENTAS'){
						$objPHPExcel->setCellValue('D'.($inicio+1),$index);
					}else if($index=='MONTO'){
						$objPHPExcel->setCellValue('E'.($inicio+1),$index);
					}else if($index=='PAGO'){
						$objPHPExcel->setCellValue('F'.($inicio+1),$index);
					}else if($index=='RECUPERO'){
						$objPHPExcel->setCellValue('G'.($inicio+1),$index);
					}
				//	echo '<td style="background-color:#B8CCE4;color:black;font-weight:bold;" align="center">'.$index.'</td>';
				}
				//echo '</tr>';
			}
			
			if( $j == ( count($data) - 1 ) ) {
				
			//	echo '<tr>';
				foreach( $data[$j] as $index => $value ) {
					if($index=='DISTRITO'){
						$objPHPExcel->setCellValue('B'.($inicio+2),'TOTAL GENERAL');
					}else if($index=='CUENTAS'){
						$objPHPExcel->setCellValue('C'.($inicio+2),$value);
					}else if($index=='P_CUENTAS'){
						$objPHPExcel->setCellValue('D'.($inicio+2),$value.'%');
					}else if($index=='MONTO'){
						$objPHPExcel->setCellValue('E'.($inicio+2),$value);
					}else if($index=='PAGO'){
						$objPHPExcel->setCellValue('F'.($inicio+2),$value);
					}else if($index=='RECUPERO'){
						$objPHPExcel->setCellValue('G'.($inicio+2),$value.'%');
					}

				/*
					if( $index == 'DISTRITO' ) {

						echo '<td style="background-color:#B8CCE4;color:black;font-weight:bold;" align="center">TOTAL GENERAL</td>';
					}else if( $index == 'RECUPERO' || $index == 'P_CUENTAS' ){
						echo '<td style="background-color:#B8CCE4;color:black;font-weight:bold;" align="center">'.$value.'%</td>';
					}else{
						echo '<td style="background-color:#B8CCE4;color:black;font-weight:bold;" align="center">'.$value.'</td>';
					}
				}*/
			//	echo '</tr>';
				
			}else{
				//echo '<tr>';
				foreach( $data[$j] as $index => $value ) {
					if($index=='DISTRITO'){
						$objPHPExcel->setCellValue('B'.($inicio+2),$value);
					}else if($index=='CUENTAS'){
						$objPHPExcel->setCellValue('C'.($inicio+2),$value);
					}else if($index=='P_CUENTAS'){
						$objPHPExcel->setCellValue('D'.($inicio+2),$value.'%');
					}else if($index=='MONTO'){
						$objPHPExcel->setCellValue('E'.($inicio+2),$value);
					}else if($index=='PAGO'){
						$objPHPExcel->setCellValue('F'.($inicio+2),$value);
					}else if($index=='RECUPERO'){
						$objPHPExcel->setCellValue('G'.($inicio+2),$value.'%');
					}


				/*	if( $index == 'RECUPERO' || $index == 'P_CUENTAS' ) {
						echo '<td align="center">'.$value.'%</td>';
					}else{
						echo '<td align="center">'.$value.'</td>';
					}
				}*/
				//echo '</tr>';
			}
			
		}
		/*echo '</table>';
		
		echo '</td>';
		echo '<td style="width:40px;"></td>';
*/
	}
//		echo '<tr>';
//	echo '</table>';

	

	header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="REPORTE_CONTACTABILIDAD.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 





?>