<?php


	class MYSQLCarteraRP3DAO {
		
		public function loadFileCartera ( $tipo , $data_car, $nombre_servicio ) {
			
			$field_table = " cartera ";
			
			if( $tipo == 'comercial' ) {
				$field_table = " cartera ";
			}else if( $tipo == 'banco' ) {
				$field_table = " carteraBanco ";
			}
			
			$c_data = array();
			for( $i=0;$i<count($data_car);$i++ ) {
				array_push( $c_data,"'".$data_car[$i]['secuencia'].",".$data_car[$i]['datetime']."'" );
			}
			
			if (!@opendir('../documents/carteras/'.$nombre_servicio)) {
				
				if (@mkdir('../documents/carteras/' . $nombre_servicio )) {
	                
	            } else {
	                return array('rst' => false, 'msg' => 'Error al crear directorio');
	                exit();
	            }
	        }
	        
	        $name_file = "TMP_C_RP3_".rand(1, 1000)."_".date("Y_m_d_H_i_s").".txt";
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
				SECUENCIA,CLI_UNICO,DOCUMENTO,NUM_CTA,PAN,GRP_FACT,
				FEC_FACT,NOM_COMPLETO,NOM_SIS,PMIN1,PMIN2,SAL_FACT1,SAL_FACT2,SEGMENTO,DIAS_MORA,
				TEL1,TEL2,TEL3,TEL4,TEL5,TEL6,TEL7,TEL8,TEL_EMPRESA,ANX_EMPRESA,
				DIR_DOMICILIO,DISTRITO_DOMICILIO,EMPRESA,DIR_EMPRESA,DISTRITO_EMPRESA,
				DIR_SIS,DISTRITO_SIS,DIR_ADI1,DISTRITO_ADI1,
				REF1,REF1_TEL,REF2,REF2_TEL,
				FLG_EMP_COBRANZA,MARCA_REF,FLG_ENTREGA
				FROM $field_table  
				WHERE CONCAT(SECUENCIA,',',DATESYSTEM) IN ( ".implode(",",$c_data)." ) ";
			
			$factoryConnection = FactoryConnection::create('mysql');
        	$connection = $factoryConnection->getConnection("","", new configRP3 );
			
        	$pr = $connection->prepare($sql);
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
			return array('rst' => true, 'msg' => 'Cartera creada correctamente', 'file' => $name_file);
        	
		}
		
		public function queryByDateCommerce ( $fecha_envio_inicio, $fecha_envio_fin ) {
			
			$sql = " SELECT 
        			SECUENCIA, DATE(DATESYSTEM) AS FECHA, TIME(DATESYSTEM) AS HORA, COUNT(*) AS CANTIDAD
					FROM cartera 
					WHERE DATE(DATESYSTEM) BETWEEN ? AND ? 
					GROUP BY SECUENCIA , DATESYSTEM ";

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
        			SECUENCIA, DATE(DATESYSTEM) AS FECHA, TIME(DATESYSTEM) AS HORA, COUNT(*) AS CANTIDAD
					FROM carteraBanco 
					WHERE DATE(DATESYSTEM) BETWEEN ? AND ? 
					GROUP BY SECUENCIA , DATESYSTEM ";

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