<?php
	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	$Servicio = $_GET['Servicio'];
	$idCartera = $_GET['Cartera'];
	$nombre_servicio = $_GET['NombreServicio'];
	$tipo_cambio = $_GET['tipocambio'];
	$tipo_vac = $_GET['tipovac'];
	$fechaProceso = $_GET['fechaProceso'];

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

//~ Columnas Estaticas
	$columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
					"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
	$now = date("Ymd_His");

//~ Seleccionar las Carteras
	$sqlCarteraTmp = "SELECT idcartera, tabla FROM ca_cartera WHERE idcartera IN (".$idCartera.")";
	$prCarTmp = $connection->prepare($sqlCarteraTmp);
	$prCarTmp->execute();
	while( $filaCarTmp = $prCarTmp->fetch(PDO::FETCH_ASSOC) ) {
		$carteras[$filaCarTmp["idcartera"]]=$filaCarTmp["tabla"];
	}

//~ Descripcion de Carteras
	$nombreCartera=array();
	foreach ($carteras as $llave => $valor)
	{
		$sqlAgencia = "SELECT agencia FROM ".$valor." WHERE TRIM(agencia)!='' LIMIT 1";
		$prAgencia = $connection->prepare($sqlAgencia);
		$prAgencia->execute();
		$dataAgencia = $prAgencia->fetchAll(PDO::FETCH_ASSOC);
		$nombreCartera[$dataAgencia[0]['agencia']] = $llave;
	}

//~ Detalle de la Cartera
	$sqlDetalle = "";
	$i=0;
	foreach ($carteras as $key => $val)
	{
		$i++;
$sqlDetalle .=<<<EOT
SELECT *,
IF(A.monto<=10,'RI1 [<10]',IF(A.monto>10 AND A.monto<=100,'RI2 <10 - 100]',IF(A.monto>100 and A.monto<=300,'RI3 <100 - 300]',IF(A.monto>300 and A.monto<=500,'RI4 <300 - 500]',IF(A.monto>500 and A.monto<=1000,'RI5 <500 - 1000]',IF(A.monto>1000 and A.monto<=2000,'RI6 <1000 - 2000]',IF(A.monto>2000 and A.monto<=5000,'RI7 <2000 - 5000]',IF(A.monto>5000 and A.monto<=10000,'RI8 <5000 - 10000]',IF(A.monto>10000 and A.monto<=25000,'RI9 <10000 - 25000]',IF(A.monto>25000 and A.monto<=50000,'RI10 <25000 - 50000]',IF(A.monto>50000 and A.monto<=100000,'RI11 <50000 -100000]','RI12 [>100000]'))))))))))) AS 'rango_deuda'
 FROM(
		SELECT h.Fproceso, h.agencia, h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.nombre, h.divisa, h.saldohoy, h.diavenc, h.ubigeo
			,h.dpto, h.dist_prov, h.tramo_dia, h.marca
			,(SELECT DATE(fecha) FROM ca_llamada ll WHERE ll.idllamada=cc.id_ultima_llamada and TRIM(ll.tipo)<>'S') AS ultima_llamada,
			CASE 
				WHEN CAST(h.diavenc AS SIGNED) <= 30 THEN 'T1'
				WHEN CAST(h.diavenc AS SIGNED) > 30 AND CAST(h.diavenc AS SIGNED) <= 60 THEN 'T2'
				WHEN CAST(h.diavenc AS SIGNED) > 60 THEN 'T3'
				ELSE 'NT'
			END AS tramo, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * $tipo_cambio, saldohoy * $tipo_vac) ) AS monto
		FROM ca_historial h
			INNER JOIN ca_cliente_cartera cc ON h.idcliente_cartera=cc.idcliente_cartera
			INNER JOIN (SELECT agencia FROM {$val} WHERE TRIM(agencia)!='' LIMIT 1) a ON h.agencia=a.agencia
		WHERE h.Fproceso='{$fechaProceso}' AND cc.idcartera IN ({$key})
)A
EOT;
		$sqlDetalle .= ($i < count($carteras)) ? " UNION ALL " : "";
	}

