<?php

	class dto_correo {
		
		private $id;
		private $correo;
		private $observacion;
		private $idcliente;
		private $estado;
		private $fechaModificacion;
		private $fechaCreacion;
		private $usuarioModificacion;
		private $usuarioCreacion;
		
		public function setId($valor) {
	        $this->id=$valor;
		}
		public function getId() {
			return $this->id;
		}
		
		public function setCorreo ( $valor ) {
			$this->correo = $valor;
		}
		public function getCorreo ( ) {
			return $this->correo;
		}
		
		public function setObservacion ( $valor ) {
			$this->observacion = $valor;
		}
		public function getObservacion ( ) {
			return $this->observacion;
		}
		
		public function setIdCliente ( $valor ) {
			$this->idcliente=$valor;
		}
		public function getIdCliente ( ) {
			return $this->idcliente;
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;
		}
		
		public function setFechaModificacion($valor){
			$this->fechaModificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fechaModificacion;
		}
	
		public function setFechaCreacion($valor){
			$this->fechaCreacion=$valor;
		}
		public function getFechaCreacion(){
			return $this->fechaCreacion;
		}
	
		public function setUsuarioModificacion($valor){
			$this->usuarioModificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuarioModificacion;
		}
	
		public function setUsuarioCreacion($valor){
			$this->usuarioCreacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuarioCreacion;
		}
		
	}

?>