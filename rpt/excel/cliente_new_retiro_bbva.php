<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	require_once 'cliente_new_retiro_function.php';

	date_default_timezone_set('America/Lima');

	$cartera = $_REQUEST['carteras'];
	$ini = $_REQUEST['fecha_inicio'];
	$fin = $_REQUEST['fecha_fin'];
	$agencia = $_REQUEST['agencias'];
	$tipocambio=$_REQUEST['tipocambio'];
	$tipovac=$_REQUEST['tipovac'];
	$agenciaDetalle = filtroComercial($_REQUEST['agenciaDetalle']);

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Nuevo_Retirado');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	$now = date("Ymd_His");

//~ Columnas Estaticas
	$columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	$sql_table_ini = create_table($ini, "Inicio", $now, $agencia, $agenciaDetalle,$cartera);
	$sql_table_fin = create_table($fin, "Fin", $now, $agencia, $agenciaDetalle,$cartera);

//~ Pestania de Clientes Nuevos y Retirados
	$prIni = $connection->prepare($sql_table_ini);
	$prIni->execute();

	$prFin = $connection->prepare($sql_table_fin);
	$prFin->execute();

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'RESUMEN DE CLIENTES NUEVOS Y RETIRADOS');
	$xls->getActiveSheet()->mergeCells("E2:I2");
	$xls->getActiveSheet()->getStyle('E2:I2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	//~ Por Territorio
	$xls->getActiveSheet()->SetCellValue('B4', 'POR TERRITORIO');
	$xls->getActiveSheet()->mergeCells("B4:D4");
	$xls->getActiveSheet()->getStyle('B4:D4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_retiro_territorio = retiro_territorio($ini, "tmp_Fin_".$now, $agencia, $agenciaDetalle,$cartera);
	$prRetiroTerritorio = $connection->prepare($sql_retiro_territorio);
	if ($prRetiroTerritorio->execute())
	{
		$xls->getActiveSheet()->SetCellValue('B6', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('C6', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('D6', 'CLIENTES');
		$xls->getActiveSheet()->getStyle('B6:D6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		while( $fila = $prRetiroTerritorio->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["contrato"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, $fila["clientes"]);
			$f++;
		}
	}

	//~ Por Tramo
	$xls->getActiveSheet()->SetCellValue('G4', 'POR TRAMO');
	$xls->getActiveSheet()->mergeCells("G4:I4");
	$xls->getActiveSheet()->getStyle('G4:I4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_retiro_tramo = retiro_tramo($ini, "tmp_Fin_".$now, $agencia, $agenciaDetalle,$cartera);
	$prRetiroTramo = $connection->prepare($sql_retiro_tramo);
	if ($prRetiroTramo->execute())
	{
		$xls->getActiveSheet()->SetCellValue('G6', 'TRAMO');
		$xls->getActiveSheet()->SetCellValue('H6', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('I6', '%');
		$xls->getActiveSheet()->getStyle('G6:I6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		$total=0;
		$fila = $prRetiroTramo->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['tramo']=='_TOTALES')
			{
				$total = $v['cantidad'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$txtTotal = ($va["tramo"]=='_TOTALES') ? "TOTALES" : $va["tramo"];
			$xls->getActiveSheet()->SetCellValue('G'.$f, $txtTotal);
			$xls->getActiveSheet()->SetCellValue('H'.$f, $va["cantidad"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, round( ( ($va["cantidad"] / $total) * 100 ), 2) );
			$f++;
		}
	}

	//~ Por Ubigeo
	$xls->getActiveSheet()->SetCellValue('L4', 'POR UBIGEO');
	$xls->getActiveSheet()->mergeCells("L4:O4");
	$xls->getActiveSheet()->getStyle('L4:O4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_nuevo_ubigeo = nuevo_ubigeo($fin, "tmp_Inicio_".$now, $agencia, $agenciaDetalle,$cartera);
	$prNuevoUbigeo = $connection->prepare($sql_nuevo_ubigeo);
	if ($prNuevoUbigeo->execute())
	{
		$xls->getActiveSheet()->SetCellValue('L6', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('M6', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('N6', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('O6', 'MONTOS');
		$xls->getActiveSheet()->getStyle('L6:O6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		while( $fila = $prNuevoUbigeo->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["contrato"]);
			$xls->getActiveSheet()->SetCellValue('N'.$f, $fila["clientes"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $fila["monto"]);
			$f++;
		}
	}

	//~ Por Producto
	$xls->getActiveSheet()->SetCellValue('L14', 'POR PRODUCTO');
	$xls->getActiveSheet()->mergeCells("L14:O14");
	$xls->getActiveSheet()->getStyle('L14:O14')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_nuevo_producto = nuevo_producto($fin, "tmp_Inicio_".$now, $agencia, $agenciaDetalle,$cartera);
	$prNuevoProducto = $connection->prepare($sql_nuevo_producto);
	if ($prNuevoProducto->execute())
	{
		$xls->getActiveSheet()->SetCellValue('L16', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('M16', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('N16', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('O16', 'MONTOS');
		$xls->getActiveSheet()->getStyle('L16:O16')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=17;
		while( $fila = $prNuevoProducto->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["contrato"]);
			$xls->getActiveSheet()->SetCellValue('N'.$f, $fila["clientes"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $fila["monto"]);
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Detalle de Clientes Nuevos y Retirados
	$xls->createSheet();
	$xls->setActiveSheetIndex(1);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Detalle_Retirado');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'DETALLE DE CLIENTES NUEVOS Y RETIRADOS');
	$xls->getActiveSheet()->mergeCells("E2:H2");
	$xls->getActiveSheet()->getStyle('E2:H2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$sql_detalle = detalle_nuevo_retirado($ini, $fin, "tmp_Inicio_".$now, "tmp_Fin_".$now, $agencia, $agenciaDetalle,$cartera);
	$prDetalle = $connection->prepare($sql_detalle);
	if ($prDetalle->execute())
	{
		$xls->getActiveSheet()->SetCellValue('A4', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('B4', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('C4', 'SUB-PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('D4', 'CONTRATO');
		$xls->getActiveSheet()->SetCellValue('E4', 'CLIENTE');
		$xls->getActiveSheet()->SetCellValue('F4', 'NOMBRE');
		$xls->getActiveSheet()->SetCellValue('G4', 'DIVISA');
		$xls->getActiveSheet()->SetCellValue('H4', 'SALDO-HOY');
		$xls->getActiveSheet()->SetCellValue('I4', 'DIAS-VENC');
		$xls->getActiveSheet()->SetCellValue('J4', 'UBIGEO');
		$xls->getActiveSheet()->SetCellValue('K4', 'NUEVO');
		$xls->getActiveSheet()->SetCellValue('L4', 'ESTADO');
		$xls->getActiveSheet()->getStyle('A4:L4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		while( $fila = $prDetalle->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('A'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["nom_subprod"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, '="'.$fila["contrato"].'"');
			$xls->getActiveSheet()->SetCellValue('E'.$f, '="'.$fila["codcent"].'"');
			$xls->getActiveSheet()->SetCellValue('F'.$f, $fila["Nombre"]);
			$xls->getActiveSheet()->SetCellValue('G'.$f, $fila["divisa"]);
			$xls->getActiveSheet()->SetCellValue('H'.$f, $fila["saldoHoy"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $fila["diavenc"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $fila["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $fila["Nuevo"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["ProNew"]);
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Resumen de Fotocartera
	$xls->createSheet();
	$xls->setActiveSheetIndex(2);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Resumen_FotoCartera');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('G2', 'RESUMEN DE FOTOCARTERA');
	$xls->getActiveSheet()->mergeCells("G2:K2");
	$xls->getActiveSheet()->getStyle('G2:K2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	//~ Territorio
	$xls->getActiveSheet()->SetCellValue('B4', 'POR TERRITORIO');
	$xls->getActiveSheet()->mergeCells("B4:E4");
	$xls->getActiveSheet()->getStyle('B4:E4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_territorio = resumen_foto_territorio($cartera,$fin);
	$prResumenFotoTerri = $connection->prepare($sql_resumen_foto_territorio);
	if ($prResumenFotoTerri->execute())
	{
		$xls->getActiveSheet()->SetCellValue('B6', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('C6', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('D6', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('E6', 'DEUDAS');
		$xls->getActiveSheet()->getStyle('B6:E6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		while( $fila = $prResumenFotoTerri->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["contrato"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, $fila["clientes"]);
			$xls->getActiveSheet()->SetCellValue('E'.$f, $fila["monto"]);
			$f++;
		}
	}

	//~ Ubigeo
	$xls->getActiveSheet()->SetCellValue('H4', 'POR UBIGEO');
	$xls->getActiveSheet()->mergeCells("H4:L4");
	$xls->getActiveSheet()->getStyle('H4:L4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_ubi = resumen_foto_ubigeo($cartera,$fin);
	$prResumenFotoUbi = $connection->prepare($sql_resumen_foto_ubi);
	if ($prResumenFotoUbi->execute())
	{
		$xls->getActiveSheet()->SetCellValue('H6', 'UBIGEO');
		$xls->getActiveSheet()->SetCellValue('I6', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('J6', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('K6', 'DEUDAS');
		$xls->getActiveSheet()->SetCellValue('L6', '% SOLES');
		$xls->getActiveSheet()->getStyle('H6:L6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		$total=0;
		$fila = $prResumenFotoUbi->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['ubigeo']=='TOTALES')
			{
				$total = $v['monto'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('H'.$f, $va["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $va["contrato"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $va["clientes"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $va["monto"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, round( ( ($va["monto"] / $total) * 100 ), 2));
			$f++;
		}
	}

	//~ Producto
	$xls->getActiveSheet()->SetCellValue('N4', 'POR PRODUCTO');
	$xls->getActiveSheet()->mergeCells("N4:R4");
	$xls->getActiveSheet()->getStyle('N4:R4')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_prod = resumen_foto_producto($cartera,$fin);
	$prResumenFotoProd = $connection->prepare($sql_resumen_foto_prod);
	if ($prResumenFotoProd->execute())
	{
		$xls->getActiveSheet()->SetCellValue('N6', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('O6', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('P6', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('Q6', 'DEUDAS');
		$xls->getActiveSheet()->SetCellValue('R6', '% SOLES');
		$xls->getActiveSheet()->getStyle('N6:R6')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=7;
		$total=0;
		$fila = $prResumenFotoProd->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['producto']=='TOTALES')
			{
				$total = $v['monto'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('N'.$f, $va["producto"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $va["contrato"]);
			$xls->getActiveSheet()->SetCellValue('P'.$f, $va["clientes"]);
			$xls->getActiveSheet()->SetCellValue('Q'.$f, $va["monto"]);
			$xls->getActiveSheet()->SetCellValue('R'.$f, round( ( ($va["monto"] / $total) * 100 ), 2));
			$f++;
		}
	}

	//~ Tramo
	$xls->getActiveSheet()->SetCellValue('B30', 'POR TRAMO');
	$xls->getActiveSheet()->mergeCells("B30:F30");
	$xls->getActiveSheet()->getStyle('B30:F30')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_tramo = resumen_foto_tramo($cartera,$fin);
	$prResumenFotoTramo = $connection->prepare($sql_resumen_foto_tramo);
	if ($prResumenFotoTramo->execute())
	{
		$xls->getActiveSheet()->SetCellValue('B32', 'TRAMO');
		$xls->getActiveSheet()->SetCellValue('C32', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('D32', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('E32', 'DEUDAS');
		$xls->getActiveSheet()->SetCellValue('F32', '% SOLES');
		$xls->getActiveSheet()->getStyle('B32:F32')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=33;
		$total=0;
		$fila = $prResumenFotoTramo->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['tramo']=='TOTALES')
			{
				$total = $v['monto'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('B'.$f, $va["tramo"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $va["contrato"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, $va["clientes"]);
			$xls->getActiveSheet()->SetCellValue('E'.$f, $va["monto"]);
			$xls->getActiveSheet()->SetCellValue('F'.$f, round( ( ($va["monto"] / $total) * 100 ), 2));
			$f++;
		}
	}

	//~ Grupo
	$xls->getActiveSheet()->SetCellValue('H30', 'POR GRUPO');
	$xls->getActiveSheet()->mergeCells("H30:L30");
	$xls->getActiveSheet()->getStyle('H30:L30')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_grupo = resumen_foto_grupo($cartera,$fin);
	$prResumenFotoGrupo = $connection->prepare($sql_resumen_foto_grupo);
	if ($prResumenFotoGrupo->execute())
	{
		$xls->getActiveSheet()->SetCellValue('H32', 'GRUPO');
		$xls->getActiveSheet()->SetCellValue('I32', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('J32', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('K32', 'DEUDAS');
		$xls->getActiveSheet()->SetCellValue('L32', '% SOLES');
		$xls->getActiveSheet()->getStyle('H32:L32')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=33;
		$total=0;
		$fila = $prResumenFotoGrupo->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['marca']=='TOTALES')
			{
				$total = $v['monto'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('H'.$f, $va["marca"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $va["contrato"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $va["clientes"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $va["monto"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, round( ( ($va["monto"] / $total) * 100 ), 2));
			$f++;
		}
	}

	//~ Monto
	$xls->getActiveSheet()->SetCellValue('N30', 'POR MONTO');
	$xls->getActiveSheet()->mergeCells("N30:R30");
	$xls->getActiveSheet()->getStyle('N30:R30')->applyFromArray(color('FF0D0D0D','FFDB9999', true, true, true));

	$sql_resumen_foto_monto = resumen_foto_monto($cartera,$fin);
	$prResumenFotoMonto = $connection->prepare($sql_resumen_foto_monto);
	if ($prResumenFotoMonto->execute())
	{
		$xls->getActiveSheet()->SetCellValue('N32', 'MONTO');
		$xls->getActiveSheet()->SetCellValue('O32', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('P32', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('Q32', 'DEUDAS');
		$xls->getActiveSheet()->SetCellValue('R32', '% SOLES');
		$xls->getActiveSheet()->getStyle('N32:R32')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=33;
		$total=0;
		$fila = $prResumenFotoMonto->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $k => $v)
		{
			if ($v['rangoMonto']=='TOTALES')
			{
				$total = $v['monto'];
			}
		}
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('N'.$f, $va["rangoMonto"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $va["contrato"]);
			$xls->getActiveSheet()->SetCellValue('P'.$f, $va["clientes"]);
			$xls->getActiveSheet()->SetCellValue('Q'.$f, $va["monto"]);
			$xls->getActiveSheet()->SetCellValue('R'.$f, round( ( ($va["monto"] / $total) * 100 ), 2));
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Detalle de FotoCartera
	$xls->createSheet();
	$xls->setActiveSheetIndex(3);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Fotocartera');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'FOTOCARTERA');
	$xls->getActiveSheet()->mergeCells("E2:G2");
	$xls->getActiveSheet()->getStyle('E2:G2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$sql_fotocartera = sql_fotocartera($cartera,$fin,$tipocambio,$tipovac);
	$prFotoCartera = $connection->prepare($sql_fotocartera);
	if ($prFotoCartera->execute())
	{
		$xls->getActiveSheet()->SetCellValue('A4', 'AGENCIA');
		$xls->getActiveSheet()->SetCellValue('B4', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('C4', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('D4', 'SUB-PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('E4', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('F4', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('G4', 'NOMBRES');
		$xls->getActiveSheet()->SetCellValue('H4', 'DIVISA');
		$xls->getActiveSheet()->SetCellValue('I4', 'SALDO-HOY');
		$xls->getActiveSheet()->SetCellValue('J4', 'DIAS-VENC');
		$xls->getActiveSheet()->SetCellValue('K4', 'UBIGEO');
		$xls->getActiveSheet()->SetCellValue('L4', 'DEPARTAMENTO');
		$xls->getActiveSheet()->SetCellValue('M4', 'DIST_PROV');
		$xls->getActiveSheet()->SetCellValue('N4', 'TRAMO');
		$xls->getActiveSheet()->SetCellValue('O4', 'MARCA');
		$xls->getActiveSheet()->SetCellValue('P4', 'OFICINA');
		$xls->getActiveSheet()->SetCellValue('Q4', 'OFICINA_NOMBRE');
		$xls->getActiveSheet()->SetCellValue('R4','NETO SOLES');
		$xls->getActiveSheet()->getStyle('A4:R4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		while( $fila = $prFotoCartera->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('A'.$f, $fila["agencia"]);
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, $fila["nom_subprod"]);
			$xls->getActiveSheet()->SetCellValue('E'.$f, '="'.$fila["contrato"].'"');
			$xls->getActiveSheet()->SetCellValue('F'.$f, '="'.$fila["codcent"].'"');
			$xls->getActiveSheet()->SetCellValue('G'.$f, $fila["Nombre"]);
			$xls->getActiveSheet()->SetCellValue('H'.$f, $fila["divisa"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $fila["saldohoy"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $fila["diavenc"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $fila["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["departamento"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["dist_prov"]);
			$xls->getActiveSheet()->SetCellValue('N'.$f, $fila["tramo_dia"]);
			$xls->getActiveSheet()->SetCellValue('O'.$f, $fila["marca"]);
			$xls->getActiveSheet()->SetCellValue('P'.$f, $fila["oficina"]);
			$xls->getActiveSheet()->SetCellValue('Q'.$f, $fila["oficina2"]);
			$xls->getActiveSheet()->SetCellValue('R'.$f, $fila["neto_soles"]);			
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Cambio
	$xls->createSheet();
	$xls->setActiveSheetIndex(4);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Cambio');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('C2', 'CAMBIO DE TRAMO');
	$xls->getActiveSheet()->mergeCells("C2:E2");
	$xls->getActiveSheet()->getStyle('C2:E2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$sql_cambio_actual = sql_tramo_actual($fin, "tmp_Inicio_".$now, $agencia, $agenciaDetalle,$cartera);
	$prCambioTramo = $connection->prepare($sql_cambio_actual);
	if ($prCambioTramo->execute())
	{
		$xls->getActiveSheet()->SetCellValue('B4', 'TRAMO ACTUAL');
		$xls->getActiveSheet()->mergeCells("B4:B5");
		$xls->getActiveSheet()->SetCellValue('C4', 'TRAMO ANTERIOR');
		$xls->getActiveSheet()->mergeCells("C4:E4");
		$xls->getActiveSheet()->SetCellValue('F4', 'TOTAL');
		$xls->getActiveSheet()->mergeCells("F4:F5");
		$xls->getActiveSheet()->SetCellValue('C5', 'TRAMO_1');
		$xls->getActiveSheet()->SetCellValue('D5', 'TRAMO_2');
		$xls->getActiveSheet()->SetCellValue('E5', 'TRAMO_3');
		$xls->getActiveSheet()->getStyle('B4:B5')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$xls->getActiveSheet()->getStyle('C4:E4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$xls->getActiveSheet()->getStyle('F4:F5')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$xls->getActiveSheet()->getStyle('C5:E5')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=6;
		$total=0;
		$fila = $prCambioTramo->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila as $ke => $va)
		{
			$xls->getActiveSheet()->SetCellValue('B'.$f, $va["tramo_actual"]);
			$xls->getActiveSheet()->SetCellValue('F'.$f, $va["contrato"]);
			$f++;
		}
	}

	$total01=0;
	$total02=0;
	$total03=0;

	$sql_cambio_antes_1 = sql_tramo_anterior($fin, "tmp_Inicio_".$now, "TRAM0_1", $agencia, $agenciaDetalle,$cartera);
	$prCambioTramo01 = $connection->prepare($sql_cambio_antes_1);
	if ($prCambioTramo01->execute())
	{
		$fila01 = $prCambioTramo01->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila01 as $ke => $va)
		{
			if ($va['tramo_anterior']=='TRAM0_2')
			{
				$xls->getActiveSheet()->SetCellValue('D6', $va["contrato"]);
				$total02 = $total02 + $va["contrato"];
			}
			if ($va['tramo_anterior']=='TRAM0_3')
			{
				$xls->getActiveSheet()->SetCellValue('E6', $va["contrato"]);
				$total03 = $total03 + $va["contrato"];
			}
		}
	}

	$sql_cambio_antes_2 = sql_tramo_anterior($fin, "tmp_Inicio_".$now, "TRAM0_2", $agencia, $agenciaDetalle,$cartera);
	$prCambioTramo02 = $connection->prepare($sql_cambio_antes_2);
	if ($prCambioTramo02->execute())
	{
		$fila02 = $prCambioTramo02->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila02 as $ke => $va)
		{
			if ($va['tramo_anterior']=='TRAM0_1')
			{
				$xls->getActiveSheet()->SetCellValue('C7', $va["contrato"]);
				$total01 = $total01 + $va["contrato"];
			}
			if ($va['tramo_anterior']=='TRAM0_3')
			{
				$xls->getActiveSheet()->SetCellValue('E7', $va["contrato"]);
				$total03 = $total03 + $va["contrato"];
			}
		}
	}

	$sql_cambio_antes_3 = sql_tramo_anterior($fin, "tmp_Inicio_".$now, "TRAM0_3", $agencia, $agenciaDetalle,$cartera);
	$prCambioTramo03 = $connection->prepare($sql_cambio_antes_3);
	if ($prCambioTramo03->execute())
	{
		$fila03 = $prCambioTramo03->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fila03 as $ke => $va)
		{
			if ($va['tramo_anterior']=='TRAMO_1')
			{
				$xls->getActiveSheet()->SetCellValue('C8', $va["contrato"]);
				$total01 = $total01 + $va["contrato"];
			}
			if ($va['tramo_anterior']=='TRAM0_2')
			{
				$xls->getActiveSheet()->SetCellValue('D8', $va["contrato"]);
				$total02 = $total02 + $va["contrato"];
			}
		}
	}

	$xls->getActiveSheet()->SetCellValue('C9', $total01);
	$xls->getActiveSheet()->SetCellValue('D9', $total02);
	$xls->getActiveSheet()->SetCellValue('E9', $total03);

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}

//~ Pestania de Detalle de Cambio de Tramo
	$xls->createSheet();
	$xls->setActiveSheetIndex(5);
	$xls->getDefaultStyle()->getFont()->setName('Calibri');
	$xls->getDefaultStyle()->getFont()->setSize(11);
	$xls->getActiveSheet()->setTitle('Detalle_Cambio');
	$xls->getActiveSheet()->getSheetView()->setZoomScale(75);

	//~ Titulo
	$xls->getActiveSheet()->SetCellValue('E2', 'DETALLE DE CAMBIO DE TRAMO');
	$xls->getActiveSheet()->mergeCells("E2:G2");
	$xls->getActiveSheet()->getStyle('E2:G2')->applyFromArray(color('FF0D0D0D','FFFFFFFF', true, true, true));

	$sql_detalle_cambio = sql_tramo_detalle($fin, "tmp_Inicio_".$now, $agencia, $agenciaDetalle,$cartera);
	$prDetalleCambio = $connection->prepare($sql_detalle_cambio);
	if ($prDetalleCambio->execute())
	{
		$xls->getActiveSheet()->SetCellValue('A4', 'TERRITORIO');
		$xls->getActiveSheet()->SetCellValue('B4', 'PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('C4', 'SUB-PRODUCTO');
		$xls->getActiveSheet()->SetCellValue('D4', 'CONTRATOS');
		$xls->getActiveSheet()->SetCellValue('E4', 'CLIENTES');
		$xls->getActiveSheet()->SetCellValue('F4', 'NOMBRES');
		$xls->getActiveSheet()->SetCellValue('G4', 'DIVISA');
		$xls->getActiveSheet()->SetCellValue('H4', 'SALDO-HOY');
		$xls->getActiveSheet()->SetCellValue('I4', 'DIAS-VENC');
		$xls->getActiveSheet()->SetCellValue('J4', 'UBIGEO');
		$xls->getActiveSheet()->SetCellValue('K4', 'TRAMO-ACTUAL');
		$xls->getActiveSheet()->SetCellValue('L4', 'TRAMO-ANTERIOR');
		$xls->getActiveSheet()->SetCellValue('M4', 'DIA-ANTERIOR');
		$xls->getActiveSheet()->getStyle('A4:M4')->applyFromArray(color('FFFFFFFF','FF8C2B2A', true, true, true));
		$f=5;
		while( $fila = $prDetalleCambio->fetch(PDO::FETCH_ASSOC) ) {
			$xls->getActiveSheet()->SetCellValue('A'.$f, $fila["territorio"]);
			$xls->getActiveSheet()->SetCellValue('B'.$f, $fila["producto"]);
			$xls->getActiveSheet()->SetCellValue('C'.$f, $fila["nom_subprod"]);
			$xls->getActiveSheet()->SetCellValue('D'.$f, '="'.$fila["contrato"].'"');
			$xls->getActiveSheet()->SetCellValue('E'.$f, '="'.$fila["codcent"].'"');
			$xls->getActiveSheet()->SetCellValue('F'.$f, $fila["nombre"]);
			$xls->getActiveSheet()->SetCellValue('G'.$f, $fila["divisa"]);
			$xls->getActiveSheet()->SetCellValue('H'.$f, $fila["saldohoy"]);
			$xls->getActiveSheet()->SetCellValue('I'.$f, $fila["diavenc"]);
			$xls->getActiveSheet()->SetCellValue('J'.$f, $fila["ubigeo"]);
			$xls->getActiveSheet()->SetCellValue('K'.$f, $fila["tramo_actual"]);
			$xls->getActiveSheet()->SetCellValue('L'.$f, $fila["tramo_anterior"]);
			$xls->getActiveSheet()->SetCellValue('M'.$f, $fila["dia_anterior"]);
			$f++;
		}
	}

	//~ Ajustar Tamanio a las Celdas
	for($j=0;$j<count($columna);$j++)
	{
		$xls->getActiveSheet()->getColumnDimension($columna[$j])->setAutoSize(true);
	}


//~ Eliminar Temporales
	$prDelIni = $connection->prepare("DROP TABLE tmp_Inicio_".$now);
	$prDelIni->execute();
	$prDelFin = $connection->prepare("DROP TABLE tmp_Fin_".$now);
	$prDelFin->execute();

//~ Exportar a Excel 2007
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Cliente_Nuevo_Retirado_'.$now.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
	$objWriter->save('php://output');
	exit;

?>
