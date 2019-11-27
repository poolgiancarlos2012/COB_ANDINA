<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=gestion_diaria.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$idcartera = $_GET['cartera'];
	$anio = (int)$_GET["anio"];
	$mes = (int)$_GET["mes"];
	$diai = (int)$_GET["diai"];
	$diaf = (int)$_GET["diaf"];
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$field = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(lla.fecha_cp) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}

	$sql = " SELECT 
		(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
		".implode(",",$field).", COUNT(*) AS 'TOTAL_CP'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN (".$idcartera.") AND lla.tipo = 'LL'
		AND DATE(lla.fecha_cp) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
		GROUP BY lla.idusuario_servicio WITH ROLLUP ";
		
	$pr = $connection->prepare($sql);
	$pr->execute();
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE COMPROMISOS DE PAGO</td>
            </tr>
            <tr>
            	<td align="right">Reporte generado:</td>
                <td align="left"><?php echo date("Y-m-d"); ?></td>
            </tr>
            <tr>
            	<td align="right">Fecha : </td>
                <td align="left"><?php echo "del ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)." al ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
            	<td style="height:40px;"></td>
            </tr>
        </table>
	<?php
	echo '<table>';
	$data = $pr->fetchAll(PDO::FETCH_ASSOC);
	for( $i=0;$i<count($data);$i++ ) {
		if( $i == 0 ) {
			
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					echo '<td align="center" style="background-color:blue;color:white;">'.$index.'</td>';
				}
			echo '</tr>';
			
		}
		
		if( $i == ( count($data)-1 ) ) {
			
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					if( $index == 'TELEOPERADOR' ) {
						echo '<td align="center" style="background-color:blue;color:white;">TOTALES</td>';
					}else{
						echo '<td align="center" style="border:1px solid #000000;" >'.$value.'</td>';
					}
				}
			echo '</tr>';
			
		}else{
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					echo '<td align="center" style="border:1px solid #000000;" >'.$value.'</td>';
				}
			echo '</tr>';
		}
		
	}
	echo '</table>';
	
?>
