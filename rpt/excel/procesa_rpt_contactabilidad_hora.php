<?php
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=contactabilidad_hora.xls");
header("Pragma:no-cache");
header("Expires:0");

require("../../libreria/funciones.php");
$idcartera=$_REQUEST['idcartera'];
$fecha_inicio=$_REQUEST['fecha_inicio'];
$fecha_fin=$_REQUEST['fecha_fin'];
$campania=lee("select nombre from ca_campania where idcampania='".$_REQUEST['idcampania']."'");
$gestion=lee("select nombre_cartera from ca_cartera where idcartera in (".$idcartera.")");
$gestiones=obtener_gestiones_html($gestion);

echo("<style type='text/css'>
td.naranja{border:1px solid #1f497d;border-collapse:collapse;color:#FFFFFF;background-color:#4f81bd;font-weight:bold;}
td.narlight{border:1px solid #1f497d;border-collapse:collapse;color:#000000;background-color:#c2d69a;font-weight:bold;}
td.bdazul{border:1px solid #1f497d;border-collapse:collapse;color:#000000;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");

$data=data_contactabilidad_por_hora_datos($idcartera,$fecha_inicio,$fecha_fin);
$dataporcentajes=data_contactabilidad_por_hora_porcentajes($idcartera,$fecha_inicio,$fecha_fin);


echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td>
				<td colspan='6' align='center'><b><h1>CONTACTABILIDAD POR HORA</h1></b></td>
				
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
				<td class='naranja'>Hora</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>Estado</td>
				<td class='naranja'>07:00</td>
				<td class='naranja'>08:00</td>
				<td class='naranja'>09:00</td>
				<td class='naranja'>10:00</td>
				<td class='naranja'>11:00</td>
				<td class='naranja'>12:00</td>
				<td class='naranja'>13:00</td>
				<td class='naranja'>14:00</td>
				<td class='naranja'>15:00</td>
				<td class='naranja'>16:00</td>
				<td class='naranja'>17:00</td>
				<td class='naranja'>18:00</td>
				<td class='naranja'>19:00</td>
				<td class='naranja'>Total General</td>
				<td></td>
			</tr>
			");
			for($i=0;$i<count($data);$i++){
				
				if($i==(count($data)-1)){
					echo("<tr><td></td>");
					echo("<td class='naranja'>Total General</td>");
					echo("<td class='naranja'>".$data[$i][1]."</td>");
					echo("<td class='naranja'>".$data[$i][2]."</td>");
					echo("<td class='naranja'>".$data[$i][3]."</td>");
					echo("<td class='naranja'>".$data[$i][4]."</td>");
					echo("<td class='naranja'>".$data[$i][5]."</td>");
					echo("<td class='naranja'>".$data[$i][6]."</td>");
					echo("<td class='naranja'>".$data[$i][7]."</td>");
					echo("<td class='naranja'>".$data[$i][8]."</td>");
					echo("<td class='naranja'>".$data[$i][9]."</td>");
					echo("<td class='naranja'>".$data[$i][10]."</td>");
					echo("<td class='naranja'>".$data[$i][11]."</td>");
					echo("<td class='naranja'>".$data[$i][12]."</td>");
					echo("<td class='naranja'>".$data[$i][13]."</td>");
					echo("<td class='naranja'>".$data[$i][14]."</td>");					
					echo("</tr>");
				}else{
					
					echo("<tr><td></td>");
					echo("<td>".$data[$i][0]."</td>");
					echo("<td>".$data[$i][1]."</td>");
					echo("<td>".$data[$i][2]."</td>");
					echo("<td>".$data[$i][3]."</td>");
					echo("<td>".$data[$i][4]."</td>");
					echo("<td>".$data[$i][5]."</td>");
					echo("<td>".$data[$i][6]."</td>");
					echo("<td>".$data[$i][7]."</td>");
					echo("<td>".$data[$i][8]."</td>");
					echo("<td>".$data[$i][9]."</td>");
					echo("<td>".$data[$i][10]."</td>");
					echo("<td>".$data[$i][11]."</td>");
					echo("<td>".$data[$i][12]."</td>");
					echo("<td>".$data[$i][13]."</td>");
					echo("<td>".$data[$i][14]."</td>");					
					echo("</tr>");
					
					
				}
			}
			
		echo("<tr height='30'><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>Resultado</td>
				<td class='naranja' align='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;07:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;08:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;09:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;11:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;13:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;14:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;16:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;17:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18:00</td>
				<td class='naranja'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;19:00</td>
				<td></td>
			</tr>
		");	
				
			for($i=0;$i<count($dataporcentajes);$i++){
				
					echo("<tr><td></td>");
					echo("<td>".$dataporcentajes[$i][0]."%</td>");
					echo("<td>".$dataporcentajes[$i][1]."%</td>");
					echo("<td>".$dataporcentajes[$i][2]."%</td>");
					echo("<td>".$dataporcentajes[$i][3]."%</td>");
					echo("<td>".$dataporcentajes[$i][4]."%</td>");
					echo("<td>".$dataporcentajes[$i][5]."%</td>");
					echo("<td>".$dataporcentajes[$i][6]."%</td>");
					echo("<td>".$dataporcentajes[$i][7]."%</td>");
					echo("<td>".$dataporcentajes[$i][8]."%</td>");
					echo("<td>".$dataporcentajes[$i][9]."%</td>");
					echo("<td>".$dataporcentajes[$i][10]."%</td>");
					echo("<td>".$dataporcentajes[$i][11]."%</td>");
					echo("<td>".$dataporcentajes[$i][12]."%</td>");
					echo("<td>".$dataporcentajes[$i][13]."%</td>");
					echo("</tr>");
				
			}
				
echo ("</table><br><br>");

?>