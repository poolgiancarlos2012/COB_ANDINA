<?php

	class dto_refinanciamiento {
	
		private $id;
		private $fecha;
		private $idcliente_cartera;
		private $idusuario_servicio;
		private $idtelefono;
		private $observacion;
		
		private $objecion ;
		private $tipo_cuota;
		private $idfinal ;
		private $total_deuda ;
		private $numero_cuota ;
		private $monto_cuota ;
		
		private $fechaModificacion;
		private $fechaCreacion;
		private $usuarioModificacion;
		private $usuarioCreacion;
                
                private $descuento;
                private $numero_cuenta;
                private $moneda;
                private $idcliente;
                
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setIdTelefono ( $valor ) {
			$this->idtelefono = $valor;
		}
		public function getIdTelefono ( ) {
			return $this->idtelefono ;
		}
		
		public function setTipoCuota ( $valor ) {
			$this->tipo_cuota = $valor;
		}
		public function getTipoCuota ( ) {
			return $this->tipo_cuota;
		}
		
		public function setObjecion ( $valor ) {
			$this->objecion = $valor;
		}
		public function getObjecion ( ) {
			return $this->objecion ;
		}
		
		public function setIdFinal ( $valor ) {
			$this->idfinal = $valor ;
		}
		public function getIdFinal ( ) {
			return $this->idfinal ;
		}
		
		public function setMontoCuota ( $valor ) {
			$this->monto_mora = $valor ;
		}
		public function getMontoCuota ( ) {
			return $this->monto_mora ;
		}
		
		public function setNumeroCuota ( $valor ) {
			$this->numero_cuota = $valor ;
		}
		public function getNumeroCuota ( ) {
			return $this->numero_cuota ;
		}
		
		public function setTotalDeuda( $valor ){
			$this->total_deuda = $valor ;
		}
		public function getTotalDeuda ( ) {
			return $this->total_deuda ;
		}

		public function setIdUsuarioServicio ( $valor ) {
			$this->idusuario_servicio=$valor;
		}
		public function getIdUsuarioServicio ( ) {
			return $this->idusuario_servicio;
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

		public function setFechaModificacion($valor){
			$this->fechaModificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fechaModificacion;
		}

		public function setFechaCreacion($valor){
			$this->fechaCreacion=$valor;
		}
		public function getFechaCreacion(){
			return $this->fechaCreacion;
		}

		public function setUsuarioModificacion($valor){
			$this->usuarioModificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuarioModificacion;
		}

		public function setUsuarioCreacion($valor){
			$this->usuarioCreacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuarioCreacion;
		}	
		public function setDescuento($valor){
			$this->descuento=$valor;
		}
		public function getDescuento(){
			return $this->descuento;
		}	                
		public function setNumeroCuenta($valor){
			$this->numero_cuenta=$valor;
		}
		public function getNumeroCuenta(){
			return $this->numero_cuenta;
		}	                                
		public function setMoneda($valor){
			$this->moneda=$valor;
		}
		public function getMoneda(){
			return $this->moneda;
		}	                                                
		public function setIdCliente($valor){
			$this->idcliente=$valor;
		}
		public function getIdCliente(){
			return $this->idcliente;
		}	                                                                
		
	}

?>