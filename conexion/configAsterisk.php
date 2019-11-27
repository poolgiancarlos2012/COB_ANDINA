<?php

	class configAsterisk {
		
		private $user ;
		private $secret ;
		private $context ;
		
		public function __construct ( ) {
			$this->user='';
			$this->secret='';
			$this->context='';
		}
		
		public function setUser ( $data ) {
			$this->user=$data;
		}
		public function getUser ( ) {
			return $this->user;
		}
		
		public function setSecret ( $data ) {
			$this->secret=$data;
		}
		public function getSecret ( ) {
			return $this->secret;
		}
		
		public function setContext ( $data ) {
			$this->context=$data;
		}
		public function getContext ( ) {
			return $this->context;
		}
		
	}


?>