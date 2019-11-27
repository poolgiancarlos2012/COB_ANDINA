<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=fotocartera_bbva.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');
	$Servicio = $_GET['Servicio'];
	$idCartera = $_GET['Cartera'];
	$nombre_servicio = $_GET['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	$sqlDataCartera = "SELECT idcartera,tabla,archivo,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera IN (".$idCartera.")";

	$prData = $connection->prepare($sqlDataCartera);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);

	$cliente = $dataCartera[0]['cliente'];
	$clienteField = campos($cliente, "cli");

	$cuenta = $dataCartera[0]['cuenta'];
	$cuentaField = campos($cuenta, "cuen");

	$detalle_cuenta = $dataCartera[0]['detalle_cuenta'];
	$detalle_cuentaField = campos($detalle_cuenta, "cuen_deta");

	$telefono = $dataCartera[0]['telefono'];
	$arrayTel = selectFono($telefono);

	$direcciones = $dataCartera[0]['direccion'];
	$arrayFinDir = selectDir($direcciones);

	$adicionales = $dataCartera[0]['adicionales'];
	$adicionalesField = adicionales($adicionales);

$sql = <<<EOT
	SELECT
		{$clienteField},
		{$cuentaField},
		{$detalle_cuentaField},
		{$arrayTel},
		{$arrayFinDir},
		{$adicionalesField},
		cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
		(SELECT pri.nombre AS idGestor FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_privilegio pri ON us.idprivilegio=pri.idprivilegio WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS idGestor,
		(SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS asignado FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS asignado
		,CASE 
			WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
			WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
			WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
			ELSE 'NO_TRAMO'
		END AS tramo_dia_hdec, IF(LEFT(TRIM(cuen.dato8),2)='VI','VIP','NO VIP') AS marca_grupo 
		,(SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio) AS codigo_user
		,date(cli_car.fecha_creacion) AS fecha_creacion_cliente,
		ml_estado,
		(select nombre from ca_final where idfinal=cuen.ml_estado) AS 'mejor_llamada',
		(select  finser.codigo from ca_final_servicio finser INNER JOIN ca_final fin On fin.idfinal=finser.idfinal where finser.estado = 1 and finser.idservicio=6 and fin.idfinal = ml_estado ) AS 'Cod_Gestion_Llamada',
		mv_estado, 
		(select  finser.codigo from ca_final_servicio finser INNER JOIN ca_final fin On fin.idfinal=finser.idfinal where finser.estado = 1 and finser.idservicio=6 and fin.idfinal = mv_estado ) AS 'Cod_Gestion_Visita'



	FROM ca_cliente cli
		INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
		INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
		INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
	WHERE cuen.idcartera={$idCartera}
EOT;

	// echo $sql;
	// exit();
	//~ $resultado = mysql_query($sql);

	$tel = json_decode($telefono,true);
	$telFin = eliminar_null($tel);
	$dir = json_decode($direcciones,true);
	$dirFin = eliminar_null($dir);

	$html = "";
	//~ $html = "<table border='1' style='font-size:10px'>";
	//~ $html .="<tr>";
	$html .= "codcent\t";
	$html .= "Nombre\t";
	$html .= "nro_doc\t";
	$html .= "tipodoc\t";
	$html .= "divisa\t";
	$html .= "saldohoy\t";
	$html .= "producto\t";
	$html .= "tramo_dia_hdec\t";
	$html .= "contrato\t";
	$html .= "divisa\t";
	$html .= "tramo_dia\t";
	$html .= "diavenc\t";

	foreach($telFin as $kt => $vt)
	{
		foreach ($vt as $ket => $vat)
		{
			$html .="".$vat."\t";
		}
	}

	foreach($dirFin as $k => $v)
	{
		foreach ($v as $ke => $va)
		{
			$html .="".$va."\t";
		}
	}

	$html .= "agencia\t";
	$html .= "Gestor\t";
	$html .= "territorio\t";
	$html .= "oficina\t";
	$html .= ($Servicio == 6) ? "tpersona\t" : "";
	$html .= "dist_prov\t";
	$html .= ($Servicio == 6) ? "email\t" : "";
	$html .= "oficina2\t";
	$html .= "Fproceso\t";
	$html .= "subprod\t";
	$html .= "nom_subprod\t";
	$html .= "fincumpli\t";
	$html .= ($Servicio == 6) ? "provision\t" : "";
	$html .= ($Servicio == 6) ? "fecha_de_generacion\t" : "";
	$html .= ($Servicio == 6) ? "fecha_de_ingreso\t" : "";
	$html .= ($Servicio == 6) ? "marca\t" : "";
	$html .= "marca_grupo\t";
	$html .= "statusCLIENTE\t";
	$html .= "statusCUENTA\t";
	$html .= "idGestor\t";
	$html .= "asignado\t";
	$html .= "codigo_user\t";
	$html .= "fecha_creacion_cliente\t";
	$html .= "mejor_llamada\t";
	$html .= "Cod_Gestion_Llamada\t";
	$html .= "Cod_Gestion_Visita\t";


	$html .="\r\n";
	//~ $html .="</tr>";

	$prFC = $connection->prepare($sql);
	$prFC->execute();

	while( $fila = $prFC->fetch(PDO::FETCH_ASSOC) ) {
		//~ $html .="<tr>";
		$html .= "=\"".$fila["codcent"]."\"\t";
		$html .= "".$fila["Nombre"]."\t";
		$html .= "=\"".$fila["nro_doc"]."\"\t";
		$html .= "".$fila["tipodoc"]."\t";
		$html .= "".$fila["divisa"]."\t";
		$html .= "".number_format($fila["saldohoy"], 2)."\t";
		$html .= "".$fila["producto"]."\t";
		$html .= "".$fila["tramo_dia_hdec"]."\t";
		$html .= "=\"".$fila["contrato"]."\"\t";
		$html .= "".$fila["divisa"]."\t";
		$html .= "".$fila["tramo_dia"]."\t";
		$html .= "".$fila["diavenc"]."\t";

		$html .= ($Servicio == 6) ? campoExplode($fila["telefono_predeterminado"]) : "";
		$html .= campoExplode($fila["telefono_domicilio"]);
		$html .= campoExplode($fila["telefono_oficina"]);
		$html .= campoExplode($fila["telefono_negocio"]);
		$html .= campoExplode($fila["telefono_laboral"]);
		$html .= campoExplode($fila["telefono_familiar"]);
		$html .= ($Servicio == 6) ? campoExplode($fila["telefono_personal"]) : "";
		$html .= ($Servicio == 6) ? campoExplode($fila["telefono_tercero"]) : "";

		$html .= campoExplode($fila["direccion_predeterminado"]);
		$html .= campoExplode($fila["direccion_domicilio"]);
		$html .= ($Servicio == 6) ? campoExplode($fila["direccion_oficina"]) : "";
		$html .= ($Servicio == 6) ? campoExplode($fila["direccion_laboral"]) : "";

		$html .= "".$fila["agencia"]."\t";
		$html .= "".$fila["Gestor"]."\t";
		$html .= "".$fila["territorio"]."\t";
		$html .= "".$fila["oficina"]."\t";
		$html .= ($Servicio == 6) ? "".$fila["tpersona"]."\t" : "";
		$html .= "".$fila["dist_prov"]."\t";
		$html .= ($Servicio == 6) ? "".$fila["email"]."\t" : "";
		$html .= "".$fila["oficina2"]."\t";
		$html .= "".$fila["Fproceso"]."\t";
		$html .= "".$fila["subprod"]."\t";
		$html .= "".$fila["nom_subprod"]."\t";
		$html .= "".$fila["fincumpli"]."\t";
		$html .= ($Servicio == 6) ? "".$fila["provision"]."\t" : "";
		$html .= ($Servicio == 6) ? "".$fila["fecha_de_generacion"]."\t" : "";
		$html .= ($Servicio == 6) ? "".$fila["fecha_de_ingreso"]."\t" : "";
		$html .= ($Servicio == 6) ? "".$fila["marca"]."\t" : "";
		$html .= "".$fila["marca_grupo"]."\t";
		$html .= "".$fila["statusCLIENTE"]."\t";
		$html .= "".$fila["statusCUENTA"]."\t";
		$html .= "".$fila["idGestor"]."\t";
		$html .= "".$fila["asignado"]."\t";
		$html .= "".$fila["codigo_user"]."\t";
		$html .= "".$fila["fecha_creacion_cliente"]."\t";
	//	$html .= "".$fila["ml_estado"]."\t";
		$html .= "=\"".$fila["mejor_llamada"]."\"\t";
		$html .= "=\"".$fila["Cod_Gestion_Llamada"]."\"\t";
	//	$html .= "".$fila["mv_estado"]."\t";
		$html .= "=\"".$fila["Cod_Gestion_Visita"]."\"\t";

		$html .="\r\n";
		//~ $html .="</tr>";
	}
	//~ $html .= "</table>";

	echo $html;

	/************************************************************************************/

	//~ Funciones
	function campoExplode($campo,$arrayData=array())
	{
		$etiqueta="";
		$explodeCampo = explode("^^",$campo);
		foreach ($explodeCampo as $keys => $values)
		{
			$etiqueta .="".$values."\t";
		}
		return $etiqueta;
	}

	function campos($json, $alias, $jsonDato="no")
	{
		if ($jsonDato=="no")
		{
			$array = json_decode($json, true);
		}
		else
		{
			$array = $json;
		}
		$campo = "";
		foreach ($array as $key => $val)
		{
			$campo .= $alias.".".$val['campoT']." AS ".$val['dato'].",";
		}
		$campoFin = substr($campo, 0, -1);
		return $campoFin;
	}

	function selectFono($telfono)
	{
		$factoryConnection1 = FactoryConnection::create('mysql');
		$connection1 = $factoryConnection1->getConnection();
		$tel = json_decode($telfono, true);

		foreach ($tel as $key => $val)
		{
			if (count($tel[$key])==0)
			{
				unset($tel[$key]);
			}
		}
		$str="";
		foreach ($tel as $k => $v)
		{
			$fie="";
			$canVal = count($v);
			$rellenar = ($canVal <= 1) ? " " : str_pad("", ($canVal-1)*2, "^^");
			foreach ($v as $ke => $va)
			{
				$fie .= "IFNULL(tel.".$ke.",''),";
			}
			$ex = explode("_",$k);
			$oneFiel = explode(",",$fie);		// Primer Campo
			$resultado = $connection1->prepare("SELECT idtipo_referencia FROM ca_tipo_referencia WHERE nombre LIKE '%".$ex[1]."%' LIMIT 1");
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_ASSOC);
			$str .= "IFNULL((SELECT CONCAT_WS('^^',".substr($fie, 0, -1).")
						FROM ca_telefono tel WHERE tel.idtipo_referencia=".$fila[0]['idtipo_referencia']."
							AND tel.idcliente_cartera=cli_car.idcliente_cartera AND tel.idcuenta=cuen.idcuenta
							AND ISNULL(".str_replace('IFNULL(','',$oneFiel[0]).")!=1 LIMIT 1
					),'".$rellenar."') AS telefono_".$ex[1].",";
		}
		$strFin = substr($str, 0, -1);
		return $strFin;
	}

	function selectDir($direcciones)
	{
		$factoryConnection2 = FactoryConnection::create('mysql');
		$connection2 = $factoryConnection2->getConnection();
		$dir = json_decode($direcciones, true);

		foreach ($dir as $key => $val)
		{
			if (count($dir[$key])==0)
			{
				unset($dir[$key]);
			}
		}
		$str="";
		foreach ($dir as $k => $v)
		{
			$fie="";
			$canVal = count($v);
			$rellenar = ($canVal <= 1) ? "^^" : str_pad("", ($canVal-1)*2, "^^");
			foreach ($v as $ke => $va)
			{
				$fie .= "IFNULL(dir.".$ke.",''),";
			}
			$ex = explode("_",$k);
			$oneFiel = explode(",",$fie);		// Primer Campo
			$resultado = $connection2->prepare("SELECT idtipo_referencia FROM ca_tipo_referencia WHERE nombre LIKE '%".$ex[1]."%' LIMIT 1");
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_ASSOC);
			$str .= "IFNULL((SELECT CONCAT_WS('^^',".substr($fie, 0, -1).")
						FROM ca_direccion dir
						WHERE dir.idtipo_referencia=".$fila[0]['idtipo_referencia']."
							AND dir.idcliente_cartera=cli_car.idcliente_cartera AND dir.idcuenta=cuen.idcuenta
							AND ISNULL(".str_replace('IFNULL(','',$oneFiel[0]).")!=1 LIMIT 1
					),'".$rellenar."') AS direccion_".$ex[1].",";
		}
		$strFin = substr($str, 0, -1);
		return $strFin;
	}

	function adicionales($adicionales)
	{
		$json = json_decode($adicionales, true);

		foreach ($json as $key => $val)
		{
			if (count($json[$key])==0)
			{
				unset($json[$key]);
			}
		}
		$varCampo="";
		foreach ($json as $k => $v)
		{
			$exp = explode("ca_datos_adicionales_",$k);
			switch ($exp[1]) {
				case "cliente":
					$aliasVal = "cli_car";
				break;
				case "cuenta":
					$aliasVal = "cuen";
				break;
				case "detalle_cuenta":
					$aliasVal = "cuen_deta";
				break;
			}
			$varCampo .= campos($json[$k], $aliasVal, "si").",";
		}

		$varCampoFin = substr($varCampo, 0, -1);
		return $varCampoFin;
	}

	function eliminar_null($json)
	{
		foreach ($json as $key => $val)
		{
			if (count($json[$key])==0)
			{
				unset($json[$key]);
			}
		}
		return $json;
	}


?>
