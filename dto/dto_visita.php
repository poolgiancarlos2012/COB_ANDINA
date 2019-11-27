<?php

	class dto_visita {
		
		private $id;
		private $idDireccion; 
		private $fechaModificacion;
		private $fechaCreacion;
		private $usuarioModificacion;
		private $usuarioCreacion;
		private $fecha_visita;
		private $fecha_recepcion;
		private $hora_ubicacion;
		private $hora_salida;
		private $idnotificador;
		private $idcontacto;
		private $nombre_contacto;
		private $idmotivo_no_pago;
		private $idparentesco;
		private $descripcion_inmueble;
		private $observacion;
		private $fecha_cp;
		private $monto_cp;
		private $idCuenta;//jmore201208		

		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;	
		}
		
		public function setMontoCp ( $valor ) {
			$this->monto_cp = $valor;
		}
		public function getMontoCp ( ) {
			return $this->monto_cp;
		}
		
		public function setFechaCp ( $valor ) {
			$this->fecha_cp = $valor;
		}
		public function getFechaCp ( ) {
			return $this->fecha_cp;
		}
		
		public function setObservacion ( $valor ) {
			$this->observacion = $valor;
		}
		public function getObservacion ( ) {
			return $this->observacion;
		}
		
		public function setHoraSalida ( $valor ) {
			$this->hora_salida = $valor ; 
		}
		public function getHoraSalida ( ) {
			return $this->hora_salida ; 
		}
		
		public function setHoraUbicacion ( $valor ) {
			$this->hora_ubicacion = $valor;
		}
		public function getHoraUbicacion ( ) {
			return $this->hora_ubicacion ;
		}
		
		public function setIdParentesco ( $valor ) {
			$this->idparentesco=$valor;
		}
		public function getIdParentesco ( ) {
			return $this->idparentesco;	
		}
		
		public function setIdMotivoNoPago ( $valor ) {
			$this->idmotivo_no_pago = $valor;
		}
		public function getIdMotivoNoPago ( ) {
			return $this->idmotivo_no_pago;
		}
		
		public function setNombreContacto ( $valor ) {
			$this->nombre_contacto = $valor;
		}
		public function getNombreContacto ( ) {
			return $this->nombre_contacto;
		}
		
		public function setIdContacto ( $valor ) {
			$this->idcontacto = $valor;
		}
		public function getIdContacto ( ) {
			return $this->idcontacto;
		}
		
		public function setDescripcionInmueble ( $valor ) {
			$this->descripcion_inmueble = $valor;
		}
		public function getDescripcionInmueble ( ) {
			return $this->descripcion_inmueble;
		}
		
		public function setFechaVisita ( $valor ) {
			$this->fecha_visita=$valor;
		}
		public function getFechaVisita ( ) {
			return $this->fecha_visita;
		}
		
		public function setFechaRecepcion ( $valor ) {
			$this->fecha_recepcion=$valor;
		}
		public function getFechaRecepcion ( ) {
			return $this->fecha_recepcion;
		}
		
		public function setIdNotificador ( $valor ) {
			$this->idnotificador=$valor;
		}
		public function getIdNotificador ( ) {
			return $this->idnotificador;
		}
		
		public function setIdDireccion ( $valor ) {
			$this->idDireccion=$valor;
		}
		public function getIdDireccion ( ) {
			return $this->idDireccion;
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
		}/*jmore201208*/
		public function setIdcuenta($valor){
			$this->idCuenta=$valor;
		}
		public function getIdcuenta(){
			return $this->idCuenta;
		}
		public function setIdfinal($valor){
			$this->idCuenta=$valor;
		}
		public function getIdfinal(){
			return $this->idCuenta;
		}
			
	}

?>