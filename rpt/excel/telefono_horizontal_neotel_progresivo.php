<?php


    header('content-type:text/html;charset=UTF-8');
    header('content-type:application/vnd.ms-excel;charset=latin');
    header('content-disposition:atachment;filename=telefonos_hotizontal_neotel.xls');
    header('Pragma:no-cache');
    header('Expires:0');
    
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
    
    $confcobrast=  parse_ini_file('../../conf/cobrast.ini',true);
    $user=$confcobrast['user_db']['user_rpt'];
    $password=$confcobrast['user_db']['password_rpt'];
    
    date_default_timezone_set('America/Lima');
    
    $factoryConnection=FactoryConnection::create('mysql');
    $connection=$factoryConnection->getConnection($user,$password);
    
    $idcartera=$_REQUEST['Cartera'];
    $servicio=$_REQUEST['Servicio'];

    //OBTENGO IDCLICAR DE QUIENES QUE SE CONSIDERARAN EN EL REPORTE SEGUN CRITERIOS DE FILTRO
    /////////////////////////////////////////////////////////////////////////////////////
    //campos pa filtro de idclicar
    $idfinal=$_REQUEST['idfinal'];
    $singestion=$_REQUEST['singestion'];
    $concdo=$_REQUEST['concdo'];

//    $w_idfinal= $idfinal==""?"":" and cu.ul_estado in ($idfinal) ";//where_idfinal
//    $w_singestion=$singestion==false?"":" and cu.ul_fecha is null ";
    /*$sql="SELECT concat(idcliente_cartera,',') as idclicar
        from ca_cuenta cu 
        where cu.idcartera in ($idcartera) and cu.estado=1 $w_idfinal $w_singestion";
        */
        $w_idfinal= $idfinal==""?"":" and lla.idfinal in ($idfinal) ";//where_idfinal
        $w_idfinal_where=$idfinal==""?"":" WHERE A.COUNT=1 ";
        //$w_singestion=$singestion==true?" and clicar.id_ultima_llamada=0 ":"";        
        if($singestion=="true"){
            $w_singestion=" and clicar.id_ultima_llamada=0 ";
        }else{
            $w_singestion=" ";
        }
        if($concdo=="true"){
            $t_concdo=" and idorigen=14";
        }else{
            $t_concdo=" ";
        }        
        $sql="SELECT concat(A.idcliente_cartera,',') AS idclicar FROM (
            SELECT clicar.idcliente_cartera,(select count(*) from ca_llamada lla where lla.idllamada=clicar.id_ultima_llamada $w_idfinal) AS 'COUNT'
            FROM ca_cliente_cartera clicar
            WHERE clicar.estado=1 and clicar.idcartera in ($idcartera) $w_singestion)A $w_idfinal_where";
   // echo($sql);exit;
    $pr=$connection->prepare($sql);
    $pr->execute();
    $idclicar="";
    while($row=$pr->fetch(PDO::FETCH_ASSOC)){
        $idclicar.=$row['idclicar'];
    }
    $idclicar=substr($idclicar,0,strlen($idclicar)-1);
    if($idclicar==''){echo('No se encontraron registros');exit;}
    //REPORTE
    /////////
    $sql="SELECT clicar.idcliente_cartera,cli.codigo, A.numeros 
	from ca_cliente cli
    inner join ca_cliente_cartera clicar
    on cli.idcliente=clicar.idcliente
    inner join 
    (
        select t1.idcliente_cartera,t1.numero as numeros 
        from (
            select DISTINCT idcliente_cartera , 
            IF(numero_act IS NULL, numero, numero_act) AS numero  
			,IF(numero_act IS NULL, numero, numero_act) AS numero_v 
            from ca_telefono 
                WHERE idcartera in ($idcartera)
                    AND CAST(IFNULL(numero_act,numero) AS SIGNED)!=0 -- evita los que tienen puros ceros y los que tienen algun signo (#,*,etc)
                    and length(CAST(IFNULL(numero_act,numero) AS SIGNED))>5 -- evita numeros con ceros adelante y 5 o menos caracteres (0000546,00025485,002584,etc)
                    and not (length(IFNULL(numero_act,numero))=9 and SUBSTR(IFNULL(numero_act,numero), 1,1)=0 and SUBSTR(IFNULL(numero_act,numero), 2,1)=9) -- evita 9 digitos, 1ro 0 y 1do 9 (celulares mal escritos)
                    and (IFNULL(numero_act,numero)!='999999999' and IFNULL(numero_act,numero)!='0999999999' and IFNULL(numero_act,numero)!='9999999')
                    and idcliente_cartera in ( $idclicar ) and estado=1 and idtipo_referencia!=3 $t_concdo
        ) t1 
		WHERE (t1.numero_v NOT REGEXP '^0.' AND (t1.numero_v REGEXP '^9........$' OR t1.numero_v REGEXP '^[2-8].......$' OR t1.numero_v REGEXP '^[2-8]......$' )) 
    ) A
    on A.idcliente_cartera=clicar.idcliente_cartera
    where clicar.idcartera in ($idcartera) ";
//    echo $sql;
//	exit();
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
        for($i=0;$i<count($cadena);$i++){
//            $contenido.=utf8_decode($cadena[$i])."\t";
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
?>
