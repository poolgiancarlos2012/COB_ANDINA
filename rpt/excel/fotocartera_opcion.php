<?php

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=fotocartera_opcion.xls");
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

	$factoryConnection= FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();

	$sql="	SELECT
			IFNULL((SELECT IF (CONCAT(YEAR(NOW()), MONTH(NOW())) = CONCAT(YEAR(fecha), MONTH(fecha)),'CON GESTION','SIN GESTION') FROM ca_llamada WHERE idllamada = clicar.id_ultima_llamada),'SIN GESTION') AS 'ESTADO_GESTION_DEL_MES',
			CONCAT('=\"',cli.codigo,'\"') AS 'CODIGO_CLIENTE',
			IF (CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) <> '',CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)),cli.razon_social) AS 'TITULAR',
			cli.tipo_persona AS 'TIPO_PERSONA',
			cu.subnegocio AS 'SEMANA',
			cu.moneda AS 'MONEDA',
			cu.producto AS 'VALOR_CERTIFICADO',
			cu.inscripcion AS 'TIPO_ADJUDICACION',
			cu.negocio AS 'CONTRATO',
			cu.tramo_cuenta AS 'TIPO_CUOTA',
			cu.telefono AS 'FECHA_VENCIMIENTO',
			cu.cuota_mensual AS 'CUOTA_MENSUAL',
			cu.seguros AS 'SEGUROS',
			cu.otros AS 'OTROS',
			(SELECT carfin.nombre FROM ca_llamada lla INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal INNER JOIN ca_carga_final carfin ON carfin.idcarga_final=fin.idcarga_final WHERE lla.idllamada = clicar.id_ultima_llamada) AS 'ULT_TIPO_LLAMADA_CLIENTE',
			(SELECT fin.nombre FROM ca_llamada lla INNER JOIN ca_final fin ON fin.idfinal = lla.idfinal WHERE lla.idllamada = clicar.id_ultima_llamada) AS 'ULT_ESTADO_LLAMADA_CLIENTE',
			(SELECT fecha FROM ca_llamada WHERE idllamada = clicar.id_ultima_llamada) AS 'ULT_FECHA_LLAMADA_CLIENTE',
			(SELECT nombre FROM ca_final WHERE idfinal=cu.ul_estado) AS 'ULT_ESTADO_LLAMADA_CONTRATO',
			cu.ul_fecha AS 'ULT_FECHA_LLAMADA_CONTRATO',
			(SELECT nombre FROM ca_final WHERE idfinal=cu.ml_estado) AS 'ML_ESTADO_LLAMADA_CONTRATO',	
			ml_fecha AS 'ML_FECHA_LLAMADA_CONTRATO',
			cu.gestor_cobranza AS 'GESTOR_COBRANZA',
			cu.anexo AS 'ANEXO',
			(SELECT departamento FROM ca_direccion WHERE codigo_cliente=cli.codigo LIMIT 1) AS 'DEPARTAMENTO',				
			(SELECT provincia FROM ca_direccion WHERE codigo_cliente=cli.codigo LIMIT 1) AS 'PROVINCIA',				
			(SELECT distrito FROM ca_direccion WHERE codigo_cliente=cli.codigo LIMIT 1) AS 'DISTRITO',				
			(SELECT direccion FROM ca_direccion WHERE codigo_cliente=cli.codigo LIMIT 1) AS 'DIRECCION',	
			(SELECT pri.nombre AS idGestor FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_privilegio pri ON us.idprivilegio=pri.idprivilegio WHERE cc.idcliente_cartera=clicar.idcliente_cartera) AS 'IDGESTOR',				
			(SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS asignado FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=clicar.idcliente_cartera) AS 'ASIGNADO',				
			(SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=clicar.idusuario_servicio) AS 'CODIGO_USUARIO',
			DATE(clicar.fecha_creacion) AS 'FECHA_CREACION_CLIENTE'
			FROM
			ca_cuenta cu
			INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = cu.idcliente_cartera
			INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
			INNER JOIN ca_cartera car ON car.idcartera = clicar.idcartera
			WHERE
			cu.estado = 1 AND 
			cu.idcartera = $idCartera AND
			(cu.cuota_mensual+cu.seguros+cu.otros)<>0
			ORDER BY cli.codigo
	";

	// echo $sql;
	// exit();

	$prData = $connection->prepare($sql);
    $prData->execute();
    $i = 0;

	echo '<table border=1>';
	while( $row = $prData->fetch(PDO::FETCH_ASSOC) ) {
	if( $i == 0 ) {
	  echo '<tr>';
	  foreach( $row as $index => $value ) {
	   
	   //echo $index."\t";
		if($index=='DIRECCION' OR $index=='TITULAR'){			  	
			echo '<td width=600 style="background-color:#000080;color:white;border-color:white;" align="center" >'.$index.'</td>';
		}else{
			echo '<td style="background-color:#000080;color:white;border-color:white;" align="center" >'.$index.'</td>';
		}
	  	//echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
	  }
	  //echo "\n";
	 echo '</tr>';
	}

	$style="";
	( $i%2 == 0 )?$style="background-color:#A8D9D8;border-color:white;":$style="background-color:#D5ECCA;border-color:white;";
	echo '<tr>';
	foreach( $row as $key => $value ){				
	  
	  /*if( $key == 'NUMERO_CUENTA' || $key == 'CODIGO_CLIENTE' || $key == 'PAN' ) {
	    // echo '="'.$value.'"'."\t";
	  }else if( $key == 'OBSERVACION' || $key=='CLIENTE' ){
	    // echo str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value)))))."\t";
	  }else{
	    // echo utf8_decode($value)."\t";
	    echo '<td style="'.$style.'" align="center">'.str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value))))).'</td>';
	  }*/

		if($key == 'TITULAR' OR $key == 'ULT_ESTADO_LLAMADA_CLIENTE' OR $key == 'ULT_ESTADO_LLAMADA_CONTRATO' OR $key == 'ML_ESTADO_LLAMADA_CONTRATO' OR $key == 'GESTOR_COBRANZA' OR $key == 'DIRECCION' OR $key == 'IDGESTOR'){
			echo '<td style="'.$style.'" align="left">'.str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value))))).'</td>';
		}else{
			echo '<td style="'.$style.'" align="center">'.str_replace("\r"," ",str_replace("\n"," ",str_replace("\n"," ",str_replace("\t"," ",utf8_decode($value))))).'</td>';
		}

	}
	//echo "\n";
	echo '</tr>';

	$i++;
	}
	echo '</table>';
?>
