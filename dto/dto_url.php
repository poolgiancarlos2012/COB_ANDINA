<?php
/**
 * Description of dto_url
 *
 * @author Davis
 */
class dto_url {
    private $id;
    private $nombreUrl;
    private $estado;
    private $idMenu;

    public function setId($valor){
        $this->id=$valor;
    }
    public function getId(){
        return $this->id;
    }
    public function setNombreUrl($valor){
        $this->nombreUrl=$valor;
    }
    public function getNombreUrl(){
        return $this->nombreUrl;
    }
    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }
    public function setIdMenu($valor){
        $this->idMenu=$valor;
    }
    public function getIdMenu(){
        return $this->idMenu;
    }
}
?>
