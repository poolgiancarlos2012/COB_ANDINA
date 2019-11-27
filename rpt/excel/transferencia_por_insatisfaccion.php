<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=transferencia_por_insatisfaccion.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$idcartera = $_GET['idcartera'];
	$idfinal = $_GET['idfinal'];
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$sql = " SELECT cu.numero_cuenta AS 'INSCRIPCION',cu.telefono AS 'TELEFONO',
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
			DATE(tran.fecha) AS 'FECHA_TRANSFERENCIA',
			fin.nombre AS 'MOTIVO_TRANSFERENCIA',
			( SELECT fecha_fin FROM ca_cartera WHERE idcartera = clicar.idcartera ) AS 'FECHA_CIERRE_CARTERA',
			lla.nombre_contacto AS 'NOMBRE_PERSONA_CONTACTO',
			tran.observacion AS 'OBSERVACION'
			FROM ca_cliente cli 
			INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente = cli.idcliente
			INNER JOIN ca_cuenta cu  ON cu.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_transaccion tran ON tran.idcliente_cartera = clicar.idcliente_cartera
			INNER JOIN ca_llamada lla ON lla.idtransaccion = tran.idtransaccion
			INNER JOIN ca_final fin ON fin.idfinal = tran.idfinal
			WHERE clicar.idcartera IN ( ".$idcartera." ) AND tran.idfinal IN ( ".$idfinal." )
			GROUP BY cu.idcuenta ";
	
	$pr = $connection->prepare($sql);
	$pr->execute();
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">TRANSFERENCIA POR INSATISFACCION</td>
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
	$count = 0;
	echo "<table>";
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		$background_color = '';
		( $count%2 == 0 )?$background_color = '#B8CCE4':$background_color='#DBE5F1';
		if( $count == 0 ) {
			echo '<tr>';
				foreach( $row as $index => $value ) {
					echo '<td align="center" style="background-color:#4F81BD;color:#FFFFFF;border:1px solid #FFFFFF;">'.$index.'</td>';
				}
			echo '</tr>';
		}
		echo '<tr>';
			foreach( $row as $index => $value ) {
				echo 
				'<td align="center" style="background-color:'.$background_color.';border:1px solid #FFFFFF;">'.$value.'</td>';
			}
		echo '</tr>';
		$count++;
	}
	echo "</table>";
	
?>

