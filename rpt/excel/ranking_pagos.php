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
	
	$cartera = $_GET['Cartera'];
	
	$factoryConnection= FactoryConnection::create('mysqli');
	$connection = $factoryConnection->getConnection();

	$sql = " SELECT 
		(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
		TRUNCATE( SUM( cu.total_deuda ),2 ) AS 'DEUDA_TOTAL',
		TRUNCATE( SUM( cu.monto_Pagado ),2 ) AS 'PAGO',
		ROUND(((TRUNCATE( SUM( cu.monto_Pagado ),2 ))/(TRUNCATE( SUM( cu.total_deuda ),2 ))*100),2) as PORCEN
		FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
		ON cu.idcliente_cartera = clicar.idcliente_cartera  
		WHERE clicar.idcartera IN ($cartera) AND cu.idcartera IN ($cartera) 
		AND clicar.idusuario_servicio != 0 
		GROUP BY clicar.idusuario_servicio ";
		
	$pr = $connection->query($sql);
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE PAGOS</td>
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
		echo "<td style=\"background-color:blue;color:white;\">".(string)$field->name."</td>";
	}
	echo "</tr>";
	while( $row = $pr->fetch_assoc() ) {
		echo "<tr>";
			foreach( $row as $index => $value ) {
				echo "<td>".$value."</td>";
			}
		echo "</tr>";
	}
	echo "</table>";
	
	
?>