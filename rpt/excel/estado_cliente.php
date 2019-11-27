<?php
header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=Estado_cliente.xls');
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

$sql="select cli.codigo,CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) as 'Cliente',cli.numero_documento,
cu.numero_cuenta, cu.total_deuda,IF(clicar.id_ultima_llamada=0,'SIN GESTION','GESTIONADO') AS 'Estado',
detcu.fecha_emision,detcu.fecha_vencimiento,detcu.fecha_alta,
cu.ul_fecha as 'Fecha_Ultima_Llamada',(select nombre from ca_carga_final where idcarga_final=cu.ul_carga) as 'Carga_Ultima_Llamada',
(select nombre from ca_final where idfinal=cu.ul_estado) as 'Estado_Ultima_Llamada',
cu.ul_fecha as 'Mejor_Ultima_Llamada',(select nombre from ca_carga_final where idcarga_final=cu.ml_carga) as 'Carga_Mejor_Llamada',
(select nombre from ca_final where idfinal=cu.ml_estado) as 'Estado_Mejor_Llamada'
from ca_cuenta cu
inner join ca_cliente_cartera clicar
on cu.idcliente_cartera=clicar.idcliente_cartera
inner join ca_cliente cli
on cli.idcliente=clicar.idcliente
inner join ca_detalle_cuenta detcu
on detcu.idcuenta=cu.idcuenta
where clicar.idcartera in ($idcartera) and cu.idcartera in ($idcartera)";
$pr=$connection->prepare($sql);
$pr->execute();

$x=0;
$table='<table>';
while($row=$pr->fetch(PDO::FETCH_ASSOC)){
    if($x==0){
        $table.='<tr>';
        foreach ($row as $index => $value) {
            $table.="<td bgcolor=#B3D8F7 align=center style='color:#0A1625;font-weight:bold'>".$index."</td>";    
       }
       $table.='</tr>';
    }
    $table.='<tr>';
    foreach($row as $index => $value){
            $table.="<td>".utf8_decode($value)."</td>";    
    }
    $table.='</tr>';
    $x++;
}
echo $table;
?>
