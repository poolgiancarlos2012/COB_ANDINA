<?php
session_start();

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';

function ProcesarCargaCorreo($file){

	$factoryConnection = FactoryConnection::create('mysql');
    $connection = $factoryConnection->getConnection();
    
    $time = date("Y_m_d_H_i_s");
    $name_table="`tmp_direccion_" . session_id() . "_" . $time . "`";

    $create="   CREATE TABLE  $name_table
                (
					`codigo_cliente` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`departamento` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`provincia` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`distrito` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`direccion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
					`idcliente` INT
				) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    $pr_create = $connection->prepare($create);
    if($pr_create->execute()){

        $load = "   LOAD DATA INFILE 'C:/xampp/htdocs/COB_ANDINA/documents/loaddireccion/" . $file . "'
                    INTO TABLE $name_table CHARACTER SET UTF8 FIELDS TERMINATED BY '\\t' LINES  TERMINATED BY '\\r\\n' IGNORE 1 LINES";
        $pr = $connection->prepare($load);
        if($pr->execute()){
    		$update="	UPDATE 
						$name_table tmp
						INNER JOIN ca_cliente cli ON tmp.codigo_cliente=cli.codigo
						SET tmp.idcliente=cli.idcliente";
			$prupdate = $connection->prepare($update);
    		if($prupdate->execute()){
    			$sqltmp = "SELECT TRIM(codigo_cliente) AS codigo_cliente,TRIM(departamento) AS departamento,TRIM(provincia) AS provincia,TRIM(distrito) AS distrito,TRIM(direccion) AS direccion FROM $name_table WHERE idcliente <>'' ";
	        	$prtmp = $connection->prepare($sqltmp);
	    		if($prtmp->execute()){
	    			$ar_temp=$prtmp->fetchAll(PDO::FETCH_ASSOC);

	    			for ($i=0; $i <=count($ar_temp)-1 ; $i++) {	 

						$codigo_cliente	= $ar_temp[$i]['codigo_cliente'];
						$departamento	= $ar_temp[$i]['departamento'];
						$provincia		= $ar_temp[$i]['provincia'];
						$distrito		= $ar_temp[$i]['distrito'];
						$direccion 		= $ar_temp[$i]['direccion'];

						$insert="	INSERT IGNORE 
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
									)
						";
						$prinsert = $connection->prepare($insert);
			        	if($prinsert->execute()){
							$unenable=" UPDATE loaddireccion SET pendiente='0' WHERE nombretrans='$file'; ";
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
						loaddireccion
						WHERE
						pendiente=1
						ORDER BY 3 DESC LIMIT 1
					';

$pr_last_fileload = $connection->prepare($sql_last_fileload);
$pr_last_fileload->execute();
$temp_file=$pr_last_fileload->fetchAll(PDO::FETCH_ASSOC);
$temp=$temp_file[0]['nombretrans'];
ProcesarCargaCorreo($temp);

?>