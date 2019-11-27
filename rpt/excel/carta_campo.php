<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
	$gestor_campo=$_REQUEST['gestorcampo'];
	$tipo_cambio=$_REQUEST['tipocambio'];
	$distritos=$_REQUEST['distritos'];
	$fproceso=$_REQUEST['fproceso'];
	$tipo_vac=$_REQUEST['tipovac'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';   

    $objPHPExcel=new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Carta Campo');


	$sqlclientecuentas="select idcliente_cartera,codigo_cliente from ca_cuenta where idcartera=$cartera and estado=1 GROUP BY idcliente_cartera having count(*)>8";
	$pr_cliente_cuentas=$connection->prepare($sqlclientecuentas);
	$nocliente=array();
	if($pr_cliente_cuentas->execute()){
		while($data_cliente_cuentas=$pr_cliente_cuentas->fetch(PDO::FETCH_ASSOC)){
			array_push($nocliente, $data_cliente_cuentas['codigo_cliente']);
		}
	}


	$wherecadena="";
	if(count($nocliente)>0){
		$wherecadena=" and clicar.codigo_cliente not in (".implode(',',$nocliente).")";
	}


    $sql_gestor="select usu.codigo,CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) as gestor from ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=$gestor_campo ";
    $pr_gestor=$connection->prepare($sql_gestor);

    $codigogestor='';
    $nombregestor='';
    if($pr_gestor->execute()){
    	while($data_gestor=$pr_gestor->fetch(PDO::FETCH_ASSOC)){
    		foreach ($data_gestor as $key => $value) {
    			if($key=='codigo'){
    				$codigogestor=$value;
    			}else{
    				$nombregestor=$value;
    			}
    		}
    	}
    }

    $sql="SET SESSION group_concat_max_len=6000";
	$pr=$connection->prepare($sql);
	$pr->execute();

    $sql_carta="select '' as Cargo,'' as Orden,'' as Nro,'".$codigogestor."' as cod, cu.dato1 as fproceso,clicar.dato1 as agencia,clicar.dato1 as gestor,cu.dato9 as territorio,
				'HDEC' as oficina,count(*) as Tot_Doc,'u/c' as 'u/c',CONCAT('=\"',clicar.codigo_cliente,'\"') as codcent,SUM(case cu.moneda when 'USD' then $tipo_cambio*cu.total_deuda when 'VAC' then $tipo_vac*cu.total_deuda else cu.total_deuda end) AS 'deudat',CONCAT('=\"',cli.numero_documento,'\"') AS 'NUMERO_DOCUMENTO',
				MAX(CAST(detcu.dias_mora AS SIGNED)) AS diasvencfinal,clicar.dato5 as tpErsona,CONCAT(IFNULL(cli.paterno,''), ' ',IFNULL(cli.materno,''),' ',IFNULL(cli.nombre,'')) as nombre,
					(select direccion from ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 LIMIT 1) as direccion,
				(select CONCAT('=\"',numero,'\"') as numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 LIMIT 1) as fono1,
				clicar.dato4 as dist_Prov,IFNULL((select zo.zona from ca_zona_distrito zo where zo.distrito=clicar.dato4),'') as Zona,'' as Referencia,
				(select direccion from ca_direccion where idcliente_cartera =clicar.idcliente_cartera and idtipo_referencia=12 limit 1) as 'direccion_alterno',
				(select direccion from ca_direccion where idcliente_cartera =clicar.idcliente_cartera and idtipo_referencia=11 ORDER BY iddireccion DESC limit 1) as 'ultima_direccion_hipotecario',
				(select CONCAT('=\"',numero,'\"') as numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=1 LIMIT 1) as fono2,
				(select CONCAT('=\"',numero,'\"') as numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=4 LIMIT 1) as fono3,
				(select CONCAT('=\"',numero,'\"') as numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=5 LIMIT 1) as fono4,
				(select CONCAT('=\"',numero,'\"') as numero from ca_telefono where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=6 LIMIT 1) as fono5,
				cu.dato10 as Nombre_Oficina,
				GROUP_CONCAT(CONCAT('=\"',cu.numero_cuenta,'\"'),'##',cu.producto,'##',cu.dato3,'##',case cu.moneda when 'USD' then '$' else 'S/.' end,'##',cu.total_deuda,'##', case cu.moneda when 'USD' then $tipo_cambio*cu.total_deuda when 'VAC' then $tipo_vac*cu.total_deuda else cu.total_deuda end,'##','Dias Venc','##',detcu.dias_mora,'##',if(detcu.dias_mora>=61,'TRAMO 3',IF(detcu.dias_mora>=31,'TRAMO 2','TRAMO 1')) ,'##',cu.dato4,'##',cu.dato8,'##', 
				IF((cu.dato8 LIKE '%10%')>0 or (cu.dato8 LIKE '%20%')>0,'10 Y 20','DEMAS'),'##',IF((cu.dato8 LIKE '%VIP%')>0,'VIP','NO VIP'),'##',
						IF(detcu.dias_mora>=2 and detcu.dias_mora<=19 and (cu.dato8 LIKE '%10%' or cu.dato8 LIKE '%20%') and cu.dato8 LIKE '%VIP%','V1',
						IF(detcu.dias_mora>=20 and detcu.dias_mora<=29 and (cu.dato8 LIKE '%10%' or cu.dato8 LIKE '%20%') and cu.dato8 LIKE '%VIP%','V2',
						IF(detcu.dias_mora>=9 and detcu.dias_mora<=19 and (cu.dato8 LIKE '%10%' or cu.dato8 LIKE '%20%') and cu.dato8 NOT LIKE '%VIP%','V3',
						IF(detcu.dias_mora>=1 and detcu.dias_mora<=29 or (cu.dato8 LIKE '%10%' and cu.dato8 LIKE '%20%' and cu.dato8 NOT LIKE '%VIP%'),'NO CARTAS',
						IF(detcu.dias_mora>=30 and detcu.dias_mora<=59 and cu.dato8 LIKE '%VIP%','V4',
						IF(detcu.dias_mora>=30 and detcu.dias_mora<=59 and cu.dato8 NOT LIKE '%VIP%','V5',
						IF(detcu.dias_mora>=60 and cu.dato8 LIKE '%VIP%','V6',
						IF(detcu.dias_mora>=60 and cu.dato8 NOT LIKE '%VIP%','V7','NO DEFINIDO')))))))),'##','Nro','##','por ','##','vencido desde el ','##','con Contrato Nro. ','##',',  con ','##','días de impago por el importe de ','##',',  liquidado al ' ORDER BY CAST(detcu.dias_mora AS SIGNED) DESC SEPARATOR '@@') as contrato25,
				'".$nombregestor."' as nomb_usua,'714-4222' as ANEXO,
				(select date(fecha) from ca_llamada where idllamada=clicar.id_ultima_llamada) as fcall,
				replace(replace(REPLACE(Replace(Replace(Replace((select observacion from ca_llamada where idllamada=clicar.id_ultima_llamada),'|',''),char(10),''),char(13),''),CHAR(9),''),char(8),''),'\"','') as 'call',
				(select tel.numero from ca_llamada lla inner join ca_telefono tel on tel.idtelefono=lla.idtelefono where lla.idllamada=clicar.id_ultima_llamada) as Telefono,
				(select carfin.nombre from ca_llamada lla inner join ca_final fin on fin.idfinal=lla.idfinal inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
				where lla.idllamada=clicar.id_ultima_llamada) as status,(select CONCAT(usu.paterno,' ',usu.materno,' ',usu.nombre) from ca_usuario usu inner join ca_usuario_servicio ususer on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) as 'asignado_a',
				clicar.idcliente_cartera as 'ID_Cliente',cu.ul_fcpg as 'cp_call','' as 'dir_hipoteca','' as 'Prioridad','' as 'Op',DATE(clicar.fecha_creacion) AS 'INGRESO_CLIENTE'
				from ca_cliente_cartera clicar
				inner join ca_cliente cli on cli.idcliente=clicar.idcliente
				inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
				inner join ca_detalle_cuenta detcu on detcu.idcuenta = cu.idcuenta
				where clicar.idcartera=$cartera and clicar.estado=1 and cu.idcartera=$cartera and cu.retirado=0 and cu.dato1 in ($fproceso) and clicar.dato4 in ($distritos) $wherecadena
				group by clicar.idcliente_cartera";

				//echo $sql_carta;
				//exit();
	$pr_carta=$connection->prepare($sql_carta);
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


	$objPHPExcel->getActiveSheet()->getStyle('A1:GO1')->getFont()->setSize(12)->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:GO1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_color::COLOR_BLACK));	
	$objPHPExcel->getActiveSheet()->getStyle('A1:GO1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	if($pr_carta->execute()){
		while($data_carta=$pr_carta->fetch(PDO::FETCH_ASSOC)){
			if($i==0){
				foreach ($data_carta as $key => $value) {
					if($key=='contrato25'){
						for($j=1;$j<=8;$j++){
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','contrato'.$j);
						$cont++;
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','Producto'.$j);
						$cont++;
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','nom_subpro'.$j);
						$cont++;												
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','divisa'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','Saldohoy'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','DeudaT_convertida_soles'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','Dias_venc'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','diasvenc'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','tramo'.$j);
						$cont++;												
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','fincumpli'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','Marca'.$j);
						$cont++;						
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','GRUPO'.$j);
						$cont++;												
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','VIC'.$j);
						$cont++;												
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','CARTA'.$j);
						$cont++;											
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','N° ');
						$cont++;																		
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','por S/. ');
						$cont++;											
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','vencido desde e ');
						$cont++;																	
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','con Contrato Nro. ');
						$cont++;																	
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',', con ');
						$cont++;															
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1','días de impago por el importe de ');
						$cont++;																							
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',',  liquidado al …/09/2012.');
						$cont++;																													
						}

					}else{
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
						$cont=$cont+1;
					}
				}
			}		
			$i++;	
			$cont=0;
			$cant_contrato=array();
			$cant_detalle=array();
			foreach ($data_carta as $key => $value) {
				if($key=='contrato25'){
					$cant_contrato=explode("@@", $value);
					for($k=0;$k<count($cant_contrato);$k++){
						$cant_detalle=explode("##",$cant_contrato[$k]);
						for($z=0;$z<count($cant_detalle);$z++){
							$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$cant_detalle[$z]);													
							$cont++;
						}
					}
					//$cont=184;
					$cont=196;
				}else{
					$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);						
					$cont=$cont+1;
				}
			}
				$filainicio=$filainicio+1;							
		}
	}
