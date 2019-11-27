<?php
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=semaforo.xls");
header("Pragma:no-cache");
header("Expires:0");

require("../../libreria/funciones.php");
$idcartera=$_REQUEST['idcartera'];
$fecha_inicio=$_REQUEST['fecha_inicio'];
$fecha_fin=$_REQUEST['fecha_fin'];
$meta=$_REQUEST['meta'];
$idcarga=$_REQUEST['idcarga'];

$campania=lee("select nombre from ca_campania where idcampania='".$_REQUEST['idcampania']."'");
$gestion=lee("select nombre_cartera from ca_cartera where idcartera in (".$idcartera.")");
$gestiones=obtener_gestiones_html($gestion);

$data=data_semaforo_fija_datos($idcartera,$fecha_inicio,$fecha_fin,$idcarga,$meta);
$data2=data_semaforo_fija_resumen($idcartera,$fecha_inicio,$fecha_fin,$idcarga,$meta);

echo("<style type='text/css'>
td.naranja{border:1px solid #1f497d;border-collapse:collapse;color:#FFFFFF;background-color:#4f81bd;font-weight:bold;}
td.narlight{border:1px solid #1f497d;border-collapse:collapse;color:#000000;background-color:#c2d69a;font-weight:bold;}
td.bdazul{border:1px solid #1f497d;border-collapse:collapse;color:#000000;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");

echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='3' align='center'><b><h1>REPORTE SEMAFORO</h1></b></td>
				
			</tr>
			<tr height='30'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>CARTERA</td>
				<td class='narlight_bd'>".$campania[0][0]."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA INICIO</td>
				<td class='narlight'>".$fecha_inicio."</td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA FIN</td>
				<td class='narlight'>".$fecha_fin."</td>
				
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
				<td></td>
				<td></td>
				<td class='naranja'>Hora</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan=2 class='naranja'>TELEOPERADOR</td>
				<td class='naranja' width='70' align='center'>07:00</td>
				<td class='naranja' width='70' align='center'>08:00</td>
				<td class='naranja' width='70' align='center'>09:00</td>
				<td class='naranja' width='70' align='center'>10:00</td>
				<td class='naranja' width='70' align='center'>11:00</td>
				<td class='naranja' width='70' align='center'>12:00</td>
				<td class='naranja' width='70' align='center'>13:00</td>
				<td class='naranja' width='70' align='center'>14:00</td>
				<td class='naranja' width='70' align='center'>15:00</td>
				<td class='naranja' width='70' align='center'>16:00</td>
				<td class='naranja' width='70' align='center'>17:00</td>
				<td class='naranja' width='70' align='center'>18:00</td>
				<td class='naranja' width='70' align='center'>19:00</td>
				<td class='naranja' width='70' align='center'>TOTAL GENERAL</td>
				<td></td>
			</tr>
			");
			
			for($i=0;$i<count($data);$i++){
				
				if($i==(count($data)-1)){
					echo("<tr><td></td>");
					echo("<td colspan='2' class='naranja'><b>TOTAL GENERAL</b></td>");
					echo("<td align='center' class='naranja'>".$data[$i][1]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][2]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][3]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][4]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][5]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][6]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][7]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][8]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][9]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][10]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][11]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][12]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][13]."</td>");
					echo("<td align='center' class='naranja'>".$data[$i][14]."</td>");					
					echo("</tr>");
				}else{
					echo("<tr><td></td>");
					
					echo("<td colspan=2>".$data[$i][0]."</td>");
					
					echo("<td align='center' bgcolor='".col_semaf($data[$i][1])."'>".$data[$i][1]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][2])."'>".$data[$i][2]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][3])."'>".$data[$i][3]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][4])."'>".$data[$i][4]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][5])."'>".$data[$i][5]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][6])."'>".$data[$i][6]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][7])."'>".$data[$i][7]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][8])."'>".$data[$i][8]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][9])."'>".$data[$i][9]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][10])."'>".$data[$i][10]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][11])."'>".$data[$i][11]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][12])."'>".$data[$i][12]."</td>");
					echo("<td align='center' bgcolor='".col_semaf($data[$i][13])."'>".$data[$i][13]."</td>");
					echo("<td align='center'>".$data[$i][14]."</td>");					
					echo("</tr>");
				}
			}
		echo ("<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>");						
		
			for($i=0;$i<count($data2);$i++){
				
				echo("<tr><td></td>");
				echo("<td colspan='2' class='naranja'>".$data2[$i][0]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][1]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][2]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][3]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][4]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][5]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][6]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][7]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][8]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][9]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][10]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][11]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][12]."</td>");
				echo("<td class='blanco_bd'>".$data2[$i][13]."</td>");
				echo("</tr>");
				
			}
echo("<tr><td></td><td colspan='2' class='naranja'>% Cumplimiento</td>");
for($i=1;$i<14;$i++){
	echo("<td class='blanco_bd'>".(($data2[1][$i]/$data2[2][$i])*100)."%</td>");	
}
echo("</tr>");
			
echo ("<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr></table>");			



?>