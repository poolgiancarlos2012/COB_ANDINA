<?php
include_once("config.php");
function leeRegistro($tabla,$columnas,$filtro,$orden){
	$tabla=strtolower($tabla);
	if(empty($columnas)){$columnas="*";}
	$sql="Select ".$columnas." from ".$tabla;
	if(!empty($filtro)){ $sql.=" where ".$filtro; }
	if(!empty($orden)){ $sql.=" order by ".$orden; }	
	conectar();
	$data="";
	$resultado=mysql_query($sql) or die(mysql_error());
	$num_resultado=mysql_num_rows($resultado);
	for($i=0;$i<$num_resultado;$i++){
		$fila=mysql_fetch_array($resultado);
		$data[]=$fila;
	}
	desconectar();
	return $data;
}

function lee($sql){
	conectar();
	$data="";
	$resultado=mysql_query($sql) or die(mysql_error());
	$num_resultado=mysql_num_rows($resultado);
	for($i=0;$i<$num_resultado;$i++){
		$fila=mysql_fetch_array($resultado);
		$data[]=$fila;
	}
	desconectar();
	return $data;
}
function rpad_php($txt,$longitud,$caracter,$cabecera){
	$text=$txt;$long=$longitud;$char=$caracter;$cabec=$cabecera;
	if(strlen($text)==$long){
		if(strlen($text)<strlen($cabec)){$text=str_pad($text,strlen($cabec),$char);}
		}
	if(strlen($text)>$long){
		$text=substr($text,0,$long);
		if(strlen($text)<strlen($cabec)){$text=str_pad($text,strlen($cabec),$char);}
		}
	if(strlen($text)<$long){
		$text=str_pad($text,$long,$char);		
		if(strlen($text)<strlen($cabec)){$text=str_pad($text,strlen($cabec),$char);}
		}
	return $text;
	}
