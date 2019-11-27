<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=RESPUESTA_RP3.xls");
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

	$carteras = $_REQUEST['carteras'];
	$servicio = $_REQUEST['servicio'];
	$fecha_inicio = $_REQUEST['fecha_inicio'];
	$fecha_fin = $_REQUEST['fecha_fin'];
	

?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">RESPUESTA RP3</td>
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


	$sql = " SELECT 
			cu.dato2 AS SECUENCIA,
			cu.numero_cuenta AS CUENTA,
			'T' AS TIPO,
			IFNULL(( SELECT CASE WHEN status = 'CORRECTA' THEN 1 WHEN status = 'INCORRECTA' THEN 2 ELSE 3   END FROM ca_ll_det_direccion_est WHERE idllamada = lla.idllamada AND status != 'NUEVO' LIMIT 1 ),3) AS CALIFICACION,
			( SELECT IFNULL( CASE WHEN idtipo_referencia IN ( 2 ) THEN 'DO' WHEN idtipo_referencia IN ( 1,4,5,3 ) THEN 'LA' WHEN idtipo_referencia IN ( 6,8,9 ) THEN 'RE' ELSE 'OT' END , 'OT' )  FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS ATRIBUTO,
			IFNULL(( SELECT codigo FROM ca_contacto WHERE idcontacto = lla.idcontacto ),3) AS GESTION_1,
			( SELECT codigo FROM ca_final_servicio WHERE idservicio = ? AND idfinal = lla.idfinal ) AS GESTION_2,
			IFNULL(( SELECT codigo FROM ca_motivo_no_pago WHERE idmotivo_no_pago = lla.idmotivo_no_pago  ),7) AS MOTIVO,
			IFNULL(( SELECT codigo FROM ca_parentesco WHERE idparentesco = lla.idparentesco ),0) AS PARENTESCO,
			lla.observacion AS COMENTARIO,
			IF( ISNULL(lla.fecha_cp) = 0 AND DATE(lla.fecha_cp) != '0000-00-00','S','N' ) AS PROMESA_PAGO,
			IF( ISNULL(lla.fecha_cp) = 0 AND DATE(lla.fecha_cp) != '0000-00-00',DATE_FORMAT(lla.fecha_cp,'%Y%m%d'),'' ) AS FECHA_PROMESA,
			CONCAT( LPAD(HOUR(lla.fecha),2,0),':00 - ', LPAD(HOUR(lla.fecha)+1,2,0),':00 ' , DATE_FORMAT(lla.fecha,'%p') ) AS HORARIO,
			TIME(FROM_UNIXTIME( UNIX_TIMESTAMP(lla.fecha) - ( RAND()*180+1) ))  AS HORA_INI,
			TIME(lla.fecha) AS HORA_FIN,
			( SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS GESTOR
			FROM ca_llamada lla INNER JOIN ca_cuenta cu 
			ON cu.idcuenta = lla.idcuenta 
			WHERE cu.idcartera IN ( ".$carteras." ) AND DATE(lla.fecha) BETWEEN ? AND ? 
			AND lla.estado = 1 AND lla.tipo = 'LL'  ";

	$pr = $connection->prepare($sql);
	$pr->bindParam(1, $servicio, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
	$pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
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
			if( $key == 'SECUENCIA' || $key == 'CUENTA' ) {
				echo '<td style="'.$style.'" align="center">="'.utf8_decode($value).'"</td>';
			}else{
				echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
			}
		}
		echo '</tr>';

		$i++;
	}
	echo '</table>';



?>

