<?php


	class MYSQLPagoRP3DAO {
		
		public function loadFilePagos ( $tipo , $data_car, $nombre_servicio ) {
			
			$field_table = " pagos ";
			
			if( $tipo == 'comercial' ) {
				$field_table = " pagos ";
			}else if( $tipo == 'banco' ) {
				$field_table = " pagosBanco ";
			}
			
			$c_data = array();
			for( $i=0;$i<count($data_car);$i++ ) {
				array_push( $c_data, "'".$data_car[$i]['datetime'].",".$data_car[$i]['grupo']."'" );
			}
			
			if (!@opendir('../documents/carteras/'.$nombre_servicio)) {
				
				if (@mkdir('../documents/carteras/' . $nombre_servicio )) {
	                
	            } else {
	                return array('rst' => false, 'msg' => 'Error al crear directorio');
	                exit();
	            }
	        }
	        
	        $name_file = "TMP_P_RP3_".rand(1, 1000)."_".date("Y_m_d_H_i_s").".txt";
	        $path = "../documents/carteras/".$nombre_servicio . "/" . $name_file;
			if (file_exists($path)) {
			    return array('rst' => false, 'msg' => 'Archivo ya existe');
			    exit();
			}
			
			$Archivo = @fopen($path, 'a+');
			
			if (!$Archivo) {
				return array('rst' => false, 'msg' => 'Problemas al crear archivo');
			    exit();
	        }
			                
			$buscar = array("เ", "แ", "ภ", "ม", "่", "้", "ศ", "ษ", "์", "ํ", "ฬ", "อ", "๒", "๓", "า", "ำ", "๙", "๚", "ู", "ฺ", "#", "๑", "ั", "$", "&", "%", "'", '"', "?", "!", "ยก", "ยฅ","\t","|","\n","\r\n","\r");
			$cambia = array("a", "a", "A", "A", "e", "e", "E", "E", "i", "i", "I", "I", "o", "o", "O", "O", "u", "u", "U", "U", " ", "n", "N", " ", " ", " ", "", '', "", "", "", "N"," "," ","","","");
			
			$sql = " SELECT 
				GRP_FACT, FEC_FACT, NUM_CTA, D_MORA, PMIN1, EFECTIVIDAD,
				PARA_GESTION, TIPO_PAGO, TOT_PAGOS, FECHA_DE_PAGO 
				FROM $field_table  
				WHERE CONCAT( DATESYSTEM,',', GRP_FACT ) IN ( ".implode(",",$c_data)." ) ";
			
			$factoryConnection = FactoryConnection::create('mysql');
        	$connection = $factoryConnection->getConnection("","", new configRP3 );
			
        	$pr = $connection->prepare($sql); 
        	$pr->bindParam(1, $fecha,PDO::PARAM_STR); 
        	$pr->execute();
        	$count = 0;
        	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
        		
        		if( $count == 0 ) {
        			
        			$header = array();
        			
        			foreach( $row as $index => $value  ) {
        				array_push($header,$index);
        			}
        			
        			fwrite($Archivo, implode("\t",$header) . "\r\n");
        		}
        		
        		$data = array();
        			
        		foreach( $row as $index => $value  ) {
        			$cell = str_replace($buscar, $cambia, trim($value));
        			array_push($data,$cell);
        		}
        			
        		fwrite($Archivo, implode("\t",$data) . "\r\n");
        		
        		$count++;
        	}
			
        	fclose($Archivo);
			return array('rst' => true, 'msg' => 'Pagos creado correctamente', 'file' => $name_file);
			
		}
		
		public function queryByDateCommerce ( $fecha_envio_inicio, $fecha_envio_fin ) {
			
			$sql = " SELECT 
        			DATE(DATESYSTEM) AS FECHA, TIME(DATESYSTEM) AS HORA, GRP_FACT AS GRUPO, COUNT(*) AS CANTIDAD
					FROM pagos  
					WHERE DATE(DATESYSTEM) BETWEEN ? AND ? 
					GROUP BY DATESYSTEM, GRP_FACT ORDER BY GRP_FACT ";

        	$factoryConnection = FactoryConnection::create('mysql');
        	$connection = $factoryConnection->getConnection("","", new configRP3 );

        	$pr = $connection->prepare($sql);
        	$pr->bindParam(1, $fecha_envio_inicio,PDO::PARAM_STR);
        	$pr->bindParam(2, $fecha_envio_fin,PDO::PARAM_STR);
        	if ($pr->execute()) {
	            return $pr->fetchAll(PDO::FETCH_ASSOC);
        	} else {
	            return array();
        	}
			
		}
		
		public function queryByDateBank ( $fecha_envio_inicio, $fecha_envio_fin ) {
			
			$sql = " SELECT 
        			DATE(DATESYSTEM) AS FECHA, TIME(DATESYSTEM) AS HORA, GRP_FACT AS GRUPO, COUNT(*) AS CANTIDAD
					FROM pagosBanco  
					WHERE DATE(DATESYSTEM) BETWEEN ? AND ? 
					GROUP BY DATESYSTEM, GRP_FACT ORDER BY GRP_FACT ";

        	$factoryConnection = FactoryConnection::create('mysql');
        	$connection = $factoryConnection->getConnection("","", new configRP3 );

        	$pr = $connection->prepare($sql);
        	$pr->bindParam(1, $fecha_envio_inicio,PDO::PARAM_STR);
        	$pr->bindParam(2, $fecha_envio_fin,PDO::PARAM_STR);
        	if ($pr->execute()) {
	            return $pr->fetchAll(PDO::FETCH_ASSOC);
        	} else {
	            return array();
        	}
			
		}
		
	}	


?>