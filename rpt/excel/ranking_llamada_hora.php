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
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$factoryConnection2= FactoryConnection::create('mysqli');
	$connection2 = $factoryConnection2->getConnection();
	
	$idservicio = $_GET['servicio'];
	$idcartera = $_GET['cartera'];
	$fecha_inicio = $_GET['fecha_inicio'];
	$fecha_fin = $_GET['fecha_fin'];
	
	$field = array();
	
	$sql = " SELECT DISTINCT carfin.idcarga_final, carfin.nombre
			FROM ca_final_servicio finser INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin
			ON carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = finser.idfinal
			WHERE finser.idservicio = $idservicio ";
	
	$pr = $connection->prepare($sql);
	$pr->execute();
	$dataCarga = $pr->fetchAll(PDO::FETCH_ASSOC);
	
	for( $j=6; $j<19;$j++ ) {
		for( $i=0; $i<count($dataCarga);$i++ ) {
			array_push($field," SUM( IF( HOUR(lla.fecha) = ".$j." AND fin.idcarga_final = ".$dataCarga[$i]['idcarga_final']." ,1,0 ) ) AS '".$dataCarga[$i]['nombre']."_".str_pad($j,2,'0',STR_PAD_LEFT).":00 ".str_pad(($j+1),2,'0',STR_PAD_LEFT).":00' ");
		}
	}
	
	$sql = " SELECT
		(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
		".implode(",",$field)." , COUNT(*) AS 'TOTAL_LLAMADAS'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idtransaccion 
		WHERE clicar.idcartera IN (".$idcartera.") AND lla.tipo = 'LL'
		AND DATE( lla.fecha ) BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY lla.idusuario_servicio WITH ROLLUP ";
		
	$fechas = array();
	$pr2 = $connection2->query($sql);
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE LLAMADA POR HORA</td>
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
	echo '<tr>';
	while( $field = $pr2->fetch_field() ) {
		if( ((string)$field->name) == 'TELEOPERADOR' ) {
			echo '<td style="background-color:blue;color:white;"></td>';
		}else{
			array_push($fechas,((string)$field->name));
		}
	}
	
	for( $i=0;$i<count($fechas);$i=$i+count($dataCarga) ) {
		$campo = explode("_",$fechas[$i]);
		if( $campo[1] == 'LLAMADAS' ) {
			echo '<td align="center" style="background-color:blue;color:white;">'.$campo[1].'</td>';
		}else{
			echo '<td align="center" colspan="'.count($dataCarga).'" style="background-color:blue;color:white;">'.$campo[1].'</td>';
		}
	}
	echo '</tr>';
	echo '<tr>';
		echo '<td style="background-color:blue;color:white;">TELEOPERADOR</td>';
	for( $i=0;$i<count($fechas);$i++ ) {
		$campo = explode("_",$fechas[$i]);
		echo '<td align="center" style="background-color:blue;color:white;">'.$campo[0].'</td>';
	}
	echo '</tr>';
	
	$pr = $connection->prepare($sql);
	$pr->execute();
	$data = $pr->fetchAll(PDO::FETCH_ASSOC);
	
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
