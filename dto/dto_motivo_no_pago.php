<?php
	
	class dto_motivo_no_pago {
		
		private $id;
		private $idservicio;
		
		public function setId ( $valor ) {
			$this->id=$valor;
		}
		public function getId ( ) {
			return $this->id;
		}
		
		public function setIdServicio ( $valor ) {
			$this->idservicio = $valor;
		}
		public function getIdServicio ( ) {
			return $this->idservicio;
		}
		
	}
	
?>