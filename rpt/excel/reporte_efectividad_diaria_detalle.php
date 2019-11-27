<?php

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	date_default_timezone_set('America/Lima');

    header('Content-Type: text/html; charset=UTF-8');
    header("Content-type:application/vnd.ms-excel;charset=latin");
    header("Content-Disposition:atachment;filename=REPORTE_EFECTIVIDAD_DIARIA_DETALLE.xls");
    header("Pragma:no-cache");
    header("Expires:0");
	
	$cartera = $_REQUEST['Cartera'];
	$nombre_servicio = $_REQUEST['NombreServicio'];
	$fecha_unica=$_REQUEST['fecha_unica'];
    $tipocambio=$_REQUEST['tipocambio'];
    $tipovac=$_REQUEST['tipovac'];
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	

     $abc= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
                'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
                'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
                'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
                'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ',
                'HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ',
                'IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK','IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ',
                'JA','JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ','JR','JS','JT','JU','JV','JW','JX','JY','JZ',
                'KA','KB','KC','KD','KE','KF','KG','KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW','KX','KY','KZ');
	


    $array_cartera=explode(",",$cartera);
//	$array_fproceso=explode(",", $fproceso);
    $sql_fproceso_cartera="";
    $array_fproceso=array();
    $cartera_posicion=0;
    for($j=0;$j<count($array_cartera);$j++){

        $sql_fproceso="select fproceso as fecha,CONCAT(SUBSTR(fecha,7,2),SUBSTR(fecha,4,2),SUBSTR(fecha,1,2)) as orden_fecha from (
                select DISTINCT his.Fproceso,CASE SUBSTR(his.fproceso,4,3)  WHEN 'Ene' THEN REPLACE(his.fproceso,'Jan','01') 
                WHEN 'Feb' THEN REPLACE(his.fproceso,'Feb','02') WHEN 'Mar' THEN REPLACE(his.fproceso,'Mar','03')                                               
                WHEN 'Abr' THEN REPLACE(his.fproceso,'Apr','04') WHEN 'May' THEN REPLACE(his.fproceso,'May','05') 
                WHEN 'Jun' THEN REPLACE(his.fproceso,'Jun','06') WHEN 'Jul' THEN REPLACE(his.fproceso,'Jul','07') 
                WHEN 'Ago' THEN REPLACE(his.fproceso,'Aug','08') WHEN 'Set' THEN REPLACE(his.fproceso,'Sep','09') 
                WHEN 'Oct' THEN REPLACE(his.fproceso,'Oct','10') WHEN 'Nov' THEN REPLACE(his.fproceso,'Nov','11') 
                WHEN 'Dic' THEN REPLACE(his.fproceso,'Dec','12') END fecha
                from ca_historial his
                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                where clicar.idcartera in(".$array_cartera[$j]."))A order by orden_fecha DESC limit 7";
        $pr_fproceso=$connection->prepare($sql_fproceso);

        unset($array_fproceso);
        $array_fproceso=array();

        if($pr_fproceso->execute()){
        while($data_fproceso=$pr_fproceso->fetch(PDO::FETCH_ASSOC)){
            foreach ($data_fproceso as $key => $value) {
                if($key=='fecha'){
                    array_push($array_fproceso,$value); 
                }

            }

            }
        }

        if($cartera_posicion>0){
            $sql_fproceso_cartera=$sql_fproceso_cartera." UNION ALL ";
        }
        $cantidad=count($array_fproceso);
    	for($i=0;$i<count($array_fproceso);$i++){
            if($i<($cantidad-1)){
    		$sql_fproceso_cartera=$sql_fproceso_cartera." SELECT his.idcliente_cartera,his.fproceso,his.territorio,(SELECT            nombre_cartera from ca_cartera where idcartera=".$array_cartera[$j].") as cod_gest,CONCAT('=\"',his.codcent,his.contrato,'\"') as UNICO,
                                    his.producto,his.divisa,his.saldohoy,his.diavenc,
                                    IF((select count(*) from ca_historial his2 where his2.Fproceso='".$array_fproceso[($cantidad-2-$i)]."' and his2.contrato=his.contrato)>0,'Activo','Retirado') AS 'STATUS',
                                    CASE WHEN his.diavenc>0 and his.diavenc<=30 then 'T1' WHEN his.diavenc>31 and his.diavenc<=60 then 'T2' ELSE 'T3' END as Trama,
                                    CASE WHEN his.divisa='PEN' THEN CONCAT('S/.',round(his.saldohoy,2)) WHEN his.divisa='USD' THEN CONCAT('S/.',round($tipocambio*his.saldohoy,2)) WHEN his.divisa='VAC' THEN CONCAT('S/.',round($tipovac*his.saldohoy,2)) END as 'monto',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=5000,'0-5000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=15000,'5000-15000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=35000,'15000-35000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=50000,'35000-50000','50000-307000')))) AS 'tramo_monto',
                                    his.codcent
                                    from ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                                    where clicar.idcartera in (".$array_cartera[$j].") and his.fproceso='".$array_fproceso[$cantidad-1-$i]."'
                                    UNION ALL";		
            }else{
            $sql_fproceso_cartera=$sql_fproceso_cartera." SELECT his.idcliente_cartera,his.fproceso,his.territorio,(SELECT nombre_cartera from ca_cartera where idcartera=".$array_cartera[$j].") as cod_gest,CONCAT('=\"',his.codcent,his.contrato,'\"') as UNICO,
                                    his.producto,his.divisa,his.saldohoy,his.diavenc,
                                    IF((select count(*) from ca_historial his2 where his2.Fproceso='".$array_fproceso[($cantidad-1-$i)]."' and his2.contrato=his.contrato)>0,'Activo','Retirado') AS 'STATUS',
                                    CASE WHEN his.diavenc>0 and his.diavenc<=30 then 'T1' WHEN his.diavenc>31 and his.diavenc<=60 then 'T2' ELSE 'T3' END as Trama,
                                    CASE WHEN his.divisa='PEN' THEN CONCAT('S/.',round(his.saldohoy,2)) WHEN his.divisa='USD' THEN CONCAT('S/.',round($tipocambio*his.saldohoy,2)) WHEN his.divisa='VAC' THEN CONCAT('S/.',round($tipovac*his.saldohoy,2)) END as 'monto',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=5000,'0-5000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=15000,'5000-15000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=35000,'15000-35000',
                                    IF(CASE WHEN his.divisa='PEN' THEN his.saldohoy WHEN his.divisa='USD' THEN $tipocambio*his.saldohoy WHEN his.divisa='VAC' THEN $tipovac*his.saldohoy END<=50000,'35000-50000','50000-307000')))) AS 'tramo_monto',
                                    his.codcent
                                    from ca_historial his
                                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                                    where clicar.idcartera in (".$array_cartera[$j].") and his.fproceso='".$array_fproceso[$cantidad-1-$i]."'";                   
            }
   		
        }
        $cartera_posicion++;
    }

    $sql_fotocartera_detalle= "SELECT A.*,IFNULL(B.nombre,'S/G') AS Contacto
                                FROM (".$sql_fproceso_cartera.")A 
                            LEFT OUTER JOIN (
                            SELECT * from (
                            SELECT lla.idcliente_cartera,lla.idcuenta, lla.fecha, fin.idcarga_final,carfin.nombre, lla.idfinal , finser.peso
                            from ca_cliente_cartera clicar inner join ca_llamada lla inner join ca_final fin inner join ca_final_servicio finser inner join ca_carga_final carfin
                            on finser.idfinal = fin.idfinal and fin.idfinal = lla.idfinal and lla.idcliente_cartera= clicar.idcliente_cartera and fin.idcarga_final=carfin.idcarga_final
                            where clicar.idcartera in ($cartera) and lla.tipo<>'IVR' and date(lla.fecha)='$fecha_unica' and lla.idusuario_servicio<>'1' 
                            order by lla.idcliente_cartera, finser.peso desc,lla.fecha DESC 
                            ) t1 group by t1.idcliente_cartera
                            )B on A.idcliente_cartera=B.idcliente_cartera";


    $pr_fproceso_cartera=$connection->prepare($sql_fotocartera_detalle);
    $cont=0;
    $filainicio=1;
    $i=0;
    if($pr_fproceso_cartera->execute()){
        while($data_fproceso_cartera=$pr_fproceso_cartera->fetch(PDO::FETCH_ASSOC)){
            if($i==0){
                foreach ($data_fproceso_cartera as $key => $value) {
                    if($key!='idcliente_cartera'){
                        echo utf8_decode($key)."\t";
                        $cont++;
                    }
                }
                $filainicio++;
                    echo "\n";
            }
            $i++;
            $cont=0;
            foreach ($data_fproceso_cartera as $key => $value) {
                if($key!='idcliente_cartera'){
                    echo utf8_decode($value)."\t";
                    $cont++;
                }
            }
                    echo "\n";
                    $filainicio++;
        }        
    }    



        
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output'); 

?>