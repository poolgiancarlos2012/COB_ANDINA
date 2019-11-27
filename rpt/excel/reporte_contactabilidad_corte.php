<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$servicio=$_REQUEST['Servicio'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
	$fproceso=$_REQUEST['fproceso'];
	$fecha_unica=$_REQUEST['fecha_unica'];
	$territorio=$_REQUEST['territorio'];


	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';   
	/*varigables*/

$style_fondo_rojo = array(
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
            'argb' => 'FF963634',
        ),
    ),
);
$style_fondo_rojo_claro = array(
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
            'argb' => 'FFF2DCDB',
        ),
    ),
);

$style_alinear_centro = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

$style_borde_negro = array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'right'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ),
        'left'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ),   
        'bottom'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ),     
        'inside'=> array(
        	'style'=> PHPExcel_Style_Border::BORDER_THIN,
        ),                
    ),
);

 $style_solido_rojo=                   array(
                            'borders' => array(
                                    'right'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                                            'color' => array('argb'=>'FFA9403D')
                                    ),
                                    'top'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    ) ,
                                    'bottom'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    ) ,
                                    'left'  =>array(
                                            'style'=>  PHPExcel_Style_Border::BORDER_THICK,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    )
                                )
                    );

	

	/*HOJA3*/
	$objPHPExcel=new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('RESUMEN');

	$sql_clientes="SELECT count(DISTINCT codcent) AS 'COUNT_CLIENTE',count(DISTINCT contrato) AS 'COUNT_CONTRATO'
					FROM ca_historial his
					INNER JOIN ca_cliente_cartera clicar ON his.idcliente_cartera=clicar.idcliente_cartera
					WHERE clicar.idcartera=$cartera AND his.fproceso IN ($fproceso)";


	$objPHPExcel->getActiveSheet()->setCellValue('A2','REPORTE CORTE DE CONTACTABILIDAD DEL '.$fecha_unica);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_alinear_centro);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

	$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getFill()->getStartColor()->setARGB('FF963634');
	$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->setCellValue('A4','TOTAL BASE ENTREGADA');
	$objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
	$objPHPExcel->getActiveSheet()->setCellValue('A5','Total Clientes');
	$objPHPExcel->getActiveSheet()->setCellValue('A6','Total Contratos');

	$objPHPExcel->getActiveSheet()->getStyle('A4:B6')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A4:B6')->applyFromArray($style_borde_negro);	

	$pr_clientes=$connection->prepare($sql_clientes);

	if($pr_clientes->execute()){
		$data_clientes=$pr_clientes->fetch(PDO::FETCH_ASSOC);
		$objPHPExcel->getActiveSheet()->setCellValue('B5',$data_clientes['COUNT_CLIENTE']);
		$objPHPExcel->getActiveSheet()->setCellValue('B6',$data_clientes['COUNT_CONTRATO']);		
	}

	/*total cliente gestionados*/

	$objPHPExcel->getActiveSheet()->setCellValue('A9','Del total de clientes Gestionados');
	$objPHPExcel->getActiveSheet()->setCellValue('B9','Unicos');	
	$objPHPExcel->getActiveSheet()->setCellValue('C9','%');	
	$objPHPExcel->getActiveSheet()->setCellValue('E9','Total LLamadas');
	$objPHPExcel->getActiveSheet()->setCellValue('A10','Contactos');
	$objPHPExcel->getActiveSheet()->setCellValue('A11','No Contactos');	
	$objPHPExcel->getActiveSheet()->setCellValue('A12','Total Clientes Gestionados');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

	$objPHPExcel->getActiveSheet()->getStyle('A9:C9')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A9:C9')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('A9:C12')->applyFromArray($style_borde_negro);
	$objPHPExcel->getActiveSheet()->getStyle('B10:C11')->applyFromArray($style_alinear_centro);

	$objPHPExcel->getActiveSheet()->getStyle('E9')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E9')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));	
	$objPHPExcel->getActiveSheet()->getStyle('E9:E12')->applyFromArray($style_borde_negro);

	$objPHPExcel->getActiveSheet()->getStyle('A12:C12')->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A12:E12')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
	$objPHPExcel->getActiveSheet()->getStyle('E12')->applyFromArray($style_fondo_rojo_claro);

	$objPHPExcel->getActiveSheet()->getStyle('E10:E11')->applyFromArray($style_alinear_centro);

	/*total de contacto*/
	$objPHPExcel->getActiveSheet()->setCellValue('A14','Del total de Contactos');
	$objPHPExcel->getActiveSheet()->setCellValue('B14','Unicos');	
	$objPHPExcel->getActiveSheet()->setCellValue('C14','%');	
	$objPHPExcel->getActiveSheet()->setCellValue('E14','Total LLamadas');
	$objPHPExcel->getActiveSheet()->setCellValue('A15','Contacto con titular');
	$objPHPExcel->getActiveSheet()->setCellValue('A16','Contacto con tercero');	
	$objPHPExcel->getActiveSheet()->setCellValue('A17','Total Clientes Contactados');

	$objPHPExcel->getActiveSheet()->getStyle('A14:C14')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A14:C14')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('A14:C17')->applyFromArray($style_borde_negro);
	$objPHPExcel->getActiveSheet()->getStyle('B15:C16')->applyFromArray($style_alinear_centro);

	$objPHPExcel->getActiveSheet()->getStyle('E14')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E14')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));	
	$objPHPExcel->getActiveSheet()->getStyle('E14:E17')->applyFromArray($style_borde_negro);


	$objPHPExcel->getActiveSheet()->getStyle('A17:C17')->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A17:E17')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
	$objPHPExcel->getActiveSheet()->getStyle('E17')->applyFromArray($style_fondo_rojo_claro);	

	$objPHPExcel->getActiveSheet()->getStyle('E14:E17')->applyFromArray($style_alinear_centro);		


	$sql_total_mejor_llamada="SELECT IFNULL(SUM(IF(nombre='CEF' or nombre='CNE',1,0)),0) as 'CONTACTO',IFNULL(SUM(IF(nombre='NOC',1,0)),0) as 'NO_CONTACTO',COUNT(*) AS 'TOTAL',
							ROUND(IFNULL(SUM(IF(nombre='CEF' or nombre='CNE',1,0)),0)*100/COUNT(*),2) AS 'POR_CONTACTO',
							ROUND(IFNULL(SUM(IF(nombre='NOC',1,0)),0)*100/COUNT(*),2) AS 'POR_NOCONTACTO','100%' AS 'POR_TOTAL', 
							IFNULL(SUM(IF(nombre='CEF',1,0)),0) AS 'CONTACTO_TITULAR',IFNULL(SUM(IF(nombre='CNE',1,0)),0) as 'CONTACTO_TERCERO' FROM (
							select * from
							(
							select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
							from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu
							on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
							where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='".$fecha_unica."' and cu.dato9 in ($territorio)
							order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
							) t1 group by t1.idcliente_cartera 
							)A";

	$pr_total_mejor_llamada=$connection->prepare($sql_total_mejor_llamada);

	if($pr_total_mejor_llamada->execute()){
		$data_total_mejor_llamada=$pr_total_mejor_llamada->fetch(PDO::FETCH_ASSOC);
		$objPHPExcel->getActiveSheet()->setCellValue('B10',$data_total_mejor_llamada['CONTACTO']);
		$objPHPExcel->getActiveSheet()->setCellValue('B11',$data_total_mejor_llamada['NO_CONTACTO']);		
		$objPHPExcel->getActiveSheet()->setCellValue('B12',$data_total_mejor_llamada['TOTAL']);		
		$objPHPExcel->getActiveSheet()->setCellValue('C10',$data_total_mejor_llamada['POR_CONTACTO'].'%');		
		$objPHPExcel->getActiveSheet()->setCellValue('C11',$data_total_mejor_llamada['POR_NOCONTACTO'].'%');				
		$objPHPExcel->getActiveSheet()->setCellValue('C12',$data_total_mejor_llamada['POR_TOTAL']);						
		$objPHPExcel->getActiveSheet()->setCellValue('B15',$data_total_mejor_llamada['CONTACTO_TITULAR']);								
		$objPHPExcel->getActiveSheet()->setCellValue('B16',$data_total_mejor_llamada['CONTACTO_TERCERO']);										
		$objPHPExcel->getActiveSheet()->setCellValue('B17',$data_total_mejor_llamada['CONTACTO']);	
		$objPHPExcel->getActiveSheet()->setCellValue('C15',($data_total_mejor_llamada['CONTACTO']>0 ? number_format(($data_total_mejor_llamada['CONTACTO_TITULAR']*100/$data_total_mejor_llamada['CONTACTO']),2) : 0));														
		$objPHPExcel->getActiveSheet()->setCellValue('C16',($data_total_mejor_llamada['CONTACTO']>0 ? number_format(($data_total_mejor_llamada['CONTACTO_TERCERO']*100/$data_total_mejor_llamada['CONTACTO']),2) : 0));														
		$objPHPExcel->getActiveSheet()->setCellValue('C17','100%');																	
	}

	$sql_total_llamada="SELECT IFNULL(SUM(IF(A.nombre='CEF' or A.nombre='CNE',1,0)),0) AS 'TOTAL_CONTACTO',IFNULL(SUM(IF(A.nombre='NOC',1,0)),0) AS 'TOTAL_NOCONTACTO',COUNT(*) AS 'TOTAL_TOTAL',
			IFNULL(SUM(IF(A.nombre='CEF',1,0)),0) AS 'TOTAL_CONTACTO_TITULAR',IFNULL(SUM(IF(A.nombre='CNE',1,0)),0) AS 'TOTAL_CONTACTO_TERCERO'
			FROM (select lla.idcliente_cartera,lla.fecha,carfin.nombre 
			from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu
			on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
			where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='".$fecha_unica."' and cu.dato9 in ($territorio)
			group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion)A ";

	$pr_total_llamada=$connection->prepare($sql_total_llamada);

	if($pr_total_llamada->execute()){
		$data_total_llamada=$pr_total_llamada->fetch(PDO::FETCH_ASSOC);
		$objPHPExcel->getActiveSheet()->setCellValue('E10',$data_total_llamada['TOTAL_CONTACTO']);		
		$objPHPExcel->getActiveSheet()->setCellValue('E11',$data_total_llamada['TOTAL_NOCONTACTO']);				
		$objPHPExcel->getActiveSheet()->setCellValue('E12',$data_total_llamada['TOTAL_TOTAL']);				
		$objPHPExcel->getActiveSheet()->setCellValue('E15',$data_total_llamada['TOTAL_CONTACTO_TITULAR']);						
		$objPHPExcel->getActiveSheet()->setCellValue('E16',$data_total_llamada['TOTAL_CONTACTO_TERCERO']);								
		$objPHPExcel->getActiveSheet()->setCellValue('E17',$data_total_llamada['TOTAL_CONTACTO']);								
	}



