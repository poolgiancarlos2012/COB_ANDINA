<?php
  
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';

    date_default_timezone_set('America/Lima');
    
    $factoryConnection= FactoryConnection::create('mysql');	
    $connection = $factoryConnection->getConnection();
    
    /* PORSEACASO QUIERAN LAS VISITAS

    UNION
SELECT 
LPAD('HDEC',20,' ') AS 'CASA_DE_COBRANZAS',
LPAD(DATE_FORMAT(vis.fecha_visita,'%d/%m/%Y %k:%i:%s' ),21,' ') AS 'FECHA_GESTION',
LPAD((SELECT contrato from adicional_contrato_extrajudicial con where con.idcartera=detcu.idcartera and con.moneda=cu.moneda ),9,' ') AS 'CONTRATO',
LPAD(cu.numero_cuenta,30,' ') AS 'OBLIGACION',
LPAD(cli.tipo_documento,1,' ') AS 'TIPO_DOCUMENTO', 
LPAD(cli.numero_documento,13,' ') AS 'DOCUMENTO',
LPAD(vis.idfinal,8,' ') AS 'CODIGO_GESTION',
LPAD((SELECT direccion FROM ca_direccion WHERE iddireccion=vis.iddireccion LIMIT 1),100,' ') AS 'UBICACION_FASE',
LPAD(vis.observacion,500,' ') AS 'DETALLE', 
LPAD(IF((vis.idfinal=910)OR(vis.idfinal=911)OR(vis.idfinal=920)OR(vis.idfinal=921)OR(vis.idfinal=942)OR(vis.idfinal=943),DATE_FORMAT(vis.fecha_cp,'%d/%m/%Y' ),''),10,' ') AS 'FECHA_PAGO',
LPAD(IF((vis.idfinal=910)OR(vis.idfinal=911)OR(vis.idfinal=920)OR(vis.idfinal=921)OR(vis.idfinal=942)OR(vis.idfinal=943),vis.monto_cp,''),15,' ') AS 'VALOR_PAGO',
LPAD(IFNULL(DATE_FORMAT(vis.fecha_cp,'%d/%m/%Y' ),' '),10,' ') AS 'FECHA_PROMESA',
LPAD(IFNULL(vis.monto_cp,' '),15,' ') AS 'VALOR_PROMESA',
LPAD(' ',15,' ') AS 'MODELO_PAGO',
LPAD(' ',15,' ') AS 'RAZON_PAGO',
LPAD(IFNULL((SELECT dni FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idservicio = ".$servicio." AND ususer.idusuario_servicio=vis.idusuario_servicio  LIMIT 1 ),' '),13,' ') AS 'DOCUMENTO_ASESOR'
FROM ca_visita vis
INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = vis.idcliente_cartera
INNER JOIN ca_cuenta cu ON cu.idcuenta = vis.idcuenta
INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta
INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente

WHERE detcu.idcartera IN (".$cartera.") 

    */

    
    $servicio = $_REQUEST['Servicio'];
    $cartera = $_REQUEST['Cartera'];
    $fechaInicio = $_REQUEST['fechaInicio'];
    $fechafin = $_REQUEST['fechaFin'];
    $time = date("Ymd");

    $sql = "SELECT 
