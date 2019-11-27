<?php
header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=Direcciones.xls');
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

$sql="select cli.codigo,cli.nombre,dir.direccion,status as 'estado' from ca_direccion dir 
    inner join ca_cliente_cartera clicar
    on clicar.idcliente_cartera=dir.idcliente_cartera
    inner join ca_cliente cli
    on cli.idcliente=clicar.idcliente
    where dir.idcartera in ($idcartera) and clicar.idcartera in ($idcartera) and dir.direccion !='' and dir.direccion!='0'
    group by cli.codigo,dir.direccion
    order by cli.nombre";
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
