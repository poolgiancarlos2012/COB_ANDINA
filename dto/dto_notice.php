<?php

	class dto_notice {
		
		private $id;
		private $titulo;
		private $descripcion;
		private $estado;
		private $usuario_modificacion;
		private $usuario_creacion;
		private $usuario_servicio;
		private $idservicio;
		
		public function setId($valor) {
			$this->id=$valor;
		}
		public function getId() {
			return $this->id;
		}
		
		public function setTitulo ( $valor ) {
			$this->titulo = $valor;
		}
		public function getTitulo ( ) {
			return $this->titulo;
		}
		
		public function setDescripcion($valor) {
			$this->descripcion=$valor;
		}
		public function getDescripcion() {
			return $this->descripcion;
		}
		
		public function setEstado($valor){
			$this->estado=$valor;
		}
		public function getEstado(){
			return $this->estado;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio = $valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
		public function setIdUsuarioServicio ( $valor ) {
			$this->usuario_servicio = $valor;
		}
		public function getIdUsuarioServicio ( ) {
			return $this->usuario_servicio ;
		}
		
		public function setUsuarioModificacion($valor){
			$this->usuario_modificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuario_modificacion;
		}
		
		public function setUsuarioCreacion($valor){
			$this->usuario_creacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuario_creacion;
		}
		
	}

?>