function leeProcedure($procedure){
	$procedure=$procedure;
	$sql="call ".$procedure;
	conectar();
	$data="";
	$resultado=mysql_query($sql) or die(mysql_error());
	$num_resultado=mysql_num_rows($resultado);
	for($i=0;$i<$num_resultado;$i++){
		$fila=mysql_fetch_array($resultado);
		$data[]=$fila;
	}
	desconectar();
	return $data;
}
function creaCombo($nombre,$data,$selected,$estilos=""){
	$combo="<select name='".$nombre."' style='".$estilos."'>";
	$combo.="<option value=-1>elija</option>";
	for($i=0;$i<count($data);$i++){
		$seleccionado="";
		if($data[$i][0]==$selected){
			$seleccionado="Selected";
		}
		$combo.="<option value='".$data[$i][0]."' 
		".$seleccionado.">
		".$data[$i][1]."</option>";
	}
	$combo.="</select>";
	return $combo;
}
function grabaRegistro($tabla,$data){
	$tabla=strtolower($tabla);
	$columnas=array_keys($data);
	$sql="Insert Into ".$tabla."(";
	for($i=0;$i<count($columnas);$i++){
		$sql.=$columnas[$i].",";
	}
	$sql.="FechaCreacion,UsuarioCreacion) ";
	$sql.="values(";
	for($i=0;$i<count($data);$i++){
		$sql.="'".$data[$columnas[$i]]."',";
	}
	$sql.="Now(),".$_SESSION['idActor'].")";
	conectar();
	$resultado=mysql_query($sql) or die(mysql_error());
	desconectar();
	if($resultado){
		$mensaje="El registro se grabo con Exito";
	}
	else {
		$mensaje="No se consiguio grabar el registro: --> ".mysql_error();
	}
	return $mensaje;
}
function actualizaRegistro($tabla,$data,$filtro){
	$tabla=strtolower($tabla);
	$columnas=array_keys($data);
	$sql="Update ".$tabla." set ";
	for($i=0;$i<count($columnas);$i++){
		$sql.=$columnas[$i]."='".$data[$columnas[$i]]."',";
	}
	$sql.="FechaModificacion=Now() , UsuarioModificacion=".$_SESSION['idActor']." ";
	$sql.="Where ".$filtro;
	conectar();
	$resultado=mysql_query($sql);
	if($resultado){
		$mensaje="El registro se actualizo con Éxito";
	}
	else {
		$mensaje="No se consiguio actualizar el registro".mysql_error();
	}
	desconectar();
	return $mensaje;	
}
function eliminaRegistro($tabla,$filtro){
	$tabla=strtolower($tabla);
	$sql="Delete from ".$tabla." ";
	if(!empty($filtro)){	
		$sql.=" Where ".$filtro;
	}
	conectar();
	$resultado=mysql_query($sql);	
	desconectar();	
}
function add_date($givendate,$day=0,$mth=0,$yr=0) {
    $cd = strtotime($givendate);
    $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
    date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
    date('d',$cd)+$day, date('Y',$cd)+$yr));
    return $newdate;
}
function obtener_gestiones_html($gestion){#necesita array de gestiones devuelve una tabla con gestiones
	$gestion_ht="";
	if(!empty($gestion)){
		$gestion_ht.="<table class='blanco'>";
		for($i=0;$i<count($gestion);$i++){
			$gestion_ht.="<tr><td>".$gestion[$i][0]."</td></tr>";
			}
		$gestion_ht.="</table>";
	}
	return $gestion_ht;
}	
function obtener_rango_dias($fechainicio,$fechafin){ //devuelve array con gestiones
	$fechainiaux=$fechainicio;
	while($fechainiaux<=$fechafin){
		//echo $fechainiaux."<br>";
		$diasgestion[]=$fechainiaux;
		$fechainiaux=substr(add_date($fechainiaux,1,0,0),0,10);
		}
	return $diasgestion;
}
function crea_cabecera_dias_html($dia_gestion){##necesita array obtener_rango_dias devuelve html con columnas de dias
	$cabec="";
	for($i=0;$i<count($dia_gestion);$i++){
		$cabec.="<td class='naranja'>".$dia_gestion[$i]."</td>";
	}
	return $cabec;
}
function cuenta_valor_call_html($data,$dia_gestion){ #devuelve arrays con html
	$reg[0]="";$reg[1]="";$reg[2]="";$reg[3]="";$reg[4]="";$reg[5]="";$reg[6]="";$reg[7]="";$reg[8]="";$reg[9]="";
//	$total;
	if(!empty($data)){
	for($i=0;$i<count($data);$i++){
		switch ($data[$i][0]) {
			case "CEF":
				for($j=0;$j<count($dia_gestion)+1;$j++){
					$reg[0].="<td>".$data[$i][$j+1]."</td>";
				};
				break;
			case "CNE":
				for($j=0;$j<count($dia_gestion)+1;$j++){
					$reg[1].="<td>".$data[$i][$j+1]."</td>";
				};
				break;
			case "NOC":
				for($j=0;$j<count($dia_gestion)+1;$j++){
					$reg[2].="<td>".$data[$i][$j+1]."</td>";
				};
				break;
			case "ENCUESTA":
				for($j=0;$j<count($dia_gestion)+1;$j++){
					$reg[3].="<td>".$data[$i][$j+1]."</td>";
				};
				break;	
			case null: //total
				for($j=0;$j<count($dia_gestion)+1;$j++){
					$reg[4].="<td class='naranja'>".$data[$i][$j+1]."</td>";
					$total[$j]=$data[$i][$j+1];
				};
				break;		
			default:
				;
				break;
		}
	}
	}
	if(!empty($data)){
	for($i=0;$i<count($data);$i++){
		switch ($data[$i][0]) {
			case "CEF":
				for($j=0;$j<count($dia_gestion);$j++){
					$prtjcef[$j]=0;
					if($total[$j]!=0){
						$prtjcef[$j]=round((100*($data[$i][$j+1]/$total[$j])),2);
					}
					$reg[5].="<td>".$prtjcef[$j]."%</td>";
				};
				break;
			case "CNE":
				for($j=0;$j<count($dia_gestion);$j++){
					$prtjcne[$j]=0;
					if($total[$j]!=0){
						$prtjcne[$j]=round((100*($data[$i][$j+1]/$total[$j])),2);
					}
					$reg[6].="<td>".$prtjcne[$j]."%</td>";
				};
				break;
			case "NOC":
				for($j=0;$j<count($dia_gestion);$j++){
					$prtjnoc[$j]=0;
					if($total[$j]!=0){
						$prtjnoc[$j]=round((100*($data[$i][$j+1]/$total[$j])),2);
					}
					$reg[7].="<td>".$prtjnoc[$j]."%</td>";
				};
				break;
			case "ENCUESTA":
				for($j=0;$j<count($dia_gestion);$j++){
					$prtjencuesta[$j]=0;
					if($total[$j]!=0){
						$prtjencuesta[$j]=round((100*($data[$i][$j+1]/$total[$j])),2);
					}
					$reg[8].="<td>".$prtjencuesta[$j]."%</td>";
				};
				break;		
			default:
				;
				break;
		}
	}
	}
	if(!empty($data)){
		for($j=0;$j<count($dia_gestion);$j++){
			$reg[9].="<td class='naranja'>".($prtjcef[$j]+$prtjcne[$j])."%</td>";
		};
	}
	return $reg;
}
function data_contactabilidad_por_hora_datos($idcartera,$fecha_inicio,$fecha_fin){
	
	$idcartera = $idcartera;
	$fecha_inicio = $fecha_inicio;
	$fecha_fin = $fecha_fin;
	
	$field = array();
	
	for( $i=7;$i<=19;$i++ ) {
		array_push($field," SUM( IF( HOUR(tran.fecha) = ".$i." ,1,0 ) ) AS '".$i."hrs' ");

	}
	
	$sqlIni = " SELECT carfin.nombre AS 'ESTADO', ".implode(",",$field)." ,
		SUM( IF( HOUR(tran.fecha) BETWEEN 7 AND 19,1,0 ) ) AS 'TOTAL' 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
		GROUP BY fin.idcarga_final WITH ROLLUP ";
		
	$dataIni = lee($sqlIni);
	//$dataPor = lee($sqlPor);
	
	return $dataIni;
		
}

