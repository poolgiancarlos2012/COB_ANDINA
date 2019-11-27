<?php

	class dto_nota {
		
		private $id;
		private $fecha;
		private $descripcion;
		private $idcliente_cartera;
		private $estado;
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
		
		public function setFecha ( $valor ) {
			$this->fecha=$valor;
		}
		public function getFecha ( ) {
			return $this->fecha;
		}
		
		public function setDescripcion ( $valor ) {
			$this->descripcion=$valor;
		}
		public function getDescripcion ( ) {
			return $this->descripcion;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera=$valor;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera;	
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;	
		}
		
		public function setUsuarioCreacion ( $valor ) {
			$this->usuario_creacion=$valor;
		}
		public function getUsuarioCreacion ( ) {
			return $this->usuario_creacion;	
		}
		
		public function setUsuarioModificacion ( $valor ) {
			$this->usuario_modificacion=$valor;
		}
		public function getUsuarioModificacion ( ) {
			return $this->usuario_modificacion;	
		}
		
		public function setFechaCreacion ( $valor ) {
			$this->fecha_creacion=$valor;
		}
		public function getFecahCreacion ( ) {
			return $this->fecha_creacion;	
		}
		
		public function setFechaModificacion ( $valor ) {
			$this->fecha_modificacion=$valor;
		}
		
		public function getFechaModificacion ( ) {
			return $this->fecha_modificacion;
		}
			
	}

?>