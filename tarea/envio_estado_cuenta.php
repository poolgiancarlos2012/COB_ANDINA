<?php

date_default_timezone_set('America/Lima');
error_reporting(E_ALL);


ini_set('include_path', ini_get('include_path').';C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/');
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel.php';/** PHPExcel_Writer_Excel2007 */
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.phpmailer.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.smtp.php';

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");

$font = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 8,
    'name'  => 'Verdana'
));
$title = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 13,
    'name'  => 'Verdana'
));
$font_cabe_blue = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '002060'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$font_header = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '002060'),
    'size'  => 8,
    'name'  => 'Verdana'
));
$font_empresa_blue = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '1F4E78'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$font_subtotal = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$border_mefium = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_MEDIUM  
      )
));
$border_thin = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN  
      )
    )
);

$fondo_amarillo = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'FFFF00')
    )
);

$fondo_celeste = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'C1EFFF')
    )
);
$fondo_morado_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'D9E1F2')
    )
);
$fondo_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'F0F4FF')
    )
);
$fondo_celeste_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'DDEBF7')
    )
);

$fondo_rojo_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'FFE1E1')
    )
);

$fondo_verde_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'D4FDCF')
    )
);

$fecha_envio=date('Y-m-d H:i:s');

#VALIDA SI HAY PROGRAMACION PARA ESTADO DE CUENTA

$idcartera=1;

$sqlasunto="	SELECT 
				idcorreo_asunto,
				asunto,
				cuerpo,
				fecha_envio,
				estado 
				FROM 
				ca_correo_asunto 
				WHERE 
				estado=1 AND 
				idcorreo_asunto=1 AND
				DATE(fecha_envio)=DATE(NOW())";
$prasunto = $connection->prepare($sqlasunto);
$prasunto->execute();
$dataasunto=$prasunto->fetchAll(PDO::FETCH_ASSOC);

$speech=$dataasunto[0]['cuerpo'];
$idcorreo_asunto=$dataasunto[0]['idcorreo_asunto'];