LPAD('HDEC',20,' ') AS 'CASA_DE_COBRANZAS',
LPAD(DATE_FORMAT(lla.fecha,'%d/%m/%Y %k:%i:%s' ),21,' ') AS 'FECHA_GESTION',
LPAD((SELECT contrato from adicional_contrato_extrajudicial con where con.idcartera=detcu.idcartera and con.moneda=cu.moneda ),9,' ') AS 'CONTRATO',
LPAD(cu.numero_cuenta,30,' ') AS 'OBLIGACION',
LPAD(cli.tipo_documento,1,' ') AS 'TIPO_DOCUMENTO', 
LPAD(cli.numero_documento,13,' ') AS 'DOCUMENTO',
LPAD((SELECT par.codigo_pareto from ca_pareto_idfinal_covinoc par WHERE par.idfinal = lla.idfinal),8,' ') AS 'CODIGO_GESTION',
LPAD((SELECT numero FROM ca_telefono WHERE idtelefono=lla.idtelefono LIMIT 1),100,' ') AS 'UBICACION_FASE',
LPAD(IF(TRIM(lla.observacion)='',(SELECT fina.nombre from ca_final fina WHERE fina.idfinal=lla.idfinal LIMIT 1),IFNULL(REPLACE(REPLACE(REPLACE(lla.observacion,char(9),''),char(10),''),char(13),''),(SELECT fina.nombre from ca_final fina WHERE fina.idfinal=lla.idfinal LIMIT 1))),500,' ') AS 'DETALLE', 
LPAD(IFNULL(IF((lla.idfinal=910)OR(lla.idfinal=911)OR(lla.idfinal=920)OR(lla.idfinal=921)OR(lla.idfinal=942)OR(lla.idfinal=943),DATE_FORMAT(lla.fecha_cp,'%d/%m/%Y' ),''),''),10,' ') AS 'FECHA_PAGO',
LPAD(IFNULL(IF((lla.idfinal=910)OR(lla.idfinal=911)OR(lla.idfinal=920)OR(lla.idfinal=921)OR(lla.idfinal=942)OR(lla.idfinal=943),lla.monto_cp,''),''),15,' ') AS 'VALOR_PAGO',
LPAD(IFNULL(DATE_FORMAT(lla.fecha_cp,'%d/%m/%Y' ),' '),10,' ') AS 'FECHA_PROMESA',
LPAD(IFNULL(lla.monto_cp,' '),15,' ') AS 'VALOR_PROMESA',
LPAD(' ',15,' ') AS 'MODELO_PAGO',
LPAD(' ',15,' ') AS 'RAZON_PAGO',
LPAD(IF(lla.idusuario_servicio IS NULL ,'44768189',IFNULL((SELECT dni FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio=lla.idusuario_servicio  LIMIT 1 ),' ')),13,' ') AS 'DOCUMENTO_ASESOR'
FROM ca_llamada lla
INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera = lla.idcliente_cartera
INNER JOIN ca_cuenta cu ON cu.idcliente_cartera = clicar.idcliente_cartera
INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta = cu.idcuenta
INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
WHERE detcu.idcartera IN (".$cartera.") 

";

    $prData = $connection->prepare($sql);
    $prData->execute();
    $arrayDATA = $prData->fetchAll(PDO::FETCH_ASSOC);

    $ruta_archivo = 'documents/reporte_txt/gestiones_'.$time.'.txt';
    $file_download= fopen('../../'.$ruta_archivo, 'w');
    for($i=0;$i<count($arrayDATA);$i++){
        
        $data = $arrayDATA[$i]['CASA_DE_COBRANZAS'].$arrayDATA[$i]['FECHA_GESTION'].$arrayDATA[$i]['CONTRATO'].$arrayDATA[$i]['OBLIGACION'].$arrayDATA[$i]['TIPO_DOCUMENTO'].
        $arrayDATA[$i]['DOCUMENTO'].$arrayDATA[$i]['CODIGO_GESTION'].$arrayDATA[$i]['UBICACION_FASE'].
        $arrayDATA[$i]['DETALLE'].$arrayDATA[$i]['FECHA_PAGO'].$arrayDATA[$i]['VALOR_PAGO'].$arrayDATA[$i]['FECHA_PROMESA'].$arrayDATA[$i]['VALOR_PROMESA'].$arrayDATA[$i]['MODELO_PAGO'].$arrayDATA[$i]['RAZON_PAGO'].$arrayDATA[$i]['DOCUMENTO_ASESOR'];
        
        fwrite($file_download,$data."\r\n");
    }
    fclose($file_download);

    echo json_encode(array('rst'=>true,'rutafile'=>$ruta_archivo,'namefile'=>'gestiones_'.$time));
?>
