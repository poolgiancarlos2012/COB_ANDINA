<?php
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=ranking_estado.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$factoryConnection= FactoryConnection::create('mysqli');
	$connection = $factoryConnection->getConnection();
	
	$idcartera = $_GET['cartera'];
	$anio = (int)$_GET["anio"];
	$mes = (int)$_GET["mes"];
	$diai = (int)$_GET["diai"];
	$diaf = (int)$_GET["diaf"];
	
	$field = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(lla.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0  ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}
	
	$sql = " SELECT 
		(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR',
		fin.nombre AS 'ESTADO',
		".implode(",",$field)." ,
		SUM( IF( DATE(lla.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."',1,0 ) ) AS 'TOTAL_LLAMADAS'
		FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final fin 
		ON fin.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN (".$idcartera.") AND lla.tipo = 'LL'
		GROUP BY lla.idusuario_servicio , lla.idfinal WITH ROLLUP ";
	
	$pr = $connection->query($sql);	
	
	?>
    	<table>
        	<tr>
            	<td colspan="2" style="font-weight:bold;font-size:24px;">RANKING DE ESTADO</td>
            </tr>
            <tr>
            	<td align="right">Reporte generado:</td>
                <td align="left"><?php echo date("Y-m-d"); ?></td>
            </tr>
            <tr>
            	<td align="right">Fecha : </td>
                <td align="left"><?php echo "del ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)." al ".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT); ?></td>
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
	$connection->close();
	
	$factoryConnection2= FactoryConnection::create('mysql');
	$connection2 = $factoryConnection2->getConnection();
	
	$pr2 = $connection2->prepare($sql);
	$pr2->execute();
	$data = $pr2->fetchAll(PDO::FETCH_ASSOC);
	
	$indices = array();
	array_push($indices,$data[0]['TELEOPERADOR']); 
	$obj = array();
	$teleoperador = $data[0]['TELEOPERADOR'];
	$obj[$teleoperador]=array();
	for( $i=0;$i<count($data);$i++ ) {
		if( $data[$i]['TELEOPERADOR'] == $teleoperador ) {
			$estados = array();
			foreach( $data[$i] as $index => $value ) {
				if( $index != 'TELEOPERADOR' ) {
					array_push($estados,$value);
				}
			}
			array_push($obj[$data[$i]['TELEOPERADOR']],$estados);
		}else{
			$teleoperador = $data[$i]['TELEOPERADOR'];
			array_push($indices,$data[$i]['TELEOPERADOR']); 
			$obj[$teleoperador]=array();
			$estados = array();
			foreach( $data[$i] as $index => $value ) {
				if( $index != 'TELEOPERADOR' ) {
					array_push($estados,$value);
				}
			}
			array_push($obj[$data[$i]['TELEOPERADOR']],$estados);
		}
	}
	
	
	for( $i=0;$i<count($indices);$i++ ) {
		if( $i != ( count($indices) - 1 ) ) {
			echo "<tr>";
				echo "<td style=\"border:1px solid #000000\" align=\"center\" valign=\"middle\" rowspan=".(count($obj[$indices[$i]])-1).">".$indices[$i]."</td>";
		}else{
			echo "<tr>";
				echo "<td style=\"border:1px solid #000000\" align=\"center\" valign=\"middle\" rowspan=".(count($obj[$indices[$i]])-2).">".$indices[$i]."</td>";
		}
		
		for( $j=0;$j<count($obj[$indices[$i]]);$j++ ) {
			if( $j!=0 ) {
				echo "<tr>";
			}
			
			if( $i != ( count($indices) - 1 ) ) {
				
				if( $j == ( count($obj[$indices[$i]]) - 1  ) ) {
					echo "<td colspan=\"2\" align=\"center\" style=\"border:1px solid #000000\">TOTAL ".$indices[$i]."</td>";
					for( $k=1;$k<count($obj[$indices[$i]][$j]);$k++ ) {
						echo "<td style=\"border:1px solid #000000\" align=\"center\">".$obj[$indices[$i]][$j][$k]."</td>";
					}
				}else{
					for( $k=0;$k<count($obj[$indices[$i]][$j]);$k++ ) {
						echo "<td style=\"border:1px solid #000000\" align=\"center\">".$obj[$indices[$i]][$j][$k]."</td>";
					}
				}
				
			}else{
				if( $j == ( count($obj[$indices[$i]]) - 1  ) ) {
					echo "<td style=\"border:1px solid #000000\" colspan=\"2\" align=\"center\">TOTALES</td>";
					for( $k=1;$k<count($obj[$indices[$i]][$j]);$k++ ) {
						echo "<td style=\"border:1px solid #000000\" align=\"center\">".$obj[$indices[$i]][$j][$k]."</td>";
					}
				}if( $j == ( count($obj[$indices[$i]]) - 2  ) ) {
					echo "<td style=\"border:1px solid #000000\" colspan=\"2\" align=\"center\">TOTAL ".$indices[$i]."</td>";
					for( $k=1;$k<count($obj[$indices[$i]][$j]);$k++ ) {
						echo "<td style=\"border:1px solid #000000\" align=\"center\">".$obj[$indices[$i]][$j][$k]."</td>";
					}
				}else{
					for( $k=0;$k<count($obj[$indices[$i]][$j]);$k++ ) {
						echo "<td style=\"border:1px solid #000000\" align=\"center\">".$obj[$indices[$i]][$j][$k]."</td>";
					}
				}
			}
			echo "</tr>";
		}
		
	}
	echo "</table>";
	
?>