<?php
$carteras=$_REQUEST['cartera'];
header('Content-Type: text/html; charset=UTF-8');
header("Content-type:application/vnd.ms-excel;charset=latin");
header("Content-Disposition:atachment;filename=gestion_diaria.xls");
header("Pragma:no-cache");
header("Expires:0");

	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	date_default_timezone_set('America/Lima');
	
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

$sql="select cli.codigo as 'CODIGO_CLIENTE',
cu.inscripcion as 'CODIGO_CUENTA',
cu.numero_cuenta AS 'INSCRIPCION',
concat_ws(' ',cli.paterno,cli.materno,cli.nombre) AS 'NOMBRE ABONADO',
cli.numero_documento AS 'DNI',
( select concat(direccion, ', ', numero, ', ', calle ) from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'DIRECCION',
( select distrito from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'DISTRITO',
( select zona from ca_direccion where idcliente = cli.idcliente order by iddireccion desc limit 1 ) AS 'ZONAL',
cu.telefono AS 'TELEFONO',detcu.fecha_alta AS 'FECHA ALTA',detcu.fecha_baja AS 'FECHA BAJA',
car.nombre_cartera AS 'NOMBRE GESTION', car.fecha_inicio AS 'FECHA INICIO GESTION', car.fecha_fin AS 'FECHA FIN GESTION'
, IF( car.idcampania = 1, 'T1', 'T2' ) AS 'EVENTO',
detcu.total_deuda AS 'EXIGIBLE', detcu.total_deuda_soles AS 'TOTAL', detcu.monto_mora AS 'AJUSTADO', 
IF( detcu.total_deuda < detcu.monto_pagado , detcu.total_deuda, detcu.monto_pagado ) AS 'PAGO', ( detcu.total_deuda - IF( detcu.total_deuda < detcu.monto_pagado , detcu.total_deuda, detcu.monto_pagado ) ) AS 'SALDO',
if(  detcu.total_deuda <= detcu.monto_pagado  ,'C', if(  detcu.total_deuda > detcu.monto_pagado AND detcu.monto_pagado>0  , 'A', 'SP'  ) ) AS 'ESTADO PAGO',
(SELECT  CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio LIMIT 1 ) AS 'USUARIO' ,
IF( clicar.is_noc_predictivo = 1 , 'NOC',( SELECT nombre from ca_carga_final where idcarga_final = cu.ul_carga  )) AS 'CONTACTABILIDAD',
IF( clicar.is_noc_predictivo = 1 , 'NO CONTESTAN/BAJO PUERTA', ( SELECT niv.nombre from ca_final fin inner join ca_nivel niv on niv.idnivel = fin.idnivel  where fin.idfinal = cu.ul_estado  )) AS 'RESPUESTA GESTION',
IF( clicar.is_noc_predictivo = 1, 'SIN INCIDENCIA', ( SELECT nombre from ca_final where idfinal = cu.ul_estado  )) AS 'RESPUESTA INCIDENCIA',
GROUP_CONCAT(detcu.codigo_operacion SEPARATOR '|' ) AS 'NRO_FACTURAS',
IF( clicar.is_noc_predictivo = 1, 'NO CONTESTA PREDICTIVO',replace(cu.ul_observacion,'\n',' ')) AS 'OBSERVACION',
if(cu.ml_carga is null,(IF( clicar.is_noc_predictivo = 1 , 'NOC','')),( SELECT nombre from ca_carga_final where idcarga_final = cu.ml_carga  )) AS 'ML CONTACTABILIDAD',
IF(cu.ml_estado is null,(IF(clicar.is_noc_predictivo = 1 , 'NO CONTESTAN/BAJO PUERTA','')),(SELECT niv.nombre from ca_final fin inner join ca_nivel niv on niv.idnivel = fin.idnivel  where fin.idfinal = cu.ml_estado)) AS 'ML RESPUESTA GESTION',
IF(cu.ml_estado is null,(IF(clicar.is_noc_predictivo = 1 , 'SIN INCIDENCIA','')),(SELECT nombre from ca_final where idfinal = cu.ml_estado )) AS 'ML RESPUESTA INCIDENCIA',
IF(cu.ml_observacion is null,(IF( clicar.is_noc_predictivo = 1, 'NO CONTESTA PREDICTIVO','')),(replace(cu.ml_observacion,'\n',' '))) AS 'ML_OBSERVACION'
from ca_cliente cli inner join ca_cartera car inner join ca_cliente_cartera clicar inner join ca_cuenta cu inner join ca_detalle_cuenta detcu
on detcu.idcuenta = cu.idcuenta and cu.idcliente_cartera = clicar.idcliente_cartera and clicar.idcartera = car.idcartera and clicar.idcliente = cli.idcliente
where clicar.idcartera in (".$carteras.") and cu.idcartera in (".$carteras.") and detcu.idcartera in (".$carteras.") and cu.retirado=0 
GROUP BY cu.numero_cuenta
";

	$prData = $connection->prepare($sql);
	$prData->execute();
	$data = $prData->fetchAll(PDO::FETCH_ASSOC);


if($data!=''){
	
	for($i=0;$i<count($data);$i++){
		if( $i==0 ) {
			foreach( $data[$i] as $index => $value ) {
				echo($index."\t");
			}
			echo("\r\n");
		}
		foreach( $data[$i] as $index => $value ) {
			echo($value."\t");
		}
		echo("\r\n");
	}
}
?>
