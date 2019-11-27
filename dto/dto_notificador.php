<?php

	class dto_notificador {
		
		private $id;
		private $codigo;
		private $nombre;
		private $paterno;
		private $materno;
		private $telefono;
		private $direccion;
		private $correo;
		private $idservicio;
		private $usuario_creacion;
		private $usuario_modificacion;
		
		public function setId($valor){
			$this->id=$valor;
		}
		public function getId(){
			return $this->id;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
		public function setNombre($valor){
			$this->nombre=$valor;
		}
		public function getNombre(){
			return $this->nombre;
		}
	
		public function setPaterno($valor){
			$this->paterno=$valor;
		}
		public function getPaterno(){
			return $this->paterno;
		}
	
		public function setMaterno($valor){
			$this->materno=$valor;
		}
		public function getMaterno(){
		   return $this->materno;
		}
		
		public function setCorreo ( $valor ) {
			$this->correo = $valor;
		}
		public function getCorreo ( ) {
			return $this->correo;
		}
		
		public function setTelefono ( $valor ) {
			$this->telefono = $valor;
		}
		public function getTelefono ( ) {
			return $this->telefono;
		}
		
		public function setDireccion ( $valor ) {
			$this->direccion = $valor ;
		}
		public function getDireccion ( ) {
			return $this->direccion;
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