<?php
/**
 * Description of dto_cuenta
 *
 * @author Davis
 */
class dto_cuenta {
    private $idcuenta;
    private $idclienteCartera;
    private $numeroCuenta;
	private $moneda;
    private $totalDeuda;
    private $estado;
	private $idcartera;
	private $codigo_cliente;
    private $fechaModificacion;
    private $usuarioModificacion;


    public function setId($valor){
        $this->idcuenta=$valor;
    }
    public function getId(){
        return $this->idcuenta;
    }
	
	public function setMoneda ( $valor ) {
		$this->moneda=$valor;
	}
	public function getMoneda ( ) {
		return $this->moneda;
	}
	
	public function setIdCartera ( $valor ) {
		$this->idcartera=$valor;
	}
	public function getIdCartera ( ) {
		return $this->idcartera;
	}
	
	public function setCodigoCliente ( $valor ) {
		$this->codigo_cliente=$valor;
	}
	public function getCodigoCliente ( ) {
		return $this->codigo_cliente;
	}
	
    public function setIdClienteCartera($valor){
        $this->idclienteCartera=$valor;
    }
    public function getIdClienteCartera(){
        return $this->idclienteCartera;
    }
    public function setNumeroCuenta($valor){
        $this->numeroCuenta=$valor;
    }
    public function getNumeroCuenta(){
        return $this->numeroCuenta;
    }
    public function setTotalDeuda($valor){
        $this->totalDeuda=$valor;
    }
    public function getTotalDeuda(){
        return $this->totalDeuda;
    }
    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }
    public function setFechaModificacion($valor){
        $this->fechaModificacion=$valor;
    }
    public function getFechaModificacion(){
        return $this->fechaModificacion;
    }
    public function setUsuarioModificacion($valor){
        $this->usuarioModificacion=$valor;
    }
    public function getUsuarioModificacion(){
        return $this->usuarioModificacion;
    }




}
?>
