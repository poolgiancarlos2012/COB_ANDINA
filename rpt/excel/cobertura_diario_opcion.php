<?php

    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
    date_default_timezone_set('America/Lima');

    $Servicio = $_GET['Servicio'];
    $idCartera = $_GET['Cartera'];
    // $nombre_servicio = $_GET['NombreServicio'];
    // $tipo_cambio = $_GET['tipocambio'];
    // $tipo_vac = $_GET['tipovac'];
    $fechaProceso = $_GET['fechaProceso'];

    $factoryConnection= FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();

    require_once '../../phpincludes/phpexcel/Classes/PHPExcel.php';
    require_once '../../phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

    // echo $Servicio."<br>";
    // echo $idCartera."<br>";
    // echo $fechaProceso."<br>";

    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0)->setTitle("Resumen Gestion");

    $sql_resumen_cobertura="    SELECT
                                'CON_GESTION' AS 'GESTION',
                                COUNT(DISTINCT his.doi) AS 'CLIENTES',
                                COUNT(DISTINCT his.contrato) AS 'CONTRATOS',
                                SUM(his.cuota_mensual+his.seguros+his.otros) AS 'MONTOS'
                                FROM 
                                ca_historico_opcion his
                                LEFT JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=his.idcliente_cartera AND clicar.idcartera=$idCartera
                                LEFT JOIN ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
                                WHERE 
                                his.idcartera=$idCartera AND
                                his.fecha_carga='$fechaProceso' AND
                                IF(CONCAT(YEAR(NOW()), MONTH(NOW())) > IFNULL(CONCAT(YEAR(lla.fecha),MONTH(lla.fecha)),'0'),0,1)=1

                                UNION ALL


                                SELECT
                                'SIN_GESTION' AS 'GESTION',
                                COUNT(DISTINCT his.doi) AS 'CLIENTES',
                                COUNT(DISTINCT his.contrato) AS 'CONTRATOS',
                                SUM(his.cuota_mensual+his.seguros+his.otros) AS 'MONTOS'
                                FROM 
                                ca_historico_opcion his
                                LEFT JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=his.idcliente_cartera AND clicar.idcartera=$idCartera
                                LEFT JOIN ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
                                WHERE 
                                his.idcartera=$idCartera AND
                                his.fecha_carga='$fechaProceso' AND
                                IF(CONCAT(YEAR(NOW()), MONTH(NOW())) > IFNULL(CONCAT(YEAR(lla.fecha),MONTH(lla.fecha)),'0'),0,1)=0

                                UNION ALL

                                SELECT
                                'BASE' AS 'GESTION',
                                COUNT(DISTINCT his.doi) AS 'CLIENTES',
                                COUNT(DISTINCT his.contrato) AS 'CONTRATOS',
                                SUM(his.cuota_mensual+his.seguros+his.otros) AS 'MONTOS'
                                FROM 
                                ca_historico_opcion his
                                LEFT JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=his.idcliente_cartera AND clicar.idcartera=$idCartera
                                LEFT JOIN ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
                                WHERE 
                                his.idcartera=$idCartera AND
                                his.fecha_carga='$fechaProceso'
                            ";



    $pr_resumen_cobertura=$connection->prepare($sql_resumen_cobertura);
    $pr_resumen_cobertura->execute();
    
    $columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
    
    $fil=1;
    $col=0;
    $xls->getActiveSheet()->SetCellValue('A2',$fechaProceso);
    $xls->getActiveSheet()->mergeCells("A2:A4");
    $xls->getActiveSheet()->SetCellValue($columna[$col].$fil, "PROCESO");
    $xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, "GESTION");
    $xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, "CLIENTES");
    $xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, "CONTRATOS");
    $xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, "DEUDA");
    while( $datos_resumen=$pr_resumen_cobertura->fetch(PDO::FETCH_ASSOC) ) {
        $fil++;
        $xls->getActiveSheet()->SetCellValue($columna[$col+1].$fil, $datos_resumen["GESTION"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col+2].$fil, $datos_resumen["CLIENTES"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col+3].$fil, $datos_resumen["CONTRATOS"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col+4].$fil, $datos_resumen["MONTOS"]);

        $xls->getActiveSheet()->getStyle($columna[$col+4].$fil)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    }

    $sql_detalle_cobertura="    SELECT
                                (SELECT nombre_cartera FROM ca_cartera WHERE idcartera=$idCartera) AS 'cartera_mes',
                                '$fechaProceso' AS 'fecha_proceso',
                                his.semana,
                                his.contrato,
                                his.fecha_vencimiento,
                                his.tipo_cuota,
                                his.cuota_mensual,
                                his.seguros,
                                his.otros,
                                his.tipo_persona,
                                CONCAT('=\"',his.doi,'\"')  AS  'doi',
                                IF(TRIM(CONCAT_WS(' ',his.nombres,his.paterno,his.materno)) IS NULL OR TRIM(CONCAT_WS(' ',his.nombres,his.paterno,his.materno))='',his.razon_social,TRIM(CONCAT_WS(' ',his.nombres,his.paterno,his.materno))) AS 'cliente',
                                his.direccion,
                                his.urbanizacion,
                                his.distrito,
                                his.provincia,
                                his.departamento,
                                his.telefono1,
                                his.telefono2,
                                his.tipo_adjudicacion,
                                his.sit_entrega,
                                his.tipo_cobranza,
                                his.moneda,
                                his.valor_certificado,
                                his.gestor_cobranza,
                                his.anexo ,
                                IF(CONCAT(YEAR(NOW()), MONTH(NOW())) > IFNULL(CONCAT(YEAR(lla.fecha),MONTH(lla.fecha)),'SIN GESTION'),'SIN GESTION','CON GESTION') AS 'gestion_cliente',
                                lla.fecha AS 'ult_fecha_llamada_cliente',
                                (SELECT nombre FROM ca_final WHERE idfinal=lla.idfinal) AS 'ult_estado_cliente'
                                FROM 
                                ca_historico_opcion his
                                LEFT JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=his.idcliente_cartera AND clicar.idcartera=$idCartera
                                LEFT JOIN ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
                                WHERE 
                                his.idcartera=$idCartera AND
                                his.fecha_carga='$fechaProceso'
                                ";

    $pr_detalle_cobertura=$connection->prepare($sql_detalle_cobertura);
    $pr_detalle_cobertura->execute();

    $xls->createSheet();
    $xls->setActiveSheetIndex(1)->setTitle("Detalle Proceso");  
    
    $fil_d=1;
    $col_d=0;

    $xls->getActiveSheet()->SetCellValue($columna[$col_d].$fil_d, 'CARTERA_MES');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+1].$fil_d, 'FECHA_PROCESO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+2].$fil_d, 'SEMANA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+3].$fil_d, 'CONTRATO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+4].$fil_d, 'FECH.VENC');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+5].$fil_d, 'NRO_CUOTA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+6].$fil_d, 'COUTA_MENSUA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+7].$fil_d, 'SEGUROS');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+8].$fil_d, 'OTROS');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+9].$fil_d, 'TIPO_PERSONA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+10].$fil_d, 'DOI');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+11].$fil_d, 'CLIENTE');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+12].$fil_d, 'DIRECCION');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+13].$fil_d, 'URBANIZACION');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+14].$fil_d, 'DISTRITO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+15].$fil_d, 'PROVINCIA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+16].$fil_d, 'DEPARTAMENTO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+17].$fil_d, 'TELEFONO1');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+18].$fil_d, 'TELEFONO2');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+19].$fil_d, 'TIPO_ADJUDICACION');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+20].$fil_d, 'SIT_ENTREGA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+21].$fil_d, 'TIPO_COBRANZA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+22].$fil_d, 'MONEDA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+23].$fil_d, 'VALOR_CERTIFICADO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+24].$fil_d, 'GESTOR_COBRANZA');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+25].$fil_d, 'ANEXO');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+26].$fil_d, 'GESTION_CLIENTE');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+27].$fil_d, 'ULT_FECHA_LLAMADA_CLIENTE');
    $xls->getActiveSheet()->SetCellValue($columna[$col_d+28].$fil_d, 'ULT_ESTADO_CLIENTE');

    while( $datos_detalle=$pr_detalle_cobertura->fetch(PDO::FETCH_ASSOC) ) {
        $fil_d++;
        $xls->getActiveSheet()->SetCellValue($columna[$col_d].$fil_d, $datos_detalle["cartera_mes"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+1].$fil_d, $datos_detalle["fecha_proceso"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+2].$fil_d, $datos_detalle["semana"]);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+3].$fil_d, $datos_detalle['contrato']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+4].$fil_d, $datos_detalle['fecha_vencimiento']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+5].$fil_d, $datos_detalle['tipo_cuota']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+6].$fil_d, $datos_detalle['cuota_mensual']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+7].$fil_d, $datos_detalle['seguros']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+8].$fil_d, $datos_detalle['otros']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+9].$fil_d, $datos_detalle['tipo_persona']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+10].$fil_d, $datos_detalle['doi']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+11].$fil_d, $datos_detalle['cliente']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+12].$fil_d, $datos_detalle['direccion']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+13].$fil_d, $datos_detalle['urbanizacion']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+14].$fil_d, $datos_detalle['distrito']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+15].$fil_d, $datos_detalle['provincia']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+16].$fil_d, $datos_detalle['departamento']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+17].$fil_d, $datos_detalle['telefono1']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+18].$fil_d, $datos_detalle['telefono2']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+19].$fil_d, $datos_detalle['tipo_adjudicacion']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+20].$fil_d, $datos_detalle['sit_entrega']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+21].$fil_d, $datos_detalle['tipo_cobranza']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+22].$fil_d, $datos_detalle['moneda']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+23].$fil_d, $datos_detalle['valor_certificado']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+24].$fil_d, $datos_detalle['gestor_cobranza']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+25].$fil_d, $datos_detalle['anexo']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+26].$fil_d, $datos_detalle['gestion_cliente']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+27].$fil_d, $datos_detalle['ult_fecha_llamada_cliente']);
        $xls->getActiveSheet()->SetCellValue($columna[$col_d+28].$fil_d, $datos_detalle['ult_estado_cliente']);


    }


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Resumen_Cobertura_opcion_'.date('Y-m-d').'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
    $objWriter->save('php://output');
    exit();







?>