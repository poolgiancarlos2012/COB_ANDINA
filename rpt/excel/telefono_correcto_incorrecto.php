<?php
header('content-type:text/html;charset=UTF-8');
header('content-type:application/vnd.ms-excel;charset=latin');
header('content-disposition:atachment;filename=telefono_correcto_incorrecto.xls');
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

/*$sql="select * from (
select CONCAT('=\"',clicar.codigo_cliente,'\"') as CODIGO_CLIENTE,cli.nombre as CLIENTE,AA.numero_incorrecta,BB.numero_correcta from 
ca_cliente_cartera clicar
inner join ca_cliente cli
on clicar.idcliente=cli.idcliente
left join (select A.codigo_cliente, GROUP_CONCAT(A.numero SEPARATOR '@@') as numero_incorrecta from (
select codigo_cliente,numero,status from ca_telefono where status is not NULL and is_active=1 and status='INCORRECTA' and idcartera in ($idcartera) GROUP BY codigo_cliente,numero)A
GROUP BY A.codigo_cliente)AA
ON clicar.codigo_cliente=AA.codigo_cliente
left join (select B.codigo_cliente, GROUP_CONCAT(B.numero SEPARATOR '@@') as numero_correcta from (
select codigo_cliente,numero,status from ca_telefono where status is not NULL and is_active=1 and status='CORRECTO' and idcartera in ($idcartera) GROUP BY codigo_cliente,numero)B
GROUP BY B.codigo_cliente)BB
ON clicar.codigo_cliente=BB.codigo_cliente
where clicar.idcartera=$idcartera and cli.idservicio=$idservicio
group by cli.codigo)T
where T.numero_incorrecta is not null OR T.numero_correcta is not null";
*/
$sql="select tel.codigo_cliente,CONCAT('=\"',cu.numero_cuenta,'\"') as 'numero_cuenta',cli.nombre,tel.numero,tel.status 
from ca_telefono tel inner join ca_cliente cli
on tel.codigo_cliente=cli.codigo
INNER JOIN ca_cliente_cartera clicar
on cli.idcliente=clicar.idcliente
INNER JOIN ca_cuenta cu
on cu.idcliente_cartera=clicar.idcliente_cartera
where tel.status is not NULL and tel.is_active=1 and tel.status in('CORRECTO','INCORRECTA') and tel.idcartera in ($idcartera)  and cu.idcartera in ($idcartera) and clicar.idcartera in ($idcartera)
GROUP BY tel.codigo_cliente,tel.numero,tel.status
order by nombre";

$pr=$connection->prepare($sql);
//$pr->bindParam(1,$idcartera);
//$pr->bindParam(2,$idcartera);
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
    if($row['status']=='CORRECTO'){
        foreach ($row as $index => $value) {
            if($index=='numero'){
                $table.="<td align=center style='color:blue'>".utf8_decode($value)."</td>";    
            }else{
                $table.="<td align=center>".utf8_decode($value)."</td>";                    
            }
        }
    }else{
        foreach ($row as $index => $value) {
            if($index=='numero'){
                $table.="<td align=center style='color:red'>".utf8_decode($value)."</td>";    
            }else{
                $table.="<td align=center>".utf8_decode($value)."</td>";                    
            }
        }

    }
    $table.='</tr>';
    $x++;
}
echo $table;
?>
