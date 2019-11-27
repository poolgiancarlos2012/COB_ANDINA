<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=ESTADO_CUENTA.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    $sql_llamada= "SELECT cu.dato1 AS 'PROCESO',
                            clicar.dato1 AS 'AGENCIA',
                            cu.dato9 AS 'TERRITORIO',
                            cu.producto AS 'PRODUCTO',
                            cu.dato2 AS 'SUB-PRODUCTO',
                            CONCAT('=\"',cu.numero_cuenta,'\"') AS 'CONTRATO',
                            cu.codigo_cliente AS 'CODCENT',
                            (SELECT cli.nombre FROM ca_cliente cli WHERE cli.idcliente=clicar.idcliente) AS 'NOMBRE',
                            cu.moneda AS 'DIVISA',
                            cu.total_deuda AS 'SALDO-HOY',
                            detcu.dias_mora AS 'DIAS-VENC',
                            (SELECT departamento FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'UBIGEO',
                            (SELECT distrito FROM ca_direccion where idcliente_cartera=clicar.idcliente_cartera and idtipo_referencia=2 limit 1) AS 'DIST_PROV',
                            cu.dato8 AS 'MARCA',
                            DATE(cu.ul_fecha) AS 'ULTIMA',
                            CASE 
                            WHEN CAST(detcu.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 30 AND CAST(detcu.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                            WHEN CAST(detcu.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                            ELSE 'NO_TRAMO'
                            END AS 'TRAMO',
                            IF(clicar.recibio_eexx=1,'SI',IF(clicar.recibio_eexx=2,'NO','')) AS 'MARCA_EECC' 
                FROM ca_cliente_cartera clicar
                INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
                INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
                where clicar.idcartera IN ($cartera) and cu.idcartera IN ($cartera) and clicar.estado=1 and cu.estado=1";


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


?>