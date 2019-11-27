<?php

	class dto_tramo {
		
		private $id;
		private $tramo;
		private $porcentaje;
		private $tipo;
		private $idservicio;
		private $fechaModificacion;
		private $fechaCreacion;
		private $usuarioModificacion;
		private $usuarioCreacion;
		
		public function setId($valor){
			$this->id=$valor;
		}
		public function getId(){
			return $this->id;
		}
		
		public function setTramo ( $valor ) {
			$this->tramo=$valor;
		}
		public function getTramo ( ) {
			return $this->tramo;
		}
		
		public function setPorcentaje ( $valor ) {
			$this->porcentaje=$valor;
		}
		public function getPorcentaje ( ) {
			return $this->porcentaje;
		}
		
		public function setTipo ( $valor ) {
			$this->tipo=$valor;
		}
		public function getTipo ( ) {
			return $this->tipo;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor; 
		}
		public function getIdServicio ( ) { 
			return $this->idservicio;  
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