/*cntacto titular*/

$sql_contacto_titular="SELECT TOTAL_LLAMADA.estado,IFNULL(MEJOR_LLAMADA.cantidad,0) as cantidad1,TOTAL_LLAMADA.cantidad as cantidad2 FROM (
				select A.idfinal,A.estado,count(*) as cantidad from (
				select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
				from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu
				on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
				where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and carfin.nombre in ('CEF') and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion
				)A GROUP BY A.idfinal,A.estado order by cantidad DESC
)TOTAL_LLAMADA 
LEFT JOIN(
				SELECT A.idfinal,A.estado,count(*) as cantidad FROM (
							select * from
							(
							select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
							from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu 
							on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
							where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and cu.dato9 in ($territorio)
							order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
							) t1 group by t1.idcliente_cartera 
							)A where A.nombre='CEF' GROUP BY A.estado order by cantidad DESC
)MEJOR_LLAMADA 
ON MEJOR_LLAMADA.idfinal=TOTAL_LLAMADA.idfinal
ORDER BY MEJOR_LLAMADA.cantidad DESC";

	$pr_contacto_titular=$connection->prepare($sql_contacto_titular);

	$filainicio=19;
	$filacabecera=$filainicio;
	$filainicio++;
	if($pr_contacto_titular->execute()){
		while($data_contacto_titular=$pr_contacto_titular->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data_contacto_titular['estado']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_contacto_titular['cantidad1']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,($data_total_mejor_llamada['CONTACTO_TITULAR']>0 ? number_format(($data_contacto_titular['cantidad1']*100/$data_total_mejor_llamada['CONTACTO_TITULAR']),2) : 0));			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_contacto_titular['cantidad2']);		
			$filainicio++;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_total_mejor_llamada['CONTACTO_TITULAR']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'100%');			
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_total_llamada['TOTAL_CONTACTO_TITULAR']);

	/*total contacto titular*/

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filacabecera,'Del total de Contactos con Titular');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filacabecera,'Unicos');	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filacabecera,'%');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filacabecera,'Total LLamadas');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total Contactos con Titular');

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filainicio)->applyFromArray($style_borde_negro);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$filacabecera.':C'.$filainicio)->applyFromArray($style_alinear_centro);

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_borde_negro);


	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio)->applyFromArray($style_fondo_rojo_claro);	

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_alinear_centro);			

