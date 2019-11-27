<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=GESTION_DE_LLAMADAS.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	

    $idcartera=$_REQUEST['Cartera'];
    $servicio=$_REQUEST['Servicio'];
    $idfinal=$_REQUEST['idfinal'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
    $whereidfinal="";
    $whereidfinal2="";
    if($idfinal!=""){
        $whereidfinal="WHERE mejor.idfinal in ($idfinal)";
        $whereidfinal2="WHERE fonos.idfinal in ($idfinal)";
    }

    $sql= "SELECT t1.idcliente_cartera,t1.codigo,t1.numero AS numeros FROM (
                    SELECT DISTINCT UNO.idcliente_cartera, DOS.numero, DOS.idfinal,UNO.codigo FROM (
                        SELECT * FROM (
                            SELECT * FROM (
                                select lla.idcliente_cartera,clicar.codigo_cliente AS codigo,
                                (SELECT IF(numero_act IS NULL,numero,numero_act) AS numero FROM ca_telefono WHERE idtelefono=lla.idtelefono) AS numero,
                                (SELECT peso FROM ca_final_servicio finser 
                                                                INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=$servicio AND fin.idfinal = lla.idfinal) AS peso,
                                                            (SELECT fin.nombre FROM ca_final_servicio finser 
                                                                INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=$servicio AND fin.idfinal = lla.idfinal) AS estado,
                                lla.idfinal
                                from ca_llamada lla
                                inner join ca_cliente_cartera clicar on lla.idcliente_cartera=clicar.idcliente_cartera
                                where clicar.idcartera in ($idcartera) AND clicar.estado=1 AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.idusuario_servicio!=1 AND lla.tipo!='S'
                                ORDER BY lla.idcliente_cartera,peso DESC
                            )A GROUP BY A.idcliente_cartera
                        )mejor $whereidfinal
                    )UNO INNER JOIN (
                        SELECT * FROM (
                            SELECT * FROM (
                                    select lla.idcliente_cartera,
                                    (SELECT IF(numero_act IS NULL,numero,numero_act) AS numero FROM ca_telefono WHERE idtelefono=lla.idtelefono) AS numero,
                                    (SELECT peso FROM ca_final_servicio finser 
                                                                    INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=$servicio AND fin.idfinal = lla.idfinal) AS peso,
                                                                (SELECT fin.nombre FROM ca_final_servicio finser 
                                                                    INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=$servicio AND fin.idfinal = lla.idfinal) AS estado,
                                    lla.idfinal
                                    from ca_llamada lla
                                    inner join ca_cliente_cartera clicar on lla.idcliente_cartera=clicar.idcliente_cartera
                                    where clicar.idcartera in ($idcartera) AND clicar.estado=1 AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND lla.idusuario_servicio!=1 AND lla.tipo!='S'
                                    ORDER BY lla.idcliente_cartera,numero,peso DESC
                            )A GROUP BY A.idcliente_cartera,numero
                        )fonos $whereidfinal2
                    )DOS ON UNO.idcliente_cartera=DOS.idcliente_cartera
                    ORDER BY UNO.idcliente_cartera
                )t1 
                WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^[2-8].......$' OR t1.numero REGEXP '^[2-8]......$' )) ";

    $pr=$connection->prepare($sql);
    $pr->execute();

    $mayor=0;
    $table="DATA \t";
    $table.="CODIGO_CLIENTE \t";
    $contenido="";
    while($row=$pr->fetch(PDO::FETCH_ASSOC)){
        $cadena=array();
        $cadena=explode('@@', $row['numeros']);
        $contenido.=utf8_decode($row['idcliente_cartera'])."\t";
        $contenido.='="'.($row['codigo']).'"'."\t";
        for($i=0;$i<count($cadena);$i++) {
            if (strlen($cadena[$i]) == 8 AND substr($cadena[$i],0,1) != 9 ) {
                $contenido.='="0'.($cadena[$i]).'"'."\t";
            } else {
                $contenido.='="'.($cadena[$i]).'"'."\t";
            }
        }
        $contenido.="\n";
        if($mayor<count($cadena)){
            $mayor=count($cadena);
        }
    }
    for($j=0;$j<$mayor;$j++){
        $table.="TELEFONO ".($j+1)."\t";
    }
    $table.="\n";
    echo $table;
    echo $contenido;

        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>