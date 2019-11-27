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
            'argb' => 'FFA0DAF5',
        ),
    ),
);
 	/*HOJA1*/
	$objPHPExcel=new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('DISTRIBUCION');

	$sql_clientes="SELECT (SELECT CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu inner join ca_usuario_servicio ususer on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) as 'Operador',
					count(DISTINCT clicar.idcliente_cartera) AS 'Clientes',
					SUM(IF(cu.estado=1,IF(cu.moneda='PEN',cu.total_deuda,IF(cu.moneda='USD',$tipocambio*cu.total_deuda,$tipovac*cu.total_deuda)),0)) AS 'Monto_total' 
					FROM ca_cliente_cartera clicar
					inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
					where clicar.idcartera=$cartera and clicar.estado=1 and cu.idcartera=$cartera
					GROUP BY clicar.idusuario_servicio WITH ROLLUP";
	

/*	$sql_marca="SELECT DISTINCT IF(cu.dato8='COMER' OR cu.dato8='PNN','COMER_PNN',cu.dato8) AS 'marca' FROM ca_cliente_cartera clicar
					INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
					where clicar.idcartera=$cartera and clicar.estado=1 and cu.idcartera=$cartera and cu.estado=1
					ORDER BY marca";*/

	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	

    $objPHPExcel->getActiveSheet()->getStyle('B2:D2')->getFont()->setBold(true);        		

	$objPHPExcel->getActiveSheet()->getStyle('B2:D2')->applyFromArray($style_fondo_celeste);

	$objPHPExcel->getActiveSheet()->setCellValue('B2','Operador');
	$objPHPExcel->getActiveSheet()->setCellValue('C2','Clientes');
	$objPHPExcel->getActiveSheet()->setCellValue('D2','Deuda Total');

	$pr_clientes=$connection->prepare($sql_clientes);
	$fila_inicio=3;
	if($pr_clientes->execute()){
		while($data_clientes=$pr_clientes->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila_inicio,$data_clientes['Operador']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila_inicio,$data_clientes['Clientes']);			
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila_inicio,$data_clientes['Monto_total']);						
			$fila_inicio++;
		}
	}

	/*$pr_marca=$connection->prepare($sql_marca);
	$fila_inicio=3;
	$i=0;
	$total_deuda=0;
	$total_clientes=0;
	if($pr_marca->execute()){
		while($data_marca=$pr_marca->fetch(PDO::FETCH_ASSOC)){
					$sql_clientes="SELECT dato8,Gestor,SUM(B.Clientes) AS 'Clientes',SUM(B.Monto_total) AS 'Monto_total' FROM (
								SELECT IF(dato8='COMER' OR dato8='PNN','COMER_PNN',dato8) AS 'dato8',A.Gestor,A.Clientes ,A.Monto_total FROM(
								SELECT cu.dato8,(select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) from ca_usuario usu inner join ca_usuario_servicio ususer on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) as 'Gestor',
								count(DISTINCT clicar.idcliente_cartera) AS 'Clientes',
								SUM(IF(cu.estado=1,IF(cu.moneda='PEN',cu.total_deuda,IF(cu.moneda='USD',$tipocambio*cu.total_deuda,$tipovac*cu.total_deuda)),0)) AS 'Monto_total' 
								FROM ca_cliente_cartera clicar
								inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
								where clicar.idcartera=$cartera and clicar.estado=1 and cu.idcartera=$cartera 
								GROUP BY cu.dato8,clicar.idusuario_servicio ORDER BY Monto_total DESC
								)A 
								)B WHERE B.dato8='".$data_marca['marca']."' GROUP BY B.dato8,B.Gestor";		
					$pr_clientes=$connection->prepare($sql_clientes);
					if($pr_clientes->execute()){
						while($data_clientes=$pr_clientes->fetch(PDO::FETCH_ASSOC)){
							if($i==0){
								$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila_inicio,$data_marca['marca']);
    							$objPHPExcel->getActiveSheet()->getStyle('B'.$fila_inicio)->getFont()->setBold(true);        										
								$fila_inicio++;
								$i++;
							}
								$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila_inicio,$data_clientes['Gestor']);
								$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila_inicio,$data_clientes['Clientes']);			
								$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila_inicio,$data_clientes['Monto_total']);						
								$total_deuda=$total_deuda+$data_clientes['Monto_total'];
								$total_clientes=$total_clientes+$data_clientes['Clientes'];
								$fila_inicio++;								
						}
					}
					$i=0;
		}
	}
*/
	$objPHPExcel->getActiveSheet()->getStyle('B'.($fila_inicio-1).':D'.($fila_inicio-1))->applyFromArray($style_fondo_celeste);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($fila_inicio-1),'Total General');
//	$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila_inicio,$total_clientes);	
//	$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila_inicio,$total_deuda);

	/*HOJA2*/

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setTitle('DETALLE');

	$sql_detalle="SELECT CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'codcent',
					(SELECT usu.codigo FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'Usuario',
					cli.nombre,CONCAT('=\"',cli.numero_documento,'\"') AS 'nro_doc',cu.moneda,cu.total_deuda,
					IF(cu.moneda='USD',$tipocambio*cu.total_deuda,IF(cu.moneda='VAC',$tipovac*cu.total_deuda,cu.total_deuda)) AS 'Deuda Soles',cu.producto,
					IF(detcu.dias_mora>0 and detcu.dias_mora<=30,'TRAMO_1',IF(detcu.dias_mora>30 AND detcu.dias_mora<=60,'TRAMO_2','TRAMO_3')) AS 'tramo_dia_hdec',
					CONCAT('=\"',cu.numero_cuenta,'\"') AS 'contrato',cu.tramo_cuenta,detcu.dias_mora as 'diavenc',clicar.dato1 as 'agencia',
					cu.dato9 as 'territorio',cu.dato10 as 'oficina',cu.dato11 as 'oficina2',cu.dato1 as 'Fproceso',cu.dato3 as 'nom_subprod',cu.dato4 as 'fincumpli',
					cu.dato5 as 'provision',cu.dato8 as 'marca',
					(SELECT CONCAT(usu.paterno, ' ',usu.materno,' ',usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'Asignado',
					(SELECT usu.codigo FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'codigo_user',
					DATE(clicar.fecha_creacion) AS 'fecha_creacion'
					FROM ca_cliente_cartera clicar
					INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
					INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
					INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
					WHERE clicar.idcartera=$cartera and clicar.estado=1 AND cli.idservicio=$servicio and cu.idcartera=$cartera and cu.estado=1";

	$pr_detalle=$connection->prepare($sql_detalle);
	$index_inicio=0;
	$fila_inicio=2;
	$i=0;
	if($pr_detalle->execute()){
		while($data_detalle=$pr_detalle->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_detalle as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$index_inicio].'1',$key);
					$index_inicio=$index_inicio+1;
				}
			}
			$i++;
			$index_inicio=0;
			foreach ($data_detalle as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($abc[$index_inicio].''.$fila_inicio,$value);
				$index_inicio++;
			}
			$index_inicio=0;
			$fila_inicio++;
		}
	}

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="REPORTE_RESUMEN_DISTRIBUCION.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 

?>