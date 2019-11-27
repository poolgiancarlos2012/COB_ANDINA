<?php

	class dto_filtros {
		
		private $id;
		private $idtipo_filtro;
		private $idservicio;
		private $tabla;
		private $tabla_mostrar;
		private $campo;
		private $tipo_campo;
		private $fecha_creacion;
		private $usuario_creacion;
		private $fecha_modificacion;
		private $usuario_modificacion;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;	
		}
		
		public function setIdTipoFiltro ( $valor ) {
			$this->idtipo_filtro=$valor;
		}
		public function getIdTipoFiltro ( ) {
			return $this->idtipo_filtro;	
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;	
		}
		
		public function setTabla ( $valor ) {
			$this->tabla=$valor;
		}
		public function getTabla ( ) {
			return $this->tabla;	
		}
		
		public function setTablaMostrar ( $valor ) {
			$this->tabla_mostrar=$valor;
		}
		public function getTablaMostrar ( ) {
			return $this->tabla_mostrar;	
		}
		
		public function setCampo ( $valor ) {
			$this->campo=$valor;
		}
		public function getCampo ( ) {
			return $this->campo;	
		}
		
		public function setTipoCampo ( $valor ) {
			$this->tipo_campo=$valor;
		}
		public function getTipoCampo ( ) {
			return $this->tipo_campo;	
		}
		
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