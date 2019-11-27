<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=Increment_Provisiones.xls");
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

	$cartera = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	$date = date("Y_m_d_H_i_s");

	$sqlFecha = " SELECT 
				CASE
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 1  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' ENERO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 2  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' FEBRERO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 3  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' MARZO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 4  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' ABRIL' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 5  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' MAYO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 6  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' JUNIO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 7  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' JULIO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 8  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' AGOSTO' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 9  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' SETIEMBRE' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 10  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' OCTUBRE' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 11  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' NOVIEMBRE' )
				ELSE CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' DICIEMBRE' ) END AS MES_ANTERIOR ,
				CASE
				WHEN MONTH( fecha_creacion ) = 1 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' ENERO' )
				WHEN MONTH( fecha_creacion ) = 2 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' FEBRERO' )
				WHEN MONTH( fecha_creacion ) = 3 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' MARZO' )
				WHEN MONTH( fecha_creacion ) = 4 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' ABRIL' )
				WHEN MONTH( fecha_creacion ) = 5 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' MAYO' )
				WHEN MONTH( fecha_creacion ) = 6 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' JUNIO' )
				WHEN MONTH( fecha_creacion ) = 7 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' JULIO' )
				WHEN MONTH( fecha_creacion ) = 8 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' AGOSTO' )
				WHEN MONTH( fecha_creacion ) = 9 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' SETIEMBRE' )
				WHEN MONTH( fecha_creacion ) = 10 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' OCTUBRE' )
				WHEN MONTH( fecha_creacion ) = 11 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' NOVIEMBRE' )
				ELSE CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' DICIEMBRE' ) END MES 
				FROM ca_cartera WHERE idcartera = ? ";
	
	$prFecha = $connection->prepare( $sqlFecha );
	$prFecha->bindParam( 1, $cartera, PDO::PARAM_INT );
	$prFecha->execute();
	$dataFecha = $prFecha->fetchAll(PDO::FETCH_ASSOC);

	$sqlTmp = " CREATE TEMPORARY TABLE tmpinpr_".$date." AS
		SELECT
		idcuenta,
		idcartera,
		IFNULL( ( SELECT dias_mora FROM ca_pago WHERE idcartera != cu.idcartera AND numero_cuenta = cu.numero_cuenta AND DATE(fecha_creacion) BETWEEN ( LAST_DAY( cu.fecha_creacion - INTERVAL 1 MONTH ) - INTERVAL 2 DAY ) and ( LAST_DAY( cu.fecha_creacion - INTERVAL 1 MONTH ) + INTERVAL 2 DAY ) LIMIT 1 ),0 ) AS dias_mora_ant
		FROM ca_cuenta cu 
		WHERE cu.idcartera = ?  ";

	$prTmp = $connection->prepare( $sqlTmp );
	$prTmp->bindParam(1, $cartera, PDO::PARAM_INT);
	$prTmp->execute();

	$sqlUptTmp = " ALTER TABLE tmpinpr_".$date." ADD INDEX( idcuenta ) ";

	$prUptTmp = $connection->prepare( $sqlUptTmp );
	$prUptTmp->execute();

	$sql = " SELECT 
			cu.numero_cuenta AS NUMERO_CUENTA,
			cu.total_deuda AS DEUDA_CAPITAL,
			tmp.dias_mora_ant,
			CASE 
			WHEN tmp.dias_mora_ant BETWEEN 0 AND 8 THEN 'NORMAL'	
			WHEN tmp.dias_mora_ant BETWEEN 9 AND 30 THEN 'CPP'	
			WHEN tmp.dias_mora_ant BETWEEN 31 AND 60 THEN 'DEFICIENTE'	
			WHEN tmp.dias_mora_ant BETWEEN 61 AND 120 THEN 'DUDOSO'	
			ELSE 'PERDIDA' END AS clasificacion_ant,
			CASE 
			WHEN tmp.dias_mora_ant BETWEEN 0 AND 8 THEN ( cu.total_deuda * 0.025 )
			WHEN tmp.dias_mora_ant BETWEEN 9 AND 30 THEN ( cu.total_deuda * 0.05 )
			WHEN tmp.dias_mora_ant BETWEEN 31 AND 60 THEN ( cu.total_deuda * 0.25 )
			WHEN tmp.dias_mora_ant BETWEEN 61 AND 120 THEN ( cu.total_deuda * 0.6 )
			ELSE ( cu.total_deuda ) END AS provision_ant,
			( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) AS dias_mora ,
			CASE 
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 0 AND 8 THEN 'NORMAL'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 9 AND 30 THEN 'CPP'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 31 AND 60 THEN 'DEFICIENTE'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 61 AND 120 THEN 'DUDOSO'	
			ELSE 'PERDIDA' END AS clasificacion,
			CASE 
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 0 AND 8 THEN ( cu.total_deuda * 0.025 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 9 AND 30 THEN ( cu.total_deuda * 0.05 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 31 AND 60 THEN ( cu.total_deuda * 0.25 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 61 AND 120 THEN ( cu.total_deuda * 0.6 )
			ELSE ( cu.total_deuda ) END AS provision
				
			FROM ca_cuenta cu INNER JOIN tmpinpr_".$date." tmp 
			ON tmp.idcuenta = cu.idcuenta 
			WHERE cu.idcartera = ? ";

	$pr = $connection->prepare( $sql );		
	$pr->bindParam(1, $cartera, PDO::PARAM_INT);
	$pr->execute();

	$header = array(
					1=>array('ENERO',31),
					2=>array('FEBRERO',28),
					3=>array('MARZO',31),
					4=>array('ABRIL',30),
					5=>array('MAYO',31),
					6=>array('JUNIO',30),
					7=>array('JULIO',31),
					8=>array('AGOSTO',30),
					9=>array('SETIEMBRE',31),
					10=>array('OCTUBRE',30),
					11=>array('NOVIEMBRE',31),
					12=>array('DICIEMBRE',30)
					);

	echo '<table border="1">';
		echo '<tr>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td colspan="3" align="center" style="background-color:#75923C;">Clasif. Y Provision al '.$dataFecha[0]['MES_ANTERIOR'].'</td>';
			echo '<td colspan="3" align="center" style="background-color:#C2D69A;">Proyeccion al '.$dataFecha[0]['MES'].'</td>';
			echo '<td colspan="2" align="center" style="background-color:#DBEEF3;">Conclusion</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" style="background-color:#4F6228;">CLIENTE</td>';
			echo '<td align="center" style="background-color:#4F6228;">DEUDA CAPITAL S/</td>';
			echo '<td align="center" style="background-color:#75923C;">DIAS MORA</td>';
			echo '<td align="center" style="background-color:#75923C;">CLASIFICACION</td>';
			echo '<td align="center" style="background-color:#75923C;">PROVISION S/</td>';
			echo '<td align="center" style="background-color:#C2D69A;">DIAS MORA</td>';
			echo '<td align="center" style="background-color:#C2D69A;">CLASIFICACION</td>';
			echo '<td align="center" style="background-color:#C2D69A;">PROVISION S/</td>';
			echo '<td align="center" style="background-color:#DBEEF3;">MIGRARA DE CLASIFICACION</td>';
			echo '<td align="center" style="background-color:#DBEEF3;">INCREMENTAL PROVISION S/</td>';
		echo '</tr>';
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		echo '<tr>';	
		$clasificacion_ant = "";
		$clasificacion = "";
		$provision_ant = 0;
		$provision = 0;
		foreach( $row as $index => $value ) {
			if( $index == 'clasificacion_ant' ){ $clasificacion_ant = $value; }
			if( $index == 'clasificacion' ){ $clasificacion = $value; }
			if( $index == 'provision_ant' ){ $provision_ant = $value; }
			if( $index == 'provision' ){ $provision = $value; }
			echo '<td align="center">'.$value.'</td>';
		}
		$dat_clas = "";
		if( $clasificacion_ant == $clasificacion ) {
			$dat_clas = "No";
		}else{
			$dat_clas = "Si";
		}
		echo '<td align="center">'.$dat_clas.'</td>';
		echo '<td align="center">'.($provision - $provision_ant).'</td>';
		echo '</tr>';
		
	}
	echo '</table>';

	
?>