//~ Consolidado de la Cartera
	$datas = array();
	$registros = array();
	foreach ($carteras as $key => $val) 
	{
$sqlConsolidado = <<<EOT
		SELECT IFNULL(t.tramo, 'SIN_GESTION') AS tramo, COUNT(t.contrato) AS contratos, SUM(CAST(t.monto AS DECIMAL(10,2)) ) AS montos
		FROM (
			SELECT h.contrato, (SELECT DATE(fecha) FROM ca_llamada ll WHERE ll.idllamada=cc.id_ultima_llamada and ll.tipo<>'S') AS ultima_llamada,
				CASE 
					WHEN CAST(h.diavenc AS SIGNED) <= 30 THEN 'T1'
					WHEN CAST(h.diavenc AS SIGNED) > 30 AND CAST(h.diavenc AS SIGNED) <= 60 THEN 'T2'
					WHEN CAST(h.diavenc AS SIGNED) > 60 THEN 'T3'
					ELSE 'NT'
				END AS tramo, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * {$tipo_cambio}, saldohoy * {$tipo_vac}) ) AS monto
			FROM ca_historial h
				INNER JOIN ca_cliente_cartera cc ON h.idcliente_cartera=cc.idcliente_cartera
				INNER JOIN (SELECT agencia FROM {$val} WHERE TRIM(agencia)!='' LIMIT 1) a ON h.agencia=a.agencia
			WHERE h.Fproceso='{$fechaProceso}' AND cc.idcartera IN ({$key}) 
		) t 
		WHERE t.ultima_llamada IS NULL
		GROUP BY t.tramo WITH ROLLUP
		UNION ALL
		SELECT t.gestion, COUNT(t.contrato) AS contratos, SUM(CAST(t.monto AS DECIMAL(10,2)) ) AS montos
		FROM (
			SELECT 'GESTIONADO' AS gestion, h.contrato, (SELECT DATE(fecha) FROM ca_llamada ll WHERE ll.idllamada=cc.id_ultima_llamada and ll.tipo<>'S') AS ultima_llamada
				, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * {$tipo_cambio}, saldohoy * {$tipo_vac}) ) AS monto
			FROM ca_historial h
				INNER JOIN ca_cliente_cartera cc ON h.idcliente_cartera=cc.idcliente_cartera
				INNER JOIN (SELECT agencia FROM {$val} WHERE TRIM(agencia)!='' LIMIT 1) a ON h.agencia=a.agencia
			WHERE h.Fproceso='{$fechaProceso}' AND cc.idcartera IN ({$key}) 
		) t 
		WHERE t.ultima_llamada IS NOT NULL
		GROUP BY t.gestion
		UNION ALL
		SELECT t.gestion, COUNT(t.contrato) AS contratos, SUM(CAST(t.monto AS DECIMAL(10,2)) ) AS montos
		FROM (
			SELECT 'BASE_ASIGNADA' AS gestion, h.contrato, (SELECT DATE(fecha) FROM ca_llamada ll WHERE ll.idllamada=cc.id_ultima_llamada and ll.tipo<>'S') AS ultima_llamada
				, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * {$tipo_cambio}, saldohoy * {$tipo_vac}) ) AS monto
			FROM ca_historial h
				INNER JOIN ca_cliente_cartera cc ON h.idcliente_cartera=cc.idcliente_cartera
				INNER JOIN (SELECT agencia FROM {$val} WHERE TRIM(agencia)!='' LIMIT 1) a ON h.agencia=a.agencia
			WHERE h.Fproceso='{$fechaProceso}' AND cc.idcartera IN ({$key}) 
		) t 
		GROUP BY t.gestion
EOT;
		$prConsolidado = $connection->prepare($sqlConsolidado);
		$prConsolidado->execute();
		
		//~ $datas[$key] = $prConsolidado->fetchAll(PDO::FETCH_ASSOC);
		while( $row = $prConsolidado->fetch(PDO::FETCH_ASSOC) ) {
			$datas[$key][$row["tramo"]] = array("tramo" => $row["tramo"], "contratos" => $row["contratos"], "montos" => $row["montos"]);
		}
	}

//~ Contenido de las Cabeceras
	$head = array(
		"T1" => array("tramo"=>"T1", "contratos"=>"0", "montos"=>"0"),
		"T2" => array("tramo"=>"T2", "contratos"=>"0", "montos"=>"0"),
		"T3" => array("tramo"=>"T3", "contratos"=>"0", "montos"=>"0"),
		"SIN_GESTION" => array("tramo"=>"SIN_GESTION", "contratos"=>"0", "montos"=>"0"),
		"GESTIONADO" => array("tramo"=>"GESTIONADO", "contratos"=>"0", "montos"=>"0"),
		"BASE_ASIGNADA" => array("tramo"=>"BASE_ASIGNADA", "contratos"=>"0", "montos"=>"0")
	);

//~ Rellenar los datos
	foreach ($datas as $k => $v)
	{
		$final[$k] = array_merge($head, $v);
	}

