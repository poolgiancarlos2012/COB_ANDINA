<?php

	class dto_evento {
		
		private $id;
		private $evento;
		private $fecha;
		private $fecha_fin;
		private $hora;
		private $estado;
		private $idusuario_servicio;
		private $fecha_creacion;
		private $fecha_modificacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;	
		}
		
		public function setEvento ( $valor ) {
			$this->evento=$valor;
		}
		public function getEvento ( ) {
			return $this->evento;
		}
		
		public function setFecha ( $valor ) {
			$this->fecha=$valor;
		}
		public function getFecha ( ) {
			return $this->fecha;	
		}
		
		public function setFechaFin ( $valor ) {
			$this->fecha_fin=$valor;
		}
		public function getFechaFin ( ) {
			return $this->fecha_fin;	
		}
		
		public function setHora ( $valor ) {
			$this->hora=$valor;
		}
		public function getHora ( ) {
			return $this->hora;	
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;
		}
		
		public function setIdUsuarioServicio ( $valor ) {
			$this->idusuario_servicio=$valor;
		}
		public function getIdUsuarioServicio ( ) {
			return $this->idusuario_servicio;	
		}
		
		public function setFechaModificacion ( $valor ) {
        	$this->fecha_modificacion=$valor;
		}
		public function getFechaModificacion ( ) {
			return $this->fecha_modificacion;
		}
		
		public function setFechaCreacion ( $valor ) {
        	$this->fecha_creacion=$valor;
		}
		public function getFechaCreacion ( ) {
			return $this->fecha_creacion;
		}
		
		public function setUsuarioModificacion ( $valor ) {
			$this->usuario_modificacion=$valor;
		}
		public function getUsuarioModificacion ( ) {
			return $this->usuario_modificacion;
		}
		
		public function setUsuarioCreacion ( $valor ) {
			$this->usuario_creacion=$valor;
		}
		public function getUsuarioCreacion ( ) {
			return $this->usuario_creacion;
		}
			
	}
	
?>