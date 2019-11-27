<?php

header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=cuenta_por_cliente.xls');
header('Pragma:no-cache');
header('expires:0');

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
$idservicio=$_REQUEST['Servicio'];

$sql="select CONCAT('=\"',cu.codigo_cliente,'\"') as 'idecli',cli.nombre as 'nomcli',CONCAT('=\"',cli.numero_documento,'\"') as 'nro_doc',
GROUP_CONCAT(cu.producto,'@',cu.negocio,'@','=\"',cu.numero_cuenta,'\"','@',cu.moneda,'@',cu.total_deuda,'@',cu.saldo_capital SEPARATOR '|') as 'cuenta'
from ca_cuenta cu
inner join ca_cliente_cartera clicar
on clicar.idcliente_cartera=cu.idcliente_cartera
inner join ca_cliente cli
on cli.idcliente=clicar.idcliente
where cu.idcartera in (?) and clicar.idcartera in (?) GROUP BY cu.codigo_cliente";

$pr=$connection->prepare($sql);
$pr->bindParam(1,$idcartera);
$pr->bindParam(2,$idcartera);
$pr->execute();

$i=0;
$table="<table>";
$table.="<tr><td bgcolor=#1F497D style='color:white;border: 1px solid white;font-weight:bold' colspan=3 align=center>CLIENTE</td>[VALOR]</tr>";
$contenido="";
$mayor=0;
while($row=$pr->fetch(PDO::FETCH_ASSOC)){
    if($i==0){
        $table.="<tr>";
        foreach ($row as $index =>$value){
            if($index!='cuenta'){
                $table.="<td bgcolor=#1F497D style='color:white'>".$index."</td>";
            }
        }

    }
    $contenido.="<tr>";    
    foreach ($row as $index => $value){
        if($index!='cuenta'){
            $contenido.="<td>".utf8_decode($value)."</td>";
        }else{
            $cadena_visita=explode('|', $value);
            if(count($cadena_visita)>$mayor){
                $mayor=count($cadena_visita);
            }
            for($j=0;$j<count($cadena_visita);$j++){
                $cadena_detalle_visita=explode('@',$cadena_visita[$j]);
                for($k=0;$k<count($cadena_detalle_visita);$k++){
                    $contenido.="<td>".utf8_decode($cadena_detalle_visita[$k])."</td>";
                }
            }
        }
    }
    $contenido.="</tr>";    
    $i++;
}
    $cadena_agrupacion="";
    for($i=0;$i<$mayor;$i++){
        $table.="<td bgcolor=#1F497D style='color:white'>Producto</td>
                 <td bgcolor=#1F497D style='color:white'>Negocio</td>        
                 <td bgcolor=#1F497D style='color:white'>Numero_Operacion</td>
                 <td bgcolor=#1F497D style='color:white'>Moneda</td>
                 <td bgcolor=#1F497D style='color:white'>Total_Deuda</td>
                 <td bgcolor=#1F497D style='color:white'>Capital</td>";

        $cadena_agrupacion.="<td bgcolor=#1F497D style='color:white;border: 1px solid white;font-weight:bold' colspan=6 align=center>CUENTA".($i+1)."</td>";
    }
    $table.="</tr>";
    
$table.=$contenido;
$table.="</table>";
$table=str_replace('[VALOR]', $cadena_agrupacion, $table);
echo $table;
?>