if(!empty($dataasunto) AND count($dataasunto)==1){

	# DEUDA_AL

	$deuda_al="	SELECT
				DISTINCT DATE_FORMAT(DATE(STR_TO_DATE(dato28, '%d/%m/%Y')),'%d/%m/%Y') AS 'fecha_al'
				FROM ca_detalle_cuenta WHERE estado=1 AND idcartera=$idcartera";
	$prdeuda_al = $connection->prepare($deuda_al);
	$prdeuda_al->execute();
	$datadeuda_al=$prdeuda_al->fetchAll(PDO::FETCH_ASSOC);

	$fecha_al=$datadeuda_al[0]['fecha_al'];

	$var = $fecha_al;
	$date = str_replace('/', '-', $var);
	$Date_al=date('Y-m-d', strtotime($date));

	# RECORRE CADA CLIENTE CON DEUDA

	$cli_deuda="	SELECT
					cli.idcliente AS 'IDCLIENTE',
					cli.codigo AS 'CODIGO_CLIENTE',
					cli.razon_social AS 'RAZON_SOCIAL',
					cli.numero_documento AS 'NUMERO_DOCUMENTO',
					(SELECT CONCAT(detcu.dato1,' ',detcu.dato6) FROM ca_detalle_cuenta detcu WHERE detcu.codigo_cliente=cli.codigo AND detcu.estado=1 AND detcu.idcartera=$idcartera LIMIT 1) AS 'VENDEDOR'
					FROM ca_envio en
					INNER JOIN ca_cliente cli ON en.idcliente=cli.idcliente
					WHERE
					en.fecha_envio IS NULL AND 
					cli.codigo NOT IN ('10225117182','10450112149','20273962752','20481050848') -- AND
					-- en.idcliente=373
					LIMIT 10;";

	$prcli_deuda = $connection->prepare($cli_deuda);
	$prcli_deuda->execute();
	$datacli_deuda=$prcli_deuda->fetchAll(PDO::FETCH_ASSOC);

	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0)->setTitle("DOCUMENTOS");

	for ($i=0; $i <=count($datacli_deuda)-1 ; $i++) { 

		$idclienteupdate=$datacli_deuda[$i]['IDCLIENTE'];

		# CAPTURO TODOS LOS DOCUMENTOS OSEA SU ESTADO DE CUENTA DE CADA CLIENTE
		$xls = new PHPExcel();
		$xls->setActiveSheetIndex(0)->setTitle("ESTADO_CUENTA");
		$xls->getActiveSheet()->mergeCells("C2:I2");
		$xls->getActiveSheet()->SetCellValue("C2","DOCUMENTOS PENDIENTES POR CLIENTE");
		$xls->getActiveSheet()->getStyle('C2')->applyFromArray($title);
		$xls->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls->getActiveSheet()->SetCellValue("K2",$fecha_al);
		$xls->getActiveSheet()->getStyle('K2')->applyFromArray($font);
		$xls->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$xls->getActiveSheet()->SetCellValue("A4","CLIENTE: ".$datacli_deuda[$i]['RAZON_SOCIAL']);
		$xls->getActiveSheet()->SetCellValue("A5","RUC: ".$datacli_deuda[$i]['NUMERO_DOCUMENTO']);
		$xls->getActiveSheet()->SetCellValue("I4","RESPONSABLE: ".$datacli_deuda[$i]['VENDEDOR']);

		$xls->getActiveSheet()->getStyle('A4')->applyFromArray($font_cabe_blue);
		$xls->getActiveSheet()->getStyle('A5')->applyFromArray($font_cabe_blue);
		$xls->getActiveSheet()->getStyle('A6')->applyFromArray($font_cabe_blue);
		$xls->getActiveSheet()->getStyle('I4')->applyFromArray($font_cabe_blue);

		$fil=8;
		$col=0;	

		$xls->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(16);
		$xls->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(15);
		$xls->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(13);
		$xls->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(13);
		$xls->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(7);
		$xls->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(5);
		$xls->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(10);
		$xls->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(10);
		$xls->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(16);
		$xls->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(13);
		$xls->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(13);
		$xls->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(18);

		$codigo_cliente=$datacli_deuda[$i]['CODIGO_CLIENTE'];

		$sqldocumentos="	SELECT
							(SELECT emp.codigo FROM ca_empresa emp WHERE emp.nom_cruze=detcu.dato2) AS 'CODIGO_EMPRESA',
							(SELECT emp.nombre FROM ca_empresa emp WHERE emp.nom_cruze=detcu.dato2 ) AS 'EMPRESA',
							(SELECT cli.razon_social FROM ca_cliente cli WHERE cli.codigo=detcu.codigo_cliente) AS 'CLIENTE',
							(SELECT cli.numero_documento FROM ca_cliente cli WHERE cli.codigo=detcu.codigo_cliente) AS 'RUC',
							'' AS 'DIRECCION',
							detcu.dato1 AS 'CODIGO_VENDEDOR',
							detcu.dato6 AS 'VENDEDOR',
							detcu.dato8 AS 'TDOC',
							(SELECT tipdoc.nombre FROM ca_tipo_documento tipdoc WHERE tipdoc.abreviacion=detcu.dato8) AS 'TD',
							detcu.codigo_operacion AS 'SERIE_DOCUMENTO',
							DATE_FORMAT(detcu.fecha_emision,'%d/%m/%Y') AS 'FECHA_EMISION',
							DATE_FORMAT(detcu.fecha_vencimiento,'%d/%m/%Y') AS 'FECHA_VENCIMIENTO',
							detcu.moneda AS 'MONEDA',
							detcu.total_deuda AS 'IMPORTE',
							detcu.saldo_capital AS 'SALDO',
							-- detcu.dato11 AS 'DIAS_PLAZO',
							IF(detcu.dias_mora>0,detcu.dias_mora,'') AS 'TRANSC',
							(SELECT estdoc.descripcion FROM ca_estado_documento estdoc WHERE estdoc.abreviacion=detcu.dato22) AS 'EST_LETR',
							IF(detcu.dato22='CO',detcu.dato23,IF(detcu.dato22='BC',detcu.dato23,'')) AS 'BANCO',
							IF(detcu.dato22='CO',detcu.dato24,IF(detcu.dato22='BC',detcu.dato24,'')) AS 'NUM_COBRANZA',
							detcu.dato25 AS 'REFERENCIA'
							FROM
							ca_cuenta cu
							INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
							INNER JOIN ca_cliente_cartera clicar ON cu.idcliente_cartera=clicar.idcliente_cartera
							INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
							INNER JOIN ca_campania cam ON cam.idcampania=car.idcampania
							INNER JOIN ca_servicio serv ON serv.idservicio=cam.idservicio
							WHERE
							serv.idservicio=1 AND
							cam.idcampania=1 AND
							car.idcartera=$idcartera AND
							cu.estado=1 AND
							cu.idcartera=$idcartera AND
							detcu.estado=1 AND
							detcu.idcartera=$idcartera AND
							detcu.codigo_cliente='$codigo_cliente' AND
							IF(detcu.dato8='FT',DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1,IF(detcu.dato8='BV',DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1,1=1)) -- NO CONSIDERA LOS CONTADOS PARA FT y BV
							ORDER BY 1 ASC
							";
		$prdocumentos = $connection->prepare($sqldocumentos);
		$prdocumentos->execute();

		$cod="";
		$nroreg=0;
		$caisacsoles=0;$caisacdolares=0;
		$andexsoles=0;$andexdolares=0;
		$semillassoles=0;$semillasdolares=0;
		$sunnysoles=0;$sunnydolares=0;
		$cantcaisac=0;
		$cantandex=0;
		$cantsemillas=0;
		$cantsunny=0;
		$totaldolares=0;
		$totalsoles=0;
		$cantreg=$prdocumentos->rowCount();

		while($datos_muestra=$prdocumentos->fetch(PDO::FETCH_ASSOC)) {
			$fil++;
			$nroreg++;
			if($datos_muestra['CODIGO_EMPRESA']=='0002'){
				$cantcaisac++;
				if($datos_muestra["TDOC"]=='FT' || $datos_muestra["TDOC"]=='BV' || $datos_muestra["TDOC"]=='ND' || $datos_muestra["TDOC"]=='LT' || $datos_muestra["TDOC"]=='TK'){
					if($datos_muestra["MONEDA"]=='US'){
						$caisacdolares=$caisacdolares+$datos_muestra["SALDO"];
						$totaldolares=$totaldolares+$datos_muestra["SALDO"];
					}else if($datos_muestra["MONEDA"]=='MN'){
						$caisacsoles=$caisacsoles+$datos_muestra["SALDO"];
						$totalsoles=$totalsoles+$datos_muestra["SALDO"];
					}
				}
			}
			if($datos_muestra['CODIGO_EMPRESA']=='0003'){
				$cantandex++;
				if($datos_muestra["TDOC"]=='FT' || $datos_muestra["TDOC"]=='BV' || $datos_muestra["TDOC"]=='ND' || $datos_muestra["TDOC"]=='LT' || $datos_muestra["TDOC"]=='TK'){
					if($datos_muestra["MONEDA"]=='US'){
						$andexdolares=$andexdolares+$datos_muestra["SALDO"];
						$totaldolares=$totaldolares+$datos_muestra["SALDO"];
					}else if($datos_muestra["MONEDA"]=='MN'){
						$andexsoles=$andexsoles+$datos_muestra["SALDO"];
						$totalsoles=$totalsoles+$datos_muestra["SALDO"];
					}
				}
			}
			if($datos_muestra['CODIGO_EMPRESA']=='0004'){
				$cantsemillas++;
				if($datos_muestra["TDOC"]=='FT' || $datos_muestra["TDOC"]=='BV' || $datos_muestra["TDOC"]=='ND' || $datos_muestra["TDOC"]=='LT' || $datos_muestra["TDOC"]=='TK'){
					if($datos_muestra["MONEDA"]=='US'){
						$semillasdolares=$semillasdolares+$datos_muestra["SALDO"];
						$totaldolares=$totaldolares+$datos_muestra["SALDO"];
					}else if($datos_muestra["MONEDA"]=='MN'){
						$semillassoles=$semillassoles+$datos_muestra["SALDO"];
						$totalsoles=$totalsoles+$datos_muestra["SALDO"];
					}
				}
			}
			if($datos_muestra['CODIGO_EMPRESA']=='0016'){
				$cantsunny++;
				if($datos_muestra["TDOC"]=='FT' || $datos_muestra["TDOC"]=='BV' || $datos_muestra["TDOC"]=='ND' || $datos_muestra["TDOC"]=='LT' || $datos_muestra["TDOC"]=='TK'){
					if($datos_muestra["MONEDA"]=='US'){
						$sunnydolares=$sunnydolares+$datos_muestra["SALDO"];
						$totaldolares=$totaldolares+$datos_muestra["SALDO"];
					}else if($datos_muestra["MONEDA"]=='MN'){
						$sunnysoles=$sunnysoles+$datos_muestra["SALDO"];
						$totalsoles=$totalsoles+$datos_muestra["SALDO"];
					}
				}
			}
			if($cod!=$datos_muestra['CODIGO_EMPRESA']){
				if($fil==9){
					/*NOMBRE DE ANDINA/CAISAC*/
					$fil=$fil+1;
					$emp=$datos_muestra['CODIGO_EMPRESA'];
					$sqlempresa="SELECT nombre FROM ca_empresa WHERE codigo='$emp'";
					$prempresa = $connection->prepare($sqlempresa);
					$prempresa->execute();					
					$empresa=$prempresa->fetchAll(PDO::FETCH_ASSOC);					
					$xls->getActiveSheet()->SetCellValue($columna[$col+0].($fil-1), $empresa[0]['nombre']);
					$xls->getActiveSheet()->getStyle($columna[$col+0].($fil-1))->applyFromArray($font_empresa_blue);

					$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "TD");
					$xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "DOCUMENTO");
					$xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "FEC.EMISION");
					$xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "FEC.VENCI.");
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "TRANSC");
					$xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, "MO");
					$xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, "IMPORTE");
					$xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "SALDO");
					// $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "VEN");
					$xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, "ESTADO");
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, "BANCO");
					$xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, "NUM.COBRA.");
					// $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, "REFERENCIA");

					$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($fondo_morado_claro);
					$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($font_header);
					$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($border_thin);
					$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$fil=$fil+1;
				}else{				
					if($cod=='0002'){
						$fil=$fil+1;
						$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
						$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
						$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $caisacdolares);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$fil=$fil+1;
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $caisacsoles);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						/*NOMBRE DE ANDEX*/
						$fil=$fil+2;
						$emp=$datos_muestra['CODIGO_EMPRESA'];
						$sqlempresa="SELECT nombre FROM ca_empresa WHERE codigo='$emp'";
						$prempresa = $connection->prepare($sqlempresa);
						$prempresa->execute();					
						$empresa=$prempresa->fetchAll(PDO::FETCH_ASSOC);
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].($fil), $empresa[0]['nombre']);	
						$xls->getActiveSheet()->getStyle($columna[$col+0].($fil))->applyFromArray($font_empresa_blue);

						$fil=$fil+1; 
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "TD");
						$xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "DOCUMENTO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "FEC.EMISION");
						$xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "FEC.VENCI.");
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "TRANSC");
						$xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, "MO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, "IMPORTE");
						$xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "SALDO");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "VEN");
						$xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, "ESTADO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, "BANCO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, "NUM.COBRA.");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, "REFERENCIA");
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($fondo_morado_claro);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($font_header);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($border_thin);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


					}else if($cod=='0003'){
						$fil=$fil+1;
						$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
						$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
						$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $andexdolares);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$fil=$fil+1;
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $andexsoles);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						/*NOMBRE DE SEMILLAS*/
						$fil=$fil+2;
						$emp=$datos_muestra['CODIGO_EMPRESA'];
						$sqlempresa="SELECT nombre FROM ca_empresa WHERE codigo='$emp'";
						$prempresa = $connection->prepare($sqlempresa);
						$prempresa->execute();					
						$empresa=$prempresa->fetchAll(PDO::FETCH_ASSOC);
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].($fil), $empresa[0]['nombre']);	
						$xls->getActiveSheet()->getStyle($columna[$col+0].($fil))->applyFromArray($font_empresa_blue);

						$fil=$fil+1;
						//$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "CIA.");    
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "TD");
						$xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "DOCUMENTO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "FEC.EMISION");
						$xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "FEC.VENCI.");
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "TRANSC");
						$xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, "MO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, "IMPORTE");
						$xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "SALDO");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "VEN");
						$xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, "ESTADO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, "BANCO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, "NUM.COBRA.");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, "REFERENCIA");
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($fondo_morado_claro);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($font_header);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($border_thin);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$fil=$fil+0;
					}else if($cod=='0004'){
						$fil=$fil+1;
						$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
						$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
						$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $semillasdolares);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$fil=$fil+1;
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $semillassoles);
						$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						/*NOMBRE DE SEMILLAS*/
						$fil=$fil+2;
						$emp=$datos_muestra['CODIGO_EMPRESA'];
						$sqlempresa="SELECT nombre FROM ca_empresa WHERE codigo='$emp'";
						$prempresa = $connection->prepare($sqlempresa);
						$prempresa->execute();					
						$empresa=$prempresa->fetchAll(PDO::FETCH_ASSOC);
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].($fil), $empresa[0]['nombre']);	
						$xls->getActiveSheet()->getStyle($columna[$col+0].($fil))->applyFromArray($font_empresa_blue);

						$fil=$fil+1;
						//$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "CIA.");    
						$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, "TD");
						$xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "DOCUMENTO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "FEC.EMISION");
						$xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "FEC.VENCI.");
						$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "TRANSC");
						$xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, "MO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, "IMPORTE");
						$xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "SALDO");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "VEN");
						$xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, "ESTADO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, "BANCO");
						$xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, "NUM.COBRA.");
						// $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, "REFERENCIA");
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($fondo_morado_claro);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($font_header);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($border_thin);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$fil=$fil+0;
					}				
					$fil=$fil+1;
				}
			}

			// $xls->getActiveSheet()->setCellValueExplicit($columna[$col+0].$fil, $datos_muestra['CODIGO_EMPRESA'],PHPExcel_Cell_DataType::TYPE_STRING);
			$xls->getActiveSheet()->SetCellValue($columna[$col+0].$fil, $datos_muestra["TD"]);
			$xls->getActiveSheet()->setCellValueExplicit($columna[$col+1].$fil, $datos_muestra["SERIE_DOCUMENTO"],PHPExcel_Cell_DataType::TYPE_STRING);
			$xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, $datos_muestra["FECHA_EMISION"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, $datos_muestra["FECHA_VENCIMIENTO"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, $datos_muestra["TRANSC"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, $datos_muestra["MONEDA"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, $datos_muestra["IMPORTE"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, $datos_muestra["SALDO"]);
			// $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, $datos_muestra["DIAS_TRANS"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, $datos_muestra["EST_LETR"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil,$datos_muestra["BANCO"]);
			$xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil,$datos_muestra["NUM_COBRANZA"]);
			// $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil,$datos_muestra["REFERENCIA"]);

			$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($fondo_claro);
			$xls->getActiveSheet()->getStyle('A'.$fil.':K'.$fil)->applyFromArray($border_thin);

			$xls->getActiveSheet()->getStyle($columna[$col+2].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$xls->getActiveSheet()->getStyle($columna[$col+3].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$xls->getActiveSheet()->getStyle($columna[$col+5].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$xls->getActiveSheet()->getStyle($columna[$col+6].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

			$cod=$datos_muestra['CODIGO_EMPRESA'];

			if($nroreg==$cantreg){
				
				if($cod=='0002'){
					$fil=$fil+2;
					$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
					$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
					$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $caisacdolares);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$fil=$fil+1;
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $caisacsoles);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}

				if($cod=='0003'){
					$fil=$fil+1;
					$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
					$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
					$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $andexdolares);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$fil=$fil+1;
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $andexsoles);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}

				if($cod=='0004'){
					$fil=$fil+2;
					$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
					$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
					$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

					$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $semillasdolares);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$fil=$fil+1;
					$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $semillassoles);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}

				if($cod=='0016'){
					$fil=$fil+2;
					$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
					$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL POR EMPRESA");
					$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);

					$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $sunnydolares);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$fil=$fil+1;
					$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
					$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
					$xls->getActiveSheet()->getStyle($columna[$col+9])->applyFromArray($font_subtotal);
					$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $sunnysoles);
					$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}

				$fil=$fil+3;
				$xls->getActiveSheet()->mergeCells("A".$fil.":C".$fil);
				$xls->getActiveSheet()->getStyle('A'.$fil)->applyFromArray($font_subtotal);
				$xls->getActiveSheet()->SetCellValue('A'.$fil,"TOTAL GENERAL");
				$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);				
				$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'US');
				$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($border_mefium);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);	
				$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $totaldolares);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$fil=$fil+1;
				$xls->getActiveSheet()->getStyle($columna[$col+4])->applyFromArray($font_subtotal);
				$xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil,'MN');
				$xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($border_mefium);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($font_subtotal);
				$xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, $totalsoles);
				$xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			}
		}

		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename="Estado_Cuenta.xls"');
		// header('Cache-Control: max-age=0');
		// $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
		// $objWriter->save('php://output');
		// exit();

		# DEBE HABER UNA COLUMNA DEUDA AL EN LA DEUDA PARA EXTRAERLO

		$sqlsum="   SELECT
                    ROUND(SUM(IF(detcu.dato2='CAISAC'  AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDINA_SOLES',
					ROUND(SUM(IF(detcu.dato2='ANDEX' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDEX_SOLES',
					ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SEMILLA_SOLES',
					ROUND(SUM(IF(detcu.dato2='SUNNY' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SUNNY_SOLES',
					
					ROUND(SUM(IF(detcu.dato2='CAISAC' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDINA_DOLARES',
					ROUND(SUM(IF(detcu.dato2='ANDEX' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDEX_DOLARES',
					ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SEMILLA_DOLARES',
					ROUND(SUM(IF(detcu.dato2='SUNNY' AND	(dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SUNNY_DOLARES',
                    
					ROUND(SUM(IF(detcu.dato2='CAISAC' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDINA_SOLES',
					ROUND(SUM(IF(detcu.dato2='ANDEX' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDEX_SOLES',
					ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SEMILLAS_SOLES',
					ROUND(SUM(IF(detcu.dato2='SUNNY' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SUNNY_SOLES',

					ROUND(SUM(IF(detcu.dato2='CAISAC' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDINA_DOLARES',
					ROUND(SUM(IF(detcu.dato2='ANDEX' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDEX_DOLARES',
					ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SEMILLAS_DOLARES',
					ROUND(SUM(IF(detcu.dato2='SUNNY' AND	(dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SUNNY_DOLARES'
                    FROM
                    ca_detalle_cuenta detcu
                    WHERE
                    detcu.codigo_cliente='$codigo_cliente' AND
                    detcu.idcartera=$idcartera AND
					detcu.estado=1 AND
					-- DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1 -- NO CONSIDERA LOS CONTADOS
					IF(detcu.dato8='FT',DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1,IF(detcu.dato8='BV',DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1,1=1)) -- NO CONSIDERA LOS CONTADOS PARA FT y BV
                    GROUP BY detcu.codigo_cliente";

        $prsum = $connection->prepare($sqlsum);
		$prsum->execute();
		$datasum=$prsum->fetchAll(PDO::FETCH_ASSOC);

		$xls->setActiveSheetIndex(0);
        $objWriter = new PHPExcel_Writer_Excel2007($xls);
        $namefile='Estado-Cuenta-'.$codigo_cliente;
        $objWriter->save('C:/xampp/htdocs/COB_ANDINA/documents/correo_masivo/'.$namefile.'.xlsx');

        $sqlcor="	SELECT
					cli.numero_documento,
					cli.razon_social,
					cor.idcliente,
					cor.idcorreo,
					cor.correo,
					(SELECT dato1 FROM ca_detalle_cuenta WHERE codigo_cliente='$codigo_cliente' AND estado=1 AND idcartera=$idcartera LIMIT 1) AS 'cod_zona'
					FROM
					ca_correo cor 
					INNER JOIN ca_cliente cli ON cor.idcliente=cli.idcliente
					WHERE
					cli.codigo='$codigo_cliente' AND
					cor.estado=1;";
		$prcor = $connection->prepare($sqlcor);
		$prcor->execute();

		while($datos_cor=$prcor->fetch(PDO::FETCH_ASSOC)) {

			$numero_documento=$datos_cor['numero_documento'];
			$razon_social=$datos_cor['razon_social'];
			$xidcliente=$datos_cor['idcliente'];
			$xidcorreo=$datos_cor['idcorreo'];
			$correo=$datos_cor['correo'];
			$cod_zona=$datos_cor['cod_zona'];


			$vendor="	SELECT
						u.idusuario,
						userv.idusuario_servicio,
						CONCAT(u.nombre,' ',u.paterno,' ',u.materno) AS 'personal',
						CONCAT(u.nombre,' ',u.paterno,' ',u.materno,IF(u.telefono IS NULL,'',CONCAT(', al Teléfono: ',u.telefono)),IF(u.celular IS NULL,'',CONCAT(', al movil(es): ',u.celular)),IF(u.celular2 IS NULL,'',CONCAT(' - ',u.celular2)),', al correo: ',u.email) AS 'dato',
						u.email AS 'correo',
						(SELECT tu.idtipo_usuario FROM ca_tipo_usuario tu WHERE tu.idtipo_usuario=userv.idtipo_usuario)AS 'idtipo_usuario',
						(SELECT tu.nombre FROM ca_tipo_usuario tu WHERE tu.idtipo_usuario=userv.idtipo_usuario)AS 'tipo_usuario',
						zo.codzona
						FROM 
						ca_usuario u 
						INNER JOIN ca_usuario_servicio userv ON u.idusuario=userv.idusuario
						INNER JOIN ca_zona_usuario zu ON zu.idusuario_servicio=userv.idusuario_servicio AND zu.estado=1
						INNER JOIN ca_zona zo ON zo.idzona=zu.idzona
						WHERE
						u.estado=1 AND
						userv.estado=1 AND
						zo.codzona='$cod_zona' AND
						userv.idtipo_usuario=8
						ORDER BY 5";

			$prvendor = $connection->prepare($vendor);
			$prvendor->execute();
			$datos_vendor=$prvendor->fetchAll(PDO::FETCH_ASSOC);

			$datogestor="";
			$encopia= array();

			$datogestor=$datos_vendor[0]['dato'];
			array_push($encopia, $datos_vendor[0]['correo']);

			//echo $datos_vendor[0]['correo'];

			// for($r=0;$r<=count($datos_vendor)-1;$r++){
			// 	if($datos_vendor[$r]['idtipo_usuario']==7){
			// 		$datogestor=$datos_vendor[$r]['dato'];
			// 	}else{
			// 		// array_push($encopia, $datos_vendor[$r]['dato']);
			// 	}
			// }

			$TOTAL_ANDINA_SOLES=$datasum[0]['ANDINA_SOLES'];
			$TOTAL_ANDEX_SOLES=$datasum[0]['ANDEX_SOLES'];
			$TOTAL_SEMILLAS_SOLES=$datasum[0]['SEMILLA_SOLES'];
			$TOTAL_SUNNY_SOLES=$datasum[0]['SUNNY_SOLES'];
			$TOTAL_GENERAL_SOLES=$TOTAL_ANDINA_SOLES+$TOTAL_ANDEX_SOLES+$TOTAL_SEMILLAS_SOLES+$TOTAL_SUNNY_SOLES;

			$TOTAL_ANDINA_SOLES=number_format($TOTAL_ANDINA_SOLES,2,'.',',');
			$TOTAL_ANDEX_SOLES=number_format($TOTAL_ANDEX_SOLES,2,'.',',');
			$TOTAL_SEMILLAS_SOLES=number_format($TOTAL_SEMILLAS_SOLES,2,'.',',');
			$TOTAL_SUNNY_SOLES=number_format($TOTAL_SUNNY_SOLES,2,'.',',');
			$TOTAL_GENERAL_SOLES=number_format($TOTAL_GENERAL_SOLES,2,'.',',');

			$TOTAL_ANDINA_DOLARES=$datasum[0]['ANDINA_DOLARES'];
			$TOTAL_ANDEX_DOLARES=$datasum[0]['ANDEX_DOLARES'];
			$TOTAL_SEMILLAS_DOLARES=$datasum[0]['SEMILLA_DOLARES'];
			$TOTAL_SUNNY_DOLARES=$datasum[0]['SUNNY_DOLARES'];
			$TOTAL_GENERAL_DOLARES=$TOTAL_ANDINA_DOLARES+$TOTAL_ANDEX_DOLARES+$TOTAL_SEMILLAS_DOLARES+$TOTAL_SUNNY_DOLARES;	

			$TOTAL_ANDINA_DOLARES=number_format($TOTAL_ANDINA_DOLARES,2,'.',',');
			$TOTAL_ANDEX_DOLARES=number_format($TOTAL_ANDEX_DOLARES,2,'.',',');
			$TOTAL_SEMILLAS_DOLARES=number_format($TOTAL_SEMILLAS_DOLARES,2,'.',',');
			$TOTAL_SUNNY_DOLARES=number_format($TOTAL_SUNNY_DOLARES,2,'.',',');
			$TOTAL_GENERAL_DOLARES=number_format($TOTAL_GENERAL_DOLARES,2,'.',',');

			$TOTAL_SA_ANDINA_SOLES=$datasum[0]['SA_ANDINA_SOLES'];
			$TOTAL_SA_ANDEX_SOLES=$datasum[0]['SA_ANDEX_SOLES'];
			$TOTAL_SA_SEMILLAS_SOLES=$datasum[0]['SA_SEMILLAS_SOLES'];
			$TOTAL_SA_SUNNY_SOLES=$datasum[0]['SA_SUNNY_SOLES'];
			$TOTAL_SA_GENERAL_SOLES=$TOTAL_SA_ANDINA_SOLES+$TOTAL_SA_ANDEX_SOLES+$TOTAL_SA_SEMILLAS_SOLES+$TOTAL_SA_SUNNY_SOLES;	

			$TOTAL_SA_ANDINA_SOLES=number_format($TOTAL_SA_ANDINA_SOLES,2,'.',',');
			$TOTAL_SA_ANDEX_SOLES=number_format($TOTAL_SA_ANDEX_SOLES,2,'.',',');
			$TOTAL_SA_SEMILLAS_SOLES=number_format($TOTAL_SA_SEMILLAS_SOLES,2,'.',',');
			$TOTAL_SA_SUNNY_SOLES=number_format($TOTAL_SA_SUNNY_SOLES,2,'.',',');
			$TOTAL_SA_GENERAL_SOLES=number_format($TOTAL_SA_GENERAL_SOLES,2,'.',',');

			$TOTAL_SA_ANDINA_DOLARES=$datasum[0]['SA_ANDINA_DOLARES'];
			$TOTAL_SA_ANDEX_DOLARES=$datasum[0]['SA_ANDEX_DOLARES'];
			$TOTAL_SA_SEMILLAS_DOLARES=$datasum[0]['SA_SEMILLAS_DOLARES'];
			$TOTAL_SA_SUNNY_DOLARES=$datasum[0]['SA_SUNNY_DOLARES'];
			$TOTAL_SA_GENERAL_DOLARES=$TOTAL_SA_ANDINA_DOLARES+$TOTAL_SA_ANDEX_DOLARES+$TOTAL_SA_SEMILLAS_DOLARES+$TOTAL_SA_SUNNY_DOLARES;	

			$TOTAL_SA_ANDINA_DOLARES=number_format($TOTAL_SA_ANDINA_DOLARES,2,'.',',');
			$TOTAL_SA_ANDEX_DOLARES=number_format($TOTAL_SA_ANDEX_DOLARES,2,'.',',');
			$TOTAL_SA_SEMILLAS_DOLARES=number_format($TOTAL_SA_SEMILLAS_DOLARES,2,'.',',');
			$TOTAL_SA_SUNNY_DOLARES=number_format($TOTAL_SA_SUNNY_DOLARES,2,'.',',');
			$TOTAL_SA_GENERAL_DOLARES=number_format($TOTAL_SA_GENERAL_DOLARES,2,'.',',');






			$resumensaldo='	<!-- [if gte mso 9]><xml>
                                <o:shapedefaults v:ext="edit" spidmax="1026" />
                                </xml><![endif]--><!-- [if gte mso 9]><xml>
                                <o:shapelayout v:ext="edit">
                                <o:idmap v:ext="edit" data="1" />
                                </o:shapelayout></xml><![endif]-->
							<div class="WordSection1">
							<table class="MsoNormalTable" align="center" style="margin: 0 auto;width: 450.0pt; border-collapse: collapse;" border="0" width="429" cellspacing="0" cellpadding="0">
							<tbody>

								<tr style="height: 16.5pt;">
									<td style="width: 60.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 16.5pt;" valign="bottom" nowrap="nowrap" width="80">&nbsp;</td>
									<td style="width: 262.0pt; border: solid #9BC2E6 1.0pt; background: #5B9BD5; padding: 0cm 3.5pt 0cm 3.5pt; height: 16.5pt;" colspan="5" width="349">
										<p class="MsoNormal" style="text-align: center;margin:3px;" align="center"><strong><span style="font-size: 12.0pt; font-family: Arial,sans-serif; color: white; mso-fareast-language: ES-PE;">RESUMEN DEUDA</span></strong></p>
									</td>
								</tr>

								<tr style="height: 15.75pt;">
								<td style="width: 60.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" valign="bottom" nowrap="nowrap" width="80">&nbsp;</td>
								<td style="width: 88.0pt; border-top: none; border-left: solid #9BC2E6 1.0pt; border-bottom: solid #9BC2E6 1.0pt; border-right: none; background: #9BC4EA; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;width: 86px;margin:0;" align="center"><strong><span style="font-size: 8pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">COMERCIAL<br>ANDINA<br>SAC</span></strong></p>
								</td>
								<td style="width: 88.0pt; border-top: none; border-left: solid #9BC2E6 1.0pt; border-bottom: solid #9BC2E6 1.0pt; border-right: none; background: #9BC4EA; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;width: 86px;margin:0;" align="center"><strong><span style="font-size: 8pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">GRUPO<br>ANDEX<br>SAC</span></strong></p>
								</td>
								<td style="width: 88.0pt; border-top: none; border-left: solid #9BC2E6 1.0pt; border-bottom: solid #9BC2E6 1.0pt; border-right: none; background:#9BC4EA; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;width: 86px;margin:0;" align="center"><strong><span style="font-size: 8pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">FERTILIZANTES Y SEMILLAS ANDINAS SAC</span></strong></p>
								</td>
								<td style="width: 88.0pt; border-top: none; border-left: solid #9BC2E6 1.0pt; border-bottom: solid #9BC2E6 1.0pt; border-right: none; background:#9BC4EA; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;width: 86px;margin:0;" align="center"><strong><span style="font-size: 8pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">SUNNY<br>VALLEY<br>SAC</span></strong></p>
								</td>
								<td style="width: 88pt; border-top: none; border-left: solid #9BC2E6 1.0pt; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #9BC4EA; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="109">
								<p class="MsoNormal" style="text-align: center;width: 86px;margin:0;" align="center"><strong><span style="font-size: 8pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">TOTAL</span></strong></p>
								</td>
								</tr>

								<tr style="height: 15.75pt;">
								<td style="width: 130pt; border: solid #9BC2E6 1.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: left;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">DEUDA S/.</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_ANDINA_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_ANDEX_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SEMILLAS_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SUNNY_SOLES.'</span></strong></p>
								</td>
								<td style="width: 82.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="109">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_GENERAL_SOLES.'</span></strong></p>
								</td>
								</tr>
								
								<tr style="height: 15.75pt;">
								<td style="width: 170pt; border: solid #9BC2E6 1.0pt; border-top: none; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: left;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">DEUDA $</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_ANDINA_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_ANDEX_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SEMILLAS_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SUNNY_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 82.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="109">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_GENERAL_DOLARES.'</span></strong></p>
								</td>
								</tr>

								<tr style="height: 15.75pt;">
								<td style="width: 170pt; border: solid #9BC2E6 1.0pt; border-top: none; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: left;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">A FAVOR S/.</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_ANDINA_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_ANDEX_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_SEMILLAS_SOLES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_SUNNY_SOLES.'</span></strong></p>
								</td>
								<td style="width: 82.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="109">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_GENERAL_SOLES.'</span></strong></p>
								</td>
								</tr>

								<tr style="height: 15.75pt;">
								<td style="width: 170pt; border: solid #9BC2E6 1.0pt; border-top: none; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: left;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">A FAVOR $</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_ANDINA_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_ANDEX_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_SEMILLAS_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 60.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; background: #CDE7FF; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="80">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_SUNNY_DOLARES.'</span></strong></p>
								</td>
								<td style="width: 82.0pt; border-top: none; border-left: none; border-bottom: solid #9BC2E6 1.0pt; border-right: solid #9BC2E6 1.0pt; padding: 0cm 3.5pt 0cm 3.5pt; height: 15.75pt;" width="109">
								<p class="MsoNormal" style="text-align: center;margin:0;" align="center"><strong><span style="font-size: 9.0pt; font-family: Arial,sans-serif; color: black; mso-fareast-language: ES-PE;">'.$TOTAL_SA_GENERAL_DOLARES.'</span></strong></p>
								</td>
								</tr>

							</tbody>
							</table>
							</div>';
			$cuentas="";
			$cuentas.='<table cellspacing="0" cellpadding="0" style="width:100%;background-color:#FFFFFF;">';
				
				###CAISAC###

				$cuentas.='<tr>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">COMERCIAL ANDINA INDUSTRIAL S.A.C.</td>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">RUC: 20108772884</td>';
				$cuentas.='</tr>';

				#BCP

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;">&nbsp;BANCO DE CREDITO DEL PERU</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;194-0091402-0-51</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;194-0078937-1-51</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;002-194-000091402051-96</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;002-194-000078937151-95</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';

				#BBVA

				#*********309(PANDURO)*********#

				if($cod_zona==309){

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO CONTINENTAL</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;0011-0181-59-0100018085</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;011-181000100018085-59</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';

				}

				#BANCO DE LA NACION

				#*********318(AYACUCHO)*********#

				if($cod_zona==318){
					
				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO DE LA NACIÓN</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00-000-307343</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;018-000-000000307343-04</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';

				}

				###ANDEX###

				$cuentas.='<tr>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">GRUPO ANDEX S.A.C.</td>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">RUC: 20509808717</td>';
				$cuentas.='</tr>';

				#BCP

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;">&nbsp;BANCO DE CREDITO DEL PERU</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;193-1486853-0-15</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;193-1534935-1-02</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00219300148685301516</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00219300153493510211</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';

				#BBVA

				#*********309(PANDURO)*********#

				if($cod_zona==309){
					$cuentas.='<tr>';
					$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO CONTINENTAL</td>';
					$cuentas.='</tr>';

					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;0011-0181-0100020918-57</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
					$cuentas.='</tr>';
					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;0011-0181-0100020675-53</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
					$cuentas.='</tr>';
					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;011-181-000100020918-57</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
					$cuentas.='</tr>';
					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;011-181-000100020675-53</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
					$cuentas.='</tr>';
				}

				#BANCO DE LA NACION

				#*********318(AYACUCHO)*********#

				if($cod_zona==318){
					$cuentas.='<tr>';
					$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO DE LA NACIÓN</td>';
					$cuentas.='</tr>';

					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00-091-005239</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
					$cuentas.='</tr>';
					$cuentas.='<tr>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;018-091-000091005239-98</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
					$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
					$cuentas.='</tr>';
				}

				###SEMILLAS###

				$cuentas.='<tr>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">FERTILIZANTES Y SEMILLAS ANDINAS S.A.C.</td>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">RUC: 20512706577</td>';
				$cuentas.='</tr>';

				#BCP

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;">&nbsp;BANCO DE CREDITO DEL PERU</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;193-1709352-0-81</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;193-1711289-1-56</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00219300170935208119</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00219300171128915613</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';

				#BBVA

				#*********309(PANDURO)*********#

				if($cod_zona==309){
				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO CONTINENTAL</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;0011-0181-53-0100018069</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;0011-0181-0100023054-58</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;011-181000100018069-53</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;011-181-000100023054-58</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				}

				#BANCO DE LA NACION

				#*********318(AYACUCHO)*********#

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;border-top: 2px solid #FFFFFF;">&nbsp;BANCO DE LA NACIÓN</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE(DETRACIÓN) N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00-058-009008</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';

				###SUNNY###

				$cuentas.='<tr>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">SUNNY VALLEY S.A.C.</td>';
				$cuentas.='<td colspan=2 style="background-color:black;color:white;border-bottom: 2px solid white;font-family: Arial, Times, serif;text-align:center;font-size: 10px;font-weight: bold;">RUC: 20524869714</td>';
				$cuentas.='</tr>';

				#BCP

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;">&nbsp;BANCO DE CREDITO DEL PERU</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;194-2360303043</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;194-2344261113</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;002-194002360303043-90</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';
				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CÓDIGO DE CUENTA INTERBANCARIO</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;002-194002344261113-95</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#C4C4C4;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Dólares Americanos</td>';
				$cuentas.='</tr>';

				#BANCO DE LA NACION

				$cuentas.='<tr>';
				$cuentas.='<td colspan=4 style="background-color:#747474;color:white;font-family: Arial, Times, serif;text-align:left;font-size: 10px;font-weight: bold;border-bottom: 2px solid #FFFFFF;">&nbsp;BANCO DE LA NACIÓN</td>';
				$cuentas.='</tr>';

				$cuentas.='<tr>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;CTA. CORRIENTE N°</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;00-058-040703</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:black;font-weight: bold;font-size: 10px;text-align: left;font-family: Arial, Times, serif;height:13px;border-right:2px solid #FFFFFF;">&nbsp;MONEDA</td>';
				$cuentas.='<td style="background-color:#E1E1E1;color:white;color:black;font-weight: bold;font-size: 10px;text-align: center;font-family: Arial, Times, serif;height:13px;">Nuevos Soles</td>';
				$cuentas.='</tr>';

			$cuentas.='</table>';

			$buscarfe = array("January","February","March","April","May","June","July","August","September","October","November","December",",");
			$cambiafe = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","");

			$layout = file_get_contents('C:/xampp/htdocs/COB_ANDINA/tarea/layout/layout_correo.php');
			$buscar = array("cuerpomensaje");
			$cambiar = array($speech);
			$speechini = str_replace($buscar, $cambiar, $layout);

			$buscars = array("datofecha","datogestor","datocliente", "tablaresumen","xcuentas");

			//$var = $fecha_al;
			$date = str_replace('/', '-', $fecha_al);
			$fecha_hasta =date('Y/m/d', strtotime($date));

			$fecha_al_deu = date("d", strtotime($fecha_hasta))." de ".$cambiafe[intval(date("m", strtotime($fecha_hasta)))-1]." del ".date("Y", strtotime($fecha_hasta));
            $cambias = array($fecha_al_deu,$datogestor,$razon_social,$resumensaldo,$cuentas);
            $speechnuevo = str_replace($buscars, $cambias, $speechini);

			setlocale(LC_TIME,"es_ES");

			$asunto="Grupo Andina - Estado de Cuenta al ".$fecha_al_deu;

			$objMailer = new PHPMailer();
			$objMailer->SMTPAuth = true;
			$objMailer->WordWrap = 50;
			$objMailer->SMTPDebug  = 1;
			$objMailer->Mailer = 'smtp';                                                
			$objMailer->Host = 'smtp.gmail.com';
			$objMailer->Timeout = 120;
			$objMailer->SMTPSecure = "tls";
			$objMailer->IsHTML(true);
			$objMailer->IsSMTP();
			$objMailer->CharSet = "utf-8";
			$objMailer->Port = '587';
			$objMailer->Username = 'informes@grupoandina.com.pe';
			$objMailer->From = 'informes@grupoandina.com.pe';
			$objMailer->Password = 'grupoandina';
			$objMailer->FromName = 'Grupo Andina S.A.C.';
			$objMailer->Subject = $asunto;

			$ar_copia = array();
			$ar_copia=array_values(array_unique($encopia));
			for($h=0; $h <=count($ar_copia)-1 ; $h++) {

				// comentar para prueba ini
//				$objMailer->AddBCC($ar_copia[$h]);
//				if($ar_copia[$h]=='jsaldana@grupoandina.com.pe'){
//					$objMailer->AddBCC('cfeijoo@grupoandina.com.pe');
//				}
				//  comentar para prueba fin

				$objMailer->ClearAddresses();
			}

			$objMailer->AddAddress($correo);



			$objMailer->Body = $speechnuevo;
			$archivo = 'C:/xampp/htdocs/COB_ANDINA/documents/correo_masivo/'.$namefile.'.xlsx';
			$objMailer->AddAttachment($archivo,$namefile.'.xlsx');

			if(!$objMailer->send()){
				// error al enviar (estado=2)
				echo "<br>"."no enviado ".$correo."<br>"."\n";
				$insert_email_send="INSERT INTO `cob_andina`.`ca_envio_historico` (`idcliente`, `idcorreo`, `idcorreo_asunto`, `fecha_al`, `fecha_enviado`, `enviado`) VALUES ($xidcliente, $xidcorreo, $idcorreo_asunto, '$Date_al', NOW(), 2);";
                $pr_insert_email_send=$connection->prepare($insert_email_send);
                if($pr_insert_email_send->execute()){
                }
			}else{
				// enviado (estado=1)
				echo "<br>"."enviado ".$correo." ".$fecha_envio."\n";
				$insert_email_send="INSERT INTO `cob_andina`.`ca_envio_historico` (`idcliente`, `idcorreo`, `idcorreo_asunto`, `fecha_al`, `fecha_enviado`, `enviado`) VALUES ($xidcliente, $xidcorreo, $idcorreo_asunto, '$Date_al', NOW(), 1);";
                $pr_insert_email_send=$connection->prepare($insert_email_send);
                if($pr_insert_email_send->execute()){
                }
			}
		}

		$updatesend="UPDATE ca_envio SET fecha_envio=NOW() WHERE idcliente=$idclienteupdate";
		$pr_updatesend=$connection->prepare($updatesend);
        if($pr_updatesend->execute()){
        }

	}
}else{
	echo "NO HAY EVENTOS PROGRAMADOS PARA HOY";
}

?>