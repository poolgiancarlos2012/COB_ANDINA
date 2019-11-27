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
	$connection = $factoryConnection->getConnection($user,$password);

	$idcartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	
?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">DIRECCIONES</td>
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


	$sql = " SELECT clicar.codigo_cliente AS 'CODIGO_CLIENTE',
		CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
		cli.numero_documento AS 'NUMERO_DOCUMENTO',
		dir.direccion AS 'DIRECCION',
		dir.departamento AS 'DEPARTAMENTO',
		dir.provincia AS 'PROVINCIA',
		dir.distrito AS 'DISTRITO',
		dir.zona AS 'ZONA'
		FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_direccion dir 
		ON dir.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente
		WHERE cli.idservicio = ? AND clicar.idcartera IN ( $idcartera ) and dir.is_new=1";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->execute();
	$i = 0;
	echo '<table>';
	while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
		if( $i == 0 ) {
			echo '<tr>';
			foreach( $row as $index => $value ) {
				echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
			}
			echo '</tr>';
		}

		$style="";
		( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
		echo '<tr>';
		foreach( $row as $key => $value )
		{
			echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
		}
		echo '</tr>';

		$i++;
	}
	echo '</table>';

?>