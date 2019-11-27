<?php

header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=llamadas_por_cliente.xls');
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
$fecha_inicio=$_REQUEST['FechaInicio'];
$fecha_fin=$_REQUEST['FechaFin'];

$sqlDataCartera = " SELECT idcartera,tabla,archivo,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales 
						FROM ca_cartera WHERE idcartera IN ($idcartera) "; 

	$prData = $connection->prepare($sqlDataCartera);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);

$dataAdicionalesCuenta=str_replace("\\","",$dataCartera[0]['adicionales']);
$arrayAdicionalesCuenta=  json_decode($dataAdicionalesCuenta,true);

$field=array();
for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cliente']);$i++ ) {
    if($arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']=='CARTERA'||$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']=='AGENCIA'){
        array_push($field," clicar.".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][$i]['label']."' ");
    }
}
$str_field=" ".implode(",",$field).", ";
$sql="select CONCAT('=\"',cli.codigo,'\"') as 'CODIGO_CLIENTE',cli.nombre AS 'NOMBRES',cli.numero_documento AS 'NUMERO_DOCUMENTO',$str_field
(select CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) from ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) as 'ASIGNACION',
GROUP_CONCAT(DATE(lla.fecha_creacion),'@@',carfin.nombre,'@@',fin.nombre,'@@',IFNULL(lla.monto_cp,''),'@@',IFNULL(lla.fecha_cp,''),'@@',lla.observacion,'@@',
(select CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre ) from ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=lla.idusuario_servicio) SEPARATOR '||') AS 'LLAMADAS'
from ca_cliente cli
inner join ca_cliente_cartera clicar
on cli.idcliente=clicar.idcliente
inner join ca_llamada lla
on lla.idcliente_cartera=clicar.idcliente_cartera
inner join ca_final fin
on fin.idfinal=lla.idfinal
inner join ca_carga_final carfin
on carfin.idcarga_final=fin.idcarga_final
where clicar.idcartera=? and lla.fecha_creacion BETWEEN '$fecha_inicio' and '$fecha_fin' and lla.estado=1
GROUP BY cli.codigo";

//echo($arrayAdicionalesCuenta['ca_datos_adicionales_cliente'][0]['label']);
//echo(implode(",",$field));
//exit();
$pr=$connection->prepare($sql);
$pr->bindParam(1,$idcartera);
$pr->execute();
$table="<table>";
$table.="<tr><td bgcolor=#1F497D style='color:white;border: 1px solid white;font-weight:bold' colspan=5 align=center>CLIENTE</td>[VALOR]</tr>";
$i=0;
$contenido="";
$mayor=0;
while($row=$pr->fetch(PDO::FETCH_ASSOC)){
    if($i==0){
        $table.="<tr>";
        foreach($row as $index=>$value){
            if($index!='LLAMADAS'){
                $table.="<td bgcolor=#1F497D style='color:white'>".$index."</td>";                
            }
        }
    }
    $contenido.="<tr>";
    foreach($row as $index=>$value){
        if($index!='LLAMADAS'){
            $contenido.="<td>".utf8_decode($value)."</td>";            
        }else{
            $cadena_llamada=explode("||",$value);
            if(count($cadena_llamada)>$mayor){
                $mayor=count($cadena_llamada);
            }
            for($j=0;$j<count($cadena_llamada);$j++){
                $cadena_detalle_llamada=explode("@@",$cadena_llamada[$j]);
                for($k=0;$k<count($cadena_detalle_llamada);$k++){
                    $contenido.="<td>".utf8_decode($cadena_detalle_llamada[$k])."</td>";                    
                }
            }
        }
    }
    $contenido.="</tr>";    
    $i++;
}
    $cadena_agrupacion="";
    for($i=0;$i<$mayor;$i++){
        $table.="<td bgcolor=#1F497D style='color:white'>Fecha_llamada</td>
                 <td bgcolor=#1F497D style='color:white'>Carga</td>
                 <td bgcolor=#1F497D style='color:white'>Estado_Llamada</td>
                 <td bgcolor=#1F497D style='color:white'>Monto_cp</td>
                 <td bgcolor=#1F497D style='color:white'>Fecha_cp</td>                 
                 <td bgcolor=#1F497D style='color:white'>Observacion</td>                 
                 <td bgcolor=#1F497D style='color:white'>Operador</td>                                  
                 ";
        $cadena_agrupacion.="<td bgcolor=#1F497D style='color:white;border: 1px solid white;font-weight:bold' colspan=7 align=center>LLAMADAS".($i+1)."</td>";
    }
    $table.="</tr>";
    
$table.=$contenido;
$table.="</table>";
$table=str_replace('[VALOR]', $cadena_agrupacion, $table);
echo $table;
?>
