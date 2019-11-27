<?php

class MARIACuentaDAO {

    public function queryHistorialByCliente(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.codigo_cliente AS 'CODIGO_CLIENTE', cu.numero_cuenta AS 'NUMERO_CUENTA', 
					DATE(det.fecha_creacion) AS 'FECHA', TRUNCATE( IFNULL(cu.total_deuda,''),2 ) AS 'DEUDA',
					TRUNCATE( IFNULL(cu.total_comision,''),2 ) AS 'COMISION',
					IFNULL(cu.moneda,'') AS 'MONEDA', IFNULL(det.tramo,'') AS 'TRAMO' , IFNULL(det.dias_mora,'') AS 'DIAS_MORA',
					IFNULL(det.numero_cuotas,'') AS 'NUMERO_CUOTAS', IFNULL(det.numero_cuotas_pagadas,'') AS 'NUMERO_CUOTAS_PAGADAS'
					FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_detalle_cuenta det 
					ON det.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera
					WHERE cu.idcartera IN ( SELECT idcartera FROM ca_cartera WHERE cartera_act = ?   )
					AND clicar.idcliente = ? AND cu.estado = 1 ";

        $cartera = $dtoClienteCartera->getIdCartera();
        $idcliente = $dtoClienteCartera->getIdCliente();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idcliente, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    // public function queryByClient(dto_cuenta $dtoCuenta,$empresa) {
    public function queryByClient($idcartera,$idcliente_cartera,$empresa,$td,$doc,$contado) {
        $where="";
        if($empresa!=""){
            $where.=" AND detcu.dato2='$empresa'";
        }

        if($td!=""){
            $where.=" AND detcu.dato8='$td'";
        }

        if($doc!=""){
            $where.=" AND detcu.codigo_operacion='$doc'";
        }

        if($contado==1){
            $where=" AND DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1";
        }

        $sql = "    SELECT 
                    cu.idcuenta AS 'idcuenta',
                    IF( cu.retirado=1, CONCAT_WS(' ','<font color=\"red\"><b>RETIRADO</b></font>', cu.fecha_retiro, cu.motivo_retiro),IF(ISNULL(cu.estado_cuenta),'NO',cu.estado_cuenta) ) AS 'RETIRADO',
                    detcu.dato2 AS 'empresa',
                    detcu.dato8 AS 'td',
                    detcu.moneda AS 'mon',
                    detcu.codigo_operacion AS 'num_doc',
                    detcu.fecha_emision AS 'fecha_doc',
                    detcu.fecha_vencimiento AS 'fecha_vcto',
                    detcu.dias_mora AS 'dias_transc_vcto_of',
                    detcu.dato15 AS 'rango_vcto',
                    IFNULL(detcu.dato22,'') AS 'est_letr',
                    IFNULL(detcu.dato23,'') AS 'banco',
                    IFNULL(detcu.dato24,'') AS 'num_cobranza',
                    detcu.total_deuda AS 'importe_original',
                    detcu.saldo_capital_dolares AS 'total_convertido_a_dolares',
                    detcu.saldo_capital_soles AS 'total_convertido_a_soles'
                    FROM ca_cuenta cu
                    INNER JOIN ca_detalle_cuenta detcu ON cu.idcuenta=detcu.idcuenta
                    WHERE 
                    cu.idcartera=$idcartera  AND 
                    cu.estado=1 AND
                    cu.idcliente_cartera = $idcliente_cartera
                    $where
                    ORDER BY detcu.fecha_emision DESC
                    ";

        // echo($sql);
        // exit();

        // $cartera = $dtoCuenta->getIdCartera();
        // $codigo_cliente = $dtoCuenta->getCodigoCliente();
        // $idcliente_cartera = $dtoCuenta->getIdClienteCartera();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        // $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        // $pr->bindParam(2, $idcliente_cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function resumen_deuda($codigo_cliente,$idcartera,$empresa,$td,$doc,$contado){

        $where="";
        if($empresa!=""){
            $where.=" AND detcu.dato2='$empresa'";
        }

        if($td!=""){
            $where.=" AND detcu.dato8='$td'";
        }

        if($doc!=""){
            $where.=" AND detcu.codigo_operacion='$doc'";
        }

        if($contado==1){
            $where=" AND DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1";
        }

        $sql = "    SELECT
                    ROUND(SUM(IF(detcu.dato2='CAISAC'  AND  (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDINA_SOLES',
                    ROUND(SUM(IF(detcu.dato2='ANDEX' AND    (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDEX_SOLES',
                    ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SEMILLA_SOLES',
                    ROUND(SUM(IF(detcu.dato2='SUNNY' AND    (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SUNNY_SOLES',

                    ROUND(SUM(IF(detcu.dato2='CAISAC' AND   (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDINA_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='ANDEX' AND    (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'ANDEX_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SEMILLA_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='SUNNY' AND    (dato8='FT' OR dato8='BV' OR dato8='ND' OR dato8='LT' OR dato8='TK') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SUNNY_DOLARES',

                    ROUND(SUM(IF(detcu.dato2='CAISAC' AND   (dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDINA_SOLES',
                    ROUND(SUM(IF(detcu.dato2='ANDEX' AND    (dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDEX_SOLES',
                    ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND (dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SEMILLAS_SOLES',
                    ROUND(SUM(IF(detcu.dato2='SUNNY' AND    (dato8='NC' OR dato8='PA') AND detcu.moneda='MN',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SUNNY_SOLES',

                    ROUND(SUM(IF(detcu.dato2='CAISAC' AND   (dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDINA_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='ANDEX' AND    (dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_ANDEX_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='SEMILLAS' AND (dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SEMILLAS_DOLARES',
                    ROUND(SUM(IF(detcu.dato2='SUNNY' AND    (dato8='NC' OR dato8='PA') AND detcu.moneda='US',IFNULL(detcu.saldo_capital,0),0)),2) AS 'SA_SUNNY_DOLARES'
                    FROM
                    ca_detalle_cuenta detcu
                    WHERE
                    detcu.codigo_cliente='$codigo_cliente' AND
                    detcu.idcartera=$idcartera AND
                    detcu.estado=1 -- AND
                    -- DATEDIFF(detcu.fecha_vencimiento,detcu.fecha_emision)<>1 -- NO CONSIDERA LOS CONTADOS
                    $where
                    GROUP BY detcu.codigo_cliente";

        // echo $sql;
        // exit();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            // echo json_encode(array('rst' => true, 'data'=>$pr->fetchAll(PDO::FETCH_ASSOC)));

            $datasum=$pr->fetchAll(PDO::FETCH_ASSOC);

            $TOTAL_ANDINA_SOLES=$datasum[0]['ANDINA_SOLES'];
            $TOTAL_ANDEX_SOLES=$datasum[0]['ANDEX_SOLES'];
            $TOTAL_SEMILLAS_SOLES=$datasum[0]['SEMILLA_SOLES'];
            $TOTAL_SUNNY_SOLES=$datasum[0]['SUNNY_SOLES'];
            $TOTAL_GENERAL_SOLES=$TOTAL_ANDINA_SOLES+$TOTAL_ANDEX_SOLES+$TOTAL_SEMILLAS_SOLES+$TOTAL_SUNNY_SOLES;

            $TOTAL_ANDINA_SOLES=number_format($TOTAL_ANDINA_SOLES,2,'.',',');
            $TOTAL_ANDEX_SOLES=number_format($TOTAL_ANDEX_SOLES,2,'.',',');
            $TOTAL_SEMILLAS_SOLES=number_format($TOTAL_SEMILLAS_SOLES,2,'.',',');
            $TOTAL_SUNNY_SOLES=number_format($TOTAL_SUNNY_SOLES,2,'.',',');
            $TOTAL_GENERAL_SOLES=number_format($TOTAL_GENERAL_SOLES,2,'.',',');

            $TOTAL_ANDINA_DOLARES=$datasum[0]['ANDINA_DOLARES'];
            $TOTAL_ANDEX_DOLARES=$datasum[0]['ANDEX_DOLARES'];
            $TOTAL_SEMILLAS_DOLARES=$datasum[0]['SEMILLA_DOLARES'];
            $TOTAL_SUNNY_DOLARES=$datasum[0]['SUNNY_DOLARES'];
            $TOTAL_GENERAL_DOLARES=$TOTAL_ANDINA_DOLARES+$TOTAL_ANDEX_DOLARES+$TOTAL_SEMILLAS_DOLARES+$TOTAL_SUNNY_DOLARES; 

            $TOTAL_ANDINA_DOLARES=number_format($TOTAL_ANDINA_DOLARES,2,'.',',');
            $TOTAL_ANDEX_DOLARES=number_format($TOTAL_ANDEX_DOLARES,2,'.',',');
            $TOTAL_SEMILLAS_DOLARES=number_format($TOTAL_SEMILLAS_DOLARES,2,'.',',');
            $TOTAL_SUNNY_DOLARES=number_format($TOTAL_SUNNY_DOLARES,2,'.',',');
            $TOTAL_GENERAL_DOLARES=number_format($TOTAL_GENERAL_DOLARES,2,'.',',');

            $TOTAL_SA_ANDINA_SOLES=$datasum[0]['SA_ANDINA_SOLES'];
            $TOTAL_SA_ANDEX_SOLES=$datasum[0]['SA_ANDEX_SOLES'];
            $TOTAL_SA_SEMILLAS_SOLES=$datasum[0]['SA_SEMILLAS_SOLES'];
            $TOTAL_SA_SUNNY_SOLES=$datasum[0]['SA_SUNNY_SOLES'];
            $TOTAL_SA_GENERAL_SOLES=$TOTAL_SA_ANDINA_SOLES+$TOTAL_SA_ANDEX_SOLES+$TOTAL_SA_SEMILLAS_SOLES+$TOTAL_SA_SUNNY_SOLES;    

            $TOTAL_SA_ANDINA_SOLES=number_format($TOTAL_SA_ANDINA_SOLES,2,'.',',');
            $TOTAL_SA_ANDEX_SOLES=number_format($TOTAL_SA_ANDEX_SOLES,2,'.',',');
            $TOTAL_SA_SEMILLAS_SOLES=number_format($TOTAL_SA_SEMILLAS_SOLES,2,'.',',');
            $TOTAL_SA_SUNNY_SOLES=number_format($TOTAL_SA_SUNNY_SOLES,2,'.',',');
            $TOTAL_SA_GENERAL_SOLES=number_format($TOTAL_SA_GENERAL_SOLES,2,'.',',');

            $TOTAL_SA_ANDINA_DOLARES=$datasum[0]['SA_ANDINA_DOLARES'];
            $TOTAL_SA_ANDEX_DOLARES=$datasum[0]['SA_ANDEX_DOLARES'];
            $TOTAL_SA_SEMILLAS_DOLARES=$datasum[0]['SA_SEMILLAS_DOLARES'];
            $TOTAL_SA_SUNNY_DOLARES=$datasum[0]['SA_SUNNY_DOLARES'];
            $TOTAL_SA_GENERAL_DOLARES=$TOTAL_SA_ANDINA_DOLARES+$TOTAL_SA_ANDEX_DOLARES+$TOTAL_SA_SEMILLAS_DOLARES+$TOTAL_SA_SUNNY_DOLARES;  

            $TOTAL_SA_ANDINA_DOLARES=number_format($TOTAL_SA_ANDINA_DOLARES,2,'.',',');
            $TOTAL_SA_ANDEX_DOLARES=number_format($TOTAL_SA_ANDEX_DOLARES,2,'.',',');
            $TOTAL_SA_SEMILLAS_DOLARES=number_format($TOTAL_SA_SEMILLAS_DOLARES,2,'.',',');
            $TOTAL_SA_SUNNY_DOLARES=number_format($TOTAL_SA_SUNNY_DOLARES,2,'.',',');
            $TOTAL_SA_GENERAL_DOLARES=number_format($TOTAL_SA_GENERAL_DOLARES,2,'.',',');

            $resumen_deuda="";
            $resumen_deuda.='<div style="font-family: arial; font-variant-ligatures: normal; font-variant-caps: normal; orphans: 2; text-align: -webkit-left; widows: 2; -webkit-text-stroke-width: 0px; text-decoration-style: initial; text-decoration-color: initial;">';
            $resumen_deuda.='<table style="margin: 0 auto;border-collapse: collapse; " border="0" cellspacing="0" cellpadding="0">';
            $resumen_deuda.='<tbody>';
            $resumen_deuda.='<tr style="">';
            $resumen_deuda.='<td style="width:40px;">&nbsp;</td>';
            $resumen_deuda.='<td style="width:30px; text-align: center;">&nbsp;</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt;width:100px;text-align: center; font-size: 11px;background: #9bc4ea;"><strong>CAISAC</strong></td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt;width:100px; text-align: center; font-size: 11px;background: #9bc4ea;"><strong>ANDEX</strong></td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt;width:100px;text-align: center; font-size: 11px;background: #9bc4ea;"><strong>SEMILLAS</strong></td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; width:100px;text-align: center; font-size: 11px;background: #9bc4ea;"><strong>SUNNY</strong></td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; width:100px;text-align: center; font-size: 11px;background: #9bc4ea;"><strong>TOTAL</strong></td>';
            $resumen_deuda.='</tr>';

            $resumen_deuda.='<tr style="border: solid #9bc2e6 1.0pt;">';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; font-size: 11px;" rowspan="2">SALDO</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: center; font-size: 11px;">S/.</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_ANDINA_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_ANDEX_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SEMILLAS_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SUNNY_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_GENERAL_SOLES.'</td>';
            $resumen_deuda.='</tr>';
            $resumen_deuda.='<tr style="border: solid #9bc2e6 1.0pt;">';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: center;font-size: 11px;">$</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_ANDINA_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_ANDEX_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SEMILLAS_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SUNNY_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_GENERAL_DOLARES.'</td>';
            $resumen_deuda.='</tr>';
            $resumen_deuda.='<tr style="border: solid #9bc2e6 1.0pt;">';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; border-image: initial; ;font-size: 11px;" rowspan="2">FAVOR&nbsp;</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; border-image: initial; text-align: center; ;font-size: 11px;">S/.</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_ANDINA_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_ANDEX_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_SEMILLAS_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_SUNNY_SOLES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_GENERAL_SOLES.'</td>';
            $resumen_deuda.='</tr>';
            $resumen_deuda.='<tr style="border: solid #9bc2e6 1.0pt;">';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; border-image: initial; text-align: center; ;font-size: 11px;">$</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_ANDINA_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_ANDEX_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_SEMILLAS_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_SUNNY_DOLARES.'</td>';
            $resumen_deuda.='<td style="border: solid #9bc2e6 1.0pt; text-align: right; font-size: 11px;padding:0 3px 0 0;background: #cde7ff;">'.$TOTAL_SA_GENERAL_DOLARES.'</td>';
            $resumen_deuda.='</tr>';
            $resumen_deuda.='</tbody>';
            $resumen_deuda.='</table>';
            $resumen_deuda.='</div>';

            echo json_encode(array('rst' => true, 'resumen'=>$resumen_deuda));

        } else {
            return array();
        }
    }


}

?>
