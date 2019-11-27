<?php

class dto_cartera {
    private $id;
    private $idCampania;
    private $fechaCarga;
    private $cantidad;
    private $nuevos;
    private $retiros;
    private $fechaModificacion;
    private $fechaCreacion;
    private $usuarioModificacion;
    private $usuarioCreacion;
	private $meta_cliente;
	private $meta_cuenta;
    private $meta_monto;
	private $tramo;
	private $fecha_inicio;
	private $fecha_fin;
    private $nombre_cartera;

    public function setId($valor) {
        $this->id=$valor;
    }
    public function getId() {
        return $this->id;
    }
    
    public function setNombreCartera ( $valor ) {
        $this->nombre_cartera = $valor;
    }
    public function getNombreCartera ( ) {
        return $this->nombre_cartera ;
    }
	
	public function setFechaFin ( $valor ) {
		$this->fecha_fin = $valor;
	}
	public function getFechaFin ( ) {
		return $this->fecha_fin;
	}
	
	public function setFechaInicio ( $valor ) {
		$this->fecha_inicio = $valor;
	}
	public function getFechaInicio ( ) {
		return $this->fecha_inicio;
	}
		
	public function setMetaCuenta ( $valor ) {
		$this->meta_cuenta = $valor;
	}
	public function getMetaCuenta ( ) {
		return $this->meta_cuenta;
	}
	
	public function setMetaCliente ( $valor ) {
		$this->meta_cliente = $valor;
	}
	public function getMetaCliente ( ) {
		return $this->meta_cliente;
	}
    
    public function setMetaMonto ( $valor ) {
        $this->meta_monto = $valor;
    }
    public function getMetaMonto ( ) {
        return $this->meta_monto;
    }
	
	public function setTramo ( $valor ) {
		$this->tramo=$valor;
	}
	public function getTramo ( ) {
		return $this->tramo; 
	}

    public function setIdCampania($valor) {
        $this->idCampania=$valor;
    }
    public function getIdCampania() {
        return $this->idCampania;
    }

    public function setFechaCarga($valor){
        $this->fechaCarga=$valor;
    }
    public function getFechaCarga(){
        return $this->fechaCarga;
    }

    public function setCantidad($valor){
        $this->cantidad=$valor;
    }
    public function getCantidad(){
        return $this->cantidad;
    }

    public function setNuevos($valor){
        $this->nuevos=$valor;
    }
    public function getNuevos(){
        return $this->nuevos;
    }

    public function setRetiros($valor){
        $this->retiros=$valor;
    }
    public function getRetiros(){
        return $this->retiros;
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
    }
}
?>
