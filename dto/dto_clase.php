<?php
/**
 * Description of dto_clase
 *
 * @author Davis
 */
class dto_clase {
    private $id;
    private $nombre;
    private $descripcion;

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
}
?>
