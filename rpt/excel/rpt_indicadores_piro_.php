<?php
   
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';

    require_once '../../phpincludes/phpexcel_2/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel_2/Classes/PHPExcel/IOFactory.php'; 
	
    $style_solido_azul=array(
                            'borders' => array(
                                'right'     => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                                        'color' => array('argb'=>'FF002060')
                                ),
                                'top'     => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                                        'color' => array('argb'=>'FF002060')                        
                                ) ,
                                'bottom'     => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                                        'color' => array('argb'=>'FF002060')                        
                                ) ,
                                'left'  =>array(
                                        'style'=>  PHPExcel_Style_Border::BORDER_THICK,
                                        'color' => array('argb'=>'FF002060')                        
                                )
                            ),
                            'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('argb' => 'FF002060')
                            ),
                            'font'  => array(
                                'bold'  => true,
                                'color' => array('argb' => 'FFFFFFFF'),
                                'size'  => 9,
                                'name'  => 'Calibri'
                            )
                        );
    $colorTitulos =array(
                            'font'  => array(
                                'bold'  => true,
                                'color' => array('argb' => 'FF000000'),
                                'size'  => 9,
                                'name'  => 'Calibri',
                                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
                            )
                        );

    $colorTotal = array(    
                            'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('argb' => 'FFD9D9D9')
                            ),
                            'font'  => array(
                                'bold'  => true,
                                'color' => array('argb' => 'FF000000'),
                                'size'  => 9,
                                'name'  => 'Calibri',
                                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
                            )
                        );
