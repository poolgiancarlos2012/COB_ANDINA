<?php

header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=visita_por_cliente_cdo.xls');
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

$sql="SELECT cli.nombre,CONCAT('=\"',cli.codigo,'\"') AS codigo,CONCAT('=\"',cu.numero_cuenta,'\"') AS numero_cuenta,
	GROUP_CONCAT(IFNULL(DATE(vis.fecha_visita),''),'@@',carfin.nombre,'@@',fin.nombre,'@@',IFNULL(fs.prioridad,''),
	'@@',vis.observacion,'@@',IFNULL(dir.distrito,''),'@@',IFNULL(dir.provincia,''),'@@',IFNULL(dir.departamento,'') SEPARATOR '||') AS VISITAS 
FROM ca_cliente cli
	INNER JOIN ca_cliente_cartera clicar ON cli.idcliente=clicar.idcliente
	INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
	INNER JOIN ca_visita vis ON vis.idcuenta=cu.idcuenta
	INNER JOIN ca_final fin ON fin.idfinal=vis.idfinal
	INNER JOIN ca_final_servicio fs ON fin.idfinal=fs.idfinal
	INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final
	INNER JOIN ca_direccion dir ON dir.iddireccion=vis.iddireccion
WHERE cu.idcartera=? AND clicar.idcartera=? AND vis.estado=1
GROUP BY cu.idcuenta";

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
            if($index!='VISITAS'){
                $table.="<td bgcolor=#1F497D style='color:white'>".$index."</td>";
            }
        }

    }
    $contenido.="<tr>";    
    foreach ($row as $index => $value){
        if($index!='VISITAS'){
            $contenido.="<td>".utf8_decode($value)."</td>";
        }else{
            $cadena_visita=explode('||', $value);
            if(count($cadena_visita)>$mayor){
                $mayor=count($cadena_visita);
            }
            for($j=0;$j<count($cadena_visita);$j++){
                $cadena_detalle_visita=explode('@@',$cadena_visita[$j]);
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
        $table.="<td bgcolor=#1F497D style='color:white'>Fecha_visita</td>
                 <td bgcolor=#1F497D style='color:white'>Carga</td>
                 <td bgcolor=#1F497D style='color:white'>Estado_Visita</td>
                 <td bgcolor=#1F497D style='color:white'>Prioridad</td>
                 <td bgcolor=#1F497D style='color:white'>Observacion</td>
                 <td bgcolor=#1F497D style='color:white'>Distrito</td>                 
                 <td bgcolor=#1F497D style='color:white'>Provincia</td>                 
                 <td bgcolor=#1F497D style='color:white'>Departamento</td>                                  
                 ";
        $cadena_agrupacion.="<td bgcolor=#1F497D style='color:white;border: 1px solid white;font-weight:bold' colspan=7 align=center>VISITA".($i+1)."</td>";
    }
    $table.="</tr>";
    
$table.=$contenido;
$table.="</table>";
$table=str_replace('[VALOR]', $cadena_agrupacion, $table);
echo $table;
?>
