<?php

	class dto_transaccion {
		
		private $id;
		private $idtipo_gestion;
		private $idcliente_cartera;
		private $idfinal;
		private $observacion;
		private $fecha ;
		
		/******/
		
		private $id_peso_transaccion ;
		private $idestado;
		private $idusuario_servicio;
		private $idservicio ;
		/******/
		
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
		
		public function setIdServicio ( $valor ) {
			$this->idservicio = $valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
		public function setIdUsuarioServicio ( $valor ) {
			$this->idusuario_servicio=$valor;
		}
		public function getIdUsuarioServicio ( ) {
			return $this->idusuario_servicio;
		}
		public function setIdEstado ( $valor ) {
			$this->idestado=$valor;
		}
		public function getIdEstado ( ) {
			return $this->idestado;
		}
				
		public function setIdTipoGestion ( $valor ) {
			$this->idtipo_gestion=$valor;
		}
		public function getIdTipoGestion ( ) {
			return $this->idtipo_gestion;
		}
		
		public function setIdFinal ( $valor ) {
			$this->idfinal=$valor;
		}
		public function getIdFinal ( ) {
			return $this->idfinal;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera=$valor;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera;
		}
		
		public function setObservacion ( $valor ) {
			$this->observacion=trim($valor);
		}
		public function getObservacion ( ) {
			return $this->observacion;
		}
		
		public function setFecha ( $valor ) {
			$this->fecha=$valor;
		}
		public function getFecha ( ) {
			return $this->fecha;	
		}
		
		/***/
		public function setIdPesoTransaccion ( $valor ) {
			$this->id_peso_transaccion=$valor;
		}
		public function getIdPesoTransaccion ( ) {
			return $this->id_peso_transaccion;	
		}
		/***/
		
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