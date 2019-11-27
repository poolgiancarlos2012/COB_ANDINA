<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];

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

    /*creando objeto excel*/
    $objPHPExcel=new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('RESUMEN');
    $objPHPExcel->getActiveSheet()->setCellValue('A2','RESUMEN DE GESTION');
    $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('A2:M2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_alinear_centro);


	$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('A5','GRUPO');
	$objPHPExcel->getActiveSheet()->setCellValue('B5','INICIO GESTION');
	$objPHPExcel->getActiveSheet()->setCellValue('C5','FIN GESTION');	
	$objPHPExcel->getActiveSheet()->setCellValue('D5','CLIENTES ASIGNADOS');
	$objPHPExcel->getActiveSheet()->setCellValue('E5','CLIENTES POR GESTIONAR');
	$objPHPExcel->getActiveSheet()->setCellValue('F5','IMPORTE');
	$objPHPExcel->getActiveSheet()->setCellValue('G5','EFECTIVIDAD');
	$objPHPExcel->getActiveSheet()->setCellValue('H5','LLAMADAS');					
	$objPHPExcel->getActiveSheet()->setCellValue('I5','PROM LLAMADAS');	
	$objPHPExcel->getActiveSheet()->setCellValue('J5','VISITAS');	
	$objPHPExcel->getActiveSheet()->setCellValue('K5','PROM VISITAS');	
	$objPHPExcel->getActiveSheet()->setCellValue('L5','PROM LLAM X GESTIONAR');	
	$objPHPExcel->getActiveSheet()->setCellValue('M5','PROM VIS X GESTIONAR');			
	$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->getFont()->setSize(8);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);		
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);			
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);			
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);							
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);			

	$sql="SELECT C.GRUPO,C.INICIO_GESTION,C.FIN_GESTION,D.CLIENTES_ASIGNADOS,D.CLIENTES_POR_GESTIONAR,C.TOTAL_DEUDA,
			G.MONTO_PAGADO,CONCAT(ROUND((G.MONTO_PAGADO/C.TOTAL_DEUDA)*100,2),'%') AS 'EFECTIVIDAD',IF(ISNULL(E.TOTAL_LLAMADA),'0',E.TOTAL_LLAMADA) as 'TOTAL_LLAMADA',
			ROUND((IF(ISNULL(E.TOTAL_LLAMADA),'0',E.TOTAL_LLAMADA)/D.CLIENTES_ASIGNADOS),2) AS 'PROM_LLAMADAS',IF(ISNULL(F.TOTAL_VISITA),'0',F.TOTAL_VISITA) AS 'TOTAL_VISITA',
			ROUND((IF(ISNULL(F.TOTAL_VISITA),'0',F.TOTAL_VISITA)/D.CLIENTES_ASIGNADOS),2) AS 'PROM_VISITA',
			ROUND((IF(ISNULL(E.TOTAL_LLAMADA),'0',E.TOTAL_LLAMADA)/D.CLIENTES_POR_GESTIONAR),2) AS 'PROM_LLAM_X_GESTIONAR',
			ROUND((IF(ISNULL(F.TOTAL_VISITA),'0',F.TOTAL_VISITA)/D.CLIENTES_POR_GESTIONAR),2) AS 'PROM_VIS_X_GESTIONAR'
			FROM (
				SELECT GRUPO,sum(total_deuda) as 'total_deuda',(select fecha_inicio from ca_cartera where idcartera=$cartera) as 'INICIO_GESTION',
				(select fecha_fin from ca_cartera where idcartera=$cartera) as 'FIN_GESTION'
				FROM (
						SELECT * from (
						select idcliente_cartera,total_deuda,dato6 as grupo from ca_historico_cuenta 
						where idcartera=$cartera order by fecha_ingreso )A
						GROUP BY idcliente_cartera
				)B
				GROUP BY B.grupo)C 
			LEFT JOIN 
				(SELECT dato6 AS 'GRUPO',SUM(IF(estado_pago='Gestionar',1,0)) AS 'CLIENTES_POR_GESTIONAR',count(*) as 'CLIENTES_ASIGNADOS'
				FROM ca_cuenta 
				where idcartera=$cartera 
				GROUP BY GRUPO)D
			ON C.GRUPO=D.GRUPO
			LEFT JOIN 
				(SELECT cu.dato6 as 'GRUPO',count(*) AS 'TOTAL_LLAMADA' FROM ca_llamada lla
				inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
				inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
				where clicar.idcartera=$cartera and tipo='LL' and cu.idcartera=$cartera and DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
				GROUP BY GRUPO)E
			ON E.GRUPO=C.GRUPO
			LEFT JOIN
				(SELECT cu.dato6 as 'GRUPO',count(*) AS 'TOTAL_VISITA' FROM ca_visita vis
				inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=vis.idcliente_cartera
				inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=vis.idcuenta
				where clicar.idcartera=$cartera and cu.idcartera=$cartera and DATE(vis.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin'
				GROUP BY GRUPO)F
			ON F.GRUPO=C.GRUPO
			LEFT JOIN
				(SELECT cu.dato6 AS 'GRUPO',SUM(pag.monto_pagado) AS 'MONTO_PAGADO' 
				FROM ca_cuenta cu
				inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
				inner join ca_pago pag on pag.iddetalle_cuenta=detcu.iddetalle_cuenta
				where cu.idcartera=$cartera and detcu.idcartera=$cartera and pag.idcartera=$cartera and pag.estado=1
				GROUP BY GRUPO)G
			ON G.GRUPO=C.GRUPO";	

	$filainicio=6;
	$total_cliente_asignado=0;
	$total_cliente_por_gestionar=0;
	$total_importe=0;
	$total_llamadas=0;

	$pr=$connection->prepare($sql);
	if($pr->execute()){
		while($data=$pr->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data['GRUPO']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$filainicio,$data['INICIO_GESTION']);			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$filainicio,$data['FIN_GESTION']);						
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,$data['CLIENTES_ASIGNADOS']);			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,$data['CLIENTES_POR_GESTIONAR']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$filainicio,$data['TOTAL_DEUDA']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$filainicio,$data['EFECTIVIDAD']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$filainicio,$data['TOTAL_LLAMADA']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$filainicio,$data['PROM_LLAMADAS']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$filainicio,$data['TOTAL_VISITA']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$filainicio,$data['PROM_VISITA']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$filainicio,$data['PROM_LLAM_X_GESTIONAR']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$filainicio,$data['PROM_VIS_X_GESTIONAR']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$filainicio,$data['MONTO_PAGADO']);			
			$filainicio++;
		}
	}	

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

	$sqlfotocartera="SELECT CONCAT('=\"',cu.numero_cuenta,'\"') AS 'NUMERO_CUENTA',cu.inscripcion AS 'TARJETA',
					cu.negocio AS 'TIPO_TARJETA',cu.dato6 AS 'GRUPO',
					(SELECT tipo_documento FROM ca_cliente where idcliente=clicar.idcliente) AS 'TIPO_DOC',CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODIGO_CLIENTE',
					(SELECT CONCAT('=\"',numero_documento,'\"') FROM ca_cliente where idcliente=clicar.idcliente) AS 'DOC',
					(SELECT CONCAT(IFNULL(paterno,''),' ',IFNULL(materno,''),' ',IFNULL(nombre,'')) FROM ca_cliente where idcliente=clicar.idcliente) AS 'NOMBRE_CLIENTE',
					(SELECT monto_mora_soles FROM ca_historico_detalle_cuenta where idhistorico_cuenta=B.idhistorico_cuenta) AS 'PAG_MIN_FACTURADO',
					B.monto_mora as 'SALDO_MORA',B.total_deuda AS 'MONTO FACTURADO'
					FROM (
								SELECT * from (
								select idcliente_cartera,idhistorico_cuenta,total_deuda,monto_mora from ca_historico_cuenta 
								where idcartera=$cartera order by fecha_ingreso ASC)A
								GROUP BY idcliente_cartera
					)B
					INNER JOIN ca_cliente_cartera clicar 
					ON clicar.idcliente_cartera=B.idcliente_cartera
					INNER JOIN ca_cuenta cu 
					ON cu.idcliente_cartera=B.idcliente_cartera
					INNER JOIN ca_detalle_cuenta detcu
					ON detcu.idcuenta=cu.idcuenta
					WHERE clicar.idcartera=$cartera and cu.idcartera=$cartera and detcu.idcartera=$cartera";

	/*HOJA 2*/
   	$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('FOTOCARTERA');

    $pr_fotocartera=$connection->prepare($sqlfotocartera);

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
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));						
					$cont=$cont+1;
			}
				$filainicio=$filainicio+1;							
		}
	}    

	/*HOJA 3*/
