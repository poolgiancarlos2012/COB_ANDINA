<?php

	class dto_estado_llamada {
		private $id;
		private $nombre;
		private $idservicio;
		private $peso;
		private $descripcion;
		private $estado;
		
		public function setId ( $valor ) {
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
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;	
		}
		
		public function setPeso ( $valor ) {
			$this->peso=$valor;
		}
		public function getPeso ( ) {
			return $this->peso;
		}
		
		public function setDescripcion ( $valor ) {
			$this->descripcion=$valor;
		}
		public function getDescripcion ( ) {
			return $this->descripcion;
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;	
		}
		 	
	}

?>