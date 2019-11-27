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

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    /*$sql= "SELECT t1.idcliente_cartera,t1.codigo,GROUP_CONCAT(t1.numero SEPARATOR '@@') AS numeros FROM (
                    SELECT DISTINCT idcliente_cartera,codigo,numero FROM (
                        select tel.idcliente_cartera,clicar.codigo_cliente as codigo,IFNULL(tel.numero_act,tel.numero) as numero,
                                    (select count(*) from ca_telefono tel2 inner join ca_llamada lla on lla.idtelefono =tel2.idtelefono where IFNULL(tel2.numero_act,tel2.numero)=IFNULL(tel.numero_act,tel.numero) and tel2.idcliente_cartera=tel.idcliente_cartera limit 1) AS validacion from ca_telefono tel
                        inner join ca_cliente_cartera clicar on clicar.codigo_cliente=tel.codigo_cliente
                        where clicar.idcartera in (".$idcartera.") and clicar.estado=1 and tel.idcartera in (".$idcartera.")
                    )A WHERE A.validacion =0
            )t1
            WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^[2-8].......$' OR t1.numero REGEXP '^[1-8]......$' )) 
            GROUP BY t1.idcliente_cartera";*/

    $sql="SELECT t1.idcliente_cartera,t1.codigo,t1.numero AS numeros FROM (
                    SELECT DISTINCT idcliente_cartera,codigo,numero FROM (
                                            select tel.idtelefono,tel.idcliente_cartera,tel.codigo_cliente as codigo,IFNULL(tel.numero_act,tel.numero) as numero,
                                            (select  count(*) from ca_llamada lla inner join ca_telefono tel2 on tel2.idtelefono=lla.idtelefono inner join ca_cliente_cartera clicar on lla.idcliente_cartera=clicar.idcliente_cartera where tel.idcliente_cartera=clicar.idcliente_cartera and IFNULL(tel2.numero_act,tel2.numero)=IFNULL(tel.numero_act,tel.numero) limit 1) as validacion
                                            from ca_telefono tel
                                            INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=tel.idcliente_cartera
                                            where tel.idcartera in (".$idcartera.") and clicar.estado=1 and tel.estado=1 and tel.is_active=1 and clicar.idcartera in (".$idcartera.") and tel.is_active=1 AND (IFNULL(tel.numero_act,tel.numero) NOT REGEXP '^0.' AND (IFNULL(tel.numero_act,tel.numero) REGEXP '^9........$' OR IFNULL(tel.numero_act,tel.numero) REGEXP '^[2-8].......$' OR IFNULL(tel.numero_act,tel.numero) REGEXP '^[2-8]......$' ))                                            
                                            GROUP BY IFNULL(tel.numero_act,tel.numero)
                    )A WHERE A.validacion =0
            )t1
            WHERE (t1.numero NOT REGEXP '^0.' AND (t1.numero REGEXP '^9........$' OR t1.numero REGEXP '^[2-8].......$' OR t1.numero REGEXP '^[2-8]......$' )) AND t1.idcliente_cartera NOT IN (SELECT idcliente_cartera FROM(
                                                                                                                                                                                                        SELECT * FROM (
                                                                                                                                                                                                                select clicar.idcliente_cartera,clicar.codigo_cliente,lla.fecha,(select finser.peso from ca_final_servicio finser where idservicio=$servicio and idfinal=fin.idfinal ) as peso,carfin.nombre as carga
                                                                                                                                                                                                                from ca_llamada lla
                                                                                                                                                                                                                INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
                                                                                                                                                                                                                inner join ca_final fin on fin.idfinal=lla.idfinal
                                                                                                                                                                                                                inner join ca_carga_final carfin on carfin.idcarga_final=fin.idcarga_final
                                                                                                                                                                                                                where clicar.idcartera in (".$idcartera.") and clicar.estado=1
                                                                                                                                                                                                                ORDER BY idcliente_cartera,peso DESC,fecha DESC
                                                                                                                                                                                                        )mejor GROUP BY mejor.idcliente_cartera
                                                                                                                                                                                                )contacto WHERE contacto.carga='CEF')";


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