//ff + color =argb

    $idcartera = $_GET['Cartera'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	date_default_timezone_set('America/Lima');
    $fecha = date('Y_m_d_h_m_i').rand(5,1000);

    $sqlTMP = "CREATE TEMPORARY TABLE tmp_".$fecha." (
                agencia varchar(30),
                estado varchar(10),
                his_saldohoy varchar(30),
                Fproceso varchar(30),
                idcliente_cartera varchar(30),
                codcent varchar(30),
                Nombre varchar(100),
                nro_doc varchar(50),
                tipodoc varchar(50),
                contrato varchar(50),
                divisa varchar(30),
                saldohoy varchar(30),
                index(idcliente_cartera),
                index(codcent),
                index(contrato)
        )";
   
    $prTMP = $connection->prepare($sqlTMP);
    $prTMP->execute();

    $sqlTMP_INSERT = "INSERT INTO tmp_".$fecha." 
                        SELECT A.agencia, A.estado,his.saldohoy as his_saldohoy,his.Fproceso,his.idcliente_cartera,A.codcent,A.Nombre,A.nro_doc,A.tipodoc,A.contrato,A.divisa,A.saldohoy 
                        FROM 
                        (
                            SELECT  clicar.dato1 AS 'agencia', cu.estado, clicar.idcliente_cartera,concat('=\"',cli.codigo,'\"') AS 'codcent' , cli.nombre AS 'Nombre' ,
                             concat('=\"',cli.numero_documento,'\"') AS 'nro_doc' , cli.tipo_documento AS 'tipodoc' , concat('=\"',cu.numero_cuenta,'\"') AS 'contrato' ,
                             cu.moneda AS 'divisa' , cu.total_deuda AS 'saldohoy', cu.idcuenta

                                         FROM ca_cliente_cartera clicar
                                         INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                                         INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                                         INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente
                                         WHERE clicar.idcartera in (".$idcartera.") -- and cu.estado= 0  
                        )A inner join ca_historial his on his.idcliente_cartera = A.idcliente_cartera and his.idcuenta = A.idcuenta ";

    $prTMP_INSERT = $connection->prepare($sqlTMP_INSERT);
    $prTMP_INSERT ->execute();

    //Ahora lo bueno

    // 1ro  los distintos contratos pal for
    $sqlDistintos = "SELECT DISTINCT contrato FROM tmp_".$fecha."";
    $prDistintos = $connection->prepare($sqlDistintos);
    $prDistintos->execute();
    $arrayDistintos = $prDistintos->fetchAll(PDO::FETCH_ASSOC);
    
    // 2do todo
    $sqlReporte = "SELECT * FROM tmp_".$fecha." ";
    $prReporte = $connection->prepare($sqlReporte);
    $prReporte->execute();
    $arrayTodo = $prReporte->fetchAll(PDO::FETCH_ASSOC);

    $data=array();
    //detallado
  /*  $html = "<table><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    for($i=0;$i<count($arrayTodo);$i++){
        $html .= "<tr><td>".$arrayTodo[$i]['his_saldohoy']."</td><td>".$arrayTodo[$i]['Fproceso']."</td><td>".$arrayTodo[$i]['idcliente_cartera'].
        "</td><td>".$arrayTodo[$i]['codcent']."</td><td>".$arrayTodo[$i]['Nombre']."</td><td>".$arrayTodo[$i]['nro_doc']."</td>
        <td>".$arrayTodo[$i]['tipodoc']."
        </td><td>".$arrayTodo[$i]['contrato']."</td><td>".$arrayTodo[$i]['divisa']."</td><td>".$arrayTodo[$i]['saldohoy']."</td></tr>";
    }
    $html.="</table>";

    echo $html;
  */
    $filasImportar="";
   // $html = "<table><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    $objPHPExcel= new PHPExcel();

    //$objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Detalle');
    $objPHPExcel->getActiveSheet()->setCellValue('A1','AGENCIA');
    $objPHPExcel->getActiveSheet()->setCellValue('B1','FLAG_CUENTA');
    $objPHPExcel->getActiveSheet()->setCellValue('C1','HIS_SALDOHOY');
    $objPHPExcel->getActiveSheet()->setCellValue('D1','FPROCESO');
    $objPHPExcel->getActiveSheet()->setCellValue('E1','CODCENT');
    $objPHPExcel->getActiveSheet()->setCellValue('F1','NOMBRE');
    $objPHPExcel->getActiveSheet()->setCellValue('G1','NRO_DOC');
    $objPHPExcel->getActiveSheet()->setCellValue('H1','TIPO_DOC');
    $objPHPExcel->getActiveSheet()->setCellValue('I1','CONTRATO');
    $objPHPExcel->getActiveSheet()->setCellValue('J1','DIVISA');
    $objPHPExcel->getActiveSheet()->setCellValue('K1','SALDOHOY');
    $objPHPExcel->getActiveSheet()->setCellValue('L1','PAGO');
    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($style_solido_azul);
    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $CONT=1;
    for($i=0;$i<count($arrayTodo);$i++){
        // $acumulador=0;
        for($j=$i+1;$j<$i+2 && $j != count($arrayTodo);$j++){
            if( $arrayTodo[$i]['contrato'] == $arrayTodo[$j]['contrato'] ){

                $resta=0;
                if( $arrayTodo[$i]['his_saldohoy'] > $arrayTodo[$j]['his_saldohoy'] ){
                    $resta = $arrayTodo[$i]['his_saldohoy'] - $arrayTodo[$j]['his_saldohoy']  ;
                }
                /*
                $html .= "<tr><td>".$arrayTodo[$i]['his_saldohoy']."</td><td>".$arrayTodo[$i]['Fproceso']."</td><td>".$arrayTodo[$i]['idcliente_cartera'].
                    "</td><td>".$arrayTodo[$i]['codcent']."</td><td>".$arrayTodo[$i]['Nombre']."</td><td>".$arrayTodo[$i]['nro_doc']."</td>
                    <td>".$arrayTodo[$i]['tipodoc']."
                    </td><td>".$arrayTodo[$i]['contrato']."</td><td>".$arrayTodo[$i]['divisa']."</td><td>".$arrayTodo[$i]['saldohoy']."</td><td>
                    ".
                    $resta
                    ."</td>
                    </tr>";
                */
                    $CONT++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$CONT,$arrayTodo[$i]['agencia']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$CONT,$arrayTodo[$i]['estado']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$CONT,$arrayTodo[$i]['his_saldohoy']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$CONT,$arrayTodo[$i]['Fproceso']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$CONT,$arrayTodo[$i]['codcent']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$CONT,str_replace("'", " ", $arrayTodo[$i]['Nombre']));
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$CONT,$arrayTodo[$i]['nro_doc']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$CONT,$arrayTodo[$i]['tipodoc']);
                $objPHPExcel->getActiveSheet()
                            ->getStyle('I'.$CONT)
                            ->getNumberFormat()
                            ->setFormatCode(
                                PHPExcel_Style_NumberFormat::FORMAT_TEXT
                            );
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$CONT,$arrayTodo[$i]['contrato']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$CONT,$arrayTodo[$i]['divisa']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$CONT,$arrayTodo[$i]['saldohoy']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$CONT,$resta);
                $filasImportar .= "(
                    '".$arrayTodo[$i]['agencia']."',
                    '".$arrayTodo[$i]['estado']."',
                    '".$arrayTodo[$i]['his_saldohoy']."',
                    '".$arrayTodo[$i]['Fproceso']."',
                    '".$arrayTodo[$i]['idcliente_cartera']."',
                    '".$arrayTodo[$i]['codcent']."',
                    '".str_replace("'", " ", $arrayTodo[$i]['Nombre'])."',
                    '".$arrayTodo[$i]['nro_doc']."',
                    '".$arrayTodo[$i]['tipodoc']."',
                    '".$arrayTodo[$i]['contrato']."',
                    '".$arrayTodo[$i]['divisa']."',
                    '".$arrayTodo[$i]['saldohoy']."',
                    '".$resta."'
                    ),";

            }else{
                 $CONT++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$CONT,$arrayTodo[$i]['agencia']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$CONT,$arrayTodo[$i]['estado']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$CONT,$arrayTodo[$i]['his_saldohoy']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$CONT,$arrayTodo[$i]['Fproceso']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$CONT,$arrayTodo[$i]['codcent']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$CONT,str_replace("'", " ", $arrayTodo[$i]['Nombre']));
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$CONT,$arrayTodo[$i]['nro_doc']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$CONT,$arrayTodo[$i]['tipodoc']);
                $objPHPExcel->getActiveSheet()
                            ->getStyle('I'.$CONT)
                            ->getNumberFormat()
                            ->setFormatCode(
                                PHPExcel_Style_NumberFormat::FORMAT_TEXT
                            );
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$CONT,$arrayTodo[$i]['contrato']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$CONT,$arrayTodo[$i]['divisa']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$CONT,$arrayTodo[$i]['saldohoy']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$CONT,'0');
                $filasImportar .= "(
                    '".$arrayTodo[$i]['agencia']."',
                    '".$arrayTodo[$i]['estado']."',
                    '".$arrayTodo[$i]['his_saldohoy']."',
                    '".$arrayTodo[$i]['Fproceso']."',
                    '".$arrayTodo[$i]['idcliente_cartera']."',
                    '".$arrayTodo[$i]['codcent']."',
                    '".str_replace("'", " ", $arrayTodo[$i]['Nombre'])."',
                    '".$arrayTodo[$i]['nro_doc']."',
                    '".$arrayTodo[$i]['tipodoc']."',
                    '".$arrayTodo[$i]['contrato']."',
                    '".$arrayTodo[$i]['divisa']."',
                    '".$arrayTodo[$i]['saldohoy']."',
                    '0'
                    ),";
            }
        }
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

    $filasImportar = substr($filasImportar, 0,-1);

    $sqlTMP_final = "CREATE TEMPORARY  TABLE tmp_final_".$fecha." (
                agencia varchar(30),
                estado varchar(10),
                his_saldohoy varchar(30),
                Fproceso varchar(30),
                idcliente_cartera varchar(30),
                codcent varchar(30),
                Nombre varchar(100),
                nro_doc varchar(50),
                tipodoc varchar(50),
                contrato varchar(50),
                divisa varchar(30),
                saldohoy varchar(30),
                pago varchar(30),
                index(idcliente_cartera),
                index(codcent),
                index(contrato)
        )";
   
    $prTMP_final = $connection->prepare($sqlTMP_final);
    $prTMP_final->execute();

    $sqlTMP_INSERT_final = "INSERT INTO tmp_final_".$fecha." VALUES " .$filasImportar ;
    // echo $sqlTMP_INSERT_final;exit();
    $prTMP_INSERT_final = $connection->prepare($sqlTMP_INSERT_final);
    $prTMP_INSERT_final ->execute();



    $sqlReporteFinal = "SELECT agencia, codcent, contrato, if( estado=1,( sum(pago) ),( sum(pago) + saldohoy) ) as pagado from  tmp_final_".$fecha." GROUP BY contrato";
    $prReporteFinal = $connection->prepare($sqlReporteFinal);
    $prReporteFinal->execute();
    $arrayFinal = $prReporteFinal->fetchAll(PDO::FETCH_ASSOC);

    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('PAGO');
    $objPHPExcel->getActiveSheet()->setCellValue('A1','AGENCIA');
    $objPHPExcel->getActiveSheet()->setCellValue('B1','CODCENT');
    $objPHPExcel->getActiveSheet()->setCellValue('C1','CONTRATO');
    $objPHPExcel->getActiveSheet()->setCellValue('D1','PAGO');
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style_solido_azul);
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   // $html = "<table><tr><td></td><td></td></tr>";
    $CONT2=1;
    for($i=0;$i<count($arrayFinal);$i++){

        $CONT2++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$CONT2,$arrayFinal[$i]['agencia']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$CONT2,$arrayFinal[$i]['codcent']);
        $objPHPExcel->getActiveSheet()
            ->getStyle('A'.$CONT2)
            ->getNumberFormat()
            ->setFormatCode(
                PHPExcel_Style_NumberFormat::FORMAT_TEXT
            );
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$CONT2,$arrayFinal[$i]['contrato']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$CONT2,$arrayFinal[$i]['pagado']);
     /*   $html .= "<tr><td>=\"".$arrayFinal[$i]['contrato']."\"</td>
                    <td>".$arrayFinal[$i]['pagado']."</td>
                    </tr>";
                    */
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="airton.xls"');
    header('Cache-Control: max-age=0');
        
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 
    //$html.="</table>";

   // echo $html;



?>