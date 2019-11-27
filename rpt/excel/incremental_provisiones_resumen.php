<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=Increment_Provisiones_Resumen.xls");
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
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 1  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Enero' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 2  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Febrero' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 3  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Marzo' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 4  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Abril' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 5  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Mayo' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 6  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Junio' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 7  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Julio' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 8  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Agosto' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 9  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Setiembre' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 10  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Octubre' )
				WHEN MONTH( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ) = 11  THEN CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Noviembre' )
				ELSE CONCAT( DAY( LAST_DAY( fecha_creacion - INTERVAL 1 MONTH ) ),' Diciembre' ) END AS MES_ANTERIOR ,
				CASE
				WHEN MONTH( fecha_creacion ) = 1 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Enero' )
				WHEN MONTH( fecha_creacion ) = 2 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Febrero' )
				WHEN MONTH( fecha_creacion ) = 3 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Marzo' )
				WHEN MONTH( fecha_creacion ) = 4 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Abril' )
				WHEN MONTH( fecha_creacion ) = 5 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Mayo' )
				WHEN MONTH( fecha_creacion ) = 6 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Junio' )
				WHEN MONTH( fecha_creacion ) = 7 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Julio' )
				WHEN MONTH( fecha_creacion ) = 8 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Agosto' )
				WHEN MONTH( fecha_creacion ) = 9 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Setiembre' )
				WHEN MONTH( fecha_creacion ) = 10 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Octubre' )
				WHEN MONTH( fecha_creacion ) = 11 THEN CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Noviembre' )
				ELSE CONCAT( DAY( LAST_DAY(fecha_creacion)) , ' Diciembre' ) END MES 
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
			
			CASE 
			WHEN tmp.dias_mora_ant BETWEEN 0 AND 8 THEN 'NORMAL'	
			WHEN tmp.dias_mora_ant BETWEEN 9 AND 30 THEN 'CPP'	
			WHEN tmp.dias_mora_ant BETWEEN 31 AND 60 THEN 'DEFICIENTE'	
			WHEN tmp.dias_mora_ant BETWEEN 61 AND 120 THEN 'DUDOSO'	
			ELSE 'PERDIDA' END AS clasificacion_ant,

			CASE 
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 0 AND 8 THEN 'NORMAL'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 9 AND 30 THEN 'CPP'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 31 AND 60 THEN 'DEFICIENTE'	
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 61 AND 120 THEN 'DUDOSO'	
			ELSE 'PERDIDA' END AS clasificacion,

			TRUNCATE( SUM(
			CASE 
			WHEN tmp.dias_mora_ant BETWEEN 0 AND 8 THEN ( cu.total_deuda * 0.025 )
			WHEN tmp.dias_mora_ant BETWEEN 9 AND 30 THEN ( cu.total_deuda * 0.05 )
			WHEN tmp.dias_mora_ant BETWEEN 31 AND 60 THEN ( cu.total_deuda * 0.25 )
			WHEN tmp.dias_mora_ant BETWEEN 61 AND 120 THEN ( cu.total_deuda * 0.6 )
			ELSE ( cu.total_deuda ) END 
			),2 ) AS provision_ant,
			
			TRUNCATE( SUM(
			CASE 
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 0 AND 8 THEN ( cu.total_deuda * 0.025 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 9 AND 30 THEN ( cu.total_deuda * 0.05 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 31 AND 60 THEN ( cu.total_deuda * 0.25 )
			WHEN ( tmp.dias_mora_ant + DAY(LAST_DAY( cu.fecha_creacion )) ) BETWEEN 61 AND 120 THEN ( cu.total_deuda * 0.6 )
			ELSE ( cu.total_deuda ) END 
			),2 ) AS provision
				
			FROM ca_cuenta cu INNER JOIN tmpinpr_".$date." tmp 
			ON tmp.idcuenta = cu.idcuenta 
			WHERE cu.idcartera = ? 
			GROUP BY 1, 2 ";

	$pr = $connection->prepare( $sql );		
	$pr->bindParam(1, $cartera, PDO::PARAM_INT);
	$pr->execute();

	echo '<table>';
		echo '<tr>';
			echo '<td align="center" colspan="2" style="background-color:#DBE5F1;"></td>';
			echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">Valores</td>';
			echo '<td align="center" style="background-color:#DBE5F1;"></td>';
			echo '<td align="center" ></td>';
			echo '<td align="center" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">Clasif_Inicial</td>';
			echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">Clasif_Proyectada</td>';
			echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">'.$dataFecha[0]['MES_ANTERIOR'].' Incrementak</td>';
			echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">'.$dataFecha[0]['MES'].' Incremental</td>';
			echo '<td align="center" style="font-weight:bold;" >%Avance</td>';
			echo '<td align="center" style="font-weight:bold;" >%Meta</td>';
		echo '</tr>';
	$provision_ant = 0;
	$provision = 0;
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		echo '<tr>';	
		
		foreach( $row as $index => $value ) {
			if( $index == 'provision_ant' ){ $provision_ant = $provision_ant + $value; }
			if( $index == 'provision' ){ $provision = $provision + $value; }
			echo '<td align="center">'.$value.'</td>';
		}
		
		echo '</tr>';
		
	}
	echo '<tr>';	
		echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">Total General</td>';
		echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;"></td>';
		echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">'.$provision_ant.'</td>';
		echo '<td align="center" style="background-color:#DBE5F1;font-weight:bold;">'.$provision.'</td>';
		echo '<td align="center" style="font-weight:bold;">'.(( $provision_ant - $provision )/$provision_ant).'</td>';
		echo '<td align="center" style="font-weight:bold;"></td>';
	echo '</tr>';
	echo '</table>';

	
?>