<?php
/**
 * Description of dto_compromiso_pago
 *
 * @author Davis
 */
class dto_compromiso_pago {
    private $id;
    private $fechaCp;
    private $montoCp;
    private $estado;
    private $idTransaccion;
    private $observacion;
    private $fechaCreacion;
    private $usuarioCreacion;
    private $fechaModificacion;
    private $usuarioModificacion;


    public function setId($valor){
        $this->id=$valor;
    }
    public function getId(){
        return $this->id;
    }

    public function setFechaCp($valor){
        $this->fechaCp=$valor;
    }
    public function getFechaCp(){
        return $this->fechaCp;
    }

    public function setMontoCp($valor){
        $this->montoCp=$valor;
    }
    public function getMontoCp(){
        return $this->montoCp;
    }

    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }

    public function setIdTransaccion($valor){
        $this->idTransaccion=$valor;
    }
    public function getIdTransaccion(){
        return $this->idTransaccion;
    }

    public function setObservacion($valor){
       $this->observacion=$valor;
    }
    public function getObservacion(){
        return $this->observacion;
    }

    public function setFechaCreacion($valor){
        $this->fechaCreacion=$valor;
    }
    public function getFechaCreacion(){
        return $this->fechaCreacion;
    }
    
    public function setFechaModificacion($valor){
        $this->fechaModificacion=$valor;
    }
    public function getFechaModificacion(){
        return $this->fechaModificacion;
    }

    public function setUsuarioCreacion($valor){
        $this->usuarioCreacion=$valor;
    }
    public function getUsuarioCreacion(){
        return $this->usuarioCreacion;
    }
    
    public function setUsuarioModificacion($valor){
        $this->usuarioModificacion=$valor;
    }
    public function getUsuarioModificacion(){
        return $this->usuarioModificacion;
    }

}
?>