function data_contactabilidad_por_hora_porcentajes($idcartera,$fecha_inicio,$fecha_fin){
	
	$idcartera = $idcartera;
	$fecha_inicio = $fecha_inicio;
	$fecha_fin = $fecha_fin;
	
	$field = array();
	
	$field2 = array();
	
	
	for( $i=7;$i<=19;$i++ ) {
		array_push($field," SUM( IF( HOUR(tran.fecha) = ".$i." ,1,0 ) ) AS '".$i."hrs' ");
		
		array_push($field2," TRUNCATE( IFNULL( (( t1.".$i."hrs / t2.".$i."hrs )*100),0 ), 2 ) AS '".$i."hrs' ");
	}
	
	$sqlPor = " SELECT t1.ESTADO AS 'RESULTADO', ".implode(",",$field2)." 
		FROM (
			SELECT carfin.nombre AS 'ESTADO', ".implode(",",$field)." 
			FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_llamada lla 
			ON lla.idtransaccion = tran.idtransaccion AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera 
			WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
			GROUP BY fin.idcarga_final
		) AS t1, (
			SELECT 'VALORES' AS 'ESTADO', ".implode(",",$field)." 
			FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_llamada lla 
			ON lla.idtransaccion = tran.idtransaccion AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera 
			WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
		) AS t2 ";
		

	//$dataIni = lee($sqlIni);
	$dataPor = lee($sqlPor);
	
	return $dataPor;
		
}