/*contacto con tercero*/	

$sql_contacto_tercero="SELECT TOTAL_LLAMADA.estado,IFNULL(MEJOR_LLAMADA.cantidad,0) as cantidad1,TOTAL_LLAMADA.cantidad as cantidad2 FROM (
				select A.idfinal,A.estado,count(*) as cantidad from (
				select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
				from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu 
				on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta = lla.idcuenta
				where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and carfin.nombre in ('CNE') and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion
				)A GROUP BY A.idfinal,A.estado order by cantidad DESC
)TOTAL_LLAMADA 
LEFT JOIN(
				SELECT A.idfinal,A.estado,count(*) as cantidad FROM (
							select * from
							(
							select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
							from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu
							on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
							where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and cu.dato9 in ($territorio)
							order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
							) t1 group by t1.idcliente_cartera 
							)A where A.nombre='CNE' GROUP BY A.estado order by cantidad DESC
)MEJOR_LLAMADA 
ON MEJOR_LLAMADA.idfinal=TOTAL_LLAMADA.idfinal
ORDER BY MEJOR_LLAMADA.cantidad DESC";

	$pr_contacto_tercero=$connection->prepare($sql_contacto_tercero);

	$filainicio=$filainicio+2;
	$filacabecera=$filainicio;
	$filainicio++;
	if($pr_contacto_tercero	->execute()){
		while($data_contacto_tercero=$pr_contacto_tercero->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data_contacto_tercero['estado']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_contacto_tercero['cantidad1']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,($data_total_mejor_llamada['CONTACTO_TERCERO']>0 ? number_format(($data_contacto_tercero['cantidad1']*100/$data_total_mejor_llamada['CONTACTO_TERCERO']),2) : 0));			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_contacto_tercero['cantidad2']);		
			$filainicio++;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_total_mejor_llamada['CONTACTO_TERCERO']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'100%');			
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_total_llamada['TOTAL_CONTACTO_TERCERO']);

	/*total contacto tercero*/

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filacabecera,'Del total de Contactos con Terceros');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filacabecera,'Unicos');	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filacabecera,'%');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filacabecera,'Total LLamadas');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total Contactos con Terceros');

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filainicio)->applyFromArray($style_borde_negro);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$filacabecera.':C'.$filainicio)->applyFromArray($style_alinear_centro);

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_borde_negro);


	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio)->applyFromArray($style_fondo_rojo_claro);	

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_alinear_centro);		

