<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=ENVIAR_CARGO.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fecha_inicio=$_REQUEST['FechaInicio'];
    $fecha_fin=$_REQUEST['FechaFin'];

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

    /*$sql_llamada= "SELECT 
                (select CONCAT_WS(' ',IFNULL(usu.paterno,''),IFNULL(usu.materno,''),IFNULL(usu.nombre,'')) from ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'gestor',
                CONCAT('=\"',cu.codigo_cliente,'\"') as 'codcent',
                CONCAT('=\"',cu.numero_cuenta,'\"') as 'contrato',
                cu.dato9 as 'territorio',
                cu.dato10 as 'oficina',
                (select nombre from ca_cliente where idcliente=clicar.idcliente) AS 'nombre',
                cu.dato3 as 'nom_subpro',
                cu.total_deuda as 'cuota',
                cu.moneda as 'divisa',
                '' as 'nro_ctas',
                '' as 'ph1',
                '' as 'cta1',
                '' as 'salmes01',
                '' as 'salso01',
                '' as 'ph2',
                '' as 'cta2',
                '' as 'salmes02',
                '' as 'salso02',
                '' as 'ph3',
                '' as 'cta3',
                '' as 'salmes03',
                '' as 'salso03',
                '' as 'ph4',
                '' as 'cta4',
                '' as 'salmes04',
                '' as 'salso04',
                '' as 'ph5',
                '' as 'cta5',
                '' as 'salmes05',
                '' as 'salso05',
                '' as 'ph6',
                '' as 'cta6',
                '' as 'salmes06',
                '' as 'salso06',
                '' as 'ph7',
                '' as 'cta7',
                '' as 'salmes07',
                '' as 'salso07',
                '' as 'ph8',
                '' as 'cta8',
                '' as 'salmes08',
                '' as 'salso08',
                '' as 'ph9',
                '' as 'cta9',
                '' as 'salmes09',
                '' as 'salso09', 
                '' as 'ph10',
                '' as 'cta10',
                '' as 'salmes10',
                '' as 'salso10',
                '' as 'diasv_hoy',
                '' as 'nrotaj',
                '' as 'cargo',
                '' as 'observaciones',
                cu.dato1 as 'fproceso',
                '' as 'gruporiesg',
                '' as 'marca2',
                '' as 'ctainactiv',
                '' as 'clientevip',
                '' as 'pagohaber',
                '' as 'alcambio',
                '' as 'total',
                '' as 'procede',
                '' as 'diferencia',
                '' as 'cc_emisor',
                '' as 'nombre_emi',
                '' as 'idagencia',
                '' as 'agencia',
                '' as 'ag_total'
                from ca_cuenta cu
                inner join ca_cliente_cartera clicar on cu.idcliente_cartera=clicar.idcliente_cartera
                where cu.enviar_cargo=1 and cu.estado=1 and cu.idcartera in ($cartera) and clicar.idcartera in ($cartera)";
    */

    $sql_llamada= "SELECT 
                (select CONCAT_WS(' ',IFNULL(usu.paterno,''),IFNULL(usu.materno,''),IFNULL(usu.nombre,'')) from ca_usuario_servicio ususer inner join ca_usuario usu on usu.idusuario=ususer.idusuario where ususer.idusuario_servicio=clicar.idusuario_servicio) AS 'gestor',
                CONCAT('=\"',cu.codigo_cliente,'\"') as 'codcent',
                CONCAT('=\"',cu.numero_cuenta,'\"') as 'contrato',
                cu.dato9 as 'territorio',
                cu.dato10 as 'oficina',
                (select nombre from ca_cliente where idcliente=clicar.idcliente) AS 'nombre',
                cu.dato3 as 'nom_subpro',
                cu.total_deuda as 'cuota',
                cu.moneda as 'divisa',
                '' as 'nro_ctas',
                '' as 'ph1',
                '' as 'cta1',
                '' as 'salmes01',
                '' as 'salso01',
                '' as 'ph2',
                '' as 'cta2',
                '' as 'salmes02',
                '' as 'salso02',
                '' as 'ph3',
                '' as 'cta3',
                '' as 'salmes03',
                '' as 'salso03',
                '' as 'ph4',
                '' as 'cta4',
                '' as 'salmes04',
                '' as 'salso04',
                '' as 'ph5',
                '' as 'cta5',
                '' as 'salmes05',
                '' as 'salso05',
                '' as 'ph6',
                '' as 'cta6',
                '' as 'salmes06',
                '' as 'salso06',
                '' as 'ph7',
                '' as 'cta7',
                '' as 'salmes07',
                '' as 'salso07',
                '' as 'ph8',
                '' as 'cta8',
                '' as 'salmes08',
                '' as 'salso08',
                '' as 'ph9',
                '' as 'cta9',
                '' as 'salmes09',
                '' as 'salso09', 
                '' as 'ph10',
                '' as 'cta10',
                '' as 'salmes10',
                '' as 'salso10',
                '' as 'diasv_hoy',
                '' as 'nrotaj',
                '' as 'cargo',
                '' as 'observaciones',
                envcar.fproceso as 'fproceso',
                '' as 'gruporiesg',
                '' as 'marca2',
                '' as 'ctainactiv',
                '' as 'clientevip',
                '' as 'pagohaber',
                '' as 'alcambio',
                '' as 'total',
                '' as 'procede',
                '' as 'diferencia',
                '' as 'cc_emisor',
                '' as 'nombre_emi',
                '' as 'idagencia',
                '' as 'agencia',
                '' as 'ag_total'
                from ca_cuenta cu
                inner join ca_cliente_cartera clicar on cu.idcliente_cartera=clicar.idcliente_cartera
                inner join ca_enviar_cargo envcar on envcar.idcuenta=cu.idcuenta and envcar.idcliente_cartera=clicar.idcliente_cartera
                where cu.idcartera in ($cartera) and clicar.idcartera in ($cartera) and envcar.estado=1 and date(envcar.fecha_creacion) BETWEEN '$fecha_inicio' AND '$fecha_fin'";




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