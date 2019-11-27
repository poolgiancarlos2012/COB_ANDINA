<?php 
	
class dto_detalle_acuerdo_pago {
	private $iddetalle_acuerdo_pago;
	private $idacuerdo_pago;
	private $idcliente_cartera;
	private $estado;
	private $numero_pagare;
	private $numero_cuota;
	private $fecha_cuota;
	private $valor_cuota;
	private $usuario_creacion;
	private $fecha_creacion;
	private $usuario_modificacion;
	private $fecha_modificacion;
	private $descripcion;

	public function setIdDetalleAcuerdoPago($valor){
		$this->iddetalle_acuerdo_pago = $valor;
	}
	public function setIdAcuerdoPago($valor){
		$this->idacuerdo_pago = $valor;
	}
	public function setIdClienteCartera($valor){
		$this->idcliente_cartera = $valor;
	}
	public function setEstado($valor){
		$this->estado = $valor;
	}
	public function setNumeroPagare($valor){
		$this->numero_pagare = $valor;
	}
	public function setNumeroCuotas($valor){
		$this->numero_cuota = $valor;
	}
	public function setFechaAcuerdo($valor){
		$this->fecha_cuota = $valor;
	}
	public function setValorAcuerdo($valor){
		$this->valor_cuota = $valor;
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

	public function getIdDetalleAcuerdoPago(){
		return $this->iddetalle_acuerdo_pago;
	}
	public function getIdAcuerdoPago(){
		return $this->idacuerdo_pago;
	}
	public function getIdClienteCartera(){
		return $this->idcliente_cartera ;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function getNumeroPagare(){
		return $this->numero_pagare ;
	}
	public function getNumeroCuotas(){
		return $this->numero_cuota ;
	}
	public function getFechaAcuerdo(){
		return $this->fecha_cuota ;
	}
	public function getValorAcuerdo(){
		return $this->valor_cuota ;
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

