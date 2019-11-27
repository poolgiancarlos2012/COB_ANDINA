<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
	$fproceso=$_REQUEST['fproceso'];
	$fecha_unica=$_REQUEST['fecha_unica'];
	$territorio=$_REQUEST['territorio'];


	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';   

	 // Standard inclusions   
	 include("../../phpincludes/pChart.1.27d/pChart/pData.class");
	 include("../../phpincludes/pChart.1.27d/pChart/pChart.class");
	 include("../../phpincludes/pChart.1.27d/pChart/pDraw.class.php");     
	/*varigables*/


 $style_borde_celeste=                   array(
                            'borders' => array(
                                    'right'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FF009EE5')
                                    ),
                                    'top'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FF009EE5')                        
                                    ) ,
                                    'bottom'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                                            'color' => array('argb'=>'FF009EE5')                        
                                    ) ,
                                    'left'  =>array(
                                            'style'=>  PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FF009EE5')                        
                                    )
                                )
                    );
$style_fondo_celeste = array(
    'font' => array(
        'bold' => false,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FF009EE5',
        ),
    ),
);
 	/*HOJA1*/
	$objPHPExcel=new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('CALL_AGENCIA');

	$sql_clientes="SELECT count(DISTINCT codcent) AS 'COUNT_CLIENTE',count(DISTINCT contrato) AS 'COUNT_CONTRATO'
					FROM ca_historial his
					INNER JOIN ca_cliente_cartera clicar ON his.idcliente_cartera=clicar.idcliente_cartera
					WHERE clicar.idcartera=$cartera AND his.fproceso IN ($fproceso)";


	// add img grafico reporte
	$objDrawing=new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Diagrama');
	$objDrawing->setPath('bbva.png');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(3);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(27);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(27);			
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);								
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
	$objPHPExcel->getActiveSheet()->mergeCells('A4:E5');				

	$objPHPExcel->getActiveSheet()->getStyle('A4:E5')->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:E5')->getFill()->getStartColor()->setARGB('FF094FA4');
	$objPHPExcel->getActiveSheet()->getStyle('F4:F5')->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('F4:F5')->getFill()->getStartColor()->setARGB('FF006EC1');
	$objPHPExcel->getActiveSheet()->getStyle('G4:G5')->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('G4:G5')->getFill()->getStartColor()->setARGB('FF009FE5');	
	$objPHPExcel->getActiveSheet()->getStyle('H4:I5')->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('H4:I5')->getFill()->getStartColor()->setARGB('FF89D1F3');		
	$objPHPExcel->getActiveSheet()->setCellValue('I1','Cobranzas - RIESGO MINORISTA');
	$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKBLUE));	
	$objPHPExcel->getActiveSheet()->setCellValue('A4','REPORTE CALL Y AGENCIAS');
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);        		
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);		
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));		

	$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($style_borde_celeste);
	$objPHPExcel->getActiveSheet()->getStyle('C7:D7')->applyFromArray($style_borde_celeste);
	$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($style_borde_celeste);		
	$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($style_borde_celeste);
	$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($style_borde_celeste);
	$objPHPExcel->getActiveSheet()->getStyle('C10:E10')->applyFromArray($style_fondo_celeste);	

	$objPHPExcel->getActiveSheet()->setCellValue('B7','Agencia');	
    $objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true); 
	$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKBLUE));		

	$objPHPExcel->getActiveSheet()->setCellValue('E7','HDC BPO Services');		
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);		
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
    $objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);        	

	$objPHPExcel->getActiveSheet()->setCellValue('G7','Fecha');		
	$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);		
	$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
	$objPHPExcel->getActiveSheet()->getStyle('G7')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKBLUE));	
    $objPHPExcel->getActiveSheet()->getStyle('G7')->getFont()->setBold(true);        	

	$objPHPExcel->getActiveSheet()->setCellValue('H7',substr($fproceso, 1,6));		
	$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);		
	$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
	$objPHPExcel->getActiveSheet()->getStyle('H7')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
    $objPHPExcel->getActiveSheet()->getStyle('H7')->getFont()->setBold(true);        	    


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="REPORTE_AGENCIA.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 

?>