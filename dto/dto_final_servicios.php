<?php

class dto_final_servicios {
    private $idfina_servicios;
    private $idservicio;
    private $idfinal;
    private $estado;
	private $prioridad;
	private $peso;
	private $efecto; 
    private $codigo;
    private $fechacreacion;
    private $fechamodificacion;
    private $usuariocreacion;
    private $usuariomodificacion;
    private $flg_volver_llamar;
	private $estado_observa;

	public function setEstadoObserva($value){
		$this->estado_observa=$value;
	}
	public function getEstadoObserva(){
		return $this->estado_observa;
	}

    public function setFlgVolverLlamar($value){
        $this->flg_volver_llamar=$value;
    }
    public function getFlgVolverLlamar(){
        return $this->flg_volver_llamar;
    }

    public function setId($value){
        $this->idfina_servicios=$value;
    }
    public function getId(){
        return $this->idfina_servicios;
    }
    
    public function setCodigo ( $valor ) {
        $this->codigo = $valor;
    }
    public function getCodigo ( ) {
        return $this->codigo ;
    }
	
	public function setPeso ( $value ) {
		$this->peso = $value;
	}
	public function getPeso ( ) {
		return $this->peso;
	}
	
	public function setEfecto ( $value ) {
		$this->efecto=$value;
	}
	public function getEfecto ( ) {
		return $this->efecto;
	}

    public function setIdServicio($value){
        $this->idservicio=$value;
    }
    public function getIdServicio(){
        return $this->idservicio;
    }
	
	public function setPrioridad ( $data ) {
		$this->prioridad=$data;
	}
	public function getPrioridad ( ) { 
		return $this->prioridad;
	}

    public function setIdFinal($value){
        $this->idfinal=$value;
    }
    public function getIdFinal(){
        return $this->idfinal;
    }

    public function setEstado($value){
        $this->estado=$value;
    }
    public function getEstado(){
        return $this->estado;
    }

    public function setFechaCreacion($value){
        $this->fechacreacion=$value;
    }
    public function getFechaCreacion(){
        return $this->fechacreacion;
    }

    public function setFechaModificacion($value){
        $this->fechamodificacion=$value;
    }
    public function getFechaModificacion(){
        return $this->fechamodificacion;
    }
    
    public function setUsuarioCreacion($value){
        $this->usuariocreacion=$value;
    }
    public function getUsuarioCreacion(){
        return $this->usuariocreacion;
    }

    public function setUsuarioModificacion($value){
        $this->usuariomodificacion=$value;
    }
    public function getUsuarioModificacion(){
        return $this->usuariomodificacion;
    }

}
?>