function data_compromiso_dia_datos($idcartera,$anio,$mes,$diai,$diaf,$idcarga){
	
	$idcartera = $idcartera;
	$anio = (int)$anio;
	$mes = (int)$mes;
	$diai = (int)$diai;
	$diaf = (int)$diaf;
	$idcarga = $idcarga;				
					
	$field = array();
	$field2 = array();
	$field3 = array();
	$field4 = array();
	$field5 = array(); 
	$field6 = array(); 
	$field7 = array();  
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."',1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field2," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field3," COUNT( DISTINCT IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' , clicar.idusuario_servicio , NULL ) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field4," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1 , 0) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field5," TRUNCATE( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), 0 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field6," ( ( SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) ) / 2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field7," TRUNCATE( ( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), '0') * 100 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}

	$sql = " SELECT 
		(SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
		ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'TELEOPERADOR', 
		".implode(",",$field).", SUM( IF( DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' , 1,0 ) ) AS 'TOTAL_CP'
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal 
		AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND tran.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) ) 
		AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."'
		GROUP BY clicar.idusuario_servicio WITH ROLLUP ";	
		
	$data = lee($sql);
	
	return $data;
	

}
function data_compromiso_dia_resumen($idcartera,$anio,$mes,$diai,$diaf,$idcarga){
	
	$idcartera = $idcartera;
	$anio = (int)$anio;
	$mes = (int)$mes;
	$diai = (int)$diai;
	$diaf = (int)$diaf;
	$idcarga = $idcarga;				
					
	$field = array();
	$field2 = array();
	$field3 = array();
	$field4 = array();
	$field5 = array(); 
	$field6 = array(); 
	$field7 = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."',1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field2," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field3," COUNT( DISTINCT IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' , clicar.idusuario_servicio , NULL ) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field4," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1 , 0) ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field5," TRUNCATE( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), 0 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field6," ( ( SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."', 1, 0 ) ) ) / 2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
		array_push($field7," TRUNCATE( ( IFNULL( ( t1.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." / t2.".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)." ), '0') * 100 ),2 ) AS '".$anio."_".str_pad($mes,2,'0',STR_PAD_LEFT)."_".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}

	$sqlCarga = " SELECT 'CEF' AS 'VALOR_EFECTIVO', ".implode(",",$field2)." 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal 
		AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND fin.idcarga_final IN ( ".$idcarga." ) 
		AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' ";

	$sqlACP = " SELECT 'Asesores con Compromisos por Día' AS 'VALOR_EFECTIVO' ,".implode(",",$field3)." 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND tran.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) )
		AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' ";

	$sqlSum = " SELECT ".implode(",",$field4)." 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla
		ON lla.idtransaccion = tran.idtransaccion AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND tran.idfinal IN ( SELECT idfinal FROM ca_final WHERE idnivel IN (12,15) )
		AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' ";
						
	$sqlPro = " SELECT 'Promedio de Compromisos Por Asesor' AS 'VALOR_EFECTIVO' ,".implode(",",$field5)." FROM ( $sqlSum ) t1 , ( $sqlACP ) t2 ";
	
	$sqlMeta = " SELECT 'Meta (50% de CEF)' AS 'VALOR_EFECTIVO', ".implode(",",$field6)." 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal 
		AND tran.idcliente_cartera = clicar.idcliente_cartera 
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND fin.idcarga_final IN ( ".$idcarga." ) 
		AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' ";
		
	$sqlCompromisos = " SELECT '% de compromisos' AS 'VALOR_EFECTIVO', ".implode(",",$field7)." FROM ( $sqlSum ) t1, ( $sqlMeta ) t2 ";
	
	$Mdata = lee($sqlCarga." UNION ".$sqlACP." UNION ".$sqlPro." UNION ".$sqlMeta." UNION ".$sqlCompromisos );
					
	return $Mdata;

}

function data_respuesta_gestion_datos($idcartera,$anio,$mes,$diai,$diaf){					

	$idcartera = $idcartera;
	$anio = (int)$anio;
	$mes = (int)$mes;
	$diai = (int)$diai;
	$diaf = (int)$diaf;
	
	$field = array();
	
	for( $i=$diai;$i<=$diaf;$i++ ) {
		array_push($field," SUM( IF( DATE(tran.fecha) = '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ,1,0 ) ) AS '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($i,2,'0',STR_PAD_LEFT)."' ");
	}
	
	$sql = " SELECT carfin.nombre AS 'ESTADO', niv.nombre AS 'RESPUESTA_GESTION', fin.nombre AS 'RESPUESTA_INCIDENCIA', 
		".implode(",",$field).", COUNT(*) AS 'TOTAL_GENERAL' 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_nivel niv INNER JOIN ca_llamada lla
		ON lla.idtransaccion = tran.idtransaccion AND niv.idnivel = fin.idnivel AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
		GROUP BY fin.idcarga_final, fin.idnivel, fin.idfinal ORDER BY 1,2,3 "; 
		
	$data = lee($sql);
	
	return $data;
}
function data_respuesta_gestion_estados($idcartera,$anio,$mes,$diai,$diaf){					

	$idcartera = $idcartera;
	$anio = (int)$anio;
	$mes = (int)$mes;
	$diai = (int)$diai;
	$diaf = (int)$diaf;
	
	$sql = " select datos.ESTADO,count(datos.ESTADO) from 
		(SELECT carfin.nombre AS 'ESTADO', niv.nombre AS 'RESPUESTA_GESTION', fin.nombre AS 'RESPUESTA_INCIDENCIA' 
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_nivel niv 
		ON niv.idnivel = fin.idnivel AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
		GROUP BY fin.idcarga_final, fin.idnivel, fin.idfinal ORDER BY 1,2,3) datos GROUP BY datos.ESTADO "; 
		
	$data = lee($sql);
	
	return $data;
}
function data_respuesta_gestion_respuestas($idcartera,$anio,$mes,$diai,$diaf){					

	$idcartera = $idcartera;
	$anio = (int)$anio;
	$mes = (int)$mes;
	$diai = (int)$diai;
	$diaf = (int)$diaf;
	
	$sql = " select datos.ESTADO,datos.respuesta_gestion, count(datos.respuesta_gestion) from 
		(SELECT carfin.nombre AS 'ESTADO', niv.nombre AS 'RESPUESTA_GESTION', fin.nombre AS 'RESPUESTA_INCIDENCIA'
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_carga_final carfin INNER JOIN ca_nivel niv
		ON niv.idnivel = fin.idnivel AND carfin.idcarga_final = fin.idcarga_final AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diai,2,'0',STR_PAD_LEFT)."' AND '".$anio."-".str_pad($mes,2,'0',STR_PAD_LEFT)."-".str_pad($diaf,2,'0',STR_PAD_LEFT)."' 
		GROUP BY fin.idcarga_final, fin.idnivel, fin.idfinal ORDER BY 1,2,3)  datos 
		GROUP BY datos.estado,datos.respuesta_gestion order by datos.estado "; 
		
	$data = lee($sql);
	
	return $data;
}
	
