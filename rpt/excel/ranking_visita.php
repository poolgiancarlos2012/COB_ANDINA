<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ranking_visita.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$factoryConnection2= FactoryConnection::create('mysqli');
	$connection2 = $factoryConnection2->getConnection();

	
	$idcartera = $_GET['cartera'];
	$anio = (int)$_GET["anio"];
	$mes = (int)$_GET["mes"];
	$diai = (int)$_GET["diai"];
	$diaf = (int)$_GET["diaf"];
						
	$field = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(vis.fecha_visita) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");					
	}
	
	$sql = " SELECT 
		(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador LIMIT 1 ) AS 'NOTIFICADOR',
		".implode(",",$field).", COUNT(*) AS 'VISITAS' 
		FROM ca_cliente_cartera clicar INNER JOIN ca_visita vis 
		ON vis.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN (".$idcartera.") AND vis.tipo = 'VIS' 
		AND DATE(vis.fecha_visita) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
		GROUP BY vis.idnotificador WITH ROLLUP ";
		
	$pr2 = $connection2->query($sql);
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE VISITAS</td>
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
	echo '<table>';
	echo '<tr>';
	while( $field = $pr2->fetch_field() ) {
		echo '<td align="center" style="background-color:blue;color:white;">'.((string)$field->name).'</td>';
	}
	echo '</tr>';
	$pr2->close();
	$pr = $connection->prepare($sql);
	$pr->execute();
	$data = $pr->fetchAll(PDO::FETCH_ASSOC);
	for( $i=0;$i<count($data);$i++ ) {
		
		if( $i == (count($data)-1) ) {
			echo '<tr>';
			foreach( $data[$i] as $index => $value ) {
				if( $index == 'NOTIFICADOR' ) {
					echo '<td align="center" style="border:1px solid #000000;">TOTALES</td>';
				}else{
					echo '<td align="center" style="border:1px solid #000000;">'+$value+'</td>';
				}
			}
			echo '</tr>';
		}else{
			echo '<tr>';
			foreach( $data[$i] as $index => $value ) {
				echo '<td align="center" style="border:1px solid #000000;">'+$value+'</td>';
			}
			echo '</tr>';
		}
		
	}
	echo '</table>';
	
?>