/*NO COTNACTO*/		

$sql_no_contacto="SELECT TOTAL_LLAMADA.estado,IFNULL(MEJOR_LLAMADA.cantidad,0) as cantidad1,TOTAL_LLAMADA.cantidad as cantidad2 FROM (
				select A.idfinal,A.estado,count(*) as cantidad from (
				select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
				from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu
				on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
				where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and carfin.nombre in ('NOC') and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion
				)A GROUP BY A.idfinal,A.estado order by cantidad DESC
)TOTAL_LLAMADA 
LEFT JOIN(
				SELECT A.idfinal,A.estado,count(*) as cantidad FROM (
							select * from
							(
							select lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre as estado,fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
							from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin inner join ca_cuenta cu 
							on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final and cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta = lla.idcuenta
							where finser.idservicio=$servicio and clicar.idcartera = $cartera and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and cu.dato9 in ($territorio)
							order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
							) t1 group by t1.idcliente_cartera 
							)A where A.nombre='NOC' GROUP BY A.estado order by cantidad DESC
)MEJOR_LLAMADA 
ON MEJOR_LLAMADA.idfinal=TOTAL_LLAMADA.idfinal
ORDER BY MEJOR_LLAMADA.cantidad DESC";

	$pr_no_contacto=$connection->prepare($sql_no_contacto);

	$filainicio=$filainicio+2;
	$filacabecera=$filainicio;
	$filainicio++;
	if($pr_no_contacto->execute()){
		while($data_no_contacto=$pr_no_contacto->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data_no_contacto['estado']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_no_contacto['cantidad1']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,($data_total_mejor_llamada['NO_CONTACTO']>0 ? number_format(($data_no_contacto['cantidad1']*100/$data_total_mejor_llamada['NO_CONTACTO']),2) : 0));			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_no_contacto['cantidad2']);		
			$filainicio++;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data_total_mejor_llamada['NO_CONTACTO']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'100%');			
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data_total_llamada['TOTAL_NOCONTACTO']);

	/*total contacto tercero*/

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filacabecera,'Del total de No Contactos');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filacabecera,'Unicos');	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filacabecera,'%');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filacabecera,'Total LLamadas');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total No Contactos');

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':C'.$filainicio)->applyFromArray($style_borde_negro);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$filacabecera.':C'.$filainicio)->applyFromArray($style_alinear_centro);

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_borde_negro);


	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio)->applyFromArray($style_fondo_rojo_claro);	

	$objPHPExcel->getActiveSheet()->getStyle('E'.$filacabecera.':E'.$filainicio)->applyFromArray($style_alinear_centro);		

