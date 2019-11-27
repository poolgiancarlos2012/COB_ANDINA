<?php
    header('content-type:text/html;charset=UTF-8');
    header('content-type:application/vnd.ms-excel;charset=latin');
    header('content-disposition:atachment;filename=telefonos_hotizontal.xls');
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
    
    $sql="select REPLACE(REPLACE(REPLACE(cli.nombre,char(13),''),char(10),''),char(9),'') as nombre,cli.numero_documento,A.numeros from ca_cliente cli
        inner join ca_cliente_cartera clicar
        on cli.idcliente=clicar.idcliente
        inner join (select t1.idcliente_cartera,group_concat(t1.numero SEPARATOR '@@') as numeros 
                                                from (select DISTINCT idcliente_cartera , numero from ca_telefono where idcartera=$idcartera)t1 group by t1.idcliente_cartera)A
        on A.idcliente_cartera=clicar.idcliente_cartera
        where clicar.idcartera=$idcartera";
    
    $pr=$connection->prepare($sql);
    $pr->execute();
    
    $mayor=0;
    $table="DATA \t";
    $table.="CODIGO CLIENTE \t";
    $contenido="";
    while($row=$pr->fetch(PDO::FETCH_ASSOC)){
        $cadena=array();
        $cadena=explode('@@', $row['numeros']);
        $contenido.=utf8_decode($row['nombre'])."\t";
        $contenido.='="'.($row['numero_documento']).'"'."\t";
        for($i=0;$i<count($cadena);$i++){
            $contenido.=utf8_decode($cadena[$i])."\t";
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
