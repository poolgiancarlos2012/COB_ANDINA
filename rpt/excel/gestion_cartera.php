<?php
	
	$sql = " SELECT detcu.tramo AS 'TRAMO', COUNT( DISTINCT clicar.idcliente_cartera ) AS 'CLIENTES',
			ROUND( SUM( IF( UPPER(cu.moneda) = 'USD', cu.total_deuda, cu.total_deuda/2.77 ) ), 2) AS 'DEUDA',
			ROUND( SUM( IF( UPPER(cu.moneda) = 'USD', cu.total_comision, cu.total_comision/2.77 ) ), 2) AS 'INTERES',
			ROUND( SUM( cu.monto_pagado ),2 ) AS 'PAGO'
			FROM ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_detalle_cuenta detcu 
			ON detcu.idcuenta  = cu.idcuenta AND cu.idcliente_cartera = clicar.idcliente_cartera
			WHERE detcu.idcartera = 19 AND cu.idcartera = 19 AND clicar.idcartera = 19
			GROUP BY TRIM(detcu.tramo) WITH ROLLUP ";
	
?>