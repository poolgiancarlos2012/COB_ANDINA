<?php

	class ConnectionMemcached {
		
		private $link;
		
		static $_instance;
		
		public function __construct ( ) {
			$this->connection(); 
		}
		
		private function __clone ( ) { }
		
		public function connection ( ) {
			
			$config = new configMemcached; 
			$server = $config->getServer(); 
			$this->link = memcache_connect($server);
			
		}
		
		public static function getInstance ( ) {
			if( self::$_instance==NULL ) {
				self::$_instance=new self();
			}
			return self::$_instance;
		}
		
		public function setValue ( $key, $value ) {
			return  memcache_set( $this->link, $key, $value );
		}
		public function getValue ( $key ) {
			return memcache_get( $this->link, $key );
		}
		public function delete ( $key ) {
			return memcache_delete( $this->link, $key );
		}
		public function close ( ) {
			return memcache_close( $this->link );
		}
		public function replace ( $key, $value ) {
			return memcache_replace( $this->link, $key, $value );
		}
	}

?>