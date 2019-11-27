<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	
	$cartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
    $fecha_unica=$_REQUEST['FechaUnica'];

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

$style_negrita = array(
    'font' => array(
        'bold' => true,
    )
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
    $objPHPExcel->getActiveSheet()->setCellValue('A2','RESUMEN DE CARGA');
    $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));    
	$objPHPExcel->getActiveSheet()->mergecells('A2:C2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_alinear_centro);


	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray($style_fondo_rojo);
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray($style_solido_rojo);
	$objPHPExcel->getActiveSheet()->setCellValue('A5','DESCRIPCION');
	$objPHPExcel->getActiveSheet()->setCellValue('B5','CANTIDAD');
    $objPHPExcel->getActiveSheet()->setCellValue('C5','DEUDA');    
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(8);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);      

	$objPHPExcel->getActiveSheet()->setCellValue('A6','TOTAL CLIENTES NUEVOS');
	$objPHPExcel->getActiveSheet()->setCellValue('A7','TOTAL CONTRATOS NUEVOS');
    $objPHPExcel->getActiveSheet()->setCellValue('A8','TOTAL CLIENTES RETIRADOS');    
    $objPHPExcel->getActiveSheet()->setCellValue('A9','TOTAL CUENTAS RETIRADOS');        
    $objPHPExcel->getActiveSheet()->setCellValue('A10','TOTAL CLIENTES ACTUAL');        
    $objPHPExcel->getActiveSheet()->setCellValue('A11','TOTAL CUENTAS ACTUAL');        
    $objPHPExcel->getActiveSheet()->setCellValue('A12','TOTAL CLIENTES ANTERIOR');        
    $objPHPExcel->getActiveSheet()->setCellValue('A13','TOTAL CUENTAS ANTERIOR');            
	$objPHPExcel->getActiveSheet()->getStyle('A6:C13')->applyFromArray($style_solido_rojo);	

    $objPHPExcel->getActiveSheet()->mergecells('A1:C1');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style_alinear_centro);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style_negrita);
    $objPHPExcel->getActiveSheet()->setCellValue('A3','Fecha Proceso');
    $objPHPExcel->getActiveSheet()->setCellValue('B3',$fecha_unica);

	$sql_fecha="select DISTINCT DATE(dateSys) AS 'fecha' FROM ca_historial his
				inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
				where clicar.idcartera in ($cartera) and date(his.dateSys)<='$fecha_unica'
				ORDER BY DATE(dateSys) DESC limit 2";	

	$arrayfecha=array();
	$prfecha=$connection->prepare($sql_fecha);
	if($prfecha->execute()){
		while($datafecha=$prfecha->fetch(PDO::FETCH_ASSOC)){
			array_push($arrayfecha, $datafecha['fecha']);
		}
	}


    $sql_fecha_anterior="select his.idcliente_cartera,his.idcuenta from ca_historial his
                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                            where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[1]."'";
    $pr_anterior=$connection->prepare($sql_fecha_anterior);
    $pr_anterior->execute();

    $idclicar_anterior="";
    $idcuenta_anterior="";
    while($row_anterior=$pr_anterior->fetch(PDO::FETCH_ASSOC)){
        $idclicar_anterior.=$row_anterior['idcliente_cartera'].",";
        $idcuenta_anterior.=$row_anterior['idcuenta'].",";
    }
    $idclicar_anterior=substr($idclicar_anterior,0,strlen($idclicar_anterior)-1);
    $idcuenta_anterior=substr($idcuenta_anterior,0,strlen($idcuenta_anterior)-1);    

    if($idclicar_anterior==''){echo('No se encontraron registros');exit;}

    $sql_fecha_actual="select his.idcliente_cartera,his.idcuenta from ca_historial his
                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                            where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
    $pr_actual=$connection->prepare($sql_fecha_actual);
    $pr_actual->execute();

    $idclicar_actual="";
    $idcuenta_actual="";
    while($row_actual=$pr_actual->fetch(PDO::FETCH_ASSOC)){
        $idclicar_actual.=$row_actual['idcliente_cartera'].",";
        $idcuenta_actual.=$row_actual['idcuenta'].",";
    }
    $idclicar_actual=substr($idclicar_actual,0,strlen($idclicar_actual)-1);
    $idcuenta_actual=substr($idcuenta_actual,0,strlen($idcuenta_actual)-1);        
    if($idclicar_actual==''){echo('No se encontraron registros');exit;}    

    /*CALCULO DE CANTIDADA DE CLIENTES Y CONTRATOS*/

    $sqlcantidadactual="SELECT COUNT(DISTINCT idcliente_cartera) AS CLIENTE,COUNT(DISTINCT idcuenta) AS CUENTA FROM ca_cuenta where idcartera in ($cartera) and idcliente_cartera in ($idclicar_actual) and idcuenta IN ($idcuenta_actual)";
    $prcantidadactual=$connection->prepare($sqlcantidadactual);
    $prcantidadactual->execute();
    $datacantidadactual=$prcantidadactual->fetchAll(PDO::FETCH_ASSOC);
    $objPHPExcel->getActiveSheet()->setCellValue('B10',$datacantidadactual[0]['CLIENTE']);
    $objPHPExcel->getActiveSheet()->setCellValue('B11',$datacantidadactual[0]['CUENTA']);

    $sqlcantidadanterior="SELECT COUNT(DISTINCT idcliente_cartera) AS CLIENTE,COUNT(DISTINCT idcuenta) AS CUENTA FROM ca_cuenta where idcartera in ($cartera) and idcliente_cartera in ($idclicar_anterior) and idcuenta IN ($idcuenta_anterior)";
    $prcantidadanterior=$connection->prepare($sqlcantidadanterior);
    $prcantidadanterior->execute();
    $datacantidadanterior=$prcantidadanterior->fetchAll(PDO::FETCH_ASSOC);
    $objPHPExcel->getActiveSheet()->setCellValue('B12',$datacantidadanterior[0]['CLIENTE']);
    $objPHPExcel->getActiveSheet()->setCellValue('B13',$datacantidadanterior[0]['CUENTA']);    

	/*CANTIDAD DE CLIENTES NUEVOS*/

    if($arrayfecha[0]==$fecha_unica){
    	if(count($arrayfecha)>1){
    		$sql_clientes_nuevos="select COUNT(DISTINCT his2.idcliente_cartera) AS 'CANTIDAD' from ca_historial his2
    							inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
    							where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[0]."' and his2.idcliente_cartera not in (
                                $idclicar_anterior)";
    	}else{
    		$sql_clientes_nuevos="select count(DISTINCT his.idcliente_cartera) AS 'CANTIDAD' FROM ca_historial his
    								inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
    								where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
    	}

    	$prclientenuevos=$connection->prepare($sql_clientes_nuevos);
    	$prclientenuevos->execute();
    	$dataclientenuevos=$prclientenuevos->fetchAll(PDO::FETCH_ASSOC);
    	$objPHPExcel->getActiveSheet()->setCellValue('B6',$dataclientenuevos[0]['CANTIDAD']);

    	/*CANTIDAD DE CUENTAS NUEVOS*/

    	if(count($arrayfecha)>1){
    		$sql_cuentas_nuevos="select COUNT(DISTINCT his2.idcuenta) AS 'CANTIDAD' from ca_historial his2
    							inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
    							where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[0]."' and his2.idcuenta not in (
                                $idcuenta_anterior)";
    	}else{
    		$sql_cuentas_nuevos="select count(DISTINCT his.idcuenta) AS 'CANTIDAD' FROM ca_historial his
    								inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
    								where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
    	}	

    	$prcuentasnuevos=$connection->prepare($sql_cuentas_nuevos);
    	$prcuentasnuevos->execute();
    	$datacuentasnuevos=$prcuentasnuevos->fetchAll(PDO::FETCH_ASSOC);
    	$objPHPExcel->getActiveSheet()->setCellValue('B7',$datacuentasnuevos[0]['CANTIDAD']);

        /*CANTIDAD DE CLIENTE RETIRADOS*/

        if(count($arrayfecha)>1){
            $sql_clientes_retirados="select COUNT(DISTINCT his2.idcliente_cartera) AS 'CANTIDAD' from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[1]."' and his2.idcliente_cartera not in (
                                $idclicar_actual)";
        }else{
            $sql_clientes_retirados="select DISTINCT '0' AS 'CANTIDAD' FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."' limit 1";
        }

        $prclienteretirados=$connection->prepare($sql_clientes_retirados);
        $prclienteretirados->execute();
        $dataclienteretirados=$prclienteretirados->fetchAll(PDO::FETCH_ASSOC);
        $objPHPExcel->getActiveSheet()->setCellValue('B8',$dataclienteretirados[0]['CANTIDAD']); 

        /*CANTIDAD DE CUENTAS RETIRADOS*/

        if(count($arrayfecha)>1){
            $sql_cuentas_retirados="select COUNT(DISTINCT his2.idcuenta) AS 'CANTIDAD' from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[1]."' and his2.idcuenta not in (
                                $idcuenta_actual)";
        }else{
            $sql_cuentas_retirados="select count(DISTINCT his.idcuenta) AS 'CANTIDAD' FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
        }   

        $prcuentasretirados=$connection->prepare($sql_cuentas_retirados);
        $prcuentasretirados->execute();
        $datacuentasretirados=$prcuentasretirados->fetchAll(PDO::FETCH_ASSOC);
        $objPHPExcel->getActiveSheet()->setCellValue('B9',$datacuentasretirados[0]['CANTIDAD']);

    }


