<?php


class dto_servicio {
	private $id;
	private $nombre;
	private $descripcion;
	private $estado;
	private $usuario_creacion;
	private $fecha_creacion;
	private $fecha_modificacion;
	private $usuario_modificacion;
	
	public function setId($valor){
		$this->id=$valor;
	}
	public function getId(){
		return $this->id;
	}
	
	public function setNombre($valor){
		$this->nombre=$valor;
	}
	public function getNombre(){
		return $this->nombre;
	}
	
	public function setDescripcion($valor){
		$this->descripcion=$valor;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	
	public function setEstado($valor){
		$this->estado=$valor;
	}
	public function getEstado(){
		return $this->estado;
	}
	
	public function setUsuarioCreacion($valor){
		$this->usuario_creacion=$valor;
	}
	public function getUsuarioCreacion(){
		return $this->usuario_creacion;
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
	
	public function setUsuarioModificacion($valor){
		$this->usuario_modificacion=$valor;
	}
	public function getUsuarioModificacion(){
		return $this->usuario_modificacion;
	}
	
}

