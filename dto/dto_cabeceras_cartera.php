<?php

	class dto_cabeceras_cartera {
		
		private $id;
		private $cabeceras;
		private $idservicio;
		private $usuario_creacion;
		private $nombre;
		private $tipo;
		private $usuario_modificacion;
		private $fecha_creacion;
		private $fecha_modificacion;
		
		public function setId ( $valor ) {
			$this->id = $valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio = $valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
		public function setCabeceras ( $valor ) {
			$this->cabeceras = $valor;
		}
		public function getCabeceras ( )  {
			return $this->cabeceras;
		}
		
		public function setNombre ( $valor ) {
			$this->nombre = $valor;
		}
		public function getNombre ( ) {
			return $this->nombre;
		}
		
		public function setTipo ( $valor ) {
			$this->tipo = $valor;
		}
		public function getTipo ( ) {
			return $this->tipo;
		}
		
		public function setFechaCreacion ( $valor ) {
			$this->cabeceras = $valor;
		}
		public function getFechaCreacion ( ) {
			return $this->fecha_creacion;
		}
		
		public function setFechaModificacion ( $valor ) {
			$this->fecha_modificacion = $valor;
		}
		public function getFechaModificacion ( ) {
			return $this->fecha_modificacion;
		}
		
		public function setUsuarioCreacion ( $valor ) {
			$this->usuario_creacion = $valor;
		}
		public function getUsuarioCreacion ( ) {
			return $this->usuario_creacion;
		}
		
		public function setUsuarioModificacion ( $valor ) {
			$this->usuario_modificacion = $valor;
		}
		public function getUsuarioModificacion ( ) {
			return $this->usuario_modificacion;
		}
		
	}

?> 