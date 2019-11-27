<?php

    class dto_usuario_servicio {
        private $idusuario_servicio;
        private $idusuario;
        private $idservicio;
        private $idprivilegio;
        private $idtipo_usuario;
        private $fecha_inicio;
        private $fecha_fin;
        private $estado;
		private $anexo;
        private $fecha_creacion;
        private $usuario_creacion;
        private $fecha_modificacion;
        private $usuario_modificacion;

        public function setId ( $valor ) {
             $this->idusuario_servicio=$valor;
        }
        public function getId ( ) {
            return $this->idusuario_servicio;
        }
		
		public function setAnexo ( $valor ) {
			$this->anexo=$valor;
		}
		public function getAnexo ( ) {
			return $this->anexo;
		}

        public function setIdUsuario ( $valor ) {
            $this->idusuario=$valor;
        }
        public function getIdUsuario ( ) {
            return $this->idusuario;
        }

        public function setIdServicio ( $valor ) {
            $this->idservicio=$valor;
        }
        public function getIdServicio ( ) {
            return $this->idservicio;
        }

        public function setIdPrivilegio ( $valor ) {
            $this->idprivilegio=$valor;
        }
        public function getIdPrivilegio ( ) {
            return $this->idprivilegio;
        }

        public function setIdTipoUsuario ( $valor ) {
            $this->idtipo_usuario=$valor;
        }
        public function getIdTipoUsuario ( ) {
            return $this->idtipo_usuario;
        }

        public function setFechaInicio ( $valor ) {
            $this->fecha_inicio=$valor;
        }
        public function getFechaInicio ( ) {
            return $this->fecha_inicio;
        }

        public function setFechaFin ( $valor ) {
            $this->fecha_fin=$valor;
        }
        public function getFechaFin ( ) {
            return $this->fecha_fin;
        }

        public function setEstado ( $valor ) {
            $this->estado=$valor;
        }
        public function getEstado ( ) {
            return $this->estado;
        }

        public function setFechaCreacion ( $valor ) {
            $this->fecha_creacion=$valor;
        }
        public function getFechaCreacion ( ) {
            return $this->fecha_creacion;
        }

        public function setUsuarioCreacion ( $valor ) {
            $this->usuario_creacion=$valor;
        }
        public function getUsuarioCreacion ( ) {
            return $this->usuario_creacion;
        }

        public function setFechaModificacion ( $valor ) {
            $this->fecha_modificacion=$valor;
        }
        public function getFechaModificacion ( ) {
            return $this->fecha_modificacion;
        }

        public function setUsuarioModificacion ( $valor ) {
            $this->usuario_modificacion=$valor;
        }
        public function getUsuarioModificacion ( ) {
            return $this->usuario_modificacion;
        }

    }

?>
