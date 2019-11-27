<?php
require_once '../../conexion/config.php';
require_once '../../conexion/MYSQLConnectionMYSQLI.php';
require_once '../../conexion/MYSQLConnectionPDO.php';

require_once '../../factory/DAOFactory.php';
require_once '../../factory/FactoryConnection.php';
	
date_default_timezone_set('America/Lima');
	
$factoryConnection= FactoryConnection::create('mysql');	
$connection = $factoryConnection->getConnection();

$carteras=$_REQUEST['Cartera'];
$fecha=$_REQUEST['fecha_unica'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=marcaciones.xls");
header("Pragma:no-cache");
header("Expires:0");

$sql_del_all="truncate table tmp_rpte_marcaciones";
$pr_del_all=$connection->prepare($sql_del_all);
if($pr_del_all->execute()){
    $sql_user="insert into tmp_rpte_marcaciones (
        idusuario_servicio,
        teleoperador)
        select
        ususer.idusuario_servicio,
        CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) as teleoperador
        from ca_cliente_cartera clicar 
            left join ca_usuario_servicio ususer on clicar.idusuario_servicio=ususer.idusuario_servicio left join ca_usuario usu on ususer.idusuario=usu.idusuario
        where clicar.idcartera in (".$carteras.") AND clicar.idusuario_servicio!=0
        group by clicar.idusuario_servicio order by 2 ASC";
    $pr_user=$connection->prepare($sql_user);
    if($pr_user->execute()){
        ////////////////////////
        for($i=7;$i<22;$i++){
            $filtro_hora="and hour(lla.fecha)=".$i;
            if($i==21){$filtro_hora="";}
            $sql_cpg="update tmp_rpte_marcaciones t left join 
            (select clicar.idusuario_servicio,count(tran.idtransaccion) as 'CPG'
                from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera 
                        inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion inner join ca_gestion_cuenta gescu on lla.idllamada=gescu.idllamada
                where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio in (select idusuario_servicio from tmp_rpte_marcaciones) 
                        and date(lla.fecha) ='".$fecha."' ".$filtro_hora." and (date(gescu.fecha_cp)>'0000-00-00' or gescu.fecha_cp is not null)
                group by clicar.idusuario_servicio) 
            d on t.idusuario_servicio=d.idusuario_servicio set t.CPG".$i."=if(d.CPG is null,0,d.CPG)";
            $pr_cpg=$connection->prepare($sql_cpg);
            $pr_cpg->execute();
            /////////
            $sql_cef="update tmp_rpte_marcaciones t left join 
            (select clicar.idusuario_servicio,count(tran.idtransaccion) as 'CEF'
                from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera 
                        inner join ca_final fin on fin.idfinal=tran.idfinal inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion 
                where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio in (select idusuario_servicio from tmp_rpte_marcaciones) 
                        and date(lla.fecha) ='".$fecha."' ".$filtro_hora." and fin.idcarga_final='3'
                group by clicar.idusuario_servicio)
            d on t.idusuario_servicio=d.idusuario_servicio set t.CEF".$i."=if(d.CEF is null,0,d.CEF)";
            $pr_cef=$connection->prepare($sql_cef);
            $pr_cef->execute();
            /////////
            $sql_cne="update tmp_rpte_marcaciones t left join 
            (select clicar.idusuario_servicio,count(tran.idtransaccion) as 'CNE' 
                from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera 
                        inner join ca_final fin on fin.idfinal=tran.idfinal inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion 
                where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio in (select idusuario_servicio from tmp_rpte_marcaciones) 
                        and date(lla.fecha) ='".$fecha."' ".$filtro_hora." and fin.idcarga_final='2'
                group by clicar.idusuario_servicio)
            d on t.idusuario_servicio=d.idusuario_servicio set t.CNE".$i."=if(d.CNE is null,0,d.CNE)";
            $pr_cne=$connection->prepare($sql_cne);
            $pr_cne->execute();
            /////////
            $sql_noc="update tmp_rpte_marcaciones t left join 
            (select clicar.idusuario_servicio,count(tran.idtransaccion) as 'NOC' 
                from ca_cliente_cartera clicar inner join ca_transaccion tran on tran.idcliente_cartera=clicar.idcliente_cartera 
                        inner join ca_final fin on fin.idfinal=tran.idfinal inner join ca_llamada lla on lla.idtransaccion=tran.idtransaccion 
                where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio in (select idusuario_servicio from tmp_rpte_marcaciones) 
                        and date(lla.fecha) ='".$fecha."' ".$filtro_hora." and fin.idcarga_final='1'
                group by clicar.idusuario_servicio)
            d on t.idusuario_servicio=d.idusuario_servicio set t.NOC".$i."=if(d.NOC is null,0,d.NOC)";
            $pr_noc=$connection->prepare($sql_noc);
            $pr_noc->execute();
            ///////////
            $sql_mar="update tmp_rpte_marcaciones t left join 
            (select clicar.idusuario_servicio,count(tran.idtransaccion) as 'MAR'  
                from ca_cliente_cartera clicar inner join ca_transaccion tran inner join ca_llamada lla 
                        on lla.idtransaccion=tran.idtransaccion and tran.idcliente_cartera=clicar.idcliente_cartera  
                where clicar.idcartera in (".$carteras.") and clicar.idusuario_servicio in (select idusuario_servicio from tmp_rpte_marcaciones) 
                        and date(lla.fecha) = '".$fecha."' ".$filtro_hora."
                group by clicar.idusuario_servicio)
            d on t.idusuario_servicio=d.idusuario_servicio set t.MAR".$i."=if(d.MAR is null,0,d.MAR)";
            $pr_mar=$connection->prepare($sql_mar);
            $pr_mar->execute();
            ///
            $sql_cg="update tmp_rpte_marcaciones t LEFT join 
            (select idusuario_servicio,ROUND( (CEF".$i."+CNE".$i.")/MAR".$i."*100,2 ) AS 'CG'
                FROM tmp_rpte_marcaciones)
            d on t.idusuario_servicio=d.idusuario_servicio set t.CG".$i."=if(d.CG is null,0,d.CG)";
            $pr_cg=$connection->prepare($sql_cg);
            $pr_cg->execute();
            /////
            $sql_CEPmarc="update tmp_rpte_marcaciones t LEFT join 
            (select idusuario_servicio,ROUND( CPG".$i."/MAR".$i."*100,2 ) AS 'CEP_marc'
                    FROM tmp_rpte_marcaciones)
            d on t.idusuario_servicio=d.idusuario_servicio set t.CEP_marc".$i."=if(d.CEP_marc is null,0,d.CEP_marc)";
            $pr_CEPmarc=$connection->prepare($sql_CEPmarc);
            $pr_CEPmarc->execute();
            //////
            $sql_CEPcont="update tmp_rpte_marcaciones t LEFT join 
            (select idusuario_servicio,ROUND( CPG".$i."/(CEF".$i."+CNE".$i.")*100,2 ) AS 'CEP_cont'
                    FROM tmp_rpte_marcaciones)
            d on t.idusuario_servicio=d.idusuario_servicio set t.CEP_cont".$i."=if(d.CEP_cont is null,0,d.CEP_cont)";
            $pr_CEPcont=$connection->prepare($sql_CEPcont);
            $pr_CEPcont->execute();
        }
    /////////////////////    
    }else{echo("Error al insertar usuarios");}
}else{echo("Error al Truncar Tabla");}




