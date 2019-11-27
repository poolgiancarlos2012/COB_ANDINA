<?php
	
	class dto_llamada {
		private $id;
		private $fecha;
		private $idtransaccion;
		private $idtelefono;
		/****/
		private $idpeso_transaccion;
		/****/
		private $tmo_inicio;
		private $tmo_fin;
		private $enviar_campo;
		private $idcontacto ;
		private $nombre_contacto;
		private $idmotivo_no_pago ;
		private $idcarga_final ;
		private $idparentesco ;
		private $idfinal ;
		/****/
		//private $idpeso_llamada;
		private $fechaModificacion;
		private $fechaCreacion;
		private $usuarioModificacion;
		private $usuarioCreacion;
		private $fechacp;
		private $montocp;
		private $observaciones;
		private $caller_id;

		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setIdFinal ( $valor ) {
			$this->idfinal = $valor;
		}
		public function getIdFinal ( ) {
			return $this->idfinal;
		}

	
		public function setIdParentesco ( $valor ) {
			$this->idparentesco=$valor;
		}
		public function getIdParentesco ( ) {
			return $this->idparentesco;
		}
		
		public function setIdCargaFinal ( $valor ) {
			$this->idcarga_final = $valor ;
		}
		public function getIdCargaFinal ( ) {
			return $this->idcarga_final ;
		}
		
		public function setNombreContacto ( $valor ) {
			$this->nombre_contacto = $valor;
		}
		public function getNombreContacto ( ) {
			return $this->nombre_contacto;
		}
		
		public function setIdMotivoNoPago ( $valor ) {
			$this->idmotivo_no_pago = $valor;
		}
		public function getIdMotivoNoPago ( ) {
			return $this->idmotivo_no_pago;
		}
		
		public function setIdContacto ( $valor ) {
			$this->idcontacto = $valor;
		}
		public function getIdContacto ( ) {
			return $this->idcontacto;
		}
		
		public function setEnviarCampo ( $valor ) {
			$this->enviar_campo = $valor;
		}
		public function getEnviarCampo ( ) {
			return $this->enviar_campo;
		}
		
		public function setTmoInicio ( $valor ) {
			$this->tmo_inicio=$valor;
		}
		public function getTmoInicio ( ) {
			return $this->tmo_inicio;
		}
		
		public function setTmoFin ( $valor ) {
			$this->tmo_fin=$valor;
		}
		public function getTmoFin ( ) {
			return $this->tmo_fin;
		}
		
		public function setFecha ( $valor ) {
			$this->fecha=$valor;
		}
		public function getFecha ( ) {
			return $this->fecha;	
		}
		
		public function setIdTransaccion ( $valor ) {
			$this->idtransaccion=$valor;
		}
		public function getIdTransaccion ( ) {
			return $this->idtransaccion;	
		}

		public function setCallerId ( $valor ) {
			$this->caller_id = $valor;
		}
		public function getCallerId ( ) {
			return $this->caller_id ;
		}		
		
		public function setIdTelefono ( $valor ) {
			$this->idtelefono=$valor;
		}
		public function getIdTelefono ( ) {
			return $this->idtelefono;	
		}
		/***/
		public function setIdPesoTransaccion ( $valor ) {
			$this->idpeso_transaccion=$valor;
		}
		public function getIdPesoTransaccion ( ) {
			return $this->idpeso_transaccion;
		}
		/***/
		//public function setIdPesoLlamada ( $valor ) {
//			$this->idpeso_llamada=$valor;
//		}
//		public function getIdPesoLlamada ( ) {
//			return $this->idpeso_llamada;	
//		}
		
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

		public function setFechaCp($valor){
			$this->fechacp=$valor;
		}
		public function getFechaCp(){
			return $this->fechacp;
		}			

		public function setMontoCp($valor){
			$this->montocp=$valor;
		}
		public function getMontoCp(){
			return $this->montocp;
		}					

		public function setObservaciones($valor){
			$this->observaciones=$valor;
		}
		public function getObservaciones(){
			return $this->observaciones;
		}							
		 
	}

?>