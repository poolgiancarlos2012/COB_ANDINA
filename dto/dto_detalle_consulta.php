<?php

	class dto_detalle_consulta {
		
		private $id;
		private $idconsulta;
		private $consulta;
		private $respuesta; 
		private $fecha_creacion;
		private $fecha_modificacion;
	    private $usuario_modificacion;
		private $usuario_creacion;

		
		public function setId ($valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setIdConsulta ( $valor ) {
			$this->idconsulta=$valor;
		}
		public function getIdConsulta ( ) {
			return $this->idconsulta; 
		}
		
		public function setConsulta ( $valor ) {
			$this->consulta=$valor;
		}
		public function getConsulta ( ) {
			return $this->consulta;
		}
		
		public function setRespuesta ( $valor ) {
			$this->respuesta=$valor;
		}
		public function getRespuesta ( ) {
			return $this->respuesta; 
		}
		
		public function setFechaConsulta ( $valor ) {
			$this->fecha_consulta=$valor;
		}
		public function getFechaConsulta ( ) {
			return $this->fecha_consulta;
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;
		}
		
		public function setAceptar ( $valor ) {
			$this->aceptar=$valor;
		}
		public function getAceptar ( ) {
			return $this->aceptar;
		}
		
		public function setFechaCreacion($valor) {
			$this->fecha_creacion=$valor;
		}
		public function getFechaCreacion() {
			return $this->fecha_alerta;
		}
		
		public function setFechaModificacion($valor){
			$this->fecha_modificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fecha_modificacion;
		}
	
		public function setUsuarioModificacion($valor){
			$this->usuario_modificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuario_modificacion;
		}
		
		public function setUsuarioCreacion($valor){
			$this->usuario_creacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuario_creacion;
		}	
		
	}

?>