//	echo($sql_carta);
//	exit();

	/*HOJA 2*/
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);

	$objPHPExcel->getActiveSheet()->setTitle('GRUPO POR DISTRITO');

	$objPHPExcel->getActiveSheet()->setCellValue('A1','N');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','Nombre');
	$objPHPExcel->getActiveSheet()->setCellValue('C1','Nro');
	$objPHPExcel->getActiveSheet()->setCellValue('D1','Direccion');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

	$objPHPExcel->getActiveSheet()->getStyle('A1:CA1')->getFont()->setSize(12)->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:CA1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_color::COLOR_BLACK));	    


	$array_grupo=array();
	$array_grupo=explode(",",$distritos);



	$filainicio=2;
	$cont=0;
	$total=1;
	$subtotal=1;
	for($k=0;$k<count($array_grupo);$k++){
		$sql_grupo="select '' as total,CONCAT(IFNULL(cli.paterno,''), ' ',IFNULL(cli.materno,''),' ',IFNULL(cli.nombre,'')) as Nombre,'' as nro,
					(select direccion from ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=3 LIMIT 1) as direccion,
					clicar.dato4 as dist_prov
					from ca_cliente_cartera clicar
					inner join ca_cliente cli on cli.idcliente=clicar.idcliente
					inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
					inner join ca_detalle_cuenta detcu on detcu.idcuenta = cu.idcuenta
					where clicar.idcartera=$cartera and clicar.estado=1 and cu.idcartera=$cartera and cu.retirado=0 and cu.dato1 in ($fproceso) and clicar.dato4 in (".$array_grupo[$k].")
					group by clicar.idcliente_cartera
					order by dist_prov";


		$pr_grupo=$connection->prepare($sql_grupo);
		$subtotal=1;
		if($pr_grupo->execute()){
			while($data_grupo=$pr_grupo->fetch(PDO::FETCH_ASSOC)){
				$cont=0;
				foreach ($data_grupo as $key => $value) {
					if($key=='total'){
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$total);						
					}else if($key=='nro'){
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$subtotal);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,$value);
					}
					$cont++;		
				}
				$filainicio++;
				$total++;
				$subtotal++;
			}

				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setSize(14)->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_color::COLOR_RED));

				$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'CUENTA '.$array_grupo[$k]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,($subtotal-1));				
				$filainicio++;
		}
	}
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setSize(14)->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$filainicio.':E'.$filainicio)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_color::COLOR_BLACK));	
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$filainicio,'CUENTA GENERAL');
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$filainicio,($total-1));				

	/*HOJA3*/
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(2);

	$objPHPExcel->getActiveSheet()->setTitle('MAS DE 8 CUENTAS');
	$objPHPExcel->getActiveSheet()->setCellValue('A1','CODIGO CLIENTE');

	$sqlclientecuentas="select idcliente_cartera,codigo_cliente from ca_cuenta where idcartera=$cartera and estado=1 GROUP BY idcliente_cartera having count(*)>8";
	$pr_cliente_cuentas=$connection->prepare($sqlclientecuentas);
	$filainicio=2;
	if($pr_cliente_cuentas->execute()){
		while($data_cliente_cuentas=$pr_cliente_cuentas->fetch(PDO::FETCH_ASSOC)){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$filainicio,$data_cliente_cuentas['codigo_cliente']);			
			$filainicio++;
		}
	}

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="REPORTE_CARTA_CAMPO.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 

?>