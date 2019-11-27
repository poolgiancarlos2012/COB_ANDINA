<?php

//~ Vic I

//~ Crear Tablas Temporales
function create_table($fecha, $name, $now, $agencia, $agenciaDetalle,$cartera)
{
$sql_table = <<<EOT
	CREATE TABLE tmp_{$name}_{$now} (INDEX (codcent), INDEX (contrato), INDEX (diavenc))
	SELECT h.codcent, h.contrato, h.diavenc, h.id FROM ca_historial h 
	INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=h.idcliente_cartera
	WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$fecha}' AND h.agencia='{$agencia}' {$agenciaDetalle} 
EOT;

return $sql_table;
}

//~ Los Retirados Territorio
function retiro_territorio($inicio, $nameTmp, $agencia, $agenciaDetalle,$cartera) 
{
$sql = <<<EOT
	SELECT cli.territorio, con.contrato, cli.clientes FROM (
		SELECT cli_con.territorio, SUM(cli_con.clientes) AS clientes FROM (
			SELECT IFNULL(t.territorio, 'TOTALES') AS territorio,COUNT(t.cliente) AS clientes FROM (
				SELECT h.territorio, COUNT(h.codcent) AS cliente
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				GROUP BY h.codcent
			) t GROUP BY t.territorio WITH ROLLUP
			UNION ALL
			SELECT IFNULL(te.territorio, 'TOTALES') AS territorio, COUNT(te.clientes) AS clientes FROM (
				SELECT h.territorio, COUNT(h.codcent) AS clientes
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
				GROUP BY h.codcent
			) te GROUP BY te.territorio WITH ROLLUP
		) cli_con GROUP BY cli_con.territorio
	) cli
	INNER JOIN (
		SELECT cli_con.territorio, cli_con.contrato AS contrato FROM ( 
			SELECT IFNULL(h.territorio, 'TOTALES') AS territorio, COUNT(h.contrato) AS contrato
			FROM ca_historial h 
			INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
				LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
			WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
			GROUP BY h.territorio WITH ROLLUP
		) cli_con GROUP BY cli_con.territorio
	) con ON cli.territorio=con.territorio
EOT;

return $sql;
}

