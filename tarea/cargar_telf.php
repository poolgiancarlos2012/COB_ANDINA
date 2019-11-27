<?php
session_start();

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';

function ProcesarCargaTelf($file){

	$factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    
    $utf8 = "SET NAMES 'utf8'";
    $prutf8 = $connection->prepare($utf8);
    $prutf8->execute();
    
    //$conf = parse_ini_file('../../../conf/cobrast.ini', true);
    $time = date("Y_m_d_H_i_s");
    $name_table="`tmp_telefono_" . session_id() . "_" . $time . "`";

    $create="   CREATE TABLE  $name_table
                (
					`codigo_cliente` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`telefono` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					idcliente_cartera INT
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    $pr_create = $connection->prepare($create);
    if($pr_create->execute()){
        $load = "   LOAD DATA INFILE 'C:/xampp/htdocs/COB_ANDINA/documents/loadtelf/" . $file . "'
                    INTO TABLE $name_table FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES";
        $pr = $connection->prepare($load);
        // $pr->execute();       

        if($pr->execute()){
        	$cruce = "	UPDATE
						$name_table tmp INNER JOIN ca_cliente_cartera clicar ON tmp.codigo_cliente = clicar.codigo_cliente
						SET tmp.idcliente_cartera=clicar.idcliente_cartera";

			$prcruce = $connection->prepare($cruce);
				// $prcruce->execute();

			if($prcruce->execute()){
				$lastidcartera = "SELECT car.idcartera AS idcartera FROM ca_servicio serv INNER JOIN ca_campania camp ON camp.idservicio=serv.idservicio INNER JOIN ca_cartera car ON car.idcampania = camp.idcampania WHERE  serv.idservicio = 1 AND camp.idcampania = 1 ORDER BY fecha_carga DESC";
				$prlastidcar = $connection->prepare($lastidcartera);
				$prlastidcar->execute();
				$ar_prlastidcar = $prlastidcar->fetchAll(PDO::FETCH_ASSOC);
				$idcartera = $ar_prlastidcar[0]['idcartera'];

	        	$sqltmp = "SELECT codigo_cliente,telefono FROM $name_table WHERE idcliente_cartera<>''";
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

					       	$insert="	INSERT IGNORE `cob_andina`.`ca_telefono`(
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
							// echo $insert;
							$prinsert = $connection->prepare($insert);
				        	if($prinsert->execute()){
				        		echo "Proceso Correctamente";
				        	}

	        			}

	        		}

	        	}

			}

        }

    } 

}

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$sql_last_fileload=	'	SELECT 
						nombrecarga,
						nombretrans,
						STR_TO_DATE(REPLACE(REPLACE(nombrecarga,"DOCS_",""),".txt",""),"%Y%m%d_%H%i%s") AS fecha_carga
						FROM 
						loadtelf
						WHERE
						pendiente=1
						ORDER BY 3 DESC LIMIT 1'
						;

$pr_last_fileload = $connection->prepare($sql_last_fileload);
$pr_last_fileload->execute();
$temp_file=$pr_last_fileload->fetchAll(PDO::FETCH_ASSOC);

$temp=$temp_file[0]['nombretrans'];

ProcesarCargaTelf($temp);

?>