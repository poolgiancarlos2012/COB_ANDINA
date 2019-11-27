<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ranking_abonado_llamada.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$factoryConnection= FactoryConnection::create('mysqli');
	$connection = $factoryConnection->getConnection();
	
	$idcartera = $_GET['cartera'];
	$anio = (int)$_GET["anio"];
	$mes = (int)$_GET["mes"];
	$diai = (int)$_GET["diai"];
	$diaf = (int)$_GET["diaf"];
	
	$field = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," COUNT( DISTINCT IF( DATE(lla.fecha)='".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', lla.idcliente_cartera,NULL ) ) AS 'ABONADO_".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0  ) ) AS 'LLAMADA_".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}
	
	$sql = " SELECT 
		(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
		".implode(",",$field)."
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
		ON lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN (".$idcartera.") AND lla.tipo = 'LL'
		GROUP BY lla.idusuario_servicio WITH ROLLUP ";
		
	$pr = $connection->query($sql);	
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE ABONADOS POR LLAMADA</td>
            </tr>
            <tr>
            	<td align="right">Reporte generado:</td>
                <td align="left"><?php echo date("Y-m-d"); ?></td>
            </tr>
            <tr>
            	<td align="right">Fechas : </td>
                <td align="left"><?php echo "del ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)." al ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT); ?></td>
            </tr>
            <tr>
            	<td style="height:40px;"></td>
            </tr>
        </table>
	<?php
	$fechas = array();
	echo "<table>";
	echo "<tr>";
	while( $field = $pr->fetch_field() ) {
		if( ((string)$field->name) == 'TELEOPERADOR' ) {
			echo "<td style=\"background-color:blue;color:white;\"></td>";
		}else{
			array_push($fechas,(string)$field->name);
		}
		
	}
	for( $i=0;$i<count($fechas);$i=$i+2 ) {
		$campo = explode("_",$fechas[$i]);
		echo "<td align=\"center\" colspan=\"2\" style=\"background-color:blue;color:white;\">".$campo[1]."</td>";
	}
	echo "</tr>";
	echo "<tr>";
		echo '<td style="background-color:blue;color:white;">TELEOPERADOR</td>';
	for( $i=0;$i<count($fechas);$i++ ) {
		$campo = explode("_",$fechas[$i]);
		echo "<td style=\"background-color:blue;color:white;\">".$campo[0]."</td>";
	}
	echo "</tr>";
	$connection->close();
	
	$factoryConnection2= FactoryConnection::create('mysql');
	$connection2 = $factoryConnection2->getConnection();
	
	$pr2 = $connection2->prepare($sql);
	$pr2->execute();
	$data = $pr2->fetchAll(PDO::FETCH_ASSOC);
	
	for( $i=0;$i<count($data);$i++ ) {
		
		if( $i == ( count($data) - 1 ) ) {
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					if( $index == 'TELEOPERADOR' ) {
						echo '<td style="border:1px solid #000000;" align="center" >TOTALES</td>';
					}else{
						echo '<td style="border:1px solid #000000;" align="center" >'.$value.'</td>';
					}
				}
			echo '</tr>';
		}else{
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					echo '<td style="border:1px solid #000000;" align="center" >'.$value.'</td>';
				}
			echo '</tr>';
		}
		
	}
	echo '</table>';

?>