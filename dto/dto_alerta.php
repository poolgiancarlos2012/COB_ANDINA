<?php
/**
 * Description of bean_alerta
 *
 * @author Davis
 */
class dto_alerta {
    private $id;
    private $fecha_creacion;
    private $fecha_alerta;
    private $descripcion;
    private $estado;
    private $idCliente_cartera;
    private $fecha_modificacion;
    private $usuario_modificacion;
	private $usuario_creacion;
	private $usuario_servicio;
	private $idservicio;

    public function  __construct() {
        
    }
    public function setId($valor) {
        $this->id=$valor;
    }
    public function getId() {
        return $this->id;
    }
	
	public function setIdServicio ( $valor ) {
		$this->idservicio = $valor;
	}
	public function getIdServicio ( ) {
		return $this->idservicio;
	}
	
	public function setIdUsuarioServicio ( $valor ) {
		$this->usuario_servicio = $valor;
	}
	public function getIdUsuarioServicio ( ) {
		return $this->usuario_servicio ;
	}

    public function setFechaCreacion($valor) {
        $this->fecha_creacion=$valor;
    }
    public function getFechaCreacion() {
        return $this->fecha_alerta;
    }

    public function setFechaAlerta($valor) {
        $this->fecha_alerta=$valor;
    }
    public function getFechaAlerta() {
        return $this->fecha_alerta;
    }

    public function setDescripcion($valor) {
        $this->descripcion=$valor;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }

    public function setIdClienteCartera($valor){
        $this->idCliente_cartera=$valor;
    }
    public function getIdClienteCartera(){
        return $this->idCliente_cartera;
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
	
	public function setUsuarioCreacion($valor){
        $this->usuario_creacion=$valor;
    }
    public function getUsuarioCreacion(){
        return $this->usuario_creacion;
    }
}
?>
