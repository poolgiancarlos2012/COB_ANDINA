<?php
session_start();

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';

function ProcesarCargaCorreo($file){

	$factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    
        $utf8 = "SET NAMES 'utf8'";
    $prutf8 = $connection->prepare($utf8);
    $prutf8->execute();
    
    //$conf = parse_ini_file('../../../conf/cobrast.ini', true);
    $time = date("Y_m_d_H_i_s");
    $name_table="`tmp_correo_" . session_id() . "_" . $time . "`";

    $create="   CREATE TABLE  $name_table
                (
					`codigo_cliente` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`correo` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    $pr_create = $connection->prepare($create);
    if($pr_create->execute()){

        $load = "   LOAD DATA INFILE 'C:/xampp/htdocs/COB_ANDINA/documents/loadmail/" . $file . "'
                    INTO TABLE $name_table FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES";

        $pr = $connection->prepare($load);
        if($pr->execute()){
        	$insert="	INSERT IGNORE `cob_andina`.`ca_correo`(`codigo_cliente`,`correo`,`estado`,`usuario_creacion`,`fecha_creacion`)
						SELECT
						tmp.codigo_cliente,
						tmp.correo,
						1,
						1,
						NOW()
						FROM
						$name_table tmp
					";
			$prinsert = $connection->prepare($insert);
        	if($prinsert->execute()){
        		$update="	UPDATE 
							ca_correo cor 
							INNER JOIN ca_cliente cli ON cor.codigo_cliente=cli.codigo
							SET cor.idcliente=cli.idcliente";
				$prupdate = $connection->prepare($update);
        		if($prupdate->execute()){

					$unenable=" UPDATE loadcorreo SET pendiente='0' WHERE nombretrans='$file'; ";
					$prunenable = $connection->prepare($unenable);
					if($prunenable->execute()){
						//$delete="DROP TABLE $name_table";
						$delete="";
	        			$prdelete = $connection->prepare($delete);
	        			if($prdelete->execute()){
	        				echo "Proceso Correctamente";
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
						loadcorreo
						WHERE
						pendiente=1
						ORDER BY 3 DESC LIMIT 1'
						;

$pr_last_fileload = $connection->prepare($sql_last_fileload);
$pr_last_fileload->execute();
$temp_file=$pr_last_fileload->fetchAll(PDO::FETCH_ASSOC);

$temp=$temp_file[0]['nombretrans'];

ProcesarCargaCorreo($temp);

?>