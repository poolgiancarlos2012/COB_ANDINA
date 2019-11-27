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
	$fecha_inicio = $_GET['fecha_inicio'];
	$fecha_fin = $_GET['fecha_fin'];
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$field = array();
					
	for( $i=6;$i<=20;$i++ ) {
		array_push($field," SUM( IF( HOUR(lla.fecha) = ".$i." ,1,0 ) ) AS '".$i."' ");
	}

	$sql = " SELECT 
		  (SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		  ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
		  ".implode(",",$field).", COUNT(*) AS 'TOTAL_LLAMADAS' 
		  FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
		  ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
		  WHERE clicar.idcartera IN (".$idcartera.") AND lla.tipo = 'LL'
		  AND DATE(lla.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
		  GROUP BY lla.idusuario_servicio  WITH ROLLUP ";
		
	$pr = $connection->prepare($sql);
	$pr->execute();
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">SEMAFORO</td>
            </tr>
            <tr>
            	<td align="right">Reporte generado:</td>
                <td align="left"><?php echo date("Y-m-d"); ?></td>
            </tr>
            <tr>
            	<td align="right">Fechas : </td>
                <td align="left"><?php echo "del ".$fecha_inicio." al ".$fecha_fin; ?></td>
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
						echo '<td align="center" >'.$value.'</td>';
					}
				}
			echo '</tr>';
			
		}else{
			echo '<tr>';
				foreach( $data[$i] as $index => $value ) {
					if( $index == 'TELEOPERADOR' || $index == 'TOTAL_LLAMADAS' ) {
						echo '<td align="center">'.$value.'</td>';
					}else{
						$background = '';
						$cnt = (int)$value;
						if( $cnt<20 ) {
							$background = '#FF0000';
						}else if( $cnt>20 && $cnt<=24 ){
							$background = '#FFFF00';
						}else{
							$background = '#00B050';
						}
						echo '<td align="center" style="background-color:'.$background.';">'.$value.'</td>';
					}
				}
			echo '</tr>';
		}
		
	}
	echo '</table>';
	
?>