//~ Los Retirados Tramo
function retiro_tramo($inicio, $nameTmp, $agencia, $agenciaDetalle,$cartera) 
{
$sql = <<<EOT
	SELECT IF(tra_cli_con.tramo='TOTALES','_TOTALES',tra_cli_con.tramo) AS tramo, SUM(tra_cli_con.cantidad) AS cantidad FROM (
		SELECT IFNULL(tra.tramo,'TOTALES') AS tramo, COUNT(tra.tramo) AS cantidad FROM (
			SELECT di.diavenc, 
				CASE 
					WHEN CAST(di.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
					WHEN CAST(di.diavenc AS SIGNED) > 30 AND CAST(di.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
					WHEN CAST(di.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
					ELSE 'NO_TRAMO'
				END AS tramo
			FROM (
				SELECT h.diavenc, COUNT(h.codcent) AS cliente
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera =clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				GROUP BY h.codcent
			) di
		) tra
		GROUP BY tra.tramo WITH ROLLUP
		UNION ALL
		SELECT IFNULL(tra.tramo,'TOTALES') AS tramo, COUNT(tra.tramo) AS cantidad FROM (
			SELECT hi.diavenc, 
				CASE 
					WHEN CAST(hi.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
					WHEN CAST(hi.diavenc AS SIGNED) > 30 AND CAST(hi.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
					WHEN CAST(hi.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
					ELSE 'NO_TRAMO'
				END AS tramo
			FROM (
				SELECT h.diavenc , COUNT(h.codcent) AS clientes
				FROM ca_historial h
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$inicio}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
				GROUP BY h.codcent
			) hi
		) tra
		GROUP BY tra.tramo WITH ROLLUP
	) tra_cli_con GROUP BY tra_cli_con.tramo ORDER BY tramo ASC
EOT;

return $sql;
}

//~ Los Nuevos por Ubigeo
function nuevo_ubigeo($final, $nameTmp, $agencia, $agenciaDetalle,$cartera) 
{
$sql = <<<EOT
	SELECT cli.ubigeo, con.contrato, cli.clientes, con.monto FROM (
		SELECT cli_con.ubigeo, SUM(cli_con.clientes) AS clientes FROM (
			SELECT IFNULL(u.ubigeo, 'TOTALES') AS ubigeo, COUNT(u.cliente) AS clientes FROM (
				SELECT h.ubigeo, COUNT(h.codcent) AS cliente
				FROM ca_historial h
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent 
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				GROUP BY h.codcent
			) u GROUP BY u.ubigeo WITH ROLLUP
			UNION ALL
			SELECT IFNULL(h.ubigeo, 'TOTALES') AS ubigeo, COUNT(h.codcent) AS clientes
			FROM ca_historial h 
			INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
				LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
			WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
			AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
			GROUP BY h.ubigeo WITH ROLLUP
		) cli_con GROUP BY cli_con.ubigeo
	) cli 
	INNER JOIN (
		SELECT cli_con.ubigeo, SUM(cli_con.contrato) AS contrato, CAST(SUM(cli_con.monto) AS DECIMAL(12,2)) AS monto FROM (
			SELECT IFNULL(s.ubigeo , 'TOTALES') AS ubigeo, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
				SELECT h.ubigeo, h.contrato, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * 2.8, saldohoy * 7) ) AS saldohoyS
				FROM ca_historial h
				INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=h.idcliente_cartera 
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent 
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
			) s GROUP BY s.ubigeo WITH ROLLUP
			UNION ALL
			SELECT IFNULL(s.ubigeo, 'TOTALES') AS ubigeo, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
				SELECT h.ubigeo, h.contrato, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * 2.8, saldohoy * 7) ) AS saldohoyS
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
			) s GROUP BY s.ubigeo WITH ROLLUP
		) cli_con GROUP BY cli_con.ubigeo
	) con ON cli.ubigeo=con.ubigeo
EOT;

return $sql;
}

//~ Los Nuevos por Producto
function nuevo_producto($final, $nameTmp, $agencia, $agenciaDetalle,$cartera) 
{
$sql = <<<EOT
	SELECT cli.producto, con.contrato, cli.clientes, con.monto FROM (
		SELECT cli_con.producto, SUM(cli_con.clientes) AS clientes FROM (
			SELECT IFNULL(p.producto, 'TOTALES') AS producto, COUNT(p.cliente) AS clientes FROM (
				SELECT COUNT(h.codcent) AS cliente
					,CASE
						WHEN TRIM(h.nom_subprod) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO ME' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
						ELSE TRIM(h.producto)
					END AS producto
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent 
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				GROUP BY h.codcent 
			) p GROUP BY p.producto WITH ROLLUP
			UNION ALL 
			SELECT IFNULL(hi.producto, 'TOTALES') AS producto, COUNT(hi.clientes) AS clientes FROM (
				SELECT h.codcent AS clientes
				,CASE
					WHEN TRIM(h.nom_subprod) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO USADO ME' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'CONTIAUTO' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
					WHEN TRIM(h.nom_subprod) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
					ELSE TRIM(h.producto)
				END AS producto
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON h.idcliente_cartera =clicar2.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
			) hi GROUP BY hi.producto WITH ROLLUP
		) cli_con GROUP BY cli_con.producto
	) cli 
	INNER JOIN (
		SELECT cli_con.producto, SUM(cli_con.contrato) AS contrato, CAST(SUM(cli_con.monto) AS DECIMAL(12,2)) AS monto FROM (
			SELECT IFNULL(s.producto, 'TOTALES') AS producto, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
				SELECT h.contrato, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * 2.8, saldohoy * 7) ) AS saldohoyS
					,CASE
						WHEN TRIM(h.nom_subprod) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO ME' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
						ELSE TRIM(h.producto)
					END AS producto
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent 
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
			) s GROUP BY s.producto WITH ROLLUP
			UNION ALL
			SELECT IFNULL(s.producto, 'TOTALES') AS producto, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
				SELECT h.contrato, IF(h.divisa='PEN', saldohoy, IF(h.divisa='USD', saldohoy * 2.8, saldohoy * 7) ) AS saldohoyS
					,CASE
						WHEN TRIM(h.nom_subprod) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO ME' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
						WHEN TRIM(h.nom_subprod) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
						ELSE TRIM(h.producto)
					END AS producto
				FROM ca_historial h 
				INNER JOIN ca_cliente_cartera clicar ON h.idcliente_cartera=clicar.idcliente_cartera
					LEFT JOIN {$nameTmp} his ON h.contrato=his.contrato
				WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
				AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 ON clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$nameTmp} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
			) s GROUP BY s.producto WITH ROLLUP
		) cli_con GROUP BY cli_con.producto
	) con ON cli.producto=con.producto
