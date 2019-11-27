<?php

require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';

date_default_timezone_set('America/Lima');

$cartera=$_GET['Cartera'];
$servicio=$_GET['Servicio'];
$fecha_inicio=$_GET['FechaInicio'];
$fecha_fin=$_GET['FechaFin'];

$factoryConnection=FactoryConnection::create('mysql');
$connection=$factoryConnection->getConnection();

$tabla="<table>";
$sql="SELECT cli.nombre as NOMBRE,tel.numero as TELEFONO,lla.observacion as 
OBSERVACION,fin.nombre as ESTADO FROM ca_llamada lla
INNER JOIN ca_cliente_cartera clicar
ON lla.idcliente_cartera=clicar.idcliente_cartera
INNER JOIN ca_cliente cli
ON clicar.codigo_cliente=cli.codigo
INNER JOIN ca_telefono tel
ON tel.idtelefono =lla.idtelefono
INNER JOIN ca_final fin
ON fin.idfinal=lla.idfinal
where clicar.idcartera in ($cartera) and cli.idservicio in ($servicio) and tel.idcartera in ($cartera) and 
lla.fecha BETWEEN ? and ? ORDER BY 1";

$pr=$connection->prepare($sql);
$pr->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
$pr->bindParam(2,$fecha_fin,PDO::PARAM_STR);
$pr->execute();

$i=0;
while($row = $pr->fetch(PDO::FETCH_ASSOC)){
    
    if($i==0){
        $tabla.="<tr>";
        foreach($row as $index=>$value){
            if($index=='OBSERVACION'){
                $tabla.="<td style='color:#ffffff;background:#1F497D;width:700px'>".$index."</td>";
            }else{
                $tabla.="<td style='color:#ffffff;background:#1F497D'>".$index."</td>";                
            }
        }
        $tabla.="</tr>";
    }
        $tabla.="<tr>";
        foreach($row as $key=>$value){
            $tabla.="<td style='background:#DCE6F1'>".$value."</td>";
        }
        $tabla.="</tr>";
    $i++;
}
$tabla.="</table>";
header('content-type: text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=Reporte_de_Estado_por_Llamada.xls');
header('Pragma:no-cache');
header('expires:0');
echo $tabla;
?>
