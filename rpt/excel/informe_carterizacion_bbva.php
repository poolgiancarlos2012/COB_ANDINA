<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	require_once 'informe_carterizacion_function.php';

	date_default_timezone_set('America/Lima');

	$cartera = $_REQUEST['carteras'];
	$ini = $_REQUEST['fecha_inicio'];
	$fin = $_REQUEST['fecha_fin'];
	$fproceso = $_REQUEST['fecha_proceso'];
	$infoCartera = $_REQUEST['info_cartera'];

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

//~ Columnas Estaticas
	$columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	$now = date("Ymd_His");

//~ Resumen de Informe de Carterizacion
	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Resumen_Carterizacion');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'RESUMEN DE CARTERIZACION');
	$xls->getActiveSheet()->mergeCells("E2:I2");
	$xls->getActiveSheet()->getStyle('E2:I2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));


	$sql_info_cartera_resumen = sql_informe_cartera_resumen($cartera, $ini, $fin, $fproceso, $infoCartera);
	$prInfoCarteraResumen = $connection->prepare($sql_info_cartera_resumen);
	if ($prInfoCarteraResumen->execute())
	{
		$xls->getActiveSheet()->SetCellValue('B4', 'TIPO CONTACTO');
		$xls->getActiveSheet()->SetCellValue('C4', 'TOTAL');
		$xls->getActiveSheet()->SetCellValue('D4', '%');
		$xls->getActiveSheet()->getStyle('B4:D4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		$total=0;
		$fila = $prInfoCarteraResumen->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['contactoGeneral']=='TOTALES')
			{
				$total = $v['total'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('B'.$f, $va["contactoGeneral"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $va["total"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, round( ( ($va["total"] / $total) * 100 ), 2) );
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Detalle de Informe de Carterizacion
	$xls->createSheet();
	$xls->setActiveSheetIndex(1);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Informe_Carterizacion');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'INFORME DE CARTERIZACION');
	$xls->getActiveSheet()->mergeCells("E2:H2");
	$xls->getActiveSheet()->getStyle('E2:H2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$sql_informe = sql_informe_cartera($cartera, $ini, $fin, $fproceso, $infoCartera);
	$prInforme = $connection->prepare($sql_informe);
	if ($prInforme->execute())
	{
		$xls->getActiveSheet()->SetCellValue('A4', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('B4', 'OFICINA');
		$xls->getActiveSheet()->SetCellValue('C4', 'COD-OFICINA');
		$xls->getActiveSheet()->SetCellValue('D4', 'CLIENTE');
		$xls->getActiveSheet()->SetCellValue('E4', 'NOMBRE');
		$xls->getActiveSheet()->SetCellValue('F4', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('G4', 'SUB-PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('H4', 'CONTRATO');
		$xls->getActiveSheet()->SetCellValue('I4', 'DIVISA');
		$xls->getActiveSheet()->SetCellValue('J4', 'SALDO');
		$xls->getActiveSheet()->SetCellValue('K4', 'SALDO-VIGENTE');
		$xls->getActiveSheet()->SetCellValue('L4', 'DIAS-VENC');
		$xls->getActiveSheet()->SetCellValue('M4', 'GESTION_CALL');
		$xls->getActiveSheet()->SetCellValue('N4', 'GESTION_CAMPO');
		$xls->getActiveSheet()->SetCellValue('O4', 'FECHA_PAGO');
		$xls->getActiveSheet()->SetCellValue('P4', 'CONTACTO_GENERAL');
		$xls->getActiveSheet()->SetCellValue('Q4', 'RESUMEN_ESTADO');
		$xls->getActiveSheet()->SetCellValue('R4', 'LLAMADA');
		$xls->getActiveSheet()->SetCellValue('S4', 'CAMPO');
		$xls->getActiveSheet()->getStyle('A4:S4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		while( $fila = $prInforme->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('A'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["oficina2"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["oficina"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, '="'.$fila["codcent"].'"');
			$xls->getActiveSheet()->SetCellValue('E'.$f, $fila["nombre"]);
			$xls->getActiveSheet()->SetCellValue('F'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('G'.$f, $fila["nom_subprod"]);
			$xls->getActiveSheet()->SetCellValue('H'.$f, '="'.$fila["contrato"].'"');
			$xls->getActiveSheet()->SetCellValue('I'.$f, $fila["divisa"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $fila["saldohoy"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $fila["saldoVigente"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["diavenc"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["gestionCall"]);
			$xls->getActiveSheet()->SetCellValue('N'.$f, $fila["observacion"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $fila["fecha_pago"]);
			$xls->getActiveSheet()->SetCellValue('P'.$f, $fila["contactoGeneral"]);
			$xls->getActiveSheet()->SetCellValue('Q'.$f, $fila["estadoResumen"]);
			$xls->getActiveSheet()->SetCellValue('R'.$f, $fila["tipificaLlamada"]);
			$xls->getActiveSheet()->SetCellValue('S'.$f, $fila["tipificaVisita"]);
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Exportar a Excel 2003
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Informe_Carterizacion_'.$now.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
	$objWriter->save('php://output');
	exit;

?>
