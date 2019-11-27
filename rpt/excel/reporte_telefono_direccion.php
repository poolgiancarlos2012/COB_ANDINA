<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=DIRECCIONES.xls");
	header("Pragma:no-cache");
	header("Expires:0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	$confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
	$user = $confCobrast['user_db']['user_rpt'];
	$password = $confCobrast['user_db']['password_rpt'];

	date_default_timezone_set('America/Lima');

	$factoryConnection= FactoryConnection::create('mysql');	
	//$connection = $factoryConnection->getConnection($user,$password);
	$connection = $factoryConnection->getConnection($user,$password);
	$idcartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	
?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE POR TELEFONO Y DIRECCIONES</td>
			</tr>
			<tr>
				<td>Reporte generado:</td>
				<td><?php echo date("Y-m-d"); ?></td>
			</tr>
			<tr>
				<td style="height:40px;"></td>
			</tr>
		</table>
	<?php


/*	$sql = "select codigo as CODIGO_CLIENTE,nombre as NOMBRE,DIRECCION_TELEFONO,distrito as DISTRITO,provincia as PROVINCIA,departamento as DEPARTAMENTO,OBSERVACION,ESTADO from (
select cli.codigo,cli.nombre,'a',dir.direccion as DIRECCION_TELEFONO,dir.distrito,dir.provincia,dir.departamento,cu.uv_observacion as OBSERVACION,
(select fin.nombre from ca_final fin INNER JOIN ca_final_servicio finser on fin.idfinal=finser.idfinal where fin.idfinal=cu.uv_estado and finser.idservicio=$servicio) as ESTADO
from ca_cuenta cu 
INNER JOIN ca_cliente cli ON cu.codigo_cliente=cli.codigo
INNER JOIN ca_direccion dir ON cli.codigo=dir.codigo_cliente
where cu.idcartera in ($idcartera) and dir.idcartera in ($idcartera)
group by cu.numero_cuenta
UNION ALL
select cli.codigo,cli.nombre,'b',tel.numero as DIRECCION_TELEFONO,dir.distrito,dir.provincia,dir.departamento,cu.ul_observacion as OBSERVACION,
(select fin.nombre from ca_final fin INNER JOIN ca_final_servicio finser on fin.idfinal=finser.idfinal where fin.idfinal=cu.ul_estado and finser.idservicio=$servicio) as ESTADO
from ca_cuenta cu 
INNER JOIN ca_cliente cli ON cu.codigo_cliente=cli.codigo
INNER JOIN ca_telefono tel ON cli.codigo=tel.codigo_cliente
INNER JOIN ca_direccion dir ON cli.codigo=dir.codigo_cliente
where cu.idcartera in ($idcartera) and tel.idcartera in ($idcartera) and dir.idcartera in ($idcartera)
group by cu.numero_cuenta)A
order by 2,3 ";*/
        $sql="select * from (
select cli.nombre AS CLIENTE,'a' as ORDEN,tel.numero AS DIRECCION_TELEFONO,'' as distrito,'' as provincia,'' as departamento,
(select lla.observacion from ca_llamada lla where lla.idtelefono=tel.idtelefono limit 1) AS OBSERVACION,
IFNULL((select fin.nombre from ca_llamada lla INNER JOIN ca_final fin on lla.idfinal=fin.idfinal INNER JOIN ca_final_servicio finser on fin.idfinal=finser.idfinal where lla.idtelefono=tel.idtelefono and finser.idservicio=$servicio limit 1),'SIN GESTION') as ESTADO
from ca_cliente_cartera clicar
INNER JOIN ca_telefono tel on clicar.idcliente_cartera=tel.idcliente_cartera
INNER JOIN ca_cliente cli on clicar.codigo_cliente=cli.codigo
where clicar.idcartera in ($idcartera) and tel.idcartera in ($idcartera) 
group by tel.numero       
UNION ALL
select cli.nombre AS CLIENTE,'b' as ORDEN,dir.direccion AS DIRECCION_TELEFONO,dir.distrito,dir.provincia,dir.departamento,
(select vis.observacion from ca_visita vis where vis.iddireccion=dir.iddireccion limit 1) AS OBSERVACION,
IFNULL((select fin.nombre from ca_visita vis INNER JOIN ca_final fin on vis.idfinal=fin.idfinal INNER JOIN ca_final_servicio finser on fin.idfinal=finser.idfinal where vis.iddireccion=dir.iddireccion and finser.idservicio=$servicio limit 1),'SIN GESTION') as ESTADO
from ca_cliente_cartera clicar
INNER JOIN ca_direccion dir on clicar.idcliente_cartera=dir.idcliente_cartera
INNER JOIN ca_cliente cli on clicar.codigo_cliente=cli.codigo
where clicar.idcartera in ($idcartera) and dir.idcartera in ($idcartera) 
group by dir.direccion)A
ORDER BY 1,2";
      
	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->execute();
	$i = 0;
	echo '<table>';
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
		if( $i == 0 ) {
			echo '<tr>';
			foreach( $row as $index => $value ) {
                            if($index!='ORDEN'){
				echo "<td style='color:#ffffff;background:#1F497D'>".$index.'</td>';
                            }
			}
			echo '</tr>';
		}

//		$style="";
//		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
		foreach( $row as $key => $value )
		{
                    if($key!='ORDEN'){
			echo '<td style="background:#DCE6F1" align=center>'.utf8_decode($value).'</td>';
                    }
		}
		echo '</tr>';

		$i++;
	}
	echo '</table>';

?>