/*HOJA2*/

   	$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('FOTOCARTERA');

/*    $sql_fotocartera="select fproceso,agencia,territorio,producto,nom_subprod,CONCAT('=\"',contrato,'\"') as contrato,CONCAT('=\"',codcent,'\"') AS codcent,nombre,divisa,
				saldohoy,diavenc,ubigeo,dpto,dist_prov,tramo_dia,marca,
				IFNULL((select DATE(fecha) from ca_llamada where idcliente_cartera=clicar.idcliente_cartera and DATE(fecha)<='".$fecha_unica."' order by fecha DESC limit 1),'') as 'ultima llamada',
				IFNULL((select TIME(fecha) from ca_llamada where idcliente_cartera=clicar.idcliente_cartera and DATE(fecha)<='".$fecha_unica."' order by fecha DESC limit 1),'') as 'Hora llamada',
				IFNULL((select CONCAT('=\"',tel.numero,'\"') as numero from ca_llamada lla INNER JOIN ca_telefono tel on tel.idtelefono=lla.idtelefono where lla.idcliente_cartera=clicar.idcliente_cartera and DATE(lla.fecha)<='".$fecha_unica."' order by lla.fecha DESC limit 1),'') as 'TELEFONO',
				IFNULL((select carfin.nombre from ca_llamada lla inner join ca_final fin on fin.idfinal=lla.idfinal inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final where lla.idcliente_cartera=clicar.idcliente_cartera and DATE(lla.fecha)<='".$fecha_unica."' order by lla.fecha DESC limit 1),'') as 'Tipo conctacto',
				IFNULL((select fin.nombre from ca_llamada lla inner join ca_final fin on fin.idfinal=lla.idfinal where lla.idcliente_cartera=clicar.idcliente_cartera and DATE(lla.fecha)<='".$fecha_unica."' order by lla.fecha DESC limit 1),'') as 'Estado',
				IFNULL((select fecha_cp from ca_llamada where idcliente_cartera=clicar.idcliente_cartera and DATE(fecha)<='".$fecha_unica."' order by fecha DESC limit 1),'') as 'F.Cpg'
				from ca_historial his
				inner join ca_cliente_cartera clicar on his.idcliente_cartera=clicar.idcliente_cartera
				where clicar.idcartera=$cartera and his.fproceso in ($fproceso)
				group by his.idcliente_cartera,his.contrato";
*/

	$sql_fotocartera="select fproceso,agencia,territorio,producto,nom_subprod,CONCAT('=\"',contrato,'\"') as contrato,CONCAT('=\"',codcent,'\"') AS codcent,nombre,divisa,
				saldohoy,diavenc,ubigeo,dpto,dist_prov,tramo_dia,marca,
				 -- IFNULL((SELECT fecha FROM (select lla.idcliente_cartera,DATE(lla.fecha) AS 'fecha',finser.peso from ca_llamada lla inner join ca_final fin on fin.idfinal=lla.idfinal inner join ca_final_servicio finser on finser.idfinal=fin.idfinal where finser.idservicio=6 and finser.estado=1 and lla.idcliente_cartera=his.idcliente_cartera and lla.idcuenta=his.idcuenta and DATE(fecha)='2013-09-16' order by finser.peso DESC)A GROUP BY A.idcliente_cartera,A.peso ),'') as 'ultima llamada',
				IFNULL(DATE(A.fecha),'') AS 'ultima llamada',
				IFNULL(TIME(A.fecha),'') as 'Hora llamada',
				IFNULL((SELECT numero FROM ca_telefono where idtelefono=A.idtelefono),'') as 'TELEFONO',
				IFNULL((SELECT nombre FROM ca_carga_final WHERE idcarga_final=A.idcarga_final),'') as 'Tipo conctacto',
				IFNULL((SELECT nombre FROM ca_final where idfinal=A.idfinal),'') as 'Estado',
				IFNULL(A.fecha_cp,'') as 'F.Cpg',
				IFNULL(A.observacion,'') as 'Observacion',
				IFNULL((SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu inner join ca_usuario_servicio ususer on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=A.idusuario_servicio),'') as 'Operador'
				from ca_historial his
				inner join ca_cliente_cartera clicar on his.idcliente_cartera=clicar.idcliente_cartera
				LEFT OUTER JOIN (
									select * from
									(
										select lla.idcliente_cartera, lla.fecha, fin.idcarga_final, lla.idfinal, lla.fecha_cp, lla.observacion, lla.idusuario_servicio, lla.idtelefono , finser.peso 
										from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser 
										on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera
										where finser.idservicio=$servicio and clicar.idcartera = $cartera and finser.idservicio = $servicio and DATE(lla.fecha)<='$fecha_unica'
										order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
								) t1 group by t1.idcliente_cartera
				)A ON A.idcliente_cartera=his.idcliente_cartera
				where clicar.idcartera=$cartera and his.fproceso in ($fproceso)
				group by his.idcliente_cartera,his.contrato";

	$pr_fotocartera=$connection->prepare($sql_fotocartera);
     $abc= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
     			'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
            	'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
            	'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
            	'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
            	'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
            	'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
            	'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ',
            	'HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ',
            	'IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK','IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ',
            	'JA','JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ','JR','JS','JT','JU','JV','JW','JX','JY','JZ',
            	'KA','KB','KC','KD','KE','KF','KG','KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW','KX','KY','KZ');

     $filainicio=2;
     $cont=0;
     $i=0;


    $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
   	$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->getStartColor()->setARGB('FF963634');
	$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));

	if($pr_fotocartera->execute()){
		while($data_fotocartera=$pr_fotocartera->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_fotocartera as $key => $value) {
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
						$cont=$cont+1;
				}
			}		
			$i++;	
			$cont=0;
			$cant_contrato=array();
			$cant_detalle=array();
			foreach ($data_fotocartera as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);						
					$cont=$cont+1;
			}
				$filainicio=$filainicio+1;							
		}
	}
