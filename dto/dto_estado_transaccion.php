<?php
	
	class dto_estado_transaccion {
		
		private $id;
		private $nombre;
		private $idservicio;
		private $peso;
		private $descripcion;
		private $estado;
		private $idTipoTransaccion ;
		private $fechaCreacion;
		private $fechaModificacion;
		private $usuarioCreacion;
		private $usuarioModificacion;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setNombre ( $valor ) {
			$this->nombre=$valor;
		}
		public function getNombre ( ) {
			return $this->nombre;	
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;	
		}
		
		public function setPeso ( $valor ) {
			$this->peso=$valor;
		}
		public function getPeso ( ) {
			return $this->peso;
		}
		
		public function setDescripcion ( $valor ) {
			$this->descripcion=$valor;
		}
		public function getDescripcion ( ) {
			return $this->descripcion;
		}
		
		public function setEstado ( $valor ) {
			$this->estado=$valor;
		}
		public function getEstado ( ) {
			return $this->estado;	
		}
		
		public function setIdTipoTransaccion ( $valor ) {
			$this->idTipoTransaccion=$valor;
		}
		public function getIdTipoTransaccion ( ) {
			return $this->idTipoTransaccion;	
		}
		
		public function setFechaCreacion($valor){
			$this->fechaCreacion=$valor;
		}
		public function getFechaCreacion(){
			return $this->fechaCreacion;
		}
	
		public function setFechaModificacion($valor){
			$this->fechaModificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fechaModificacion;
		}
	
		public function setUsuarioCreacion($valor){
			$this->usuarioCreacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuarioCreacion;
		}
	
		public function setUsuarioModificacion($valor){
			$this->usuarioModificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuarioModificacion;
		}
			
	}

?>