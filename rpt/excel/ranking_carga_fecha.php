<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=RANKING_CARGA_FECHA.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
	require_once '../../dao/MARIARankingDAO.php';
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

	$idcartera = $_REQUEST['cartera'];
	$anio = (int)$_REQUEST["anio"];
	$mes = (int)$_REQUEST["mes"];
	$diai = (int)$_REQUEST["diai"];
	$diaf = (int)$_REQUEST["diaf"];
	$tipo = (int)$_REQUEST["tipo"];

	$daoRanking=DAOFactory::getDAORanking('maria'); 


	?>
		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING  POR CARGA Y FECHA</td>
			</tr>
			<tr>
				<td align="right">Reporte generado:</td>
				<td align="left"><?php echo date("Y-m-d"); ?></td>
			</tr>
			<tr>
				<td style="height:40px;"></td>
			</tr>
		</table>
	<?php
	
	$data = $daoRanking->ranking_carga_fecha( $idcartera, $anio, $mes, $diai, $diaf, $tipo ) ;
	
	$dt_tel = array();
	$last_tele = "";
	
	for( $i=0;$i<count($data);$i++ ) {
		if( !array_key_exists( $data[$i]['TELEOPERADOR'], $dt_tel ) ) {
			$dt_tel[ $data[$i]['TELEOPERADOR'] ] = array();
		}
		array_push( $dt_tel[ $data[$i]['TELEOPERADOR'] ] , $data[$i] );
		$last_tele = $data[$i]['TELEOPERADOR'] ; 
	}
	
	echo '<table border="1" >';
	
	if( count($data)>0 ) {
		
		echo '<tr>';
		foreach( $data[0] as $index => $value ) {
			echo '<td align="center" style="background-color:blue;color:white;" >'.$index.'</td>';
		}
		echo '</tr>';
		
	}
	
	foreach( $dt_tel as $index => $value  ) {
		
		echo '<tr>';
			if( $index == $last_tele ) {
				echo '<td rowspan="'.(count($value)-2).'" align="center" valign="middle">'.utf8_decode($index).'</td>';
			}else{
				echo '<td rowspan="'.(count($value)-1).'" align="center" valign="middle">'.utf8_decode($index).'</td>';
			}
			
			for( $i=0;$i<count($value);$i++ ) {
				
				if( $i>0 ){
					if( $index == $last_tele ) {
						if( $i == ( count($value)-1 ) ) { 
							echo '</tr>';
							echo '<tr>';
						}else{
							echo '<tr>';
						}
					}else{
						echo '<tr>';
					}
				}
				
				if( $i == ( count($value)-1 ) ) {
					
					if( $index == $last_tele ) {
						
						foreach( $value[$i] as $k => $v ) {
							if( $k == 'TELEOPERADOR' ) {
								echo '<td align="center" colspan="2"> TOTALES </td>';
							}else if( $k == 'CARGA' ){

							}else{
								echo '<td align="center">'.$v.'</td>';
							}
						}
						
					}else{
						foreach( $value[$i] as $k => $v ) {
							if( $k == 'TELEOPERADOR' ) {
								echo '<td align="center" colspan="2"> TOTAL '.utf8_decode($v).'</td>';
							}else if( $k == 'CARGA' ){
	
							}else{
								echo '<td align="center">'.$v.'</td>';
							}
						}
					}
					
				}else if( $i == ( count($value)-2 ) ){
					
					if( $index == $last_tele ) {
						
						foreach( $value[$i] as $k => $v ) {
							if( $k == 'TELEOPERADOR' ) {
								echo '<td align="center" colspan="2"> TOTAL '.utf8_decode($v).'</td>';
							}else if( $k == 'CARGA' ){
	
							}else{
								echo '<td align="center">'.$v.'</td>';
							}
						}
						
					}else{
						foreach( $value[$i] as $k => $v ) {
							if( $k != 'TELEOPERADOR' ) {
								echo '<td align="center">'.$v.'</td>';
							}
						}
					}
					
				}else{
					foreach( $value[$i] as $k => $v ) {
						if( $k != 'TELEOPERADOR' ) {
							echo '<td align="center">'.$v.'</td>';
						}
					}
				}
				
				echo '</tr>';
			}
		
	}
	
	echo '</table>';
	
	

?>
