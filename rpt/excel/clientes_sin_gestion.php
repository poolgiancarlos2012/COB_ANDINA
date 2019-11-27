<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=clientes_sin_gestionar.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$cartera = $_GET['cartera'];
	$servicio = $_GET['servicio'];
	
	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
	
	$sqlDataCartera = " SELECT idcartera,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera = ? ";
	
	$prData = $connection->prepare($sqlDataCartera);
	$prData->bindParam(1,$cartera,PDO::PARAM_INT);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
	$arrayCuenta = json_decode($dataCuenta,true);
	
	$dataDetalle = str_replace("\\","",$dataCartera[0]['detalle_cuenta']);
	$arrayDetalle = json_decode($dataDetalle,true);
	
	$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
	$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
	$field = array();
	
	for( $i=0;$i<count($arrayCuenta);$i++ ) {
		if( $arrayCuenta[$i]['campoT'] == 'fecha_inicio' ) {
		}else if( $arrayCuenta[$i]['campoT'] == 'fecha_fin' ) {
		}else{
			array_push($field," cu.".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
		}
	}
	
	for( $i=0;$i<count($arrayDetalle);$i++ ) {
		array_push($field," detcu.".$arrayDetalle[$i]['campoT']." AS '".$arrayDetalle[$i]['label']."' ");
	}
	
	$factoryConnection= FactoryConnection::create('mysqli');
	$connection = $factoryConnection->getConnection();

	$sql = " SELECT cli.codigo AS 'CODIGO_CLIENTE', CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
			cli.tipo_documento AS 'TIPO_DOCUMENTO', cli.numero_documento AS 'NUMERO_DOCUMENTO', ".implode(",",$field)."
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_detalle_cuenta detcu 
			ON detcu.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente
			WHERE clicar.idcartera = $cartera AND cu.idcartera = $cartera  AND detcu.idcartera = $cartera AND cli.idservicio = $servicio AND clicar.id_ultima_llamada = 0 ";
		
	$pr = $connection->query($sql);
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE CLIENTES SIN GESTIONAR</td>
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
	echo "<tr>";
	while( $field = $pr->fetch_field() ) {
		echo "<td align=\"center\" style=\"background-color:blue;color:white;\">".(string)$field->name."</td>";
	}
	echo "</tr>";
	while( $row = $pr->fetch_assoc() ) {
		echo "<tr>";
			foreach( $row as $index => $value ) {
				echo "<td align=\"center\">".$value."</td>";
			}
		echo "</tr>";
	}
	echo "</table>";
	
	
?>

