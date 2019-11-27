<?php
	
	//require_once('../../phpincludes/excel/Workbook.php');
	//require_once('../../phpincludes/excel/Worksheet.php');	
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=gestion_llamadas.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	//$workbook = new Workbook("-");
	//$workbook->setName('Reportes');

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	//$xls =& $workbook->add_worksheet('LLamadas');
	
	/*$xls->write_string(1,1,'REPORTE DE LLAMADAS');
	$xls->write_string(2,1,'Fecha: del '.$_GET['FechaInicio'].' al '.$_GET['FechaFin']);
	
	$xls->write_string(4,1,'CODIGO CLIENTE');
	$xls->write_string(4,2,'CLIENTE');
	$xls->write_string(4,3,'NUMERO DOCUMENTO');
	$xls->write_string(4,4,'FECHA LLAMADA');
	$xls->write_string(4,5,'HORA LLAMADA');
	$xls->write_string(4,6,'TELEFONO');
	$xls->write_string(4,7,'TIPO CONTACTO');
	$xls->write_string(4,8,'ESTADO LLAMADA');
	$xls->write_string(4,9,'NUMERO CUENTA');
	$xls->write_string(4,10,'MONEDA');
	$xls->write_string(4,11,'ESTADO CUENTA');
	$xls->write_string(4,12,'FECHA CP');
	$xls->write_string(4,13,'MONTO CP');
	$xls->write_string(4,14,'TELEOPERADOR');
	$xls->write_string(4,15,'OBSERVACION');*/
	
	$countRow = 5;
	$sql = " CALL llamadas( ?,?,?,? )";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
	$pr->bindParam(2,$_GET['Cartera'],PDO::PARAM_INT);
	$pr->bindParam(3,$_GET['FechaInicio'],PDO::PARAM_STR);
	$pr->bindParam(4,$_GET['FechaFin'],PDO::PARAM_STR);
	$pr->execute();
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE GESTION DE LLAMADAS</td>
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
	echo "<table>";
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		
		if( $countRow==5 ) {
			echo "<tr>";
			foreach( $row as $index=>$value ) { 
				if( $index != 'idcliente_cartera' ) {
					echo "<td align=\"center\" style=\"background-color:blue;color:white;\">".$index."</td>";
				}
			}
			echo "</tr>";
		}
		echo "</tr>";
		foreach( $row as $index=>$value ) { 
			if( $index != 'idcliente_cartera' ) {
				echo "<td align=\"center\">".utf8_decode($value)."</td>";
			}
		}
		echo "</tr>";
				
		/*$xls->write_string($countRow,1,$row['CODIGO']);
		$xls->write_string($countRow,2,$row['CLIENTE']);
		$xls->write_string($countRow,3,$row['NUMERO_DOCUMENTO']);
		$xls->write_string($countRow,4,$row['FECHA_LLAMADA']);
		$xls->write_string($countRow,5,$row['HORA_LLAMADA']);
		$xls->write_string($countRow,6,$row['TELEFONO']);
		$xls->write_string($countRow,7,$row['TIPO_CONTACTO']);
		$xls->write_string($countRow,8,$row['ESTADO_LLAMADA']);
		$xls->write_string($countRow,9,$row['NUMERO_CUENTA']);
		$xls->write_string($countRow,10,$row['MONEDA']);
		$xls->write_string($countRow,11,$row['ESTADO_CUENTA']);
		$xls->write_string($countRow,12,$row['FECHA_CP']);
		$xls->write_string($countRow,13,$row['MONTO_CP']);
		$xls->write_string($countRow,14,$row['TELEOPERADOR']);
		$xls->write_string($countRow,15,$row['OBSERVACION']);*/
		
		$countRow++;
	}
	echo "</table>";
	/*$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$xls =& $workbook->add_worksheet('Ultimas Llamadas');
	
	$xls->write_string(1,1,'ULTIMAS LLAMADAS DE CADA CLIENTE');
	$xls->write_string(2,1,'Fecha: del '.$_GET['FechaInicio'].' al '.$_GET['FechaFin']);
	
	$xls->write_string(4,1,'CODIGO CLIENTE');
	$xls->write_string(4,2,'CLIENTE');
	$xls->write_string(4,3,'NUMERO DOCUMENTO');
	$xls->write_string(4,4,'FECHA LLAMADA');
	$xls->write_string(4,5,'HORA LLAMADA');
	$xls->write_string(4,6,'TELEFONO');
	$xls->write_string(4,7,'ESTADO LLAMADA');
	$xls->write_string(4,8,'NUMERO CUENTA');
	$xls->write_string(4,9,'MONEDA');
	$xls->write_string(4,10,'ESTADO CUENTA');
	$xls->write_string(4,11,'FECHA CP');
	$xls->write_string(4,12,'MONTO CP');
	$xls->write_string(4,13,'TELEOPERADOR');
	$xls->write_string(4,14,'OBSERVACION');
	
	$countRow = 5;
	$sql = " CALL ultimas_llamadas_cliente ( ?,?,?,? )";
	
	$prULC = $connection->prepare($sql);
	$prULC->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
	$prULC->bindParam(2,$_GET['Cartera'],PDO::PARAM_INT);
	$prULC->bindParam(3,$_GET['FechaInicio'],PDO::PARAM_STR);
	$prULC->bindParam(4,$_GET['FechaFin'],PDO::PARAM_STR);
	$prULC->execute();
	while( $row = $prULC->fetch(PDO::FETCH_ASSOC) ) {
		
		$xls->write_string($countRow,1,$row['CODIGO']);
		$xls->write_string($countRow,2,$row['CLIENTE']);
		$xls->write_string($countRow,3,$row['NUMERO_DOCUMENTO']);
		$xls->write_string($countRow,4,$row['FECHA_LLAMADA']);
		$xls->write_string($countRow,5,$row['HORA_LLAMADA']);
		$xls->write_string($countRow,6,$row['TELEFONO']);
		$xls->write_string($countRow,7,$row['ESTADO_LLAMADA']);
		$xls->write_string($countRow,8,$row['NUMERO_CUENTA']);
		$xls->write_string($countRow,9,$row['MONEDA']);
		$xls->write_string($countRow,10,$row['ESTADO_CUENTA']);
		$xls->write_string($countRow,11,$row['FECHA_CP']);
		$xls->write_string($countRow,12,$row['MONTO_CP']);
		$xls->write_string($countRow,13,$row['TELEOPERADOR']);
		$xls->write_string($countRow,14,$row['OBSERVACION']);
		
		$countRow++;
	}
	
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	$xls =& $workbook->add_worksheet('Mejores Llamadas');
	
	$xls->write_string(1,1,'MEJORES LLAMADAS DE CADA CLIENTE');
	$xls->write_string(2,1,'Fecha: del '.$_GET['FechaInicio'].' al '.$_GET['FechaFin']);
	
	$xls->write_string(4,1,'CODIGO CLIENTE');
	$xls->write_string(4,2,'CLIENTE');
	$xls->write_string(4,3,'NUMERO DOCUMENTO');
	$xls->write_string(4,4,'FECHA LLAMADA');
	$xls->write_string(4,5,'HORA LLAMADA');
	$xls->write_string(4,6,'TELEFONO');
	$xls->write_string(4,7,'ESTADO LLAMADA');
	$xls->write_string(4,8,'NUMERO CUENTA');
	$xls->write_string(4,9,'MONEDA');
	$xls->write_string(4,10,'ESTADO CUENTA');
	$xls->write_string(4,11,'FECHA CP');
	$xls->write_string(4,12,'MONTO CP');
	$xls->write_string(4,13,'TELEOPERADOR');
	$xls->write_string(4,14,'OBSERVACION');
	
	$countRow = 5;
	$sql = " CALL llamadas_mejor_prioridad ( ?,?,? )";

	$prLMP = $connection->prepare($sql);
	$prLMP->bindParam(1,$_GET['Cartera'],PDO::PARAM_INT);
	$prLMP->bindParam(2,$_GET['FechaInicio'],PDO::PARAM_STR);
	$prLMP->bindParam(3,$_GET['FechaFin'],PDO::PARAM_STR);
	$prLMP->execute();
	while( $row = $prLMP->fetch(PDO::FETCH_ASSOC) ) {
		
		$xls->write_string($countRow,1,$row['CODIGO']);
		$xls->write_string($countRow,2,$row['CLIENTE']);
		$xls->write_string($countRow,3,$row['NUMERO_DOCUMENTO']);
		$xls->write_string($countRow,4,$row['FECHA_LLAMADA']);
		$xls->write_string($countRow,5,$row['HORA_LLAMADA']);
		$xls->write_string($countRow,6,$row['TELEFONO']);
		$xls->write_string($countRow,7,$row['ESTADO_LLAMADA']);
		$xls->write_string($countRow,8,$row['NUMERO_CUENTA']);
		$xls->write_string($countRow,9,$row['MONEDA']);
		$xls->write_string($countRow,10,$row['ESTADO_CUENTA']);
		$xls->write_string($countRow,11,$row['FECHA_CP']);
		$xls->write_string($countRow,12,$row['MONTO_CP']);
		$xls->write_string($countRow,13,$row['TELEOPERADOR']);
		$xls->write_string($countRow,14,$row['OBSERVACION']);
		
		$countRow++;
	}*/

	//$workbook->close();	
	
?>