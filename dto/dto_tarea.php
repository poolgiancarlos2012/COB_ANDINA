<?php

	class dto_tarea {
		
		private $id;
		private $titulo;
		private $fecha;
		private $hora;
		private $estado;
		private $idusuario_servicio;
		private $nota;
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
		
		public function setTitulo ( $valor ) {
			$this->titulo=$valor;
		}
		public function getTitulo ( ) {
			return $this->titulo;
		}
		
		public function setFecha ( $valor ) {
			$this->fecha=$valor;
		}
		public function getFecha ( ) {
			return $this->fecha;	
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
		
		public function setNota ( $valor ) {
			$this->nota=$valor;
		}
		public function getNota ( ) {
			return $this->nota;
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