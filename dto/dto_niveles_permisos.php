<?php
/**
 * Description of dto_niveles_permisos
 *
 * @author Davis
 */
class dto_niveles_permisos {
    private $id;
    private $nombreNivel;
    private $estado;

    public function setId($valor){
        $this->id=$valor;
    }
    public function getId(){
        return $this->id;
    }
    public function setNombreNivel($valor){
        $this->nombreNivel=$valor;
    }
    public function getNombreNivel(){
        return $this->nombreNivel;
    }
    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }
}
?>
