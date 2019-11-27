<?php

class dto_usuario {
	private $id;
	private $nombgre;
	private $paetrno;
	private $materno;
	private $dni;
	private $email;
	private $codigo;
	private $celular;
	private $telefono;
	private $telefono2;
	private $direccion;
	private $clave;
	private $estado;
	private $img_avatar ;
	private $fecha_nacimiento ;
	private $tipo_trabajo ;
	private $estado_civil ;
	private $genero ;
	private $is_planilla ;
	private $fecha_creacion;
	private $fecha_modificacion;
	private $usuario_creacion;
	private $usuario_modificacion;
	
	public function setId($valor) {
        $this->id=$valor;
    }
    public function getId() {
        return $this->id;
    }
	
	public function setIsPlanilla ( $valor ) {
		$this->is_planilla = $valor;
	}
	
	public function getIsPlanilla ( ) {
		return $this->is_planilla ;
	}
	
	public function setGenero ( $valor ) {
		$this->genero = $valor;
	}
	public function getGenero ( ) {
		return $this->genero ;
	}
	
	public function setEstadoCivil ( $valor ) {
		$this->estado_civil = $valor ;
	}
	public function getEstadoCivil ( ) {
		return $this->estado_civil ;
	}
	
	public function setTipoTrabajo ( $valor ) {
		$this->tipo_trabajo = $valor ;
	}
	public function getTipoTrabajo ( ) {
		return $this->tipo_trabajo ;
	}
	
	public function setFechaNacimiento ( $valor ) {
		$this->fecha_nacimiento = $valor ;
	}
	public function getFechaNacimiento ( ) {
		return $this->fecha_nacimiento ;
	}
	
	public function setCodigo ( $valor ) {
		$this->codigo = $valor;
	}
	public function getCodigo ( ) {
		return $this->codigo ;
	}
	
	public function setCelular ( $valor ) {
		$this->celular;
	}
	public function getCelular ( ) {
		return $this->celular;
	}
	
	public function setTelefono ( $valor ) {
		$this->telefono = $valor;
	}
	public function getTelefono ( ) {
		return $this->telefono ;
	}
	
	public function setTelefono2 ( $valor ) {
		$this->telefono2 = $valor;
	}
	public function getTelefono2 ( ) {
		return $this->telefono2;
	}
	
	public function setDireccion ( $valor ) {
		$this->direccion = $valor;
	}
	public function getDireccion ( ) {
		return $this->direccion;
	}
	
	public function setImgAvatar ( $valor ) {
		$this->img_avatar = $valor;
	}
	public function getImgAvatar ( ) {
		return $this->img_avatar;
	}
	
	public function setNombre ( $valor ) {
		$this->nombre=$valor;
	}
	public function getNombre ( ) {
		return $this->nombre;
	}
	
	public function setPaterno ( $valor ) {
		$this->paterno=$valor;
	}
	public function getPaterno ( ) {
		return $this->paterno;
	}
	
	public function setMaterno ( $valor ) {
		$this->materno=$valor ;
	}
	public function getMaterno ( ) {
		return $this->materno ;
	}

        public function setDni ( $valor ) {
                $this->dni=$valor;
        }
        public function getDni ( ) {
            return $this->dni;
        }
	
	public function setEmail ( $valor ) {
		$this->email=$valor;
	}
	public function getEmail ( ) {
		return $this->email;
	}
	
	public function setClave ( $valor ) {
		$this->clave=$valor;
	}
	public function getClave ( ) {
		return $this->clave ;
	}

        public function setEstado ( $valor ) {
            $this->estado=$valor;
        }
        public function getEstado ( ) {
            return $this->estado;
        }
	
	public function setUsuarioCreacion($valor){
		$this->usuario_creacion=$valor;
	}
	public function getUsuarioCreacion(){
		return $this->usuario_creacion;
	}
	
	public function setFechaCreacion($valor){
		$this->fecha_creacion=$valor;
	}
	public function getFechaCreacion(){
		return $this->fecha_creacion;
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
	
}


?>