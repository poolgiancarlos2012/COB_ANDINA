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
	$tipocambio=$_REQUEST['tipocambio'];
	$tipovac=$_REQUEST['tipovac'];
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

$style_fondo_rojo_claro2 = array(
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
            'argb' => 'FFE6B9B8',
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

 $style_borde_interno_punteado_rojo=                   array(
                            'borders' => array(
							        'inside'=> array(
							        	'style'=> PHPExcel_Style_Border::BORDER_DASHED,
							        	'color' => array('argb'=>'FFA9403D')
							        )
                                )
                    );    

$style_negrita = array(
    'font' => array(
        'bold' => true,
    )
);


    /*creando objeto excel*/


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

	$createhora=Date("Ymd_His");
	$sqltemporal="CREATE TEMPORARY TABLE tmp_fotocartera_".$createhora."
				(
					codcent varchar(20),
					Nombre varchar(100),
					nro_doc varchar(25),
					tipodoc varchar(25),
					divisa varchar(25),
					saldohoy varchar(25),
					producto varchar(100),
					tramo_dia_hdec varchar(25),
					contrato varchar(45),
					agencia varchar(80),
					Fproceso varchar(25),
					neto_soles varchar(25),
					carga_llamada varchar(25),
					estado_llamada varchar(100),
					fecha_cp_llamada varchar(25),
					carga_visita varchar(25),
					estado_visita varchar(100),
					fecha_cp_visita varchar(25),
					INDEX index_codcent(codcent ASC),
					INDEX index_tramo(tramo_dia_hdec ASC),
					INDEX index_carga_llamada(carga_llamada ASC)
					)";

	$pr_sql_temporal=$connection->prepare($sqltemporal);
	if($pr_sql_temporal->execute()){

	}else{
		return array();
        exit();
	}

	$sqlinsertatemporal="Insert tmp_fotocartera_".$createhora."
						(SELECT CONCAT('=\"',codcent,'\"'),Nombre,nro_doc,tipodoc,divisa,saldohoy,producto,tramo_dia_hdec,contrato,agencia,Fproceso,neto_soles,mejorllamada.carga,mejorllamada.estado,mejorllamada.fecha_cp,mejorvisita.carga as carga_visita,mejorvisita.estado as estado_visita,mejorvisita.fecha_cp as fecha_cp_visita FROM(
								select clicar.codigo_cliente As codcent,
								(SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente) as Nombre,
								(SELECT CONCAt('=\"',numero_documento,'\"') FROM ca_cliente where idcliente=clicar.idcliente) as nro_doc,
								(SELECT tipo_documento FROM ca_cliente where idcliente=clicar.idcliente) as tipodoc,
								cu.moneda As divisa,
								cu.total_deuda AS saldohoy,
								cu.producto,
								CASE 
												WHEN CAST(detcu.dias_mora AS SIGNED) <= 30 THEN 'TRAMO_1'
												WHEN CAST(detcu.dias_mora AS SIGNED) > 30 AND CAST(detcu.dias_mora AS SIGNED) <= 60 THEN 'TRAMO_2'
												WHEN CAST(detcu.dias_mora AS SIGNED) > 60 THEN 'TRAMO_3'
												ELSE 'NO_TRAMO'
											END AS tramo_dia_hdec,
								CONCAT('=\"',cu.numero_cuenta,'\"') As contrato,
								clicar.dato1 as agencia,
								cu.dato1 as Fproceso,
								IF(cu.moneda='USD',$tipocambio*cu.total_deuda,IF(cu.moneda='VAC',$tipovac*cu.total_deuda,cu.total_deuda)) as neto_soles
								from ca_cliente_cartera clicar
								inner join ca_cuenta cu ON clicar.idcliente_cartera=cu.idcliente_cartera
								inner join ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
								WHERE clicar.idcartera IN ($cartera)
						)base
						LEFT JOIN (
							SELECT * FROM (
											SELECT clicar.codigo_cliente,carfin.nombre as carga,fin.nombre as estado,lla.fecha_cp,finser.peso FROM ca_llamada lla
											INNER JOIN ca_cliente_cartera clicar ON lla.idcliente_cartera=clicar.idcliente_cartera
											INNER JOIN ca_final fin on lla.idfinal=fin.idfinal
											INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
											INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final
											WHERE finser.idservicio=$servicio and clicar.idcartera in ($cartera) ORDER BY clicar.codigo_cliente, finser.peso DESC
							)A GROUP BY codigo_cliente
						)mejorllamada ON mejorllamada.codigo_cliente=base.codcent
						LEFT JOIN (
							SELECT * FROM (
											SELECT clicar.codigo_cliente,carfin.nombre as carga,fin.nombre as estado,vis.fecha_cp,finser.peso FROM ca_visita vis
											INNER JOIN ca_cliente_cartera clicar ON vis.idcliente_cartera=clicar.idcliente_cartera
											INNER JOIN ca_final fin on vis.idfinal=fin.idfinal
											INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
											INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final
											WHERE finser.idservicio=$servicio and clicar.idcartera in ($cartera) ORDER BY clicar.codigo_cliente, finser.peso DESC
							)A GROUP BY codigo_cliente
						)mejorvisita ON mejorvisita.codigo_cliente=base.codcent)";

	$pr_inserta_temporal=$connection->prepare($sqlinsertatemporal);
	if($pr_inserta_temporal->execute()){

	}else{
		return array();
        exit();
	}


    $objPHPExcel=new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('RESUMEN');
    $objPHPExcel->getActiveSheet()->setCellValue('A2','REPORTE ACUMULADO');
    $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('A2:Q2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_alinear_centro);

    $objPHPExcel->getActiveSheet()->setCellValue('A4','GESTION CALL');
    $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('A4:H4');
	$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($style_alinear_centro);	

    $objPHPExcel->getActiveSheet()->setCellValue('J4','GESTION CAMPO');
    $objPHPExcel->getActiveSheet()->getStyle('J4:Q4')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('J4:Q4')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('J4:Q4');
	$objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray($style_alinear_centro);	

	$objPHPExcel->getActiveSheet()->getStyle('A6:C6')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A6:C6')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A6:C6')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('A6','tramo_dia_hdec');
	$objPHPExcel->getActiveSheet()->setCellValue('B6','tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('C6','total');	

	$objPHPExcel->getActiveSheet()->getStyle('E6:H6')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('E6:H6')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('E6:H6')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('E6','tramo_dia_hdec');
	$objPHPExcel->getActiveSheet()->setCellValue('F6','tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('G6','total');	
	$objPHPExcel->getActiveSheet()->setCellValue('H6','%Monto');	

	$objPHPExcel->getActiveSheet()->getStyle('J6:L6')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('J6:L6')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('J6:L6')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('J6','tramo_dia_hdec');
	$objPHPExcel->getActiveSheet()->setCellValue('K6','tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('L6','total');	

	$objPHPExcel->getActiveSheet()->getStyle('N6:Q6')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('N6:Q6')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('N6:Q6')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('N6','tramo_dia_hdec');
	$objPHPExcel->getActiveSheet()->setCellValue('O6','tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('P6','total');	
	$objPHPExcel->getActiveSheet()->setCellValue('Q6','%Monto');	

	$objPHPExcel->getActiveSheet()->getStyle('A6:Q6')->getFont()->setSize(8);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);	


	/*GESTIOn cantidad*/
	/*tramo1*/
	$sqlcallgestion1="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora." ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B WHERE tramo_dia_hdec='TRAMO_1' GROUP BY tramo_dia_hdec,carga_llamada 
					ORDER BY tramo_dia_hdec,carga_llamada";

	$prcallgestion1=$connection->prepare($sqlcallgestion1);

	$cont=0;
	$filainicio=7;


	if($prcallgestion1->execute()){
		while($data_call_gestion1=$prcallgestion1->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion1 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=0;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('A7:A'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A7:A'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('A7:A'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total Tramo 1');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C7:C".($filainicio-1).")");
	$filainicio++;
	$marca=$filainicio;

	/*tramo2*/
	$sqlcallgestion2="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora." ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B WHERE tramo_dia_hdec='TRAMO_2' GROUP BY tramo_dia_hdec,carga_llamada 
					ORDER BY tramo_dia_hdec,carga_llamada";

	$prcallgestion2=$connection->prepare($sqlcallgestion2);

	$cont=0;

	if($prcallgestion2->execute()){
		while($data_call_gestion2=$prcallgestion2->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion2 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=0;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$marca.':A'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$marca.':A'.($filainicio-1))->applyFromArray($style_negrita);		
	$objPHPExcel->getActiveSheet()->mergecells('A'.$marca.':A'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total Tramo 2');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C".$marca.":C".($filainicio-1).")");
	$filainicio++;
	$marca=$filainicio;	

	/*tramo3*/
	$sqlcallgestion3="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora." ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B WHERE tramo_dia_hdec='TRAMO_3' GROUP BY tramo_dia_hdec,carga_llamada 
					ORDER BY tramo_dia_hdec,carga_llamada";

	$prcallgestion3=$connection->prepare($sqlcallgestion3);

	$cont=0;

	if($prcallgestion3->execute()){
		while($data_call_gestion3=$prcallgestion3->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion3 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=0;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$marca.':A'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$marca.':A'.($filainicio-1))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->mergecells('A'.$marca.':A'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total Tramo 3');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':C'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C".$marca.":C".($filainicio-1).")");
	$filainicio++;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->getStyle('A'.($filainicio))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C7:C".($filainicio-1).")/2");	

	/*GESTIOn monto*/
	/*tramo1*/

	$arraymontocall=array();
	$sqlcallgestion1="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_1' 
						GROUP BY tramo_dia_hdec,carga_llamada
						ORDER BY tramo_dia_hdec,carga_llamada";

	$prcallgestion1=$connection->prepare($sqlcallgestion1);

	$cont=4;
	$filainicio=7;
	$marca=$filainicio;


	if($prcallgestion1->execute()){
		while($data_call_gestion1=$prcallgestion1->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion1 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=4;
		}
	}
	array_push($arraymontocall, $filainicio);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('E'.$marca.':E'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'Total Tramo 1');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio.':H'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$filainicio,"=SUM(G".$marca.":G".($filainicio-1).")");


	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,"=G".$i."/G".$filainicio);
	}

	$filainicio++;
	$marca=$filainicio;


	/*tramo2*/
	$sqlcallgestion2="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_2' 
						GROUP BY tramo_dia_hdec,carga_llamada
						ORDER BY tramo_dia_hdec,carga_llamada";;

	$prcallgestion2=$connection->prepare($sqlcallgestion2);

	$cont=4;
	$marca=$filainicio;


	if($prcallgestion2->execute()){
		while($data_call_gestion2=$prcallgestion2->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion2 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=4;
		}
	}
	array_push($arraymontocall, $filainicio);	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('E'.$marca.':E'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'Total Tramo 2');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio.':H'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$filainicio,"=SUM(G".$marca.":G".($filainicio-1).")");

	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,"=G".$i."/G".$filainicio);
	}

	$filainicio++;
	$marca=$filainicio;

	/*tramo3*/
	$sqlcallgestion3="SELECT tramo_dia_hdec,IFNULL(carga_llamada,'S/G') as carga_llamada,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_3' 
						GROUP BY tramo_dia_hdec,carga_llamada
						ORDER BY tramo_dia_hdec,carga_llamada";;

	$prcallgestion3=$connection->prepare($sqlcallgestion3);

	$cont=4;
	$marca=$filainicio;


	if($prcallgestion3->execute()){
		while($data_call_gestion3=$prcallgestion3->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_call_gestion3 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=4;
		}
	}
	array_push($arraymontocall, $filainicio);	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$marca.':E'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('E'.$marca.':E'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'Total Tramo 3');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filainicio.':H'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$filainicio,"=SUM(G".$marca.":G".($filainicio-1).")");


	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,"=G".$i."/G".$filainicio);
	}
		

	$filainicio++;
	$marca=$filainicio;

	for($j=0;$j<count($arraymontocall);$j++){
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$arraymontocall[$j],"=G".$arraymontocall[$j]."/G".$filainicio);
	}

	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,1);	
	$objPHPExcel->getActiveSheet()->getStyle('E'.($filainicio))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$filainicio,"=SUM(G7:G".($filainicio-1).")/2");
	$objPHPExcel->getActiveSheet()->getStyle('H7:H'.$filainicio)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00 ) );			
	$objPHPExcel->getActiveSheet()->getStyle('G7:G'.$filainicio)->getNumberFormat()->setFormatCode("S/. #,##0.##");  


	/*VISITA*/

	/*GESTION CANTIDAD*/
	/*tramo1*/
	$sqlvisitagestion1="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/V') as carga_visita,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora."  ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B  WHERE tramo_dia_hdec='TRAMO_1' GROUP BY tramo_dia_hdec,carga_visita 
					ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion1=$connection->prepare($sqlvisitagestion1);

	$cont=9;
	$filainicio=7;


	if($prvisitagestion1->execute()){
		while($data_visita_gestion1=$prvisitagestion1->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion1 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=9;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('J7:J'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('J7:J'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('J7:J'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'Total Tramo 1');
	$objPHPExcel->getActiveSheet()->getStyle('J'.$filainicio.':L'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,"=SUM(L7:L".($filainicio-1).")");
	$filainicio++;
	$marca=$filainicio;

	/*tramo2*/
	$sqlvisitagestion2="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/V') as carga_visita,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora." ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B WHERE tramo_dia_hdec='TRAMO_2' GROUP BY tramo_dia_hdec,carga_visita 
					ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion2=$connection->prepare($sqlvisitagestion2);

	$cont=9;

	if($prvisitagestion2->execute()){
		while($data_visita_gestion2=$prvisitagestion2->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion2 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=9;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('J'.$marca.':J'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$marca.':J'.($filainicio-1))->applyFromArray($style_negrita);		
	$objPHPExcel->getActiveSheet()->mergecells('J'.$marca.':J'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'Total Tramo 2');
	$objPHPExcel->getActiveSheet()->getStyle('J'.$filainicio.':L'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,"=SUM(L".$marca.":L".($filainicio-1).")");
	$filainicio++;
	$marca=$filainicio;	

	/*tramo3*/
	$sqlvisitagestion3="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/V') as carga_visita,count(*) as cantidad FROM(
						SELECT * FROM 
						(select * from tmp_fotocartera_".$createhora." ORDER BY codcent,tramo_dia_hdec ASC)A GROUP BY codcent
					)B WHERE tramo_dia_hdec='TRAMO_3' GROUP BY tramo_dia_hdec,carga_visita 
					ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion3=$connection->prepare($sqlvisitagestion3);

	$cont=9;

	if($prvisitagestion3->execute()){
		while($data_visita_gestion3=$prvisitagestion3->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion3 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=9;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle('J'.$marca.':J'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$marca.':J'.($filainicio-1))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->mergecells('J'.$marca.':J'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'Total Tramo 3');
	$objPHPExcel->getActiveSheet()->getStyle('J'.$filainicio.':L'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,"=SUM(L".$marca.":L".($filainicio-1).")");
	$filainicio++;

	$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->getStyle('J'.($filainicio))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,"=SUM(L7:L".($filainicio-1).")/2");	

	/*GESTIOn monto*/
	/*tramo1*/

	$arraymontovisita=array();
	$sqlvisitagestion1="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/G') as carga_visita,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_1' 
						GROUP BY tramo_dia_hdec,carga_visita
						ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion1=$connection->prepare($sqlvisitagestion1);

	$cont=13;
	$filainicio=7;
	$marca=$filainicio;


	if($prvisitagestion1->execute()){
		while($data_visita_gestion1=$prvisitagestion1->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion1 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=13;
		}
	}
	array_push($arraymontovisita, $filainicio);
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('N'.$marca.':N'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$filainicio,'Total Tramo 1');
	$objPHPExcel->getActiveSheet()->getStyle('N'.$filainicio.':Q'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$filainicio,"=SUM(P".$marca.":P".($filainicio-1).")");


	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i,"=P".$i."/P".$filainicio);
	}

	$filainicio++;
	$marca=$filainicio;


	/*tramo2*/
	$sqlvisitagestion2="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/G') as carga_visita,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_2' 
						GROUP BY tramo_dia_hdec,carga_visita
						ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion2=$connection->prepare($sqlvisitagestion2);

	$cont=13;
	$marca=$filainicio;


	if($prvisitagestion2->execute()){
		while($data_visita_gestion2=$prvisitagestion2->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion2 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=13;
		}
	}
	array_push($arraymontovisita, $filainicio);	
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('N'.$marca.':N'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$filainicio,'Total Tramo 2');
	$objPHPExcel->getActiveSheet()->getStyle('N'.$filainicio.':Q'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$filainicio,"=SUM(P".$marca.":P".($filainicio-1).")");

	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i,"=P".$i."/P".$filainicio);
	}

	$filainicio++;
	$marca=$filainicio;

	/*tramo3*/
	$sqlvisitagestion3="SELECT tramo_dia_hdec,IFNULL(carga_visita,'S/G') as carga_visita,ROUND(SUM(neto_soles),2) as neto_soles
						from tmp_fotocartera_".$createhora." WHERE tramo_dia_hdec='TRAMO_3' 
						GROUP BY tramo_dia_hdec,carga_visita
						ORDER BY tramo_dia_hdec,carga_visita";

	$prvisitagestion3=$connection->prepare($sqlvisitagestion3);

	$cont=13;
	$marca=$filainicio;


	if($prvisitagestion3->execute()){
		while($data_visita_gestion3=$prvisitagestion3->fetch(PDO::FETCH_ASSOC)){
			foreach ($data_visita_gestion3 as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
				$cont=$cont+1;
			}
			$filainicio=$filainicio+1;
			$cont=13;
		}
	}
	array_push($arraymontovisita, $filainicio);	
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_fondo_rojo_claro);
	$objPHPExcel->getActiveSheet()->getStyle('N'.$marca.':N'.($filainicio-1))->applyFromArray($style_negrita);	
	$objPHPExcel->getActiveSheet()->mergecells('N'.$marca.':N'.($filainicio-1));
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$filainicio,'Total Tramo 3');
	$objPHPExcel->getActiveSheet()->getStyle('N'.$filainicio.':Q'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$filainicio,"=SUM(P".$marca.":P".($filainicio-1).")");


	for($i=$marca;$i<$filainicio;$i++){
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i,"=P".$i."/P".$filainicio);
	}
		

	$filainicio++;
	$marca=$filainicio;

	for($j=0;$j<count($arraymontovisita);$j++){
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$arraymontovisita[$j],"=P".$arraymontovisita[$j]."/P".$filainicio);
	}

	//$filainicio++;
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$filainicio,1);	
	$objPHPExcel->getActiveSheet()->getStyle('N'.($filainicio))->applyFromArray($style_negrita);			
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$filainicio,"=SUM(P7:P".($filainicio-1).")/2");
	$objPHPExcel->getActiveSheet()->getStyle('Q7:Q'.$filainicio)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00 ) );			
	$objPHPExcel->getActiveSheet()->getStyle('P7:P'.$filainicio)->getNumberFormat()->setFormatCode("S/. #,##0.##");  	

	$filainicio=$filainicio+2;
	/*MATRIZ*/

    $objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'MATRIZ');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':M'.$filainicio)->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':M'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('A'.$filainicio.':M'.$filainicio);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio)->applyFromArray($style_alinear_centro);	

	/*CUADRO 1 CANTIDAD*/
	$filainicio=$filainicio+2;

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'CEF');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'CNE');	
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'NOC');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'S/V');		
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,'Total General');

	$filainicio++;
	$marca=$filainicio;

	$sql="SELECT 'CEF',
			SUM(IF(carga_llamada='CEF' AND carga_visita='CEF',1,0)) AS CEF_CEF,
			SUM(IF(carga_llamada='CEF' AND carga_visita='CNE',1,0)) AS CEF_CNE,
			SUM(IF(carga_llamada='CEF' AND carga_visita='NOC',1,0)) AS CEF_NOC,
			SUM(IF(carga_llamada='CEF' AND carga_visita IS NULL,1,0)) AS CEF_SV
			FROM (SELECT * from tmp_fotocartera_".$createhora." GROUP BY codcent)A";	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}

	$sql="SELECT 'CNE',
			SUM(IF(carga_llamada='CNE' AND carga_visita='CEF',1,0)) AS CEF_CEF,
			SUM(IF(carga_llamada='CNE' AND carga_visita='CNE',1,0)) AS CEF_CNE,
			SUM(IF(carga_llamada='CNE' AND carga_visita='NOC',1,0)) AS CEF_NOC,
			SUM(IF(carga_llamada='CNE' AND carga_visita IS NULL,1,0)) AS CEF_SV
			FROM (SELECT * from tmp_fotocartera_".$createhora." GROUP BY codcent)A";		

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}

	$sql="SELECT 'NOC',
			SUM(IF(carga_llamada='NOC' AND carga_visita='CEF',1,0)) AS CEF_CEF,
			SUM(IF(carga_llamada='NOC' AND carga_visita='CNE',1,0)) AS CEF_CNE,
			SUM(IF(carga_llamada='NOC' AND carga_visita='NOC',1,0)) AS CEF_NOC,
			SUM(IF(carga_llamada='NOC' AND carga_visita IS NULL,1,0)) AS CEF_SV
			FROM (SELECT * from tmp_fotocartera_".$createhora." GROUP BY codcent)A";	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}	

	$sql="SELECT 'S/G',
			SUM(IF(carga_llamada IS NULL AND carga_visita='CEF',1,0)) AS CEF_CEF,
			SUM(IF(carga_llamada IS NULL AND carga_visita='CNE',1,0)) AS CEF_CNE,
			SUM(IF(carga_llamada IS NULL AND carga_visita='NOC',1,0)) AS CEF_NOC,
			SUM(IF(carga_llamada IS NULL AND carga_visita IS NULL,1,0)) AS CEF_SV
			FROM (SELECT * from tmp_fotocartera_".$createhora." GROUP BY codcent)A";	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}	

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,"=SUM(B".$marca.":B".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C".$marca.":C".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,"=SUM(D".$marca.":D".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,"=SUM(E".$marca.":E".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,"=SUM(F".$marca.":F".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);

	/*CUADRO 2 MONTO*/
	$filainicio=$filainicio+2;

	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Tipo contacto');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,'CEF');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,'CNE');	
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'NOC');	
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,'S/V');		
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,'Total General');

	$filainicio++;
	$marca=$filainicio;	

	$sql="SELECT 'CEF',
			ROUND(SUM(IF(carga_llamada='CEF' AND carga_visita='CEF',neto_soles,0)),2) AS CEF_CEF,
			ROUND(SUM(IF(carga_llamada='CEF' AND carga_visita='CNE',neto_soles,0)),2) AS CEF_CNE,
			ROUND(SUM(IF(carga_llamada='CEF' AND carga_visita='NOC',neto_soles,0)),2) AS CEF_NOC,
			ROUND(SUM(IF(carga_llamada='CEF' AND carga_visita IS NULL,neto_soles,0)),2) AS CEF_SV
			FROM tmp_fotocartera_".$createhora;	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}	

	$sql="SELECT 'CNE',
			ROUND(SUM(IF(carga_llamada='CNE' AND carga_visita='CEF',neto_soles,0)),2) AS CEF_CEF,
			ROUND(SUM(IF(carga_llamada='CNE' AND carga_visita='CNE',neto_soles,0)),2) AS CEF_CNE,
			ROUND(SUM(IF(carga_llamada='CNE' AND carga_visita='NOC',neto_soles,0)),2) AS CEF_NOC,
			ROUND(SUM(IF(carga_llamada='CNE' AND carga_visita IS NULL,neto_soles,0)),2) AS CEF_SV
			FROM tmp_fotocartera_".$createhora;	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}	

	$sql="SELECT 'NOC',
			ROUND(SUM(IF(carga_llamada='NOC' AND carga_visita='CEF',neto_soles,0)),2) AS CEF_CEF,
			ROUND(SUM(IF(carga_llamada='NOC' AND carga_visita='CNE',neto_soles,0)),2) AS CEF_CNE,
			ROUND(SUM(IF(carga_llamada='NOC' AND carga_visita='NOC',neto_soles,0)),2) AS CEF_NOC,
			ROUND(SUM(IF(carga_llamada='NOC' AND carga_visita IS NULL,neto_soles,0)),2) AS CEF_SV
			FROM tmp_fotocartera_".$createhora;	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}

	$sql="SELECT 'S/G',
			ROUND(SUM(IF(carga_llamada IS NULL AND carga_visita='CEF',neto_soles,0)),2) AS CEF_CEF,
			ROUND(SUM(IF(carga_llamada IS NULL AND carga_visita='CNE',neto_soles,0)),2) AS CEF_CNE,
			ROUND(SUM(IF(carga_llamada IS NULL AND carga_visita='NOC',neto_soles,0)),2) AS CEF_NOC,
			ROUND(SUM(IF(carga_llamada IS NULL AND carga_visita IS NULL,neto_soles,0)),2) AS CEF_SV
			FROM tmp_fotocartera_".$createhora;	

	$prsql=$connection->prepare($sql);
	$cont=0;
	if($prsql->execute()){
		while($datasql=$prsql->fetch(PDO::FETCH_ASSOC)){
			foreach ($datasql as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,$value);
				$cont++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].$filainicio,"=SUM(B".$filainicio.":".$abc[($cont-1)].$filainicio.")");
			$cont=0;
			$filainicio++;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,'Total General');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,"=SUM(B".$marca.":B".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,"=SUM(C".$marca.":C".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,"=SUM(D".$marca.":D".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,"=SUM(E".$marca.":E".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,"=SUM(F".$marca.":F".($filainicio-1).")");
	$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':F'.$filainicio)->applyFromArray($style_fondo_rojo_claro2);

	$objPHPExcel->getActiveSheet()->getStyle('B'.$marca.':F'.$filainicio)->getNumberFormat()->setFormatCode("S/. #,##0.##");  

	/*HOJA 2*/
   	$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($style_solido_rojo);


    $objPHPExcel->getActiveSheet()->setTitle('FOTOCARTERA');

    $sqlfotocartera="SELECT * from tmp_fotocartera_".$createhora;


    $pr_fotocartera=$connection->prepare($sqlfotocartera);

     $filainiciofotocartera=2;
     $cont=0;
     $i=0;

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
			foreach ($data_fotocartera as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainiciofotocartera,utf8_decode($value));						
					$cont=$cont+1;
			}
				$filainiciofotocartera=$filainiciofotocartera+1;							
		}
	}   


  header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="RESUMEN_ACUMULADO.xls"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');         

?>