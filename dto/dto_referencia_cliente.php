<?php

	class dto_referencia_cliente {
	
		private $id;
		private $idorigen;
		private $idclase;
		private $idtipo_referencia;
		private $idcliente;
		private $estado;
		private $observacion;
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
		
		public function setIdOrigen ( $valor ) {
			$this->idorigen=$valor;
		}
		public function getIdOrigen ( ) {
			return $this->idorigen;
		}
		
		public function setIdClase ( $valor ) {
			$this->idclase=$valor;
		}
		public function getIdClase ( ) {
			return $this->idclase;
		}
		
		public function setIdTipoReferencia ( $valor ) {
			$this->idtipo_referencia=$valor;
		}
		public function getIdTipoReferencia ( ) {
			return $this->idtipo_referencia;
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
		
		public function setObservacion ( $valor ) {
			$this->observacion=$valor;
		}
		public function getObservacion ( ) {
			return $this->observacion;
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