$sql="select * from tmp_rpte_marcaciones";
$pr_sql=$connection->prepare($sql);
$pr_sql->execute();
$count=0;
?>
<style type='text/css'>
    td.cabeza{border:1px solid #ee710b;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
    td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#FFFFFF;background-color:#f79646;font-weight:bold;}
    td.narlight{border:0px solid #fcd5b4;border-collapse:collapse;color:#000000;background-color:#fcd5b4;}
</style>
<?php
echo("<table cellspacing='0' cellpadding='0' border='0' bordercolor='#ffffff'>
    <tr height='60'>
        <td width='30'></td>
        <td colspan='12' align='center'><b><h1>REPORTE MARCACIONES</h1></b></td>
    </tr>
    
    <tr align='center'>
        <td width='30'></td>
        <td rowspan='2' class='cabeza'>Teleoperador</td>");
for($j=7;$j<22;$j++){
    $hora=$j." -- ".($j+1);    
    if($j==21){$hora="Total General x dia";}
    echo("<td colspan='5' class='cabeza'>".$hora."</td>
        <td rowspan='2' class='cabeza'>% Contactabilidad<br>General</td>
        <td rowspan='2' class='cabeza'>% CEP efectiva<br>sobre marcac</td>
        <td rowspan='2' class='cabeza'>% CEP sobre<br>contactos</td>
        ");
}
echo("</tr>
    <tr>
        <td width='30'></td>");
for($j=7;$j<22;$j++){
    echo("<td class='cabeza'>CPG</td>
    <td class='cabeza'>CEF</td>
    <td class='cabeza'>CNE</td>
    <td class='cabeza'>NOC</td>
    <td class='cabeza'>MAR</td>");
}    
echo("</tr>
");

while( $row = $pr_sql->fetch(PDO::FETCH_ASSOC) ) {
    echo '<tr><td></td>';
    foreach( $row as $index => $value ) {
        if($index=='idusuario_servicio'){                
        }else{		
            if( $count%2==0 ) {
                echo '<td align="center" class="narlight" >'.utf8_decode($value).'</td>';
            }else{
                echo '<td align="center">'.utf8_decode($value).'</td>';
            }
        }
    }
    echo '</tr>';

    $count++;
}
echo('</table>');
?>
