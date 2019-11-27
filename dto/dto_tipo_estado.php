<?php

	class dto_tipo_estado {
		
		private $id;
		private $nombre;
		private $idservicio;	
		private $idtipo_transaccion ;
		
		public function setId( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setNombre ( $valor ) {
			$this->nombre=$valor;
		}
		public function getNombre ( ) {
			return $this->nombre;
		}
		
		public function setIdTipoTransaccion ( $valor ) {
			$this->idtipo_transaccion=$valor;
		}
		public function getIdTipoTransaccion ( ) {
			return $this->idtipo_transaccion;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
			
	}

?>