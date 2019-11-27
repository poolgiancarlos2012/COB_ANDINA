<?php
	
	class dto_direccion {
		
		private $id;
		private $idreferencia_cliente;
		private $direccion;
		private $referencia;
		private $ubigeo;
		private $departamento;
		private $provincia;
		private $distrito;
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
		
		public function setIdReferenciaCliente ( $valor ) {
			$this->idreferencia_cliente=$valor;
		}
		public function getIdReferenciaCliente ( ) {
			return $this->idreferencia_cliente;
		}
		
		public function setDireccion ( $valor ) {
			$this->direccion=$valor;
		}
		public function getDireccion ( ) {
			return $this->direccion;
		}
		
		public function setReferencia ( $valor ) {
			$this->referencia=$valor;
		}
		public function getReferencia ( ) {
			return $this->referencia;
		}
		
		public function setUbigeo ( $valor ) {
			$this->ubigeo=$valor;
		}
		public function getUbigeo ( ) {
			return $this->ubigeo;
		}
		
		public function setDepartamento ( $valor ) {
			$this->departamento=$valor;
		}
		public function getDepartamento ( ) {
			return $this->departamento;
		}
		
		public function setProvincia ( $valor ) {
			$this->provincia=$valor;
		}
		public function getProvincia ( ) {
			return $this->provincia;
		}
		
		public function setDistrito ( $valor ) {
			$this->distrito=$valor;
		}
		public function getDistrito ( )	{
			return $this->distrito;
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