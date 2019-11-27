<?php
	
	class dto_pago {
		
		private $id;
		private $idcartera;
		private $idcartera_pago;
		private $iddetalle_cuenta;
		private $codigo_operacion;
		private $numero_cuenta;
		private $moneda;
		private $monto_pagado;
		private $fecha;
		private $agencia;
		private $observacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		private $estado_pago;
		private $idcliente_cartera;
		private $codigo_cliente;
		
		public function setId ( $valor ) {
			$this->id = $valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setCodigoCliente ( $valor ) {
			$this->codigo_cliente = $valor ;
		}
		public function getCodigoCliente ( ) {
			return $this->codigo_cliente;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera = $valor ;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera;
		}
		
		public function setEstadoPago ( $valor ) {
			$this->estado_pago = $valor;
		}
		public function getEstadoPago ( ) {
			return $this->estado_pago;
		}
		
		public function setIdCarteraPago ( $valor ) {
			$this->idcartera_pago = $valor;
		}
		public function getIdCarteraPago ( ) {
			return $this->idcartera_pago;
		}
		
		public function setIdCartera ( $valor ) {
			$this->idcartera = $valor;
		}
		public function getIdCartera ( ) {
			return $this->idcartera;
		}
		
		public function setIdDetalleCuenta ( $valor ) {
			$this->iddetalle_cuenta = $valor;
		}
		public function getIdDetalleCuenta ( ) {
			return $this->iddetalle_cuenta;
		}
		
		public function setCodigoOperacion ( $valor ) {
			$this->codigo_operacion = $valor;
		}
		public function getCodigoOperacion ( ) {
			return $this->numero_cuenta ;
		}
		
		public function setNumeroCuenta ( $valor ) {
			$this->numero_cuenta = $valor;
		}
		public function getNumeroCuenta ( ) {
			return $this->numero_cuenta;
		}
		
		public function setMoneda ( $valor ) {
			$this->moneda = $valor;
		}
		public function getMoneda ( ) {
			return $this->moneda;
		}
		
		public function setMontoPagado ( $valor ) {
			$this->monto_pagado = $valor;
		}
		public function getMontoPagado ( ) {
			return $this->monto_pagado;
		}
		
		public function setFecha ( $valor ) {
			$this->fecha = $valor;
		}
		public function getFecha ( ) {
			return $this->fecha;
		}
		
		public function setAgencia ( $valor ) {
			$this->agencia = $valor;
		}
		public function getAgencia ( ) {
			return $this->agencia;
		}
		
		public function setObservacion ( $valor ) {
			$this->observacion = $valor;
		}
		public function getObservacion ( ) {
			return $this->observacion;
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
			return $this->usuario_modificacion ;
		}
		
	}
	
?>