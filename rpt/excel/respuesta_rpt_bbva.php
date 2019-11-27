<?php
	header('Content-Type: text/html; charset=UTF-8');
	header("Content-Disposition:atachment;filename=RESPUESTA_".date('Ymd-His').".txt");
	header("Content-Type: application/force-download");
	header("Content-Transfer-Encoding: binary");
	header("Pragma:no-cache");
	header("Expires:0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	$confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
	$user = $confCobrast['user_db']['user_rpt'];
	$password = $confCobrast['user_db']['password_rpt'];

	date_default_timezone_set('America/Lima');

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection($user,$password);

	$carteras = $_REQUEST['carteras'];
	//$idcarteras = "'".$carteras."'";
	$fecha_inicio = $_REQUEST['fecha_inicio'];
	$fecha_fin = $_REQUEST['fecha_fin'];
	$datePro = explode("-",$_REQUEST['fecha_proceso']);
	$fecha_proceso = $datePro[2]."-".nameMonth($datePro[1])."-".substr($datePro[0], 2, 2);

	$fecha_inicio_vis = $_REQUEST['fecha_inicio_vis'];
	$fecha_fin_vis = $_REQUEST['fecha_fin_vis'];
	$servicio=$_REQUEST['servicio'];

	function nameMonth($mes) 
	{
		$meses = array('Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
						'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12');
		$txt = array_search($mes, $meses);
		return $txt;
	}

/*$sql = <<<EOT
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(lla.fecha, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		LEFT( CONCAT_WS('',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(lla.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'                                                                                                                                    '),100) AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(lla.fecha, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', IF(SUBSTRING(te.observacion,1,1) not in ('C','O','D'),'',SUBSTRING(te.observacion,1,1)), '     '), 3) AS tipo,
		LEFT( CONCAT_WS('', te.referencia, '     '), 3) AS prefijo,
		LEFT( CONCAT_WS('', replace(te.numero,'-',''), '            '), 10) AS telefono,
		LEFT( CONCAT_WS('', te.anexo, '     '), 4) AS anexo,
		LEFT( CONCAT_WS('', dir.direccion, '                                                            ') , 50) AS direccion,
		LEFT( CONCAT_WS('', IF(dir.departamento REGEXP ('^[0-9]')=0,'',dir.departamento), '    '), 2) AS departamento,
		LEFT( CONCAT_WS('', IF(dir.provincia REGEXP ('^[0-9]')=0,'',dir.provincia), '    '), 2) AS provincia,
		LEFT( CONCAT_WS('', IF(dir.distrito REGEXP ('^[0-9]')=0,'',dir.distrito), '    '), 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(lla.fecha_cp, '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(lla.fecha_cp - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_llamada lla
		INNER JOIN ca_cliente_cartera cli_car ON lla.idcliente_cartera=cli_car.idcliente_cartera
		INNER JOIN ca_cuenta cuen ON lla.idcuenta=cuen.idcuenta
		INNER JOIN ca_final_servicio fin_ser ON lla.idfinal=fin_ser.idfinal
		LEFT JOIN ca_telefono te ON lla.idtelefono=te.idtelefono
		LEFT JOIN ca_direccion dir ON cli_car.idcliente_cartera=dir.idcliente_cartera
	WHERE DATE_FORMAT(lla.fecha,'%Y-%m-%d') BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}' AND dir.idtipo_referencia=3 AND cuen.dato1='{$fecha_proceso}' AND cuen.idcartera IN ({$carteras}) 
		GROUP BY lla.idcliente_cartera,cuen.idcuenta,lla.fecha,lla.fecha_cp,lla.monto_cp
	UNION ALL
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(vi.fecha_visita, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		LEFT( CONCAT_WS('',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(vi.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'                                                                                                                                    '),100) AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(vi.fecha_visita, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', 'O    '), 3) AS tipo,
		LEFT( CONCAT_WS('', '00000'), 3) AS prefijo,
		LEFT( CONCAT_WS('', '000000000000000'), 10) AS telefono,
		LEFT( CONCAT_WS('', '00000'), 4) AS anexo,
		LEFT( CONCAT_WS('', dir.direccion, '                                                            ') , 50) AS direccion,
		LEFT( CONCAT_WS('', IF(dir.departamento REGEXP ('^[0-9]')=0,'',dir.departamento), '    '), 2) AS departamento,
		LEFT( CONCAT_WS('', IF(dir.provincia REGEXP ('^[0-9]')=0,'',dir.provincia), '    '), 2) AS provincia,
		LEFT( CONCAT_WS('', IF(dir.distrito REGEXP ('^[0-9]')=0,'',dir.distrito), '    '), 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(vi.fecha_cp, '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(vi.fecha_cp - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_visita vi
		INNER JOIN ca_cliente_cartera cli_car ON vi.idcliente_cartera=cli_car.idcliente_cartera
		INNER JOIN ca_cuenta cuen ON vi.idcuenta=cuen.idcuenta
		INNER JOIN ca_final_servicio fin_ser ON vi.idfinal=fin_ser.idfinal
		LEFT JOIN ca_direccion dir ON vi.iddireccion=dir.iddireccion
	WHERE DATE_FORMAT(vi.fecha_visita,'%Y-%m-%d') BETWEEN '{$fecha_inicio_vis}' AND '{$fecha_fin_vis}' AND cuen.dato1='{$fecha_proceso}' AND cuen.idcartera IN ({$carteras})
	GROUP BY fecha_contacto,codigo_cliente,numero_cuenta

EOT;*/

$sql = <<<EOT
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		RPAD(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(MEJOR.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'',''),'Ã',''),' ',' '),char(9),''),char(10),''),char(13),''),'Â',''),'°',''),'º',''),'³',''),3000,' ') AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', IF(SUBSTRING(IFNULL(te.observacion,''),1,1) not in ('C','O','D'),'O',SUBSTRING(te.observacion,1,1)), '     '), 3) AS tipo,
		LEFT( CONCAT_WS('', IF(IFNULL(te.referencia,'')='','001',te.referencia), '     '), 3) AS prefijo,
		LPAD( replace(IFNULL(te.numero_act,te.numero),'-',''), 10,'0') AS telefono,
		LPAD( CAST(IFNULL(te.anexo,'') as SIGNED), 4,'0') AS anexo,
		RPAD('',300,' ') AS direccion,
		LEFT(  '    ', 2) AS departamento,
		LEFT(  '    ', 2) AS provincia,
		LEFT(  '    ', 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(IF(DATE(MEJOR.fecha_cp)<DATE(now()),'',MEJOR.fecha_cp), '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(IF(DATE(MEJOR.fecha_cp)<DATE(now()),'',MEJOR.fecha_cp) - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_cliente_cartera cli_car 
		INNER JOIN ca_cuenta cuen ON cuen.idcliente_cartera=cli_car.idcliente_cartera
		INNER JOIN (
			SELECT * FROM (
				SELECT lla.idcliente_cartera,finser.peso,lla.idfinal,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.observacion,lla.idtelefono FROM ca_llamada lla
				INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
				INNER JOIN ca_final fin ON fin.idfinal=lla.idfinal
				INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
				WHERE DATE_FORMAT(lla.fecha,'%Y-%m-%d') BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}'  and finser.idservicio=6 and clicar.idcartera IN ({$carteras})
				ORDER BY lla.idcliente_cartera,finser.peso DESC
			)A 
			
		)MEJOR ON MEJOR.idcliente_cartera=cuen.idcliente_cartera
		INNER JOIN ca_final_servicio fin_ser ON MEJOR.idfinal=fin_ser.idfinal
		LEFT JOIN ca_telefono te ON MEJOR.idtelefono=te.idtelefono
	WHERE cuen.dato1='{$fecha_proceso}' AND cuen.idcartera IN ({$carteras}) and fin_ser.idservicio={$servicio} and cuen.estado=1
	GROUP BY MEJOR.idcliente_cartera,cuen.idcuenta,MEJOR.fecha
	UNION ALL
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha_visita, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		RPAD(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(MEJOR.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'',''),'Ã',''),' ',' '),char(9),''),char(10),''),char(13),''),'Â',''),'°',''),'º',''),'³',''),3000,' ') AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha_visita, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', '     '), 3) AS tipo,
		LEFT( CONCAT_WS('', '    '), 3) AS prefijo,
		LEFT( CONCAT_WS('', '           '), 10) AS telefono,
		LEFT( CONCAT_WS('', '    '), 4) AS anexo,
		RPAD(IF(dir.is_new=1,dir.direccion,''),300,' ') AS direccion,
		LEFT( CONCAT_WS('', IF(IF(dir.is_new=1,dir.departamento,'') REGEXP ('^[0-9]')=0,'',dir.departamento), '    '), 2) AS departamento,
		LEFT( CONCAT_WS('', IF(IF(dir.is_new=1,dir.provincia,'') REGEXP ('^[0-9]')=0,'',dir.provincia), '    '), 2) AS provincia,
		LEFT( CONCAT_WS('', IF(IF(dir.is_new=1,dir.distrito,'') REGEXP ('^[0-9]')=0,'',dir.distrito), '    '), 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(IF(DATE(MEJOR.fecha_cp)<DATE(now()),'',MEJOR.fecha_cp), '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(IF(DATE(MEJOR.fecha_cp)<DATE(now()),'',MEJOR.fecha_cp) - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_cliente_cartera cli_car 
		INNER JOIN ca_cuenta cuen ON cuen.idcliente_cartera=cli_car.idcliente_cartera
		LEFT JOIN ca_direccion dir ON cli_car.idcliente_cartera=dir.idcliente_cartera
		INNER JOIN (
			SELECT * FROM (
				SELECT vis.idcuenta,vis.idcliente_cartera,finser.peso,vis.idfinal,vis.fecha_visita,vis.fecha_cp,vis.monto_cp,vis.observacion,vis.iddireccion FROM ca_visita vis
				INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=vis.idcliente_cartera
				INNER JOIN ca_final fin ON fin.idfinal=vis.idfinal
				INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
				WHERE DATE_FORMAT(vis.fecha_visita,'%Y-%m-%d') BETWEEN '{$fecha_inicio_vis}' AND '{$fecha_fin_vis}'  and finser.idservicio={$servicio} and clicar.idcartera IN ({$carteras})
				ORDER BY vis.idcliente_cartera,finser.peso DESC
			)A 
			)MEJOR ON MEJOR.idcliente_cartera=cuen.idcliente_cartera
		INNER JOIN ca_final_servicio fin_ser ON MEJOR.idfinal=fin_ser.idfinal
	WHERE cuen.dato1='{$fecha_proceso}' AND cuen.idcartera IN ({$carteras}) and fin_ser.idservicio={$servicio} and cuen.estado=1
	GROUP BY MEJOR.idcliente_cartera,cuen.idcuenta,MEJOR.fecha_visita

EOT;

/*$sql = <<<EOT
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		LEFT( CONCAT_WS('',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(MEJOR.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'',''),'Ã',''),' ',' '),char(9),''),char(10),''),char(13),''),'Â',''),'°',''),'º',''),'                                                                                                                                    '),100) AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', IF(SUBSTRING(IFNULL(te.observacion,''),1,1) not in ('C','O','D'),'O',SUBSTRING(te.observacion,1,1)), '     '), 3) AS tipo,
		LEFT( CONCAT_WS('', IF(IFNULL(te.referencia,'')='','001',te.referencia), '     '), 3) AS prefijo,
		LPAD( replace(IFNULL(te.numero_act,te.numero),'-',''), 10,'0') AS telefono,
		LPAD( IFNULL(te.anexo,''), 4,'0') AS anexo,
		LEFT('                                                            ' , 50) AS direccion,
		LEFT('    ', 2) AS departamento,
		LEFT( '    ', 2) AS provincia,
		LEFT( '    ', 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(MEJOR.fecha_cp, '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(MEJOR.fecha_cp - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_cliente_cartera cli_car 
		INNER JOIN ca_cuenta cuen ON cuen.idcliente_cartera=cli_car.idcliente_cartera
		LEFT JOIN ca_direccion dir ON cli_car.idcliente_cartera=dir.idcliente_cartera
		INNER JOIN (
			SELECT * FROM (
				SELECT lla.idcliente_cartera,finser.peso,lla.idfinal,lla.fecha,lla.fecha_cp,lla.monto_cp,lla.observacion,lla.idtelefono FROM ca_llamada lla
				INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=lla.idcliente_cartera
				INNER JOIN ca_final fin ON fin.idfinal=lla.idfinal
				INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
				WHERE DATE_FORMAT(lla.fecha,'%Y-%m-%d') BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}'  and finser.idservicio=6 and clicar.idcartera IN ({$carteras})
				ORDER BY lla.idcliente_cartera,finser.peso DESC
			)A 
			
		)MEJOR ON MEJOR.idcliente_cartera=cuen.idcliente_cartera
		INNER JOIN ca_final_servicio fin_ser ON MEJOR.idfinal=fin_ser.idfinal
		LEFT JOIN ca_telefono te ON MEJOR.idtelefono=te.idtelefono
	WHERE dir.idtipo_referencia=3 AND cuen.idcartera IN ({$carteras}) and fin_ser.idservicio={$servicio}
	GROUP BY MEJOR.idcliente_cartera,cuen.idcuenta,MEJOR.fecha
	UNION ALL
	SELECT DISTINCT 
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha_visita, '%Y%m%d'), '          '), 8) AS fecha_contacto,
		LEFT( CONCAT_WS('', cli_car.codigo_cliente, '         '), 8) AS codigo_cliente,
		LEFT( CONCAT_WS('', cuen.numero_cuenta, '                         '), 18) AS numero_cuenta,
		LEFT( CONCAT_WS('', fin_ser.codigo, '     '), 3) AS codigo,
		LEFT('          ', 8) AS fecha_preparacion,
		LEFT( CONCAT_WS('',REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(MEJOR.observacion, '\n', ' '), 'ñ', 'n'), 'Ñ', 'N'),'Ã','N'),'Ã±','n'),'Ã',''),'',''),'Ã',''),' ',' '),char(9),''),char(10),''),char(13),''),'Â',''),'°',''),'º',''),'                                                                                                                                    '),100) AS observacion,
		LEFT('          ', 8) AS campo_nulo,
		LEFT( CONCAT_WS('', DATE_FORMAT(MEJOR.fecha_visita, '%Y-%m-%d-%H.%i.%s'), '.000000000000000'), 26 ) AS hora,
		LEFT( CONCAT_WS('', 'O    '), 3) AS tipo,
		LEFT( CONCAT_WS('', '00000'), 3) AS prefijo,
		LEFT( CONCAT_WS('', '000000000000000'), 10) AS telefono,
		LEFT( CONCAT_WS('', '00000'), 4) AS anexo,
		LEFT( '                                                            ' , 50) AS direccion,
		LEFT(  '    ', 2) AS departamento,
		LEFT(  '    ', 2) AS provincia,
		LEFT(  '    ', 3) AS distrito,
		LEFT( CONCAT_WS('',DATE_FORMAT(MEJOR.fecha_cp, '%d/%m/%Y'),'            '), 10) AS fecha_compromiso,
		LEFT( CONCAT_WS('',DATE_FORMAT(MEJOR.fecha_cp - INTERVAL 1 DAY, '%d/%m/%Y'),'           '), 10) AS fecha_revision
	FROM ca_cliente_cartera cli_car 
		INNER JOIN ca_cuenta cuen ON cuen.idcliente_cartera=cli_car.idcliente_cartera
		LEFT JOIN ca_direccion dir ON cli_car.idcliente_cartera=dir.idcliente_cartera
		INNER JOIN (
			SELECT * FROM (
				SELECT vis.idcuenta,vis.idcliente_cartera,finser.peso,vis.idfinal,vis.fecha_visita,vis.fecha_cp,vis.monto_cp,vis.observacion,vis.iddireccion FROM ca_visita vis
				INNER JOIN ca_cliente_cartera clicar on clicar.idcliente_cartera=vis.idcliente_cartera
				INNER JOIN ca_final fin ON fin.idfinal=vis.idfinal
				INNER JOIN ca_final_servicio finser ON finser.idfinal=fin.idfinal
				WHERE DATE_FORMAT(vis.fecha_visita,'%Y-%m-%d') BETWEEN '{$fecha_inicio_vis}' AND '{$fecha_fin_vis}'  and finser.idservicio={$servicio} and clicar.idcartera IN ({$carteras})
				ORDER BY vis.idcliente_cartera,finser.peso DESC
			)A 
			)MEJOR ON MEJOR.idcliente_cartera=cuen.idcliente_cartera
		INNER JOIN ca_final_servicio fin_ser ON MEJOR.idfinal=fin_ser.idfinal
	WHERE dir.idtipo_referencia=3 AND cuen.idcartera IN ({$carteras}) and fin_ser.idservicio={$servicio}
	GROUP BY MEJOR.idcliente_cartera,cuen.idcuenta,MEJOR.fecha_visita

EOT;*/
//echo $sql;
//exit();
	$pr = $connection->prepare($sql);
	$pr->execute();
	$txts = "";

	$buscar= array("Ã‘","Ã","Ã±","±","á","é", "í","ó","ú", "É","Ó","Ú", "Ã","Ã¡", "Ã", "Ã?", "Ã©", "Ãš", "Ã",  "Ã",  "Ã­", "Ã¬", "Ã?",  "Ã", "Ã³", "Ã²",  "Ã",  "Ã", "Ãº", "Ã¹", "Ã",   "Ã","Ã?", "Â", "Ã","â",  "*", "Ã" , "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","õ","ö","÷","ø","ù","ú","û","ü","ý","þ","ÿ","Œ","œ","Š","š","Ÿ","ƒ","†","‡","‰","€","Â?");
	$cambiar=array("  "," ","  "," ","a", "e","i","o","u", "E","O","U","N ","a ","a ", "A ", "A ", "e ", "e ", "E ",  "E", "i ", "i ", "I ", "I ", "o ", "o ", "O ", "O ", "u ", "u ", "U ","A ","A ","A "," ",  " ", "A" , " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " "," "," "," ","D","N","O","O","O","O","O"," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," ","  ");

        
        //echo count($buscar);exit();
            
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		$txts .= $row['fecha_contacto'];
		$txts .= $row['codigo_cliente'];
		$txts .= $row['numero_cuenta'];
		$txts .= $row['codigo'];
		$txts .= $row['fecha_preparacion'];
		$txts .= utf8_encode(str_replace($buscar,$cambiar,$row['observacion']));
                
		//$txts .= ereg_replace("[^A-Za-z0-9]", "",$row['observacion']);
		$txts .= $row['campo_nulo'];
		$txts .= $row['hora'];
		$txts .= $row['tipo'];
		$txts .= $row['prefijo'];
		$txts .= $row['telefono'];
		$txts .= $row['anexo'];
		$txts .= $row['direccion'];
		$txts .= $row['departamento'];
		$txts .= $row['provincia'];
		$txts .= $row['distrito'];
		$txts .= $row['fecha_compromiso'];
		$txts .= $row['fecha_revision'];
		$txts .= "\r\n";
	}
	echo $txts;
?>
