<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=TELEFONOS_NUEVOS.xls");
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
	$sql_trace = "";
	/*
	if( $tipo == 'todo' ) {
		$sql_trace = "";
	}else if( $tipo == 'nuevo' ) {
		$sql_trace = " AND tel.is_new = 1 ";
	}else{
		$sql_trace = " AND tel.is_new = 1 ";
	}
	*/
	/*
	
	?>
		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">TELEFONOS</td>
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
	*/

	$sql = "";


		/*$sql = " SELECT 
			clicar.codigo_cliente AS 'CODIGO_CLIENTE',
			CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
			cli.numero_documento AS 'NUMERO_DOCUMENTO',
			cu.numero_cuenta AS NUMERO_CUENTA,
			tel.numero AS 'NUMERO',
			IF( tel.is_new = 1,'NUEVO','CARTERA' ) AS TIPO 
			FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_telefono tel 
			ON tel.idcuenta = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente
			WHERE cli.idservicio = ? AND tel.estado = 1 AND clicar.idcartera IN ( $idcartera ) AND cu.idcartera IN ( $idcartera ) AND tel.idcartera IN ( $idcartera ) and tel.is_new=1";
		*/
			$sql="select clicar.codigo_cliente AS 'CODIGO_CLIENTE',
				CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE_CLIENTE',
				cli.numero_documento AS 'NUMERO_DOCUMENTO',
				cu.numero_cuenta AS NUMERO_CUENTA,
				tel.numero AS 'NUMERO',
				IF( tel.is_new = 1,'NUEVO','CARTERA' ) AS TIPO 
				from ca_telefono tel
				inner join ca_cliente_cartera clicar
				on tel.idcliente_cartera=clicar.idcliente_cartera 
				inner join ca_cliente cli
				on cli.idcliente=clicar.idcliente
				inner join ca_cuenta cu
				on cu.idcuenta=tel.idcuenta
				where tel.is_new=1 and tel.idcartera in ($idcartera) and clicar.idcartera in ($idcartera)";

		$pr = $connection->prepare($sql);
		$pr->bindParam(1, $servicio, PDO::PARAM_INT);
		$pr->execute();
		$i = 0;
		
		while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
			if( $i == 0 ) {
		
				foreach( $row as $index => $value ) {
					$header = explode("-",$index);
					for( $k=0;$k<count($header);$k++ ) {
						echo $header[$k]."\t";
					}
				}
				
				echo "\n";
			}

			foreach( $row as $key => $value )
			{
				$data = explode("|",$value);
				for( $k=0;$k<count($data);$k++ ) {
					echo '="'.str_replace("\n","",str_replace("\t"," ",$data[$k])).'"'."\t";
				}
				
			}
			
			echo "\n";

			$i++;
		}
		
				


	

?>






