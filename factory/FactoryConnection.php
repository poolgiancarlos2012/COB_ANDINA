<?php
	class FactoryConnection {
		
		public function __construct ( ) { }
		
		public static function create ( $tipo ) {
			$cn = NULL ;
			switch ($tipo) :
				case 'mysql' :
	                $cn = MYSQLConnectionPDO::getInstance();
				break;
                case 'mysqli':
                	$cn=MYSQLConnectionMYSQLI::getInstance();
                break;
				case 'sqlite':
                	$cn=SQLITEConnectionPDO::getInstance();
                break;
				case 'postgres_pdo':
					$cn = PGSQLConnectionPDO::getInstance();
				break;
			endswitch;
			
			return $cn;
		}
		
	}
?>