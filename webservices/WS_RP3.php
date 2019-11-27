<?php

	class WS_RP3 {
		
		public function SendResponse ( $data )  {
			
			$confCobrast = parse_ini_file('../conf/cobrast.ini', true);
			
			$action = $confCobrast['RP3']['action']; //http://tempuri.org/RegistrarGestionCartera
			$wsdl = $confCobrast['RP3']['wsdl']; //http://200.48.12.181:83/Operaciones.asmx
			$namespace = $confCobrast['RP3']['namespace']; //"http://tempuri.org/"
			$usuario = $confCobrast['RP3']['usuario']; //userHdec
			$proveedor = $confCobrast['RP3']['proveedor']; //H
			$empresa = $confCobrast['RP3']['empresa']; //1 
			
			$parse = '';
			for( $i=0;$i<count($data);$i++ ) {
				$data_p = array();
				foreach( $data[$i] as $index => $value ){
					array_push( $data_p, $index.' = "'.str_replace('"','',$value).'"' );
				}
				
				$parse .= '<Contacto '.implode(' ',$data_p).'  /> ' ;
			}
			
			$xml = '';
			$xml .= '<RegistrarGestionCartera xmlns="'.$namespace.'">';
				$xml .= '<req>';
					$xml .= '<Empresa>'.$empresa.'</Empresa>';
					$xml .= '<Proveedor>'.$proveedor.'</Proveedor>';
					$xml .= '<Xml>';
						$xml .= '<Gestion_Cobranza>'.$parse.'</Gestion_Cobranza>';
					$xml .= '</Xml>';
					$xml .= '<Usuario>'.$usuario.'</Usuario>';
				$xml .= '</req>';
			$xml .= '</RegistrarGestionCartera>';
			
			$client = new nusoap_client($wsdl);
			
			$msg = $client->serializeEnvelope($xml,'',array(),'document','literal');
			
			$response = $client->send( $msg, $action ); 
			
			
			
		}
		
	}


?>