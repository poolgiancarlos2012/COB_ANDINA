<?php
/**
 * Description of dto_cliente
 *
 * @author Davis
 */
	class dto_cliente {
		private $id;
		private $codigo;
		private $nombre;
		private $paterno;
		private $materno;
		private $dni;
		private $ruc;
		private $idservicio;
		private $numero_documento;
		private $tipo_documento;
	
		public function setId($valor){
			$this->id=$valor;
		}
		public function getId(){
			return $this->id;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio=$valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
		public function setNumeroDocumento ( $valor ) {
			$this->numero_documento=$valor;
		}
		public function getNumeroDocumento ( ) {
			return $this->numero_documento;
		}
		
		public function setTipoDocumento ( $valor ) {
			$this->tipo_documento=$valor;
		}
		public function getTipoDocumento ( ) {
			return $this->tipo_documento;
		}
	
		public function setCodigo($valor){
			$this->codigo=$valor;
		}
		public function getCodigo(){
			return $this->codigo;
		}
		
		public function setNombre($valor){
			$this->nombre=$valor;
		}
		public function getNombre(){
			return $this->nombre;
		}
	
		public function setPaterno($valor){
			$this->paterno=$valor;
		}
		public function getPaterno(){
			return $this->paterno;
		}
	
		public function setMaterno($valor){
			$this->materno=$valor;
		}
		public function getMaterno(){
		   return $this->materno;
		}
	
		public function setDni($valor){
			$this->dni=$valor;
		}
		public function getDni(){
			return $this->dni;
		}
	
		public function setRuc($valor){
			$this->ruc=$valor;
		}
		public function getRuc(){
			return $this->ruc;
		}
	}
?>
