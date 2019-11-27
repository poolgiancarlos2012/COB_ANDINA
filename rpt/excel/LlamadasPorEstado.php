<?php
	
	header("Content-Type: text/html; charset=UTF-8");	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=llamada_por_estado.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	$servicio = $_GET['Servicio'];
        $fechaInicio = $_GET['FechaInicio'];
        $fechaFin = $_GET['FechaFin'];
        $cartera = $_GET['Cartera'];
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
echo("<style type='text/css'>
td.naranja{border:1px solid #f79646;border-collapse:collapse;color:#000000;background-color:#f79646;font-weight:bold;}
td.narlight{border:0px solid #f79646;border-collapse:collapse;color:#000000;background-color:#fde4d0;}
td.naranja_bdo{border:1px solid #f5872c;border-collapse:collapse;color:#000000;background-color:#f79646;}
td.narlight_bd{border:1px solid #f79646;border-collapse:collapse;color:#000000;background-color:#fde4d0;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");
	
	echo ("<table cellspacing='2' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='3' align='center'><b><h1>CANTIDAD LLAMADAS POR ESTADO</h1></b></td>
				
			</tr>
			<tr height='30'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>REPORTE GENERADO EL:</td>
				<td class='narlight_bd'>".date("Y-m-d H:i:s")."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA INICIO</td>
				<td class='narlight_bd'>".$_GET['FechaInicio']."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA FIN</td>
				<td class='narlight_bd'>".$_GET['FechaFin']."</td>
				
			</tr>
			<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>
			");
			$sqlColumnas = "SELECT fin.idfinal, UPPER(fin.nombre) as nombre
                        FROM ca_final_servicio finser INNER JOIN ca_final fin INNER JOIN ca_clase_final clasfin
                        ON clasfin.idclase_final = fin.idclase_final AND fin.idfinal = finser.idfinal 
                        WHERE finser.estado = 1 AND LOWER(clasfin.nombre) = 'llamada' AND finser.idservicio = $servicio ";
                        
                        $prstm = $connection->prepare($sqlColumnas);
                        $prstm->execute();
                        $colmunas = '';
                        while ($row = $prstm->fetch(PDO::FETCH_ASSOC)) {
                            $colmunas .= '(SUM( IF( lla.idfinal = '.$row['idfinal'].' ,1,0 ) ) ) AS \''.$row['nombre'].'\',';
                        }
			$sql = 'SELECT lla.idusuario_servicio AS IDUSUARIO,
                        ( SELECT CONCAT_WS(" ",usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario 
                        WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS OPERADOR,
                        '.$colmunas.'
                        COUNT(*) AS TOTAL
                        FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla 
                        ON lla.idcliente_cartera = clicar.idcliente_cartera
                        WHERE clicar.idcartera IN ('.$cartera.') AND DATE(lla.fecha_creacion) BETWEEN "'.$fechaInicio.'" AND "'.$fechaFin.'"
                        GROUP BY lla.idusuario_servicio ORDER BY 2';
                        //echo $sql;
                        //$sql = " CALL cantidad_llamadas_por_estado_transaccion ( ?,?,?,? ) ";
			$pr = $connection->prepare($sql);
			/*$pr->bindParam(1,$_GET['Servicio'],PDO::PARAM_INT);
			$pr->bindParam(2,$_GET['Cartera'],PDO::PARAM_INT);
			$pr->bindParam(3,$_GET['FechaInicio'],PDO::PARAM_STR);
			$pr->bindParam(4,$_GET['FechaFin'],PDO::PARAM_STR);*/
			$pr->execute();
			
			$count=0;
			//echo '<table bordercolor="#FFFFFF">';
			while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
				if( $count==0 ) {
					echo '<tr><td></td>';
					foreach( $row as $index => $value ){
						echo '<td align="center" class="naranja_bdo" >'.utf8_decode($index).'</td>';
						//echo '<td align="center">'.$value.'</td>';
					}
				}
				echo '<tr><td></td>';
				foreach( $row as $index => $value ){
					if($count%2==0){
						echo "<td align='center' class='narlight'>".utf8_decode($value)."</td>";
					}else{
						echo "<td align='center'>".utf8_decode($value)."</td>";
					}
				}
				
				$count++;
			}
	echo '</table>';
	
?>