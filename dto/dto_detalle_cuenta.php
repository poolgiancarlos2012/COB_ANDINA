<?php


class dto_detalle_cuenta {
    private $iddetalleCuenta;
    private $idcuenta;
    private $diasMora;
	private $idcartera;
    private $totalDeuda;
    private $totalDeudaSoles;
    private $totalDeudaDolares;
    private $montoMora;
    private $montoMoraSoles;
    private $montoMoraDolares;
    private $saldoCapital;
    private $saldoCapitalSoles;
    private $saldoCapitalDolares;
    private $fechaAsignacion;
    private $tramo;
    private $codigoOperacion;
    private $moneda;
    private $fechaModificacion;
    private $usuarioModificacion;

    public function setId($valor){
            $this->iddetalleCuenta=$valor;
    }
    public function getId(){
        return $this->iddetalleCuenta;
    }
	
	public function setTramo ( $valor ) {
		$this->tramo=$valor;
	}
	public function getTramo ( ) {
		return $this->tramo;
	}
	
	public function setIdCartera ( $valor ) {
		$this->idcartera = $valor;
	}
	public function getIdCartera ( ) {
		return $this->idcartera;
	}
	
	public function setCodigoOperacion ( $valor ) {
		$this->codigoOperacion=$valor;
	}
	public function getCodigoOperacion ( ) {
		return $this->codigoOperacion;
	}
    public function setIdCuenta($valor){
        $this->idcuenta=$valor;
    }
    public function getIdCuenta(){
        return $this->idcuenta;
    }
    public function setDiasMora($valor){
        $this->diasMora=$valor;
    }
    public function getDiasMora(){
        return $this->diasMora;
    }
    public function setTotalDeuda($valor){
        $this->totalDeuda=$valor;
    }
    public function getTotalDeuda(){
        return $this->totalDeuda;
    }
    public function setTotalDeudaSoles($valor){
        $this->totalDeudaSoles=$valor;
    }
    public function getTotalDeudaSoles(){
        return $this->totalDeudaSoles;
    }
    
    public function setTotalDeudaDolares($valor){
        $this->totalDeudaDolares=$valor;
    }
    public function getTotalDeudaDolares(){
        $this->totalDeudaDolares;
    }

    public function setMontoMora($valor){
        $this->montoMora=$valor;
    }
    public function getMontoMora(){
        return $this->montoMora;
    }

    public function setMontoMoraSoles($valor){
        $this->montoMoraSoles=$valor;
    }
    public function getMontoMoraSoles(){
        return $this->montoMoraSoles;
    }
    public function setMontoMoraDolares($valor){
        $this->montoMoraDolares=$valor;
    }
    public function getMontoMoraDolares(){
        return $this->montoMoraDolares;
    }
    

}
?>