//	echo($sql_carta);
//	exit();

	/*HOJA 3*/
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(2);

	$objPHPExcel->getActiveSheet()->setTitle('DETALLE LLAMADAS');

	$sql_detalle="select cu.dato9 as 'Territorio',CONCAT('=\"',clicar.codigo_cliente,'\"') as 'CodCent',Date(fecha) as 'fecha',time(fecha) as 'hora',
					(select CONCAT('=\"',numero,'\"') from ca_telefono where idtelefono=lla.idtelefono) as Telefono,
					carfin.nombre as 'Tipo',fin.nombre as 'Estado',lla.fecha_cp as 'F.Cpg',lla.observacion as 'Obs',
					(select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) from ca_usuario usu inner join ca_usuario_servicio ususer on ususer.idusuario=usu.idusuario where ususer.idusuario_servicio=lla.idusuario_servicio) as Operador,
					(select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.idservicio=$servicio) as 'Prioridad',HOUR(lla.fecha) as 'Trama'
					from ca_llamada lla
					inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
					inner join ca_final fin on fin.idfinal=lla.idfinal
					inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
					inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
					where clicar.idcartera=$cartera and date(fecha)='".$fecha_unica."' and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion";

    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
   	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->getStartColor()->setARGB('FF963634');
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(55);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(55);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);		

	$pr_detalle=$connection->prepare($sql_detalle);
     $filainicio=2;
     $cont=0;
     $i=0;
	if($pr_detalle->execute()){
		while($data_detalle=$pr_detalle->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_detalle as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
					$cont++;
				}
			}
			$i++;	
			$cont=0;
			$cant_contrato=array();
			$cant_detalle=array();
			foreach ($data_detalle as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);						
					$cont=$cont+1;
			}
				$filainicio=$filainicio+1;				
		}
	}		

	/*HOJA4*/

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(3);
	$objPHPExcel->getActiveSheet()->setTitle('TRAMA');

	$sql_trama="select X.nombre as Estado,IFNULL(Z.h7,0) as 'h7',IFNULL(Z.h8,0) as 'h8',IFNULL(Z.h9,0) as 'h9',IFNULL(Z.h10,0) as 'h10',IFNULL(Z.h11,0) as 'h11',IFNULL(Z.h12,0) as 'h12',
				IFNULL(Z.h13,0) as 'h13',IFNULL(Z.h14,0) as 'h14',IFNULL(Z.h15,0) as 'h15',IFNULL(Z.h16,0) as 'h16',IFNULL(Z.h17,0) as 'h17',
				IFNULL(Z.h18,0) as 'h18',IFNULL(Z.h19,0)  as 'h19',Z.total as 'Total General' from ca_carga_final X 
				LEFT OUTER JOIN (
						select A.carga,SUM(IF(A.hora=7,1,0)) as 'h7',SUM(IF(A.hora=8 ,1,0)) as 'h8',SUM(IF(A.hora=9 ,1,0)) as 'h9',SUM(IF(A.hora=10 ,1,0)) as 'h10',
									 SUM(IF(A.hora=11 ,1,0)) as 'h11',SUM(IF(A.hora=12 ,1,0)) as 'h12',SUM(IF(A.hora=13 ,1,0)) as 'h13',SUM(IF(A.hora=14 ,1,0)) as 'h14',
									 SUM(IF(A.hora=15 ,1,0)) as 'h15',SUM(IF(A.hora=16 ,1,0)) as 'h16',SUM(IF(A.hora=17 ,1,0)) as 'h17',SUM(IF(A.hora=18 ,1,0)) as 'h18',SUM(IF(A.hora=19 ,1,0)) as 'h19',count(*) as total
						FROM (
						select HOUR(fecha) as hora,carfin.nombre as carga
											from ca_llamada lla
											inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
											inner join ca_final fin on fin.idfinal=lla.idfinal
											inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
											inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta = lla.idcuenta
											where lla.tipo<>'IVR' and clicar.idcartera=$cartera and date(fecha)='$fecha_unica' and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion
						)A GROUP BY carga
				)Z on Z.carga=X.nombre
				where X.idcarga_final in (1,2,3) order by nombre";	

	$pr_trama=$connection->prepare($sql_trama);			

	$objPHPExcel->getActiveSheet()->setCellValue('A2','CONTACTABILIDAD POR TRAMA HORARIA');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_alinear_centro);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);

	$i=0;
	$cont=0;
	$filainicio=5;
	$filainicio2=11;
	$filacabecera=$filainicio;
	$filacabecera2=$filainicio2;
	$filainicio++;
	$filainicio2++;

	if($pr_trama->execute()){
		while($data_trama=$pr_trama->fetch(PDO::FETCH_ASSOC)){

			if($i==0){
				foreach ($data_trama as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filacabecera,str_replace('h', '', $key));
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filacabecera2,str_replace('h', '', $key));					
					$cont++;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->applyFromArray($style_fondo_rojo);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getFont()->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));				

				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera2.':'.$abc[($cont - 1)].$filacabecera2)->applyFromArray($style_fondo_rojo);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera2.':'.$abc[($cont - 1)].$filacabecera2)->getFont()->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera2.':'.$abc[($cont - 1)].$filacabecera2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera2.':'.$abc[($cont - 1)].$filacabecera2)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));								


			}
			$i++;
			$cont=0;	

			foreach ($data_trama as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				if($key!='Total General'){
					if($key!='Estado'){
						$total_columna=$objPHPExcel->getActiveSheet()->getCell($abc[$cont].'9')->getValue();						
						//$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio2,number_format($value*100/$total_columna,2).'%');
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio2,'=ROUND(IF('.$abc[$cont].'9=0,"0.00%",'.$abc[$cont].''.$filainicio.'*100/'.$abc[$cont].'9),2) & "%"');
						$objPHPExcel->getActiveSheet()->getCell($abc[$cont].$filainicio2)->getCalculatedValue();							
					}else{
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio2,$value);				
					}
				}

				$cont++;
			}
			$filainicio++;
			$filainicio2++;

		}
	}

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filainicio)->applyFromArray($style_alinear_centro);				
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filainicio)->applyFromArray($style_borde_negro);			
	$objPHPExcel->getActiveSheet()->getStyle('A11:O14')->applyFromArray($style_borde_negro);			

	$objPHPExcel->getActiveSheet()->setCellValue('A9','Total General');


	$cabecera=9;
	$cont=1;
	/*calcula la suma total por columna*/
	for($i=1;$i<=14;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$cabecera,'=SUM('.$abc[$cont].'6:'.$abc[$cont].'8)');
		//$objPHPExcel->getActiveSheet()->getCell($abc[$cont].''.$cabecera)->getValue();
		$objPHPExcel->getActiveSheet()->getCell($abc[$cont].''.$cabecera)->getCalculatedValue();		
		$cont++;
	}


	$objPHPExcel->getActiveSheet()->setCellValue('A17','COMPROMISOS POR TRAMA HORARIA');
	$objPHPExcel->getActiveSheet()->getStyle('A17')->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->mergeCells('A17:O17');
	$objPHPExcel->getActiveSheet()->getStyle('A17')->applyFromArray($style_alinear_centro);
	$objPHPExcel->getActiveSheet()->getStyle('A17')->getFont()->setSize(14);

	$sql_trama_compromiso="select X.nombre as Estado,IFNULL(Z.h7,0) as 'h7',IFNULL(Z.h8,0) as 'h8',IFNULL(Z.h9,0) as 'h9',IFNULL(Z.h10,0) as 'h10',IFNULL(Z.h11,0) as 'h11',IFNULL(Z.h12,0) as 'h12',
				IFNULL(Z.h13,0) as 'h13',IFNULL(Z.h14,0) as 'h14',IFNULL(Z.h15,0) as 'h15',IFNULL(Z.h16,0) as 'h16',IFNULL(Z.h17,0) as 'h17',
				IFNULL(Z.h18,0) as 'h18',IFNULL(Z.h19,0)  as 'h19',IFNULL(Z.total,0) as 'Total General' from ca_carga_final X 
				LEFT OUTER JOIN (

						select A.carga,SUM(IF(A.hora=7,1,0)) as 'h7',SUM(IF(A.hora=8 ,1,0)) as 'h8',SUM(IF(A.hora=9 ,1,0)) as 'h9',SUM(IF(A.hora=10 ,1,0)) as 'h10',
									 SUM(IF(A.hora=11 ,1,0)) as 'h11',SUM(IF(A.hora=12 ,1,0)) as 'h12',SUM(IF(A.hora=13 ,1,0)) as 'h13',SUM(IF(A.hora=14 ,1,0)) as 'h14',
									 SUM(IF(A.hora=15 ,1,0)) as 'h15',SUM(IF(A.hora=16 ,1,0)) as 'h16',SUM(IF(A.hora=17 ,1,0)) as 'h17',SUM(IF(A.hora=18 ,1,0)) as 'h18',SUM(IF(A.hora=19 ,1,0)) as 'h19',count(*) as total
						FROM (
						select HOUR(fecha) as hora,carfin.nombre as carga
											from ca_llamada lla
											inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
											inner join ca_final fin on fin.idfinal=lla.idfinal
											inner join ca_final_servicio finser on finser.idfinal=fin.idfinal
											inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
											inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and lla.idcuenta=cu.idcuenta
											where lla.tipo<>'IVR' and clicar.idcartera=$cartera and date(fecha)='$fecha_unica' and finser.prioridad in (7,8,9,36,37,38) and cu.dato9 in ($territorio) group by lla.idcliente_cartera,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.idtelefono,lla.observacion
						)A GROUP BY carga
				)Z on Z.carga=X.nombre
				where X.idcarga_final in (2,3) order by nombre";	

	$pr_trama_compromiso=$connection->prepare($sql_trama_compromiso);


	$i=0;
	$cont=0;
	$filainicio=20;
	$filacabecera=$filainicio;
	$filainicio++;
	if($pr_trama_compromiso->execute()){
		while($data_trama_compromiso=$pr_trama_compromiso->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_trama_compromiso as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filacabecera,str_replace('h', '', $key));
					$cont++;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->applyFromArray($style_fondo_rojo);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getFont()->setSize(12);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filacabecera.':'.$abc[($cont - 1)].$filacabecera)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));				

			}
			$i++;
			$cont=0;
			foreach ($data_trama_compromiso as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$filainicio++;
		}
	}

	$objPHPExcel->getActiveSheet()->getStyle('A20:O22')->applyFromArray($style_borde_negro);			

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="REPORTE_CONTACTABILIDAD_CORTE.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 

?>