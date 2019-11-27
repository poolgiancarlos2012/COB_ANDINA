<?php
session_start();

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';

function ProcesarCargaCorreo($file){

    $factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    
    $time = date("Y_m_d_H_i_s");
    $name_table="`tmp_datocontacto_" . session_id() . "_" . $time . "`";

    $create="   CREATE TABLE  $name_table (
                                `codigo_cliente` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `departamento` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `provincia` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `distrito` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `direccion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `correo` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `telefono` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `tipo_dato` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
                                `idcliente` INT
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    
    $pr_create = $connection->prepare($create);
    if($pr_create->execute()){

        $load = "     LOAD DATA INFILE 'C:/xampp/htdocs/COB_ANDINA/documents/loaddatocontacto/" . $file . "' INTO TABLE $name_table CHARACTER SET UTF8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES";
        $pr = $connection->prepare($load);
        if($pr->execute()){
            $update="       UPDATE 
                                    $name_table tmp
                                    INNER JOIN ca_cliente cli ON tmp.codigo_cliente=cli.codigo
                                    SET tmp.idcliente=cli.idcliente";
            $prupdate = $connection->prepare($update);
            if($prupdate->execute()){
                $sqldir = "    SELECT 
                                    TRIM(codigo_cliente) AS codigo_cliente,
                                    TRIM(departamento) AS departamento,
                                    TRIM(provincia) AS provincia,
                                    TRIM(distrito) AS distrito,
                                    TRIM(direccion) AS direccion 
                                    FROM 
                                    $name_table 
                                    WHERE 
                                    idcliente <> '' AND
                                    TRIM(departamento) <> '' AND
                                    TRIM(provincia) <> '' AND
                                    TRIM(distrito) <> '' AND
                                    TRIM(direccion) <> ''
                                    ";
                $prdir = $connection->prepare($sqldir);
                if($prdir->execute()){
                    $ar_temp=$prdir->fetchAll(PDO::FETCH_ASSOC);

                    for ($i=0; $i <=count($ar_temp)-1 ; $i++) {
                        $codigo_cliente	= $ar_temp[$i]['codigo_cliente'];
                        $departamento	= $ar_temp[$i]['departamento'];
                        $provincia	= $ar_temp[$i]['provincia'];
                        $distrito		= $ar_temp[$i]['distrito'];
                        $direccion 	= $ar_temp[$i]['direccion'];

                        $insert="      INSERT IGNORE 
                                            `cob_andina`.`ca_direccion`
                                            (
                                                `codigo_cliente`,
                                                `departamento`,
                                                `provincia`,
                                                `distrito`,
                                                `direccion`,
                                                `estado`,
                                                `usuario_creacion`,
                                                `fecha_creacion`
                                            )
                                            VALUES(
                                                '$codigo_cliente',
                                                '$departamento',
                                                '$provincia',
                                                '$distrito',
                                                '$direccion',
                                                1,
                                                1,
                                                NOW()
                                            )";
                        $prinsert = $connection->prepare($insert);
                        if($prinsert->execute()){
                                echo "Proceso Correctamente Direccion"."\n";
                        }
                    }
                }

                $sqlcor = "SELECT codigo_cliente,correo,idcliente FROM $name_table WHERE idcliente <>'' AND correo <> '' GROUP BY codigo_cliente,correo ORDER BY codigo_cliente ASC";
                $prcor = $connection->prepare($sqlcor);
                if($prcor->execute()){
                    $ar_cor=$prcor->fetchAll(PDO::FETCH_ASSOC);
                    for ($j=0; $j <= count($ar_cor)-1; $j++) {

                        $cor_idcliente = $ar_cor[$j]['idcliente'];
                        $cor_codigo_cliente = $ar_cor[$j]['codigo_cliente'];
                        $ar_correo = explode(',', $ar_cor[$j]['correo']);

                        for($k=0; $k <= count($ar_correo)-1; $k++){
                                $correo = $ar_correo[$k];

                                $insertcor="	INSERT IGNORE `cob_andina`.`ca_correo`(
                                                                        `codigo_cliente`,
                                                                        `correo`,
                                                                        `estado`,
                                                                        `usuario_creacion`,
                                                                        `fecha_creacion`,
                                                                        `idcliente`
                                                                )
                                                                VALUES(
                                                                        '$cor_codigo_cliente',
                                                                        '$correo',
                                                                        1,
                                                                        1,
                                                                        NOW(),
                                                                        $cor_idcliente
                                                                )
                                                        ";
                                $prinsertcor = $connection->prepare($insertcor);
                            if($prinsertcor->execute()){
                                    echo "Proceso Correctamente Correo"."\n";
                            }
                        }
                    }
                }

                $lastidcartera = "SELECT car.idcartera AS idcartera FROM ca_servicio serv INNER JOIN ca_campania camp ON camp.idservicio=serv.idservicio INNER JOIN ca_cartera car ON car.idcampania = camp.idcampania WHERE  serv.idservicio = 1 AND camp.idcampania = 1 ORDER BY fecha_carga DESC";
                $prlastidcar = $connection->prepare($lastidcartera);
                $prlastidcar->execute();
                $ar_prlastidcar = $prlastidcar->fetchAll(PDO::FETCH_ASSOC);
                $idcartera = $ar_prlastidcar[0]['idcartera'];

                $sqltmp = "SELECT codigo_cliente,telefono FROM $name_table WHERE idcliente <> '' AND telefono <> '' GROUP BY codigo_cliente,telefono ORDER BY codigo_cliente ASC";
                $prtmp = $connection->prepare($sqltmp);
                if($prtmp->execute()){
                    $ar_prtmp = $prtmp->fetchAll(PDO::FETCH_ASSOC);

                    for ($i=0; $i <= count($ar_prtmp)-1 ; $i++) { 

                        $cod_cliente = $ar_prtmp[$i]["codigo_cliente"];

                                $sqlidcliente_cartera = "SELECT idcliente_cartera FROM ca_cliente_cartera WHERE codigo_cliente='$cod_cliente' AND idcartera=$idcartera";
                                $pridclicar = $connection->prepare($sqlidcliente_cartera);
                                $pridclicar->execute();
                                $ar_pridclicar = $pridclicar->fetchAll(PDO::FETCH_ASSOC);
                                $idcliente_cartera = $ar_pridclicar[0]['idcliente_cartera'];

                        $ar_telf = $ar_prtmp[$i]["telefono"];

                        $arrtelf = explode(',', $ar_telf);

                        for($j=0; $j <= count($arrtelf)-1 ; $j++){

                            $telf = $arrtelf[$j];

                            $inserttelf="	INSERT IGNORE `cob_andina`.`ca_telefono`(
                                                                idtipo_telefono,
                                                                numero,
                                                                fecha_creacion,
                                                                usuario_creacion,
                                                                idorigen,
                                                                idtipo_referencia,
                                                                idcartera,
                                                                idlinea_telefono,
                                                                codigo_cliente,
                                                                estado,
                                                                is_new,
                                                                is_active,
                                                                idcliente_cartera,
                                                                status
                                                        )
                                                        VALUES
                                                        (
                                                                8,
                                                                '$telf',
                                                                NOW(),
                                                                1,
                                                                16,
                                                                5,
                                                                '$idcartera',
                                                                7,
                                                                '$cod_cliente',
                                                                1,
                                                                1,
                                                                1,
                                                                '$idcliente_cartera',
                                                                1
                                                        )

                                                ";
                            $prinserttelf = $connection->prepare($inserttelf);
                            if($prinserttelf->execute()){
                                    echo "Proceso Correctamente Telefono"."\n";
                            }
                        }
                    }
                }

                $unenable=" UPDATE loaddatocontacto SET pendiente='0' WHERE nombretrans='$file'; ";
                $prunenable = $connection->prepare($unenable);
                if($prunenable->execute()){
                    $delete="DROP TABLE $name_table";
                    $prdelete = $connection->prepare($delete);
                    if($prdelete->execute()){
                    echo "Proceso Correctamente"."\n";
                    }
                }
            }
        }
    }      
}

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$sql_last_fileload=	'  SELECT 
                                        nombrecarga,
                                        nombretrans,
                                        STR_TO_DATE(REPLACE(REPLACE(nombrecarga,"DOCS_",""),".txt",""),"%Y%m%d_%H%i%s") AS fecha_carga
                                        FROM 
                                        loaddatocontacto
                                        WHERE
                                        pendiente=1
                                        ORDER BY 3 DESC LIMIT 1 ';

$pr_last_fileload = $connection->prepare($sql_last_fileload);
$pr_last_fileload->execute();
$temp_file=$pr_last_fileload->fetchAll(PDO::FETCH_ASSOC);
$temp=$temp_file[0]['nombretrans'];
ProcesarCargaCorreo($temp);



?>