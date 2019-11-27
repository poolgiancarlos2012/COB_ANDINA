<?php

    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
    date_default_timezone_set('America/Lima');

    $Servicio = $_GET['Servicio'];
    $idCartera = $_GET['Cartera'];
    $fecha_Unica = $_GET['FechaUnica'];

    $factoryConnection= FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();

    require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';


    $xls = new PHPExcel();
    

    $sql_count="    SELECT
                    COUNT(*) AS 'COUNT'
                    FROM ca_historico_cobranza_andina
                    WHERE
                    DATE(fecha_carga)='$fecha_Unica';";

    $pr_count=$connection->prepare($sql_count);
    $pr_count->execute();
    $ar_count=$pr_count->fetch(PDO::FETCH_ASSOC);

    if($ar_count['COUNT']>0){

    $xls->setActiveSheetIndex(0)->setTitle("STATUS COLOCACION");

    $sql_status_colocacion="    SELECT
                                    TRIM(t.vend_actual) AS 'vend_actual',
									ROUND(CAST(t.cero AS DECIMAL(10,2))) AS 'cero',
									ROUND(CAST(t.uno AS DECIMAL(10,2))) AS 'uno',
									ROUND(CAST(t.dos AS DECIMAL(10,2))) AS 'dos',
									ROUND(CAST(t.tres AS DECIMAL(10,2))) AS 'tres',
									ROUND(CAST(t.cuatro AS DECIMAL(10,2))) AS 'cuatro',
									ROUND(CAST(t.cinco AS DECIMAL(10,2))) AS 'cinco',
									ROUND(CAST(t.seis AS DECIMAL(10,2))) AS 'seis',
									ROUND(CAST(t.siete AS DECIMAL(10,2))) AS 'siete',
									ROUND(CAST(t.ocho AS DECIMAL(10,2))) AS 'ocho',
									ROUND(CAST(t.nueve AS DECIMAL(10,2))) AS 'nueve',
									ROUND(CAST(t.total_general AS DECIMAL(10,2))) AS 'total_general',
									ROUND(CAST(t.total_general AS DECIMAL(10,2)))-ROUND(CAST(t.nueve AS DECIMAL(10,2))) AS 'calculado',
                                    (t.uno+t.dos+t.tres+t.cuatro+t.cinco+t.seis)*100/(t.uno+t.dos+t.tres+t.cuatro+t.cinco+t.seis+t.siete+t.cero+t.ocho) AS 'order'
                                FROM
                                (
                                SELECT
                                    TRIM(vend_actual) AS vend_actual,
                                    SUM(IF(TRIM(rango_vcto)='0-(01 a 08 dias)',TRIM(total_convertido_a_dolares),0))    AS 'cero',
                                    SUM(IF(TRIM(rango_vcto)='1-(09 a 30 dias)',TRIM(total_convertido_a_dolares),0))    AS 'uno',
                                    SUM(IF(TRIM(rango_vcto)='2-(31 a 60 dias)',TRIM(total_convertido_a_dolares),0))    AS 'dos',
                                    SUM(IF(TRIM(rango_vcto)='3-(61 a 90 dias)',TRIM(total_convertido_a_dolares),0))    AS 'tres',
                                    SUM(IF(TRIM(rango_vcto)='4-(91 a 120 dias)',TRIM(total_convertido_a_dolares),0))   AS 'cuatro',
                                    SUM(IF(TRIM(rango_vcto)='5-(121 a 360 dias)',TRIM(total_convertido_a_dolares),0))  AS 'cinco',
                                    SUM(IF(TRIM(rango_vcto)='6-(mas de 360 dias)',TRIM(total_convertido_a_dolares),0)) AS 'seis',
                                    SUM(IF(TRIM(rango_vcto)='7-(Cob. Judicial)',TRIM(total_convertido_a_dolares),0))   AS 'siete',
                                    SUM(IF(TRIM(rango_vcto)='8-(Vigente)',TRIM(total_convertido_a_dolares),0))         AS 'ocho',
                                    SUM(IF(TRIM(rango_vcto)='9-(Saldo a favor)',TRIM(total_convertido_a_dolares),0))   AS 'nueve',
                                    SUM(TRIM(total_convertido_a_dolares))                                        AS 'total_general'
                                FROM ca_historico_cobranza_andina
                                WHERE
                                DATE(fecha_carga)='$fecha_Unica'
                                GROUP BY TRIM(vend_actual)
                                ) t
                                ORDER BY 14 DESC";

    //echo $sql_status_colocacion;
    //exit();

    $pr_status_colocacion=$connection->prepare($sql_status_colocacion);
    $pr_status_colocacion->execute();

    // echo count($ar_status);

    // if(count($ar_status)>0){

        $columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
        
        $font = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 8,
            'name'  => 'Arial'
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

        $xls->getActiveSheet()->getStyle('A2:H2')->applyFromArray($fondo_celeste);
        $xls->getActiveSheet()->getStyle('A3:H3')->applyFromArray($fondo_celeste);
        $xls->getActiveSheet()->getStyle('A4')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('B4')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('B5:H5')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('J5:M5')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('O5:Q5')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('S5:V5')->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('X5:Z5')->applyFromArray($fondo_celeste_claro);        

        $xls->getActiveSheet()->setCellValue('A2','%');
        $xls->getActiveSheet()->setCellValue('A3','%');

        $fil=5;
        $col=0;
        $xls->getActiveSheet()->mergeCells("A4:A5");
        $xls->getActiveSheet()->SetCellValue('A4',"CARTERA TOTAL US$");
        $xls->getActiveSheet()->getStyle("A4:A5")->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('A4:A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $xls->getActiveSheet()->getStyle('A4:A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $xls->getActiveSheet()->mergeCells("B4:H4");
        $xls->getActiveSheet()->SetCellValue('B4',"CARTERA EN MORA");
        $xls->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $xls->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $xls->getActiveSheet()->getStyle('B4:H4')->applyFromArray($border_thin);

        

        $xls->getActiveSheet()->mergeCells("J2:M2");
        $xls->getActiveSheet()->SetCellValue('J2',"GENERAL");
        $xls->getActiveSheet()->getStyle('J2:M2')->applyFromArray($font);
        $xls->getActiveSheet()->getStyle('J2:M2')->applyFromArray($border_mefium);
        $xls->getActiveSheet()->getStyle('J2:M2')->applyFromArray($fondo_amarillo);
        $xls->getActiveSheet()->getStyle('J2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $xls->getActiveSheet()->getStyle('J2:M2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $xls->getActiveSheet()->mergeCells("J3:M3");
        $xls->getActiveSheet()->SetCellValue('J3',"SIN GRUPO ANDINA");
        $xls->getActiveSheet()->getStyle('J3:M3')->applyFromArray($font);
        $xls->getActiveSheet()->getStyle('J3:M3')->applyFromArray($border_mefium);
        $xls->getActiveSheet()->getStyle('J3:M3')->applyFromArray($fondo_amarillo);
        $xls->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $xls->getActiveSheet()->getStyle('J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $xls->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "CARTERA ENTRE 1 Y 8 DIAS DE VENCIDA");    
        $xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "CARTERA ENTRE 9 Y 30 DIAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "CARTERA ENTRE 31 Y 60 DIAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "CARTERA ENTRE 61 Y 90 DIAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, "CARTERA ENTRE 91 Y 120 DIAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, "CARTERA ENTRE 121 Y 360 DIAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, "CARTERA CON MAS DE 360 DÍAS DE VENCIDA");
        $xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, " ");
        $xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, "CARTERA EN MORA DESDE DÍA 1");
        $xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, "Cobranza judicial");
        $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, "CARTERA VIGENTE / AUN NO VENCE");
        $xls->getActiveSheet()->SetCellValue($columna[$col+12].$fil, "CARTERA TOTAL");
        $xls->getActiveSheet()->SetCellValue($columna[$col+13].$fil, " ");
        $xls->getActiveSheet()->SetCellValue($columna[$col+14].$fil, "CARTERA EN MORA DESDE DÍA 1");
        $xls->getActiveSheet()->SetCellValue($columna[$col+15].$fil, "Cobranza judicial");
        $xls->getActiveSheet()->SetCellValue($columna[$col+16].$fil, "CARTERA VIGENTE / AUN NO VENCE");
        $xls->getActiveSheet()->SetCellValue($columna[$col+17].$fil, " ");
        $xls->getActiveSheet()->SetCellValue($columna[$col+18].$fil, "CARTERA EN MORA DESDE DÍA 9");
        $xls->getActiveSheet()->SetCellValue($columna[$col+19].$fil, "Cobranza judicial");
        $xls->getActiveSheet()->SetCellValue($columna[$col+20].$fil, "CARTERA VIGENTE / AUN NO VENCE + MORA 1-8");
        $xls->getActiveSheet()->SetCellValue($columna[$col+21].$fil, "CARTERA TOTAL");
        $xls->getActiveSheet()->SetCellValue($columna[$col+22].$fil, " ");
        $xls->getActiveSheet()->SetCellValue($columna[$col+23].$fil, "CARTERA EN MORA DESDE DÍA 9");
        $xls->getActiveSheet()->SetCellValue($columna[$col+24].$fil, "Cobranza judicial");
        $xls->getActiveSheet()->SetCellValue($columna[$col+25].$fil, "CARTERA VIGENTE / AUN NO VENCE + MORA 1-8");
        $xls->getActiveSheet()->SetCellValue($columna[$col+26].$fil, " ");

        $xls->getActiveSheet()->getColumnDimensionByColumn(8)->setAutoSize(false);
        $xls->getActiveSheet()->getColumnDimensionByColumn(13)->setAutoSize(false);
        $xls->getActiveSheet()->getColumnDimensionByColumn(17)->setAutoSize(false);
        $xls->getActiveSheet()->getColumnDimensionByColumn(22)->setAutoSize(false);

        $xls->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(1);
        $xls->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(1);
        $xls->getActiveSheet()->getColumnDimensionByColumn(17)->setWidth(1);
        $xls->getActiveSheet()->getColumnDimensionByColumn(22)->setWidth(1);


        $xls->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(22);
        $xls->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(15)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(16)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(18)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(19)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(20)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(21)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(23)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(24)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimensionByColumn(25)->setWidth(12);

        $xls->getActiveSheet()->getStyle('B5:Z5')->getAlignment()->setWrapText(true);
        $xls->getActiveSheet()->getStyle('B5:Z5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $xls->getActiveSheet()->getStyle('B5:Z5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $xls->getActiveSheet()->getStyle($columna[$col+1].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+2].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+3].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+5].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+6].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+7].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+10].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+11].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+12].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+14].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+15].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+16].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+18].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+19].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+20].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+21].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+23].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+24].$fil)->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle($columna[$col+25].$fil)->applyFromArray($border_thin);

        

        while( $datos_resumen=$pr_status_colocacion->fetch(PDO::FETCH_ASSOC) ) {
            $fil++;
            $xls->getActiveSheet()->SetCellValue($columna[$col].$fil, $datos_resumen["vend_actual"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, $datos_resumen["cero"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, $datos_resumen["uno"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, $datos_resumen["dos"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, $datos_resumen["tres"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+5].$fil, $datos_resumen["cuatro"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+6].$fil, $datos_resumen["cinco"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+7].$fil, $datos_resumen["seis"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+8].$fil, " ");
            $xls->getActiveSheet()->SetCellValue($columna[$col+9].$fil, '=SUM(B'.$fil.':H'.$fil.')');
            $xls->getActiveSheet()->SetCellValue($columna[$col+10].$fil, $datos_resumen["siete"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+11].$fil, $datos_resumen["ocho"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+12].$fil, $datos_resumen["calculado"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+13].$fil, " ");

            $suma_x_fila_1=$xls->getActiveSheet()->getCell('M'.$fil)->getValue();

            $calculado_1=$xls->getActiveSheet()->getCell('J'.$fil)->getCalculatedValue();
            $xls->getActiveSheet()->SetCellValue($columna[$col+14].$fil, "=".$calculado_1/$suma_x_fila_1);

            $calculado_2=$xls->getActiveSheet()->getCell('K'.$fil)->getCalculatedValue();
            $xls->getActiveSheet()->SetCellValue($columna[$col+15].$fil, "=".$calculado_2/$suma_x_fila_1);

            $calculado_3=$xls->getActiveSheet()->getCell('L'.$fil)->getCalculatedValue();
            $xls->getActiveSheet()->SetCellValue($columna[$col+16].$fil, "=".$calculado_3/$suma_x_fila_1);

            $xls->getActiveSheet()->SetCellValue($columna[$col+17].$fil, " ");

            $xls->getActiveSheet()->SetCellValue($columna[$col+18].$fil, '=SUM(C'.$fil.':H'.$fil.')');
            $xls->getActiveSheet()->SetCellValue($columna[$col+19].$fil, $datos_resumen["siete"]);
            $xls->getActiveSheet()->SetCellValue($columna[$col+20].$fil, "=SUM(B".$fil.",L".$fil.")");
            $xls->getActiveSheet()->SetCellValue($columna[$col+21].$fil, "=SUM(S".$fil.":U".$fil.")");

            $xls->getActiveSheet()->SetCellValue($columna[$col+22].$fil, " ");

            $valor_4=$xls->getActiveSheet()->getCell('S'.$fil)->getCalculatedValue();
            $valor_5=$xls->getActiveSheet()->getCell('T'.$fil)->getCalculatedValue();
            $valor_6=$xls->getActiveSheet()->getCell('U'.$fil)->getCalculatedValue();
            $valor_7=$xls->getActiveSheet()->getCell('V'.$fil)->getCalculatedValue();

            $xls->getActiveSheet()->SetCellValue($columna[$col+23].$fil, "=".$valor_4/$valor_7);
            $xls->getActiveSheet()->SetCellValue($columna[$col+24].$fil, "=".$valor_5/$valor_7);
            $xls->getActiveSheet()->SetCellValue($columna[$col+25].$fil, "=".$valor_6/$valor_7);

            // $xls->getActiveSheet()->getStyle($columna[$col+1].$fil)->getNumberFormat()->setFormatCode('[Blue][>=3000]$#,##0;[Red][<0]$#,##0;$#,##0');

            $xls->getActiveSheet()->getStyle($columna[$col+1].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+2].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+3].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+5].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+6].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+7].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+10].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+11].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+12].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+14].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $xls->getActiveSheet()->getStyle($columna[$col+15].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $xls->getActiveSheet()->getStyle($columna[$col+16].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $xls->getActiveSheet()->getStyle($columna[$col+18].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+19].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+20].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+21].$fil)->getNumberFormat()->setFormatCode('#,##0');
            $xls->getActiveSheet()->getStyle($columna[$col+23].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $xls->getActiveSheet()->getStyle($columna[$col+24].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $xls->getActiveSheet()->getStyle($columna[$col+25].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);

            $xls->getActiveSheet()->getStyle($columna[$col].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+1].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+2].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+3].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+5].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+6].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+7].$fil)->applyFromArray($border_thin);
            // $xls->getActiveSheet()->getStyle($columna[$col+8].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+10].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+11].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+12].$fil)->applyFromArray($border_thin);
            // $xls->getActiveSheet()->getStyle($columna[$col+13].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+14].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+15].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+16].$fil)->applyFromArray($border_thin);
            // $xls->getActiveSheet()->getStyle($columna[$col+17].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+18].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+19].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+20].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+21].$fil)->applyFromArray($border_thin);
            // $xls->getActiveSheet()->getStyle($columna[$col+22].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+23].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+24].$fil)->applyFromArray($border_thin);
            $xls->getActiveSheet()->getStyle($columna[$col+25].$fil)->applyFromArray($border_thin);

            $xls->getActiveSheet()->getStyle($columna[$col+9].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+10].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+11].$fil)->applyFromArray($fondo_verde_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+14].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+15].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+16].$fil)->applyFromArray($fondo_verde_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+18].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+19].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+20].$fil)->applyFromArray($fondo_verde_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+23].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+24].$fil)->applyFromArray($fondo_rojo_claro);
            $xls->getActiveSheet()->getStyle($columna[$col+25].$fil)->applyFromArray($fondo_verde_claro);

        }



        $xls->getActiveSheet()->setCellValue('A'.($fil+1),'TOTAL GENERAL');
        $xls->getActiveSheet()->setCellValue('B'.($fil+1),'=SUM(B6:B'.$fil.')');
        $xls->getActiveSheet()->setCellValue('C'.($fil+1),'=SUM(C6:C'.$fil.')');
        $xls->getActiveSheet()->setCellValue('D'.($fil+1),'=SUM(D6:D'.$fil.')');
        $xls->getActiveSheet()->setCellValue('E'.($fil+1),'=SUM(E6:E'.$fil.')');
        $xls->getActiveSheet()->setCellValue('F'.($fil+1),'=SUM(F6:F'.$fil.')');
        $xls->getActiveSheet()->setCellValue('G'.($fil+1),'=SUM(G6:G'.$fil.')');
        $xls->getActiveSheet()->setCellValue('H'.($fil+1),'=SUM(H6:H'.$fil.')');
        $xls->getActiveSheet()->setCellValue('J'.($fil+1),'=SUM(J6:J'.$fil.')');
        $xls->getActiveSheet()->setCellValue('K'.($fil+1),'=SUM(K6:K'.$fil.')');
        $xls->getActiveSheet()->setCellValue('L'.($fil+1),'=SUM(L6:L'.$fil.')');
        $xls->getActiveSheet()->setCellValue('M'.($fil+1),'=SUM(M6:M'.$fil.')');


        $xls->getActiveSheet()->getStyle('A'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('B'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('C'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('D'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('E'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('F'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('G'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('H'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('J'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('K'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('L'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('M'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('O'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('P'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('Q'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('S'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('T'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('U'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('V'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('X'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('Y'.($fil+1))->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('Z'.($fil+1))->applyFromArray($border_thin);

        $xls->getActiveSheet()->getStyle('A'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('B'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('C'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('D'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('E'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('F'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('G'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('H'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('J'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('K'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('L'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('M'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('O'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('P'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('Q'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('S'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('T'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('U'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('V'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('X'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('Y'.($fil+1))->applyFromArray($fondo_celeste_claro);
        $xls->getActiveSheet()->getStyle('Z'.($fil+1))->applyFromArray($fondo_celeste_claro);

        $tot_sum1=$xls->getActiveSheet()->getCell('M'.($fil+1))->getCalculatedValue();

        $tot_cal1=$xls->getActiveSheet()->getCell('J'.($fil+1))->getCalculatedValue();
        $xls->getActiveSheet()->setCellValue('O'.($fil+1),'='.$tot_cal1/$tot_sum1);

        $tot_cal2=$xls->getActiveSheet()->getCell('K'.($fil+1))->getCalculatedValue();
        $xls->getActiveSheet()->setCellValue('P'.($fil+1),'='.$tot_cal2/$tot_sum1);

        $tot_cal3=$xls->getActiveSheet()->getCell('L'.($fil+1))->getCalculatedValue();
        $xls->getActiveSheet()->setCellValue('Q'.($fil+1),'='.$tot_cal3/$tot_sum1);

        $xls->getActiveSheet()->setCellValue('S'.($fil+1),'=SUM(S6:S'.$fil.')');
        $xls->getActiveSheet()->setCellValue('T'.($fil+1),'=SUM(T6:T'.$fil.')');
        $xls->getActiveSheet()->setCellValue('U'.($fil+1),'=SUM(U6:U'.$fil.')');
        $xls->getActiveSheet()->setCellValue('V'.($fil+1),'=SUM(V6:V'.$fil.')');

        $calc_percent_1=$xls->getActiveSheet()->getCell('S'.($fil+1))->getCalculatedValue();
        $calc_percent_2=$xls->getActiveSheet()->getCell('T'.($fil+1))->getCalculatedValue();
        $calc_percent_3=$xls->getActiveSheet()->getCell('U'.($fil+1))->getCalculatedValue();
        $calc_percent_4=$xls->getActiveSheet()->getCell('V'.($fil+1))->getCalculatedValue();

        $xls->getActiveSheet()->setCellValue('X'.($fil+1),'='.$calc_percent_1/$calc_percent_4);
        $xls->getActiveSheet()->setCellValue('Y'.($fil+1),'='.$calc_percent_2/$calc_percent_4);
        $xls->getActiveSheet()->setCellValue('Z'.($fil+1),'='.$calc_percent_3/$calc_percent_4);

        $xls->getActiveSheet()->getStyle('B'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('C'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('D'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('E'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('F'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('G'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('H'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('J'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('K'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('M'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('O'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00); 
        $xls->getActiveSheet()->getStyle('P'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00); 
        $xls->getActiveSheet()->getStyle('Q'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('S'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('T'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('U'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('V'.($fil+1))->getNumberFormat()->setFormatCode('#,##0');
        $xls->getActiveSheet()->getStyle('X'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00); 
        $xls->getActiveSheet()->getStyle('Y'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00); 
        $xls->getActiveSheet()->getStyle('Z'.($fil+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);

        $tot_B=$xls->getActiveSheet()->getCell('B'.($fil+1))->getCalculatedValue();
        $tot_C=$xls->getActiveSheet()->getCell('C'.($fil+1))->getCalculatedValue();
        $tot_D=$xls->getActiveSheet()->getCell('D'.($fil+1))->getCalculatedValue();
        $tot_E=$xls->getActiveSheet()->getCell('E'.($fil+1))->getCalculatedValue();
        $tot_F=$xls->getActiveSheet()->getCell('F'.($fil+1))->getCalculatedValue();
        $tot_G=$xls->getActiveSheet()->getCell('G'.($fil+1))->getCalculatedValue();
        $tot_H=$xls->getActiveSheet()->getCell('H'.($fil+1))->getCalculatedValue();

        $m_28=$xls->getActiveSheet()->getCell('M'.($fil+1))->getCalculatedValue();

        $xls->getActiveSheet()->SetCellValue("B2", "=".$tot_B/$m_28);
        $xls->getActiveSheet()->SetCellValue("C2", "=".$tot_C/$m_28);
        $xls->getActiveSheet()->SetCellValue("D2", "=".$tot_D/$m_28);
        $xls->getActiveSheet()->SetCellValue("E2", "=".$tot_E/$m_28);
        $xls->getActiveSheet()->SetCellValue("F2", "=".$tot_F/$m_28);
        $xls->getActiveSheet()->SetCellValue("G2", "=".$tot_G/$m_28);
        $xls->getActiveSheet()->SetCellValue("H2", "=".$tot_H/$m_28);

        $xls->getActiveSheet()->getStyle('A2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('B2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('C2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('D2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('E2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('F2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('G2')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('H2')->applyFromArray($border_thin);

        $xls->getActiveSheet()->getStyle('B2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('D2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('E2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('G2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('H2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);

        $val_B=$xls->getActiveSheet()->getCell('B6')->getCalculatedValue();
        $val_C=$xls->getActiveSheet()->getCell('C6')->getCalculatedValue();
        $val_D=$xls->getActiveSheet()->getCell('D6')->getCalculatedValue();
        $val_E=$xls->getActiveSheet()->getCell('E6')->getCalculatedValue();
        $val_F=$xls->getActiveSheet()->getCell('F6')->getCalculatedValue();
        $val_G=$xls->getActiveSheet()->getCell('G6')->getCalculatedValue();
        $val_H=$xls->getActiveSheet()->getCell('H6')->getCalculatedValue();

        $xls->getActiveSheet()->SetCellValue("B3", "=".($tot_B-$val_B)/$m_28);
        $xls->getActiveSheet()->SetCellValue("C3", "=".($tot_C-$val_C)/$m_28);
        $xls->getActiveSheet()->SetCellValue("D3", "=".($tot_D-$val_D)/$m_28);
        $xls->getActiveSheet()->SetCellValue("E3", "=".($tot_E-$val_E)/$m_28);
        $xls->getActiveSheet()->SetCellValue("F3", "=".($tot_F-$val_F)/$m_28);
        $xls->getActiveSheet()->SetCellValue("G3", "=".($tot_G-$val_G)/$m_28);
        $xls->getActiveSheet()->SetCellValue("H3", "=".($tot_H-$val_H)/$m_28);

        $xls->getActiveSheet()->getStyle('A3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('B3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('C3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('D3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('E3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('F3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('G3')->applyFromArray($border_thin);
        $xls->getActiveSheet()->getStyle('H3')->applyFromArray($border_thin);

        $xls->getActiveSheet()->getStyle('B3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('C3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('D3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('E3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('F3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('G3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
        $xls->getActiveSheet()->getStyle('H3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);

    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Status_Colocacion_'.$fecha_Unica.'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
    $objWriter->save('php://output');
    exit();
?>