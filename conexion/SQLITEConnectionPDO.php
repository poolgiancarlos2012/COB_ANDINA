<?php
	class SQLITEConnectionPDO {
		public static $instance= NULL;
        private function __construct(){}
		private function  __clone() {}
		public static function getInstance( ) {
			if(self::$instance == NULL){
				self::$instance= new self() ;
			}
			return self::$instance;
		}
		
		public function getConnection ( ) {
			$cn = NULL;
			$confCobrast = parse_ini_file('../conf/cobrast.ini',true);
			try{
				$cf=new config();
				//echo "sqlite:".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/db/cobrast.sqlite";
                $cn = new PDO("sqlite:".$confCobrast['ruta_cobrast']['document_root_cobrast']."/".$confCobrast['ruta_cobrast']['nombre_carpeta']."/db/cobrast.sqlite");
			}catch(PDOException $exc){
				echo json_encode(array('rst'=>false,'msg'=>'Error KNCHE0000000160007x16 : COBRAST not found'));
				exit();
			}
			return $cn;
		}
	}

?>