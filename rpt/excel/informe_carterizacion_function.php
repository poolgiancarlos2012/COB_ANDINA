<?php

//~ Vic I

//~ Informe de Carterizacion
function sql_informe_cartera($cartera, $ini, $fin, $proceso, $agencia)
{
$sql_table = <<<EOT
	SELECT hi.territorio, hi.oficina2, hi.oficina, hi.codcent, hi.nombre, hi.producto, hi.nom_subprod, hi.contrato, hi.divisa, 
		hi.saldohoy, '' AS saldoVigente, hi.diavenc, hi.idcliente_cartera, llama.tipificaLlamada, visi.tipificaVisita
		,IF(t.numero_act IS NULL, CONCAT_WS(' ', t.numero, llama.observacion), CONCAT_WS(' ', t.numero_act, llama.observacion)) AS gestionCall
		,visi.observacion,
		CASE 
			WHEN TRIM(llama.tipificaLlamada) = 'CEF' OR TRIM(visi.tipificaVisita)='CEF' THEN 'Contacto Directo'
			WHEN TRIM(llama.tipificaLlamada) = 'CNE' OR TRIM(visi.tipificaVisita)='CNE' THEN 'Contacto Indirecto'
			WHEN TRIM(llama.tipificaLlamada) = 'NOC' AND (TRIM(visi.tipificaVisita)='NOC' OR TRIM(visi.tipificaVisita) IS NULL)  THEN 'No Contacto'
			WHEN (TRIM(llama.tipificaLlamada) = 'NOC' OR TRIM(llama.tipificaLlamada) IS NULL) AND TRIM(visi.tipificaVisita)='NOC' THEN 'No Contacto'
			ELSE NULL
		END AS contactoGeneral, llama.efecto, 
			IF(llama.fecha_cp IS NOT NULL OR visi.fecha_cp IS NOT NULL, 'CANCELACION ATRASOS, SE REFORZARA COMPROMISO', fsb.nombre) AS estadoResumen, 
			IF(llama.fecha_cp IS NULL, visi.fecha_cp, llama.fecha_cp) AS fecha_pago 
	FROM ca_historial hi 
	LEFT JOIN (
		SELECT t1.idcliente_cartera, t1.fecha_cp, t1.observacion,t1.idtelefono, t1.nombre AS tipificaLlamada, t1.efecto FROM (
			SELECT lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre AS estado,fin.idcarga_final,carfin.nombre, lla.idfinal, finser.peso
				,lla.fecha_cp, lla.observacion, lla.idtelefono, finser.efecto
			FROM ca_cliente_cartera clicar 
				INNER JOIN ca_llamada lla 
				INNER JOIN ca_final fin 
				INNER JOIN ca_final_servicio finser 
				INNER JOIN ca_carga_final carfin 
					ON finser.idfinal = fin.idfinal 
					AND fin.idfinal = lla.idfinal 
					AND lla.idcliente_cartera= clicar.idcliente_cartera 
					AND fin.idcarga_final=carfin.idcarga_final
			WHERE clicar.idcartera = {$cartera} AND lla.tipo<>'IVR' 
				AND DATE(lla.fecha) BETWEEN '{$ini}' AND '{$fin}'
				AND lla.idusuario_servicio<>'1'
			ORDER BY lla.idcliente_cartera, finser.peso DESC,lla.fecha DESC 
		) t1 GROUP BY t1.idcliente_cartera 
	) llama ON hi.idcliente_cartera=llama.idcliente_cartera
	LEFT JOIN ca_telefono t ON llama.idtelefono=t.idtelefono
	LEFT JOIN (
		SELECT * FROM (
			SELECT vi.idcliente_cartera,vi.idcuenta, vi.fecha_visita, fin.nombre AS estado,fin.idcarga_final,
				carfin.nombre AS tipificaVisita, vi.idfinal , finser.peso,vi.fecha_cp, vi.observacion
			FROM ca_cliente_cartera clicar 
				INNER JOIN ca_visita vi 
				INNER JOIN ca_final fin 
				INNER JOIN ca_final_servicio finser 
				INNER JOIN ca_carga_final carfin 
					ON finser.idfinal = vi.idfinal 
					AND fin.idfinal = vi.idfinal 
					AND vi.idcliente_cartera= clicar.idcliente_cartera 
					AND fin.idcarga_final=carfin.idcarga_final
			WHERE clicar.idcartera = {$cartera} AND vi.tipo<>'IVR' 
				AND DATE(vi.fecha_visita) BETWEEN '{$ini}' AND '{$fin}'
				AND vi.idusuario_servicio<>'1'
			ORDER BY vi.idcliente_cartera, finser.peso DESC,vi.fecha_visita DESC 
		) t1 
		GROUP BY t1.idcliente_cartera 
	) visi ON hi.idcliente_cartera=visi.idcliente_cartera
		LEFT JOIN ca_final_servicio_bbva fsb ON llama.efecto=fsb.id
	WHERE hi.Fproceso='{$proceso}' AND hi.agencia='{$agencia}' 
EOT;

return $sql_table;
}

