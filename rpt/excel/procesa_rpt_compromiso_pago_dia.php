<?php
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=compromiso_pago.xls");
header("Pragma:no-cache");
header("Expires:0");

require("../../libreria/funciones.php");

$idcartera = $_GET['idcartera'];
$anio = (int)$_GET["anio"];
$mes = (int)$_GET["mes"];
$diai = (int)$_GET["diai"];
$diaf = (int)$_GET["diaf"];
$idcarga = $_GET['idcarga'];

$fechainicio=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diai'], 2, "0", STR_PAD_LEFT);
$fechafin=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diaf'], 2, "0", STR_PAD_LEFT);

$campania=lee("select nombre from ca_campania where idcampania='".$_REQUEST['idcampania']."'");	
$gestion=lee("select nombre_cartera from ca_cartera where idcartera in (".$idcartera.")");
$gestiones=obtener_gestiones_html($gestion);
$dia_gestion=obtener_rango_dias($fechainicio,$fechafin);
$cabecera_dias=crea_cabecera_dias_html($dia_gestion);
$data=data_compromiso_dia_datos($idcartera,$anio,$mes,$diai,$diaf,$idcarga);
$data2=data_compromiso_dia_resumen($idcartera,$anio,$mes,$diai,$diaf,$idcarga);

echo("<style type='text/css'>
td.naranja{border:1px solid #1f497d;border-collapse:collapse;color:#FFFFFF;background-color:#4f81bd;font-weight:bold;}
td.narlight{border:1px solid #1f497d;border-collapse:collapse;color:#000000;background-color:#c2d69a;font-weight:bold;}
td.bdazul{border:1px solid #1f497d;border-collapse:collapse;color:#000000;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");

echo ("<table cellspacing='2' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='2' align='center'><b><h1>COMPROMISOS POR DIA</h1></b></td>
				
			</tr>
			<tr height='30'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>CARTERA</td>
				<td class='narlight'>".$campania[0][0]."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA INICIO</td>
				<td class='narlight'>".$fechainicio."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA FIN</td>
				<td class='narlight'>".$fechafin."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja' valign='top'>CODIGO GESTION</td>
				<td class='narlight'>".$gestiones."
				</td>
				
			</tr>
			<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>
			
			<tr>
				<td></td>
				<td class='naranja'>TELEOPERADOR</td>
				".$cabecera_dias."
				<td class='naranja'>TOTAL CP</td>
				<td></td>
			</tr>
		");
		
			for($i=0;$i<count($data);$i++){
				echo("<tr><td></td>");	
				if($i==(count($data)-1)){
					echo("<td class='naranja'>TOTALES</td>");
					for($j=1;$j<(count($dia_gestion)+2);$j++){
						echo("<td class='naranja'>".$data[$i][$j]."</td>")
						;	
					}
				}else{
					
					for($j=0;$j<(count($dia_gestion)+2);$j++){
						echo("<td>".$data[$i][$j]."</td>");	
					}
					
				}
				echo("</tr>");				
			}


		echo ("<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>VALOR EFECTIVO</td>
				".$cabecera_dias."
				<td></td>
			</tr>
		");
		
			for($i=0;$i<count($data2);$i++){
				
				echo("<tr><td></td>");	
				
				if($i==(count($data2)-1)){
					for($j=0;$j<(count($dia_gestion)+1);$j++){
						echo("<td class='naranja'>".$data2[$i][$j]."</td>");
					}
				}else{
					for($j=0;$j<(count($dia_gestion)+1);$j++){
						if($j==0){
							echo("<td class='naranja'>".$data2[$i][$j]."</td>");
						}else{
							echo("<td>".$data2[$i][$j]."</td>");
						}
					}
				}
				
				
				
				echo("</tr>");				
			}
echo ("<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr></table>");
			

?>
