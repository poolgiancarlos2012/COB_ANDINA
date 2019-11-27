<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dto_permisos
 *
 * @author Davis
 */
class dto_permisos_detalle {
    private $id;
    private $idPermiso;
    private $idUrl;
    private $estado;

    public function setEstado($valor){
        $this->estado=$valor;
    }
    public function getEstado(){
        return $this->estado;
    }
    
    public function setId($valor){
        $this->id=$valor;
    }
    public function getId(){
        return $this->id;
    }
    public function setIdPermiso($valor){
        $this->idPermiso=$valor;
    }
    public function getIdPermiso(){
        return $this->idPermiso;
    }
    public function setIdUrl($valor){
        $this->idUrl=$valor;
    }
    public function getIdUrl(){
        return $this->idUrl;
    }   
}
?>