EOT;

return $sql;
}

//~ Detalle de Clientes Nuevos y Retirados
function detalle_nuevo_retirado($ini, $fin, $tmpIni, $tmpFin, $agencia, $agenciaDetalle,$cartera)
{
$sql = <<<EOT
	SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.Nombre, h.divisa, h.saldoHoy, h.diavenc, h.ubigeo, 'Nuevo', 'Cliente Nuevo' AS ProNew
	FROM ca_historial h 
	INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera
		LEFT JOIN {$tmpIni} his ON h.codcent=his.codcent
	WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$fin}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
	UNION ALL
	SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.Nombre, h.divisa, h.saldoHoy, h.diavenc, h.ubigeo, 'Nuevo', 'Producto Nuevo' AS ProNew
	FROM ca_historial h 
	INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera
		LEFT JOIN {$tmpIni} his ON h.contrato=his.contrato
	WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$fin}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
		AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 on clicar2.idcliente_cartera=h.idcliente_cartera LEFT JOIN {$tmpIni} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$fin}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
	UNION ALL
	SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.Nombre, h.divisa, h.saldoHoy, h.diavenc, h.ubigeo, 'Retirado','Cliente Retirado' AS CliNew
	FROM ca_historial h
	INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera 
		LEFT JOIN {$tmpFin} his ON h.codcent=his.codcent
	WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$ini}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
	UNION ALL
	SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.Nombre, h.divisa, h.saldoHoy, h.diavenc, h.ubigeo, 'Retirado', 'Producto Retirado' AS ProNew
	FROM ca_historial h 
	INNER JOIN ca_cliente_cartera clicar on h.idcliente_cartera=clicar.idcliente_cartera
		LEFT JOIN {$tmpFin} his ON h.contrato=his.contrato
	WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$ini}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle}
		AND h.codcent NOT IN (SELECT h.codcent FROM ca_historial h INNER JOIN ca_cliente_cartera clicar2 On h.idcliente_cartera=clicar2.idcliente_cartera LEFT JOIN {$tmpFin} his ON h.codcent=his.codcent WHERE clicar2.idcartera='{$cartera}' AND h.Fproceso='{$ini}' AND h.agencia='{$agencia}' AND his.id IS NULL {$agenciaDetalle})
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Territorio
function resumen_foto_territorio($idcartera, $fin) 
{
$sql = <<<EOT
	SELECT IFNULL(cli.territorio,con.territorio) AS territorio, con.contrato, IFNULL(cli.clientes,0) AS clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(t.territorio, 'TOTALES') AS territorio,COUNT(t.cliente) AS clientes FROM ( 
			SELECT cuen.dato9 AS territorio, COUNT(cli.codigo) AS cliente
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			GROUP BY cli.codigo
		) t GROUP BY t.territorio WITH ROLLUP
	) cli 
	RIGHT JOIN (
		SELECT IFNULL(s.territorio, 'TOTALES') AS territorio, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
			SELECT cuen.dato9 AS territorio, cuen.numero_cuenta AS contrato, 
				IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS saldohoyS
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
		) s GROUP BY s.territorio WITH ROLLUP
	) con ON cli.territorio=con.territorio
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Ubigeo
function resumen_foto_ubigeo($idcartera,$fin) 
{
$sql = <<<EOT
	SELECT cli.ubigeo, con.contrato, cli.clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(ub.ubigeo, 'TOTALES') AS ubigeo,COUNT(ub.cliente) AS clientes FROM (
			SELECT u.ubigeo AS ubigeo, COUNT(u.codigo) AS cliente FROM (
				SELECT (SELECT d.ubigeo FROM ca_direccion d WHERE d.idcliente_cartera=cli_car.idcliente_cartera AND d.idtipo_referencia=3 LIMIT 1) AS ubigeo
					,cli.codigo
				FROM ca_cliente cli 
					INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
					INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
					INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
				WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			) u GROUP BY u.ubigeo, u.codigo
		) ub GROUP BY ub.ubigeo WITH ROLLUP
	) cli 
	INNER JOIN (
		SELECT IFNULL(s.ubigeo, 'TOTALES') AS ubigeo, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
			SELECT (SELECT d.ubigeo FROM ca_direccion d WHERE d.idcliente_cartera=cli_car.idcliente_cartera AND d.idtipo_referencia=3 LIMIT 1) AS ubigeo, 
				cuen.numero_cuenta AS contrato, 
				IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS saldohoyS
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
				INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
		) s GROUP BY s.ubigeo WITH ROLLUP
	) con ON cli.ubigeo=con.ubigeo
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Producto
function resumen_foto_producto($idcartera,$fin) 
{
$sql = <<<EOT
	SELECT cli.producto, con.contrato, cli.clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(p.producto, 'TOTALES') AS producto,COUNT(p.cliente) AS clientes FROM ( 
			SELECT COUNT(cli.codigo) AS cliente
				,CASE
					WHEN TRIM(cuen.dato3) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO USADO ME' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
					ELSE TRIM(cuen.producto)
				END AS producto
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
				INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			GROUP BY cli.codigo
		) p GROUP BY p.producto WITH ROLLUP
	) cli 
	INNER JOIN (
		SELECT IFNULL(s.producto, 'TOTALES') AS producto, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
			SELECT cuen.numero_cuenta AS contrato, 
				IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS saldohoyS
				,CASE
					WHEN TRIM(cuen.dato3) = 'AUT USAD CONVE USD' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUT USAD DEALER NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUT USADO MNAD NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENI NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO USD' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO CONVENIO USD .' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO USADO ME' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'AUTO USADO MN NUEVOS SOLES' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO CLIENTES MN/ SI' THEN 'CONTIAUTO'
					WHEN TRIM(cuen.dato3) = 'CONTIAUTO GNV' THEN 'CONTIAUTO'
					ELSE TRIM(cuen.producto)
				END AS producto
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
				INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
		) s GROUP BY s.producto WITH ROLLUP
	) con ON cli.producto=con.producto
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Tramo
function resumen_foto_tramo($idcartera,$fin) 
{
$sql = <<<EOT
	SELECT cli.tramo, con.contrato, cli.clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(tr.tramo, 'TOTALES') AS tramo,COUNT(tr.cliente) AS clientes FROM (
			SELECT COUNT(t.codigo) AS cliente,
				CASE 
					WHEN CAST(t.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
					WHEN CAST(t.dias_mora AS SIGNED) > 30 AND CAST(t.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
					WHEN CAST(t.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'	END AS tramo 
			FROM (
				SELECT cli.codigo,cuen_deta.dias_mora
				FROM ca_cliente cli 
					INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
					INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
					INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
				WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			) t GROUP BY t.codigo
		) tr GROUP BY tr.tramo WITH ROLLUP
	) cli 
	INNER JOIN (
		SELECT IFNULL(s.tramo, 'TOTALES') AS tramo, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
			SELECT CASE 
					WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
					WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
					WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
					ELSE 'NO_TRAMO'
				END AS tramo,cuen.numero_cuenta AS contrato, 
				IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS saldohoyS
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
				INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
		) s GROUP BY s.tramo WITH ROLLUP
	) con ON cli.tramo=con.tramo
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Grupo
function resumen_foto_grupo($idcartera,$fin) 
{
$sql = <<<EOT
	SELECT cli.marca, con.contrato, cli.clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(ma.marca, 'TOTALES') AS marca,COUNT(ma.cliente) AS clientes FROM (
			SELECT m.marca AS marca, COUNT(m.codigo) AS cliente FROM (
				SELECT CASE 
					WHEN cuen.dato8 LIKE '%VIP%' THEN 'VIP'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 01%' THEN 'GRUPO_01'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 02%' THEN 'GRUPO_02'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 03%' THEN 'GRUPO_03'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 04%' THEN 'GRUPO_04'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 05%' THEN 'GRUPO_05'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 06%' THEN 'GRUPO_06'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 07%' THEN 'GRUPO_07'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 08%' THEN 'GRUPO_08'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 09%' THEN 'GRUPO_09'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 10%' THEN 'GRUPO_10'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 11%' THEN 'GRUPO_11'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 12%' THEN 'GRUPO_12'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 13%' THEN 'GRUPO_13'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 14%' THEN 'GRUPO_14'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 15%' THEN 'GRUPO_15'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 16%' THEN 'GRUPO_16'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 17%' THEN 'GRUPO_17'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 18%' THEN 'GRUPO_18'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 19%' THEN 'GRUPO_19'
					WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 20%' THEN 'GRUPO_20'
					ELSE cuen.dato8
					END AS marca, cli.codigo
				FROM ca_cliente cli 
					INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
					INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
					INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
				WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			) m GROUP BY m.marca, m.codigo
		) ma GROUP BY ma.marca WITH ROLLUP
	) cli 
	INNER JOIN (
		SELECT IFNULL(s.marca, 'TOTALES') AS marca, COUNT(s.contrato) AS contrato, SUM(s.saldohoyS) AS monto FROM (
			SELECT CASE 
				WHEN cuen.dato8 LIKE '%VIP%' THEN 'VIP'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 01%' THEN 'GRUPO_01'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 02%' THEN 'GRUPO_02'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 03%' THEN 'GRUPO_03'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 04%' THEN 'GRUPO_04'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 05%' THEN 'GRUPO_05'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 06%' THEN 'GRUPO_06'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 07%' THEN 'GRUPO_07'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 08%' THEN 'GRUPO_08'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 09%' THEN 'GRUPO_09'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 10%' THEN 'GRUPO_10'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 11%' THEN 'GRUPO_11'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 12%' THEN 'GRUPO_12'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 13%' THEN 'GRUPO_13'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 14%' THEN 'GRUPO_14'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 15%' THEN 'GRUPO_15'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 16%' THEN 'GRUPO_16'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 17%' THEN 'GRUPO_17'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 18%' THEN 'GRUPO_18'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 19%' THEN 'GRUPO_19'
				WHEN cuen.dato8 NOT LIKE '%VIP%' AND cuen.dato8 LIKE '%GR 20%' THEN 'GRUPO_20'
				ELSE cuen.dato8
				END AS marca,cuen.numero_cuenta AS contrato, 
				IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS saldohoyS
			FROM ca_cliente cli 
				INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
				INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
				INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
			WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
		) s GROUP BY s.marca WITH ROLLUP
	) con ON cli.marca=con.marca
EOT;

return $sql;
}

//~ Resumen Fotocartera - por Monto
function resumen_foto_monto($idcartera,$fin) 
{
$sql = <<<EOT
	SELECT cli.rangoMonto, con.contrato, cli.clientes, CAST(con.monto AS DECIMAL(12,2)) AS monto FROM (
		SELECT IFNULL(tr.rangoMonto, 'TOTALES') AS rangoMonto,COUNT(tr.cliente) AS clientes FROM (
			SELECT COUNT(t.codigo) AS cliente,
				CASE 
					WHEN t.sol <= 10 THEN '0-10'
					WHEN t.sol > 10 AND t.sol <= 100 THEN '10-100'
					WHEN t.sol > 100 AND t.sol <= 1000 THEN '100-1000'
					WHEN t.sol > 1000 AND t.sol <= 3000 THEN '1000-3000'
					WHEN t.sol > 3000 AND t.sol <= 7000 THEN '3000-7000'
					WHEN t.sol > 7000 THEN '7000+'
				ELSE '0' END AS rangoMonto 
			FROM (
				SELECT s.codigo, s.sol FROM (
					SELECT cli.codigo,IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS sol
					FROM ca_cliente cli 
						INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
						INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
						INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
					WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
				) s 
			) t GROUP BY t.codigo
		) tr GROUP BY tr.rangoMonto WITH ROLLUP
	) cli 
	INNER JOIN (
		SELECT IFNULL(so.rangoMonto, 'TOTALES') AS rangoMonto, COUNT(so.contrato) AS contrato, SUM(so.sol) AS monto FROM (
			SELECT s.contrato, s.sol, 
				 CASE 
					WHEN s.sol <= 10 THEN '0-10'
					WHEN s.sol > 10 AND s.sol <= 100 THEN '10-100'
					WHEN s.sol > 100 AND s.sol <= 1000 THEN '100-1000'
					WHEN s.sol > 1000 AND s.sol <= 3000 THEN '1000-3000'
					WHEN s.sol > 3000 AND s.sol <= 7000 THEN '3000-7000'
					WHEN s.sol > 7000 THEN '7000+'
					ELSE '0'
				END AS rangoMonto
			FROM (
				SELECT cuen.numero_cuenta AS contrato, 
					IF(cuen.moneda='PEN', cuen.total_deuda, IF(cuen.moneda='USD', cuen.total_deuda * 2.8, cuen.total_deuda * 7) ) AS sol
				FROM ca_cliente cli 
					INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
					INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
					INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
				WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
			) s
		) so GROUP BY so.rangoMonto WITH ROLLUP
	) con ON cli.rangoMonto=con.rangoMonto
EOT;

return $sql;
}

//~ FotoCartera
function sql_fotocartera($idcartera,$fin,$tipocambio,$tipovac)
{
$sql = <<<EOT
	SELECT cuen.dato1 AS Fproceso, cli_car.dato1 AS agencia, cuen.dato9 AS territorio, cuen.producto AS producto,  cuen.dato3 AS nom_subprod
		,cuen.numero_cuenta AS contrato, cli.codigo AS codcent,cli.nombre AS Nombre, cuen.moneda AS divisa,cuen.total_deuda AS saldohoy
		,cuen_deta.dias_mora AS diavenc
		,(SELECT d.ubigeo FROM ca_direccion d WHERE d.idcliente_cartera=cli_car.idcliente_cartera AND d.idtipo_referencia=3 LIMIT 1) AS ubigeo
		,(SELECT d.departamento FROM ca_direccion d WHERE d.idcliente_cartera=cli_car.idcliente_cartera AND d.idtipo_referencia=4 LIMIT 1) AS departamento
		,cli_car.dato6 AS dist_prov,cuen_deta.tramo AS tramo_dia,cuen.dato8 AS marca,cli_car.dato4 AS oficina,cli_car.dato8 AS oficina2 ,
		IF(cuen.moneda='USD',{$tipocambio}*cuen.total_deuda,IF(cuen.moneda='VAC',{$tipovac}*cuen.total_deuda,cuen.total_deuda)) AS neto_soles
	FROM ca_cliente cli 
		INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente 
		INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera 
		INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta 
	WHERE cuen.idcartera IN ({$idcartera}) AND cli_car.idcartera IN ({$idcartera}) AND cuen.dato1='{$fin}'
EOT;

return $sql;
}

//~ Tramo Actual
function sql_tramo_actual($final, $nameTmp, $agencia, $agenciaDetalle,$cartera)
{
$sql = <<<EOT
	SELECT IFNULL(t.tramo_actual, 'TOTALES') AS tramo_actual, COUNT(t.contrato) AS contrato 
	FROM (
		SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.nombre, h.divisa, h.saldohoy, h.diavenc, h.ubigeo,
			CASE 
				WHEN CAST(h.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(h.diavenc AS SIGNED) > 30 AND CAST(h.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(h.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_actual,
			CASE 
				WHEN CAST(his.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(his.diavenc AS SIGNED) > 30 AND CAST(his.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(his.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_anterior, his.diavenc AS dia_anterior
		FROM ca_historial h 
		INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=h.idcliente_cartera
			INNER JOIN {$nameTmp} his ON h.contrato=his.contrato
		WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' {$agenciaDetalle}
	) t WHERE t.tramo_actual!=t.tramo_anterior
	GROUP BY t.tramo_actual WITH ROLLUP
EOT;

return $sql;
}

//~ Tramo Anterior - Tramos
function sql_tramo_anterior($final, $nameTmp, $tramo, $agencia, $agenciaDetalle,$cartera)
{
$sql = <<<EOT
	SELECT IFNULL(t.tramo_anterior, 'TOTALES') AS tramo_anterior, COUNT(t.contrato) AS contrato 
	FROM (
		SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.nombre, h.divisa, h.saldohoy, h.diavenc, h.ubigeo,
			CASE 
				WHEN CAST(h.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(h.diavenc AS SIGNED) > 30 AND CAST(h.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(h.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_actual,
			CASE 
				WHEN CAST(his.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(his.diavenc AS SIGNED) > 30 AND CAST(his.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(his.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_anterior, his.diavenc AS dia_anterior
		FROM ca_historial h
		INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=h.idcliente_cartera 
			INNER JOIN {$nameTmp} his ON h.contrato=his.contrato
		WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' {$agenciaDetalle}
	) t WHERE t.tramo_anterior!=t.tramo_actual AND t.tramo_actual='{$tramo}'
	GROUP BY t.tramo_anterior WITH ROLLUP
EOT;

return $sql;
}

//~ Tramo Anterior - Detalles
function sql_tramo_detalle($final, $nameTmp, $agencia, $agenciaDetalle,$cartera)
{
$sql = <<<EOT
	SELECT * FROM (
		SELECT h.territorio, h.producto, h.nom_subprod, h.contrato, h.codcent, h.nombre, h.divisa, h.saldohoy, h.diavenc, h.ubigeo,
			CASE 
				WHEN CAST(h.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(h.diavenc AS SIGNED) > 30 AND CAST(h.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(h.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_actual,
			CASE 
				WHEN CAST(his.diavenc AS SIGNED) <= 30 THEN 'TRAM0_1'
				WHEN CAST(his.diavenc AS SIGNED) > 30 AND CAST(his.diavenc AS SIGNED) <= 60 THEN 'TRAM0_2'
				WHEN CAST(his.diavenc AS SIGNED) > 60 THEN 'TRAM0_3'
				ELSE 'NO_TRAMO'
			END AS tramo_anterior, his.diavenc AS dia_anterior
		FROM ca_historial h 
		INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=h.idcliente_cartera
			INNER JOIN {$nameTmp} his ON h.contrato=his.contrato
		WHERE clicar.idcartera='{$cartera}' AND h.Fproceso='{$final}' AND h.agencia='{$agencia}' {$agenciaDetalle}
	) t WHERE t.tramo_actual!=t.tramo_anterior
EOT;

return $sql;
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

// Generar el filtro de Consulta para Comercial y Natural
function filtroComercial($opcion) 
{
	if ($opcion=='COMERCIAL') {
		$filtro = " AND h.producto='COMERCIAL' ";
	}
	elseif ($opcion=='NATURAL') {
		$filtro = " AND h.producto!='COMERCIAL' ";
	}
	else {
		$filtro = " ";
	}

	return $filtro;
}

//~ Vic F

?>
