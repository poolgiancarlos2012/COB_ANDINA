<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
	$servicio=$_REQUEST['Servicio'];
	$fecha_inicio=$_REQUEST['FechaInicio'];
	$fecha_fin=$_REQUEST['FechaFin'];
	$tipo_cambio=$_REQUEST['tipocambio'];
	$tipo_vac=$_REQUEST['tipovac'];
	$fproceso=$_REQUEST['fproceso'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
        
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';        


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
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FFA9403D')
                                    ),
                                    'top'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    ) ,
                                    'bottom'     => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    ) ,
                                    'left'  =>array(
                                            'style'=>  PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FFA9403D')                        
                                    ),     
							        'inside'=> array(
							        		'style'=>  PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('argb'=>'FFA9403D')         
							        )
                                )
                    );    

 $style_borde_interno_punteado_rojo=                   array(
                            'borders' => array(
							        'inside'=> array(
							        	'style'=> PHPExcel_Style_Border::BORDER_DASHED,
							        	'color' => array('argb'=>'FFA9403D')
							        )
                                )
                    );    

 	/*armar temporal */
 	$fecha=date("Y_m_d_H_i_s");
 	$sqltemporal="CREATE TABLE tmp_contactabilidad_".$fecha."
 				(	idcliente_cartera int,
 					codigo_cliente varchar(45),
 					idusuario_servicio int,
 					operador varchar(150),
 					carga varchar(25),
 					idfinal int,
 					nombrefinal varchar(200),
 					INDEX(idcliente_cartera),
 					INDEX(idfinal),
 					INDEX(idusuario_servicio)
 				)";
	$prtemporal=$connection->prepare($sqltemporal);
	if($prtemporal->execute()){
		$sqlinsertadatos="insert tmp_contactabilidad_".$fecha."(idcliente_cartera,codigo_cliente,idusuario_servicio,operador)
						(select clicar.idcliente_cartera,clicar.codigo_cliente,clicar.idusuario_servicio,CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) AS operador
						from ca_cliente_cartera clicar
						inner join ca_usuario_servicio ususer on clicar.idusuario_servicio=ususer.idusuario_servicio
						inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
						inner join ca_usuario usu on usu.idusuario=ususer.idusuario
						where clicar.idcartera=($cartera) and cu.dato1 IN ($fproceso) and clicar.idusuario_servicio>0 GROUP BY clicar.idcliente_cartera)";
		$prinsertadatos=$connection->prepare($sqlinsertadatos);
		if($prinsertadatos->execute()){
			$sqlmejorllamada="UPDATE tmp_contactabilidad_".$fecha." tmp
								INNER JOIN (SELECT * FROM (
											select lla.idcliente_cartera,lla.fecha,carfin.nombre as carga,fin.nombre as final,fin.idfinal,finser.peso from ca_llamada lla
											inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
											inner join ca_final fin on fin.idfinal=lla.idfinal
											inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
											inner join ca_final_servicio finser on finser.idfinal=fin.idfinal
											where clicar.idcartera=$cartera and DATE(lla.fecha) BETWEEN '$fecha_inicio' and '$fecha_fin' and finser.idservicio=$servicio and finser.estado=1 AND lla.tipo IN ('LL','SA')
											ORDER BY lla.idcliente_cartera,finser.peso DESC,lla.fecha DESC)A GROUP BY A.idcliente_cartera)MEJOR_LLAMADA
								ON MEJOR_LLAMADA.idcliente_cartera=tmp.idcliente_cartera
								SET tmp.carga=MEJOR_LLAMADA.carga,tmp.idfinal=MEJOR_LLAMADA.idfinal,tmp.nombrefinal=MEJOR_LLAMADA.final";
			$prmejorllamada=$connection->prepare($sqlmejorllamada);
			if($prmejorllamada->execute()){

			}else{

			}
		}else{

		}
	}else{

	}


    /*creando objeto excel*/
    $objPHPExcel=new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('ANALISIS LLAMADA');
    $objPHPExcel->getActiveSheet()->setCellValue('A1','ANALISIS DE LLAMADAS');
    $objPHPExcel->getActiveSheet()->setCellValue('A2','Fecha : '.$fecha_inicio);
    $objPHPExcel->getActiveSheet()->setCellValue('A3','Desde :'.$fecha_fin);
    $objPHPExcel->getActiveSheet()->setCellValue('A4','CLIENTES GESTIONADOS');

    $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($style_alinear_centro);
	$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($style_solido_rojo);	


	$objPHPExcel->getActiveSheet()->setCellValue('A6','CODIGO');
	$objPHPExcel->getActiveSheet()->setCellValue('B6','OPERADOR');
	$objPHPExcel->getActiveSheet()->setCellValue('C6','TOTAL ABONADO');	
	$objPHPExcel->getActiveSheet()->setCellValue('D6','TOTAL GESTION');
	$objPHPExcel->getActiveSheet()->setCellValue('E6','SIN GESTION');
	$objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setSize(8);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);			

	$sql="SELECT idusuario_servicio,operador,count(*) as 'total_abonado',SUM(IF(isnull(carga),0,1)) AS 'total_gestion', SUM(IF(isnull(carga),1,0)) as 'sin_gestion'
		FROM tmp_contactabilidad_".$fecha."
		GROUP BY operador OrDER BY idusuario_servicio";	

	$filainicio=7;
	$puntoinicio=$filainicio;

	$pr=$connection->prepare($sql);
	if($pr->execute()){
		while($data=$pr->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data['idusuario_servicio']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data['operador']);			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,$data['total_abonado']);						
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,$data['total_gestion']);			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data['sin_gestion']);						
			$filainicio++;
		}
	}	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$puntoinicio.':E'.$filainicio)->applyFromArray($style_solido_rojo);	

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'TOTAL NETO');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'=SUM(C'.$puntoinicio.':C'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'=SUM(D'.$puntoinicio.':D'.($filainicio-1).')');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'=SUM(E'.$puntoinicio.':E'.($filainicio-1).')');
	$filainicio=$filainicio+2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'CONTACTO EFECTIVO (CEF), CONTACTO NO EFECTIVO (CNE) Y NO CONTACTO: POR CLIENTE');
	$filainicio=$filainicio+2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'CODIGO');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'OPERADOR');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'CEF');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'CNE');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'NOC');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,'TOTAL');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,'CEF');
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'CNE');
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'NOC');
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$filainicio,'TOTAL');

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':J'.$filainicio);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
	$objPHPExcel->getActiveSheet()->getStyle('H'.$filainicio.':K'.$filainicio)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$filainicio.':K'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));


	$filainicio++;

	$sqlcontacto="SELECT idusuario_servicio,operador,SUM(IF(carga='CEF',1,0)) AS 'CEF',
					SUM(IF(carga='CNE',1,0)) AS 'CNE',SUM(IF(carga='NOC',1,0)) AS 'NOC',SUM(IF(carga='CEF' OR carga='CNE' OR carga='NOC',1,0)) AS 'TOTAL'
				FROM tmp_contactabilidad_".$fecha."
				GROUP BY idusuario_servicio ORDER BY idusuario_servicio";
	$prcontacto=$connection->prepare($sqlcontacto);

	$puntoinicio=$filainicio;
	if($prcontacto->execute()){
		while($datacontacto=$prcontacto->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$datacontacto['idusuario_servicio']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$datacontacto['operador']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,$datacontacto['CEF']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,$datacontacto['CNE']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$datacontacto['NOC']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,$datacontacto['TOTAL']);

			$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,'=C'.$filainicio.'/F'.$filainicio);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'=D'.$filainicio.'/F'.$filainicio);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'=E'.$filainicio.'/F'.$filainicio);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$filainicio,'100%');

			$objPHPExcel->getActiveSheet()->getStyle('H'.$filainicio.':J'.$filainicio)
		    ->getNumberFormat()->applyFromArray( 
		        array( 
		            'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
		        )
		    );			
			$filainicio++;	
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'TOTAL NETO');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'=SUM(C'.$puntoinicio.':C'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'=SUM(D'.$puntoinicio.':D'.($filainicio-1).')');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'=SUM(E'.$puntoinicio.':E'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,'=SUM(F'.$puntoinicio.':F'.($filainicio-1).')');

	$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,'=C'.$filainicio.'/F'.$filainicio);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'=D'.$filainicio.'/F'.$filainicio);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'=E'.$filainicio.'/F'.$filainicio);
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$filainicio,'100%');	

	$objPHPExcel->getActiveSheet()->getStyle('H'.$filainicio.':J'.$filainicio)
    ->getNumberFormat()->applyFromArray( 
        array( 
            'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
        )
    );	

	$objPHPExcel->getActiveSheet()->getStyle('A'.$puntoinicio.':F'.$filainicio)->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$puntoinicio.':K'.$filainicio)->applyFromArray($style_solido_rojo);	

	$filainicio=$filainicio+2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'CLIENTE POR ESTADO');
	$filainicio=$filainicio+2;

	$sqlusuario="SELECT distinct idusuario_servicio FROM tmp_contactabilidad_".$fecha." ORDER BY idusuario_servicio";
	$array_usuario=array();
	$prusuario=$connection->prepare($sqlusuario);
	if($prusuario->execute()){
		while($datausuario=$prusuario->fetch(PDO::FETCH_ASSOC)){
			array_push($array_usuario, $datausuario['idusuario_servicio']);
		}
	}

	for($i=0;$i<count($array_usuario);$i++){
		$sqldetalle="SELECT idusuario_servicio,operador,nombrefinal,carga,COUNT(*) as 'TOTAL' FROM tmp_contactabilidad_".$fecha."
					WHERE idusuario_servicio=".$array_usuario[$i]." AND carga IN ('CEF','NOC','CNE') GROUP BY idusuario_servicio,nombrefinal";
		$prdetalle=$connection->prepare($sqldetalle);
		if($prdetalle->execute()){
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'CODIGO');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'OPERADOR');
				$objPHPExcel->getActiveSheet()->mergeCells('C'.$filainicio.':G'.$filainicio);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'ESTADO');	
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,'CARGA');				
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'ABONADOS');
			    $objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':I'.$filainicio)->applyFromArray($style_fondo_rojo);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':I'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':I'.$filainicio)->applyFromArray($style_alinear_centro);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':I'.$filainicio)->applyFromArray($style_solido_rojo);									
				$filainicio++;
				$puntoinicio=$filainicio;
				while($datadetalle=$prdetalle->fetch(PDO::FETCH_ASSOC)){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$datadetalle['idusuario_servicio']);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$datadetalle['operador']);
					$objPHPExcel->getActiveSheet()->mergeCells('C'.$filainicio.':G'.$filainicio);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,$datadetalle['nombrefinal']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,$datadetalle['carga']);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,$datadetalle['TOTAL']);
					$filainicio++;
				}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$puntoinicio.':I'.$filainicio)->applyFromArray($style_solido_rojo);
				$objPHPExcel->getActiveSheet()->mergeCells('C'.$filainicio.':G'.$filainicio);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'TOTAL');
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'=SUM(I'.$puntoinicio.':I'.($filainicio-1).')');
				$filainicio=$filainicio+2;
		}
	}
