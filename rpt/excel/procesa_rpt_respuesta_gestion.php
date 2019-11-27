<?php
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=respuesta_gestion.xls");
header("Pragma:no-cache");
header("Expires:0");

require("../../libreria/funciones.php");

$idcartera = $_GET['idcartera'];
$anio = (int)$_GET["anio"];
$mes = (int)$_GET["mes"];
$diai = (int)$_GET["diai"];
$diaf = (int)$_GET["diaf"];

$fechainicio=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diai'], 2, "0", STR_PAD_LEFT);
$fechafin=$_REQUEST['anio']."-".str_pad($_REQUEST['mes'], 2, "0", STR_PAD_LEFT)."-".str_pad($_REQUEST['diaf'], 2, "0", STR_PAD_LEFT);

$campania=lee("select nombre from ca_campania where idcampania='".$_REQUEST['idcampania']."'");	
$gestion=lee("select nombre_cartera from ca_cartera where idcartera in (".$idcartera.")");
$gestiones=obtener_gestiones_html($gestion);
$dia_gestion=obtener_rango_dias($fechainicio,$fechafin);
$cabecera_dias=crea_cabecera_dias_html($dia_gestion);

echo("<style type='text/css'>
td.naranja{border:1px solid #1f497d;border-collapse:collapse;color:#FFFFFF;background-color:#4f81bd;font-weight:bold;}
td.narlight{border:1px solid #1f497d;border-collapse:collapse;color:#000000;background-color:#c2d69a;font-weight:bold;}
td.bdazul{border:1px solid #1f497d;border-collapse:collapse;color:#000000;font-weight:bold;}
table.blanco{border-collapse:collapse;color:#000000;font-weight:bold;}
</style>");

echo ("<table cellspacing='2' cellpadding='0' border='0' bordercolor='#FFFFFF'>
			<tr height='10'><td></td></tr>
			<tr height='60'>
				<td width='30'></td><td></td>
				<td colspan='2' align='center'><b><h1>REPORTE RESPUESTA GESTION</h1></b></td>
				
			</tr>
			<tr height='30'><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td>
				<td class='naranja'>CARTERA</td>
				<td class='narlight'><b>".$campania[0][0]."</b></td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA INICIO</td>
				<td class='narlight' align='left'><b>".$fechainicio."</b></td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja'>FECHA FIN</td>
				<td class='narlight' align='left'><b>".$fechafin."</b></td>
				
			</tr>
			<tr>
				<td></td>
				<td class='naranja' valign='top'>CODIGO GESTION</td>
				<td class='narlight'>".$gestiones."
				</td>
				
			</tr>
			<tr height='25'><td></td><td></td><td></td><td></td><td></td></tr>");

//echo ("</table>");
			
echo ("
			<tr>
				<td></td>
				<td class='naranja'>ESTADO</td>
				<td class='naranja'>RESPUESTA GESTION</td>
				<td class='naranja'>RESPUESTA INCIDENCIA</td>
				".$cabecera_dias."
				<td class='naranja'>TOTAL GENERAL</td>
				<td></td>
			</tr>
			");
//echo ("</table>");	

$estados=data_respuesta_gestion_estados($idcartera,$anio,$mes,$diai,$diaf);
$estad_rptas=data_respuesta_gestion_respuestas($idcartera,$anio,$mes,$diai,$diaf);
$data=data_respuesta_gestion_datos($idcartera,$anio,$mes,$diai,$diaf);

for($i=0;$i<count($data);$i++){
	echo("<tr><td></td>");	
	for($j=0;$j<count($dia_gestion)+4;$j++){
		echo("<td>".$data[$i][$j]."</td>");
	}
	echo("</tr>");	
}

//$html="<table border='1'>";  OPTIMO
/*$html="";
$fr=0;	//contador de rptas_gestion
$f=0; // contador de registros de data_gral
	for($i=0;$i<count($estados);$i++){
		$html.="<tr><td></td>";
		if($i%2==0){
		$html.="<td valign='top' align='center' class='narlight_bd' rowspan='".$estados[$i][1]."'><b>".$estados[$i][0]."</b></td>";
		}else{
		$html.="<td valign='top' align='center' class='blanco_bd' rowspan='".$estados[$i][1]."'><b>".$estados[$i][0]."</b></td>";
		}
		
		$i_fr=0; //indica si se ha imprimido ya la 1ra fila de la rpta_gestion
		
		for($j=0;$j<count($estad_rptas);$j++){
			if($estad_rptas[$j][0]==$estados[$i][0]){
				
				if($i_fr>0){$html.="<tr><td></td>";}
				
				if($fr%2==0){
				$html.="<td valign='top' class='narlight_bd' rowspan='".$estad_rptas[$j][2]."'>".$estad_rptas[$fr][1]."</td>";
				}else{
				$html.="<td valign='top' class='blanco_bd' rowspan='".$estad_rptas[$j][2]."'>".$estad_rptas[$fr][1]."</td>";	
				}
				
				$fr++;
				$i_fr++;  //pa indicar q ya se imprimio la 1ra fia de rpta_gestion
				//$html.="<td>".$data[$f][2]."</td>";
				for($x=2;$x<(count($dia_gestion)+4);$x++){
					if($f%2==0){
					$html.="<td class='narlight_bd'>".$data[$f][$x]."</td>";
					}else{
					$html.="<td class='blanco_bd'>".$data[$f][$x]."</td>";
					}
				}
				$f++;
				for($k=0;$k<count($data);$k++){
					if(!empty($data[$f][0])){
					if($data[$f][0]==$estados[$i][0] && $data[$f][1]==$estad_rptas[$j][1]){
						$html.="<tr><td></td>";
						//$html.="<td>".$data[$f][2]."</td>";	
						for($x=2;$x<(count($dia_gestion)+4);$x++){
							if($f%2==0){
							$html.="<td class='narlight_bd'>".$data[$f][$x]."</td>";	
							}else{
							$html.="<td class='blanco_bd' >".$data[$f][$x]."</td>";	
							}
						}
						$f++;
					}
					}
				}
				
				
			}
		}
	
	}
$html.="</table>";


echo($html);
*/
	
////////////////////////////////////</OPTIMO




			/*$html = "";
			$estados = array();
			$long_estados = array();
			$carga = $data[0][0]; //carga = obj[0].ESTADO;
			$rpt_gestion=$data[0][1];//rpt_gestion = obj[0].RESPUESTA_GESTION;
			$count_es = 0;
			$count_rptgst = 0;
			$estados[$carga] = array();
			$estados[$carga][$rpt_gestion]= array(); 
			for( $i=0;$i<count($data);$i++ ) {
				
				if( $carga == $data[$i][0] ) {
					$count_es++;
					//$data[$i] = eval(obj[i]);
					if( $rpt_gestion == $data[$i][1] ) {
						$count_rptgst++;
					}else{
						$estados[$carga][$rpt_gestion] = $count_rptgst; 
						$count_rptgst=1;
						$rpt_gestion = $data[$i][1];
						$estados[$carga][$rpt_gestion] = array(); 
					}
				$estados[$carga][$rpt_gestion] = $count_rptgst; 	
				}else{ //paso a otro estado
					$long_estados[$carga] = $count_es;
					$count_es=1;
					$carga = $data[$i][0]; //nueva carga(estado)
					$rpt_gestion = $data[$i][1]; //nueva rta_gst
					$estados[$carga] = array();
					$estados[$carga][$rpt_gestion] = array(); 
				}
				$data_e = array();
				for( index in data ) {
					if( index != 'ESTADO' && index != 'RESPUESTA_GESTION' ) {
						data_e.push(data[index]);
					}
					if( i == 0 ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
					}
			}
				$estados[$carga][$rpt_gestion].push(data_e);
			}
			$long_estados[$carga] = $count_es;
			$html='<tr class="ui-state-default" >'.$html.'</tr>';
			for( i in $estados ) {
			for( $i=0;$i<count($estados);$i++ ) {	
				var $count_c = 0;
				for( j in $estados[i] ) {
					//var html_d = '';
					var $cont_rg = 0;
					if( $count_c == 0 ) {
						$html+='<tr class="ui-widget-content"><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" rowspan="'+$long_estados[i]+'" >'+i+'</td>';
					}else{
						$html+='<tr class="ui-widget-content">';
					}
					for( $k = 0; $k<$estados[i][j].length;$k++ ) {
						//alert(estados[i][j][k]);
						if( $cont_rg == 0 ) {
							$html+='<td align="center" rowspan="'+$estados[i][j].length+'" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+$j+'</td>';
						}else{
							$html+='<tr class="ui-widget-content">';
						}
						for( $p=0;$p<$estados[i][j][k].length;$p++ ) {
							$html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+$estados[i][j][k][p]+'</td>';
						}
						$html+='</tr>';
						$cont_rg++;
						$count_c++;
					}
				}
			}
print_r($long_estados);echo("<br><br>");
echo($rpt_gestion);echo("<br><br>");
print_r($estados);echo("<br><br>");

foreach($long_estados as $k => $value)  
{ 
echo "<b>".$k ."</b> =". $value ."<br>";  
} 

for($i=0;$i<count($estados);$i++){
	}
foreach($estados as $fi){
	foreach($fi as $co =>$value)
	echo($co."=".$value."<br>");
	}*/


?>


