<?php
	
	class dto_factura_digital {
		
		private $id;
		private $correo;
		private $idusuario_servicio;
		private $solicita;
		private $idcliente_cartera;
		private $ruta_absoluta;
		private $ruta_cobrast;
		private $observacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		private $fecha_vencimiento;
		private $idcuenta;
		
		public function setIdcuenta($idcuenta)
		{
			$this->idcuenta = $idcuenta;
		}
		
		public function getIdcuenta()
		{
			return $this->idcuenta;
		}
		
		public function setFechaVencimiento($fecha)
		{
			$this->fecha_vencimiento = $fecha;
		}
		
		public function getFechaVencimiento()
		{
			return $this->fecha_vencimiento;
		}
		
		public function setId ( $valor ) {
			$this->id = $valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setCorreo ( $valor ) {
			$this->correo = $valor;
		}
		public function getCorreo ( ) {
			return $this->correo;
		}
		
		public function setIdUsuarioServicio ( $valor ) {
			$this->idusuario_servicio = $valor;
		}
		public function getIdUsuarioServicio ( ) {
			return $this->idusuario_servicio;
		}
		
		public function setSolicita ( $valor ) {
			$this->solicita = $valor;
		}
		public function getSolicita ( ) {
			return $this->solicita;
		}
		
		public function setIdClienteCartera ( $valor ) {
			$this->idcliente_cartera = $valor;
		}
		public function getIdClienteCartera ( ) {
			return $this->idcliente_cartera;
		}
		
		public function setRutaAbsoluta ( $valor ) {
			$this->ruta_absoluta = $valor;
		}
		public function getRutaAbsoluta ( ) {
			return $this->ruta_absoluta;
		}
		
		public function setRutaCobrast ( $valor ) {
			$this->ruta_cobrast = $valor;
		}
		public function getRutaCobrast ( ) {
			return $this->ruta_cobrast;
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
			return $this->usuario_modificacion;
		}
		
	}
	
?>
