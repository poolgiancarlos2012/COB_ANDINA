<?php

//	require_once('../../phpincludes/excel/Workbook.php');
//	require_once('../../phpincludes/excel/Worksheet.php');	

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=gestion_diaria.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
//	header("Pragma: public");

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$anio = (int) $_GET['anio'];
$servicio = $_GET['servicio'];
$mes = (int) $_GET['mes'];
$diai = (int) $_GET['diai'];
$diaf = (int) $_GET['diaf'];
$cartera = (int) $_GET['cartera'];
//$tmpfield = json_decode(str_replace("\\","",$_GET["campos"]),true);
//	$tmp = array();
//	for( $i=0;$i<count($tmpfield);$i++ ) {
//		array_push($tmp,"tmp.".$tmpfield[$i]['campo']);
//	}
//	$workbook = new Workbook("-");
//	$workbook->setName('Reportes');

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

//	$xls =& $workbook->add_worksheet('GESTION DIARIA');
//	$xls->write_string(1,1,'REPORTE DE GESTION DIARIA');
//	$xls->write_string(2,1,'Reporte Generado el: '.date("Y-m-d H:i:s"));
//	$xls->write_string(3,1,'Fecha: Del '.$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT).' al '.$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT));
//$sqlCartera = " SELECT tabla, numero_cuenta, moneda_cuenta FROM ca_cartera WHERE idcartera = ? ";
//	
//	$pr = $connection->prepare( $sqlCartera );
//	$pr->bindParam(1,$cartera,PDO::PARAM_INT);
//	$pr->execute();
//	$dataCartera = $pr->fetchAll(PDO::FETCH_ASSOC);

$field = array();

$sql = "";
$sql .= " SELECT ";
$sql .= " clicar.idcliente_cartera, ";
$sql .= " IF( clicar.estado=1,'ACTIVO','INACTIVO') AS 'STATUS', ";
//	$sql .= " clicar.codigo_cliente, ";
//	$sql .= " gescu.numero_cuenta,  ";
//	$sql .= " gescu.moneda,  ";
//if( trim($dataCartera[0]['moneda_cuenta']) == '' || trim($dataCartera[0]['moneda_cuenta']) == 'null' ) {
//		//$sql .= " ( SELECT SUM( total_deuda - monto_pagado )  FROM ca_cuenta WHERE idcartera = clicar.idcartera AND numero_cuenta = gescu.numero_cuenta LIMIT 1 ) AS 'DEUDA', ";
//		array_push( $field, " ( SELECT SUM( total_deuda - monto_pagado )  FROM ca_cuenta WHERE idcartera = $cartera AND numero_cuenta = tmp.".$dataCartera[0]['numero_cuenta']." LIMIT 1 ) AS 'DEUDA' " );
//	}else{
//		//$sql .= " ( SELECT SUM( total_deuda - monto_pagado )  FROM ca_cuenta WHERE idcartera = clicar.idcartera AND numero_cuenta = gescu.numero_cuenta AND moneda = gescu.moneda LIMIT 1 ) AS 'DEUDA', ";
//		array_push( $field, " ( SELECT SUM( total_deuda - monto_pagado )  FROM ca_cuenta WHERE idcartera = $cartera AND numero_cuenta = tmp.".$dataCartera[0]['numero_cuenta']." AND moneda = tmp.".$dataCartera[0]['moneda_cuenta']." LIMIT 1 ) AS 'DEUDA' " );
//	}
//array_push( $field, " t1.DEUDA " );
for ($i = $diai; $i <= $diaf; $i++) {

    $sql.= " IF( DATE(tran.fecha)='" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "',fin.nombre, NULL) AS 'UBICADO____$i', ";
    $sql.= " IF( DATE(tran.fecha)='" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "',( SELECT nombre FROM ca_carga_final WHERE idcarga_final = fin.idcarga_final LIMIT 1 ), NULL) AS 'STATUS____$i', ";
    $sql.= " IF( DATE(tran.fecha) = '" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "', DATE(gescu.fecha_cp) , NULL ) AS 'FECHA_COMPRO____$i', ";
    $sql.= " ( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = IF( DATE(tran.fecha) = '" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "',tran.idusuario_servicio,0) ) AS 'OPERADOR____$i',";

    array_push($field, " t1.UBICADO____$i ");
    array_push($field, " t1.STATUS____$i ");
    array_push($field, " t1.FECHA_COMPRO____$i ");
    array_push($field, " t1.OPERADOR____$i ");
}
$sql = substr($sql, 0, strlen($sql) - 1);
$sql .= " FROM ca_cliente_cartera clicar LEFT JOIN ca_transaccion tran ON tran.idcliente_cartera = clicar.idcliente_cartera LEFT JOIN ca_final fin ON fin.idfinal = tran.idfinal ";
$sql .= " LEFT JOIN ca_llamada lla ON lla.idtransaccion = tran.idtransaccion LEFT JOIN ca_gestion_cuenta gescu ON gescu.idllamada = lla.idllamada ";
//	$sql .= " ON gescu.idllamada = lla.idllamada AND lla.idtransaccion = tran.idtransaccion  ";
//	$sql .= " AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera  ";
$sql .= " WHERE clicar.idcartera IN ($cartera) AND DATE(tran.fecha) BETWEEN '" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($diai, 2, '0', STR_PAD_LEFT) . "' AND '" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($diaf, 2, '0', STR_PAD_LEFT) . "' ";
$sql .= " GROUP BY gescu.idcuenta ";