//~ Resumen de Informe de Carterizacion
	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Resumen_Cobertura');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'REPORTE DE COBERTURA DE CONTRATOS BBVA');
	$xls->getActiveSheet()->mergeCells("E2:I2");
	$xls->getActiveSheet()->getStyle('E2:I2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$xls->getActiveSheet()->SetCellValue('A4', 'PROCESO');
	$xls->getActiveSheet()->mergeCells("A4:A5");
	$xls->getActiveSheet()->getStyle('A4')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$xls->getActiveSheet()->SetCellValue('A6', $fechaProceso);
	$xls->getActiveSheet()->mergeCells("A6:A11");
	$xls->getActiveSheet()->getStyle('A6')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

//~ Listar los Datos
	$c=1;
	$f=6;
	foreach ($final as $k => $v)
	{
		$xls->getActiveSheet()->SetCellValue($columna[$c].'4', array_search($k, $nombreCartera));
		$xls->getActiveSheet()->SetCellValue($columna[$c].'5', "Tramo");
		$xls->getActiveSheet()->SetCellValue($columna[$c+1].'5', "Contrato");
		$xls->getActiveSheet()->SetCellValue($columna[$c+2].'5', "Monto");
		foreach ($v as $ke => $va) 
		{
			$xls->getActiveSheet()->SetCellValue($columna[$c].$f, $va["tramo"]);
			$xls->getActiveSheet()->SetCellValue($columna[$c+1].$f, $va["contratos"]);
			$xls->getActiveSheet()->SetCellValue($columna[$c+2].$f, $va["montos"]);
			$f++;
		}
		$f=6;
		$c = $c + 3;
	}

//~ Pestania de Detalle de Informe de Carterizacion
	$xls->createSheet();
	$xls->setActiveSheetIndex(1);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Detalle');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'CARTERAS BBVA');
	$xls->getActiveSheet()->mergeCells("E2:H2");
	$xls->getActiveSheet()->getStyle('E2:H2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$prDetalle = $connection->prepare($sqlDetalle);
	if ($prDetalle->execute())
	{
		$xls->getActiveSheet()->SetCellValue('A4', 'PROCESO');
		$xls->getActiveSheet()->SetCellValue('B4', 'AGENCIA');
		$xls->getActiveSheet()->SetCellValue('C4', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('D4', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('E4', 'SUB-PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('F4', 'CONTRATO');
		$xls->getActiveSheet()->SetCellValue('G4', 'CODCENT');
		$xls->getActiveSheet()->SetCellValue('H4', 'NOMBRE');
		$xls->getActiveSheet()->SetCellValue('I4', 'DIVISA');
		$xls->getActiveSheet()->SetCellValue('J4', 'SALDO-HOY');
		$xls->getActiveSheet()->SetCellValue('K4', 'DIAS-VENC');
		$xls->getActiveSheet()->SetCellValue('L4', 'UBIGEO');
		$xls->getActiveSheet()->SetCellValue('M4', 'DEPARTAMENTO');
		$xls->getActiveSheet()->SetCellValue('N4', 'DIST_PROV');
		$xls->getActiveSheet()->SetCellValue('O4', 'TRAMO-DIA');
		$xls->getActiveSheet()->SetCellValue('P4', 'MARCA');
		$xls->getActiveSheet()->SetCellValue('Q4', 'ULTIMA');
		$xls->getActiveSheet()->SetCellValue('R4', 'TRAMO');
		$xls->getActiveSheet()->SetCellValue('S4', 'MONTO');
		$xls->getActiveSheet()->SetCellValue('T4', 'RANGO DEUDA');
		$xls->getActiveSheet()->getStyle('A4:T4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		while( $fila = $prDetalle->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('A'.$f, $fila["Fproceso"]);
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["agencia"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('E'.$f, $fila["nom_subprod"]);
			$xls->getActiveSheet()->SetCellValue('F'.$f, '="'.$fila["contrato"].'"');
			$xls->getActiveSheet()->SetCellValue('G'.$f, '="'.$fila["codcent"].'"');
			$xls->getActiveSheet()->SetCellValue('H'.$f, $fila["nombre"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $fila["divisa"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $fila["saldohoy"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $fila["diavenc"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["dpto"]);
			$xls->getActiveSheet()->SetCellValue('N'.$f, $fila["dist_prov"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $fila["tramo_dia"]);
			$xls->getActiveSheet()->SetCellValue('P'.$f, $fila["marca"]);
			$xls->getActiveSheet()->SetCellValue('Q'.$f, $fila["ultima_llamada"]);
			$xls->getActiveSheet()->SetCellValue('R'.$f, $fila["tramo"]);
			$xls->getActiveSheet()->SetCellValue('S'.$f, $fila["monto"]);
			$xls->getActiveSheet()->SetCellValue('T'.$f, $fila["rango_deuda"]);
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//	Funcion para personalizar color
	function color($txt,$fon,$negra=true,$bord=true,$ali=true)
	{
		//	Alineacion
		if($ali==true)
		{
			$alinea=array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		else
		{
			$alinea=array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
		//	Border
		if($bord==true)
		{
			$border=array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
		}
		else
		{
			$border=array();
		}

		$set=array(
			'font' => array( 'bold' => $negra, 'color' => array('argb' => $txt) ),
			'alignment' => $alinea,
			'borders' => $border,
			'fill' => array( 'type'	=> PHPExcel_Style_Fill::FILL_SOLID,	'color'	=> array('argb' => $fon) )
		);
		return $set;
	}

//~ Exportar a Excel 2003
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Resumen_Cobertura_'.$now.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
	$objWriter->save('php://output');
	exit();


?>
