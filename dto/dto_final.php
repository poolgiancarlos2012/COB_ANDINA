<?php

	class dto_final {
		private $idfinal;
		private $idtipo_final;
		private $idcarga_final;
		private $idclase_final;
		private $idnivel;
		private $nombre;
		private $descripcion;
		private $estado;
		private $fecha_creacion;
		private $fecha_modificacion;
		private $usuario_creacion;
		private $usuario_modificacion;
		
		public function setId ( $valor ) {
			$this->idfinal=$valor;
		}
		public function getId ( ) {
			return $this->idfinal;
		}
		
		public function setIdTipoFinal ( $valor ) {
			$this->idtipo_final=$valor;
		}
		public function getIdTipoFinal ( ) {
			return $this->idtipo_final;
		}
		
		public function setIdCargaFinal ( $valor ) {
			$this->idcarga_final=$valor;
		}
		public function getIdCargaFinal ( ) {
			return $this->idcarga_final;
		}
		
		public function setIdClaseFinal ( $valor ) {
			$this->idclase_final=$valor;
		}
		public function getIdClaseFinal ( ) {
			return $this->idclase_final;
		}
		
		public function setIdNivel ( $valor ) {
			$this->idnivel=$valor;
		}
		public function getIdNivel ( ) {
			return $this->idnivel;
		}
				
		public function setNombre ( $valor ) {
			 $this->nombre=$valor;
		}
		public function getNombre ( ) {
			return $this->nombre;
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
		
		public function setFechaCreacion($valor){
			$this->fecha_creacion=$valor;
		}
		public function getFechaCreacion(){
			return $this->fecha_creacion;
		}
	
		public function setFechaModificacion($valor){
			$this->fecha_modificacion=$valor;
		}
		public function getFechaModificacion(){
			return $this->fecha_modificacion;
		}
	
		public function setUsuarioCreacion($valor){
			$this->usuario_creacion=$valor;
		}
		public function getUsuarioCreacion(){
			return $this->usuario_creacion;
		}
	
		public function setUsuarioModificacion($valor){
			$this->usuario_modificacion=$valor;
		}
		public function getUsuarioModificacion(){
			return $this->usuario_modificacion;
		}

	}

?>