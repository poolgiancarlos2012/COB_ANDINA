<?php
	
	class dto_telefono {
		
		private $id;
		private $idtipo_telefono;
		private $idreferencia_cliente;
		private $numero;
		private $anexo;
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
		
		public function setIdTipoTelefono ( $valor ) {
			$this->idtipo_telefono=$valor;
		}
		public function getIdTipoTelefono ( ) {
			return $this->idtipo_telefono;
		}
		
		public function setIdReferenciaCliente ( $valor ) {
			$this->idreferencia_cliente=$valor;
		}
		public function getIdReferenciaCliente ( ) {
			return $idreferencia_cliente;
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