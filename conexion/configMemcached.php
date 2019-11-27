<?php

	class configMemcached {
		
		private  $server ;
		
		public function __construct ( ) {
			$this->server='localhost';
		}
		
		public function setServer ( $data ) {
			$this->server=$data;
		}
		public function getServer ( ) {
			return $this->server;
		}
		
	}

?>