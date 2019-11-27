<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=PROVISION.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fechaunica=$_REQUEST['FechaUnica'];
    $tipocambio=$_REQUEST['tipocambio'];
    $tipovac=$_REQUEST['tipovac'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT car.nombre_cartera,cu.dato1 as fproceso,clicar.dato1 as agencia,cu.dato9 as 'territorio',
                    cu.producto,cu.dato2 as 'sub-producto',CONCAT('=\"',cu.numero_cuenta,'\"') as 'contrato',
                    CONCAT('=\"',clicar.codigo_cliente,'\"') as 'codcent',(SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente) AS 'nombre',
                    cu.moneda as 'divisa',cu.total_deuda as 'saldo-hoy',his.provision,his.clasificacion,
                    detcu.dias_mora as 'dias-venc',cu.dato10 as 'nro oficina',cu.dato11 as 'nombre oficina',
                    (SELECT provincia from ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) as 'dist-prov',
                    cu.tramo_cuenta as 'tramo-dia',cu.dato8 as 'marca',
                    CASE 
                                        WHEN CAST(detcu.dias_mora AS SIGNED) <= 30 THEN 'T1'
                                        WHEN CAST(detcu.dias_mora AS SIGNED) > 30 AND CAST(detcu.dias_mora AS SIGNED) <= 60 THEN 'T2'
                                        WHEN CAST(detcu.dias_mora AS SIGNED) > 60 THEN 'T3'
                                        ELSE 'NT'
                                    END AS tramo,
                    IF(cu.moneda='PEN', cu.total_deuda, IF(cu.moneda='USD', cu.total_deuda*$tipocambio, cu.total_deuda *$tipovac) ) AS monto,
                    DATE(his.fecha_creacion) as 'fecha_carga',TIME(his.fecha_creacion) as 'hora_carga'
                    from ca_historico_provision his
                    inner join ca_cliente_cartera clicar on his.idcliente_cartera=clicar.idcliente_cartera
                    inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
                    inner join ca_detalle_cuenta detcu on detcu.idcuenta=cu.idcuenta
                    inner join ca_cartera car on car.idcartera=clicar.idcartera
                    where clicar.idcartera in ($cartera) and cu.idcartera in ($cartera) and date(his.fecha_creacion)='$fechaunica' and cu.estado=1 ";


    $pr_llamada=$connection->prepare($sql_llamada);
    $i=0;
    if($pr_llamada->execute()){
        while($data_llamada=$pr_llamada->fetch(PDO::FETCH_ASSOC)){
            if($i==0){
                foreach ($data_llamada as $key => $value) {
                        echo utf8_decode($key)."\t";
                }
                    echo "\n";
            }
            $i++;
            $cont=0;
            foreach ($data_llamada as $key => $value) {
                    echo utf8_decode($value)."\t";
            }
                    echo "\n";
        }        
    }    



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>