$psql = "";
$psql .= " SELECT cu.idcliente_cartera, CONCAT_WS('-',cu.gestion,cu.numero_cuenta) AS 'ID_ABONADO' ";
$psql .= " ,cu.gestion AS 'GESTION', 'HDEC' AS 'GESTOR',cu.codigo_cliente AS 'CODIGO' ";
$psql .= " ,cu.numero_cuenta AS 'NUMERO_CUENTA', cu.moneda AS 'MONEDA', cu.telefono AS 'TELEFONO' ";
$psql .= " ,( SELECT CONCAT_WS(' ',paterno,materno,nombre) FROM ca_cliente WHERE idservicio = $servicio AND codigo = cu.codigo_cliente LIMIT 1 ) AS 'NOMBRE' ";
$psql .= " ,( SELECT zona FROM ca_direccion WHERE idcartera = cu.idcartera AND codigo_cliente = cu.codigo_cliente LIMIT 1 ) AS 'ZONAL' ";
$psql .= " , total_deuda AS 'DEUDA' ";
$psql .= " , total_comision AS 'COMISION' ";
$psql .= " , monto_pagado AS 'MONTO_PAGADO' ";
$psql .= " FROM ca_cuenta cu WHERE idcartera IN ($cartera) ";

//$xsql = " SELECT tmp.ID_ABONADO,tmp.GESTION,tmp.GESTOR,tmp.CODIGO,tmp.numero_cuenta AS 'ANEXO',tmp.TELEFONO,tmp.NOMBRE,tmp.ZONAL,tmp.DEUDA, ".implode(",",$field)." FROM ( $psql ) tmp LEFT JOIN ( ".$sql." ) t1 ON t1.numero_cuenta = tmp.numero_cuenta ";

$xsql = " SELECT t2.ID_ABONADO,t2.GESTION,t2.GESTOR,t2.CODIGO,t2.NUMERO_CUENTA,t2.MONEDA,t2.TELEFONO,t2.NOMBRE,t2.ZONAL,t2.DEUDA,t2.COMISION,t2.MONTO_PAGADO,t1.STATUS," . implode(",", $field) . " FROM ( " . $psql . " ) t2 LEFT JOIN ( " . $sql . " ) t1 ON t1.idcliente_cartera = t2.idcliente_cartera ";

$pr = $connection->prepare($xsql);
$pr->execute();
$countRow = 8;
echo "<table>";
while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {

    if ($countRow == 8) {
        //$j=1;
        $fieldTop = "";
        $fieldTop2 = "";
        foreach ($row as $index => $value) {
            $field = explode("____", $index);
            if ($field[0] == 'UBICADO') {
                //$xls->write_string($countRow-2,$j,$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($field[1],2,'0',STR_PAD_LEFT));
                //$xls->write_string($countRow-1,$j,$field[0]);
                $fieldTop.="<td align=\"center\" style=\"background-color:blue;color:white;\">" . $anio . "-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($field[1], 2, '0', STR_PAD_LEFT) . "</td>";
                $fieldTop2.="<td align=\"center\" style=\"background-color:blue;color:white;\">" . $field[0] . "</td>";
            } else {
                //$xls->write_string($countRow-1,$j,$field[0]);
                $fieldTop.="<td align=\"center\" style=\"background-color:blue;color:white;\"></td>";
                $fieldTop2.="<td align=\"center\" style=\"background-color:blue;color:white;\">" . $field[0] . "</td>";
            }
            //$j++;
        }
        echo "<tr>" . $fieldTop . "</tr>";
        echo "<tr>" . $fieldTop2 . "</tr>";
    }
    echo "<tr>";
    //$j=1;
    foreach ($row as $index => $value) {
        //$xls->write_string($countRow,$j,$value);
        //$j++;
        echo "<td>" . $value . "</td>";
    }
    $countRow++;
    echo "</tr>";
}
echo "</table>";
//$workbook->close();
?>