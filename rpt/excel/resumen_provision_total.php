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

    $carteras = $_REQUEST['Cartera'];
    $fechaUnica = $_REQUEST['FechaUnica'];
    $tipoCambio = $_REQUEST['TipoCambio'];
    $VAC = $_REQUEST['VAC'];
    $time = date("Y_m_d_H_i_s_u");
/*	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
    $fechaunica=$_REQUEST['FechaUnica'];
    $tipocambio=$_REQUEST['tipocambio'];
    $tipovac=$_REQUEST['tipovac'];
*/
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	 /*$sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERAVISITA_PROVISION_TOTAL ";
    $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
    $prEliminarTMP->execute();

    $sqlCreateTemporaryFotoCarteraVisita= "CREATE TEMPORARY TABLE TMP_FOTOCARTERAVISITA_PROVISION_TOTAL
                    ( 
                        idcliente_cartera int(11),
                        codcent varchar(15),
                        idcliente_cartera_visita varchar(40),
                        idfinal varchar(40),
                        peso varchar(40),
                       CODIGO_CLIENTE varchar(40),
                        FECHA_CPG varchar(40),
                        CARGA varchar(40),
                        ESTADO_VISITA varchar(40) ,
                        OBSERVACION varchar(40),
                        IDCLIENTE_CARTERA_UL_MOTNOPAGO varchar(40),
                        FECHA_VISITA_UL_MOTNOPAGO varchar(40),
                        CODIGO_CLIENTE_UL_MOTNOPAGO varchar(40),
                        UL_MOTIVO_NO_PAGO varchar(40),
                        TIPO_CONTACTO varchar(40),
                        IDCLIENTE_CARTERA_UL_TIPO_CONTACTO varchar(40),
                        CODIGO_CLIENTE_UL_TIPOCONTACTO varchar(40),
                        FECHA_VISITA_UL_TIPOCONTACTO varchar(40), 
                        INDEX( idcliente_cartera ),
                        INDEX( codcent )
                        
                      )
                    ";
                   
    $prCreateTemporaryFotoCarteraVisita=$connection->prepare( $sqlCreateTemporaryFotoCarteraVisita );*/
   // if($prCreateTemporaryFotoCarteraVisita->execute()){
        /* OBTENER LOS DISTINTOS CODIGO_CLIENTE DE LAS VISITAS */
        /*UN COUNT ANTES EN CASO NO HAIGAN VISITAS*/
        $sqlCountCodClientesByVisitas = "SELECT COUNT(*) AS 'COUNT' FROM ( SELECT DISTINCT a.CODIGO_CLIENTE AS 'CODIGO_CLIENTE' from(
                                        select * from (
                                                                    
                                                SELECT  vis.idcliente_cartera AS 'idcliente_cartera_visita',                    
                                                clicar.codigo_cliente AS 'CODIGO_CLIENTE'
                                              FROM ca_cartera car 
                                                INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                                                INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                                                INNER JOIN ca_campania cam ON cam.idcampania =car.idcampania
                                                WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1  and cam.idservicio=6
                                                AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) limit 1
                                            
                                        )AS a GROUP BY a.idcliente_cartera_visita
                                    )AS a )AS A;";
        $prCountCodClientesByVisitas = $connection->prepare($sqlCountCodClientesByVisitas);
        $prCountCodClientesByVisitas->execute();
        $arrayCountClientesbyVisitas = $prCountCodClientesByVisitas->fetchAll(PDO::FETCH_ASSOC);
        $countClientesbyVisitas = $arrayCountClientesbyVisitas[0]['COUNT'];

        /*UN COUNT EN CASOS NO HAIGNA LLAMADAS*/
        $sqlCountCodClientesByLlamadas = "SELECT COUNT(*) AS 'COUNT' FROM (
                    SELECT
                    
                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                    lla.idcliente_cartera AS 'idcliente_cartera_llamada'
                    FROM ca_cartera car 
                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                    INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                    INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                    INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                    WHERE clicar.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." )  -- 2600
                    AND cli.idservicio = 6
                    AND car.idcartera IN ( ".$carteras." )    
                    limit 1   
                   )AS d";
        $prCountCodClientesByLlamadas = $connection->prepare($sqlCountCodClientesByLlamadas);
        $prCountCodClientesByLlamadas->execute();
        $arrayCountClientesbyLlamadas=$prCountCodClientesByLlamadas->fetchAll(PDO::FETCH_ASSOC);
        $countClientesbyLlamadas = $arrayCountClientesbyLlamadas[0]['COUNT'];

        if($countClientesbyVisitas!=0 && $countClientesbyLlamadas!=0)/*1ER*/{
                $sqlGetDtistinctCodClienteByVisitas = "SELECT DISTINCT a.CODIGO_CLIENTE AS 'CODIGO_CLIENTE',idcliente from(
                    select * from (                       
                            SELECT 
                            cli.idcliente,                         
                            vis.idcliente_cartera AS 'idcliente_cartera_visita',
                            clicar.codigo_cliente AS 'CODIGO_CLIENTE'
                          FROM ca_cartera car 
                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                            INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                            INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                            
                            WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                            AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                        
                    )AS a GROUP BY a.idcliente_cartera_visita 
                )AS a";
        
                $prGetDtistinctCodClienteByVisitas= $connection->prepare($sqlGetDtistinctCodClienteByVisitas);
                $prGetDtistinctCodClienteByVisitas->execute();
                $CodigoClientesByVisitas = $prGetDtistinctCodClienteByVisitas->fetchAll(PDO::FETCH_ASSOC);
                $arrayCodigoClienteVisita= array();
                for($i=0;$i<count($CodigoClientesByVisitas);$i++){
                    array_push($arrayCodigoClienteVisita,$CodigoClientesByVisitas[$i]['idcliente']); 
                }
                $CodigoClientesByVisitasFinal = implode($arrayCodigoClienteVisita, ',');

                
                //CREANDO TEMPORARYS CON LA DATA.
                $sqlCreateTemporaryFotoVisita="CREATE TEMPORARY TABLE TMP_FOTOVISITA_".$time."  SELECT
                            cli_car.idcliente_cartera AS 'idcliente_cartera',cli.codigo AS codcent  
                            FROM ca_cliente cli
                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                            WHERE cuen.idcartera IN ( ".$carteras." ) ORDER BY cli.codigo DESC ;";
                $sqlAddIndexTemporaryFotoVisita="ALTER TABLE TMP_FOTOVISITA_".$time." ADD INDEX(idcliente_cartera);";
                $sqlAddIndexTemporaryFotoVisita_2="ALTER TABLE TMP_FOTOVISITA_".$time." ADD INDEX(codcent);";


                $sqlCreateTemporaryFotoVisita2="CREATE TEMPORARY TABLE TMP_FOTO_VISITA2_".$time."  select * from (
                                select * from (
                                    SELECT 
                                    vis.idcliente_cartera AS 'idcliente_cartera_visita',
                                    (SELECT fin.idfinal from ca_final fin where fin.idfinal=vis.idfinal limit 1)AS 'idfinal',
                                    (SELECT finser.peso from ca_final_servicio finser inner join ca_final fin on fin.idfinal=finser.idfinal where fin.idfinal= vis.idfinal and finser.estado=1 and finser.idservicio=6 limit 1) AS 'peso',
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                    vis.fecha_cp AS 'FECHA_CPG',
                                    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal limit 1 ) as 'CARGA',
                                    ( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal limit 1 ) AS 'ESTADO_VISITA',
                                 replace(replace(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION'
                                    FROM ca_cartera car 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                    INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                                    INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                                    WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                                    AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                                ) AS f ORDER BY f.idfinal,f.peso,f.codigo_cliente desc 
                            )AS a GROUP BY a.idcliente_cartera_visita ;";
                $sqlAddIndexTemporaryFotoVisita2="ALTER TABLE TMP_FOTO_VISITA2_".$time." ADD INDEX(idcliente_cartera_visita);";

                $sqlCreateTemporaryFotoVisita3="CREATE TEMPORARY TABLE TMP_FOTO_VISITA3_".$time."  select * from (
                                select vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                vis.fecha_visita AS 'FECHA_VISITA_UL_MOTNOPAGO',
                                clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = vis.idmotivo_no_pago) AS 'UL_MOTIVO_NO_PAGO' 
                                from    ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                inner join ca_cartera car on car.idcartera = clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania
                                where vis.idmotivo_no_pago is not null and cam.idservicio = 6 and car.estado=1 and cli.idservicio=6 and  ( MONTH(vis.fecha_visita) > (MONTH(now())-2) ) and cli.idcliente 
                                    in (".$CodigoClientesByVisitasFinal.") and ( DATE(vis.fecha_visita) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ) ORDER BY vis.fecha_visita,clicar.codigo_cliente desc 
                                                                ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO ;";
                $sqlAddIndexTemporaryFotoVisita3 ="ALTER TABLE TMP_FOTO_VISITA3_".$time." ADD INDEX(IDCLIENTE_CARTERA_UL_MOTNOPAGO);";

                $sqlCreateTemporaryFotoVisita4 = "CREATE TEMPORARY TABLE TMP_FOTO_VISITA4_".$time." select * from (
                                select 
                               ( select  par.nombre  from ca_parentesco par where par.idparentesco = vis.idparentesco )AS 'TIPO_CONTACTO', vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_TIPO_CONTACTO', clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCONTACTO', vis.fecha_visita AS 'FECHA_VISITA_UL_TIPOCONTACTO' 
                                from ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera  clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cartera car on car.idcartera= clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania  -- 643
                                where car.estado = 1 and cam.idservicio = 6 and car.idcartera IN ( ".$carteras." ) and vis.idparentesco is not null order by vis.fecha_visita,clicar.idcliente_cartera DESC
                            ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO ;";
                $sqlAddIndexTemporaryFotoVisita4 = "ALTER TABLE TMP_FOTO_VISITA4_".$time." ADD INDEX(IDCLIENTE_CARTERA_UL_TIPO_CONTACTO);";

                //EJECUTANDO TEMPORARYS

                $prCreateTemporaryFotoVisita = $connection->prepare($sqlCreateTemporaryFotoVisita);
                if($prCreateTemporaryFotoVisita->execute()){
                  $prAddIndexTemporaryFotoVisita= $connection->prepare($sqlAddIndexTemporaryFotoVisita);
                  $prAddIndexTemporaryFotoVisita->execute(); 
                  $prAddIndexTemporaryFotoVisita_2= $connection->prepare($sqlAddIndexTemporaryFotoVisita_2);
                  $prAddIndexTemporaryFotoVisita_2->execute();

                  $prCreateTemporaryFotoVisita2 = $connection->prepare($sqlCreateTemporaryFotoVisita2);
                  if($prCreateTemporaryFotoVisita2->execute()){
                    $prAddIndexTemporaryFotoVisita2=$connection->prepare($sqlAddIndexTemporaryFotoVisita2);
                    $prAddIndexTemporaryFotoVisita2->execute();

                    $prCreateTemporaryFotoVisita3 = $connection->prepare($sqlCreateTemporaryFotoVisita3);
                    if($prCreateTemporaryFotoVisita3->execute()){
                      $prAddIndexTemporaryFotoVisita3 = $connection->prepare($sqlAddIndexTemporaryFotoVisita3);
                      $prAddIndexTemporaryFotoVisita3->execute();

                      $prCreateTemporaryFotoVisita4 = $connection->prepare($sqlCreateTemporaryFotoVisita4);
                      if($prCreateTemporaryFotoVisita4->execute()){
                        $prAddIndexTemporaryFotoVisita4 = $connection->prepare($sqlAddIndexTemporaryFotoVisita4);
                        $prAddIndexTemporaryFotoVisita4->execute();
                      }
                    }
                  }
                }
                
                $sqlLlenarTemporaryVisita = "CREATE TEMPORARY TABLE TMP_FOTOVI_PT_".$time." 
                                            SELECT * FROM TMP_FOTOVISITA_".$time."  FOTOCARTERA   
                    INNER JOIN( 
                        select * from
                            TMP_FOTO_VISITA2_".$time." a
                        LEFT JOIN 
                            TMP_FOTO_VISITA3_".$time."    
                         b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO=a.idcliente_cartera_visita
                        LEFT JOIN 
                            TMP_FOTO_VISITA4_".$time."
                        c ON c.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO =a.idcliente_cartera_visita
                    ) AS VISITAS ON VISITAS.idcliente_cartera_visita=FOTOCARTERA.idcliente_cartera
                ";

                $prLlenarTemporaryVisita = $connection->prepare($sqlLlenarTemporaryVisita);   
                if($prLlenarTemporaryVisita->execute()){
                    $sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL = "ALTER TABLE TMP_FOTOVI_PT_".$time." ADD INDEX(CODIGO_CLIENTE);";
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL);
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL->execute();

                    $sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL2 = "ALTER TABLE TMP_FOTOVI_PT_".$time." ADD INDEX(CARGA);";
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL2=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL2);
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL2->execute();

                    $sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL3 = "ALTER TABLE TMP_FOTOVI_PT_".$time." ADD INDEX(peso);";
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL3=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL3);
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL3->execute();

                    $sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL4 = "ALTER TABLE TMP_FOTOVI_PT_".$time." ADD INDEX(FECHA_VISITA_UL_TIPOCONTACTO);";
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL4=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL4);
                    $prAddIndexTemporaryFOTOCARTERAVISITA_PROVISION_TOTAL4->execute();
                    
                    $sqlDistinctClienteByLlamada = "select  distinct CODIGO_CLIENTE AS CODIGO_CLIENTE,idcliente  FROM (  -- DISTINTOS CODIGO_CLIENTES DE LLAMADAS
                                SELECT * FROM (
                                    SELECT * FROM (
                                                    SELECT
                                                    cli.idcliente,
                                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                    lla.idcliente_cartera AS 'idcliente_cartera_llamada'
                                                    FROM ca_cartera car 
                                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                    INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                    INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                    INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                    WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  -- 2600
                                                    AND cli.idservicio = 6
                                                    AND car.idcartera IN (".$carteras.")    
                                                    GROUP BY lla.idcliente_cartera,lla.fecha 
                                    ) AS b  ORDER BY  b.CODIGO_CLIENTE ASC
                                ) AS c GROUP BY  c.idcliente_cartera_llamada
                            ) AS d " ;
            
                    $prDistinctClienteByLlamada = $connection->prepare($sqlDistinctClienteByLlamada);
                    $prDistinctClienteByLlamada->execute();
                    $distinctClienteByLlamada = $prDistinctClienteByLlamada->fetchAll(PDO::FETCH_ASSOC);

                    $arrayCodigoClienteLlamada = array();
                    for($i=0;$i<count($distinctClienteByLlamada);$i++){
                        array_push($arrayCodigoClienteLlamada,$distinctClienteByLlamada[$i]['idcliente']);
                    }
                    $CodigoClientesByLlamadasFinal = implode($arrayCodigoClienteLlamada, ',');

   // if( $prCreateTemporaryFotocarteraLlamadas->execute() ){
                        $sqlCreateTemporaryFotoLlamada = "CREATE TEMPORARY TABLE TMP_FOTO11_".$time." SELECT
                                                                        cli_car.idcliente_cartera AS 'idcliente_cartera_fotocartera',cli.codigo AS codcent,cli.nombre AS Nombre,
                                                                        cuen.numero_cuenta AS contrato,if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*2.8,cuen.total_deuda*7) ) AS saldohoy,cuen.producto AS producto,
                                                                        CASE 
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                                                                            ELSE 'NO_TRAMO'
                                                                        END AS tramo_dia,
                                                                        cuen_deta.dias_mora AS diavenc,
                                                                        cli_car.dato1 AS agencia,cli_car.dato4 AS dist_prov,cuen.dato3 AS nom_subprod,cuen.dato8 AS marca,cuen.dato9 AS territorio,cuen.dato11 AS oficina2,
                                                                        cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
                                                                        (SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS 'asignadoFOTOCARTERA' FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera limit 1) AS asignadoFOTOCARTERA,
                                                                        IFNULL((SELECT CONCAT_WS('  ',IFNULL(dir.direccion,''),IFNULL(dir.departamento,''),IFNULL(dir.provincia,''),IFNULL(dir.distrito,''))
                                                                            FROM ca_direccion dir
                                                                            WHERE dir.idtipo_referencia=2
                                                                                AND dir.idcliente_cartera=cli_car.idcliente_cartera AND dir.idcuenta=cuen.idcuenta
                                                                                AND ISNULL(dir.direccion)!=1 LIMIT 1
                                                                        ),'^^^^^^') AS direccion_domicilio,
                                                                        (SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio limit 1) AS codigo_user
                                                                        
                                                                        FROM ca_cliente cli
                                                                        INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                        INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                        INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                        WHERE cuen.idcartera IN ( ".$carteras." ) ORDER BY cli.codigo desc
                                                                ;";
                        $sqlAddIndexTemporaryFotoLlamada = "ALTER TABLE TMP_FOTO11_".$time." ADD INDEX(codcent);";

                        $sqlCreateTemporaryFotoLlamada2="CREATE TEMPORARY TABLE TMP_FOTO22_".$time."  SELECT c.codigo AS 'codigo',sum(deuda)AS 'total_deuda' FROM (
                                                                            
                                                                            SELECT  if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*2.8 ,cuen.total_deuda*7) ) AS 'deuda' ,
                                                                            cli.codigo AS 'codigo'
                                                                            FROM ca_cliente cli
                                                                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                            WHERE cuen.idcartera IN ( ".$carteras." ) and cuen.retirado!=1 ORDER BY cli.codigo DESC

                                                                    ) as c GROUP BY c.codigo;";
                        $sqlAddIndexTemporaryFotoLlamada2= "ALTER TABLE TMP_FOTO22_".$time." ADD INDEX(codigo);";

                        $sqlCreateTemporaryFotoLlamada3="CREATE TEMPORARY TABLE TMP_FOTO33_".$time."   SELECT * FROM (
                                                                           
                                                                                        SELECT
                                                                                               
                                                                                        CASE  WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK(  NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 3 DAY ) )   THEN '1'
                                                                                                WHEN ( DAYOFWEEK(  NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 4 DAY ) )  THEN '1'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 6 DAY ) ) THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 8 DAY ) )  THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 9 DAY ) )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3    and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3   or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 16 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 15 DAY ) ) THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 20 DAY ) )  THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 18 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 21 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 28 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 32 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 27 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 36 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 30 DAY ) )  THEN '10'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 40 DAY ) ) THEN '10'
                                                                                            ELSE 'A'
                                                                                        END AS 'CONDICION',
                                                                                        CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                                                                                            ELSE 'B'
                                                                                        END AS 'FRECUENCIA',    
                                                                                            lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal AND finser.estado=1 and finser.idservicio=6 limit 1) AS 'peso',
                                                                                           
                                                                                            clicar.idcliente_cartera AS 'DATA',
                                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                                                          
                                                                                            lla.idcliente_cartera AS 'idcliente_cartera_llamada',
                                                                                         CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal limit 1)   END    AS 'TIPO_CONTACTO',
                                                                                            replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                                                                                            DATE(lla.fecha_cp) AS 'FECHA_CP',
                                                                                            TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                                                                                        ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto limit 1) AS 'CONTACTO',
                                                                                          ( SELECT fin.nombre from ca_final fin where fin.idfinal=lla.idfinal limit 1) AS 'ESTADO_LLAMADA',
                                                                                            (select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=6 limit 1) as 'prioridad'
                                                                                            FROM ca_cartera car 
                                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                                            
                                                                                            WHERE clicar.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras."  )  -- 2600
                                                                                            AND cli.idservicio = 6
                                                                                
                                                                                            AND car.idcartera IN ( ".$carteras." )   ORDER BY  clicar.CODIGO_CLIENTE,CONDICION,lla.fecha,prioridad ASC                        
                                                                        ) AS c GROUP BY  c.idcliente_cartera_llamada;";
                        $sqlAddIndexTemporaryFotoLlamada3 = "ALTER TABLE TMP_FOTO33_".$time." ADD INDEX(idcliente_cartera_llamada);";

                        $sqlCreateTemporaryFotoLlamada4="CREATE TEMPORARY TABLE TMP_FOTO44_".$time." select * from ( -- 270
                                                                            select lla.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                                                            lla.fecha AS 'FECHA_UL_MOTNOPAGO',
                                                                            (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = lla.idmotivo_no_pago ) AS 'UL_MOTIVO_NO_PAGO' 
                                                                            from ca_llamada lla 
                                                                            inner join ca_cuenta cu on cu.idcuenta = lla.idcuenta
                                                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = lla.idcliente_cartera 
                                                                            inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                                                            inner join ca_cartera car on car.idcartera = clicar.idcartera
                                                                            inner join ca_campania cam on cam.idcampania = car.idcampania
                                                                            where  car.estado = 1 and ( MONTH(lla.fecha) > (MONTH(now())-2) ) and cam.idservicio = 6 and cli.idservicio=6   and lla.idmotivo_no_pago is not null  and  cli.idcliente in( ".$CodigoClientesByLlamadasFinal."  ) and DATE(lla.fecha) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ORDER BY clicar.codigo_cliente,lla.fecha DESC 
                                                                                                                                                    ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO;";
                        $sqlAddIndexTemporaryFotoLlamada4="ALTER TABLE  TMP_FOTO44_".$time." ADD INDEX(IDCLIENTE_CARTERA_UL_MOTNOPAGO);";

                        $sqlCreateTemporaryFotoLlamada5="CREATE TEMPORARY TABLE TMP_FOTO55_".$time."    select * from (  -- 67
                                                                            select lla.idcliente_cartera as 'IDCLIENTE_CARTERA_UL_TIPOCOBRANZA',clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCOBRANZA', (select par.nombre from ca_parentesco par where par.idparentesco=lla.idparentesco ) AS 'NOMBRE_UL_TIPO_COBRANZA', lla.fecha AS 'FECHA_UL_TIPO_COBRANZA' 
                                                                            FROM ca_cartera car 
                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                            WHERE clicar.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." )  
                                                                            AND cli.idservicio = 6
                                                                            AND car.idcartera IN ( ".$carteras.")
                                                                            AND lla.idparentesco is not null
                                                                            order by lla.fecha,clicar.codigo_cliente DESC
                                                                        ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA;";
                        $sqlAddIndexTemporaryFotoLlamada5="ALTER TABLE TMP_FOTO55_".$time." ADD INDEX(IDCLIENTE_CARTERA_UL_TIPOCOBRANZA);";

                        $sqlCreateTemporaryFotoLlamada6="CREATE TEMPORARY TABLE FOTO_LLAMADA_".$time."  select * from   TMP_FOTO11_".$time."  b

                                                                LEFT JOIN TMP_FOTO22_".$time." d ON d.codigo=b.codcent;";
                        $sqlAddIndexTemporaryFotoLlamada6="ALTER TABLE FOTO_LLAMADA_".$time." ADD INDEX(idcliente_cartera_fotocartera);";
                        
                        $sqlCreateTemporaryFotoLlamada7 = "CREATE TEMPORARY TABLE FOTO_LLAMADA2_".$time."  select  *  FROM TMP_FOTO33_".$time." d  

                                                                    LEFT JOIN TMP_FOTO44_".$time." b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO = d.idcliente_cartera_llamada

                                                                    LEFT JOIN TMP_FOTO55_".$time." e ON e.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA = d.idcliente_cartera_llamada ;";
                        $sqlAddIndexTemporaryFotoLlamada7 = "ALTER TABLE FOTO_LLAMADA2_".$time." ADD INDEX(idcliente_cartera_llamada);";

                        $prCreateTemporaryFotoLlamada =$connection->prepare($sqlCreateTemporaryFotoLlamada);
                        if($prCreateTemporaryFotoLlamada->execute()){
                          $prAddIndexTemporaryFotoLlamada=$connection->prepare($sqlAddIndexTemporaryFotoLlamada);
                          $prAddIndexTemporaryFotoLlamada->execute();

                          $prCreateTemporaryFotoLlamada2 = $connection->prepare($sqlCreateTemporaryFotoLlamada2);
                          if($prCreateTemporaryFotoLlamada2->execute()){
                            $prAddIndexTemporaryFotoLlamada2 = $connection->prepare($sqlAddIndexTemporaryFotoLlamada2);
                            $prAddIndexTemporaryFotoLlamada2->execute();

                            $prCreateTemporaryFotoLlamada3 = $connection->prepare($sqlCreateTemporaryFotoLlamada3);
                            if($prCreateTemporaryFotoLlamada3->execute()){
                              $prAddIndexTemporaryFotoLlamada3 = $connection->prepare($sqlAddIndexTemporaryFotoLlamada3);
                              $prAddIndexTemporaryFotoLlamada3->execute();

                              $prCreateTemporaryFotoLlamada4 = $connection->prepare($sqlCreateTemporaryFotoLlamada4);
                              if($prCreateTemporaryFotoLlamada4->execute()){
                                $prAddIndexTemporaryFotoLlamada4 = $connection->prepare($sqlAddIndexTemporaryFotoLlamada4);
                                $prAddIndexTemporaryFotoLlamada4->execute();

                                $prCreateTemporaryFotoLlamada5 = $connection->prepare($sqlCreateTemporaryFotoLlamada5);
                                if($prCreateTemporaryFotoLlamada5->execute()){
                                  $prAddIndexTemporaryFotoLlamada5 = $connection->prepare($sqlAddIndexTemporaryFotoLlamada5);
                                  $prAddIndexTemporaryFotoLlamada5->execute();

                                  $prCreateTemporaryFotoLlamada6 = $connection->prepare($sqlCreateTemporaryFotoLlamada6);
                                  if($prCreateTemporaryFotoLlamada6->execute()){
                                    $prAddIndexTemporaryFotoLlamada6= $connection->prepare($sqlAddIndexTemporaryFotoLlamada6);
                                    $prAddIndexTemporaryFotoLlamada6->execute();

                                    $prCreateTemporaryFotoLlamada7= $connection->prepare($sqlCreateTemporaryFotoLlamada7);
                                    if($prCreateTemporaryFotoLlamada7->execute()){
                                      $prAddIndexTemporaryFotoLlamada7= $connection->prepare($sqlAddIndexTemporaryFotoLlamada7);
                                      $prAddIndexTemporaryFotoLlamada7->execute();
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                        
                        $sqlLlenarTemporaryLlamada = "CREATE TEMPORARY TABLE TMP_FOTOLLA_PT_".$time." SELECT * FROM FOTO_LLAMADA_".$time." FOTOCARTERA 

                                                            LEFT JOIN FOTO_LLAMADA2_".$time." h ON FOTOCARTERA.idcliente_cartera_fotocartera = h.idcliente_cartera_llamada
                                                            ";
                        $prLlenarTemporaryLlamada = $connection->prepare($sqlLlenarTemporaryLlamada);
                        if( $prLlenarTemporaryLlamada->execute() ){
                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(CODIGO_CLIENTE)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL2= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(CONTRATO)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL2=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL2);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL2->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL3= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(DATA)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL3=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL3);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL3->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL4= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(codcent)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL4=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL4);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL4->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL5= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(peso)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL5=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL5);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL5->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL6= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(TIPO_CONTACTO)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL6=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL6);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL6->execute();

                            $sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL7= "ALTER TABLE TMP_FOTOLLA_PT_".$time." ADD INDEX(FECHA_UL_TIPO_COBRANZA)";
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL7=$connection->prepare($sqlAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL7);
                            $prAddIndexTemporaryFOTOCARTERALLAMDA_PORIVION_TOTAL7->execute();
                            
                            /* ultima prov cargada */
                            $getUltimaProvCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 ";
                            $prGetUltimaProvCargada = $connection->prepare($getUltimaProvCargada);
                            $prGetUltimaProvCargada->execute();
                            $ultimaProvisionCargada=$prGetUltimaProvCargada->fetchAll(PDO::FETCH_ASSOC);
                            $nameUltProvisionCargada= $ultimaProvisionCargada[0]['nombre_tabla'];


                            $sqlSumaPrincipales= "CREATE TEMPORARY TABLE TMP_SUMAPRINCIPALES_".$time."  SELECT 
                                                        LLAMADASTOTAL.codcent AS 'codcent',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL'
                                                    FROM TMP_FOTOVI_PT_".$time." VISITASTOTAL
                                                    RIGHT JOIN TMP_FOTOLLA_PT_".$time." LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";
                            $prSumaPrincipales = $connection->prepare($sqlSumaPrincipales);
                            $prSumaPrincipales->execute();

                            $sqlAddIndexSumaPrincipales= "ALTER TABLE TMP_SUMAPRINCIPALES_".$time." ADD INDEX(codcent)";
                            $prAddIndexSumaPrincipales=$connection->prepare($sqlAddIndexSumaPrincipales);
                            $prAddIndexSumaPrincipales->execute();

                            $sqlSumaPrincipales2= "CREATE TEMPORARY TABLE TMP_SUMAPRINCIPALES2_".$time."  SELECT 
                                                        LLAMADASTOTAL.codcent AS 'codcent',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL'
                                                    FROM TMP_FOTOVI_PT_".$time." VISITASTOTAL
                                                    RIGHT JOIN TMP_FOTOLLA_PT_".$time." LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";
                            $prSumaPrincipales2 = $connection->prepare($sqlSumaPrincipales2);
                            $prSumaPrincipales2->execute();

                            $sqlAddIndexSumaPrincipales2= "ALTER TABLE TMP_SUMAPRINCIPALES2_".$time." ADD INDEX(codcent)";
                            $prAddIndexSumaPrincipales2=$connection->prepare($sqlAddIndexSumaPrincipales2);
                            $prAddIndexSumaPrincipales2->execute();

                            $sqlSumaPrincipales3= "CREATE TEMPORARY TABLE TMP_SUMAPRINCIPALES3_".$time."  SELECT 
                                                        LLAMADASTOTAL.codcent AS 'codcent',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL'
                                                    FROM TMP_FOTOVI_PT_".$time." VISITASTOTAL
                                                    RIGHT JOIN TMP_FOTOLLA_PT_".$time." LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";
                            $prSumaPrincipales3 = $connection->prepare($sqlSumaPrincipales3);
                            $prSumaPrincipales3->execute();

                            $sqlAddIndexSumaPrincipales3= "ALTER TABLE TMP_SUMAPRINCIPALES3_".$time." ADD INDEX(codcent)";
                            $prAddIndexSumaPrincipales3=$connection->prepare($sqlAddIndexSumaPrincipales3);
                            $prAddIndexSumaPrincipales3->execute();

                            $sqlSumaPrincipales4= "CREATE TEMPORARY TABLE TMP_SUMAPRINCIPALES4_".$time."  SELECT 
                                                        LLAMADASTOTAL.codcent AS 'codcent',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL'
                                                    FROM TMP_FOTOVI_PT_".$time." VISITASTOTAL
                                                    RIGHT JOIN TMP_FOTOLLA_PT_".$time." LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";
                            $prSumaPrincipales4 = $connection->prepare($sqlSumaPrincipales4);
                            $prSumaPrincipales4->execute();

                            $sqlAddIndexSumaPrincipales4= "ALTER TABLE TMP_SUMAPRINCIPALES4_".$time." ADD INDEX(codcent)";
                            $prAddIndexSumaPrincipales4=$connection->prepare($sqlAddIndexSumaPrincipales4);
                            $prAddIndexSumaPrincipales4->execute();

                           $sqlReporte = "SELECT        ( SELECT SUMPRIN.VARIACION_HIPOTECARIO FROM TMP_SUMAPRINCIPALES_".$time." SUMPRIN WHERE SUMPRIN.codcent=LLAMADASTOTAL.codcent limit 1 )  AS 'VARIACION_HIPOTECARIO',
                                                        ( SELECT SUMPRIN.VARIACION_PROVISION FROM TMP_SUMAPRINCIPALES2_".$time." SUMPRIN WHERE SUMPRIN.codcent=LLAMADASTOTAL.codcent limit 1 )  AS 'VARIACION_PROVISION',
                                                        ( SELECT SUMPRIN.VARIACION_HIPOTECARIO_TOTAL FROM TMP_SUMAPRINCIPALES3_".$time." SUMPRIN WHERE SUMPRIN.codcent=LLAMADASTOTAL.codcent limit 1 )  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        ( SELECT SUMPRIN.VARIACION_PROVISION_TOTAL FROM TMP_SUMAPRINCIPALES4_".$time." SUMPRIN WHERE SUMPRIN.codcent=LLAMADASTOTAL.codcent limit 1 )  AS 'VARIACION_PROVISION_TOTAL',
                                                       (SELECT A.fecha_provision FROM ( SELECT hisprotot.fecha_provision,hisprotot.codcen FROM ca_historico_provision_total hisprotot ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'FECHA_PROVISION',
                                                       (SELECT A.fecha_provision_total FROM ( SELECT hisprotot.fecha_provision_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_provision_total  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'FECHA_PROVISION_TOTAL',
                                                       (SELECT A.fecha_ingreso FROM ( SELECT hisprotot.fecha_ingreso,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'FECHA_INGRESO',
                                                       (SELECT A.fecha_ingreso_total FROM ( SELECT hisprotot.fecha_ingreso_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso_total  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'FECHA_INGRESO_TOTAL',
                                                       (SELECT A.status FROM ( SELECT hisprotot.status,hisprotot.codcen FROM ca_historico_provision_total hisprotot  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'STATUS',
                                                       (SELECT A.provisionPositiva FROM ( SELECT hisprotot.provisionPositiva,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisionPositiva  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'VARIACION_TOTAL_HISTORICO',
                                                       (SELECT A.hipotecarioPositivo FROM ( SELECT hisprotot.hipotecarioPositivo,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPositivo IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'VARIACION_HIPOTECARIO_HISTORICO',
                                                       (SELECT A.provisitonPosiNega FROM ( SELECT hisprotot.provisitonPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisitonPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'VARIACION_TOTAL_HISTORICO_+-',
                                                       (SELECT A.hipotecarioPosiNega FROM ( SELECT hisprotot.hipotecarioPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent limit 1 ) AS 'VARIACION_HIPOTECARIO_HISTORICO_+-',
                                                       (SELECT A.isRetirado FROM ( select a.isRetirado,a.codcen FROM (
                                                                                                                       SELECT hisprotot.isRetirado,hisprotot.codcen,hisprotot.idhistorial_tabla_provision_total FROM ca_historico_provision_total hisprotot WHERE hisprotot.isRetirado IS NOT NULL ORDER BY hisprotot.date_provision DESC )as a INNER JOIN (
                                                                                                                      SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 )as b ON a.idhistorial_tabla_provision_total=b.idhistorial_tabla_provision_total
                                                                                                                      )AS A 
                                                                                                                WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'isRetirado',

                                                        (SELECT max(hisprotot.provisitonPosiNega) FROM ca_historico_provision_total hisprotot where hisprotot.codcen = LLAMADASTOTAL.codcent AND hisprotot.date_provision>date_sub(hisprotot.date_provision,INTERVAL 2 MONTH))AS MAXPROVI3MESES,
                                           LLAMADASTOTAL.agencia AS 'CARTERA',LLAMADASTOTAL.nom_subprod AS 'NOM_SUBPROD',LLAMADASTOTAL.territorio AS 'TERRITORIO',LLAMADASTOTAL.oficina2 AS 'OFICINA2',LLAMADASTOTAL.contrato AS 'CONTRATO',LLAMADASTOTAL.codcent AS 'CODCENT',LLAMADASTOTAL.nombre AS 'NOMBRE',LLAMADASTOTAL.saldohoy AS 'DEUDA',LLAMADASTOTAL.total_deuda AS 'TOTALDEUDA',LLAMADASTOTAL.direccion_domicilio AS 'DIRECCION',LLAMADASTOTAL.diavenc AS 'DIAVENC',LLAMADASTOTAL.dist_prov AS 'DIST_PROV',LLAMADASTOTAL.marca AS 'MARCA',LLAMADASTOTAL.producto AS 'PRODUCTO',LLAMADASTOTAL.codigo_user AS 'CODIGO_GESTOR',LLAMADASTOTAL.asignadoFOTOCARTERA AS 'GESTOR_ASIGNADO',LLAMADASTOTAL.tramo_dia AS 'TRAMO_DIA_HDEC',LLAMADASTOTAL.statusCUENTA AS 'FLAG_CUENTA',
                                                    LLAMADASTOTAL.TIPO_CONTACTO AS 'TIPO_CONTACTO_CALL',LLAMADASTOTAL.ESTADO_LLAMADA AS 'ESTADO_CONTACTO_CALL',LLAMADASTOTAL.FECHA_CP AS 'FECHA_COMPROMISO_CALL',LLAMADASTOTAL.OBSERVACION AS 'OBSERVACION_CALL', 
                                                    VISITASTOTAL.CARGA AS 'TIPO_CONTACTO_CAMPO',VISITASTOTAL.ESTADO_VISITA AS 'ESTADO_CONTACTO_CAMPO', VISITASTOTAL.FECHA_CPG AS 'FECHA_COMPROMISO_CAMPO',VISITASTOTAL.OBSERVACION AS 'OBSERVACION_CAMPO',
                                                    CASE 
                                                            WHEN ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO>VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NULL )  THEN LLAMADASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO>LLAMADASTOTAL.FECHA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NOT NULL )  THEN VISITASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            ELSE null
                                                    END AS 'MOTIVO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA>VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NULL )  THEN LLAMADASTOTAL.NOMBRE_UL_TIPO_COBRANZA
                                                        WHEN ( VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO>LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NOT NULL )  THEN VISITASTOTAL.TIPO_CONTACTO
                                                        ELSE null
                                                    END AS 'TIPO_DE_COBRANZA',
                                            -- LLAMADASTOTAL.peso AS 'PESO1',
                                            -- VISITASTOTAL.peso AS 'PESO2',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN 'CALL'
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN 'CAMPO'
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_GESTION',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.TIPO_CONTACTO
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.CARGA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_TIPO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.ESTADO_LLAMADA
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.ESTADO_VISITA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_ESTADO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.FECHA_CP 
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.FECHA_CPG
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_FECHA_COMPROMISO'



                                                    FROM TMP_FOTOVI_PT_".$time." VISITASTOTAL
                                                    RIGHT JOIN TMP_FOTOLLA_PT_".$time." LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";

                        $prReporte=$connection->prepare($sqlReporte);
                        if($prReporte->execute()){
                            $contenidoReporte= $prReporte->fetchAll(PDO::FETCH_ASSOC);
                            /*Colores Cabecera por fotocartera-llamada-visita-mejorvisitallamada,historialdeprovision-provision*/
                            $colorFotoCartera = "background:#C00000;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorCallCampo = "background:#963634;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorMejorGestion = "background : #00B0F0 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorHistorial = "background : #FFFF00 ;color:black;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvision = "background : #632523 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvisionMASMENOS = "background : #000000 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $contenidoCuerpoFotoCartera = "height:20px;font-size:11px;border: 1px solid #000000";
                            /*CABECERA*/
                            $cabecera = "<table><tr><td style=\"".$colorFotoCartera."\">CARTERA</td><td style=\"".$colorFotoCartera."\">NOM_SUBPROD</td><td style=\"".$colorFotoCartera."\">TERRITORIO</td><td style=\"".$colorFotoCartera."\">OFICINA2</td><td style=\"".$colorFotoCartera."\">CONTRATO</td><td style=\"".$colorFotoCartera."\">CODCENT</td><td style=\"".$colorFotoCartera."\">NOMBRE</td><td style=\"".$colorFotoCartera."\">DEUDA</td><td style=\"".$colorFotoCartera."\">DEUDA_TOTAL</td><td style=\"".$colorFotoCartera."\">DIRECCION</td><td style=\"".$colorFotoCartera."\">DIAVENC</td><td style=\"".$colorFotoCartera."\">DIST_PROV</td><td style=\"".$colorFotoCartera."\">MARCA</td><td style=\"".$colorFotoCartera."\">PRODUCTO</td><td style=\"".$colorFotoCartera."\">CODIGO_GESTOR</td><td style=\"".$colorFotoCartera."\">GESTOR_ASIGNADO</td><td style=\"".$colorFotoCartera."\">TRAMO_DIA_HDEC</td><td style=\"".$colorFotoCartera."\">FLAG_CUENTA</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CALL</td><td style=\"".$colorCallCampo."\">OBSERVACION_CALL</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CAMPO</td><td style=\"".$colorCallCampo."\">OBSERVACION_CAMPO</td><td style=\"".$colorCallCampo."\">MOTIVO</td><td style=\"".$colorCallCampo."\">TIPO_DE_COBRANZA</td><td style=\"".$colorMejorGestion."\">MEJOR_GESTION</td><td style=\"".$colorMejorGestion."\">MEJOR_TIPO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_ESTADO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_FECHA_COMPROMISO</td><td style=\"".$colorHistorial."\">FECHA_PROVISION</td><td style=\"".$colorHistorial."\">FECHA_INGRESO</td><td style=\"".$colorHistorial.";text-align:center"."\">STATUS</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO</td><td style=\"".$colorProvision."\">VARIACION_TOTAL</td><td style=\"".$colorProvision."\">VARIACION_HIPOTECARIO</td><td style=\"".$colorProvisionMASMENOS."\">MAYOR_PROVISION_3MESESATRAS</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_TOTAL_+-</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_HIPOTECARIO_+-</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO_+-</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO_+-</td><td style=\"".$colorHistorial."\">FECHA_PROVISION_TOTAL</td><td style=\"".$colorHistorial."\">FECHA_INGRESO_TOTAL</td></tr></table>";   
                            /*CUERPO*/
                            $cuerpo ="<table>";
                            for($i=0;$i<count($contenidoReporte);$i++){
                                if($contenidoReporte[$i]['FECHA_PROVISION_TOTAL']==""){
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION'];
                                }else{
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION_TOTAL'] ;
                                }

                                if($contenidoReporte[$i]['FECHA_INGRESO_TOTAL']==""){
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO'];
                                }else{
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO_TOTAL'];
                                }

                                if($contenidoReporte[$i]['TIPO_CONTACTO_CALL']==""){
                                    $mejorGestion = "CALL";
                                    $mejorTipoContacto = "NOC";
                                    $mejorEstadoContacto = "NO CONTESTA";
                                    $tipoContactoCall = "NOC";
                                    $estadoContactoCall = "NO CONTESTA";
                                    $observacionCall = "NO CONTESTA";
                                    $motivo = "NO HAY MOTIVO DE NO PAGO";
                                }else{
                                    $mejorGestion = $contenidoReporte[$i]['MEJOR_GESTION'];
                                    $mejorTipoContacto = $contenidoReporte[$i]['MEJOR_TIPO_CONTACTO'];
                                    $mejorEstadoContacto = $contenidoReporte[$i]['MEJOR_ESTADO_CONTACTO'];
                                    $tipoContactoCall = $contenidoReporte[$i]['TIPO_CONTACTO_CALL'];
                                    $estadoContactoCall = $contenidoReporte[$i]['ESTADO_CONTACTO_CALL'];
                                    $observacionCall = $contenidoReporte[$i]['OBSERVACION_CALL'];
                                    $motivo = $contenidoReporte[$i]['MOTIVO'];
                                }

                                if($contenidoReporte[$i]['FLAG_CUENTA']==0 || $contenidoReporte[$i]['isRetirado']==1){
                                    $variacionProvision="";
                                    $variacionHipotecario="";
                                    $variacionProvisionTotal="";
                                    $variacionHipotecarioTotal="";
                                    $flagCuenta = "0";
                                }else{
                                    $variacionProvision=$contenidoReporte[$i]['VARIACION_PROVISION'];
                                    $variacionHipotecario=$contenidoReporte[$i]['VARIACION_HIPOTECARIO'];
                                    $variacionProvisionTotal=$contenidoReporte[$i]['VARIACION_PROVISION_TOTAL'];
                                    $variacionHipotecarioTotal=$contenidoReporte[$i]['VARIACION_HIPOTECARIO_TOTAL'];
                                    $flagCuenta=$contenidoReporte[$i]['FLAG_CUENTA'];
                                }
                                $cuerpo.="  <tr>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CARTERA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['NOM_SUBPROD']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TERRITORIO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OFICINA2']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CONTRATO']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CODCENT']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['NOMBRE'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TOTALDEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIRECCION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIAVENC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIST_PROV']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MARCA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['PRODUCTO'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CODIGO_GESTOR']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['GESTOR_ASIGNADO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TRAMO_DIA_HDEC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$flagCuenta."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$tipoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$estadoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CALL']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$observacionCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['ESTADO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OBSERVACION_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$motivo."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_DE_COBRANZA']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorGestion."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorTipoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorEstadoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MEJOR_FECHA_COMPROMISO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_PROVISION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_INGRESO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['STATUS']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvision."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecario."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MAXPROVI3MESES']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecarioTotal."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaIngresoTotal."</td>
                                            </tr>";
                            }
                            $cuerpo.="</table>";
                            

                         
                            echo $cabecera;
                            echo $cuerpo;
                        }else{}
                        }else{
                        }
                //    }else{
                 //   }
                }else{
                }
        }else if($countClientesbyVisitas==0 && $countClientesbyLlamadas!=0)/*2DO*/{

                $sqlLlenarTemporaryVisita = "INSERT TMP_FOTOCARTERAVISITA_PROVISION_TOTAL (
                                            SELECT * FROM ( 
                            SELECT
                            cli_car.idcliente_cartera AS 'idcliente_cartera',cli.codigo AS codcent  
                            FROM ca_cliente cli
                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                            WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo DESC 
                    ) AS FOTOCARTERA   

                    INNER JOIN( 
                        select * from(
                            select * from (
                                select * from (
                                    SELECT 
                                    vis.idcliente_cartera AS 'idcliente_cartera_visita',
                                    (SELECT fin.idfinal from ca_final fin where fin.idfinal=vis.idfinal)AS 'idfinal',
                                    (SELECT finser.peso from ca_final_servicio finser inner join ca_final fin on fin.idfinal=finser.idfinal where fin.idfinal= vis.idfinal) AS 'peso',
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                    vis.fecha_cp AS 'FECHA_CPG',
                                    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal) as 'CARGA',
                                    ( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO_VISITA',
                                 replace(replace(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION'
                                    FROM ca_cartera car 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                    INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                                    INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                                    WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                                    AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                                ) AS f ORDER BY f.idfinal,f.peso,f.codigo_cliente desc 
                            )AS a GROUP BY a.idcliente_cartera_visita 
                        )AS a

                        LEFT JOIN (
                                select * from (
                                select vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                vis.fecha_visita AS 'FECHA_VISITA_UL_MOTNOPAGO',
                                clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = vis.idmotivo_no_pago) AS 'UL_MOTIVO_NO_PAGO' 
                                from    ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                inner join ca_cartera car on car.idcartera = clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania
                                where vis.idmotivo_no_pago is not null and cam.idservicio = 6 and car.estado=1 and cli.idservicio=6 and  ( MONTH(vis.fecha_visita) > (MONTH(now())-2) ) and cli.codigo 
                                    in (0) and ( DATE(vis.fecha_visita) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ) ORDER BY vis.fecha_visita,clicar.codigo_cliente desc 
                                ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO  
                        ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO=a.idcliente_cartera_visita

                        LEFT JOIN( 
                            select * from (
                                select 
                               ( select  par.nombre  from ca_parentesco par where par.idparentesco = vis.idparentesco )AS 'TIPO_CONTACTO', vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_TIPO_CONTACTO', clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCONTACTO', vis.fecha_visita AS 'FECHA_VISITA_UL_TIPOCONTACTO' 
                                from ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera  clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cartera car on car.idcartera= clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania  -- 643
                                where car.estado = 1 and cam.idservicio = 6 and car.idcartera IN (".$carteras.") and vis.idparentesco is not null order by vis.fecha_visita,clicar.idcliente_cartera DESC
                            ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO 
                        )AS c ON c.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO =a.idcliente_cartera_visita

                    ) AS VISITAS ON VISITAS.idcliente_cartera_visita=FOTOCARTERA.idcliente_cartera
                )";

                $prLlenarTemporaryVisita = $connection->prepare($sqlLlenarTemporaryVisita);   
                if($prLlenarTemporaryVisita->execute()){
                    
                    /*
                        --   DAYOFWEEK( lla.fecha ),
                        --    CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                        --         WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                        --   ELSE 'B'
                        --  END AS 'FRECUENCIA',    
                        --  lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal) AS 'peso',
                        --  cu.estado AS 'FLAG_CUENTA',
                        --  car.nombre_cartera AS 'NOMBRE_GESTION',
                        --  cu.numero_cuenta AS 'NUMERO_CUENTA',
                        --  clicar.dato1 AS 'CARTERA',
                        --  clicar.idcliente_cartera AS 'DATA',
                        --  CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) AS 'CLIENTE',
                        --  cli.numero_documento AS 'NUMERO_DOCUMENTO',
                        --  car.fecha_inicio as 'FECHA_INICIO',
                        --  car.fecha_fin as 'FECHA_FIN',
                        --  DATE(lla.fecha) AS 'FECHA_LLAMADA',
                        --  CASE WHEN DAYOFWEEK(DATE(lla.fecha)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) - 1) WHEN DAYOFWEEK(DATE(lla.fecha)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) + 1)  ELSE DATE(lla.fecha) END AS 'FECHA_SIG_LLAMADA',
                        --  CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal)  END    AS 'TIPO_CONTACTO',
                        -- replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                        -- DATE(lla.fecha_cp) AS 'FECHA_CP',
                        -- TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                        -- ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'TELEOPERADOR',
                        -- ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  LIMIT 1 ) AS 'ASIGNADO_LLAMADA',
                        -- ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'CONTACTO',
                        -- lla.nombre_contacto AS 'NOMBRE_CONTACTO',
                        -- ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) AS 'PARENTESCO' ,
                        -- (SELECT mnp.nombre from ca_motivo_no_pago mnp where mnp.idmotivo_no_pago=lla.idmotivo_no_pago) AS 'MOTIVO_NO_PAGO',
                                                    
                    */
                    /*UN COUNT ANTES PORSEACASO NO HALLAN CODIGOS*/
                    $sqlDistinctClienteByLlamada = "select  distinct CODIGO_CLIENTE AS CODIGO_CLIENTE  FROM (  -- DISTINTOS CODIGO_CLIENTES DE LLAMADAS
                                SELECT * FROM (
                                    SELECT * FROM (
                                                    SELECT
                                                
                                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                    lla.idcliente_cartera AS 'idcliente_cartera_llamada'
                                                    FROM ca_cartera car 
                                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                    INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                    INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                    INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                    WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  -- 2600
                                                    AND cli.idservicio = 6
                                                    AND car.idcartera IN (".$carteras.")    
                                                    GROUP BY lla.idcliente_cartera,lla.fecha 
                                    ) AS b  ORDER BY  b.CODIGO_CLIENTE ASC
                                ) AS c GROUP BY  c.idcliente_cartera_llamada
                            ) AS d " ;
            
                    $prDistinctClienteByLlamada = $connection->prepare($sqlDistinctClienteByLlamada);
                    $prDistinctClienteByLlamada->execute();
                    $distinctClienteByLlamada = $prDistinctClienteByLlamada->fetchAll(PDO::FETCH_ASSOC);

                    $arrayCodigoClienteLlamada = array();
                    for($i=0;$i<count($distinctClienteByLlamada);$i++){
                        array_push($arrayCodigoClienteLlamada,$distinctClienteByLlamada[$i]['CODIGO_CLIENTE']);
                    }
                    $CodigoClientesByLlamadasFinal = implode($arrayCodigoClienteLlamada, ',');


                    $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                    $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                    $prEliminarTMP->execute();
                    /*TEMPORARY PARA LAS LLAMADASTOTAL */

                    /*dps nombre
                       -- nro_doc varchar(50),
                    */
                    /*dsps divisa
                       -- divisa varchar(20),
                    */
                    /*dsps tramo_dia
                        -- contrato2 varchar(30),
                    */
                    /*dsps agencia
                        --  Gestor varchar(30),
                    */
                    /*dsps dist_prov
                        --  subprod varchar(30),
                    */
                    /*dsps territorio
                        --  oficina varchar(25),
                    */
                    /*dsps statuscuenta
                        --  idGestor varchar(25),
                    */
                    /*dsps peso
                         --   FLAG_CUENTA TINYINT(11),
                         --   NOMBRE_GESTION varchar(50),
                         --   NUMERO_CUENTA varchar(50),
                         --   CARTERA  varchar(50),
                    */
                    /*dsps codigo_cliente
                         --   CLIENTE  varchar(50),
                         --   NUMERO_DOCUMENTO  varchar(50),
                    */
                    /*dsps iodcliente_Cartera_llamada
                         --   FECHA_INICIO date,
                         --   FECHA_FIN date,
                         --   FECHA_LLAMADA date,
                         --   FECHA_SIG_LLAMADA date,
                    */
                    /*dsps monto_cp
                         --   TELEOPERADOR  varchar(150),
                         --   ASIGNADO_LLAMADA varchar(50),
                    */
                    /*dsps contacto 
                         --   NOMBRE_CONTACTO varchar(50),
                    */
                     /*dsps estado_llamada
                        --   PARENTESCO varchar(20),
                        --   MOTIVO_NO_PAGO varchar(70),
                     */

                    $sqlCreateTemporaryFotocarteraLlamadas = "CREATE TEMPORARY TABLE TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL 
                                            (
                                                idcliente_cartera_fotocartera int(11),
                                                codcent varchar(50),
                                                Nombre varchar(150),
                                                contrato varchar(50),
                                                saldohoy varchar(20),
                                                producto varchar(30),
                                                tramo_dia varchar(30),
                                                diavenc varchar(25),
                                                agencia varchar(50),
                                                dist_prov varchar(30),
                                                nom_subprod varchar(30),
                                                marca varchar(30),
                                                territorio varchar(30),
                                                oficina2 varchar(30),
                                                statusCLIENTE TINYINT(4),
                                                statusCUENTA TINYINT(4),
                                                asignadoFOTOCARTERA varchar(65),
                                                direccion_domicilio varchar(100),
                                                codigo_user  varchar(30),
                                                codigo varchar(20),
                                                total_deuda  varchar(20),
                                                CONDICION varchar(10),
                                                FECUENCIA varchar(10),
                                                idfinal INT(11),
                                                peso INT(11),
                                                DATA varchar(30),
                                                CODIGO_CLIENTE varchar(30),
                                                idcliente_cartera_llamada int(11),
                                                TIPO_CONTACTO varchar(20),
                                                OBSERVACION  varchar(200),
                                                FECHA_CP date,
                                                MONTO_CP varchar(20),
                                                CONTACTO varchar(50),
                                                ESTADO_LLAMADA varchar(50),
                                                prioridad int(11),
                                                IDCLIENTE_CARTERA_UL_MOTNOPAGO int(11),
                                                CODIGO_CLIENTE_UL_MOTNOPAGO varchar(50),
                                                FECHA_UL_MOTNOPAGO date,
                                                UL_MOTIVO_NO_PAGO  varchar(100),
                                                IDCLIENTE_CARTE_UL_TIPOCOBRANZA int(11),
                                                CODIGO_CLIENTE_UL_TIPOCOBRANZA  varchar(50),
                                                NOMBRE_UL_TIPO_COBRANZA  varchar(50),
                                                FECHA_UL_TIPO_COBRANZA date,
                                                INDEX( idcliente_cartera_fotocartera ),
                                                INDEX( codcent ),
                                                INDEX( statusCLIENTE )
                                                    )";
                
                    $prCreateTemporaryFotocarteraLlamadas=$connection->prepare($sqlCreateTemporaryFotocarteraLlamadas);
                    if( $prCreateTemporaryFotocarteraLlamadas->execute() ){
                        
                        /*fotocartera
                            --cli.numero_documento AS nro_doc,
                            cuen.moneda AS divisa,
                            cuen_deta.codigo_operacion AS contrato2,
                            cli_car.dato2 AS Gestor,
                            ,cuen.dato2 AS subprod
                            cuen.dato10 AS oficina,
                            (SELECT pri.nombre AS idGestor FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_privilegio pri ON us.idprivilegio=pri.idprivilegio WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS idGestor,

                        */

                        /*  
                            -- cu.estado AS 'FLAG_CUENTA',
                            --  car.nombre_cartera AS 'NOMBRE_GESTION',
                            --  cu.numero_cuenta AS 'NUMERO_CUENTA',
                            --  clicar.dato1 AS 'CARTERA',
                            --  CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) AS 'CLIENTE',
                            --  cli.numero_documento AS 'NUMERO_DOCUMENTO',
                            --  car.fecha_inicio as 'FECHA_INICIO',
                            --  car.fecha_fin as 'FECHA_FIN',
                            --  DATE(lla.fecha) AS 'FECHA_LLAMADA',
                            --   CASE WHEN DAYOFWEEK(DATE(lla.fecha)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) - 1) WHEN DAYOFWEEK(DATE(lla.fecha)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) + 1)  ELSE DATE(lla.fecha) END AS 'FECHA_SIG_LLAMADA',
                            --   ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'TELEOPERADOR',
                            --   ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  LIMIT 1 ) AS 'ASIGNADO_LLAMADA',
                            --    lla.nombre_contacto AS 'NOMBRE_CONTACTO',
                            --   ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) AS 'PARENTESCO' ,
                            --   (SELECT mnp.nombre from ca_motivo_no_pago mnp where mnp.idmotivo_no_pago=lla.idmotivo_no_pago) AS 'MOTIVO_NO_PAGO',
                                                                                                                                                                                                                        
                        */

                        $sqlLlenarTemporaryLlamada = "INSERT TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL (
                                                        SELECT * FROM (
                                                                select * from   (
                                                                        SELECT
                                                                        cli_car.idcliente_cartera AS 'idcliente_cartera_fotocartera',cli.codigo AS codcent,cli.nombre AS Nombre,
                                                                        cuen.numero_cuenta AS contrato,if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") )AS saldohoy,cuen.producto AS producto,CASE 
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                                                                            ELSE 'NO_TRAMO'
                                                                        END AS tramo_dia,
                                                                        cuen_deta.dias_mora AS diavenc,
                                                                        cli_car.dato1 AS agencia,cli_car.dato4 AS dist_prov,cuen.dato3 AS nom_subprod,cuen.dato8 AS marca,cuen.dato9 AS territorio,cuen.dato11 AS oficina2,
                                                                        cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
                                                                        (SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS 'asignadoFOTOCARTERA' FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS asignadoFOTOCARTERA,
                                                                        IFNULL((SELECT CONCAT_WS('  ',IFNULL(dir.direccion,''),IFNULL(dir.departamento,''),IFNULL(dir.provincia,''),IFNULL(dir.distrito,''))
                                                                            FROM ca_direccion dir
                                                                            WHERE dir.idtipo_referencia=2
                                                                                AND dir.idcliente_cartera=cli_car.idcliente_cartera AND dir.idcuenta=cuen.idcuenta
                                                                                AND ISNULL(dir.direccion)!=1 LIMIT 1
                                                                        ),'^^^^^^') AS direccion_domicilio,
                                                                        (SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio) AS codigo_user
                                                                        
                                                                        FROM ca_cliente cli
                                                                        INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                        INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                        INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                        WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo desc
                                                                )AS  b

                                                                LEFT JOIN (
                                                                    select c.codigo AS 'codigo',sum(deuda)AS 'total_deuda' FROM (
                                                                            
                                                                            SELECT  if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") ) AS 'deuda' ,
                                                                            cli.codigo AS 'codigo'
                                                                            FROM ca_cliente cli
                                                                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                            WHERE cuen.idcartera IN (".$carteras.") and cuen.retirado!=1 ORDER BY cli.codigo DESC

                                                                    ) as c GROUP BY c.codigo

                                                                )AS d ON d.codigo=b.codcent
                                                            ) AS FOTOCARTERA 

                                                            LEFT JOIN (

                                                                    select  *  FROM (  
                                                                        SELECT * FROM (
                                                                            
                                                                                    
                                                                                                SELECT
                                                                                               
                                                                                        CASE  WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK(  NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 3 DAY ) )   THEN '1'
                                                                                                WHEN ( DAYOFWEEK(  NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 4 DAY ) )  THEN '1'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 6 DAY ) ) THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 8 DAY ) )  THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 9 DAY ) )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3    and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3   or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 16 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 15 DAY ) ) THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 20 DAY ) )  THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 18 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 21 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 28 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 32 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 27 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 36 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 30 DAY ) )  THEN '10'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 40 DAY ) ) THEN '10'
                                                                                            ELSE 'A'
                                                                                        END AS 'CONDICION',
                                                                                        CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                                                                                            ELSE 'B'
                                                                                        END AS 'FRECUENCIA',    
                                                                                            lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal) AS 'peso',
                                                                                           
                                                                                            clicar.idcliente_cartera AS 'DATA',
                                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                                                          
                                                                                            lla.idcliente_cartera AS 'idcliente_cartera_llamada',
                                                                                         CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal)  END    AS 'TIPO_CONTACTO',
                                                                                            replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                                                                                            DATE(lla.fecha_cp) AS 'FECHA_CP',
                                                                                            TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                                                                                        ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'CONTACTO',
                                                                                          ( SELECT fin.nombre from ca_final fin where fin.idfinal=lla.idfinal ) AS 'ESTADO_LLAMADA',
                                                                                            (select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=6) as 'prioridad'
                                                                                            FROM ca_cartera car 
                                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                                            
                                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  -- 2600
                                                                                            AND cli.idservicio = 6
                                                                                
                                                                                            AND car.idcartera IN (".$carteras.")   ORDER BY  clicar.CODIGO_CLIENTE,CONDICION,lla.fecha,prioridad ASC  
                                                                                            
                                                                                   
                                                                              
                                                                        ) AS c GROUP BY  c.idcliente_cartera_llamada
                                                                    ) AS d  

                                                                    LEFT JOIN (
                                                                        select * from ( -- 270
                                                                            select lla.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                                                            lla.fecha AS 'FECHA_UL_MOTNOPAGO',
                                                                            (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = lla.idmotivo_no_pago ) AS 'UL_MOTIVO_NO_PAGO' 
                                                                            from ca_llamada lla 
                                                                            inner join ca_cuenta cu on cu.idcuenta = lla.idcuenta
                                                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = lla.idcliente_cartera 
                                                                            inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                                                            inner join ca_cartera car on car.idcartera = clicar.idcartera
                                                                            inner join ca_campania cam on cam.idcampania = car.idcampania
                                                                            where  car.estado = 1 and ( MONTH(lla.fecha) > (MONTH(now())-2) ) and cam.idservicio = 6 and cli.idservicio=6   and lla.idmotivo_no_pago is not null  and  cli.codigo in( ".$CodigoClientesByLlamadasFinal." ) and DATE(lla.fecha) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ORDER BY clicar.codigo_cliente,lla.fecha DESC 
                                                                        ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO
                                                                    ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO = d.idcliente_cartera_llamada

                                                                    LEFT JOIN ( 
                                                                            select * from (  -- 67
                                                                            select lla.idcliente_cartera as 'IDCLIENTE_CARTERA_UL_TIPOCOBRANZA',clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCOBRANZA', (select par.nombre from ca_parentesco par where par.idparentesco=lla.idparentesco ) AS 'NOMBRE_UL_TIPO_COBRANZA', lla.fecha AS 'FECHA_UL_TIPO_COBRANZA' 
                                                                            FROM ca_cartera car 
                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  
                                                                            AND cli.idservicio = 6
                                                                            AND car.idcartera IN (".$carteras.")
                                                                            AND lla.idparentesco is not null
                                                                            order by lla.fecha,clicar.codigo_cliente DESC
                                                                        ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA 
                                                                    )   AS e ON e.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA = d.idcliente_cartera_llamada 

                                                                ) AS h ON FOTOCARTERA.idcliente_cartera_fotocartera = h.idcliente_cartera_llamada 
                                                            )";

                        $prLlenarTemporaryLlamada = $connection->prepare($sqlLlenarTemporaryLlamada);
                        if( $prLlenarTemporaryLlamada->execute() ){
                            //probando
                           /* $sqlPrueba= "SELECT * from TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                            $prPrueba = $connection->prepare($sqlPrueba);
                            $prPrueba->execute();
                            $data=$prPrueba->fetchAll(PDO::FETCH_ASSOC);
                            $a="<table>";
                            $style="width:250px;vertical-align:middle;text-align:center";

                            for($i=0;$i<count($data);$i++){
                             $a.="<tr><td>=\"".$data[$i]['codcent']."\"</td></tr>";
                             
                            }
                           
                            $a.="</table>";
                            echo $a;
                            exit();*/
                            //probando


                            /* ultima prov cargada */
                            $getUltimaProvCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 ";
                            $prGetUltimaProvCargada = $connection->prepare($getUltimaProvCargada);
                            $prGetUltimaProvCargada->execute();
                            $ultimaProvisionCargada=$prGetUltimaProvCargada->fetchAll(PDO::FETCH_ASSOC);
                            $nameUltProvisionCargada= $ultimaProvisionCargada[0]['nombre_tabla'];


                            $sqlReporte = "SELECT (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL',
                                                       (SELECT A.fecha_provision FROM ( SELECT hisprotot.fecha_provision,hisprotot.codcen FROM ca_historico_provision_total hisprotot ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION',
                                                       (SELECT A.fecha_provision_total FROM ( SELECT hisprotot.fecha_provision_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_provision_total  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION_TOTAL',
                                                       (SELECT A.fecha_ingreso FROM ( SELECT hisprotot.fecha_ingreso,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO',
                                                       (SELECT A.fecha_ingreso_total FROM ( SELECT hisprotot.fecha_ingreso_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso_total  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO_TOTAL',
                                                       (SELECT A.status FROM ( SELECT hisprotot.status,hisprotot.codcen FROM ca_historico_provision_total hisprotot  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'STATUS',
                                                       (SELECT A.provisionPositiva FROM ( SELECT hisprotot.provisionPositiva,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisionPositiva  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO',
                                                       (SELECT A.hipotecarioPositivo FROM ( SELECT hisprotot.hipotecarioPositivo,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPositivo IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO',
                                                       (SELECT A.provisitonPosiNega FROM ( SELECT hisprotot.provisitonPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisitonPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO_+-',
                                                       (SELECT A.hipotecarioPosiNega FROM ( SELECT hisprotot.hipotecarioPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO_+-',
                                                       (SELECT A.isRetirado FROM ( select a.isRetirado,a.codcen FROM (
                                                                                                                       SELECT hisprotot.isRetirado,hisprotot.codcen,hisprotot.idhistorial_tabla_provision_total FROM ca_historico_provision_total hisprotot WHERE hisprotot.isRetirado IS NOT NULL ORDER BY hisprotot.date_provision DESC )as a INNER JOIN (
                                                                                                                      SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 )as b ON a.idhistorial_tabla_provision_total=b.idhistorial_tabla_provision_total
                                                                                                                      )AS A 
                                                                                                                WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'isRetirado',

                                                        (SELECT max(hisprotot.provisitonPosiNega) FROM ca_historico_provision_total hisprotot where hisprotot.codcen = LLAMADASTOTAL.codcent AND hisprotot.date_provision>date_sub(hisprotot.date_provision,INTERVAL 2 MONTH))AS MAXPROVI3MESES,
                                           LLAMADASTOTAL.agencia AS 'CARTERA',LLAMADASTOTAL.nom_subprod AS 'NOM_SUBPROD',LLAMADASTOTAL.territorio AS 'TERRITORIO',LLAMADASTOTAL.oficina2 AS 'OFICINA2',LLAMADASTOTAL.contrato AS 'CONTRATO',LLAMADASTOTAL.codcent AS 'CODCENT',LLAMADASTOTAL.nombre AS 'NOMBRE',LLAMADASTOTAL.saldohoy AS 'DEUDA',LLAMADASTOTAL.total_deuda AS 'TOTALDEUDA',LLAMADASTOTAL.direccion_domicilio AS 'DIRECCION',LLAMADASTOTAL.diavenc AS 'DIAVENC',LLAMADASTOTAL.dist_prov AS 'DIST_PROV',LLAMADASTOTAL.marca AS 'MARCA',LLAMADASTOTAL.producto AS 'PRODUCTO',LLAMADASTOTAL.codigo_user AS 'CODIGO_GESTOR',LLAMADASTOTAL.asignadoFOTOCARTERA AS 'GESTOR_ASIGNADO',LLAMADASTOTAL.tramo_dia AS 'TRAMO_DIA_HDEC',LLAMADASTOTAL.statusCUENTA AS 'FLAG_CUENTA',
                                                    LLAMADASTOTAL.TIPO_CONTACTO AS 'TIPO_CONTACTO_CALL',LLAMADASTOTAL.ESTADO_LLAMADA AS 'ESTADO_CONTACTO_CALL',LLAMADASTOTAL.FECHA_CP AS 'FECHA_COMPROMISO_CALL',LLAMADASTOTAL.OBSERVACION AS 'OBSERVACION_CALL', 
                                                    VISITASTOTAL.CARGA AS 'TIPO_CONTACTO_CAMPO',VISITASTOTAL.ESTADO_VISITA AS 'ESTADO_CONTACTO_CAMPO', VISITASTOTAL.FECHA_CPG AS 'FECHA_COMPROMISO_CAMPO',VISITASTOTAL.OBSERVACION AS 'OBSERVACION_CAMPO',
                                                    CASE 
                                                            WHEN ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO>VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NULL )  THEN LLAMADASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO>LLAMADASTOTAL.FECHA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NOT NULL )  THEN VISITASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            ELSE null
                                                    END AS 'MOTIVO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA>VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NULL )  THEN LLAMADASTOTAL.NOMBRE_UL_TIPO_COBRANZA
                                                        WHEN ( VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO>LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NOT NULL )  THEN VISITASTOTAL.TIPO_CONTACTO
                                                        ELSE null
                                                    END AS 'TIPO_DE_COBRANZA',
                                            -- LLAMADASTOTAL.peso AS 'PESO1',
                                            -- VISITASTOTAL.peso AS 'PESO2',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN 'CALL'
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN 'CAMPO'
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_GESTION',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.TIPO_CONTACTO
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.CARGA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_TIPO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.ESTADO_LLAMADA
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.ESTADO_VISITA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_ESTADO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.FECHA_CP 
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.FECHA_CPG
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_FECHA_COMPROMISO'



                                                    FROM TMP_FOTOCARTERAVISITA_PROVISION_TOTAL VISITASTOTAL 
                                                    RIGHT JOIN TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";

                        $prReporte=$connection->prepare($sqlReporte);
                        if($prReporte->execute()){
                            $contenidoReporte= $prReporte->fetchAll(PDO::FETCH_ASSOC);
                            /*Colores Cabecera por fotocartera-llamada-visita-mejorvisitallamada,historialdeprovision-provision*/
                            $colorFotoCartera = "background:#C00000;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorCallCampo = "background:#963634;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorMejorGestion = "background : #00B0F0 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorHistorial = "background : #FFFF00 ;color:black;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvision = "background : #632523 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvisionMASMENOS = "background : #000000 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $contenidoCuerpoFotoCartera = "height:20px;font-size:11px;border: 1px solid #000000";
                            /*CABECERA*/
                            $cabecera = "<table><tr><td style=\"".$colorFotoCartera."\">CARTERA</td><td style=\"".$colorFotoCartera."\">NOM_SUBPROD</td><td style=\"".$colorFotoCartera."\">TERRITORIO</td><td style=\"".$colorFotoCartera."\">OFICINA2</td><td style=\"".$colorFotoCartera."\">CONTRATO</td><td style=\"".$colorFotoCartera."\">CODCENT</td><td style=\"".$colorFotoCartera."\">NOMBRE</td><td style=\"".$colorFotoCartera."\">DEUDA</td><td style=\"".$colorFotoCartera."\">DEUDA_TOTAL</td><td style=\"".$colorFotoCartera."\">DIRECCION</td><td style=\"".$colorFotoCartera."\">DIAVENC</td><td style=\"".$colorFotoCartera."\">DIST_PROV</td><td style=\"".$colorFotoCartera."\">MARCA</td><td style=\"".$colorFotoCartera."\">PRODUCTO</td><td style=\"".$colorFotoCartera."\">CODIGO_GESTOR</td><td style=\"".$colorFotoCartera."\">GESTOR_ASIGNADO</td><td style=\"".$colorFotoCartera."\">TRAMO_DIA_HDEC</td><td style=\"".$colorFotoCartera."\">FLAG_CUENTA</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CALL</td><td style=\"".$colorCallCampo."\">OBSERVACION_CALL</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CAMPO</td><td style=\"".$colorCallCampo."\">OBSERVACION_CAMPO</td><td style=\"".$colorCallCampo."\">MOTIVO</td><td style=\"".$colorCallCampo."\">TIPO_DE_COBRANZA</td><td style=\"".$colorMejorGestion."\">MEJOR_GESTION</td><td style=\"".$colorMejorGestion."\">MEJOR_TIPO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_ESTADO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_FECHA_COMPROMISO</td><td style=\"".$colorHistorial."\">FECHA_PROVISION</td><td style=\"".$colorHistorial."\">FECHA_INGRESO</td><td style=\"".$colorHistorial.";text-align:center"."\">STATUS</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO</td><td style=\"".$colorProvision."\">VARIACION_TOTAL</td><td style=\"".$colorProvision."\">VARIACION_HIPOTECARIO</td><td style=\"".$colorProvisionMASMENOS."\">MAYOR_PROVISION_3MESESATRAS</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_TOTAL_+-</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_HIPOTECARIO_+-</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO_+-</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO_+-</td><td style=\"".$colorHistorial."\">FECHA_PROVISION_TOTAL</td><td style=\"".$colorHistorial."\">FECHA_INGRESO_TOTAL</td></tr></table>";   
                            /*CUERPO*/
                            $cuerpo ="<table>";
                            for($i=0;$i<count($contenidoReporte);$i++){
                                if($contenidoReporte[$i]['FECHA_PROVISION_TOTAL']==""){
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION'];
                                }else{
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION_TOTAL'] ;
                                }

                                if($contenidoReporte[$i]['FECHA_INGRESO_TOTAL']==""){
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO'];
                                }else{
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO_TOTAL'];
                                }

                                if($contenidoReporte[$i]['TIPO_CONTACTO_CALL']==""){
                                    $mejorGestion = "CALL";
                                    $mejorTipoContacto = "NOC";
                                    $mejorEstadoContacto = "NO CONTESTA";
                                    $tipoContactoCall = "NOC";
                                    $estadoContactoCall = "NO CONTESTA";
                                    $observacionCall = "NO CONTESTA";
                                    $motivo = "NO HAY MOTIVO DE NO PAGO";
                                }else{
                                    $mejorGestion = $contenidoReporte[$i]['MEJOR_GESTION'];
                                    $mejorTipoContacto = $contenidoReporte[$i]['MEJOR_TIPO_CONTACTO'];
                                    $mejorEstadoContacto = $contenidoReporte[$i]['MEJOR_ESTADO_CONTACTO'];
                                    $tipoContactoCall = $contenidoReporte[$i]['TIPO_CONTACTO_CALL'];
                                    $estadoContactoCall = $contenidoReporte[$i]['ESTADO_CONTACTO_CALL'];
                                    $observacionCall = $contenidoReporte[$i]['OBSERVACION_CALL'];
                                    $motivo = $contenidoReporte[$i]['MOTIVO'];
                                }

                                if($contenidoReporte[$i]['FLAG_CUENTA']==0 || $contenidoReporte[$i]['isRetirado']==1){
                                    $variacionProvision="";
                                    $variacionHipotecario="";
                                    $variacionProvisionTotal="";
                                    $variacionHipotecarioTotal="";
                                    $flagCuenta = "0";
                                }else{
                                    $variacionProvision=$contenidoReporte[$i]['VARIACION_PROVISION'];
                                    $variacionHipotecario=$contenidoReporte[$i]['VARIACION_HIPOTECARIO'];
                                    $variacionProvisionTotal=$contenidoReporte[$i]['VARIACION_PROVISION_TOTAL'];
                                    $variacionHipotecarioTotal=$contenidoReporte[$i]['VARIACION_HIPOTECARIO_TOTAL'];
                                    $flagCuenta=$contenidoReporte[$i]['FLAG_CUENTA'];
                                }
                                $cuerpo.="  <tr>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CARTERA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['NOM_SUBPROD']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TERRITORIO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OFICINA2']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CONTRATO']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CODCENT']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['NOMBRE'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TOTALDEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIRECCION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIAVENC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIST_PROV']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MARCA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['PRODUCTO'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CODIGO_GESTOR']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['GESTOR_ASIGNADO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TRAMO_DIA_HDEC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$flagCuenta."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$tipoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$estadoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CALL']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$observacionCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['ESTADO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OBSERVACION_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$motivo."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_DE_COBRANZA']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorGestion."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorTipoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorEstadoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MEJOR_FECHA_COMPROMISO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_PROVISION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_INGRESO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['STATUS']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvision."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecario."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MAXPROVI3MESES']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecarioTotal."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaIngresoTotal."</td>
                                            </tr>";
                            }
                            $cuerpo.="</table>";
                            

                            /*VOLAR TMPS*/

                            //tmp1
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERAVISITA_PROVISION_TOTAL ";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            //tmp2
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            echo $cabecera;
                            echo $cuerpo;
                        }else{}
                        }else{
                        }
                    }else{
                    }
                }else{
                }
        }else if($countClientesbyVisitas!=0 && $countClientesbyLlamadas==0)/*3ER*/{
            $sqlGetDtistinctCodClienteByVisitas = "SELECT DISTINCT a.CODIGO_CLIENTE AS 'CODIGO_CLIENTE' from(
                    select * from (
                        select * from (
                            SELECT 
                           -- vis.idvisita AS 'CODVISITA',
                            vis.idcliente_cartera AS 'idcliente_cartera_visita',
                           -- car.nombre_cartera AS 'GESTION',
                           -- car.fecha_inicio AS 'FECHA_INICIO',
                           -- car.fecha_fin AS 'FECHA_FIN',
                            (SELECT fin.idfinal from ca_final fin where fin.idfinal=vis.idfinal)AS 'idfinal',
                            (SELECT finser.peso from ca_final_servicio finser inner join ca_final fin on fin.idfinal=finser.idfinal where fin.idfinal= vis.idfinal) AS 'peso',
                           -- vis.idcuenta AS 'idcuenta',
                         -- CONCAT('=\"',clicar.codigo_cliente,'\"') AS 'CODIGO_CLIENTE' ,
                            clicar.codigo_cliente AS 'CODIGO_CLIENTE'
                          FROM ca_cartera car 
                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                            INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                            INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta

                            WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                            AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                        ) AS f ORDER BY f.idfinal,f.peso,f.codigo_cliente desc 
                    )AS a GROUP BY a.idcliente_cartera_visita 
                )AS a";
        
                $prGetDtistinctCodClienteByVisitas= $connection->prepare($sqlGetDtistinctCodClienteByVisitas);
                $prGetDtistinctCodClienteByVisitas->execute();
                $CodigoClientesByVisitas = $prGetDtistinctCodClienteByVisitas->fetchAll(PDO::FETCH_ASSOC);
                $arrayCodigoClienteVisita= array();
                for($i=0;$i<count($CodigoClientesByVisitas);$i++){
                    array_push($arrayCodigoClienteVisita,$CodigoClientesByVisitas[$i]['CODIGO_CLIENTE']); 
                }
                $CodigoClientesByVisitasFinal = implode($arrayCodigoClienteVisita, ',');

                /*adicional fotocarteca
                     , cli.nombre AS Nombre,cli.numero_documento AS nro_doc,
                            /*cuen.numero_cuenta AS contrato ,-- cuen.moneda AS divisa,cuen.total_deuda AS saldohoy,cuen.producto AS producto,cuen.tramo_cuenta AS tramo_dia
                            cuen_deta.codigo_operacion AS contrato2,cuen_deta.dias_mora AS diavenc,
                            cli_car.dato1 AS agencia,cli_car.dato2 AS Gestor,cli_car.dato4 AS dist_prov,cuen.dato2 AS subprod,cuen.dato3 AS nom_subprod,cuen.dato8 AS marca,cuen.dato9 AS territorio,cuen.dato10 AS oficina,cuen.dato11 AS oficina2,
                            cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
                            (SELECT pri.nombre AS idGestor FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_privilegio pri ON us.idprivilegio=pri.idprivilegio WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS idGestor,
                          (SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS asignado FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS asignado
                            
                           ,(SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio) AS codigo_user
                          
            
                */
               /*adicional a
                    -- vis.idvisita AS 'CODVISITA',
                    -- car.nombre_cartera AS 'GESTION',
                    --  vis.idcuenta AS 'idcuenta',
                    --  (SELECT CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) FROM ca_cliente cli where cli.idcliente=clicar.idcliente AND cli.idservicio = 6) AS 'NOMBRE_CLIENTE',
                     --  case cu.moneda when 'USD' then ".$tipoCambio."*cu.total_deuda when 'VAC' then ".$VAC."*cu.total_deuda else cu.total_deuda end AS 'DEUDA',                          
                    --  DATE(vis.fecha_visita) AS 'FECHA_VISITA',
                    --  TRUNCATE(vis.monto_cp,2) AS MONTO_CP,                                       
                    --  ( SELECT nombre FROM ca_parentesco WHERE idparentesco = vis.idparentesco ) AS PARENTESCO,
                    --  ( SELECT nombre FROM ca_contacto WHERE idcontacto = vis.idcontacto ) AS CONTACTO,
                    --   vis.nombre_contacto AS NOMBRE_CONTACTO,
                    --   ( SELECT nombre FROM ca_motivo_no_pago WHERE idmotivo_no_pago = vis.idmotivo_no_pago ) AS MOTIVO_NO_PAGO,
                    --   ( SELECT direccion FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DIRECCION',
                    --   ( SELECT distrito FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DISTRITO',
                    --   ( SELECT provincia FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'PROVINCIA',
                    --   ( SELECT departamento FROM ca_direccion WHERE iddireccion = vis.iddireccion ) AS 'DEPARTAMENTO',
                    --   ( SELECT finser.prioridad FROM ca_final fin INNER JOIN ca_final_servicio finser ON fin.idfinal=finser.idfinal WHERE finser.idservicio=6 and fin.idfinal=vis.idfinal and finser.estado=1) AS 'PRIORIDAD',
                    --   ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = vis.idnotificador  LIMIT 1 ) AS 'GESTOR_CAMPO',
                                     
               */
                $sqlLlenarTemporaryVisita = "INSERT TMP_FOTOCARTERAVISITA_PROVISION_TOTAL (
                                            SELECT * FROM ( 
                            SELECT
                            cli_car.idcliente_cartera AS 'idcliente_cartera',cli.codigo AS codcent  
                            FROM ca_cliente cli
                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                            WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo DESC 
                    ) AS FOTOCARTERA   

                    INNER JOIN( 
                        select * from(
                            select * from (
                                select * from (
                                    SELECT 
                                    vis.idcliente_cartera AS 'idcliente_cartera_visita',
                                    (SELECT fin.idfinal from ca_final fin where fin.idfinal=vis.idfinal)AS 'idfinal',
                                    (SELECT finser.peso from ca_final_servicio finser inner join ca_final fin on fin.idfinal=finser.idfinal where fin.idfinal= vis.idfinal) AS 'peso',
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                    vis.fecha_cp AS 'FECHA_CPG',
                                    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal) as 'CARGA',
                                    ( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO_VISITA',
                                 replace(replace(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION'
                                    FROM ca_cartera car 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                    INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                                    INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                                    WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                                    AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                                ) AS f ORDER BY f.idfinal,f.peso,f.codigo_cliente desc 
                            )AS a GROUP BY a.idcliente_cartera_visita 
                        )AS a

                        LEFT JOIN (
                                select * from (
                                select vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                vis.fecha_visita AS 'FECHA_VISITA_UL_MOTNOPAGO',
                                clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = vis.idmotivo_no_pago) AS 'UL_MOTIVO_NO_PAGO' 
                                from    ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                inner join ca_cartera car on car.idcartera = clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania
                                where vis.idmotivo_no_pago is not null and cam.idservicio = 6 and car.estado=1 and cli.idservicio=6 and  ( MONTH(vis.fecha_visita) > (MONTH(now())-2) ) and cli.codigo 
                                    in (".$CodigoClientesByVisitasFinal.") and ( DATE(vis.fecha_visita) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ) ORDER BY vis.fecha_visita,clicar.codigo_cliente desc 
                                ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO  
                        ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO=a.idcliente_cartera_visita

                        LEFT JOIN( 
                            select * from (
                                select 
                               ( select  par.nombre  from ca_parentesco par where par.idparentesco = vis.idparentesco )AS 'TIPO_CONTACTO', vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_TIPO_CONTACTO', clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCONTACTO', vis.fecha_visita AS 'FECHA_VISITA_UL_TIPOCONTACTO' 
                                from ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera  clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cartera car on car.idcartera= clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania  -- 643
                                where car.estado = 1 and cam.idservicio = 6 and car.idcartera IN (".$carteras.") and vis.idparentesco is not null order by vis.fecha_visita,clicar.idcliente_cartera DESC
                            ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO 
                        )AS c ON c.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO =a.idcliente_cartera_visita

                    ) AS VISITAS ON VISITAS.idcliente_cartera_visita=FOTOCARTERA.idcliente_cartera
                )";

                $prLlenarTemporaryVisita = $connection->prepare($sqlLlenarTemporaryVisita);   
                if($prLlenarTemporaryVisita->execute()){
                    
                    /*
                        --   DAYOFWEEK( lla.fecha ),
                        --    CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                        --         WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                        --   ELSE 'B'
                        --  END AS 'FRECUENCIA',    
                        --  lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal) AS 'peso',
                        --  cu.estado AS 'FLAG_CUENTA',
                        --  car.nombre_cartera AS 'NOMBRE_GESTION',
                        --  cu.numero_cuenta AS 'NUMERO_CUENTA',
                        --  clicar.dato1 AS 'CARTERA',
                        --  clicar.idcliente_cartera AS 'DATA',
                        --  CONCAT_WS(' ',TRIM(cli.nombre),TRIM(cli.paterno),TRIM(cli.materno)) AS 'CLIENTE',
                        --  cli.numero_documento AS 'NUMERO_DOCUMENTO',
                        --  car.fecha_inicio as 'FECHA_INICIO',
                        --  car.fecha_fin as 'FECHA_FIN',
                        --  DATE(lla.fecha) AS 'FECHA_LLAMADA',
                        --  CASE WHEN DAYOFWEEK(DATE(lla.fecha)) = 7 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) - 1) WHEN DAYOFWEEK(DATE(lla.fecha)) = 1 THEN FROM_DAYS(TO_DAYS(DATE(lla.fecha)) + 1)  ELSE DATE(lla.fecha) END AS 'FECHA_SIG_LLAMADA',
                        --  CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal)  END    AS 'TIPO_CONTACTO',
                        -- replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                        -- DATE(lla.fecha_cp) AS 'FECHA_CP',
                        -- TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                        -- ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = lla.idusuario_servicio  LIMIT 1 ) AS 'TELEOPERADOR',
                        -- ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio  LIMIT 1 ) AS 'ASIGNADO_LLAMADA',
                        -- ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'CONTACTO',
                        -- lla.nombre_contacto AS 'NOMBRE_CONTACTO',
                        -- ( SELECT nombre FROM ca_parentesco WHERE idparentesco = lla.idparentesco ) AS 'PARENTESCO' ,
                        -- (SELECT mnp.nombre from ca_motivo_no_pago mnp where mnp.idmotivo_no_pago=lla.idmotivo_no_pago) AS 'MOTIVO_NO_PAGO',
                                                    
                    */
                    /*UN COUNT ANTES PORSEACASO NO HALLAN CODIGOS*/
                    


                    $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                    $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                    $prEliminarTMP->execute();
                    

                    $sqlCreateTemporaryFotocarteraLlamadas = "CREATE TEMPORARY TABLE TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL 
                                            (
                                                idcliente_cartera_fotocartera int(11),
                                                codcent varchar(50),
                                                Nombre varchar(150),
                                                contrato varchar(50),
                                                saldohoy varchar(20),
                                                producto varchar(30),
                                                tramo_dia varchar(30),
                                                diavenc varchar(25),
                                                agencia varchar(50),
                                                dist_prov varchar(30),
                                                nom_subprod varchar(30),
                                                marca varchar(30),
                                                territorio varchar(30),
                                                oficina2 varchar(30),
                                                statusCLIENTE TINYINT(4),
                                                statusCUENTA TINYINT(4),
                                                asignadoFOTOCARTERA varchar(65),
                                                direccion_domicilio varchar(100),
                                                codigo_user  varchar(30),
                                                codigo varchar(20),
                                                total_deuda  varchar(20),
                                                CONDICION varchar(10),
                                                FECUENCIA varchar(10),
                                                idfinal INT(11),
                                                peso INT(11),
                                                DATA varchar(30),
                                                CODIGO_CLIENTE varchar(30),
                                                idcliente_cartera_llamada int(11),
                                                TIPO_CONTACTO varchar(20),
                                                OBSERVACION  varchar(200),
                                                FECHA_CP date,
                                                MONTO_CP varchar(20),
                                                CONTACTO varchar(50),
                                                ESTADO_LLAMADA varchar(50),
                                                prioridad int(11),
                                                IDCLIENTE_CARTERA_UL_MOTNOPAGO int(11),
                                                CODIGO_CLIENTE_UL_MOTNOPAGO varchar(50),
                                                FECHA_UL_MOTNOPAGO date,
                                                UL_MOTIVO_NO_PAGO  varchar(100),
                                                IDCLIENTE_CARTE_UL_TIPOCOBRANZA int(11),
                                                CODIGO_CLIENTE_UL_TIPOCOBRANZA  varchar(50),
                                                NOMBRE_UL_TIPO_COBRANZA  varchar(50),
                                                FECHA_UL_TIPO_COBRANZA date,
                                                INDEX( idcliente_cartera_fotocartera ),
                                                INDEX( codcent ),
                                                INDEX( statusCLIENTE )
                                                    )";
                
                    $prCreateTemporaryFotocarteraLlamadas=$connection->prepare($sqlCreateTemporaryFotocarteraLlamadas);
                    if( $prCreateTemporaryFotocarteraLlamadas->execute() ){
                        
                        $sqlLlenarTemporaryLlamada = "INSERT TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL (
                                                        SELECT * FROM (
                                                                select * from   (
                                                                        SELECT
                                                                        cli_car.idcliente_cartera AS 'idcliente_cartera_fotocartera',cli.codigo AS codcent,cli.nombre AS Nombre,
                                                                        cuen.numero_cuenta AS contrato,if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") ) AS saldohoy,cuen.producto AS producto,CASE 
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                                                                            ELSE 'NO_TRAMO'
                                                                        END AS tramo_dia,
                                                                        cuen_deta.dias_mora AS diavenc,
                                                                        cli_car.dato1 AS agencia,cli_car.dato4 AS dist_prov,cuen.dato3 AS nom_subprod,cuen.dato8 AS marca,cuen.dato9 AS territorio,cuen.dato11 AS oficina2,
                                                                        cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
                                                                        (SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS 'asignadoFOTOCARTERA' FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS asignadoFOTOCARTERA,
                                                                        IFNULL((SELECT CONCAT_WS('  ',IFNULL(dir.direccion,''),IFNULL(dir.departamento,''),IFNULL(dir.provincia,''),IFNULL(dir.distrito,''))
                                                                            FROM ca_direccion dir
                                                                            WHERE dir.idtipo_referencia=2
                                                                                AND dir.idcliente_cartera=cli_car.idcliente_cartera AND dir.idcuenta=cuen.idcuenta
                                                                                AND ISNULL(dir.direccion)!=1 LIMIT 1
                                                                        ),'^^^^^^') AS direccion_domicilio,
                                                                        (SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio) AS codigo_user
                                                                        
                                                                        FROM ca_cliente cli
                                                                        INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                        INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                        INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                        WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo desc
                                                                )AS  b

                                                                LEFT JOIN (
                                                                    select c.codigo AS 'codigo',sum(deuda)AS 'total_deuda' FROM (
                                                                            
                                                                            SELECT  if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") ) AS 'deuda' ,
                                                                            cli.codigo AS 'codigo'
                                                                            FROM ca_cliente cli
                                                                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                            WHERE cuen.idcartera IN (".$carteras.") and cuen.retirado!=1 ORDER BY cli.codigo DESC

                                                                    ) as c GROUP BY c.codigo

                                                                )AS d ON d.codigo=b.codcent
                                                            ) AS FOTOCARTERA 

                                                            LEFT JOIN (

                                                                    select  *  FROM (  
                                                                        SELECT * FROM (
                                                                            
                                                                                    
                                                                                                SELECT
                                                                                               
                                                                                        CASE  WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK(  NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 3 DAY ) )   THEN '1'
                                                                                                WHEN ( DAYOFWEEK(  NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 4 DAY ) )  THEN '1'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 6 DAY ) ) THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 8 DAY ) )  THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 9 DAY ) )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3    and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3   or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 16 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 15 DAY ) ) THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 20 DAY ) )  THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 18 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 21 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 28 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 32 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 27 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 36 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 30 DAY ) )  THEN '10'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 40 DAY ) ) THEN '10'
                                                                                            ELSE 'A'
                                                                                        END AS 'CONDICION',
                                                                                        CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                                                                                            ELSE 'B'
                                                                                        END AS 'FRECUENCIA',    
                                                                                            lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal) AS 'peso',
                                                                                           
                                                                                            clicar.idcliente_cartera AS 'DATA',
                                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                                                          
                                                                                            lla.idcliente_cartera AS 'idcliente_cartera_llamada',
                                                                                         CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal)  END    AS 'TIPO_CONTACTO',
                                                                                            replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                                                                                            DATE(lla.fecha_cp) AS 'FECHA_CP',
                                                                                            TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                                                                                        ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'CONTACTO',
                                                                                          ( SELECT fin.nombre from ca_final fin where fin.idfinal=lla.idfinal ) AS 'ESTADO_LLAMADA',
                                                                                            (select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=6) as 'prioridad'
                                                                                            FROM ca_cartera car 
                                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                                            
                                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  -- 2600
                                                                                            AND cli.idservicio = 6
                                                                                
                                                                                            AND car.idcartera IN (".$carteras.")   ORDER BY  clicar.CODIGO_CLIENTE,CONDICION,lla.fecha,prioridad ASC  
                                                                                            
                                                                                   
                                                                              
                                                                        ) AS c GROUP BY  c.idcliente_cartera_llamada
                                                                    ) AS d  

                                                                    LEFT JOIN (
                                                                        select * from ( -- 270
                                                                            select lla.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                                                            lla.fecha AS 'FECHA_UL_MOTNOPAGO',
                                                                            (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = lla.idmotivo_no_pago ) AS 'UL_MOTIVO_NO_PAGO' 
                                                                            from ca_llamada lla 
                                                                            inner join ca_cuenta cu on cu.idcuenta = lla.idcuenta
                                                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = lla.idcliente_cartera 
                                                                            inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                                                            inner join ca_cartera car on car.idcartera = clicar.idcartera
                                                                            inner join ca_campania cam on cam.idcampania = car.idcampania
                                                                            where  car.estado = 1 and ( MONTH(lla.fecha) > (MONTH(now())-2) ) and cam.idservicio = 6 and cli.idservicio=6   and lla.idmotivo_no_pago is not null  and  cli.codigo in( 0 ) and DATE(lla.fecha) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ORDER BY clicar.codigo_cliente,lla.fecha DESC 
                                                                        ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO
                                                                    ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO = d.idcliente_cartera_llamada

                                                                    LEFT JOIN ( 
                                                                            select * from (  -- 67
                                                                            select lla.idcliente_cartera as 'IDCLIENTE_CARTERA_UL_TIPOCOBRANZA',clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCOBRANZA', (select par.nombre from ca_parentesco par where par.idparentesco=lla.idparentesco ) AS 'NOMBRE_UL_TIPO_COBRANZA', lla.fecha AS 'FECHA_UL_TIPO_COBRANZA' 
                                                                            FROM ca_cartera car 
                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  
                                                                            AND cli.idservicio = 6
                                                                            AND car.idcartera IN (".$carteras.")
                                                                            AND lla.idparentesco is not null
                                                                            order by lla.fecha,clicar.codigo_cliente DESC
                                                                        ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA 
                                                                    )   AS e ON e.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA = d.idcliente_cartera_llamada 

                                                                ) AS h ON FOTOCARTERA.idcliente_cartera_fotocartera = h.idcliente_cartera_llamada 
                                                            )";

                        $prLlenarTemporaryLlamada = $connection->prepare($sqlLlenarTemporaryLlamada);
                        if( $prLlenarTemporaryLlamada->execute() ){
                            //probando
                           /* $sqlPrueba= "SELECT * from TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                            $prPrueba = $connection->prepare($sqlPrueba);
                            $prPrueba->execute();
                            $data=$prPrueba->fetchAll(PDO::FETCH_ASSOC);
                            $a="<table>";
                            $style="width:250px;vertical-align:middle;text-align:center";

                            for($i=0;$i<count($data);$i++){
                             $a.="<tr><td>=\"".$data[$i]['codcent']."\"</td></tr>";
                             
                            }
                           
                            $a.="</table>";
                            echo $a;
                            exit();*/
                            //probando


                            /* ultima prov cargada */
                            $getUltimaProvCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 ";
                            $prGetUltimaProvCargada = $connection->prepare($getUltimaProvCargada);
                            $prGetUltimaProvCargada->execute();
                            $ultimaProvisionCargada=$prGetUltimaProvCargada->fetchAll(PDO::FETCH_ASSOC);
                            $nameUltProvisionCargada= $ultimaProvisionCargada[0]['nombre_tabla'];


                            $sqlReporte = "SELECT (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL',
                                                       (SELECT A.fecha_provision FROM ( SELECT hisprotot.fecha_provision,hisprotot.codcen FROM ca_historico_provision_total hisprotot ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION',
                                                       (SELECT A.fecha_provision_total FROM ( SELECT hisprotot.fecha_provision_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_provision_total  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION_TOTAL',
                                                       (SELECT A.fecha_ingreso FROM ( SELECT hisprotot.fecha_ingreso,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO',
                                                       (SELECT A.fecha_ingreso_total FROM ( SELECT hisprotot.fecha_ingreso_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso_total  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO_TOTAL',
                                                       (SELECT A.status FROM ( SELECT hisprotot.status,hisprotot.codcen FROM ca_historico_provision_total hisprotot  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'STATUS',
                                                       (SELECT A.provisionPositiva FROM ( SELECT hisprotot.provisionPositiva,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisionPositiva  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO',
                                                       (SELECT A.hipotecarioPositivo FROM ( SELECT hisprotot.hipotecarioPositivo,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPositivo IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO',
                                                       (SELECT A.provisitonPosiNega FROM ( SELECT hisprotot.provisitonPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisitonPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO_+-',
                                                       (SELECT A.hipotecarioPosiNega FROM ( SELECT hisprotot.hipotecarioPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO_+-',
                                                       (SELECT A.isRetirado FROM ( select a.isRetirado,a.codcen FROM (
                                                                                                                       SELECT hisprotot.isRetirado,hisprotot.codcen,hisprotot.idhistorial_tabla_provision_total FROM ca_historico_provision_total hisprotot WHERE hisprotot.isRetirado IS NOT NULL ORDER BY hisprotot.date_provision DESC )as a INNER JOIN (
                                                                                                                      SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 )as b ON a.idhistorial_tabla_provision_total=b.idhistorial_tabla_provision_total
                                                                                                                      )AS A 
                                                                                                                WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'isRetirado',

                                                        (SELECT max(hisprotot.provisitonPosiNega) FROM ca_historico_provision_total hisprotot where hisprotot.codcen = LLAMADASTOTAL.codcent AND hisprotot.date_provision>date_sub(hisprotot.date_provision,INTERVAL 2 MONTH))AS MAXPROVI3MESES,
                                           LLAMADASTOTAL.agencia AS 'CARTERA',LLAMADASTOTAL.nom_subprod AS 'NOM_SUBPROD',LLAMADASTOTAL.territorio AS 'TERRITORIO',LLAMADASTOTAL.oficina2 AS 'OFICINA2',LLAMADASTOTAL.contrato AS 'CONTRATO',LLAMADASTOTAL.codcent AS 'CODCENT',LLAMADASTOTAL.nombre AS 'NOMBRE',LLAMADASTOTAL.saldohoy AS 'DEUDA',LLAMADASTOTAL.total_deuda AS 'TOTALDEUDA',LLAMADASTOTAL.direccion_domicilio AS 'DIRECCION',LLAMADASTOTAL.diavenc AS 'DIAVENC',LLAMADASTOTAL.dist_prov AS 'DIST_PROV',LLAMADASTOTAL.marca AS 'MARCA',LLAMADASTOTAL.producto AS 'PRODUCTO',LLAMADASTOTAL.codigo_user AS 'CODIGO_GESTOR',LLAMADASTOTAL.asignadoFOTOCARTERA AS 'GESTOR_ASIGNADO',LLAMADASTOTAL.tramo_dia AS 'TRAMO_DIA_HDEC',LLAMADASTOTAL.statusCUENTA AS 'FLAG_CUENTA',
                                                    LLAMADASTOTAL.TIPO_CONTACTO AS 'TIPO_CONTACTO_CALL',LLAMADASTOTAL.ESTADO_LLAMADA AS 'ESTADO_CONTACTO_CALL',LLAMADASTOTAL.FECHA_CP AS 'FECHA_COMPROMISO_CALL',LLAMADASTOTAL.OBSERVACION AS 'OBSERVACION_CALL', 
                                                    VISITASTOTAL.CARGA AS 'TIPO_CONTACTO_CAMPO',VISITASTOTAL.ESTADO_VISITA AS 'ESTADO_CONTACTO_CAMPO', VISITASTOTAL.FECHA_CPG AS 'FECHA_COMPROMISO_CAMPO',VISITASTOTAL.OBSERVACION AS 'OBSERVACION_CAMPO',
                                                    CASE 
                                                            WHEN ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO>VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NULL )  THEN LLAMADASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO>LLAMADASTOTAL.FECHA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NOT NULL )  THEN VISITASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            ELSE null
                                                    END AS 'MOTIVO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA>VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NULL )  THEN LLAMADASTOTAL.NOMBRE_UL_TIPO_COBRANZA
                                                        WHEN ( VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO>LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NOT NULL )  THEN VISITASTOTAL.TIPO_CONTACTO
                                                        ELSE null
                                                    END AS 'TIPO_DE_COBRANZA',
                                            -- LLAMADASTOTAL.peso AS 'PESO1',
                                            -- VISITASTOTAL.peso AS 'PESO2',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN 'CALL'
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN 'CAMPO'
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_GESTION',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.TIPO_CONTACTO
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.CARGA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_TIPO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.ESTADO_LLAMADA
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.ESTADO_VISITA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_ESTADO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.FECHA_CP 
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.FECHA_CPG
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_FECHA_COMPROMISO'



                                                    FROM TMP_FOTOCARTERAVISITA_PROVISION_TOTAL VISITASTOTAL 
                                                    RIGHT JOIN TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";

                        $prReporte=$connection->prepare($sqlReporte);
                        if($prReporte->execute()){
                            $contenidoReporte= $prReporte->fetchAll(PDO::FETCH_ASSOC);
                            /*Colores Cabecera por fotocartera-llamada-visita-mejorvisitallamada,historialdeprovision-provision*/
                            $colorFotoCartera = "background:#C00000;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorCallCampo = "background:#963634;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorMejorGestion = "background : #00B0F0 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorHistorial = "background : #FFFF00 ;color:black;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvision = "background : #632523 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvisionMASMENOS = "background : #000000 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $contenidoCuerpoFotoCartera = "height:20px;font-size:11px;border: 1px solid #000000";
                            /*CABECERA*/
                            $cabecera = "<table><tr><td style=\"".$colorFotoCartera."\">CARTERA</td><td style=\"".$colorFotoCartera."\">NOM_SUBPROD</td><td style=\"".$colorFotoCartera."\">TERRITORIO</td><td style=\"".$colorFotoCartera."\">OFICINA2</td><td style=\"".$colorFotoCartera."\">CONTRATO</td><td style=\"".$colorFotoCartera."\">CODCENT</td><td style=\"".$colorFotoCartera."\">NOMBRE</td><td style=\"".$colorFotoCartera."\">DEUDA</td><td style=\"".$colorFotoCartera."\">DEUDA_TOTAL</td><td style=\"".$colorFotoCartera."\">DIRECCION</td><td style=\"".$colorFotoCartera."\">DIAVENC</td><td style=\"".$colorFotoCartera."\">DIST_PROV</td><td style=\"".$colorFotoCartera."\">MARCA</td><td style=\"".$colorFotoCartera."\">PRODUCTO</td><td style=\"".$colorFotoCartera."\">CODIGO_GESTOR</td><td style=\"".$colorFotoCartera."\">GESTOR_ASIGNADO</td><td style=\"".$colorFotoCartera."\">TRAMO_DIA_HDEC</td><td style=\"".$colorFotoCartera."\">FLAG_CUENTA</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CALL</td><td style=\"".$colorCallCampo."\">OBSERVACION_CALL</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CAMPO</td><td style=\"".$colorCallCampo."\">OBSERVACION_CAMPO</td><td style=\"".$colorCallCampo."\">MOTIVO</td><td style=\"".$colorCallCampo."\">TIPO_DE_COBRANZA</td><td style=\"".$colorMejorGestion."\">MEJOR_GESTION</td><td style=\"".$colorMejorGestion."\">MEJOR_TIPO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_ESTADO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_FECHA_COMPROMISO</td><td style=\"".$colorHistorial."\">FECHA_PROVISION</td><td style=\"".$colorHistorial."\">FECHA_INGRESO</td><td style=\"".$colorHistorial.";text-align:center"."\">STATUS</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO</td><td style=\"".$colorProvision."\">VARIACION_TOTAL</td><td style=\"".$colorProvision."\">VARIACION_HIPOTECARIO</td><td style=\"".$colorProvisionMASMENOS."\">MAYOR_PROVISION_3MESESATRAS</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_TOTAL_+-</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_HIPOTECARIO_+-</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO_+-</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO_+-</td><td style=\"".$colorHistorial."\">FECHA_PROVISION_TOTAL</td><td style=\"".$colorHistorial."\">FECHA_INGRESO_TOTAL</td></tr></table>";   
                            /*CUERPO*/
                            $cuerpo ="<table>";
                            for($i=0;$i<count($contenidoReporte);$i++){
                                if($contenidoReporte[$i]['FECHA_PROVISION_TOTAL']==""){
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION'];
                                }else{
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION_TOTAL'] ;
                                }

                                if($contenidoReporte[$i]['FECHA_INGRESO_TOTAL']==""){
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO'];
                                }else{
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO_TOTAL'];
                                }

                                if($contenidoReporte[$i]['TIPO_CONTACTO_CALL']==""){
                                    $mejorGestion = "CALL";
                                    $mejorTipoContacto = "NOC";
                                    $mejorEstadoContacto = "NO CONTESTA";
                                    $tipoContactoCall = "NOC";
                                    $estadoContactoCall = "NO CONTESTA";
                                    $observacionCall = "NO CONTESTA";
                                    $motivo = "NO HAY MOTIVO DE NO PAGO";
                                }else{
                                    $mejorGestion = $contenidoReporte[$i]['MEJOR_GESTION'];
                                    $mejorTipoContacto = $contenidoReporte[$i]['MEJOR_TIPO_CONTACTO'];
                                    $mejorEstadoContacto = $contenidoReporte[$i]['MEJOR_ESTADO_CONTACTO'];
                                    $tipoContactoCall = $contenidoReporte[$i]['TIPO_CONTACTO_CALL'];
                                    $estadoContactoCall = $contenidoReporte[$i]['ESTADO_CONTACTO_CALL'];
                                    $observacionCall = $contenidoReporte[$i]['OBSERVACION_CALL'];
                                    $motivo = $contenidoReporte[$i]['MOTIVO'];
                                }

                                if($contenidoReporte[$i]['FLAG_CUENTA']==0 || $contenidoReporte[$i]['isRetirado']==1){
                                    $variacionProvision="";
                                    $variacionHipotecario="";
                                    $variacionProvisionTotal="";
                                    $variacionHipotecarioTotal="";
                                    $flagCuenta = "0";
                                }else{
                                    $variacionProvision=$contenidoReporte[$i]['VARIACION_PROVISION'];
                                    $variacionHipotecario=$contenidoReporte[$i]['VARIACION_HIPOTECARIO'];
                                    $variacionProvisionTotal=$contenidoReporte[$i]['VARIACION_PROVISION_TOTAL'];
                                    $variacionHipotecarioTotal=$contenidoReporte[$i]['VARIACION_HIPOTECARIO_TOTAL'];
                                    $flagCuenta=$contenidoReporte[$i]['FLAG_CUENTA'];
                                }
                                $cuerpo.="  <tr>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CARTERA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['NOM_SUBPROD']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TERRITORIO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OFICINA2']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CONTRATO']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CODCENT']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['NOMBRE'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TOTALDEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIRECCION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIAVENC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIST_PROV']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MARCA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['PRODUCTO'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CODIGO_GESTOR']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['GESTOR_ASIGNADO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TRAMO_DIA_HDEC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$flagCuenta."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$tipoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$estadoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CALL']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$observacionCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['ESTADO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OBSERVACION_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$motivo."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_DE_COBRANZA']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorGestion."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorTipoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorEstadoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MEJOR_FECHA_COMPROMISO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_PROVISION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_INGRESO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['STATUS']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvision."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecario."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MAXPROVI3MESES']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecarioTotal."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaIngresoTotal."</td>
                                            </tr>";
                            }
                            $cuerpo.="</table>";
                            

                            /*VOLAR TMPS*/

                            //tmp1
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERAVISITA_PROVISION_TOTAL ";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            //tmp2
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            echo $cabecera;
                            echo $cuerpo;
                        }else{}
                        }else{
                        }
                    }else{
                    }
                }else{
                }
        }else if($countClientesbyVisitas==0 && $countClientesbyLlamadas==0)/*4TO*/{

                $sqlLlenarTemporaryVisita = "INSERT TMP_FOTOCARTERAVISITA_PROVISION_TOTAL (
                                            SELECT * FROM ( 
                            SELECT
                            cli_car.idcliente_cartera AS 'idcliente_cartera',cli.codigo AS codcent  
                            FROM ca_cliente cli
                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                            WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo DESC 
                    ) AS FOTOCARTERA   

                    INNER JOIN( 
                        select * from(
                            select * from (
                                select * from (
                                    SELECT 
                                    vis.idcliente_cartera AS 'idcliente_cartera_visita',
                                    (SELECT fin.idfinal from ca_final fin where fin.idfinal=vis.idfinal)AS 'idfinal',
                                    (SELECT finser.peso from ca_final_servicio finser inner join ca_final fin on fin.idfinal=finser.idfinal where fin.idfinal= vis.idfinal) AS 'peso',
                                    clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                    vis.fecha_cp AS 'FECHA_CPG',
                                    ( SELECT carfin.nombre FROM ca_carga_final carfin inner join ca_final fin ON carfin.idcarga_final=fin.idcarga_final where fin.idfinal=vis.idfinal) as 'CARGA',
                                    ( SELECT nombre FROM ca_final WHERE idfinal = vis.idfinal ) AS 'ESTADO_VISITA',
                                 replace(replace(Replace(Replace(Replace(vis.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),'') AS 'OBSERVACION'
                                    FROM ca_cartera car 
                                    INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                    INNER JOIN ca_visita vis ON vis.idcliente_cartera=clicar.idcliente_cartera
                                    INNER JOIN ca_cuenta cu ON cu.idcuenta=vis.idcuenta
                                    WHERE clicar.idcartera IN ( ".$carteras." ) AND car.idcartera IN ( ".$carteras." ) AND cu.idcartera IN ( ".$carteras." ) and vis.estado=1
                                    AND MONTH( vis.fecha_visita ) = MONTH( now() ) and YEAR( vis.fecha_visita )=YEAR( now() ) 
                                ) AS f ORDER BY f.idfinal,f.peso,f.codigo_cliente desc 
                            )AS a GROUP BY a.idcliente_cartera_visita 
                        )AS a

                        LEFT JOIN (
                                select * from (
                                select vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                vis.fecha_visita AS 'FECHA_VISITA_UL_MOTNOPAGO',
                                clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = vis.idmotivo_no_pago) AS 'UL_MOTIVO_NO_PAGO' 
                                from    ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                inner join ca_cartera car on car.idcartera = clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania
                                where vis.idmotivo_no_pago is not null and cam.idservicio = 6 and car.estado=1 and cli.idservicio=6 and  ( MONTH(vis.fecha_visita) > (MONTH(now())-2) ) and cli.codigo 
                                    in (0) and ( DATE(vis.fecha_visita) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ) ORDER BY vis.fecha_visita,clicar.codigo_cliente desc 
                                ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO  
                        ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO=a.idcliente_cartera_visita

                        LEFT JOIN( 
                            select * from (
                                select 
                               ( select  par.nombre  from ca_parentesco par where par.idparentesco = vis.idparentesco )AS 'TIPO_CONTACTO', vis.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_TIPO_CONTACTO', clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCONTACTO', vis.fecha_visita AS 'FECHA_VISITA_UL_TIPOCONTACTO' 
                                from ca_visita vis
                                inner join ca_cuenta cu on cu.idcuenta = vis.idcuenta
                                inner join ca_cliente_cartera  clicar on clicar.idcliente_cartera = vis.idcliente_cartera
                                inner join ca_cartera car on car.idcartera= clicar.idcartera
                                inner join ca_campania cam on cam.idcampania = car.idcampania  -- 643
                                where car.estado = 1 and cam.idservicio = 6 and car.idcartera IN (".$carteras.") and vis.idparentesco is not null order by vis.fecha_visita,clicar.idcliente_cartera DESC
                            ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO 
                        )AS c ON c.IDCLIENTE_CARTERA_UL_TIPO_CONTACTO =a.idcliente_cartera_visita

                    ) AS VISITAS ON VISITAS.idcliente_cartera_visita=FOTOCARTERA.idcliente_cartera
                )";

                $prLlenarTemporaryVisita = $connection->prepare($sqlLlenarTemporaryVisita);   
                if($prLlenarTemporaryVisita->execute()){
                    $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                    $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                    $prEliminarTMP->execute();
                    
                    $sqlCreateTemporaryFotocarteraLlamadas = "CREATE TEMPORARY TABLE TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL 
                                            (
                                                idcliente_cartera_fotocartera int(11),
                                                codcent varchar(50),
                                                Nombre varchar(150),
                                                contrato varchar(50),
                                                saldohoy varchar(20),
                                                producto varchar(30),
                                                tramo_dia varchar(30),
                                                diavenc varchar(25),
                                                agencia varchar(50),
                                                dist_prov varchar(30),
                                                nom_subprod varchar(30),
                                                marca varchar(30),
                                                territorio varchar(30),
                                                oficina2 varchar(30),
                                                statusCLIENTE TINYINT(4),
                                                statusCUENTA TINYINT(4),
                                                asignadoFOTOCARTERA varchar(65),
                                                direccion_domicilio varchar(100),
                                                codigo_user  varchar(30),
                                                codigo varchar(20),
                                                total_deuda  varchar(20),
                                                CONDICION varchar(10),
                                                FECUENCIA varchar(10),
                                                idfinal INT(11),
                                                peso INT(11),
                                                DATA varchar(30),
                                                CODIGO_CLIENTE varchar(30),
                                                idcliente_cartera_llamada int(11),
                                                TIPO_CONTACTO varchar(20),
                                                OBSERVACION  varchar(200),
                                                FECHA_CP date,
                                                MONTO_CP varchar(20),
                                                CONTACTO varchar(50),
                                                ESTADO_LLAMADA varchar(50),
                                                prioridad int(11),
                                                IDCLIENTE_CARTERA_UL_MOTNOPAGO int(11),
                                                CODIGO_CLIENTE_UL_MOTNOPAGO varchar(50),
                                                FECHA_UL_MOTNOPAGO date,
                                                UL_MOTIVO_NO_PAGO  varchar(100),
                                                IDCLIENTE_CARTE_UL_TIPOCOBRANZA int(11),
                                                CODIGO_CLIENTE_UL_TIPOCOBRANZA  varchar(50),
                                                NOMBRE_UL_TIPO_COBRANZA  varchar(50),
                                                FECHA_UL_TIPO_COBRANZA date,
                                                INDEX( idcliente_cartera_fotocartera ),
                                                INDEX( codcent ),
                                                INDEX( statusCLIENTE )
                                                    )";
                
                    $prCreateTemporaryFotocarteraLlamadas=$connection->prepare($sqlCreateTemporaryFotocarteraLlamadas);
                    if( $prCreateTemporaryFotocarteraLlamadas->execute() ){
         
                        $sqlLlenarTemporaryLlamada = "INSERT TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL (
                                                        SELECT * FROM (
                                                                select * from   (
                                                                        SELECT
                                                                        cli_car.idcliente_cartera AS 'idcliente_cartera_fotocartera',cli.codigo AS codcent,cli.nombre AS Nombre,
                                                                        cuen.numero_cuenta AS contrato,if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") ) AS saldohoy,cuen.producto AS producto,CASE 
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) <= 30 THEN 'TRAM0_1'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 30 AND CAST(cuen_deta.dias_mora AS SIGNED) <= 60 THEN 'TRAM0_2'
                                                                            WHEN CAST(cuen_deta.dias_mora AS SIGNED) > 60 THEN 'TRAM0_3'
                                                                            ELSE 'NO_TRAMO'
                                                                        END AS tramo_dia,
                                                                        cuen_deta.dias_mora AS diavenc,
                                                                        cli_car.dato1 AS agencia,cli_car.dato4 AS dist_prov,cuen.dato3 AS nom_subprod,cuen.dato8 AS marca,cuen.dato9 AS territorio,cuen.dato11 AS oficina2,
                                                                        cli_car.estado as statusCLIENTE, IF(cuen.retirado = 1,0,1) as statusCUENTA,
                                                                        (SELECT CONCAT_WS(' ',u.paterno,u.materno,u.nombre) AS 'asignadoFOTOCARTERA' FROM ca_cliente_cartera cc INNER JOIN ca_usuario_servicio us ON cc.idusuario_servicio=us.idusuario_servicio INNER JOIN ca_usuario u ON us.idusuario=u.idusuario WHERE cc.idcliente_cartera=cli_car.idcliente_cartera) AS asignadoFOTOCARTERA,
                                                                        IFNULL((SELECT CONCAT_WS('  ',IFNULL(dir.direccion,''),IFNULL(dir.departamento,''),IFNULL(dir.provincia,''),IFNULL(dir.distrito,''))
                                                                            FROM ca_direccion dir
                                                                            WHERE dir.idtipo_referencia=2
                                                                                AND dir.idcliente_cartera=cli_car.idcliente_cartera AND dir.idcuenta=cuen.idcuenta
                                                                                AND ISNULL(dir.direccion)!=1 LIMIT 1
                                                                        ),'^^^^^^') AS direccion_domicilio,
                                                                        (SELECT u.codigo FROM ca_usuario u INNER JOIN ca_usuario_servicio us ON u.idusuario=us.idusuario WHERE us.idusuario_servicio=cli_car.idusuario_servicio) AS codigo_user
                                                                        
                                                                        FROM ca_cliente cli
                                                                        INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                        INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                        INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                        WHERE cuen.idcartera IN (".$carteras.") ORDER BY cli.codigo desc
                                                                )AS  b

                                                                LEFT JOIN (
                                                                    select c.codigo AS 'codigo',sum(deuda)AS 'total_deuda' FROM (
                                                                            
                                                                            SELECT  if(cuen.moneda='PEN',cuen.total_deuda,if(cuen.moneda='USD',cuen.total_deuda*".$tipoCambio.",cuen.total_deuda*".$VAC.") ) AS 'deuda' ,
                                                                            cli.codigo AS 'codigo'
                                                                            FROM ca_cliente cli
                                                                            INNER JOIN ca_cliente_cartera cli_car ON cli.idcliente=cli_car.idcliente
                                                                            INNER JOIN ca_cuenta cuen ON cli_car.idcliente_cartera=cuen.idcliente_cartera
                                                                            INNER JOIN ca_detalle_cuenta cuen_deta ON cuen.idcuenta=cuen_deta.idcuenta
                                                                            WHERE cuen.idcartera IN (".$carteras.") and cuen.retirado!=1 ORDER BY cli.codigo DESC

                                                                    ) as c GROUP BY c.codigo

                                                                )AS d ON d.codigo=b.codcent
                                                            ) AS FOTOCARTERA 

                                                            LEFT JOIN (

                                                                    select  *  FROM (  
                                                                        SELECT * FROM (
                                                                            
                                                                                    
                                                                                                SELECT
                                                                                               
                                                                                        CASE  WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK(  NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 3 DAY ) )   THEN '1'
                                                                                                WHEN ( DAYOFWEEK(  NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 4 DAY ) )  THEN '1'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 6 DAY ) ) THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 8 DAY ) )  THEN '2'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 9 DAY ) )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '3'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3    and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 12 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or  DAYOFWEEK( NOW() )=3   or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 16 DAY ) ) THEN '4'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 15 DAY ) ) THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 20 DAY ) )  THEN '5'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 18 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '6'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 21 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 28 DAY ) ) THEN '7'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 24 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 32 DAY ) )  THEN '8'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 27 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 36 DAY ) )  THEN '9'
                                                                                                WHEN ( DAYOFWEEK( NOW() )!= 2 and  DAYOFWEEK( NOW() )!=3  and  DAYOFWEEK( NOW() )!=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 30 DAY ) )  THEN '10'
                                                                                                WHEN ( DAYOFWEEK( NOW() )= 2 or    DAYOFWEEK( NOW() )=3  or    DAYOFWEEK( NOW() )=4 ) and ( DATE( lla.fecha ) >=  DATE_SUB(NOW(), INTERVAL 40 DAY ) ) THEN '10'
                                                                                            ELSE 'A'
                                                                                        END AS 'CONDICION',
                                                                                        CASE  WHEN ( DAYOFWEEK( lla.fecha )!= 2 and  DAYOFWEEK( lla.fecha )!=3  and  DAYOFWEEK( lla.fecha )!=4 )   THEN '3'
                                                                                                WHEN ( DAYOFWEEK( lla.fecha )= 2 or  DAYOFWEEK( lla.fecha )=3  or DAYOFWEEK( lla.fecha )=4 )    THEN '4'
                                                                                            ELSE 'B'
                                                                                        END AS 'FRECUENCIA',    
                                                                                            lla.idfinal,(select finser.peso from ca_final_servicio finser where  finser.idfinal=lla.idfinal) AS 'peso',
                                                                                           
                                                                                            clicar.idcliente_cartera AS 'DATA',
                                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE',
                                                                                          
                                                                                            lla.idcliente_cartera AS 'idcliente_cartera_llamada',
                                                                                         CASE WHEN ( select  fin.idfinal from ca_final fin where fin.idfinal= lla.idfinal and fin.idfinal IN ('761', '762' ,'763' ,'764') ) THEN 'CPG' ELSE (SELECT carfin.nombre FROM ca_final fin2 INNER JOIN ca_carga_final carfin ON carfin.idcarga_final = fin2.idcarga_final WHERE fin2.idfinal = lla.idfinal)  END    AS 'TIPO_CONTACTO',
                                                                                            replace(replace(replace(Replace(Replace(Replace(lla.observacion,'|',''),char(10),''),char(13),''),char(9),''),char(8),''),char(34),'')  AS 'OBSERVACION',
                                                                                            DATE(lla.fecha_cp) AS 'FECHA_CP',
                                                                                            TRUNCATE(lla.monto_cp,2)  AS 'MONTO_CP',
                                                                                        ( SELECT nombre FROM ca_contacto WHERE idcontacto = lla.idcontacto ) AS 'CONTACTO',
                                                                                          ( SELECT fin.nombre from ca_final fin where fin.idfinal=lla.idfinal ) AS 'ESTADO_LLAMADA',
                                                                                            (select prioridad from ca_final_servicio finser where finser.idfinal=lla.idfinal and finser.estado=1 and finser.idservicio=6) as 'prioridad'
                                                                                            FROM ca_cartera car 
                                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                                            
                                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  -- 2600
                                                                                            AND cli.idservicio = 6
                                                                                
                                                                                            AND car.idcartera IN (".$carteras.")   ORDER BY  clicar.CODIGO_CLIENTE,CONDICION,lla.fecha,prioridad ASC  
                                                                                            
                                                                                   
                                                                              
                                                                        ) AS c GROUP BY  c.idcliente_cartera_llamada
                                                                    ) AS d  

                                                                    LEFT JOIN (
                                                                        select * from ( -- 270
                                                                            select lla.idcliente_cartera AS 'IDCLIENTE_CARTERA_UL_MOTNOPAGO',
                                                                            clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_MOTNOPAGO',
                                                                            lla.fecha AS 'FECHA_UL_MOTNOPAGO',
                                                                            (select mot.nombre from ca_motivo_no_pago mot where mot.idmotivo_no_pago = lla.idmotivo_no_pago ) AS 'UL_MOTIVO_NO_PAGO' 
                                                                            from ca_llamada lla 
                                                                            inner join ca_cuenta cu on cu.idcuenta = lla.idcuenta
                                                                            inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = lla.idcliente_cartera 
                                                                            inner join ca_cliente cli on cli.idcliente = clicar.idcliente
                                                                            inner join ca_cartera car on car.idcartera = clicar.idcartera
                                                                            inner join ca_campania cam on cam.idcampania = car.idcampania
                                                                            where  car.estado = 1 and ( MONTH(lla.fecha) > (MONTH(now())-2) ) and cam.idservicio = 6 and cli.idservicio=6   and lla.idmotivo_no_pago is not null  and  cli.codigo in( 0 ) and DATE(lla.fecha) > DATE_SUB(NOW(),INTERVAL 2 MONTH) ORDER BY clicar.codigo_cliente,lla.fecha DESC 
                                                                        ) AS a GROUP BY a.IDCLIENTE_CARTERA_UL_MOTNOPAGO
                                                                    ) AS b ON b.IDCLIENTE_CARTERA_UL_MOTNOPAGO = d.idcliente_cartera_llamada

                                                                    LEFT JOIN ( 
                                                                            select * from (  -- 67
                                                                            select lla.idcliente_cartera as 'IDCLIENTE_CARTERA_UL_TIPOCOBRANZA',clicar.codigo_cliente AS 'CODIGO_CLIENTE_UL_TIPOCOBRANZA', (select par.nombre from ca_parentesco par where par.idparentesco=lla.idparentesco ) AS 'NOMBRE_UL_TIPO_COBRANZA', lla.fecha AS 'FECHA_UL_TIPO_COBRANZA' 
                                                                            FROM ca_cartera car 
                                                                            INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera=car.idcartera
                                                                            INNER JOIN ca_cliente cli ON clicar.idcliente = cli.idcliente 
                                                                            INNER JOIN ca_llamada lla ON lla.idcliente_cartera = clicar.idcliente_cartera
                                                                            INNER JOIN ca_cuenta cu ON cu.idcuenta = lla.idcuenta
                                                                            WHERE clicar.idcartera IN (".$carteras.") AND cu.idcartera IN (".$carteras.")  
                                                                            AND cli.idservicio = 6
                                                                            AND car.idcartera IN (".$carteras.")
                                                                            AND lla.idparentesco is not null
                                                                            order by lla.fecha,clicar.codigo_cliente DESC
                                                                        ) AS a group by a.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA 
                                                                    )   AS e ON e.IDCLIENTE_CARTERA_UL_TIPOCOBRANZA = d.idcliente_cartera_llamada 

                                                                ) AS h ON FOTOCARTERA.idcliente_cartera_fotocartera = h.idcliente_cartera_llamada 
                                                            )";

                        $prLlenarTemporaryLlamada = $connection->prepare($sqlLlenarTemporaryLlamada);
                        if( $prLlenarTemporaryLlamada->execute() ){
                            $getUltimaProvCargada = "SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 ";
                            $prGetUltimaProvCargada = $connection->prepare($getUltimaProvCargada);
                            $prGetUltimaProvCargada->execute();
                            $ultimaProvisionCargada=$prGetUltimaProvCargada->fetchAll(PDO::FETCH_ASSOC);
                            $nameUltProvisionCargada= $ultimaProvisionCargada[0]['nombre_tabla'];


                            $sqlReporte = "SELECT (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.HIPOTECARIO>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_HIPOTECARIO',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.TOTAL_VARIACION_PROVISION>0 AND tmp.estado=1 AND tmp.idcartera IS NOT NULL )  AS 'VARIACION_PROVISION',
                                                        (SELECT SUM(tmp.HIPOTECARIO) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_HIPOTECARIO_TOTAL',
                                                        (SELECT SUM(tmp.TOTAL_VARIACION_PROVISION) from ".$nameUltProvisionCargada." tmp WHERE tmp.COD_CEN=LLAMADASTOTAL.codcent AND tmp.estado=1 AND tmp.idcartera IS NOT NULL)  AS 'VARIACION_PROVISION_TOTAL',
                                                       (SELECT A.fecha_provision FROM ( SELECT hisprotot.fecha_provision,hisprotot.codcen FROM ca_historico_provision_total hisprotot ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION',
                                                       (SELECT A.fecha_provision_total FROM ( SELECT hisprotot.fecha_provision_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_provision_total  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_PROVISION_TOTAL',
                                                       (SELECT A.fecha_ingreso FROM ( SELECT hisprotot.fecha_ingreso,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO',
                                                       (SELECT A.fecha_ingreso_total FROM ( SELECT hisprotot.fecha_ingreso_total,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.fecha_ingreso_total  IS NOT NULL  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'FECHA_INGRESO_TOTAL',
                                                       (SELECT A.status FROM ( SELECT hisprotot.status,hisprotot.codcen FROM ca_historico_provision_total hisprotot  ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'STATUS',
                                                       (SELECT A.provisionPositiva FROM ( SELECT hisprotot.provisionPositiva,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisionPositiva  IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO',
                                                       (SELECT A.hipotecarioPositivo FROM ( SELECT hisprotot.hipotecarioPositivo,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPositivo IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO',
                                                       (SELECT A.provisitonPosiNega FROM ( SELECT hisprotot.provisitonPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.provisitonPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_TOTAL_HISTORICO_+-',
                                                       (SELECT A.hipotecarioPosiNega FROM ( SELECT hisprotot.hipotecarioPosiNega,hisprotot.codcen FROM ca_historico_provision_total hisprotot WHERE hisprotot.hipotecarioPosiNega IS NOT NULL ORDER BY hisprotot.date_provision DESC )AS A WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'VARIACION_HIPOTECARIO_HISTORICO_+-',
                                                       (SELECT A.isRetirado FROM ( select a.isRetirado,a.codcen FROM (
                                                                                                                       SELECT hisprotot.isRetirado,hisprotot.codcen,hisprotot.idhistorial_tabla_provision_total FROM ca_historico_provision_total hisprotot WHERE hisprotot.isRetirado IS NOT NULL ORDER BY hisprotot.date_provision DESC )as a INNER JOIN (
                                                                                                                      SELECT c.idhistorial_tabla_provision_total, c.nombre_tabla, c.DIA, c.fecha, c.fecha_provisionTotal from (SELECT idhistorial_tabla_provision_total, nombre_tabla, DAYOFMONTH(Fecha) as 'DIA',fecha,fecha_provisionTotal from ca_historial_tabla_provision_total  
                                                                                                                                ORDER BY fecha DESC )AS c GROUP BY c.fecha_provisionTotal ORDER BY  c.fecha_provisionTotal DESC limit 2 )as b ON a.idhistorial_tabla_provision_total=b.idhistorial_tabla_provision_total
                                                                                                                      )AS A 
                                                                                                                WHERE A.codcen=LLAMADASTOTAL.codcent GROUP BY A.codcen ) AS 'isRetirado',

                                                        (SELECT max(hisprotot.provisitonPosiNega) FROM ca_historico_provision_total hisprotot where hisprotot.codcen = LLAMADASTOTAL.codcent AND hisprotot.date_provision>date_sub(hisprotot.date_provision,INTERVAL 2 MONTH))AS MAXPROVI3MESES,
                                           LLAMADASTOTAL.agencia AS 'CARTERA',LLAMADASTOTAL.nom_subprod AS 'NOM_SUBPROD',LLAMADASTOTAL.territorio AS 'TERRITORIO',LLAMADASTOTAL.oficina2 AS 'OFICINA2',LLAMADASTOTAL.contrato AS 'CONTRATO',LLAMADASTOTAL.codcent AS 'CODCENT',LLAMADASTOTAL.nombre AS 'NOMBRE',LLAMADASTOTAL.saldohoy AS 'DEUDA',LLAMADASTOTAL.total_deuda AS 'TOTALDEUDA',LLAMADASTOTAL.direccion_domicilio AS 'DIRECCION',LLAMADASTOTAL.diavenc AS 'DIAVENC',LLAMADASTOTAL.dist_prov AS 'DIST_PROV',LLAMADASTOTAL.marca AS 'MARCA',LLAMADASTOTAL.producto AS 'PRODUCTO',LLAMADASTOTAL.codigo_user AS 'CODIGO_GESTOR',LLAMADASTOTAL.asignadoFOTOCARTERA AS 'GESTOR_ASIGNADO',LLAMADASTOTAL.tramo_dia AS 'TRAMO_DIA_HDEC',LLAMADASTOTAL.statusCUENTA AS 'FLAG_CUENTA',
                                                    LLAMADASTOTAL.TIPO_CONTACTO AS 'TIPO_CONTACTO_CALL',LLAMADASTOTAL.ESTADO_LLAMADA AS 'ESTADO_CONTACTO_CALL',LLAMADASTOTAL.FECHA_CP AS 'FECHA_COMPROMISO_CALL',LLAMADASTOTAL.OBSERVACION AS 'OBSERVACION_CALL', 
                                                    VISITASTOTAL.CARGA AS 'TIPO_CONTACTO_CAMPO',VISITASTOTAL.ESTADO_VISITA AS 'ESTADO_CONTACTO_CAMPO', VISITASTOTAL.FECHA_CPG AS 'FECHA_COMPROMISO_CAMPO',VISITASTOTAL.OBSERVACION AS 'OBSERVACION_CAMPO',
                                                    CASE 
                                                            WHEN ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO>VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NULL )  THEN LLAMADASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO>LLAMADASTOTAL.FECHA_UL_MOTNOPAGO ) OR ( LLAMADASTOTAL.FECHA_UL_MOTNOPAGO IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_MOTNOPAGO IS NOT NULL )  THEN VISITASTOTAL.UL_MOTIVO_NO_PAGO
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CNE','NOC','CEF')
                                                              ¿'N 'NO HAY MOT  7IVO DE NO PAGO'
                                                            WHEN ( ( LLAMADASTOTAL.peso > VISITASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS NOT NULL  AND  VISITASTOTAL.peso IS NULL  ) )  
                                                                        AND LLAMADASTOTAL.TIPO_CONTACTO IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CNE','NOC','CEF')
                                                                THEN 'NO HAY MOTIVO DE NO PAGO'
                                                            WHEN ( ( VISITASTOTAL.peso > LLAMADASTOTAL.peso )
                                                                        OR (LLAMADASTOTAL.peso IS  NULL  AND  VISITASTOTAL.peso IS NOT NULL  ) )  
                                                                        AND VISITASTOTAL.CARGA IN('CPG')
                                                                THEN 'NO BRINDA MOTIVO DE NO PAGO'
                                                            ELSE null
                                                    END AS 'MOTIVO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA>VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS NOT NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NULL )  THEN LLAMADASTOTAL.NOMBRE_UL_TIPO_COBRANZA
                                                        WHEN ( VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO>LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA ) OR ( LLAMADASTOTAL.FECHA_UL_TIPO_COBRANZA IS  NULL AND VISITASTOTAL.FECHA_VISITA_UL_TIPOCONTACTO IS NOT NULL )  THEN VISITASTOTAL.TIPO_CONTACTO
                                                        ELSE null
                                                    END AS 'TIPO_DE_COBRANZA',
                                            -- LLAMADASTOTAL.peso AS 'PESO1',
                                            -- VISITASTOTAL.peso AS 'PESO2',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN 'CALL'
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN 'CAMPO'
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_GESTION',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.TIPO_CONTACTO
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.CARGA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_TIPO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.ESTADO_LLAMADA
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.ESTADO_VISITA
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_ESTADO_CONTACTO',
                                                    CASE 
                                                        WHEN ( LLAMADASTOTAL.peso > VISITASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NOT NULL AND VISITASTOTAL.peso IS NULL  ) THEN LLAMADASTOTAL.FECHA_CP 
                                                        WHEN ( VISITASTOTAL.peso > LLAMADASTOTAL.peso ) OR (LLAMADASTOTAL.peso IS NULL AND VISITASTOTAL.peso IS NOT NULL  ) THEN VISITASTOTAL.FECHA_CPG
                                                        WHEN VISITASTOTAL.peso = LLAMADASTOTAL.peso THEN 'EMPATE'
                                                        ELSE NULL
                                                    END AS 'MEJOR_FECHA_COMPROMISO'



                                                    FROM TMP_FOTOCARTERAVISITA_PROVISION_TOTAL VISITASTOTAL 
                                                    RIGHT JOIN TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL LLAMADASTOTAL  ON LLAMADASTOTAL.CODIGO_CLIENTE = VISITASTOTAL.CODIGO_CLIENTE GROUP BY LLAMADASTOTAL.CONTRATO,LLAMADASTOTAL.DATA ORDER BY LLAMADASTOTAL.codcent ASC";

                        $prReporte=$connection->prepare($sqlReporte);
                        if($prReporte->execute()){
                            $contenidoReporte= $prReporte->fetchAll(PDO::FETCH_ASSOC);
                            /*Colores Cabecera por fotocartera-llamada-visita-mejorvisitallamada,historialdeprovision-provision*/
                            $colorFotoCartera = "background:#C00000;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorCallCampo = "background:#963634;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorMejorGestion = "background : #00B0F0 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorHistorial = "background : #FFFF00 ;color:black;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvision = "background : #632523 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $colorProvisionMASMENOS = "background : #000000 ;color:white;font-size:13px;height:35px;font-weight:bold;vertical-align:middle";
                            $contenidoCuerpoFotoCartera = "height:20px;font-size:11px;border: 1px solid #000000";
                            /*CABECERA*/
                            $cabecera = "<table><tr><td style=\"".$colorFotoCartera."\">CARTERA</td><td style=\"".$colorFotoCartera."\">NOM_SUBPROD</td><td style=\"".$colorFotoCartera."\">TERRITORIO</td><td style=\"".$colorFotoCartera."\">OFICINA2</td><td style=\"".$colorFotoCartera."\">CONTRATO</td><td style=\"".$colorFotoCartera."\">CODCENT</td><td style=\"".$colorFotoCartera."\">NOMBRE</td><td style=\"".$colorFotoCartera."\">DEUDA</td><td style=\"".$colorFotoCartera."\">DEUDA_TOTAL</td><td style=\"".$colorFotoCartera."\">DIRECCION</td><td style=\"".$colorFotoCartera."\">DIAVENC</td><td style=\"".$colorFotoCartera."\">DIST_PROV</td><td style=\"".$colorFotoCartera."\">MARCA</td><td style=\"".$colorFotoCartera."\">PRODUCTO</td><td style=\"".$colorFotoCartera."\">CODIGO_GESTOR</td><td style=\"".$colorFotoCartera."\">GESTOR_ASIGNADO</td><td style=\"".$colorFotoCartera."\">TRAMO_DIA_HDEC</td><td style=\"".$colorFotoCartera."\">FLAG_CUENTA</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CALL</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CALL</td><td style=\"".$colorCallCampo."\">OBSERVACION_CALL</td><td style=\"".$colorCallCampo."\">TIPO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">ESTADO_CONTACTO_CAMPO</td><td style=\"".$colorCallCampo."\">FECHA_COMPROMISO_CAMPO</td><td style=\"".$colorCallCampo."\">OBSERVACION_CAMPO</td><td style=\"".$colorCallCampo."\">MOTIVO</td><td style=\"".$colorCallCampo."\">TIPO_DE_COBRANZA</td><td style=\"".$colorMejorGestion."\">MEJOR_GESTION</td><td style=\"".$colorMejorGestion."\">MEJOR_TIPO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_ESTADO_CONTACTO</td><td style=\"".$colorMejorGestion."\">MEJOR_FECHA_COMPROMISO</td><td style=\"".$colorHistorial."\">FECHA_PROVISION</td><td style=\"".$colorHistorial."\">FECHA_INGRESO</td><td style=\"".$colorHistorial.";text-align:center"."\">STATUS</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO</td><td style=\"".$colorProvision."\">VARIACION_TOTAL</td><td style=\"".$colorProvision."\">VARIACION_HIPOTECARIO</td><td style=\"".$colorProvisionMASMENOS."\">MAYOR_PROVISION_3MESESATRAS</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_TOTAL_+-</td><td style=\"".$colorProvisionMASMENOS."\">VARIACION_HIPOTECARIO_+-</td><td style=\"".$colorHistorial."\">VARIACION_TOTAL_HISTORICO_+-</td><td style=\"".$colorHistorial."\">VARIACION_HIPOTECARIO_HISTORICO_+-</td><td style=\"".$colorHistorial."\">FECHA_PROVISION_TOTAL</td><td style=\"".$colorHistorial."\">FECHA_INGRESO_TOTAL</td></tr></table>";   
                            /*CUERPO*/
                            $cuerpo ="<table>";
                            for($i=0;$i<count($contenidoReporte);$i++){
                                if($contenidoReporte[$i]['FECHA_PROVISION_TOTAL']==""){
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION'];
                                }else{
                                  $fechaProvisionTotal = $contenidoReporte[$i]['FECHA_PROVISION_TOTAL'] ;
                                }

                                if($contenidoReporte[$i]['FECHA_INGRESO_TOTAL']==""){
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO'];
                                }else{
                                  $fechaIngresoTotal = $contenidoReporte[$i]['FECHA_INGRESO_TOTAL'];
                                }

                                if($contenidoReporte[$i]['TIPO_CONTACTO_CALL']==""){
                                    $mejorGestion = "CALL";
                                    $mejorTipoContacto = "NOC";
                                    $mejorEstadoContacto = "NO CONTESTA";
                                    $tipoContactoCall = "NOC";
                                    $estadoContactoCall = "NO CONTESTA";
                                    $observacionCall = "NO CONTESTA";
                                    $motivo = "NO HAY MOTIVO DE NO PAGO";
                                }else{
                                    $mejorGestion = $contenidoReporte[$i]['MEJOR_GESTION'];
                                    $mejorTipoContacto = $contenidoReporte[$i]['MEJOR_TIPO_CONTACTO'];
                                    $mejorEstadoContacto = $contenidoReporte[$i]['MEJOR_ESTADO_CONTACTO'];
                                    $tipoContactoCall = $contenidoReporte[$i]['TIPO_CONTACTO_CALL'];
                                    $estadoContactoCall = $contenidoReporte[$i]['ESTADO_CONTACTO_CALL'];
                                    $observacionCall = $contenidoReporte[$i]['OBSERVACION_CALL'];
                                    $motivo = $contenidoReporte[$i]['MOTIVO'];
                                }

                                if($contenidoReporte[$i]['FLAG_CUENTA']==0 || $contenidoReporte[$i]['isRetirado']==1){
                                    $variacionProvision="";
                                    $variacionHipotecario="";
                                    $variacionProvisionTotal="";
                                    $variacionHipotecarioTotal="";
                                    $flagCuenta = "0";
                                }else{
                                    $variacionProvision=$contenidoReporte[$i]['VARIACION_PROVISION'];
                                    $variacionHipotecario=$contenidoReporte[$i]['VARIACION_HIPOTECARIO'];
                                    $variacionProvisionTotal=$contenidoReporte[$i]['VARIACION_PROVISION_TOTAL'];
                                    $variacionHipotecarioTotal=$contenidoReporte[$i]['VARIACION_HIPOTECARIO_TOTAL'];
                                    $flagCuenta=$contenidoReporte[$i]['FLAG_CUENTA'];
                                }
                                $cuerpo.="  <tr>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CARTERA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['NOM_SUBPROD']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TERRITORIO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OFICINA2']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CONTRATO']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\"> =\"".$contenidoReporte[$i]['CODCENT']."\" </td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['NOMBRE'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TOTALDEUDA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIRECCION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIAVENC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['DIST_PROV']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MARCA']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".utf8_decode($contenidoReporte[$i]['PRODUCTO'])."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['CODIGO_GESTOR']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['GESTOR_ASIGNADO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TRAMO_DIA_HDEC']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$flagCuenta."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$tipoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$estadoContactoCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CALL']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$observacionCall."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['ESTADO_CONTACTO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_COMPROMISO_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['OBSERVACION_CAMPO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$motivo."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['TIPO_DE_COBRANZA']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorGestion."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorTipoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$mejorEstadoContacto."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MEJOR_FECHA_COMPROMISO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_PROVISION']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['FECHA_INGRESO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['STATUS']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO']."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvision."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecario."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['MAXPROVI3MESES']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$variacionHipotecarioTotal."</td>

                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_TOTAL_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$contenidoReporte[$i]['VARIACION_HIPOTECARIO_HISTORICO_+-']."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaProvisionTotal."</td>
                                                <td style=\"". $contenidoCuerpoFotoCartera. "\">".$fechaIngresoTotal."</td>
                                            </tr>";
                            }
                            $cuerpo.="</table>";
                            

                            /*VOLAR TMPS*/

                            //tmp1
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERAVISITA_PROVISION_TOTAL ";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            //tmp2
                            $sqlEliminarTMP = "DROP TEMPORARY TABLE IF EXISTS TMP_FOTOCARTERALLAMADA_PROVISION_TOTAL";
                            $prEliminarTMP = $connection->prepare($sqlEliminarTMP);
                            $prEliminarTMP->execute();

                            echo $cabecera;
                            echo $cuerpo;
                        }else{}
                        }else{
                        }
                    }else{
                    }
                }else{
                }
        }        
   // }
?>