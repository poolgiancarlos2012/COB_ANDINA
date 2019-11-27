<?php
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=contactabilidad.xls");
header("Pragma:no-cache");
header("Expires:0");

require("../../libreria/funciones.php");

$cartera=$_REQUEST['cartera'];
$fechainicio=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diai'], 2, "0", STR_PAD_LEFT);
$fechafin=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diaf'], 2, "0", STR_PAD_LEFT);
$campania=lee("select nombre from ca_campania where idcampania='".$_REQUEST['campania']."'");

$gestion=lee("select nombre_cartera from ca_cartera where idcartera in (".$cartera.")");
$dia_gestion=obtener_rango_dias($fechainicio,$fechafin);
$data=leeProcedure("proc_contactabilidad_diaria_llam('".$cartera."','".$fechainicio."','".$fechafin."')");
$data2=leeProcedure("proc_contactabilidad_diaria_vis('".$cartera."','".$fechainicio."','".$fechafin."')");
$gestiones=obtener_gestiones_html($gestion);
$cabecera_dias=crea_cabecera_dias_html($dia_gestion);
$reg_call_valor=cuenta_valor_call_html($data,$dia_gestion);
$reg_visit_valor=cuenta_valor_call_html($data2,$dia_gestion);

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
				<td colspan='6' align='center'><b><h1>HISTORICO DIARIO POR CONTACTABILIDAD</h1></b></td>
				
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
				<td></td>
			</tr>
			<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>Cuenta de Valor</td>
				<td class='naranja'>Fecha</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>Valor</td>
				".$cabecera_dias."
				<td class='naranja'>Total General</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>CEF</td>
				".$reg_call_valor[0]."
			</tr>
			<tr>
				<td></td>
				<td>CNE</td>
				".$reg_call_valor[1]."
			</tr>
			<tr>
				<td></td>
				<td>NOC</td>
				".$reg_call_valor[2]."
			</tr>
			<tr>
				<td></td>
				<td>ENCUESTA</td>
				".$reg_call_valor[3]."
			</tr>
			
			<tr>
				<td></td>
				<td class='naranja'>Total General</td>
				".$reg_call_valor[4]."
				</tr>
			</tr>
			
			<tr height='8'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>CALL</td>
				<td class='naranja'>Fecha</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>Valor</td>
				".$cabecera_dias."
			</tr>
			<tr>
				<td></td>
				<td>CEF</td>
				".$reg_call_valor[5]."
			</tr>
			<tr>
				<td></td>
				<td>CNE</td>
				".$reg_call_valor[6]."
			</tr>
			<tr>
				<td></td>
				<td>NOC</td>
				".$reg_call_valor[7]."
			</tr>
			<tr>
				<td></td>
				<td>ENCUESTA</td>
				".$reg_call_valor[8]."
			</tr>
			
			<tr>
				<td></td>
				<td class='naranja'>CEF + CNE</td>
				".$reg_call_valor[9]."
			</tr>
			<tr height='40'><td></td><td></td><td></td><td></td><td></td></tr>
				");
echo ("</table>");

echo ("<table cellspacing='0' cellpadding='0' border='1' bordercolor='#FFFFFF'>
			<tr>
				<td width='30'></td>
				<td class='naranja'>Cuenta de Valor</td>
				<td class='naranja'>Fecha</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>Valor</td>
				".$cabecera_dias."
				<td class='naranja'>Total General</td>
				<td></td>				
			</tr>
			<tr>
				<td></td>
				<td>CEF</td>
				".$reg_visit_valor[0]."
			</tr>
			<tr>
				<td></td>
				<td>CNE</td>
				".$reg_visit_valor[1]."
			</tr>
			<tr>
				<td></td>
				<td>NOC</td>
				".$reg_visit_valor[2]."
			</tr>
			<tr>
				<td></td>
				<td>ENCUESTA</td>
				".$reg_visit_valor[3]."
			</tr>
			
			<tr>
				<td></td>
				<td class='naranja'>Total General</td>
				".$reg_visit_valor[4]."
				</tr>
			</tr>
			
			<tr height='8'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>CAMPO</td>
				<td class='naranja'>Fecha</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>Valor</td>
				".$cabecera_dias."
			</tr>
			<tr>
				<td></td>
				<td>CEF</td>
				".$reg_visit_valor[5]."
			</tr>
			<tr>
				<td></td>
				<td>CNE</td>
				".$reg_visit_valor[6]."
			</tr>
			<tr>
				<td></td>
				<td>NOC</td>
				".$reg_visit_valor[7]."
			</tr>
			<tr>
				<td></td>
				<td>ENCUESTA</td>
				".$reg_visit_valor[8]."
			</tr>
			
			<tr>
				<td></td>
				<td class='naranja'>CEF + CNE</td>
				".$reg_visit_valor[9]."
			</tr>
			<tr><td></td><td></td><td></td><td></td><td></td></tr>
				");
echo ("</table>");






?>

