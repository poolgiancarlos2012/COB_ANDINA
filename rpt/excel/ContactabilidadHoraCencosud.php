<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=CONTACTABILIDAD_DIARIA.xls");
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
	$fecha_inicio = $_REQUEST['FechaInicio'];
	$fecha_fin = $_REQUEST['FechaFin'];
	
	echo '<table>';
		echo '<tr>';
			echo '<td align="center" colspan="16" style="font-size:18px;" >CONTACTABILIDAD POR HORA</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
			echo '<td style="width:20px;"></td>';
		echo '</tr>';
	echo '<table>';
			
	$sql = " SELECT 
		t1.HORA AS DIA,
		SUM(t2.TOTAL_LLAMADAS) AS TOTAL_LLAMADAS,
		SUM(t2.MEJOR_LLAMADA) AS MEJOR_LLAMADA,
		SUM(t2.CONTACTOS) AS CONTACTOS,
		SUM(t2.CONTACTO_DIRECTO) AS CONTACTO_DIRECTO,
		SUM(t2.CONTACTO_INDIRECTO) AS CONTACTO_INDIRECTO,
		SUM(t2.NO_CONTACTO) AS NO_CONTACTO,
		TRUNCATE(AVG(t2.CONTACTABILIDAD),0) AS CONTACTABILIDAD,
		TRUNCATE(AVG(t2.C_CONTACTO_DIRECTO),0) AS C_CONTACTO_DIRECTO,
		TRUNCATE(AVG(t2.C_CONTACTO_INDIRECTO),0) AS C_CONTACTO_INDIRECTO,
		TRUNCATE(AVG(t2.C_NO_CONTACTO),0) AS C_NO_CONTACTO 
		FROM
		(
		SELECT 7 AS HORA UNION
		SELECT 8 UNION
		SELECT 9 UNION
		SELECT 10 UNION
		SELECT 11 UNION
		SELECT 12 UNION
		SELECT 13 UNION
		SELECT 14 UNION
		SELECT 15 UNION
		SELECT 16 UNION
		SELECT 17 UNION
		SELECT 18 UNION
		SELECT 19 UNION
		SELECT 20
		) t1 LEFT JOIN
		(
		SELECT 
		HOUR(lla.fecha) AS HORA,
		COUNT( * ) AS TOTAL_LLAMADAS,
		COUNT( DISTINCT lla.idcuenta ) AS MEJOR_LLAMADA,
		SUM( IF( fin.idcarga_final IN ( 3,2  ),1,0 ) ) AS CONTACTOS,
		SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) AS CONTACTO_DIRECTO,
		SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) AS CONTACTO_INDIRECTO,
		SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) AS NO_CONTACTO,
		TRUNCATE( ( SUM( IF( fin.idcarga_final IN ( 3,2  ),1,0 ) )/COUNT( DISTINCT lla.idcuenta ) )*100,0 ) AS CONTACTABILIDAD,
		TRUNCATE( ( SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,0) AS C_CONTACTO_DIRECTO,
		TRUNCATE( ( SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,0) AS C_CONTACTO_INDIRECTO,
		TRUNCATE( ( SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100,0) AS C_NO_CONTACTO
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
		ON fin.idfinal = lla.idfinal AND  lla.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND lla.tipo = 'LL' AND lla.estado = 1 AND HOUR(lla.fecha) <= 20 
		AND DATE(lla.fecha) BETWEEN ? AND ? 
		GROUP BY HOUR(lla.fecha)
		) t2 ON t2.HORA = t1.HORA
		GROUP BY t1.HORA WITH ROLLUP  ";
	
	$pr = $connection->prepare($sql);
	$pr->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
	$pr->bindParam(2,$fecha_fin,PDO::PARAM_STR);
	$pr->execute();
	$data = $pr->fetchAll(PDO::FETCH_ASSOC);
	
	$data_c = array();
	foreach( $data as $index => $value ) {
		
		foreach( $value as $k => $v  ) {
			
			if( @!is_array( $data_c[$k] ) ){
				$data_c[$k] = array();
			}
			
			array_push( $data_c[$k], $v );
			
		}
		
	}
	
	$header = array( 
					"TOTAL_LLAMADAS" => "BASE GESTIONADA  (TOTAL LLAMADAS)" ,
					"MEJOR_LLAMADA" => "BASE GESTIONADA  (MEJOR LLAMADA)",
					"CONTACTOS" => "CONTACTOS (CEF + CNE)",
					"CONTACTO_DIRECTO" => "CONTACTO DIRECTOS (CEF)",
					"CONTACTO_DIRECTO_CELULAR" => "CONTACTO DIRECTOS  CELULAR",
					"CONTACTO_DIRECTO_FIJO" => "CONTACTOS DIRECTOS TELEFONO FIJO",
					"CONTACTO_INDIRECTO" => "CONTACTOS INDIRECTOS (CNE)",
					"CONTACTO_INDIRECTO_CELULAR" => "CONTACTOS INDIRECTOS CELULAR",
					"CONTACTO_INDIRECTO_FIJO" => "CONTACTOS INDIRECTOS TELEFONO FIJO",
					"NO_CONTACTO" => "NO CONTACTO (NOC)" ,
					"NO_CONTACTO_CELULAR" => "NO CONTACTO CELULAR",
					"NO_CONTACTO_FIJO" => "NO CONTACTO FIJO",
					"CONTACTABILIDAD" => "CONTACTABILIDAD  B/A",
					"C_CONTACTO_DIRECTO" => "CONTACTO DIRECTO C/A",
					"C_CONTACTO_DIRECTO_CELULAR" => "CONTACTO DIRECTO CELULAR C.1/C",
					"C_CONTACTO_DIRECTO_FIJO" => "CONTACTO DIRECTO TELEFONO FIJO C.2/C",
					"C_CONTACTO_INDIRECTO" => "CONTACTO INDIRECTO D/A",
					"C_CONTACTO_INDIRECTO_CELULAR" => "CONTACTO INDIRECTO CELULAR D.1/D",
					"C_CONTACTO_INDIRECTO_FIJO" => "CONTACTO INDIRECTO TELEFONO FIJO D.2/D",
					"C_NO_CONTACTO" => "NO CONTACTO E/A",
					"C_NO_CONTACTO_CELULAR" => "NO CONTACTO CELULAR E.1/E",
					"C_NO_CONTACTO_FIJO" => "NO CONTACTO TELEFONO FIJO E.2/E"
					);
	
	echo '<table>';
	foreach( $data_c as $index => $value ) {
		echo '<tr>';
			if( $index == 'DIA' ) {
				echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">'.$index.'</td>';
			}else if( $index == 'CONTACTABILIDAD' ) {
				echo '<td align="center" style="background-color:#95B3D7;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_CONTACTO_DIRECTO' ) {
				echo '<td align="center" style="background-color:#0070C0;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_CONTACTO_INDIRECTO' ) {
				echo '<td align="center" style="background-color:#376091;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_NO_CONTACTO' ) {
				echo '<td align="center" style="background-color:#953735;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else{
				echo '<td align="center" style="color:#1F497D;border:1px black solid;">'.$header[$index].'</td>';
			}
		foreach( $value as $k => $v ) {
			
			if( $index == 'DIA' ) {
				if( $v == '' ){
					echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">TOTAL</td>';
				}else{
					echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">'.str_pad($v,2,'0',STR_PAD_LEFT).':00 - '.str_pad($v+1,2,'0',STR_PAD_LEFT).':00'.'</td>';
				}
			}else if( $index == 'CONTACTABILIDAD' ) {
				echo '<td align="center" style="background-color:#95B3D7;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_CONTACTO_DIRECTO' ) {
				echo '<td align="center" style="background-color:#0070C0;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_CONTACTO_INDIRECTO' ) {
				echo '<td align="center" style="background-color:#376091;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_NO_CONTACTO' ) {
				echo '<td align="center" style="background-color:#953735;color:white;border:1px black solid;">'.$v.'%</td>';
			}else{
				echo '<td align="center" style="color:#1F497D;border:1px black solid;">'.$v.'</td>';
			}
			
		}
		echo '</tr>';
		
	}
	echo '</table>';
	
	echo '<table>';
		echo '<tr><td style="width:20px;"></td></tr>';
		echo '<tr><td style="width:20px;"></td></tr>';
	echo '</table>';
	
	$sqlD = " SELECT 
			t1.HORA AS DIA,
			SUM(t2.TOTAL_LLAMADAS) AS TOTAL_LLAMADAS,
			SUM(t2.MEJOR_LLAMADA) AS MEJOR_LLAMADA,
			SUM(t2.CONTACTOS) AS CONTACTOS,
			SUM(t2.CONTACTO_DIRECTO) AS CONTACTO_DIRECTO,
			SUM(t2.CONTACTO_DIRECTO_CELULAR) AS CONTACTO_DIRECTO_CELULAR,
			SUM(t2.CONTACTO_DIRECTO_FIJO) AS CONTACTO_DIRECTO_FIJO,
			SUM(t2.CONTACTO_INDIRECTO) AS CONTACTO_INDIRECTO,
			SUM(t2.CONTACTO_INDIRECTO_CELULAR) AS CONTACTO_INDIRECTO_CELULAR,
			SUM(t2.CONTACTO_INDIRECTO_FIJO) AS CONTACTO_INDIRECTO_FIJO,
			SUM(t2.NO_CONTACTO) AS NO_CONTACTO,
			SUM(t2.NO_CONTACTO_CELULAR) AS NO_CONTACTO_CELULAR,
			SUM(t2.NO_CONTACTO_FIJO) AS NO_CONTACTO_FIJO,
			TRUNCATE( AVG(t2.CONTACTABILIDAD),0 ) AS CONTACTABILIDAD,
			TRUNCATE( AVG(t2.C_CONTACTO_DIRECTO),0 ) AS C_CONTACTO_DIRECTO,
			TRUNCATE( AVG(t2.C_CONTACTO_DIRECTO_CELULAR),0 ) AS C_CONTACTO_DIRECTO_CELULAR,
			TRUNCATE( AVG(t2.C_CONTACTO_DIRECTO_FIJO),0 ) AS C_CONTACTO_DIRECTO_FIJO,
			TRUNCATE( AVG(t2.C_CONTACTO_INDIRECTO),0 ) AS C_CONTACTO_INDIRECTO,
			TRUNCATE( AVG(t2.C_CONTACTO_INDIRECTO_CELULAR),0 ) AS C_CONTACTO_INDIRECTO_CELULAR,
			TRUNCATE( AVG(t2.C_CONTACTO_INDIRECTO_FIJO),0 ) AS C_CONTACTO_INDIRECTO_FIJO,
			TRUNCATE( AVG(t2.C_NO_CONTACTO),0 ) AS C_NO_CONTACTO,
			TRUNCATE( AVG(t2.C_NO_CONTACTO_CELULAR),0 ) AS C_NO_CONTACTO_CELULAR ,
			TRUNCATE( AVG(t2.C_NO_CONTACTO_FIJO),0 ) AS C_NO_CONTACTO_FIJO 
			FROM
			(
			SELECT 7 AS HORA UNION
			SELECT 8 UNION
			SELECT 9 UNION
			SELECT 10 UNION
			SELECT 11 UNION
			SELECT 12 UNION
			SELECT 13 UNION
			SELECT 14 UNION
			SELECT 15 UNION
			SELECT 16 UNION
			SELECT 17 UNION
			SELECT 18 UNION
			SELECT 19 UNION
			SELECT 20
			) t1 LEFT JOIN
			(
			SELECT 
			HOUR(lla.fecha) AS HORA,
			COUNT( * ) AS TOTAL_LLAMADAS,
			COUNT( DISTINCT lla.idcuenta ) AS MEJOR_LLAMADA,
			SUM( IF( fin.idcarga_final IN ( 3,2  ),1,0 ) ) AS CONTACTOS,
			SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) AS CONTACTO_DIRECTO,
			SUM( IF( fin.idcarga_final = 3 AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1 ,0 ) ) AS CONTACTO_DIRECTO_CELULAR,
			SUM( IF( fin.idcarga_final = 3 AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1 ,0 ) ) AS CONTACTO_DIRECTO_FIJO,
			SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) AS CONTACTO_INDIRECTO,
			SUM( IF( fin.idcarga_final = 2 AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1,0 ) ) AS CONTACTO_INDIRECTO_CELULAR,
			SUM( IF( fin.idcarga_final = 2 AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1,0 ) ) AS CONTACTO_INDIRECTO_FIJO,
			SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) AS NO_CONTACTO,
			SUM( IF( fin.idcarga_final = 1  AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1,0 ) ) AS NO_CONTACTO_CELULAR,
			SUM( IF( fin.idcarga_final = 1  AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1,0 ) ) AS NO_CONTACTO_FIJO,
	
			( SUM( IF( fin.idcarga_final IN ( 3,2  ),1,0 ) )/COUNT( DISTINCT lla.idcuenta ) )*100 AS CONTACTABILIDAD,
	
			( SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100 AS C_CONTACTO_DIRECTO,
			( SUM( IF( fin.idcarga_final = 3 AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1 ,0 ) ) / SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) )*100 AS C_CONTACTO_DIRECTO_CELULAR,
			( SUM( IF( fin.idcarga_final = 3 AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1 ,0 ) ) / SUM( IF( fin.idcarga_final = 3 ,1,0 ) ) )*100 AS C_CONTACTO_DIRECTO_FIJO,
	
			( SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100 AS C_CONTACTO_INDIRECTO,
			( SUM( IF( fin.idcarga_final = 2 AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1,0 ) ) / SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) )*100 AS C_CONTACTO_INDIRECTO_CELULAR,
			( SUM( IF( fin.idcarga_final = 2 AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1,0 ) ) / SUM( IF( fin.idcarga_final = 2 ,1,0 ) ) )*100 AS C_CONTACTO_INDIRECTO_FIJO,
	
			( SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) / COUNT( DISTINCT lla.idcuenta ) )*100 AS C_NO_CONTACTO,
			( SUM( IF( fin.idcarga_final = 1  AND SUBSTRING( TRIM(tel.numero),1,1 ) = '9' ,1,0 ) ) / SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) )*100 AS C_NO_CONTACTO_CELULAR,
			( SUM( IF( fin.idcarga_final = 1  AND SUBSTRING( TRIM(tel.numero),1,1 ) != '9' ,1,0 ) ) / SUM( IF( fin.idcarga_final = 1 ,1,0 ) ) )*100 AS C_NO_CONTACTO_FIJO
	
			FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin INNER JOIN ca_telefono tel
			ON tel.idtelefono = lla.idtelefono AND fin.idfinal = lla.idfinal AND  lla.idcliente_cartera = clicar.idcliente_cartera 
			WHERE clicar.idcartera IN ( ".$idcartera." ) AND lla.tipo = 'LL' AND lla.estado = 1 AND HOUR(lla.fecha) <= 20 
			AND DATE(lla.fecha) BETWEEN ? AND ? 
			GROUP BY HOUR(lla.fecha)
			) t2 ON t2.HORA = t1.HORA
			GROUP BY t1.HORA WITH ROLLUP  ";
	
	$prD = $connection->prepare($sqlD);
	$prD->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
	$prD->bindParam(2,$fecha_fin,PDO::PARAM_STR);
	$prD->execute();
	$data_d = $prD->fetchAll(PDO::FETCH_ASSOC);

	$data_det = array();
	foreach( $data_d as $index => $value ) {

		foreach( $value as $k => $v  ) {

			if( @!is_array( $data_det[$k] ) ){
				$data_det[$k] = array();
			}

			array_push( $data_det[$k], $v );

		}

	}
	
	echo '<table>';
	foreach( $data_det as $index => $value ) {
		echo '<tr>';
			if( $index == 'DIA' ) {
				echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">'.$index.'</td>';
			}else if( $index == 'CONTACTABILIDAD' ) {
				echo '<td align="center" style="background-color:#95B3D7;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_CONTACTO_DIRECTO' || $index == 'C_CONTACTO_DIRECTO_CELULAR' || $index == 'C_CONTACTO_DIRECTO_FIJO' ) {
				echo '<td align="center" style="background-color:#0070C0;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_CONTACTO_INDIRECTO' || $index == 'C_CONTACTO_INDIRECTO_CELULAR' || $index == 'C_CONTACTO_INDIRECTO_FIJO' ) {
				echo '<td align="center" style="background-color:#376091;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else if( $index == 'C_NO_CONTACTO' || $index == 'C_NO_CONTACTO_CELULAR' || $index == 'C_NO_CONTACTO_FIJO' ) {
				echo '<td align="center" style="background-color:#953735;color:white;border:1px black solid;">'.$header[$index].'</td>';
			}else{
				echo '<td align="center" style="color:#1F497D;border:1px black solid;">'.$header[$index].'</td>';
			}
		foreach( $value as $k => $v ) {

			if( $index == 'DIA' ) {
				if( $v == '' ){
					echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">TOTAL</td>';
				}else{
					echo '<td align="center" style="background-color:#1F497D;color:white;border:1px black solid;">'.str_pad($v,2,'0',STR_PAD_LEFT).':00 - '.str_pad($v+1,2,'0',STR_PAD_LEFT).':00'.'</td>';
				}
			}else if( $index == 'CONTACTABILIDAD' ) {
				echo '<td align="center" style="background-color:#95B3D7;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_CONTACTO_DIRECTO' || $index == 'C_CONTACTO_DIRECTO_CELULAR' || $index == 'C_CONTACTO_DIRECTO_FIJO' ) {
				echo '<td align="center" style="background-color:#0070C0;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_CONTACTO_INDIRECTO' || $index == 'C_CONTACTO_INDIRECTO_CELULAR' || $index == 'C_CONTACTO_INDIRECTO_FIJO' ) {
				echo '<td align="center" style="background-color:#376091;color:white;border:1px black solid;">'.$v.'%</td>';
			}else if( $index == 'C_NO_CONTACTO' || $index == 'C_NO_CONTACTO_CELULAR' || $index == 'C_NO_CONTACTO_FIJO' ) {
				echo '<td align="center" style="background-color:#953735;color:white;border:1px black solid;">'.$v.'%</td>';
			}else{
				echo '<td align="center" style="color:#1F497D;border:1px black solid;">'.$v.'</td>';
			}

		}
		echo '</tr>';

	}
	echo '</table>';
	
	

?>