/*HOJA2*/
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('CLIENTES');    

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);          

    if($arrayfecha[0]==$fecha_unica){
        if(count($arrayfecha)>1){
            $sql_clientes_nuevos_detalle="select DISTINCT his2.CodCent,his2.Nombre,(SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE codigo_cliente=clicar2.codigo_cliente limit 1) AS 'TELEFONO' from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[0]."' and his2.idcliente_cartera not in (
                                $idclicar_anterior)";
        }else{
            $sql_clientes_nuevos_detalle="select DISTINCT his.CodCent,his.Nombre,(SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE codigo_cliente=clicar.codigo_cliente limit 1) AS 'TELEFONO' FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
        }

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
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($style_fondo_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($style_solido_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(8);

        $prclientenuevosdetalle=$connection->prepare($sql_clientes_nuevos_detalle);
        if($prclientenuevosdetalle->execute()){
            while($dataclientenuevosdetalle=$prclientenuevosdetalle->fetch(PDO::FETCH_ASSOC)){
                if($i==0){
                    foreach ($dataclientenuevosdetalle as $key => $value) {
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
                        $cont++;
                    }
                }
                $i++;
                $cont=0;
                foreach($dataclientenuevosdetalle as $key => $value){
                    if($key=='Codcent'){
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,'="'.utf8_decode($value).'"');                        
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));                        
                    }
                    $cont++;
                }
                $filainicio++;
            }
        }
    }

