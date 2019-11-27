<?php 
	
class dto_acuerdo_pago {
	private $idacuerdo_pago;
	private $idcliente_cartera;
	private $idcuenta;
	private $estado;
	private $numero_pagare;
	private $numero_cuotas;
	private $fecha_acuerdo;
	private $valor_acuerdo;
	private $usuario_creacion;
	private $fecha_creacion;
	private $usuario_modificacion;
	private $fecha_modificacion;
	private $descripcion;

	public function setIdAcuerdoPago($valor){
		$this->idacuerdo_pago = $valor;
	}
	public function setIdClienteCartera($valor){
		$this->idcliente_cartera = $valor;
	}
	public function setIdCuenta($valor){
		$this->idcuenta = $valor;
	}
	public function setEstado($valor){
		$this->estado = $valor;
	}
	public function setNumeroPagare($valor){
		$this->numero_pagare = $valor;
	}
	public function setNumeroCuotas($valor){
		$this->numero_cuotas = $valor;
	}
	public function setFechaAcuerdo($valor){
		$this->fecha_acuerdo = $valor;
	}
	public function setValorAcuerdo($valor){
		$this->valor_acuerdo = $valor;
	}
	public function setUsuarioCreacion($valor){
		$this->usuario_creacion = $valor;
	}
	public function setFechaCreacion($valor){
		$this->fecha_creacion = $valor;
	}
	public function setUsuarioModificacion($valor){
		$this->usuario_modificacion = $valor;
	}
	public function setFechaModificacion($valor){
		$this->fecha_modificacion = $valor;
	}
	public function setDescripcion($valor){
		$this->descripcion = $valor;
	}

	public function getIdAcuerdoPago(){
		return $this->idacuerdo_pago;
	}
	public function getIdClienteCartera(){
		return $this->idcliente_cartera ;
	}
	public function getIdCuenta(){
		return $this->idcuenta ;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function getNumeroPagare(){
		return $this->numero_pagare ;
	}
	public function getNumeroCuotas(){
		return $this->numero_cuotas ;
	}
	public function getFechaAcuerdo(){
		return $this->fecha_acuerdo ;
	}
	public function getValorAcuerdo(){
		return $this->valor_acuerdo ;
	}
	public function getUsuarioCreacion(){
		return $this->usuario_creacion ;
	}
	public function getFechaCreacion(){
		return $this->fecha_creacion ;
	}
	public function getUsuarioModificacion(){
		return $this->usuario_modificacion ;
	}
	public function getFechaModificacion(){
		$this->fecha_modificacion ;
	}
	public function getDescripcion(){
		return $this->descripcion ;
	}
	
}





?>

