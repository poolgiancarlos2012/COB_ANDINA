<?php
	
	class dto_telefono_ER2 {
		
		private $id;
		private $idcliente;
		private $idcliente_cartera;
		private $idcartera;
		private $idorigen;
		private $idtipo_referencia;
		private $idtipo_telefono;
		private $idlinea_telefono;
		private $numero;
		private $anexo;
		private $observacion;
		private $fecha_creacion;
		private $fecha_modificacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		private $status;
		private $codigo_cliente;
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
		
		public function setCodigoCliente ( $valor ) {
			$this->codigo_cliente = $valor;
		}
		public function getCodigoCliente ( ) {
			return $this->codigo_cliente ;
		}
		
		public function setStatus ( $valor ) {
			$this->status = $valor;
		}
		public function getStatus ( ) {
			return $this->status;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera = $valor;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera ;
		}
		
		public function setIdCliente ( $valor ) {
			$this->idcliente=$valor;
		}
		public function getIdCliente ( ) {
			return 	$this->idcliente;
		}
		
		public function setIdLineaTelefono ( $valor ) {
			$this->idlinea_telefono=$valor;
		}
		public function getIdLineaTelefono ( ) {
			return $this->idlinea_telefono;
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
		
		public function setIdTipoTelefono ( $valor ) {
			$this->idtipo_telefono=$valor;
		}
		public function getIdTipoTelefono ( ) {
			return $this->idtipo_telefono;
		}
		
		public function setNumero ( $valor ) {
			$this->numero=$valor;
		}
		public function getNumero ( ) {
			return $this->numero;
		}
		
		public function setAnexo ( $valor ) {
			$this->anexo=$valor;
		}
		public function getAnexo ( ) {
			return $this->anexo;
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