/*   	$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->setTitle('LLAMADA');


	$sqlllamada="SELECT (SELECT CONCAT(IFNULL(paterno,''),' ',IFNULL(materno,''),' ',IFNULL(nombre,'')) FROM ca_cliente where idcliente=clicar.idcliente) AS 'NOMBRE_CLIENTE',
				Date(lla.fecha) AS 'FECHA_LLAMADA',TIME(lla.fecha) AS 'HORA_LLAMADA',lla.fecha_cp AS 'FECHA_CP',lla.monto_cp AS 'MONTO_CP',
				cu.dato6 AS 'GRUPO'
				FROM ca_llamada lla
				inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
				inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=lla.idcuenta
				where clicar.idcartera=$cartera and cu.idcartera=$cartera and DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

	$pr_llamada=$connection->prepare($sqlllamada);

     $filainicio=2;
     $cont=0;
     $i=0;

	if($pr_llamada->execute()){
		while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_llamada	 as $key => $value) {
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
						$cont=$cont+1;
				}
			}		
			$i++;	
			$cont=0;
			$cant_contrato=array();
			$cant_detalle=array();
			foreach ($data_llamada as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));						
					$cont=$cont+1;
			}
				$filainicio=$filainicio+1;							
		}
	}    

	/*HOJA 4*/
/*   	$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(3);
    $objPHPExcel->getActiveSheet()->setTitle('VISITA');


	$sqlvisita="SELECT (SELECT CONCAT(IFNULL(paterno,''),' ',IFNULL(materno,''),' ',IFNULL(nombre,'')) FROM ca_cliente where idcliente=clicar.idcliente) AS 'NOMBRE_CLIENTE',
			Date(vis.fecha_visita) AS 'FECHA_VISITA',TIME(vis.hora_visita) AS 'HORA_VISITA',vis.fecha_cp AS 'FECHA_CP',vis.monto_cp AS 'MONTO_CP',
cu.dato6 AS 'GRUPO'
FROM ca_visita vis
	inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=vis.idcliente_cartera
	inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera and cu.idcuenta=vis.idcuenta
	where clicar.idcartera=$cartera and cu.idcartera=$cartera and DATE(vis.fecha_visita) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

	$pr_visita=$connection->prepare($sqlvisita);

     $filainicio=2;
     $cont=0;
     $i=0;

	if($pr_visita->execute()){
		while($data_visita=$pr_visita->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_visita	 as $key => $value) {
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
						$cont=$cont+1;
				}
			}		
			$i++;	
			$cont=0;
			$cant_contrato=array();
			$cant_detalle=array();
			foreach ($data_visita as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));						
					$cont=$cont+1;
			}
				$filainicio=$filainicio+1;							
		}
	}    

*/
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="RESUMEN_GESTION.xls"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');         

?>