/*HOJA3*/
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->setTitle('CONTRATOS');


    if($arrayfecha[0]==$fecha_unica){
        if(count($arrayfecha)>1){
            $sql_cuentas_nuevos_detalle="select DISTINCT his2.CodCent,CONCAT('=\"',his2.contrato,'\"') as contrato,his2.producto,his2.nom_subprod,his2.divisa,his2.saldohoy from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[0]."' and his2.idcuenta not in (
                                $idcuenta_anterior)";
        }else{
            $sql_cuentas_nuevos_detalle="select DISTINCT his.Codcent,CONCAT('=\"',his.contrato,'\"') as contrato,his.producto,his.nom_subprod,his.divisa,his.saldohoy FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
        }   
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_fondo_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_solido_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(8);

        $prcuentasnuevosdetalle=$connection->prepare($sql_cuentas_nuevos_detalle);
        if($prcuentasnuevosdetalle->execute()){
            while($datacuentanuevosdetalle=$prcuentasnuevosdetalle->fetch(PDO::FETCH_ASSOC)){
                if($i==0){
                    foreach ($datacuentanuevosdetalle as $key => $value) {
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
                        $cont++;
                    }
                }
                $i++;
                $cont=0;
                foreach($datacuentanuevosdetalle as $key => $value){
                    if($key=='CUENTA' || $key=='Codcent'){
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,'="'.utf8_decode($value).'"');                        
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));                        
                    }

                    $cont++;
                }
                $filainicio++;
            }
        }

    }
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue()->setCellValue('C6','=SUM(CONTRATOS!F2:F'.($filainicio-1).')');
    $objPHPExcel->getActiveSheet()->setCellValue()->setCellValue('C7','=SUM(CONTRATOS!F2:F'.($filainicio-1).')');    


    /*HOJA4*/
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(3);
    $objPHPExcel->getActiveSheet()->setTitle('CLIENTES RETIRADOS'); 

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);          


    if($arrayfecha[0]==$fecha_unica){
        if(count($arrayfecha)>1){
            $sql_clientes_retirados_detalle="select DISTINCT his2.CodCent,his2.Nombre,(SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE codigo_cliente=clicar2.codigo_cliente limit 1) AS 'TELEFONO' from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[1]."' and his2.idcliente_cartera not in (
                                $idclicar_actual)";

        }else{
            $sql_clientes_retirados_detalle="select DISTINCT his.CodCent,his.Nombre,(SELECT IFNULL(numero_act,numero) FROM ca_telefono WHERE codigo_cliente=clicar.codigo_cliente limit 1) AS 'TELEFONO' FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
        }

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
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($style_fondo_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($style_solido_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(8);

        $prclienteretiradosdetalle=$connection->prepare($sql_clientes_retirados_detalle);
        if($prclienteretiradosdetalle->execute()){
            while($dataclienteretiradosdetalle=$prclienteretiradosdetalle->fetch(PDO::FETCH_ASSOC)){
                if($i==0){
                    foreach ($dataclienteretiradosdetalle as $key => $value) {
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
                        $cont++;
                    }
                }
                $i++;
                $cont=0;
                foreach($dataclienteretiradosdetalle as $key => $value){
                    if($key=='CodCent'){
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,'="'.utf8_decode($value).'"');                        
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));                        
                    }
                    $cont++;
                }
                $filainicio++;
            }
        }
    }      

    /*HOJA5*/ 
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(4);
    $objPHPExcel->getActiveSheet()->setTitle('CONTRATOS RETIRADAS');


    if($arrayfecha[0]==$fecha_unica){
        if(count($arrayfecha)>1){
            $sql_cuentas_retiradas_detalle="select DISTINCT his2.Codcent,CONCAT('=\"',his2.contrato,'\"') as contrato,his2.producto,his2.nom_subprod,his2.divisa,his2.saldohoy from ca_historial his2
                                inner join ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=his2.idcliente_cartera
                                where clicar2.idcartera in ($cartera) and date(his2.dateSys)='".$arrayfecha[1]."' and his2.idcuenta not in (
                                $idcuenta_actual)";
        }else{
            $sql_cuentas_retiradas_detalle="select DISTINCT his.Codcent,his.contrato,his.producto,his.nom_subprod,his.divisa,his.saldohoy FROM ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera=his.idcliente_cartera
                                    where clicar.idcartera in ($cartera) and date(his.dateSys)='".$arrayfecha[0]."'";
        }   
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));    
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_fondo_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_solido_rojo);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(8);

        $prcuentasretiradasdetalle=$connection->prepare($sql_cuentas_retiradas_detalle);
        if($prcuentasretiradasdetalle->execute()){
            while($datacuentaretiradasdetalle=$prcuentasretiradasdetalle->fetch(PDO::FETCH_ASSOC)){
                if($i==0){
                    foreach ($datacuentaretiradasdetalle as $key => $value) {
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].'1',$key);
                        $cont++;
                    }
                }
                $i++;
                $cont=0;
                foreach($datacuentaretiradasdetalle as $key => $value){
                    if($key=='CUENTA' || $key=='Codcent'){
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,'="'.utf8_decode($value).'"');                        
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValue($abc[$cont].''.$filainicio,utf8_decode($value));                        
                    }

                    $cont++;
                }
                $filainicio++;
            }
        }

    }    

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue()->setCellValue('C8',"=SUM('CONTRATOS RETIRADAS'!F2:F".($filainicio-1).")");
    $objPHPExcel->getActiveSheet()->setCellValue()->setCellValue('C9',"=SUM('CONTRATOS RETIRADAS'!F2:F".($filainicio-1).")");        

    $sql_cartera="SELECT nombre_cartera FROM ca_cartera WHERE idcartera in ($cartera)";
    $prcartera=$connection->prepare($sql_cartera);
    if($prcartera->execute()){
        while($datacartera=$prcartera->fetch(PDO::FETCH_ASSOC)){
            $objPHPExcel->getActiveSheet()->setCellValue('A1',$datacartera['nombre_cartera']);
        }
    }


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="RESUMEN_GESTION.xls"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');         

?>