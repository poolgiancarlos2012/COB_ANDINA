<?php

	class dto_peso_transaccion {
		
		private $id;
		private $peso;
		private $idestado_transaccion;
		private $fechaCreacion;
		private $fechaModificacion;
		private $usuarioCreacion;
		private $usuarioModificacion;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;	
		}
		
		public function setPeso ( $valor ) {
			$this->peso=$valor;
		}
		public function getPeso ( ) {
			return $this->peso;
		}
		
		public function setIdEstadoTransaccion ( $valor ) {
			$this->idestado_transaccion=$valor;
		}
		public function getIdEstadoTransaccion ( ) {
			return $this->idestado_transaccion;
		}
		
		public function setFechaCreacion($valor){
			$this->fechaCreacion=$valor;
		}
		public function getFechaCreacion(){
			return $this->fechaCreacion;
		}
	
		public function setFechaModificacion($valor){
			$this->fechaModificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fechaModificacion;
		}
	
		public function setUsuarioCreacion($valor){
			$this->usuarioCreacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuarioCreacion;
		}
	
		public function setUsuarioModificacion($valor){
			$this->usuarioModificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuarioModificacion;
		}
			
	}

?>