/*
	$objPHPExcel->getActiveSheet()->getStyle('A6:M'.($filainicio-1))->applyFromArray($style_borde_interno_punteado_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':M'.$filainicio)->applyFromArray($style_solido_rojo);

	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'=SUM(D6:D'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->getCell('D'.$filainicio)->getCalculatedValue();

	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'=SUM(E6:E'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->getCell('E'.$filainicio)->getCalculatedValue();	

	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,'=SUM(F6:F'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->getCell('F'.$filainicio)->getCalculatedValue();		

	$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,'=SUM(H6:H'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->getCell('H'.$filainicio)->getCalculatedValue();			

	$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,'=ROUND(H'.$filainicio.'/D'.$filainicio.',2)');
	$objPHPExcel->getActiveSheet()->getCell('I'.$filainicio)->getCalculatedValue();				

	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'=SUM(J6:J'.($filainicio-1).')');
	$objPHPExcel->getActiveSheet()->getCell('J'.$filainicio)->getCalculatedValue();				

	$objPHPExcel->getActiveSheet()->setCellValue('K'.$filainicio,'=ROUND(J'.$filainicio.'/D'.$filainicio.',2)');
	$objPHPExcel->getActiveSheet()->getCell('K'.$filainicio)->getCalculatedValue();				

	$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,'=ROUND(H'.$filainicio.'/E'.$filainicio.',2)');
	$objPHPExcel->getActiveSheet()->getCell('L'.$filainicio)->getCalculatedValue();					

	$objPHPExcel->getActiveSheet()->setCellValue('M'.$filainicio,'=ROUND(J'.$filainicio.'/E'.$filainicio.',2)');
	$objPHPExcel->getActiveSheet()->getCell('M'.$filainicio)->getCalculatedValue();						

/************************/  


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reporte_contactabilidad.xls"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');         

?>