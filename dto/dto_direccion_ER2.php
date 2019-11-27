<?php
	
	class dto_direccion_ER2 {
		
		private $id;
		private $idcliente;
		private $idcliente_cartera;
		private $idcartera;
		private $idorigen;
		private $idtipo_referencia;
		private $direccion;
		private $referencia;
		private $ubigeo;
		private $departamento;
		private $provincia;
		private $distrito;
		private $observacion;
		private $fecha_creacion;
		private $fecha_modificacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		private $codigo_cliente;
		private $status;
		private $is_campo;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}

		public function setIsCampo ( $valor ) {
			$this->is_campo = $valor;
		}
		public function getIsCampo ( ) {
			return $this->is_campo;
		}
		
		public function setStatus ( $valor ) {
			$this->status = $valor;
		}
		public function getStatus ( ) {
			return $this->status ;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera = $valor;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera ;
		}
		
		public function setCodigoCliente ( $valor ) {
			$this->codigo_cliente = $valor;
		}
		public function getCodigoCliente ( ) {
			return $this->codigo_cliente;
		}
		
		public function setIdCliente ( $valor ) {
			$this->idcliente=$valor;
		}
		public function getIdCliente ( ) {
			return 	$this->idcliente;
		}
		
		public function setIdCartera ( $valor ) {
			$this->idcartera=$valor;
		}
		public function getIdCartera ( ) {
			return $this->idcartera;	
		}
		
		public function setIdOrigen ( $valor ) {
			$this->idorigen=$valor;
		}
		public function getIdOrigen ( ) {
			return $this->idorigen;
		}
		
		public function setIdTipoReferencia ( $valor ) {
			$this->idtipo_referencia=$valor;
		}
		public function getIdTipoReferencia ( ) {
			return $this->idtipo_referencia;	
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