//~ Informe de Carterizacion - Resumen
function sql_informe_cartera_resumen($cartera, $ini, $fin, $proceso, $agencia) 
{
$sql_info = <<<EOT
	SELECT IFNULL(a.contactoGeneral, 'TOTALES') AS contactoGeneral, COUNT(*) AS total FROM (
		SELECT hi.territorio, hi.oficina2, hi.oficina, hi.codcent, hi.nombre, hi.producto, hi.nom_subprod, hi.contrato, hi.divisa, 
			hi.saldohoy, '' AS saldoVigente, hi.diavenc, hi.idcliente_cartera, llama.tipificaLlamada, visi.tipificaVisita
			,IF(t.numero_act IS NULL, CONCAT_WS(' ', t.numero, llama.observacion), CONCAT_WS(' ', t.numero_act, llama.observacion)) AS gestionCall
			,visi.observacion,
			CASE 
				WHEN TRIM(llama.tipificaLlamada) = 'CEF' OR TRIM(visi.tipificaVisita)='CEF' THEN 'Contacto Directo'
				WHEN TRIM(llama.tipificaLlamada) = 'CNE' OR TRIM(visi.tipificaVisita)='CNE' THEN 'Contacto Indirecto'
				WHEN TRIM(llama.tipificaLlamada) = 'NOC' AND (TRIM(visi.tipificaVisita)='NOC' OR TRIM(visi.tipificaVisita) IS NULL)  THEN 'No Contacto'
				WHEN (TRIM(llama.tipificaLlamada) = 'NOC' OR TRIM(llama.tipificaLlamada) IS NULL) AND TRIM(visi.tipificaVisita)='NOC' THEN 'No Contacto'
				ELSE '_'
			END AS contactoGeneral, llama.efecto, fsb.nombre AS estadoResumen, IF(llama.fecha_cp IS NULL, visi.fecha_cp, llama.fecha_cp) AS fecha_pago
		FROM ca_historial hi 
		LEFT JOIN (
			SELECT t1.idcliente_cartera, t1.fecha_cp, t1.observacion,t1.idtelefono, t1.nombre AS tipificaLlamada, t1.efecto FROM (
				SELECT lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.nombre AS estado,fin.idcarga_final,carfin.nombre, lla.idfinal, finser.peso
					,lla.fecha_cp, lla.observacion, lla.idtelefono, finser.efecto
				FROM ca_cliente_cartera clicar 
					INNER JOIN ca_llamada lla 
					INNER JOIN ca_final fin 
					INNER JOIN ca_final_servicio finser 
					INNER JOIN ca_carga_final carfin 
						ON finser.idfinal = fin.idfinal 
						AND fin.idfinal = lla.idfinal 
						AND lla.idcliente_cartera= clicar.idcliente_cartera 
						AND fin.idcarga_final=carfin.idcarga_final
				WHERE clicar.idcartera = {$cartera} AND lla.tipo<>'IVR' 
					AND DATE(lla.fecha) BETWEEN '{$ini}' AND '{$fin}'
					AND lla.idusuario_servicio<>'1'
				ORDER BY lla.idcliente_cartera, finser.peso DESC,lla.fecha DESC 
			) t1 GROUP BY t1.idcliente_cartera 
		) llama ON hi.idcliente_cartera=llama.idcliente_cartera
		LEFT JOIN ca_telefono t ON llama.idtelefono=t.idtelefono
		LEFT JOIN (
			SELECT * FROM (
				SELECT vi.idcliente_cartera,vi.idcuenta, vi.fecha_visita, fin.nombre AS estado,fin.idcarga_final,
					carfin.nombre AS tipificaVisita, vi.idfinal , finser.peso,vi.fecha_cp, vi.observacion
				FROM ca_cliente_cartera clicar 
					INNER JOIN ca_visita vi 
					INNER JOIN ca_final fin 
					INNER JOIN ca_final_servicio finser 
					INNER JOIN ca_carga_final carfin 
						ON finser.idfinal = vi.idfinal 
						AND fin.idfinal = vi.idfinal 
						AND vi.idcliente_cartera= clicar.idcliente_cartera 
						AND fin.idcarga_final=carfin.idcarga_final
				WHERE clicar.idcartera = {$cartera} AND vi.tipo<>'IVR' 
					AND DATE(vi.fecha_visita) BETWEEN '{$ini}' AND '{$fin}'
					AND vi.idusuario_servicio<>'1'
				ORDER BY vi.idcliente_cartera, finser.peso DESC,vi.fecha_visita DESC 
			) t1 
			GROUP BY t1.idcliente_cartera 
		) visi ON hi.idcliente_cartera=visi.idcliente_cartera
			LEFT JOIN ca_final_servicio_bbva fsb ON llama.efecto=fsb.id
		WHERE hi.Fproceso='{$proceso}' AND hi.agencia='{$agencia}' 
	) a GROUP BY a.contactoGeneral WITH ROLLUP
EOT;

return $sql_info;
}

//	Funcion para personalizar color
function color($txt,$fon,$negra=true,$bord=true,$ali=true)
{
	//	Alineacion
	if($ali==true)
	{
		$alinea=array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}
	else
	{
		$alinea=array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	}
	//	Border
	if($bord==true)
	{
		$border=array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
	}
	else
	{
		$border=array();
	}

	$set=array(
		'font' => array( 'bold' => $negra, 'color' => array('argb' => $txt) ),
		'alignment' => $alinea,
		'borders' => $border,
		'fill' => array( 'type'	=> PHPExcel_Style_Fill::FILL_SOLID,	'color'	=> array('argb' => $fon) )
	);
	return $set;
}

//~ Vic F

?>