function data_semaforo_fija_datos($idcartera,$fecha_inicio,$fecha_fin,$idcarga,$meta){	

	$idcartera = $idcartera;
	$fecha_inicio = $fecha_inicio;
	$fecha_fin = $fecha_fin;
	$idcarga = $idcarga;
	$meta = (int)$meta;
	
	$field = array();
	
	for( $i=7;$i<=19;$i++ ) {
		array_push($field," SUM( IF( HOUR(tran.fecha) = ".$i." ,1,0 ) ) AS '".$i."hrs' ");
		
	}
	
	$sql = " SELECT 
		( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) AS 'TELEOPERADOR',
		".implode(",",$field).", SUM( IF( HOUR(tran.fecha) BETWEEN 7 AND 19,1,0 ) ) AS 'TOTAL_LLAMADAS'
		FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND fin.idcarga_final IN ( ".$idcarga." )
		GROUP BY clicar.idusuario_servicio WITH ROLLUP ";
		
	$data = lee($sql);
	
	return $data;
}

function data_semaforo_fija_resumen($idcartera,$fecha_inicio,$fecha_fin,$idcarga,$meta){	

	$idcartera = $idcartera;
	$fecha_inicio = $fecha_inicio;
	$fecha_fin = $fecha_fin;
	$idcarga = $idcarga;
	$meta = (int)$meta;
	
	$field = array();
	$field2 = array();
	$field3 = array();
	$field4 = array();
	$field5 = array();
	
	for( $i=7;$i<=19;$i++ ) {
		array_push($field2," TRUNCATE( COUNT( DISTINCT IF( HOUR(tran.fecha) = ".$i." , clicar.idusuario_servicio, NULL ) ), 0 ) AS '".$i."hrs' ");
		array_push($field3," SUM( IF( HOUR(tran.fecha) = ".$i." , 1, 0 ) ) AS '".$i."hrs' ");
		array_push($field4," TRUNCATE( IFNULL( ( t1.".$i."hrs / t2.".$i."hrs ),0 ), 2 ) AS '".$i."hrs' ");
		array_push($field5," TRUNCATE( ".$meta.", 0 ) AS ".$i."hrs ");
	}
	
	$sqlNA = " SELECT 'NRO. Asesores por Hora' AS 'VALOR',".implode(",",$field2)." FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND fin.idcarga_final IN ( ".$idcarga." )  ";
	
	$sqlSum = " SELECT ".implode(",",$field3)." FROM ca_cliente_cartera clicar INNER JOIN ca_transaccion tran INNER JOIN ca_final fin INNER JOIN ca_llamada lla 
		ON lla.idtransaccion = tran.idtransaccion AND fin.idfinal = tran.idfinal AND tran.idcliente_cartera = clicar.idcliente_cartera
		WHERE clicar.idcartera IN ( ".$idcartera." ) AND DATE(tran.fecha) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND fin.idcarga_final IN ( ".$idcarga." ) ";
		
	$sqlPr = " SELECT 'Promedio de LLamadas' AS 'VALOR',".implode(",",$field4)." FROM ( $sqlSum ) t1 , ( $sqlNA ) t2 ";
	
	$sqlMeta = " SELECT 'Meta' AS 'VALOR',".implode(",",$field5)." ";
	
		
	$Mdata = lee($sqlNA." UNION ".$sqlPr." UNION ".$sqlMeta);

	return $Mdata;
	
}
function col_semaf($v){
	$color="white";
	if($v>=1 && $v<=20){
		$color="red";
	}
	if($v>=21 && $v<=24){
		$color="yellow";
	}
	if($v>=25){
		$color="green";
	}
	return $color;
}	
	
	
?>