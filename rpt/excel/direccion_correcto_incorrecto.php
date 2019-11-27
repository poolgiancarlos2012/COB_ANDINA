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
select CONCAT('=\"',clicar.codigo_cliente,'\"') as CODIGO_CLIENTE,cli.nombre as CLIENTE,AA.direccion_incorrecta,BB.direccion_correcta from 
ca_cliente_cartera clicar
inner join ca_cliente cli
on clicar.idcliente=cli.idcliente
left join (select A.codigo_cliente, GROUP_CONCAT(A.direccion SEPARATOR '@@') as direccion_incorrecta from (
select codigo_cliente,direccion,status from ca_direccion where status is not NULL and estado=1 and status='INCORRECTA' and idcartera in ($idcartera) GROUP BY codigo_cliente,direccion)A
GROUP BY A.codigo_cliente)AA
ON clicar.codigo_cliente=AA.codigo_cliente
left join (select B.codigo_cliente, GROUP_CONCAT(B.direccion SEPARATOR '@@') as direccion_correcta from (
select codigo_cliente,direccion,status from ca_direccion where status is not NULL and estado=1 and status='CORRECTA' and idcartera in ($idcartera) GROUP BY codigo_cliente,direccion)B
GROUP BY B.codigo_cliente)BB
ON clicar.codigo_cliente=BB.codigo_cliente
where clicar.idcartera=$idcartera and cli.idservicio=$idservicio
group by cli.codigo)T
where T.direccion_incorrecta is not null OR T.direccion_correcta is not null
";*/
$sql="
select dir.codigo_cliente,CONCAT('=\"',cu.numero_cuenta,'\"') as 'numero_cuenta',cli.nombre,dir.direccion,dir.status,dir.distrito,dir.provincia,dir.departamento from ca_direccion dir
INNER JOIN ca_cliente cli
on dir.codigo_cliente=cli.codigo
INNER JOIN ca_cliente_cartera clicar
on cli.idcliente=clicar.idcliente
INNER JOIN ca_cuenta cu
on cu.idcliente_cartera=clicar.idcliente_cartera
where dir.status is not NULL and dir.estado=1 and dir.status in('CORRECTA','INCORRECTA') and dir.idcartera in ($idcartera) and cu.idcartera in ($idcartera) and clicar.idcartera in ($idcartera)
GROUP BY dir.codigo_cliente,dir.direccion,dir.status
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
    if($row['status']=='CORRECTA'){
        foreach ($row as $index => $value) {
            if($index=='direccion'){
                $table.="<td align=center style='color:blue'>".utf8_decode($value)."</td>";    
            }else{
                $table.="<td align=center>".utf8_decode($value)."</td>";                    
            }
        }
    }else{
        foreach ($row as $index => $value) {
            if($index=='direccion'){
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
